<?php

namespace IgnitionWolf\API\Controllers;

use DateTime;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Mail\Message;

use IgnitionWolf\API\Controllers\BaseController;
use IgnitionWolf\API\Exceptions\EntityNotFoundException;
use IgnitionWolf\API\Exceptions\FailedLoginException;
use IgnitionWolf\API\Services\RequestValidator;
use Tymon\JWTAuth\Facades\JWTAuth;

use IgnitionWolf\API\Services\UserVerificationService;
use IgnitionWolf\API\Entity\Authenticatable;

use Flugg\Responder\Http\Responses\SuccessResponseBuilder;
use Flugg\Responder\Http\Responses\ErrorResponseBuilder;

class AuthenticateController extends BaseController
{
    /**
     * Points to the user entity to be handled in the controller.
     *
     * @psalm-var class-string
     * @var string
     */
    protected $entity;

    /**
     * @var UserVerificationService
     */
    protected $userVerification;

    public function __construct(UserVerificationService $userVerification)
    {
        $this->entity = config('api.user.model', \Modules\User\Entities\User::class);
        $this->userVerification = $userVerification;
    }

    /**
     * Register the user.
     *
     * @param Request $request
     * @return SuccessResponseBuilder
     */
    public function register(Request $request): SuccessResponseBuilder
    {
        // This will call RegisterRequest
        RequestValidator::validate($request, $this->entity, 'register');

        $password = Hash::make($request->password);

        /**
         * @var Authenticatable
         */
        $entity = new $this->entity;

        /**
         * Fill the entity data
         */
        $data = $request->only($entity->getFillable());
        $entity->fill($data);
        
        if (method_exists($entity, 'automap')) {
            $entity->automap();
        }

        $entity->password = $password;
        $entity->save();

        $relationshipData = $request->only($entity->getRelationships());
        $entity->fillRelationships($relationshipData);

        if (config('api.user.verifications', true)) {
            $this->userVerification->createToken($entity);
        }

        $entity->save();
        return $this->success($entity);
    }

    /**
     * Verify the user e-mail token.
     *
     * @param string $verificationCode
     * @return SuccessResponseBuilder
     */
    public function verifyUser($verificationCode): SuccessResponseBuilder
    {
        $verification = $this->userVerification->verify($verificationCode);
        return $this->success($verification);
    }

    /**
     * Login the user by comparing passwords and providing a JWT token.
     *
     * @param Request $request
     * @return SuccessResponseBuilder
     */
    public function login(Request $request)
    {
        // This will call RegisterEntityRequest
        RequestValidator::validate($request, $this->entity, 'login');
        
        $credentials = $request->only('email', 'password');
        if (!$token = JWTAuth::attempt($credentials)) {
            throw new FailedLoginException;
        }

        return $this->success(['token' => $token]);
    }

    /**
     * Check if an user is logged in.
     *
     * @param Request $request
     * @return SuccessResponseBuilder|ErrorResponseBuilder
     */
    public function check(Request $request)
    {
        if ($this->getCurrentUser()) {
            return $this->success();
        }
        return $this->error();
    }

    /**
     * Log out the user by invalidating the JWT token.
     *
     * @return SuccessResponseBuilder
     */
    public function logout(): SuccessResponseBuilder
    {
        $user = $this->getCurrentUser();
        JWTAuth::invalidate($user);
        return $this->success();
    }
    
    /**
    * Send the password reset link via e-mail.
    *
    * @param Request $request
    * @throws EntityNotFoundException
    * @return SuccessResponseBuilder
     */
    public function recover(Request $request): SuccessResponseBuilder
    {
        $user = $this->entity::where('email', $request->email)->first();
        if (!$user) {
            throw new EntityNotFoundException;
        }

        Password::sendResetLink($request->only('email'), function (Message $message) {
            $message->subject('Your Password Reset Link');
        });

        return $this->success();
    }
}

<?php

namespace IgnitionWolf\API\Controllers;

use DateTime;
use Exception;
use Flugg\Responder\Facades\Transformation;
use IgnitionWolf\API\Events\UserSocialRegistered;
use IgnitionWolf\API\Exceptions\VerificationCodeException;
use IgnitionWolf\API\Exceptions\WrongLoginMethodException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Mail\Message;

use IgnitionWolf\API\Exceptions\EntityNotFoundException;
use IgnitionWolf\API\Exceptions\FailedLoginException;
use IgnitionWolf\API\Services\RequestValidator;
use Laravel\Socialite\Facades\Socialite;
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
        $this->entity = config('api.user.model');
        $this->userVerification = $userVerification;
    }

    /**
     * Register the user.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function register(Request $request): JsonResponse
    {
        // This will call RegisterRequest
        RequestValidator::validate($request, $this->entity, 'register');

        /**
         * @var Authenticatable
         */
        $user = new $this->entity;

        /**
         * Fill the entity data
         */
        $data = $request->only($user->getFillable());
        $user->fill($data);

        if (method_exists($user, 'automap')) {
            $user->automap();
        }

        $user->password = Hash::make($request->get('password'));
        $user->save();

        $relationshipData = $request->only($user->getRelationships());
        $user->fillRelationships($relationshipData);

        if (config('api.user.verifications', true)) {
            $this->userVerification->createToken($user);
        }

        $user->save();

        $token = JWTAuth::fromUser($user);

        if (method_exists($user, 'transformer') && $user->transformer()) {
            $user = Transformation::make($user)->transform();
        }

        return $this->success(['token' => $token, 'user' => $user]);
    }

    /**
     * Login the user by comparing passwords and providing a JWT token.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws FailedLoginException
     * @throws WrongLoginMethodException
     * @throws Exception
     */
    public function login(Request $request)
    {
        // This will call RegisterEntityRequest
        RequestValidator::validate($request, $this->entity, 'login');

        $credentials = $request->only('email', 'password');

        $user = $this->entity::where(['email' => $credentials['email']])->first();
        if ($user) {
            if (!$user->password && $user->registration_source !== 'email') {
                throw new WrongLoginMethodException;
            }
        }

        if (!$token = JWTAuth::attempt($credentials)) {
            throw new FailedLoginException;
        }

        if (method_exists($user, 'transformer') && $user->transformer()) {
            $user = Transformation::make($user)->transform();
        }

        return $this->success(['token' => $token, 'user' => $user]);
    }

    /**
     * Login or register the user via a third party.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function social(Request $request)
    {
        RequestValidator::validate($request, $this->entity, 'social');

        $provider = $request->get('provider');
        $token = $request->get('token');

        $data = Socialite::driver($provider)->userFromToken($token);

        if (!$user = $this->entity::where(['email' => $data->email])->first()) {
            $user = new $this->entity;
            $user->email = $data->email;
            $user->name = $data->name;
            $user->registration_source = $provider;
            $user->save();

            event(new UserSocialRegistered($user, $data));
        }

        $token = JWTAuth::fromUser($user);

        if (method_exists($user, 'transformer') && $user->transformer()) {
            $user = Transformation::make($user)->transform();
        }

        return $this->success(['token' => $token, 'user' => $user]);
    }

    /**
     * Check if an user is logged in.
     *
     * @return JsonResponse
     */
    public function check()
    {
        if ($user = $this->getCurrentUser()) {
            return $this->success($user);
        }
        return $this->error();
    }

    /**
     * Log out the user by invalidating the JWT token.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $user = $this->getCurrentUser();
        JWTAuth::invalidate($user);
        return $this->success();
    }

    /**
    * Send the password reset link via e-mail.
    *
    * @param Request $request
    * @return JsonResponse
     *@throws EntityNotFoundException
     */
    public function recover(Request $request): JsonResponse
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

    /**
     * Verify the user e-mail token.
     *
     * @param string $verificationCode
     * @return JsonResponse
     * @throws VerificationCodeException
     */
    public function verifyUser($verificationCode): JsonResponse
    {
        $verification = $this->userVerification->verify($verificationCode);
        return $this->success($verification);
    }
}

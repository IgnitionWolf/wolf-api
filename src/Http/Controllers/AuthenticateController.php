<?php

namespace IgnitionWolf\API\Http\Controllers;

use Exception;
use Flugg\Responder\Facades\Transformation;
use IgnitionWolf\API\Events\UserAuthenticated;
use IgnitionWolf\API\Events\UserLoggedIn;
use IgnitionWolf\API\Events\UserRegistered;
use IgnitionWolf\API\Exceptions\WrongLoginMethodException;
use IgnitionWolf\API\Traits\FillsDataFromRequest;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Hash;

use IgnitionWolf\API\Exceptions\FailedLoginException;
use IgnitionWolf\API\Services\EntityRequestValidator;
use Laravel\Socialite\Facades\Socialite;

use IgnitionWolf\API\Services\UserVerificationService;

class AuthenticateController extends BaseController
{
    use FillsDataFromRequest;

    /**
     * Points to the user model to be handled in the controller.
     *
     * @psalm-var class-string
     * @var string
     */
    protected string $model;

    protected UserVerificationService $userVerification;

    public function __construct(UserVerificationService $userVerification)
    {
        $this->model = config('api.user.model');
        $this->userVerification = $userVerification;
    }

    /**
     * Register the user.
     *
     * @return JsonResponse
     * @throws BindingResolutionException
     * @throws Exception
     */
    public function register(): JsonResponse
    {
        $request = $this->validate('register');

        $user = new $this->model;
        $user->password = Hash::make($request->get('password'));

        $this->fillFromRequest($request, $user);

        if (config('api.user.verifications', true)) {
            $this->userVerification->createToken($user);
        }

        $user->save();

        UserAuthenticated::dispatch($user);
        UserRegistered::dispatch($user);

        $token = auth()->tokenById($user->id);

        if (method_exists($user, 'transformer') && $user->transformer()) {
            $user = Transformation::make($user)->transform();
        }

        return $this->success(['token' => $token, 'user' => $user]);
    }

    /**
     * Login the user by comparing passwords and providing a JWT token.
     *
     * @return JsonResponse
     * @throws FailedLoginException
     * @throws WrongLoginMethodException
     * @throws Exception
     */
    public function login(): JsonResponse
    {
        $request = $this->validate('login');

        $credentials = $request->only('email', 'password');

        $user = $this->model::where(['email' => $credentials['email']])->first();
        if ($user) {
            if (!$user->password && $user->registration_source !== 'email') {
                throw new WrongLoginMethodException;
            }
        }

        if (!$token = auth()->attempt($credentials)) {
            throw new FailedLoginException;
        }

        UserLoggedIn::dispatch($user);

        if (method_exists($user, 'transformer') && $user->transformer()) {
            $user = Transformation::make($user)->transform();
        }

        return $this->success(['token' => $token, 'user' => $user]);
    }

    /**
     * Login or register the user via a third party.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function social(): JsonResponse
    {
        $request = $this->validate('social');

        $provider = $request->get('provider');
        $token = $request->get('token');

        $data = Socialite::driver($provider)->userFromToken($token);

        if (!$user = $this->model::where(['email' => $data->email])->first()) {
            $user = new $this->model;
            $user->email = $data->email;
            $user->name = $data->name;
            $user->registration_source = $provider;
            $user->save();

            UserRegistered::dispatch($user, $data);
        } else {
            UserLoggedIn::dispatch($user);
        }

        UserAuthenticated::dispatch($user);

        $token = auth()->tokenById($user->id);

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
    public function check(): JsonResponse
    {
        $user = auth()->user();
        if (method_exists($user, 'transformer') && $user->transformer()) {
            $user = Transformation::make($user)->transform();
        }

        return $this->success([
            'authenticated' => !empty($user),
            'user' => $user ?? (object) []
        ]);
    }

    /**
     * Log out the user by invalidating the JWT token.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();
        return $this->success();
    }

    /**
     * Wrapper function to validate a CRUD request.
     *
     * @param string $action
     * @return FormRequest
     * @throws Exception
     */
    private function validate(string $action): FormRequest
    {
        return app()->make(EntityRequestValidator::class)->validate(request(), $this->model, $action);
    }
}

<?php

namespace IgnitionWolf\API\Services;

use DateTime;
use IgnitionWolf\API\Exceptions\VerificationCodeException;
use IgnitionWolf\API\Entity\Authenticatable;
use Illuminate\Support\Facades\DB;
use Modules\User\Entities\User;

class UserVerificationService
{
    /**
     * The authenticatable model to be used.
     *
     * @psalm-var class-string
     * @var string
     */
    protected $entity;

    public function __construct($entity = null)
    {
        $this->entity = config('api.user.model', \Modules\User\Entities\User::class);
    }

    /**
     * Verify an user e-mail with provided token.
     *
     * @param string $token
     * @return User
     */
    public function verify(string $token): Authenticatable
    {
        $verification = DB::table('user_verifications')->where(['token' => $token])->first();

        if (!$verification) {
            throw new VerificationCodeException(['VERIFICATION_CODE_INVALID', 'This verification code is invalid']);
        }

        $user = $this->entity::find($verification->user_id);

        if (!$user) {
            throw new VerificationCodeException(['VERIFICATION_CODE_INVALID_USER', 'This verification code is not assigned to any user']);
        }

        if ($user->email_verified_at) {
            throw new VerificationCodeException(['VERIFICATION_CODE_VERIFIED', 'This user is already verified']);
        }

        $user->email_verified_at = new DateTime;
        $user->save();

        $this->delete($verification->token);
        return $user;
    }

    /**
     * Delete a token from the dabatase.
     *
     * @param string $token
     * @return void
     */
    public function delete(string $token)
    {
        DB::table('user_verifications')->where(['token' => $token])->delete();
    }

    /**
     * Create a token and assign it to an user.
     *
     * @param User $user
     * @return void
     */
    public function createToken(Authenticatable $user): void
    {
        DB::table('user_verifications')->insert([
            'user_id' => $user->id,
            'token' => $this->generateToken()
        ]);
    }

    /**
     * Generate random string to issue verification tokens.
     *
     * @return string
     **/
    private function generateToken()
    {
        return \Str::random(10);
    }
}

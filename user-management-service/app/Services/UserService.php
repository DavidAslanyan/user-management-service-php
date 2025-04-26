<?php

namespace App\Services;

use App\Models\User;
use App\Models\AccessToken;
use App\Models\RefreshToken;
use App\Models\Device;
use App\Repositories\UserRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\VerificationCodeRepository;
use App\Exceptions\InvalidPasswordException;
use App\Exceptions\InvalidAccessException;
use App\Exceptions\UserIsDeletedException;
use App\Providers\EmailProvider;
use App\Providers\SmsProvider;

class UserService
{
    protected $userRepository;
    protected $deviceRepository;
    protected $verificationCodeRepository;
    protected $emailProvider;
    protected $smsProvider;

    public function __construct(
        UserRepository $userRepository,
        DeviceRepository $deviceRepository,
        VerificationCodeRepository $verificationCodeRepository,
        EmailProvider $emailProvider,
        SmsProvider $smsProvider
    ) {
        $this->userRepository = $userRepository;
        $this->deviceRepository = $deviceRepository;
        $this->verificationCodeRepository = $verificationCodeRepository;
        $this->emailProvider = $emailProvider;
        $this->smsProvider = $smsProvider;
    }

    public function login(array $loginData)
    {
        $user = $this->userRepository->findByEmail($loginData['email']);

        if (!$user || !password_verify($loginData['password'], $user->password)) {
            throw new InvalidPasswordException('Invalid credentials');
        }

        if ($user->is_deleted) {
            throw new UserIsDeletedException('User account is deleted');
        }

        // Generate tokens, save devices, etc.
        $accessToken = $this->createAccessToken($user);
        $refreshToken = $this->createRefreshToken($user);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ];
    }

    public function createAccessToken(User $user)
    {
        $accessToken = AccessToken::create([
            'user_id' => $user->id,
            'token' => bin2hex(random_bytes(32)),
            'expires_at' => now()->addHours(1),
        ]);

        return $accessToken->token;
    }

    public function createRefreshToken(User $user)
    {
        $refreshToken = RefreshToken::create([
            'user_id' => $user->id,
            'token' => bin2hex(random_bytes(32)),
            'expires_at' => now()->addDays(30),
        ]);

        return $refreshToken->token;
    }

    public function register(array $registrationData)
    {
        $user = $this->userRepository->create($registrationData);

        $this->sendWelcomeMessage($user);

        return $user;
    }

    public function sendWelcomeMessage(User $user)
    {
        if ($user->is_sms_verified) {
            $this->smsProvider->sendVerificationCode($user->phone);
        } else {
            $this->emailProvider->sendWelcomeEmail($user->email);
        }
    }

    public function updateUserProfile(User $user, array $updateData)
    {
        $updatedUser = $this->userRepository->update($user, $updateData);
        return $updatedUser;
    }

    public function logout(User $user)
    {
        $this->deviceRepository->deleteUserDevices($user->id);
        $this->accessTokenRepository->deleteUserTokens($user->id);

        return true;
    }
}

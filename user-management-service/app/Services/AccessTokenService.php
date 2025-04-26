<?php

namespace App\Services;

use App\Models\Device;
use App\Models\RefreshToken;
use App\Models\AccessToken;
use App\Models\User;
use App\Repositories\AccessTokenRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\RefreshTokenRepository;
use App\Repositories\UserRepository;
use App\Exceptions\RefreshTokenNotFoundException;

class AccessTokenService
{
    protected $userRepository;
    protected $accessTokenRepository;
    protected $deviceRepository;
    protected $refreshTokenRepository;

    public function __construct(
        UserRepository $userRepository,
        AccessTokenRepository $accessTokenRepository,
        DeviceRepository $deviceRepository,
        RefreshTokenRepository $refreshTokenRepository
    ) {
        $this->userRepository = $userRepository;
        $this->accessTokenRepository = $accessTokenRepository;
        $this->deviceRepository = $deviceRepository;
        $this->refreshTokenRepository = $refreshTokenRepository;
    }

    /**
     * Get validated access token.
     *
     * @param  string  $refreshToken
     * @param  array  $deviceDto
     * @return array
     * @throws \App\Exceptions\RefreshTokenNotFoundException
     */
    public function getValidatedToken(string $refreshToken, array $deviceDto): array
    {
        $existingRefreshToken = $this->refreshTokenRepository->getActiveByToken($refreshToken);

        $device = $this->deviceRepository->getByIdentifier($deviceDto['deviceIdentifier']);

        if ($existingRefreshToken && $existingRefreshToken->isTokenExpired()) {
            $existingRefreshToken->updateActiveStatus(false);
            $this->refreshTokenRepository->update($existingRefreshToken);
        }

        if (!$existingRefreshToken || !$existingRefreshToken->isActiveStatus() || $device->getId() !== $existingRefreshToken->device->getId()) {
            throw new RefreshTokenNotFoundException();
        }

        $user = $existingRefreshToken->user;

        if (!$device) {
            $device = new Device($deviceDto);
            $device->users()->attach($user->getId());
            $device->save();
        }

        if (!$device->users->contains($user->getId())) {
            $device->users()->attach($user->getId());
        }

        $existingAccessToken = $this->accessTokenRepository->getByUserDevice($user->getId(), $device->getId());

        if ($existingAccessToken && !$existingAccessToken->isTokenExpired()) {
            return [
                'accessToken' => $existingAccessToken->getToken(),
                'refreshToken' => $existingRefreshToken->getToken(),
            ];
        }

        if ($existingAccessToken) {
            $existingAccessToken->updateIsActiveStatus(false);
            $this->accessTokenRepository->update($existingAccessToken);
        }

        $newAccessToken = new AccessToken();
        $newAccessToken->user_id = $user->getId();
        $newAccessToken->device_id = $device->getId();
        $newAccessToken->token = $this->generateAccessToken(); 
        $newAccessToken->save();

        return [
            'accessToken' => $newAccessToken->getToken(),
            'refreshToken' => $existingRefreshToken->getToken(),
        ];
    }

    /**
     * Generate an access token.
     *
     * @return string
     */
    private function generateAccessToken(): string
    {
        return bin2hex(random_bytes(32)); 
    }
}

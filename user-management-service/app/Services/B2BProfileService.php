<?php

namespace App\Services;

use App\Models\User;
use App\Models\B2BProfile;
use App\Repositories\UserRepository;
use App\Repositories\B2BProfileRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\FollowerRepository;
use App\Exceptions\EmailNotFoundException;
use App\DTOs\B2BProfileDTO;
use App\DTOs\DeviceDTO;
use App\DTOs\CreateUserDTO;
use App\DTOs\B2BRegistrationOutputDTO;
use App\Services\UserService;

class B2bProfileService
{
    protected $userRepository;
    protected $b2bProfileRepository;
    protected $deviceRepository;
    protected $followerRepository;
    protected $userService;

    public function __construct(
        UserRepository $userRepository,
        B2BProfileRepository $b2bProfileRepository,
        DeviceRepository $deviceRepository,
        UserService $userService
    ) {
        $this->userRepository = $userRepository;
        $this->b2bProfileRepository = $b2bProfileRepository;
        $this->deviceRepository = $deviceRepository;
        $this->followerRepository = $followerRepository;
        $this->userService = $userService;
    }

    public function register(B2BProfileDTO $createUserProfileDto, DeviceDTO $deviceDto): B2BRegistrationOutputDTO
    {
        if (!$createUserProfileDto->email) {
            throw new EmailNotFoundException();
        }

        $user = $this->createUser($createUserProfileDto);

        $b2bProfile = $this->createB2BProfile($user, $createUserProfileDto);

        $output = new B2BRegistrationOutputDTO();
        $output->token = $this->userService->getTokens($deviceDto, $user);

        $output->user = [
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'userType' => $user->user_type,
            'username' => $user->username,
            'email' => $user->email,
            'b2bProfileType' => $b2bProfile->profile_type,
            'b2bStatusEnum' => $b2bProfile->profile_status,
            'b2bProfileRole' => $b2bProfile->profile_role,
            'id' => $user->id,
            'profileId' => $b2bProfile->id,
        ];

        return $output;
    }

    protected function createUser(B2BProfileDTO $createUserProfileDto): User
    {
        $user = new User();
        $user->first_name = $createUserProfileDto->firstName;
        $user->last_name = $createUserProfileDto->lastName;
        $user->email = $createUserProfileDto->email;
        $user->password = bcrypt($createUserProfileDto->password);
        $user->phone = $createUserProfileDto->phone;
        $user->user_type = $createUserProfileDto->userType;
        $user->save();

        $user->roles()->sync([
            $createUserProfileDto->profileType, 
            'attendee'
        ]);

        return $user;
    }

    protected function createB2BProfile(User $user, B2BProfileDTO $createUserProfileDto): B2BProfile
    {

        $b2bProfile = new B2BProfile();
        $b2bProfile->user_id = $user->id;
        $b2bProfile->profile_type = $createUserProfileDto->profileType;
        $b2bProfile->profile_role = $createUserProfileDto->profileRole;
        $b2bProfile->save();

        return $b2bProfile;
    }

    public function getProfile(string $userId): B2BProfileDTO
    {
        $profile = $this->b2bProfileRepository->getByUser($userId);

        return new B2BProfileDTO($profile);
    }

    public function getFollowers(string $userId, array $option): CustomPagination
    {
        $profile = $this->b2bProfileRepository->getByUser($userId);
        $followers = $this->followerRepository->listByB2B($profile->id, $option);

        return new CustomPagination($followers);
    }
}

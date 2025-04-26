<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\VerifCodeRequest;
use App\Http\Requests\DeletionReasonRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    public function login(LoginUserRequest $request): JsonResponse
    {
        try {
            $deviceDto = $request->device ?? null;
            $user = $this->userService->login($request->validated(), $deviceDto);

            return response()->json([
                'message' => 'Login successfully!',
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'login' => $e->getMessage() ?? 'Unknown error'
            ]);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $device = $request->device;
            $this->userService->logout($userId, $device['deviceIdentifier'] ?? null);

            return response()->json(['message' => 'Logged out successfully!']);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'logout' => $e->getMessage() ?? 'Unknown error'
            ]);
        }
    }

    public function sendResetPasswordCode(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $device = $request->device;
            $result = $this->userService->resetPasswordVerification(
                $request->email,
                $request->phone,
                $device
            );

            return response()->json([
                'message' => 'Verification code sent successfully!',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'reset_password' => $e->getMessage() ?? 'Unknown error'
            ]);
        }
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $device = $request->device;
            $data = $request->validated();

            $user = $this->userService->resetPassword(
                $userId,
                $data['password'],
                $data['confirm_password'],
                $device
            );

            return response()->json([
                'message' => 'Password changed successfully!',
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'update_password' => $e->getMessage() ?? 'Unknown error'
            ]);
        }
    }

    public function verifyUser(VerifCodeRequest $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $device = $request->device;
            $this->userService->userVerification($request->code, $userId, $device);

            return response()->json([
                'message' => 'Verified user successfully!',
                'data' => null,
            ]);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'verification' => $e->getMessage() ?? 'Unknown error'
            ]);
        }
    }

    public function updateUser(UpdateUserRequest $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $user = $this->userService->updateUserDetails($userId, $request->validated());

            return response()->json([
                'message' => 'User details updated successfully!',
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'update_user' => $e->getMessage() ?? 'Unknown error'
            ]);
        }
    }

    public function blockUser(string $userId): JsonResponse
    {
        try {
            $user = $this->userService->blockUser($userId);

            return response()->json([
                'message' => 'User blocked successfully!',
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'block_user' => $e->getMessage() ?? 'Unknown error'
            ]);
        }
    }

    public function deleteUser(DeletionReasonRequest $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $this->userService->delete($userId, $request->validated());

            return response()->noContent();
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'delete_user' => $e->getMessage() ?? 'Unknown error'
            ]);
        }
    }

    public function deleteUserByAdmin(DeletionReasonRequest $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $this->userService->delete($userId, $request->validated());

            return response()->noContent();
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'delete_user' => $e->getMessage() ?? 'Unknown error'
            ]);
        }
    }

}

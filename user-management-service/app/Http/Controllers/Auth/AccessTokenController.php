<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\AccessTokenService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class AccessTokenController extends Controller
{
    protected $accessTokenService;

    public function __construct(AccessTokenService $accessTokenService)
    {
        $this->accessTokenService = $accessTokenService;
    }

    /**
     * Create or refresh access token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createAccessToken(Request $request): JsonResponse
    {
        try {
            $deviceDto = $request->device;
            $refreshToken = $request->header('refresh-token');

            $tokens = $this->accessTokenService->getValidatedToken($refreshToken, $deviceDto);

            return response()->json([
                'message' => 'Refreshed access token successfully!',
                'data' => $tokens,
            ], 201);
        } catch (\Exception $e) {
            throw new ValidationException('Failed to refresh access token!', $e->getMessage());
        }
    }
}

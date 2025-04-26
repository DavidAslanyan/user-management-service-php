<?php

namespace App\DTO;

use App\Enums\B2bProfileTypeEnum;
use App\Enums\B2bProfileRoleEnum;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;

class B2BProfileDto extends FormRequest
{
    public ?string $userId = null;
    public ?string $accountImagePath = null;
    public ?string $profileImagePath = null;
    public ?string $legalName = null;
    public ?string $venueName = null;
    public ?string $stageName = null;
    public ?array $genres = null;
    public ?string $website = null;
    public ?string $facebook = null;
    public ?string $instagram = null;
    public ?string $linkedin = null;
    public ?array $portfolioImages = null;
    public ?array $portfolioVideos = null;
    public ?array $youtubeLinks = null;
    public ?string $coverPhotoPath = null;
    public ?string $bio = null;
    public ?string $address = null;

    public function rules(): array
    {
        return [
            'userId' => 'nullable|string|uuid',
            'accountImagePath' => 'nullable|string|url',
            'profileImagePath' => 'nullable|string|url',
            'legalName' => 'nullable|string',
            'venueName' => 'nullable|string',
            'stageName' => 'nullable|string',
            'genres' => 'nullable|array',
            'website' => 'nullable|string|url',
            'facebook' => 'nullable|string|url',
            'instagram' => 'nullable|string|url',
            'linkedin' => 'nullable|string|url',
            'portfolioImages' => 'nullable|array',
            'portfolioVideos' => 'nullable|array',
            'youtubeLinks' => 'nullable|array',
            'coverPhotoPath' => 'nullable|string|url',
            'bio' => 'nullable|string',
            'address' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'profileType.required' => 'Profile type is required',
            'profileRole.required' => 'Profile role is required',
        ];
    }
}

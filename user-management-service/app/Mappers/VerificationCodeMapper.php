<?php

namespace App\Mappers;

use App\Models\VerificationCode;
use App\DTOs\VerificationCodeDTO;

class VerificationCodeMapper
{
    public static function toDTO(VerificationCode $verificationCode): VerificationCodeDTO
    {
        return new VerificationCodeDTO(
            $verificationCode->id,
            $verificationCode->user_id,
            $verificationCode->device_id,
            $verificationCode->code,
            $verificationCode->status,
            $verificationCode->attempt_count,
            $verificationCode->sent_at->toISOString(),  
            $verificationCode->expires_at ? $verificationCode->expires_at->toISOString() : null
        );
    }

    public static function toModel(VerificationCodeDTO $dto): VerificationCode
    {
        $verificationCode = new VerificationCode();

        $verificationCode->id = $dto->id;
        $verificationCode->user_id = $dto->user_id;
        $verificationCode->device_id = $dto->device_id;
        $verificationCode->code = $dto->code;
        $verificationCode->status = $dto->status->value;
        $verificationCode->attempt_count = $dto->attempt_count;
        $verificationCode->sent_at = $dto->sent_at;
        $verificationCode->expires_at = $dto->expires_at;

        return $verificationCode;
    }
}

<?php

namespace App\DTOs;

use App\Enums\VerificationCodeStatusEnum;

class VerificationCodeDTO
{
    public string $id;
    public string $user_id;
    public string $device_id;
    public string $code;
    public VerificationCodeStatusEnum $status;
    public int $attempt_count;
    public string $sent_at;
    public ?string $expires_at;

    public function __construct(
        string $id,
        string $user_id,
        string $device_id,
        string $code,
        VerificationCodeStatusEnum $status,
        int $attempt_count,
        string $sent_at,
        ?string $expires_at
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->device_id = $device_id;
        $this->code = $code;
        $this->status = $status;
        $this->attempt_count = $attempt_count;
        $this->sent_at = $sent_at;
        $this->expires_at = $expires_at;
    }
}

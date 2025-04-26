<?php

namespace App\Enums;

enum VerificationCodeStatusEnum: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case VERIFIED = 'verified';
}

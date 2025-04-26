<?php

namespace App\Enums;

enum UserStatusEnum: string
{
    case ACTIVE = 'active';
    case UNVERIFIED = 'unverified';
    case DELETED = 'deleted';
    case BLOCKED = 'blocked';
}


<?php

namespace App\Enums;

enum UserTypeEnum: string
{
    case B2B = 'b2b';
    case B2C = 'b2c';
    case BOTH = 'both';
}

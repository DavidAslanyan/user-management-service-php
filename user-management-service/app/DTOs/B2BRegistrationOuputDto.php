<?php

namespace App\DTOs;

use App\Enums\UserTypeEnum;
use App\Enums\B2BStatusEnum;
use App\Enums\B2bProfileTypeEnum;
use App\Enums\B2bProfileRoleEnum;

class B2bRegistrationOutputDTO
{
    public $user;
    public $token;

    public function __construct(
        array $user,
        TokenOutputDTO $token
    ) {
        $this->user = $user;
        $this->token = $token;
    }

    public static function fromArray(array $user, TokenOutputDTO $token): self
    {
        return new self($user, $token);
    }
}

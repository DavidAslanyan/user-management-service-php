<?php

namespace App\DTOs;

use App\Enums\UserStatusEnum;
use App\Enums\UserTypeEnum;

class UserDTO
{
    public string $id;
    public string $first_name;
    public string $last_name;
    public string $username;
    public ?string $email;
    public ?string $phone;
    public string $password;
    public UserTypeEnum $user_type;
    public UserStatusEnum $status;
    public string $language;
    public ?string $deletion_reason;
    public string $created_at;
    public string $updated_at;

    public function __construct(
        string $id,
        string $first_name,
        string $last_name,
        string $username,
        ?string $email,
        ?string $phone,
        string $password,
        UserTypeEnum $user_type,
        UserStatusEnum $status,
        string $language,
        ?string $deletion_reason,
        string $created_at,
        string $updated_at
    ) {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->username = $username;
        $this->email = $email;
        $this->phone = $phone;
        $this->password = $password;
        $this->user_type = $user_type;
        $this->status = $status;
        $this->language = $language;
        $this->deletion_reason = $deletion_reason;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}

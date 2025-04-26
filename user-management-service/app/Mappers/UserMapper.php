<?php

namespace App\Mappers;

use App\Models\User as UserEntity;
use App\Models\User;
use App\ValueObjects\EmailValueObject;
use App\ValueObjects\PhoneValueObject;
use App\ValueObjects\UsernameValueObject;
use App\ValueObjects\PasswordValueObject;

class UserMapper
{
    public static function toModel(UserEntity $entity): User
    {
        $email = $entity->email ? EmailValueObject::create($entity->email) : null;
        $phone = $entity->phone ? PhoneValueObject::create($entity->phone) : null;

        return new User(
            $entity->first_name,
            $entity->last_name,
            UsernameValueObject::create($entity->username),
            $email,
            $phone,
            $entity->user_type,
            $entity->status,
            $entity->language,
            $entity->deletion_reason,
            $entity->id,
            $entity->created_at,
            $entity->updated_at,
            PasswordValueObject::create($entity->password)
        );
    }

    public static function toEntity(User $model): UserEntity
    {
        $email = $model->getEmail() ? $model->getEmail()->getValue() : null;
        $phone = $model->getPhone() ? $model->getPhone()->getValue() : null;

        $entity = new UserEntity();
        $entity->id = $model->getId();
        $entity->username = $model->getUsername()->getValue();
        $entity->email = $email;
        $entity->phone = $phone;
        $entity->user_type = $model->getUserType();
        $entity->status = $model->getStatus();
        $entity->language = $model->getLanguage();
        $entity->deletion_reason = $model->getDeletionReason();
        $entity->created_at = $model->getCreatedAt();
        $entity->first_name = $model->getFirstName();
        $entity->last_name = $model->getLastName();
        
        return $entity;
    }
}

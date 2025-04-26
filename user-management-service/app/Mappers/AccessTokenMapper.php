<?php

namespace App\Mappers;

use App\Models\AccessToken;
use App\Mappers\UserMapper;
use App\Mappers\DeviceMapper;

class AccessTokenMapper
{
    public static function toModel(AccessToken $entity): AccessToken
    {
        return new AccessToken(
            UserMapper::toModel($entity->user), 
            $entity->token,
            $entity->expires_at,
            $entity->is_active,
            DeviceMapper::toModel($entity->device), 
            $entity->id,
            $entity->created_at,
            $entity->updated_at
        );
    }

    public static function toEntity(AccessToken $model): AccessToken
    {
        $entity = new AccessToken();
        $entity->id = $model->getId();
        $entity->user()->associate(UserMapper::toEntity($model->getUser())); 
        $entity->is_active = $model->isActiveStatus();
        $entity->expires_at = $model->getExpiresAt();
        $entity->device()->associate(DeviceMapper::toEntity($model->getDevice()));
        $entity->token = $model->getToken();
        $entity->created_at = $model->getCreatedAt();
        
        return $entity;
    }
}

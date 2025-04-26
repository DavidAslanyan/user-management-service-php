<?php

namespace App\Repositories;

use App\Models\AccessToken;
use App\Models\User;
use App\Models\Device;
use App\Mappers\AccessTokenMapper;
use App\Models\AccessToken;
use App\Exceptions\AccessTokenNotFoundException;
use App\Core\CustomPagination;
use App\Core\Paginator;

class AccessTokenRepositoryHandler
{
    protected $repository;

    public function __construct(AccessToken $accessToken)
    {
        $this->repository = $accessToken;
    }

    public function getByUserDevice(string $userId, string $deviceId): ?AccessToken
    {
        $entity = $this->repository->with(['user', 'device'])
            ->where('user_id', $userId)
            ->where('device_id', $deviceId)
            ->where('is_active', true)
            ->first();

        if ($entity) {
            return AccessTokenMapper::toModel($entity);
        }

        return null;
    }

    public function create(AccessToken $token): AccessToken
    {
        $entity = AccessTokenMapper::toEntity($token);
        $savedEntity = $this->repository->create($entity->toArray());

        return AccessTokenMapper::toModel(
            $this->repository->with(['user', 'device'])->find($savedEntity->id)
        );
    }

    public function update(AccessToken $token): AccessToken
    {
        $entity = AccessTokenMapper::toEntity($token);
        $this->repository->where('id', $token->getId())->update($entity->toArray());

        $updatedEntity = $this->repository->with(['user', 'device'])->find($token->getId());

        if (!$updatedEntity) {
            throw new AccessTokenNotFoundException();
        }

        return AccessTokenMapper::toModel($updatedEntity);
    }

    public function delete(string $id): void
    {
        $this->repository->destroy($id);
    }

    public function getById(string $id): AccessToken
    {
        $entity = $this->repository->with(['user', 'device'])->find($id);

        if (!$entity) {
            throw new AccessTokenNotFoundException();
        }

        return AccessTokenMapper::toModel($entity);
    }

    public function deactivateToken(string $userId, string $deviceId): AccessToken
    {
        $entity = $this->repository->with(['user', 'device'])
            ->where('user_id', $userId)
            ->where('device_id', $deviceId)
            ->first();

        if (!$entity) {
            throw new AccessTokenNotFoundException();
        }

        $entity->is_active = false;
        $entity->save();

        return AccessTokenMapper::toModel($entity);
    }

    public function list(array $options): CustomPagination
    {
        $query = $this->repository->newQuery();
        $paginatedResult = Paginator::paginate($query, $options);

        return new CustomPagination(
            $paginatedResult->map(fn($token) => AccessTokenMapper::toModel($token)),
            $paginatedResult->total()
        );
    }

    public function listByUser(string $userId, array $options): CustomPagination
    {
        $query = $this->repository->newQuery()
            ->leftJoin('users', 'users.id', '=', 'access_tokens.user_id')
            ->where('user_id', $userId);

        $paginatedResult = Paginator::paginate($query, $options);

        if ($paginatedResult->isEmpty()) {
            throw new AccessTokenNotFoundException();
        }

        return new CustomPagination(
            $paginatedResult->map(fn($token) => AccessTokenMapper::toModel($token)),
            $paginatedResult->total()
        );
    }

    public function listByDevice(string $deviceId, array $options): CustomPagination
    {
        $query = $this->repository->newQuery()
            ->leftJoin('devices', 'devices.id', '=', 'access_tokens.device_id')
            ->where('device_id', $deviceId);

        $paginatedResult = Paginator::paginate($query, $options);

        if ($paginatedResult->isEmpty()) {
            throw new AccessTokenNotFoundException();
        }

        return new CustomPagination(
            $paginatedResult->map(fn($token) => AccessTokenMapper::toModel($token)),
            $paginatedResult->total()
        );
    }
}

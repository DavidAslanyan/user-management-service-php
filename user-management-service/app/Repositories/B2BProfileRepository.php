<?php

namespace App\Repositories;

use App\Models\B2BProfile;
use App\Mappers\B2bProfileMapper;
use App\Exceptions\ProfileNotFoundException;
use App\Enums\B2BStatusEnum;
use App\Enums\UserStatusEnum;
use App\Pagination\CustomPagination;

class B2BProfileRepositoryHandler
{
    protected $repository;

    public function __construct(B2BProfile $repository)
    {
        $this->repository = $repository;
    }

    public function create(B2BProfile $profile): B2BProfile
    {
        $savedEntity = $this->repository->create(B2bProfileMapper::toEntity($profile));
        return B2bProfileMapper::toModel(
            $this->repository->with('user')->find($savedEntity->id)
        );
    }

    public function update(B2BProfile $profile): B2BProfile
    {
        $this->repository->where('id', $profile->getId())
            ->update(B2bProfileMapper::toEntity($profile)->toArray());
        return $this->getById($profile->getId());
    }

    public function delete(string $id): void
    {
        $this->repository->destroy($id);
    }

    public function getById(string $id): B2BProfile
    {
        $profile = $this->repository->with('user')->find($id);

        if (!$profile) {
            throw new ProfileNotFoundException();
        }

        return B2bProfileMapper::toModel($profile);
    }

    public function list(array $options): CustomPagination
    {
        $query = $this->repository->query();
        $paginatedResult = Paginator::paginate($query, $options);

        return new CustomPagination(
            $paginatedResult->items->map([B2bProfileMapper::class, 'toModel']),
            $paginatedResult->totalCount
        );
    }

    public function getByUser(string $userId): B2BProfile
    {
        $profile = $this->repository->whereHas('user', function ($query) use ($userId) {
            $query->where('id', $userId)
                  ->where('status', UserStatusEnum::ACTIVE);
        })->with('user')->first();

        if (!$profile) {
            throw new ProfileNotFoundException();
        }

        return B2bProfileMapper::toModel($profile);
    }
}

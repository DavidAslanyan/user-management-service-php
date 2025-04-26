<?php

namespace App\Repositories;

use App\Models\User;
use App\Enums\UserStatusEnum;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\DuplicateValueException;
use App\Exceptions\WrongPasswordOrUsernameException;
use App\Enums\DeletionReasonEnum;
use App\ValueObjects\PasswordValueObject;
use App\ValueObjects\UsernameValueObject;
use App\Mappers\UserMapper;
use App\Models\UserModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Pagination\CustomPagination;
use App\Pagination\Paginator;
use Illuminate\Support\Facades\Hash;
use App\Http\Dtos\DeletionReasonDto;

class UserRepositoryHandler 
{
    public function delete(string $userId, DeletionReasonDto $deletionReasonDto): void
    {
        $reason = $deletionReasonDto->reason === DeletionReasonEnum::OTHER
            ? $deletionReasonDto->otherReason
            : $deletionReasonDto->reason;

        $user = User::findOrFail($userId);
        $user->update([
            'status' => UserStatusEnum::DELETED,
            'deletion_reason' => $reason,
        ]);
    }

    public function create(UserModel $user, PasswordValueObject $password): UserModel
    {
        $baseUsername = $user->getUsername()->getValue();
        $newUsername = $baseUsername;
        $counter = 1;

        while (User::where('username', $newUsername)->exists()) {
            $newUsername = $baseUsername . $counter;
            $counter++;
        }
        $user->setUsername(UsernameValueObject::create($newUsername));

        if ($user->getEmail()) {
            $userWithDuplicateEmail = User::where('email', $user->getEmail()->getValue())->first();
            if ($userWithDuplicateEmail) {
                throw new DuplicateValueException(
                    "User with email '{$user->getEmail()->getValue()}' already exists"
                );
            }
        }

        if ($user->getPhone()) {
            $userWithDuplicatePhone = User::where('phone', $user->getPhone()->getValue())->first();
            if ($userWithDuplicatePhone) {
                throw new DuplicateValueException(
                    "User with phone '{$user->getPhone()->getValue()}' already exists"
                );
            }
        }

        $password->encrypt();

        $entity = UserMapper::toEntity($user);
        $entity->password = $password->getValue();
        $savedEntity = User::create($entity->toArray());

        return UserMapper::toModel($savedEntity);
    }

    public function updatePassword(string $userId, string $newPassword): UserModel
    {
        $user = User::findOrFail($userId);
        $user->update(['password' => Hash::make($newPassword)]);
        return $this->getById($userId);
    }

    public function updateStatus(string $userId, UserStatusEnum $status): UserModel
    {
        $user = User::findOrFail($userId);
        $user->update(['status' => $status]);
        return $this->getById($userId);
    }

    public function update(UserModel $user): UserModel
    {
        $userWithDuplicateUsername = User::where('username', $user->getUsername()->getValue())
            ->where('id', '!=', $user->getId())
            ->first();

        if ($userWithDuplicateUsername) {
            throw new DuplicateValueException(
                "User with username '{$user->getUsername()->getValue()}' already exists"
            );
        }

        if ($user->getEmail()) {
            $userWithDuplicateEmail = User::where('email', $user->getEmail()->getValue())
                ->where('id', '!=', $user->getId())
                ->first();

            if ($userWithDuplicateEmail) {
                throw new DuplicateValueException(
                    "User with email '{$user->getEmail()->getValue()}' already exists"
                );
            }
        }

        if ($user->getPhone()) {
            $userWithDuplicatePhone = User::where('phone', $user->getPhone()->getValue())
                ->where('id', '!=', $user->getId())
                ->first();

            if ($userWithDuplicatePhone) {
                throw new DuplicateValueException(
                    "User with phone '{$user->getPhone()->getValue()}' already exists"
                );
            }
        }

        $entity = UserMapper::toEntity($user);
        $entity->save();

        $updatedEntity = User::findOrFail($user->getId());
        return UserMapper::toModel($updatedEntity);
    }

    public function partialUpdate(string $userId, array $details): UserModel
    {
        if (isset($details['email'])) {
            $userWithDuplicateEmail = User::where('email', $details['email'])
                ->where('id', '!=', $userId)
                ->first();

            if ($userWithDuplicateEmail) {
                throw new DuplicateValueException(
                    "User with email '{$details['email']}' already exists"
                );
            }
        }

        if (isset($details['phone'])) {
            $userWithDuplicatePhone = User::where('phone', $details['phone'])
                ->where('id', '!=', $userId)
                ->first();

            if ($userWithDuplicatePhone) {
                throw new DuplicateValueException(
                    "User with phone '{$details['phone']}' already exists"
                );
            }
        }

        $user = User::findOrFail($userId);
        $user->update($details);

        return UserMapper::toModel($user);
    }

    public function getById(string $id): UserModel
    {
        $user = User::find($id);
        if (!$user) {
            throw new UserNotFoundException();
        }

        return UserMapper::toModel($user);
    }

    public function list(array $options): CustomPagination
    {
        $query = User::query();
        $paginatedResult = Paginator::paginate($query, $options);

        if (!$paginatedResult->items->isEmpty()) {
            throw new UserNotFoundException();
        }

        return new CustomPagination($paginatedResult->items, $paginatedResult->totalCount);
    }

    public function listByName(string $name, array $options): CustomPagination
    {
        $query = User::where('name', 'LIKE', '%' . $name . '%');
        $paginatedResult = Paginator::paginate($query, $options);

        if (!$paginatedResult->items->isEmpty()) {
            throw new UserNotFoundException();
        }

        return new CustomPagination($paginatedResult->items, $paginatedResult->totalCount);
    }

    public function getByUsername(string $username): UserModel
    {
        $user = User::where('email', $username)->first();
        if (!$user) {
            throw new WrongPasswordOrUsernameException();
        }

        return UserMapper::toModel($user);
    }

    public function getByEmail(string $email): UserModel
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            throw new WrongPasswordOrUsernameException();
        }

        return UserMapper::toModel($user);
    }

    public function getByPhone(string $phone): UserModel
    {
        $user = User::where('phone', $phone)->first();
        if (!$user) {
            throw new UserNotFoundException();
        }

        return UserMapper::toModel($user);
    }

    public function listByTicket(string $ticketId, array $options): CustomPagination
    {
        $query = User::whereHas('tickets', function ($query) use ($ticketId) {
            $query->where('ticket.id', $ticketId);
        });

        $paginatedResult = Paginator::paginate($query, $options);

        if (!$paginatedResult->items->isEmpty()) {
            throw new UserNotFoundException();
        }

        return new CustomPagination($paginatedResult->items, $paginatedResult->totalCount);
    }

    public function listActive(array $options): CustomPagination
    {
        $query = User::where('status', UserStatusEnum::ACTIVE);
        $paginatedResult = Paginator::paginate($query, $options);

        if (!$paginatedResult->items->isEmpty()) {
            throw new UserNotFoundException();
        }

        return new CustomPagination($paginatedResult->items, $paginatedResult->totalCount);
    }

    public function listUnverified(array $options): CustomPagination
    {
        $query = User::where('status', UserStatusEnum::UNVERIFIED);
        $paginatedResult = Paginator::paginate($query, $options);

        if (!$paginatedResult->items->isEmpty()) {
            throw new UserNotFoundException();
        }

        return new CustomPagination($paginatedResult->items, $paginatedResult->totalCount);
    }

    public function listDeleted(array $options): CustomPagination
    {
        $query = User::where('status', UserStatusEnum::DELETED);
        $paginatedResult = Paginator::paginate($query, $options);

        if (!$paginatedResult->items->isEmpty()) {
            throw new UserNotFoundException();
        }

        return new CustomPagination($paginatedResult->items, $paginatedResult->totalCount);
    }

    public function listBlocked(array $options): CustomPagination
    {
        $query = User::where('status', UserStatusEnum::BLOCKED);
        $paginatedResult = Paginator::paginate($query, $options);

        if (!$paginatedResult->items->isEmpty()) {
            throw new UserNotFoundException();
        }

        return new CustomPagination($paginatedResult->items, $paginatedResult->totalCount);
    }

    public function listByCreationDate(DateTime $creationDate, array $options): CustomPagination
    {
        $query = User::whereDate('created_at', $creationDate);
        $paginatedResult = Paginator::paginate($query, $options);

        if (!$paginatedResult->items->isEmpty()) {
            throw new UserNotFoundException();
        }

        return new CustomPagination($paginatedResult->items, $paginatedResult->totalCount);
    }
}

<?php

namespace App\Repositories;

use App\Models\Device;
use Illuminate\Database\Eloquent\Collection;

class DeviceRepository
{
    /**
     * Get devices associated with a user.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByUser($user): Collection
    {
        return Device::where('user_id', $user->id)->get();
    }

    /**
     * Get all devices for an admin.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllDevicesForAdmin(): Collection
    {
        return Device::all();
    }

    /**
     * Get all devices.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllDevices(): Collection
    {
        return Device::all();
    }

    /**
     * Get device by its ID.
     *
     * @param string $id
     * @return \App\Models\Device|null
     */
    public function getById(string $id): ?Device
    {
        return Device::find($id);
    }
}

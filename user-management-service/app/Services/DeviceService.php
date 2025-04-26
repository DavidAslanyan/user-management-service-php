<?php

namespace App\Services;

use App\Repositories\DeviceRepository;
use App\Models\Device;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DeviceService
{
    protected $deviceRepository;

    public function __construct(DeviceRepository $deviceRepository)
    {
        $this->deviceRepository = $deviceRepository;
    }

    public function createDevice(string $userId, array $createDeviceDto)
    {
        $user = User::findOrFail($userId);
        
        $device = new Device();
        $device->fill($createDeviceDto);
        $device->user_id = $user->id;
        $device->save();

        return response()->json([
            'message' => 'Device created successfully!',
            'data' => $device
        ], 201);
    }

    public function getDeviceByUser()
    {
        $user = Auth::user();
        $devices = $this->deviceRepository->getByUser($user);

        return response()->json([
            'data' => $devices
        ]);
    }

    public function getAdminDeviceByUser()
    {
        $devices = $this->deviceRepository->getAllDevicesForAdmin();

        return response()->json([
            'data' => $devices
        ]);
    }

    public function getDevice()
    {
        $devices = $this->deviceRepository->getAllDevices();

        return response()->json([
            'data' => $devices
        ]);
    }

    public function getAdminDevice()
    {
        $devices = $this->deviceRepository->getAllDevicesForAdmin();

        return response()->json([
            'data' => $devices
        ]);
    }

    public function getDeviceById(string $id)
    {
        $device = $this->deviceRepository->getById($id);

        return response()->json([
            'data' => $device
        ]);
    }

    public function getAdminDeviceById(string $id)
    {
        $device = $this->deviceRepository->getById($id);

        return response()->json([
            'data' => $device
        ]);
    }
}

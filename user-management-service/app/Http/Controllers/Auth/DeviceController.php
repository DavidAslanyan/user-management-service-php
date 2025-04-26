<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DeviceService;

class DeviceController extends Controller
{
    protected $deviceService;

    public function __construct(DeviceService $deviceService)
    {
        $this->deviceService = $deviceService;
    }

    public function createDevice(Request $request, $id)
    {
        $createDeviceDto = $request->all();
        return $this->deviceService->createDevice($id, $createDeviceDto);
    }

    public function getDeviceByUser()
    {
        return $this->deviceService->getDeviceByUser();
    }

    public function getAdminDeviceByUser()
    {
        return $this->deviceService->getAdminDeviceByUser();
    }

    public function getDevice()
    {
        return $this->deviceService->getDevice();
    }

    public function getAdminDevice()
    {
        return $this->deviceService->getAdminDevice();
    }

    public function getDeviceById($id)
    {
        return $this->deviceService->getDeviceById($id);
    }

    public function getAdminDeviceById($id)
    {
        return $this->deviceService->getAdminDeviceById($id);
    }
}

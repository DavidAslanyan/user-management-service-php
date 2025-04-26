<?php 

namespace App\Http\Controllers;

use App\DTOs\VerificationCodeDTO;
use App\Mappers\VerificationCodeMapper;
use App\Models\VerificationCode;

class VerificationCodeController extends Controller
{
    public function store(VerificationCodeDTO $dto)
    {
        $verificationCode = VerificationCodeMapper::toModel($dto);
        $verificationCode->save();

        return response()->json($verificationCode);
    }
}

<?php

namespace App\DTO;

class TokenOutputDto
{
    public string $accessToken;
    public string $refreshToken;

    public function __construct(string $accessToken, string $refreshToken)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
    }
}

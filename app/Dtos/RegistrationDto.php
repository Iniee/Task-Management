<?php

namespace App\Dtos;

use App\Traits\DataTransferObject;

class RegistrationDto
{
    use DataTransferObject;

    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password
    ) {}
}
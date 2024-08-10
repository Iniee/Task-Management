<?php

namespace App\Dtos;

use App\Traits\DataTransferObject;

class CreateTaskDto
{
    use DataTransferObject;

    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $due_by
    ) {}
}
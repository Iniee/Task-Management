<?php

namespace App\Dtos;

use App\Traits\DataTransferObject;

class UpdateTaskDto
{
    use DataTransferObject;

    public function __construct(
        public readonly ?string $title = null,
        public readonly ?string $description = null,
        public readonly ?string $due_by = null
    ) {}
}
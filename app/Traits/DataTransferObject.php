<?php

namespace App\Traits;

trait DataTransferObject
{
    public static function fromArray(array $data): static
    {
        return new static(...$data);
    }

    public function toArray():array {
        return get_object_vars($this);
    }
}
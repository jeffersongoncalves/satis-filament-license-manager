<?php

namespace App\Data;

use Illuminate\Contracts\Support\Arrayable;

readonly class PackageData implements Arrayable
{
    public function __construct(
        public string $name,
        public string $version = '*'
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'version' => $this->version,
        ];
    }
}

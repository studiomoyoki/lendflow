<?php

namespace App\Data\NYT;

use Spatie\LaravelData\Data;

class IsbnData extends Data
{
    public function __construct(
        public string $isbn10
    ) {}


}

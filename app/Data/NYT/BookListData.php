<?php

namespace App\Data\NYT;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class BookListData extends Data
{

    /**
     * @param array<int, BookData> $books
     */
   public function __construct(
        public string $status,
        public string $copyright,
        public string $num_results,
        public Collection $books
    ) {}


    public static function fromData(array $data, Collection $books): self
    {
        return new self(
            $data['status'],
            $data['copyright'],
            $data['num_results'],
            BookData::collect($books)
        );
    }
}

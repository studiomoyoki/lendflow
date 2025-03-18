<?php

namespace App\Data\NYT;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class BookData extends Data
{
    /*
     * @param Collection<int, IsbnData> $isbns
     * @param Collection<int, RanksHistoryData> $ranks_history
     * @param Collection<int, ReviewData> $reviews
     */
    public function __construct(
        public string $title,
        public ?string $description,
        public ?string $contributor,
        public string $author,
        public ?string $contributor_note,
        public float $price,
        public ?string $age_group,
        public ?string $publisher,
        public Collection $isbns,
        public Collection $ranks_history,
        public Collection $reviews
    ) {}


    public static function fromData(array $data, Collection $isbns, Collection $ranksHistory, Collection $reviews): self
    {
        return new self(
            $data['title'],
            $data['description'],
            $data['contributor'],
            $data['author'],
            $data['contributor_note'],
            $data['price'],
            $data['age_group'],
            $data['publisher'],
            IsbnData::collect($isbns),
            RankData::collect($ranksHistory),
            ReviewData::collect($reviews)
        );
    }

}

<?php

namespace App\Data\NYT;

use Spatie\LaravelData\Data;

class ReviewData extends Data
{
    public function __construct(
        public ?string $book_review_link,
        public ?string $first_chapter_link,
        public ?string $sunday_review_link,
        public ?string $article_chapter_link
    ) {}


}

<?php

namespace App\Data\NYT;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

/**
 * @OA\Schema(
 *     schema="FilterData",
 *     type="object",
 *     @OA\Property(property="author", type="string", nullable=true, example="Terry Pratchett"),
 *     @OA\Property(property="isbn", type="string", nullable=true, example="0399178570"),
 *     @OA\Property(property="title", type="string", nullable=true, example="The Colour of Magic"),
 *     @OA\Property(property="offset", type="integer", nullable=true, example=0)
 * )
 */
class FilterData extends Data
{
    public function __construct(
        public ?string $author,
        public ?string $isbn,
        public ?string $title,
        public ?int $offset
    )
    {
    }

    public static function rules(): array
    {
        return [
            'author' => ['nullable', 'string', 'max:100'],
            'isbn' => ['nullable', 'string', 'min:10', 'max:13', 'regex:/^\d{10}$|^\d{13}$/'],
            'title' => ['nullable', 'string', 'max:255'],
            'offset' => ['nullable', 'integer', 'min:0', 'multiple_of:20'],
        ];
    }
}

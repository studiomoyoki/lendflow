<?php

namespace Tests\Unit\Data;

use App\Data\NYT\FilterData;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class FilterDataTest extends TestCase
{
    public function test_from_request(): void
    {
        $request = Request::create('/api/nyt/best-sellers', 'GET', [
            'author' => 'Terry Pratchett',
            'isbn' => '0399178570',
            'title' => 'The Colour of Magic',
            'offset' => 0,
        ]);

        $filterData = FilterData::from($request);

        $this->assertEquals('Terry Pratchett', $filterData->author);
        $this->assertEquals('0399178570', $filterData->isbn);
        $this->assertEquals('The Colour of Magic', $filterData->title);
        $this->assertEquals(0, $filterData->offset);
    }

    public function test_from_request_with_incorrect_isbn(): void
    {
        $request = Request::create('/api/nyt/best-sellers', 'GET', [
            'isbn' => '03991785'
        ]);
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The isbn field must be at least 10 characters');

        FilterData::from($request);
    }

    public function test_from_request_with_incorrect_isbn2(): void
    {
        $request = Request::create('/api/nyt/best-sellers', 'GET', [
            'isbn' => '039917851232355'
        ]);
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The isbn field must not be greater than 13 characters');

        FilterData::from($request);
    }

    public function test_from_request_with_incorrect_author(): void
    {
        $request = Request::create('/api/nyt/best-sellers', 'GET', [
            'offset' => 23,
        ]);
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The offset field must be a multiple of 20');

        FilterData::from($request);
    }

    public function test_validation_rules(): void
    {
        $rules = FilterData::rules();

        $this->assertArrayHasKey('author', $rules);
        $this->assertArrayHasKey('isbn', $rules);
        $this->assertArrayHasKey('title', $rules);
        $this->assertArrayHasKey('offset', $rules);
    }
}

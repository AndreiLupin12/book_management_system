<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_book_with_a_book_id(): void
    {
        $response = $this->postJson('/api/v1/books', [
            'book_id' => 'BK-001',
            'title' => 'Example Book',
            'author' => 'Example Author',
            'genre' => 'Fiction',
            'year_published' => 2020,
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('book_id', 'BK-001');

        $this->assertDatabaseHas('books', [
            'book_id' => 'BK-001',
        ]);
    }

    public function test_it_can_update_a_book_without_rejecting_its_existing_book_id(): void
    {
        $book = Book::create([
            'book_id' => 'BK-001',
            'title' => 'Example Book',
            'author' => 'Example Author',
            'genre' => 'Fiction',
            'year_published' => 2020,
        ]);

        $this->putJson("/api/v1/books/{$book->id}", [
            'book_id' => 'BK-001',
            'title' => 'Updated Book',
        ])
            ->assertOk()
            ->assertJsonPath('title', 'Updated Book');
    }
}

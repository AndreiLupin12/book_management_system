<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    // GET: Display all books
    public function index()
    {
        return response()->json(
            Book::all(),
            200
        );
    }


    // POST: Create a book
    public function store(Request $request)
    {
        $data = $request->validate([
            'book_id' => 'required|string|max:30|unique:books,book_id',
            'title' => 'required|string|max:100',
            'author' => 'required|string|max:100',
            'genre' => 'required|string|max:100',
            'year_published' => 'required|integer|min:0',
        ]); 
        $book = Book::create($data);
        return response()->json($book, 201);
    }

    // GET: Display one book
    public function show(Book $book)
    {
        return response()->json($book, 200);
    }


    // PUT/PATCH: Update a book
    public function update(
        Request $request,
        Book $book
    ) {
        $data = $request->validate([
            'book_id' => [
                'sometimes',
                'required',
                'string',
                'max:30',
                Rule::unique('books', 'book_id')->ignore($book->book_id, 'book_id'),
            ],
            'title' => 'sometimes|required|string|max:100',
            'author' => 'sometimes|required|string|max:100',
            'genre' => 'sometimes|required|string|max:100',
            'year_published' => 'sometimes|required|integer|min:0',
        ]);
        $book->update($data);
        return response()->json($book, 200);
    }


    // DELETE: Remove a book
    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json([
            'message' => 'Book deleted successfully.'
        ], 200);
    }
}

<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\BookStoreRequest;
use App\Http\Requests\v1\BookUpdateRequest;
use App\Models\v1\Book;
use App\Traits\v1\ApiResponse;
use App\Traits\v1\FormatsBook;
use Illuminate\Http\Request;

class BookController extends Controller
{
    use ApiResponse, FormatsBook;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with('category')->paginate(10);

        $formattedBooks = $books->transform(function ($book) {
            return $this->formatBook($book);
        });

        return $this->successResponse([
            'books' => $formattedBooks,
            'current_page' => $books->currentPage(),
            'total_pages' => $books->lastPage(),
            'total_books' => $books->total(),
        ], 'Books retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookStoreRequest $request)
    {
        $book = Book::create([
            'name' => $request->name,
            'owner' => $request->owner,
            'about' => $request->about,
            'category_id' => $request->category_id,
        ]);

        return $this->successResponse(
            $book,
        'Successfully added book',201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::with('category')->find($id);

        if (!$book) {
            return $this->errorResponse('Book not found', 404);
        }

        return $this->successResponse([
            'id' => $book->id,
            'name' => $book->name,
            'owner' => $book->owner,
            'about' => $book->about,
            'category' => $book->category?->name ?? 'Uncategorized', // Fallback for null categories
            'created_at' => $book->created_at,
            'updated_at' => $book->updated_at,
        ], 'Book retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookUpdateRequest $request, string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return $this->errorResponse('Book not found', 404);
        }

        $book->update($request->validated());

        return $this->successResponse($book, 'Book updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return $this->errorResponse('Book not found', 404);
        }

        $book->delete();

        return $this->successResponse(null, 'Book deleted successfully');
    }


    public function search(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return $this->errorResponse('Search query is required');
        }

        $books = Book::with('category')
            ->where('name', 'LIKE', "%$query%")
            ->orWhere('owner', 'LIKE', "%$query%")
            ->orWhereHas('category', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%");
            })
            ->paginate(10);

        if ($books->isEmpty()) {
            return $this->errorResponse('No book found matching the search criteria', 404);
        }

        $formattedBooks = $books->transform(function ($book) {
            return $this->formatBook($book);
        });

        return $this->successResponse([
            'books' => $formattedBooks,
            'current_page' => $books->currentPage(),
            'total_pages' => $books->lastPage(),
            'total_books' => $books->total(),
        ], 'search results retrieved successfully');
    }
}

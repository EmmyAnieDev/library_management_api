<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Requests\v2\BookStoreV2Request;
use App\Http\Requests\v2\BookUpdateV2Request;
use App\Models\v2\BookV2;
use App\Traits\v1\ApiResponse;
use App\Traits\v2\FormatsBookV2;
use Illuminate\Http\Request;

class BookV2Controller extends Controller
{
    use ApiResponse, FormatsBookV2;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = BookV2::with('category')->paginate(10);

        if ($books->isEmpty()) {
            return $this->successResponse([], 'No books available');
        }

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
    public function store(BookStoreV2Request $request)
    {
        $book = BookV2::create([
            'title' => $request->title,
            'author' => $request->author,
            'description' => $request->description,
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
        $book = BookV2::with('category')->find($id);

        if (!$book) {
            return $this->errorResponse('Book not found', 404);
        }

        return $this->successResponse([
            'id' => $book->id,
            'title' => $book->title,
            'author' => $book->author,
            'description' => $book->description,
            'category' => $book->category?->name ?? 'Uncategorized', // Fallback for null categories
            'created_at' => $book->created_at,
            'updated_at' => $book->updated_at,
        ], 'Book retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookUpdateV2Request $request, string $id)
    {
        $book = BookV2::find($id);

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
        $book = BookV2::find($id);

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

        $books = BookV2::with('category')
            ->where('title', 'LIKE', "%$query%")
            ->orWhere('author', 'LIKE', "%$query%")
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

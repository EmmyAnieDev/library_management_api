<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\CategoryStoreRequest;
use App\Http\Requests\v1\CategoryUpdateRequest;
use App\Models\v1\Category;
use App\Traits\v1\ApiResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('books')->get();

        $categoryData = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'books' => $category->books->pluck('name'),
            ];
        });

        return $this->successResponse($categoryData, 'Successfully retrieved categories with books');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request)
    {
        $category = Category::create([
            'name' => $request->name,
        ]);

        return $this->successResponse(
            $category,
            'Successfully added category',201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::with('books')->find($id);

        if (!$category) {
            return $this->errorResponse('Category not found', 404);
        }

        $categoryData = [
                'id' => $category->id,
                'name' => $category->name,
                'books' => $category->books->pluck('name'),
        ];


        return $this->successResponse($categoryData, 'category retrieved successfully with books');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->errorResponse('Category not found', 404);
        }

        $category->update($request->validated());

        return $this->successResponse($category, 'Category updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->errorResponse('Category not found', 404);
        }

        $category->delete();

        return $this->successResponse(null, 'Category deleted successfully');
    }


    public function search(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return $this->errorResponse('Search query is required');
        }

        $categories = Category::with('books')
            ->where('name', 'LIKE', "%$query%")
            ->orWhereHas('books', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%");
            })
            ->get();

        if ($categories->isEmpty()) {
            return $this->errorResponse('No category found matching the search criteria', 404);
        }

        $categoryData = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'books' => $category->books->pluck('name'),
            ];
        });

        return $this->successResponse($categoryData, 'search results retrieved successfully');
    }
}

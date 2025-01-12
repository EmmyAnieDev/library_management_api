<?php

namespace App\Traits\v2;

trait FormatsBookV2
{
    /**
     * Format a book for the response.
     *
     * @param \App\Models\BookV2 $book
     * @return array
     */
    public function formatBook($book): array
    {
        return [
            'id' => $book->id,
            'title' => $book->title,
            'author' => $book->author,
            'description' => $book->description,
            'category' => $book->category?->name ?? 'Uncategorized', // Fallback for null categories
            'created_at' => $book->created_at,
            'updated_at' => $book->updated_at,
        ];
    }
}

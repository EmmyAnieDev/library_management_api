<?php

namespace App\Traits\v1;

trait FormatsBook
{
    /**
     * Format a book for the response.
     *
     * @param \App\Models\Book $book
     * @return array
     */
    public function formatBook($book): array
    {
        return [
            'id' => $book->id,
            'name' => $book->name,
            'owner' => $book->owner,
            'about' => $book->about,
            'category' => $book->category?->name ?? 'Uncategorized', // Fallback for null categories
            'created_at' => $book->created_at,
            'updated_at' => $book->updated_at,
        ];
    }
}

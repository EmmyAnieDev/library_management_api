<?php

namespace App\Models\v2;

use App\Models\v1\Category;
use Illuminate\Database\Eloquent\Model;

class BookV2 extends Model
{

    protected $table = 'books_v2';

    protected $fillable = ['title', 'author', 'description', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

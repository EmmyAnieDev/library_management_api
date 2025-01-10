<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['name', 'owner', 'about', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

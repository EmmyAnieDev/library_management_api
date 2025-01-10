<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['id', 'name'];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'isbn',
        'author_id',
        'publication_year',
    ];

    /**
     * Relationships
     */
    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}

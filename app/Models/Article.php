<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /** @use HasFactory<\Database\Factories\ArticleFactory> */
    use HasFactory;

    // cast published_at as datetime
    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected $fillable = [
        'category_id',
        'news_source_id',
        'author_id',
        'title',
        'description',
        'content',
        'url',
        'image',
        'published_at',
    ];

    // Article belongs to one category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Article belongs to one news source
    public function newsSource()
    {
        return $this->belongsTo(NewsSource::class, 'news_source_id');
    }

    // Article belongs to one author
    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}

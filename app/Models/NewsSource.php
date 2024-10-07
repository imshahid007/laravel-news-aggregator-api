<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsSource extends Model
{
    protected $fillable = ['name', 'url'];

    // News source has many articles
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}

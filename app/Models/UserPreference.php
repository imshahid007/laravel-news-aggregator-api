<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    // Define the fillable fields
    protected $fillable = ['user_id', 'preferred_categories', 'preferred_sources', 'preferred_authors'];

    // Cast the JSON fields to arrays
    protected $casts = [
        'preferred_categories' => 'array',
        'preferred_sources' => 'array',
        'preferred_authors' => 'array',
    ];

    // Relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Mutator for preferred_categories
    public function setPreferredCategoriesAttribute($value)
    {
        // Ensure it's an array of values, not key-value pairs
        $this->attributes['preferred_categories'] = json_encode(array_values($value));
    }

    // Mutator for preferred_sources
    public function setPreferredSourcesAttribute($value)
    {
        // Ensure it's an array of values, not key-value pairs
        $this->attributes['preferred_sources'] = json_encode(array_values($value));
    }

    // Mutator for preferred_authors
    public function setPreferredAuthorsAttribute($value)
    {
        $this->attributes['preferred_authors'] = json_encode(array_values($value));
    }

    // Get the full category objects
    public function getPreferredCategoriesDataAttribute()
    {
        return Category::whereIn('id', $this->preferred_categories)->get();
    }

    // Get the full source objects
    public function getPreferredSourcesDataAttribute()
    {
        return NewsSource::whereIn('id', $this->preferred_sources)->get();
    }

    // Get the full author objects
    public function getPreferredAuthorsDataAttribute()
    {
        return Author::whereIn('id', $this->preferred_authors)->get();
    }
}

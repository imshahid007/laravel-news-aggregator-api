<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPreferenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->user_id,
            'preferred_categories' => CategoryResource::collection($this->preferredCategoriesData),
            'preferred_sources' => NewsSourceResource::collection($this->preferredSourcesData),
            'preferred_authors' => AuthorResource::collection($this->preferredAuthorsData),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}

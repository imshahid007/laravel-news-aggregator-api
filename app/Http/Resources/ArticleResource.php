<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category' => new CategoryResource($this->category),
            'source' => new NewsSourceResource($this->newsSource),
            'author' => new AuthorResource($this->author),
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'url' => $this->url,
            'image' => $this->image,
            'published_at' => $this->published_at,
        ];
    }
}

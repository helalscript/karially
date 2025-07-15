<?php

namespace App\Http\Resources\API\V1\CMS;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetFeatureSectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this['id'],
            'title' => $this['title'],
            'description' => $this['description'],
            'contents' => $this['contents']->map(function ($item) {
                return [
                    'id' => $item->id,
                    'section_title' => $item->title,
                    'section_description' => $item->description,
                    'button_text' => $item->button_text,
                    'image' => $item->image,
                    'tags' => json_decode($item->tags),
                ];
            }),
        ];
    }
}

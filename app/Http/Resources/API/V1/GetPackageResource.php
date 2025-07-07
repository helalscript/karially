<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetPackageResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'trial_period_days' => $this->trial_period_days,
            'billing_cycle' => $this->billing_cycle,
            'price' => $this->price,
            'status' => $this->status,
            'features' => $this->whenLoaded('features', function () {
                return $this->features->map(function ($feature) {
                    return [
                        'id' => $feature->id,
                        'title' => $feature->title,
                        'description' => $feature->pivot->description,
                    ];
                });
            }),
        ];
    }
}

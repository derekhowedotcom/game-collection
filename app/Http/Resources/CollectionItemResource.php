<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class CollectionItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request): array|JsonSerializable|Arrayable
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'category_id' => $this->category_id,
            'category' => $this->category->name,
            'rarity_id' => $this->rarity_id,
            'rarity' => $this->rarity->name ?? null,
            'value' => $this->value,
            'price_paid' => $this->price_paid,
            'boxed' => $this->boxed,
            'description' => $this->description,
            'thumbnail' => $this->thumbnail,
            'cex_image' => $this->cex_image,
            'barcode' => $this->barcode,
            'created_at' => $this->created_at->toDateString()
        ];
    }
}

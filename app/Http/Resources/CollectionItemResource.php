<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CollectionItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'category_id' => $this->category_id,
            'category' => $this->category->name,
            'value' => $this->value,
            'price_paid' => $this->price_paid,
            'description' => $this->description,
            'thumbnail' => $this->thumbnail,
            'cex_image' => $this->cex_image,
            'barcode' => $this->barcode,
            'created_at' => $this->created_at->toDateString()
        ];
    }
}

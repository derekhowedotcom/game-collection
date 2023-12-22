<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectionItem extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'barcode', 'value', 'price_paid', 'thumbnail', 'cex_image', 'category_id'];


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function rarity(): BelongsTo
    {
        return $this->belongsTo(Rarity::class);
    }

}

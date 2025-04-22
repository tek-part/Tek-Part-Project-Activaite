<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'product_id',
        'sku',
        'description',
        'price',
        'version',
        'is_active',
        'features',
        'settings',
        'image_path',
        'category_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'features' => 'json',
        'settings' => 'json',
    ];

    public function licenses()
    {
        return $this->hasMany(License::class, 'product_id', 'product_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public static function generateProductId(): string
    {
        return Str::slug(Str::random(8));
    }
}

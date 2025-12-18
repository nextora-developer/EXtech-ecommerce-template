<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'options',   // JSON
        'price',
        'stock',
        'image',
        'is_active',
    ];

    protected $casts = [
        'options' => 'array', // 自动转 array <-> json
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /*
    |--------------------------------------------------------------------------
    |  Accessors: $variant->label / $variant->value
    |--------------------------------------------------------------------------
    |
    |  这样 Blade 里面用:
    |     value="{{ $variant->label }}"
    |     value="{{ $variant->value }}"
    |  就会从 options['label'] / options['value'] 拿数据。
    */

    public function getLabelAttribute()
    {
        $opts = $this->options ?? [];
        return $opts['label'] ?? null;
    }

    public function getValueAttribute()
    {
        $opts = $this->options ?? [];
        return $opts['value'] ?? null;
    }

    /*
    |--------------------------------------------------------------------------
    |  Mutators: 允许你在代码里改 $variant->label / $variant->value
    |--------------------------------------------------------------------------
    |
    |  如果以后你在 controller 里想:
    |     $variant->label = 'Color / Size';
    |  它会自动写回 options JSON.
    */

    public function setLabelAttribute($value): void
    {
        $opts = $this->options ?? [];
        $opts['label'] = $value;
        $this->options = $opts;
    }

    public function setValueAttribute($value): void
    {
        $opts = $this->options ?? [];
        $opts['value'] = $value;
        $this->options = $opts;
    }
}

<?php

namespace App\Models;

use App\Models\ProductPurchase;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * ? The attributes that are mass assignable.
     * @var array<string>
     */

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'available',
    ];

    /**
     * ? Create the instance for all purchases of the product.
     */
    public function purchases()
    {
        return $this->belongsToMany(Purchase::class)->withPivot('quantity');
    }

    /**
     * ? Create the instance for the relation product_purchase.
     */
    public function product_purchase()
    {
        return $this->hasMany(ProductPurchase::class);
    }
}

<?php

namespace App\Models;

use App\Models\Product;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPurchase extends Model
{
    use HasFactory;

    /**
     * Define the table name for the model.
     */

    protected $table = 'product_purchase';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quantity',
    ];

    /**
     * Get the product that owns the product purchase.
     */

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the purchase that owns the product purchase.
     */

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}

<?php

namespace App\Models;

use App\Models\Product;
use App\Models\StatusPurchase;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status_purchase_id',
    ];

    /**
     * ? Create the instance for status purchase.
     */
    public function statusPurchase()
    {
        return $this->belongsTo( StatusPurchase::class );
    }

    /**
     * ? Create the instance for all products of the purchase.
     */
    public function products()
    {
        return $this->belongsToMany( Product::class )->withPivot('quantity');
    }
}

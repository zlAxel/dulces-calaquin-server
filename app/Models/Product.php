<?php

namespace App\Models;

use App\Models\Purchase;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * ? Create the instance for all purchases of the product.
     */
    public function purchases()
    {
        return $this->belongsToMany(Purchase::class)->withPivot('quantity');
    }
}

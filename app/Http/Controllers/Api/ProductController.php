<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;

use App\Models\Product;
use App\Models\ProductPurchase;

use DB;
use Illuminate\Http\Request;

class ProductController extends Controller {
    
    /**
     * Protect the routes for this controller.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        // ? Devolvemos la colección de productos
        return new ProductCollection( Product::where('available', true)->orderBy('id', 'DESC')->get() );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        //
    }

    /**
     * Display a listing of the top products selled.
     */
    
    public function top_products()
    {
        /**
         * ? Buscamos de la tabla "products" los productos "available => 1", después vamos a la relación muchos a muchos 
         * ? "product_purchase" y vemos cuales son los productos que más se han vendido según la columna "quantity" de la tabla
        */
        // $products = Product::where('available', true)->withCount('product_purchase')->orderBy('product_purchase_count', 'DESC')->take(10)->get();
        $products = Product::where('available', true)->withCount(['product_purchase' => function ($query) {
            $query->select(DB::raw('SUM(quantity) as total'));
        }])->orderBy('product_purchase_count', 'DESC')->take(12)->get();
        
        // ? Retornamos la respuesta 200 = OK
        return response()->json([
            'products' => $products,
        ], 200);
    }
}

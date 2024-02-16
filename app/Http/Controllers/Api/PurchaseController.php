<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    
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
    public function index()
    {
        /**
         * ? Obtenemos las ordenes, obtenemos la relación pivote "product_purchase", obtenemos del modelo "products" el precio y 
         * ? los recorremos para obtener el total ($) multiplicado por la cantidad.
         * ? Al final también obtenemos la descripción del status_purchase_id
         *  
        */ 

        $purchases = auth()->user()
                            ->purchases()
                            ->with('products')
                            ->get()
                            ->map(function ($purchase) {
                                $purchase->total = $purchase->products->map(function ($product) {
                                    return $product->pivot->quantity * $product->price;
                                })->sum();
                                $purchase->status_purchase_desc = $purchase->statusPurchase->description;
                                return $purchase;
                            });
        
        

        // ? Retornamos la respuesta 200 = OK
        return response()->json([
            'purchases' => $purchases,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ? Recibimos el estatus de la compra y lo convertimos a un id
        $status_purchase = $request['status_purchase'];
        switch ($status_purchase) {
            case 'Pagado':
                $status_purchase_id = 1;
                break;
            case 'Pendiente':
                $status_purchase_id = 2;
                break;
        }
        
        // ? Creamos la compra
        $purchase = $request->user()->purchases()->create([
            'status_purchase_id' => $status_purchase_id,
        ]);

        // ? Creamos los productos de la compra
        $products = $request['products'];
        foreach ($products as $product) {
            $purchase->products()->attach($product['id'], [
                'quantity' => $product['amount'],
            ]);
        }

        // ? Retornamos la respuesta 201 = Created
        return response()->json([
            'message' => 'Compra creada correctamente',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Display a list of recent purchases.
     */

     public function recent_purchases()
     {
         // ? Obtenemos solamente los últimos 10 productos de las compras del usuario autenticado y eliminamos los productos repetidos
         $purchases = auth()->user()
                            ->purchases()       // * Obtenemos las compras
                            ->with('products')  // * Obtenemos la relación del modelo Purchase con el modelo Product
                            ->latest()          // * Ordenamos de forma descendente
                            ->take(5)           // * Tomamos los últimos 5 registros  
                            ->get()             // * Finalmente obtenemos los registros
                            ->pluck('products') // * Obtenemos solamente los productos de las compras
                            ->flatten()         // * Aplanamos la colección
                            ->unique('id')      // * Eliminamos los productos repetidos
                            ->values()          // * Quitamos los índices que "unique" agrega
                            ->take(10);         // * Finalmente tomamos los últimos 10 productos
 
         // ? Retornamos la respuesta 200 = OK
         return response()->json([
             'purchases' => $purchases,
         ], 200);
     }
}

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
        //
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
}

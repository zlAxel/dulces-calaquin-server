<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;

use App\Http\Resources\ProductCollection;
use App\Models\Product;

use App\Models\ProductPurchase;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
     * Display a listing of all the products.
     */
    public function all_products(){
        // ? Devolvemos la colección de productos
        return new ProductCollection( Product::orderBy('id', 'DESC')->get() );
    }

    /**
     * Store a newly created resource in storage.
     * Elements to receive: name, description, price, available, image
     */
    public function store(ProductRequest $request) {

        $image = $request->file('image');                           // Obtenemos el archivo de la imagen
        $nameImage = Str::uuid().".".$image->extension();           // Generamos un nombre único para la imagen
        $image->move(public_path('images/products'), $nameImage);   // Movemos la imagen a la carpeta "public/images/products"
        
        // ? Creamos el producto
        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price, 
            'available' => $request->available, 
            'image' => $nameImage,
        ]);
        
        // ? Retornamos la respuesta 201 = CREATED
        return response()->json([
            'message' => 'Tu producto se ha creado correctamente.',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        // ? Devolvemos la colección de productos
        return new ProductCollection( Product::where('id', $id)->get() );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        // ? Buscamos el producto por su id
        $product = Product::find($id);

        // ? Validamos el request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric',
            'available' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        // ? Si no se encontró el producto retornamos un error 404
        if( ! $product ){
            return response()->json([
                'message' => 'Producto no encontrado',
            ], 404);
        }
        
        // ? Si se encontró el producto, actualizamos sus datos
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price, 
            'available' => $request->available, 
        ]);
        
        // ? Si se envió una imagen, actualizamos la imagen
        if($request->file('image')){
            // ? Eliminamos la imagen anterior
            unlink(public_path('images/products/'.$product->image));
            
            // ? Obtenemos la nueva imagen
            $image = $request->file('image');
            
            // ? Generamos un nombre único para la imagen
            $nameImage = Str::uuid().".".$image->extension();
            
            // ? Movemos la imagen a la carpeta "public/images/products"
            $image->move(public_path('images/products'), $nameImage);
            
            // ? Actualizamos la imagen del producto
            $product->update([
                'image' => $nameImage,
            ]);
        }
        
        // ? Retornamos la respuesta 200 = OK
        return response()->json([
            'message' => "El producto $product->name se ha actualizado correctamente.",
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        // ? Buscamos el producto por su id
        $product = Product::find($id);
        
        // ? Si no se encontró el producto retornamos un error 404
        if( ! $product ){
            return response()->json([
                'message' => 'Producto no encontrado',
            ], 404);
        }
        
        // ? Eliminamos la imagen del producto
        unlink(public_path('images/products/'.$product->image));
        
        // ? Eliminamos el producto
        $product->delete();
        
        // ? Retornamos la respuesta 200 = OK
        return response()->json([
            'message' => "El producto $product->name se ha eliminado correctamente."
        ], 200);
    }

    /**
     * Display a listing of the top products selled.
     */
    
    public function top_products()
    {
        /**
         * ? Buscamos de la tabla "products" los productos "available => 1", después vamos a la relación muchos a muchos 
         * ? "product_purchase" y vemos cuales son los productos que más se han vendido según la columna "quantity" de la tabla
         * 
         * ? Y por último recorremos cada producto para construir la URL de la imagen
        */
        $products = Product::where('available', true)->withCount(['product_purchase' => function ($query) {
            $query->select(DB::raw('SUM(quantity) as total'));
        }])->orderBy('product_purchase_count', 'DESC')->take(12)->get();
        
        foreach($products as $product){
            $image = $product->image;
            $product->image = request()->getSchemeAndHttpHost().'/images/products/'.$image;
        }
        
        // ? Retornamos la respuesta 200 = OK
        return response()->json([
            'products' => $products,
        ], 200);
    }

    public function product_available(Request $request, string $id)
    {
        // Obtenemos el $id y el $available(0 o 1) del request
        $available = $request->available;
        $description = $available ? 'activado' : 'inactivado';
        
        // Buscamos el producto por su id
        $product = Product::find($id);
        
        // Si no se encontró el producto retornamos un error 404
        if(!$product){
            return response()->json([
                'message' => 'Producto no encontrado',
            ], 404);
        }
        
        // Cambiamos el estado del producto
        $product->available = $available;
        $product->save();
        
        // ? Retornamos la respuesta 200 = OK
        return response()->json([
            'message' => "El producto {$product->name} ha sido {$description}.",
        ], 200);
    }
}

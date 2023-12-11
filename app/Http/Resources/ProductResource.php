<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
        /**
         * Para la imagen construiremos la ruta usando
         * la ruta completa del backend y concatenando la imagen
         * 
         * Ejemplo:
         * http://localhost:8000/images/products/uuid.jpg
         */

        $image = $this->image;
        $dir = $request->getSchemeAndHttpHost().'/images/products/'.$image;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'image' => $dir,
            'available' => $this->available,
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}

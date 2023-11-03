<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeder is used to seed the products table.
     */
    public function run(): void {
        DB::table('products')->insert([
            [
                'name' => 'Coca Cola',
                'description' => 'Bebida gaseosa de cola de 600 ml',
                'price' => 19.00,
                'image' => 'https://www.coca-cola.com/content/dam/onexp/mx/es/brands/coca-cola/coca-cola-original/Product-Information-Section-Coca-Cola-Original.jpg',
                'available' => true,
            ],
            [
                'name' => 'Fritos limón y sal',
                'description' => 'Papas fritas de limón y sal de 86g',
                'price' => 17.00,
                'image' => 'https://i5.walmartimages.com.mx/gr/images/product-images/img_large/00750047802084L.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF',
                'available' => true,
            ],
            [
                'name' => 'Galletas de animalitos',
                'description' => 'Galletas de animalitos de 40g',
                'price' => 5.00,
                'image' => 'https://m.media-amazon.com/images/I/61LA1giEvNL._AC_UF1000,1000_QL80_.jpg',
                'available' => true,
            ],
            [
                'name' => 'Galletas de chocolate',
                'description' => 'Galletas de chocolate de 40g',
                'price' => 5.00,
                'image' => 'https://hebmx.vtexassets.com/arquivos/ids/655442-800-800?v=638218630931670000&width=800&height=800&aspect=true',
                'available' => true,
            ],
            [
                'name' => 'Papas sabritas',
                'description' => 'Papas fritas de 86g',
                'price' => 17.00,
                'image' => 'https://i5.walmartimages.com.mx/gr/images/product-images/img_large/00750101116765L.jpg',
                'available' => true,
            ],
            [
                'name' => 'Doritos nacho',
                'description' => 'Doritos nacho de 80g',
                'price' => 17.00,
                'image' => 'https://m.media-amazon.com/images/I/41ABHsynlHL._AC_.jpg',
                'available' => true,
            ],
            [
                'name' => 'Agua Ciel',
                'description' => 'Agua purificada de 600ml',
                'price' => 10.00,
                'image' => 'https://oneiconn.vtexassets.com/arquivos/ids/185456/CIEL-AGUA-P-PET-1LT.jpg?v=638061145841870000',
                'available' => true,
            ],
            [
                'name' => 'Bubulubu',
                'description' => 'Bubulubu de 50g',
                'price' => 7.00,
                'image' => 'https://detqhtv6m6lzl.cloudfront.net/wp-content/uploads/2021/05/mini_7432354-1.jpg',
                'available' => true,
            ],
            [
                'name' => 'Chocorroles',
                'description' => 'Chocorroles de 50g',
                'price' => 7.00,
                'image' => 'https://subodega.mx/articulo/4473/01.webp',
                'available' => true,
            ],
            [
                'name' => 'Choco milk',
                'description' => 'Choco milk de 200ml',
                'price' => 10.00,
                'image' => 'https://i5.walmartimages.com.mx/gr/images/product-images/img_large/00750620580762L.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF',
                'available' => true,
            ],
            [
                'name' => 'Sabritas flamin hot',
                'description' => 'Sabritas flamin hot de 80g',
                'price' => 18.00,
                'image' => 'https://images.sabroson.com.mx/insecure/fit/1000/1000/ce/0/plain/https://sabroson-assests.s3.us-west-2.amazonaws.com/af268c/prods/ROlQxbb4WjJYgnl7TQSfKCtEWm2PWZLzO13XPpaT.webp@webp',
                'available' => true,
            ],
        ]);
    }
}


<?php

use Illuminate\Support\Facades\Route;

use App\Models\Product;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/product/{id}', function ($id) {
    $product = Product::find($id);
    if (!$product) {
        return response()->json(['error' => 'Producto no encontrado'], 404);
    }
    return response()->json([
        'reference'   => $product->reference,
        'description' => $product->description,
        'price'       => $product->price,
        'cost'        => $product->cost,
    ]);
});

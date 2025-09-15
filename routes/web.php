

<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

// API para obtener el precio de un producto por ID

Route::get('/admin/api/product-price/{id}', function ($id) {
    $product = Product::find($id);
    return response()->json([
        'price' => $product ? $product->price : 0
    ]);
});

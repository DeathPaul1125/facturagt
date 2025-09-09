<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Product extends Model
{
    use AsSource;

    protected $fillable = [
        'name',
        'code',
        'barcode',
        'price',
        'cost',
        'stock',
        'unit',
        'type',
    ];
}

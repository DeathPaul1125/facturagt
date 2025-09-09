<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Supplier extends Model
{
    use AsSource;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'phone2',
        'nit'
    ];
}

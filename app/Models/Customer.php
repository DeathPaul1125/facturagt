<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Customer extends Model
{
    use AsSource;

    protected $fillable = [
        'name',
        'nit',
        'address',
        'phone',
        'phone2',
        'email',
    ];
}

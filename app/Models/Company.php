<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Company extends Model
{
    use AsSource;

    protected $fillable  = [
        'name',
        'nit',
        'address',
        'phone',
        'phone2',
        'email',
        'user_fel',
        'password_fel',
        'token_fel',
        'produccion',
        'city',
        'state',
        'country',
        'zip',
    ];
}

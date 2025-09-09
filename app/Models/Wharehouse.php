<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Wharehouse extends Model
{
    use AsSource;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'phone2',
        'email',
        'sat_code',
        'company_id',
    ];
}

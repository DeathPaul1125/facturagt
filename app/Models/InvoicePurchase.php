<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class InvoicePurchase extends Model
{
    use AsSource;

    protected $fillable = [
        'invoice_number',
        'serie_fel',
        'number_fel',
        'authorization_number_fel',
        'date_fel',
        'date',
        'supplier_id',
    ];
}

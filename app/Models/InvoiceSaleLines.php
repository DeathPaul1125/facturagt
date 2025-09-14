<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceSaleLines extends Model
{
    protected $fillable = [
        'invoice_sale_id',
        'product_id',
        'type',
        'quantity',
        'unit_price',
        'total_price',
    ];

    public function invoice()
    {
        return $this->belongsTo(InvoiceSale::class, 'invoice_sale_id');
    }
}

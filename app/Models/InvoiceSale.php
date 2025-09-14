<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Screen\AsSource;

class InvoiceSale extends Model
{
    use AsSource;

    protected $fillable = [
        'customer_id',
        'date',
        'series',
        'document_number',
        'total_amount', // Assuming you might have these fields
        'tax',          // Assuming you might have these fields
    ];

    /**
     * Get the lines for the invoice sale.
     */
    public function lines()
    {
        return $this->hasMany(InvoiceSaleLines::class, 'invoice_sale_id');
    }

    /**
     * Get the customer that owns the invoice.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

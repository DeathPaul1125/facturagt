<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoice_purchase_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_purchase_id');
            $table->unsignedBigInteger('product_id');
            $table->string('type')->default('B'); // 'bien' or 'servicio'
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->foreign('invoice_purchase_id')->references('id')->on('invoice_purchases')->onDelete('cascade');
            //$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_purchase_lines');
    }
};

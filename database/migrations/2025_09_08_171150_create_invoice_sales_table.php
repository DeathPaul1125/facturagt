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
        Schema::create('invoice_sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->string('serie_fel');
            $table->string('number_fel');
            $table->string('authorization_number_fel');
            $table->string('date_fel');
            $table->date('date');
            $table->unsignedBigInteger('customer_id');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_sales');
    }
};

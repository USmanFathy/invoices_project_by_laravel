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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number',50);
            $table->date('invoice_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('product' ,50);
            $table->foreignId('sections_id')->constrained('sections' ,'id')->cascadeOnDelete();
            $table->decimal('amount_collection',8,2)->nullable();
            $table->decimal('amount_comission' ,8,2);
            $table->decimal('discount');
            $table->string('rate_vat' ,999);
            $table->decimal('value_vat' , 8 ,2);
            $table->decimal('total' , 8 ,2);
            $table->string('status' ,50);
            $table->integer('value_status');
            $table->date('payment_date')->nullable();
            $table->text('note')->nullable();
            $table->string('user');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

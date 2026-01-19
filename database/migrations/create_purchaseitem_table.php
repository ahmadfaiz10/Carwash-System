<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchaseitem', function (Blueprint $table) {
            $table->id('PurchaseItemID');
            $table->unsignedInteger('PurchaseID');
            $table->unsignedInteger('ProductID');
            $table->integer('Quantity');
            $table->decimal('UnitPrice', 10, 2);
            $table->decimal('Subtotal', 10, 2);

            $table->foreign('PurchaseID')
                  ->references('PurchaseID')->on('purchase')
                  ->onDelete('cascade');

            $table->foreign('ProductID')
                  ->references('ProductID')->on('product')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchaseitem');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('purchaseitem', function (Blueprint $table) {
            $table->id('PurchaseItemID');

            $table->foreignId('PurchaseID')
                  ->constrained('purchase', 'PurchaseID')
                  ->cascadeOnDelete();

            $table->foreignId('ProductID')
                  ->constrained('product', 'ProductID')
                  ->cascadeOnDelete();

            $table->integer('Quantity');
            $table->decimal('UnitPrice', 10, 2);
            $table->decimal('Subtotal', 10, 2);
        });
    }

    public function down(): void {
        Schema::dropIfExists('purchaseitem');
    }
};


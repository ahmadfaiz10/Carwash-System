<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('product', function (Blueprint $table) {
            $table->id('ProductID');
            $table->string('ProductName', 100);
            $table->string('Category', 50)->nullable();
            $table->decimal('Price', 10, 2);
            $table->integer('StockQuantity');
            $table->text('Description')->nullable();
            $table->string('Image')->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists('product');
    }
};

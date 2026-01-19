<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchase', function (Blueprint $table) {
            $table->id('PurchaseID');
            $table->unsignedInteger('CustomerID');
            $table->decimal('TotalAmount', 10, 2);
            $table->string('PaymentMethod', 50)->nullable();
            $table->dateTime('PurchaseDate')->useCurrent();
            $table->string('PurchaseStatus', 50)->default('Completed');
            $table->string('DeliveryType', 30);
            $table->text('DeliveryAddress')->nullable();
            $table->decimal('DeliveryFee', 6, 2)->default(0);

            $table->foreign('CustomerID')
                  ->references('CustomerID')->on('customer')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase');
    }
};

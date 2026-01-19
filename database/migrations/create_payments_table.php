<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('PaymentID');
            $table->unsignedInteger('CustomerID');
            $table->unsignedInteger('BookingID')->nullable();
            $table->unsignedInteger('PurchaseID')->nullable();
            $table->decimal('Amount', 8, 2);
            $table->string('PaymentMethod', 50);
            $table->string('ReferenceNumber', 100)->nullable();
            $table->string('BankType', 50)->nullable();
            $table->string('OwnerName', 100)->nullable();
            $table->enum('PaymentStatus', ['Unpaid','Awaiting Cash Payment','Paid','Failed'])->default('Unpaid');
            $table->string('ReceiptPath')->nullable();
            $table->timestamps();

            $table->foreign('CustomerID')->references('CustomerID')->on('customer')->onDelete('cascade');
            $table->foreign('BookingID')->references('BookingID')->on('booking')->onDelete('set null');
            $table->foreign('PurchaseID')->references('PurchaseID')->on('purchase')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

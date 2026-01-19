<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('PaymentID');

            $table->foreignId('CustomerID')
                  ->constrained('customer', 'CustomerID')
                  ->cascadeOnDelete();

            $table->foreignId('BookingID')
                  ->nullable()
                  ->constrained('booking', 'BookingID')
                  ->nullOnDelete();

            $table->foreignId('PurchaseID')
                  ->nullable()
                  ->constrained('purchase', 'PurchaseID')
                  ->nullOnDelete();

            $table->decimal('Amount', 8, 2);
            $table->string('PaymentMethod', 50);
            $table->string('ReferenceNumber', 100)->nullable();
            $table->string('BankType', 50)->nullable();
            $table->string('OwnerName', 100)->nullable();
            $table->enum('PaymentStatus', ['Unpaid','Awaiting Cash Payment','Paid','Failed'])->default('Unpaid');
            $table->string('ReceiptPath')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('payments');
    }
};

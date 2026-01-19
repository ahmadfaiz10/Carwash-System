<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('booking', function (Blueprint $table) {
            $table->id('BookingID');

            $table->foreignId('CustomerID')
                  ->constrained('customer', 'CustomerID');

            $table->integer('id'); // service id
            $table->date('BookingDate');
            $table->time('BookingTime');
            $table->string('PlateNumber', 20);
            $table->string('BookingStatus', 50);
            $table->string('PaymentStatus', 50)->nullable();
            $table->string('PaymentMethod', 50)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void {
        Schema::dropIfExists('booking');
    }
};


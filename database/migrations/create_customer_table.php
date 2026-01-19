<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->id('CustomerID');
            $table->string('CustomerName', 100);
            $table->string('CustomerEmail', 100);
            $table->string('CustomerPhone', 15);
            $table->string('CustomerAddress', 255);
            $table->unsignedInteger('UserID')->nullable();

            $table->foreign('UserID')
                  ->references('UserID')->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};

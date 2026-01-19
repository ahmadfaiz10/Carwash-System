<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('owner', function (Blueprint $table) {
            $table->id('OwnerID');
            $table->string('OwnerName', 100);
            $table->string('OwnerEmail', 100);
            $table->string('OwnerPhone', 15);
            $table->string('OwnerAddress', 255);
            $table->unsignedInteger('UserID')->nullable();

            $table->foreign('UserID')
                  ->references('UserID')->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('owner');
    }
};

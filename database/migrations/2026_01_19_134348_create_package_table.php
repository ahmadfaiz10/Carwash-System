<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('package', function (Blueprint $table) {
            $table->id('PackageID');
            $table->string('PackName', 100);
            $table->text('PackDescription')->nullable();
            $table->decimal('PackPrice', 10, 2);
            $table->string('PackDuration', 50)->nullable();
            $table->string('PackAvailability', 50)->default('Available');
        });
    }

    public function down(): void {
        Schema::dropIfExists('package');
    }
};

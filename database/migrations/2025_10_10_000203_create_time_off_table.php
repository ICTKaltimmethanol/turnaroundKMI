<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('time_off', function (Blueprint $table) {
            $table->id();
            $table->enum('time_off_status', ['approved', 'pending', 'rejected']);
            $table->enum('time_off_action', ['sakit', 'melahirkan', 'duka']);
            $table->text('description');
            $table->foreignId('employees_id')->constrained('employees');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_off');
    }
};

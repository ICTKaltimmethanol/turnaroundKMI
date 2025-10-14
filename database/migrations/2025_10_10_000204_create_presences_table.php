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
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->integer('total_time');
            $table->foreignId('presenceIn_id')->constrained('presence_in');
            $table->foreignId('presenceOut_id')->constrained('presence_out');
            $table->foreignId('employees_id')->constrained('employees');
            $table->timestamp();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};

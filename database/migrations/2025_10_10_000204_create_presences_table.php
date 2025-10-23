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
            $table->integer('total_time')->nullable();
            $table->foreignId('presenceIn_id')->constrained('presence_in');
            $table->foreignId('presenceOut_id')->nullable()->constrained('presence_out');
            $table->foreignId('employees_id')->constrained('employees');
            $table->foreignId('employees_company');        
            $table->foreignId('employees_position');
            $table->timestamps();
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

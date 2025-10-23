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
        Schema::create('presence_out', function (Blueprint $table) {
            $table->id();
            $table->string('latitude_out')->nullable();
            $table->string('longitude_out')->nullable();
            $table->time('presence_time')->nullable();
            $table->date('presence_date')->nullable();
            $table->foreignId('employees_id')->nullable()->constrained('employees');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presence_out');
    }
};

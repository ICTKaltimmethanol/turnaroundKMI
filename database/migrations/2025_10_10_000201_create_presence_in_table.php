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
        Schema::create('presence_in', function (Blueprint $table) {
            $table->id();
            $table->enum('presence_in_status', ['on_time', 'late']);
            $table->string('latitude_in');
            $table->string('longitude_in');
            $table->time('presence_time');
            $table->date('presence_date');
            $table->foreignId('employees_id')->constrained('employees');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presence_in');
    }
};

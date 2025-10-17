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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('password');
            $table->string('employees_code')->unique();

            $table->string('full_name');
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('position_id')->constrained('positions');
            $table->string('profile_img_path')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended']);
            $table->timestamp();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

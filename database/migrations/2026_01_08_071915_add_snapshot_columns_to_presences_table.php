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
        Schema::table('presences', function (Blueprint $table) {
            $table->string('employee_name')->after('employees_id');
            $table->string('employee_code')->after('employee_name');

            $table->string('company_name')->nullable()->after('employee_code');
            $table->string('position_name')->nullable()->after('company_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presences', function (Blueprint $table) {
            $table->dropColumn([
                'employee_name',
                'employee_code',
                'company_name',
                'position_name',
            ]);
        });
    }
};

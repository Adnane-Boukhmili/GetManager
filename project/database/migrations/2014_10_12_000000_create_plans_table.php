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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('each_employee_price', 10, 2);
            $table->timestamps();
        });
        
        DB::table('plans')->insert([
            ['id' => 1, 'name' => 'Free trial', 'each_employee_price' => 0.00],
            ['id' => 2, 'name' => 'Monthly', 'each_employee_price' => 2.00],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};

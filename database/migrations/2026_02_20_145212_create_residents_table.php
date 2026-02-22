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
        Schema::create('residents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('full_name', 100);
            $table->enum('gender', ['male', 'female']);
            $table->date('date_of_birth')->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('national_id', 20)->nullable()->unique();
            $table->text('address')->nullable();
            $table->string('occupation', 100)->nullable();
            $table->string('ethnicity', 50)->nullable();
            $table->string('religion', 50)->nullable();
            $table->string('education_level', 50)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            
            $table->index('full_name');
            $table->index('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dan_cu');
    }
};

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
        Schema::create('temporary_residences', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('resident_id');
            $table->text('address');                          // Nơi tạm trú
            $table->string('host_name', 100)->nullable();    // Tên chủ nhà/chủ hộ nơi tạm trú
            $table->date('from_date');                       // Ngày bắt đầu tạm trú
            $table->date('to_date')->nullable();             // Ngày kết thúc tạm trú
            $table->text('reason')->nullable();              // Lý do tạm trú
            $table->timestamps();

            $table->foreign('resident_id')
                  ->references('id')->on('residents')
                  ->onDelete('cascade');

            $table->index('resident_id');
            $table->index('from_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporary_residences');
    }
};

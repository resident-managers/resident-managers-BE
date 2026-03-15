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
        Schema::create('temporary_absences', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('resident_id');
            $table->text('destination');                     // Nơi đến (tạm vắng đến đâu)
            $table->date('from_date');                       // Ngày bắt đầu tạm vắng
            $table->date('to_date')->nullable();             // Ngày dự kiến quay về
            $table->text('reason')->nullable();              // Lý do tạm vắng
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
        Schema::dropIfExists('temporary_absences');
    }
};

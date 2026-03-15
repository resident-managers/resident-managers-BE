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
        Schema::create('health_insurances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('resident_id');
            $table->string('code', 20)->unique();               // Mã thẻ BHYT (VD: DN4012001234567)
            $table->string('healthcare_facility')->nullable();  // Nơi KCB ban đầu
            $table->date('issued_date')->nullable();            // Ngày cấp (từ ngày)
            $table->date('expiry_date')->nullable();            // Ngày hết hạn (đến ngày)
            $table->timestamps();

            $table->foreign('resident_id')
                  ->references('id')->on('residents')
                  ->onDelete('cascade');

            $table->unique('resident_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_insurances');
    }
};

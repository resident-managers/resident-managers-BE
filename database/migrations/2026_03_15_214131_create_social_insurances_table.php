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
        Schema::create('social_insurances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('resident_id');
            $table->string('code', 10)->unique();               // Mã số BHXH (10 số)
            $table->string('employer')->nullable();             // Nơi đóng BHXH / tên đơn vị
            $table->date('enrolled_date')->nullable();          // Ngày tham gia
            $table->string('insurance_type')->default('compulsory'); // Bắt buộc / tự nguyện
            $table->string('status')->default('active');             // Trạng thái
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
        Schema::dropIfExists('social_insurances');
    }
};

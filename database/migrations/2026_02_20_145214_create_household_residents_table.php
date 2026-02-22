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
        Schema::create('household_residents', function (Blueprint $table) {
	        $table->uuid('household_id');         // ← đổi sang uuid
	        $table->uuid('resident_id');          // ← đổi sang uuid
	        $table->string('relationship', 50);
	        $table->timestamps();

	        $table->primary(['household_id', 'resident_id']); // ← composite PK

	        $table->foreign('household_id')
		        ->references('id')
		        ->on('households')
		        ->onDelete('cascade');          // xoá hộ → xoá luôn liên kết

	        $table->foreign('resident_id')
		        ->references('id')
		        ->on('residents')
		        ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('household_residents');
    }
};

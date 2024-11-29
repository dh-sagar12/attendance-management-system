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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table ->bigInteger('user_id') ;
            $table->enum('application_type', ['Absentism', 'PaidHoliday', 'PostpartumLeave', 'PrenatalLeave', 'BusinessTrip', 'Overtime']);
            $table->date('date');
            $table->time('start_time')-> nullable();
            $table-> time('end_time')->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_approved')->default('false');
            $table->bigInteger('approved_by')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });  
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};

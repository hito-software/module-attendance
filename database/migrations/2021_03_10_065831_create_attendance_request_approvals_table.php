<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceRequestApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_request_approvals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('attendance_request_id');
            $table->uuid('user_id');
            $table->boolean('is_approved')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('attendance_request_id')->references('id')->on('attendance_requests')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_request_approvals');
    }
}

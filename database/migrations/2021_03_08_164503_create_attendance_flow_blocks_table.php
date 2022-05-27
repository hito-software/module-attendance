<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceFlowBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_flow_blocks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('flow_id');
            $table->uuid('parent_id')->nullable();
            $table->string('type')->nullable();
            $table->string('value')->nullable();
            $table->uuid('model_id')->nullable();
            $table->string('model_type')->nullable();
            $table->integer('min')->nullable();
            $table->integer('order')->nullable();
            $table->enum('condition', ['OR', 'AND'])->nullable();
            $table->timestamps();
        });

        Schema::table('attendance_flow_blocks', function (Blueprint $table) {
            $table->foreign('flow_id')->references('id')->on('attendance_flows')->cascadeOnDelete();
            $table->foreign('parent_id')->references('id')->on('attendance_flow_blocks')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_flow_blocks');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('color')->nullable();
            $table->string('symbol')->nullable();
            $table->boolean('is_unavailable')->default(false);
            $table->uuid('attendance_flow_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('attendance_flow_id')->references('id')->on('attendance_flows')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_types');
    }
}

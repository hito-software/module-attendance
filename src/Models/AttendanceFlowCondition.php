<?php

namespace Hito\Modules\Attendance\Models;

use Hito\Core\Database\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceFlowCondition extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = [
        'attendance_flow_id',
        'attendance_block_id',
        'value'
    ];
}

<?php

namespace Hito\Modules\Attendance\Models;

use Hito\Core\Database\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hito\Modules\Attendance\Database\Factories\AttendanceTypeFactory;

class AttendanceType extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;

    protected $fillable = [
        'name',
        'description',
        'color',
        'symbol',
        'is_unavailable',
        'attendance_flow_id',
        'user_id'
    ];

    protected $casts = [
        'is_unavailable' => 'boolean'
    ];

    protected $with = [
        'flow'
    ];

    protected $hidden = [
        'id',
        'deleted_at'
    ];

    protected static function newFactory()
    {
        return AttendanceTypeFactory::new();
    }

    public function flow()
    {
        return $this->hasOne(AttendanceFlow::class, 'id', 'attendance_flow_id');
    }
}

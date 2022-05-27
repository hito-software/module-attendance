<?php

namespace Hito\Modules\Attendance\Models;

use Hito\Core\Database\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceFlow extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = [
        'name',
        'description',
        'condition',
        'user_id'
    ];

    protected $with = [
        'blocks'
    ];

    public function type()
    {
        return $this->hasMany(AttendanceType::class);
    }

    public function blocks()
    {
        return $this->hasMany(AttendanceFlowBlock::class, 'flow_id');
    }
}

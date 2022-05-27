<?php

namespace Hito\Modules\Attendance\Models;

use Hito\Platform\Models\User;
use Hito\Core\Database\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceRequest extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = [
        'user_id',
        'type_id',
        'description',
        'start_at',
        'end_at',
        'status'
    ];

    protected $with = [
        'type',
        'user',
        'approvals'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime'
    ];

    public function type()
    {
        return $this->belongsTo(AttendanceType::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvals()
    {
        return $this->hasMany(AttendanceRequestApproval::class);
    }
}

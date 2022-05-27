<?php

namespace Hito\Modules\Attendance\Models;

use Hito\Platform\Models\User;
use Hito\Core\Database\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class AttendanceRequestApproval extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = [
        'attendance_request_id',
        'user_id',
        'is_approved',
        'notes'
    ];

    protected $with = [
        'user'
    ];

    protected $casts = [
        'is_approved' => 'bool'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendanceRequest()
    {
        return $this->belongsTo(AttendanceRequest::class);
    }

    public function scopeVoted(Builder $query)
    {
        return $query->whereNotNull('is_approved');
    }

    public function scopeApproved(Builder $query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeRejected(Builder $query)
    {
        return $query->where('is_approved', false);
    }
}

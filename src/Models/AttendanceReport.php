<?php

namespace Hito\Modules\Attendance\Models;

use Hito\Core\Database\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceReport extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = [
        'users',
        'types',
        'start_at',
        'end_at',
        'user_id'
    ];

    protected $casts = [
        'users' => 'array',
        'types' => 'array',
        'start_at' => 'datetime',
        'end_at' => 'datetime'
    ];
}

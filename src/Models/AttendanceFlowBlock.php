<?php

namespace Hito\Modules\Attendance\Models;

use Hito\Core\Database\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceFlowBlock extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = [
        'flow_id',
        'parent_id',
        'type',
        'value',
        'min',
        'order',
        'model_id',
        'model_type',
        'condition'
    ];

    public function children()
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }
}

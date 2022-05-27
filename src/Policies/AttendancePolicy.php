<?php

namespace Hito\Modules\Attendance\Policies;

use Hito\Platform\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendancePolicy extends WebPolicy
{
    protected function getPrefixKey(): string
    {
        return 'attendance';
    }


    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $this->_viewAny($user);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @return bool
     */
    public function view(User $user): ?bool
    {
        return $user->can('attendance.view');
    }
}

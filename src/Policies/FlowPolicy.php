<?php

namespace Hito\Modules\Attendance\Policies;

use Hito\Modules\Attendance\Models\AttendanceFlow;
use Hito\Platform\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FlowPolicy extends  WebPolicy
{
    protected function getPrefixKey(): string
    {
        return 'flow';
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
    public function view(User $user, AttendanceFlow $flow): ?bool
    {
        $hasPermissions = $this->_view($user);

        if (is_null($hasPermissions)) {
            return $user->id === $flow->user_id;
        }

        return $hasPermissions;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $this->_create($user);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param AttendanceFlow $flow
     * @return bool
     */
    public function update(User $user, AttendanceFlow $flow): ?bool
    {
        $hasPermissions = $this->_update($user);

        if (is_null($hasPermissions)) {
            return $user->id === $flow->user_id;
        }

        return $hasPermissions;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param AttendanceFlow $flow
     * @return bool
     */
    public function delete(User $user, AttendanceFlow $flow): bool
    {
        $hasPermissions = $this->_delete($user);

        if (is_null($hasPermissions)) {
            return $user->id === $flow->user_id;
        }

        return $hasPermissions;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param AttendanceFlow $type
     * @return bool
     */
    public function restore(User $user, AttendanceFlow $flow): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param AttendanceFlow $flow
     * @return bool
     */
    public function forceDelete(User $user, AttendanceFlow $tflow): bool
    {
        return false;
    }
}

<?php

namespace Hito\Modules\Attendance\Policies;

use Hito\Modules\Attendance\Models\AttendanceType;
use Hito\Modules\Attendance\Policies\WebPolicy;
use Hito\Platform\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;


class TypePolicy extends WebPolicy
{
    
    protected function getPrefixKey(): string
    {
        return 'type';
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
    public function view(User $user, AttendanceType $type): ?bool
    {
        $hasPermissions = $this->_view($user);

        if (is_null($hasPermissions)) {
            return $user->id === $type->user_id;
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
     * @param AttendanceType $type
     * @return bool
     */
    public function update(User $user, AttendanceType $type): ?bool
    {
        $hasPermissions = $this->_update($user);

        if (is_null($hasPermissions)) {
            return $user->id === $type->user_id;
        }

        return $hasPermissions;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param AttendanceType $type
     * @return bool
     */
    public function delete(User $user, AttendanceType $type): bool
    {
        $hasPermissions = $this->_delete($user);

        if (is_null($hasPermissions)) {
            return $user->id === $type->user_id;
        }

        return $hasPermissions;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param AttendanceType $type
     * @return bool
     */
    public function restore(User $user, AttendanceType $type): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param AttendanceType $type
     * @return bool
     */
    public function forceDelete(User $user, AttendanceType $type): bool
    {
        return false;
    }
}

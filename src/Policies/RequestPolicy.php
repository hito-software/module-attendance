<?php

namespace Hito\Modules\Attendance\Policies;

use Hito\Platform\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Hito\Modules\Attendance\Models\AttendanceRequest;

class RequestPolicy extends WebPolicy
{
    protected function getPrefixKey(): string
    {
        return 'request';
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
    public function view(User $user, AttendanceRequest $request): ?bool
    {
        $hasPermissions = $this->_view($user);

        if (is_null($hasPermissions)) {
            return $user->id === $request->user_id;
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
}

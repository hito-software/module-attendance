<?php

namespace Hito\Modules\Attendance\Policies;

 use Hito\Platform\Models\User;

 abstract class WebPolicy
{
     abstract protected function getPrefixKey(): string;


     /**
      * Determine whether the user can view any models.
      *
      * @param User  $user
      * @return bool
      */
     protected function _viewAny(User $user): bool
     {
         return $user->can($this->getPermissionName('view-any')) ||
             $user->can($this->getPermissionName('view-own'));
     }

     /**
      * Determine whether the user can view the model.
      *
      * @param User $user
      * @return bool|null
      */
     protected function _view(User $user)
     {

         if ($user->can($this->getPermissionName('view-any'))) {
             return true;
         }

         if (!$user->can($this->getPermissionName('view-own'))) {
             return false;
         }

         return null;
     }

     /**
      * Determine whether the user can create models.
      *
      * @param User $user
      * @return bool
      */
     protected function _create(User $user): bool
     {
         return $user->can($this->getPermissionName('create'));
     }

     /**
      * Determine whether the user can update the model.
      *
      * @param User $user
      * @return bool|null
      */
     protected function _update(User $user): ?bool
     {
         if ($user->can($this->getPermissionName('update-any'))) {
             return true;
         }

         if (!$user->can($this->getPermissionName('update-own'))) {
             return false;
         }

         return null;
     }

     /**
      * Determine whether the user can delete the model.
      *
      * @param User $user
      * @return bool|null
      */
     protected function _delete(User $user): ?bool
     {
         if ($user->can($this->getPermissionName('delete-any'))) {
             return true;
         }

         if (!$user->can($this->getPermissionName('delete-own'))) {
             return false;
         }

         return null;
     }

     /**
      * Determine whether the user can restore the model.
      *
      * @param User $user
      * @return bool
      */
     protected function _restore(User $user): bool
     {
         return false;
     }

     /**
      * Determine whether the user can permanently delete the model.
      *
      * @param User $user
      * @return bool
      */
     protected function _forceDelete(User $user): bool
     {
         return false;
     }

     protected function _download(User $user): bool
     {
         return $user->can($this->getPermissionName('download'));
     }

     protected function getPermissionName(string $key): string
     {
         if (empty($this->getPrefixKey())) {
             return $key;
         }

         return $this->getPrefixKey() . '.' . $key;
     }
}

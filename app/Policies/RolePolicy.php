<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_role');
    }

    public function view(User $user, Role $role): bool
    {
        if ($role->name === 'super_admin') {
            return $user->hasRole('super_admin');
        }
        return $user->can('view_role');
    }

    public function create(User $user): bool
    {
        return $user->can('create_role');
    }

    public function update(User $user, Role $role): bool
    {
        if ($role->name === 'super_admin') {
            return false;
        }
        return $user->can('update_role');
    }

    public function delete(User $user, Role $role): bool
    {
        if ($role->name === 'super_admin') {
            return false;
        }
        return $user->can('delete_role');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_role');
    }
}

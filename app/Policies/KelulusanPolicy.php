<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Kelulusan;
use Illuminate\Auth\Access\HandlesAuthorization;

class KelulusanPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Kelulusan');
    }

    public function view(AuthUser $authUser, Kelulusan $kelulusan): bool
    {
        return $authUser->can('View:Kelulusan');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Kelulusan');
    }

    public function update(AuthUser $authUser, Kelulusan $kelulusan): bool
    {
        return $authUser->can('Update:Kelulusan');
    }

    public function delete(AuthUser $authUser, Kelulusan $kelulusan): bool
    {
        return $authUser->can('Delete:Kelulusan');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Kelulusan');
    }

    public function restore(AuthUser $authUser, Kelulusan $kelulusan): bool
    {
        return $authUser->can('Restore:Kelulusan');
    }

    public function forceDelete(AuthUser $authUser, Kelulusan $kelulusan): bool
    {
        return $authUser->can('ForceDelete:Kelulusan');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Kelulusan');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Kelulusan');
    }

    public function replicate(AuthUser $authUser, Kelulusan $kelulusan): bool
    {
        return $authUser->can('Replicate:Kelulusan');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Kelulusan');
    }

}
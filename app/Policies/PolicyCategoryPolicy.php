<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Policy\PolicyCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class PolicyCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any categories.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return true; // All authenticated users can view categories
    }

    /**
     * Determine whether the user can view the category.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Policy\PolicyCategory  $category
     * @return bool
     */
    public function view(User $user, PolicyCategory $category)
    {
        return true; // All authenticated users can view a category
    }

    /**
     * Determine whether the user can create categories.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasRole(['admin', 'policy_manager']);
    }

    /**
     * Determine whether the user can update the category.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Policy\PolicyCategory  $category
     * @return bool
     */
    public function update(User $user, PolicyCategory $category)
    {
        return $user->hasRole(['admin', 'policy_manager']);
    }

    /**
     * Determine whether the user can delete the category.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Policy\PolicyCategory  $category
     * @return bool
     */
    public function delete(User $user, PolicyCategory $category)
    {
        return $user->hasRole(['admin', 'policy_manager']);
    }
}

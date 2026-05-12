<?php

namespace App\Policies;

use App\Models\LoanApplication;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LoanApplicationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LoanApplication $loanApplication): bool
    {
        // Staff can view all applications
        if (User::isStaffRole($user->role)) {
            return true;
        }

        // Customers can only view their own applications
        return $user->id === $loanApplication->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'customer';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LoanApplication $loanApplication): bool
    {
        // Customers can update their own pending applications
        if ($user->role === 'customer') {
            return $user->id === $loanApplication->user_id && $loanApplication->status === 'pending';
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LoanApplication $loanApplication): bool
    {
        // Customers can delete their own pending applications
        if ($user->role === 'customer') {
            return $user->id === $loanApplication->user_id && $loanApplication->status === 'pending';
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LoanApplication $loanApplication): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LoanApplication $loanApplication): bool
    {
        return false;
    }
}

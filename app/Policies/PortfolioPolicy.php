<?php

namespace App\Policies;

use App\Portfolio;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PortfolioPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function add(User $user){

        foreach($user->roles as $role) {

            if($role->name == 'admin' || $role->name == 'moderator') {
                return TRUE;
            }
        }

        return FALSE; //FALSE
    }

    public function update(User $user){

        foreach($user->roles as $role) {
            if($role->name == 'admin') {
                    return TRUE;
            }
        }
        return FALSE;

    }
}

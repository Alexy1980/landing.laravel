<?php

namespace App\Policies;

use App\Page;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy
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

    public function update(User $user, Page $page){

        foreach($user->roles as $role) {
            if($role->name == 'admin') {
                if($user->id == $page->user_id) {
                    return TRUE;
                }
            }
        }
        return FALSE;

    }

}

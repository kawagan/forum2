<?php

namespace App\Policies;

use App\User;
use App\Thread;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThreadPolicy
{
    use HandlesAuthorization;

    // this function make kawa saleh admin, and admin can do any thing
    // so we repeat this code in all policy we make, but if we dont repeat this code
    // in all policies , we make Gate in boot in Authservcieprovider, to apply in all policies
    
//    public function before($user)
//    {
//     if($user->name=="kawa saleh"){
//         return true;
//     }   
//    }
    
    /**
     * Determine whether the user can view the thread.
     *
     * @param  \App\User  $user
     * @param  \App\Thread  $thread
     * @return mixed
     */
    public function view(User $user, Thread $thread)
    {
        //
    }

    /**
     * Determine whether the user can create threads.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the thread.
     *
     * @param  \App\User  $user
     * @param  \App\Thread  $thread
     * @return mixed
     */
    public function update(User $user, Thread $thread)
    {
        // this basic policy include,delete and edit and update
        // if want specific policy for delete you can write policy in delete function
        //php artisan make:policy ThreadPlicy --model=Thread to make polich for thread
       return  $user->id==$thread->user_id;
    }

    /**
     * Determine whether the user can delete the thread.
     *
     * @param  \App\User  $user
     * @param  \App\Thread  $thread
     * @return mixed
     */
    public function delete(User $user, Thread $thread)
    {
        //
    }
}

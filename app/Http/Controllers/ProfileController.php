<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Activity;

class ProfileController extends Controller
{
    protected  $limit=6;
    
    public function show(User $user)
    {
        //return Activity::feed($user);
        return view('profile.show',[
            'activities'=> Activity::feed($user),
            'userProfile'=>$user,
            
        ]);
    }
    
}

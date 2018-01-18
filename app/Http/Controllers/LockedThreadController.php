<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;

class LockedThreadController extends Controller
{
    public function __construct() {
        $this->middleware('is-admin');
    }
    public function store()
    {
//            if(!auth()->user()->isAdmin()){
//                return response('you are not Admin to lock a Thread',403);
//            }
//       
        //or
        // i will use middleware ('is-admin') to check if user is Admin or not
        $thread=Thread::findOrFail(request()->threadId);
        $thread->lock();
    }
    
    public function destroy()
    {
       
       $thread=Thread::findOrFail(request()->threadId);
       $thread->unlock();
    }
}

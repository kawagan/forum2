<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ThreadSubscription;
use App\Thread;

class ThreadSubscriptionController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    public function store($channel, Thread $thread)
    {
        // function accesor(IsSubscribedTo) make as arrtibute in Thread
        // we can make another way in Thread , see Thread
        //$thread->append('IsSubscribedTo');
        //return $thread;
        if (!$thread->isSubscribedTo)
        $thread->subscribe();
        
        return redirect($thread->path());
    }
    
    public function destroy($channel,Thread $thread)
    {
        $thread->unsubscribe();
        
        return redirect($thread->path());
    }
}

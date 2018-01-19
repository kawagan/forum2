<?php

namespace App\Reputation;

class Reputation {
   
    const THREAD_WAS_PUBLIDHED=10;
    const REPLY_POSTED=2;
    const REPLY_FAVORITED=5;
    
    public static function award($user,$points)
    {
        $user->increment('reputation',$points);
    }
    
    public static function reduce($user, $points){
        $user->decrement('reputation', $points);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\ThreadWasUpdated;

class ThreadSubscription extends Model
{
   protected $guarded=[];
    
    public function thread()
    {
        return $this->belongsTo('App\Thread');
    }
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    //  i use it in in Thread model
    public function notify($reply)
    {
       return $this->user->notify(new ThreadWasUpdated($reply->thread,$reply));
    }
    
    
}

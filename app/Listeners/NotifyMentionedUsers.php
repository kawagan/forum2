<?php

namespace App\Listeners;

use App\Events\ThreadRecievedNewReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\YouAreMentoined;
use App\User;

class NotifyMentionedUsers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserHasTagged  $event
     * @return void
     */
    public function handle(ThreadRecievedNewReply $event)
    {
//        preg_match_all('/\@([^\s\.]+)/', $event->reply->body, $matches);
//        
//        if($matches[1]){ 
//            foreach($matches[1] as $match){
//                $user= User::where('name',$match)->first();
//                $user->notify(new YouAreMentoined($event->reply));
//            }    
//        }
//        
        // or
        //we use filter() to delete all null values
//        if ($event->reply->mentionedUsers()){
//            collect([$event->reply->mentionedUsers()])
//                ->collapse()
//                ->map(function($name){
//                    return User::where('name',$name)->first();
//                })
//                ->filter()
//                ->each(function($user) use ($event){
//                    $user->notify(new YouAreMentoined($event->reply));
//                }) ;       
//        }
        
        // or 
        if (!empty($event->reply->mentionedUsers() ) ){
            User::whereIn('name',$event->reply->mentionedUsers())->get()
                    ->each(function($user) use ($event){
                        $user->notify(new YouAreMentoined($event->reply));
                    }) ;   
            
        }
        
    }
}

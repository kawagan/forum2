<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;
use App\Traits\RecordsActivity;
use App\ThreadSubscription;
use App\Events\ThreadHasReply;
use Illuminate\Support\Facades\Event;
use App\Events\ThreadRecievedNewReply;
use App\Trending\Visits;
use Laravel\Scout\Searchable;
use Stevebauman\Purify\Facades\Purify; // for clean text

class Thread extends Model
{
    //Searchable in Laravel Scout 
    // i use alogria as third party in Scout to make search faster 
    use RecordsActivity,Searchable;
   
    /// function accesor(IsSubscribedTo) make as arrtibute in Thread
    protected $appends=['isSubscribedTo'];
    
    protected $guarded=[];
    protected $casts=[
        'locked'=>'boolean'
    ];


    //$with: eager loding these relationships in every single query,we can use scope global for that,
    // with globa scope we can remove eager loading
    // we need channel and owner in many place for that load auto in every single query
    protected $with=['owner','channel'];
    
    public static function boot()
    {
        parent::boot();
        // apply this query in all query in Thread
        // we want show replies count in  every thread in all pages
        //withCount('replies') == replies_count
        // we comment this becs we make it as column in threads table call replies_count
        
       /* static::addGlobalScope('replyCount',function( Builder $builder){
            $builder->withCount('replies');

        });*/
 //*******************************************************************************       
        // eager loading by global scope
         /*static::addGlobalScope('owner',function( Builder $builder){
            $builder->with('owner');

        });*/
        
        // event to delete replies associated, when we delete a thread and
        // delete activities for reply and thread
        // when this run ,deleteing in RecordsActivity aslo run
        static::deleting(function($thread){
          //  $thread->replies()->delete();// 
          // to delete for every reply in Activity table with delete thread
          /*$thread->replies->each(function($reply){
              $reply->delete();
          }) ; */ // or
          $thread->replies->each->delete();  
        });
        
        
        // this event is trigger when create a thread
        // we delete this and make it as boot in trait(RecordsActivity) ,so it is triger auto
//        static::created(function($thread){
//            $thread->recordActivity();
//        });
        
        //---------------------------------------------------------------
        // when create a thread, it produce slug assoicate
        // when run this , then function setSlugAttribute($value) trigger(run) auto
        // when  update or create slug, then setSlugAttribute($value) also trigger
        // this work perfect for ModelFactory ,when run db:seed then this also run, and make desfualt slug in threads table
        static::created(function($thread){
            $thread->update(['slug'=>$thread->title]);
        });
    }
    
    public function owner()
    {
        // owner==user
        return $this->belongsTo('App\User','user_id');
    }

    public function replies()
    {
        return $this->hasMany('App\Reply');
 
         // we move eager loading to reply model
         //// we use withCount('favorites'),with('owner') , to reduce select query(eager loading) 
        /*return $this->hasMany('App\Reply')
                ->withCount('favorites')
                ->with('owner');*/
    }
    
    // activities save all actions in Activity model 
    public function activities()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    public function channel()
    {
        return $this->belongsTo('App\Channel');
    }
    
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->slug}";
    }
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function addReply($reply)
    {
      $reply= $this->replies()->create($reply);
      
    // when tag name then should be  get notification
    // for exmp: when typing in reply comment: "@kawa"
    event( new ThreadRecievedNewReply($reply));  
      
      //*****prepare notifiactions for all subscribers*****
//      foreach($this->subscriptions as $subscribe){
//          if($subscribe->user_id==$reply->user_id){
//            $subscribe->user->notify(new ThreadWasUpdated($this,$reply));
//          }
//      }
      // or
//      $this->subscriptions->filter(function($subscribe) use ($reply){
//         return $subscribe->user_id==$reply->user_id ; 
//      })->each(function($subscribe) use ($reply){
//          $subscribe->user->notify(new ThreadWasUpdated($this,$reply));
//      });
        //or
      //*** filter is a collection method
//       $this->subscriptions->filter(function($subscribe) use ($reply){
//         return $subscribe->user_id!=$reply->user_id ; 
//      })->each->notify($reply); //*** notify function here i create it
      
     // or
//      $this->subscriptions
//              ->where('user_id','!=',$reply->id)
//              ->each
//              ->notify($reply);
      
      //or 
      //$this->notifySubscribers($reply)
      //or
      // or by Event listner
      // add listner NotifySubscribers()  to event ThreadRecievedNewReply() 
    
      return $reply;
    }
    
    public function lock()
    {
        $this->update(['locked'=>true]);
    }
    
    public function unlock()
    {
        $this->update(['locked'=>false]);
    }
    
    public function notifySubscribers($reply)
    {
        $this->subscriptions
        ->where('user_id','!=',$reply->id)
        ->each
        ->notify($reply);
    }


    public function scopeFilter($query,$filter)
    {
        //apply is normal function , not from laravel
       return $filter->apply($query);

    }

    /*public function replyCount()
    {
        return $this->replies->count();
    }*/
    
    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }
    
    //create ein subscribe in Thread_subscriptions table,
    // run by ThreadSubscriptionController@store()
    public function subscribe($userId=null)
    {
        $this->subscriptions()->create([
            'user_id'=>$userId?:auth()->user()->id,
        ]);
    }
    
    public function unsubscribe($userId=null)
    {
        //here call relationship
        $this->subscriptions()
                ->where('user_id', $userId?:auth()->user()->id)
                ->delete();
    }
    public function getIsSubscribedToAttribute()
    {
        // we use exists to return boolean value
        return $this->subscriptions()
                ->where('user_id',auth()->user()->id)
                ->exists();
    }
    
    // threasd has readed
    public function ThreadHasReaded($user)
    {
        $key=$user->visitedThreadCachKey($this);
        
        return $this->updated_at > cache($key);
    }
    
    // to increment count of visits in threads/index.php and save it in redis cache
    public function visits()
    {
        return new Visits($this);
    }
    
    // when we write Thread $thread in function params, then it will find by slug not id
    public function getRouteKeyName() {
        return 'slug';
    }
    
    // see up in boot
    // this trigger when create a thread
    public function setSlugAttribute($value)
    {
        
        if(static::whereSlug($slug= str_slug($value))->exists()){
          $slug="{$slug}-{$this->id}";  
        }
        $this->attributes['slug']=$slug;
        
        // this comment lines is usefull select sql and usefull info
        //$max = static::whereTitle($this->title)->latest('id')->value('slug');
        //$max = static::whereTitle($this->title)->latest('id')->pluck('slug');
        //$max = static::whereTitle($this->title)->max('slug');
        //$max = static::whereTitle($this->title)->max('id');
        // $k='kawa'[-1] result: $k=a,    $k='kawa'[2] result:$k=w
        
    }
    
    
    // sanitize
    //https://packagist.org/packages/stevebauman/purify
    //Purify is an HTML input sanitizer for Laravel
    //for exmp write:<script>alert('hallo')</script><b>kawa</b><a href='#' onclick='alert(\"hi\")'></a>
    // this it will inseert in database and Purify::clean() it will sanitize
    // we can add and remove tag attributes in config/purify.php in 'HTML.Allowed'
    public function getBodyAttribute($value)
    {
        return Purify::clean($value);
    }
    
}

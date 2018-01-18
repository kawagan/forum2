<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Favoritable;
use App\Traits\RecordsActivity;
use Carbon\Carbon;
use Stevebauman\Purify\Facades\Purify;

class Reply extends Model
{
    use Favoritable,RecordsActivity;
    
    protected $fillable=['user_id','user_thread','body'];
    
    // we use with('favorites'),with('owner') , to reduce select query(eager loading)
    // we want eager loading these relationship for every single query
    protected $with=['owner','favorites'];
    
    public static function boot() {
        parent::boot();
        
        // this both function tirger after create and delete,
        // this triger also , when run db:seed, i mean when run db seeding
        static::created(function($reply){
           $reply->thread->increment('replies_count');  
        });
        static::deleted(function($reply){
           $reply->thread->decrement('replies_count');  
        });
        //or
        /*static::deleting(function($reply){
            $reply->thread->replies_count-=1;
        });*/
    }
    public function owner()
    {
        return $this->belongsTo('App\User','user_id');  //owner==user
    }

    public function thread()
    {
        // all relations return object
        return $this->belongsTo('App\Thread');
    }
    
    public function activities()
    {
        return $this->morphMany('App\Activity', 'subject');
    }
    
    public function path()
    {
        // i used in views/activities/created_favorite.blade.php, $this is Model
        return  $this->thread->path()."#reply-{$this->id}";
    }
    
    // i used with latestReply() in User, to check interval between tow create 
    // replies is 1 mintute
    // create  latest reply and now reply must be 1 minute at least 
    public function wasJustPublished()
    {
        return Carbon::now()->subMinute()->gt($this->created_at);
    }
    
    // i use it in Listners/NotifyMentionedUsers
    public function mentionedUsers()
    {
       preg_match_all('/@([\w\-]+)/', $this->body, $matches);
       
       return $matches[1];
    }
   
    public function setBodyAttribute($body)
    {
       $this->attributes['body']= preg_replace(
               '/@([\w\-]+)/', 
               '<a href="/profiles/$1">$0</a>', 
               $body); 
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

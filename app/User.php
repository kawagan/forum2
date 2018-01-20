<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','avatar_path'
    ];
    
    protected $casts=[
        'confirmed'=>'boolean'
    ];
    
    /**
     * The accessors to append to the model's array form.
     * @var array
     */
    protected $appends = [
      'isAdmin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function threads()
    {
        return $this->hasMany('App\Thread')->latest();
    }

    public function replies()
    {
        return $this->hasMany('App\Reply');
    }
    
    public function activities()
    {
        return $this->hasMany('App\Activity');
    }
    
    //override this function
    public function getRouteKeyName() {
        return 'name'; // username
    }
    
    // i use this relationship to get latest reply for a user
    public function latestReply()
    {
        return $this->hasOne('App\Reply')->latest()->first();
    }
    
    // thread has readed... Thread call this function by ThreadHasReaded
    public function readThread($thread){
        cache()->forever($this->visitedThreadCachKey($thread), Carbon::now());
    } 

    public function visitedThreadCachKey($thread)
    {
        return $key= sprintf('users.%s.vistis.%s', auth()->user()->id,$thread->id);
        //users.12.visits.5 // here we create variable
    }
    
    public function getAvatarImageAttribute($value)
    {  
        if($this->avatar_path){
            return asset('/storage/'.$this->avatar_path);
        }else{
            return asset('/img/avatar.png');
        }
        
    }
    
    // i used it in RegisterConfiramtionController/index
    public function confirm() 
    {
         $this->confirmed=true;
         $this->confirmation_token=null;
         $this->save();
    }
    
    // only Admin can make lock a thread
    public function isAdmin()
    {
        return in_array($this->email, config('council.administrators'));
    }
    
    /**
     * Determine if the user is an administrator.
     * @return bool
     */
    public function getIsAdminAttribute()
    {
        return $this->isAdmin();
    }

}

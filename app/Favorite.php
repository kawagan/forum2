<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordsActivity;
use App\Reputation\Reputation;

class Favorite extends Model
{
    use RecordsActivity;
    
    protected  $guarded=[];
    
    protected static function boot() {
        static::created(function($favorite){
            Reputation::award($favorite->user, Reputation::REPLY_FAVORITED);
        });
    }
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }


    // we can make favorite for reply and thread and user for that
    // we make polymorphich relation
    public function favorited()
    {
        // return object
        return $this->morphTo();
    }
    
    public function activities()
    {
        return $this->morphMany('App\Activity', 'subject');
    }
}


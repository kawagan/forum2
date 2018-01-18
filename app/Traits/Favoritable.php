<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

// in this trait we group all reply favorites funtion in one place(trait), so we clean up
trait Favoritable
{
    protected static function bootFavoritable()
    {
        // when this run, deleting in recordsActivtiy also run in Favorite
        static::deleting(function($reply){
            $reply->favorites->each->delete();
        });
    }
    public function favorites()
    {
        // get all replie's favorites
        return $this->morphMany('App\Favorite', 'favorited');
    }
    
    
    // only one favorite musst add , we protect it in tow level here and in database 
    // tables, see create tables
    public function isFavorited()
    {
        if(Auth::check()){
            // favoites is relationship after eager loding
            return $this->favorites->where('user_id', auth()->user()->id)->count();
            // without eager loading
            //return $this->favorites->where('user_id', auth()->user()->id)->count();
        }
        return false;
    }
    
    // add favorites to Reply table
    public function favorite()
    {
        $attributes = ['user_id' => auth()->user()->id];
        
        if (!$this->isFavorited()) {
            $this->favorites()->create($attributes);
        }
        /*
        Favorite::create([
            'user_id' => auth()->user()->id,
            'favorited_id' => $reply->id,
            'favorited_type' => get_class($reply)
        ]);
         */
        // or ,favorited_id,favorited_type added automaticly 
       
    }
    
    public function getFavoritesCountAttribute()
    {
        //favorites is eager loading relationship
        return $this->favorites->count();
    }
}
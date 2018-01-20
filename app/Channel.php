<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $guarded=[];
    
    public function threads()
    {
        return $this->hasMany('App\Thread');
    }

    public function getRouteKeyName()
    {
        //becuse when we write: public function index(Channel $channel)
        // $channel is refer to Id , so we put this function for point $channel to slug
        return 'slug';
    }
}

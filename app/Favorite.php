<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordsActivity;

class Favorite extends Model
{
    use RecordsActivity;
    
    protected  $guarded=[];
    
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


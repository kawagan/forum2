<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded=[]; // mass assignment
    
    // subject is reply or thread and,favorite,.....
    public function subject()
    {
        return $this->morphTo();
    }
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public static function feed($user,$take=50)
    {
         // we use with() for eager loading(showing relation subject in results)
        // groupBy according date.
 
        //return $user->activities()->latest()->....
        return  static::where('user_id',$user->id)  // or $user->activities()
                ->latest()
                ->with('subject')
                ->get()
                ->take($take)
                ->groupBy(function($value){
                    return $value->created_at->format('Y-m-d') ;
                });
    }
}

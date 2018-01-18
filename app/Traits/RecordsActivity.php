<?php

namespace App\Traits;

use App\Activity;

// register activties for any action.
// these functios was for Thread.
// i make trait becs , i need it for Thread, Reply,...
// event lisen triger for create , but i want it also for update and delete,and
// maybe i want aciticvit delete for thread but not for reply(getRecordEvents()), for all thess reasons, 
// i want make it refactor dynamic
trait RecordsActivity {
    
    // this function triger auto for every Class extend it
    protected static function bootRecordsActivity()
    {
        if(auth()->guest()) return;
        foreach(static::getRecordEvents() as $event){       
            static::$event(function($model) use($event){ 
                $model->recordActivity($event);
            });
        // static::, is model, Thread, Reply,..,and $model alwyes object from static::
        }
        
        // this work with thread but not for reply ,so we change in deleting
        // in Thread Model to delete reply activity also
        static::deleting(function($model){
          $model->activities()->delete();
        });
    }
    
    
    protected static function getRecordEvents()
    {
        // if you want it for delete or update then you can overwrite it
        // or make property for that,
        return ['created'];
    }
    
    protected function recordActivity($event)
    { 
        //Activity::create([ //or
        $this->activities()->create([
            'user_id'=>auth()->user()->id,
            'type'=>$this->getActivityType($event) ,
            // laravel make it auto
            // 'subject_id'=>$this->id,
            // 'subject_type'=> get_class($this)
        ]);
    }
    
    protected function getActivityType($event)
    {
        // App/Thread , only class without namespace(php platform) 
        $type=strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}".'_'."{$type}";  
    }
}

<?php
// all visits function i  made as trait

namespace App\Trending;
use Illuminate\Support\Facades\Redis;
class Trending {
    
    // i can use also function setCacheKey(){ return 'trending_threads' }, then call this
    //function in all function, but i use ie as property
    protected $cacheKey;
    
    protected function setCacheKey()
    {
        $this->cacheKey='trending_threads';
    }
    
    public function get()
    {
        // redis, was in index() in ThreadController , we move it here
        // we made 'trending_threads' in show()
        // map can work like foreach
        // important see map() and filter() , becs much use ie in laravel
        // number 4, take only 5 threads , -1 take all threads
        $trending=Redis::zrevrange($this->cacheKey,0,4);
//        
//        foreach($trending as $thread){
//             $res[]= json_decode($thread, true);
//        }
        // or
//        $trending=collect($trending)->map(function($thread){
//            return json_decode($thread);
//        });
        // or
        $trending= array_map('json_decode', $trending);
        
        return $trending;
    }
    
    public function push($thread)
    {
        // to see in cms run: redis-cli
        // save in redis cash
        Redis::zincrby($this->cacheKey,1, json_encode([
            'title'=>$thread->title,
            'path'=>$thread->path(),
        ]));
    }
    
    public function reset()
    {
        Redis::del($this->cacheKey);
    }
    
}

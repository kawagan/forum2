<?php
namespace App\Trending;
use Illuminate\Support\Facades\Redis;
class Visits{
    
    protected $thread;
    
    public function __construct($thread) {
        $this->thread=$thread;
    }

    protected function cacheKey()
    {
        return 'threads-'.$this->thread->id.'-visits';
    }
    
    public function record()
    {
        Redis::incr($this->cacheKey());
        return $this;
    }
    
    public function countx()
    {
        return Redis::get($this->cacheKey())?? 0;
    }
    
    public function reset()
    {
        Redis::del($this->cacheKey());
        return $this;
    }
}


// this was one option
// i use as trait in Thread Model
// and from ThreadController i use for exmp: $thread->recoredVisits()
namespace App\Trending;
use Illuminate\Support\Facades\Redis;

trait RecordsVisits{
    
    protected function visitsCacheKey()
    {
        return 'threads-'.$this->id.'-visits';
    }
    
    public function recordVisits()
    {
        Redis::incr($this->visitsCacheKey());
        return $this;
    }
    public function visits()
    {
        return Redis::get($this->visitsCacheKey())?? 0;
    }
    
    public function resetVisits()
    {
        Redis::del($this->visitsCacheKey());
        return $this;
    }
    
}

<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\User;

class ThreadFilters
{
    protected $request;
    protected $builder;

    /**
     * ThreadFilters constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request=$request;

    }

    // // we apply our filter to builder query (builder = query scope)
    public function apply($builder)
    {
        $this->builder=$builder;

        if($this->request->has('by')){
            $this->by($this->request->by);
        }

        if($this->request->has('popular')){
           $this->popular();
        }
        
        if($this->request->has('unanswered')){
           $this->unanswered();
        }
        return $builder;

    }

    /**
     * @param $builder
     * @param $username
     * @return mixed
     */
    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    protected function popular()
    {
        // query/builder
        // we have by default ->latest(); in our select , so orderBy('replies_count','desc') not work
        // we want delete latest(); , we will return  instance from query builder: builder->sqlQuery
        // anb by this instance we call with order property
        // orders is public property
        $this->builder->getQuery()->orders=[];
        return $this->builder->orderBy('replies_count','desc');

    }
    
    protected function unanswered()
    {
        return $this->builder->where('replies_count',0);
    }

}
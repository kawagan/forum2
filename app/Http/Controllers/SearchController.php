<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;
use App\Trending\Trending;

class SearchController extends Controller
{
    public function show(Trending $trending)
    {
       // dd(request('q'));
       //i use laravel Scout with algolia for search. episode 94,... 
       $threads= Thread::search(request('search'))->paginate(25);
       
       return view('threads.index',[
            'threads'=>$threads,
            'trending'=>$trending->get()
        ]);
    }
}

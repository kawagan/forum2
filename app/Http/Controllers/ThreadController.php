<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;
use App\Channel;
use App\User;
use App\Filters\ThreadFilters;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Trending\Trending; // for redis cash

class ThreadController extends Controller
{
    protected $limit=10;

    public function __construct()
    {
        $this->middleware('auth')->except(['index','show']);
        $this->middleware('must-be-confirmed')->only('store');// for confirm email
    }

    public function index(Channel $channel ,ThreadFilters $filter, Trending $trending)
    {
        //$threads = $this->getMethod($channel);

        if ($channel->exists) { // exists check if model is exists
            $threads = $channel->threads()->latest();
        } else {
            $threads = Thread::latest();
        }

       // dd($threads->toSql()); // give me select query, shuold be before ->get()
        $threads=$threads->filter($filter)->paginate(10);
        
        if(request()->wantsJson()){
            return $threads;
        }
        
        return view('threads.index',[
            'threads'=>$threads,
            'trending'=>$trending->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $thread=new Thread();
        return view('threads.create',compact('thread'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  
        // we use try catch in ReplyController only to show different code
        $rules=[
            'title'=>'required|spamfree',
            'body'=>'required|spamfree',
            'channel_id'=>'required|exists:channels,id' // the ID muss exists in channels table, for exmp: 999 not exists
        ];
        $this->validate($request,$rules);
        
        // we delete this line and put spamfree in $rules
        //$spam->detect($request->body); // hard code we can use resolve(), dpd injection
        
        $thread=Thread::create([
            'user_id'=>auth()->user()->id,
            'channel_id'=>$request->channel_id,
            'title'=>$request->title, // or request('title'), we use request() function , it is global function
            'body'=>$request->body,
          //  'slug'=> $request->title, // instead of , we make in boot function statis::create(function)....
        ]);

        return redirect($thread->path());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channel,Thread $thread, Trending $trending)
    {
        // some technic, we use to reduce select query 
        //return $thread->load('replies'); // lazy eager loading
        //return  $thread->load('replies.favorites'); //nested relation
        //return  $thread->load('replies.favorites')->load('replies.owner');
        
        //eager loading using relationship in model
        //return $thread->replies; // $this->hasMany('App\Reply')->withCount('favorites')
        //-------------------------------------------------------------------
        
        // this very good for withCount , becs we reduce selects
        //return $thread->withCount('replies')->find($thread->id); // ->get() at end

        // replies count for every thread , we make it scope global variable
        //return $thread->withCount('replies');
        
        //return $thread->replies;
        
        if(auth()->check()){
            auth()->user()->readThread($thread); // for thread has readed
        }
        
        // push $thread in redis cache
        $trending->push($thread);
        
        $thread->visits()->record();
        
        return view('threads.show',[
            'thread'=>$thread,
            'replies'=>$thread->replies()->paginate($this->limit)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update($channel, Thread $thread)
    {
        // to clock a thread
//        if(request()->has('locked')){
//            if(!auth()->user()->isAdmin()){
//                return response('you are not Admin to lock a Thread',403);
//            }
//        }
    //or  
    // we put in separate controller (LockedThreadController) , then it will be more clear and easy.
 
//*********************************************************************************   
        $this->authorize('update',$thread);
        
        $thread->update(request()->validate([
            'title'=>'required|spamfree',
            'body'=>'required|spamfree',
        ]));
           
        return $thread;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel,Thread $thread)
    {
        // policy thread, in update method in policy 
        // only the owner of thread can delete his thread
        $this->authorize('update', $thread);
        
       // we make event in Thread model to delete replies associated auto
       // $thread->replies()->delete();
       $thread->delete();
       
        // if request come from api restfull  then return json
       if(request()->wantsJson()){
           return response([], 304);
       }
       return redirect('/threads');
    }

    /**
     * @param Channel $channel
     * @return $this|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection|static|static[]
     */
    protected function getMethod(Channel $channel)
    {
        if ($channel->exists) { // exists check if model is exists
            $threads = $channel->threads()->latest();
        } else {
            $threads = Thread::latest();
        }

        if ($username = request('by')) {
            $user = User::where('name', $username)->firstOrFail();

            $threads = $threads->where('user_id', $user->id);
        }

        $threads = $threads->get();
        return $threads;
    }
   
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reply;
use App\Thread;
use App\Spames\Spam;
//use Illuminate\Support\Facades\Gate;
use App\Http\Requests\CreatePostRequest;
use App\User;

class ReplyController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
    }

    public function store($channel, Thread $thread, CreatePostRequest $request)
    {
        if($thread->locked){
            return response('Thread is locked.',422);
        }
        //---------------------------------------------------------------------       
          // this very important aslo
//        try {
//            //  $this->authorize('create',new Reply); // or use Gate:
//            // read doc
//            if(Gate::denies('create',new Reply)){
//                return response('You are positng too frequently. Please take break. :)',422);
//            }
//            
//            $this->validateReply();
//            $thread->addReply([
//                'user_id'=>auth()->user()->id,
//                'body'=>request('body'),
//            ]);
//        
//        } catch (\Exception $ex) {
//           return response('Sorry,Your reply cant be saved in this time.',400); 
//        }
        
        
        // after refactor
        // we make CreatePostRequest and refactor down:
        $thread->addReply([
            'user_id'=>auth()->user()->id,
            'body'=>request('body'),
        ]);

        return redirect($thread->path());
    }
    
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply); // @can, @endcan , linked to authorize
        // when this run :deleting in Reply(Favoritable trait) run also
        $reply->delete();
        
        if(request()->expectsJson()){
            return response(['status'=>'Reply deleted']);
        }
        
        return redirect($reply->thread->path());
    }
    
    protected function validateReply()
    {
//        $rules=[
//            'body'=>'required'
//        ];
//        $this->validate(request(),$rules);
        
        // we can use constructer injection in fucntion __countructer() for depedency injection
        // use app() or resolve() to make depedency injection
        // resolve(Spam::class)->detect(request()->body);
        //** for spam we make class Spam **
        
        // or 
        // we want use spam class for validation in reply(body) and in thread(title, body)
        // we make refactor to make easy:
        // 1- make class for custom validation
        // 2- make custome validation and register it in serverprovider
        // 3- cutome resource/lang/en/validation add custome message validation
        // use custome validation in $rules variables
        $rules=[
            'body'=>'required|spamfree'
        ];
        $this->validate(request(),$rules);
       
        
    }

}

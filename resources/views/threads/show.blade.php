@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-1 text-left">
                                <img src="{{$thread->owner->avatar_image}}" width="50" height="50" >
                            </div>
                            <div class="col-md-8 text-left"> 
                                <a href="{{route('profile',$thread->owner)}}">{{$thread->owner->name}}</a> posted: {{$thread->title}}
                            </div>
                            
                            <!--policy for delete, only user which has made thread can delete it-->
                            <!--another user can not see link delete-->
                            <!--pls see policy-->
                            @can('update',$thread)
                            <div class="col-md-3 text-right">
                                <!--$thread->channel , channel is relation eager loading -->
                                <form action='{{route('delete.thread',[$thread->channel->slug,$thread->slug])}}' method="POST">
                                    {{csrf_field()}}
                                    {{method_field('delete')}}
                                    <button type="submit" class="btn btn-link">Delete Thread</button>
                                </form>
                            </div>
                            @endcan
                        </div>  
                    </div>
                    <div class="panel-body">
                        <article>
                                <div class="body">{{$thread->body}}</div>
                        </article>
                    </div>
                </div>
                      <?php // $replies=$thread->replies()->paginate(6) ?>
                @foreach($replies as $reply)
                    <div class="panel panel-default">

                        <div class="panel-heading" id="reply-{{$reply->id}}">
                            <div class="row">
                                <div class="col-md-6 text-left">
                                    <!--$reply->owner->name eager loading-->
                                    <a href="{{route('profile',$reply->owner->name)}}">
                                        {{$reply->owner->name}}
                                    </a> said {{$reply->created_at->diffForHumans()}}
                                </div>
                                <div class="col-md-6 text-right">
                                    <form action="/replies/{{$reply->id}}/favoraite" method="POST">
                                        {{csrf_field()}}
                                        <!-- eager loading : favorites_count-->
                                        <?php $countfav=$reply->favorites_count ?>
                                        <button type="submit" class='btn btn-default' {{($reply->isFavorited() || auth()->guest())?'disabled':''}}>
                                            {{$countfav}} {{str_plural('Favorite',$countfav)}}
                                        </button>
                                    </form> 
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <article>
                                <div class="body"><?php echo $reply->body ?></div>
                            </article>
                        </div>
                        
                        @can('update',$reply)
                        <div class="panel-footer">
                            <form action="/replies/{{$reply->id}}" method="POST">
                                {{csrf_field()}}
                                {{method_field('delete')}}
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                        @endcan
                        
                    </div>
                @endforeach
                {{ $replies->links() }}

                @if(auth()->check())
                    @if(!$thread->locked)  
                        <form method="POSt" action="{{$thread->path().'/replies'}}" class="reply">
                                <?php echo csrf_field() ?>
                            <div class="form-group">
                                    <textarea name="body" rows="5" class="form-control" placeholder="Write a reply"></textarea>
                            </div>
                            <button type="submit" class="btn btn-default">Reply</button>

                        </form>
                    @else
                    <p class="text-info h3 msg-lock">the thread is locked</p>
                    @endif
                @else

                    <span class="text-center help-block">
                        Please <a href="/login">sign in </a> to participate in this discussion.
                @endif
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        This thread was published {{$thread->created_at->diffForHumans()}}
                        by<a href="#"> {{$thread->owner->name}}</a>

                        <?php
                        // replies_count it is global scope variable in Thread model
                        $count=$thread->replies_count
                        ?>
                        and currently has {{$count}} {{ str_plural('comment',$count) }}
                    </div>
                    
                    @if(Auth::check())
                    <div class="panel-footer">
                        <form action="{{$thread->path()}}/subscribtions" method="POST" style="display: inline">
                            {{csrf_field()}}
                            <button type="submit" class="btn btn-default">Subscribe</button>    
                        </form>
                        <form action="{{$thread->path()}}/subscribtions" method="POST" style="display: inline">
                            {{csrf_field()}}
                            {{method_field('delete')}}
                            <button type="submit" class="btn btn-default">Unsubscribe</button>    
                        </form>
                        <hr>
                        <div class="lock-buttons" thread-id="{{$thread->id}}">
                            <button  id="lock"  class="btn btn-default  {{ $thread->locked?'disabled':'' }}" >Lock</button>
                            <button  id='unlock' class="btn btn-default {{ !$thread->locked?'disabled':'' }}" >Unlock</button>
                        </div>    
                    </div>
                    @endif
                </div>
            </div>

        </div>

    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        $('#lock').click(function(){
             var threadId=$('.lock-buttons').attr('thread-id');
             var btn=$(this);
            
            $.ajax({
               type:"POST",
               url:"{{route('locked-thread.store')}}",
               data:{
                   threadId:threadId,
                   _token:"{{csrf_token()}}",
               },
               cache:false,
               success:function(response){
                   $('.reply').addClass('hide');
                   $('.lock-buttons').children().removeClass('disabled');
                   btn.addClass('disabled');
                   $('p.msg-lock').show();
               }
            });
           
        })
    });
    
     $(document).ready(function(){
        $('#unlock').click(function(){
            var threadId=$('.lock-buttons').attr('thread-id');
            var btn=$(this);
            $.ajax({
               type:"POST",
               url:"{{route('locked-thread.delete')}}",
               data:{
                   threadId:threadId,
                   _token:"{{csrf_token()}}",
                   _method:"DELETE"
               },
               cache:false,
               success:function(response){
                    $('.reply').removeClass('hide');
                    $('.lock-buttons').children().removeClass('disabled');
                    btn.addClass('disabled');
                     $('p.msg-lock').hide();
               }
            });
           
        })
    });
    
    
    
</script>
@endsection

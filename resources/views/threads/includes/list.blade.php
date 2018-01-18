@forelse($threads as $thread)
                <div class="panel panel-default">
                     
                    <div class="panel-heading">
                        <div  class="row">
                            <div class="col-md-10 text-left" >
                                 <a href="{{$thread->path()}}" style="" >
                                     @if(auth()->user() && $thread->threadHasReaded(auth()->user()))
                                     <h4 class="no-margin"><strong>{{$thread->title}}</strong></h4>
                                     @else
                                     <h4 class="no-margin">{{$thread->title}}</h4>
                                     @endif 
                                 </a>
                            </div>

                            <div class="col-md-2 text-right" >
                                <!--replies_count is global scope variable -->
                                <a href="#"><strong><span class="small">{{$thread->replies_count}} replies</span></strong></a>
                            </div>
                        </div>
                       
                        <div class="top-margin">Posted By: <a href="{{route('profile',$thread->owner)}}">{{$thread->owner->name}}</a></div>
                        
                    </div>
                    <div class="panel-body">
                        <article>
                                <div class="body">{{$thread->body}}</div>
                        </article>
                    </div>
                    <div class="panel-footer">
                        {{$thread->visits()->countx()}} Vistis
                    </div>
                     
                </div>
                @empty
                <p>There is no relevant results at this time</p>  
@endforelse

{{$threads->render()}}
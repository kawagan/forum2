@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                @include('threads.includes.list')
            </div>
            @if(count($trending))
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">Search</div>
                        <div class="panel-body">
                            <form action="/threads/search" method="get">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search for something...">
                                </div>
                                <button class="btn btn-default">Search</button>
                            </form>
                        </div>     
                    </div>    
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Ternding Threads
                    </div>
                    <div class="panel-body">
                        <ul class="list-group">
                                @foreach($trending as $thread)
                                    <li class="list-group-item">
                                        <a href="{{$thread->path}}">{{$thread->title}}</a>
                                    </li>

                                @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

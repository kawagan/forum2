@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div>
                <h3 class="page-header m">{{$userProfile->name}}</h3>
                
                <!-- i use it without $this->authorize() -->
                @can('update',$userProfile)
                {!! Form::open([
                    "route"=>['avatar',$userProfile->id],
                    'files'=>true
                ]) !!}
                <div class="form-group {{$errors->has('avatar')?'has-error':''}}">
                {!! Form::file('avatar',['class'=>'form-control','style'=>'width:300px']) !!}
                @if($errors->has('avatar'))
                <span class="help-block">{{$errors->first('avatar')}}</span>
                @endif
                
                {!! Form::submit('Save',['class'=>'btn btn-primary', 'style'=>'margin-top:8px']) !!}
                </div>
                {!! Form::close() !!}
                @endcan
            </div>
            <img src="{{$userProfile->avatar_image}}" width="100" height="100">
            <hr />
            @forelse($activities as $date=>$activtiy)
            <h3 class="page-header">{{$date}}</h3>
                @foreach($activtiy as $record)
                    @if(View::exists("profile.activities.$record->type"))
                        @include("profile.activities.$record->type",['activity'=>$record])
                    @endif    
                @endforeach
            @empty
            No activities
            @endforelse
        </div>
    </div>
</div>
@endsection

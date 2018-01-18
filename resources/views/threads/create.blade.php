@extends('layouts.app')


@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <!-- this middleware:must-be-confirmed, see it -->
            @include('includes.info-box')      
            <div class="panel panel-default">
                <div class="panel-heading">Create a Thread</div>

                <div class="panel-body">
                  {!! Form::open([
                    'url'=>'/threads',
                    ])
                  !!}

                    <div class="form-group {{$errors->has('channel_id')?'has-error':''}}" >
                        {!! Form::label('channel_id') !!}
                        {!! Form::select('channel_id',$channels->pluck('slug','id'),null,['class'=>'form-control','placeholder'=>'Choose a Channel']) !!}
                        @if($errors->has('channel_id'))
                            <span class="help-block">{{$errors->first('channel_id')}}</span>
                        @endif
                    </div>

                    <div class="form-group {{$errors->has('title')?'has-error':''}}">
                        {!! Form::label('title') !!}
                        {!! Form::text('title',null,['class'=>'form-control'])!!}

                        @if($errors->has('title'))
                            <span class="help-block">{{$errors->first('title')}}</span>
                        @endif
                    </div>
                    <div class="form-group {{$errors->has('body')?'has-error':''}}">
                        {!! Form::label('body') !!}
                        {!! Form::textarea('body',null,['class'=>'form-control','rows'=>5]) !!}
                        @if($errors->has('body'))
                            <span class="help-block">{{$errors->first('body')}}</span>
                        @endif
                    </div>
                    {!! Form::submit('Create',['class'=>'btn btn-success']) !!}
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

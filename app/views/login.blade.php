@extends('main')



@section('content')


{{ Form::open(array('url' => 'logeado','method' => 'POST')) }}

    @if(isset($error))
       <p> <strong> {{ $error }} </strong> </p><br/>
    @endif 

    {{ Form::label('nick', 'Nick'); }}
    {{ Form::text('nick'); }}
    <br/>
    {{ Form::label('password', 'Clave'); }} 
    {{ Form::password('password'); }}
    <br/>
    {{ Form::submit('  Entrar  '); }}
 
{{ Form::close() }}


@stop
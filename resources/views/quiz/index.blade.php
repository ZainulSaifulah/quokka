@extends('adminlte::page')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Your Quiz</h1>
        </div>
    </div>
@stop

@section('content')
    @foreach ($quizzes as $quiz)
        <div class="card">
            <div class="card-header">
                <h3>{{$quiz->name}}</h3>
                <h4>Start : {{$quiz->start_date}}| End : {{$quiz->end_date}} | Duration : {{$quiz->duration}}</h4>
                @if ($quiz->userQuizResults->contains('user_id', Auth::user()->id ))
                    <h4>Status : Finish</h4>
                @elseif (now() > $quiz->end_date)
                    <h4>Status : Closed</h4>
                @else
                    <h4>Status : Available</h4>
                @endif
                <a href="/quizzes/{{$quiz->id}}" class="btn btn-primary stretched-link">Detail</a>
            </div>
        </div>
    @endforeach
@stop

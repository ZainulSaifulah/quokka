@extends('adminlte::page')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">{{$quiz->name}}</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-8">
            <table class="table table-striped">
                <tr>
                    <th>Quiz Status</th>
                    <td>
                        @if ($quiz->userQuizResults->contains('user_id', Auth::user()->id ))
                            Finish
                        @elseif (now() > $quiz->end_date)
                            Closed
                        @else
                            Available
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Start Date</th>
                    <td>{{$quiz->start_date}}</td>
                </tr>
                <tr>
                    <th>End Date</th>
                    <td>{{$quiz->end_date}}</td>
                </tr>
                <tr>
                    <th>Duration</th>
                    <td>{{$quiz->duration}} Minutes</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row d-flex justify-content-center">
        @if (!$quiz->userQuizResults->contains('user_id', Auth::user()->id) && now() < $quiz->end_date)
            <a href="/quizzes/test/{{$quiz->id}}" class="btn btn-primary">Start Test</a>
        @else
            <div class="col-8">
                <h3 class="text-center">Result</h3>
                <table class="table table-striped">
                    <tr>
                        <th>Question Total</th>
                        <td>{{$quiz->question_total}}</td>
                    </tr>
                    <tr>
                        <th>Answer True</th>
                        <td>{{$quiz->userQuizResults->where('user_id', Auth::user()->id)->first()->true_total}}</td>
                    </tr>
                    <tr>
                        <th>Answer False</th>
                        <td>{{$quiz->userQuizResults->where('user_id', Auth::user()->id)->first()->false_total}}</td>
                    </tr>
                    <tr>
                        <th>Grade</th>
                        <td>{{$quiz->userQuizResults->where('user_id', Auth::user()->id)->first()->result}}</td>
                    </tr>
                </table>
            </div>
        @endif
    </div>

@stop

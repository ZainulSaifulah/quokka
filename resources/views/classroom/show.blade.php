@extends('adminlte::page')

@section('content_header')
    <h1 class="m-0 text-dark">{{$class->name}}</h1>
    <small>{{$class->code}}</small>
@stop

@section('content')
    <div class="text-center tab">
    <button class="tablinks" onclick="openCity(event, 'Quiz')" id="defaultOpen">Quiz</button>
    <button class="tablinks" onclick="openCity(event, 'Member')">Member</button>
    </div>


    <div id="Quiz" class="tabcontent">
        @if (Auth::user()->role == 'lecturer')
            <a href="/quizzes/create" class="btn btn-primary">
                Create Quiz
            </a>
        @endif

        @foreach ($quizzess as $quiz)
            <div class="card mt-2">
                <div class="card-header">
                    <h4>{{$quiz->name}}</h4>
                    <h4>Start : {{$quiz->start_date}}| End : {{$quiz->end_date}} | Duration : {{$quiz->duration}}</h4>
                    @if ($quiz->userQuizResults->contains('user_id', Auth::user()->id ))
                        <h4>Status : Finish</h4>
                    @elseif (now() > $quiz->end_date)
                        <h4>Status : Closed</h4>
                    @else
                        <h4>Status : Available</h4>
                    @endif
                    <a href="" class="btn btn-primary stretched-link">Detail</a>
                </div>
            </div>
        @endforeach
    </div>

    <div id="Member" class="tabcontent">
        <h1 class="border-bottom pt-3 pb-3">Teachers</h1>
        <h3>{{$members[0]->user->name}}</h3>
        @if (Auth::user()->role == 'lecturer')
            <h1 class="border-bottom pt-3 pb-3">Students</h1>
        @else
            <h1 class="border-bottom pt-3 pb-3">Classmates</h1>
        @endif

        @for ($i = 1; $i < count($members); $i++)
            <h3 class="border-bottom pt-2 pb-2">{{$members[$i]->user->name}}</h3>
        @endfor
    </div>

    <div class="modal fade" id="join-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Join Class <i class="fas fa-parking-circle-slash"></i></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="classrooms/join" method="POST">
              @csrf
              <div class="modal-body">
                <div class="form-group">
                    <label for="code">Class Code</label>
                    <input type="text" class="form-control" name="code" placeholder="Class Code">
                </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary"">Join</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Create Class <i class="fas fa-parking-circle-slash"></i></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="classrooms" method="POST">
              @csrf
              <div class="modal-body">
                <div class="form-group">
                    <label for="name">Class Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Class Name">
                </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary"">Create</button>
              </div>
            </form>
          </div>
        </div>
      </div>
@stop

@section('css')
    <style>
        /* Style the tab */
        .tab {
            overflow: hidden;
            border-bottom: 1px solid #ccc;
            background-color: #f1f1f1;
        }

        /* Style the buttons that are used to open the tab content */
        .tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
            background-color: #ddd;
        }

        /* Create an active/current tablink class */
        .tab button.active {
            background-color: #ccc;
        }

        /* Style the tab content */
        .tabcontent {
            display: none;
            padding-top: 6px;
            /* border: 1px solid #ccc; */
            /* border-top: none; */
        }
    </style>
@endsection

@section('js')
    <script>
        function openCity(evt, cityName) {
            // Declare all variables
            var i, tabcontent, tablinks;

            // Get all elements with class="tabcontent" and hide them
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Get all elements with class="tablinks" and remove the class "active"
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            // Show the current tab, and add an "active" class to the button that opened the tab
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }
        $(document).ready(function() {
            // Get the element with id="defaultOpen" and click on it
            document.getElementById("defaultOpen").click();
        })
    </script>
@endsection

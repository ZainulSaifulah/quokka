@extends('adminlte::page')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">{{$quiz->name}}</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-4">
            <input type="hidden" id="duration"  value="{{$quiz->duration}}">
            <div class="row">
                <div class="col">
                    <div class="card card-shadow">
                        <div class="card-body">
                            <h3 id="hour-countdown"></h3>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card-body">
                        <h3>:</h3>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-shadow">
                        <div class="card-body">
                            <h3 id="minute-countdown"></h3>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card-body">
                        <h3>:</h3>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-shadow">
                        <div class="card-body">
                            <h3 id="second-countdown"></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-body">
                    <form id="quizForm" action="/quizzes/submit" method="POST">
                        @csrf
                        <input type="hidden" name="quiz_id" value="{{$quiz->id}}">
                        @foreach ($quiz->quizQuestions as $quizQuestion)
                            <div class="tab">
                                <h3>{{$quizQuestion->question}}</h3>
                                @foreach ($quizQuestion->quizOptions as $quizOption)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="answers[]" onclick="checkbox(this)" value="{{$quizQuestion->id}}:{{$quizOption->option}}">
                                        <label class="form-check-label">
                                            {{$quizOption->option}}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach


                        <div style="overflow:auto;">
                          <div style="float:right;">
                            <button type="button" id="prevBtn" class="btn btn-danger" onclick="nextPrev(-1)">Previous</button>
                            <button type="button" id="nextBtn" class="btn btn-primary" onclick="nextPrev(1)">Next</button>
                          </div>
                        </div>

                        <!-- Circles which indicates the steps of the form: -->
                        <div style="text-align:center;margin-top:40px;">
                          @foreach ($quiz->quizQuestions as $quizQuestion)
                            <span class="step"></span>
                          @endforeach
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
    <style>
        #question {
            overflow-y: scroll;
            height: 100%;
        }
    </style>

    <style>
        /* Mark input boxes that gets an error on validation: */
        input.invalid {
            background-color: #ffdddd;
        }

        /* Hide all steps by default: */
        .tab {
            display: none;
        }

        /* Make circles that indicate the steps of the form: */
        .step {
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: #bbbbbb;
            border: none;
            border-radius: 50%;
            display: inline-block;
            opacity: 0.5;
        }

        /* Mark the active step: */
        .step.active {
            opacity: 1;
        }

        /* Mark the steps that are finished and valid: */
        .step.finish {
            background-color: #4CAF50;
        }
    </style>
@endsection

@section('js')
    <script>
        var currentTab = 0; // Current tab is set to be the first tab (0)
        showTab(currentTab); // Display the current tab

        function showTab(n) {
            // This function will display the specified tab of the form ...
            var x = document.getElementsByClassName("tab");
            x[n].style.display = "block";
            // ... and fix the Previous/Next buttons:
            if (n == 0) {
                document.getElementById("prevBtn").style.display = "none";
            } else {
                document.getElementById("prevBtn").style.display = "inline";
            }
            if (n == (x.length - 1)) {
                document.getElementById("nextBtn").innerHTML = "Submit";
            } else {
                document.getElementById("nextBtn").innerHTML = "Next";
            }
            // ... and run a function that displays the correct step indicator:
            fixStepIndicator(n)
        }

        function nextPrev(n) {
            // This function will figure out which tab to display
            var x = document.getElementsByClassName("tab");
            // Exit the function if any field in the current tab is invalid:
            if (n == 1 && !validateForm()) return false;
            // Hide the current tab:
            x[currentTab].style.display = "none";
            // Increase or decrease the current tab by 1:
            currentTab = currentTab + n;
            // if you have reached the end of the form... :
            if (currentTab >= x.length) {
                //...the form gets submitted:
                document.getElementById("quizForm").submit();
                return false;
            }
            // Otherwise, display the correct tab:
            showTab(currentTab);
        }

        function validateForm() {
            // This function deals with validation of the form fields
            var x, y, i, valid = true;
            x = document.getElementsByClassName("tab");
            y = x[currentTab].getElementsByTagName("input");
            // A loop that checks every input field in the current tab:
            for (i = 0; i < y.length; i++) {
                // If a field is empty...
                if (y[i].value == "") {
                // add an "invalid" class to the field:
                y[i].className += " invalid";
                // and set the current valid status to false:
                valid = false;
                }
            }
            // If the valid status is true, mark the step as finished and valid:
            if (valid) {
                document.getElementsByClassName("step")[currentTab].className += " finish";
            }
            return valid; // return the valid status
        }

        function fixStepIndicator(n) {
            // This function removes the "active" class of all steps...
            var i, x = document.getElementsByClassName("step");
            for (i = 0; i < x.length; i++) {
                x[i].className = x[i].className.replace(" active", "");
            }
            //... and adds the "active" class to the current step:
            x[n].className += " active";
        }

        function checkbox(event){
            if($(event).prop("checked")){
                $(event).closest('.tab').find('.form-check-input').not(event).attr('disabled', 'disabled');
            }else{
                $(event).closest('.tab').find('.form-check-input').removeAttr('disabled');
            }
        }
    </script>

    <script>
        $(document).ready(function() {
            var totalMiliSeconds;
            duration = $('#duration').val();

            totalMiliSeconds = (duration * 60 * 1000);

            countdown =  setInterval(function() {
                second = Math.floor(totalMiliSeconds / 1000);
                minute = Math.floor(second / 60);
                second = second % 60;
                hour = Math.floor(minute / 60);
                minute = minute % 60;
                day = Math.floor(hour / 24);
                hour = hour % 24;

                $('#hour-countdown').html(`${hour}`);
                $('#minute-countdown').html(`${minute}`);
                $('#second-countdown').html(`${second}`);
                $('#form').hide();
                $('#countdown').show();
                if(totalMiliSeconds == -1000){
                    clearInterval(countdown)
                    alert('Time is Up')
                    $('#hour-countdown').html('0');
                    $('#minute-countdown').html('0');
                    $('#second-countdown').html('0');
                    $('#quizForm').submit();
                }else{
                    totalMiliSeconds -= 1000;
                }
            }, 1000);
        });
    </script>


@endsection

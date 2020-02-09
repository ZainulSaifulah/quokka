@extends('adminlte::page')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Create Quiz</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form id="regForm" action="/quizzes" method="POST">
                @csrf
                <div class="tab">
                    <div class="form-group">
                        <label for="class">Class Name</label>
                        <select name="class" id="class" class="form-control">
                            <option value="" selected disabled>Select Class</option>
                            @foreach ($userClasses as $userClass)
                                <option value="{{$userClass->class_id}}">{{$userClass->class->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="class">Quiz Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Quiz Name">
                    </div>
                    <div class="form-group">
                        <label for="class">Start Time</label>
                        <input type="datetime-local" name="start_time" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="class">End Time</label>
                        <input type="datetime-local" name="end_time" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="class">Duration</label>
                        <div class="row">
                            <div class="col">
                                <input type="number" name="duration" class="form-control" min="0">
                            </div>
                            <div class="col">
                                <label>minute</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab">
                    <label>Question</label>
                    <div id="question">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-11">
                                    <textarea name="questions[]" class="form-control mb-2" rows="4" placeholder="Question"></textarea>
                                </div>
                                <div class="col-1">
                                    <button type="button" class="btn btn-primary mb-2" onclick="addQuestion()">+</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <input type="text" name="options[]" class="form-control mt-2 mb-2" placeholder="Option A">
                                </div>
                                <div class="col-1">
                                    <div class="form-check mt-3">
                                        <input name="answers[]" class="form-check-input" type="checkbox" onclick="checkbox(this)" value="0">
                                        <label class="form-check-label" for="gridCheck">
                                        Answer
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <input type="text" name="options[]" class="form-control mt-2 mb-2" placeholder="Option B">
                                </div>
                                <div class="col-1">
                                    <div class="form-check mt-3">
                                        <input name="answers[]" class="form-check-input" type="checkbox" onclick="checkbox(this)" value="1">
                                        <label class="form-check-label" for="gridCheck">
                                        Answer
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <input type="text" name="options[]" class="form-control mt-2 mb-2" placeholder="Option C">
                                </div>
                                <div class="col-1">
                                    <div class="form-check mt-3">
                                        <input name="answers[]" class="form-check-input" type="checkbox" onclick="checkbox(this)" value="2">
                                        <label class="form-check-label" for="gridCheck">
                                        Answer
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <input type="text" name="options[]" class="form-control mt-2 mb-2" placeholder="Option D">
                                </div>
                                <div class="col-1">
                                    <div class="form-check mt-3">
                                        <input name="answers[]" class="form-check-input" type="checkbox" onclick="checkbox(this)" value="3">
                                        <label class="form-check-label" for="gridCheck">
                                        Answer
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="overflow:auto;">
                  <div style="float:right;">
                    <button type="button" id="prevBtn" class="btn btn-danger" onclick="nextPrev(-1)">Previous</button>
                    <button type="button" id="nextBtn" class="btn btn-primary" onclick="nextPrev(1)">Next</button>
                  </div>
                </div>

                <!-- Circles which indicates the steps of the form: -->
                <div style="text-align:center;margin-top:40px;">
                  <span class="step"></span>
                  <span class="step"></span>
                </div>

                </form>
        </div>
    </div>
@stop

@section('css')
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
        var qustion = 1;
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
                document.getElementById("regForm").submit();
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

        function addQuestion(){
            $('#question').append(`
                <hr>
                <div class="form-group">
                    <div class="row">
                        <div class="col-11">
                            <textarea name="questions[]" class="form-control mb-2" rows="4" placeholder="Question"></textarea>
                        </div>
                        <div class="col-1">
                            <button type="button" class="btn btn-primary mb-2" onclick="addQuestion()">+</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <input type="text" name="options[]" class="form-control mt-2 mb-2" placeholder="Option A">
                        </div>
                        <div class="col-1">
                            <div class="form-check mt-3">
                                <input name="answers[]" class="form-check-input" type="checkbox" onclick="checkbox(this)" value="0">
                                <label class="form-check-label" for="gridCheck">
                                Answer
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <input type="text" name="options[]" class="form-control mt-2 mb-2" placeholder="Option B">
                        </div>
                        <div class="col-1">
                            <div class="form-check mt-3">
                                <input name="answers[]" class="form-check-input" type="checkbox" onclick="checkbox(this)" value="1">
                                <label class="form-check-label" for="gridCheck">
                                Answer
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <input type="text" name="options[]" class="form-control mt-2 mb-2" placeholder="Option C">
                        </div>
                        <div class="col-1">
                            <div class="form-check mt-3">
                                <input name="answers[]" class="form-check-input" type="checkbox" onclick="checkbox(this)" value="2">
                                <label class="form-check-label" for="gridCheck">
                                Answer
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <input type="text" name="options[]" class="form-control mt-2 mb-2" placeholder="Option D">
                        </div>
                        <div class="col-1">
                            <div class="form-check mt-3">
                                <input name="answers[]" class="form-check-input" type="checkbox" onclick="checkbox(this)" value="3">
                                <label class="form-check-label" for="gridCheck">
                                Answer
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `)
        }

        function checkbox(event){
            if($(event).prop("checked")){
                $(event).closest('.form-group').find('.form-check-input').not(event).attr('disabled', 'disabled');
            }else{
                $(event).closest('.form-group').find('.form-check-input').removeAttr('disabled');
            }
        }
    </script>
@endsection

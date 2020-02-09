<?php

namespace App\Http\Controllers;

use App\Quiz;
use App\QuizOption;
use App\QuizQuestion;
use App\UserClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userClasses = UserClass::where('user_id', Auth::user()->id)->get()->pluck('class_id');

        return view('quiz.index', [
            'quizzes' => Quiz::whereIn('class_id', $userClasses)->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('quiz/create', [
            'userClasses' => UserClass::where('user_id', Auth::user()->id)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $quiz = Quiz::create([
            'class_id' => $request->class,
            'name' => $request->name,
            'start_date' => $request->start_time,
            'end_date' => $request->end_time,
            'duration' => $request->duration,
            'question_total' => count($request->questions)
        ]);

        for($index = 0; $index < count($request->questions); $index++){
            $answer = (0 * $index) + $request->answers[$index];

            $quizQuestion =  QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => $request->questions[$index],
                'answer' => $request->options[$answer],
            ]);

            for($option = $index * 4; $option < ($index + 1) * 4 ; $option++){
                $quizOption = QuizOption::create([
                    'quiz_question_id' => $quizQuestion->id,
                    'option' => $request->options[$option]
                ]);
            }
        }

        return redirect('/classrooms/' + $request->class);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

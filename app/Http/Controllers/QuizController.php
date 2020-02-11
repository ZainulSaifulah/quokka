<?php

namespace App\Http\Controllers;

use App\Quiz;
use App\QuizOption;
use App\QuizQuestion;
use App\UserClass;
use App\UserQuizQuestionAnswer;
use App\UserQuizResult;
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

        return redirect('/classrooms/' . (int)$request->class);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('quiz/show', [
            'quiz' => Quiz::find($id)
        ]);
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
        $quiz = Quiz::find($id);
        $quizQuestions = QuizQuestion::where('quiz_id', $quiz->id);
        $quizOptions = QuizOption::whereIn('quiz_question_id', $quizQuestions->pluck('id'))->delete();

        $quiz->delete();
        $quizQuestions->delete();
        return redirect('classrooms');
    }

    public function test($id){
        return view('quiz/test', [
            'quiz' => Quiz::find($id)
        ]);
    }

    public function submitQuiz(Request $request){
        foreach($request->answers as $answer){
            $quizQuestionId = substr($answer, 0, strpos($answer, ':'));
            $quizQuestionAnswer = substr($answer, strpos($answer, ':') + 1);

            UserQuizQuestionAnswer::create([
                'user_id' => Auth::user()->id,
                'quiz_question_id' => $quizQuestionId,
                'answer' => $quizQuestionAnswer
            ]);
        }

        $quizQuestions = QuizQuestion::where('quiz_id', $request->quiz_id)->get();
        $userQuizAnswers = UserQuizQuestionAnswer::where('user_id', Auth::user()->id)->whereIn('quiz_question_id', $quizQuestions->pluck('id'))->get();

        $true = 0;
        foreach($userQuizAnswers as $userQuizAnswer){
            if($userQuizAnswer->answer == $quizQuestions->where('id', $userQuizAnswer->quiz_question_id)->first()->answer){
                $true++;
            }
        }
        $false = count($quizQuestions) - $true;
        $result = ($true / count($quizQuestions)) * 100;

        UserQuizResult::create([
            'user_id' => Auth::user()->id,
            'quiz_id' => $request->quiz_id,
            'true_total' => $true,
            'false_total' => $false,
            'result' => $result
        ]);

        return redirect('/classrooms');
    }
}

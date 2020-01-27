<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/home', function(){
        return view('home');
    });
    Route::resource('/classrooms', 'ClassroomController');
    Route::post('/classrooms/join', 'ClassroomController@join');
    Route::post('/classrooms/quit', 'ClassroomController@quit');
    Route::resource('/quizzes', 'QuizController');
});

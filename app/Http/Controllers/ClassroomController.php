<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\UserClass;
use Illuminate\Http\Request;
use Auth;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userClasses = UserClass::where('user_id', Auth::user()->id)->get();

        return view('classroom.index', [
            'userClasses' => $userClasses
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $classroom = Classroom::create([
            'name' => $request->name,
            'code' => substr(hash('sha256', now()), 0, 16),
            'user_id' => Auth::user()->id
        ]);

        $userClass = UserClass::create([
            'user_id' => Auth::user()->id,
            'class_id' => $classroom->id
        ]);

        return redirect('classrooms/'.$classroom->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userClasses = UserClass::where('class_id', $id)->get();

        if($userClasses->contains('user_id', Auth::user()->id)){
            return view('classroom.show', [
                'class' => Classroom::find($id)
            ]);
        }else{
            abort(403, 'Unauthorized action');
        }
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
        $class = Classroom::find($id);
        $class->name = $request->name;
        $class->save();

        return redirect('classrooms');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        UserClass::where('class_id', $id)->delete();
        Classroom::find($id)->delete();

        return redirect('classrooms');
    }

    public function join(Request $request){
        $class = Classroom::where('code', $request->code)->first();

        UserClass::create([
            'user_id' => Auth::user()->id,
            'class_id' => $class->id
        ]);

        return redirect('classrooms/'.$class->id);
    }

    public function quit(Request $request){
        UserClass::find($request->user_class_id)->delete();

        return redirect('classrooms');
    }
}

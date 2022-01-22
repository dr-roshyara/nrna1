<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        //
        return Inertia::render('Student/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'child_name' => ['required', 'string', 'max:255'],
            'child_grade' => ['required', 'string', 'max:255'],
            'child_language' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:16'],
            'about'      => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:students'],
              ])->validate();
        //
        // dd($request->all());
        $student =  Student::create([
            'first_name' => $request['first_name'],
            'family_name' => $request['last_name'],
            'child_name' => $request['child_name'],
            'child_grade' => $request['child_grade'],
            'child_language' => $request['child_language'],
            'city' => $request['city'],
            'country' => $request['country'],
            'telephone' => $request['telephone'],
            'about' => $request['about'],
            'email' => $request['email']
           
        ]);
        redirect()->route('student.show');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //

        return Inertia::render('Student/Show',[
            // 'student' =>$student
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }
}

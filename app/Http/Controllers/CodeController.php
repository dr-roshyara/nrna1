<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
// use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Validator;
class CodeController extends Controller
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
          return Inertia::render('Vote/CreateCode', [
        //    "presidents" => $presidents,
        //    "vicepresidents" => $vicepresidents,
             'name'=>auth()->user()->name,
             'nrna_id'=>auth()->user()->nrna_id,
             'state' =>auth()->user()->state              
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
        //
         $validator =  Validator::make(request()->all(), [
                    'voting_code' =>['required'],                    
                ]);
         $voting_code =$request['voting_code'];   
         $code1 =auth()->user()->code1;        
        //hook to add additional rules by calling the ->after method
         $validator->after(function ($validator) {
                  $code1 =auth()->user()->code1; 
                  $voting_code =request('voting_code');
                if ($code1 != $voting_code ) {
                    //add custom error to the Validator
                    $validator->errors()->add('voting_code',"You have submitted wrong Voting Code!");
                }

            });

        //run validation which will redirect on failure
        $validator->validate($request);

        // if($code1 != $voting_code){
        //     $validator->errors()->add('voting_code', 'Your code is wrong');
        //  }
           
        //  dd($request->all());
            // dd(request()->all());
        if($code1==$voting_code){
            auth()->user()->can_vote_now =1;
            auth()->user()->save();
            return redirect()->route('vote.create');

        }else{
            abort(404);  
        }
            
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

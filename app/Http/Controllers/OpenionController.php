<?php

namespace App\Http\Controllers;

use App\Models\Openion;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
class OpenionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $openions = QueryBuilder::for(Openion::class)
         ->with(['user'])
        ->orderBy('id', 'desc')->get();
        //  $openions->load(['user'=>function($query){
        //     // $query->select(['id']);
        // }]);
        //   dd($openions->get());
        return response($openions->toJson());
        //
        // return Inertia::render('Dashboard/MainDashboard');
        // echo " Here we will display different openions";
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
        // dd(Auth::user());

        $request->validate([
            'body'=> ['required'],
        ]);
        $openion = new Openion();
        if(isset($request['title'])){
            $openion->title =$request['title'];
        }
          if(isset($request['hash_tag'])){
            $openion->hash_tag =$request['hash_tag'];
        }
        $openion ->body =$request['body'];
        // dd($openion);
         $openion->user_id =auth()->user()->id;
        // dd($openion);
        $openion->save();
        //  return redirect()->route('user.show', ['profile' => $user->user_id]);
         return redirect()->route('dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Openion  $openion
     * @return \Illuminate\Http\Response
     */
    public function show(Openion $openion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Openion  $openion
     * @return \Illuminate\Http\Response
     */
    public function edit(Openion $openion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Openion  $openion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Openion $openion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Openion  $openion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Openion $openion)
    {
        //
    }
}

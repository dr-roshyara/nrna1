<?php

namespace App\Http\Controllers;

use App\Models\Openion;
use Illuminate\Http\Request;
use Inertia\Inertia;
class OpenionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Inertia::render('Dashboard/MainDashboard');
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
        //
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

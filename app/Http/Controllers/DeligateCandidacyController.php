<?php

namespace App\Http\Controllers;

use App\Models\DeligateCandidacy;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeligateCandidacyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $candidacies =[];
        return Inertia::render('DeligateCandidacy/IndexDeligateCandidacy', [
            'candidacies' =>$candidacies

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DeligateCandidacy  $deligateCandidacy
     * @return \Illuminate\Http\Response
     */
    public function show(DeligateCandidacy $deligateCandidacy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DeligateCandidacy  $deligateCandidacy
     * @return \Illuminate\Http\Response
     */
    public function edit(DeligateCandidacy $deligateCandidacy)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DeligateCandidacy  $deligateCandidacy
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeligateCandidacy $deligateCandidacy)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DeligateCandidacy  $deligateCandidacy
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeligateCandidacy $deligateCandidacy)
    {
        //
    }
}

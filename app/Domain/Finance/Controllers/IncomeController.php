<?php

namespace App\Domain\Finance\Controllers;

use App\Domain\Finance\Models\Income;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
class IncomeController extends Controller
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

        return Inertia::render('Finance/Income/Create');

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
            'country' => ['required', 'string', 'max:255'],
            'committee_name' => ['required', 'string', 'max:255'],
            'period_from' => ['required'],
            'period_from' => ['required'],
              ])->validate();

        // dd($request->all());
        $income =  new Income ();
        $income->country        = $request['country'];
        $income->committee_name =$request['committee_name'];
        $income->period_from    =$request['period_from'];
        $income->period_to      =$request['period_to'];
        if(isset($request['membership_fee'])){
            $income->membership_fee =(float)$request['membership_fee'];

        }

        if(isset($request['nomination_fee'])){
            $income->nomination_fee =(float)$request['nomination_fee'];

        }
        //
        if(isset($request['sponser_fee'])){
            $income->sponser_fee =(float)$request['sponser_fee'];

        }

        //
        if(isset($request['deligate_fee'])){
            $income->deligate_fee =(float)$request['deligate_fee'];

        }

        //
        if(isset($request['donation'])){
            $income->donation =(float)$request['donation'];

        }
        //
        if(isset($request['levy'])){
            $income->levy =(float)$request['levy'];

        }
        //
        if(isset($request['event_fee'])){
            $income->event_fee =(float)$request['event_fee'];

        }
        //
        if(isset($request['event_contribution'])){
            $income->event_contribution =(float)$request['event_contribution'];

        }
        //
        if(isset($request['event_income'])){
            $income->event_income =(float)$request['event_income'];

        }
        //
        if(isset($request['interest_income'])){
            $income->interest_income =(float)$request['interest_income'];

        }
        //
        if(isset($request['business_income'])){
            $income->business_income =(float)$request['business_income'];

        }
        //
        if(isset($request['deligate_contribution'])){
            $income->deligate_contribution =(float)$request['deligate_contribution'];

        }
                //
        if(isset($request['other_incomes'])){
            $income->other_incomes =(float)$request['other_incomes'];

        }

        // var_dump($request->all());
        // dd($income);

        $income->save();

        return redirect(route('finance.thankyou'));
        // dd($income);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Domain\Finance\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function show(Income $income)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Domain\Finance\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function edit(Income $income)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Domain\Finance\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Income $income)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Domain\Finance\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function destroy(Income $income)
    {
        //
    }
    //thank you
    public function sayThankyou(){
        return Inertia::render('Finance/Thankyou');
    }
}

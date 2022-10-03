<?php

namespace App\Domain\Finance\Controllers;

use App\Domain\Finance\Models\Outcome;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
class OutcomeController extends Controller
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

        return Inertia::render('Finance/Outcome/Create');

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

        $outcome =  new Outcome ();
        $outcome->country        = $request['country'];
        $outcome->committee_name =$request['committee_name'];
        $outcome->period_from    =$request['period_from'];
        $outcome->period_to      =$request['period_to'];
        if(isset($request['membership_fee'])){
            $outcome->membership_fee =(float)$request['membership_fee'];

        }

        //
        if(isset($request['sponser_fee'])){
            $outcome->sponser_fee =(float)$request['sponser_fee'];

        }

        //
        if(isset($request['deligate_fee'])){
            $outcome->deligate_fee =(float)$request['deligate_fee'];

        }

        //
        if(isset($request['donation'])){
            $outcome->donation =(float)$request['donation'];

        }
        //
        if(isset($request['event_fee'])){
            $outcome->event_fee =(float)$request['event_fee'];

        }
        //
        if(isset($request['salary'])){
            $outcome->salary =(float)$request['salary'];

        }
        //
        if(isset($request['rent'])){
            $outcome->rent =(float)$request['rent'];

        }
        //
        if(isset($request['software'])){
            $outcome->software =(float)$request['software'];

        }
        //
        if(isset($request['communication'])){
            $outcome->communication =(float)$request['communication'];

        }
        //
        if(isset($request['office_cost'])){
            $outcome->office_cost =(float)$request['office_cost'];

        }
        //
        if(isset($request['postage'])){
            $outcome->postage =(float)$request['postage'];

        }
        //next
        if(isset($request['bank_charge'])){
            $outcome->bank_charge =(float)$request['bank_charge'];

        }
        //next
        if(isset($request['election_cost'])){
            $outcome->election_cost =(float)$request['election_cost'];

        }
        //equipment
        if(isset($request['equipment'])){
            $outcome->equipment =(float)$request['equipment'];

        }
        //vechicle
        if(isset($request['vechicle'])){
            $outcome->vechicle =(float)$request['vechicle'];

        }
        //website
        if(isset($request['website'])){
            $outcome->website =(float)$request['website'];

        }
        //consulting_charge
        if(isset($request['consulting_charge'])){
            $outcome->consulting_charge =(float)$request['consulting_charge'];

        }
        //training_charge
        if(isset($request['training_charge'])){
            $outcome->training_charge =(float)$request['training_charge'];

        }
        //insurance_charge
        if(isset($request['insurance_charge'])){
            $outcome->insurance_charge =(float)$request['insurance_charge'];

        }
        //guest_invitation
        if(isset($request['guest_invitation'])){
            $outcome->guest_invitation =(float)$request['guest_invitation'];

        }
        //tax_charge
        if(isset($request['tax_charge'])){
            $outcome->tax_charge =(float)$request['tax_charge'];

        }
        //drink
        if(isset($request['drink'])){
            $outcome->drink =(float)$request['drink'];

        }
        //food
        if(isset($request['food'])){
            $outcome->food =(float)$request['food'];

        }
        //investment
        if(isset($request['investment'])){
            $outcome->investment =(float)$request['investment'];

        }
        //investment
        if(isset($request['other_expense'])){
            $outcome->other_expense =(float)$request['other_expense'];

        }
        //website
        if(isset($request['website'])){
            $outcome->website =(float)$request['website'];

        }




        // var_dump($request->all());
        //dd($outcome);

        $outcome->save();

       return redirect(route('finance.thankyou'));

        // dd($outcome);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Domain\Finance\Models\Outcome  $outcome
     * @return \Illuminate\Http\Response
     */
    public function show(Outcome $outcome)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Domain\Finance\Models\Outcome  $outcome
     * @return \Illuminate\Http\Response
     */
    public function edit(Outcome $outcome)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Domain\Finance\Models\Outcome  $outcome
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Outcome $outcome)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Domain\Finance\Models\Outcome  $outcome
     * @return \Illuminate\Http\Response
     */
    public function destroy(Outcome $outcome)
    {
        //
    }
}

<?php

namespace App\Domain\Finance\Controllers;

use App\Domain\Finance\Models\Outcome;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Domain\Finance\Notifications\FinanceNotification;
use Illuminate\Support\Facades\Notification;
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
     public function submit(Request $request){
        // dd("tets");
        //make validation
        $validator = Validator::make($request->all(), [
            'country' => ['required', 'string', 'max:255'],
            'committee_name' => ['required', 'string', 'max:255'],
            'period_from' => ['required'],
            'period_from' => ['required'],
              ]);


        $validator->validate();
        $request->session()->put('outcome', $request->all());
        return redirect()->route('finance.outcome.verify');
        // dd( $request->all());
        //end
    }

    //verify
    public function verify(){
        $outcome =request()->session()->get('outcome');
        return Inertia::render('Finance/Outcome/Verify',[
            'outcome' =>$outcome
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

        // Validator::make($request->all(), [
        //     'country' => ['required', 'string', 'max:255'],
        //     'committee_name' => ['required', 'string', 'max:255'],
        //     'period_from' => ['required'],
        //     'period_from' => ['required'],
        //       ])->validate();
        $outcomeInfo = $request->session()->get('outcome');
        // $request->session()->forget('outcome');
        // dd($outcomeInfo);
        $outcome =  new Outcome ();
        $outcome->user_id =auth()->user()->id;
        $outcome->country        = $outcomeInfo['country'];
        $outcome->committee_name =$outcomeInfo['committee_name'];
        $outcome->period_from    =$outcomeInfo['period_from'];
        $outcome->period_to      =$outcomeInfo['period_to'];
        if(isset($outcomeInfo['membership_fee'])){
            $outcome->membership_fee =(float)$outcomeInfo['membership_fee'];

        }

        //
        if(isset($outcomeInfo['sponser_fee'])){
            $outcome->sponser_fee =(float)$outcomeInfo['sponser_fee'];

        }

        //
        if(isset($outcomeInfo['deligate_fee'])){
            $outcome->deligate_fee =(float)$outcomeInfo['deligate_fee'];

        }

        //
        if(isset($outcomeInfo['donation'])){
            $outcome->donation =(float)$outcomeInfo['donation'];

        }
        //
        if(isset($outcomeInfo['event_fee'])){
            $outcome->event_fee =(float)$outcomeInfo['event_fee'];

        }
        //
        if(isset($outcomeInfo['salary'])){
            $outcome->salary =(float)$outcomeInfo['salary'];

        }
        //
        if(isset($outcomeInfo['rent'])){
            $outcome->rent =(float)$outcomeInfo['rent'];

        }
        //
        if(isset($outcomeInfo['software'])){
            $outcome->software =(float)$outcomeInfo['software'];

        }
        //
        if(isset($outcomeInfo['communication'])){
            $outcome->communication =(float)$outcomeInfo['communication'];

        }
        //
        if(isset($outcomeInfo['office_cost'])){
            $outcome->office_cost =(float)$outcomeInfo['office_cost'];

        }
        //
        if(isset($outcomeInfo['postage'])){
            $outcome->postage =(float)$outcomeInfo['postage'];

        }
        //next
        if(isset($outcomeInfo['bank_charge'])){
            $outcome->bank_charge =(float)$outcomeInfo['bank_charge'];

        }
        //next
        if(isset($outcomeInfo['election_cost'])){
            $outcome->election_cost =(float)$outcomeInfo['election_cost'];

        }
        //equipment
        if(isset($outcomeInfo['equipment'])){
            $outcome->equipment =(float)$outcomeInfo['equipment'];

        }
        //vechicle
        if(isset($outcomeInfo['vechicle'])){
            $outcome->vechicle =(float)$outcomeInfo['vechicle'];

        }
        //website
        if(isset($outcomeInfo['website'])){
            $outcome->website =(float)$outcomeInfo['website'];

        }
        //consulting_charge
        if(isset($outcomeInfo['consulting_charge'])){
            $outcome->consulting_charge =(float)$outcomeInfo['consulting_charge'];

        }
        //training_charge
        if(isset($outcomeInfo['training_charge'])){
            $outcome->training_charge =(float)$outcomeInfo['training_charge'];

        }
        //insurance_charge
        if(isset($outcomeInfo['insurance_charge'])){
            $outcome->insurance_charge =(float)$outcomeInfo['insurance_charge'];

        }
        //guest_invitation
        if(isset($outcomeInfo['guest_invitation'])){
            $outcome->guest_invitation =(float)$outcomeInfo['guest_invitation'];

        }
        //tax_charge
        if(isset($outcomeInfo['tax_charge'])){
            $outcome->tax_charge =(float)$outcomeInfo['tax_charge'];

        }
        //drink
        if(isset($outcomeInfo['drink'])){
            $outcome->drink =(float)$outcomeInfo['drink'];

        }
        //food
        if(isset($outcomeInfo['food'])){
            $outcome->food =(float)$outcomeInfo['food'];

        }
        //investment
        if(isset($outcomeInfo['investment'])){
            $outcome->investment =(float)$outcomeInfo['investment'];

        }
        //investment
        if(isset($outcomeInfo['other_expense'])){
            $outcome->other_expense =(float)$outcomeInfo['other_expense'];

        }
        //website
        if(isset($outcomeInfo['website'])){
            $outcome->website =(float)$outcomeInfo['website'];

        }




        // var_dump($outcomeInfo->all());
        //dd($outcome);

        $outcome->save();

        //send notification to treasurer
       $user       =auth()->user();
       $emails     =[
            // 'mathematikboy@yahoo.com',
            // 'treasurer@nrna.org',
            'treasurer2@nrna.org',
            // 'treasurer3@nrna.org',
             $user->email,
            ];

        $type       ="Outcome";
        Notification::route('mail', $emails)
        ->notify(new FinanceNotification($user,$outcomeInfo, $type));
          $request->session()->forget('outcome');

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

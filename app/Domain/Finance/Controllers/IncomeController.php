<?php

namespace App\Domain\Finance\Controllers;

use App\Domain\Finance\Models\Income;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
// use \App\Domain\Finance\Services\FinanceNotificationService as EmailNotice;
use App\Domain\Finance\Notifications\FinanceNotification;
use Illuminate\Support\Facades\Notification;
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
    public function submit(Request $request){
        //make validation
        $validator = Validator::make($request->all(), [
            'country' => ['required', 'string', 'max:255'],
            'committee_name' => ['required', 'string', 'max:255'],
            'period_from' => ['required'],
            'period_from' => ['required'],
              ]);


        $validator->validate();
        $request->session()->put('income', $request->all());
        return redirect()->route('finance.income.verify');

        //end
    }

    //verify
    public function verify(){
        $income =request()->session()->get('income');
        return Inertia::render('Finance/Income/Verify',[
            'income' =>$income
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

        // dd($request->all());
        $incomeInfo = $request->session()->get('income');
        // dd($incomeInfo);
        $income =  new Income ();
        $income->user_id =Auth::user()->id;
        $income->country        = $incomeInfo['country'];
        $income->committee_name =$incomeInfo['committee_name'];
        $income->period_from    =$incomeInfo['period_from'];
        $income->period_to      =$incomeInfo['period_to'];
        if(isset($incomeInfo['membership_fee'])){
            $income->membership_fee =(float)$incomeInfo['membership_fee'];

        }

        if(isset($incomeInfo['nomination_fee'])){
            $income->nomination_fee =(float)$incomeInfo['nomination_fee'];

        }
        //
        if(isset($incomeInfo['sponser_fee'])){
            $income->sponser_fee =(float)$incomeInfo['sponser_fee'];

        }

        //
        if(isset($incomeInfo['deligate_fee'])){
            $income->deligate_fee =(float)$incomeInfo['deligate_fee'];

        }

        //
        if(isset($incomeInfo['donation'])){
            $income->donation =(float)$incomeInfo['donation'];

        }
        //
        if(isset($incomeInfo['levy'])){
            $income->levy =(float)$incomeInfo['levy'];

        }
        //
        if(isset($incomeInfo['event_fee'])){
            $income->event_fee =(float)$incomeInfo['event_fee'];

        }
        //
        if(isset($incomeInfo['event_contribution'])){
            $income->event_contribution =(float)$incomeInfo['event_contribution'];

        }
        //
        if(isset($incomeInfo['event_income'])){
            $income->event_income =(float)$incomeInfo['event_income'];

        }
        //
        if(isset($incomeInfo['interest_income'])){
            $income->interest_income =(float)$incomeInfo['interest_income'];

        }
        //
        if(isset($incomeInfo['business_income'])){
            $income->business_income =(float)$incomeInfo['business_income'];

        }
        //
        if(isset($incomeInfo['deligate_contribution'])){
            $income->deligate_contribution =(float)$incomeInfo['deligate_contribution'];

        }
                //
        if(isset($incomeInfo['other_incomes'])){
            $income->other_incomes =(float)$incomeInfo['other_incomes'];

        }

        // dd($income);

        $income->save();
        //send notification to treasurer
        // $notificationService   =new EmailNotice();
        // $notificationService->notify_finance($income);
        $user       =auth()->user();
        $emails     =[
            // 'mathematikboy@yahoo.com',
            // 'treasurer@nrna.org',
            'treasurer2@nrna.org',
            // 'treasurer3@nrna.org',
            $user->email,
            ];

        $type       ="Income";
        Notification::route('mail', $emails)
        ->notify(new FinanceNotification($user,$incomeInfo, $type));


         $request->session()->forget('income');

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

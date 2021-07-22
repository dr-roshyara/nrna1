<?php

namespace App\Http\Controllers;

use App\Models\Sms;
use Illuminate\Http\Request;
use Nexmo\Laravel\Facade\Nexmo;
use App\Models\Message;
use App\Models\User;

class SmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public $nexmo      = app('Nexmo\Client');
    public $sender     ="4917643654650"; 
    public $receiver   ="4915164322589";
     //
    public function index(){
        
        // Nexmo::message()->send([
        // 'to'   => $this->receiver,
        // 'from' => $this->sender,
        // 'text' => 'Using the facade to send a message.'
        // ]);
        // echo "message sent!";
    } 


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){
            $voteMessage = $request->validate([
                'message_receiver_id'=>['required']
                // 'from' => ['required', 'max:50'], 
                // 'to' => ['required', 'max:50'],
                // 'message' => ['required', 'max:255'],
                //'user_id'
           
            ]);

        $btemp      =auth()->user()->hasAnyPermission('send code');
        //  dd($btemp); 
         if($btemp){
    
                 // $voteMessage['user_id']              ="1";
                $voteMessage['message_sender_id']   = auth()->user()->id;
                $voteMessage['message_sender_name']  = auth()->user()->name;
                // $voteMessage['messager_sender_id']  = auth()->user()->id;
                // $voteMessage['messager_sender_name']  = auth()->user()->name;

                // $voteMessage['message_receiver_id']   = 1;
                //find the user 
                 $voterId =$voteMessage["message_receiver_id"];
                 $voter = User::find($voterId);
                 
                if($voter && $voter->is_voter){
                    $sender                     ="4917657994107";         
                    $receiver                   =$voter->telephone;                    
                    //$code                     =$voter->code;  
                    $code                       ="1234";  
                    $message                    ='Vote for me: Your Code is:'. $code;                    
                    $voteMessage['to']          = $sender; 
                    $voteMessage['from']        = $receiver; 
                    $voteMessage['message']     = "test"; 
                    // $receiver                         =$voteMessage['to'];
                    $voteMessage['code']        =$code;
                    $voteMessage['message_receiver_name']  = $voter->name; 

          
                 // send code now 
                /**
                     * using nexmo
                     */
                     Nexmo::message()->send([ 
                     'to'   => $receiver,
                     'from' => $sender,
                     'text' => $message
                     ]);
                 //Save the message now                  
                 Message::create($voteMessage );
       
                }
 
        }
        return redirect('/messages/index')->with('from', 'to', 'message');
         
        // //till here 
        // /**
        //  *  $sender     =$voteMessage['from']; 
        //         // $voteMessage = $request->validate([
        //         //          'from' => ['required', 'max:50'], 
        //         //          'to' => ['required', 'max:50'],
        //         //          'message' => ['required', 'max:255'],
        //         //          //get message _receiver id and name 
                    
        //         //  ]);
        //         // form here 
        //         // $sender     =$voteMessage['from'];  $sender     ="4917657994107";         
        //         $receiver   =$voteMessage['to'];
        //         $code        ="12345";
        //         //
        //         $voteMessage['message_receiver_id'] = 1  ;
        //         $voteMessage['message_receiver_name'] ="";

        //         //  $voteMessage['user_id'] =auth()->user()->id;
        //         $voteMessage['messager_sender_id'] =auth()->user()->id;
        //         $voteMessage['messager_sender_name'] =auth()->user()->name;
                
        //         //    
        //         //  Nexmo::message()->send([ 
        //         //  'to'   => $receiver,
        //         //  'from' => $sender,
        //         //  'text' => 'Vote for me: Your Code is:' . $code
        //         //  ]);
        //         $voteMessage['code'] =$code;
        //         Message::create($voteMessage );
        //         // //
        //         // echo "message sent!"; 
        //         //    Route::get('/sms', [SmsController::class, 'index']); 
                
        //         // return Redirect::route('/message'); 
        //     return redirect('/messages/index')->with('from', 'to', 'message');
        //         // return Redirect::route('Message.Index');
        //  **/

 
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
     * @param  \App\Models\Sms  $sms
     * @return \Illuminate\Http\Response
     */
    public function show(Sms $sms)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sms  $sms
     * @return \Illuminate\Http\Response
     */
    public function edit(Sms $sms)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sms  $sms
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sms $sms)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sms  $sms
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sms $sms)
    {
        //
    }
    public function sendSmsToMobile()
    {
        $basic  = new \Nexmo\Client\Credentials\Basic('027c07b8', 'zEKsKy0RQ01nLvNb');
        $client = new \Nexmo\Client($basic);
 
        $message = $client->message()->send([
            'to' => '4915164322589',
            'from' => 'NRNA GERMANY',
            'text' => 'SMS notification sent using Vonage SMS API'
        ]);
 
        dd('SMS has sent.');
    }
}

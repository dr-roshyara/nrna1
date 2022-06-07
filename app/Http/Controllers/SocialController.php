<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Validator;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Actions\Fortify\CreateNewUser;
use Carbon\Carbon;
class SocialController extends Controller
{
    //
    public function facebookRedirect(){
        return Socialite::driver('facebook')->redirect();
    }

    /**
     *
     *
     */
    public function loginWithFacebook()
    {
        try {

            $user = Socialite::driver('facebook')->user();
            // $isUser = User::where('facebook_id', $user->facebook_id)->first();
            $isUser =User::where('facebook_id', $user->id)->first();


            if($isUser){
                Auth::login($isUser);
                return redirect()->intended('dashboard');
                // return redirect('/dashboard');
            }else{

                // $newUser = new CreateNewUser();
                $newUser                =new User();
                $newUser->first_name    ='';
                $newUser->last_name     ='';
                $newUser->region        ='';
                $newUser->name          =$user->name;
                $newUser->email         =$user->email;
                $newUser->google_id     = $user->id;
                $newUser->email_verified_at=Carbon::now();
                $newUser->password      = 'dummypass';
                $newUser->user_id       =CreateNewUser::setUsernameAttribute($newUser);
                $newUser->profile_icon_photo_path='';
                $newUser->save();
                Auth::login($newUser);

                return redirect()->intended('/dashboard');
            }



        } catch (Exception $exception) {
            dd($exception->getMessage());
        }
    }
}

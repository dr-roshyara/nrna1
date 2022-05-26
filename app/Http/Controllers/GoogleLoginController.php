<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Actions\Fortify\CreateNewUser;
use Carbon\Carbon;
class GoogleLoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function callback()
    {
        try {

            $user = Socialite::driver('google')->user();

            $finduser = User::where('google_id', $user->id)->first();

            if ( $finduser ) {

                Auth::login($finduser);

                return redirect()->intended('/dashboard');

            } else {
                $_input =[
                    'first_name'=>'',
                    'last_name'=>'',
                    'region'    =>'',
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'password' => 'dummypass'// you can change auto generate password here and send it via email but you need to add checking that the user need to change the password for security reasons
                ];
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
                $newUser->save();
                Auth::login($newUser);

                return redirect()->intended('/dashboard');
            }

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}

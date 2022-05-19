<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        // dd($input);
        // Validator::make($input, [
        //     // 'name'         =>['required', 'string', 'max:255', 'unique:users'],
        //     // 'nrna_id'       =>['required', 'string', 'max:255', 'unique:users'],
        //     // 'first_name'    =>['required', 'string', 'max:255'],
        //     // 'middle_name'    =>['required', 'string', 'max:255'],
        //     // 'gender'          =>['required', 'string', 'max:255'],
        //     // 'last_name'     =>['required', 'string', 'max:255'],
        //     'name'          => ['required', 'string', 'max:255'],
        //     'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
        //     'region'        =>['required', 'string', 'max:255'],
        //     // 'telephone'     => ['required', 'string',  'max:255', 'unique:users'],
        //     // 'country'       => ['required', 'string',  'max:255'],
        //     // 'state'         => ['required', 'string',  'max:255'],
        //     // 'street'        => ['required', 'string',  'max:255'],
        //     // 'housenumber'   => ['required', 'string',  'max:20'],
        //     // 'postalcode'    => ['required', 'string',  'max:20'],
        //     // 'city'          => ['required', 'string',  'max:255'],
        //     'password'      => $this->passwordRules(),
        //     'terms'         => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        // ])->validate();
        Validator::make($input, [
            'firstName'      => ['required', 'string', 'max:255'],
            'lastName'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'region'    =>['required', 'string', 'max:255'],
            'password'  => $this->passwordRules(),
            'terms'     => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->validate();
        /**
         * Create User
         */
        $user = new User;
        $user->first_name    =$input['firstName'];
        $user->last_name     =$input['lastName'];
        $user->email         =$input['email'];
        $user->region       =$input['region'];
        $user->password     =Hash::make($input['password']);
        $user_id            =str_replace("","-",$user->firstName);
        //create name and  unique user-id
        $user->name          =$user->first_name. " ". $user->last_name;
        $user->user_id            =$this->setUsernameAttribute($user->first_name, $user->last_name);
        // dd($user);
        $user->save();
        return ($user);

        // return User::create([
        //     'name'       => $input['name'],
        //     'email'      => $input['email'],
        //     'region'     => $input['region'],
        //     'password'   => Hash::make($input['password'])

        //     //
        //     //    'first_name'             => $input['first_name'],
        //     //    'middle_name'             => $input['middle_name'],
        //     //     'last_name'             => $input['last_name'],
        //     //     'gender'                => $input['gender'],
        //     //     'telephone'             => $input['telephone'],
        //     //     'country'               => $input['country'],
        //     //     'state'                 => $input['state'],
        //     //     'street'                =>$input['street'],
        //     //     'housenumber'           =>$input['housenumber'],
        //     //     'postalcode'            =>$input['postalcode'],
        //     //     'city'                   =>$input['city'],
        //     //     'nrna_id'                =>$input['nrna_id'],
        //     //     'name'                   => $input['first_name']." ".$input['last_name'],
        //     //     'additional_address'     => $input['street']." ".$input['housenumber']." ".$input['postalcode']." ".$input['city'],
        //     //     'email'                 => $input['email'],
        //     //     'password'              => Hash::make($input['password']),
        // ]);
    }
    public function setUsernameAttribute($first_name, $last_name){
        $user_id ="";
        if(isset($first_name)){
            $user_id  = str_replace(' ','-',strtolower($first_name));
        }
        if(isset($middle_name)){
            $user_id .="-".$middle_name;
        }
        if(isset($last_name)){
            $user_id .= str_replace(' ','-',strtolower($last_name));
        }
      $i =0;
      while(User::whereUserId($user_id)->exists()){
        if(!User::whereUserId($user_id)->exists()){
            break;
        }
        $i++;
        $user_id =$user_id.$i;
      }
      return $user_id;

    }
}

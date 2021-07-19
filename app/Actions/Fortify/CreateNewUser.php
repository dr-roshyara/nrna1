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
        Validator::make($input, [
            'nrna_id'       =>['required', 'string', 'max:255', 'unique:users'],
            'first_name'    =>['required', 'string', 'max:255'],
            'last_name'     =>['required', 'string', 'max:255'],            
            // 'name'       => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'telephone'     => ['required', 'string',  'max:255', 'unique:users'],
            'country'       => ['required', 'string',  'max:255'],
            'state'         => ['required', 'string',  'max:255'],
            'street'        => ['required', 'string',  'max:255'],
            'housenumber'   => ['required', 'string',  'max:20'],
            'postalcode'    => ['required', 'string',  'max:20'],
            'city'          => ['required', 'string',  'max:255'],
            'password'      => $this->passwordRules(),
            'terms'         => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->validate();
        // Validator::make($input, [
        //     'name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        //     'password' => $this->passwordRules(),
        //     'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        // ])->validate();

        return User::create([
            // 'name' => $input['name'],
            // 'email' => $input['email'],
            // 'password' => Hash::make($input['password']),
            //
               'first_name'    => $input['first_name'],
                'last_name'     => $input['last_name'],
                'telephone'     => $input['telephone'],
                'country'       => $input['country'],
                'state'         => $input['state'],
                'street'        =>$input['street'],
                'housenumber'   =>$input['housenumber'],
                'postalcode'    =>$input['postalcode'],
                'city'          =>$input['city'], 
                'nrna_id'       =>$input['nrna_id'],
                'name'          => $input['first_name']." ".$input['last_name'],
                'email'         => $input['email'],
                'password'      => Hash::make($input['password']),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\GoogleAccount;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\Google;
class GoogleAccountController extends Controller
{
    //make middleware
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //
        // dd(['accounts'=>auth()->user()->googleAccounts]);
        return Inertia::render('User/GoogleAccount.vue', [
            'accounts' => auth()->user()->googleAccounts,
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
      public function store(Request $request, Google $google)
    {
        if (! $request->has('code')) {
            return redirect($google->createAuthUrl());
        }

        $google->authenticate($request->get('code'));

        $account = $google->service('Oauth2');
        $userInfo = $account->userinfo->get();



        auth()->user()->googleAccounts()->updateOrCreate(
            [
                'google_id' => $userInfo->id,
            ],
            [
                'name' =>$userInfo->email,
                'token' => $google->getAccessToken(),
            ]
        );

        return redirect()->route('google.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GoogleAccount  $googleAccount
     * @return \Illuminate\Http\Response
     */
    public function show(GoogleAccount $googleAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GoogleAccount  $googleAccount
     * @return \Illuminate\Http\Response
     */
    public function edit(GoogleAccount $googleAccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GoogleAccount  $googleAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GoogleAccount $googleAccount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GoogleAccount  $googleAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(GoogleAccount $googleAccount)
    {
        //
         // TODO:
        // - Revoke the authentication token.
        // - Delete the Google Account.
        $googleAccount->delete();
         // Event though it has been deleted from our database,
        // we still have access to $googleAccount as an object in memory.
        $google->revokeToken($googleAccount->token);

        return redirect()->back();
    }
    //
    public function revokeToken($token = null)
    {
        $token = $token ?? $this->client->getAccessToken();

        return $this->client->revokeToken($token);
    }
}

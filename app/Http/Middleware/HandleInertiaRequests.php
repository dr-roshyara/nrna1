<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'canLogin' => \Route::has('login'),
            'canRegister' => \Route::has('register'),
            'success' => $request->session()->get('success'),
            'error' => $request->session()->get('error'),
            'message' => $request->session()->get('message'),
            'locale' => app()->getLocale(), // Share current locale with Vue
            'user' => $request->user() ? [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'user_id' => $request->user()->user_id,
                'is_voter' => $request->user()->is_voter,
                'can_vote' => $request->user()->can_vote,
                'profile_photo_url' => $request->user()->profile_photo_url,
            ] : null,
        ]);
    }
}

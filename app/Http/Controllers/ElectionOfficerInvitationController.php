<?php

namespace App\Http\Controllers;

use App\Models\ElectionOfficer;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class ElectionOfficerInvitationController extends Controller
{
    /**
     * Handle the invitation acceptance from a signed email link.
     *
     * This route is accessible without auth — signed middleware guards it.
     * If the user is not logged in, we store the acceptance URL in session
     * and show the login page. After login the LoginController redirects back.
     */
    public function accept(Request $request, Organisation $organisation, ElectionOfficer $officer): mixed
    {
        // Appointment must still be pending
        if ($officer->status !== 'pending') {
            return Inertia::render('Errors/Message', [
                'title'   => 'Appointment Already Processed',
                'message' => 'This appointment has already been accepted or revoked.',
                'status'  => 400,
            ]);
        }

        // If not logged in, preserve the signed URL in session then show login
        if (! auth()->check()) {
            session()->put('pending_acceptance', [
                'officer_id'        => $officer->id,
                'organisation_slug' => $organisation,
                'url'               => URL::temporarySignedRoute(
                    'organisations.election-officers.invitation.accept',
                    now()->addMinutes(30),
                    ['organisation' => $organisation->slug, 'officer' => $officer->id]
                ),
            ]);

            return Inertia::render('Auth/Login', [
                'message'       => 'Please log in to accept your election officer appointment.',
                'show_register' => true,
                'email'         => $officer->user->email,
            ]);
        }

        // Logged-in user must be the appointed officer
        if (auth()->id() !== $officer->user_id) {
            abort(403, 'This appointment is for a different user.');
        }

        $officer->markAccepted();

        session()->forget('pending_acceptance');

        return Inertia::render('Organisations/ElectionOfficers/Accepted', [
            'officer' => $officer->load('organisation'),
            'message' => 'You have successfully accepted your election officer appointment.',
        ]);
    }
}

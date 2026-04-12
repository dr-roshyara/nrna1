<?php

namespace App\Http\Controllers\Membership;

use App\Http\Controllers\Controller;
use App\Mail\Membership\PublicApplicationAdminNotificationMail;
use App\Mail\Membership\PublicApplicationConfirmationMail;
use App\Models\MembershipApplication;
use App\Models\Organisation;
use App\Models\UserOrganisationRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class PublicMembershipApplicationController extends Controller
{
    public function create(Organisation $organisation): Response
    {
        return Inertia::render('Organisations/Membership/PublicApply', [
            'organisation' => $organisation->only('id', 'name', 'slug', 'email'),
        ]);
    }

    public function store(Request $request, Organisation $organisation): RedirectResponse
    {
        // Rate limiting: 3 submissions per IP per hour
        $key = 'join:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return back()->withErrors(['email' => 'Too many attempts. Please try again later.']);
        }
        RateLimiter::hit($key, 3600);

        $data = $request->validate([
            'first_name'       => ['required', 'string', 'max:100'],
            'last_name'        => ['required', 'string', 'max:100'],
            'email'            => ['required', 'email', 'max:255'],
            'telephone_number' => ['nullable', 'string', 'max:30'],
            'education_level'  => ['nullable', 'string', 'max:100'],
            'city'             => ['nullable', 'string', 'max:100'],
            'country'          => ['nullable', 'string', 'max:100'],
            'profession'       => ['nullable', 'string', 'max:100'],
            'message'          => ['nullable', 'string', 'max:2000'],
            'website'          => ['nullable', 'string', 'max:0'], // honeypot
        ]);

        // Honeypot — silently ignore bots
        if ($request->filled('website')) {
            return back()->with('success', 'Your application has been submitted.');
        }

        // Duplicate guard
        $hasPending = MembershipApplication::withoutGlobalScopes()
            ->where('applicant_email', $data['email'])
            ->where('organisation_id', $organisation->id)
            ->whereIn('status', ['submitted', 'under_review'])
            ->exists();

        if ($hasPending) {
            return back()->withErrors(['email' => 'You already have a pending application for this organisation.']);
        }

        $application = MembershipApplication::create([
            'id'               => (string) Str::uuid(),
            'organisation_id'  => $organisation->id,
            'user_id'          => null,
            'membership_type_id' => null,
            'applicant_email'  => $data['email'],
            'source'           => 'public',
            'status'           => 'submitted',
            'submitted_at'     => now(),
            'expires_at'       => now()->addDays(60),
            'application_data' => $data,
        ]);

        // Confirmation to applicant
        Mail::to($data['email'])->queue(
            new PublicApplicationConfirmationMail($application, $organisation)
        );

        // Notify all org admins and owners
        $this->notifyAdmins($application, $organisation);

        return back()->with('success', 'Your application has been submitted. We will be in touch soon.');
    }

    private function notifyAdmins(MembershipApplication $application, Organisation $organisation): void
    {
        $adminEmails = UserOrganisationRole::where('organisation_id', $organisation->id)
            ->whereIn('role', ['owner', 'admin'])
            ->with('user:id,email,name')
            ->get()
            ->pluck('user.email')
            ->filter()
            ->unique()
            ->values();

        foreach ($adminEmails as $email) {
            Mail::to($email)->queue(
                new PublicApplicationAdminNotificationMail($application, $organisation)
            );
        }
    }
}

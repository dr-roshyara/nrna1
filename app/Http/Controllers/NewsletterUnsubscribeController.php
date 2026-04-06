<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Inertia\Inertia;

class NewsletterUnsubscribeController extends Controller
{
    public function unsubscribe(string $token)
    {
        $member = Member::withoutGlobalScopes()
            ->where('newsletter_unsubscribe_token', $token)
            ->firstOrFail();

        if (! $member->newsletter_unsubscribed_at) {
            $member->update(['newsletter_unsubscribed_at' => now()]);
        }

        return Inertia::render('Newsletter/Unsubscribed');
    }
}

<?php

namespace App\Http\Controllers\Membership;

use App\Exceptions\InvalidNewsletterStateException;
use App\Http\Controllers\Controller;
use App\Models\Organisation;
use App\Models\OrganisationNewsletter;
use App\Models\UserOrganisationRole;
use App\Services\NewsletterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\RateLimiter as Limiter;
use Inertia\Inertia;

class OrganisationNewsletterController extends Controller
{
    public function __construct(
        private readonly NewsletterService $service
    ) {}

    public function index(Request $request, string $slug)
    {
        $org = Organisation::where('slug', $slug)->firstOrFail();
        $this->authorizeAdmin($org, $request->user());

        $newsletters = OrganisationNewsletter::where('organisation_id', $org->id)
            ->latest()
            ->paginate(15);

        return Inertia::render('Organisations/Membership/Newsletter/Index', [
            'organisation' => $org,
            'newsletters'  => $newsletters,
        ]);
    }

    public function create(Request $request, string $slug)
    {
        $org = Organisation::where('slug', $slug)->firstOrFail();
        $this->authorizeAdmin($org, $request->user());

        return Inertia::render('Organisations/Membership/Newsletter/Create', [
            'organisation' => $org,
        ]);
    }

    public function store(Request $request, string $slug)
    {
        $org = Organisation::where('slug', $slug)->firstOrFail();
        $this->authorizeAdmin($org, $request->user());

        $key = 'newsletters:' . $request->user()->id;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return back()->withErrors(['rate' => 'Too many newsletters created. Please wait.']);
        }
        RateLimiter::hit($key, 3600);

        $data = $request->validate([
            'subject'      => ['required', 'string', 'max:255'],
            'html_content' => ['required', 'string'],
        ]);

        $newsletter = $this->service->createDraft($org, $request->user(), $data, $request);

        return redirect()->route('organisations.membership.newsletters.show', [$slug, $newsletter->id]);
    }

    public function show(Request $request, string $slug, int $id)
    {
        $org = Organisation::where('slug', $slug)->firstOrFail();
        $this->authorizeAdmin($org, $request->user());

        $newsletter = OrganisationNewsletter::where('organisation_id', $org->id)
            ->with(['auditLogs'])
            ->findOrFail($id);

        $recipients = $newsletter->recipients()->paginate(20);

        return Inertia::render('Organisations/Membership/Newsletter/Show', [
            'organisation' => $org,
            'newsletter'   => $newsletter,
            'recipients'   => $recipients,
        ]);
    }

    public function previewRecipients(Request $request, string $slug, int $id)
    {
        $org = Organisation::where('slug', $slug)->firstOrFail();
        $this->authorizeAdmin($org, $request->user());

        $newsletter = OrganisationNewsletter::where('organisation_id', $org->id)->findOrFail($id);

        return response()->json([
            'count' => $this->service->previewRecipientCount($newsletter),
        ]);
    }

    public function send(Request $request, string $slug, int $id)
    {
        $org = Organisation::where('slug', $slug)->firstOrFail();
        $this->authorizeAdmin($org, $request->user());

        $newsletter = OrganisationNewsletter::where('organisation_id', $org->id)->findOrFail($id);

        try {
            $this->service->dispatch($newsletter, $org, $request->user(), $request);
        } catch (InvalidNewsletterStateException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return redirect()->route('organisations.membership.newsletters.show', [$slug, $newsletter->id])
            ->with('success', 'Newsletter has been queued for delivery.');
    }

    public function cancel(Request $request, string $slug, int $id)
    {
        $org = Organisation::where('slug', $slug)->firstOrFail();
        $this->authorizeAdmin($org, $request->user());

        $newsletter = OrganisationNewsletter::where('organisation_id', $org->id)->findOrFail($id);

        try {
            $this->service->cancel($newsletter, $request->user(), $request);
        } catch (InvalidNewsletterStateException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return redirect()->route('organisations.membership.newsletters.show', [$slug, $newsletter->id])
            ->with('success', 'Newsletter cancelled.');
    }

    public function destroy(Request $request, string $slug, int $id)
    {
        $org = Organisation::where('slug', $slug)->firstOrFail();
        $this->authorizeAdmin($org, $request->user());

        $newsletter = OrganisationNewsletter::where('organisation_id', $org->id)
            ->where('status', 'draft')
            ->findOrFail($id);

        $newsletter->delete();

        return redirect()->route('organisations.membership.newsletters.index', $slug)
            ->with('success', 'Newsletter deleted.');
    }

    private function authorizeAdmin(Organisation $org, $user): void
    {
        $role = UserOrganisationRole::where('organisation_id', $org->id)
            ->where('user_id', $user->id)
            ->value('role');

        if (! in_array($role, ['admin', 'owner'])) {
            abort(403);
        }
    }
}

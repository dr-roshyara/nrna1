<?php

namespace App\Http\Controllers\Membership;

use App\Exceptions\InvalidNewsletterStateException;
use App\Http\Controllers\Controller;
use App\Models\NewsletterAttachment;
use App\Models\Organisation;
use App\Models\OrganisationNewsletter;
use App\Models\UserOrganisationRole;
use App\Services\NewsletterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\RateLimiter as Limiter;
use Illuminate\Support\Facades\Storage;
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
            ->with(['auditLogs', 'attachments'])
            ->findOrFail($id);

        $recipients = $newsletter->recipients()->paginate(20);

        return Inertia::render('Organisations/Membership/Newsletter/Show', [
            'organisation' => $org,
            'newsletter'   => $newsletter,
            'recipients'   => $recipients,
        ]);
    }

    public function edit(Request $request, string $slug, int $id)
    {
        $org = Organisation::where('slug', $slug)->firstOrFail();
        $this->authorizeAdmin($org, $request->user());

        $newsletter = OrganisationNewsletter::where('organisation_id', $org->id)
            ->where('status', 'draft')
            ->with('attachments')
            ->findOrFail($id);

        return Inertia::render('Organisations/Membership/Newsletter/Edit', [
            'organisation' => $org,
            'newsletter'   => $newsletter,
        ]);
    }

    public function update(Request $request, string $slug, int $id)
    {
        $org = Organisation::where('slug', $slug)->firstOrFail();
        $this->authorizeAdmin($org, $request->user());

        $newsletter = OrganisationNewsletter::where('organisation_id', $org->id)
            ->where('status', 'draft')
            ->findOrFail($id);

        $data = $request->validate([
            'subject'      => ['required', 'string', 'max:255'],
            'html_content' => ['required', 'string'],
            'plain_text'   => ['nullable', 'string'],
        ]);

        $newsletter->update([
            'subject'      => $data['subject'],
            'html_content' => $this->service->sanitiseHtml($data['html_content']),
            'plain_text'   => $data['plain_text'] ?? null,
        ]);

        return redirect()->route('organisations.membership.newsletters.show', [$slug, $newsletter->id])
            ->with('success', 'Draft updated.');
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

    public function storeAttachment(Request $request, string $slug, int $id)
    {
        $org = Organisation::where('slug', $slug)->firstOrFail();
        $this->authorizeAdmin($org, $request->user());

        $newsletter = OrganisationNewsletter::where('organisation_id', $org->id)->findOrFail($id);

        if ($newsletter->status !== 'draft') {
            return response()->json(['error' => 'Attachments can only be added to draft newsletters.'], 422);
        }

        if ($newsletter->attachments()->count() >= 3) {
            return response()->json(['error' => 'Maximum 3 attachments per newsletter.'], 422);
        }

        $request->validate([
            'attachment' => [
                'required',
                'file',
                'max:10240',
                'mimetypes:application/pdf,'
                    . 'image/jpeg,image/png,image/gif,image/webp,'
                    . 'application/msword,'
                    . 'application/vnd.openxmlformats-officedocument.wordprocessingml.document,'
                    . 'application/vnd.ms-excel,'
                    . 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ],
        ]);

        $file = $request->file('attachment');
        $path = $file->store("newsletters/{$newsletter->id}", 'private');

        $attachment = NewsletterAttachment::create([
            'organisation_newsletter_id' => $newsletter->id,
            'original_name'              => $file->getClientOriginalName(),
            'stored_path'                => $path,
            'mime_type'                  => $file->getMimeType(),
            'size'                       => $file->getSize(),
            'uploaded_by'                => $request->user()->id,
        ]);

        return response()->json($attachment->only(['id', 'original_name', 'mime_type', 'size']));
    }

    public function destroyAttachment(Request $request, string $slug, int $id, int $attachmentId)
    {
        $org = Organisation::where('slug', $slug)->firstOrFail();
        $this->authorizeAdmin($org, $request->user());

        $attachment = NewsletterAttachment::whereHas('newsletter', function ($q) use ($org) {
            $q->where('organisation_id', $org->id);
        })->where('organisation_newsletter_id', $id)->findOrFail($attachmentId);

        Storage::disk('private')->delete($attachment->stored_path);
        $attachment->delete();

        return back()->with('success', 'Attachment removed.');
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

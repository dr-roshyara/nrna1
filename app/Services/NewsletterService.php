<?php

namespace App\Services;

use App\Exceptions\InvalidNewsletterStateException;
use App\Jobs\DispatchNewsletterBatchJob;
use App\Models\Member;
use App\Models\NewsletterAuditLog;
use App\Models\NewsletterRecipient;
use App\Models\Organisation;
use App\Models\OrganisationNewsletter;
use App\Models\User;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\ElectionOfficer;
use App\Models\OrganisationParticipant;
use App\Models\UserOrganisationRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class NewsletterService
{
    private const ALLOWED_TAGS = '<p><br><strong><em><ul><ol><li><a><h1><h2><h3><img>';

    public const AUDIENCE_TYPES = [
        'all_members',
        'members_full',
        'members_associate',
        'members_overdue',
        'election_voters',
        'election_not_voted',
        'election_voted',
        'election_candidates',
        'election_observers',
        'election_committee',
        'election_all',
        'org_participants_staff',
        'org_participants_guests',
        'org_admins',
    ];

    private const CACHE_TTL = 300;
    private const BATCH_SIZE = 500;

    public function sanitiseHtml(string $html): string
    {
        return strip_tags($html, self::ALLOWED_TAGS);
    }

    public function createDraft(
        Organisation $org,
        User $creator,
        array $data,
        Request $request
    ): OrganisationNewsletter {
        $html = strip_tags($data['html_content'], self::ALLOWED_TAGS);

        $newsletter = OrganisationNewsletter::create([
            'organisation_id' => $org->id,
            'created_by'      => $creator->id,
            'subject'         => $data['subject'],
            'html_content'    => $html,
            'plain_text'      => $data['plain_text'] ?? null,
            'status'          => 'draft',
            'audience_type'   => $data['audience_type'] ?? 'all_members',
            'audience_meta'   => $data['audience_meta'] ?? null,
        ]);

        NewsletterAuditLog::create([
            'organisation_newsletter_id' => $newsletter->id,
            'organisation_id'            => $org->id,
            'actor_user_id'              => $creator->id,
            'action'                     => 'created',
            'metadata'                   => ['subject' => $data['subject']],
            'ip_address'                 => $request->ip(),
        ]);

        return $newsletter;
    }

    public function previewRecipientCount(OrganisationNewsletter $newsletter): int
    {
        $org = $newsletter->organisation;
        $audience = $this->resolveAudience(
            $org,
            $newsletter->audience_type,
            $newsletter->audience_meta ?? []
        );
        return $audience->count();
    }

    public function resolveAudience(
        Organisation $organisation,
        string $type,
        array $meta = [],
        bool $forceRefresh = false
    ): Collection {
        $cacheKey = "audience:{$organisation->id}:{$type}:" . md5(json_encode($meta));

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($organisation, $type, $meta) {
            return $this->buildAudienceQuery($organisation, $type, $meta)->get();
        });
    }

    public function buildAudienceQuery(
        Organisation $organisation,
        string $type,
        array $meta = []
    ): Builder {
        return match ($type) {
            'all_members' => $this->queryAllMembers($organisation),
            'members_full' => $this->queryFullMembers($organisation),
            'members_associate' => $this->queryAssociateMembers($organisation),
            'members_overdue' => $this->queryMembersWithOverdueFees($organisation),
            'election_voters' => $this->queryElectionVoters($organisation, $meta),
            'election_not_voted' => $this->queryElectionNotVoted($organisation, $meta),
            'election_voted' => $this->queryElectionVoted($organisation, $meta),
            'election_candidates' => $this->queryElectionCandidates($organisation, $meta),
            'election_observers' => $this->queryElectionObservers($organisation, $meta),
            'election_committee' => $this->queryElectionCommittee($organisation, $meta),
            'election_all' => $this->queryElectionAll($organisation, $meta),
            'org_participants_staff' => $this->queryOrgParticipantsStaff($organisation),
            'org_participants_guests' => $this->queryOrgParticipantsGuests($organisation),
            'org_admins' => $this->queryOrgAdmins($organisation),
            default => $this->queryAllMembers($organisation),
        };
    }

    private function queryAllMembers(Organisation $organisation): Builder
    {
        return Member::withoutGlobalScopes()
            ->join('users', 'members.user_id', '=', 'users.id')
            ->where('members.organisation_id', $organisation->id)
            ->where('members.status', 'active')
            ->whereNull('members.newsletter_unsubscribed_at')
            ->whereNull('members.newsletter_bounced_at')
            ->selectRaw('DISTINCT users.email, users.name, members.id as member_id, users.id as user_id, ? as consent_source', ['member_agreement']);
    }

    private function queryFullMembers(Organisation $organisation): Builder
    {
        return Member::withoutGlobalScopes()
            ->join('users', 'members.user_id', '=', 'users.id')
            ->where('members.organisation_id', $organisation->id)
            ->where('members.status', 'active')
            ->whereNull('members.newsletter_unsubscribed_at')
            ->whereNull('members.newsletter_bounced_at')
            ->whereHas('membershipType', fn($q) => $q->where('grants_voting_rights', true))
            ->selectRaw('DISTINCT users.email, users.name, members.id as member_id, users.id as user_id, ? as consent_source', ['member_agreement']);
    }

    private function queryAssociateMembers(Organisation $organisation): Builder
    {
        return Member::withoutGlobalScopes()
            ->join('users', 'members.user_id', '=', 'users.id')
            ->where('members.organisation_id', $organisation->id)
            ->where('members.status', 'active')
            ->whereNull('members.newsletter_unsubscribed_at')
            ->whereNull('members.newsletter_bounced_at')
            ->whereHas('membershipType', fn($q) => $q->where('grants_voting_rights', false))
            ->selectRaw('DISTINCT users.email, users.name, members.id as member_id, users.id as user_id, ? as consent_source', ['member_agreement']);
    }

    private function queryMembersWithOverdueFees(Organisation $organisation): Builder
    {
        return Member::withoutGlobalScopes()
            ->join('users', 'members.user_id', '=', 'users.id')
            ->where('members.organisation_id', $organisation->id)
            ->where('members.status', 'active')
            ->whereNull('members.newsletter_unsubscribed_at')
            ->whereNull('members.newsletter_bounced_at')
            ->whereIn('members.fees_status', ['unpaid', 'partial'])
            ->selectRaw('DISTINCT users.email, users.name, members.id as member_id, users.id as user_id, ? as consent_source', ['member_agreement']);
    }

    private function queryElectionVoters(Organisation $organisation, array $meta): Builder
    {
        $electionId = $meta['election_id'] ?? null;

        return ElectionMembership::withoutGlobalScopes()
            ->where('election_id', $electionId)
            ->where('role', 'voter')
            ->where('status', 'active')
            ->join('users', 'election_memberships.user_id', '=', 'users.id')
            ->leftJoin('members', 'users.id', '=', 'members.user_id')
            ->where('users.organisation_id', $organisation->id)
            ->whereNull('users.newsletter_unsubscribed_at')
            ->whereNull('users.newsletter_bounced_at')
            ->selectRaw('DISTINCT users.email, users.name, COALESCE(members.id, NULL) as member_id, users.id as user_id, ? as consent_source', ['election_participation']);
    }

    private function queryElectionNotVoted(Organisation $organisation, array $meta): Builder
    {
        $electionId = $meta['election_id'] ?? null;

        return ElectionMembership::withoutGlobalScopes()
            ->where('election_id', $electionId)
            ->where('role', 'voter')
            ->where('has_voted', false)
            ->where('status', 'active')
            ->join('users', 'election_memberships.user_id', '=', 'users.id')
            ->leftJoin('members', 'users.id', '=', 'members.user_id')
            ->where('users.organisation_id', $organisation->id)
            ->whereNull('users.newsletter_unsubscribed_at')
            ->whereNull('users.newsletter_bounced_at')
            ->selectRaw('DISTINCT users.email, users.name, COALESCE(members.id, NULL) as member_id, users.id as user_id, ? as consent_source', ['election_participation']);
    }

    private function queryElectionVoted(Organisation $organisation, array $meta): Builder
    {
        $electionId = $meta['election_id'] ?? null;

        return ElectionMembership::withoutGlobalScopes()
            ->where('election_id', $electionId)
            ->where('role', 'voter')
            ->where('has_voted', true)
            ->join('users', 'election_memberships.user_id', '=', 'users.id')
            ->leftJoin('members', 'users.id', '=', 'members.user_id')
            ->where('users.organisation_id', $organisation->id)
            ->whereNull('users.newsletter_unsubscribed_at')
            ->whereNull('users.newsletter_bounced_at')
            ->selectRaw('DISTINCT users.email, users.name, COALESCE(members.id, NULL) as member_id, users.id as user_id, ? as consent_source', ['election_participation']);
    }

    private function queryElectionCandidates(Organisation $organisation, array $meta): Builder
    {
        $electionId = $meta['election_id'] ?? null;

        return ElectionMembership::withoutGlobalScopes()
            ->where('election_id', $electionId)
            ->where('role', 'candidate')
            ->join('users', 'election_memberships.user_id', '=', 'users.id')
            ->leftJoin('members', 'users.id', '=', 'members.user_id')
            ->where('users.organisation_id', $organisation->id)
            ->whereNull('users.newsletter_unsubscribed_at')
            ->whereNull('users.newsletter_bounced_at')
            ->selectRaw('DISTINCT users.email, users.name, COALESCE(members.id, NULL) as member_id, users.id as user_id, ? as consent_source', ['election_participation']);
    }

    private function queryElectionObservers(Organisation $organisation, array $meta): Builder
    {
        $electionId = $meta['election_id'] ?? null;

        return ElectionMembership::withoutGlobalScopes()
            ->where('election_id', $electionId)
            ->where('role', 'observer')
            ->join('users', 'election_memberships.user_id', '=', 'users.id')
            ->leftJoin('members', 'users.id', '=', 'members.user_id')
            ->where('users.organisation_id', $organisation->id)
            ->whereNull('users.newsletter_unsubscribed_at')
            ->whereNull('users.newsletter_bounced_at')
            ->selectRaw('DISTINCT users.email, users.name, COALESCE(members.id, NULL) as member_id, users.id as user_id, ? as consent_source', ['election_participation']);
    }

    private function queryElectionCommittee(Organisation $organisation, array $meta): Builder
    {
        $electionId = $meta['election_id'] ?? null;

        return ElectionOfficer::withoutGlobalScopes()
            ->where('election_officers.election_id', $electionId)
            ->where('election_officers.status', 'active')
            ->join('users', 'election_officers.user_id', '=', 'users.id')
            ->leftJoin('members', 'users.id', '=', 'members.user_id')
            ->where('users.organisation_id', $organisation->id)
            ->whereNull('users.newsletter_unsubscribed_at')
            ->whereNull('users.newsletter_bounced_at')
            ->selectRaw('DISTINCT users.email, users.name, COALESCE(members.id, NULL) as member_id, users.id as user_id, ? as consent_source', ['election_participation']);
    }

    private function queryElectionAll(Organisation $organisation, array $meta): Builder
    {
        $electionId = $meta['election_id'] ?? null;

        $voters = ElectionMembership::withoutGlobalScopes()
            ->where('election_id', $electionId)
            ->whereIn('role', ['voter', 'candidate', 'observer'])
            ->where('election_memberships.status', 'active')
            ->join('users', 'election_memberships.user_id', '=', 'users.id')
            ->leftJoin('members', 'users.id', '=', 'members.user_id')
            ->where('users.organisation_id', $organisation->id)
            ->whereNull('users.newsletter_unsubscribed_at')
            ->whereNull('users.newsletter_bounced_at')
            ->selectRaw('DISTINCT users.email, users.name, COALESCE(members.id, NULL) as member_id, users.id as user_id, ? as consent_source', ['election_participation']);

        $committee = ElectionOfficer::withoutGlobalScopes()
            ->where('election_id', $electionId)
            ->where('election_officers.status', 'active')
            ->join('users', 'election_officers.user_id', '=', 'users.id')
            ->leftJoin('members', 'users.id', '=', 'members.user_id')
            ->where('users.organisation_id', $organisation->id)
            ->whereNull('users.newsletter_unsubscribed_at')
            ->whereNull('users.newsletter_bounced_at')
            ->selectRaw('DISTINCT users.email, users.name, COALESCE(members.id, NULL) as member_id, users.id as user_id, ? as consent_source', ['election_participation']);

        return $voters->union($committee);
    }

    private function queryOrgParticipantsStaff(Organisation $organisation): Builder
    {
        return OrganisationParticipant::withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->where('participant_type', 'staff')
            ->join('users', 'organisation_participants.user_id', '=', 'users.id')
            ->leftJoin('members', 'users.id', '=', 'members.user_id')
            ->whereNull('users.newsletter_unsubscribed_at')
            ->whereNull('users.newsletter_bounced_at')
            ->selectRaw('DISTINCT users.email, users.name, COALESCE(members.id, NULL) as member_id, users.id as user_id, ? as consent_source', ['election_participation']);
    }

    private function queryOrgParticipantsGuests(Organisation $organisation): Builder
    {
        return OrganisationParticipant::withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->where('participant_type', 'guest')
            ->join('users', 'organisation_participants.user_id', '=', 'users.id')
            ->leftJoin('members', 'users.id', '=', 'members.user_id')
            ->whereNull('users.newsletter_unsubscribed_at')
            ->whereNull('users.newsletter_bounced_at')
            ->selectRaw('DISTINCT users.email, users.name, COALESCE(members.id, NULL) as member_id, users.id as user_id, ? as consent_source', ['election_participation']);
    }

    private function queryOrgAdmins(Organisation $organisation): Builder
    {
        return UserOrganisationRole::withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->whereIn('role', ['admin', 'owner', 'commission'])
            ->join('users', 'user_organisation_roles.user_id', '=', 'users.id')
            ->leftJoin('members', 'users.id', '=', 'members.user_id')
            ->where('users.organisation_id', $organisation->id)
            ->whereNull('users.newsletter_unsubscribed_at')
            ->whereNull('users.newsletter_bounced_at')
            ->selectRaw('DISTINCT users.email, users.name, COALESCE(members.id, NULL) as member_id, users.id as user_id, ? as consent_source', ['member_agreement']);
    }

    public function dispatch(OrganisationNewsletter $newsletter): void
    {
        if ($newsletter->status !== 'draft') {
            throw new InvalidNewsletterStateException(
                "Newsletter cannot be dispatched from status [{$newsletter->status}]."
            );
        }

        $org = $newsletter->organisation;

        DB::transaction(function () use ($newsletter, $org) {
            // Always bypass cache on actual send - must use fresh data
            $audience = $this->resolveAudience(
                $org,
                $newsletter->audience_type,
                $newsletter->audience_meta ?? [],
                forceRefresh: true
            );

            $recipientRows = $audience->map(function ($recipient) use ($newsletter) {
                return [
                    'id'                         => Str::uuid(),
                    'organisation_newsletter_id' => $newsletter->id,
                    'member_id'                  => $recipient->member_id,
                    'user_id'                    => $recipient->user_id,
                    'email'                      => $recipient->email,
                    'name'                       => $recipient->name,
                    'status'                     => 'pending',
                    'consent_source'             => $recipient->consent_source ?? 'member_agreement',
                    'consent_given_at'           => now(),
                    'idempotency_key'            => hash('sha256', $newsletter->id . ':' . ($recipient->member_id ?? $recipient->user_id)),
                    'created_at'                 => now(),
                    'updated_at'                 => now(),
                ];
            });

            foreach ($recipientRows->chunk(self::BATCH_SIZE) as $chunk) {
                retry(2, fn() => NewsletterRecipient::insert($chunk->toArray()), 100);
            }

            $newsletter->update([
                'status'           => 'queued',
                'idempotency_key'  => hash('sha256', $org->id . ':' . $newsletter->id . ':' . now()->timestamp),
                'queued_at'        => now(),
                'total_recipients' => $recipientRows->count(),
            ]);

            NewsletterAuditLog::create([
                'organisation_newsletter_id' => $newsletter->id,
                'organisation_id'            => $org->id,
                'actor_user_id'              => auth()->id(),
                'action'                     => 'dispatched',
                'metadata'                   => [
                    'recipient_count' => $recipientRows->count(),
                    'audience_type' => $newsletter->audience_type,
                    'audience_meta' => $newsletter->audience_meta,
                ],
                'ip_address'                 => request()->ip(),
            ]);

            DispatchNewsletterBatchJob::dispatch($newsletter->id)->onQueue('emails-normal');
        });
    }

    public function cancel(
        OrganisationNewsletter $newsletter,
        User $actor,
        Request $request
    ): void {
        if (! in_array($newsletter->status, ['draft', 'processing'])) {
            throw new InvalidNewsletterStateException(
                "Newsletter cannot be cancelled from status [{$newsletter->status}]."
            );
        }

        $newsletter->update(['status' => 'cancelled']);

        NewsletterAuditLog::create([
            'organisation_newsletter_id' => $newsletter->id,
            'organisation_id'            => $newsletter->organisation_id,
            'actor_user_id'              => $actor->id,
            'action'                     => 'cancelled',
            'ip_address'                 => $request->ip(),
        ]);
    }
}

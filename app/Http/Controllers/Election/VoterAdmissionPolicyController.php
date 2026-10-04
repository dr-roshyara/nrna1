<?php

namespace App\Http\Controllers\Election;

use App\Contexts\Elections\Application\Commands\ChangeVoterAdmissionPolicyCommand;
use App\Contexts\Elections\Application\Handlers\ChangeVoterAdmissionPolicyHandler;
use App\Contexts\Elections\Domain\Exceptions\AdmissionPolicyLocked;
use App\Domain\Election\Enum\VoterSourceStrategy;
use App\Http\Controllers\Controller;
use App\Http\Requests\Election\UpdateVoterAdmissionPolicyRequest;
use App\Models\Election;
use App\Services\ElectionAuditService;
use Illuminate\Http\RedirectResponse;

/**
 * VoterAdmissionPolicyController — save the election's Voter Admission Policy
 * (PBDIGIT-213 C2, ADR_20261004_1549 D4 / R3-1 / C2-D2).
 *
 * - Real Election-Only elections only (C2-D2); demo elections → 404.
 * - Locked from ReadyForVoting onward (handler + Election model guard).
 * - Every effective change is audited (admission_policy_changed); a no-op is not.
 * - No voter membership is touched: admission is derived from policy + evidence.
 */
class VoterAdmissionPolicyController extends Controller
{
    private const FIELD = 'identity_verification_required';

    public function update(
        UpdateVoterAdmissionPolicyRequest $request,
        Election $election,
        ChangeVoterAdmissionPolicyHandler $handler,
    ): RedirectResponse {
        abort_if($election->type === 'demo', 404);

        if (! self::isAvailableFor($election)) {
            return back()->withErrors([self::FIELD => 'Voter admission requirements are available for Election-Only elections only.']);
        }

        try {
            $change = $handler->handle(new ChangeVoterAdmissionPolicyCommand(
                electionId: $election->id,
                proposed: $request->proposedPolicy(),
                actorId: $request->user()->id,
            ));
        } catch (AdmissionPolicyLocked) {
            return back()->withErrors([self::FIELD => 'The admission policy cannot be changed once the election is ready for voting.']);
        }

        if (! $change->changed) {
            return back();
        }

        app(ElectionAuditService::class)->log(
            election: $election,
            event: 'admission_policy_changed',
            user: $request->user(),
            category: 'committee',
            ip: $request->ip(),
            metadata: [
                'from'            => $change->from->toPersistence(),
                'to'              => $change->to->toPersistence(),
                'affected_voters' => $change->affectedVoters,
            ],
        );

        return back()->with('success', $change->to->isEmpty()
            ? 'Identity verification is no longer required for voters.'
            : 'Identity verification is now required for every voter.');
    }

    /** C2-D2: the admission policy applies to real Election-Only elections. */
    public static function isAvailableFor(Election $election): bool
    {
        return $election->type === 'real'
            && $election->voter_source_strategy !== null
            && VoterSourceStrategy::fromElection($election)->isImportedVoterRegistry();
    }
}

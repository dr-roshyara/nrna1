<?php
// Builds the ratchet baseline from a scan: one entry per individual violation, classified (baseline §7, inventory).
$f = json_decode(file_get_contents($argv[1]), true);
$out = [];
$c = static fn (string $class, string $reason, string $remediation, string $decision = 'none') => compact('class', 'reason', 'remediation', 'decision');
foreach ($f as $key => $count) {
    [$rule, $file, $dep] = explode('|', $key);
    $e = match (true) {
        // ---- A-1 Domain purity
        $rule === 'A-1' && str_contains($file, 'Membership/Domain/Models/Member.php')
            => $c('genuine', 'Eloquent model inside Membership Domain (predates the ratified 2026-10-08 architecture; 16 importers)', 'move to Infrastructure (remediation R-4)', 'Membership change request'),
        $rule === 'A-1' && str_starts_with($file, 'app/Contexts/Membership/') && $dep === 'Illuminate\Support\Str'
            => $c('genuine', 'identifier value object generates UUIDs with Laravel Str', 'pure-PHP UUID (ramsey/uuid, as Committee CommitteeId already does) when touched (R-5)'),
        $rule === 'A-1' && str_starts_with($file, 'app/Contexts/Geography/')
            => $c('genuine (legacy)', 'legacy Geography context: Eloquent models / Laravel cache contract in Domain', 'superseded by Reference Geography read models (G-5); integration deferred (D-147); no change now (R-6)'),
        $rule === 'A-1' && str_starts_with($file, 'app/Contexts/Elections/')
            => $c('genuine', 'domain repository interface typed on an Eloquent model', 'type it on a domain type when Elections is next touched'),
        // ---- A-2 Application layer
        $rule === 'A-2' && str_contains($file, 'ApproveMembershipApplication') && $dep === 'Illuminate\Support\Facades\DB'
            => $c('artefact (unused import)', 'DB imported but unused; its comment says the CONTROLLER owns the transaction (a delivery-owned transaction)', 'delete the import; move the transaction behind a port with R-3'),
        $rule === 'A-2' && str_contains($file, 'Requests/CreateMemberRequest')
            => $c('genuine (placement)', 'an HTTP FormRequest placed in Application, calling the Log facade', 'move to Http when touched'),
        $rule === 'A-2' && str_starts_with($file, 'app/Contexts/Membership/') && in_array($dep, ['DB::', 'Illuminate\Support\Facades\DB'], true)
            => $c('genuine', 'DB::transaction / DB facade in an Application use case', 'application transaction port, implemented in Infrastructure (R-3); PBDIGIT-345 for TenantUserProvisioningService'),
        $rule === 'A-2' && str_starts_with($dep, 'App\Models\\')
            => $c('genuine', 'legacy Eloquent model used in Application', 'a repository or query port when touched'),
        $rule === 'A-2' && str_starts_with($file, 'app/Contexts/Geography/')
            => $c('genuine (legacy)', 'legacy Geography context uses facades in Application', 'superseded by Reference Geography (G-5, D-147); no change now (R-6)'),
        $rule === 'A-2' && (str_contains($dep, 'Log') )
            => $c('genuine (minor)', 'Log facade in Application', 'inject Psr\Log\LoggerInterface when touched'),
        $rule === 'A-2'
            => $c('genuine', 'Laravel facade in Application', 'a port implemented in Infrastructure when touched'),
        // ---- A-3 cross-context
        $rule === 'A-3' && preg_match('#^App\\\\Contexts\\\\(TenantAuth|Subscription)\\\\#', $dep) === 1
            => $c('artefact (dangling)', 'imports a context that does not exist in app/Contexts', 'delete the dead code path when touched'),
        $rule === 'A-3' && str_starts_with($file, 'app/Contexts/ReferenceGeography/')
            => $c('published language (approved)', 'D-225 acting-person adapter; the only Identity import, guarded by IdentityG0ArchitectureTest', 'none'),
        $rule === 'A-3' && $dep === 'App\Contexts\Membership\Domain\Member\MemberId'
            => $c('published language (proposed)', "Membership's member identifier", 'none; add to the published-language list once accepted'),
        $rule === 'A-3' && str_contains($dep, 'Membership\Domain\Services\GeographyLookupInterface')
            => $c('legitimate (consumer-owned port)', 'Geography implements a port that Membership defines: correct hexagonal direction', 'none; superseded with legacy Geography (R-6)'),
        $rule === 'A-3' && str_contains($dep, '\Infrastructure\\')
            => $c('persistence or infrastructure leakage', "another context's Infrastructure (Eloquent model or service)", 'a query port owned by the providing context (R-2)'),
        $rule === 'A-3' && (preg_match('#Membership\\\\Domain\\\\(Committee\\\\|ValueObjects\\\\(CommitteeId|ConstitutionalLegitimacy|StructuralOperationalState|TemporalGovernanceState))#', $dep) === 1)
            => $c('misplaced ownership', 'a committee (organ) concept Governance owns (strategic baseline rev3) but stored under Membership', 'new organ code goes to Governance; relocation is its own ticket (R-2)', 'Membership change request for the relocation'),
        $rule === 'A-3' && str_contains($dep, 'Contexts\Geography\\')
            => $c('genuine (legacy)', 'Membership couples to the legacy Geography context', 'replace with Reference Geography read models (G-5; D-147)'),
        $rule === 'A-3' && str_contains($dep, 'Contexts\Platform\\')
            => $c('unclear ownership', 'consumes a Platform service/port (module installation); no published contract recorded', 'name it as a Platform published contract, or route through a port'),
        $rule === 'A-3' && str_contains($dep, 'Contexts\Committee\\')
            => $c('misplaced ownership', 'the Committee read-model module (committee = Governance organ, rev3)', 'with the committee relocation (R-2)'),
        $rule === 'A-3'
            => $c('unclear ownership', 'cross-context Domain/Application import without a recorded contract', 'classify during R-2'),
        // ---- A-4 delivery
        $rule === 'A-4' && str_contains($dep, 'Contexts\Shared\Infrastructure')
            => $c('acceptable exception', 'console driver for the shared outbox/inbox (technical infrastructure, not a context)', 'none'),
        $rule === 'A-4' && str_starts_with($file, 'app/Console/Commands/')
            => $c('acceptable exception', 'operational maintenance command (backfill or projection rebuild), not a user path', 'none; re-check if it becomes a recurring operation'),
        $rule === 'A-4' && str_starts_with($file, 'routes/')
            => $c('placement', 'a delivery class (job or controller) placed in a context Infrastructure folder; the route only references it', 'move the class to the delivery layer when touched'),
        $rule === 'A-4' && str_contains($dep, 'EloquentVotingEntitlementQuery')
            => $c('genuine (minor)', 'controller depends on the concrete adapter while its port VotingEntitlementQuery exists', 'type-hint the port'),
        $rule === 'A-4'
            => $c('genuine', "controller reads through another context's persistence model", 'a query port of the owning context when touched'),
        // ---- A-5 organisation connection
        $rule === 'A-5' && str_starts_with($file, 'app/Contexts/Geography/')
            => $c('genuine (legacy)', "legacy Geography mirrors geography into the shared 'tenant' connection: contrary to D-222 and to 'never copy Reference Geography into organisation databases'", 'retire with G-5 and the D-147 integration decision (R-6)'),
        $rule === 'A-5'
            => $c('genuine', "shared, re-pointed 'tenant' connection, contrary to D-222", 'per-organisation connection in PBDIGIT-345 (R-1); no workaround'),
    };
    $out[$key] = ['count' => $count] + $e;
}
file_put_contents($argv[2], json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n");
$t = [];
foreach ($out as $k => $e) { $t[substr($k, 0, 3)][$e['class']] = ($t[substr($k, 0, 3)][$e['class']] ?? 0) + 1; }
foreach ($t as $r => $cl) { echo "$r: " . array_sum($cl) . "  "; foreach ($cl as $k => $n) echo "[$k: $n] "; echo "\n"; }

# Release-1 blueprint: B-05 repository evidence (reproducible)

| | |
|---|---|
| **Purpose** | raw search output behind blueprint §6 G-1c, G-1d and G-3 (IR-R1C-01 B-05; IR-R1C-01-R1 §8.5). **Evidence only; decides nothing** |
| **Repository state** | branch `election-flow-updated`, HEAD `a53ba8ea8`; captured 2026-11-12T11:30Z. Uncommitted changes under `app/ database/ tests/ routes/ config/ bootstrap/` at capture: **2 path(s)** |
| **Tool** | `git grep` over **tracked files at HEAD plus working tree** (each command below), and `git status --untracked-files=all` for untracked files (last section) |

## G-1c: no ledger-clock state

```
$ git grep -nE "closed_through|last_recorded_at|ledger_clock|closedThrough" -- app database tests routes config bootstrap
(exit 1; 1 = no match)

$ ls database/migrations | grep -iE "membership|org_membership"
2026_03_17_213212_create_election_memberships_table.php
2026_03_17_220000_add_expires_at_index_to_election_memberships_table.php
2026_03_22_000001_add_voted_fields_to_election_memberships.php
2026_03_22_213421_add_suspension_proposal_columns_to_election_memberships.php
2026_04_03_155706_create_membership_types_table.php
2026_04_03_155707_create_membership_fees_table.php
2026_04_03_155708_create_membership_renewals_table.php
2026_04_03_155710_create_membership_applications_table.php
2026_04_04_113506_add_membership_dashboard_indexes.php
2026_04_05_000002_add_grants_voting_rights_to_membership_types_table.php
2026_04_05_000004_add_membership_type_id_to_members_table.php
2026_04_06_200002_make_public_fields_nullable_on_membership_applications.php
2026_04_15_120000_add_uses_full_membership_to_organisations.php
2026_04_26_add_committee_role_to_election_memberships.php
2026_05_04_000003_add_amount_currency_to_membership_fees.php
2026_05_19_000001_harden_election_memberships_for_election_only_mode.php
2026_05_19_215036_fix_election_memberships_unique_index_mysql_compat.php
2026_05_20_085627_fix_election_memberships_unique_index_mysql_compat.php
2026_10_09_100000_create_org_membership_tables.php
2026_11_02_180000_normalise_org_membership_commands.php
2026_11_03_120000_extend_org_membership_facts_for_corrections.php

$ git grep -n "Schema::create(.org_membership" -- database
database/migrations/2026_10_09_100000_create_org_membership_tables.php:25:        Schema::create('org_memberships', function (Blueprint $table) {
database/migrations/2026_10_09_100000_create_org_membership_tables.php:38:        Schema::create('org_membership_facts', function (Blueprint $table) {
database/migrations/2026_10_09_100000_create_org_membership_tables.php:74:        Schema::create('org_membership_commands', function (Blueprint $table) {
(exit 0)
```

## G-3: InterimRoleAuthority and AuthorityCheckPort bindings

```
$ git grep -n "InterimRoleAuthority" -- app bootstrap config routes tests
app/Contexts/Membership/Infrastructure/Organisational/InterimRoleAuthority.php:24:final class InterimRoleAuthority implements AuthorityCheckPort
tests/Unit/Contexts/Membership/Infrastructure/Organisational/InterimRoleAuthorityTest.php:8:use App\Contexts\Membership\Infrastructure\Organisational\InterimRoleAuthority;
tests/Unit/Contexts/Membership/Infrastructure/Organisational/InterimRoleAuthorityTest.php:19:final class InterimRoleAuthorityTest extends TestCase
tests/Unit/Contexts/Membership/Infrastructure/Organisational/InterimRoleAuthorityTest.php:21:    private static function authority(array $roles): InterimRoleAuthority
tests/Unit/Contexts/Membership/Infrastructure/Organisational/InterimRoleAuthorityTest.php:35:        return new InterimRoleAuthority($reader, static fn (): int => 1_760_000_000_000_000);

$ git grep -nE "AuthorityCheckPort" -- app bootstrap config routes
app/Contexts/Membership/Application/Organisational/AdmitMemberHandler.php:7:use App\Contexts\Membership\Application\Organisational\Ports\AuthorityCheckPort;
app/Contexts/Membership/Application/Organisational/AdmitMemberHandler.php:29:    public function __construct(private readonly MemberRepositoryPort $repository, AuthorityCheckPort $authority)
app/Contexts/Membership/Application/Organisational/DecideAndAppend.php:8:use App\Contexts\Membership\Application\Organisational\Ports\AuthorityCheckPort;
app/Contexts/Membership/Application/Organisational/DecideAndAppend.php:31:        private readonly AuthorityCheckPort $authority,
app/Contexts/Membership/Application/Organisational/EndMembershipHandler.php:7:use App\Contexts\Membership\Application\Organisational\Ports\AuthorityCheckPort;
app/Contexts/Membership/Application/Organisational/EndMembershipHandler.php:28:    public function __construct(private readonly MemberRepositoryPort $repository, AuthorityCheckPort $authority)
app/Contexts/Membership/Application/Organisational/Ports/AuthorityCheckPort.php:15:interface AuthorityCheckPort
app/Contexts/Membership/Infrastructure/Organisational/InterimRoleAuthority.php:7:use App\Contexts\Membership\Application\Organisational\Ports\AuthorityCheckPort;
app/Contexts/Membership/Infrastructure/Organisational/InterimRoleAuthority.php:24:final class InterimRoleAuthority implements AuthorityCheckPort

$ git grep -nE "->(bind|singleton|scoped)\(" -- app/Providers bootstrap app/Contexts/Membership | grep -iE "Authority" ; echo "(exit of grep)"
(exit 1; 1 = no binding mentioning Authority)
```

## G-1d: what the retry loop handles today

```
$ git grep -n "case " -- app/Contexts/Membership/Application/Organisational/Ports/AppendOutcome.php
app/Contexts/Membership/Application/Organisational/Ports/AppendOutcome.php:10:    case Appended = 'appended';
app/Contexts/Membership/Application/Organisational/Ports/AppendOutcome.php:12:    case ConcurrencyConflict = 'concurrency_conflict';
app/Contexts/Membership/Application/Organisational/Ports/AppendOutcome.php:14:    case AlreadyApplied = 'already_applied';
app/Contexts/Membership/Application/Organisational/Ports/AppendOutcome.php:16:    case IdempotencyKeyConflict = 'idempotency_key_conflict';
app/Contexts/Membership/Application/Organisational/Ports/AppendOutcome.php:17:    case MemberNotFound = 'member_not_found';
app/Contexts/Membership/Application/Organisational/Ports/AppendOutcome.php:19:    case ValidTimeNotStorable = 'valid_time_not_storable';

$ git grep -nE "AppendOutcome::|MAX_ATTEMPTS|for \(\$attempt" -- app/Contexts/Membership/Application/Organisational/DecideAndAppend.php
app/Contexts/Membership/Application/Organisational/DecideAndAppend.php:21: *   repeat ≤ MAX_ATTEMPTS:  load → authorise → decide (guards + G*) → append(expected version)
app/Contexts/Membership/Application/Organisational/DecideAndAppend.php:27:    public const MAX_ATTEMPTS = 3;
app/Contexts/Membership/Application/Organisational/DecideAndAppend.php:46:        for ($attempt = 1; $attempt <= self::MAX_ATTEMPTS; $attempt++) {
app/Contexts/Membership/Application/Organisational/DecideAndAppend.php:66:                AppendOutcome::Appended, AppendOutcome::AlreadyApplied => CommandOutcome::ofAppended($result->appended()),
app/Contexts/Membership/Application/Organisational/DecideAndAppend.php:67:                AppendOutcome::IdempotencyKeyConflict => CommandOutcome::refused(ApplicationRefusal::IdempotencyKeyConflict),
app/Contexts/Membership/Application/Organisational/DecideAndAppend.php:68:                AppendOutcome::MemberNotFound => CommandOutcome::refused(ApplicationRefusal::MemberNotFound),
app/Contexts/Membership/Application/Organisational/DecideAndAppend.php:69:                AppendOutcome::ValidTimeNotStorable => CommandOutcome::refused(ApplicationRefusal::ValidTimeNotStorable),
app/Contexts/Membership/Application/Organisational/DecideAndAppend.php:70:                AppendOutcome::ConcurrencyConflict => null,
```

**Reading (for G-1d):** the loop is bounded (`MAX_ATTEMPTS = 3`) and retries **only `ConcurrencyConflict`**. **There is no clock or boundary refusal outcome today;** one must be added and mapped into the loop (blueprint rev 4 §6 G-1d).

## Untracked files in these folders (not covered by git grep)

```
?? tests/Isolated/Pbdigit274/Database/VoteAnonymityLinkageTest.php
(end)
```

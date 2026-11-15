# 25: Renew, and the rule that every admission states its term (lifecycle S2-1), PBDIGIT-354

**Decisions:** D-163 (Renew in release 1; no platform default term) · accepted design D-177 §2–§5, §8, §10, §12 · D-205 (3) (nothing future-dated) · D-236 (S2 plan; "standing" = the C1 / C2 intervals) · blueprint G-3, G-4 (D-216).
**Plan:** `docs/plans/20261115-0510-release-1-build-slice-s2-membership-lifecycle-plan.md`, step S2-1.
**Evidence:** `docs/publicdigit/membership/evidence/2026-11-15-pbdigit354-s2-1/`.

## Business purpose

- **Renewal.** An organisation can extend a member's term. The member is **in force** for the union of all granted terms, inside the membership period. A period whose term ran out is **lapsed, not ended**: it can be renewed late (the gap stays), or with an earlier effective date (the gap closes). After a formal End a renewal is refused; that needs a new admission.
- **The admission-term rule.** Every admission through the release-1 command states its term: an end date, or open-ended. There is no default. This stops records in which nobody decided how long the membership runs.

## Where it fits

| Layer | Class | Role |
|---|---|---|
| Domain (pure PHP) | `Domain/Organisational/Renew` | the fact: period, `effectiveAt`, `until` (finite or ∞) |
| | `Member::decideRenew` | the decision and its guards |
| | `Resolution::term` | `termSegs` = the Admit's initial term ∪ every active Renew, clipped to the lifetime |
| Application | `RenewMembership`, `RenewMembershipHandler` | the use case (pull shape: an office holder's act) |
| | `AdmitMemberHandler` | the admission-term rule, at the command boundary |
| | `Ports/MembershipAct::Renew` | the act the authority is asked for (`renew-membership`) |
| | `Ports/StorableValidTime` | both Renew dates must fit the database's date range |
| Infrastructure | `PostgresMemberRepository` | encode / decode the `renew` row |
| | migrations `2026_11_15_340000_…` (central) and `OrganisationMigrations/2026_11_15_300500_…` | the `renew` kind |

## Design decisions

1. **The caller never names the period.** `decideRenew` takes the period containing `effectiveAt`: the latest-started period that has started by then. This is D-177 §4 ("the period containing `effectiveAt`"); a wrong or stale period id cannot be supplied.
2. **Refusals, in order** (`Member::decideRenew`): `DuplicateFactId` → `EmptySegment` (`until` ≤ `effectiveAt`) → `NoPeriodAt` (no period has started) → `PeriodEnded` (formally ended at `effectiveAt`; half-open, so the end instant itself is ended) → `EffectiveDateAfterDecisionDate`.
3. **Nothing is future-dated (D-205 (3)).** A renewal cannot take effect after its decision date. **To renew ahead of time, record a renewal effective on the decision date with a later `until`:** segments are a set, so the overlap with the current term is harmless and `inForce` is continuous (D-177 row 1, realised under D-205).
4. **`PeriodEnded` is not `PeriodAlreadyEnded`.** The first refuses a renewal in an ended period; the second (older) refuses a second End.
5. **The admission-term rule lives at the command boundary, not in the kernel.** D-177 §3: "the kernel keeps 'absent', so history is faithful". `Member::decideAdmit` still accepts no term (attested imports, and the kernel's own tests); `AdmitMemberHandler` refuses it with `AdmissionTermRequired`.
   - **No Member record is created for that refusal:** the decision runs on the existing Member or a transient one that is never stored. (Found by the reference model: a leftover empty record would turn a later `member_not_found` into `period_not_found`.)
6. **A renewal states its term too.** `RenewMembership` has `until` and `unlimited`; neither → `RenewalTermRequired`. A missing end date is **never** read as open-ended.
7. **`Renew` is not an `EffectiveDatedFact`.** That interface marks the dates a `Correct` may replace. **Correcting a Renew is refused** (`CorrectionOfRenewNotSupported`) **until Q-2 is decided** (see "Open").
8. **Authority.** The handler asks `AuthorityCheckPort` for `MembershipAct::Renew` on every attempt and records the evidence on the fact. **No production `AuthorityCheckPort` exists until Governance S3 (G-3):** the container cannot build `RenewMembershipHandler`, so renewal is refused in production by construction (`MembershipThroughRoutingTest::test_s2_1_…` asserts this).

## How it works

```php
$outcome = (new RenewMembershipHandler($repository, $authority))->handle(new RenewMembership(
    tenantId: $organisationId, memberId: 'M1', factId: $newFactId,
    effectiveAt: $day, decisionDate: $day,       // day ordinals (days since 1970-01-01)
    until: $untilDay, unlimited: false,           // exactly one of the two states the term
    actor: $actorRef, decidedAt: null, idempotencyKey: $key,
));
$outcome->isAppended();          // or ->domainRefusal() / ->applicationRefusal()
```

- **Resolution.** `Resolution::term` unions the Admit's initial segment with every **active** Renew of that period and intersects with the lifetime. An annulled or voided Renew is not live, so its segment disappears from its record time on (row 17); `knownAt(τ)` before the record time does not see it (row 22).
- **A renewal belongs to one period only.** A term renewed far ahead does not carry into a later period after an End and a new admission.
- **C1.** Nothing new: `PostgresMemberRepository::append` writes `MembershipCoverageResolved` in the same transaction whenever coverage, `inForce` or `suspended` changed. A Renew changes `inForce`, never coverage. A renewal wholly inside an existing term is recorded but publishes nothing.

## Storage

`org_membership_facts`, kind `renew`:

| Column | Renew |
|---|---|
| `effective_at` | the segment's start |
| `target_fact_id` | the period (its Admit), as for an End |
| `renew_until_kind` | `until` or `unlimited` |
| `renew_until` | the date, or NULL when unlimited |

Two CHECKs: the `kind` CHECK's new branch, and `org_membership_facts_renew_check` (the two columns are set exactly on renew rows; kind and date agree; the segment is not empty).

**Parity (guide 23):** the central and the organisation migration carry the same SQL; `OrganisationMigrationsTest` compares the two schemas.

## Testing

| Suite | What |
|---|---|
| `tests/Unit/Contexts/Membership/Domain/Organisational/RenewTest.php` (22) | D-177 rows 1–7, 17, 21–23; the guards; one period only; published intervals clipped |
| `tests/Unit/Contexts/Membership/Application/Organisational/RenewMembershipHandlerTest.php` (12) | the use case; authority asked for `renew`; idempotency; the admission-term rule; storable dates |
| `…/ReferenceModelPropertyTest.php` | the independent model now refuses a term-less admission too |
| `tests/Feature/Contexts/Membership/Organisational/PostgresRenewTest.php` (12) | round trip, the stored row, C1, the CHECKs |
| `tests/Feature/Contexts/Platform/MembershipThroughRoutingTest.php::test_s2_1_…` | in an organisation's own database through the routed repository; fail-closed wiring |

```
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Membership/Domain/Organisational/RenewTest.php tests/Unit/Contexts/Membership/Application/Organisational
MEMBERSHIP_PG_IMAGE=<pinned> MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite MembershipPostgres --filter PostgresRenewTest
```

## Pitfalls

- **A CHECK that evaluates to NULL is satisfied.** `renew_until_kind = 'until'` is NULL, not false, when the column is NULL. The renew CHECK therefore says `renew_until_kind IS NOT NULL` explicitly. The first version accepted a renew row with no term; `PostgresRenewTest` "no term stated" caught it.
- **Do not order outbox rows by `created_at`.** It has second precision and the id is random. Order C1 events by their own `seq`.
- **A new central ledger migration must be added, by exact name, to `MembershipLedgerIsolationTest::ALLOWLIST`** (it names a ledger table outside the Membership folder). S2-1 added `2026_11_15_340000_add_renew_to_org_membership_facts.php`.
- **A new fact kind with dates must be handled in `StorableValidTime`** (`MembershipStorableValidTimeCoverageTest` fails otherwise).
- **Test fixtures record the admission at instant 1001** (`MemberHistory`); an explicit earlier record time fails for the wrong reason.

## Open

- **Q-2, decided 2026-11-15 as D-237 (Option A); implemented in S2-5. Correcting a Renew's `until`** (D-177 row 20). The accepted texts say a Correct may change it, but give no dating rule for a corrected `until`; the existing rule ("not after the decision date") fits effective dates, not a term's end, which is normally in the future. Until decided, the kernel refuses such a Correct. Annul and Void of a Renew work.
- **Push shape** (a committee resolution renewing many members, all or none): S2-6.
- **Suspended members:** a renewal while suspended is allowed (D-177 row 8); suspension itself is S2-2.

**Traceability:** PBDIGIT-354 S2-1 · D-163 · D-177 · D-205 · D-216 (G-3, G-4) · D-233 · D-236.

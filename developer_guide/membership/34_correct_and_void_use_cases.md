# 34: Correct and Void as use cases, and D-240 for a corrected End (lifecycle S2-5a), PBDIGIT-354

**Decisions:** D-206 (recording authority with evidence) · D-208 §2 (`correct-membership-record`, `void-membership-record`; GC-14) · D-238 (order and refusals) · D-240 (a pending scheduled resignation takes effect first) · the ratified correction algebra C-1 / C-2 (D-COR-3: dates of Admit and End) and its persistence C-4.
**Plan:** `docs/plans/20261115-0510-release-1-build-slice-s2-membership-lifecycle-plan.md` §7e.
**Evidence:** `docs/publicdigit/membership/evidence/2026-11-16-pbdigit354-s2-5a/`.
**Not here (S2-5b, and open questions):**
- a Renew's `until` (D-237, with its migration) and a Restore's date (row 14);
- a Suspend's dates (Q-6) and a Renew's start (Q-7) stay refused;
- the form of a Void's report (Q-8);
- a correction after a superseded declaration (Q-9).

## Business purpose

**When a membership record is wrong, an officer with recording authority can now fix it, citing the evidence:**
- **Correct:** the event happened, but on another date. "She resigned on 14 November, not 9 November."
- **Void:** the event never happened. "This ending was entered for the wrong person."

**Nothing is overwritten.** The original record, the correction, who made it, under what authority and on what evidence all stay in the history. Earlier answers ("as known on …") don't change. The change reaches the organisation through the membership feed (C1).

**A member cannot correct their own record** (`ownAct` authorises nothing here). **Without evidence nothing happens**, and the authority isn't even asked.

## The pieces

| Layer | Files |
|---|---|
| Commands | `CorrectMembershipRecord` (target fact, corrected date, decision date, evidence), `VoidMembershipRecord` (target fact, decision date, evidence) |
| Handlers | `CorrectMembershipRecordHandler`, `VoidMembershipRecordHandler`: through `DecideAndAppend`, the D-238 order |
| Authority | `MembershipAct::CorrectMembershipRecord` / `VoidMembershipRecord` (Governance's names); `AuthorityRequest` gains `factRef`, `correctedValue` (additive) and allows `evidenceRef` for both; validated per act |
| Domain | **unchanged:** `Member::decideCorrect` / `decideVoid` (C-1 / C-2) decide what is correctable and the dating rules; new refusal `CorrectionEvidenceRequired` |
| Infrastructure | `PostgresMemberRepository::competingEnd()`: D-240 also judges a **Correct whose chain is rooted in an End**, at its **corrected** date. `InterimRoleAuthority` denies both acts |

**The order of a request** (D-238):
1. Evidence present? If not, `CorrectionEvidenceRequired`; nobody is asked.
2. Ask: `correct-membership-record` with (fact ref, corrected value, evidence ref), or `void-membership-record` with (fact ref, evidence ref). The subject is the member.
3. Not authorised or undetermined: refused, and nothing is told about the member or the fact.
4. Member missing: `MemberNotFound`. Fact missing: `TargetNotFound`, said only after authorisation.
5. The kernel decides; then the append.

**Provenance:** `Provenance::decision(recorder, authority answer, evidence ref)`. The evidence reference is in `declarationRef`, as for a death (S2-3b): the "recorded from evidence, challengeable" marking (D-148, D-159).

## D-240 and corrections

- **A corrected End is a competing End at its new date.** While a scheduled resignation of its period is pending, a Correct of an End, or of a Correct of one, moving it **onto or after** the scheduled date is refused with `scheduled_resignation_takes_effect_first`. Nothing is written, and the declaration is untouched.
- **The same enforcement point as S2-4d-Q5:**
  - the append;
  - under the organisation's clock row and the membership row;
  - before the version compare-and-set.

  So the concurrency protection already proven there (the Q6 mutant, the race tests) covers corrections too.
- **Not a competing End:**
  - a correction to a date **before** the scheduled date (MI-3 unchanged);
  - a Correct of an **Admit**;
  - a **Void**, which reopens the period; the resignation is then recorded on its date.

## Testing

```
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Membership/Application/Organisational/CorrectAndVoidMembershipRecordTest.php
MEMBERSHIP_PG_IMAGE=… MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite MembershipPostgres --filter PostgresCorrectAndVoidTest
MEMBERSHIP_PG_IMAGE=… MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite PlatformPostgres --filter MembershipThroughRoutingTest
```

- **Unit (9):**
  - request shapes;
  - new facts, originals kept;
  - evidence first;
  - no leak;
  - `ownAct` refused;
  - a chain of corrections;
  - idempotency;
  - Renew still refused;
  - request validation.
- **PostgreSQL (4):**
  - stored append-only with evidence and published;
  - D-240 on and after the date;
  - a Correct of a Correct judged by its End;
  - the allowed cases, then the resignation executed.
- **Routing (1):** in A's own database, B untouched; production cannot build either handler (no authority until S3).

## Pitfalls

- **Never "fix" a record by editing a row.** The ledger is append-only by design. Correct or Void it with evidence.
- **Correct vs Void:** Correct is for a real event with a wrong date; Void is for an event that never happened. Don't Void-and-re-record what was only misdated: the history would then claim the original never happened.
- **Correcting a Renew, Suspend or Restore is still refused** (S2-5b and Q-6 / Q-7). A wrong one can be Voided.

**Traceability:** PBDIGIT-354 S2-5a · D-206 · D-208 §2 (GC-14) · D-238 · D-240 · C-1, C-2, C-4.

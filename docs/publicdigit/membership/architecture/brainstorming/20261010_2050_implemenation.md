
Option E: final readiness correction (plan only)

I agree with ChatGPT's review and have no disagreements with the prompt. Nothing was edited, run or committed, and no database was touched. HEAD is still bab1a9fbb.

Three corrections from the previous version:
- The test sequence no longer depends on a temporary UnhandledMatchError; every new test that should fail now fails on an assertion, never on a crash.
- Test IDs are renamed E1–E9 so they don't clash with the existing Slice 1b tests T1–T12.
- The PostgreSQL delivery choice is laid out below as an explicit decision for you.

1. Corrected test sequence

How the mapping test avoids crashing (E9). Its rows are built from the enum's actual cases, so it never refers to a case that doesn't exist yet. Each case is looked up in a table of expected mappings keyed by text value. The coverage check compares the two sets of values in both directions.

So before implementation, the five existing cases pass, and the coverage check fails with a plain assertion: "expected valid_time_not_storable, not defined". After implementation, everything passes. If someone later adds an outcome without mapping it, the coverage check fails. The match without a default is only a backstop; E9 does not rely on its exception.

┌─────┬─────────────────────────────────┬────────────────────────────────────────────────┬─────────────────────┬──────────────────────────┬─────────────────────────────────────┐
│ ID  │           Requirement           │                     Input                      │          Expected after implementation           │  Before implementation   │                 Why                 │
├─────┼─────────────────────────────────┼────────────────────────────────────────────────┼─────────────────────┼──────────────────────────┼─────────────────────────────────────┤
│ E1  │ an unsupported date gives an    │ Admit effectiveAt −719163; Admit effectiveAt   │ result is 'valid_time_not_storable'; version,    │                          │ the double stores the fact and      │
│ a–d │ explicit result and changes     │ 2932897; Admit initialTermUntil 2932897; End   │                     │ fails (assertion)        │ returns appended                    │
│     │ nothing                         │ effectiveAt 2932897                            │                                                  │                          │                                     │
├─────┼─────────────────────────────────┼────────────────────────────────────────────────┼─────────────────────┼──────────────────────────┼─────────────────────────────────────┤
│ E2  │ boundary dates stay storable    │ −719162 and 2932896 for effectiveAt,           │ appended, and read back with identical dates     │ passes (guard test)      │ pins behaviour that the new check   │
│ a–c │                                 │ initialTermUntil and End                       │                     │                          │ must not break                      │
├─────┼─────────────────────────────────┼────────────────────────────────────────────────┼──────────────────────────────────────────────────┼──────────────────────────┼─────────────────────────────────────┤
│     │ the key check comes before the  │ key k1 already used; fingerprint fp-other;     │                     │                          │ the double already checks the key   │
│ E3  │ date check (reused key,         │ date 2932897                                   │ key conflict; version unchanged                  │ passes (guard test)      │ first; a date check placed earlier  │
│     │ different payload)              │                                                │                     │                          │ would break this                    │
├─────┼─────────────────────────────────┼────────────────────────────────────────────────┼──────────────────────────────────────────────────┼──────────────────────────┼─────────────────────────────────────┤
│ E4  │ the key check comes before the  │ same key and fingerprint; date 2932897         │iginal result        │ passes (guard test)      │ same reason                         │
│     │ date check (identical retry)    │                                                │                                                  │                          │                                     │
├─────┼─────────────────────────────────┼────────────────────────────────────────────────┼─────────────────────┼──────────────────────────┼─────────────────────────────────────┤
│ E5  │ R-1a-6: an unsupported date     │ stale version; date 2932897                    │ 'valid_time_not_storable'; version unchanged     │ fails (assertion)        │ the double returns a concurrency    │
│     │ wins over a stale version       │                                                │                     │                          │ conflict                            │
├─────┼─────────────────────────────────┼────────────────────────────────────────────────┼──────────────────────────────────────────────────┼──────────────────────────┼─────────────────────────────────────┤
│ E6  │ the boundary is inclusive and   │ the boundaries ±1 for initialTermUntil only    │; one day outside is │ refused case fails;      │ same as E1 and E2                   │
│     │ the check is exact              │                                                │  refused                                         │ accepted case passes     │                                     │
├─────┼─────────────────────────────────┼────────────────────────────────────────────────┼─────────────────────┼──────────────────────────┼─────────────────────────────────────┤
│     │ the application refuses with    │ AdmitMember with effectiveAt 2932897, on the   │ not appended; refusal value is                   │                          │                                     │   │ E7  │ the new refusal and does not    │ double                                         │o domain refusal;    │ fails (assertion)        │ today the command is appended       │
│     │ retry                           │                                                │ append called once                               │                          │                                     │   ├─────┼─────────────────────────────────┼────────────────────────────────────────────────┼─────────────────────┼──────────────────────────┼─────────────────────────────────────┤
│     │ unchanged at the application    │ AdmitMember k1 reused, different payload, date │                                                  │                          │ the application checks the key      │   │ E8  │ level: a reused key with an     │  2932897                                       │alled                │ passes (guard test)      │ before deciding                     │
│     │ unsupported date                │                                                │                                                  │                          │                                     │   ├─────┼─────────────────────────────────┼────────────────────────────────────────────────┼─────────────────────┼──────────────────────────┼─────────────────────────────────────┤
│ E9  │ every outcome is mapped, and    │ a stub repository returns each actual enum     │ the table below; coverage sets are equal         │ 5 rows pass; coverage    │ the new case is missing             │   │     │ only a conflict retries         │ case                                           │                     │ check fails (assertion)  │                                     │
└─────┴─────────────────────────────────┴────────────────────────────────────────────────┴──────────────────────────────────────────────────┴──────────────────────────┴─────────────────────────────────────┘   
E9's expected mappings:                                                                                                                                                                                          
┌──────────────────────────┬─────────────────────────┬─────────────────┐                                                                                                                                         │         Outcome          │     Expected result     │ Calls to append │
├──────────────────────────┼─────────────────────────┼─────────────────┤                                                                                                                                         │ appended                 │ appended                │ 1               │
├──────────────────────────┼─────────────────────────┼─────────────────┤                                                                                                                                         │ already_applied          │ appended                │ 1               │
├──────────────────────────┼─────────────────────────┼─────────────────┤                                                                                                                                         │ idempotency_key_conflict │ refused                 │ 1               │
├──────────────────────────┼─────────────────────────┼─────────────────┤                                                                                                                                         │ member_not_found         │ refused                 │ 1               │
├──────────────────────────┼─────────────────────────┼─────────────────┤                                                                                                                                         │ valid_time_not_storable  │ refused                 │ 1               │
├──────────────────────────┼─────────────────────────┼─────────────────┤                                                                                                                                         │ concurrency_conflict     │ concurrent_modification │ 3               │
└──────────────────────────┴─────────────────────────┴─────────────────┘                                                                                                                                         
E1–E6 go into the shared MemberRepositoryContract, so the PostgreSQL test class inherits them. E7 and E8 go into MembershipCommandHandlersTest, and E9 into a new mapping test. All comparisons use text values, and the boundaries are literal numbers, never the shared constants.

Stages

Every result below is a prediction. Each run is recorded as .out, .exit and SHA-256, plus from its prediction, I stop and report.

┌─────────────────┬────────────────────────────────────────────────────────────────────────────────────────────────────────────────┬────────────────────────────────────────────┐
│      Stage      │                                    Purpose                                    │                           Command                           │                 Prediction                 │
├─────────────────┼────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────┤
│                 │                                                                               │ the four Part 1 suites: Application, Domain,                │ exit 0, with the counts recorded in the    │
│ 1 Baseline      │ confirm the starting point                                            e three tests/Architecture/Membership*   │ Part 1 evidence (34 / 89 / 5 / 12)         │
│                 │                                                                               │ files                                                       │                                            │
├─────────────────┼────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────┤
│ 2 Contract      │                                                                               │                                                             │ E1, E5 and E6's refused case fail on       │
│ tests           │ show the capability is missing                                                                                 │ assertions; E2, E3, E4 and E6's accepted   │
│                 │                                                                               │                                                             │ case pass                                  │
├─────────────────┼────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────┤
│ 3 Application   │                                                                               │                                                             │ E7 and E9's coverage check fail on         │
│ tests           │ show the refusal and mapping are missing                                                                       │ assertions; E8 and E9's five rows pass; no │
│                 │                                                                               │                                                             │  crashes, exit 1                           │
├─────────────────┼────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────┤
│ Commit 1        │ the failing tests                                                             │ —                                                           │ —                                          │
├─────────────────┼────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────┤
│ 4               │ one step: StorableValidTime, the AppendOutcome and ApplicationRefusal cases,  │                                                             │ E1–E9 pass; existing tests unchanged; exit │
│ Implementation  │ the checks in the double and PostgreSQL, DateOrdinal using the shared then DateOrdinalTest                     │  0                                         │
│                 │ constants, switch → match                                                     │                                                             │                                            │
├─────────────────┼────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────┤
│ Commit 2        │ the fix                                                                       │ —                                                           │ —                                          │
├─────────────────┼────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────┤
│                 │ Domain (including IntervalAlgebraTest and ∞), Application (including          │                                                             │                                            │
│ 5 Regression    │ concurrency and idempotency), DateOrdinalTest, Architecture (dependenc                                         │ exit 0 for each                            │
│                 │ direction, banned words)                                                      │                                                             │                                            │
├─────────────────┼────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────┤
│ Part 2          │ E1–E6 run on PostgreSQL                                                       │ gated separately                                            │ not claimed                                │
└─────────────────┴────────────────────────────────────────────────────────────────────────────────────────────────────────────────┴────────────────────────────────────────────┘

2. Revised acceptance criteria

1. Stages 2 and 3 fail exactly as predicted, every failure an assertion failure (no Error/xit 0. All outputs are recorded.
2. The diff touches only the files listed in §6. TimePoint, the domain, and the existing test assertions are unchanged.
3. The contract amendments in §3 are approved by you before Commit 2, and committed with t guide.
4. Explicitly not claimed:
   - full adapter parity: the member-not-found ordering is F-3's job;
   - any PostgreSQL behaviour, until Part 2;
   - any business calendar rule.

3. Proposed contract amendments (exact wording; not yet edited)

Slice 1a contract (2026-10-09-membership-slice1a-contract.md)

- §3, a new line after MemberNotFound:
  | ValidTimeNotStorable   (a finite valid time outside 0001-01-01 … 9999-12-31; R-1a-6)
- §4, a new row:
  | R-1a-6 | **Storable valid time.** Every finite valid time of a new command's fact (Admit effectiveAt; Admit initialTermUntil when finite; End effectiveAt) lies within 0001-01-01 … 9999-12-31 inclusive
  (ordinals −719162 … 2932896). append checks this after the idempotency-key lookup and beso ValidTimeNotStorable takes precedence over ConcurrencyConflict and MemberNotFound. It is a technical persistence capability, not a business calendar rule; TimePoint is unchanged. The refusal is not remembered (IDEM-4) and is not retried. |
- §5, a new row:
  | a valid time outside the storable range (R-1a-6) | Refused(ValidTimeNotStorable); nothing appended; not retried; not remembered |
- Amendment block at the end:
  Amended 2026-10-xx (PBDIGIT-264 / Option E, PO-authorised): R-1a-6 and the outcome ValidTimeNotStorable added. Ordering in append: key lookup → R-1a-6 → version → writes (§7 F-3 clause unchanged). Parity
  scope: the date contract only; the MemberNotFound-versus-key ordering of the test double

Slice 1b contract (2026-10-09-membership-slice1b-contract.md)

- §3 (line 48): "No new outcome." becomes "No new outcome, except ValidTimeNotStorable (R-x amendment."
- §4, a new table row:
  | storable range | 0001-01-01 … 9999-12-31 inclusive | a finite TimePoint outside it is fore any write; DateOrdinal also throws as a last safeguard |
- §6 (line 132): insert, between (1) and (2):
  (1b) any finite valid time of the fact outside the storable range → ValidTimeNotStorableodified state
- §8: add rows E1–E6 (the contract suite, run on PostgreSQL in Part 2).
- Amendment section at the end: the same text as the Slice 1a block, plus "PostgreSQL behat 2."

4. PostgreSQL delivery boundary

┌───────────────────────────┬───────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                           │ P1: include the PostgreSQL change now; verify it in Part 2  │                                   P2: leave PostgreSQL unchanged until Part 2                                    │
├───────────────────────────┼───────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ Unverified code shipped   │ 3 lines (a check that returns before the version update),   │ none added. But PostgreSQL's current refusal also relies on a rollback that has never been verified              │
│                           │ never run against PostgreSQL                                                                                                                      │
├───────────────────────────┼─────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ Agreement with the        │ the production adapter is written to the contract; the       breaks the amended contract: it throws where the contract says it returns a refusal. │
│ amended contract          │ double and PostgreSQL agree in code                         │  E1 and E5 would fail on PostgreSQL in Part 2 by construction                                                    │
├───────────────────────────┼───────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ Effect on the test-double │ the double is a faithful stand-in for production            │ the double promises behaviour that production doesn't deliver, which is the very kind of gap Option E exists to  │
│  contract                 │                                                                                                                                                   │
├───────────────────────────┼─────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ Effect on behaviour       │ an unsupported date never reaches the version update, so it h stays the only protection                                                           │
│                           │  no longer depends on rollback                              │                                                                                                                  │
├───────────────────────────┼───────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ Evidence needed before    │ Part 2: E1–E6 on PostgreSQL, version and rows unchanged,    │ the same, plus a second implementation increment                                                                 │
│ release                   │ plus the existing T7 rollback test                                                                                                                │
└───────────────────────────┴─────────────────────────────────────────────────────────────┴──────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘

Recommendation: P1, with an explicit release gate. The adapter must not be declared accepted, integrated or used in production until Part 2 evidence exists.

Inferred, not verified: the inventory shows no Membership slice integrated or in production, so P1 changes nothing that is live. That should be revalidated against the gate register before any status is
published. The G3 review document's verdicts were still blank when I last read it.

5. Your decisions (all verdicts blank)

┌─────┬──────────────────────────────────────────────────────────────────────────────────────────────────────────────┬─────────┐
│  #  │                                                                 Decision                                                                  │ Verdict │
├─────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────┤
│ 1   │ Order inside append: key lookup → date check → version → writes                                                                           │ [ ]     │
├─────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────┤
│ 2   │ R-1a-6: an unsupported date takes precedence over a stale version, so the observable result changes from conflict to ValidTimeNotStorable │ [ ]     │
├─────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────┤
│ 3   │ Accept the F-3 limitation (member-not-found ordering) for this increment; no claim of full parity                                         │ [ ]     │
├─────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────┤
│ 4   │ Approve the Slice 1a and 1b amendments in §3                                                                                              │ [ ]     │
├─────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────┤
│ 5   │ PostgreSQL delivery: P1 (include now, with a release gate until Part 2) or P2 (defer)                                                     │ [ ]     │
├─────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────┤
│ 6   │ Names: StorableValidTime, ValidTimeNotStorable, R-1a-6, tests E1–E9                                                                       │ [ ]     │
├─────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────┤
│ 7   │ Authorise this increment (no database)                                                                                                    │ [ ]     │
├─────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────┤
│ —   │ Business calendar or plausibility rule (B, C, D or none): separate, not decided by Option E                                               │ [ ]     │
└─────┴──────────────────────────────────────────────────────────────────────────────────────────────────────────────┴─────────┘

6. Implementation scope

Production:
- Application/Organisational/Ports/StorableValidTime.php (new). The architecture test scans comments too, so its source must not contain DateTime or date(;
- Ports/AppendOutcome.php;
- ApplicationRefusal.php;
- DecideAndAppend.php (match);
- Infrastructure/Organisational/PostgresMemberRepository.php (only under P1);
- Infrastructure/Organisational/DateOrdinal.php.

Tests:
- tests/Doubles/InMemoryMemberRepository.php;
- MemberRepositoryContract.php (E1–E6);
- MembershipCommandHandlersTest.php (E7, E8);
- a new AppendOutcomeMappingTest.php (E9).

Docs:
- the two contract amendments;
- developer_guide/membership/ (a new step, plus 00_index.md);
- an evidence folder for stages 1–5.

Commits: failing tests → fix → documents and evidence. Nothing pushed.

7. For ChatGPT's final review

1. Does each test that should fail before implementation fail on an assertion, with no reference to an undefined case?
2. Does E9 prove complete mapping and retry-only-on-conflict without relying on UnhandledM
3. Do E3, E4 and E8 protect the key-first order? Would they catch a date check placed too early?
4. Is R-1a-6's wording precise and limited to a technical capability?
5. Do the amendments cover Slice 1a §3, §4, §5 and Slice 1b §3, §4, §6, §8 without overclaiming parity?
6. Is P1 with a release gate the right delivery boundary?
7. Are all PostgreSQL claims deferred to Part 2?

I've stopped here, waiting for ChatGPT's final review, your decisions in §5, and explicit authorisation to implement.
Stop says: Project-state sync (CLAUDE.md):
  x Session log .claude/sessions/2026-10-10.md not updated in 45+ min (39 files modified today)
  x CONTEXT.md not updated today - refresh active ticket / next action if they changed
  o no plan file touched today (fine for small fixes; update the active plan for ticket work)
  (non-blocking; modified files: .claude/runtime/2026-10-10-files.log)
##

# Membership implementation — Principal Reviewer assessment

My conclusion from Claude’s latest report is: the Option E plan is close to implementation readiness, but the architecture decision is not yet formally finalized and implementation has not been authorized.

The immediate task is not to begin coding. It is to complete the independent review, resolve the remaining architectural decisions, and obtain an explicit Human PO/ARB approval. Claude’s report confirms that the latest revision was plan-only: no files were edited, no tests were run, no database was touched, and no commits were made; the reported HEAD is `bab1a9fbb`. These are statements from the report, not a fresh verification of the repository.&#x20;

Eingefügter Text.txt

Eingefügter Text.txt



## 1. What Claude has done well

The test plan is materially improved

Tests E1–E9 now have a clearer purpose. The mapping test is designed to fail through an assertion when an outcome lacks a mapping, rather than relying on a temporary runtime exception. The proposed tests also protect idempotency-key precedence and the rule that only concurrency conflicts trigger retries.&#x20;

Eingefügter Text.txt

Eingefügter Text.txt



The technical and business questions are separated

Option E concerns whether a finite valid time can be represented and persisted safely. It must not silently introduce a new business rule about when a membership may begin or end. `TimePoint` and its intentional support for extreme values must remain unchanged.

The adapter-parity limitation is explicit

The plan does not claim that the PostgreSQL repository and in-memory test double have complete parity. The known F-3 ordering difference remains an identified limitation. PostgreSQL behaviour is explicitly unverified until Part 2.&#x20;

Eingefügter Text.txt



There is a release boundary

Claude recommends including the PostgreSQL change in the technical increment while prohibiting acceptance, integration, or production use until the PostgreSQL contract tests and rollback-related evidence are available. That is a defensible approach if the gate is enforced.&#x20;

Eingefügter Text.txt



## 2. My independent review: what still needs resolution

### A. Confirm the observable precedence rule

The proposed append order is:

1. Idempotency-key lookup.
2. Valid-time representability check.
3. Version/concurrency check.
4. Writes.

This protects the existing key-first behaviour. However, it deliberately introduces R-1a-6: an unsupported date takes precedence over a stale version and produces `ValidTimeNotStorable` instead of `ConcurrencyConflict`.

That is not merely an implementation detail. It changes the observable application contract. My recommendation is to approve it as an explicit technical validation policy, provided the Human PO/ARB agrees that this error precedence is desirable.

### B. Accept F-3 only as a bounded limitation

The plan acknowledges that the in-memory test double and PostgreSQL adapter can still differ in the ordering of `MemberNotFound` and the idempotency-key check.

My recommendation: allow the Option E increment to proceed with that limitation explicitly documented, but do not call this full adapter parity. Track F-3 as a separate unresolved issue and prevent the limitation from disappearing during later cleanup.

### C. Tighten the boundary-test specification

The report has E1–E6, including overlapping checks of boundary acceptance and one-day-outside rejection. The intent is sound, but the final test matrix should clearly distinguish:

- Every date-bearing fact field that requires a check.
- The inclusive lower and upper boundaries.
- One value below and one value above the supported range.
- The absence of writes and version changes on refusal.
- The expected result when an idempotency key has already been used.
- The intentional precedence of an unsupported date over a stale version.

The report should also state exactly which combinations are covered by E2 and E6, avoiding ambiguity in the labels and case counts.

### D. Keep the mapping test genuinely exhaustive

E9 should enumerate the actual `AppendOutcome` cases and verify that each has an explicit application result. It should also verify that only `ConcurrencyConflict` retries, with the expected retry limit, and that the other outcomes do not accidentally fall through to a generic concurrency failure.

One detail needs care: `AlreadyApplied` is mapped to an application-level success in the table. Confirm that this matches the established idempotency contract and the original result semantics, rather than assuming every already-applied outcome should be represented identically.

### E. Clarify the helper's architectural location

Claude proposes `Application/Organisational/Ports/StorableValidTime.php`. Before freezing that location, require a short dependency and naming justification:

- Is this a port, or is it a framework-free policy/helper used by multiple adapters?
- Can both the PostgreSQL adapter and test double use it without violating dependency direction?
- Does the architecture test permit its namespace and source vocabulary?
- Are all relevant fact types covered explicitly, with a safe default if a new date-bearing fact is introduced later?

The class should not turn into an accidental second domain calendar policy. Its responsibility is technical persistence representability.

### F. Reconcile the documentation and commit sequence

The plan requires contract amendments to be approved before the fix is committed, but the final sequence also refers to evidence and developer-guide changes. Claude should give a single unambiguous sequence covering:

- Approval of contract changes.
- Tests-first commit.
- Implementation commit.
- Contract, developer-guide and evidence changes.
- The exact state of the repository after each commit.

No documentation should be labelled “PO-authorised” until the relevant Human PO/ARB decision actually exists.

## 3. Recommended decision package for the Human PO/ARB

These are my recommendations for a decision meeting, not approvals on behalf of the Human PO/ARB.

| Decision                               | Principal Reviewer recommendation                                                     | Why                                                                                                                       |
| -------------------------------------- | ------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------- |
| 1. Append order                        | Approve key → date → version → writes                                                 | Preserves key-first behaviour and makes the new check explicit.                                                           |
| 2. R-1a-6 precedence                   | Approve explicitly                                                                    | Prevents an unsupported value reaching the persistence mutation path, but changes which refusal wins.                     |
| 3. F-3 limitation                      | Accept for this increment only                                                        | Keeps scope bounded without claiming complete parity.                                                                     |
| 4. Slice 1a/1b amendments              | Approve after the wording and test matrix are finalized                               | The implementation must follow an explicit contract.                                                                      |
| 5. PostgreSQL delivery                 | P1, with a strict release gate                                                        | Keep production-adapter code aligned with the proposed contract, but do not accept or release it without Part 2 evidence. |
| 6. Names                               | Provisionally accept `StorableValidTime`, `ValidTimeNotStorable`, `R-1a-6`, and E1–E9 | Clear enough, subject to the helper's architectural placement and existing vocabulary.                                    |
| 7. Business calendar/plausibility rule | Do not decide this under Option E                                                     | Treat it as a separate domain decision with its own evidence and identifier.                                              |
| 8. Implementation authorization        | Withhold until decisions 1–7 are resolved                                             | Plan readiness does not equal authorization to modify the repository.                                                     |

The P1 recommendation is conditional. The plan must maintain a clear distinction between code that has been written, unit-tested, and contract-tested, and code whose PostgreSQL behaviour has actually been verified. The report itself correctly says the latter remains unverified until Part 2.&#x20;

Eingefügter Text.txt



## 4. Prompt instructions for Claude — next step

Use the following prompt to direct Claude. It keeps the responsibilities separate: Claude investigates and prepares the plan; ChatGPT independently challenges it; the Human PO/ARB owns architectural decisions and authorization.

# Membership — Option E Final Architecture Decision Readiness

## Mission

Continue the Membership implementation work as a senior Domain-Driven Design (DDD) architect, requirements engineer, researcher, mathematical and statistical analyst, computer-logic expert, and implementation planner.

The immediate goal is to finalize the technical architecture and decision package for Option E: persistence representability of finite membership valid times.

Do not expand the scope into other domains or silently introduce new business rules. Preserve the existing Membership architecture and all approved contracts unless a change is explicitly proposed, justified, reviewed, and approved.

## 1. Roles and decision authority

### Claude — Investigator and implementation engineer

- Inspect the actual repository, active ticket, contracts, architecture tests, test doubles, production adapter, and evidence.
- Produce a precise, reproducible implementation plan.
- Challenge assumptions and identify missing requirements, inconsistent contracts, race conditions, and untested failure paths.
- Do not treat a proposed decision as approved.
- Do not implement until the final contract and scope have been approved by the Human PO/ARB and explicit implementation authorization has been given.

### ChatGPT — Independent Principal Reviewer

- Independently review the proposed design and test strategy.
- Challenge semantic assumptions, state-transition correctness, idempotency, concurrency, dependency direction, and claims of parity.
- Distinguish verified facts from inferences and recommendations.
- Do not substitute its recommendation for Human PO/ARB authority.

### Human PO/ARB — Decision authority

- Owns acceptance of the observable contract, architectural trade-offs, residual risks, and final authorization.
- Must explicitly approve or reject each unresolved decision.
- No silence, suggested default, or reviewer recommendation counts as approval.

## 2. Current baseline to revalidate

The latest report stated:

- Option E remains a plan-only change.
- No files, tests, database, or commits were changed in that run.
- The reported HEAD was `bab1a9fbb`.
- The proposed append order is idempotency-key lookup → valid-time representability → version/concurrency check → writes.
- R-1a-6 would make an unsupported valid time take precedence over a stale version.
- F-3 remains an explicit adapter-ordering limitation.
- PostgreSQL behaviour remains unverified until Part 2.
- Human PO/ARB decisions and implementation authorization remain outstanding.

Revalidate these statements against the current repository and gate register before relying on them. Do not claim a test passed unless it was run and its output and exit status were captured.

## 3. Next task: final plan correction, not implementation

Remain in PLAN-ONLY mode. Do not edit source files, contracts, test files, project-state documents, or session logs. Do not run tests or touch any database during this step. Do not commit or push.

Review the latest Option E plan and return one corrected, decision-ready proposal that addresses the following.

### A. Freeze the technical boundary

Define Option E as a persistence-representability rule only:

- Supported finite valid-time range: 0001-01-01 through 9999-12-31 inclusive.
- Confirm the ordinal boundaries against the actual `DateOrdinal` implementation.
- Preserve `TimePoint` semantics, including its intentional extreme values and infinity.
- Identify every date-bearing fact field that must be checked.
- State clearly that Option E does not decide business calendar validity, membership plausibility, historical backdating, or scheduling policy.

### B. Specify append semantics

Show the exact outcome for each relevant condition:

1. Existing key and identical fingerprint.
2. Existing key and different fingerprint.
3. Unsupported finite valid time.
4. Stale version.
5. Missing member.
6. Successful append.
7. Any other existing append outcome.

Explain the intended ordering and precedence. Explicitly mark R-1a-6 as a proposed observable contract change requiring Human PO/ARB approval.

Do not claim full adapter parity while F-3 remains unresolved.

### C. Finalize the test design

Provide a single matrix for E1–E9 showing:

- Requirement and invariant.
- Exact input and setup.
- Expected application/repository result.
- Whether state, version, log, or key rows must remain unchanged.
- The test suite in which it belongs.
- Expected result before implementation.
- Expected result after implementation.

Remove duplicate or ambiguous coverage. Confirm all relevant boundary combinations are covered.

E9 must enumerate actual `AppendOutcome` cases and detect missing or unexpected mappings through assertions. It must prove that only concurrency conflicts retry and that the retry limit is correct. Explain why `AlreadyApplied` maps to the proposed application result without losing idempotency semantics.

Every deliberately failing pre-implementation test must fail on an assertion, not on an undefined enum case, uncaught exception, or malformed test.

### D. Validate the helper's architecture

Evaluate the proposed location `Application/Organisational/Ports/StorableValidTime.php`.\
Explain whether the helper is truly a port or a shared framework-free policy. Recommend the smallest location and API that satisfy dependency direction and permit safe reuse by the production adapter and test double.

Check the planned name and implementation against the repository's actual architecture tests, including prohibited source vocabulary. Do not weaken or bypass architecture tests.

Explain how adding a future date-bearing fact would require the range check to be extended rather than silently bypassed.

### E. Make the PostgreSQL boundary explicit

Compare:

- P1: implement the PostgreSQL check in this increment, but block acceptance/release until Part 2.
- P2: defer the PostgreSQL code change until Part 2.

Recommend one option with explicit reasons. State exactly what has and has not been verified. Under no circumstances claim PostgreSQL behaviour without Part 2 evidence.

If P1 is selected in the proposal, identify the minimum safe verification required before acceptance, including the unsupported-date result, unchanged version and rows, transaction/rollback behaviour, and relevant existing regression tests.

### F. Correct the delivery sequence

Give one exact sequence covering:

1. Revalidation of baseline and gate register.
2. Human PO/ARB approval of the contract amendments.
3. Tests-first change and evidence capture.
4. Implementation change and focused regression.
5. Contract, developer-guide, and evidence updates.
6. Review of the final diff and commit boundaries.
7. Part 2 PostgreSQL verification.
8. Formal acceptance decision.

For every stage, identify the allowed actions, expected evidence, stop conditions, and who authorizes progression. Do not write “PO-authorised” in any artifact before actual approval.

### G. Preserve unresolved decisions

Provide a compact decision sheet with explicit blank verdicts for:

1. Append ordering.
2. R-1a-6 precedence over stale version.
3. F-3 limitation for this increment.
4. Slice 1a and Slice 1b contract amendments.
5. P1 versus P2 PostgreSQL delivery.
6. Names and helper location.
7. Separate business calendar/plausibility rule.
8. Implementation authorization.

For each item, provide the recommended choice, rationale, impact of rejecting it, and the approving authority.

## 4. Research and analytical discipline

Treat the Membership research corpus as brainstorming and evidence material, not as a collection of automatically approved requirements.

Use formal reasoning where useful:

- Separate invariants, preconditions, postconditions, and observable outcomes.
- Identify contradictions and missing cases.
- Use boundary analysis, equivalence classes, counterexamples, and state-transition reasoning.
- Use statistical or machine-learning methods only when they materially improve corpus organization or hypothesis generation. Label clusters and inferred relationships as hypotheses, not approved domain truth.
- Maintain traceability from source evidence to requirement, invariant, contract clause, test, and decision.
- Preserve unresolved questions as explicit decisions; do not resolve them silently.

## 5. Required response format

Return:

1. Repository baseline and evidence actually verified.
2. Independent critique and unresolved risks.
3. Final proposed technical contract.
4. Consolidated E1–E9 test matrix.
5. Recommended helper architecture.
6. P1/P2 recommendation and PostgreSQL release gate.
7. Exact staged delivery plan.
8. Blank Human PO/ARB decision sheet.
9. Updated Membership implementation status and remaining todos.
10. Checklist for ChatGPT's independent review.

End by stating explicitly:

“PLAN ONLY. No source, test, contract, state, or session files changed. No tests run. No database touched. No commit made. Waiting for independent ChatGPT review, Human PO/ARB decisions, and explicit implementation authorization.”

If any of those statements is not true, report the actual actions and evidence instead.

Stop after delivering the plan. Do not begin implementation.

## 5. How far are we from the Membership implementation goal?

The current evidence supports the following assessment. These are implementation-status observations, not formal acceptance decisions.

## Membership delivery status

Domain discovery and design

Largely complete

The core Membership model and important design distinctions are established. The architecture has a defined direction, but not every correction, business rule, and implementation contract is finalized.

Technical foundations

Partially implemented

Slice 0, Slice 1a, Slice 1b and C-1 have implementation or review evidence in the prior reports. Their formal acceptance and remaining conditions must be checked against the current gate register.

Option E

Plan only

The proposed correction has a detailed test plan, contract amendments and a PostgreSQL delivery recommendation. It is not yet implemented according to the latest report.

End-to-end Membership capability

Early implementation stage

Commands, queries, integrations, migration, user interface and full regression/acceptance remain substantial work.

I would not assign a reliable overall completion percentage yet. The previous inventory counted roughly four of seventeen slices as implemented in whole or in part. That is not equivalent to four completed slices out of seventeen, and it does not measure the amount of domain, integration or release work remaining.

## 6. Remaining todos

### Immediate — finalize Option E

- Ask Claude to produce the corrected decision-ready plan using the prompt above.
- Revalidate the current HEAD, changed files, active ticket, gate register and formal review verdicts.
- Finalize the E1–E9 test matrix and resolve any ambiguity or duplicate coverage.
- Confirm the helper's location, dependency direction and API.
- Finalize the exact Slice 1a and Slice 1b contract amendments.
- Obtain Human PO/ARB decisions on append ordering, R-1a-6, F-3, P1/P2 and naming.
- Have ChatGPT independently review the finalized plan.
- Authorize implementation explicitly only after the architecture decisions are settled.

### Technical implementation and verification

- Implement Option E test-first after authorization.
- Verify key-first behaviour, range boundaries, refusal semantics, unchanged state and retry behaviour.
- Verify complete `AppendOutcome` mapping and application-refusal handling.
- Run the focused tests and regression suites; preserve output, exit codes and evidence hashes.
- Complete Part 2 PostgreSQL verification, including version and row preservation, transaction/rollback behaviour and inherited contract tests.
- Resolve the outstanding F-2–F-5 issues according to their existing tickets and approved scope.
- Obtain formal verdicts for Slice 0, Slice 1a, Slice 1b and the C-1 review, including all residual risks.

### Complete the Membership domain and application

- Finalize outstanding domain decisions, including C-2, C-3 and C-4.
- Progress the remaining Membership command and query slices.
- Complete the required application and integration work, including outbox/inbox behaviour and cross-context reactions where specified.
- Keep Membership distinct from voting entitlement, appointment, mandate, authority and duty.
- Ensure every new requirement has traceability to its source, invariant, contract, test and approval.

### Migration, user interface and release

- Finalize the Slice 13 migration and normalization contract.
- Classify historical records as finite, open-ended or ambiguous; use dry-run reports, provenance and registrar review rather than silently reinterpreting legacy placeholders.
- Complete the Slice 14 user interface.
- Complete Slice 15 regression, security, migration and acceptance checks.
- Update project-state and session documentation only through the approved workflow.
- Keep the release gate closed until required evidence and formal acceptance exist.

### Research and theory improvement

- Maintain the Membership corpus as evidence and brainstorming material, not automatically approved truth.
- Build traceability between research observations, proposed rules, domain invariants, implementation contracts and decisions.
- Use formal logic, counterexamples, boundary analysis and state-transition reasoning to detect contradictions and missing cases.
- Use machine learning for exploratory clustering and candidate contradiction detection only where it adds value; validate its findings against original sources and human decisions.
- Keep business calendar validity, historical plausibility and backdating/scheduling policy separate from technical persistence representability.

## 7. The correct next action

The next action is to finalize the Option E decision package, not to implement it immediately.

Claude has improved the plan enough for a focused final review. The remaining gate is architectural and procedural: resolve the explicitly identified decisions, have ChatGPT challenge the final proposal, and obtain Human PO/ARB approval plus explicit implementation authorization.

That preserves the division of responsibility: Claude investigates and implements, ChatGPT independently reviews, and the Human PO/ARB decides and authorizes.
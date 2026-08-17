# EM-IMPL-001 — C-5 Independent Verification Report

**Type:** Evidence-only verification report (C-5 lane) · **Date:** 2026-08-17
**Commission (PO/ARB, 2026-08-17, verbatim):** *"Start C-5 independent verification lane for EM-IMPL-001. Treat the committed increment as authorized but unverified. Evaluate against the five queued observations without modifying implementation. Produce evidence-only verification report."*
**Framing (fixed by PO):** the increment is trusted governance input, untrusted correctness input — authorization accepted, correctness independently evaluated.
**Object:** production `app/Contexts/Election/Domain/OperatingCore/` (56 files, commit `a31f54f1`) · tests `tests/Unit/Contexts/Election/OperatingCore/` (7 files, commit `4b707798`). Working tree verified identical to HEAD for both directories (`git status --porcelain` empty; `git diff a31f54f1 HEAD -- <dir>` and `git diff 4b707798 HEAD -- <dir>` empty).
**Nothing was modified.** One scratch script was executed outside the repository (scratchpad) to derive observation ① from the committed code; no repository file was touched except this report.

---

## 1 · Verified constraint table (Task A)

| # | Constraint | Verdict | Evidence |
|---|---|---|---|
| **A-1** | **D-1 wall** — `OrganisationalAppointmentAuthority` has no adapter, binding or operations anywhere in `app/` | **PASS** | The port declares zero operations (`Port/OrganisationalAppointmentAuthority.php` — empty interface body, lines 33–35 doc-only). `grep -rn "OrganisationalAppointmentAuthority" app/` outside the port file returns exactly one doc-comment mention (`Event/CommitteeSeatFilled.php:15`). No `implements …OrganisationalAppointmentAuthority`, no container binding, no `::class` reference in `app/ bootstrap/ config/ routes/` (grep exit 1). No caller of `fillSeat` exists in `app/` outside the aggregate itself (grep). `StructuralGuardsTest::test_no_adapter_exists…` enforces the absence over every PHP file under `app/` and passed. |
| **A-2** | **No time on OPEN** — nothing attaches deadline/timeout/scheduler/expiry to an OPEN gate; only time concept is caller-supplied `RecordedInstant` | **PASS** | `grep -rni "deadline\|timeout\|scheduler\|cron\|setInterval\|sleep(\|expiryHook"` over the 56 files: zero code hits (exit 1). `AcceptanceGateDecision` holds no time-typed state (fields: electionId, gate, thresholdRule, constitutedSize, positions — lines 45–52); instants appear only inside emitted event payloads. `InstantSource` is declared and consumed by nothing (`grep -rn "InstantSource" app/` outside its own file: exit 1). `GateIntervalClassification::classify` takes no time parameter (Policy/GateIntervalClassification.php:41–46). Enforced by `test_s06…no_clock_exists` (method-name regex `/clock|expir|timeout|deadline|schedul|elaps/`) and `test_nothing_attaches_time_to_open_or_to_inaction`. |
| **A-3** | **Derived-never-stored** — unableToFunction and gate interval computed, no authoritative stored flags | **PASS** (two notes) | `unableToFunction` is computed per call: `ElectionCommittee::unableToFunction()` → `UnableToFunction::evaluate($this->nonVacantCount(), $required)` (Committee/ElectionCommittee.php:126–129); `nonVacantCount()` is an `array_filter` count, no field. Gate interval computed per call in `AcceptanceGateDecision::intervalState()` (Gate/AcceptanceGateDecision.php:169–193); no interval-state field exists. Repository interfaces forbid persisting the derived values as authoritative columns (all three files, doc contracts). *Note 1:* `RecoveryProcess::$active` (Recovery/RecoveryProcess.php:38) is a stored boolean redundant with "last interval's until === null" — it cannot drift today because both mutate together in `pause()`/`resume()`, but it is bookkeeping the derivation does not need. *Note 2:* only AG-2 has a replay constructor (`fromRecordedFacts`); AG-1/AG-3 assert projection-of-facts in doc contracts only (see finding N-4). Neither note is a stored authoritative classification. |
| **A-4** | **Vocabulary** — terminal state renders only "Election Discontinued"; no Terminated/Suspended/Abandoned/Expired substitutes at election level; election-level cancellation a distinct type | **PASS** | `TerminalStatePlaceholder::businessRendering()` returns exactly `'Election Discontinued'` (Condition/TerminalStatePlaceholder.php:29–32) — the sole rendering, no other constructor. `grep -rni "terminated\|suspend\|abandon"` over the core hits only the negative documentation comment restating EM-GOV-069's meaning boundary (TerminalStatePlaceholder.php:12–13). `ElectionLevelCancellation` is a distinct final readonly type rendering `'Election Cancelled'` (058's own adopted word), never merged with the terminal type or any opportunity-level value; no opportunity-outcome type exists in the core at all (D-9/B-4 honoured by type separation). `RecoveryPeriodExpired` names the *period*, not an election state. Election-prefix convention (EM-GOV-069) honoured in both business renderings. Enforced by `test_terminal_state_renders_as_election_discontinued…` including a forbidden-substitutes loop. |
| **A-5** | **ADR-T11** — no voter↔vote linkage surface in events, aggregate state, or free-text handling | **PASS** (one note) | Grep of all event property names for `voter|ballot|\bvote\b|user`: zero (exit 1). Event payloads carry electionId, gate, seatId, position, ground, reason, instants, appointeeReference — committee-channel facts EM-GOV-005/031 *require* to be attributable; no voter or ballot concept exists anywhere in the 56 files. `VacancyReason` is a constrained surface exactly as design §5e demands: non-blank + max 500 chars, checked at construction (Committee/VacancyReason.php:16–29), tested (`test_vacancy_reason_is_a_constrained_surface`). `StructuralGuardsTest` re-checks every event property name reflectively. *Note:* `RefusalRecord::$reason`/`$requestedAct` and `CommitteeSeatFilled::$appointeeReference` are non-blank-checked but carry **no length bound** — see finding N-3. |
| **A-6** | **Framework freedom** — no Laravel/Carbon/framework import in the 56 files | **PASS** | `grep -rn "Illuminate\|Carbon\|Laravel\|use App\\Models\|use App\\Http"` over the core: zero (exit 1). The complete set of imports from outside the OperatingCore namespace is: `App\Contexts\Election\Domain\DomainEvent` (pure marker interface, verified), `App\Contexts\Election\Domain\ElectionId` (pure readonly VO, verified), `DomainException`, `InvalidArgumentException` (SPL). Enforced by `test_the_operating_core_domain_is_framework_free`. |
| **A-7** | **Boundary containment** — nothing outside the ratified boundary exists | **PASS** | Commit `a31f54f1` contains exactly the 56 domain files (git show --stat), commit `4b707798` exactly the 7 test files. `grep -rln "OperatingCore"` over `app/ resources/ routes/ database/ config/ bootstrap/` outside the domain directory: zero (exit 1) — no application layer, adapter, migration, model, controller, scheduler, or lifecycle wiring references the core. The `committee*` migrations found under `database/migrations` all predate the increment (April–May 2026) and belong to the unrelated organisational-committee subsystem. No storage/queue/scheduler technology is selected anywhere in the increment. |
| **A-8** | **Invariants I-1…I-16 enforced by code + covered by test** | **PASS** (I-5, I-6, I-15 qualified below) | Per-invariant table follows. |

### A-8 per-invariant evidence

| Inv | Enforced by (file:line) | Tested by | Status |
|---|---|---|---|
| I-1 ≥3 at constitution | `ElectionCommittee` ctor throws `CommitteeTooSmall` (ElectionCommittee.php:47–49); AG-2 ctor repeats the guard (AcceptanceGateDecision.php:54–56) | ElectionCommitteeTest test 1 | ENFORCED + TESTED |
| I-2 vacancy only by recorded event, closed grounds | `recordVacancy` is the only vacating path; `VacancyGround` closed enum; resignation requires reason (ElectionCommittee.php:96–110); `SeatAlreadyVacant`/`UnknownCommitteeSeat` guards | tests 2, 2b, 3, 16 | ENFORCED + TESTED |
| I-3 constituted denominator stable | `constitutedSize()` = count of seats fixed at construction; no add/remove-seat API exists (verified: full method list) | test 4 | ENFORCED + TESTED |
| I-4 filling re-occupies existing seat | `fillSeat` → `CommitteeSeat::fill()` throws `SeatNotVacant` on occupied seat (CommitteeSeat.php:43–49) | test 5 | ENFORCED + TESTED |
| I-5 no internal filling power | Enforced **by absence**: no adapter (A-1), no internal caller of `fillSeat` (grep), port declares no operations. The aggregate itself cannot verify the caller — the code comment says so honestly (ElectionCommittee.php:113–116) | StructuralGuardsTest (adapter absence half) | ENFORCED-BY-ABSENCE + STRUCTURALLY TESTED — the honest limit of what domain code can enforce; the wall is D-1's missing adapter |
| I-6 member ⇥ representative incompatibility | Vacuous in Model A; kept as a wall by NOT modelling any representative linkage (no such type exists in the core — verified by reading all 56 files) | none (nothing exists to test) | COMMENT-ONLY, VACUOUSLY TRUE — matches design §2c ("vacuous in Model A, kept as a wall"); not a defect, named for the record |
| I-7 one position per seat per decision | `expressPosition` throws `SeatAlreadyExpressedPosition` (AcceptanceGateDecision.php:129–137) | test 9 | ENFORCED + TESTED |
| I-8 cast vote stands; append-only; reconstitutable | positions written only by `apply()` (single writer, line 145–148); no remove/rewrite API (verified: full method list); `fromRecordedFacts` replay ctor (lines 73–98) | test 10 (incl. rebuild equality + method-name regex) | ENFORCED + TESTED |
| I-9 replacement expresses only where seat has not | positions keyed by seat id — holds by construction | test 11 | ENFORCED + TESTED |
| I-10 named rule, round-up, constituted denominator | `ThresholdRule` closed to `TWO_THIRDS_OF_COMMITTEE_VOTES` (fromName throws otherwise); `RequiredVotes` = `intdiv(2n+2,3)` = ⌈2n/3⌉ (RequiredVotes.php:31) | tests 7 (5 data-provider sizes 3→2·4→3·5→4·6→4·7→5), 8 | ENFORCED + TESTED |
| I-11 interval derived on recorded facts, trusted collaborator | `intervalState(ElectionCommittee)` rejects foreign committee and denominator mismatch (AcceptanceGateDecision.php:171–181), delegates to stateless P-2 | tests 12, 13, pin test | ENFORCED + TESTED (the two mismatch guards themselves are untested — N-6) |
| I-12 dissent recorded after threshold achieved | `expressPosition` has no "already decided" guard — deliberate; dissent remains expressible | test 14 | ENFORCED + TESTED |
| I-13 policy bound at start, never rebound | `PolicyBinding` readonly, captured in ctor, no rebinding API (verified: full method list) | test 18 | ENFORCED + TESTED |
| I-14 accrual only while condition active | interval arithmetic in `ClockAccrual::elapsedSeconds` over recorded `[from, until]` pairs | test 19 (disjointness proven: 450+200=650) | ENFORCED + TESTED |
| I-15 per-election allowance, nothing renews | No renew/restart/extend API (verified + regex-tested). The **uniqueness half** (one process per kind per election) lives only as a doc contract on `RecoveryProcessRepository` — no domain guard can exist without persistence, which is outside the boundary | test 21 (no-renewal half) | NO-RENEWAL ENFORCED + TESTED; UNIQUENESS COMMENT-ONLY (deferred to the future persistence slice — named, N-4 adjacent) |
| I-16 resumption resumes remaining portion | interval arithmetic; `resume` opens a new interval, duration unchanged | tests 20, 21 | ENFORCED + TESTED |

**Test run:** `php artisan test tests/Unit/Contexts/Election/OperatingCore` → **42 passed (2420 assertions), 0.12s** — all seven classes PASS.

---

## 2 · The five queued observations (Task B)

### ① OPEN ∧ INOPERATIVE edge — **CONFIRMED** (the combination arises; design §2b commentary is wrong; the code derivations are each rule-faithful; one residual facet is a GOVERNANCE-QUESTION)

**Executed against the committed code** (scratch script, no repo modification), scenario exactly as queued — 3 seats, required 2, seat A accepts, then A and B vacated by recorded events:

```
nonVacant=1 required=2
unableToFunction=true
inoperativeOnset=3000
gateIntervalState=open
```

**Derivation trace.** `GateIntervalClassification::classify(3, 2, ['seat-a'=>Accept], ['seat-a','seat-b'])`: accepts=1 < 2 → not DecidedPass; unexpressedAll=2, 1+2=3 ≥ 2 → not DecidedFailure; vacant-unexpressed = seat-b only (seat-a is vacant **but has expressed**) = 1, unexpressedNonVacant=1, 1+1=2 ≥ 2 → **Open** (Policy/GateIntervalClassification.php:56–82). Simultaneously `UnableToFunction::evaluate(1, 2)` → **true**, so `InoperativeOnset` fires at the causing event's instant (Policy/InoperativeOnset.php:27–33).

**Adjudication.**
- The design §2b claim — *"`OPEN ∧ INOPERATIVE` cannot arise in Model A: the same recorded vacancy event that begins Inoperative makes the threshold unachievable on recorded facts"* — is **refuted as a general claim**. Its argument overlooks a **standing accept on a subsequently vacated seat**: EM-GOV-066 (ADOPTED) makes that vote stand, so achievability on recorded facts survives (standing accept + the one non-vacant seat = 2 ≥ 2), while EM-GOV-065's arithmetic (non-vacant 1 < required 2) is breached by the same event.
- **The code derivations are right, the commentary is wrong.** Each derivation follows its adopted rule exactly: OPEN per EM-GOV-068 (undecided ∧ mathematically achievable on recorded facts, counting the standing vote 066 protects), Inoperative per EM-GOV-065 (pure arithmetic, no determiner). Forcing agreement with the commentary would require either discounting a standing vote (violates 066) or distorting the 065 arithmetic (violates 065). No ADOPTED rule is violated **by the derivations themselves**.
- **Representable:** `ElectionOperationalStatus::operative()->becameInoperative()` represents non-halted-Inoperative (haltedAtGate=null); nothing in code forbids the combination.
- **Residual GOVERNANCE-QUESTION (not resolvable here):** EM-GOV-068's parenthetical gloss on OPEN — *"(in progress; no recovery period runs)"* — meets EM-GOV-062's restoration clock, which **does** run while Inoperative. Whether the gloss means "OPEN itself starts no recovery period" (compatible with the combination) or "no recovery period may run while a gate is OPEN" (incompatible) is a rule-interpretation question only Governance can answer. A second facet: whether the remaining member (or a replacement) may **express a position while the election is Inoperative** — the code does not block it (AG-2 never sees the operational condition), and adopted text fixes Inoperative as *"preventing progression"* (059(b)), not as preventing position expression. Both facets are recorded here as governance questions; neither is resolved by this report.

### ② Count discrepancy — **REFUTED** (no real discrepancy; the two counts measure different things)

From disk and git only: **7 test files** on disk = **7 files** in commit `4b707798` (git show --stat). **38 `test_` methods** (`grep -c`). **42 executed tests** (`php artisan test` output: "Tests: 42 passed") — the difference is exactly the `roundUpArithmetic` data provider expanding one method into 5 named cases (38 − 1 + 5 = 42). The commit subject "the 42-test domain-core suite" matches the executed-test count. Any claim of a different file count or a 38-vs-42 contradiction is adjudicated by these three numbers: all internally consistent.

### ③ EM-GOV-065 causal note — **REFUTED** (causal linkage is not lost; the payload is sufficient together with the recorded vacancy facts)

`ElectionBecameInoperative` carries `electionId + onset` (Event/ElectionBecameInoperative.php:27–31). EM-GOV-065 (ADOPTED) requires: onset **at** the recorded vacancy event causing it, no declaration, no determiner. Evidence of sufficiency:
1. **The onset instant IS the causing event's instant by construction** — `InoperativeOnset::onsetFor` returns `$vacancyEventInstant`, never evaluation time (Policy/InoperativeOnset.php:31–33); verified in the executed scenario (onset=3000 = the second vacancy's instant) and by `ConditionSemanticsTest` test 23.
2. **The causing event is derivable, not stored:** the `CommitteeSeatVacated` facts are separately recorded input facts carrying seat identity, ground, reason and instant; replaying them in record order, the causing event is the one whose application first makes non-vacant < required. F-PROTO-1 (durable, append-only, complete, order-preserving in meaning) guarantees that replay is possible. Deriving the cause instead of storing it is the same posture EM-GOV-065 itself mandates for the condition ("no declaration, no determiner") — the derived-consequence event carrying seat identity would additionally imply single-seat causation the rule never defines (the in-code rationale, ElectionBecameInoperative.php:17–21, is consistent with G-1/G-4).
3. 065's text imposes the recording obligation on **when Inoperative begins**, not on duplicating the vacancy event's content inside the consequence event.

*Boundary of the verdict:* sufficiency assumes the protocol preserves the vacancy events and their order — an F-PROTO-1 property of the (not-yet-built, deliberately unprescribed) protocol store, not of this increment. If two vacancy events ever shared one recorded second, instant-correlation alone would be ambiguous, but arithmetic replay over record order still disambiguates.

### ④ Classification dispute (DecidedFailure algebra/ordering) — **REFUTED** (the implemented ordering is correct against the frozen corpus)

**Algebra verified.** The code's branch `accepts + unexpressedAll < r` with `unexpressedAll = constituted − expressed = constituted − accepts − objections` reduces to `constituted − objections < r` ⟺ **objections > constituted − required** — exactly the claimed algebra (Policy/GateIntervalClassification.php:56–66, the comment derives it correctly). At 3/2: objections > 1, i.e. two objections ⇒ DecidedFailure — the canonical qualified R-F2 case.
**Vacancy can never trip the branch:** vacancies do not appear in the formula; every unexpressed seat, vacant or not, is credited as a potential future accept (a replacement expresses where the seat has not — EM-GOV-066). Only after the decided-failure test fails does vacancy arithmetic separate Open from Unachievable — and Unachievable is reachable only through vacancies, never through objections alone.
**Ordering safe:** DecidedPass is checked first; simultaneous pass and decided-failure is arithmetically impossible (accepts ≥ r ∧ objections > n − r ⇒ accepts + objections > n, exceeding the seat count), so the ordering cannot mask a case.
**Pin-test coverage verified** (`test_decided_failure_is_a_pure_function_of_objections_never_of_vacancies`, AcceptanceGateDecisionTest.php:227–269): ✓ canonical R-F2 (two objections, no vacancy → DecidedFailure); ✓ vacancy-present example (one objection + two vacancies → Unachievable, not a decision); ✓ the occupy-the-vacancy proof pair (two objections + one vacancy vs the same with the third seat accepting — both DecidedFailure, proving the vacancy contributed nothing); ✓ vacancy-only impossibility → Unachievable, never DecidedFailure. EM-GOV-068's decided-failure vs mathematical-impossibility distinction and the qualified R-F2 reading are both honoured. 

### ⑤ Do-not-expand note (RecoveryProcess as pure clock) — **CONFIRMED** (no consequence authority leaked into the clock)

`RecoveryProcess`'s complete public API: `start · electionId · kind · policyBinding · pause · resume · readingAt · isExpiredAt` (verified — full file read, Recovery/RecoveryProcess.php). It models accrual (recorded intervals + active flag) and answers expiry **as a question** (`isExpiredAt` returns bool; produces no event, no state, imports no consequence type). Every consequence lives outside the clock in stateless `ExpiryConsequence` (P-6), which fires only after verifying the Election Rule's facts: correct period kind, not-Inoperative (for the terminal path), recovery-not-succeeded, and expiry-on-recorded-intervals — each precondition throwing `ExpiryConsequencePreconditionNotMet` otherwise (Policy/ExpiryConsequence.php:44–63, 79–88). The terminal/cancellation types are imported by P-6 only, never by the Recovery namespace. Tested: `test_expiry_consequences_are_rule_evaluated_never_clock_produced` exercises all four refusal preconditions plus both consequence paths.

---

## 3 · Test-quality findings (Task C)

**Rule expression — strong.** All 42 tests assert adopted business rules and cite them: EM-GOV-033 (test 1), 064 (2, 2b, 3, 16), 057 (4), 056 (5), 065 (6, 23), 036/038 with the exact governance-verified size table (7), 035/D-6 (8), 066 (9, 10, 11), 068 + qualified R-F2 (12, 13, pin), 031/005 (14), D-9/G-1 closed positions (15), 069 rendering + Discontinued≠Cancelled (17), ADR-T11 constrained surface (VacancyReason test), 050(b) (18), 062 disjoint clocks (19), 060 non-retroactive pause (20), 061 no-new-time (21), 059(a) (22), 059(b) halt retained (24), 063 three-facts + 058 + 062 (25), 059(c) never-a-deemed-decision (26), F-PROTO-1 properties 2/4/5/6/7 (Protocol tests), D-1/D-4/D-5/D-7 + repo layer rule (Structural tests). Assertions test **properties and refusals**, not implementation structure; the failure messages carry rule IDs.

**Vacuity risks — mostly low, three named:**
1. `test_every_domain_event_is_marked_non_canonical` silently skips any event class failing `class_exists` (StructuralGuardsTest.php:139–143); only an `assertNotEmpty` floor protects against total vacuity. A future event with a broken autoload mapping would escape the D-7/ADR-T11 scan unnoticed. (The deliberate non-freezing of the class count is documented and reviewer-sanctioned; the skip-on-autoload-failure is the residual hole.)
2. Several guards prove **absence via method-name regexes** (tests 3, 6, 10, 13, 18, 21) — meaningful only while behaviour keeps living in aptly-named methods; a hostile rename defeats them. Acceptable as tripwires; they are not the primary enforcement (type structure is).
3. `test_s06…` exercises `GateIntervalClassification::classify` directly with a raw vacancy list, bypassing the trusted-committee route the aggregate mandates (I-11). Legitimate for a policy-level test, but the aggregate-level I-11 guards themselves are untested (next paragraph).

**Untested guards (no invariant is unenforced, but these enforcement points have no red path):** `intervalState()`'s foreign-committee and denominator-mismatch rejections (AcceptanceGateDecision.php:171–181); `fromRecordedFacts`' rejection of another gate's/election's facts (lines 84–88); `AppointmentsAwaited`'s empty-list guard; `RecordedInstant`'s negative-epoch guard and `secondsUntil` forward-only guard; `PolicyBinding`'s blank-version/non-positive-duration guards; the `ElectionCommittee` duplicate-seat guard. All are single-line constructor/precondition throws — low risk, but a regression in any would currently pass the suite.

**No invariant is without a test** except I-6 (vacuous by design — nothing exists to test) and I-15's uniqueness half (unenforceable without persistence, which the boundary excludes); both are named in the A-8 table.

---

## 4 · New findings (outside the five observations)

| # | Finding | Class | Severity |
|---|---|---|---|
| **N-1** | **OPEN ∧ INOPERATIVE is reachable** (proven, §2①). Design §2b's "cannot arise" commentary is incorrect as written; the code is rule-faithful. Consequences needing governance: does 068's "no recovery period runs" gloss tolerate the restoration clock accruing under an OPEN gate? Design-doc commentary needs correction either way. | Governance question + design-doc correction | Medium — no code change indicated unless Governance rules the combination illegal |
| **N-2** | In the same scenario, a **single remaining member can complete a pass** (standing accept + own accept = 2 ≥ 2) while the Committee is arithmetically "unable to function", and nothing blocks `expressPosition` while Inoperative (AG-2 never sees the operational condition). R-F2's sentence "a single remaining member can neither pass nor decidedly fail" holds only where no standing positions exist. Whether position expression is permitted while Election Inoperative is not fixed by adopted text. | Governance question | Medium |
| **N-3** | **Unbounded free-text surfaces:** `RefusalRecord::$reason`/`$requestedAct` and `CommitteeSeatFilled::$appointeeReference` are non-blank-checked but have no length bound; the ADR-T11 constrained-surface discipline (design §5e) was implemented for `VacancyReason` only. A refusal reason at the post-counting gate is exactly the surface class EM-OPEN-091② flagged. | Engineering (hardening) | Low-medium — recommend a bounded-length rule mirroring VacancyReason in a future authorized slice |
| **N-4** | **B-7 reconstitution is realized for AG-2 only** (`fromRecordedFacts`); AG-1 `ElectionCommittee` and AG-3 `RecoveryProcess` carry the projection-of-facts obligation as repository doc contracts, with no replay constructor. Not a boundary violation (persistence is excluded from this increment), but the "state rebuildable from recorded facts" property is currently asserted, not demonstrated, for two of three aggregates. | Engineering (future slice) | Low |
| **N-5** | `RecoveryProcess::$active` is stored bookkeeping derivable from the last interval (`until === null`); cannot drift today (both mutate together) but is a redundancy the DD-1 posture would rather not carry. | Engineering (cosmetic) | Low |
| **N-6** | The untested guard list of §3 (all single-line precondition throws). | Test coverage | Low |

---

## 5 · File-access attestation

**Opened (read) for this verification:**
- All **56 production files** under `app/Contexts/Election/Domain/OperatingCore/` (complete contents, via cat/Read).
- All **7 test files** under `tests/Unit/Contexts/Election/OperatingCore/` (complete contents).
- `app/Contexts/Election/Domain/ElectionId.php` (head) and `app/Contexts/Election/Domain/DomainEvent.php` (complete) — the core's only two non-SPL external imports, purity-checked.
- `docs/publicdigit/architecture/2026-08-17-EM-ARCH-001-model-a-operating-core-design.md` — complete.
- `docs/publicdigit/architecture/2026-08-17-EM-ARCH-001-dependency-reconciliation.md` — complete.
- `docs/publicdigit/business_rules/ELECTION_MANIFESTO.md` — rule rows read: EM-GOV-005, 006, 010 (grep context), 025–036, 038–069 (lines 111–185); EM-VOC-004/005/007/008 block (lines 219–231); F-PROTO-1 section + EM-GOV-004 correction context (lines 186–214); ADR-T11 row (line 369) and EM-OPEN-091 row (line 508) via grep.
- Git metadata: `git show --stat a31f54f1`, `git show --stat 4b707798`, `git log`, `git status --porcelain`, `git diff <commit> HEAD --stat` for both directories.

**NOT opened, per commission:** `docs/publicdigit/implementation/2026-08-17-EM-IMPL-001-first-increment-completion-report.md`; the readiness report; every file in `docs/publicdigit/reviews/` whose name contains "EM-IMPL". No testimony was read; the tests' own doc-comment references to "readiness report §E" test numbers were read as part of the test files but the referenced document was not opened.

**Executed:** `php artisan test tests/Unit/Contexts/Election/OperatingCore` (42 passed, 2420 assertions); read-only grep/find/wc; one scratch PHP script in the session scratchpad deriving observation ① against the committed classes (no repository file created, modified or deleted other than this report).

---

## 6 · Overall conclusion

**The committed increment is SUITABLE for PO acceptance within the ratified boundary, with two conditions.** All eight constraint checks PASS on evidence; the full suite passes (42/2420); the D-1 wall, the no-time-on-OPEN posture, the derived-never-stored discipline, the EM-GOV-069 vocabulary, ADR-T11's surfaces, framework freedom and boundary containment are each verified against the code, not against testimony. Of the five queued observations, three are refuted with evidence (② count, ③ causal linkage, ④ classification algebra — the implementation is correct against the frozen corpus in each), one is confirmed in the implementation's favour (⑤ — no consequence authority in the clock), and one is confirmed **against the design commentary, not against the code** (① — OPEN ∧ INOPERATIVE is reachable and each derivation is individually rule-faithful; the design §2b "cannot arise" argument fails on standing votes under EM-GOV-066).

**Conditions:**
1. **Governance must adjudicate N-1/N-2 before any slice builds behaviour on the OPEN ∧ INOPERATIVE region** (the 068 "no recovery period runs" gloss; position expression while Inoperative). Acceptance of this increment does not require the answer — no committed code takes a position on either question — but the design document's §2b commentary should be corrected so the false impossibility claim does not mislead the next slice.
2. **N-3…N-6 enter the backlog as named items** (free-text bounds, AG-1/AG-3 replay, `$active` redundancy, untested guards); none blocks acceptance.

This report supplies evidence and a recommendation only; acceptance is the PO's act, not this lane's (R-34).

**Traceability.** C-5 commission (PO/ARB, 2026-08-17, verbatim above) · commits `a31f54f1` / `4b707798` · EM-ARCH-001 approved design + dependency reconciliation · Election Manifesto ADOPTED rows as read (§5) · ADR-T11 · F-PROTO-1 · P-2H.

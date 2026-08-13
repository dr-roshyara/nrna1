# Election Context — governance current-state report

**Type:** Governance / architecture reconciliation · **Date:** 2026-08-13 · **Programme:** IERVP (Session 2)
**Baseline:** the [ticket authority matrix](2026-08-13-election-only-ticket-authority-matrix.md) — consumed, not repeated.
**⛔ Analysis only. No production code, test, fixture, `ElectionConstitution`, aggregate/entity/repository, or domain-rule change. No decision resolved (`BR-1.12`, `EM-OPEN-021`, timestamp semantics all untouched). No authorization granted. No settled decision reopened.**

---

## 1 · Executive summary

> **The four layers — DOMAIN, AUTHORITY, IMPLEMENTATION, VERIFICATION — are aligned for the core voting-activation rule as of today, and misaligned in exactly three places: voter-eligibility resolution (`65`+`69`), admission-state authority (`BR-1.12`), and time semantics (`59`+`67`).**
>
> `EM-VOT-002` landed (`f2c2cc4e`) **within its grant, on both paths, with RED→GREEN→regression evidence, without touching `EM-OPEN-021`** — awaiting Session 1's independent verification. **Election-Only is not "ready", but what remains is now enumerable and small:** one authorized repair, one cheap decision, one conditional decision pair, one verification pass.
>
> **Two new governance findings** from this reconciliation: **(a)** `SD-15` turns out to carry a **documented deliberate decision from 2026-05-22** that I had missed — evidence balance shifted; **(b)** a Session 1 commit records *finding, fixing and guarding* a privilege escalation — an **apparent separation deviation, flagged not adjudicated** (§14).

## 2 · Current Election Context architecture

**Current-state map (responsibilities, not redesign):**

| Layer | Components | Responsibility |
|---|---|---|
| **1 · Election domain** | `Domain/Election/Constitution/*` (`ElectionConstitution`, `ConstitutionalArticlesSnapshot`) · `Enum/` (`ElectionLifecycleState`, `VoterSourceStrategy`) · `Security/*` (`ADR-T11`-shaped evidence types) | constitutional rules registry · frozen participation snapshot · mode sovereignty · trust/evidence value objects |
| **2 · Election application** | `Application/Election/Services/` (`ElectionLifecycleEngineImpl`, `ConstitutionalTransitionGuard`, `ElectionClockService`) · `Capabilities/*` (resolver, snapshot, denial reasons) · `Security/` (`TrustPolicyEvaluator`) · `Facades/ElectionLifecycle` | **computed** lifecycle derivation · precondition evaluation · capability computation (ADR-001/004/005) · observational trust evidence |
| **3 · Constitutional governance** | `ElectionConstitution::RULES` — 15 actions, 4-field policy shape; suspension overlay (`suspended_at` fact + derived state) | *what lifecycle action is allowed, by whom, when, under what preconditions* |
| **4 · Lifecycle** | engine priorities (suspension override → … → **voting-active now gated on approved candidates** → …) + guarded transitions | two paths into state: **computed** (clock) and **commanded** (guard) — both now carry `EM-VOT-002` |
| **5 · Election-Only entitlement** | `ElectionMembership` (record) · `ElectionOnlyPolicy` (stub + `decideForContext`) · `VoterImportService` · `VoterEligibilityService` | admission + the entitlement record; **entitlement semantics adopted (27 rules) but only partly expressed in code** |
| **6 · Voter eligibility** | `User::isVoterInElection()` *(the live gate)* · `EnsureElectionVoter` / `VoteEligibility` middleware · dormant: `scopeEligible()`, `isEligible()`, `VotingEligibilityPolicy` (0 callers) | **four definitions, one live** — the `AD-2` authoritative-engine question remains open |
| **7 · Election security** | `TrustPolicyEvaluator` (observational) · `ADR-T11` anonymity · codes/`VoterSlug` credential chain · S5 guard *(new, via Session 1 — §14)* | trust evidence · no voter↔vote linkage · credential possession ≠ entitlement |
| **8 · Membership dependencies** | `organisation_users` + `user_organisation_roles` *(technical linkage — adopted vocabulary: NOT Organisation Membership)* · `Member` aggregate (0 rows) · `Membership*` events (no subscriber) | admission-time linkage only in EO; everything deeper is **Full Membership, frozen** |
| **9 · Shared infrastructure** | `BelongsToTenant` *(platform-org fallback — the `62`/`65`/`69` defect family)* · caches (`voter_cache_ttl` 300s, tenant-blind key) | 🔴 the one shared mechanism repeatedly corrupting election-scoped answers with ambient context |
| **10 · Legacy/compat** | `is_voter`/`can_vote` flags (no DB columns) · `ElectionUser` (dead, now parseable) · `VoterSlugStep` (unparseable, unregistered) · legacy `status` column (58's migration) | traceability + `PBDIGIT-58` scope; **not authority, not prior art** |

## 3 · Election-Only boundary

Adopted and unchanged: **entitlement = the `ElectionMembership` record** (election-scoped, created at admission, no Organisation Membership required/created — `EM-EO-001`…`004`, `Q-B1` closed) · stored in `election_memberships` · **resolved** by `User::isVoterInElection()` · **authorized** at `EnsureElectionVoter`/`VoteEligibility`. **Does ambient context incorrectly participate? YES — that is precisely `65`** (the tenant scope's platform-org fallback corrupts election-scoped resolution) **and `69`** (a tenant-blind cache key makes the wrong answer persist 300s in both directions). **Genuinely required from Membership:** the technical linkage rows at admission, nothing more. **Outside Election-Only / deferred:** the `Member` aggregate, organisation-driven suspension, `FM-1`…`15`, `EM-FM-001`…`006`. **No contamination found** — the `f2c2cc4e` delta touches no membership concept (measured at Watch 02 and re-confirmed against the commit).

## 4 · `ElectionConstitution` boundary

15 actions, uniform `allowed_states · allowed_roles · preconditions · target_state`. **Now contains `has_approved_candidates` on `open_voting`** *(the authorized `EM-VOT-002` expression, with rule citation)*. Evaluators live in `ConstitutionalTransitionGuard` (`has_posts`/`has_voters`/`has_chief`/`has_approved_candidates`/`voting_window_defined`/`timezone_set`/`capacity_eligibility`). **Bypass reality unchanged:** the computed path never consults the guard — which is exactly why the engine carries the second enforcement point. **Belongs there:** constitutional workflow. **Does not:** entitlement/membership/suspension-of-voter/credential/participation (ARB-rejected). **`EM-VOT-002` enforcement answer: C — BOTH paths** *(commanded via precondition; computed via the engine's priority-5 condition)*.

## 5 · Lifecycle authority chain

```
BUSINESS RULE          EM-VOT-002 (Manifesto §4a, adopted)
      ↓
GOVERNANCE             SD-14 ruling + explicit implementation grant
      ↓
CONSTITUTION           open_voting → has_approved_candidates        (commanded path)
      ↓
APPLICATION            ElectionLifecycleEngineImpl priority 5       (computed path — where the Constitution is NOT the enforcement site, by design)
      ↓
RUNTIME                voting_active unreachable without an approved candidate
      ↓
VERIFICATION           Session 3's RED→GREEN evidence recorded · Session 1 independent pass PENDING
```

**Residual authority dispute in this chain: WHICH clock.** The engine reads `voting_*` timestamps; legacy paths read `start/end_date`; they disagree on live data (`59`), and the stored instants themselves are off by 60–120 min DST-variable (`67`). **The chain is structurally aligned; its temporal input is not yet authoritative.**

## 6 · Entitlement / eligibility authority chain

```
BUSINESS RULE          EM-ENT-001…007 · EM-EO-001…004 (adopted)
      ↓
GOVERNANCE             D-ENT chain closed · BR-1.12 OPEN (admission state) · Q3 OPEN (exercisability)
      ↓
CONSTITUTION           — (deliberately: voter-level rules stay OUT, ARB-rejected)
      ↓
APPLICATION            isVoterInElection() live gate · admission via import/assign
      ↓                🔴 corrupted by ambient tenant context (65) + tenant-blind cache (69)
RUNTIME                valid entitlement intermittently unrecognisable
      ↓
VERIFICATION           no test asserts a suspended voter cannot vote; 65's defect measured, not estate-covered
```

**This is the misaligned chain.** The rules are adopted; the implementation resolves them through a context-dependent mechanism the rules contradict.

## 7 · Election security boundary

`ADR-T11` intact — nothing in the new work approaches voter↔vote linkage. Credential possession remains outside the entitlement formula (adopted). **S5** (privilege escalation in `transitionTo`) was found **and fixed** by Session 1 — the *fix* is welcome; the *actor* is a §14 flag. Coherence gaps (`Q-E1`: credential issued to suspended voter) remain **recorded, deferred, unauthorized**.

## 8 · `PBDIGIT-64` / `EM-VOT-002` status

✅ **IMPLEMENTED — within grant, on the evidence of the commit itself** (`f2c2cc4e`):

* **Both paths** — engine priority 5 + `open_voting` precondition; **exactly the two production files** the grant anticipated; **no new class/method** (pre-existing evaluator and helper reused).
* **TDD evidence recorded:** RED first (4 computed + 2 commanded failures against unmodified production) → minimal GREEN (8/8) → regression to the **exact 17-name pre-existing baseline**, with the remaining 50 unit/arch failures **proven pre-existing by revert-comparison**.
* **Fixtures:** classified over **34 call sites** — zero tests relied on candidate absence; the corrections change no assertion. *(Legitimate repair, not meaning-change — on the evidence stated; independent confirmation is Session 1's.)*
* **`EM-OPEN-021` untouched** — the commit's own words: *"no substitute state is chosen… fallback semantics remain an open PO decision."* ✅ **The domain decision was not implicitly resolved.**
* **Pre-registered `SD-15` check: PASS** — the `has_committee_members` assertion was **not altered**; Session 3's change to that test file only adds an approved-candidate fixture.
* **Status: awaiting Session 1's independent verification. Session 3 has not self-certified** — its commit says so explicitly.

## 9 · `PBDIGIT-65` + `69` status

🔴 **THE authoritative unaddressed Election-Only blocker.** Full trace already on record (G-1 review · `69` ticket · admission gate): voter → entitlement row → **resolution via a tenant-scoped query whose scope falls back to the platform org without context** → wrong answer → **cached 300s under a tenant-blind key, leaking in both directions**. Responsible components: `BelongsToTenant` + `User::isVoterInElection()`'s cache. **Genuinely coupled:** yes — `65` is the cause, `69` the persistence; **`62` was the same mechanism family, already repaired**, which is precedent that a bounded fix is tractable. **Constitutional rule:** none needed — adopted entitlement rules already contradict the behaviour. **New business decision required:** only `Q-D1`-adjacent design choice (option A/B/C recorded in `69`). **Smallest authorized slice:** repair of resolution + cache, with the cross-tenant regression test `69` specifies. **Authorization package: effectively ready — the `69` ticket's D-1 options + acceptance criteria are the package. Not granted; not implemented.**

## 10 · `PBDIGIT-62` status

✅ Repaired 2026-08-07 under explicit PO authorization, RED→GREEN proven, 27→25 failures (2 fixed, 0 broken); the protecting tests exist (`DashboardResolverElectionPriorityTest`). **Same tenant-scope family as 65/69** — its fix addressed the subquery scoping at the two consumers, **not** the general mechanism, so no residual claim is made beyond its own scope. Election-Only: applies (post-vote routing). **Estate confirmation belongs to Session 1; not asserted here.**

## 11 · `PBDIGIT-59` + `67` status — one decision set

Timestamps in play: `voting_starts_at`/`voting_ends_at` *(drive the engine + `EM-VOT-002`'s computed gate)* · legacy `start_date`/`end_date` *(legacy queries; disagree on 4 of 4 measured elections)* · `elections.timezone` *(2/10 set; presence-checked, never used to convert)* · input parsed as UTC while meaning browser-local *(60–120 min DST-variable error, measured)*. **The missing business decision, precisely: what does an officer-entered schedule time MEAN, and which timestamp set is constitutional.** `D-1`…`D-4` + the `59` decision **are one set** — deciding display (`EM-OPEN-018`) without input, or input without timestamp authority, would decide half a rule. **No option selected here.** Package: the `67` ticket + `59` ticket already carry options and consequences.

## 12 · `BR-1.12` status

Admission currently: import writes `'active'`; assignment defaults `'active'`; **no production writer of `'invited'`** — while schema, UI (pill + `invited → active` action), tests (`makeMembership('invited')`) and the officer guide all expect the gate. Constitutional involvement: none (`admit` = 0). **Blocked until decided:** the admission slice — its tests, its import/assign behaviour, and any `invited` disposition. **The danger remains asymmetric: building "keep current behaviour" IS Option A by default.** Decision package unchanged, on the PO's desk.

## 13 · `EM-OPEN-021` status

**OPEN · honoured.** The landed implementation refuses `VotingActive` and **falls through without choosing a substitute state**, saying so in code comment and commit message. The fall-through result remains **a mechanical outcome, not a business rule** — the ARB qualification stands verbatim in the Manifesto row. **Not actionable until the PO's lifecycle-state decision.**

## 14 · Session responsibilities — one flag

| Session | Mandate | Current conformance |
|---|---|---|
| **Session 1** — verification | classify, verify, never implement | ⚠️ **One apparent deviation, flagged not adjudicated:** commit `9ee15cc6` records a privilege escalation *"found, **fixed** and guarded"* — the verification stream **implementing a production fix**. Mitigating context visible in the commit: a security defect (S5), honestly labelled, with its own guard test, and `"No product story applies"` stated. **Whether the PO authorized it inside Session 1's stream is not visible to Session 2 — the PO should confirm or regularise it.** *(Their self-corrections in the same commit — grep-attribution errors caught before entering findings — are exemplary.)* |
| **Session 2** — governance | authority, decisions, boundaries, no implementation | ✅ this report; nothing implemented; nothing decided |
| **Session 3** — implementation | authorized slices, strict TDD | ✅ `f2c2cc4e` — within grant, evidence recorded, handed to Session 1, no self-certification |

## 15 · Election-Only readiness matrix — capability level

| # | Capability | Class | Business authority | Constitutional | Implementation | Verification | Blocker / open decision | Session |
|---|---|---|---|---|---|---|---|---|
| 1 | Election creation | **CORE** | epics; runtime-exercised | approval workflow rules | ✅ works (IERVP runtime) | partial (estate) | — | 1 verify |
| 2 | Election configuration | **CORE** | partial — time rules undecided | `voting_window_defined`, `timezone_set` | ⚠️ input stores wrong instants (`67`) | measured, not estate-covered | 🟡 `59`+`67` | PO decide |
| 3 | Admission / entitlement | **CORE** | ✅ adopted (`EM-EO-*`, `EM-ENT-*`) | none (deliberate) | works; **state semantics undecided** | runtime-verified (EO) | 🔴 `BR-1.12` | PO decide |
| 4 | Nomination | **CORE** | Constitution (`apply_candidacy`) | ✅ | ✅ runtime-verified | estate rows exist | — | 1 |
| 5 | Candidate approval | **CORE** | Constitution (`complete_nomination` + `has_approved_candidates`) | ✅ | ✅ runtime-verified | ✅ guard tests | — | 1 |
| 6 | Voting window | **CORE** | 🔴 **disputed clocks** | `voting_window_defined` | implemented on undecided semantics | `67` measured | 🟡 `59`+`67` | PO |
| 7 | Voting activation | **CORE** | ✅ `EM-VOT-002` | ✅ precondition landed | ✅ **both paths** (`f2c2cc4e`) | 🟡 **Session 1 pending** | verification only | 1 |
| 8 | Voter eligibility resolution | **CORE** | ✅ adopted entitlement rules | none (deliberate) | 🔴 **context-corrupted** (`65`+`69`) | measured, defect | 🔴 **repair authorization** | PO → 3 |
| 9 | Ballot opening | **CORE** | adopted + `ADR-T11` | lifecycle-gated | ✅ works | runtime-verified | *(coherence `Q-E1` deferred)* | 1 |
| 10 | Ballot submission | **CORE** | one-vote via codes | — | ✅ (`PBDIGIT-38` fixed & verified) | verified then | — | 1 |
| 11 | One-vote enforcement | **CORE** | codes UNIQUE + exhaustion | — | ✅ | partial | — | 1 |
| 12 | Suspension / resume | **CONDITIONAL** | election-level ✅ · voter-level rules open (`BR-1.13`, `Q3`) | election-level ✅ | voter-level works **incidentally** | 🔴 no test asserts suspended-voter-cannot-vote | 🟡 `BR-1.13` + `Q3` — **not needed for first EO delivery unless PO says so** | PO |
| 13 | Results counting | **CORE** | Constitution (`close_voting` → counting) | ✅ | implemented | unknown to this stream | — | 1 |
| 14 | Results publication / visibility | **CONDITIONAL** | 🔑 PO ruling 2026-08-06 (publication immutable · visibility hide/show) | `publish_results`/`archive` ✅; visibility capability **missing** | partial; `V-1` integrity defect (`60`) | — | 🟡 `60 D-2` authorization; `EM-OPEN-013` confirmation | PO |
| 15 | Audit / evidence | **SUPPORTING** | `BR-1.5`/`1.6` open | — | 🔴 governance acts unaudited; facility unused | — | 🟡 defaultable decisions | PO |
| 16 | Security / trust controls | **SUPPORTING** | `ADR-001`/`T11` ✅ | snapshot ✅ | trust evidence observational; S5 guarded | partial | — | 1 |
| — | Full Membership | **DEFERRED** | frozen | — | dormant (`members` 0) | — | — | later phase |

**Justification of classes:** CORE = the voter-facing path create→admit→nominate→approve→open→vote→count; CONDITIONAL = needed for a *complete* product but a PO call whether first-delivery-gating (12, 14) or gated on decisions (2, 6); SUPPORTING = quality/assurance layers that don't change what a voter can do; DEFERRED = the frozen mode.

## 16 · Critical path

```
NOW ──► Session 1 independently verifies f2c2cc4e (cap 7)          [only verification]
   ──► PO authorizes 65+69 repair → Session 3 fixes (cap 8)        [only authoritative blocker]
   ──► PO decides BR-1.12 → admission slice authorized (cap 3)     [cheapest decision]
   ──► PO decides 59+67 as ONE time-semantics set (caps 2,6)       [conditional pair]
        └──────────────► ELECTION-ONLY COHERENT: domain, authority, implementation
                         and verification aligned for caps 1–11, 13
```

## 17 · Non-critical work

`50` display (needs `EM-OPEN-018` anyway) · `51` error pages · `58` (unless the login-redirect candidate is adopted) · `60` (unless the PO prioritises `V-1` integrity) · voter-suspension completion (`BR-1.13`/`Q3`/`Q-E1`/`Q-E2`) · audit hardening (`BR-1.5`/`1.6`) · `48`/`63` retirement · `PBDIGIT-70` residue · Officer Guide recognition · Manifesto ratification (`EM-OPEN-017`).

## 18 · Governance gaps

1. 🔴 **`SD-15` — my package was incomplete, and I correct it here.** Commit **`086cb3f5` (2026-05-22)** shows `complete_administration`'s precondition was **deliberately changed FROM `has_committee_members` TO `has_chief`**, documented as *"only one active chief officer is now required"* and explicitly tied to *"enabling election-only mode"* — **the same deliberate-decision pattern as the FK drop, found the same way I should have looked the first time: commit history.** The commit even claims it *updated the unit test* — yet the current test **still asserts `has_committee_members`**, so the assertion either survived or was restored against a documented change. **Evidence balance shifts toward reading B (chief suffices) — with a documented deliberate decision behind it — while the PO ratification `SD-15` requires is still outstanding.** *(Watch report 01 §3.1's "no authority anywhere" is superseded by this find.)*
2. The adopted rules' canonical home remains unratified (`EM-OPEN-017`) — livable, known.
3. Governance acts remain unaudited (`BR-1.5`/`1.6` defaultable).
4. §14's Session 1 deviation needs PO confirmation or regularisation.

## 19 · Architectural risks

1. 🔴 **`BelongsToTenant`'s platform-fallback is a systemic pattern, not a spot defect** — three confirmed hits (`62` fixed, `65`, `69`); other consumers unaudited. The `65`+`69` slice should state whether it fixes instances or the pattern — **a scoping question for the authorization, not decided here.**
2. **Undecided time semantics beneath a now-enforced activation rule** — `EM-VOT-002` gates on a clock whose authority (`59`) and stored values (`67`) are both disputed. The rule is right; its input is not yet trustworthy.
3. **`EM-OPEN-021`'s mechanical fall-through will be user-visible** — someone will eventually read the resulting state as intended. The register entry guards the record; only the PO decision removes the ambiguity.
4. **Dormant competing eligibility definitions** (4 for one concept) — inert but a standing trap until `AD-2`/`Q3`.

## 20 · Recommended next actions

**① Session 1: verify `f2c2cc4e`** *(nothing else gates it)* · **② PO: authorize the `65`+`69` repair slice** — scope question in §19.1 included · **③ PO: decide `BR-1.12`** · **④ PO: decide `59`+`67` as one set** · **⑤ PO one-liners:** `EM-OPEN-013` disposal (via the 2026-08-06 ruling) · `SD-15` (now with the 2026-05-22 evidence) · confirm/regularise §14 · **⑥ retire `48`/`63` to traceability.**

---

## Final decision table

| Area | Current state | Authority | Blocker? | Decision needed? | Session responsible |
|---|---|---|---|---|---|
| **EM-VOT-002 / PBDIGIT-64** | ✅ implemented both paths (`f2c2cc4e`), RED→GREEN→regression recorded, `EM-OPEN-021` untouched | adopted rule + explicit grant | **no — verification pending** | none | **Session 1** (verify) |
| **PBDIGIT-65** | 🔴 defect present; ambient context corrupts entitlement resolution | adopted entitlement rules contradict behaviour (measured) | 🔴 **YES — the authoritative EO blocker** | **repair authorization** (+ pattern-vs-instance scope) | **PO → Session 3** |
| **PBDIGIT-69** | 🔴 tenant-blind cache persists 65's wrong answers 300s, both directions | measured; options A/B/C on record | YES (with 65) | **same authorization**; `D-1` design choice | **PO → Session 3** |
| **PBDIGIT-62** | ✅ repaired (authorized, RED→GREEN); protecting tests exist | PO-authorized fix record | no | none | **Session 1** (estate confirmation) |
| **BR-1.12** | admission writes `active`; four layers expect `invited`; no production writer | `BUSINESS RULE NOT SPECIFIED` | 🔴 blocks the **admission slice** | ✅ **the decision itself** | **PO** |
| **PBDIGIT-59** | lifecycle and legacy clocks disagree 4/4 | decision required on its face | conditional-strong | ✅ timestamp authority | **PO** |
| **PBDIGIT-67** | stored instants wrong 60–120 min, DST-variable | `D-1`…`D-4` reserved for PO | conditional-strong | ✅ `D-1`…`D-4` (with 59, one set) | **PO** |
| **EM-OPEN-021** | fall-through state = mechanical outcome; implementation honoured the boundary | ARB qualification in force | no (recorded) | ✅ lifecycle-state decision, when PO chooses | **PO** |
| **ElectionConstitution** | 15 actions + the authorized `EM-VOT-002` precondition; home status settled (ADR-001); contents ≠ automatic authority | ADR-001 (pattern/home) | no | none *(SD-15 may later adjust one precondition)* | **Session 2** (guard) |
| **ElectionMembership** | record exists; 27 adopted rules govern it; expression gaps (`IG-*`) unauthorized; `status` overload load-bearing | `D-ENT` chain adopted | via `BR-1.12`/`Q3` only | `BR-1.12` now; `Q3` later | **PO → Session 3** |
| **Election-Only mode** | **coherent except: 65+69 · BR-1.12 · 59+67 · Session-1 verification of 64** | 27 adopted rules + Constitution + grants | see the four | the four above | **all three, as mapped** |

> **Answer in the required terms: the layers are ALIGNED for voting activation (pending independent verification), MISALIGNED for eligibility resolution (implementation contradicts adopted rules), and UNDECIDED for admission state and time semantics (authority absent, implementation waiting). That — not test counts — is the current definition of Election-Only readiness.**

**Traceability:** `f2c2cc4e` *(full message quoted in §8)* · `086cb3f5` (2026-05-22, `SD-15` history) · `9ee15cc6` (§14 flag) · `tests/Unit/Application/Election/ConstitutionalTransitionGuardTest.php:190-197` *(assertion unaltered — pre-registered check PASS)* · the ticket authority matrix · Watch reports 01/02 · the G-1 review · the `65`/`69`/`62`/`59`/`60`/`67` tickets · Manifesto §§4a, 9 · measured this pass: no membership concept in the `f2c2cc4e` delta.


---

# GOVERNANCE WATCH — POST `EM-VOT-002` INDEPENDENT VERIFICATION *(dated update, 2026-08-13 — history above unchanged)*

**⛔ Analysis only. Nothing implemented, repaired, resolved or authorized. `EM-OPEN-021` remains open as a hard boundary.**

## U1 · Corrected `EM-VOT-002` status

| | |
|---|---|
| **Business rule** | **ADOPTED** |
| **Implementation** | **COMPLETED WITHIN AUTHORIZATION** (`f2c2cc4e`) |
| **Independent verification** | ✅ **VERIFIED ON BOTH PATHS by Session 1** — command (`open_voting`) and computed lifecycle, same approved-candidacy predicate |
| **Remaining** | **`EM-OPEN-021`** — unresolved lifecycle-semantics question. **`EM-VOT-002` is NOT reopened or weakened by it** |

**§1/§16's "pending verification" is superseded; the earlier "critical path" sequencing is WITHDRAWN as over-compressed (U3).**

## U2 · `EM-VOT-002` ≠ `EM-OPEN-021` — with the new observable behaviour

| | `EM-VOT-002` | `EM-OPEN-021` |
|---|---|---|
| Question | *Can an election become `VotingActive` without an approved candidate?* | *What is the legitimate lifecycle meaning of an election whose window is open but which cannot satisfy the invariant?* |
| Status | **ADOPTED · IMPLEMENTED · VERIFIED — NO** | **NOT YET DECIDED** |

**New evidence (Session 1):** window open + zero approved candidates → `VotingActive` correctly refused → derivation falls through → **`InvalidElectionStateException`**.

> **That is CURRENT TECHNICAL BEHAVIOUR, not an adopted business rule.** `EM-OPEN-021` upgrades from *"we don't know what happens"* to *"we know what the implementation does; we do not know whether that is the intended domain semantics."* **The exception must not become the de facto answer** — no fallback state (`setup_nomination`, holding state, cancellation, special `close_voting`, new lifecycle state) is recommended or authorized. ⚠️ **Session 1's "may be unmanageable — unable even to close voting" is a POTENTIAL LIFECYCLE DEAD-END, untraced — measurement assigned to Session 1, not called a defect.**

## U3 · Critical path UNBUNDLED — seven independent concerns

*(The previous "verify → authorize 65+69 → decide BR-1.12 → decide 59+67" sequencing bundled distinct concerns; each now stands alone.)*

| | Business question | Authority | Architectural owner | EO-relevant? | Implementation | Evidence | Open decision | Impl. authorization required? |
|---|---|---|---|---|---|---|---|---|
| **A · EM-VOT-002** | candidate before voting | adopted + grant | Constitution (expression) + lifecycle engine (computed) | YES — core | ✅ complete | RED→GREEN→regression + independent verification | none | ✅ **was granted; consumed** |
| **B · EM-OPEN-021** | lifecycle meaning of window-open/zero-candidate | **none** | lifecycle semantics — **owner is the PO decision itself** | YES — user-visible | exception by fall-through | Session 1 observation | ✅ **the lifecycle-state decision** | ⛔ none until decided |
| **C · 65** | must a valid entitlement be recognised regardless of ambient context? | adopted entitlement rules (`EM-ENT-001`, `EM-EO-*`) | **contested — see U4 Q4** | YES — measured on the EO runtime path | defect present | controlled A/B | repair scope (U4) | ⛔ **NOT YET — evidence package only** |
| **D · 69** | may a cached eligibility answer cross tenant/context boundaries? | same rules; options A/B/C on record | same as C | YES | defect present | measured both directions | design choice (`69 D-1`) | ⛔ **NOT YET** |
| **E · BR-1.12** | admission state: `active` vs `invited`→approval | **BUSINESS RULE NOT SPECIFIED** | admission workflow | YES — blocks admission slice | production=`active`; 4 layers expect `invited` | full package on record | ✅ the decision | after the decision |
| **F · 59** | which timestamp set is constitutional? | **DECISION REQUIRED** | election lifecycle (clock authority) | YES — the engine + `EM-VOT-002` gate read it | two clock sets disagree 4/4 | measured | ✅ timestamp authority | after |
| **G · 67** | what does an officer-entered time MEAN? | `D-1`…`D-4` reserved | scheduling input boundary | YES — stored instants wrong 60–120 min | defect present | measured (Carbon executed) | ✅ `D-1`…`D-4` | after |

**F and G remain SEPARATE decisions** — F is *lifecycle-boundary clock authority*, G is *input interpretation* (with display split out as `EM-OPEN-018`). They **interact** (deciding one constrains the other) and should be *presented together*, **decided as themselves** — the earlier "one set" phrasing is corrected to *"one presentation, two decisions."*

## U4 · `65`+`69` — evidence assessment (NOT an authorization)

**The eleven questions:**

| # | Question | Answer |
|---|---|---|
| 1 | Exact adopted rule `65` violates | `EM-ENT-001` *(entitlement is the election-specific record)* + `EM-EO-001`…`003` — nothing in the adopted set makes recognition depend on ambient browser/tenant context |
| 2 | Exact adopted rule `69` violates | the same, in cached form — plus it defeats *any* future fix of `65` for 300s windows |
| 3 | Business capability | **voter-eligibility resolution** (readiness cap 8) |
| 4 | **Bounded-context owner** | 🟡 **UNRESOLVED — deliberately.** The live gate is `app/Models/User::isVoterInElection()`; candidate owners exist in `app/Contexts/Elections` (stub policies) and `app/Contexts/Membership` (`VotingEligibilityPolicy`, 0 callers); `AD-2` (authoritative engine) is an open ARB question. **Folder structure is not accepted as authority (U8)** |
| 5 | Required by Election-Only? | **YES** — it is the ballot gate on every real slug route |
| 6 | On the EO runtime path? | **YES — measured at runtime** (G-1: the gate refused/admitted real requests) |
| 7 | Defect class | **(d) infrastructure/context-propagation defect** *(the `BelongsToTenant` platform fallback + a tenant-blind cache key)* **surfacing as (b) a shared eligibility defect.** NOT (a) EO-specific *(the mechanism is mode-blind)*, NOT (c) Membership *(no `Member` concept involved)*, NOT (e) *(the rules are clear; the mechanism disobeys them)* |
| 8 | Would fixing require changing Membership concepts? | **NO** — on current evidence, the fix is in resolution/caching, not in any membership model |
| 9 | Would it change `ElectionConstitution`? | **NO** |
| 10 | New aggregate/entity/repository? | **Not necessarily** — options range from key-scoping to context-free resolution; **the choice is design, deferred** |
| 11 | Would it alter Full Membership behaviour? | ⚠️ **The mechanism is shared, so a fix touches both modes' resolution path.** Under `EM-ENT-005` (one entitlement concept) that is *consistent*, not contamination — **but the authorization must say so explicitly** |
| — | **Local defect vs systemic pattern** | **SYSTEMIC PATTERN, scope unbounded**: 3 confirmed instances (`62` fixed · `65` · `69`); other `BelongsToTenant` consumers **unaudited**. **Proposed scope definition required BEFORE authorization: (i) fix the two eligibility instances only, or (ii) audit-then-fix the pattern.** Neither selected |

> **Authorization status: ⛔ NOT GRANTED, NOT REQUESTED.** Package-completeness blockers: the **owner question (Q4)** — which the architecture archaeology must settle — and the **scope question (i vs ii)**. Both are named; neither is decided here.

## U5 · `BR-1.12`

**KNOWN:** production admission writes `active` (import explicit, assignment by default); schema, UI, tests and documentation all expect `invited`→approval; no production writer of `invited`; the Constitution and `ADR-002` are silent; clause 10's "directly" does not settle it. **UNKNOWN:** which workflow the business intends; what the approval gate's risk/workload trade is worth. **DECISION FOR THE PO:** Option A *(immediately `ACTIVE`)* vs Option B *(`INVITED`→approval→`ACTIVE`)* — consequences tabled in the admission gate §0.6. **Not inferred from anything; not encoded.**

## U6 · `59` + `67`

**What each actually concerns:** `59` = **lifecycle-boundary clock authority** *(which stored set governs)* · `67` = **scheduling-input interpretation** *(what the officer's entry means; storage is currently mislabelled UTC)* · display = **`EM-OPEN-018`**, separate · fallback-on-detection-failure = part of `EM-OPEN-018`. **EO-required?** `59`: **YES-conditional** — the engine and the now-verified `EM-VOT-002` gate read this clock; with 4/4 disagreement its authority is undecided, so window meaning is undecided. `67`: **YES-conditional** — stored instants measurably wrong. **Neither blocks admission or activation *mechanics*; both block trustworthy window semantics.** Presented together, decided as two.

## U7 · Election-Only readiness — rebuilt

| Capability | Business rule | Owner (bounded context) | Current implementation | EO-required? | Status | Decision req.? | Impl. authorized? |
|---|---|---|---|---|---|---|---|
| Election creation | approval workflow (Constitution) | Election | works (runtime-verified) | **REQUIRED** | OK | no | n/a |
| Admission / approval | `EM-EO-001`…`003`; state = `BR-1.12` | Election | works; state semantics undecided | **REQUIRED** | 🟡 decision-gated | ✅ `BR-1.12` | ⛔ no |
| Nomination | Constitution (`apply_candidacy`) | Election | works (runtime-verified) | **REQUIRED** | OK | no | n/a |
| Candidate approval | Constitution + `has_approved_candidates` | Election | works | **REQUIRED** | OK | no | n/a |
| Lifecycle | Constitution + engine | Election | works; fall-through semantics open | **REQUIRED** | 🟡 `EM-OPEN-021` | ✅ lifecycle-state | ⛔ no |
| Voting activation | **`EM-VOT-002`** | Election | ✅ both paths | **REQUIRED** | ✅ **VERIFIED** | no | consumed |
| Voter assignment | admission rules | Election | works | **REQUIRED** | OK *(state per `BR-1.12`)* | via `BR-1.12` | ⛔ no |
| Voter eligibility | `EM-ENT-001`+ | 🟡 **UNRESOLVED owner** (U4 Q4) | 🔴 context-corrupted (`65`+`69`) | **REQUIRED** | 🔴 misaligned | scope (i)/(ii) + `AD-2` | ⛔ **no** |
| Voter verification | credential chain (≠ entitlement) | credential/security | works | **REQUIRED** | OK *(coherence `Q-E1` deferred)* | no *(deferred)* | ⛔ no |
| Vote casting | one-vote via codes; `ADR-T11` | Voting | works (`38` verified) | **REQUIRED** | OK | no | n/a |
| Suspension (voter) | `EM-GOV-002`/`003`; `BR-1.13`/`Q3` open | Election governance | works incidentally | **SHARED** *(mode-blind)* — not first-delivery-gating unless PO says | 🟡 | ✅ `BR-1.13`+`Q3` later | ⛔ no |
| Suspension (election) | Constitution `suspend`/`resume` | Election | ✅ works, tested | **REQUIRED** | OK | no | n/a |
| Counting | Constitution (`close_voting`→counting) | Election | implemented | **REQUIRED** | verification = Session 1 | no | n/a |
| Results | publication immutable · visibility hide/show (PO 2026-08-06) | Election | partial; `V-1` defect (`60`) | **REQUIRED** *(publication)* / visibility **SHARED** | 🟡 | ✅ `60 D-2` + `EM-OPEN-013` confirm | ⛔ no |
| Audit | `BR-1.5`/`1.6` open (defaultable) | Election governance | governance acts unaudited | **SHARED** | 🟡 | defaultable | ⛔ no |
| Entitlement | 27 adopted rules | Election | record exists; expression gaps (`IG-*`) | **REQUIRED** *(record + admission)*; deeper expression via `Q3` | 🟡 | `Q3` later | ⛔ no |
| Membership | `EM-VOC-001` (Member aggregate) | Organisation | dormant (0 rows) | **OUTSIDE ELECTION-ONLY** | frozen | no | ⛔ frozen |
| Credentials | possession ≠ entitlement (adopted) | security | works | **REQUIRED** *(possession)*; control (`Q-E1`) **SHARED** | OK / 🟡 | `Q-E1` later | ⛔ no |

## U8 · Architectural authority — **UNRESOLVED, deliberately**

Four parallel roots exist: `app/Models/` *(holds the live eligibility gate)* · `app/Domain/Election/` *(Constitution, enums, security VOs)* · `app/Application/Election/` *(engine, guard, capabilities — the verified `EM-VOT-002` home)* · `app/Contexts/Elections/` + `app/Contexts/Membership/` *(DDD-shaped; contains stubs and 0-caller policies)*. **Measured repeatedly: RUNTIME AUTHORITY does not coincide with the `Contexts/` folders** *(the declared "authoritative eligibility engine" has zero callers; the admission domain policies are stubs)*. **Therefore: `app/Contexts/Elections/Domain` is NOT accepted as authoritative from folder structure. Classification per component (current-runtime vs target vs transitional vs adapter) awaits the architecture archaeology — marked UNRESOLVED, not guessed.**

## U9 · Session 3 governance status

**Completed implementation confirmed WITHIN its boundary** against every grant term: EO programme scope *(mode-independent lifecycle rule — recorded reading)* · strict TDD, RED first *(4+2)* · both paths · no other rule · no Full Membership *(measured)* · no new registry · tests cite `EM-VOT-002` · handed to Session 1, no self-certification · **`EM-OPEN-021` not decided by it.** **No redesign performed or proposed; no `EM-OPEN-021` solution prescribed.**

## U10 · Session 1 security fix — **PROVENANCE ESTABLISHED, my earlier flag CORRECTED**

🔴 **My §14 flag was wrong in its implication, and I withdraw it.** Measured: **commit `9ee15cc6` changed exactly ONE file — the verification execution plan (70 doc lines). Zero production code.** The phrase *"found, fixed and guarded"* describes the **discovered state**: the S5 fix itself landed in **`ec8ee295` (2026-06-27, "fix(security): restore authorization and workflow correctness (S4-S8)")** — **weeks before Session 1's batch.** Session 1 *found that it had been fixed and guarded*, and documented that. **Verdict: NO separation deviation occurred. Session 1 behaved exactly as the verification stream should.** *(Residual, historical only: whether `ec8ee295` itself was authorized is a pre-programme provenance question — classify `GOVERNANCE PROVENANCE HISTORICAL`, no current action.)* **I read a commit subject instead of its diff — the exact attribution error Session 1 warns about in that same commit.**

## U11 · `SD-15` evidence — verified, decision still the PO's

| Question | Answer |
|---|---|
| Was the 2026-05-22 change deliberate? | ✅ **YES** — `086cb3f5` documents intent (*"only one active chief officer is now required"*, *"enabling election-only mode"*), touched Constitution + guard + tutorial |
| Does the current Constitution match it? | ✅ **YES** — `has_chief` is what stands and what the guard evaluates |
| Does the current test represent stale behaviour? | ✅ **YES, now proven:** `git log -S has_committee_members` on the test shows the assertion **introduced 2026-05-19 (`ba4cfa9c`) and NEVER modified since** — so `086cb3f5`'s claim *"updated unit test"* is **contradicted by the diff history for this file** *(it may have updated a different test — the message is not evidence; the diff is)* |
| Is the commit authoritative? | It is a **documented deliberate engineering decision** — strong evidence, **not PO ratification** |
| Does `SD-15` still require a PO/ARB decision? | ✅ **YES** — evidence now clearly favours reading **B** *(chief suffices; the test assertion is stale)*, and **the ratification remains the PO's. Not silently resolved.** |

## U12 · Decisions & authorizations board

**Decisions requiring PO/ARB:** `EM-OPEN-021` *(lifecycle semantics — now with observed exception behaviour)* · `BR-1.12` · `59` and `67` *(presented together, decided as two)* · `65`/`69` **scope** (i vs ii) — *pre-authorization decision* · `SD-15` ratification *(evidence favours B)* · `EM-OPEN-013` confirmation *(via the 2026-08-06 ruling)* · standing: `EM-OPEN-017`, `EM-OPEN-019`.

**Implementation authorizations ACTIVE:** **none.** *(The `EM-VOT-002` grant is consumed — implemented and verified.)*
**Implementation authorizations NOT granted:** `65`/`69` repair · anything `EM-OPEN-021` · admission slice (`BR-1.12`) · time-semantics changes · `60 D-2` · suspension/credential/audit work · everything Full Membership.

---

## FINAL BOARD STATE

| Category | Items |
|---|---|
| **IMPLEMENTED + VERIFIED** | `EM-VOT-002` *(both paths, independent verification)* · `PBDIGIT-62` *(repaired + protecting tests; estate confirmation = Session 1)* · S4–S8 security fixes *(historical, `ec8ee295`)* |
| **DECIDED + NOT IMPLEMENTED** | `Q-B1` vocabulary *(adopted; expression gaps unauthorized)* · the 27 adopted rules' unimplemented aspects (`IG-*`) · publication/visibility model *(PO 2026-08-06; capability unbuilt)* |
| **IMPLEMENTATION AUTHORIZED** | **none currently active** |
| **OPEN BUSINESS DECISION** | `EM-OPEN-021` · `BR-1.12` · `59` · `67` · `EM-OPEN-018` · `EM-OPEN-019` · `SD-15` *(ratification)* · `EM-OPEN-013` *(confirmation)* · `BR-1.x` set · `Q-E1`/`Q-E2` · `EM-OPEN-017` |
| **ARCHITECTURE UNRESOLVED** | eligibility-resolution **owner** (U4 Q4 / `AD-2`) · the four-root authority map (U8) · `65`/`69` fix **scope** (instance vs pattern) · `Q3` exercisability |
| **GOVERNANCE PROVENANCE UNKNOWN** | **none current** — the §14 flag is withdrawn (U10); `ec8ee295` is `HISTORICAL` |
| **OUTSIDE CURRENT ELECTION-ONLY SCOPE** | Full Membership (`EM-FM-*`, `FM-1`…`15`, `W-*`) · `Member` aggregate · organisation-driven suspension · newsletter (`61`) |

**Traceability (this update):** `9ee15cc6` *(1 file, docs only — measured)* · `ec8ee295` (2026-06-27, S4–S8) · `ba4cfa9c` (2026-05-19, assertion introduced; `-S` shows no modification since) · `086cb3f5` (2026-05-22) · Session 1's verification finding and exception observation *(consumed as evidence, not authority — the lifecycle-semantics conclusion is mine only insofar as it says "undecided")* · `f2c2cc4e`.

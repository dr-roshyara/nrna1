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

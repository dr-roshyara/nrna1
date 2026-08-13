# Election-Only — domain ownership & authority analysis

**Type:** Strategic DDD governance analysis · **Date:** 2026-08-13 · **Programme:** IERVP (Session 2)
**⛔ STRATEGIC ONLY. No production/test/fixture/schema change · no refactoring · no policy redesign · no lifecycle-state change · no eligibility fix · no aggregate/entity/repository/VO/service/context recommended · no implementation authorized · `EM-VOT-002` not reopened · `EM-OPEN-021` not solved.**

---

## 1 · Executive status of Election-Only

> **`EM-VOT-002` is consumed: adopted, implemented, independently verified. What blocks the next implementation slice is not code — it is FOUR unresolved authorities:** the **owner of voting-time eligibility resolution** *(architecture)*, the **admission state** *(business, `BR-1.12`)*, the **lifecycle meaning of the anomalous window-open/zero-candidate state** *(business, `EM-OPEN-021`)*, and the **time semantics pair** *(business, `59`+`67`)*.
>
> **This analysis also corrects one of my own standing findings (§6.1): admission-time eligibility IS mechanically enforced** — the interface binding routes around the stubs I had flagged. **The runtime turns out to have TWO different eligibility authorities for two different moments**, and that split is the single most useful fact for deciding `65`/`69` ownership.

## 2 · Domain responsibility / context map

**Derived from business language, adopted rules, invariants and runtime dependencies — NOT from folders.**

| Concept | Election context | Organisation context | Credential/Security | Infrastructure |
|---|---|---|---|---|
| **Election lifecycle** | **OWNS** *(Constitution + engine; ADR-001)* | — | — | consumes clock |
| **Admission** (may this person be admitted?) | **OWNS the decision**; mode rule from `VoterSourceStrategy` snapshot | **REFERENCED** *(EO: technical linkage only — `EM-VOC-002`)* | — | — |
| **Election entitlement** (`ElectionMembership`) | **OWNS** *(`EM-ENT-007`)* | **FORBIDDEN owner** *(`EM-ENT-004`/`006`)* | — | — |
| **Voting-time eligibility resolution** (is this person a recognisable voter of THIS election, now?) | **OWNS by adopted language** *(`EM-GOV-001`: the election governs exercisability)* — 🟡 **formal owner UNRESOLVED (`AD-2`)** | **FORBIDDEN dependency** — nothing in the adopted rules makes recognition depend on ambient organisation context; **the election itself determines its organisation** | — | 🔴 **currently INTRUDES** *(`BelongsToTenant` fallback + tenant-blind cache — the `65`/`69` defect)* |
| **Credential possession** | consumes *(a gate input)* | — | **OWNS** | — |
| **Nomination/candidacy** | **OWNS** | — | — | — |
| **Tenant identity** | **consumes at most** — and three production sites already derive it **from the election** | co-owns with platform | — | **carrier** (middleware + session), **not owner of meaning** |
| **Officer authority** | **OWNS** (`manageVoters`, Constitution roles) | — | — | — |
| **Organisation Membership** (`Member` aggregate) | **FORBIDDEN in EO** *(`EM-EO-003`)* | **OWNS** | — | — |

## 3 · Authoritative implementation map — concept → runtime authority → transitional → legacy → target

| Concept | **RUNTIME AUTHORITY (measured)** | Transitional | Legacy | Target *(ratified?)* |
|---|---|---|---|---|
| Election record | `app/Models/Election` | — | legacy `status` column (58's scope) | ❌ none ratified |
| Constitutional workflow | `Domain/Election/Constitution/ElectionConstitution` | — | — | ✅ ADR-001 *(home ratified; contents per-rule)* |
| Lifecycle state | `Application/Election/.../ElectionLifecycleEngineImpl` (computed) + guard (commanded) | — | routing that read `status` (58) | ADR-003/004/005 pattern ✅ |
| **Admission-time eligibility** | 🔑 **`Contexts/Elections` IS live here** — `VoterEligibilityService` → *(interface binding)* → **`EloquentVoterEligibilityQueryService`** → builds `EligibilityContext` from DB → `ElectionOnlyPolicy::decideForContext()` *(real logic: mode + active + not deleted)*; `AssignVoterHandler`/`BulkAssignVotersHandler` + `EloquentVoterRepository` are live consumers | the self-bound stub `isEligible()` methods *(strangler shims — "Phase B strangler" per the provider comment)* | — | partially in force |
| **Voting-time eligibility** | **`app/Models/User::isVoterInElection()`** + `EnsureElectionVoter`/`VoteEligibility` middleware | — | `isEligibleToVote()` (dead flags) · `scopeEligible()`/`isEligible()` (0 callers) | ❌ **the declared target (`VotingEligibilityPolicy`, Contexts/Membership) has 0 callers — `AD-2` unresolved** |
| Entitlement record | `app/Models/ElectionMembership` | — | `ElectionUser` (dead) | ❌ `Q3` open |
| Credential | `VoterSlug`/`Code` chain | — | — | — |
| Tenant identity | middleware `TenantContext` / `IdentifyTenantFromHeader` / session · **plus 3 controller sites deriving it FROM the election** | — | — | ❌ unratified |

> ### §3.1 — Correction to my own standing finding
> **`B-4` of the Session-3 handover and several later records said admission-time enforcement was `MECHANISM NOT ESTABLISHED` because the domain policies' `isEligible()` are stubs returning `true`.** **Measured now: the `VoterEligibilityPolicy` INTERFACE is bound to `EloquentVoterEligibilityQueryService`** — so runtime calls route **around** the stubs into the infrastructure service, which builds context from the database and delegates to the policies' **real** `decideForContext()` logic. **Admission-time eligibility enforcement IS mechanically established.** *(The stubs remain a trap for anyone calling the concrete classes directly — a finding, not a defect claim.)* **I had read class bodies instead of container wiring — same error family as reading a commit subject instead of its diff.**

> ### §3.2 — The most decision-relevant fact in this map
> **The runtime has TWO eligibility authorities for TWO moments:** admission-time *(Contexts/Elections chain, wired, mode-aware)* and voting-time *(the legacy `app/Models` gate, mode-blind, ambient-context-dependent)*. **The `65`/`69` defect lives entirely in the second.** Any ownership decision that treats "eligibility" as one thing will mis-scope the repair.

## 4 · Decision register — one category each *(Mission 10: A–G, never mixed)*

| Item | Question | Category | Basis |
|---|---|---|---|
| **`EM-OPEN-021`** | lifecycle meaning of window-open + zero candidates | **C — business decision required** | observed `InvalidElectionStateException` is technical behaviour, not semantics; no adopted rule determines a fallback |
| **`BR-1.12`** | admission state (`active` vs `invited`→approval) | **C** | four layers vs production; no authority either way |
| **`59`** | which timestamp set is constitutional | **C** | two clock sets disagree 4/4; nothing adopted |
| **`67`** | what an officer-entered time means | **C** *(separate from 59 — different question, different boundary: lifecycle clock authority vs input interpretation; they share columns, not a rule)* | measured 60–120 min error; `D-1`…`D-4` reserved |
| **`SD-15`** | `has_chief` vs `has_committee_members` | **C — ratification** | evidence now clearly favours `has_chief` *(deliberate 2026-05-22 change; test assertion never modified since 2026-05-19)*; **the ratification is still the PO's** |
| **`EM-OPEN-013`** | unpublish vs hide/show | **B — adopted but implementation owner unclear** | the PO ruling of 2026-08-06 IS the adopted answer *(publication immutable; visibility hide/show)*; the visibility **capability** has no owner/implementation (`60`) · **still EO-relevant: results phase is REQUIRED** |
| **`Q3`** | representation of exercisability | **D — architecture ownership decision required** | the business meaning is adopted (`EM-ENT-002`); where the state lives and who owns it is not |
| **`65`-defect** | valid entitlement denied by ambient context | **E — implementation defect under adopted rules** (`EM-ENT-001`, `EM-EO-*`) | measured A/B |
| **`69`-defect** | cached answers cross context boundaries | **E** | measured both directions |
| 🆕 **`AD-2`/ownership of voting-time eligibility resolution** | which component/context owns the voting-time gate | **D** | declared target has 0 callers; legacy gate is live; adopted language says the election governs — **formalisation missing** |
| 🆕 **repair scope for the `BelongsToTenant` family** | fix instances or audit-then-fix pattern | **D** | 3 confirmed surfacings; other consumers unaudited |
| four-root architecture map | which generation is target vs transitional | **F — evidence gap** | archaeology incomplete; **not guessed** |

## 5 · `PBDIGIT-65`/`69` — ownership analysis *(the thirteen questions)*

| # | Question | Answer |
|---|---|---|
| 1 | What concept do they express? | **Voting-time recognition of an election entitlement** — the evaluation *"is this person an admitted, currently-recognisable voter of THIS election?"* |
| 2 | Which kind of concept? | **Eligibility (evaluation of an entitlement)** — corrupted by **infrastructure/context propagation**. Not membership *(no `Member` involved)*, not entitlement-record *(the row is correct)*, not credential |
| 3 | Which context owns it? | **By adopted business language: the Election context** — `EM-GOV-001` *(the election governs whether the entitlement may be exercised)*, `EM-ENT-007`. **Formal ownership is the open `D` item (`AD-2`)** — `ADR-002` assigned "Eligible" to an Eligibility Context that materialised only as a 0-caller policy |
| 4 | Who merely consumes? | the middleware gates, `ElectionVotingController`, the dashboard/login resolvers (`62`'s consumers) |
| 5 | Is `ElectionOnlyPolicy` owner or consumer? | **Neither, at voting time.** It is the **admission-time** qualification logic *(live via the interface binding, §3.1)*. It plays no role at the ballot gate |
| 6 | Is `FullMembershipPolicy` relevant? | **Out of scope** — frozen; same wiring shape, dormant data |
| 7 | What is ambient context at runtime? | request-scoped `TenantContext` *(set by tenant middleware from route organisation / header)*, falling back to `session('current_organisation_id')`, falling back — inside `BelongsToTenant` — to **the platform organisation's id** |
| 8 | Where does tenant identity come from? | the sources above — **and, notably, three production sites in `ElectionManagementController` already derive it FROM THE ELECTION** (`TenantContext::set($election->organisation_id)`). **The pattern the adopted rules imply already exists in production as precedent** *(evidence, not a decision)* |
| 9 | Is `BelongsToTenant` infrastructure or domain-meaning-carrying? | 🔑 **Infrastructure that CARRIES domain meaning it does not own.** Its platform fallback silently decides *whose rows exist* when context is absent — a domain-relevant decision taken by an infrastructure default. That is the precise ownership violation |
| 10 | Is the tenant-blind cache an infra or ownership defect? | **Both, layered:** an **infrastructure defect** (key design omits a dimension the answer depends on) **compounding the ownership defect** (the answer should not depend on that dimension at all). Fixing only the key cures the cache and leaves the ownership violation intact |
| 11 | Are `62`/`65`/`69` one pattern? | **One root mechanism** — ambient organisation context participating in election-scoped resolution via `BelongsToTenant`'s fallback |
| 12 | Evidence FOR one pattern | all three trace to the same trait and fallback; `62` (no-context at login) and `65` (wrong context) are the same query-scoping failure in two context states; `69` presupposes `65`'s context-dependence |
| 13 | Evidence AGAINST | **the defect classes differ:** `62`/`65` are **scoping** defects; `69` is a **caching** defect that would persist wrong answers even if scoping were fixed-but-context-dependent; the proven `62` fix (subquery scoping) does not generalise to `69` (key/no-cache/context-free are different remedies). **Verdict: ONE pattern for diagnosis; AT LEAST TWO defect classes for repair scoping** |

> **Ownership conclusion offered for the ARB (not decided):** the adopted rules place voting-time recognition **in the Election context**, with ambient organisation context as a **forbidden dependency** — the election determines its own organisation, and production already does this in three places. **Formalising that is the `D` decision that must precede any `65`/`69` authorization.**

## 6 · Election-Only eligibility / entitlement analysis

```
ADMISSION TIME                                  VOTING TIME
Chief imports/assigns                           voter requests ballot
      │                                               │
VoterEligibilityService                         EnsureElectionVoter / VoteEligibility
      │  (interface binding)                          │
EloquentVoterEligibilityQueryService            User::isVoterInElection()
      │  builds EligibilityContext from DB            │  role='voter' AND status='active'
ElectionOnlyPolicy::decideForContext()          BelongsToTenant scope ← 🔴 AMBIENT CONTEXT
      │  mode + active + not deleted            cache 'user.{u}.voter.{e}' ← 🔴 TENANT-BLIND
      ▼                                               ▼
ElectionMembership created                       admit / deny
(mode-aware · wired · Contexts/Elections)        (mode-blind · legacy · app/Models)
```

**The two gates evaluate different questions with different authorities, and only the second is defective.** The entitlement *record* between them is sound (`EM-ENT-*` conformant, measured). `BR-1.12` sits at the seam *(what state does admission produce)*; `Q3` sits under the second gate *(what the gate should read)*.

## 7 · Minimal Election-Only readiness gates

| # | Gate | Why necessary | Owner | Authority | Status | Blocking? |
|---|---|---|---|---|---|---|
| **G1** | Voting cannot activate without an approved candidate | franchise integrity | Election | `EM-VOT-002` ✅ | ✅ **MET — verified** | met |
| **G2** | **A valid entitlement is recognised regardless of ambient context** | adopted entitlement rules; measured violation | Election *(formalisation = the `D` item)* | `EM-ENT-001`+ | 🔴 **NOT MET** (`65`/`69`) | **BLOCKING** — after the ownership `D` + scope `D` |
| **G3** | Admission produces a **decided** state | otherwise the first admission test legislates | Election | `BR-1.12` | 🟡 undecided | **BLOCKING for the admission slice only** |
| **G4** | The anomalous window-open/zero-candidate state has **decided** semantics (or is proven recoverable) | potential lifecycle dead-end (untraced) | Election lifecycle | `EM-OPEN-021` | 🟡 undecided; Session 1 measuring recoverability | **CONDITIONAL** — on Session 1's dead-end finding |
| **G5** | The voting window rests on a **decided** clock | the verified `EM-VOT-002` gate reads a disputed clock | Election lifecycle / scheduling | `59` + `67` | 🟡 undecided | **CONDITIONAL** — correctness of *when*, not *whether* |

**Deliberately NOT gates:** suspension completion (`BR-1.13`/`Q3`) · credential coherence (`Q-E1`/`Q-E2`) · audit hardening · results visibility capability (`60`) · display conversion (`EM-OPEN-018`) — all real, none required for coherent first-phase EO operation unless the PO elevates one.

## 8 · Explicit lists

| Category | Items |
|---|---|
| **IMPLEMENTED** | `EM-VOT-002` (both paths) · admission chain (§3.1) · `62` fix · election-level suspension · S4–S8 (historical) |
| **VERIFIED** | `EM-VOT-002` (Session 1, both paths) · `62` (RED→GREEN at fix time; estate confirmation = Session 1) |
| **ADOPTED BUT NOT IMPLEMENTED** | visibility capability (`EM-OPEN-013` ruling → `60`) · several `IG-*` expression gaps of the 27 rules |
| **BUSINESS DECISION REQUIRED** | `EM-OPEN-021` · `BR-1.12` · `59` · `67` · `SD-15` ratification · `EM-OPEN-018` · `EM-OPEN-019` · `BR-1.x` set · `Q-E1`/`Q-E2` |
| **ARCHITECTURE DECISION REQUIRED** | **voting-time eligibility ownership (`AD-2`)** · **`65`/`69` repair scope (instances vs pattern)** · `Q3` · `EM-OPEN-017` |
| **EVIDENCE GAP** | the four-root generation map (archaeology) · other `BelongsToTenant` consumers (unaudited) · `EM-OPEN-021` recoverability (Session 1, in progress) |
| **OUT OF SCOPE** | Full Membership (`EM-FM-*`, `FM-1`…`15`, `W-*`) · `Member` aggregate · organisation-driven suspension · newsletter |

## 9 · Authorization recommendations *(recommendations only — nothing granted)*

1. **Next authorizable slice, once two `D` decisions are taken:** the `65`/`69` repair — **prerequisites:** ① the **ownership decision** *(voting-time resolution owned by the Election context; ambient context a forbidden dependency — evidence in §5 supports it, the ARB takes it)*, ② the **scope decision** *(instances vs audited pattern)*. **With those two, the authorization is safe; without them it is a guess wearing a grant.**
2. **`BR-1.12`** — decide before any admission-slice work; no analysis remains.
3. **`EM-OPEN-021`** — hold until Session 1's recoverability measurement lands; then decide **from the business, not from the exception.**
4. **`59`/`67`** — present together, decide as two.
5. **Nothing else** should be authorized in this phase.

## 10 · Risks of premature implementation

| Risk | If we implement before deciding |
|---|---|
| **Fixing `65` inside the wrong owner** | the repair hard-codes ambient-context semantics into whichever class is touched, and the eventual `AD-2` owner inherits a second live gate — **five eligibility definitions instead of four** |
| **Cache-key-only fix for `69`** | cures persistence, leaves the ownership violation — the wrong answer still computed, now merely uncached |
| **Admission slice before `BR-1.12`** | the first test **legislates** `active`-by-default *(production is its only evidence)* |
| **Catching the `EM-OPEN-021` exception** | the technical fallback becomes de facto semantics — the exact failure the governance mechanism exists to prevent |
| **"One time fix" for `59`+`67`** | half-decided rule: input semantics without clock authority (or vice versa) produces confident wrong windows |
| **Pattern-wide `BelongsToTenant` change without audit** | unaudited consumers (organisation pages, newsletters, dashboards) silently change row visibility — blast radius unknown by construction |

## 11 · Evidence (exact)

Container wiring: `AppServiceProvider:125-145` *(interface→`EloquentVoterEligibilityQueryService`; stubs self-bound)* · live admission chain: `ElectionVoterController:74`, `VoterEligibilityService:53`, `BulkAssignVotersHandler:56`, `AssignVoterHandler:34` · tenant writers: `Http/Middleware/TenantContext:54,85`, `IdentifyTenantFromHeader:48`, **`ElectionManagementController:228,686,1125` (from-the-election precedent)** · voting-time gate: `User:315-328`, middleware, G-1 runtime A/B · `69` cache measurements · `f2c2cc4e` + Session 1's both-paths verification · `086cb3f5`/`ba4cfa9c` (SD-15) · `ec8ee295` (S4–S8) · PO ruling in `PBDIGIT-60` (2026-08-06) · Manifesto §§1–9 · ticket authority matrix · current-state report + U-addendum.

**STOP. Nothing authorized. Decision-ready for the Product Owner / ARB.**

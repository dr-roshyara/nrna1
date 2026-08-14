# Election-Only Mode — Architecture Review

**Session 4 (ARCHITECTURE stream) · 2026-08-15 · a review of the architecture this stream established for Election-Only Mode**

> **What this document is:** the consolidated architectural picture of Election-Only Mode as the architecture stream established it — the domain model, the authority map, the boundaries, and the corrections. It is a *review of architecture*, written with a DDD mindset.
>
> **What this document is NOT — stated first, because the distinction is the point:** **this stream implemented no Election code.** Every Election artifact it produced was read-only: archaeology, an authority map, an ADR, and boundary rulings. Where Election code changed, Session 3 changed it under its own grants, and Session 1 verified it. Nothing below claims otherwise, and §11 attributes current state to its actual authors.

*Placement note: filed in `docs/publicdigit/reviews/` (the governed reviews root; the requested `reveiws/` spelling would have created a second, unruled directory).*

---

## 1 · Executive summary

Election-Only Mode is **not a subsystem, not a bounded context, and not a folder**. Architecturally it is one **immutable per-election snapshot** — `elections.voter_source_strategy` — whose value selects between two *admission* policies and reaches **nothing else** in the Election domain. That is the single most consequential finding this stream produced: the mode's real footprint is far smaller than its name suggests, and almost every capability a running election needs is **mode-independent**.

Five architectural results, each evidence-backed:

1. **Authority was misattributed, and the misattribution was refuted.** The claim that `app/Contexts/Elections/Domain/` is authoritative for Election rules is false; it is a downstream, single-capability strangler slice. Election lifecycle authority is `app/Domain/Election` + `app/Application/Election` + `app/Models/Election.php`.
2. **The mode is defined in `Domain/Election`, not in either Election context** — `Enum/VoterSourceStrategy`, snapshotted at creation, immutable thereafter, failing closed on absence.
3. **"Eligibility" was one word covering two different authorities** — admission-time (mode-dependent) and voting-time (mode-independent, credential/entitlement-based). Naming that split was this stream's most useful contribution to the Election track; the 65/69 repair subsequently proved it in production code.
4. **The lifecycle is structurally hybrid** — a command path and a computed path that cannot reach each other. That is why EM-VOT-002 required enforcement in *both*, and why its placement is correct.
5. **The mode's architecture is sound; the mode is not ready.** Readiness is blocked by business decisions and unverified journeys, not by architectural defects (§11).

## 2 · Provenance and role boundary

| Artifact | Commit | Nature |
|---|---|---|
| Election Context Architecture Archaeology (22 §§ + addendum) | `5ff413a3` | evidence base — read-only investigation |
| ADR — Current Architecture Authority and Code Placement | `5ff413a3` | PROPOSED architectural decision record |
| PKS candidate — evidence hierarchy for code ownership | `5ff413a3` | research candidate, not promoted |
| This review | — | consolidation; no new authority |

**Not this stream's work:** EM-VOT-002's implementation (`f2c2cc4e`, Session 3), the 65/69 entitlement repair (`3499ea38`→`5d46498e`→`ac313368`, Session 3), every independent verification (Session 1), every ruling and registration (PO/ARB + Session 2). **Architecture proposes; it does not implement, verify, govern, or qualify.**

## 3 · Strategic DDD — what Election-Only *is*

### 3.1 The domain question, answered precisely

> *"Where does the set of people entitled to vote in this election come from?"*

Election-Only answers: **from the organisation's own enrolment** (`organisation_users`), with no membership governance. Full Membership answers: **from formal membership** (`members` + fees + type + expiry). Both are answers to the *admission* question — **not** to "may this person cast a ballot right now," which is a different question with a different owner (§6).

### 3.2 The concept is a snapshot, not a state

```
Organisation.uses_full_membership          ← organisational policy, mutable
            │
            │ read ONCE, at election creation
            ▼
Election.voter_source_strategy             ← the SNAPSHOT: sovereign, IMMUTABLE
            │
            └── ImportedVoterRegistry ('election_only') | MembershipRegistry ('full_membership')
```

**The invariant, and why it matters:** once created, an election owns its participation rule. Later organisational change cannot retroactively alter who may vote in an election already under way. The code enforces this by **failing closed** — `VoterSourceStrategy::fromElection()` throws `RuntimeException` on a null snapshot rather than defaulting. A default would have been a silent business decision; the throw is the correct architectural posture for a rule that governs enfranchisement.

*Ubiquitous-language caveat, recorded not resolved:* the enum's case names (`ImportedVoterRegistry` / `MembershipRegistry`) are **explicitly transitional**, carrying `@deprecated` pending a Phase-4 governance-language review, while `label()` renders the customer-facing terms "Election-Only" / "Full Membership". So the *concept* is current authority; its *names* are future intention. That divergence is a live open item (AMB-7), not a cosmetic one — vocabulary is part of the model.

### 3.3 Mode selection and enforcement — the whole footprint

**Selected in exactly one production site:** `ElectionManagementController::store()` — `VoterSourceStrategy::fromOrganisation($organisation)->toPersistenceValue()`. Plus one backfill command for pre-snapshot rows.

**Enforced in exactly six sites**, all within admission/import:

| Site | Layer | Role |
|---|---|---|
| `VoterEligibilityService::isEligibleVoter()` | Service | delegates to the policy port |
| `VoterEligibilityService::unassignedEligibleQuery()` | Service | own mode branch, own SQL (a duplicate — §8) |
| `EloquentVoterEligibilityQueryService` | Context Infra | builds context, branches, delegates the decision |
| `ElectionOnlyPolicy::decideForContext()` | Context Domain | the pure decision: active ∧ not-deleted ∧ mode |
| `VoterImportService` | Service | mode-specific parse/preview/import |
| `VoterImportController` / `ElectionVoterController` | HTTP | serialise `toApiValue()` into Inertia props |

**Mode reaches nothing else.** Measured: zero `VoterSourceStrategy` references in the candidacy controllers, the vote controller, the code/credential controller, or anywhere in the lifecycle engine, constitution, or guard.

```
        ELECTION-ONLY MODE — actual footprint

  creation snapshot ─▶ admission eligibility ─▶ voter assignment ─▶ voter import ─▶ UI/telemetry vocabulary

        MODE-INDEPENDENT (everything else)

  lifecycle · transitions · nomination · candidacy · voting activation · credentials ·
  ballot access · vote submission · one-vote · counting · results · audit · security
```

**Architectural consequence:** "make Election-Only work" is overwhelmingly a task of making *the shared election machinery* work — the mode-specific surface is small and largely implemented. Treating Election-Only as a large distinct subsystem would misdirect the entire effort.

## 4 · The authority map

| Capability | Current authority | Layer |
|---|---|---|
| Transition rules (states, roles, preconditions) | `Domain/Election/Constitution/ElectionConstitution::RULES` | Domain |
| Command-path authorization | `Application/Election/Services/ConstitutionalTransitionGuard` | Application |
| Computed lifecycle state | `Application/Election/Services/ElectionLifecycleEngineImpl::getState()` | Application |
| State consumption API | `Application/Election/Facades/ElectionLifecycle` | Application |
| State persistence (`state` column) | `Election::transitionTo()` — sole writer | Model (compatibility artifact) |
| **Election-Only mode vocabulary + snapshot** | `Domain/Election/Enum/VoterSourceStrategy` | Domain |
| **Admission-time eligibility decision** | `Contexts/Elections/…/ElectionOnlyPolicy::decideForContext()` behind the DI-bound port | Context Domain + Infra |
| **Voting-time entitlement** | `ElectionMembership` (voter, election) + credential; **election-derived, ambient-free** | Model + HTTP (post-65/69) |
| Voter assignment | `Contexts/Elections/Application/Handlers/*` | Context App |
| Adjudication → election correction | `Contexts/Election/Domain/Election` (singular) | Context Domain |

**Level-1 corroboration:** `ADR_20260807_1500` (ACCEPTED) independently names the `ElectionLifecycle` façade the single source of truth for election state and classifies `status`/`is_active`/`state` as compatibility artifacts on an approved Option-B retirement path. The archaeology reached the same conclusion from wiring and call sites *before* citing that ADR — a methodological failure recorded in §10.

## 5 · Bounded-context assessment

The repository's own approved model (`deptrac.yaml`, ARB/PB-007) declares exactly four analysed paths: **Contestation · Adjudication · Election *(singular)* · Shared**.

| Directory | Files | Provider registered | In deptrac | Owns |
|---|---|---|---|---|
| `app/Contexts/Election/` **(singular)** | 29 | ✅ | ✅ | adjudication → election **correction** — idempotent, forward-only (ADR-T8/T11), tenant-free (ADR-T16) |
| `app/Contexts/Elections/` **(plural)** | 15 | ❌ | ❌ | **one** capability: admission eligibility + voter assignment |
| `app/Domain/Election` + `app/Application/Election` | 108 + 69 | n/a | ❌ *(deliberately out of scope)* | **the lifecycle, the constitution, the mode, the events, security** |

**Dependency direction settles ownership** — verified in both directions and re-verified for this review (still zero):

```
Contexts/Elections  ──imports──▶  Domain/Election   (VoterSourceStrategy, 2 domain events)
Domain/ , Application/  ──imports──▶  Contexts/*    :  ZERO
```

A module that imports its neighbour's enum and domain events is **downstream** of it and cannot be the authority for that neighbour's rules. And the *governed* Election context says so in its own source: `LegacyElectionExistenceAdapter` documents the legacy `elections` table as *"the CURRENT operational source of truth, until a greenfield Election-lifecycle capability replaces it (Strangler)."*

**Two directories differing by one character, holding unrelated capabilities from different architectural generations, is itself a hazard** — it produced exactly one wrong architectural conclusion before this stream tested it. Recorded as AMB-2; no rename performed or recommended here.

## 6 · The two eligibilities — this stream's most useful correction

The word "eligibility" was covering **two different authorities**, and conflating them invites consolidating things that share only a noun:

| | **Admission-time** — *may this person be added to the roll?* | **Voting-time** — *may this voter cast a ballot now?* |
|---|---|---|
| Authority | `VoterEligibilityPolicy` → `EloquentVoterEligibilityQueryService` → `ElectionOnlyPolicy` / `FullMembershipPolicy` | `ElectionMembership` (voter, election) + credential/slug chain |
| Mode-dependent? | **YES** | **NO** — zero `VoterSourceStrategy` refs across `VerifyVoterSlug` → `EnsureElectionVoter` → `EnsureVoterStepOrder` → `VoteEligibility` |
| Data | `organisation_users` (EO) or `members`+fees (FM) | `election_memberships`, `codes.can_vote_now` |
| Question it answers | population | permission |

**Subsequently proven in production, not merely argued.** The 65/69 repair (Session 3, verified by Session 1) established the voting-time rule as: *the election determines the required organisation · the credential supplies the comparand · no credential → deny · ambient session/tenant must never filter entitlement · a result computed under another context must never be replayed.* Two runtime-reproduced defects — a valid voter denied because ambient tenant acted as an invisible filter, and a wrong-context `false` replayed for up to 300 s from a tenant-free cache key — were repairs to **voting-time** entitlement, and they touched the admission-time policy not at all. The distinction is now load-bearing in code.

**Architectural rule this yields:** *entitlement is a fact of `(voter, election)`; ambient context has no role in it, in either direction.* Election-derivation replaced ambient-derivation; that is the substantive architectural outcome of the 65/69 work, and it is a property of the Election domain, not of the mode.

**Still open (not this stream's to close):** `start()` remains a third site with the same predicate, unauthorized for repair (B-2); and `AD-2` — ownership of the eligibility model itself — is undecided.

## 7 · The lifecycle architecture, and why EM-VOT-002 sits where it does

**The structural fact everything else follows from:** state is **hybrid**, and the two halves cannot reach each other.

```
COMMAND PATH (writes)                          COMPUTED PATH (reads)
Election::transitionTo(Transition)             ElectionLifecycleEngineImpl::getState()
   → ConstitutionalTransitionGuard                → 12-rule ladder over business facts
       → ElectionConstitution::RULES              → clock · candidacies · completion flags
       → validatePreconditions()
   → updateQuietly(['state' => …])  ← the column is a COMPATIBILITY CACHE, not truth
```

The engine's own docblock is explicit: *"Derives state ONLY from business facts. State column is compatibility cache, not truth."* Why the design is right: **election state is a function of time, and nothing writes columns when time passes** — a voting window opens at 10:00 because the clock reached 10:00. A persisted column is *structurally* incapable of answering "is voting open now?"

**Therefore EM-VOT-002 — *voting requires at least one approved candidate* — necessarily lands in three places, and this stream confirmed the placement is correct:**

| Concern | Home |
|---|---|
| the rule itself | `ElectionConstitution::RULES['open_voting'].preconditions` → `has_approved_candidates` |
| command enforcement | `ConstitutionalTransitionGuard::validatePreconditions()` |
| computed enforcement | `ElectionLifecycleEngineImpl::getState()` rule 5 |

Both enforcement points are required **because no command guard can reach the computed path**: the engine derives state from facts with no transition occurring. Had the rule been placed in `Contexts/Elections` — as the refuted claim would have directed — it would have been unreachable from either path.

**The residual is semantic, not architectural (EM-OPEN-021, open):** when the window is open and no candidate is approved, rule 5 declines and derivation falls through every remaining rule to a terminal `throw`. So `canVote()` **raises rather than denies**, and Session 1 later proved the state is reachable in production through `forceCloseNomination()` — a legitimate administrative action lacking the approved-candidate guard its sibling `completeNomination()` has. Choosing the fallback is a PO decision; the architecture merely makes the gap precise.

## 8 · Duplicate authorities — classified, deliberately unrepaired

| Concern | Competing implementations | Classification |
|---|---|---|
| approved-candidate-required | constitution precondition ≡ engine `hasCandidatesApproved()`; **but `Election::whyCannotOpenVoting()`** tests `candidates_count` / `pending_candidacies_count` | **SEMANTICALLY DIVERGENT** — a different rule, not a copy: an election with one `draft` candidate passes one test and fails the other |
| current state | column readers (`lifecycleState()`, `getCurrentStateAttribute()`) vs engine readers | **COMPATIBILITY / STRANGLER** — named migration debt under ACCEPTED `ADR_20260807_1500` |
| eligible-voter query | `VoterEligibilityService::unassignedEligibleQuery()` vs `EloquentVoterEligibilityQueryService::qualifyingSubset*()` | **SEMANTIC EQUIVALENCE UNKNOWN** — never differentially tested |
| results publication | `Contexts\Elections\…\ResultsPublishedEvent` (dispatched, **no listener**) vs `Domain\Election\Events\ResultsPublished` (**never dispatched**) | duplicate vocabulary, neither wired end to end |
| state vocabulary | `ElectionLifecycleState` (12 cases, live) vs `ElectionState` (7 cases, dead, disjoint) | dead, per DMT evidence |

**"SEMANTIC EQUIVALENCE UNKNOWN" is a valid architectural result** and is recorded as such rather than rounded to "duplicate — consolidate." Retirement is evidence-led: a path retires when evidence shows what retiring it changes, never because it looks redundant. **None of these was repaired by this stream**, and consolidating the eligibility pair in particular would fuse the two authorities §6 just separated.

## 9 · DDD assessment of the Election domain as it stands

| Aspect | Finding |
|---|---|
| **Aggregate** | `app/Models/Election.php` (2,279 lines) is the *de facto* aggregate root — sole state writer, transition orchestrator, write barrier, plus a third business-rule layer. Not a thin read model; any Election work that ignores it will be wrong regardless of which `Contexts/` folder it targets |
| **Value objects** | `VoterSourceStrategy`, `Transition`, `ElectionLifecycleState`, `ElectionLifecycleSnapshot` — genuine VOs. *Purity deviation recorded:* the mode enum imports `App\Models\*` and `Log`, violating the project's own Layer-3 rule while remaining the concept's owner |
| **Domain services** | `NominationWindowPolicy`, `VotingWindowPolicy` — correctly scoped |
| **Ports/adapters** | `VoterEligibilityPolicy` (port) → Eloquent implementation: the cleanest hexagonal seam in the Election estate |
| **Domain events** | declared richly; **wiring is incomplete** — the results pair is the clearest case (dispatched with no listener / never dispatched) |
| **Ubiquitous language** | *two live vocabularies*: transitional enum names vs customer-facing labels; plus `ElectionState`'s dead disjoint set. Language debt is real model debt |
| **Anti-corruption** | `LegacyElectionExistenceAdapter` — exemplary: read-only, tenant-scoped, soft-delete aware, self-documenting about what it does *not* own |

**One honest DDD verdict:** the Election estate is **not** a clean tactical-DDD implementation, and pretending otherwise would be the folder-name fallacy again. It is a working transactional core with a constitutional rule layer bolted on correctly, several architectural generations coexisting deliberately, and one genuinely governed greenfield context beside it doing a narrow job well.

## 10 · What this stream got wrong, and corrected

Recorded because provenance discipline applies to architecture too:

1. **Missed Level-1 evidence.** The archaeology reached its conclusions from wiring, dependencies, and code without citing `ADR_20260807_1500` (ACCEPTED) or `ADR_20260806_1620` — the two highest-ranked applicable artifacts. The conclusions survived because they *agreed* with the accepted ADR; that is luck, not rigour. Root cause: ADRs live across three directories under four naming schemes with no capability index (AMB-1).
2. **Asserted eligibility authority while omitting a registered open question.** The ADR claimed HIGH-confidence authority for the eligibility decision without citing **MB-5** — a pre-existing Product-level finding recording *"eligibility has five implementations and no named authority"* — even while citing the ADR that points at MB-5. That is the false-authority claim the package exists to prevent, committed inside the package.
3. **Conflated the two eligibilities** in the first draft's capability map — corrected into §6, which the 65/69 work then validated.
4. **Mis-severity on the write barrier.** `DeprecationPolicy::STRICT_LEVEL = 1` was reported as an unexplained governance gap; `ADR_20260807_1500` step 4 shows it is deliberate, sequenced staging gated on `PBDIGIT-59`. Severity downgraded, recommendation withdrawn.

## 11 · Current readiness — attributed, not claimed

Readiness is **Session 3's record and Session 1's verifications**, reproduced here only so the architecture is read against reality:

- **1 of 16 capabilities is GREEN** (implemented *and* independently verified): **voting activation** (EM-VOT-002).
- **Repaired and verified:** voting-time entitlement at two of three sites (65/69).
- **Never demonstrated:** *an Election-Only voter casting a real vote end to end.* Every defect found so far was found **upstream** of the ballot.
- **Blocking items are decisions, not architecture:** EM-OPEN-021 (lifecycle totality) · BR-1.12 + AD-2 (admission/eligibility model ownership) · Q3/BR-1.13/Q-E1/Q-E2 (suspension semantics) · `PBDIGIT-59`/`67` (time semantics — **voting windows are measurably 60–120 minutes wrong on live data**) · `60` (results visibility).

**Architectural reading of that list:** the mode's *design* is not what blocks it. What blocks it is unresolved **business semantics** in the shared election machinery — which is exactly what §3.3 predicted when it showed the mode's footprint is small and everything else is shared.

## 12 · What must not be changed on the basis of this document

`ElectionConstitution::RULES` (sole rule table) · the engine's 12-rule priority order (reordering silently changes outcomes for existing rows) · `Election::transitionTo()` (sole state write; lock + transaction + audit ordering) · the `state` column's demoted status · `VoterSourceStrategy` case names/values (persisted, immutable per election; renames are a Phase-4 governance decision) · `VoterEligibilityPolicy`'s frozen signature · the 65/69 predicate semantics (`status !== 'removed'` — byte-identical by design, so suspension questions stay open) · `Contexts/Election`'s ACL seam · the duplicate authorities in §8 (findings, not tickets) · `DeprecationPolicy::STRICT_LEVEL` (raising it starts throwing on writes that exist today).

## 13 · Open architectural questions

| # | Question | Owner |
|---|---|---|
| A-1 | `AD-2` — who owns the eligibility model? MB-5 records five implementations and no named authority | PO/ARB |
| A-2 | Are the two eligible-voter queries semantically equivalent? (differential test — currently asserted by neither code nor test) | Engineering, on authorization |
| A-3 | `whyCannotOpenVoting()` vs the constitution — one rule, one evaluator | PO/ARB → its own story |
| A-4 | `Election` / `Elections` naming collision (AMB-2) | ARB |
| A-5 | Should deptrac coverage extend to `Domain`/`Application`/`Models` — the highest-risk, least-governed code? (AMB-3) | ARB |
| A-6 | `VoterSourceStrategy` Phase-4 vocabulary review — owner and trigger (AMB-7) | PO |
| A-7 | Results-publication events: which class is real, wire it or remove it | PO/ARB |

---

**Traceability:** archaeology + ADR + PKS (`5ff413a3`) · `ADR_20260807_1500` (ACCEPTED — lifecycle SSOT) · `ADR_20260806_1620` (PROPOSED — one authority per concern, n=1) · MB-5 (`2026-08-06-membership-capability-review.md`) · EM-VOT-002 (`f2c2cc4e`, Session 3; verified Session 1) · 65/69 (`3499ea38`→`32215fea`→`5d46498e`→`ac313368`, Session 3; verified Session 1) · Session 3 implementation record (`6889ff11`) · `deptrac.yaml` (ARB/PB-007) · `AppServiceProvider` DI bindings · `workflow-state.php`-era governance rulings only where they bear on Election · EM-OPEN-021 (open) · ES-001.1 · ES-005.1/.4 · R-34 · DDD Tactical Governance Principles (ASP · DMT · RMSP).

---

> **Status: architectural review — no authority created, no code changed, nothing reopened.**

# Session 3 (Implementation stream) — what was implemented, and what is not

**Type:** Implementation-stream record · **Date:** 2026-08-15 · **Author:** Session 3
**⛔ This document changes no code, decides nothing, verifies nothing, and qualifies nothing.** It is a factual record produced by the implementing stream. Where an item is independently verified, that is attributed to Session 1; where it is not, this document says so rather than implying otherwise.

**Reading rule.** Everything below is one of: **IMPLEMENTED** (code exists, tests green) · **IMPLEMENTED + INDEPENDENTLY VERIFIED** (Session 1 confirmed) · **NOT IMPLEMENTED — BLOCKED** (named authority owes a decision) · **NOT IMPLEMENTED — OUT OF SCOPE** (deliberately excluded) · **OBSERVATION** (recorded, not repaired). *Implementation evidence is never verification, and neither is qualification.*

---

## 1 · Executive summary

Two tracks ran in sequence. **The Election track came first; the platform/KnowledgeOS track exists because of what the Election track exposed.**

| Track | Delivered | State |
|---|---|---|
| **Election — EM-VOT-002** (`PBDIGIT-64`) | voting cannot begin without an approved candidate, on **both** paths into `voting_active` | IMPLEMENTED + INDEPENDENTLY VERIFIED |
| **Election — `PBDIGIT-65/69`** | voting-time entitlement is election-derived and ambient-free; cache identity restored | IMPLEMENTED (independent verification of the final state: **see §4.2**) |
| **Platform — `KOS-AI-ORCH-001` Inc. 1** | authoritative per-work-item workflow state record (`workflow-state.php`) | IMPLEMENTED + INDEPENDENTLY VERIFIED + **operationally qualified** (signed PO ruling; governance-closed) |
| **Platform — `KOS-SESSION-DISCOVERY-001`** | read-only Session Assignment Resolver (`session-resolve.php`) + C-1/C-2 corrective | IMPLEMENTED; corrective increment **awaiting independent verification** |

**The single most important sentence in this document:** the Election capability that the programme actually cares about — *an Election-Only voter completing a vote end to end* — **has never been demonstrated**, and no session has claimed otherwise. What was fixed are three specific defects on that path, not the path itself.

---

## 2 · Election track — what was implemented

### 2.1 EM-VOT-002 — voting requires an approved candidate

**Business rule (adopted, Manifesto §4a, ruling `SD-14` = YES):** *an election must have at least one approved candidate before voting may be opened.*

**Defect it closes (`PBDIGIT-64`, runtime-reproduced):** an election became `voting_active` **with zero candidacies**, because lifecycle state is *derived from the clock*, not transitioned. Nobody executed `open_voting`; the window simply arrived. Voters were routed to a ballot with nothing on it.

**Implemented — two enforcement points, because the paths are architecturally disjoint** (commit **`f2c2cc4e`**, 2026-08-13; guide + audit **`db5ec7a8`**):

| Path | Where | What was added |
|---|---|---|
| Command | `ElectionConstitution::RULES['open_voting']` | `has_approved_candidates` precondition (evaluated by the pre-existing guard) |
| **Computed** | `ElectionLifecycleEngineImpl::getState()` priority 5 | `VotingActive` only when the window is open **and** ≥1 approved candidacy exists |

**Why both:** a command-level precondition cannot protect a path on which **no command executes**. This was the whole finding.

**Authoritative fact:** `candidacies.status = 'approved'` (EXISTS query) — deliberately **not** the cached `candidates_count` columns, which are projections.

**Evidence:** RED first (4 computed-path + 2 command-path failures); GREEN 8/8. Fixture corrections in 7 files whose premise was "a legitimately voting election" — each classified before editing, none weakened. Regression restored to the exact pre-existing 17-name baseline; the remaining unit/architecture failures were **proven pre-existing by an A/B revert run**.

**Deliberately NOT decided:** what an election *should* be when the window opens and the invariant is unmet. No fallback state, no new lifecycle state, no precedence change. → **`EM-OPEN-021`, §4.1.**

### 2.2 PBDIGIT-65/69 — entitlement corrupted by ambient tenant context

**Defects (both runtime-reproduced by Session 1's P6 A/B):** a voter holding a valid, election-specific `ElectionMembership` was **denied** because the ambient session/tenant organisation acted as an invisible filter (`65`); and a `false` computed under the wrong context was **replayed for up to 300 s into the correct context** from a tenant-free cache key (`69`).

**The governing rule, as ruled (Q-TEN-1 + Q-TEN-2, six clauses):** the Election determines the required organisation · the voting **credential** supplies the comparand · no credential → deny · ambient session/tenant must never filter entitlement · a result produced under another context must never be reused.

**Two decisions, kept apart — this separation is the substantive architectural outcome:**

```
Credential correspondence (CC)          Voting entitlement (ENT)
"does the credential's org match         "is this person an admitted, active
 the election's, and does one exist?"     voter of THIS election?"
        │                                        │
   credential layer                        the repair subject
   ALREADY CONFORMANT — reused,            a fact of (voter, election);
   untouched                               ambient has NO role, either direction
```

**Implemented** (boundary v2 **`3499ea38`** → RED **`32215fea`** → GREEN **`5d46498e`** → pin **`ac313368`**, 2026-08-14) — 3 files, +18/−3:

- `User::isVoterInElection()` — membership lookup drops **only** the `tenant` global scope (SoftDeletes preserved) and requires `organisation_id = <the election's own organisation>`, resolved by a surgical `Election::withoutGlobalScope('tenant')` subquery.
- Cache key namespaced `.v2` so pre-repair results produced under ambient contexts can never be replayed; both invalidation hooks updated.
- `ElectionVotingController` entry projection — same election-derived lookup; **predicate semantics byte-identical** (`status !== 'removed'`), so the open suspension questions stay open.

**Acceptance shape (corrected during review):** the post-repair invariant is **ambient-invariance** — same voter, same election, same valid credential, evaluated under ambient B → A → B → A yields the *identical* answer. The P6 sequence survives only as reproduction of the **old** defect, never as desired behaviour.

**Evidence:** RED (TE2/TE3/TE4 failing) → GREEN 8/8 including TE6 (a soft-deleted election fails closed, proving the narrow scope bypass). Five-path frozen baseline unchanged at 120 non-passing; the one unmapped class proven pre-existing by A/B revert.

**Two review catches worth recording, because both would have shipped:**
1. My first implementation used `Election::withoutGlobalScopes()` — which would have **stripped SoftDeletes too**. Corrected to the single-scope form *before* the implementation commit; TE6 now pins it permanently.
2. My v1 acceptance model would have preserved the very ambient dependency being removed. Corrected in boundary v2.

### 2.3 KOS-OQ-001 — invitation delivery retries (Election-adjacent)

**Implemented** (commit **`3419d08d`**): `SendVoterInvitation`'s hard-coded `3` became `election.invitation_send_attempts` (default 3), with `.env.example` and an entry in the existing `developer_guide/election_engine/election-system.md`.

**Its real value was not the parameter.** The boundary-refinement gate caught, *before implementation*, that adding a `tries()` method while keeping `public $tries = 3` would produce a **method the framework never calls** — `Queue::getJobTries()` resolves `$job->tries ?? $job->tries()`, so the property wins. The configuration key would have existed, looked green, and been permanently inert. T4 now guards it.

---

## 3 · Platform track — what was implemented

### 3.1 KOS-AI-ORCH-001 Increment 1 — the workflow state record

**Implemented** (commit **`c2f5a831`**): `.claude/scripts/workflow-state.php` (AST-015) — one authoritative append-only JSON record per governed work item; state is the **fold** of its transition log; two never-merged records (`sessionRegistry` ≠ `authorityState`); illegal writes are **refused transitions**, not blocked file writes.

Contracts R1–R8 pinned RED-first (11 tests) plus the 65/69 replay criterion. **Independently verified by Session 1, then operationally qualified by signed PO ruling and governance-closed.**

**What it deliberately does NOT do:** enforce anything. A session that ignores the record can still act — Increment 1 makes violations *visible and adjudicable*, not impossible.

### 3.2 KOS-SESSION-DISCOVERY-001 — the Session Assignment Resolver

**Implemented** (boundary **`103d8762`** → **`73d056c8`** → corrective **`84100bb0`**): `.claude/scripts/session-resolve.php` (AST-016, **still `planned`, not adopted**) — a read-only capability answering *"which governed assignment does the record currently expose for this host?"* with verdicts `RESOLVED · UNASSIGNED · AMBIGUOUS · UNRESOLVABLE`.

Load-bearing properties, each pinned by test:
- **No second interpreter.** All workflow interpretation is obtained by invoking AST-015 as a subprocess; the resolver contains no fold, no state machine, no schema knowledge. With AST-015 unavailable it **refuses** rather than falling back (T-13, both halves).
- **Structurally read-only.** No write call exists in the file; proven byte-identical on the real runtime records and across every verdict path including beside a corrupt record (T-11).
- **A produced report is a success** — `UNASSIGNED` and `AMBIGUOUS` exit 0; non-zero is reserved for usage/refusal (T-12).
- **Discovery ≠ Authorization.** Facts 5–6 of the actor conjunction stay `UNKNOWN`; `readOnlyParticipation` stays `NOT EXPRESSIBLE`; every report carries *"Resolution is not activation."*
- **C-1/C-2 corrective:** the report now names **which interpreter answered**, in both renderings, on every verdict — information only, never a judgement; and `KOS_MECHANISM_PATH` is documented truthfully as a runtime-selectable path, not as an impossibility. Final suite **17 passed / 170 assertions**.

---

## 4 · What is NOT implemented

### 4.1 Election — blocked on business/architecture decisions (not on engineering)

| # | Item | Blocked by | Owner |
|---|---|---|---|
| B-1 | **`EM-OPEN-021`** — what an election *is* when its window is open and the candidate invariant is unmet. Today it reaches the engine's **pre-existing invalid-state backstop**: such an election has **no derivable state**, so `close_voting` and even `suspend` are unreachable *from inside* for the duration of the window | no adopted rule names a fallback | **PO/ARB** — urgent; deferral is no longer neutral |
| B-2 | **`start()` — the third ambient-scoped lookup.** `ElectionVotingController::start()` contains the *same* mechanism repaired at the two granted sites, feeding credential issuance. Runtime-reproduced. **Not repaired: outside the two confirmed violations the grant scoped** | needs its own repair authorization | PO/ARB |
| B-3 | **Admission state** (`BR-1.12`) — does admission yield `invited` (approval required) or `active`? Schema, UI, tests and docs all expect `invited`; **no production path writes it** | undecided | PO |
| B-4 | **Suspension mechanics** — `Q3` (where exercisability lives), `BR-1.13` (one- vs two-actor), `BR-1.1/1.2` (removal semantics), `BR-1.8` (restoration authority), `Q-E1` (must suspension block credential issuance / invalidate one?), `Q-E2` (must suspended be distinguishable from already-voted at the gate?) | undecided | PO/ARB |
| B-5 | **Slice-1 entitlement pins** — tests drafted for the adopted entitlement rules; **never executed**, because no canonical Manifesto rule IDs existed for them | Manifesto IDs | Session 2 / PO |
| B-6 | **Boundary-plan Slices 2–4** — import integrity (row errors are silently swallowed; writes are non-atomic; no `assigned_by`/`assigned_at` audit), route/organisation coherence, invitation delivery observability | conditional EP-01 grant needs re-confirmation | PO |
| B-7 | **`AD-2`** — formal ownership of voting-time eligibility resolution. Adopted language points to the Election context; `MB-5`/`PBDIGIT-49` record **multiple implementations and no named authority** | architecture decision | ARB |

### 4.2 Election — verification status, stated precisely

- **EM-VOT-002:** independently verified on both paths by Session 1.
- **`PBDIGIT-65/69`:** implemented and handed off with evidence. **This document does not claim independent verification of the final state**; the implementing stream cannot certify its own work (R-34). Session 1's `start()` reproduction (`16e4a51a`) is evidence *about the gap*, not verification of the repair.
- **Never demonstrated at runtime by anyone:** a first vote. The IERVP programme paused before any vote was cast; `PBDIGIT-64`/`65`/`69` were all found *before* the ballot.

### 4.3 Election — deliberately out of scope throughout

Full Membership Mode in its entirety (including the broken `ElectionMembership::bulkAssignVoters()` import path, which is a guaranteed exception); the wider `BelongsToTenant` family (41 consumers, 19 outside Decision A); the 375 `withoutGlobalScopes()` bypass sites; `voter_count`; `has_voters`; `EM-VOT-003` implementation; the Constitution; ADR-002.

### 4.4 Platform — not implemented

| # | Item | Status |
|---|---|---|
| P-1 | **Increment 2 — physical enforcement** (locks, leases, write prevention, CI gates) | NOT AUTHORIZED, not designed. Increment 1 makes violations visible, never impossible |
| P-2 | **`D-5` SESSION_START wiring** — surfacing the record automatically at session start | deliberately deferred; keeps "no `.claude` restructuring" trivially true |
| P-3 | **`D-1`** transition timestamps (staleness is currently **uncomputable**) · **`D-2`** grant↔session/role linkage (why authorization facts 5–6 are `UNKNOWN`) · **`D-3`** grant lifecycle/closure · **`D-4`** native `resolve` subcommand · **`D-6`** read-only-participation vocabulary | each a separately governed evolution |
| P-4 | **`AST-016` adoption** | remains `planned`; adoption follows independent verification and qualification, not the file's existence |
| P-5 | **C-1/C-2 corrective verification** | `S1-verify-discovery-corrective` is `CREATED`, not ACTIVE |

---

## 5 · What was PLANNED to complete Election-Only Mode

This is the plan as it stood when the Election track was interrupted — recorded so the next implementer inherits a route, not an archaeology exercise.

### 5.1 The complete Election-Only voter journey, stage by stage

```
1 election created (voter_source_strategy = election_only)   ✅ works
2 Chief imports voters → ElectionMembership created           ✅ works, 5 defects (§5.2 Slice 2)
3 invitation delivered → voter sets password                  ✅ works, delivery state invisible
4 entitlement exists (election-specific, no org membership)    ✅ adopted + implemented
5 Chief suspension / removal / restore                        🔵 BLOCKED — Q3, BR-1.13, BR-1.1/1.2, BR-1.8
6 election lifecycle opens voting                             ✅ EM-VOT-002 enforced both paths
7 voter recognised as entitled at entry                       ✅ repaired (65/69) — start() still open (B-2)
8 credential issued (VoterSlug + codes)                       🔵 BLOCKED — Q-E1 (suspended voter still gets one)
9 ballot access (gate)                                        ✅ enforced; Q-E2 open (suspended vs already-voted)
10 vote submitted + persisted (anonymous)                     ⚠️ NEVER DEMONSTRATED AT RUNTIME
11 one-vote / exhaustion                                      ✅ enforced on codes.has_voted
12 results / publication                                      🔵 BLOCKED — visibility capability unimplemented (60/D-2)
```

**Stage 10 is the whole point and remains unproven.** Every defect found so far was found *upstream* of it.

### 5.2 The four planned implementation slices (boundary plan, EP-01 approved with conditions)

| Slice | Content | Status |
|---|---|---|
| **1 · Entitlement pins** | tests pinning the adopted rules: no `Member` aggregate required at the ballot gate · election-specificity (membership in X grants nothing in Y) · suspended member cannot vote · **credential possession does not override suspension** · one-vote rule | **drafted, never executed** — gated on Manifesto rule IDs (B-5). Protective only; no production change intended |
| **2 · Import integrity** | surface the silently-swallowed row errors · wrap the 5 writes per row in a transaction · record `assigned_by`/`assigned_at` | **not started** — needs PO re-confirmation of the conditional grant (B-6) |
| **3 · Route/organisation coherence** | `VoterImportController::resolveElection()` never checks that the election belongs to the route's organisation (the sibling controller does) | **not started** — one `abort_if` |
| **4 · Invitation observability** | the Chief cannot see `email_status`; a failed invitation is invisible; no resend | **not started** — projection only |

Slices 2–4 were chosen precisely because **none depends on an open governance question**. Slice 1 was chosen first because the estate asserts *none* of the adopted entitlement rules today.

### 5.3 Readiness matrix — the 16 capabilities

GREEN = implemented **and independently verified** · YELLOW = implemented, verification incomplete · RED = known defect · BLUE = decision required.

| Capability | State | What it needs |
|---|---|---|
| 1 admission / import | 🟡🔵 | Slice 2 + `BR-1.12` |
| 2 configuration (windows, timezone) | 🔵 | `59` timestamp authority + `67` `D-1…D-4` — **windows are measurably 60–120 min wrong** |
| 3 nomination | 🟡 | `EM-OPEN-019` (30 vs 40) |
| 4 candidate approval | 🟡 | whether approval itself derives lifecycle state (EM-OPEN-021 exit 2) NOT ESTABLISHED |
| **5 voting activation** | ✅ **GREEN** | — *(the only GREEN row)* |
| 6 lifecycle derivation | 🔴🔵 | `EM-OPEN-021` — derivation is not total |
| 7 voting-time eligibility | 🟡🔵 | repaired at 2 sites; `start()` (B-2) + `AD-2` ownership |
| 8 entitlement record | 🟡🔵 | `Q3` — suspension enforcement is incidental (`status` overload is load-bearing) |
| 9 voter assignment (admission-time) | 🟡 | mechanically enforced; confidence pending falsification |
| 10 voting access / credential | 🟡🔵 | `Q-E1`, `Q-E2` |
| 11 vote submission / one-vote | 🟡 | **first vote never runtime-verified** |
| 12 voting closure | 🔴 | `close_voting` throws in the anomalous state |
| 13 results / publication | 🔵 | ruling exists; capability unimplemented (`60` `D-2`) |
| 14 audit / security | 🟡 | 4 of 6 governance acts write no audit; none reaches the election trail |
| 15 authorization (officer) | 🟡 | `SD-15` ratification |
| 16 tenant isolation vs election scoping | 🔴🔵 | `BelongsToTenant` family repair scope undecided |

### 5.4 Critical path to "Election-Only READY"

```
ADOPTED RULES
     │
     ├── EM-VOT-002 ........................ ✅ DONE (verified)
     │
     ├── 65/69 entitlement ................. ✅ repaired at 2 sites
     │        └── start() (B-2) ............ 🔴 third site, unauthorized
     │
     ├── EM-OPEN-021 ....................... 🔵 PO — unblocks capabilities 6 and 12
     │
     ├── BR-1.12 admission state ........... 🔵 PO — unblocks capability 1 + Slice 1's admission pin
     │
     ├── Q3 / BR-1.13 / Q-E1 / Q-E2 ........ 🔵 PO+ARB — unblocks capabilities 5, 8, 10
     │
     ├── 59 + 67 time semantics ............ 🔵 PO — windows are wrong on live data
     │
     ├── 60 results visibility ............. 🔵 PO re-confirmation
     │
     └── THEN: run the journey end to end and cast the first vote ... ⬜ never done
```

**Two decisions unblock the most:** `EM-OPEN-021` (lifecycle safety — an election in the anomalous shape is unmanageable for its whole window) and the `AD-2` + `BR-1.12` pair (the eligibility/admission model itself, gating capabilities 1, 7 and 16). They are different kinds of urgency; neither is ranked above the other here.

**Definition of done for the mode, as the programme framed it:** not "the tests are green" but *an organisation can run an Election-Only election end to end, with a real voter casting a real vote, verified independently.* Nothing in this document claims that has happened.

## 6 · Open observations — recorded, not repaired

- **O-1 · The verifier-bootstrap gap.** The mechanism has no path for an implementer to hand off to a verifier who does not yet exist: `HANDOFF` refuses an unregistered successor, and `REGISTER` is Governance's act. Both Session 1 and Session 3 hit this independently.
- **O-2 · The handoff at seq 16 is not recognised as `S1-verify-discovery-corrective`'s predecessor handoff.** The resolver reports **both** activation facts missing, so registering only the human START act will *not* activate S1. Two things must be closed, not one.
- **O-3 · `KOS_MECHANISM_PATH` is a runtime-selectable interpreter path.** Honestly documented as such after C-2; the resolver reports which interpreter answered so substitution is visible rather than silent. It is not a hardened boundary and must not be described as one.
- **O-4 · Test-estate debt, untouched:** 7 rows encoding the *superseded* "window open ⇒ voting_active" rule; `VoterStrategySnapshotTest` ×3 unread and unclassified; ~6 Security-cluster rows with `MECHANISM NOT ESTABLISHED`; the pre-existing `models`/`http` developer-guide debt from `PBDIGIT-65`.
- **O-5 · Process failures of my own, recorded rather than buried:** I once swept another session's staged files into a commit (provenance corrected in the session log, history not rewritten); I once wrote a test file claiming an approval that did not exist (superseded and deleted); I twice proposed an over-broad scope bypass caught in review. The commit-hygiene protocol in `MEMORY.md` exists because of the first.

---

## 7 · The through-line

The Election work produced the platform work. Each Election defect was a *governance* failure wearing implementation clothes: a rule enforced on one path and not another (`EM-VOT-002`); infrastructure silently deciding a domain question (`65`); a cache transporting an answer across a boundary it never knew existed (`69`). The orchestration mechanism exists so the next such failure is **visible in a record** instead of reconstructable only from prose — and the resolver exists so a session can ask *"what am I permitted to be?"* instead of inferring it from a prompt.

**None of that is finished.** The record makes violations adjudicable, not impossible; the resolver reports identity, not authority; and the Election-Only journey still has no demonstrated vote.

---

**Traceability:** `f2c2cc4e` · `db5ec7a8` · `3499ea38` · `32215fea` · `5d46498e` · `ac313368` · `3419d08d` · `c2f5a831` · `103d8762` · `73d056c8` · `84100bb0` · boundary plan `docs/plans/20260812-1748-election-only-implementation-boundary-plan.md` · readiness gate `2026-08-13-election-only-implementation-readiness-gate.md` · EM-VOT-002 audit `2026-08-13-em-vot-002-implementation-boundary-audit.md` · 65/69 boundary `2026-08-14-6569-implementation-boundary-proposal.md` · Session 1 verification `2026-08-13-election-only-independent-verification.md` · Session 2 disposition `2026-08-13-6569-repair-disposition-package.md`.

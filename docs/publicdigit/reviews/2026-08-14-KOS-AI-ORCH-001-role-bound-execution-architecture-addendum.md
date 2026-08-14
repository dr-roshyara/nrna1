# KOS-AI-ORCH-001 — Role-Bound Execution
# Architecture Addendum to the Increment-1 Boundary Proposal

**Session 4 (ARCHITECTURE stream) · 2026-08-14 · addendum to `2026-08-14-KOS-AI-ORCH-001-implementation-boundary-proposal.md` (`9b69ab76`, PROPOSED — NOT HUMAN APPROVED)**

> ## PROPOSED — NOT HUMAN APPROVED
> This addendum evaluates the role-bound-execution concept architecturally and classifies where it belongs. It approves nothing, authorizes nothing, implements nothing, and does not amend the accepted rule — rule amendment is Governance's act. Central deliverable: **the Increment-1 / rule-track / Increment-2 split in §10**, for the PO to decide on.

**⛔ Produced architecture-only: no code, no tests, no `workflow_engine`/`session_manager`/`platform_registry` change, no `.claude` change, no hook/lock/lease, no Election change, no governance decision.**

---

## 1 · Problem statement

Sessions currently establish "what type of work am I doing?" by convention — a role line in a commission prompt, a table row in CONTEXT.md. Nothing governed answers it, nothing records when it changes, and nothing distinguishes *declaring* a role from *being authorized* in it. The observed hazard class is real and already evidenced: F8/F9 (identity/authority ambiguity) are the accepted rule's own motivating failures, and this repository has already seen a session act on a role claim ("human-approved") that was false (premature test file, boundary proposal §1.1). The proposed concept: every governed session is bound to exactly one role per work-item assignment, verifies role/state/authority/ownership/boundary before working, and changes role only through a governed transition — while role never becomes authorization.

## 2 · Domain interpretation

The orchestration domain the accepted rule + Increment-1 boundary already define is: **a WorkItem with an authoritative record, whose Sessions carry identity, whose mutations flow through owned transitions, and whose authority lives in a separate Governance-written record.** Role-bound execution is not a new domain — it is the *sharpening of one existing attribute (role) into a bound, immutable-per-assignment concept with a verification protocol*. The correct question is therefore not "what new subsystem?" but "which existing concept carries this, and what is genuinely new?" (ES-001.1; the platform's own stopping rule).

**What is genuinely new** (not already in the accepted rule or the Increment-1 boundary):
1. Role **immutability per assignment** — role change = new assignment, never mutation of an existing one.
2. The **mandatory startup check** as an articulated protocol (the rule has a consultation duty; it does not enumerate the checklist).
3. An explicit **role-capability model** (role + boundary + authorization ⇒ capability; never an action blacklist).

**What is NOT new** (already accepted rule text — cite, don't duplicate): role in session identity (C-2) · role boundaries ("Governance authorizes. Implementation executes. Verification independently verifies. No role may infer authority from another role's files, commits, tests, or observations" — KOS-AI-ORCH-001 verbatim) · role ≠ authorization (§10a: ACTIVE ≠ AUTHORIZED; G-2) · workflow-declared role sets (§4/§6).

## 3 · Four role definitions *(canonical platform set — see §15-Q1 for the open/closed question)*

| Role | Does | Does NOT |
|---|---|---|
| **ARCHITECTURE** | defines boundaries, structure, system shape; runs read-only architectural checks; inspects code; produces boundary/design proposals | implement production code · make governance decisions · certify implementation |
| **GOVERNANCE** | determines what is allowed; registers/rules authorization when a human act exists; reconciles gates; records closure (G-1) | implement production code · substitute for architecture · manufacture authority without a recorded human act (G-2) |
| **IMPLEMENTATION** | implements the approved architecture inside the authorized boundary; runs the tests TDD requires; produces evidence | redesign architecture silently · grant itself authorization · self-certify (R-34) |
| **VERIFICATION** | independently checks implementation against approved contracts; runs tests; inspects; attempts falsification; produces evidence | repair the implementation · close the work item (G-1: it REPORTS) · acquire implementation authority |

**Capability model (architectural contract, not a blacklist):** an action is permitted iff `role admits the action-class` ∧ `the action is inside the approved boundary` ∧ `required authorization exists and is active` ∧ `where the action mutates the shared context, the session holds mutation ownership`. "Run tests" is thus permitted to IMPLEMENTATION (TDD, inside boundary) *and* VERIFICATION (evidence), and even ARCHITECTURE for read-only fitness checks — the role differentiates *purpose and permitted consequence*, not the shell command.

## 4 · Proposed invariants — evaluated

| Inv | Text (as proposed) | Architectural evaluation |
|---|---|---|
| **ROLE-1** | exactly one role per session per work-item assignment | **Sound; already structurally true in the Increment-1 schema** (role is a scalar field of a session entry). One precision required: "exactly one role **from the workflow's declared role set**" — the accepted rule makes the set workflow-declared and open ("others per workflow, e.g. Architecture", §4; "the workflow declares its role set at creation", §6). A closed four-enum would silently amend accepted rule text → §15-Q1 |
| **ROLE-2** | no governed work outside role + boundary | **Sound as RULE TEXT; representable but not enforceable in Increment 1.** The record can carry role and boundary and make a violation *adjudicable*; making it *impossible* is enforcement = Increment 2. Same slicing as the mutation-ownership invariant itself |
| **ROLE-3** | role declaration ≠ authorization; authorization and ownership stay separate | **Already structurally guaranteed** by the Increment-1 design: role lives in `sessionRegistry`; authority lives in `authorityState`; the records are disjoint with disjoint writers (Inv G/H); R6 makes ACTIVE-but-unauthorized first-class. **Zero delta** — the invariant is a *property* of the approved-to-be design, and this addendum records that explicitly |
| **ROLE-4** | role change only by explicit governed transition | **Sound, with one architectural refinement (the user's own):** not "the session's role field transitions" but **role is immutable per session record; a role change is a NEW assignment** — a new session entry with a fresh sessionId, `predecessor` = the completed/handed-off prior assignment, and its own G-3 start conjunction. This reuses the *existing* transition machinery (HANDOFF + START) instead of inventing a role-mutation transition — smallest possible delta |
| **ROLE-5** | mandatory startup check; STOP on missing/contradictory authority | **Sound; it is the articulated form of the consultation duty Increment 1 already carries** (commission §6: consult before mutating and before acting on authority). The eight questions are all answerable from the record (§4 of the boundary: identity, state, owner, grants, boundary, predecessor, exclusions). As *protocol text* it is convention/rule-track; as *enforced gating* (a hook that blocks) it is Increment 2 |

**STOP conditions (ROLE-5's decision procedure)** — adopted verbatim as the protocol's semantics: unknown role → STOP · required authorization absent → STOP · mutation ownership not held (for mutating work) → STOP · requested action outside role → STOP · boundary unclear → STOP · otherwise proceed. **STOP means: read-only, report, escalate to Governance — never infer authority from absence** (identical to the boundary's failure semantics, §9).

## 5 · Role · State · Authorization · Ownership · Boundary — the relationships

```
        SessionAssignment (one session entry in the work item's record)
        ├── Role                 WHAT KIND of work — immutable per assignment
        ├── State                WHERE in the lifecycle (CREATED…STOPPED) — mutable via transitions
        ├── Authorization LINK   reference into authorityState — NEVER a contained property
        └── (ownership)          held or not — a property of the WORK ITEM (one field), not of the session

   Role ≠ Authorization   (IMPLEMENTATION with no active grant: representable, queryable, NOT permitted to implement — R6)
   Role ≠ Ownership       (an owning ARCHITECTURE session still may not implement — role admits no such action)
   Role ≠ State           (an ACTIVE VERIFICATION session is not thereby authorized — R6)
   Boundary ∈ Grant       (boundary is a property of the authorization, not of the role — the same role has
                           different boundaries under different grants)

   PERMISSION TO ACT = Role ∧ Boundary ∧ active Authorization ∧ (Ownership, if mutating)   — conjunction, no single source
```

This is the same conjunction discipline as G-3 (start = handoff AND human act): **no single fact ever suffices.**

## 6 · Session startup / role-identification protocol *(protocol text — realization per §10 classification)*

Before governed work, a session establishes from the authoritative record (never from prose interpretation):

```
1. WORK ITEM        which record am I in?
2. ROLE             my assignment's role (one, from the workflow's declared set)
3. WORKFLOW STATE   the fold's current state for me and the item
4. MUTATION OWNER   who holds it (me? someone? nobody?)
5. AUTHORIZATION    which grants exist, active?, covering what scope?
6. BOUNDARY         the approved boundary of the grant I act under
7. PREDECESSOR      whose handoff admitted me; token reference
8. PROHIBITIONS     the grant's exclusions + my role's does-NOT column
→ any missing/contradictory → STOP (read-only, report, escalate)
```

In Increment 1 the session performs this check itself against the record (the consultation duty). Injection at SESSION_START via `session_manager` remains the **deferred candidate** it already was (boundary §6.3) — unchanged by this addendum.

## 7 · Role transition / handoff semantics

**No new transition type is introduced.** Role change = assignment change, composed entirely of existing machinery:

```
Session S4-a (ARCHITECTURE, ACTIVE) ──HANDOFF(token)──▶ HANDED_OFF        ← existing §7 transition
                                                             │
   NEW ASSIGNMENT: session entry S4-b {role: VERIFICATION, predecessor: S4-a}   ← REGISTER (existing)
                                                             │
S4-b ──START(G-3: predecessor handoff AND human start act)──▶ ACTIVE       ← existing conjunction
```

- The same underlying Claude process MAY carry both assignments **sequentially**; the *assignment* is the governed unit, not the process (consistent with terminal-agnosticism, §8).
- An informal "now I'll also verify" has no record path: there is no transition that mutates `role`, so the fold simply never shows it — **role immutability is structural, like single-ownership** (candidate contract R8, §14).
- R-34 composition note: a process that implemented may not verify its own implementation *regardless* of a new assignment — that prohibition is R-34's (independence), not the role model's; the role model merely makes the history queryable so R-34 violations become visible.

## 8 · Concurrency implications

None new. The invariant remains **one governed mutation owner per work item** — never one terminal, never one role-per-terminal. Role-bound execution works across terminals A/B/C (ARCHITECTURE / IMPLEMENTATION / VERIFICATION) exactly because identity, role, ownership, and authorization are properties of the *record*, not of the process arrangement: reads stay concurrent; roles without ownership read and report; the single mutating session is whichever assignment currently holds ownership. Terminal identity is not, and must not become, the authority mechanism (accepted rule §7; boundary §11 unchanged).

## 9 · DDD classification

| Concept | Classification |
|---|---|
| **WorkItem** | **entity / aggregate root** of the orchestration record (identity, per-item isolation — Inv A/J) |
| **SessionAssignment** | **entity** within the aggregate — *the* first-class concept this addendum sharpens: the binding {session, work item, role}. In the Increment-1 schema a `sessionRegistry.sessions[]` entry already **is** an assignment (it exists only inside one work-item record); no schema change — a naming/semantics clarification |
| **Role** | **value object** (enumerated, from the workflow-declared set); immutable attribute of an assignment |
| **WorkflowState** | **value object** (the §5 state machine's vocabulary) |
| **Authorization (Grant)** | **entity in the separate Authority State record** — governance's, not orchestration's, per G-2; orchestration links to it by reference only |
| **MutationOwnership** | **relationship/attribute of the WorkItem** (single-valued field) — not an entity, not a session property |
| **Handoff / Transition** | **domain events** (append-only log entries; state = fold) |
| **Boundary** | **value object owned by the Grant** (scope/boundary/exclusions fields) — never a role property |
| **Startup check** | **protocol** (application-level duty), not a domain concept |
| **Terminal / process** | **not a domain concept** (accepted rule §4) |
| FQCNs, file formats, APIs | implementation details (Session 3's, post-approval, inside §10 placement constraints) |

## 10 · Increment-1 vs rule-track vs Increment-2 classification *(the commission's central question)*

| Element | Classification | Reason |
|---|---|---|
| Role as queryable, per-assignment identity attribute | **ALREADY INSIDE Increment 1** | Inv B field; R2 pins it; zero delta |
| Role ≠ authorization, structurally | **ALREADY INSIDE Increment 1** | disjoint records (Inv G/H), R6; zero delta |
| Role **immutability** per assignment; role change = new assignment via existing REGISTER/HANDOFF/START | **INCREMENT-1 REFINEMENT** (representation only) | uses only existing transitions; one added fold rule ("no transition mutates role"); candidate contract R8 (§14) — a small **amendment to the not-yet-approved boundary**, decidable in the same human review |
| Startup-check protocol text (§6) | **RULE-TRACK / convention** | the record makes it *answerable* (Inc-1); mandating it is rule text for Governance to accept; no mechanism needed to start practising it |
| INV-ORCH-ROLE-1…5 as binding rule text (or KOS-AI-ORCH-002) | **RULE-TRACK — Governance acceptance required** | rules are accepted by PO/ARB, never by architecture (the ORCH-001 precedent: rule first, mechanism later). **Parsimony flag (ES-001.1):** ROLE-3 and much of ROLE-1/2 restate accepted ORCH-001 text — Governance should weigh **amending KOS-AI-ORCH-001** (a G-7-style amendment) against minting **KOS-AI-ORCH-002**; recommendation in §16 |
| Enforced role gating (blocking work outside role; startup gating hooks; role-aware locks) | **INCREMENT 2 — NOT AUTHORIZED, not designed** | physical enforcement, exactly like ownership enforcement |
| Role-capability matrices wired into `.claude` hooks/settings | **INCREMENT 2 — NOT AUTHORIZED** | `.claude` restructuring excluded |

**Answer to the PO's question:** role-bound execution does **not** require expanding Increment 1. Its representation is already ~95% inside the proposed boundary; the genuine additions are **one representational refinement** (role immutability, R8) that fits the pending boundary approval, **rule text** that belongs to Governance's track, and **enforcement** that is Increment 2 by the same doctrine that made the ownership record precede ownership enforcement.

## 11 · Minimum architectural delta to the committed boundary (`9b69ab76`)

1. **§4-A clarification:** a `sessionRegistry.sessions[]` entry is a **SessionAssignment**; its `role` field is **immutable for the life of the entry**; role values come from the work item's declared `roles` set. *(No schema change — semantics.)*
2. **§5/§7 addition (one fold rule):** no transition type mutates `role`; reassignment = new entry with `predecessor` linkage + G-3 START. *(No new transition type.)*
3. **Candidate contract R8** (§14) — offered to the same human approval as R1–R7.
4. **§6 protocol text** recorded as the articulated consultation duty. *(No wiring — §6.3 deferral unchanged.)*

**Everything else: no change.** State model, authority model, ownership, handoff, concurrency, append-only/fold, CMP-004 semantics / CMP-002 namespace / CMP-008 registration-only, runtime location, R1–R7 — all stand as committed. **If the PO prefers zero amendment, Increment 1 is viable without R8**: role immutability then remains rule-text-only until a later increment, and nothing in R1–R7 breaks.

## 12 · Explicitly OUT OF SCOPE

Enforced role gating and every blocking mechanism · hooks, locks, leases · `.claude` restructuring (incl. startup-check injection — §6.3 deferral stands) · per-stream logging · terminal orchestration · registry schema changes · rule acceptance itself (Governance's) · new authority models · the premature test file's disposition (boundary Q1, unchanged) · Election work, EM-OPEN-021, all Election grants (NONE, unchanged) · any KOS-AI-ORCH-002 *acceptance* — this addendum only drafts material for that decision.

## 13 · Required human / PO / ARB decisions

| # | Decision | Options |
|---|---|---|
| D-1 | Accept role-bound execution as **rule text**? | amend KOS-AI-ORCH-001 · mint KOS-AI-ORCH-002 · reject |
| D-2 | Approve the boundary **with** the §11 delta (incl. R8) or **as committed** (role immutability stays rule-text-only)? | with delta · as committed |
| D-3 | Role set: closed four-enum or workflow-declared with the four as the canonical platform set? | the accepted rule currently says **workflow-declared** (§15-Q1) — closing it amends accepted text |
| D-4 | Startup check: interim convention now (practised, unenforced) vs waiting for rule acceptance? | convention now · rule first |
| D-5 | Same-process reassignment (one Claude process, sequential assignments): permitted with R-34's independence limit, or one-process-one-role? | recommendation §16 |

## 14 · Proposed RED contract — only what is genuinely inside the (pending) authorized boundary

**R8 (candidate — only if D-2 = "with delta"):**
*Given* a work-item record with assignment S having role X, *when* any transition attempts to change S's role, or a session asserts work under a role different from its assignment's, *then* the transition is refused by the fold and the record still shows S:role=X; a legitimate role change appears only as a **new assignment** entry with predecessor S and its own G-3 START conjunction.

No other new contracts. R1–R7 already cover identity (R2 pins the role field), authority separation (R5/R6), and transitions (R3/R4). **No test is created here; R8 is written by Session 3 only after human approval, against the contract — never against a class name** (boundary §15 discipline applies unchanged).

## 15 · Open questions

| # | Question |
|---|---|
| Q1 | **Open vs closed role set.** Accepted rule text: workflow-declared, "others per workflow". Proposed ROLE-1 (as drafted): a closed four-enum. These conflict; only Governance may amend accepted text. Architecture's input: the *four* are the proven canonical set (this programme ran exactly them), and closing the set would forbid the rule's own example flexibility — recommend canonical-four + workflow-declared extensions |
| Q2 | Does the startup check's STOP produce a recorded event (a STOPPED-adjacent fact) or only behaviour? Representable either way in Increment 1; recording it is one more transition type — parsimony says defer until evidence |
| Q3 | Cross-work-item role consistency: may one process hold ARCHITECTURE on item A and IMPLEMENTATION on item B concurrently? The records are isolated (Inv J) so it is representable; whether it is *wise* is rule-track |
| Q4 | Where does the role-capability table (§3) live once accepted — rule text or a derived registry artifact? (ES-005.4: one home) |

## 16 · Recommendation

1. **Approve the Increment-1 boundary with the §11 delta** (it is four clarifications and one candidate contract; the mechanism, scope, and exclusions are untouched) — or as committed, if minimal-change is preferred; both are architecturally sound.
2. **Route INV-ORCH-ROLE-1…5 to Governance as an amendment proposal to KOS-AI-ORCH-001** rather than a new ORCH-002: ROLE-3 and most of ROLE-1/2 already exist as accepted text, and the platform's stopping rule asks "which existing rule owns this?" first. Mint ORCH-002 only if Governance finds role-bound execution a genuinely separate concern. *(Recommendation, not a decision — R-34.)*
3. **Adopt the startup check as an interim convention immediately** (D-4): it needs no mechanism, it is answerable from the record once Increment 1 lands, and it is exactly the discipline whose absence let a premature test claim approval.
4. **Permit same-process sequential reassignment (D-5) with R-34's independence limit intact** — one-process-one-role would encode process identity into governance, repeating the same-terminal category error.
5. **Keep all enforcement in Increment 2**, where the ownership doctrine already put it: record first, then enforcement of the recorded.

---

**Traceability:** boundary proposal `9b69ab76` (§4/§5/§6.3/§7/§9/§11/§15, Q1) · commission `7cbe5984` (§6 consultation duty, §9 delegation, §15 authorization) · accepted rule + G-1…G-4 (`51ba56fd`; §4 role definition, §6 role-set declaration, §10a, §14 role table, §15 authority model) · R-34 · ES-001.1 (parsimony → amend-vs-mint) · ES-005.4 (one home, Q4) · F8/F9 (identity failures) · premature-test incident (boundary §1.1 — the worked example of role/authority claims needing verification) · G-2/G-3 (the conjunction discipline §5 generalizes).

---

> # PROPOSED — NOT HUMAN APPROVED

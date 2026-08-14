# KOS-AI-ORCH-001 — Proposed Platform Implementation Commission

**Type:** Governance preparation (Session 2) · **Date:** 2026-08-14
> ## **"This artifact is a proposed implementation commission and does not itself create implementation authority."** *(commission-required statement, §14 — TRUE at writing and still true: the authority below was created by the PO's ruling, not by this artifact.)*
>
> ## ☑ AUTHORIZED (PO, 2026-08-14 — performative, verbatim in §15) — INCREMENT 1 EXACTLY AS PRESENTED, gates uncollapsed.
> **G-6 RULED:** *"KOS-AI-ORCH-001 implementation proceeds under reading (ii)+(iii), with the F1–F9 evidence set recorded as the qualifying operational evidence where required."* — the methodology-freeze exception (2026-08-01) is formally invoked with F1–F9 as the qualifying "genuine deficiency", AND R-37's burden of proof is ruled met by the same evidence; where `engineering/` is touched, this commission is the "explicitly commissioned by the Decision Authority" act its freeze names.
> **Explicit exclusions restated by the authorization:** no scope expansion · no Increment-2 enforcement · no locks, leases, hooks, `.claude` restructuring, per-stream logging, Election changes, or unrelated platform work.
> **Gate state:** PO authorization ✅ → **NEXT: Session 4 architecture/design boundary** (delivery of this commission to Session 4 is the PO's act in that stream) → human boundary approval ⬜ → RED ⬜ → implementation ⬜ → GREEN ⬜ → regression ⬜ → Session 1 independent verification ⬜ → operational qualification ⬜ → Governance closure (G-1) ⬜. **Session 2 registers and reconciles; it does not implement.**
**⛔ Nothing implemented · no `workflow_engine`/`session_manager`/`platform_registry`/`.claude` change · no hook, lock, lease, or registry created · no terminal/session behavior changed · the stage chain is preserved uncollapsed: GOVERNANCE RULE → IMPLEMENTATION AUTHORIZATION → IMPLEMENTATION DESIGN → IMPLEMENTATION → INDEPENDENT VERIFICATION → OPERATIONAL QUALIFICATION.**

---

## 1 · Current authority state *(reconstructed from the repository, this session)*

| Item | Verified state |
|---|---|
| `KOS-AI-ORCH-001` | ✅ **ACCEPTED WITH AMENDMENTS (PO/ARB, 2026-08-14, verbatim in the ARB review §Acceptance)** — **RULE ONLY** |
| Amendments G-1…G-4 | ✅ applied inline, dated (G-1 closure-is-governance · G-2 Authority-State writer · G-3 start = handoff AND human act · G-4 per-stream logs = candidate mechanism) |
| G-5 (placement) | 🟡 **provisional** — directed location accepted pending the ADR:OQ-2 ruling |
| G-6 (freeze reading) | 🟡 **open** — the interpretation must be stated in the implementation-authority decision; **not invented here** (§4) |
| "Same terminal" | interim **operational convention only** (accepted principle 5) — not architectural, not governance (§9) |
| Orchestration mechanism | ⛔ **NONE authorized.** This artifact does not change that |

## 2 · The accepted rule being implemented

`KOS-AI-ORCH-001` + accepted principles 1–7: one authoritative workflow state per governed work item · coordinated execution context with explicit mutation ownership and token handoff · Session Registry ≠ Authority State · ACTIVE ≠ AUTHORIZED / OWNERSHIP ≠ AUTHORITY / EXISTENCE ≠ PERMISSION · same-item isolation exceptional and coordinated · the workflow engine coordinates, never creates/interprets/promotes authority — with the four amendments as rule text.

## 3 · G-5 status — does provisional placement block this commission?

**NO — placement does not block.** The rule is citable by **identity** (`KOS-AI-ORCH-001`), which is stable regardless of file location; implementation consumes the rule's *content*, not its path; and the acceptance explicitly made the location provisional rather than conditional. The ADR:OQ-2 ruling remains open **independently** and should not be resolved as a side effect of implementation convenience. *(The rule is not moved, and this commission must not move it.)*

## 4 · G-6 status — the freeze question, made precise for a one-line ruling

**What actually requires a freeze reading:** implementing an orchestration mechanism is new platform capability. Three distinct recorded freezes could be read to govern it — **the PO/ARB must select which reading applies before implementation authority is issued:**

| Reading | The freeze text implicated | What selecting it means |
|---|---|---|
| **(i) Execution policy — no freeze implicated** | none | orchestration is execution policy, outside the methodology; implementation needs only the normal EP-01 authorization |
| **(ii) Methodology-freeze exception invoked** | *"THE METHODOLOGY IS FROZEN (2026-08-01)… no KnowledgeOS proposals — **unless PublicDigit implementation exposes a genuine deficiency**"* (`.claude/CLAUDE.md`) | the F1–F9 failure set from the Election programme is ruled the qualifying "genuine deficiency"; the exception is formally invoked and recorded |
| **(iii) R-37 burden-of-proof satisfied** | *"software mechanisms may exist only after operational evidence shows the governance model insufficient"* (Platform Baseline; plus `engineering/` R-37 structural / R-38 conceptual freeze, whose named exception path is "explicitly commissioned by the Decision Authority") | F1–F9 is ruled the operational evidence meeting R-37's burden; if implementation must touch `engineering/`, the commission itself is the "explicitly commissioned" exception act |

**These are not mutually exclusive** — (ii) and (iii) can both be true; (i) makes them moot. **Formulated one-line PO ruling:**

> *"G-6 ruling: the KOS-AI-ORCH-001 implementation proceeds under reading ___ [(i) / (ii) / (iii) / (ii)+(iii)], with the F1–F9 evidence set recorded as the qualifying operational evidence where a reading requires it."*

## 5 · Implementation objective

Realize the accepted rule's invariants as **the smallest coherent increment** — chosen below by the platform's own doctrine (record before enforcement; governance precedes automation):

> ## **Increment 1 — THE AUTHORITATIVE WORKFLOW STATE RECORD: make the workflow state EXIST, be QUERYABLE, and be the single source sessions consult — before any physical enforcement.**

**Why this slice is smallest-coherent:** the record cures the worst measured failures by itself — F5/F9 (divergent authorization state, ambiguous authority) are *representation* failures cured by one authoritative record; F7 (stale directives) is cured by handoff-as-recorded-transition; F1/F2/F4/F6 (git-level collisions) are *mitigated* by visible ownership and fully cured only by **physical enforcement — deliberately a LATER increment**, so that enforcement mechanics never get designed before the state they enforce exists. Every accepted invariant lands in this slice as a property of the record (mapping in §6); nothing in the slice presupposes a mechanism choice (§9-of-scope).

## 6 · Smallest implementation boundary *(rule-requirement → slice mapping; commission §2's A–J)*

| Accepted requirement | In Increment 1 as |
|---|---|
| A · one authoritative workflow state | the record itself, one per governed work item |
| B · session identity `{workflow · work-item · role · predecessor · state · authorization · context}` | the Session Registry portion of the record |
| C · exclusive mutation ownership | an ownership **field** with transition rules — *represented and consulted; physically enforced in a later increment* |
| D · token-carrying handoff | handoff = a recorded transition carrying its token (grant/evidence reference); no transition, no handoff |
| E · sticky STOPPED | a state-machine rule of the record: no transition out of STOPPED except an explicit recorded continuation |
| F · start = predecessor handoff AND human start act (G-3) | a transition precondition: both facts recorded, conjunction checked |
| G · Session Registry ≠ Authority State | **two records**, never merged, jointly queryable |
| H · Authority-State writer restriction (G-2) | a write rule: Governance role only, registering a recorded Human/PO/ARB act only |
| I · engine coordinates, never manufactures authority | the record exposes queries; it contains no decision logic; G-1 (closure = governance act) is a transition rule |
| J · different work items concurrent | records are per-work-item; no cross-item coupling |

**Session consultation duty (the increment's behavioural change):** a session belonging to a governed work item consults the record before mutating the shared execution context and before acting on authority — replacing document interpretation with the query the rule requires.

## 7 · In-scope components

`workflow_engine` (CMP-004 — the conceptual owner; gains its first real contract) · `session_manager` (CMP-002 — session identity at start; continuity already exists) · `platform_registry` (CMP-008 — the natural registration point, **if** design confirms it fits without schema violence) · the state representation itself (WHERE it lives is a §9 design question) · the query/write protocols of §6-G/H · handoff transition semantics · the verification evidence of §10–§12. **Extension of existing components — no new independent subsystem** (accepted proposal §14).

## 8 · Explicit exclusions

Election implementation and all Election business/domain rules · any new business authority · redesign of the AI Engineering Platform or replacement of the Platform Baseline · speculative orchestration features (scheduling, parallel same-item coordination mechanics — the §12 exceptional case is NOT implemented in Increment 1) · broad `.claude` restructuring · per-stream session-log restructuring (G-4: candidate mechanism, separate decision) · unrelated developer-workflow improvements · **physical mutation enforcement (locks/leases/gates) — Increment 2+, its own authorization** · any mechanism not required by `KOS-AI-ORCH-001` · moving the accepted rule document (G-5).

## 9 · Design questions delegated to the architecture/design stream *(AUTHORIZED OUTCOME ≠ DESIGN CHOICE ≠ IMPLEMENTATION DETAIL)*

| Authorized outcome (fixed by the rule) | Design choice (delegated — NOT prescribed here) | Implementation detail (implementer's) |
|---|---|---|
| exclusive mutation ownership exists and is consulted | lock vs lease vs registry entry vs transactional state vs filesystem ownership — **evaluated, not assumed** | field names, serialization |
| one queryable authoritative record, two never-merged sub-records | WHERE the record lives (platform_registry entry · dedicated state file · other) and its schema | file format, ids |
| handoff = recorded token-carrying transition | token representation (reference vs embedded) | hashing, timestamps |
| consultation duty at session start / before mutation | HOW a session consults (start-of-session injection via session_manager's existing scripts is a *candidate*, not a decision) | script wiring |
| G-2/G-3/E rules hold | how conjunctions and write-restrictions are checked in a convention-first platform | error wording |

## 10 · Required RED / contract evidence *(before any implementation, from the accepted proposal's §18 acceptance criteria)*

Contract tests, written first and failing against the current (record-less) state: **(R1)** two same-item sessions cannot both hold mutation ownership · **(R2)** session identity is inspectable per §6-B · **(R3)** a handoff without its token, or a start without BOTH G-3 facts, is rejected as a transition · **(R4)** a STOPPED work item's record accepts no mutation-ownership change without an explicit continuation transition · **(R5)** the Authority State rejects writes not made by the Governance role registering a recorded human act · **(R6)** ACTIVE-but-unauthorized is representable and queryable as exactly that · **(R7)** two different work items' records operate with zero coupling.

## 11 · Required GREEN evidence

All §10 contracts pass · **the replay criterion:** the Election 65/69 track's decision points re-expressed as record transitions produce the identical authorization history (grant → boundary gate → RED → GREEN → verification → governance closure) with **no decision point moved and no authority manufactured** · regression: zero behavioural change to any existing platform script/hook surface not named in §7.

## 12 · Independent verification requirement

A verification session that did not implement (Session-1 role) verifies the contracts independently, attempts falsification (can a session acquire authority or ownership outside the protocol?), and **does not self-certify anything it touched**. R-34 applies unchanged.

## 13 · Operational qualification requirement

After verification: the mechanism runs alongside the manual conventions for at least one real governed work item **without replacing them**, and the PO/ARB rules on qualification with that evidence. Only after qualification may the interim conventions (including "same terminal", §9-status below) be retired — **by explicit ruling, not by the mechanism existing**.

**Same-terminal statement (commission §9):** *"Same terminal" is currently an operational convention used while the governed mechanism does not yet exist. The implementation goal is to make the governance invariant enforceable even when the eventual implementation is not dependent on terminal count.* It is not converted back into a rule by this commission.

## 14 · Session roles *(the succeeded 65/69 discipline, mapped)*

| Role | Session | Activity |
|---|---|---|
| Governance / authorization / state reconciliation | **Session 2** | issues nothing itself; registers the PO's authorization; reconciles gates; records closure (G-1) |
| Architecture / implementation design | **Session 4** *(the established architecture stream)* | evaluates §9's design choices; presents the implementation boundary |
| Implementation | **Session 3** | RED → minimal implementation → GREEN, inside the approved boundary only |
| Independent verification | **Session 1** | §12 |

**Gates (uncollapsed):** PO authorization (with the G-6 ruling recorded in it) → Session 4 boundary presentation → **boundary approval (human)** → RED (§10) → minimal implementation → GREEN (§11) → regression → independent verification (§12) → operational qualification (§13) → governance closure (G-1). **The commission existing authorizes none of these to begin.**

---

**Traceability:** accepted rule + amendments (`51ba56fd`) · acceptance verbatim (ARB review §Acceptance) · ARB review (`fb9cee29`) · proposal @ `30e125e5` · Platform Baseline (CMP states; R-34/R-37/R-38; "governance precedes automation") · methodology freeze (`.claude/CLAUDE.md`, 2026-08-01) · F1–F9 evidence set · ADR:OQ-2 (placement, open) · the 65/69 track as the gate-discipline precedent.

---

## 15 · AUTHORIZATION RECORD (PO, 2026-08-14 — performative, verbatim)

> *"G-6 ruling: KOS-AI-ORCH-001 implementation proceeds under reading (ii)+(iii), with the F1–F9 evidence set recorded as the qualifying operational evidence where required.*
>
> *I authorize the proposed Increment-1 Platform Implementation Commission exactly as presented.*
>
> *Do not expand the scope. Do not authorize Increment 2 enforcement. Do not authorize locks, leases, hooks, .claude restructuring, per-stream logging, Election changes, or unrelated platform work.*
>
> *Register the ruling and authorization, then proceed only through the defined gates: PO authorization → Session 4 architecture/design boundary → human boundary approval → RED → implementation → GREEN → regression → Session 1 independent verification → operational qualification → Governance closure.*
>
> *Session 2 remains the governance/authority stream and must not implement the mechanism itself."*

**Authority-State effect (registered by Session 2, the Governance role, per the accepted G-2 rule — registering a recorded PO act):**

```
Platform grants:  ONE — KOS-AI-ORCH-001 Increment 1 (§5–§9 boundary), ☑ AUTHORIZED,
                  gated: nothing begins before the Session-4 boundary and its HUMAN approval
G-6:              CLOSED — reading (ii)+(iii); F1–F9 = the qualifying operational evidence
G-5:              unchanged — placement provisional (ADR:OQ-2 open); the rule is not moved
Increment 2+:     ⛔ NOT AUTHORIZED (enforcement mechanics)
Election grants:  NONE (unchanged) — start()/A-2 pending PO commission · EM-OPEN-021 open · Session 3 stopped
```

---

## 16 · BOUNDARY APPROVAL + ROLE-BOUND RULING (PO/ARB, 2026-08-14 — performative, verbatim in substance)

> **D-1** Amend KOS-AI-ORCH-001; do NOT create KOS-AI-ORCH-002 at this stage.
> **D-2** Approve the Increment-1 implementation boundary **WITH the R8 refinement** *(R8: role is immutable for the lifetime of a SessionAssignment; a role change is not mutation — it creates a new assignment with predecessor linkage via the existing HANDOFF → START machinery)*.
> **D-3** Four canonical roles — ARCHITECTURE · GOVERNANCE · IMPLEMENTATION · VERIFICATION — with the role set remaining workflow-declared; explicitly declared workflow-specific extensions allowed; NOT a closed enum.
> **D-4** The eight-question startup check is adopted **immediately as an operating convention** (work item · role · workflow state · mutation owner · authorization · boundary · predecessor/handoff · prohibitions; missing/contradictory → STOP, read-only, report, escalate, never infer authority). Convention, NOT runtime enforcement.
> **D-5** Sequential role reassignment within one process is allowed; **R-34 remains binding** — a process that implemented may NOT independently verify that same implementation; process/terminal identity must not become the authority mechanism.

**Registered by Governance (Session 2):** the ruling verbatim + Amendment **A-1** on KOS-AI-ORCH-001 (roles · R8 with provenance · startup convention · D-5/R-34 · unchanged-items list). **Conflict check performed: no ruled item conflicts with the accepted rule text** — D-3 matches the accepted per-workflow role declaration; R8 sharpens identity corollary C-2; the startup check is the articulated consultation duty.

**Gate state after this ruling:**

```
PO authorization                        ✅  (§15)
Session 4 architecture/design boundary  ✅  (9b69ab76 + addendum 641d4112)
Human boundary approval                 ✅  D-2 — WITH R8 (R8 joins R1–R7 as a contract)
NEXT →  RED (contracts R1–R8)           ⬜  Session 3 (IMPLEMENTATION role) may begin,
                                            applying the D-4 startup convention first
Minimal implementation                  ⬜
GREEN (+ 65/69 replay criterion)        ⬜
Regression                              ⬜
Session 1 independent verification      ⬜  (R-34: must not have implemented)
Operational qualification               ⬜
Governance closure (G-1)                ⬜
```

**Still excluded (unchanged):** Increment-2 enforcement of any kind (incl. role gating and startup-check hooks) · locks/leases/hooks · `.claude` restructuring · per-stream logging · Election work.

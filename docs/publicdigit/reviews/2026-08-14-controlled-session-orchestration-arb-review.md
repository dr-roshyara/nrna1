# Controlled Session Orchestration — ARB Review

**Type:** Governance review (Session 2) · **Date:** 2026-08-14 · **Subject:** `docs/architecture/governance/KnowledgeOS_Controlled_Session_Orchestration_Proposal.md` @ `30e125e5` (with clarifications A+B applied; reviewed as the reconstructed final text, deleted lines discarded)
**Reviewer note:** Session 2 authored the proposal; this review was therefore conducted **adversarially against its own wording** — the gaps in §7 are the product of that pass, not a rubber stamp.
**⛔ Governance review only. Nothing implemented · no platform/Election/test/`.claude` change · no authorization created · no decision made on behalf of the PO/ARB.**

---

## 1 · Executive verdict

> **MATURE ENOUGH FOR ARB DECISION.** The proposal solves the right problem (shared mutable governance state, not terminal count), states one clean invariant, separates every concept the failure modes conflated, preserves the authority chain without exception, and defers mechanisms exactly where the platform's own R-37 doctrine requires. **Four wording-precision gaps and two external rulings remain — none blocks acceptance; each is a one-line amendment or a separate ruling** (§7). Recommended path: **ACCEPT WITH THE FOUR AMENDMENTS** (§8, option 2).

**§2-A — the problem actually solved:** not "too many terminals" but **ungoverned concurrent mutation of one execution context plus divergent authoritative state** — nine documented failure modes (F1–F9), all of which are context/state failures; zero are terminal failures. The proposal identifies shared mutable governance state as the real hazard explicitly (§7 analysis; F5 as the archetype). ✅ Correct.

## 2 · What the proposal establishes

- **§2-B — the architectural invariant (INV-ORCH-1):** one workflow owns a governed work item's mutable execution context; same-item sessions never mutate it independently; ownership explicit, identity-carrying, handoff-transferred. Plus the state invariant added by Clarification A: **one authoritative workflow state per work item, under every context arrangement.**
- **§2-C — implementation policy (correctly labeled as such):** "same terminal / one shared controlled context" — an interim realization, never the rule.
- **§2-D — concept separation audit:** all nine concepts checked in the reconstructed text — *workflow* ≠ *work item* (§12 matrix) · *execution context* ≠ *terminal* (§4, §7) · *session* = role with identity (§4, C-2) · *Session Registry* ≠ *Authority State* (§10a, both directions closed) · *mutation ownership* ≠ *implementation authorization* (§10a ladder) · *handoff* = token-carrying state transition, not a message (§9). ✅ **All correctly separated; no conflation found.**
- **§2-E — authority chain:** preserved verbatim and structurally (§10a, §15): Human/PO/ARB → governance decision → explicit bounded authorization → implementation → independent verification. The workflow engine "automates the choreography; it does not become the source of business or architecture authority." ✅
- **§2-G — concurrency coherence:** same-item+shared → §8-C (reads free, one mutator, handoffs) · same-item+isolated → §12 exceptional case (explicit KnowledgeOS coordination, one authoritative state, no independent governance decisions) · different-items → isolated/concurrent. After the §7-table consistency fix, **internally coherent** — one residual tension recorded as Gap G-4.

**§2-F — wording-hazard sweep (could any phrasing accidentally allow…):**

| Hazard | Verdict |
|---|---|
| active session → authorized by existence | ❌ blocked twice (§10a ladder; §15 never-inferred list) |
| mutation ownership → implementation authority | ❌ blocked, both directions (§10a closing paragraph) |
| stopped session → successor may continue | ❌ blocked (§11 load-bearing sentence; §9 "no successor inherits authority from the stop") |
| divergent authoritative state | ❌ blocked (§12 invariant; the exceptional case explicitly preserves ONE state) |
| implementation self-authorization | ❌ blocked (§9 handoff prerequisites; §15) |
| workflow engine as decision-maker | ❌ blocked (§15; §10a) |
| **residual:** who may WRITE the Authority State | ⚠️ **unspecified — Gap G-2** |
| **residual:** does HANDED_OFF auto-start the successor | ⚠️ **ambiguous — Gap G-3** |

## 3 · What it deliberately does NOT establish *(and whether deferral is legitimate)*

Lock vs lease vs registry mechanism · `workflow_engine`/`session_manager` changes · terminal/process model · registry schema · state-file restructuring · the freeze-exception reading · placement ruling. **Deferral verdict: LEGITIMATE, and required** — the platform's own standing doctrine ("governance precedes automation", R-37 burden of proof) mandates rule-before-mechanism, and the proposal's §2 supplies precisely the operational evidence that doctrine demands. Deciding mechanisms here would violate the platform's constitution, not serve it.

## 4 · Architectural invariants *(as proposed — ARB Decisions 1, 3, 5, 6 below)*

INV-ORCH-1 · one authoritative workflow state per work item · Session Registry ≠ Authority State · exclusive mutation ownership per execution context · sticky STOPPED · workflow engine coordinates, never decides.

## 5 · Governance rules *(as proposed — ARB Decision 2)*

Sequential-by-default lifecycle with token-carrying handoffs (§9's per-transition prerequisites, codified from the 65/69 track's working practice) · Human-started sessions · read-freedom within a work item · isolation between work items · the §12 exceptional-case conditions · authorization as queryable state, never document interpretation.

## 6 · Implementation policies *(explicitly subordinate — ARB Decision 4)*

"Same terminal / one shared controlled context" as the **interim realization** · candidate mechanisms as classified in §10 (possible mechanism / optional hardening / unnecessary) · build direction: extend CMP-004/CMP-002, no new subsystem.

**§3 of the commission — the original operational requirement classified:** *"Governance, implementation and verification should run in the same terminal"* is, on the evidence, **a temporary operational convention, correctly promoted by the proposal to (at most) an interim implementation policy under INV-ORCH-1 — and correctly denied the status of architectural invariant or governance rule.** Verification that this conclusion is supported: every one of F1–F9 can occur in ONE terminal (two sequential sessions sharing a dirty tree reproduce F2/F8; a single session with `--amend` reproduced F1) and every one can be PREVENTED across many terminals given ownership + one authoritative state — therefore terminal count is neither necessary nor sufficient, which is the definition of "not the invariant." ✅ Classification supported.

## 7 · Remaining gaps *(genuine issues only; each: issue · why · evidence · blocks? · disposition)*

| # | Issue | Why it matters | Evidence | Blocks ARB? | Recommended disposition |
|---|---|---|---|---|---|
| **G-1** | **Workflow closure is not an explicit governance act.** The lifecycle ends `Verification → COMPLETED`; nothing says Governance records completion | risk: "verification success → business acceptance" — the very inference §15 forbids — could creep back in at the last step | proposal §6 diagram (line: `Verification … ──▶ COMPLETED`) vs the Election practice, where Session 2 registered every closure | **NO** | one-line amendment at acceptance: *"Completion is recorded by the Governance role (or Human); Verification reports, it does not close"* |
| **G-2** | **Authority State write-authority unspecified** — who may create/update the authorization record? | the record cures F5 only if its writers are constrained; an unconstrained record just relocates the divergence | §10 authorization-object row defines the *reader* protocol ("a role asks…") but never the writer | **NO** | one-line amendment: *"Only the Governance role writes the Authority State, and only to register a recorded Human/PO/ARB act"* |
| **G-3** | **Start semantics ambiguous:** `HANDED_OFF` + "Human starts each session" — conjunction or alternative? | if handoff alone unblocks, the human-start authority-adjacent act is bypassable | §6 bullets ("later role starts only on the recorded handoff" / "the Human/PO starts each session") | **NO** | one-line amendment: *"start requires BOTH the predecessor's recorded handoff AND the human start act"* |
| **G-4** | **§8 embeds a mechanism in the rule block:** "session logs get per-stream sections or per-stream files" is design intent, while §16/§17-Q2 defer state-file restructuring as undecided — an internal tension | the proposal's own discipline (rule vs mechanism) is violated by one sentence | §8 final sentence vs §16 + open question 2 | **NO** | wording correction (proposed, NOT applied by this review): reclassify the sentence as *candidate mechanism* — keep only "the hunk-splitting workaround must not become the permanent design" as the rule-level statement |
| **G-5** | **Placement is PENDING (ADR:OQ-2)** — the artifact sits at a PO-directed location over an unruled derivation | an accepted rule living at an unruled location repeats the AMB-1 discoverability failure shape | proposal placement note; `doc-placement.php` output | **NO** (separate ruling) | rule the cross-product-research placement (or ratify the directed location) in the acceptance act |
| **G-6** | **Freeze-exception reading undecided** (execution policy vs methodology evolution) | determines which authority accepts and under which exception | proposal §13/§17-Q1 | **NO** (it *shapes* the acceptance, not the content) | the ARB states its reading in the acceptance record |

**"Requires" vs "needs a mechanism", audited:** everything the architecture *requires* is stated as a rule (ownership, one authoritative state, registry/authority separation, sticky stops, handoff tokens); everything still *needing a mechanism* is listed in §16 and classified in §10. Apart from G-4's single sentence, the line is held throughout.

## 8 · ARB decision package *(PROPOSED decisions — nothing here is accepted until the Human/ARB accepts it)*

> **Decision 1 — Architectural invariant.** Adopt **INV-ORCH-1**: *"For every governed work item, KnowledgeOS MUST create and manage its AI sessions as members of one workflow execution context with explicit ownership. Sessions belonging to the same governed work item MUST NOT independently mutate the same execution context outside the workflow's coordination protocol"* — together with: *a governed work item has ONE authoritative workflow state.* (Candidate rule identity: **KOS-AI-ORCH-001**, proposal §13.)
>
> **Decision 2 — Session coordination.** Same-work-item sessions operate sequential-by-default in one coordinated execution context; reads are free; **exactly one session at a time holds mutation ownership**, acquired and released only through token-carrying handoffs whose prerequisites are §9's per-transition lists; STOPPED is sticky and continuation is an explicit transition.
>
> **Decision 3 — Authority separation.** **ACTIVE SESSION ≠ AUTHORIZED SESSION · MUTATION OWNERSHIP ≠ IMPLEMENTATION AUTHORITY · SESSION EXISTENCE ≠ IMPLEMENTATION PERMISSION.** The Session Registry and the Authority State are distinct records that never merge; authorization is queryable workflow state, never document interpretation; *(with G-2 amendment)* only Governance writes the Authority State, and only to register a recorded Human/PO/ARB act.
>
> **Decision 4 — Terminal policy.** "Same terminal" is **NOT architectural and NOT a governance rule**: it is the **interim implementation policy** (and today's temporary operational convention) realizing INV-ORCH-1 until a mechanism exists.
>
> **Decision 5 — Isolation.** Isolated execution contexts for the **same** work item exist only when KnowledgeOS explicitly coordinates them AND one authoritative workflow state is preserved AND ownership per context is explicit AND re-entry is a recorded handoff AND no governance decision occurs inside an isolated context. Different work items with isolated contexts run concurrently without restriction.
>
> **Decision 6 — Workflow-engine authority.** The workflow engine MAY coordinate: session identity, lifecycle states, mutation ownership, handoff transitions, the two records of Decision 3. It MUST NEVER: decide business or architecture questions, manufacture authorization, promote evidence to acceptance, or close a work item on verification success alone *(G-1 amendment)*.

**Acceptance options before the ARB:** **(1) ACCEPT as-is** — workable; G-1…G-4 become day-one erratum · **(2) ACCEPT WITH THE FOUR ONE-LINE AMENDMENTS (G-1…G-4) — RECOMMENDED** · **(3) MODIFY** — return with directed changes · **(4) REJECT** — the nine failure modes then remain governed by per-session convention alone, which §2 of the proposal documents as the failure surface.

## 9 · NO IMPLEMENTATION AUTHORIZED

> **This review authorizes nothing.** No lock, lease, registry, hook, automation, `workflow_engine`/`session_manager`/`platform_registry` change, `.claude` orchestration configuration, or Election change is created or permitted by this document. Acceptance of the proposal itself authorizes only the RULE — a **separate Platform Implementation commission**, humanly issued, is required before any mechanism exists.

## 10 · Recommended next step

**Human/ARB disposition of the proposal — option (2) recommended** (accept with the four one-line amendments; rule G-5's placement and state G-6's freeze reading in the same acceptance act). After acceptance: a separate Platform Implementation commission → its own verification → operational qualification. **Until that capability is implemented and verified, the current Election sessions remain governed manually under the existing conventions.**

---

**Traceability:** proposal @ `30e125e5` (`e689e597` + clarifications) · Platform Baseline (R-34/R-37/R-38, component states) · F1–F9 evidence set (proposal §2) · `.claude/CLAUDE.md` session/governance conventions · ADR:OQ-2 (placement PENDING) · KOS-AI-ORCH-001 candidate text (proposal §13).

---

## Acceptance record (PO/ARB, 2026-08-14 — performative, verbatim)

> *"I accept the KnowledgeOS Controlled Session Orchestration Proposal with amendments G-1, G-2, G-3 and G-4 as proposed.*
>
> *I also accept the following principles:*
> *1. One governed work item has one authoritative workflow state.*
> *2. Same-work-item sessions normally operate in one coordinated execution context with explicit mutation ownership and token-based handoff.*
> *3. Session Registry and Authority State remain separate.*
> *4. ACTIVE SESSION ≠ AUTHORIZED SESSION. MUTATION OWNERSHIP ≠ IMPLEMENTATION AUTHORITY. SESSION EXISTENCE ≠ IMPLEMENTATION PERMISSION.*
> *5. "Same terminal" is not an architectural or governance rule. It remains an interim operational convention until KnowledgeOS provides the governed execution mechanism.*
> *6. Isolated execution contexts for the same work item are exceptional and require explicit KnowledgeOS coordination and preservation of one authoritative workflow state.*
> *7. The workflow engine coordinates execution but never creates, interprets, or promotes business/architecture authority.*
>
> *G-5: I accept the directed placement provisionally pending the ADR:OQ-2 placement ruling.*
> *G-6: I accept that the freeze-exception interpretation must be explicitly recorded as part of the acceptance/implementation authority decision.*
>
> *This acceptance authorizes the governance RULE only. It does NOT authorize implementation of any orchestration mechanism. A separate bounded Platform Implementation commission is required before implementation begins."*

**Registration acts performed by Session 2 (same day):** amendments G-1–G-4 applied inline to the proposal, each marked with its acceptance date · proposal status → **ACCEPTED WITH AMENDMENTS (RULE ONLY)**, original status preserved struck · provisos G-5/G-6 carried in the status header — **the G-6 freeze-exception reading itself remains unstated and is deliberately not invented; it must appear in the implementation-authority decision** · `KOS-AI-ORCH-001` is now the accepted rule identity (proposal §13). **Open follow-ups for the decision authority:** the ADR:OQ-2 placement ruling (G-5) · whether `.claude/CLAUDE.md` should carry a POINTER to the accepted rule (proposal §13 contemplated it; Session 2 does not edit the project instruction file unilaterally) · the separate Platform Implementation commission, when desired.

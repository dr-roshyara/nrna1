# Approval Request — Session Assignment Discovery (architecture design)

**Prepared by Governance (Session 2) · 2026-08-14 · Format: the adopted human-approval-language convention (`5023f9c8`) · One decision, plus four boundary choices that change what gets built.**
**Governance conformance check performed:** the proposal (`696a4316`) conforms to the registered intake boundary on every constraint — read-side only · no mechanism change · UNKNOWN as a first-class answer · four canonical roles · no Session 5 · STOP-safe semantics · design-only deliverable. **Nothing in it requires amendment before your decision.**

---

## Purpose

Let a terminal (or working copy) find out **which piece of governed work it is currently assigned to** — by reading the authoritative record, and nothing else.

## Why it matters

Several teams share one working copy. Today each one has to work that out by hand, and we have already seen what goes wrong when the hand-work slips: work performed under an assignment that had never properly begun, a start recorded against the wrong piece of text, and one team reading a state that had already moved on. Each was caught, but only because a person or a session went looking. This capability answers the question mechanically, and — importantly — **answers "you may not proceed" just as clearly as "you may."**

## What has been produced

A design (no code) for a **read-only lookup**. It reads the existing records, works out which assignment could apply, and reports one of four answers: **found · none · more than one · unreadable.** It says whether the assignment is in a state where work may happen, and if not, what is missing. Where the records genuinely cannot answer something, it says **"unknown"** rather than guessing.

## What I am being asked to approve

**The architecture design as the basis for implementation** — the direction and its limits, not the code, and not permission to build it yet.

## What this approval does NOT authorize

Building it (that is the next, separate decision) · giving anyone permission to do work · starting or activating any session · changing the existing workflow mechanism, which was just qualified · deciding whether someone is *allowed* to perform an act — the design deliberately refuses to answer that · anything on the Election side.

## Four boundary choices — each changes what gets built

| | Choice | Governance recommendation |
|---|---|---|
| **1** | Build it as a **separate read-only tool**, or add the feature **inside the existing workflow mechanism**? | **Separate tool.** The existing mechanism was qualified two commits ago; changing it now would require its own approval and put a qualified component back in play. |
| **2** | Run it **when a person or session asks**, or **automatically at every session start**? | **On request, for now.** Automatic startup wiring is a bigger change; decide it later on evidence. |
| **3** | A known gap: the records cannot say **which team a permission belongs to**. Accept as a known limitation here, or open separate work to fix it? | **Accept here, note it separately.** The design reports "unknown" instead of guessing — which is the honest behaviour either way. |
| **4** | Six related record improvements are possible (timestamps, permission ownership, and similar). Do those **first**, or build the lookup now and improve later? | **Build now.** The lookup works without them; each improvement is its own decision. |

## One thing the design does not ask you, and should

**The design assumes it will be built by the implementation team as a new tool alongside the existing mechanism.** That is sound — but it means **a second tool now reads the same records as the qualified mechanism.** If the two ever disagree about what a record means, the qualified mechanism wins, and the new tool is wrong by definition. The design guards this by reusing the mechanism's own reading logic rather than writing its own. **You may want to state that precedence explicitly in your approval** — Governance recommends it, and will register it as a binding constraint if you say so.

## Technical reference

`docs/publicdigit/reviews/2026-08-14-KOS-SESSION-DISCOVERY-001-fresh-architecture-proposal.md` (`696a4316`) — the full design, including the state-by-state behaviour table, the six permission facts and which two cannot be answered, the risk list, and the six deferred record improvements. Governance boundary: the intake (`15f4f484`).

## Programme sequencing (PO intent, registered 2026-08-14)

**Deliberate: finish the KnowledgeOS session/orchestration capability to end-to-end completion — architecture approval → implementation → verification → qualification → closure — BEFORE returning to Election-only work.** Rationale, in the PO's own framing: *finish the mechanism that makes the four-terminal workflow reliable, prove it end-to-end, then use that mechanism to govern the Election implementation.* Constraint carried: finish the **minimum viable** governed session architecture — not an endlessly expanding one; the §O non-goals and the six deferred dependencies (D-1…D-6) are what keeps it minimal. **The Election items (voting-page fix · elections with no approved candidate) stay registered and untouched at the front of the queue that follows.**

## Decision

**APPROVE / AMEND / DECLINE** — and, if approving, your answers to boundary choices 1–4 and whether the precedence rule above is binding.

*(Implementation is a separate decision that follows this one. Nothing is built on this approval alone.)*

---

## DECISION RECORDED — ARCHITECTURE APPROVED (PO/ARB, 2026-08-14, verbatim)

> *"I approve the Session Assignment Discovery architecture as the basis for implementation.*
>
> *I approve the following architectural choices:*
>
> *Session Assignment Discovery will be implemented as a separate read-only capability and will not modify the existing workflow mechanism.*
> *It will initially be invoked on request, not automatically at every session start.*
> *Where the existing records cannot establish a fact reliably, the capability will report unknown rather than infer or invent an answer. Those underlying record limitations remain separate future work.*
> *The related record improvements are not prerequisites for this implementation and must remain separately governed.*
>
> *Binding precedence rule: the already-qualified workflow mechanism is the authoritative interpretation of workflow records. Session Assignment Discovery must reuse or conform to that interpretation and must not create a competing interpretation of workflow state.*
>
> *This approval is for the architecture only. It does not authorize implementation, session activation, authorization changes, workflow changes, or any Increment-2 work.*
>
> *— PO/ARB"*

### The four boundary choices — DECIDED as recommended

**1** separate read-only capability; the qualified mechanism untouched (Q-B) · **2** on-request invocation, not startup-wired (Q-C; D-5 stays deferred) · **3** report **unknown** rather than infer; the underlying record limitations (incl. the grant↔session linkage gap / L-2) remain separate future work (Q-D) · **4** the six record improvements (D-1…D-6) are **not prerequisites** and remain separately governed (Q-E).

### BINDING PRECEDENCE RULE — registered as a constraint on implementation

> **The already-qualified workflow mechanism (`workflow-state.php`, AST-015) is the authoritative interpretation of workflow records. Session Assignment Discovery must reuse or conform to that interpretation and must not create a competing interpretation of workflow state.**

**Consequence for implementation, binding:** the resolver **consumes** the mechanism's own reading surface (`fold`/`identity`) and must contain **no second folding/validation implementation**. Where the resolver's output could diverge from the mechanism's reading, the mechanism is correct by definition and the resolver is defective. *(This is the item Governance surfaced as absent from the proposal's own question list; the PO has now made it binding.)*

### What is explicitly NOT authorized by this approval

**Implementation** (its own separate authorization is required) · session activation · authorization changes · workflow changes · any Increment-2 work.

### Machine-record effect

The architecture assignment's bounded work is delivered and human-approved; the assignment is recorded **COMPLETED** (governance act) and mutation ownership released. **No implementation grant is issued. No implementation assignment is registered.** The next gate is a separate PO implementation authorization.

---

## BOUNDARY-PRESENTATION STAGE AUTHORIZED (PO/ARB, 2026-08-14, verbatim)

> *"I authorize the implementation boundary presentation stage."*

**A-3 verification:** performative, in the PO's own words, in the Governance record — ✅ registrable. **Read exactly as written: it authorizes the STAGE (determine + present the implementation boundary). It is not a session START act** — under G-3, activation additionally requires a recorded human start; Governance does not manufacture one (the precedent set by today's audited registration error).

**Registered effects:** implementation assignment `S3-implementation-discovery` REGISTERED · stage-scoped grant **`G-KOS-DISC-IMPL-BOUNDARY`** issued (`humanActRef` = this section + its commit) · HANDOFF recorded (bootstrap form, valid while ownerless after the architecture assignment's completion; token = this authorization + the approved architecture).

**Grant scope, exactly:** determine and present the implementation boundary for the approved architecture — which file, which tests, which registry entry, and the verification surface — **for human approval before any code exists.** **NOT authorized by this grant:** writing production code or tests · any `workflow-state.php` change · a second interpretation of workflow records (the **binding precedence rule** applies to whatever is eventually built) · startup wiring (D-5) · authorization evaluation · Increment-2 · Election work.

**Remaining to activate: one recorded human start.** State until then: assignment CREATED · grant AUTHORIZED · owner null · nothing begun.

### Fuller authorization act, same day — REGISTERED VERBATIM and read as the recorded human START

> *"I authorize the implementation-boundary preparation for KOS-SESSION-DISCOVERY-001.*
> *Session 3 may prepare and present the exact implementation boundary derived from the approved architecture.*
> *This authorization does not authorize production or test code changes.*
> *Session 3 must present the exact files, components, tests, documentation, and exclusions before implementation begins.*
> *No implementation code may be changed until that boundary has been presented and separately approved.*
> *— PO/ARB"*

**Reading, stated openly for correction:** *"Session 3 may prepare and present…"* is a permission **addressed to the session**, authorizing it to act now — operationally an activation permission. Governance therefore records it as the **human START act** for `S3-implementation-discovery`, completing the G-3 conjunction with the seq-6 handoff. *(This differs from the earlier audited error, where the text was an instruction addressed to the registrar, not a permission addressed to a session. If the PO intended otherwise, one line reverses it and the assignment returns to CREATED.)*

**Binding presentation content (from the act itself):** the boundary must enumerate **exact files · components · tests · documentation · exclusions** — presented **before** implementation begins. **No implementation code may change until that boundary is separately approved.** Reaffirmed: no production or test code under this authorization; the **binding precedence rule** governs whatever is eventually built.

# ES-001 — Engineering Constitution

**Status:** PROPOSED · part of the [Standards Index](STANDARDS_INDEX.md)
**Purpose:** the foundational principles of the Engineering Platform and the rules by which governance itself is created.
**Scope:** all engineering work and all platform governance acts.
**Authority:** Decision Authority (ARB).
**Qualification Method:** constitution audits (OQ-ENG-002-class: ownership, discoverability, single-home, no-contradiction checks).
**Supersedes:** the MEMORY-resident texts of rule parsimony and governance-creation (now hosted here; MEMORY holds hints).
**Decision authority (compliance):** Human + AI — the AI evaluates, governance decides (see the Decision Authority & Verification Matrix in the Standards Index).
**Related Standards:** all (ES-002..ES-006 derive their authority from this document's registered sources).

## Registered constitutional sources (governed homes — pointers, never copies)

| Rule family | One-line statement | Canonical home |
|---|---|---|
| Principles AIP-01..14 | incl. AIP-10 Assertion Integrity · AIP-11 Append-Only History · AIP-13 Implementation-Driven Evolution · AIP-14 Product Primacy | sealed Baseline corpus, `../architecture/baseline/` (+ ADR-AIP-01/02) |
| Platform Decisions PD-01..20 · Fitness Functions FF-01..17 | defined; FF implementation deferred per AIP-14 | sealed Baseline corpus |
| Rulings R-30..R-37 | living governance decisions (R-27 governance freeze · R-37 structural freeze + burden of proof) | `../architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` (append-only; R-1..29 sealed in Phase-02.5 §6) |
| Reference Architecture | what the platform IS (DRAFT→ADOPTED→STABLE lifecycle) | `../architecture/reference/` |
| Governing insight | *Governance precedes automation. Automation may implement governance. Automation never defines governance.* | stated in the Reference Architecture; numbering deferred to ratification |

## Hosted rules (this document is their canonical home; previously MEMORY-only)

**ES-001.1 — Rule Parsimony** *(ARB 2026-07-10)*. The constraint set is complete (R-27 claims · R-37 structure · EEP execution · ES-003.2 recording · inbox freeze). On any recurring problem, FIRST ask: *does an existing rule already cover this?* Prefer interpreting existing rules over creating new ones; a new rule requires a genuine "no." Corollary: the rulings register must not grow faster than the software.

**ES-001.2 — Documents Record Governance; They Do Not Create It** *(ARB 2026-07-11)*. Only explicit ARB decisions create governance. Workflow words (*continue, looks good, go ahead*) are permission to proceed — never Approved/Promoted/Retired/Closed. When a governance decision is needed: STOP and ask per item (Approve / Reject / Defer). Authors propose; the authority adopts (AIP-10 corollary: no artifact may assert an unoccurred adoption).

**ES-001.3 — Governance Orientation Duty** *(ARB 2026-08-16)*. **Governance is the system of record *and* the orientation layer: it must actively maintain the programme's execution state and tell the Human/PO/ARB what the next governed action is.** Recording what happened is necessary and not sufficient.

Whenever a task, decision, finding, verification result or dependency is recorded, Governance evaluates the state **of the records** and returns a concise *"what happens next"*: **current state** (what is actually recorded) · **what is waiting** (human decision, START, verification, architecture, implementation, or another dependency) · **recommended next action**, in business language · **responsible role** · **whether human authority is required** · **why**, derived from the recorded state and its dependencies, never from assumption. Where several actions are possible, it presents the recommendation, the reason, the other waiting items, and the human decision required — rather than a menu with no recommendation.

Governance must actively detect and surface: stale or blocked assignments · duplicate or conflicting records · missing STARTs · missing handoffs · **work completed in prose but not closed in the record** · verification not yet performed · findings needing a separate governed act · **scope that does not match the recorded grant** · dependencies between work items.

**Communication — business language first** *(ARB 2026-08-16, same-day refinement; this is a requirement, not a style preference)*. **Governance addresses the Human/PO/ARB in business language, and explains what the situation means for the programme before presenting any technical detail.** Technical identifiers — work-item and session IDs, grant IDs, transition types, sequence numbers, file names, scripts, exit codes — are **supporting evidence, never the primary language of the conversation.**

For every issue of consequence, Governance presents, in this order: **business situation** (what is happening) · **business consequence** (why it matters) · **recommendation** (what Governance advises doing next) · **human decision required** (what exactly must be decided) · **technical evidence** (the identifiers and records supporting the conclusion) — **last, and only then.**

> *Not:* "`S1-…-g2g4` is `CREATED`, `G-…-G2G4` is `AUTHORIZED`, seq 6 is `HANDOFF`, and G-3 requires START."
> *But:* "The independent review of the remaining governance weaknesses is authorized and assigned but has not started. We cannot complete the governance review until it runs. I recommend starting it now. Please authorize the START. *Evidence:* the assignment is registered, the authority exists, the handoff is recorded, and the lane is still `CREATED`."

**The separation is the rule, and it is absolute:**

```
Observed fact → Business interpretation → Governance recommendation
             → Human decision → Recorded authorization → Execution
```

**Each arrow is a boundary, and Governance owns only the first three.** It must never present an interpretation as an observed fact, nor a recommendation as a decision. **Governance recommends; it never decides on the Human/PO/ARB's behalf, and never withholds a recommendation to avoid the responsibility of making one.**

**Governance must never silently perform the action it recommends, and must never turn its own recommendation into an authorization.** This extends ES-001.2 rather than qualifying it: ES-001.2 forbids Governance from *creating* authority; ES-001.3 obliges it to *seek* authority proactively instead of waiting to be asked. A recommendation stated as a finding, a next action taken because it was obvious, or a state reported from prose rather than from the record, each breach this rule.

**Command protocol — Governance translates; it does not execute** *(ARB 2026-08-16, same-day refinement)*. **Human commands are expressed in business language. Governance translates a Human authorization into the required governed workflow actions and routes the authorized work to the responsible role.** The Human makes a business authorization; the Human does not operate the workflow engine — Governance operates it on the Human's act, recording the act verbatim.

```
Human / PO / ARB      — business authorization
        ↓
Governance            — records the act · translates it into the governed
        ↓               transitions · routes to the responsible role
Responsible role      — executes the authorized work
        ↓
Result → Human        — decides what the result means
        ↓
Governance            — records the outcome
```

**The sharp boundary: when the Human/PO/ARB authorizes an action for another role, Governance must record and route that authorization; it must not perform the authorized action itself.** Recording a START is a Governance act; performing the started work is the role's act — only the second carries the role's independence obligations, and Governance never crosses into it. Canonical pattern: the Human says *"Authorize start of the independent verification of the remaining governance weaknesses"* — Governance identifies the governed assignment, records the transitions, and instructs the responsible role *"Start the independent verification of G-2 and G-4"*. **Technical identifiers live in the workflow record; the conversation stays business-first in both directions.**

**The Human command vocabulary — RECORD · START · COMPLETE** *(ARB 2026-08-16, second same-day refinement; corrected the same day by ARB act: COMPLETE is a Human verb for session closure, not internal-only)*. **The Human should never have to learn the workflow engine to operate Governance.** The Human speaks three commands; Governance translates:

| Human says | Meaning | Governance translates to |
|---|---|---|
| **RECORD: …** | *"Put my business decision, fact, instruction, or completed-work statement into the official record."* | whatever internal transitions the act requires (`REGISTER` · `HANDOFF` · grants · registrations) |
| **START: …** | *"Begin the already-authorized session/assignment."* | the recorded human START act + activation per the workflow rules |
| **COMPLETE: …** | *"Formally close this session/assignment."* | the `COMPLETE` transition, recorded by Governance on the Human's act |

The Human normally never supplies session IDs, grant IDs, sequence numbers, transition names, or JSON — Governance resolves the referent from the authoritative records, and asks only where genuine ambiguity cannot be resolved from them. `REGISTER` and `HANDOFF` remain internal workflow vocabulary, never forced on the Human: *"finished my part, hand it to the next role"* is spoken as a RECORD act, and Governance translates it to `HANDOFF`. **`START` and `COMPLETE` are also workflow transitions internally; the Human uses them as business commands with the meanings above, and Governance performs the corresponding internal transition** — the shared name is deliberate, not a conflation.

**Lifecycle translation duty.** When a role reports that its work is finished, Governance does not assume closure and **does not invent the role's intent** — it determines the applicable lifecycle transition **from the recorded business intent and context**: work handed to another role → **HANDOFF** (*"finished my part; the next role takes over"*); genuine formal closure with no successor → **COMPLETE**. **Where the intent is ambiguous, Governance asks the Human rather than guessing.** **HANDOFF does not require COMPLETE first** — a producing role may hand finished work to a successor while remaining open for future authorized work under the same assignment.

**Function determines role.** A review of Architecture work is a Verification function if its purpose is independent checking — routing follows the function, not the producer's role name. Where reviewer independence is discretionary, Governance surfaces the decision to the Human/PO/ARB; the producer never verifies itself.

*Burden of proof (R-37) — this rule was adopted on implementation evidence, not on principle: two records created for one commission with one grant ID issued twice · one human START act recorded in two records · a human act recorded against an assignment the human did not name · a lane left ACTIVE while its verdict existed only in prose (the recurring `E-2` pattern) · a verification lane handed off and left unstarted for two days · an authorized grant with no assignment · an initialized record with no content · and three separate state claims that the records falsified. Companion: ES-006.1 — this is a repeated pattern, not a single occurrence.*

# FINAL GOVERNANCE + COMMUNICATION OPERATING MODEL — `KOS-OPERATING-MODEL-001`

**Status:** **IMPLEMENTED** (this document + minimal capability + contract test) · **VERIFIED** (independent verification `fc59bb0a`, 2026-08-22) · **REVIEWED** (Governance adoption review `cf621832`, 2026-08-23, result PASS) · ✅ **ADOPTED** (PO/ARB decision 2026-08-23, verbatim *"Accept and adopt the three verified layers"* — bounded to L1 + L2 + L3; `AST-019` explicitly NOT adopted) · **`AUTHORIZED for future use`: NOT DECIDED** — the human said *adopt* and said nothing about authorization; §38 forbids collapsing the two. §38 — the four states are never collapsed. *(Status annotation synchronized per `ES-004.3` by the adoption-recording act; the 40 sections below are unchanged. Decision record: `docs/knowledgeos/governance/2026-08-23-KOS-OPERATING-MODEL-001-ADOPTION-DECISION.md`.)*
**Work item:** `KOS-OPERATING-MODEL-001` · **Lane:** `claude-code-session:259c1966-b18e-4759-8afe-b46627dd5a2f` (role `implementation`) · **Date:** 2026-08-22
**Governing contract (verbatim):** `docs/knowledgeos/reviews/2026-08-22-KOS-OPERATING-MODEL-001-implementation-prompt.md` (40 sections) · **Operational breakdown:** `docs/plans/20260822-2126-kos-operating-model-001-implementation-plan.md`
**Placement derived:** `scripts/doc-placement.php` (product-specific · knowledgeos → `docs/knowledgeos`, exit 0)

> This document is the **operating model** — the human-facing operating layer of governed AI-engineering work. It is implemented **over** the existing workflow estate (AST-015 · AST-017 · AST-018), which it **consumes and never replaces**. The one-line core principle, operationalized across all 40 sections:

> **The human speaks BUSINESS LANGUAGE. The system handles TECHNICAL WORKFLOW MECHANICS. The human never operates the workflow engine.**

---

## 1 · Core business principle

The human makes **business and authority decisions**. Governance and Communication capabilities perform the **mechanical consequences** of those decisions through the canonical workflow mechanism.

The human is **never required** to understand or manually operate: workflow UUIDs · process UUIDs · `REGISTER` · `HANDOFF` · `START` · `mutationOwner` · `predecessor` · transition JSON · `workflow-state.php` · workflow record structure · governance tokens · `tokenRef` · internal resolver codes.

## 2 · The human always starts with dedicated Governance

Every new business work item starts with the dedicated **Governance Engineer**. The human says — in business language, nothing more:

> "Hello Governance, listen to me. I want …"

The human describes the desired outcome. The human does **not** need to know a work-item ID, role, actor, architecture, implementation, reviewer, or workflow state. Governance performs the initial intake and classification.

## 3 · Governance Engineer

The Governance Engineer is the **dedicated human-facing governance responsibility** (not a new workflow role, not a service, not a position — adopted six-role operating model, 2026-08-19).

Its responsibility:

```
HUMAN REQUEST
    → UNDERSTAND / CLASSIFY
    → DETERMINE GOVERNED PATH
    → REQUEST HUMAN DECISION WHEN REQUIRED
    → COMMISSION / ESCALATE
    → HANDLE SUBSTANTIVE GOVERNANCE QUESTIONS
    → PRESENT ADOPTION / ACCEPTANCE DECISIONS
```

It determines: whether Architecture is required · whether work can proceed directly to Implementation · whether an existing work item/process already covers the request · whether a governance decision is required · whether a scope change needs explicit approval · whether an exception or conflict must return to the human. It speaks business language.

## 4 · Communication Engineer

The Communication Engineer is **NOT a permanent person** — it is a **transferable operational responsibility**. Any currently authorized actor/session may perform it for **routine coordination**:

- session completion
- next-actor detection
- routine next-step communication
- continuation decisions
- fresh-session prompt preparation
- session identity continuity
- routine actor handoff
- business-language recovery from routine mismatches
- routine execution of **already-authorized** workflow mechanics

It does **not** make substantive governance decisions — not Architecture vs Implementation, not adoption, not policy, not scope expansion, not substantive governance exceptions, not unresolved governance conflicts. Those belong to the Governance Engineer / Human.

## 5 · Governance vs Communication

| Signal | Owner |
|---|---|
| BUSINESS / AUTHORITY / EXCEPTION | **Governance Engineer** |
| ROUTINE / OPERATIONAL / SESSION COORDINATION | **Communication Engineer** |
| HUMAN AUTHORITY | **Human / PO-ARB** |

Routine examples (Communication): "Architecture completed. Review is required." · "The current session can continue." · "A fresh session is required." · "This is not the assigned session." · "Here is the next-session prompt."

Governance examples: "Does this require Architecture?" · "Should scope be expanded?" · "Architecture and Review disagree." · "No eligible reviewer exists." · "An exception to policy is proposed." · "Is this ready for adoption?"

## 6 · Initial governance analysis

After "Hello Governance, I want …", Governance performs intake:

- **A. Is this already understood?** — existing approved work · architecture decision · implementation · operational procedure · backlog item · work item.
- **B. Does the request require Architecture?** — architectural boundary change · new integration pattern · authority model change · new governance mechanism · unresolved architectural question · new responsibility/boundary.
- **C. If Architecture is not required** → Implementation / Developer may be next.
- **D. If neither Architecture nor Implementation can be selected deterministically** → **governance decision required.**

## 7 · Human authorization of role transitions

Governance must **not** silently change substantive roles. When a role transition requires human authority, present:

> "The next role is **\<ROLE\>**."
> 1. Yes
> 2. Write a prompt for \<ROLE\>
> 3. Stop

1 → authorize the role transition · 2 → produce a detailed prompt for the proposed next actor · 3 → stop. The human never performs workflow mechanics.

## 8 · Role transition mechanics

After the human business authorization exists:

```
current role
    → REGISTER next role
    → HANDOFF
    → Human START boundary
    → next actor ACTIVE
```

The mechanics are executed by the authorized Governance/Communication capability using the **canonical workflow mechanism** (AST-015 via AST-018 `appoint`). The human does not type `REGISTER`/`HANDOFF`/`START`, supply `mutationOwner`/`predecessor`, or edit JSON.

## 9 · Human START boundary

Human authority must **never be manufactured**. The system may prepare the transition, validate preconditions, explain the consequence, prepare the activation — it must **not** fabricate a human act. Yet the human must not be forced to understand the technical transition. Business language: *"Start the assigned session."* — never *"Append START transition with this JSON."*

## 10 · Current actor work

Every actor receives a **bounded assignment** (Architecture → architecture/design/discovery; Implementation → code/configuration/documentation; Reviewer → independent assessment). Actors perform substantive work. They do **not** accept their own work, silently change their own role, or silently adopt their own result.

## 11 · Local governance at session completion — the five business outcomes

Every working session behaves as a small Governance/Communication capability for **routine session-lifecycle operations**. At completion it answers *"Can I perform the next step?"* — producing **exactly one** business-level outcome:

```
CONTINUE · PERMISSION_REQUIRED · FRESH_SESSION_REQUIRED · GOVERNANCE_DECISION_REQUIRED · STOP
```

These are a **business rendering of the existing governed/application result model** — never a second workflow-state vocabulary, never a new state machine.

## 12 · Outcome A — current session can continue

> "I can perform the next step and will continue."

No human interaction required.

## 13 · Outcome B — current session can continue but needs permission

> "I can perform the next step, but I need your permission."
> 1. Yes
> 2. Write a prompt for the next step

1 → continue. 2 → produce a detailed prompt (the human may edit it). No workflow mechanics exposed.

## 14 · Outcome C — fresh session required

> "The next step requires a fresh session."
>
> "Start a new session and paste this prompt:"
>
> `<generated prompt>`
>
> "You may use this prompt unchanged or edit it."

This is the **complete** human task. The human must **not** know or provide the next session ID, appoint a process by UUID, `REGISTER`, `HANDOFF`, `START`, choose `mutationOwner`, or choose `predecessor`.

## 15 · Process identity rule

A process identity is **DISCOVERED FROM THE RUNTIME** — never invented by the human, copied from another session, inferred from prompt text, or chosen from a human-entered UUID. The human chooses **business intent / role**; the runtime supplies **concrete process identity**. Therefore:

```
role ≠ process identity
identity ≠ eligibility
eligibility ≠ appointment
appointment ≠ activation
activation ≠ authorization
```

## 16 · Fresh session bootstrap

The generated next-session prompt must instruct the new session to: (1) determine its actual runtime identity · (2) read authoritative workflow state · (3) identify the required role · (4) determine whether an appointment already exists · (5) verify independence/eligibility as required · (6) report its state · (7) **stop before substantive work if authorization is incomplete**. The human may edit the prompt. The new session must **not** trust prompt text as authoritative identity.

## 17 · No pre-known session UUID requirement

The system is **not** designed so the human must know `claude-code-session:<UUID>` before opening the fresh session. The human starts the fresh process first. The fresh process declares its identity. The system discovers it. Then the system determines how that process relates to the governed work.

## 18 · Fresh session — no existing appointment

If no actor is already appointed, the new session becomes a **candidate**. The system validates identity · independence · eligibility · role suitability · work-item compatibility. Then present the human:

> "A fresh eligible actor is available."
> 1. Yes — appoint and activate
> 2. Write / revise prompt
> 3. Stop

**Human choice 1 is the authority act.** No authority is created before the human act.

## 19 · Fresh session — existing appointment

If an actor is already appointed, compare **expected actor vs current runtime identity**:

- **MATCH** → `ACTOR_MATCH` → *"This is the assigned session. You may continue."*
- **MISMATCH** → `ACTOR_MISMATCH` → *"This is not the session assigned to this work."* then 1. Open the assigned session · 2. Prepare the exact prompt for the assigned session · 3. Stop.

Do **not**: create another appointment, replace the appointed actor, impersonate the appointed actor, self-register, or treat the mismatch as authorization.

## 20 · Wrong-session recovery

The system must **not** produce a long technical refusal as the primary human response. Instead:

> "This session is not assigned to this work."
> "The current session cannot perform the assignment."

…then a **business-language recovery path**. Technical details are available only as optional diagnostics.

## 21 · Review after substantive work

Every substantive actor's work must be reviewed: Architecture → Review · Implementation → Review · Developer → Review. The initial Governance intake/classification is the exception. The review determines whether the completed work: satisfies commissioned scope · preserves architectural invariants · satisfies required evidence · has no unauthorized scope expansion · is ready for the next governed step. **Review does not automatically equal adoption.**

## 22 · Review independence policy

This implementation does **not** hard-code whether every review must be same-session, subagent, or independent OS session. Review independence is a **separate policy** — `REVIEW_INDEPENDENCE_POLICY` is supported as a **concept and a policy placeholder**, never invented final policy.

## 23 · Review result

When review completes, the Communication Engineer performs routine routing. Results: **PASS** (determine next role) · **RETURN_FOR_CORRECTION** (route to the appropriate correction role) · **GOVERNANCE_DECISION_REQUIRED** (substantive conflict → escalate to Governance Engineer).

## 24 · Governance escalation

The Communication Engineer escalates when the problem is no longer routine: Architecture vs Review disagreement · no eligible actor · scope expansion · governance conflict · policy exception · adoption decision · unresolved workflow inconsistency · authority ambiguity. Message in business language — *"A Governance decision is required."* — then provide **reason, decision required, options**.

## 25 · Final adoption

After implementation and successful review, the Governance Engineer presents:

> "The work is ready for adoption."
> 1. Accept / Adopt
> 2. Return
> 3. Stop

The human decides. Governance records the decision using the canonical mechanism. **Verification ≠ adoption. Review PASS ≠ adoption.**

## 26 · AST-015 boundary

AST-015 remains the **SINGLE WORKFLOW AUTHORITY**. This model does **not**: duplicate fold logic · build another state machine · derive `mutationOwner` independently · modify workflow records directly · bypass `workflow-state.php` · create a parallel workflow store. All workflow writes use the canonical mechanism.

## 27 · AST-017 boundary

AST-017 remains **READ-ONLY · ON_DEMAND · GOVERNED-STATE / RESPONSIBILITY RESOLUTION**. It answers *"What is the current governed situation?"* It does **not**: create assignments · perform transitions · invent human authority · become the Communication Engineer.

## 28 · AST-018 boundary

AST-018 is **NEXT-ACTOR ORCHESTRATION** — it answers *"What should happen next?"* and *"What should the human do next?"*, owning next-actor decision rendering · continuation · permission prompt · fresh-session prompt · actor continuity · routine coordination · business-language recovery · governed handoff preparation. It must **not** become a second workflow engine · an identity authority · an adoption authority · a migration engine · EKS-07.

## 29 · Human-friendly contract — the six cases

| Case | Business language | Human options |
|---|---|---|
| **CASE 1** continue | "I can perform the next step." | — |
| **CASE 2** permission | "I can perform the next step, but I need your permission." | 1. Yes · 2. Write a prompt |
| **CASE 3** fresh session | "The next step requires a fresh session." + "Start a new session and paste this prompt: \<prompt\>" | paste unchanged / edit |
| **CASE 4** wrong session | "This session is not assigned to this work." | 1. Open assigned session · 2. Prepare exact prompt · 3. Stop |
| **CASE 5** governance | "A Governance decision is required." + business explanation | reason · decision · options |
| **CASE 6** adoption | "The work is ready for adoption." | 1. Accept / Adopt · 2. Return · 3. Stop |

## 30 · The human must never be required to (HARD acceptance criteria)

Provide a session UUID · choose runtime process identity · determine `mutationOwner` · determine `predecessor` · construct `REGISTER` · construct `HANDOFF` · construct `START` · edit workflow JSON · invoke `workflow-state.php` · inspect workflow records to route the work · diagnose INV-B · diagnose G-3 · understand token/tokenRef · manually calculate authorization. **If the human is forced to do any of these in a normal workflow, the implementation has failed the human-facing contract.**

## 31 · The only required physical human action for a fresh session

The system cannot safely manufacture a genuinely new OS/session process from inside the current process. The human may need to **START A NEW SESSION**. The system then provides the exact prompt; the human may **paste unchanged or edit before submitting**. After that, Governance/Communication handles the rest.

## 32 · Routine role transition model

```
business:  current role → human approval → next role
technical: current role → REGISTER next role → HANDOFF → Human START boundary → next actor ACTIVE
```

The human experiences only: "Next role: \<ROLE\>." · 1. Yes · 2. Write prompt · 3. Stop.

## 33 · DDD responsibility model

Work Item = the business request. Role = the responsibility currently assigned to the work. Assignment = governed binding between work, role and actor. Role Transition = business change from one responsibility to another. Human Decision = authority act allowing a business transition. Next Actor Recommendation = recommendation of what should happen next. Session Continuity = whether the current process can perform the next responsibility. Governance Engineer = human-facing governance and authority responsibility. Communication Engineer = transferable routine coordination responsibility. Reviewer = substantive assessment of completed work.

**Never confuse:** Role ≠ Session ≠ Process Identity ≠ Authority.

## 34 · Local governance rule

Every actor/session has access to the **Communication Engineer capability** for routine work — this does **not** mean every session becomes a Governance Engineer. Local Communication Engineer may determine the routine next step, ask permission, prepare prompts, perform routine handoff, report mismatch, escalate to Governance. Only the **dedicated Governance Engineer** handles substantive human-facing governance.

## 35 · Failure / stop model

A STOP is **not** automatically an architectural failure. Normal controlled stops include: human permission required · fresh session required · wrong session · no eligible actor · governance decision required · review return · adoption decision · invalid workflow precondition. Every stop must identify **what happened · why · who must act next · what the human can do**. Never leave the human with bare `UNRESOLVED` — always a business explanation and recovery path.

## 36 · Testing

RED → implementation → GREEN. The contract test `OperatingModelContractTest` (`tests/Unit/Platform/WorkflowEngine/OperatingModelContractTest.php`) uses the scoped `test_om_*` naming (distinct from the existing `n/r/p/s/t/usage` suites) and covers all 30 required scenarios of the governing contract — including the hard acceptance criteria: human never provides a session UUID, human START is never fabricated, AST-017 stays read-only, no second workflow engine, canonical assets byte-unchanged, deterministic read-only behavior.

## 37 · No unauthorized scope expansion

This model does **not**: reopen EKS-07 · redesign AST-015 · redesign AST-017 · implement autonomous session creation · implement automatic actor replacement · implement automatic adoption · implement migration · implement Phase 3/5 · create a new authority model · create a second workflow engine. When a deeper requirement is discovered: **record it as a follow-up and STOP.**

## 38 · Completion report

The implementing session's completion report distinguishes — and never collapses — the four states:

```
IMPLEMENTED   the capability, document and contract test were produced (this slice)
VERIFIED      independent verification of the produced work (PENDING — not this session, R-34/EP-02)
ADOPTED       governance-path adoption decision by the PO/ARB (PENDING)
AUTHORIZED    authorized for future use as the operating model (PENDING)
```

## 39 · Success criterion

The implementation is successful when the human can operate the whole lifecycle **without understanding workflow mechanics** — the seven interactions of §39 (start, role change, continuation, fresh session, wrong session, governance exception, adoption) all in business language, and the human never operates the workflow engine.

## 40 · Final architectural principle

- **HUMAN** — decides business intent and authority.
- **GOVERNANCE ENGINEER** — interprets human intent, determines the governed path, handles substantive governance, escalates decisions.
- **COMMUNICATION ENGINEER** — routine coordination, prompt preparation, next-step communication, session continuity, authorized routine handoff mechanics.
- **ACTOR** — performs substantive work.
- **REVIEWER** — independently assesses substantive work.
- **AST-015** — authoritative workflow mechanism.
- **AST-017** — authoritative responsibility / governed-state resolution.
- **AST-018** — next-actor orchestration and human-facing next-step guidance.

The system handles identity discovery, eligibility, assignment, REGISTER, HANDOFF, START, activation verification. The human handles **business decisions**. The human does **not** handle workflow mechanics.

---

## Implementing capability (Layer 2 — pointer)

The operating model is rendered by a thin, **read-only** presenter, `.claude/scripts/operating-model.php` (`outcome <workItem>` → the exactly-one business outcome of §11; `session <workItem>` → the MATCH / MISMATCH / candidate / governance presentation of §18–§20). It consumes AST-018 and AST-017 strictly as subprocesses, knows no store path, performs no fold, has no write path, and hides mechanics by default (`--show-mechanics` is the only escape hatch — §30, §35 optional diagnostics). Developer guide: `developer_guide/ai_platform/05_operating_model.md`.

---

**Traceability:** PO/ARB in-session commission 2026-08-22 (verbatim) · implementation prompt `…-KOS-OPERATING-MODEL-001-implementation-prompt.md` (verbatim, 40 sections) · implementation plan `docs/plans/20260822-2126-kos-operating-model-001-implementation-plan.md` · appointment + commission registrations `docs/knowledgeos/reviews/…-APPOINTMENT-registration.md` / `…-commission-registration.md` · adopted six-role operating model (2026-08-19 — role ≠ capability ≠ service ≠ position) · `KOS-NEXT-ACTOR-ORCHESTRATION-001` / `AST-018` (separate slice, unchanged) · `AST-015` · `AST-017` · `G-3` · `P-3` · `EP-01` · `EP-02`/`R-34` · `ES-004.3` · `INV-ATTR-1/2` · `F1` · placement: `scripts/doc-placement.php` (product-specific · knowledgeos → `docs/knowledgeos`, exit 0)

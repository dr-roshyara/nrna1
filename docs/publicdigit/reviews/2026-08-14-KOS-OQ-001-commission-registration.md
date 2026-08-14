# KOS-OQ-001 — Registration of the Operational-Qualification Commission

**Type:** Governance registration (Session 2) · **Date:** 2026-08-14
**⛔ Registration only. No implementation. The OQ is not performed here. Session 1 is not started here. One element of the commission is INCOMPLETE and is reported, not invented (§3).**

---

## 1 · The signed performative commission — REGISTERED VERBATIM

> *"PO/ARB COMMISSION — KOS-AI-ORCH-001 — Operational Qualification*
>
> *I authorize Operational Qualification using KOS-OQ-001 as the dedicated real governed work item.*
>
> *Vehicle: KOS-OQ-001 — **[NAME THE TASK HERE]** — Example: "KOS-OQ-001 will add a new configuration parameter to the system, following the full governance → architecture → implementation → verification lifecycle."*
>
> *Evidence compiler: Session 1 (Verification).*
>
> *Evidence period: one complete governed work item, from initiation through handoff and independent verification.*
>
> *Scope: exercise the existing Increment-1 mechanism in normal governed work. No Increment-2 enforcement, hooks, locks, leases, or unrelated implementation.*
>
> *Signed: PO/ARB Chief · Date: 2026-08-14"*

## 2 · What is registered as PERFORMED

| Element | Status |
|---|---|
| OQ authorized; **KOS-OQ-001** designated as the vehicle — *a real governed work item selected as the operational qualification vehicle, not a test* (adopted vocabulary) | ✅ registered |
| **Session 1** designated independent OQ evidence compiler | ✅ registered |
| Evidence period: **one complete governed work item**, initiation → handoffs → independent verification | ✅ registered |
| Scope: existing Increment-1 mechanism in normal governed work; **no Increment-2 enforcement, hooks, locks, leases, or unrelated implementation** | ✅ registered |
| Qualification semantics (adopted in advance from the PA review): a QUALIFIED outcome means **operationally qualified for the scope defined by this commission** — never "production-ready", never a silent Increment-2 authorization | ✅ registered |
| Session 4 dark by default; Session 3 only on an actual implementation gap | ✅ registered (routing, per the review on file) |

**Record created through the mechanism itself** (KOS-OQ-001 governed from birth — itself OQ evidence): `init` with the four canonical roles · Governance session assignment registered · OQ grant written with `humanActRef` = §1 of this artifact + its registration commit (the established G-KOS-INC1 reference pattern; the registration creates no authority).

## 3 · ⬜ THE ONE MISSING ELEMENT — reported, not invented

**The vehicle's TASK is unnamed: the signed text carries the literal placeholder `[NAME THE TASK HERE]`.** The attached example (*"add a new configuration parameter…"*) is an **example, not a designation** — under A-3, a template/example is not a registrable act, and Session 2 does not convert it into one.

**Consequence:** KOS-OQ-001 exists, its authority state exists, its roles are declared — **but no session may proceed past registration, because there is no task for Governance to gate.** The OQ clock does not start.

> **Required: one line from the PO naming the real task KOS-OQ-001 performs.** (Adopting the example verbatim is one valid line — *"The task is: add a new configuration parameter to the system"* — but it must be said, not inferred.)

## 3a · REVISED COMMISSION (rev 2, same day — registered; the gap PERSISTS)

A second signed commission arrived during registration. **Newly performed and registered:** the task shall proceed through the applicable governed lifecycle (Governance → Architecture → Implementation → Verification) · **qualification protocol** — *"after the evidence is compiled, I will decide whether KOS-AI-ORCH-001 is operationally qualified for the scope defined by this commission"* · **closure protocol** — *"after my qualification decision, Session 2 shall register the decision and perform G-1 governance closure"* · **implementation limit** — *"this commission does not authorize implementation beyond what is strictly required by the existing approved Increment-1 boundary."*

**Unchanged: the task is STILL a placeholder — `[ONE SPECIFIC, REAL ENGINEERING TASK]`.** The §3 gap persists across both revisions; one PO line naming the task remains the sole blocker.

**Mechanism observations during record creation (OQ evidence, accumulating):** the contract refused Governance's own writes **three times** before accepting — invalid grant status vocabulary (`active`→`AUTHORIZED`, on the Inc-1 record) · missing `recordedBy` on REGISTER · wrong identity field name (`sessionId`→`session`). Each refusal was precise, corrective, and exit-coded; each acceptance followed the corrected vocabulary. **The record now stands: Governance session `CREATED`, grant `G-KOS-OQ-001 AUTHORIZED`, `mutationOwner: null`, state `OPEN`.**

## 4 · Authority state after registration

```
KOS-OQ-001:            EXISTS · roles declared · OQ grant AUTHORIZED (scope-limited per §1)
                       · TASK UNDESIGNATED → work may not begin
Session 1:             designated compiler — NOT started
Sessions 3 / 4:        dark by default (gap-triggered only)
Increment 2 / hooks / locks / leases: NOT authorized (restated by the commission itself)
Increment 1:           closed at the verification gate; OQ authorized; G-1 awaits the
                       qualification decision
Election track:        unchanged (start()/A-2 commission pending · EM-OPEN-021 open)
```

**Traceability:** the signed commission (§1, this artifact + its commit = `humanActRef`) · OQ authorization ruling (closure §8, `b6ff24ac`) · ownership analysis (closure §9) · PA review corrections (vocabulary; qualified-for-scope) · A-3 (`604f4578`) · G-KOS-INC1-OQ (`5db67fc6`) · `workflow-state.php` (AST-015).

## 5 · TASK DESIGNATION — performative, verified, REGISTERED VERBATIM (2026-08-14)

> *"PO/ARB TASK DESIGNATION — KOS-OQ-001. I hereby designate the task for KOS-OQ-001:*
>
> ***"Add a new configuration parameter to the system, including its documented default behavior, configuration handling, and automated verification, following the Governance → Architecture → Implementation → Verification lifecycle."***
>
> *This designation completes the task designation for the OQ commission. It does not itself start implementation or any other execution activity; execution remains subject to the applicable workflow gates. — Signed: PO/ARB Chief, 2026-08-14"*

**Verification (step 1):** performative ("I hereby designate"), signed, exact wording — ✅ a registrable human act under A-3. **The §3/§3a gap is CLOSED: the task is DESIGNATED.** This section + its registration commit = the `humanActRef` for the record write.

**Record updates (via the mechanism, existing vocabulary only — no schema change):** the contract has no task field/transition type (observed for the evidence file, not repaired); the designation is carried into the machine record as grant **`G-KOS-OQ-001-TASK`** (task verbatim in scope; the designation's own no-start clause preserved) + the **bootstrap HANDOFF (`from: null`)** to the Governance session carrying the commission+designation as token — the Inc-1 precedent pattern exactly.

**Next applicable gate (determined from the contract, NOT performed):** **START of `S2-governance-2026-08-14-oq`** — the contract requires the G-3 conjunction: the bootstrap handoff (now recorded) **AND a recorded human START act, which does not exist**. The designation is NOT that act (its own text says so; the transition principle stands). **Execution: NOT STARTED. No session activated. Sessions 1/3/4 unchanged.**

## 6 · START ACT — performative, verified, REGISTERED VERBATIM (2026-08-14)

> *"PO/ARB START ACT — KOS-OQ-001. I hereby authorize and start the Governance session for KOS-OQ-001. Session 2 is authorized to perform only its Governance responsibilities within the approved KOS-OQ-001 Operational Qualification scope and the designated task. This START does not authorize architecture changes, implementation, verification, Increment-2 enforcement, hooks, locks, leases, or unrelated work. — Signed: PO/ARB Chief, 2026-08-14"*

**Verification:** performative ("I hereby authorize and start"), signed, scope-limited — ✅ a registrable human act (A-3). **Distinct from the §5 designation — the two-acts separation held.** START transition performed through the mechanism with this section + its commit as the recorded `humanAct`; G-3 conjunction complete (bootstrap handoff §5 + this act). **Expected effect: Governance session ACTIVE, mutation owner = Governance. Sessions 1/3/4: unchanged — this START authorizes Governance work only.**

**Governance's first bounded work (next, per the adopted next-role-by-work discipline):** determine and register the governance boundary for the designated task — what governance the configuration-parameter work actually requires — then hand off to whichever role the work genuinely needs. Not performed in this registration step.

## 7 · GOVERNANCE BOUNDARY DETERMINATION (the ACTIVE session's bounded work, 2026-08-14)

### 7.1 · What the task actually requires (analysis, not diagram)

The designated task is generic by the PO's deliberate choice (two placeholder rounds preceded it): *"a configuration parameter"* — no name, no purpose, no surface. **Turning that into a specific parameter (name · purpose · owning context · placement · default semantics · verification surface) is genuine DESIGN work that nobody has performed.** Letting Implementation choose it silently would be the placement-by-convenience anti-pattern this programme's whole Election evidence base refutes; Governance choosing it would be Governance doing design.

**Decisive, and independent of any diagram:** the PO's designated task text itself prescribes the lifecycle — *"following the Governance → Architecture → Implementation → Verification lifecycle."* **Architecture is in the path by the performative designation's own words.** The analysis above independently agrees: a genuine (small) architectural question exists — parameter selection, placement, and ownership under constraints. **Not manufactured; doubly grounded.**

**Existing architecture coverage (evidence):** the *mechanics* are covered — the repository has an approved configuration pattern (Laravel `config/*.php` + `env()` + default + consumer; e.g. `config/election.php:50` → `User.php:317`; Infrastructure layer, framework-allowed by the standing layer rules). **What is NOT covered: which parameter, where, owned by what.** So Architecture's step is small: selection + placement + boundary, not a new pattern.

**Human decision required NOW? NO.** The START authorization + the designated task cover this determination. The reserved human decision points downstream: **(i)** approval of Architecture's boundary (the established gate pattern, and implied by the designated lifecycle), **(ii)** the S4 START act (G-3's human half), **(iii)** the qualification decision. No Business Decision Request is needed at this gate — nothing here is outside recorded authorization.

### 7.2 · The governance boundary (binding on the work; the handoff token)

1. **Exactly ONE configuration parameter.** Real and useful — the OQ vehicle is real governed work, not a toy.
2. **Behavior-preserving default:** the parameter's default MUST preserve current system behavior, and that default MUST be documented (the designation's "documented default behavior").
3. **Surface constraint (evidence-resolved, escalation-guarded):** the established Laravel configuration pattern is the presumptive surface ("the system" = the product application; the platform mechanism is frozen under OQ and `.claude` config is excluded by standing rules). **Architecture selects the parameter by evidence — preference: making an existing hardcoded operational value configurable** (adds configurability without inventing behavior). **If every viable candidate carries business meaning (changes what the product means, not how it is tuned): STOP and return a Business Decision Request** — a parameter must never become a silent business decision.
4. **Election guard:** the parameter must NOT touch Election-critical semantics, any adopted-rule enforcement surface (EM-VOT-002/003 predicates, entitlement evaluation, credential checks), or anything under the Election stop-register. Election grants remain NONE; the OQ creates no side door.
5. **OQ freeze restated:** no `workflow-state.php` or platform-script change · no Inc-2/hooks/locks/leases · observations recorded, never fixed.
6. **Verification:** automated verification of the parameter is part of the task (Implementation writes it; Session 1's OQ evidence compilation remains separate and independent).
7. **Commit hygiene:** product-code commits carry no product-story ID (no false traceability); label as the OQ task. Developer-guide DoD applies to the implementation step.
8. **Lifecycle gates:** Architecture presents its boundary for **human approval** before Implementation; RED before GREEN; Session 1 verifies independently; Governance closes.

### 7.3 · Determination and handoff

> **NEXT ROLE: ARCHITECTURE (Session 4).** Work: select and specify the ONE parameter (name · purpose · owning context · placement · default + documented behavior · verification surface) under §7.2, presented as a small boundary for human approval. **Not activated by this handoff** — the contract's G-3 conjunction still requires the PO's START act for the architecture session; the handoff records only Governance's completed determination and transfers nothing until then.

**Recorded through the mechanism:** REGISTER of the architecture session assignment (predecessor: this Governance session) + HANDOFF (from this session, token = this §7 + its commit). Per the contract, this session becomes HANDED_OFF and releases mutation ownership — **its assignment's bounded work is complete** (R8: later Governance acts are new assignments or registrar acts, never a role mutation).

### 7.4 · OQ evidence observations (for Session 1's compilation — recorded, not assessed, not fixed)

**E-1** three contract refusals of Governance's own writes (grant-status vocabulary · missing `recordedBy` · `sessionId` vs `session`) — precise, corrective, exit-coded · **E-2** fold *projection* displays less than the validation fold computes (`handoffsTo` omitted from output; durably present in the log) · **E-3** no task-designation vocabulary in the contract (task carried via grant scope — interpretation was required) · **E-4** G-3 conjunction enforced in practice: handoff alone never yielded ACTIVE; the START succeeded only with both halves · **E-5** the two-acts separation (designation ≠ start) held under a real attempt-shaped sequence · **E-6** next-role determination required interpretation (resolved by the designation's own lifecycle clause — the process needed a human-text anchor, and had one) · **E-7** bootstrap handoff (`from: null`) validated only while ownerless — the contract prevented a second bootstrap. **None of this is qualification; OBSERVATION ≠ DEFECT ≠ HUMAN DECISION.**

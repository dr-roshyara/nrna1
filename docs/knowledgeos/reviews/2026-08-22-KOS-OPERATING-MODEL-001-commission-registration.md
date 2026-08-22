# `KOS-OPERATING-MODEL-001` — commission RECORDED (provisional work item) · FINAL GOVERNANCE + COMMUNICATION OPERATING MODEL

**Recorded by:** Governance-recording — `claude-code-session:b51dba91-6fc4-4110-b2f2-ac76ad0f3808` *(disclosed GOVERNANCE-RECORDING capacity, per the PO/ARB's explicit in-session direction; identity disclosed — this process is the CORRECTION-001 author. Recording ≠ implementing/verifying/accepting/adopting/authorizing.)*
**Act:** PO/ARB 2026-08-22 — verbatim: *"Create a new work item for the final Governance + Communication operating model, while preserving `KOS-NEXT-ACTOR-ORCHESTRATION-001` / `AST-018` as a separate existing slice that continues through independent verification and adoption."*
**Capability:** FINAL OPERATING MODEL — the human-facing operating layer: **Dedicated Governance Engineer** (human-facing governance responsibility) + **transferable Communication Engineer** (routine coordination responsibility), the role-transition boundary, fresh-session handling, the review lifecycle, the adoption lifecycle, and the rule that **the human never operates workflow mechanics** — operationalizing the already-adopted six-role operating model (2026-08-19) over the existing workflow estate (AST-015 · AST-017 · AST-018).
**Commissioned prompt (verbatim):** `docs/knowledgeos/reviews/2026-08-22-KOS-OPERATING-MODEL-001-implementation-prompt.md` — the PO/ARB's **whole operating-model implementation contract** (40 sections).
**Design principle (as stated by PO/ARB in the prompt):** *"The human speaks BUSINESS LANGUAGE. The system handles TECHNICAL WORKFLOW MECHANICS. The human never operates the workflow engine."*

> ⛔ **This registration records the commission only. It creates no authority, no lane, no grant, no work-item number, no state change.** The capability is **NOT implemented**, **NOT adopted**, **NOT authorized for future use**. The work-item name `KOS-OPERATING-MODEL-001` is **provisional** — pending PO/ARB confirmation. **No implementation actor is appointed by this recording.** The appointee identity remains the placeholder `claude-code-session:<ACTOR-ID>` until a fresh process declares it from the runtime mechanism.

---

## 1 · What the PO/ARB decided (2026-08-22) — Option B

Following the governance review delivered in-session (reviewed the FINAL OPERATING MODEL prompt against the estate; the review created no authority), the PO/ARB **selected Option B**:

| Decision | Value |
|---|---|
| New work item for the operating model | **CREATED (this recording)** — provisional name `KOS-OPERATING-MODEL-001` |
| Implementation contract | the FINAL OPERATING MODEL prompt, **verbatim** (`…-implementation-prompt.md`, 40 sections) |
| `KOS-NEXT-ACTOR-ORCHESTRATION-001` / `AST-018` | **PRESERVED as a separate existing slice** — continues through independent verification and adoption, **unaffected by this act** |
| This process (`b51dba91`) | **NOT the implementation actor** — it is the CORRECTION-001 author (neither fresh nor independent for this estate) and does not self-appoint |

## 2 · The separation boundary — AST-018 preserved (binding)

The new work item is **NOT a continuation, rename, or supersession** of `KOS-NEXT-ACTOR-ORCHESTRATION-001` / `AST-018`. Two governed slices now exist:

```
KOS-NEXT-ACTOR-ORCHESTRATION-001 / AST-018   (SEPARATE, EXISTING, UNCHANGED)
   implementation lane 5c0e13c1 … — REGISTER → HANDOFF → human START (seq 1–3) — ACTIVE
   → record STOP on 5c0e13c1  (per the START act's own declared sequence)
   → PO/ARB appoints a fresh independent verifier
   → Governance REGISTER (role = verification) → HANDOFF → human START
   → independent verification → Governance adoption review → PO/ARB adoption decision
   → FU-1 (store durability) / FU-2 (attribution vs mention) classification by Governance

KOS-OPERATING-MODEL-001                         (NEW — this recording)
   FINAL OPERATING MODEL implementation contract (verbatim prompt)
   → PO/ARB confirms work-item name + appoints a fresh independent implementation session
   → eligibility → workflow record + REGISTER (role = implementation) → HANDOFF → human START
   → implement → STOP → independent verification → governance path → adoption (NOT automatic)
```

**The new slice CONSUMES AST-018 as the existing canonical mechanism for next-actor orchestration.** It does **not** modify, re-implement, re-open, or await the adoption of AST-018. AST-018's five commands (`next-actor` · `appoint` · `prepare-prompt` · `prepare-next-session` · `stop`) remain the mechanical layer the operating model renders business language onto. If implementing this slice reveals a required change to AST-018, that is a **follow-up on the AST-018 slice** (recorded), never implemented here.

## 3 · Scope (from the commissioned prompt)

- **§2–§5 · Governance Engineer vs Communication Engineer:** business/authority/exceptions → Governance Engineer; routine/operational/session coordination → Communication Engineer; human authority → Human/PO-ARB. Communication Engineer is a **transferable responsibility** ("NOT a permanent person"); Governance Engineer is the human-facing governance responsibility.
- **§6 · Initial governance analysis:** intake → already-understood? · Architecture required? · direct Implementation? · governance decision required?
- **§7–§9, §32 · Role transitions:** "Next role is <ROLE>. 1 Yes / 2 Write a prompt / 3 Stop"; mechanics REGISTER → HANDOFF → human START executed by the capability; human START **never fabricated**.
- **§11–§14, §16–§17 · Session lifecycle & fresh sessions:** exactly one business outcome (CONTINUE / PERMISSION_REQUIRED / FRESH_SESSION_REQUIRED / GOVERNANCE_DECISION_REQUIRED / STOP); fresh-session kickoff prompt generated from governed facts; **no pre-known session UUID required**; identity discovered from the runtime.
- **§18–§20 · Fresh-session appointment & wrong-session recovery:** no-existing-appointment → candidate → human "Yes — appoint and activate" is the authority act; existing-appointment MATCH/MISMATCH; business-language recovery, never a bare technical refusal.
- **§21–§24 · Review lifecycle:** review after every substantive actor's work; `REVIEW_INDEPENDENCE_POLICY` is a **policy placeholder** (never hard-coded, never invented final policy); results PASS / RETURN_FOR_CORRECTION / GOVERNANCE_DECISION_REQUIRED; escalation to Governance when no longer routine.
- **§25 · Final adoption:** Governance Engineer presents; human decides (1 Accept/Adopt · 2 Return · 3 Stop); **verification ≠ adoption; review PASS ≠ adoption**.
- **§29–§30, §39 · Human-friendly contract + hard acceptance criteria:** the six human-facing cases; the human is never required to know a UUID, REGISTER/HANDOFF/START, mutationOwner, predecessor, tokens, or workflow JSON.
- **§36 · Testing:** RED → implementation → GREEN; minimum 30 named tests; scoped naming that does not collide with existing AST-015/AST-016 test identifiers.
- **§38 · Completion report:** `session_completion` / `next_actor` / `authorization`; explicitly distinguish IMPLEMENTED · VERIFIED · ADOPTED · AUTHORIZED (never collapse).

## 4 · Boundaries (binding, from the prompt §26–§28, §37 + estate)

| Boundary | Rule |
|---|---|
| AST-015 | SINGLE WORKFLOW AUTHORITY — no duplicate fold, no second state machine, no independent mutationOwner derivation, no direct record modification, no bypass of `workflow-state.php`, no parallel store |
| AST-017 | READ-ONLY · ON_DEMAND · responsibility/resolution only; does not create assignments, perform transitions, invent human authority, or become the Communication Engineer |
| AST-018 | NEXT-ACTOR ORCHESTRATION — consumed, **not** modified/re-implemented/re-opened by this slice; must not become a second engine, identity authority, adoption authority, migration engine, or EKS-07 |
| EKS-07 | NOT reopened — no autonomous session creation, no automatic actor replacement, no automatic adoption (§37) |
| Migration | NOT touched (§37) |
| Adoption | NOT claimed — `implemented` ≠ `adopted` ≠ `authorized for future use`; adoption only via the governance path after independent verification |
| Workflow vocabulary | **No second workflow-state vocabulary** (§11). The declared role vocabulary (architecture · implementation · verification · governance · human) is unchanged; "Governance Engineer" / "Communication Engineer" are **operating responsibilities** (adopted six-role model 2026-08-19), NOT new workflow roles (P-3) |
| Six-role non-equivalences | ADOPTED 2026-08-19 — role ≠ capability ≠ agent ≠ platform service ≠ organizational position. The implementation must keep Governance Engineer / Communication Engineer as **responsibilities**, not platform services or positions (see §5) |
| Startup wiring | §2 "starts with the Governance Engineer" is an **operating-model description**, NOT SESSION_START wiring (V-3 binding remains) |
| Registry-first / placement | any new asset is registered before implementation (R-17); any new doc placed via `scripts/doc-placement.php` |

## 5 · Recorded review constraints (from the in-session governance review — binding where estate law, pending PO/ARB confirmation where noted)

1. **Responsibilities, not services/positions.** "Dedicated Governance Engineer" and "transferable Communication Engineer" must be implemented as operating **responsibilities** — the six-role non-equivalence (role ≠ service ≠ position) is adopted estate law and binds regardless of PO/ARB confirmation.
2. **Not workflow vocabulary.** Neither name enters the declared workflow role vocabulary consumed by AST-015/AST-017/AST-018 and the session-completion template without an explicit governed decision (P-3).
3. **Layer scope — PENDING PO/ARB confirmation.** The contract spans three layers (operating model · capability · testing). The PO/ARB should state which layers the implementing session must deliver (software vs documented operating procedure vs policy input) before implementation.
4. **`REVIEW_INDEPENDENCE_POLICY`** stays a policy placeholder (§22) — the estate's concrete independence bars live in commissions and prior gate refusals, not in code.
5. **Outstanding estate obligations are NOT displaced** by this work item: the AST-018 STOP → verification → adoption sequence (§2) and FU-1/FU-2 classification proceed on the AST-018 slice; this new slice does not absorb them.

## 6 · Non-actions honored by this recording

⛔ no implementation · ⛔ no lane created · ⛔ no REGISTER/HANDOFF/START · ⛔ no transition write · ⛔ no grant · ⛔ no appointment (appointee identity still placeholder) · ⛔ no workflow-record creation (init is a governed write, not a recording act) · ⛔ no change to `KOS-NEXT-ACTOR-ORCHESTRATION-001` / `AST-018` · ⛔ no change to AST-015/AST-017/AST-018 · ⛔ no EKS-07 work · ⛔ no migration work · ⛔ no adoption claim · ⛔ no spawning of an implementation actor by the recorder (a fresh implementation session must be a **separate process** appointed by the PO/ARB — a subagent of this session would inherit this session's identity and would not be a fresh actor).

## 7 · Next actor (recorded, not decided)

```
commission recorded (this artifact) + verbatim prompt preserved      ✅
   → PO/ARB confirms the work-item name + appoints a fresh independent
     implementation session                                            ← NEXT
   → appointee declares identity (CLAUDE_CODE_SESSION_ID) per a
     candidate-declaration prompt
   → eligibility / independence / non-participation verification
   → workflow record created (init via AST-015) + REGISTER (role = implementation)
   → HANDOFF
   → human START (G-3)
   → implementation session runs the verbatim FINAL OPERATING MODEL prompt → STOP
   → independent verification → governance path → adoption (NOT automatic)
```

The implementation session must be a **fresh process** — not the AST-018 implementation actor `5c0e13c1`, not the AST-017 producer `8a525719`, not the CORRECTION-001 author `b51dba91`, not the re-verifier `8deac5de`, not the prior verifier `d1612e03`, not Governance `b64828fe`, not the would-be verifier `7c2690ae`. It receives the commissioned prompt verbatim (`…-implementation-prompt.md`). **This recording is not that appointment.**

## 8 · session_completion

```yaml
session_completion:
  status:            # COMMISSION RECORDED (Option B) — capability NOT implemented, NOT adopted
  completed_work:    # captured the PO/ARB's decision (new work item for the final Governance +
                     #   Communication operating model; AST-018 preserved as a separate slice);
                     #   preserved the FINAL OPERATING MODEL prompt verbatim (…-implementation-prompt.md);
                     #   recorded the separation boundary, scope, boundaries, review constraints,
                     #   non-actions, next-actor sequence; placement derived
  evidence:          # PO/ARB in-session decision 2026-08-22 (verbatim); the FINAL OPERATING MODEL prompt
                     #   (verbatim, 40 sections); in-session governance review (Option B selected);
                     #   adopted six-role operating model (2026-08-19); KOS-NEXT-ACTOR-ORCHESTRATION-001 /
                     #   AST-018 registry entry + lane record (seq 1–3) + independent-verification
                     #   START GATE REFUSAL (7c2690ae) + FOLLOW-UP observations (FU-1/FU-2)
  open_items:        # work-item name provisional (KOS-OPERATING-MODEL-001); implementation session NOT
                     #   appointed/registered/started; capability NOT implemented/adopted; layer scope
                     #   pending PO/ARB confirmation; AST-018 slice still needs STOP → verification lane
                     #   → adoption; FU-1/FU-2 classification still open on the AST-018 slice

next_actor:
  recommended_role:  # po/arb (confirm work-item name + appoint implementation session) then governance
                     #   (create record + REGISTER role=implementation → HANDOFF → record human START)
  reason:            # the implementation session must be a fresh, appointed, lanned process; only the
                     #   PO/ARB holds appointment authority; the recorder cannot manufacture a lane
                     #   (P-3; G-3; EP-01 — this is a new, non-trivial engineering work item). AST-018's
                     #   own governed path (STOP → verification → adoption) is NOT blocked by this act.
  blocking_condition: # no confirmed work-item number; no appointed implementation process; no
                     #   role=implementation lane on this work item

authorization:
  current_session_can_continue:   # false (AST-017 for this process: UNRESOLVED/UNRESOLVABLE; capacity=recording only)
  authorized_to_act:              # false — this process (b51dba91, correction author) does not implement,
                                  #   appoint, register, or adopt this capability
  requires_human_decision:        # true — PO/ARB: confirm work-item name + appoint the implementation
                                  #   session; Governance: create the record + REGISTER → HANDOFF → record
                                  #   human START
```

---

**Traceability:** PO/ARB in-session decision 2026-08-22 (verbatim) · governance review of the FINAL OPERATING MODEL prompt (delivered in-session by `b51dba91`, 2026-08-22; Option B selected) · commissioned prompt `…-KOS-OPERATING-MODEL-001-implementation-prompt.md` (verbatim, 40 sections) · adopted six-role operating model (`2026-08-19-six-role-operating-model-adoption.md` — role ≠ capability ≠ service ≠ position) · `KOS-NEXT-ACTOR-ORCHESTRATION-001` / `AST-018` (registry `adoption: verify`; lane `5c0e13c1` seq 1–3; `…-AST-018-INDEPENDENT-VERIFICATION-START-GATE-REFUSAL-7c2690ae.md`; `…-FOLLOW-UP-observations.md`) · `AST-015` · `AST-017` · `G-3` · `P-3` · `EP-01` · `EP-02`/`R-34` · `INV-ATTR-1/2` · `ES-004.3` · `F1` · placement: `scripts/doc-placement.php` (product-specific · knowledgeos → `docs/knowledgeos`, exit 0)

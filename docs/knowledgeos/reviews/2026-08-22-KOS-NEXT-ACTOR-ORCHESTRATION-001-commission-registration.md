# `KOS-NEXT-ACTOR-ORCHESTRATION-001` — commission RECORDED (provisional work item) · NEXT-ACTOR ORCHESTRATION / BUSINESS-LANGUAGE HANDOFF

**Recorded by:** Governance-recording — `claude-code-session:b51dba91-6fc4-4110-b2f2-ac76ad0f3808` *(disclosed GOVERNANCE-RECORDING capacity, per the PO/ARB's explicit in-session direction; identity disclosed — this process is the CORRECTION-001 author. Recording ≠ implementing/verifying/accepting/adopting/authorizing.)*
**Act:** PO/ARB 2026-08-22 — framed a **small DDD-oriented Governance Architecture capability**, **separate from AST-017** and **separate from the migration**, and directed: *"Use this prompt with the implementation session."*
**Capability:** NEXT-ACTOR ORCHESTRATION / BUSINESS-LANGUAGE HANDOFF — translate a human business decision ("Appoint a fresh independent reviewer.") into governed workflow mechanics (REGISTER → HANDOFF → START) without exposing mechanics, without weakening governance or human authority.
**Commissioned prompt (verbatim):** `docs/knowledgeos/reviews/2026-08-22-KOS-NEXT-ACTOR-ORCHESTRATION-001-implementation-prompt.md`
**Design principle (as stated by PO/ARB):** *"The domain decides what authority means; the orchestration layer translates a human business decision into governed workflow mechanics."*

> ⛔ **This registration records the commission only. It creates no authority, no lane, no grant, no work-item number, no state change.** The capability is **NOT implemented**, **NOT adopted**, **NOT authorized for future use**. The work-item name `KOS-NEXT-ACTOR-ORCHESTRATION-001` is **provisional** — pending PO/ARB confirmation.

---

## 1 · Why this work item exists

An observed operational problem in the current estate: a human must understand workflow mechanics (REGISTER, HANDOFF, START, predecessor, mutation owner, transition JSON, `workflow-state.php`) to act on a business decision. The governing sequence after the independent re-verification (verifier `8deac5de`, V-1/V-3/V-5 PASS) produced the START GATE REFUSAL for the Governance adoption review — precisely because no governed lane existed and the only candidate at hand was the correction author. The PO/ARB's direction: build the capability that removes the mechanical burden while preserving human authority, AST-015 authority, AST-017 read-only semantics, fail-closed behaviour, DDD boundaries, provenance, and determinism.

## 2 · What is being commissioned (scope, from the prompt)

- **Use case 1 — `DetermineNextActorAction`:** business-language next-actor decision (`NEXT_ACTOR_REQUIRED` with role · reason · business explanation · required_human_decision · options `APPOINT` / `DRAFT_PROMPT` / `STOP`).
- **Use case 2 — `AppointReviewer`:** executes the governed mechanical consequences of the human authority act (resolve candidate → verify independence/eligibility/no-conflict → identify mutation owner → prepare transition sequence → REGISTER → HANDOFF → human START per G-3 → verify ACTIVE → business-language report).
- **Advisory branch — `PrepareReviewerPrompt`:** writes the reviewer prompt only; no appointment, no lane, no HANDOFF, no START.
- **Human authority boundary:** the application MUST NOT manufacture the human decision (no inferred "Yes", no AI-message-as-humanAct, no auto-appoint from a single candidate, no auto-START).
- **Candidate selection:** deterministic, fail-closed — `NO_ELIGIBLE_CANDIDATE` (0) · propose (1) · `AMBIGUOUS` (>1) · never silently choose · identity from the runtime mechanism / authoritative record.
- **TDD:** RED-first, minimum 15 property tests (no human decision → no appointment; candidate unavailable → no transition; AMBIGUOUS → no selection; ineligible → no transition; STOP → no transition; DRAFT_PROMPT → no transition; REGISTER uses current mutation owner; HANDOFF uses correct predecessor; START requires humanAct; transition failure → fail closed; resulting lane ACTIVE; AST-015 remains the only interpreter; no raw parsing outside bounded interfaces; business language hides mechanics; plus the human "Yes" path).
- **Regression scenario:** the real sequence — Architecture completes → Independent verification completes → Governance adoption review required → produce *"A fresh independent Governance reviewer is required."* → ask *"Should I appoint a fresh independent reviewer now?"* → options 1/2/3.

## 3 · Boundaries (binding, from the prompt)

| Boundary | Rule |
|---|---|
| AST-017 | stays READ-ONLY · ON_DEMAND · responsibility/resolution only; **not modified to perform transitions** |
| AST-015 | stays SINGLE WORKFLOW AUTHORITY; new capability implements **no fold**, reconstructs no state, parses no raw JSON for state, duplicates no mutation-owner/authorization logic |
| Transition execution | all transition writes through the canonical governed mechanism; **no** direct JSON edit, no Python bypass, no second transition store |
| EKS-07 | NOT reopened; no autonomous coordination/marketplace/scheduler/authority-transfer/attestation/new bounded contexts; deeper requirements → record FOLLOW-UP |
| Migration | NOT touched: `KOS-AIP-GOV-STATE-DURABILITY` migration, Phase 3, Phase 4b, Phase 5, migration authorization, DV-1…DV-7, RV-1…RV-7 |
| Adoption | NOT claimed: `implemented` ≠ `adopted` ≠ `authorized for future use`; no adoption without an explicit Governance/PO/ARB path for this new work item |

## 4 · Non-actions honored by this recording

⛔ no implementation · ⛔ no lane created · ⛔ no REGISTER/HANDOFF/START · ⛔ no transition write · ⛔ no grant · ⛔ no AST-015/AST-017 modification · ⛔ no EKS-07 work · ⛔ no migration work · ⛔ no adoption claim · ⛔ no spawning of an implementation actor by the recorder (a fresh implementation session must be a **separate process** appointed by the PO/ARB — a subagent of this session would inherit this session's identity and would not be a fresh actor).

## 5 · Next actor (recorded, not decided)

```
commission recorded (this artifact)                      ✅
   → PO/ARB names/confirms the work item + appointment
   → Governance REGISTER (role = implementation)         ← NEXT (with the PO/ARB act)
   → HANDOFF
   → human START
   → implementation session runs the commissioned prompt → implementation → STOP
   → independent verification → governance path → adoption (NOT automatic)
```

The implementation session must be a **fresh process** — not the producer `8a525719`, not the author `b51dba91`, not the re-verifier `8deac5de`, not Governance `b64828fe`, not the prior verifier `d1612e03`. It receives the commissioned prompt verbatim (`…-implementation-prompt.md`). **This recording is not that appointment.**

## 6 · session_completion

```yaml
session_completion:
  status:            # COMMISSION RECORDED — capability NOT implemented, NOT adopted
  completed_work:    # captured the PO/ARB's framing + design principle; preserved the commissioned
                     #   implementation prompt verbatim (…-implementation-prompt.md); recorded scope,
                     #   boundaries, non-actions, next-actor sequence (this artifact); placement derived
  evidence:          # PO/ARB in-session act 2026-08-22; …-implementation-prompt.md (verbatim);
                     #   this commission registration; prior sequence (independent re-verification PASS
                     #   → Governance adoption review gate refused — b51dba91 is the correction author)
  open_items:        # work-item number provisional (KOS-NEXT-ACTOR-ORCHESTRATION-001); implementation
                     #   session NOT appointed/registered/started; capability NOT implemented/adopted;
                     #   AST-017 adoption review still awaiting a governed Governance reviewer

next_actor:
  recommended_role:  # po/arb (confirm work item + appoint implementation session) then governance
                     #   (REGISTER → HANDOFF → human START for the implementation lane)
  reason:            # the implementation session must be a fresh, appointed, lanned process; only the
                     #   PO/ARB holds appointment authority; the recorder cannot manufacture a lane
                     #   (P-3; G-3; EP-01 — this is a new, non-trivial engineering work item)
  blocking_condition: # no confirmed work-item number; no appointed implementation process; no
                     #   role=implementation lane on the work item

authorization:
  current_session_can_continue:   # false (AST-017 for this process: UNRESOLVED; capability=recording only)
  authorized_to_act:              # false — this process (b51dba91, correction author) does not implement,
                                  #   appoint, register, or adopt this capability
  requires_human_decision:        # true — PO/ARB: confirm work-item name + appoint the implementation
                                  #   session; Governance: REGISTER → HANDOFF → record human START
```

---

**Traceability:** PO/ARB in-session framing + commission 2026-08-22 · design principle (PO/ARB quote) · commissioned prompt `…-KOS-NEXT-ACTOR-ORCHESTRATION-001-implementation-prompt.md` (verbatim) · independent re-verification (`…-CORRECTION-001-INDEPENDENT-RE-VERIFICATION.md`, verifier `8deac5de`) · Governance adoption review START GATE REFUSAL (`…-GOVERNANCE-ADOPTION-REVIEW-START-GATE-REFUSAL-b51dba91.md`) · `AST-015` · `AST-017` · `G-3` · `P-3` · `EP-01` · `EP-02`/`R-34` · `INV-ATTR-1/2` · `ES-004.3` · `F1` · placement: `scripts/doc-placement.php` (product-specific · knowledgeos → `docs/knowledgeos`, exit 0)

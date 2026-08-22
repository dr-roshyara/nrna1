# `KOS-AIP-GOV-STATE-DURABILITY-ADR` — Session Completion & Handoff Protocol — **Operational Handoff Test** (controlled, synthetic)

**Work item:** `KOS-AIP-GOV-STATE-DURABILITY-ADR` (operational improvement) · **Test of:** `docs/knowledgeos/governance/SESSION-COMPLETION-HANDOFF-PROTOCOL.md` **v1.1** (ADOPTED OPERATIONAL PRACTICE)
**Test date:** 2026-08-22 · **Test conductor:** this session — **self-declared, not attestable** (`INV-ATTR-1`/`INV-ATTR-2`)
**Test type:** controlled, synthetic — a **small artificial session completion** exercising the standardized Session Completion Template.

> ## ⛔ What this record is / is not
> This is **operational evidence**, not governance acceptance. The protocol passed the governance loop (adopted · amended F1/F3 · independently reviewed CONFORMANT · integrated into agent templates). This test answers the one remaining question: **does the mechanism produce a report the human can read without reverse-engineering?** Whether it solves the original failure mode is confirmed by the **human PO/ARB** — this record does not self-attest. It creates no authority, assigns no ownership, and changes no workflow rule.

---

## 1 · Test scenario (synthetic — no migration state touched)

A session finishes a bounded task in the chain that exposed the friction:

```
Task:                DV correction authored and delivered
State:               DONE (slice closed)
Open item:           Independent architecture review required
Current session:     cannot continue — responsibility ended
Next required role:  Architecture Reviewer
Human decision:      YES
```

The session is the **author** of a correction; the next act is an **independent review of that correction** — a producer bar applies (§3 rule 2, §4: an author cannot independently verify its own correction).

---

## 2 · Session Completion Report produced (standardized template)

The session produces the report exactly in the standardized form now carried by `.claude/CLAUDE.md`, `AGENTS.md`, and `.codex/` (all pointers to this canonical protocol):

```yaml
session_completion:

  status:
    COMPLETED                     # slice closed (EKS-04 COMPLETE); the work item's
                                  # remaining obligations are carried by open_items

  completed_work:
    - DV correction authored and delivered

  evidence:
    - commit                        # the correction commit
    - document                      # the correction artifact, by path
    - test                          # the verification that ran against it

  open_items:
    - independent architecture review of the correction

  next_actor:
    recommended_role:
      Architecture Reviewer

    reason:
      Independent review required — the author cannot independently
      verify its own correction

    blocking_condition:
      Reviewer lane must be authorized — START requires a recorded human act
      AND the predecessor's recorded handoff (G-3)

  authorization:

    current_session_can_continue:
      NO                            # capability statement: responsibility ended

    authorized_to_act:
      NO                            # workflow has not granted the reviewer lane

    requires_human_decision:
      YES                           # human act needed before the next actor may START
```

### Field-by-field verification against the protocol

| Field | Value | Protocol rule satisfied |
|---|---|---|
| `status` | `COMPLETED` | consumed state vocabulary (`CREATED · ACTIVE · HANDED_OFF · COMPLETED · …`) — the session is formally closed, not merely handed off (§2, EKS-04) |
| `completed_work` / `evidence` | correction + its artifacts | summary of the responsibility, not a justification (§2) |
| `open_items` | independent review | remaining obligations are carried forward — the *work item* is not done because it was never accepted (§2) |
| `next_actor.recommended_role` | **Architecture Reviewer** | declared role vocabulary; a producing process is never its own next actor where the next act is review of its own output (§3 rule 2) |
| `next_actor.reason` | independent review required | the **rule**, not a preference (§3) |
| `next_actor.blocking_condition` | reviewer lane must be authorized (`G-3`) | the report names the gate; it does **not** pass it (§6 — the engine decides *who is allowed to act*) |
| `authorization.current_session_can_continue` | `NO` | **F1** — a capability statement; it grants nothing, transfers nothing, starts nothing |
| `authorization.authorized_to_act` | `NO` | authorization comes from workflow state + Governance + `humanAct` — none granted (§5) |
| `authorization.requires_human_decision` | `YES` | a human act is needed before the next actor may act (§5) |

**F1 discipline confirmed:** `current_session_can_continue: NO` is the session's self-answered capability statement. It does **not** mean ownership granted · authority transferred · workflow started · human decision completed · artifact accepted. Authorization stays with the workflow, Governance, `humanAct`, and ownership rules — none of which this report touches.

---

## 3 · Expected human-visible result

The human receives the report and immediately sees — **no investigation required**:

```
Current session:   FINISHED
Can continue:      NO
Next actor:        Architecture Reviewer
Why:               Independent review required
Can AI automatically continue:   NO
Human decision:    YES
```

---

## 4 · Verification against the original failure mode

| | Before the protocol | After the protocol |
|---|---|---|
| Session finishes | "Work done" — nothing more | Session Completion Report |
| Who owns the next action? | human investigates | `next_actor.recommended_role: Architecture Reviewer` |
| Who can act? | human investigates | `authorized_to_act: NO` — workflow/G-3 gate named |
| Who authorizes? | human investigates | `requires_human_decision: YES` — the single human act is **approval, not discovery** |

**Result:** the mechanism produces a report whose recommendation is deterministic (rule-driven, not preference), whose authorization claims are `NO` (nothing manufactured), and whose human-required flag is explicit. The human's remaining work is to **approve**, not to **reconstruct**. ✅ *Mechanism demonstrated on a controlled scenario.* Confirmation that this solves the failure mode: **human PO/ARB**.

---

## 5 · Non-decisions

⛔ No migration phase executed · no workflow state changed · no `humanAct` created · no authority transferred · no EKS-07 opened · no DV correction modified · no second completion discipline created (`ES-005.4`) · the work item `KOS-AIP-GOV-STATE-DURABILITY` remains exactly where it was before this test.

---

## 6 · Next steps (after human confirmation of the test)

1. **Governance bounded review** — of the DV-correction chain (⏳)
2. **PO/ARB migration authorization** (⏳)
3. **Phase 3** (⏳) → **Phase 5** execution (⏳)

---

**Traceability:** the work item `KOS-AIP-GOV-STATE-DURABILITY-ADR` (operational improvement) · protocol `SESSION-COMPLETION-HANDOFF-PROTOCOL.md` v1.1 @ `9cca06d8` (ADOPTED OPERATIONAL PRACTICE) · adoption registration @ `14ebd6ec` · independent v1.1 review CONFORMANT @ `c209f68c` · agent templates @ `42791bf4` (`.claude/CLAUDE.md` · `AGENTS.md` · `.codex/README.md`) · `R-34`/`P-2` (producer bar) · `G-3` (START gate) · `Inv E` (CONTINUATION gate) · F1 (capability ≠ authorization) · F3 (complements End-of-Commission checklist) · `INV-ATTR-1`/`INV-ATTR-2` (self-declared identity) · `ES-005.4` (never a second) · placement: `scripts/doc-placement.php` (product-specific · knowledgeos → `docs/knowledgeos`, exit 0); `reviews/` per the review README convention

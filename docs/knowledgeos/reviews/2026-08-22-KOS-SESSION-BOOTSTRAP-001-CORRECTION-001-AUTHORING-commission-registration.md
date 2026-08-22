# `KOS-SESSION-BOOTSTRAP-001-CORRECTION-001` — AUTHORING commission · PO/ARB act **REGISTERED**

**Registered by:** Governance — `claude-code-session:b51dba91` *(self-declared; identity resolved mechanically and disclosed — this session's own bootstrap resolves `UNRESOLVED`; the PO/ARB's explicit direction is the authority for this act)*
**Act:** PO/ARB correction-authoring commission 2026-08-22 · **Governance records; it decides nothing here.**

---

## 1 · The act, as delivered

**PO/ARB authorizes a bounded Architecture correction** of `V-1` / `V-3` / `V-5` on work item `KOS-SESSION-BOOTSTRAP-001` (`AST-017` · `CMP-004` / workflow_engine).

**It does NOT constitute** (recorded verbatim): `AST-017` adoption · governance acceptance · `EKS-07` completion · migration authorization · workflow automation authorization.

## 2 · Governed state acknowledged by the act

```
AST-017 implementation          IMPLEMENTED
Independent technical verification   RETURN FOR CORRECTION
V-8                             RESOLVED
Workflow record                 CREATED (OPEN · transitions=0 · grants=0 · mutationOwner=null)
Findings commissioned           V-1 · V-3 · V-5
Not commissioned                V-2 · V-4 · V-6
Observation                     V-7
V-8                             RESOLVED — workflow registration requirement satisfied
```

## 3 · ⚠ The appointment — placeholder, not yet resolved

**Verbatim:**
> **Fresh Architecture process: `claude-code-session:<ACTOR-ID>`**

**Eligibility (verbatim):** MUST NOT be the AST-017 producer (`claude-code-session:8a525719`) · MUST NOT be the independent technical verifier (`claude-code-session:d1612e03`, or the verifier identity recorded in the verification artifact) · MUST NOT be Governance (`claude-code-session:b64828fe`) · MUST be independent of the V-1/V-3/V-5 review and acceptance chain.

> *"The actual process identity MUST be declared by the appointed actor and verified through the existing governance mechanism. No process may adopt another process's identity."*

**Recorded, not decided:** the concrete actor identity is a **placeholder (`<ACTOR-ID>`)** — Governance does **not** fill it, adopt a process's identity, fabricate one, or self-appoint. The commission names the *role* and *eligibility*, and requires the identity to be **declared by the appointed actor** and verified. **Until that resolution, `REGISTER` cannot lawfully run** (a REGISTER transition carries the concrete `session` id + `executionContext`).

## 4 · Commissioned findings — defect · required outcome · required regression

| Finding | Defect (verbatim) | Required outcome + regression (verbatim) |
|---|---|---|
| **V-1 FALSE START CLAIM** | `recorded_human_start_act` inferred from `workflow_state != CREATED` — false for a terminal lane reaching `CANCELLED` from `CREATED` without a START | bootstrap MUST distinguish **actual recorded human START** from **merely leaving CREATED**; MUST NOT claim a START unless the authoritative record supports it; regression: a lane created-and-cancelled-without-START MUST report `recorded_human_start_act = false` and stay `authorized_to_act = false`. **Do NOT weaken G-3.** |
| **V-3 AMBIGUITY MESSAGE** | `meta.disambiguation_required` lists all discovered candidates, not the ones that actually matched the process selector | when multiple matching candidates: `selected = null` · `verdict = AMBIGUOUS` · `authorized_to_act = false` · `disambiguation_required` identifies ONLY matching candidates · fail-closed unchanged. **CRITICAL:** `a8ce5a39 → AMBIGUOUS` MUST remain AMBIGUOUS — do NOT "fix" the live ambiguity by choosing a lane; the correction is ONLY about making the explanation truthful. |
| **V-5 BOOTSTRAP FIELD CONTRACT** | harness references `bootstrapping_status`; AST-017 emits `activation_prerequisites` | exactly ONE canonical field name; update implementation · tests · `.claude/CLAUDE.md` · `AGENTS.md` · `.codex/README.md` · canonical boundary doc to agree; no duplicate aliases unless justified + governed; add regression that the documented field exists with the promised meaning |

## 5 · Preservation conditions (must all survive — verbatim summary)

1. `AST-015` remains the single authority for workflow interpretation.
2. `AST-017` remains read-only.
3. The V-3 raw-read exception remains exactly one bounded HANDOFF fact.
4. No second fold or workflow engine.
5. Provider independence (Claude and DeepSeek consume the same authoritative state → equivalent resolution).
6. Ambiguity MUST remain fail-closed.
7. Identity remains evidence-only, never authority.
8. Human START remains a human authority boundary.
9. No automatic REGISTER/HANDOFF/START.

## 6 · Non-commissioned items (do NOT fix/redesign/implement)

`V-2` · `V-4` · `V-6` (unless a separate PO/ARB act) · `V-7` beyond observation · V-8 must NOT be re-opened by the correction · V-3 FULL remedy in AST-015 · `SESSION_START` wiring · handoff automation · process identity attestation · `EKS-07` architecture · new bounded context · new authority model.

## 7 · TDD / verification requirements (per commissioned finding)

RED regression reproducing the defect → smallest correction → GREEN → re-run AST-015/AST-016 suites → re-run complete AST-017 suite → provider conformance → read-purity → live ambiguity behavior → verify no workflow-state mutation. **Do NOT claim closure because text/code changed — each finding requires evidence its property now holds.**

## 8 · Deliverables

Corrected AST-017 implementation · regression tests for V-1/V-3/V-5 · updated developer documentation · updated canonical bootstrap contract · independent-testable evidence (V-1 · V-3 · V-5 corrections · preserved provider independence · preserved fail-closed ambiguity · preserved V-3 boundary) · **Session Completion Report**.

## 9 · Status rule

The author MUST **not** self-close V-1/V-3/V-5. The author reports **`CORRECTED / READY FOR INDEPENDENT RE-VERIFICATION`** — never ACCEPTED/ADOPTED/CLOSED.

## 10 · Workflow sequence

`PO/ARB act (this)` → **Governance REGISTER appointed Architecture lane** → HANDOFF → **Human START** → Architecture correction → **STOP** → fresh independent verifier → technical re-verification → Governance adoption review → PO/ARB adoption decision.

## 11 · Boundaries

**Migration:** ZERO authority — no migration plan change · no Phase 3 · no Phase 5 · no migration authorization · no gate closing · no `KOS-AIP-GOV-STATE-DURABILITY` state change.

**EKS-07:** `KOS-SESSION-BOOTSTRAP-001` remains a MINIMAL OPERATIONAL CORRECTION; `EKS-07` remains FUTURE ARCHITECTURE EXPLORATION; any deeper coordination issue discovered = FOLLOW-UP, not implemented here.

## 12 · Final PO/ARB decision (verbatim)

**APPROVED:** V-1 correction · V-3 correction · V-5 correction.
**NOT APPROVED:** V-2 · V-4 · V-6 · V-7 beyond observation · V-3 full AST-015 remedy · `SESSION_START` wiring · handoff automation · `EKS-07` implementation · `AST-017` adoption · migration authorization.

**STOP.** After authoring the correction: do not self-review · do not self-accept · do not register adoption. Produce the completion report. **`NEXT ACTOR = FRESH INDEPENDENT VERIFIER`.**

---

## ⚠️ Governance note (recorded — not a decision)

**The REGISTER step (§10) is blocked pending actor-identity resolution.** The commission's appointment is a placeholder (`claude-code-session:<ACTOR-ID>`) and explicitly requires the identity to be *declared by the appointed actor and verified through the governance mechanism*. Governance has **not** REGISTERED a lane, adopted an identity, or fabricated one — doing so would violate the commission's own rule ("No process may adopt another process's identity") and `INV-ATTR-1`/`INV-ATTR-2` (identity is evidence-only). The registration of this act is complete; the lane registration awaits the concrete actor declaration + verification.

## Not decided by this registration

⛔ the concrete actor identity · V-2/V-4/V-6 · V-7 disposition · `AST-017` adoption · migration · `EKS-07`. **No REGISTER · no HANDOFF · no START · nothing corrected, verified or accepted.**

**Next actor:** the **appointed actor's process** (to declare its concrete identity per §3) **or PO/ARB** (to resolve the placeholder) — after which Governance performs the `REGISTER` (§10).

**Traceability:** PO/ARB correction-authoring commission 2026-08-22 (verbatim) · `CORRECTION-001` commission registration `2026-08-22-KOS-SESSION-BOOTSTRAP-001-CORRECTION-001-commission-registration.md` · V-8 determination registration `2026-08-22-KOS-SESSION-BOOTSTRAP-001-V8-DETERMINATION-registration.md` · independent verification artifact (verifier `d1612e03`, untracked) · workflow record `KOS-SESSION-BOOTSTRAP-001` (OPEN · 0/0/null) · `G-3` · `Inv A` · `R8` · `INV-ATTR-1`/`INV-ATTR-2` · `ES-005.4` · `EKS-07`

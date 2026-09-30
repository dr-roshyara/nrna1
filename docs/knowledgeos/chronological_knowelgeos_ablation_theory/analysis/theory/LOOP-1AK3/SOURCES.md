# Source passages (verbatim). Code ONLY from these.

## P1 — session log 2026-07-26, lines 425-440

Fresh session, Auto mode, WP-1: read ADR-T22 + roadmap §WP-1 + current v2 implementation → four keystone RED tests → confirm RED for the expected reasons → GREEN → gates → STOP for ARB slice acceptance. No governance reopened without fresh evidence.

---

## FINAL HANDOFF CONFIRMATION (chair) — chapter closed for its intended purpose; one candidate parked

- **Chair's external-reviewer verdict:** engineering-platform chapter CLOSED for its intended purpose — the platform credibly governs implementation; remaining EG work isolated from product delivery; further contract polish would not produce proportionate value vs. starting WP-1. WP-1 contract validated as three reinforcing layers (purpose · execution policy · safety rails) with observable, automation-suitable STOP conditions.
- **Parked candidate (evidence-before-promotion, same family as the slice-ledger candidate):** an **Implementation Execution Contract** — reusable doc (implementation philosophy · Auto/Plan policy · STOP conditions · scope discipline · ARB escalation) that future WPs reference instead of repeating. **Extract only after WP-1 + a few more slices demonstrate the shape is stable** — not now. Revisit alongside the slice-ledger candidate (~WP-3/WP-4).
- Push of the final commits = user's action (passphrase-protected key).

## Session close — the program's standing position
**Push → fresh session → SessionStart injects the WP-1 contract → ADR-T22 + roadmap §WP-1 + v2 implementation → four keystone RED tests → confirm RED → GREEN → gates → STOP for ARB slice acceptance.**

---

## WP-1 OPENED (chair: "continue" — Auto mode, per the recorded contract) — RED WRITTEN + CONFIRMED, STOP

## P2 — session log 2026-07-30, lines 193-204

## DDD-Driven Architectural Implementation Protocol received (17 phases) — ADOPTED AS THE OPERATING STANDARD for implementation work; NOT self-promoted into a standards document

**Received from the PA as the going-forward protocol.** In force from the next work package. Its ordering is binding: *Business Model → Strategic Architecture → Architectural Decisions → Approved Design → Implementation → Repository State → Operational Evidence* — never reversed.

**Confirmed already-demonstrated in WP-1/WP-2** (so this formalizes practice rather than introducing untested process): Commission Reset (Ph 1) · Authority Register (Ph 2) · Business Understanding (Ph 3) · Strategic DDD (Ph 4) · Business Model Fidelity (Ph 5) · Traceability (Ph 6) · Simplification (Ph 7) · Business Assumption Review (Ph 8) · RED-first with business-behaviour tests (Ph 10) · minimal GREEN (Ph 11) · Architectural Verification (Ph 13) · Static analysis as design feedback (Ph 14 — the `whereNotIn` type-erasure fix was exactly this) · **Failure Analysis (Ph 15)** — applied when the state guards rejected my own test: the five-way diagnosis returned "incorrect test", so the test changed and the code did not · Governance Discipline (Ph 16) · Completion Review (Ph 17).

**Governing principle carried at the top of the protocol** (originating in this slice's mapper self-correction, now generalised by the PA): *a good question does not become a decision by being well argued* → **evidence + reasoning is not authority; a decision is an explicit act by an authority.** Applied twice in WP-2 — the mapper (roadmap won over my simplification argument) and F-T1 (PM-1's wording won over my inference).

**GOVERNANCE FLAG — one decision belongs to the DA, not to me.** This protocol is, in substance, the **"Implementation Execution Contract"** candidate that the PA parked on 2026-07-26 with an explicit promotion criterion: *extract only after WP-1 plus a few more slices demonstrate the shape is stable; revisit ~WP-3/WP-4* (recorded with the slice-ledger candidate). Evidence today = **two** slices (WP-1, WP-2). Writing it into a governed standards document now would be exactly what the protocol's own **Phase 16** forbids — *promoting observations into standards* without authorization — so I have **not** created one. Options for the DA: **(a)** adopt now as an explicit early promotion (R-39 is the precedent for DA-authorized early promotion, recorded as an exception so the normal bar stays intact); **(b)** hold to the parked criterion and let WP-3 supply the third instance, following the protocol meanwhile without a standards doc. **Recommendation: (b)** — the protocol is followed either way, and one more slice makes the promotion evidence-backed rather than assertion-backed.

*(Practical note: whichever is chosen, the protocol must reach the next session through the runtime path — the WP-3 work plan will carry it, as the WP-1 plan carried the execution contract.)*


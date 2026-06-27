# Plan — Verify the "32 contexts / 9 strategic domains" claims, then write an accurate Architecture Landscape

## Context
Two pasted analyses make **contradictory** claims about the strategic structure: (A) "~32 bounded contexts discovered"; (B) "final outcome = 9 strategic domains each containing bounded contexts." The user asked to **verify against `./docs/`** before documenting. I verified against the frozen artifacts. **Both claims are inaccurate.** This plan records the verified truth and proposes one accurate landscape document — it does **not** adopt either narrative.

## Verification findings (evidence-grounded)
- **"32 bounded contexts" — FALSE.** Grep of `docs/architecture/design` shows every `32` is **"Round 32"** (Round 32 Design Governance Charter; "Rounds 17–32"), never "32 contexts." No 32-context discovery exists.
- **"9 strategic domains (2-tier hierarchy)" — UNSUPPORTED.** No "9 strategic domains" / "Domains/" hierarchy in the frozen docs. The "9" is the **v0 candidate-context hypothesis** that Round 47-01 *revalidated* — not a certified 9-domain structure.
- **Actual frozen structure (authoritative):**
  - **Certified Strategic Domain Landscape v1.0** — `Round47-02_Strategic_Domain_Landscape_Certification.md` (FROZEN): 15 decisions = **8 bounded-context candidates** (6 owning: Evidence&Replay · Audit · Adjudication · Authorization · Contestation · Appointment; + 2 F-PROC substrate: Voting · Election Lifecycle) + **2 Read Models** (Results, Legitimacy) + **Anonymity** invariant + **Transparency** (held) + **Trust-Anchor/Consent** (external) + **2 Deferred** (Eligibility, Identity-Trust). It is a **flat landscape**, not a domain→context tree.
  - **BDR v1.1** — `Round49-06_Boundary_Decision_Register.md` (IMMUTABLE): of **8 owning candidates → 5 Confirmed BCs** — **3 Operational** (Evidence, Voting, Appointment) + **1 Greenfield** (Contestation) + **1 Greenfield/Unrealized** (Adjudication); **3 downgraded** (Authorization→Supporting Service, Lifecycle→Supporting Subdomain, Audit→Infrastructure; Replay→Application Capability) + 2 Read Models + Anonymity invariant + external.
- **Greenfield Core** = the **2 greenfield BCs** (Contestation + Adjudication) — the trustworthiness differentiator (binding-finality correction loop). Reference implementations: Challenge aggregate + Determination aggregate + AdjudicationService (Push A).
- **Third stratum — `architecture/strategic/` (Round 6, 2026-06-13, EARLIER lens):** `ADR-004` + `BOUNDED_CONTEXT_ASSESSMENT` assessed **5 candidate contexts** — Election Governance (Confirmed, Very High), Voting (Candidate), Organisation (Candidate), Membership (Candidate), Trustworthiness (Unresolved). Rejected: "Trust is a peer BC", "Election is a God Object". Again **5**, not 32, not 9.
- **Existing codebase** organizes as ~8 `app/Contexts/*` (Committee, Elections, Finance, Geography, Governance, Membership, Shared, Trust) + greenfield Adjudication, Contestation. (A *flat* context set — no `Domains/` tier.)
- **Evolution (the honest arc):** Round 6 general lens (**5** candidates) → EBSD v0 hypothesis (~9 candidates, Round 47-01) → **Certified Landscape v1.0 (8** BC candidates, Round 47-02**)** → **BDR v1.1 (5 Confirmed BCs**, Round 49-06**)**. Two *lenses* (general system contexts vs trustworthiness/correction-loop contexts) overlap on Voting; neither is a 32-context set or a 2-tier 9-domain hierarchy.

## Deliverable (one document — the AKB "map of the system")
`docs/architecture/Architecture_Landscape_and_Implementation_Status_v1.0.md` (Level-2 AKB, linked from the Handbook):
- **Correction note** up front (evidence-cited): there were never 32 implementation contexts ("32" = Round 32); there is no certified 9-strategic-domain hierarchy (the "9" = the v0 candidate-context *hypothesis*); the structure is a **flat** set. Cites `architecture/strategic/ADR-004` (Round 6: 5), Round 47-02 (Landscape v1.0: 8 candidates), Round 49-06 (BDR v1.1: 5 Confirmed BCs).
- **Evolution arc** (Round 6 → v0 → Landscape v1.0 → BDR v1.1) and the **two lenses** (general codebase `app/Contexts/*` vs trustworthiness/correction-loop EBSD landscape), noted honestly.
- **The landscape table** (verbatim from the frozen artifacts): 8 candidates + their BDR verdict + classification (Operational / Greenfield / Service / Supporting / Infra / Read Model / Invariant / External).
- **Implementation status** per confirmed BC: Evidence/Voting/Appointment = Operational (legacy; strangler-migrate per `Round49-07`); Contestation/Adjudication = Greenfield reference implementation (Push A done; Push B next); non-module items (Authorization/Lifecycle/Audit/Replay/Results/Legitimacy) noted as such.
- **Reference-implementation note**: the greenfield Core is the proven pattern (Architecture Baseline 1.1) future contexts copy.
- Explicit statement: this document **adds no contexts** (that needs a new Landscape version); it only reports verified status.

Optional: one line in the Handbook (Part VIII Strategic DDD) pointing to this landscape map.

## Verification (of the doc)
Cross-check every count/name against `Round47-02` (8 candidates / 15 decisions) and `Round49-06` (5 Confirmed BCs, 3 downgraded). No number appears that isn't in those two frozen docs. No "32" and no "9 strategic domains" framing is propagated.

## Files
New: `docs/architecture/Architecture_Landscape_and_Implementation_Status_v1.0.md`. Edit (optional, 1 line): the Handbook Part VIII pointer. No code.

# Round 49-03 — AI-1 Code-Home Consolidation Decision

**Program:** NRNA DDD Trustworthiness Research Program · **Phase II** · **Under `Round47-OP` operating protocol + SD-1..7**
**Status:** 🧭 ARCHITECTURE DECISION (plan/recommendation — **no file moves executed**; migration awaits authorization)
**Date:** 2026-06-26 · **Built against:** Package 1.0.0 / Vocabulary 1.0.0 / Ontology 1.0.0 / Landscape v1.0

*(Produced in the protocol's 14-point output format.)*

**1. Purpose** — Resolve AI-1 (gap analysis `Round49-02`, Drift): Core/governance concepts are implemented across **three+ homes** — `app/Domain/Election`, `app/Application/Election`, `app/Contexts/{Governance,Membership,Committee}`. Decide the **authoritative code home** so the Contestation prototype lands correctly and conformance can be claimed against *one* codebase.

**2. Inputs** — `Round49-02` (reflexion findings), Strategic Domain Landscape v1.0, SD-7 (traceability), Knowledge Release Governance (Traceability Version Matrix). Existing code = empirical evidence (not authoritative).

**3. Certified concepts consumed** — none redefined. This is a *code-organization* decision realizing the certified **contexts** (Adjudication, Evidence&Replay, Contestation, Appointment, Authorization, Voting, Lifecycle); it consumes the landscape's context list only.

**4. Architecture decision** — **Adopt `app/Contexts/<Context>/{Domain,Application,Infrastructure}` as the single authoritative home for all certified-landscape contexts.** `app/Domain/Election` + `app/Application/Election` become **legacy (deprecated-in-place)**, migrated incrementally; no big-bang move. Each certified context = exactly one `app/Contexts/*` module (SD-7 one-home rule). The Contestation prototype is created **greenfield** at `app/Contexts/Contestation/`.

**5. Decision rationale (WHY, certified-evidence-based)** — (a) `app/Contexts/*` is the only home whose structure *matches the certified landscape's bounded-context decomposition* (Governance, Membership/Committee already host the richest Core: arbitration+replay+legitimacy). (b) SD-7 requires per-artifact traceability to one context; multiple homes make "the code" ambiguous (Q8 concern in `Round49-01`). (c) The landscape (not the folders) drives the home: one certified context → one module. Not "because it seems reasonable" — because SD-7 + the landscape demand a single, context-aligned home.

**6. Traceability** — Landscape v1.0 (8 contexts) → each maps to `app/Contexts/<Context>`; `Round49-02` Drift finding → this consolidation; SD-7 → one-home rule; KRG Traceability Matrix → every module declares Package/Vocab/Ontology/Landscape version.

**7. Alternatives considered** — (A) **Keep `app/Domain/Election` authoritative**, migrate `app/Contexts/*` into it — *rejected:* `app/Domain/Election` is a single bag, not context-aligned; contradicts the landscape. (B) **Hybrid / leave both** — *rejected:* perpetuates AI-1 ambiguity; violates SD-7 single-home. (C) **`app/Contexts/*` authoritative** — *chosen.* (D) Fresh top-level (`app/Governance/*`) — *rejected:* needless churn; `app/Contexts/*` already exists and is populated.

**8. Constraint verification (5 carried)** — (1) federate Independence by facet: unaffected (organization only). (2) Anonymity supreme: preserved — Voting/Evidence modules keep the no-`user_id`/hashed-id invariant; migration must not introduce linkage (verify per move). (3) one Legitimacy projection: preserved (`LegitimacyOutcome` single resolver stays single). (4) software doesn't own enforcement: unaffected. (5) **design-from-ownership, no retrofit:** *this decision IS the anti-retrofit step* — it reorganizes code to the ownership seams rather than bending the model to the folders.

**9. Forbidden-Transformation verification** — none introduced. No concept persisted/aggregated that must not be (Legitimacy stays derived; Trust-Anchor stays external; no bare "Independence"; no Blocked concept created). Pure relocation.

**10. Risks** — (R1) migration regression risk in live election code → mitigate with **strangler/incremental** moves + tests, never big-bang. (R2) anonymity regression during a move (R-grade verify each step). (R3) effort/coordination cost. (R4) two homes coexist *during* migration → temporary, tracked. (R5) hidden coupling between `app/Domain/Election` and controllers — must map before moving.

**11. Open questions** — Is `app/Application/Election` (application services) folded under each context's `Application/` or kept as a cross-context application layer? Does `Membership` vs `Governance` vs a new `Adjudication` context own the arbitration kernel currently in `Membership/Committee/Constitutional`? (boundary-confirmation, per DDD Discovery Discipline — candidate, not settled.)

**12. Governance implications** — **None.** AI-1 is **software-side technical debt / refactoring**, not governance (SD-6): no ontology/vocabulary/admissibility change. No Governance Change Request needed. (Confirms `Round49-02` classification.)

**13. Implementation implications** — Migration is **incremental (strangler)**: (i) new work (Contestation) goes to `app/Contexts/*` now; (ii) Core contexts (Adjudication, Evidence&Replay) consolidated next, behind tests; (iii) Supporting/Generic later; (iv) `app/Domain/Election` shrinks to empty, then removed. **No file moves in this artifact** — execution requires explicit authorization (live code).

**14. References** — `Round47-00` SD Constitution; `Round47-OP` protocol; `Round47-02` Landscape v1.0; `Round49-01`/`Round49-02`; KRG `Round46-KRG` (Traceability Matrix).

---

## Self-review (protocol gate)
☑ No governance concepts invented · ☑ Only certified vocabulary · ☑ No Forbidden Transformation · ☑ Ownership respected (one context→one home) · ☑ Admissibility respected · ☑ Traceability complete · ☑ SD Constitution obeyed · ☑ KRG respected. **Pass.**

**Decision:** `app/Contexts/*` authoritative; incremental strangler migration; Contestation prototype lands at `app/Contexts/Contestation/`. **Awaiting authorization to execute any code move.**

---

*Round 49-03 — AI-1 Code-Home Consolidation Decision — ISSUED (plan; no code moved).*
*Authoritative home = `app/Contexts/<Context>/{Domain,Application,Infrastructure}` (SD-7 one-home; matches Landscape v1.0). `app/Domain/Election`+`app/Application/Election` = legacy, strangler-migrated. Software-side debt — NO governance change. Risks: regression/anonymity (mitigate incremental+tests). Next: prototype Contestation greenfield in app/Contexts (gated on authorization).*

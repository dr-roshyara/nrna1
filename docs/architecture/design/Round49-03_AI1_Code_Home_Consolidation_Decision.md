# Round 49-03 — AI-1 Module Authority Decision (Migration Decision) v1.1

**Program:** NRNA DDD Trustworthiness Research Program · **Phase II** · **Under `Round47-OP` + SD-1..7**
**Status:** 🧭 ARCHITECTURE DECISION (the **Migration Decision** — first of Decision→Plan→Execution→Verification). **No file moves**; migration gated on the **BDR** (Round 49).
**Date:** 2026-06-26 · **Built against:** Package 1.0.0 / Vocabulary 1.0.0 / Ontology 1.0.0 / Landscape v1.0 · *(v1.0→v1.1: decoupled the principle from `app/Contexts`; gated on confirmed BCs + BDR; added migration principles, exit criteria, fitness tests; deferred the application-layer question.)*

*(14-point output format.)*

**1. Purpose** — Resolve AI-1 (multi-home drift: `app/Domain/Election` + `app/Application/Election` + `app/Contexts/{Governance,Membership,Committee}`). Establish the **module-authority principle** and a **safe migration approach** — *without* deciding which candidates are real contexts (that is the BDR's job).

**2. Inputs** — `Round49-02` v1.2 (conformance); `Round48A` v1.1 (BC evaluation framework); Landscape v1.0; SD-7. Existing code = empirical evidence.

**3. Certified concepts consumed** — none redefined; this is code organization realizing **confirmed** contexts.

**4. Architecture decision (principle — module-agnostic)**
> **Each *confirmed* bounded context shall have exactly one *authoritative implementation module*.**
**Current realization:** `app/Contexts/<Context>/{Domain,Application,Infrastructure}`. *The principle is the architecture; the folder is merely its present realization* — if the project later adopts `Modules/`, `src/`, or `packages/`, the principle is unchanged. (Renames SD-7's "one-home" → **one-confirmed-BC → one-authoritative-module**.)

**5. Decision rationale (WHY)** — SD-7 traceability requires each confirmed BC to map to exactly one module; multi-home makes "the code" ambiguous (`Round49-01` Q-concern). The landscape (not the folders) drives module authority. The current `app/Contexts/*` realization is chosen because it is already the most context-aligned home — but the *commitment* is to the principle, not the path.

**6. Traceability (the chain this enables)**
`Certified Concept → Candidate BC → Confirmed BC (BDR) → Authoritative Module → Namespace → Aggregate → Entity → Table → Tests.`
AI-1 **implements the Boundary Decision Register**: a module is created/consolidated **only** for a BC the BDR marks **Confirmed** (or Supporting Subdomain). Merged/Capability/Rejected candidates get **no standalone module**.

**7. Alternatives considered** — (A) keep `app/Domain/Election` authoritative — *rejected:* not context-aligned. (B) hybrid/leave both — *rejected:* perpetuates AI-1. (C) **principle + current realization `app/Contexts/*`** — *chosen.* (D) hardcode `app/Contexts` as *the* architecture — *rejected:* couples architecture to a folder (this review's correction).

**8. Constraint verification (5 carried)** — (1) federate Independence: unaffected. (2) Anonymity: preserved (migration must not introduce linkage — fitness test §13). (3) one Legitimacy projection: preserved. (4) no software enforcement ownership: unaffected. (5) **design-from-ownership, no retrofit:** this *is* the anti-retrofit step.

**9. Forbidden-Transformation verification** — none introduced (pure relocation; no concept persisted/aggregated that must not be).

**10. Risks** — regression in live election code (→ strangler + tests); anonymity regression during a move; hidden coupling Domain↔controllers; migrating a candidate that the BDR later merges (→ **migrate only Confirmed BCs**).

**11. Open questions** — *(deferred, not left open-ended):* whether `Application/` is per-context or cross-context → **Deferred to Round 50 (Tactical DDD)**. Which candidates get modules → **the BDR decides** (Round 49).

**12. Governance implications** — **none** (software-side debt; SD-6). No Governance Change Request.

**13. Implementation implications — sequence, principles, exit, fitness**

*Sequence (binding):* `Round 49 Evaluation → BDR → Migration Plan → Migration Execution → Migration Verification → Architecture Fitness Tests`. **Never migrate before the BDR.**

*Migration principles (the migration constitution):* (1) no behavior changes · (2) no semantic changes · (3) no public-API changes · (4) tests before moves · (5) incremental (strangler) · (6) rollback possible · (7) one context at a time.

*Exit criteria — migration complete **iff**:* every **Confirmed** BC has exactly one authoritative module **and** the legacy implementation is removed.

*Architecture fitness tests (prevent re-drift):* enforce in CI (Deptrac / PHPStan / ArchUnit-style) — e.g. **"exactly one module may own Evidence,"** "no `app/Domain/Election2`," "no cross-context write to another context's system-of-record," "no voter↔vote linkage." Without these, the architecture degrades again.

**14. References** — `Round47-00`/`47-OP`; `Round47-02` Landscape; `Round48A` v1.1; `Round49-01`/`49-02` v1.2; KRG `Round46-KRG`.

---

## Self-review (protocol gate)
☑ principle decoupled from folder · ☑ gated on Confirmed BCs + BDR (not candidates) · ☑ migrate only after BDR · ☑ migration principles + exit criteria + fitness tests · ☑ application-layer deferred (not open) · ☑ no governance change · ☑ no code moved. **Pass.**

**Decision:** *one confirmed BC → one authoritative module* (current realization `app/Contexts/*`); strangler migration **after** the BDR; principles + exit criteria + fitness tests defined. **No code moved; awaiting BDR then authorization.**

---

*Round 49-03 — AI-1 Module Authority (Migration Decision) v1.1 — ISSUED (plan; no code moved).*
*PRINCIPLE (module-agnostic): one CONFIRMED BC → one authoritative module; `app/Contexts/*` = current realization, NOT the principle. Migration GATED on the BDR (migrate only Confirmed BCs). This = the Migration DECISION (Plan→Execution→Verification follow). Migration constitution (7 principles) + exit criteria + CI fitness tests. Application-layer split DEFERRED to Round 50. Software-side debt, NOT governance.*

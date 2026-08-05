# ADR-AIP-01 — AI Engineering Platform Baseline v1.0

| | |
|---|---|
| **Status** | **Accepted** (Chief ARB, 2026-07-08) |
| **Series** | ADR-AIP (AI Engineering Platform) — new series, precedent: ADR-MP (Messaging Platform) |
| **Decision authority** | Architecture Review Board (Chief ARB resolution, 2026-07-08; session record: `.claude/sessions/2026-07-08.md`) |
| **Drafted by** | AI platform (authority: generated); accepted by human decision — this document records that Human Decision Event |
| **Supersedes** | None |
| **Related** | ER-05 (Convergence) · PGP-01..05 · D-11 (verification ≠ certification) · AIP-01..13 |

---

## Context

Between 2026-07-07 and 2026-07-08 the ARB conducted a strategic discovery and domain-modeling program for an AI Engineering Platform to govern AI-assisted development of PublicDigit. The program produced, under `docs/architecture/proposals/ai-platform/`:

1. `Phase-01-Discovery.md` — discovery of the external claude-flow framework; **rejected as migration target** (reference only).
2. `Phase-02-Domain-Model.md` — strategic DDD model: mission ("evidence, never authority"), six bounded contexts + two external domains, context map, aggregates, events, governance model, AI responsibility matrix, platform fitness functions FF-1..13.
3. `Phase-02.5-Certification-Plan.md` — capability model CAP-01..13, principles AIP-01..13, capability lifecycle, FF-14..16, the platform's future D-11-style certification plan, and the ARB rulings register R-1..R-14.
4. `Phase-02.6-Ubiquitous-Language.md` — the platform's dictionary (canonical terms, naming conventions, reserved and forbidden vocabulary).
5. `Phase-02.7-Platform-Decisions.md` — the platform constitution: binary authority decisions PD-01..20, conflict rule, enforcement mapping.
6. `Phase-03A-Reference-Architecture.md` — how the domain model becomes software: seven components, runtime moments, configuration, persistence, extension model, provider binding, Phase 3B acceptance criteria and validation plan.

The architecture journey (Discovery → Capability Discovery → Domain Model → Capability Model → Vocabulary → Constitution → Reference Architecture) is complete; no conceptual piece is missing.

## Decision (ARB Resolution — AI Engineering Platform Baseline v1.0)

> The strategic architecture defined in Phases 01 through 03A is accepted as the **Baseline v1.0** of the PublicDigit AI Engineering Platform. Future architectural evolution shall occur only through amendments justified by implementation experience, in accordance with AIP-13 (Implementation-Driven Evolution). No new architecture phases shall be introduced without explicit ARB approval. The next objective is not further modeling, but realization of the approved reference architecture in Phase 3B, followed immediately by validation through a real PublicDigit feature.

Individual artifact dispositions:

| Artifact | Disposition |
|---|---|
| Phase-01-Discovery | **Frozen** (Baseline v1.0) |
| Phase-02-Domain-Model | **Frozen** (Baseline v1.0) |
| Phase-02.5-Certification-Plan | **Frozen** (Baseline v1.0) — its rulings register (§6) remains the append-only record of ARB rulings |
| Phase-02.6-Ubiquitous-Language | **Freeze approved, conditional on one final terminology review** (open item OI-1) |
| Phase-02.7-Platform-Decisions | **Frozen** (Baseline v1.0) |
| Phase-03A-Reference-Architecture | **Frozen** (Baseline v1.0) — the last design artifact |

**"Baseline" semantics** (deliberately chosen over "closed"): Stable → Validated → **may evolve by amendment**. Frozen artifacts change only by supersession via a new ADR in this series (AIP-11), never in place.

## Rationale

- Architecture is never proven by more architecture; it is proven by implementation. The program has reached the point where implementation is the primary source of architectural feedback (AIP-13).
- Continuing to produce Phase-02.x documents would constitute the over-modeling the program's own convergence rule (ER-05) forbids.
- The platform's governance (promotion chain, Authority Boundary, honesty invariant, separation of duties) is defined tightly enough that realization can be objectively checked against it (FF-1..16, PD enforcement mapping).
- A formal baseline decision was the one missing artifact: the promotion chain requires a Human Decision Event to move the corpus beyond `generated/draft` — this ADR is that event's record.

## Consequences

1. **Phase 3B mission:** *realize the approved reference architecture* — not "create `.claude`". Every file produced in Phase 3B must be able to answer: **Which capability owns me? → Which bounded context owns me? → Which architecture principle justifies me? → Which platform decision governs me? → Which ADR authorizes me?** A file that cannot answer these questions shall not exist.
2. **Phase 4 — Platform Validation** follows immediately: implement one real PublicDigit feature through the platform. **Pilot: PB-004 (ContestedOutcome / Election Reaction)** — chosen because it exercises DDD, governance, the ADR workflow, implementation guidance, verification, review, and traceability (nearly every modeled capability).
3. No further architecture documents unless implementation reveals a genuine architectural gap; such gaps produce **amendment proposals** to existing artifacts, routed through ARB.
4. The Phase-02.5 rulings register (§6) remains the append-only log of ARB rulings for this platform; the open item below is tracked there.
5. Open item **OI-1:** final terminology review of Phase-02.6 before its freeze takes effect (review protocol per ruling R-11).

## Conformance

- FF-14 (assertion integrity) verifies no platform artifact claims a status this ADR (or a successor) does not grant.
- FF-13 (convergence) verifies the artifact count does not grow without ADR authorization.
- The Phase 3B Definition of Done includes demonstrating every PD-01..20 row guarded or compensated (Phase-02.7 §4; Phase-03A §6).

---

## Addendum — ARB sign-off & Construction Resolution (2026-07-08, appended; original text above unchanged per AIP-11)

**This ADR was reviewed and APPROVED (signed) by the Chief ARB on 2026-07-08**, with the following refinements, effective immediately:

1. **Baseline qualifier:** the baseline is designated **"Baseline v1.0 — Reference Implementation Pending."** The architecture is approved; the reference implementation is not yet proven. These are different states and are stated as such.
2. **Renames (vocabulary of the next chapter):**
   - Phase 3B → **Platform Construction** (infrastructure is being built, not business logic implemented);
   - Phase 4 "Platform Validation" → **Platform Qualification** ("this platform is now trusted for production engineering" — stronger than testing);
   - phase-thinking ends here: construction proceeds in **Iterations**, not phases. Iteration 1 = minimal platform capable of supporting PB-004; Iteration 2 = refinement from lessons learned; Iteration 3+ = advanced capabilities only if justified by implementation evidence (AIP-13).
3. **Construction discipline (binding):** the platform is constructed **incrementally — every commit leaves the platform usable, green, and testable**; indicative slice order: (C1) composition root/loading/registry → (C2) rules → (C3) knowledge → (C4) hooks → (C5) commands → (C6) agents. Construction follows the same cadence as PublicDigit itself: architecture → small slice → review → merge → next slice. **Construction shall not be executed in a single session.**

**ARB Resolution (Construction):**

> The AI Engineering Platform architecture is accepted as **Baseline v1.0**. The architecture phase is complete. The platform shall now enter **Construction**. All future architectural changes shall originate from implementation evidence and shall be processed through the established amendment process. The first objective is **not** feature completeness. The first objective is to construct a **minimal, working AI Engineering Platform** capable of supporting a single PublicDigit feature (**PB-004**) from planning through implementation, review, and verification.

Open item OI-1 (final terminology review of Phase-02.6) remains the only outstanding review; the ARB flagged the term **"Review"** (architecture/code/security/gate/ARR/human/AI review) as the exemplary ambiguity that review must resolve.

---

*Traceability: records the Chief ARB resolutions of 2026-07-08 (baseline + sign-off addendum) · baseline corpus at `docs/architecture/proposals/ai-platform/` · rulings register `Phase-02.5-Certification-Plan.md` §6 (append-only; R-1..R-22) · session `.claude/sessions/2026-07-08.md`.*

# PO / ARB Decision Record — Phase 3 Voting Authorization Boundary

**Date:** 2026-10-05 · **Decision:** ✅ **APPROVED** — architectural baseline only · **Decided by:** PO / ARB (human act, this session)
**Subject:** `docs/publicdigit/adr/ADR_20261005_0950_Voting_Authorization_Boundary.md` (rev 2, committed `dc809b6bf`) — status changed from *PROPOSED — READY FOR PO / ARB REVIEW* to **APPROVED — Phase 3 Authorization Boundary Design**
**Roadmap:** `docs/plans/20261005-0647-voting-integrity-anonymity-and-authorization-roadmap-plan.md` (Phase 3) · **Recorded by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable). Engineering supplies the record; the decision is the PO's.

---

## 1. Decision (as stated by the PO / ARB)

> The Phase 3 Voting Authorization Boundary ADR is approved as the architectural baseline for PublicDigit voting authorization.
>
> The approved baseline establishes:
> 1. Admission remains inside Voting Entitlement.
> 2. `forEntry()` provides the fresh authorization boundary for entering / continuing the voting process.
> 3. `forCast()` combines fresh authorization with process validation and produces a `CastDecision`.
> 4. `Code.has_voted` is participation state, not authorization.
> 5. The database compare-and-set is the sole authoritative atomic participation-consumption mechanism.
> 6. The existing Trust → Legitimacy chain remains the current security authority; no parallel security engine is authorized.
> 7. No persisted authorization state is introduced.
> 8. No new voter ↔ ballot correlation path may be introduced.
> 9. A5 / A6 remain unchanged pending Phase 2.0.
> 10. Phase 7 IP binding remains blocked pending the anonymity-boundary decision.
>
> **This approval authorizes the architectural baseline only. It does not authorize production implementation.**
>
> Phase 4 implementation requires a separate implementation authorization and resolution of the applicable open decisions, particularly Q2, Q4 and Q5.
>
> **Phase 2.0 remains the authoritative decision point for Model A vs Model B.**

## 2. Rationale (summary of the PO / ARB review)

- Authorization ≠ process validation ≠ atomic participation consumption — no hidden authority in `Code`, `forCast()` or a generic flag.
- `forCast()` cannot decide "already voted"; the compare-and-set is authoritative.
- Security consolidated as a **role** of the existing chain — no architecture proliferation.
- Admission is evaluated once (the decided admission architecture is not reopened).
- The anonymity sequencing is respected: A5 / A6 preserved, Model A / B undecided, interim rule and I10 binding.
- Phase 7 is blocked because of the cross-cutting A1 correlation.
- Q4 (demo) and Q5 (agreement) are explicit decisions; implementation may not decide them silently.
- Implementation stays separately authorised per phase and per production edit group.

## 3. Approval conditions / implementation prerequisites

| Decision | Status | Required before |
|---|---|---|
| Authorization boundary | ✅ Approved | — |
| Admission inside entitlement | ✅ Approved | — |
| `forEntry()` concept | ✅ Approved | Phase 5 |
| `forCast()` concept | ✅ Approved | Phase 4 |
| CAS participation consumption | ✅ Approved | Phase 4 |
| One security authority | ✅ Approved | Phase 4 / 5 |
| No persisted authorization | ✅ Approved | all implementation |
| Interim anonymity boundary | ✅ Approved | all implementation |
| A5 / A6 unchanged | ✅ Approved | Phase 2.0 |
| **Q1** security authority naming / home | 🟡 Pending | before implementation where relevant |
| **Q2** `Deferred` / `Investigate` | 🟡 Pending | **Phase 4** |
| **Q3** security-policy boundary | 🟡 Pending | Phase 6 |
| **Q4** demo-election scope | 🟡 Pending | **Phase 4** / 5 |
| **Q5** agreement requirement | 🟡 Pending | **Phase 4** |
| **Q6** middleware placement | 🟡 Pending | Phase 5 |
| **Q7** `can_vote_now` regeneration (PBDIGIT-231) | 🟡 Pending | Phase 5 |

## 4. Consequences

- The ADR text is **frozen** as the baseline; further changes require a superseding ADR or an explicit revision act.
- **Next engineering step:** Phase 4 — only after (a) a separate Phase 4 implementation authorization and (b) PO answers to **Q2, Q4, Q5** (Q1 where relevant).
- Unchanged: Phase 2.0 open (Model A / B); A5 / A6 accepted interim risk; Phase 7 blocked; interim anonymity rule active.

**Traceability:** ADR `ADR_20261005_0950` (rev 2, `dc809b6bf`) · roadmap Phase 3 · Phase 1 evidence (Gate 1 ACCEPTED) · PBDIGIT-223 … 233.

# Kernel Theory Track (Track A) — Reassessing the Seven Candidates Against New Evidence

**Date:** 2026-09-30. This is Track A, explicitly parallel to and not gated by Track B (empirical
validation — Gate D, WP population reading, independent-author search). Nothing here is frozen; no
concept is promoted to an adopted kernel. All new evidence is same-author, per the disclosed finding
in `2026-09-29-KOS-WP-GOVERNANCE-MECHANISM-RECONSTRUCTION.md` and
`2026-09-29-KOS-CROSS-ARTIFACT-MINIMAL-THEORY.md` — reused here without re-verifying facts already
confirmed and reviewed in those documents.

## The seven existing candidates (status carried from `EKS-PKS-Relationship.md` §4, unchanged)

Observation (withdrawn as kernel-level) · Rule/classification (plausible, weaker than claimed) ·
Authorization/commitment-gate (supported, two sources) · Canonical representation (supported in EKS,
asserted in PKS) · Provenance (supported in EKS, declared in PKS) · Uncertainty/indeterminacy
(strongest candidate as of the 2026-09-29 assessment, three confirmations — **see below: this ranking
is narrowed, not re-confirmed, by this round's evidence**) · Governed lifecycle (supported, same
asymmetry as Canonical representation/Provenance).

## New evidence from `docs/ARCHITECTURE.md` (`RULE-H6-02`–`H6-10`) and PKS C4/AD-1, tested against each candidate

| Candidate | Effect of new evidence | Status change |
|---|---|---|
| **Canonical representation** | `RULE-H6-03` (*"Projection tables are not sources of truth"*) and PKS's `AC-2` (a diagram-label annotation, *"Holds NO source of truth"*, `PKS_Phase_IIC_C4_Architecture_Views.md`, §6) are two more same-author instances of the same declared distinction already behind this candidate — plus `RULE-H6-04`, reclassified here from `Authorization/commitment-gate` (see below). **Sharpens the candidate's definition** rather than adding new support: "canonical representation" should be read specifically as *source-of-truth identification*, distinct from mere data structure. | Definition refined, not strengthened in generality (same-author) |
| **Provenance** | `DP-5` (*"every graphical element shall have an architectural provenance"*) is a fourth same-author instance. No new evidentiary weight beyond what was already counted. | Unchanged |
| **Uncertainty/indeterminacy** | **Corrected — this is a real limit on the candidate's standing, not a neutral note.** No new evidence for this candidate was found in `ARCHITECTURE.md`/PKS-C4; both are notably *more* deterministic/certain in tone (`RULE-H6-06` guarantees reproducibility, not uncertainty). All of this candidate's support remains concentrated in the governance-decision artifacts (KOS, WP) and is entirely absent from the architecture-representation and code-level artifacts tested this round. Its "strongest candidate" ranking was carried forward unchanged from the 2026-09-29 assessment without re-examining whether that ranking's own "three independent confirmations" are actually independent — they are same-author, same as everything else, and "independent" there meant only "separate lines of work," not cross-author. | **Scope narrowed, not merely noted: this candidate's evidentiary base is narrower than previously stated, and its "strongest" ranking has not been re-verified this round** |
| **Authorization/commitment-gate** | **Corrected — this was a misreading, verified directly against `tests/Architecture/GovernanceDomainPurityTest.php:569`.** The test only reflects on constructor-parameter *types* of four query-path classes and checks they don't name four forbidden policy/interpreter classes; it does not, and cannot, verify that "no governance interpretation... may execute at request time" as the rule's prose claims — a static call, service-locator resolution, or method-level parameter would pass undetected. `RULE-H6-04` is better read as a CQRS read/write layering rule (queries stay off the write-model's policies) than as an authorization *gate* in the sense this candidate means (a check before a commitment). **Reclassified as evidence for `Canonical representation`** (it applies `H6-03`'s "projections aren't the source of truth" on the read side) rather than `Authorization/commitment-gate`. No search for other code-enforced instances was performed; the "first ... found" claim is withdrawn as unsupported. | **Withdrawn from Authorization/commitment-gate; folded into Canonical representation's evidence instead, with the test's real scope disclosed** |
| **Governed lifecycle** | `PROGRAM_STATUS.md`'s four-gate structure (§"decision gates — engineering resumes on one of these, and on nothing else") and the WP `PLAN→EXECUTION→ACCEPTANCE` staging are further same-author instances already counted via the WP reconstruction. No new artifact type here. | Unchanged |
| **Rule/classification** | No new evidence found. | Unchanged |
| **Observation** | Not revisited — already withdrawn, no new evidence bears on the withdrawal either way. | Unchanged |

## Candidate 8? Testing `Determinism ≠ Truth/Authority/Correctness` against the promotion criteria

The redirect proposes this distinction (`RULE-H6-06`'s reproducibility guarantee, explicitly not
claiming reconstructed state is *true*) as a possible new kernel-level primitive. Tested against the
required criteria, honestly:

1. **Repeatedly observed?** Only once, in one document (`RULE-H6-06`/`H6-03`'s juxtaposition), and —
   per the just-reviewed correction — that juxtaposition is a *reading consistent with* the document,
   not something the document states outright. **Weak on this criterion.**
2. **Logically necessary?** Plausibly yes in the abstract (determinism is a property of a function;
   truth is a property of a proposition; they are different logical types) — but this is a general
   fact about computation, not specific evidence from this corpus.
3. **Discriminating — does it distinguish real cases?** Not yet tested against a real case where the
   distinction actually mattered for a determination (e.g., a case where a deterministic
   reconstruction was *wrongly* trusted as truth). No such case has been found in this research line.
4. **Survives counterexamples?** Not searched specifically for this candidate.
5. **Domain-independent?** Untested — same-author-only evidence, same limitation as everything else
   this cycle.

**Verdict: `HYPOTHESIS`, not promoted to an 8th candidate — and, corrected, given no home among the
other seven either.** The original version of this document folded it into `Uncertainty/indeterminacy`
without testing whether it fit `Canonical representation` or `Provenance` at least as well (a
deterministic-but-unverified reconstruction is arguably *exactly* `RULE-H6-03`'s own point — "not the
source of truth" regardless of how faithfully computed). Assigning it a home before the candidate
itself has passed any of the five criteria above is the same move the cross-artifact document's
reviewer already flagged there: reaching a comfortable classification instead of leaving the question
open. **Left unassigned.** A failed/untested candidate does not need a home.

## Dependencies between candidates (asked directly, not previously addressed — all three downgraded to `HYPOTHESIS`, none demonstrated)

- **`Authorization/commitment-gate` depends on `Governed lifecycle`** — **`HYPOTHESIS`, not
  demonstrated.** The offered counterexample (`RULE-H6-02`'s staleness tolerance has no discrete gate)
  shows a lifecycle can exist *without* a gate; it does not show a gate cannot exist without a
  multi-stage lifecycle — a single one-off approval (e.g. a bare acceptance, no prior stages) is never
  ruled out and was not searched for.
- **`Canonical representation` and `Provenance` co-occur** — **`HYPOTHESIS`, not demonstrated.** Rests
  on `DP-5` alone, with no enumerated set of instances checked. `RULE-H6-03` declares projections
  non-canonical without itself stating a provenance requirement for them — a real, unexamined
  potential counterexample to "always co-occur," left unresolved rather than asserted away.
- **`Uncertainty/indeterminacy` is presupposed by the others** — **`HYPOTHESIS`, not demonstrated.**
  Only three examples were checked, and no candidate's own definition was actually shown to *require*
  an uncertainty notion to be coherent — needing an "unresolved" bucket for edge cases is a common
  feature of most classification schemes, not evidence this candidate is special. This claim also
  should not be read as reinforcing `Uncertainty/indeterminacy`'s "strongest" ranking, which this same
  document's own new finding (above) narrows rather than confirms — using the ranking to justify the
  dependency, and the dependency to reinforce the ranking, would be circular.

## What remains explicitly unfrozen

All seven candidates remain candidates. No kernel version is declared. The 8th-candidate question is
left `HYPOTHESIS` and **unassigned** — corrected from an earlier draft that folded it into
`Uncertainty/indeterminacy` without testing whether `Canonical representation` or `Provenance` fit at
least as well.

## Next step for this track

Before assigning the 8th-candidate question a home in *any* of the seven, test it against all three
plausible candidates (`Uncertainty/indeterminacy`, `Canonical representation`, `Provenance`)
side by side, rather than picking the first one that seems to fit. Separately, `Uncertainty/
indeterminacy`'s own definition needs the same operational precision Track B's review demanded of
the cross-artifact document's central abstraction — without a stated boundary for what would count as
*outside* its scope, any classification question can be waved into it, which is exactly the failure
mode this correction round just found and removed.

---

**Traceability:** `2026-09-29-KOS-EKS-PKS-Relationship.md` §4 (the seven candidates' prior status) ·
`2026-09-29-KOS-WP-GOVERNANCE-MECHANISM-RECONSTRUCTION.md` (source of the WP lifecycle-staging
evidence) · `2026-09-29-KOS-CROSS-ARTIFACT-MINIMAL-THEORY.md` (source of the determinism≠truth
question and the compression discipline applied here) · `docs/ARCHITECTURE.md` `RULE-H6-04` (reclassified
to `Canonical representation`, not `Authorization/commitment-gate`; test scope is constructor-type-only,
verified against `tests/Architecture/GovernanceDomainPurityTest.php:569`) ·
`docs/implementation/PKS_Phase_IIC_C4_Architecture_Views.md`.

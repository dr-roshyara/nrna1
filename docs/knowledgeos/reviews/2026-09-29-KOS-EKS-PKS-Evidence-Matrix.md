# EKS/PKS — Evidence Matrix (corrected)

**Date:** 2026-09-29, consolidated and corrected. Supersedes
`2026-09-29-KOS-EKS-PKS-EVIDENCE-MATRIX.md` (retained in git history). The canonical claim→source
index for `EKS-Research-Definition.md`, `PKS-Research-Definition.md`, and
`EKS-PKS-Relationship.md` — those three documents link here rather than re-asserting tags.

| # | Conclusion | Status | Primary evidence |
|---|---|---|---|
| 1 | EKS = "Engineering Knowledge System," governs how engineering work gets done | `EXISTING DESIGN` | `20260821-2033-what-eks-is-today.md` |
| 2 | "The mechanism records authority; it does not grant authority" | `OBSERVED` | **144 grants, 22 work-item files, re-measured 2026-09-29** — corrected from an earlier "20 grants" citation; see `EKS-Research-Definition.md` §1 |
| 3 | EKS is two structurally unconnected real systems | `OBSERVED` | direct grep, re-verified twice (this session's own work, and the independent review) |
| 4 | `IdentifierIntegrity` README is "the template for every future capability" | `EXISTING DESIGN` | quoted directly, verified verbatim |
| 5 | Cohesion is a structural exception to that template (`Tests` location) | `DERIVED` | new finding, `2026-09-29-KOS-EKS-PKS-RESEARCH-REVIEW.md` (`EKS-PY-1`) |
| 6 | PKS = "Product Knowledge System," models knowledge/governance/evidence/relationships | `EXISTING DESIGN` | `20260728_1710_what_is_pks_v1.md`, quoted directly |
| 7 | PKS declares itself non-software (YAML+Markdown only) | `EXISTING DESIGN` | same source |
| 8 | PKS's measured reality is executing PHP code, identical to EKS's capability layer | `OBSERVED` | `PKS-Current-Architecture-Baseline-Stage-2.md`, code-identity confirmed by direct file check |
| 9 | `CAP-003` realized via historical back-test | `OBSERVED` | `20260821-1138-track2-deterministic-assurance-phase0-plan.md` |
| 10 | `OQ-11` (EKS/PKS boundary) is the corpus's own oldest unresolved question | `EXISTING DESIGN` | independently cited from two angles (AIP reconstruction; PKS baseline) |
| 11 | Cohesion's PHP/Python split is cross-language-neutral within tested scope | `OBSERVED` | 11 mechanisms, **9/11 byte-identical, 2/11 legitimate spelling differences with identical decisional fields** (corrected precision, not "0 divergences" unqualified) |
| 12 | Two closed verdict vocabularies exist, structurally identical | `OBSERVED` | code, both verified |
| 13 | The two vocabularies were "independently arrived at" | `UNKNOWN` — **withdrawn as a claim**, no design-history evidence found | `2026-09-29-KOS-EKS-PKS-RESEARCH-REVIEW.md` §5 |
| 14 | Python-first is an adopted, real policy | `EXISTING DESIGN` | `2026-09-28-KOS-PYTHON-FIRST-POLICY.md` |
| 15 | PKS's Python architecture question may be a category error | `DERIVED` | direct comparison |
| 16 | Seven-concept kernel candidate set, confirmed in both EKS and PKS | `OBSERVED`, **asymmetric — EKS mostly code-level, PKS mostly declared; only Uncertainty/indeterminacy is code-level on both sides** | corrected, `EKS-PKS-Relationship.md` §3.C |
| 17 | Authorization/commitment-gate has a "third, fully independent witness" | **`CONTRADICTED` — withdrawn** | see `EKS-PKS-Relationship.md` §1.1; the candidate itself survives on two sources, not three |
| 18 | `Fuse` "correctly reproduced" a real anomaly (6-commit test) | **`CONTRADICTED` by later same-day evidence — corrected** | see `EKS-PKS-Relationship.md` §1.2; `Fuse` as specified would have missed the case |
| 19 | The 379-commit census and entropy-reduction results | `OBSERVED`, population-level, unaffected by #18's correction | `2026-09-29-KOS-evidence-fusion-model.md` §11 |
| 20 | Relation to the externally-referenced `K5=(S,A,R)` candidate | **out of scope** | no source material found anywhere in this repository — confirmed independently twice (this session, and the adversarial review) |

---

## What remains genuinely open

- `OQ-11` (EKS/PKS boundary) — the single largest unresolved question this whole document set
  depends on.
- Whether PKS needs a Python architecture at all, or only a schema validator.
- Whether EKS and PKS could run on genuinely different kernels.
- Whether the seven-candidate set and `K5` are the same, overlapping, or unrelated — not evaluable
  without `K5`'s source material.
- Whether Cohesion's `Tests`-placement deviation from the shared template is intentional or
  unnoticed drift.
- Whether the two closed verdict vocabularies were actually designed independently or with
  cross-influence.

**None of these are filled by inference in this document set.**

---

**Traceability:** `2026-09-29-KOS-EKS-Research-Definition.md` ·
`2026-09-29-KOS-PKS-Research-Definition.md` · `2026-09-29-KOS-EKS-PKS-Relationship.md` ·
`2026-09-29-KOS-EKS-PKS-RESEARCH-REVIEW.md` (source of every correction row above) · superseded
original: `2026-09-29-KOS-EKS-PKS-EVIDENCE-MATRIX.md` (git history, commit `19d3cb7fa`).

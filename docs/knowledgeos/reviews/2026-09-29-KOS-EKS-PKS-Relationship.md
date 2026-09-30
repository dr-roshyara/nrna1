# EKS ↔ PKS — Relationship, Boundary, and Kernel Connection

**Date:** 2026-09-29, consolidated. Supersedes and merges `2026-09-29-KOS-EKS-PKS-BOUNDARY.md` and
`2026-09-29-KOS-EKS-PKS-KERNEL-RELATION.md` (retained in git history). **Carries two corrections
from the independent adversarial review** (`2026-09-29-KOS-EKS-PKS-RESEARCH-REVIEW.md`) as an
explicit erratum, §4 — read that section before trusting Question F/G below.

---

## 1 · Erratum — read this first

Two claims in the original `KERN` document (merged into §3 below) were found, on independent
review, not to survive scrutiny. Both are corrected here, in place, with the original claim quoted
so the correction is auditable, not silent.

### 1.1 · The "third independent witness" claim (was Question F)

**Original claim:** *"the third independent witness for candidate (3) [Authorization/commitment-
gate] came from a 2026-08-21 investigation that used **neither** EKS's nor PKS's vocabulary... and
reached the same substance... independently."*

**Correction:** reading `KnowledgeOS-Epistemic-Architecture-Investigation.md` directly (the cited
source), its own headline finding is explicitly built by citing *"EKS (`AP-1`: 'knowledge feeds
authority, it never holds it')"* and *"PKS (`AP-1`; `MCR-5`)"* as primary evidence, and its own
evidence-hierarchy rule states repository evidence (including EKS/PKS baselines) is **primary**,
while the actually-external frameworks it tested (Bayesian/calibrated-confidence models) are
**secondary, never architectural evidence**, and were in fact **largely rejected** (its own finding
3: one such framework "has ZERO current-architecture support"). **This is not an independent
witness — it is `AP-1` cited a third time.** The Authorization/commitment-gate candidate does not
need this witness to stand: it is directly supported by EKS's own real, tested code (`G-3`
mandatory human `START`) and PKS's own declared two-act rule. Removing the third-witness claim
weakens nothing; it removes an overclaim.

### 1.2 · The `Fuse` "correctly reproduced" claim (was Question G)

**Original claim:** `Fuse` was *"tested against a complete real commit population (6 commits, 3
`CONFIRMED`/2 `UNCITED`/1 `CONTRADICTED`)... correctly reproduced an independently-verified real
anomaly."*

**Correction:** this claim was accurate when written, but was **superseded the same day** by later
work in the same research chain (`2026-09-29-KOS-evidence-fusion-model.md` §11 and the separate
`2026-09-29-KOS-PROTECTED-ARTIFACT-SWEEP.md`), which discloses that the underlying citation was
misread — `Fuse`, exactly as specified, would actually have returned `UNCITED` for the `9f83a369c`
case, **missing** the real violation entirely, because `Fuse` gates every check behind a work-item
citation and this commit cited none. A citation-independent protected-artifact sweep was required
to find it. **This is a more interesting and more honest result than the original claim**: it shows
a real, disclosed blind spot in the evidence model being tested, not a clean success. Recorded here
as the corrected version, not smoothed over.

**What survives unaffected by either correction, per the review's own §11 assessment:** none of the
seven kernel candidates in §3 below structurally depend on `Fuse` being correct — it was offered
only as a demonstration that EKS data can empirically test kernel candidates. Six of seven
candidates' evidence comes from elsewhere entirely. The exception, Uncertainty/indeterminacy, gains
one of *three* independent confirmations from the sweep's own `NOT_APPLICABLE` state — even if that
confirmation were removed, it would still be the strongest candidate in the set on the remaining two.

---

## 2 · The comparison (from `BOUND`, spot-checked by the review, no corrections required)

| Dimension | EKS | PKS |
|---|---|---|
| Purpose | Governs *how* engineering work on this repo gets done | Models engineering knowledge, governance, evidence, and their relationships |
| Primary responsibility | Enforce/verify selected invariants over engineering activity | Provide deterministic AI guidance on artifact production/structure/verification |
| Input | Source files, commits, file saves | Knowledge specifications (declared); real capability inputs are source/markdown files (measured) |
| Output | Verdicts, observations, recommendations, decisions, outcomes, assessments | Projections (`ADR`, `Guide`, `Session Log`...); real capability outputs are the same verdict vocabulary as EKS |
| Core concepts | Canonical semantic facts, closed verdict vocabulary, observation→recommendation→decision→outcome→assessment chain | 17 knowledge concepts, capability lifecycle |
| Main operations | Cohesion analysis, identifier/reference/vocabulary integrity checks | Identifier/reference/vocabulary integrity checks (**identical code**), knowledge modeling (declared, not yet fully executing) |
| Persistence | Gitignored JSON (workflow), JSONL (observations) | YAML/Markdown, git-tracked (declared) |
| Python boundary | Established — `EKS-Research-Definition.md` §8 | Unresolved — `PKS-Research-Definition.md` §4 |

**Are they independent, layered, or the same thing? Not decided — `OQ-11` remains open.** They are
not independent in practice at the code level (the real capability layer is claimed as evidence by
both sides); not simply layers (no designed data flow found either direction); may be different
*views* of one underlying capability substrate — `HYPOTHESIS`, not `ESTABLISHED`.

> **Note on double-duty evidence (review finding, contradiction #4):** the shared-directory fact
> (Cohesion and PKS's capability layer occupy the same code) is used in §3 below to support treating
> EKS and PKS as validly *independent* validation domains for the kernel candidates, **and** in this
> section as evidence they might be the *same system* under two names. Both uses are individually
> defensible. Stating this plainly, in one place, rather than leaving it implicit across two
> documents as before.

## 3 · Kernel relation — Questions A–G

> ⛔ **Scope disclosure, unchanged:** this section cannot evaluate the specific candidate
> `K5=(S,A,R)` referenced in dialogue elsewhere — no source material for it exists anywhere in this
> repository (confirmed by direct grep, and independently re-confirmed by the adversarial review).
> What follows uses this research line's own seven-concept candidate set.

**A — Which EKS capabilities require a kernel?** Cohesion requires canonical representation and
uncertainty-as-distinct-state (`IndeterminateBehaviourReference`, `OBSERVED`). Capability-layer
checks require rule/classification and provenance. The Observation Runtime requires observation,
authorization-gate, and governed lifecycle. `OBSERVED`.

**B — Which PKS capabilities require a kernel?** The 17 declared concepts require provenance
(*"citations, provenance"* on every concept) and uncertainty (`Question` as *"an owned, routed
unknown"*). The capability lifecycle requires governed lifecycle. `EXISTING DESIGN`.

**C — Which concepts are shared by EKS and PKS?** All seven — **with an asymmetry the review
surfaced that the original document did not state plainly: EKS's side of this evidence is
code-level/tested for most candidates; PKS's side is textual/declared for most.** Only
Uncertainty/indeterminacy has genuine code-level confirmation on both sides (Cohesion's `D-1`; the
Observation Runtime's own `INCONCLUSIVE`). This asymmetry is now stated explicitly rather than
implied as equal support.

**D — Merely application-level concerns?** Cohesion's specific PHP/Python syntax rules; PKS's
specific YAML field names. `DERIVED`.

**E — Could EKS and PKS operate on different candidate kernels?** Not ruled out. `UNKNOWN`.

**F — Could the kernel exist independently of both EKS and PKS?** See §1.1 — the third-witness
claim previously offered here does not survive. **What remains defensible:** the Authorization/
commitment-gate candidate is independently supported by EKS's own tested code and PKS's own
declared rule, which is itself two independent sources, not one — the correction removes an
overclaimed third source, it does not eliminate the candidate.

**G — Do EKS and PKS provide empirical requirements that can test candidate kernels?** Yes — see
§1.2's corrected account. The `379`-commit census and the normalized-entropy comparison in
`2026-09-29-KOS-evidence-fusion-model.md` §11 remain valid, population-level results, unaffected by
the `9f83a369c` correction.

## 4 · Re-derived kernel-candidate table (from the independent review, not the original documents)

| Candidate | Status |
|---|---|
| Observation | **Not kernel-level** — a logical precondition of running any experiment, not an empirical finding. Withdrawn, and the corpus's own later work (`PROTECTED-ARTIFACT-SWEEP.md`) independently agrees. |
| Rule/classification | **Plausible, weaker than claimed** — explainable by a shared governance mandate across both systems, not necessarily a shared kernel. |
| Authorization/commitment-gate | **Supported** — by EKS's tested code (`G-3`) and PKS's declared two-act rule alone; the third-witness claim is removed (§1.1), the candidate still stands on two real sources. |
| Canonical representation | **Supported in EKS (tested), asserted-not-tested in PKS.** The asymmetry was previously implied as equal; now stated. |
| Provenance | **Supported in EKS (144/144 grants, strong), declared-only in PKS** (schema field exists; no drift-check found). |
| Uncertainty/indeterminacy | **The strongest candidate in the set** — three independent code-level confirmations (Cohesion's `D-1`; the Observation Runtime's four-state verdicts; the sweep's own `NOT_APPLICABLE` state, required to correctly classify real cases), plus a declared PKS analog. |
| Governed lifecycle | **Supported**, same EKS-code/PKS-declaration asymmetry as Canonical representation and Provenance. |

---

## Status table

| Question | Status |
|---|---|
| EKS/PKS boundary (`OQ-11`) | `UNKNOWN`, deliberately unresolved |
| Comparison table | `DERIVED`, spot-checked, no corrections required |
| Kernel Questions A–G | Answered, F/G corrected per §1 |
| Relation to `K5=(S,A,R)` | out of scope — no source material found anywhere in the repository |

**Traceability:** `2026-09-28-KOS-evidence-derived-architecture-research.md` §§6, 13, 19–21 ·
`2026-09-29-KOS-evidence-fusion-model.md` (complete, including §11's correction) ·
`2026-09-29-KOS-PROTECTED-ARTIFACT-SWEEP.md` · `KnowledgeOS-Epistemic-Architecture-Investigation.md`
(read directly for §1.1's correction) · `2026-09-29-KOS-EKS-PKS-RESEARCH-REVIEW.md` (source of both
corrections and the re-derived kernel table) · superseded originals:
`2026-09-29-KOS-EKS-PKS-BOUNDARY.md`, `2026-09-29-KOS-EKS-PKS-KERNEL-RELATION.md` (git history,
commit `19d3cb7fa`).

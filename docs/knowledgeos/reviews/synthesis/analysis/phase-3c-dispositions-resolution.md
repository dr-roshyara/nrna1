# 3C Dispositions — Resolution & Adjudication Record (GN-27)

**Authority: HPA ruling 2026-08-28 — "resolve CF-001 and CF-003, adjudicate CF-010."
All determinations below are evidence-based; sources cited; nothing else was changed.**

---

## CF-001 · RESOLVED — the "worktree" is a parallel authorized branch, not a rival tree

**Evidence ⟦E⟧ (git, 2026-08-28):** main tree = branch `knowelegeos-modelling` (HEAD `f24edf31`,
2026-08-28); `.claude/worktrees/kos-v11-ddd` = branch `kos-v11-ddd-refinement` (HEAD `22e8e1f1`,
2026-08-25, unmerged). Fork point `6ef5d288`, 2026-08-22; since then 66 commits (main) vs 53
(branch), parallel. Reference Architecture v1.1 is **committed on the branch** with commit message
*"v1.1 §9 CONFORMANCE CORRECTION (r5) — applied under explicit HPA authorization and VERIFIED
against all six criteria (2026-08-23)"* — i.e. governed, lane-scoped work, not stray files.

**Resolution:**
1. **For the synthesis programme** (v0.2 · 3C · archaeology · Final Architecture), the
   authoritative tree is the **main branch `knowelegeos-modelling`** — every GN-ruled act of this
   programme (GN-19…27) is recorded there, and it carries the later record.
2. The branch `kos-v11-ddd-refinement` is a **parallel authorized lane** (kernel adjudication +
   RA v1.1 refinement). Its artifacts hold the authority of their own lane's acts, but are **not
   part of this programme's record or conformance target until a governed merge/intake act**.
3. **Consequence for Final Architecture (binding input):** RA v1.1 must be either consumed through
   an explicit intake act or explicitly scoped out — it may not be absorbed silently, and it may
   not be ignored silently. The ambiguity is thereby resolved: nothing is "the wrong tree"; the
   authority boundary is per-lane, and the reconciliation is a named Final-Architecture input.

## CF-003 · RESOLVED — a ratification act exists; the Constitution's banner is stale

**Evidence ⟦E⟧:** Constitution v1.0 produced 2026-08-22 09:51 with status *"PROPOSED … pending HPA
ratification."* The Research-Phase Closure record (`reviews/20260822-1028-…`, 10:28 the same day)
records the HPA's assessment of the executed sequence — *"P5 → Validate candidates → Remove
everything unnecessary → **Freeze KnowledgeOS Constitution v1.0**"* — and states: *"This assessment
is therefore recorded as a **retrospective ratification**, not a re-commission."* Two later
same-day records rely on it: Z-KOS-001 ratification (11:15) and the Zero synthesis (11:30, *"
Constitution: RATIFIED"*).

**Resolution:**
1. **The Constitution v1.0 was ratified** — by the retrospective ratification of 2026-08-22 10:28,
   which covers the constitution-freeze step by name. The downstream "ratified" statements are
   grounded, not manufactured.
2. The residual defect is **status-synchronization only**: the Constitution document's own banner
   was never updated from PROPOSED. That is an ES-004.3-style stale-banner condition on the
   repository side. **Recommendation (not performed — outside this authorization):** a one-line
   additive status annotation on the Constitution citing the 10:28 act.
3. ⟦INT⟧ The AF-008/CF-003 pattern-sighting narrows accordingly: the gap is *tracking* of in-force
   status, not a missing ratification. The four-sighting pattern remains real; this instance is its
   mildest form.

## CF-010 · ADJUDICATED — the prose verdict record prevails; the CSV is unreliable standalone

**Evidence ⟦E⟧, cell-by-cell (2026-08-28):**

| Cell | Corpus verdict doc `20260827-135038` (prose matrix) | Repo CSV | Model-side registry |
|---|---|---|---|
| Weighted-Mean / Duplicate | ❌ | True | "fails duplicates" (= ❌) |
| Bayesian / Irrelevance | ✅ | False | "passes all 7" (= ✅) |
| MAX / Irrelevance | ❌* (footnote: handled by normalization layer) | True | footnote transcribed |

**Adjudication:**
1. **The model-side registry is exonerated** — it transcribes the corpus verdict document
   faithfully, footnote included. The discrepancy is **between the two historical artifacts**.
2. **The prose verdict document prevails as the reliable record.** Grounds (not preference): it is
   self-consistent under its own footnote (raw-operator irrelevance ❌ for MAX and Weighted Mean
   alike), while the CSV's MAX-Irr=True / WM-Irr=False split is internally inconsistent under ANY
   single reading (raw or normalized); the CSV carries no generator, no provenance, and no
   accompanying code; the prose record argues each property in named sections.
3. The CSV's three disagreeing cells are graded **NOT ESTABLISHED**; the CSV as a standalone
   artifact is graded **UNRELIABLE-WITHOUT-ITS-GENERATOR**. Neither artifact was modified.
4. **The central negative verdict is unaffected:** under either source Weighted Mean and MAX fail
   required properties, Saturating passes all listed, and the verdict additionally rested on the
   dependency-first argument. v0.2's non-claim (no operator selected) needs no revision.

---

**Gate consequence:** the three GN-24 pre-Final-Architecture obligations are discharged:
CF-001 RESOLVED · CF-003 RESOLVED · CF-010 ADJUDICATED. CF-006/CF-009 remain a
feed-into-Final-Architecture input (with AF-006 lineage attached). **Final Architecture remains
LOCKED pending its own authorization.**

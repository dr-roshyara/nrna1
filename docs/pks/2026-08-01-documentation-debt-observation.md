# PKS Observation — 47 Referenced Documents Were Never Written

**Date:** 2026-08-01 · **Kind:** operational evidence · **Domain:** PKS
**Placement:** derived — `php scripts/doc-placement.php --scope=product-specific --domain=pks` → `docs/pks`
**Status:** recorded observation. **No remediation performed.**

---

## Observation

**A repository-wide scan of every Markdown link found 121 broken references. After deterministic repair, 53 remain: 6 ambiguous and 47 pointing at documents that do not exist.**

**47 occurrences · 30 distinct targets · 21 source files.**

## Evidence

**Method:** for each unresolved target, `git log --all -- <path>` — if the repository has ever contained a file at that path, git retains it. Independently, a basename index over the whole repository shows whether a file of that name exists anywhere.

| Hypothesis | Result |
|---|---:|
| Deleted documents | **0** |
| Renamed or relocated documents | **0** |
| Intentionally archived documents | **0** |
| **Never written** | **47 (all)** |

> **No target has any git history at its referenced path, and no file of that name exists anywhere in the repository.** The references are index entries and *see-also* links pointing at documents that were **planned and never authored.**

**Concentration:** 15 of the 47 occurrences are two files referenced repeatedly from one index (`…/statemachine/05_COMMON_PATTERNS.md` ×8, `…/statemachine/02_API_REFERENCE.md` ×7). A further 6 belong to `docs/architecture/members-forum-context/`, and 5 to `developer_guide/organisations/membership_mode/`.

**Distribution by area:** `developer_guide/` 42 · `docs/` 10 (of the pre-repair set) · `architecture_legacy/` 2 · `.claude/` 1.

## Classification

> **Documentation debt — not migration damage.**

**This matters for attribution.** The documentation-roots refactoring and the `architecture_legacy` relocation broke links, and **every one of those was repairable and has been repaired.** The 47 predate that work entirely: **they were broken the day they were written**, because they referenced documents that were never created.

## What the observation suggests about the process

**A pattern worth recording, since PKS studies how engineering knowledge behaves:**

- **Indexes were authored ahead of their contents**, and nothing detected the gap. A `00_index.md` or `README.md` listing planned siblings produces a broken link the moment it is committed.
- **The gap survived indefinitely** because `knowledge-lint` validates `docs/knowledge/` only. **The areas carrying the debt — `developer_guide/`, `architecture_legacy/` — have no link validation at all.** Green lint was never evidence of a healthy repository.
- **Debt is invisible until it is counted.** The count did not exist until a repo-wide scan was run for an unrelated commission.

## Reusable rule — CANDIDATE, not adopted

**The observation generalizes beyond this repository. Recorded as a candidate at ARB direction (2026-08-01); adoption requires the ES-006.1 ladder and an explicit ruling (R-34).**

> **PROMOTION CONDITION (ARB, 2026-08-01): the candidate stays a candidate until *another unrelated repository produces the same observation*.** One repository has produced **one class of failure** — that is a single observation however many links it generated, and **a rule generalized from one corpus is a rule fitted to one corpus.**

> **CANDIDATE:** *A documentation index shall not reference an artifact that does not yet exist, unless the reference is explicitly marked as planned.*

**Why it is recorded here rather than in its own file.** The candidate is **cross-product** (any project could adopt it unchanged) at **research** maturity. Run through the resolver, that classification returns:

```
php scripts/doc-placement.php --scope=cross-product --maturity=research
PENDING — placement unruled (rule: cross-product-research, ref: ADR:OQ-2).
```

> **It lands in the one cell the placement model does not yet rule** — the same cell as `engineering/knowledge/methodology/Layer_Verification_Rule.md`. **Rather than invent a home or repeat that placement, the candidate is recorded inside this observation, which is the evidence that produced it and is already correctly placed.**

**⚠️ Decision-relevant consequence: the unruled cell now holds two artifacts, not one.** The stewardship decision was deferred on the explicit ground that *"the evidence is one artifact"* and one example does not justify generalizing. **That ground has changed.**

## Recommendation

**Backlog item, not remediation.** The three available dispositions each change meaning and belong to an owner, not to a repair tool:

| Option | Consequence |
|---|---|
| **Write the missing documents** | 30 documents; largest effort; the indexes become true |
| **Remove the references** | cheapest; the indexes stop promising what does not exist |
| **Accept as tracked debt** | honest; requires the count to stay visible, i.e. a validator that covers these areas |

**Recorded as `ENG-010` in `docs/implementation/backlog/BACKLOG.md`.**

## Reproduction

```bash
php scripts/link-check.php            # classification by evidence and confidence
php scripts/link-check.php --json=out.json
```

---

**Traceability:** `engineering/verification/reports/2026-08-01-link-evidence-and-ambiguity-reports.md` (the full classification) · `engineering/verification/reports/2026-08-01-placement-refinement-and-link-integrity-report.md` (the repair pass) · `scripts/link-check.php` · `docs/knowledge/schema/repository-migrations.yaml`. **Observation only — no document written, removed, or altered.**

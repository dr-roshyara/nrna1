# A Recurring Unruled Classification Is a Model Gap — Finding

**Date:** 2026-08-01 · **Prepared by:** Recording Architect
**Repository Integrity Gate:** ✅ PASSED. **No standard amended · no ruling minted · no artifact promoted · nothing moved.**

---

## 1. The reframing — and it is stronger than what I recorded

**I wrote:** *"the unruled cell now holds two artifacts."*

**The ARB's correction:** the discovery is not the count. It is that **the architecture has repeatedly produced evidence that the governance model lacks one classification.**

> **The distinction is real and it changes what the evidence supports.**
>
> **Two artifacts** is an inventory fact. It invites the reply *"and two is still a small number."*
>
> **The same unresolved classification recurring during ordinary work** is a property of the **model**, not of the artifacts. It does not get weaker with a small count, because **the observation is the recurrence, not the population.**
>
> **The DDD form: you evolve a model when reality repeatedly exposes the same missing concept.** Not when instances accumulate — **when the same gap keeps being reached from unrelated directions.**

**Recorded as the governing framing for the stewardship question, superseding my "now there are two" phrasing.**

## 2. The recurrence, stated as evidence

**Three independent arrivals at `cross-product + research`, from three unrelated commissions, within one day:**

| # | Artifact | How it arrived |
|---|---|---|
| **1** | `engineering/knowledge/methodology/Layer_Verification_Rule.md` | promoted from a WP-7 finding (G-1); the placement that opened this entire thread |
| **2** | The documentation-index candidate — *"a documentation index shall not reference an artifact that does not yet exist…"* | produced by a **link-integrity commission**, at ARB instruction |
| **3** | **The governance principle proposed in §3 below** | produced by **this** commission, at ARB instruction |

> **Each was reached by a different route, for a different purpose, by a different commission. None was looking for the gap.** That is what distinguishes recurrence from accumulation.

**And the third instance is self-demonstrating: the principle that says *"recurrence is evidence of a model gap"* is itself the third occurrence of the gap.** Recorded because it is the strongest available evidence for the ARB's own point, **not because it is a neat observation** — it would carry the same weight if the third artifact had been about anything else.

## 3. Governance principle — CANDIDATE, not adopted

> **CANDIDATE:** *An unruled classification that repeatedly appears during normal engineering work becomes architectural evidence for extending the classification model.*

**What it rules out, which is the point of it:** extending the model because *"this seems useful."* **What it admits:** extension because **engineering repeatedly reached the same unresolved state.** The trigger is observed recurrence, not judgment.

**Status: candidate.** Adoption requires the ES-006.1 ladder and explicit issuance (R-34). **Consistent with the standard the ARB just applied to the documentation-index candidate — one programme's experience is one corpus — this principle should meet at least the same bar before adoption.**

### Where it is recorded, and why not in a file of its own

**The principle is cross-product at research maturity.** Run through the resolver:

```
php scripts/doc-placement.php --scope=cross-product --maturity=research
PENDING — placement unruled (rule: cross-product-research, ref: ADR:OQ-2).
```

**So it is recorded here, inside the evidence record that produced it** — the same treatment given to the documentation-index candidate, and for the same reason: **a candidate with no ruled home is not given an invented one.**

## 4. Agreed — the documentation-index candidate stays a candidate

**Agreed, and the bar has been written into the artifact:** promotion waits until **another unrelated repository produces the same observation.**

**The reasoning is worth preserving:** the 47 references and 30 targets are **one class of failure in one corpus**, however many links they produced. **A rule generalized from one corpus is a rule fitted to one corpus** — and this programme has already recorded that confidence ceiling elsewhere (*"one corpus, one lineage — never citable as independently confirmed"*).

**Note the two candidates now sit at different bars, correctly:**

| Candidate | Bar |
|---|---|
| Documentation index rule | **another unrelated repository** produces the same observation |
| Recurring-unruled-classification principle | **at least** the same bar — and its own evidence is three arrivals within one programme |

## 5. ENG-008's trigger is now in the item, not in intent

**Written into `docs/implementation/backlog/BACKLOG.md`:**

> **TRIGGER (binding): a SECOND CONSUMER of the confidence model.** `link-check.php` is the only consumer today; externalizing now would introduce abstraction before operational evidence requires it. **Do not activate on "later"; activate on the trigger.**

**A trigger recorded only in a report is a trigger nobody will see when the item is next read.**

## 6. Remaining governance — no engineering reopens

| # | Item | Note |
|---|---|---|
| 1 | **Stewardship of `cross-product + research`** | **now supported by recurrence, not by a count** (§1–2) |
| 2 | **OQ-5** — the long-term KnowledgeOS structure | gates ENG-009 and what `docs/knowledgeos/` holds |
| 3 | **Does R-37 permit Phase 2 migration?** | gates all physical movement |
| 4 | **Mint the approved rulings** when appropriate | R-65…R-71 remain drafts |
| 5 | **Execute the Phase 1 classification map** when authorized | unblocked, not started |

**Also carried, unchanged:** the 6 ambiguous references (a human choice, ENG-010) · the five principles into the ADR in place of DAP-001 · applying the ES-005 amendment (one ruling).

```
knowledge-lint   ✅ All documents pass (0 errors, 0 warnings)
doc-placement    All 9 cases pass · registry and roots consistent
link-check       53 broken: 6 ambiguous · 47 missing · 0 deterministic
```

---

**Traceability:** `2026-08-01-classification-placement-operational-validation.md` (the "two artifacts" phrasing this supersedes) · `docs/pks/2026-08-01-documentation-debt-observation.md` (candidate 1 + its promotion condition) · `docs/implementation/backlog/BACKLOG.md` ENG-008 (trigger) / ENG-010 / ENG-011 · **R-34** (adoption requires issuance) · **R-29 / R-37** (reversed burden of proof) · **ES-006.1** (the ladder both candidates must climb) · `scripts/doc-placement.php` (PENDING, three times). **No standard amended · no ruling minted · no artifact promoted · nothing moved.**

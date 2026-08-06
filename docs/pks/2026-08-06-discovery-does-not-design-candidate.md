# PKS Observation — Discovery Does Not Design (engineering-principle candidate)

**Date:** 2026-08-06 · **Source:** `PBDIGIT-30` Working Organisation business discovery — §B4 withdrawal, Product Owner intervention
**Classification:** KnowledgeOS Candidate (operating-loop phases 13–14) — **FILED, NOT ADOPTED**
**⚠️ PLACEMENT: `PENDING` — non-compliance acknowledged, escalated, not hidden.** This artifact is `scope: cross-product · maturity: research`; `php scripts/doc-placement.php --scope=cross-product --maturity=research` returns **`PENDING — placement unruled (rule: cross-product-research, ref: ADR:OQ-2)`** — *record PENDING and escalate; do not guess a location.* It sits in `docs/pks/` on the **same interim, time-bounded exception** recorded by `2026-08-05-knowledgeos-distillation-principle-candidate.md` and `2026-08-01-knowledgeos-maturity-structure-assessment.md`: that folder is the PKS product domain's root, **not** an incubation namespace, and a research-maturity cross-product namespace **does not exist and must not be created without a ruling** (ES-005.2). The exception expires when OQ-2/OQ-5 are ruled.

**Why filed and not adopted:** n=1. One discovery story, in one repository, exposed the failure mode once. ES-006.1 requires repeated observation before a practice, and adoption is a Decision Authority act (R-34) — never engineering self-promotion. The methodology freeze (2026-08-01) permits *filing* evidence produced by implementation; it forbids acting on it.

---

## Candidate principle (verbatim heart-sentence)

> **Business discovery finds the rules a product already implies. It does not design the product's future.**

**The test it yields:** a discovery activity answers *"what does this product already require?"* When it starts answering *"what would be a good product?"*, it has silently become **design** — and should be **split**, not continued.

## The evidence — what actually happened

`PBDIGIT-30` set out to discover the business rules of the **Working Organisation** concept. B1 (identity), B2 (lifecycle) and B3 (authority) were discovery: each rule was already implied by behaviour the product ships, and the Product Owner ruled on questions the product was already asking.

**B4 was not.** Asked *"is the Working Organisation remembered between logins?"*, engineering produced a draft containing:

| Drafted as a rule | What it actually was |
|---|---|
| memory has **no expiry** | an invented policy — nothing in the product implies it |
| memory is **per person, not per device** | a product choice; mature products deliberately differ |
| memory **carries its reason across logout** | a new requirement, derived from a rule that had not asked for it |

The Product Owner rejected the draft, and named the giveaway — a sentence engineering had written unprompted:

> *"That is a gap **B4 creates work for**."*

**A discovered rule does not create work; it describes work the product already owes.** A rule that manufactures a backlog is a requirement wearing a discovery's clothes. B4 was withdrawn and became `PBDIGIT-34` — an explicitly unprioritised **question**, with no answer assumed.

## The second observation, from the same story — the corollary

Closing `PBDIGIT-30` showed why the discipline pays rather than merely constrains. Of nine questions opened, **three fundamental rules (B1–B3) retired five scenario questions (B5–B9)**: they were never answered, they *stopped being questions*, because each is B1–B3 applied to a case.

> **Discover the fundamental rules first, and many scenario questions disappear on their own.**

**Candidate corollary:** discovery that answers scenarios one by one *manufactures* decisions. Discovery that finds the fundamental rules *dissolves* them. And a derived consequence needs **no ruling, no owner and no maintenance** — it cannot drift from its parents, because it *is* its parents applied to a case. An independently answered scenario can drift.

## Assessment against the four promotion tests

Using `2026-08-05-knowledgeos-distillation-principle-candidate.md`'s tests:

| Test | Verdict |
|---|---|
| **1 Domain independence** — valuable if PublicDigit disappeared? | ✅ nothing here mentions elections, organisations or tenancy |
| **2 Repository independence** — would another project benefit unchanged? | ✅ any product with a requirements-discovery activity and a separate design authority |
| **3 Engineering value** — improves how software is engineered? | ✅ it protects the boundary between *eliciting* requirements and *inventing* them |
| **4 Evidence** — discovered through actual engineering work? | ✅ produced by a live discovery story failing in exactly this way, and being caught |

All four pass — which yields a **candidate**, and nothing more. **Passing the tests is not adoption.**

## What this candidate would extend, if ever promoted — never a new standard

**ES-005.4: consume or extend, never create a second.** This is *not* a new standard. It belongs as a clause of the existing discovery/readiness discipline:

- **The standing Development Discipline rule** (`.claude/CLAUDE.md`) already orders `Business → DDD → Architecture → Tests → Implementation` and already warns that *"an observation must never silently become architecture."* **This candidate is the same failure one stage earlier: a discovery must never silently become a requirement.**
- **Product Capability Review method rules** (`docs/publicdigit/backlog/README.md`) already carry *"a discovery report describes reality and never prescribes implementation"* and *"business discovery finds the rules a product already implies, never designs the product's future"* — **repository-local, and already in force.** Promotion would generalise the rule beyond this repository; it would add nothing here.
- **EP-01/EP-03** own authorization and readiness; **R-34** owns the separation of evidence from acceptance. The candidate reinforces both.

## Explicitly NOT claimed

- **Not that B4's content was wrong.** Each drafted answer is defensible; none was *discovered*. The defect is provenance, not quality.
- **Not that engineering may not propose enhancements** — only that it must not smuggle them in as discovered rules. `PBDIGIT-34` is the correct shape for the same material.
- **Not adopted, not a standard, not a practice.** ES-006.1 rung: **observation → candidate**. It stays there until a second, independent occurrence appears.

## Promotion path

```
this observation (n=1) → second independent occurrence → repeated observation
    → practice → candidate standard → Decision Authority ruling → clause of an EXISTING standard
```

**No promotion action is requested.** The next legitimate step is *waiting for a second occurrence* — nothing else.

---

**Traceability:** `docs/publicdigit/backlog/PBDIGIT-30-active-organisation-business-lifecycle-discovery.md` (§B4 withdrawn draft · CLOSURE RECORD "The discipline this closure protects" · "Why B5–B7 needed no ruling") · `docs/publicdigit/backlog/PBDIGIT-34-remember-the-working-organisation-across-logins.md` (where the withdrawn material went) · `docs/publicdigit/backlog/README.md` (method rules, FROZEN) · `docs/pks/2026-08-05-knowledgeos-distillation-principle-candidate.md` (the four promotion tests, themselves a candidate) · ES-005.4 · ES-006.1 · R-34 · R-90 · AIP-14 · placement `PENDING` per ADR:OQ-2

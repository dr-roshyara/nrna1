# PKS Observation — KnowledgeOS Distillation Principle (constitutional candidate)

**Date:** 2026-08-05 · **Source:** PB003 closure retrospective (ARB Chair / mentor review)
**Classification:** KnowledgeOS Candidate (operating-loop phases 13–14) — **PREPARED, NOT ADOPTED**
**⚠️ PLACEMENT: `PENDING` — non-compliance acknowledged, escalated, not hidden.** This artifact is `scope: cross-product · maturity: research`; the resolver returns **`PENDING — placement unruled (rule: cross-product-research, ref: ADR:OQ-2)`, exit 2**, i.e. *record PENDING and escalate; do not guess a location.* It sits in `docs/pks/` because that folder has become a **de-facto** home for engineering-process observations (8+ artifacts since 2026-08-01) — but `docs/pks/README.md` defines that root as *"documentation classified `scope: product-specific` · `domain: pks`"*, **the PKS product domain's root, not an incubation namespace**. So this placement is an **interim, time-bounded exception expiring when OQ-2/OQ-5 are ruled** (precedent: `2026-08-01-knowledgeos-maturity-structure-assessment.md`, which recorded the identical tension and escalated rather than inventing a folder). **A research-maturity cross-product namespace does not exist and must not be created without a ruling** (folder rule, ES-005.2).
**Why not adopted here:** the methodology is FROZEN (2026-08-01: no KnowledgeOS proposals absent implementation-evidenced deficiency); constitutional text is adopted by Decision Authority ruling (ES-006.1 · R-34), never by engineering self-promotion; and this candidate at filing has n=1 programme of supporting experience (PB003) plus the EPIC-001 retrospective's demonstrated ladder use — evidence to *file*, not yet to *rule*.

---

## Candidate principle (verbatim heart-sentence)

> **KnowledgeOS never invents engineering practices. It records engineering practices that have already demonstrated value.**

KnowledgeOS is **downstream of engineering**, not upstream:

```
PublicDigit → Experience → Distillation → KnowledgeOS
```

## Candidate distillation rule

Every commission may produce three kinds of knowledge:

| Kind | Examples | Destination |
|---|---|---|
| **Product knowledge** | election lifecycle · contestation model · membership rules · event catalog · aggregate design | product repository — **never promote** |
| **Engineering knowledge** | commission lifecycle · completion audit · certification methodology · evidence-based governance | KnowledgeOS **candidate**, only if repository-independent |
| **Local practices** | repo layout · naming conventions · build scripts · local tooling | repository — do not promote |

## Candidate promotion tests (all four must pass)

1. **Domain independence** — still valuable if PublicDigit disappeared tomorrow?
2. **Repository independence** — would another project benefit without adopting PublicDigit?
3. **Engineering value** — improves how software is *engineered*, not how *this software works*?
4. **Evidence** — discovered through actual engineering work, not speculation?

Passing all four yields a **candidate**; adoption still requires the ES-006.1 ladder (≥ repeated observation; "one work package is not a standard", R-90) and a Decision Authority ruling.

## Relationship to existing governance (this candidate largely CONFIRMS, it does not replace)

- AIP-14 Product Primacy — every platform addition serves a product feature
- R-27/R-29 governance freeze — platform changes only on product-demonstrated insufficiency, via retrospective
- Placement litmus (`engineering/README.md`) — "could a different project adopt it UNCHANGED?"
- Multi-product rule (DA clarification 2026-07-27) — improvements flow back **only** through ES-006.4 → ES-006.1 → Decision Authority
- ES-006.1 promotion ladder — observation → repeated observation → practice → candidate standard → approved standard

**What is genuinely NEW in this candidate:** (a) the explicit three-kind knowledge taxonomy at commission closure; (b) the four-test battery as a single checkable gate; (c) the heart-sentence as constitutional text; (d) the explicit statement that KnowledgeOS never goes *looking* for knowledge.

## Evidence at filing

- PB003 closure produced clean examples of all three kinds (closeout lifecycle = engineering candidate; inbox dedup key & challenge→contestation = product; review-folder convention = local).
- EPIC-001 retrospective (2026-07-11) exercised the two-evidence discipline: P-1/P-3 promoted on 3–4 independent instances; P-2/P-5 deferred at n=1.
- Counter-discipline applied to itself: the PB003 closeout lifecycle remains a project convention (n=1) pending a second exercising commission.

## Disposition requested

One Decision Authority act: **adopt / defer / reject** this candidate as an ES-006 constitutional amendment at the next retrospective or ARB session (it can join the prepared five-vote session as an additional item, or wait). Until ruled, this document binds nothing.

---

# Addendum — three-layer refinement (same commission, 2026-08-05)

**Filed as an extension, not a second document** (ES-005.4: consume or extend, never create a second home).

## The refinement

Knowledge sits in three layers, and **no layer bypasses the one above it**:

```
KnowledgeOS (Engineering Standards)
        ▲  Decision Authority
Proven Candidates
        ▲  second independent evidence
Observations (incubation)
        ▲  reflection
Product Engineering Experience  ←  PublicDigit
```

**KnowledgeOS is where ideas graduate, not where they are born.** Ideas are born in product engineering, incubate as observations, and graduate only on evidence plus a ruling.

## Lifecycle-per-artifact-class (candidate expression)

| Artifact | Lifecycle |
|---|---|
| Product feature | Discovery → Design → Implementation → Test → Release |
| ADR | Proposal → Review → Accepted/Rejected |
| Observation | Observation → Evidence → Candidate → Decision |
| KnowledgeOS rule | Candidate → Decision Authority → Standard |
| Architecture | Discovery → Review → Certification |

Different artifacts · different lifecycles · same discipline.

## Two corrections recorded at filing (both narrow the candidate)

**C-1 — "PKS = the incubation layer" is NOT this repository's definition.** The placement registry (`docs/knowledge/schema/documentation-placement.yaml`) defines `pks` as **"PKS — Product Knowledge System"**, a **product root** peer to `publicdigit` and `knowledgeos`. Engineering-process observations do in fact accumulate in `docs/pks/` (8+ artifacts since 2026-08-01, including this one), so a **dual use exists in practice with no ruling behind it**. ⚠️ **This candidate therefore presupposes the answer to the already-open `OQ-5`** (*is KnowledgeOS the Engineering Platform or a distinct prospective product?* — `ENG-009` is blocked on it). **The layer model must not be adopted ahead of OQ-5**; if adopted, the incubation namespace has to be named deliberately (`docs/pks/` ruled as dual-use, or a distinct observations namespace declared under the folder rule).

**C-2 — the lifecycle principle is ALREADY ISSUED, so it is a pointer here, not a new rule.** *"Every artifact has a lifecycle appropriate to its level"* is **ES-004.3 Artifact Lifecycle Consistency** (R-41, 2026-07-30) — which already carries the role-based artifact table and the Draft → Authorized → Executing → Accepted → Closed work-plan lifecycle. The table above is a **candidate extension of ES-004.3's coverage to additional artifact classes**, not a new principle. Similarly the promotion chain restates **ES-006.1**, and "no layer bypasses the one above" restates **ES-006.3** (*research dossiers are input-only; never architecture until promoted*).

## What remains genuinely new after both corrections

1. The **three-layer picture as a single mental model** (product generates · observations incubate · standards graduate · Decision Authority gates).
2. **Extending ES-004.3's role table** to observation / KnowledgeOS-rule / architecture classes.
3. The cultural counter-incentive: ***most commissions must end "nothing general learned" — that is the healthy outcome***, guarding against every commission being expected to invent a principle.
4. The explicit statement that **KnowledgeOS never goes looking for knowledge** (§heart-sentence above).

## Disposition (unchanged, now with a dependency)

Still one Decision Authority act — **adopt / defer / reject** — but the ordering is now explicit: **OQ-5 must be answered first** if the layer model is to be adopted as constitutional text. The heart-sentence and the "most days: no" counter-incentive are **independent of OQ-5** and could be ruled separately. **Nothing here binds until ruled.**

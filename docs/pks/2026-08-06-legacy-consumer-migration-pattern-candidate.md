# PKS Observation — Legacy Consumer Migration Pattern (engineering-pattern candidate)

**Date:** 2026-08-06 · **Source:** `PBDIGIT-48` discovery → `PBDIGIT-58` epic (election state)
**Classification:** KnowledgeOS Candidate (operating-loop phases 13–14) — **FILED, NOT ADOPTED**
**⚠️ PLACEMENT: `PENDING` — acknowledged, escalated, not hidden.** `scope: cross-product · maturity: research`; the resolver returns **`PENDING — placement unruled (rule: cross-product-research, ref: ADR:OQ-2)`**. Placed in `docs/pks/` on the **same interim, time-bounded exception** already recorded by the three existing candidates. **No new namespace invented** (ES-005.2). Expires when OQ-2/OQ-5 are ruled.

**Why filed and not adopted:** the *problem* shape has n=3 **within one repository**; the *pattern* — the remedy — has been **applied zero times**. `PBDIGIT-58` is authorised-pending, not executed. **A pattern whose first application has not finished is a hypothesis.** Adoption is a Decision Authority act (`R-34`).

---

## Candidate pattern

> **When a domain evolves a new authoritative model, the work that remains is not redesign — it is migrating the consumers that still read the old one.**

### The six steps

| Step | Action | Why in this order |
|---|---|---|
| **1** | **Identify the authoritative model** | Often already declared. **Check before deciding** — the decision may exist in code |
| **2** | **Observe legacy consumers at runtime** | Text search cannot identify consumers of a common field name |
| **3** | **Migrate the highest-risk consumers** | Restores the capabilities that are actually broken, first |
| **4** | **Migrate remaining consumers** | Including the tooling that *writes* the legacy field |
| **5** | **Raise enforcement** | The mechanism certifies completeness — not a review |
| **6** | **Retire the legacy model** | Only once enforcement is clean |

**Step 2 is the step most often skipped, and the one that carries the pattern's value.**

## The distinction the pattern rests on

**A Single-Source-of-Truth problem and a Legacy Consumer Migration problem look identical from a bug report and require different work:**

| | SSOT problem | Legacy consumer migration |
|---|---|---|
| Question | **Who owns the concept?** | **The owner is known — who still reads the old representation?** |
| Work | model the domain, declare an owner | **inventory consumers, migrate them** |
| Risk | designing the wrong owner | **missing a consumer** |
| Domain changes | likely | **none** |

> **Misclassifying the second as the first produces a redesign nobody needed.** In the source case the domain model was already correct and complete; every hour spent debating ownership was an hour not spent finding readers.

## Evidence from the source case

**Step 1 was already done, and nobody had noticed.** The repository contained a `DeprecationPolicy` naming the replacement for each legacy field, a command describing the engine as the **“SSOT engine”**, a deprecation-aware read wrapper, an access guard, a graduated enforcement plan (Levels 0–4) and a divergence detector. **The authority was declared; only the consumers were unmigrated.** The first framing of the discovery — *"four competing authorities"* — was wrong, and it invited a decision that had already been made.

**Step 2 is where the value was.** Three successive text-based inventories each corrected the previous one:

* two consumers confidently reported as *"more serious than the reported symptom"* turned out to read **`ElectionOfficer.status`** and **`election_memberships.status`** — different tables entirely;
* the cause was matching `where('status', 'active')` without checking the query subject;
* **520 candidate statements** existed app-wide for two field names.

> **A column name is not a consumer, and at scale text search cannot tell the difference.**

**Step 5 is what makes completeness checkable.** The enforcement level advancing to strict *without violations* is a stronger claim than any inventory or review — **the mechanism proves the migration, rather than someone asserting it.**

## Why this is not simply "delete the old field"

**Retirement is not behaviour-neutral.** In the source case the legacy field and the authoritative model disagreed in *both* directions: the legacy computation could refuse where the model would permit, and had no representation for a state the model supports. **Deleting first would have changed outcomes silently.** Hence: observe, migrate, enforce, *then* delete.

## Assessment against the four promotion tests

Using `2026-08-05-knowledgeos-distillation-principle-candidate.md`'s tests:

| Test | Verdict |
|---|---|
| **1 Domain independence** | ✅ nothing here is about elections |
| **2 Repository independence** | ✅ applies wherever a newer model coexists with older readers — legacy or greenfield, any language |
| **3 Engineering value** | ✅ it changes the order of work, and in the source case the order changed the outcome |
| **4 Evidence** | ⚠️ **partial.** The *problem* is evidenced three times in one repository; the *remedy* has not been executed once |

**Three of four pass. Test 4 is the reason this is filed rather than proposed for adoption.**

## What this would extend, if ever promoted — never a new standard

**`ES-005.4`: consume or extend, never create a second.** This is a clause of the existing Development Discipline rule, not a standard of its own:

* the standing rule already orders `Business → DDD → Architecture → Tests → Implementation` and warns that *"an observation must never silently become architecture"*. **This candidate adds the modernisation case: a newer model must not silently coexist with the older one it replaced.**
* it is the same family as `docs/pks/2026-08-06-consistency-boundary-before-transaction-boundary-candidate.md` — **establish the boundary/authority before changing the mechanism.**

## Explicitly NOT claimed

* **Not that the pattern is proven.** Its first application is authorised-pending. **If `PBDIGIT-58` reveals that observation is impractical or enforcement cannot be raised, the pattern is wrong and must be amended.**
* **Not that the other two instances confirm it.** `PBDIGIT-45` (votes per IP) and `PBDIGIT-49` (voter eligibility) share the *problem* shape; **neither has been migrated**, so they are evidence of recurrence, not of remedy.
* **Not adopted, not a practice, not a standard.** ES-006.1 rung: **observation**.

## Promotion path

```
this observation (problem n=3, remedy n=0)
  → PBDIGIT-58 executed and verified          (remedy n=1)
  → a second domain migrated the same way     (remedy n=2, independent)
  → repeated observation → practice → candidate standard
  → Decision Authority ruling → clause of an EXISTING standard
```

**No promotion action is requested.** The next legitimate step is **executing `PBDIGIT-58` and recording whether the pattern held.**

---

**Traceability:** `docs/publicdigit/backlog/PBDIGIT-48-election-state-has-four-representations.md` (the discovery, incl. the corrected framing and the withdrawn claims) · `docs/publicdigit/backlog/PBDIGIT-58-complete-legacy-election-state-migration.md` (the first application) · `PBDIGIT-47` (the symptom) · `PBDIGIT-45` · `PBDIGIT-49` (same problem shape, unmigrated) · `docs/pks/2026-08-06-consistency-boundary-before-transaction-boundary-candidate.md` · `docs/pks/2026-08-05-knowledgeos-distillation-principle-candidate.md` (the four tests) · ES-005.4 · ES-006.1 · R-34 · placement `PENDING` per ADR:OQ-2

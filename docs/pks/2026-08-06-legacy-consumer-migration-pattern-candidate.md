# PKS Observation — Legacy Consumer Migration Pattern (engineering-pattern candidate)

**Date:** 2026-08-06 · **Source:** `PBDIGIT-48` discovery → `PBDIGIT-58` epic (election state)
**Classification:** KnowledgeOS Candidate (operating-loop phases 13–14) — **FILED, NOT ADOPTED**
**⚠️ PLACEMENT: `PENDING` — acknowledged, escalated, not hidden.** `scope: cross-product · maturity: research`; the resolver returns **`PENDING — placement unruled (rule: cross-product-research, ref: ADR:OQ-2)`**. Placed in `docs/pks/` on the **same interim, time-bounded exception** already recorded by the three existing candidates. **No new namespace invented** (ES-005.2). Expires when OQ-2/OQ-5 are ruled.

**Why filed and not adopted:** the *problem* shape has **n=4 within one repository** (see §Evidence count); the *pattern* — the remedy — has been **applied zero times**. `PBDIGIT-58` is authorised-pending, not executed. **A pattern whose first application has not finished is a hypothesis.** Adoption is a Decision Authority act (`R-34`).

---

## Candidate pattern

> **When a domain evolves a new authoritative model, the work that remains is not redesign — it is migrating the consumers that still read the old one.**

### The sentence the pattern reduces to

> **Move consumers of the business capability from the legacy representation to the authoritative representation.**

**Every clause is load-bearing:**

| Clause | Why it is there |
|---|---|
| *consumers* | the unit of work — not fields, not tables, not the schema |
| *of the business capability* | anchors the migration in **what the system does for someone**, so slices are named by capability restored rather than by file touched |
| *from the legacy representation to the authoritative representation* | both are **representations**. Neither is the concept. **The capability is what matters; the representation is implementation** |

**It generalises without modification, and the generalisation is the test of whether this belongs in KnowledgeOS at all:**

| Migration | Move… | not… |
|---|---|---|
| Authentication | **consumers** | passwords |
| Payments | **consumers** | tables |
| Membership | **consumers** | entities |
| Audit | **consumers** | events |
| Election state *(the source case)* | **consumers** | columns |

**And the wording is part of the pattern.** Say *"migrate the legacy consumers"*, never *"replace the legacy fields"*: the fields are persistence, and naming them as the work invites someone to begin by dropping a column. **The unit of work is a consumer of a business capability.**

### The Definition of Done follows from that

| Wrong target | Right target |
|---|---|
| *the legacy field is removed* | **zero production readers of the legacy representation** |

**They are not the same, and the order is not interchangeable.** Remove the field while twenty consumers still read it and production breaks. **Reach zero readers first and removal is nearly trivial** — which is why the target is a property of the consumers, and retirement is a consequence.

### The six steps

| Step | Action | Why in this order |
|---|---|---|
| **1** | **Identify the authoritative model** | Often already declared. **Check before deciding** — the decision may exist in code |
| **2** | **Observe legacy consumers at runtime — and expect to DISCOVER HIDDEN ONES** | Text search cannot identify consumers of a common field name. **Demonstrated: one login request found a decision site three static passes had missed, in a Critical capability** |
| **3** | **Migrate complete CAPABILITIES, never individual call sites** | A half-migrated capability is a capability with two behaviours. **The capability is the migration boundary** |
| **4** | **Migrate remaining consumers** | Including the tooling that *writes* the legacy field |
| **5** | **Raise enforcement** | The mechanism certifies completeness — not a review |
| **6** | **Retire the legacy model** | Only once enforcement is clean |

**Step 2 is the step most often skipped, and the one that carries the pattern's value.**

⚠️ **A caution the source case produced immediately: step 3 may reveal that migration is not behaviour-preserving.** If the authoritative model and the legacy representation *disagree for existing data*, then migrating **is** a behaviour change, and *"preserve behaviour first"* cannot be satisfied — it needs a product decision, not more care. **Establishing that is legitimate output from a migration slice: a decision rather than a diff.**

### The Legacy Compatibility Adapter — the nuance the pattern must not blur

**Steps 3–4 rarely happen in one change.** A **Legacy Compatibility Adapter** — presenting the authoritative model in the legacy shape, so unmigrated consumers keep working — is legitimate and usually necessary. *(Named for the Adapter pattern deliberately: it adapts a new model to an old interface, for a bounded period.)*

**It is separated from permanent synchronisation by one thing only — an exit condition:**

| | Legacy Compatibility Adapter | Permanent synchronisation |
|---|---|---|
| Ends when | **no consumer reads the legacy representation** | never |
| Consumer count | shrinking, measured | stable, unmeasured |
| Result | the legacy representation is retired | two representations, indefinitely |

**Three obligations, or "temporary" becomes permanent by default:** a **named owner**, a **review date**, and a **visible, falling reader count** — plus removal as an explicit **task**, never a lapse.

> **An adapter with no owner, no review date and no falling reader count is permanent synchronisation with better manners** — and permanent synchronisation is the condition this pattern exists to end.

⚠️ **The loophole that must be closed in words, because it will otherwise be argued:**

> **The adapter exists solely to protect *existing* legacy consumers during migration. It must never justify creating a new legacy consumer. Every new capability must consume the authoritative model directly.**

**Otherwise the adapter becomes a permission slip** — *"the field is still maintained, so I'll read it just this once"* — and each such once moves the reader count the wrong way.

## The distinction the pattern rests on

**A Single-Source-of-Truth problem and a Legacy Consumer Migration problem look identical from a bug report and require different work:**

| | SSOT problem | Legacy consumer migration |
|---|---|---|
| Question | **Who owns the concept?** | **The owner is known — who still reads the old representation?** |
| Work | model the domain, declare an owner | **inventory consumers, migrate them** |
| Risk | designing the wrong owner | **missing a consumer** |
| Domain changes | likely | **none** |

> **Misclassifying the second as the first produces a redesign nobody needed.** In the source case the domain model was already correct and complete; every hour spent debating ownership was an hour not spent finding readers.

## The Legacy Modernization Principle — the sequence the pattern implies

**The six steps reduce to one ordering, and the ordering is the whole discipline:**

```
        Authority  ->  Consumers  ->  Persistence

   1. establish the authoritative model
   2. migrate the consumers
   3. retire the persistence
```

**Never the reverse:**

```
        Persistence  ->  Authority          <-  wrong
```

**Reasoning from persistence is what produces the classic failures:** dropping a column to force callers to change, synchronising two representations because both "exist", or debating which *table* is right when the question is which *model* is authoritative. **Each of those starts at step 3 and works backwards.**

⚠️ **In the source case step 1 was already complete and nobody had noticed** — the authority was declared in code, with per-field replacements and an enforcement plan. **The first framing still opened a debate about ownership.** So the principle's first instruction is not "decide the authority" but **"check whether it has already been decided."**

## Evidence from the source case

**Step 1 was already done, and nobody had noticed.** The repository contained a `DeprecationPolicy` naming the replacement for each legacy field, a command describing the engine as the **“SSOT engine”**, a deprecation-aware read wrapper, an access guard, a graduated enforcement plan (Levels 0–4) and a divergence detector. **The authority was declared; only the consumers were unmigrated.** The first framing of the discovery — *"four competing authorities"* — was wrong, and it invited a decision that had already been made.

**Step 2 is where the value was.** Three successive text-based inventories each corrected the previous one:

* two consumers confidently reported as *"more serious than the reported symptom"* turned out to read **`ElectionOfficer.status`** and **`election_memberships.status`** — different tables entirely;
* the cause was matching `where('status', 'active')` without checking the query subject;
* **520 candidate statements** existed app-wide for two field names.

> **A column name is not a consumer, and at scale text search cannot tell the difference.**

**Step 5 is what makes completeness checkable.** The enforcement level advancing to strict *without violations* is a stronger claim than any inventory or review — **the mechanism proves the migration, rather than someone asserting it.**

## Two corollaries added 2026-08-06 (from `PBDIGIT-60`) — same pattern, sharper failure modes

**Corollary 1 — new functionality gets built against the deprecated representation.**

> **During a legacy migration, defects arise not because two representations exist, but because new functionality is implemented against the deprecated one instead of the authoritative one.**

Coexistence is the *intended* transitional state; it is not the defect. The defect is that the deprecated representation stays **writable and welcoming**, so the next feature reaches for it — and each such feature adds a consumer to the set the migration must move. **A migration that does not close the deprecated representation to *new* writers is not converging.** *(Observed: an "Unpublish Results" control written against the legacy boolean, after the authority had moved to the timestamp and the lifecycle engine.)*

**Corollary 2 — a migration can inherit a conflation.**

> **Before deciding where a legacy field's authority moved, establish how many business concepts it encodes.**

`results_published` encoded **two**: *the election reached publication* (a constitutional fact) and *the public results page is reachable* (an operational control). Publication set both in one statement, so nothing ever revealed the seam. The migration moved the field's authority to the lifecycle — correct for the first concept, and it left the second **with no authority at all**, hence unimplementable except by writing the deprecated field.

**Diagnostic value: this explains a class of "the migration broke a feature" reports.** If a capability can only be expressed by writing the legacy field, that is evidence the legacy field carried **more than one concept** and only one of them was migrated. **Symptom → look for a missing capability, not a missing column.**

### Evidence count, stated honestly

| | Count |
|---|---|
| **Problem shape**, documented in this candidate | **n=4** — `PBDIGIT-48` (election state) · `PBDIGIT-45` (votes-per-IP: env → snapshot) · `PBDIGIT-49` (voter eligibility) · `PBDIGIT-60` (results publication/visibility) |
| **Conflation** specifically (Corollary 2) | **n=1** — `PBDIGIT-60`. `PBDIGIT-59` (`end_date` vs `voting_ends_at` = scheduled vs actual) is a **candidate second instance**, but it is undecided, so it is not counted |
| **Remedy applied** | **n=0** — unchanged. `PBDIGIT-58` is authorised, not executed |

**The Product Owner additionally cites the Working Organisation writers as an instance.** It is **not counted above**, because no ticket in `docs/publicdigit/backlog/` records that inventory, and an uncited instance cannot carry promotion weight (`ES-006.1`). If it is real it should be pointed at a ticket first.

> **Four sightings of a problem is not four confirmations of a remedy.** The rung is unchanged: **observation**.

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

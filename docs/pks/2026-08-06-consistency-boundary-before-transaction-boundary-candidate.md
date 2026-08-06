# PKS Observation — Consistency boundary before transaction boundary (engineering-principle candidate)

**Date:** 2026-08-06 · **Source:** `PBDIGIT-38` — a rejected patch, and the discovery that replaced it
**Classification:** KnowledgeOS Candidate (operating-loop phases 13–14) — **FILED, NOT ADOPTED**
**⚠️ PLACEMENT: `PENDING` — non-compliance acknowledged, escalated, not hidden.** `scope: cross-product · maturity: research`; `php scripts/doc-placement.php --scope=cross-product --maturity=research` returns **`PENDING — placement unruled (rule: cross-product-research, ref: ADR:OQ-2)`**. Placed in `docs/pks/` on the **same interim, time-bounded exception** already recorded by `2026-08-05-knowledgeos-distillation-principle-candidate.md`, `2026-08-06-discovery-does-not-design-candidate.md` and `2026-08-01-knowledgeos-maturity-structure-assessment.md`. **No new namespace invented** (ES-005.2). Expires when OQ-2/OQ-5 are ruled.

**Why filed and not adopted:** n=1. One defect, one repository, one occurrence. ES-006.1 requires repeated observation before a practice; adoption is a Decision Authority act (**R-34**), never engineering self-promotion.

---

## Candidate principle

> **Before changing transaction management, discover the business consistency boundary. Transaction boundaries should *implement* consistency boundaries — not define them.**

## The evidence — a wrong fix, caught by asking the boundary question first

`PBDIGIT-00`'s end-to-end walk found that a vote could not be saved: an `INSERT` into an audit table violated a `NOT NULL` constraint, which aborted the enclosing PostgreSQL transaction (`25P02`), which killed the vote.

**Engineering's first move was the wrong one.** It proposed a nested transaction (SAVEPOINT) so the audit failure could not poison the vote transaction. The reasoning ran:

```
PostgreSQL aborts the transaction  →  SAVEPOINT isolates it  →  Laravel supports nested transactions  →  done
```

**The Product Owner rejected the patch** — not because the diagnosis was wrong (it was correct) but because it **chose an implementation before establishing which writes belonged in the transaction at all.**

The discovery that followed inverted the order:

```
What business invariant is at stake?
  →  Is the audit record inside the Vote's consistency boundary?
  →  What transaction boundary does that consistency boundary imply?
  →  Which implementations satisfy it?
```

## What the reordering actually produced

**It was not a tidier write-up. It changed the answer.**

| | |
|---|---|
| **The boundary question** | Is the security event part of the Vote? **No** — and the code proved it: the record is *"fire-and-forget"*, has independent 730-day retention, deliberately stores no voter linkage, and — decisively — **ALLOW events are ~10 % sampled.** A record intentionally discarded nine times in ten cannot participate in a transactional invariant. |
| **The consequence nobody had seen** | Asking where the boundary lay exposed **nine `rollBack()` calls after the audit write**. So every *rejected* vote had its security event rolled back with it — while the recorder's stated policy was *"DENY events: always record immediately."* **The audit trail was guaranteed absent exactly for the events it existed to capture.** |
| **The effect on the fix** | That second defect made the invariant **two-directional**: an audit failure must not block a vote, **and** an audit record must survive a vote rollback. **The proposed SAVEPOINT satisfies the first and fails the second** — a savepoint rolls back with its parent. So does the obvious alternative, an after-commit listener: denials never commit, so they would never be recorded. |

> **Both cheap fixes failed the half of the invariant that was invisible until the boundary was investigated.** The patch would have shipped as a fix while leaving the audit trail silently broken.

**That is the whole argument for the principle.** Not that boundary-first is tidier — that boundary-first is what made the difference between a fix and a plausible-looking half-fix.

## The generalisable shape

**The failure mode has a name worth carrying:** *treating a transaction boundary as an implementation detail of the database rather than an expression of a business rule.* Under that assumption, "which statements are in the transaction" looks like a technical question, so the technically smallest change wins — and a technically smallest change cannot be evaluated against an invariant nobody has stated.

**Diagnostic questions the principle yields, in order:**

1. What business invariant requires these writes to succeed or fail together?
2. Which of these writes is an **observation** of the operation rather than a **part** of it? *(A sampled, independently-retained, deliberately-unlinked record is an observation.)*
3. Does the invariant run in **both** directions — must A survive B's failure, as well as B survive A's?
4. **Only now:** which mechanism implements that?

## Assessment against the four promotion tests

Using `2026-08-05-knowledgeos-distillation-principle-candidate.md`'s tests:

| Test | Verdict |
|---|---|
| **1 Domain independence** | ✅ nothing here is about elections, voting or audit specifically |
| **2 Repository independence** | ✅ applies to legacy and greenfield, DDD and CRUD, monolith and microservices — anywhere transactions and business rules coexist |
| **3 Engineering value** | ✅ it changes the order of work, and in the source case that order changed the outcome |
| **4 Evidence** | ✅ produced by a real rejected patch, not by speculation |

All four pass — which yields a **candidate, and nothing more.**

## What this would extend, if ever promoted — never a new standard

**ES-005.4: consume or extend, never create a second.** This is a clause of the **existing** Development Discipline rule, not a new standard:

- The standing rule (`.claude/CLAUDE.md`) already orders `Business → DDD → Architecture → Tests → Implementation`, and already says **"resolve ownership of every invariant *before* protecting it."** **This candidate is that sentence applied to transactions:** a transaction *is* a protection mechanism, so its scope cannot be chosen before the invariant it protects is known.
- The **DDD Tactical Governance Principles** own aggregate and consistency-boundary modelling; this is the transaction-management corollary.
- The **Layer Verification Rule** (`engineering/knowledge/methodology/Layer_Verification_Rule.md`, PROPOSED) already distinguishes *invariant* (ARB) from *mechanism* (engineering, the substitutable level). **A SAVEPOINT is a mechanism; "the audit must survive a rollback" is an invariant.** The source case is a clean instance of choosing a mechanism before its invariant was known — and therefore also **weak evidence for that rule's usefulness**, which is recorded there, not claimed here.

## Explicitly NOT claimed

- **Not that SAVEPOINTs are wrong.** They are correct where the consistency boundary genuinely nests. The defect was choosing one *before* knowing whether it did.
- **Not that the original diagnosis was poor.** *"Catching the exception cannot recover an aborted PostgreSQL transaction"* was correct and necessary. **A correct diagnosis does not license the first remedy that follows from it.**
- **Not adopted, not a practice, not a standard.** ES-006.1 rung: **observation**.

## Promotion path

```
this observation (n=1) → second independent occurrence → repeated observation
    → practice → candidate standard → Decision Authority ruling → clause of an EXISTING standard
```

**No promotion action is requested.** The next legitimate step is a second, independent occurrence.

---

**Traceability:** `docs/publicdigit/backlog/PBDIGIT-38-a-vote-cannot-be-saved.md` §DISCOVERY D-1…D-4 (the rejected patch, the sampling argument, the nine rollbacks, the eliminated options) · `docs/publicdigit/backlog/PBDIGIT-00-verify-the-journey-end-to-end.md` (where the defect surfaced) · `app/Application/Election/Security/SecurityEventRecorder.php:15-19` · `app/Http/Controllers/Demo/DemoVoteController.php:1456,1493,1785` · `.claude/CLAUDE.md` §Development Discipline · `engineering/knowledge/methodology/DDD_Tactical_Governance_Principles.md` · `engineering/knowledge/methodology/Layer_Verification_Rule.md` (PROPOSED) · `docs/pks/2026-08-05-knowledgeos-distillation-principle-candidate.md` (the four tests) · ES-005.4 · ES-006.1 · R-34 · placement `PENDING` per ADR:OQ-2

# S2-F010 · Four question-types are being answered under one heading

**Class:** CHALLENGE (systematic, affects both `S1-F003` and `S1-F004`)
**Status:** OPEN · review record only · **not adjudicated**

---

| | |
|---|---|
| **Source Session-1 artifacts** | `session1/S1-F003` · `session1/S1-F004` |
| **Commissioned question** | has Session 1 conflated Kernel **membership**, **boundary**, **mechanism**, and **semantics**? |
| **Lens** | DDD · Vocabulary · Boundary |

---

## FINDING

**Yes — and systematically, in both artifacts.** The four question-types are distinct and are answered under one heading in each.

### `S1-F003`, classified by question-type

| Content | Question-type |
|---|---|
| A's list — authority mechanics, evidence mechanics, lifecycle, provenance | **MEMBERSHIP** (which concerns are protected) |
| B's list — Observation, Rule-evaluation protocol, Assessment, Result | **MECHANISM** (what the runtime does) |
| `ChangeSet` — *"separates event detection from observation execution"*, source-agnostic | **MECHANISM** |
| `Rule Port` — kernel holds the protocol, EKS/PKS supply the rules | **BOUNDARY** (what crosses) |
| Method finding — *"what is the actual relationship between the existing EKS runtime, the AI Engineering Platform, PKS, and the proposed kernel?"* | **BOUNDARY** |
| *"`.claude` definitely should not become the kernel"* | **SEMANTICS** (what the word denotes) |

**The set intersection in S1-F003 is computed across MEMBERSHIP and MECHANISM.** That is the type error recorded in `S2-F007`, now shown to be an instance of a general pattern rather than a one-off.

### `S1-F004`, classified by question-type

| Finding | Question-type |
|---|---|
| F1 — no established *"knowledge kernel"* category | **SEMANTICS** |
| F2 — determinism (A: *"deterministic runtime"*; B: *systems lack deterministic execution*) | **MECHANISM** |
| F3 — *"epistemic accountability over time"* as the trustworthy core | **PURPOSE / DOMAIN RESPONSIBILITY** |
| F4 — Unix lessons: stable primitives · minimal core · mechanism/policy separation | **BOUNDARY** + **MECHANISM** |

Four findings, four different question-types, presented as four findings **about one thing**.

## EVIDENCE

The pattern has a cause visible in the artifacts themselves: **Session 1's registers are organised by source document, not by question-type.** That is the commissioned method — *document → summary → extraction → record* — and it is the right method for breadth. But it means findings of different types accumulate under one document heading, and **cross-finding operations then run across types without a type check**:

- an **intersection** across membership and mechanism (`S1-F003`)
- a **contradiction** between a mechanism claim and a semantics claim about category existence (`S1-F004` F1 vs F2, per `S2-F009`)
- a **convergence count** mixing purpose evidence with deficiency measurement (`S1-F004` F3, per `S2-F012`)

## WHY IT MATTERS

Every one of the three defects already recorded in this register — `S2-F007` (ill-typed intersection), `S2-F009` (F1 undercuts F2), `S2-F012` (deficiency counted as thesis-support) — **is downstream of this single missing distinction.** They are not three independent errors; they are one omission expressing itself three times.

That reframes the remedy. Individually the three look like three corrections to three artifacts. Together they suggest **one cheap structural addition**: a question-type field on each finding, so that any operation combining findings must first show they are of the same type.

## WHAT THIS DOES NOT CLAIM

- It does **not** claim Session 1's method is wrong. Document-by-document extraction with a stop rule is what was commissioned, and breadth requires it.
- It does **not** claim any individual finding is false. Each may be sound within its own type.
- It does **not** propose the four-way taxonomy as architecture. **Membership · boundary · mechanism · semantics** are used here as *analytical* categories for sorting research findings. They are not Kernel elements and confer nothing.

## POSSIBLE IMPACT

Extraction methodology. Suggested for Session 1 to accept or reject: record a **question-type** per finding, and require a type match before computing an intersection, a contradiction, or a convergence count across findings.

## PROVENANCE

Derived solely from `session1/S1-F003` and `session1/S1-F004`, using their own quotations. No corpus document read.

## STATUS

**OPEN — CHALLENGE.** Session-1 artifacts not modified.

# Batch 001 — Experience Analysis & Optimization Targets

Written after executing F0001–F0010 twice: once naively, once against the amended protocol with P3A comparison.

---

## 1. Where the effort actually went

| Activity | Share of effort | Protocol's share of text |
|---|---|---|
| Reading + understanding the 10 files | **~70%** | ~10% (§2, §0E.5) |
| Deciding identity / relationships (the hard judgment) | **~20%** | ~25% (§19A, §7, §31) |
| Writing records into the prescribed formats | **~10%** | **~65%** (§5, §9, §13, §19A/B/C, §36A, §41…) |

**The protocol's text budget is inverted relative to where the work is.** That is not automatically wrong — recording format is what makes the work auditable and reproducible, and it does not need to be re-derived per file. But it becomes wrong at scale: the reading cost is irreducible and roughly constant per file, while the recording cost is *also* per-file and is the part the protocol keeps growing.

## 2. What produced value (measured, not asserted)

| Rule | Concrete error it prevented in these 10 files |
|---|---|
| **§2 complete-file** | **Decisive.** The 3 wrong P3A dates are all dates *cited inside* the documents. Any snippet/regex/embedding reader picks them up. Only reading the whole file reveals which date is the document's own commission date. This single rule is the difference between a correct and an inverted chronology. |
| **§19A identity test** | Kept `C-1`'s four meanings apart; kept `TH-0003` at `UNCERTAIN` instead of merging P1/P2 with F0010's blocker classes on structural resemblance. |
| **§1 P3A ≠ truth** | Directly vindicated: P3A's dates would have placed F0010 first of ten — *before the document it refutes and cites as input*. |
| **§28 no silent repair** | Forced `G-0002` to be `POTENTIAL_VALIDATION_ISSUE` rather than "the tier table is wrong". |
| **§33 completeness bar** | All 4 threads correctly `historically_complete: false`. |
| **§37 check 4** | Caught a real dangling `DI-####` reference in my own output. |

## 3. What cost effort without proportionate return

### 3.1 The 30-step pipeline is not a procedure

I did not perform 30 discrete acts per file. Steps 4–10 (`IDENTIFY DEFINITIONS` / `ASSUMPTIONS` / `PREMISES` / `CLAIMS` / `MATHEMATICAL OBJECTS` / …) are **not separable operations** — they are one reading pass, described seven ways. §0E was added precisely because a step list is not how research is done, yet §9 still presents 30 steps as a sequence.

**They work well as an audit checklist** ("did I extract assumptions?") and badly as an execution order.

### 3.2 Artifact sprawl

§41 specifies 25+ artifacts. For this batch:

| Outcome | Artifacts |
|---|---|
| **Carried real signal** | FILE-REGISTRY · VERIFIED-EDGES · THEORY-OBJECTS · THEORY-THREADS · GAPS · CONTRADICTIONS · SOURCE-LOCAL-IDENTIFIERS · INTRA-FILE-REVISIONS · P3A-COMPARISON · ARCHITECTURE-OBJECTS · DERIVATION-INSTANCES · CORPUS-INDEX |
| **Would have been empty** | BRANCHES · MERGES · CONTINUITY · IMPLEMENTATION-READINESS · PROPOSITIONS · ASSUMPTIONS · DEFINITIONS · CANDIDATE-EDGES · SEQUENTIAL-EDGES · CANDIDATE-THEORY-OBJECTS |

Ten of ~22 would be empty files — **not because the pass was shallow, but because this corpus window genuinely contains no branches, no merges, and no candidate edges** (§8's candidate layer degenerates when every edge is explicitly stated). Two of the three most valuable artifacts I produced (`SOURCE-LOCAL-IDENTIFIERS`, `INTRA-FILE-REVISIONS`) **are not in §41's list at all**.

### 3.3 Over-specified schemas

- §0E.4 Level 2 defines **12** quality dimensions; **6** carried all the signal, 6 were `NOT_APPLICABLE` on every file.
- §9's File Reconstruction Record has ~40 fields; a large minority were empty for every file in the batch.

### 3.4 One event, three records

F0008's `F-3` refutation is legitimately recordable as an intra-file revision (`IFR-0003`), a contradiction (`C-0003`), **and** a verified edge (`R-0002`). I wrote all three with cross-references. At 3,000-file scale this triples the ledger for the corpus's most common and most valuable event type.

## 4. The optimization thesis

> The protocol's safeguards are earning their keep and must not be weakened. Its **recording surface** is what needs to shrink — and it should shrink by becoming *conditional on what the corpus actually contains*, not by lowering any standard.

Concretely: a **mandatory core** of records every file produces, plus **conditional** records created only when the corpus yields that content, plus an explicit statement that an artifact absent for lack of content is a valid result, not an incomplete one.

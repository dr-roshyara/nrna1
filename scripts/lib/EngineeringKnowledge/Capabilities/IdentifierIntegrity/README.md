# CAP-001 — Identifier Integrity

> **The template for every future Engineering Knowledge capability.**
> A new capability should be creatable by copying this structure, replacing only the
> domain concepts, and leaving the architecture unchanged.

| | |
|---|---|
| **Capability** | Engineering Knowledge Validation — **CAP-001: Identifier Integrity** |
| **Status** | ✅ **REALIZED** — vertical slice complete. ⏳ **Operationally: initial evidence only** |
| **Frozen** | **except for bug fixes.** New validation concerns become CAP-002+, never additions here |

---

## 1. Purpose

**Protect the integrity of governed identifiers.**

Named for the **engineering responsibility it protects**, not the mechanism that protects it.
*Validation is one behaviour; reporting, diagnostics and metrics may follow. The name must not
constrain them.*

## 2. Business rule

> ### **PMR-10 — an identifier must be checked for collision before it is minted.**

**DP-1**, the policy this capability executes: *every identifier shall be unique within its
register(ns), and checked before minting.*

⛔ **The capability executes this policy. It does not own it.**

## 3. Architecture

```
CLI  →  Infrastructure  →  Application  →  Domain  →  Shared
```

⛔ **No arrow runs the other way.** `Shared` must not know this capability exists.

```
Capabilities/IdentifierIntegrity/
├── README.md
├── Domain/           Identifier · IdentifierSeries · SeriesContents
│                     IdentifierPolicy (DP-1) · AssessesIdentifierIntegrity
├── Application/      SeriesContentsReader (PORT) · ValidateIdentifier
├── Infrastructure/   GovernedRegisterMap · MarkdownSeriesContentsReader · CliOutputFormatter
└── Tests/{Domain,Application,Infrastructure}/
```

**Shared:** `Verdict` (the closed vocabulary) · `Assessment` (verdict + evidence).

**Constraints binding any change here** — *stated as rules, not as history:*

| ⛔ | Rule |
|---|---|
| **No new source of truth** | governed registers are read, never replaced (**AP-4**) |
| **No cached index consulted for a decision** | read the registers themselves (**DR-1 · AP-2**) |
| **Criteria are read-only** | no numbering scheme is defined, amended or versioned here (**AP-7 · DR-4**) |
| **Closed verdict vocabulary** | `PASS AFTER CORRECTION`, `EMERGENT`, `CERTIFIED` are never emitted (**AP-8 · DR-8**) |
| **Fail closed** | absence of evidence is never `PASS` |
| **Prevents, never repairs** | existing collisions are uncurable — identifier stability forbids renaming (**M4**) |
| **Not a bounded context** | this is a capability (**AD-1 §6.2**) |

## 4. Operational Usage

**Run before minting any new identifier.**

```bash
php scripts/identifier-check.php R-72          # may I mint this?
php scripts/identifier-check.php --audit R     # state of a whole series
php scripts/identifier-check.php --series      # which register(ns) are governed
```

Adding a register is a **configuration edit**, never a code change:
`docs/knowledge/schema/governed-registers.yaml`.

## 5. Expected outcomes

| Verdict | Meaning | Exit | Action |
|---|---|---|---|
| **PASS** | free in its register(ns) | `0` | mint it |
| **FAIL** | already minted there | `2` | ⛔ **choose another** — minting would collide |
| **WARN** | cited in the corpus but never minted | `1` | ⚠️ **a human must dispose it.** Either the citations expect this subject, or a **reservation lapsed** and reuse is correct. **The tool cannot tell which** |
| **INCONCLUSIVE** | the series has no governed register | `1` | ⚠️ the tool cannot help; check by hand |

⛔ **`INCONCLUSIVE` is not a tool failure.** It is the honest answer when no criterion exists,
and its frequency measures the missing canonical register list.

## 6. Operational Baseline

| | |
|---|---|
| **Date** | 2026-08-02 |
| **Status** | **First implementation.** Repository state measured **before** operational adoption |
| **Commit** | `e0ee93d37` · branch `feature/pb003` · PHP 8.3.24 |

**Measurements**

| Measure | Value |
|---|---|
| Governed series configured | **2** — `R`, `PMR` |
| `R` — minted / cited-but-unminted | **67 / 13** |
| `PMR` — minted / cited-but-unminted | **10 / 0** |
| The 13 | `R-01`..`R-09` *(zero-padding drift)* · `R-68`..`R-71` *(genuinely unminted)* |
| False positives | **0 of 13** *(first run reported 42; 29 traced to one incomplete register entry)* |
| Tests | **36 passing / 55 assertions** |
| Execution time | **~0.4 s** per check |

**Repository findings — surfaced by running, not by reading**

- stale `R-30` register reference
- `R` register(ns) split across two files
- `ADR` namespace collapses four distinct registers

**Interpretation**

> **These measurements establish the baseline. They do not demonstrate improvement.**
> **Improvement requires future operational comparison.**

| ⛔ Not demonstrated | |
|---|---|
| **Collisions prevented** | **n = 0.** Detection ≠ prevention |
| **Consistency improved** | **nothing corrected.** Measurement ≠ improvement |
| Anything about the product | this capability operates on the knowledge corpus, not the runtime |

> **First prevention evidence** = one recorded instance in which someone ran the check
> before minting, received FAIL or WARN, and **chose a different identifier as a result**
> — date · identifier · verdict · action changed.
> **Until then this is a measuring instrument, not a control.**

## 7. Developer notes

**Ubiquitous language** — *no term is coined here.*

| Term | Meaning |
|---|---|
| **Identifier** | the name by which a governed artifact is cited |
| **Register(ns)** | the identity **namespace unit**. ⚠️ always written `register(ns)` — `register` alone carries three senses |
| **Series** | the prefix identifying a register(ns) — `R`, `PMR` |
| **Minting** | bringing a new identifier into use — the act the business rule governs |
| **Collision** | two artifacts bearing one identifier within one register(ns) |
| **Unminted citation** | cited in the corpus, absent from the register — a **hazard**, not yet a collision |
| **Verdict** | `PASS` · `FAIL` · `WARN` · `INCONCLUSIVE` |
| **Assessment** | a verdict together with its evidence |

**Extending it**

| Point | How |
|---|---|
| a new source of register data | implement `SeriesContentsReader`; **the domain is unchanged** |
| a new output format | add an Infrastructure formatter |
| a refined policy | change `IdentifierPolicy`; the service is untouched |

**⛔ What does not belong here** — a central registry · renumbering or repair · defining a
numbering scheme · a cached index · governance or identifier allocation · generic utilities.
*Anything validating relationships, projections or terminology is **CAP-002+**, not an addition here.*

## 8. Future evolution

| # | Deferred | Why |
|---|---|---|
| **A** | Make the register catalog a **port** (`RegisterCatalog` ← `YamlRegisterCatalog`) so a database, git or graph source satisfies the same contract | one consumer today; the second decides |
| **B** | Split machine-readable configuration from its explanatory commentary | not urgent; the commentary currently records why entries are absent |
| **C** | ⛔ **CAP-001 stops growing** | the reason capability-first organisation exists |

**To create CAP-002:** copy this tree, replace the domain concepts, keep everything else.

---

## 9. Capability Evidence Record

> ### ⭐ **THE CANONICAL RECORD. Counters are DERIVED from it — never maintained beside it.**
>
> **Append one row per real event. ⛔ Do not invent rows. ⛔ Do not summarise instead of recording.**
> *A counter says “3 decisions changed.” **This says WHY** — and the why is what a future capability consumes.*

**What is being measured is a chain, not an activity:**

```
CAPABILITY  →  DEVELOPER BEHAVIOUR  →  ENGINEERING OUTCOME
```

*⚠️ Hence **Capability** Evidence, not generic “operational” evidence — the distinction will matter once CAP-005/006 exist and several capabilities write to records like this one.*

| Date | Capability | Context | Identifier | Verdict | Tool run? | ⭐ Decision changed? | Outcome / why |
|---|---|---|---|---|---|---|---|
| 2026-08-02 | — *(pre-CAP-001)* | Capability catalog labels | `Evidence Integrity` · `Capability Catalog` · `Link Integrity` | collision ×3 of 8 | ⛔ no — manual grep | ✅ **YES ×2** | 2 renamed, 1 qualified. *PMR-10 check performed by hand; the tool did not exist yet* |
| 2026-08-02 | CAP-001 | WP-3A acceptance ruling | `R-67` | would have been **WARN** | ⛔ no — manual | ➖ no | **Reservation for WP-4 authorization had lapsed and was recorded as lapsed.** Reuse was correct. *Observed retrospectively, not run* |

| 2026-08-02 | CAP-001 | WP-4 subdivision ruling | `R-68` | **WARN** *(recorded at baseline, hours earlier)* | ⛔ **NO** | ⛔ **no** | ⚠️ **COLLISION OCCURRED.** Minted for *"WP-4 subdivided"* while the old sense — *ES-005 resolves placement by rule* — remains live in two pre-existing records. **No disposition recorded** |
| 2026-08-02 | CAP-001 | WP-4A acceptance ruling | `R-69` | **WARN** *(recorded at baseline, hours earlier)* | ⛔ **NO** | ⛔ **no** | ⚠️ **COLLISION OCCURRED.** Same pattern; old sense *R-39 is the precedent* |

| 2026-08-02 | **CAP-001** | Labelling 5 principles in the KnowledgeOS Architecture Baseline | `KP-1`..`KP-5` | ⭐ **INCONCLUSIVE** | ⭐ **YES — RUN BEFORE MINTING** | ⭐⭐ **YES** | ⭐ **FIRST DECISION CHANGED BY THE TOOL.** Intent was to mint `KP-1..KP-5`. `INCONCLUSIVE` — *"series 'KP' is not a governed register(ns); absence of evidence is not PASS"* — forced a hand check, which found **no collision** but established that **a fourth ungoverned series would worsen G-1**. ⛔ **Nothing minted; document-local labels `P1`–`P5` used instead** |

**Derived from the rows above — ⛔ recompute, never edit:**

| Derived counter | Value |
|---|---|
| Executions during real minting | ⭐ **1** *(was 0)* |
| ⭐ **Decisions changed BY THE TOOL** | ⭐ **1** *(was 0)* |
| Decisions changed by the **rule**, applied manually | **2** *(catalog labels)* |

⚠️ **The last two lines must never be added together.** *The rule changing behaviour is not the capability changing behaviour — conflating them would credit CAP-001 with work done by hand before it existed.*

---

### ⏳ Architecture restart gate

> ⛔ **No CAP-002. No new capability. No architectural analysis — until this record holds roughly 20–30 rows.**

**At that point, and only then, these questions have answers worth acting on:**

| # | Question the record will answer |
|---|---|
| 1 | Which **WARNs repeat**? |
| 2 | Which checks are **always** manual? |
| 3 | Which **developer decisions** repeat? |
| 4 | Which **invariant** appears repeatedly? |
| 5 | ⭐ What friction is **CAP-001 unable to address**? |

**⭐ Those answers — not brainstorming — define what CAP-002 is.**

### Standing observations

| # | Observation |
|---|---|
| **OE-1** | ⭐ **Minting is entirely manual.** No script, template or gate creates an identifier — a human appends a row to a register. **So CAP-001 can only be adopted by habit, not by mechanism.** *That is the adoption risk, and it is not a defect in the capability* |
| **OE-2** | ⭐ **WARN requires human disposition, not avoidance.** `R-67` was reserved, the reservation lapsed and was recorded as lapsed, and reuse was correct. **A WARN that always meant "choose another" would have given wrong advice.** *Guidance corrected 2026-08-02 — the tool reports the hazard and names both readings; it does not prescribe* |
| **OE-3** | ⚠️ **The rule works manually — INCONSISTENTLY.** `R-67`'s reuse was explicitly disposed (*"reserved… moot"*); `R-68`/`R-69`'s was not. **Same class of act, two different standards, same day** |
| **OE-4** | ⛔ **MANUAL ADOPTION FAILED WITHIN HOURS.** The baseline recorded `R-68`/`R-69` as WARN; both were minted for new subjects the same day **without the tool being run**. *This is the counterfactual TESTED, not reconstructed — the tool's own prior output flagged them* |
| ⭐ **OE-6** | ⭐⭐ **`INCONCLUSIVE` IS THE VERDICT THAT PROVED MOST USEFUL FIRST.** *The first decision the tool changed was changed by **INCONCLUSIVE**, not by FAIL or WARN.* It did not name a collision — it established that **no criterion existed**, which is what stopped a fourth ungoverned series from being created. ⛔ **A tool that only reported collisions would have said nothing here.** *Corroborated independently: an isolated bootstrap of an unrelated product judged the same verdict "exactly correct for a greenfield repository."* |
| **OE-5** | ⭐ **The hazard is created at RESERVATION, not at minting.** `R-67` shows identifiers enter circulation when a commission reserves one. **A check that runs only at mint time is already too late to prevent the citation hazard** |

# Targeted Resolution Report — Step 3

| | |
|---|---|
| **Objective** | Resolve the highest-value open terms by **targeted search**, not by reading more files in sequence |
| **Scope searched** | ⭐ **the whole repository**, not F0001–F0025 — semantically, not by literal string |
| **Result** | **1 open term RESOLVED · 1 candidate CONFIRMED and strengthened · 2 of my own constructions WEAKENED** |

---

## 1 ⭐⭐ `bar` — RESOLVED, and it falsifies the formalization that needed it

### What was searched

Not the string `bar`. The **concept**: promotion threshold · evidence threshold · promotion rule · qualification criterion · `n ≥ 2` · second adopter · "requires BOTH" · ES-006.1 · the promotion ladder.

### What was found

**`ES-006.1 — The Promotion Ladder`** *(ARB, refined 2026-07-11)*, `engineering/governance/ES-006-Engineering-Knowledge-Governance.md`:

> `Research → Pilot → Qualification → Engineering Standard → Stable Engineering Capability`
>
> *"nothing is promoted because it is a good idea; everything is promoted because **operational evidence demonstrated necessity** (the burden-of-proof rule, R-37, applied to promotion)"*

**`ES-003.2 — Score-Persistence Stop`** *(ARB 2026-07-10)*, `engineering/governance/ES-003-Qualification.md`:

> ⛔ *"**Numeric review scores are conversational, never architectural** — they are **NOT persisted** in repository records. Records persist **governance states** (Accepted · Rejected · Deferred · Research question · Evidence required · Stable) plus rationale."*

**`ES-006.3`:** *"the Pattern Evidence Register tracks the four frozen convergence metrics … — **no composite scores**."*
**`ES-004`:** *"**No numeric scores in records** (ES-003.2)."*
**`ES-006.1` maturity vocabulary:** *Evidence = proven · Pattern = proven · Capability = strong hypothesis · Principle = research question.*

### ⭐ The finding

> ## ⛔ **`bar` is not missing. A numeric threshold is FORBIDDEN by an ARB ruling.**

| My assumption in `STR-0001` | What the corpus actually holds |
|---|---|
| `ev: Knowledge → ordered set` | ⛔ no persisted ordering; scores are *conversational, never architectural* |
| a threshold value `bar` | ⛔ **prohibited** by ES-003.2, ES-006.3, ES-004 |
| comparison `ev(k) ≥ bar` | ⛔ replaced by a **discrete governance state** from a closed set of six |

**`STR-0001` imposed a quantitative structure the corpus explicitly refuses.** I searched for a number the governance had already ruled out of existence.

### The corrected shape — evidence-derived, not invented

```
promote(k)  ⟺  qualification_verdict(k) = Accepted  ∧  ∃a: grants(a,k)

qualification_verdict ∈ { Accepted · Rejected · Deferred
                        · Research question · Evidence required · Stable }
```

⛔ **Not adopted.** A **new open question** replaces the old one, and it is sharper:

> ⚠️ **`OT-0001`** — if `qualification_verdict` is itself assigned by the DA/ARB, then **both conjuncts are governance acts**, and *"evidence EARNS"* has no independent formal content. `T-0013`'s central asymmetry may not survive formalization.

**Consequence for `SI-0008`** *(the empty back-edge = `bar = ∞`)*: ⛔ **FALSIFIED.** There is no `bar` to be infinite. The competition `CMP-0001` loses its third reading.

---

## 2 ⭐⭐ `SI-0007` — CONFIRMED, on four independent grounds, and strengthened

The claim was tested against **the actual artifact**, `docs/knowledge/schema/authorities.yaml`.

| Test | Result |
|---|---|
| **Does the two-question header exist?** | ✅ verbatim: *"Authority answers: 'how much should I trust this, and where did it come from?'"* |
| **Does the P/S partition hold against the descriptions?** | ✅ **provenance:** `derived` (*"Derived from an authoritative source"*), `generated` (*"Produced by tooling or AI"*). **standing:** `authoritative` (*"single source of truth"*), `historical` (*"Was true at a point in time"*), `provisional` (*"Carries no authority yet"*) |
| **Is the field single-valued?** | ✅ `knowledge-schema.yaml`: `authority: type: enum` — ⭐ **and the schema demonstrably has `list-enum`, which it uses for `audience`.** Single-valuedness is a *choice*, not a limitation |
| **Can provenance be carried by another field?** | ✅ **No.** Full field set is `knowledge_id · title · knowledge_type · bounded_context · status · authority · audience · owner · reviewers · version · schema_version · tags · last_review`. **No provenance field exists** |
| **Are states excluded by an invariant?** | ⚠️ one exists — `authoritative: single_per_topic: true` — but that constrains the *population*, not the *state space*. It does not explain the missing sixth state |

### ⭐ And a stronger result than I claimed

`authorities.yaml` assigns each value a **`rank: 1–5`**:

```
authoritative 1  ·  derived 2  ·  generated 3  ·  historical 4  ·  provisional 5
```

> **The schema does not merely conflate provenance and standing — it imposes a TOTAL ORDER ACROSS them.** `derived` (provenance) is ranked *above* `generated` (provenance) and *below* `authoritative` (standing), as though the two dimensions were commensurable on one axis.

**F0018 never mentions `rank`.** Its claim — *"the field conflates two questions"* — is weaker than what the artifact shows. `SI-0007` is **CONFIRMED and strengthened**: the worked example (*"derived in provenance AND authoritative in standing, simultaneously"*) requires rank 1 **and** rank 2 in a single-valued enum, which is unsatisfiable.

**Status: `SI-0007` → confirmed. Provenance: artifact-verified, not corpus-reported.**

---

## 3 ⛔ `STR-0003` — WEAKENED by my own test

`check(X, M) → {ALREADY_IN_M, SPLITS_M, GENUINELY_NEW}` fails three formal tests:

| Test | Result |
|---|---|
| **Codomain well-defined?** | ⛔ **No.** `ALREADY_IN_M` collapses **three distinct senses**: *derivable from existing structure* (LG-1) · *distributed across artifacts* (Provenance Domain) · *permitted by an axiom* (nesting) |
| **Outcomes mutually exclusive?** | ⛔ **No.** `PD-3` was `GENUINELY_NEW` **and** corrected the model's partition — simultaneously new and splitting |
| **Total?** | ⛔ **Unsupported.** 5 observations, one author, three days, one programme |

> **Verdict: demote from *total function* to *observed pattern with an ill-defined codomain*.** The generalization was mine and it does not survive scrutiny.

---

## 4 ⛔ `STR-0004` — CATEGORY ERROR, corrected

Tested against the five categories:

| Property | Result |
|---|---|
| Acyclic | holds, n=4 |
| Transitive | ⚠️ **untested** — no chain of length 3 |
| Single superseding record | ⛔ **No** — `LG-1` is superseded by **both** `PM-1` and `PM-2`; supersession **branches** |
| Invariant? | ⛔ **No** — `IFR-0010` is a counterexample **inside the window**: F0019's annotation was rewritten and the original is unrecoverable |

> **Verdict: category 2 — a HISTORICAL PROPERTY of the corpus, with 1 violation in 5.**
> ⛔ Not a logical invariant. ⛔ Not a mathematical structure. **I previously called it a structure. It is a practice.**

---

## 5 Evidence-class discipline

| Class | Instances |
|---|---|
| `SOURCE_REPETITION` | *"0 traversals"* in ≥7 files — ⛔ **one claim copied, not seven observations** |
| `SOURCE_CORROBORATION` | F0018's three prior canon refusals of lifecycle fusion |
| `INDEPENDENT_DERIVATION` | F0015's blind review; F0017's external survey |
| ⭐ `MATHEMATICAL_VALIDATION` | **`SI-0007` — the first item in this project to reach this class** |
| `EMPIRICAL_VALIDATION` | ⛔ **none** |

---

## 6 What this pass changed

| Item | Before | After |
|---|---|---|
| `bar` | open term blocking 6 structures | ⭐ **RESOLVED — a numeric threshold is forbidden** |
| `STR-0001` | F1, awaiting `bar` | ⛔ **wrong shape; corrected form proposed, not adopted** |
| `SI-0008` | L2 hypothesis | ⛔ **FALSIFIED** |
| `SI-0007` | L3 construction | ⭐ **CONFIRMED · artifact-verified · strengthened** |
| `STR-0003` | discovered total function | ⛔ **demoted to observed pattern** |
| `STR-0004` | discovered structure | ⛔ **re-categorized as historical practice** |
| `CMP-0001` | 3 rival readings | 2 — the third is falsified |
| — | — | ⭐ **`OT-0001` opened: does *evidence EARNS* survive formalization?** |

> ⭐ **Targeted search resolved in one pass what "read five more files" would not have touched.** The answer was never in `docs/knowledgeos/` — it was in `engineering/governance/`, outside the registry entirely.

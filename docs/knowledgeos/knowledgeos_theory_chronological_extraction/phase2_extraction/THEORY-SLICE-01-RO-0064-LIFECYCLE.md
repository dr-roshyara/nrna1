# `RO-0064` — what `lifecycle.md` actually means

*Phase 2, job `2C`. ⛔ Slice 01 not edited · no schema changed · `F-b`/`F-c` not chosen.*

| | |
|---|---|
| **Obligation** | **`RO-0064`** — is `generated → authoritative` a real transition, and does it contradict `D4`? |
| **Source** | `docs/knowledge/_meta/lifecycle.md` *(`status: baseline`, `authority: authoritative`)* · `knowledge-schema.yaml` · `scripts/knowledge-lint.php` |
| **Status of source** | ⭐ **the authoritative governance document for this question** — not a draft, not a research artifact |
| **Result** | ⛔⛔ **`D4` is FALSE of the implementation — and the reason is deeper than a mutable field** |

---

## 1 · The seven questions, answered from the source

| # | Question | Answer |
|---|---|---|
| **1** | Is `generated → authoritative` permitted? | ⭐ **YES.** §7: *"AI output is never `authority: authoritative` **until a human review moves it there**"* |
| **2** | Normative, implemented, historical or aspirational? | ⭐ **NORMATIVE.** Stated as a rule in a `baseline`/`authoritative` document, ⭐ **beside a lint-enforced neighbour** |
| **3** | What changes? | ⭐ **the `authority:` field value itself** |
| **4** | Does provenance change? | ⭐⭐ **The historical fact does not. The FIELD does.** *(see §2 — this is the finding)* |
| **5** | Does standing change? | ⭐ **YES** — the artifact becomes the single source of truth for its topic |
| **6** | One dimension or several? | ⭐⭐ **ONE.** §6 settles it |
| **7** | How to read `derived + authoritative`? | ⛔⛔ **The implementation FORBIDS it** — §6 |

## 2 · ⭐⭐⭐ The decisive rule — §6

> **"There shall be exactly ONE `authority: authoritative` document for each governed knowledge topic within a bounded context."**
> **"Everything else on that topic is `derived`, `generated`, `historical`, or `provisional`."**
> ⭐ *"Violations are a lint **error** (`single_authoritative`)."* — ⭐ **confirmed enforced**: `knowledge-schema.yaml:107` severity `error`, implemented at `knowledge-lint.php:519`

### What §6 establishes

⭐ **The five values are MUTUALLY EXCLUSIVE ALTERNATIVES within one topic.** Exactly one artifact is `authoritative`; **everything else** takes one of the other four.

> ### ⛔ **That is unambiguously ONE dimension: a per-topic trust ranking with a unique maximum, machine-enforced.**

⛔ **It is not a product, and it is not a sum of two independent axes.** ⭐ **It is a ranking with a uniqueness constraint at the top.**

## 3 · ⭐⭐ The real finding — the labels are origin-named, the dimension is trust

`F0018` read the five values as answering two questions, because **the labels and descriptions carry origin language**: *"Derived **from** an authoritative source"*, *"**Produced by** tooling or AI"*.

⭐ **But §6 uses those same values as the ranks below `authoritative` on one scale.** And §7 moves an artifact **off** `generated` when a human reviews it.

> ### ⭐⭐⭐ **The five values are a SINGLE TRUST RANKING whose labels are named after origins.**
>
> **That is the conflation — not two dimensions crammed into one field, but ORIGIN-NAMED LABELS SERVING AS TRUST LEVELS.**

**This explains every observation at once:**

| Observation | Explained |
|---|---|
| `rank:` orders all five | ⭐ **coherent after all** — it *is* a trust ranking. ⛔ Still inert, but no longer incoherent |
| `single_authoritative` is lint-enforced | ⭐ a ranking with a **unique maximum** per topic |
| `generated → authoritative` is legal | ⭐ **moving UP the ranking** |
| `F0018` saw two questions | ⭐ the labels *are* origin-named |
| `derived + authoritative` feels natural | ⭐ **semantically it is** — the artifact's origin and its trust genuinely differ |
| …yet is structurally impossible | ⛔ **§6 forbids it** — `derived` *means* "not the authoritative one for this topic" |

## 4 · ⛔ `D4` is false of the implementation — and `A2` is a misreading

**`D4`** *(`[C]` from `F0018`)*: *"`generated`, `derived` → PROVENANCE → **NEVER** changes."*

⛔ **§7 moves an artifact off `generated` by human review. The field changes. `D4` is false of the implemented model.**

⭐ **And the deeper point:** `D4` would be true of a *provenance* field. ⛔ **`authority:` is not one.** The artifact's origin — *"this was produced by AI"* — remains a historical fact forever; **it is simply not what `authority:` records.**

> ### ⛔⛔ **`A2` — `F0018`'s partition — is not merely "false as a description of what is built". It is a MISREADING OF WHAT THE VALUES MEAN.**
> ⭐ *A reading invited by the labels, and refuted by §6 and §7.*

## 5 · ⭐ This REVERSES my own `P4` strengthening

**In the A3 correction I wrote:** *"P4 is STRENGTHENED past its original claim — the implementation documents `generated → authoritative`, which CONTRADICTS `D4` outright."*

⛔ **Wrong, or at least badly framed.** Immutability is not *contradicted* — ⭐ **it never applied**, because these were never provenance values.

> ### ⛔ **`P4` does not strengthen. It DISSOLVES, along with the premise that gave it content.**
> **A proposition about the vacuity of an immutability invariant is empty once the invariant has no subject.**

⚠️ **Second time in this investigation that a "strengthening" of mine has turned out to rest on the premise under attack.** *Recorded as a pattern in my own reasoning, not just a one-off.*

## 6 · Revised status of Slice 01 — recommended, ⛔ not applied

| Proposition | After `RO-0064` |
|---|---|
| **P1** *(codomain is `P ⊔ S`, 5 ≠ 6)* | ⛔⛔ **PREMISE FALSIFIED.** `V` is not `P ⊔ S`; it is a 5-element **ranked set with a uniqueness constraint**. ⭐ The arithmetic was never wrong — **the partition it described does not exist in the implementation** |
| ⭐ **P2** *(the paradigm example is unrepresentable)* | ⭐⭐ **SURVIVES, and is now sharper: not merely unrepresentable — ⛔ FORBIDDEN by §6.** `derived` *means* "not the authoritative one" |
| **P3** *(refinement not derivable)* | ⚠️ **still valid IF a two-dimensional model is wanted.** ⛔ But the migration is no longer *adding a missing coordinate* — it is **replacing one model with another** |
| **P4** *(immutability vacuous)* | ⛔ **DISSOLVED** — §5 |

### The competing formulations, after the evidence

| | |
|---|---|
| **F-a** current | ⭐⭐ **coherent as implemented** — a ranked trust scale, lint-enforced, with a documented promotion path. ⛔ **Its cost is that origin is not recorded anywhere** |
| **F-b** `P × S` | ⭐ **would record what `F-a` loses** — ⛔ but it is a **replacement**, not a refinement, and §6's uniqueness constraint would need restating on the `S` coordinate |
| **F-c** `P × (S ∪ ⊥)` | ⚠️ **unchanged in standing**; ⛔ still awaiting `RO-0065` |

⛔ **None chosen. The choice is `PM-1`, and it is now a clearer choice:** *keep a ranked trust scale that does not record origin, or adopt a two-coordinate model and re-express the single-source-of-truth rule.*

## 7 · Origin and epistemic status

| Item | Origin | Level |
|---|---|---|
| §6, §7, §2's status-only transition table, the lint enforcement | `[C]` | **L0** |
| the five values are one ranked trust scale with origin-named labels | `[E]` | **L2** |
| `D4` false of the implementation; `A2` a misreading; `P1` premise falsified; `P4` dissolved | `[E]` | **L3** |

⛔ **Nothing above `L3`.** ⚠️ `RA-15`: SOURCE-SUPPORTED, RECONSTRUCTION-VALID, ⛔ **not independently corroborated.**

## 8 · Obligations

| # | |
|---|---|
| `RO-0064` | ⭐ **DISCHARGED** |
| ⭐ **`RO-0067`** | ⛔ **Origin is recorded NOWHERE.** If `authority:` is trust, no field holds *"this was produced by AI"* after review moves it to `authoritative`. ⚠️ **That is a provenance gap in a platform whose thesis is traceability** |
| **`RO-0068`** | Does `F0018`'s two-question reading survive anywhere? ⭐ It may describe the *semantics the corpus wants* rather than the schema it has — worth separating |
| **`RO-0069`** | ⚠️ **Twice now a "strengthening" of mine rested on the premise under attack.** Check `[E]`-derived strengthenings against their own premises before recording them |

## 9 · Unresolved

⛔ `PM-1` itself — the choice, now clearer but not made · ⛔ **`RO-0067`, the origin gap — possibly the more consequential finding** · ⚠️ `RO-0065` *(do generated indexes have applicable standing?)* · ⚠️ whether `F0018`'s reading holds for any other artifact class.

---

## ⭐ `RO-0064` in one paragraph

**`lifecycle.md` §6 settles it: exactly one `authority: authoritative` document per topic, everything else `derived`/`generated`/`historical`/`provisional`, lint-enforced.** That is one dimension — **a per-topic trust ranking with a unique maximum** — and §7 documents moving *up* it by human review. ⛔ **So `D4`'s immutability is false of the implementation, and `A2`'s partition is a misreading invited by origin-named labels.** ⭐ **The five values are a single trust ranking whose labels are named after origins**, which explains why `F0018` saw two questions, why `derived + authoritative` feels natural yet is forbidden, and why `rank:` orders all five coherently. **Slice 01's `P1` loses its premise and `P4` dissolves; `P2` survives and sharpens — the paradigm example is not merely unrepresentable but forbidden.** ⭐ **And a new question is now the sharper one: if `authority:` records trust, what records origin? Nothing found.**

---

*`RO-0064` discharged · 7 questions answered from the authoritative source · `D4` falsified · `A2` identified as a misreading · **`P1` premise falsified · `P4` dissolved · `P2` strengthened** · ⛔ one of my own prior corrections reversed · 3 new obligations · ⛔ Slice 01 unedited · no schema changed · Candidate Theory v0.9 FROZEN.*

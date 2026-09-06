# Epistemic Status Vocabulary — every mathematical statement carries its status

**Status:** ADOPTED for the KnowledgeOS research lane · **Date:** 2026-09-04
**Authority:** issued by the human research owner, 2026-09-04, as a *permanent methodological rule*.
**Scope:** every mathematical statement in KnowledgeOS research artifacts.
**Placement:** resolved via `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0).

---

## 0. The rule

> **Every future mathematical statement in KnowledgeOS must carry its epistemic status:
> Definition, Theorem, Corollary, Empirical Result, Proposition, Conjecture, Refuted, or Open.**
>
> *"That is what will allow us to turn this from an interesting research program into a
> genuinely rigorous mathematical theory without accidentally promoting an experiment into
> a theorem."* — the issuing instruction, quoted so the reason travels with the rule.

**An untagged mathematical statement is a defect**, not a stylistic lapse. It is the exact
mechanism by which an experiment becomes a theorem without anyone deciding that it should.

---

## 1. This EXTENDS the existing vocabulary — it does not replace it (`ES-005.4`, never a copy)

The zero-algebra lane already runs a status vocabulary (22 `[EXP]` · 9 `[OPEN]` · 7 `[PROP]` ·
5 `[NEG]` · 2 `[THEORY]` · 2 `[DESIGN]` · 1 `[DEFECT]` · 1 `[DEF]` in the results documents
alone). **Five of the eight required categories already exist.** Creating a second vocabulary
would be the copy that `ES-005.4` forbids.

| Required category | Existing tag | Action |
|---|---|---|
| **Definition** | `[DEF]` | ✅ **exists** — stipulated; carries no truth claim; cannot be refuted, only replaced |
| **Empirical Result** | `[EXP]` | ✅ **exists** — established by a **named experiment**; **must carry its scope** |
| **Refuted** | `[NEG]` | ✅ **exists** — must cite the refuting evidence |
| **Open** | `[OPEN]` | ✅ **exists** |
| **Theorem** | — | ➕ **`[THM]` — NEW.** Proved. **Must cite the proof or contain it.** |
| **Corollary** | — | ➕ **`[COR]` — NEW.** Follows from a **cited** `[THM]`/`[DEF]` by **stated** steps |
| **Conjecture** | — | ➕ **`[CONJ]` — NEW.** Believed, unproved, untested. **Must state what would refute it.** |
| **Proposition** | `[PROP]` ⚠️ | ⚠️ **COLLISION — see §2** |

---

## 2. ⚠️ `[PROP]` is ambiguous and is retired as a live tag

`[PROP]` is in use in this repository meaning **proposal / recommendation** — *"Recommendations
`[PROP]` — not decisions"* (`KR-BRIDGE-02-RESULTS-2026-09.md` §8). The rule above uses
**Proposition** in the mathematical sense: **a proved statement of lesser weight than a theorem.**

These are not near-synonyms. One is *a suggestion carrying no truth claim*; the other is
*proved*. Leaving them on one tag is precisely the silent-promotion failure the rule exists to
prevent — and it would promote in the worst direction, from suggestion to proof.

**Resolution — the pattern this repository already uses for a colliding ID** (see
`EXPERIMENT-ID-REGISTRY.md`, *"a superseded ID survives as a legacy alias, never as a live name"*):

| Tag | Meaning | Status |
|---|---|---|
| **`[PRP]`** | **Proposition** — proved, lesser weight than a theorem; must cite its proof | **live** |
| **`[REC]`** | **Recommendation / proposal** — carries **no** truth claim | **live** |
| ~~`[PROP]`~~ | ambiguous between the two | **RETIRED as a live tag; legacy alias only** |

**Every existing `[PROP]` in the repository remains valid as a historical record and is NOT
rewritten** (historical artifacts are immutable). But **any citation of a `[PROP]` statement
must re-resolve it** against this table. In the zero-algebra results documents all 7 existing
uses are the *recommendation* sense — i.e. `[REC]` — and **none is a proved proposition.**

---

## 3. The full live vocabulary

| Tag | Category | Obligation carried |
|---|---|---|
| `[DEF]` | Definition | stipulated; no truth claim; replaced, never refuted |
| `[THM]` | Theorem | **proof cited or contained** |
| `[COR]` | Corollary | **antecedent cited** + **derivation steps stated** |
| `[PRP]` | Proposition | proved; **proof cited** |
| `[EXP]` | Empirical Result | **named experiment** + **explicit scope bound** |
| `[CONJ]` | Conjecture | **what would refute it**, stated |
| `[NEG]` | Refuted | **the refuting evidence**, cited |
| `[OPEN]` | Open question | — |
| `[REC]` | Recommendation | **no truth claim**; names who decides |
| `[DEFECT]` | Known defect | contained scope + effect on conclusions |
| `[CORPUS]` `[EXT]` `[INF]` `[THEORY]` `[DESIGN]` `[DECIDED]` `[DECISION REQUIRED]` `[DEFERRED]` | lane/governance states | unchanged |

### The promotion ladder is one-directional and gated

```
[CONJ] ──experiment──▶ [EXP] ──proof──▶ [PRP]/[THM] ──derivation──▶ [COR]
   │                     │
   └──refutation──▶ [NEG] ◀──refutation──┘

[EXP] NEVER becomes [THM] by accumulating experiments.  A theorem needs a PROOF.
[REC] NEVER becomes anything.  It carries no truth claim to promote.
```

`[EXP] → [THM]` **requires a proof, not more evidence.** This is the specific promotion the
rule was issued to prevent, and it is therefore stated as a prohibition rather than a caution.

---

## 3a. ⚠️ A design property is not an empirical result

**Added 2026-09-05, on the research owner's instruction that the distinction be kept permanently.**

The most common way a `[DEF]` becomes a `[EXP]` by accident is a **measured design property**: a
number that comes out of an experiment, is reported with a numerator and a denominator, and could
not have come out any other way.

> ### `context\_preserved = 1.000` must NEVER be written as
> ### ~~"the experiment demonstrated perfect context preservation"~~
> ### It means **"the operator was designed to preserve context."**

**The rule.** Before tagging any measured quantity `[EXP]`, ask: **could this number have come out
differently, given the design?** If no, it is `[DEF]` — a restatement of the construction — and it
must be reported as such, with the mechanism that forces it named.

**Worked instances from the corpus**, kept because the pattern recurs:

| reported number | looked like | actually |
|---|---|---|
| `context_preserved = 1.000` | perfect context preservation | the operator never deletes |
| restriction finds cross-dimension causes: `0.000` | a strong negative | a restricted operator cannot cross the boundary that defines it |
| `Zero` before evidence `= 0.87525` | an epistemic rate | **identical to the generator's cross-dimension rate** — that rate, relabelled |
| `zoom_admissible == zoom_nontrivial` (844/844) | H1 strongly supported | the threshold could not fail |
| counterfactual structural divergence `= 0/4368` | silent dimensions do not matter | the operator could not have shown a difference |

`[REC]` **Every experiment reports a DEGENERATE METRICS section** naming each such quantity and
the mechanism that forces it. A results document without one has not looked.

---

## 4. Immediate application — the standing register, re-tagged

The three-way separation accepted on 2026-09-04:

| Statement | Status |
|---|---|
| **Eliminability ≠ Preservation ≠ Realization** — three distinct questions about `D --T--> R` | `[EXP]` — separated experimentally by KR-ZERO, KR-REP-REDUCTION and KR-BRIDGE-01/02. **Not** `[THM]`: no proof that they *must* differ in general, only that they *do* differ in the systems tested. |
| `Zero_{T,Π}(S;D) ⟺ Π(T(D)) = Π(T(E_S(D)))` | `[DEF]` |
| `Adequacy: Ĥ(Q\|T(D)) = 0` | `[DEF]` |
| `Realization: O(T(D)) = Q(D)` | `[DEF]` |
| `H(Q\|R₅) ≤ H(Q\|R₄) ≤ H(Q\|R₃) ≤ H(Q\|R₂)` for a deterministic sequential chain | **`[THM]`** — the data-processing inequality. **Proof: standard; cited, not re-derived.** The only `[THM]` in the register. |
| Adequacy cannot be non-monotone along a deterministic sequential chain | **`[COR]`** of the `[THM]` above. *(Earlier recorded as "refuted a priori" — corrected: **structurally inapplicable ≠ empirically falsified**.)* |
| The preservation boundary lies at `R5 → R4` | `[EXP]` — **scope: that carrier, that `Q`, that chain, that contract, that value domain.** Explicitly **not** "three significant digits is the universal preservation limit." |
| `Zero` predicts preservation | `[NEG]` — KR-BRIDGE-01 (OUTCOME A) and KR-BRIDGE-02 (H1 not refuted) |
| Relational transformations are necessary for higher-order `Zero` | `[NEG]` |
| Rank transformations are invertible recodings | `[NEG]` — audit FAIL 2.3: `desc ≠ (n+1) − asc` under ties |
| Reduction dimensions are independent | `[NEG]` |
| `FR-001` pairwise distinguishability cannot carry family-level complexity | `[EXP]`, frozen |
| `FR-003` read-disjointness must be discharged on `(Π, Q, T)` and verified empirically | `[EXP]` (KR-BRIDGE-02 §2) + `[REC]` for promotion — **needs a second independent adopter** (`ES-006.1`) |
| A conditional bridge exists for some `(Π, Q)` | `[OPEN]` — searched 31 of 112 cells; **absence of evidence over the region searched only** |
| Flattening the redundancy distribution increases informative strata | **`[NEG]`** — KR-BRIDGE-03 calibration, §5 below |

---

## 5. What is NOT changed by this rule

- **Theory v1.2 remains FROZEN. The kernel remains NOT SELECTED.** A vocabulary is not a theory.
- **Governance order is unchanged:** `EXPERIMENT → AUDIT → ADJUDICATION → THEORY v1.3`.
- **No historical document is rewritten.** Re-tagging applies from this document forward;
  prior artifacts keep their text and are re-resolved on citation.

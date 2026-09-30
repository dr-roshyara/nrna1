# Theory Slice `01` — Attack on assumption `A3`

*Phase 2, job `2C` Theory Attack. Append-only. ⛔ Slice 01 is **not** edited; its reformulation is **recommended**, not applied.*

| | |
|---|---|
| **Target** | **`A3`** — *"every artifact that has an origin also has a meaningful trust/standing level."* Flagged in Slice 01 §3 as **load-bearing** and *"the one to attack first"* |
| **Obligation** | ⭐ **`RO-0059`**, named **before** retrieval: *hypothesis → question → required evidence → targeted source → result* |
| **Targeted source** | `docs/knowledge/schema/authorities.yaml` + `authority:` frontmatter across `docs/knowledge/**` — ⛔ **implementation evidence, NOT the canonical research corpus.** No corpus file read; no receipt issued |
| **Tests executed** | **`[T]`-06** — value census and migration cost over **39 governed artifacts** |
| ⛔ **Untouched** | Candidate Theory v0.9 · Slice 01 · every Phase-1 record · the authority schema |

---

## 1 · Verdict on `A3` — **PARTIALLY_SUPPORTED**, with a distinction the question did not anticipate

> ### ⭐ **`A3` is semantically TRUE and structurally UNTESTABLE in the current schema.**

Every artifact examined plausibly *has* a standing. ⛔ **But for 11 of 39 the standing is not merely unrecorded — it is INEXPRESSIBLE**, because the field admits exactly one value and that value was spent on provenance.

**This is a sixth category the attack brief did not list.** Distinguishing the five it did:

| Category | Found? |
|---|---|
| explicitly absent | ⛔ no |
| explicitly unknown | ⛔ no |
| intentionally unassigned | ⛔ no |
| not applicable | ⛔ no |
| merely omitted from the record | ⛔ **no** — the field is marked `# REQUIRED` |
| ⭐⭐ **STRUCTURALLY INEXPRESSIBLE** | ⭐ **YES — 11 of 39** |

⭐ **`"not recorded"` and `"does not exist"` were correctly separated in the brief. The evidence lands on neither: the value exists and the schema has no slot for it.**

## 2 · `[T]`-06 — the census

**39 governed artifacts carry an `authority:` field.**

| Value | Count | Class *(per `F0018`)* |
|---|---|---|
| `authoritative` | **15** | STANDING |
| `provisional` | **13** | STANDING |
| `derived` | **10** | PROVENANCE |
| `generated` | **1** | PROVENANCE |

| | |
|---|---|
| PROVENANCE-class → **standing not recorded** | **11** |
| STANDING-class → **provenance not recorded** | **28** |
| ⛔ **artifacts carrying BOTH coordinates** | ⛔⛔ **ZERO** |

⭐ **A second field was searched for and does not exist:** no `standing:`, no `provenance:`, no `trust:` anywhere in the tree. **The schema is single-field, empirically as well as by declaration.**

## 3 · ⭐⭐ Proposition 2 is confirmed empirically, and generalizes

Slice 01 proved the signed-financial-statement example unrepresentable from **one** worked case.

> ### ⭐ **`[T]`-06 raises that from one illustration to 39 of 39 artifacts. Not one carries both coordinates, because not one can.**

⛔ **Proposition 2 is no longer a curiosity about an example. It is the observed state of every governed artifact in the repository.**

## 4 · ⭐⭐ Proposition 3 is confirmed and now QUANTIFIED

Slice 01 proved the `P ⊔ S → P × S` refinement is not derivable — the missing coordinate must be **stipulated**.

> ### ⛔ **Cost, measured: 39 of 39 artifacts require a stipulated coordinate. There is no free case.**
> **11 need a standing supplied · 28 need a provenance supplied.**

⭐ **That converts `PM-1` from a modelling question into a sized decision**: whatever default the ARB chooses is applied to **every governed artifact**, not to a residual tail. ⛔ **No `r` can compute it (Prop 3), so the entire set is decided, not derived.**

## 5 · ⛔⛔ An unexpected result — the attack hits `A2`, not `A3`

**`A2`** = *"`P` and `S` as `F0018` gives them are complete and correct."* Slice 01: *"if `A2` is wrong, everything above falls."*

`authorities.yaml` assigns **every** value a `rank:`

```
authoritative 1 · derived 2 · generated 3 · historical 4 · provisional 5
```

> ### ⛔ **A single TOTAL ORDER over all five values.**
>
> ⭐ **A rank is only coherent between comparable things. You cannot rank *"where did it come from"* against *"how much should I trust it"* on one scale.**

**So the schema asserts the five are one ordered dimension, while its own comment describes the field as `# trust/source … INDEPENDENT of status` — two things.**

### The dilemma, stated without resolving it

| | |
|---|---|
| **(a)** | **all five are STANDING** — `derived`/`generated` mean *"trust this less than authoritative"*, not merely *"it came from there"*. ⛔ **Then `F0018`'s `P` is wrong and `A2` fails** |
| **(b)** | **the partition holds** and ⛔ **`rank:` is meaningless across it** — a defect in the schema, not the theory |

⭐ **The descriptions support BOTH readings and that is the problem:** *"Derived from an authoritative source"* is an **origin** claim; *"Carries no authority yet"* is a **trust** claim. **The field's own documentation mixes the two question-types inside a single ranked enum.**

⛔ **NOT RESOLVED HERE.** Deciding between (a) and (b) determines whether Slice 01's Proposition 1 stands, and it needs evidence this attack did not seek.

## 6 · Effect on Slice 01 — recommended, not applied

| Proposition | Effect |
|---|---|
| **P1** *(codomain is a sum, 5 ≠ 6)* | ⚠️ **CONDITIONAL on `A2`.** The arithmetic is unaffected; ⛔ **the partition it presumes is now contested by `rank:`** |
| **P2** *(the example is unrepresentable)* | ⭐ **STRENGTHENED — 39/39 empirical instances** |
| **P3** *(refinement not information-preserving)* | ⭐ **STRENGTHENED and quantified — 39/39 require stipulation** |
| **P4** *(immutability vacuous under the sum)* | ⚠️ **CONDITIONAL on `A2`**, same dependency as P1 |

### Competing formulations — the balance moved

| | |
|---|---|
| **F-a** sum as built | ⭐ **gains support from `rank:`** — a single ordered scale is what the schema implements |
| **F-b** full product | ⭐ **gains support from `A3`** — the standing exists in all 11 provenance-class cases, it simply has no slot |
| **F-c** partial product `P × (S ∪ ⊥)` | ⛔ **WEAKENED.** ⭐ **`⊥` was motivated by artifacts that genuinely lack standing. None was found.** The 11 are inexpressible, not empty |

> ⭐ **The attack did what it was supposed to: it moved support toward `F-b` on the `A3` question and toward `F-a` on the `rank:` question, and those pull in opposite directions. ⛔ Neither is selected.**

## 7 · Origin and epistemic status

| Item | Origin | Level |
|---|---|---|
| the five values, `rank:`, the descriptions, the `# trust/source` comment | `[C]` | **L0** |
| the 39-artifact census; zero two-coordinate artifacts; no second field | `[T]` **`[T]`-06** | **L0** *(measured)* |
| `A3` = PARTIALLY_SUPPORTED; the sixth category *(structurally inexpressible)* | `[E]` | **L2** |
| P2/P3 strengthened; the migration cost 39/39 | `[E]` derived from `[T]`-06 | **L3** |
| the `rank:` dilemma (a)/(b) | `[E]` | **L2** |

⛔ **Nothing promoted. Nothing above `L3`. `L5` unreachable.**
⚠️ **`RA-15` status: SOURCE-SUPPORTED and RECONSTRUCTION-VALID. ⛔ NOT independently corroborated** — one repository, one programme *(`RO-0051`)*.

## 8 · New obligations

| # | |
|---|---|
| **`RO-0059`** | ⭐ **DISCHARGED** by this attack |
| **`RO-0060`** | ⛔⛔ **Resolve the `rank:` dilemma.** Does `rank:` order trust across all five *(a)*, or is it incoherent across the partition *(b)*? ⭐ **Slice 01's P1 and P4 depend on the answer** |
| **`RO-0061`** | Is `rank:` consumed by any tool? ⭐ A ranking nothing reads is inert; one that gates a lint rule is load-bearing. ⛔ Not investigated |
| **`RO-0062`** | `historical` appears **0 times** in 39 artifacts. A declared value with no instance — unexercised, like so much else in this corpus |
| **`RO-0063`** | ⭐ **Sample bias:** all 39 are `docs/knowledge/**`. Artifacts governed elsewhere were not examined |

## 9 · What remains unresolved

⛔ **`A2`** — the partition itself, now contested · ⛔ **the `rank:` dilemma** · ⛔ **`F-a` vs `F-b`** — evidence now pulls both ways · ⚠️ whether `⊥` is needed for any purpose other than absent standing · ⚠️ whether the 39 are representative.

---

## ⭐ The attack in one paragraph

**`A3` survives as a semantic claim and fails as a structural one: every artifact plausibly has a standing, and 11 of 39 have nowhere to put it.** That raises Slice 01's Proposition 2 from a single worked example to **39 of 39 artifacts carrying exactly one coordinate**, and it quantifies Proposition 3's stipulation cost at **every artifact, with no free case**. ⛔ **But the attack's most consequential finding is not about `A3` at all:** `authorities.yaml` assigns all five values a single `rank:`, which is coherent only if they are one dimension — **contradicting the partition `A2` asserts and Slice 01's Propositions 1 and 4 depend on.** ⭐ **`F-c` is weakened for want of a single empty-standing artifact; `F-a` and `F-b` both gained support, from opposite directions. Nothing is selected.**

---

*A3 attack · `RO-0059` discharged · 1 test · 39 artifacts · P2 and P3 strengthened · **P1 and P4 made conditional** · F-c weakened · 4 new obligations · ⛔ 0 corpus reads · 0 records modified · Slice 01 unedited · Candidate Theory v0.9 FROZEN.*

---

# ⭐ APPENDED — correction and two completed investigations *(the body above stands UNCHANGED)*

## C1 · ⛔ The verdict wording above was too strong — corrected

**Written above:** *"`A3` is semantically TRUE and structurally UNTESTABLE in the current schema."*

⛔ **That converts absence of representation into proof of semantic truth, and it conflates *inexpressible* with *untestable*.** The implementation evidence cannot establish that every artifact *has* a standing; it can only show the schema does not record one.

> ### ⭐ **Corrected verdict**
> **`A3` is PARTIALLY SUPPORTED.** The examined artifacts provide **no counterexample** to the existence of a standing dimension, but the current schema **cannot independently represent standing** for artifacts whose `authority:` value is consumed by provenance. **The proposition therefore cannot be fully tested from the current representation.**

⚠️ **And `§1`'s sixth category is itself now conditional** — see `C4`.

## C2 · `RO-0060` — the rank has **no operational meaning**

| Question | Result |
|---|---|
| Who reads `authorities.yaml`? | ⭐ **exactly one consumer — `scripts/knowledge-lint.php`** |
| Does any code read `rank`? | ⛔ **NO.** The only `rank` hit repository-wide is `"<=_rank"` in an unrelated KSME relation profile |
| What does the lint actually check? | `enum_values_valid: { severity: error }` — ⭐ **membership in the enum, never ordering** |

> ### ⛔ **`rank:` is INERT. It is documentation ordering, not an operational relation.**

⛔⛔ **This corrects my own §5.** I presented `rank:` as evidence that the schema asserts one dimension. ⭐ **A field no code reads asserts nothing operationally.** The dilemma in §5 was built on the weaker of the two available arguments.

⭐ **`RO-0061` is discharged by the same finding.** ⛔ **And `R = f(P,S)` or a third axis `P × S × R` is unsupported: there is no `R` to model.**

## C3 · ⭐⭐ The real evidence — and it is much stronger

`docs/knowledge/_meta/lifecycle.md` *(`status: baseline`, `authority: authoritative`)* supplies three lines the rank could not:

| Line | |
|---|---|
| **37** | the five values listed as **one axis** — *"`authority` — trust & source, **independent of status**"* |
| **39** | *"**Real combinations**: `approved` + `generated`; `baseline` + `derived`; `frozen` + `authoritative`"* — ⭐ **status × authority pairs. The declared orthogonality is `status ⟂ authority`, TWO axes, not three** |
| ⭐⭐ **107** | ⛔⛔ **`AI-generated knowledge → review → approval → baseline → authoritative`** |

> ### ⛔⛔ **Line 107 is a PATH from `generated` to `authoritative`.**
>
> ⭐ **If `generated` were immutable provenance and `authoritative` were mutable standing, that transition would be impossible — `D4` says provenance NEVER changes.**
>
> ### **The implementation documents a legal transition that the recovered theory forbids.**

⭐ **So the `§5` dilemma resolves — but on better evidence and in a sharper form than `(a)` or `(b)`:**

| | |
|---|---|
| ⭐ **The IMPLEMENTATION** | treats `authority` as **ONE axis of five values with a progression**, orthogonal to `status` |
| ⭐ **The RECOVERED THEORY** *(`F0018`)* | reads the same five values as **TWO questions**, one immutable |
| ⛔ | **They disagree, and line 107 is the proof.** ⭐ `A2` is sound as a *semantic reconstruction* and **false as a description of what is built** |

## C4 · ⛔ Classifying the 11 — and the finding weakens

**All 11 are generated navigation artifacts:** `INDEX.md` · `by-type.md` · `by-role.md` · `knowledge-graph.md` · five `README.md` · two domain pages. `lifecycle.md` names this exact case: *"`baseline` + `derived` (a generated index built from baselines)."*

**Against the three cases:**

| Case | Verdict |
|---|---|
| **A** — independently evidenced standing | ⛔ **NOT FOUND.** No artifact, rule or guard assigns any of the 11 a separate standing |
| **B** — merely hypothesized | ⚠️ **this is what §1 did.** *"A derived index probably has a standing"* is inference, not evidence |
| ⭐ **C** — standing genuinely not applicable | ⭐⭐ **SUPPORTED.** Under the implementation's own one-axis model, `derived` **IS** the artifact's authority. Nothing is missing |

> ### ⛔⛔ **Therefore §1's "structurally inexpressible, 11 of 39" is CONDITIONAL on `A2`, and `A2` is the thing line 107 falsifies.**
>
> ⭐ **The 11 are inexpressible only relative to a two-dimensional model the implementation does not share. Presupposing `F-b` to measure the cost of `F-b` is circular, and §1 did exactly that.**

## C5 · Revised effect on Slice 01

| Proposition | Revised |
|---|---|
| **P1** *(5 ≠ 6)* | ⚠️ **arithmetic intact; interpretation CONDITIONAL.** Without `A2`, `V` is a 5-element set with an inert rank — ⛔ not `P ⊔ S` |
| ⭐ **P2** *(the example is unrepresentable)* | ⭐ **SURVIVES on either reading.** One field, one value: the signed statement loses a coordinate whichever model is right |
| **P3** *(refinement not derivable)* | ⚠️ **survives, conditional on WANTING `P × S`.** ⛔ The 39/39 cost figure presupposes `F-b` |
| ⭐⭐ **P4** *(immutability vacuous)* | ⭐ **STRENGTHENED past its original claim.** Not merely vacuous — ⛔ **the implementation documents `generated → authoritative`, which CONTRADICTS `D4` outright** |

⛔ **§6 above over-claimed** that `P2`/`P3` were *"strengthened"* by 39/39. ⭐ **`P2` is strengthened; `P3`'s quantification is withdrawn as circular.**

### Competing formulations — corrected

| | |
|---|---|
| **F-a** | ⭐ **support was NOT from `rank`** *(inert)* but from lines 37/39/107 — **stronger than §6 claimed, for different reasons** |
| **F-b** | ⚠️ **weakened.** Case C means the 11 may need no standing at all |
| **F-c** | ⭐ **partially rehabilitated.** ⛔ §6 dismissed `⊥` for want of empty-standing artifacts; **Case C is precisely that case** — if a generated index has no applicable standing, `⊥` models it |

⭐⭐ **§6 got the direction of movement wrong on both `F-b` and `F-c`. Recorded rather than quietly amended.**

## C6 · Obligations

| # | |
|---|---|
| `RO-0060` · `RO-0061` | ⭐ **DISCHARGED** — rank is inert |
| ⭐ **`RO-0064`** | ⛔⛔ **`lifecycle.md` line 107 documents `generated → authoritative`, contradicting `D4`'s immutability.** Determine whether the path is real, aspirational, or a loose description — **`F0018`'s flagship finding depends on it** |
| **`RO-0065`** | Do generated indexes have an applicable standing at all, or does trust inhere in their sources? ⭐ Decides `F-b` vs `F-c` |
| **`RO-0066`** | ⚠️ **Circularity check:** any future cost estimate for a schema change must not presuppose the target schema |

## C7 · Status after correction

| Finding | Status |
|---|---|
| `A3` original formulation | ⚠️ **PARTIALLY SUPPORTED · underdetermined by current evidence** |
| Single-field encoding | ⭐ **CONFIRMED** — 39/39, no second field exists |
| `P2` — the example is unrepresentable | ⭐ **CONFIRMED, reading-independent** |
| Provenance/standing conflation | ⭐ **STRONGLY EVIDENCED** — but as a *theory↔implementation* disagreement |
| *"Structurally inexpressible, 11/39"* | ⛔ **WITHDRAWN AS STATED** — conditional on `A2`, circular |
| `rank` as a semantic relation | ⛔ **REFUTED — inert** |
| `A2` as a description of the implementation | ⛔ **FALSIFIED by line 107** |
| `P × S` as the answer | ⛔ **NOT ESTABLISHED** |
| Slice 01 edited? | ⛔ **NO** |

---

*Correction appendix · 2 investigations discharged · **3 of my own claims corrected: the A3 wording, the rank argument, and the 11/39 generalization** · `A2` falsified as an implementation description · `P4` strengthened · `P3`'s quantification withdrawn as circular · 3 new obligations · ⛔ Slice 01 unedited · Candidate Theory v0.9 FROZEN.*

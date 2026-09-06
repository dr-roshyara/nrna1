# G — Phases B′ and C′ with the Three Evaluators Supplied · `KR-SIM-2026-09-02-E`

**Instruction:** supply the three missing evaluators — governance, temporal, operational — and re-run
Phases B and C.

> **These evaluators are EXPERIMENTER-SUPPLIED CANDIDATES, not theory-derived.** The theory defines
> none of them. Each is deliberately **partial**: an evaluator that always returned `⊤` would rig the
> result. Every one produces `⊤`, `⊥` **and** several distinct `U` reasons.

> ### 🔴 RETRACTED — two of the three evaluators are corpus-unsupported
> `KR-SIM-2026-09-02-G` re-read this run against the commissioning document's Rules A3 and A4:
> * **`Eval_Gov`** — the corpus defines no governance artifact, no evidence of authority and no
>   conflict rule. An authority table is **invented governance**, which A3 forbids.
> * **`Eval_Time`** — A4 requires distinguishing **valid/world**, **observation** and
>   **transaction/record** time and forbids equating timestamps with temporal validity. This
>   evaluator equated interval coverage with validity.
>
> **Consequence for §C′-2 below: the `Zero_reasoned` 8/80 vs `Zero_weak` 36/80 separation is an
> ARTIFACT of these invented evaluators.** Once retracted, every remaining `U` is theory-blocked and
> the two readings **agree on every honest case**. `Zero_reasoned` remains *formally* distinct, but
> only a constructed case separates it. See
> [`G-evaluation-semantics-experiment.md`](G-evaluation-semantics-experiment.md) §D-2.
> Only `Sat_op`/`δ` survives, and it too returns `U (DELTA_UNDEFINED)` honestly.

## The three candidates

| class | evaluator | `⊤` | `⊥` | `U` and why |
|---|---|---|---|---|
| **governance** | `Eval_Gov(K,g,authorities,now)` — authorities are `(id, grants, valid_until)`; `K` is accepted but **never consulted for the grant**, per the theory's rule that governance may not be inferred from content | a live authority grants `g` | a live authority denies `g` | `AUTHORITY_SILENT` · `AUTHORITY_EXPIRED` · `AUTHORITY_CONFLICT` |
| **temporal** | `Eval_Time(K,p,I,intervals,counter)` — `p` established over `I` iff the evidence intervals **cover** `I`; `true@t1 / false@t2` is **not** a contradiction | intervals cover `I` | established counter-evidence covers `I` | `COVERAGE_INCOMPLETE` · `NO_INTERVAL` |
| **operational** | `δ(K,o)` defined iff preconditions hold; `Sat_op = Sat_κ(δ(K,o))` | δ defined ∧ κ met | δ defined ∧ κ unmet | `PRECONDITION_UNMET` · `KAPPA_OPERATIONAL` |

Situation matrix: 5 authority states × 4 temporal states × 4 operational states = **80 situations**.

---

# PHASE B′

## PB-4′ — contagion drops sharply, and does not clear `[EXP]`

| composite arity | **before** (5 blocked) | **after** (2 blocked) |
|---|---|---|
| 1 | 5/8 = 0.625 | **2/8 = 0.250** |
| 2 | 25/28 = 0.893 | **13/28 = 0.464** |
| 3 | 55/56 = 0.982 | **36/56 = 0.643** |
| 4 | 70/70 = 1.000 | **55/70 = 0.786** |
| 5 | 56/56 = 1.000 | 50/56 = 0.893 |
| 6 | 28/28 = 1.000 | 27/28 = 0.964 |
| **7** | 8/8 = 1.000 | **8/8 = 1.000** |
| **8** | 1/1 = 1.000 | **1/1 = 1.000** |
| all eight conjoined | `U` | **`U`** |

Supplying three of five evaluators roughly **halves** contagion at low arity — and changes nothing at
arity 7–8, because every such composite still touches `status` or `consistency`.

> `[EXP]` **The residual contagion is now attributable to exactly two classes:** `status`
> (`⪰` undefined) and `consistency` (`Contr` undefined). PB-4 has gone from a diffuse blockage across
> five classes to a **named two-class blocker.**

> ### ⚠ CORRECTED — these figures UNDERSTATE the contagion
> This run was executed **before** the PB-2 repair, contrary to the prescribed sequencing. Under the
> only PB-2 repair that survives all three criteria (`R_b`: `Sat_content` delegates to
> `Sat_consistency`), **`content` inherits `consistency`'s blockage** and the true figures are worse:
> arity 2 `0.464 → 0.643`, arity 3 `0.643 → 0.821`, arity 4 `0.786 → 0.929`, and **arity ≥ 6 becomes
> 1.000** rather than ≥ 7. See `H-repair-phase-and-reordered-rerun.md` §3.
> **The Phase C′ conclusions below are unaffected** — Zero readings are identical under both.

## PB-3′ — the reason vocabulary grows, and so must the partition `[EXP]` `[NEG]`

All **7 new reasons** were observed across the 80 situations, none unclassified — **but only because
the Phase-C partition had to be extended by two buckets that do not exist until evaluators do**:

| bucket | reasons | who can act |
|---|---|---|
| agent-remediable | `UNOBSERVED`, `UNINTERPRETED`, `UNDERDETERMINED`, `INSUFFICIENT_PROVENANCE`, `COVERAGE_INCOMPLETE`, `NO_INTERVAL`, `PRECONDITION_UNMET` | the agent |
| **governance-remediable** *(NEW)* | `AUTHORITY_SILENT`, `AUTHORITY_EXPIRED` | **a third party** — not the agent, not the theory |
| **normative-decision** *(NEW)* | `AUTHORITY_CONFLICT` | **nobody acts; someone decides** |
| theory-blocked | `NO_ORDERING`, `NO_TEMPORAL_SEMANTICS`, `DELTA_UNDEFINED`, `NO_EVALUATOR`, `KAPPA_OPERATIONAL` | the theory |
| world-blocked | `UNOBSERVABLE` | nobody |

> **The three-bucket partition of Phase C was insufficient.** Supplying evaluators did not merely
> convert `U`s into `⊤`s — it **re-sourced** them, and revealed two categories of blockage the
> agent/theory/world trichotomy could not express.

## PB-5′ — the well-foundedness defect is now reachable, and **invisible in the value** `[NEG]`

| | result |
|---|---|
| κ restricted to the seven non-operational classes | `U (KAPPA_OPERATIONAL)` — refused immediately, family well-founded |
| κ unrestricted | `U (KAPPA_OPERATIONAL)` — recursed, then cut off by an **arbitrary depth bound** |

**Both return the identical value.** The defect is real and now reachable, but it **cannot be
detected from `Sat`'s codomain** — only from the evaluation trace. `Just` does not separate them
either. `[OPEN]` A well-foundedness failure is invisible to the two-level scheme as specified.

---

# PHASE C′ — is `Zero_strict` reachable?

80 situations, two variants.

| variant | `Zero_strict` | `Zero_reasoned` | `Zero_weak` | chain holds |
|---|---|---|---|---|
| **`⪰` and `Contr` still undefined** | **0 / 80 — UNREACHABLE** | 8 / 80 | 36 / 80 | ✔ |
| **`⪰` and `Contr` invented by the experimenter** | **1 / 80** (`grants/full/met`) | 8 / 80 | 36 / 80 | ✔ |

## C′-1 — the answer to PB-4's question `[EXP]`

> **`Zero_strict` remains unreachable after supplying three of the five missing evaluators.**
> It becomes reachable *only* when the experimenter also invents `⪰` and `Contr` — and then it fires
> in **1 of 80 situations**, the single maximally cooperative configuration.

**`Zero_strict` reachability measures how much has been invented, not how much is known.** That is
the direct answer to the question PB-4 posed: *unreachable by construction* while any class lacks
semantics, and *barely reachable* even when every class is supplied.

## C′-2 — `Zero_reasoned` earns its place `[EXP]`

It fires **8 / 80**, strictly between `strict` (0–1) and `weak` (36). It fires on exactly:

```
{grants, silent, expired, conflicting} × full × {met, kappa_op}
```

* **Independent of the authority state** — `silent`, `expired` and `conflicting` all close, because
  those are *governance-remediable* or *normative*, **not the agent's job**. This is precisely the
  semantics it was designed for, and it behaves correctly without being told to.
* Closes when the operational class is blocked by `KAPPA_OPERATIONAL` (theory's problem) but **not**
  when it is blocked by `PRECONDITION_UNMET` (the agent's problem).

What keeps `reasoned` from closing in the 28 cases where `weak` does:

| blocker | count |
|---|---|
| `temporal : agent-remediable` (`COVERAGE_INCOMPLETE`, `NO_INTERVAL`) | 24 |
| `operational : agent-remediable` (`PRECONDITION_UNMET`) | 12 |

> `Zero_weak` closes on 36 situations in which the agent still has work to do. `Zero_reasoned`
> closes on 8 in which it does not. **That is a 4.5× difference in what "closed" means**, and it is
> exactly the distinction the two-level `Sat` was introduced to make possible.

---

# Consequences

| Question | Answer |
|---|---|
| Does supplying evaluators unblock `Sat`? | **Partly.** Contagion halves at low arity; arity ≥ 7 is unchanged; the blockage is now attributable to two named classes |
| Is `Zero_strict` reachable in principle? | **No** — not until `⪰` and `Contr` are defined. With them invented: 1/80 |
| Do evaluators eliminate `U`? | **No — they re-source it.** `U` moves from *"no evaluator"* to *"the evaluator returned undetermined"*, and the reasons split into new buckets |
| Was the reason partition adequate? | **No.** Two new buckets were required: **governance-remediable** and **normative-decision** |
| Is `Zero_reasoned` worth keeping? | **Yes** — it fires 8/80 against `weak`'s 36/80 and separates *agent's work remaining* from *someone else's* |

## Revised status

| Element | Status |
|---|---|
| Three candidate evaluators | **`[PROP]` — experimenter-supplied; the theory defines none of them** |
| `Zero_strict` unreachable while any class lacks semantics | **`[EXP]` DERIVED** |
| Reason partition needs ≥ 5 buckets | **`[EXP]` ESTABLISHED** (2 new buckets forced by the data) |
| `Zero_reasoned` | **`[PROP]`, now with a measured separation (8 vs 36 of 80)** |
| Well-foundedness invisible in `Sat`'s codomain | **`[NEG]` NEW — the two-level scheme cannot see it** |
| Residual contagion | attributable to **`status` and `consistency` only** |

## Next experiment

> **Define `⪰` (the epistemic-status ordering) and `Contr` (the contradiction predicate).**

They are now the *entire* remaining blockage: PB-4′ shows every composite of arity ≥ 7 is `U` because
of them and nothing else, and C′-1 shows `Zero_strict` is unreachable until they exist. `Contr`
carries the OPEN four-valued question (PB-2), so the two are not independent — defining `Contr` may
force the codomain decision.

**Do not** invent them the way this experiment invented the three evaluators. The three were supplied
to *measure a blockage*; `⪰` and `Contr` would be supplied to *close the theory*, and that requires
derivation, not a test fixture.

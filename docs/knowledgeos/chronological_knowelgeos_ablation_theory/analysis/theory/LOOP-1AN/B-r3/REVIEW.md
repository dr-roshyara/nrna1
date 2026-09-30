# Independent review of 1an r3 (separating-set minimization, frozen)

**Basis.** I used only these files: `minimize_1an_r3.py` (its docstring is the spec), `RESULT-r3.json`, `OBSERVATIONS.json`, `PRIOR-REVIEW-r2.md`, `RESULT-1AM-SUPERSEDE.json` and `SCHEMA.md`. I ran no code; every number below was recomputed by hand. Anything that depends on `models_1al.py`, SEMOBS r4 or the F-LOG is marked **unverifiable**.

**Rule applied throughout.** Formal requirement and formal redundancy are model-relative. Neither is ever empirical falsification or confirmation. Empirical status is quoted verbatim from the script's `EMPIRICAL` table and is not re-judged here.

---

## 1. Summary

- **Recomputation matches.** I recomputed every operation × variant × model:
  - D(p,q) for every opposite-outcome pair;
  - the unseparated pairs;
  - the inclusion-minimal and minimum hitting sets;
  - the classes, identity flags and forced cluster pairs;
  - the operation-test counts (248; no decisive pair).

  Every one agrees with `RESULT-r3.json`. **mismatches = none.**
- **Spec fidelity is good for the guard analysis.** D, the hitting sets, the classes, the flags and the forced pairs are all implemented as written.
- **The operation test (MO) diverges from its own spec.**
  - The docstring says UNTESTABLE iff power = 0. Reported power is 248, yet the code prints UNTESTABLE, because its criterion is "no pair with all 9 variables known".
  - The decisive criterion is the same all-9-known test as r2, so it still has zero power by construction.
  - The "power" figure of 248 is simply the number of cross-op opposite-outcome pairs (17·18 − 58). It measures nothing about decisiveness.
- **Prior items:**
  - **Fixed:** D1, D3, D5 and D15.
  - **Partly fixed:** D2 (the label only), D4 (the flag is weak) and D7 (the count is of distinct pairs, not disjoint ones).
  - **Still open:** D6, D8, D9, D10 (partly), D11, D12, D13, D14 and D16.
- **New main findings:**
  1. **Required-by-ignorance, the dual of D1.** `t` is FORMAL-REQUIRED for START only because the git event `START WP-4B 08-03 16:10` has s = UNK. All 5 t-forced pairs run through that one event.
  2. **The identity flag is uninformative for 2-event operations.** There it fires if and only if v separates. It also fires on NEVER-SEPARATES variables (SUPERSEDE a, e), and it misses pair-level lookups (START t, RAISE t).
  3. **Disjoint support is thin.** Only `s` has more than one disjoint cluster pair within a single operation: 2, for START.
  4. **The SUPERSEDE result {k} | {r} is scope-bound.** r4 lacks the event `SUPERSEDE mechanism by R-44 (reported in R-57)`, which RESULT-1AM uses and which breaks {r} (and {a}).

---

## 2. Spec fidelity

### 2.1 Item by item

| Item | Docstring | Code | Verdict |
|---|---|---|---|
| Legality events | PERFORMED or RULE-grounded; CHOICE excluded | `ground != "CHOICE"` | ✓ on the data: every event is PERFORMED/n/a, RULE, or CHOICE (R-94 only). 35 legality events. Wording differs |
| Opposite outcome | (not defined) | PERFORMED vs not-PERFORMED, so NOT-IN-FORCE counts as opposite | Under-specified; D6 is still open |
| D(p,q) | vars in pool, both KNOWN (not UNK, n/a, token), unequal | `isknown` excludes UNK, "n/a", None and `same:*` | ✓ |
| Unseparated pairs | D = {} → reported, never solved | `unsep` listed; empty sets dropped before hitting | ✓ |
| Hitting sets | exhaustive minimum hitting sets | enumerates all subsets, keeps the ⊂-minimal ones, then the minimum-cardinality ones | ✓ (exact) |
| FORMAL-REQUIRED | in every minimum AND every inclusion-minimal set | `all(v in h for h in incl)`. Minimum sets are a subset of the inclusion-minimal ones, so this is equivalent | ✓ |
| MODEL-COND-REDUNDANT | omitted by some inclusion-minimal set | the else-branch after "appears" | ✓ (this includes variables that appear in some D but in no minimal set, e.g. RAISE k; the spec allows it) |
| NEVER-SEPARATES | appears in no D | `v not in appear` | ✓ |
| IDENTITY-LOOKUP flag | known values pairwise distinct across o's legality events (≥ 2) | ≥ 2 events with a known value, all values distinct. It does not check whether v separates | ✓ literal. The weakness is discussed in §4a |
| Forced cluster pairs | per FR v: the number of distinct cluster pairs among pairs with D = {v} | a sorted list of distinct cluster pairs, not a number | ✓ in substance, but the output is a list, not a count. Distinct is not disjoint (§4b) |
| Models | M0 {a,k,s,t}, M1 +e, M2 +c, M4 +h, MF = APPL ∪ {r} | M0–M4 are intersected with APPL ∪ {r} | ✓ (the docstring omits the intersection; wording) |
| Variants | REV: C1 plus coherent C2 (k = ruling and c = conformant on both R-81..85 ADOPT events). REV-TYPE: those k → UNK, R-91 k → header type | `variant()`; OBSERVATIONS agrees | ✓ |
| Tokens | treated as UNKNOWN, never a separating value | `isknown` excludes `same:` | ✓ (selftest 4) |
| Operation test | pairs with all MF-union vars known and equal. Power = number of pairs with ≥ 1 comparable var. **UNTESTABLE iff power = 0** | `decisive` requires all 9 known and equal. The label is UNTESTABLE iff `decisive` is empty | **✗ divergence.** By the spec's own rule, power = 248 ≠ 0, so the op is not "UNTESTABLE", and the spec gives no label for "testable but no decisive pair". The code's label is the more honest one, but it is not the spec's. The power definition does not measure what it claims |
| Status names | (not defined) | SEPARABLE / PARTIAL / UNCONSTRAINED | Wording. "PARTIAL" is also used when **no** pair is separable (ASSIGN-ID M0–M2) |

### 2.2 Prior-review items

| Item | Claimed fix | Status | Evidence |
|---|---|---|---|
| D1 consistency-by-ignorance | UNK never separates; unseparated pairs are reported | **FIXED** | ASSIGN-ID MF now gives {h} only, with r NEVER-SEPARATES. RAISE/SUPERSEDE x is NEVER-SEPARATES. REV-TYPE ADOPT k is NEVER-SEPARATES. **Residual (the dual):** UNK on the real separator forces a surrogate variable (START t via the git event's s = UNK; see D-N1) |
| D2 M3 zero power | renamed MO, power reported, UNTESTABLE | **PARTIAL** | The label is correct now. The test is unchanged (it still needs all 9 known, and h is UNK on every non-ASSIGN event while a/t/r are UNK on the ASSIGN events), so it still has zero power by construction. The "power" of 248 counts every cross-op opposite pair. The spec rule "power = 0 → UNTESTABLE" is not what the code does |
| D3 half-applied C2 | coherent C2 | **FIXED** | REV and REV-TYPE: E1 and E2 both have c = conformant (k = ruling in REV). The spurious {c} singleton is gone, and a is FORMAL-REQUIRED in ADOPT in every variant |
| D4 identity lookups | flag added | **PARTIAL** | The flag exists, but it is trivial for 2-event ops, fires on NEVER-SEPARATES variables, and misses pair-level lookups (§4a) |
| D5 tokens counted as known | tokens are UNK | **FIXED** | BASE ADOPT k, c and t are now NEVER-SEPARATES. Note: this is an interpretive choice, and it discards the fact that `same:R-81..85` ≠ `R-91` as targets (D-N6) |
| D7 clusters ignored | forced cluster pairs | **PARTIAL** | Clusters are now reported, but as distinct pairs, not disjoint ones, and within-cluster pairs appear as (X,X). START t lists 5 pairs that all run through one event |
| D6 NOT-IN-FORCE treated as opposite | — | **OPEN** | AUTHORIZE-IMPL a is FR only because of it |
| D8, D9, D16 bisimulation | — | **OPEN / out of scope** | r3 does not re-run the bisimulation ("r2 outputs stand") |
| D10 wording | — | **PARTLY OPEN** | FR/MCR shorthand remains; new undefined status names |
| D11, D12 unverifiable F-LOG / EMPIRICAL | — | **OPEN** | F-LOG-0153 is cited for C2 and is unverifiable. h's "empirical" entry is still a formal statement |
| D13 global minimum | — | **OPEN / moot** | r3 computes no global minimum (see §5 for a hand computation) |
| D14 UNK vs n/a | — | **OPEN** | No effect, since both are unknown to `isknown` |
| D15 M3 name clash | renamed MO | **FIXED** (docstring) | |

---

## 3. Recomputation (hand, from OBSERVATIONS.json)

**Setup.**
- 35 legality events: 17 PERFORMED and 18 non-PERFORMED. `SUPERSEDE PB-006 row declined (R-94)` is CHOICE and is dropped.
- Unless noted, results are identical for BASE, REV and REV-TYPE.
- Notation: P = PERFORMED, N = non-PERFORMED.

### ADOPT (E1 = Chief declined, N; E2 = DA (R-86), P; E3 = R-91 held, N)

| Variant | D(E1,E2) | D(E2,E3) | Unseparated | M0/M1/M4 | M2/MF | Classes | Match |
|---|---|---|---|---|---|---|---|
| BASE | {a} (ARB-CHIEF vs DA; s and r equal; k, t, c are tokens) | {} (a equal; k, t, c: token on E2) | (E2,E3) in all models | {a} | {a} | a FR; k, s, t, c, r NEVER | ✓ |
| REV | {a} (c conformant = conformant) | M0/M1/M4 {}; M2/MF {c} (conformant vs collapsed; t token on E2; k ruling = ruling) | (E2,E3) in M0/M1/M4 | {a} | {a,c} | M2/MF: a, c FR | ✓ |
| REV-TYPE | {a} | as REV (k UNK on E2) | as REV | {a} | {a,c} | as REV | ✓ |

- **Forced cluster pairs:**
  - a: (R-81..85-annotation, R-86);
  - c: (R-86, R-91).
- **Flags:** none. a = {CHIEF, DA, DA} is not distinct. k, t and c have fewer than 2 known values, except REV c = {conformant, conformant, collapsed}, which is not distinct. ✓

### AUTHORIZE-IMPL (R-70 P, R-89 NOT-IN-FORCE)

- D = {a}; k, s, t and r are equal.
- {a} is the only guard: a FR, the rest NEVER.
- Flag: a (2 distinct values).
- Forced pair: (R-70, R-89). ✓

### REGISTER (both in cluster S0804-L576)

- D = {k}. Guard {k}. Flag: k.
- Forced pair: (S0804-L576, S0804-L576). ✓

### OPEN-WORK (both in cluster R-60)

- D = {k} (recording-note vs ruling; a and t equal).
- Flag: k. Forced pair: (R-60, R-60). ✓

### ASSIGN-ID

- M0/M1/M2 pool {k,s}: D = {}. The pair is unseparated, no guards exist, and k and s are NEVER.
- M4 {k,s,h} and MF {k,s,h,r}: D = {h} (unused vs retired; r is UNK in both events).
  - {h}: h FR, flagged.
  - Forced pair: (S0804-L576, register-numbering).
- ✓ The D1 artefact {r} is gone.

### START (P: git, R-47a, R-58a, R-65, R-86. N: R-72, R-79, R-47b, R-56, R-58b, R-81. 30 pairs)

- a, k and r are constant, so they are NEVER.
- Pairs with the git event (s = UNK):
  - vs R-72 (WP-4B): D = {}. This is the only unseparated pair.
  - vs R-79, R-47b, R-56, R-58b, R-81: D = {t} each.
- Pairs without the git event:
  - same target: D = {s}. These are 7B R-58a vs R-47b and vs R-56; 7C R-65 vs R-58b; §12 R-86 vs R-81.
  - otherwise: D = {s,t}.
- Minimal and minimum guard: {s,t}. Both s and t are FR.
- Forced pairs:
  - s: (R-47,R-58), (R-56,R-58), (R-58,R-65), (R-81,R-86);
  - t: (R-47|R-56|R-58|R-79|R-81, git-6a67da5d7).
- No flags. ✓

### RAISE (P: P2, P3, P9, P10 [R-36, e = 2+], P1/R-41 [a = PA, k = UNK, t = ES-004.3, e = 1]. N: N5, N14, N17 [R-36, e = 1], L493-B [PROMOTION, observation, methodology, e = 1]. 20 pairs)

| Pair type (count) | BASE D (MF) | REV/REV-TYPE D (MF) |
|---|---|---|
| P\* vs N\* (12) | {e} (M0/M2/M4: {}) | same |
| P\* vs L (4) | {a,k,t,e,r} (M0 {a,k,t}) | {k,t,e,r} (M0 {k,t}): a is UNK by C1 |
| P1 vs N\* (3) | {a,t} | {a,t} |
| P1 vs L (1) | {a,t,r} (M0 {a,t}) | {t,r} (M0 {t}) |

| Model | BASE minimal = minimum | REV/REV-TYPE minimal / minimum | Match |
|---|---|---|---|
| M0, M2, M4 (12 unseparated) | {a}, {t}. a, t, k MCR; s NEVER | {t} / {t}. t FR (forced by R-41 vs S0815-L483); a, k MCR | ✓ |
| M1 | {a,e}, {e,t}. e FR (R-36, R-36) | {e,t} / {e,t}. e and t FR | ✓ |
| MF | {a,e}, {e,t}. e FR; a, k, t, r MCR; x NEVER | {e,t}, {a,e,r} / {e,t}. e FR; a, k, t, r MCR; x NEVER | ✓ |

**Note.** C1 now changes the RAISE results (in r2 it changed nothing): t becomes FR in M0 through M4.

### SUPERSEDE (R-77 N, R-83 N, D-12 P; R-94 is CHOICE and is dropped)

- D-12 has a and e UNK.
- M0 through M4: D = {k} for both pairs, giving {k}. k is FR; a and e are NEVER.
  - Flags: a, k, and also e in M1. a and e are flagged even though they never separate.
  - Forced pairs: (ADR-MP, R-77) and (ADR-MP, R-83).
- MF: D = {k,r} for both pairs, giving {k} and {r}, both MCR with no forced pairs.
  - Flags: a, e, k. r is not distinct (RULING appears twice); x is all UNK.
- ✓

### ANNOTATE

One event, UNCONSTRAINED. ✓

### Operation test

- Cross-op opposite-outcome pairs = 17·18 − Σ over ops of P_o·N_o = 306 − 58 = **248** ✓. Every one of them has at least one comparable variable.
- No event has all 9 variables known, so `decisive = []` ✓ in all three variants.

**Result: recomputation_matches = true; mismatches = [].**

---

## 4. Instrument weaknesses

### (a) The identity-lookup flag

- **With exactly 2 legality events of opposite outcome,** "known values pairwise distinct" is equivalent to "v separates the only pair". The flag therefore just repeats the FR label. This covers AUTHORIZE-IMPL a, OPEN-WORK k, REGISTER k and ASSIGN-ID h. The flag carries no information there.
- **It is computed over all events, not over separating use.** So it fires on NEVER-SEPARATES variables: SUPERSEDE a (ARB vs ARB-CHIEF, both REFUSED) and e.
- **It misses pair-level lookups.** A variable can have repeated values overall while the *decisive* values are singletons:
  - START t: the git side's value "WP-4B, PERFORMED" occurs once;
  - RAISE t (REV): ES-004.3 and methodology each occur once.

  Neither is flagged.

**Proposed criterion (cross-cluster value recurrence plus leave-one-cluster-out).**

1. **Recurrence.** For each forced pair (p,q) of v, the separation is *generalizing* only if the value p[v] recurs with p's outcome in another cluster, **and** q[v] recurs with q's outcome in another cluster. A separation is flagged LOOKUP if neither side recurs, and HALF-LOOKUP if only one side does.
2. **LOCO test.** For operations with ≥ 3 clusters, drop one cluster at a time, recompute the minimum guards, and check whether they predict the held-out events. A variable whose separations all fail LOCO is non-generalizing.
3. **Operations with 2 events or 1 cluster** are reported as **INSUFFICIENT FOR GENERALIZATION** instead of a flag.

Applied to the data:

| Variable (op) | Verdict |
|---|---|
| s (START) | Generalizing: "authorized" recurs as P in R-47, R-58, R-65, R-86; "not-authorized" recurs as N in R-47, R-56, R-58, R-81 |
| t (START) | LOOKUP: WP-4B/P occurs only in git |
| t (RAISE REV) | LOOKUP |
| e (RAISE) | Single-cluster: 2+ occurs only in R-36 |
| k (SUPERSEDE) | LOOKUP |
| a (ADOPT) | HALF: ARB-CHIEF/N occurs once; DA occurs with both outcomes |
| c (ADOPT) | HALF: conformant recurs (R-81..85-annotation, R-86), but with both outcomes; collapsed occurs once |
| a (AUTHORIZE-IMPL), k (REGISTER, OPEN-WORK), h (ASSIGN-ID) | INSUFFICIENT |

### (b) Independent support: disjoint cluster pairs

"Distinct cluster pairs" overcounts whenever pairs share a cluster. The count below is the maximum number of pairwise-disjoint forced cluster pairs, i.e. a maximum matching.

- **Within-cluster pairs.** A pair such as (R-60, R-60) counts as 1, the cluster itself, per SCHEMA R4. It is **not** a contrast between independent decisions, so I also give the cross-cluster-only count.

| FR variable | Where FR | Forced pairs (distinct) | Disjoint (R4) | Cross-cluster disjoint | Per-op max |
|---|---|---|---|---|---|
| a | ADOPT (all variants), AUTHORIZE-IMPL | 2 | **2** | 2 | 1 |
| k | REGISTER, OPEN-WORK, SUPERSEDE (M0–M4) | 1 + 1 + 2 | **3** | 1 (one SUPERSEDE ADR-MP pair) | 1 |
| s | START | 4 | **2**: e.g. (R-47,R-58) + (R-81,R-86) | 2 | **2** |
| t | START (all variants), RAISE (REV/REV-TYPE, M0–M4) | 5 + 1 | **2** | 2 | 1 (START's 5 are a star on git-6a67da5d7) |
| e | RAISE (M1, MF) | 1 (R-36, R-36) | **1** | 0 | 1 |
| c | ADOPT (REV/REV-TYPE, M2/MF) | 1 | **1** | 1 | 1 |
| h | ASSIGN-ID (M4, MF) | 1 | **1** | 1 | 1 |
| r, x, o | never FR | — | 0 | 0 | 0 |

Only `s` has more than one independent forced contrast within one operation. Every other FR claim rests on a single decision, or a single event, per operation.

### (c) SUPERSEDE data scope

- **RESULT-1AM-SUPERSEDE** uses 4 events, including `SUPERSEDE mechanism by R-44 (reported in R-57)`. That event is **absent from r4 / OBSERVATIONS**, which has only 3 SUPERSEDE legality events.
- With it, {r} and {a} become inconsistent through the conflict pair (R-77, R-44). So R-44 is PERFORMED, on the RULING route, with actor ARB.
- **Consequence:** r3's MF result "{k} or {r}" is **not consistent** with 1AM. On the fuller data {r} would fail, and only {k} could remain.
- Whether {k} actually *separates* (R-77, R-44) under r3 semantics cannot be decided. 1AM's "{k} consistent_after" was computed under the old consistency-by-ignorance rule, so it may rest on k = UNK for R-44. If it does, r3 on the fuller data would report (R-77, R-44) as unseparated in M0 through M4.
- **Verdict:** supersede_scope_issue = **true** (evidence scope).

---

## 5. Minimality report (per variable)

**Column meanings.**
- *Removal test* = delete v from the pool and recompute; D(p,q) sets that equal {v} become unseparated.
- *Counterexample* = the forced pair.
- *Independent support* = disjoint cluster pairs (from §4b).

| VAR | WHY IT APPEARED | FORMAL MODEL | REMOVAL TEST | COUNTEREXAMPLE (forced by) | RESULT | SCOPE | EMPIRICAL STATUS (imported) | INDEPENDENT SUPPORT |
|---|---|---|---|---|---|---|---|---|
| **a** | actor/authority | M0–MF; ADOPT, AUTHORIZE-IMPL (also RAISE, SUPERSEDE, REGISTER, OPEN-WORK, START pools) | ADOPT − a: (E1,E2) unseparated. AUTHORIZE-IMPL − a: (R-70, R-89) unseparated | `ADOPT R-81..85 by the Chief (declined)` vs `…by the DA (R-86)`; `AUTHORIZE-IMPL R-70 (ARB)` vs `R-89 (Chief, PREPARED)` | FR: ADOPT (all variants, all models), AUTHORIZE-IMPL (all). MCR: RAISE (all). NEVER: REGISTER, OPEN-WORK, START, SUPERSEDE (a UNK on D-12) | AUTHORIZE-IMPL depends on NOT-IN-FORCE being "opposite" (D6), and a stands in for PREPARED status there | SUPPORTED | 2 (1 per op) |
| **k** | object kind | M0–MF; all ops | REGISTER − k and OPEN-WORK − k: the pair is unseparated. SUPERSEDE M0–M4 − k: both pairs unseparated | `REGISTER operational acceptance (R-90)` vs `constitutional decision (rule)`; `OPEN-WORK by a recording note (R-60)` vs `by a ruling (R-60 refiled)`; `SUPERSEDE ADR-T14 (R-77)` / `§12 (R-83)` vs `D-12 by ADR-MP` | FR: REGISTER, OPEN-WORK, SUPERSEDE (M0–M4). MCR: SUPERSEDE MF (tied with r), RAISE. NEVER: ADOPT, START, AUTHORIZE-IMPL, ASSIGN-ID | REGISTER and OPEN-WORK are within-cluster. SUPERSEDE is an identity lookup (3 distinct kinds) and scope-bound (§4c) | SUPPORTED | 3 by R4; 1 cross-cluster |
| **s** | state before the act | M0–MF; START | START − s: 4 pairs unseparated | `START 7B at R-56` vs `7B at R-58`; `7C at R-58` vs `7C at R-65`; `§12 at R-81` vs `§12 at R-86`; `7B after AUTHORIZE(7A)` vs `7B at R-58` | FR: START (all). NEVER elsewhere | Same target across time; generalizing (§4a). (R-72, git) remains unseparated because git's s is UNK | SUPPORTED (target-indexed) | **2** |
| **t** | target/scope | M0–MF; START, RAISE | START − t: 5 git pairs unseparated. RAISE (REV, M0–M4) − t: (P1, L493-B) unseparated | `START WP-4B 08-03 16:10` vs `START WP-8`, `7B after AUTHORIZE(7A)`, `7B at R-56`, `7C at R-58`, `§12 at R-81`; RAISE `P1 / R-41` vs `L493-B` | FR: START (all variants), RAISE REV/REV-TYPE (M0–M4). MCR: RAISE BASE, RAISE REV MF. NEVER: ADOPT (tokens), AUTHORIZE-IMPL, OPEN-WORK | **Required by ignorance:** START t is forced only because the git event's s is UNK. RAISE t exists only in REV, where C1 makes L493-B's a UNK. Both are lookups | index of s; possible witness only | 2 (1 per op; START's is a star on one event) |
| **e** | evidence (repetition count) | M1, MF; RAISE (SUPERSEDE pool) | RAISE − e: the 12 P\*/N\* pairs are unseparated | `RAISE promoted #2` vs `RAISE not promoted #5` (and 11 more, all in R-36) | FR: RAISE M1/MF (all variants). NEVER: SUPERSEDE (D-12 e is UNK) | Single decision matrix (R-36). Note e = 1 also appears on PERFORMED P1 | WEAK (overloaded) | 1 (within-cluster) |
| **c** | role conformance | M2, MF; ADOPT | ADOPT REV − c: (E2,E3) unseparated | `ADOPT R-81..85 by the DA (R-86)` vs `ADOPT R-91 by the DA (held)` | FR: ADOPT REV/REV-TYPE (M2/MF). NEVER in BASE (token) | Variant-dependent: exists only through C2 coding (F-LOG-0153, unverifiable) | WEAK (coarse grain) | 1 |
| **h** | history (unused/retired) | M4, MF; ASSIGN-ID | ASSIGN-ID − h: the pair is unseparated (M0–M2 already show this) | `ASSIGN a never-used number` vs `ASSIGN the retired number R-90` | FR: M4/MF (all variants) | 2-event op, so the flag is trivial. One contrast | absorbed into status (formal). *This is a formal statement in the empirical slot* | 1 |
| **r** | route | MF only | removal changes no result (never FR) | none forced. SUPERSEDE {r} and RAISE {a,e,r} (REV) are alternatives | MCR: SUPERSEDE MF, RAISE MF. NEVER: all others (ASSIGN-ID all UNK) | SUPERSEDE {r} is refuted in scope by the R-44 event in 1AM (§4c) | NOT DEMONSTRATED | 0 |
| **x** | exception | MF; RAISE, SUPERSEDE | no effect | none (only `none-stated` is known; UNK elsewhere) | NEVER-SEPARATES (untested, not redundant) | no data | NOT DEMONSTRATED | 0 |
| **o** | operation label | MO | not performable: no cross-op pair has all 9 variables known | none possible | UNTESTABLE (the label is correct; the stated power of 248 is not a power) | The instrument has zero power by construction | NOT DEMONSTRATED | 0 |

---

## 6. Candidate minimal theory

A per-operation guard that hits every non-empty D(p,q) in **all three variants** under MF (the largest pool):

| Operation | Guard | Robust across variants? | Single-cluster dependence | Residual unseparated |
|---|---|---|---|---|
| ADOPT | **{a, c}** (BASE alone needs only {a}) | a robust; **c variant-dependent** (REV/REV-TYPE, C2) | a: one pair; c: one pair | BASE (E2,E3); M0/M1/M4 (E2,E3) in every variant |
| AUTHORIZE-IMPL | {a} | robust | one pair; depends on the NOT-IN-FORCE coding | — |
| REGISTER | {k} | robust | within one cluster (S0804-L576) | — |
| OPEN-WORK | {k} | robust | within one cluster (R-60) | — |
| START | {s, t} | robust | s: 2 disjoint; **t: one event (git)** | (R-72, git) |
| RAISE | **{e, t}**, the unique minimum valid in all variants ({a,e} fails REV; {a,e,r} has size 3) | e robust; **t variant-dependent** (FR in REV; in BASE it is interchangeable with a) | e: R-36 only; t: one pair | M0/M2/M4: 12 pairs |
| ASSIGN-ID | {h} | robust | one pair | M0–M2 |
| SUPERSEDE | {k} (r is an alternative only on r4 data, and is refuted by the 1AM scope) | robust on r4 | one PERFORMED event (ADR-MP) | — |
| ANNOTATE | ∅ (unconstrained) | — | — | — |

**Variable union: {a, c, e, h, k, s, t} (7).** This is unique under MF across all variants:
- ADOPT REV forces a and c;
- START forces s and t, so RAISE's t is free;
- RAISE forces e;
- REGISTER and OPEN-WORK force k, so SUPERSEDE's k is free;
- ASSIGN-ID forces h.

Per variant:
- **BASE:** {a, e, h, k, s, t} (6); in RAISE, {a,e} and {e,t} are both free.
- **REV and REV-TYPE:** {a, c, e, h, k, s, t} (7).

r, x and o are never needed.

**Classification of the parts:**
- **Robust across variants:** a (ADOPT, AUTHORIZE-IMPL), k (REGISTER, OPEN-WORK, SUPERSEDE), s and t (START), e (RAISE), h (ASSIGN-ID).
- **Variant-dependent:** c (ADOPT), and t in RAISE.
- **Dependent on a single cluster or event:** everything except s. Specifically:
  - e (R-36);
  - k in OPEN-WORK (R-60) and in REGISTER (S0804-L576);
  - k in SUPERSEDE (the single PERFORMED event ADR-MP);
  - t in START (git-6a67da5d7, via s = UNK);
  - t in RAISE (R-41 / S0815-L483);
  - h, c, and a in each of its operations.

All of this is formal and model-relative. It is not an empirical claim about the governance rules.

---

## 7. Model equivalences and separating observations

| Op / variant / model | Tied guards | Future observation that separates them |
|---|---|---|
| RAISE BASE M0/M2/M4 | {a} vs {t} | A PA-instructed raise on AST-013 (or an ARB raise on ES-004.3). PERFORMED supports {a}; REFUSED supports {t} |
| RAISE BASE M1/MF | {a,e} vs {e,t} | A PA-instructed raise on AST-013 with e = 1: {a,e} predicts PERFORMED (like P1); {e,t} predicts REFUSED (like N\*). Also an ARB raise with e = 1 on target ES-004.3. Coding L493-B's actor (BASE PO/ARB vs REV UNK) decides whether {a,e} survives; that is coding, not observation |
| RAISE REV MF | {e,t} (minimum) vs {a,e,r} (minimal) | A PA-instructed RULING-route raise on `methodology` with e = 1: {e,t} predicts REFUSED (like L493-B); {a,e,r} predicts PERFORMED (like P1) |
| SUPERSEDE MF | {k} vs {r} | **Already available outside r4:** `SUPERSEDE mechanism by R-44 (reported in R-57)` (RULING route, ARB, PERFORMED) breaks {r}. On r4 alone: a RULING-route supersession of a decision-log-entry, or an ADR-ACCEPTANCE supersession of an ADR/design-rule, would separate them |
| START {s,t} vs {s} (latent) | t is needed only because git's s is UNK | Source the authorization state behind `START WP-4B 08-03 16:10`. If s = authorized, t becomes non-required and {s} suffices. If s = auth-proviso-unmet, the pair (R-72, git) stays unseparated and the pool is inadequate |
| ADOPT M0 {a} vs M2 {a,c} (REV) | not equivalent on the data: M0 leaves (E2,E3) unseparated | Resolve R-81..85's intrinsic kind vs R-91's in REV-TYPE. A DA adoption of a `collapsed`-conformance object that is PERFORMED would refute c; one that is REFUSED on a conformant object would refute {a,c} |
| AUTHORIZE-IMPL {a} vs "status" (outside the pool) | a carries the PREPARED/in-force difference | A Chief authorization issued in force (not PREPARED), or an ARB authorization left PREPARED |

---

## 8. Disagreement register

| ID | Disagreement | Category |
|---|---|---|
| D-N1 | Required-by-ignorance (the dual of D1): START t is FORMAL-REQUIRED only because the git event's s is UNK. All 5 forced pairs are one event | **formal reasoning** |
| D-N2 | The operation test is still an all-9-known test (zero power by construction). "Power = 248" is the total number of cross-op opposite pairs. The code's UNTESTABLE rule differs from the docstring's "power = 0" rule | **coding** (spec divergence) / formal reasoning |
| D-N3 | The identity flag is trivial for 2-event ops, fires on NEVER-SEPARATES variables (SUPERSEDE a, e), and misses pair-level lookups (START t, RAISE t) | **formal reasoning** |
| D-N4 | Forced cluster pairs count distinct pairs, not disjoint ones. Within-cluster pairs such as (R-60, R-60), (S0804-L576, S0804-L576) and (R-36, R-36) are not independent contrasts. START t (5 pairs) has 1 disjoint pair; s (4 pairs) has 2; SUPERSEDE k (2 pairs) has 1 | **independence** |
| D-N5 | SUPERSEDE "{k} or {r}": r4 lacks the R-44 event used in RESULT-1AM, and that event breaks {r} and {a} | **evidence scope** |
| D-N6 | Treating `same:R-81..85` as UNK for **t** discards known identity. The target of E1/E2 is the object R-81..85, which is known to differ from R-91. This is a deliberate anti-lookup choice and it changes ADOPT's (E2,E3) from "separated by t" to unseparated | **source interpretation** |
| D-N7 | C1 (L493-B a → UNK) now changes the RAISE results (t becomes FR in M0 through M4, and {a,e} fails in REV). In r2 it changed nothing | **coding** |
| D6 | NOT-IN-FORCE is treated as opposite to PERFORMED. AUTHORIZE-IMPL a FR depends on it | **source interpretation** |
| D-N8 | "PARTIAL" is used even when no pair is separable (ASSIGN-ID M0–M2). Status names are undefined. The model definitions omit the intersection with APPL. The legality wording ("PERFORMED or RULE") differs from the code (≠ CHOICE), though they are equivalent on this data | **wording** |
| D-N9 | "Independence" is emitted as a list, not the number the docstring specifies | **wording** |
| D12 | EMPIRICAL statuses and F-LOG-0153 are unverifiable. h's entry is formal, not empirical. k is listed as "SUPPORTED" while its formal forced support is within-cluster (these are not the same test; no contradiction is claimed) | **evidence scope** |
| D13 | r3 reports no global minimum. The hand result is {a,c,e,h,k,s,t} across variants, or {a,e,h,k,s,t} for BASE | **evidence scope** |
| D8/D9/D16 | The bisimulation caveats are unaddressed (r3 does not re-run it) | **formal reasoning** (carried over) |
| D14 | UNK vs n/a coding | **coding** (no effect) |

**Not in dispute:** every number in RESULT-r3.json, the hitting-set enumeration, and the coherent application of C2.

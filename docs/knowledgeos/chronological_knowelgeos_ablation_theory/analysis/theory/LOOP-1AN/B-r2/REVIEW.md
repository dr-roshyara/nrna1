# Independent review of 1an (formal minimization, r2)

Reviewer basis: only `minimize_1an.py`, `RESULT.json`, `RESULT-r1-defective-fulllabels.json`, `OBSERVATIONS.json`, `SCHEMA.md` and `m3_check.py`. I did not run any code. Every number below was recomputed by hand from those files. `models_1al.py`, 1al itself and the F-LOG record were not supplied. Any claim that depends on them is marked **unverifiable**.

---

## 1. Summary

- **Recomputation: everything matches.** I recomputed every operation × variant × model (M0, M1, M2, M4, MF) by hand. This covers the minimal signatures, the variable classes, the MODEL-INADEQUATE conflict pairs, the global minima (5 / 6 / 5), the M3 result, and the reachable-state count (250). I also recomputed both bisimulation class counts (80 for r2, 151 for r1). I found **no numeric mismatch** with `RESULT.json`.
- **Spec fidelity: the code computes what the docstring says.** The divergences are all wording or under-specification, and none of them changes a number (§2). There are two exceptions to note. The docstring promises a "minimal distinguishing situation", but the code returns the first pair in BFS order; here those pairs happen to be the shallowest possible. The docstring also leaves the VACUOUS-vs-signature precedence undefined.
- **The r1 → r2 label fix changes no family class, and this can be proven** (§7.3). r2 is faithful only if its claim that "1al used operation-name labels" is true, and that cannot be checked from these files.
- **The main problems are in interpretation, not arithmetic:**
  1. **Consistency by ignorance.** Under the rule "an UNK never creates a conflict", a variable that is UNK on the decisive event forms a consistent signature without separating anything. This is how the following get listed as minimal signatures even though the script itself classes each one VACUOUS:
     - `r` for ASSIGN-ID (all UNK);
     - `x` for RAISE and for SUPERSEDE;
     - `k` for ADOPT in REV-TYPE.

     Those signatures then demote real discriminators: `h` in ASSIGN-ID (MF), and `a` and `t` in ADOPT (REV-TYPE). They also create ties in the global minimum.
  2. **The M3 operation test has zero power.** It requires all 9 pooled variables to be known and equal. Every legality event has at least one UNK among those 9, so a conflict is impossible by construction. "o MODEL-COND-REDUNDANT under M3" should read "UNTESTABLE".
  3. **Object-identity lookups.** `t` for ADOPT, `k` for SUPERSEDE, and the `same:` tokens for `k`/`c`/`t` in BASE ADOPT each take one value per object. `t`'s FORMAL-REQUIRED status in REV ADOPT only memorizes which object was involved.
  4. **REV correction C2 is half-applied to `c`.** Only the DA event is set to `conformant`. The Chief event keeps the identity token `same:R-81..85`, which contradicts schema R2 (`c` is intrinsic and shared by identity). This artifact is what produces the singleton signature `{c}` and demotes `a` in REV (M2/MF).
  5. **Bisimulation "MODEL-COND-REDUNDANT" families pass vacuously.** `text` and `deleg` never change in the unrelaxed model. `iss`, `by` and `reg` are functions of the other components. No reachable pair that differs only in F exists, so nothing was actually tested.

---

## 2. Spec fidelity

### 2.1 Checked items

| Item | Docstring | Code | Verdict |
|---|---|---|---|
| Legality filter | CHOICE events never enter legality | `e["ground"] != "CHOICE"` (guard analysis and M3) | ✓ (35 of 36 events; only `SUPERSEDE PB-006 row declined (R-94)` is dropped) |
| Consistency | no two events with known, equal values on all of V and opposite outcomes; UNK never conflicts; `same:X` equals only `same:X` | `conflict()` with `known()` excluding UNK, n/a, None; string equality | ✓ |
| Minimal subsets | minimal consistent subsets of the pool | enumerates all subsets, keeps the ⊂-minimal ones | ✓ (consistency is monotone, so this is exact) |
| M0/M1/M2/M4 | {a,k,s,t} + e / c / h, intersected with APPL[o] ∪ {r} | `vs & appl` | ✓ (r is never in M0–M4, because none of them list it) |
| MF | APPL[o] ∪ {r} | `appl` | ✓ |
| M3 | all ops pooled, pool = MF-vars of all ops, o removed | fixed pool `akstexhcr` = ∪APPL ∪ {r} | ✓ (identical set) |
| Classes | FR / MCR / VACUOUS / MODEL-INADEQUATE / UNCONSTRAINED | VACUOUS is tested first, then FR, then MCR; INADEQUATE and UNCONSTRAINED are op-level statuses | ✓ with divergences D-a and D-b below |
| Global minimum | one minimal signature per constrained op, minimizing the size of the union | product over MF-CONSISTENT ops, all ties enumerated | ✓ (restricting to minimal signatures is enough, because a superset never shrinks a union) |
| REV C1 | L493-B a → UNK | `startswith("RAISE L493-B")` | ✓ (OBSERVATIONS REV agrees) |
| REV C2 | R-81..85 k = "ruling" for both ADOPT events; R-86 c = "conformant" | both `ADOPT R-81..85…` events get k = ruling; only the `(R-86)` event gets c = conformant | ✓ against the docstring text; see D3 for the conflict with schema R2 |
| REV-TYPE | REV, but R-81..85 k → UNK; R-91 k → determination-on-submitted-evidence | as stated | ✓ (OBSERVATIONS REV-TYPE agrees) |
| Bisimulation | partition refinement; per family F: MCR iff every pair of reachable states equal off F is bisimilar, else a counterexample | standard signature refinement; family grouping by prefix of B0 keys | ✓ (8 families derived from B0 = the 8 listed) |
| Event count | 36 | BASE has 36 events (35 legality) | ✓ |

### 2.2 Divergences (all are non-numeric)

- **D-a. Class precedence is undefined in the docstring.** A variable can meet both the VACUOUS definition and the "in a minimal subset" condition. The code gives VACUOUS precedence. As a result, VACUOUS variables sit inside reported minimal signatures:
  - `k` in REV-TYPE ADOPT (all models);
  - `x` in RAISE MF and SUPERSEDE MF;
  - `r` in ASSIGN-ID MF.

  In each case the class label contradicts the signature list.
- **D-b. Status vs class.** The docstring lists MODEL-INADEQUATE and UNCONSTRAINED as per-variable classes. The code emits them only as op-level statuses. This is wording only.
- **D-c. "a minimal distinguishing situation".** The code returns the first non-bisimilar pair in the BFS-ordered groups, and there is no minimality criterion. In this LTS the returned pairs happen to be the shallowest possible (depth 1 vs 2, 1 vs 2, 2 vs 3), so the outcome is unaffected.
- **D-d. Output wording.**
  - The docstring requires "MODEL-CONDITIONAL REDUNDANCY under model M and the tested transition semantics" and "FORMAL REQUIREMENT under M". RESULT uses the shorthand `MODEL-COND-REDUNDANT` / `FORMAL-REQUIRED`, nested under a model key.
  - The bisimulation's non-redundant class `BEHAVIOUR-RELEVANT` is not defined in the docstring.
- **D-e. The outcome coding is 3-valued and "opposite" is undefined.** Schema outcomes are {PERFORMED, REFUSED, NOT-IN-FORCE}. The code treats NOT-IN-FORCE as not-performed, so it becomes "opposite" to PERFORMED. AUTHORIZE-IMPL is constrained only because of this choice (see D6).
- **D-f. r2 is a post-freeze change** to a script that was "committed before its first run". The change is disclosed and kept as a variant. The fact it relies on (1al's labels) is **unverifiable** here.
- **D-g. Name clash.** "M3" means the global operation-pooled model in the docstring, and the whole LTS in `m3_check.py`. Wording.

### 2.3 Assessment of the r1 → r2 fix

- **Where it changes things.** The fix touches only `bisim_components`. The guard sections of r1 and r2 are identical, and `bisimulation_variant_full_labels_r1` reproduces r1 exactly (151 classes, same families, same counterexamples).
- **Family classes: none change** (proof in §7.3).
- **Faithfulness:**
  - The refinement itself is correct.
  - The label abstraction `lab.split("(")[0]` drops both the ruling index (r1/r2) and the actor (human/chief, Authority/Chief).
  - r2 is faithful **if** 1al used operation-name labels, which cannot be verified here.
  - Operation-name labels are the weaker observer. For example, (A0,A2) ~ (A1,A1): the model can no longer tell which ruling was annotated.

---

## 3. Recomputation (hand, from OBSERVATIONS.json)

Notation: E1 = `ADOPT R-81..85 by the Chief (declined)` (REFUSED), E2 = `…by the DA (R-86)` (PERFORMED), E3 = `ADOPT R-91 by the DA (held)` (REFUSED). The opposite-outcome pairs are (E1,E2) and (E2,E3).

### 3.1 ADOPT (the only operation that differs across variants)

**BASE**
- (E1,E2) differ only in `a` (the k/t/c tokens are equal).
- (E2,E3) differ in k, t and c (a token vs a value).
- M0/M1/M4: {a,k}, {a,t}.
- M2/MF: {a,c}, {a,k}, {a,t}.
- Classes: a FR; k, t, c MCR; s VACUOUS (r VACUOUS in MF).
- Matches RESULT ✓.

**REV** (k = ruling for all three events; c: E1 = same:R-81..85, E2 = conformant, E3 = collapsed)
- (E1,E2) are separated by `a` or `c`.
- (E2,E3) are separated by `t` or `c`.
- M0/M1/M4: {a,t} is unique, with a and t FR and k, s VACUOUS.
- M2/MF: {c}, {a,t}, with a, t, c MCR.
- Matches RESULT ✓.

**REV-TYPE** (k: E1 = UNK, E2 = UNK, E3 = determination-on-submitted-evidence)
- `k` never conflicts, so {k} is consistent. {a,t} is also consistent.
- M0/M1/M4: {k}, {a,t}.
- M2/MF: {c}, {k}, {a,t}.
- k is VACUOUS (1 known value); a and t are MCR.
- Matches RESULT ✓.

### 3.2 All operations (identical in BASE / REV / REV-TYPE unless noted)

| Op | Legality events | M0 minimal | M1 | M2 | M4 | MF minimal | MODEL-INADEQUATE (conflict pair) | Match |
|---|---|---|---|---|---|---|---|---|
| ADOPT | 3 | see §3.1 | = M0 | see §3.1 | = M0 | see §3.1 | — | ✓ |
| ANNOTATE | 1 | UNCONSTRAINED | ← | ← | ← | ← | — | ✓ |
| ASSIGN-ID | 2 | INADEQUATE (pool {k,s}) | INADEQ. | INADEQ. | {h} | {h}, {r} | M0/M1/M2: (`ASSIGN a never-used number`, `ASSIGN the retired number R-90`) | ✓ |
| AUTHORIZE-IMPL | 2 | {a} | {a} | {a} | {a} | {a} | — | ✓ |
| OPEN-WORK | 2 | {k} | {k} | {k} | {k} | {k} | — | ✓ |
| RAISE | 9 | INADEQUATE | {a,e}, {e,k}, {e,t} | INADEQ. | INADEQ. | {a,e}, {e,k}, {e,t}, {e,x} | M0/M2/M4: (`RAISE promoted #2`, `RAISE not promoted #5`), the first pair in combinations order | ✓ |
| REGISTER | 2 | {k} | {k} | {k} | {k} | {k} | — | ✓ |
| START | 11 | {s} | {s} | {s} | {s} | {s} | — | ✓ |
| SUPERSEDE | 3 (R-94 is CHOICE) | {a}, {k} | {a}, {e}, {k} | {a}, {k} | {a}, {k} | {a}, {e}, {k}, {r}, {x} | — | ✓ |

**Derivation notes**

- **RAISE.**
  - P\* (promoted, e = 2+) vs N\* (not promoted, e = 1) agree on every other known field, so `e` is forced.
  - The remaining pairs are P1/R-41 (PERFORMED, e = 1) vs N\* and vs L493-B. They are separated by:
    - `a`: PA vs ARB; and PA vs PO/ARB (BASE) or UNK (REV);
    - `k`: UNK on P1;
    - `t`: ES-004.3 is unique;
    - `x`: UNK on P1.
  - `r` does not separate P1 from N\* (RULING = RULING), so no minimal signature contains r. Its MCR label comes from having 2 known values.
  - x is VACUOUS (only `none-stated` is known).
  - The C1 correction changes nothing in RAISE.
- **START.**
  - `{t}` fails, because 7B is both REFUSED (R-47, R-56) and PERFORMED (R-58).
  - a, k and r are constant.
  - No refused event has s = `authorized`, so `{s}` is consistent and unique.
- **SUPERSEDE.** The only PERFORMED event (D-12) has a, e and x = UNK, so each of these is a consistent singleton by ignorance. `k` (3 distinct kinds) and `r` (ADR-ACCEPTANCE vs RULING) separate on known values.
- **ASSIGN-ID.** k and s are known and equal, so M0/M1/M2 conflict. `{h}` (unused vs retired) resolves the conflict. `{r}` is consistent only because r = UNK in both events.

### 3.3 Global minimum under MF

- **BASE.** Forced variables: a (AUTHORIZE-IMPL), k (REGISTER/OPEN-WORK), s (START). RAISE adds e. ASSIGN-ID adds h or r. ADOPT {a,k} and SUPERSEDE {a}/{k} add nothing. Result: size 5, {a,e,h,k,s} or {a,e,k,r,s} ✓.
- **REV.** ADOPT now needs c or t in addition, giving size 6 and four unions ✓.
- **REV-TYPE.** ADOPT {k} is already covered, giving size 5 and the same two unions as BASE ✓.

### 3.4 M3

- The pool is all 9 variables. Every legality event has at least one UNK among them (for example, h is UNK on every non-ASSIGN event, and a, t and r are UNK on both ASSIGN events).
- `conflict` therefore returns None, and RESULT's `null` is correct. The consequence is examined in D2.

### 3.5 Result

**mismatches = none.**

---

## 4. Degenerate discriminators

### 4.1 Object-identity lookups (one value per object)

| Variable / op | Values | Verdict |
|---|---|---|
| **t, ADOPT** | `same:R-81..85` (E1, E2), `R-91` (E3). One value per object | **Identity lookup.** In REV it is FORMAL-REQUIRED under M0/M1/M4 only because E2 and E3 are *different objects* with the same actor, kind and state. The "requirement" says "R-91 is not R-81..85". Only a second attempt on the same object with a different outcome could test it. |
| t, RAISE ({e,t}) | AST-013 (8 events), methodology, ES-004.3 | **Lookup on the decisive event.** P1/R-41 is the only e = 1 PERFORMED event, and its target is unique. |
| k, c, t in BASE ADOPT | the `same:` token vs a value | **All three encode the same partition** {E1,E2} \| {E3}. A token is unequal to every value, so each works as an object-identity flag. {a,k}, {a,c} and {a,t} are one signature written three ways. |
| c, REV ADOPT | `same:R-81..85`, `conformant`, `collapsed`: 3 events, 3 values | **Per-event lookup,** produced by the half-applied C2 (D3). |
| k, SUPERSEDE | ADR, design-rule, decision-log-entry: 3 events, 3 values | **Per-object lookup.** |
| t, START | — | **Not a lookup.** 7B, 7C and §12 each appear with both outcomes, so `t` is correctly MCR and `s` does the work. |
| s, START | — | **Genuine.** It separates the same target across time (R-56 → R-58, R-58 → R-65, R-81 → R-86). |

### 4.2 `r` for ASSIGN-ID

This is not an identity lookup. It is worse: r = UNK in both events, so {r} is consistent because nothing is known. The script classes it VACUOUS but still lists it as a minimal signature. That signature:

- demotes `h` from FORMAL-REQUIRED (M4) to MODEL-COND-REDUNDANT (MF);
- creates the global-minimum tie {a,e,k,r,s}.

### 4.3 VACUOUS / REDUNDANT classes that rest on coding artifacts

- **REDUNDANT via UNK on the decisive event:**
  - `h` (ASSIGN-ID, MF), because of r = UNK;
  - `a` and `t` (ADOPT, REV-TYPE, all models), because of k = UNK on both R-81..85 events;
  - `a` and `e` (SUPERSEDE), because of D-12 a = UNK and e = UNK;
  - `k` (RAISE), because the alternative {e,k} rests on P1 k = UNK.
- **REDUNDANT via the C2 artifact:** `a` in REV ADOPT (M2/MF) is demoted only by `{c}`.
- **Not VACUOUS only because a token counts as a value:** `k` and `c` in BASE ADOPT each have 2 "known values", `same:R-81..85` and a real value. If the token is read as R2 intends ("unknown but shared"), each has one known value and is VACUOUS.
- **VACUOUS because of zero data, not constancy:** `r` (ASSIGN-ID) and `x` (SUPERSEDE). The class merges "never observed" with "observed constant".
- **Unaffected by artifacts:**
  - VACUOUS: `a`, `k` and `r` in START; `s` in ADOPT, RAISE and AUTHORIZE-IMPL.
  - FORMAL-REQUIRED: `s` in START, `e` in RAISE, and `k` in REGISTER and OPEN-WORK.

---

## 5. Minimality report per variable

Rule applied: **formal redundancy is model-relative and is NOT empirical falsification.** Here, "removal test" means deleting v from the model's pool and re-testing consistency, and "counterexample" is the conflict pair that appears when v is removed. Empirical status is quoted verbatim from `EMPIRICAL` in the script.

| VARIABLE | WHY IT APPEARED | FORMAL MODEL | REMOVAL TEST | COUNTEREXAMPLE | RESULT | SCOPE | EMPIRICAL STATUS |
|---|---|---|---|---|---|---|---|
| **a** | actor/authority (who may perform: Chief vs DA vs ARB) | M0–MF; ADOPT, AUTHORIZE-IMPL, RAISE, SUPERSEDE, REGISTER, OPEN-WORK, START | pool − a | ADOPT: E1 vs E2 (equal k, s, t, c, r). AUTHORIZE-IMPL: `R-70 (ARB)` vs `R-89 (Chief, PREPARED)` | FR: ADOPT BASE (all models), REV (M0/M1/M4); AUTHORIZE-IMPL (all). MCR: RAISE, SUPERSEDE, REV ADOPT M2/MF, REV-TYPE ADOPT. VACUOUS: REGISTER, OPEN-WORK, START | Each FR rests on one contrast in one cluster pair. The REV and REV-TYPE demotions are artifacts (D3, D1). AUTHORIZE-IMPL FR depends on NOT-IN-FORCE counting as "opposite" (D6), and the R-89 contrast is really issuer → PREPARED status | SUPPORTED |
| **k** | object kind (what is acted on) | M0–MF; all ops | pool − k | REGISTER: `operational acceptance (R-90)` vs `constitutional decision (rule)`. OPEN-WORK: `by a recording note (R-60)` vs `by a ruling (R-60 refiled)` | FR: REGISTER, OPEN-WORK. MCR: BASE ADOPT (token), RAISE (P1 k = UNK), SUPERSEDE (lookup). VACUOUS: START, AUTHORIZE-IMPL, ASSIGN-ID, REV/REV-TYPE ADOPT (in REV-TYPE, VACUOUS yet a sole signature) | Each FR comes from one within-cluster contrast (S0804-L576; R-60) | SUPPORTED |
| **s** | state before the act | M0–MF; START (constrained), others | pool − s | The first pair found is `START WP-4B at R-72` vs `START WP-4B 08-03 16:10`, but the latter has s = UNK. Robust pairs: `7B at R-56` vs `7B at R-58`; `7C at R-58` vs `7C at R-65`; `§12 at R-81` vs `§12 at R-86` | FR: START (all models, all variants). VACUOUS: ADOPT, RAISE, AUTHORIZE-IMPL, ASSIGN-ID | Several independent cluster pairs; same-target contrasts over time; not a lookup | SUPPORTED (target-indexed) |
| **t** | target/scope of the act | M0–MF | pool − t | REV ADOPT (M0/M1/M4): E2 `…by the DA (R-86)` vs E3 `ADOPT R-91 by the DA (held)` | FR: REV ADOPT M0/M1/M4. MCR: BASE ADOPT, RAISE, START, REV ADOPT M2/MF, REV-TYPE ADOPT. VACUOUS: AUTHORIZE-IMPL, OPEN-WORK | **Object-identity lookup.** The FR memorizes that R-91 ≠ R-81..85. START shows t is not sufficient on its own | index of s; possible witness only |
| **e** | evidence condition (repetition count "2+" vs "1") | M1, MF; RAISE, SUPERSEDE | pool − e | RAISE: `promoted #2` vs `not promoted #5`. This is the same pair that makes M0/M2/M4 INADEQUATE | FR: RAISE (M1, MF). MCR: SUPERSEDE (the alternative {e} rests on D-12 e = UNK) | Single cluster R-36 (one decision matrix, 7 items) | WEAK (overloaded: repetition/breadth) |
| **c** | role conformance of the adopted object | M2, MF; ADOPT only | pool − c | none (other signatures survive) | MCR in every variant; never FR | BASE: token lookup. REV/REV-TYPE: the singleton {c} exists only because of the half-applied C2 (D3) | WEAK (coarse grain only) |
| **o** | the operation label | M3 (all ops pooled) | pool = 9 vars, o removed | **none, and none is possible:** every event has at least one UNK among the 9 vars | reported MCR under M3 | **The test has zero power.** It should read UNTESTABLE, not redundant | NOT DEMONSTRATED |
| **r** | route (RULING / EXECUTION / PROMOTION / ADR-ACCEPTANCE) | MF only | pool − r | none | Never FR. MCR: SUPERSEDE ({r} is genuine: RULING refused ×2 vs ADR-ACCEPTANCE performed); RAISE (in no minimal signature). VACUOUS: ASSIGN-ID (all UNK, yet a sole signature {r}) and all other ops | The ASSIGN-ID {r} and the global-minimum tie are UNK artifacts. SUPERSEDE rests on one PERFORMED event | NOT DEMONSTRATED |
| **x** | exception | MF only; RAISE, SUPERSEDE | pool − x | none | VACUOUS in both, yet in minimal signatures {e,x} (RAISE) and {x} (SUPERSEDE) | Consistent only by UNK on P1/R-41 and D-12 | NOT DEMONSTRATED |
| **h** | history summary (identifier unused / retired) | M4, MF; ASSIGN-ID | pool − h | `ASSIGN a never-used number` vs `ASSIGN the retired number R-90`. This pair also makes M0/M1/M2 INADEQUATE | FR under M4; MCR under MF **only** because {r} (all UNK) is consistent | One contrast. In the LTS, `reg` is determined by `st` (§7) | absorbed into status (formal) |

The imported status for `h` ("absorbed into status (formal)") is a formal statement placed in the empirical-status slot. It is not an empirical verdict.

---

## 6. Model equivalences

On the data, every consistent signature classifies every observed legality event correctly. Two minimal signatures of one operation are therefore observationally equivalent on the data whenever both separate every opposite-outcome pair. They can differ only on observations not yet made.

| Op / variant | Minimal signatures | Equivalent on data? | Observation that would separate them |
|---|---|---|---|
| ADOPT BASE (MF) | {a,c}, {a,k}, {a,t} | **Yes, and identical as partitions.** k, c and t all split {E1,E2} \| {E3} through identity tokens | Resolve R-81..85's kind or conformance: equal to R-91's value breaks {a,k} or {a,c}. A second ADOPT of the same object with a different outcome breaks {a,t}. A DA adoption of a new `ruling`/`collapsed` object that is PERFORMED breaks {a,k} and {a,c} but not {a,t} |
| ADOPT REV (MF) | {c}, {a,t} | Yes (both separate all three events) | R-91 re-submitted with repaired conformance and adopted by the DA breaks {a,t} and keeps {c}. A DA adoption REFUSED on a `conformant` new object breaks {c}. Applying R2 (E1 c = conformant) removes {c}; that is a coding decision, not an observation |
| ADOPT REV-TYPE (MF) | {c}, {k}, {a,t} | Yes, but {k} is consistent only by ignorance | Resolve the typed-header kind of R-81..85. If E2's kind equals R-91's, {k} breaks |
| ADOPT M0 vs M2 (REV) | {a,t} vs {c} | Same predictions on data | as above |
| RAISE MF (M1 lacks {e,x}) | {a,e}, {e,k}, {e,t}, {e,x} | Yes. {e,k} and {e,x} rest on P1/R-41's k and x being UNK | Code P1's k and x: k = engineering-behaviour breaks {e,k}; x = none-stated breaks {e,x}. A PA-instructed raise on AST-013 with e = 1, PERFORMED, breaks {e,t} but not {a,e}. An ARB raise with e = 1 on a new target, PERFORMED, breaks {a,e} but not {e,t} |
| SUPERSEDE MF | {a}, {e}, {k}, {r}, {x} | Yes. {a}, {e} and {x} rest on D-12's UNKs | Code D-12's actor, evidence and exception. A REFUSED ADR-ACCEPTANCE-route supersession breaks {r}. A PERFORMED supersession of an ADR-kind object breaks {k} |
| ASSIGN-ID M4 vs MF | {h} vs {h}, {r} | Yes. {r} rests on r = UNK in both events | Code the route of either assignment. The same route in both events breaks {r} |
| Global minimum BASE / REV-TYPE | {a,e,h,k,s} vs {a,e,k,r,s} | Yes | ASSIGN-ID route coding (as above) |
| Global minimum REV | {a,c,e,h,k,s}, {a,c,e,k,r,s}, {a,e,h,k,s,t}, {a,e,k,r,s,t} | Yes | ASSIGN-ID route; plus the ADOPT c-vs-t separators above |

---

## 7. Bisimulation audit

### 7.1 Logic

- **BFS.** `bfs(B0, B_ops())` expands each state once, so each transition is listed once.
- **Refinement.** Initial blocks are the enabled-label sets. The signature is (old block, {(label, target block)}), which is a true refinement. The loop stops when the block count is stable; because each step refines, an unchanged count means an unchanged partition. This is correct.
- **Family grouping.** States are keyed on all components outside F, and a counterexample is any non-bisimilar pair in the same key. This matches the docstring.
- **No state propositions.** Only actions are observed. A family can show relevance only through guards, not through invariants such as I-B1 and I-B2, which are stated over `iss` and `by` (see D16).

### 7.2 Hand recomputation of the counts

Each ruling's local state is (st, ann), with iss, by and reg determined by the rest. The reachable local states are:

- none: 1
- human A with ann 0, 1, 2: 3
- chief P0–2: 3
- H0–2: 3
- W0–2: 3
- adopted A1–2: 2

That is 15 local states per ruling.

- **Reachable states: 250 ✓.** With sup = none, all 15 × 15 = 225 pairs are reachable. sup = r2 needs both rulings in A, which gives 5 × 5 = 25 more. 225 + 25 = 250.
- **Full labels (r1): 151 ✓.** There are 10 non-W local classes (N, P0–2, H0–2, A0–2):
  - both rulings non-W: 100 classes;
  - one ruling in {N, P, H}, the other in W: 21 + 21 = 42 classes;
  - "pure counters", where neither side can ever reach SUPERSEDE: 9 classes, one per (ann.r1, ann.r2).
  - 100 + 42 + 9 = 151.
- **Operation-name labels (r2): 80 ✓.** r1 and r2 become symmetric, and pure counters merge by total annotations remaining.
  - both non-W: 55 unordered pairs, minus the one merge {A0,A2} ~ {A1,A1}, gives 54;
  - {N, P, H} with W: 21;
  - counters with 0–4 annotations remaining: 5.
  - 54 + 21 + 5 = 80.

### 7.3 The fix cannot change a family class

- Operation-name labels are a relabelling of the full labels. Every full-label bisimulation is therefore also an operation-name bisimulation, so the r1 partition refines the r2 partition (151 ≥ 80).
- **BEHAVIOUR-RELEVANT families (`st`, `sup`, `ann`).** Their counterexamples are distinguished under the coarser r2 labels (§7.4), so they are also distinguished under r1.
- **MODEL-COND-REDUNDANT families (`iss`, `by`, `reg`, `text`, `deleg`).** No reachable pair differs only in F (§7.5), so the verdict does not depend on the labels at all.
- Both runs agree, as RESULT and r1 show.

### 7.4 Counterexamples: each pair differs only in the stated family

Checked field by field against RESULT.

| Family | Pair (s₁ / s₂) | Distinguishing behaviour, from the rules in `m3_check.py` |
|---|---|---|
| **st** | r1 issued by the chief with st = P, vs the same state with st = H (reached by `ISSUE(r1,chief)`, then `HOLD`) | In s₁, `HOLD` and `WITHDRAW` are enabled (guard `st == "P"`); in s₂ they are not. The enabled labels are {ISSUE, ADOPT, HOLD, WITHDRAW, ANNOTATE} vs {ISSUE, ADOPT, ANNOTATE}. The states split in round 0, witnessed by ⟨HOLD⟩⊤ |
| **sup** | both rulings issued by humans and in A, with sup.r1 = none vs r2 | The `SUPERSEDE(r2>r1)` guard needs `sup.r1 == "none"`. The enabled labels are {ANNOTATE, SUPERSEDE} vs {ANNOTATE}, witnessed by ⟨SUPERSEDE⟩⊤ |
| **ann** (recomputed in full) | r1 issued by a human and in A, r2 none; ann.r1 = 0 vs 1 | Round 0: both states enable {ANNOTATE, ISSUE}. s₁ −ANNOTATE→ ann.r1 = 1 (which is s₂), where {ANNOTATE, ISSUE} are enabled. s₂ −ANNOTATE→ ann.r1 = 2, where `ann < 2` fails and r2's ANNOTATE needs `st.r2 ≠ none`, so only {ISSUE} is enabled. Round 1 splits them: ⟨ANNOTATE⟩⟨ANNOTATE⟩⊤ holds in s₁ and not in s₂. This holds under both labelings |

### 7.5 Scope of the MODEL-COND-REDUNDANT family verdicts

- **`text` and `deleg` are constants in the unrelaxed model.** Only the relaxations I-B4 and I-B2 change them. Their redundancy is therefore guaranteed by construction and should be labelled VACUOUS.
- **`reg` is a function of `st`:** none → unused; P, H, A → used; W → retired.
- **`by` is a function of (`iss`, `st`).**
- **`iss` is determined by the other components:** a human-issued ruling is always A with by = issue; a chief-issued ruling is P, H or W with by = "-", or A with by = Authority.
- In each case no pair exists to test, so the verdict means "encoded redundantly given the others", not "the content does not matter". `reg` is read by the ISSUE guard, for instance.
- Removing several families together (for example `iss` and `by`) is not tested.
- Only sub-model B is analysed.

### 7.6 Model artifact behind `ann`

`ann` is BEHAVIOUR-RELEVANT only because of the cap `ann < 2` and `min(2, ann+1)`, which is a finite state-space bound, not a sourced rule. **PLAUSIBLE** artifact.

### 7.7 Verdict

**The bisimulation audit passes on the logic and the counts.** The caveats in §7.5 and §7.6 affect how the results should be read, not what was computed.

---

## 8. Disagreement register

| ID | Disagreement | Category | Effect on RESULT |
|---|---|---|---|
| D1 | Consistency by ignorance: VACUOUS variables (r in ASSIGN-ID; x in RAISE and SUPERSEDE; k in REV-TYPE ADOPT) are minimal signatures. They demote `h` (ASSIGN-ID MF) and `a`, `t` (REV-TYPE ADOPT) to MCR and create global-minimum ties | **substantive** | the MCR claims for h (MF), a and t (REV-TYPE) are not supported by known data |
| D2 | The M3 operation test cannot find a conflict, because every event has at least one UNK among the 9 pooled vars; "o MODEL-COND-REDUNDANT under M3" is uninformative | **formal reasoning** | the o result should read UNTESTABLE |
| D3 | REV C2 sets c = conformant on E2 only, leaving E1 = `same:R-81..85`. This contradicts R2 (c is intrinsic and shared by identity). Under consistent coding (both conformant), {c} conflicts on (E1,E2); REV MF becomes {a,c}, {a,t}, and a becomes FR again | **coding** | REV and REV-TYPE `{c}` and the demotion of a are artifacts |
| D4 | t (ADOPT, RAISE), k (SUPERSEDE) and the k/c/t tokens in BASE ADOPT are object-identity lookups; t's FR in REV memorizes the object | **formal reasoning** | t FR (REV) must not be read as "target is a guard" |
| D5 | `same:` tokens count as known values in the VACUOUS test, so BASE ADOPT k and c are MCR rather than VACUOUS | **coding** | changes 2 class labels under the R2 reading |
| D6 | NOT-IN-FORCE is treated as opposite to PERFORMED. AUTHORIZE-IMPL is constrained, and a is FR there, only because of this. The R-89 difference (PREPARED status) is carried by a, because s = guards-met in both events | **source interpretation** | AUTHORIZE-IMPL a FR is conditional |
| D7 | Independence clusters are ignored. e (RAISE; one cluster, R-36), k (REGISTER, OPEN-WORK; one cluster each) and a (ADOPT; one contrast) are FR on single decisions. Only s (START) has several cluster pairs | **independence** | none on the formal claims; limits what they can be taken to show |
| D8 | The bisimulation MCR families pass vacuously: text and deleg are constant, and iss, by and reg are determined by the other components. Joint removal is untested | **formal reasoning** | "redundant" should read "constant / determined" |
| D9 | `ann` relevance rests on the ann ≤ 2 bound (a state-space cap) | **formal reasoning** | ann BEHAVIOUR-RELEVANT is model-bound (PLAUSIBLE) |
| D10 | Class precedence is undefined; the "minimal" counterexample is not implemented (first BFS pair, which here is de facto shallowest); `BEHAVIOUR-RELEVANT` is undefined; FR/MCR shorthand departs from the mandated phrasing; per-variable INADEQUATE/UNCONSTRAINED appear only as op-level statuses | **wording** | none numeric |
| D11 | The r2 justification ("1al used op-name labels") is unverifiable from the supplied files; the change is post-freeze. It is proven not to change any family class and only changes 151 → 80 | **evidence scope** | none on families |
| D12 | The F-LOG citations (0147, 0153, 0154) and the imported EMPIRICAL statuses are unverifiable. C1 changes no output. h's "empirical status" is a formal statement | **evidence scope** | none |
| D13 | The global minimum covers only constrained operations (ANNOTATE is unconstrained; AUTHORIZE-PLAN has no events). The {a,e,k,r,s} tie rests on r = UNK. REV-TYPE returns to size 5 only through k = UNK | **evidence scope** | "5 variables suffice" is data- and artifact-bound |
| D14 | Fields that are not applicable are coded UNK instead of n/a (schema R1) | **coding** | none (both are "unknown" to `known()`) |
| D15 | "M3" names both the global operation-pooled model and the m3_check LTS | **wording** | none |
| D16 | The bisimulation observes actions only and covers sub-model B only. The relevance of iss and by to invariants I-B1 and I-B2 cannot show up | **evidence scope** | the family verdicts are about B's guards only |

**What is not in dispute:** every number in RESULT.json, and the r2 refinement logic.

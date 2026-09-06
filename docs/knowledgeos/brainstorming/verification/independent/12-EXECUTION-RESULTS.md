---
artifact: 12-EXECUTION-RESULTS
date: 2026-08-30
status: **5 suites executed · 4 pass · 1 witness found VACUOUS**
rule: prose claiming executability is not execution evidence
---

# 12 · Execution Results

Every executable artifact in the corpus was located and run. Format per mandate §14.

---

## RUN-1 · `zero_reference.py`

| | |
|---|---|
| **File** | `reviews/synthesis/analysis/mathematical-tests/zero_reference.py` |
| **Command** | `python3 zero_reference.py` |
| **Expected** | reproduce 025d's T1–T8 falsification tests; `Zero(K,EC)` total over finite `R` |
| **Actual** | **exit 0** — T1…T8 **8/8 PASS**; `§25D.8` example reproduced exactly; Γ-sufficiency PASS; scalar-collapse counterexample PASS |

**Interpretation — three findings, one of them decisive.**

1. **The ten-status set is real, executable and passing.** `STATUSES = [Satisfied,
   PartiallySatisfied, Unknown, Insufficient, Conflicted, Stale, Invalid, Prohibited, NotApplicable,
   Missing]`, with in-code discrimination `Missing` = *"expected governed artifact absent"* vs
   `Unknown` = *"no evidence either way"*. **Falsifies any claim that this corpus cannot distinguish
   them.**
2. **Implementation exceeds theory.** The script's own note: *"'Insufficient' is expressible ONLY
   because Γ is carried; the ratified four-arm summary has no arm for it, nor for Stale or
   Prohibited — the PF-1 loss, demonstrated here as executable semantics, not as prose."*
3. **The honest computability boundary, stated by the artifact itself:** *"Zero is computable, total
   and terminating over finite R, **RELATIVE to per-requirement evaluators**. What is NOT
   established: the evaluators themselves (Γ semantics, HumanAuthorization oracles) and η."*
   → **the theory computes down to its oracles, and the oracles are where the epistemics live.**

---

## RUN-2 · `ladder_dc_reference.py`

| | |
|---|---|
| **Command** | `python3 ladder_dc_reference.py` |
| **Actual** | **exit 0** — I-12 covering relation PASS · no-averaging PASS · `Unknown→Block` PASS · refined-form `Q` block PASS · PF-6 expressibility probe CONFIRMED |

**Interpretation — two valid results and one vacuous test.**

✅ **Valid:** `no averaging` (42.10) executes correctly — *"a 95%-admissible decision is
inadmissible"*. `Unknown → Block` executes. The PF-6 expressibility probe is a genuine negative
result. And the script **reports the ratified-model mismatch itself**: *"the ratified 6-tuple has NO
ratified admissibility conjunction (42.9 omits Temporal; 42.41 is the 7-tuple's law)."*

🔴 **VACUOUS — the `Σ ⊥ Γ` witness:**
```python
def commit(self, purpose, authority_act=None, evidence_volume=0):
    if self.status != "Accepted": raise ...
    if authority_act is None: return False       # ← the entire test
```
`grep evidence_volume` → **exactly two lines**: the parameter declaration, and the call site
`evidence_volume=10**6`. **The body never reads it.**

> The line `10^6 evidence, no authority act : PASS (not committed)` is produced by a function that
> never inspects the evidence count. **It would print the identical PASS for `evidence_volume = 0`,
> and would also "pass" if the law under test were false.** This is a null-check on a parameter
> named `authority_act`, restating A6/I-4 in Python.
> **Classification: `IMPLEMENTATION-ONLY`, specifically tautological. Withdrawn as evidence.**
> *(The `Σ ⊥ Γ` claim survives on other grounds — `07` §1.)*

---

## RUN-3 · `exp01_recheck.py`

| | |
|---|---|
| **Command** | `python3 exp01_recheck.py` |
| **Actual** | **exit 0** — 7-column matrix re-derived under RAW and normalized readings; CSV oddities reproduced |

**Interpretation.** A genuinely strong executed result, and it survives:

> *"the negative conclusion is **CONFIRMED and is in fact provable**"* — no simple scalar operator is
> sufficient as the epistemic foundation, because **no scalar can retain `(S⁺,S⁻)`**.

It also demonstrates **witness-vs-claim divergence** honestly: every historical PASS cell that the RAW
run fails is true only of the *normalized* pipeline, and *"the CSV mixes semantics across cells."*
**This is the corpus catching its own register error by execution.** Model behaviour.

---

## RUN-4 · PHP unit tests

| | |
|---|---|
| **Command** | `vendor/bin/phpunit --filter KnowledgeOs --no-coverage` |
| **Actual** | **Tests: 10, Assertions: 34, 0 failures.** (77 warnings + 1681 deprecations are suite-config duplication and PHPUnit version noise, not test failures) |

**Interpretation.** `KnowledgeOsDoctorTest` and `KnowledgeOsInitPlannerTest` pass. **They exercise
tooling, not theory** — no assertion, evidence, policy or status object is under test. **The theory
has no PHP conformance suite.**

---

## RUN-5 · constructed attacks (`attack.py`)

**A · relation-field loss**
```
corpus relation fields   : ['E','E1','E2','Q','R','Sigma','T','tau']
canonical relation fields: ('a1','a2','RelationType')
FIELDS DISCARDED         : ['E','Q','R','Sigma','tau']   (5 of 8)
```

**B · relation-as-assertion reduction → CIRCULAR** `V_D → 𝒫 → Assertion → 𝒜 → V_D`.
⇒ `ℛ` is a genuine primitive.

**C · Σ is policy-relative**
```
identical evidence (2 supporting), policy(min_support=1) -> Supported
identical evidence (2 supporting), policy(min_support=3) -> Unknown
```

**D · cycle detection, 24 nodes**
```
CYCLE: Evidence -> Policy -> K -> Assertion -> Evidence     UNTERMINATED
CYCLE: Evidence -> Policy -> K -> Relation  -> Evidence     UNTERMINATED
CYCLE: Policy   -> K -> Relation -> Sigma   -> Policy       ill-typed field
```

**E · assertion identity instability**
```
id before withdrawal : 86a0330e2b65
id after  withdrawal : a9d84e7a43d8      → every R edge dangles
```

**F · Σ blind to ℛ** — two assertions in an explicit `contradicts` edge both evaluate
`('Supporting','Weak')`.

**G · `δ` commit is a no-op** — `K₁ is K₀ == True` after an authorized, executed, historied commit.

---

## Theory-vs-implementation mismatches

| Direction | Instance |
|---|---|
| **theory says X, implementation does Y** | theory: `Unknown` is irreducible. EKP: `status` is a **required closed enum with no `unknown`** (8 values, measured) |
| **implementation EXCEEDS theory** | `zero_reference.py` expresses `Missing`, `Stale`, `Prohibited`, `NotApplicable`, `Insufficient` — **six statuses `(dir,str)` cannot carry** |
| **implementation EXCEEDS theory** | EKP `orphan_document` — asserted but unconnected; a **third** kind of missingness |
| **implementation EXCEEDS theory** | **93 of 132** markdown files under `docs/knowledge/` carry **no frontmatter at all** — neither governed nor declared ungoverned: a running, unnamed *"not assessed"* |
| **declared ⊥, measured collinear** | `draft ⇔ provisional` **13/13 both directions** over all 39 governed docs; 6 of 40 cells occupied |
| **discipline without binding** | **132/132** grants across **22 work items / 269 transitions** carry `humanActRef` — but it is **free text** (83 prose / 49 path-bearing), no schema, no identity. The estate's own record shows **two distinct authority acts sharing one `grantId`** |

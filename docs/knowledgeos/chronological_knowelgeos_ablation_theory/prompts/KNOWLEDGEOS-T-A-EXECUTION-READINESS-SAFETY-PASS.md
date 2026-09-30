# T-A EXECUTION READINESS & COUNTERMODEL SAFETY PASS

| | |
|---|---|
| **Kind** | pre-execution safety report. ⚠ authority: generated. **No decision is recorded here** |
| **Commission** | human, 2026-09-26: senior assessment plus the "KNOWLEDGEOS — T-A EXECUTION READINESS & COUNTERMODEL SAFETY PASS" prompt; *"analyse the discussion and follow the prompt"* |
| **Object** | `prompts/KNOWLEDGEOS-H-F2-1-R-T-A-PREREGISTRATION.md`: r1 (`e735aa336`, sha256 `194beacb…0ecd`) → **r2** (sha256 `a1640c3a657c370efa7814d8240f9f4861f9926f543fbe9700cecd8424ce23dc`) |
| **Reads** | ⛔ **no corpus content read.** Used: git **metadata** (commit ids and dates per path; `git status`; blob ids), the **byte hash** of F0018 compared with the corpus-manifest pin (no content viewed), the registry row and reconstruction record of F0018 (lane data read previously), and the lane's own artifacts |
| **Candidate** | H-F2-1-R: **unchanged** |

---

## 0. Result in one line

**NOT READY.** STOP condition §15 *"an axiom's scope is undefined"* is triggered. It is **reported, not fixed** (§2). Every other finding was either a commissioned correction or a scope-independent logical error; these were fixed additively in r2 (§3).

---

## 1. Scope of the STOP rule, as applied

- The commission says: *"Stop and report instead of fixing if …"*.
- Applied **per condition**:
  - the triggered condition (axiom scope) is **left unresolved** and flagged in the pre-registration;
  - independent defects and the explicitly commissioned A6 correction (§2 of the commission: *"Replace that implication with …"*) were still made, because they do not depend on the scope.
- If the human reads the STOP as "no edits at all", r2 can be reverted to r1 as a whole: all changes are listed in its §9.

---

## 2. STOP: axiom scope and kind assignment are undefined

### 2.1 Finding [F]

| Where | Text | Problem |
|---|---|---|
| pre-registration §3 | *"Each question is a **universal** locality claim. One DIRECT COUNTEREXAMPLE refutes the axiom **for that mechanism**."* | two different scopes in one sentence |
| pre-registration §5, core row | *"REFUTED for that mechanism … revise (restrict the axiom's scope)"* | presumes per-mechanism scope, while §5.1 says a counterexample *"refutes H-F2-1a as stated"* (universal) |
| attack document, H-1 | *"A mechanism with two kinds must be split into steps, or it is a COMP step (A4)"* | a **splitting licence** with no pre-registered trigger |
| everywhere | none | **no rule for assigning a kind** (GOV / EVID / EVIDREF / WORK / COMP) to an operation the source describes |

### 2.2 Why it is a STOP, not a detail [D]

Suppose a source describes a ruling that changes evidential position. The reader has three ways to *absorb* it:
- relabel the ruling as EVID;
- split it into GOV + EVID;
- or call it COMP.

In each case A2e survives. If the kind can be chosen **after** the effect is seen, the locality axioms become true by classification. That is Popper's conventionalist stratagem, and it would make T-A unable to refute anything. The four outcome classes cannot repair this, because they classify passages *given* a kind.

### 2.3 What HD-S must fix, per axiom (options; Claude does not choose)

| Dimension | Options |
|---|---|
| (i) **scope** | **S-U** universal over every operation the authorized sources describe · **S-C** universal within a **pre-declared** mechanism class list · **S-N** a hypothesis about each **named** mechanism |
| (ii) **kind basis** | **K-A** by the **actor/authority the source names** (who performs the operation) · **K-D** by the source's own **declared type** of the operation · ~~K-E by its effect~~ is **excluded**, because it makes the axioms analytic |
| (iii) **splitting** | **SP-S** only where the **source itself** describes separate steps · **SP-R** by reader judgement, recorded with a justification |

**Recommendation [E]:**
- **S-U + K-A (falling back to K-D where no actor is named) + SP-S.**
- Kind is assigned **before** the effect is assessed (checklist step 6).

**Why:**
1. It is the **most falsifiable** combination.
2. **S-C cannot be pre-declared completely without reading F0018.** The recorded reconstruction names only P-3, P-6 and P-10 of the ten mechanisms. Choosing S-C would therefore require a class list written after reading, which is leakage.
3. Under S-U, a refuted axiom can still re-enter later **with a restricted scope as a new candidate**, through the §5.1 re-check rule. The weakening route is kept, but it becomes a new registration, not an in-test reinterpretation.

`aggregate.py` implements all three scopes. It **refuses to run without `--scope`** (exit 2), so the STOP is enforced mechanically.

---

## 3. Clause-by-clause verification (commission §2–§13)

| § | Requirement | Finding | Action (r2) |
|---|---|---|---|
| 2 | A6: no "two-level model required" | **error**: r1 row 147 preselected a replacement theory, and its restriction clause was phrased in two-level terms | **corrected**: "A6 refuted as formulated; replacement UNRESOLVED; characterize → ≥ 2 alternatives → re-check rule"; a non-exhaustive candidate list; nothing preselected. Kept as a **conditional theorem**: D1/D3 hold on every trajectory with no bar change. That is mathematics, not a model choice |
| 3 | axiom false ≠ proposition false; proof unavailable ≠ disproved | §5.1 already right. **Errors** in §5: the A0/A3m row said "refuted"; the core row mixes scopes | A0/A3m row aligned (UNSUPPORTED); a record field `effect_instantiates` limits FALSIFIED to the propositions whose countermodel the source **states**. Core row → PENDING HD-S |
| 3 | decision table audit | **two logical errors:** (a) the rows "only S and A → SURVIVES" and "A dominant → INCONCLUSIVE" **overlap** (S = 1, A = 5 satisfies both), and "dominant" is undefined; (b) **no rule** for a core axiom that ends NOT_EVIDENCED or NOT_RUN, although §3.2 predicts exactly that for A5g | (a) new **§5.0** precedence-ordered verdict (exhaustive, mutually exclusive). A live counterexample reading in an AMBIGUOUS record gives INCONCLUSIVE, not SUPPORTED. (b) new row **PARTIALLY UNTESTED**, plus a candidate-level precedence |
| 4 | per-axiom scope recorded | **undefined**: §2 | **flag only** (STOP) |
| 5 | expected findings cannot steer classification | **leak**: the reader filled `expected_finding_matched` and could see §3.2 | the field is now **computed by the script after classification** (the validator rejects reader-filled values). A second reader is blind to §3.2. Match and mismatch are declared non-evidential. The §3.2 prose predictions are **translated** into §5.0 verdicts in `aggregate.py` (`PREDICTED`); HD-1 confirms the translation. **No prediction changed** |
| 6 | historical versions | the rule was sound for M-2…M-4 but **silent on M-1**; F0018 is first committed (2026-08-04) **after** its own date (2026-08-02), so the date rule would yield nothing | M-1 = the **manifest-pinned object** (sha256 matches; blob at `d61bf5e84` = HEAD). Boundary fixed (committer date ≤ 2026-08-02T23:59:59+02:00; robust). **All versions resolved**, see the table below. `NOT_AVAILABLE_AT_DATE → AMBIGUOUS` marked moot, because AMBIGUOUS is a passage class |
| 6 | ES-006 snapshot vs history kept apart | kept: snapshot `668cc7b22`; history = 7 commits `aee484e9c`…`668cc7b22` | recorded. **Fact:** the whole history **precedes** F0018, and there are 0 post-date commits. F-A6 can test stated amendment semantics and the pre-F0018 bar changes; it cannot observe later ones, and none exist |
| 7 | minimum release | confirmed: **M-1 + M-4** for the dynamics core and A6. M-2/M-3 only for Q-GS/Q-D4; M-5 deferred | un-released questions become **NOT_RUN** (§5.0 rule 0), never NOT EVIDENCED. `aggregate.py` `MATERIAL` encodes the §2 needs. **One derived mapping:** F-A3m → M-1 + M-4, because §2 does not name it; HD-1 confirms |
| 8 | evidence record fields | missing: the version/commit, the independence class (merged into `reader`), the competing verdict, A6's level as a field | added `source_version`, `independence_class`, `competing_classification`, `a6_level`, `effect_instantiates`. **No "probably true" field** (`confidence` is about the classification). Found: record-level `NOT_EVIDENCED` conflicts with §3.1's absence definition, so it is counted as **U**; the §3.1 definition is unchanged; HD-1 confirms |
| 9 | discovery ≠ evidence; no ML in T-A | met (§6: *"T-A itself needs no ML"*) | none |
| 10 | descriptive statistics only in T-A | met; the script emits counts and verdicts only | none |
| 11 | revision loop: spec → derivation → implementation → NV → counterexamples → minimality → re-registration | met by §5.1's re-check rule plus §6's computer-logic loop | none |
| 12 | no DDD artifacts | none created | none |
| 13 | ML before T-B: adjudicated training data, separation, frozen configuration, provenance | met in §6 | none |

**Resolved versions (metadata only):**

| Item | Object | Post-date commits |
|---|---|---|
| M-1 F0018 | manifest pin sha256 `b685f599…7bc`; commit `d61bf5e84` | n/a |
| M-2 authorities.yaml | `faa8d61e2` (2026-06-27) | 0 |
| M-3 statuses.yaml | `faa8d61e2` (2026-06-27) | 0 |
| M-4 ES-006 | snapshot `668cc7b22` (2026-07-26); history of 7 commits | 0 |

All four files are unmodified in the working tree.

---

## 4. Deliverables

| | Deliverable | Where |
|---|---|---|
| A | safety report | this file |
| B | corrected pre-registration | r2, sha256 `a1640c3a…23dc`. Its §9 lists every change. **No question, no prediction and no §3.1 outcome definition changed**; two decision rules changed, each justified |
| C | execution checklist | pre-registration **§10** (10 deterministic steps, including "HD-S in force" and "kind before effect") |
| D | evidence schema validation | pre-registration §4 (r2 fields), plus `analysis/t_a/aggregate.py` (sha256 `f0d836e8…ef71`) and `analysis/t_a/test_aggregate.py` (sha256 `906e0494…e601`). **18/18 tests pass on synthetic records.** No new theory concept was needed to record evidence |

The tests check, among other things:
- precedence;
- that the r1 overlap case is now decided;
- that absence is not support;
- NOT_RUN;
- that scope switches REFUTED vs WEAKENED;
- that an A6 counterexample leaves the replacement mechanism UNRESOLVED;
- that UNSUPPORTED is kept distinct from FALSIFIED;
- that the extension is isolated from the core;
- that the reader cannot fill the prediction match;
- the exit-2 STOP when no scope is given;
- **that the §5.1 map is the exact transpose of the computed minimal sets.**

---

## 5. Where this leaves the goal (the senior roadmap, sharpened)

- The formal work has stopped by design.
- The test is fully mechanized, apart from **one human choice** that decides whether it can refute anything at all.
- After HD-S, the order is unchanged:
  - HD-1 freezes the post-HD-S revision (a new hash);
  - HD-2 releases M-1;
  - HD-3 releases M-4 under the OQ-6 scope decision;
  - HD-4 sets the reader independence class;
  - T-A runs, with no ML;
  - `aggregate.py`;
  - fixed rules;
  - stop.
- The empirical validation of H-F2-1-R is **still at zero**, as the senior assessment says.

---

## T-A READINESS
**NOT READY.** The axiom scope and the kind-assignment rule are undefined (§2). This is a commission STOP condition, reported and not fixed.

## PREREGISTRATION STATUS
**REVISED** (r1 → r2, sha256 `a1640c3a…23dc`).
- Changes: the commissioned A6 correction; two justified decision-rule repairs (the §5.0 precedence and the PARTIALLY UNTESTED row); additive temporal, schema, blinding and checklist items; PENDING-HD-S flags.
- No question, prediction or outcome definition changed.

## A6 STATUS
**RULE CORRECTED.** An A6 counterexample refutes A6 as formulated. The replacement mechanism is UNRESOLVED and nothing is preselected. D1/D3 remain proved for trajectories in which the bar does not change.

## LOGICAL SAFETY
**ISSUE.** The scope and kind assignment are undefined: counterexamples could be absorbed by relabelling or splitting. The table overlap and gap were fixed in r2.

## TEMPORAL SAFETY
**PASS.**
- All four items resolve to exact objects, with 0 post-date commits.
- M-1 is pinned by the manifest hash.
- The date boundary is fixed and robust.

## ML LEAKAGE SAFETY
**PASS.**
- No ML in T-A.
- The prediction match is computed after classification.
- A second reader is blind to §3.2.
- The T-B ML configuration is frozen before use and trained on adjudicated labels only.

## DDD BOUNDARY
**PASS.** No bounded context, aggregate, entity model, domain service or implementation architecture.

## NEXT HUMAN DECISION
**HD-S**: fix, per axiom, three things:
- the **scope** (S-U / S-C / S-N);
- the **kind-assignment basis** (K-A / K-D; the effect is never a basis);
- the **splitting rule** (SP-S / SP-R).

Claude recommends **S-U + K-A (K-D fallback) + SP-S**. HD-1 follows on the revision that records HD-S.

## NO-GO
- No freeze of r2 (it is NOT READY); no HD-1 before HD-S.
- No corpus reading: F0018, ES-006, the schemas, M-5.
- No release request by Claude.
- No T-A, T-B or C-M.
- No running `aggregate.py` on real records.
- No choice of scope or kind basis by Claude.
- No replacement model for A6 before evidence.
- No change to H-F2-1-R.
- No change to the Master Protocol, Research Architecture v1.2 or F-Series architecture.
- No DDD artifacts.
- No ML in T-A.
- No probabilities, intervals, tests or theory scores in T-A.
- No claim that H-F2-1 is validated or canonical.
- No approval, decision record or decision switch written by Claude.

---

**T-A SAFETY PASS COMPLETE — NO CORPUS READ, NO RELEASE REQUESTED, NO DECISION RECORDED, H-F2-1-R UNCHANGED.**

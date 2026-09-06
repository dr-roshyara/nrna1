# OPERATION REGISTRY — DECISION PACKAGE

> ## ⛔ NOTHING IN THIS FILE IS RATIFIED
> Deliverable 5 of commission GN-79/GN-80, assembled by the **commissioner** (book/governance lane)
> from two independent reports. It **recommends**; it decides nothing. Assembled under the
> acceptance procedure fixed in advance by **GN-85**. Registry status: **NOT ESTABLISHED**.

## 0 · The two reports

| Artifact | md5 | Size | Lane |
|---|---|---|---|
| `OPERATION-REGISTRY-DERIVATION.md` | 4cd2b33aad476c9efdd3c48269906df9 | 589 ln / 7,141 w | derivation (fresh context) |
| `MINIMALITY-RESULT.md` | 5f30594fddf4eb74f6db00b2ae1198d1 | 485 / 5,144 | derivation |
| `OPERATION-CONTRACTS.md` | 7aac27a9cf161b3cdd9ec996880da983 | 629 / 9,509 | derivation |
| `OPERATION-REGISTRY-INDEPENDENT-REVIEW.md` | 131a07a7609c22370c48559904b79aca | 877 / 9,373 | falsification (fresh context, C-8) |

**Independence confirmed:** the reviewer took no part in constructing any candidate registry and
never received the commissioner's interpretation. The three derivation artifacts verify
**byte-pristine after review** — the reviewer modified nothing.

**Gate verdicts:** AC-0 **PASS** · AC-1 **FAIL** · AC-2 PASS WITH FINDINGS · AC-3 **PASS** ·
AC-4 PASS WITH FINDINGS · AC-5 PASS WITH FINDINGS · AC-6 PASS WITH FINDINGS. **6 RED · 11 AMBER ·
9 GREEN.**

---

## 1 · THREE-WAY COMPARISON (GN-85 §1 — the mandated first act; the reports are NOT merged)

| Derivation claim | Falsification result | Canonical consequence · rung · **authority required to raise** |
|---|---|---|
| Candidate universe = 57 live names / 17 sources, complete | **FALSIFIED — AC-1 FAIL (IR-F-26).** A table literally headed "The Operation Taxonomy" (Q15 §2.3), 24 arrow-typed signatures, cited by no key; **~19 of its names appear in neither list**. `unask` is a **live** operation under an open normative decision. Steps **282/283/284 exist** — the 281 ceiling is wrong by three. Steps 260/261 uncited, and `Deduplicate` is a first-class matrix row there. | **OPEN.** The universe is not closed, so the **core-of-12 and band-of-9 cannot be relied on as computed values.** *To raise:* re-run Task 1 over the existing unmodified scripts, then re-execute Tasks 3–6. |
| Minimality was executed, not argued | **SURVIVED.** All five scripts re-ran **byte-identically on an independent Python**; the closure computation is **real bounded BFS composing operations**, not a hand-authored table — it clears the bar it sets for `oderive.py`. | **COMPUTATIONALLY VERIFIED** (relative to its pool). *To raise to FORMALLY DERIVED:* a closed universe (above) plus removal of the caps in IR-F-3. |
| Enumeration exhaustive in every variant | **PARTLY FALSIFIED (IR-F-3).** Holds for **one variant of three**; A13's family is one **hand-constructed size-7 set, self-labelled "not proven-minimal."** The "band invariant at 9" is largely mechanical. | Variant 1 **COMPUTATIONALLY VERIFIED**; variants 2–3 **PROPOSED**. *To raise:* exhaustive enumeration in the remaining variants. |
| Σ and `Q_t` used nowhere in the state core | **SURVIVED — AC-3 PASS**, tested against the model code, not the prose. | **COMPUTATIONALLY VERIFIED.** *To raise:* nothing needed for this use; it is a negative, and it holds. |
| 13 of 15 mandatory capabilities are RATIFIED-FORCED | **SURVIVED — the HPA watch item.** All 13 traced to primary text in v0.1/v0.2/FA-1/the Constitution; **every one lands on a real anchor; none fails on "the theory needs it."** | **FORMALLY DERIVED from ratified material** — the strongest surviving result in the package. *To raise to RATIFIED:* an HPA act adopting the capability set. |
| `Reject` violates I-12 and Art. 8 → all six registries inconsistent (**the stated ground for D**) | **FALSIFIED (IR-F-1).** Step 256.11 says "**Possible** semantics" and names `Proposed` — not a ratified status; `op_Reject` implements **no source-status guard at all**. **There is no specification to violate**, and the derivation's own sentence contradicts itself. | The obstruction is **UNDERDETERMINATION, not inconsistency.** AF-F-31 must be **re-characterised**: `Reject`'s guard is specified nowhere. *To raise:* an act typing `Reject` (prior decision P-1). |
| Discovery: the ratified model permits an I-12 bypass `Candidate → Conflicted → Supported` | **FALSIFIED / mischaracterised (IR-F-4).** Reaching `Supported` from `Candidate` **skips no rung**. The effect exists only because `rm.py:371` **hardcodes the return rung** — an undeclared choice, though §5.3 concedes it moves 6 registries to 5. | **NOT a ratified-model defect.** A modelling choice masquerading as a discovery. *To raise the underlying question:* an act stating which rung a governed resolution returns to. |
| A6 crossed by step 025a-2 §36's `Commit` | **SURVIVED**, verified verbatim: five conjuncts, **no authority conjunct**, source **self-labelled "experimental."** | **COMPUTATIONALLY VERIFIED**, scoped to an experimental source. AF-F-32 stands, with that scope attached. |
| `oderive.py`'s "14-forced/18-upper" is PROPOSED, not DERIVED | **SURVIVED**, verified line by line: `FORCES` is a hand-authored dict; "RESULT 3 — the closure question" is **pure `print()`**. | AF-F-33 stands. The bound **may not be cited as mathematical evidence.** |
| 272A's `D_mandatory` is a bijection → its necessity test is a tautology | **SURVIVED** — correct and correctly scoped. | Stands as an evidence-grade correction. |
| `op_Replay` replays | **FALSIFIED (IR-F-2).** It **sets a flag**, and A15's goal tests that flag — **the identical defect the derivation uses to discredit the record's withdrawn A6 witness.** | Any replay-dependent result is **OPEN**. Self-inflicted; the strongest single reason not to accept the package as-is. |
| *(not claimed)* | **NEW — IR-F-5.** Constitution **Art. 8.3: "Resolution SHALL be forward-only: the conflict record SHALL survive resolution"** — **never consulted.** Zero hits for "forward-only" across all deliverables and scripts, despite ~20 Art. 8 citations, in a derivation whose central discovery is that the return rung is unstated. | **Materially decisive and unexamined.** Commissioner spot-check: the phrase is present verbatim in the ratified Constitution **and** in Reference Architecture v1.0 ("Supersession SHALL be forward-only"). *To raise:* re-examine the return-rung question against Art. 8.3 before any registry is derived again. |
| Terminal verdict **D** | **AGREES with D — and rejects the ground.** Adds **P-11 (what is the operation universe, and what act closes it?)** as prior to all of P-1…P-10. Excludes A "on every variant"; declines B (conditional on three modelling choices, one cap, an unclosed universe) and C (discards the real contribution). | **D recommended by both, independently, on incompatible grounds.** Convergence on the verdict; divergence on why. |

**Commissioner note on its own error:** an initial spot-check of the step-284 claim returned zero
because the pattern `step_284|step-284` misses the actual filename `# step 284 Yes. After
reviewing the Step 283 materi`. **The reviewer is correct and the commissioner's first check was
wrong** — recorded because it is the same failure class the programme keeps catching: a pattern
that misses the naming rather than the content.

---

## 2 · THE EIGHT QUESTIONS (GN-85 §4)

| # | Question | Answer | Basis |
|---|---|---|---|
| 1 | Six minimal registries reproducible? | **Mechanically yes; as computed values, NO** | scripts re-run byte-identically, but the universe is unclosed (AC-1 FAIL) and exhaustiveness holds for 1 of 3 variants |
| 2 | The six genuinely non-equivalent? | **NOT ESTABLISHED** | conditional on three modelling choices, one cap, and an unclosed universe |
| 3 | A smaller registry exists? | **NOT EXCLUDED** | `rm.py:371`'s hardcoded return rung alone moves 6 → 5; missed names may realize the same capabilities |
| 4 | Mandatory capabilities actually entailed? | **YES — all 13** | independently traced to primary ratified text; none rests on "the theory needs it" |
| 5 | Σ/`Q_t` genuinely irrelevant? | **YES** | AC-3 PASS, tested against the code |
| 6 | `Reject` compatible with canon? | **UNDETERMINED** — not *incompatible* as claimed | its guard is specified nowhere; the nearest candidate names a non-ratified status |
| 7 | A6 crossing genuine? | **YES**, scoped to an experimental source | verified verbatim |
| 8 | **Unique canonical registry established?** | **NO** | fails on 1, 2, 3, and 6 |

---

## 3 · RECOMMENDED TERMINAL VERDICT

> ### **D — THE OPERATION UNIVERSE DEPENDS ON AN UNRESOLVED PRIOR CANONICAL DECISION**
> *Recommended by both lanes independently. Recommended, **not ratified**.*

**The ground the HPA should record is the reviewer's, not the derivation's.** The derivation
grounded D in *inconsistency* (every registry contains an operation violating a ratified
invariant); that ground **fails** under IR-F-1. The surviving ground is **underdetermination**:
`Reject`'s guard is specified nowhere, and AC-1's failure adds **P-11 — what is the operation
universe, and what act closes it? — as prior to all ten previously named prior decisions.** On the
reviewer's reading this makes the case the most literal instance of D.

**Neither lane recommends rejecting the deliverable.** The failure is **localised at Task 1** and is
re-runnable over the existing unmodified scripts without discarding Tasks 2, 3 or 8 — and Task 2's
result (the 13 entailed capabilities) is the package's strongest asset.

---

## 4 · THE THREE PERMITTED NEXT MOVES (GN-85 §5)

| Move | What it requires | Commissioner's assessment |
|---|---|---|
| **A** resolve the prerequisite canonical decisions → rerun the derivation | acts on P-11 (universe closure) and P-1 (`Reject` typing) at minimum; re-examine the return rung against **Art. 8.3**; fix `op_Replay`; close variants 2–3 | **the only move the evidence supports.** P-11 is cheap to state and unblocks the rest |
| **B** ratify a uniquely established registry | a unique registry | **unavailable** — question 8 is NO |
| **C** record the operation algebra as an open architectural decision | nothing further | available, but discards Task 2's surviving result |

> 🚫 **FORBIDDEN (GN-85, by name): "the six candidates are good enough — choose the most sensible
> one."** Refused by reference. Question 2 shows the six are not established as distinct and
> question 3 shows a smaller set is not excluded, so plausibility-selection would choose among
> sets whose distinctness is itself unproven.

## 5 · NEW ITEMS REQUIRING REGISTRATION (reported, not resolved)

**P-11** — the operation universe's closure act, prior to P-1…P-10 · **IR-F-2** the `op_Replay`
flag defect · **IR-F-5** Art. 8.3 forward-only never consulted · **IR-F-4** the return rung is a
hardcoded modelling choice, undeclared · **AF-F-31 must be re-characterised** from *inconsistency*
to *underdetermination* · the step-ceiling correction (282/283/284 exist).

## 6 · STOP

The commission's mandate: **stop after delivering the decision package.** No ratification · no
registry selected · no `Reject` typing · no I-12/Art. 8 repair · no transformation semantics · no
implementation specification · GC-1 and OQ-1…12 untouched · book unchanged, Operations and
Transformations **NOT ESTABLISHED**.

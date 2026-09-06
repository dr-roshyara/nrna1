# 10 — Gap Update from Step 281 (executed) and Step 282

**Tasks: (1) files renamed; (2) gaps updated.** Reviewed as senior mathematician · statistician ·
DDD architect. **All Step 281 tests independently re-executed by this session.**

---

## PART 1 — Rename complete

**5 files**, MD5-verified byte-identical 5/5. Zero un-normalized files remain.

| New name | bytes |
|---|---:|
| `20260831-000627_step_282_theory-closure-decision-and-readiness-determination.md` | 26,553 |
| `20260831-001049_step_282_required-completion-and-correction-part.md` | 20,471 |
| `20260831-001525_step_282_supervisory-review-of-original-step-282.md` | 19,562 |
| `20260831-001754_step_282_hpa-ruling-theory-criticality-and-residual-gap-classification.md` | 8,885 |
| `20260831-001927_step_282_claude-execution-prompt-residual-gap-closure-gate.md` | 19,181 |

---

## PART 2 — Step 281 was executed, and **the missingness defect is repaired**

`verification/step-281/` — repair selection, three proofs, E4 re-run, affected-test re-run, and an
executable suite. **This session re-ran all six test files independently. All pass.**

| Test | Result (my re-run) |
|---|---|
| `test_e4_rerun.py` | **7/7 PASS.** CF#7 re-check: `not asked`=`('NotAsked','-')` vs `absent`=`('Asked','Absent')` — **DISTINCT = True** |
| `test_distinguishability.py` | M1–M6 **6/6 distinct**; M7 orthogonal |
| `test_minimality.py` | M1 ∧ M2 ∧ M3 **PASS**; `ΔR(B)` minimal |
| `test_invariant_preservation.py` | **5/5 PRESERVED** |
| `test_affected_falsification.py` | **5/5 PASS** (F1, F3, F5, F6, F10) |
| `test_repair_selection.py` | **B selected**; A **refuted** by executed counterexample; C2 fails M3 |

**Selected repair: B — the inquiry register `Q_t ⊆ 𝒫`, placed *outside* `K`.**
`|components of K| = 2 (𝒜, ℛ)` — unchanged. `Σ` remains a function of `e` alone.

### 2.1 A correction to my own prior recommendation

In `09` PART 6 I wrote: *"Candidate A is the minimal repair… Repair at `(0,0)`, not across `Σ`."*

**Half of that was wrong, and the executed refutation of A is sound:**

```
after Ask(p):  |𝒜| = 2                    a BOTTOM marker is now a MEMBER of 𝒜
contradicts(real 3.69, BOTTOM) = True     *** SPURIOUS CONTRADICTION ***
is_orphan(BOTTOM) = True                  the inquiry marker is itself an orphan
Σ(BOTTOM) = ('Neutral','None')            a non-assertion carries an epistemic status
```

and A additionally requires `BOTTOM ∈ V_D`, which corrupts the ValueSpace that makes
`WellFormed(P)` decidable. **A is refuted on executed grounds. I recommended it on an unexecuted
minimality intuition, and I was wrong.**

**The other half held.** *"Repair at `(0,0)`, not across `Σ`"* is exactly what B does: `Q_t` sits
alongside `K`, and `Σ` is untouched. And my `09` call that **M7 is structural, not epistemic**
(`orphan(a) ⟺ deg_ℛ(a)=0`) is confirmed by their orthogonality test — same conclusion, reached
independently before their result was available.

### 2.2 What IC281 does and does not mean

Their own caveat is correct and I endorse it:

> *"IC281 is a computational result about a theory revision, certified by the same session that
> proposed it."* — **Level 4 only.** The real EKP has no inquiry register, so `Ask(p)` was never
> observed in the running system.

**Empirical closure remains NOT ACHIEVED**, and for the reason that was always the larger one:
**15 of 24 constructs have no real-environment observation**, which no theory revision can fix.

---

## PART 3 — Step 282's verdicts, and what they do to my register

| Item | Step 282 verdict |
|---|---|
| **T-3 Probability** | **NOT REQUIRED FOR THE CORE THEORY** — closed at core level |
| **T-4 Non-identifiability** | **NOT A CORE-THEORY BLOCKER** — extension concern |
| **I-2** | implementation / dependency-graph artifact |
| **`Q_t`** | event-derived projection |
| Formal / Computational / DDD | **SUBSTANTIALLY CLOSED** |
| **Empirical / Governance** | **NOT CLOSED** |
| Overall | **THEORY PROVISIONALLY CLOSED — EMPIRICAL CERTIFICATION PENDING** (Outcome B) |

---

## PART 4 — Register movements

### CLOSED — for the first time in this investigation, with executed evidence

| Gap | Evidence | Status |
|---|---|---|
| **A6 / G-60** missingness carrier | Repair B; E4 7/7; CF#7 re-check DISTINCT; **independently re-run by me** | **CLOSED (Level 4)** — the carrier is `Q_t`, and it is named, typed and tested |
| **G-64** orphan | M7 orthogonal; structural predicate over `ℛ`; O1/O2/O3 separated from `Σ` | **CLOSED as a theory gap** — reclassified **structural**, exactly as `09` recommended |

**These are the first two gaps in my register to close on executed evidence rather than argument.**
Both carry the Level-4 caveat.

### RECLASSIFIED — not holes, out of scope by declaration

| Gap | Was | Now |
|---|---|---|
| **G-22 / MT-1** no `(Ω,𝓕,P)` | ① theoretical hole | **⑧ out-of-scope capability** — T-3 closes it *at core level*. A declared exclusion is not a defect |
| **MT-2** confidences can sum > 1 | ① | **DISSOLVED** — 272B removes numerical confidence from the core; with no numbers there is no incoherence to forbid |
| **MT-3** 176 flipping rules | ① | **CONDITIONAL** — bites only if *Assessment* does arithmetic on the ordinal grade. Since `07` moved Strength to Assessment, this is now a **constraint on Assessment**, not on `Σ` |
| **G-12 / MT-5** no empirical relational structure | ① | **⑧ at core level** — but **still ① for Assessment**, which is where measurement now lives |

### **A consequence Step 282 has not drawn**

Step 280 recorded **E20 as BLOCKED** — *"no `(Ω,𝓕,P)`; calibration inexecutable."*
Step 282 now rules **probability is not required**.

> **Then E20 is not BLOCKED. It is NOT APPLICABLE.**

A test blocked for want of a construct the theory has since declared unnecessary is not evidence of
incompleteness — it is evidence of a stale test. **Recommend reclassifying E20 before the closure
matrix is finalized**; leaving it as BLOCKED overstates the residual gap by one row.

### STANDING

`G-02` `K` minimality · `G-03` identity/equality · **`G-56` congruence ≠ sufficiency** ·
`G-55`/`D-5` `ℛ` 3-field · `G-57` `AuthorityAct` untyped · `G-15` `Context` untyped ·
`G-61` strength cardinality · `G-62` `Compare` undefined · `G-63` citation drift ·
`G-65` 15/24 unobserved · `G-66` no `Authorize()` runtime.

---

## PART 5 — Three lenses on the repair

### Mathematician

**Repair B is correct, and its invariant argument is stronger than it looks.**
Because `ΔR` lives **outside** `K`, every invariant defined over `K` is preserved **by construction
rather than by re-proof**. That is a genuinely good structural move — it converts a proof obligation
into a typing fact.

**But it changes what "the state" means, and that is not yet recorded.** After Repair B:

$$\text{system state} = (K,\ Q_t,\ H) \qquad\text{not}\qquad K$$

`K` alone can no longer distinguish M1 from M2 — that distinction now requires `Q_t`. So the
sufficiency question re-opens **one level up**: is `(K, Q_t)` sufficient, and is it minimal?
**`G-56` is exactly the instrument for that**, and it remains unadopted.

**This does not reopen `Σ`.** `Σ` stays `P({Support,Refute})`, a function of `e` alone — consistent
with `07` and with the D-4 answer.

### Statistician

**T-3 closing "not required" is the right call, and it retires more than it claims.** With no
probability and no numerical confidence in the core, three of my measurement findings change status
(§4). What it does **not** retire is measurement in **Assessment**: `Qualify`, `Assess` and `Compare`
still need an order over evidence, and **`G-12` still applies there**. T-3 closes the core; it does
not close the layer where the numbers actually live.

**And one caution.** T-4 is classified *"not a core-theory blocker — preserve distinction."* Preserving
a distinction the theory cannot express is not the same as closing it. Non-identifiability —
*different underlying realities, same available evidence* — is precisely the case where `Σ` is
`(0,0)` for a **structural** reason rather than an evidential one. Under Repair B that is
`Asked + Absent`, which is **indistinguishable from a merely-unlucky search**. **T-4 should be logged
as a deferred extension with a named carrier, not as closed.**

### DDD architect

**`Q_t ⊆ 𝒫` is a well-placed bounded-context decision.** Inquiry is not knowledge; putting the
register alongside `K` rather than inside it keeps the epistemic context free of process state — the
same separation `278` made for governance.

**The cost is stated honestly by the authors and should be tracked:** *"`Q_t` must itself be replayable
and serializable. `Ask(p)` becomes a recorded event in History — **not** a `K`-transformation."*
That is a new obligation on the **event vocabulary**, and it lands on `G-65`: the EKP has no
inquiry register, so `Ask(p)` is unobservable in the only running instance.

**`M7` belongs with `circular_dependency` and `relationship_targets_exist`** — a structural-integrity
concern, now confirmed by execution rather than asserted.

---

## PART 6 — Net position

| | |
|---|---:|
| Gaps **CLOSED** (executed evidence) | **2** — A6/G-60, G-64 |
| Gaps **reclassified out of scope** | **4** — G-22, MT-2, MT-3, G-12 (core only) |
| Gaps **standing** | **11** |
| Gaps **added** | **0** |
| **Theory closure** | **PROVISIONALLY CLOSED** (Outcome B) |
| **Empirical closure** | **NOT ACHIEVED** — 15/24 unobserved |

**The direction of travel has reversed.** Every prior update in this series added gaps or confirmed
them. This one **closes two and retires four**, on executed evidence that I re-ran myself.

**The reason is not that the theory got better arguments. It is that the programme started running
tests it could fail** — Step 280 failed one, Step 281 fixed it, and the fix verifies.

---

## PART 7 — What I would put to Step 282

1. **Reclassify E20 from BLOCKED to NOT APPLICABLE.** T-3 makes it stale (§4).
2. **Re-ask sufficiency at the new level.** `(K, Q_t, H)` is the state now; `K`-only sufficiency no
   longer answers M1 vs M2. **Adopt `Sufficient(K,𝒪,ℐ)`** — `G-56`, still 0 adoption, and now
   load-bearing for the very repair just made.
3. **Do not report T-4 as closed.** Log it as a deferred extension with a named carrier; under
   Repair B it currently collapses into `Asked + Absent`.
4. **State the Level-4 boundary in the closure matrix itself.** IC281 is certified by its own
   proposer; the matrix should carry that, not just the report.
5. **Keep `G-12` open for Assessment.** T-3 closes probability for the core; measurement still lives
   where `Qualify`/`Assess`/`Compare` do.

**And the ruling's own instruction is the right one:** *do not use "complete", "proven", "validated"
or "canonical" unless the evidence supports that exact claim.* On the evidence I re-ran:
**"provisionally closed, empirically uncertified, Level-4 verified" is exactly what is supported.**

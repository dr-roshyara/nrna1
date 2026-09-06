# OPERATION REGISTRY — INDEPENDENT FALSIFICATION REVIEW

> ## ⛔ STATUS BLOCK — NOTHING IN THIS FILE IS RATIFIED
>
> **Authority:** HPA commission **GN-79 / GN-80**, clause **C-8**, executed under **GN-82 / GN-83**
> (2026-08-31). This is an **independent falsification review**, not a ratification, not an
> acceptance, and not a repair.
>
> **Nothing below is `RATIFIED`.** The word `RATIFIED` appears only to name constraints that were
> ratified **before** this commission. No operation is named as canon here. No registry is
> selected, completed, repaired or improved. **No finding in this review creates authority.**
>
> **The reviewer did not author the derivation under review and took no part in constructing any
> candidate registry.** The posture is adversarial by instruction.
>
> **Boundaries observed:** `model/`, `final-architecture/`, `analysis/`, `brainstorming/`, `book/`,
> `book-edition-2/`, `book-architecture/` were **read only** and **not modified**. `GC-1` is
> untouched. `OQ-1 … OQ-12` are unmoved. `OQ-3` / `OQ-4` are untouched. Σ, `Q_t`, identity,
> equality and replay are **not defined** here. The book is not cited as evidence anywhere in this
> review. The word "validated" is not used of anything.
>
> **Epistemic markers used:** `VERIFIED` (I re-executed or read the primary source) ·
> `REFUTED` · `OVERSTATED` · `UNVERIFIED` · `NORMATIVE — NOT MINE TO DECIDE`.

---

## 0 · What I did

### 0.1 Integrity check — all three deliverables

| file | claimed md5 | computed md5 | lines |
|---|---|---|---|
| `OPERATION-REGISTRY-DERIVATION.md` | `4cd2b33aad476c9efdd3c48269906df9` | **matches** | 589 ✓ |
| `MINIMALITY-RESULT.md` | `5f30594fddf4eb74f6db00b2ae1198d1` | **matches** | 485 ✓ |
| `OPERATION-CONTRACTS.md` | `7aac27a9cf161b3cdd9ec996880da983` | **matches** | 629 ✓ |

All three verified **before** reading. `VERIFIED`.

### 0.2 The ratified surface — read directly, not through the derivation

I read, in full and before reading any deliverable:
`model/canonical-architecture-v0.2.md` (AUTHORIZED, GN-19) ·
`model/canonical-architecture.md` (v0.1 — required, because v0.2 §1/§4/§5–7 carry I-1…I-10, the
concept rows and the DC 6-tuple **by reference** rather than restating them) ·
`final-architecture/FA-1`, `FA-3`, `FA-6`, `FA-8`, `FA-9` (RATIFIED, GN-31) ·
`architecture/20260822-0951-KOS-EP01-Constitution-v1.0.md` Articles 3, 4, 7, 8, 9 ·
and, as primary sources for specific claims, step 256 §256.11, step 025a-2 §36,
`canonical-construction/exec/oderive.py`, and `analysis/OPERATION-CONTRACT-GAP.md`.

### 0.3 Re-execution — all five scripts, independently

I copied all five scripts to a scratch directory and re-ran them under Python 3.13.2:

| script | reproduces? |
|---|---|
| `inventory.py` | **byte-identical** to `OUT-inventory.txt` |
| `circularity.py` | **byte-identical** to `OUT-circularity.txt` |
| `consistency.py` | **byte-identical** to `OUT-consistency.txt` |
| `mintest.py` | **byte-identical** to `OUT-mintest.txt`, modulo the one wall-clock line |

`VERIFIED`. The stored outputs are the genuine product of the stored code. This is a real and
non-trivial pass: the deliverable's evidence chain is mechanically replayable by a third party.

---

## 1 · GATE VERDICTS

### AC-0 · Ordering rule — has the derivation EARNED THE RIGHT to propose? — **PASS**

Applied first, before any consideration of whether particular operations "make sense."

The evidence chain **can be shown**, end to end, and I traced it without relying on the
derivation's own characterisation:

1. **The requirement index set is derived, not borrowed.** `exec/circularity.py` establishes — and
   I re-ran it — that the corpus's own `D_mandatory` register (272A §272A.17) is a **bijection**
   onto 272A's own operation list, so the corpus's stated necessity criterion returns "all 19
   necessary" by construction. The derivation therefore could not use the record's requirement set,
   and says so. This is a genuine methodological result, not a rhetorical move. `VERIFIED`.
2. **The closure computation exists and is real.** `mintest.py:160` `reachable()` and
   `mintest.py:186–232` `_phaseA/_phaseA_at` are an actual bounded BFS that composes operations by
   applying `fn(st, a)` and testing outcomes. There is **no** law→operation lookup table anywhere
   in the file. The `CAPS` table (`mintest.py:96–114`) is a table of *(seed state, goal predicate,
   depth bound)* — a specification of the **question**, not of the **answer**; which operations
   satisfy it is computed. `VERIFIED`.
3. **Removal was run**, including interacting subsets (`mintest.py:482` onward — 630 pairs, 2024
   triples). `VERIFIED`.
4. **Every deliverable carries a status block disclaiming ratification**, and I found no instance
   of a `RATIFIED` claim over any of its own content.

The right to propose is earned. **AC-0 PASSES**, and the content is therefore argued about below.

I record one qualification that does not reach failure: the deliverable does not merely propose a
registry — it **declines** to, which is a weaker and more defensible act than the gate anticipates.

### AC-1 · Completeness of the candidate universe — **FAIL**

**What holds.** The 17-source inventory is real, mechanically unioned, and reproduces. The three
enumerations claimed as newly folded in are genuinely present and genuinely absent from prior
passes: step 274 (`Contest`, `QualifyEvidence`), step 276 (six families including `O_X` as
members), and the executed harness's own 9-op set (`281x`, including `contradicts`, a name that
appears under that spelling nowhere else). The pre-canonical/historical separation is correctly
justified by **FA-1 §1 L5** (*"HISTORICAL CORPUS … evidence of discovery, never authority"*), which
I read directly. §1.6's refusal to claim absolute closure is appropriate as far as it goes.
`VERIFIED`.

**Why this nevertheless fails.** The commission instructed me to *"independently search the corpus
for operation names it missed."* I did, and the universe is **materially incomplete** — not at the
margin, and not only in ways §1.6's hedge covers. At least **twelve enumeration sources** are absent
from the 17-source list, including one the derivation's own supporting corpus describes as a
*"24-operation taxonomy with 18 signatures + pre/postconditions"*, and at least one **live,
governance-escalated operation name** (`unask`) is absent from all 57. I verified the four
load-bearing items myself, from primary sources, rather than accepting them second-hand. Full
finding at **IR-F-26**; the decisive items are:

- **Question 15 §2.3, a table literally headed "The Operation Taxonomy"** — 8 categories × 3 = 24
  operations, followed by *"## 3. Detailed Operation Definitions"* with arrow-typed signatures.
  Roughly **nineteen** of its names appear in neither the 57-name live universe nor the 48-name
  historical union (`Detect`, `Parse`, `Discover Dimension`, `Derive Proposition`, `Re-evaluate`,
  `Add Evidence`, `Change Epistemic State`, `Revise Value`, `Detect Conflict`, `Detect Gap`,
  `Resolve Conflict`, `Resolve Gap`, `Reframe`, `Prioritize`, `Present`, `Preserve`, `Recall`,
  `Rollback`, `Measure`). The file is cited by **no** key in the 17-source list.
- **`unask`** — a live operation name under an **open** normative decision (`ND-282-1`,
  `verification/step-282/11-STEP-282-HUMAN-DECISION-REGISTER.md`; `GD-08` at step 283 §283.14,
  where the recommendation `No unask` is explicitly marked *"Recommendation ≠ Decision"*). It is in
  neither list, and it sits entirely **above the derivation's stated reading ceiling of step 281**.
- **Steps 282, 283 and 284 exist.** The read list stops at 281 (plus 277). The ceiling is wrong by
  three steps, and `unask` and the `Q_t` operations live above it.
- **Steps 260 and 261 contain operation enumerations bearing directly on minimality** and are
  uncited — step 261 §261.19's operation/equality matrix carries **`Deduplicate`** as a first-class
  row with verdict `NORMATIVE/DOMAIN-DEPENDENT`, and step 260 enumerates
  `Merge, Supersede, Replay, Remove, Revise, Deduplication, Validation`.

**Why this is a FAIL and not a finding.** Task 1's stated output is *"the **complete** candidate
operation universe"* and §1.1 presents 57 as *"the number a registry decision must range over."*
That number is not the universe. And the incompleteness is **not epistemically inert**: several of
the missed names are plausible **alternative realizers of exactly the capabilities whose core
membership rests on a unique-writer argument** — `ResolveConflict`/`ResolveGap` against `Resolve`'s
sole claim to A10, `AddEvidence` against `LinkEvidence`'s core membership for A5, and
`Preserve`/`Recall`/`Rollback` against A15. An incomplete universe can only **overstate** necessity
and **understate** multiplicity, which is the same direction as the method's other bounds — so the
§5.2 necessary core of twelve and the band of nine cannot be relied upon as computed values.

I also found the source-count reporting internally inconsistent across four different values, and
one executed figure misreported in the prose. See **IR-F-7**, **IR-F-8**, **IR-F-9**.

### AC-2 · Minimality actually executed — **PASS WITH FINDINGS**

**A described test is a failed test — this test was run.** I re-ran it; it reproduces
byte-identically. The closure computation is genuine BFS, not a hand-authored table, and I applied
the derivation's own standard (the one it uses against `oderive.py`) to its code: `mintest.py`
clears that bar. Removal was executed at the single, pair and triple level. The unique-writer
cross-check (§2.1) is a legitimate device for separating search-independent necessity from
search-dependent necessity. `VERIFIED`.

**Three findings reduce this from a clean pass:**

- The registry enumeration is **exhaustive for only one of the three reported variants**
  (**IR-F-3**) — `mintest.py:392–394` substitutes a single hand-constructed, explicitly
  "not proven-minimal" support set for A13.
- One operation in the pool **does not implement the semantics its capability tests for**
  (**IR-F-2**) — `op_Replay` sets a flag and never folds history, which is the *identical* defect
  the derivation uses to discredit the record's withdrawn A6 witness.
- The A6 consistency probe **cannot reach the sharpest A6 hazard the deliverable itself names**
  (**IR-F-12**).

### AC-3 · Hidden assumptions — **PASS**

The claim that Σ and `Q_t` are "used nowhere in the state core" is **true of the code**, and I
tested it rather than accepting it. `Σ` appears in no field of the state record; the evidence grade
is a `frozenset` of source-class strings (M4), so I-5/I-6 are satisfied structurally without
selecting an aggregation operator — which is what leaves **OQ-3 genuinely untouched**. `Q_t`
appears nowhere. §3.4's table of operations-that-need-something-unratified is accurate against the
sources I spot-checked, and the corresponding operations are reported rather than relied on.
`𝒪_core` and `𝒯_candidate` are read as candidates and adopted nowhere. `VERIFIED`.

Two qualifications, both recorded as findings rather than failures: one declared-commitment is
**missing** from the §0.4 list although it demonstrably changes the headline result (**IR-F-4**,
`op_Resolve`'s hardcoded return rung), and one capability's *formulation* imports unratified
vocabulary (**IR-F-15**, A11's "dimension"). Neither is a hidden dependence on Σ or `Q_t`.

### AC-4 · Alternatives genuinely reported, not selected — **PASS WITH FINDINGS**

**No selection is made.** I checked the prose for privileging and found none: all six registries
are printed with equal weight in §4.2, the three generating choices are each marked
`NORMATIVE DECISION REQUIRED`, and no registry is described as preferable, natural, or recommended.
R6 is excluded on an *executed consistency* ground (`Split`), not a preference — and the exclusion
is reported as "a *reduction* in multiplicity, not a resolution," which is the correct
characterisation. The cross-check that the intersection of all minimal registries equals the
independently computed necessary set is a real self-consistency test and it passes in all three
variants. `VERIFIED`.

**The exhaustiveness claim is where this gate is wounded.** "All minimal sufficient registries —
**SIX, in every variant**" (§4.2) and "enumerated **exactly**" (§0.3 step 3) are true only of
`R_A_strict`. See **IR-F-3**. The related "band invariant at nine across all three sets" — which
T-5 offers as its answer to the AC-6 watch item — is substantially a mechanical artifact of adding
one fixed set to every registry, not independent evidence of robustness.

### AC-5 · Invariant / governance compatibility — **PASS WITH FINDINGS**

I checked each reported result against the ratified text myself.

| claim | my verdict |
|---|---|
| **I-5** duplicates do not amplify; corroboration does | `VERIFIED` — executed, and I-5 is one of only two `TESTED` invariants in v0.1 §4. The derivation's characterisation of I-5/I-6 as "the only TESTED invariant pair in the whole model" is **accurate** (v0.1:85–86). |
| **I-6** dependency resolution precedes aggregation | `VERIFIED` — the unresolved unit is *excluded*, not down-weighted, which is the correct reading. |
| **I-9** four-way typology never collapses to Boolean | `VERIFIED` against v0.1:89. 0 violations. |
| **I-2** proposal selector holds no authority | `VERIFIED` against v0.1:82 + v0.1:64 flow node. |
| **Art. 7** REJECTED terminal-preserved | `VERIFIED` against Constitution Art. 7.1–7.2. 0 violations. |
| **A6 / I-4** holds for all 55 pool members | `VERIFIED` against v0.1:84 — for the pool. See the probe caveat at **IR-F-12**. |
| **`Commit` per step 025a-2 §36 crosses A6** | **`VERIFIED` — and this is the deliverable's strongest single finding.** I read §36 verbatim: five conjuncts (`Relevant ∧ TemporallyValid ∧ SufficientSupport ∧ NoBlockingConflict ∧ ProvenanceAvailable`), **no authority conjunct**, `SufficientSupport` evidence-derived. The corpus self-labels it "**experimental**, not final" and the derivation reports that caveat. A corpus-stated commitment rule that would, if adopted, violate the ratified A6 is a real and material result, correctly scoped and correctly recommended for exclusion **with its reason**. |
| **`Split` violates I-12** | `VERIFIED as executed`, **OVERSTATED as attributed** — see **IR-F-11**. The three cited signatures say nothing about the products' status; the inheritance comes from `replace()` copying all fields. |
| **`Reject` violates I-12 and Art. 8** | `VERIFIED as executed`, **REFUTED as attributed** — see **IR-F-1**, the review's principal finding. Art. 8.1's text (*"SHALL coexist as CONFLICTED until governed resolution"*) does make `Conflicted → Rejected` with no governed act a violation **given the encoding**; the encoding is the problem. |
| **I-11 holds; 3 model-detected ambiguities** | `VERIFIED`. The §3.3 ambiguity (in-force record ↔ content item binding) is real against the *ratified* text, and correctly distinguished from GC-1. But the corpus contains a candidate answer the derivation did not read — **IR-F-10**. |

**One ratified constraint was never consulted at all.** Constitution **Art. 8.3** — *"Resolution
SHALL be forward-only: the conflict record SHALL survive resolution"* — bears directly on P-2, the
return-rung question the derivation elevates to a structural discovery. The string "forward-only"
appears **zero times** across all three deliverables and all five scripts. See **IR-F-5**.

### AC-6 · ENTAILMENT — the HPA's specific watch item — **PASS WITH FINDINGS**

This is the gate I weighted most heavily. I traced **each of the 13 claimed RATIFIED-FORCED
capabilities** to a primary ratified/authorized object, reading v0.1, v0.2, FA-1 and the
Constitution rather than the derivation's citation column.

| cap | claimed anchor | trace result |
|---|---|---|
| **A1** Candidate→Supported | ladder + I-12 | **SURVIVES.** v0.2 §1 R-3 (three-valued ladder) + R-4/I-12 covering relation. Without a traversable first rung the ladder is vacuous — criterion (a) properly applied. |
| **A2** Determination, in-force policy | ladder + I-12 + *Determination* row | **SURVIVES.** v0.1:31 defines *Determination* as *"the `Supported → Accepted` transition under AcceptancePolicy"*, carried forward unamended; v0.2 §3 supplies "in-force." Both halves are real. |
| **A3** Committed by authority act | A6 / I-4 | **SURVIVES.** v0.1:84 I-4 verbatim; v0.2 R-3 re-types `Committed` as a decision-boundary status. |
| **A4** governed policy version change | I-11 + §3 loop | **SURVIVES.** v0.2 §4 I-11 + §3 stratification loop, both in the AUTHORIZED text. |
| **A5** compose evidence | I-5 + I-6 | **SURVIVES**, and on the strongest available ground — the only two `TESTED` invariants. |
| **A6c** typed gap | `Zero(K,EC)` + I-9 | **SURVIVES.** v0.2 R-2 + v0.1:89. |
| **A7** observation → evidence | `SourceObs ≠ SemanticObs` | **SURVIVES, MIS-CITED.** The cited distinction is between two kinds of *observation* and does not by itself force an observation→evidence transition. A7 survives instead via criterion (b) — v0.1:55's flow arrow to `EVIDENCE` — and via the gate-ratified `observation ≠ evidence` (v0.1:43). Neither is the anchor named. **IR-F-16.** |
| **A8** reach REJECTED, preserved | FA-1 §2 / D-FA-1 · Art. 7 | **SURVIVES.** FA-1:49 + Art. 7.1–7.2. Ratified GN-31. |
| **A9** reach CONFLICTED | FA-1 §2 / D-FA-1 · Art. 8 | **SURVIVES.** FA-1:50 + Art. 8.1. |
| **A10** governed exit from CONFLICTED | FA-1 §2 · Art. 8 *"until governed resolution"* | **SURVIVES.** The forcing is real and rests on a contrast I checked: FA-1:49 marks REJECTED *"[terminal, never deleted]"* while FA-1:50 marks CONFLICTED *"[until governed resolution]"*. The asymmetry does force an exit capability. Note FA-9's own row for D-FA-1 concedes *"interaction semantics ladder↔CONFLICTED left coarse"* — the capability is forced; its target is not. |
| **A11** UNKNOWN ≠ ABSENT | Art. 9 + I-9 + FA-1 §2 | **SURVIVES AS CAPABILITY, OVERSTATED AS PHRASED.** FA-1:52 and Art. 9.2 are ratified and unambiguous. But neither speaks of "dimensions"; A11's phrasing imports ⟨D_t⟩ from a research artifact. **IR-F-15.** |
| **A12** authority-free proposal | I-2 + §3 flow node | **SURVIVES.** v0.1:82 + v0.1:64 *"PROPOSAL (selector, no authority)"*. |
| **A13** decision admissible under DC | 042 DC 6-tuple + I-3 | **SURVIVES AS CAPABILITY**, and the derivation **correctly** grades the six-slot *conjunction* as PROPOSED. v0.1:35 carries the 6-tuple; v0.1:67 carries the flow node; v0.1:83 carries I-3. The honesty here is exemplary: the capability and its formulation are graded separately. |

**All 13 trace to a genuine ratified or authorized anchor. None fails on "the theory needs it."**
That is the substantive answer to the HPA's watch item, and the derivation earns it. A7's citation
is imprecise and A11's phrasing over-reaches, but neither capability is manufactured.

The two `PROPOSED` capabilities are correctly graded and correctly excluded from the primary run.
§2.4's explicit list of sixteen corpus-named operations the ratified surface does **not** force is
exactly the right discipline, and I confirmed it is honoured — none of the sixteen enters `R_A`.

**Two findings against this gate.** The stated forcing criterion is **not applied uniformly**
(**IR-F-14** — criterion (b) would force A14, and the derivation declines, correctly but on
judgment). And §2.3's summary sentence makes a **mandatory claim about A15 that the same document
grades PROPOSED two pages earlier** (**IR-F-6**) — the precise failure mode AC-6 exists to catch,
caught here in the summary rather than the analysis.

---

## 2 · ADDITIONAL ATTACK SURFACES

### 2.1 Could a registry SMALLER than the reported minimum exist?

**The bias claim is TRUE, and I verified the reasoning rather than accepting it.** If a witness is
missed: fewer witnesses ⇒ fewer minimal registries (understates multiplicity ✓); and `o` is
necessary iff *every* witness of some capability contains `o`, so a missed witness not containing
`o` makes `o` look necessary when it is not (overstates necessity ✓). The direction is as claimed,
and a multiplicity result found **against** that bias is correspondingly stronger. The derivation's
reasoning on this point is sound.

**But the caps do bear on minimum size, and one exclusion is by cap rather than by evidence.** A13
has **no witness of size ≤ 4**; the size-4 enumeration returned nothing and a size-7 support was
supplied by construction. The derivation states the sound half — any minimal A13 witness has size
≥ 5 — but then uses the size-7 set as though it were the family. If A13's true minimal witnesses
are size 5 or 6, both the minimum registry size and the number of registries change for the two
variants that include A13. So: a smaller registry was **not** excluded by evidence for `R_A` and
`R_A_ratified`; it was excluded by a cap. `R_A_strict` is unaffected. **IR-F-3.**

The undetermined band of **nine** is not a registry size (registry sizes are 14–20) and I found no
claim confusing the two.

### 2.2 Equivalent alternative registries not found

Beyond IR-F-3, the enumeration device (`minimal_registries`, `mintest.py:272`) is sound: it takes
the iterated union over witness families and reduces to the antichain, which is exact **given the
families**. Its exactness is therefore exactly as good as the witness families, and for A13 the
family is a single constructed element. Two further generators of missed alternatives are declared
by the derivation and I confirmed the direction of each: M3 (both granularities in the pool)
*increases* multiplicity, and M6 (guards from ratified constraints only) biases *against* it. Both
disclosures are accurate.

### 2.3 Replayability — does replay survive its own contracts?

**No — and this is a RED finding.** `op_Replay` (`rm.py`) is:

```python
def op_Replay(k: K, arg: str):
    ok = len(k.hist) > 0
    return log(replace(k, replays=k.replays | {("H", ok)}), "Replay", arg), OK
```

It sets a marker. It never folds history back to state. A15's goal predicate is
`g_replay(k) = ("H", True) in k.replays` — it tests precisely that marker. So "`Replay` is
necessary for A15" is a tautology over a field invented for a single no-op operation, and
§2.1 nonetheless lists `replays <- Replay` among necessities graded `FORMALLY SHOWN`. **IR-F-2.**

Separately, the contracts surface a genuinely sharp replay question — C-03's *"is replaying an
authority act legitimate? Replaying a recorded authorization re-confers authority without a fresh
act"* — which is a candidate A6 crossing. The consistency test cannot see it, because the A6
adversarial state is built with no authority act to replay. **IR-F-12.** The question itself is a
real contribution; it simply is not tested.

Mitigating: A15 is graded PROPOSED and dropped from `R_A_ratified` and `R_A_strict`, so the vacuity
contaminates only the `R_A` variant, and the derivation does say replay "is undecidable without a
state-equality rule the canon does not supply."

### 2.4 Rejection semantics — is the blockage real or an artifact of framing?

**Real.** I checked the ratified surface for a rejection vocabulary and found none: v0.2, v0.1 and
FA-1 contain no typed-failure vocabulary of any kind, and the three unanswered questions the
derivation names — the state after rejection, whether rejection is recorded in history, whether a
rejected operation is a no-op or an exception — are genuinely unanswered by the ratified text.
So `failure semantics` cannot be canonically filled for **any** operation, and reporting all 23
blocks as blocked on that field is correct, not an artifact of the model's own framing.

The model-relative part is the *count*: five kinds were needed because the model itself introduced
`legality` (I-12) and `evidence` (232 §232.21) refusals. That is declared `PROPOSED` in place. The
distinction between "the canon supplies no vocabulary" (real) and "five kinds are required"
(model-local) is correctly drawn in §1.2.

### 2.5 Structural discovery 1 — the claimed I-12 bypass — **REFUTED as characterised**

The claim: `Candidate → Conflicted → Supported` is an "I-12 bypass," and *"the item arrives at
`Supported` without ever traversing the covering relation `Candidate ⋖ Supported`."*

**It is not a bypass of I-12's prohibition.** I-12 (v0.2 §4, from R-4) excludes **skipping**: each
status is reachable only from its immediate predecessor. `Supported`'s immediate predecessor **is**
`Candidate`. The item was at `Candidate` before suspension and arrives at `Supported` — its
immediate successor. **No rung is skipped**, so the axiom's prohibition is not engaged. The
genuinely dangerous path — `Candidate → Conflicted → Accepted`, which *would* skip `Supported` —
is **never tested**, although the derivation's own candidate answer *"a rung the resolving
authority names"* would permit it.

**And the path exists only because of an undeclared choice.** `rm.py:371`:

```python
if it.status != "Conflicted": return k, Rej.LEGALITY
return log(_put(k, replace(it, status="Supported")), "Resolve", arg), OK
```

`Resolve` returns to `Supported` **unconditionally**, regardless of the pre-suspension rung. Had it
returned to the rung held before suspension — the derivation's own first candidate answer — A1's
second witness would not exist. The derivation **knows** this and states the consequence in §5.3
(*"the family collapses from six to five with a different core"*), which is creditable. But the
choice is **absent from the §0.4 declared-commitments table** even though it changes the headline
count, and the finding is presented as *"a finding against the ratified layered state model,
discovered by execution"* when it is a consequence of one resolution of a gap.

**What survives, and it is worth keeping:** the ratified text genuinely does not say which rung a
governed resolution returns to, and whether an off-axis state may serve as the source of an on-axis
status is genuinely unaddressed. That underspecification is real, and P-2 is a legitimate open
question. Only the "I-12 bypass" framing is refuted. **IR-F-4.** And Art. 8.3 constrains the
question more than the derivation allows — **IR-F-5**.

### 2.6 Structural discovery 2 — A13 has no witness of size ≤ 4 — **VERIFIED**

I reproduced this exactly. The exhaustive size-4 enumeration returns nothing for A13; the
reachability program of 7 steps is in `OUT-mintest.txt:152–165`. The structural explanation is
sound and I checked it: the DC 6-tuple needs six independently produced slots and five of the six
have a unique writer in the pool, so A13's cost is a property of a six-slot contract rather than a
modelling accident. The lower bound "≥ 5" is correctly derived. `VERIFIED`.

The **inference** drawn from it is what fails — using the constructed size-7 support as the witness
family. That is **IR-F-3**, not a defect in the discovery itself.

### 2.7 The methodological accusation against `oderive.py` — **VERIFIED and fairly stated**

I read all 116 lines. The accusation is accurate in every particular:

- `FORCES` (lines 31–52) **is** a hand-authored dict mapping each law to the operation the author
  judged it to entail, with prose justifications inline.
- `LAWS` (lines 14–29) **is** hand-authored repetition counts, and line 13's own comment calls them
  *"measured repetition counts (evidence weight)"* — so "corpus frequency used as evidence weight"
  is the file's self-description, not an imputation.
- There is **no** closure computation, **no** removal test and **no** state model. The only
  computation is lines 53–56, inverting `FORCES`. "RESULT 3 — the closure question" (line 92) is
  pure `print()` of hand-written prose conclusions.
- `|DERIVED| = 14` is confirmed.

Two points of fairness, both of which the derivation gets right. It says *"This does not make the
14-element result wrong; it makes it **untested**"* — the correct and measured claim. And it
attributes the overclaim to the **citation** (*"the widely-cited 14-forced / 18-upper result"*)
rather than to `oderive.py` itself — which matters, because `oderive.py`'s own RESULT 3 concludes
*"Set-membership is therefore NOT derivable"* and offers only bounds with an undetermined band.
The file is more modest than its downstream reputation, and the derivation's aim is correctly taken
at the reputation.

One imprecision: "prints the **transitive** image of its own input table" — the operation is a
single-level inverse, with no transitivity involved. Immaterial to the substance. `VERIFIED`.

---

## 3 · FINDINGS

### RED

**IR-F-1 · RED · The headline obstruction rests on an encoding more permissive than its cited source.**
The deliverable's decisive claim — *"the single operation that every one of them must contain in
order to satisfy a **ratified** capability is an operation whose **specification** contradicts two
ratified constraints"* (`MINIMALITY-RESULT.md` §5.3) — is the stated ground for recommending **D**
over **C**. It depends on `op_Reject` carrying no source-status guard. I read step 256.11 verbatim
(`20260830-185954_step_256_formal-operation-signature-registry.md:445–470`):

- the signature is introduced under the heading "**Candidate:**";
- the status rule is introduced as "**Possible semantics:**" — *not* a specification;
- and that possible semantics **names a source status**: `Status(x): Proposed → Rejected`.

Step 256's own tables grade `Reject` 🟡 (line 75) and mostly `?` (line 1052). So:

1. **There is no "specification" to violate.** 256.11 offers a *possible* semantics, self-marked
   provisional. The derivation's phrase *"`Reject` as 256.11 specifies it"* upgrades a possibility
   to a specification.
2. **The derivation's own sentence is self-contradictory.** §3.2 V-2 asserts `Reject` *"carries
   **no guard on the source status**"* while quoting, in the same parenthesis, a rule that states a
   source status.
3. **The encoding is strictly more permissive than the source.** `op_Reject` drops the `Proposed`
   restriction entirely. The `Conflicted → Rejected` transition is therefore produced by the model,
   not licensed by the corpus.
4. **An unremarked vocabulary gap sits underneath.** `Proposed` is **not** a ratified status — the
   ratified ladder is `Candidate ⋖ Supported ⋖ Accepted`. Mapping 256.11's `Proposed` onto the
   ratified vocabulary is itself an unmade normative choice, and the derivation nowhere notes it.

**Consequence.** The obstruction as stated — *all six registries are inconsistent* — does not
survive. What survives is weaker and different: **`Reject`'s guard is unspecified, so the
consistency of every candidate registry is undetermined.** That still blocks establishment, and
P-1 remains a genuine prior decision. But the deliverable's stated ground for elevating the verdict
is materially stronger than its evidence. This is a finding about the strength of a claim, not a
refutation of the conclusion.

**IR-F-2 · RED · `op_Replay` does not replay, and the deliverable applies to the record a standard its own code fails.**
`op_Replay` (`rm.py`) computes `ok = len(k.hist) > 0` and records `("H", ok)`. It never folds
history to state. A15's goal tests exactly that marker. `Replay`'s necessity is therefore an
artifact of a field invented for one no-op operation, yet §2.1 presents `replays <- Replay` among
necessities graded `FORMALLY SHOWN`. The derivation discredits the record's strongest A6 witness on
precisely this ground — *"`evidence_volume` was declared in `commit()` and never referenced in the
body… the test would have passed identically had the law been false"* — and `op_Replay` would
report A15 realizable identically had replay been impossible. Contained by A15's PROPOSED grade and
its exclusion from two of three variants, but the symmetry is exact and the commission required the
code be held to its own standard.

**IR-F-3 · RED · "All minimal sufficient registries, enumerated exactly — six in every variant" is exhaustive for one variant of three.**
`mintest.py:392–394`:

```python
if ok and not fam[cid]:
    fam[cid] = [frozenset(sup)]
    print(f"     -> recorded as a CONSTRUCTED (not proven-minimal) witness; the")
```

A13's witness family is a **single hand-constructed size-7 set**, self-labelled *not
proven-minimal*. `R_A` (15 caps) and `R_A_ratified` (13 caps) both include A13, so their "6 minimal
registries" is conditional on A13 having exactly that one witness — not an exhaustive enumeration.
Only `R_A_strict` (A13 dropped) is genuinely exact. Two further consequences the deliverable does
not draw: (a) if A13's true minimal witnesses are size 5–6, both the count and the minimum registry
size change for two variants; (b) the **"band invariant at nine across all three sets"** — offered
in T-5 as the answer to the AC-6 watch item — follows substantially from adding one *fixed* set to
every registry, which cannot alter the band, so it is far weaker evidence of robustness than
presented. The caveat is disclosed in T-3 but is not propagated to the headline claims in §0.3 and
§4.2 that depend on it.

**IR-F-4 · RED · The "I-12 bypass" is mischaracterised, and rests on a modelling choice absent from the declared-commitments table.**
Full argument at §2.5 above. Three parts: (a) reaching `Supported` from `Candidate` skips no rung,
so I-12's prohibition is not engaged and "bypass" overstates; (b) `rm.py:371` hardcodes
`status="Supported"` unconditionally, and this choice — which §5.3 itself says changes the family
from six to five with a different core — is **not** in the §0.4 list of commitments that "could
change the conclusion"; (c) the path that *would* be a true I-12 skip,
`Candidate → Conflicted → Accepted`, is never tested although one of the derivation's own four
candidate answers to P-2 would permit it. The underlying underspecification is real and P-2 is a
legitimate open question; the characterisation as "a finding against the ratified layered state
model, discovered by execution" is not.

**IR-F-5 · RED · A ratified constraint bearing directly on P-2 was never consulted.**
Constitution **Article 8.3**: *"Resolution SHALL be forward-only: the conflict record SHALL survive
resolution."* The string "forward-only" occurs **zero times** in all three deliverables and all
five scripts; Art. 8 is cited roughly twenty times, exclusively in its §1 sense (*"governed
suspension" / "until governed resolution"*). Art. 8.3 constrains resolution — on the narrower
reading, that the conflict record survives; on the broader, that resolution may not move an item
backward. Under either reading, P-2's four candidate answers should have been tested against it,
and at least one ("return to `Candidate`") is in tension with it. A derivation whose central
structural discovery is *"FA-1 never states which rung a governed resolution returns to"* was
obliged to read the whole of the article it cites for that state. Also relevant and unconsulted:
Art. 8.2, *"Challenge SHALL NOT destroy identity."*

**IR-F-26 · RED · The candidate operation universe is materially incomplete; "57 live / 105 grand union" is not the universe.**
Task 1's output is *"the **complete** candidate operation universe"*, and §1.1 presents **57** as
*"the number a registry decision must range over"* and **105** as *"the whole search history."*
An independent sweep of the corpus, with the four load-bearing items verified by me from primary
sources, refutes both counts.

**(a) Question 15 is an uncited operation taxonomy with typed signatures.**
`phase_measure_theory/20260826-183128_question-15-state-transition-how-kt-becomes-kt-plus-1.md:69–81`
is a table headed **"### 2.3 The Operation Taxonomy"** — 8 categories × 3 = **24 operations** —
immediately followed by **"## 3. Detailed Operation Definitions"** with arrow-typed signatures
(`Detect(K_t, Pattern, Threshold) → K_{t+1}`, `Parse(K_t, Input, Language, Context) → K_{t+1}`,
`Preserve(K_t) → K_{t+1}`, `Recall(K_t, τ) → K_τ`, `Rollback(K_t, τ) → K_τ`, …). About **nineteen**
of its names are in **neither** the 57-name live universe **nor** the 48-name historical union.
The file appears under no key in the 17-source list.

This is not an obscure file. The derivation's **own** supporting corpus describes it:
`verification/spec/A3W-state-transition-register.md:52` records
*"`Transition(K_t, Operation, Parameters)`, total `(𝒦,𝒪,𝒫)→𝒦`, **24-operation taxonomy with 18
signatures + pre/postconditions** | Q15 original §2–§5"*.

**In fairness, the same line supplies a reason it might be excluded** — *"superseded by review
(Operation≠Event≠Transition; 𝒪⁺/𝒪?/𝒪q split)"*. So the **live** universe may well survive Q15's
exclusion. But the derivation **never cites Q15, never states this exclusion, and never records the
supersession** — so the exclusion is an omission, not a determination. And under FA-1 §1 L5 the
superseded material belongs in the **recorded historical union**, where it also does not appear. The
**105 grand union is therefore wrong regardless of how Q15's liveness is decided.** This also sits
oddly against §1.4, whose central finding is that the corpus never reconciles its enumerations: here
is a documented supersession the inventory did not surface.

**(b) `unask` — a live operation under an open governance decision, above the reading ceiling.**
Verified verbatim:
- `verification/step-282/06-QT-REPLAY-SERIALIZATION-RESULT.md:28–29` — `ask(p); unask(p) → Q = ∅`,
  and *"Whether `unask` should exist at all is a **normative** choice."*
- `verification/step-282/11-STEP-282-HUMAN-DECISION-REGISTER.md` — **`ND-282-1 — `unask` deletion
  semantics for the inquiry register`**, an open human decision.
- `phase_measure_theory/# STEP 283 — GOVERNANCE RATIFICATION AND.md:524–545` — `§283.14 GD-08`,
  recommending `No unask` while stating **`Recommendation ≠ Decision`**.

`unask` is in neither list. It is the one operation name in the corpus under an **active, named,
unresolved normative decision** — precisely the class of item a "complete candidate universe"
exists to capture.

**(c) The stated reading ceiling is wrong by three steps.** Steps **282, 283 and 284** exist
(`# STEP 282 — THEORY CLOSURE DECISION`, `# STEP 283 — GOVERNANCE RATIFICATION AND.md`,
`# step 284 …`; several are extensionless and invisible to a `*.md` glob). The read list stops at
281. `unask` and the `Q_t` operations live above the ceiling — which bears directly on the
derivation's claim that *"`Q_t` appears in **none** of steps 249/250/256/257/259/272A/277."* That
statement is true of those seven steps and misleading as a claim about the corpus.

**(d) Steps 260 and 261 enumerate operations, bear directly on minimality, and are uncited.**
`20260830-192425_step_261…:922–928` — §261.19's operation/equality matrix carries **`Deduplicate`**
as a first-class row (verdict `NORMATIVE/DOMAIN-DEPENDENT`) alongside `Merge`, `Supersede`,
`Replay`, `Remove`, `Revise`, `Validate`, all verdict `UNRESOLVED`.
`20260830-191956_step_260…:1315` enumerates
`Merge, Supersede, Replay, Remove, Revise, Deduplication, Validation`. The derivation dismisses
`dedup` on one register's word (TG-11, *"UNDEFINED — no such operation exists"*) while two uncited
steps treat it as an operation with a normative criterion.

**Further sources absent from the 17 keys** (reported to me and consistent with everything I
checked, but not individually verified by me — see §4): `verification/handoff/05-CANONICAL-CONSTRUCT-REGISTRY.md`
(the `hand` key covers only `handoff/02`; `05` carries a "Required operations" column with `dedup`,
`eq`, `append`, `fold`, and states `T` requires *13* ops — agreeing with neither algD's twelve nor
the derivation's counts); `verification/gap-discovery/second-order/01-OPERATION-UNIVERSE-ANALYSIS.md`
(a 17-row classification whose own headline reads *"`𝒪` is never enumerated — **False**"*, with
`EverContested`, `RevisionCount`, `ResolveProvenance`); `verification/RELATION-ALGEBRA.md:73`
(`Contradict : 𝕂 × 𝒜 × 𝒜 → Bool` — a **live typed signature** for a name the derivation files as
historical); step 147/138 command-and-query enumerations; step 252 (`ProveTruth`, a named
*excluded* operation the exclusion lists never record); step 254 (`ConflictResolution`); step 028
and the Doignon–Falmagne cluster (`EXPAND / REVISE / CONTRACT / CHALLENGE / SUPERSEDE / REINSTATE`).

**Why it matters beyond the count.** The incompleteness is not inert. Several missed names are
plausible **alternative realizers of precisely the capabilities whose core membership rests on a
unique-writer argument**: `ResolveConflict`/`ResolveGap` against `Resolve`'s sole claim to A10;
`AddEvidence` against `LinkEvidence`'s core membership for A5; `Preserve`/`Recall`/`Rollback`
against A15. By the derivation's own correctly-reasoned bias analysis, a missing realizer can only
**overstate necessity** and **understate multiplicity**. Therefore the §5.2 necessary core of twelve
and the band of nine **cannot be relied upon as computed values** — not because the computation is
wrong, but because it ranged over an incomplete universe.

**What is NOT claimed here.** I do not claim the missed names are necessary, should be admitted, or
would change the terminal verdict. Determining their status is Task 1 work and belongs to whoever
holds authority to reopen it. I claim only that the universe is not complete and was not shown to be.

### AMBER

**IR-F-6 · AMBER · A mandatory overclaim in a summary sentence, contradicted by the same document.**
§2.3: *"**Six** capabilities the ratified architecture **forces** are missing from the governed
capability list"* — the six being A8, A9, A10, A11, A12, A15. But §2.2 grades **A15 `PROPOSED`**
with the explicit note that its forcing law *"is **not** in the ratified surface at all."* Five are
ratified-forced, not six. The defending sentence immediately after correctly claims only four (the
D-FA-1 group), so the analysis is sound and only the headline overstates. Exactly the failure mode
AC-6 watches for.

**IR-F-7 · AMBER · An executed figure misreported in the prose.**
§1.4: *"The script compared all **45** pairs among the **ten** enumerations that claim to be an
operation universe."* The executed output has **13** claiming enumerations and **78** pairwise rows
(`OUT-inventory.txt`; C(13,2)=78, and I counted 78 comparison lines). Two sentences later the same
section says *"all **thirteen** claiming enumerations."* No conclusion changes — the intersection is
still empty and "NO TWO ENUMERATIONS AGREE" still holds — but a deliverable whose authority rests
on execution misstates its own executed count.

**IR-F-8 · AMBER · The source count is reported inconsistently as 10, 13, 14 and 17.**
§1.1 and AC-1 say **17** (correct, per `OUT-inventory.txt:6`). §1.3's prose header says *"how many
of the **14** sources"* against a column headed *"n (of **17** sources)"*. §1.4 says **ten**. §1.6
says *"closed with respect to the **fourteen** sources inventoried."* §1.6 further calls 257's
`ExplainRevision` and `SupersessionHistory` *"two of the fourteen **sources**"* — they are two
**names** from one source.

**IR-F-9 · AMBER · Cross-deliverable contradiction on corroboration counts.**
`MINIMALITY-RESULT.md` §4.1: *"`Merge` and `Supersede` being the two best-corroborated names in the
entire corpus (**10** sources each)."* `OPERATION-REGISTRY-DERIVATION.md` §1.3 and
`OUT-inventory.txt` both give **13** each (I verified in the membership matrix). §4.1 also contains
*"56 of the 17 sources' mentions,"* which is malformed.

**IR-F-10 · AMBER · Steps 278, 279, 280 and 282 were never inventoried, and one supplies a candidate answer to a question reported as unanswered.**
These four steps are **later** than 277 and directly on-topic; none appears in the read list or the
17-source inventory. I checked them: they do not appear to add operation *names*, which is why
AC-1 is not failed. But:

- **Step 279 §11** states `PID = Hash(c)` with *"Changing any semantically relevant policy
  component MUST change the identity."* That is a candidate answer to the very binding the
  derivation raises in §3.3 as `NORMATIVE DECISION REQUIRED` — *"does an in-force policy version
  reference a content item in `K_t`, and if so is that item frozen while the version is in force?"*
- **Step 278:958** refers to *"an explicit historical replay operation"* — on-topic for A15.
- Steps 278/279 are titled *policy-authority-integration-and-executable-semantics* and
  *policy-and-authority-executable-implementation*, bearing on **A4** and on the claim that
  `ChangePolicy` has *"no signature, no authority guard and no runtime"* anywhere in the corpus.

The derivation's §3.3 point remains **technically intact** — the *ratified* text still states no
binding, and step 279 is research-lane material that cannot resolve it canonically. But under the
commission's own C-7 provenance discipline a candidate answer with a nameable source should have
been surfaced, and §1.6's closure hedge is about a possible *fifteenth name*, not about four
unread later steps.

**IR-F-11 · AMBER · `Split`'s status inheritance is attributed to the corpus but comes from the model.**
§3.2 V-1: *"`Split` as the corpus specifies it … produces **new items carrying the parent's
status**."* The three cited signatures (`Split: K × x → K'`; `Split(x) = {x₁,…,xₙ}`;
`Split: K ⇀ K₁ × K₂`) say **nothing** about status. The inheritance comes from `op_Split`'s
`replace(a, id=…, ev=frozenset(), grade=None)`, which copies `status` along with every other
unnamed field. Same class as IR-F-4. Partially mitigated: the derivation lists *"products enter at
`Candidate`"* among the three repairs, so it knows the rule is undetermined — but V-1's framing
asserts a corpus specification that does not exist, and R6's exclusion rests on it.

**IR-F-12 · AMBER · The A6 probe cannot reach the sharpest A6 hazard the deliverable names.**
`OPERATION-CONTRACTS.md` C-03 raises *"is replaying an authority act legitimate? Replaying a
recorded authorization re-confers authority without a fresh act — the corpus does not address this,
and it is the sharpest replay question in the registry."* That is a candidate A6 crossing. The
adversarial state (`consistency.py:119`) is constructed with **no authority act**, so there is no
authorization or commit in history to replay, and the probe that clears A6 for all 55 pool members
is structurally incapable of exhibiting the hazard. The question is a genuine contribution; the
"A6 HOLDS for all 55 pool members" result simply does not speak to it.

**IR-F-13 · AMBER · Capability-identifier collision between prose and code.**
The prose renames the Zero capability **`A6c`** specifically to avoid colliding with the ratified
axiom **A6**; `mintest.py:101` names it `"A6"`, and `OUT-mintest.txt` prints it as `A6`. In the
executed artifact the ratified axiom A6 and the derived capability A6 share an identifier, and a
reader mapping output to prose must know to translate.

**IR-F-14 · AMBER · The stated forcing criterion is not applied uniformly.**
§2's rule makes a capability mandatory if the ratified surface *"contains it as a node of the
canonical flow"* (criterion b). **A14 is a flow node** — v0.1:70, `AUTHORIZED ACTION ──► new
observation` — yet is graded `PROPOSED` on OQ-4 grounds. The override is defensible and errs
**conservative** (it under-claims rather than over-claims, which is the right direction under
AC-6), but it means the RATIFIED-FORCED boundary is set by judgment, not by the stated criterion.
The criterion should either carry the OQ-4 exception explicitly or not be stated as a biconditional.

**IR-F-15 · AMBER · A11's formulation imports unratified vocabulary.**
Art. 9.2 and FA-1:52 ratify `UNKNOWN ≠ ABSENT ≠ FALSE` as **epistemic/evidence-layer states**.
Neither speaks of "dimensions." A11 is phrased *"recognise a **dimension** while its value is
UNKNOWN, distinguishably from the dimension being ABSENT,"* importing ⟨D_t⟩ from
`02-OPERATION-UNIVERSE.md` — a research artifact, and one the derivation itself classes as a forced
*structure*, not an operation. The capability survives; the phrasing does not trace to the cited
anchor. Consistently, `RecognizeDimension` is graded a proposal under C-7 and excluded from
membership, so nothing is smuggled into the registry — the defect is in the capability statement.

**IR-F-16 · AMBER · A7's cited anchor is not what forces it.**
A7 (*"turn a source observation into evidence"*) is cited as forced by
`SourceObservation ≠ SemanticObservation` — a distinction between two kinds of *observation*, which
does not by itself entail an observation→evidence transition. A7 survives via criterion (b)
(v0.1:55's flow arrow to `EVIDENCE`) and via the gate-ratified `observation ≠ evidence` (v0.1:43),
neither of which is the anchor named in the table.

### GREEN

**IR-F-17 · GREEN · Full reproducibility.** All five scripts re-run to byte-identical output under
an independent Python 3.13.2 (only the wall-clock line differs). Rare and creditable.

**IR-F-18 · GREEN · The closure computation is genuine.** Real bounded BFS composing operations
(`mintest.py:160`, `186–232`, `233–263`); no law→operation table. The deliverable clears the bar it
sets for `oderive.py`.

**IR-F-19 · GREEN · The accusation against `oderive.py` is accurate and fairly stated.** Verified
line by line (§2.7). Correctly says the 14-element result is *untested*, not *wrong*, and correctly
aims at the citation rather than the file, which is itself more modest than its reputation.

**IR-F-20 · GREEN · The circularity/bijection finding is correct and important.** 272A's
`D_mandatory` register maps one-to-one onto its own operation list, so the corpus's stated necessity
criterion returns "all 19 necessary" by construction. Re-executed. This justifies deriving the
index set from the ratified surface and is the load-bearing methodological move of the commission.

**IR-F-21 · GREEN · All 13 RATIFIED-FORCED anchors trace to primary ratified/authorized text.**
Traced individually against v0.1, v0.2, FA-1 and the Constitution. None fails on "the theory needs
it." A13's separation of a ratified *capability* from a PROPOSED *formulation* is exemplary
discipline.

**IR-F-22 · GREEN · The invariant results verified.** I-5, I-6, I-9, I-2, Art. 7 and A6-for-the-pool
all check out against the ratified text and the executed output. The I-5/I-6 evidence handling
(grade as a *set* of source classes) satisfies both invariants **without** selecting an aggregation
operator, which genuinely leaves OQ-3 untouched — a careful piece of design.

**IR-F-23 · GREEN · The step 025a-2 §36 `Commit` result is correct and correctly scoped.** Read
verbatim: five conjuncts, no authority conjunct, `SufficientSupport` evidence-derived, self-labelled
*"experimental, not final."* A corpus-stated commitment rule that would violate the ratified A6 is a
material finding, and it is reported with its caveat and recommended for exclusion **with its
reason** rather than silently dropped. The strongest single result in the deliverable.

**IR-F-24 · GREEN · The failure-semantics blockage is real, not an artifact of framing.** The
ratified surface contains no typed-rejection vocabulary of any kind, and the three unanswered
questions are genuinely unanswered. The model-relative part (five kinds) is declared PROPOSED in
place and correctly distinguished from the canonical gap.

**IR-F-25 · GREEN · Multiplicity is reported, not resolved.** No registry is privileged anywhere in
the prose; R6's exclusion is on an executed consistency ground and is explicitly called a reduction
rather than a resolution; the ten prior decisions P-1…P-10 are named and left unmade; and the
intersection == necessary-set cross-check is a genuine self-consistency test that passes.

---

## 4 · WHAT I COULD NOT VERIFY, AND WHY

1. **The full extent of the incompleteness.** IR-F-26 rests on four items I verified from primary
   sources (Question 15's taxonomy, `unask`/ND-282-1/GD-08, the 282–284 ceiling, steps 260/261) plus
   a larger set reported to me by a commissioned independent sweep — roughly sixty names and twelve
   sources — which I did **not** individually verify. Every item I did check held exactly as
   reported, so I regard the remainder as credible but formally `UNVERIFIED`. **The FAIL verdict on
   AC-1 rests only on the four verified items, each of which is independently sufficient.**
2. **Whether the missed names change any computed result.** I did **not** re-run the minimality test
   over an enlarged pool. Doing so would mean constructing a better answer, which the commission
   forbids me. I therefore report only the **direction** of the effect (necessity overstated,
   multiplicity understated — by the derivation's own correctly-reasoned bias analysis) and not its
   magnitude. **Re-running Task 1 and then Task 3 over a corrected universe is the single
   highest-value follow-up I can identify, and it belongs to whoever holds authority to reopen
   Task 1 — not to this review.**
3. **Whether Question 15's taxonomy is genuinely superseded.** `A3W-state-transition-register.md:52`
   says it is. I did not trace the supersession to a governed act, and I make no determination:
   either way the 105-name grand union is incomplete, which is all my finding needs.
4. **GN-79/GN-80/GN-82/GN-83 verbatim commission text and acceptance standards C-1…C-9.** My
   research pass on `analysis/governance-notes.md` terminated on an infrastructure error. I
   therefore assessed the deliverable against the six acceptance gates **as given to me in this
   review's own instructions**, plus the ratified surface read directly. Where the derivation quotes
   the commission (C-5, C-7, AC-1…AC-6), I could not independently confirm the quotations. The
   nine-capability list **was** independently confirmed — `analysis/OPERATION-CONTRACT-GAP.md` §A,
   C-1…C-9, and the derivation's nine-to-fifteen crosswalk maps correctly in all nine rows.
5. **Whether a smaller minimal registry exists for `R_A` / `R_A_ratified`.** Establishing this would
   require enumerating A13's true witness family at sizes 5–6, which is a re-execution with a raised
   cap. I did not perform it: it would be constructing a better answer, which the commission
   forbids me. I report only that the question is **open by cap, not closed by evidence** (IR-F-3).
6. **`GC-1`.** Untouched by instruction. I confirmed the derivation's §3.3 ambiguity is genuinely
   *adjacent to and distinct from* GC-1, and I neither resolved nor moved it.

---

## 5 · RECOMMENDED TERMINAL VERDICT

### **D — THE OPERATION UNIVERSE DEPENDS ON AN UNRESOLVED PRIOR CANONICAL DECISION**

**I agree with the derivation's recommendation of D — but I reach it on a partly different and
weaker ground, and I record that the ground it states does not survive review.**

**Where I agree.** Membership genuinely is not determined until prior canonical decisions are taken.
That conclusion is robust to every finding above, and it is over-determined by several independent
routes I verified myself:

- **P-8/P-9/P-10 are real and independently confirmed.** State equality is absent from the entire
  corpus; the invariant register is nowhere asserted closed and a competing five-element register
  exists; the canon supplies no rejection vocabulary. Each blocks the contracts on its own.
- **P-1 survives even though the derivation's argument for it does not.** `Reject`'s guard is
  genuinely unspecified — step 256.11 offers "possible semantics," graded 🟡 — and its source status
  `Proposed` is not even a ratified status (IR-F-1). Whether `Reject` is an epistemic disposition or
  a governance act therefore really does determine whether the only realizer of the ratified A8 is
  consistent.
- **P-2 survives, and Art. 8.3 makes it sharper, not softer** (IR-F-5). The ratified text does not
  fix the return rung; a ratified constraint bearing on it was not consulted; and the answer changes
  the registry family by the deliverable's own §5.3.
- **P-3…P-7** — granularity, `LinkEvidence`'s typing, the CONFLICTED entry route, contradiction as
  fact vs relation, the DC conjunction — are each traceable to a real, documented,
  unreconciled disagreement in the record.
- **AC-1's failure adds an eleventh prior decision, and it is the most basic of them.** IR-F-26
  shows the candidate universe is not enumerated: an uncited 24-operation taxonomy with typed
  signatures, a live operation (`unask`) under an open normative decision `ND-282-1`/`GD-08`, a
  reading ceiling three steps short, and uncited minimality-bearing enumerations at steps 260/261.
  **P-11 — *what is the operation universe, and what act closes it?* — is prior to all ten of
  P-1…P-10.** This is the most literal possible instance of verdict D: the operation **universe**
  itself depends on an unresolved prior canonical decision. It strengthens D rather than weakening
  it, while simultaneously removing any reliance on the deliverable's computed values.
- **A structural argument the derivation does not make also lands on D.** Its own §2.4 establishes
  that the ratified surface quantifies over **none** of `Merge`, `Split`, `Supersede`, `Revise`,
  `Withdraw`, `Reintroduce`, `Remove`, `Derive`, `Trace`, `Query`, `Compare`, `Identity`, `Equal`,
  `Explain`, `Evaluate`, `LineageQuery`, `ProvenanceQuery` — the bulk of the corroborated corpus
  vocabulary. Any registry either excludes them (and must justify discarding the record's
  best-corroborated names) or includes them on a ground **outside** ratified entailment that no
  ratified act supplies. That is a prior canonical decision by itself, and it is independent of
  every RED finding above.

**Where I differ, and it matters for how D should be recorded.** The deliverable grounds D in a
claim of **inconsistency** — *"every one of the six minimal registries contains an operation whose
specification violates a ratified invariant."* That claim **does not survive** (IR-F-1): there is no
specification to violate, only a provisional "possible semantics" that the model encoded more
permissively than its source, and the derivation's own sentence contradicts itself on the point. The
correct ground is **underdetermination**, not inconsistency:

> The six computed registries are neither consistent nor inconsistent with the ratified surface,
> because the guard on the one operation all six must contain in order to satisfy a ratified
> capability **is not specified anywhere** — and the corpus's nearest candidate names a source
> status that is not a ratified status.

D still follows. But the record should carry the weaker, accurate ground, because the stronger one
would make a ratified-invariant *violation* part of the programme's findings on the strength of a
modelling choice — and the deliverable's own governing rule is that the strongest statement made
must never exceed the strength of the available evidence.

**Why not B.** B ("multiple minimal registries exist") is true as a computed result for
`R_A_strict` only, and even there it is conditional on `op_Resolve`'s undeclared return-rung choice
(IR-F-4) and on `op_Reject`'s permissive guard (IR-F-1). For `R_A` and `R_A_ratified` the
enumeration is not exhaustive (IR-F-3). And all three variants range over an **incomplete universe**
(IR-F-26). B would report as a finding a number that three modelling choices, one cap, and an
unclosed universe can each move.

**Why not C.** C ("no minimal registry can yet be established") is true as a status and is fully
supported, but it discards the deliverable's genuine contribution: the test **ran**, it **produced
a well-defined family**, and it **identified which prior decisions move membership**. D carries that
information; C loses it. If the HPA prefers the more conservative record, **C is defensible and I
would not object** — but it under-reports what was achieved.

**Why not A.** Ruled out on every variant. No unique minimal registry was found, and the method's
bounds bias *toward* A — I verified that bias claim is correct — so finding multiplicity against
that bias makes A the least supportable option of the four.

### Recommendation on disposition

The deliverable is, in my assessment, **methodologically strong work resting on an incomplete
inventory, which over-states several of its own results**. Two things should be recorded separately,
because they point in opposite directions and neither should absorb the other.

**What is genuinely good, and should survive.** It reproduces byte-identically — rare and creditable.
Its closure computation is real BFS, not a lookup table, and it clears the bar it sets for
`oderive.py`. All thirteen of its ratified-forcing claims trace to real ratified anchors, which is
the direct answer to the HPA's AC-6 watch item. Its two most valuable findings — the `D_mandatory`
bijection (which justifies deriving the requirement set at all) and the step 025a-2 §36 `Commit`/A6
crossing — are correct, correctly scoped, and worth carrying forward on their own. Its refusal to
select a registry is exactly right and is honoured throughout.

**What does not survive.** Five characterisation defects — a permissive encoding described as a
corpus specification (IR-F-1, IR-F-11), a modelling choice described as a discovery about the
ratified model (IR-F-4), an exhaustiveness claim broader than the execution supports (IR-F-3), a
no-op operation credited with a semantics it does not implement (IR-F-2), a ratified sub-clause never
read (IR-F-5) — and, decisively, an **incomplete candidate universe** (IR-F-26) that AC-1 required be
tested and that fails the test.

**Recommendation.**

1. **Record the terminal verdict D**, on the **underdetermination** ground stated above rather than
   the inconsistency ground the deliverable states, and with **P-11 (what is the operation universe,
   and what act closes it?)** recorded as prior to P-1…P-10.
2. **Do not rely on the computed values.** The six registries, the twelve-member core and the
   nine-member band should be recorded as *"computed over a universe since shown incomplete"*, not
   as results. Their direction of error is known — necessity overstated, multiplicity understated.
3. **Do not reject the deliverable.** Its method, its reproducibility, its entailment discipline and
   its two substantive findings are worth keeping. The failure is at Task 1, and Task 1 is
   re-runnable without discarding Tasks 2, 3 or 8.
4. **The natural next act is a reopened Task 1** — a corrected inventory that reads to step 284,
   makes an explicit determination on Question 15's supersession, and records `unask` — followed by
   re-execution of the existing, unmodified scripts over the corrected pool. That is an authority
   decision, and I neither make it nor pre-empt its outcome.

Nothing in this review is `RATIFIED`, nothing here selects, repairs or completes a registry, and the
operation registry remains **NOT ESTABLISHED**.

---

**Reviewer:** independent falsification lane, GN-82/GN-83, 2026-08-31.
**Read-only compliance:** no file under `model/`, `final-architecture/`, `analysis/`,
`brainstorming/`, `book/`, `book-edition-2/` or `book-architecture/` was modified. Scripts were
copied to a scratch directory to re-execute; the originals in `exec/` were not altered.
**This file is the only artifact this review produced.**

---
artifact: TV-F-071 … TV-F-078
track: A (verification)
phase: 2C
covers: raw-titled Steps 158–185 (28 files)
date: 2026-08-30
status: DELIVERED
authority: verifier session (adversarial, independent)
source: spec/STEP-VERIFY-159-185.md
discipline: SOURCE RESULT / VERIFIER OBSERVATION / POSSIBLE REPAIR kept separate. NO SILENT REPAIR.
---

# Findings TV-F-071 … TV-F-078

## TV-F-071 — Step 180 forbids "Confirmed" and then declares five Confirmed bounded contexts one section later

**Class:** unlicensed epistemic promotion, self-contradicted within one file · **Severity:** CRITICAL ·
`[SUPERVISOR-VERIFIED]`

**SOURCE RESULT, verbatim, in file order (`# Step 180`, lines 528–553):**

> §180.15: *"…not:* `ConfirmedBoundary.` *This protects the architecture from premature crystallization."*
>
> §180.16 — **the very next section** — *"After analysis:* `5 Confirmed BCs.` *That transition is meaningful
> precisely because we did **not** treat every initial hypothesis as truth."*

**VERIFIER OBSERVATION.** This is sharper than the band agent reported. The agent placed the defect as
"committed in the file whose subject is `Candidate ≠ Confirmed`". First-hand reading shows it is committed
**one section after the file explicitly boxes the prohibition**, and the sentence committing it invokes the
very discipline it breaks as its justification.

**No predecessor licenses the promotion.** Step 173 §173.24 and Step 174 §174.37 both explicitly *withhold*
bounded-context confirmation. No file between 174 and 180 supplies the analysis §180.16 refers to; "After
analysis" names an act that does not appear in the corpus. The batch's central strategic-DDD question —
*which bounded contexts are confirmed?* — is therefore still open at Step 185, while Step 180 records it as
answered.

**POSSIBLE REPAIR — NOT ESTABLISHED BY CORPUS.** Retract §180.16's "5 Confirmed BCs", or supply the analysis
and the confirmation criterion it presupposes. The corpus does neither.

---

## TV-F-072 — `EpistemicDebt` is monotone increasing in Recoverability, inverting its own prose

**Class:** directional error in a proposed metric · **Severity:** high · `[SUPERVISOR-VERIFIED]`

**SOURCE RESULT (Step 184 §184.23, verbatim):**

```
EpistemicDebt = Importance × Recoverability × Missingness
```

**VERIFIER OBSERVATION.** The formula is **strictly increasing in `Recoverability`**: holding `Importance`
and `Missingness` fixed, more recoverable information yields *higher* debt. That is the opposite of the
intended meaning. Debt is the cost of information you cannot easily get back; information that is *readily
recoverable* imposes *less* debt, not more. The prose surrounding §184.23 treats debt as a burden to be
reduced; the formula rewards recoverability with a larger burden.

**Second, independent defect — measurement theory.** The expression multiplies three quantities for which
**no scale type is established anywhere**. Multiplication is admissible only on a ratio scale (a true zero
and meaningful ratios). None of `Importance`, `Recoverability` or `Missingness` is given a unit, a range, a
zero point, or an estimation procedure. This is the same defect class as step-206 §206.16's weighted-sum
boundary score and step-124 §124.32's `ActionRisk = Impact × Irreversibility × Scope` — **three products
over unscaled quantities, in three separate bands, none noticing the others.**

**POSSIBLE REPAIR — NOT ESTABLISHED BY CORPUS.** Replace `Recoverability` with `Irrecoverability`
(or `1 − Recoverability` on a normalised ratio scale), and establish scale types for all three factors before
multiplying. The corpus does neither.

---

## TV-F-073 — Fifteen invariant registries minted in 28 files, zero crosswalks, one back-reference — and it is fictional

**Class:** registry proliferation without canonical discovery · **Severity:** CRITICAL · `[BAND-REPORTED]`

Fifteen distinct ID registries are minted across steps 158–185: `AA-001` · `KNOWN-UNKNOWN-001…005` ·
`I_1…I_10` (161) · `I-01…I-20` (162) · an un-IDed 21st (162.46) · `P1…P10` (163) · aggregate sets `I_i`,`I_AB`
(165) · `C_1…C_5` (167) · `L_1…L_8` (168) · `Law A…H` + meta-law (169) · `I_1…I_5` (171) · an un-IDed ninth
law (171.30) · `CP_1…CP_6` (172) · four structural invariants (182.32) · `I_15…I_20` (185).

**Zero crosswalks exist. There is exactly one back-reference across 28 files — 164.17's "invariant I-07" —
and it occurs inside a hypothetical utterance, i.e. it is fictional.**

**The collisions are content-level, not merely notational:**

| Collision | Detail |
|---|---|
| **A** | 161's `I_1…I_5` × 171's `I_1…I_5` — **five-for-five**, both framed constitutionally, ten files apart. `I_1` is *"Observation ≠ Interpretation"* in one and *"Evidence must have provenance"* in the other |
| **B** | 162's `I-15…I-20` × 185's `I_15…I_20` — **six-for-six** |
| **C** | 161→162 silent renumbering across ten mappings, with one **loss**: `I_2` (*Evidence ≠ Knowledge*) — the batch's most-cited invariant — **loses its ID entirely**, surviving only as §162.9 prose |
| **D** | All in-scope `I_n` registries sit inside step-048's `I1…I20` range and overlap step-055's `I1…I15`. 162's `I-01…I-20` is step-048's range with a hyphen |
| **E** | Step 185 presupposes `I_11…I_14`, **which exist nowhere in scope**; the only ones in the corpus belong to steps 048 and 055, neither cited |
| **F** | 168's `L_1…L_8` → 169's `Law A…H` with **no crosswalk**; `L6` (*Aggregate ≠ Process*) **survives falsification at §169.11 and is then dropped from the final list** |

**Canonical Discovery was never performed.** Machine-verified absent from all 28 files: step-004's `E-K1…E-K10`,
step-051's `INV-1…INV-20`, step-048's `I1…I20`, step-120's `C1…C7`, step-145's `GT/GG/GE/GC/GA`, and the
8-primitive kernel `𝒫`. **Every registry in this band is a second copy of something that already existed.**

---

## TV-F-074 — Eleven incompatible status vocabularies, and the terminal state collides with a domain object

**Class:** ubiquitous-language failure · **Severity:** high · `[BAND-REPORTED]`

Eleven mutually incompatible Knowledge/epistemic status enumerations appear across 28 files (161.11, 163.8,
164.10, 167.49, 168.2, 170.10, 173.15, 176.27, 179.38, 180.4, 183.13/185.19), **none reconciled, every one
self-labelled "not yet frozen".**

The terminal high-support state is called **`CONFIRMED`** (161, 163, 164), **`Established`** (170, 173, 176,
180), and **`Determined`** (179, 183, 185).

**The third is the critical one.** *"Determined"* is the batch's reserved word for the separate domain object
**`Determination`** (`D`, owned by a distinct bounded context per §163.15 and §173.5). Steps 183.13 and 185.19
therefore make a **Knowledge *status* homonymous with a different *aggregate*** — in the very corpus that
declares `Determination ≠ Decision` and `Evidence ≠ Knowledge` as constitutional invariants. **This is the
single clearest ubiquitous-language defect in the band.**

Three incompatible evidence scales coexist likewise: `E0–E4` grades (159.24), `E1–E5` classes (160.3),
`Levels 0–5` (168.26). **`E2` means "direct observation" in 159 and "Repository" in 160.** None cites another.

---

## TV-F-075 — Six definitions of `K`, eight meanings of `I`, and arity drift on all three headline functions

**Class:** operator and tuple drift · **Severity:** high · `[BAND-REPORTED]`

**`Knowledge K` — six incompatible definitions, none citing its predecessor:** 161.10 `(claim, source,
context, validity, status)` · 161.55 `(claim, evidence, authority, validity, status)` · 165.5 `(id, claim,
status, validity, authority, version)` · 170.7 `⟨claim, basis, scope, validity, version⟩` · 176.9 six-field
retrieval record · **177.15 `K = ⟨C,E,M,S,T,V,P⟩`** (the batch's headline tuple). **Only `validity` and
`status` survive all six.**

Also drifting: `Determination` ×4 · `Authorization` ×5 · `Evidence` ×4 · `Observation` ×3 · `Decision` ×3 ·
`Proposition` ×3 · `Verification` ×3. **`Execution X` is the batch's only stable tuple.**

**Symbol overloading (worst in the corpus):** `I` carries **eight** meanings (Inquiry · four separate invariant
registries · mutual information `I(S;H)` · information set `I_t`); `A` carries **eight**; `P` and `E` carry
**seven** each; `G` **six**; `V` **six**; `H` **four** — *and `H(H)` uses two of them in one expression*.

**The headline tuple is built from three of the most overloaded glyphs in the corpus:** `K = ⟨C,E,M,S,T,V,P⟩`
uses `C`, `E`, `V` and `P`, each already bound five to eight ways.

**Arity drift on all three headline functions:**
- `Determination`: 4-ary `f(K,E,C,M)` → 4-ary `f(K,Policy,Method,Context)` (**`E` replaced by Policy**) →
  `E(Evidence,Method,Context,TemporalState)` (**`K` dropped, function renamed `E`, shadowing its own argument**)
- `Decision`: 4-ary → **boxed** 3-ary `δ(K_t,Policy_t,Authority_t)` → 7-ary → **boxed** 7-ary `G(…)`, uncited
- `Authority`: 6-ary → 5-ary → 4-ary → **boxed** 7-ary → **boxed 5-ary `f(Domain,Proposition,Context,Scope,Time)`
  WITH NO ACTOR ARGUMENT** (182.35), contradicting §182.27 in the same file and step-179's entire thesis

---

## TV-F-076 — A genuine mathematical error at 167.32, correctly repaired eight steps later without anyone noticing

**Class:** invalid information-theoretic inference · **Severity:** high · `[BAND-REPORTED]`

Step 167 §167.32 asserts `I(S;H) < H(H)` — that the mutual information between state and history is strictly
less than the entropy of history, i.e. that projecting history to state necessarily loses information.

**This is false when the projection `π` is injective.** If `S = π(H)` with `π` injective, then `S` determines
`H`, so `I(S;H) = H(H)` exactly, and the strict inequality fails. The claim requires non-injectivity as a
premise, which §167.32 does not state. The file's own next sentence contradicts the inequality.

Compounding it, **the expression uses `H` for both *history* and *entropy* simultaneously** — `H(H)` is the
entropy of the history variable, written with one glyph carrying two of its four bindings.

**The repair exists in the corpus and is uncredited.** Step 175 §175.3 supplies exactly the non-injectivity
argument that would make §167.32 valid — eight steps later, with no citation, no correction notice, and no
indication that the author noticed the earlier statement was unsound. Under the first-invalid-inference rule,
everything downstream of §167.32 that relies on strict information loss remains conditional on that repair.

---

## TV-F-077 — Four genuine falsification passes exist, and the corpus's own expectation about which ones was wrong

**Class:** positive finding, with a correction · **Severity:** informational · `[BAND-REPORTED]`

Against ~1,600 self-confirming experiments verified so far, **this band contains the corpus's only real
adversarial testing** — four passes producing genuine counterexamples and genuine rejections:

- **Step 169** — attacks five of eight laws plus the whole architecture; **`L6` survives the attack and is
  then silently dropped from the final list** (TV-F-073, Collision F).
- **Step 175** — `100→80` / `120→80` identifiability counterexample. **Fully specified, self-contained, and
  independently checkable: the only REPRODUCIBLE_WITNESS in the entire corpus verified to date.**
- **Step 178** — `K_A = K_B` with `Objective_A ≠ Objective_B` ⟹ `Decision_A ≠ Decision_B`, refuting
  `Decision = f(Knowledge)`.
- **Step 181** — refutes both latest-wins and confidence-wins conflict resolution with counterexamples, and
  rejects an architectural primitive on that basis.

**SUPERVISORY NOTE — the band agent corrected its own brief, and the correction is sound.** Step 172 was
expected to be a genuine falsification file. It is not: all six scenarios pass, none defeats the model,
Experiment E is repaired by re-description rather than revision, and the outcome is three refinements rather
than a refutation. Step 182 is a confirmation exercise. **Revised genuine-falsification set: 169, 175, 178, 181.**

**Also recorded — a self-defeating pattern the batch's own meta-law forbids.** Four times a formalism is
imported, immediately disclaimed, and does no inferential work: `argmax` utility (178.23), expected
information gain (180.7), `P(D|X̂)` concentration (183.18), and — the exception that *is* used — likelihood
ratios (181.17). Step 169 §169.29 boxes `Use the weakest mechanism that provides the required assurance`,
and the batch never applies it to itself.

---

## TV-F-078 — Step 158's reality test: specified, commissioned, never performed

**Class:** the corpus's own audit, unrun · **Severity:** CRITICAL · `[BAND-REPORTED]`, consistent with `[SUPERVISOR-VERIFIED]` TV-F-056

Step 158 opens the band by commissioning a reality test against the actual system: five artifacts, six gates,
ten dimensions. **None is produced, evaluated, or populated. Zero repository inspection occurs.** The file
self-labels its output *"audit hypotheses, not findings yet."* §158.54 states an evidence rule and the
following 27 files never enforce it.

**Execution evidence across the 28 files: 0 shell invocations · 0 repository paths · 0 executable blocks ·
1 mathematical reproducible witness (175).** Step 185 is the sharpest instance: it **designs an executable
test** (§185.21, five cases with an explicit pass criterion), states a maturity gate at §185.22, **does not
run it**, and ships six constitutional invariants (`I_15…I_20`) anyway at §185.24 — presented as the *output*
of the experiment that was never executed, with the pass criterion neither evaluated nor waived.

**The filename-slug collision is resolved and is not a defect of substance.** Two files carry "step-158" in
their names; the timestamped one self-declares as *pre*-158 preparation and the raw-titled one self-declares
as Step 158. Internal numbering governs and is unambiguous. The defect lies in the `-preparation` naming
convention, not in the corpus's step sequence.

---

# What survives from this band

The band is the corpus's densest and, in places, its best material. These results survive verification:

- **168.57 — multi-dimensional state** `State = ⟨Operational, Epistemic, Governance, Authorization⟩`, with the
  proof-sketch that collapsing to a single `status` field is a many-to-one projection destroying assurance
  information. **The most implementable finding in the band.**
- **175 — identifiability** (`100→80` / `120→80`). The only mathematically airtight derivation in the band,
  and the only reproducible witness in the corpus.
- **184.30 — three protected transitions** as state-machine guards: `Inference ≠ Fact`, `Confidence ≠ Truth`,
  `Authorization ≠ Evidence`. **The most directly implementable AI-safety result in the corpus.**
- **185 — bitemporality** `T_valid × T_known`. Standard, correct, and buildable today.
- **166.36 — `AgentSession ≠ BusinessProcess`**, with the multi-session example. Directly consequential for
  any AI engineering platform.
- **182.11 — question decomposition**: one question ("Is port 8081 okay?") resolves into six propositions and
  potentially six distinct authorities. The clearest demonstration in the corpus of why lexical retrieval fails.
- **182.33 — type-system scoping**: *"KnowledgeOS enforces the integrity of governance artifacts; Domain
  governance defines what those artifacts mean."*
- **173.12–.13** — treating the platform's own name ("KnowledgeOS") as an architectural risk to the very
  distinctions the architecture depends on. Unusually self-aware.
- **169, 178, 181** — genuine falsification with real counterexamples (TV-F-077).

**Where the band fails as DDD:** the ubiquitous language is asserted and never stabilised (eleven status
vocabularies, fifteen registries, "Determined" naming two different things); **no bounded context is ever
legitimately confirmed** (173.24 and 174.37 defer; 180.16 asserts without licence — TV-F-071); Canonical
Discovery is never performed (TV-F-073); aggregates remain self-labelled hypotheses at 165.16 and are never
revised; and **not one artifact of the real system is inspected**, in a 28-file sequence whose opening act
commissions a reality test against that system.

**Overall: a coherent, largely well-reasoned, entirely unexecuted architectural monologue. Its worst defects
are not reasoning errors but bookkeeping failures** — fifteen registries with no crosswalks, six definitions
of `K`, eight meanings of `I`, and one unlicensed Candidate→Confirmed promotion committed one section after
the prohibition that forbids it.

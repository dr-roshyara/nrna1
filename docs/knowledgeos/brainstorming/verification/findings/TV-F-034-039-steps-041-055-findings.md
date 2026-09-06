# TV-F-034 … TV-F-039 — Findings from deep verification of Steps 041–055 (Phase 2C)

**VERIFY SESSION · 2026-08-30.** Source: 17 files (15 unique; step-046 and step-052 duplicate pairs md5-confirmed), read in full. This band contains **the most severe defects found anywhere in the corpus to date** — for the first time the failures are not canonicalisation defects but **unsupported claims of correctness**.

---

## TV-F-034 — **Step-048 issues PASS on proof obligations it states and never discharges, over a state set it never characterises**

**What step-048 correctly sets up.** §48.32: `𝓘 = {I₁,…,I₂₀}` and `∀s ∈ ReachableStates: ⋀_{i=1}^{20} Iᵢ(s)`. §48.35 states the preservation obligation `I(s) ∧ ValidTransition(s,s') ⇒ I(s')`; §48.36 states the base case `I(S₀)=True` (*"Otherwise induction cannot begin"*); §48.37 states the conditional *"The architecture is invariant-preserving **if**: Initialization … Preservation … Then `∀S ∈ Reach: I(S)`."* §48.33 is explicit that this is not enough: *"20 invariants do not automatically prove correctness … `Specification ≠ Proof`."*

**What is missing.**
- **No base case is exhibited for a single `Iᵢ`.** **No preservation argument is given for a single `Iᵢ`.** `ValidTransition` / `T` is never enumerated anywhere in the band.
- **`ReachableStates` is never characterised.** §48.6 introduces `Reach(KOS,S₀)` without a generating relation; §48.68–72 propose a sound abstraction `α` with `Violation_Concrete ⇒ Violation_Abstract` but **no `α` is exhibited and no abstract domain is named**. In step-051 the only states ever shown are a ten-element **linear trace** — one path, not `Reach`.
- **§48.34's completeness question is posed and never answered:** `DesiredProperties ⊆ Consequences(𝓘)?` — `DesiredProperties` is never enumerated and `Consequences(·)` is given no deduction system, so completeness is not even well-posed.

**First invalid inference: §48.37 → §48.89.** The verdict `STEP 48 — PASS` is drawn while the induction is an open conditional and the completeness question is unanswered. §48.89's attempted rescue (*"this PASS … does not mean `ImplementationCorrect`"*) **redefines PASS post hoc rather than withdrawing it**. Compounding: §48.3's compositional schema `Correct(C₁) ∧ Correct(C₂) ∧ Contract(C₁,C₂) ⇒ Correct(C₁∘C₂)` is stated as something *"we want"*, is never proven, and is nevertheless relied on from §48.38 onward to treat bounded contexts as *"proof boundaries"*.

**Credit where due:** §48.76 is exactly right — *"We should **not** conclude: 'KnowledgeOS has now been mathematically proven correct.' … `We now have a candidate formal specification against which correctness can be demonstrated.`"* That sentence is the correct claim; the file's own PASS contradicts it. **Severity: MAJOR. Register sync: AC C-078.**

---

## TV-F-035 — **The invariant set is silently substituted between steps 048 → 051 → 055; the corpus's most-emphasised invariant is dropped and never tested**

Three families specify the same architecture, sharing no numbering and with no mapping between any two:

| Family | Size | Source |
|---|---|---|
| `𝓘` = I1–I20 | 20 | 048 §48.12–31 (frozen, with the ⋀-formula) |
| INV-1…INV-20 | 20 | 051 §51.19–38 |
| I1–I15 | 15 | 055 §55.33–34 |

**Step-051's substitution, itemised.** Fifteen entries correspond to 048 but are **renumbered from #4 onward**. **Five are dropped without notice:** I4 Identity integrity · **I7 Decision integrity (`Execute(d) ⇒ Admissible(d)`)** · I9 Safety integrity (`SafetyGate=False ⇒ ¬Execute(d)`) · I17 Independent validation · I20 Causal-model integrity. **Five are added without notice:** Model invalidation · Knowledge demotion · Computational uncertainty · Action-outcome linkage · Governance compliance. **Cardinality is preserved at 20, which masks a 25 % substitution of content** — and step-050's closing text and step-051 §51.18 both promise to test `I₁,…,I₂₀`.

**The consequential loss.** §48.18 calls `Execute(d) ⇒ Admissible(d)` *"one of the most important system invariants"* — it is the invariant binding execution to the full admissibility conjunction of step-042 (`Pre ∧ Invariant ∧ Assurance ∧ Authorization`). Step-051 replaces it with INV-6 (**authorization only**), which is strictly weaker. **It is absent from step-055's family too. The reference machine therefore does not test admissibility at all, and no document says so.**

**Severity: MAJOR.** Any downstream statement of the form "the invariants were tested against the reference machine" is false for five of the twenty, including the one the corpus itself ranks highest. **Register sync: AC C-079.**

---

## TV-F-036 — **Step-050's `Counterexample(ℳ₄₉) = ∅` is not legitimate as scoped, and one of its 50 attacks is a genuine counterexample recorded as PASS**

**Verified attack count: 50** (§50.4–§50.53, contiguous). **Fourteen carry conditional passes**, verbatim, e.g. Attack 12 *"PASS, provided policy precedence is explicitly modeled"*; Attack 18 *"PASS. But this requires source-dependency metadata to be implemented"*; Attack 30 *"PASS, assuming boundary enforcement"*; Attack 46 *"PASS at the model level"*; Attack 50 *"PASS, but this reveals an important global implementation requirement"*.

**Three independent grounds on which the aggregation fails:**

1. **Scope substitution.** The fourteen passes are conditional on properties `ℳ₄₉` **does not contain** — PolicyPrecedence, source-dependency metadata, boundary enforcement, idempotency, event ordering/versioning, model-artifact binding, acyclic derivation, typed relation registry, sampling-context preservation, policy/authority binding, concurrency control. §50.55 lists them as *"Newly identified mandatory contracts"* and then reclassifies them as *"not new conceptual primitives… constraints on implementation"* — and that reclassification is what licenses the `∅`. **The model actually evaluated is `ℳ₄₉ ∪ {14 contracts}`, not `ℳ₄₉`.**
2. **Non-independence.** §50.59 conditions the confidence gain on *"independent, meaningful tests"*. The 50 attacks are authored by the same agent that authored `ℳ₄₉`, in one session, against one mental model — **precisely the common-mode failure step-047 §47.15 defines and forbids**. Step-050 commits the error step-047 names.
3. **Non-execution.** No attack instantiates a state or runs a transition; each is a two-to-six-line prose argument. `Counterexample=∅` is a statement about what the author did not think of.

**Attack 50 is a real counterexample.** Two locally valid concurrent decisions jointly violating an invariant is a direct counterexample to the compositional schema of §48.3 / §53.72. It is recorded as PASS and the schema is boxed as a result 78 sections later without addressing it.

**Credit where due — and it is substantial:** §50.57–59 are the single most disciplined epistemic passage in the corpus. Verbatim: *"the correct scientific statement is: `No counterexample was found in the tested scenarios.` Not: `No counterexample exists.`"* and the refusal to numericise `P(ModelCorrect|Tests)` *"without a justified prior and likelihood model"*. **Those hedges bound the inductive strength of the result; they do not repair the scope error, which is orthogonal to sample size.** **Severity: MAJOR. Register sync: AC C-080.**

---

## TV-F-037 — **Step-049's kernel minimality is stipulated, and the file supplies its own counter-evidence**

`𝒫 = {Entity, State, Event, Observation, Proposition, Relation, Policy, Action}` (§49.75), introduced with *"**I would currently freeze**"* and *"**candidate** mathematical kernel"*.

- **No independence proof.** No demonstration that any `p ∈ 𝒫` is underivable from `𝒫∖{p}`.
- **The file refutes its own minimality.** §49.3, verbatim: *"Therefore 'Entity' is **arguably a semantic abstraction over** `Identity + State`."* Entity is then retained on an explicitly **non-mathematical** ground (*"keeping Entity as a domain primitive is useful because DDD requires identity-bearing domain objects"*). §49.77 supplies the governing test — *"Is X a new primitive, or can X be derived from the kernel? If derivable: X ∉ 𝒫"* — **which its own §49.3 fails.**
- **No expressive-completeness argument.** The twelve "falsification experiments" (§49.78–89) are rhetorical necessity questions (*"Can Evidence exist without Observation? No. Therefore Evidence is derived. PASS."*). Necessity of a dependency is not a reduction.
- **The two headline reductions are ill-typed.** `L: K_t → K_{t+1}` and `D: (K,S,Π) → A` are typed over **`K`, which is not in `𝒫` and is never derived from it**; §49.32 then supplies a fourth tuple `K=(V,E,R,Metadata)` in which `E` denotes **edges**, nine sections after `𝒦=(E,S,T,O,P,R,Π,A)` used `E` for **entities**. `Context` and `ID` in `Evidence ⊆ O×Context` and `Identity: E→ID` are likewise not kernel members. **`Structure(·)` — the operator on which *"everything else must be expressible as `Structure(𝒫)`"* rests — is never defined.**
- **`𝒦` and `𝒫` are not the same eight objects** (`𝒦` lists `T` = "temporal/event structure"; `𝒫` lists `Event`).

**Verdict: minimality STIPULATED; expressive completeness NOT ARGUED; two reductions ILL-TYPED.** This directly governs `MATHEMATICAL-KERNEL-REVERIFICATION.md`: the corpus's most-reused kernel artifact has no minimality result behind it. **Severity: MAJOR. Register sync: AC C-081.**

---

## TV-F-038 — Step-046 is a 645-byte stub credited by step-047 with an establishment

The file (duplicated byte-identically at 103922 and 114426) contains a title, two boxed questions, and seven bare undefined symbols (`LearningStability`, `FeedbackAmplification`, `SelfCorrection`, `EpistemicDrift`, `ModelCollapse`, `FeedbackLoops`, `Human/External Anchors`). **It is a verbatim copy of step-045 §45.87's forward pointer**, saved twice under a step number. No formal object, no test, no verdict.

Step-047 §13 nonetheless states that *"Step 46 established the problem of learning stability"* — **attributing an establishment to a file that establishes nothing.** Consequence: any claim of the form "Steps 1–55 established …" (e.g. step-055's `Steps 1-55 → KOS_ref`) is **vacuous at index 46**. **Severity: MINOR mathematically, MAJOR for traceability.** **Register sync: AC C-082.**

---

## TV-F-039 — Step-053's compositional theorem is boxed with two undefined operators and a standing counterexample

§53.72, boxed: `LocalInvariant + ContractInvariant ⇒ GlobalInvariant`, with `KOS = ⊕ᵢ Bᵢ`.

- **`⊕` is never defined** — the composition operator on which the claim rests.
- **`Preserve(T_ij, I_required)` is never defined** — and it is the *same undefined predicate* that already appeared unrepaired at step-048 §48.40.
- **`RequiredSemantics(·)`** (§53.16), on which the backward-compatibility rule depends, is likewise undefined.
- **Attack 50 stands against it**, unaddressed, 78 sections earlier in the same corpus.

The file is explicit that it is picking up step-048's thread (*"This connects Step 53 directly back to Step 48"*) — and like step-048 it states the schema and does not prove it, while the boxed typography reads as a result. **First invalid inference: §53.72.**

**Credit where due:** step-053 is otherwise among the strongest DDD files in the corpus — `BoundedContext ≠ Microservice`, producer-owns-meaning / consumer-owns-interpretation, `Unknown ≠ Denied` in authorization results, and an explicit refusal of "architecture astronautics" are all correct and unusually disciplined. **Severity: MAJOR for the composition claim only. Register sync: AC C-083.**

---

## Verifier observations (recorded, not findings)

1. **`KOSCorrect` is restated with different conjuncts one step later.** 048 §48.75: `I_global ∧ Contracts ∧ Safety ∧ Liveness ∧ Traceability ∧ EpistemicIntegrity`. 049 opening: `Safety ∧ Liveness ∧ EpistemicIntegrity ∧ Traceability ∧ GovernanceCompliance` — **dropping the two conjuncts step-048 spent ninety sections constructing**, adding a new one, and presenting it as a quotation. Step-048 also defines `Correctness` **twice internally and incompatibly** (§48.53 six-element set vs §48.74 five-fold intersection); §48.75 matches neither.
2. **`DC(d)` has three signatures** (042 §42.2 six-tuple, §42.40 seven-tuple, 044 §44.55 six-tuple with different members) plus a fourth relabelled `Contract` at 054 §54.38. `X_t` has two (050 six-tuple, 051 eleven-tuple). `KOS_ref` has two (051 five-tuple, 055 six-tuple). No supersession statements.
3. **Step-042 §42.20 rules that violable properties are not invariants** and mandates renaming to `PolicyConstraint`/`AdvisoryCondition` — **the correction is never propagated**, and 048's taxonomy continues to carry governance invariants its own rule reclassifies.
4. **The `→` arrow denotes implication, causation, state transition, function mapping, derivation and pipeline stage interchangeably**, often within one section — the exact conflation steps 043 §43.7 and 049 §49.20 forbid.
5. **Step-055 §55.68 is the corpus's only explicit specified-vs-executed guard**, verbatim: *"we should not yet claim that the reference implementation has passed … we have specified it. We have not yet executed it. That distinction is scientifically essential."* It is introduced as a departure (*"unlike earlier steps"*) — **conceding that the fourteen preceding PASS verdicts were issued without it — and none of them is revisited.**
6. **Genuinely strong content in this band, for the survivor register:** step-045's `Evidence immutable / interpretations may evolve` (§45.42) and its consequence `Immutable evidence + Versioned interpretation + Governed learning = Safe knowledge evolution`; step-047's `Stability ≠ Validity` and `KnowledgeOS must not be the sole authority for validating its own critical knowledge`; step-049's computability ladder (`Undecidable ≠ Intractable ≠ InsufficientEvidence`, graceful degradation, *"When no meaningful bound exists, it should not invent one"*); step-052's boundary test (*"What does this context have authority to change? If the answer is: Everything, the boundary is wrong"*); step-054's typed-identifier discipline and `InferenceAdapter` demotion of the LLM.
7. **Execution across all 17 files: zero.** ~178 test-level PASS tokens, 14 step-level PASS verdicts, no interpreter output, no artifact, no hash. Step-046 has no verdict at all.

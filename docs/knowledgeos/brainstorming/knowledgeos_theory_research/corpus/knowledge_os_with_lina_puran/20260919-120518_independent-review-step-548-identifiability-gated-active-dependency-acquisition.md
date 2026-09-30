# Independent Review — Step 548 (Identifiability-Gated Active Dependency Acquisition)

## Reviewer stance

Mathematician · Statistician · DDD architect · Computer-logic / ML specialist.

Two documents in this turn: R548 (short) and Step 548 (long). They agree on substance; the long document is the working draft of the short one. I review them as a single unit. No external research needed; if a gap appears I name the book.

I will do seven things:

1. **Verdict** on Step 548.
2. **What is actually established** — theorem, experiment, architecture.
3. **Where Step 548 is imprecise or wrong** — seven items with corrections.
4. **Full term definitions**, extended to new objects.
5. **Worked examples** proving or refuting the central claims.
6. **ML positioning** and what Step 549 must do.
7. **Optimized architecture** and short bullet status.

---

# Part I — Verdict

Step 548 is a **substantive advance**, and it makes four correct architectural moves:

$$\boxed{\text{No-information impossibility is a theorem, not an ML limitation.}}$$
$$\boxed{InformationGain \neq DeterminationGain \neq DecisionValue.}$$
$$\boxed{W7 \text{ must split into } W7\text{-}N \text{ and } W7\text{-}W.}$$
$$\boxed{Acquisition \neq ML.}$$

The first is the strongest: it is an information-theoretic impossibility result, and it correctly restricts what any ML system (or any other method) can do. That is a permanent architectural constraint on KnowledgeOS.

The third is also important: R604.9 already hinted that W7 was a family, not a single world; Step 548 correctly separates the no-information world from the weak-signal world. This is the right refinement.

**Seven residual issues**, each requiring correction before Step 549:

1. The no-information theorem is stated for discrete $Z$ but not generalized. Its scope needs to be explicit.
2. `IAR = I(O;Z)/H(Z)` is proposed as a metric, but its interpretation table is not justified — the boundaries "near 0" and "near 1" are not quantified.
3. The synthetic ML numbers (AUC ≈ 0.786, Precision 0.965, Recall 0.223) are presented as evidence, but the train/test separation and the generator parameters are not fully described. This is the described-as-executed problem again, in milder form.
4. `VoI(a)` is defined generically, but the decision-theoretic foundation depends on a utility model that is not declared.
5. The acquisition action type omits the **provenance of the acquisition action itself** — who authorized it, what its scope is.
6. The step proposes an eight-layer architecture (L0–L8) but does not reconcile it with the six-layer architecture that has been stable since R601. This risks architectural inflation.
7. The "Gate B" status is presented as PASS, but several items labeled "NOT YET PROVEN" are actually **open questions**, not pending evidence. The gate should distinguish "not proven" from "not addressed."

---

# Part II — What Is Actually Established

## 2.1 A mathematical theorem

The **no-information impossibility theorem**:

$$P(O \mid Z = 0) = P(O \mid Z = 1) \Rightarrow \forall f : P(f(O) \mid Z = 0) = P(f(O) \mid Z = 1)$$

This is correct, and it is a genuine theorem, not a claim about ML.

**Proof.** Any estimator $\hat Z = f(O)$ is a deterministic function of $O$. If $O$ has the same distribution under both states, then $f(O)$ has the same distribution under both states. Therefore no test can distinguish them. $\square$

**Real-world.** A medical test cannot detect a disease if the test's output distribution is identical whether the disease is present or absent. This is the reason no amount of statistical machinery helps.

**Scope caveat.** The theorem as stated holds for discrete $Z$. For continuous $Z$, it generalizes to conditional distributions:

$$P(O \mid Z) = P'(O \mid Z) \Rightarrow \text{no consistent estimator}$$

The generalization is not stated in the document but is straightforward.

## 2.2 An empirical result (with caveats)

The synthetic ML benchmark showed:

- W7-W (weak signal): AUC ≈ 0.786, Precision ≈ 0.965, Recall ≈ 0.223, Brier ≈ 0.331.
- W7-N (no signal): AUC ≈ 0.499 (chance).
- W9 (adversarial similarity): the model predicted positive for 100% of samples; Brier ≈ 0.997.

The **strongest of these** is the W7-N result: the model correctly failed to discriminate when the information was absent. That is the theorem of §2.1 in action.

The **most important** is the W9 result: the model confidently predicted positive when the correct answer was negative. That is direct empirical evidence that **semantic similarity is not dependency**. The firewall is not optional; without it, this failure propagates into authoritative state.

**Caveat.** The document does not fully specify:

- Train/test generator parameters.
- Whether the train set contained W9-like adversarial examples.
- The exact feature definitions.
- The exact model architecture and hyperparameters.

Without these, the numbers are *reported*, not *reproducible*. Step 549 must fix this.

## 2.3 An architectural separation

Step 548 correctly separates five concepts that earlier rounds had begun to conflate:

$$\boxed{Unknown \neq Unobservable \neq Unidentifiable \neq Unacquired \neq Unvalidated}$$

This refines the Zero theory introduced in R602. Each of the five has a different remedy:

- **Unknown:** investigate.
- **Unobservable:** change instrumentation (may require new sensors).
- **Unidentifiable:** no observation can help; stop.
- **Unacquired:** acquire.
- **Unvalidated:** validate.

That is a real epistemic diagnostic.

---

# Part III — Where Step 548 Is Imprecise or Wrong

## Error 1 — The no-information theorem's scope is not stated

The theorem in §548.3 is correct as stated for discrete $Z$, but the document uses it as if it were universal. For continuous or general $Z$, the correct statement is:

$$P(O \mid Z = z) = P'(O \mid Z = z) \ \forall z \Rightarrow \text{no informative test}$$

And for partial equality, the result is a bound:

$$KL(P \| P') = 0 \Rightarrow \text{no detection}$$

but for small $KL$, detection is possible but requires exponentially many samples (Stein's lemma). This is the correct generalization, and it is *not* the same as "no information ⇒ no detection."

**Recommendation.** Restate the theorem with explicit assumptions and note the generalization.

## Error 2 — IAR's interpretation table is unjustified

The table:

| IAR | Interpretation |
|---|---|
| 0 | no observable information |
| near 0 | extremely weak signal |
| intermediate | partial information |
| near 1 | strong identification |
| 1 | complete information under the model |

The boundaries "near 0" and "near 1" are not quantified. What counts as "near"? In the W7-W experiment, `IAR` is implicitly computed as $AUC - 0.5 \approx 0.286$, but the document does not state the mapping.

**Recommendation.** Either:

- Give explicit thresholds (e.g., `IAR < 0.1` = weak, `IAR > 0.9` = strong), or
- Remove the qualitative labels and report `IAR` as a continuous value.

The current presentation hides an important decision.

## Error 3 — Described-as-executed (mild form)

The document says:

> "I implemented a fresh synthetic experiment..."

And gives numbers:

> "AUC ≈ 0.786... Precision ≈ 0.965... Recall ≈ 0.223..."

No `ExecutionRunID`, no generator specification, no model hyperparameters, no reproducibility seed. This is the same class of issue as earlier rounds but milder: the numbers are consistent and plausible, but the report does not allow verification.

**Recommendation.** Step 549 must include:

- Generator code or parameter specification.
- Train/test split specification.
- Model type and hyperparameters.
- Random seeds.
- `ExecutionRunID` per run.
- Bootstrap resample specification.

Without these, the numbers are illustrations, not evidence.

## Error 4 — VoI's decision-theoretic foundation is not declared

The formula:

$$VoI(a) = E_o[U(\delta(K, o))] - U(\delta(K)) - Cost(a)$$

is correct, but it requires:

- A **utility function** $U$ — not stated.
- A **decision policy** $\delta$ — not stated.
- A **distribution over observations** $o$ given $K$ and $a$ — not stated.

Without these, VoI is a *schema*, not a number.

**Recommendation.** Step 549 should either:

- Declare the utility function, policy, and observation model for each benchmark, or
- Report VoI as a *family* parameterized by these choices, and show how the ranking changes.

Otherwise, "VoI" is another untyped slogan — exactly what the project has repeatedly warned against.

## Error 5 — Acquisition action omits its own provenance

The type:

$$a = (Target, Source, Observation, Cost, Authority, Time, Contract)$$

includes `Authority` and `Time`, but not:

- Who *authorized* this acquisition action? (Different from who *executes* it.)
- What is the action's own provenance chain?
- What evidence supports that the action will yield the declared observation?

**Recommendation.** Extend to:

$$a = (Target, Source, Observation, Cost, Authority, Time, Contract, ActionProvenance, AuthorizationRecord)$$

This connects to the recovery-vs-augmentation distinction from R604.6: the action's provenance determines whether its output is an *acquired* observation or a *reconstructed* one.

## Error 6 — The eight-layer architecture risks architectural inflation

Step 548 §548.39 proposes L0–L8, replacing the L0–L6 architecture stable since R601. The additions are:

- L6 Active Epistemic Acquisition (new)
- L7 Assurance (was L4)
- L8 Governance (was L6)

And the shuffle is not justified. The previous architecture was:

- L0 Kernel
- L1 Semantic Fabric
- L2 Formal Fabric
- L3 Epistemic Assessment
- L4 Assurance
- L5 Intelligence
- L6 Governance

Adding "Active Acquisition" as L6 and pushing Assurance and Governance up is a **structural change** that has not been argued for. It violates the project's own discipline: *do not add a new layer without a counterexample that forces it*.

**Recommendation.** Fit Active Acquisition into the existing architecture:

- L3 Assessment: add `IdentifiabilityAssessment`, `AcquisitionAssessment`.
- L4 Assurance: add `AcquisitionVerification`, `CounterexampleSearch`.
- L5 Intelligence: add `AcquisitionCandidateGenerator`, `AcquisitionRanking`.
- L6 Governance: add `AcquisitionPermission`, `ResourceAuthorization`.

This preserves the six-layer stability while adding the new capabilities.

The alternative (eight layers) should be adopted only if a specific capability cannot be placed in the six-layer form.

## Error 7 — Gate B conflates "not proven" with "not addressed"

Gate B lists:

```
? Real-world dependency discovery
? General ML performance
? Universal VoI formulation
? Completeness of acquisition strategy
? Optimal active acquisition
? Real-world latent-factor identifiability
```

But these are heterogeneous:

- "Universal VoI formulation" is not a goal that should be pursued; the document already correctly says VoI is contract-relative. Marking it as "not proven" implies it is a target.
- "Optimal active acquisition" may be infeasible (NP-hard in general). It should be labeled "**open problem**" not "**not proven**."
- "Real-world dependency discovery" is a **future benchmark**, not a pending proof.

**Recommendation.** Split Gate B's bottom section into:

- **Not yet proven** (there is a specific claim, evidence is pending).
- **Not yet addressed** (the question is open; no claim has been made).
- **Out of scope** (the goal has been correctly abandoned).

This is the same status discipline the project applies to `VerificationResult` (`PASS`, `FAIL`, `UNKNOWN`, `NOT_APPLICABLE`).

---

# Part IV — Full Term Definitions

Each term: **Definition · Type · Real-world · Invalid · Invariant.**

## Kernel terms

### Identity (ID)
- **Definition:** Persistent unique name for an epistemic artifact.
- **Type:** $ID : \text{Artifact} \to \mathbb{N}$, injective.
- **Real-world:** ISBN.
- **Invariant:** I-S01.

### Typed Relation (𝓡*)
- **Type:** $r : ID \times ID \to \text{RelationType}$.

### Semantics (Sem)
- **Type:** $Sem : ID \times \mathcal{R}^\star \times C \times \Gamma \to \text{Meaning}$.

## State and operation terms

### State (K)
- **Type:** $K = (X, H)$.

### Authoritative State (X)
- **Real-world:** Accepted patient record.

### History (H)
- **Type:** Append-only event sequence.

### Operation
- **Type:** $(ID, InputType, OutputType, Class, MutationPolicy, Pre, Post, Transform, Scope, Regime, PreservationTargets, LossProfile)$.

### Transformation
- **Type:** $f : X \to Y$.

### CompatibilityWitness (8-tuple)
- **Type:** $(src, tgt, conv, pre_{conv}, pre_{comp}, Z_w, Loss_w, \Gamma_w)$.

### Scope
- **Type:** $(Domain, Population, TimeRange, Regime)$.

### Regime (Γ)
- **Type:** $(Axioms, Semantics, Rules)$.

## New terms (Step 548)

### Information (I)
- **Definition:** An observable distinction that can reduce uncertainty about a variable, proposition, relation, or state under a specified model.
- **Type:** $I : \Omega \to \mathcal{O}$ where $\mathcal{O}$ is the observation space.
- **Real-world:** A pipeline identifier distinguishes two possible pipelines.
- **Invalid:** Observations with identical distributions under all hypotheses carry no information.

### Information Availability
- **Definition:** Whether the currently accessible observations contain any distinguishable signal about the target.
- **Type:** $I(O; Z) > 0$ under the declared probability model.
- **Real-world:** A metadata field that distinguishes worlds carries information; a constant does not.

### Identifiability (recap from R604.7, refined here)
- **Definition:** Target $Z$ is identifiable from observations $O$ if different values of $Z$ produce distinguishable $O$ distributions.
- **Type:** $P(O \mid Z = z) \neq P(O \mid Z = z')$ for some $z \neq z'$.
- **Real-world:** A test with distinct distributions under disease present vs absent.
- **Invalid:** A test whose distribution is identical under both states.

### Information Boundary
- **Definition:** The explicit boundary between observable, hidden, inferable, and non-identifiable parts of the model.
- **Type:** $B_I = (O, H, ID, \Lambda)$.
- **Real-world:** A privacy boundary that declares what is accessible.
- **Invalid:** Assuming observations are complete.

### Information Availability Ratio (IAR)
- **Definition:** $IAR = I(O; Z) / H(Z)$.
- **Type:** Ratio in $[0, 1]$.
- **Real-world:** $IAR = 0$ means no information; $IAR = 1$ means full identification.
- **Invalid:** Treating it as a universal KnowledgeOS score (it belongs to the information-theoretic regime).

### Information Gain (IG)
- **Definition:** Reduction in uncertainty about $Z$ from observing $O_a$.
- **Type:** $IG(a) = H(Z) - H(Z \mid O_a)$.
- **Real-world:** Reducing entropy of a hypothesis space.
- **Invalid:** Equating with Determination Gain.

### Determination Gain (DG)
- **Definition:** Whether the acquired observation changes a determination from unresolved to resolved.
- **Type:** $DG(a) \in \{0, 1\}$ (or a finer scale).
- **Real-world:** An observation that flips an assessment from `UNKNOWN` to `PASS` or `FAIL`.
- **Invalid:** Confusing with information gain.

### Decision Value (DV)
- **Definition:** The usefulness of the acquired information for a downstream decision.
- **Type:** $DV(a \mid U, \delta) = E[U \mid \text{with } a] - E[U \mid \text{without } a]$.
- **Real-world:** The expected improvement in a decision's utility.
- **Invalid:** Confusing with information gain.

### Value of Information (VoI)
- **Definition:** Expected benefit of acquisition under a declared utility, decision policy, cost, and uncertainty model.
- **Type:** $VoI(a \mid U, \delta, C) = DV(a) - Cost(a)$.
- **Real-world:** Deciding whether to order a $50 lab test that resolves a diagnosis.
- **Invalid:** Presenting VoI as universal (it is contract-relative).

### Information Acquisition Specification (IAS)
- **Definition:** A contract-level declaration of what information is being sought.
- **Type:** $(Target, Hypotheses, Action, ExpectedObservation, Cost, Scope, Regime, Contract)$.
- **Real-world:** "Determine whether E₁ and E₂ share a pipeline by inspecting the build manifest."
- **Invalid:** Acquiring without declaring what hypothesis it addresses.

### Acquisition Action (corrected to include provenance)
- **Definition:** A formally described operation capable of obtaining additional evidence.
- **Type:** $(Target, Source, Observation, Cost, Authority, Time, Contract, ActionProvenance, AuthorizationRecord)$.
- **Real-world:** A specific API call to retrieve a pipeline ID.
- **Invalid:** An acquisition without declared authority or provenance.

### Stopping Rule
- **Definition:** The condition under which KnowledgeOS stops acquiring information.
- **Type:** Decision function over current state, VoI of admissible actions, and governance constraints.
- **Real-world:** "Stop when no admissible action has positive VoI."
- **Invalid:** "Keep acquiring forever."

### Acquisition Frontier
- **Definition:** The set of currently admissible observations whose acquisition could materially change the epistemic state.
- **Type:** $AF(K, Q) = \{a : a \text{ admissible and may change } K\}$.
- **Real-world:** The list of tests that could resolve a diagnosis.

### Unidentifiable (Zero-classification)
- **Definition:** Available observations cannot distinguish competing possibilities.
- **Type:** A classification in the Zero theory.
- **Real-world:** A hidden dependency that no observation can reveal.
- **Invalid:** Any "I don't know."

### Unacquired (Zero-classification)
- **Definition:** A potentially informative observation exists but has not been obtained.
- **Type:** A classification in the Zero theory.
- **Real-world:** A log that would resolve the question but has not been fetched.
- **Invalid:** A question with no possible observation.

### Unvalidated (Zero-classification)
- **Definition:** A candidate interpretation exists but has not passed its validation contract.
- **Type:** A classification in the Zero theory.
- **Real-world:** An ML-predicted dependency awaiting verification.
- **Invalid:** Established knowledge.

### Dependency Hyperedge
- **Definition:** A dependency involving multiple entities and a shared latent factor.
- **Type:** $\{E_1, \ldots, E_k\} \to D$.
- **Real-world:** Three evidence items sharing a latent common cause.
- **Invalid:** A single pairwise edge that misrepresents the joint structure.

### Determination Fragility
- **Definition:** Whether admissible perturbations of inputs can change a determination.
- **Type:** $Fragility(d) = \exists \pi \in \Pi : Det(E) \neq Det(\pi(E))$.
- **Real-world:** A conclusion that changes when one source is removed.
- **Invalid:** A conclusion insensitive to all perturbations (that is robustness).

### Candidate Quarantine
- **Definition:** A separate epistemic space for unvalidated candidates.
- **Type:** `CandidateSpace` distinct from `AuthoritativeKnowledgeState`.
- **Real-world:** An ML prediction awaiting validation before it enters the KB.
- **Invalid:** ML candidates written directly to X.

---

# Part V — Worked Examples

## Example 1 — No-information theorem in practice

**Setup.** A rare disease with two possible causes, $Z \in \{0, 1\}$. A test $O$ returns "normal" always, regardless of cause.

$$P(O = \text{normal} \mid Z = 0) = P(O = \text{normal} \mid Z = 1) = 1$$

**Consequence.** No estimator can distinguish $Z = 0$ from $Z = 1$ from this test.

**Real-world.** A thermometer used to diagnose a viral infection. Temperature is normal in both cases. No ML helps.

## Example 2 — Weak-signal world (W7-W)

**Setup.** Three evidence items share a latent pipeline. The pipeline produces a fingerprint that is visible in one of 20 features.

**Information.** $I(O; Z) > 0$ but small.

**ML result.** AUC ≈ 0.786 — better than chance.

**Interpretation.** ML can exploit weak signal. Recall at threshold 0.5 is low (0.223), meaning most true dependencies are missed. That is an operational issue, not a violation of the theorem.

## Example 3 — Adversarial world (W9)

**Setup.** Three evidence items have:
- high text similarity,
- high embedding similarity,
- similar timestamps,
- common superficial fingerprints.

But ground truth $Z = 0$ (no dependency).

**ML result.** All predictions positive. Brier ≈ 0.997.

**Interpretation.** ML is confidently wrong. Similarity ≠ dependency.

**Firewall consequence.** This candidate must not enter X.

## Example 4 — Information gain ≠ determination gain

**Setup.** A hypothesis space of 100 possible dependency structures. An observation eliminates 50 of them.

$$IG > 0$$

But suppose both `dependency exists` and `dependency absent` remain possible in the surviving 50.

$$DG = 0$$

**Conclusion.** Learned something; resolved nothing. This is a real epistemic state.

## Example 5 — VoI is contract-relative

**Setup.** Two acquisitions for a diagnosis:

| Action | Error rate | Cost |
|---|---|---|
| Metadata | 0.35 | 1 |
| Log | 0.10 | 5 |
| Registry | 0.00 | 8 |

Information gains (symmetric binary channel): 0.066, 0.531, 1.000.

Decision values (loss 10 per error): +0.5, −1.0, −3.0.

**Conclusion.** The most informative acquisition is not the most valuable. VoI depends on the loss function.

## Example 6 — Unidentifiable is not Unacquired

**Setup A.** A hidden dependency between E₁ and E₂. Two competing worlds produce identical observations. There is no test that could distinguish them.

→ **Unidentifiable.** Acquisition is futile.

**Setup B.** A hidden dependency between E₁ and E₂. Their pipeline information exists in a database but has not been fetched.

→ **Unacquired.** Acquisition is useful.

**Consequence.** The same "I don't know" state requires different remedies.

## Example 7 — Active acquisition in the Nexus case

**Setup.** Whether the cloud-first policy mandates a particular deployment architecture is unclear.

**Acquisition candidates.**
- A: retrieve authoritative policy version.
- B: ask infrastructure team.
- C: retrieve approved exceptions.
- D: inspect Architecture Board decisions.
- E: check target environment constraints.

**Evaluation.** A and B are likely to resolve; C, D, E may add context but not resolve.

**Action.** If VoI(A) > 0 and A is admissible, acquire A.

**Real-world.** Instead of letting an LLM guess, the system asks for the specific missing fact.

---

# Part VI — ML Positioning and Step 549 Plan

## What ML may do

- **Candidate generation.** Propose `CandidateDependency(E₁, …, E_k, features, score)`.
- **Group discovery.** Propose `CandidateLatentFactor({E₁, …, E_k})`.
- **Acquisition ranking.** Estimate $P(\text{action } a \text{ resolves the target})$ — this is a candidate, not a fact.
- **OOD detection.**
- **Calibration.**

## What ML may not do

- Assert dependency.
- Assert independence.
- Assert identifiability.
- Write to X.
- Bypass L4.

## Firewall (extended)

```
ML candidate
  → IdentifiabilityCheck
  → TypeCheck
  → ContractCheck
  → RegimeCheck
  → ScopeCheck
  → CandidateQuarantine
  → L4 Validation
  → Assessment
```

The IdentifiabilityCheck is new: it ensures the ML is applied only when the information is present in the data. This is an architectural consequence of the no-information theorem.

## Concrete technique

**Features per evidence group:**
- source overlap
- lineage overlap
- model lineage
- transformation lineage
- assumption overlap
- citation overlap
- temporal proximity
- graph distance
- semantic similarity (embeddings)

**Models:**
- Pairwise: gradient-boosted trees.
- Group: set-based classifiers or graph neural networks over evidence hypergraphs.
- Acquisition ranking: classification or regression over action features.

**Metrics:** Precision, Recall, FDR, FIR, AUC, Calibration Error, Brier, Abstention Quality, ARR (Acquisition Resolution Recall), AWR (Acquisition Waste Rate).

**Cardinal rules:**

$$\boxed{MLAccuracy \neq Dependency}$$
$$\boxed{MLScore \neq Materiality}$$
$$\boxed{MLPredictedValue \neq VerifiedValue}$$
$$\boxed{MLCapability \leq InformationAvailable}$$

## Step 549 — Active Acquisition Planning and Sequential Value-of-Information

Step 549 must do:

**Q1 — Full specification of the acquisition benchmark.** Generator parameters, distributions, seed, split, ExecutionRunID.

**Q2 — Sequential acquisition.** Test whether greedy (pick highest VoI at each step) matches or falls short of optimal (dynamic programming over actions).

**Q3 — Cost-sensitive planning.** Test whether greedy VoI with cost weights outperforms unweighted ranking.

**Q4 — Stopping rules.** Test when greedy terminates, whether it matches optimal stopping, and whether it terminates when VoI is uniformly negative.

**Q5 — Adversarial acquisition.** Cases where:

- The most informative action is unauthorized.
- The least informative action has hidden cost.
- An acquisition changes the scope.
- An acquisition changes the regime.
- An acquisition introduces a new competing hypothesis.

**Q6 — Acquisition provenance.** Ensure every acquired observation carries its full provenance chain.

**Q7 — Report discipline.** Every test emits `certificate.json` with `ExecutionRunID`.

## What Step 549 must not do

- Do not introduce a new Kernel primitive.
- Do not introduce a new BC.
- Do not expand to eight layers.
- Do not conflate VoI with Information Gain.
- Do not allow ML to enter the authority path.
- Do not claim real-world results from synthetic benchmarks.

---

# Part VII — Optimized Architecture

## Six-layer architecture (preserved)

Active Acquisition fits into the existing six layers without expansion:

```
                KNOWLEDGEOS — Six-Layer Architecture
                              │
                     L0 Kernel (ID, R*, Sem)
                              │
                     L1 Semantic Fabric
             Context / Contract / Scope / Regime
             IdentifiabilityContract / AcquisitionContract
                              │
                     L2 Formal Fabric
        Types / Operations / Transformations
        Composition / PreservationTarget / Bridge
        Provenance / Lineage / CausalHistory
        DependencyFactor / FactorSet / Hyperedge
        Intervention / AcquisitionAction
                              │
                     L3 Epistemic Assessment
     Dependency | Materiality | Minimality
     Identifiability | InformationGain
     DeterminationGain | DecisionValue
     Zero (Unknown | Unobservable | Unidentifiable |
           Unacquired | Unvalidated)
     AcquisitionAssessment
                              │
                     L4 Assurance
     Invariants / Verification / Counterexample
     Spec → Run(ExecutionRunID) → Result → Cert
     IdentifiabilityTesting / FirewallVerification
     CandidateQuarantine / Metamorphic / Bootstrap
                              │
                     L5 Intelligence
       Pairwise ML / Group ML / Embeddings
       AcquisitionCandidateGenerator
       AcquisitionRanking / OOD
                              │
                     L6 Governance
       Authority / Permission / Decision
       AcquisitionPermission / ResourceAuthorization
```

No L4.5. No L7. No L8.

## The laws (updated)

$$\boxed{ML \not\to X}$$
$$\boxed{ML \not\to Assessment \text{ without L4}}$$
$$\boxed{MLCapability \leq InformationAvailable}$$
$$\boxed{Pure \Rightarrow X' = X}$$
$$\boxed{Spec \neq Run \neq Result \neq Cert}$$
$$\boxed{\text{Use the weakest sufficient method}}$$
$$\boxed{HandEnumerated \neq Simulated \neq ExecutedWithTrace}$$
$$\boxed{NonCommutativity \neq Error}$$
$$\boxed{Associativity\ is\ }\mathcal{O}\text{-relative}$$
$$\boxed{Target \neq Bridge \neq Assessment}$$
$$\boxed{Provenance \neq CausalHistory \neq Dependency}$$
$$\boxed{Structure \neq Mechanism \neq Factors}$$
$$\boxed{Materiality \neq Minimality}$$
$$\boxed{InteractionDependency \neq JointMateriality}$$
$$\boxed{InformationGain \neq DeterminationGain \neq DecisionValue}$$
$$\boxed{Unknown \neq Unobservable \neq Unidentifiable \neq Unacquired \neq Unvalidated}$$
$$\boxed{Acquisition \neq ML}$$
$$\boxed{Acquisition \neq Resolution}$$

## Methodological invariant

$$\boxed{\text{Each round attempts to falsify the previous round before extending it.}}$$

Step 548 continues this pattern (sixth consecutive round with self-correction).

---

# Part VIII — How Far We Are (Bullet Points)

## Achieved

- **Kernel:** stable; $(ID, \mathcal{R}^\star, Sem)$ unchanged across 600+ rounds.
- **L0–L6 architecture:** stable; no new BC.
- **Operation algebra:** executable.
- **Composition, non-commutativity, associativity:** executed and proven.
- **Preservation, bridges, loss composition:** executable.
- **Provenance, lineage, history:** executable with tamper detection.
- **Dependency (structure, mechanism, factors):** executable, target/scope/regime-relative.
- **Multi-factor dependency:** materiality, minimality, interaction — executable.
- **No-information impossibility:** theorem; correct scope; correct consequence.
- **W7 split into W7-N (no signal) and W7-W (weak signal):** correct refinement.
- **Adversarial negative control (W9):** ML confidently wrong; firewall required.
- **Identifiability, information gain, determination gain, decision value, VoI:** separated.
- **Zero refined to five-classification:** Unknown / Unobservable / Unidentifiable / Unacquired / Unvalidated.
- **Active acquisition:** typed as acquisition action + specification.
- **ML firewall:** preserved and extended with identifiability check.
- **Self-falsification:** six consecutive rounds.

## Not yet done

- **Step 549:** sequential VoI benchmark, greedy vs optimal, stopping rules, adversarial acquisition.
- **Full report discipline:** ExecutionRunID, certificate.json per test.
- **Reproducibility:** generator parameters, seeds, hyperparameters.
- **VoI specification:** utility function, decision policy, observation model.
- **Acquisition composition:** algebraic laws for sequential acquisition.
- **Real-world benchmark:** outside synthetic worlds.
- **Full invariant catalogue** (~47 + new I-E-D, I-E-I).
- **Terminology freeze.**
- **Theory Specification v1.0.**

## Open (not "not proven")

- **Optimal acquisition planning:** NP-hard in general; greedy may be adequate.
- **Universal VoI formula:** correctly abandoned; VoI is contract-relative.
- **Category theory application:** pending specific falsifiable experiment.
- **Matroid universality:** falsified; other representations may apply.
- **Lattice for dependency types:** proposed ordering falsified; lattice theory itself not falsified.

## Standing rules

- No new Kernel primitive.
- No new BC.
- No universal theorems from finite tests.
- Use the weakest sufficient method.
- Each round attempts to falsify the previous round.
- ML cannot write to X.
- ML cannot bypass L4.
- `MLCapability ≤ InformationAvailable`.

## The single most important next thing

$$\boxed{\text{Fix report discipline. Execute Step 549. Test greedy vs optimal acquisition.}}$$

Because the sequence to date has produced increasingly rigorous theory, but the evidence has not been fully surfaced. Report discipline is now the bottleneck to independent verification.

## Two open questions I flag

1. **Should acquisitions compose?** If $a_1$ then $a_2$ produces the same observation as $a_2$ then $a_1$, acquisition is commutative; otherwise not. This is the same question as composition for operations and should be tested in Step 549.

2. **Does acquisition have a closure?** If $a_1$ makes $a_2$ admissible but $a_2$ is not initially admissible, the acquisition set grows. Step 549 must decide whether to compute an *acquisition closure* (all reachable actions) or to plan one step at a time. My recommendation: one step at a time, with a termination condition, because full closure may be infinite.

If you want to proceed, tell me:

- **(A)** Step 549 specification (acquisition benchmark, sequential VoI, greedy vs optimal, stopping rules, adversarial acquisition, report format).
- **(B)** Step 549 code.
- **(C)** Both, in order.

My recommendation is **(C)**, with **report discipline fixed first** — because the whole point of Step 549 is to produce *auditable* evidence for the acquisition theory, and without `ExecutionRunID` and `certificate.json` per test, the evidence is described, not executed.
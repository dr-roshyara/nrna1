Yes. Based on the cross-review, I would make the next experiment explicitly a **bridge/falsification experiment**, not an attempt to confirm the emerging theory.

The prompt below is designed for Claude Code/Codex to implement in the repository while preserving the current governance discipline.

---

# Prompt: KR-BRIDGE-01 — Zero × Representation Preservation

```text
You are implementing the next controlled empirical experiment in the KnowledgeOS research program.

EXPERIMENT ID
KR-BRIDGE-01-ZERO-PRESERVATION-2026-09

TITLE
Zero × Representation Preservation — Bridge/Falsification Experiment

PURPOSE
Determine whether there is any reproducible empirical or structural relationship between:

    A. elimination / Transformation-relative Zero
    B. representation preservation / adequacy

WITHOUT assuming that Zero is a preservation criterion.

This is a bridge experiment between the independently established KR-ZERO and
KR-REP-REDUCTION result families.

The experiment MUST remain capable of falsifying the hypothesis that Zero
predicts, characterizes, or is systematically associated with preservation.

DO NOT attempt to construct an algebra.
DO NOT select a carrier in advance.
DO NOT modify Theory v1.2.
DO NOT create Theory v1.3.
DO NOT select a kernel operator.
DO NOT merge KR-ZERO and KR-REP-REDUCTION into one formalism.

======================================================================
1. EXISTING EMPIRICAL BASELINE
======================================================================

Two prior experiments exist.

KR-ZERO:
- Studies Transformation-relative Zero / eliminability.
- Found approximately 89.7% singleton-determined levels.
- Found approximately 8.2% irreducible levels.
- Demonstrated that proper-subset Zero-status alone cannot, in general,
  determine group Zero-status.
- Does NOT establish Zero as a preservation criterion.
- Carrier remains OPEN.

KR-REP-REDUCTION:
- Studies representation reduction and preservation of a required observable Q.
- Found a preservation boundary in the tested sequential chain:
      R5 adequate
      R4 inadequate
- Demonstrated that representation adequacy and decoder realization differ.
- Demonstrated multidimensional reduction.
- Found no observed Zero/boundary association in that experiment.
- Does NOT establish a KR-ZERO law.
- Carrier remains OPEN.

These experiments are complementary but analytically independent.

The present experiment must test the missing bridge rather than assume it.

======================================================================
2. PRIMARY RESEARCH QUESTION
======================================================================

Primary question:

    Is there a reproducible relationship between elimination Zero and
    preservation/adequacy under representation transformation?

The experiment must distinguish at least the following possibilities:

    H0:
        Zero and preservation are not systematically related in the tested
        regime.

    H1:
        Zero is associated with preservation under specified conditions.

    H2:
        Zero is predictive of preservation only under particular
        transformation/observable/contract configurations.

    H3:
        Any apparent relationship is explained by a confounding factor
        such as transformation class, contract, observable, redundancy,
        or representation regime.

Do NOT privilege H1.

The experiment is successful if it produces a clear positive OR negative
result with respect to the tested relationship.

======================================================================
3. CRITICAL DESIGN RULE
======================================================================

USE A PARALLEL REPRESENTATION FAMILY.

Do NOT use only a deterministic sequential chain.

A sequential chain:

    R(n-1) = T(Rn)

has a data-processing constraint:

    H(Q | Rn) <= H(Q | R(n-1))

and therefore cannot test non-monotone adequacy.

The present experiment requires independently generated representations:

    Ra = Ta(D)
    Rb = Tb(D)

where neither representation is required to be a deterministic function
of the other.

Every representation must nevertheless have a precisely defined provenance
back to the same source D.

======================================================================
4. DO NOT ASSUME A CARRIER
======================================================================

The mathematical carrier must be the minimum structure required by the
actual experiment.

Do not declare:

    set
    graph
    hypergraph
    lattice
    matroid
    closure system
    rewriting system
    category
    algebra

as the carrier before the experiment.

The experiment may use a deliberately small numerical/document-like
representation, but clearly distinguish:

    experimental carrier
    mathematical carrier
    future KnowledgeOS knowledge representation

No transfer between these is allowed without evidence.

======================================================================
5. SOURCE DATA
======================================================================

Use a finite structured source D.

Prefer a source structure rich enough to permit:

    - redundancy
    - relational dependence
    - context dependence
    - individually removable elements
    - jointly non-removable groups
    - preservation and information-loss cases

The source generator must be deterministic from declared seeds.

Persist the complete source population.

For every source case store:

    D
    Q(D)
    contract parameters
    transformation parameters
    Zero ground truth
    every representation
    every adequacy result
    every decoder result

No result may depend on regenerated or hidden data.

======================================================================
6. REQUIRED REPRESENTATION FAMILY
======================================================================

Generate multiple representations independently from D.

At minimum construct representations with:

    R_A:
        information-preserving transformation

    R_B:
        controlled lossy transformation

    R_C:
        redundancy-removing transformation

    R_D:
        relational/context-sensitive transformation

The exact transformations must be specified before results are examined.

Each transformation must have:

    transformation ID
    mathematical definition
    input type
    output type
    deterministic/random status
    seed if applicable
    provenance
    expected information effect
    contract dependencies

Do not optimize transformations after seeing results.

======================================================================
7. REQUIRED PRESERVATION TARGET
======================================================================

Define Q BEFORE generating representations.

Q must be an observable whose preservation can be tested independently.

Use:

    Q : D -> answer space

and define:

    Adequate(R) iff Q is recoverable from R

Operationally measure both:

    empirical conditional information
        Hhat(Q | R)

and

    exact semantic violation count
        Nviol

where appropriate.

Do not treat:

    Hhat(Q|R) = 0

as proof that population:

    H(Q|R) = 0.

Maintain the distinction throughout the report.

======================================================================
8. REQUIRED ZERO DEFINITION
======================================================================

Use the existing Transformation-relative Zero definition.

For elimination E_S:

    Zero(T, Pi, S; D)
        iff
    Pi(T(D)) = Pi(T(E_S(D)))

Use the typed elimination operator appropriate to the representation.

DO NOT use D \ S unless the representation type explicitly makes that
operation valid.

Record:

    D
    T
    Pi
    S
    E_S(D)
    T(D)
    T(E_S(D))
    Pi(T(D))
    Pi(T(E_S(D)))
    Zero result

Zero must be calculated independently from the preservation measurement.

======================================================================
9. NEVER DEFINE ZERO USING Q

This is critical.

Do NOT define:

    Zero(S) := Q(D) = Q(E_S(D))

That would build the desired bridge into the definition.

Zero must use its own declared observable/operator:

    Pi

while preservation uses:

    Q.

The experiment is specifically testing whether these independently defined
observables relate.

======================================================================
10. REQUIRED CROSSING DESIGN

The experiment must deliberately seek all four cells:

                         Adequate       Inadequate

    Zero                  Z+A            Z-I

    Non-Zero              NZ+A           NZ-I

The ideal experiment contains substantial observations in all four cells.

Especially important:

    Zero + Inadequate

would directly demonstrate that Zero is not sufficient for preservation.

    Non-Zero + Adequate

would demonstrate that Zero is not necessary for preservation.

If one or more cells are empty, DO NOT interpret that as a law.

Investigate whether the emptiness is caused by:

    generator constraints
    contract constraints
    transformation design
    sample size
    mathematical necessity
    implementation error

======================================================================
11. PARAMETER SWEEPS
======================================================================

Do not select a single convenient configuration.

Sweep the parameters that plausibly influence the relationship, including
where applicable:

    - redundancy level
    - group size
    - representation loss
    - transformation class
    - observable Pi
    - preservation target Q
    - contract parameters
    - context dependence
    - relational interaction
    - source cardinality

Every parameter sweep must be declared before execution.

Do not silently tune parameters to obtain balanced cells.

======================================================================
12. CONFOUNDING ANALYSIS
======================================================================

If Zero and Adequacy appear associated, determine whether the association
is actually caused by another factor.

At minimum stratify results by:

    transformation T
    observable Pi
    contract C
    representation family
    source structure
    elimination size |S|

Report both:

    pooled relationship

and

    stratified relationship.

An association that disappears after stratification must NOT be reported
as a general Zero-preservation relationship.

======================================================================
13. TRANSFORMATION × OBSERVABLE INTERACTION
======================================================================

Explicitly test:

    T × Pi

because KR-ZERO already indicates that Zero behavior is transformation-
specific and that later mechanism analysis found interaction effects.

Do not classify transformations as simply:

    relational
    element-wise

and stop there.

Measure the actual interaction between transformation and observable.

If higher-order or preservation behavior occurs under an element-wise
transformation, record it.

Do not resurrect the withdrawn claim:

    "relational transformations produce higher order;
     element-wise transformations do not."

======================================================================
14. ZERO DETERMINATION ORDER
======================================================================

Where group elimination is tested, retain the KR-ZERO determination-order
analysis.

Measure:

    k = 1
    k = 2
    k = 3
    ...
    irreducible

where feasible.

Do not assume:

    higher k -> more reduction
    higher k -> less preservation
    irreducibility -> information loss

Those are hypotheses to test, not definitions.

In particular, do not equate:

    determination order

with

    preservation boundary.

======================================================================
15. FOUR-WAY CLASSIFICATION

For each elimination/representation observation classify:

    1. Zero + Adequate
    2. Zero + Inadequate
    3. Non-Zero + Adequate
    4. Non-Zero + Inadequate

Then additionally record:

    determination order k
    transformation T
    observable Pi
    preservation target Q
    contract C
    reduction metrics

This allows the bridge to be analysed without collapsing dimensions.

======================================================================
16. REDUCTION METRICS

Retain the KR-REP-REDUCTION principle that reduction is multidimensional.

Measure independently where meaningful:

    encoded size
    cardinality
    field count
    representation entropy
    number of relations
    number of records
    other declared structural measures

Do NOT invent a single "reduction score" unless independently justified.

Do not call these dimensions statistically independent merely because they
move differently.

======================================================================
17. DECODER SEPARATION

If a decoder is used, keep:

    representation adequacy

separate from:

    decoder realization.

Where possible include an invertible recoding control.

The purpose is to ensure that an observed relationship between Zero and
performance is not actually caused by decoder sensitivity.

Report separately:

    Hhat(Q|R)
    exact recoverability
    fixed-decoder F
    decoder violations

======================================================================
18. CONTROLS

Required controls:

CONTROL A:
    A known information-preserving representation.

CONTROL B:
    A known information-destroying representation.

CONTROL C:
    An invertible recoding of the same representation.

CONTROL D:
    A representation where Zero is known to vary independently of the
    preservation target, if the generator permits this.

CONTROL E:
    Positive control demonstrating that the preservation test can actually
    detect information loss.

CONTROL F:
    Positive control demonstrating that the Zero test can actually detect
    elimination differences.

All controls must execute before interpretation of results.

======================================================================
19. SAMPLE DESIGN

Use independent TRAIN and TEST populations.

Declare:

    seeds
    population size
    generator
    parameter distributions

before execution.

Persist the complete populations.

No training result may influence test generation.

No test result may influence transformation definitions.

No post-hoc filtering may be used to create the desired four-way cells.

======================================================================
20. STATISTICAL ANALYSIS

Do not rely only on raw percentages.

For the Zero × Adequacy relationship report:

    contingency tables
    conditional rates
    effect size
    confidence intervals
    bootstrap intervals where appropriate
    stratified rates
    parameter sensitivity

If a statistical test is used, declare why it is appropriate.

Do not confuse:

    statistical association

with:

    causal mechanism.

A significant association is not evidence that Zero causes preservation.

======================================================================
21. REQUIRED FALSIFICATION QUESTIONS

The implementation must explicitly answer:

Q1:
    Can Zero occur while preservation fails?

Q2:
    Can preservation hold while Zero fails?

Q3:
    Does the Zero/Adequacy relationship survive changes in T?

Q4:
    Does it survive changes in Pi?

Q5:
    Does it survive contract changes?

Q6:
    Does it survive changes in redundancy?

Q7:
    Does determination order predict preservation independently of T/Pi/C?

Q8:
    Does the relationship survive an invertible recoding?

Q9:
    Is any apparent relationship explained by transformation class?

Q10:
    Is any apparent relationship explained by the preservation target Q?

======================================================================
22. REQUIRED OUTCOMES

The experiment must support one of the following outcome classes.

OUTCOME A — NO RELATIONSHIP OBSERVED

Zero and preservation show no stable relationship after controls and
stratification.

Interpretation:

    Zero remains analytically independent in the tested regime.

This does NOT prove universal independence.

OUTCOME B — CONDITIONAL RELATIONSHIP

A relationship exists only under particular T/Pi/C/Q configurations.

Interpretation:

    Zero may be a contract/transformation-relative predictor under a
    restricted regime.

Do NOT generalize.

OUTCOME C — ROBUST RELATIONSHIP

A relationship survives transformations, observables, contracts and
controls.

Only in this case may a formal bridge hypothesis be proposed for a new
experiment.

OUTCOME D — DESIGN FAILURE

The four-way space cannot be generated or controls fail.

Do NOT interpret the results theoretically.

Revise the experiment.

======================================================================
23. REQUIRED AUDIT ARTIFACT

Create:

    verification/zero-algebra/KR-BRIDGE-01-AUDIT-2026-09.md

The audit must verify:

    - source generation
    - Q generation
    - Pi generation
    - transformation definitions
    - elimination definitions
    - Zero calculation
    - adequacy calculation
    - decoder calculation
    - train/test independence
    - seed handling
    - serialization
    - parameter sweeps
    - contingency tables
    - statistical calculations
    - absence of post-hoc selection
    - reproducibility

Every important empirical claim must trace to persisted data.

======================================================================
24. REQUIRED RESULT ARTIFACT

Create:

    verification/zero-algebra/KR-BRIDGE-01-RESULTS-2026-09.md

The result document must contain:

    1. Executive verdict
    2. Experimental question
    3. Design
    4. Carrier actually used
    5. Q and Pi definitions
    6. Transformation family
    7. Contract family
    8. Zero definition
    9. Adequacy definition
   10. Four-way Zero × Adequacy table
   11. Parameter sweep results
   12. Stratified analysis
   13. Determination-order analysis
   14. Decoder controls
   15. Reduction dimensions
   16. Falsification scorecard
   17. Threats to validity
   18. What is established
   19. What is not established
   20. Open questions
   21. Reproduction instructions
   22. Epistemic status

======================================================================
25. EPISTEMIC LABELS

Use explicit labels:

    [THEORY]
    [EXP]
    [NEG]
    [PROP]
    [OPEN]
    [DESIGN]

Do not convert:

    [EXP]

into:

    universal theorem.

Do not convert:

    [NEG]

into:

    proof of impossibility.

Do not convert:

    [PROP]

into:

    theory.

======================================================================
26. GOVERNANCE CONSTRAINTS

During implementation:

DO NOT modify:

    Theory v1.2
    KR-ZERO historical results
    KR-REP-REDUCTION historical results
    kernel operators
    production application code
    production database
    existing governance decisions

Do not rewrite historical result documents to accommodate new findings.

If a new finding conflicts with an old statement, record the conflict in
the new experiment's audit/result document and request adjudication.

Historical artifacts remain immutable.

======================================================================
27. SUCCESS CRITERION

The experiment is NOT successful merely because it finds an association.

It is successful if it establishes, with controlled evidence, whether the
Zero/preservation bridge is:

    absent,
    conditional,
    robust,
    or currently untestable.

A clean negative result is equally valuable.

======================================================================
28. FINAL INTERPRETATION RULE

At the end, produce exactly three separate conclusions:

A. WHAT THE EXPERIMENT ESTABLISHES

B. WHAT THE EXPERIMENT DOES NOT ESTABLISH

C. WHAT THE NEXT EXPERIMENT SHOULD TEST

Do not propose an algebra unless the evidence explicitly earns such a step.

======================================================================
29. FINAL DELIVERABLE

Before declaring completion, report:

    - files created
    - files modified
    - code created
    - datasets created
    - population sizes
    - seeds
    - controls passed
    - controls failed
    - four-way Zero × Adequacy counts
    - strongest positive result
    - strongest negative result
    - unresolved design issues
    - audit status
    - reproducibility command

Then STOP.

Do not modify Theory v1.2.
Do not create Theory v1.3.
Do not select an algebra.
Do not select a kernel operator.
Do not declare the bridge established unless the evidence satisfies the
controls and stratification requirements above.
```

## Why I would use this exact experiment

The critical improvement over the previous two experiments is the **four-cell falsification design**:

$$
\begin{array}{c|cc}
 & Adequate & Inadequate\\
\hline
Zero & Z{+}A & Z{-}I\\
\neg Zero & NZ{+}A & NZ{-}I
\end{array}
$$

This prevents the experiment from quietly building in either of the two desired implications.

In particular:

$$
Zero \Rightarrow Adequacy
$$

would require the **Zero + Inadequate** cell to disappear under controlled conditions.

And:

$$
Adequacy \Rightarrow Zero
$$

would require the **Non-Zero + Adequate** cell to disappear.

But the experiment should **not expect either cell to disappear**. Its job is to find out.

The other major safeguard is that `Zero` and `Q` must remain independently defined. Otherwise the experiment would become circular.

### The research sequence would then be

```text
KR-ZERO
   │
   │ eliminability / determination order
   ▼
   ?
   │
   │ KR-BRIDGE-01
   │
   ├───────────────┐
   ▼               ▼
Zero          Preservation
   │               │
   └───────?───────┘
           │
           ▼
     only if earned
      formal bridge
           │
           ▼
     future algebra
```

That is the experiment I would run **before X3 is interpreted as evidence for a general algebra**. It directly attacks the biggest currently unresolved question between the two result families while preserving the very strong epistemic discipline already established in both experiments.

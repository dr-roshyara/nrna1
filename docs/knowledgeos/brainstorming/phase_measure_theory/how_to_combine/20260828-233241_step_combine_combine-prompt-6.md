# GN-43 EXTENSION — MATHEMATICAL / STATISTICAL / COMPUTATIONAL VERIFICATION
## READ / VERIFY / FALSIFY — DO NOT REPAIR

You are already running the Book-Driven Architecture Verification Protocol.

This instruction adds a second, independent verification track:

    MATHEMATICAL
    STATISTICAL
    COMPUTATIONAL
    FORMAL-CONSISTENCY

The purpose is NOT to make the mathematics look elegant.

The purpose is to determine whether the mathematical claims made by
KnowledgeOS are actually:

- mathematically well-defined;
- logically derived;
- statistically justified where applicable;
- computationally meaningful;
- algorithmically computable where claimed;
- numerically stable where relevant;
- consistent with the architecture;
- honestly represented in the Edition-2 book.

============================================================
1. GOVERNANCE BOUNDARY
============================================================

This is a VERIFICATION task only.

You MAY:

- read;
- derive;
- calculate;
- test;
- simulate;
- construct counterexamples;
- run symbolic checks;
- run numerical checks;
- implement independent reference calculations;
- compare alternative formulations;
- inspect executable implementations;
- classify findings;
- report errors.

You MUST NOT:

- modify v0.2;
- modify Final Architecture;
- modify BA-1…BA-6;
- modify BA-ED2;
- modify Edition 1;
- modify Edition-2 book chapters;
- repair mathematical definitions in place;
- silently replace formulas;
- resolve OQ-1…12;
- promote a mathematical hypothesis to architecture;
- introduce a new mathematical object as architecture;
- incorporate DeepSeek;
- create v0.3.

If something is mathematically wrong, report it.

If something is mathematically incomplete, report it.

If something is computationally impossible or underspecified, report it.

DO NOT FIX IT.

Follow:

    DISCOVER
    → FORMALIZE
    → CHECK
    → ATTACK
    → CLASSIFY
    → TRACE
    → REPORT
    → GOVERNANCE DISPOSITION

============================================================
2. SCOPE

Audit all substantive mathematical/formal claims already present in:

A. Ratified v0.2
B. Final Architecture
C. Edition-2 chapters already produced
D. Production findings PF-1…PF-9
E. Architecture findings AF-F-1…AF-F-6
F. The underlying source documents that justify those claims

Do NOT audit merely prose metaphors unless the book presents them
as mathematical statements.

============================================================
3. REQUIRED MATHEMATICAL AUDIT QUESTIONS
============================================================

For every mathematical object or formula, ask:

1. Is it well-defined?
2. Is its domain defined?
3. Is its codomain defined?
4. Are all variables typed?
5. Are all operators defined?
6. Are all assumptions stated?
7. Is the expression internally consistent?
8. Does the conclusion follow from the premises?
9. Is the derivation complete?
10. Is the result unique when uniqueness is implied?
11. Is existence established when existence is implied?
12. Is totality established when a function is called total?
13. Is the object finite, countable, infinite, or unspecified?
14. Is computation possible?
15. If possible, is an algorithm specified?
16. Is termination guaranteed?
17. What is the computational complexity?
18. Are approximations being mistaken for exact computation?
19. Are numerical issues relevant?
20. Are stochastic assumptions explicit?
21. Is statistical interpretation legitimate?
22. Are causal claims being made from correlational/statistical quantities?
23. Are probability, confidence, support, warrant and epistemic status
    being incorrectly treated as synonyms?
24. Are metrics actually metrics?
25. Are operators actually operators of the mathematical kind claimed?

Do not assume that a symbol such as "distance", "probability", "measure",
"distribution", "operator", "projection", "kernel", or "entropy" has its
mathematical textbook meaning merely because that name is used.

============================================================
4. FORMAL OBJECT INVENTORY
============================================================

At minimum audit:

A. EC = η(G, IdealState)

B. Zero(K, EC)

C. Zero source representation:
   - nine-valued satisfaction set
   - per-requirement vector
   - Missing vs Unknown
   - any source-level EC_G = (R_G, Γ_G)

D. KnowledgeState K_t
   - eight primitives
   - relations among primitives
   - K_{t+1} = Learn(K_t, Observations_t, Events_t, Policies_t, Outcomes_t)

E. Epistemic status system
   - Candidate
   - Supported
   - Accepted
   - Committed
   - source-level branched alternatives

F. Decision Contract
   - six-tuple ratified form
   - source-level seven-tuple refinement
   - Hoare-style formulations
   - admissibility conjunctions

G. Authorization relation

H. Governance algebra

I. Evidence model
   - dependency structure
   - corroboration
   - duplicate invariance
   - EXP-01 operators/properties

J. Any metric, distance, probability, entropy, KL divergence,
   integration, measure, distribution, topology, or operator formulation
   that appears in the existing material.

============================================================
5. DEFINITION TEST
============================================================

For each mathematical object produce a table:

| Object | Domain | Codomain | Definition complete? |
| Variables typed? | Assumptions | Computable? | Evidence |

Do not allow statements such as:

    "Zero is a function"

unless its domain/codomain and semantics are sufficiently specified.

Do not allow:

    "EC is total"

unless the conditions for totality are established.

Do not allow:

    "K_t is a state"

without identifying what a state contains and what constitutes equality
of two states.

============================================================
6. DERIVATION AUDIT
============================================================

For each derivation:

1. State premises.
2. State transformation/reasoning steps.
3. State conclusion.
4. Identify hidden assumptions.
5. Check every inference.
6. Attempt a counterexample.
7. Determine whether the derivation is:

   VALID DERIVATION
   VALID BUT INCOMPLETE
   RECONSTRUCTABLE
   HEURISTIC
   INVALID
   NOT ESTABLISHED

Important:

"Intuitively plausible" is NOT a valid mathematical status.

A derivation may be architecturally useful and still be mathematically
unproven.

============================================================
7. COMPUTABILITY AUDIT
============================================================

For every computationally claimed object determine:

A. Is it mathematically computable in principle?

B. If yes:
   - give a reference algorithm;
   - identify required inputs;
   - identify stopping condition;
   - identify complexity class/order if reasonably derivable;
   - identify finite/infinite requirements.

C. If only approximately computable:
   - identify approximation;
   - error assumptions;
   - convergence requirements.

D. If not computable in general:
   - state why;
   - identify restricted domains where computation is possible.

E. If computability cannot yet be determined:
   - classify NOT ESTABLISHED.

Do not confuse:

    "an LLM can produce a textual answer"

with:

    "the formal function is computable with specified semantics."

============================================================
8. STATISTICAL AUDIT
============================================================

Where probability/statistics are used, verify:

- probability space;
- random variables;
- distributions;
- independence assumptions;
- conditional independence;
- sampling assumptions;
- likelihood;
- posterior interpretation;
- calibration;
- confidence vs probability;
- support vs probability;
- epistemic confidence vs statistical confidence;
- duplicate evidence effects;
- dependence between evidence items;
- base-rate assumptions;
- aggregation validity.

Audit especially any formulation equivalent to:

    P(Proposition) = Support(Proposition) / total support

Do NOT assume this is a valid epistemic probability merely because it
produces numbers in [0,1].

Test whether:

- the values sum to one;
- the sample space is defined;
- propositions are mutually exclusive/exhaustive where required;
- the transformation has a defensible probabilistic interpretation.

If not, classify it as a heuristic scoring model rather than probability.

============================================================
9. METRIC-SPACE AUDIT
============================================================

If discrepancy is described as a metric, explicitly test:

1. non-negativity;
2. identity of indiscernibles;
3. symmetry;
4. triangle inequality.

If any condition fails, it is not a metric under the ordinary definition.

It may instead be:

- a dissimilarity;
- pseudo-metric;
- directed distance;
- weighted score;
- discrepancy measure.

Classify it correctly.

Do not rename it to make it pass.

============================================================
10. MEASURE / INTEGRATION AUDIT
============================================================

If evidence aggregation is described using measure theory, verify:

- underlying measurable space;
- sigma algebra;
- measure;
- measurable function;
- integrability;
- whether the proposed integral actually corresponds to the
  aggregation being described.

Explicitly distinguish:

    "This can be represented as an integral"

from:

    "Measure theory is required by the architecture."

The latter needs architectural evidence.

============================================================
11. BAYESIAN AUDIT
============================================================

If Bayesian reasoning appears, verify:

- prior;
- likelihood;
- posterior;
- conditioning event;
- independence assumptions;
- update equation;
- normalization;
- whether the evidence items are genuinely conditionally independent.

Pay particular attention to double-counting dependent evidence.

Do not allow a Bayesian label to substitute for the actual mathematics.

============================================================
12. ENTROPY / KL AUDIT
============================================================

If entropy or KL divergence appears, verify:

- valid probability distribution;
- common sample space;
- support conditions;
- zero-probability handling;
- orientation of KL divergence;
- interpretation.

Do NOT automatically interpret:

    entropy = "epistemic uncertainty"

or:

    KL divergence = "knowledge gap"

without establishing the mapping.

These may be useful interpretations, not mathematical identities.

============================================================
13. TOPOLOGY AUDIT
============================================================

If topology is invoked, verify:

- underlying set;
- topology;
- open/closed sets;
- boundary;
- continuity;
- closure/interior where relevant.

Do not call arbitrary conflicts "topological boundaries".

For a claim such as:

    Zero : Ω → ∂Ω

determine whether Ω and ∂Ω are mathematically defined and whether Zero
actually has that codomain.

If not:

    classify as a mathematical analogy / hypothesis,
    not a formal result.

============================================================
14. OPERATOR AUDIT
============================================================

For claims such as:

    Z : Ω → ∂Ω
    L : Ω → P(Ω)
    S : Ω × ∂Ω → G
    T : Ω → Ω

verify whether these are actually operators over mathematically defined
spaces.

Check:

- domains;
- codomains;
- closure;
- deterministic vs stochastic behavior;
- composability;
- existence;
- uniqueness where applicable.

Do not accept notation merely because it is elegant.

============================================================
15. CATEGORY-THEORETIC / FUNCTIONAL-ANALYSIS CLAIMS

If category theory, functors, morphisms, functional analysis, vector spaces,
Hilbert spaces, linearity, operators, projections, kernels, or related
terminology appears:

Perform a strict type/definition audit.

A "projection" must satisfy the properties required by the mathematical
notion being claimed.

A "kernel" must not be confused with a bounded-context or architectural
kernel.

A "functor" must actually preserve the relevant categorical structure.

If the term is metaphorical, label it metaphorical.

============================================================
16. EXP-01 RECHECK

Independently reproduce the EXP-01 reasoning.

Do NOT simply reread the verdict.

For each claimed property:

- state the exact property;
- construct test cases;
- run the operator;
- determine the result;
- inspect duplicate evidence;
- inspect dependent evidence;
- inspect corroborating evidence;
- inspect contradiction behavior.

Then compare the independent result with the historical verdict.

The historical CSV discrepancy must remain under its GN-27 adjudication.

Do not modify either historical artifact.

Also investigate PF-4:

The design apparently specifies ten criteria A–J plus Calibration,
while the executed matrix contains seven reported outcomes and includes
a "Bounded [0,1]" column not obviously matching the designed criteria.

Determine whether:

- the historical experiment is correctly represented;
- the executed subset is sufficient for the published conclusion;
- the negative conclusion follows from the tested properties only;
- any stronger claim would be invalid.

============================================================
17. SOURCE-vs-SYNTHESIS MATHEMATICS

For every important mathematical object distinguish:

SOURCE FORM
     ↓
RATIFIED SYNTHESIS
     ↓
EDITION-2 EXPLANATION

Especially audit:

- PF-1 / PF-5 Zero compression
- PF-6 status compression
- PF-7 DC six→seven tuple
- PF-8 selector compression
- PF-9 governance compression

Ask:

    Did the synthesis preserve the mathematical properties it relies on?

A compression is not automatically wrong.

It becomes a formal problem if information lost by the compression is
required to preserve a claimed invariant, transition, or computation.

============================================================
18. COMPUTATIONAL REFERENCE IMPLEMENTATIONS

Where practical, construct SMALL INDEPENDENT reference implementations
for formulas.

Examples:

- Zero on the source-level status vector;
- status transitions;
- Decision Contract predicates;
- evidence duplicate/corroboration experiments;
- proposed metrics;
- probability transformations.

These are TESTING TOOLS ONLY.

Do not write them into the KnowledgeOS repository unless separately
authorized.

For each computational test report:

- input;
- expected mathematical result;
- computed result;
- pass/fail;
- assumptions;
- numerical caveats.

============================================================
19. COUNTEREXAMPLE SEARCH

For every major theorem-like or invariant-like mathematical claim:

attempt to break it.

At minimum search for:

- empty set;
- singleton;
- duplicate evidence;
- contradictory evidence;
- dependent evidence;
- missing values;
- conflicting statuses;
- zero probabilities;
- infinite/unbounded cases;
- malformed inputs;
- policy changes;
- ambiguous propositions;
- multiple equally valid decisions.

A single counterexample does not necessarily invalidate the architecture,
but it does invalidate the universal claim under the tested assumptions.

Record scope precisely.

============================================================
20. ARCHITECTURAL ALIGNMENT

After the mathematical audit, compare the mathematics with the architecture.

For each mathematical claim classify:

MATHEMATICALLY CORRECT + ARCHITECTURALLY ALIGNED
MATHEMATICALLY CORRECT + ARCHITECTURALLY ONLY INTERPRETIVE
MATHEMATICALLY INCOMPLETE
MATHEMATICALLY INVALID
COMPUTATIONALLY UNREALIZED
COMPUTATIONALLY POSSIBLE BUT UNSPECIFIED
ARCHITECTURALLY UNAUTHORIZED
NOT ESTABLISHED

Do not collapse these categories.

For example:

A formula may be mathematically valid but not part of the architecture.

An architectural object may be well-defined conceptually but have no
computable realization.

A computation may work but compute a different quantity than the formula
claims.

These are different findings.

============================================================
21. SPECIAL AUDIT OF THE BOOK

Inspect all completed Edition-2 Part III chapters for phrases such as:

- "therefore"
- "hence"
- "must"
- "is equivalent to"
- "can be computed"
- "is a metric"
- "is a probability"
- "is Bayesian"
- "proves"
- "guarantees"
- "optimal"
- "minimal"
- "complete"
- "unique"

For each occurrence determine whether the source actually supports the
strength of the statement.

Flag epistemic inflation.

============================================================
22. DELIVERABLES

Create:

analysis/mathematical-verification-report.md

and, if needed for reproducibility:

analysis/mathematical-tests/

with small test artifacts ONLY.

Do not modify the book or architecture.

The report must contain:

1. Executive Summary
2. Scope and Authority
3. Mathematical Object Inventory
4. Definition Audit
5. Derivation Audit
6. Computability Audit
7. Statistical Audit
8. Measure/Integration Audit
9. Metric Audit
10. Probability/Bayesian Audit
11. Entropy/KL Audit
12. Topology Audit
13. Operator Audit
14. EXP-01 Independent Recheck
15. Source-vs-Synthesis Compression Audit
16. Counterexample Results
17. Computational Reference Tests
18. Architecture Alignment Matrix
19. Book Epistemic-Strength Audit
20. Findings Register
21. Severity Assessment
22. Open Questions Created or Deepened
23. What Passed
24. What Failed
25. What Remains NOT ESTABLISHED
26. Recommended Governance Actions

============================================================
23. SEVERITY

Use:

CRITICAL
Major claim mathematically invalid or architecture relies on it.

HIGH
Major formal object under-specified, non-computable claim, or substantial
statistical error.

MEDIUM
Important mathematical ambiguity, incomplete derivation, or unsupported
interpretation.

LOW
Notation, terminology, local formal precision issue.

OBSERVATION
Interesting mathematical relationship requiring no current action.

============================================================
24. NO SILENT REPAIR

This is the most important rule.

If you discover:

- an incorrect equation;
- invalid derivation;
- invalid metric;
- invalid probability interpretation;
- impossible computation;
- wrong codomain;
- hidden assumption;
- counterexample;
- statistical error;

DO NOT CORRECT IT.

Report:

WHAT IS WRONG
WHY
COUNTEREXAMPLE / CALCULATION
SOURCE
AFFECTED ARCHITECTURE
AFFECTED BOOK CHAPTER
SEVERITY
POSSIBLE REPAIR (OPTIONAL, CLEARLY NON-AUTHORITATIVE)

The proposed repair must never be presented as approved architecture.

============================================================
25. FINAL VERDICT VOCABULARY

At book/architecture level use:

MATHEMATICALLY SOUND
MATHEMATICALLY SOUND WITH QUALIFICATIONS
MATHEMATICALLY UNDER-SPECIFIED
MATHEMATICALLY UNSUPPORTED
MATHEMATICALLY INVALID
COMPUTABLE
COMPUTABLE UNDER RESTRICTIONS
APPROXIMATELY COMPUTABLE
COMPUTABILITY NOT ESTABLISHED
NOT COMPUTATIONALLY REALIZED
STATISTICALLY SOUND
STATISTICALLY UNSUPPORTED
ARCHITECTURALLY ALIGNED
ARCHITECTURALLY INTERPRETIVE
ARCHITECTURALLY UNAUTHORIZED

Never use "validated".

============================================================
26. FINAL STOP CONDITION

After completing the audit:

STOP.

Do not:

- rewrite chapters;
- modify mathematical formulas in the book;
- modify architecture;
- resolve OQs;
- incorporate DeepSeek;
- create v0.3;
- approve any mathematical theory.

The purpose is to determine the truth status of the mathematics,
statistics, derivations and computation.

The final report must make a clear distinction between:

    mathematical correctness
    mathematical completeness
    computational possibility
    computational realization
    statistical validity
    architectural authorization
    book-level explanation

That distinction is mandatory.
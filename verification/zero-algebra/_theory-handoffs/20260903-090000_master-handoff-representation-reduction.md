Yes. **But I would not give Claude only the old KR-ZERO corpus and ask it to continue.** The situation you discovered is exactly why we should first give Claude the **current theory and the corrected research state**, explicitly marking the old KR-ZERO work as a **prior experiment under an earlier conceptual model**.

The key distinction is:

> **KR-ZERO-ORDER-2026-09 is evidence about the old representation of the Zero problem. It is not evidence that the new `R⁵ → R²` representation-reduction theory has been experimentally tested.**

So Claude should **not retrofit the old R1–R4 representation classes into the new reduction hierarchy**.

Below is the prompt I recommend giving Claude Code CLI. It contains the theory, the methodological boundaries, and precise instructions for auditing the old work before starting the new `R⁵ → R²` experiment.

---

# MASTER RESEARCH HANDOFF — KNOWLEDGEOS REPRESENTATION REDUCTION THEORY

Copy everything below into Claude Code CLI as the research handoff.

```text
# KNOWLEDGEOS RESEARCH HANDOFF
# Representation Reduction Under Preservation Contracts
# Research state: 2026-09-03
#
# PURPOSE
#
# This document is the authoritative conceptual handoff for the next phase
# of the KnowledgeOS research program.
#
# IMPORTANT:
# The repository contains earlier KR-ZERO experiments that were conducted
# before the current representation-reduction theory was fully formulated.
#
# DO NOT retroactively reinterpret old KR-ZERO representation labels
# as the new R^5 -> R^2 reduction hierarchy.
#
# The new theory requires a NEW generating experiment.
#
# ============================================================
# 1. CENTRAL RESEARCH QUESTION
# ============================================================
#
# The central question is:
#
#   How much representation can be eliminated while remaining in the
#   same inquiry-relevant equivalence class and satisfying the
#   preservation contract?
#
# More specifically:
#
#   Given a source representation D, an inquiry Q, constraints C,
#   decoder/operator O, and transformation family T,
#   how far can representation be reduced before preservation fails?
#
# This is NOT fundamentally a question about digit count.
#
# The original numerical analogy (e.g. reducing a 5-digit representation
# to a 2-digit representation) is a motivating example.
#
# The research object is more general:
#
#   representation reduction under a preservation contract.
#
# ============================================================
# 2. CORE CONCEPT: PRESERVATION CONTRACT
# ============================================================
#
# Let:
#
#   X = source sample space
#   D ∈ X = source representation/data object
#
# Let a preservation/inquiry contract be represented by:
#
#   Π = (Q, C, O)
#
# where:
#
#   Q = inquiry / target information that must be preserved
#   C = constraints defining admissibility
#   O = decoder / interpretation / realization operator
#
# A transformation:
#
#   T : X -> Y
#
# produces:
#
#   R = T(D)
#
# The fundamental question is not whether T preserves D itself.
#
# The question is whether T preserves what the contract requires.
#
# ============================================================
# 3. ADEQUACY
# ============================================================
#
# Define the admissible domain:
#
#   X_{Π,T} = { D ∈ X : C(T(D)) = 1 }
#
# A representation T(D) is adequate for inquiry Q when:
#
#   H(Q(D) | T(D)) = 0
#
# in the theoretical/distributional setting.
#
# Equivalently, there exists an optimal decoder O* such that:
#
#   P(O*(T(D)) = Q(D)) = 1
#
# under the relevant distribution.
#
# IMPORTANT:
#
# Empirical experiments must distinguish:
#
#   empirical adequacy
#
# from:
#
#   theoretical/population adequacy.
#
# For a finite observed dataset:
#
#   H_hat(Q | R) = 0
#
# means no observed R-fiber contains conflicting Q values.
#
# This is evidence about the observed population, not proof of
# generalization to an unseen population.
#
# ============================================================
# 4. REALIZATION
# ============================================================
#
# Adequacy and realization are distinct.
#
# A fixed decoder/operator O realizes the contract when:
#
#   O(T(D)) = Q(D)
#
# on admissible cases.
#
# Define:
#
#   A(T) = indicator that admissibility/contract constraints hold
#
#   F(T) = fraction of cases for which
#           O(T(D)) = Q(D)
#
# Therefore:
#
#   H(Q|T)=0
#
# does NOT by itself imply:
#
#   F(T)=1
#
# because an optimal decoder may exist even when the specified/fixed
# decoder O does not realize it.
#
# This distinction must remain explicit in all experiments.
#
# ============================================================
# 5. FIBER SEPARATION
# ============================================================
#
# If T is adequate, then Q must be constant on every T-fiber.
#
# For observed finite data define:
#
#   N_viol(R)
#
# as the number of conflicting pairs:
#
#   N_viol(R)
#   =
#   #{(D_a,D_b):
#       R(D_a)=R(D_b)
#       and
#       Q(D_a) != Q(D_b)}
#
# Then:
#
#   N_viol(R)=0
#
# iff the observed R-fibers contain no conflicting Q values.
#
# Under the standard empirical distribution, this is equivalent to:
#
#   H_hat(Q|R)=0
#
# Therefore:
#
#   N_viol = structural audit
#   H_hat(Q|R) = information-theoretic summary
#
# They should both be reported.
#
# ============================================================
# 6. INFORMATION-THEORETIC EXCESS
# ============================================================
#
# For adequate deterministic T:
#
#   H(Q|T)=0
#
# and therefore:
#
#   H(T) = H(Q) + H(T|Q)
#
# Thus:
#
#   H(T) >= H(Q)
#
# The difference:
#
#   H(T|Q)
#
# measures representation information that is not determined by Q.
#
# Do NOT call this objectively "superfluous information".
#
# Correct terminology:
#
#   inquiry-extraneous representation relative to Q
#
# because what is extraneous depends on the inquiry/contract.
#
# ============================================================
# 7. THEOREM 1 — ADEQUACY
# ============================================================
#
# For a transformation T:
#
#   Adequacy:
#
#       H(Q(D)|T(D)) = 0
#
# iff there exists a decoder O* such that:
#
#       P(O*(T(D)) = Q(D)) = 1
#
# under the relevant distribution.
#
# For a fixed decoder O, probabilistic realization additionally requires:
#
#   C(T(D)) = 1
#
# and:
#
#   O(T(D)) = Q(D)
#
# almost surely.
#
# ============================================================
# 8. THEOREM 2 — FACTORIZATION / FIBER PRESERVATION
# ============================================================
#
# Pointwise fiber preservation is characterized by the existence of a
# factor map:
#
#   Q = O* ∘ T
#
# on the admissible domain.
#
# More precisely:
#
#   Q|_{X_{Π,T}} = O* ∘ T
#
# for some:
#
#   O* : T(X_{Π,T}) -> Q
#
# This means Q factors through T.
#
# IMPORTANT:
#
# This is NOT automatically a bijection.
#
# Do NOT call this "Q-isomorphic" unless actual bijectivity has been
# established on an explicitly defined image/quotient.
#
# Preferred terminology:
#
#   Q-equivalent
#
# or:
#
#   Q-factorized
#
# depending on context.
#
# ============================================================
# 9. THEOREM 3 — INFORMATION LOWER BOUND
# ============================================================
#
# Let:
#
#   T* ∈ argmin_{T ∈ T_adequate(Π)} H(T(D))
#
# whenever the minimum exists.
#
# Then:
#
#   H(T*) >= H(Q)
#
# Equality holds iff there exists an adequate transformation satisfying:
#
#   H(T|Q) = 0
#
# For deterministic T:
#
#   H(T|Q)=0
#
# iff:
#
#   I(T;D|Q)=0
#
# Therefore the theoretical information-theoretic optimum is:
#
#   H(T*) = H(Q)
#
# together with adequacy.
#
# IMPORTANT:
#
# Do not assume argmin existence.
# If a minimum is not guaranteed, use an infimum.
#
# ============================================================
# 10. CONTRACTUALLY ELIMINABLE SET
# ============================================================
#
# Let:
#
#   E_S : X -> X
#
# be an elimination operator associated with a candidate subset S.
#
# Do NOT assume:
#
#   E_S(D) = D \ S
#
# unless the representation makes this mathematically valid.
#
# Define:
#
#   Elim_{T,Π}(D)
#
# as:
#
#   { S :
#       D ∈ X_{Π,T}
#       and
#       E_S(D) ∈ X_{Π,T}
#       and
#       O(T(D)) = O(T(E_S(D))) = Q(D)
#   }
#
# This defines contractual eliminability.
#
# ============================================================
# 11. ZERO
# ============================================================
#
# Zero is NOT an algebraic zero element.
#
# Zero is a transformation-relative preservation relation.
#
# General form:
#
#   Zero_{T,Π}(S;D)
#
# iff:
#
#   Π(T(D)) = Π(T(E_S(D)))
#
# operationally understood as preservation of everything the contract
# requires.
#
# A convenient information-theoretic formulation is:
#
#   Zero_{T,Π}(S;D)
#   iff
#   Q(T(D)) = Q(T(E_S(D)))
#
# only where the notation is type-correct and the contract supports it.
#
# More generally use the preservation predicate directly.
#
# IMPORTANT:
#
# Zero is:
#
#   context-dependent
#   transformation-dependent
#   contract-dependent
#   representation-dependent
#   potentially subset/group-dependent
#
# Therefore:
#
#   local eliminability != general eliminability
#
# ============================================================
# 12. ZERO IS NOT NECESSARILY ELEMENT-WISE
# ============================================================
#
# Earlier experiments demonstrated a crucial phenomenon:
#
#   Zero(a1;D) AND Zero(a2;D)
#
# does NOT imply:
#
#   Zero({a1,a2};D)
#
# Example under deduplication:
#
#   D = [a,a,b,b]
#
# individual occurrences can be eliminable while eliminating both
# occurrences of a simultaneously changes the representation.
#
# Therefore eliminability may be relational.
#
# This means:
#
#   Zero(S;D)
#
# must remain defined over subsets/groups where appropriate.
#
# ============================================================
# 13. HIGHER-ORDER ZERO EXPERIMENT
# ============================================================
#
# Earlier experiment:
#
#   KR-ZERO-ORDER-2026-09
#
# investigated whether Zero-status at subset size/order k could be
# inferred from lower-order Zero-status.
#
# Published aggregate:
#
#   k=1: 1252 / 1395 = 89.7%
#   k=2: 25 / 1395 = 1.8%
#   k=3: 3 / 1395 = 0.2%
#   irreducible: 115 / 1395 = 8.24%
#
# Additional observations:
#
#   O2:
#   irreducible approximately 8.49% after removing a cancelling contract
#
#   O3 transformation dependence:
#     T1 stopword             0%
#     T2 dedup               31.46%
#     T3 normalize            0.44%
#     T4 context             11.50%
#     T5 reference            0%
#     T6 meta-preserving      0.48%
#     T7 meta-destroying      0%
#     T8 interacting         20.59%
#
# O5:
#
#   387 monotone
#   4 non-monotone
#
# Therefore:
#
#   k(m+1) >= k(m)
#
# is NOT a universal law.
#
# Interaction order is a local property of an elimination problem,
# not a global complexity rank.
#
# The correct interpretation is:
#
#   Zero is predominantly singleton-determined in this experiment,
#   but a non-negligible class is not.
#
# Do NOT conclude:
#
#   "Zero is fundamentally higher-order."
#
# ============================================================
# 14. IMPORTANT STATUS OF KR-ZERO CORPUS
# ============================================================
#
# A previous audit established:
#
#   property-results.json
#
# is aggregate only.
#
# The original 1,395 case-level rows were not persisted.
#
# Only:
#
#   481 irreducible witnesses
#
# plus:
#
#   14 illustrative records
#
# had originally been saved.
#
# However:
#
#   emit_corpus.py
#
# is a deterministic generator with a recorded seed.
#
# It was used to materialize:
#
#   corpus/cases.jsonl
#   corpus/cases.csv
#
# containing:
#
#   1,395 records
#
# and:
#
#   corpus/subsets.jsonl
#
# containing:
#
#   14,194 subset-level records.
#
# The generator reproduces the published aggregates:
#
#   1395 total
#   {1:1252, 2:25, 3:3, irreducible:115}
#
# This makes the old KR-ZERO corpus reconstructible.
#
# IMPORTANT METHODOLOGICAL LESSON:
#
# The population should have been persisted.
#
# Reconstruction is acceptable here only because:
#
#   deterministic generator + recorded seed + aggregate verification
#
# make it reproducible.
#
# A stochastic or externally sourced corpus could not necessarily be
# recovered.
#
# ============================================================
# 15. CRITICAL WARNING ABOUT KR-ZERO R1-R4
# ============================================================
#
# The old KR-ZERO experiment contains labels:
#
#   R1
#   R2
#   R3
#   R4
#
# These are REPRESENTATION CLASSES.
#
# They describe categories such as:
#
#   token sequence
#   token sequence + metadata
#   graph
#   structured claim
#
# They are NOT:
#
#   R5 -> R4 -> R3 -> R2 -> R1
#
# reduction levels.
#
# There is a dangerous naming collision.
#
# NEVER construct a representation hierarchy from those labels.
#
# NEVER claim that the old KR-ZERO experiment tested:
#
#   R5 -> R4 -> R3 -> R2 -> R1
#
# It did not.
#
# A grep/audit found no evidence for:
#
#   Q(D)
#   R5
#   C(Rn)
#   O(Rn)
#   representation_hierarchy
#   reduction_level
#
# in the old KR-ZERO corpus artifacts.
#
# Therefore:
#
#   KR-ZERO-ORDER != KR-REP-DATASET
#
# ============================================================
# 16. NEW RESEARCH OBJECT
# ============================================================
#
# The new research object is:
#
#   𝒵(D,S,R,I,C,M,T,Π,W)
#
# where:
#
#   D = source data
#   S = candidate eliminated subset
#   R = representation
#   I = inquiry
#   C = constraints
#   M = reasoning/method regime
#   T = transformation
#   Π = preservation contract
#   W = Zero witness / provenance information
#
# More generally:
#
#   𝒦_Π = (X, 𝒯, 𝓔, ~_Π, Q)
#
# where:
#
#   X       = source sample space
#   𝒯       = admissible transformations
#   𝓔       = elimination operators
#   ~_Π     = contract-relative equivalence
#   Q       = inquiry
#
# ============================================================
# 17. THE NEW R^5 -> R^2 IDEA
# ============================================================
#
# The digit-reduction example is a motivating test case.
#
# We want to investigate a representation chain such as:
#
#   D -> R5 -> R4 -> R3 -> R2
#
# where:
#
#   D
#
# is the original representation and:
#
#   R5
#   R4
#   R3
#   R2
#
# are actual progressively reduced representations.
#
# IMPORTANT:
#
# These MUST be generated by an explicit reduction experiment.
#
# They cannot be inferred from old KR-ZERO representation classes.
#
# The research question is:
#
#   At which reduction stage does the representation cease to preserve
#   the inquiry Q under contract Π?
#
# ============================================================
# 18. SEQUENTIAL REDUCTION PROTOCOL
# ============================================================
#
# Correct chain:
#
#   R5 = T5(D)
#   R4 = T4(R5)
#   R3 = T3(R4)
#   R2 = T2(R3)
#   R1 = T1(R2)
#
# if a five-stage chain is required.
#
# Do NOT use the incorrect indexing:
#
#   Rn = Tn(Rn+1)
#
# with R6=D unless the entire indexing convention is explicitly defined
# consistently.
#
# The reduction direction must always be explicit.
#
# ============================================================
# 19. DO NOT REQUIRE MONOTONIC ENTROPY
# ============================================================
#
# Representation reduction does NOT necessarily imply:
#
#   H(R5) > H(R4) > H(R3) > H(R2)
#
# because representation capacity, encoding size, cardinality and
# Shannon entropy are different quantities.
#
# Measure them separately.
#
# Possible metrics:
#
#   representation capacity
#   encoded storage size
#   cardinality
#   Shannon entropy
#   H(Q|R)
#   H(R|Q)
#
# ============================================================
# 20. EXPERIMENTAL METRIC VECTOR
# ============================================================
#
# At every reduction stage Rn record:
#
#   M(Rn) =
#     (
#       A_n,
#       F_n,
#       H_hat(Q|Rn),
#       H_hat(Rn|Q),
#       H_hat(Rn),
#       N_viol(Rn),
#       n
#     )
#
# where:
#
#   A_n = contract admissibility
#   F_n = fixed decoder/operator realization
#   H_hat(Q|Rn) = empirical inquiry uncertainty
#   H_hat(Rn|Q) = inquiry-extraneous representation
#   H_hat(Rn) = representation entropy
#   N_viol(Rn) = conflicting Q pairs within same Rn fiber
#   n = reduction level
#
# ============================================================
# 21. FOUR DIAGNOSTIC QUESTIONS
# ============================================================
#
# A. SUFFICIENCY
#
# Does Rn preserve Q?
#
# Measure:
#
#   N_viol(Rn)
#   H_hat(Q|Rn)
#
# ------------------------------------------------------------
#
# B. CONTRACT REALIZATION
#
# Does the specified operator O actually realize Q?
#
# Measure:
#
#   A_n
#   F_n
#
# ------------------------------------------------------------
#
# C. REPRESENTATIONAL EXCESS
#
# How much representation remains that is not determined by Q?
#
# Measure:
#
#   H_hat(Rn|Q)
#
# Use terminology:
#
#   inquiry-extraneous relative to Q
#
# ------------------------------------------------------------
#
# D. REDUCTION BOUNDARY
#
# How far can representation be reduced before preservation fails?
#
# Determine:
#
#   last adequate stage
#
# and:
#
#   first inadequate stage
#
# together with the transition:
#
#   B_n : R_n -> R_{n-1}
#
# IMPORTANT:
#
# If sequential adequacy is non-monotone, do NOT define the boundary
# merely as the minimum adequate n.
#
# Instead explicitly identify:
#
#   last adequate -> first inadequate transition.
#
# ============================================================
# 22. MINIMAL ADEQUATE REPRESENTATION
# ============================================================
#
# For an ordered reduction chain, define the lowest adequate stage
# according to the direction of reduction.
#
# If lower n means more reduced:
#
#   n* = min { n :
#              A(Rn)=1
#              AND
#              N_viol(Rn)=0
#              AND
#              H_hat(Q|Rn)=0
#            }
#
# only when the reduction ordering and adequacy behavior justify this.
#
# If adequacy is non-monotone, report the complete adequacy profile
# instead of pretending that one n* captures the boundary.
#
# ============================================================
# 23. REPRESENTATION BOUNDARY VS CONTRACT BOUNDARY
# ============================================================
#
# Distinguish:
#
# REPRESENTATION BOUNDARY:
#
#   N_viol(R) > 0
#
# or:
#
#   H(Q|R) > 0
#
# meaning Q cannot be recovered from the representation.
#
# CONTRACT BOUNDARY:
#
#   A(R) < 1
#
# meaning contract constraints are violated.
#
# OPERATOR BOUNDARY:
#
#   H(Q|R)=0
#
# but:
#
#   F < 1
#
# meaning the information is present but the specified operator does not
# realize it.
#
# These are different failure modes.
#
# ============================================================
# 24. EMPIRICAL VS THEORETICAL CLAIMS
# ============================================================
#
# Never silently promote:
#
#   H_hat(Q|R)=0
#
# into:
#
#   H(Q|R)=0
#
# The former is an empirical observation on the tested dataset.
#
# The latter is a theoretical/population claim.
#
# Similarly:
#
#   F=1
#
# on observed cases does not establish universal realization.
#
# Held-out evaluation should be considered where generalization matters.
#
# ============================================================
# 25. FIVE-DIGIT -> TWO-DIGIT ANALOGY
# ============================================================
#
# The phrase:
#
#   "reduce a 5-digit representation to a 2-digit representation"
#
# is NOT itself the theoretical result.
#
# Digit count is only one representation-capacity measure.
#
# The proper experiment asks:
#
#   Can R2 preserve Q?
#
# by testing:
#
#   H(Q|R2)
#   N_viol(R2)
#   F(R2)
#   A(R2)
#
# and separately:
#
#   H(R2)
#   H(R2|Q)
#   encoding/storage cost
#
# A two-digit representation may have a larger information capacity than
# a five-digit representation depending on the encoding and alphabet.
#
# Therefore "2 digits" must never be treated as automatically less
# informative without specifying the carrier and encoding.
#
# ============================================================
# 26. CARRIER QUESTION
# ============================================================
#
# One of the deepest unresolved questions is:
#
#   What is the carrier on which eliminability actually lives?
#
# Candidate carriers include:
#
#   subset S ⊆ D
#   (D,S,context)
#   relational structure
#   graph/hypergraph
#   rewriting state
#   structured representation
#   combination of these
#
# DO NOT assume the carrier before experimentation establishes it.
#
# ============================================================
# 27. ALGEBRAIC CAUTION
# ============================================================
#
# Earlier formulations proposed:
#
#   T_R(D) = Inv_T(D) ⊕ Δ_R(D) ⊕ Rem_T(D)
#
# This should NOT be treated as established.
#
# A safer representation is:
#
#   T_R(D) -> (Inv_T(D), Δ_T(D), Rem_T(D))
#
# as transformation observables.
#
# Similarly, do not assume:
#
#   Δ_R(D) = D - R
#
# unless D and R live in a suitable algebra.
#
# Use a typed difference operator:
#
#   Diff_R : D_R × R_R -> Δ_R
#
# where necessary.
#
# ============================================================
# 28. PROJECTION CAUTION
# ============================================================
#
# An elimination/retention operator must not automatically be called
# a projection.
#
# Projection normally suggests idempotence.
#
# Unless:
#
#   L(L(D)) = L(D)
#
# has been established, use:
#
#   elimination operator
#
# or:
#
#   retention operator
#
# instead.
#
# Also test:
#
#   idempotence
#   order dependence
#   commutativity
#   fixed-point convergence
#
# ============================================================
# 29. ZERO COMPOSITIONALITY
# ============================================================
#
# A major experimental question is whether Zero is compositional.
#
# Test:
#
#   Zero(S1;D)
#   Zero(S2;D)
#
# versus:
#
#   Zero(S1 ∪ S2;D)
#
# and investigate:
#
#   order dependence
#   group interactions
#   fixed points
#   representation changes
#
# The dedup witness already shows that naive element-wise composition
# fails.
#
# ============================================================
# 30. WHAT THE OLD KR-ZERO EXPERIMENT ACTUALLY TELLS US
# ============================================================
#
# It provides evidence for:
#
#   context dependence
#   transformation dependence
#   relational/group eliminability
#   non-universal compositionality
#   local higher-order interaction
#
# It does NOT provide evidence for:
#
#   the R5 -> R4 -> R3 -> R2 reduction hierarchy
#   sequential representation reduction
#   minimal adequate representation in that hierarchy
#   information-theoretic optimum of the new hierarchy
#   digit-system reduction
#
# Therefore it is PRIOR EVIDENCE, not the new experiment.
#
# ============================================================
# 31. NEW EXPERIMENT REQUIRED
# ============================================================
#
# Create a new experiment, tentatively:
#
#   KR-REP-REDUCTION-2026-09
#
# or another repository-approved name.
#
# Its purpose:
#
#   experimentally construct an actual ordered representation hierarchy
#   and measure preservation under Π at every stage.
#
# Do NOT reuse old KR-ZERO R1-R4 labels as reduction levels.
#
# ============================================================
# 32. EXPERIMENT DESIGN REQUIREMENTS
# ============================================================
#
# Before implementing, define:
#
#   1. Source carrier X
#   2. Source representation D
#   3. Inquiry Q
#   4. Constraints C
#   5. Decoder/operator O
#   6. Transformation family T5...T1
#   7. Representation carriers R5...R1
#   8. Encoding assumptions
#   9. Dataset-generation process
#   10. Random seed if stochastic
#   11. Population size
#   12. Held-out evaluation strategy
#   13. Metrics
#   14. Failure criteria
#   15. Persistence format
#
# ============================================================
# 33. DATASET PERSISTENCE REQUIREMENT
# ============================================================
#
# The NEW experiment MUST persist the full population.
#
# Never persist only:
#
#   failures
#   irreducible cases
#   witnesses
#   examples
#
# Persist:
#
#   every generated source case
#   every reduction stage
#   every relevant transformation
#   every Q value
#   every contract evaluation
#   every Zero/elimination result where applicable
#   provenance/seed
#
# Aggregate results are NEVER a substitute for the case-level corpus.
#
# ============================================================
# 34. CASE-LEVEL GRAIN MUST BE EXPLICIT
# ============================================================
#
# Do not confuse:
#
#   case
#   representation level
#   subset
#   transformation
#   contract
#   witness
#
# Each dataset must explicitly define its grain.
#
# Example:
#
#   one case = (D,Q,Π, reduction chain)
#
# with nested level records:
#
#   (case_id,n,Rn,Tn,metrics)
#
# and, where necessary:
#
#   subset-level elimination records.
#
# ============================================================
# 35. REPRODUCIBILITY
# ============================================================
#
# Every generated experiment must record:
#
#   generator version
#   git commit
#   seed
#   generation parameters
#   transformation version
#   contract version
#   schema version
#
# The aggregate results must be independently reproducible from the
# persisted case-level corpus or deterministic generator.
#
# ============================================================
# 36. RESEARCH DISCIPLINE
# ============================================================
#
# Follow these rules strictly:
#
# RULE 1:
# Inspect before hypothesizing.
#
# RULE 2:
# Never infer a hierarchy from naming alone.
#
# RULE 3:
# Never fabricate missing case-level data.
#
# RULE 4:
# Separate old experiments from new theoretical constructs.
#
# RULE 5:
# Separate empirical results from mathematical claims.
#
# RULE 6:
# Separate adequacy from realization.
#
# RULE 7:
# Separate entropy from representation size/capacity.
#
# RULE 8:
# Never assume algebraic structure before proving/observing it.
#
# RULE 9:
# Preserve negative results and falsifications.
#
# RULE 10:
# Persist the full experimental population.
#
# ============================================================
# 37. CLAUDE CODE TASK
# ============================================================
#
# You are now acting as RESEARCH ENGINEER + PRODUCTION AUDITOR.
#
# DO NOT immediately implement the new experiment.
#
# First perform an evidence audit.
#
# PHASE A — INVENTORY
#
# Locate:
#
#   corpus/cases.jsonl
#   corpus/cases.csv
#   corpus/subsets.jsonl
#   property-results.json
#   emit_corpus.py
#   CORPUS-AUDIT.md
#
# Also locate all relevant:
#
#   KR-ZERO
#   KR-ZERO-ORDER
#   KR-ZERO-MECHANISM
#   KR-REP
#   representation reduction
#   digit reduction
#   Zero theory
#
# artifacts.
#
# Do not assume filenames.
#
# PHASE B — CLASSIFY ARTIFACTS
#
# For every relevant artifact determine:
#
#   artifact
#   purpose
#   experiment
#   conceptual version
#   data grain
#   schema
#   whether raw/generated/aggregate
#   whether reproducible
#   whether relevant to new theory
#
# PHASE C — VERIFY OLD KR-ZERO
#
# Verify:
#
#   1395 total cases
#   k=1 1252
#   k=2 25
#   k=3 3
#   irreducible 115
#
# Verify these against:
#
#   cases.jsonl
#   subsets.jsonl
#   property-results.json
#   emit_corpus.py
#
# Report discrepancies.
#
# PHASE D — PROTECT AGAINST R1-R4 COLLISION
#
# Explicitly prove/document that old:
#
#   R1,R2,R3,R4
#
# are representation classes and are NOT the new:
#
#   R5,R4,R3,R2,R1
#
# reduction hierarchy.
#
# Do not create an adapter unless there is actual evidence that the
# representations form an ordered reduction chain.
#
# PHASE E — THEORY-TO-CORPUS GAP
#
# Determine which elements of the new theory already exist in the
# repository and which do not.
#
# Produce a matrix:
#
#   theoretical construct
#   repository artifact
#   evidence
#   status
#
# Status must be one of:
#
#   IMPLEMENTED
#   PARTIAL
#   EMPIRICALLY TESTED
#   THEORETICAL ONLY
#   ABSENT
#   CONFLICTING
#
# PHASE F — NEW EXPERIMENT DESIGN
#
# ONLY after the audit, design:
#
#   KR-REP-REDUCTION-2026-09
#
# Do not implement until the design has been written and internally
# checked.
#
# ============================================================
# 38. REQUIRED FINAL AUDIT REPORT
# ============================================================
#
# Produce:
#
#   docs/.../CORPUS-THEORY-AUDIT.md
#
# containing:
#
# 1. What the old KR-ZERO corpus actually contains
# 2. What it does not contain
# 3. Verification of 1395 population
# 4. Verification of published aggregates
# 5. Verification of reconstruction
# 6. Exact meaning of old R1-R4
# 7. Exact definition of new R5-R2 concept
# 8. Theory-to-artifact mapping
# 9. Missing experimental infrastructure
# 10. Proposed new experiment
# 11. Risks of conceptual contamination
# 12. Open questions
#
# ============================================================
# 39. STOP CONDITIONS
# ============================================================
#
# STOP and report if:
#
#   - a claimed dataset does not exist
#   - a field exists only by name but has no populated values
#   - an R5-R2 hierarchy cannot be demonstrated
#   - old R1-R4 labels are being treated as reduction levels
#   - an aggregate is being mistaken for case-level data
#   - a theoretical claim lacks experimental evidence
#   - a transformation is assumed to be algebraic without justification
#   - the proposed carrier is unsupported
#
# Do NOT silently repair these by inventing data or semantics.
#
# ============================================================
# 40. FIRST RESPONSE REQUIRED FROM CLAUDE
# ============================================================
#
# Before modifying code, return:
#
#   A. Repository inventory
#   B. Relevant artifact table
#   C. Old KR-ZERO corpus verification
#   D. R1-R4 semantic verification
#   E. Theory-to-artifact gap matrix
#   F. Evidence for/against an existing R5->R2 hierarchy
#   G. Recommendation for the new experiment
#
# No implementation yet.
#
# The purpose of this phase is epistemic hygiene:
#
#   INSPECT -> CLASSIFY -> VERIFY -> MAP -> DESIGN -> IMPLEMENT
#
# Never:
#
#   ASSUME -> ADAPT -> FABRICATE
#
# ============================================================
# END MASTER HANDOFF
# ============================================================
```

## One important thing I would change from our previous direction

I **would not ask Claude to inspect `cases.jsonl` as if it were the dataset for the new R⁵→R² theory**.

Instead, tell Claude:

> **“Audit `cases.jsonl` as historical evidence. Determine exactly what experiment it represents. Then determine whether the current R⁵→R² theory has an existing empirical implementation. If not, design a new experiment.”**

That is much safer.

The conceptual lineage is:

**Vedic-inspired transformations**
↓
**Zero as transformation-relative eliminability**
↓
**KR-ZERO experiments**
↓
**discovery of context/interaction/order dependence**
↓
**representation-reduction theory**
↓
**preservation contracts + adequacy + realization + information excess**
↓
**actual ordered reduction experiment R⁵ → R⁴ → R³ → R²**
↓
**measure the preservation boundary**

The old KR-ZERO experiment is therefore **not wasted at all**. It supplies important empirical constraints on the theory. But it must not be allowed to masquerade as the experiment that tests the new reduction hierarchy. 

And I think the most important thing Claude should discover next is **whether the R⁵→R² hierarchy has actually been defined somewhere in the repository already, or whether we are currently at the point where it needs to be designed from the theory.**

That is precisely why the first Claude run above is an **audit, not an implementation**.

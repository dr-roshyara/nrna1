# Claim-to-evidence matrix: S-Series theory (as of 2026-10-18 05:46)

**Independence key**
- **INT:** internally reviewed by a same-model-family sub-agent.
- **INT-IND-CODE:** the same, plus an independently written implementation.
- **EXT:** externally reviewed. *No claim has reached EXT.*
- **PRIM:** verified against a KnowledgeOS primary source. *No claim has reached PRIM.*

**Evidence key**
- **PROOF:** a mathematical proof.
- **CE:** an explicit counterexample.
- **FIN:** a finite computational check.
- **ANA:** an analytic argument.
- **EXPL:** exploratory.
- **LIT-U:** literature, unverified.
- **ARCH:** an architectural proposal.
- **STAKE:** a stakeholder decision.

## A. Deterministic core

| Claim | Evidence | Independence | Main report | Limits |
|---|---|---|---|---|
| The kernel is the Nerode quotient of the required decisions; unique for trim realizations | PROOF | INT | 1850 C-6 | determinism, input completeness, observable definedness |
| Behavioural equivalence is undecidable in general; even for one fixed polynomial-time decision function | PROOF (sketch) | INT | 1850 C-1/C-1⁺ | stated as a promise problem |
| R2: a greatest admissible element exists ⇔ ⋁𝒜 ∈ 𝒜 | PROOF | INT | 1850 | wording disagreement U-1 open |
| R4: upward-closed policies inert; separation = an extra decision | PROOF | INT | 1850 | partition-level policies only |
| A2–A5: quotient-closed update classes inert; injective infinite-state updates break uniqueness | PROOF + CE | INT | 1830 | infinite-state only |
| B2: hereditary decoder restrictions inert | PROOF | INT | 1830 | unrestricted updates |
| E2: the cap policy has 6 + 141 maximal elements (3 and 4 states) | FIN (3 implementations) | INT-IND-CODE | 1849 | ≥ 5 states unknown |
| O-7: static examples survive dynamics | PROOF + FIN | INT | 1849 | — |

## B. Approximation and nondeterminism

| Claim | Evidence | Independence | Report | Limits |
|---|---|---|---|---|
| O5-1: no free approximation for recursive kernels | PROOF | INT | 1900 §9 | — |
| O5-12: the best approximation beats every coarsening (35/60, 30/60) | FIN, pre-declared | INT | 1951 | synthetic population; n = 4 |
| N-a: the belief-set kernel is canonical (with a blocking convention) | PROOF | INT | 1900 §9 | linear-time decisions |
| Minimal NFAs are non-unique | CE + FIN | INT | 1900 §9 | — |

## C. Records (O-10)

| Claim | Evidence | Independence | Report | Limits |
|---|---|---|---|---|
| L1: factorization = refinement = distinguishability | PROOF | INT | 2016 | — |
| Theorem 2: the outcome log is the greatest recoverable online record | PROOF + FIN (2 independent enumerators + a 3rd method) | **INT-IND-CODE** | 2016 §8; 0546 enumerator | 𝔇 fixed, computed online, never revised; delimited entries |
| P4: privacy feasibility reduces to the outcome log | PROOF + FIN | INT-IND-CODE (as a corollary of C2 on n = 3) | 2016 §8 | upward-closed predicates; no DP |
| P3 / V3: raw-projection schemas can have incomparable minima | CE | INT | 2016, 2052-f | a derived-field schema removes the issue |
| D10-1: the fixed letter-wise optimum | PROOF | INT | 1951 | d on all of Σ\* |
| F2-1: verifiable ⇔ evidence never merges confusable pairs | PROOF + FIN (exhaustive) + independent random check | INT | 0546-f | output-log fidelity (OF) |
| F2-4: under OF, recoverable ⇒ verifiable; not conversely | PROOF | INT (a reviewer correction) | 0546-f | — |
| F1-2/F1-4: contestability strictly stronger, or incomparable | CE | INT | 0546-f | — |
| F3-2/F3-3: local ⇏ global correctness, and conversely | CE | INT | 0546-f | — |
| F4-2/F4-3: historical verification needs bitemporal evidence | CE + PROOF | INT | 0546-f | — |
| F4-6: deletion limits verification and retroactivity | PROOF | INT | 0546-f | x-only evidence |
| B1–B3: the bitemporal greatest record; the difference log is equivalent | PROOF + FIN | INT | 2052-i | — |
| B5: concurrent corrections need a commutative **and** associative merge, or a recorded order | ANA + CE | INT | 2052-i | — |
| S1–S4: storage behaviour (global monotone; online can increase) | PROOF + FIN | INT | 2052-g | the encoding scheme is named per claim |

## D. Corpus-side claims (all SOURCE-REPORTED)

| Claim | Evidence | Status |
|---|---|---|
| AX-1…AX-7, the Zero theorem, the 5 core operations, T-7, ABK-1 | Session A family summaries | **not verified against the primary sources**. Gate: BLOCKED |

## E. Withdrawn or corrected (never to be reinstated silently)

- 1805 E2 ("three maxima").
- 1805 E3′ sentence.
- 1850 R2 "backwards" (disputed, U-1).
- C-6′ "PROVED".
- O5-5 framing.
- O5-12 Q2.
- D9 "forced".
- N-CE3 universal.
- D10-1 "context-dependent optimum".
- P-9 (D4).
- DP in P4.
- "Binding ⇒ ⊑-finest".
- I3 computation (vacuous).
- O-10h W4/W5 (vacuous).
- **V5 "T_p ⊆ T orders contestability"** (revised by F1-4).
- **F2-4 "neither implies the other"** (refuted).
- **F3-5 table "strict for range ≥ 3"** (corrected).

## F. Added at 05:59: correction horizons and storage (report 20261018_0559)

| Claim | Evidence | Independence | Limits |
|---|---|---|---|
| H1: under horizon k, the window log is the greatest record (unique up to kernel) | PROOF + FIN (an independent brute force by the reviewer) | INT | finite V; corrections serialized |
| H3: a smaller horizon gives a coarser or equal record (within one truncation family) | PROOF + FIN | INT | — |
| Entry size bounded independently of u under a horizon | PROOF | INT | a horizon is *a* sufficient knob, not the only one |
| P4-bi: the privacy reduction carries over to bitemporal records; recording corrections can make privacy infeasible | PROOF + CE | INT | upward-closed Π |
| The full log and the difference log cost the same under kernel-determined codes | PROOF | INT | — |
| The difference log is never smaller under context-free fixed width | PROOF | INT | **syntactic domain only**; reverses over realizable entries |
| Coarsening can raise the global width from ⌈log₂ s⌉ to ⌈n log₂ s⌉ (tight), and the per-node cost to Θ(n²) | PROOF + EXAMPLES | INT | worst case, fixed s |

**Withdrawn or corrected at 05:59:**
- "unique up to an entry-wise recoding" (H1);
- "only a bounded reach makes entries u-independent";
- "the difference log is never smaller" without the domain qualifier;
- K2's D⁴ check (vacuous) and K6(d) (near-tautological), both downgraded as evidence.

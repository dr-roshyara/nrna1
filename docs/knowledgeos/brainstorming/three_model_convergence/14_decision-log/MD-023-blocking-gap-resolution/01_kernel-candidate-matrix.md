# Kernel Candidate Matrix — Full Enumeration (register-level, no deep testing here)

Every Kernel candidate named in any model's own concept register, with the 10 requested fields where
the register supplies them. **Formality level** uses Stage 06's own 8-level classification
(`06_gap-analysis/04`). Where a field is not available at register level, this is stated as
`NOT IN REGISTER` — not inferred, not reconstructed.

## Model A

| Candidate | Components | Relations | Operations | Invariants | Representation | Lifecycle | Reduction claim | Closure claim | Provenance | Formality |
|---|---|---|---|---|---|---|---|---|---|---|
| 0219 Proposition/Position/Claim | 3 named categories | `unresolved_equivalence` vs. other 3 A candidates | NOT IN REGISTER | NOT IN REGISTER | tuple of typed domain objects | NOT IN REGISTER | none | none | seq 0219 (A-primary/C1-secondary) | Level 2 |
| 0216 KnowledgeClaim/Evidence/Determination/Authority | 4 named categories | referenced by 0219 | NOT IN REGISTER | NOT IN REGISTER | tuple | NOT IN REGISTER | none | none | seq 0216, outside A's own 84-file base | Level 2 |
| "K-1" KnowledgeAggregate+ConflictRecord+VerificationPort (as cited in A's own register) | 3 named categories | referenced by 0219, but Phase 6's raw-source check found 0219 does **not** actually cite it | NOT IN REGISTER | NOT IN REGISTER | tuple | NOT IN REGISTER | none | none | outside A's own 84-file base; real origin is C1's seq 0165/0167, see below | Level 2, and the citation itself is unverified |
| Kernel Candidate v0.2/v0.3 | 8 named fields (Question/Hypothesis/Evidence/Claim/Argument/Inference/Assessment/Provenance) | referenced by 0219 | NOT IN REGISTER | NOT IN REGISTER | tuple | NOT IN REGISTER | none | none | outside A's own 84-file base | Level 2 |

## Model B

| Candidate | Components | Relations | Operations | Invariants | Representation | Lifecycle | Reduction claim | Closure claim | Provenance | Formality |
|---|---|---|---|---|---|---|---|---|---|---|
| C0 (13-operator) | 13 named operators: Observe, Interpret, Represent, Relate, Discriminate, Hypothesize, Infer, DetectGap, Challenge, Validate, Revise, Determine, Select | ablation-tested against removal | the 13 operators themselves are the operations | none stated as such | operator set over an unspecified domain | none stated | **TESTED → REJECTED as uniquely minimal** (P-1) | none | M0030→M0035→M0037 | **Level 4 (tested)** |
| 4 cardinality-8 minimal kernels | count only — the register states 4 distinct minimal kernels exist, but does not enumerate each one's own 8-operator membership list | representation-dependent (P-2) | subset of C0's 13 | none stated | operator set, representation-relative | none stated | **ESTABLISHED representation-dependent** (P-2) | none | M0037, ratified M0099 | Level 6 (representation-dependent), but the 4 specific sets are **NOT IN REGISTER** at the member-operator level |
| Extended kernel (RespondToEvidence, Maintain·ApplyEpistemicStandards) | C0 + 2 further operators | extends C0 | same as C0 + 2 | none stated | operator set | none stated | **PROPOSED → UNTESTED** (P-3) | none | M0032/M0033 | Level 3 (specified, untested) |

## Model C1 (12-row table, §N — condensed here, full text in `04_model-c_kernel-ddd/02`)

| Candidate | Components | Formality | Test status |
|---|---|---|---|
| P-1 K1-K8 | 8 named capacities | Level 2 | PROPOSED → UNTESTED |
| P-2 Six Pillars | 6 named (Identity/Evidence/Context/Provenance/Contradiction/History) | Level 2 | PROPOSED → UNTESTED |
| P-3 Six-part aggregate | Identity+EvidenceRefs+Justification+EpistemicState+Confidence+... | **Level 4 (tested)** | **TESTED → REJECTED** (falsified, seq 0157) |
| P-4 `ADR-KOS-KERNEL-001` | one-sentence canonical statement | Level 1 | PROPOSED → UNTESTED |
| P-5 "K-1" KnowledgeAggregate+ConflictRecord+VerificationPort | 3 named components | Level 2 | REJECTED BY ARCHITECTURAL DECOMPOSITION (not an executed test) |
| P-6 McGinn's 5 categories as Kernel primitives | 5 named categories | Level 2 | UNRESOLVED |
| P-7 Fagin/Kripke epistemic model | Kripke-structure formalism | Level 3 (formally specified elsewhere in modal-logic literature, but not independently re-derived here) | **TESTED → REJECTED** as foundation |
| P-8 6-component `K_t` | `K_t=(𝒜_t,ℛ_t,ℰ_t,ℋ_t,𝒵_t,ℒ_t)` | Level 3 | PROPOSED → UNTESTED, superseded within its own arc |
| P-9 `S_Kernel=(D,E,S,T,U)` | 5 named components | Level 2–3 | PROPOSED → UNTESTED |
| P-10 Knowledge Ātma Kernel `𝒦_core` | not further decomposed in register | Level 1–2 | PROPOSED → UNTESTED |
| P-11 Reiter/situation-calculus apparatus | frame problem, successor-state axioms, Golog/RGolog | Level 4 (tested, 9/10 negative tests) | TESTED, survives — not established |
| P-12 (=C2's own candidate) | see C2 row below | Level 3 | PROPOSED → UNTESTED |

## Model C2

| Candidate | Components | Formality |
|---|---|---|
| Seq 2330's DDD Kernel definition | prose definition: "the smallest domain-independent bounded context that owns the identity, lifecycle and provenance of knowledge-bearing participants, content references, information histories, contexts, epistemic states, knowledge attributions and transitions" — decomposable into the same 7 named roles listed in its own `𝒞=(D,P,T,C,I,E,R,H,Θ)` structure | Level 3 (the `𝒞` structure itself is mathematically specified; the Kernel-definition prose wrapping it is Level 1–2) |

## Summary observation (register-level, before any deep test)

**Only 3 candidates across all 20 reach Level 4 (experimentally tested)**: Model B's C0 (rejected as
uniquely minimal), Model C1's P-3 (falsified), Model C1's P-11 (survives, not established). **Every
other candidate — 17 of 20 — is Level 1–3: named, sometimes formally specified, never tested.** This
register-level fact alone already constrains what §2's deep correspondence testing can achieve: most
candidate pairs will not be `TESTED — NO MAP FOUND` in the rigorous sense, because most candidates lack
the operational/behavioral content a genuine map would need to preserve.

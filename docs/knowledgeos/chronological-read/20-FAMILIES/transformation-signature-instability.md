# transformation-signature-instability

**Scope(s):** `THEORY-LEVEL` · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `11 named functions`, `45 distinct RHS strings`, `K_{t+1}=T(K_t)` · **Aliases:** `TG-1, TG-2`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0041, scope THEORY-LEVEL): A grep-based census over the primary corpus finds 45 distinct right-hand-side strings and 11 named transition functions (T, T_t, T_K, Update, Revise, Revision, Recalculate, Derive, Transition, Learn, Improve) plus several unnamed forms, with arities ranging from 1 to >=6; the most-repeated form K_{t+1}=delta_K(K_t,o_t,rho_t,Omega_t) contains two symbols (rho, Omega) with no declared type anywhere in the corpus.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1706] §"45 distinct right-hand-side strings ... 11 distinct named functions ... Step 240 independently reported "approximately 25 materially different right-hand sides" ... The transformation has NO SETTLED SIGNATURE. Arities range from 1 to >=6. Step 240's verdict -- OPEN -- major contradiction -- stands, and this session's independent census reproduces it."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: `S1706`. Candidate lifecycle: **DORMANT**.
Evidence: none recorded (no retraction, supersession, or internal contradiction found). The **DORMANT** classification is a heuristic based on how recently (by source_id ordering) this label was last used in the captured contributions, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1706 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1706 |
| dependencies | PRESENT | S1706, S1706, S1706 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1706 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1706 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Finding TG-2: examining the most-cited transition form K_{t+1}=delta_K(K_t,o_t,rho_t,Omega_t) against the mandate's no-undefined-symbols requirement finds rho_t never defined anywhere outside this equation family, and Omega_t carrying at least three incompatible readings (the domain space Omega_D; the whole knowledge space; the sample space of a probability model K=(Omega,F)), with no domain given for either symbol. [S1706]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S1706]` types=[EXPERIMENTAL-RESULT, CORRECTION] scope=THEORY-LEVEL — "Finding TG-1: an independent grep census over the primary corpus for K_{t+1}=... finds 45 distinct right-hand-side strings and 11 distinct named transition functions with arities ranging from 1 (K_{t+1}=T(K_t)) to >=6 (Step 020's F(K_t,I_t,C_t,E_t,D_t,...)), corroborating (rather than merely repeating) Step 240's independent count of approximately 25 materially different right-hand sides and its 'OPEN -- major contradiction' verdict." (anchor: "45 distinct right-hand-side strings ... 11 distinct named functions ... Step 240 independently reported "approximately 25 materially different right-hand sides" ... The transformation has NO SETTLED S…")
- `[S1706]` types=[ARGUMENT, LIMITATION] scope=OBJECT — "Finding TG-2: examining the most-cited transition form K_{t+1}=delta_K(K_t,o_t,rho_t,Omega_t) against the mandate's no-undefined-symbols requirement finds rho_t never defined anywhere outside this equation family, and Omega_t carrying at least three incompatible readings (the domain space Omega_D; the whole knowledge space; the sample space of a probability model K=(Omega,F)), with no domain given for either symbol." (anchor: "At least two symbols in the corpus's most-repeated transition equation -- rho and Omega -- have no declared type anywhere. Omega is additionally overloaded across three incompatible readings (04 KG-7)…")
- `[S1706]` types=[VALIDATION, RESTATEMENT] scope=THEORY-LEVEL — "Finding TG-3 (positive): endorses the corpus's separation of domain evolution X_{t+1}=delta_X(X_t,e_t) (the world changing) from knowledge evolution K_{t+1}=delta_K(K_t,o_t,rho_t,Omega_t) (our record of it changing) as genuinely different transitions with different inputs, calling it the single most robust result about T in the corpus, one that survives the signature instability documented in TG-1/TG-2." (anchor: "Domain evolution != knowledge evolution ... Step 240 §240.2 is right that this survives the signature instability, and it is the single most robust result about T in the corpus.")

## Notes for P3
- Ungrouped: no mechanical cross-link signal connected this label to any other label in P2a.

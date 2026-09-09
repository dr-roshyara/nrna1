# GAP REGISTER — live

A gap must connect to the current dependency graph or a required research objective.
**Gaps are not manufactured.** Each is a missing node or edge with a precise question.

| ID | missing node/edge | precise question | blocking reason | earliest evidence | candidate answers | status | next action |
|---|---|---|---|---|---|---|---|
| **G-01** | edge `Sat_v4 → Sat_v6` | Why did `Satisfied(K,r,EC)` lose its `EC` argument, and by what argument did a 9/10-valued codomain become 3-valued? | The contract argument is what made satisfaction *contract-relative* (`025d` §25D.11: *"Satisfied = ContractSpecific"*). Without it, `Sat`'s value has no declared standard. | `025d` §25D.12 (08-27 18:31) vs the 09-02 batch | (a) deliberate simplification for a fixed contract; (b) drift; (c) a bridge document not yet read | **UNRESOLVED** | read the 09-01/09-02 `math_ideas` documents that first write `Sat` — chronologically, not by search |
| **G-02** | edge `Γ_v2 → Γ_v3` | Did `Γ` change meaning from *sufficiency/satisfaction rules* to *context*, or are these two unrelated uses of one glyph? | `Γ` sits beside a requirements set in **both** eras — `(R_G, Γ_G)` and `ℛ_req(Q,Γ)` — so the later pair reads naturally through the earlier one and would be wrong. | `025d` §25D.3 (08-27 18:31) vs `182019` (09-02) | (a) `SEMANTIC_REINTERPRETATION`; (b) `UNRELATED_HOMONYM` | **UNRESOLVED** | find the first math-lane document using `Γ`; check whether it defines it or assumes it |
| **G-03** | edge `T:(K,E,Ω,EC)→K'` → `δ(K_t,e_t)` | Where were the ontology `Ω` and contract `EC` arguments dropped from the transition function? | `025k` §25K.35 makes reproducibility *depend* on `Ω_v` and `EC_v`. Their loss removes the stated basis for `Replay` and for the convergence candidate. | `025k` §25K.38 (08-28 09:39) vs `276-final` §276.20 (08-30 21:59) | (a) dropped in `273`–`275` (unread); (b) dropped at `276` | **UNRESOLVED** | read `274` and `275` — the two unread documents inside the 08-30 interval |
| **G-04** | node: `Σ` reconciliation | Which of `Σ_v1 … Σ_v11` are the same object? | ⛔ **NOT YET A RESEARCH TARGET.** Per the operating model, versions are recorded, not reconciled. Listed so it is not mistaken for an oversight. | `009` §8 (08-27 15:20) | 11 versions recorded in `05-DEFINITION-EVOLUTION-REGISTRY.tsv` | **DEFERRED BY METHOD** | none — resume only after chronological reconstruction |
| **G-05** | edge `ℛ(P)` → `ℛ_req(Q,Γ)` | Is the Required Distinction Universe a descendant of the requirements-for-purpose set, or an independent object reusing the glyph? | Both are parameterized sets under a calligraphic `ℛ` at the centre of an adequacy argument; the resemblance is plausible and undocumented. | `023` §5 (08-27 16:25) vs `182016` (09-02) | (a) descent; (b) independent reuse | **UNRESOLVED** | depends on G-01 — the same 09-01/09-02 documents |
| **G-06** | node: `KAID` | What is Knowledge Meaning Identity, and how does it relate to semantic equivalence? | Referenced as established in `025l` §25L.4; its defining documents are unread. | `025l` §25L.4 (08-28 09:40) | — | **UNRESOLVED — evidence not yet read** | read `025i` (knowledge-identity-algebra) and `025s` |
| **G-07** | `TG-02` "six-component vector" | Does the forward plan's `TG-02` describe `025n` §25N.2, and is "six-component vector" a misreading of "six concepts held apart"? | Affects whether `TG-02` is an open derivation or a closed one. | `025n` §25N.2 (08-28 09:42) | (a) misreading; (b) a different six-component object elsewhere | **OPEN** | read the plan's own cited source before asserting anything against it |
| **C-1** | contradiction, not a gap | `025d` rules the gap must **not** be a scalar and rejects all four metric axioms; the 09-02 `Loss_{ℛ_req}(π)` **is** a scalar sum. | Different objects (requirements vs distinctions); neither lane cites the other. | `025d` §25D.17/32 vs 09-02 | — | **OPEN, unadjudicated** | preserve both branches; do not adjudicate |

## Selection — the single smallest load-bearing gap

**`G-03`.** It is the smallest (two named arguments on one function), it is source-verified at both
endpoints, and it blocks the most: `025k`'s reproducibility equation, `025l`'s convergence
candidate, and every later claim about `Replay`. Crucially **its resolution is bounded** — the
interval `025k` (08-28) → `276-final` (08-30) contains exactly two unread documents, `274` and `275`,
both already inside the reading plan.

`G-01`, `G-02` and `G-05` all resolve against the *same* unread 09-01/09-02 documents and are
therefore one investigation, not three — but that investigation is **downstream** of the current
chronological position and must not be opened early.

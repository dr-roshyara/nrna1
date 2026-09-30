---
source_track: TRACK-A-PHASE-MEASURE (extraction only, no modification)
derived_from: [Question 15, Question 17, Step 016 -- all read directly and in full]
cross_track_dependency: none
---

# KSME-17 — Historical Framework Extraction (unmodified)

Extracted verbatim from Question 15 (`20260826-183128`), Question 17 (`20260826-184000`), and Step 016
(`20260827-160001`) — all read directly and in full during KSME-16. No gaps repaired at this stage.

| Object | Exact source form | Location | Tier |
|---|---|---|---|
| Transition | `δ: 𝒦×ℰ ⇀ 𝒦` (partial function) | Question 15 §20.5 | SOURCE |
| Precondition | `Pre(K_t,e_t)`; `δ(K_t,e_t)` defined `⟺ Pre(K_t,e_t)` | Question 15 §21 | SOURCE |
| Postcondition | `Post(K_t,e_t,K_{t+1})`; `Pre∧K_{t+1}=δ(K_t,e_t)⟹Post(...)` | Question 15 §21 | SOURCE |
| Event | `e_t∈ℰ`; types `AssertionCreated, EvidenceAdded, ValueRevised, ConflictResolved, GapAddressed, ReframeApplied, RollbackPerformed` | Question 15 §2.2 | SOURCE |
| Command | `c_t∈𝒞` — an intention, distinct from the event it produces | Question 15 §2.3 | SOURCE |
| History | `H_t=(e_0,...,e_{t-1})`; `H_{t+1}=H_t‖e_t` (append-only) | Question 15 §2.7 | SOURCE |
| Replay | `Replay(K_0,∅)=K_0`; `Replay(K_0,H_t‖e_t)=δ(Replay(K_0,H_t),e_t)` | Question 15 §2.8 | SOURCE |
| 8 foundational theorems (T1–T8) | Compute≠Change; only committed events change K; history append-only; rollback creates new state; Zero evaluates but isn't knowledge; Lord proposes/Sārathi guides/Knower commits; `K_{t+1}=δ(K_t,e_t)`; `K_t=Replay(K_0,H_t)` | Question 15 §1.1 | SOURCE |
| `AssertionCreated(P,Σ,E,τ,Π)` | `δ(K_t,·)=(𝒜_t∪{A},ℛ_t,ℰ_t,ℋ_t,𝒵_t,ℒ_t)` | Question 15 §6.1 | SOURCE (typed shape) |
| `EvidenceAdded(A,E,s)` | `δ(K_t,·)=(𝒜_t,ℛ_t,ℰ_t∪{E},ℋ_t,𝒵_t,ℒ_t)`; **"A's epistemic state may be updated"** | Question 15 §6.2 | SOURCE (typed); the "may" clause is the load-bearing ambiguity (G1) |
| `ValueRevised(A,V_old,V_new,τ)` | `δ(K_t,·)=(𝒜_t∪{A_new},ℛ_t∪{R},ℰ_t,ℋ_t,𝒵_t,ℒ_t)`; "creates new assertion, retires old, creates relationship" | Question 15 §6.3 | SOURCE (typed); object construction (G2) not specified |
| `ConflictResolved(C,Strategy,Result)` | `δ(K_t,·)=(𝒜_t,ℛ_t,ℰ_t,ℋ_t,𝒵_t',ℒ_t)`; **"Assertion epistemic states may be updated"** | Question 15 §6.4 | SOURCE (typed); the "may" clause is the load-bearing ambiguity (G1, second instance) |
| `RollbackPerformed(K_t,τ_target,Reason)` | `δ(K_t,·)=K_{τ_target}`; "creates a new state that is structurally identical to `K_τ_target` but has a different provenance" | Question 15 §6.5 | SOURCE (typed); provenance claim unconstructed (G3) |
| Bitemporal assertion (temporal refinement, Step 016) | `A=(P,E,Σ,Π,T_v,T_o,T_k,Ctx,ID)` | Step 016 §4 | SOURCE — extends, does not resolve, the transition gaps |
| Domain/knowledge state separation | `X_t` (world) ≠ `K_t` (knowledge); `K_{t+1}=δ_K(K_t,o_t,ρ_t,Ω_t)` | Step 016 §9, §58 | SOURCE |
| Step 016's own self-assessment | **"THEORETICALLY RESOLVED AT THE FRAMEWORK LEVEL"** | Step 016 §57 | SOURCE (author's own tier declaration) |

No gap is filled in this document. See `KSME-17-CONSTRUCTION-GAPS-AND-CANDIDATES.md` for what KSME-17
constructs on top of this extraction.

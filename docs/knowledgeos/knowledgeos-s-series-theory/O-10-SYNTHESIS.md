# O-10 synthesis: records, verification, corrections, storage (the canonical statement)

| Field | Value |
|---|---|
| Status | **The canonical consolidated statement** of the O-10 results as of 2026-10-18. It **supersedes cross-reading** reports 2016, 2052-f/i/g/h, 0546-f, 0546-enum and 0559 for definitions and theorem statements. Those reports remain the record of proofs, checks, reviews and corrections. |
| Evidence level | Every result is at best **INTERNALLY REVIEWED (same model family)**. **None is externally reviewed. None is verified against KnowledgeOS sources** (gate BLOCKED). |
| Change control | A new result enters here only after review. Withdrawn claims are listed in §7 and are never reinstated silently. |

## 1. Objects and standing assumptions

| Symbol | Meaning |
|---|---|
| Σ, L | input alphabet; legal histories, prefix-closed with ε ∈ L |
| 𝔇, 𝔇(h) | required decisions; decision vector at h. **A1:** 𝔇 is fixed in advance and computable online (§5 relaxes "never revised") |
| record r | a map on L. **A2:** online append-only, one **delimited** entry per step, entry may depend on the whole past |
| ⊑ | information order: r ⊑ r′ ⇔ r = g∘r′ ⇔ ker r′ ⊆ ker r ⇔ Dist(r) ⊆ Dist(r′) (Lemma L1) |
| PC | the kernels of A2-records: length-preserving, prefix-closed equivalences (P0) |
| x, o, F | attested inputs; the **actual** output (may be faulty); the published rule, d = F(x) |
| W (Φ) | fault model: the admissible (input, output) worlds; **observer-relative** |
| OF | output-log fidelity: the o-log is a faithful record of what was decided (not that it was right) |
| T_p, A_p, K_p | a party's trust set, record access, private knowledge |
| D(h_u, t) | bitemporal decision: the decision for time t as held at time u ≥ t |
| k | correction horizon: D(h_u, t) frozen once u > t + k |

## 2. Recoverability (what was decided)

| ID | Statement | Assumptions | Evidence |
|---|---|---|---|
| L1 | the three orders coincide | — | PROOF |
| P0 | A2-record kernels = PC | delimited entries; whole-past dependence | PROOF |
| **T2** | **the decision-outcome log is the greatest (P)-record**: ≡\* (same length, equal 𝔇 at every prefix) | A1, A2 | PROOF + **two independent enumerators and a third method** (n = 3, binary; 2¹⁴ distinct cases) |
| T2a | resumable version: log (𝔇, Nerode class) | A1, A2 | PROOF |
| T2b | the fixed letter-wise optimum (D10-1) ⊆ ≡\*, strictly in some cases | d defined on all of Σ\* | PROOF + FIN |
| P3 | raw-field-projection schemas can have incomparable minima | projection restriction | CE |
| P4 | for upward-closed privacy predicates: a compliant record exists ⇔ the outcome log complies | A1, A2; deterministic | PROOF (+ FIN as a corollary of T2) |
| P6 | a record minimized for 𝔇 may fail 𝔇′ ⊋ 𝔇 | — | PROOF |

## 3. Verification and contestability (whether it was right)

| ID | Statement | Assumptions | Evidence |
|---|---|---|---|
| **F2-1** | the record (o, E) is verifiable ⇔ E never merges a **confusable** pair (different correct decisions, and some output equal to one of them admissible for both) | OF; x-only evidence | PROOF + exhaustive FIN + an independent random check |
| F2-1a | evidence determining F ⇒ verifiable, for every W | OF | PROOF |
| F2-2 | "verifiable ⇔ evidence determines F", for all E ⇔ every F-distinct pair is confusable | OF | PROOF + FIN |
| F2-4 | under OF: recoverable ⇒ verifiable; **not** conversely. **Witness corrected (2026-10-18, manuscript-v1 Ch. 8 review):** the F2-3(a) example is not a witness, because o alone determines d there. Valid witness: F = id on {0,1}, W = {(0,0), (1,1), (0,9), (1,9)} | OF | PROOF |
| F2-6 | per step: Ver_t ⇔ G_t identifies no t-confusable pair | OF; outputs from x-history | PROOF + FIN |
| **F2-7J** | **for all append-only G: (Ver at all steps ⇒ G determines d at all steps) ⇔ (S-joint)**: every d_t-distinct pair is confusable at **some step s ≤ t**. The converse implication holds for every W. Per-step necessity is **not** jointly required. Ver uses the whole o-prefix | OF; A2; PC realizable | PROOF + FIN: author 0 / 6,000; **independent checker 0 / 161,024 exhaustive** + 23,000 sampled. INTERNALLY REVIEWED (0615) |
| F2-8 | the joint fault model can make step-t evidence unnecessary; per-step marginals over-demand | — | CE |
| F1-1 | party contestability ⇒ auditor verifiability for decisions concerning p, if T_p ⊆ T_a, A_p ⊆ A_a, K_p = ∅, Op_p ⊆ Op_a, Φ_a ⊆ Φ_p | — | PROOF |
| F1-2/F1-4 | contestability can be strictly stronger, or **incomparable** (private knowledge) | — | CE |
| F3-2/F3-3 | local ⇏ global correctness, and global ⇏ local; **which governs is a stakeholder choice** | rules read past outputs | CE |
| F3-4 | global verifiability may need the whole raw-input history | unconstrained outputs | PROOF |
| F3-5 | with output-reading evidence: (o-log, attested correctness bits) is coarsest; strictly coarser than an attested d-log only under condition W | trusted attester | PROOF |

## 4. Corrections and history

| ID | Statement | Assumptions | Evidence |
|---|---|---|---|
| B1 | bitemporal greatest record = T2 with the vector decision (D(h_u, t))_{t≤u} | A2 | PROOF + FIN (same enumerator) |
| B2 | a current-decisions-only log loses corrected past decisions | — | CE |
| B3 | the difference log ≡ the full bitemporal log (same kernel) | — | PROOF |
| B5 | concurrent corrections need a **commutative and associative** merge, or a recorded order, plus the arrival set at each u | — | ANA + CE |
| F4-2/F4-3 | historical verification needs **bitemporal evidence**; bitemporal decisions alone do not suffice | — | CE + PROOF |
| F4-6 | deletion: independent verification survives iff the retained evidence separates confusable pairs; otherwise attestation-only. Retroactive re-evaluation **from the retained record** requires it to determine the new rule. **Retroactivity and deletion constrain each other** | x-only evidence | PROOF |
| H1 | under horizon k, the window log is the greatest record (unique up to kernel) | finite V; serialized corrections | PROOF + independent brute force |
| H3 | a smaller horizon gives a coarser or equal record (within one truncation family) | — | PROOF + FIN |
| P4-bi | P4 carries over to bitemporal records; **recording corrections can make privacy infeasible** | upward-closed Π | PROOF + CE |

## 5. Storage (every claim names its encoding and counting domain)

| Encoding | Behaviour under coarsening or corrections | Evidence |
|---|---|---|
| class count; global per-length code (not append-only) | monotone non-increasing | PROOF + exhaustive FIN |
| ideal fractional coding (−log₂ p) | monotone (telescoping entropy) | PROOF |
| online single global width | can increase; tight bound from ⌈log₂ s⌉ to ⌈n log₂ s⌉ | PROOF + EXAMPLE |
| online per-node integer cost | can increase, up to Θ(n²) vs n⌈log₂ s⌉ | PROOF + EXAMPLES |
| full bitemporal log vs difference log, kernel-determined codes | equal | PROOF |
| the same, context-free fixed width, **syntactic** domain | the difference log is never smaller | PROOF |
| the same, **realizable** domain | can reverse | CE |
| variable-length difference list | wins only if corrections are sparse | PROOF + CE |
| under horizon k | entry size independent of u | PROOF |

## 6. Proof-dependency map

```
L1 ─┬─ P0 ── T2 ──┬── T2a, T2b, P4, P6
    │             ├── B1 (T2 with vector decisions) ── B3 ── H1 ── H3
    │             │                                      └── P4-bi (P4 + B1)
    │             └── storage (class counts; G2 bounds use PC structure only)
F2-1 ── F2-1a, F2-2, F2-4 ── F2-6 ── F2-7J (uses F2-6 + PC prefix-closure)
   └── F3-1 (local = F2 under OF) ;  F4-1 (each question = F2 on a snapshot) ── F4-6
F1-1 uses F2-1 + dominance assumptions
```

**Standing assumptions and where they enter:**

| Assumption | Used by |
|---|---|
| A1 (fixed, online 𝔇) | T2, P4, B1 |
| A2 (delimited append-only) | P0 and everything downstream |
| OF | the whole of §3 |
| Finite V, serialized corrections | H1 |
| x-only evidence | F2-1, F4-6 |
| Determinism | all of the above (probabilistic records are out of scope) |

**Circularity check:**
- No proof uses its own conclusion.
- The finite checks of **C4 (P4)** and of the O-10h wrong candidates are **implied by T2** on the same domain, so they are not separate evidence.

## 7. Evidence audit

| Category | Items |
|---|---|
| **Vacuous** | O-10h W4 (t = 0) and W5 (constant); the 2052-i I3 computation; the 0559 K2 D⁴ = D check at n = 4 |
| **Tautological or implied** | C4 given C2; detection of wrong candidates given C2's uniqueness; the 0559 K6(d) (guards code only); the 0546-f K5c/K6/K7 single-instance hand algebra |
| **Duplicated evidence** | B1's check reused the O-10a enumerator. O-10h reused it too; **closed** by the independent enumerator (0546-enum) |
| **Unsupported generalization** | none standing after the corrections. Earlier ones are withdrawn below |
| **Self-attested only** | every "protocol frozen before code" timestamp (`date` output; no external anchor) |
| **Withdrawn or corrected** | 1805 E2 · 1805 E3′ sentence · C-6′ "PROVED" · O5-5 framing · O5-12 Q2 · D9 "forced" · N-CE3 universal · D10-1 context-dependent optimum · DP in P4 · "binding ⇒ ⊑-finest" · V5 "T_p ⊆ T orders contestability" · F2-4 "neither implies" · F3-5 table · H1 "entry-wise recoding" · "only a bounded reach bounds entries" · the difference log "never smaller" without a domain · U-1 (R2 wording) **still disputed** |

## 8. Optimized research backlog (at most five, ranked)

1. ~~The F2-7J review~~: **done** (0615, PASS WITH CONDITIONS).
2. **The external review package:** compact, self-contained definitions, theorems, proofs, counterexamples, reproducible checks, and questions. **This is now the top research item.**
3. **The probabilistic or randomized-record extension.** Every result above is deterministic. This is the largest scope gap for real systems, which use randomized attestations.
4. **Integration of the joint fault model** into storage and horizon results: do the F2-8 and F2-7J phenomena reduce the evidence-retention costs of §4–§5?
5. **After the gate passes:** map A1, A2 and OF against the five primary sources. Are KnowledgeOS decisions fixed and online? Are its logs delimited and append-only? Is its output log faithful?

## 9. Principal decision sheet (unchanged; only genuine human decisions)

| Decision | Action needed |
|---|---|
| D2 | approve G-LOG-0147 after confirming rows 1 and 5 |
| D1 | appoint the filter reviewer and a separate human tester, after explaining the G-LOG-0144 consequence and obtaining their agreement |
| D6 | appoint a human external reviewer; set the scope and a deadline |
| Later, with stakeholders | D9 critical decisions; D10 retention by category; local vs global correctness; retroactivity together with deletion; correction horizon |

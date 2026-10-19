# Chapter 14. Integrated theory, limitations and open problems

*Status: DRAFT, checked in the final cross-chapter review (same model family); corrections applied. No new theorems.*

## 14.1 The theory in one page

**One order.** Information is ordered by computability (Lemma 2.6): f ⊑ g iff f is a function of g, iff ker g ⊆ ker f. Every "minimal" object below is a **greatest kernel** in a constrained family of equivalence relations.

**One existence criterion.** A family has a greatest element iff its join belongs to it (Thm 4.3). Natural families either satisfy this or break it in identifiable ways.

**Part II: what a system must hold.**
- **(S1)** The **canonical state** is the Nerode quotient of the required decisions. It is unique for trim realizations, decidable for finite-state specifications, and undecidable in general (Ch. 3).
- **(S2)** It stays the unique minimum under:
  - upward-closed policies;
  - separation requirements shaped as an equivalence on legal pairs, which act as extra decisions (Thm 4.5(b));
  - quotient-closed update classes;
  - hereditary decoder restrictions.

  Uniqueness can fail under:
  - cap policies;
  - pair-list separation requirements;
  - infinite-state reversible updates.

  **Existence** can fail: history-counted anonymity can be unsatisfiable (Thm 4.7). State-counted conditions are vacuous, because padding always meets them.

  Component **schemas** are not unique in general (Ch. 4).
- **(S3)** Contract gaps are derived evaluations, ordered only partially and per contract (Ch. 5).
- **(S4)** Exactness on all of Σ\* forces sufficiency (Thm 6.2). Good approximations often track different information than the exact state (synthetic evidence, Obs. 6.5). Nondeterminism is handled in two ways:
  - the belief-set quotient (Thm 6.8; linear-time observer);
  - alternatively, recording choice outcomes as inputs restores determinism (Prop. 6.12, ANA, conditions (a)–(e)).

**Part III: what a system must retain.**
- **(R1) Recover.** The decision-outcome log is ⊑-least among recoverable online records, unique up to kernel, under A1(i),(iii) (Thm 7.3). That is least information, not least storage.
- **(R2) Verify.** Verification ⇔ the retained trusted evidence never merges confusable pairs. Determining evidence always suffices. Fault-model knowledge can reduce evidence, at the price of a trust assumption (Ch. 8).
- **(R3) Processes.**
  - Ver_s ⇔ G_s separates s-confusable pairs (Thm 9.5).
  - "Verifiable at every step ⇒ determining at every step" holds for every append-only G iff (S-joint) (Thm 9.8).
  - (S-multi) is not necessary (CE 9.10).
  - Linked faults can reduce evidence (CE 9.11).
- **(R4) Contest.** Epistemically, auditor verifiability and party contestability are incomparable in general. Under dominance, party ⇒ auditor (Thm 10.4).
- **(R5) Correct.**
  - Bitemporal decisions give recoverability of both the original and the current decisions.
  - Current-only evidence can fail historical verification (CE 11.9). Bitemporal evidence **suffices** under the conditions of Prop. 11.10.
  - Horizons bound entries at the cost of late corrections.
- **(R6) Privacy and deletion.** Upward-closed privacy reduces to one check on the minimal record, and corrections can break privacy. Deletion and retroactivity constrain each other (Ch. 12).
- **(R7) Storage.** Information-minimal ≠ storage-minimal for streaming logs. Every claim must name its encoding (Ch. 13).

## 14.2 Standing assumptions (each must be checked against the real system; Ch. 16)

| ASM | Content | Used in |
|---|---|---|
| IC | every datum a decision reads is in the inputs | all |
| DET | decisions are functions of history | all |
| OD | definedness is observable. *A DEF 2.3 convention with assumption content;* λ ∈ 𝔇 is part of the convention | Ch. 3 onward |
| A1 | 𝔇 is (i) fixed, (ii) computable online, and (iii) never revised; Ch. 11 relaxes (iii) | Ch. 7 |
| A2 | records are append-only with delimited entries | Part III |
| Assumption 5.2 | contract satisfaction predicates are members of 𝔇 | Ch. 5 |
| OF | output logs are faithful | Ch. 8–12 |
| x-only evidence | retained evidence depends on inputs only (DEF 8.3, DEF 12.4) | Ch. 8, 12 |
| Trust and fault models | declared per observer; trust constraints conjunctive (DEF 10.2) | Ch. 8–10 |
| Observer power | linear-time vs branching-time (Ch. 6) | Ch. 6 |
| Determinism of records | no randomized mechanisms | all of Part III |

**Human-decision labels (fixed here, used throughout).**
- **D9:** which decisions are **critical**, i.e. require exact authoritative results. Approximations are confined to labelled projections.
- **D10:** **event recording**, i.e. which decision-relevant outcomes (including choice outcomes) are recorded, and whether "paths not taken" are ever required.
- **R-OPT:** the **retention option** (A–E, Ch. 12) per decision type. This is distinct from D10.

## 14.3 Claims withdrawn or corrected during the research (never reinstated)

**From the earlier report record:**
- "exactly three maxima" for the cap policy;
- "always satisfiable by splitting states";
- C-6′ "PROVED";
- the O5-5 framing;
- O5-12 Q2;
- "D9 is forced by the mathematics";
- the universal NFA-merge claim;
- the D10-1 context-dependent optimum;
- DP as an instance of the privacy reduction;
- "binding commitment ⇒ ⊑-finest";
- "T_p ⊆ T orders contestability";
- "neither recoverable nor verifiable implies the other";
- the strictness table row for attested correctness bits;
- "entry-wise recoding" uniqueness;
- "only a bounded reach bounds entries";
- "the difference log is never smaller" without a domain;
- several vacuous or tautological checks (W4, W5, I3, the K2 D⁴ check, K6(d)).

**During manuscript v1 review:**
- Thm 4.5(b),(c): the separation policy is now stated on legal pairs;
- the audit-requirement claim, now restricted to equivalence-shaped requirements (CE 4.5e);
- the ⊑ direction in Thm 7.3;
- the misplaced L = Σ\* limit in Thm 7.6;
- unsatisfiable k-anonymity instances in Thm 7.8;
- the witness of Prop. 8.10(b), together with its predecessor F2-4;
- Prop. 8.12's ordering and strictness;
- the Ch. 10 traceability direction;
- "an effective-date registry is necessary";
- Prop. 11.6 (merge condition, now the commuting-actions reading);
- Prop. 11.8, downgraded to ANA (a reduction schema);
- Prop. 11.10, now conditional and downgraded to ANA;
- the Ch. 13 Fibonacci-family claim, removed as unspecified;
- Thm 12.5(b), downgraded to ANA;
- the Ch. 13 asymptotic claim that the per-node constant approaches the upper bound, withdrawn.

**Disputed, now addressed but pending external review:** U-1 (binary joins vs ACC; witness in Ch. 4).

## 14.4 Open problems (ranked by consequence for the architecture)

1. **Randomized and cryptographic records.** Commitments, zero-knowledge proofs, differential privacy. The deterministic order ⊑ does not apply. This is the largest gap for real audit infrastructure.
2. **Demonstrative contestability** (Ch. 10): does it reduce to verifiability of an augmented record?
3. **Instantiating (C) and (S) verification** (Ch. 11) as Ch. 8 data.
4. **Rules that read past outputs**, in the multi-step setting (Ch. 9). The earlier record has separation results; a manuscript treatment is still needed.
5. **Approximation under nondeterminism** (Ch. 6).
6. **Storage:** a general ratio bound under coarsening; whether worst cases arise from horizon reductions (Ch. 13).
7. **The finite-state version of history-counted anonymity** (Ch. 4).
8. **Encoding-level update restrictions** (Ch. 4).
9. **"Execute transformations"**, the corpus requirement not mapped by this theory (Ch. 3).
10. **An intrinsic (J2) characterization** for infinite-state update classes (Ch. 4).
11. **Whether a bitemporal registry is required** (DEF 11.6a).

## 14.5 What the theory does not establish

- That KnowledgeOS satisfies any assumption of §14.2. **This is unverified; the source gate is BLOCKED.**
- Legal adequacy of any record, remedy or deletion design.
- Which decisions are critical, what to retain, how far back to correct, or whom to trust. **These are STAKE.**

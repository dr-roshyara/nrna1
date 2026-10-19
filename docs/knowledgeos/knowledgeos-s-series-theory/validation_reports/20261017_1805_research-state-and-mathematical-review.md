# Research state, mathematical review and gated work plan

| Field | Value |
|---|---|
| Author | Session B (Professor) |
| Date | 2026-10-17 18:05 |
| Reviews | `20261017_1752_validation-gate-and-restricted-decoder-minimality.md` (the "1752 report") and, through it, `20261017_1748_validation_report.md` |
| Inputs used | Only those two reports, the manuscript workspace's own files and `corpus-access/corpus_filter.py` (Session B's own script). **No corpus file, family file, seal, hold-out or S5 material was opened. No script was run. No governed file was edited.** |
| Status | Same-model review. It is **not** independent validation. Every PROVED tag below means "proved by Session B, awaiting independent review". |

Labels: PROVED · COUNTEREXAMPLE (CE) · CONJECTURE · OPEN · SOURCE-REPORTED · PRIMARY-SOURCE-VERIFIED · EMPIRICAL-DEMONSTRATION · PROPOSED · PRINCIPAL-DECISION-REQUIRED (PDR).

---

## 1. Executive summary

1. **The 1752 report survives re-checking, with four repairs.**
   - **R1 is vacuous as written.** It says "whether some admissible realization induces π depends only on π", which is true of anything. The intended statement, restated as R1′, also needs realizations to be *trim* (every state reachable).
   - **E3 is imprecise.** k-anonymity on decoder fibres counted in *states* can always be met by splitting states. The counterexample holds only when some decision value is attained by fewer than k *histories*. This has a direct governance consequence: privacy constraints must be stated on histories or actors, never on states.
   - **C-0 merges two notions.** *Predictive* sufficiency (ker R ⊆ ∼) is strictly weaker than *recursive* realizability (ker R is a right congruence that answers every decision). The repair is C-0′.
   - **C-6(iii) needs a warning.** If undefined decisions are treated as "don't care" rather than through a legality decision, the canonical quotient is lost: minimization becomes non-unique and NP-hard (C-6′).
2. **New result: the join-closure question is now answered for two large policy classes** (Theorem R4, PROVED):
   - *upward-closed* policies (anonymity-type: coarser is always allowed) never change the minimal state;
   - *separation* policies (audit-type: "must distinguish ρ") are equivalent to adding a required decision.

   For any mix of the two, the coarsest admissible state is unique, namely the Nerode quotient of the augmented decision set, whenever any admissible state exists. Non-uniqueness, as in E2, needs a downward-closed policy that is not *principal*, i.e. not expressible as one separation requirement. Policies on **updates** (𝔊) and **non-invariant decoder classes** (𝔉) remain OPEN.
3. **C-1 can be strengthened:** a *single* polynomial-time decision function can already have an undecidable behavioural equivalence.
4. **The filter script has a fail-open defect.** Re-reading `corpus_filter.py` (my own script) shows that a failed label-list hash is printed but does not stop the run. Further defects: case-sensitive substring matching, a non-recursive directory scan, mixed repository roots, no source-ID check, and outputs written to the current directory. This strengthens D1: the independent filter review is needed before any further reading.
5. **Nothing here is evidence that KnowledgeOS satisfies the model.** All corpus-side claims remain SOURCE-REPORTED (mostly third-hand, through Session A's family files). Zero primary sources are verified for the five Gate-D targets.
6. **Stop point.** Phases C (reading), D (computation) and any manuscript change wait for decisions D1–D8 (§9).

---

## 2. Research-state assessment

### 2.1 Register by category

| # | Category | Items |
|---|---|---|
| 1 | **Formally defined** | Σ, Σ\*, 𝔇, ∼ (behavioural equivalence); realization (S, s₀, u, f); predictive and recursive sufficiency (§3.1); admissible family 𝒜; coarseness ⊒; gap Δ, Zero, ≼_EC (as reconstructed, not yet checked against the primary source) |
| 2 | **Proved (same-model)** | C-0′(a); C-1 and C-1⁺; C-2 counterexamples; C-3; C-5; C-6 conditions; R1′; R2; R4; E1; E2; E3′ |
| 3 | **Awaiting independent review** | all of row 2: none has an external reviewer yet (D6) |
| 4 | **Conjectures and open problems** | join-closure under 𝔊 restrictions (O-1); encoding-level minimality under non-invariant 𝔉 (O-2); whether 𝒪_core generates Σ (O-3); the chronology of K-first vs EC-first (O-4); the stochastic case with a declared loss for Φ (O-5); the choice of behavioural equivalence under nondeterminism (O-6) |
| 5 | **Corpus-reported** | AX-1…AX-7 and the Zero theorem; gap theorems THM-G2/G4; v1.1 Obs_{EC,Q,C}; 𝒪_core; Step-88 information sufficiency; Adequacy/R_req/EA; ABK-1 (6/6); the T-7 witness; the eight primitives; the layered pipeline. All are SOURCE-REPORTED, mostly via family files (third-hand). |
| 6 | **Primary-source verified** | 4 brainstorming documents read through the filter (Step 285, the Gita model, docs 30 and 38). They support S-1…S-10 **as statements made in those documents**, not as true. The five Gate-D targets: **none.** |
| 7 | **Empirical demonstrations** | none performed by Session B. ABK-1 and T-7 are reported demonstrations (§3.6). |
| 8 | **Architecture hypotheses** | H-1…H-10 (§7) |
| 9 | **Principal decisions** | recorded: G-LOG-0141 and its extension (l.2220, l.2230). Open: D1–D8 (§9). **None is answered here.** |

### 2.2 Research questions

| RQ | Current claim | Evidence and provenance | Status | Key assumptions | Open objections | Next evidence | Authorization |
|---|---|---|---|---|---|---|---|
| T1 Does a canonical minimal kernel exist? | Yes: the Nerode quotient of (𝓞, 𝔇) | Session B's proof (Prop. 15.2/15.4, C-6) | PROVED, unreviewed | C-6 (i)–(iv) | Do KnowledgeOS decisions satisfy determinism and input completeness? | S2373, S2763; external review | D5, D6 |
| T2 Is the kernel computable? | Well-defined; decidable for finite-state specs; undecidable in general | C-1, C-1⁺ | PROVED (sketch), unreviewed | effective specification of 𝔇 | Which specification class does KnowledgeOS use? | the primary definitions of 𝔇/R_req | D5 |
| T3 Does adequacy imply realizability? | Only with a deterministic update (C-0′) | C-0′ | PROVED, unreviewed | determinism | Does the corpus's EA clause mean a deterministic update? | S2871 (T-7), the EA source | D5 |
| T4 Minimal kernel under constraints | Unique iff ⋁𝒜 ∈ 𝒜; resolved for up/separation policies; not in general | R2, R4, E1–E3′ | PROVED/CE, unreviewed | partition-level policy (R1′) | 𝔊 restrictions; non-invariant 𝔉 | analysis (O-1, O-2); optional synthetic checks | D6, D7 |
| T5 Gap order | Preorder on states; partial order on gap classes | C-5 | PROVED, unreviewed | Δ is a set of unmet requirements | Is Δ really set-valued in the primary source, or weighted? | the gap-theory primary | D5 |
| T6 Does 𝒪_core suffice? | SOURCE-REPORTED that five operations are the core | family file (third-hand) | OPEN | — | completeness and independence unproved | S2763 Part IV | D5 |
| T7 Adequacy ≠ Realization | One witness (T-7) | family file (third-hand) | SOURCE-REPORTED | the witness is correctly computed | not yet seen in the primary | S2871 | D5 |
| T8 Is corpus access safe? | Plausibly, but not established | filter outputs; 1752 §4 | OPEN | the filter is correct | source-ID channel; fail-open hash (§5.1) | independent review plus the count-only check | D1, D2 |

---

## 3. Formal mathematical review (Deliverable B)

**Common setting** (as in the 1752 report, §5): histories h ∈ Σ\*; 𝔇 is a set of decisions d : Σ\* → V_d; h ∼ h′ iff for every w and every d, d(hw) = d(h′w).

### 3.1 B1: Adequacy and dynamic sufficiency (repair C-0′)

**Defect.** C-0 called "R(h) = R(h′) ⇒ h ∼ h′" *dynamic sufficiency* and also used realizations with updates. These are different notions:
- **Predictive sufficiency:** ker R ⊆ ∼. Every future decision d(hw) is a function F(R(h), w).
- **Recursive (realizable) sufficiency:** there are u and f with R(ha) = u(R(h), a) and d = f_d ∘ R. Equivalently, ker R is a right congruence contained in every ker d.

**C-0′(a) [PROVED].** Recursive sufficiency implies predictive sufficiency. This is the 1752 proof unchanged.

**C-0′(b) [CE].** Predictive sufficiency does **not** imply recursive sufficiency on the same state space.
- Let 𝔇 be constant, so ∼ is one class.
- Let R(h) = [|h| is prime]. Then ker R ⊆ ∼ trivially.
- But |h| = 2 and |h′| = 3 both give R = 1, while appending one symbol gives lengths 3 (prime) and 4 (not prime). So ker R is not a right congruence, and no update u on {0, 1} exists.

**C-0′(c) [CE].** The 1752 counterexample (R = d for d = "ends with ab") stands. It fails *both* notions, because it is not even predictively sufficient.

**Quantifier check.** (a) needs adequacy at **every** history reachable under the update, not only at the current one. Over a legal language L, it needs adequacy on L, with L prefix-closed and continuations kept in L.

**What follows for Executable Adequacy (EA), conditionally.** *If* the corpus's EA means "adequate at every history + an auditable deterministic transition", then EA ⇔ recursive sufficiency, and the Nerode quotient is its coarsest instance. *What still needs evidence:* that this is what the EA source says. This is a Gate-D question, under the EA and T-7 sources.

### 3.2 B2: Behavioural equivalence and decidability

| Term | Meaning here | Holds for ∼? |
|---|---|---|
| well-defined | ∼ exists as a relation on Σ\* | always, under C-6 (i)–(ii) |
| computable / decidable | an algorithm decides h ∼ h′ | only for restricted specification classes |
| effectively constructible | an algorithm outputs a finite presentation of Σ\*/∼ with u and f | for finite-state specifications (Moore/Hopcroft minimization) |

**C-1 check.** The 1752 reduction is correct.
- d_M("0"w) = 0 for every w, and d_M("1"w) = 1 iff M halts within |w| steps. So "0" ∼ "1" iff M never halts.
- The uniform problem (M, h, h′) ↦ [h ∼ h′] is undecidable; it is co-r.e. (Π⁰₁).
- *Precision.* For any **fixed** M, the answer is a constant, so this shows uniform undecidability only.

**C-1⁺ (strengthening) [PROVED, sketch].** There is one fixed decision d, computable in polynomial time, whose ∼ is undecidable.
- *Construction.* Use histories x·c·w, where x is a prefix-free code of a machine M_x and c ∈ {0, 1}. Let d(x1w) = 1 iff M_x halts within |w| steps, and d = 0 otherwise.
- d runs in polynomial time: it simulates |w| steps.
- *Result.* x0 ∼ x1 iff M_x never halts. So efficient decision rules do **not** imply decidable kernel comparison.
- *Consequence for KnowledgeOS:* kernel comparison is algorithmic only if 𝔇 is specified in a class with decidable equivalence (finite-state or bounded horizon). This is a **requirements choice**, not a theorem about the corpus.

**Other conditions.**
- **Completeness of the input alphabet (C-6(ii)).** If d reads the clock, the actor, an authorization or an oracle not recorded in Σ, then d is not a function of the history, and ∼ is undefined.
- **Nondeterminism [OPEN, O-6].** With nondeterministic or stochastic transitions, "behaviour" must be chosen:
  - trace equivalence and bisimulation then differ;
  - for probabilistic systems, probabilistic bisimulation (Larsen–Skou, 1991; literature, not re-verified here) has a unique coarsest quotient.

  The theory must **declare** which equivalence it means. For deterministic systems they coincide.
- **Partial decisions: repair C-6′ [literature-backed; PROVED for the stated reduction].** C-6(iii)'s repair, "add legality decisions to 𝔇 and use a prefix-closed L", is the correct one. The alternative, treating undefined values as "don't care", replaces ∼ by a **compatibility relation**, which is not transitive. Then:
  - minimal realizations are not unique;
  - minimization is NP-complete: state reduction of incompletely specified machines (Pfleeger, 1973; literature, not re-verified here).

  So the canonical quotient **depends on** legality being a declared decision.

### 3.3 B3: Component minimality and no-compression

**C-2 check.** The duplicated-component counterexample is correct.

**Added counterexample [CE].** Components c₁ = x, c₂ = y, c₃ = x ⊕ y, with d = x ⊕ y.
- {c₃} and {c₁, c₂} are both sufficient and irredundant (no component can be dropped).
- Their sizes differ.
- c₁ is necessary in {c₁, c₂} but redundant in {c₁, c₂, c₃}.

**Distinctions.**
- **Local necessity** depends on the other components retained.
- **Irredundant** (minimal under ⊆) ≠ **minimum** (fewest components).
- A finite sufficient component set always contains an irredundant sufficient subset, by finite descent. Neither notion gives a unique answer.
- Finding a minimum set is NP-hard in general: this is the minimal-reduct problem of rough-set theory (Skowron–Rauszer, 1992; literature).
- **The partition** Σ\*/∼ is unique; **component sets** realizing it are not. A kernel's *schema* is a design choice; its *information content* is not.

**C-3 check.** Correct: no compression is possible iff ∼ is the identity on (legal) histories. The audit example works only for a decision that needs the **full** history, such as d(h) = h, or "replay in order".

### 3.4 B4: Gap order

**C-5 check.**
- Reflexivity and transitivity are inherited from ⊆.
- Antisymmetry holds on classes {K′ : Δ(K′) = Δ(K)}. It fails on states, because two distinct states can have the same Δ.
- **Zero** (Δ = ∅) is the top class.

**Two qualifications.**
- The order is **contract-relative**: states assessed under different contracts are not comparable.
- If the primary source defines Δ as weighted or multiset-valued, then ⊆ must be replaced, and C-5 must be re-proved. This is a Gate-D check against the gap-theory primary.

Contract satisfaction ≠ knowledge quality ≠ the transition relation (unchanged from the 1752 report).

### 3.5 B5: Restricted-decoder minimality

**R1, restated (R1′) [PROVED].** The 1752 statement is vacuous. The intended theorem:

> Let realizations be **trim** (every state reachable). Let 𝔉 and 𝔊 be bijection-invariant. If one admissible trim sufficient realization induces π, then every trim sufficient realization inducing π is admissible.

*Proof.* Two trim realizations with the same kernel are related by β(s(h)) := s′(h). This β is well defined and bijective because the kernels are equal, and it commutes with the updates and decoders. Invariance then transfers admissibility. ∎

*Why trimness matters:* without it, the carriers can differ in size (unreachable states), and no bijection S → S′ exists.

*When invariance is unnecessary:* when the policy is stated directly on partitions (E2, R4). Invariance is needed only to **descend** an encoding-level policy (decoder or update form) to partitions.

**R2 check [PROVED; elementary].** Correct. It holds for any subset of any complete lattice, and needs no non-emptiness hypothesis: for 𝒜 = ∅, ⋁∅ is the identity relation, which is not in ∅, so both sides are false.
- *Honest weight:* R2 is a **reformulation**. Its value is moving the problem to join-closure.
- *Caution:* closure under **binary** joins suffices only if 𝒜 has no infinite strictly ascending chains. Otherwise arbitrary joins are needed, as R2 states.
- *Corollary:* when the greatest element exists and the index is finite, it is also the unique minimum-cardinality element. Coarser gives fewer blocks, and an equal count under refinement means equality. For infinite index, this fails.

**R4 (new) [PROVED].** Let Suff be the set of right congruences contained in ∼, ordered by ⊒. Consider policies that are subfamilies of Suff.

- **(a) Upward-closed** policies U (if π ∈ U and π ⊆ π′ ∈ Suff, then π′ ∈ U). For example, k-anonymity measured on history classes, or minimum aggregation. If U ≠ ∅, then ∼ ∈ U, so the coarsest admissible state is ∼ itself.
  - *Meaning:* such policies affect **existence** only, never the minimal state.
- **(b) Separation** policies D_ρ = {π ∈ Suff : π ⊆ ρ}, for a given equivalence ρ, such as "must distinguish actors" or "must distinguish authorization contexts". D_ρ is join-closed, because a join of relations inside ρ stays inside ρ (ρ is transitive). Its greatest element is the Nerode quotient ∼′ of the augmented set 𝔇 ∪ {d_ρ}, with d_ρ(h) = [h]_ρ.
  - *Proof:* ∼′ is a right congruence contained in ∼ and in ρ (take w = ε). Any right congruence π ⊆ ∼ ∩ ρ satisfies π ⊆ ∼′.
  - *Meaning:* **a separation requirement is a required decision.**
- **(c) Mixtures.** For U ∩ D_ρ: if it is non-empty, its greatest element is ∼′. Some π ⊆ ∼′ lies in U, and U is upward-closed, so ∼′ ∈ U. It is empty iff ∼′ ∉ U, which is the anonymity-vs-audit conflict.
- **(d) Characterization.** A downward-closed D ⊆ Suff has a greatest element iff D is **principal**, i.e. D = D_g for g = ⋁D.
  - *(⇐)* is (b).
  - *(⇒)* If g = ⋁D ∈ D, downward closure gives D ⊇ D_g. Trivially D ⊆ D_g.
- **(e) E2 is the non-principal case.** The cap policy is downward-closed, but it is the union of three principal ideals, so it has no greatest element.

*Status:* PROVED by Session B, unreviewed. It **resolves O-1 for partition-level up/down policies only.** It says nothing about update restrictions 𝔊 or non-invariant 𝔉.

**E1 check [CE, re-verified by hand].**
- Binary encoding: d = b₁ ⊕ b₂, so no single-bit decoder computes it.
- Gray encoding (01 ↦ 1, 11 ↦ 2): d = b₂.

Correct.

**E2 check [CE, re-verified].** First-symbol partitions are right congruences. Maximality holds over **all** right congruences, not only the stated family: merging ε with the c-class forces a ∼ ca, which merges the a- and c-classes and so gives three channels. The three maxima have equal state count, so even minimum cardinality is non-unique.

**E3 repair (E3′) [CE, precise form].**
- **Defect:** "fibres of ≥ k *states*" is always satisfiable by splitting every ∼-class into k reachable states.
- **Correct form:** if some required decision value is attained by fewer than k **histories**, then no sufficient realization has all fibres of size ≥ k. Each history has one state, so the fibre has fewer than k states, and 𝒜 = ∅.
- **Governance consequence [PROPOSED]:** anonymity constraints counted on internal states are void, because padding defeats them. They must be stated on histories, actors or released outputs.

**Answer kept.** Bijection invariance is neither sufficient (E2) nor necessary for uniqueness. The criterion is ⋁𝒜 ∈ 𝒜 (R2). For partition policies, it is decided by R4(d).

### 3.6 B6: Evidence and statistical claims

| Item | What it can show | What it cannot show |
|---|---|---|
| **ABK-1 6/6** (SOURCE-REPORTED, third-hand) | That six constructed cases pass, *if* the primary confirms it | Any rate or prevalence: the cases are constructed, not sampled. Even if read as a sample, 6/6 gives only a one-sided 95% lower bound p ≥ 0.05^{1/6} ≈ 0.61. Not generalization; not that the bilattice is the right kernel. |
| **T-7 witness** (SOURCE-REPORTED, third-hand) | One correct witness suffices to refute "Adequacy = Realization", a universal identity | How often they differ, or whether the difference matters in practice. Pending S2871: the witness's data, decoders and k. |
| **Repetition across families** | Nothing additional | Repeated summaries of one primary are **one** piece of evidence (README rule 2). |

---

## 4. Proofs, counterexamples and open questions (index)

| ID | Label | One line |
|---|---|---|
| C-0′(a) | PROVED | recursive sufficiency ⇒ predictive sufficiency |
| C-0′(b) | CE | prime-length bit: predictive but not recursive |
| C-0′(c) | CE | R = d for "ends with ab": neither |
| C-1, C-1⁺ | PROVED (sketch) | ∼ uniformly undecidable; even for one polynomial-time d |
| C-2, XOR | CE | local necessity is relative; irredundant ≠ minimum; non-unique |
| C-3 | PROVED | no compression ⇔ ∼ = identity |
| C-5 | PROVED | preorder on states, partial order on gap classes |
| C-6, C-6′ | PROVED / literature | canonical quotient under (i)–(iv); lost under "don't care" partiality |
| R1′ | PROVED | partition-level admissibility for trim realizations under invariance |
| R2 | PROVED (elementary) | greatest exists ⇔ ⋁𝒜 ∈ 𝒜 |
| R4 | PROVED | upward policies inert; separation = decision; downward policy has a greatest element ⇔ principal |
| E1, E2, E3′ | CE | encoding dependence; three maxima; history-counted anonymity can be infeasible |
| O-1 | OPEN | join-closure under update restrictions 𝔊 |
| O-2 | OPEN | minimality per encoding under non-invariant 𝔉: what is the right order? |
| O-3 | OPEN | do ASSERT…ISOLATE generate every needed transition, and are they independent? |
| O-4 | OPEN | chronology of K-first vs EC-first |
| O-5 | OPEN | stochastic Φ: approximate sufficiency needs a declared loss; rate–distortion form |
| O-6 | OPEN | which behavioural equivalence under nondeterminism |

---

## 5. Safety gate and source-verification plan (Phase C)

### 5.1 New findings from re-reading `corpus_filter.py` (read only; not run)

| # | Defect | Effect | Severity |
|---|---|---|---|
| F-1 | `label_hash_ok` is printed but **not enforced**: the run continues on a mismatch | **fail-open** if the seal's label list does not match its recorded hash | High |
| F-2 | Label match is `l in text`: case-sensitive and exact substring | variants such as case or slugged forms are missed | High |
| F-3 | Stem rule is slug **equality** only | names containing a label slug are not caught | Medium |
| F-4 | `os.listdir(FAMILIES)`, `isfile` only | subdirectories are not scanned (the "unscanned subdirectory") | Medium |
| F-5 | Corpus paths are resolved against the PROD repo, families against the W worktree | branch mixing; disk-status results depend on the checkout | Medium |
| F-6 | No source-ID check | the 1752 quotation channel stays open | High |
| F-7 | Sealed exclusion is by `source_id` only, with no content-sha or path check (unlike `discovery_filter.py`) | a copy under another ID passes | Medium |
| F-8 | Outputs are written to the current directory | the location of the allowed lists depends on where it was run | Low |

**Also from the 1752 report:** `discovery_filter.py`'s `norm()` (`lstrip("./")`) and the edited-and-moved copies.

**Remediation [PROPOSED; needs D1]:**
- stop with a non-zero exit on any hash mismatch;
- normalize case and slugs on both sides;
- scan recursively;
- use one declared root;
- check content sha and path in addition to the ID;
- add the source-ID check;
- write to absolute paths;
- have the independent reviewer re-derive the outputs.

**Read-log remediation [PROPOSED; needs D3 or a log-edit authorization]:**
- append a correction for `knowledgeos-kernel-concept.md` (logged, never opened);
- mark the family reads as "header only";
- correct the source register to "5 read (6 logged)".

### 5.2 The gate sequence (unchanged from the 1752 report, §4.2)

1. D1 and D2 are decided.
2. The independent review fixes F-1…F-8 and the `norm()` defect.
3. The count-only check runs, outputting counts and yes/no answers only.
4. If any previously read item is affected: stop and report to the Principal; do not open anything to investigate.
5. Only then Gate D: the five targets in the 1752 report, §6.
   - For each, record: full or partial; verbatim or paraphrase; which claim (T1–T7) it supports.
   - Record any discrepancy with the reports; never silently change the theory.

---

## 6. Computational and ML research plan (Phase D; nothing runs without D7)

All methods use **synthetic inputs, or registry metadata of allowed rows only**. Never sealed, hold-out or S5 data. Never used to infer labels.

| M | Question | Inputs (allowed / prohibited) | Baseline | Validation | Output | Cannot establish | Main risk |
|---|---|---|---|---|---|---|---|
| M1 Synthetic Moore machines | Do C-0′, C-6, R2 and R4 hold on all small cases? | random/exhaustive machines with ≤ 5 states and \|Σ\| ≤ 3 / no corpus | hand proofs | Hopcroft minimization as an independent oracle; two independent implementations | pass, or a counterexample | general truth (only refutation) | bugs shared between the implementations |
| M2 Property-based and metamorphic tests | Invariance of ∼ under state relabelling and decision-preserving input renaming | generated machines / no corpus | example tests | shrinking to a minimal counterexample | properties and seeds | correctness beyond the generator's distribution | generator bias |
| M3 SAT/SMT search | Is join-closure kept under specific 𝔊 classes (O-1)? E2-like failures for natural policies? | encoded finite instances / no corpus | M1 enumeration | solver certificates (models or UNSAT proofs) | counterexamples or bounded proofs | unbounded claims | encoding errors (cross-check against M1) |
| M4 Reduct search | How non-unique are component sets (C-2) in realistic schemas? | synthetic schemas; later, authorized schema definitions only | greedy reduct | exact search on small cases | reduct counts | which reduct is *right*: that is a design decision | over-reading counts |
| M5 Lineage graph | Which documents derive from which (dates, paths, cites)? | allowed-row registry metadata / no seal, no hold-out | date order | spot-checked edges | DAG of claim provenance | truth of any claim | metadata errors |
| M6 Near-duplicate detection | Effective number of independent sources per claim | allowed files after Gate D / no withheld files | exact sha | MinHash vs hand-labelled pairs | duplicate clusters; effective n | conceptual identity | treating duplicates as independent |
| M7 Embedding prioritization | Which allowed documents to read next for O-3/O-5 | allowed files after Gate D | BM25 or keywords | precision@k against reviewer judgements | ranked list | relevance or truth (similarity ≠ identity) | leakage if an embedding index ever includes withheld files: **must be built only from the allowed list** |
| M8 Information measures | The loss for approximate sufficiency (O-5) | synthetic first; real data only if separately authorized | exact sufficiency | analytic cases | rate–distortion curves | KnowledgeOS behaviour | choosing the loss post hoc |

**Order.** M1 → M2 → M3 can start without any corpus access (D7). M5 → M6 → M7 only after Gate D.

---

## 7. DDD architecture hypotheses and invariant register (Phase E)

**Every row is PROPOSED.** "Basis" says which proved property, source requirement or stakeholder decision would justify it.

| H | Hypothesis | Basis | Status |
|---|---|---|---|
| H-1 | **The history (event log) is authoritative; the kernel is a projection** | C-0′: a state is valid only with a deterministic update ⇒ the kernel = a left fold over events | follows from proved math, **if** T1's assumptions hold |
| H-2 | **Projections must be pure folds; stored answers without a fold are invalid** | C-0′(c): a snapshot R = d fails | follows from proved math |
| H-3 | **Events carry time, actor, authorization and oracle outcomes; a projection reads nothing external** | C-6(ii): input completeness | follows from proved math |
| H-4 | **Required Decision is a first-class, versioned domain concept; changing 𝔇 is a schema-migration event for the kernel** | T1: K is determined by 𝔇 | follows from proved math; versioning policy is PDR |
| H-5 | **Audit and separation requirements are expressed as required decisions** | R4(b) | follows from proved math |
| H-6 | **Anonymity policies are stated on histories, actors or outputs, never on internal states** | E3′ | follows from proved math; the policy content is PDR |
| H-7 | **Gap, Zero and ≼_EC are derived evaluations (queries) over (state, contract version), not stored state; a Zero claim is recomputable** | C-5: a function of state + contract | follows from proved math; recomputation-on-demand is an engineering choice |
| H-8 | **Provenance is append-only** | follows **only if** some required decision needs the full history (C-3); otherwise it is a stakeholder requirement | conditional; PDR |
| H-9 | **Evidence, proposition, proposal and authoritative decision are distinct categories; AI-generated proposals grant no authority** | not derivable from the math: governance requirement (already the project's practice) | stakeholder requirement; record as a decision, not as theory |
| H-10 | **ASSERT, LINK, REVISE, RETRACT and ISOLATE are the kernel's commands; REVISE and RETRACT append, never erase** | SOURCE-REPORTED (S2763, unverified); "never erase" is consistent with H-1 | needs Gate D (O-3) |

**Provisional context sketch [PROPOSED].**

| Context | Owns | Kind |
|---|---|---|
| **Evidence & Provenance** | the event log, the source register | aggregate: the stream; invariant: append-only, complete inputs |
| **Kernel** | the projection, its decoders | read model / projection; invariant: pure fold |
| **Contracts & Assessment** | contracts, 𝔇 versions, the gap | policy and specification objects; derived Δ |
| **Governance** | proposals, decisions, authorizations | aggregate: Decision; invariant: authority only through a recorded human act |

Each context needs its boundary confirmed against stakeholder requirements. No implementation is proposed.

**Failure and correction.** A correction is a new event (REVISE/RETRACT), never an edit. A wrong projection is fixed by fixing the fold and replaying (H-1, H-2).

---

## 8. Risk-ranked backlog

| # | Task | Depends on | Deliverable | Acceptance | Risk if skipped | Authorization | Stop condition |
|---|---|---|---|---|---|---|---|
| 1 | Filter review and count-only check | D1, D2 | review note; counts | F-1…F-8 closed; counts reported | leakage into the theory | D1, D2 | any hit on a previously read item |
| 2 | Correction set C-0′…C-8, R1′, E3′, R4 | D4 | a patch proposal (not applied) | reviewer signs off on each item | wrong theorems in the manuscript | D4 | a reviewer refutes an item |
| 3 | Independent formal review | D6 | review record | each PROVED item confirmed or refuted | same-model error | D6 | — |
| 4 | Gate D: five targets | 1 passed; D5 | read records; discrepancy list | every T-row has primary provenance | theory built on third-hand claims | D5 | discrepancy ⇒ report |
| 5 | Reconcile sources with the model | 4 | updated T-rows | assumptions (i)–(iv) checked against the sources | the model may not match KnowledgeOS | D3/D4 | an assumption fails ⇒ revise the model, with approval |
| 6 | O-1, O-2 (𝔊 and 𝔉 minimality) | none (analytic) | proofs or counterexamples | reviewed | — | none for the analysis; D7 for M3 | — |
| 7 | Finalize the theory | 3, 5 | manuscript v0.5 | Principal acceptance | — | D3, D4 | — |
| 8 | Architecture derivation | 7 | H-register → decisions | each H tied to a theorem or a decision | architecture from tickets, not theory | separate | — |
| 9 | Synthetic computation M1–M3 | D7 | test report | two independent implementations agree | uncaught counterexample | D7 | a counterexample ⇒ back to 2 |
| 10 | Principal review of theory and architecture | 7, 8 | submission | Principal decision | — | Principal | — |

---

## 9. Principal decision sheet (recommendations only; no answer is recorded here)

| # | What is being decided | Why it matters | Recommended | Alternatives and consequences | Blocks |
|---|---|---|---|---|---|
| D1 | Authorize an independent review of both filter scripts and the count-only source-ID check; name the reviewer | the filter is the only barrier between hold-out material and the theory; F-1 is fail-open | **Approve**, with a reviewer other than Session B | Decline: corpus reading stays stopped. Self-review: not independent. | everything in Phase C |
| D2 | Does G-LOG-0141 item 7 ("sealed lists forbidden") forbid only *showing* the lists, or also *using* them inside a script? | decides whether any filter is permitted at all | **"Use inside a script, never displayed"**, consistent with item 2 | Strict reading: no filter, so no corpus reading | D1, Gate D |
| D3 | Keep or roll back the v0.4 manuscript edits | baseline integrity | **Keep as a labelled draft baseline, pending D4** | Roll back to v0.3: Part III §15.7–15.8 removed | D4, task 7 |
| D4 | Accept C-0′…C-8, R1′, E3′, C-6′ and R4 as a *proposed* change set for review (not application) | stops known-wrong statements (decidable, partial order, vacuous R1, state-counted anonymity) from standing | **Approve as a proposal** | Defer: errors stay in the draft | task 2 |
| D5 | Conditionally authorize the five Gate-D reads after the gate passes | moves corpus claims from third-hand to primary | **Approve conditionally**, exactly the five targets | Broader reading: more exposure, not more rigour | tasks 4–5 |
| D6 | Commission an independent review of Part III, §3 of this report and R1′/R2/R4 | same-model proofs are not validation | **Approve**: e.g. the ChatGPT reviewer on supplied text only | None: proofs stay "unreviewed" | tasks 3, 7 |
| D7 | Authorize synthetic-only computation M1–M3 (no corpus, no S5, no Run-3) | cheap refutation of the theorems before review | **Approve** | Defer: analysis only | task 9 |
| D8 | Should "anonymity on histories, not states" (E3′) and "separation = decision" (R4) be passed to the architecture track as candidate requirements? | they are proved properties with direct design consequences | **Note as candidates; no adoption now** | Ignore until task 8 | task 8 |

---

## 10. Remaining TODOs, in priority order

1. D1 and D2, then the filter review (F-1…F-8, `norm()`), then the count-only check.
2. The correction-set patch proposal (D4), written as a diff for review, not applied.
3. Independent review (D6).
4. Gate-D reads (D5), with full/partial and verbatim/paraphrase records.
5. Read-log and source-register corrections (when edits are authorized).
6. O-1 (𝔊) and O-2 (encoding order), analytically; M1–M3 if D7 is granted.
7. Reconcile assumptions (i)–(iv) with the primary sources; O-3 (𝒪_core generation), O-6 (choice of equivalence).
8. Manuscript v0.5; H-register → architecture decisions; the Principal's review.

---

## 11. Candid assessment

**What is solid (mathematically, same-model):**
- The structural core is now coherent: kernel = Nerode quotient of the required decisions; a valid state is a fold over complete inputs; constraint-relative minimality is governed by join-closure.
- Join-closure is settled for the two policy classes that matter most in governance (anonymity-type and audit-type).
- The proofs are short and use standard tools, which makes independent review cheap.

**What is weak:**
- **No corpus claim is verified at primary-source level** for the five targets that carry the theory: the axioms, 𝒪_core, the gap order, v1.1 and T-7.
- Every proved item is unreviewed.
- The filter has a fail-open defect, so earlier access cannot yet be called safe.
- Two errors survived my own previous report: the vacuous R1 and the imprecise E3. That is direct evidence that same-model checking is insufficient.

**Distance to a robust theory:** roughly, the mathematical frame is near-complete for the deterministic case. The **link between that frame and KnowledgeOS is not yet evidenced.** The next real progress comes from three things, not from more reading or longer chapters:
- the safety gate;
- independent review;
- five targeted primary reads.

**Stop.** This report changes nothing outside `validation_reports/`. Phases C and D, and every manuscript change, wait for D1–D8.

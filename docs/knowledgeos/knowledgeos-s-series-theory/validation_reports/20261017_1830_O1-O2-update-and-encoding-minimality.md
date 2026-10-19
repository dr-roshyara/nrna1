# O-1 and O-2: update-restricted and encoding-level minimality

| Field | Value |
|---|---|
| Author | Session B (Professor), acting as worker and integrator |
| Date | 2026-10-17 18:30 |
| Extends | `20261017_1805_research-state-and-mathematical-review.md` §3.5 (R1′, R2, R4) and the open problems O-1 and O-2 listed there |
| Review | Two fresh-context reviewer sub-agents (`kos-theory-reviewer`): one for Part A, one for Part B. Both worked by hand only, with no file access beyond the draft. Verdict: **CONFIRMED WITH CORRECTIONS (both)**. All corrections are applied below. |
| Review strength | **Internal review, not independent.** The reviewers are the same model family. The status label is therefore **INTERNALLY REVIEWED**. D6 (an independent reviewer named by the Principal) is still required. |
| Inputs | None from the corpus. No computation was run (D7 not granted). No governed file was edited. |

## 0. Setting

**Histories and decisions.**
- Histories are h ∈ Σ\*; decisions are d : Σ\* → V_d, for d ∈ 𝔇.
- h ∼ h′ iff for every continuation w and every d, d(hw) = d(h′w). This ∼ is a right congruence and ∼ ⊆ ker d.

**Equivalences.**
- Eq(Σ\*) is the lattice of equivalences on Σ\*, ordered by ⊆. A larger relation is coarser.
- **Suff** = the right congruences contained in ∼.
- **Suff is closed under arbitrary joins** (made explicit at the reviewer's request). So joins taken in Suff and in Eq coincide, and ∼ is the top of Suff.

**Realizations.**
- The quotient realization of π ∈ Suff is S_π = Σ\*/π, u_π([h], a) = [ha], f_d([h]) = d(h).
- Every trim sufficient realization with kernel π is isomorphic to it.

**Admissibility.**
- 𝔊 is an iso-invariant class of trim pointed automata.
- 𝒢 := {π ∈ Suff : (S_π, [ε], u_π) ∈ 𝔊}.
- **R2:** 𝒢 has a greatest element ⇔ ⋁𝒢 ∈ 𝒢.

## Part A: O-1, restrictions on the update (INTERNALLY REVIEWED)

**A1 (joins decompose).** For a non-empty F, ⋁F is the directed union of its finite sub-joins. Hence 𝒢 is closed under all non-empty joins iff it is closed under **(J2)** binary joins and **(JD)** directed unions.

**A2 (quotient-closed update classes are inert).** Assumption (H): 𝔊 is closed under homomorphic images of trim pointed automata. Then:
1. 𝒢 is upward-closed in Suff.
2. If 𝒢 ≠ ∅, then ∼ ∈ 𝒢 and ∼ is the greatest element.
3. Hence 𝒢 ≠ ∅ iff the Nerode automaton is in 𝔊.

Examples (finite automata):
- at most N states;
- transition monoid in a pseudovariety (aperiodic, commutative, group, J-trivial, k-definite). This last point is from the literature.

**A3 (reversible, infinite-state updates break uniqueness).**
- *Class and decision:* 𝔊_inj (every u(·, a) injective). Σ = {a, b} and d = [h = ε]; then ∼ ∉ 𝒢.
- *Two admissible states:* π_L = "length" and π_W = "2·#a + 3·#b" are both admissible. They are incomparable: aaa and bb have equal weight but different lengths, and aa and bb have equal length but different weights.
- *Their join:* {ε} | Σ¹ | Σ^{≥2}. The chain bᵏ⁺¹ ≡_W a³bᵏ⁻¹ links every length ≥ 2. This join is not injective, and nothing in Suff lies between it and ∼.
- *Conclusion:* **no coarsest admissible state exists** (by R2).

**A4 (maximal states still exist).** 𝒢_inj is closed under non-empty directed unions. By Zorn's lemma, applied to the up-set {σ ∈ 𝒢 : π ⊆ σ}, every admissible π lies below a maximal admissible state. So under 𝔊_inj, (JD) holds but (J2) fails.

**A5 (quotient closure is sufficient, not necessary).**
- Take Σ = {a}, 𝔊_inj and d(aⁿ) = [n = 0]. Then 𝒢 = {identity}, which is trivially join-closed, although (H) fails.
- Here ∼ has 2 states, while the only admissible state is **infinite**.

**Reviewer correction:** finite injective automata are permutation automata, and they *do* satisfy (H). So A3–A5 are necessarily infinite-state phenomena.

**A6 (corrected summary).**

| Condition | Join-closed | Greatest element | Minimum index |
|---|---|---|---|
| (H): quotient-closed, iso-invariant | yes (upward-closed) | ∼, when 𝒢 ≠ ∅ | ∼; unique if finite |
| (J2) + (JD) | yes (iff, by A1) | ⋁𝒢 | ⋁𝒢; unique if finite |
| (JD) only (e.g. injective, infinite-state) | not necessarily (A3) | may not exist; maximal elements exist (A4) | — |

- **General fact (reviewer correction to the 1805 report and to my draft):** whenever a greatest element G exists, it has **minimum index**, because each π ⊆ G maps onto G's classes. It is the unique minimum when the index is finite. So uniqueness and minimum state count come together whenever a greatest element exists.
- **Withdrawn conjecture:** my "amalgamation" conjecture was mis-named (these are pushouts of quotients, not of embeddings). The reviewer showed it is provable but near-tautological: (J2) for all (Σ, 𝔇) ⇔ trim members of 𝔊 are closed under binary joins of their kernels. It is withdrawn as an open problem.
- **Remaining open question (O-1′):** an *intrinsic* characterization of which infinite-state update classes satisfy (J2). For example, are linear or affine updates over a group closed under binary joins of their kernels?

## Part B: O-2, encoding-level minimality (INTERNALLY REVIEWED)

### B1. Definitions

**Code space and admissibility.**
- A code space is a set B, for example {0,1}ⁿ.
- Admissibility is a predicate Adm_d(f, U), for a decoder f : B → V_d and a set U ⊆ B of codewords actually used.

**Encoded realizations.**
- An encoded realization of π ∈ Suff is an injection e : S_π → B, with initial codeword e([ε]).
- It is admissible iff for each d there is f with Adm_d(f, im e) and f ∘ e = f_d.
- **Updates are unrestricted in this part.**
- 𝒜^∃_B := {π ∈ Suff : π has an admissible encoding in B}.

**Heredity** (replaces my vague "codeword-independence"): if Adm_d(f, U) and U′ ⊆ U, then Adm_d(f, U′).
- *Hereditary:* "reads one bit", "linear over GF(2)".
- *Not hereditary:* "every fibre has ≥ k used codewords"; "f is 0 on the unused codewords".

### B2. Hereditary decoder restrictions are inert at partition level

If every Adm_d is hereditary, then 𝒜^∃_B is upward-closed in Suff. So if 𝒜^∃_B ≠ ∅, then ∼ ∈ 𝒜^∃_B and ∼ is the greatest element, within the same B.

*Proof.* Encode each coarser block by the codeword of one of its π-classes. Then im e′ ⊆ im e, so the same decoders work, and a fresh update is defined on the used codewords. ∎

*Weight:* the result is elementary. It needs π′ ∈ Suff and unrestricted updates.

### B3. Results on encodings

- **E4 (non-uniqueness up to symmetry).**
  - *Instance:* Σ = {a} and d(aⁿ) = [n mod 4 ∈ {1, 2}], so there are 4 classes. Use 2 bits and one-bit decoders.
  - *Symmetry group:* the decoder-preserving permutations of {0,1}² form a group of order 8 that acts freely on the 24 encodings. This gives 3 orbits, which are the 3 antipodal pairings.
  - *Result:* two of these orbits are admissible.
  - So **the minimal admissible encoding is not unique, even up to symmetry.**
  - Trim pointed automata have no non-trivial automorphisms, so this non-uniqueness is genuine.
- **E5 (forced redundancy).**
  - *Instance:* Σ = {a} and d_X = [n mod 3 = X], with one-bit decoders.
  - With 2 bits no admissible encoding exists; one-hot with 3 bits works.
  - So **the minimal bit count can exceed ⌈log₂ |∼|⌉.**
- **E6 (remark, downgraded).** When several costs count (bits, decoder or update complexity), the Pareto order on encodings is a preorder and *may* have several minima. The cost components are not yet defined, so choosing a scalar cost is a stakeholder or design decision.

### B4. Corrected classification

| Layer | Classification | Result |
|---|---|---|
| Minimal behavioural state | intrinsic | ∼, under hereditary decoder restrictions, unrestricted updates, and 𝒜^∃_B ≠ ∅ |
| Admissibility of a given encoding | representational | depends on the encoding (E1, 1752 report) |
| Minimal encoding | representational | unique at best up to symmetry; can fail even then (E4); can need extra bits (E5) |
| Encoding-level **update** restrictions | **UNCLASSIFIED / OPEN; possibly intrinsic** | A5 shows that update restrictions can bind at partition level |

## Consequences for the theory

**The answers to O-1 and O-2 are mathematical results only.** Whether they apply to KnowledgeOS is unverified, and it depends on Gate D.

1. **The minimal kernel is robust.** It remains the Nerode quotient under:
   - anonymity-type restrictions (R4a);
   - audit-type restrictions (R4b; with the audit separation added as a decision);
   - quotient-closed update restrictions (A2);
   - hereditary decoder restrictions (B2).

   In each case, if any admissible state exists, the minimal one is unique and has minimum state count.
2. **The only failures found so far** are cap policies (E2), infinite-state reversible updates (A3), usage-dependent anonymity (E3′) and encoding-level update restrictions (open).
3. **Encoding choice is a design space, not part of the theory.** It is non-unique (E4), it has redundancy costs (E5), and it is multi-objective (E6).

## Architecture implications (PROPOSED; not requirements)

- **H-11.** Any proposed constraint on how the kernel *updates* (reversibility, linearity, incremental-only computation) must be checked against A2 and A3 *before* adoption, because it can destroy the uniqueness of the kernel or make it infinite.
- **H-12.** State-encoding choices (schemas, bit layouts) belong to infrastructure. They do not change the domain kernel (B2), so the domain model should be specified at the partition level.

## Status and next step

| Item | Status |
|---|---|
| A1, A2, A4, A5, B2, E4, E5 | INTERNALLY REVIEWED; awaiting D6 |
| A3 | COUNTEREXAMPLE, internally reviewed |
| A6 correction (minimum index) | applied; it also corrects the 1805 report's R2 corollary, which is still correct but too narrow |
| O-1′, the encoding-level update problem | OPEN |
| E6 | REMARK |

**Next authorized analytical task:** Task 5. Run the same internal review on the 1805 report's core (C-0′, C-1⁺, C-2, C-3, C-5, C-6′, R1′, R2, R4, E1–E3′), then assemble the reviewed core table.

# Chapter 4. Constrained state: policies, decoders, updates and schemas

*Status: INTERNALLY REVIEWED, two rounds (same model family; round 1 found an error in Thm 4.5(b),(c); round 2 found an overreach in the audit-requirement claim, now qualified with CE 4.5e; all corrections applied). Not externally reviewed. Late additions not re-reviewed: CE 4.5e.*

**Research question (RQ2).** Real systems do not permit every realization. Privacy or audit policies, limited decoders and restricted update rules all remove options. When does a unique minimal permitted state still exist?

**Plain meaning.**
- **Anonymity-type rules** ("coarser is always allowed") never change the minimal state. They only decide whether **any** permitted state exists.
- **Audit-type rules** of the form "tell apart whatever differs in attribute X" act exactly like extra required decisions. Arbitrary lists of pairs that must be told apart do not (CE 4.5e).
- **Update rules that survive merging states** leave the canonical state optimal. **Reversible updates on an infinite state** can destroy uniqueness.
- **Restrictions on how decisions are read from a fixed code space** (hereditary ones) do not change the minimal state.
- **Cap-type rules** ("a state may combine at most two sources") **can** produce many incomparable minima. One checked instance is given.
- The **information** in the state is fixed; the **schema** (which fields store it) is a design choice.

## 4.1 Admissible families and the existence criterion

**DEF 4.1.**
- **Suff** = Suff(𝔇) := {π ∈ Eq(Σ\*) : π is a right congruence and π ⊆ ∼_𝔇}. The decision set is written explicitly when it varies, e.g. Suff(𝔇 ∪ {d_ρ}).
- **Suff = the kernels of trim sufficient realizations (THM):**
  - (⊆) by Lemma 2.9 and Thm 3.4;
  - (⊇) for π ∈ Suff, the quotient (Σ\*/π, [ε], u_π([h], a) := [ha], f_d([h]) := d̂(h)) is well-defined (since π ⊆ ∼ ⊆ ker d̂), trim and sufficient, with kernel π.
- The **index** of π is |Σ\*/π|.
- An **admissible family** is any 𝒜 ⊆ Suff, given by a policy or by restrictions on realizations.
- A **coarsest admissible state** is a greatest element of (𝒜, ⊆).

**Lemma 4.2. THM.** Suff is closed under arbitrary joins in Eq(Σ\*), and ∼ is its top element.

*Proof.*
- **Right congruence:** let (h, h′) ∈ ⋁Π be witnessed by a chain h = h₀ π₁ h₁ … π_k h_k = h′. Then ha = h₀a π₁ h₁a … h′a, because each πᵢ is a right congruence.
- **Contained in ∼:** each πᵢ ⊆ ∼, and ∼ is transitive.
- **Top:** ∼ ∈ Suff (Prop. 3.2). ∎

**Theorem 4.3 (existence criterion). THM.** 𝒜 has a greatest element iff ⋁𝒜 ∈ 𝒜. The greatest element is then ⋁𝒜. If it has finite index, it is the **unique** admissible element of minimum index.

*Proof.*
- **(⇐)** ⋁𝒜 is above every member.
- **(⇒)** A greatest element g lies above every member, so g ⊇ ⋁𝒜. Being a member, g ⊆ ⋁𝒜.
- **Minimum index:** every π ∈ 𝒜 satisfies π ⊆ g, so π-classes map onto g-classes, and index(π) ≥ index(g). If the indices are equal and finite, the refinement π ⊆ g has the same finite number of classes, so π = g.
- **Empty family:** for 𝒜 = ∅, ⋁∅ is the identity relation, which is not in ∅. So both sides are false. ∎

**Lemma 4.4 (joins decompose). THM.** For non-empty Π, ⋁Π is the union of the **directed** family of finite sub-joins. Hence 𝒜 is closed under non-empty joins iff it is closed under binary joins (J2) and under directed unions (JD).

*Proof.*
- **Directed:** the finite sub-joins form a directed family under inclusion.
- **Union is an equivalence:** a directed union of equivalences is an equivalence; any two related pairs lie in one member.
- **Union = join:** the union is an equivalence containing ∪Π, so it contains ⋁Π. Every finite sub-join lies inside ⋁Π, so the union is contained in ⋁Π. ∎

**Remark (THM + CE).**
- **Under ACC, (J2) suffices.** If 𝒜 satisfies the ascending chain condition, a directed family has a maximum.
- **Without ACC, (J2) can fail to suffice.**
  - *Witness:* Σ = {a, b}, L = Σ\*, 𝔇 = {λ}. Let K_n be the set of words containing a factor b aᵏ b with k ≤ n, and π_n the relation "h = h′, or both are in K_n".
  - Each π_n is a right congruence in Suff, since K_n is closed under appending. The π_n form a strictly ascending chain.
  - The chain is closed under binary joins, but its union (relate any two words with at least two b's) is not in the chain. So the family has no greatest element.

*Note:* the wording of this remark was disputed in the earlier record (U-1). This form, together with the witness, **addresses** the dispute. **Pending external review.**

## 4.2 Policy classes that preserve uniqueness

**Theorem 4.5. THM.**

**(a) Upward-closed policies.** Let U ⊆ Suff be **upward-closed in Suff**: π ∈ U and π ⊆ π′ ∈ Suff imply π′ ∈ U. If U ≠ ∅, then ∼ ∈ U, and ∼ is the greatest element.

**(b) Separation policies.** For a fixed ρ ∈ Eq(L), let D_ρ := {π ∈ Suff : π ∩ (L × L) ⊆ ρ}. Let d_ρ : L → L/ρ be d_ρ(h) := [h]_ρ, extended by OD. Then D_ρ = Suff(𝔇 ∪ {d_ρ}), and its greatest element is ∼′ := ∼_{𝔇∪{d_ρ}}.

**(c) Mixtures.** Here ∼′ ∈ D_ρ. Moreover, U ∩ D_ρ ≠ ∅ ⇔ ∼′ ∈ U, and in that case ∼′ is the greatest element of U ∩ D_ρ.

**(d) Downward-closed policies.** A downward-closed D ⊆ Suff has a greatest element iff D = {π ∈ Suff : π ⊆ g} for some g ∈ Suff, i.e. iff D is **principal**.

*Proof.*
- **(a)** Take any π ∈ U. Since π ⊆ ∼ ∈ Suff, upward closure gives ∼ ∈ U.
- **(b)**
  - *Key observation:* for a right congruence π ⊆ ∼, no legal history is related to an illegal one, since λ ∈ 𝔇. So π ⊆ ker d̂_ρ ⇔ π ∩ L² ⊆ ρ. Illegal pairs have d̂_ρ = ⊥ = ⊥.
  - *Conclusion:* π ∈ D_ρ iff π is a right congruence, π ⊆ ∼ and π ⊆ ker d̂_ρ. By Prop. 3.2(d) applied to 𝔇 ∪ {d_ρ}, that holds iff π ⊆ ∼′.
- **(c)**
  - ∼′ ∈ D_ρ by (b).
  - (⇒) If U ∩ D_ρ ≠ ∅, take π in it. Then π ⊆ ∼′ ∈ Suff, and U is upward-closed, so ∼′ ∈ U. Being the greatest element of D_ρ, ∼′ is the greatest element of U ∩ D_ρ.
  - (⇐) If ∼′ ∈ U, then ∼′ ∈ U ∩ D_ρ, which is therefore non-empty.
- **(d)** (⇐): the greatest element is g. (⇒): if g is greatest, then D ⊆ ↓g, and downward closure gives ↓g ⊆ D. ∎

**Plain meaning of (b).** **An audit requirement of the form "distinguish whenever ρ-inequivalent" (ρ an equivalence) is a required decision.** "Must distinguish actors" (ρ = ker actor) is formally the same as adding "which actor?" to 𝔇.

**CE 4.5e (arbitrary must-distinguish sets are not decisions).**
- *Setting:* Σ = {a, b, c}, L = Σ\*, 𝔇 = {λ}, and a requirement "never identify the pair (a, b)": P = {(a, b)}, with family {π ∈ Suff : (a, b) ∉ π}.
- *Two admissible congruences:* the right congruence generated by (a, c) avoids P, and so does the one generated by (c, b).
- *Their join* contains (a, b).
- *Conclusion:* the family is downward-closed but not principal, so there is no greatest element (Thm 4.5(d)).
- **So only requirements shaped as an equivalence ρ reduce to decisions.** Pair-wise must-distinguish lists in general do not.

**Instance of (a):** k-anonymity counted on **histories**: every legal class has at least k histories. Coarsening only enlarges classes, so the policy is upward-closed.

## 4.3 Where uniqueness fails

**CE 4.6 (cap policy: many incomparable maxima). CE + FIN.**
- *Setting:* Σ = {a, b, c}, L = Σ\*, 𝔇 = {λ}. Policy: every class contains histories from at most 2 distinct first symbols; ε carries no symbol.
- *Results:*
  - no admissible right congruence has 1 or 2 classes. *Proof (2 classes):* the quotient is a 2-state trim automaton. Either its two states reach each other, so each state's class contains histories starting with every letter (3 first symbols); or one state B is absorbing, in which case B is reachable from every successor of ε, and B's class sees all 3 first symbols;
  - exactly **6** admissible 3-class congruences exist, all maximal;
  - **141** maximal admissible 4-class congruences exist.
- *Evidence for the counts:* independent enumerations by Session B's earlier checks and by the Ch. 4 reviewer agree on 6 and 141. Further implementations are recorded in `../synthetic-checks/`.
- *Conclusion:* the policy is downward-closed but not principal (Thm 4.5(d)). There is no greatest element, and the minimum index (3) is attained non-uniquely.

**Theorem 4.7 (history-counted anonymity can be infeasible). THM.** For **trim** realizations, a sufficient realization all of whose **non-empty** decoder fibres contain at least k states exists iff every attained value of every decision is attained by at least k histories.

*Remark.* The set of illegal histories is infinite whenever it is non-empty, so ⊥ never violates the **history-count** condition. In the identity witness, its fibre is infinite. In other realizations, such as C_𝔇, the ⊥-fibre may be a single sink state.

*Proof.*
- **(⇒)** Distinct reachable states come from distinct histories. So a fibre of size ≥ k contains ≥ k histories with that value.
- **(⇐)** Take the identity realization (state = history). Its fibre for a value v is the set of histories with value v, which has ≥ k elements by assumption. ∎

*Remarks.*
- The (⇐) witness is **infinite-state**. With finite state required, (⇐) is **OPEN**.
- With unreachable padding states, a state-counted condition can **always** be met.
- **ARCH:** privacy rules should count histories, and, by extension not shown here, actors or released outputs, rather than internal states.

## 4.4 Restrictions on updates

Let 𝔊 be a class of trim pointed automata, closed under isomorphism, and let 𝒢 := {π ∈ Suff : (Σ\*/π, [ε], u_π) ∈ 𝔊}.

**Theorem 4.8 (quotient-closed update classes are harmless). THM.**
- *Hypothesis:* 𝔊 is closed under images of onto homomorphisms of pointed automata.
- *Claim:* 𝒢 is upward-closed in Suff. So if 𝒢 ≠ ∅, ∼ is its greatest element, and 𝒢 ≠ ∅ iff C_𝔇's automaton is in 𝔊.

*Proof.* For π ⊆ π′, the map [h]_π ↦ [h]_{π′} is an onto homomorphism of pointed automata, so Σ\*/π′ is a homomorphic image of Σ\*/π. Then apply Thm 4.5(a). ∎

*Examples:*
- "at most N states";
- for finite automata: transition monoid in a pseudovariety (aperiodic, commutative, group, J-trivial, k-definite). *Literature, unverified here:* Eilenberg.

**CE 4.9 (reversible updates on an infinite state destroy uniqueness).**
- *Setting:* 𝔊 = every letter acts injectively. Σ = {a, b}, L = Σ\*, 𝔇 = {λ, d} with d = [h = ε]. Then ∼ = {ε} | Σ⁺, and ∼ ∉ 𝒢.
- *Two admissible states:*
  - the length kernel (state ℕ, every letter maps n to n + 1);
  - the kernel of 2·#a + 3·#b (state ℕ ∖ {1}; a maps n to n + 2, b maps n to n + 3).

  Both are injective and lie inside ∼.
- *Incomparable:* aaa and bb have equal weight but different lengths; aa and bb have equal length but different weights.
- *Their join:* {ε} | Σ¹ | Σ^{≥2}.
  - Every length ≥ 2 is linked, because b^{k+1} and a³b^{k−1} have equal weight.
  - Weights 2 and 3 occur only at length 1.
- *No greatest element:* in the join, a maps both Σ¹ and Σ^{≥2} into Σ^{≥2}, so it is not injective. The only element of Suff strictly coarser than the join is ∼, which is also not in 𝒢. Hence ⋁𝒢 ∉ 𝒢, and by Thm 4.3 there is no greatest element.

**Proposition 4.10. THM.** For the injective update class, and for any 𝔇, 𝒢 is closed under non-empty directed unions. Hence every π ∈ 𝒢 lies below a maximal element of 𝒢 (by Zorn's lemma applied to {σ ∈ 𝒢 : π ⊆ σ}).

*Proof.* Let U be the union of a directed family. Suppose ¬(x U y) but xa U ya. Then xa π ya for some π in the family, while ¬(x π y). So u_π is not injective, a contradiction. ∎

*Remark.* Finite injective automata are permutation automata. They are closed under quotients, so they fall under Thm 4.8. The failure in CE 4.9 is therefore an **infinite-state** phenomenon.

## 4.5 Restrictions on decoders, and encodings

**Setting.**
- A code space B, with an encoding e : Σ\*/π → B (injective).
- Decoder admissibility is a predicate Adm_d(f, U), where U = im e is the set of codewords used.
- Adm is **hereditary** iff Adm_d(f, U) and U′ ⊆ U imply Adm_d(f, U′).
- Updates are unrestricted.
- An **admissible encoding** of π into B is an injective e : Σ\*/π → B together with decoders f_d on B such that Adm_d(f_d, im e) holds and f_d(e([h])) = d̂(h) for all h and d.

**Theorem 4.11 (hereditary decoder restrictions are harmless). THM.**
- *Claim:* the family of π ∈ Suff with an admissible encoding into B is upward-closed in Suff. So, if it is non-empty, ∼ is its greatest element, encodable in the same B.

*Proof.*
- *New encoding:* for π ⊆ π′, encode each π′-class C by the codeword of one chosen π-class inside C. This choice may require the axiom of choice. It is not needed when Σ\* is well-orderable, e.g. when Σ is countable (take the shortlex-least history).
- *Injective:* e′ is injective, because distinct π′-classes contain disjoint π-classes.
- *Decoders:* the new set of used codewords is contained in the old one, so heredity keeps the **same** decoders admissible. They still compute d, because d̂ is constant on π′-classes (π′ ⊆ ∼).
- *Update:* u′(e′(C), a) := e′([ha]_{π′}) for h ∈ C. This is well-defined because π′ is a right congruence. It is arbitrary off the used codewords. The initial codeword is e′([ε]_{π′}). ∎

**CE 4.12 (encodings are a design space). CE + FIN.**
*Definition.* A **one-bit decoder** on {0,1}ⁿ is a single bit or its negation (or a constant). The order-8 symmetry group of {0,1}² (bit permutations and complements) preserves this decoder class.

- **(a) Admissibility depends on the encoding.**
  - *Setting:* 4 classes labelled 0–3, d true on {1, 2}, one-bit decoders.
  - Binary encoding: d = b₁ ⊕ b₂, not admissible. Gray encoding: d = b₂, admissible.
  - *Dynamic instance:* Σ = {a}, d(aⁿ) = [n mod 4 ∈ {1, 2}]. The Gray encoding also gives single-bit-flip updates.
- **(b) Not unique even up to symmetry.**
  - The 24 bijections fall into 3 orbits under the order-8 symmetry group of the one-bit decoders.
  - 2 of the 3 orbits are admissible.
- **(c) Redundancy may be forced.**
  - *Setting:* 3 classes, with decisions d_i = [class = i] for i = 0, 1, 2.
  - No 2-bit admissible encoding exists; 3 bits are needed.

## 4.6 Information versus schema

*Definitions.* A set of state components is **sufficient** iff the state they form is recursively sufficient: it admits an update and decoders (DEF 2.7). It is **irredundant** iff no proper subset is sufficient.

**CE 4.13 (component sets are not unique). CE.**
- **Static case** (decoder-only notion; no update involved): components x, y and x⊕y, with d = x⊕y. Both {x⊕y} and {x, y} are irredundant and determine d, but they have different sizes.
- **Flip dynamics** (inputs toggle x or toggle y; start (0,0)): {x⊕y} realizes ∼ (2 classes). {x, y} realizes a strictly finer partition (4 classes).
- **Overwrite dynamics** (inputs x:=0, x:=1, y:=0, y:=1; start (0,0)): ∼ has 4 classes, and {x⊕y} is no longer sufficient. The three pairs {x, y}, {x, x⊕y} and {y, x⊕y} each realize ∼ itself. *Evidence:* checked independently by the reviewer, for this alphabet and for a set-to-1 variant.

**Conclusion. THM (Thm 3.8) + CE.**
- The **partition** ∼ is unique (Thm 3.8).
- **Component schemas** realizing it are not unique, and **the update alphabet constrains which schemas are viable**.
- Choosing a schema is an engineering decision (**ARCH**). It is not part of the information.

## 4.7 Limitations and open problems

- **OPEN:** an intrinsic characterization of which infinite-state update classes satisfy (J2).
- **OPEN:** whether encoding-level **update** restrictions can change the minimal partition.
- **OPEN:** Thm 4.7 when finite state is required.
- **OPEN:** CE 4.6 for 5 or more classes.

## 4.8 Traceability (ARCH)

| Result | Engineering consequence |
|---|---|
| Thm 4.5(b) / CE 4.5e | audit requirements of the form "distinguish by attribute X" are written as decisions in 𝔇; pair-list requirements are flagged, since they can break uniqueness |
| Thm 4.7 | privacy rules count histories, not internal states. Counting actors or outputs is a recommended extension, not shown here |
| Thm 4.8 / CE 4.9 | any proposed update restriction (reversibility, incremental-only) is checked against these results before adoption |
| Thm 4.11 / CE 4.12 / CE 4.13 | **recommendation:** specify domain models at the partition level, and treat schemas and encodings as design choices. *Caveat:* the update alphabet constrains which schemas are viable (CE 4.13), so schema choice is not independent of domain behaviour |
| CE 4.6 | cap-type aggregation policies are flagged: they **can** break uniqueness |

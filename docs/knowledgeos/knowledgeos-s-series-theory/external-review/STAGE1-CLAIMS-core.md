# D6 review packet, stage 1: definitions and claims only (no proofs)

## Definitions

**Histories and decisions.**
- Σ is a non-empty alphabet; histories are h ∈ Σ\*, with ε the empty history.
- A decision is d : Σ\* → V_d, and 𝔇 is a set of decisions.

**Nerode relation ∼.** h ∼ h′ iff, for every w ∈ Σ\* and every d ∈ 𝔇, d(hw) = d(h′w).

**The lattice of equivalences.**
- Eq(Σ\*) is the set of equivalence relations on Σ\*, ordered by ⊆; a larger relation is coarser.
- ⋁ is the join (transitive closure of the union).
- Suff = the right congruences contained in ∼.

**Realizations.**
- A realization is (S, s₀, u : S×Σ→S, (f_d)), with s(h) = u\*(s₀, h).
- It is **trim** if s is surjective.
- It is **(recursively) sufficient** if d = f_d ∘ s for all d.
- A map R : Σ\* → S is **predictively sufficient** if ker R ⊆ ∼.
- The quotient realization of π ∈ Suff is S_π = Σ\*/π, with u_π([h], a) = [ha].

**Admissible families.**
- An admissible family is a subset 𝒜 ⊆ Suff, defined by a policy or by permitted decoder classes 𝔉 / update classes 𝔊.
- **Coarsest admissible state:** the greatest element of (𝒜, ⊆).

**Gap.** For a contract, Δ(K) is the set of unmet requirements of state K. K ≼ K′ iff Δ(K′) ⊆ Δ(K). Zero means Δ = ∅.

## Claims to test

Try to refute each claim before reading any argument for it.

### Sufficiency and decidability

- **C-0′a.** Recursive sufficiency implies predictive sufficiency.
- **C-0′b.** Predictive sufficiency does not imply that the map R itself admits an update u with R(ha) = u(R(h), a).
- **C-0′c.** For d(h) = [h ends with "ab"] over Σ = {a, b}, R = d is not predictively sufficient.
- **C-1.** The problem "given a Turing machine M (defining a total computable d_M) and histories h, h′, decide h ∼ h′" is undecidable; in fact Π⁰₁-complete.
- **C-1⁺.** A single fixed d, computable in polynomial time, exists whose ∼ is undecidable.
- **C-6.** Assume:
  - (i) each d is a function of the history;
  - (ii) Σ contains every input decisions depend on;
  - (iii) every d is total, or definedness is observable;
  - (iv) decoders and updates are unrestricted.

  Then ∼ is a right congruence, and Σ\*/∼ is the coarsest recursively sufficient realization, unique up to isomorphism.
- **C-6′.** If undefined values are treated as "don't care", the canonical quotient is lost: minimal realizations become non-unique. Literature claim, unverified: minimization is then NP-complete for explicit finite machines.

### Components and compression

- **C-2.** Whether a component is necessary depends on which other components are kept. Irredundant ≠ minimum-cardinality, and minimal component sets need not be unique.
  - *Static instance:* components x, y, x⊕y with d = x⊕y.
- **C-3.** No compression is possible (Σ\*/∼ is the identity on Σ\*) iff the joint ∼ of 𝔇 is the identity. No single decision needs to require the full history.

### Gap order

- **C-5.** ≼ is a preorder on states and a partial order on gap-equivalence classes. Zero is the top class only if some state attains Δ = ∅.

### Admissible families

- **R1′.** Suppose 𝔉 and 𝔊 are bijection-invariant and 𝒜 is defined over trim realizations. Then the admissibility of a trim sufficient realization depends only on its kernel.
- **R2.** 𝒜 has a greatest element ⇔ ⋁𝒜 ∈ 𝒜. A greatest element, when it exists, has minimum index, and it is the unique minimum if its index is finite. 𝒜 is closed under non-empty joins ⇔ it is closed under binary joins and under directed unions.
- **R4.** Ordering is within Suff.
  - (a) An upward-closed non-empty family contains ∼.
  - (b) For an equivalence ρ, D_ρ = {π ∈ Suff : π ⊆ ρ} is join-closed, and its greatest element is the Nerode relation of 𝔇 ∪ {d_ρ}, where d_ρ(h) = [h]_ρ.
  - (c) U ∩ D_ρ, with U upward-closed and the intersection non-empty, has that same greatest element.
  - (d) A downward-closed family has a greatest element iff it is principal.

### Update restrictions (O-1)

- **A2.** Suppose 𝔊 is iso-invariant and closed under homomorphic images of trim automata. Then 𝒢 = {π ∈ Suff : u_π ∈ 𝔊} is upward-closed. So, when 𝒢 is non-empty, ∼ is its greatest element.
- **A3.** Take 𝔊 = every letter acts injectively, Σ = {a, b} and d = [h = ε]. Then 𝒢 has no greatest element: the length kernel and the kernel of 2·#a + 3·#b are both admissible, and their join is not.
- **A4.** In the injective case, 𝒢 is closed under non-empty directed unions, so maximal elements exist.
- **A5.** Take Σ = {a}, injective updates and d(aⁿ) = [n = 0]. Then 𝒢 = {identity}. So closure under quotients is sufficient for join-closure but not necessary.

### Decoder restrictions and encodings (O-2)

- **B2.** Suppose the decoder admissibility predicate Adm_d(f, U), where U is the set of codewords used, is hereditary in U, and updates are unrestricted. Then the family of partitions with an admissible encoding into a fixed B is upward-closed in Suff.
- **E1.** Take 4 classes with d true on {1, 2}, and one-bit decoders. The binary encoding is not admissible; the Gray encoding is.
- **E4.** Take n = 2 bits and one-bit decoders (any function of a single bit). There are 24 encodings of 4 classes with d true on {1, 2}, forming 3 orbits under the hyperoctahedral group of order 8. Exactly 2 of these orbits are admissible.
- **E5.** Three classes with one-hot decisions d_X have no admissible 2-bit encoding under one-bit decoders; a 3-bit one exists.

### Cap policy

- **E2 (corrected).** Take Σ = {a, b, c}, a constant 𝔇, and the policy "every class contains histories from at most 2 distinct first symbols". Then:
  - no admissible right congruence has 1 or 2 classes;
  - there are exactly 6 admissible right congruences with 3 classes, up to isomorphism;
  - there are 141 maximal admissible right congruences with 4 classes, i.e. ones with no admissible proper coarsening.

  So the policy has no greatest element and many maxima. (These counts come from a synthetic computation; check them by hand where you can, and test the reasoning.)
- **E3′.** Assume trim realizations, and decoders whose every non-empty fibre contains ≥ k states. Such a realization exists iff every attained value of every d is attained by at least k histories. With unreachable padding states, the condition can always be met.

### Dynamic versions of static examples (O-7)

- **O7a.** Take Σ = {a} and d(aⁿ) = [n mod 4 ∈ {1, 2}].
  - There are 4 Nerode classes.
  - The Gray encoding admits a one-bit decoder; the binary encoding does not.
  - Under an extra update restriction, "each input flips at most one bit", the Gray encoding is admissible and the binary encoding is not.
- **O7b.** Take flip dynamics: Σ = {fx, fy} toggling x or y, start (0, 0), and d = x⊕y.
  - ∼ has 2 classes.
  - {x⊕y} is sufficient and updatable.
  - {x, y} is sufficient and updatable, and its partition is strictly finer.
- **O7c.** Take overwrite dynamics: Σ = {x0, x1, y0, y1}, start (0, 0), and d = x⊕y.
  - ∼ has 4 classes.
  - {x⊕y} alone is not sufficient.
  - {x, y}, {x, x⊕y} and {y, x⊕y} are each sufficient, updatable, irredundant, and each induces ∼ itself.

### Evidence

- **B6.** "6 of 6 cases passed" yields a one-sided 95% Clopper–Pearson lower bound of 0.05^{1/6} ≈ 0.607, only under i.i.d. sampling. A single valid witness refutes a universal identity claim.

## Additional claims (O-5, O-6; in the form stated after internal review)

### O-5 (approximation)

**Setting.** Deterministic decisions; recursive realization (S, s₀, u, f). Expected loss:

L_μ = Σ_t w_t E_μ ρ(f(s(H_t)), d(H_t))

- **O5-1.** If u is deterministic and f(s(h)) = d(h) for all h, then ker s ⊆ ∼.
- **O5-2.** Assume ρ(a, b) = 0 ⇔ a = b. If μ gives positive mass to every history of length ≤ T and w_t > 0 for t ≤ T, then L_μ = 0 ⇔ there is no error on any history of length ≤ T. This does **not** imply ker s ⊆ ∼.
- **O5-3.**
  - *Setting:* overwrite dynamics Σ = {x0, x1, y0, y1}, inputs i.i.d. uniform; decisions d_x = x and d_y = y, equally weighted; a 2-state budget.
  - *Claim:* "track x" and "track y" are the only optimal 2-state realizations. Their losses are equal and their partitions are incomparable.
- **O5-4.**
  - A penalty λ that is fixed independently of the weights can be outbid.
  - With fixed weights, a finite candidate set and violations bounded away from 0, a large enough λ reproduces the lexicographic optimum.
- **O5-5** (numerical; check it).
  - *Setting:* machine M with δ(0,·) = (1,1), δ(1,·) = (1,2), δ(2,·) = (0,2); outputs [0,1,0]; i.i.d. uniform inputs; L = (1/7) Σ_{t=0..6} P[error].
  - *Claim 1:* the best 2-state realization (remember "the last input was 0") has loss 55/224.
  - *Claim 2:* M has no 2-block congruence. So the only coarsening within the budget is the constant predictor, with loss ≈ 0.4241.

### O-6 (nondeterminism)

- **N-a.** Assume a declared convention for blocked runs (empty set observable, or a ⊥ sink). Then "equal sets of possible decision values after every continuation" is a right congruence. It is the Nerode relation of the belief-set system, and it has a unique minimal deterministic quotient. It is strictly coarser than bisimulation.
- **N-b.** With acceptance-only decisions, this set semantics merges a.(b+c) and a.b+a.c, just as trace equivalence does. It separates them only if readiness or refusals are decision values, or if blocking is observable.
- **N-CE1.** L = 0\*(ε|1) has two non-isomorphic minimal 2-state NFAs.
- **N-CE3.** Merging NFA states whose future languages are equal preserves the language. This is sufficient, not necessary. *Counterexample to necessity:* s –a→ p, s –a→ q, p and q final, q –b→ f.
- **H-13** (a modelling claim). Recording every choice outcome as an input event yields a deterministic kernel over the extended alphabet. This holds only if:
  - (a) the outcomes are observable when the decision is taken;
  - (b) the decisions are restated over the extended alphabet;
  - (c) the order of choice events is fixed.

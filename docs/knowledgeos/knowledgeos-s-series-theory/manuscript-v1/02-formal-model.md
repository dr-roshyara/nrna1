# Chapter 2. The formal system model

*Status: INTERNALLY REVIEWED (same model family; refute-first review 2026-10-18; all corrections applied). Not externally reviewed.*

**Purpose.** To fix one vocabulary, used by every later chapter, for inputs, histories, decisions, internal states and records. To prove the one lemma on which both Part II and Part III rest: three natural notions of "holding less information" coincide.

**Plain meaning.** A system is described by what it receives (inputs), what it must answer (decisions), how it updates its state (realizations), and what it writes down (records). One piece of information is "less" than another exactly when the first can be computed from the second.

## 2.1 Inputs and histories

**DEF 2.1 (inputs, histories).**
- Σ is a non-empty set of **inputs**.
- A **history** is a finite sequence h = a₁…a_n ∈ Σ\*. ε is the empty history, |h| is its length, and h_t is its prefix of length t.
- hw denotes concatenation.

**DEF 2.2 (legal histories).** A **legal language** is a set L ⊆ Σ\* that contains ε and is **prefix-closed**: if hw ∈ L, then h ∈ L.

**ASM IC (input completeness).** Every datum on which a required decision depends is part of some input letter. This includes time, the acting party, authorization and the outcomes of external queries.

*Why it is needed:* without IC, a decision is not a function of the history, and none of the objects below is well-defined. *Architecture:* events must carry these data (Ch. 15).

## 2.2 Decisions

**ASM DET (determinism).** In the real system, each required decision is a function of the input history.

*Note:* inside the formalism, DEF 2.3 already makes every decision a map on L, so DET is automatic there. DET is the claim that the real system can be modelled this way. Ch. 6 relaxes it.

**DEF 2.3 (decisions, observable definedness).**
- A **decision** is a map d : L → V_d. 𝔇 is a set of decisions.
- **Observable definedness (OD):** each d is extended to Σ\* by d̂(h) := d(h) for h ∈ L, and d̂(h) := ⊥ otherwise, where ⊥ ∉ V_d.
- **Convention:** the legality decision λ(h) := [h ∈ L] is a member of 𝔇. In particular 𝔇 ≠ ∅. If 𝔇 contains any other decision, λ is recoverable from it anyway, as λ(h) = [d̂(h) ≠ ⊥]. Including λ explicitly covers the case where it is the only decision.
- **Empty intersections:** ⋂ over an empty family of kernels is Σ\* × Σ\*.
- The **decision vector** is 𝔇(h) := (d̂(h))_{d∈𝔇}.

*Why OD:* without it, "undefined" is treated as "don't care", and canonicity fails (Ch. 3, CE 3.9). OD says illegality is itself an observable outcome. In this setting that is a modelling choice, not a theorem.

## 2.3 Equivalences and the information order

**DEF 2.4.**
- For a set A, Eq(A) is the set of equivalence relations on A, ordered by inclusion. π ⊆ π′ means π is **finer** and π′ is **coarser**.
- The **kernel** of a map f with domain A is ker f := {(a, b) : f(a) = f(b)}.
- Dist(f) := (A × A) ∖ ker f is the set of pairs f **distinguishes**.

*Fact (LIT, standard):* Eq(A) is a complete lattice. Meets are intersections; the empty meet is A × A. The join ⋁Π is the **reflexive**-transitive closure of the union ∪Π; the empty join is the identity relation.

**DEF 2.5 (information order).** For maps f, g with the same domain A, f ⊑ g ("f holds no more information than g") iff f = φ ∘ g for some map φ.

**Lemma 2.6 (three orders coincide). THM.** For maps f, g on A, the following are equivalent:
- (i) f ⊑ g;
- (ii) ker g ⊆ ker f;
- (iii) Dist(f) ⊆ Dist(g).

*Proof.*
- **(i) ⇒ (ii).** If g(a) = g(b), then f(a) = φ(g(a)) = φ(g(b)) = f(b).
- **(ii) ⇒ (i).** On the image of g, define φ(g(a)) := f(a). This is well-defined: if g(a) = g(b), then (a, b) ∈ ker g ⊆ ker f, so f(a) = f(b). Outside the image of g, define φ arbitrarily. Then f = φ ∘ g.
- **(ii) ⇔ (iii).** Take complements in A × A. ∎

*Consequence.* ⊑ is a preorder on maps. Two maps are ⊑-equivalent iff they have equal kernels. **The kernel is the information content; the map is a representation of it.** Every "minimal" object in this manuscript is a greatest element, in ⊆, of a family of kernels.

## 2.4 Realizations (internal state with an update)

**DEF 2.7 (realization).** A **realization** is R = (S, s₀, u, (f_d)_{d∈𝔇}), with:
- a state set S;
- an initial state s₀ ∈ S;
- an update u : S × Σ → S;
- decoders f_d : S → V_d ∪ {⊥}.

Its **run** is s(ε) = s₀ and s(ha) = u(s(h), a).
- R is **trim** iff s is onto S.
- R is **sufficient** iff d̂ = f_d ∘ s for every d.

**Isomorphism.** An isomorphism of realizations is a bijection β : S → S′ with β(s₀) = s₀′, β(u(x, a)) = u′(β(x), a) and f_d′ ∘ β = f_d.

**DEF 2.8 (right congruence).** π ∈ Eq(Σ\*) is a **right congruence** iff (h, h′) ∈ π implies (ha, h′a) ∈ π for every a ∈ Σ.

**Lemma 2.9. THM.** The kernel of every run is a right congruence.

*Proof.* If s(h) = s(h′), then s(ha) = u(s(h), a) = u(s(h′), a) = s(h′a). ∎

**Lemma 2.10 (the trim part). THM.** For any realization, restricting S to the image of s gives a trim realization with the same run. It is sufficient iff the original is.

*Proof.* The image of s contains s₀ and is closed under u(·, a), because u(s(h), a) = s(ha). Decoders restrict. Sufficiency depends only on values along the run. ∎

## 2.5 Records (what is written down)

Part III studies what a system **writes**, as distinct from what it **holds**.

**DEF 2.11 (online append-only record).**
- An **online record** is a map r on L of the form r(ε) = ⟨e₀⟩ and r(ha) = r(h) · ⟨ρ(h, a)⟩.
- Each step appends exactly one **delimited** entry. An entry may depend on the **whole** past h; it is not required to depend only on r(h).

**DEF 2.12 (PC).** **PC(L)** is the set of π ∈ Eq(L) that are:
- **length-preserving:** (h, h′) ∈ π ⇒ |h| = |h′|; and
- **prefix-closed:** (h, h′) ∈ π ⇒ (h_t, h′_t) ∈ π for every t ≤ |h|.

**Proposition 2.13 (the kernels of online records are exactly PC). THM.**
- (a) For every online record r, ker r ∈ PC(L).
- (b) For every π ∈ PC(L), the **class log** r_π(h) := ⟨[h₀]_π, [h₁]_π, …, [h]_π⟩ is an online record with ker r_π = π.

*Proof.*
- **(a)**
  - *Entry count:* by induction on |h|, r(h) has exactly |h| + 1 entries, and r(h_{t+1}) = r(h_t) · ⟨ρ(h_t, a_{t+1})⟩. Each prefix h_t lies in L, because L is prefix-closed.
  - *Prefixes:* so for t ≤ |h|, r(h_t) is the prefix of r(h) consisting of its first t + 1 entries.
  - *Conclusion:* suppose r(h) = r(h′). Delimited logs that are equal have equal entry counts, so |h| = |h′|, and their (t+1)-entry prefixes agree, so r(h_t) = r(h′_t). Reflexivity, symmetry and transitivity are inherited from equality.
- **(b)**
  - *Online:* the next entry [ha]_π is a function of the whole past ha.
  - *π ⊆ ker r_π:* if (h, h′) ∈ π, then |h| = |h′| by length preservation, and prefix-closure gives (h_t, h′_t) ∈ π for every t ≤ |h|. So the two logs have the same length and equal entries.
  - *ker r_π ⊆ π:* equal logs have equal last entries [h]_π = [h′]_π. ∎

**Remarks (the assumptions matter).**
- **Undelimited entries:** if entries could be concatenated without delimiters ("a" + "b" = "ab"), kernels could relate histories of different lengths and leave PC. Delimiting is therefore part of the model.
- **Dependence on the past:** if ρ could read only (r(h), a), kernels would also have to be right congruences relative to L. That family is contained in PC(L).
  - *Strictness:* the containment is strict for \|Σ\| ≥ 2 and L = Σ\*. For example, the identity together with {(a, b), (b, a)} is in PC but is not a right congruence.
  - *Equality case:* for Σ = {a} the two families coincide (both equal {identity}).

## 2.6 Notation summary

| Symbol | Meaning | Defined in |
|---|---|---|
| Σ, Σ\*, L | inputs, histories, legal histories | 2.1, 2.2 |
| 𝔇, d̂, 𝔇(h) | decisions, their extension with ⊥, the decision vector | 2.3 |
| ker, Dist, ⊑ | kernel, distinguished pairs, information order | 2.4, 2.5 |
| R = (S, s₀, u, f) | realization | 2.7 |
| PC(L) | kernels of online records | 2.12 |
| IC, DET, OD | the standing assumptions | 2.1, 2.2, 2.3 |

## 2.7 Limitations

- DET and IC are strong. Ch. 6 treats DET's failure. IC is an engineering requirement that must be checked against the real system (Ch. 16).
- All maps are deterministic. Randomized records lie outside ⊑ (Ch. 14).

# Claimed theorems for candidate M1 (claims to falsify, with the lead's proofs)

### Proofs (M1)

**M1-T1 (existence and uniqueness, stage 1).** For a finite record and a fixed question, the key set *D* is finite:
- acts;
- (grant, point, view, judge) with points and judges drawn from acts and views from positions ∪ {now};
- claims at the question instant, plus their successors.

(V₃^D, ⊑) is a finite poset with least element ⊥ (all U). Each coordinate of Φ is a term built from constants (record data and comparisons of data) and other coordinates using Kleene ¬, ∧, ∨ over finite index sets. Each connective is ⊑-monotone:
- ¬U = U ⊑ ¬*a*;
- if *a* ⊑ *a′* and *b* ⊑ *b′*, then *a* ∧ *b* ⊑ *a′* ∧ *b′*, by case analysis on F, T and U;
- dually for ∨.

The non-monotone map h(U) = F is **not** used inside Φ. So Φ is monotone. The chain ⊥ ⊑ Φ(⊥) ⊑ Φ²(⊥) ⊑ … is increasing in a finite poset. Each coordinate changes at most once (from U), so the chain is stationary after at most |*D*| strict steps. Its limit is a fixed point below every fixed point: by induction, Φⁿ(⊥) ⊑ *q* for every fixed point *q*. So it is the **unique least** fixed point (Kleene). ∎

**M1-T2 (stage 2, υ = "does not count").** The suppressed set S is fixed by stage 1 and does not vary during stage 2. Φ_S has the same form as Φ, with fewer terms, so M1-T1 applies. ∎ *Limitation:* stage-2 values of members of S are not reported as their authorization (§5.1).

**M1-T3 (no cycles under at-the-time).** Under ρ = at-the-time with own views, A(*x*) reads only:
- W keys of view *s*ₓ;
- through them, A(*y*) for acts with *s_y* < *s*ₓ: issuing acts, endings and annulments visible in the view, and uses before *p*(*x*).

Induction on *s* therefore gives every A(*x*) a value determined by earlier entries. **A-values never depend cyclically on each other**, so U arises only from genuine unknowns (κ, π, λ, missing holders) or from one recorded inconsistency: **cyclic issuance** (*g* issued under *h* and *h* under *g*). Cyclic issuance makes the W keys of one view depend on each other, and they stay U. Mutual annulment, mutual revocation and the F-13 freeze cannot occur under at-the-time. ∎

**M1-T4 (total headline).** If the value is T or U, the headline is fixed. If the value is F:
- either there is no recorded adoption and C1-a holds, giving the reason "never adopted";
- or every disjunct is F, so each has at least one conjunct equal to F. Every conjunct maps to an entry in the causal list (c1: not validly made or annulled; c2: no longer valid or not yet valid; c3: revoked or superseded; c4: authority ended or suspended).

So R ≠ ∅, and the first entry in causal order is unique. ∎

**Property 2′ (CANDIDATE restatement).** Under ρ = at-the-time, adding an adoption act or a basis for *c* never turns T into F, but may turn F or U into T.
- *Proof sketch:* a later-recorded entry is invisible to earlier acts' views, so existing disjuncts keep their values, and ⋁ with one more term cannot decrease from T.
- Under ρ = now this fails (§5.7 witness).

**Property 4 (views agree without late or backdated entries)** carries over: with no entry recorded after an act it affects, each own view already contains every entry the "now" evaluation uses for that act, so the two views coincide (v1's sketch, unchanged). *Not re-proved for quota consumption.*


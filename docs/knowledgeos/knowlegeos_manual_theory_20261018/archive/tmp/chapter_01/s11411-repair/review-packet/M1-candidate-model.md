# Candidate model M1 for §1.14.1 (PROPOSED; not approved; not in the book)

*Status tags:* **[SRC]** stated in the baseline text or an authorized source · **[DER]** derived from stated definitions · **[ASM]** an assumption introduced by the author · **[UNR]** unresolved interpretation · **[PROP]** proposed revision. Every element below is [PROP] unless marked otherwise. Parameters already in v1 keep their names.

## M1.1 Domains and permitted comparisons

| Domain | Elements | Ordered by | Status |
|---|---|---|---|
| 𝕋 world time | act times *t*ₓ, effective times eff_y, period bounds from(g), to(g), validity bounds of claims, the question instant *t* | ≤ on 𝕋 | [SRC] act time and effective time (v1); [PROP] that all of these share one domain |
| 𝕊 record positions | *s*ₓ ∈ ℕ, one global gap-free sequence | ≤ on ℕ | [SRC] v1 |
| recording time | τ(*s*) ∈ 𝕋, non-decreasing in *s* | via τ only | [PROP] (F-10: v1 has positions but no recording time) |
| 𝕎 points | *p*(*x*) = (*t*ₓ, *s*ₓ) for acts; a pure time bound *b* is embedded as (*b*, −∞) | lexicographic | [PROP] (F-8) |
| record states | prefixes K_n = {entries with *s* < *n*}; "now" = the whole record K | ⊆ | [PROP] |

**Permitted comparisons:** within 𝕋, within 𝕊, within 𝕎, and from 𝕊 to 𝕋 only through τ. **Never** an act time against a record position. Act windows, validity intervals and grant periods are **half-open intervals in 𝕎** (the inclusive/exclusive choice at a period end is decision D-4).

**Effective point of an ending *y*:** *e*(*y*) = *p*(*y*) if eff_y = *t*_y, else (eff_y, −∞). An act *x* lies inside an interval [a, b) iff a ≤ *p*(*x*) < b. Two acts at the same act time are ordered by record position, so "first recorded use wins" is decided by the order, not by a cycle.

## M1.2 Entities

- **Act** *x*: actors, activity, object, scope, cited ∈ {∅, ⊥, a non-empty set of bases}, *t*ₓ, *s*ₓ, and eff for endings. [SRC] v1.
- **Grant** *g* is the **product of an issuing act** i(*g*) (activity *issue-grant*). i(*g*) fixes holder H(*g*), permitted activities Act(*g*), scope Sc(*g*), period [from(*g*), to(*g*)) with to possibly missing, and single-use or not. Its **parents** are the bases cited by i(*g*): ∅ (empty) if i(*g*) is a root act. [PROP] (F-3, F-4; the "grants as products of acts" route of the step-1 record, §6.)
- **Root act:** authorized by stipulation. [SRC] v1, which marks it as the author's stipulation.

## M1.3 Valuation and operator

Value set V₃ = {T, F, U} with the **information order** U ⊑ T, U ⊑ F. Strong Kleene ¬, ∧, ∨ are used throughout. [SRC] v1.

Keys (a finite set *D* for a finite record and a fixed question):
- A(*x*): *x* authorized;
- W(*g*, *p*, *v*, *j*): point *p* lies in *g*'s act window under view *v*, when judging act *j*;
- C(*c*, *t*): claim *c* currently authoritative at *t* (needed inside the fixed point only for σ = conditional).

View: *v*(*x*) = *s*ₓ under ρ = at-the-time (**own view**: *x* is judged on the entries recorded before *x*), and "all of K" under ρ = now. [PROP] (F-9; the own-view reading is [UNR] in v1.)

**Effect of an act *y* at point *p* in view *v*:** valid(*y*, *p*, *v*) = A(*y*) ∧ ¬⋁{A(*z*) : *z* annuls the act *y*, *z* visible in *v*, *e*(*z*) ≤ *p*}. [PROP] (F-18: an annulled revocation or annulment loses its effect.)

**A(*x*)** = T if *x* is a root act; F if cited = ∅; κ if cited = ⊥; otherwise
⋁_B [ ⋀_{g∈B} W(*g*, *p*(*x*), *v*(*x*), *x*) ∧ covers(B, *x*) ∧ ι(*x*) ].

**W(*g*, *p*, *v*, *j*)** = W1 ∧ W2 ∧ ¬W3 ∧ W4, where:
- **W1 (issued):** i(*g*) is visible in *v*; valid(i(*g*), *p*, *v*); *p*(i(*g*)) ≤ *p*; and (from(*g*), −∞) ≤ *p*. If i(*g*) exists but is not visible in *v*, the value is the late-ratification parameter λ (C11). A grant with no issuing act on record takes κ_g, tied to C1 (decision D-2);
- **W2 (period):** *p* < (to(*g*), −∞); if to(*g*) is missing: π (U or T);
- **W3 (endings):** ⋁ of valid(*y*, *p*, *v*) over the endings *y* of *g* visible in *v* with *e*(*y*) ≤ *p* (suspensions: inside their interval), **excluding *j* itself and endings caused by *j***. If *g* is single-use, add A(*u*) for every other visible use *u* of *g* with *p*(*u*) < *p*. A use consumes the grant only if the using act is authorized (C13, decision D-5);
- **W4 (delegation cascade):** if δ = yes, ⋁ over the parent bases B of i(*g*) of ⋀_{h∈B} W(*h*, *p*, *v*, *j*); if δ = no, T. δ is decision D-3.

**covers(B, *x*):**
- every *g* ∈ B permits the activity of *x*;
- every holder of a *g* ∈ B is among the actors of *x*, and every actor holds some *g* ∈ B (the actor–holder link, F-12);
- scope: under χ = co-signature, Sc(*x*) ⊆ Sc(*g*) for each *g*; under χ = pooled, Sc(*x*) ⊆ ⋃ Sc(*g*);
- **for an issuing act:** activity *issue-grant* ∈ Act(parent) (a right to delegate), Sc(child) ⊆ Sc(parent), and to(child) ≤ to(parent). These conditions are the book's §1.13.3(a)–(b), which is [DER/ASM], not a source rule; see D-3.

covers is U when a holder is not recorded, which is every live grant record today (F-14).

**C(*c*, *t*)** = ε ∨ ⋁ over adoptions *x* of *c* with *t*ₓ ≤ *t*, and over bases B of *x*, of (c1 ∧ c2 ∧ c3 ∧ c4):
- **ε** = (no adoption on record) ? (C1-a: F; C1-b: U) : F. This puts C1 **inside the formula** (F-5), option H2 of decision D-1;
- **c1** = A through B for *x*, and ¬⋁ of valid(*z*) over act annulments *z* of *x* effective by *t*;
- **c2** = *t* ∈ validity(*c* as set by *x*) [SRC v1];
- **c3** = ¬⋁ of valid(*y*) over revocations or supersessions *y* of *c* with *p*(*y*) > *p*(*x*), effective by *t*. Under σ = conditional, a supersession counts only ∧ C(successor, *t*), **inside** the same fixed point (F-11);
- **c4** = ¬⋁ of valid(*y*) over the endings *y* of grants in B whose kind ∈ P(kind of *c*) and that fall after *p*(*x*) and by *t*, other than endings caused by *x*. Expiry contributes at (to(*g*), −∞) if expiry ∈ P.

**Timing under ρ = at-the-time** for an ending, annulment or revocation *y* recorded **after** *x* (*s_y* > *s*ₓ): it takes effect for *x*'s product from max(*e*(*y*), (τ(*s_y*), −∞)), i.e. **not before it was recorded** (λ_e = record-time). The alternative λ_e = act-time is v1's note "from its own act time". That note is silent when the ending's act time precedes *x*; see D-6.

**υ (two stages).**
1. Stage 1: the least fixed point V* of the operator above, with U propagated.
2. Under υ = "does not count until evidenced", let S = {endings *y* : V*(A(*y*)) = U}. Solve again with the endings in S removed.

The stage-2 authorization values of the members of S are **not reported** as their authorization; they are reported as "undetermined; effect suppressed under υ". [PROP] (F-1)

## M1.4 Headline

H(*c*, *t*) depends only on the value and a reason set R, both computed from the same disjuncts:
- **current** if the value is T;
- **cannot determine** if U (including "no adoption on record" under C1-b);
- **not current** if F, headlined by the first reason in causal order:
  1. never adopted (C1-a);
  2. not validly made;
  3. **annulled (void)** (new, F-6);
  4. revoked;
  5. superseded;
  6. authority ended (kind);
  7. suspended;
  8. no longer valid;
  9. not yet valid.

C8, the alternative order, stays noted.

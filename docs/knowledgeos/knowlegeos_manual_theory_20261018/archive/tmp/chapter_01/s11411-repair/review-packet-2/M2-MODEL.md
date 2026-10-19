# Candidate model M2 for §1.14.1 (PROPOSED; scratchpad only; not approved)

## Provenance tags
- **[BOOK]**: stated in Chapter 1 baseline v1 (sha256 `4d65261d…391367`); a book statement, not a source rule.
- **[SRC]**: stated in an authorized source, with file and lines, verified by the author.
- **[DER]**: derived; premises named.
- **[ASM]**: assumption introduced here.
- **[OPT]**: an explicit option. The value is not chosen here; reserved or alternative IDs follow `M2-DECISION-BRIEF.md`.
- **[UNR]**: unresolved.

M2 supersedes M1, whose disposition is in `M1-DISPOSITION-NOTE.md`. Every M1 element that the review found defective is either re-derived here or turned into an explicit option.

## 1. Domains, axioms and comparisons

**Domains:**

| Domain | Elements | Order | Tag |
|---|---|---|---|
| 𝕋 world time | act time *t*ₓ; effective time eff_y; period bounds; claim validity bounds; question instant *t*; knowledge time *k*; recording time τₓ | total ≤ | [BOOK] act time and effective time; [ASM] one totally ordered domain (never stated in v1, reported by the review) |
| 𝕊 record position | *s*ₓ ∈ ℕ, global, gap-free, unique | ≤ | [BOOK] |
| 𝕎 points | *p*(*x*) = (*t*ₓ, *s*ₓ); a time bound *b* ↦ (*b*, −∞) | lexicographic | [DER] repair of V1-f, conflation 1 |
| question point | q(*t*) = (*t*, +∞) under **inclusive**; (*t*, −∞) under exclusive | — | inclusive is **[DER] from v1 case 12** ("revocation after adoption, same act time → revoked"); exclusive kept as [OPT] |

**Axioms:**
- **[ASM] Ax1 (no record before the act):** τₓ ≥ *t*ₓ. The encoding rejects a violating record.
- **[ASM] Ax2 (recording order):** τ is non-decreasing in *s*.

**Permitted comparisons:** within 𝕋, within 𝕊, within 𝕎, and (τ, *s*) against (*t*, *s*) in the "at-act" view only. That comparison is within 𝕋 × 𝕊, because τ ∈ 𝕋. **Never** an act time against a record position.

**Bitemporal question (repairs F-7, F-15, NEW-2).** "Is *c* current at *t*, on the records as known at *k*?" K_k = {entries with τ ≤ *k*}. "Now" means *k* = the present. [DER, from safeguard 3: "what the records showed at the time" requires knowledge time.]

## 2. Entities

**Act *x*:** actors, activity, object, scope, cited ∈ {∅ explicitly none, ⊥ unknown, a non-empty set of bases (each a set of grants)}, *t*ₓ, *s*ₓ, τₓ; for endings, eff. [BOOK]

**Grant *g*:**
- the product of an **issuing act** i(*g*). It is [SRC] that authority is "not transferable — a new act is required" (AMS L189).
- i(*g*) fixes holder H, activities Act, scope Sc, period [from, to) and quota *n*_g (single-use = 1; none = unlimited);
- a **grant record with no act on record** carries a flag; its issuance validity is κ_g (§4).

**Delegation vs independent authority.** AM-2 [SRC AMS L285] says these must be distinguished.
- **Independent:** i(*g*) is a root act or cites no parent. Its parents are **{∅}**, the empty conjunction, which is T. [DER, repair of F-1]
- **Delegated:** i(*g*) cites parent bases.
- The delegation constraints are in `covers` (§3).

## 3. Operator: keys and equations

**Value set** V₃ = {T, F, U}, information order U ⊑ T, U ⊑ F. Strong Kleene ¬, ∧, ∨ [BOOK], plus the **Kleene threshold** ATLEAST(*n*, ·): T if #T ≥ *n*; F if #T + #U < *n*; else U.

**Keys** (finite for a finite record and one question):
- A(*x*): *x* is authorized;
- V(*y*, *p*, *v*): act *y* has effect at point *p* in view *v*;
- W(*g*, *p*, *v*, *j*): *p* lies in *g*'s act window, in view *v*, when judging act *j*;
- C(*c*, *t*): claim *c* current at *t* (inside the fixed point only for σ = conditional).

**Views** v(*x*), for the record view ρ ([OPT], reserved decision 2):
- *now*: all of K_k;
- *at-recording*: {*y* : *s_y* < *s*ₓ} (v1's formula reading [BOOK L107]);
- *at-act*: {*y* : (τ_y, *s_y*) < (*t*ₓ, *s*ₓ)}.

**Equations:**
- **V(*y*, *p*, *v*)** = A(*y*) ∧ ¬⋁ { V(*z*, *p*, *v*) : *z* annuls act *y*, *z* visible in *v*, *e*(*z*) ≤ *p*, *z* ∉ S }. *(Recursive: an annulment of an annulment restores the effect. Repairs F-2.)*
- **A(*x*):**
  - T if *x* is a root act;
  - F if cited = ∅;
  - κ if cited = ⊥;
  - otherwise ⋁_B [ ⋀_{g∈B} W(*g*, *p*(*x*), v(*x*), *x*) ∧ covers(B, *x*) ].
- **W(*g*, *p*, *v*, *j*)** = w0 ∧ w1 ∧ w2 ∧ ¬(closers) ∧ w4:
  - **w0, issuance validity:** if i(*g*) is not visible in *v*: λ ([OPT] C11, late ratification). If the record has no act: κ_g. Otherwise V(i(*g*), *p*, *v*);
  - **w1:** *p*(i(*g*)) ≤ *p* and (from(*g*), −∞) ≤ *p*;
  - **w2, period:** *p* < (to, −∞) under β = half-open; *p* ≤ (to, +∞) under β = closed ([OPT]); a missing *to* takes π;
  - **closers:**
    - V(*y*, *p*, *v*) for every ending *y* of *g* (end-grant, annul-grant) visible in *v*, *y* ∉ S, *y* ≠ *j*, *y* not caused by *j*, with *e*(*y*) ≤ *p* (a suspension counts inside its interval);
    - **quota:** ATLEAST(*n*_g, {debit(*u*, *g*)}) over uses *u* ≠ *j*, *u* ∉ S_use, with *p*(*u*) < *p*, visible in *v* (consume_view = view) or in K_k (consume_view = now) ([OPT] C13). Here debit(*u*, *g*) = ⋁_{B ∋ *g*, B cited by *u*} [⋀ W(·) ∧ covers(B, *u*)]: a use debits *g* if *u* is authorized through a basis containing *g* (repairs F-9's undefinedness; the choice "any satisfied basis" is [OPT] C13);
  - **w4, delegation cascade:** if δ = yes ([OPT] reserved decision 1): ⋁ over the parent bases of i(*g*) (independent: {∅}) of ⋀ W(*h*, *p*, *v*, *j*). If δ = no: T.
- **covers(B, *x*)**, evaluated in Kleene order (repairs F-10ii): **data checks first**, then F on any definite mismatch, then U if a holder is unrecorded, then the holder link.
  - **Data:** activity ∈ Act(*g*) for every *g*; scope per χ ([OPT] C4: co-signature, each; pooled, union).
  - **For an issuing act:** Sc(child) ⊆ Sc(*g*), **Act(child) ⊆ Act(*g*)**, and to(child) ≤ to(*g*).
    - Scope and activity narrowing are [SRC AMS L285 "within a narrower boundary"; L92–94].
    - Period containment is **[ASM]**: the book's §1.13.3(a), not a source rule. It is an option, contain_period ([OPT] C15).
    - A **right to delegate** (the activity *issue-grant* ∈ Act(parent)) is **[DER]**: issuing is an activity [BOOK L93], and every act needs a grant covering its activity (safeguard 1). So it is not a separate assumption. *(Corrected while drafting: first tagged [ASM].)*
  - **Holder link:** every holder in B is an actor of *x*, and every actor of *x* holds some *g* ∈ B. [DER] The holder is **not recorded** in today's grant records, so covers = U today.
- **C(*c*, *t*) = ε ∨ ⋁ over adoptions *x* of *c* with *p*(*x*) ≤ q(*t*), and over bases B, of [c1 ∧ ¬ann ∧ c2 ∧ c3 ∧ c4]:**
  - **ε**, if **no adoption of *c* with *p*(*x*) ≤ q(*t*)** is in K_k: F under C1-a (reason "not adopted by *t*"); U under C1-b. Otherwise F. (Repairs F-12: "by *t*", not "ever".)
  - **c1:** root ⇒ T. Otherwise the base B, or κ for ⊥, or F for ∅.
  - **ann:** ⋁ V(*z*, q, ·) over annulments *z* of *x* effective by q.
  - **c2:** q(*t*) lies inside the validity of *c* set by *x* (β applies at its end). For no stated period: [*t*ₓ, ∞) [BOOK convention L192].
  - **c3:** ¬⋁ over revocations or supersessions *y* of *c* with *p*(*y*) > *p*(*x*) and *e*(*y*) ≤ q, of V(*y*, q, ·). Under σ = conditional, ∧ C(succ, *t*). Under C6 = later-prevails ([OPT] C6): also ¬V(*z*, q, ·) for adoptions *z* of a claim *d* recorded as **conflicting** with *c*, with *p*(*x*) < *p*(*z*) ≤ q. Conflict is **data**; for decisions it is undefined in Chapter 2 [BOOK] (review F-20).
  - **c4:** ¬⋁ over endings *y* of grants in B (and of their ancestors if δ = yes) whose kind ∈ P(kind of *c*) ([OPT] reserved decision 1), not caused by *x*, *y* ∉ S, with *e*(*y*) ≤ q and **either** *p*(*x*) < *e*(*y*) **or** *y* unseen in v(*x*) with late = reach ([OPT] reserved decision 2, the old D-6). Expiry ∈ P contributes at (to, −∞).

**υ, unverifiable endings ([OPT] reserved decision 6):**
- *undetermined:* the least fixed point, unchanged.
- *does-not-count:* **greedy suppression in record order.**
  1. For each entry *y* in increasing *s* that is an ending or a quota use: if A(*y*) = U in the current least fixed point, suppress its **effect** (*y* → S, or a use → S_use), then re-solve.
  2. The authorization values of suppressed entries are **not reported** as their authorization; they are reported as "undetermined; effect suppressed".

  This repairs F-3 (only entries that are U **when their turn comes** are suppressed) and F-4 (uses included).

## 4. Option table (values not chosen here)

| Symbol | Values | Mapped to |
|---|---|---|
| ρ | now · at-recording · at-act | reserved decision 2 |
| late | reach · never | reserved decision 2 |
| λ | U · T · F | C11 (within reserved decision 2) |
| consume_view | view · now | C13 (consumption); reserved decision 2 interaction |
| υ | undetermined · does-not-count (greedy) | reserved decision 6 |
| P | set of ending kinds, per kind of claim | reserved decision 1 |
| δ | yes · no (cascade) | reserved decision 1 (cascade = carry-over of a parent's ending) |
| κ | constitutive · evidential | C2. **[DER]** under C1-b only "evidential" keeps the book's promise (v1 L69) |
| κ_g | C1-a: T · C1-b: U | C1 |
| C1 | constitutes · represents | C1 |
| χ | co-signature · pooled | C4 |
| ω | whole-or-nothing **(only value modelled)** | C4. Intersection needs per-atom evaluation (§6) |
| σ | standalone · conditional | C5 |
| π | undetermined · open-ended | C7 |
| headline | progress · union | C8 |
| C6 | both-current · later-prevails | C6 |
| β | half-open · closed | **C14 (new; continues the existing C-series)** |
| q | inclusive ([DER] v1 case 12) · exclusive | C16 (technical; confirm only) |
| contain_period | yes · no | **C15 (new)**. Scope and activity narrowing are [SRC]; the right to delegate is [DER] |

## 5. Headline

- **current** (T) · **cannot determine** (U).
- **not current** (F), with the reason of the **furthest-progressed** adoption. Stage 0: never counted (not validly made, annulled (void), not adopted by *t*). Stage 1: not yet valid. Stage 2: counted and then ended (revoked, superseded, overridden, authority ended, suspended, no longer valid). Ties are broken by v1's causal order.
- This follows v1's stated rationale: "a claim that never counted should not be headlined as revoked, as if it once had", and conversely (F-13).
- The **union** aggregation stays an option (C8).

## 6. Explicitly outside M2 (named, not silently dropped)

- **ω = valid on the intersection:** needs C(*c*, *t*, *a*) over scope atoms. Live scopes are prose (AMS L104–112, reported), so there are no atoms today. Review F-19 shows T1 and T2 survive. T4 would become per atom.
- **Claim-level suspension** (V3's Rule "Suspended"), from review 1 F-18: not modelled.
- **Root change over time** (C12): not modelled.
- **A mixture of known and unknown bases in one citation:** not modelled.

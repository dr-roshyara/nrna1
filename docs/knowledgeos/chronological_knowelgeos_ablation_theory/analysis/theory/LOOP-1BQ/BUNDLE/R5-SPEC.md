# R5-SPEC: definitions, statements and the finite-state verification task

(Extracted from a formal-theory draft. Proofs and expected outcomes are removed. Your job is to compute, not to confirm.)

## 1. Primitives and definitions

### 1.1 Parameters
**Def 1.1.** Fix the following:
- a finite time set T = {0, 1, …, N}, totally ordered. All three time concepts take values in T; they are different *functions*, not different clocks;
- a finite non-empty set 𝒯 of **targets** (register targets, e.g. the work packages named in A01, A10);
- a finite set V of **deontic values**, and a non-empty subset P ⊆ V of values that **permit work to begin**;
- a finite set Σ of **texts**.

### 1.2 Actors and capability
**Def 1.2.** A history has a finite set 𝒜 of **actors**, and a **capability relation** Cap ⊆ 𝒜 × {iss, adp}. (a, k) ∈ Cap reads "actor a can perform acts of kind k". Capability is constant within a history.

### 1.3 Norm and norm state
**Def 1.3.** A **norm** is a pair (τ, v) ∈ 𝒯 × V. The **norm state** is a function σ : T → V^𝒯. σ(t)(τ) is the value in force for τ at t. σ is a **primitive** of a history. It is not computed from acts, because H-B allows norm changes that are not made by acts (Def 3.1, B-chg).

### 1.4 Acts
**Def 1.4.** A history has a finite set E of norm-making **acts**, partitioned into issue acts E^iss and adoption acts E^adp. Every act a has:
- an actor α(a) ∈ 𝒜;
- a decision time d(a) ∈ T.

Every issue act a ∈ E^iss also has:
- a norm (τ(a), v(a));
- a flag prep(a) ∈ {0, 1}. prep(a) = 1 means "issued as prepared, not adopted";
- an entry into force f(a) ∈ T ∪ {∞}. f(a) = ∞ means the act never enters into force.

Every adoption act b ∈ E^adp has an object obj(b) ∈ E^iss. (This typing is part of the definition. r0 repeated it as a frame axiom F3, which is withdrawn.)

Motivation for using two kinds: the texts separate issuing from adopting (A05: "remains PREPARED, NOT ADOPTED, until the Decision Authority acts on it"; A06; A08 "HELD … NOT ADOPTED"). HYP's status clause needs this split.

### 1.5 Work events
**Def 1.5.** A history has a finite set W of **work events**. Every work event w has:
- a target τ(w);
- an executor α(w) ∈ 𝒜;
- a start time t(w) ∈ T.

### 1.6 Records
**Def 1.6.** A history has a finite set R of **records** (the corpus entries). Every record ρ has:
- a recording time r(ρ) ∈ T;
- a text c(ρ) ∈ Σ;
- a keeper k(ρ) ∈ 𝒜.

It also has two relations:
- a **reference relation** Ref ⊆ R × (E ∪ W). (ρ, x) ∈ Ref reads "ρ carries or references x";
- a partial **constitution map** con : E ⇀ R. con(a) = ρ reads "**ρ is the act a**": making the record is performing the act.

If (ρ, a) ∈ Ref and con(a) ≠ ρ, then ρ **reports** a, and a is a separate act. That act is constituted by another record, or by no record.

### 1.7 History, coordinates, identity
**Def 1.7 (history; coordinates; ≈_X).** A **history** is
h = (𝒜, Cap, E^iss, E^adp, W, R, α, d, τ, v, prep, f, obj, τ_W, α_W, t, r, c, k, Ref, con, σ),
with the components of Defs 1.2–1.6. (τ_W and α_W are the target and executor of work events. Below they are written τ(w) and α(w) where this is unambiguous.) Each component is a **coordinate** of h, compared as follows:
- an item set (𝒜, E^iss, E^adp, W, R) or a relation (Cap, Ref) is compared as a set;
- an attribute (α, d, τ, v, prep, f, obj, τ_W, α_W, t, r, c, k) is compared as a function, *domain included*;
- con is compared as its graph, the set of pairs (a, con(a));
- σ is compared as a function on T.

For a set X of coordinates, **h ≈_X h′** iff h and h′ have the same value on every coordinate outside X. Since an attribute's domain is part of its value, h ≈_X h′ with no item set in X implies that h and h′ have the same items.

**Def 1.7a (cross-history identity).** Fix countably infinite, pairwise disjoint sets of identifiers: 𝔄 (actors), 𝔈 (acts), 𝔚 (work events) and 𝔑 (records). Every history has 𝒜 ⊆ 𝔄, E ⊆ 𝔈, W ⊆ 𝔚 and R ⊆ 𝔑, all finite. The parameters of Def 1.1 are shared by all histories. An actor, act, work event or record of h is **the same** as one of h′ iff it has the same identifier. This is the identity used by ≈_X, by equality of observations (Def 1.11) and by Def 5.1. An identifier is **fresh** for h if it is not among h's items.

**Def 1.7b (replacements and fresh additions).** For a history h:
- h[σ*, f*] is h with σ replaced by σ* and f replaced by f* (on the same E^iss). h[Cap*] is defined in the same way.
- A **fresh addition** to h is a history h⁺ that has the same value as h on every coordinate, except for exactly one of the following changes:
  - **(+actor)** 𝒜⁺ = 𝒜 ∪ {x}, with x fresh. Cap is unchanged;
  - **(+work)** W⁺ = W ∪ {w}, with w fresh and any τ(w) ∈ 𝒯, α(w) ∈ 𝒜, t(w) ∈ T. Ref is unchanged, so w is unreferenced;
  - **(+act)** E^iss⁺ = E^iss ∪ {a}, with a fresh, prep(a) = 1, any norm, any α(a) ∈ 𝒜, any d(a) ∈ T and any f(a) ∈ T ∪ {∞}. Ref and con are unchanged, so a is unreferenced and unconstituted;
  - **(+ref)** Ref⁺ = Ref ∪ {(ρ, x)} for some ρ ∈ R and x ∈ E ∪ W.

  In each case the attributes are extended to the new item and are otherwise unchanged; σ is unchanged.

### 1.8 Structures and frames
**Def 1.8 (structures, frames, base frame 𝓗).** 𝓢 is the set of all histories (Def 1.7). The **base frame** 𝓗 is the set of histories that satisfy these two axioms:
- **F1 (entry into force, Def).** If a ∈ E^iss and f(a) = t ∈ T, then σ(t)(τ(a)) = v(a). This axiom is what "entry into force" means.
- **F2 (the record is the act, Asm).** If con(a) = ρ, then (ρ, a) ∈ Ref and d(a) = r(ρ). This is a modelling assumption: under constitution, the decision time is the recording time. F2 says nothing about entry into force, and it does not assume that any record constitutes any act (con may be undefined everywhere).
- **F3 (typing). WITHDRAWN.** It repeated the typing obj(b) ∈ E^iss already stated in Def 1.4 (M-2).

A **frame** is any subset 𝓗′ ⊆ 𝓗. (r2, B-1: r1 allowed any subset of 𝓢.) Hence **every member of every frame satisfies F1 and F2.** 𝓢, 𝓗 and the frames are relative to the parameters of Def 1.1.

The base frame has no other axiom. In particular it does not require:
- that every act or work event is referenced by some record (H11: non-occurrence may not be read from absence);
- that a norm value implies or excludes any act or work event (H10);
- any order between r, d and f, apart from F2 (H12, H04).

*Check of the r2 restriction (part of Def 1.8).*
- Every frame used in R4 is a subset of 𝓗: 𝓗 itself (the base pair; Lem 4.2, Cor 4.3, §4.5, Lem 5.2 on 𝓗, and CM-A1, CM-A2, CM-A3a, CM-A3b, CM-A4a, CM-A4b, CM-A8); 𝓗₅, 𝓗₇ and 𝓗₉, each of which is 𝓗 plus one further axiom; and the CM-A6 frame, which is 𝓗 for the parameters V = P = {p}. So every countermodel still qualifies, and no countermodel changes.
- The closure hypotheses A5, A7 and A9 (§4.1) remain consistent with the restriction. Let h ∈ 𝓗. Then h[σ*, f*] satisfies F2, because F2 mentions neither σ nor f; every fresh addition to h satisfies F2 (proof of Lem 4.0a, A7); and every change of only con or only d leaves F1 true, because F1 mentions neither. So every history that A5, A7 or A9 puts into 𝓗′ lies in 𝓗.
- The R5 universe U and its variants keep only assignments that satisfy F1 and F2 (§7.1), so they are frames.

### 1.9 The three times
**Def 1.9.** The three times are:
- the **recording time** r(ρ) of a record;
- the **decision time** d(a) of an act;
- the **entry into force** f(a) of an issue act.

The frame equates d and r **only under constitution** (F2: con(a) = ρ ⇒ d(a) = r(ρ)). It never equates f with r or with d. The work start time t(w) is an attribute of work events. It is not one of the three governance times. (G05, H04, A07.)

**Rem 1.9a.** F2 gives no way to read a decision time off a recording time, because con is not observed (Obs O6). Prop 5.4(b) states what would follow if con were observed.

### 1.10 Derived notions
**Def 1.10.** Four derived notions:
- **Adoption.** For a ∈ E^iss and t ∈ T, Adopted(a, t) holds iff either
  - prep(a) = 0, (α(a), adp) ∈ Cap and d(a) ≤ t; or
  - there is b ∈ E^adp with obj(b) = a, (α(b), adp) ∈ Cap and d(b) ≤ t.
- **Entry-into-force data.** F̂(h) = {(a, τ(a), v(a), f(a)) : a ∈ E^iss, f(a) ∈ T}.
- **Change points.** Chg(h) = {(t, τ) : 1 ≤ t ≤ N, σ(t)(τ) ≠ σ(t−1)(τ)}.
- **Violation.** A work event w **violates** in h iff σ(t(w))(τ(w)) ∉ P. Otherwise it **conforms**.

### 1.11 The observation function
**Def 1.11.** The base observation is
> **O(h) = { (ρ, r(ρ), c(ρ)) : ρ ∈ R }**,

the set of record identities with their recording times and texts. More generally, an **observation on a frame 𝓗′** is any function O′ on 𝓗′. O′ **does not depend on** a set X of coordinates **over 𝓗′** iff for all h, h′ ∈ 𝓗′, h ≈_X h′ implies O′(h) = O′(h′). For a class 𝓒 ⊆ 𝓗′ and a value o, the **fibre** is 𝓒 ∩ O′⁻¹(o).

**Rem 1.11a.** The texts c(ρ) are observed **as texts**. A text that says "effective immediately" or "remains blocked" is a value of c, not a value of f or σ. Statements like these are self-assertions (A02, A05, A06). The base frame does not treat them as observations of σ; that is a modelling choice (A5), not a finding that no corpus text states a norm state.

**What O can and cannot see (Obs O1–O9).**

| | Coordinate | Seen by O? | Base |
|---|---|
| O1 | record identities, recording times r, verbatim texts c | **at most these, and not completely.** Recording times are known as reported, second-hand, and not for every record (A07: "R-80 has no timeline entry"). Verbatim texts exist only where a span exists (F13: R24, R28, R35 have no source span). Capture is local only (G01). The available observation is therefore a function of O that forgets some values. By Lem 1.12 every result below that holds for O also holds for it | A01–A13, A15 (L0, per H01); A07; F13; B06 "RECORDING_ORDER"; G01 |
| O2 | decision time d | **no, not in general** | A07 "Recording times (not decision times)"; F08 "the hour of decision of R-77 and R-79"; G05 |
| O3 | entry into force f (and so F̂) | **no** | G05 "is NOT an upper bound on entry into force"; H12 |
| O4 | norm state σ | **no independent channel.** No locally checkable channel qualifies as an *independent* norm-in-force record (B07, F-B1). Whether one exists anywhere is not determinable (D04). This is not a claim that no corpus text states a norm state; ruling texts do, as self-assertions (Rem 1.11a) | B07; D04; F06; H14 |
| O5 | capability Cap | **no**. Host membership is access, not capability | G07; H19 |
| O6 | constitution con (whether a record *is* the act or *reports* it); reference Ref | **con: not reliably codable on this corpus, within the Claude family, even as a binary distinction** (IF: ACT_SELF_PERFORMED fails for every pair, opus~sonnet 0.598). IF is not an R3 item; IF's pre-registered consequence is "stated, not adopted"; IF shows nothing cross-family. **Ref: no unreliability is shown.** IF reports, exploratorily, that the two capable coders reproduced ACT_REPORTED (1.000) and NORM_STATED (0.941). R4 leaves Ref out of O as a **modelling choice**, and Lem 4.2 shows that 4.1(i) and (ii) do not depend on that choice. B09 (perfect within-family agreement on other fields) supports only the caveat that within-family agreement "is not evidence against correlated error" | IF (not an R3 item); B09 (caveat only); F15 (R2 pending) |
| O7 | keeper k | **no, not in general** | F14; A03 ("no Author line"); F02 |
| O8 | work start time t | **no**. Execution traces give recording order only. File names are not timestamps | B06; G05 (Protocol A L3) |
| O9 | acts and work events that no record references; completeness of the corpus | **no**. Absence is NOT-EVIDENCED-IN-CAPTURE | H11; F08; F11; F13; G01 |

**Lem 1.12.** Over every frame, O does not depend on σ, f, Ref, con, d, Cap, k, or on 𝒜, E, W and their attributes, and O is unchanged by every fresh addition (Def 1.7b). Every function g∘O of O inherits all of these properties.

---

## 3. H-D and H-B as model classes

### 3.1 Clauses (derived only from HYP, except where stated)
**Def 3.1.** These are constraints on h ∈ 𝓢. Each one is followed by the HYP text it formalizes and the reading chosen.

| Clause | Constraint | HYP text; reading |
|---|---|
| **D-chg** | for every (t, τ) ∈ Chg(h) there is a ∈ E^iss with τ(a) = τ, f(a) = t, v(a) = σ(t)(τ) and (α(a), iss) ∈ Cap | "norms change **only** through such acts, by actors holding the capability" |
| **D-st** | for every a ∈ E^iss, f(a) < ∞ implies Adopted(a, f(a)) | "the force of a norm depends on the status of the act that made it (e.g. prepared vs adopted)". Read as: force requires adoption. The converse reading (adopted implies in force) is not adopted; see Lem 3.7 |
| (D-wrk) | none | "the governed work itself is outside what the record needs to contain". This allows; it does not constrain |
| **D-rec** | some (ρ, a) ∈ Ref has a ∈ E | "the record is primarily a record of norm-making". Weakest reading. The comparative "primarily" is not formalized, because it would require counting kinds of reference, which is coding-dependent (IF) |
| **B-chg** | for every (t, τ) ∈ Chg(h), **either** there is a ∈ E^iss with τ(a) = τ, f(a) = t and v(a) = σ(t)(τ) (no capability or status condition), **or** some w ∈ W has t(w) < t | "norms change through norm-making acts **and** in response to work". "In response to" is formalized only as temporal precedence, **on any target** (Rem 3.1b). A response relation would be a new primitive that the base does not need |
| **B-conf** | every w ∈ W conforms | "work is expected to conform … (execution begins only when permitted)". **Universal reading, a modelling choice taken from E01 (L1), not from HYP** (Rem 3.1a) |
| (B-fb) | no constraint beyond B-chg's second disjunct | "work outcomes feed back into norm changes". Allowed, not required. The model has no outcome attribute |
| **B-recW** | some (ρ, w) ∈ Ref has w ∈ W | together with D-rec: "the record is expected to carry, or reference, both" |
| **B-rec** | D-rec ∧ B-recW | |

**Rem 3.1a (B-conf's universal reading is a modelling choice).** HYP says only that work "is expected to conform". The universal reading "every work event conforms" is taken from E01 (L1 data: "refutes only a universal 'all recorded work conforms'"), not from HYP. It makes H-B refutable by a single violating work event, while H-D has no work clause of comparable strength. This asymmetry is a consequence of the chosen reading, not a finding about the hypotheses. A weaker (non-universal) reading is not modelled.

**Rem 3.1b (B-chg's any-target vacuity).** B-chg's work disjunct accepts work on **any** target at **any** earlier time. So once any work event precedes t, B-chg holds at t for every target, and B-chg is nearly vacuous after the first work event. This is a disclosed modelling choice (the weakest reading of "in response to"). A target-tied variant (τ(w) = τ) is not adopted.

**Rem 3.1c (unformalized clauses).** B-fb and the comparative "primarily" are not formalized. The first omission weakens H-B and the second weakens D-rec. Both are roughly symmetric, and neither class is built into the primitives, the frame or O.

**Def 3.2.** The model classes are:
- **H-D** = {h ∈ 𝓗 : D-chg ∧ D-st ∧ D-rec};
- **H-B** = {h ∈ 𝓗 : B-chg ∧ B-conf ∧ B-rec};
- **R_B** = {h ∈ 𝓗 : B-rec}.

For a frame 𝓗′, the primed classes **H-D′**, **H-B′** and **R_B′** are defined by the same clauses with 𝓗′ in place of 𝓗. These are sets of histories. Nothing in R4 asserts that the actual history lies in either set (H07).

### 3.2 The exact relation
**Lem 3.3.** D-chg ⇒ B-chg, and B-rec ⇒ D-rec. Neither converse holds.

**Prop 3.4 (near nesting).**
> H-D ∩ H-B = H-D ∩ B-conf ∩ B-recW = H-B ∩ D-chg ∩ D-st.

Hence:
- H-D ∖ H-B = H-D ∖ (B-conf ∩ B-recW);
- H-B ∖ H-D = H-B ∖ (D-chg ∩ D-st).

Each class is contained in the other once exactly two of the other's clauses are added:
- H-D ∩ B-conf ∩ B-recW ⊆ H-B;
- H-B ∩ D-chg ∩ D-st ⊆ H-D.


**Lem 3.5 (neither class contains the other).** Take T = {0, 1}, 𝒯 = {τ}, V = {p, q}, P = {p} and 𝒜 = {x}. The record ρ has some fixed r(ρ) and c(ρ), the same in every item below.
- **(a) A history in H-D ∖ H-B.** E = {a} with a an issue act: (τ, q), prep 1, f = ∞. W = {w} with t(w) = 0. R = {ρ} with Ref = {(ρ, a), (ρ, w)}. σ ≡ q. Then h ∈ H-D, and w violates.
- **(a′) Another history in H-D ∖ H-B.** The same, but with W = ∅ and Ref = {(ρ, a)}. Then h ∈ H-D, and B-recW fails.
- **(b) A history in H-B ∖ H-D.** As in (a), but σ(0) = p, σ(1) = q and t(w) = 0. The change at 1 is explained by earlier work, so B-chg holds. B-conf and B-rec hold. D-chg fails, because no act enters into force at 1.
- **(b′) WITHDRAWN.** In (b′) B-chg also holds through the work disjunct, so it added nothing beyond (b) (M-6).


**Lem 3.6 (shared acceptance of acts; G06).** H-D ∪ H-B ⊆ {h : E ≠ ∅}.

**Lem 3.7 (the biconditional status reading).** Let D-st⁺ be D-st together with "if Adopted(a, t) holds for some t, then f(a) < ∞". Let H-D⁺′ be H-D′ with D-st⁺ in place of D-st. Let (O′, 𝓗′) satisfy A1, A2 and A5 (§4.1), and the side conditions:
- **(SC1)** 𝓗′ is closed under h ↦ h[Cap := ∅];
- **(SC2)** O′ does not depend on Cap over 𝓗′.

Then Prop 4.1(i) holds with H-D⁺′ in place of H-D′. The base pair (O, 𝓗) satisfies SC1, because no axiom of 𝓗 mentions Cap, and SC2 by Lem 1.12.


---

### 4.1 Hypotheses
A1–A9 are **hypotheses (Hyp) of the generic results** about a pair (O′, 𝓗′), where 𝓗′ is a frame and O′ an observation on 𝓗′. Lem 4.0a shows that the base pair (O, 𝓗) satisfies all of them except A6, which is a parameter condition. They are **pairwise independent**: §4.4 gives, for each one, a countermodel that violates it and satisfies all the others.

| | Hypothesis | Kind | Motivation (base) |
|---|---|
| **A1** | O′ does not depend on σ over 𝓗′ | observation | O4: B07, D04, F06 |
| **A2** | O′ does not depend on f over 𝓗′ | observation | O3: G05, H12 |
| **A3** | O′ does not depend on Ref or con over 𝓗′ | observation | O6 (con: IF, within-family; Ref: modelling choice) |
| **A4** | **Invariance under fresh additions.** O′(h⁺) = O′(h) whenever h, h⁺ ∈ 𝓗′ and h⁺ is a fresh addition to h of kind (+actor), (+work) or (+act) (Def 1.7b). A4 says nothing about f, d, Ref or con of existing items | observation | O7, O8, O9 |
| **A5** | **Neutrality for σ and f.** For every h ∈ 𝓗′ and all σ*, f*: if h[σ*, f*] satisfies F1, then h[σ*, f*] ∈ 𝓗′. In other words, 𝓗′ constrains σ and f only through F1, and no frame axiom ties any other coordinate to σ or f | frame | O4: B07 (no local channel is an *independent* norm-in-force record); D04. This is a modelling choice. It is not a claim that no attestation exists anywhere (H14), or that no corpus text states a norm state (Rem 1.11a) |
| **A6** | P ≠ V | parameter | otherwise no work can violate |
| **A7** | **Openness.** For every h ∈ 𝓗′, every fresh addition h⁺ to h of any of the four kinds that satisfies F1 is in 𝓗′. The frame thus admits actors, work events and prepared issue acts that no record references, and it does not constrain which items records reference | frame | H11; F08; F11; O9 |
| **A8** | O′ does not depend on d over 𝓗′ | observation | O2: A07, F08 |
| **A9** | **No c–con tie (neutrality for con and d).** For every h ∈ 𝓗′, every history obtained from h by changing only con, or only d, that satisfies F2 is in 𝓗′. In particular no frame axiom ties the text c (or any other coordinate) to con | frame | O6; IF's candidate concept (Conj 5.5) |

Where the hypotheses are used:
- Prop 4.1(i): A1, A2, A5;
- 4.1(i′): A1, A2, A3, A4, A5, A7;
- 4.1(ii): A1, A2, A5, A6;
- 4.1(iii): A1, A2, A4, A5, A7;
- Lem 5.2: A3, A8, A9, and also A1, A2, A5 for its H-D cases.

**Lem 4.0a (the base pair satisfies the hypotheses).** (O, 𝓗) satisfies A1, A2, A3, A4, A5, A7, A8 and A9. A6 is a condition on the parameters only.


**Lem 4.0b (joint non-dependence on σ and f).** Assume A1, A2 and A5. Let h ∈ 𝓗′, and let σ*, f* be such that h[σ*, f*] satisfies F1. Then h[σ*, f*] ∈ 𝓗′ and O′(h[σ*, f*]) = O′(h).


Separate non-dependence on σ and on f thus gives joint non-dependence, through histories that stay in the frame.

### 4.2 Statement
**Prop 4.1 (D1; generic).** Let 𝓗′ be a frame and O′ an observation on 𝓗′.
- **(i) Core.** Assume A1, A2 and A5. For every h ∈ R_B′ there is h* ∈ H-D′ ∩ H-B′ with h* ≈_{σ, f} h and O′(h*) = O′(h). Hence
  > O′(H-D′ ∩ R_B′) = O′(H-B′) = O′(H-D′ ∩ H-B′) = O′(R_B′).
- **(i′) Record clauses.** Assume A1, A2, A3, A4, A5 and A7. Then
  > O′(H-D′) = O′(H-B′) = O′(H-D′ ∩ H-B′) = {O′(h) : h ∈ 𝓗′, R_h ≠ ∅}.
- **(ii) The contrast.** Assume A1, A2, A5 and A6. Let h ∈ R_B′, and let w be a work event referenced in h. There are h_v and h_c, both ≈_{σ, f} h, with **O′(h_v) = O′(h_c) = O′(h)**, such that:
  - h_v ∈ H-D′ ∖ H-B′, σ_v is constant on T, and w **violates a norm that never changes**;
  - h_c ∈ H-D′ ∩ H-B′, and every work event conforms.
- **(iii) H5's wording, local form.** Assume A1, A2, A4, A5 and A7. Let h ∈ H-D′ ∩ R_B′, and let w violate in h. Then there is h′ ∈ H-B′ with O′(h′) = O′(h) such that:
  - h′ differs from h only in σ (and only at violation points of h), in f, and by additional prepared issue acts that no record references and no record constitutes;
  - w conforms in h′;
  - every change point of σ′ at which no act of h enters into force (in h′) with the new value is the entry into force of an added act.

**Cor 4.1b (the base case).** For (O, 𝓗):
- (i), (i′) and (iii) hold;
- (ii) holds whenever P ≠ V;
- in (i′), {O(h) : h ∈ 𝓗, R_h ≠ ∅} = {o ∈ O(𝓗) : o ≠ ∅}.


**Rem 4.1a (in words).** On any observation satisfying the hypotheses, "the work violated an unchanged norm" and "the norm state at t(w) was different" are not separated ((ii), (iii)). (iii) adds that the difference can be carried by acts that no record references, which is H5's "without a recorded act". **Only this last clause needs A7.** In a frame where every item must be referenced, the non-separation itself survives, through added acts that are referenced (CM-A7(b)).

**Lem 4.2 (robustness).** Prop 4.1(i) holds for every (O′, 𝓗′) satisfying A1, A2 and A5, and (ii) holds if A6 holds as well; neither needs A3, A4, A7 or A8. In particular, on 𝓗 they hold for O_¬σf, the observation of **every coordinate except σ and f**. O_¬σf sees Ref, con, d, Cap, k, 𝒜, E, W and all their attributes **other than f**. So (i) and (ii) hold even if the reference and mode coding were perfectly reliable.

**Cor 4.3 (separation requires dependence on σ or f).** Assume A5 for 𝓗′. If O′(H-D′ ∩ R_B′) ≠ O′(H-B′), then O′ depends, over 𝓗′, on σ or on f. In particular, on 𝓗, no observation that is a function of coordinates other than σ and f separates the classes on R_B. This includes any function of the records, their keepers, Ref, con, Cap, and the acts and work events with their attributes **other than f**.

Cor 4.3 derives **only** that a separating observation must depend on σ or f. It does **not** derive the further elements of H5 §5.2's necessary condition: independence from the norm-maker, the executor and the self-asserting ruling, and the mapping to a target at t. Those appear only in the sufficient conditions (§4.5) and in Def 4.9. O₂ in CM-A2 depends on f and does separate, as Cor 4.3 allows.

## 5. Performative ambiguity

**Def 5.1.** Let O′ be an observation on a frame 𝓗′, and 𝓒 ⊆ 𝓗′ a class. Identity of records and acts across histories is as in Def 1.7a. A pair (ρ, a) with a an act is **performatively ambiguous in 𝓒 at o** iff there are h₁, h₂ ∈ 𝓒 ∩ O′⁻¹(o) such that:
- (ρ, a) ∈ Ref in both h₁ and h₂;
- con_{h₁}(a) = ρ, so in h₁ the record *is* the act;
- con_{h₂}(a) ≠ ρ, so in h₂ the record *reports* a separate act.

A record is performatively ambiguous if some pair containing it is. This is a property of the fibre O′⁻¹(o), not of any one history.

**Lem 5.2.** Let (O′, 𝓗′) satisfy A3, A8 and **A9 (no c–con tie)**.
- For 𝓒 = 𝓗′ and 𝓒 = H-B′: every pair (ρ, a) ∈ Ref, with a an act, of every h ∈ 𝓒 is performatively ambiguous in 𝓒 at O′(h).
- If A1, A2 and A5 also hold, the same is true for 𝓒 = H-D′ and every h ∈ H-D′ ∩ R_B′, and for 𝓒 = H-D′ ∩ H-B′ and every h ∈ H-D′ ∩ H-B′.


**Rem 5.2a (what the model does and does not say).** In the model every referenced pair is ambiguous, including a record such as R-56 whose text states its own mode (A10: "a recording note marked not part of the ruling"). That is because the frame has no axiom tying c to con (A9). The relevant condition is this absence of a c–con tie, not A3. Whether real texts do fix the mode is the empirical question of Conj 5.5, which the model does not settle.

**Cor 5.3 (the R-A3 convention).** Assume the hypotheses of Lem 5.2. A coding rule that fixes the mode for a class of records, such as R-A3 "act and after-state are the same record" (G04), selects a proper subset of each fibre in which both modes occur. Within the model, that choice is therefore a **convention, not an observation**.

**Prop 5.4 (relation to D1).**
- **(a) Removing the ambiguity does not remove D1.** Let O⁺ = (O, Ref, con, d). No pair is performatively ambiguous under O⁺, because con is seen. Yet O⁺ satisfies A1 and A2, so 4.1(i) and (ii) hold for O⁺ by Lem 4.2. So, in this model, performative ambiguity is **not necessary** for non-identifiability.
- **(b) What removing it gives.** Under O⁺, d(a) = r(con(a)) is determined for every constituted act (F2). In H-D, every issue act a in force satisfies f(a) ≥ min{d(b)}, where b ranges over its effective adopters (including a itself when prep(a) = 0); this follows from D-st. If all of these adopters are constituted, recording time becomes a **lower** bound on entry into force. This is consistent with G05, which says recording time is not an *upper* bound. H-B has no such bound: take an act constituted at r = 1, with prep 0 and f = 0. The bound concerns f, which is unobserved (A2), so no separation follows.
- **(c) What the ambiguity blocks.** If (ρ, a) is performatively ambiguous in 𝓒 at o and a is an act on τ, then "ρ is independent for τ" (Def 4.9) is **false in h₁**, by its first clause. So it does not hold throughout the fibre 𝓒 ∩ O′⁻¹(o), and O′ cannot certify any such record as independent. (Independence may also fail in h₂, for example when k(ρ) = α(a). Nothing here depends on h₂.) Keeper unobservability (O7) is a **separate**, second obstacle to the same certification.


**Rem 5.4a (application to the corpus).** Within R4's model, performative ambiguity does not *explain* the non-identifiability of H-D and H-B (IF's point (ii)). It accounts for a weaker fact: **why the corpus cannot certify one of its own records as an independent observable of the kind Rem 4.9a describes.** Both performative ambiguity and D1 stem from what the observation does not see, but they rest on different unseen coordinates: con and d (with the absence of a c–con tie) for the ambiguity, and σ and f for D1. Applying this to the actual corpus presupposes that the corpus is adequately modelled by (O, 𝓗), which is a modelling choice.

**Obs IF-obs (instrument finding; exploratory parts so marked).** IF reports:
- the binary core instrument FAILS its pre-registered rule: 2 of 9 pair × field cells reach κ ≥ 0.67;
- ACT_SELF_PERFORMED fails for every pair (0.598, 0.583, 0.637);
- *exploratory, one reviewer's judgement:* the 8 opus~sonnet disagreements on ACT_SELF_PERFORMED are all class (c), "schema admits both", and each "turns on whether a register/ruling row's words *perform* the ruling or *describe* it";
- *exploratory:* the failure lies in the register and ruling rows, not in the R15–R21 evidence-matrix rows, where all coders give SP = NO.

All of this is within the Claude family, on 36 records, with no ground truth. IF's consequence is "stated, not adopted". IF is not an R3 item.

**Conj 5.5 (performative ambiguity and the coding failure). Stated as a conjecture, not established.**
> On the register and ruling records of this corpus **outside S_mode** (the sealed set of records whose text states its own mode; see the pre-registration requirement below), the text of a record does not determine whether the record constitutes the act it refers to. This is the empirical counterpart of the **absence of a c–con tie (A9)** and of Def 5.1. It is not the counterpart of A3. The failure of ACT_SELF_PERFORMED reported in IF is a consequence of this ambiguity, and not only of coder competence or of the wording of the coding scheme.

*Scope (r2, NB-5).* The conjecture does not claim that the text fails to determine the mode on S_mode. On S_mode it predicts *less* disagreement (the second expected consequence; F-c). r1 stated the conjecture for all register and ruling records, which conflicted with F-c's use of S_mode; that wording is replaced.

Obs IF-obs is consistent with the conjecture.

**Expected consequences, if true:**
- disagreement concentrates on records whose text could be either the act or its report;
- records whose text cannot itself be a norm-making act show agreement, and records whose text states its own mode (S_mode) show less disagreement than the other register and ruling records (F-c);
- any rule that raises agreement does so by stipulating the mode (Cor 5.3). Two different admissible conventions would each be reproducible, yet disagree with each other.

**Pre-registration requirement for the falsifiers.** Before any coding for F-a to F-c, the following are fixed and sealed:
- the list of record ids forming "the register and ruling records";
- the coder pairs;
- the set S_mode used by F-c, as a subset of that list;
- F-b's review protocol: the reviewers (or the rule selecting them), the reviewer instructions, and F-b's decision rule as stated below (r2, NB-4).

**It would be falsified by any of the following.**
- **(F-a)** Coders who receive only the texts, with no added mode convention, reach **IF's pre-registered reliability rule, κ ≥ 0.67 for every coder pair** on the field ACT_SELF_PERFORMED, over the sealed list of register and ruling records **outside S_mode**. Cross-family or human coders are especially relevant here; the R2 decision is still pending (F15). This would show that the text does determine the mode.
- **(F-b)** A pre-registered independent review classifies the disagreeing cells as coder deviations ("schema admits one answer") rather than "schema admits both". This would point to competence, not ambiguity. **Pre-fixed decision rule (r2, NB-4):**
  - *Cells.* Every ACT_SELF_PERFORMED cell, on a record of the sealed list outside S_mode, on which a sealed coder pair disagrees.
  - *Who reviews.* Two reviewers. Neither is a coder in any sealed pair, the author of R4, or the reviewer who made IF's exploratory disagreement anatomy. At least one is outside the Claude family or human if the R2 decision (F15) permits; if it does not, this is recorded and F-b is read as within-family. Each reviewer sees the record text, the coding schema and the two codes, but not the coder identities, and classifies without seeing the other reviewer's classification.
  - *Classes.* Each reviewer assigns every cell to (a) "schema admits one answer" (a coder deviation) or (c) "schema admits both", the two classes of IF's anatomy.
  - *Validity condition.* The two reviewers' agreement on (a)/(c) over all cells reaches κ ≥ 0.67 (IF's threshold). If κ is undefined because both reviewers assign the same single class to every cell, the condition counts as met. If the condition fails, F-b is **undecided** and counts neither for nor against Conj 5.5.
  - *Threshold.* A cell is a deviation iff both reviewers assign (a). F-b **falsifies** Conj 5.5 iff, for every sealed coder pair with at least one disagreeing cell, strictly more than half of that pair's disagreeing cells are deviations. Otherwise F-b does not falsify. If no pair has a disagreeing cell, F-b is void, and F-a bears instead.
- **(F-c)** For every coder pair, the proportion of disagreements on ACT_SELF_PERFORMED is no lower on S_mode than on the other sealed register and ruling records. S_mode is the set of records whose text states its own mode. Selecting S_mode is itself a mode judgement, so it is made once, before any coding, and not revised afterwards. R-56 (A10, a recording note marked not part of the ruling) is the only such record named in the inputs. A result of F-c is read conditional on the sealed S_mode; a later dispute about S_mode is not evidence for or against Conj 5.5. This would show that the ambiguity explanation does no work. **Power (r2, NB-5): F-c may have no power.** If the sealed S_mode is empty, F-c is void. If S_mode = {R-56}, F-c rests on a single record, and each pair's proportion on S_mode can only be 0 or 1. The inputs also do not establish that R-56 is among the 36 records IF coded. R4 therefore does not claim that F-c can falsify Conj 5.5 with any power; the conjecture's falsifiability rests on F-a and F-b.

Scope: IF is within-family, on these records, with no ground truth. Conj 5.5 says nothing about other corpora. R5 cannot test it.

---

## 7. R5 specification (finite-state model; exhaustive enumeration; no code here)

### 7.1 Universe U
**Parameters.**
- T = {0, 1};
- 𝒯 = {τ};
- V = {p, q} with P = {p};
- Σ = {c1, c2};
- 𝒜 = {x};
- act slots {a1, a2}, a work slot w1, and one record ρ1, which is always present. The slot names are the identifiers of Def 1.7a;
- k(ρ1) = x, fixed and not enumerated.

**Coordinates to enumerate.**

| Coordinate | Range |
|---|---|
| σ(0), σ(1) | V |
| each act slot | absent, or present with: kind ∈ {iss, adp}; d ∈ T; actor x. For iss: v ∈ V, prep ∈ {0, 1}, f ∈ {0, 1, ∞} (target τ). For adp: obj = the other slot, which must be a present iss act |
| Cap | subsets of {(x, iss), (x, adp)} |
| w1 | absent, or present with t(w1) ∈ T (target τ, executor x) |
| Ref | subsets of {(ρ1, a1), (ρ1, a2), (ρ1, w1)}, restricted to present items |
| con(s) for each present act s | {none, ρ1} |
| r(ρ1) | T |
| c(ρ1) | Σ |

**Enumeration order (R-1).** Histories are enumerated in lexicographic order of the tuple (σ(0), σ(1), slot a1, slot a2, Cap, w1, Ref, con(a1), con(a2), r(ρ1), c(ρ1)). Each component is ordered as follows:
- values: p < q;
- an act slot: absent < present. Present slots are ordered by kind (iss < adp), then d (0 < 1), then, for iss, v (p < q), prep (0 < 1) and f (0 < 1 < ∞);
- Cap: ∅ < {(x, iss)} < {(x, adp)} < {(x, iss), (x, adp)};
- w1: absent < present with t = 0 < present with t = 1;
- Ref: by the number 4·[(ρ1, a1) ∈ Ref] + 2·[(ρ1, a2) ∈ Ref] + [(ρ1, w1) ∈ Ref];
- con: none < ρ1 (only none for absent slots);
- r: 0 < 1;
- c: c1 < c2.

The **first counterexample** to a universal check is the least h in this order. For a check of the form "for all h there is h′", it is the least h for which no h′ exists. The reported witness of an existential check is the least witness.

**Frame.** Keep exactly the assignments that satisfy F1 and F2 (Def 1.8). The typing of obj is built into the ranges. This gives a few million assignments at most.

**Classes.** Implement the clauses of Def 3.1 literally, and H-D, H-B and R_B as in Def 3.2. "Differs at most in X" (≈_X) means equal on every enumerated coordinate outside X.

**Fresh additions in U.** (+act) makes an absent slot present as an iss act with prep 1, Ref unchanged and con none. (+work) makes w1 present, unreferenced. (+ref) adds a pair to Ref. (+actor) is not representable, because 𝒜 is fixed.

**Observations.**

| Name | Definition |
|---|---|
| O | (r(ρ1), c(ρ1)) |
| O₁ | (O, σ) |
| O₂ | (O, F̂⁰), with F̂⁰ = {(s, v(s), f(s)) : s a present iss slot, prep(s) = 0, f(s) ≠ ∞} |
| O₃ | (O, Ref) |
| O₃′ | (O, con) |
| O₄ | (O, presence of w1) |
| O₄′ | (O, presence and kind of a1 and a2). No attributes; in particular not f or d |
| O₈ | (O, {(s, d(s)) : s a present iss slot with prep(s) = 0}) |
| O⁺ | (O, Ref, con, d) |
| O_¬σf | every coordinate except σ and f |

Below, F̂ = {(s, v(s), f(s)) : s a present iss slot, f(s) ≠ ∞}.

**Additional observables.** "If w1 is present" is abbreviated "if w1".

| Name | Definition |
|---|---|
| Q1 | σ(t(w1)) if w1, else ⊥ |
| Q0 | σ(0) |
| Qe | p if w1, else ⊥ |
| Q2 | (σ, F̂, Cap, and the kind, prep, obj and d of each present act) |
| Q1ʙ | if w1: σ(t(w1)) when h ∈ H-B, and q when h ∉ H-B; else ⊥. It is veridical (at least) on H-B |
| Qm | if w1: σ(t(w1)) when h ∈ H-D, and q when h ∉ H-D; else ⊥. It is veridical (at least) on H-D (Lem 4.8(a)) |
| Q* | if w1: {(λ1, σ(t(w1)))} ∪ ({(λ2, σ(t(w1)))} if h ∉ H-D); else ∅ (Ex 4.5a) |
| Q′ | (σ(0), σ(1), X), where X = 1 iff some present iss slot s has f(s) = 1, (x, iss) ∈ Cap and Adopted(s, 1) (Prop 4.6 with t₁ = 0, t₂ = 1) |

(r0's Qc is dropped. It was a function of O, so its check followed trivially from K4; M-7.)

**Frame variants.**

| Name | Change |
|---|---|
| U₅ | add the axiom "c(ρ1) = c2 ⇒ σ(0) = σ(1) = q" |
| U₆ | V = P = {p} |
| U₇ | add the axiom "every present act and w1 is in Ref" |
| U₉ | add the axiom "c(ρ1) = c2 ⇒ no slot has con = ρ1" |

**Instance I7** (the CM-A7 instance in U):
- σ = (q, q);
- a1 is iss with v = q, prep 1, f = ∞, d = 0, referenced, con none;
- a2 is absent;
- w1 is present with t = 1 and is referenced;
- Cap = ∅;
- r(ρ1) and c(ρ1) are arbitrary.

### 7.2 Checks
For existential checks, report the least witness. For universal checks, report the first counterexample if there is one (order as in §7.1). Model-size counts may be reported, but no count is predicted. "For every o" means for every value (r, c) ∈ T × Σ of O.

| # | Check |
|---|---|
| K1 | The following are nonempty: H-D, H-B, H-D ∩ H-B, H-D ∖ H-B, H-B ∖ H-D. Also, every history in H-D ∪ H-B has a present act (Lem 3.6) |
| K2 | For all h: D-chg ⇒ B-chg and B-rec ⇒ D-rec. Witnesses against both converses exist |
| K3 | H-D ∩ H-B = H-D ∩ B-conf ∩ B-recW = H-B ∩ D-chg ∩ D-st (Prop 3.4) |
| K4 | (i) For every h ∈ R_B there is h* ∈ H-D ∩ H-B with h* ≈_{σ, f} h, i.e. equal on every enumerated coordinate except σ and the values of f. This implies O(h*) = O(h) and O_¬σf(h*) = O_¬σf(h) (Lem 4.2), so no separate run is needed |
| K4b | Lem 3.7: K4 with D-st replaced by D-st⁺, with h* ≈_{σ, f, Cap} h |
| K5 | (i′) O(H-D) = O(H-B) = O(H-D ∩ H-B) = all four values of (r, c) |
| K6 | (ii) For every h ∈ R_B with w1 referenced there are h_v ∈ H-D ∖ H-B, with σ_v constant and w1 violating, and h_c ∈ H-D ∩ H-B. Both are ≈_{σ, f} h, hence have the same O |
| K7 | (iii) Take every h ∈ H-D ∩ R_B with a2 absent and w1 violating. There is h′ ∈ H-B with the same O such that: h′ differs at most in σ (only at t(w1)), in f, and by a2 present as an unreferenced iss act with prep 1 and con none; w1 conforms; and every change point of σ′ at which no act of h enters into force (under f′) with the new value is f(a2) |
| K8.1 | ¬A1: O₁(H-B) ⊊ O₁(H-D ∩ R_B). Determine exactly which values form the difference, for every o. |
| K8.2 | ¬A2: for every o, the value (o, F̂⁰ = {(a1, q, 0), (a2, q, 1)}), with both slots iss and prep 0, lies in O₂(H-D ∩ R_B) ∖ O₂(H-B). (One forced prep-0 act does not suffice in U: with T = {0, 1}, a prep-1 act at the other time, or work at 0, escapes F̂⁰. The two-act value forces σ ≡ q) |
| K8.3 | ¬A3: a value of O₃ with Ref containing no (ρ1, w1) lies in O₃(H-D) ∖ O₃(H-B). (i) still holds for O₃ on R_B |
| K8.4 | ¬A4: the value "w1 absent" lies in O₄(H-D) ∖ O₄(H-B). Under O₄′, (iii) fails at I7, and the failure comes only from (iii)'s literal requirement of an added act |
| K8.5 | ¬A5 in U₅: (r, c2) ∈ O(H-D ∩ R_B) ∖ O(H-B) for both values of r |
| K8.6 | ¬A6 in U₆: no h_v exists, so (ii) fails |
| K8.7 | ¬A7 in U₇: (iii) fails at I7, because an added a2 would have to be referenced. In U, (iii) holds at I7. In U₇, there is nevertheless h′ ∈ U₇ ∩ H-B with O(h′) = O(I7) in which w1 conforms, namely a2 iss with v = p, prep 1, d = f = 1 and referenced, and σ′ = (q, p) (CM-A7(b)) |
| K9.1 | Q1: (o, q) ∈ O_Q1(H-D) ∖ O_Q1(H-B) for every o, and O_Q1(H-B) ⊆ O_Q1(H-D) |
| K9.2 | Q0 (unmapped): O_Q0(H-D ∩ R_B) = O_Q0(H-B) = every (o, v) |
| K9.3 | Qe: O_Qe(H-D ∩ R_B) = O_Qe(H-B), and Qe never takes a value outside P (S2 fails; Lem 4.8(b)) |
| K9.4 | Q2: both O_Q2(H-B) ∖ O_Q2(H-D) and O_Q2(H-D ∩ R_B) ∖ O_Q2(H-B) are nonempty. Witnesses: σ = (p, q) with F̂ = ∅, and σ = (q, q) |
| K9.5 | Q1ʙ (S1 only on H-B suffices; Prop 4.5): every value of O_Q1ʙ on H-B has Q1ʙ = p, and (o, q) ∈ O_Q1ʙ(H-D) ∖ O_Q1ʙ(H-B) for every o |
| K9.6 | Qm (S1 only on H-D does not suffice; Lem 4.8(a)): Qm = σ(t(w1)) on every h ∈ H-D with w1 present; some h ∈ H-D has Qm = q; and (o, q) ∈ O_Qm(H-D) ∩ O_Qm(H-B) for every o |
| K9.7 | Q* (Ex 4.5a): for every o, (o, {(λ1, q)}) ∈ O_Q*(H-D) ∖ O_Q*(H-B) and (o, {(λ1, p), (λ2, p)}) ∈ O_Q*(H-B) ∖ O_Q*(H-D) |
| K9.8 | Q′ (Prop 4.6 directly): for every o, (o, (p, q, 0)) ∈ O_Q′(H-B) ∖ O_Q′(H-D); and no value with σ(0) ≠ σ(1) and X = 0 lies in O_Q′(H-D) |
| K10.1 | Lem 5.2: for every h ∈ 𝓒 and every present act s with (ρ1, s) ∈ Ref there are h₁ and h₂ in 𝓒 ∩ O⁻¹(O(h)) with (ρ1, s) ∈ Ref in both, con(s) = ρ1 in h₁ and con(s) = none in h₂. Run for 𝓒 = U and 𝓒 = H-B with every h ∈ 𝓒; for 𝓒 = H-D with every h ∈ H-D ∩ R_B; and for 𝓒 = H-D ∩ H-B with every h ∈ 𝓒 (R-4: the lemma's scope) |
| K10.2 | Under O⁺ no pair is ambiguous. K4 holds for O⁺ |
| K10.3 | (a) For every h ∈ U and every present slot s with con(s) = ρ1: d(s) = r(ρ1). Hence in every O⁺-fibre every constituted act has d = r(ρ1). (b) For every o there are h, h′ ∈ U ∩ O⁻¹(o) and a slot s, present and referenced in both, with d_h(s) ≠ d_{h′}(s) |
| K10.4 | In H-D, every present iss s with f(s) ≠ ∞, prep 0, con(s) = ρ1 and no adp act on s satisfies f(s) ≥ r(ρ1). Some h ∈ H-B violates this |
| K10.5 | ¬A8: under O₈ there is a referenced pair that is not ambiguous in U (e.g. a1 iss, prep 0, d = 0, r(ρ1) = 1) |
| K10.6 | ¬A3 for Lem 5.2: under O₃′ no pair is ambiguous in U |
| K10.7 | ¬A9 in U₉: for every h with c(ρ1) = c2 and a present referenced slot, the pair is not ambiguous in U₉. For c(ρ1) = c1, K10.1 holds in U₉ |
| K11.1 | U is closed under removing w1 (with its Ref entry) and under adding w1 at any t without a reference. That is, there is no norm→work and no absence→non-occurrence constraint (H10, H11) |
| K11.2 | In U, call a present iss slot s **forced** iff it enters into force, i.e. f(s) ∈ T (f(s) ≠ ∞) (r2, NB-6). There are referenced, forced slots with f(s) > r(ρ1), and others with f(s) < r(ρ1). That is, there is no bound from r in the frame (H12) |
| K12.1 | Independence of the countermodels (observations). For each of O, O₁, O₂, O₃, O₃′, O₄, O₄′, O₈, report the change groups over U on which it depends: σ-only, f-only, Ref-only, con-only, d-only, and (+act)/(+work) additions. Ref-only and con-only are separate groups (r2, NB-6). |
| K12.2 | Independence of the countermodels (frames). For each of U, U₅, U₇, U₉, report which of the closure properties fail: A5 (σ, f replacements satisfying F1), A7 restricted to (+act), (+work) and (+ref), and A9 (con-only or d-only changes satisfying F2). |

Conj 5.5 is empirical and is **not** a check for R5.

### 7.3 What cannot be tested in U (R-6)
- **Def 4.9 and Prop 5.4(c).** With 𝒜 = {x} and k(ρ1) = x, ρ1 is never independent for τ whenever some act or work event on τ is present. So Prop 5.4(c), and any use of Def 4.9 with a present act, cannot be exhibited in U.
- **(+actor) additions**, since 𝒜 is fixed. A4 and A7 are checked in U only for (+act), (+work) and (+ref).
- **The generic statements** (Prop 4.1 for arbitrary (O′, 𝓗′), Lem 4.0b, Lem 3.7's side conditions). U tests the base pair and the listed variants only.
- **Phenomena that need |P| ≥ 2, more than one target, more than one record or N > 1.** Examples are the |P| ≥ 2 failure of the withdrawn Rem 4.5's justification, and B-chg's any-target vacuity (Rem 3.1b), which needs two targets to show.
- **Every external realization claim** (Rem 4.5b, Rem 4.6a, Table 4.6) and **Conj 5.5**.

---

# FORMAL-THEORY-R4: the minimal formal theory (draft r3)

| | |
|---|---|
| Inputs (only) | `R3-CODING-INDEPENDENT.json` (base items cited by id, e.g. A07); `HYPOTHESES.md` (HYP); `H5.md` (H5); `INSTRUMENT-FINDING.md` (IF). IF is an r0 input but **not an R3 item**; where it is cited this is said |
| Revision | **[r3]** r3 adds §9 and scope remarks (CHANGELOG-r3.md; human act F-LOG-0232). Earlier: **r2**, a minimal proof repair of r1 after `REVIEW-r1` (verdict FAIL, one blocking issue B-1 / RG-1; human act F-LOG-0221). Every r2 change is mapped to its review item in `CHANGELOG-r2.md`; the r1 changes are mapped in `CHANGELOG-r1.md`. Statement ids are stable; withdrawn statements stay in place, marked **WITHDRAWN** with a reason |
| Status | **[r3]** draft r3, reviewed PASS_WITH_LIMITATIONS (LOOP-1BO/B) with the review's wording items applied as disclosed analyst edits; same-family (Claude) analyst and reviewers throughout; R5 finite-verified (r2 content); robustness per §9 (finite results in U only) |
| Standing rule | H-D and H-B are **sets of histories** (model classes). Neither is assumed anywhere. No statement uses an act count, a norm count or any other coding-dependent value |

**Labels.** Every statement carries exactly one of these labels:
- **Def**: definition;
- **Asm**: modelling assumption of the base frame;
- **Hyp**: hypothesis of a generic theorem (A1–A9). For the base pair (O, 𝓗) each Hyp other than A6 is a *theorem* (Lem 4.0a);
- **Lem**, **Prop**, **Cor**: proved claims (a countermodel CM-… is a Lem: an existence claim with its verification);
- **Ex**: an example, i.e. a construction together with a proof that it has the stated properties;
- **Conj**: conjecture, not established;
- **Obs**: empirical observation taken from the inputs;
- **Rem**: interpretive remark. It applies the model to the inputs or explains a choice, and asserts no formal claim beyond those it cites;
- **Cmp**: compliance assertion about this document (§8);
- **Spec**: an R5 check with its expected outcome (§7). No Lem, Prop or Cor rests on a Spec.

§6 lists every statement.

**Rem 0.1 (what is deliberately not modelled).** Each primitive below is there because a clause of HYP, the D1 statement of H5, or the performative-ambiguity concept of IF needs it. The following are not modelled, because nothing below needs them:
- conditional norms and provisos (A01);
- norm content beyond "target ↦ value";
- hold, withdraw, supersede and annotate as separate act kinds. Supersede and withdraw are issue acts with another value. Hold, and withdrawal before adoption, are the absence of adoption;
- work outcomes, and any causal "response" relation;
- capability that varies over time;
- drafting time;
- support, frequencies and epistemic-status labels;
- a state/transition view (r0 §2 is withdrawn as unused).

---

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
|---|---|---|---|
| O1 | record identities, recording times r, verbatim texts c | **at most these, and not completely.** Recording times are known as reported, second-hand, and not for every record (A07: "R-80 has no timeline entry"). Verbatim texts exist only where a span exists (F13: R24, R28, R35 have no source span). Capture is local only (G01). The available observation is therefore a function of O that forgets some values. By Lem 1.12 every result below that holds for O also holds for it | A01–A13, A15 (L0, per H01); A07; F13; B06 "RECORDING_ORDER"; G01 |
| O2 | decision time d | **no, not in general** | A07 "Recording times (not decision times)"; F08 "the hour of decision of R-77 and R-79"; G05 |
| O3 | entry into force f (and so F̂) | **no** | G05 "is NOT an upper bound on entry into force"; H12 |
| O4 | norm state σ | **no independent channel.** No locally checkable channel qualifies as an *independent* norm-in-force record (B07, F-B1). Whether one exists anywhere is not determinable (D04). This is not a claim that no corpus text states a norm state; ruling texts do, as self-assertions (Rem 1.11a) | B07; D04; F06; H14 |
| O5 | capability Cap | **no**. Host membership is access, not capability | G07; H19 |
| O6 | constitution con (whether a record *is* the act or *reports* it); reference Ref | **con: not reliably codable on this corpus, within the Claude family, even as a binary distinction** (IF: ACT_SELF_PERFORMED fails for every pair, opus~sonnet 0.598). IF is not an R3 item; IF's pre-registered consequence is "stated, not adopted"; IF shows nothing cross-family. **Ref: no unreliability is shown.** IF reports, exploratorily, that the two capable coders reproduced ACT_REPORTED (1.000) and NORM_STATED (0.941). R4 leaves Ref out of O as a **modelling choice**, and Lem 4.2 shows that 4.1(i) and (ii) do not depend on that choice. B09 (perfect within-family agreement on other fields) supports only the caveat that within-family agreement "is not evidence against correlated error" | IF (not an R3 item); B09 (caveat only); F15 (R2 pending) |
| O7 | keeper k | **no, not in general** | F14; A03 ("no Author line"); F02 |
| O8 | work start time t | **no**. Execution traces give recording order only. File names are not timestamps | B06; G05 (Protocol A L3) |
| O9 | acts and work events that no record references; completeness of the corpus | **no**. Absence is NOT-EVIDENCED-IN-CAPTURE | H11; F08; F11; F13; G01 |

**Lem 1.12.** Over every frame, O does not depend on σ, f, Ref, con, d, Cap, k, or on 𝒜, E, W and their attributes, and O is unchanged by every fresh addition (Def 1.7b). Every function g∘O of O inherits all of these properties. *Proof:* O reads only R, r and c, and none of the listed changes touches them. If O(h) = O(h′), then g(O(h)) = g(O(h′)). ∎

---

## 2. State space and transitions: WITHDRAWN

- **Def 2.1 (state and labels). WITHDRAWN.**
- **Def 2.2 (step rules). WITHDRAWN.** This includes its unlabelled claim "Ad is monotone".
- **Lem 2.3 (class membership as step rules). WITHDRAWN.**

*Reason:* no proof, result or R5 check used them (M-1). The class definitions are Def 3.1 and Def 3.2.

---

## 3. H-D and H-B as model classes

### 3.1 Clauses (derived only from HYP, except where stated)
**Def 3.1.** These are constraints on h ∈ 𝓢. Each one is followed by the HYP text it formalizes and the reading chosen.

| Clause | Constraint | HYP text; reading |
|---|---|---|
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
**Lem 3.3.** D-chg ⇒ B-chg, and B-rec ⇒ D-rec. Neither converse holds. *Proof:* The witness for D-chg is a witness for B-chg's first disjunct, and B-rec contains D-rec. For the converses, see Lem 3.5(b) and (a′). ∎

**Prop 3.4 (near nesting).**
> H-D ∩ H-B = H-D ∩ B-conf ∩ B-recW = H-B ∩ D-chg ∩ D-st.

Hence:
- H-D ∖ H-B = H-D ∖ (B-conf ∩ B-recW);
- H-B ∖ H-D = H-B ∖ (D-chg ∩ D-st).

Each class is contained in the other once exactly two of the other's clauses are added:
- H-D ∩ B-conf ∩ B-recW ⊆ H-B;
- H-B ∩ D-chg ∩ D-st ⊆ H-D.

*Proof:* If h ∈ H-D satisfies B-conf and B-recW, then B-chg holds by Lem 3.3, and B-rec = D-rec ∧ B-recW. So h ∈ H-B. If h ∈ H-B satisfies D-chg and D-st, then D-rec holds by Lem 3.3. D-wrk adds nothing. So h ∈ H-D. The reverse inclusions hold because H-D ∩ H-B satisfies all the clauses. ∎

**Lem 3.5 (neither class contains the other).** Take T = {0, 1}, 𝒯 = {τ}, V = {p, q}, P = {p} and 𝒜 = {x}. The record ρ has some fixed r(ρ) and c(ρ), the same in every item below.
- **(a) A history in H-D ∖ H-B.** E = {a} with a an issue act: (τ, q), prep 1, f = ∞. W = {w} with t(w) = 0. R = {ρ} with Ref = {(ρ, a), (ρ, w)}. σ ≡ q. Then h ∈ H-D, and w violates.
- **(a′) Another history in H-D ∖ H-B.** The same, but with W = ∅ and Ref = {(ρ, a)}. Then h ∈ H-D, and B-recW fails.
- **(b) A history in H-B ∖ H-D.** As in (a), but σ(0) = p, σ(1) = q and t(w) = 0. The change at 1 is explained by earlier work, so B-chg holds. B-conf and B-rec hold. D-chg fails, because no act enters into force at 1.
- **(b′) WITHDRAWN.** In (b′) B-chg also holds through the work disjunct, so it added nothing beyond (b) (M-6).

∎

**Lem 3.6 (shared acceptance of acts; G06).** H-D ∪ H-B ⊆ {h : E ≠ ∅}. *Proof:* Both classes require D-rec. ∎

**Lem 3.7 (the biconditional status reading).** Let D-st⁺ be D-st together with "if Adopted(a, t) holds for some t, then f(a) < ∞". Let H-D⁺′ be H-D′ with D-st⁺ in place of D-st. Let (O′, 𝓗′) satisfy A1, A2 and A5 (§4.1), and the side conditions:
- **(SC1)** 𝓗′ is closed under h ↦ h[Cap := ∅];
- **(SC2)** O′ does not depend on Cap over 𝓗′.

Then Prop 4.1(i) holds with H-D⁺′ in place of H-D′. The base pair (O, 𝓗) satisfies SC1, because no axiom of 𝓗 mentions Cap, and SC2 by Lem 1.12.

*Proof:* Take h* from the proof of 4.1(i). Let h** = h*[Cap := ∅]. Then h** ∈ 𝓗′ by SC1, and O′(h**) = O′(h*) = O′(h) by SC2. No act is adopted in h**, because Adopted requires capability, so the extra clause of D-st⁺ is vacuous. f** ≡ ∞, so D-st is vacuous. D-chg is vacuous, because σ** is constant. D-rec is unchanged, and the H-B clauses do not mention Cap. ∎

---

## 4. D1 as a proposition

### 4.1 Hypotheses
A1–A9 are **hypotheses (Hyp) of the generic results** about a pair (O′, 𝓗′), where 𝓗′ is a frame and O′ an observation on 𝓗′. Lem 4.0a shows that the base pair (O, 𝓗) satisfies all of them except A6, which is a parameter condition. They are **pairwise independent**: §4.4 gives, for each one, a countermodel that violates it and satisfies all the others.

| | Hypothesis | Kind | Motivation (base) |
|---|---|---|---|
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

*Proof:*
- A1, A2, A3, A4 and A8 follow from Lem 1.12.
- A5: F2 mentions neither σ nor f, so h[σ*, f*] satisfies F2 whenever h does, and F1 is assumed.
- A7: a fresh actor or work event does not touch F1 or F2. A fresh (+act) item is unconstituted, so F2 holds for it, and F1 is assumed. A (+ref) addition cannot break F2, since F2 only requires certain pairs to be in Ref.
- A9: F1 mentions neither con nor d, and F2 is assumed. ∎

**Lem 4.0b (joint non-dependence on σ and f).** Assume A1, A2 and A5. Let h ∈ 𝓗′, and let σ*, f* be such that h[σ*, f*] satisfies F1. Then h[σ*, f*] ∈ 𝓗′ and O′(h[σ*, f*]) = O′(h).

*Proof:* Let f^∞ ≡ ∞ on E^iss. Define the intermediate histories
- h¹ = h[σ, f^∞];
- h² = h[σ*, f^∞];
- h³ = h[σ*, f*].

h¹ and h² satisfy F1 vacuously, and h³ satisfies it by assumption. Each is h[·, ·] for an F1-satisfying replacement, so all three are in 𝓗′ by A5. Now:
- h ≈_{f} h¹, so O′(h¹) = O′(h) by A2;
- h¹ ≈_{σ} h², so O′(h²) = O′(h¹) by A1;
- h² ≈_{f} h³, so O′(h³) = O′(h²) by A2. ∎

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

**Rem 4.1s [r3] (Scope).** Parts (i), (ii) and (iii) hold for the Def 3.1 readings and every reading with property M (§9) that also meets Lem 9.2's further condition for that part (M_q^D and N_q for (ii), T for (iii); every M-reading of Table 9.4 meets them, except R-h and the rejected R-k for (iii)), while (i′) is classified in §9 only for R-i and R-i′; Prop 4.1 fails generically under R-c, R-d, R-g, R-g⁺, R-i, R-i′ and R-j, part by part as in Table 9.4, and in the literal wording of (iii) under R-h. Part (iii) holds under every reading with property T.

**Cor 4.1b (the base case).** For (O, 𝓗):
- (i), (i′) and (iii) hold;
- (ii) holds whenever P ≠ V;
- in (i′), {O(h) : h ∈ 𝓗, R_h ≠ ∅} = {o ∈ O(𝓗) : o ≠ ∅}.

*Proof:* Lem 4.0a and Prop 4.1. O(h) = ∅ iff R_h = ∅. ∎

**Rem 4.1a (in words).** On any observation satisfying the hypotheses, "the work violated an unchanged norm" and "the norm state at t(w) was different" are not separated ((ii), (iii)). (iii) adds that the difference can be carried by acts that no record references, which is H5's "without a recorded act". **Only this last clause needs A7.** In a frame where every item must be referenced, the non-separation itself survives, through added acts that are referenced (CM-A7(b)).

**Lem 4.2 (robustness).** Prop 4.1(i) holds for every (O′, 𝓗′) satisfying A1, A2 and A5, and (ii) holds if A6 holds as well; neither needs A3, A4, A7 or A8. In particular, on 𝓗 they hold for O_¬σf, the observation of **every coordinate except σ and f**. O_¬σf sees Ref, con, d, Cap, k, 𝒜, E, W and all their attributes **other than f**. So (i) and (ii) hold even if the reference and mode coding were perfectly reliable. *Proof:* The first sentence is the hypothesis list of Prop 4.1(i) and (ii). O_¬σf satisfies A1 and A2 on 𝓗, because h ≈_σ h′ or h ≈_f h′ leaves every other coordinate unchanged, and 𝓗 satisfies A5 (Lem 4.0a). ∎

**Rem 4.2s [r3] (Scope).** Lem 4.2 holds for the Def 3.1 readings and every reading with property M (§9; Cor 9.3, whose (ii) part also needs M_q^D and N_q); it fails generically under R-c, R-d, R-g, R-g⁺, R-i, R-i′ and R-j, under each of which (i) fails for O_¬σf on 𝓗 (Table 9.4; V1A Rem V3.4a, Lem V3.6(c), V3.9(c), V3.13(b) with Rem V3.14a, V3.15(b), V3.17).

**Cor 4.3 (separation requires dependence on σ or f).** Assume A5 for 𝓗′. If O′(H-D′ ∩ R_B′) ≠ O′(H-B′), then O′ depends, over 𝓗′, on σ or on f. In particular, on 𝓗, no observation that is a function of coordinates other than σ and f separates the classes on R_B. This includes any function of the records, their keepers, Ref, con, Cap, and the acts and work events with their attributes **other than f**. *Proof:* This is the contrapositive of Lem 4.2. ∎

**Rem 4.3s [r3] (Scope).** Cor 4.3 holds for the Def 3.1 readings and every reading with property M (§9; Cor 9.3); it fails generically under R-c, R-d, R-g, R-g⁺, R-i, R-i′ and R-j, under each of which an observation that reads neither σ nor f separates the reading's H-D ∩ R_B from its H-B on 𝓗 (O₄ for R-c, O_¬σf for R-d, R-g, R-g⁺ and R-j, O₃ for R-i and R-i′; V1A Lem V3.3(c), V3.6(d), V3.9(c), V3.17, V3.16(c), V3.13(d)).

Cor 4.3 derives **only** that a separating observation must depend on σ or f. It does **not** derive the further elements of H5 §5.2's necessary condition: independence from the norm-maker, the executor and the self-asserting ruling, and the mapping to a target at t. Those appear only in the sufficient conditions (§4.5) and in Def 4.9. O₂ in CM-A2 depends on f and does separate, as Cor 4.3 allows.

### 4.3 Proofs
***Proof of (i).*** Fix p ∈ P. By Lem 4.0b, with σ* ≡ p and f* ≡ ∞ (so F1 holds vacuously), h* := h[σ*, f*] ∈ 𝓗′ and O′(h*) = O′(h). Then:
- *H-D′:* Chg(h*) = ∅, so D-chg holds. No act enters into force, so D-st holds. D-rec is inherited from h ∈ R_B′, since Ref is unchanged.
- *H-B′:* B-chg holds vacuously. σ* ≡ p gives B-conf. B-rec is inherited.

For the equalities: H-D′ ∩ H-B′ ⊆ H-D′ ∩ R_B′ ⊆ R_B′, and H-D′ ∩ H-B′ ⊆ H-B′ ⊆ R_B′. By the construction, O′(R_B′) ⊆ O′(H-D′ ∩ H-B′). ∎

***Proof of (i′)*** (r2: re-proved; frames are subsets of 𝓗, Def 1.8). Write S := {O′(h) : h ∈ 𝓗′, R_h ≠ ∅}.

*S ⊆ O′(H-D′ ∩ H-B′).* Let h ∈ 𝓗′ with R ≠ ∅. Since 𝓗′ ⊆ 𝓗, h satisfies F1. Pick ρ₀ ∈ R, τ₀ ∈ 𝒯 and v₀ ∈ V. Apply these fresh additions in turn:
1. if 𝒜 = ∅, (+actor) with a fresh x; otherwise let x ∈ 𝒜;
2. (+act) a fresh a₀ with norm (τ₀, v₀), prep 1, α(a₀) = x, d(a₀) = 0 and f(a₀) = ∞ (x ∈ 𝒜 after step 1);
3. (+work) a fresh w₀ with τ(w₀) = τ₀, α(w₀) = x and t(w₀) = 0;
4. (+ref) (ρ₀, a₀);
5. (+ref) (ρ₀, w₀).

Each intermediate history satisfies F1. The history before step 1 is h, which satisfies F1. Steps 1, 3, 4 and 5 change none of E^iss, τ, v, f and σ, which are the only coordinates F1 reads, so they preserve F1. Step 2 leaves σ and every existing act unchanged, and adds a₀ with f(a₀) = ∞, which imposes no F1 condition; so it preserves F1 too. Each step is a fresh addition (Def 1.7b) to a history that is already in 𝓗′ (for step 1, h; afterwards, by the previous step), and its result satisfies F1. So each result is in 𝓗′ by A7. Steps 1–3 are fresh additions of kinds (+actor), (+act) and (+work), so they leave O′ unchanged by A4. Steps 4 and 5 change only Ref, so they leave O′ unchanged by A3. Let h̃ be the result. Then h̃ ∈ 𝓗′ and O′(h̃) = O′(h). h̃ satisfies D-rec through (ρ₀, a₀) ∈ Ref with a₀ ∈ E, and B-recW through (ρ₀, w₀) ∈ Ref with w₀ ∈ W, so h̃ ∈ R_B′. By (i), which needs A1, A2 and A5, there is h̃* ∈ H-D′ ∩ H-B′ with O′(h̃*) = O′(h̃) = O′(h).

*O′(H-D′) ⊆ S and O′(H-B′) ⊆ S.* Every h in H-D′ or in H-B′ satisfies D-rec (H-B′ through B-rec), which needs a pair (ρ, a) ∈ Ref with ρ ∈ R. So R_h ≠ ∅.

*The equalities.* O′(H-D′ ∩ H-B′) is contained in O′(H-D′) and in O′(H-B′), both of which are contained in S, which is contained in O′(H-D′ ∩ H-B′). So all four sets are equal. ∎

***Proof of (ii).*** Take h_c = h* from (i). For h_v, choose q ∈ V ∖ P (A6), and let h_v = h[σ ≡ q, f ≡ ∞]. By Lem 4.0b, h_v ∈ 𝓗′ and O′(h_v) = O′(h). h_v ∈ H-D′ as in (i). The work event w exists by R_B′ and violates, so h_v ∉ H-B′. σ_v has no change points. ∎

***Proof of (iii)*** (r2: re-proved; frames are subsets of 𝓗, Def 1.8). Fix p ∈ P (P ≠ ∅ by Def 1.1). Since h ∈ 𝓗′ ⊆ 𝓗, h satisfies F1. Let Vio = {(t(w), τ(w)) : w violates in h}.
- **Step 1 (σ and f).** Let σ′ = σ, except that σ′(t)(τ) = p for (t, τ) ∈ Vio. Let f′(a) = ∞ if f(a) ∈ T and (f(a), τ(a)) ∈ Vio, and f′(a) = f(a) otherwise. *h[σ′, f′] satisfies F1:* let a ∈ E^iss with f′(a) = t ∈ T. Then f(a) = t and (t, τ(a)) ∉ Vio, so σ′(t)(τ(a)) = σ(t)(τ(a)), and σ(t)(τ(a)) = v(a) by F1 in h. By Lem 4.0b (A1, A2, A5), h² := h[σ′, f′] ∈ 𝓗′ and O′(h²) = O′(h). (By F1 in h, an act a with f(a) ∈ T and f′(a) = ∞ has v(a) = σ(f(a))(τ(a)), and this value is not in P, because a work event violates at (f(a), τ(a)). These are exactly the acts in force at a violation point.)
- **Step 2 (added acts).** Let L be the set of (t, τ) ∈ Chg(h²) for which there is no a ∈ E^iss with τ(a) = τ, f′(a) = t and v(a) = σ′(t)(τ). 𝒜 ≠ ∅, because h ∈ R_B′ satisfies B-recW, so some work event exists and its executor is in 𝒜; fix x ∈ 𝒜. For each (t, τ) ∈ L in turn, apply (+act) with a fresh act a_{t,τ} of norm (τ, σ′(t)(τ)), prep 1, α = x and d = f = t. The history before each addition satisfies F1 (h² does, and by induction). The addition changes neither σ′ nor any existing act, and the new act's own F1 condition is σ′(t)(τ) = v(a_{t,τ}), which holds by the choice of v. So each result satisfies F1. It is a fresh addition to a history in 𝓗′, so it is in 𝓗′ by A7, and O′ is unchanged by A4. Let h′ be the final history. Then h′ ∈ 𝓗′ and O′(h′) = O′(h²) = O′(h).

Check that h′ ∈ H-B′:
- *B-chg:* σ_{h′} = σ′, so Chg(h′) = Chg(h²). Let (t, τ) ∈ Chg(h′). If (t, τ) ∉ L, some original act a has τ(a) = τ, f′(a) = t and v(a) = σ′(t)(τ). If (t, τ) ∈ L, the added act a_{t,τ} has these properties. Either way B-chg's act disjunct holds.
- *B-conf:* W_{h′} = W_h. Let w′ ∈ W. If (t(w′), τ(w′)) ∈ Vio, then σ′ has the value p ∈ P there. Otherwise w′ does not violate in h (if it did, its point would be in Vio), so σ(t(w′))(τ(w′)) ∈ P, and σ′ = σ at that point. So w′ conforms in h′.
- *B-rec:* Ref_{h′} = Ref_h, and E and W only grow. So the D-rec pair and the B-recW pair of h are pairs of h′.

The three bullets of (iii):
- h′ differs from h only in σ (σ′ ≠ σ only on Vio, the violation points of h), in f (f′, extended to the added acts), and by the added acts a_{t,τ}. These are prepared (prep 1). Ref and con are unchanged, so no record references or constitutes them.
- w violates in h, so (t(w), τ(w)) ∈ Vio, and w conforms in h′.
- A change point of σ′ at which no act of h enters into force under f′ with the new value is, by definition, a member of L. Each (t, τ) ∈ L is the entry into force f(a_{t,τ}) = t of the added act a_{t,τ} on τ. ∎

### 4.4 Countermodels (one per hypothesis; each violates exactly that one)
Unless stated otherwise, the parameters are T = {0}, 𝒯 = {τ}, V = {p, q}, P = {p}, 𝒜 = {x}, the frame is 𝓗 and the observation is a variant of O. Recall from Lem 4.0a that 𝓗 satisfies A5, A7 and A9 and that O satisfies A1–A4 and A8.

**Instance I₁.** E = {a} with a an issue act: (τ, q), prep 1, f = ∞. W = {w} with t(w) = 0. R = {ρ} with Ref = {(ρ, a), (ρ, w)}. σ(0)(τ) = q. Then I₁ ∈ H-D ∩ R_B, and w violates.

| Id | Violates only | Why every other hypothesis holds | Instance | What fails |
|---|---|---|---|---|
| **CM-A1** | A1, with O₁ = (O, σ) | σ is unchanged by changes of f, Ref, con or d and by fresh additions. The frame is 𝓗 and A6 holds | I₁ | Every h′ in the O₁-fibre has σ′(0)(τ) = q. B-rec needs a work event, which is on τ at 0, so it violates. Hence O₁(I₁) ∉ O₁(H-B), and (i) fails. (ii) fails, since no h_c exists in the fibre, and (iii) fails, since no h′ ∈ H-B exists in the fibre |
| **CM-A2** | A2, with O₂ = (O, F̂⁰), where F̂⁰(h) = {(a, τ(a), v(a), f(a)) : a ∈ E^iss, prep(a) = 0, f(a) ∈ T} | F̂⁰ does not read σ, Ref, con or d. Fresh additions add only prep-1 acts, so they leave F̂⁰ unchanged (A4). The frame is 𝓗 | I₁, but a has prep 0, d = 0 and f = 0, and Cap = {(x, iss), (x, adp)}. Then h ∈ H-D (D-st holds) | F̂⁰ = {(a, τ, q, 0)}. By F1, every h′ in the O₂-fibre has σ′(0)(τ) = q, so its work violates. (i) fails, and for the same reason (ii) and (iii) fail |
| **CM-A3a** | A3, with O₃ = (O, Ref) | Ref is unchanged by σ, f, d, con and by the A4 additions, whose new items are unreferenced | E = {a} (issue, prep 1, f = ∞), W = ∅, Ref = {(ρ, a)}. Then h ∈ H-D | Every h′ in the O₃-fibre fails B-recW, so (i′) fails. (i) still holds on R_B, by Lem 4.2 |
| **CM-A3b** | A3, with O₃′ = (O, con) | the graph of con is unchanged by σ, f, d, Ref and by the A4 additions, whose new acts are unconstituted | any h with (ρ, a) ∈ Ref | con is constant on the fibre, so no pair is performatively ambiguous. Lem 5.2 fails |
| **CM-A4a** | A4, with O₄ = (O, [W ≠ ∅]) | W is unchanged by σ, f, Ref, con and d | the CM-A3a instance | O₄ shows W = ∅, so B-recW fails throughout the fibre. (i′) fails |
| **CM-A4b** | A4, with O₄′ = (O, E), where E is the set of act identifiers with their kinds. No attribute is included, in particular not f or d | E is unchanged by σ, f, Ref, con and d | I₇ below | h′ must have E′ = E, so no act can be added. (iii) fails as at CM-A7(a) |
| **CM-A5** | A5, with frame 𝓗₅ = 𝓗 plus the axiom "if c(ρ) = c★ for some ρ, then σ(t)(τ) = q for all t" | O satisfies A1–A4 and A8 by Lem 1.12. 𝓗₅ satisfies A7, because a fresh addition that satisfies F1 keeps σ, and A9, because the axiom does not mention con or d | I₁ with c(ρ) = c★. O is unchanged | The whole fibre has σ ≡ q, so no member is in H-B. (i), (ii) and (iii) fail |
| **CM-A6** | A6: V = P = {p} | A6 is a parameter condition only | any h ∈ R_B | No work event can violate, so h_v does not exist. (ii) fails |
| **CM-A7** | A7, with frame 𝓗₇ = 𝓗 plus the axiom "every act and every work event is referenced by some record" | O satisfies A1–A4 and A8. 𝓗₇ satisfies A5, since σ and f do not touch Ref, and A9, since con and d do not touch Ref | I₇ below | (a) and (b) below |
| **CM-A8** | A8, with O₈ = (O, {(a, d(a)) : a ∈ E^iss, prep(a) = 0}) | O₈ does not read σ, f, Ref or con. Fresh additions add only prep-1 acts (A4) | T = {0, 1}; a is an issue act with prep 0, d(a) = 0 and con undefined; Ref = {(ρ, a)}; r(ρ) = 1 | In the O₈-fibre, d(a) = 0 ≠ 1 = r(ρ), so con(a) = ρ would break F2. The pair (ρ, a) is not ambiguous, and Lem 5.2 fails |
| **CM-A9** | A9, with frame 𝓗₉ = 𝓗 plus the axiom "if c(ρ) = c★, then con(a) ≠ ρ for every act a" (a record with text c★ constitutes no act) | O satisfies A1–A4 and A8. 𝓗₉ satisfies A5. It satisfies A7, because fresh acts are unconstituted and adding a reference does not touch con | any h with (ρ, a) ∈ Ref and c(ρ) = c★ | No member of the fibre has con(a) = ρ, so the pair is not ambiguous, and Lem 5.2 fails. Changing con(a) to ρ (with d(a) = r(ρ)) satisfies F2 but leaves 𝓗₉, so A9 fails |

**Instance I₇.** T = {0, 1}. E = {a} (issue, (τ, q), prep 1, f = ∞). W = {w} with t(w) = 1. R = {ρ} with Ref = {(ρ, a), (ρ, w)}. σ ≡ q. Cap = ∅. Then I₇ ∈ H-D ∩ R_B, and w violates.

**CM-A7 (a): the literal form of (iii) fails in 𝓗₇.** By (iii)'s first bullet, σ′ differs from σ only on Vio = {(1, τ)}. w must conform, so σ′ = (q, p), which has a change point at 1. B-chg's act disjunct needs an issue act entering into force at 1 with value p. v(a) = q, and an added act would have to be unreferenced, which 𝓗₇ forbids. The work disjunct needs work before 1, and there is none. So (iii) fails.

**CM-A7 (b): the O-level non-separation survives in 𝓗₇** (C-3). Let h ∈ H-D ∩ R_B in 𝓗₇, and let w violate. Run the construction of (iii), but give each added act a reference from some ρ₀ ∈ R (R ≠ ∅ by R_B). Every item of the result is referenced, F1 and F2 hold, and con is unchanged. So the result h′ is in 𝓗₇, and O(h′) = O(h), because O reads only R, r and c. h′ ∈ H-B: B-chg and B-conf hold as in (iii), and B-rec holds because Ref only grows. So A7 is needed for H5's wording "without a recorded act", not for non-separation on O. ∎

Each row above is a **Lem**: the instance exists and has the stated properties, and the listed reasons establish that the other hypotheses hold.

### 4.5 Sufficient conditions on an additional observable
**Def 4.4.** An additional observable is a function Q on 𝓗. The joint observation is O_Q = (O, Q). H-D and H-B are **distinguishable under O_Q** iff O_Q(H-D) ≠ O_Q(H-B). An outcome y **excludes H-B** iff y ∈ O_Q(H-D) ∖ O_Q(H-B). An outcome **excludes H-D** iff it lies in O_Q(H-B) ∖ O_Q(H-D).

**Prop 4.5 (sufficient for an outcome that excludes H-B).** Let Q(h) be a set of pairs (λ, v) with labels λ from a fixed set Λ.
- **S1 (veridical and mapped at h).** This is a property of Q at a single history h: there is a partial map μ_h : Λ → W_h such that, for every (λ, v) ∈ Q(h), μ_h(λ) is defined and v = σ_h(t(μ_h(λ)))(τ(μ_h(λ))). So at h, Q reports the norm state **for the target of a work event, at that event's start time**.
- **S2 (realizable).** Some h ∈ H-D has some (λ, v) ∈ Q(h) with v ∉ P.
- **S3 (veridical on H-B).** S1 holds at **every h ∈ H-B**. Nothing is required on 𝓗 ∖ H-B, and in particular nothing on H-D ∖ H-B. (r0 called S3 "non-circular" and required S1 on all of 𝓗; that requirement is withdrawn as unnecessary, see Lem 4.8.)

If S2 and S3 hold, then the outcome O_Q(h) of the S2 witness h excludes H-B, so the classes are distinguishable.

*Proof:* O_Q(h) ∈ O_Q(H-D), because h ∈ H-D. Suppose h′ ∈ H-B had O_Q(h′) = O_Q(h). Then (λ, v) ∈ Q(h′). By S3, S1 holds at h′, so μ_{h′}(λ) is a work event of h′ at which σ_{h′} has the value v ∉ P. That work event violates, which contradicts B-conf. ∎

**Rem 4.5 (r0's unlabelled remark after Prop 4.5). WITHDRAWN.** r0 said: "Such a Q only ever excludes H-B. It never excludes H-D, because the trivial construction of 4.1(i) keeps any Q-value equal to p. This matches E01's direction." *Reason:* the claim is false (Ex 4.5a), and its justification was invalid. A Q-veridicality axiom on the frame mentions σ, so A5 fails and 4.1(i) does not apply. Also, if |P| ≥ 2, setting σ ≡ p changes reported values in P ∖ {p}. No R4 statement now links Prop 4.5 to E01's "H-D makes no prediction".

**Ex 4.5a (Q*: a Q satisfying S1–S3 with an outcome that excludes H-D).** Take the parameters of Lem 3.5, and fix a work identifier w₁ ∈ 𝔚. Let Λ = {λ₁, λ₂}. If w₁ ∈ W_h, let μ_h(λ₁) = μ_h(λ₂) = w₁. If w₁ ∉ W_h, μ_h is the empty partial map (r2, NB-1). Let
- Q*(h) = ∅ if w₁ ∉ W_h;
- otherwise Q*(h) = {(λ₁, s_h)} ∪ {(λ₂, s_h) : h ∉ H-D}, where s_h = σ_h(t(w₁))(τ(w₁)).

Then:
- **(a)** S1 holds at every h ∈ 𝓗, by construction (vacuously when w₁ ∉ W_h, since Q*(h) = ∅ there), so S3 holds;
- **(b)** S2 holds: h_a = Lem 3.5(a) with w = w₁ is in H-D, and Q*(h_a) = {(λ₁, q)} with q ∉ P. By Prop 4.5, O_{Q*}(h_a) excludes H-B;
- **(c)** h_b = Lem 3.5(b) with w = w₁ is in H-B ∖ H-D, and Q*(h_b) = {(λ₁, p), (λ₂, p)}, since σ(0) = p and t(w₁) = 0. Every h′ whose Q*-value contains a λ₂-pair is outside H-D. So O_{Q*}(h_b) ∈ O_{Q*}(H-B) ∖ O_{Q*}(H-D): **this outcome excludes H-D.** ∎

Q* reads class membership. It is a formal counterexample to the withdrawn remark, not a proposed instrument.

**Rem 4.5b (the content of S3 is external).** As functions on 𝓗, observables that satisfy S1 at every history exist trivially (for example Q1 in §7). The substantive content of S2 and S3 is an *external* claim: that some instrument's output on the actual history equals Q(h) for a Q satisfying S3. In other words, the instrument must be veridical and mapped at least whenever the actual history is in H-B. Nothing in R4 shows that such an instrument exists or does not exist (H14).

**Prop 4.6 (sufficient for an outcome that excludes H-D; the symmetric case).** Fix τ and t₁ < t₂. Let Q′(h) = (σ(t₁)(τ), σ(t₂)(τ), X(h)), where X(h) = 1 iff there is a ∈ E^iss with τ(a) = τ, f(a) ∈ (t₁, t₂], (α(a), iss) ∈ Cap and Adopted(a, f(a)). Suppose:
- **S1′. WITHDRAWN as a model hypothesis.** *Reason:* Q′ is defined directly from coordinates, so "Q′ is veridical on 𝓗" is vacuous (S-3, M-5). Its external content is kept in Rem 4.6a.
- **S2′.** Some h ∈ H-B has σ(t₁)(τ) ≠ σ(t₂)(τ) and X(h) = 0.

Then O_{Q′}(h) excludes H-D.

*Proof:* Let h′ have O_{Q′}(h′) = O_{Q′}(h). Then σ_{h′} changes on τ somewhere in (t₁, t₂], and X(h′) = 0. At that change point, D-chg needs a capable act entering into force with the new value, and D-st needs that act to be adopted then; such an act would give X(h′) = 1. So h′ ∉ H-D. And h ∈ H-B. ∎ The witness for S2′ is Lem 3.5(b) with t₁ = 0, t₂ = 1.

**Rem 4.6a.** For Q′ to be realized, an instrument would have to return Q′(h) for the actual history. In particular, X = 0 would have to be a *positive* attestation that no capable, adopted act entered into force, not an absence in capture (H11). Q′ needs Cap, and host membership does not supply it (G07, H19).

**Lem 4.7 (the mapping in S1 is needed).** There is a parameter choice and a Q₀ that is veridical about σ at a fixed clock time but not mapped to any work event, under which O_{Q₀}(H-D ∩ R_B) = O_{Q₀}(H-B).

*Proof.* Take T = {0, 1}, 𝒯 = {τ}, V = {p, q}, P = {p}, 𝒜 = {x}, and Q₀(h) = σ(0)(τ). For each nonempty o and each v, build h with the records of o as follows (the references come from one record of o):
- σ = (v, p);
- a₁ is a referenced issue act: (τ, q), prep 1, f = ∞;
- if v = q, a₂ is an unreferenced issue act: (τ, p), prep 0, d = f = 1, with Cap = {(x, iss), (x, adp)};
- w is a referenced work event with t(w) = 1.

This h is in H-D ∩ H-B, so both images contain every (o, v) with o nonempty, and neither contains anything else. ∎

**Lem 4.8 (r1: veridicality on H-D alone does not suffice; the executor report fails S2).**

*Withdrawn r0 content.* r0's Lem 4.8 ("non-circularity is needed") and its ledger statement "a Q veridical only within one class does not suffice" are **WITHDRAWN**. *Reason:* the ledger claim is false for the class H-B. S1 on H-B alone suffices (Prop 4.5 with S3 as restated; check K9.5). r0's example Q_e fails S2, not S3, and is not of Prop 4.5's form.

*r1 statement.*
- **(a)** Take the parameters of Lem 3.5, q ∉ P, and a fixed w₁ ∈ 𝔚. If w₁ ∈ W_h, let μ_h(λ₁) = w₁; if w₁ ∉ W_h, μ_h is the empty partial map (r2, NB-1). Let Q_m(h) be:
  - ∅ if w₁ ∉ W_h;
  - {(λ₁, σ_h(t(w₁))(τ(w₁)))} if w₁ ∈ W_h and h ∈ H-D;
  - {(λ₁, q)} if w₁ ∈ W_h and h ∉ H-D.

  This is a "norm-maker report" that is veridical only on H-D. Q_m satisfies S1 at every h ∈ H-D (vacuously when w₁ ∉ W_h), and it satisfies S2. But the outcome of the S2 witness h_a = Lem 3.5(a) does **not** exclude H-B. So Prop 4.5's conclusion can fail if S3 is replaced by "S1 at every h ∈ H-D".
- **(b)** The executor's report "permitted when I began" is Q_e(h) = {(λ_e, p)} if W ≠ ∅, with μ_h(λ_e) any work event, and Q_e(h) = ∅ otherwise. Q_e fails **S2**, because it never reports a value outside P. If P = {p}, Q_e satisfies S3, because B-conf gives σ = p at every work point of a history in H-B. O_{Q_e} satisfies A1 and A2, since Q_e depends only on whether W ≠ ∅, so Prop 4.1(i) gives O_{Q_e}(H-D ∩ R_B) = O_{Q_e}(H-B).

*Proof of (a).* Build h_a and h_b of Lem 3.5 with w = w₁ and the same r(ρ) and c(ρ), so that O(h_a) = O(h_b). h_a ∈ H-D with σ(0) = q, so Q_m(h_a) = {(λ₁, q)}. h_b ∉ H-D, so Q_m(h_b) = {(λ₁, q)}. h_b ∈ H-B, so O_{Q_m}(h_a) ∈ O_{Q_m}(H-B). ∎ *(b)* is shown in its statement. ∎

**Def 4.9 (independence, as in F06 and H5 §5.2).** A record ρ (inside or outside the corpus) is **independent for τ** in h iff all three hold:
- ρ constitutes no act on τ, that is, there is no a with con(a) = ρ and τ(a) = τ (or τ(obj(a)) = τ);
- k(ρ) ≠ α(a) for every act a on τ;
- k(ρ) ≠ α(w) for every work event w on τ.

**Rem 4.9a (the role of Def 4.9).** Independence is the base's criterion (F06, H5 §5.2) for when an instrument may be taken to satisfy S3 (veridical and mapped, at least on H-B) without presupposing either hypothesis. It bears on veridicality only; it does not bear on realizability (S2), which is a separate condition (r2, NB-2). Nothing in the model derives S3 from Def 4.9, or Def 4.9 from S3. Formally, what suffices is S2 + S3 (Prop 4.5), or S2′ (Prop 4.6). Def 4.9 is used formally only in Prop 5.4(c).

### 4.6 Relation of §4.5 to the H5 gaps (no observable is claimed to exist; H14)

**Table 4.6 (Rem: interpretive analysis).** This table applies §4.5 and Cor 4.3 to the base items. It is interpretation, not observation.

| H5 gap (base id) | Bears on | Reading in R4 |
|---|---|---|
| Host-side records; Protocol C sealed and not executed (F01) | candidate source of a Q | Undetermined. Its stated limits map onto the conditions. "Current state only": the reported state is the present one, not σ at t(w), so S1's mapping is not met as stated. "Events retention is an unverified assumption": veridicality for past t is not established. "Q3 defaults to INDEPENDENCE_NOT_DETERMINABLE": Def 4.9 is undetermined, so the base gives no ground to grant S3. "Q2 needs an API relation to e4724a44 or its PR": this corresponds to S1's map μ to a work event. The "< 10%" estimate is not used. Host membership cannot supply Cap for Prop 4.6 (G07, H19) |
| Signer verification declined; signer kind of the K-SIG headers undetermined (F02, B07) | the keeper condition of Def 4.9 | Undetermined |
| Content of status, register, CI and hook files not read; K-CI and K-HOOK history not read (F03) | candidate sources of a Q | Whether S3 and Def 4.9 hold is undetermined. A file kept by the norm-maker would fail Def 4.9 |
| `IMPLEMENTATION_PROGRESS.md:58` not acquired; execution reports uncoded (F04; texts in A09, A11, A15) | O, not Q | As corpus-type records they enlarge O. By Cor 4.3, as records they cannot separate the classes. They could only do so if treated as a Q satisfying S2 and S3, and the base gives no ground for that. This agrees with A15: the S0815 reports "fit both hypotheses" |
| Self-contamination; corpora outside this git history (F05, G03) | the boundary of O; outside sources are candidate Qs | Undetermined |
| The missing fact (F06) | **S3 (veridical and mapped, at least on H-B) together with Def 4.9**: a record by someone other than the norm-maker, the executor or the self-asserting ruling, attesting σ(t)(τ) at a stated t | NOT_DETERMINABLE (D04). Its motivating time, 2026-08-04T00:30:25Z, is a recording-order time (B06, L1). So even S1's mapping would need t(w) to be fixed relative to recording time, which H12 and G05 do not allow to be read off |
| H5 §6 reopening conditions | condition 2: a candidate route to S2 + S3 (an independent record mapped to a register target at a stated t); condition 1: a test of S1, S3 and Def 4.9 on host data; condition 3: a coding defect | By Lem 4.2, a defect in the reference and mode coding cannot affect 4.1(i) or (ii). It could only affect (i′) |
| The waiver (D02) | none | 4.1 is a formal result relative to an observation. The waiver concerns whether channels were exhausted, which R4 does not address |

**Rem 4.10 (E01 in R4 terms; no new verdict).** E01's NON-CONFORMS verdict amounts to the value "σ at the WP-4B RED commit ∉ P". It was obtained from the ruling texts (A01, A02) and from a commit's *recording* time (A03). For it to be an outcome that excludes H-B under Prop 4.5, it would need:
- S2 and S3 for the corpus ruling texts read as an instrument. R4's frame does not grant this; that is the modelling choice A5. B07 says only that no local channel is an *independent* norm-in-force record, and R4 does not decide whether the ruling texts are veridical. If those records constitute the acts, the texts are self-assertions (§5);
- a mapping from the commit's recording time to t(w), and from the rulings' recording times to f, which H12 forbids reading off.

R4 therefore leaves E01 at its recorded bearing (weak, one-sided and confounded) and adds no verdict.

---

## 5. Performative ambiguity

**Def 5.1.** Let O′ be an observation on a frame 𝓗′, and 𝓒 ⊆ 𝓗′ a class. Identity of records and acts across histories is as in Def 1.7a. A pair (ρ, a) with a an act is **performatively ambiguous in 𝓒 at o** iff there are h₁, h₂ ∈ 𝓒 ∩ O′⁻¹(o) such that:
- (ρ, a) ∈ Ref in both h₁ and h₂;
- con_{h₁}(a) = ρ, so in h₁ the record *is* the act;
- con_{h₂}(a) ≠ ρ, so in h₂ the record *reports* a separate act.

A record is performatively ambiguous if some pair containing it is. This is a property of the fibre O′⁻¹(o), not of any one history.

**Lem 5.2.** Let (O′, 𝓗′) satisfy A3, A8 and **A9 (no c–con tie)**.
- For 𝓒 = 𝓗′ and 𝓒 = H-B′: every pair (ρ, a) ∈ Ref, with a an act, of every h ∈ 𝓒 is performatively ambiguous in 𝓒 at O′(h).
- If A1, A2 and A5 also hold, the same is true for 𝓒 = H-D′ and every h ∈ H-D′ ∩ R_B′, and for 𝓒 = H-D′ ∩ H-B′ and every h ∈ H-D′ ∩ H-B′.

*Proof* (r2: re-proved; frames are subsets of 𝓗, Def 1.8). Let h be as in the statement, and let (ρ, a) ∈ Ref_h with a ∈ E_h. Since h ∈ 𝓗′ ⊆ 𝓗, h satisfies F2.

*The starting history h⁰.* For 𝓒 = 𝓗′ and 𝓒 = H-B′, let h⁰ := h. In the H-D cases, A1, A2 and A5 hold, and h ∈ R_B′ (for the second case because H-B′ ⊆ R_B′). Fix p ∈ P and let h⁰ := h[σ ≡ p, f ≡ ∞]. This replacement satisfies F1 vacuously, so h⁰ ∈ 𝓗′ and O′(h⁰) = O′(h) by Lem 4.0b, and h⁰ ∈ H-D′ ∩ H-B′ by the proof of 4.1(i). In every case:
- h⁰ ∈ 𝓒 and O′(h⁰) = O′(h);
- h⁰ has the same items, Ref, con, d and r as h, so (ρ, a) ∈ Ref_{h⁰};
- h⁰ satisfies F2 (as a member of 𝓗′ ⊆ 𝓗).

*Three steps.* Starting from h⁰, build three histories in turn:
- **h₂** := h⁰ with con(a) made undefined (h₂ = h⁰ if it is already undefined). F2 imposes no condition on a in h₂. For every other act b, con(b), d(b), Ref and r are those of h⁰, so F2 for b is inherited from h⁰. So h₂ satisfies F2. Only con was changed, so h₂ ∈ 𝓗′ by A9 (applied to h⁰ ∈ 𝓗′), and O′(h₂) = O′(h⁰) by A3;
- **h₂′** := h₂ with d(a) := r(ρ). F2 imposes no condition on a, because con(a) is undefined. For every other act b, con(b), d(b), Ref and r are unchanged. So h₂′ satisfies F2. Only d was changed, so h₂′ ∈ 𝓗′ by A9 (applied to h₂ ∈ 𝓗′), and O′(h₂′) = O′(h₂) by A8;
- **h₁** := h₂′ with con(a) := ρ. F2 for a holds, because (ρ, a) ∈ Ref (Ref is unchanged throughout) and d(a) = r(ρ). For every other act b nothing relevant changed. So h₁ satisfies F2. Only con was changed, so h₁ ∈ 𝓗′ by A9 (applied to h₂′ ∈ 𝓗′), and O′(h₁) = O′(h₂′) by A3.

Hence h₁, h₂ ∈ 𝓗′ ∩ O′⁻¹(O′(h)), (ρ, a) ∈ Ref in both, con_{h₁}(a) = ρ, and con_{h₂}(a) is undefined, so con_{h₂}(a) ≠ ρ.

*Class membership.* h₂, h₂′ and h₁ differ from h⁰ only in con and d.
- 𝓒 = 𝓗′: shown above.
- 𝓒 = H-B′: B-chg, B-conf and B-rec read σ, f, τ, v, the work events with their attributes, and Ref. None of them reads con or d. So h₂, h₂′ and h₁ satisfy them, as h⁰ does.
- 𝓒 = H-D′ and 𝓒 = H-D′ ∩ H-B′: h⁰ has σ ≡ p and f ≡ ∞, and the three steps change neither. So Chg = ∅ and D-chg holds; no act enters into force, so D-st is vacuous (Adopted, which reads d, is never invoked); D-rec reads only Ref, which is unchanged. Together with the H-B′ argument, h₂, h₂′ and h₁ are in H-D′ ∩ H-B′.

So h₁, h₂ ∈ 𝓒 ∩ O′⁻¹(O′(h)), and (ρ, a) is performatively ambiguous in 𝓒 at O′(h).

∎ The countermodels are CM-A3b (O sees con), CM-A8 (O sees d) and CM-A9 (a c–con tie) in §4.4.

**Rem 5.2a (what the model does and does not say).** In the model every referenced pair is ambiguous, including a record such as R-56 whose text states its own mode (A10: "a recording note marked not part of the ruling"). That is because the frame has no axiom tying c to con (A9). The relevant condition is this absence of a c–con tie, not A3. Whether real texts do fix the mode is the empirical question of Conj 5.5, which the model does not settle.

**Cor 5.3 (the R-A3 convention).** Assume the hypotheses of Lem 5.2. A coding rule that fixes the mode for a class of records, such as R-A3 "act and after-state are the same record" (G04), selects a proper subset of each fibre in which both modes occur. Within the model, that choice is therefore a **convention, not an observation**. *Proof:* By Lem 5.2, each fibre contains both h₁ and h₂ for every referenced pair, and a rule fixing the mode excludes one of them. ∎

**Prop 5.4 (relation to D1).**
- **(a) Removing the ambiguity does not remove D1.** Let O⁺ = (O, Ref, con, d). No pair is performatively ambiguous under O⁺, because con is seen. Yet O⁺ satisfies A1 and A2, so 4.1(i) and (ii) hold for O⁺ by Lem 4.2. So, in this model, performative ambiguity is **not necessary** for non-identifiability.
- **(b) What removing it gives.** Under O⁺, d(a) = r(con(a)) is determined for every constituted act (F2). In H-D, every issue act a in force satisfies f(a) ≥ min{d(b)}, where b ranges over its effective adopters (including a itself when prep(a) = 0); this follows from D-st. If all of these adopters are constituted, recording time becomes a **lower** bound on entry into force. This is consistent with G05, which says recording time is not an *upper* bound. H-B has no such bound: take an act constituted at r = 1, with prep 0 and f = 0. The bound concerns f, which is unobserved (A2), so no separation follows.
- **(c) What the ambiguity blocks.** If (ρ, a) is performatively ambiguous in 𝓒 at o and a is an act on τ, then "ρ is independent for τ" (Def 4.9) is **false in h₁**, by its first clause. So it does not hold throughout the fibre 𝓒 ∩ O′⁻¹(o), and O′ cannot certify any such record as independent. (Independence may also fail in h₂, for example when k(ρ) = α(a). Nothing here depends on h₂.) Keeper unobservability (O7) is a **separate**, second obstacle to the same certification.

∎ (Each part's proof is given in its statement.)

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

## 6. Statement ledger

Status: **=** unchanged from r0; **rev** revised in r1; **new** new in r1; **WD** WITHDRAWN. **r2** marks a row whose statement or proof was changed in r2 (mapped in `CHANGELOG-r2.md`); every row without it is unchanged from r1. **[r3]** marks a row added in r3 (mapped in `CHANGELOG-r3.md`). The [r3] scope sentences added to Prop 4.1, Lem 4.2 and Cor 4.3 change no statement's content within the Def 3.1 readings and no proof; their rows are not re-marked.

| ID | Statement (short) | Label | Status | Base ids / inputs |
|---|---|---|---|---|
| Rem 0.1 | what is deliberately not modelled | Rem | rev | A01, HYP, H5, IF |
| Def 1.1 | parameters T, 𝒯, V, P, Σ | Def | = | A01, A10 (targets named in texts) |
| Def 1.2 | actors, constant Cap | Def | = | HYP (capability) |
| Def 1.3 | norm (τ, v); σ is a primitive | Def | = | HYP |
| Def 1.4 | issue and adoption acts; α, d, τ, v, prep, f, obj; typing of obj | Def | rev | HYP; A05, A06, A08 |
| Def 1.5 | work event: τ, executor, t | Def | = | HYP |
| Def 1.6 | record: r, c, k; Ref; con | Def | = | G01, G04, IF |
| Def 1.7 | history, coordinates and their comparison, ≈_X | Def | rev | none |
| Def 1.7a | cross-history identity through identifier universes | Def | new | none |
| Def 1.7b | replacements h[σ*, f*], h[Cap*]; fresh additions (+actor, +work, +act, +ref) | Def | new | none |
| F1 | entry into force sets σ | Def | = | none (meaning of f) |
| F2 | con(a) = ρ ⇒ Ref and d(a) = r(ρ) | Asm | rev (basis) | modelling assumption; motivated by IF's candidate concept, an L2 hypothesis that is NOT established and that F2 does not assume |
| F3 | typing of obj | — | **WD** (redundant with Def 1.4; M-2) | — |
| Def 1.8 | 𝓢; base frame 𝓗 = F1 ∧ F2; frames are subsets of 𝓗 (r2); no completeness, no norm→work, no time order | Def | rev; **r2** (frames ⊆ 𝓗; B-1) | H10, H11, H12, H04 |
| Def 1.9 | the three times; d = r only under con; f never equated | Def | rev | G05, H04, A07 |
| Rem 1.9a | F2 does not let d be read from r, because con is unobserved | Rem | new (was unlabelled) | O6 |
| Def 1.10 | Adopted, F̂, Chg, violation | Def | = | HYP |
| Def 1.11 | O(h) = {(ρ, r, c)}; observations on a frame; dependence over 𝓗′; fibres | Def | rev | O1–O9 |
| Rem 1.11a | texts are observed as texts; self-assertions are not observations of σ (modelling choice) | Rem | new (was unlabelled) | A02, A05, A06 |
| O1 | O sees at most record ids, r (second-hand, incomplete) and verbatim texts where spans exist | Obs | rev | A01–A13, A15, A07, F13, B06, G01, H01 |
| O2 | decision times not observed in general | Obs | = | A07, F08, G05 |
| O3 | entry into force not observed; r is not an upper bound | Obs | = | G05, H12 |
| O4 | no *independent* norm-state channel locally; existence elsewhere not determinable | Obs | rev | B07, D04, F06, H14 |
| O5 | capability not observed; membership is not capability | Obs | = | G07, H19 |
| O6 | con not reliably codable (within-family, IF); Ref left out by modelling choice, no unreliability shown | Obs | rev | IF (not R3), B09 (caveat), F15 |
| O7 | keeper not observed in general | Obs | = | F14, A03, F02 |
| O8 | work start time not observed; recording order only | Obs | = | B06, G05 |
| O9 | completeness not shown; absence ≠ non-occurrence | Obs | = | H11, F08, F11, F13, G01 |
| Lem 1.12 | O depends on no hidden coordinate and is unchanged by fresh additions; every function of O inherits this | Lem | rev | none |
| Def 2.1, Def 2.2, Lem 2.3 | state space, step rules, "Ad is monotone", step-rule membership | — | **WD** (unused; M-1) | — |
| Def 3.1 | clauses D-chg, D-st, D-rec, B-chg, B-conf, B-recW, B-rec, with readings | Def | rev | HYP; E01 (universal reading of conformance) |
| Rem 3.1a | B-conf's universal reading is a modelling choice from E01 (L1); asymmetry | Rem | new | E01, HYP |
| Rem 3.1b | B-chg's work disjunct accepts any target: near-vacuity disclosed | Rem | new | HYP |
| Rem 3.1c | B-fb and "primarily" not formalized; symmetric | Rem | new | HYP |
| Def 3.2 | H-D, H-B, R_B; primed classes for a frame 𝓗′ | Def | rev | HYP; H07 |
| Lem 3.3 | D-chg ⇒ B-chg; B-rec ⇒ D-rec; converses fail | Lem | = | none |
| Prop 3.4 | H-D ∩ H-B = H-D ∩ B-conf ∩ B-recW = H-B ∩ D-chg ∩ D-st | Prop | = | none |
| Lem 3.5 | witnesses (a), (a′), (b): neither class contains the other | Lem | rev | none |
| Lem 3.5(b′) | second H-B ∖ H-D witness | — | **WD** (adds nothing beyond (b); M-6) | — |
| Lem 3.6 | both classes need E ≠ ∅ | Lem | = | G06 |
| Lem 3.7 | 4.1(i) under D-st⁺, given SC1 (no frame constraint on Cap) and SC2 (O′ blind to Cap) | Lem | rev | G07 |
| A1–A9 | hypotheses of the generic results; pairwise independent | Hyp | rev (A4, A5, A7 restated; A9 new) | as in the §4.1 table |
| Lem 4.0a | (O, 𝓗) satisfies A1–A5 and A7–A9 | Lem | new | none |
| Lem 4.0b | joint non-dependence on σ and f via explicit intermediate histories | Lem | new | none |
| Prop 4.1(i) | D1 core: every R_B′ observation is produced inside H-D′ ∩ H-B′ | Prop | rev (generic) | (via A1, A2, A5) B07, D04, G05, H12 |
| Prop 4.1(i′) | the images are equal without restriction | Prop | rev (generic; A7 added); **r2** (proof re-done; B-1) | (via A3, A4, A7) IF, H11 |
| Prop 4.1(ii) | violation of an unchanged norm and conformance in the same O′-fibre | Prop | rev (O′-equality added) | as (i) |
| Prop 4.1(iii) | H5's local wording, through unreferenced acts | Prop | rev (generic); **r2** (proof re-done; B-1) | (via A4, A7) H11, F08, F11 |
| Cor 4.1b | base case of Prop 4.1 | Cor | new | none |
| Rem 4.1a | the "in words" gloss; only the "unreferenced" clause needs A7 | Rem | new (was unlabelled) | H5 |
| Lem 4.2 | robust to perfectly reliable reference and mode coding (all attributes except f) | Lem | rev | none |
| Cor 4.3 | separation needs dependence on σ or f (it does not derive H5 §5.2's further conditions) | Cor | rev (label, scope) | H5 §5.2 |
| CM-A1, CM-A2, CM-A3a, CM-A3b, CM-A4a, CM-A4b, CM-A5, CM-A6, CM-A7(a), CM-A7(b), CM-A8, CM-A9 | one countermodel per hypothesis, each violating exactly that one | Lem | rev / new | none |
| Def 4.4 | additional observable, distinguishability, exclusion | Def | = | none |
| Prop 4.5 | S2 + S3 (S1 at every h ∈ H-B) suffice for an outcome that excludes H-B | Prop | rev | F06; H5 §5.2 |
| Rem 4.5 | "such a Q never excludes H-D" | — | **WD** (false; Ex 4.5a) | — |
| Ex 4.5a | Q*: satisfies S1–S3 and has an outcome that excludes H-D | Ex | new; **r2** (μ_h only when w₁ ∈ W_h; NB-1) | none |
| Rem 4.5b | the content of S2 and S3 is an external realization claim | Rem | new | H14 |
| Prop 4.6 | S2′ suffices for an outcome that excludes H-D | Prop | rev | H11, G07, H19 |
| S1′ | Q′ veridical on all of 𝓗 | — | **WD** as a model hypothesis (vacuous; S-3) | — |
| Rem 4.6a | external content of Q′; X = 0 must be positive attestation; Cap needed | Rem | new (was unlabelled note) | H11, G07, H19 |
| Lem 4.7 | an unmapped Q does not suffice | Lem | = | none |
| Lem 4.8 | r0: "a Q veridical only within one class does not suffice" | — | **WD** (false) | — |
| Lem 4.8 (r1) | (a) veridicality on H-D alone does not suffice; (b) the executor report fails S2, not S3 | Lem | new content, same id; **r2** ((a): μ_h only when w₁ ∈ W_h; NB-1) | none |
| Def 4.9 | independence of a record | Def | = | F06, H5 §5.2 |
| Rem 4.9a | role of Def 4.9 (a criterion for S3 only); not derived in the model | Rem | new (was in Def 4.9); **r2** (S3 only; NB-2) | F06, H5 §5.2 |
| Table 4.6 | relation to the H5 gaps; nothing claimed to exist | Rem | rev (label, S-conditions) | F01–F06, B06, B07, D02, D04, G03, A09, A11, A15, H14 |
| Rem 4.10 | E01 would need S2/S3 premises that R4 does not grant; no new verdict | Rem | rev (label, weakened) | E01, A01, A02, A03, B07, H12 |
| Def 5.1 | performative ambiguity relative to (O′, 𝓒) | Def | rev (identity) | IF |
| Lem 5.2 | every referenced pair is ambiguous under A3, A8, A9 | Lem | rev; **r2** (proof re-done; B-1) | O6, O2 |
| Rem 5.2a | in the model even R-56 is ambiguous; the relevant condition is no c–con tie | Rem | new | A10, IF |
| Cor 5.3 | R-A3 is a convention, not an observation (within the model) | Cor | rev | G04 |
| Prop 5.4 | (a) not necessary for D1; (b) lower bound on f under H-D; (c) blocks certification of independence | Prop | rev ((c)) | G05, O7 |
| Rem 5.4a | the closing interpretation, applied to the corpus | Rem | new (was unlabelled) | IF |
| Obs IF-obs | binary instrument FAIL; ACT_SELF_PERFORMED disagreement anatomy (exploratory) | Obs | rev (now in body, §5) | IF |
| Conj 5.5 | the ambiguity (no c–con tie) explains the ACT_SELF_PERFORMED failure on the register and ruling records outside S_mode; falsifiers F-a to F-c, pre-registered; F-b's decision rule pre-fixed; F-c may have no power | Conj | rev; **r2** (F-b rule; scope; F-c power; NB-4, NB-5) | IF; F15; A10 |
| K1–K12 | R5 checks | Spec | rev; **r2** (K11.2, K12.1; NB-6) | none |
| §8 rows | compliance with section H | Cmp | rev | H01, H04, H07, H10, H11, H12, H14, H18, H19 |
| Def 9.1 | reading; reading classes H-D^𝐑, H-B^𝐑, record base R_B^𝐑; Def 3.1 readings 𝐑₀; v-quiescence; RB1, RB2, M_p, M_p^W, M_q^D, N_q; property M; act-explained, T; S-form, O-form | Def | **[r3]** new | V1A Def V1.1, V1.2, V2.1–V2.4 |
| Lem 9.2 | **Lemma M**: (o) 𝐑₀ has every property; (i) M ⇒ Prop 4.1(i) by h*; (ii) M_p^W ⇒ h_c half, M_q^D ∧ N_q ⇒ h_v half; (iii) T ⇒ (iii) for any D-side reading | Lem | **[r3]** new (proof by reference) | V1A Lem V2.5, V2.6 |
| Cor 9.3 | under M, Lem 4.2 and Cor 4.3 transfer ((ii) part of Lem 4.2 also needs M_q^D, N_q) | Cor | **[r3]** new (proof by reference) | V1A Cor V2.8, Lem V2.6(ii); Lem 4.2 |
| Table 9.4 | reading table R-a … R-k: HYP licence, M / not-M, status of (i), (ii), (iii) | Rem | **[r3]** new | HYP (via V1A Def V1.3); V1A §3 lemmas, Table V3.0 |
| Rem 9.5 | V1b finite results per reading, with scope caveat (U, one target, Claude-family, predictions not independent); no Lem, Prop or Cor rests on it | Rem | **[r3]** new | V1-RECONCILIATION; V1A Rem V3.1a, V3.14a |
| Prop 9.6 | **weak form**: for (O, 𝓗), N ≥ 1, P ≠ V, o ≠ ∅, records-only O does not separate the hypotheses under every tested reading ((a) (i)-O; (b) (ii)-O), except (b) under R-j | Prop | **[r3]** new (proof by reference) | R4 Prop 4.1, Cor 4.1b, Lem 3.7, Lem 4.0a; Lem 9.2; V1A Lem V3.2, V3.4, V3.5, V3.7, V3.8, V3.10, V3.11, V3.13–V3.17; V1-RECONCILIATION (finite confirmation only) |
| Rem 4.1s, 4.2s, 4.3s | scope of Prop 4.1, Lem 4.2, Cor 4.3 per reading (§9) | Rem | **[r3]** new | Table 9.4; V1A |
| Rem 9.7 | under R-d the corpus relevance of the non-separation rests additionally on Cap being unobserved (O5); the SC1/SC2 recovery is not finite-verified | Rem | **[r3]** new | V1A Lem V3.6, V3.7, Rem V3.7a; R4 Lem 3.7; O5; V1-RECONCILIATION |
| Rem 9.8 | R-a is untested (needs two targets) | Rem | **[r3]** new | V1A Lem V3.1, Rem V3.1a, Lem V4.1(e); V1-RECONCILIATION |

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

### 7.2 Checks and expected outcomes (Spec)
For existential checks, report the least witness. For universal checks, report the first counterexample if there is one (order as in §7.1). Model-size counts may be reported, but no count is predicted. **Any deviation from an expected outcome is a finding against R4.** "For every o" means for every value (r, c) ∈ T × Σ of O.

| # | Check | Expected |
|---|---|---|
| K1 | The following are nonempty: H-D, H-B, H-D ∩ H-B, H-D ∖ H-B, H-B ∖ H-D. Also, every history in H-D ∪ H-B has a present act (Lem 3.6) | all true |
| K2 | For all h: D-chg ⇒ B-chg and B-rec ⇒ D-rec. Witnesses against both converses exist | true; witnesses exist |
| K3 | H-D ∩ H-B = H-D ∩ B-conf ∩ B-recW = H-B ∩ D-chg ∩ D-st (Prop 3.4) | true |
| K4 | (i) For every h ∈ R_B there is h* ∈ H-D ∩ H-B with h* ≈_{σ, f} h, i.e. equal on every enumerated coordinate except σ and the values of f. This implies O(h*) = O(h) and O_¬σf(h*) = O_¬σf(h) (Lem 4.2), so no separate run is needed | true |
| K4b | Lem 3.7: K4 with D-st replaced by D-st⁺, with h* ≈_{σ, f, Cap} h | true |
| K5 | (i′) O(H-D) = O(H-B) = O(H-D ∩ H-B) = all four values of (r, c) | true |
| K6 | (ii) For every h ∈ R_B with w1 referenced there are h_v ∈ H-D ∖ H-B, with σ_v constant and w1 violating, and h_c ∈ H-D ∩ H-B. Both are ≈_{σ, f} h, hence have the same O | true |
| K7 | (iii) Take every h ∈ H-D ∩ R_B with a2 absent and w1 violating. There is h′ ∈ H-B with the same O such that: h′ differs at most in σ (only at t(w1)), in f, and by a2 present as an unreferenced iss act with prep 1 and con none; w1 conforms; and every change point of σ′ at which no act of h enters into force (under f′) with the new value is f(a2) | true |
| K8.1 | ¬A1: O₁(H-B) ⊊ O₁(H-D ∩ R_B). The difference is exactly the values with σ = (q, q), for every o. *Derivation (R-3):* (q, q) ∉ O₁(H-B), because w1 is on τ and would violate. (p, p) is realized by h* of K4. (q, p) is realized in H-B by an iss act with v = p and f = 1 and w1 at 1. (p, q) is realized in H-B by w1 at 0, which explains the change at 1. All four values are realized in H-D ∩ R_B, using a capable, adopted act with prep 0 entering into force at 1 for the two non-constant values | true, exactly as stated |
| K8.2 | ¬A2: for every o, the value (o, F̂⁰ = {(a1, q, 0), (a2, q, 1)}), with both slots iss and prep 0, lies in O₂(H-D ∩ R_B) ∖ O₂(H-B). (One forced prep-0 act does not suffice in U: with T = {0, 1}, a prep-1 act at the other time, or work at 0, escapes F̂⁰. The two-act value forces σ ≡ q) | true |
| K8.3 | ¬A3: a value of O₃ with Ref containing no (ρ1, w1) lies in O₃(H-D) ∖ O₃(H-B). (i) still holds for O₃ on R_B | true; true |
| K8.4 | ¬A4: the value "w1 absent" lies in O₄(H-D) ∖ O₄(H-B). Under O₄′, (iii) fails at I7, and the failure comes only from (iii)'s literal requirement of an added act | true; fails |
| K8.5 | ¬A5 in U₅: (r, c2) ∈ O(H-D ∩ R_B) ∖ O(H-B) for both values of r | true |
| K8.6 | ¬A6 in U₆: no h_v exists, so (ii) fails | (ii) fails |
| K8.7 | ¬A7 in U₇: (iii) fails at I7, because an added a2 would have to be referenced. In U, (iii) holds at I7. In U₇, there is nevertheless h′ ∈ U₇ ∩ H-B with O(h′) = O(I7) in which w1 conforms, namely a2 iss with v = p, prep 1, d = f = 1 and referenced, and σ′ = (q, p) (CM-A7(b)) | fails in U₇; holds in U; exists |
| K9.1 | Q1: (o, q) ∈ O_Q1(H-D) ∖ O_Q1(H-B) for every o, and O_Q1(H-B) ⊆ O_Q1(H-D) | true; true |
| K9.2 | Q0 (unmapped): O_Q0(H-D ∩ R_B) = O_Q0(H-B) = every (o, v) | true |
| K9.3 | Qe: O_Qe(H-D ∩ R_B) = O_Qe(H-B), and Qe never takes a value outside P (S2 fails; Lem 4.8(b)) | true; true |
| K9.4 | Q2: both O_Q2(H-B) ∖ O_Q2(H-D) and O_Q2(H-D ∩ R_B) ∖ O_Q2(H-B) are nonempty. Witnesses: σ = (p, q) with F̂ = ∅, and σ = (q, q) | true |
| K9.5 | Q1ʙ (S1 only on H-B suffices; Prop 4.5): every value of O_Q1ʙ on H-B has Q1ʙ = p, and (o, q) ∈ O_Q1ʙ(H-D) ∖ O_Q1ʙ(H-B) for every o | true; true |
| K9.6 | Qm (S1 only on H-D does not suffice; Lem 4.8(a)): Qm = σ(t(w1)) on every h ∈ H-D with w1 present; some h ∈ H-D has Qm = q; and (o, q) ∈ O_Qm(H-D) ∩ O_Qm(H-B) for every o | true; true; true |
| K9.7 | Q* (Ex 4.5a): for every o, (o, {(λ1, q)}) ∈ O_Q*(H-D) ∖ O_Q*(H-B) and (o, {(λ1, p), (λ2, p)}) ∈ O_Q*(H-B) ∖ O_Q*(H-D) | true; true |
| K9.8 | Q′ (Prop 4.6 directly): for every o, (o, (p, q, 0)) ∈ O_Q′(H-B) ∖ O_Q′(H-D); and no value with σ(0) ≠ σ(1) and X = 0 lies in O_Q′(H-D) | true; true |
| K10.1 | Lem 5.2: for every h ∈ 𝓒 and every present act s with (ρ1, s) ∈ Ref there are h₁ and h₂ in 𝓒 ∩ O⁻¹(O(h)) with (ρ1, s) ∈ Ref in both, con(s) = ρ1 in h₁ and con(s) = none in h₂. Run for 𝓒 = U and 𝓒 = H-B with every h ∈ 𝓒; for 𝓒 = H-D with every h ∈ H-D ∩ R_B; and for 𝓒 = H-D ∩ H-B with every h ∈ 𝓒 (R-4: the lemma's scope) | true |
| K10.2 | Under O⁺ no pair is ambiguous. K4 holds for O⁺ | true; true |
| K10.3 | (a) For every h ∈ U and every present slot s with con(s) = ρ1: d(s) = r(ρ1). Hence in every O⁺-fibre every constituted act has d = r(ρ1). (b) For every o there are h, h′ ∈ U ∩ O⁻¹(o) and a slot s, present and referenced in both, with d_h(s) ≠ d_{h′}(s) | true; true |
| K10.4 | In H-D, every present iss s with f(s) ≠ ∞, prep 0, con(s) = ρ1 and no adp act on s satisfies f(s) ≥ r(ρ1). Some h ∈ H-B violates this | true; exists |
| K10.5 | ¬A8: under O₈ there is a referenced pair that is not ambiguous in U (e.g. a1 iss, prep 0, d = 0, r(ρ1) = 1) | true |
| K10.6 | ¬A3 for Lem 5.2: under O₃′ no pair is ambiguous in U | true |
| K10.7 | ¬A9 in U₉: for every h with c(ρ1) = c2 and a present referenced slot, the pair is not ambiguous in U₉. For c(ρ1) = c1, K10.1 holds in U₉ | true; true |
| K11.1 | U is closed under removing w1 (with its Ref entry) and under adding w1 at any t without a reference. That is, there is no norm→work and no absence→non-occurrence constraint (H10, H11) | true |
| K11.2 | In U, call a present iss slot s **forced** iff it enters into force, i.e. f(s) ∈ T (f(s) ≠ ∞) (r2, NB-6). There are referenced, forced slots with f(s) > r(ρ1), and others with f(s) < r(ρ1). That is, there is no bound from r in the frame (H12) | true |
| K12.1 | Independence of the countermodels (observations). For each of O, O₁, O₂, O₃, O₃′, O₄, O₄′, O₈, report the change groups over U on which it depends: σ-only, f-only, Ref-only, con-only, d-only, and (+act)/(+work) additions. Ref-only and con-only are separate groups (r2, NB-6). Expected: O none; O₁ {σ}; O₂ {f}; O₃ {Ref}; O₃′ {con}; O₄ and O₄′ {additions}; O₈ {d} | exactly as stated |
| K12.2 | Independence of the countermodels (frames). For each of U, U₅, U₇, U₉, report which of the closure properties fail: A5 (σ, f replacements satisfying F1), A7 restricted to (+act), (+work) and (+ref), and A9 (con-only or d-only changes satisfying F2). Expected: U none; U₅ {A5}; U₇ {A7}; U₉ {A9} | exactly as stated |

Conj 5.5 is empirical and is **not** a check for R5.

### 7.3 What cannot be tested in U (R-6)
- **Def 4.9 and Prop 5.4(c).** With 𝒜 = {x} and k(ρ1) = x, ρ1 is never independent for τ whenever some act or work event on τ is present. So Prop 5.4(c), and any use of Def 4.9 with a present act, cannot be exhibited in U.
- **(+actor) additions**, since 𝒜 is fixed. A4 and A7 are checked in U only for (+act), (+work) and (+ref).
- **The generic statements** (Prop 4.1 for arbitrary (O′, 𝓗′), Lem 4.0b, Lem 3.7's side conditions). U tests the base pair and the listed variants only.
- **Phenomena that need |P| ≥ 2, more than one target, more than one record or N > 1.** Examples are the |P| ≥ 2 failure of the withdrawn Rem 4.5's justification, and B-chg's any-target vacuity (Rem 3.1b), which needs two targets to show.
- **Every external realization claim** (Rem 4.5b, Rem 4.6a, Table 4.6) and **Conj 5.5**.

---

## 8. Compliance with section H

Each row is a **Cmp** (compliance assertion about this document). The rows cite the definitions they rest on; none rests on an R5 check.

| Item | How R4 complies |
|---|---|
| H01 (may) | L0 items (A01–A13, A15, G04, G06) are used as text data, with the provenance and second-hand caveats of O1. L1 items are also used: as scope and limits (B07, **[r3]** F01–F08, F11, F13–F16, G01, G03, G05, G07; F09, F10 and F12 are QUARANTINED in `R3-FROZEN.md`, schema-dependent and not usable for R4, and are not cited); as modelling motivation (E01 fixes B-conf's universal reading, Rem 3.1a; it is discussed in Rem 4.10); and as provenance of a time (B06 in Table 4.6). H01 is a MAY and does not forbid this. A14 and A16 are not used. IF is cited but is not an R3 item |
| H04 (may) | Three distinct time functions. The frame equates d and r only under the unobserved con (F2, Def 1.9). It never equates f with r or d |
| H07 | H-D and H-B are defined as subsets of 𝓗 (or of a frame 𝓗′) and are never assumed (Def 3.2) |
| H10 | The base frame has no norm→act or norm→work axiom (Def 1.8). B-conf is a clause of a hypothesis, never asserted of the corpus |
| H11 | The base frame does not require completeness (Def 1.8; A7; O9). Prop 4.6 requires a *positive* attestation (Rem 4.6a) |
| H12 | No decision order and no entry into force is read from r (Def 1.8, Def 1.9, O2, O3). Prop 5.4(b) is conditional on con and is a lower bound under H-D only |
| H14 | §4.5 and §4.6 state sufficient conditions and counterexamples only. No observable is claimed to exist or not to exist (Rem 4.5b) |
| H18 | No support is computed or pooled anywhere |
| H19 | Cap is unobserved. Host membership is never read as Cap (O5; Rem 4.6a) |
| Prohibitions | No act or norm counts. No coding-dependent premise: Lem 4.2 shows that 4.1(i) and (ii) do not rest on any coding. The IF reliability values are cited only as provenance (O6, Obs IF-obs, Conj 5.5); no Lem, Prop or Cor uses them |

---

## 9. Reading robustness [r3]

**[r3] New section.** §9 states how far Prop 4.1, Lem 4.2 and Cor 4.3 depend on the readings of HYP fixed in Def 3.1. Besides the r0 inputs, it uses `V1A-LEMMA.md` (r1, reviewed PASS_WITH_LIMITATIONS, same-family; cited as **V1A**, with its V-prefixed statement ids) and `V1-RECONCILIATION.md` (cited as **V1-REC**). §9 restates V1A's definitions and results; the only new inferences are elementary steps (Cor 9.3's (ii) clause; small steps in Prop 9.6), labelled as such. Every proof is an exact reference to V1A or to §1–§4 of this document. No proof in §1–§8 is changed.

### 9.1 Readings and Lemma M

**Def 9.1 [r3] (reading; reading classes; properties).** This is V1A Def V1.1, V1.2 and V2.1–V2.4, restated.
- A **reading** 𝐑 gives four finite sets of predicates on 𝓗: the D-side dyn clauses 𝔇_dyn^𝐑, the D-side record clauses 𝔇_rec^𝐑, the B-side dyn clauses 𝔅_dyn^𝐑, and the B-side record clauses 𝔅_rec^𝐑. ("Dyn" clauses concern change, status or conformance. "Record" clauses are D-rec, B-recW, B-rec or readings of them.)
- For a frame 𝓗′: H-D^𝐑′ is the set of h ∈ 𝓗′ that satisfy every clause in 𝔇_dyn^𝐑 ∪ 𝔇_rec^𝐑; H-B^𝐑′ is the same for 𝔅_dyn^𝐑 ∪ 𝔅_rec^𝐑; and the **record base** R_B^𝐑′ is the same for 𝔅_rec^𝐑 alone. So H-B^𝐑′ ⊆ R_B^𝐑′, as in Def 3.2.
- The **Def 3.1 readings** 𝐑₀ are 𝔇_dyn = {D-chg, D-st}, 𝔇_rec = {D-rec}, 𝔅_dyn = {B-chg, B-conf} and 𝔅_rec = {B-rec}. Then H-D^𝐑₀′ = H-D′, H-B^𝐑₀′ = H-B′ and R_B^𝐑₀′ = R_B′.
- For v ∈ V, g ∈ 𝓗 is **v-quiescent** iff σ_g ≡ v and f_g ≡ ∞.
- **RB1 (blindness):** no record clause of 𝐑 depends on {σ, f}. **RB2 (dominance):** every g ∈ 𝓗 that satisfies 𝔅_rec^𝐑 also satisfies 𝔇_rec^𝐑.
- For p ∈ P and q ∈ V ∖ P: **M_p** iff every dyn clause of 𝐑 holds at every p-quiescent g ∈ 𝓗 that satisfies 𝔅_rec^𝐑; **M_p^W** iff the same holds for every such g with W_g ≠ ∅; **M_q^D** iff every clause in 𝔇_dyn^𝐑 holds at every q-quiescent g ∈ 𝓗 that satisfies 𝔅_rec^𝐑; **N_q** iff every q-quiescent g ∈ 𝓗 that satisfies 𝔅_rec^𝐑 and has W_g ≠ ∅ fails some clause in 𝔅_dyn^𝐑.
- 𝐑 has **property M** iff it satisfies RB1, RB2 and M_p for some p ∈ P.
- g ∈ 𝓗 is **act-explained** iff for every (t, τ) ∈ Chg(g) some a ∈ E^iss_g has τ(a) = τ, f(a) = t and v(a) = σ_g(t)(τ). 𝐑 has **T** iff (T1) every clause in 𝔅_dyn^𝐑 holds at every act-explained g ∈ 𝓗 with W_g ≠ ∅ in which every work event conforms, and (T2) every clause in 𝔅_rec^𝐑 does not depend on {σ, f} and is preserved by every (+act) fresh addition (Def 1.7b).
- A part of Prop 4.1 is read in its **S-form** when its witnesses must be ≈_{σ, f} h, as stated in §4.2, and in its **O-form** when they need only have the same observation as h.

**Lem 9.2 [r3] (Lemma M: reading monotonicity).** Let (O′, 𝓗′) be a pair and 𝐑 a reading.
- **(o)** 𝐑₀ satisfies RB1, RB2, M_p for every p ∈ P, M_q^D and N_q for every q ∈ V ∖ P, and T.
- **(i)** Assume A1, A2, A5, and that 𝐑 has M (with p ∈ P). Then for every h ∈ R_B^𝐑′ the history h* := h[σ ≡ p, f ≡ ∞] satisfies h* ∈ H-D^𝐑′ ∩ H-B^𝐑′, h* ≈_{σ, f} h and O′(h*) = O′(h). Hence
  > O′(H-D^𝐑′ ∩ R_B^𝐑′) = O′(H-B^𝐑′) = O′(H-D^𝐑′ ∩ H-B^𝐑′) = O′(R_B^𝐑′).
- **(ii)** Assume A1, A2, A5, A6, RB1 and RB2, and let p ∈ P and q ∈ V ∖ P.
  - **(ii-c)** If M_p^W holds, then for every h ∈ R_B^𝐑′ with W_h ≠ ∅, h_c := h[σ ≡ p, f ≡ ∞] is in H-D^𝐑′ ∩ H-B^𝐑′, h_c ≈_{σ, f} h, O′(h_c) = O′(h), and every work event conforms in h_c.
  - **(ii-v)** If M_q^D and N_q hold, then for every h ∈ R_B^𝐑′ and every w ∈ W_h, h_v := h[σ ≡ q, f ≡ ∞] is in H-D^𝐑′ ∖ H-B^𝐑′, h_v ≈_{σ, f} h, O′(h_v) = O′(h), σ_v is constant, and w violates.

  So Prop 4.1(ii) holds for 𝐑 if both parts hold; if only one holds, exactly the corresponding half survives with R4's proof.
- **(iii)** Assume A1, A2, A4, A5, A7, and that 𝐑 has T. Let h ∈ R_B^𝐑′ and let w violate in h. Then the history h′ of the proof of Prop 4.1(iii) is in H-B^𝐑′, O′(h′) = O′(h), and the three bullets of Prop 4.1(iii) hold. No D-side clause is used, so this holds for every D-side reading.

*Proof:* (o) is V1A Lem V2.5. (i), (ii) and (iii) are V1A Lem V2.6, with the proofs given there. The proof of (i) is the proof of Prop 4.1(i) in §4.3 with its six clause checks replaced by RB1, RB2 and M_p. The proof of (iii) runs the proof of Prop 4.1(iii) in §4.3, taking 𝒜 ≠ ∅ from α(w) ∈ 𝒜 instead of from B-recW; V1A notes that this substitution is also valid for 𝐑₀, and the proof in §4.3 is not changed. ∎

**Cor 9.3 [r3] (Lem 4.2 and Cor 4.3 transfer with M).** Let 𝐑 have M. Then Prop 4.1(i) holds for 𝐑 for every pair satisfying A1, A2 and A5, in particular for O_¬σf on 𝓗; and on 𝓗 no observation that is a function of coordinates other than σ and f separates H-D^𝐑 ∩ R_B^𝐑 from H-B^𝐑. If 𝐑 also satisfies M_q^D and N_q, Prop 4.1(ii) holds for 𝐑 for every such pair that also satisfies A6, in particular for O_¬σf on 𝓗 when P ≠ V.

*Proof:* The first sentence is V1A Lem V2.6(i) (with Cor V2.8). For the second, O_¬σf satisfies A1 and A2 on 𝓗 and 𝓗 satisfies A5 (Lem 4.2, Lem 4.0a); apply Lem 9.2(ii). ∎

### 9.2 The reading table

**Table 9.4 [r3] (Rem: the readings; every entry is proved in the V1A statement cited in its row; the table adds no claim).** The readings are those of V1A Def V1.3; each changes the clauses of 𝐑₀ as stated, and everything not mentioned is as in 𝐑₀. "Holds" means that the part holds for every pair satisfying R4's hypothesis list for that part, with R4's proof (Lem 9.2). "Fails gen." means that some pair satisfying that list violates the part. (O, 𝓗) is the base pair.

| Id | Change to 𝐑₀ | HYP licence (one line) | M | (i) | (ii) | (iii) | V1A |
|---|---|---|---|---|---|---|---|
| **R-a** | B-chg's work disjunct is target-tied: τ(w) = τ | "norms change … in response to work", read as a response to work on the norm's own target (named, not adopted, in Rem 3.1b) | **M** | holds | holds | holds | Lem V3.1 |
| **R-b** | B-conf → "some w ∈ W conforms" | "work is expected to conform", read as a tendency of the system, not a property of every work event (cf. Rem 3.1a) | **M** | holds | holds | holds | Lem V3.2 |
| **R-c** | R-b, and B-rec → D-rec (B-recW dropped) | R-b's phrase, with "the record is expected to carry, or reference, both" read as defeasible for its work half; the matrix's combination, with a disclosed asymmetry in reading "expected to" | **not-M** | fails gen.; holds with A4 and A7 added | holds | holds | Lem V3.3, V3.4 |
| **R-d** | D-st → D-st⁺ (Lem 3.7) | "the force of a norm depends on the status of the act", read as "is determined by" (adopted ⇒ in force at some time) | **not-M** | fails gen.; holds under SC1, SC2, with ≈_{σ, f, Cap} | fails gen.; holds under SC1, SC2, with ≈_{σ, f, Cap} | holds | Lem V3.6, V3.7; Lem 3.7 |
| **R-e** | D-chg without "(α(a), iss) ∈ Cap" | "by actors holding the capability to perform them", read as a description of the actors, not a condition (strained; named by the matrix) | **M** | holds | holds | holds | Lem V3.5 |
| **R-f** | B-rec → D-rec (B-recW dropped); B-conf stays universal | "the record is expected to carry, or reference, both", read as defeasible for its work half | **M** | holds | holds | holds | Lem V3.8 |
| **R-g** | B-chg ∧ "some (t, τ) ∈ Chg(h) has some w ∈ W with t(w) < t" | "norms change through norm-making acts and in response to work", read as a description (at least one change follows work); licence contested | **not-M** | fails gen. (O_¬σf; also the middle equality under O_Cap, which satisfies A1–A9); holds for (O, 𝓗) with N ≥ 1, P ≠ V; inconsistent (H-B^g = ∅) at N = 0 | h_v half holds; h_c half fails gen. in S-form, holds in O-form for (O, 𝓗) with N ≥ 1, P ≠ V | **fails** (also for (O, 𝓗)) | Lem V3.9, V3.10 |
| **R-g⁺** | B-chg^g ∧ "some change point is made by an act entering into force with its value" | the same sentence, read as a description of both conjuncts | **not-M** | as R-g | as R-g | **fails** | Lem V3.17 |
| **R-h** | B-rec ∧ "every act and every work event is referenced by some record" | "the record is expected to carry, or reference, both", read universally | **M** (not T) | holds | holds | literal wording **fails** (at I₇, also for (O, 𝓗)); holds with referenced added acts under A3 as well | Lem V3.11, V3.12 |
| **R-i** | B-rec → "E ≠ ∅" | "Both hypotheses accept that norm-making acts exist" (Lem 3.6), with the record sentence read as defeasible for both halves | **not-M** (RB2 fails) | fails gen. (S-form; O₃); for (O, 𝓗) fails exactly at o = ∅ and holds on o ≠ ∅; (i′) and Cor 4.1b also fail at o = ∅ | S-form fails gen.; O-form holds under A1, A2, A3, A5, A6, A7 | holds | Lem V3.13, V3.14 |
| **R-i′** | no B-side record clause | as R-i, with the shared acceptance read as a statement about both hypotheses jointly, not a clause of H-B | **not-M** (RB2 fails) | as R-i | as R-i, with A4 added for the O-form | holds | Lem V3.13, V3.14 |
| **R-j** | D-chg ∧ "Chg(h) ≠ ∅" | "norms change only through such acts", read as a description (norms do change, and only through acts); the D-side analogue of R-g | **not-M** | fails gen. (O_¬σf); holds for (O, 𝓗) with N ≥ 1, P ≠ V; inconsistent (H-D^j = ∅) at N = 0 | h_v half **fails for every pair** (H-D^j has no constant-σ history); the S-form h_c half also fails; the base-pair O-form h_c holds (V1A Lem V3.16(a)) | holds | Lem V3.15, V3.16 |
| **R-k** | B-chg's act disjunct also requires Adopted(a, t) (R-k^st) or (α(a), iss) ∈ Cap (R-k^cap) | "norms change through norm-making acts", read with H-D's status and capability qualifiers; weak licence. **REJECTED** as a reading of HYP: it imports H-D's clauses into H-B (V1A Rem V3.18a) | **M** (not T) | holds | holds | literal wording **fails** at I₇ | Lem V3.18 |

The not-M readings are R-c, R-d, R-g, R-g⁺, R-i, R-i′ and R-j. R-h and R-k have M but not T. Every M-reading in the table also satisfies M_q^D and N_q, and every reading other than R-g, R-g⁺, R-h and R-k satisfies T (V1A Table V3.0).

### 9.3 V1b finite results

**Rem 9.5 [r3] (V1b finite results; reported from V1-REC, not re-derived).** **Scope caveat.** These are results of a blind exhaustive enumeration of the finite universe **U** of §7.1: **one target**, T = {0, 1}, one actor and one record, which is always present. Every agent involved was **Claude-family**. The predictions were sealed by the same analyst who wrote V1A, so they are **not independent**. A finite result in U does not establish a generic statement (§7.3). No Lem, Prop or Cor of R4 rests on this remark.

*Integrity (V1-REC).* The predictions were sealed before reading (`f5a0d0219`, plus the V1B-SUPP-RETURN seal); isolation was clean; the re-run was identical apart from runtime; the sizes of U and U₅ equal R5's. The planted controls of all eight readings with sealed predictions were VALID. (V1-REC calls these "the 8 non-M readings". By V1A Table V3.0 they are the seven not-M readings together with R-h, whose (iii) does not transfer.) Every scorable prediction was CONFIRMED: the HOLDS/FAILS patterns, the exact difference sets where reported, and all essential least-counterexample features (P1.1–P1.6, P1.10–P1.13). There were 0 minor deviations. The inputs do not map these prediction numbers to readings, except P1.7 (R-h).

| Reading | V1b result in U |
|---|---|
| 𝐑₀ | reproduces R4 / R5 |
| R-a | **untested** (Rem 9.8) |
| R-b, R-e, R-f | hold; Lemma M is not falsified |
| R-c | control VALID; scorable predictions CONFIRMED |
| R-d | control VALID; scorable predictions CONFIRMED. The global K4b and the ≈_{σ, f, Cap} variants were not requested by the spec and are **unscorable** (Rem 9.7) |
| R-g, R-g⁺ | control VALID; scorable predictions CONFIRMED |
| R-h | (i) and (ii) hold. Control VALID after a blind supplementary evaluation of H_U5h. The only falsified prediction is P1.7 (the R-h least counterexample), which was identified as wrong before the run and kept as sealed (addendum). The referenced-a2 variant is **unscorable** |
| R-i, R-i′ | control VALID after a blind supplementary evaluation of H_U5i; scorable predictions CONFIRMED. The base-pair failure at o = ∅ is untestable in U, because ρ1 is always present (V1A Rem V3.14a) |
| R-j | control VALID; scorable predictions CONFIRMED |
| R-k | not predicted (rejected); the inputs report no V1b result |

H_U5h and H_U5i had been omitted by the main analyst's spec-extraction error; the supplementary evaluation only added these named histories. Also **unscorable**, and not attributed to a reading in the inputs: P1.4's least witness; P1.8 (the combination was not run); some exact O_¬σf sets (output truncated; the counts and prefixes are consistent).

### 9.4 The weak form

**Prop 9.6 [r3] (weak form: records-only O does not separate the hypotheses).** *Scope:* the base pair (O, 𝓗), with N ≥ 1 (needed only for R-g, R-g⁺ and R-j, which are inconsistent at N = 0) and P ≠ V; the **tested readings** 𝕋 = {𝐑₀, R-b, R-c, R-d, R-e, R-f, R-g, R-g⁺, R-h, R-i, R-i′, R-j}, i.e. every reading of Table 9.4 run in V1b (R-a is untested and R-k rejected). For every 𝐑 ∈ 𝕋:
- **(a) (i)-O.** For every o ≠ ∅: o ∈ O(H-D^𝐑 ∩ R_B^𝐑) ⟺ o ∈ O(H-B^𝐑) ⟺ o ∈ O(H-D^𝐑 ∩ H-B^𝐑) ⟺ o ∈ O(R_B^𝐑). For 𝐑 ∉ {R-i, R-i′} none of the four images contains ∅, so the image equalities of Prop 4.1(i) hold without restriction. For R-i and R-i′ they fail exactly at o = ∅.
- **(b) (ii)-O, except under R-j.** For 𝐑 ≠ R-j: for every h ∈ R_B^𝐑 and every work event w referenced in h there are h_v ∈ H-D^𝐑 ∖ H-B^𝐑, with σ_v constant and w violating, and h_c ∈ H-D^𝐑 ∩ H-B^𝐑, in which every work event conforms, with O(h_v) = O(h_c) = O(h).
- **Exception.** Under R-j, (b) fails, for every pair and not only for (O, 𝓗): H-D^j contains no history with constant σ (V1A Lem V3.15(d)).

*What Prop 9.6 does not say.* It concerns O only, not every observation satisfying A1, A2 and A5, and it is an O-form statement, not an S-form one; under the not-M readings both stronger forms fail (Table 9.4). It does not cover R-a or R-k. (Both have M, M_q^D and N_q, so (a) and (b) follow for them from Lem 9.2 as well; but neither was tested, and they are outside the stated scope.)

*Proof.* (O, 𝓗) satisfies A1–A5, A7–A9 (Lem 4.0a), A6 (P ≠ V), and SC1 and SC2 (Lem 3.7). Reading by reading:
- 𝐑₀: Prop 4.1(i), (ii) and Cor 4.1b.
- R-b, R-e, R-f, R-h: each has M, M_q^D and N_q (V1A Lem V3.2, V3.5, V3.8, V3.11), so Lem 9.2(i) and (ii) apply. The images contain no ∅, because every member of the record base satisfies D-rec (RB2), which needs a record.
- R-c: (a) by V1A Lem V3.4(c); (b) by V1A Lem V3.4(a).
- R-d: (a) by V1A Lem V3.7(a); (b) by V1A Lem V3.7(b); both use SC1 and SC2.
- R-g, R-g⁺: (a) by V1A Lem V3.10(a) (N ≥ 1, P ≠ V) and Lem V3.17. (b): the h_v half by Lem 9.2(ii-v) (V1A Lem V3.10(b)); the h_c half by (a) applied to O(h), which is nonempty because w is referenced. Every work event conforms in h_c, because B-conf is universal under R-g and R-g⁺.
- R-i, R-i′: (a) by V1A Lem V3.13(c) and V3.14(a). (b) by V1A Lem V3.14(b), whose hypotheses A1, A2, A3, A5, A6, A7 (and A4 for R-i′) hold for (O, 𝓗).
- R-j: (a) by V1A Lem V3.16(a) (N ≥ 1, P ≠ V). The exception is V1A Lem V3.15(d). ∎

*Finite confirmation.* U has N = 1 and P ≠ V, and every observation in U is nonempty. V1-REC reports that the weak form survived under every valid V1b run, with the R-j exception (Rem 9.5). This is a finite confirmation under the caveat of Rem 9.5, not part of the proof. V1-REC attributes the survival to the freedom inside each O-fibre: for the not-M readings, Prop 9.6 is obtained only by spending further freedoms of the frame (A4 and A7 for R-c; SC1 and SC2 for R-d; the choice of Cap, acts and work times for R-g, R-g⁺ and R-j; a nonempty record for R-i and R-i′; V1A Rem V5.2).

### 9.5 R-d and capability

**Rem 9.7 [r3] (under R-d, non-identifiability rests additionally on Cap being unobserved).** Under R-d, the non-separation of Prop 9.6 rests not only on σ and f being unobserved (O3, O4) but also on **Cap being unobserved (O5)**:
- an observation that reads neither σ nor f but reads Cap separates: O_¬σf(R_B) ⊄ O_¬σf(H-D⁺ ∩ H-B), and O_¬σf(H-D⁺ ∩ R_B) ≠ O_¬σf(H-B), on 𝓗 (V1A Lem V3.6(c), (d));
- (i) and (ii) are recovered under SC1 (𝓗′ closed under Cap := ∅) and SC2 (O′ blind to Cap), through witnesses that are ≈_{σ, f, Cap} h, not ≈_{σ, f} h (Lem 3.7; V1A Lem V3.7, Rem V3.7a). SC1 and SC2 are **sufficient**; their necessity is not shown. O satisfies SC2 because it does not read Cap (Lem 1.12).

So for R-d, the corpus relevance of Prop 9.6 rests on Obs O5 (G07, H19), that the available observation is Cap-blind; this is an observation about the corpus, not a theorem (the proof itself uses SC2 for O, Lem 1.12). The SC1/SC2 recovery is established formally (V1A) and is **not finite-verified**: the ≈_{σ, f, Cap} variants and the global K4b were unscorable in V1b (Rem 9.5). V1A also shows that under R-g, R-g⁺ and R-j every member of H-D ∩ H-B^g, respectively of H-D^j, has Cap containing an iss pair and an adp pair, so that Cap-reading observations separate there as well (V1A Lem V3.9(d), V3.16(c)); there too, Prop 9.6 uses the free choice of Cap inside the O-fibre (V1A Rem V3.10a).

### 9.6 R-a

**Rem 9.8 [r3] (R-a is untested).** R-a was **not tested** in V1b. With one target, as in U, B-chg^a and B-chg coincide, because τ(w) = τ always holds; R-a needs at least two targets (V1A Rem V3.1a; V1-REC). Its classification in Table 9.4 (M, with (i), (ii) and (iii) holding) rests on V1A Lem V3.1 alone. The combination R-a ∘ R-g is likewise untestable in U (V1A Lem V4.1(e)). R-a is therefore outside the scope of Prop 9.6.

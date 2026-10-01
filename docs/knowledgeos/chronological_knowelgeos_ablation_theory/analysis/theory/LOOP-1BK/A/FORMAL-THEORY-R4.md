# FORMAL-THEORY-R4: the minimal formal theory (draft)

| | |
|---|---|
| Inputs (only) | `R3-CODING-INDEPENDENT.json` (base items cited by id, e.g. A07); `HYPOTHESES.md` (HYP); `H5.md` (H5); `INSTRUMENT-FINDING.md` (IF) |
| Status | Draft. Written by one same-family (Claude) analyst. Not reviewed. Every proof below is meant to be checked by hand and by the R5 enumeration (§7) |
| Standing rule | H-D and H-B are **sets of histories** (model classes). Neither is assumed anywhere. No statement uses an act count, a norm count or any other coding-dependent value |

**Labels.** Every statement carries one of these labels: **Def** (definition), **Asm** (assumption), **Lem** (lemma), **Prop** (proposition), **Cor** (corollary), **Conj** (conjecture), **Obs** (empirical observation from the inputs). §6 lists all of them.

**Minimality.** Each primitive below is there because a clause of HYP, the D1 statement of H5, or the performative-ambiguity concept of IF needs it. The following are **deliberately not modelled**, because nothing below needs them:
- conditional norms and provisos (A01);
- norm content beyond "target ↦ value";
- hold, withdraw, supersede and annotate as separate act kinds. Supersede and withdraw are issue acts with another value. Hold, and withdrawal before adoption, are the absence of adoption;
- work outcomes, and any causal "response" relation;
- capability that varies over time;
- drafting time;
- support, frequencies and epistemic-status labels.

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

Every adoption act b ∈ E^adp has an object obj(b) ∈ E^iss.

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

### 1.7 History
**Def 1.7.** A **history** is h = (𝒜, Cap, E, W, R, the attributes of Defs 1.4–1.6, Ref, con, σ). Each of these is a **coordinate** of h. For a set X of coordinates, write h ≈_X h′ when h and h′ agree on every coordinate outside X.

### 1.8 Frame
**Def 1.8 (frame 𝓗).** 𝓗 is the set of histories that satisfy these three axioms:
- **F1 (entry into force, Def).** If a ∈ E^iss and f(a) = t ∈ T, then σ(t)(τ(a)) = v(a). This axiom is what "entry into force" means.
- **F2 (the record is the act, Asm).** If con(a) = ρ, then (ρ, a) ∈ Ref and d(a) = r(ρ). Performing the act by making the record fixes the decision at the recording time. F2 says nothing about entry into force.
- **F3 (typing, Def).** obj(b) ∈ E^iss for every b ∈ E^adp.

There is no other axiom. In particular, the frame does not require:
- that every act or work event is referenced by some record. This is required by H11 (non-occurrence may not be read from absence);
- that a norm value implies or excludes any act or work event (H10);
- any order between r, d and f, apart from F2 (H12, H04).

### 1.9 The three times
**Def 1.9.** The three times are:
- the **recording time** r(ρ) of a record;
- the **decision time** d(a) of an act;
- the **entry into force** f(a) of an issue act.

The frame never equates them. The only link is F2, which applies under con. con is not observed (Obs O6), so F2 never lets anyone read a decision time from a recording time. The work start time t(w) is an attribute of work events. It is not one of the three governance times. (G05, H04, A07.)

### 1.10 Derived notions
**Def 1.10.** Four derived notions:
- **Adoption.** For a ∈ E^iss and t ∈ T, Adopted(a, t) holds iff either
  - prep(a) = 0, (α(a), adp) ∈ Cap and d(a) ≤ t; or
  - there is b ∈ E^adp with obj(b) = a, (α(b), adp) ∈ Cap and d(b) ≤ t.
- **Entry-into-force data.** F̂(h) = {(a, τ(a), v(a), f(a)) : a ∈ E^iss, f(a) ∈ T}.
- **Change points.** Chg(h) = {(t, τ) : 1 ≤ t ≤ N, σ(t)(τ) ≠ σ(t−1)(τ)}.
- **Violation.** A work event w **violates** in h iff σ(t(w))(τ(w)) ∉ P. Otherwise it **conforms**.

### 1.11 The observation function
**Def 1.11.** The observation function is
> **O(h) = { (ρ, r(ρ), c(ρ)) : ρ ∈ R }**,

the set of record identities with their recording times and texts. For a class 𝓒 ⊆ 𝓗 and an observed value o, the **fibre** is 𝓒 ∩ O⁻¹(o).

More generally, an **observation** is any function O′ on 𝓗. O′ **does not depend on** a set X of coordinates iff h ≈_X h′ implies O′(h) = O′(h′).

The texts c(ρ) are observed **as texts**. A text that says "effective immediately" or "remains blocked" is a value of c, not a value of f or σ. Statements like this are self-assertions (A02, A05, A06).

**What O can and cannot see (Obs O1–O9).**

| | Coordinate | Seen by O? | Base |
|---|---|---|---|
| O1 | record identities, recording times r, verbatim texts c | **yes** (local capture only) | A01–A13, A15 (L0, per H01); A07; B06 "RECORDING_ORDER"; G01 |
| O2 | decision time d | **no, not in general** | A07 "Recording times (not decision times)"; F08 "the hour of decision of R-77 and R-79"; G05 |
| O3 | entry into force f (and so F̂) | **no** | G05 "is NOT an upper bound on entry into force"; H12 |
| O4 | norm state σ | **no**. No locally checkable channel qualifies as an independent norm-in-force record. Whether one exists anywhere is not determinable | B07 (F-B1); D04; F06; H14 |
| O5 | capability Cap | **no**. Host membership is access, not capability | G07; H19 |
| O6 | reference Ref and constitution con (whether a text refers to an act, a work event or a norm, and whether it *is* the act or *reports* it) | **no**. Not reliably codable on this corpus, even as a binary distinction. Within-family agreement elsewhere is not evidence against correlated error | IF (verdict FAIL); B09; F15; F16; G04 |
| O7 | keeper k | **no, not in general** | F14; A03 ("no Author line"); F02 |
| O8 | work start time t | **no**. Execution traces give recording order only. File names are not timestamps | B06; G05 (Protocol A L3) |
| O9 | acts and work events that no record references; completeness of the corpus | **no**. Absence is NOT-EVIDENCED-IN-CAPTURE | H11; F08; F11; F13; G01 |

**Lem 1.12.** O does not depend on σ, f, Ref, con, d, Cap, k, or on 𝒜, E, W and their attributes. *Proof:* O reads only R, r and c. ∎

---

## 2. State space and transitions

**Def 2.1 (state and labels).** The state at time t is s(t) = (σ(t), Ad(t)), where Ad(t) = {a ∈ E^iss : Adopted(a, t)}. The state space is S = V^𝒯 × 𝒫(E^iss). The step into t carries the label ℓ(t) = (Dec(t), For(t), Wk(t), Rc(t)):
- Dec(t) = {a ∈ E : d(a) = t};
- For(t) = {a ∈ E^iss : f(a) = t};
- Wk(t) = {w : t(w) = t};
- Rc(t) = {ρ : r(ρ) = t}.

**Def 2.2 (step rules).** These are the rules for each step into t (for t = 0, only the clauses about t itself).
- **Frame step.**
  - Ad(t) adds the issue acts adopted by the acts in Dec(t). Ad is monotone.
  - For each a ∈ For(t), σ(t)(τ(a)) = v(a) (F1).
  - Every other coordinate of σ(t) is unconstrained.
  - Records (Rc(t)) have no effect on the state. Constitution acts only through d (F2).
- **D-step.**
  - For(t) ⊆ Ad(t).
  - For each τ with (t, τ) ∈ Chg(h), there is a ∈ For(t) with τ(a) = τ, v(a) = σ(t)(τ) and (α(a), iss) ∈ Cap.
- **B-step.**
  - For each τ with (t, τ) ∈ Chg(h), either there is a ∈ For(t) with τ(a) = τ and v(a) = σ(t)(τ), or Wk(u) ≠ ∅ for some u < t.
  - For each w ∈ Wk(t), σ(t)(τ(w)) ∈ P.

**Lem 2.3.** Let h ∈ 𝓗.
- h ∈ H-D iff every step of h satisfies the D-step and h satisfies D-rec.
- h ∈ H-B iff every step satisfies the B-step and h satisfies B-rec.

(H-D, H-B, D-rec and B-rec are defined in §3.) *Proof:* This is the clause-by-clause rewriting of Def 3.1 per time step. ∎

---

## 3. H-D and H-B as model classes

### 3.1 Clauses (derived only from HYP)
**Def 3.1.** These are constraints on h ∈ 𝓗. Each one is followed by the HYP text it formalizes and the reading chosen.

| Clause | Constraint | HYP text; reading |
|---|---|---|
| **D-chg** | for every (t, τ) ∈ Chg(h) there is a ∈ E^iss with τ(a) = τ, f(a) = t, v(a) = σ(t)(τ) and (α(a), iss) ∈ Cap | "norms change **only** through such acts, by actors holding the capability" |
| **D-st** | for every a ∈ E^iss, f(a) < ∞ implies Adopted(a, f(a)) | "the force of a norm depends on the status of the act that made it (e.g. prepared vs adopted)". Read as: force requires adoption. The converse reading (adopted implies in force) is not adopted; see Lem 3.7 |
| (D-wrk) | none | "the governed work itself is outside what the record needs to contain". This allows; it does not constrain |
| **D-rec** | some (ρ, a) ∈ Ref has a ∈ E | "the record is primarily a record of norm-making". Weakest reading. The comparative "primarily" is not formalized, because it would require counting kinds of reference, which is coding-dependent (IF) |
| **B-chg** | for every (t, τ) ∈ Chg(h), **either** there is a ∈ E^iss with τ(a) = τ, f(a) = t and v(a) = σ(t)(τ) (no capability or status condition), **or** some w ∈ W has t(w) < t | "norms change through norm-making acts **and** in response to work". "In response to" is formalized only as temporal precedence. A response relation would be a new primitive that the base does not need |
| **B-conf** | every w ∈ W conforms | "work is expected to conform … (execution begins only when permitted)". Universal reading, as used in E01 ("refutes only a universal 'all recorded work conforms'") |
| (B-fb) | no constraint beyond B-chg's second disjunct | "work outcomes feed back into norm changes". Allowed, not required. The model has no outcome attribute |
| **B-recW** | some (ρ, w) ∈ Ref has w ∈ W | together with D-rec: "the record is expected to carry, or reference, both" |
| **B-rec** | D-rec ∧ B-recW | |

**Def 3.2.** The model classes are:
- **H-D** = {h ∈ 𝓗 : D-chg ∧ D-st ∧ D-rec};
- **H-B** = {h ∈ 𝓗 : B-chg ∧ B-conf ∧ B-rec};
- **R_B** = {h ∈ 𝓗 : B-rec}.

These are sets of histories. Nothing in R4 asserts that the actual history lies in either set (H07).

### 3.2 The exact relation
**Lem 3.3.** D-chg ⇒ B-chg, and B-rec ⇒ D-rec. Neither converse holds. *Proof:* The witness for D-chg is a witness for B-chg's first disjunct, and B-rec contains D-rec. For the converses, see Lem 3.5. ∎

**Prop 3.4 (near nesting).**
> H-D ∩ H-B = H-D ∩ B-conf ∩ B-recW = H-B ∩ D-chg ∩ D-st.

Hence:
- H-D ∖ H-B = H-D ∖ (B-conf ∩ B-recW);
- H-B ∖ H-D = H-B ∖ (D-chg ∩ D-st).

Each class is contained in the other once exactly two of the other's clauses are added:
- H-D ∩ B-conf ∩ B-recW ⊆ H-B;
- H-B ∩ D-chg ∩ D-st ⊆ H-D.

*Proof:* If h ∈ H-D satisfies B-conf and B-recW, then B-chg holds by Lem 3.3, and B-rec = D-rec ∧ B-recW. So h ∈ H-B. If h ∈ H-B satisfies D-chg and D-st, then D-rec holds by Lem 3.3. D-wrk adds nothing. So h ∈ H-D. The reverse inclusions hold because H-D ∩ H-B satisfies all the clauses. ∎

**Lem 3.5 (neither class contains the other).** Take T = {0, 1}, 𝒯 = {τ}, V = {p, q}, P = {p} and 𝒜 = {x}.
- **(a) A history in H-D ∖ H-B.** E = {a} with a an issue act: (τ, q), prep 1, f = ∞. W = {w} with t(w) = 0. R = {ρ} with Ref = {(ρ, a), (ρ, w)}. σ ≡ q. Then h ∈ H-D, and w violates.
- **(a′) Another history in H-D ∖ H-B.** The same, but with W = ∅ and Ref = {(ρ, a)}. Then h ∈ H-D, and B-recW fails.
- **(b) A history in H-B ∖ H-D.** As in (a), but σ(0) = p, σ(1) = q and t(w) = 0. The change at 1 is explained by earlier work, so B-chg holds. B-conf and B-rec hold. D-chg fails, because no act enters into force at 1.
- **(b′) Another history in H-B ∖ H-D.** As in (b), but with v(a) = q, f(a) = 1 and Cap = ∅. B-chg holds through the act. D-chg fails, because the act lacks capability.

∎

**Lem 3.6 (shared acceptance of acts; G06).** H-D ∪ H-B ⊆ {h : E ≠ ∅}. *Proof:* Both classes require D-rec. ∎

**Lem 3.7 (the biconditional status reading).** Let D-st⁺ be D-st together with: "if Adopted(a, t) holds for some t, then f(a) < ∞". Prop 4.1(i) below still holds with D-st⁺ in place of D-st, provided O also does not depend on Cap. *Proof:* In the proof of 4.1(i), additionally set Cap* = ∅. Then no act is adopted, so f* ≡ ∞ satisfies D-st⁺. ∎

---

## 4. D1 as a proposition

### 4.1 Assumptions
Each assumption is a property of the observation or of the frame. The base O and 𝓗 satisfy A1–A4 and A8 by Lem 1.12, and satisfy A5 and A7 by Def 1.8. They are listed separately because each proof uses only some of them, and §4.4 shows that each one is needed.

| | Assumption | Supported by |
|---|---|---|
| **A1** | O does not depend on σ | O4: B07, D04, F06 |
| **A2** | O does not depend on f | O3: G05, H12 |
| **A3** | O does not depend on Ref or con | O6: IF, B09, F15, F16 |
| **A4** | O does not depend on 𝒜, E, W or their attributes | O6, O8, O9 |
| **A5** | **Neutrality.** F1 is the only frame axiom that mentions σ or f. No axiom ties the observed coordinates (R, r, c) to σ or f. This means no corpus record is an attestation of the norm state | O4: B07 (no local channel qualifies); D04. This is not a claim that no attestation exists anywhere (H14) |
| **A6** | P ≠ V | parameter condition. Otherwise no work can violate |
| **A7** | **Openness.** The frame admits acts that no record references | H11; F08; F11 |
| **A8** | O does not depend on d | O2: A07, F08 |

### 4.2 Statement
**Prop 4.1 (D1).**
- **(i) Core, relative to O.** Assume A1, A2 and A5. For every h ∈ 𝓗 ∩ R_B there is h* ∈ H-D ∩ H-B with h* ≈_{σ, f} h. In particular O(h*) = O(h). Hence
  > O(H-D ∩ R_B) = O(H-B) = O(H-D ∩ H-B) = O(𝓗 ∩ R_B).
- **(i′) Record clauses.** Assume in addition A3 and A4. Then
  > O(H-D) = O(H-B) = O(H-D ∩ H-B) = {o ∈ O(𝓗) : o ≠ ∅}.
- **(ii) The contrast.** Assume A1, A2, A5 and A6. Let h ∈ 𝓗 ∩ R_B, and let w be a work event referenced in h. There are h_v and h_c, both ≈_{σ, f} h, such that:
  - h_v ∈ H-D ∖ H-B, σ_v is constant on T, and w **violates a norm that never changes**;
  - h_c ∈ H-D ∩ H-B, and every work event conforms.
- **(iii) H5's wording, local form.** Assume A1, A2, A4, A5 and A7. Let h ∈ H-D ∩ R_B, and let w violate in h. Then there is h′ ∈ H-B with O(h′) = O(h) such that:
  - h′ differs from h only in σ (and only at violation points of h), in f, and by additional issue acts that no record references;
  - w conforms in h′;
  - every change point of σ′ at which no act of h enters into force (in h′) with the new value is the entry into force of an added act.

In words: on O, "the work violated an unchanged norm" and "the norm state at t(w) was different, through facts that no record shows" are not separated.

**Lem 4.2 (robustness).** Prop 4.1(i) and (ii) hold for **every** observation O′ that does not depend on σ or f (given A5). This includes an O′ that also sees Ref, con, d, Cap, k, 𝒜, E, W and all their attributes. That is, they hold even if the reference and mode coding were perfectly reliable. *Proof:* The proofs below change only σ and f. ∎

**Cor 4.3 (D1 as a necessary condition; H5 §5.2).** Assume A5. If some observation O′ separates the classes on R_B, that is O′(H-D ∩ R_B) ≠ O′(H-B), then O′ depends on σ or on f. In other words it is an observable of the norm state or of entry into force. Any O′ computed only from the corpus records, their keepers and the acts and work they reference cannot separate the classes. *Proof:* This is the contrapositive of Lem 4.2. ∎

### 4.3 Proofs
***Proof of (i).*** Fix p ∈ P. Let h* be h with σ*(t)(τ) = p for all t and τ, and f*(a) = ∞ for all a ∈ E^iss.
- *Frame:* F1 holds vacuously. F2 and F3 do not mention σ or f. By A5 there is no other axiom. So h* ∈ 𝓗.
- *H-D:* Chg(h*) = ∅, so D-chg holds. No act enters into force, so D-st holds. D-rec is inherited from h ∈ R_B.
- *H-B:* B-chg holds vacuously. σ* ≡ p gives B-conf. B-rec is inherited.
- *Observation:* O(h*) = O(h) by A1 and A2.

For the equalities: H-B ⊆ R_B, and every o ∈ O(𝓗 ∩ R_B) is in O(H-D ∩ H-B). ∎

***Proof of (i′).*** Let h ∈ 𝓗 with R ≠ ∅, and pick ρ₀ ∈ R. Form h̃ by adding the following (and an actor, if 𝒜 = ∅):
- a fresh issue act a₀ with prep 1, f = ∞ and con(a₀) undefined;
- a fresh work event w₀;
- the references (ρ₀, a₀) and (ρ₀, w₀).

Then:
- F1–F3 hold, so h̃ ∈ 𝓗;
- h̃ ∈ R_B;
- O(h̃) = O(h), by A3 and A4.

Now apply (i). The empty corpus is excluded from both images, because D-rec needs a record. ∎

***Proof of (ii).*** Take h_c = h* from (i). For h_v, choose q ∈ V ∖ P (A6), and let h_v be h with σ ≡ q and f ≡ ∞. Then h_v ∈ 𝓗 by A5, and h_v ∈ H-D as in (i). The work event w exists by R_B. It violates, so h_v ∉ H-B. σ_v has no change points. O is unchanged by A1 and A2. ∎

***Proof of (iii).*** Let Vio = {(t(w), τ(w)) : w violates in h}. Build h′ as follows.
- **σ′.** σ′ = σ, except that σ′(t)(τ) = p on Vio.
- **f′.** f′(a) = ∞ if (f(a), τ(a)) ∈ Vio, and f′(a) = f(a) otherwise. By F1 in h, the acts set to ∞ are exactly those whose value is not in P at a violation point.
- **Added acts.** Consider each (t, τ) ∈ Chg(h′) with no a ∈ E^iss such that τ(a) = τ, f′(a) = t and v(a) = σ′(t)(τ). For each one, add a fresh issue act with norm (τ, σ′(t)(τ)), prep 1, d = f = t, any actor, no reference and con undefined. A7 allows this.

Check the result:
- *F1.* For the original acts that are still in force, (f(a), τ(a)) ∉ Vio, so σ′ = σ = v(a) there. For the added acts, F1 holds by construction.
- *F2 and F3* are unaffected.
- *B-chg* holds by construction.
- *B-conf.* Points in Vio now have value p. Every other work point keeps its value in P.
- *B-rec.* Ref is unchanged.
- *O(h′) = O(h)* by A1, A2 and A4.

∎

### 4.4 Countermodels (one per assumption)
Unless stated otherwise, the parameters are T = {0}, 𝒯 = {τ}, V = {p, q}, P = {p}, 𝒜 = {x}.

| Dropped | Variant | Instance | Failure |
|---|---|---|---|
| **A1** | O₁ = (O, σ) | E = {a} with a an issue act: (τ, q), prep 1, f = ∞. W = {w} with t(w) = 0. R = {ρ} with Ref = {(ρ, a), (ρ, w)}. σ(0)(τ) = q. Then h ∈ H-D ∩ R_B | Every h′ with O₁(h′) = O₁(h) has σ′(0)(τ) = q. B-rec needs a work event, and that event must be on τ at 0, so it violates. So O₁(h) ∉ O₁(H-B), and (i) fails |
| **A2** | O₂ = (O, F̂) | As for A1, but a has prep 0, d = 0, f = 0, and Cap = {(x, iss), (x, adp)}. Then h ∈ H-D (D-st holds) | F̂ = {(a, τ, q, 0)}. By F1, every h′ in the O₂-fibre has σ′(0)(τ) = q, so its work violates. (i) fails |
| **A5** | 𝓗₅ = 𝓗 plus the axiom "if c(ρ) = c★, then σ(t)(τ) = q for all t" | The A1 instance with c(ρ) = c★. O is unchanged | The whole fibre has σ ≡ q, so no member is in H-B. (i) fails |
| **A3** | O₃ = (O, Ref) | E = {a} (issue, prep 1, f = ∞). W = ∅. Ref = {(ρ, a)}. Then h ∈ H-D | Every h′ in the O₃-fibre fails B-recW. (i′) fails. (i) still holds on R_B, by Lem 4.2 |
| **A4** | O₄ = (O, W) | The A3 instance | O₄ shows W = ∅, so B-rec fails throughout the fibre. (i′) fails |
| **A6** | V = P = {p} | any | No work event can violate, so h_v does not exist. (ii) fails |
| **A7** (and A4 for (iii)) | 𝓗₇ = 𝓗 plus the axiom "every act and work event is referenced by some record" (or: O₄′ = (O, E with attributes)) | T = {0, 1}. E = {a} (issue, (τ, q), prep 1, f = ∞). W = {w} with t(w) = 1. R = {ρ} with Ref = {(ρ, a), (ρ, w)}. σ ≡ q. Cap = ∅ | h′ must have σ′(0) = q and σ′(1) = p, so there is a change at 1. B-chg's act disjunct would need an issue act entering into force at 1 with value p, but v(a) = q and no act may be added. Its work disjunct would need work before 1, but there is none. (iii) fails |

### 4.5 Sufficient conditions on an additional observable
**Def 4.4.** An additional observable is a function Q on 𝓗. The joint observation is O_Q = (O, Q). H-D and H-B are **distinguishable under O_Q** iff O_Q(H-D) ≠ O_Q(H-B). An outcome y **excludes H-B** iff y ∈ O_Q(H-D) ∖ O_Q(H-B). An outcome **excludes H-D** iff it lies in O_Q(H-B) ∖ O_Q(H-D).

**Prop 4.5 (sufficient for an outcome that excludes H-B).** Let Q(h) be a set of pairs (λ, v) with labels λ from a fixed set Λ. Suppose:
- **S1 (veridical and mapped).** For each h there is a partial map μ_h : Λ → W such that, for every (λ, v) ∈ Q(h), μ_h(λ) is defined and v = σ_h(t(μ_h(λ)))(τ(μ_h(λ))). So Q reports the norm state **for the target of a work event, at that event's start time**.
- **S2 (realizable).** Some h ∈ H-D has some (λ, v) ∈ Q(h) with v ∉ P.
- **S3 (non-circular).** S1 holds on **all of 𝓗**, as a frame axiom, not only on histories in H-D or only on histories in H-B.

Then O_Q(h) excludes H-B, so the classes are distinguishable.

*Proof:* Let h′ satisfy O_Q(h′) = O_Q(h). Then (λ, v) ∈ Q(h′). By S1 and S3, μ_{h′}(λ) is a work event that violates in h′, so h′ ∉ H-B. ∎

Such a Q only ever excludes H-B. It never excludes H-D, because the trivial construction of 4.1(i) keeps any Q-value equal to p. This matches E01's direction: "H-D makes no prediction".

**Prop 4.6 (sufficient for an outcome that excludes H-D; the symmetric case).** Fix τ and t₁ < t₂. Let Q′(h) = (σ(t₁)(τ), σ(t₂)(τ), X(h)), where X(h) = 1 iff there is a ∈ E^iss with τ(a) = τ, f(a) ∈ (t₁, t₂], (α(a), iss) ∈ Cap and Adopted(a, f(a)). Suppose:
- **S1′.** Q′ is veridical on all of 𝓗. In particular, X = 0 is a positive attestation of completeness, not an absence in capture (H11);
- **S2′.** Some h ∈ H-B has σ(t₁)(τ) ≠ σ(t₂)(τ) and X(h) = 0.

Then O_{Q′}(h) excludes H-D.

*Proof:* Some change point in (t₁, t₂] on τ has no capable, adopted act entering into force there, so D-chg ∧ D-st fails. ∎

Note that Q′ requires Cap, and host membership does not supply it (G07, H19).

**Lem 4.7 (the mapping in S1 is needed).** There is a parameter choice and a Q₀ that is veridical about σ at a fixed clock time but not mapped to any work event, under which O_{Q₀}(H-D ∩ R_B) = O_{Q₀}(H-B).

*Proof.* Take T = {0, 1}, 𝒯 = {τ}, V = {p, q}, P = {p}, 𝒜 = {x}, and Q₀(h) = σ(0)(τ). For each nonempty o and each v, build h as follows:
- σ = (v, p);
- a₁ is a referenced issue act: (τ, q), prep 1, f = ∞;
- if v = q, a₂ is an unreferenced issue act: (τ, p), prep 0, d = f = 1, with Cap = {(x, iss), (x, adp)};
- w is a referenced work event with t(w) = 1.

This h is in H-D ∩ H-B, so both images contain every (o, v). ∎

**Lem 4.8 (non-circularity is needed).** Consider an observable Q_e whose veridicality is guaranteed only on H-B. An example is the executor's own report "permitted when I began": Q_e ≡ p, which is veridical exactly where B-conf holds. Then O_{Q_e}(H-D ∩ R_B) = O_{Q_e}(H-B). *Proof:* Q_e is constant, and Prop 4.1(i) applies. ∎ The same argument works symmetrically for a report by the norm-maker whose veridicality is guaranteed only on H-D.

**Def 4.9 (independence, as in F06 and H5 §5.2).** A record ρ (inside or outside the corpus) is **independent for τ** in h iff all three hold:
- ρ constitutes no act on τ, that is, no a with con(a) = ρ and τ(a) = τ (or τ(obj(a)) = τ);
- k(ρ) ≠ α(a) for every act a on τ;
- k(ρ) ≠ α(w) for every work event w on τ.

Independence is the base's criterion for when S3 may be assumed without presupposing either hypothesis. It is **not derivable** inside the model: formally, S1–S3 are what suffice.

### 4.6 Relation of (e) to the H5 gaps (no observable is claimed to exist; H14)

| H5 gap (base id) | Bears on | Status in R4 |
|---|---|---|
| Host-side records; Protocol C sealed and not executed (F01) | candidate source of a Q | Undetermined. Its stated limits map onto the conditions. "Current state only": the reported state is the present one, not σ at t(w), so S1's mapping is not met as stated. "Events retention is an unverified assumption": S1 veridicality for past t is not established. "Q3 defaults to INDEPENDENCE_NOT_DETERMINABLE": S3 and Def 4.9 are undetermined. "Q2 needs an API relation to e4724a44 or its PR": this is exactly S1's map μ to a work event. The "< 10%" estimate is not used. Host membership cannot supply Cap for Prop 4.6 (G07, H19) |
| Signer verification declined; signer kind of the K-SIG headers undetermined (F02, B07) | the keeper condition of Def 4.9 | Undetermined |
| Content of status, register, CI and hook files not read; K-CI and K-HOOK history not read (F03) | candidate S1 sources | Whether S3 and Def 4.9 hold is undetermined. A file kept by the norm-maker would fail Def 4.9 |
| `IMPLEMENTATION_PROGRESS.md:58` not acquired; execution reports uncoded (F04; texts in A09, A11, A15) | O, not Q | As corpus-type records they enlarge O. By Cor 4.3 they cannot separate the classes unless treated as a Q satisfying S1–S3, and the base gives no ground for that. This agrees with A15: the S0815 reports "fit both hypotheses" |
| Self-contamination; corpora outside this git history (F05, G03) | the boundary of O; outside sources are candidate Qs | Undetermined |
| The missing fact (F06) | **S1 + S3 + Def 4.9 together**: a record by someone other than the norm-maker, the executor or the self-asserting ruling, attesting σ(t)(τ) at a stated t | NOT_DETERMINABLE (D04). Its motivating time, 2026-08-04T00:30:25Z, is a recording-order time (B06). So even S1's mapping would need t(w) to be fixed relative to recording time, which H12 and G05 do not allow to be read off |
| H5 §6 reopening conditions | condition 2 is S1–S3; condition 1 is a test of S1 and S3 on host data; condition 3 (a coding defect) | By Lem 4.2, a defect in the reference and mode coding cannot affect 4.1(i) or (ii). It could only affect (i′) |
| The waiver (D02) | none | 4.1 is a formal result relative to O. The waiver concerns whether channels were exhausted, which R4 does not address |

**Remark 4.10 (E01 in R4 terms; no new verdict).** E01's NON-CONFORMS verdict amounts to the value "σ at the WP-4B RED commit ∉ P". It was obtained from the ruling texts (A01, A02) and from a commit's *recording* time (A03). For it to be an outcome that excludes H-B under Prop 4.5, it would need:
- S1 and S3 for corpus ruling texts, which A5 excludes; and, if those records constitute the acts, the texts are self-assertions (§5);
- a mapping from the commit's recording time to t(w), and from the rulings' recording times to f, which H12 forbids reading off.

R4 therefore leaves E01 at its recorded bearing: weak, one-sided and confounded.

---

## 5. Performative ambiguity

**Def 5.1.** Let O′ be an observation and 𝓒 ⊆ 𝓗 a class. A pair (ρ, a) is **performatively ambiguous in 𝓒 at o** iff there are h₁, h₂ ∈ 𝓒 ∩ O′⁻¹(o) such that:
- (ρ, a) ∈ Ref in both h₁ and h₂;
- con_{h₁}(a) = ρ, so in h₁ the record *is* the act;
- con_{h₂}(a) ≠ ρ, so in h₂ the record *reports* a separate act.

A record is performatively ambiguous if some pair containing it is. This is a property of the fibre O′⁻¹(o), not of any one history.

**Lem 5.2.** Assume A3 and A8. Then every pair (ρ, a) ∈ Ref of every h ∈ 𝓒 is performatively ambiguous in 𝓒 at O(h):
- for 𝓒 = 𝓗 and 𝓒 = H-B;
- also for H-D, and for H-D ∩ H-B when h ∈ R_B, if A1, A2 and A5 are added.

*Proof.* For h₂, take h with con(a) set to undefined. F2 then no longer applies to a. For h₁, take h with con(a) = ρ and d(a) = r(ρ). F2 holds because (ρ, a) ∈ Ref. O is unchanged by A3 and A8. H-B mentions neither con nor d. For H-D, first pass to the trivial h* of 4.1(i). There D-st is vacuous, so changing d cannot break it. ∎

Countermodels:
- *If A3 is dropped* (O sees con), no pair is ambiguous.
- *If A8 is dropped* (O sees d), a pair with d(a) ≠ r(ρ) cannot be constituted by ρ within its fibre.

**Cor 5.3 (the R-A3 convention).** A coding rule that fixes the mode for a class of records, such as R-A3 "act and after-state are the same record" (G04), selects a subset of each O-fibre. Under Lem 5.2 that choice is a **convention, not an observation**.

**Prop 5.4 (relation to D1).**
- **(a) Removing the ambiguity does not remove D1.** Let O⁺ = (O, Ref, con, d). No pair is performatively ambiguous under O⁺. Yet 4.1(i) and (ii) hold for O⁺, by Lem 4.2. So, in this model, performative ambiguity is **not necessary** for non-identifiability.
- **(b) What removing it gives.** Under O⁺, d(a) = r(con(a)) is determined for every constituted act (F2). In H-D, every issue act a in force satisfies f(a) ≥ min{d(b)}, where b ranges over its effective adopters (including a itself when prep(a) = 0). If all of these are constituted, recording time becomes a **lower** bound on entry into force. This is consistent with G05, which says recording time is not an *upper* bound. H-B has no such bound: take an act constituted at r = 1 with prep 0 and f = 0. The bound concerns f, which is unobserved (A2), so no separation follows.
- **(c) What the ambiguity blocks.** If (ρ, a) is performatively ambiguous in 𝓒 at o and a is an act on τ, then "ρ is independent for τ" (Def 4.9) is **false in h₁**, so it is not constant on the fibre. Hence O cannot certify any such corpus record as independent, and the corpus cannot supply its own Q satisfying S3. Keeper unobservability (O7) is a **separate**, second obstacle to the same certification.

So, within R4's model, performative ambiguity does not *explain* the non-identifiability of H-D and H-B (point (ii) in IF). It explains a weaker fact: **why the corpus cannot certify one of its own records as the independent observable that Prop 4.5 needs.** Both performative ambiguity and D1 stem from what O does not see. They rest on different unseen coordinates: con and d for the ambiguity, σ and f for D1.

**Conj 5.5 (performative ambiguity and the coding failure). Stated as a conjecture, not established.**
> On the register and ruling records of this corpus, the text of a record does not determine whether the record constitutes the act it refers to. This is the empirical counterpart of A3 for con, and of Def 5.1. The failure of ACT_SELF_PERFORMED reported in IF is a consequence of this ambiguity, and not only of coder competence or of the wording of the coding scheme.

Consistent with the conjecture (IF, exploratory, one reviewer's judgement):
- the disagreements on ACT_SELF_PERFORMED between the two capable coders were all classed "schema admits both", and each "turns on whether a register/ruling row's words *perform* the ruling or *describe* it";
- the failure lies in register and ruling rows, not in the evidence-matrix rows.

**Expected consequences, if true:**
- disagreement concentrates on records whose text could be either the act or its report;
- records whose text cannot itself be a norm-making act show agreement;
- any rule that raises agreement does so by stipulating the mode (Cor 5.3). Two different admissible conventions would each be reproducible, yet disagree with each other.

**It would be falsified by any of the following.**
- **(F-a)** Coders who receive only the texts, with no added mode convention, reach the pre-registered reliability rule on ACT_SELF_PERFORMED for the register and ruling records. Cross-family or human coders are especially relevant here; the R2 decision is still pending (F15). This would show that the text does determine the mode.
- **(F-b)** A pre-registered independent review classifies the disagreeing cells as coder deviations ("schema admits one answer") rather than "schema admits both". This would point to competence, not ambiguity.
- **(F-c)** Disagreement is no lower on records whose text states its own mode than on other records. An example of such a text is a recording note marked as not part of the ruling (A10, R-56). This would show that the ambiguity explanation does no work.

Scope: IF is within-family, on these records, with no ground truth. Conj 5.5 says nothing about other corpora. R5 cannot test it.

---

## 6. Statement ledger

| ID | Statement (short) | Label | Base ids / inputs |
|---|---|---|---|
| Def 1.1 | parameters T, 𝒯, V, P, Σ | Def | A01, A10 (targets named in texts) |
| Def 1.2 | actors, constant Cap | Def | HYP (capability) |
| Def 1.3 | norm (τ, v); σ is a primitive | Def | HYP |
| Def 1.4 | issue and adoption acts; α, d, τ, v, prep, f, obj | Def | HYP; A05, A06, A08 |
| Def 1.5 | work event: τ, executor, t | Def | HYP |
| Def 1.6 | record: r, c, k; Ref; con | Def | G01, G04, IF |
| Def 1.7 | history, ≈_X | Def | none |
| F1 | entry into force sets σ | Def | none (meaning of f) |
| F2 | con(a) = ρ ⇒ Ref and d(a) = r(ρ) | Asm | IF ("the record of an act and the act itself coincide") |
| F3 | typing of obj | Def | none |
| Def 1.8 | frame = F1–F3 only; no completeness, no norm→work, no time order | Def | H10, H11, H12, H04 |
| Def 1.9 | the three times, never equated | Def | G05, H04, A07 |
| Def 1.10 | Adopted, F̂, Chg, violation | Def | HYP |
| Def 1.11 | O(h) = {(ρ, r, c)}; fibres; dependence | Def | O1–O9 |
| O1 | O sees record ids, r, verbatim texts (local capture) | Obs | A01–A13, A15, A07, B06, G01, H01 |
| O2 | decision times not observed in general | Obs | A07, F08, G05 |
| O3 | entry into force not observed; r is not an upper bound | Obs | G05, H12 |
| O4 | no independent norm-state channel locally; existence elsewhere not determinable | Obs | B07, D04, F06, H14 |
| O5 | capability not observed; membership is not capability | Obs | G07, H19 |
| O6 | reference and mode not reliably codable | Obs | IF, B09, F15, F16, G04 |
| O7 | keeper not observed in general | Obs | F14, A03, F02 |
| O8 | work start time not observed; recording order only | Obs | B06, G05 |
| O9 | completeness not shown; absence ≠ non-occurrence | Obs | H11, F08, F11, F13, G01 |
| Lem 1.12 | O does not depend on any hidden coordinate | Lem | none |
| Def 2.1 | state (σ, Ad), labels | Def | none |
| Def 2.2 | frame, D- and B-step rules | Def | HYP |
| Lem 2.3 | class membership = step rules + record clause | Lem | none |
| Def 3.1 | clauses D-chg, D-st, D-rec, B-chg, B-conf, B-recW, B-rec, with readings | Def | HYP; E01 (universal reading of conformance) |
| Def 3.2 | H-D, H-B, R_B as sets of histories | Def | HYP; H07 |
| Lem 3.3 | D-chg ⇒ B-chg; B-rec ⇒ D-rec | Lem | none |
| Prop 3.4 | H-D ∩ H-B = H-D ∩ B-conf ∩ B-recW = H-B ∩ D-chg ∩ D-st | Prop | none |
| Lem 3.5 | witnesses: neither class contains the other | Lem | none |
| Lem 3.6 | both classes need E ≠ ∅ | Lem | G06 |
| Lem 3.7 | 4.1(i) under the biconditional status reading, given Cap unobserved | Lem | G07 |
| A1–A8 | assumptions of §4.1 | Asm | as in the §4.1 table |
| Prop 4.1(i) | D1 core: every R_B observation is produced inside H-D ∩ H-B | Prop | (via A1, A2, A5) B07, D04, G05, H12 |
| Prop 4.1(i′) | the images are equal without restriction | Prop | (via A3, A4) IF, H11 |
| Prop 4.1(ii) | violation of an unchanged norm and conformance in the same fibre | Prop | as (i) |
| Prop 4.1(iii) | H5's local wording, through unreferenced acts | Prop | (via A7) H11, F08, F11 |
| Lem 4.2 | robust to perfectly reliable reference and mode coding | Lem | none |
| Cor 4.3 | separation needs an observable of σ or f | Cor | H5 §5.2 |
| CM-A1 … CM-A7 | one countermodel per assumption | Lem | none |
| Def 4.4 | additional observable, distinguishability, exclusion | Def | none |
| Prop 4.5 | S1–S3 suffice for an outcome that excludes H-B | Prop | F06; H5 §5.2 |
| Prop 4.6 | S1′–S2′ suffice for an outcome that excludes H-D | Prop | H11, G07, H19 |
| Lem 4.7 | an unmapped Q does not suffice | Lem | none |
| Lem 4.8 | a Q veridical only within one class does not suffice | Lem | none |
| Def 4.9 | independence of a record | Def | F06, H5 §5.2 |
| Table 4.6 | relation to the H5 gaps; nothing claimed to exist | Obs | F01–F06, B07, D02, D04, G03, A09, A11, A15, H14 |
| Rem 4.10 | E01 needs S1–S3 premises that R4 does not grant; no new verdict | Lem (remark) | E01, A01, A02, A03, H12 |
| Def 5.1 | performative ambiguity relative to (O′, 𝓒) | Def | IF |
| Lem 5.2 | every referenced pair is ambiguous under A3 and A8 | Lem | O6, O2 |
| Cor 5.3 | R-A3 is a convention, not an observation | Cor | G04 |
| Prop 5.4 | (a) not necessary for D1; (b) gives a lower bound on f under H-D; (c) blocks corpus self-certification | Prop | G05, O7 |
| Conj 5.5 | the ambiguity explains the ACT_SELF_PERFORMED failure; falsifiers F-a to F-c | Conj | IF; F15; A10 |
| IF-obs | binary instrument FAIL; ACT_SELF_PERFORMED disagreement anatomy (exploratory) | Obs | IF |
| §8 rows | compliance with section H | Lem | H01, H04, H07, H10, H11, H12, H14, H18, H19 |

---

## 7. R5 specification (finite-state model; exhaustive enumeration; no code here)

### 7.1 Universe U
**Parameters.**
- T = {0, 1};
- 𝒯 = {τ};
- V = {p, q} with P = {p};
- Σ = {c1, c2};
- 𝒜 = {x};
- act slots {a1, a2}, a work slot w1, and one record ρ1, which is always present;
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

**Frame.** Keep exactly the assignments that satisfy F1, F2 and F3 (Def 1.8). This gives a few million assignments at most.

**Classes.** Implement the clauses of Def 3.1 literally, and H-D, H-B and R_B as in Def 3.2. "Differs at most in X" means equal on every enumerated coordinate outside X.

**Observations.**

| Name | Definition |
|---|---|
| O | (r(ρ1), c(ρ1)) |
| O₁ | (O, σ) |
| O₂ | (O, F̂), with F̂ = {(s, v(s), f(s)) : s a present iss act, f(s) ≠ ∞} |
| O₃ | (O, Ref) |
| O₄ | (O, presence of w1) |
| O₄′ | (O, presence and all attributes of a1 and a2) |
| O⁺ | (O, Ref, con, d) |
| O_¬σf | every coordinate except σ and f |

**Additional observables.**

| Name | Definition |
|---|---|
| Q1 | σ(t(w1)) if w1 is present, else ⊥ |
| Q0 | σ(0) |
| Qe | the constant p |
| Qc | [c(ρ1) = c2] |
| Q2 | (σ, F̂, Cap, and the kind, prep, obj and d of each present act) |

**Frame variants.**

| Name | Change |
|---|---|
| U₅ | add the axiom "c(ρ1) = c2 ⇒ σ(0) = σ(1) = q" |
| U₆ | V = P = {p} |
| U₇ | add the axiom "every present act and w1 is in Ref" |

**Instance I7** (the CM-A7 instance in U):
- σ = (q, q);
- a1 is iss with v = q, prep 1, f = ∞, d = 0, referenced, con none;
- a2 is absent;
- w1 is present with t = 1 and is referenced;
- Cap = ∅;
- r(ρ1) and c(ρ1) are arbitrary.

### 7.2 Checks and expected outcomes
For existential checks, report a witness. For universal checks, report the first counterexample if there is one. Model-size counts may be reported, but no count is predicted. **Any deviation from an expected outcome is a finding against R4.**

| # | Check | Expected |
|---|---|---|
| K1 | The following are nonempty: H-D, H-B, H-D ∩ H-B, H-D ∖ H-B, H-B ∖ H-D. Also, every history in H-D ∪ H-B has a present act (Lem 3.6) | all true |
| K2 | For all h: D-chg ⇒ B-chg and B-rec ⇒ D-rec. Witnesses against both converses exist | true; witnesses exist |
| K3 | H-D ∩ H-B = H-D ∩ B-conf ∩ B-recW = H-B ∩ D-chg ∩ D-st (Prop 3.4) | true |
| K4 | (i) For every h ∈ R_B there is h* ∈ H-D ∩ H-B differing at most in σ and f. Run this for O, and as the statement "O_¬σf(h*) = O_¬σf(h)" (Lem 4.2) | true |
| K4b | Lem 3.7: K4 with D-st replaced by D-st⁺, allowing Cap to differ as well | true |
| K5 | (i′) O(H-D) = O(H-B) = O(H-D ∩ H-B) = all four values of (r, c) | true |
| K6 | (ii) For every h ∈ R_B with w1 referenced there are h_v ∈ H-D ∖ H-B with σ_v constant and w1 violating, and h_c ∈ H-D ∩ H-B. Both differ from h at most in σ and f | true |
| K7 | (iii) Take every h ∈ H-D ∩ R_B with a2 absent and w1 violating. There is h′ ∈ H-B with the same O such that: h′ differs at most in σ (only at t(w1)), in f, and by a2 present as an unreferenced iss act with con none; w1 conforms; and every change point of σ′ at which no act of h enters into force (under f′) with the new value is f(a2) | true |
| K8.1 | ¬A1: O₁(H-B) ⊊ O₁(H-D ∩ R_B). The difference is exactly the values with σ = (q, q), for every (r, c) | true, exactly as stated |
| K8.2 | ¬A2: the value with F̂ = {(a1, q, 0)} lies in O₂(H-D ∩ R_B) ∖ O₂(H-B) | true |
| K8.3 | ¬A3: a value of O₃ with Ref containing no (ρ1, w1) lies in O₃(H-D) ∖ O₃(H-B). (i) still holds for O₃ on R_B | true; true |
| K8.4 | ¬A4: the value "w1 absent" lies in O₄(H-D) ∖ O₄(H-B). Under O₄′, (iii) fails at I7 | true; fails |
| K8.5 | ¬A5 in U₅: (r, c2) ∈ O(H-D ∩ R_B) ∖ O(H-B) for both values of r | true |
| K8.6 | ¬A6 in U₆: no h_v exists, so (ii) fails | (ii) fails |
| K8.7 | ¬A7 in U₇: (iii) fails at I7. In U, (iii) holds at I7 | fails in U₇; holds in U |
| K9.1 | Q1: (o, q) ∈ O_Q1(H-D) ∖ O_Q1(H-B) for every o, and O_Q1(H-B) ⊆ O_Q1(H-D) | true; true |
| K9.2 | Q0 (unmapped): O_Q0(H-D ∩ R_B) = O_Q0(H-B) = every (o, v) | true |
| K9.3 | Qe and Qc: O_Q(H-D ∩ R_B) = O_Q(H-B) | true |
| K9.4 | Q2: both O_Q2(H-B) ∖ O_Q2(H-D) and O_Q2(H-D ∩ R_B) ∖ O_Q2(H-B) are nonempty. Witnesses: σ = (p, q) with F̂ = ∅, and σ = (q, q) | true |
| K10.1 | Lem 5.2: for every h ∈ 𝓒 and every present act s with (ρ1, s) ∈ Ref there are h₁ and h₂ in 𝓒 ∩ O⁻¹(O(h)) with (ρ1, s) ∈ Ref, con(s) = ρ1 in h₁ and con(s) = none in h₂. Run for 𝓒 ∈ {U, H-B, H-D}, and for H-D ∩ H-B when h ∈ R_B | true |
| K10.2 | Under O⁺ no pair is ambiguous. K4 holds for O⁺ | true; true |
| K10.3 | In O⁺-fibres, every constituted act has d = r(ρ1). In O-fibres, d is not constant for referenced acts | true; true |
| K10.4 | In H-D, every present iss s with f(s) ≠ ∞, prep 0, con(s) = ρ1 and no adp act on s satisfies f(s) ≥ r(ρ1). Some h ∈ H-B violates this | true; exists |
| K10.5 | ¬A8: under (O, d) there is a referenced pair that is not ambiguous | true |
| K11.1 | U is closed under removing w1 (with its Ref entry) and under adding w1 at any t without a reference. That is, there is no norm→work and no absence→non-occurrence constraint (H10, H11) | true |
| K11.2 | In U there are referenced, forced acts with f > r(ρ1), and others with f < r(ρ1). That is, there is no bound from r in the frame (H12) | true |

Conj 5.5 is empirical and is **not** a check for R5.

---

## 8. Compliance with section H

| Item | How R4 complies |
|---|---|
| H01 (may) | Only L0 items (A01–A13, A15, G04, G06) are used as text data. L1 items are cited for scope and limits only. A16 is not used |
| H04 (may) | Three distinct time functions. The only link between them is F2, under the unobserved con |
| H07 | H-D and H-B are defined as subsets of 𝓗 and are never assumed (Def 3.2) |
| H10 | The frame has no norm→act or norm→work axiom (K11.1). B-conf is a clause of a hypothesis, never asserted of the corpus |
| H11 | The frame does not require completeness (A7, O9). Prop 4.6 requires a *positive* attestation |
| H12 | No decision order and no entry into force is read from r (O2, O3, K11.2). Prop 5.4(b) is conditional on con and is a lower bound under H-D only |
| H14 | §4.5 and §4.6 state sufficient conditions only. No observable is claimed to exist or not to exist |
| H18 | No support is computed or pooled anywhere |
| H19 | Cap is unobserved. Host membership is never read as Cap (O5; Prop 4.6) |
| Prohibitions | No act or norm counts. No coding-dependent premise: Lem 4.2 shows that 4.1(i) and (ii) do not rest on any coding |

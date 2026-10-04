# V1a: reading robustness of D1 (formal)

| | |
|---|---|
| Inputs (only) | `FORMAL-THEORY-R4.md` (R4, cited by statement id); `HYPOTHESES.md` (HYP, quoted); `R6-VALIDATION-MATRIX.md` (rows V1a, V1b) |
| Status | Draft by one same-family (Claude) analyst, not reviewed. Proofs are meant to be checked by hand. No command was run. R4 is **not** changed. Where a reading would need a change to R4, this is said explicitly and nothing is revised |
| Companion | `V1B-PREDICTIONS.md`: sealed predictions and planted controls for the non-M readings. Not needed to check anything here |

**Labels.** Every statement carries one label: **Def** (definition), **Lem** (lemma, proved), **Prop** (proposition, proved), **Cor** (corollary, proved), **Conj** (conjecture, not established), **Rem** (interpretive remark; it asserts no formal claim beyond those it cites). Statement ids are V-prefixed so that they cannot be confused with R4 ids. Lemma numbers in §3 follow the order of Table V3.0's ids, not the section order. §6 lists every statement.

**Notation.** As in R4. 𝓗′ is a frame and O′ an observation on it (R4 Def 1.8, Def 1.11). "dyn clause" means a non-record clause (one about change, status or conformance). "Record clause" means D-rec, B-recW, B-rec or a reading of one of them.

---

## 1. Readings

**Def V1.1 (reading; reading classes; record base).** A **reading** 𝐑 gives four finite sets of predicates on 𝓗:
- 𝔇_dyn^𝐑, the D-side dyn clauses, and 𝔇_rec^𝐑, the D-side record clauses;
- 𝔅_dyn^𝐑, the B-side dyn clauses, and 𝔅_rec^𝐑, the B-side record clauses.

For a frame 𝓗′:
- H-D^𝐑′ = {h ∈ 𝓗′ : h satisfies every clause in 𝔇_dyn^𝐑 ∪ 𝔇_rec^𝐑};
- H-B^𝐑′ = {h ∈ 𝓗′ : h satisfies every clause in 𝔅_dyn^𝐑 ∪ 𝔅_rec^𝐑};
- the **record base** R_B^𝐑′ = {h ∈ 𝓗′ : h satisfies every clause in 𝔅_rec^𝐑}.

This copies R4 Def 3.2, where R_B′ is defined by H-B's record clause B-rec. So H-B^𝐑′ ⊆ R_B^𝐑′ always.

**Def V1.2 (R4's reading 𝐑₀).** 𝔇_dyn = {D-chg, D-st}, 𝔇_rec = {D-rec}, 𝔅_dyn = {B-chg, B-conf}, 𝔅_rec = {B-rec}, with the clauses of R4 Def 3.1. Then H-D^𝐑₀′ = H-D′, H-B^𝐑₀′ = H-B′ and R_B^𝐑₀′ = R_B′.

**Def V1.3 (the readings).** Each reading below replaces, drops or adds clauses of 𝐑₀ as stated. Everything not mentioned is as in 𝐑₀. The HYP phrase each reading is derived from is quoted.

| Id | Change to 𝐑₀ | HYP phrase; derivation |
|---|---|---|
| **R-a** | B-chg → **B-chg^a**: for every (t, τ) ∈ Chg(h), either some a ∈ E^iss has τ(a) = τ, f(a) = t and v(a) = σ(t)(τ), or some w ∈ W has **τ(w) = τ** and t(w) < t | "norms change through norm-making acts **and** in response to work (execution evidence, completion, failure)". A change *in response to work* on the norm's own target: the evidence, completion or failure is evidence about the work the norm governs. R4 names this variant and does not adopt it (Rem 3.1b) |
| **R-b** | B-conf → **B-conf^b**: some w ∈ W conforms | "work is expected to **conform** to the norms in force". "Is expected to" is read as a tendency of the system, not as a property of every work event. R4 says the universal reading comes from E01, not from HYP (Rem 3.1a). The existential reading is the weakest non-vacuous one |
| **R-c** | R-b, and B-rec → **B-rec^c** := D-rec (B-recW dropped) | R-b's phrase, together with "the record is **expected to** carry, or reference, both". The same "expected to" is read as defeasible, so it constrains no single history. What remains of the record clause is the shared "both hypotheses accept that norm-making acts exist", which D-rec already formalizes |
| **R-d** | D-st → **D-st⁺** (R4 Lem 3.7): D-st, and if Adopted(a, t) holds for some t, then f(a) < ∞ | "the force of a norm **depends on** the status of the act that made it (e.g. prepared vs adopted)". "Depends on" is read as "is determined by": an adopted act is (at some time) in force, as well as conversely. R4 names this reading and treats it only in Lem 3.7 |
| **R-e** | D-chg → **D-chg^e**: D-chg without "(α(a), iss) ∈ Cap" | "norms change only through such acts, **by actors holding the capability to perform them**". The restrictive word is "only", which attaches to "through such acts". The capability phrase is read as describing the actors of H-D, not as a further condition checked at each change point |
| **R-f** | B-rec → **B-rec^f** := D-rec (B-recW dropped); B-conf stays universal | "the record is **expected to** carry, or reference, both", read as in R-c |
| **R-g** *(added)* | B-chg → **B-chg^g** := B-chg ∧ "there are (t, τ) ∈ Chg(h) and w ∈ W with t(w) < t" | "**norms change** through norm-making acts **and** in response to work". In the H-B list this sentence is a description of the system ("On H-B: norms change …"), parallel to H-D's "norms change only through such acts", which R4 reads as a constraint. Read as a description, the "and" says both mechanisms operate, so at least one change follows work. Temporal precedence is kept as R4's formalization of "in response to" (Def 3.1). R4's B-chg is the permissive reading ("may change in response to work") |
| **R-h** *(added)* | B-rec → **B-rec^h** := D-rec ∧ B-recW ∧ "every a ∈ E and every w ∈ W is referenced by some record" | "the record is expected to **carry, or reference, both**". Read universally: the record carries or references the norm-making acts and the work, not just one item of each. This is the reading-level analogue of R4's frame variant 𝓗₇ (CM-A7) |

**Rem V1.4 (why only two further readings).** TASK allows at most three, and only if HYP clearly licenses them. R-g and R-h each turn a HYP sentence that R4 reads permissively or existentially into its plain descriptive or universal sense, with no new primitive. The following candidates were considered and **not** added:
- *B-fb as a requirement* ("work **outcomes feed back** into norm changes"). It needs an outcome attribute, which R4 deliberately does not model (Rem 0.1, Def 3.1 B-fb). **This reading would require revising R4** (a new primitive). It is not formalized here.
- *"In response to" as a causal relation.* R4 Def 3.1 says this "would be a new primitive". **It would require revising R4.** Not formalized.
- *"primarily" as a comparative* (D-rec). It needs counting kinds of reference, which is coding-dependent (R4 Def 3.1, Rem 3.1c). Excluded by the no-coding rule.
- *D-wrk as a prohibition* ("the governed work itself is outside what the record **needs** to contain"). "Needs to contain" sets no ceiling, so a reading "the record references no work" is not licensed.
- *D-st with adoption at any time* (in force ⇒ adopted at some time). Licensed, but weaker than D-st and vacuous where D-st is. It has M by Cor V2.9 and adds nothing.

---

## 2. Lemma M

### 2.1 R4's construction, checked against R4's proof

**Rem V2.0 (what the construction is).** R4's proof of Prop 4.1(i) (§4.3) fixes p ∈ P and sets h* := h[σ* ≡ p, f* ≡ ∞]. Lem 4.0b (from A1, A2, A5) gives h* ∈ 𝓗′ and O′(h*) = O′(h). The proof then checks each clause of 𝐑₀:
- D-chg, because Chg(h*) = ∅;
- D-st, because no act enters into force;
- D-rec, inherited from h ∈ R_B′ (Ref and E are unchanged; B-rec contains D-rec);
- B-chg, vacuously (Chg(h*) = ∅);
- B-conf, because σ* ≡ p ∈ P;
- B-rec, inherited.

Only σ and f change. 𝒜, Cap, E, W, all attributes other than f, Ref and con are unchanged, so h* ≈_{σ, f} h. The proof of (ii) uses the same construction twice, with p (for h_c) and with q ∈ V ∖ P (for h_v). The proof of (iii) uses a **different** construction (σ changed only at violation points, plus added prepared, unreferenced acts). Lemma M treats (iii) separately for this reason.

**Def V2.1 (quiescent histories).** For v ∈ V, a history g ∈ 𝓗 is **v-quiescent** iff σ_g(t)(τ) = v for every t ∈ T and τ ∈ 𝒯, and f_g(a) = ∞ for every a ∈ E^iss_g. For every h ∈ 𝓗, h[σ ≡ v, f ≡ ∞] is v-quiescent, and it is in 𝓗 because F1 holds vacuously and F2 mentions neither σ nor f.

**Def V2.2 (record conditions).** A reading 𝐑 satisfies:
- **(RB1) blindness** iff every clause in 𝔇_rec^𝐑 ∪ 𝔅_rec^𝐑 does not depend on {σ, f}: for g, g′ ∈ 𝓗 with g ≈_{σ, f} g′, g satisfies the clause iff g′ does;
- **(RB2) dominance** iff every g ∈ 𝓗 that satisfies 𝔅_rec^𝐑 also satisfies 𝔇_rec^𝐑.

**Def V2.3 (property M and its variants).** Let p ∈ P and q ∈ V ∖ P. A reading 𝐑 has:
- **M_p** iff every clause in 𝔇_dyn^𝐑 ∪ 𝔅_dyn^𝐑 holds at every p-quiescent g ∈ 𝓗 that satisfies 𝔅_rec^𝐑;
- **M_p^W** iff the same holds for every such g with W_g ≠ ∅ (so M_p implies M_p^W);
- **M_q^D** iff every clause in 𝔇_dyn^𝐑 holds at every q-quiescent g ∈ 𝓗 that satisfies 𝔅_rec^𝐑;
- **N_q** iff every q-quiescent g ∈ 𝓗 that satisfies 𝔅_rec^𝐑 and has W_g ≠ ∅ fails some clause in 𝔅_dyn^𝐑.

𝐑 **has M** iff it satisfies RB1, RB2 and M_p for some p ∈ P. A single clause reading (one row of Def V1.3) has M iff the reading it produces from 𝐑₀ has M.

M is the precise form of the matrix's "universal or vacuous on constant σ with no forced acts": the dyn clauses hold on the quiescent version of every member of the record base, whichever record base the reading uses.

**Def V2.4 (property T, for (iii)).** Call g ∈ 𝓗 **act-explained** iff for every (t, τ) ∈ Chg(g) some a ∈ E^iss_g has τ(a) = τ, f(a) = t and v(a) = σ_g(t)(τ). A reading 𝐑 has **T** iff:
- **(T1)** every clause in 𝔅_dyn^𝐑 holds at every g ∈ 𝓗 that is act-explained, has W_g ≠ ∅ and in which every work event conforms;
- **(T2)** every clause in 𝔅_rec^𝐑 does not depend on {σ, f} (as in RB1) and is preserved by every (+act) fresh addition (R4 Def 1.7b).

**Lem V2.5 (R4's reading has every property).** 𝐑₀ satisfies RB1, RB2, M_p for every p ∈ P, M_q^D and N_q for every q ∈ V ∖ P, and T.

*Proof.*
- RB1: D-rec and B-rec read only Ref, E and W.
- RB2: B-rec = D-rec ∧ B-recW.
- M_p: on a p-quiescent g, Chg(g) = ∅ gives D-chg and B-chg; f ≡ ∞ gives D-st; σ ≡ p ∈ P gives B-conf.
- M_q^D: D-chg and D-st hold on every quiescent history, for the same reasons.
- N_q: a q-quiescent g with W ≠ ∅ has a work event at which σ = q ∉ P, so B-conf fails.
- T1: act-explained gives B-chg's act disjunct at every change point; all work conforming gives B-conf.
- T2: B-rec reads only Ref, E and W, and each conjunct is existential, so it survives adding an item. ∎

These are exactly the clause checks of R4's proofs of (i), (ii) and (iii) (Rem V2.0; R4 §4.3, "Check that h′ ∈ H-B′").

### 2.2 The lemma

**Lem V2.6 (Lemma M: reading monotonicity).** Let (O′, 𝓗′) be a pair and 𝐑 a reading.

- **(i)** Assume A1, A2, A5, and that 𝐑 has M (RB1, RB2, M_p). Then for every h ∈ R_B^𝐑′ the history h* := h[σ ≡ p, f ≡ ∞] satisfies h* ∈ H-D^𝐑′ ∩ H-B^𝐑′, h* ≈_{σ, f} h and O′(h*) = O′(h). Hence
  > O′(H-D^𝐑′ ∩ R_B^𝐑′) = O′(H-B^𝐑′) = O′(H-D^𝐑′ ∩ H-B^𝐑′) = O′(R_B^𝐑′).
- **(ii)** Assume A1, A2, A5, A6, RB1, RB2, and for some p ∈ P and q ∈ V ∖ P:
  - **(ii-c)** M_p^W. Then for every h ∈ R_B^𝐑′ with W_h ≠ ∅, h_c := h[σ ≡ p, f ≡ ∞] ∈ H-D^𝐑′ ∩ H-B^𝐑′, h_c ≈_{σ, f} h, O′(h_c) = O′(h), and every work event conforms in h_c;
  - **(ii-v)** M_q^D and N_q. Then for every h ∈ R_B^𝐑′ and every w ∈ W_h, h_v := h[σ ≡ q, f ≡ ∞] ∈ H-D^𝐑′ ∖ H-B^𝐑′, h_v ≈_{σ, f} h, O′(h_v) = O′(h), σ_v is constant, and w violates.

  So Prop 4.1(ii) holds for 𝐑 if both parts hold. If only one holds, exactly the corresponding half (h_c or h_v) of 4.1(ii) survives with R4's proof.
- **(iii)** Assume A1, A2, A4, A5, A7, and that 𝐑 has T. Let h ∈ R_B^𝐑′ (**no D-side clause is needed**), and let w violate in h. Then R4's history h′ (proof of Prop 4.1(iii)) is in H-B^𝐑′, O′(h′) = O′(h), and the three bullets of Prop 4.1(iii) hold. In particular Prop 4.1(iii) holds for 𝐑 with **any** D-side reading, because the D-side clauses only restrict the class from which h is taken.

*Proof of (i).* Fix h ∈ R_B^𝐑′. σ* ≡ p and f* ≡ ∞ satisfy F1 vacuously, so h* ∈ 𝓗′ and O′(h*) = O′(h) by Lem 4.0b (A1, A2, A5). h* ≈_{σ, f} h by construction (Rem V2.0). h satisfies 𝔅_rec^𝐑, so h* does too by RB1, and h* satisfies 𝔇_rec^𝐑 by RB2. So h* ∈ R_B^𝐑′, and h* is p-quiescent. By M_p, h* satisfies every dyn clause. So h* ∈ H-D^𝐑′ ∩ H-B^𝐑′. For the equalities: H-D^𝐑′ ∩ H-B^𝐑′ ⊆ H-D^𝐑′ ∩ R_B^𝐑′ ⊆ R_B^𝐑′ and H-D^𝐑′ ∩ H-B^𝐑′ ⊆ H-B^𝐑′ ⊆ R_B^𝐑′, because H-B^𝐑′ ⊆ R_B^𝐑′ (Def V1.1). The construction gives O′(R_B^𝐑′) ⊆ O′(H-D^𝐑′ ∩ H-B^𝐑′). ∎

This is R4's proof of (i) word for word, with its six clause checks replaced by RB1, RB2 and M_p.

*Proof of (ii).* (ii-c): as in (i), but M_p^W suffices because W_{h_c} = W_h ≠ ∅. Every work event conforms because σ_c ≡ p ∈ P. (ii-v): q exists by A6, and h_v ∈ 𝓗′ with O′(h_v) = O′(h) by Lem 4.0b. Record clauses hold by RB1 and RB2, so h_v satisfies 𝔅_rec^𝐑 and is q-quiescent. By M_q^D, h_v ∈ H-D^𝐑′. W_{h_v} = W_h ∋ w, so h_v fails some B-side dyn clause by N_q, and h_v ∉ H-B^𝐑′. σ_v ≡ q is constant, and w violates because q ∉ P. ∎ (R4's proof of (ii) only uses that w ∈ W; that w is referenced is not used.)

*Proof of (iii).* Run R4's proof of Prop 4.1(iii) with one justification replaced. R4 obtains 𝒜 ≠ ∅ from B-recW. Instead: w ∈ W_h, so α(w) ∈ 𝒜. (This substitution is valid in R4 too, so R4's proof is not changed in substance.) Steps 1 and 2 use only F1, A1, A2, A4, A5 and A7, and never a clause of 𝐑. They give h′ ∈ 𝓗′ with O′(h′) = O′(h). R4's checks of B-chg and B-conf show that h′ is act-explained and that every work event conforms in it, and W_{h′} = W_h ∋ w. So h′ satisfies 𝔅_dyn^𝐑 by T1. h′ is obtained from h by a σ, f replacement (step 1) followed by (+act) additions (step 2). By T2 the record clauses survive both, so h′ satisfies 𝔅_rec^𝐑. So h′ ∈ H-B^𝐑′. R4's verification of the three bullets reads no clause of 𝐑. ∎

**Rem V2.7 (what changes when a reading alters R_B itself).** A reading that changes 𝔅_rec (R-c, R-f, R-h) changes R_B^𝐑 in four places at once:
1. **The domain of (i) and (ii).** (i) quantifies over every h ∈ R_B^𝐑′. Dropping B-recW (R-c, R-f) **enlarges** R_B to the D-rec class, which contains histories with W = ∅. M must then hold on the quiescent versions of these new members. This is where R-c fails and R-f does not (§3). Strengthening the record clause (R-h) shrinks the domain, which only weakens (i) and (ii).
2. **The conclusion of (i).** Under R-c and R-f, H-D ⊆ D-rec = R_B^𝐑, so H-D^𝐑′ ∩ R_B^𝐑′ = H-D^𝐑′. (i) then reads O′(H-D′) = O′(H-B^𝐑′) = …, which is the unrestricted form that R4 obtains for 𝐑₀ only in (i′), with A3, A4 and A7.
3. **RB1 and RB2** must be re-checked for the new record clauses. They hold for all readings here (§3).
4. **(iii)** needs the record clause to survive (+act) additions (T2). The existential clauses D-rec and B-recW do. The universal clause of R-h does not.

R4's Prop 3.4 (near nesting) also changes under R-c and R-f (for R-f, H-D ∩ H-B^f = H-D ∩ B-conf). Prop 3.4 is not part of D1, so this is noted and not developed.

**Cor V2.8 (robustness transfers with M).** If 𝐑 has M, then (i) holds for O_¬σf on 𝓗 (the analogue of R4 Lem 4.2), and on 𝓗 no observation that is a function of coordinates other than σ and f separates H-D^𝐑 ∩ R_B^𝐑 from H-B^𝐑 (the analogue of Cor 4.3). *Proof:* O_¬σf satisfies A1 and A2 on 𝓗, and 𝓗 satisfies A5 (R4 Lem 4.2, Lem 4.0a). Apply Lem V2.6(i). An observation that reads no σ or f satisfies A1 and A2. ∎

**Cor V2.9 (monotonicity).** Hold the record clauses of 𝐑₀ fixed. Then:
- (a) replacing any dyn clause by a **weaker** one (implied by it on 𝓗) preserves M_p, M_p^W and T1. Replacing a D-side dyn clause by a weaker one also preserves M_q^D. N_q is preserved if the B-side dyn clauses still fail on q-quiescent histories with work;
- (b) replacing a dyn clause by a **stronger** one preserves M_p if the stronger clause still holds on p-quiescent members of R_B;
- (c) if two single-clause readings each have M, each changes only dyn clauses, and they change different clauses, then their combination has M.

Weakening a **record** clause is anti-monotone: it enlarges R_B, so M of the dyn clauses must be re-checked (Rem V2.7(1)).

*Proof:* (a) and (b) are immediate from Def V2.3 and Def V2.4, since the record base is unchanged. (c): M_p is a conjunction over dyn clauses, each checked against the same record base. ∎

---

## 3. Classification

**Table V3.0 (summary).** Columns: M (Lem V2.6(i)); (ii-c) and (ii-v); T (Lem V2.6(iii)); and the status of D1 itself where the proof does not transfer. "S-form" is the statement of 4.1(i) with h* ≈_{σ, f} h. "Generic" means for every pair satisfying R4's hypothesis list for that part.

| Reading | M | ii-c | ii-v | T | D1 status where the proof does not transfer | Needs revising R4 if adopted? |
|---|---|---|---|---|---|---|
| R-a | **M** | yes | yes | yes | — (full transfer) | no |
| R-b | **M** | yes | yes | yes | — | no |
| R-c | **not-M** | yes | yes | yes | (i) generic **fails** (Lem V3.3); (i) holds for pairs with A4 and A7 as well (Lem V3.4) | **yes**: Prop 4.1(i)'s hypothesis list and S-form; Lem 4.2; Cor 4.3 |
| R-d | **not-M** | no | no | yes | (i) and (ii) generic **fail** (Lem V3.6); both hold under SC1, SC2 (R4 Lem 3.7; Lem V3.7) | no (R4 claims nothing under D-st⁺ beyond Lem 3.7); Lem 4.2 and Cor 4.3 do not extend (Lem V3.6) |
| R-e | **M** | yes | yes | yes | — | no |
| R-f | **M** | yes | yes | yes | — | no |
| R-g | **not-M** | no | yes | no | (i) generic **fails**, even for the base pair when N = 0; Cor 4.3 fails; (iii) **fails** (Lem V3.9); (i) and (ii) hold for (O, 𝓗) when N ≥ 1 and P ≠ V (Lem V3.10) | **yes**: Prop 4.1(i), (ii), (iii), Lem 4.2, Cor 4.3, Cor 4.1b |
| R-h | **M** | yes | yes | **no** | (iii) in its literal form **fails** (Lem V3.12); the variant with referenced added acts holds | **yes** for (iii)'s wording "that no record references"; (i) and (ii) unaffected |

The non-M readings are **R-c, R-d and R-g**. R-h has M, but (iii) does not transfer. Together these are the subject of `V1B-PREDICTIONS.md`.

**Rem V3.0a (the matrix's break candidates).** The V1b row lists "D-chg without capability" (R-e) among the genuine break candidates. Under Lemma M, R-e has M (Lem V3.5): D-chg^e is weaker than D-chg and vacuous on constant σ. A V1b failure under R-e would therefore be a falsifier of V1a in the matrix's sense ("a reading with M under which the construction fails"). By TASK §4 it is outside the sealed predictions. If the V1b scope keeps R-e, its expected outcome is that K4, K6 and K7, run with R-e's classes, all hold. The same applies to R-b, R-f and R-h's (i) and (ii).

### 3.1 R-a (target-tied B-chg)
**Lem V3.1.** R-a has M and T, and satisfies M_q^D and N_q. So (i), (ii) and (iii) hold for R-a with R4's proofs.

*Proof.* The record clauses are those of 𝐑₀ (RB1 and RB2 by Lem V2.5). B-chg^a constrains only change points, and a quiescent g has none, so M_p holds. The other dyn clauses are R4's (Lem V2.5). M_q^D is unchanged, and N_q holds through B-conf. T1: B-chg^a's act disjunct is identical to B-chg's, so an act-explained history satisfies B-chg^a. ∎

**Rem V3.1a.** With one target (as in U), B-chg^a and B-chg coincide, because τ(w) = τ always holds. **R-a is UNTESTABLE IN U.** This does not matter for V1b, because R-a has M.

### 3.2 R-b (existential B-conf)
**Lem V3.2.** R-b has M and T, and satisfies M_q^D and N_q.

*Proof.* The record base is R_B (RB1, RB2 as for 𝐑₀). M_p: a p-quiescent g ∈ R_B has W ≠ ∅ by B-recW, and every work event conforms, so some work event conforms. N_q: a q-quiescent g with W ≠ ∅ has no conforming work event. T1: "W ≠ ∅ and every work event conforms" implies B-conf^b. ∎

**Rem V3.2a.** R-b's M rests on B-recW, which supplies a work event in every member of the record base. This is the interaction that R-c breaks.

### 3.3 R-c (R-b with B-recW dropped)
**Lem V3.3 (R-c is not-M; D1(i) fails generically).**
- (a) R-c satisfies RB1 and RB2 (B-rec^c = D-rec), M_p^W, M_q^D, N_q and T, but **not M_p** for any p.
- (b) The S-form of (i) fails on 𝓗: some h ∈ R_B^c has no h* ∈ H-D^c ∩ H-B^c with h* ≈_{σ, f} h.
- (c) Prop 4.1(i) fails for R-c under A1, A2 and A5 alone: (O₄, 𝓗), with O₄ = (O, [W ≠ ∅]) from CM-A4a, satisfies A1, A2, A3, A5, A8 and A9, and O₄(R_B^c) ≠ O₄(H-B^c).

*Proof.* (a) RB1 and RB2 are clear. *Where the construction breaks:* take h = R4 Lem 3.5(a′) (E = {a}, W = ∅, Ref = {(ρ, a)}). Then h ∈ D-rec = R_B^c. h* = h[σ ≡ p, f ≡ ∞] has W = ∅, so B-conf^b ("some w ∈ W conforms") fails, and h* ∉ H-B^c. So M_p fails. M_p^W holds as in Lem V3.2, since W ≠ ∅ is now assumed rather than supplied by B-recW. M_q^D: the D-side dyn clauses are unchanged. N_q: as in Lem V3.2. T1: as in Lem V3.2. T2: D-rec is existential and reads only Ref and E.
(b) The same h. Any h* ≈_{σ, f} h has W = ∅, so it fails B-conf^b.
(c) O₄ reads only R, r, c and whether W ≠ ∅. None of σ, f, Ref, con or d affects these, and 𝓗 satisfies A5 and A9 (Lem 4.0a). O₄ violates only A4 (R4 CM-A4a). With h as in (a), O₄(h) ∈ O₄(R_B^c) has the flag "W = ∅". Every member of H-B^c has W ≠ ∅, because B-conf^b needs a work event. So O₄(h) ∉ O₄(H-B^c). ∎

**Lem V3.4 (R-c: what survives, and what would have to change in R4).**
- (a) Prop 4.1(ii) holds for R-c with R4's proof (Lem V2.6(ii), both parts).
- (b) Prop 4.1(iii) holds for R-c with R4's proof (Lem V2.6(iii)). The only change is the source of 𝒜 ≠ ∅.
- (c) Assume A1, A2, **A4**, A5, **A7**. Then for every h ∈ R_B^c there is h̃* ∈ H-D^c ∩ H-B^c with O′(h̃*) = O′(h), where h̃* differs from h in σ, in f, and by at most one fresh unreferenced work event. Hence the image equalities of (i) hold for R-c, and for the base pair they read O(H-D) = O(H-B^c) = O(H-D ∩ H-B^c) = O(D-rec class) = {o ∈ O(𝓗) : o ≠ ∅}.

*Proof of (c).* If W_h ≠ ∅, apply Lem V2.6(ii-c). Otherwise h ∈ D-rec gives some a ∈ E, so α(a) ∈ 𝒜. Add a fresh work event w₀ with α(w₀) = α(a), any target and t(w₀) = 0 (+work). F1 is untouched, so the result is in 𝓗′ by A7, and O′ is unchanged by A4. It satisfies D-rec and has W ≠ ∅. Apply Lem V2.6(ii-c). The last equality is R4 Cor 4.1b's identity O(h) = ∅ iff R_h = ∅, together with Lem 3.6. ∎

**Rem V3.4a (revision flag).** If R-c were adopted as the reading of HYP, **R4 would have to be revised**:
- Prop 4.1(i)'s hypothesis list would need A4 and A7;
- its S-form (h* ≈_{σ, f} h) would have to allow one added work event;
- Lem 4.2 (robustness to O_¬σf) and Cor 4.3 would fail as stated. O_¬σf sees W, so the counterexample of Lem V3.3(b) separates under O_¬σf, and O₄ separates although it reads neither σ nor f.

Nothing is revised here.

**Rem V3.4b.** The failure comes from the existential import of "some work conforms". The variant "not every work event violates" agrees with B-conf^b whenever W ≠ ∅, is vacuous on W = ∅, and with B-recW dropped it has M.

### 3.4 R-d (D-st⁺, the biconditional)
**Lem V3.6 (R-d is not-M; D1(i) and (ii) fail generically).**
- (a) R-d satisfies RB1, RB2, N_q and T, but neither M_p^W (so not M_p) nor M_q^D.
- (b) The S-forms of (i) and of (ii) fail on 𝓗 (parameters of R4 Lem 3.5: T = {0, 1}, 𝒯 = {τ}, V = {p, q}, P = {p}, 𝒜 = {x}).
- (c) Prop 4.1(i) fails for R-d under A1, A2, A5 alone: (O_¬σf, 𝓗) satisfies them, yet O_¬σf(R_B) ⊄ O_¬σf(H-D⁺ ∩ H-B).
- (d) The analogue of Cor 4.3 fails for R-d: O_¬σf(H-D⁺ ∩ R_B) ≠ O_¬σf(H-B).

*Proof.* (a) *Where the construction breaks:* let h ∈ R_B contain an issue act a with prep(a) = 0 and (α(a), adp) ∈ Cap. Adopted reads prep, Cap, d and obj, which the construction leaves unchanged, so Adopted(a, d(a)) holds in h*. But f*(a) = ∞, so D-st⁺'s extra clause fails. Such h exist with W ≠ ∅ (take R4 Lem 3.5(a) with prep(a) = 0 and Cap = {(x, adp)}), so M_p^W fails. The same h with σ ≡ q refutes M_q^D. N_q: the B side is 𝐑₀'s. T: the B side is 𝐑₀'s.
(b) *(i):* let h₁ have E = {a}, a issue with (τ, q), prep 0, d = 0, f = ∞; Cap = {(x, adp)}; W = {w}, t(w) = 0; Ref = {(ρ, a), (ρ, w)}; σ ≡ p. Then h₁ ∈ R_B. In any h* ≈_{σ, f} h₁, a is adopted, so D-st⁺ forces f*(a) ∈ {0, 1}. If f*(a) = 0, then σ*(0) = q by F1, and w violates. If f*(a) = 1, then σ*(1) = q, and B-conf forces σ*(0) = p, so (1, τ) ∈ Chg. D-chg then needs an act a′ with (α(a′), iss) ∈ Cap, which does not exist. *(ii):* let h₂ be h₁ with v(a) = p. h_v must have σ_v constant with w violating, so σ_v ≡ q. But a is adopted, so f_v(a) < ∞ and σ_v(f_v(a)) = p by F1, a contradiction.
(c) O_¬σf satisfies A1 and A2 on 𝓗, and 𝓗 satisfies A5 (R4 Lem 4.2). The O_¬σf-fibre of h₁ in 𝓗 is {h′ ∈ 𝓗 : h′ ≈_{σ, f} h₁}, which by (b) has no member in H-D⁺ ∩ H-B.
(d) Let h₃ have E = {a₁, a₂}, both issue acts on τ with prep 0 and d = 0, v(a₁) = p and v(a₂) = q; Cap = {(x, adp)}; W = {w} with t(w) = 0; Ref ⊇ {(ρ, a₁), (ρ, w)}; f ≡ ∞; σ ≡ p. Then h₃ ∈ H-B. Every h′ ≈_{σ, f} h₃ in H-D⁺ has both acts adopted, so both have finite f. Their values differ, so by F1 they enter at different times, and σ′ changes at 1. D-chg needs iss-capability, which is absent. So O_¬σf(h₃) ∉ O_¬σf(H-D⁺), and a fortiori ∉ O_¬σf(H-D⁺ ∩ R_B). ∎

**Lem V3.7 (R-d: what survives).**
- (a) (R4 Lem 3.7.) Under A1, A2, A5, SC1 and SC2, the image equalities of (i) hold for R-d, through h** = h*[Cap := ∅], with h** ≈_{σ, f, Cap} h.
- (b) Under A1, A2, A5, A6, SC1 and SC2, Prop 4.1(ii) holds for R-d, with h_v** := h[σ ≡ q, f ≡ ∞][Cap := ∅] and h_c** := h**. Both are ≈_{σ, f, Cap} h.
- (c) Prop 4.1(iii) holds for R-d with R4's proof (Lem V2.6(iii); H-D⁺ ⊆ H-D, and the D side is not used).

*Proof of (b).* h_v := h[σ ≡ q, f ≡ ∞] ∈ 𝓗′ with O′(h_v) = O′(h) (Lem 4.0b). Then h_v** ∈ 𝓗′ by SC1, and O′(h_v**) = O′(h_v) by SC2. In h_v** nothing is adopted (Adopted needs capability), so D-st⁺'s extra clause is vacuous. f ≡ ∞, so D-st is vacuous. σ is constant, so D-chg is vacuous. D-rec reads Ref. So h_v** ∈ H-D⁺′. w violates, so h_v** ∉ H-B′. h_c** ∈ H-D⁺′ ∩ H-B′ by (a). ∎

**Rem V3.7a (no revision needed, but robustness does not extend).** R4 makes no claim for D-st⁺ beyond Lem 3.7, whose side conditions SC1 and SC2 are exactly what O_¬σf violates. So R-d requires **no** revision of R4. But Lem 4.2 and Cor 4.3 hold only for R4's D-st: under R-d an observation blind to σ and f, namely O_¬σf, which sees Cap, does separate (Lem V3.6(d)). With R-d, the non-identifiability rests additionally on Cap being unobserved (O5).

### 3.5 R-e (D-chg without capability)
**Lem V3.5.** R-e has M and T, and satisfies M_q^D and N_q.

*Proof.* D-chg^e constrains only change points, so it holds on every quiescent history (M_p, M_q^D). It is weaker than D-chg (Cor V2.9(a)). The B side and the record clauses are 𝐑₀'s. T concerns only the B side. ∎

**Rem V3.5a.** R-e only enlarges H-D. It enlarges (iii)'s starting class H-D^e ∩ R_B, and Lem V2.6(iii) shows that (iii) does not use the D side.

### 3.6 R-f (B-recW dropped)
**Lem V3.8.** R-f has M and T, and satisfies M_q^D and N_q.

*Proof.* B-rec^f = D-rec satisfies RB1 and RB2. M_p: let g be p-quiescent with g ∈ D-rec, possibly with W = ∅. D-chg, D-st and B-chg hold as for 𝐑₀. Universal B-conf holds because σ ≡ p, vacuously if W = ∅. N_q: universal B-conf fails on a q-quiescent g with W ≠ ∅. T1 is as for 𝐑₀. T2: D-rec is existential. ∎

**Rem V3.8a.** Under R-f, (i) becomes O′(H-D′) = O′(H-B^f′) = O′(H-D′ ∩ H-B^f′) = O′(D-rec class) under A1, A2, A5 only (Rem V2.7(2)). (ii) applies to members with a work event. In U, R-f is testable: histories with w1 absent and an act referenced lie in R_B^f.

### 3.7 R-g (existential work-responsive change)
**Lem V3.9 (R-g is not-M; D1 fails generically; (iii) fails).**
- (a) R-g satisfies RB1, RB2, M_q^D and N_q, but neither M_p^W nor T.
- (b) For the base pair (O, 𝓗) with **N = 0** (T = {0}), H-B^g = ∅ while H-D ∩ R_B ≠ ∅. So Prop 4.1(i) and Cor 4.1b fail for R-g.
- (c) For N = 1 and the parameters of R4 Lem 3.5, (O_¬σf, 𝓗) (which satisfies A1, A2, A5) violates (i): some h ∈ H-D ∩ R_B has O_¬σf(h) ∉ O_¬σf(H-B^g). So the analogues of Lem 4.2 and Cor 4.3 fail.
- (d) The middle equality of (i) fails even for an observation that satisfies **every** hypothesis A1–A5 and A7–A9: O_Cap := (O, Cap) on 𝓗 with N = 1.
- (e) Prop 4.1(iii) fails for R-g on 𝓗 (N = 1).

*Proof.* (a) *Where the construction breaks:* for every h, h* has Chg(h*) = ∅, so B-chg^g's existential conjunct fails. So M_p^W fails at every member of R_B. M_q^D: the D side is 𝐑₀'s. N_q: q-quiescent histories have Chg = ∅. T1 fails because an act-explained history with conforming work may have Chg = ∅.
(b) Chg(h) ⊆ {1, …, N} × 𝒯 = ∅ when N = 0, so B-chg^g fails everywhere. R4's I₁ (T = {0}) is in H-D ∩ R_B, so O(H-D ∩ R_B) ≠ ∅ = O(H-B^g).
(c) Let h have W = {w} with t(w) = 1, any referenced issue act, w referenced, and σ ≡ p, f ≡ ∞ (in H-D ∩ R_B). Every h′ ≈_{σ, f} h has W = {w} with t(w) = 1 = N, so there is no change point after a work event, and h′ ∉ H-B^g.
(d) O_Cap reads only R, r, c and Cap, none of which changes under σ, f, Ref, con, d, or fresh additions (which leave Cap unchanged). So A1–A4 and A8 hold, and 𝓗 gives A5, A7 and A9. In H-D ∩ H-B^g there is a change point (B-chg^g). By D-chg it needs an act with iss-capability, and by D-st that act is adopted, which needs adp-capability. So every member has Cap ⊇ {(x, iss), (x, adp)} (with 𝒜 = {x}). Take h ∈ R_B with Cap = ∅. Then O_Cap(h) ∈ O_Cap(R_B) ∖ O_Cap(H-D ∩ H-B^g).
(e) Let h: σ = (p, q); a issue on τ with (τ, q), prep 0, d = 0, f = 1; Cap = {(x, iss), (x, adp)}; W = {w}, t(w) = 1; Ref = {(ρ, a), (ρ, w)}. Then h ∈ H-D ∩ R_B, and w violates. Every h′ allowed by (iii)'s first bullet has W = {w} with t(w) = 1, so B-chg^g fails in h′. ∎

**Lem V3.10 (R-g: what survives).**
- (a) For the base pair with N ≥ 1 and P ≠ V: O(H-D ∩ R_B) = O(H-B^g) = O(H-D ∩ H-B^g) = O(R_B) = {o ∈ O(𝓗) : o ≠ ∅}. The proof is a direct construction, not R4's.
- (b) Lem V2.6(ii-v) holds for R-g: the h_v half of Prop 4.1(ii) survives with R4's proof. The h_c half holds for the base pair with N ≥ 1 and P ≠ V in its O-form ("same O"), by (a), but not in its S-form (Lem V3.9(c)).

*Proof of (a).* Let o ≠ ∅ be the observation of some h ∈ 𝓗, and keep h's records with their r and c (keepers: any actors). Let 𝒜 contain the keepers and an actor x, and fix q ∈ V ∖ P and p ∈ P. Add an issue act a with (τ, q), prep 0, d = 0, f = N, actor x; Cap = {(x, iss), (x, adp)}; a work event w on τ, executor x, t(w) = 0; Ref = {(ρ₀, a), (ρ₀, w)} for some record ρ₀; con empty; σ(t)(τ) = p for t < N, σ(N)(τ) = q, and σ ≡ p on every other target. F1 holds (σ(N)(τ) = v(a)), and F2 holds vacuously. D-chg holds at (N, τ) through a, D-st through prep 0 with adp-capability, and D-rec holds. B-chg holds; B-chg^g holds through w (0 < N); B-conf holds (σ(0)(τ) = p); B-rec holds. O reads only R, r, c, so this history has observation o. Hence every nonempty o lies in O(H-D ∩ H-B^g). The reverse inclusions are as in Lem V2.6(i). ∎

**Rem V3.10a (revision flag).** If R-g were adopted, **R4 would have to be revised**: Prop 4.1(i) generically, (ii)'s h_c half generically, (iii), Lem 4.2, Cor 4.3, and Cor 4.1b (fails at N = 0). Under R-g, "the classes are not separated by what the records show" survives only for O itself, and only through the freedom to choose Cap, acts and work times inside the fibre. Nothing is revised here.

### 3.8 R-h (universal reference)
**Lem V3.11.** R-h has M, M_q^D and N_q. So (i) and (ii) hold for R-h with R4's proofs.

*Proof.* B-rec^h reads only Ref, E and W (RB1) and contains D-rec (RB2). The dyn clauses are 𝐑₀'s (Lem V2.5). ∎

**Lem V3.12 (R-h: (iii) does not transfer, and its literal form fails).**
- (a) R-h does not have T: B-rec^h is not preserved by (+act), since a fresh act is unreferenced.
- (b) On 𝓗, R4's I₇ (T = {0, 1}) is in H-D ∩ R_B^h, w violates, and no h′ as in Prop 4.1(iii) is in H-B^h.
- (c) Under A1, A2, **A3**, A4, A5, A7, the weakened (iii), in which the added acts are prepared and unconstituted **but referenced**, holds for R-h. This is R4's construction CM-A7(b), carried out in 𝓗′.

*Proof.* (b) I₇: σ ≡ q, a issue with (τ, q), prep 1, f = ∞, W = {w} with t(w) = 1, Ref = {(ρ, a), (ρ, w)}, Cap = ∅. Every item is referenced, so I₇ ∈ R_B^h, and I₇ ∈ H-D (R4). By bullet 1, σ′ differs from σ only at (1, τ), and w must conform, so σ′ = (q, p) with (1, τ) ∈ Chg. B-chg's work disjunct needs work before 1, and there is none. Its act disjunct needs an issue act with f = 1 and value p. v(a) = q, so a cannot serve under any f′. An added act would be unreferenced (bullet 1), and B-rec^h would fail.
(c) Run R4's (iii) construction, and after each (+act) apply (+ref) (ρ₀, a_{t,τ}) for a fixed ρ₀ ∈ R. Each (+ref) result satisfies F1, so it is in 𝓗′ by A7, and O′ is unchanged by A3. Every original item is referenced (h ∈ R_B^h), and so is every added act. So B-rec^h holds. B-chg and B-conf hold as in R4. ∎

**Rem V3.12a (revision flag).** If R-h were adopted, **R4's (iii) would have to be reworded** ("no record references" dropped; A3 added). This is the same phenomenon as CM-A7, moved from the frame into the class: H5's wording "without a recorded act" fails, and the O-level non-separation survives (R4 Rem 4.1a). Nothing is revised here.

---

## 4. Combinations

**Rem V4.1 (only interacting combinations matter).** By Cor V2.9(c), combinations of readings that change different dyn clauses and leave the record base fixed keep M: R-a, R-b and R-e combine freely. The interactions are these:
- **R-c = R-b ∘ R-f.** R-b has M relative to R_B (through B-recW), and R-f has M. Their combination is not-M (Lem V3.3), because R-f enlarges the record base to members on which R-b's existential clause fails. This is the only combination of two M-readings that loses M. It is why Lemma M checks M_p against the reading's own record base.
- **R-g ∘ R-f.** B-chg^g needs a work event, so with B-recW dropped the W = ∅ members of R_B^f also fail, as under R-c. The failure set is R-g's together with the W = ∅ members. No new phenomenon.
- **R-d ∘ R-e.** No interaction for (i): Lem 3.7's h** has Chg = ∅, and D-chg^e is vacuous there.
- **R-d ∘ R-c.** No interaction. For the base pair, the two repairs compose ((+work), then Cap := ∅, then σ ≡ p, f ≡ ∞), so the image equalities of (i) hold. The S-form fails on the union of the two failure sets.
- **R-a ∘ R-g** (target-tied work-responsive change). Needs two targets. **UNTESTABLE IN U.**

---

## 5. Answer to V1a

**Prop V5.1 (V1a result).** Under R4's hypotheses for each part of Prop 4.1:
- **R-a, R-b, R-e, R-f:** M. D1 (i), (ii) and (iii) hold with R4's proofs (Lem V2.6, Lem V3.1, V3.2, V3.5, V3.8).
- **R-h:** M. (i) and (ii) hold with R4's proofs. (iii) holds only with referenced added acts and A3 (Lem V3.11, V3.12).
- **R-c:** not-M. (ii) and (iii) hold with R4's proofs. (i) **fails** under A1, A2, A5 alone. It holds with A4 and A7 added (Lem V3.3, V3.4).
- **R-d:** not-M. (iii) holds with R4's proof. (i) and (ii) **fail** under A1, A2, A5 alone. They hold under SC1 and SC2 (Lem V3.6, V3.7; R4 Lem 3.7).
- **R-g:** not-M. (i) fails even for the base pair at N = 0, and under O_Cap, which satisfies every hypothesis A1–A5 and A7–A9. (iii) fails. (i) and (ii) hold for (O, 𝓗) when N ≥ 1 and P ≠ V (Lem V3.9, V3.10).

*Proof:* collected from the cited lemmas. ∎

**Rem V5.2 (what this means for D1).** Lemma M shows that D1's core survives every reading that is monotone in the sense of M, with the same one-line construction. The three non-M readings share one feature: each makes H-B or H-D demand something the quiescent history lacks: a work event (R-c), a finite entry into force for adopted acts (R-d), or a norm change (R-g). In each case the observation-level non-separation on O can be restored only by spending further freedoms of the frame (A4 and A7; SC1 and SC2; free choice of Cap and work times). R-c and R-g would require revising R4 if adopted. Whether either is the *intended* reading of HYP is a human decision, not settled here.

**Conj V5.3.** The only HYP-licensed readings under which D1 fails for the base pair (O, 𝓗) at the R4 parameters with N ≥ 1 and P ≠ V are readings that add a frame-level demand on a coordinate O does not see but the fibre cannot freely supply. *Not established;* stated as a direction for review.

---

## 6. Statement ledger

| ID | Statement (short) | Label | Rests on |
|---|---|---|---|
| Def V1.1 | reading; reading classes; record base R_B^𝐑 := 𝔅_rec^𝐑 | Def | R4 Def 3.2 |
| Def V1.2 | R4's reading 𝐑₀ | Def | R4 Def 3.1 |
| Def V1.3 | readings R-a … R-h, each with its HYP phrase | Def | HYP; R4 Rem 3.1a, 3.1b, Lem 3.7 |
| Rem V1.4 | candidates not added; B-fb and causal response would require revising R4 | Rem | HYP; R4 Rem 0.1, Def 3.1, Rem 3.1c |
| Rem V2.0 | R4's construction and its clause checks | Rem | R4 §4.3, Lem 4.0b |
| Def V2.1 | v-quiescent history | Def | — |
| Def V2.2 | RB1 (blindness), RB2 (dominance) | Def | — |
| Def V2.3 | M_p, M_p^W, M_q^D, N_q; "has M" | Def | — |
| Def V2.4 | act-explained; property T (T1, T2) | Def | R4 Def 1.7b |
| Lem V2.5 | 𝐑₀ has RB1, RB2, M_p, M_q^D, N_q, T | Lem | R4 Def 3.1 |
| Lem V2.6 | **Lemma M**: (i) M ⇒ D1(i) by h*; (ii) M_p^W ⇒ h_c half, M_q^D ∧ N_q ⇒ h_v half; (iii) T ⇒ (iii) for any D reading | Lem | R4 Lem 4.0b, Prop 4.1 proofs; A1, A2, A4–A7 |
| Rem V2.7 | what changes when a reading alters R_B | Rem | Lem V2.6 |
| Cor V2.8 | under M: Lem 4.2 and Cor 4.3 analogues | Cor | Lem V2.6; R4 Lem 4.2 |
| Cor V2.9 | monotonicity in dyn clauses; record weakening anti-monotone; combination | Cor | Def V2.3, V2.4 |
| Table V3.0 | classification summary | Rem | §3 lemmas |
| Rem V3.0a | R-e is M, so a V1b failure under it falsifies V1a | Rem | Lem V3.5; matrix V1a falsifier |
| Lem V3.1 | R-a: M, T, M_q^D, N_q | Lem | Lem V2.5 |
| Rem V3.1a | R-a UNTESTABLE IN U | Rem | R4 §7.1, §7.3 |
| Lem V3.2 | R-b: M, T, M_q^D, N_q | Lem | Lem V2.5 |
| Rem V3.2a | R-b's M rests on B-recW | Rem | Lem V3.2 |
| Lem V3.3 | R-c not-M; S-form fails; (i) fails generically (O₄) | Lem | R4 Lem 3.5(a′), CM-A4a |
| Lem V3.4 | R-c: (ii), (iii) transfer; (i) holds with A4, A7 | Lem | Lem V2.6; R4 Cor 4.1b, Lem 3.6 |
| Rem V3.4a | R-c would require revising R4 (4.1(i), Lem 4.2, Cor 4.3) | Rem | Lem V3.3 |
| Rem V3.4b | the failure is existential import; "not all violate" has M | Rem | Def V2.3 |
| Lem V3.5 | R-e: M, T, M_q^D, N_q | Lem | Cor V2.9 |
| Rem V3.5a | R-e only enlarges H-D | Rem | Lem V2.6(iii) |
| Lem V3.6 | R-d not-M; S-forms of (i), (ii) fail; (i) fails for O_¬σf; Cor 4.3 analogue fails | Lem | R4 Lem 3.5, Lem 4.2 |
| Lem V3.7 | R-d: (i) via R4 Lem 3.7; (ii) via Cap := ∅; (iii) transfers | Lem | R4 Lem 3.7, Lem 4.0b |
| Rem V3.7a | R-d needs no R4 revision; robustness then rests on O5 | Rem | Lem V3.6 |
| Lem V3.8 | R-f: M, T, M_q^D, N_q | Lem | Lem V2.5 |
| Rem V3.8a | R-f's (i) is unrestricted; testable in U | Rem | Rem V2.7 |
| Lem V3.9 | R-g not-M; (i) fails at N = 0, for O_¬σf and for O_Cap; (iii) fails | Lem | R4 I₁, Lem 4.2, Lem 4.0a |
| Lem V3.10 | R-g: base-pair images equal for N ≥ 1, P ≠ V; h_v half transfers | Lem | direct construction |
| Rem V3.10a | R-g would require revising R4 | Rem | Lem V3.9 |
| Lem V3.11 | R-h: M, M_q^D, N_q | Lem | Lem V2.5 |
| Lem V3.12 | R-h: no T; (iii) literal fails at I₇; referenced variant holds | Lem | R4 I₇, CM-A7(b) |
| Rem V3.12a | R-h would require rewording R4's (iii) | Rem | Lem V3.12 |
| Rem V4.1 | interacting combinations; R-c = R-b ∘ R-f is the one M-loss | Rem | Cor V2.9, Lem V3.3 |
| Prop V5.1 | V1a result per reading | Prop | §3 |
| Rem V5.2 | what the non-M readings have in common | Rem | Prop V5.1 |
| Conj V5.3 | D1 fails at N ≥ 1, P ≠ V only by frame-level demands the fibre cannot supply | Conj | — |

**Compliance.** No act count, norm count or coding-dependent value is used. R4 is not changed. Every place where a reading would need a change to R4 is flagged (Rem V1.4, V3.4a, V3.10a, V3.12a).

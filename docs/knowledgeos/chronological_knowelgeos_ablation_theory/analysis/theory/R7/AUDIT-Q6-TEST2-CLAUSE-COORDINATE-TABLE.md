# R7 · Audit Q6 test 2: which record coordinates each clause of each reading reads

**Analysis only; decides nothing; draft r1.1, 2026-10-13.** **r0 reviewed: FAIL (F1 stale copy; F2–F10 substantive, addressed by G1–G9). r1 re-reviewed PWL; r1.1 fixes (wording) not re-reviewed.** Changes G1–G9 and H1–H8 are listed in §11. This is RESEARCH-AUDIT-1 r1.1 §2 Q6 test 2: "for every clause of 𝐑₀ and of each Table 9.4 reading, tabulate the record coordinates it reads, under S and under S′". It adopts, amends, ratifies, selects and authorizes nothing. It selects no spanning rule (F-LOG-0271 stands). It does not touch the K-c reassessment of M/K/C (F-LOG-0302). r1 cites that document's §5.5 (r1.2, now committed) once, in §8, as a scoping note only; it is not cited as corroboration (G7).

**Standing qualifier:** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

**Independence disclosure.** This test reads the same sources as the parallel M/K/C reassessment (`R7/KC-MKC-REASSESSMENT.md`, F-LOG-0302): R4 r3.1, T1 r1.6 and T2 r1.4. It also uses T3 rule 2a (S, S′ and S″), taken through RESEARCH-AUDIT-1 Q1 P3 (here P10), and RESEARCH-AUDIT-1 Q1 P5 (here P5). KC-MKC additionally uses T3 r1.9 and the frozen encoding FZ. The same analyst commissioned both. Its author is Claude-family, like every other reviewer and run in this corpus (Λ7). **It is not independent evidence** for or against anything that reassessment or RESEARCH-AUDIT-1 concludes. It is an inspection of definitions plus one finite enumeration, and it should be reviewed before any act relies on it.

**Reproduction:** `R7/AUDIT-Q6-TEST2-scripts/clause_coords.py`. Run `python3 -B clause_coords.py [test names…]` from that folder. It makes one exhaustive pass over a finite universe per tested coordinate set (§5). Results are in §5.2.

---

## 0. Summary

| question | answer |
|---|---|
| Does any change, status or conformance ("dyn") clause of either family read a record coordinate {R, r, c, k, Ref, con}, under any Table 9.4 reading? | **No**: not under unexpanded mention, not under expanded mention, and not under dependence. This holds for every reading, including the rejected R-k (Thm 1). |
| Is an actor exclusion needed for that? | **No, if R4's own record-coordinate list (Def 1.6, P5) is used.** The only actor-valued record coordinate is the keeper k, and **no clause of any reading mentions k or depends on it**. Excluding k (the coordinate form of S′) is therefore idle (Cor 1.1). |
| Then where does the actor convention matter? | Only if the act and work actor attributes α, α_W and the actor set 𝒜 are counted **on the record side**. That is the coordinate form of S (C1 spans and so sits on the record side too). Under that count, D-chg reads α in every reading except R-e (dependence sense: N ≥ 1, |V| ≥ 2), and D-st reads α once Adopted is expanded, in every reading. **No H-B dyn clause reads α, except under the rejected R-k** (Thm 2). |
| Does Lemma 2 hold at coordinate level? | **Dyn part: yes**, for every reading, in both senses, with the P5, S′ and S″ coordinate sets; **it fails with the S set**, on the H-D side only (H-B too under the rejected R-k). **Record part: symmetric** (both sides read exactly Ref, in the dependence sense) **in every reading except R-i and R-i′**. Under those two readings H-D reads Ref and H-B reads no record coordinate (Thm 3). |
| Mention versus dependence | They agree on every record coordinate except R, which is mentioned only through Ref (the typing Ref ⊆ R × (E ∪ W), or, in R-h, a redundant ∃ρ ∈ R) and is never a dependence (Lem 2, Lem 5). Outside the record coordinates, the actor set 𝒜 (not a record coordinate) is likewise mentioned only as the codomain of α and is never a dependence (Lem 3). Expansion changes the answer in **one place that matters here**: Adopted, inside D-st, D-st⁺ and R-k^st, brings in α (actor-valued) under the S set. **No expansion brings in a record coordinate.** |
| Record clauses: mention versus dependence | D-rec, B-recW and B-rec **mention** E or W as well as Ref. They **depend on Ref alone**, because act and work identifiers come from disjoint universes (Def 1.7a; Lem 4). R-h's B-side clause is the only record clause that depends on act or work coordinates as well as on Ref. |

**What follows (stated, not resolved; §7).**
- The coordinate-level form of RESEARCH-AUDIT-1's restated Lemma 2 holds under S′ **and** S″, and fails under S. "Equivalent to S′" is narrower than what the coordinate evidence shows: the condition is "**not S**" (under A-1). This partly restates RESEARCH-AUDIT-1's own F1 and S2 (S″ coincides with S′). Cor 1.1 also conflicts with the literal wording "only … (S′)" in RESEARCH-AUDIT-1 C6 and F-LOG-0292 (§7.6).
- F-LOG-0265's record-clause premise ("neutral under 𝐑₀ only, asymmetric under R-i and R-i′") is matched at coordinate level. At coordinate level the record clauses are symmetric under every other Table 9.4 reading as well.
- At coordinate level, the R-i/R-i′ asymmetry holds in both senses and under every spanning convention. At row level it is principle-dependent (§8).

---

## 1. Research question

**RQ.** For each clause φ of each family (H-D, H-B) under each reading 𝐑 ∈ {𝐑₀, R-a, …, R-k} of R4 Table 9.4:
- (a) which coordinates of R4 Def 1.7 does φ **mention**, before and after expanding the derived notions of Def 1.10?
- (b) on which coordinate sets does φ's truth value **depend**?
- (c) in particular, does φ read a **record coordinate**, and does the answer change with the actor-counting convention (S, S′, S″)?

This question supports RESEARCH-AUDIT-1 Lemma 2 (§1 Q1) and its suggestion S1 (§2 Q4), which r1.1 restates as "neutral at coordinate level if actor-valued coordinates are not counted (equivalent to S′)".

---

## 2. Premises and assumptions

### 2.1 Source-backed premises

| id | premise | source |
|---|---|---|
| P1 | A history is h = (𝒜, Cap, E^iss, E^adp, W, R, α, d, τ, v, prep, f, obj, τ_W, α_W, t, r, c, k, Ref, con, σ). Each component is a coordinate. Attributes are compared as functions **with their domain**. **con is compared as its graph**, the set of pairs (a, con(a)), so adding or removing an act outside con's domain leaves con unchanged (the Lem 5 and Lem 6 witnesses rely on this). h ≈_X h′ iff h and h′ agree on every coordinate outside X | R4 Def 1.7 |
| P2 | Records have r, c and k. Ref ⊆ R × (E ∪ W) and con : E ⇀ R are relations of the record layer. k(ρ) ∈ 𝒜 | R4 Def 1.6 |
| P3 | The identifier universes 𝔄, 𝔈, 𝔚 and 𝔑 are pairwise disjoint, and E ⊆ 𝔈, W ⊆ 𝔚 | R4 Def 1.7a |
| P4 | Base frame 𝓗 = F1 ∧ F2. F1: f(a) = t ∈ T ⇒ σ(t)(τ(a)) = v(a). F2: con(a) = ρ ⇒ (ρ, a) ∈ Ref ∧ d(a) = r(ρ) | R4 Def 1.8 |
| P5 | The record coordinates are R, r, c, k, Ref and con. Actors are a separate item set. k is the only actor-valued record coordinate | RESEARCH-AUDIT-1 r1.1 Q1 P5, from R4 Def 1.6–1.7 |
| P6 | Clauses D-chg, D-st, D-rec, B-chg, B-conf, B-recW, B-rec, and Adopted, Chg and conform/violate, are as defined | R4 Def 3.1, Def 1.10 |
| P7 | A reading supplies 𝔇_dyn, 𝔇_rec, 𝔅_dyn and 𝔅_rec. "Dyn" clauses concern change, status or conformance. "Record" clauses are D-rec, B-recW, B-rec or readings of them. 𝐑₀ = ({D-chg, D-st}, {D-rec}, {B-chg, B-conf}, {B-rec}) | R4 Def 9.1 |
| P8 | The readings R-a … R-k change 𝐑₀ exactly as stated in Table 9.4. R-k is REJECTED as a reading of HYP | R4 Table 9.4 |
| P9 | The T1 concepts behind the record coordinates: C13 record (R, r, c), C14 r, C15 c, C16 k, C17 Ref, C18 con. Derived concepts that read them: C23 (an unreferenced item, i.e. Ref-absence), C25 independence (Def 4.9: k, con, and the actors of acts and work), C27 mode-undetermined (Lem 5.2: Ref, con, d). C1 participant = 𝒜, the value set of α, α_W and k. C2 = Cap | T1 r1.6 §1 |
| P10 | Spanning rules for C1, at concept level: S (both sides), S′ (neither side), S″ (the side where it is located). No rule is selected | T3 rule 2a, via RESEARCH-AUDIT-1 Q1 P3; F-LOG-0271 |
| P11 | T2 reading-sensitivity facts used as cross-checks: R12 (Lem 3.6) fails for H-B under R-i′; R13's B-rec ⇒ D-rec (RB2) fails under R-i and R-i′ | T2 r1.4 R12, R13 |

### 2.2 Analyst assumptions (not in the sources)

| id | assumption | why it is needed | effect if dropped |
|---|---|---|---|
| A-1 | **Coordinate transfer of the spanning rules.** REC_P5 = {R, r, c, k, Ref, con}. REC_S′ = REC_P5 ∖ {k} (actor-valued coordinates on neither side). REC_S″ = REC_P5 (each actor reference sits with its bearer: k with records, α with acts, α_W with work). REC_S = REC_P5 ∪ {𝒜, α, α_W} (C1 spans, so its coordinates also count on the record side) | T3's rule 2a is stated for concepts, not coordinates | the S row of Thm 2 would need re-stating. Thm 1 and Thm 3 use only REC_P5 and are unaffected |
| A-2 | Cap is counted with C2 (capability), not with C1, so it never joins REC_S | T1 separates C1 and C2 (P9) | if Cap were added to REC_S, Thm 2 would be unchanged in outcome: D-chg and D-st read Cap wherever they read α, and B-side clauses read Cap only under R-k |
| A-3 | **Dependence is relative to R4's coordinatization.** d is a primitive coordinate. It is not re-expressed as r∘con, even though F2 forces d(a) = r(con(a)) where con is defined | otherwise "everything else fixed" has no fixed meaning | under a re-parametrization in which d is derived from (r, con), D-st, D-st⁺ and R-k^st would read r and con. R4 does not adopt that parametrization (Def 1.7, Def 1.9: d = r **only** under constitution) |
| A-4 | Dependence is evaluated over the base frame 𝓗. Witness histories are checked against F1 and F2 | R4 Def 1.11 defines "does not depend" over a frame | the independence results (by Lem 1) hold over every frame and over 𝓢. The witnesses are in 𝓗, so they also serve over 𝓢 |

---

## 3. Definitions

**Def T2.1 (mention).** For a clause φ:
- **M₀(φ)**, the *unexpanded* mention, is the set of coordinates and defined notions written in φ's text in Def 3.1 or Table 9.4, together with the item sets its quantifiers range over.
- **M₁(φ)**, the *expanded* mention, is M₀(φ) with every Def 1.10 notion replaced by its definition:
  - Adopted(a, t) → prep, α, Cap, d, E^adp, obj;
  - Chg → σ (and the parameter T);
  - conforms / violates → σ, t, τ_W (and the parameter P).
- The ‡ marks apply to both M₀ and M₁ columns of Table A. R is written **R‡** when it enters M₀ or M₁ only through Ref: either through the typing Ref ⊆ R × (E ∪ W), or through a quantifier ∃ρ ∈ R that the typing makes redundant (B-rec^h; Lem 5). 𝒜 is written **𝒜‡** when it enters M₀ or M₁ only as the codomain of α or the carrier of Cap, with no quantifier ranging over 𝒜. R‡ and 𝒜‡ **are members of M₀ and M₁** (so they count for the mention sense), but Lem 1 cannot be applied to them directly; Lem 2, Lem 3 and Lem 5 handle them.

**Def T2.2 (dependence).** φ **depends on** a coordinate set X over 𝓗 iff some h, h′ ∈ 𝓗 have h ≈_X h′ and φ(h) ≠ φ(h′). This is R4 Def 1.11's notion, applied to a predicate. **Dependence is relative to the Def 1.1 parameters** (T = {0, …, N}, 𝒯, V, P), because 𝓗 is. A positive dependence result is claimed only for the parameters of its witness. A non-dependence result proved by Lem 1, Lem 3 (for 𝒜), Lem 4 or Lem 5 (for R) holds for all parameters. A finite 0 in §5.2 proves non-dependence only for the universe U (G3).

**Def T2.3 (reads a record coordinate).**
- Under the **mention** sense, φ reads a record coordinate under convention K iff M₁(φ) ∩ REC_K ≠ ∅. (Do the same with M₀ for the unexpanded sense.)
- Under the **dependence** sense, φ reads a record coordinate under K iff φ depends on REC_K, taken as a block.

The block test is the right one here: "φ is a function of the non-record coordinates" is exactly "φ does not depend on REC_K".

---

## 4. Lemmas and proofs

**Lem 1 (mention bounds dependence).** If X ∩ M₁(φ) = ∅, then φ does not depend on X over any frame.

*Proof.* φ's truth value is a function of the parameters (T, 𝒯, V, P) and of the coordinates in M₁(φ), including the item sets its quantifiers range over. If h ≈_X h′ and X ∩ M₁(φ) = ∅, then h and h′ agree on M₁(φ), so φ(h) = φ(h′). ∎

**Lem 2 (singleton item sets are degenerate).** For X = {R}, h ≈_X h′ implies h = h′. The same holds for {E^iss}, {E^adp} and {W}.

*Proof.* r, c and k are attributes with domain R, compared with their domain (P1). If they are equal, then R is equal. The same argument works with α, d, … for E, and with τ_W, α_W, t for W. ∎

So no predicate depends on {R} alone. Dependence on R is tested only through the record block REC_K. 𝒜 is not the domain of any attribute, so {𝒜} is a non-degenerate singleton.

**Lem 3 (no clause reads k, con or α_W; 𝒜 only as 𝒜‡).** No clause of any reading in Table 9.4 has k, con or α_W in M₁, and 𝒜 occurs in M₁ only as 𝒜‡. Hence no clause depends on {k}, {con}, {α_W} or {𝒜}.

*Proof.* Inspection of every M₁ entry in Table A (§6). For k, con and α_W, apply Lem 1. For 𝒜: no quantifier ranges over 𝒜, and 𝒜 enters only through the values α(a) and the pairs in Cap. So each clause's truth is a function of M₁ ∖ {𝒜‡}. If h ≈_{𝒜} h′, then α and Cap are unchanged, and the truth value is unchanged. ∎

**Lem 4 (record clauses are functions of Ref alone).** D-rec, B-recW and B-rec depend on a coordinate set X iff Ref ∈ X.

*Proof.*
- (⇐ fails without Ref.) In every history, Ref ⊆ R × (E ∪ W), with E ⊆ 𝔈, W ⊆ 𝔚 and 𝔈 ∩ 𝔚 = ∅ (P2, P3). So for (ρ, x) ∈ Ref, "x ∈ E" holds iff x ∈ 𝔈. D-rec(h) therefore holds iff some (ρ, x) ∈ Ref_h has x ∈ 𝔈, which is a function of Ref_h alone. The same goes for B-recW (with 𝔚) and for B-rec. If Ref ∉ X, then h ≈_X h′ forces Ref_h = Ref_h′, and the truth values agree.
- (⇒ with Ref.) Witness **W1**:
  - T = {0, 1}, one target τ, V = {p, q}, P = {p}, 𝒜 = {x}, Cap = ∅;
  - E^iss = {a} with (τ, q), prep 1, d 0, f = ∞ and α x;
  - W = {w} with t 0 and α_W x;
  - R = {ρ} with r 0, c s1 and k x;
  - con = ∅ and σ ≡ q.

  F1 is vacuous (f = ∞) and F2 is vacuous (con = ∅). Vary only Ref over ∅, {(ρ, a)}, {(ρ, w)} and {(ρ, a), (ρ, w)}:
  - D-rec is 0, 1, 0, 1;
  - B-recW is 0, 0, 1, 1;
  - B-rec is 0, 0, 0, 1. ∎

**Lem 5 (R-h's B-side clause).** B-rec^h := B-rec ∧ "every act and every work event is referenced by some record" depends on Ref, on the act block {E^iss, E^adp, α, d, τ, v, prep, f, obj} and on the work block {W, τ_W, α_W, t}. It depends on no other record coordinate.

*Proof.* In W1 with Ref = {(ρ, a), (ρ, w)}, B-rec^h holds. Add a fresh unreferenced issue act a′ ((+act), Def 1.7b: prep 1, f = ∞, so F1 and F2 still hold). The result is ≈ h outside the act block, and B-rec^h fails. Do the same with a fresh work event and the work block. Both additions leave con unchanged as a graph (P1). Ref: by W1.

*No other record coordinate.*
- r, c, k and con are not in M₁(B-rec^h), so Lem 1 applies to them.
- **R is in M₁** (∃ρ ∈ R), so Lem 1 does not apply to it. Instead: every history has Ref ⊆ R × (E ∪ W) (P2), so "∃ρ ∈ R: (ρ, x) ∈ Ref" is equivalent to "∃ρ: (ρ, x) ∈ Ref". The clause is therefore a function of Ref, the act block and the work block. If h ≈_X h′ with X disjoint from those, the truth value is unchanged; in particular R is not a dependence in any context where Ref is fixed.
- The enumeration cannot test this, because {R} is degenerate (Lem 2). ∎

*Finite note.* The work-block dependence needs two work events. U has one work slot, so §5.2 shows 0 there.

**Lem 6 (R-i's B-side record clause reads no record coordinate).** "E ≠ ∅" has M₁ = {E^iss, E^adp}. It depends on the act block and on no record coordinate under any K.

*Proof.* Lem 1 for independence. For dependence on the act block: take W1 with Ref = {(ρ, w)}, and remove a (Ref does not reference it, so the history stays well-typed and in 𝓗; con = ∅ is unchanged as a graph, P1). The clause goes from 1 to 0. ∎

**Thm 1 (dyn clauses read no record coordinate: Lemma 2, dyn part, under P5, S′ and S″).** For every reading 𝐑 in Table 9.4 (𝐑₀, R-a, …, R-j, and the rejected R-k in both variants) and every φ ∈ 𝔇_dyn^𝐑 ∪ 𝔅_dyn^𝐑:
- M₀(φ) ∩ REC_P5 = ∅;
- M₁(φ) ∩ REC_P5 = ∅;
- φ does not depend on REC_P5.

The same holds for REC_S′ ⊆ REC_P5 and for REC_S″ = REC_P5.

*Proof.* Table A lists M₁ for every dyn clause. None of them contains R, r, c, k, Ref or con. Expansion adds only prep, α, Cap, d, E^adp and obj (from Adopted); σ (from Chg); and σ, t and τ_W (from conforms). None of these is in REC_P5. Apply Lem 1. ∎

**Cor 1.1 (the k-exclusion is idle).** For every clause of every reading, "reads a record coordinate" has the same answer under REC_P5, REC_S′ and REC_S″, in both senses.

*Proof.* The three sets differ only in k, and by Lem 3 no clause mentions k or depends on it. ∎

**Thm 2 (where the actor convention matters: REC_S).** Take REC_S = REC_P5 ∪ {𝒜, α, α_W} (A-1).
- **(a) H-D dyn.**
  - D-chg reads REC_S in both senses under 𝐑₀ and every reading except **R-e** (dependence sense: N ≥ 1, |V| ≥ 2). This includes D-chg^j under R-j.
  - D-st reads REC_S in the expanded-mention and dependence senses (through Adopted's α) under **every** reading, but **not** in the unexpanded sense.
  - D-st⁺ (R-d) behaves the same way as D-st.
  - D-chg^e (R-e) does not read REC_S.
- **(b) H-B dyn.** No H-B dyn clause reads REC_S under any accepted reading: 𝐑₀ and R-a … R-j. Under the **rejected** R-k:
  - B-chg^k-cap reads α in both senses;
  - B-chg^k-st reads α in the expanded-mention and dependence senses.
- **(c) Record clauses.** No record clause reads 𝒜, α or α_W. Their REC_S answers equal their REC_P5 answers.

*Proof.*
- Mention: Table A.
- Independence: Lem 1 and Lem 3. No clause depends on 𝒜 or α_W (Lem 3; 𝒜 is mentioned only as 𝒜‡), so the only new member of REC_S that any clause depends on is α.
- Dependence on α (Cap held fixed): the witnesses below. All are in 𝓗.
  - **Parameter caveat (G3).** W2, W4 and W5 need N ≥ 1 and |V| ≥ 2. When N = 0 or |V| = 1, Chg = ∅, so D-chg, D-chg^j and the R-k change clauses are vacuous or constant and do not depend on α. W3 needs only T ∋ 0 and V ≠ ∅, so D-st and D-st⁺ depend on α for every parameter choice. The mention-sense results do not depend on parameters. Each one varies **only** α, so it also shows dependence on the block REC_S.
  - **W2 (D-chg, D-chg^j).**
    - T = {0, 1}, one target, V = {p, q}, 𝒜 = {x, y}, Cap = {(x, iss)};
    - σ(0) = p and σ(1) = q;
    - E^iss = {a} with (τ, q), prep 1, d 0 and f 1 (F1: σ(1)(τ) = q ✓);
    - W = R = ∅.

    With α(a) = x, D-chg and D-chg^j hold. With α(a) = y, they fail.
  - **W3 (D-st, D-st⁺).**
    - 𝒜 = {x, y}, Cap = {(x, adp)}, σ ≡ q;
    - E^iss = {a} with (τ, q), prep 0, d 0 and f 0 (F1 ✓);
    - E^adp = W = R = ∅.

    With α(a) = x, Adopted(a, 0) holds, so D-st and D-st⁺ hold. With α(a) = y, no adoption exists and f(a) = 0 < ∞, so both fail.
  - **W4 (B-chg^k-cap).** W2 unchanged. α(a) = x makes it hold, and α(a) = y makes it fail (W = ∅, so the work disjunct is unavailable).
  - **W5 (B-chg^k-st).** W2 with prep 0, d 0 and Cap = {(x, adp)}. α(a) = x gives Adopted(a, 1), so it holds. α(a) = y makes it fail.
- D-chg^e, B-chg, B-chg^a, B-chg^g, B-chg^g⁺, B-conf and B-conf^b do not have α in M₁ (Table A). ∎

**Thm 3 (record clauses: symmetric except under R-i and R-i′).** In the dependence sense, under every K ∈ {P5, S′, S″, S}:
- the H-D record clause D-rec reads exactly {Ref} under every reading;
- the H-B record clause reads exactly {Ref} under 𝐑₀, R-a, R-b, R-c, R-d, R-e, R-f, R-g, R-g⁺, R-h, R-j and R-k;
- under **R-i** the H-B record clause ("E ≠ ∅") reads **no** record coordinate, and under **R-i′** there is **no** H-B record clause.

In the mention sense the record-coordinate part is the same:
- D-rec: Ref, plus R‡;
- B-recW and B-rec: Ref, plus R‡;
- B-rec^h: Ref, plus R‡ (here a quantifier ∃ρ ∈ R that the typing makes redundant; Lem 5);
- "E ≠ ∅": none.

*Proof.* Table 9.4 fixes the B-side record clause per reading:
- 𝐑₀, R-a, R-b, R-d, R-e, R-g, R-g⁺, R-j and R-k: B-rec;
- R-c and R-f: D-rec;
- R-h: B-rec^h;
- R-i: "E ≠ ∅";
- R-i′: none.

Then apply Lem 4, Lem 5 and Lem 6. Ref belongs to every REC_K. ∎

**Remark (mention versus dependence on the record clauses).** D-rec mentions E, and B-recW mentions W, as well as Ref. Under P-c, E (C8, C9) and W (C19) are on the norm-and-work side, and Ref (C17) is on the record side. So **in the mention sense D-rec and B-recW span both sides; in the dependence sense they read only Ref** (Lem 4). B-rec^h is the only record clause that genuinely depends on both sides (Lem 5). This is the coordinate-level counterpart of the R-h/R83 crossing note in F-LOG-0265. It is a remark about clauses, not about T2 rows or P-c crossing counts. Whether Ref itself is a record-side concept or a spanning one is a T1/T3 placement question (C17 is a relation from records *to* acts and work). This test does not decide it.

---

## 5. Finite check

### 5.1 Universe and method

- **Universe.** T = {0, 1}; one target; V = {p, q}; P = {p}; texts {s1, s2}; 𝒜 ∈ {{x}, {x, y}}.
- **Optional items:** an issue act a1 (any α, d, v, prep and f ∈ {0, 1, ∞}); an adoption act b1 with obj a1 (any α, d); a work event w1 (any α_W, t); a record r1 (any r, c, k).
- **Relations:** every Cap, Ref and con; every σ : T → V. Only histories that satisfy F1 and F2 are kept: **4,190,640** histories.
- **Method.** For each clause in Table A and each tested set X, the script groups histories by their values outside X and reports 1 iff the clause's truth value varies inside some group.
  - Groups are keyed by Python's `hash` of the remaining coordinates. A collision can only merge groups, so it could only produce a false **1**; a **0** is exact.
  - Every **1** that a conclusion uses is backed by an explicit witness (W1–W5, Lem 5, Lem 6).
- **One target.** With one target, R-a's clause coincides with B-chg (R4 Rem 9.8), so R-a is covered by Lem 1 only and not by the enumeration. The enumeration supports the proofs. It is not their basis.

### 5.2 Results

The full output is in `AUDIT-Q6-TEST2-scripts/clause_coords.out`, produced by four parallel invocations of the script. The universe has 4,190,640 histories. Entries are 1 if the clause's truth value varies with X while everything else is fixed.

**Block tests (the Lemma 2 criterion):**

| clause | REC_P5 | REC_S′ | REC_S (+𝒜, α, α_W) | actor-valued {𝒜, α, α_W, k} |
|---|---|---|---|---|
| D-chg | 0 | 0 | **1** | 1 |
| D-chg^e (R-e) | 0 | 0 | 0 | 0 |
| D-chg^j (R-j) | 0 | 0 | **1** | 1 |
| D-st | 0 | 0 | **1** | 1 |
| D-st⁺ (R-d) | 0 | 0 | **1** | 1 |
| D-rec | **1** | **1** | 1 | 0 |
| B-chg (= B-chg^a in U) | 0 | 0 | 0 | 0 |
| B-chg^g (R-g) | 0 | 0 | 0 | 0 |
| B-chg^g⁺ (R-g⁺) | 0 | 0 | 0 | 0 |
| B-chg^k-st (R-k) | 0 | 0 | **1** | 1 |
| B-chg^k-cap (R-k) | 0 | 0 | **1** | 1 |
| B-conf | 0 | 0 | 0 | 0 |
| B-conf^b (R-b) | 0 | 0 | 0 | 0 |
| B-recW | **1** | **1** | 1 | 0 |
| B-rec | **1** | **1** | 1 | 0 |
| B-rec^h (R-h) | **1** | **1** | 1 | 0 |
| E ≠ ∅ (R-i) | 0 | 0 | 0 | 0 |

**Singleton tests on record coordinates:**
- r, c, k and con: 0 for every clause.
- R: 0 for every clause (degenerate; Lem 2).
- Ref: 1 exactly for D-rec, B-recW, B-rec and B-rec^h.
- 𝒜 and α_W: 0 for every clause (Lem 3).
- α: 1 exactly for D-chg, D-chg^j, D-st, D-st⁺, B-chg^k-st and B-chg^k-cap.

**Agreement with the proofs.**
- Every 0 agrees with Lem 1 and Thm 1–3. Every 1 that a conclusion uses has a witness (W1–W5, Lem 5, Lem 6).
- Two entries are finite-universe artifacts and carry no conclusion:
  - B-rec^h on the work block is 0 in U, because U has one work slot. If that slot is unreferenced, B-recW already fails. Lem 5's work-block dependence needs two work events.
  - B-chg^g on the act block is 0 in U. With N = 1, its extra conjunct forces the work disjunct.

---

## 6. Table A: clause × reading × coordinates

Notation:
- **rec** = record coordinates from REC_P5.
- **act-val** = the actor-valued coordinates 𝒜, α, α_W and k.
- **other** = every remaining coordinate.
- Under "Dependence", "rec" is the dependence-sense answer for REC_P5 (= S′ = S″, Cor 1.1), and "S" is the answer for REC_S.

### 6.1 Clauses (each defined once)

| clause | formula (R4) | M₀: rec · act-val · other | M₁ (expanded): rec · act-val · other | expansion changes? | Dependence: rec · S |
|---|---|---|---|---|---|
| **D-chg** | ∀(t, τ) ∈ Chg ∃a ∈ E^iss: τ(a) = τ, f(a) = t, v(a) = σ(t)(τ), (α(a), iss) ∈ Cap | — · α, 𝒜‡ · Chg, E^iss, τ, f, v, σ, Cap | — · α, 𝒜‡ · σ, E^iss, τ, f, v, Cap | no (Chg → σ only) | **no** (Thm 1) · **yes** (W2) |
| **D-chg^e** (R-e) | D-chg without the Cap conjunct | — · — · Chg, E^iss, τ, f, v, σ | — · — · σ, E^iss, τ, f, v | no | no · no |
| **D-chg^j** (R-j) | D-chg ∧ Chg ≠ ∅ | as D-chg | as D-chg | no | no · **yes** (W2) |
| **D-st** | ∀a ∈ E^iss: f(a) < ∞ ⇒ Adopted(a, f(a)) | — · — · E^iss, f, *Adopted* | — · **α**, 𝒜‡ · E^iss, f, prep, Cap, d, E^adp, obj | **yes**: α enters | no · **yes** (W3) |
| **D-st⁺** (R-d) | D-st ∧ ∀a ∈ E^iss: (∃t Adopted(a, t)) ⇒ f(a) < ∞ | — · — · E^iss, f, *Adopted* | as D-st | **yes**: α enters | no · **yes** (W3) |
| **D-rec** | ∃(ρ, a) ∈ Ref: a ∈ E | Ref, R‡ · — · E^iss, E^adp | same | no | **Ref only** (Lem 4) · Ref only |
| **B-chg** | ∀(t, τ) ∈ Chg: [∃a ∈ E^iss: τ(a) = τ, f(a) = t, v(a) = σ(t)(τ)] ∨ [∃w ∈ W: t(w) < t] | — · — · Chg, E^iss, τ, f, v, σ, W, t | — · — · σ, E^iss, τ, f, v, W, t | no | no · no |
| **B-chg^a** (R-a) | B-chg with τ(w) = τ in the work disjunct | — · — · as B-chg + τ_W | — · — · as B-chg + τ_W | no | no · no (Lem 1; not enumerated, Rem 9.8) |
| **B-chg^g** (R-g) | B-chg ∧ ∃(t, τ) ∈ Chg ∃w ∈ W: t(w) < t | as B-chg | as B-chg | no | no · no |
| **B-chg^g⁺** (R-g⁺) | B-chg^g ∧ ∃(t, τ) ∈ Chg ∃a ∈ E^iss: τ(a) = τ, f(a) = t, v(a) = σ(t)(τ) (**author's formalization** of R4's prose "some change point is made by an act entering into force with its value") | as B-chg | as B-chg | no | no · no |
| **B-chg^k-cap** (R-k, rejected) | B-chg with (α(a), iss) ∈ Cap added to the act disjunct | — · α, 𝒜‡ · as B-chg + Cap | same | no | no · **yes** (W4) |
| **B-chg^k-st** (R-k, rejected) | B-chg with Adopted(a, t) added to the act disjunct | — · — · as B-chg + *Adopted* | — · **α**, 𝒜‡ · as B-chg + prep, Cap, d, E^adp, obj | **yes**: α enters | no · **yes** (W5) |
| **B-conf** | ∀w ∈ W: w conforms | — · — · W, *conforms* | — · — · W, σ, t, τ_W | yes, but no rec or act-val coordinate enters | no · no |
| **B-conf^b** (R-b, R-c) | ∃w ∈ W: w conforms | as B-conf | as B-conf | as B-conf | no · no |
| **B-recW** | ∃(ρ, w) ∈ Ref: w ∈ W | Ref, R‡ · — · W | same | no | **Ref only** (Lem 4) · Ref only |
| **B-rec** | D-rec ∧ B-recW | Ref, R‡ · — · E^iss, E^adp, W | same | no | **Ref only** (Lem 4) · Ref only |
| **B-rec^h** (R-h) | B-rec ∧ ∀x ∈ E ∪ W ∃ρ ∈ R: (ρ, x) ∈ Ref | Ref, R‡ (∃ρ ∈ R, redundant given the typing) · — · E^iss, E^adp, W | same | no | **Ref** + act block + work block (Lem 5; R not a dependence) · same |
| **E ≠ ∅** (R-i) | E^iss ∪ E^adp ≠ ∅ | — · — · E^iss, E^adp | same | no | **none** (Lem 6) · none |

Finite cross-check (§5.2): every "no" in the "Dependence" column is a 0 in the enumeration, and every "yes" is a 1 there and has a witness. There are two exceptions:
- B-chg^a, which the one-target universe cannot separate from B-chg;
- B-rec^h's work-block dependence, which needs two work events (§5.2). It is proved by Lem 5.

### 6.2 Readings (which clause fills each slot)

"rec" = the record coordinates the slot reads in the dependence sense; this is identical under P5, S′, S″ and S. "α under S" = whether the slot reads α when the S set is used (dependence sense; "exp." = only after expanding Adopted).

| reading | 𝔇_dyn (α under S) | 𝔇_rec (rec) | 𝔅_dyn (α under S) | 𝔅_rec (rec) | record-coordinate symmetry | actor asymmetry under S |
|---|---|---|---|---|---|---|
| **𝐑₀** | D-chg (yes), D-st (exp.) | D-rec (Ref) | B-chg (no), B-conf (no) | B-rec (Ref) | symmetric | H-D only |
| **R-a** | as 𝐑₀ | D-rec (Ref) | B-chg^a (no), B-conf (no) | B-rec (Ref) | symmetric | H-D only |
| **R-b** | as 𝐑₀ | D-rec (Ref) | B-chg (no), B-conf^b (no) | B-rec (Ref) | symmetric | H-D only |
| **R-c** | as 𝐑₀ | D-rec (Ref) | B-chg (no), B-conf^b (no) | D-rec (Ref) | symmetric (the same clause on both sides) | H-D only |
| **R-d** | D-chg (yes), D-st⁺ (exp.) | D-rec (Ref) | B-chg, B-conf (no) | B-rec (Ref) | symmetric | H-D only |
| **R-e** | D-chg^e (**no**), D-st (exp.) | D-rec (Ref) | B-chg, B-conf (no) | B-rec (Ref) | symmetric | H-D only after expansion; **none unexpanded** |
| **R-f** | as 𝐑₀ | D-rec (Ref) | B-chg, B-conf (no) | D-rec (Ref) | symmetric | H-D only |
| **R-g** | as 𝐑₀ | D-rec (Ref) | B-chg^g, B-conf (no) | B-rec (Ref) | symmetric | H-D only |
| **R-g⁺** | as 𝐑₀ | D-rec (Ref) | B-chg^g⁺, B-conf (no) | B-rec (Ref) | symmetric | H-D only |
| **R-h** | as 𝐑₀ | D-rec (Ref) | B-chg, B-conf (no) | B-rec^h (Ref; plus the act and work blocks) | symmetric in record coordinates; the H-B record clause also reads act and work coordinates | H-D only |
| **R-i** | as 𝐑₀ | D-rec (Ref) | B-chg, B-conf (no) | E ≠ ∅ (**none**) | **asymmetric: H-D reads Ref, H-B reads none** | H-D only |
| **R-i′** | as 𝐑₀ | D-rec (Ref) | B-chg, B-conf (no) | **no clause** | **asymmetric: H-D reads Ref, H-B has no record clause** | H-D only |
| **R-j** | D-chg^j (yes), D-st (exp.) | D-rec (Ref) | B-chg, B-conf (no) | B-rec (Ref) | symmetric | H-D only |
| **R-k** (rejected) | as 𝐑₀ | D-rec (Ref) | B-chg^k-cap (yes) or B-chg^k-st (exp.), B-conf (no) | B-rec (Ref) | symmetric | **both sides** |

The dyn columns read no record coordinate in any row (Thm 1). The "rec" entries are the same under every convention (Thm 3).

---

## 7. Lemma 2: verdict and exact conditions

**Paraphrase, not a quotation.** The following is assembled from the bullets of RESEARCH-AUDIT-1 r1.1 §1 Q1 (Lemma 2 and its "[r1] Status of Lemma 2" bullet). Lemma 2 says that D-chg, D-st, B-chg and B-conf read no record coordinate, that D-rec and B-recW each read Ref and R, and hence that 𝐑₀ is symmetric under this criterion. Its stated status is "neutral at coordinate level if actor-valued coordinates are not counted (equivalent to S′)".

**Verdict.**

1. **Dyn part: holds.** It holds at 𝐑₀ and at every Table 9.4 reading, in the unexpanded-mention, expanded-mention and dependence senses (Thm 1).
   - It needs **no actor exclusion** when the record coordinates are R4's own list P5, because k is unread (Cor 1.1).
   - It **fails** when α and 𝒜 are counted on the record side (REC_S). The failure is on the H-D side: D-chg in every reading except R-e, and D-st and D-st⁺ after expanding Adopted. The H-B side fails only under the rejected R-k (Thm 2).
2. **Record part: holds in a corrected form.**
   - "Reads Ref and R" is exact in the mention sense, with R entering only through Ref's typing. In the dependence sense **each clause reads Ref alone** (Lem 2, Lem 4).
   - The symmetry holds at 𝐑₀ and at every reading except R-i and R-i′ (Thm 3).
3. **Exact condition (under A-1).** Lemma 2 (dyn part) holds at coordinate level **iff the record side is not taken to include the act actor attribute α (and 𝒜)**. Of the three spanning conventions, S′ and S″ meet this condition and S does not.
   - **"(and 𝒜)" is not necessary in the dependence sense.** No clause depends on 𝒜 (Lem 3), so adding 𝒜 alone to the record side changes no dependence answer. It matters only in the mention sense, where 𝒜‡ is in M₁ of D-chg, D-st, D-st⁺ and the R-k clauses. α_W is never read.
   - **Parameter caveat on the "iff", dependence sense only (G3).** The "only if" direction rests on W2–W5. D-st's dependence on α (W3) holds for every Def 1.1 parameter choice, so the dependence-sense failure under S holds for all parameters. D-chg's dependence on α needs N ≥ 1 and |V| ≥ 2. When N = 0 or |V| = 1, Chg = ∅, and only D-st and D-st⁺ carry the failure. The mention-sense statement is unaffected.
   - So the coordinate evidence supports "**neutral at coordinate level unless the actor is counted on the record side (S)**".
   - That is broader than "equivalent to S′": S″ also yields it (consistent with F-LOG-0271, "S′/S″ only", and with RESEARCH-AUDIT-1 S2, S″ ≡ S′ on rows).
   - The parenthesis "if actor-valued coordinates are not counted" is, at coordinate level, about **α and 𝒜 entering the record side**. It is not about excluding the actor-valued record coordinate k, which no clause reads.
4. **Mention or dependence.**
   - The dyn part holds in both senses.
   - Under S, its failure for D-st (and for R-k^st) appears **only** in the expanded sense and the dependence sense. The unexpanded sense hides it (Adopted is not unfolded). This is the coordinate-level form of RESEARCH-AUDIT-1 counterexample (a), "Read Adopted through".
   - The failure for D-chg appears in all three senses.
5. **Where the H-D/H-B asymmetry appears at coordinate level.** In exactly two places:
   - **(i) record clauses under R-i and R-i′.** This holds in every sense and under every convention.
   - **(ii) dyn clauses under the S coordinate set.** This is always on the H-D side, through α. Under R-e it disappears in the unexpanded sense and remains in the expanded sense. Under the rejected R-k it is mirrored on the H-B side.
   - Nowhere does a dyn clause of either family read a record coordinate in R4's own sense.
6. **Conflict with existing wording (G5, H4; stated, not amended).** Cor 1.1 conflicts with the literal wording of two passages:
   - RESEARCH-AUDIT-1 r1.1 Appendix C, item C6 (line 501): "This holds only with actor-valued coordinates not counted (S′)."
   - F-LOG-0292 (`F-GOVERNANCE-LOG.md`, line 3971): "'Neutral under 𝐑₀' can be restated at coordinate level only if actor coordinates are not counted, which is equivalent to S′."

   With R4's record-coordinate list P5, and **with k counted**, Lemma 2 still holds, because no clause reads k. The "only if" fails for the actor-valued record coordinate. What is necessary (under A-1) is that α is not added to the record side, which is "not S", not "S′".
   - The "not S" refinement partly restates RESEARCH-AUDIT-1's own F1 (the asymmetry is present under S and absent under S′ and S″) and S2 (S″ coincides with S′ on rows).
   - **Alternative reading.** RESEARCH-AUDIT-1's "do not count actor-valued coordinates" may refer to α and 𝒜 entering the record side, not to k. On that reading, Cor 1.1 refutes only the literal wording, and the S″ point is already conceded by RESEARCH-AUDIT-1 S2.
   - This test does not amend RESEARCH-AUDIT-1 or the governance log.

---

## 8. Implications (stated; nothing here is resolved)

- **For S1 (restated Lemma 2).**
  - The table is the evidence base that RESEARCH-AUDIT-1 Q6 asked for.
  - It supports S1's content with two refinements:
    - the condition is "**not S**" (under A-1) rather than "equivalent to S′";
    - in the dependence sense, the record clauses read Ref only, not "Ref and R".
  - It does **not** remove the spanning-rule question. It locates that question: under A-1, S1 holds iff α is not counted on the record side (with the parameter caveat of §7.3 in the dependence sense), and the choice between counting it there (S) or not (S′/S″) is the open human choice under F-LOG-0271.
  - Whether S1 should be re-worded is a matter for a proposal and a human act, not for this test.
- **For F-LOG-0265's premise** ("neutrality holds under 𝐑₀ only (asymmetric under R-i and R-i′)").
  - At coordinate level, the record-clause part of the premise is matched: 𝐑₀ is symmetric, and R-i and R-i′ are asymmetric.
  - At coordinate level, symmetry also holds under R-a, R-b, R-c, R-d, R-e, R-f, R-g, R-g⁺, R-h, R-j and R-k. So "**only** under 𝐑₀" is narrower than the coordinate-level result; the premise concerns rows (not measured here).
  - The premise was stated about the P-c cut on T2 rows, not about clauses. This test does not measure rows or cuts, and it does not say whether the premise's scope should change.
  - The VP12 condition recorded on the premise (F-LOG-0270/0271: conditional on S′/S″) corresponds, via A-1, to Thm 2: under S the H-D change clause reads α and the H-B change clause does not.
- **For the R-i/R-i′ asymmetry (RESEARCH-AUDIT-1 F2).**
  - Confirmed **at coordinate level** in its record-coordinate form, under every spanning convention and in both senses.
  - **Scope.** At row level, KC-MKC-REASSESSMENT r1.2 §5.5 finds that the R-i/R-i′ record-clause asymmetry disappears under principles M and U. This is a scoping, not a contradiction: this test speaks about clauses and coordinates, and §5.5 about T2 rows under row-encoding principles. It is cited here for scope only, not as corroboration.
  - F2's *crossing* form ("D-rec crosses") depends on the mention sense: D-rec mentions E but depends only on Ref (Lem 4). The record-coordinate asymmetry does not depend on that distinction.
  - A definitional point: under R-i, the H-B side **has** a record clause by Def 9.1's classification ("readings of" B-rec), but that clause **reads no record coordinate**. "Is a record clause" and "reads a record coordinate" therefore come apart under R-i. This is consistent with T2 R12 and R13 (RB2 fails under R-i and R-i′).
- **For R-h / R83.** B-rec^h is the only record clause whose dependence spans record coordinates and act and work coordinates (Lem 5). This matches the R-h crossing note carried with F-LOG-0265, at clause level only.

---

## 9. What this establishes, and what it does not

**Establishes (formal, from definitions; [F]-type inspection plus elementary proofs):**
- Thm 1, Cor 1.1, Thm 2 and Thm 3, relative to R4 r3.1's coordinatization (A-3) and to the coordinate transfer of S, S′ and S″ (A-1);
- the clause-level location of the actor convention's effect;
- the R-i/R-i′ record asymmetry, at coordinate level only (§8 for row level).

Positive dependence results hold for the parameters of their witnesses (G3). General non-dependence results come from Lem 1, Lem 3, Lem 4 and Lem 5, not from the enumeration.

**Supports (finite, Claude-family):** the §5 enumeration agrees with every entry it can test; there are two entries it cannot separate (§6.1). An independent reviewer re-ran all 22 tests (4,190,640 histories each) and matched §5.2 (G1). That reviewer is Claude-family (Λ7). Finite 0s prove non-dependence only for U.

**Does not establish:**
- anything about T2 rows, P-c crossing counts, host lists or cuts. Those are concept-level and row-level objects governed by the K-c rules, and this test does not apply those rules;
- whether S, S′ or S″ is the right convention;
- whether Ref is a record-side or a spanning concept;
- anything about H-D versus H-B, which stay non-identifiable;
- any adequacy of R4's readings to HYP or to the corpus;
- independence from RESEARCH-AUDIT-1 or from the M/K/C reassessment (see the disclosure at the top).

---

## 10. Limitations

- **Λ7:** every reviewer and every successful independent run is Claude-family. This test was written and run by a Claude-family analyst, with no non-Claude check.
- **Standing qualifier (verbatim):** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"
- The mention sets M₀ and M₁ were extracted by one analyst from the R4 text, and they are not independently re-extracted. The finite check tests dependence, not extraction.
- A-1 (the coordinate form of S, S′ and S″) is the analyst's transfer of a concept-level rule. A different transfer could change Thm 2's S row, but not Thm 1 or Thm 3.
- A-3: dependence is relative to R4's coordinates. Under a parametrization in which d is derived from (r, con) via F2, the status clauses would read r and con.
- Finite universe: one target (R-a not separable), T = {0, 1}, at most one item of each kind (plus one adoption act). Hash-keyed grouping can only produce false positives, and every positive used is backed by a witness.
- Dependence is relative to the Def 1.1 parameters. W2, W4 and W5 need N ≥ 1 and |V| ≥ 2 (G3).
- R-k is rejected (Table 9.4) and is included only for completeness. Its entries carry no weight for any accepted reading.
- No file other than this one and its script folder was created or changed. Nothing is committed.

---

## 11. Changes in r1 (review of r0; fixes not re-reviewed)

| id | finding | change |
|---|---|---|
| G1 | F1: the reviewed copy still had the §5.2 placeholder (stale on-disk copy; resolved in the 20:35 version). F2: the false claim that every "yes" in Table A is a 1 in the enumeration | F2 fixed by the §6.1 exceptions list (B-chg^a; B-rec^h × work block). Recorded that the reviewer independently re-ran all 22 tests, each over 4,190,640 histories, and matched §5.2. This includes S = 1 for D-chg, D-chg^j, D-st, D-st⁺, B-chg^k-st and B-chg^k-cap, and B-rec^h × work block = 0. §9 now reads "agrees with every entry it can test; two entries it cannot separate (§6.1)". The full enumeration was not re-run for r1. The reviewer is Claude-family (Λ7) |
| G2 | F3: Lem 5 proof gap. R is in M₁(B-rec^h), so Lem 1 does not apply to R | Replaced with the typing argument: given Ref ⊆ R × (E ∪ W), ∃ρ ∈ R is redundant, so R is not a dependence. Noted that the enumeration cannot test this, because {R} is degenerate (Lem 2) |
| G3 | F4: parameters | Def T2.2: dependence is relative to the Def 1.1 parameters. W2, W4 and W5 need N ≥ 1 and |V| ≥ 2. Caveat added to Thm 2, to the §7.3 "iff" (dependence sense only), §8, §9 and §10. Finite 0s prove non-dependence only for U |
| G4 | F5: inconsistent ‡ notation | ‡ is now defined once (Def T2.1) and used consistently in Lem 3, Thm 3 and Table A for R‡ and 𝒜‡. Lem 3 re-proved for 𝒜. §7.3: "(and 𝒜)" is not necessary in the dependence sense |
| G5 | F6: conflict with prior wording not stated | §7 item 6: Cor 1.1 contradicts the "only … (S′)" wording in RESEARCH-AUDIT-1 C6 (line 501) and F-LOG-0292 (log line 3971), both quoted. The "not S" refinement partly restates RESEARCH-AUDIT-1 F1 and S2. Neither document is amended |
| G6 | F7: A-1 dependence not carried | "(under A-1)" is attached wherever "the condition is not S" is stated (§0, §7.3, §8). "Corresponds exactly" weakened to "corresponds, via A-1" |
| G7 | F8: R-i/R-i′ scope | The "every spanning convention" and "confirms F2" sentences are scoped to coordinate level. One sentence added citing KC-MKC-REASSESSMENT r1.2 §5.5 (the row-level asymmetry disappears under M and U) as a scoping, not a contradiction, and not as corroboration |
| G8 | F9: disclosure too narrow | The independence disclosure now lists T3 rule 2a (via RESEARCH-AUDIT-1 P3, here P10) and RESEARCH-AUDIT-1 P5, and notes that KC-MKC also uses T3 r1.9 and FZ |
| G9 | F10: minor accuracy | The §7 Lemma 2 "quote" is now marked as a paraphrase assembled from RESEARCH-AUDIT-1's bullets. §0 no longer calls 𝒜 a record coordinate. P1 adds Def 1.7's rule that con is compared as a graph, which the Lem 5 and Lem 6 witnesses rely on. R-g⁺'s formula is labelled as the author's formalization of R4's prose |

### r1.1 (targeted re-review of r1: PASS WITH LIMITATIONS; G2, G3 (W3 at N = 0), G4 confirmed; G5 quotes verbatim; wording fixes not re-reviewed)

| id | change |
|---|---|
| H1 | "(dependence sense: N ≥ 1, |V| ≥ 2)" added to the D-chg/D-chg^j line of Thm 2(a) and to §0 row 3 |
| H2 | §7.3 and the Thm 2 caveat: "When N = 0 or |V| = 1, Chg = ∅, and only D-st and D-st⁺ carry the failure" |
| H3 | §0 row 4: "(H-B too under the rejected R-k)" |
| H4 | §0 and §7.6: "contradicts" changed to "conflicts with the literal wording". Alternative reading added: RESEARCH-AUDIT-1's phrase may concern α and 𝒜 entering the record side, not k; on that reading Cor 1.1 refutes only the literal wording, and the S″ point is already conceded by RESEARCH-AUDIT-1 S2 |
| H5 | Header: "r0 reviewed: FAIL (F1 stale copy; F2–F10 substantive, addressed by G1–G9)". G1 row now describes F2 correctly (the false "every yes is a 1" claim, fixed by the §6.1 exceptions list) |
| H6 | Def T2.2 names all four sources of all-parameter non-dependence: Lem 1, Lem 3 (𝒜), Lem 4, Lem 5 (R) |
| H7 | The ‡ definition in Def T2.1 now covers the M₀ columns too |
| H8 | §8: "conservative" replaced with "narrower than the coordinate-level result; the premise concerns rows (not measured here)". The Λ7 note (Claude-family reviewer) sits next to the G1 re-run sentence in §9 and in the G1 row |

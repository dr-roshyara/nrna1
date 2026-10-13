# R7 · K-c reassessment: M, K and C against formal requirements, sources, consistency and E3-REL

**Analysis only; decides nothing; draft r1.2** (2026-10-13). **r1.1 re-reviewed PWL; r1.2 fixes not re-reviewed.**

**Review history.**
- r0 had a fresh adversarial review: PASS_WITH_LIMITATIONS. Every number reproduced, but the formal conclusions overreached. The review raised 5 material findings (F1–F5) and 7 minor ones (F6–F12).
- r1 applies all twelve; §9 maps each finding to its fix.
- r1 had a targeted re-review: PASS_WITH_LIMITATIONS. Every number reproduced, and F1–F12 were addressed. It raised nine further findings (H1–H9).
- r1.1 applies H1–H9 (§9).
- r1.1 had an independent re-review: PASS_WITH_LIMITATIONS. Every number reproduced. It raised seven findings (J1–J7).
- r1.2 applies J1–J7 (§9.1). **r1.2 is not re-reviewed.**

**Authority.** F-LOG-0302 (Human ARB direction, 2026-10-13), `F-GOVERNANCE-LOG.md` line 4195 ff., verbatim:
> "Do not select T3, M, or K solely to reconcile the criterion label. The earlier choice of "single principle" was a research criterion, not a ratification of any specific convention. Reassess M, K, and C against the formal requirements, source evidence, consistency across all cases, and consequences for E3-REL. Keep T3 as written as the minimal-amendment baseline. Record the criterion as provisional until the comparison is independently reviewed. Do not silently resolve C-γ or authorize E4/E5 through this act."

**What this document does not do.**
- It ratifies, selects, adopts and authorizes nothing, and KC-1 is not made.
- T3 is used as a reference point and is not ratified.
- "Single principle" stays a provisional research criterion.
- No spanning rule is selected (F-LOG-0271); S, S′ and S″ are reported throughout.
- C-γ is not resolved, D12 is not resolved, and E4 and E5 are not authorized.
- Minimal amendment, output preservation and "single principle" are **not** used to rank anything. Where a result bears on them, it is reported as a fact.

**Scope beyond F-LOG-0302 (r1, F4).** F-LOG-0302 asks for M, K and C to be reassessed. The candidates K-T, U and C-Σ (§6.2) are **additions by the analyst**. They are reported because they locate where M, K and C fail. They are not options that the direction put in scope.

**Post-hoc disclosure (r1, F4).** The requirements Rq1–Rq9, the counterexample pairs and the candidates K-T, U and C-Σ were all written **after** the KC-CONVENTIONS r1.1 results were known. Each one may have been shaped, consciously or not, by which principles it separates.

**Independence disclosure.** This work shares its sources (T1 r1.6, T2 r1.4, T3 r1.9, R4 r3.1, FZ) with the parallel audit test 2 (the clause × coordinate table, `R7/AUDIT-Q6-TEST2-*`). One analyst commissioned both, and both are by Claude. **This document is not independent evidence for test 2, and test 2 is not independent evidence for this document.** No test 2 file was opened for this work or changed by it.

**Standing qualifier (verbatim):** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

**Reproduction.**
- The scripts live in `R7/KC-MKC-REASSESSMENT-scripts/`.
- Run them from that folder with **`python3 -B mkc.py ..`** and **`python3 -B adhoc.py ..`**. Together they take about 12 s. Use `-B` so that no `__pycache__` is written.
- They import `R7/KC-CONVENTIONS-scripts/kc.py` (r1.1) and, through it, `R7/AUDIT-Q6-TEST1-scripts/blind_enc.py`. Neither file is changed.

| file | sha256 prefix | role |
|---|---|---|
| `mkc.py` | `10d40efb451ec061` | Encodes the principles, including the [E] candidates (r1.2, J1: **K-T's encoding equals K's by construction, row for row**; §3a2 prints the empty diff; per-principle requirement checks, §3e). Computes counts, E3-REL, host diffs, the clause table, ranks, the counterexample pairs (§3), the guard / T-C pairing test (§3b), C-Σ's diff from C, its 83-row classification (§3d) and the Theorem 1 premise check |
| `mkc.out` | `e9a2dce2a9ac1bc4` | its output |
| `adhoc.py` | `91935bc6d613f595` | Lists the ad hoc choice points of each principle (including K-T) and what each one moves |
| `adhoc.out` | `c84fdddb01dd3f5b` | its output |

On T3 (FZ), K, C and M the scripts reproduce KC-CONVENTIONS r1.1 §3 exactly. The r1 changes alter only the counterexample encodings (F6) and C-Σ's R11 (F3d). r1.1 adds a K-T entry whose encoding **equals K's by construction, row for row** (same construction as `kc.py`'s K; the guard-row sets are FZ's). Only K-T's stated definition differs. **Every K-T verdict, mark and count in this document is K's result relabelled, not a second computation** (r1.2, J1). r1.2 adds K-T's A-i parse choice points to `adhoc.py` (J2); no other output changes.

---

## 0. Summary

| question | result |
|---|---|
| **Research question (§1.1)** | Which of M, K and C (with T3 as the baseline) meets the requirements that the governed uses of "crossing" place on K-c? Where does each fail, and why? What does each imply for E3-REL? Does the evidence support "single principle" as a criterion? |
| **Requirements (§2)** | Nine requirements; the normative status of each is marked [U], [E-s] or [E] (§2.2):<br>- Rq1: determinacy.<br>- Rq2a: definitional invariance.<br>- Rq2b: meta-level invariance.<br>- Rq3: polarity coherence.<br>- Rq4: compositionality.<br>- Rq5: T-B slot functionality.<br>- Rq6: separability.<br>- Rq7: fit to purpose, split into 7h (hosting relevance) and 7c (constraint coupling).<br>- Rq9: guard / T-C pairing coherence.<br>Only Rq6 and Rq8 follow directly from the uses ([U]). Every other requirement is a stipulation that the sources support in part. **Rq9 [E] largely re-encodes 7h on the guard rows**: a principle that hosts guard rows on the tie they delimit also gives them their paired T-C row's verdict. So 7h and Rq9 are **one position, not two supports** |
| **Theorem 1 (§2.3)** | No principle can be invariant under equivalence modulo the frame 𝓗 (or modulo all typed histories 𝓢) and still reproduce the ratified uses. **Every usable principle is therefore syntactic at a stipulated level of description.** Which rewordings it must survive is itself a choice |
| **Theorem 2 (§2.4, conditional)** | Rq3, Rq5 and Rq7h conflict **only under four choices**, and each is a stipulation:<br>(a) R80 is classed as a non-entailment;<br>(b) Rq3 spans row types;<br>(c) Rq7h is limited to T-A rows;<br>(d) the Rq5 witness counts as N-free.<br>Without (a) or (b), the principle "T-B form = slot clause; frame non-entailments contribute their subject" satisfies all three, and its outputs equal K's. **Theorem 2 is not an impossibility for every principle.** It locates what T3's A1 depends on |
| **Does any principle pass every requirement?** | **None of the seven evaluated passes all of them.** Every principle evaluated fails at least one of Rq1, Rq3 (under (a)–(b)), Rq5, Rq7h, Rq7c or Rq9. The 7h / 7c split alone means no principle passes Rq7 as a whole (§2.2). This does not show that no principle could pass |
| **Failures by root cause (§6.1)** | **T3:** A1 (Rq1, and Rq3 under (a)–(b)); rule 2 own-placement (Rq2a); fails 7c.<br>**M:** mention level (Rq2a); polarity-blind slot glosses (Rq5); fails 7c.<br>**K:** guard role from the T2 label (Rq1), which reduces to the tier under K-T; Rq3 under (a)–(b); fails 7c.<br>**C (K1c):** an undefined class-level clause (Rq1 parse, Rq2b, Rq3); a KC encoding slip at R15 (Rq4); fails 7h and Rq9 |
| **[E] candidates (§6.2)**, additions beyond F-LOG-0302 | **K-T:** K with a textual guard criterion. Its encoding equals K's by construction, so its verdicts and marks are K's results relabelled (J1). Marks: Rq1 ~ (the tier is still a T2 label; K's R80-role and R79-contrast choices remain; the four A-i parse choices that count against C-Σ move no row for K-T; none of its choices moves E3-REL, J2); Rq2b ~ (frame statements); Rq3 conditional on (a) and (b), as for K; fails 7c.<br>**U:** M's polarity read through definitions. Rq1 ~; fails Rq5 and 7c.<br>**C-Σ:** C with a Σ₁ polarity rule. Rq1 ✗ under the uniform rule (5 choice points that move rows; R15's exceptive premise moves E3-REL 10 / 5 vs 9 / 4, the same kind of effect as K's label). It fails Rq9 (1 / 5 on T2-stated pairs) and 7h. **At the verdict level** it differs from C only at R15; **at the encoding level** at R11 and R15 (and from C-R15ref only at R11, encoding level). It is **a choice, not a derivation** |
| **Parse and label dependence (§5.2)** | **C:** E3-REL is 9 / 4 (K1c, SH), 10 / 5 (R15ref) or 11 / 6 (R15ref-R14). The range comes from C's undefined class-level clause.<br>**K:** removing the "guard form" label moves 5 rows and gives 9 / 4. Under K-T the label becomes the tier.<br>**M:** polarity-blind but coherent. It needs no choice that moves a row; its cost is Rq5 |
| **E3-REL (13 / 8 accepted, F-LOG-0297) (§5.3)** | T3, M, K, K-T, U: **13 / 8**. C: 9 / 4 to 11 / 6. C-Σ: 10 / 5. Under the other D12 reading, each is +1 / +1 |
| **"Single principle" (§6.3)** | It does not select a unique principle. Several single principles exist (M, K-T, U, C-Σ), differing in which requirement they give up (r1.2, J3: "coherent" dropped). **The criterion stays provisional.** Nothing here confirms or revises it |
| **Assessment [E] (§6.4)** | **No principle is identified as best supported.** The requirements are themselves stipulations written after the fact. The pivotal open question remains the guard-row purpose (7h vs 7c, together with Rq9), which is entangled with C-γ. It is a human question |

---

## 1. Question, definitions, premises

### 1.1 Research question

**RQ.** The governed records make several uses of "a T2 row crosses the P-c cut" (K-c). The question has five parts:
- what formal requirements do those uses put on a counting principle, and what is the normative status of each requirement?
- which of M, K and C meets them, with T3 as written as the reference point?
- where does each fail, with a proof or a concrete counterexample, and from which root cause?
- what does each imply for E3-REL and the ratified host lists?
- does the evidence support "single principle" as a criterion?

### 1.2 Formal definitions

Notation follows KC-CONVENTIONS r1.1 §2.1.

- **Row:** a T2 r1.4 row x, R1–R83. txt(x) is its statement cell.
- **Placement:** the concept table of T3 §1a.
  - CB1 = {C13–C18, C23, C24, C27}.
  - CB23 = {C2–C12, C19–C21}.
  - C1 spans both sides (VP12 (i)).
  - C22, C25, C26 and C28 are on no side.
- **Principle:** a principle Π assigns to each row a set ρ_Π(x) of T1 concepts and an actor flag a_Π(x) ∈ {none, N, R, B, U}.
- **Crossing:** take a cut (A, B) and a spanning rule s ∈ {S, S′, S″}. Row x crosses iff ρ_Π(x) ∪ span_s(a_Π(x)) meets both A and B. K-c is the case (A, B) = (CB1, CB23).
- **Verdict:** v_Π,s(x) ∈ {X, CB1, CB23, none}.
- **E3-REL_s:** the T-A rows with verdict X, minus R12, R13 and R23 (hosted at MW1) and R24 (hosted at CV4) (E3 r1.3 lines 111–114).
- **Logical forms** (the analyst's classification [E]):
  - **H (history-level formula):** a formula about one history, such as F1, a typing or definition row, or a Def 3.1 clause.
  - **Π (universal statement over histories):** ∀h ∈ 𝒦 φ(h), or a ∀∃ prefix over histories. Examples: Cor 4.3; ambiguity, Def 5.1; image equality, P16.
  - **Σ₁ (possibility claim):** a statement equivalent to ∃h₁ … ∃hₙ ∈ 𝒦 θ, with θ free of history quantifiers.
  - **N(Γ, ψ) (non-entailment):** "Γ does not require ψ". It is equivalent to ∃h ∈ Mod(Γ) ¬ψ(h), so it is Σ₁.
  - **Slot clause:** the formula that activating a T-B row would add.

**Principles compared.**
- **T3:** rules 1–5 as written, with FZ where T3 is silent.
- **M, K and C:** as defined in KC r1.1 §2.1 and encoded in `kc.py`. C comes with four guard parses.
- **New [E] candidates** (§6.2), additions beyond F-LOG-0302: K-T, U and C-Σ.

### 1.3 Source-backed premises (quoted, with locations)

| id | premise | source |
|---|---|---|
| P1 | "A T2 row **crosses** the P-c cut iff its statement … reads **at least one item placed on CB1 and at least one item placed on CB23**" | T3 r1.9 §1a rule 1 (line 66) |
| P2 | "A **guard row** (T-A guard form: R11, R14–R17, R21) is judged by the items whose tie or order it denies" | T3 rule 1 (line 66) |
| P3 | "A **T-B row** is judged by its clause text only" | T3 rule 1 (line 66) |
| P4 | "Negations ("with no Cap condition", "no Cap or status condition") are not read, as for R79 and R68" | T3 rule 2 [r1.7] (line 68) |
| P5 | "A row reading no placed item (an absent clause …) is "none""; for R-i′, "**none** (R80 states no clause)" | T3 rule 5 (line 76); T3 §1a (d) (line 265) |
| P6 | "A derived predicate named as a concept (Adopted, C10) and Cap named alone (C2) count at their own placement and are not read through their definitions" | T3 rule 2 [r1.7] (line 68) |
| P7 | "a row that refers to other T2 rows … is assessed by the items its own statement reads, not through those rows" | T3 rule 4 (line 75) |
| P8 | R14–R17 "T-A (non-constraint; guard form)"; R11 "T-A (guard form)"; R21 "T-A (guard form; modelling choice)" | T2 r1.4 lines 63, 66–69, 73 |
| P9 | "A row that names an N-item as **guard** prevents it and asserts nothing about it" | T2 r1.4 §0 line 43 |
| P10 | "Its guard is R14 (T-A)" (R43); "… R21" (R46); "… R11" (R47); "… R17" (R48); "… R16" (R49) | T2 r1.4 lines 110, 113–116 |
| P11 | "Guard-form rows R14–R17 are absences of constraints"; R14: "Nothing to check: it is the absence of a constraint" | T2 r1.4 §6 line 166; line 66 |
| P12 | "T-A rows that name N1, N2, N3, N4, N7 do so as **guards** (R2, R3, R9, R11, R14–R18, R21, R24)" | T2 r1.4 §6 line 172 |
| P13 | "The base frame has no other axiom. In particular it does not require: that every act or work event is referenced by some record …; that a norm value implies or excludes any act or work event …; any order between r, d and f, apart from F2" | R4 r3.1 Def 1.8 (lines 107–117) |
| P14 | "It never equates f with r or with d" | R4 Def 1.9 |
| P15 | Def 1.10 "Four derived notions" (Adopted, F̂, Chg, Violation); Def 3.1 clauses; Def 3.2 classes | R4 Def 1.10, 3.1, 3.2 |
| P16 | "If O′(H-D′ ∩ R_B′) ≠ O′(H-B′), then O′ depends … on σ or on f" (separation is image inequality) | R4 Cor 4.3 (line 320) |
| P17 | Hosting: the per-reading host lists are ratified; "The ratified lists are therefore a ratified output of an unratified method"; "A revision of the K-c criterion or rule 2a reopens the lists" | E2b r1.3 §A2.2 (lines 583–584); §A2.7 (line 634) |
| P18 | E3-REL: "the set of T-A rows that E2b §A1.3 states … as "hosted on both CB1 and CB23""; "A crossing records which items a row's statement reads"; offered only as "the rows the two modules share" | E3 r1.3 §2.3 (lines 111–115) |
| P19 | "R14, R15 and R16 are guard rows, the absence of a constraint" | E3 r1.3 §2.3 (line 116) |
| P20 | Cut evaluation: "No 9│14 cut has strictly fewer crossings under FZ and its variants" | F-GOVERNANCE-LOG line 4153 (F-LOG-0299, from line 4140) |
| P21 | "neutrality holds under 𝐑₀ only (asymmetric under R-i and R-i′)" | F-LOG-0265 (log line 3337) |
| P22 | RESEARCH-AUDIT-1 F2 "**holds.** It is substantive and the same under S, S′, S″, P-d and the coordinate-level criterion" (line 63); F-LOG-0292 "The R-i/R-i′ record-clause asymmetry is real under every convention" (log line 3970) | as stated |
| P23 | **(r1, F11)** T3 itself says only that R80 "states no clause" (P5). The wording "R80 is the *absence* of H-B's record clause, not a guard denying a tie. Reading the absent clause would make "H-B has no record clause" count as a record crossing" is the **AUDIT-Q6-TEST1 encoder's gloss** on T3 rules 1 and 5 (F11). It is not T3's text | AUDIT-Q6-TEST1 r1 line 74 |
| P24 | "A crossing records which items a clause's text reads, not whether the clause is true"; a crossing "is not a boundary relation" | T3 §1a (g) (line 296) |

### 1.4 Assumptions (not source-backed; analyst [E])

- **A-i.** Each statement, or part of a statement, is classified by logical form (§1.2). This is the analyst's reading of natural-language cells.
- **A-ii.** An exceptive "no X except Y" asserts Y. Only C-Σ's verdict at R15 uses this.
- **A-iii.** Unfolding a definition (Def 1.10, 3.1, 3.2, 4.9, 5.1) preserves content. This is standard for eliminable definitions, but **T3 rule 2 (P6) stipulates the opposite for K-c**.
- **A-iv.** The reworded statements in §3 are the analyst's, and so are their encodings, which follow FZ conventions.
- **A-v.** Fit to purpose is read from how the records *use* crossings (P17–P21). No record states the purpose of K-c.

---

## 2. Requirements derived from the uses

### 2.1 The four uses

| use | what it consumes | source |
|---|---|---|
| U1 hosting | a per-row verdict on T-A and T-B rows; X = "hosted on both" | P17 |
| U2 E3-REL | the T-A rows with verdict X (minus MW1 / CV4) | P18 |
| U3 cut evaluation | the crossings of one ρ over all 817,190 9 │ 14 cuts and under S / S′ / S″ | P20 |
| U4 asymmetry | the per-clause verdicts of the H-D and H-B clause rows | P21, P22 |

### 2.2 Requirements and their normative status (r1, F2)

Status codes:
- **[U]**: follows from the uses.
- **[E-s]**: a stipulation that a source supports, with that source's limits stated.
- **[E]**: the analyst's stipulation.

Only Rq6 and Rq8 are [U] (r1.1, H6).

| id | requirement | status | what supports it, and its limits |
|---|---|---|---|
| **Rq1 Determinacy** | ρ(x) is fixed by txt(x), R4 and the T1 placement, with no row-specific label and no free parse choice beyond the shared encoding step (A-i) | **[E]** | P17 calls for a method that fixes its output. But an enumerated rule such as T3 rule 1's guard list is determinate too, so "no enumeration" **does not follow from P17**. That part is the analyst's addition. **Grading rule (r1.1, H1), applied to every principle from `adhoc.out`:** ✓ = no choice point moves any row; ~ = some choice points move rows, but none moves E3-REL under S or S′; ✗ = some choice point moves E3-REL. A dependence on a T2 label counts through its choice point like any other |
| **Rq2a Definitional invariance** | statements that differ only by unfolding an R4 definition get the same ρ | **[E]** | It is supported by R4 calling these "derived notions" (P15) and by U3: a reworded definition should not change cut comparisons. It **contradicts T3 rule 2 (P6)**, and R4 does not entail that K-c must read through definitions |
| **Rq2b Meta-level invariance** | N(Γ, ψ) and its Σ₁ form ∃h ∈ Mod(Γ) ¬ψ(h) get the same verdict | **[E]** | The equivalence is pure logic. Requiring K-c to respect it **turns C's own distinction (asserted vs not asserted) into a test of C**, so it targets C by construction |
| **Rq3 Polarity coherence** | statements of the same logical form are treated alike: N(Γ, ψ) contributes ψ either always or never | **[E]** | Supported by P1's single verb "reads". **Whether "same form" spans T-A frame statements and T-B slots is a stipulation** (choice (b) in §2.4) |
| **Rq4 Compositionality** | clause names unfold; row references are read always or never, by one rule | **[E-s]** | P7 and T3 §1a (e) |
| **Rq5 T-B slot functionality** | a T-B row's verdict is a function of its slot clause | **[E-s]** | Directly T3's text (P3). **Selective use disclosed:** T3's P6 and P7 are not promoted to requirements in the same way |
| **Rq6 Separability** | ρ does not depend on the cut or the spanning rule | **[U]** | U3; F-LOG-0271 |
| **Rq7h Hosting relevance** | a T-A row that delimits a tie between items on both sides is hosted on both | **[E-s]** | P17, P18, P24 |
| **Rq7c Constraint coupling** | a row crosses only if a constraint it carries couples the sides | **[E-s]** | P11, P19, U3. **(r1, F12)** Rq7c is evaluated **only on the guard rows**. Applied literally to definition and typing rows (R9 and R22 under every reading; R5, R6, R8, R10 and R19 under S only), it would fail under every principle, because they cross through what they type or define and no coupling constraint is in view. So it is a criterion **about guard rows**, not a general one |
| **Rq9 Guard / T-C pairing coherence** (r1, F5) | a guard row and the T-C row that T2 says it guards get the same P-c verdict | **[E]** (r1.1, H6) | P9, P10: T2 **pairs** each guard with the formula it keeps out, but it does **not require** the two rows to get the same verdict; that is the analyst's step. Rq9 largely re-encodes 7h on the guard rows (one position, not an extra support). FZ against FZ (KC G8), so this is not independent evidence |
| (Rq8 Family neutrality) | the rule mentions neither H-D nor H-B | [U] | every principle passes |

7h and 7c conflict on R14, R16 and R21, so no principle passes Rq7 as a whole. They are reported separately and **not** used to rank.

### 2.3 Theorem 1: invariance modulo a class is incompatible with the ratified uses (r1, F7)

**Theorem 1.** Let Π be invariant under equivalence over a class 𝒦 of histories. Then every row whose content is 𝒦-valid gets one and the same verdict, the verdict of ⊤.

*Proof.* Any two 𝒦-valid contents are 𝒦-equivalent to ⊤, and so to each other. ∎ (No premise about essential vocabulary is needed; r0's premise is dropped.)

**Case 𝒦 = 𝓗 (premise narrowed to the F-axioms; r1.1, H4).**
- The F1 part of R1 is the axiom F1 (§3d classes R1 as H + a Σ₁ part; r1.2, J4), and R2 is the reference part of F2. Both are axioms of the base frame (Def 1.8), so both are 𝓗-valid. No claim about the other T-A rows is needed.
- Taking R1's content as its F1 part (its Σ₁ part contributes nothing to the 𝓗-valid content either way), invariance would give R1 and R2 the same verdict. The ratified lists give R1 CB23 and R2 X under every reading (`mkc.out` §4). Contradiction.

**Case 𝒦 = 𝓢 (all typed histories, Def 1.7).**
- The typing rows R4 and R5 are 𝓢-valid, because a history is a typed tuple. R22 is built into Def 1.7a.
- Under S, R4 is CB23 and R5 is X. Under S′, R4 is CB23 and R22 is X. Contradiction under every reading.

**Consequence.**
- Every usable principle reads statements **as stated**, at a stipulated level of description.
- Invariance can only be required for named transformations (Rq2a, Rq2b), and **which transformations to require is a choice**.
- So a "single principle" is single only relative to a chosen normal form.

### 2.4 Theorem 2 (conditional): when Rq3, Rq5 and Rq7h conflict (rewritten in r1, F1)

**The four choices.**
- **(a)** R80 is classified as a non-entailment N(Cl(H-B^{R-i′}), ψ′), where ψ′ is a record-reference clause. Note that R80 is an **unasserted** T-B option, while R14 and R16 are **asserted** T-A non-entailments; the classification sets that difference aside.
- **(b)** Rq3's "same logical form" spans row types (T-A frame statements and T-B slots).
- **(c)** Rq7h covers T-A rows only. If it also covered R79 and R80 (which delimit the B-side record tie), Rq5 and Rq7h would conflict on their own, without Rq3.
- **(d)** The Rq5 witness R80′ ("R-i′: the third conjunct of H-B is ⊤; E ≠ ∅ holds jointly") counts as **N-free**. Treating a closed clause list as free of non-entailment content is itself a meta-level equivalence, which Theorem 1 shows to be a choice.

- **(e)** (r1.1, H3; stated, not a choice) An N-free positive clause is read by the items it names: P1 ("reads"), with clause names unfolded (Rq4). This is what fixes R80′'s reading at {C8, C9}.

**Theorem 2.** Given (a)–(d), and (e), Rq3, Rq5 and Rq7h cannot all hold.

*Proof (witness R14, a pure Σ₁ statement).* r1 replaced R16 as witness because the review read R16's second clause ("absence is NOT-EVIDENCED-IN-CAPTURE, not non-occurrence") as positive. §3d classes R14, R16 and R17 all as Σ₁-only; reading R16's second clause as a C23 classification is a C-Σ choice point (§5.2). R14 is used because its Σ₁-only status does not depend on that choice (r1.1, H4).
1. R14, "the frame never equates f with r or d … f may coincide with d or r in some histories", is N(Ax(𝓗), f = r/d): a pure Σ₁ statement. It delimits a tie between f, d (CB23) and r (CB1), so Rq7h requires R14 to cross.
2. Under (b), Rq3 then requires every non-entailment to contribute its subject. By (a), R80 is a non-entailment, so it reads ψ′ (records, Ref, acts) and crosses.
3. R80′ has the same slot clause as R80. By (d) it is N-free, and by (e) it reads only the items it names, {C8, C9}, so it is CB23. Rq5 requires R80 to get R80′'s verdict, which is not X.

Contradiction. ∎

**Counterexample principle (without (a) or (b)).** Let a T-B row's logical form be its slot clause, and let frame non-entailments in T-A statements contribute their subject. Then:
- Rq3 holds, because no T-B row has N-form;
- Rq5 holds, because each T-B row is its clause;
- Rq7h holds, because R14, R15, R16 and R21 cross.

It is K-T (§6.2). **Its encoding in `mkc.py` equals K's by construction, row for row** (`mkc.py` §3a2 prints an empty diff); only the definition above differs from K's. **Its outputs here, and so the counterexample's Rq3, Rq5 and Rq7h results, are K's results relabelled, not a second computation** (r1.2, J1). That K's encoding satisfies this definition is the analyst's reading [E], not a check. **So Theorem 2 is not an impossibility for every principle.** It shows that T3's A1 (KC F11) is coherent exactly when (a) or (b) is rejected, and that M / U and C / C-Σ accept (a) and (b) in different ways.

(`mkc.out` CE-form-1 shows the R16 / R80 instance: X vs none under T3 and K.)

---

## 3. Test 1: each principle against each requirement

Each counterexample is a concrete pair of statements, encoded under FZ conventions (A-iv). The column "cuts" counts the 9 │ 14 cuts, out of 817,190, on which the two statements get different S′ verdicts. That is the effect on U3.

### 3.1 Counterexamples (r1, F6: CE-def-1, CE-def-2 and the CE-slot witnesses corrected)

| id | statement A | statement B (equivalent rewording [E]) | principle(s) | A verdict, S / S′ | B verdict, S / S′ | cuts |
|---|---|---|---|---|---|---|
| **CE-def-1** | R27 "f(a) < ∞ ⇒ Adopted(a, f(a))" | the same with Adopted unfolded by Def 1.10: {C2, C8, C9, C10, C11, C12} + actor | T3, M (P6) | CB23 / CB23 | **X** / CB23 | 181,730 |
| **CE-def-2** | R34 "some w ∈ W conforms" (M reads it at its own placement) | "some w ∈ W has σ(t(w))(τ(w)) ∈ P" (Def 1.10): {C3, C4, C6, C19, C20, C21} | M | CB23 / CB23 | CB23 / CB23 | 385,220 |
| **CE-def-3** | R33 "every work event conforms", read by the text of T3 rule 2 | R32 (T2: R33 is "formally identical to B-conf") | T3 (text) | CB23 / CB23 | CB23 / CB23 | 79,016 |
| **CE-meta-1** | R14 | R14′ "some history of 𝓗 has an issue act with f(a) ≠ d(a), and one with a record constituting a with f(a) ≠ r(ρ)" | C (K1c) | none / none | **X / X** | 805,630 |
| **CE-meta-2** | R16 | R16′ "some history of 𝓗 has an act or work event referenced by no record" | C (K1c) | none / none | **X / X** | 765,510 |
| **CE-meta-3** | R17 | R17′ "some history of 𝓗 has a work event whose occurrence is not fixed by σ" | C (K1c) | none / none | CB23 / CB23 | 713,184 |
| **CE-slot-1** | R79 "H-B requires only that some act exists, not that a record references it" | R79′ "R-i: the third conjunct of H-B is E ≠ ∅" (names no record) | M, U | **X / X** | CB23 / CB23 | 306,204 |
| **CE-slot-2** | R80 "H-B carries no record requirement; "acts exist" … joint" | R80′ "R-i′: the third conjunct of H-B is ⊤; E ≠ ∅ holds jointly" (names no record) | M, U | **X / X** | CB23 / CB23 | 306,204 |
| **CE-slot-3** | R26 "… with no Cap condition" | R26's formula alone | M, U | CB23 / CB23 | CB23 / CB23 | 13,430 |
| **CE-form-1** | R16 (frame non-entailment) | R80 (slot, read as a non-entailment under (a)). A same-form pair, **not** a rewording | T3, K | X / X | none / none | 792,200 |

**Status of the pairs.**
- CE-def-1 to CE-def-3 rest on A-iii.
- CE-meta-1 to CE-meta-3 use pure logic.
- CE-slot-1 to CE-slot-3 rest on P3, which makes each pair share its slot clause.
- CE-form-1 tests Rq3 only under choices (a) and (b).

### 3.2 Guard / T-C pairing test (r1, F3a, F5; `mkc.out` §3b)

The test compares each guard row with the T-C row that T2 pairs with it (P10). R15 / R42 is inferred via X5 (KC G8), and R16 / R41 via X25. Each cell gives the guard's S′ verdict / the T-C row's S′ verdict, with the number of cuts on which they differ.

| pair | source | T3 | M | K, K-T | U | C (K1c) | C-Σ |
|---|---|---|---|---|---|---|---|
| R14 / R43 | T2-stated | X / X (0) | X / X (0) | X / X (0) | X / X (0) | none / X (610,470) | none / X (610,470) |
| R16 / R49 | T2-stated | X / X (26,690) | X / X (26,690) | X / X (26,690) | X / X (26,690) | none / X (765,510) | none / X (765,510) |
| R16 / R41 | **inferred** (via X25) | X / X (0) | X / X (0) | X / X (0) | X / X (0) | none / X (792,200) | none / X (792,200) |
| R17 / R48 | T2-stated | CB23 / CB23 (208,420) | same (208,420) | same (208,420) | same (208,420) | none / CB23 (610,470) | none / CB23 (610,470) |
| R21 / R46 | T2-stated | X / X (203,490) | X / X (203,490) | X / X (203,490) | X / X (203,490) | CB1 / X (406,980) | CB1 / X (406,980) |
| R15 / R42 | **inferred** (via X5) | X / X (398,650) | X / X (358,530) | X / X (398,650) | X / X (398,650) | CB23 / X (406,980) | X / X (398,650) |
| R11 / R47 | T2-stated | CB23 / CB23 (195,160) | same (195,160) | same (195,160) | same (195,160) | CB23 / CB23 (310,080) | CB23 / CB23 (208,590) |
| **P-c agreement: all 7 / the review's 6 / T2-stated 5** | | **7 / 6 / 5** | **7 / 6 / 5** | **7 / 6 / 5** | **7 / 6 / 5** | **1 / 0 / 1** | **2 / 1 / 1** |

(r1.1, H5.) Cells: guard S′ verdict / T-C S′ verdict, with the number of 9 │ 14 cuts on which the two differ in brackets.
- On the five T2-stated pairs, C and C-Σ agree only at R11 / R47 (1 / 5). C-Σ splits 5 of the review's 6 pairs, involving 4 distinct guard rows: R14, R16, R17 and R21.
- T3, M, K, K-T and U agree on every pair **on P-c only**. On other cuts their guard and T-C rows differ on 26,690 to 398,650 cuts for 5 of the 7 pairs (0 for R14 / R43 and R16 / R41).
- This is **FZ against FZ**: a consistency check, not independent evidence (KC G8).

### 3.3 Requirement by requirement, by root cause (r1, F2)

| principle | requirement failed | root cause | evidence |
|---|---|---|---|
| **T3** | Rq1 (by enumeration) | **A1**: the guard list in rule 1 | `adhoc.out`: flipping the list moves 6 rows; E3-REL 9 / 4 |
| | Rq3 (only under (a) and (b)) | **A1** | CE-form-1 |
| | Rq2a | rule 2 own-placement (P6) | CE-def-1 (P-c, under S); CE-def-3 (other cuts) |
| | Rq1 (minor) | A3, Cap vs Ref | `adhoc.out` |
| | passes | — | Rq2b on T-A guard rows, Rq4, Rq5, Rq6, Rq7h, Rq9 |
| **M** | Rq2a | the mention level | CE-def-1, CE-def-2 |
| | Rq5 | polarity-blind reading of slot glosses | CE-slot-1, -2 (P-c); CE-slot-3 (cuts only) |
| | passes | — | Rq1, Rq2b, Rq3, Rq4, Rq6, Rq7h, Rq9 |
| **K** | Rq1 | guard role taken from the T2 "guard form" label | `adhoc.out`: removing the label moves 5 rows; E3-REL 9 / 4 |
| | Rq3 (only under (a) and (b); passes if either is rejected) | the row-type distinction | CE-form-1 |
| | Rq2b (qualified as for T3, r1, F9) | — | passes on the **labelled** guard rows. For an unlabelled non-entailment, the verdict depends on the role assigned |
| | passes | — | Rq2a, Rq4, Rq5, Rq6, Rq7h, Rq9 |
| **K-T** (r1.1, H2; r1.2, J1, J2: K's results relabelled) | Rq1 (~) | the tier (a T2 label) and K's R80-role and R79-contrast choices; the A-i parse choices R15 exception, R21 typing part, R11 F1 restatement, R16 NOT-EVIDENCED move no row; none moves E3-REL | `adhoc.out` |
| | Rq3 (only under (a) and (b); passes if either is rejected) | the row-type distinction, **as for K** (identical verdicts) | CE-form-1 |
| | Rq2b (~) | passes on frame statements; for an unasserted T-B option the row type decides | — |
| | passes | — | Rq2a, Rq4, Rq5, Rq6, Rq7h, Rq9 (`mkc.out` §3e: K's rows relabelled) |
| **C (K1c)** | Rq1, Rq2b, Rq3 | **one root cause: C's clause "a part that asserts no formula about histories contributes nothing" is undefined for statements about classes of histories** | Rq1: four parses. Rq2b: CE-meta-1 to -3. Rq3: KC's encoding reads R23, R24, R66–R70 and R82 (class-level) but not R14, R16 or R17. Applied to all eight, they move X → none (`adhoc.out`; no effect on E3-REL) |
| | Rq4 | a **KC encoding slip**: K1c ignores R15's "(R3)", against C's own row-reference rule | C-R15ref is the compositional C |
| | Rq7h, Rq9 | — | §3.2 |

T3, M, K and K-T fail 7c; C (K1c) fails 7h instead.

**U and C-Σ** (r1.2, J6): their marks and root causes are in §6.1 and §6.2. U fails Rq5 (CE-slot-1, -2) and 7c; Rq1 ~. C-Σ fails Rq1 (✗: the exceptive premise moves E3-REL), Rq9 and 7h; it passes Rq2a, Rq2b, Rq3 (given A-i), Rq4 (given A-ii), Rq5 and Rq6 (`mkc.out` §3e). Every principle passes Rq6.

KC r1.1 §4 says "Only C counts no denial anywhere". As encoded, that is not accurate: C reads the negative class-level statements of R66, R69 and R82.

---

## 4. Test 2: source evidence

### 4.1 Evidence by principle

| evidence | location | bears on | for / against |
|---|---|---|---|
| "judged by the items whose tie or order it denies" | P2 | guard rows | for T3, K, M, U; against C, C-Σ |
| "A T-B row is judged by its clause text only" | P3 | Rq5 | for T3, K, C, C-Σ; against M, U |
| "Negations … are not read" | P4 | contrasts | for T3, K, C, C-Σ; against M, U |
| own placement of derived predicates | P6 | definitional level | for T3, M; against K, C, U, C-Σ |
| row references not read through | P7 | Rq4 | for T3, M; against K, C, U, C-Σ |
| "R80 states no clause"; "absent clause … none" | P5 | R80 | for T3, K, C, C-Σ. **The "not a guard denying a tie" rationale is the AUDIT-Q6 encoder's gloss (P23), not T3's** |
| "T-A (non-constraint; guard form)" | P8 | guards as non-constraints | for C, C-Σ (7c) |
| "prevents it and asserts nothing about it" | P9 | "asserts nothing" points to C; "prevents" points to K | **conflicting within one sentence** |
| "Its guard is …" | P10 | Rq9 | for T3, K, M, U; against C, C-Σ (FZ against FZ) |
| R2, R3, R9, R18, R24 are also called guards | P12 | "guard" is not crisp | against relying on the label |
| "absences of constraints" | P11, P19 | 7c | for C, C-Σ |
| frame "does not require …" | P13 | the logical form of R14–R17 | neutral |
| derived notions | P15 | Rq2a | weakly for K, C, U, C-Σ (P6 says the opposite for K-c) |
| ratified lists | P17 | 7h; and T3's outputs | for T3, K. **This is output fidelity, not evidence about the principle** |
| "the rows the two modules share" | P18 | 7h | for T3, K, M, U |
| crossings compared over cuts | P20 | 7c | for C, C-Σ |
| a crossing records what is read, "not a boundary relation" | P24 | reading, not coupling | for 7h |
| R-i / R-i′ asymmetry "real under every convention" | P22 | U4 | consistent with T3, K, C, C-Σ; contradicted by M, U. These records tested only conventions with K5 (a), so this is not independent evidence |

### 4.2 Weighing [E]

**Conflicts.**
1. **The guard-row purpose: 7h with Rq9, against 7c.**
   - For 7c: T2's tier column and its §6 (P8, P11), and E3's own text (P19), call guard rows absences of constraints.
   - For 7h and Rq9: T3 rule 1 (P2), T2's pairing column (P10) and the hosting (P17, P18) treat them as relevant to both sides.
   - P9 contains both readings.
   - **Unsettled.** This question is entangled with C-γ (KC F4).
2. **The definitional level.** T3 rule 2 (P6) and R4's "derived notions" (P15) point in opposite directions. R4 does not say what K-c should read.
3. **R80 vs the guard rows.** T3 states only "R80 states no clause" (P5). The row-type rationale is the encoder's gloss (P23). No source discusses the shared logical form.
4. **Output fidelity.** The ratified lists (P17) and the asymmetry records (P22) were produced under T3's conventions, so agreement with them is agreement with T3.

**Net [E].** Source evidence alone does not single out M, K or C.

---

## 5. Test 3 and Test 4: all 83 rows, and E3-REL

### 5.1 Rows where any principle differs from T3 (`mkc.out` §2)

| row | tier | T3 | M | K | C (K1c) | U | C-Σ | under |
|---|---|---|---|---|---|---|---|---|
| R14 | T-A guard | X | X | X | none | X | none | S, S′ |
| R15 | T-A guard | X | X | X | CB23 | X | X | S, S′ |
| R16 | T-A guard | X | X | X | none | X | none | S, S′ |
| R17 | T-A guard | CB23 | CB23 | CB23 | none | CB23 | none | S, S′ |
| R21 | T-A guard | X | X | X | CB1 | X | CB1 | S, S′ |
| R27, R28 | T-B | CB23 | CB23 | X | X | X | X | S only |
| R79 | T-B | CB23 | X | CB23 | CB23 | X | CB23 | S, S′ |
| R80 | T-B | none | X | none | none | X | none | S, S′ |
| R45, R52 | T-C | X | CB1 | X | X | X | X | S, S′ |
| R56, R60, R61 | T-C | CB23 | CB23 | X | X | X | X | S only |
| R62 | T-C | CB1 | CB1 | X | X | X | X | S, S′ |
| R71 | T-D | X / CB23 | X / CB23 | X / X | X / X | X / X | X / X | differs under S′ |
| R75 | T-D | none | none | X | X | X | X | S, S′ |

- All other rows agree under every principle.
- S′ = S″ on every row, under every principle.
- K-T's verdicts equal K's.

### 5.2 Ad hoc choice points, graded uniformly (`adhoc.out`; r1, F3b, F8)

A choice point is a place where the principle's definition, applied to the text, does not fix ρ. The table counts every choice point that moves a row, and grades Rq1 by the rule in §2.2 (r1.1, H1). **Rq1 grades the principle's definition, not its encoding** (r1.2, J2): the choice points are those the definition leaves open, each flipped on the principle's encoding to measure its effect.

| principle | choice points listed / that move any row / that move a T-A or T-B row | the choices that have an effect | E3-REL effect | Rq1 grade |
|---|---|---|---|---|
| T3 | 7 / 4 / 2 | A1 (guard list); A1 in reverse (R79, R80 → X); A3 (2 directions) | A1: 13 / 8 vs 9 / 4 | ✗ |
| M | 3 / 0 / 0 | none | none | ✓ |
| K | 4 / 4 / 4 | guard label; R80 role (2 ways); R79 contrast | label: 13 / 8 vs 9 / 4 | ✗ |
| **K-T** | 7 / 3 / 3 | **the tier** (still a T2 label) decides frame statement vs slot (R80 → X if it is a frame statement); R80 role (VC8); R79 contrast vs subject. **r1.2 (J2):** the four A-i parse choices that count against C-Σ (R15 exception, R21 typing part, R11 F1 restatement, R16 NOT-EVIDENCED) are enumerated for K-T and **move no row** | none | ~ |
| C (K1c) | 5 / 5 / 5 | guard parse (3 ways); R80 VC8; class-level clause | 9 / 4, 10 / 5, 11 / 6 | ✗ |
| U | 1 / 1 / 1 | R80's joint "acts exist" | none | ~ |
| **C-Σ** | 6 / 5 / 5 | R15 exceptive (A-ii); R21 typing vs Σ₁; R11 read vs nothing; R16 "NOT-EVIDENCED" as C23; R80 VC8. (R11's F1 restatement: no effect) | **exceptive: 10 / 5 vs 9 / 4**, the same kind of effect as K's label | ✗ |

**Reading.**
- **M's polarity blindness** is coherent, and M needs no choice that moves a row.
- **K's label dependence** reduces to a dependence on the tier under K-T, but K's R80 and R79 choices remain.
- **C's parse range** (9 / 4, 10 / 5, 11 / 6) comes from its undefined class-level clause.
- **C-Σ narrows C's range only by adopting the exceptive premise.** This is a choice. **r0's claim "derived, not chosen" is withdrawn (F3e).**
  - **Encoding level:** C-Σ differs from C (K1c) at R11 and R15. **Verdict level:** only R15 changes a verdict (r1.1, H8).
  - At the encoding level it differs from C-R15ref only at R11, which keeps the same P-c verdict but differs on 181,730 cuts. **r0 encoded R11 as {3, 4, 6}. By C-Σ's own rule, R11's F1 restatement is read, giving {3, 4, 5, 6, 8, 12} (F3d).**

### 5.3 E3-REL and accepted outputs (F-LOG-0297)

| principle | E3-REL, S | E3-REL, S′ / S″ | under the other D12 reading | change against 13 / 8 |
|---|---|---|---|---|
| T3, M, K, K-T, K-VC8, U | 13 | 8 | 14 / 9 | none |
| C (K1c), C-SH | 9 | 4 | 10 / 5 | −R14, R15, R16, R21 |
| C-R15ref, **C-Σ [E]** | 10 | 5 | 11 / 6 | −R14, R16, R21 |
| C-R15ref-R14 | 11 | 6 | 12 / 7 | −R16, R21 |

**Accepted outputs that change** (E3 r1.3 sites, as in KC §3.7):
- **T3, M, K, K-T, U:** E3-REL and the T-A placement are unchanged.
  - K and U change E3 §6: R27 and R28 cross under S. They also change the §2.5 slot rows and the 83-row totals (K 53 / 39; U 55 / 41).
  - M and U change §6 for R79 and R80, and the asymmetry rows.
- **C-Σ:**
  - E3-REL becomes 10 / 5, so the F-LOG-0297 acceptance would need a new act.
  - The T-A placement gains a "none" category (R14, R16, R17).
  - E3-EV3, "R14 crosses (all readings)", no longer holds.
  - R15 still crosses groupings, so C-γ is not emptied (KC G1).
  - The 83-row totals become 50 / 36.

### 5.4 Host-list differences from the ratified E2b §A1.3 / §A1.4 lists

| principle | S | S′ / S″ |
|---|---|---|
| T3 | identical | identical |
| M | R79 → X; R80 → X | same |
| K, K-T | R27, R28 → X | identical |
| U | R27, R28, R79, R80 → X | R79, R80 → X |
| C (K1c) | R14, R16, R17 → none; R15 → CB23; R21 → CB1; R27, R28 → X | as under S, without R27 and R28 |
| C-Σ | R14, R16, R17 → none; R21 → CB1; R27, R28 → X | R14, R16, R17 → none; R21 → CB1 |

- Any difference triggers the E2b §A2.7 reopen row "the K-c criterion or rule 2a revised" (P17).
- The S-only move of R27 and R28 also triggers "an S-only borderline applied".

### 5.5 Asymmetry records

| principle | change / status clauses at 𝐑₀, under S | under S′ / S″ | R-i / R-i′ record clauses |
|---|---|---|---|
| T3, M | H-D-asymmetric: R25 (R38 under R-j) | symmetric | T3: H-D-asymmetric. **M: symmetric** |
| K, K-T, C, C-Σ | widened to D-st: R25, R27 (R28 under R-d; R38 under R-j) | symmetric | H-D-asymmetric |
| U | widened to D-st, as K | symmetric | **symmetric** |

- Under M and U, the records in P22 would need restating. They tested only K5 (a) conventions, so this is a limit on their scope, not a refutation of them.
- Under every principle the asymmetry stays documented, unresolved and unaccepted (F-LOG-0271). None of this is evidence about H-D or H-B.

### 5.6 Cut evaluation (U3)

**Under S′ / S″.** On T-A + T-B, no 9 │ 14 cut has fewer crossings than P-c, under every principle:

| principle | P-c crossings / cuts strictly fewer / cuts equal (incl. P-c) |
|---|---|
| T3 | 17 / 0 / 1 |
| K, K-T | 17 / 0 / 1 |
| C (K1c) | 13 / 0 / 1 |
| U | 19 / 0 / 1 |
| C-Σ | 14 / 0 / 1 |
| M | 19 / 0 / 2 (the minimum is tied) |

**Under S.** P-c is not the minimum under any principle:

| principle | P-c crossings / cuts strictly fewer / cuts equal (incl. P-c) |
|---|---|
| T3 | 24 / 10 / 78 |
| M | 26 / 147 / 417 |
| K, K-T | 26 / 64 / 304 |
| C (K1c) | 22 / 46 / 234 |
| U | 28 / 469 / 1246 |
| C-Σ | 23 / 34 / 155 (r0: 23 / 53 / 283, before the R11 fix) |

**Rq2a failures change single-row verdicts on many cuts.** CE-def-1 to CE-def-3 differ on 79,016 to 385,220 of the 817,190 cuts. So the exhaustive rank under T3 or M depends on the definitional level at which rows are worded. At P-c this has not mattered.

---

## 6. Synthesis

### 6.1 Verdict matrix (r1: requirements graded by status; failures by root cause)

**Key.**
- ✓ passes; ✗ fails; ~ passes in a weakened or qualified form; c(ab) fails only under Theorem 2's choices (a) and (b), and passes if either is rejected.
- Confidence: **H** a proof or a computed counterexample; **M** depends on the A-i classification; **L** depends on a contested premise or purpose.

| requirement (status) | T3 | M | K | K-T [E] | C (K1c) | U [E] | C-Σ [E] |
|---|---|---|---|---|---|---|---|
| Rq1 [E], graded by the §2.2 rule (r1.1, H1) | ✗ A1 moves E3-REL (H) | ✓ no moving choice (M) | ✗ label moves E3-REL (H) | ~ 3 moving choices (tier; R80; R79), none moves E3-REL (H) | ✗ parse moves E3-REL (H) | ~ 1 moving choice (R80), no E3-REL effect (M) | ✗ exceptive moves E3-REL (H) |
| Rq2a [E], contradicts P6 | ✗ (H) | ✗ (H) | ✓ (H) | ✓ (H) | ✓ (H) | ✓ (H) | ✓ (H) |
| Rq2b [E], C's own distinction | ~ on labelled guards (H) | ✓ (H) | ~ on labelled guards (H) | ~ on frame statements (M) | ✗ class-level clause (H) | ✓ (H) | ✓ by construction (M) |
| Rq3 [E], spanning row types stipulated | c(ab) A1 (H) | ✓ (H) | c(ab) (H) | c(ab), as K (H) | ✗ class-level clause (M) | ✓ (H) | ✓ (M) |
| Rq4 [E-s] | ✓ | ✓ | ✓ | ✓ | ✗ encoding slip (H) | ✓ | ✓ (L: A-ii) |
| Rq5 [E-s], P3 used selectively | ✓ (H) | ✗ (H) | ✓ (H) | ✓ (H) | ✓ (H) | ✗ (H) | ✓ (H) |
| Rq6 [U] | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| Rq7h [E-s] | ✓ (L) | ✓ (L) | ✓ (L) | ✓ (L) | ✗ (L) | ✓ (L) | ✗ (L) |
| Rq7c [E-s], guard rows only | ✗ (L) | ✗ (L) | ✗ (L) | ✗ (L) | ✓ (L) | ✗ (L) | ✓ (L) |
| Rq9 [E], ≈ 7h on guard rows; FZ against FZ; P-c only (all 7 / review 6 / T2-stated 5) | ✓ 7 / 6 / 5 (M) | ✓ 7 / 6 / 5 (M) | ✓ 7 / 6 / 5 (M) | ✓ 7 / 6 / 5 (M) | ✗ 1 / 0 / 1 (M) | ✓ 7 / 6 / 5 (M) | ✗ 2 / 1 / 1 (M) |
| **root causes of failure** | A1; P6 | mention level; polarity on slots | label (reduces to tier); row type | tier; R80 / R79 roles | undefined class-level clause; one encoding slip | polarity on slots | exceptive premise; reading guards as non-constraints |
| host cells departing from T3 (S; S′) | 0; 0 | 2; 2 | 2; 0 | 2; 0 | 7; 5 | 4; 2 | 6; 4 |
| E3-REL | 13 / 8 | 13 / 8 | 13 / 8 | 13 / 8 | 9 / 4 – 11 / 6 | 13 / 8 | 10 / 5 |

### 6.2 Does any principle satisfy every requirement? Repairs [E] (r1, F1, F3, F8, F10)

**No principle evaluated here satisfies every requirement** (§6.1). The 7h / 7c conflict alone means none passes Rq7 as a whole.
- **This does not show that no principle could.** Theorem 2 is conditional (§2.4). Its counterexample principle, K-T, has these marks (r1.1, H2): **~ Rq1** (tier; R80 and R79 choices), Rq2a ✓, **~ Rq2b** (frame statements), **Rq3 conditional** (passes if (a) or (b) is rejected; the same presentation as K, whose verdicts it shares), Rq4 ✓, Rq5 ✓, Rq6 ✓, Rq7h ✓, Rq9 ✓, and it **fails Rq7c**.
- **r0's "every candidate gives up exactly one" is withdrawn (F10).** C (K1c), for example, fails Rq1, Rq2b, Rq3, Rq4, Rq7h and Rq9.

**The candidates below are additions by the analyst. They are offered to locate failures, not as options for adoption.**

| candidate | definition | where it stands |
|---|---|---|
| **K-T** | Each statement contributes according to its role:<br>- T-A: a statement's non-entailment parts about the frame 𝓗 contribute their subject formula; the other parts contribute their formula after unfolding definitions;<br>- T-B: the row contributes its slot clause;<br>- T-C: the row contributes the prohibited formula;<br>- T-D: the row contributes the formula asked about.<br>Row references are read through | **Encoding equals K's by construction, row for row; its verdicts and marks are K's results relabelled (r1.2, J1).** It replaces the "guard form" label by **the tier**, which is still a T2 label. K's R80-role and R79-contrast choice points remain (F8). Marks: ~ Rq1 (J2: the A-i parse choices move no row), ~ Rq2b, Rq3 conditional (as K), fails Rq7c |
| **U** | K's level, with every denied or non-required subject contributing its signature (M's polarity) | Fails Rq5 (CE-slot-1, -2) and 7c. E3-REL 13 / 8. R79 and R80 → X. The R-i / R-i′ asymmetry disappears |
| **C-Σ** | Fully specified in r1 (F3c):<br>(1) **equivalence** is taken modulo pure logic and R4's definitions (P15), never modulo R4's axioms or frame validity (Theorem 1);<br>(2) every row is split into the parts of the table in `mkc.out` §3d, classed H, Π, Σ₁, exceptive, slot, prohibition (T-C) or question (T-D);<br>(3) **precedence:** ρ is the union of the contributions of the parts that are not Σ₁. A Σ₁ part contributes nothing. An exceptive contributes its exception (A-ii). A part counts as Σ₁ only if it is Σ₁ under (1);<br>(4) **coverage:** T-C rows read the formula as stated; T-D questions read the formula asked about; meta rows (R23, R24, R66–R70, R82) are Π, including R4's image equality (P16), and are read;<br>(5) row references are read through | **Rq1 ✗** under the uniform rule (5 moving choices; the exceptive premise moves E3-REL). Fails **Rq9** (1 / 5 on T2-stated pairs) and **7h**. Passes Rq2a, Rq2b, Rq3 (given A-i) and Rq5. E3-REL 10 / 5. It is not a derivation of C-R15ref: **at the verdict level** it differs from C only at R15, by a premise; at the encoding level, at R11 and R15 |

**The 83-row classification for C-Σ** is printed in `mkc.out` §3d, with each row's S and S′ verdict. Rows whose classification is not simply "H", "slot", "T-C formula" or "T-D formula":

| row | classification |
|---|---|
| R1 | H, plus a Σ₁ part ("says nothing about σ at other times") |
| R9 | H, plus Σ₁ ("con may be undefined everywhere") |
| R11 | H (typing and an F1 restatement), plus Σ₁ ("no frame axiom derives σ from E"). ρ = {C3, C4, C5, C6, C8, C12} |
| R12, R13 | Π, plus Σ₁ ("R4 asserts nowhere …"; "neither converse holds") |
| R14, R16, R17 | Σ₁ only. R16's "absence … not non-occurrence" is treated as Σ₁; reading it as a C23 classification is a choice point |
| R15 | Σ₁ ("no order"), plus an exceptive ("except d = r under constitution (R3)"), plus H (the typing of t(w)) |
| R21 | Σ₁ ("no axiom ties"; "not declared disjoint"), plus H ("a self-assertion is a value of c") |
| R23 | Π over pairs of histories (non-dependence: ∀h, h′) |
| R24 | Π∃ (ambiguity) |
| R66–R70, R82 | Π (meta) |
| R80 | slot with an empty clause; VC8 is a choice point |

### 6.3 Evidence on "single principle" as a criterion

**Established.**
1. Any principle reads statements at a stipulated level of description (Theorem 1). A "single principle" is single only relative to a chosen normal form.
2. Several single principles exist here: M, K-T, U and C-Σ. They differ in which requirement they give up (r1.2, J3: "coherent" dropped; by the Rq1 rule, M, K-T and U have no choice point that moves E3-REL, and C-Σ has one), and in their outputs: E3-REL 13 / 8 (M, K-T, U) vs 10 / 5 (C-Σ). **"Single principle" does not select a unique principle.**
3. T3's A1 is incoherent only under the stipulations (a) and (b) of Theorem 2.

**Not established.**
- That "single principle" should be confirmed.
- That it should be revised, or how.
- That mixed principles are better, or that they are worse.

**Status.** The criterion stays **provisional** (F-LOG-0302). [E] One option open to the human is to state instead which requirement may be given up. That would be a choice about the guard-row purpose (7h with Rq9, or 7c) and about whether Rq3 spans row types. It is not recommended here.

### 6.4 Analyst assessment [E] (not a decision; r1, F4)

- **r0's "formally best supported: C-Σ" is withdrawn.** C-Σ's apparent advantage came from requirements that were written after the KC results (Rq2b is C's own distinction turned into a test) and from an R11 encoding that did not follow its own rule. Once its choice points are graded uniformly and Rq9 is added, it has no formal advantage.
- **No principle is identified as best supported.** The requirements are stipulations of uneven status (§2.2). The sources conflict on the decisive purpose question (§4.2). The candidates that look strongest on some requirements (K-T, C-Σ) are post-hoc additions beyond F-LOG-0302.
- What the analysis does locate are **two questions that separate the main options**. The first is the guard-row purpose: whether a guard row is read as relevant to the tie it delimits (7h, Rq9; this gives T3, K, K-T, M, U) or as a non-constraint (7c; this gives C, C-Σ). It is entangled with C-γ.
- **Choice (b), whether Rq3 spans row types, also separates the options** (r1.1, H9). If it does, T3, K and K-T fail Rq3 under (a) and M, U pass; if it does not, T3, K and K-T pass. It is a stipulation, not a finding.
- Within the first group, Rq2a (T3 and M fail it) and Rq5 (M and U fail it) also separate T3, K / K-T and M / U. Both are stipulations, so neither settles the choice.
- **These are human questions.**

### 6.5 What the evidence does and does not establish

| established (with the stated confidence) | not established |
|---|---|
| Theorem 1 (proof) | that any principle is correct for this model |
| Theorem 2, **conditional on (a)–(d)**, and its counterexample principle (K-T) | that the conflict holds without (a)–(d) |
| the counterexamples, computed and corrected in r1 | that the rewordings are what T2's authors meant (A-iv) |
| the E3-REL counts, host differences, asymmetry effects and pairing results, computed under FZ conventions | that the encodings are right: they are Claude's (Λ7) |
| the root causes of each principle's failures | which requirement should be given up |
| (moved to "argued [E]", r1.2, J2) | that any principle is best supported |
| that C's parse range comes from its undefined class-level clause | anything about H-D or H-B, or about the acceptability of the asymmetry |

**Argued [E], not established:** that K's label dependence reduces to the tier (K-T). K-T's encoding is K's by construction (J1), so this rests on the analyst's reading that K's encoding satisfies K-T's textual definition.

---

## 7. Limitations

- **Λ7, within-family.** Every principle, classification, rewording and encoding is by Claude. FZ, KC and their reviewers are Claude-family too.
- **The encodings are by Claude.** The A-i classification underlies Theorem 2's choice (a) and all of C-Σ.
- **The external-encoding option exists.** A non-Claude or human encoder, blind to T3 and the ratified lists, could re-encode the 83 rows and the counterexample pairs (AUDIT-Q6-TEST1 §7 (i); KC §6). That needs its own authorization.
- **Shared sources with audit test 2** (see the header). The two must not be cited as corroborating each other.
- **Post-hoc.** The requirements, the counterexamples and the candidates K-T, U and C-Σ were all written after the KC results were known.
- **Natural-language premises.** C-Σ at R15 relies on A-ii. Rq2a relies on A-iii, against P6.
- **Inherited approximations.** The K8 and K9 vocabulary choices are held at A.0. K15–K19 stay disclosed but not computed. The S rank rests on Lemma 1's assumption that C1 spans every cut.
- **Scope.** Only P-c, the ratified T-A and T-B hosts, E3-REL, the asymmetry clause rows and the guard / T-C pairs are evaluated. MW1 / CV4 hosting, D12 and C-γ are not decided.
- **Review status.** r1 and r1.1 were each re-reviewed independently (both PWL). The r1.2 fixes (J1–J7) are wording, plus one enumeration that moves no row and changes no mark; they are not re-reviewed. The analyst reproduced `mkc.out` and `adhoc.out` byte-identically before commit.
- **Rejected parse, disclosed (r1.2).** While applying J2, the worker briefly tried a fifth K-T parse, R21 read without c. It would move R21 to CB23, move E3-REL to 12 / 7 and make K-T's Rq1 ✗. It was excluded because R21's text names c, so it is not a reading of the text. It is not in `adhoc.py`. K-T's Rq1 ~ therefore rests on that exclusion [E].
- **Standing qualifier (verbatim):** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

## 8. Self-check

| check | result |
|---|---|
| Labelled "Analysis only; decides nothing" | yes; draft r1.2 |
| T3 used as baseline and not ratified; departures reported | yes (§5.1, §5.4, §6.1) |
| No option selected to reconcile a label; minimal amendment, output preservation and single principle not used to rank | yes |
| Requirements explicit, each with its normative status | yes (§2.2) |
| Theorems stated with their conditions | yes (§2.3, §2.4) |
| All 83 rows covered; choice points graded uniformly; C's 9 / 4, 10 / 5, 11 / 6; K's labels; M's polarity | yes (§5; `mkc.out`, `adhoc.out`) |
| E3-REL, accepted outputs, host lists and asymmetry records | yes (§5.3–§5.5) |
| Repairs labelled [E] and marked as additions; "best supported" withdrawn | yes (§6.2, §6.4) |
| C-γ, D12, E4, E5 and the spanning rule not decided | yes |
| Test 2 files not touched; shared-source disclosure made | yes |
| Scripts run with `python3 -B`; no `__pycache__`; not committed | yes |

## 9. Changes: r1 (adversarial review of r0: PASS_WITH_LIMITATIONS; 5 material, 7 minor)

| finding | fix | where |
|---|---|---|
| **F1** Theorem 2 depends on four undisclosed choices | made conditional on (a) R80 classed as N (unasserted T-B vs asserted T-A), (b) Rq3 spanning row types, (c) Rq7h limited to T-A, (d) R80′ counted as N-free; witness changed to R14 (pure Σ₁); the counterexample principle (outputs = K) added; "none can" and "a trilemma any principle meets" dropped | §0, §2.4, §6.2, §6.3 |
| **F2** normative status; failures by root cause | each requirement marked [U], [E-s] or [E], with its limits: Rq2a contradicts P6; Rq2b is C's own distinction; Rq5 uses P3 selectively; Rq1's "no enumeration" does not follow from P17. Failures grouped by root cause (C: the class-level clause, plus an encoding slip at Rq4; T3: A1) | §2.2, §3.3, §6.1 |
| **F3** C-Σ not established | (a) pairing test added (C-Σ splits 5 of the 6 listed pairs, 4 guard rows; K agrees 6 / 6); (b) Rq1 graded uniformly (C-Σ: 5 moving choices; the exceptive moves E3-REL 10 / 5 vs 9 / 4); (c) C-Σ fully specified (equivalence basis, precedence, coverage) with an 83-row classification (`mkc.out` §3d); (d) R11 fixed to {3, 4, 5, 6, 8, 12} (181,730 cuts; same P-c verdict; S rank now 23 / 34 / 155); (e) "derived, not chosen" withdrawn; C-Σ differs from C only at R11 and R15, and from C-R15ref only at R11 (encoding level; verdict level R15 only, H8) | §3.2, §5.2, §5.6, §6.2; `mkc.py` |
| **F4** "formally best supported: C-Σ" | withdrawn; post-hoc disclosure extended to Rq1–Rq9; the repairs marked as additions beyond F-LOG-0302's M / K / C | header, §6.4 |
| **F5** missing pairing requirement | Rq9 added; fails C and C-Σ, passes T3, K, M and U | §2.2, §3.2, §6.1 |
| **F6** counterexample encodings | CE-def-1 B adds C10 (181,730 cuts); CE-def-2 B = R32's encoding, adding C3 and C21 (385,220); CE-slot-1 and -2 witnesses reworded so that they name no record | §3.1; `mkc.py` |
| **F7** Theorem 1 | the essential-vocabulary premise dropped; R24 excluded from "every T-A row 𝓗-valid"; the 𝓢 case restated with R4 / R5 / R22 | §2.3 |
| **F8** K-T uses a label | stated: the tier is a T2 label; K's R80-role and R79-contrast choice points remain (listed in `adhoc.out`) | §5.2, §6.1, §6.2 |
| **F9** K's Rq2b | qualified as for T3: it passes on labelled guard rows | §3.3, §6.1 |
| **F10** "every candidate gives up exactly one" | withdrawn (false for C) | §0, §6.2 |
| **F11** P23 attribution | T3 says only "R80 states no clause" (line 265); the "not a guard denying a tie" rationale is attributed to the AUDIT-Q6-TEST1 encoder's gloss | §1.3 P5, P23; §4 |
| **F12** Rq7c scope | stated: evaluated on guard rows only; definition and typing rows would violate it under every principle | §2.2 |
| (runtime) | about 5 s for both scripts (r1.1: about 12 s with the added checks) | header |

### 9.1 Changes: r1.1 (targeted re-review of r1: PASS_WITH_LIMITATIONS; H1–H9) and r1.2 (independent re-review of r1.1: PASS_WITH_LIMITATIONS; J1–J7)

| finding | fix | where |
|---|---|---|
| **H1** Rq1 graded inconsistently | explicit grading rule (✓ no moving choice; ~ moving choices, none moving E3-REL; ✗ a choice moves E3-REL), applied from `adhoc.out` to all seven: T3 ✗, M ✓, K ✗, K-T ~, C ✗, U ~ (was ✓), C-Σ ✗ (was ~) | §2.2 Rq1, §5.2, §6.1, §6.2, §0 |
| **H2** K-T not encoded | a K-T entry added to `mkc.py` (§3a2: identical verdicts to K on all 83 rows). **r1.2 (J1) corrects this row: the entry equals K's encoding by construction, row for row; it is not an independent encoding**; per-principle requirement checks added (§3e); `adhoc.py` uses the K-T encoding; K and K-T given the same Rq3 presentation; K-T's marks stated in full (~ Rq1, ~ Rq2b, Rq3 conditional, fails Rq7c) | §2.4, §3.3, §6.1, §6.2; scripts |
| **H3** Theorem 2's reading premise | premise (e) stated: an N-free positive clause is read by the items it names (P1, Rq4 unfolding); §0 "without them" → "without (a) or (b)" | §2.4, §0 |
| **H4** Theorem 1 premise; §3d consistency | 𝓗 case narrowed to the F-axioms (R1 CB23 vs R2 X); R14 as witness explained against §3d's Σ₁-only class for R14, R16, R17; R23 "Π over pairs" | §2.3, §2.4, §6.2 |
| **H5** Rq9 denominators | 7 / 6 / 5 reported (T2-stated only: C and C-Σ 1 / 5), R11 / R47 included, R15 / R42 and R16 / R41 marked inferred, cut counts per pair (26,690–398,650 for T3, K, M, U where non-zero), "agree on every pair" limited to P-c | §3.2, §6.1 |
| **H6** status labels | Rq6 and Rq8 are [U]; Rq9 relabelled [E] (P10 pairs, does not require equal verdicts); §0 notes that Rq9 largely re-encodes 7h on guard rows (one position, not two supports) | §0, §2.2, §6.1 |
| **H7** F12 detail | R19 crosses under S only: "R9 and R22 under every reading; R5, R6, R8, R10 and R19 under S only" | §2.2 Rq7c |
| **H8** C-Σ vs C | the level is named each time: verdict level, R15 only; encoding level, R11 and R15 (and vs C-R15ref, R11 only) | §0, §5.2, §6.2 |
| **H9** ranks; choice (b) | §5.6 completed (S: M 26 / 147 / 417, K 26 / 64 / 304, C 22 / 46 / 234; S′: T3 and K 17 / 0 / 1, C 13 / 0 / 1); §6.4 states that choice (b) also separates the options | §5.6, §6.4 |
| **J1** K-T not independently encoded | stated plainly: K-T's encoding equals K's by construction, row for row (same construction as `kc.py`'s K; SUBJ sets equal FZ's); only the definition differs; all K-T verdicts, marks and Theorem 2's counterexample outputs are K's results relabelled, not a second computation. "Encoded from its own definition", "adds K-T as its own encoding" and "encoded on its own" removed (header, §0, §2.4, §6.2, §9.1 H2); code not rewritten | header, §0, §2.4, §3.3, §6.2, §9.1; `mkc.py` comment |
| **J2** Rq1 grades the definition | stated in §5.2; `adhoc.py` enumerates for K-T the A-i parse choices that count against C-Σ (R15 exception, R21 typing part, R11 F1 restatement, R16 NOT-EVIDENCED): **none moves any row or E3-REL**, so K-T stays ~ (choice points 7 / 3 / 3, was 3 / 3 / 3); "K's label dependence reduces to the tier" moved from "established" to "argued [E]" | §0, §3.3, §5.2, §6.2, §6.5; `adhoc.py` |
| **J3** "coherent" stale | "coherent" dropped; "several single principles exist, differing in which requirement they give up"; by the Rq1 rule M, K-T and U have no choice moving E3-REL, C-Σ has one; "single principle" stays provisional | §0, §6.3 |
| **J4** Theorem 1 wording | "the F1 part of R1", consistent with §3d's H + Σ₁ class | §2.3 |
| **J5** §8 row 1 | draft r1.2 | §8 |
| **J6** U, C-Σ in §3.3 | note added after the §3.3 table with their failures and a pointer to §6.1 | §3.3 |
| **J7** §9 F3 row | appended "(encoding level; verdict level R15 only, H8)" | §9 |

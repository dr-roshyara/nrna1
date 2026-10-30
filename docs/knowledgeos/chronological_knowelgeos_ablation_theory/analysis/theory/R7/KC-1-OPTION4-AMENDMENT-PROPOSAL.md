# KC-1 option 4 (Add) — amendment proposal

**ADOPTED as r7.3 by the Human ARB, F-LOG-0324 (2026-10-30); applied to E3 r1.4.** [r7.3 header as adopted: "PROPOSED / NOT ADOPTED. r7, 2026-10-30. Implements nothing."] Prepared under F-LOG-0313 (KC-1 = option 4; parse K1c; R80 as written), F-LOG-0315 (the human's provisional decisions), F-LOG-0316 (name; uniform test; correction note to be shown first) and **F-LOG-0317 (the human's answers: R22 qualifies; R24 assertion, with the ambiguity distinction recorded; scope conditions K18, K2, Λ7 kept; symbol E3-REL-AC; audit C9 met; correction note approved)**. **r7 applies the r6 focused check (F-LOG-0320). Spot check of r7: PASS_WITH_LIMITATIONS (F-LOG-0321); its minor findings m1, m3–m5 were applied (r7.1), and m2 was clarified at the human's direction in `KC-1-ONE-PAGE-CHOICE.md` (r7.2, F-LOG-0322). Final short check of r7.1 / r7.2: PASS_WITH_LIMITATIONS (F-LOG-0323), bookkeeping findings only, applied (r7.3).**

**Review history:**
- r0: NEEDS_REVISION (F-LOG-0314).
- r2: focused re-review PASS_WITH_LIMITATIONS (F-LOG-0316). No finding was blocking. It reported 5 material and 10 minor findings, and its figures were recomputed independently.
- r3: applied those findings; classified the non-guard rows.
- r4: the human's answers (F-LOG-0317). Focused review: **NEEDS_REVISION** (F-LOG-0318). No finding was blocking and no figure changed; 3 material and 9 minor findings.
- r5: applied them.
- r6: the human's re-confirmation (F-LOG-0319). Focused check: **NEEDS_REVISION** (F-LOG-0320): 2 material, 8 minor; no figure or classification changed.
- r7: applied them. **Spot check: PASS_WITH_LIMITATIONS** (F-LOG-0321): no blocking or material findings, 5 minor.
- r7.1: m1, m3, m4, m5 applied; m2 disclosed.
- r7.2: m2 clarification appended (F-LOG-0322).
- Final short check of r7.1 / r7.2: **PASS_WITH_LIMITATIONS** (F-LOG-0323): no figure, classification or definition defect; 5 bookkeeping findings.
- **r7.3: those bookkeeping edits applied (not reviewed; status pointers only).**

No accepted record is edited by this document, and nothing is committed.

Labels: [CR] computed and reproducible · [SF] source fact (quoted) · [FD] formal derivation · [E] analyst stipulation · [G] executor's gloss (not a recorded human phrase).

---

## 1. The rule-classification discrepancy — resolved from the sources (reviewed in r2: correct)

The two descriptions apply **the same parse (KC r1.1 §2.4 item 4, "K1c") to different questions**:

- **"R14, R16, R17 host nothing; R15 → CB23; R21 → CB1"** is the K1c *hosting side*: where each guard row's asserted part lies. It is what option (c) would have done to the **host lists** (`KC-CONVENTIONS-PROPOSAL.md:63`, `:88`). Under Add the host lists are **not** changed, so these host cells are **not applied**.
- **"13 minus R14, R15, R16, R21"** is the *both-sides test* for the additional measure. One-sided asserted parts (R15, R21) and empty ones (R14, R16) do not qualify.

| row | statement (T2 r1.4, head) [SF] | items read under (a) → hosting stays | K1c asserted part [SF, KC:228–235] | side | in E3-REL? | qualifies? |
|---|---|---|---|---|---|---|
| R11 | "Norm state is a total primitive, not computed from acts." | CB23 (internal) | "σ : T → V^𝒯 is total": C3, C4, C6 | CB23 | no | no (not in E3-REL) |
| R14 | "No equation between f and d or r … imposes no inequality" | crosses (C11, C12 / C14) | none | — | yes | **no** |
| R15 | "No time order among r, d, f beyond F2; t(w) outside the governance times" | crosses | "t(w) as a work attribute": C20 | CB23 only | yes | **no** |
| R16 | "No completeness constraint." | crosses | none | — | yes | **no** |
| R17 | "No norm→work dependence." | CB23 (internal) | none | — | no | no |
| R21 | "Texts are texts. No axiom ties the text c to σ or f" | crosses | "a self-assertion … is a value of c": C15 | CB1 only | yes | **no** |

Item sets [SF]: CB1 = C13–C18, C23, C24, C27 (T3:48); CB23 = C2–C12, C19–C21 (T3:49).

## 2. Classification of the non-guard rows — the uniform test

**Test (human, F-LOG-0316, verbatim):** *"Does the rule's asserted content itself state a relation, constraint, typing condition, definition, or proposition involving both item sets?"*; "a rule's mention of both item sets is not sufficient to qualify; the label 'definition,' 'lemma,' or 'constraint' is not, by itself, sufficient to qualify." [G] A positive assertion is distinguished from a denial or an absence (from the human's session instruction "Explicitly distinguish a positive assertion from a denial or absence of a relationship"; not among F-LOG-0316's logged phrases).

**Dual rule (the human's, F-LOG-0313, F-LOG-0316, F-LOG-0319):** guard ("no link") rows (R11, R14–R17, R21) are judged by the **strict K1c parse**; all other rows of E3-REL by the uniform test. These are two different rules, not one test.

Sides are taken from T3 r1.9's crossing table (T3:86–105). C1, the participant, counts on both sides **only under reading S** (rule 2a [E]; no reading selected).

| row | asserted content (T2 r1.4) [SF] | CB1 side | CB23 side | positive? | both sets? | qualifies |
|---|---|---|---|---|---|---|
| R2 | "If con(a) = ρ then (ρ, a) ∈ Ref" | con, Ref (C18, C17) | act a | relation | yes | **yes** |
| R3 | "If con(a) = ρ then d(a) = r(ρ)" | r (C14) | d (C11) | constraint | yes | **yes** |
| R9 | "Ref ⊆ R × (E ∪ W); con : E ⇀ R is partial …" | R, Ref, con | E, W | typing | yes | **yes** |
| R5 | "every act has α(a) ∈ 𝒜 …" (T2:57; sides T3:86) | α (C1), under S only | acts, d, τ, v, prep, f | typing | **under S only** | S yes; S′/S″ not in E3-REL |
| R6 | "Every work event has τ(w) ∈ 𝒯, α(w) ∈ 𝒜, t(w) ∈ T" | α(w) (C1), under S only | work events | typing | under S only | as R5 |
| R8 | "Every record has a keeper k(ρ) ∈ 𝒜" (T2:60; sides T3:89) | k(ρ) | 𝒜 (C1), under S only | typing | under S only | as R5 |
| R10 | "Cap ⊆ 𝒜 × {iss, adp}, constant within a history" | 𝒜 (C1), under S only | Cap (C2) | typing | under S only | as R5 |
| **R19** | "Adopted(a, t) iff (prep(a) = 0, (α(a), adp) ∈ Cap, d(a) ≤ t) or some adoption act b with obj(b) = a, (α(b), adp) ∈ Cap, d(b) ≤ t" (T2:71) | only the actor α (C1), under S (T3:100) | prep, Cap, d, obj (C2, C9, C10, C11) | definition (positive) | **under S only, through the participant** | S yes; S′/S″ not in E3-REL |
| **R22** | "Records (and actors, acts, work events) are the same across histories iff they share an identifier from disjoint identifier universes" (T2:74) | record identifiers (C13) (T3:103) | act and work-event identifiers (T3:103: "crosses (also without C1*)"; T3:235: "record ids against act and work-event ids") | definition (positive) | **yes**: one condition stated for both sets, **and** the identifier universes are pairwise disjoint across them | **yes — by the human's interpretation (F-LOG-0317, "A qualifies")** |
| **R24** (D12 (b) only) | "every (ρ, a) ∈ Ref is performatively ambiguous at O(h): records alone never fix whether ρ is or reports a" (T2:76) | Ref, O(h), con (T3:105) | acts (through the Ref pair) | **a conditional universal proposition; its content is a non-determination** | yes | **yes — by the human's interpretation (F-LOG-0317, "B assertion")** |

**Recorded rationale and interpretation-dependence (F-LOG-0317; corrected per F-LOG-0318; rationale re-confirmed by the human, F-LOG-0319):**
- **R22 — qualifies.** *Source basis:* T2:74 states one identity condition ("the same across histories iff they share an identifier from **disjoint** identifier universes") for records (CB1, C13) **and** for acts and work events (CB23) (T3:103; T3:235 "record ids against act and work-event ids"). Its source definition, Def 1.7a (`FORMAL-THEORY-R4.md:94`), fixes "countably infinite, **pairwise disjoint** sets of identifiers: 𝔄 (actors), 𝔈 (acts), 𝔚 (work events) and 𝔑 (records)". *Two grounds:* (1) a uniform definition stated for items of both sets (the interpretation selected in F-LOG-0317); (2) the **disjointness** of the record universe 𝔑 from the act and work-event universes 𝔈, 𝔚 is itself a relation between items of the two sets, inside R22's own statement. *Dependence (resolved, F-LOG-0319):* the human recorded ground (2) as a second ground. **R22 therefore qualifies under either interpretation**: a uniform definition qualifies under the first, and the cross-set disjointness relation under the second. **The 9 / 4 result does not depend on which of the two recorded interpretations of R22 is applied** (the human's words: "does not depend on this particular interpretation of R22"). It still depends on R22 qualifying under at least one recorded ground, which is why reopen trigger (viii) keeps R22. The r4 alternative "8 / 3 if an explicit cross-set relation is required" does not arise. *Disclosed limitation (F-LOG-0320, finding 9):* the second ground counts a disjointness condition (no identifier is shared between the record universe and the act / work-event universes) as a positive cross-set relation. Structurally, that is a non-overlap condition, close to the "denial or absence" the uniform test is meant to distinguish. The classification is the human's decision (F-LOG-0319); this is recorded only so that the ground's character is visible.
- **R24 — qualifies, as an assertion.** *Source basis:* T2:76 is a lemma (Lem 5.2), **conditional** ("Under A3, A8, A9, for every h in 𝓗 or H-B (and, with A1, A2, A5, in H-D on R_B)") and **𝐑₀-scoped** (it fails under R-j; T2:76). Its content is a universal proposition: every (ρ, a) ∈ Ref "is performatively ambiguous at O(h)", about pairs that join a record (CB1: Ref, O(h), con) to an act (CB23) (T3:105). It asserts **no existence** of pairs: it holds vacuously if Ref is empty. By Def 5.1 (`FORMAL-THEORY-R4.md:485`), "performatively ambiguous" means there **are** two histories h₁, h₂ with the same observation, in one of which ρ is the act and in the other reports it. *Interpretation selected:* attributing this property to every cross-set pair is a positive assertion. **Ambiguity distinction (corrected):** the property's content is a non-determination ("records alone never fix whether ρ is or reports a"), stated through the existence of alternative histories. **The difference from the guard rows does not rest on content.** R14 also contains a positive possibility clause, "f may coincide with d or r in some histories" (T2:66; recorded as an alternative asserted parse at KC:231). It is excluded because guard rows are judged by **strict K1c**, the human's F-LOG-0313 choice, under which R14's asserted part is empty. R24, as a non-guard row, is judged by the uniform test. *Dependence:* only under D12 (b), where R24 joins E3-REL. If R24 did not qualify, the D12 (b) figure would be 9 / 4 instead of 10 / 5. D12 itself stays unresolved. **Distinction confirmed by the human (F-LOG-0319):** R24 is a conditional "every pair is ambiguous" statement and makes no existence claim. Its treatment differs from R14 because the strict K1c rule applies to "no link" rules, not because R24 asserts that pairs exist while R14 does not.

**Disclosure (m5):** under S, **5 of the 9 rows (R5, R6, R8, R10, R19) qualify only because C1 is counted on both sides** (rule 2a, reading S, [E]). R22 qualifies through its parenthetical "(and actors, acts, work events)".

**Recalculated [CR]:** all four rows were recomputed by hand from the source files by the r4 reviewer (F-LOG-0318). `KC-1-OPTION4-scripts/recount.py` (a transcription check by the executor) reproduces every row via its `--alt` flag. The r2 reviewer's independent 9 / 4 predates the R22/R24 classification.

| R22 | R24 | D12 (a) S / S′ | D12 (b) S / S′ |
|---|---|---|---|
| qualifies | qualifies | **9 / 4** | 10 / 5 |
| qualifies | does not | 9 / 4 | 9 / 4 |
| does not | qualifies | **8 / 3** | 9 / 4 |
| does not | does not | 8 / 3 | 8 / 3 |

S″ = S′ throughout. **Selected (F-LOG-0317: R22 qualifies, R24 assertion): 9 / 4 under D12 (a), 10 / 5 under D12 (b).** The other rows show the dependence: R22 alone rejected → 8 / 3 and 9 / 4 (D12 (b)); R24 alone rejected → 9 / 4 and 9 / 4; both rejected → 8 / 3 and 8 / 3. Since F-LOG-0319 the R22-not-qualifying rows do not arise; they are kept only to show what r4 had stated. **The only remaining interpretation-dependence is R24, and only under D12 (b)** (10 / 5 vs 9 / 4).

**Sensitivities not covered by the interpretations above [CR, F-LOG-0318] (disclosure; neither is proposed):**
- If R14's possibility clause were judged by the uniform test instead of strict K1c, R14 would plausibly qualify: **10 / 5** (D12 (a)). This is excluded by the strict K1c choice (F-LOG-0313).
- If counting C1 on both sides under S were not accepted for the uniform test, R5, R6, R8, R10 and R19 drop under S: **S = 4**. This is the rule 2a reading S stipulation [E], inherited from E3-REL.

**Corroboration — of E3-REL membership only:** within the enumerated space, **E3-REL ∖ L = ratified ∖ L**, and membership varies only at R14, R15, R16, R21 (readiness audit §3.3, line 215, with its own caveat "(K18, not enumerated, would break this.)"). The C / K1c computation also leaves every non-guard T-A row crossing (KC:88). Both are crossing results, i.e. "mention", which the human ruled insufficient. They therefore do **not** corroborate the uniform-test classification, which rests on §2's row-by-row reading.

## 3. Two figures, kept distinct

| | **E3-REL, cross-module rows** (existing, unchanged) | **E3-REL-AC, cross-module asserted-content rows** (new) |
|---|---|---|
| Definition | E3:111, quoted exactly: "the set of T-A rows that E2b §A1.3 states, ratified per reading (R-a), as … "hosted on both CB1 and CB23", per reading, and nothing else" | The **set** of rows of the existing E3-REL set whose rule text asserts content involving items placed on both CB1 and CB23, per reading; the figure is its **cardinality**. Guard rows are judged by the strict K1c classification; other rows by the uniform test of §2 |
| Purpose | hosting: which rows both modules host | which rule texts assert content involving both item sets |
| D12 (a) | **13 / 8** | **9 / 4** (proposed; independent of which of the two recorded R22 interpretations is applied, F-LOG-0319) |
| D12 (b) | 14 / 9 | 10 / 5 (depends on the R24 interpretation only; 9 / 4 if R24 did not qualify; F-LOG-0317, F-LOG-0319) |
| Status | accepted (F-LOG-0297); **not reopened or re-accepted** | new; adopted only by the amendment act |

E3-REL-AC is a set of **qualifying rule-text rows**; its figure counts them. **It does not measure real-world module dependency** (E3 §2.1 I.1a, E3:71; E3:102, R23). **Mentioning both item sets is not sufficient; neither is the label "definition", "lemma" or "constraint".**

**Scope conditions — kept explicitly (F-LOG-0317, "C yes"):**

| # | condition | effect if it changed [SF] |
|---|---|---|
| **K18** | the text read is the **statement cell** (T3:66), not T2's "rests on" column | E3-REL becomes 6 / 3 under composition I, or 8 / 5 under composition II (readiness audit §12.2, ~lines 535–538). The non-guard rows move (R2, R9, R22 drop under S′), and R24's host cell departs (line 535). Both figures would be reopened |
| **K2** | the class rule is **on** (T3 rule 3, K2 (a)) | K2 (b) moves R12's T-A host cell (X → CB23 **under every reading**; KC:89 "R12, all readings"; readiness audit line 514 "in every rule"). K2's T-A footprint is R12 **and R24** (audit §12.2 footprint table, ~line 503). **Neither figure changes:** R12 is hosted at MW1, outside E3-REL, and "no T-A ∖ H row and no R24 change X-status" under D12 (a) or (b) (audit ~line 516). E3-REL-AC's text classification does not depend on K2. R12's host cell would move |
| **Λ7** | every encoding and every review is **Claude-family** | none computed; a shared interpretive error would go undetected. The external non-Claude check was waived, not satisfied (F-LOG-0311) |

## 4. Name check (M5, m9)

- **"Coupling" is avoided.** It already has **three** uses in the records:
  1. VP14 criterion 4 "coupling [I]", scored by "fewest clause crossings among the cuts" (`VP5-VP10-VP14-DECISION-PACKAGE.md:202`): hosting and crossing, not asserted content.
  2. E3 I.1a, which excludes "domain coupling" as a meaning (E3:71).
  3. **This measure under earlier wording:** readiness audit §13.1 (line 673, "Coupling (a new, explicitly named measure)"), F-LOG-0313's heading and "Decided" bullet, and `ch_rho.out` §4.

  Use 3 is **superseded by the name chosen under F-LOG-0316**, to be recorded in the adoption entry (the log is append-only).
- **"Cross-module rows"** is E3-REL's existing name (E3:111). Reusing it signals the subset relation correctly.
- **"Asserted-content"** appears nowhere else in the records.
- **Symbol: E3-REL-AC** (F-LOG-0317). "E3-REL-A" (F-LOG-0316) is not used, because it could be confused with E3's "[A]" tag and the audit's "A-T3[…]" names.

## 5. Ratification status — stated, not manufactured

| item | status | basis |
|---|---|---|
| KC-1 = option 4, Add | decided (provisional until the adoption act) | F-LOG-0313, F-LOG-0315 A1 |
| uniform test for non-guard rows; strict K1c only for "no link" (guard) rows | decided | split: F-LOG-0313 (strict K1c for this measure) and F-LOG-0319 Q2 ("the strict K1c rule applies to 'no link' rules"); the test itself: F-LOG-0316 |
| strict K1c as the splitting rule **for E3-REL-AC only** | decided, scope: this measure | F-LOG-0313 ("Strict (K1c): 9 / 4") |
| name; symbol E3-REL-AC | decided | F-LOG-0316; F-LOG-0317 |
| R22 qualifies (two grounds; independent of which of the two recorded interpretations is applied); R24 qualifies as an assertion (conditional universal, no existence claim; differs from R14 by the strict K1c rule, not by content) | decided **interpretations** and re-confirmed rationale (not findings) | F-LOG-0317; F-LOG-0319 |
| scope conditions K18 = statement, K2 (a), Λ7 | kept as scope conditions of both figures; **not** a ratification of K18 or K2 | F-LOG-0317 |
| audit C9 met for this amendment | decided | F-LOG-0317 |
| R80 as written (K-c result "none"; CB23 element host only as the H-B-side VP1d option, K3) | decided; no change | F-LOG-0313; T3:122, :265; E2b:332–333, :375 |
| T3's crossing method (K-c criterion, rules 1–5, 2a, encoding) | **NOT ratified**; E2b:584 "a ratified output of an unratified method" stays accurate and now also covers E3-REL-AC | F-LOG-0315 A2 |
| keeping the host lists | relies on the ratified R-a lists (F-LOG-0290); **does not ratify K1 (a)** | — |
| the §2 uniform-test classifications | adopted only with the amendment | — |

**Audit C9 (M4).** E3:596 discloses: "audit C9 recommended running Q6 tests 1 and 2 before any further amendment that cites host lists, and those tests are still open". E3-REL-AC is defined on the §A1.3 host list, so this amendment cites it. Q6 tests 1 and 2 have since been run, as analysis only (F-LOG-0299; F-LOG-0304, test 2 r1.1, PWL); C6 stays open. **The human ruled C9 met for this amendment (F-LOG-0317, "E met").**

**Is method ratification needed?** Not for Add. It may become relevant before E4/R8 (F-LOG-0300 "a settled basis"). The decision then would be T3 §1a rules 1–5 with K1 (a), A1 and A3 as stipulations, Chg as FZ reads it, and K9 = A.0 (KC:445), under the scope conditions of §3 (K18, K2, Λ7), with rule 2a and the encoding as separate items. **Not proposed here.**

## 6. Proposed exact edits (Add only; applied only after the human's review and adoption)

### 6.1 E3 (`E3-ARCHITECTURE-PROPOSAL.md`, r1.3 line numbers) — KC-3

| # | site | edit |
|---|---|---|
| E0 | 1 title ("draft r1.3") | → "draft r1.4", keeping "[r1.3 …]" as a tag (m3) |
| E1 | 2–3 status | new r1.4 status line: "KC-1 option 4 (Add) recording under F-LOG-0xxx: E3-REL-AC added (§2.3); E3-REL and every accepted output unchanged; nothing re-accepted." The r1.3 line is kept |
| E2 | 99 §2.2 shared cells | no edit (hosting cells) |
| E3 | 111 E3-REL definition | no edit ("and nothing else" not breached) |
| E4 | new bullet after 114 | "**[added by F-LOG-0xxx] E3-REL-AC, cross-module asserted-content rows** (provisional name, not canonical): the set of rows of E3-REL whose rule text asserts content involving items placed on both CB1 and CB23, per reading (the figure is its cardinality). Guard rows by the strict K1c classification (`KC-CONVENTIONS-PROPOSAL.md` r1.1 §2.4 item 4, selected for this measure by F-LOG-0313); other rows by the uniform test of `KC-1-OPTION4-AMENDMENT-PROPOSAL.md` §2, as adopted (revision pinned at adoption) [E]. S: R2, R3, R5, R6, R8, R9, R10, R19, R22 (9); S′ / S″: R2, R3, R9, R22 (4); 10 / 5 under the other D12 reading; D12 not resolved. Under S, R5, R6, R8, R10, R19 qualify only through the participant counted on both sides (rule 2a S). R14, R15, R16, R21 are cross-module rows but do not qualify for E3-REL-AC. **A set of qualifying rule-text rows; not a measure of real-world module dependency (§2.1).** Scope conditions: the statement cell is the text read (K18 = statement); the class rule is on (K2 (a); under K2 (b) neither figure changes, since R12 lies outside E3-REL and R24's X-status is unchanged); Claude-family encodings and reviews only (Λ7). R22 qualifies on two grounds (a uniform identity definition; the pairwise disjointness of record and act / work-event identifier universes) and R24 (D12 (b)) qualifies as an assertion by the human's interpretation (F-LOG-0317; rationale re-confirmed F-LOG-0319). R24 is a conditional universal proposition whose content is a non-determination; it makes no existence claim (F-LOG-0319); R14's possibility clause is excluded by the strict K1c rule for guard rows, not by content. R22 qualifies under either interpretation (uniform definition; cross-set disjointness), so 9 / 4 does not depend on which of the two recorded interpretations of R22 is applied (F-LOG-0319). If R24 did not qualify, the D12 (b) figure would be 9 / 4. Like E3-REL, it rests on per-reading lists of an unratified method (E2b §A2.2). **Reopen if:** (i) the guard-row splitting rule (K1c) is revised; (ii) the K-c criterion or rule 2a is revised (E2b §A2.7, through E3-REL); (iii) E3-REL changes; (iv) a K18 decision is taken; (v) the §2 uniform test or a row's classification is revised; (vi) the T2 r1.4 statement text of any row of E3-REL or of R11, R14–R17, R21 changes; (vii) a K2 decision is taken (no figure change expected, since R12 lies outside E3-REL; recheck required); (viii) the R22 or R24 interpretation of F-LOG-0317 / F-LOG-0319 is revised; (ix) an external non-Claude check is run (F-LOG-0311) and departs from an encoding or classification. Every trigger reopens E3-REL-AC; (ii)–(iv), (vi) and (ix) may also reopen E3-REL." |
| E5 | 115 [O] relation | append "[F-LOG-0xxx: [O] applies equally to E3-REL-AC; a named count, not a typed relationship or context-map pattern]" |
| E6 | 116 checkability | append "[F-LOG-0xxx: E3-REL-AC excludes R14, R15, R16 (no asserted part involving both sides) and R21 (asserted part on CB1 only; its constraint is on interpretation)]" |
| E7 | 327 §7.2 | new row "E3-REL-AC (§2.3) \| CB1, CB23 \| traces through E3-REL plus the K1c parse (F-LOG-0313) and the §2 classification as adopted (revision pinned at adoption) [E] \| as E3-REL" |
| E8 | 357 | no edit |
| E9 | 359 §8.2 | new row beneath: "E3-REL-AC content (subset of E3-REL) \| R2, R3, R5, R6, R8, R9, R10, R19, R22 (9) \| R2, R3, R9, R22 (4) \| as S′ \| [H] by §2.3; 10 / 5 under the other D12 reading" |
| E10 | 380, 395 | append pointer "[F-LOG-0xxx: E3-REL-AC traces through E3-REL; criterion status as E3-REL]" |
| E11 | 411 §9.3 B1 | append "[F-LOG-0xxx: E3-REL-AC additionally uses the K1c parse, from an unadopted proposal, selected for this measure only by F-LOG-0313; hosting still only from E2b §A1.3 / §A1.4]" |
| E12 | 464 §10.2 | new [O]: "whether E3-REL-AC is a relation (as for E3-REL)" |
| E13 | 472 §10.3 | append "[F-LOG-0xxx: unchanged; the K-c criterion stays unratified; E3-REL-AC inherits this]" |
| E14 | 568 (inside the verbatim F-LOG-0297 act) | no edit |
| E15 | new bullet at the end of the list under "**Rulings and selections recorded here:**" (header E3:590; after the bullet "Accepted: …" at E3:594) | "**[F-LOG-0xxx] KC-1 option 4 (Add):** E3-REL-AC added (§2.3). F-LOG-0297's E3-REL box, the cut-status cells and every accepted output **stand, unchanged and not re-accepted**. The external non-Claude check was **waived, not satisfied** (F-LOG-0311)." |
| E16 | 596 C9 disclosure | append "[F-LOG-0xxx: Q6 tests 1 and 2 have since been run, analysis only (F-LOG-0299, F-LOG-0304); the Human ARB ruled C9 met for this amendment (F-LOG-0317); C6 open]" |
| E17 | §12 change rows | new r1.4 row listing E0, E1, E4–E7, E9–E13, E15, E16 |

### 6.2 E2b — checked; **no edit**

Lines 315, 335, **417** (§A1.10 bullet 1; m2), 584, 634 and 646 all remain true under Add with the method not ratified. The host lists §A1.3 / §A1.4 are unchanged. E3-REL-AC's reopen trigger sits in E3 (E4).

### 6.3 T3 — checked; **no edit**

- Line 3, the status line: still true.
- Line 65, the "[E]" on the crossing criterion: still true.
- **R80** at lines 122 and 265 ("none" / "R80 states no clause"): kept. No contradiction, since E2b §A1.4 hosts R80 on CB23 only as an option slot (E2b:332–333, :375).
- **Clarifying clause (T3:68): omitted, on the basis of F-LOG-0315 A5** ("Omit it if the two measures and their meanings are stated unambiguously") **and** the one-page choice's "If option 1" condition (m8). §3 states both measures without relying on it.

### 6.4 Other files

| file | change | status |
|---|---|---|
| `KC-1-ONE-PAGE-CHOICE.md` lines 7, 22, 29 | appended, dated correction note (§7); original text kept | **approved by the human (F-LOG-0317); applied**, and the dated addendum (**F-LOG-0319, approved; applied**) (the page is not an accepted record) |
| `.claude/CONTEXT.md`, `.claude/sessions/2026-10-30.md` | synced to r7.3 / F-LOG-0323 | working-state files, not accepted records |
| readiness audit §14.E; `APPROVAL-PACKAGE.md:132`; `VALIDATION-REPORT.md:47`, `:111` | **no edit.** These historical analyses describe branch S as "E3:111 amended". **Add departs from them, and from option 4 as the one-page choice described it** ("the definition of the figure in the design (E3:111) must be formally amended", line 22). The departure is resolved by the human's A1 / A6 decisions (F-LOG-0315) | disclosure only |

## 7. Correction note for the one-page choice (approved, F-LOG-0317; applied verbatim as shown)

```markdown
---

## Correction note (appended 2026-10-30; the text above is unchanged and is the page on which KC-1 was decided)

Three passages above are imprecise under the definitions clarified after the decision
(F-LOG-0313 to F-LOG-0316):

- Line 7 describes the figure 13 as "how many rules tie the two modules together".
- Option 4 (line 22) speaks of "a separate 'real coupling' figure" and says "the definition of the
  figure in the design (E3:111) must be formally amended".
- The second guiding question (line 29) speaks of a rule that "actually constrains one side by the other".

Under the clarified definitions:

- **13 / 8 (E3-REL, cross-module rows)** keeps its existing, accepted meaning: the rows hosted on both
  modules (E3:111). It counts hosting, not rows that tie the modules together. Under the "Add" form of
  option 4 that was chosen (F-LOG-0315), its definition in E3:111 is **not** amended.
- **9 / 4 (E3-REL-AC, cross-module asserted-content rows; proposed, subject to verification)** counts
  those of the 13 rows whose rule text asserts content involving both item sets, under the strict K1c
  classification. It counts rule-text rows; it is not a "real coupling" figure and does not measure
  real-world module dependency.

This distinction was drawn after the decision, in `KC-1-OPTION4-AMENDMENT-PROPOSAL.md`. It is not
contained in the text above and does not change what was decided.
```

## 8. Consequences for KC-2 to KC-5 (reviewed in r2: correct)

| act | under option 4, Add, method not ratified |
|---|---|
| KC-2 (E2b) | not needed: no host cell changes |
| KC-3 (E3) | needed: §6.1 |
| KC-4 (T3) | no edit. Reduces to a log record that T3 is unchanged. Defining the measure in E3 avoids revising the K-c criterion, which would trigger E2b:634 |
| KC-5 | not triggered: host lists unchanged |

## 9. Decisions recorded, re-confirmation requested, and the next review

| # | decision (F-LOG-0317) |
|---|---|
| A | R22 qualifies → 9 / 4; on two grounds, under either interpretation (F-LOG-0319) |
| B | R24 qualifies as an assertion (D12 (b): 10 / 5); distinction confirmed (F-LOG-0319) |
| C | scope conditions K18 = statement, K2 (a), Λ7 kept explicitly (§3) |
| D | symbol E3-REL-AC |
| E | audit C9 met for this amendment |
| — | correction note approved and applied (§7) |

**Re-confirmation (F-LOG-0319):** Q1 yes (disjointness recorded as a second ground; R22 qualifies under either interpretation). Q2 confirmed. Correction-note addendum approved and appended to `KC-1-ONE-PAGE-CHOICE.md`; the correction note itself is unchanged.

**Disclosed (F-LOG-0321 m2):** item 3 of the approved addendum says "R22 qualifies under either interpretation, so 9 / 4 does not depend on it". "It" can be misread as R22 itself; without R22 the figure would be 8 / 3. The intended meaning is "does not depend on which interpretation is applied". The approved text is **not edited**. At the human's direction, a one-line clarification is appended below it (F-LOG-0322).

**Review status:** the r7 spot check was PASS_WITH_LIMITATIONS (F-LOG-0321). The final short check of r7.1 / r7.2 was PASS_WITH_LIMITATIONS (F-LOG-0323), with no figure, classification or definition defect. r7.3 changes status pointers only. **The r7.3 pointer edits themselves are not reviewed. Adoption, accepted-record edits and any commit each need the human's separate explicit approval.**

## 10. Change rows

| rev | change |
|---|---|
| r0 | first draft |
| r1 | review r0 NEEDS_REVISION (F-LOG-0314); findings 1–14 applied |
| r2 | human's provisional decisions (F-LOG-0315) applied; discrepancy resolved; name check; ratification table; site list |
| r3 | **re-review r2 PASS_WITH_LIMITATIONS (F-LOG-0316).** Changes by finding:<br>• M1: K18 / Λ7 scope stated; reopen triggers (iv)–(vi); KC pinned to r1.1; HDR-C.<br>• M2: correction note extended to lines 22 and 29; the departure from the page and from §14.E disclosed.<br>• M3: CONTEXT and session log synced.<br>• M4: C9 and HDR-E; E16.<br>• M5: third use of "coupling"; superseded by the name.<br>• m1: E3:111 quoted exactly.<br>• m2: E2b:417.<br>• m3: anchors fixed (E0, E15).<br>• m4: circular phrase withdrawn; corroboration cited.<br>• m5: the 5-of-9 dependence on rule 2a S disclosed; "spanning" removed.<br>• m6: "do not qualify for E3-REL-AC".<br>• m7: "transcription check"; docstring fixed.<br>• m8: omission based on A5.<br>• m9: symbol E3-REL-AC proposed (HDR-D).<br>• m10: verbatim phrases in F-LOG-0316.<br>Human's F-LOG-0316: name "cross-module asserted-content rows"; §2 uniform-test classification of every non-guard row (R19, R22, R24 in full), with HDR-A and HDR-B. **Not reviewed** |
| r4 | human's answers (F-LOG-0317): R22 qualifies and R24 assertion, each with a source-based rationale and interpretation-dependence; ambiguity distinction for R24; K18 / K2 / Λ7 scope table (K2 effect stated: R12 outside E3-REL); symbol E3-REL-AC; C9 met (E16); correction note approved and applied; reopen triggers (vii) K2 and (viii) interpretation revision added. **Not reviewed** |
| r5 | **review r4 NEEDS_REVISION (F-LOG-0318)**, no BLOCKING, figures unchanged. Changes by finding:<br>• M1: R22 disjointness (Def 1.7a, FORMAL-THEORY-R4.md:94) added as a second ground; 8 / 3 flagged as possibly not live (Q1); T3:235 citation.<br>• M2: R24 "exist" removed; conditional and 𝐑₀-scoped; Def 5.1; distinction restated: R14 excluded by strict K1c, not by content (Q2).<br>• M3: E4 / E7 pinned to the adopted revision.<br>• m1: recount.py `--alt` added, docstring corrected; label fixed.<br>• m2: rejected-interpretation figures stated per case.<br>• m3: trigger summary fixed; (ix) Λ7 trigger added.<br>• m4: K2 "every reading", R24 footprint, audit line 516; K18 composition II 8 / 5 and R24.<br>• m5: E3-REL-A → E3-REL-AC; legend; sync row; review history; change-row order.<br>• m6: T2 citations for R5 / R8; corroboration limited to membership; set vs cardinality.<br>• m7: [G] gloss label; header wording; K2 finding re-labelled in F-LOG-0318.<br>• m8: offered as an optional addendum (§9).<br>• m9: session log Next Steps marked superseded.<br>**Not reviewed** |
| r6 | human's re-confirmation (F-LOG-0319): R22 second ground recorded, so R22 qualifies under either interpretation and 9 / 4 is interpretation-independent; R24 distinction confirmed; E4 updated; §5 and §9 updated; addendum to the correction note approved and appended. **Not reviewed** |
| r7 | **focused check r6 NEEDS_REVISION (F-LOG-0320)**. Changes by finding:<br>• 1: §3 D12 cells no longer state an R22 dependence.<br>• 2: §3 definition row repaired; the E3-REL-AC definition is now in its own column.<br>• 3: "which of the two recorded interpretations".<br>• 4: E4 says "no existence claim" and cites F-LOG-0319; trigger (viii) cites 0317 / 0319.<br>• 5: "does not rest on content".<br>• 6: (ix) added to the E3-REL reopen list.<br>• 7: dual rule attributed to the human; §5 row added.<br>• 8: sync row and CONTEXT updated.<br>• 9: disjointness-as-non-overlap limitation disclosed.<br>• 10: F-LOG-0317 "unlike the guard rows' denials" superseded in F-LOG-0320.<br>No figure or classification changed. **Not spot-checked** |
| r7.1 | spot check r7 PASS_WITH_LIMITATIONS (F-LOG-0321): m1 E4 wording; m3 §5 basis split; m4 §6.4 addendum; m5 KC:228–235 and KC:231; m2 disclosed (approved addendum not edited). No figure or classification changed. **Not re-checked** |
| r7.2 | m2: clarification appended to the one-page choice at the human's direction (F-LOG-0322); §9 updated. Final short check PASS_WITH_LIMITATIONS (F-LOG-0323) |
| r7.3 | F-LOG-0323 bookkeeping: f1 header, f2 §9 review status, f3 sync row, f4 CONTEXT, f5 session-log attribution. Status pointers only |

**Standing qualifier:** one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable. All analysis Claude-family (Λ7).

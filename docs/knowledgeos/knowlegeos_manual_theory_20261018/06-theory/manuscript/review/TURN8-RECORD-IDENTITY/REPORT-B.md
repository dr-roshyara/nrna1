TURN 8 REPORT B

Reviewer B (Sonnet). The independence is process independence only. Topic review of §9.17, step 2 (completeness) and step 3 (validity). I re-read my saved inventory first (STEP1-INVENTORY-B.md). I did not open reviewer A's inventory or report. I edited nothing.

## 1. Assessment

- **Reliability.** The core identity result is reliable within scope. A predicate on entries cannot distinguish byte-identical stores, so which store is the record is not a property of its entries (R1). K2-r implies the old K2 (R2). Both are near-trivial but valid.
- **Weak results.** R3(a) and R3(c), the "only if" of R3, and R4's "together sufficient / each independent" are weaker than stated. R4's sufficiency is true by construction of the model. Its conditions are inconsistent with one another as defined.
- **Largest risk.** CON-0262's "iff" definition of membership classes the estate's own carried-across (byte-copied) entries as non-members. CON-0263's "iff", its independence claim and its interval clause do not survive scrutiny.
- **Critical findings:** none. The only dependants on these claims are the provisional K1 cell and register rows, and the K1 cell states the interval limit itself.

## 2. Findings (ranked)

| ID | Sev | Type | Claim (line in §9.17) | What is wrong | Consequence | Correction |
|---|---|---|---|---|---|---|
| B-01 | high | defect | Membership "iff a kernel step appended it while the designation named its store" (L26; register CON-0262) | Entries brought into the new store by byte-copy are not kernel appends. S0300 Phase 3 copies the history before the switch, and §8.20 C2 notes the new store "already holds the copies". By the definition they are non-members of ρ′. The section never separates mechanical writes from governance appends (S0300 L888). | The definition misclassifies the flagship case. R3(c) "within E ⇒ of ρ" disagrees with the "iff" at the second switch. K1 and K2-w are silent on carry-across. | A record persists across a carrier change. Membership is by append while designated, or by designated carry-across. Say which writes count under K1 and K2-w. Register wording: "if", or the extended "iff". |
| B-02 | high | defect | A1, A2, A3 are "independent"; "A1's again is necessary, not sufficient" (L133, L160, L164); register "iff" | Three defects: (a) A1 is defined as "taken after the last write before the act". With the check before the act, a write in the check–act interval violates A1. So A1∧A2∧A3 already excludes the interval, yet L158-160 treats the interval as a limit. (b) If A1 means coverage, A2 is derivable from it. A reference derived from a lossy survivor cannot cover every written entry. My exhaustive rerun of the 32,000 scenarios under coverage-A1 gives 0 witnesses that A2 is necessary. Independence holds only for the model's timing reading of A1 (`ta ≥ last write`). (c) The register's "detects every loss iff A1∧A2∧A3" is false at scenario level: in 17,460 of 32,000 scenarios a condition fails yet every loss is detected. | The "three independent necessary conditions" and the interval clause contradict each other. A1 is also not CON-0243's cut condition, which is procedural ("taken again at the operation"). | Fix one reading of A1. If operational (`ta` = check time), sufficiency fails in the interval. If semantic, it is an unverifiable world condition. Show A2 independent under that reading, or fold A1 and A2 into "reference = intended state, computed independently". Drop "iff". |
| B-03 | high | defect | R4(i) "every loss is detected"; "no false alarm" (L138-144) | The proof step "A1∧A2 give reference = content that ought to survive" needs exactness (the reference lists nothing extra), which is not a condition. The loss definition (L27) includes entries written and then lost from the source itself. S0300 L428-444 measured exactly this, and CON-0245 is VALIDATED. A source-derived reference cannot show it. In the model `writes` only ever adds entries to the source, so with A1–A3 the reference equals the source and the survivor is a subset of it. Sufficiency is then definitional. | The "model check" adds no evidence beyond the definitions. R4(i) holds only for copy loss, not for "loss" as defined. | Scope "loss" to "relative to the source at ta". Add exactness and the equality relation (byte or identity, CON-0240; entry vs file, CON-0249). Say the model shows non-degeneracy, not sufficiency. |
| B-04 | high | defect | R3(a) "content cannot decide membership"; heading and §9.17.1 item 3 "decidable only if recorded with an enumeration"; (c) | (a): in D each entry carries a recording time τ, non-decreasing along positions (H2, §9.8 L37). Given a designation time and trusted time (L6), "τ < switch" is a content predicate, so (a) is not unconditional. It holds for an estate without trusted times (2 of 216), which is not stated. Also two byte-identical entries in one store are one act under §9.16 R2, so the (a) example is realisable only across stores (that is R1). (c)'s proof assumes E is an exact atomic snapshot ("every member written before t_E is in E"), which is unstated. "Only if" is not proved. Shared order sources and epochs refute it. | CON-0262's "only if" and "decidable" are overclaimed. The result is "decidable up to the window, given no usable clock". | Add premises: no trusted recording time (¬(H2∧L6)) and E an exact snapshot. Restate as "if"; mark "only if" as relative to CON-0251's premise. |
| B-05 | high | defect | R3(d) "closing record lies outside the record it closes" (L117) | CON-0254(i) itself says a closing entry can describe a state that includes itself, and only a hash cannot. A list-form enumeration can name its own closing entry, so "would fall outside E" is false for lists. The real reason for placing it elsewhere is S0300's evidence-of-write loop (PREMISE 2, L801-829), not membership. S0253 RV-1 (step (iii)) is not integrated. | CON-0254 is restated with its qualifier dropped. | Say: hash forms cannot self-cover; the estate's placement answers a termination argument. Cite PREMISE 2 as the premise. |
| B-06 | high | defect/omission | K2-d/w/r as three parts; "kernel assumptions K2-w, K2-r; K2-d is L11" | (a) The independence table has no K2-d model. K2-d's clause "every component means Ρ" is K2-w∧K2-r by another name. (b) Parity with K3 is unresolved: K3 makes an input "altered outside the kernel's steps" a kernel failure, and a designation is outside by definition. (c) The designation history (R3(b)) must live in some record, and that record needs a designation. This is a regress, like L5/F31. It appears only as an open question. | The classification is consistent with §9.15.2 for w and r. It is not shown for d. | Give K2-d a countermodel or fold it into definition plus w/r. Resolve the K3 parity. Name the regress as a located assumption. |

Normal findings:
- **B-07.** R1's "Src" items are analogies. S0917 and S0904 concern the independence of evidence, and S1375 L83 concerns authority per fact. Only S0300 is on point. S2055 L745-757 is an open test in a commission. Homonyms (record, authority) are not qualified.
- **B-08.** CON-0251's "if" half is attributed to K2-r. Its real source is D's earlier-only citation rule.
- **B-09.** The restated K2 has no must-do. The section discloses this ("nothing here claims that it does"). A do-nothing kernel satisfies K2-w and K2-r vacuously. The capabilities belong to §9.10, and the section should cross-reference it.
- **B-10.** CON-0258's "some change falsifies a guarantee" classes almost any parameter as an invariant. The intended contrast needs a different quantifier. CON-0162's test is order-dependent for symmetric dimensions. I could not check either against the copy's findings, which are not among my allowed files.
- **B-11.** Omissions in the book: (i) the reader's predicate is part of resolution (§8.20 C4), but "Resolution" covers only the store; (ii) the relation of ρ to S2055's H_t and K (open there) is not stated; (iii) L11 does not scope the competence (S1375: authority by domain, proposition, scope and time).
- **B-12.** CON-0254(ii)'s "forced" non-termination depends on PREMISE 2 applying without exemption to the evidence-copy. This is the A-16 premise, and it is a design choice. S0142 notes that six of the seven placements are forced and one is chosen.

## 3. Concept verdicts

| Concept | Verdict | Reason and scope |
|---|---|---|
| R1 | VALIDATED | A predicate on entries cannot separate identical stores. Scope: predicates on entries only. |
| K2-r ⇒ old K2 | VALIDATED | A sound one-step derivation. It rests on "named by its entries" and content identity (§9.16 R2). |
| CON-0261 | PROVISIONAL | K2-w and K2-r are sound. K2-d has no independence model and the K3 parity is open. M2 equivocates (appendix). |
| L11 | PROVISIONAL | Independent of L5 (model: two identical stores, an unauthorised designation). Competence is not scoped. |
| CON-0262 | INVALID as stated | The "iff" fails on carried-across entries (B-01). The "only if" is unproved (B-04). |
| CON-0263 | INVALID as stated | "iff", "independent", and A1/interval inconsistent as stated. Restatable. |
| CON-0251, 0254, 0257 | PROVISIONAL | B-08, B-05, B-12. |
| CON-0258 | PROVISIONAL | Quantifier problem (B-10). |
| CON-0239, 0240, 0242, 0245, 0250, 0253 | stand as recorded | I found no defect. I did not independently re-derive them (see Limits). |

## 4. Coverage against my inventory (ranked gaps)

| Inventory item | Status | Note |
|---|---|---|
| S0300 two write classes, PREMISE 2 (item 10) | MISSING / DISTORTED | B-01, B-05. |
| S0300 density ≠ completeness, source-side loss (item 5) | MISSING from R4's loss scope | B-03. |
| S0300 reader predicate `*.json` as part of the record (item 3) | MISSING | B-11. |
| S0300 entry identity failures (item 8) | MISSING | Not engaged in §9.17. |
| S0300 content-defined boundary, residual windows (items 4, 12) | COVERED | R3(c), R4. |
| S0300 reference relative to a manifest (items 6, 7) | COVERED | A1/A2. |
| S0300 readers advisory (item 9) | COVERED | K2-r. |
| S0300 quarantine hold, OPEN-M7 (item 11) | JUSTIFIABLY EXCLUDED | CON-0250 is out of scope. |
| S0253 RV-1 (step (iii) evidencing append) | MISSING | B-05. |
| S0253 specification-only limit | MISSING from the section's labels | The estate claims rest on S0300 and its reviews, which are spec-level. |
| S0917 anchor needed for truncation | COVERED | A2. |
| S0917 raw vs canonical hash, identity levels | MISSING in R4's equality relation | See B-03. |
| S0904 frontier, Consistent ≠ Complete | MISSING | A node cannot see its own gap. |
| S0904 convergence (same H ⇒ same K) vs K2-r | OPEN | Not related to §9.17. |
| S1375 authority bounded; repository records authority | MISSING for L11's scope | B-11. |
| S2055 K vs record (H_t part of K?) | OPEN | B-11. |

New fork: the regress of the designation record (B-06c).

Source defects (list only; the theory relies on none):
- S0904: "Three possible merged states" lists four.
- S0917: Au and Auth are swapped.
- S1375: four versus five owners.
- S2055: a boxed assertion against a hypothesis-only framing.
- S0253: RV-5 and RV-6.
- anchor_model.py: the printed witnesses for A2 and A3 omit the `indep` and `opv` flags, so they look identical.

## 5. Next steps (dependency order)

1. Decide the referent of "the record" (carrier vs persisting record). Add the two write classes. Done when B-01 and B-06a-b close and the register CON-0262 wording matches.
2. Restate A1 and re-run the model with coverage-A1, an exactness condition and a source-side-loss transition. Done when independence is shown or A2 is folded in, and the interval clause is consistent.
3. Add the R3 premises and mark "only if" as relative to CON-0251's premise. Done when the heading, §9.17.1 item 3 and CON-0262 agree.
4. Correct R3(d) against CON-0254 and name PREMISE 2 and the regress. Done when the CON-0254 text is quoted correctly.
5. Question for the Principal only: is the designation-record regress a located assumption, as L5 is, or a deferral?

## 6. Limits

- I did not independently re-derive CON-0240, 0242, 0245, 0250, 0253, 0260 or the Ch. 2 and Ch. 3 re-verdicts.
- CON-0258 and CON-0162 were judged only from their register wording. I could not check them against the copy's findings or S0021.
- The A-16 premises were checked at C24, CON-0249 and CON-0254 only.
- I did not run the quote check or R1/R2.
- I did not open Ext sources. §9.17 marks them not opened.

## Appendix

### A. Challenge adequacy (§9.17.4)

| Row | Judgement | Reason |
|---|---|---|
| R1 | adequate | A self-naming entry is copied with the content. |
| R2 | inadequate | The rival "identity inside K1" is not answered. The falsifier is met by the section's own "K2-w alone" model, so it is mis-specified. M2's K1 and K2 refer to different stores; with one referent (ρ) in both, M2 is not a countermodel. R2 is a re-axiomatisation (it adds the referent), not a derivation. |
| R3 | inadequate | The epoch rival is covered. The strongest counterexample, recording time τ (H2) with trusted time (L6), is not. |
| R4 | inadequate | Strongest counterexamples: source-side loss before `ta`; the A1/interval inconsistency; A2 derivable from coverage-A1. The listed falsifier is met by source-side loss under the section's own loss definition. |
| L11 | adequate with a caveat | A definition. The rival "designation as part of A1 recognized" is unevaluated. |

### B. Proof-step tables

**R2, K2-r ⇒ old K2**

| Step | Justification | Defined in book? |
|---|---|---|
| Two readers, same cut named by entries | premise | yes (§9.16 R2 (d)) |
| Each value is a prefix of ρ | K2-r | "prefix" ambiguous in D (records are sets with ties unordered); yes in a store |
| Same named entries ⇒ identical | "named by entries" plus content identity | yes (§9.16 R2) |
| Tombstone removal acts | tombstones are entries of ρ | yes |

Verdict: adequate.

**R2, M2**

| Step | Justification | Missing premise |
|---|---|---|
| Writer resolves s ≠ ρ for e | S0300 L578, a code-level possibility, not observed | none |
| All readers resolve s | premise | none |
| K2 holds | agreement on s | none |
| K1 holds | referent ρ | K1 and K2 refer to different objects |
| e is not an entry of ρ | membership definition | s holds the act that e annuls (D's earlier-citation rule), so s is a replica. Its creation is a non-kernel copy. |

Verdict: inadequate (a premise is missing).

**R3(a)**

| Step | Justification | Defined? |
|---|---|---|
| e at t1 while s is ρ; e′ byte-identical at t2 after the switch | premise | realisable only if τ is absent or untrusted |
| e is of ρ, e′ is not | definition | the definition is incomplete (B-01) |
| No content predicate separates them | identity of content | holds only if H2 and L6 do not give τ |

Verdict: inadequate (premise missing).

**R3(c)**

| Step | Justification | Defined? |
|---|---|---|
| E taken at t_E | premise | yes |
| Every member written before t_E is in E | not stated | E exact and atomic is not stated |
| An entry outside E was written after t_E | follows from the previous step | yes |
| A write after the switch is not of ρ | definition | yes |
| A write in [t_E, switch) is misclassified | consequence | yes |

Verdict: sketch, not proof.

**R4(i)**

| Step | Justification | Missing premise |
|---|---|---|
| A1∧A2 ⇒ reference equals the content that ought to survive | stated | exactness (no extra entries); the "written" scope |
| A3 ⇒ the survivor is compared | stated | none |
| A lost entry is in the reference and not in the survivor, so they differ | follows | none |

Verdict: sketch, premise missing.

**R4(iii)**: valid within the model. It is a product of three switches, so each condition's necessity is built in. The A2 witness equals the A3 witness except for the flags.

### C. Trivial cases and must-do

The empty record and the refuse-all kernel satisfy K2-w and K2-r vacuously (see B-09). The model's "no false alarm" line addresses the refuse-all check correctly.

### D. R3 inbound citations for the proposed corrections (`inbound_citations.py`)

| Target | Citing lines |
|---|---|
| CON-0262 | chapter_08/08.19 L236; chapter_09/09.17 L90; register L263; THEORY-MAP L361 |
| CON-0263 | 08.18 L212; 08.21 L182; 09.15 L74; 09.17 L128, L164; 09.99 L62; register L244, L258, L260, L264; THEORY-MAP L361 |
| CON-0254 | 08.00 L34; 08.20 L62, L67, L92, L101, L246, L263; 09.17 L117; register L253, L255, L263; THEORY-MAP L189; BOOK-OUTLINE L224 |
| CON-0261 | 08.18 L211; 09.15 L75; 09.17 L57; 09.99 L61; 09/TODO L18; register L240, L262; THEORY-MAP L361; BOOK-OUTLINE L176 |

### Files opened

- /home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/03-methodology/REVIEWER-INSTRUCTIONS.md
- …/06-theory/manuscript/review/TURN8-RECORD-IDENTITY/BRIEF-STEP1.md
- …/06-theory/manuscript/review/TURN8-RECORD-IDENTITY/BRIEF-STEP2.md
- …/06-theory/manuscript/review/TURN8-RECORD-IDENTITY/STEP1-INVENTORY-B.md
- …/06-theory/manuscript/review/TURN8-RECORD-IDENTITY/RESEARCH-QUESTION.md
- …/06-theory/manuscript/chapter_09/09.17-the-designated-record-and-its-anchors.md
- …/06-theory/manuscript/chapter_09/09.15-the-trust-boundary.md
- …/06-theory/manuscript/chapter_08/08.18-…md
- …/06-theory/manuscript/chapter_08/08.19-…md
- …/06-theory/manuscript/chapter_08/08.20-…md
- …/06-theory/manuscript/chapter_08/08.21-…md
- …/tmp/tools/anchor_model.py
- …/tmp/tools/anchor_model-run-20261024.txt
- …/tmp/tools/proof_step_check.py (head only)
- …/tmp/tools/inbound_citations.py (head only)
- The persisted output of the register query (a tool-results file holding the CONCEPT-REGISTER.tsv rows)
- The six step-1 sources (S0300, S0253, S0917, S0904, S1375, S2055), read in step 1.

### Searches and runs

- Register rows for CON-0162, 0191, 0193, 0239, 0240, 0242, 0243, 0245, 0248-0251, 0253, 0254, 0257-0263 (python3 -I csv read of CONCEPT-REGISTER.tsv).
- `grep` for "K2" in 09.99-change-log.md.
- `grep` for "covers" in 09.13-the-evaluator.md.
- `grep` for "R2" in 09.16-reassessment.md.
- `grep` for "recording time" and "H2" in chapter_09 sections 09.08, 09.12, 09.13, 09.15 and 09.16.
- `inbound_citations.py` for CON-0262, CON-0263, CON-0254 and CON-0261.
- `proof_step_check.py` on 09.17 (0 lemmas scanned; the tool matches "Lemma/Theorem" headings only).
- Two scratch scripts, run with `python3 -I`, outside the repository (/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/chk.py and chk2.py). They re-ran the 32,000-scenario model.
  - They counted scenarios where a condition fails yet every loss is detected: 17,460.
  - They confirmed that under coverage-A1 there are 0 witnesses that A2 is necessary.
  - They confirmed that with A1–A3 the reference equals the source by construction.

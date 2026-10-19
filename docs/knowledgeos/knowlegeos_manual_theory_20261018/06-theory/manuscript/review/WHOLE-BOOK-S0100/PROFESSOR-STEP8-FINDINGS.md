# Whole-book review S0100: the Professor's step-8 check and findings

*2026-10-19. Written by the Professor before the first reviewer commission, and committed before it. **Never given to the reviewers.** Under D2 (the Rule Reviewer's ruling, confirmed by the Principal) these findings are **scored as the Professor's own detection** and **fixed in stage 2**, not before. The book is read here only as `BOOK-UNDER-REVIEW/` (the frozen copy); the working book was not opened and nothing was compared.*

**One-time deviation (to be repeated in the stage-1 settlement):** Rulebook v1.3 §2 step 8 says "fix what the check finds before commissioning the review". For this review, the freeze and the blindness of the test take precedence (D2). The findings below are recorded, not fixed, and the briefs carry the dependency structure only, with no assessments. This supersedes resolution 7.2 of the first plan ("step-8 findings go into the brief as known issues").

## 1. Scope and method

**What the check covers:**
- **A. The changed theory since the last review** (step 9): the C18 settlement's changes to §8.11 (C1–C8, plain language, §8.11.4), the §1.14 claims it touched (1.14.1-7, 1.14.2-1, 1.14.3-2, 1.14.5-1; the 1.14.1-4, 1.14.5-14 head and wording changes), CON-0157–0159. Because definitions changed (CON-0157, CON-0159 restated), the check runs **claim by claim** along "rests on" and § citations (entry 55), not by section.
- **B. Falsification** (§2 step 2, entry 55) of each materially changed concept: (a) counterexample, (b) boundary, (c) rival explanation, (d) falsifier. The C18 settlement predates entry 55 and recorded none.
- **C. Book-wide mechanical checks** that bear on step 8's "unverified" rule: register status against basis and review fields; bare citations of INVALID concepts outside "what does not hold"; references completeness. Each is a list of **candidates**; the ones below were read and judged.
- **D. The dependency structure per part** (graph `--up` per section and concept, unverified marked), which goes into the briefs without assessment.

**What it does not cover:** a reread of the whole book (step 8: "check only the relevant dependencies"); the content of claims not changed since their last review. That is the reviewers' task.

**Method record** (entry 55), for the computational parts:
- *Question:* which items rest on the changed concepts, and which dependencies lack a VALIDATED verdict.
- *Method:* `tmp/tools/dependency_graph.py --root BOOK-UNDER-REVIEW` (the approved `--root` change; output in `PROFESSOR/DEPENDENCY-GRAPH-COPY.txt`); scripts for the register and reference checks (output of the latter in `PROFESSOR/STEP8-REFCHECK.txt`).
- *Assumptions and limits:* explicit references only. **The graph's downstream lists do not discriminate in this book:** one strongly connected component holds 139 nodes (77 sections, 62 concepts), so `--down CON-0157` lists 142 items, nearly the whole book. The claim-level reassessment was therefore done by hand along the § citations of §8.11 and of the concepts. The reference check matches `S nnnn Lnn` patterns against each section's references table; it misses anchors written another way and flags tables that list sources as "full". Every finding below was verified in the copy's text, and source claims were verified in the source.
- *Effect on the conclusions:* the graph gives the candidate list; the judgements are by reading.

## 2. Step 8 for the changed sections

**§8.11 rests on** (direct, explicit): §1.12; §1.14 (1.14.3 role laws, 1.14.5); §5.6; §5.7 C1 (CON-0144, INVALID with restated form); §8.6.2 (CON-0125, PROVISIONAL); §8.9 C2, C10, F32 (CON-0151, PROVISIONAL); §8.10 C2 (CON-0156, VALIDATED, scoped), C5 (CON-0155, INVALID with restated form), C6; §9.8 H4, M4/M5 (CON-0148, INVALID with restated form). **All dependencies except CON-0156 are unverified** (no independent VALIDATED verdict).

**Downstream of the changed concepts** (claim by claim): §1.14.2-1 further evidence (§8.11 C4); §1.14.3-2 further evidence (§8.11 C1, C2, C3, C4, C6); §1.14.5-1 further evidence (§8.11 C6, CON-0159); §9.9 L13, L16 (home lists: "§8.11 C1"; §9.9 uses only "self-declared and not attestable", which the restated C1 keeps). No other section cites §8.11 or CON-0157–0159.

| Dependency | Definitions preserved? | Results contradicted or changed? | Omissions? |
|---|---|---|---|
| §8.6.2 factors (process lineage, working context, resume) | yes | no | — |
| §8.9 C2 Ded, E(o) | **no**: C7 widens the drop condition (PS8-3) | **yes**: "drops only when …" silently extended | the CON-0158 limb |
| §8.10 C5 (authorship by the writing act, intact transcripts) | yes | no | **C1 omits C5's assumptions** (PS8-14) |
| §9.8 H4, M4, M5 | yes | no | **mis-cited** (PS8-5) |
| §1.14.1-12 | — | — | **mis-cited** for truth vs standing (PS8-4) |
| §1.14.5-3, 1.14.5-13 (a) | yes | no (but see PS8-8, PS8-11) | — |
| ARCH-INV-1 (1.14.2-1) | yes | no | **the "challenging" label does not fit** (PS8-7) |

## 3. Findings

Severity: critical / high / normal (REVIEWER-INSTRUCTIONS). Each is labelled fact (F: checked in text or source), deduction (D) or open question (Q).

**PS8-1 · high · §8.11 C1, L32 · residue of the refuted form.**
- *Evidence (F):* C1 says "**So the correcting session's self-declaration was false: self-declared identity failed in one direction.**" The source says the opposite: the correction "measured its OWN identity correctly" and read "this session's transcript carries `4858c37c`" as "**true**" (S0099 L46); the correction's own table labels its identity claim True (S0100 L68). The book's own plain language (L24) and CON-0157's meaning say every self-declaration was true.
- *Reasoning (D):* the false statement was the correction's claim that the **refusal's** identity claim was false (S0100 L61, L105), a consequence of the misattribution. The sentence keeps half of the refuted headline "failed in both directions".
- *Why high:* it contradicts the restated claim of a critical finding's settlement, inside the same claim.
- *Affected:* §8.11 C1; CON-0157 (restated form).
- *Correction (stage 2):* restate the bullet: no self-declared identifier was shown false; what was false was the correction's verdict on the refusal's claim; the source's "false-bar" class remains a risk (S0100 L95).
- *Resolved when:* C1 contains no claim that a checked self-declaration was false, and the quote check passes.

**PS8-2 · normal · §8.11 plain language and CON-0157 · over-claim.**
- *Evidence (F):* "Every self-declared identifier in the episode was true" (L24; CON-0157: "every self-declaration in the case was true"), while C1 itself says "the erratum's own self-declaration is not checked in the sources" (L34).
- *Correction:* "every self-declaration the sources check was true; the erratum's is unchecked".
- *Resolved when:* plain language, C1 and the register say the same thing.

**PS8-3 · high · §8.11 C7 against §8.9 C2 · a derived result silently changed.**
- *Evidence (F):* C7: "It fits §8.9 C2's restated form: the pool drops when a process authors what the object is shaped by." §8.9 C2's Ded: E(o) "drops **only** when an eligible process authors o or what o protects", and "verifying did not make them so". The source of C7, "each completed assurance pass removes another process from the eligible pool" (S0100 L49), says verifying **does** remove a process, from the pool for the next object, through CON-0158's mechanism (findings that the next object takes up).
- *Reasoning (D):* "shaped by" is C3's (CON-0158) extension, Hyp and provisional. C7 imports it into a Ded result without restating the result or carrying the Hyp label.
- *Why high:* a derived result with "only" is changed by citation, and F32 depends on it.
- *Affected:* §8.9 C2 Ded (CON-0151), F32, §8.11 C3, C7.
- *Correction or investigation:* either restate §8.9 C2's drop condition with a third limb, "or authors findings that o directly takes up (CON-0158, Hyp)", conditional on CON-0158; or restate C7 as an extension, not a fit. Check whether "verifying did not make them so" still holds for later objects.
- *Resolved when:* §8.9 C2 and §8.11 C7 state the same drop condition, and CON-0158's status travels with it.

**PS8-4 · normal · §8.11 C7 · citation does not carry the claim.** "truth and standing come apart (§1.14.1-12)"; 1.14.1-12 is "Current authority is not standing", a different pair. *Correction:* cite a home that states truth ≠ standing (candidates: §1.12 verifiable ≠ valid; "Recording ≠ Asserting", §9.8 M5), or mark it Int with no citation. *Resolved when:* the cited home states the distinction.

**PS8-5 · normal · §8.11 C4 · M4 and M5 merged.** "Int (§9.8 M5): … refusing everything satisfies it, and nothing obliges anyone to act." Refusal satisfying a must-never is M4's trivial-solution test (H1′–H3 must-never, H5 must-do); M5 is the unattested author. *Correction:* cite M4 for the trivial solution and M5 for self-declaration. *Resolved when:* each half cites its model.

**PS8-6 · normal · references tables (form; book-wide).**
- *§8.11 (F):* lines cited in the body and absent from the table: S0097 L5, L21, L27–31, L41, L53, L75, L77; S0098 L101–108, L235–266, L337; S0100 L78, L88, L130–135, L140, L142.
- *Book-wide (candidates, F for the counts):* 128 (section, source) pairs cited in a body but absent from that section's table, and pairs whose cited lines are missing from it; 35 section files have no "References" heading (Ch. 0; §1.1–§1.11; others). List: `PROFESSOR/STEP8-REFCHECK.txt`. Some may use another references form; not judged one by one.
- *Correction:* complete the tables, and state the references form for Ch. 0 and §1.1–§1.11. *Resolved when:* the check reports no missing pairs, or each remaining one is explained.

**PS8-7 · normal · §1.14.2-1, further evidence labelled "challenging".** "the engine checks only that such a reference is present, and no gate reads process identity … a START by a barred process would have been recorded". This does not challenge ARCH-INV-1 ("records authority, never grants it"): recording a START does not grant authority. It challenges the record's **soundness** (§9.8 H4) and the enforcement of a bar (§8.11 C4). *Correction:* relabel it as related, bearing on H4, or argue the challenge. *Resolved when:* the label matches what the evidence bears on.

**PS8-8 · normal · §1.14.5-1, further evidence · an analogy stated beyond its scope.** "… which is the same ordering failure." A gate closes a question by a mechanism with a normative effect; an analysis has none (1.14.5-3) and takes effect only through an adoption. What is the same is the **order**: settled before any recorded act. The rest differs. Unqualified, the sentence re-imports part of CON-0159 as stated ("as a gate does"). *Correction:* say what is the same and what differs. *Resolved when:* the paragraph cannot be read as the refuted form.

**PS8-9 · high · the register (book-wide) · restated forms have no ID or status.**
- *Evidence (F):* 27 of the 84 INVALID rows carry a restated meaning whose status (usually PROVISIONAL) appears only in the basis or history text (CON-0003, 0007, 0009, 0015, 0020, 0025, 0028, 0030, 0031, 0037, 0039, 0043, 0050, 0052, 0054, 0061, 0135, 0137, 0142, 0144, 0145, 0146, 0148, 0155, 0157, 0159, 0161). Sections tag restated claims with the bare INVALID ID (§8.2 C3 `[CON-0106]`; §5.5 C1 `[CON-0109]`) or cite it as a home (§8.7 L14, `[CON-0123, CON-0124]` for the restricted form).
- *Reasoning (D):* step 8's rule ("a dependency on a concept without an independent VALIDATED verdict is marked unverified") and the graph see the **refuted** concept, not the restated one. A dependency on a restated form cannot be classed, and a reader of a tag cannot tell which form is meant.
- *Why high:* it disables the unverified rule mechanically across the book.
- *Correction (already on the stage-2 list):* give each restated form its own CON-ID with its status, linked by same_subject_as. Retag §8.2 C3, §5.5 C1, §5.6 C3, §8.7 L14 and the other bare tags.
- *Resolved when:* no INVALID row carries a live restated meaning, and the bare-tag check reports 0.

**PS8-10 · normal · the register · stale basis and review fields.** 42 rows (CON-0099 … CON-0141) are reviewed (column "reviewed" filled, history shows the verdict) while "basis" still says "not yet reviewed". 13 PROVISIONAL rows have an empty "reviewed" column though "basis" or "follow_up" names the review (e.g. CON-0158 "PROVISIONAL at review C18"). *Correction:* update basis and reviewed from the history. *Resolved when:* no row says "not yet reviewed" with a review recorded.

**PS8-11 · normal · CON-0159 (restated) · not testable as stated.** The claim is that an analysis that **settles** the questions it prepares risks an implicit adoption. Recommending is not a normative effect (1.14.5-3), and the line between preparing with a recommendation and settling is undefined. *Correction:* define "settles" (for example: the analysis forecloses alternatives that its commission asked it to keep open), or mark the claim not testable. *Resolved when:* a falsifier can be stated (see §4).

**PS8-12 · normal · §8.11 C2 Int · conditional on a definition.** "An option based on information lineage would leave no eligible verifier for any verification of uptake" holds if the option bars anyone who **read the barred process's outputs**. It does not follow if the option bars only sharing the barred process's **inputs**. *Correction:* state the definition the argument assumes. *Resolved when:* the argument names it.

**PS8-13 · normal · stale C18 step-8 record (process).** The C18 step-8 table recorded "C7 fits the restated pool" (`review/C18-S0097-S0100/STEP8-AND-SCAN.md`), which PS8-3 shows does not hold as a fit. This concerns the record only; it is corrected through PS8-3.

**PS8-14 · normal · §8.11 C1 "How it was settled" · omitted assumptions.** C1's settlement rests on the writing-act provenance of §8.10 C5, whose stated assumptions are intact transcripts and a verifier's self-declared mapping to its transcript. Here the analysis is the stop record's, written by `2da45a86` (S0099 L44: "Which session is writing *this* document? `2da45a86`"), a self-declaration. C1 says the transcripts are keyed by identifier, but it does not carry these assumptions. *Correction:* carry §8.10 C5's assumptions into C1. *Resolved when:* C1 states them.

**PS8-15 · normal · step 9 gap (process): the TT4 settlement's changes were never reviewed.**
- *Evidence (F):* the TT4 settlement (`review/TT4-trust/SETTLEMENT.md`, "Changes") changed §9.9 (rewritten), §9.8 M5 and result 6, §1.13.2-3, §1.13.1-3, §5.7 C4, §8.10 C10, §8.10 C2, register CON-0160, CON-0161, CON-0151 and map F23, F34. The next review, C18, listed only §8.11, §1.14 and CON-0157–0159 (ACCESS-RECORD, entry 18:50:07Z; its report does not mention TT4).
- *Effect:* step 9's rule ("no correction stays unreviewed until the next consolidation") was not met for TT4's settlement text.
- *Correction (this review):* the briefs list TT4's settlement changes as changed theory beside C18's (P1: §1.13.1-3, §1.13.2-3; P3: §5.7 C4; P4: §9.8 M5 and result 6, §9.9, CON-0160, CON-0161; P5: §8.10 C2, C10, CON-0151).
- *Resolved when:* both settlements' changes have been reviewed in this review.

## 4. Falsification of the changed concepts (§2 step 2, entry 55)

| Item | (a) Strongest counterexample | (b) Boundary | (c) Rival explanation | (d) Falsifier |
|---|---|---|---|---|
| **CON-0157 restated** (misattribution of an act in inherited context) | The erratum calls itself "the SECOND instance in the OPPOSITE direction — a process wrongly claiming NOT to be barred" (S0100 L128). If C2 is answered "barred", a self-declared *eligibility* was false. The book holds that this presupposes C2: **adequate**, conditional on C2. "Every self-declaration true" is too strong (PS8-2). | It should stop holding where the compacted summary marks inherited acts as inherited (provenance in the summary). The claim is about first-person summaries. | The correction's own diagnosis: identity "INFERRED FROM THE ESTATE" (S0100 L82), a reasoning error that any session reading the estate could make, without inheritance. The book prefers the stop record's reading (the summary names `2da45a86` five times, S0099 L44 row 4; L47). That reading is itself a self-declared analysis (PS8-14). **Both kept open as mechanisms; the attribution failure stands either way.** | Transcript evidence that `4858c37c`'s summary did not carry the refusal history, or that `4858c37c` created the refusal (`cat >`). |
| **CON-0158** (author of a finding may not judge its uptake; direct uptake; Hyp) | A referee checking that their own comments were taken up (named in C3). Inside this lane: the reviewers' findings are checked by the next review, not by their authors, so this is not a counterexample. | It should stop at **meaning confirmation**: the author may say whether the correction understood the finding, not judge its adequacy. | The bar is a registered act on another track (S0100 L36): an estate **policy**, not a consequence of producer ≠ verifier. The book keeps it as Hyp: **adequate**. | A ratified rule, or a case in the estate, where the author lawfully judged uptake, with no loss of independence shown. |
| **CON-0159 restated** (scope verification; risk of implicit adoption) | An analysis that settles its questions and is then adopted by a recorded act: no implicit adoption occurs. This is consistent with "risks". | Under C1 = "records constitute authority" (§1.14.1), an unregistered adoption is no adoption at all, and the risk becomes a settlement **in practice** without standing. The claim should name this. | The source's concern is commission compliance (scope creep), not decision-making. The text supports both readings (S0097 L45–47). | **None statable until "settles" is defined** (PS8-11): **not testable as stated.** |
| **§8.11 C2 Int** (F32 option: inherited working context, kept apart from information lineage) | An option barring only the barred process's inputs leaves verifiers of uptake eligible (PS8-12). | It holds where every verifier of uptake must read the findings. | — | A definition of the information-lineage option under which a verifier of uptake stays eligible. |
| **§8.11 C5** (preconditions: the object exists, the assignment is registered; time-indexed) | None found. The A1 standing/existence split was checked against §1.6 and §8.10 C2. | It stops holding for acts that permit **future** objects (grants), as CON-0124's restriction already says. | — | A verification validly performed on an undelivered object. |
| **§8.9 C2 drop condition** (affected by C7) | S0100 L49: verifying removes a process from the pool for the next object (PS8-3). | — | — | Already met in its "only" form; restate it (PS8-3). |

## 5. Dependency structure for the briefs (no assessments)

Generated per part from the graph on the copy, with every dependency on a concept that lacks a VALIDATED verdict marked unverified, and every dependency outside the part marked. The tables go into `P1…P5/BRIEF.md` unchanged. For PS8-9's reason, a dependency on an INVALID ID can be a dependency on its restated form; the briefs say so neutrally ("an INVALID ID may carry a restated form in the same register row").

## 6. Counts

| Severity | Count | Findings |
|---|---|---|
| critical | 0 | — |
| high | 3 | PS8-1, PS8-3, PS8-9 |
| normal | 12 | PS8-2, 4, 5, 6, 7, 8, 10, 11, 12, 13, 14, 15 |

*Traceability:* Rulebook v1.3 §2 steps 2, 8, 9; §12c, §12d; D2 (the Rule Reviewer's ruling, confirmed by the Principal, 2026-10-19); the Principal's approval with corrections 1–4, 2026-10-19.

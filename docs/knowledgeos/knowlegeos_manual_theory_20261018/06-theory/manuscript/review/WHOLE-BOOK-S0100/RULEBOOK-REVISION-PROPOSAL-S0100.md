# Rulebook v1.4 amendment (proposed): checks after the whole-book review S0100

> **STATUS: PROPOSED v1.4 AMENDMENT — NOT IN FORCE, pending the Principal's approval** (the Principal, 2026-10-19: "Treat RULEBOOK-REVISION-PROPOSAL-S0100.md as the v1.4 amendment, NOT IN FORCE pending my approval; no second copy. On approval, the Rule Reviewer records it in v1.3 (entry 56) and in the v2 proposal."). RULEBOOK-v1.3 governs until then. This is the only copy of the amendment. It does not alter `03-methodology/RULEBOOK-v1.3.md` or `REVIEWER-INSTRUCTIONS.md`.

*2026-10-19. By the Professor, on the Principal's instruction after the scored stage-1 result (`88b6e3890`). Source of the recommendations: `PLANTED-GAP-RESULT.md` §5, recommendations 1–4.*

## 1. Summary

| ID | Change | Target in v1.3 | Mechanical / judgement | Tested on known cases |
|---|---|---|---|---|
| R1 | **Register-home check**: every register row is visible, with a matching status, in its canonical section | §2 step 8 (and the review brief) | mechanical list, human disposition | P5 / CON-0149: flagged (copy); not flagged (working book) |
| R2 | **Undefined-term check, narrowed** (three tiers) | §2 step 8 (and the review brief) | mechanical candidate list, human judgement | RX-3, "watermark" (copy): tier A; BC-7, "lane" (constructed fixture): tiers B, C; none of the four flagged in the working book |
| R3 | **Downstream-impact (inbound-citation) check for every accepted correction**, claim by claim | §2 step 8 (entry 55) and §6 settlement; §12c | mechanical list, human reassessment | P6b (§9.8 result 5 citing §5.7 C3): listed (copy) |
| R4 | **Step-by-step verification behind every "adequate" judgement** in a refutation table | §6 refutation-adequacy paragraph; REVIEWER-INSTRUCTIONS step 3 | human; a mechanical helper flags one pattern | P1 (Lemma D step 4): flagged (copy); not flagged (working book) |
| R5 | **Severity guidance for missing premises** in foundational lemmas, with criteria for "critical" | REVIEWER-INSTRUCTIONS "Severity"; settlement | human, by stated criteria | P1: classified critical by the criteria |

Tools (all read-only, in `tmp/tools/`):
- `register_home_check.py` (R1);
- `undefined_terms.py` (R2);
- `inbound_citations.py` (R3);
- `proof_step_check.py` (R4 helper).

Test outputs: `review/WHOLE-BOOK-S0100/PROFESSOR/checks/`. **None of the tests modified the frozen copy or its manifest**: the copy hashes identically to `PLANTED-GAP-COPY-MANIFEST.txt`, 119 of 119, after all runs.

## 2. R1: register-home check

- **Proposed rule text** (§2 step 8, new bullet): *"Before every review and every settlement, run the register-home check on the book. Every flag is disposed of in the step-8 file: corrected, or explained. A register row whose home section does not carry its CON-ID is a missing claim until shown otherwise."*
- **Input:** `06-theory/CONCEPT-REGISTER.tsv` and the section files (`--root` for a copy).
- **Procedure:** for every row whose book_location names a section file, check:
  - H1, the file exists;
  - H2, the file contains the CON-ID;
  - H3, every status tag `[CON-nnnn · …]` in the file agrees with the register status;
  - H4 (information), a tag that carries no status word.
- **Expected output:** one line per flag (ID, check, file, detail) and a count.
- **Limitations:** it checks presence and tag agreement, not meaning. A restated form living under an INVALID row is not detected (PS8-9 needs its own fix). An ID cited only in prose without a tag passes H2. A home given as a section range is not checked.
- **False positives and their handling:**
  - H4 flags non-status tags ("merged into ARCH-INV-1", "restated at TT1"). These are information; the step-8 file states whether the tag should carry a status.
  - H2 flags a row whose home moved. The fix is to the register.
- **Evidence to retain:** the tool output, dated, in the step-8 file; the disposition of each flag.
- **Test (known case P5):**

  | Run | Flags | Known case |
  |---|---|---|
  | copy | H2: CON-0138, CON-0140, CON-0143, **CON-0149**; H4: CON-0133, CON-0135–0137 | **CON-0149 flagged** (the plant removed result 4) |
  | working book | H2: CON-0138, CON-0140, CON-0143; H4: the same four | CON-0149 not flagged (the base has it) |

  **Real findings from this test:** CON-0138, CON-0140 and CON-0143 are missing from their home sections **in the working book**. These are three real register defects that the stage-1 review did not report. They are recorded for stage 2 (`STAGE2-READINESS.md`), not fixed here.

## 3. R2: undefined-term check, narrowed

- **Proposed rule text** (§2 step 8, new bullet): *"Run the undefined-term check. Every tier-A candidate is defined, deleted or explained in the step-8 file. Tier-B and tier-C candidates are read and disposed of for every new or changed section. The list is a reading list: a candidate is not a finding until a person judges it."*
- **Input:** the section files, the map and the register (`--root`), and the allowed corpus (`CORPUS-ALLOWED.tsv`; never sealed or hold-out files).
- **Procedure:**
  - **Tokens:** identifiers (`[A-Z][A-Za-z]{0,6}-\d+`, `[A-Z]{2,}\d+`) and words (four letters or more, plurals folded).
  - **"Defined":** a short span (at most four words, not a quotation) in a definition form: bold followed by a gloss, a definition bullet, a table's first column, "X:" at a line start, "X means / denotes", an expansion "X (Capitalised …)", or a register term. Families with a defining table are excluded (CON-, S-IDs, forks, to-dos and the like).
  - **Tier A:** undefined, and absent from every allowed corpus source. Identifiers count only if their prefix family has a single member in the book (a code family cited from a source has several). Words count only if used in at least one formal line (mathematical symbols, a definition bullet, a claim head).
  - **Tier B:** an undefined identifier that the corpus uses, and that the book uses in three or more sections.
  - **Tier C,** per section: an undefined word used at least three times in the section, used at least five times per cited source in the section's own cited sources, and marked as a term (backticked or bold) there at least once.
- **Expected output:** tiered lines (tier · token · counts · first location) and a count line.
- **Limitations:**
  - The definition forms are heuristics. A gloss written in running prose is missed (a false positive). A bold span with a colon counts as defined even when it is not a definition (a false negative).
  - Tier C depends on the section citing its sources by S-ID; it misses sections that cite only knowledge-transfer files.
  - Words of non-allowed sources (U) cannot be checked against those sources.
  - The run takes about 10 s on the whole book.
- **False positives and their handling:** tier A contains real candidates that are not errors (derived English forms such as "annulling"; the book's own mathematical terms such as "laminar", which then need a definition). The disposition in the step-8 file is one of: *defined now* · *ordinary English, no definition needed* · *deleted* · *explained (source code, cited)*. A disposition is a judgement and is recorded with its reason.
- **Evidence to retain:** the tool output per review, and the disposition table.
- **Test:**

  | Run | Tier A | Tier B | Tier C | Known cases |
  |---|---|---|---|---|
  | copy (whole book) | 42 | 26 | 9 | **RX-3: tier A. "watermark": tier A** (both absent from the corpus, both in §9.8) |
  | working book | 40 | 26 | 9 | RX-3 and "watermark" absent (correct negatives); BC-7 and "lane" not flagged (both defined in §6.5.2) |
  | constructed fixture: the working book with the §6.5.2 definitions of BC-7 and Lane removed (`PROFESSOR/checks/fixture-lane-bc7.diff`) | 40 | 27 | 12 | **BC-7: tier B** (40 uses, 7 sections). **"lane": tier C** (§6.5: 8 uses) |

  - **Narrowing achieved:** the Rule Reviewer's unnarrowed lists had 169 and 213 candidates. The narrowed tier A has 42 (2 of them the plants) and tiers B+C have 35. All four known cases are in the lists.
  - **Untested:** precision on unknown cases. The 40 tier-A and 35 tier-B/C candidates of the working book are not yet classified. Stage 2 classifies them, which gives the first false-positive count.
- **Why a fixture for "lane" and "BC-7":** the original case (consolidation S0036–S0065: "lane" and "BC-7" undefined in §5.5 C6 and §6.5, at commit `adbe16c5c`) can only be reconstructed from the manuscript's git history. A command reading that history was not permitted in this session. The fixture reproduces the defect by removing the two definitions that the consolidation settlement added. **A historical re-run is an open item (Q2).**
- **The original case at `adbe16c5c`** (read-only, on the Principal's permission; ACCESS-RECORD entry; output `PROFESSOR/checks/undefined-terms.adbe16c5c.txt`). **Reported as a known case, not as validation.** On the book at that commit, R2 as drafted flags **neither "lane" nor "BC-7"**:
  - BC-7 occurs in 2 sections, below tier B's threshold of 3;
  - "lane" occurs 7 times in §6.5 and 64 times in the 9 sources §6.5 cited then, but none of those sources backticks or bolds it, so tier C's term-mark condition fails.

  **R2 as drafted misses the original case.** The fixture had passed because the current §6.5 cites more sources. Lowering the thresholds would catch the case, but that would be tuning on it, so the thresholds are left as drafted. Whether to widen them is Q6.
- **Disposition of every candidate** (the false-positive count; `PROFESSOR/checks/R2-DISPOSITIONS-working.md`). Of 75 candidates on the working book:
  - **7 are true** (undefined terms or codes: KT-8, AR-11, C-1, CV-1, CAP-001, C-14, "port");
  - **4 are review labels;**
  - **64 are false positives:** 34 ordinary English, 15 defined in place but missed by the forms, 12 cited source codes, 3 tokenisation artefacts.

  False negatives are measured by the Rule Reviewer's next planted-gap test, with fresh cases.

## 4. R3: downstream-impact check for every accepted correction

- **Proposed rule text:**
  - §6, settlement, new bullet: *"For every accepted correction, the settlement runs the inbound-citation check on the corrected claim (and its concept ID) and lists the result in the correction's row. Each inbound citation is reassessed claim by claim (entry 55) and stated in the row: unaffected, corrected, or to be reassessed."*
  - §12c, integration review: *"The integration reviewer receives the inbound-citation lists of the corrections proposed in the part reports."*
- **Input:** the corrected claim ("§5.7 C3", "1.14.3-2", or a CON-ID), and the book (`--root`).
- **Procedure:** list every line in other sections, the register, the map and the outline that cites:
  - the claim exactly;
  - the concept ID tagged at the claim;
  - the section only (weaker; listed separately).
- **Expected output:** three lists (claim / concept / section-only) with file:line.
- **Limitations:** explicit citations only. A paraphrase without a citation is not found. A claim cited as "C3" after a section cited earlier in the paragraph appears only in the section-only list. This is why the section-only list is read, not ignored.
- **False positives and their handling:** section-only hits that do not concern the claim are marked "not about the claim" in the row.
- **Evidence to retain:** the list per correction, in the settlement row.
- **Test (known case P6b):** on the copy, `inbound_citations.py "§5.7 C3"` lists, at claim level, **§9.8 L95 (result 5, citing "§5.7 C3's rule")**: the stale citation that no reviewer reported. It also lists §6.7 C9 (three lines) and §9.8 L16 (existing homes), and 12 section-only lines. On the working book the same command lists the corresponding line, §9.8 L97 (result 6 in the base numbering), which there agrees with §5.7 C3.
- **Application to the real critical corrections (working book, read-only):**
  - §9.1 C2 / CON-0100 (P4-1): 5 claim-level citations, 7 concept-level, 27 section-only.
  - §2.3 (P2-2): 15 section-only.
  - §3.1 (P2-1): 15 section-only.
  - §9.9 (P4-4): 3 section-only.

  These lists are the starting point of stage 2's downstream reassessment (`PROFESSOR/checks/inbound.working.txt`).

## 5. R4: step-by-step verification behind every "adequate" judgement

- **Proposed rule text:**
  - §6, appended to the refutation-adequacy paragraph: *"A falsification attempt on a derivation (Ded, lemma, theorem, or a result built from them) may be judged adequate only with a proof-step table: each step, its justification (a stated premise, a definition with its home, an earlier step, or a cited result with its home), and whether each object the step uses is defined in the book. A step using an object the book declares undefined, without stating it as a premise, makes the derivation incomplete. The judgement is then 'inadequate: premise missing'. Without the table, the judgement is 'not assessed'."*
  - REVIEWER-INSTRUCTIONS step 3, "Proof checking": the same table, required.
- **Input:** the derivation and its section; for the helper, the section files.
- **Procedure (human):** fill the table step by step, and check every premise against the section's own statements about what is defined (for example §9.8.2, "CE … is not yet defined").
- **Mechanical helper:** `proof_step_check.py` flags a proof step that uses an object the same section declares undefined ("not yet defined", "not a function", "not established"), where neither the step says "(premise)" nor the hypothesis names the object.
- **Expected output:** the table in the report (human). The helper prints flagged steps.
- **Limitations:** the helper sees only formatted lemmas ("**Lemma …**", "**Theorem …**", "**Proposition …**") and same-section declarations; the book has one formatted lemma. Most derivations are prose "Ded" claims, which only the human table covers. The helper cannot judge whether a premise is true.
- **False positives and their handling:** a flagged object may be defined elsewhere. The reviewer states where, and the flag is closed.
- **Evidence to retain:** the proof-step tables in the review report; the helper output in the step-8 file.
- **Test (known case P1):**
  - On the copy, the helper flags **§9.8 Lemma D step 4** ("CE is applied to these arguments, so its value is determined"; CE declared undefined in §9.8.2).
  - On the working book it flags nothing: step 4 reads "(premise)" and the hypothesis names CE.
  - Both P4 reviewers had judged Lemma D "adequate"; the table would have required the premise check.

## 6. R5: severity guidance for missing premises

- **Proposed rule text** (REVIEWER-INSTRUCTIONS "Severity", and the settlement's severity column):

  > **A missing premise** is a premise that a derivation's proof uses, or that its conclusion needs, but that the derivation's statement does not state. Signs: a proof step justified by nothing stated (no premise, definition, earlier step or cited result); an object the book declares undefined used as if defined; a quantifier ("every", "always") wider than the premises give.
  >
  > **Severity of a missing premise, by explicit criteria.** It is **critical** if any of F1–F4 holds; otherwise **high**. It is **normal** only if N1 holds.
  > - **F1 (results rest on it):** a numbered result of a section, or a register concept with status VALIDATED or PROVISIONAL, cites the derivation (its R3 list is not empty at claim or concept level, excluding "existing homes" and other navigation lists).
  > - **F2 (other derivations rest on it):** another Ded, lemma or theorem uses its conclusion as a step.
  > - **F3 (kernel or boundary):** the derivation decides a kernel candidate, a boundary test (necessary / kernel must provide / application could provide), a primitive's independence, or a fork marked "for the Principal".
  > - **F4 (the premise is known false or undefined):** the missing premise is one the book itself states is false or not established (for example "CE is not yet defined").
  > - **N1 (normal):** no result, concept or derivation rests on it (an empty R3 list at claim and concept level), and it decides none of F3.
  >
  > **Resolution.** The finding is resolved only when (i) the statement carries the premise, (ii) the proof step names it, (iii) every result in the R3 list is reassessed and carries the condition, and (iv) the register row of every concept affected carries it. A condition recorded only in the register does not resolve the finding.
- **Input:** the finding; the derivation's R3 list; the section's statements about what is defined (R4's helper).
- **Procedure:** the reviewer (and then the settlement) checks F1–F4 in order and names the first that holds. R3 supplies F1 and F2. F3 needs reading the section's purpose. R4's helper or the proof-step table supplies F4.
- **Expected output:** the severity, the criterion that decided it, and its evidence (the R3 lines; the defining statement).
- **Limitations:**
  - F1 and F2 depend on explicit citations, so an implicit dependency is missed.
  - F3 needs judgement.
  - F4 needs the book to state what is undefined.
- **False positives and their handling:**
  - A derivation cited only in a "related" list is not F1; the settlement row says so.
  - Over-rating is corrected with the reason given.
- **Evidence to retain:** the criterion and its evidence in the settlement row.
- **Test (known case P1, the copy's Lemma D):**
  - **F1 holds:** §9.8 result 1 (CON-0148) rests on D. §9.9's mention of Lemma D is in its existing-homes list, so it is navigation and does not count.
  - **F4 holds:** §9.8.2 says CE "is not yet defined".
  - **Classification: critical,** the key's classification.
  - The settlement had rated it high (P4-7) and put the condition in the register. Both points are now covered: severity by F1/F4, resolution by (i)–(iv).
- **Test on a real case (working book):** the settlement's P2-2 (§2.3's pairwise conflict definition) is a missing-premise case in the wide sense (the quantifier "two rules" is narrower than the gate result needs). F1 holds through §2.9's gate result. Critical, as settled. Consistent.

## 7. Relation to other drafts and to v1.3

- **v1.3 already has** the claim-level downstream check when a definition or law changes (§2 step 8, entry 55), and computational checks as method (§2 step 2). R3 extends the check from "before review" to "every accepted correction in a settlement", which v1.3 does not require. R1, R2 and R4's helper are methods that v1.3 already **permits**. The proposal makes them **required** and defines their dispositions.
- **The v2 proposal** (`RULEBOOK-v2-PROPOSED.md`, not in force) is not merged here. If the Principal adopts v2, R1–R5 would be placed at its §8.2 (the Rule Reviewer's note); this draft does not presume that.
- **Form:** a v1.4 amendment (R1–R5) is the smallest change. The alternative is to fold it into v2. This is the Principal's decision (decision brief, D-R6).

## 8. Unresolved questions

- **Q1.** Should R2's tiers B and C apply to the whole book, or only to new and changed sections? The test supports tier A book-wide (42 candidates); B and C are cheaper per section.
- **Q2 (answered):** the Principal permitted the read-only run at `adbe16c5c`. Result: R2 as drafted misses the original case (§3).
- **Q3.** Who disposes of the candidates? The Professor (step 8) or the reviewer (brief)? The proposal assigns the step-8 file to the Professor and the reading to reviewers through the brief. Mixing the two affects blindness: a brief must not carry assessments (D2).
- **Q4 (answered by the count):** 64 of 75 candidates are false positives, and 11 are actionable (§3; `R2-DISPOSITIONS-working.md`).
- **Q5.** R5's criteria F1 and F2 use explicit citations. Should an implicit-dependency check (the reviewer's judgement) be added, or is that over-specification?
- **Q6.** R2 misses the original lane/BC-7 case at its drafted thresholds. Should tier B count identifiers in 2 or more sections, and should tier C drop the term-mark condition? Either change would catch the case, at the cost of longer lists. The Rule Reviewer's next planted-gap test, with fresh cases, is the right place to measure false negatives before any threshold is changed.

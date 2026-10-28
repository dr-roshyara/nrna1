CONSOLIDATION S0182–S0200: REPORT A

## Summary (≤15 lines)
1. Within their stated scope, §8.27–§8.30 are mostly reliable. I checked every bracketed quote and about 300 "in meaning" quotes by tool (`corpus_quote_check.py`: 0 not found; my own in-meaning checker: 49 non-verbatim, 13 of them judged by hand). I also spot-read the cited lines of S0182–S0200.
2. No critical finding. The largest risk is a set of stale or inverted cross-references, not a broken derivation.
3. High (A-01): §6.7 C1 inverts its source. S0089 L81 says an untracked diagram is "not a governed artifact … evidence of NON-REGISTRATION"; the book says "a governed artifact … evidence of registration". This contradicts §8.9 C9 L77.
4. High (A-02, A-03): wrong status labels. The Ch. 8 index gives CON-0208 as "invalid" (the register has VALIDATED) and CON-0213 as "validated" (the register has PROVISIONAL). THEORY-MAP L373 marks CON-0265 ~ although L198 and L372 say VALIDATED.
5. High (A-04): §8.30 says everything about the AIP reconstruction "is relayed by S0198 and S0199". But the book already reads that reconstruction, S0234, directly: §6.5 C2 (L20) and §9.6 L163, L195, L244 (S0234 L272, L282). §8.30 does not use those homes, including the erratum text, which bears on C4's disposition point.
6. §9.17 R3 (c) and (c′) say the root owner is "read from the record". In v20 it is a constant (`ROOT_OWNER='A0'`, ce_model.py L129, L261–268), set like the root carrier, outside D.
7. Model v20: my scratch run is byte-identical to the saved run. My probes confirm the stated limits: a foreign kernel that declares the owner's application stays in D. No unstated defect found.
8. §8.27 C1 says a relay "errs the other way". This compares an AMD6-review bar (S0183 L294) with a DV-correction-review status (S0185 L38), which are different subjects.
9. §8.28 C1 and T0-03b cite S0189 L1983 for an open "one system or two" question. The line says "exact PKS/EKS runtime boundary", which presupposes two systems.
10. Ch. 6: one high defect (A-01) and four normal ones (stale "undated" header, wrong T6-02 pointer, reversed index, stale register basis).
11. Ch. 8: I fully read only §8.00–§8.06 and §8.27–§8.30. For §8.07–§8.26 I read only the claim headers, status tags, quote checks and some spot-reads (see Limits).
12. Coverage: 12 MISSING items, 1 DISTORTED. Top gaps: S0198's 8-class relationship vocabulary (for T0-03b); S0199's materiality test; S0188's Rule↔Grant disconnection; S0196's AP-1 and "a derivation may not classify as Observed".
13. Concept verdicts: all 22 Ch. 6 concepts and 20 Ch. 8 concepts. The other 76 Ch. 8 concepts are not assessed (homes not read in full).

Process independence: I am a separate session with no access to the Professor's drafts or to the working book. This is process independence, not personal independence.

## Findings
| ID | Section | Ch | Finding | Sev | Evidence | Correction | R3 lines | Resolved when |
|---|---|---|---|---|---|---|---|---|
| A-01 | §6.7 C1 | 6 | Inverts the source: "So at the time the set was a governed artifact; S0089 classes such a diagram as evidence of registration". Contradicts §8.9 C9 L77. | high | S0089 L81: "UNTRACKED … never committed, therefore not a governed artifact ⇒ evidence of NON-REGISTRATION, not of NON-EXISTENCE"; S0090 L57 | "So at the time the set was not a governed artifact: S0089 classes an untracked diagram as evidence of non-registration, not of non-existence (S0089 L81)." | claim: THEORY-MAP:242 (correct; no change); §8.9 L77 consistent | text restated |
| A-02 | 08.00 index rows 8.14, 8.15 | 8 | "CON-0208 invalid as stated" and "CON-0213 validated" are both stale. The register and §8.14 C1 L26 give VALIDATED and PROVISIONAL (since consolidation S0101–S0130). The register's CON-0213 `same_subject_as` field still reads "CON-0208 (invalid as stated)". | high | tag check (my script); register rows 209, 214 | Row 8.14: "CON-0208 validated (restated); CON-0213 provisional". Row 8.15: "D2's grounds (CON-0213 provisional)". Fix the register's `same_subject_as`. | CON-0208: 08.00:28, 08.14:26, 08.14:86, 08.15:97, register 209, 214. CON-0213: 08.00:28–29, 08.14:26, 08.15:17, 35, 92, 97, register 209, 214, 216 | index and register agree |
| A-03 | THEORY-MAP L373 (L96) | map | L373 tags CON-0265 `~`, while L198 and L372 say ✓ / VALIDATED. L96 tags CON-0079 `~`, but the register has VALIDATED. | high (L373) / normal (L96) | register; tag check | set ✓ at both lines | CON-0265: 27 lines (map 196, 198, 372, 373; 09.17:177, 225, 309; 08.27 ×3 …). CON-0079: 05.03:24, 08.28 ×2, map 96, 196, 372 | map agrees with register |
| A-04 | §8.30 lineage, C2, C4; homes table | 8 | Says the reconstruction is known only by relay. In fact the book reads S0234 (the corrected text) at §6.5 C2 (L20, E-2: second write path) and §9.6 L163, L244 (L282: 23/30) and L195 (L272: the "every / almost every" asymmetry, already attacked). So the 23/30 measurement reaches the book by three routes, and S0234 L15–21 (erratum: "not needed" withdrawn, body "read in full") bears on C4's "no disposition record". | high | S0234 L15–21 (read); 06.05 references table; 09.06 L163, L195, L244 | Add §6.5 C2 and §9.6 as homes. Restate the lineage as "relayed in this window; read directly at §6.5, §9.6". In C2, name the three routes (S0136, S0300 / S0234, S0199). In C4, cite S0234 L15 as the applied erratum. | §8.30 C2: 0 claim-level, 3 section-only | homes added |
| A-05 | §9.17 R3 (c) item 1; (c′) | 9 | "The owner is read from the record: the root carrier's owner …" In v20 the root owner is a model constant, not a record entry. | normal | ce_model.py L129, L261–268; probe 2 | "The root carrier's owner is set with the root designation, outside D (§9.15 L11); every later owner is read from the record." | tool does not resolve "R3 (c)"; manual grep: Ch9 TODO 19–20; 08.27:23, 70; 08.29:25, 72, 75; 09.13:48; map 193, 373 (none rests on the root-owner wording) | text restated |
| A-06 | §8.27 C1, 4th bullet | 8 | "A relay also errs the other way" compares S0183 L294 (next actors for the AMD6 review, C-11/C-12) with S0185 L38 (status for the DV-correction review). For AMD6 a conflict plausibly remains: 870305e0 authored the findings AMD6 repairs (S0185 L38). So the "too broad" half of the Int is not shown. | normal | S0183 L288–298; S0185 L33, L38 | "The two bars concern different subjects; that a relay errs broad is not shown." Keep "too narrow" (2 occurrences). | 08.28:21, 64, 67; Ch8 TODO:13 (they cite the record-over-relay half only; no change) | restated |
| A-07 | §8.27.1 item 2 | 8 | "and must be recorded if it did" makes an obligation out of a constitutive rule. The source says an unregistered act "still counts as missing", meaning it has no effect. | normal | S0184 L96; S0192 L66 | "An act that happened but is not recorded counts as missing." | §8.27 C2: 08.28:69; 08.29:21, 37, 49 (cite the false-record half; unaffected) | restated |
| A-08 | §8.28 C1 limits; T0-03b | 8, 0 | S0189 L1983 lists the "exact PKS/EKS runtime boundary" as needing further archaeology. That presupposes two systems; it does not support "one system or two". | normal | S0189 L1974–1988 | Cite it as "exact runtime boundary unknown". Rest the one-system reading on S0186 L14 and S0195 L78 only. | T0-03b: Ch0 TODO:27; 08.28:53; 08.29:70, 97; 08.30:79; 08.00:42; map 199 | restated |
| A-09 | §8.28.3 "Rule" | 8 | The sense "authoritative obligation" is defined at S0188 L271. L288–302 is S0188's own report that "Rule" names seven concepts, so the homonym is the source's finding, not a cross-source one. | normal | S0188 L271, L288–302 | Cite L271, and record that S0188 already flags the homonym. | §8.28.3: none | restated |
| A-10 | §8.29 C4 | 8 | "model v19 shows the designation rule internally consistent": stale, since v20 has been current since C34. | normal | §9.17 R3 (c) | "v20" | §8.29 C4: 0 claim-level | restated |
| A-11 | §8.30 C5, last bullet | 8 | "Not refuted for magnitudes the source might define later (confidence, evidence strength)": S0200 already defines them (C+iT at L161–172; E+iP at L345; the 5-vector at L315–334). It also uses several incompatible pairings and facet lists (C37 inventory items 3, 8). | normal | S0200 L23, L77, L166, L330–346 | "Not refuted for the magnitude pairings S0200 itself proposes, where interval scales are given." Record the pairing drift. | §8.30 C5: 0 claim-level | restated |
| A-12 | §8.8 C2 | 8 | The "37 of 37" is at S0073 L82, not L88 or L155. It counts a different class (all STARTs recordedBy human) from "6 of 37" (STARTs whose text says Governance recorded them). Not a conflicting count. | normal | S0073 L82, L88, L155 | Drop the pair from the conflicting-count list, or restate it as two classes. | §8.8 C2: 05.07:16, 58, 60; 06.07:17, 75, 106; 08.09:14, 61, 63 (cite the rule; unaffected) | restated |
| A-13 | §6.6 header | 6 | The header says "undated", while the lineage and §6.6.4 say "not undated". | normal | 06.06 L4 vs L12, L101 | delete "undated" | §6.6: 38 section-only, 0 claim-level | fixed |
| A-14 | Ch6 TODO T6-02 | 6 | "Where: §6.1.3 (C7)" should be §6.1 C6 (see §6.1.4). T6-02 and T6-03 overlap. | normal | 06.01 L106 | point to C6; merge or relate the two rows | §6.1 C6: 06.03:25, 27, 28; TODO:11 | fixed |
| A-15 | §8.5 C9 | 8 | "Count errors occur in all five sources of this window": the bullets cite S0051, S0052, S0054 and S0055. S0053 is not cited, and the header lists three sources. | normal | 08.05 L1–5, C9 | "in four of the window's sources" | §8.5 C9: 08.08:15, 45, 52 | fixed |
| A-16 | 08.99 change log | 8 | No title or header row; a single C37 row; the C34–C36 settlement changes are not logged (Ch. 6's log has a header and full rows). | normal | `cat -A` | add header and rows | — | fixed |
| A-17 | register (basis column) | 6, 8 | 40 rows say "not yet reviewed" although the reviewed column and history show reviews (e.g. CON-0102, 0104, 0108, 0120 VALIDATED). | normal | my script | update the basis column | — | fixed |
| A-18 | §8.28, §8.27 references | 8 | The reference tables omit many body citations: S0187 L81–103, L172–221, L685; S0188 L73, L271, L630, L809; S0189 L879, L1983, L1988; S0190 L46, L113, L153, L171; S0191 L162–298, L465–467; S0183 L164, L294; S0185 L23, L38; S0186 L6, L14. | normal | body vs table | complete the tables | — | fixed |
| A-19 | §8.27 C7 | 8 | Scope: S0182 L57–60 is about where the renamed filenames' timestamps came from (mtime written into the filename). After the rename, the filename time *is* the mtime. | normal | S0182 L57–60 | add the scope | — | restated |
| A-20 | R2 "adviser", "Rule Reviewer" | 8, 9 | Used as source-standing labels (48 uses; first §8.21 L8) without a definition. | normal | R2 output | define once (or link the definition) | — | defined |

One line each, out of scope, not reviewed: §8.1 and BOOK-OUTLINE L186 cite "Rulebook v1.3", now v1.4. CON-0076 has a dual home (§1.8 and §6.1), and its §6.1 home is not in the scope list. The R1 H4 flag on CON-0133 ("merged into ARCH-INV-1" carries no status).

## SEQ order
| Ch | Problem |
|---|---|
| 6 | The index lists sections in reverse (6.7 → 6.1). §6.4 C3 defines "held" by a forward pointer to §6.5 C2. |
| 8 | The index note limits Ch. 9 usage to §8.18–§8.21, but §8.22–§8.30 also use D, K3, K6, §9.17 R1 and R3 (c), and v19/v20 (e.g. §8.27 C2, §8.28 C2, §8.29 C5). |
| 9 | §9.13.2's designation bullet uses carrier, old store, carrier in force and §9.17 R1, all defined later in §9.17.2. |
| 0 | TODO rows are out of order: T0-03, T0-03b, T0-03a. |
| 5 | Not checked beyond the index (the index order is linear). |

## Model v20 (read-only; scratch copy)
- My run matches `ce_model-run-20261028-v20.txt` byte for byte.
- Probes:
  - **P1:** a merged record with a move by kernel K2 declaring app = A0 is in D. This confirms stated limit (ii).
  - **P2:** ROOT_OWNER is a constant (A-05).
  - **P3:** a root-only record is in D. This is the trivial case; the must-do is shown by check 32 (the owner's move is accepted).
  - **P4:** a fork of C1 by two kernels, both declaring the owner, is outside D.
- The induction in limit (i) checks: every designation must have app = ROOT_OWNER.

## Concept verdicts
| ID | Verdict | Reason (scope) |
|---|---|---|
| 0072 | VALIDATED | definition; scope: a declared input contract |
| 0075 | VALIDATED | product order on {0,1}² and [0,1]² is a lattice; tiers 2 and 3 incomparable (checked) |
| 0087 | VALIDATED | as a distinction; that the protocol belongs to the platform is the source's own step |
| 0089 | VALIDATED | core/supporting as a relation to a reference product; the support is a counterfactual conditional |
| 0102 | VALIDATED | stated ≠ held ≠ enforced, as a distinction; V-2 breach not established |
| 0104 | VALIDATED | opposite directions defined; scope AIP-14 as quoted |
| 0108 | VALIDATED | nearly definitional: a verdict→{pass, block} map errs iff some verdict covers both; blocking only |
| 0103 | PROVISIONAL | one author; criteria overlap; "one programme" is history, not structure |
| 0132 | PROVISIONAL | "reason lives in" is not operational; S0091 I-4 has two owners, unresolved |
| 0140 | PROVISIONAL | a quoted list; completeness is a Hyp; one source |
| 0147 | VALIDATED | testing a dependency law on a view needs a partition and dependency-typed edges (Ded); "blocks confirmation more than refutation" holds |
| 0172 | PROVISIONAL | probably §1.10 + §1.16.6, not minimal; one source |
| 0176 | VALIDATED | the fibre argument (a)–(c) checked; as mathematics only, the kernel-law reading stays Hyp |
| 0073, 0074, 0085, 0086, 0088, 0133, 0134, 0139, 0146 | INVALID cores confirmed as restated | each restatement checked against its section; for 0088, the register's C7 follow-up is still open |
| 0058, 0059, 0060 | PROVISIONAL | self-reported single experiment; raw record absent (T8-01) |
| 0164 | PROVISIONAL | sound classification, but rests on RC-9's scoped form, not re-derived here |
| 0166 | PROVISIONAL | the regrouping holds; "prevented incorrect" is circular as benefit evidence |
| 0120 | VALIDATED | a scope-relative absence rule; its extension to readings (§8.30 C1) is Int |
| 0121 | VALIDATED | as a possibility claim; generality is a Hyp (one case) |
| 0125 | PROVISIONAL | the nine factors are not shown complete or orthogonal |
| 0126 | VALIDATED | proof checked: reported set ⊇ B(a) Δ B(b); a common-mode intersection is never reported |
| 0127 | VALIDATED (upgrade) | a conditional theorem, correct given R ⊆ F; the estate's premise is not met (C3) |
| 0128 | PROVISIONAL | the probability is correct (I re-checked the counterexample: joint LR 50 against product 2.25); the counting rule is a heuristic |
| 0208 | VALIDATED | S0120 L15 checked: decided on non-realization |
| 0209 | VALIDATED | definitional consequence of S0113 L18 |
| 0213, 0214 | PROVISIONAL | the registrar's reading; two operations are distinguished but rest on one source |
| 0106, 0112, 0119, 0122, 0210 | INVALID cores confirmed | restatements checked |

**Not assessed** (homes in §8.07–§8.26 not read in full; no verdict given):
- VALIDATED (11): 0129, 0153, 0219, 0220, 0226, 0232, 0239, 0240, 0242, 0245, 0253.
- PROVISIONAL (49): 0130, 0141, 0150, 0151, 0152, 0154, 0156, 0158, 0178, 0179, 0180, 0181, 0195, 0196, 0199, 0200, 0201, 0203, 0204, 0206, 0207, 0211, 0212, 0215, 0216, 0217, 0221, 0222, 0223, 0224, 0225, 0228, 0229, 0230, 0231, 0233, 0236, 0237, 0238, 0241, 0243, 0244, 0248, 0249, 0250, 0251, 0254, 0257, 0259.
- INVALID (16): 0155, 0157, 0159, 0197, 0198, 0202, 0205, 0218, 0227, 0234, 0235, 0246, 0247, 0252, 0255, 0256.

## Coverage (ranked; a search miss means "no home found by search")
| Item (inventory) | Status | Home |
|---|---|---|
| S0198 L98: 8-class relationship vocabulary (SAME … UNKNOWN) for P4 (C37) | MISSING | — (for T0-03b) |
| S0199 L22, L54–56: materiality test, blocking vs non-blocking unread part (C37) | MISSING | §8.30 C1 should state it |
| S0188 L1077–1083: Rule↔Grant not connected (RM-3) (C35) | MISSING | — (RM-3 itself is at §2.11) |
| S0196 AP-1 "knowledge feeds authority, never holds it"; projection a terminal sink; strongest invariants negative (C36) | MISSING | — (ARCH-INV-1, trivial-case test) |
| S0196 L233: a derivation may not classify anything as Observed (C36) | MISSING | — (§1.3) |
| S0200 L330–346: magnitude pairings and 5-vector already defined | DISTORTED | §8.30 C5 (A-11) |
| S0199 L125: NOT CONFIRMED as a defect / CONFIRMED as an annotation (C37) | MISSING | — |
| S0191 L404–432: agent infrastructure consumes knowledge, does not own it (an earlier instance of §8.30 C3) (C35) | MISSING | §8.30 C3 |
| S0189 L842–864: legacy hook bypassed the runtime, then rerouted (a bypass case) (C35) | MISSING | §8.29 C5 |
| S0189 L1401–1431: not all persistence is domain state (C35) | MISSING | — |
| S0186 L319–379: no Knowledge/Evidence/Decision aggregates demonstrated; four candidates (C34) | MISSING | — |
| S0196 L429: ES-005.1 counted as a PKS invariant (corpus blur, T0-03b) (C36) | MISSING | T0-03b |
| S0187 L516–532 keep-out list; S0187 L291–305 vs L637 internal inconsistency (C35) | MISSING | T0-03b list |
| S0183 quarantine without DISPOSE; freeze self-reference | COVERED | §8.20 C1, C10 |
| S0182 two orthogonal tags; S0194 L148 teleological closing; S0196 L452 and L710 source defects | MISSING (low) | — |

Source defects, listed separately (the theory does not rely on them): S0196 L452 (X-04 should be X-03); S0196 L710 vs L374; S0198 section labels (S0199 L85).

## Limits
- §8.07–§8.26 were not read in full. I read their claim headers and tags and ran the quote checks on them; I spot-read §8.08 C2, §8.09 C9, §8.14 C1–C3, §8.19 L42 and §8.21 C8.
- No proof-step tables except for §8.6 C2–C4 and §9.17 limit (i).
- Ch. 5 SEQ order not checked beyond its index.
- The concepts listed above are not assessed.

## Files opened (all read-only)
- **Instructions and review folder:** REVIEWER-NOTE.md, BRIEF.md, SCOPE.md, FREEZE.md, README-PROFESSOR.md (opened: it mentions a planted-gap test; it is not in item 4's list; I did not use it), 03-methodology/REVIEWER-INSTRUCTIONS.md, 00-access/ACCESS-RECORD.md (grep only), the four allowed STEP1-INVENTORY.md files (C34–C37).
- **Copy (BOOK-UNDER-REVIEW/06-theory):**
  - chapter_06: all 10 files;
  - chapter_08: 08.00–08.06, 08.27–08.30, 08.99 and TODO in full; 08.07–08.26 by grep and partial reads (08.08 L48–56, 08.14 L20–40, 08.21 L16–37 and L110–118);
  - chapter_09: 09.17 L126–222, 09.13 L40–52 and TODO in full;
  - chapter_00: TODO;
  - chapter_01: 01.13 (grep);
  - the index files of chapters 0, 5 and 9;
  - THEORY-MAP (L1–40 plus grep), BOOK-OUTLINE L186–206, CONCEPT-REGISTER.tsv (by script).
- **Repository tools, read-only:**
  - tmp/tools/ce_model.py: copied to scratch; probes run on the copy;
  - ce_model-run-20261028-v20.txt;
  - the heads of corpus_quote_check.py, inbound_citations.py and allowed_order.py;
  - a directory listing of tmp/tools.
- **Corpus, all on the allowed list (checked; sealed and hold-out files never opened):**
  - S0182–S0200: cited ranges only; S0182 and S0199 in full;
  - S0234 L14–22; S0300 L1200–1206; S0025, S0031 L130–152, S0073, S0089 L79–82, S0090 L55–58, S0113 L18, S0116 L25, S0117 L92, S0120 L15, S0136 (grep), S0138 L142, S0142 L242, S0148 L140, S0179 L6, S0180 L78–81: cited lines only.
- Index: 02-FILES.jsonl. Allowed list: CORPUS-ALLOWED.tsv (sha256 `1f8e7638…c5c1`, verified).
- **Exposure:**
  - one grep over `docs/knowledgeos/reviews/KOS-AIP-GOV-STATE-DURABILITY-DV-CORRECTION-*.md` also showed lines from S0146 and S0253 (both allowed, not sealed; not otherwise used);
  - a `find -iname "*ALLOWED*"` showed file names under the old review folder `review/CONSOLIDATION-S0151-S0181/` (names only; not opened).
- **Item 4:** no item-4 file was opened: no working chapters, working map, register, outline or BASELINE-REVISIONS, and no manuscript git history. The only git command run was `git log` on two corpus files (S0195, S0196).

## Commands and searches run
- `register_home_check.py --root …` (R1)
- `undefined_terms.py --root …` (R2)
- `corpus_quote_check.py` on Ch. 6, Ch. 8, §9.13 and §9.17 of the copy
- `inbound_citations.py --root …` with targets: §6.7 C1, §8.27 C1, §8.27 C2, §8.28 C1, §8.29 C4, §8.30 C2, §8.30 C5, §8.8 C2, §6.1 C6, §8.5 C9, §9.17 (c′), §9.17 R3 (c), §6.6, T0-03b, §8.28.3, CON-0208, CON-0213, CON-0265, CON-0079
- `book_search.py` (copy) for: DISPOSE, "jointly unsatisfiable", "placeholder identity", launder, "literal freeze", "script to service", "governance append mechanical write", "not demonstrated as aggregates", RM-3, "Rule Grant connection", "post-commit hook bypassed", "keep-out list", "JSONL stream", "agent infrastructure consumes knowledge", "smallest stable kernel", "AP-1 knowledge feeds authority", "terminal sink", "derivation may not classify Observed", "closed verdict vocabulary", "ES-005.1 PKS invariant", teleological
- grep on the copy for: untracked / NON-REGISTRATION, CON-0213, CON-0208, "23 of 30", adviser, unforgeable, "R3 (c)", T0-03b, "read from the record", Kleene, "C9 / S0090 L57 / S0089 L81 / puml", rotat
- grep on sources for: 870305e0 (DV-correction reviews and S0183), "37 of 37" (S0073), "this repository / documentation-only" (S0194), Rule (S0188), "Two independent" (S0188), "23 / 30" (S0136), block (S0196)
- `awk` and `sed` line prints of the cited ranges; `wc -l` / `wc -c`
- `git log` on S0195 and S0196 (corpus files)
- my scratch scripts in `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/consolA/`: tagcheck.py (status tags against the register), inmeaning.py (in-meaning quote check), show.py (prints source lines), a register recount, and the scope list against the register
- ce_model.py v20 run and the probe script (`model/probe.py`, scratch copy only)

Nothing in the repository was edited.

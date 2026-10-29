CONSOLIDATION S0201–S0225: REPORT B

Object: the frozen copy BOOK-UNDER-REVIEW/. Sections: Ch. 9 §9.13–§9.18, the six files in SCOPE §2.6 for reviewer B. No working chapter file was opened.

## 0. Summary (≤15 lines)
1. All six sections were read in full. That is 2,127 lines, from the copy only.
2. **Mathematics sound.** I re-derived the proof tables of §9.13 R1/R3 and §9.14 R1/R2/R4 and the R3 interval and threshold-with-forbidden-value proofs. I found no invalid step. A scratch brute force reproduced three values of the §9.14 table: h = n on n ordered values, m+2 on a ℤ window, and 3 on the coupled space. These are small-scope confirmations, not proofs of the general claims.
3. **Largest risk: stale status text, not theory.**
   - The sections carry many "returned to the Principal", "version 14", "ties by content" and "counts consumptions that are not F" statements that later decisions superseded.
   - Some are left unmarked, so a reader can take a closed fork for an open one (F1, F2).
4. **Undefined notation.** §9.18's containment result (Ded) uses D_S, D_W, W, "area" and "the element outside every area". D_S and D_W are defined nowhere in the book. The rest are defined only in Ch. 10, which comes later (F3). Severity high.
5. **Quote and source check.**
   - Quote check on all six files: 53 quotes found, 0 not found.
   - I spot-checked about 20 cited source lines by hand (S1761, S0300, S0095, S0071, S0969, S0991, S0253, S0875, S1498, S0431, S0103, S0078). I found no misattribution.
   - One scoping gap: §9.15 K1's "numbered successor" clause against S0206's erratum E-1 (F6).
6. **Findings:** 12 (0 critical, 3 high, 9 normal). Concept verdicts: CON-0265 second verdict VALIDATED, CON-0192 VALIDATED within scope, CON-0191, 0193, 0194, 0261, 0264 PROVISIONAL. INVALID cores 0262 and 0263 are consistent as restated.
7. **Coverage.** None of windows C38–C45 wrote or changed §9.13–§9.18 (their output is §8.31–§8.38). Only a keyword scan of the inventories was possible, which is not a full read. See §4.
8. **R1:** 1 flag in the whole book (CON-0133, chapter_06, out of my scope). **R2:** all 81 in-scope candidates judged (§5). **R3:** the tool matches only "§x.y Cn"-style claims or CON ids, so for the corrections I used the ids it supports and a grep fallback (§6).
9. **Process independence:** process independence only, not personal independence. I saw no other reviewer's report.

## 1. Findings
Each finding is an interpretation or recommendation unless marked "fact". Facts are quoted with file and line.

| ID | Section | Finding | Sev | Evidence | Correction | R3 lines | Resolved when |
|---|---|---|---|---|---|---|---|
| F1 | 09.16 R1 table | **Fact:** four rows of the dependency table state superseded positions. | high | See the next three lines. | Restate rows 66, 69, 80, 86 in place. | CON-0194 rows in 10.05 (L96, 144, 162, 231, 308–317): cite §9.16 R1 and "L79–80" by line number. 09.18:250 cites "§9.16 L79". Edit in place so L79–80 do not move. | Rows agree with §9.13.2 and §9.15 G4. |
| | | (a) F26 row (L66): "(a) and (c) inside D, (b) outside". Against it: §9.13.2 "F26 (b) at ties"; §9.13.5 "F26 (b) at ties is now in Θ_f". | | | | | |
| | | (b) L69 "ties are by content" and L80 "equal answers under content ties". Rejected since v3; L67 itself says unordered. | | | | | |
| | | (c) L86 G4 row: "the guard, which counts consumptions that are not F". Superseded by §9.16 R4a v8, "every recorded use counts", and §9.15 G4 itself. | | | | | |
| F2 | 09.16 R4a (v5–v8 blocks); 09.13 L37–39 | **Fact:** statements closed by later decisions remain without a "superseded" tag. | high | L288: a non-single-use grant's consumptions are only detected across applications; D admits consumption only of single-use grants since v14. | Tag or delete the superseded lines. | 10.05:162 (R4a–R4d), 08.19:15 and 08.19:43 (cite K4-o, R4a), 10.04:61, CON-0194 row. | Each closed question is marked decided with its decision, or removed. |
| | | | | L307–311 "Who may transfer is returned to the Principal". Decided at v7 (L280) and R4a v8. | | | |
| | | | | L317 "'A use' (returned…): the model reads it as a consumption". Decided v7: every act under a single-use grant is a use. | | | |
| | | | | L271 W-6 "returned". Decided after C21. | | | |
| | | | | L325/L327: "the 'already consumed?' guard runs on the owner's record" and "What the owner must provide (§9.15 K4-o)". This contradicts R4c, and §9.15 K4 (v10): K4-o "has no remaining domain of its own". Same in 09.13 L37. | | | |
| F3 | 09.18 §9.18.5–6 | D_S, D_W, W, `opaque` and "area" carry the Ded containment results (c1)–(c5) and Results 1–6. D_S and D_W are never defined in the copy. grep finds only uses (09.18, 08.35:59). "Area", "element outside every area" and PL-12/PL-14 are defined in Ch. 10 (10.03 ~L231–300), which follows Ch. 9. | high | grep of D_S/D_W over chapters 08, 09, 10; undefined_terms: evidence-area, containable, store-known. | Add to §9.18.1 or §9.18.6 a gloss: D_S = standing's record clauses plus record-level clauses; D_W = W's acceptance clauses; W = evidence area; each with a pointer to §10.3.6. Alternatively move or label the forward dependence. | 08.31:117, 08.33:87, 08.35:59, 08.36:57 cite §9.18 clauses and use D_S. They need no change. | Every symbol is defined at or before first use, or flagged as a forward reference. |
| F4 | 09.13 header, 09.13 §9.13.10, 09.13 References | **Fact:** the header status is stale. | normal | Header: "version 14 (2026-10-21)… Changed theory for C22's review". The body applies v19–v20 (designation, owner clause, "Model v20, checks 28–32") and the C22 and consolidation settlements. | Update the status line, tool line and open questions. | CON-0191 register basis ("ce_model v5"); 09.00 row 9.13 ("v8… for C19's review"); 09.16:240 ("ce_model.py v15"). | Status, tool version and index row agree. |
| | | | | The References "Tool: ce_model.py v15, run …v15" conflicts with v20 checks 28–32 and with the existing runs v16–v23 (these files exist; I listed them, not opened). | | | |
| | | | | L331 "Under F26 (b) does CE* stay total? (not checked)" is contradicted by checks 1 and 6 and R1's text. | | | |
| | | | | The register rows for 0192 ("helly v4") and 0193 (K2 "single view… while ties are by position") are also stale. | | | |
| F5 | 09.18 header and §9.18.1 | **Fact:** the header says "v22 (checks 33–36)". The body applies T9-16 at "v23, check 37" (L193, L290). §9.18.1 item 6 still lists T9-16 as an undecided choice, while §9.18.2 G4 row and §9.18.10 say "decided (b)". Stray bold at L154. | normal | Lines 3, 44, 78, 193, 285–290. | Header "v23, checks 33–37"; item 6 marked decided. | 09.00 row 9.18 | Header, summary and body agree. |
| F6 | 09.15 R1, K1 cell | **Fact:** the clause is truncated: "…a numbered successor survives, and within D only if (scoped at the consolidation S0182–S0200, C N4)." The condition after "only if" is missing. | normal | 09.15 L74. | Complete the clause. Add the premise that numbering is not reused by a lost concurrent append. That is a K6 failure and is excluded within one serialised kernel. | 08.18:186 and 08.18:237, 08.26:21 and 08.26:54, CON-0243/CON-0212/CON-0237 rows (all point to K1). The wording changes, not the cell's verdict on "external commitment". | The clause reads as a complete condition. |
| | | **Interpretation:** the "numbered successor" detection claim needs a scope. | | S0206 L513–517 (E-1): a lost transition "mechanically detectable as a gap" is contradicted. In 23 of 30 concurrent-append trials the clobbering writer reused the number, and every survivor stayed dense and monotonic. This is the file the section cites at L283 and L395. I assessed E-1 as a different scenario (lost append, not out-of-band removal). The book does not say so. | | | |
| F7 | 09.17 R3 (b) L149; L23 | **Fact:** the draft wording "withheld, not promoted" remains in (b). L23 and L175 call exactly this wording corrected (B-01): "withholding has no meaning for answers". The register row CON-0264 still has the list-based form. | normal | 09.17 L23, L149, L175. | Restate (b) as a carrier-change classification only. Say that no answer is withheld. | CON-0264 register row; 08.19:167; 09.17 L237 (G2 row, safe direction for retirement, can stay). | (b), L23, L175 and CON-0264 agree. |
| F8 | 09.17 R4 setting and C4 | **Fact:** C4 says a foreign entry "blocks G0 and G2". **Interpretation:** this holds only if NEW does not hold the foreign entry (the copy carries members only). If NEW is a full copy of OLD, G0 passes and only G2 blocks. The premise sits in the Loss definition (L46, "left behind"), not in the Setting. | normal | 09.17 L46, L236–243. | State in the Setting that the carry-across copies members only. | CON-0265 inbound: 10.02:86, 10.03:99/336, 08.27:22/61–64, 08.26:21 and 08.26:54, register. They cite the concept, not C4's wording. | Premise stated in the Setting. |
| F9 | Several | **Homonyms** (ubiquitous language). | normal | See the next five lines. | Qualify each use. | The qualifying edits touch only in-section wording. | Each term has one meaning per context, or a qualifier. |
| | | "coupling": 09.14.2, a constraint on the outcome space. In 09.18: dependence of standing on W ("the two couplings"). | | | | | |
| | | "safe direction": 09.13 v5 (tolerating a dangling citation as F; found false for negative acts); 09.17 R4 C4 (blocking a retirement); 09.17 L175 (corrected "classed foreign, the safe direction"). | | | | | |
| | | "attestation/attested": 09.17 (kernel attestation of acceptance); 09.15 (authentication status); 09.18 (class "not attested"); CON-0212's "NOT ATTESTED" (unresolved). §9.18.10 flags only the last pair. | | | | | |
| | | "owner": a grant's owner application (09.13/09.16) against a store's owner application (09.17). Same pattern, two objects. | | | | | |
| | | "recorder" against "recording application": 09.15 L10 says L10's `app` is "the application named as an entry's recorder". 09.16 R2 says identity excludes the recorder; L319 says the application is part of identity. Whether they are one field or two is not stated, and the merge rule's identity depends on it. | | | | | |
| F10 | SEQ in 09.13/09.16/09.18 | **Fact:** "mover" first appears at 09.13 L48 with a gloss only at 09.17 L166. §9.16 R4 reads R4, R4a, R4d, R4c, then the "v8", "v7" and "v5–v6" blocks in reverse order. The R4a substance sits under R4c. R4d cites R4c before R4c. 09.18 relies on Ch. 10 definitions (see F3). **Interpretation:** a reading-order defect, not a content one. | normal | 09.13 L48; 09.16 L223–332. | Gloss "mover" at first use. Reorder R4 as R4a, R4c, R4d, or add a reading note. | None beyond the lines under F1 and F2. | Nothing is used before it is introduced, or each such use carries a pointer. |
| F11 | 09.14 R5 (c) L157 | "on a discrete order" is undefined. **Interpretation:** it matters (on a discrete order a strict threshold equals a closed one). | normal | 09.14 L157; undefined_terms D "discrete". | Gloss it. | None | Gloss added. |
| F12 | Source variance (source defect, list only) | The number of dated transitions is given as "2 of 210" (09.13.2, via §1.14.1 and §9.7), "2 of 216" (09.17, S0300 L1204) and "2 (0.9%)" in S0206 L395, which the C39 inventory records as "2 of 218". Different snapshots. The theory does not rely on the count. | normal | S0300 L1204; S0206 L395; C39 inventory item 6. | State the snapshot or the source per count. | None | — |

No critical finding. The three high findings are contradictions and undefined notation. Each stops dependent work only if a later section cites the stale text.

## 2. Concept verdicts
Scope: concepts homed in §9.13–§9.18. The other in-scope concepts are homed in §9.00–§9.12 and are not mine.

| ID | Verdict | Reason |
|---|---|---|
| CON-0265 (second verdict) | **VALIDATED**, within scope (an exact outside list; no write between check and act; the casualty read at the check). | C1–C5 follow from the gate definitions. C2 and C3 hold by construction. The model (384,000 worlds) is "reported, not re-run". I re-derived the proofs by hand. Caveat F8. The independence-is-derivable-from-coverage point (C2) is correct. |
| CON-0192 | **VALIDATED**, within scope: mandatory effects, a fixed representation, indexed families. The placement of the book's rule classes is Int and outside the verdict. | I re-checked R1(a)–(d) step by step. R2 (product lemma), R4 (|F| ≤ |X|, tight), the interval Helly proof, and the threshold-with-forbidden-value cases on n values, ℕ, ℤ and dense order all hold. I confirmed three computationally (§0 item 2). The Doignon/Helly rows are cited facts. R5(c) is stated with its cylinder premise. |
| CON-0191 | PROVISIONAL | R1–R3 are sound as proved for Φ. The concept as registered now contains v8–v20 clauses (consumption, transfer, owner, designation, attestation). Those rest on model checks and the Professor's choices, which are labelled. The register basis is stale. Open question L331 is stale. The domain D, as extended, has no totality proof. |
| CON-0193 | PROVISIONAL | The recognition/legitimacy split is definitional. The K and L classification rests on an Int criterion. R8(c) is provisional. The register meaning is out of date. K1 cell: F6. |
| CON-0194 | PROVISIONAL | R2 (merge-order independence) holds only under the unordered-tie rule, on model checks. R3 is a "Ded sketch", law by law unchecked. R3a's split rests on the Principal's reading (stated in L189). Rows stale: F1, F2. |
| CON-0261 | PROVISIONAL | R1 and the within-one-designation derivation are proved. The across-change part is stated as a sketch. The independence countermodels are informal. A re-axiomatisation, not a derivation (stated in L87). |
| CON-0264 | PROVISIONAL | (a) is a two-history proof. (b) rests on the exactness of E. (c) depends on a kernel attestation assumed unforgeable, with presence checked but not validity. F7. |
| CON-0262 (INVALID) | Core stands as restated | 09.17.6 matches the register. The defect depends on the order copy, then switch. This is not stated explicitly in the restated text. A one-line gloss would help. |
| CON-0263 (INVALID) | Core stands as restated | Independence follows from coverage (C2). The operand condition dropped "not only". Version 1 of the model was tautological. 09.17.6 matches the register. |

## 3. Proof checks and challenge adequacy
These are my judgements, with the reason.

| Result | Judgement | Reason |
|---|---|---|
| §9.13 R1 (steps 1–8) | adequate | Each step cites a premise, a definition or an earlier step. The step 1 objects (record constants, covers = U) are defined in §9.13.3. |
| §9.13 R3 lemma (steps 0–8) | adequate | Step 2 (the u and o atoms are in no unfounded set) holds because "not u" is U in KK. Step 8 gets KK ≤ WF from Fitting's fixpoint. The result is "probably known", as the section states, and no novelty is claimed. The section's own challenge (WF over forward citations, D_acyclic, KK-with-anomalies, stable models) is adequate. |
| §9.14 R1/R2/R4 | adequate | The strongest rival (a pairs-only analyser on random sets) was run, "agreement sampled, not proved". |
| §9.15 R3 (no answer depends on occurrence) | adequate but definitional | Labelled "definitional under DB-1". The proof is one line. |
| §9.17 R1 proof | adequate | It is an existence argument over worlds (a byte-identical copy). It is limited to predicates on entries, as stated in the scope. |
| §9.17 R2 | adequate | The countermodel M2 is a model. Since v18 the kernel sets each entry's store, so M2 is not an observed case (L85, L121 note). |
| §9.18 g0 | adequate as argued | Kleene-monotone "argued, tested on fixtures, not exhaustive" (L148). The "totality" probe is "reported, not re-run". |

## 4. Coverage against the inventories (windows C38–C45)
No window of C38–C45 wrote or changed §9.13–§9.18. Their output is §8.31–§8.38 and the G7 row of 10.02, which are reviewer C's. I could therefore only check the inventories for items that bear on my sections' topics. That check was a keyword grep over the eight inventories, 1,085 lines in total, followed by reading the matching lines. It is not a full read of the inventories.

| Item | Status | Home |
|---|---|---|
| C39 #7, covers/scope is prose, two AuthorizationFacts UNKNOWN | COVERED | §9.13.2 "covers is an input; pair outside its domain gives U"; K3 |
| C39 #3, the recorder is not authenticated | COVERED | L10, with the same-estate caveat (not independent support) |
| C39 #6, authority untimed ("2 of 218") | COVERED, count differs | L6; F12 |
| C39 #13 (E-1), density is not a completeness proof | DISTORTED / unscoped | K1 cell: F6 |
| C39 #4, two records never merged, no join key | COVERED | K3 (identity link), estate not in D (§9.13.2) |
| C40 #13 reply, "Prevent Evidence→Authority" | COVERED | §9.18 containment (c1)–(c2) |

No item ranks as a material miss for my sections.

## 5. R2: every in-scope candidate judged
Tool: `undefined_terms.py --scope 09.13-09.18`, 81 rows.

**Tier A**

| Word | Judgement |
|---|---|
| mover | **Defect:** first used at 09.13 L48, glossed only at 09.17 L166. See F10. |
| crowded | Defined as a test fixture (act times in {1,2}) at 09.16 L129. First use 09.13 L26. Add a gloss. |
| tombstoned | Inflection of "tombstone" (defined §9.13.2). OK. |
| polyhedra | Standard mathematics. OK. |
| wclass | Model function name in a code list. OK. |
| TURN8 | A log label. OK. |
| iterating | Ordinary. OK. |
| uncoupled | Ordinary compound of "coupling" (§9.14.2). OK. |
| cofinite | Standard mathematics ("complements of finite sets"). A gloss is optional. |

**Tier B and C**

| Word | Judgement |
|---|---|
| B-02 | A settlement finding id with no key in the book. Provenance tag only. Low. |
| switch | "The switch" in the Principal's quoted message means the designation act. Ambiguous beside "designation"; gloss. |

**Tier D**

*Tokeniser artefacts.* entrya, kernelguard (tokeniser artefacts), subfamilie (subfamilies), matche, verifie (defined §9.15 L53). OK.

*Compounds of defined terms.* empty-intersection, once-only (defined by laws 18/31, 09.16 L166), annulling/annulled (annulment is an admitted kind), restored/withdraw (kinds defined in §9.18.4).

*Defined or inferable in the section.*
- survivor-derived: survivor = NEW and casualty = OLD are used in the gate table but not stated; gloss.
- realisability: defined at §9.14.2.
- vacuity: defined by the Q1 test.
- cyclic: standard; "Ordered" is defined.
- linear: "linear extension" is defined at 09.16 L99.
- intact: defined at K5.
- issued: grant issuance is defined.
- precedence: g0's order.
- constitutive: C1 and Searle.

*Judged individually.*
- discrete: used undefined (09.14 L157). It matters; F11.
- evidence-area: F3.
- containable: defined by the 09.18.6 criterion.
- store-known: a model clause name; refers to the D clause "every entry carries a store". Gloss.
- composed-system: a pointer to §9.11 R3. OK.
- safe: homonym, F9.
- coupling: homonym, F9.
- capacity: legal capacity (L7), and "Governance-recording capacity" in the sources. Add a qualifier.
- stable (as in stable models): standard external term, a rival not adopted. OK.
- variable-based: quoted from a source abstract. OK.
- suspension: outside D (§9.13.2, "not admitted"). OK.
- duty (display duty): defined by PL-14 in Ch. 10; forward pointer; F3.
- duress, honest, band (out of band), irreversible: ordinary words.

*Ordinary words, no defect.* contribution, smallest, overall, vacuously, weakened, smaller, incomparable, clear, combine, restatement, saying, addressed, implicit, equality, implement, reconciled, passed, confirmed, completion, empirical, replaced, message, strongest, correctly, recommend, useful, select, complement, mistyped.

## 6. R3 for corrections
`inbound_citations.py` returned 0 for "§9.16 R1", "§9.17 R3", "§9.15 G4", "§9.13 R1", "9.13.2". It recognises only "§x.y Cn"-style claims and concept ids. I used CON ids (the CON-0194 and CON-0265 results above are the tool's own output) and a grep of the copy's chapters, register, THEORY-MAP and BOOK-OUTLINE for the specific strings. The grep strings are in the command list. Dispositions per correction are in the "R3 lines" column. In short:
- F1, F2: edit in place so that the "L79–80" and "§9.16 L79" line citations stay valid.
- F3, F4, F5, F10, F11: wording only, no inbound change.
- F6, F7, F8: the cited sections point at the cell or concept, not at the corrected wording. No inbound change is needed beyond the register rows for CON-0264 and CON-0191–0193.
- No inbound line would have to be rewritten by any proposed correction.

## 7. Limits
- Not covered: the model runs (ce_model, helly_check, anchor_model) were not re-run. All their figures (400, 1,500, 12,000 records; 384,000 worlds) are "reported, not shown". I did not run helly_check.py. My own scratch brute force (in the scratchpad) covers only the three values in §0 item 2.
- Citations in the "in meaning" style were spot-checked for about 20 sources. The rest rest on the quote check, which tests quotations and not meaning.
- I did not read the C38–C45 inventories in full (§4). I did not read the review folders' settlements, reports or briefs.
- Out-of-scope observations, one line each:
  - 09.00 row 9.13 ("v8… for C19's review") and the rows for 9.14–9.16 are stale. Row 28 is absent from the index listing; I did not open the file in full.
  - THEORY-MAP and CONCEPT-REGISTER meanings for CON-0191/0192/0193 are stale (F4).

## 8. Files opened and commands run
**Files opened**
- In `W/06-theory/manuscript/review/CONSOLIDATION-S0201-S0225/`: REVIEWER-NOTE.md, SCOPE.md, COMMISSION-B.md. I did not open COMMISSION-A, COMMISSION-C or INTEGRATION-COMMISSION.
- `W/03-methodology/REVIEWER-INSTRUCTIONS.md`.
- In BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_09/: 09.13-the-evaluator.md, 09.14-when-pairs-suffice.md, 09.15-the-trust-boundary.md, 09.16-reassessment.md, 09.17-the-designated-record-and-its-anchors.md, 09.18-evidence-in-the-record.md. Parts of 09.00-index.md (by grep).
- BOOK-UNDER-REVIEW/06-theory/CONCEPT-REGISTER.tsv (rows, by awk/grep).
- Inventories (grep and `sed` ranges only): C38–C45 STEP1-INVENTORY.md.
- Tool sources, headers read: tmp/tools/undefined_terms.py (first 40 lines), tmp/tools/inbound_citations.py (first 25 lines).
- Corpus (allowed list; excerpts by line range):
  - S1761 L140–150
  - S0300 L454, L1204, L1286, L1313–1316
  - S0991 L2058–2063
  - S0071 L74, L276
  - S0095 L141, L216–221
  - S0253 L252–258
  - S0078 L248–260
  - S0969 L1245–1260
  - S0875 L1696–1702
  - S1498 L131–140
  - S0431 L17–21
  - S0103 L143
  - S0206 L281–284, L393–398, L513–519, L1414–1418
- `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv` (head, and by awk for ids).
- I did not open anything in 00-access/, the working chapters, the working THEORY-MAP/CONCEPT-REGISTER/BOOK-OUTLINE, BASELINE-REVISIONS, review-folder settlements or reports, or git history. I did not touch the S-IDs excluded in SCOPE §2.4. I listed the names of `W/06-theory/manuscript/review/` and `.../C38-S0201-S0204/` (BRIEF-W-STEP1.md, REVIEW-REPORT.md, SETTLEMENT.md etc. were seen as names; not opened).

**Commands and searches**
- `cat` of the three commission files and REVIEWER-INSTRUCTIONS.
- `ls`/`find`/`wc -l` of the copy and of `W/06-theory/manuscript/review` and `W/tmp/tools`.
- `Read` of the six section files (full).
- `python3 -I tmp/tools/undefined_terms.py --root <copy> --scope 09.13-09.18` (output to scratchpad r2.txt).
- `python3 -I tmp/tools/register_home_check.py --root <copy>`.
- `python3 -I tmp/tools/corpus_quote_check.py` on 09.13–09.18 (counts above).
- `python3 -I tmp/tools/inbound_citations.py --root <copy>` for: "counts consumptions that are not F", "ce_model.py v15", "withheld, not promoted", "T9-16", "D_S", "§9.16 R1", "§9.17 R3", "§9.15 G4", "§9.13 R1", "§9.18.1", "9.16 R1", "9.17 R3", "9.15 G4", "9.18.4", CON-0194, CON-0265, CON-0261, "9.13.2", "9.15 K1", "9.16 R3a".
- `python3 -I <copy>/tmp/tools/book_search.py --help`.
- greps over the copy for: D_S/D_W, "withheld", "not promoted", "consumptions that are not F", "model v15", "content tie", "returned to the Principal", "§9.15 K1"/"numbered successor", "K4-o"/"owner's guard", "F26 (b)", "L79/L80", "R3 (b)", and the grep of the eight inventories for 9.13–9.18 topic keywords.
- awk over CONCEPT-REGISTER.tsv for the scoped concept ids.
- `sed` ranges over the C39, C40 and C41 inventories, and over the corpus files named above.
- Scratch brute force (`/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/chk/helly.py`): Helly numbers for n = 3, 4, 5 ordered values, the ℤ window family, and the coupled space.
- Scratchpad output: `.../scratchpad/r2.txt` (R2 list).
- I changed nothing in the repository.

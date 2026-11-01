CONSOLIDATION S0278–S0302: INTEGRATION REPORT

Integration reviewer. Object: the frozen copy `<copy>` = `W/06-theory/manuscript/review/CONSOLIDATION-S0278-S0302/BOOK-UNDER-REVIEW/`, the three reports and the cited allowed sources. Every finding below was checked again against the copy and, where cited, against the source.

## Summary (10 lines)
1. **Findings: 41 checked in total.** A: 17; B: 17; C: 7, plus C's coverage misses. Result: 30 confirmed, 11 modified, 0 rejected outright. One part of A-F16 is rejected, and one duplicate (A's CON-0003 remark = B-01) is merged.
2. **No critical finding.** Nine are high:
   - A-F1, A-F2, A-F3 (Ch. 2);
   - B-01, B-02, B-03 (Ch. 3);
   - C-01 (Ch. 8);
   - plus the status change CON-0001 VALIDATED → PROVISIONAL, which is B-02's consequence and is not counted separately.
3. **A-F2 reproduced by my own brute force** (`cons2-i-overlap.py`). The book's set-overlap rule disagrees with supervaluation in 6 of 125 cases at n=3 and 60 of 625 at n=4. A's corrected rule disagrees in 0.
4. **A-F3 confirmed.** "UNKNOWN, never CONSISTENT" (02.08 L87, 02.09 L59) is defeated by rules with identical effects. The error is fail-closed: it costs liveness (§2.3 e), not safety.
5. **B-02 confirmed, with the reason modified.** S0016 states no value set for nature. The mismatch is between the GKC list the book uses and S0016's test rows (L127, L128, L130, L132). §3.1 L67 itself says CON-0001 "cannot … hold as written".
6. **C-01 confirmed (REVIEWER-NOTE item 6):** no content is missing. §8.56 L40 is the defect. §8.58 L57's quotation is correct once L40 is fixed.
7. **Line references in Reviewer A's report are shifted** (a process point, not a book defect). For 02.10 and 02.11, A's line numbers are those of one numbered listing of 02.09–02.11 run together. Subtract 87 for 02.10 and 134 for 02.11 (e.g. "02.11 L153" = 02.11 L19). I verified the content at the corrected lines; this report uses the corrected numbers.
8. **Concept verdicts:** all 56 concepts in SCOPE §2.2 have a verdict or a restatement check. The only status change is CON-0001 → PROVISIONAL.
9. **Coverage gaps:**
   - nobody gave the architecture view;
   - nobody checked Ch. 8's SEQ order outside §8.52–§8.61;
   - THEORY-MAP L412 counts are unverified;
   - source anchors into `docs/knowledge_tranfer/*`, GKC and EXT-029 cannot be checked (not allowed).
10. **Process independence:** I am a separate run that wrote none of the work and saw the reports only as inputs. This is independence of process, not of model.

## 1. Findings
Severity scale: critical / high / normal. Line numbers refer to the copy.

| ID(s) | Verdict | Sev | Ch. | Evidence (what I checked) | Correction |
|---|---|---|---|---|---|
| A-F1 | confirmed | high | 2 | 02.03 L68 says "provisional, CON-0192". Register row 193: VALIDATED, CONS-S0201-S0225. 09.14 L47 `[CON-0192 · validated]`; MAP L413. BOOK-OUTLINE L177 also says "provisional" (outside every reviewer's lines). | "validated at S0201–S0225, within its scope", at 02.03 L68 and BOOK-OUTLINE L177 |
| A-F2 | confirmed | high | 2 | 02.08 L85 against L77's supervaluation. My brute force (V={a,b}, one dimension): n=2 0/25, n=3 6/125, n=4 60/625 mismatches; A's corrected rule 0 in every case. Example: {a},{b},⊥ gives the rule U, supervaluation F. Error is conservative. | "intersection of the specified sides empty → false; otherwise unknown if some side is ⊥". Extend the L99 check to n ≥ 3. |
| A-F3 | confirmed | high | 2 | §2.3 (d) L64: a conflict needs a point where no x satisfies the effects. If the effects are satisfiable together everywhere (e.g. MUST φ twice), "no conflict" holds under every completion. So "never CONSISTENT" (02.08 L87) is false. Same over-statement at 02.09 L59. Contradicts §2.3 (e) liveness (L77). Fail-closed. | A's qualified form at 02.08 L87 and 02.09 L59. Apply after A-F2 (same paragraph). |
| A-F4 | confirmed (modified) | normal | 2 | Counterexample 1 holds by A′ᵣ (02.03 L47): rule scope {P,Q}, applicability P only, exception {P} empties A′ᵣ with strict containment. Counterexample 2 (equal scope, shorter validity = a waiver) is a reading, not a defect. 02.11 L19 (A: "L153") states the chain without the equality condition. | State the test on A′ᵣ over the rule's validity; add the condition at 02.11 L19. |
| A-F5 | modified | normal | 2 | Not a strict circle: §2.8.4a (2) (02.08 L116–120) **asserts** that "normative rules are independent obligations" without citing §2.5 for it. So §2.5's ground 2 rests on an unsupported premise. The ambiguity of "compete for one output" is confirmed by the TLS 1.3 vs "1.2 only" row (02.03 L33). | Mark ground 2 as a stated premise (Int/Src, RM). Restate the criterion as "jointly binding obligations vs selectors". |
| A-F6 | modified | normal | 2 | 02.05 L17 already limits the result to author-assigned ranks and leaves "standing enacted precedence" open. The residue: the case author = competent authority ranking in advance is that open question, unnamed. | One sentence at L17 linking the author = authority case to the open question. Title unchanged. |
| A-F7 | confirmed (modified) | normal | 2 | 02.05 L7 cites §2.10; 02.10 L23 cites §2.5: a circle. 00.05 L10 gives I-4 as "Contradictions stay visible: they coexist at rest and are resolved at use", with no "explicit status" and no "rank as annotation". Whether two unlinked rulings breach "visible" is **undecided**, not "no breach" as A says. | Cite 00.05 L10. Label the "explicit, visible status" reading Int at both places. Keep the I-7 breach. |
| A-F8 | confirmed | normal | 2 | 02.10 L20: "Prediction (Src, measured)" is a derivation from PK-P4. | "Hyp (derived from PK-P4)" |
| A-F9 | confirmed | normal | 2 | (b) verified: 01.09 L46 rules on two outcome concepts; 02.00a L15 generalises it. (a) and (c) verified at 02.10 L3, 02.09 L76, L82, 02.08 L147. | Qualifiers at first use. §2.0 L15 as an application of the §1.9 pattern. |
| A-F10 | modified | normal | 2 | 02.10 L24. RS is not on the allowed list, so this cannot be decided against the source. The correction matches 02.10 L7's "without a record of who or why". | Qualify as A proposes, labelled Int. |
| A-F11 | confirmed | normal | 2 | 02.11 L19 itself says "the sources imply but do not state", under "Integration (Ded)". | Label Int / convention. |
| A-F12 | confirmed | normal | 2 | 02.08 L173: strong Kleene over app(k). An empty or shrunk app(k) turns CANNOT DETERMINE (or DOES NOT APPLY) into APPLIES. L185 asserts "removing … costly" without deriving it. | Add the dual case: narrowing app(k) broadens applicability, and is a governed act. |
| A-F13 | confirmed | normal | 2 | 02.06 L11 uses SC-2 and ⊥ with no pointer. SC-2 is introduced at 02.08 L35. | Add "SC-2, ⊥ (§2.8.3–2.8.4)" at 02.06 L11. |
| A-F14 | confirmed | normal | 2 | S0034 L68 (Transactional consistency row): the quoted sentence has no source name. `Round39-MC` appears in L66's row. | "in S0034's D-2 table, whose constitution is `Round39-MC`" (Int) |
| A-F15 | modified | normal | 2 | (b) T2-10 is "Resolved" while its criterion ("the record and its standing") is unmet: confirmed. (c) T2-07 triage says CLOSE while the row says Open: confirmed (minor). (d) **T2-04 already cites Ch. 3 §3.1** (TODO L27); only T2-05 lacks §3.1 L40 and §3.9 C4. (a) and (e) accepted on a sample. | As A proposes, but (d) applies to T2-05 only. |
| A-F16 | partly confirmed | normal | 2 | 02.08 L276 "(below)" points to L270, which is above: confirmed. 02.10 L42 (A: "L129") "§2.8.6": **rejected as an error**, because the gate is recorded at 02.08 L135 (§2.8.6), and §2.8.7 L212 analyses it. | L276 "(above)". Optionally "§2.8.6–§2.8.7" at 02.10 L42. |
| A-F17 | confirmed | normal | 2 | Register CON-0063 meaning: "leaves its question silent". That is SC-2 vocabulary, and 02.08 L56 withdrew the SC-2 step. | Align the register meaning with L56's claim and its two senses. |
| B-01 (= A's out-of-scope note) | confirmed, merged | high | 3 | R1 (my run): `CON-0003 \| H3`. 03.01 L17 is tagged `validated`; the register says INVALID. | Tag → `invalid` |
| B-02 | confirmed (reason modified) | high | 3 | S0016 L127–132 test rows use "capability spec", "constitution", "model" ×2. Those values lie outside the GKC value list in 03.01 L15. S0016 L41 gives no value set, so the mismatch is book list vs source test, not a source contradiction. 03.01 L67: "CON-0001, CON-0002 and CON-0010 cannot all hold as written". | CON-0001 → PROVISIONAL (register and 03.01 L15). Reason: the value list does not cover the source's own test, and §3.1 L67. |
| B-03 | confirmed | high | 3 | S0009 L55: "SPLIT: KNOWLEDGE (kind) + ARTIFACT (kind)". 03.03 L29 reverses this, and does so under fork (B), which is unchosen. Projection fails provenance, not encoding or scope (L30). | "One (runtime asset) fails the test; artifact and projection are questioned on other grounds (Int)". Same at 03.08 L21 and T3-03. |
| B-04 | confirmed (modified) | normal | 3 | 03.03 L53 quotes "spaces NEST" inside the definition. 03.10 L29 and the register meaning exclude that clause. So the text and the register disagree on CON-0011's definition. The register reason (S0020 L96) treats coverage as boundary, which 03.03 L54 separates. | Drop the clause at L53 and restate the reason. The text and register then agree. |
| B-05 | confirmed (modified) | normal | 3 | At archetype level (nodes 03.08 L21), knowledge→knowledge (S0026 L42) is a self-loop. B's second witness, Vision↔Mission (S0026 L38), is **also** a self-loop (both PURPOSE). The valid witness is artifact→any concept (L43) plus projection→its sources (L46), which needs the premise that a projection's sources include artifacts. | Replace the witness, with that premise stated. Also 03.08 L49 (see R3). |
| B-06 | confirmed | normal | 3 | 03.10 L103: {∅,{1},{2}} is laminar; ∅'s supersets are not a chain. L105's check covers non-empty sets only. | "non-empty members" |
| B-07 | confirmed | normal | 3 | 03.06 L76 says "non-circular". The register's follow-up for CON-0258 is missing from the text. | Add the clause. |
| B-08 | confirmed | normal | 3 | 03.05 L46 ("regenerates") vs 03.07 L48 ("no longer regenerates"): modal vs actual. | State which. |
| B-09 | confirmed | normal | 3 | 03.10 L58–59 use §6.3 C1 and §6.1 M(β). | Premise gloss or a 03.00 note. |
| B-10 | confirmed | normal | 3 | 04.00 L3: the kinds of change are not in Ch. 4. | → Ch. 1 §1.6 |
| B-11 | confirmed | normal | 3 | 03.00 L6–7. My grep: S0166, S0889, S1052, S0651 each have 0 "CANDIDATE" and 0 "never authoritative". This is a search miss, not proof of their status. | Correct the list; qualify the claim. |
| B-12, B-13, B-14, B-17 | confirmed | normal | 3 | TODO L43 (S0021 → S0026 L36–46; §3.6 → §3.8 C8). 06.01 L44–49 tiers. TODO L31, L39, L40. 03.08 L21 "L25–32" vs actual L27–34. | As B proposes |
| B-15 | confirmed | normal | 3 | S0029 L16: "`enable` operates on CAPABILITIES, never adapters". "Port" is the book's reading. | Label sense C as Int. |
| B-16 | confirmed | normal | 3 | 03.09 L107/L110 (ask interrupts) vs L111 ("running plus a stop"). S0043 L49 "YES — it interrupts". | Separate block from interrupt. |
| C-01 | confirmed | high | 8 | 08.56 L40 contradicts L24, L34–38, L86, index L70, MAP L231 and log L83. S0293 L44 says "proposed mechanism pipeline"; the C63 inventory item 15 names no parser owner. Item 6: §8.58 L57's quotation is right and §8.56 L40 is wrong. No content is missing. | C's wording |
| C-02 | confirmed (modified) | normal | 8 | 08.60 L34, L40, L38, L41, L46 give "in part", "yes and left open" and "as reported". The notes at 08.18 L202, L213, L215 and 08.19 L238, L239 say a flat "Settled". **Added:** 08.20 L247's flat "Settled" drops L42's "repaired, not closed"; C called it consistent. | Grade each note as §8.60 does, including L247. |
| C-03 | confirmed | normal | 8 | MAP L227 "passed" vs 08.52 L1, L61, L111 "asserted" | "asserted" |
| C-04 | confirmed | normal | 8 | 08.59 L3 "pending that window's settlement"; 08.61 L3 settled. Log L88 lacks §8.59. | As C proposes |
| C-05 | confirmed | normal | 8 | 08.60 L36 vs L104–105 | C's wording |
| C-06 | confirmed | normal | 8 | Verified: S0284 L137 "'Epistemic Operating System' is now justified". S0296 L38 wording differs. S0241 L23 and L123 lack the quoted words, which are at L138. S0253 L190 blank, L191 has it. S0241 L112 blank, L113 has it. | Quote exactly; fix line numbers. |
| C-07 | confirmed | normal | 8 | Index L77 covers §8.18–§8.51 only. | Extend to §8.61. |
| C coverage O-1 to O-3 (S0294 UM-22 circular admission; vocabularies; dimension sets), S0281 "consumed" | confirmed MISSING | normal | 8 | Copy grep: "UM-22", "outside the admitted corpus", "consumed, not authored", "DV-5" all 0 hits. S0294 L859, L914 and S0281 L103, L221 read. | Home them in §8.56 / §8.52, or exclude with a reason. |

## 2. R3, every line (for each confirmed correction)
Dispositions use these labels: stays / same change / different change / not affected. `inbound_citations.py --root <copy>` cannot resolve "§2.8.4" (0 hits); for that target I grepped the copy.

| Correction | Inbound line | Disposition |
|---|---|---|
| A-F1 | 02.03 L68 | same change |
| | BOOK-OUTLINE L177 | same change |
| | 09.00 L24; 09.14 L47; register L193; MAP L413 | stays |
| A-F2 | 02.09 L59 ("§2.8.4") | stays (the corrected rule still gives UNKNOWN where it should) |
| | 09.14 L65, L262; 10.03 L137 | not affected |
| A-F3 | 02.09 L59 | same change |
| | 02.03 L77 (e) | stays (now consistent) |
| | 10.03 L137 "unknown never collapses (§2.8)" | stays (it concerns ⊥) |
| | 02.06 L11 "or its pairs reported UNKNOWN" | stays (a permitted conservative option) |
| A-F4 | 02.11 L19 | different change (add the equality / A′ᵣ condition) |
| | 02.08 L189 | stays |
| A-F5 | 02.05 L11, L15 | same change (label premise 2) |
| | 02.08 L116–120 | same change (criterion) |
| | 03.01 L44; 06.06 L54; 02.04 L53; 02.02 L7 | not affected |
| A-F6 | 02.05 L17 | same change |
| | 02.05 L1 (title) | stays |
| | 03.01 L44; 06.06 L54 | stays |
| A-F7 | 02.05 L7; 02.10 L23 | same change |
| | 00.05 L10 | stays |
| A-F8 | 02.10 L20 | same change |
| A-F9 | 02.00a L15; 02.10 L3; 02.09 L76, L82 | same change (qualifiers) |
| A-F10 | 02.10 L24 | same change |
| A-F11 | 02.11 L19 | same change (label; combine with A-F4) |
| A-F12 | 02.08 L185 | same change |
| | 09.14 L208 | not affected |
| A-F13 | 02.06 L11 | same change |
| A-F14 | 02.05 L23 | same change |
| | TODO L31 | stays |
| | register CON-0040 basis | check: wording not read in full |
| A-F15 | TODO rows | same change |
| A-F16 | 02.08 L276 | same change |
| A-F17 | register CON-0063 | same change |
| | 02.08 L55–56; TODO L33 | stays |
| B-01 | 03.01 L17 | same change |
| | 03.01 L52, L79; TODO L11; 08.29 L86; 08.30 L21, L74; MAP L18 | stays |
| | register L82 (CON-0081) | not affected |
| B-02 | register CON-0001; 03.01 L15 | same change |
| | 03.01 L67 | stays (now consistent) |
| | 03.00 L15 (an example tag) | not affected (optionally use another example) |
| B-03 | 03.03 L34 | same change |
| | 03.08 L21 | same change (combine with B-17) |
| | TODO L13 | same change |
| | 03.01 L95; 00.03 L66 | not affected |
| B-04 | 03.03 L53, L57–61; register CON-0011 basis; TODO L14 | same change |
| | 03.10 L29; 03.06 L63; register CON-0096, 0097 | stays |
| B-05 | 03.08 L46 | same change |
| | **03.08 L49** ("references is cyclic as in S0026") | different change (cite the corrected witness; S0021's references are not checked) |
| | 05.04 L106; MAP L24 | stays |
| B-06 | 03.10 L103 | same change |
| | 03.10 L105; TODO L47 | stays |
| B-07 | 03.06 L76 | same change |
| | register CON-0258 meaning ("the test is then non-circular") | same change |
| | TODO L46 | stays |
| B-08 | 03.05 L46; register CON-0260 | same change |
| | 03.07 L48 | stays (the "actual" side) |
| | 08.25 L27, L84; 08.29 L28; 10.01 L64; 10.02 L121 | not affected |
| | 08.29 L88–90 | not checked (B: "check") |
| B-09 | 03.00 | same change |
| | MAP L130, L369 | not affected |
| B-15 | 03.08 L73 | same change |
| | TODO L33 | stays |
| B-16 | 03.09 L111; register CON-0092 meaning | same change |
| | 02.10 L42, L44 | stays (they already say ask does not block) |
| | 06.04 L34, L59; 06.06 L25; 08.03 L30 | not affected |
| C-01 | 08.56 L40 | same change |
| | 08.58 L57 | stays (becomes exact) |
| | 08.57 L22, L53–55; 08.58 L22, L54–56 | stays ("fits (b)" survives) |
| | 08.57 L9, L20, L38, L83; 08.58 L82; index L71; MAP L231; log L83, L84; 08.61 L20 | not affected |
| C-02 | 08.18 L202, L213, L215; 08.19 L238, L239 | same change |
| | 08.20 L247 | same change (added) |
| | 08.18 L217; 08.20 L248; 08.21 L185, L186 | stays |
| C-03 | MAP L227 | same change |
| | index L66; 08.55 L6, L16, L24, L41 | stays |
| C-04 | 08.59 L3; log L88 | same change |
| | 08.61 L3 | stays |
| C-05 | 08.60 L36 | same change |
| | 08.60 L104–105; index L74; MAP L235 | stays |
| C-06 | the lines named | same change |
| | inbound (C's list) | not affected |
| C-07 | index L77 | same change |

## 3. References to missing claims (REVIEWER-NOTE item 6)
| Finding | Missing content or stale pointer? | Evidence |
|---|---|---|
| C-01 (§8.58 L57 quotes "the sources do not say which") | Neither: the cited line is the defect. §8.56 L40 states the opposite of its own section. | S0293 L44 "proposed"; C63 inventory item 15; 08.56 L34–38 |
| A-F7 (§2.5 ↔ §2.10 reading of I-4) | Content missing: neither section holds the I-4 text; Ch. 0 has it. | 00.05 L10 |
| A-F4 (§2.8.2 → "§2.11" for the equality act) | Content missing at the target. | 02.11 L19 has no equality condition |
| A-F16, B-10, B-12, B-17, C-04 | Stale pointers; the content exists elsewhere. | 02.08 L270; 01.06 via 04.00 L3; S0026 L36–46; 03.03 L27–34; 08.61 L3 |
| A-F15 (d) | Stale only for T2-05; T2-04 already points to §3.1. | Ch. 2 TODO L27, L28 |

## 4. Conflicts between reviewers
| Pair | Resolution | Reason |
|---|---|---|
| A (out-of-scope CON-0003 note) / B-01 | merged into B-01 | same R1 flag |
| A-F1 / C's scope | A-F1 carries BOOK-OUTLINE L177 | no reviewer's lines covered L177 |
| A-F2 / A-F3 (02.08 L85–87) | apply F2 first, then F3 | different sentences, compatible |
| A-F5 / A-F6 (02.05 L10–17) | one edit | compatible |
| B-03 / B-17 (03.08 L21) | one edit | compatible |
| B-01 / B-02 (03.01 table) | both apply | different rows |
| A-F15 (T2-05) / B-16 | point T2-05 at §3.9 C4 as corrected | compatible |
| A's CON-0055 check (02.10 L44) / B-16 | no conflict | B-16 leaves non-sufficiency intact, which L44 uses |
| C-02 ("L247 consistent") / my check | modified: L247 is in the change set | 08.60 L42 "repaired, not closed" |
| B-05 witness / my check | modified | Vision↔Mission is a self-loop at archetype level |
| A ("most concepts homed in Ch. 3 or Ch. 1") / SCOPE §2.2 | no gap | B covered every Ch. 3 home |
| Quote check: B 75 / mine 74 | no conflict | B's count includes TODO.md |

## 5. Defects in the rotated chapters (confirmed)
- **Ch. 2 (reviewer A):**
  - high: F1, F2, F3;
  - normal: F4 (mod), F5 (mod), F6 (mod), F7 (mod), F8, F9, F10 (mod), F11, F12, F13, F14, F15 (mod), F16 (L276 only), F17.
- **Ch. 3 (reviewer B):**
  - high: B-01, B-02 (mod), B-03;
  - normal: B-04 (mod), B-05 (mod), B-06 to B-17.
- **New, from my checking:**
  - (i) 03.08 L49 inherits B-05's witness;
  - (ii) 08.20 L247 belongs in C-02's set (Ch. 8, not rotated);
  - (iii) 02.06 L11's "its pairs reported UNKNOWN" is pairwise language in a set-wise section (low; L13 repairs it in substance);
  - (iv) 03.00 L15's example tag `[CON-0001 · validated]` becomes stale if B-02 is adopted (low);
  - (v) B's "22 PROVISIONAL" is a miscount: it lists 19 plus CON-0001 (report-level, not a book defect).

## 6. Coverage gaps (reported, not filled)
- **Architecture view** (whether the derivation runs from the primitives through the laws to the results): no reviewer gave it for Ch. 2 or Ch. 3.
- **Ch. 8 SEQ order:** checked only for §8.52–§8.61 (C-07). The rest of Ch. 8's order was not assessed in this consolidation.
- **THEORY-MAP L412 counts:** unverified by every reviewer and by me. The working-state tool was not run. The figure 293 does not follow from 302 minus the 8 named exclusions (= 294) without a further exclusion I could not identify.
- **Not checkable** (not allowed or not provided):
  - anchors into `docs/knowledge_tranfer/*` (RM, RS, SC, SD, EV, …);
  - GKC, EXT-029 and PKSM;
  - the audit-label files;
  - the repository code that Ch. 2 cites.
- **Partial reads:**
  - C read the C61–C66 inventories only through their item lines;
  - B spot-checked sources and did not check §3.10 C2's dates;
  - C's quote checking is a sample (the tool's pattern finds 0 in §8.52–§8.61; my run confirms 0/0).
- **Concepts:** every one of the 56 in SCOPE §2.2 has a verdict or a restatement check (A: 0056, 0063, 0082, 0083, 0034, 0040, 0055, 0061, 0062; B: the rest). CON-0034 was checked in both homes. None is left without a verdict.

**Process independence:** independence of process only. I wrote none of the work, and I used the three reports only as inputs to check.

## Files opened, commands and searches
- **Review folder:** REVIEWER-NOTE.md, SCOPE.md, INTEGRATION-COMMISSION.md, REPORT-A.md, REPORT-B.md, REPORT-C.md. `ls -la` of the folder listed the COMMISSION-A/B/C, SHA256 files by name; none was opened. Also `W/03-methodology/REVIEWER-INSTRUCTIONS.md`.
- **Copy (all under `<copy>/06-theory/`):**
  - Ch. 2: 02.03, 02.05, 02.06, 02.10 in full; 02.08 L20–32, L50–125, L135, L140–190, L212, L238–289; 02.09 L55–66, L76, L82; 02.11 L15–37; 02.00a L15; TODO.md in full.
  - Ch. 3: 03.00 L1–12; 03.01 L1–80; 03.03 in full; 03.05 L44–50; 03.06 L63, L70–80, L108–112; 03.07 L46–50; 03.08 L21, L38–50; 03.09 by grep; 03.10 L29, L56–60, L102–110; TODO by grep.
  - Other chapters: 00.05 L10 (grep); 01.09 L46; 04.00 L3; 06.01 L44–49; 08.08 L20.
  - Ch. 8: 08.52 L1 and grep; 08.56 L20–42, L84–88; 08.57 L53–55; 08.58 L54–57; 08.59 grep; 08.60 L1–4, L28–50, L100–108; 08.61 L3 grep; 08.18 L202, L213, L215, L217; 08.19 L238–239; 08.20 L247–248; 08.21 L185–186; 08.00 L66, L70, L75, L77; 08.99 L83, L88.
  - THEORY-MAP L18, L227, L231, L411–415; BOOK-OUTLINE L177; CONCEPT-REGISTER.tsv (awk extracts and counts).
- **Corpus (allowed list; sources checked against `CORPUS-ALLOWED.tsv` via awk):**
  - S0034 L64–71; S0016 L38–46, L120–134 (plus grep counts); S0009 L44–60; S0026 L36–52; S0029 L16, L60; S0043 L48–50;
  - S0293 L26–34, L44–46; S0284 L137; S0241 L23, L112, L113, L123, L138; S0253 L190–191; S0296 L38; S0294 L859, L873, L887, L914; S0281 L103, L221; S0298 grep "DV-5";
  - grep counts only: S0166, S0889, S1052, S0651.
- **Coverage basis:** `W/06-theory/manuscript/review/C63-S0293-S0294/STEP1-INVENTORY.md`, by grep only. It is named in SCOPE §2.3 and needed for task 3. It lies outside this folder, so the commission's folder restriction does not cover it; I record it here as a judgement call. `ls -d` named the C59 and C66 folders; their files were not opened.
- **Tools (run from W, read-only, `--root <copy>` from the first call):**
  - `register_home_check.py --root <copy> --help` (it ran R1: CON-0003 H3, CON-0133 H4);
  - `inbound_citations.py --root <copy> --help`; then "§8.56 C1", "CON-0192", "§2.8.4", "§2.5", "CON-0001", "CON-0003", "§3.3", "§3.8 C2", "§3.10 C4", "CON-0258", "CON-0260", "CON-0092", "CON-0011", "§8.60 C1", "§8.59 C2";
  - `undefined_terms.py --root <copy> --help` (it ran a whole-book list; I did not re-judge R2 candidates; the reviewers did);
  - `integration_check.py --root <copy> --help`: it failed at once because it takes a file path, and read nothing;
  - `corpus_quote_check.py` on `<copy>` chapter_02/0*.md (10/0), chapter_03/0*.md (74/0), and 08.52–08.61 (0/0).
- **Searches (grep / python on the copy):** "time containment|exception validity"; "destructive"; "(below)"; "§2.8.4"; "never CONSISTENT|unknown overlap"; "§2.8"; "§2.10"; "I-4"; "PK-P7"; "cyclic|self-loop"; "forest|laminar|non-empty"; "running plus a stop|interrupt"; "port…adapter"; "T3-xx/SEQ-0012"; "CON-0067"; "CON-0162"; "passed by|asserted"; "pending that window"; "UM-22", "DV-5", "consumed, not authored", "outside the admitted corpus", "theorem-backed"; heading list of 02.08; `wc -l` of Ch. 2 and Ch. 3.
- **Scratch files** (session scratchpad, read-only on the book):
  - /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/cons2-i-overlap.py
  - /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/cons2-i-ctx.py
- **Item-4 files:** none reached. No working chapter, map, register or outline; nothing in 00-access; no git history; no file comparison. Nothing was written to the repository, and no text was sent to any outside service.

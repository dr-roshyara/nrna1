CONSOLIDATION S0182–S0200: INTEGRATION REPORT

Integration reviewer, 2026-10-28. Object: the frozen copy `BOOK-UNDER-REVIEW/` only. Nothing in the repository was edited. Scratch: /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/consolI

**Access breach (stated first):** early on I ran `grep -c . 00-access/*` from W. It printed one line count ("426"). It showed no file content and no file name. It still reached a file in `00-access/`, which the commission forbids. Nothing from it was used.

## Summary (10 lines)
1. Of 37 reviewer findings (A-01–A-20, B-01–B-17), they come to 28 integrated defects: 19 confirmed, 9 modified. B-17 is rejected as a defect (the book already records it) and kept as a recommendation. One sub-point of B-14 is rejected.
2. No critical finding. Three are high: I-01 (§6.7 C1 inverts S0089 L81; both reviewers), I-02 (Ch. 8 index gives CON-0208 as "invalid" and CON-0213 as "validated"), and I-04 (§8.30 says the AIP reconstruction is known only by relay; the book reads it, S0234, directly at §6.5 and §9.6).
3. Three findings were downgraded from high to normal: B-02 (an overclaim in the plain-language text only; the body is scoped correctly), B-03 (an omission in §9.13.2, not a contradiction) and A-03.
4. The quote tool passes §6.7 with 33 found and 0 not found, even though I-01 holds. The inverted sentence is an unquoted paraphrase. A tool pass does not establish fidelity in meaning.
5. The v20 model: I checked code lines only (ROOT_OWNER = 'A0' at ce_model.py L129; store_owner L261–268). Byte-identity of the run is reported by A and B; I did not re-run it. Prevention by v20 is shown only under a declared-application premise. That premise is stated in §9.17 L166 and L204, but L173, §9.13.2 L48 and register row CON-0264 lack it.
6. Concept conflicts: CON-0121 stays VALIDATED (scoped). CON-0127, 0147 and 0176 go to VALIDATED (both reviewers agree; scoped). CON-0128 and 0059 stay PROVISIONAL.
7. New defects found while checking: CON-0264's register row lacks the condition (N-1); §8.8 L52's "Ten cases" falls with A-12 (N-2).
8. Coverage gap: 76 Ch. 8 concepts homed in §8.07–§8.26 have no text-based second verdict. B's verdicts on them rest on register rows only.
9. R3: 7 inbound lines need the same change, 1 needs a different change, and the rest stay true or are unaffected (table below).
10. What remains unproven: the v20 run identity (reported, not re-run by me); S0195 L78 (not read); A's §6.4 C3 and B's Ch. 5 and Ch. 9 SEQ counts (not re-checked).

**Process independence:** I am a separate session. I did not open REPORT-A's or REPORT-B's scratch folders (consolA, consolB), the working book, or any file named in REVIEWER-NOTE item 4. This is independence of process, not of person. The three scratch folders share one parent directory.

## 1. Findings
| ID(s) | Verdict | Sev | Ch | Evidence (checked by me) | Correction |
|---|---|---|---|---|---|
| I-01 = A-01 + B-01 | confirmed | high | 6 | 06.07 L28 reads "a governed artifact … evidence of registration". S0089 L81: "never committed, therefore not a governed artifact ⇒ evidence of NON-REGISTRATION, not … NON-EXISTENCE". S0090 L57: untracked. §8.9 L77 and §1.13 L112 state it correctly. | A's wording: "not a governed artifact: S0089 classes an untracked diagram as evidence of non-registration, not of non-existence (S0089 L81)." |
| I-02 = A-02 | confirmed | high | 8 | 08.00 L28: "CON-0208 invalid as stated". L29: "CON-0213 validated". The register and §8.14 L26 have VALIDATED and PROVISIONAL. Register CON-0213 `same_subject_as` still reads "CON-0208 (invalid as stated)". | Rows 8.14 and 8.15 as A proposes; fix `same_subject_as`. |
| I-03 = A-03 | modified (high→normal) | normal | map | MAP L373 has `[CON-0265 ~]`; L198 and L372 have ✓ / VALIDATED. L96 has `[CON-0079 ~]`; the register has VALIDATED. | Set ✓ at L373 and L96. |
| I-04 = A-04 | confirmed | high (lineage; no derived claim overturned) | 8 | 08.30 L7: "Everything said about it is relayed by S0198 and S0199". S0198 L7 reviews the S0234 path at commit 0d6fb1fc. The book reads S0234 directly at §6.5 L63 and §9.6 L162–163, L195, L244, L246 (L246 uses S0234's erratum). S0234 L13–21: erratum E-1 to E-3, with "not needed" withdrawn. | Lineage: "not in this window; read directly at §6.5 and §9.6 (S0234, erratum applied)". Add §6.5 and §9.6 to the homes table. C2: name the direct route. C4: cite S0234 L15 as the applied erratum (that this is a "disposition record" stays Int). |
| I-05 = A-05 + B-03 + B-04 | confirmed; B-03 modified (high→normal: an omission, not a contradiction) | normal | 9 | ce_model.py L129: `ROOT_OWNER='A0'`; L261–268. 09.17 L166: "The owner is read from the record: the root carrier's owner". L173: "a single foreign move … is detected", without a condition. L204 has the condition. 09.13 L48's guard lists no owner refusal (D's checks do). Its "across merged applications a fork is detected, not prevented" agrees with decision record L202. | (a) L166 and L204: "the root carrier's owner is set with the root designation, outside D (§9.15 L11); every later owner is read from the record." (b) L173: add "if the mover declares its own application". (c) §9.13.2 guard: add the owner refusal with that condition, and point to §9.17.2 for carrier, old store and carrier in force (A's SEQ point). |
| I-06 = B-05 | modified | normal (low) | 9 | The T9-10 row already says "closed as a design sketch with its assumptions stated (§9.17 (c′))". The closure is the Principal's act (`a51be45b8`), so its status is not the book's to change. | Name the declared-application premise in the row; keep the status. |
| I-07 = A-06 | confirmed | normal | 8 | S0183 L291–294 concern the AMD6 review. S0185 L1, L9 and L33 concern the DV-correction review (grant 22), and L38 gives the status for that review. Different subjects, so "errs broad" is not shown. | A's wording; keep "too narrow". |
| I-08 = A-07 + B-09 | confirmed (duplicate) | normal | 8 | 08.27 L30: "must be recorded if it did". S0184 L96: "'recorded' is load-bearing — a live but unregistered act still counts as missing". S0185 L108 likewise. | "…and an act that happened but is not recorded counts as missing." |
| I-09 = A-08 + B-10 | confirmed (two halves of one sentence) | normal | 8, 0 | 08.28 L53. S0189 L1978–1983: the "exact PKS/EKS runtime boundary" needs further archaeology, which presupposes a boundary between two. S0187 L740: criterion 2 names no PKS. | Limit the presupposition to criterion 1 and S0191 L465–467. Cite L1983 as "exact runtime boundary unknown". Rest the one-system reading on S0186 L14 (S0195 L78, cited by A, not read by me). |
| I-10 = A-09 | confirmed | normal (low) | 8 | S0188 L271 defines Rule as "authoritative obligation". L288 is S0188's own finding that "Rule" is a homonym. | As A proposes. |
| I-11 = A-10 | confirmed | normal (low) | 8 | 08.29 L70: "model v19 shows …"; v20 is current. | Say "v20". |
| I-12 = A-11 + B-06 | merged; B-06 modified | normal | 8 | S0200 already defines C+iT (L166–172), the 5-vector with S = "semantic validity" (L330), and E+iP (L345). It writes "I would probably move beyond complex numbers" (L313) and "a special two-dimensional projection" (L336). It uses its first axis in three senses: M, "What does the artifact claim?" (L77–82); S graded high/low (L204–209); S as validity (L330). The book's "content" reading fits L36–46 and L77–82 only. B's "S = validity" is one sense, not the reading. The "merge" bullet already concerns A only. | Last bullet as A proposes. Record the concession and the drift of the axis senses. No change to the "merge" or ΔK bullets. |
| I-13 = A-12 | confirmed | normal | 8 | S0073 L82: "START 37/37 human" in a cross-tab. L88 and L155: 6 of 37 STARTs say in their own text that Governance recorded them. These are two classes, not conflicting counts. | Drop the pair, or restate it as two classes. See N-2. |
| I-14 = A-13 + B-15 | confirmed | normal | 6 | 06.06 L4 says "undated"; L11 says "It is not undated". The 06.00 index runs 6.7→6.1. | Delete "undated"; reorder the index. |
| I-15 = A-14 | confirmed | normal (low) | 6 | T6-02 points to "§6.1.3 (C7)". §6.1.4 L101 links T6-02 to C6. | Point T6-02 to C6; relate it to T6-03. |
| I-16 = A-15 | confirmed | normal | 8 | The 08.05 header lists 3 sources. C9 says "all five" and cites S0051, S0052, S0054 and S0055; S0053 is not cited. | "in four of the window's sources". |
| I-17 = A-16 | confirmed | normal (low) | 8 | `cat -A`: 08.99 has no title or header row. | Add them. |
| I-18 = A-17 + B-14 | confirmed (basis column); B-14's CON-0176 sub-point rejected | normal | 6, 8 | My count: 41 register rows with basis "not yet reviewed" carry review evidence (A: 40, a different heuristic). In scope, exactly B's 18. CON-0176's meaning reads "constant on fibres", as §6.7 L74 and L105 do, so there is no mismatch. The unregistered valid cores (CON-0119 "valid as two axes", CON-0122 "valid core below") are a fact, recorded for the Principal; not a defect. | Synchronise the basis column. |
| I-19 = A-18 | confirmed | normal | 8 | Checked §8.27 and §8.28 only. Cited in the bodies but missing from the tables: S0183 L294, S0185 L38, S0186 L14, S0189 L1983, S0190 L113, and others. | Complete the tables. |
| I-20 = A-19 | modified | normal (low) | 8 | S0182 L57–60 is scoped to the 27 renamed corpus entries: confirmed. A's "filename time is the mtime" is not established by L57–60. | Add the scope only. |
| I-21 = A-20 | confirmed | normal | 8, 9 | 59 uses of "adviser" or "Rule Reviewer" in 14 chapter files plus the map. My patterns found no definition. | Define once, or link a definition. |
| I-22 = B-02 | modified (high→normal) | normal | 8 | 08.28 L30 and L111 overstate. L31's first sentence is S0190 L25's own claim, so attribute it; its second sentence overstates. The body at L50 and L62 is scoped. The overclaim did not reach the T9-11 row. | "in the sources read" / "in this window". See R3 for §9.17 L166. |
| I-23 = B-07 | confirmed | normal | 8 | `git log`, c673de5de: "P4 gate OPEN conditional on P3-F1 bounded evidence completion". S0198 L52: "remains CLOSED until this lands". The two agree. | Quote both in full, or delete the contrast. |
| I-24 = B-08 | confirmed | normal | 8 | S0182 rows 01–06 link S0178, S0176, S0177, S0180, S0181 and S0175, all on CORPUS-ALLOWED. Only column 4 names files that are not allowed. | "…whose fourth column names corpus files not on the allowed list". |
| I-25 = B-11 | modified | normal | 8 | 08.28 L62: "a direct estate instance". S0190 L25 concerns an AI process's identity. The step from a declared application to a declared process is not stated. | "an instance of the same kind of premise (declared identity); that the moving application is declared by the moving process is the step to state". |
| I-26 = B-12 | confirmed | normal | 8 | 08.00 L43 cites "(C7)". §8.29 has C1–C6, and the phrase occurs nowhere else in the copy. | Delete the clause. |
| I-27 = B-13 | confirmed | normal | map | MAP L374: "§8.1–8.26". OUTLINE L183: "§8.1–8.21". | Make both "§8.1–8.30". |
| I-28 = B-16 | modified | normal (low) | 8 | S0196 L494: a heredoc bypasses the `ask` gate (possible-after-authorization); the deny mapping is separate. 08.29 L33's "a protected file" is broader than that. | "a file whose writing needs approval". |
| — B-17 | rejected as a defect | — | 8 | Already recorded at 08.00 L48 (DB-4) and in T0-03 ("a coverage ledger, not validation"). | Recommendation to the Principal only. |
| N-1 (new) | new | normal | 9 | Register CON-0264 (L265): "so a single foreign move is detected" lacks the declared-application condition. | Same change as I-05 (b). |
| N-2 (new) | new | normal | 8 | 08.08 L52: "Ten cases in one estate" counts the S0073 pair that I-13 removes. | "nine", or restate the pair as two classes. |

Out of scope, one line each, not reviewed: "Rulebook v1.3" at §8.1 and OUTLINE L186 (A). The CON-0076 dual home (A). The R1 H4 flag on CON-0133, reproduced by my R1 run (1 flag, 5 untagged homes). B's "18 of 119 rows in scope": the scope has 118 rows.

## 2. R3, by inbound line
Run: `inbound_citations.py --root …/BOOK-UNDER-REVIEW`. Section-only hits are dispositioned as a group after a wording grep. A group is "not affected" when no line in it carries the changed wording.

| Correction | Inbound line | Disposition |
|---|---|---|
| I-01 | MAP L242 ("untracked") | stays true |
| I-01 | 08.09 L77; 01.13 L112 (wording grep) | stays true (already correct) |
| I-01 | 22 section-only (06.99 L29–36, 08.09 L118, 08.10 ×6, 09.08 L151, MAP L246, OUTLINE L61, L140, L141) | not affected |
| I-02 | 08.00 L28 | same change |
| I-02 | 08.00 L29 | same change |
| I-02 | register L214 (CON-0213 `same_subject_as`) | same change |
| I-02 | 08.14 L26, L86; 08.15 L17, L35, L92, L97; register L209, L216 | stays true |
| I-02 | 08.18 L19, L158, L194, L196; 08.19 L138; 08.21 L79, L81; 08.22 L22, L90 (cite §8.14 C1 for the history rule) | not affected |
| I-03 | MAP L373 (CON-0265) | same change |
| I-03 | MAP L96 (CON-0079) | same change |
| I-03 | the other 26 CON-0265 lines and 6 CON-0079 lines (08.00 L41; 08.18 L212; 08.19 L236; 08.21 L182; 08.22 L110; 08.26 L21, L54; 08.27 L22, L61, L64; 09.00 L27; 09.15 L74; 09.17 L177, L225, L309; 09.99 L65, L66, L69; register ×5; MAP L196, L198, L372; 05.03 L24; 08.28 L25, L99; register L80) | stays true |
| I-04 | MAP L201 and 08.00 L44 ("relayed twice") | stays true (window-relative; the measurement is one, so the direct route adds no count) |
| I-04 | 08.99 L2, MAP L370 | not affected |
| I-05 (a) | 09.17 L204 ("ownership … read from the record") | same change |
| I-05 (b) | register L265, CON-0264 | same change (N-1) |
| I-05 (b) | 09.17 L178 (describes the W-01 probe) | stays true (it describes a test case; the condition sits in limit (ii)) |
| I-05 | MAP L195 ("fork detected, not prevented") | stays true |
| I-05 | MAP L197 ("conflicting moves are prevented", the decision's wording) | stays true as a report of the decision; the condition is at MAP L199 |
| I-05 | MAP L199 | stays true |
| I-05 | "R3 (c)", "(c′)", "§9.13.2" (the tool cannot resolve these; grepped): 08.19 L236; 08.23 L65; 08.24 L24, L25, L93, L95; 08.27 L3, L23, L70; 08.28 L20, L58; 08.29 L25, L70, L72, L75; 09.99 L67–76; Ch. 9 TODO L18–21; 05.05 L70, 05.06 L74, 05.07 L81; 08.20 L59; 08.22 L18, L57; 08.26 L65; 09.08 L116; 09.10 L6; 09.12 L33; 09.15 ×7; 09.16 ×4; MAP L193 | not affected (none rests on the root-owner wording or the unconditioned L173) |
| I-05 | 08.28 L62 | different change: I-25 |
| I-06 | T9-10 (grepped): TODO L20 | same change |
| I-06 | 09.17 L174, L175, L202, L203, L205; 09.99 L73–75; MAP L195, L196 | stays true |
| I-07 | 08.28 L21, L64, L67; Ch. 8 TODO L13 | stays true (they cite the record-over-relay half) |
| I-07 | 14 section-only | not affected |
| I-08 | 08.28 L69; 08.29 L21, L37, L49 | stays true |
| I-09 | Ch. 0 TODO L27 (T0-03b) | same change |
| I-09 | MAP L199 ("whether PKS and EKS are one system is open") | stays true |
| I-09 | 08.30 L19, L50, L56 | not affected |
| I-09 | 08.00 L42 ("two of them resting on an untested EKS/PKS independence") | different change: "one of them" (criterion 1) |
| I-09 | T0-03b hits (grepped): 08.29 L70, L97; 08.30 L79 | not affected |
| I-10 | §8.28.3 (grepped): 08.28 L55 | stays true |
| I-11 | §8.29 C4: 7 section-only | not affected |
| I-12 | §8.30 C5: 08.99 L2, MAP L201, L370 | stays true |
| I-13 | 08.08 L52 ("Ten cases") | same change (N-2) |
| I-13 | 05.07 L16, L58, L60; 06.07 L17, L75, L106; 08.09 L14, L61, L63 | stays true (they cite the rule) |
| I-14 | §6.6: 38 section-only | not affected ("undated" occurs only at 06.06 L4) |
| I-15 | 06.03 L25, L27, L28; Ch. 6 TODO L11 | stays true |
| I-15 | §6.1 C7: TODO L13 | not affected |
| I-16 | 08.08 L15, L45 | stays true |
| I-16 | 08.08 L52 ("six cases") and 08.05 L72 | stays true |
| I-22 | §8.28 C2: 08.29 L21, L51 | stays true |
| I-22 | 09.17 L166 ("so this premise has no instance there") | same change (scope: "reported none") |
| I-23 | §8.30 C4: 3 section-only | not affected |
| I-24, I-27 | no claim-level lines | not affected |
| I-25 | 08.29 L21, L51; 09.17 L166 (S0190 attributed) | stays true |
| I-26 | "§8.29 C7": 0 claim-level; 7 section-only | not affected |
| I-28 | §8.29 C5: 08.99 L2 | stays true |
| I-28 | 6 section-only | not affected |
| I-20 | §8.27 C7: 08.29 L23, L63 | not affected |

## 3. Conflicts
| Pair | Resolution | Reason |
|---|---|---|
| CON-0121 (A V / B P) | VALIDATED, scoped | Validated as a method rule and a possibility: a different method can expose what the original's blind set hides. The benefit claim (a factor change lowers overlap) is Hyp under T8-02, and the register basis already says "generality Hyp". B's ground concerns a claim the concept does not make. |
| CON-0127 (A V / B V, logical half) | VALIDATED within scope | The deduction holds under R ⊆ F and an open world. "Never PASS" is policy. Both reviewers agree. |
| CON-0128 (A P / B V) | PROVISIONAL | The probability core is standard; both agree. But the concept also calls §4.1 C5 and the lineage rule "conservative". Under dependence the joint LR is bounded by neither single ratio (the register's own wording), so counting dependent sources as one line can understate or overstate. "Conservative" is not shown. |
| CON-0059 (A P / B V) | PROVISIONAL | One verified counterexample would settle the non-implication. This one comes from a self-reported n=1 experiment whose raw record is unavailable (T8-01). |
| CON-0147, CON-0176 (both upgrade) | VALIDATED (0176 as mathematics) | Both reviewers re-derived them. The kernel-law reading of 0176 stays Hyp. |
| B-06 / A-11 (S0200) | merged into I-12 | Both rest on S0200 L311–346. B's reading of S as validity is one of three senses in the source. |
| B-11 / §8.28 C2 (C35 W-02, settled) | B-11 stands as a strength correction (I-25) | C2's claim that the estate reports no attestation of identity is untouched. A settled status does not make a wording immune to correction. |
| A-04 / B's note on CON-0245 ("relayed twice") | both hold at different levels | Within the window it is relayed twice. Across the book it is one measurement reaching by three routes (S0136 via §8.19; S0234 via §9.6; S0300/S0199 via §8.30). None of them is a reproduction. |
| A-17 (40) / B-14 (18) | both reproduce | Register-wide versus in-scope populations. |
| A-07 / B-09 | identical | I-08. |
| A-13 / B-15 | identical | I-14. |
| A-08 / B-10 (§8.28 L53) | one combined restatement | I-09. |
| A-05 / B-03 / B-04 / B-05 / N-1 (§9.17 L166, L173, L204; §9.13 L48; TODO L20; CON-0264) | apply as one edit | A's root-owner wording plus B's condition everywhere the guard is described. A's SEQ pointer goes into the same §9.13.2 edit. |
| A-04 / B-07 (§8.30 C4 V line) | apply together | B-07: full quotations. A-04: S0234 L15 as the erratum text. |
| A-03 / B-13 (MAP L373 / L374) | compatible | Adjacent lines, independent edits. |
| A-02 / A-17 (register CON-0213) | compatible | Different columns. |
| B-02 / I-22 R3 at 09.17 L166 | same scope change | — |

## 4. Defects in the rotated chapters (confirmed)
| Ch | A only | B only | Both | New |
|---|---|---|---|---|
| 6 | I-15 (T6-02); §6.4 C3 forward pointer (not re-checked by me) | Ch. 6's 39 forward references to Ch. 8/9 (not re-counted) | I-01; I-14 (header and reversed index); I-18 (Ch. 6 rows) | — |
| 8 | I-02, I-04, I-07, I-10, I-11, I-13, I-16, I-17, I-19, I-20, I-21 | I-22, I-23, I-24, I-25, I-26, I-28 | I-08; I-09 (two halves); I-12; the index note limits Ch. 9 usage to §8.18–§8.21 though §8.22–§8.30 use it too (e.g. 08.27 L23); I-18 (Ch. 8 rows) | N-2 |
| other | I-03 (map); I-05 (a) (Ch. 9); Ch. 0 TODO order T0-03, 03b, 03a (confirmed, L26–28) | I-06, I-27 | I-05 (b, c) | N-1 (register, Ch. 9) |

## 5. Coverage gap (§8.07–§8.26; not filled)
These concepts lack a text-based second verdict. Both reviewers have one only for 0208, 0209, 0210, 0213 and 0214 (A spot-read §8.14 L20–40). For all the rest, B's verdict rests on register rows ("R").
- VALIDATED (11): 0129, 0153, 0219, 0220, 0226, 0232, 0239, 0240, 0242, 0245, 0253.
- PROVISIONAL (49): 0130, 0141, 0150, 0151, 0152, 0154, 0156, 0158, 0178, 0179, 0180, 0181, 0195, 0196, 0199, 0200, 0201, 0203, 0204, 0206, 0207, 0211, 0212, 0215, 0216, 0217, 0221, 0222, 0223, 0224, 0225, 0228, 0229, 0230, 0231, 0233, 0236, 0237, 0238, 0241, 0243, 0244, 0248, 0249, 0250, 0251, 0254, 0257, 0259.
- INVALID cores (16): 0155, 0157, 0159, 0197, 0198, 0202, 0205, 0218, 0227, 0234, 0235, 0246, 0247, 0252, 0255, 0256.
- Total 76, which matches A's "not assessed" list (homes computed from the register: 81 in §8.07–§8.26, less those 5).
- Confirmed defects inside these sections: I-13 and N-2 (§8.8 C2). I-18's stale basis rows for 0129, 0130 and 0141 (homes §8.07, §8.08). I-02 concerns §8.14/§8.15 concepts, but the defect lies in the index and register, not the section text. I-04 touches CON-0245's home §8.19 only by cross-reference; no defect was found in §8.19.

## 6. Verified / reported / interpreted / recommended
- **Verified by me:** every source line cited in section 1 (read with my helper, which refuses the six barred IDs). The register rows. The git subjects and times of c673de5de, e5f98a6ad, 6034db5c0, 0d6fb1fca and 61219780e. The quote check (Ch. 6 and §8.27–§8.30: 0 not found). R1 (1 flag).
- **Reported, not re-done:** the v20 run's byte-identity; A's probes P1–P4; A's numbers in the LR counterexample; B's forward-reference counts.
- **Interpretation:** severities; the conflict resolutions in section 3.
- **Recommendation:** the corrections, and B-17's coverage-ledger note for DB-4.

## Files opened (read-only)
- **Review folder:** INTEGRATION-COMMISSION.md, REVIEWER-NOTE.md, SCOPE.md, REPORT-A.md, REPORT-B.md.
- **Copy (BOOK-UNDER-REVIEW/06-theory):** CONCEPT-REGISTER.tsv (python csv); THEORY-MAP.md (lines 96, 193, 195, 197–201, 242, 370, 372–374); manuscript/BOOK-OUTLINE.md (L180–190).
- **chapter_06:** 06.00 (index rows); 06.01 L80–110; 06.06 (grep); 06.07 L26–30 and grep; TODO (grep).
- **chapter_08:** 08.00 L25–48; 08.05 L1–5 and L62–70; 08.08 L45–55; 08.09 L77; 08.14 L26; 08.27 L1–50, L78–82 and the references table; 08.28 L8–75, L105–115 and the references table; 08.29 L26–33, L70–78; 08.30 L6–7, L41–96; 08.99 (cat -A); TODO (grep).
- **chapter_09:** 09.13 L48; 09.17 L160–178, L200–206; TODO L20–21.
- **chapter_00:** TODO L25–28, L42. 06.99 head. Whole-copy greps by script.
- **Tools (W/tmp/tools, read-only):** inbound_citations.py (head), ce_model.py (L125–132, L255–272); ran corpus_quote_check.py and register_home_check.py.
- **W/02-FILES.jsonl:** by script, for ID→path.
- **Allowed list:** docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv (sha256 1f8e7638…c5c1; head and awk lookups).
- **Corpus (cited lines only, all on the allowed list):**
  - S0073 L82, L88, L155;
  - S0089 L81; S0090 L57;
  - S0182 L39–45, L57–60;
  - S0183 L1–6, L288–296;
  - S0184 L96;
  - S0185 L1–12, L23, L33, L38, L108;
  - S0186 L14;
  - S0187 L734–757;
  - S0188 L271, L288–292;
  - S0189 L1978–1988;
  - S0190 L25;
  - S0191 L463–468;
  - S0196 L490–496;
  - S0198 L1–12, L37–40, L50–53;
  - S0200 L36–104, L158–212, L310–348;
  - S0234 L1–22.
- **00-access:** reached once by `grep -c` (a count only; see the breach note at the top).
- No item-4 file was opened. No manuscript git history was read.

## Commands and searches run
- `cat` of the five review-folder files. `mkdir -p` of the scratch directory. `find`/`ls` of the copy and of W. `ls tmp/tools`. `head -c 600 02-FILES.jsonl`. `grep -c . 00-access/*` (the breach).
- `find` for CORPUS-ALLOWED (under W, then under docs/knowledgeos). `sha256sum`, `head` and awk ID lookups on it for S0052, S0053, S0073, S0075, S0089, S0090, S0113, S0120, S0175–S0182, S0188, S0196, S0199, S0200, S0234 and S0300.
- Scratch scripts, outside the repository:
  - src.py prints source lines and refuses S0155, S0156, S0157, S0161, S0170 and S0174;
  - ctx.py is a regex context grep.
- python: register rows (CON-0059, 0079, 0104, 0121, 0127, 0128, 0147, 0176, 0208, 0209, 0213, 0239, 0265); index-row context; THEORY-MAP and OUTLINE line extracts; the stale-basis count (register-wide and in scope); Ch. 8 homes grouped by section range and status; the S0182 linked-file → ID mapping.
- Greps and ctx on the copy for:
  - "relayed by|S0234|reconstruction"; S0234 in 06.05 and 09.06; "constant on"; CON-0119|CON-0122; "undated"; "governed artifact|NON-REGISTRATION";
  - "R3 (c)", "(c′)", "§9.13.2", T0-03b, T9-10, §8.28.3, §8.2x.1; "adviser|Rule Reviewer" (count, and definition patterns);
  - the changed-wording set: "evidence of registration|owner is read from the record|single foreign move|might define later|errs the other way|too narrow or too broad|must be recorded if|no such mark|no instance in the estate|direct estate instance|37 of 37|all five sources|six cases|view is never authority|§8.1–8.2x|v19 shows|nothing in the estate answers|boundary UNKNOWN|presuppose that EKS|detected, not prevented|1983".
- `sed`/`awk` line prints of the copy ranges listed above. `cat -A` on 08.99. `grep -n` of the claim headers in 08.29, of T6-02/T6-03 and of the §6.1 C6/C7 headers.
- `git log -1 --format='%h %ad %s' --date=iso` on c673de5de, e5f98a6ad, 6034db5c0, 0d6fb1fca and 61219780e (corpus commits).
- `python3 -I tmp/tools/inbound_citations.py --root …/BOOK-UNDER-REVIEW` with targets: §6.7 C1 (alone, then with the rest), CON-0208, CON-0213, §8.14 C1, CON-0265, CON-0079, §8.30 C2, §8.30 C4, §8.30 C5, §8.27 C1, §8.27 C2, §8.27 C7, §8.28 C1, §8.28 C2, §8.29 C4, §8.29 C5, §8.29 C7, §8.8 C2, §6.6, §6.1 C6, §6.1 C7, §8.5 C9, §9.17, §9.13. Output saved to scratch as r3_all.txt.
- `python3 -I tmp/tools/corpus_quote_check.py` on chapter_06/06.0*.md and 08.27–08.30 of the copy.
- `python3 -I tmp/tools/register_home_check.py --root …/BOOK-UNDER-REVIEW`.

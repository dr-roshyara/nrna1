CONSOLIDATION S0378–S0402: INTEGRATION REPORT

Integration reviewer. Object: the frozen copy `…/review/CONSOLIDATION-S0378-S0402/BOOK-UNDER-REVIEW/` only. Line numbers are the copy's. "Verified" means I re-read the cited copy line or source line myself. "Accepted on report" means I did not re-check it and rely on the reviewer's evidence.

## Summary (10 lines)
1. Of 92 reviewer findings (A 17, B 19, C 12, D 14, plus out-of-scope notes), I confirm 84, modify 5 and reject none. After merging there are 7 high findings and no critical one. I add 4 new normal findings (I-01 to I-04).
2. The high findings are A-01, A-02, B-01, B-02, B-03, D-01 and D-02. A-02 is normative and goes to the Principal; the others are edits.
3. The Ch. 10 changes of 2026-11-03 follow from U2 and U6 for placement (consequences 1, 3, 6), with two exceptions. Consequence 4 (§10.2 L115) reverses §9.18 L273: it says the relocation test makes kernel placement necessary, but §9.18 L273 says the test shows g1 could sit in an application. Consequences 1 and 2 put placement where an owner or enforcer belongs.
4. The closure of T10-20 treats U2's placement as an answer to a subdomain-class question. §10.1 stipulation 2 says no class is decided by kernel membership. One question to the Principal follows (task 9).
5. Several Ch. 10 rows are still stale against decided Ch. 9 results: G2, G4 and G7 (U2); law 18 / F36 R6 (adopted), in two places; evidence kinds in D; §9.07 L86's inference gap; the G1-conditional rows of §10.4 and their count "16 of 23".
6. In §8.100–§8.105: one quotation is reversed (D-01: "does create" for "does not create"). One plain-language line contradicts its own R3 note (D-02). F-C1 to F-C12 and D-03 to D-14 are normal defects of fact, pointer, count or derivation, each verified or sampled against the sources.
7. Three-digit numbering is sound. Every citation of §8.100–§8.105 I traced names an existing section; zero-padded forms ("§9.05", "§9.07") are a notation slip (I-03).
8. Coverage gaps: the SEQ order of §8.103–§8.105 was assigned to no reviewer. The quote check established nothing for Ch. 10. TODO.md lies outside R2. The decision record and CH10-PROPOSAL were on no list, so decision texts are checked only as the copy quotes them.
9. Every reviewer access deviation is minor (help calls, tool-source reads, one crashed scope, one failed path). In my sample of 8 findings, none rests on a read outside the copy and the allowed corpus.
10. My R1 output on the copy is byte-identical to reviewer A's (sha256 41d4701c…043; 0 flags).

**Process independence:** I am a separate session that wrote none of the reviewed work and none of A–D. This is process independence, not personal independence.

## 1. Findings

Severity: H = high, N = normal. Verdicts: C = confirmed, M = modified. The last three columns (window · source · settlement or draft) are filled for §8.100–§8.105 only (task 6).

| ID(s) | Verdict | Sev. | Ch. | Evidence (verified unless marked) | Correction | Window | Source | Settl./draft |
|---|---|---|---|---|---|---|---|---|
| A-01 (= B out-of-scope 3) | C | H | 10 | §10.2 L115 says "the relocation test makes it necessary there: g1 could sit in an application …". That is self-contradictory. §9.18 L273 and §8.90 L51 say the test permits an application placement, and that kernel placement is U2's decision, "not a reason the book derives" | A's wording: placed by U2, not derived; the relocation test permits an application placement | | | |
| A-02 | C | H (normative) | 10 | T10-20 asks for a class ("core with the display, or supporting"). U2's options are placements: (a) kernel, (b) application under one version (§9.19.9). §10.1 L13 says "No class below is decided by kernel or application membership". §9.18 L210: "the classification is T10-20's" | A question to the Principal (task 9); no edit before the answer | | | |
| A-03 | C | N | 10 | §10.3 L115: the enforcer column reads "g1's class, in the kernel" (a placement, not a candidate plus an operation, §10.3.2 L61). §10.3 L176: "Q4's answer is the kernel's" stands in an owner list, while row B gives the owner as 2 | A's wording (owner 2; computed by the kernel's step, U2) | | | |
| A-04 + B-17 (merged; = B out-of-scope 1) | C (A-04); M (B-17) | N | 10 | §10.3 L114 still reads "who applies g1 is not placed (§9.18 L273)", but L273 now says "placed in the kernel … U2". B-17: PL-14 (§10.5 L277) grounds "no context enforces row C" on "§10.3.2's own definition". §10.3.2 defines enforcers over candidates, and row C names candidate 3. The statement is true because 3 is a role (§10.3.6), not by that definition. "Contradiction" overstated → modified to a wrong ground plus a forward use (SEQ) | One restatement of L114: "applied by the kernel (U2); the display duty has no enforcing context (PL-14, §10.5, forward)". Re-ground PL-14 on §10.3.6 / §10.4.0 (3 is a role); annotate row C | | | |
| A-05 | C | N | 10 | G2 and G4 decided (§9.18 L77, L79). §10.2 G2 row L215, G4 row L217, the G4 line at §10.3 L118, and the "producer authorisation (G4)" line in §10.2 are not annotated | Annotate. Carry G4 into B-01's re-marking of R5 | | | |
| A-06 | C | N | 10 | §10.2 L220 (G7) still lists Q4's grade as open; §9.18 L82 and §10.2 L114 say it is decided (U2) | Annotate | | | |
| A-07 | C | N | 10 | §10.3 L113: "the correcting act is not a kind of D (G1)". Per §9.18 / THEORY-MAP L453, correction is standing's annulment | A's wording | | | |
| A-08 | C | N | 10 | §10.3 L105's note names only register and link. T9-21 and U6 list establishment among the five kinds outside D too. So all three of the row's O1 kinds are outside D, and the trivial-solution check holds through O4 (root) only | A's wording | | | |
| A-09 | C | N | 10 | TODO T10-19 and 10.99 L25 say "§10.3.4 L292–293". Those lines are in §10.3.6 (which starts at L246); SCOPE §2.1 repeats the error. §10.2 L5 and §10.4 L18 (and §10.4 L3, found here) keep the unqualified "not contexts" | Readdress to §10.3.6; carry the qualification to §10.2 L5, §10.4 L3, L18 | | | |
| A-10 | C | N | 10 | §10.3 L157 calls CON-0141 "a method rule" owned by 6. The register (and §8.8 C1, L31) define a reader/fold lemma over derived values, a kernel candidate (BOOK-OUTLINE L150). Row 6 gets "not a context" while row 4, on the same inputs, gets "not admitted today" (L290 vs L292) | Re-run R-b for CON-0141; then re-derive candidate 6's result | | | |
| A-11 | M | N | 10 | §10.3 L29 and L172 exclude §8.17 C5 (CON-0237) as a review/governance rule. Its content is a rule of the record's merge (§8.17 L77, §9.16 R2). Modified: that content already enters the inventory through law 30 (§10.3.7 L334) and K1 (row G), so nothing is lost; the exclusion's reason is wrong | Restate the exclusion: "its content enters as law 30 (§10.3.7) and K1"; no move needed | | | |
| A-12 (= B out-of-scope 2) | C | N | 10 | §10.3 L7 ("five, mutually exclusive") and L237 ("five exclusive relations") are against L63 and 10.00 L9 ("not mutually exclusive") | Annotate as superseded | | | |
| A-13 | C | N | 10 | The State of TODO T10-21 begins "Resolved", while (5) is open (last column) | A's wording | | | |
| A-14 | C | N | 10 | The earlier State and decision-criterion texts of T10-19 and T10-20 are not shown. 10.99 L25's "verbatim removed" column holds no text. SCOPE §2.1 requires each earlier wording kept. The section lines (§10.2 L114, L115; §10.3 L115, L176) do keep theirs | Add the removed TODO text | | | |
| A-15 | C | N | 10 | Row C at §10.3 L124 and L127 still carries bare G1 labels (G1 closed) | Annotate | | | |
| A-16 | C | N | 10 | §10.3 L231 "only standing and the rules about rules are separate areas today" is not annotated; L298 and L315 supersede it | Annotate | | | |
| A-17 | C | N | 10 | §10.1 row (L28): "read out of order" for S0365 and S0392, now read in order (§8.89; §8.102) | Forward note | | | |
| B-01 + B-11 (merged) | C | H | 10 | §10.4 L6 says G1-conditional rows are "to be re-marked at the next review"; that has not been done. R4, R7 and R17 are conditional on G1 alone (L57, L60, L70). §10.4.4 L126 still says "the G1 theory turn" and omits R11, R20 and R21. 10.00 L10 and SUMMARY L31 repeat "16 of 23" without the caveat | Re-mark each row (also on G4, A-05); recount in every inbound line | | | |
| B-02 | C | H | 10 | §10.4.3 L86 reads the kernel as "what the boundary test placed there (§9.6–§9.17)". Since U2, g1 is kernel by decision (§9.19.9). If g1 is kernel, R11 (N → W, criteria) could be a kernel dependence on a non-kernel area. Unproven whether the result changes | Extend the kernel reading to the U2 placement and re-check R4, R5, R7, R11; or keep the boundary with a stated reason | | | |
| B-03 | C | H | 10 | The narrowed law (L99) excludes every input not accepted by a specified step. R12 (L65) and L93 show answers depending on covers, θ and the link with no such step, so read as a description the law is false today. The falsifier (L100) tests only "unrecorded application state". The two do not match | Choose a descriptive or a normative reading; match the falsifier; restate the result line and T10-11 | | | |
| B-04 + I-01 | C (+ new) | N | 10 | §10.4 L110: "§9.16 R6 recommends a reading (c)". R6's heading says "adopted by the Principal … Adopt R6's recommendation per law" (§9.16 R6 header). **New (I-01):** the same staleness at §10.3 L170 ("F36 recommended (c) reading (i)"), which A did not flag | Change to "adopted" at both sites; re-weigh law 18 | | | |
| B-05 | C | N | 10 | §10.5 L205: "no links, evidence or absence entries" in D. Evidence kinds are admitted (D_W, §9.20 L46) | Qualify as D_S, or restate | | | |
| B-06 | C | N | 10 | §10.5 L246 says "needed … (CON-0173; §9.07 L86)". L86 now carries the inference-gap note ("Step 4 is a sketch under that unstated premise"), and lists no identity link | Qualify; drop "each" for the link | | | |
| B-07 | M | N | 10 | PL-4 L89 restates §9.16 L93 faithfully (L93 equates "the fields CE* reads" with "D's required fields other than recorder and status"). The defect is L93's equation (Ch. 9, out of scope), not PL-4 | Restate PL-4 only after §9.16 R2 is restated; route an R3 note to §9.16 | | | |
| B-08 | C | N | 10 | §10.5 L75 omits removal (v8, the Principal, §9.13 L47) and is silent on observe (v17 added, v18 withdrawn, L49). So "every kind added" is over-quantified | B's wording | | | |
| B-09 | C | N | 10 | §10.5 L143 points to L69 for "closed under prefixes"; the phrase is at §9.13 L62 only | Drop the L69 pointer | | | |
| B-10 | C | N | 10 | §10.4 L23–24 "every row … typed by one of" the five types. R0 is untyped (L52, "none of the five types fits") and R14 is "↔ shared" (L67) | Name the exceptions | | | |
| B-12 | C | N | 10 | S0196 L537 is "AR-1 → AC-1 (criteria)". L540 is a cross-edge, "translation obligation unassigned", which parallels R18, not R11 | B's wording | | | |
| B-13 | C (pointer); scope part accepted on report | N | 10 | S0036 L169 is a table separator; the ACL row is L170 | L170; state the scope (Int) | | | |
| B-14 | C | N | 10 | §10.4.1 L35 counts S0069 R-8 *against* the direction law; §6.6 C8 (L73) counts S0069 L53–54 as "one measured instance" *for* it. The conflict is unrecorded | Record it; R3 note to §6.6 C8 | | | |
| B-15 | C | N | 10 | §10.5 L3 ("pending G1") and L354 ("after gap G1") carry no note | Annotate | | | |
| B-16 | accepted on report | N | 10 | Missing pointers to U2, T10-21 (5), U6/T9-21 in §10.5 | Add | | | |
| B-18 | C (sampled: "§9.15 L1–L11" at R1 L54) | N | 10 | "L<n>" is used both for lines and for legitimacy assumptions; "limit (i)/(ii)" is ambiguous | Disambiguate | | | |
| B-19 (+ 10.00 L11, B out-of-scope 4) | C | N | 10 / SUMMARY | (a) SUMMARY L36 and **10.00 L11** (in A's scope, missed by A): "standing's language PL-0–PL-14", against the §10.5.2 split (L52–L98). (b) L9 omits "contested" (10.01 L45). (c) L11 omits rows 4–13. (d) L42 "prepared, not run" and L48 "Stage 1 not prepared" come from index T1, not from Ch. 10 or its TODO, so they are added content. (e) The "Open" list is a selection without saying so. (f) L31 inherits B-01. SUMMARY L27 inherits A-03 | Restate from the sections; restate 10.00 L11 | | | |
| F-C1 | C | N | 8 | S0380 L98 already lists `5928b9f9`. S0386 L105 gives a new ground ("Identity bar added"). So §8.101 L65 and L71 "adds a bar" is wrong | C's wording; list the S0386 slip as a source defect | C107 ✓ | S0386 (S0380–S0382) ✓ | settlement ✓ |
| F-C2 | C | N | 8 | S0387 has 175 lines. L70 cites `next-actor-orchestration.php:365`. Stale pointer, not missing content (task 3) | C's wording | C107 ✓ | S0387 ✓ | settlement ✓ |
| F-C3 | C | N | 8 | S0383 L1099 "cleanest result" concerns the separation of lenses, DDD and kernel, not convergence | Drop or replace with L1103 | C107 ✓ | S0383 ✓ | settlement ✓ (marker "(WA W-02)") |
| F-C4 | accepted on report (the S0363 grep not repeated) | N | 8 | §8.101 L113 "not checked" can be decided: a misattribution in S0388 | C's wording, with the limit | C107 ✓ | S0388 (S0363, S0361) ✓ | settlement ✓ |
| F-C5 | C | N | 8 | S0392 L1304 names two options; S0364 L11–14 list three (the third is "A subordinate act") | C's wording | C108 ✓ | S0392, S0364 ✓ | settlement ✓ |
| F-C6 | C | N | 8 | CON-0179's register meaning includes "corroborated by the producer's self-disclosure … one case". §8.100 L45 uses it for S0380, where no self-disclosure exists | C's wording | C106 ✓ | S0380 ✓ | settlement ✓ |
| F-C7 | M | N | 8 | S0378 L46: "Before that verification can be commissioned, finding F-3 must be settled". S0382 L26: "Why this appointment is now possible". Step (8) of §8.100 L49 already gives the false assurance; what is missing is only that the attribution *opened* the commission | Add step (6′); extend (8) only | C106 ✓ | S0380 (S0378, S0382) ✓ | settlement ✓ ("restated at review, W-09") |
| F-C8 | accepted on report | N | 8 | §8.102 L46 "the others restate C1–C2" leaves out facts 4, 5 and 7 | C's wording | C108 ✓ | S0392 ✓ | settlement ✓ |
| F-C9 | C | N | 8 | 08.00 L122 separates only S0361's and S0376's F-lists; row L116's "F-1" is unqualified | C's wording | C107 ✓ (also C106, C108) | S0390, S0378, S0379, S0392 ✓ | settlement (index) ✓ |
| F-C10 (headers) + D-14 + F-C10 (index part) | C, merged | N | 8 | Headers' "uses" lists are incomplete. 08.00 L122's forward-R3 list stops at "§8.82–§8.98 … ← §8.98" (verified) | Complete the headers; extend L122 with a §8.100–§8.105 block | C106–C111 ✓ | none ✓ | headers: draft ✓; index note: settlement ✓ |
| F-C11 | accepted on report | N | 8 | CON-0242 is a loose fit for S0391 L58 | State as an Int analogy, or rehome | C107 ✓ | S0391, S0390 ✓ | settlement ✓ |
| F-C12 | accepted on report | N | 8 | S0380 L81's narrower novelty claim is not answered | Add a sentence | C106 ✓ | S0380 ✓ | settlement ✓ |
| D-01 | C | H | 8 | S0395 L417: "A brainstorming lens does **not** create domain authority." §8.103 L40 quotes "does create" in brackets; L46 has it right | Restore "not" | C109 ✓ | S0395 ✓ | settlement ✓ |
| D-02 | C | H | 8 | §8.103 L22: "disagrees with the adviser's own text". The R3 note at L59 says "The two 'disagreements' are not borne out" | R3 note at L22, or restate | C109 (R3 from C110) ✓ | S0396 (read via S0397) ✓ | draft by SCOPE's definition (unmarked) ✓; the cause is an R3 not carried at C110 settlement |
| D-03 | C | N | 8 | S0393 L215 places Ziran in the "Adjudication layer"; S0394 L1 says "primarily in the observation layer". §8.103 L31 says "every lens" | D's wording | C109 ✓ | S0393, S0394 ✓ | settlement ✓ |
| D-04 | accepted on report | N | 8 | §8.103 L56 "no place" vs §8.104 C3 "not built, not excluded" | R3 note | C109 ✓ | S0396 ✓ | settlement ✓ |
| D-05 | C | N | 8 | S0396 L489–503: identity, boundary, context, authority, justification, reasoner, knowledge/action, naming = 8 rows; §8.104 L38 and L85 say "seven" | "Eight", at L38 and L85 | C110 ✓ | S0397 (matrix = S0396) ✓ | settlement ✓ |
| D-06 | C | N | 8 | S0397 L2241 is the question and L2243 is "Relevant to"; the citations are off by 2 | Correct the three references | C110 ✓ | S0397 ✓ | settlement ✓ |
| D-07 (linked to B-06) | C | N | 8 | §8.104 L64 cites CON-0173 for "no accountable supplier". The register meaning is only "a common, versioned value" (and does not cover the link) | Cite §9.13 L327 (source open) for the supplier point | C110 ✓ | none ✓ | settlement ✓ |
| D-08 | C | N | 8 | S0400 L23 reads only "A Claude restart …". No compaction or pasted turn appears in S0400 or S0401 (grep) | Delete, or source it | C111 ✓ | S0400 ✓ (S0401 checked) | settlement ✓ |
| D-09 | C | N | 8 | §9.19 L264: T7 is stated "If s₀ does not meet Req_O …"; §9.19 L352 scopes T1 to "a fixed finite pool". §8.105 L30 uses ativyāpti in two senses and drops both premises | D's wording | C111 ✓ | S0398 ✓ | settlement ✓ |
| D-10 | C | N | 8 | S0398 has nine numbered families (headings L21–L315) and a separate lens (L400 on). S0399 L636: "And then one additional principle". Neither is a source defect | Withdraw both | C111 ✓ | S0398, S0399 ✓ | settlement ✓ |
| D-11 | C | N | 8 | S0399 L461 is "L4 — Governance certification" (the book cites L462). W9 is at L640; the rule quoted is at L531 | Correct | C111 ✓ | S0399 ✓ | settlement ✓ |
| D-12 | C | N | 8 SUMMARY / map | §8.104 L25 says "Several"; SUMMARY L49 and map L278 say "mostly" | D's wording | metadata: fields not applicable | | |
| D-13 (= A out-of-scope 1) | C | N | map | THEORY-MAP L454 still says "S0377; next: S0378", against L455's "Reading position S0402". (SCOPE names L455–456 for "position"; the position line is L454) | Update or supersede | n/a | | |
| I-02 (new) | new | N | 10 | §10.3 L322 attaches the T10-19 qualification to "3, 6, 7 and 8". L292–293 and TODO T10-19 restrict it to 6, 7 and 8; 3 is a role, not an application | Limit L322 to 6–8 | | | |
| I-03 (new; D out-of-scope) | new | N | 8 (in-scope row L115) | Index row 8.100 writes "§9.05"; §10.5 L246 and §10.4 L65 write "§9.07". The headings are "§9.5" and "§9.7" (book_search) | Use the heading form | C106 | none | settlement (index) |
| I-04 (new) | new | N | 10 | 10.00 L10 states "23 relationships, 16 conditional" and "a narrowed law … untestable" without B-01's or B-03's caveats. It is an inbound line of both, listed in R3 below | With B-01, B-03 | | | |

Out-of-scope one-liners (A, B, C, D) are kept as reported and not reviewed. Exception: B's four §10.x one-liners are merged above, since they fall in A's scope.

## 2. R3, every line (task 2)
Tool: `inbound_citations.py --root <copy>` (scratch r3.txt). It returns home "—" and 0 lines for Ch. 10 sub-sections and for TODO IDs. For those I searched the copy with `book_search.py` (which does not cover TODO.md or SUMMARIES) and with `grep -n` on the named files.

| Correction | Inbound line | Disposition |
|---|---|---|
| A-01 | §9.18 L273; §8.90 L51 | stay true (they already say this) |
| A-01 | 10.SUMMARY L21; 10.99 L25 | stay true |
| A-02 | §9.18 L192, L210 ("the classification is T10-20's") | different change, per the Principal's answer: point to the decision, or to T10-21 (5) |
| A-02 | §10.1 row 2 (L55), L85–86 | not affected if the class stays open; same change if decided |
| A-02 | TODO T10-21 (5); 10.99 L26; 10.SUMMARY L43, L47 | same change if the class question moves |
| A-02 | 10.99 L13, L19 (history) | not affected |
| A-03 | 10.SUMMARY L27 | same change |
| A-03 | §10.2 L114; 10.99 L25 | stay true |
| A-04 + B-17 | §10.5 L277 (PL-14) | different change (re-ground) |
| A-04 + B-17 | §10.5 L294, L339 | stay true |
| A-04 + B-17 | 10.99 L24 (history) | not affected |
| A-05 | §10.4 L58 (R5 "G1, G4"); L126 ("R5 also needs G4") | same change (with B-01) |
| A-05 | §9.18 L77, L79 | stay true |
| A-06 | §9.18 L82 ("§10.2 G7 row") | stays true after the change |
| A-07 | §10.3 L339 | stays true |
| A-08 | §10.3 L26; §10.4 L155; §10.5 L117; 10.SUMMARY L27 | stay true |
| A-09 | TODO T10-19 (State, Where); 10.99 L25 | same change (readdress) |
| A-09 | §10.2 L5; §10.4 L3 (found here); §10.4 L18 | different change (add the qualification) |
| A-09 | 10.SUMMARY L28, L46 | stay true |
| A-09 | SCOPE §2.1 | not book text |
| A-10 | §10.4 L73 (R20 "owns non-consultation (CON-0141)"); §10.3 L218, L292 | same change |
| A-10 | §10.3 L25, L242; BOOK-OUTLINE L150; THEORY-MAP L342 | stay true |
| A-11 | §10.3 L29, L172 | same change |
| A-11 | 08.18 L17, 08.21 L21, 09.08 L104 (A's tool hits; not reopened) | not affected |
| A-12 | §10.3 L63; 10.00 L9; 10.SUMMARY L24 | stay true |
| A-13 | 10.SUMMARY L43 | stays true |
| A-14, A-15, A-16, A-17, A-07 | no inbound line found | — |
| B-01 | 10.00 L10; §10.4 L132; 10.SUMMARY L31; THEORY-MAP L451 ("16 conditional") | same change |
| B-01 | §10.4 L166 | same change for its current-count clause; history kept |
| B-01 | 10.99 L11, L14 | not affected (history) |
| B-02 | §10.4 L80–L119 | the change itself |
| B-02 | §10.5 L246–248 | different change (Q4's inputs, or a reason they do not belong) |
| B-02 | TODO T10-11 | same change if the result changes |
| B-02 | §9.20 L63 | out of scope; flagged, not judged |
| B-03 | 10.00 L10; TODO L16; §10.4 L117; 10.SUMMARY L32; THEORY-MAP L451 | same change |
| B-03 | §10.4 L162 (the draft's withdrawn law) | not affected |
| B-03 | book_search hits 08.91, 09.20 L63, L119, L142, L168–169; §10.4 L129, L148; §10.5 L247, L341 | cite T10-11 as open; not reopened by me; not affected if T10-11 stays open |
| B-04 + I-01 | §10.4 L118; §10.3 L170; TODO T10-12 | same change |
| B-05 | §10.5 L315 | same change |
| B-06 + D-07 | §10.5 L318 | same change |
| B-06 + D-07 | §10.4 L65 (R12's CON-0173 gloss lists covers, θ, root, not the link) | stays true |
| B-06 + D-07 | §8.104 L64 (cites §10.5 L246 and CON-0173) | different change (D-07) |
| B-06 + D-07 | §9.20 L63 | out of scope; needs an R3 note |
| B-07 | §10.5 L310 | same change, after §9.16 R2 |
| B-07 | 10.99 L17 | not affected |
| B-08 | §10.5 L306 | same change |
| B-12 | §10.4 L64 | same change |
| B-14 | §6.6 L73 | different change (R3 note recording the disagreement) |
| B-19 | metadata; nothing cites it | — |
| B-09, B-10, B-13, B-15, B-16, B-18 | none found | — |
| F-C1 | §8.100 L68 (step-0 R3 note); §8.97 L52 | stay true |
| F-C1 | §8.105 L86 | not affected |
| F-C3 | §8.102 L3, L67; §8.103 L3, L14, L31, L32, L35, L77; §8.105 L3, L6, L34 | not affected (they cite the one-voice point, not L1099) |
| F-C4 | §8.86 (C2, .1, .5), §8.87.3 | stay true |
| F-C5 | §8.88 L5; index row 8.102; map L276 | stay true |
| F-C6 | 08.82 L52; 08.98 L58; 08.101 L20, L66, L77; CON-0179 cites in §8.10, §8.12, §8.67, §8.69, §10.1, §10.2 | stay true |
| F-C6 | register CON-0179 "one case" | different change, at its rotation (out of scope) |
| F-C7 | index L115; map L274 | stay true (may be extended) |
| F-C8 | §8.104 L90 | stays true |
| F-C2, F-C9–F-C12 | none (index-only or no inbound) | — |
| D-01 | the 13 section-only lines: 08.99 L141–142; 08.104 L3, L14, L18, L25, L37, L54, L55, L63, L80; 08.105 L3; map L277 | not affected (none repeats the quotation) |
| D-02 | map L277; index L118; summary L45 | stay true |
| D-02 | §8.103 L73 | already noted |
| D-03 | map L277; index L118; summary L42 | stay true |
| D-04 | §8.104 L63 | stays true |
| D-05 | §8.104 L85 | same change |
| D-05 | 08.103 L59, L73; 08.105 L3, L15, L68; map L277 | not affected |
| D-09 | map L279 ("T7 as ativyāpti"); index L120; summary L53 | same change |
| D-09 | 08.99 L143; BOOK-OUTLINE L209 | not affected |
| D-12 | map L278 | same change ("several") |
| I-02 | 10.SUMMARY L28 (says 6–8) | stays true |
| D-06, D-08, D-10, D-11, D-13, I-03 | none | — |

## 3. References to missing claims (task 3)
- **F-C2** ("L365" in S0387): the pointer is stale, nothing is missing. S0387 has 175 lines, and L70 cites the code site `:365`.
- **B-09** (§9.13 L69): the pointer is stale. The phrase was moved to L62 at an earlier restatement.
- **A-09** ("§10.3.4 L292–293"): the pointer names the wrong section. The content exists at §10.3.6.
- **D-06, D-11, B-13** (lines off by 1–2): stale pointers. The content exists at the corrected lines.
- **D-08**: content is missing in the source. Neither S0400 nor S0401 contains the claim, so this is a claim without a source, not a pointer slip.
- **F-C3**: the line exists, but its meaning is misattributed (separation, not convergence).

## 4. Conflicts between reviewers (task 4)
| Pair | Resolution | Reason |
|---|---|---|
| A-04 vs B-17 (both on §10.3 L114) | one restatement of L114 (§1) | A fixes the stale "not placed"; B fixes the premise and the forward use. They touch the same sentence and are compatible |
| A-03 vs B-02 (U2's reach) | both stand | A keeps owner 2 and names the kernel only as placement; B extends §10.4.3's kernel to the same placement. Both read U2 as placement only, consistent with A-02 |
| A-05 vs B-01 (R5) | B-01's re-marking must also apply G4 decided | R5 is marked "G1, G4". A shows G4 is decided, so R5 may become unconditional. Re-marking on G1 alone would leave it stale |
| B-06 vs D-07 (CON-0173) | merged; one fix at §10.5 L246 plus D-07's fix at §8.104 L64 | §8.104 L64 cites §10.5 L246 itself. B's R3 list missed L64 |
| B-07 vs PL-4 (B's own correction) | modified: fix at §9.16 first | PL-4 restates L93 faithfully |
| B-17's "contradiction" | modified to a wrong ground | 3 is not a context, so "no context enforces" is true, but not by §10.3.2 |
| F-C10 vs D-14 (index note L122) | merged | the same missing §8.100–§8.105 block |
| D-12 vs map L278 | merged | the same word |
| A-01 vs B out-of-scope; A-12, A-04 vs B out-of-scope; 10.00 L11 vs B-19(a) | merged | duplicates |
| F-C1 vs D ("did not check S0386") | no conflict | C checked; its sha256 matches §8.101 L8 (C's report) |

## 5. Rotated-chapter defects (Ch. 10, task 5)
- **Reviewer A:** A-01 (H), A-02 (H, normative), A-03, A-04, A-05, A-06, A-07, A-08, A-09, A-10, A-11 (modified), A-12, A-13, A-14, A-15, A-16, A-17.
- **Reviewer B:** B-01 (H, with B-11), B-02 (H), B-03 (H), B-04, B-05, B-06, B-07 (modified), B-08, B-09, B-10, B-12, B-13, B-14, B-15, B-16, B-17 (modified), B-18, B-19 (with 10.00 L11).
- **New, found here:** I-01 (§10.3 L170, F36 "recommended"); I-02 (§10.3 L322 extends T10-19 to candidate 3); I-04 (10.00 L10 inbound of B-01 and B-03); 10.00 L11 (B out-of-scope, confirmed in scope).

## 6. Per-finding fields (task 6)
These are in the findings table. All C and D windows and sources are confirmed.
- F-C7 adds S0378 and S0382 beside S0380.
- D-02 is "draft" by SCOPE's definition. Its cause is a settlement omission (the C110 R3 was not carried to L22).
- **Classification note:** many findings are classed "settlement" on the strength of "(W-nn)" / "(WA W-nn)" tags, which SCOPE §2.7 does not list by name (it names "(Restated at review: …)", R3 notes and passages added at settlement). The Professor should confirm that the W-tag counts as a settlement marker before compiling.

## 7. Coverage gaps (task 7; not filled)
- **SEQ order of Ch. 8 from §8.100:** C checked §8.100–§8.102. §8.103–§8.105 were assigned to no reviewer (SCOPE §2.6 assigns only Ch. 10's SEQ order, to B); D noted one forward use ("hidden reasoner", §8.103 L51/L56 before §8.104 L61) only in passing.
- **Quote check on Ch. 10:** 0/0 found. Per B the tool's pattern does not match the chapter's citation form, so this establishes nothing.
- **R2 on TODO.md:** not covered (not a section ID).
- **Decision texts:** not checked beyond the copy's quotations. The decision record and `CH10-PROPOSAL.md` were on no list. "Decision record addendum to be recorded by the Governance Engineer" (§10.2 L5, §10.3 L319, §9.18 L76) is unchecked.
- **Earlier TODO wording (A-14):** cannot be verified; history is barred.
- **Unchecked statements:** §10.5.4 "CON-0188 not yet reviewed after correction" (B). S0033, S0034, S0149, S0187 pointers in §10.4–§10.5 (B, budget).
- **Source checks not done:** S0396 = S0397 L3196–3835 identity (D; comparison tools barred); the duplicate `kernel/110305` (C); S0383 and S0390 not re-read in full (C).
- **SCOPE's own line numbers:** "§10.3.4 L292–293" should be §10.3.6; "position L455–456": the stale position line is L454.
- **THEORY-MAP L451–452** (Ch. 10 turn lines) are outside the named map range but carry B-01 and B-03 content (listed in R3 above).
- **Working-instructions index vs decision record:** no reviewer reported a difference, and none could open the record. I found no internal inconsistency between the index (T1, W4) and SCOPE.

## 8. Access deviations (task 8; weighed, not re-run)
- **All four reviewers** called `book_search.py --help` without `--root`. The copy's tool searches the copy by design. Minor (A6).
- **All four** read lane tool sources (`tmp/tools/*.py`, by grep, sed or head). These are not book content, and no finding rests on them. Minor.
- **B:** `undefined_terms.py` crashed once on a wrong `--scope` (still with `--root`). No effect.
- **C:** `corpus_quote_check.py --help` failed ("no such file"); `undefined_terms.py --root <copy> --help` printed the copy's whole-book list. Both on the copy. Minor.
- **D:** ran `test_section_numbers.py`, which takes no `--root`; D reports "temporary book only". Its numbering conclusion also stands on D's own grep and on my R3 output's numeric order. One failed read at a wrong copy path (no content). Minor.
- **Reads beyond the copy:** no reviewer reports reading outside the copy other than allowed corpus sources, the SCOPE §2.3 inventories (C, D) and the two methodology files. None reports an item-5 file, `00-access/` or git.
- **Sample checked on the copy:** A-01, A-09, B-01, B-09, F-C1, F-C2, D-01, D-05. Each rests on copy text plus allowed sources; none rests on a read outside the copy.

## 9. Decisions in scope (task 9)
**U2**, as the copy records it (§9.19.9): "The Principal rules on U2 (G7): Q4's grade is (a) g1's class, kernel". The options as asked were "(a) yes, g1 in the kernel; (b) yes, g1 application policy under one shared version; …", with "No recommendation".
- *What it decides:* Q4's grade is g1's class, and g1's placement is the kernel.
- *Consequences that follow:* 1 (in part), 3, and the reference-definition part of 2.
- *Treated as decided beyond it:*
  - (i) that placement is derived by the relocation test. This is consequence 4, §10.2 L115, and needs an edit (A-01);
  - (ii) that the kernel is an enforcer or owner. These are consequences 1–2, L115 and L176, and need an edit (A-03);
  - (iii) that the subdomain class of g1 is answered (T10-20). This needs a question.

**U6**, as the copy records it: "O1 is (a) one requirement per kind, D clauses to follow". The addendum reads "D clauses for the five kinds outside D to follow in a later authorised turn (TODO T9-21)".
- *What T9-21 records:* "D clauses for O1's kinds outside D (registration, link, retirement, establishment, declassification)", Open.
- *What it decides:* the per-kind cut, and that the clauses are not yet written.
- *Consequence 6* (§10.3 L26, L105) follows, but omits establishment (A-08, an edit).
- *Nothing is treated as decided beyond it.*

**Closure of T10-20**, as the copy records it: "T10-20 closes as answered by U2. Whether the class's display is core published language or supporting is decided with T10-21 before part 2."
- *What it decides:* T10-20 is closed, and the display question moves to T10-21 (5).
- *Treated as decided beyond it:* that g1's class (core with the display, or supporting) is answered by a placement decision. §10.1 L13 says no class is decided by kernel membership, and the copy names no class.
- *The question for the Principal (one sentence):* Does the closure of T10-20 decide the subdomain class of the rule g1 — (a) core, with the display; (b) supporting; or (c) not decided, with "the class of g1" added to T10-21 (5) beside the display question?

**Step 2 (T10-19)**, as the copy records it: the approval "Apply the Ch. 10 consequences 1–6 and plan steps 1–2 … for review at the next consolidation". The proposal's own text is not on my list.
- *What follows:* "not a context" relative to the inventory, for candidates 6, 7 and 8.
- *Beyond it:* L322 extends the qualification to 3 (I-02, an edit).
- *Under-applied:* §10.2 L5 and §10.4 L3, L18 (A-09, an edit).
- *No question needed.*

**T9-21** is cited, not decided, by these changes. The copy uses it correctly except for A-08.

## Files opened (all read-only)
- **Consolidation folder:** INTEGRATION-COMMISSION.md, REVIEWER-NOTE.md, SCOPE.md, REPORT-A.md, REPORT-B.md, REPORT-C.md, REPORT-D.md.
- **Methodology:** 03-methodology/REVIEWER-INSTRUCTIONS.md, 03-methodology/WORKING-INSTRUCTIONS-IN-FORCE.md.
- **Copy (`BOOK-UNDER-REVIEW/06-theory/manuscript/…`), read whole:** chapter_10/10.00-index.md, chapter_10/10.99-change-log.md, chapter_10/TODO.md, SUMMARIES/10.SUMMARY.md.
- **Copy, read by line range or grep:**
  - Ch. 10: 10.01-subdomains.md (L10–15, 26–30, 34–60, 84–88; grep); 10.02-bounded-contexts.md (L1–12, 95–120, 205–230; grep); 10.03-ownership-of-invariants.md (L1–30, 55–64, 100–130, 150–178, 214–246, 280–300, 315–345); 10.04-context-map.md (L1–40, 50–168); 10.05-published-language.md (L3, 28, 75, 89, 117, 143, 205, 246, 277, 354; grep of headings).
  - Ch. 8: 08.00-index.md (L113–124; grep); 08.08-… (L29–33); 08.17-… (L69–77); 08.90-… (L49–52); 08.100-… (L45, L49); 08.101-… (L43, 65, 71, 86, 113); 08.102-… (L5, L46); 08.103-… (L22, 31, 40, 46, 56, 59, 73); 08.104-… (L25, 38, 46, 64, 85); 08.105-… (L8, 30, 53, 73, 77, 78).
  - Ch. 9: 09.07-… (L85–87); 09.13-the-evaluator.md (L33–49, 62, 69); 09.16-reassessment.md (L91–95, 355–368); 09.18-evidence-in-the-record.md (L74–83, 190–193, 205–212, 270–275); 09.19-… (L264, 352, 385–405); TODO.md (grep T9-21).
  - Ch. 6: 06.06-agent-harnesses-linked-not-copied.md (L72–74).
  - Other: BOOK-OUTLINE.md (L150; grep).
- **Copy, `BOOK-UNDER-REVIEW/06-theory/`:** THEORY-MAP.md (L274–279, 451–456; grep); CONCEPT-REGISTER.tsv (header; rows CON-0141, 0173, 0179, 0237, 0242).
- **Allowed list:** CORPUS-ALLOWED.tsv (header; grep by S-ID).
- **Corpus, from the allowed list (single lines or ranges):** S0036 (L169–170); S0069 (L52–54); S0196 (L537, 540); S0378 (L46); S0380 (L98); S0382 (L26); S0383 (L1099); S0386 (L105); S0387 (line count, L70); S0392 (L1304); S0364 (L11–14); S0393 (L215, 375); S0394 (L1); S0395 (L417); S0396 (L489–503); S0397 (L2241, 2243, 2249, 2257); S0398 (headings, L19, L404); S0399 (L461–462, 531, 623, 636, 640; path as listed in the allowed list, not reproduced under the outside-authors rule); S0400 (L20–25; grep); S0401 (grep).
- **Not opened:** nothing in `00-access/`, no working chapter, map, register, outline or summaries, no 02-FILES.jsonl, no inventories, no decision record or CH10-PROPOSAL, no git.

## Commands and searches run
- `test -e` and `wc -l` on named copy files, one by one; `mkdir -p` of my scratch directory.
- `book_search.py` (the copy's), searches: Ch. 8 headings `^# §8\.10[0-5]…`; Ch. 9 and Ch. 8 headings (two patterns); `^# (9\.11|9\.15|9\.06|9\.6|9\.10)[ .]`; `^# (6\.6|6\.06)[ .]`; "T10-20" "T10-19" "T10-11" → bs1.txt.
- `register_home_check.py --root <copy>` → r1.txt (0 flags; sha256 41d4701c…f043).
- `inbound_citations.py --root <copy>` with targets §8.103 C2, §8.103 C1, §8.104 C1–C3, §8.105 C1, §8.105 C3, §8.101 C3, C1, C5, C8, §8.100 C2, §8.102 C3, §8.102, §10.4.3, §10.2, §10.3, §10.4, §10.5, T10-11, T10-19, T10-20, T10-21, CON-0141, CON-0173, CON-0179 → r3.txt (sha256 d307c3c3…f8c3); summarised with grep/awk.
- `grep -n` on named copy files: 10.02 (relocation/G-rows); 10.01 (row-group lines); 10.05 (headings); 10.SUMMARY, TODO, BOOK-OUTLINE, THEORY-MAP, 09 TODO (inbound strings); 09 TODO (T9-21); 08.00-index (F-n).
- `grep -P` on CORPUS-ALLOWED.tsv by S-ID (three calls).
- `sed -n` / `awk` line reads as listed under Files opened; `grep -n` of S0398 headings; `grep -in` compact/paste/opening on S0400, S0401.
- `sha256sum` of r1.txt, r3.txt, bs1.txt.
- No globs, no recursive search, no directory listing, no git, no diff or cmp, no edits, nothing copied, nothing sent outside.

Scratch outputs: /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/cons5-i/ (r1.txt, r3.txt, bs1.txt).

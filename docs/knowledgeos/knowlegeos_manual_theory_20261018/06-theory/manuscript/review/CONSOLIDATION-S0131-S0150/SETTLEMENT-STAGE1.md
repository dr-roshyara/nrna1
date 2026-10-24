# Consolidation S0131–S0150: settlement, stage 1 (on the copy)

*2026-10-24. By the Professor. **A statement only:** dispositions, verdicts and the corrections stated against the copy `BOOK-UNDER-REVIEW/`. The copy's files are not edited (the Principal, 2026-10-24, point 1). The working book stays frozen until the Rule Reviewer has scored. Stage 2 then applies these corrections to the working book **where it has the same defect**. No comparison of the copy with the working book was made in writing this.*

## Reports, saved verbatim, with their sha256 at hand-back

| Report | File | sha256 at receipt | Harness time |
|---|---|---|---|
| A (Opus) | `REPORT-A.md` | `e97c331c22de51f0e631fb84675434cbe082b6306dcba58189fb38c9bc110bed` | 1,050 s |
| B (Sonnet) | `REPORT-B.md` | `150473b7d7693e22a84431dba8ac623385a148c580375d3f3c00f058c02ce405` | 1,118 s |
| Integration (Opus) | `INTEGRATION-REPORT.md` | `b42d047548bda33cb0df499213597df0ac3978822986e84a1c9d826b387b9920` | 1,065 s |

## 0. Entry 58 record (stage 1)

| Measure | Value |
|---|---|
| **Harness time of the consolidation** | A 1,050 s + B 1,118 s + integration 1,065 s = **3,233 s** (53.9 min) |
| Wall clock, review phase | 00:18:40 → 00:59:20 UTC (2,440 s; A and B in parallel) |
| Part 0 (scope, frozen table, R3, brief) | 00:17:09 → 00:18:40 (part-0 commit), about 90 s, plus the plan (written before approval; not timed) |
| Settlement stage 1 | 00:59:20 → 01:01:00 UTC |
| **Defects in the rotated chapters** | **Ch. 2:** A 13 (14 items), B 11; both 7, A only 6, B only 5; **union holding 18** (B-19 does not hold), **plus 2 found by the integration reviewer that both missed** (M-1, M-2) and 1 new site of D1 → **20**. **Ch. 3:** A 13, B 16; both 7, A only 6, B only 11; **union holding 24** (A-23's typography flags do not hold), **plus 2 found by the integration reviewer** (M-4, M-5) → **26** |
| Overlap estimate (Chapman, the integration reviewer) | Ch. 2 ≈ 22, Ch. 3 ≈ 32. **Indicative only:** the reviewers are positively dependent (same instructions, copy and tools), so these are likely underestimates |
| R5 notes | no finding critical by F4 only. None critical at all |

## 1. Process record

- **A test-leak exposure, recorded at the Principal's instruction ("Proceed, record it").** Before the commissions, a recent commit subject (`4ab5b3b82`, the Rule Reviewer's) contained the words "planted-gap test sealed". Reviewers' sessions carry the five most recent commit subjects. The reviewers were told nothing.
  - A and the integration reviewer disclosed only commit subjects naming "the frozen copy", "the S0150 evaluation facts" and "the C28 settlement", or "this consolidation and the saving of reports A and B". B disclosed subjects naming "this consolidation and a frozen copy".
  - **None of the three reports mentions a test.** Whether any reviewer saw the 4ab5b3b82 subject cannot be told from the reports. The Rule Reviewer's scoring should allow for it.
- **Files outside the allowed set:**
  - B opened `frozen-table.md` (hashes only), `02-FILES.jsonl` (named by SCOPE for paths) and a folder listing (names only). It disclosed all three.
  - The integration reviewer read REPORT-B and two inventories through the harness's saved copies of its own earlier reads (copies of permitted files).
  - All three listed folder names.
  - **No reviewer reached a file of REVIEWER-NOTE item 4.**
- **An inconsistency of the Professor's.** SCOPE says the four inventories are "the only files outside the copy" reviewers may open, while BRIEF also permits the C28 settlement. Both reviewers opened it, under BRIEF. The fault is in my two texts, not in the reviewers.
- **Small errors in the reports, as the integration reviewer found:**
  - file counts: chapter_03 has 13 files, not 14; chapter_02 has 15;
  - A's quote anchor S0025 L152 should be L153;
  - A lists 14 Ch. 2 items while stating 13.

## 2. Verdicts

| CON | Before (copy's register) | Stage-1 verdict | Basis |
|---|---|---|---|
| 0239 | PROVISIONAL | **VALIDATED** | A and the integration reviewer; B PROVISIONAL. The non-implication is witnessed (two defaults, `--dir`), and sufficiency for K2 is deduced. Record identity as a kernel matter stays an open question, not a defect |
| 0240 | PROVISIONAL | **VALIDATED**, term narrowed | A and the integration reviewer. The term and headline become "content identity certifies the fields CE* reads, not marks" (B-07) |
| 0242 | PROVISIONAL | **VALIDATED** | A and the integration reviewer. A re-run is a sufficient route, not the only one (A-17) |
| 0245 | PROVISIONAL | **VALIDATED** | A and the integration reviewer. Possibility is shown structurally; the rate is not established; K6 applies by analogy |
| 0253 | PROVISIONAL | **VALIDATED**, reworded | A and the integration reviewer. "three axes" becomes "at least three axes"; exhaustiveness is not shown |
| 0256 | PROVISIONAL | **INVALID as stated** | A and the integration reviewer (A-02). A reference stored in the survivor but derived from the source meets the falsifier, and that is the estate's own design (§8.20 C2, C8). **Restated at stage 2** by the origin of the reference's information, which the act cannot rewrite. B's quantifier fix becomes near-tautological (conflict K1) |
| 0038 | PROVISIONAL | **INVALID as stated** | A and the integration reviewer. "Breaks the model" has no definition independent of the invariant list. Restated at stage 2 relative to stated guarantees or decisions |
| 0010 | INVALID | **PROVISIONAL** | B and the integration reviewer; A agrees the reason is stale. The INVALID ground is overtaken by §3.5's own grading. Distinctness is supported, survival is not. The dependents (CON-0095, 0185; §3.1, §3.3, §3.8 C6, §3.9 C2) use only distinctness and are flagged, not invalidated |
| 0007 | INVALID | **PROVISIONAL** | B and the integration reviewer. No defect is shown, and the register concedes that "not closed" is not a contradiction |
| 0063 | VALIDATED | **VALIDATED, narrowed** to "cannot bind at t" | the integration reviewer, with B-16. The derivation uses only the necessary direction, so it survives either fix of "effective" |
| 0001, 0047, 0056, 0064, 0092, 0093 | VALIDATED | **VALIDATED** (second, independent verdicts) | both reviewers. 0056's register basis is to be fixed: it cites the converse; the support is S0024 L84–88. 0064's content is stated as trivial (B-33) |
| 0238, 0241, 0243, 0244, 0248, 0249, 0250, 0251, 0254, 0162 | PROVISIONAL | **PROVISIONAL** | both. 0243 is **split** at stage 2 (A-15; done together with 0256, conflict K3). 0251 gets "is shown before" and EXT-084 (A-05; practice 2). 0254 (i) gets "cannot feasibly" (B-06) |
| other Ch. 2 and Ch. 3 PROVISIONAL | PROVISIONAL | PROVISIONAL | both |
| INVALID cores (Ch. 2: 0034, 0040, 0055, 0061, 0062; Ch. 3: the rest) | INVALID | cores checked, **unchanged**, except 0010 and 0007 above | both. 0033's restated core gets an ID at stage 2 (A-24, B-36) |
| 0246, 0247, 0252, 0255 | INVALID as stated | **unchanged**. Restated cores 0248, 0249, 0254 sound (with A-16's premises); 0256 fails (above) | both |

**Not decided here, a question for the Principal (conflict K5):** B-37 holds that several Ch. 3 INVALID verdicts mean "unsupported" or "not yet built", which the instruction scale would call PROVISIONAL. A mass re-verdict goes beyond SCOPE, which checks cores as restated. **Recorded; no re-verdict.**

## 3. Findings and the corrections stated against the copy

*Found by both reviewers: all accepted. One-only: accepted where the integration reviewer's check says **holds** or **holds in part**, and rejected where it says **does not hold**. Disagreements are settled as shown. Locations are the copy's.*

### 3.1 Ch. 8 (C25–C28)

| Finding(s) | Disposition | Correction stated against the copy |
|---|---|---|
| A-01 = B-02 | accepted (both) | §8.21's title and the Ch. 8 index row 8.21 promise "an internal ledger proves nothing outward", which no claim states. The settlement's W-10 remedy is absent and W-15 is misrouted (to C15, though the text is at C14). **Correction:** restore the claim on S0150 L109–118, L248–273, with S0146 L147, S0148 L55, CON-0196 and §8.15's reference ≠ ownership as homes; or retitle the section and the index row and correct the settlement's pointers. *Stage 2 decides which, by checking the working book.* Also add the missing References lines (S0149 L28–34, L141–158, L569–610; S0150 L488–492, L555–564, L571–592) |
| B-01 | accepted (holds) | §8.20 L27 (item 1), L111 (C9 heading) and L113 still say the hold sits "on the container". **Correction:** rewrite them to an explicit hold state, independent of location, with a gate on moves (C9's own W-04 correction) |
| A-02 (CON-0256) | accepted; supersedes B-05 | see §2. Rewrite §8.21 C1 and its title, the index row and the register |
| A-05 (CON-0251) | accepted (both) | "before" becomes "is shown before"; cite EXT-084 (Lamport 1978), already in EXTERNAL-SOURCES, together with practice 2's S0915 and S0991 (`PRACTICE2-NOTES.md`). Re-check §8.20 C8 and CON-0254, which rest on it (R3: 16 lines) |
| B-06 (CON-0254) | accepted | "cannot" becomes "cannot feasibly" in (i) (§8.20, 4 lines) |
| B-07 (CON-0240) | accepted (holds in part) | §8.18 C2's heading, §8.18.1 and index row L32 become "certifies the fields CE* reads, not marks" |
| A-15 (CON-0243) | accepted | split the operand condition into its own concept, and "shows that one is missing" becomes "shows that the set differs". One change with CON-0256 (K3; R3: 28 lines) |
| A-16 | accepted (in part) | state the premises: CON-0244 (one durability policy per store); CON-0254 (ii) (the follower holds the evidence entries); CON-0249 (the declaration must exclude what a violator could write; the operator's log is testimony and needs an anchor) |
| A-08 = B-10 | accepted | INVALID concepts cited as homes: CON-0252 in §8.20.3 L237 → CON-0254; CON-0247 in §8.19 L231 → CON-0249; CON-0085 in §8.21 L19 and L165 → "two necessary-condition forms (CON-0085 INVALID)" |
| A-09 | accepted (holds) | §8.20 C14 L157's pointer ("§8.19 cites S0140 L31–37") is false: correct the pointer, and add S0140's marker lifecycle to §8.19 C22 or note it there |
| A-17 | accepted | §8.18 C10 L111 "only a recorded re-run": add a note pointing to §8.19 C10's second route |
| B-03 | accepted (in part, normal) | withdraw the two unqualified "independently" claims (§8.18 L144, §8.19 L81). Tag "agreement" as within the estate's lineage unless a lineage argument is given |
| B-08 | accepted | add to §8.19 C18 a forward note to §8.20 C2 (α reachable when the switch record names the old head) |
| B-09 | accepted | §8.19 C19's heading contradicts its body: fix the heading |
| B-12 | accepted (in part) | add to the Ch. 8 index a dependency note: §8.18–§8.21 use Ch. 9's D, CE*, K, L and R by address |
| B-40 | accepted (low) | §8.18 C24 sits between C5 and C6: add an order note |
| A-25 / B-13 (coverage) | accepted, per the integration reviewer's §6 rulings | add, or exclude with a reason, the items marked MISSING in §4 below. B-13's "113 ids" is rejected as an omission (does not hold) |
| A-24 / B-11 | accepted | THEORY-MAP §9 still says "§8.1–§8.17"; the "149 read in order" needs its basis stated; BOOK-OUTLINE's Ch. 8 marks table needs rows for CON-0238–0256, and its "Ch. 8 (§8.1–8.11)" line is stale; the map has rows for 0238, 0244 and 0253 |

### 3.2 Ch. 2 (rotated)

| Finding(s) | Disposition | Correction stated against the copy |
|---|---|---|
| A-03 = B-15; integration D1 site §2.0 L46 | accepted (both); **conflict K2 settled as a technical choice, for review:** B's form | §2.11's "Effective = adopted ∧ t ∈ validity" is marked a **necessary condition**, with a pointer to §9.13's current-authority, so that no second definition is created. "adopted (a lifecycle status, not a standing)" is reconciled with §2.0 L46 and with "standing adopted" in §2.8.3 L56, Ch. 1 §1.14.1-12 L269 and CON-0063's basis. Settled once under T1-02 (R3: 6 lines plus 26 section-only) |
| B-16 (CON-0063) | accepted | the term becomes "cannot bind at t" (register, BOOK-OUTLINE L36); THEORY-MAP L56 already agrees |
| A-07 = B-24 | accepted (both) | the RM-1…4, SC-1 and SC-2 rulings are labelled "as recorded in RS/SC; act not seen" (the C23 rule). SC-1 gets the same "record not located" caveat as SC-2. §2.8.3 L53's "SC-1 unruled" against L124/L130 is labelled superseded |
| A-14 | accepted (holds) | §2.8.4a L114 "linked by containment": mark it a premise ("undefined until mapped", §1.6). §2.8.2 L29 Src (inferred) against L31 and §2.8.6 L189 Ded: one label |
| A-18 = B-17 | accepted (both) | "41 enforced controls" (§2.9 L76; §2.10 L30, L42): qualify with §3.9 C4 (22 `ask` triggers interrupt and do not block) |
| A-19 = B-18 | accepted (both) | label I-n(RO) against the book's I-n in §2.9 (and I-10 in §2.5). Name A1's reading where used (§2.4 L16, and Ch. 3 below). §2.11 L18: A1 is at §1.6, not §1.2 |
| A-20 = B-20; A-20 (AMS/GKC) | accepted | §2.9 L73's TODO T2-06: mark it superseded by the next paragraph. Add AMS and GKC to §2.0's short-name list |
| A-21 | accepted (holds) | §2.5 ↔ §2.10 cite each other for "explicit rank", which §2.10 does not contain: cite I-4/PK-P7's own text |
| A-22 = B-21 | accepted | §2.0 "only three of the eight are rules": state it as RS's claim. §2.4 L28: the reconciled analyser vocabulary restores OVERLAPS |
| B-14 | accepted (in part, low) | §2.9 L59's gate formula: add "unknown overlap is UNKNOWN" (§2.8.4) |
| B-19 | **rejected** (does not hold) | "realisability" is defined at §9.14 L43, and §2.3 L68 points there |
| B-22 | accepted (low) | the audit labels (L-, H-, R-, E-, C-) resolve only via 02.99 to an audit file outside the book: add an index or restate them |
| B-23 | accepted (low) | §2.8.6 L173 says "two consequences" and lists four: renumber |
| A-24 (CON-0056 basis) | accepted | the register basis cites the converse: replace it with S0024 L84–88 |
| **M-1** (integration) | accepted (low) | §2.8.7 L206 "not one of E1–E7" and §2.8.8 L286 "subset of E1–E7 (and E8)": stale since E9 and E10 were added |
| **M-2** (integration) | accepted (normal; depends on reading) | §2.10 L9 "the only authority boundary enforced by machine is the reserved-verdict rule": reconcile it with the 19 `deny` boundaries at L30–42 and with §2.8.7's blocking gate |

### 3.3 Ch. 3 (rotated)

| Finding(s) | Disposition | Correction stated against the copy |
|---|---|---|
| A-04 = B-04 | accepted (both) | CON-0010 → PROVISIONAL (§2). The stale reason is removed in §3.4 L7, T3-10 and register rows 11 and 40. The dependents are flagged as resting on distinctness only. SEQ-0012's rationale is reworded (K4); it stays open through I-11 |
| A-06 = B-31 | accepted (both); verdict as A | CON-0038 INVALID as stated (§2). §3.6 L74, L82 are restated |
| B-32 | accepted | CON-0007 → PROVISIONAL; §3.2 L31, L35 and T3-02 |
| A-10 / B-26 | accepted (in part) | §3.8 L67 "traced to a collision of two words" against §3.9 L22 ("part of it, not all"); index row 3.9 "four senses" is stale. The three statements of §3.9 C1 stand |
| A-11 | accepted (holds) | §3.6 L110 "as a necessary condition only": CON-0057 was refuted as necessary (§6.1 C4). Fix the text |
| A-12 | accepted (in part) | §3.3 L51 quotes S0008 correctly. The stale item is register CON-0011's "spaces may nest" against §3.10 L29: fix the register meaning (R3: 8 lines) |
| A-13 | accepted (holds) | CON-0162: state the case set of (ii), and drop (i) or justify it |
| A-19 / B-27 | accepted | label I-n(RO) at §3.3 L53, §3.7 L20–23, §3.10 L115. Name A1's reading at §3.5 and §3.9 C5a L128 |
| A-20 = B-25, B-28; A-20 (MM-1) | accepted | define or cite AC-7 (§3.8 L5) and MM-1 (03.00 L8); complete the 03.00 source list (S0018, S0023, S0024, S0026, S0029, S0043, S0166, S0889, S1052, S1524 and the T2 sources) |
| A-22 (Ch. 3 parts) | accepted | §3.1: S ties nature too (n = Skill ⇒ r = Tacit). §3.2 "applies only orthogonality" over a row that is the category test: fix |
| A-23 = B-29 | accepted in part | **one real quote failure:** §3.6 L110 "remains HYPOTHESIS" against S0025 **L153** "remain HYPOTHESES". Quote exactly. The two typography flags (03.01 L22, 03.04 L11) **do not hold** (faithful) |
| B-30 | accepted | §3.4 L5 "Deleting the document does not delete the rule": qualify per §3.8 C6 (fails when the document is the only carrier) |
| B-33 | accepted (low) | §3.8 C3 / CON-0064: state that the typed union losing nothing is trivial |
| B-34 | accepted (omission) | §3.7 CON-0046: add the stronger counter (`generates` is in the composition set) |
| B-35 | accepted | §3.10 C4's computed check has no script or run record: save it and cite the run. The count was reproduced by A, B and the integration reviewer (1,940 / 0 / 946) |
| A-24 = B-36 | accepted | CON-0033's restated core gets a register ID; CON-0045's argument cites it |
| B-38 | accepted (low) | the Ch. 3 TODO lacks CON-0009, 0065, 0066, 0067, 0091, 0096: add rows |
| B-39 | accepted (low) | §3.9 L135 and L138 repeat each other: merge |
| **M-4** (integration) | accepted | §3.8.2 L21 builds the orders on artifact, runtime asset and projection, which §3.3 L25–32 found fail the category test: add a note |
| **M-5** (integration) | accepted (low) | 03.00 L7 "all … CANDIDATE documents, AI-generated" is false for EXT-029 (the Principal's brainstorm, §3.10 C8): qualify |

## 4. Coverage against the window inventories

The integration reviewer's §6 rulings are adopted where A and B differed.

**MISSING items to add, or to exclude with a reason, at stage 2:**
- S0131 #6 ("no engine redesign"), #13 (the EKS-03 mapping), defect e (the union is the source's reading);
- S0133 #3 (INV-R1…R4, INV-ORDER), #6 (INFO-1's narrowing to "one writer of governance evidence");
- S0132 #6 ("compatible ⇒ merge");
- S0134 defects b (two owners settled by declaration) and e (interpreter undefined);
- S0136 #14 (a partial manifest is a FAIL; the tmp remnant quarantined);
- S0137 #12, #13;
- S0138 #7 (marker vs record), #10 (readers first vs writer first);
- S0139 #3 (registration ≠ production);
- S0140 defect f (self-verification);
- S0141 r2 (the happy path only);
- S0142 #2 (six placements forced, one chosen);
- S0143 defect e;
- S0144 #3 (realised while the configuration is not adopted);
- S0145 #2 (cost in amendment cycles) and defects b, c, e, f, g;
- S0146 #10 (author ≠ independent reviewer, not every prior reviewer);
- S0148 #1 (the report vocabulary and exit 0);
- S0149 #7, #9;
- S0150 #5 (the four principles), #6 (the Evidence Boundary Model), defect 1 (git and logs as authorship evidence: part of A-01).

**JUSTIFIABLY EXCLUDED, as marked:**
- S0132 #1, #3, #10;
- S0145 #7;
- S0146 defect 4;
- S0148 #7;
- S0149 #8, #10;
- S0150 #4 and defects 2–3.

## 5. Conflicts and their resolution

| Conflict | Resolution |
|---|---|
| K1: CON-0256, A's or B's restatement | **A's.** B's version is near-tautological. Its internal-inconsistency case is an instance of A's |
| K2: "effective", redefine (A) or demote (B) | **B's form**, as a technical choice for review: a necessary condition plus a pointer to §9.13, with no second definition. Settled once under T1-02 |
| K3: the CON-0243 split and the CON-0256 restatement overlap | done in **one change** at stage 2 |
| K4: CON-0010's re-verdict touches SEQ-0012 | SEQ-0012's rationale is reworded; it stays open through I-11 |
| K5: B-37, a mass re-verdict of Ch. 3 INVALIDs | **not decided.** A question for the Principal (§2) |

## 6. What stage 2 will do (after the score)

1. Apply §2–§5 to the working book **where it has the same defect**: the register, the sections, the map, the outline and the indexes.
2. Add practice 2's citations (`PRACTICE2-NOTES.md`): CON-0251 (EXT-084 plus S0915, S0991), CON-0245, CON-0253, CON-0256.
3. Write `SETTLEMENT.md` with stage 2's time and the entry-58 record.
4. Quote check, R1, CONTEXT and the session log.

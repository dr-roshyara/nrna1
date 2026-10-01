# Re-review of the coder-facing schema r3c-1

**Files used (all in this directory):** REVIEW-TASK.md, SCHEMA-r3.md (authoritative), SCHEMA-r3-CODER.md (r3c-1), EQUIVALENCE.md, CHANGELOG-r3c1.md, REVIEW-prev.md (the previous review, verdict FAIL), RECORDS.json, and SOURCES.md (used only for the anchoring check).

**Method:** I read everything by hand. No commands were available, so the word count is an estimate. I compared every rule line of the coder file with the SCHEMA-r3 sentence it maps to in EQUIVALENCE.md. I spot-checked the S-line numbers in EQUIVALENCE (62, 86, 93, 99, 206, 228, 243, 266), and all of them are correct.

## Verdict: **FAIL**

The rules themselves now match. **Every rule sentence of SCHEMA-r3 appears in the coder file with the same meaning**, so the previous B1 is fixed. The fail comes from the examples, for three reasons:

1. **One worked example gives a value that the rule lines contradict.** E-N14 also copies the form of R10 exactly.
2. **Several examples written into the rule lines are still one-to-one copies of specific records.** These include the R12 and R05 templates that the previous review explicitly asked to be broken, plus new ones for R30, R18/R20 and R06/R07.
3. **E-A21/E-A22 quietly set a convention for which record gets which act on a shared span.** That is the open R06/R07 assignment question. The previous review blocked "(by lineage)" for the same reason.

All three fixes are mechanical. No rule needs to change. Once they are fixed, I expect **PASS_WITH_LIMITATIONS**. The remaining limitations are gaps in SCHEMA-r3 itself (§4).

---

## 1. Equivalence

### 1.1 Rule sentences
I compared every coder id in §1–§5 with its S-line. The following all hold:
- **V lines:** verbatim once markup and tags are removed.
- **R lines:** only the cross-reference changes (U-6, R-A5.1.15).
- **H lines:** the rank-table cells are carried word for word.
- **E lines:** only the example changes. The rule part is untouched.

The previous review's B1 and minor qualifiers are all restored verbatim:
- "on the target" (D4.1)
- "for X" (R-A5.3.6)
- "that disposes of that proposal" (R-A2.3)
- "with no refusal expression" (R-A2.5)
- "rank 3 if it occurred" and "The intended failure is not a non-effect marker" (D6.10–.11)
- "guard" (D8.15)
- "no RED begins" (D8.10)
- the D4 question wording (D4.2)

The struck r2 text, the purpose clauses and the THEORY-CHOICES pointers that were left out do not decide any value. **At the level of rule sentences, `equivalence_ok = true`.**

There is one textual change that is not purely an example swap: **D11.6**. SCHEMA-r3 states as a fact that its example clause *is* "a ground clause in a session log". The coder file says "(when it is a ground clause …)". The rule itself is unchanged, but SCHEMA-r3 settled one value through that example, and the coder file no longer does. See T11.

### 1.2 Adversarial tests (at the points where the previous review found drops)

| # | Area | Input (section / span) | SCHEMA-r3 | Coder r3c-1 | Same? |
|---|---|---|---|---|---|
| T1 | proposal binding, target | `## R-40`; section: "Motion: adopt the hedge rule." Span: "The committee declines to adopt the fence rule." | UNKNOWN: no proposal for X on this target (D4, rank 1) | UNKNOWN (D4.1, R-A5.3.7; matches E-A10) | ✓ |
| T2 | proposal binding, none stated | `## R-40`, no motion in the section. Same span. | UNKNOWN | UNKNOWN | ✓ |
| T3 | negated X, no proposal | `## R-41`. Span: "The fence rule is not superseded." No motion. | Not a disposition (D5 (ii)) → not a rank-1 candidate → act NONE. NORM is still STATUS_CONSTRAINT / PERFORMATIVE_ONLY (D8 (iii)) | Same (R-A5.3.8, D5.2–.4, D8.13) | ✓ |
| T4 | refusal whose complement is a noun | `## R-42`; section: "Motion from plot 7: ban rotavators." Span: "The committee rejects the plot 7 motion." | X = *reject* (S-91). No proposal "for reject" → UNKNOWN | Same (R-A5.1.23, R-A5.3.7) | ✓ (but see the §4 gap) |
| T5 | A3 note: a reason, plus a refusal verb | Register row with options stated. Span: "Option B declined: it would shade the next plot." | Rank 1. R-A2's "reason … NONE" does not apply, because a refusal expression is present | Same (R-A2.5 keeps "with no refusal expression") | ✓ |
| T6 | A3 note: a reason only | "Fencing would shade the next plot." | NONE | NONE (R-A2.5–.6) | ✓ |
| T7 | mechanism: intended failure | Plot log: "Ran the drip-line test; three emitters fail, as expected." | "run": rank 3 OCCURRED; `mechanism_refusal` not required | Same (D6.9–.11) | ✓ |
| T8 | mechanism: empty result | "Searched the rent ledger → returns nothing." | Not a mechanism response; "search" REPORTED OCCURRED | Same (D6.8) | ✓ |
| T9 | mechanism vs named actor | "Submitted the plot swap on the booking system → refused by the secretary." | Named actor + refusal verb → rank 1 ACT_REFUSED (attempt identified); `mechanism_refusal` false | Same (D6.16) | ✓ |
| T10 | mechanism, no attempt stated | "Terminal: rejected: card expired." | UNKNOWN, `mechanism_refusal` true | Same (D6.15) | ✓ |
| T11 | ground without a listed marker (R22 shape) | Session log: "Not allocated — one season only, and rule 4 forbids allocating to first-season members." | SCHEMA's D11 treats the parallel R22 clause as "a ground clause" → `cited_as_ground` **true** | No listed cue, so "Otherwise it is false" (F-norm.cited_as_ground.4). E-N17 shows "since" as the cue → likely **false** | ✗ (plausible) |
| T12 | non-commencement with a noun subject | "Fencing does not begin." | D13 (iii): the noun subject goes to `about_target`. `about_operation` = *begin* | The rule lines say the same, but worked example E-N14 codes `begin digging` | ✗ (example contradicts the rule) |

T1–T10 cover every place where the previous review found drops, and the two texts agree on all of them. T11 comes from SCHEMA-r3 itself: its `cited_as_ground` cue list is open ("…"), while the D15 list is closed. Only SCHEMA-r3's example decided the R22 case. That makes T11 a limitation rather than a blocker, but it needs a KNOWN-AMBIGUITIES entry. T12 is a blocker (§2, A1).

## 2. Anchoring

I checked whether any example shares wording, structure or answer pattern with a specific RECORDS.json record.
- **§6 worked examples:** the surfaces are new, and the previous review's specific templates have been broken there.
- **Examples inside the rule lines (code E):** often only the nouns were swapped and the record's syntax was kept.
- **Answer patterns:** almost every determinate record has a matching example. Under the previous review's standard that is acceptable where the record is decided by rule. It is **not** acceptable where the example copies source wording, or settles an open point.

**Blocking:**
- **A1. E-N14 copies R10 and goes against D13.7.**
  - "Digging on the new field does not begin" has R10's form ("IMPLEMENTATION DOES NOT BEGIN"), and the example codes `about_operation = begin digging`.
  - D13.7 says an operation named only by a noun subject supplies `about_target`, never `about_operation`. D13.2 names "does not begin" as the verbal predicate.
  - So the example settles R10's `about_operation` in a way the rule lines do not support.
  - *Fix:* give `about_operation` = begin, `about_target` = digging on the new field. Or use a verbal complement ("Members do not begin digging …"). Also change the surface so it no longer mirrors R10.
- **A2. Rule-line examples that still copy specific records.**
  - D13.6 "may be measured and must not be fenced" has the same syntax as R12 "MAY BE PLANNED AND MUST NOT BE STARTED". The previous review named R12 as blocking. Only E-N07 was changed.
  - D13.5 "authorized to dig and plant" has the same syntax as R05 "AUTHORIZED TO PLAN AND IMPLEMENT". This is also a named R05 template.
  - D13.8 "FENCING is adopted" matches R30 "ANNOTATE is adopted.": an all-caps option name + "is adopted", with the same answer.
  - R-A5.1.11 "documented in the autumn minutes §3", "already adopted in the 2023 rule book §9" match R20 "documented in PB-004 retro §9" and R18 "already ARB-adopted in Handover §9". The words "already … adopted in … §9" are kept, and the previous review flagged this pair.
  - D3.5 "Then the bonfire motion itself withdrawn" is R06/R07's "Then R-90 itself withdrawn" with only the noun swapped.
  - *Fix:* rewrite each one with different syntax that follows the same decision path. Examples: "Measuring is permitted; fencing is forbidden" (two operators); "required to weed and mulch" (already in E-N06); "the meeting's choice, gravel, is adopted"; "recorded in last year's minutes"; "Later, the motion was withdrawn by its mover".
- **A3. E-A21/E-A22 set the shared-span assignment convention (open R06/R07 question, the previous review's KA A5).**
  - In both examples, the lower record id takes the main or first-mentioned act ("M1 is the record for (re-let …)", "M3 … re-let; M4 … give notice").
  - SCHEMA-r3 states no assignment rule, and RECORDS.json has no `v1_event_ids`. The examples teach "lower id = main act", which decides R06 vs R07.
  - *Fix:* say explicitly that the schema does not decide which sharing record takes which act. Then either leave the assignment open in the examples, or reverse the order in one of them.

**Non-blocking. These records are decided by rule, so the example only teaches the rule; still, the surfaces should be varied:**
- E-N15, D8.4 and D10.4 repeat the R06/R07 frames "<body> found it was <class>, not <class>" and "withdrawn … because <body> found". E-N15 follows the previous review's own suggested fix.
- E-N17 "<act>, since rule 3 forbids <-ing>" has R22's structure and result (PROHIBITED, OPERATOR, ground). Because of T11, it also pushes R22 toward `cited_as_ground = false`.
- E-N10 matches R34 (bare AUTHORIZED); E-N09 matches R32 ("is authorized to <verb>"); E-A15 matches R05's answer (authorize / ATTEMPTED / PREPARED).
- R-A3.6 "the shed rule is not superseded" and E-A11 match R26. E-A11 uses a different path (the merge rule).
- D7.6 "no plot holder may sublet" matches R01's norm clause. It is the D7 tested form "no <noun> may …".
- R-A5.1.22 and D3.17 "declines to extend a tenancy …" match R01's "declines to resolve an ambiguity …".
- F-norm.adopted_via.1's heading "(adopted by R-12)" matches the R15–R21 section heading. This is the tested form.

**Domain:** all §6 examples and all replacement examples come from one fictional, non-software domain: an allotment-garden association. Some software or project words remain in rule lines because SCHEMA-r3 tests for them:
- "RED" and "no RED begins" (D6.9, D8.10). "no RED begins" is source wording from R-72, the section of R10.
- "commits" (D1.2).
- program, tool, test and exit code (D6).
- "GROUNDS AS ISSUED:".

This is acceptable.

## 3. Contamination

- **Clean:** nothing about hypotheses, the study, expected answers, record ids, THEORY-CHOICES or KNOWN-AMBIGUITIES.
- **Minor, non-blocking:** the file still mentions the schema's revision history and an earlier coding round:
  - "r2 meaning", "r2 sense", "as in r2" and "r3 gives no lexicon";
  - "`v1_event_ids` (lineage)" and "the v1 operation list is preferred";
  - "the competitions that G3 and G4 raise", plus R-A5.0.2 "The gap list that defines G3 and G4".

  These say that an earlier coding (v1) and earlier revisions exist, but they reveal none of their content. The G3/G4 mention also contradicts the file's own reading note, which says that "pointers to other project files are left out". R-A5.0.1 is fully stated after its colon, so the G3/G4 words can be dropped without changing anything.

## 4. Self-containment and the NOT IN SCHEMA markers

The NOT IN SCHEMA markers are honest and placed correctly. Most of them, though, point to **gaps in SCHEMA-r3 itself**, not in the coder edition:

| Marker | Acceptable? | Gap in SCHEMA-r3? |
|---|---|---|
| F-norm.deontic.6 / D7.8: the r2 meaning of PERMITTED, AUTHORIZED, UNAUTHORIZED, DELEGATED | Honest | **Yes, and significant.** R04, R12, R13, R14, R32, R33, R34 and R36 depend on these values. For example, nothing says whether "remains unauthorized" (R33) is the UNAUTHORIZED operator or a status label. |
| R-A5.0.5 / R-A5.3.10: the r2 meaning of reported ACT_OCCURRED and of ACT_REVERSED | Honest | Yes. Rank 3 plus D3 partly cover reported occurrence. ACT_REVERSED has no conditions. No current record is a reversal. |
| R-X1.5: r2 rules on other uses of the section | Honest | Yes, but low impact, because each rule says "span or section" where the section is allowed. |
| F-act.operation.2: the v1 operation list | Acceptable ("preferred", open vocabulary) | Yes, minor |
| U-2a: how lineage is established | Acceptable if coded NOT_EVIDENCED | Yes, and RECORDS has no `v1_event_ids` |
| R-A5.0.2: G3/G4 | Unnecessary, because the content is stated in R-A5.0.1 | No |

**Needed but missing, and not marked:**
- No rule covers records with no span (R24, R28, R35). SOURCES.md's "UNCLEAR" is not a schema value.
- The file never says where the section text comes from (SOURCES.md). Coders need it for proposals, `later_disposition` and the Evidence column header.
- "Taken" in R-A5.1.8 is undefined. This is verbatim SCHEMA-r3, and CHANGELOG discloses it.
- Nothing defines how a proposal's target is matched to the act's target, which D4 "on the target" requires. For example, "a ban on rotavators before 9 a.m." vs "the rotavator ban".
- Nothing defines the cue set for `cited_as_ground`, or how it relates to the closed D15 list (T11).
- The noun-complement rule (R-A5.1.23) makes "rejects the motion" UNKNOWN whenever the motion is named as a noun, because no proposal "for *reject*" can exist (T4). This looks unintended.
- E-N09 gives no section, yet `performative_token = true` depends on whether "is authorized to" is the instrument's operative text (D2).

## 5. Codability and length

- **Words:** about **10,500**, by hand estimate (range about 9,500–12,000). That is about 3× the previous draft (about 3,600). There are about 440 rule lines, 43 worked examples, and the layout section.
- **Risk: MEDIUM-HIGH, unchanged.**
  - *Better:* one rule per line, stable ids, verbatim wording, and one decision path per example.
  - *Worse:* the length, about 25 fields per record plus four nested lists, heavy cross-referencing, and the undefined r2 deontic values. The no-span records and the missing section source remain.

## Blocking
1. **A1:** E-N14 codes `begin digging`, which goes against D13.7 (a noun subject is `about_target`), and its form mirrors R10.
2. **A2:** rule-line examples are still one-to-one templates of R12 (D13.6), R05 (D13.5), R30 (D13.8), R18/R20 (R-A5.1.11, including "already … adopted in … §9"), and R06/R07 (D3.5, "Then … itself withdrawn").
3. **A3:** E-A21/E-A22 set an unstated "lower id = main act" assignment on shared spans, which is the open R06/R07 question.

## Non-blocking
1. T11 / R22 `cited_as_ground`: SCHEMA-r3 decides it only through an example. Add a KNOWN-AMBIGUITIES item, or state the cue rule in SCHEMA-r3.
2. Vary the surfaces of the determinate templates: E-N15/D8.4/D10.4 (R06/R07), E-N17 (R22), E-N10 (R34), E-N09 (R32), E-A15 (R05), R-A3.6/E-A11 (R26), D7.6 and R-A5.1.22/D3.17 (R01).
3. Drop the "G3 and G4" mention, which contradicts the reading note. The r2/v1 mentions are low-grade contamination.
4. The r2 meaning of PERMITTED, AUTHORIZED, UNAUTHORIZED and DELEGATED is a gap in SCHEMA-r3 that affects about 8 records.
5. Add: a no-span rule (or a KNOWN-AMBIGUITIES item); the section source (SOURCES.md); target matching for D4; the R-A5.1.23 noun-complement refusal case.
6. E-N09 needs a section.
7. Length is about 10,500 words (hand estimate), which raises codability risk.

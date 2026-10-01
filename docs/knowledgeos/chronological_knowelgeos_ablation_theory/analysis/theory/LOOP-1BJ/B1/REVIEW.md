# Targeted re-review: SCHEMA-CORE r1 (frozen)

**Verdict: PASS_WITH_LIMITATIONS.** All six TASK items are applied. Both neutrality fixes are neutral, and no theory decision was introduced. No new or changed example mirrors a record. One new guidance cue ("deliberately did not") repeats R20's wording. It does not block, because the rule alone gives R20 the same code. The schema is under 1,000 words. In the dry run, debatable fields drop from 47 to 41.

Sources used: SCHEMA-CORE.md (r1), CHANGELOG-r1.md, TASK.md, CORE-SPEC.md, REVIEW-r0.md/.json, RECORDS.json. **There is no r0 copy of the schema here**, so "nothing else changed" is checked against the changelog and the descriptions in REVIEW-r0. It is not a line-by-line diff.

## 1. Fixes

| # | Item | Status | Note |
|---|---|---|---|
| 1 | Performative convention as a rule | Fixed | §3 rule 5 uses the TASK wording word for word. |
| 2 | NS test: "has, or is stated to have" | Fixed | §2, line 18. The "give something a status" wording is gone. |
| 3 | Heading rule | Fixed | §4 uses the section where available, otherwise UNKNOWN, and never NO by default. It now agrees with §1 and rule 2. |
| 4a–e | One line of guidance each | Fixed | Five one-line bullets: deliberate non-action, attempted, recommendation, prepared/not-yet-adopted, quoted performative. |
| 5 | Example 6 verb | Fixed | "declined" → "refused". Codes and reason are unchanged. |
| 6 | SOURCE_FACT vs INFERENCE for section context | Fixed | One line was added. The changelog also discloses one extra edit: "or context taken from the section" was removed from the INFERENCE line. That removal was needed to avoid a contradiction, so it is acceptable, but it goes slightly beyond "one line". |

**Nothing else changed:** this is consistent with the changelog and with REVIEW-r0's descriptions (field definitions, rules 1–4, Examples 1–7 and 9). One point cannot be verified: REVIEW-r0 quotes Example 8 as "Topic: the new arrangement", but r1 has "Compost bins: the new arrangement". The review may have paraphrased the example, or the label may have changed without being listed.

## 2. Neutrality

- **NS fix: neutral.** Normative status is now a state that is stated, not the result of an act. Residual issues (non-blocking):
  - Example 3's reason ("the allocation **gives** an entitlement") still frames status as the outcome of an act. It was kept out of scope on purpose.
  - "is stated to have" does not say *who* states it: the span itself, or someone the span reports. That ambiguity makes R08 newly debatable.
- **Heading fix: neutral.** The rule has no default direction. It also removes r0's H-B risk, because R11 (the work-event record) is now decided from its section rather than defaulting to NO. Residual issue: Example 8's SP = NO has no reason in the table. The changelog's explanation ("from the label's words, not from a default") is not in the schema, so a coder could still read the example as a default NO.
- **No theory decision.** Rule 5 is the mandated reporting convention. Two choices sit near the line, but each keeps the data recoverable:
  - 4a counts a non-action presented as chosen as a refusal, i.e. an act. This follows from the spec's "refused".
  - 4e codes a bare quoted performative as written (SP = YES) and marks it "quoted" in `notes`.
  - 4d ("do not decide its force") stays neutral on PREPARED vs ADOPTED.
- **Inconsistency (non-blocking):** 4c allows NS = YES for a recommendation only for "permission, requirement or prohibition". That list is narrower than the NS definition, which also covers authorized, eligible and normative status. The gap matters for R18–R20.

## 3. Anchoring

- **No new or changed example mirrors a record.** Example 6 ("refused Mr Lutz's request…") has no match in RECORDS.json.
- **Non-blocking:** the 4a cue "deliberately did not" matches R20 ("deliberately did NOT reuse") word for word. REVIEW-r0 quoted this phrase from R20, so the cue was taken from that quote, which contradicts CHANGELOG's "generic phrases" claim. The rule ("presented as chosen") gives R20 AR = YES by itself, so the cue does not change the code. Suggest a neutral cue such as "chose not to".
- **Low risk:** the 4d label "Prepared" repeats R05's "(PREPARED)". The wording was required by TASK, and the bullet does not decide R05's force.

## 4. Length

About 945 words by hand count (±5%), excluding table pipes, markdown markers and JSON punctuation. That is within the ≤ 1,000 limit, in line with the changelog's ~965, and leaves little room for additions.

## 5. Dry run (R01–R36, r1 only; codes withheld)

| Field | Determinate | Debatable | r0 debatable |
|---|---|---|---|
| ACT_REPORTED | 26 | 10 | 13 |
| ACT_SELF_PERFORMED | 19 | 17 | 18 |
| NORM_STATED | 22 | 14 | 16 |

**ACT_REPORTED** (R01, R09, R11, R16, R19, R22, R23, R25, R27, R30)
- **Resolved:** R02 (4e plus rule 5), R20 (4a), R26 (both readings give the same code).
- **R01, R09, R23, R25, R27, R30:** rule 5 makes AR depend on SP, and SP is still debatable for these records. The AR question is now an SP question.
- **R11:** the rule conflict is gone. AR now depends on whether the section shows that a commit header reports a work act.
- **R16, R22:** it is unclear whether a reasoned non-action ("NOT transferred because…", "NOT promoted — … forbids") is "presented as chosen".
- **R19:** is "recorded there as" an act?

**ACT_SELF_PERFORMED** (R01, R03, R04, R05, R09, R10, R12, R13, R14, R23, R25, R26, R27, R30, R32, R34, R36)
- **Resolved:** R02 (4e).
- **Still open:**
  - Annotation voice: R01, R03.
  - Ruling headlines: R04, R05, R23. 4d explicitly leaves R05's force open.
  - "is …-ed" forms: R09, R25, R27, R30.
  - Is a negative declaration a performative? R26.
  - Norm sentences inside rulings: R10, R12, R13, R14, R32, R34, R36. There is still no rule for whether stating a norm performs it.

**NORM_STATED** (R02, R06, R07, R08, R10, R15, R16, R17, R18, R19, R20, R26, R27, R30)
- **Resolved:**
  - R05: 4d says to record what the text states.
  - R09: "filed as a ruling" now reads as a stated status.
  - R11: decided from the label's words or its section.
- **New:** R08. "Is stated to have" may reach a reported heading such as "Not Part of Ruling".
- **Still open:**
  - R02, R30: adoption plus the Example 3 residual.
  - R06/R07: rule or description?
  - R10: directive or description?
  - R15–R19: "PROMOTE" sits inside a matrix that is "adopted by R-36". The spans also contain principle statements, and 4c's narrow list conflicts with the NS definition for R18 and R19.
  - R20: is "Candidate" a normative status?
  - R26, R27: an implied continued status.

## Follow-ups (non-blocking)

1. Replace the 4a cue "deliberately did not" with a phrase that does not come from a record.
2. Align 4c's list with the NS definition, or state why it is narrower.
3. Add a reason for SP = NO in Example 8.
4. Make clear who "is stated to have" refers to: the span, or a party the span reports.
5. Example 3's "gives an entitlement" (carried over from r0).
6. R32/rule 1 overlap: report it to the spec owner.
7. R06 and R07 are identical records (carried over from r0).

# CHANGELOG: SCHEMA-CORE r0 → r1

Scope: only the six items in TASK.md. They come from REVIEW-r0 (verdict PASS_WITH_LIMITATIONS). Nothing else was changed.

| # | TASK item | Where | Change |
|---|---|---|---|
| 1 | Performative convention as an explicit rule | §3, new fixed rule 5 | Added: "A span that itself performs an act is ACT_SELF_PERFORMED = YES. It is ACT_REPORTED = YES only if the text also reports a separate act as having happened." Before, this convention appeared only in Example 3. |
| 2 | Neutrality of the NS test | §2, NORM_STATED decision test | "Or does it give something a status that carries such consequences?" → "Or does it say that something has, or is stated to have, a normative status that carries such consequences?" |
| 3 | Heading rule agrees with §1 and Example 8 | §4, "Heading or label alone" | Old rule (code only the heading's words; a bare label is NO on all three; do not use the section) replaced with: "decide it using the surrounding section where the sources provide it. If it is still undecided, UNKNOWN. Never NO by default." Example 8 (UNKNOWN / NO / UNKNOWN) now follows this rule: its SP = NO comes from reading the label's words, not from a default. |
| 4a | Deliberate non-action | §4, new bullet after "Negated or refused act" | A not-doing counts as a refusal only if it is presented as chosen; otherwise it is mere absence. |
| 4b | Lower bound of "attempted" | §4, new bullet | At least one step toward the act must be reported; intention alone is not an attempt. |
| 4c | Recommendations | §4, new bullet | A recommendation is not an adoption. NS = YES only if the span states a permission, requirement or prohibition. |
| 4d | Prepared / not-yet-adopted decisions | §4, new bullet | Record what the text states; do not decide its force. |
| 4e | Quoted performatives | §4, new bullet after the quotation rule | A span made up only of the quoted words is coded as written, with "quoted" in `notes`. If the span includes the reporting frame, the existing quotation rule applies. |
| 5 | Example 6 refusal verb | §5, Example 6 | "declined" → "refused". The codes and the reason are unchanged. |
| 6 | SOURCE_FACT vs INFERENCE for section context | §6, `status` list | Added one line: section context is SOURCE_FACT if its words decide the field outright, INFERENCE if they need interpretation. The clause "or context taken from the section" was removed from the INFERENCE line because it contradicted the new definition. |

## Deliberately not changed

- **Example 3's reason** ("the allocation gives an entitlement"). REVIEW-r0 §1 links it to the "give a status" drift, but TASK item 2 is limited to the NS test.
- **Fixed rule 1's R32 overlap** (review follow-up 6). The wording is required by CORE-SPEC, and the follow-up is addressed to the spec owner. It is not one of this task's items.
- **The SP cue phrases and Example 8's label shape** (low-risk anchoring notes in REVIEW-r0 §2). They are not in scope.
- **Everything else:** the field definitions, fixed rules 1–4, the other boundary bullets, Examples 1–5 and 7–9, and the output format.

## Length

Counted by hand: about 965 words, excluding table pipes and markdown markers. r0 was about 850 on the same basis; REVIEW-r0 gave about 880. On the review's basis, r1 is about 995. It is within the ≤ 1,000 limit but close to it, so a future addition will need an equal cut.

## Domain

All examples are still in the allotment-garden domain. No record wording was added. The new guidance cues ("should", "recommends", "deliberately did not") are generic phrases, not wording taken from a record.

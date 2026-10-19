# Review C6: settlement (theory-only turn, 2026-10-19)

Report: `REVIEW-REPORT.md` (sha256 `f7428635…`). Every finding was checked against the sources (anchors re-read). None was disproved, so all stand.

## Concept verdicts

| Concept | Before | Reviewer | Settled | Change in the book |
|---|---|---|---|---|
| CON-0040 void-overriding | ✓ | KEEP | ✗ partly valid | Ch. 2 §2.5: region reading is Int; wider reading; scope = Round39-MC |
| CON-0084 runtime adapter | ✓ | KEEP | ✗ partly valid | Ch. 1 §1.5: two senses of "adapter"; "falsified" → not instantiated, deferred |
| CON-0085 BC admission | ✗ | KEEP | ✗ partly valid, **reason reversed** | §6.2 C1: necessary condition, no sufficiency condition; same-day opposite verdicts |
| CON-0086 context type | ✓ | KEEP | ✗ partly valid | §6.3: D-7 WEAK, circular check, nesting, five readings |
| CON-0087 protocol/records | ✓ | VALIDATE | ✓ | limit noted |
| CON-0088 two reuse tests | ✓ | KEEP | ✗ partly valid | §6.3 C5: gap = binding, case law, research clause |
| CON-0089 core/supporting | ✓ | VALIDATE | ✓ | horizon identification dropped |

Under CV-1 (two statuses), KEEP maps to INVALID with "partly valid" in `basis`. The repaired statements go to the reviewer at C7.

## Claims refuted or narrowed
- Ch. 0 §0.2: "falsifier contradiction" → a tension (S0032 L59 is conjunctive); F1 dissolved for the vision only, open for the mission.
- Ch. 1 §1.10: identity → stable under permitted moves; words are identifiers relative to a context (Int).
- §6.1 C7: S0031's scoping confined to one claim.

## Coverage (question 6): 20 items
- Written into the book: items 1–6, 10, 12–15.
- Recorded as open: T6-04 … T6-11 (items 7–9, 11, 16–20).

## Evidence on the process (compared with C5)
- **Coverage.** C5 listed about 10 omissions, under a general instruction. C6 listed 20, 6 judged necessary, under the explicit question 6. The sources differ, so the counts are not strictly comparable. Question 6 produced an itemized, ranked list that could be settled item by item; that is the observable difference.
- **Claim-scoping did not prevent overstatement.**
  - Of the 7 new concepts, 5 lost VALIDATED.
  - 2 further claims were refuted (the falsifier contradiction; F1 dissolution for the mission).
  - Every one of these was an overstatement of scope, or a misreading of logical form; claim-scoping was meant to catch exactly these.
  - In C5, 6 results did not survive refutation.
- **So no improvement is shown at the Professor's step.** The review step caught the defects, as designed.
- **Provenance.** The reviewer's report wrongly says its step 1 was not delivered. The transcript shows two step-1 texts (10:02:54Z and 10:05:56Z), both before the brief (10:05:57Z). See `00-access/ACCESS-RECORD.md`.

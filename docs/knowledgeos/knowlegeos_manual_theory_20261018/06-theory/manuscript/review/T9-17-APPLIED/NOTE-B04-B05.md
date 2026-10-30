# Note to the Principal: B-04 and B-05 (I-003) in T9-17

*2026-10-30. The Professor. Asked in the decision record addenda 2026-10-29T16:34:45Z and 2026-10-29T23:55:13Z.*

**Answer: neither used nor rejected. They were not considered in T9-17.**

- **Fact (my session):** B-04 and B-05 were not routed to me before this request. No instruction to me named them, and I did not read the auditor's reports (`review-implementation/`) while preparing T9-17. My recommendation of (b) rested on I-4, S0237 L209 and S0888 (§9.18), and on C43 W-05.
- **Fact (read now, `review-implementation/PART-B-REPORT.md` L123–137):**
  - **B-04:** the code enforces "absence is never a positive result": INCONCLUSIVE, never PASS, on unreadable or empty input; an empty check set is refused.
  - **B-05:** `Assessment::of` refuses an empty evidence string.
  - The auditor classes both as theory gaps (no enforcer named in the theory, G1), medium confidence, S0.
- **Do they bear on T9-17's question? (Int):** mostly not. T9-17 asks what to show when live support and live refutation of one item coexist. B-04 is about an empty record (it agrees with every option: no live evidence gives "not attested", never "supported"). B-05 is about evidence being non-empty. Neither addresses combining conflicting evidence.
- **One part does bear on it, and points the other way (fact and Int):** the code's aggregate (`AssuranceHandoffReport::aggregateVerdict`, `scripts/lib/EngineeringKnowledge/Shared/Domain/AssuranceHandoffReport.php` L121–133) returns FAIL if any slice fails, whatever the others say. That is refutation-first, option (a)'s shape. But it combines **independent checks of different slices** of one artifact, not support and refutation **of one claim**. A FAIL in one slice and a PASS in another do not contradict each other. So it is not evidence for (a) on T9-17's question. It is one code base, implementation evidence only, and the book does not take its reasons from it.
- **Would it have changed the recommendation?** No. It is recorded here so that the auditor's re-check can see the difference: the code's fail-closed aggregate is a conjunction over checks, while g1's "contested" concerns one claim. Recorded for the re-check, not for a decision.

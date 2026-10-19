# Settlement of the window C8 review (S0041–S0045 + S0129, S2834)

*2026-10-19. Report: `REVIEW-REPORT.md`; blind inventory: `STEP1-INVENTORY.md`. One commission, one step-2 message. Reviewer time: about 2 min (step 1) + 7 min (step 2) by the tool clock. All 20 findings accepted. Finding 3 was re-checked in Ch. 1 and holds.*

**Running measure:** 20 findings · 0 critical · 9 high · 5 material omissions · 0 defects found in work that had passed review. **The largest one is mine:** canonical discovery (search before modelling) was not done against Ch. 1 before §5.5 was written, so §5.5 re-derived §1.12–§1.14.3.

## Verdicts applied

| CON | Verdict | Valid core / scope |
|---|---|---|
| 0109 | INVALID as stated | executor ≠ authorizer (n = 1); an instance of Ch. 1 §1.12 / §1.14.3 |
| 0110 | INVALID as stated | no canon-level generative run as of S0045 Run 1 (08-03) |
| 0111 | VALIDATED (restricted) | capability ≠ kernel primitive; N ↔ minimality, S ↔ Req |
| 0112 | INVALID as stated | regrouped: cases / counterfactual or comparative / not observed; usage ≠ outcome |
| 0113 | INVALID as stated | vocabulary graded by exercise; promotion needs a recorded performance |

## Changes made

| Findings | Change |
|---|---|
| 1–4 | §5.5 C1–C2 rewritten as instances of Ch. 1 §1.12, §1.13.1, §1.14.3. Two roles plus the recorder; "decide" marked as a homonym; the merge loses execution provenance, not A1; C2's premise stated; the two laws kept apart. |
| 5 | §5.5 C2: invariant owner. The engine guarantees presence of the reference; externality is held by discipline, not enforced. |
| 6, 7 | §9.3 C2 rewritten as an analogy with four limits; C4: N ↔ minimality, S ↔ Req, P has no counterpart. |
| 8 | §9.3 C1 scoped to Run 1 and canon level; the instrumentation-level n = 1 and the source's internal tension added. |
| 9 | §8.3 C1 regrouped; S0045's self-measurement limit added. |
| 10, 20 | §4.3 C1 restated; C4 "is" → "resembles". |
| 11 | **§4.2** (earlier section, after review): a note that the first run tests its stage rule; §4.3 C5b. |
| 12 | **Ch. 1 §1.13.2 point 5** (earlier section, after review): S0129's dated durability evidence, same line as AMS. |
| 13 | **Map:** F13a, the attribution layer of an act. |
| 14, 15 | §4.3 C5a (derived time-to-decision); §5.5 C5 (count skew). |
| 16 | §5.5 C3: the must-do half (return for confirmation; a blank is not a decision). |
| 17 | §9.3 C5 weakened to a lexical signal. |
| 18, 19 | §8.3 C5 ("fixed in advance" unverified), C6 (inclusion, not temporal law). |
| Appendix | §5.5 C4 marked unverified; S2834's separations marked as proposals. |

## The §12a selection, made reproducible (finding on §12a)

- **Query** (logged as a scan row in the read log, "C8 window §12a: actor provenance (S0044 RD-6)"): `who.executed|who.authori[sz]ed|executed.*authori[sz]ed.*actor|actor provenance|on behalf of the (user|human)` over the allowed list. **26 hits.**
- **Selection:** the earliest relevant hit after S0044 is S0129 (L47, "who authorized it"); the latest is S2834 (L1871, "Who authorized the action?"). The phrase "actor provenance" itself occurs in neither. The query was broader than the topic's name, as the reviewer notes.
- **Limit:** the allowed list covers `docs/knowledgeos/`. The earlier, more direct source AMS lies outside it and is already in Ch. 1.

## Record corrections

- S0041, S0042, S0044 and S0045 were closed with "book: pending" because the sections were written after the whole window had been read. Their book locations: S0041 → §8.3; S0042 → §9.3 C5; S0044 → §4.3, §5.5; S0045 → §8.3, §9.3. S0043 → §8.3 C6.
- `tmp/tools/corpus_quote_check.py` does not handle elided quotes ("…"). Nine older Ch. 1 quotes fail it for that reason only; `close_source.py`'s check is the authoritative one.

## Not done
- S0129's R-CONFLICT and Execution Context ≠ Governance Evidence Context; S2834's Unknown ≠ Uncertain and its two disagreeing ladders. All four are in the map, not yet in the book.

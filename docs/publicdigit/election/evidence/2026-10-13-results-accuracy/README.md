# Results accuracy: known ballots produce exactly the expected official result, 2026-10-13

**Authority:** Human PO ("start the results-accuracy test").

**Test:** `tests/Isolated/Pbdigit274/Database/ResultsAccuracyTest.php`, run on `pbdigit274_isolated_test` only (identity confirmed). No production code was changed.

## Scenario
- **Election:**
  - P1 national, choose 1 of {A, B, C};
  - P2 national, choose 2 of {D, E, F};
  - P3 regional (Bayern), choose 1 of {G, H};
  - abstention enabled.
- **Casting:** every cast goes through the real voting route (`slug.vote.store`), each voter from its own IP.

| Voter | Region | P1 | P2 | P3 | Outcome |
|---|---|---|---|---|---|
| v1 | Bayern | A | D, E | G | counted |
| v2 | Bayern | A | D, F | abstain | counted |
| v3 | none | B | abstain | (not offered) | counted |
| v4 | Bayern | abstain | E, F | H | counted |
| v1 again | | | | | refused: "You have already voted in this election…" |
| invalid | Bayern | A **and** B | D, E | G | refused: "…does not match the ballot for this election…" |
| outsider | none | C | D, E | G (not their region) | refused: same ballot mismatch |

## Asserted
- **4 votes stored.**
- **The official result page** (`ResultController::index`, route `result.index`, after publishing) shows exactly:
  - total 4;
  - President: A 2 · B 1 · C 0 · abstain 1;
  - Committee: D 2 · E 2 · F 2 · abstain 1;
  - Bayern representative: G 1 · H 1 · abstain 1.
- **Each refusal is checked for its specific reason.** A one-off probe confirmed the reasons first; it was removed before this run.
- **Anonymity:** neither `votes` nor `results` has a column that could link a vote to a voter (`user_id`, `voter_id`, `voter_slug_id`, `code_id`, `client_ip`, `ip_address`).

## Run
`01-results-accuracy.out`: **2 tests, 27 assertions, exit 0.**

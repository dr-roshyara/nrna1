# PBDIGIT-332: Product Owner status decision on the April 2026 Contribution Points module

**Type:** Decision item (PO) · **Created:** 2026-11-07 · **Raised by:** the Principal (Decision B, 2026-11-07). Recorded by Claude, Session A.

| | |
|---|---|
| **Status** | `PROPOSED` · awaiting PO decision · **no change to the module authorised** |
| **Interim classification [D]** | **Historical experiment, pending formal Product Owner confirmation** (Principal Decision B, 2026-11-07) |
| **Workstream / Epic** | none assigned |

## 1. Subject

The nrna1 Contribution Points module (April 2026):
- tables `contributions` and `points_ledger`, and the column `users.leaderboard_visibility` (`database/migrations/2026_04_11_000001…`, `…000002…`);
- `app/Services/GaneshStandardFormula.php`, `ContributionPointsService`, `LeaderboardService`;
- `app/Http/Controllers/Contribution/ContributionController.php`;
- `resources/js/Pages/Contributions/*`;
- `tests/Feature/Contribution/*`;
- `developer_guide/contribution/*`.

## 2. Verified state

[F] Source: `docs/publicdigit/architecture/reviews/2026-11-07-social-contribution-cross-project-discovery.md` §4.1, §10.

- Members can submit a contribution. It stays `pending`: there is no verify, approve, reject or appeal route.
- `ContributionPointsService::awardPoints()` has no production caller.
- `proof_type` and `outcome_bonus` are chosen by the member, and `proof_path` is never written.
- The formula's weights are heuristic by its own comments.
- The developer guide describes admin review and award tiers that do not exist in the code.
- It conflicts in principle with `PBDIGIT-330` §7 (L109–110, "no arbitrary universal numerical scores"; self-reported, verified and reviewed records "must remain distinguishable").

## 3. Consequences of the interim classification [D]

- Not an approved product capability.
- The formula is not a foundation for `PBDIGIT-331`.
- The module is not deleted, modified, retired or migrated.
- Its implementation does not represent approved business rules.

## 4. Options for the PO (no recommendation beyond the Principal's interim classification)

1. Confirm it as a historical experiment and leave it in place, unused.
2. Treat it as a product capability to retain and evaluate.
3. Treat it as legacy, and open a separate, authorised retirement decision.

Each option has consequences for the routes and pages that remain reachable by URL, and for the developer guide, which currently describes non-existent features. Those consequences are listed here, not decided here.

## Traceability

Principal Decision B (2026-11-07) · cross-project discovery 2026-11-07 · `PBDIGIT-330` · `PBDIGIT-331`.

# PBDIGIT-278 item 8: per-IP vote limit and refusal responses, 2026-10-13

**Authority:** Human PO specification "PBDIGIT-278 — Item 8" (default limit 10; an election override wins; consistent across start, code entry and verification; readable refusals).

**Status:** implemented. **Decision (Human PO 2026-10-13): keep the two limits separate**; at code entry the stricter one applies.

## Runs
| # | File | Result |
|---|---|---|
| 01 | `01-unit-red.out` | `IpVoteLimitTest` before the service existed: RED (18 errors) |
| 02 | `02-flow-red.out` | `IpVoteLimitFlowTest` before the fix: **5 / 5 RED**, for the expected reasons: hidden default 7 at code entry; start never enforcing; old code-entry message; "System error" at verification; election override not enforced at start |
| 03 | `03-unit-green.out` | `IpVoteLimitTest`: **18 / 56 GREEN** |
| 04 | `04-flow-green.out` | `IpVoteLimitFlowTest`: **6 / 47 GREEN**. After the decision, the "below 10" test was split into "below both limits passes every step" and "the separate constitutional limit can be stricter at code entry" |
| 05 | `05-isolated-all.out` | the whole isolated suite: **100 / 537, exit 0** |

The isolated identity guard confirmed `pbdigit274_isolated_test` on every run. No other database was used.

## Decision: the two limits stay separate
At code entry, the **trust policy** (PBDIGIT-239 entry authorization → `TrustPolicyEvaluator`) applies its own constitutional per-IP limit: `elections.max_votes_per_ip`, default 6, scaled by evidence level. With 9 earlier votes from one IP it refuses ("Your access to voting cannot be granted based on constitutional verification…"). **Decided: keep them separate.** The trust policy is unchanged (rule 7). `IpVoteLimit` is applied consistently at start, code entry and verification (rule 5), and at code entry the stricter of the two limits applies. Pinned by `test_the_constitutional_trust_limit_stays_separate_and_can_be_stricter_at_code_entry`.

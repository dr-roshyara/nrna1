# PBDIGIT-236 — The regional ballot rests on the mutable, user-level `users.region` (D6)

**Type:** Architecture decision (domain authority) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-05
**Found by:** VE-01 design investigation (`docs/publicdigit/reviews/2026-10-05-ve01-ballot-integrity-design-and-adr-amendment-proposal.md`, §3, §8)
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Existing-story search (freeze rule):** no existing story covers the source of regional ballot eligibility; VE-01 (ballot integrity) consumes it but does not decide it.

| | |
|---|---|
| **Status** | 🟡 **OPEN — architecture decision required (D6).** Not authorised for implementation. |
| **Finding (FACT [S])** | `BallotAssemblyService::buildRegionalPosts()` selects regional posts by `posts.state_name = trim(users.region)`. `users.region` is a free-text, nullable attribute of the **user** (`create_uuid_users_table.php:15`, nullable since `2026_04_06_200001`), written at registration, by user import, set to `''` by social login (no regional posts), and editable afterwards; `ElectionMembership` has no region; nothing snapshots the region when a ballot is issued. `residence_geo_unit_id` exists in the Membership context but is not used by ballot assembly. |
| **Consequence** | The regional ballot is f(current `users.region`). If the region changes during a voting session, the ballot the voter saw and the ballot the final cast would validate against can differ (inference [I]). Regional eligibility is not election-specific. |
| **Options** | (a) current `users.region` at cast (today's behaviour) · (b) a region snapshot at ballot issue / code verification, stored voter-side · (c) an election-specific region on the membership (`ElectionMembership`) · (d) a geo-unit based model (`residence_geo_unit_id`) |
| **Constraints** | (b), (c), (d) need new data → schema decision; any snapshot must not create a voter↔ballot link (interim anonymity rule). |
| **VE-01 relation** | VE-01 (`PBDIGIT-237`) uses today's `users.region` as the existing source **for characterization only** and does **not** constitutionalize it; the final constitutional ballot authority depends on this decision. |

**Traceability:** VE-01 proposal §3, §8 · verification gate report §15–§17 · `BallotAssemblyService.php:60–80`.

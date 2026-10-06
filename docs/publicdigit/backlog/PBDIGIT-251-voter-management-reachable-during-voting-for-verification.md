# PBDIGIT-251 — Voter Management reachable during voting, for verification only

**Type:** Defect (lifecycle gate vs. business process) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-06
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable), found during PBDIGIT-247 GREEN
**Existing-story search (freeze rule):** PBDIGIT-213 (identity window `verify_voter_identity`, SetupAdministration → VotingActive), PBDIGIT-222 (capture IP), PBDIGIT-247 (capture window `capture_voter_ip`, same span). None owns the reachability of the **page** that hosts those actions.

| | |
|---|---|
| **Status** | 🟡 `OPEN` — not authorised; RED first |
| **Business process (PO 2026-10-06)** | Identity verification by video call **and** IP capture happen just before voting starts **or during voting**; voting is allowed only after verification, bound to the IP captured then. **Separate buttons** (identity, IP), not one combined action |
| **Finding [S]** | The Voter Management page `GET voters/manage` (`elections.voters.index`) is in the `election.state:import_voters` group. `EnsureElectionState` blocks **every** method (GET too) with 403 when `canManageVoters` is false, which is the case from **SetupNomination** onward. So from SetupNomination through VotingActive officers **cannot open the page**, although the identity endpoints (`verify_voter_identity`) and the capture endpoint (`capture_voter_ip`) accept requests until voting closes |
| **Constraint (PO 2026-10-06)** | During this window, voter **contact data (email, telephone) must not be changeable**; verification (identity, IP capture) **must** be allowed. Registration mutations (assign, import, remove, approve) stay governed by `import_voters` |
| **Design question** | The page needs its own read window (e.g. a `view_voter_management` operation, SetupAdministration → VotingActive) while its write actions keep their own gates. Which actions show / hide per state |
| **RED to write** | officer opens Voter Management while VotingActive → 200; identity and IP buttons usable; import / assign / remove still refused; no contact-data edit possible; Draft / closed states unchanged |

**Traceability:** PBDIGIT-213 D1 · PBDIGIT-222 · PBDIGIT-247 option (a) · `OperationCapabilityMapper` · `routes/organisations.php`.

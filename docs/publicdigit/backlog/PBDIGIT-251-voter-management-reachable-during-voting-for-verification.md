# PBDIGIT-251 — Voter Management reachable during voting, for verification only

**Type:** Defect (lifecycle gate vs. business process) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-06
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable), found during PBDIGIT-247 GREEN
**Existing-story search (freeze rule):** PBDIGIT-213 (identity window `verify_voter_identity`, SetupAdministration → VotingActive), PBDIGIT-222 (capture IP), PBDIGIT-247 (capture window `capture_voter_ip`, same span). None owns the reachability of the **page** that hosts those actions.

| | |
|---|---|
| **Status** | 🟢 **RESOLVED 2026-10-06** (GREEN, pending commit approval): Voter Management is readable in every state; verification, IP capture and two-person suspension work until voting closes; registration actions stay setup-only. Guide: `developer_guide/election/voter_admission/09_voter_management_lifecycle_access.md` |
| **Business process (PO 2026-10-06)** | Identity verification by video call **and** IP capture happen just before voting starts **or during voting**; voting is allowed only after verification, bound to the IP captured then. **Separate buttons** (identity, IP), not one combined action |
| **Finding [S]** | The Voter Management page `GET voters/manage` (`elections.voters.index`) is in the `election.state:import_voters` group. `EnsureElectionState` blocks **every** method (GET too) with 403 when `canManageVoters` is false, which is the case from **SetupNomination** onward. So from SetupNomination through VotingActive officers **cannot open the page**, although the identity endpoints (`verify_voter_identity`) and the capture endpoint (`capture_voter_ip`) accept requests until voting closes |
| **Constraint (PO 2026-10-06)** | During this window, voter **contact data (email, telephone) must not be changeable**; verification (identity, IP capture) **must** be allowed. Registration mutations (assign, import, remove, approve) stay governed by `import_voters` |
| **Design question** | The page needs its own read window (e.g. a `view_voter_management` operation, SetupAdministration → VotingActive) while its write actions keep their own gates. Which actions show / hide per state |
| **RED to write** | officer opens Voter Management while VotingActive → 200; identity and IP buttons usable; import / assign / remove still refused; no contact-data edit possible; Draft / closed states unchanged |

## Plan (proposed 2026-10-06, awaiting PO approval)

**PO requirement (2026-10-06):** *"this page should be accessible all the time to verify identity and IP address binding … even at the time of voting."*

**Design**

1. **New lifecycle operation `view_voter_management`, allowed in every state.** `GET voters/manage` moves out of the `import_voters` group into its own `election.state:view_voter_management` group. `import_voters` itself is unchanged.
2. **Write actions keep their own gates, unchanged:**
   - import / assign / bulk / remove / approve / suspend / proposals / export stay in `import_voters` (SetupAdministration only);
   - Verify Identity / Verify All / Cancel stay in `verify_voter_identity`;
   - Capture IP stays in `capture_voter_ip` (SetupAdministration → VotingActive).
3. **The page shows only what the server allows.** `ElectionVoterController::index` sends `capabilities: { manageVoters, verifyIdentity, captureIp }` from `OperationCapabilityMapper`, and `Voters/Index.vue` hides the actions whose capability is false. During voting the officer therefore sees Verify Identity, Cancel and Verify IP Address, but no add / import / remove / suspend.
4. **Contact data:** the page has no email / telephone editing, and none is added. A test asserts that the page offers no contact-data change and that registration mutations are still refused while voting.
5. **Authorisation unchanged:** `authorize('view', $election)` stays.

**RED (after approval)**

- **L1:** the Chief opens Voter Management while VotingActive → 200.
- **L2:** the page opens in Draft, SetupNomination, ReadyForVoting, Counting / ResultsPublished and Suspended → 200 ("all the time").
- **L3:** while VotingActive, Verify Identity and Capture IP succeed.
- **L4:** while VotingActive, assign, remove and import are refused (403), unchanged.
- **L5:** `capabilities` per state: during voting `manageVoters` is false and `verifyIdentity` / `captureIp` are true; after voting all three are false.
- **L6 (frontend):** with `manageVoters` false there is no add / import / remove / suspend control, while the verification buttons are present.
- **L7 (frontend):** the page has no email / telephone input.

**Progress**

- [x] Plan approved by the PO (2026-10-06).
- [x] RED written: `tests/Feature/Election/VoterManagementLifecycleAccessTest.php` (L1–L5) and `tests/Frontend/VoterManagementCapabilities.spec.js` (L6a–c, L7).
- **Failing as intended:**
  - L1 / L2: 403 during voting and in other states;
  - L5: `capabilities` missing;
  - L6a / L6b: management actions are shown regardless of capabilities.
- **Guards, passing today and to stay green:**
  - L3: verification and capture during voting already work (PBDIGIT-213 / 247 windows);
  - L4: assign / remove are refused during voting;
  - L6c: setup administration is unchanged;
  - L7: no email / phone input.
- [x] GREEN approved and implemented (2026-10-06):
  - `view_voter_management` (every state) and its own route group for `GET voters/manage`;
  - `capabilities` prop from `OperationCapabilityMapper`;
  - `Voters/Index.vue` shows actions per capability.
- **Decision during GREEN (PO 2026-10-06): "only suspension".**
  - Two-person suspension (propose / confirm / cancel proposal) is allowed **until voting closes**, through the new `suspend_voters` operation (`canManageVoters` OR SetupAdministration → VotingActive, which adds to the window it had before) and its own route group.
  - **Remove, approve, import and export stay setup-only, and are hidden and refused during voting.**
  - The single-officer `elections.voters.suspend` route stays in `import_voters`.
  - RED for it, then GREEN: L5 (`suspendVoters`), L8 propose and confirm while voting, L9 cancel while voting, L10 refused after voting, L11 approve refused while voting; L6a (frontend) updated.
- **PO note (not in scope):** if a voter must be removed, it should follow the four-eyes principle, like suspension. Today Remove is a single-officer action. The PO said to keep Remove hidden and only enable suspension. **No story was raised.**
- **Results:**
  - L1–L11 and L6a–c / L7 pass;
  - the suspension, admission, identity and capture suites pass;
  - frontend 309 passed / 3 skipped;
  - design-check 91;
  - the full regression is reported separately.

**Risks**

- **Wider exposure (VIS-D2):** `ElectionPolicy::view` lets any **active officer of any election in the organisation** view this page. It is not election-scoped. Keeping the page open in every state widens what that grants (voter names, emails, last-login IP). This is not changed here; it is a candidate for a follow-up.

**Traceability:** PBDIGIT-213 D1 · PBDIGIT-222 · PBDIGIT-247 option (a) · `OperationCapabilityMapper` · `routes/organisations.php`.

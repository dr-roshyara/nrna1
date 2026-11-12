# PBDIGIT-341: voter import privacy remediation, assessment and plan

| | |
|---|---|
| **Status** | **Assessment and proposed plan for Principal review.** Not an approved EP-01 plan; **nothing is authorised**; no code, data or test changed. |
| **Commission** | "PBDIGIT-341 — Voter Import Privacy Remediation Assessment and Implementation Plan", Principal, 2026-11-10 (Commission 2). Commission 1 (independent review of P-I1 (d)) needs a reviewer outside the Claude family and is **not** done here. |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0). Date prefix added, as for every file in `architecture/reviews/`. |
| **Git state** | branch `election-flow-updated`, HEAD `754144edb`. |
| **Evidence limits** | Code reading only. **No test was run**, and no data was queried except a read-only check of column names. The runtime effects stated below follow from the code, and must be confirmed by the tests of §7 before any fix is accepted. |

**Labels:** **FACT** (code read 2026-11-10) · **RAT** · **WD** · **P** · **REC** · **UNKNOWN** · **PRINCIPAL**.

## 1. Verified findings
The mode is chosen by `VoterSourceStrategy::fromElection($election)` (`app/Http/Controllers/Election/VoterImportController.php:106-111` preview, `:139-150` import). Routes: `organisations/{organisation}/elections/{election}/voters/import[/preview]`, guarded by `EnsureOrganisationMember`, `EnsureElectionState:import_voters` and `authorize('manageVoters')`.

| # | Finding | Mode | Evidence (FACT) |
|---|---|---|---|
| F-1 | **Account-existence check through validation messages.** "User '{email}' does not exist in the platform." versus "'{email}' is not an active member of this organisation." / "… is not an eligible voter …", returned per row in the preview JSON. | membership | `app/Services/VoterImportService.php:155-190` (`validateRow`), `:68-100` (`preview` returns `errors`) |
| F-2 | Membership mode **does not** link accounts across organisations: it enrols only accounts that are eligible voters of this organisation. | membership | `:180-187` (`isEligibleVoter`), `:107-145` (`import`) |
| F-3 | **Account-existence check through preview status:** "Existing user" / "Will be created". | election-only | `:194-235` |
| F-4 | **Account-existence check through result counts:** "%d users created, %d already existing, %d invitation emails queued". | election-only | `VoterImportController.php:141-147` |
| F-5 | **Silent cross-organisation linking.** For an **existing** account, election-only import creates the importing organisation's `user_organisation_roles` (role `member`), its `organisation_users` (`active`) and an **active** voter `election_memberships`, with no acceptance and **no notice** (an invitation is sent only to new or unverified accounts). | election-only | `:267-334` |
| F-6 | The role `member` from F-5 is what `EnsureOrganisationMember` checks, so the person also **gains access to the importing organisation's workspace** and sees it in "My organisations". | election-only | F-5 plus middleware on organisation routes |
| F-7 | Accounts created by the import are marked **verified at creation** (`email_verified_at => now()`) without any verification; the login address is only proven later, when the invitation link sets a password. | election-only | `:268-278` |
| F-8 | The voter list sends **every voter's** central `name`, `email`, `current_ip` and `updated_at` to the browser in the page data, although only the IP-capture dialog shows the IP. | both | `app/Http/Controllers/ElectionVoterController.php:71`; `resources/js/Pages/Elections/Voters/Index.vue:442-448` |
| F-9 | `current_ip` is the **last sign-in IP**, written at every login anywhere on PublicDigit, and `updated_at` reflects any change to the account. Neither is scoped to the organisation. | both | `app/Listeners/RecordLastLoginTimestamp.php:23` |
| F-10 | The legitimate use: IP capture for voter IP-binding takes `current_ip` for an **active voter of this election** (PBDIGIT-307/315 policy). After F-5, a silently linked account **is** such a voter. | both | `app/Http/Controllers/Election/VoterIpCaptureController.php:40-75` |
| F-11 | The CSV export contains name, e-mail, status and assignment date (no IP). | both | `ElectionVoterController.php:388-415` |
| F-12 | For an existing account, the name shown is the **central** `users.name` (set by the person or by whichever import created the account); the importing organisation's typed name is ignored. | election-only | `firstOrCreate` keeps existing attributes (`:268-278`); F-8 |
| F-13 | Raw exception messages are returned per row ("Row n: " + `$e->getMessage()`). | election-only | `:336-339` |
| F-14 | No existing test asserts non-disclosure. `CsvVoterImportTest`, `ElectionOnlyModeTest` and `VoterEligibilityTest` (`test_import_preview_*`) pin eligibility behaviour, not privacy. | — | tests read, not run |

## 2. Privacy impact and affected journeys
| What the importing organisation can learn or do | Membership mode | Election-only mode |
|---|---|---|
| Whether an e-mail address has a PublicDigit account | **yes** (F-1) | **yes** (F-3, F-4) |
| Create a relationship without the person's acceptance | no (F-2) | **yes**: affiliation, workspace access, voter status (F-5, F-6) |
| See data supplied elsewhere or produced by activity elsewhere | name and last-login IP of its own eligible voters (F-8, F-9) | **yes**: central name (F-12), last-login IP and last update from activity anywhere (F-8, F-9), including for silently linked people |
| Capture that IP into its election's IP binding | for its voters | **yes**, for silently linked people too (F-10) |

**Affected people:**
- anyone with a PublicDigit account whose address appears in another organisation's upload;
- particularly members of more than one organisation, a common case in diaspora communities;
- families sharing one e-mail address, where the "existing account" may belong to a different family member.

**Conflicts with accepted decisions:**
- **D-75 §2.6 (RAT):** no account linking based on matching e-mail (F-5).
- **D-75 §2.4 / C-1 (RAT):** no cross-organisation disclosure (F-1, F-3, F-4, F-8, F-9, F-12).
- **D-101 (WD):** affiliation is the organisation's own record of a relationship, not something created by matching another organisation's account (F-5).
- **D-102 (WD):** login-address verification is Identity's (F-7).
- **D-100 (WD):** account creation is not affiliation.

## 3. Domain ownership
Election-only import today does **four contexts' work in one Election service**:
1. Identity: find or create an account by e-mail;
2. Organisation: create an affiliation (`user_organisation_roles`, `organisation_users`);
3. Election: the electorate entry (`election_memberships`);
4. Communication: the invitation e-mail.

| Act | Owner (target) | Rule for the remediation |
|---|---|---|
| Finding or creating an account | Identity (D-102) | never by matching e-mail across organisations (D-75); permanent fix: claim (I-TDD-02) |
| Recording that the organisation has a voter in capacity "voter" | Organisation (D-101) | its own record, from its own list; never by adopting another organisation's account data |
| The electorate entry | Election | from the organisation's own records; status changes only by the person's acceptance or the organisation's rule |
| Who sees what about a voter | the organisation, for data it holds | central account data (`users.name`, `current_ip`, `updated_at`) is **not** the organisation's |
| Ballots | Voting | untouched (VO-1); nothing in this plan reaches Voting |

**The remediation must not:**
- make Identity the owner of affiliation;
- turn account creation into admission;
- let Election decide affiliation;
- expose another organisation's records;
- change eligibility facts of running or closed elections.

## 4. Recommended remediation

### 4.1 Containment (small, quick, no change to who can vote)
| # | Control | Effect | Side effects |
|---|---|---|---|
| C-1 | **One neutral validation message** in membership mode for "no account" and "not an eligible member": "cannot be added to this election's voters" | closes F-1 | officers lose the hint "this person has no account"; they can still check their own member list |
| C-2 | **One neutral preview status** in election-only mode ("will be invited") and **one neutral result count** ("n rows accepted, m rejected") | closes F-3, F-4 | the invitation count is no longer shown separately |
| C-3 | **No raw exception text** to the browser; a generic row error; details to the log | closes F-13 | — |
| C-4 | **Remove `current_ip` and `updated_at` from the voter-list page data**; the IP-capture dialog fetches the IP **on demand**, with the same authorisation (active voter of this election) | closes the bulk exposure in F-8; F-10 unchanged | one extra request when capturing |

C-1…C-4 do not change who is enrolled, invited or eligible.

### 4.2 Correction of silent linking (changes behaviour; needs decisions)
| # | Control | Effect |
|---|---|---|
| R-1 | For a row whose address belongs to an **existing account with no affiliation to this organisation**: create nothing in Organisation or Election yet; send that account an **acceptance invitation** bound to that account (accepting requires being signed in **as that account**: `auth()->id() == invitation.user_id`, so a forwarded link is useless); on acceptance, create the organisation's records and the voter entry. | closes F-5, F-6 and F-12 for new imports |
| R-2 | **Indistinguishable states for the organisation:** new and existing rows both appear as **"invited"** until the person acts (sets a password, or accepts). | otherwise "active" versus "pending" would leak existence again (F-3 by another route) |
| R-3 | Accounts created by the import are **not** marked verified until the invitation link is used. | closes F-7; consistent with D-102 |

**R-2 changes the electorate semantics of election-only elections:** invited-but-inactive people would not be active voters. That is Election's and the Principal's decision (**PRINCIPAL PR-2**).

### 4.3 Permanent correction
- **Identity's claim capability (I-TDD-02, after P-I1)** replaces account creation and linking by e-mail in this import. The organisation records its own voters with its own `PersonRef`, and the person connects by redeeming a claim.
- **Election** enrols from the organisation's records.
- **IP-binding** takes an IP observed at an **organisation-scoped** event (for example the voter opening this election) instead of the platform-wide last-login IP (**PRINCIPAL PR-3**: this touches the PBDIGIT-286/315 IP policy).

## 5. Existing-data risk (assessment only; nothing modified)
| Risk | Detection (read-only query, to be run by the Principal against production) | Handling (P; decisions: PR-4) |
|---|---|---|
| Silently linked existing accounts | `election_memberships em` joined to `users u` for election-only elections, where `u.created_at` is clearly before `em.created_at` and no `voter_invitations` row exists for (`em.election_id`, `em.user_id`); cross-check `user_organisation_roles.created_at ≈ em.created_at` for that organisation | **Closed or running elections: do not touch** (integrity and audit). Future elections: switch these entries to "invited" and send acceptance invitations (R-1). Record a privacy finding. |
| Organisation access gained by F-6 | the same rows' `user_organisation_roles` (role `member`) created by the import | revoke only after the person declines or ignores the acceptance (PR-4); never silently |
| Central names shown to other organisations | not detectable after the fact | containment C-4 and R-1 stop it going forward |
| Historical IP exposure | IP-binding records for the accounts of row 1 | keep, for election integrity; note in the finding; PR-3 decides future capture |
| Notifying affected people | — | **PRINCIPAL + data protection (PR-5):** whether the affected people must be informed |

The table names for IP-binding records were not traced here (UNKNOWN); the detection queries must be written in the EP-01 plan and reviewed before running.

## 6. Proposed sequence (each step its own EP-01 plan and Principal approval)
| Step | Content | Class | Entry |
|---|---|---|---|
| 0 | Principal decides PR-1…PR-5 (§8) | — | this report |
| 1 | C-1, C-2, C-3 (messages, counts, errors) | **containment** | EP-01 plan |
| 2 | C-4 (IP on demand) | **containment** | EP-01 plan |
| 3 | Run the §5 detection queries (read-only) | assessment | Principal runs or authorises |
| 4 | R-1, R-2, R-3 | correction | PR-1, PR-2; EP-01 plan |
| 5 | Handling of existing affected records | correction | PR-4, PR-5 |
| 6 | Claim flow replaces e-mail linking; organisation-scoped IP | **permanent** | I-TDD-02 accepted; PR-3 |

**Out of this plan, still under PBDIGIT-341:** membership approval (`MembershipApplicationController.php:221-224`), participant add (`OrganisationParticipantController.php:73`), the `ImportUsers` command (`:565`). Each needs the same analysis.

## 7. Tests and acceptance criteria (written RED first in each step)
| # | Test (property, not class names) | Step |
|---|---|---|
| T-1 | For the same uploaded row, an address **with** an account and one **without** produce byte-identical preview responses (membership mode) | 1 |
| T-2 | The same for election-only preview and result message (status, counts) | 1 |
| T-3 | No row error contains exception text or SQL | 1 |
| T-4 | The voter-list page data contains no `current_ip` and no `updated_at` | 2 |
| T-5 | The IP-capture endpoint still returns the IP only for an active voter of this election; for others it is refused | 2 |
| T-6 | Election-only import of an existing account creates **no** `user_organisation_roles`, `organisation_users` or active `election_memberships` row before acceptance | 4 |
| T-7 | The acceptance link works only when signed in as the invited account; for another account it fails with a neutral message; a second acceptance is idempotent | 4 |
| T-8 | New accounts: an invitation is sent; the account is not marked verified until the link is used; the person then becomes an active voter | 4 |
| T-9 | Membership mode: eligibility results unchanged (existing `VoterEligibilityTest` import cases stay green) | 1, 4 |
| T-10 | Organisation isolation: an organisation's voter list and export never contain data of a person not in its own voter records | 2, 4 |
| T-11 | Running or closed elections: an import attempt is refused as today (`EnsureElectionState:import_voters`), and existing voter entries are unchanged by the migration of step 5 | 4, 5 |
| T-12 | Concurrency: two simultaneous imports of the same address create one invitation and no duplicate records | 4 |
| T-13 | Audit: each acceptance, decline and invitation is logged with organisation, election and time; no cross-organisation data in the log | 4 |
| T-14 | Retry: a failed invitation e-mail is retried by the existing job; the voter stays "invited" | 4 |

## 8. Risks, dependencies, Principal decisions
| ID | Decision | Options | REC |
|---|---|---|---|
| **PR-1** | Authorise containment C-1…C-4 now? | yes / no | **yes**: no change to who can vote |
| **PR-2** | In election-only elections, do **invited** people count as voters before they accept or set a password? | count them (the current electorate size is unchanged, but the state is distinguishable unless both kinds count) / count only accepted | count **only accepted**, the same for new and existing people (R-2); confirm it fits your election-only rules |
| **PR-3** | Future IP-binding source | platform last-login IP (today) / an IP observed at an organisation-scoped event | organisation-scoped, decided with the PBDIGIT-286/315 IP policy |
| **PR-4** | Existing silently linked records in future elections | leave / switch to "invited" and ask the person / remove | switch to "invited" and ask; never touch running or closed elections |
| **PR-5** | Inform affected people? | yes / no / after the data-protection assessment | after a data-protection assessment |

**Risks:**
- R-2 can lower turnout figures in election-only elections, because people who never accept are no longer voters.
- C-4 needs a small change to the IP-capture dialog.
- Coordinating with Identity: R-1 is an **interim** acceptance mechanism; it must not become a second permanent linking system beside the claim (I-TDD-02 should replace it).

## 9. Containment versus permanent correction
- **Containment** (steps 1–2): stops the existence checks, the raw errors and the bulk IP exposure. It does **not** stop silent linking.
- **Interim correction** (step 4): stops silent linking for new imports, with an acceptance bound to the account.
- **Permanent correction** (step 6): the claim-based connection owned by Identity, enrolment by Election from the organisation's own records, and an organisation-scoped IP source.

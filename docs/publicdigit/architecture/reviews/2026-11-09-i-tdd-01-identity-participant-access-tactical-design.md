# I-TDD-01: Identity participant access (tactical design)

| | |
|---|---|
| **Status** | **PROPOSED tactical design.** Decides nothing; ratifies nothing; authorises no implementation. Not independently reviewed (Claude family). |
| **Commission** | Principal, 2026-11-09: "continue" — the Identity participant-access design (P-1 decided), started with **P-I1 (K4) open**. |
| **Derives from** | XD-3 ARB act (D-75, **RAT**) with XD-3a/b/c; `./2026-11-09-identity-participant-access-strategic-discovery.md` (P); `docs/publicdigit/reviews/2026-11-01-identity-affiliation-discovery-evaluation.md` (research, items 4 and 6). |
| **Method** | `engineering/knowledge/methodology/DDD_Tactical_Governance_Principles.md`: APP, VODP, ASP, ADP, DMT, RMSP. Chain: aggregate → responsibilities → protected truths → invariants → value objects → events → commands → repositories → domain services. |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0); `architecture/reviews/`, beside the discovery (no Identity folder exists; none invented) |
| **Snapshot** | branch `election-flow-updated`, HEAD `754144edb`. No `app/Contexts/Identity` exists (FACT). |

**Labels:** **RAT** · **WD** · **FACT** · **P** · **REC** · **UNKNOWN** · **PRINCIPAL** · **SEAM** (a point where an open decision plugs in; the design around it does not depend on the answer).

## 0. Findings that shape the design (stated first)

1. **Recovery is the main takeover risk, not sign-in (P).** If an organisation could issue a claim code that **re-binds a Person who already has an Account**, one officer of one organisation could hand that person's whole identity, **including access to their other organisations**, to someone else. That would defeat D-75 §2.3–§2.4 from inside a single organisation. The design therefore forbids it (invariant **INV-ID-6**) and moves account recovery without the login address into an Identity-owned process (**P-I3**).
2. **Claim-first registration removes routine merges (P, from the evaluation's item 6).** If a plain registration always creates a new Person, every later claim produces two Persons for one human. The g15 model found that in 100 of 100 claim sequences. Registration that **starts from a claim code** binds the new Account to the existing Person, so no second Person is created. Plain registration without a code remains, but its Person is **unanchored** (§2.1) and can be absorbed by a first claim without a merge.
3. **Sign-in by one's own login address is authentication, not e-mail matching (P).** D-75 forbids linking or creating accounts by matching e-mail. Looking up the Account whose own login identifier the person types, then checking the password, is not linking. The design keeps that line explicit (INV-ID-3), because PBDIGIT-341 shows how easily the two get confused.

## 1. Scope

**In scope:**
- Person;
- Account (sign-in credential and lifecycle);
- organisation-issued Claim and its redemption;
- registration with and without a claim;
- account recovery by own login address;
- the working-context selection seam;
- sessions on shared phones;
- the Identity side of Account suspension and deletion.

**Out of scope:**
- the L1/L2 reference mechanism (**SEAM** `ReferenceIssuer`, P-I2);
- affiliation discovery (**SEAM** `AffiliationDiscovery`, INV-AFF-1, P-I2);
- K4 (**SEAM** `ClaimSubject`, P-I1);
- organisation-local profiles (owner pending, INV-AFF-1);
- data migration from `users`;
- legal basis (P-I4);
- any technology or API.

## 2. Aggregates

### 2.1 Person
| Step | Content |
|---|---|
| **Responsibility** | Be the one continuous identity of a human across organisations, known to nobody but Identity. |
| **Protected truths** | (T1) a Person's identity continues unless Identity's explicit merge act says otherwise (C-5); (T2) a Person carries no descriptive data (XD-3b §2a); (T3) no organisation is recorded on the Person (C-4). |
| **Invariants** | **INV-ID-1:** a Person holds only `PersonId`, `state` (`active` / `mergedInto(PersonId)`) and `anchored` (bool). **INV-ID-2:** a merged Person accepts no new binding; its references resolve to the surviving Person, and only inside Identity. |
| **`anchored`** | `true` once any organisation reference has ever been issued for this Person. It records **that** some organisation knows the Person, never **which**. It lets an unanchored Person (from plain registration) be absorbed by the first claim without a merge. **P; needs an ARB check against C-4** (it is one bit, not an index). |

### 2.2 Account
| Step | Content |
|---|---|
| **Responsibility** | Authenticate a human who controls it; grant nothing beyond "this is the holder". |
| **Protected truths** | (T4) at most one Account per Person, and an Account belongs to exactly one Person (RAT §2.6, B2); (T5) authenticity of the credential; (T6) suspension and deletion only by Identity, with authority, reason and audit (R-XD3c-2); (T7) Account changes never delete the Person or organisational records (R-XD3c-4). |
| **Invariants** | **INV-ID-3:** an Account's login identifier is used **only** to authenticate its own holder, never to find, create or bind any other record. **INV-ID-4:** binding an Account to a Person happens only through claim redemption or claim-first registration. **INV-ID-5:** `suspended` and `deleted` are reached only by commands that carry authority evidence and a reason; neither is reached by ending a membership or revoking an organisation's access (R-XD3c-5). |
| **State** | `active` → `suspended` → `active`, or `deleted` (terminal). |

### 2.3 Claim
| Step | Content |
|---|---|
| **Responsibility** | Let an organisation give one of its own people a one-time proof of possession, so that the person can bind an Account to their Person without e-mail matching. |
| **Protected truths** | (T8) single use; (T9) expiry; (T10) revocable by the issuing organisation until redeemed; (T11) the code proves possession for **one** organisation and reveals nothing else (RAT §6); (T12) a claim never moves an existing binding. |
| **Invariants** | **INV-ID-6 (the takeover guard):** a Claim can bind only to a Person that has **no Account**, or to the Person already bound to the redeeming Account. Otherwise redemption fails with a neutral message. Re-binding a Person that already has an Account is not a claim; it is account recovery (§5, P-I3). **INV-ID-7:** only a hash of the code is stored; the code is shown once to the issuer. **INV-ID-8:** after redemption, expiry or revocation, the Claim keeps no link to the subject beyond what audit needs (C-4 minimisation). **INV-ID-9:** failed redemption attempts are throttled per code and per client. |
| **SEAM `ClaimSubject`** | **K4 (P-I1).** Under eager creation (K4 i or iii) the subject is a `PersonId`. Under lazy creation (K4 ii) it is the issuing organisation's sealed local reference, and Identity creates the Person when the claim is redeemed, then issues that organisation its `PersonRef`. The invariants above hold either way. |

### 2.4 Rejected or deferred candidates (ASP)
| Candidate | Decision | Reason | Reversal condition |
|---|---|---|---|
| `WorkingContext` / "organisation session" aggregate | **rejected** | protects no domain truth; choosing an organisation grants nothing (I-5). It is a session value object; every request is re-authorised by the owning context. | if an organisation rule ever makes the act of entering an organisation business-significant (for example attendance) |
| `Affiliation` inside Identity | **rejected** | would rebuild the forbidden index (C-4); affiliations belong to organisations | INV-AFF-1 ruling |
| `LoginEmail` as identity evidence | **rejected** | e-mail never establishes identity (D-75) | none |
| Social identity (Google, Facebook) | **removed** | P-I5 decided 2026-11-09 (PBDIGIT-340) | a new decision with linking only from a signed-in Account |
| `Merge` aggregate | **deferred** | C-5 needs an explicit merge act, but its review policy is open (XD-3b [R]: review where election records are affected) | when the first duplicate is confirmed |

## 3. Value objects (VODP: each strengthens an invariant)
| Value object | Invariant(s) it serves |
|---|---|
| `PersonId` (opaque, internal to Identity) | INV-ID-1, INV-ID-2 |
| `PersonRef` (opaque, per organisation; resolvable only by Identity) | RAT §2.2–§2.3 |
| `LoginIdentifier` (the holder's own address, normalised) | INV-ID-3 (used for authentication only) |
| `ClaimCode` (shown once) and `ClaimCodeHash` (stored) | INV-ID-7 |
| `ClaimWindow` (issued, expires) | T9 |
| `ClaimSubject` (**SEAM**, K4) | INV-ID-6 |
| `AuthorityEvidence` (who, which power, reference) | INV-ID-5 (R-XD3c-2) |
| `SessionPolicy` (lifetime, remember-me allowed) | P-I3; shared phones |
| `WorkingContext` (organisation id, own `PersonRef`) | I-5: carries no authority |

## 4. Domain events (business occurrences only)
| Event | Business meaning | Audience |
|---|---|---|
| `ClaimIssued` | an organisation gave one of its people a way in | issuing organisation only |
| `ClaimRedeemed` | that person now has access to that organisation | issuing organisation only, with its own `PersonRef`; nothing about other organisations |
| `ClaimRevoked` / `ClaimExpired` | the way in was withdrawn or lapsed | issuing organisation only |
| `AccountSuspended`, `AccountReinstated`, `AccountDeleted` | Identity acted on an account, with authority | Identity's audit; organisations learn only what affects their own references |
| `PersonsMerged` | Identity confirmed two Persons are one human | Identity; each affected organisation only for its own reference |

**Not events (ASP):** signing in, choosing an organisation, password reset requested (application or security log, not business), reconstituting an Account.

## 5. Commands (business intentions)
| Command | Actor, channel | Authority | Result |
|---|---|---|---|
| `IssueClaim(organisation, subject)` | officer, **desktop** (DD-13) | the organisation's competence (Governance `AuthorityCheckPort`) | `ClaimIssued`; the code is shown once |
| `RevokeClaim` | officer, desktop | as above | `ClaimRevoked` |
| `RegisterWithClaim(code, credential)` | person, **phone** | possession of the code | Account created and bound (INV-ID-4, INV-ID-6); `ClaimRedeemed` |
| `RedeemClaim(code)` while signed in | person, phone | possession + own Account | bound if INV-ID-6 holds; an unanchored own Person is absorbed; `ClaimRedeemed` |
| `RegisterAccount(credential)` (no code) | person, phone | none | Account + **unanchored** Person; no affiliation |
| `ResetPassword` via own login address | person, phone | control of that address | credential replaced; never binds or re-binds (INV-ID-3) |
| `RecoverAccess` without the login address | person + Identity process | **P-I3** (open) | must satisfy INV-ID-6: no single organisation can cause a re-binding |
| `RequestAccountReview(reason)` | an organisation | right to **request** only (R-XD3c-3) | review case at Identity |
| `SuspendAccount`, `ReinstateAccount`, `DeleteAccount` | Identity | `AuthorityEvidence` (R-XD3c-2) | lifecycle events; organisational records untouched (R-XD3c-4) |
| `MergePersons` | Identity | explicit act, with review where election records are affected | deferred (§2.4) |

**Application-level, not domain:**
- `SignIn` (authenticate, then start a session under `SessionPolicy`);
- `SelectWorkingOrganisation` (choose from `AffiliationDiscovery`; set `WorkingContext`);
- `SignOut` (end the session, clear `WorkingContext`).

## 6. Repositories (RMSP: minimal surface)
| Repository | Operations | Not offered (deliberately) |
|---|---|---|
| `PersonRepository` | `get(PersonId)`, `save(Person)` | any search; any "find by organisation" |
| `AccountRepository` | `get(AccountId)`, `findForAuthentication(LoginIdentifier)`, `findByPerson(PersonId)`, `save` | lookup by name or e-mail for any purpose but authenticating the typed identifier |
| `ClaimRepository` | `findByCodeHash(hash)`, `get(ClaimId)`, `save` | listing claims of a Person; listing across organisations |

## 7. Domain services and ports
| Service or port | Why it exists | Note |
|---|---|---|
| `ClaimRedemption` (domain service) | redemption touches Claim, Account and Person, and must keep all three truths (INV-ID-4, INV-ID-6, INV-ID-2) | the one place where binding happens |
| `ReferenceIssuer` (**SEAM**, port) | issue and resolve `PersonRef` for an organisation | L1 or L2 behind it (P-I2); only Identity calls it |
| `AffiliationDiscovery` (**SEAM**, port) | after sign-in, list **the signed-in person's own** organisations for selection | INV-AFF-1 (P-I2); the result never leaves the person's session |
| `AuthorityCheckPort` (consumed) | officers issuing or revoking claims act with authority | existing Governance port (Membership kernel uses it) |

## 8. The participant journeys on a phone (from the discovery, §4)
| # | Situation | Commands and checks |
|---|---|---|
| A-1 | returning member | `SignIn` → (one organisation) `WorkingContext` set → pages re-authorise per request |
| A-2 | two organisations | `SignIn` → `AffiliationDiscovery` (**SEAM**) → `SelectWorkingOrganisation`; switching shows only the person's own list |
| A-3 | imported, never registered | officer `IssueClaim` (desktop) → code delivered by the organisation → person `RegisterWithClaim` (phone) → `ClaimRedeemed` to that organisation only |
| A-4 | public applicant approved | Membership admission (RA:72) → the organisation `IssueClaim` for the new member (replaces e-mail reuse, G-2, PBDIGIT-341) → as A-3 |
| A-5 | forgot password | `ResetPassword` by own login address |
| A-5b | lost the login address | `RecoverAccess` (**P-I3**); never via one organisation's claim (INV-ID-6) |
| A-6 | shared family phone | `SessionPolicy` (P-I3): no "remember me" by default for participants; `SignOut` clears everything; the PWA stores nothing personal (mobile baseline §5.3) |
| A-7 | installed PWA | same as the browser; the iOS storage behaviour is UNKNOWN (verify at Stage 2) |

## 9. Threats and how the design answers them
| # | Threat (XD-3 §5) | Answer |
|---|---|---|
| TH-1 | one organisation takes over a person's identity through a re-issued claim | INV-ID-6; recovery is Identity's (P-I3) |
| TH-2 | a forwarded or leaked claim code is used by someone else | single use, short `ClaimWindow`, revocable, throttled (INV-ID-7, INV-ID-9); the organisation sees `ClaimRedeemed` and can request review (R-XD3c-3). Optional organisation-local second check before issuing (organisation's own data, not Identity's): **REC** |
| TH-3 | cross-organisation inference from errors or previews | neutral failure messages; events only to the issuing organisation; PBDIGIT-341 interim fix |
| TH-4 | duplicate Persons from independent imports | eager K4 creates them; lazy K4 avoids them at import; either way merges stay explicit (C-5) |
| TH-5 | e-mail confusion between family members | INV-ID-3: an address authenticates its holder only; it never binds |
| TH-6 | stale access after leaving an organisation | the owning organisation revokes its access; the Account is untouched (R-XD3c-5) |

## 10. Existing mechanisms (DMT classification, FACT → conclusion)
| Mechanism | Evidence | Classification |
|---|---|---|
| `voter_invitations` token set-password (`VoterInvitationController.php:34-62`) | single-use, expiring token; but bound to an account **pre-created at import by e-mail** | (3) a partly modelled business intention: the precursor of `Claim`. Its pre-created account conflicts with INV-ID-4. |
| `organisation_invitations` accept (`ParticipantInvitationController.php:103-125`) | binds to **whoever is signed in**; no Person | (3) bearer-claim intention without INV-ID-6; to be replaced by `RedeemClaim` |
| member invitation accept (`OrganisationMemberInvitationController.php:110-190`) | compares the invitation e-mail with accounts | conflicts with INV-ID-3; `PBDIGIT-341` |
| `users.organisation_id` + session `current_organisation_id` | one "current" organisation per account | (1) implementation convenience; becomes `WorkingContext` (session only, not stored on the Account) |
| e-mail reuse in import and approval | `VoterImportService.php:217-226`; `MembershipApplicationController.php:221-224` | conflicts with INV-ID-3 and INV-ID-4; `PBDIGIT-341` |
| `PasswordResetController` | reset via the account's own address | consistent with INV-ID-3 |

## 11. Open decisions this design isolates
| # | Decision | Seam | Blocks implementation of |
|---|---|---|---|
| **P-I1** | K4: Person at import (eager) or at claim (lazy) | `ClaimSubject` | `IssueClaim`, A-3, A-4 |
| **P-I2** | INV-AFF-1 and L1/L2 (ARB) | `ReferenceIssuer`, `AffiliationDiscovery` | A-2 (multi-organisation selection) |
| **P-I3** | recovery without the login address; participant session policy | `RecoverAccess`, `SessionPolicy` | A-5b, A-6 |
| new **P-I7** | ARB check: is the one-bit `anchored` flag compatible with C-4? | Person | absorbing an unanchored Person (§0.2) |

## 12. Readiness and the first implementation slice (proposal, not authorised)
- **Implementable now, independent of the open decisions:** the Account lifecycle separation (INV-ID-5, R-XD3c-5), and `ResetPassword` and `SignIn` kept within INV-ID-3. These are mostly already true; the work is tests that pin them.
- **The first useful slice for members:** `Claim` with `IssueClaim`, `RevokeClaim`, `RegisterWithClaim` and `RedeemClaim`. It needs **P-I1**. Without multi-organisation discovery it serves A-3 and A-4 for people in one organisation.
- **Before any slice:** an EP-01 plan; the Identity bounded context's code placement (`app/Contexts/Identity`, new) is itself an architecture decision for the Principal.

## Traceability
XD-3 act §2–§6 (D-75); XD-3a C-1…C-5; XD-3b §1 (duplicates, K4 options i–iii), §2, §2a; XD-3c R-XD3c-1…5; affiliation evaluation §4 items 1–5 and item 6; the strategic discovery (I-1…I-9; A-1…A-8); PBDIGIT-340 (removed), PBDIGIT-341; mobile baseline §5.3, K-3, K-4.

**Supersession note (2026-11-11, recorded from the PublicDigit baseline; append-only):**
- **K4 / P-I1 are decided by D-113** (reference issued at import without a Person; Person created at registration and valid claim; the claim is tied to the organisation's `PersonRef`).
- **`ClaimRedeemed` means routing only:** the organisation's `PersonRef` is bound to a registered Person, and requests may be routed there. **It grants no access, membership, voting, office or authority** (multi-database design §13.1).
- Line 93's "now has access" and the K4 SEAM text are superseded accordingly. **P-I2 and C-5 remain open in this session.**

# Identity: participant access (strategic discovery assessment)

| | |
|---|---|
| **Status** | **PROPOSED assessment.** Decides nothing; ratifies nothing; changes no canonical record. Not independently reviewed (Claude family). |
| **Commission** | Principal instruction, 2026-11-09, Part E: "Identity participant access" strategic discovery. No implementation. |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0); `architecture/reviews/` |
| **Snapshot** | branch `election-flow-updated`, HEAD `e2051cbbe` (working tree carries PBDIGIT-338/339, uncommitted) |
| **Not a new baseline** | The Identity baseline is the **XD-3 ARB act** (`docs/publicdigit/reviews/2026-11-09-identity-xd3-arb-act.md`, D-75). This document applies it to the participant journey and reports evidence. Affiliation discovery mechanics are in `docs/publicdigit/reviews/2026-11-01-identity-affiliation-discovery-evaluation.md` (not repeated). |

**Labels:** **RAT** ratified (ARB act) · **WD** accepted working decision · **FACT** verified in the repository on this date · **P** proposed · **REC** recommendation · **UNKNOWN** · **PRINCIPAL**.

## 0. Disagreements and corrections (stated first)

1. **XD-3a, XD-3b and XD-3c are accepted, not merely proposed.** The XD-3 act (D-75, 2026-11-09) states: "XD-3a, XD-3b and XD-3c accepted by append-only notes". Each record carries that note: XD-3a `:106`, XD-3b `:130`, XD-3c `:91`. Their original headers still say PROPOSED, and so does `docs/publicdigit/architecture/strategic-design-status.md:60` (last changed 2026-11-08). Those are stale, not contrary decisions. **Still open** by the act's own §3: the mechanism (L1 or L2), K4, INV-AFF-1, legal basis, retention, and the account-security policy.
2. **The recorded Google sign-in "takeover" is not on a live route (FACT, correction).**
   - XD-3 §4, MAP §32 and the mobile baseline cite `GoogleController.php:44` (`User::updateOrCreate(['email' => …])`). That controller's routes have been **commented out since 2022** (`routes/user/googleRoutes.php:15-16`, last change `3eca8998f`, 2022-05-27). No registered route reaches it.
   - The live routes `login/google` and `login/google/callback` use `GoogleLoginController`. It matches by **`google_id`**, never by e-mail (`GoogleLoginController.php:33`).
   - **The live path has different defects** (`PBDIGIT-340`):
     - a new Google user gets the stored constant password `'dummypass'` as **plain text** (no `hashed` cast in `User`; assigned at `:60`);
     - when the e-mail already belongs to an account (for example an imported voter), the unique e-mail constraint fails and `dd($e->getMessage())` shows the database error, including the e-mail, to the visitor (`:70`);
     - the takeover code still exists and would be live again if re-routed.
   - **Consequence:** the strategic conclusion stands (no linking by e-mail, D-75), but "a live e-mail takeover through Google" overstates the evidence. The ratified act is not edited here; an append-only correction note is a **PRINCIPAL** choice (§6, P-I6).
3. **The real live conflicts with D-75 item 6 are elsewhere (FACT, `PBDIGIT-341`):** several flows resolve or reuse accounts by **matching e-mail**.
   - Voter import reuses an existing account and tells the importing organisation "Existing user" (`app/Services/VoterImportService.php:217-226`). An organisation can therefore learn that an address already has a PublicDigit account (cross-organisation inference).
   - Membership approval reuses or creates by e-mail (`MembershipApplicationController.php:221-224`).
   - Adding a participant looks the account up by e-mail (`OrganisationParticipantController.php:73`).
   - The `ImportUsers` command does the same (`:565`).

## 1. The business problem Identity must solve

A real person, often a diaspora member on a phone, must be able to **get into PublicDigit and act in the right organisation**:
- without PublicDigit creating, linking or taking over accounts by guessing from e-mail or name;
- without one organisation learning anything about the person's other organisations.

In a political party this matters more than usual: membership of a party, faction or diaspora association can be politically sensitive, and families share phones.

## 2. Capabilities and responsibility boundaries

| Capability | Owner | What it may know | Status |
|---|---|---|---|
| Person identity (`PersonId`, bare) | Identity | nothing about organisations | **RAT** (D-75 §2.1) |
| Per-organisation reference (`PersonRef`) | Identity issues; each organisation holds only its own | only Identity can resolve | **RAT** (§2.2–§2.3) |
| Account (credentials, sign-in) | Identity; zero or one per Person | authenticates; grants nothing | **RAT** (§2.6) |
| Person ↔ Account association | Identity, only by an **organisation-issued claim code** | the code proves possession, not e-mail | **RAT** (§2.6; XD-3b §2a) |
| Registration without a claim | Identity: an Account with a new Person and no affiliation | — | P (XD-3b) |
| Account recovery | Identity | must not become linking by e-mail | open: account-security policy (OQ-3c-1, OQ-3c-2) |
| Affiliation discovery at sign-in ("which organisations am I in?") | **INV-AFF-1, referred to the ARB**; mechanism L1/L2 open | must not create a cross-organisation index beyond C-4 | **open** |
| Organisation selection (working context) | channel/session concern using the discovery result | **grants no authority, creates no membership** | P |
| Names, contact details | organisation-local profile; owner pending (INV-AFF-1, proposed Organisation) | never shared across organisations | **RAT** that it is local (§2.5); owner open |
| Membership facts and lifecycle | Membership | `PersonRef` only | **RAT** (RA) |
| Affiliation records, structure, belonging | Organisation | its own people only | **WD** (D-3, D-22, D-80) |
| Platform access roles (`user_organisation_roles`) | platform access, **not** membership or office | — | FACT: the role `member` here is platform access; the dashboard test's person C has the role and no membership |
| Official authority | Governance | — | **WD** |
| Election entitlement | Election | facts at τ | **RAT** (RA:110) |

## 3. Relationships with Organisation and Membership

```
 Account ──(claim code redeemed)──▶ Person (Identity) ──issues──▶ PersonRef@OrgA   PersonRef@OrgB
    │ authenticates                                                │                │
    ▼                                                              ▼                ▼
 Session: working context = one organisation          OrgA: affiliation, profile,   OrgB: (cannot see A)
 (selection grants nothing)                           Membership facts, roles
```
- **Membership** receives only a `PersonRef`. Admission still needs the organisation's decision with authority evidence (RA:72). Signing in or choosing an organisation never creates membership or voting entitlement.
- **Organisation** owns who is affiliated, and in which unit. An address never establishes belonging (D-80). The organisation issues the claim code for its own people.
- **Election** evaluates entitlement from facts; Identity contributes nothing but the reference.

## 4. The participant access journey on a phone

| # | Situation | Today (FACT) | Target under D-75 (P) | Blocked by |
|---|---|---|---|---|
| A-1 | returning member, one organisation | e-mail and password; requires a verified e-mail (`LoginController.php:72`); lands on the organisation page; verified 2026-11-09 at 390 px with synthetic users | unchanged in principle | — |
| A-2 | member of two organisations | one "current" organisation per account (`users.organisation_id`; session `current_organisation_id`); `/my-organisations` lists them | after sign-in, a list of **own** affiliations only, then a choice; a link from one organisation never shows another | **INV-AFF-1**, L1/L2 |
| A-3 | imported by an organisation, never registered | account pre-created at import by e-mail; one-time invitation token sets a password (`VoterInvitationController.php:34-62`) | the organisation issues a **claim code**; the person registers or signs in and redeems it; no account exists before that | **K4** (eager or lazy Person creation) |
| A-4 | public applicant, after approval | approval creates or reuses an account by e-mail (G-2) | approval issues a claim code (M-TDD-01 J-1) | K4; Membership cutover |
| A-5 | forgot password | e-mail reset link (`PasswordResetController`) | resetting **one's own** account through its own verified address is recovery, not linking (P). Lost access to that address needs a non-e-mail path (organisation-assisted re-claim) | account-security policy |
| A-6 | shared family phone | session 120 minutes, not ended on close (`config/session.php:34-36`); "remember me" available | clear sign-out; no "remember me" by default on shared devices; nothing personal kept by the PWA (mobile baseline §5.3) | account-security policy |
| A-7 | installed PWA | same session cookie | same; whether iOS shares the browser's sign-in is **UNKNOWN** (verify at Stage 2) | — |
| A-8 | Google sign-in | live path matches `google_id`; new users get a plain-text constant password; collisions show a raw error | decide whether social sign-in stays; if it stays, link to an existing account only after signing in to it (never by e-mail) | **P-I5**; `PBDIGIT-340` |

## 5. Security and privacy invariants (for the tactical design)

| # | Invariant | Basis |
|---|---|---|
| I-1 | No account creation, linking or takeover by matching e-mail or name | **RAT** D-75 §2.6 |
| I-2 | Only Identity resolves a `PersonRef`; nothing else can find a person's other organisations | **RAT** §2.3 |
| I-3 | No cross-organisation disclosure in pages, previews, errors, logs, events or e-mails (for example "Existing user" in an import preview) | **RAT** §2.4, §5 |
| I-4 | A claim code proves possession for **one** organisation and reveals nothing about others | **RAT** §6 |
| I-5 | Signing in, selecting an organisation or redeeming a code grants **no** authority, membership or voting entitlement | **WD** (CP-1, K-1) |
| I-6 | Ending membership, removing access, suspending and deleting an account are separate acts | **RAT** §2.7 |
| I-7 | Merges only by Identity's explicit act, reviewed where election records are affected | **RAT** C-5; XD-3b [R] |
| I-8 | Nothing personal is served from a cache shared between people | evidence: `PBDIGIT-339` |
| I-9 | No mobile-specific identity system | **WD** (CP-1) |

## 6. Unresolved strategic decisions

| # | Question | Owner | Options → consequence | REC | When |
|---|---|---|---|---|---|
| **P-I1** | **K4:** when an organisation imports a person who has not registered, is a Person created then (eager) or only when they redeem a claim code (lazy)? | PO + ARB + data protection (open in the act) | eager → duplicates across independent imports need merges; lazy → contradicts the import clarification of 2026-11-01 | needs your business view; the tactical design can isolate it behind one operation | before A-3 is built |
| **P-I2** | **INV-AFF-1:** who owns affiliation discovery, and with which mechanism (L1 stored mapping, L2 keyed derivation)? | ARB (referred) | decides A-2 | the 2026-11-01 evaluation holds the analysis | before A-2 is built |
| **P-I3** | **Account-security policy:** recovery without e-mail, second factor for officers, session length on shared phones | PO (OQ-3c-1, OQ-3c-2) | decides A-5, A-6 | organisation-assisted re-claim when the e-mail is lost; shorter participant sessions | before A-5, A-6 |
| P-I4 | Legal basis, controllers and processors (OQ-3a-1) | PO + data protection | — | carried | before production use |
| **P-I5** | Does social sign-in (Google) stay? | PO | keep → `PBDIGIT-340` fixes, link only from a signed-in account; remove → simpler, fewer paths | remove until the claim flow exists, or fix `PBDIGIT-340` first | soon |
| P-I6 | Append a correction note to the XD-3 act §4 (the takeover route is unrouted; the live path has other defects)? | Principal (ARB record) | note / leave | append a note; the decision itself is unaffected | any time |

## 7. Readiness for tactical DDD design

**Ready, within limits (REC):** a tactical design of participant access can start now for what is decided:
- the Account and Person separation;
- claim-code redemption (one organisation, one code, one Person);
- sign-in;
- organisation selection as a pure working context fed by an affiliation port;
- separate end, removal, suspension and deletion acts;
- the invariants I-1…I-9.

**Kept behind explicit seams until decided:**
- K4 (P-I1): one "register imported person" operation, with the eager/lazy choice inside it;
- the affiliation discovery mechanism (P-I2): an Identity port, L1 or L2 behind it;
- recovery and session policy (P-I3).

**Not needed for the design:** technology, an API or a mobile framework.

**Order with the mobile work:**
1. `PBDIGIT-340` and `PBDIGIT-341` triage (live defects, independent of design).
2. Identity participant-access tactical design.
3. Membership approval issues claim codes (cutover, Slice 4).
4. PWA (Stage 2).

## Traceability
- **Records:** XD-3 act (D-75) §1–§6; XD-3a `:106`, XD-3b `:38-59, :79, :130`, XD-3c `:91`; `2026-11-01-identity-affiliation-discovery-evaluation.md`; `strategic-design-status.md:60`; DR §11 D-75, D-80; mobile baseline (same date).
- **Code (FACT):**
  - routes `login/google*` → `GoogleLoginController`;
  - `routes/user/googleRoutes.php:15-16` (commented);
  - `GoogleLoginController.php:28-72`; `app/Models/User.php:141-145` (no `hashed` cast);
  - `users.email` unique (users migration `:16`);
  - `VoterImportService.php:217-226`; `MembershipApplicationController.php:221-224`; `OrganisationParticipantController.php:73`; `ImportUsers.php:565`;
  - `VoterInvitationController.php:34-62`; `ParticipantInvitationController.php:103-125` (binds to whoever is signed in);
  - `LoginController.php:38-73`; `config/session.php:34-36`.
- **Runtime evidence:** synthetic sign-in journey on a throwaway database, 2026-11-09 (`PBDIGIT-338`).

# Identity: Principal decision dossier

| | |
|---|---|
| **Status** | **Decision preparation.** It decides nothing. Recommendations are marked **REC**; every decision is the Principal's (D-94: the Principal is also the ARB). |
| **Commission** | Deliverable B of the "Identity Strategic Reconciliation and Principal Decision Dossier" commission, 2026-11-10. Builds on `./2026-11-10-identity-strategic-reconciliation-addendum.md` (A). |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |
| **Independence** | Self-authored (Claude family); an outside review is still needed before ratifying anything that follows from these decisions. |

## 0. In one page

**Decide before I-TDD-02 (the next tactical design):**
- **P-I1:** when an organisation adds a person to its own records, does PublicDigit create that person's central identity record at that moment, or only when the person first redeems their code?

**Decide before the first implementation slice (not before I-TDD-02):**
- **P-I11:** where Identity code lives;
- **P-I12:** what a new account gets at registration, now that the "platform organisation" is not an Organisation (D-100);
- **P-I7:** may a claim absorb a person record created by plain registration, if no organisation refers to it?

**Can be decided now, independently:**
- **P-I10:** a small privacy fix in voter import.

**Can be deferred, provided I-TDD-02 keeps a clean seam:**
- **P-I2:** how a person's organisations are found at sign-in;
- **P-I3:** recovery without the login address, and session rules;
- **P-I8:** Election's reference;
- **P-I9:** the "same natural person" question for Reference Geography;
- XD-PROV, the merge review policy and the legal basis (before production).

**Business decisions vs technical recommendations:**
- **Business:** P-I1, P-I3, P-I9, P-I12, XD-PROV, merge policy, legal.
- **Architectural, with business effect:** P-I2, P-I7, P-I8.
- **Technical:** P-I10 (authorisation of a fix), P-I11.

---

## P-I1: when is a person's identity record created for someone an organisation imports?

| | |
|---|---|
| **Business question** | NRNA imports its member list. Many members have never used PublicDigit. Should PublicDigit create each one's central identity record **now**, or **only when they first redeem the code NRNA gives them**? |
| **Current authoritative position** | K4 is open (XD-3 act §3, **RAT** that it is undecided). The Principal's import clarification of 2026-11-01 assumes creation at import (XD-3b §1). Membership needs an opaque `PersonRef` per organisation for every member (RA, quoted in XD-3 §2.2). |
| **Already decided** | One canonical Person; a different `PersonRef` per organisation; no matching by e-mail or name; merges only by an explicit Identity act (D-75; C-5); organisations own affiliation and profile (D-101). |
| **Undecided** | eager or lazy; and whether Identity records which organisation a not-yet-registered person belongs to (that part is P-I2). |
| **Alternatives** | **(a) Eager:** a Person and the organisation's `PersonRef` are created at import. **(b) Lazy:** the organisation keeps its own local placeholder until the claim is redeemed; then the Person and `PersonRef` are created. **(c) Eager, discovery later:** as (a), but Identity records nothing usable for sign-in discovery until the claim is redeemed. This splits "reference" from "discovery"; how it is done depends on P-I2. |
| **Business consequences** | **(a)/(c):** imported members are full Membership and Election subjects immediately (voter lists, terms, fees refer to them from day one), even if they never sign in; that matches how parties work, since many members never log in. **(b):** imported members have no `PersonRef` until they claim, so Membership and Election records for them need a placeholder and **re-keying** when they claim. Re-keying recorded membership facts conflicts with their immutability (RA). |
| **Architectural consequences** | **(a)/(c):** two organisations importing the same human create **two Persons** (no matching allowed), so an explicit merge process is needed before imports grow. Under (a)/(c) INV-ID-6 is enforceable as designed (the claim names an existing Person). **(b):** no import duplicates; INV-ID-6 changes shape; every context needs placeholder handling. |
| **Security and privacy** | **(a):** Identity holds a bare record for people who never consented (a bare `PersonId`, no data, C-2). Whether it also holds their organisation depends on P-I2; C-4 allows a minimised, reviewed index (addendum §2). **(c)** avoids a discovery entry for people who never registered. **(b)** holds least, but spreads placeholder identities across contexts. |
| **Dependencies** | P-I2 (how (c) is realised); merge policy (for (a)/(c)); P-I8. |
| **Operational burden (solo developer)** | **(a)/(c):** one model everywhere, plus a merge tool later. **(b):** placeholders and re-keying in Membership and Election (the highest burden, and it touches ratified Membership). |
| **REC** | **(c): eager creation of the Person and `PersonRef`; nothing usable for discovery until the claim is redeemed.** Merges are handled by a later, explicit Identity process with review where election records are affected. |
| **Blocks I-TDD-02?** | **Yes**: it fixes the Claim's subject and the shape of INV-ID-6. |
| **Blocks the first slice?** | Yes. |
| **Decision required** | Choose (a), (b) or (c). |

## P-I2: how are a person's organisations found at sign-in?

| | |
|---|---|
| **Business question** | When Ram signs in, how does PublicDigit show him the two organisations he belongs to, without keeping a political-affiliation list that could leak? |
| **Current position** | Ownership settled (D-101, **WD**): each organisation owns its affiliations. Mechanism open; L1 and L2 both admissible within C-1…C-5. C-4 allows a minimised, data-protection-reviewed index. |
| **Already decided** | Ownership; no disclosure between organisations; INV-AFF-1's "no persisted register" not adopted. |
| **Undecided** | **L1:** Identity stores a minimal mapping (Person → organisation references). **L2:** references are computed with a secret key, and Identity asks organisations at sign-in. **L3** needs a B1 amendment. |
| **Alternatives and consequences** | **L1:** simple and fast; a central index exists, so breach impact is a list of a person's organisations (C-4 review needed). **L2:** no stored index; sign-in fans out to organisations (latency, partial outages, key management; feasibility **not established**, evaluation 2026-11-01). |
| **Evidence still needed** | From the evaluation's §4: organisations expected in 1–3 years; physical topology (one cluster or separate servers); peak and election-day sign-in volume. Also the data-protection review that C-4 requires. |
| **REC** | **Defer.** Build the first slice for people in **one** organisation (the claim needs no discovery); collect the three facts; decide before multi-organisation sign-in. |
| **Blocks I-TDD-02?** | **No**, if I-TDD-02 specifies the `AffiliationDiscovery` and `ReferenceIssuer` port contracts so either mechanism fits. |
| **Blocks the first slice?** | No (single organisation). |
| **Decision required** | Later: L1 or L2, after the facts and the review. |

## P-I3: account recovery and session rules

| Situation | Who acts | Recommendation (REC) | Decision needed? |
|---|---|---|---|
| forgot password; still controls the login address | the holder | reset by own address (exists, `PasswordResetController`) | no |
| **lost the login address** | Identity, not an organisation | Identity-run recovery with stronger evidence, or no recovery (new account, then fresh claims from each organisation, each only for its own reference) | **yes (business)** |
| changing one's login address | the holder | verify the new address while signed in; never a linking act | no |
| shared family phone | the holder | participant sessions shorter than 120 minutes; no "remember me" by default; sign-out clears everything; the PWA stores nothing personal | **yes (the session length)** |
| suspension | Identity, with authority and reason (R-XD3c-2) | an organisation may **request** it (R-XD3c-3) | procedure only |
| deletion | the holder (exists: soft delete, `DeleteAccountController`) or Identity with authority | add a reason and audit; organisational records stay (R-XD3c-4) | with XD-PROV |
| organisation-assisted recovery | — | **an organisation may**: request review; issue a claim only for a person **without** an Account (INV-ID-6); revoke its own access. **It must never**: change a login address, reset a password, suspend or delete an Account, or re-bind a person to another Account. | confirm the line |

| | |
|---|---|
| **Blocks I-TDD-02?** | No; I-TDD-02 must state the recovery boundary (INV-ID-6). |
| **Blocks the first slice?** | The session rule matters before phones are invited (PWA). |
| **Decision required** | (1) recovery when the address is lost; (2) the participant session length; (3) confirm the "must never" list. |

## P-I7: may a claim absorb a person record that no organisation refers to?

| | |
|---|---|
| **Business question** | Someone registers on their own (a public applicant, or a demo user), then later receives NRNA's code. Should the code simply attach to their existing account, without a formal merge? |
| **Current position** | I-TDD-01 proposed a one-bit `anchored` flag; the addendum (S-6) shows that under L1 the flag is redundant and under L2 it is minimal. |
| **Alternatives** | **absorb**: if no organisation refers to the account's Person, the claim replaces it, with no merge. **always merge**: every such case goes through the merge process. |
| **Consequences** | absorb is simple for members; always-merge makes the most common onboarding path a manual review. |
| **Privacy** | knowing "some organisation refers to this Person" is one bit (C-4: minimised). |
| **Depends on** | P-I2 (how "refers to" is known). |
| **REC** | **absorb**, with the test "no reference has ever been issued for this Person". |
| **Blocks I-TDD-02?** | No (I-TDD-02 can state the rule, conditional on P-I2). |
| **Blocks the first slice?** | Yes, if the first slice lets already-registered people redeem codes. |

## P-I8: what does Election receive from Identity?

| | |
|---|---|
| **Current position** | Context map R-03: "not established". Election evaluates eligibility (RA:110); Voting never receives identity (VO-1, **RAT**; D-44). |
| **Alternatives** | `PersonRef` of the organisation only · an election-specific reference derived from it |
| **Business consequence** | elections are run inside one organisation, so that organisation's `PersonRef` identifies the voter to Election. Duplicates **within one organisation** (the same human imported twice) are Election's double-vote risk (XD-3b §1). |
| **REC** | **`PersonRef` only**; Identity tells Election nothing beyond merges of its own references; nothing reaches Voting (credentials per D-44). |
| **Blocks** | neither I-TDD-02 nor the first slice. |

## P-I9: "same natural person?" for Reference Geography (D-90)

| | |
|---|---|
| **Business question** | When a second steward approves a geography change, PublicDigit must be sure it is a different human (D-88), and must flag the approval if later found otherwise (D-90). Who may ask Identity that, and what may Identity answer? |
| **Current position** | D-88 and D-90 (**WD**) assume Identity can say it; no contract exists. |
| **Facts** | Stewards are appointed by the platform owner (D-84). With one Account per Person, sameness only becomes known through a merge (C-5). |
| **Alternatives** | **(a) namespace-scoped:** Geography has its own reference namespace; Identity answers only "same / different / unknown" for two references in **that** namespace, and notifies Geography only about its own references after a merge. **(b) any context may ask** about any two references (rejected: lets organisations probe each other). **(c) no query; merges only** (Geography learns nothing actively; D-90's flag could not be applied). |
| **Minimum safeguards (REC)** | callers: Geography's governance function only; recorded purpose required; every question logged; "unknown" (for example a pending merge review) counts as "not established as the same"; an organisation can never ask. |
| **REC** | **(a)** |
| **Blocks** | not the claim slice; needed before a second steward is appointed. |

## P-I10: interim treatment of e-mail matching (PBDIGIT-341)

| | |
|---|---|
| **Risk (FACT)** | Voter import tells the importing organisation "Existing user" for addresses that already have an account (`VoterImportService.php:217-226`), and reuses that account across organisations. Every import can leak whether a person uses PublicDigit. |
| **Smallest safe fix (REC)** | (1) The preview shows the same status for every valid row ("will be invited"), so it never reveals whether an account exists. (2) Reuse stays until the claim flow replaces it: changing who is linked now would break current imports without a replacement. |
| **Blocks** | nothing. It is independent and needs only an EP-01 plan and authorisation. |
| **Decision required** | Authorise step (1) now? |

## P-I11: where Identity code lives

| | |
|---|---|
| **Evidence** | Contexts live under `app/Contexts/<Name>/{Domain,Application,Infrastructure}` (FACT: Membership, Geography). Layer rules: no Laravel in Domain (`.claude/CLAUDE.md`). |
| **REC** | `app/Contexts/Identity`, same layering. Laravel's authentication stays as the infrastructure adapter behind an authentication port (keeps Keycloak or similar optional; maturity review §7). |
| **Blocks** | not I-TDD-02 (domain design is placement-independent); **yes** the first slice. |

## P-I12: what a new account gets at registration

| | |
|---|---|
| **Current position (FACT)** | `RegisterController.php:41-63` sets the account's organisation to the "platform organisation" and creates a `member` role in it; tests pin this (`RegistrationAndLoginTest`, `LoginOrganisationAccessTest`). The demo flow and the welcome page use it. |
| **Conflict** | D-100: a platform record is not an Organisation, so this is an affiliation with something that is not an Organisation. D-101: affiliation is an organisation's own record. Account creation ≠ affiliation ≠ membership. |
| **Alternatives** | **(a)** keep as is (contradicts D-100/D-101). **(b)** replace it with a **platform access setting** for the demo and welcome flow: not an affiliation, not membership, not shown as an organisation. **(c)** drop it; demo access by its own route. |
| **REC** | **(b)** in the claim slice, or just before it; carry nothing of today's behaviour forward as a domain rule. |
| **Blocks** | not I-TDD-02 (which must state "registration creates no affiliation"); the first slice touches registration, so yes. |
| **Decision required** | (b) or (c); whether demo onboarding must survive unchanged. |

## Other open items (decide before production, not now)
| Item | Question | REC |
|---|---|---|
| XD-PROV | ratify organisation-local provenance and deletion authority? | after the legal input (OQ-3b-2) |
| Merge policy | review every merge, or only where election records are affected? | review where election records are affected; required before large eager imports |
| Legal (OQ-3a-1) | legal basis, controllers, processors | before any production use with real members |

## Dependencies
P-I1 → (shape of) INV-ID-6, merge policy, P-I8 · P-I2 → P-I7, how P-I1 (c) is realised, P-I3 partly · P-I9 independent of the claim slice · P-I10, P-I11 independent.

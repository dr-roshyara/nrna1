# I-TDD-02: Identity claims and bindings (tactical design), revision 3

| | |
|---|---|
| **Status** | **PROPOSED, revision 3. Awaiting independent review.** It decides nothing, ratifies nothing and authorises no implementation. Written by Claude, **not independent** |
| **Commission** | **D-195**; bounded corrections I-TDD-02-R1 (→ revision 2) and **I-TDD-02-R2 (→ this revision)**, Principal 2026-11-11 / 12 |
| **Earlier revisions (preserved unchanged)** | rev 1: `…-tactical-design-rev1.md`, SHA-256 `fdc3b0c0f5d2b8e2777c832951759bc2c9be30a778f60b1c8db51fdbc718df24`<br>rev 2: `…-tactical-design-rev2.md`, SHA-256 `98177e1163da230a1d86b5e57c7f4bc0fde037d1fc441d0a9deeaf430ea23e89`<br>**Revision 3 replaces both** |
| **Starting point** | scope `2026-11-10-identity-i-tdd-02-scope.md` (**IS**) as trimmed by the pre-TDD adversarial review §7–§8 (**AR**); I-TDD-01 (**I1**, proposed) where not superseded |
| **Binding** | **D-75** (XD-3 RAT, incl. XD-3c R-XD3c-2 / -3); D-100, D-101; **D-110, D-111, D-113**; D-148; D-164; D-166; D-174 … D-193 (Platform revision 11, incl. X-2, R-B, H-2); D-179; D-191; **D-194**; **D-195** |
| **Entry criteria** | E-1 met by D-113 (option (d)); E-2 not required under (d) (AR §8); E-4 met by D-195. **No conflict** |

**Labels:** [AD] accepted decision · [BF] fact · [AC] architectural conclusion · [TD] tactical design · [TU] proof · [EXT] another context · [P] proposal · [OQ] Principal decision · UNKNOWN.

## Change summary

**Revision 2 → revision 3** (findings of the Principal's review of revision 2):

| # | Finding | Change | Section |
|---|---|---|---|
| 1 | R-2 corrected | **kept**; adds that an Account reference is not authority | §8 |
| 2 | **P-B option B1 conflicted with D-191, Platform H-2 and D-166, not only D-164** | **Founding information split into three categories.** Documents are never held centrally: their fingerprints are taken at application, and the documents are submitted straight into the new organisation database at provisioning. **Options rebuilt:** B1 holds only minimal contact data centrally, which needs a D-164 decision; **B2 holds nothing centrally**. **The recommendation is now B2** (smallest footprint) | §9, §21 P-B |
| 3 | **P-A authority model inconsistent** | issue, replace, revoke and security response analysed separately; **one consistent Governance act-kind model per option; no authority inferred from having issued; Identity gets no organisational authority**; a security lock offered only under an approved policy (R-XD3c-3) | §6.2, §10, §21 P-A |
| 4 | **registration retry not demonstrably idempotent** | durable evidence named: the redeemed claim, plus a **minimal `RegistrationCompletion` record** (hashes and Account reference, 24 h); **six cases stated** | §5, §14 |
| 5 | reissue / revoke / redeem concurrency and authority | one lock rule, one transaction per act, the authority per act; a redeemed claim cannot be revoked | §6.2, §14 |
| 6 | claim window rule | who sets it, how it is read, what the maximum is; **the D-171 citation corrected** (precedent for the method only) | §6.3, §21 P-C |
| 7 | Platform initial-contact dependency | recorded precisely; **not solved here** | §19 D-7 |
| 8 | **identity limitation** | stated: a claim proves possession of an invitation, **not that two Accounts are different humans** | §0, §18 |

**Wording:** "access codes" is replaced throughout by **claim codes**. **A claim establishes an identity binding; it grants no membership and no access.**

**Revision 1 → revision 2 (kept):**
- R-2 returns the bound Account (not a handle);
- the bootstrap path;
- one central registration transaction;
- issue / retry / reissue distinguished;
- deferred seams made visible;
- D-194 roles.

## 0. Findings first

1. **[AC] Identity establishes *who*, never *what they may do*.** A binding says: this Account's Person is organisation O's `PersonRef` R. **It grants no belonging, membership, office, authority or access** (D-164; ISO-7; XD-3 §2.4).
2. **[AD] Two resolutions, two consumers:**
   - **R-1** Account → R in O, for every organisation context;
   - **R-2** R → the bound **Account reference**, for Platform only (X-2).

   **An Account reference is not authority:** Platform still checks the current designation and its approval rules (D-174, D-183, D-186).
3. **[EXT] Ordinary claims need Governance act kinds that do not exist yet** (P-A). Until then **only the bootstrap claim can be issued**, so **no one recorded by an organisation can bind their login to that record**, though they can still register an Account.
4. **[EXT] Platform R-B** references initial contacts by Account before the organisation database exists, **without saying how they are identified without e-mail matching.** It is Platform's to answer (§19 D-7).
5. **[AC] Limitation:**
   - **A claim proves possession of one organisation's invitation, redeemed with a verified login. It does not prove that two different Accounts are two different humans.**
   - One human could register twice with two login addresses and redeem claims of different organisations with each, giving two Persons. **Only redeeming within one organisation is protected** (INV-BND-2).
   - Detecting that two Accounts are one human needs identity verification or merge processes **outside this design** (C-5; P-I3). **D-110's "one registration" is the rule; this mechanism cannot enforce it across organisations by itself.**

## 1. Purpose and scope

**Purpose:** let a person who registered once act, with their own identity, inside an organisation that recorded them. **No e-mail matching, no takeover and no cross-organisation disclosure.**

**In scope:**
- PersonRef issuance;
- claims;
- redemption;
- bindings;
- R-1, R-2;
- bootstrap claim;
- refusal when Identity is unavailable;
- neutral errors;
- audit;
- transactions;
- the merge rule (only);
- the users-mapping principle.

**Out:**
- P-I2 mechanism (seam);
- P-I3 recovery;
- merge procedure;
- identity verification;
- Election and Voting;
- Finance;
- authentication provider;
- user interface;
- code and migrations;
- Platform changes.

## 2. Business language

| Term | Meaning | Source |
|---|---|---|
| **Person** | the identity of a human **who has registered**; only `PersonId` and state | D-113, XD-3 §2.1 |
| **Account** | the login a Person controls; authenticates its holder, grants nothing else | D-110 |
| **Account reference** | an Account's identifier, as Platform stores it for contacts and the counterparty | Platform R-B, M-6 |
| **`PersonRef` (R)** | organisation O's opaque number for one person it recorded; issued at entry, before any Person exists | D-113 |
| **Claim code** | a one-time, organisation-issued proof of possession for one R. **It establishes an identity binding; it grants no membership or access** | D-113, XD-3b §2a |
| **Binding** | "R of O is Person P"; **not a merge** | D-113 |
| **Bootstrap claim** | the one claim per organisation issued without an officer, for the founding registrar | D-148, D-178, D-195 |

**Five separate concepts [AD]:**
1. identity resolution (Identity);
2. belonging (Organisation);
3. membership (Membership);
4. authority (Governance);
5. access and entitlement (each consuming context).

## 3. Responsibility

| Identity owns (central, D-164) | Identity does not own |
|---|---|
| Person, Account, authentication, sessions | the organisation's record, contacts, profile (D-101) |
| `PersonRef` issuance | belonging, membership, office, authority, access |
| claims, bindings, their technical security | **who may issue, replace or revoke claims (Governance, P-A)** |
| R-1, R-2; sending D-179 security notices | routing, contact lists, grants (Platform) |

## 4. Aggregates and invariants

| Aggregate | Invariants |
|---|---|
| **Person** | **INV-ID-1:** only `PersonId` and state (`active` / `mergedInto`). **INV-ID-2:** a merged Person takes no new binding |
| **Account** | **INV-ID-3:** the login identifier authenticates its holder only (sole exception: D-179 notices). **INV-ID-4:** bound only through redemption or claim-first registration. **INV-ID-5:** suspension and deletion only under Identity's lifecycle rules with authority, reason and audit (R-XD3c-2). **INV-ACC-1:** login identifier unique |
| **IssuedReference** | **INV-REF-1:** R is issued to exactly one organisation. **INV-REF-2:** issuance idempotent on (O, entry E), **whether stored (L1) or derived (L2)** |
| **Claim** | **INV-ID-6:** binds only an unbound R. **INV-ID-7:** only the code's hash is stored. **INV-ID-8:** after it ends, only what audit needs is kept. **INV-ID-9:** failed attempts throttled. **INV-CLM-1:** at most one active claim per (O, R). **INV-CLM-2:** at most one bootstrap claim per O is ever redeemed. **INV-CLM-3:** a redeemed claim is terminal; it cannot be revoked, replaced or redeemed again |
| **Binding** | **INV-BND-1:** an R is bound to at most one Person. **INV-BND-2:** a Person is bound to at most one R of O; a second is refused and reported to O (D-113); **post-merge exception, §18**. **INV-BND-3:** created only by redemption; never edited; ended only by Identity's explicit acts (deferred) |

**Rejected:**
- an Affiliation inside Identity;
- a working-organisation aggregate;
- e-mail as identity evidence;
- social sign-in;
- `anchored`;
- `ContactHandle` (withdrawn in revision 2).

## 5. Models

| Model | Fields (minimal) |
|---|---|
| Person | `PersonId`, state |
| Account | `AccountId`, `PersonId`, login identifier (verified), credential, state (`active`, `suspended`, `deleted`) |
| PendingRegistration | registration-token hash, login identifier, verification state, expiry, optional claim-code hash. **No Person, no Account** |
| **RegistrationCompletion** (new) | **registration-token hash → `AccountId`, completedAt**. **Kept 24 hours, then deleted.** It holds no login identifier, name or organisation; it exists only to answer a retry (§14) |
| IssuedReference | R, O, issuedAt. **SEAM P-I2** (stored or derived; must keep INV-REF-2) |
| Claim | `ClaimId`, O, R, kind (`ordinary` / `bootstrap`), code hash, issuedAt, expiresAt, state (`issued`, `redeemed`, `revoked`, and **`locked` only if P-A4 is approved**), issuance id, authority reference, `replacesClaimId`, attempt count |
| Binding | O, R, `PersonId`, boundAt, `ClaimId` |

**Expiry is time-derived:**
- a claim is **active** iff `state = issued ∧ now < expiresAt`;
- `now` is the central database clock in the redemption transaction;
- **an `expired` mark is for reporting only.**

## 6. Claims and bindings

### 6.1 Lifecycle

```
Claim:   issued ──redeem (active only)──▶ redeemed   (terminal; INV-CLM-3)
           ├── revoke ───────────────────▶ revoked    (terminal)
           ├── replace ─────────────────▶ revoked (+ a new claim, same transaction)
           ├── [only if P-A4 approved] security lock ──▶ locked (terminal for redemption; O may issue a new claim)
           └── now ≥ expiresAt ──────────▶ inactive (reported as expired)

Binding: (none) ──redemption──▶ bound ──[deferred: Identity merge / recovery acts]──▶ ended
```

### 6.2 Operations and their authority (one model per P-A option, §21)

**All claim operations lock the claim row `FOR UPDATE` in one central transaction. The first committed act wins; the others find a state that refuses them.**

| Operation | Business act? | Authority checked in O before the call (option **A1**, recommended) | Under option A2 | Identity's checks | Effect |
|---|---|---|---|---|---|
| **IssueClaim(O, R, issuanceId, authorityRef)** | **yes**: O invites the person to bind | `manage-claims` Authorised (actor = officer R, capacity = tenure, subject = (E, R)) | `issue-claim` | R ∈ O; R unbound; no active claim; authorityRef present; call from O (ISO-1) | new claim; **code returned once** |
| **retry, same issuanceId** | no (same act) | — (the first answer stands) | — | claim exists for (O, issuanceId) | **`AlreadyIssued(ClaimId)`, no code** |
| **ReplaceClaim(O, oldClaimId, newIssuanceId, authorityRef)** | **yes: one act** (withdraw one invitation and give another) | `manage-claims` | **`issue-claim` and `revoke-claim`, both Authorised, one request** (like admit + place-member, GTS correction note 3) | old claim `issued` (not redeemed or revoked) | **one transaction:** old → `revoked` (`replacesClaimId` recorded) and new claim created. **Failure → full rollback; the old claim stays active** |
| **RevokeClaim(O, ClaimId, authorityRef)** | **yes** | `manage-claims` | `revoke-claim` | state `issued` | `revoked`. A redeemed claim → refused (INV-CLM-3) |
| **security lock** (suspected compromise) | **no: technical, not organisational** | — | — | **only if P-A4 approves a security policy (R-XD3c-3 analogue)**: e.g. failed attempts beyond the throttle limit | `locked`; reported to O. **No binding is created or ended; O may issue a new claim** |

**Answers to the commission's questions:**
- **Does an officer keep authority to revoke after their issuing authority ends?**
  - **No, not under any option:** authority is evaluated at the act (D-12).
  - Having issued the claim confers nothing afterwards (D-167).
- **Is revoking one's own claim an organisational act?**
  - **Yes.** It withdraws O's invitation, so it needs the same check as any revocation.
  - Revision 2's "the issuer may revoke their own" is **withdrawn**.
- **Can Identity revoke an organisation-issued claim without O's act?** **No.**
  - Identity may only apply a **technical security lock**, and only under an approved security policy (P-A4).
  - A lock ends nothing organisational and grants nothing.
- **What authorises the security process?**
  - **No accepted decision does yet.** R-XD3c-3 allows emergency suspension of *accounts* "only under an approved security policy".
  - A claim lock would need the same kind of approval → **P-A4**.
- **Is replacement a distinct act?**
  - Under A1, **one act kind covers it**.
  - Under A2 it is **a defined combination**: both checks, one request.

**Delivery** is O's own, through its channel and contact data (D-101). **Never via the login address** (INV-ID-3).

### 6.3 Claim window (P-C)

- **Organisation-specific duration:** set by **the organisation's rules on file or a decision of a body they empower** (Governance owns the rules, D-157). The issuing context reads it with the authority answer and passes it in `IssueClaim`.
- **Rules silent:** **the platform default (proposed 14 days).** It is labelled like D-171's defaults; **D-171 is a precedent for the method only** (a labelled default that assigns no authority). It does not establish this value.
- **Platform maximum (proposed 30 days):** a platform-wide technical constraint. Identity **caps** any requested window at the maximum and **records that it did**.
- **Check at redemption:** `now < expiresAt` on the central clock (§5).

## 7. R-1: Account → R in organisation O

| | |
|---|---|
| **Operation** | `ActingRef(session, O) → R \| NotBound \| Ambiguous \| Unavailable` |
| **Caller** | O's entry layer (ISO-1, ISO-3), for every request or job acting for a person |
| **Output** | O's own R only |
| **Time** | read at the operation's start, as its τ; bindings never change retroactively (D-73) |
| **`NotBound`** | no binding in O: public endpoints only |
| **`Ambiguous`** | after a merge, two of O's references point to this Person (§18): **refused** until O resolves it |
| **`Unavailable`** | **refused** (D-195 (3)); no fallback, no cross-request cache, no e-mail matching |
| **Suspended or deleted Account** | no valid session; the binding is untouched (XD-3 §2.7) |

### 7.1 Uses and D-194

| Act | R is | Decided by |
|---|---|---|
| read or post in a forum | reader or author | Organisation (D-152), D-161 / D-168; moderator via Governance |
| officer acts | actor | Governance AuthorityCheck |
| **resignation (D-194)** | **declarant = the member concerned** | Membership |
| newsletter send | actor | `send-newsletter(series)` |
| registrar recording | actor | `record-from-evidence` |
| membership application | applicant | Membership (account creation ≠ admission) |

**For a resignation, four things are kept apart, and Membership owns the fact [EXT for M-TDD-01]:**
1. **the declaration**, the member's own act;
2. **the declarant**, the member's R from R-1;
3. **the recorder**, an empowered office, which records but does not decide;
4. **the effective date** (the declaration's) and **the recording time**.

**Identity decides none of these.**

## 8. R-2: R → bound Account reference, for Platform (X-2)

### 8.1 Conformance

| Requirement | Accepted meaning | Revision 3 | Conformance |
|---|---|---|---|
| D-193 X-2 | Identity operation resolving O's `PersonRef` to its **bound Account** | bound Account reference | **yes** |
| D-195 (2) | O's `PersonRef` → **its bound Account**, for Platform | same | **yes** |
| D-174 | Platform keeps only **account / PersonRef references** | same | **yes** |
| Platform R-B, M-6 | Platform stores **only Account references** of contacts | same | **yes** |
| D-179 path | Identity sends notices on Platform's request | `SendSecurityNotice(AccountRef, notice)` | **yes**; Platform never gets the address |

**Cross-organisation correlation:**
- R-2 resolves only O's own R, to an Account reference.
- **XD-3 §2.3** reserves to Identity the resolution to a Person or to another organisation's reference. R-2 does neither.
- Platform seeing one Account reference among several organisations' contacts **follows from the accepted D-174 / R-B model**. It is purpose-limited to contacts and the counterparty [AC].

### 8.2 The operation

| | |
|---|---|
| **Operation** | `BoundAccountFor(O, R, purpose = platform-contact) → AccountRef + account state \| NotBound \| Unknown \| Unavailable` |
| **Caller** | Platform only (service identity); purpose fixed |
| **Output** | the Account reference and its state (`active` / `suspended` / `deleted`). **Never** a `PersonId`, address, other bindings or other organisations' references |
| **Bound, Account not active** | **reported as such, not as `NotBound`.** Platform: the contact cannot approve; D-179 notices still apply |
| **`NotBound`** / **`Unknown`** | no binding / R not O's or the binding ended. **Platform fails closed** |
| **`Unavailable`** | Platform fails closed (D-193) |
| **Time** | read inside Platform's approval or promotion operation, as its τ. A stored Account reference is a reference, not authorisation |

## 9. Founding registrar's bootstrap claim

### 9.1 Founding information in three categories

| Category | Why needed | Who needs it | Central temporary holding permitted by accepted decisions? | Path |
|---|---|---|---|---|
| **A. Names and delivery addresses** of the registrar and initial contacts | to create their local entries in O's database and deliver the registrar's code | provisioning (step 6) | **No.** Not on D-164's list; D-191 permits only contact *metadata* (references, dates, digests, sender, outcome), not names or addresses | **decision P-B** |
| **B. Statutes and the founding decision (documents)** | rules on file and the first Governance records in O's database (D-148) | O's database (Governance) | **No.** Platform holds no decision texts (rev 4 H-2, D-191); D-166 keeps Platform out of organisational content | **never held centrally in any option:**<br>1. at application, Platform computes their **fingerprints** (D-148: "the platform only checks that the documents exist") and **discards the documents**;<br>2. at provisioning the documents are **submitted directly into O's new database** (written there by provisioning as founding records, R6-6);<br>3. **their fingerprints must match** the application's, else provisioning stops |
| **C. Evidence metadata** (document references, fingerprints, dates, sender) | to check that what arrives at provisioning is what was applied with; challenge | Platform | **Yes:** D-191 metadata; rev 4 H-2 | central, as accepted |

### 9.2 End-to-end path (under the recommended option B2)

| Step | What | Context | Central data |
|---|---|---|---|
| 1 | application: identities of the organisation and counterparty; **document fingerprints**; the claim that a founding decision names a registrar and initial contacts | applicant → Platform (`pending`) | category C only |
| 3–4 | counterparty verified (D-175); service-operation grant recorded (D-166) | Platform | Platform's accepted records |
| 5 | database created and migrated | provisioning | — |
| 6a | **the counterparty, as messenger (D-175, D-176), submits into provisioning:**<br>- the statutes and founding decision (category B, fingerprints checked);<br>- the registrar's and initial contacts' names and addresses (category A) | counterparty → provisioning → **O's database** | **none retained** |
| 6b | founding records written: rules on file, the founding decision, the local entries E | provisioning (technical operation, D-166, R6-6) | — |
| 6c | `IssueReference(O, E)` → R; **`IssueBootstrapClaim(O, R, foundingDecisionRef)`** (unique per O) | provisioning → Identity | R; claim hash |
| 6d | **code sent once to the registrar's address, in the same step, from the data being written into O's database** | provisioning, acting for O | **no address retained** |
| 8 | the registrar registers or redeems (§14) | Identity | binding |
| — | redemption reported to O (outbox) and to the initial contacts (D-179, via `SendSecurityNotice`) | Identity | audit |

### 9.3 Retry, expiry, reissue, challenge

| Case | Rule |
|---|---|
| crash during step 6 | provisioning resumes (idempotent on O). **Under B2 the submission of step 6a must be repeated if it did not complete.** Then a new bootstrap claim is issued (the old one revoked in the same transaction) and delivered |
| code lost or expired, not redeemed | **reissue** while no bootstrap claim has been redeemed and O is still in `provisioning`.<br>- **Provisioning never reads the address back from O's database:** that would be reading business content, which D-166 forbids technical operations.<br>- **Under B2 the counterparty resubmits the registrar's address** (as messenger), and provisioning delivers the new code from that submission.<br>- After `operational`, a lost bootstrap code needs an ordinary claim, and so a `manage-claims` designation (P-A) |
| bootstrap redeemed | never again (INV-CLM-2) |
| residual risk | **the registrar's address is as supplied by the applicant.** A wrong address could let someone else redeem. **Mitigations:** the redemption is reported to O and the initial contacts; **the founding decision is challengeable** (D-148) |
| Platform retains | only accepted records (status, grants, D-191 metadata). **No address, no code, no documents** |

## 10. Governance collaboration [EXT]

**Needed (per P-A):**
- **A1:** one act kind **`manage-claims`**;
- **A2:** **`issue-claim`** and **`revoke-claim`**.

**Shape:**
- actor = the officer's R; capacity = their tenure (pulled at the act);
- subject:
  - for issue: (E, R) + issuance id;
  - for replace and revoke: the claim id (+ new issuance id);
- O; business date = the act's date; six fields + basis (GTS correction notes 1, 4);
- **`NotAuthorised` / `Undetermined` → no call; refused** (D-167).

**The check runs in O's context. Identity refuses calls without an authority reference or from another organisation. It never re-evaluates competence and never acts for O.**

## 11. Platform collaboration (X-2)

**R-2 (§8.2) is the operation D-193 X-2 requires.** Platform's fail-closed conditions are unchanged.

## 12. Cross-context contracts and failures

| # | Contract | Failure |
|---|---|---|
| C-I1 | `IssueReference(O, E) → R` | the entry waits; no R invented |
| C-I2 | `IssueClaim`, `ReplaceClaim`, `RevokeClaim` | refused without an authority reference; idempotent (§6.2) |
| C-I3 | `ClaimRedeemed(O, R)` (outbox → O's inbox, idempotent on claim id) | delayed: O marks "claimed" later; access does not depend on it |
| C-I4 | R-1 | refuse |
| C-I5 | R-2, `SendSecurityNotice` | fail closed |
| C-I6 | `BindingRefused(O, R)` | O learns of the refusal only |
| C-I7 | `IssueBootstrapClaim` | provisioning only, in `provisioning`; unique per O |
| C-I8 | `ClaimLocked(O, ClaimId)` (only if P-A4) | O informed |

**Neutral errors:**
- one message for all claim failures;
- never reveals whether an Account or Person exists, or anything about another organisation.

## 13. Temporal consistency

| Fact | Business time | Knowledge time |
|---|---|---|
| Claim | issuedAt; active until expiresAt (time-derived) | recordedAt = issuedAt |
| Binding | boundAt | recordedAt = boundAt; **never back-dated** |
| R-1, R-2 | the operation's τ | τ; **one τ, complete or refused (D-73)** |

## 14. Transactions, retries and concurrency

**All in the central Identity database; no step spans an organisation database.**

| Operation | Atomic unit |
|---|---|
| start registration | create PendingRegistration (token hash, login, expiry); send verification to the address being registered (authentication of its holder, INV-ID-3) |
| **complete claim-first registration** | **one transaction:**<br>1. lock the claim `FOR UPDATE`;<br>2. check it is active and INV-ID-6;<br>3. create Person;<br>4. create Account (unique login, INV-ACC-1);<br>5. create Binding (unique (O, R), unique (O, `PersonId`));<br>6. claim → `redeemed`;<br>7. outbox `ClaimRedeemed`;<br>8. **write `RegistrationCompletion`**;<br>9. delete the PendingRegistration |
| redeem while signed in | one transaction: lock the claim; check; Binding; `redeemed`; outbox |
| plain registration | one transaction: Person + Account + `RegistrationCompletion`; delete the PendingRegistration |

**The six cases (finding 4):**

| # | Case | Outcome | Durable evidence used |
|---|---|---|---|
| 1 | transaction rolled back | nothing exists. The PendingRegistration and the claim are unchanged; **the person retries normally** | the PendingRegistration |
| 2 | committed, response lost; retry with the same registration token | **"registration completed: please sign in"** (success, no second Account) | **`RegistrationCompletion`** (token hash → Account), for 24 h. After 24 h the retry gets the neutral message; signing in still works |
| 3 | the same authenticated Account presents the same code again | **success, idempotent** (the claim is `redeemed` and bound to this Account's Person) | the claim and the Binding |
| 4 | a new registration with an existing login identifier | **"sign in instead"** (neutral). No second Account (INV-ACC-1) | the Account's unique login |
| 5 | the code is presented again after redemption, by anyone else | **neutral failure** (INV-CLM-3) | the claim |
| 6 | two concurrent registrations: same code (claim row lock, one wins, the other case 5) or same login (unique login, one wins, the other case 4) | as stated | locks and unique keys |

**Claim operations:** one lock rule (§6.2). Races:

| Race | Outcome |
|---|---|
| replace vs redeem | whichever commits first. Redeem first → the replace is refused (INV-CLM-3). Replace first → the redeem is refused (old claim `revoked`) |
| revoke vs redeem | the same rule |
| replace fails after selecting the old claim | **full rollback; the old claim is still active** |
| the same request retried | idempotent on issuance id (§6.2) |

**Privacy of `RegistrationCompletion`:**
- it holds token hash, Account reference and time only;
- 24-hour retention;
- **no login identifier, name or organisation.**

## 15. Audit and provenance

**Audited (central, content-free):**
- reference issued;
- claim issued, replaced, revoked, redeemed (and locked, if P-A4);
- failed attempts (counts);
- binding refused;
- R-2 calls;
- security notices;
- bootstrap issuance, fingerprint matches and delivery attempts.

**Never in audit:** codes or addresses beside an organisation.

**O records its own claim acts with their authority answers.** Retention: [LQ].

## 16. Privacy and minimisation

**Central data:**
- Person;
- Account;
- PendingRegistration (deleted on completion or expiry);
- RegistrationCompletion (24 h);
- references;
- claims (hashes);
- bindings;
- D-191 metadata (Platform).

**Under B2:** no names, contacts, documents or affiliations beyond bindings. `PersonRef`, bindings and Account references are pseudonymous personal data ([LQ]).

## 17. Security (threat walk-through)

| Threat | Answer |
|---|---|
| organisation takeover of identity | INV-ID-6; re-binding is recovery (P-I3) |
| leaked code | single use; time-derived expiry; revocable by O; throttled; **lock only if P-A4**; redemption reported |
| cross-organisation correlation | R per organisation; R-1 returns O's R only; R-2 is Platform-only and purpose-bound |
| e-mail confusion | INV-ID-3 |
| officer without authority | authority checked in O; Identity refuses calls without a reference |
| bootstrap abuse | unique per O; fingerprint match; reported; challengeable |
| **one human, two Accounts** | **not prevented across organisations (§0 item 5)**; within an organisation, INV-BND-2 |

## 18. Recovery and exceptional cases

| Case | Rule |
|---|---|
| lost login address | P-I3, deferred; never via a claim |
| **merge (deferred; rule only)** | a merged Person takes no new binding; bindings resolve to the survivor. **If the survivor then holds two of O's references, INV-BND-2 no longer holds for O: R-1 answers `Ambiguous` (refused) until O resolves it** (e.g. archives one entry, D-129) |
| **two Accounts of one human** | **not detected by this design** (§0 item 5); a merge, if ever confirmed, follows the row above |
| Account suspended or deleted | no session; R-2 reports the state; bindings and O's records untouched |
| membership ended (D-194) | no Identity effect |
| existing `users` | principle: each → one Account + one Person + references for its current organisations; migration ADR later |
| legacy flows | the first slice replaces the election-only voter invitation (AR §7) |

## 19. Open dependencies

| # | Item | Owner | Effect until resolved |
|---|---|---|---|
| D-1 | claim act kinds (P-A) | **Principal**, then Governance | bootstrap only; no ordinary claims |
| D-2 | founding contact data path (P-B) | **Principal** | the bootstrap path is not fixed |
| D-3 | claim window (P-C) | **Principal** | proposal |
| D-4 | security lock policy (P-A4) | **Principal** | no lock; only throttling (INV-ID-9) |
| D-5 | `end-membership` act kinds (D-194) | Governance | Membership cannot record End |
| D-6 | X-3 decision log | Governance | Identity unaffected |
| D-7 | **Platform:**<br>- how initial contacts' Account references are obtained before O's database exists;<br>- without e-mail matching;<br>- without Identity inventing a binding;<br>- without changing the accepted contact model | **Platform** | Platform's onboarding of initial contacts is incomplete; **Identity provides nothing new for it here** |
| D-8 | P-I2 mechanism | Identity, later | must keep INV-REF-2 |
| D-9 | retention, `PersonRef` classification, D-194 effective time | counsel | — |

## 20. Proof obligations (all NOT EXECUTED)

| # | Property |
|---|---|
| PI-1 | concurrent redemption of one code: exactly one binding |
| PI-2 | a bound R cannot be bound again |
| PI-3 | a second of O's references for one Person is refused and reported, with nothing about other organisations |
| PI-4 | R-1 never returns another organisation's R; refuses when Identity is unavailable; `Ambiguous` after a merge duplicate |
| PI-5 | R-2 answers only Platform; returns only the Account reference for O's own R; distinguishes an inactive Account from `NotBound` |
| PI-6 | at most one bootstrap claim redeemed per O; reissue atomic |
| PI-7 | claim calls without an authority reference, or from another organisation, are refused |
| PI-8 | identical neutral errors; no code or address in logs |
| PI-9 | bindings never back-dated |
| PI-10 | registration without a code creates no binding or affiliation |
| PI-11 | failure injected at each step of claim-first registration leaves nothing; retry succeeds |
| PI-12 | an issuance retry never creates a second claim or returns a code |
| PI-13 | redemption after `expiresAt` fails regardless of any mark |
| **PI-14** | **lost response after commit: a retry with the same registration token within 24 h returns "completed", never a second Account; after 24 h, neutral** |
| **PI-15** | **replace vs redeem and revoke vs redeem in both orders; a failed replace leaves the old claim active; a redeemed claim cannot be revoked** |
| **PI-16** | **provisioning refuses founding documents whose fingerprints differ from the application; no document, name or address remains centrally after step 6** |

## 21. Principal decision brief (genuine choices only)

### P-A: who may authorise claim codes (inviting a recorded person to bind their login to the organisation's record)

**A claim establishes an identity binding. It grants no membership and no access.**

**Relevant decisions:**
- D-167 (no default authority);
- D-190 (act-kind precedent);
- D-12 (authority at the act);
- R-XD3c-3 (security actions only under an approved policy).

| Option | Issue | Replace | Revoke | Consequence |
|---|---|---|---|---|
| **A1 (recommended): one competence, `manage-claims`** | the office or body the rules or a designation empower; **silent → `Undetermined`** | the same competence | the same competence | **one designation lets an organisation invite and withdraw.** The issuer's own later revocation needs the competence at that time. **Founding documents should name the office** (onboarding guidance) |
| A2: two competences, `issue-claim` / `revoke-claim` | as above | **both, in one request** | `revoke-claim` | finer control; an organisation can separate inviting from withdrawing, **but needs two designations before codes are manageable** |

**Separately: P-A4, technical security lock.**
- **Approve or not a security policy** under which Identity may **lock** (not revoke) a claim on stated technical grounds (e.g. failed attempts beyond the throttle limit), reported to O.
- **It creates or ends no binding and no organisational act; O may issue a new claim.**
- **Recommended: approve**, limited to that ground.
- **Without it, only throttling protects a code under attack until O revokes it.**

**Until P-A is decided:** no ordinary claim can be issued. People recorded by an organisation can register, but **cannot bind their login to that record**, so **cannot act in its forum or as officers.**

### P-B: how the founding contact data reaches provisioning (category A, §9.1)

**Founding documents are never held centrally in either option** (only fingerprints).

**Relevant decisions:**
- D-164 (central contents);
- D-166;
- D-191;
- Platform H-2;
- D-148;
- D-175 / D-176 (counterparty as messenger).

| Option | Central data | Consequence | Changes an accepted decision? |
|---|---|---|---|
| **B2 (recommended): resubmission at provisioning** | **none** | after verification and the service-operation grant, **the counterparty submits the documents and the registrar's and initial contacts' names and addresses once more, straight into provisioning**, which writes them into O's database. **If provisioning fails before completion, they are submitted again.** One extra step for the applicant, which they take anyway to submit the documents | **no** |
| B1: minimal transient holding | **names and delivery addresses only** (category A), encrypted, held from application until step 6 completes, then deleted; documents still fingerprint-only | the applicant submits contact data once; provisioning can resume without them. **Personal data sits centrally for days or weeks** | **yes: a narrow addition to D-164's list for onboarding** (D-191 and H-2 unaffected, because no documents and no decision texts are held) |

**Recommendation:** **B2**, the smallest footprint, with no change to accepted decisions.

**Until P-B is decided:** the bootstrap path of §9.2 is the proposal, not the design.

### P-C: how long a claim code stays valid

**Relevant:** D-167 / D-171 (labelled defaults that assign no authority; the method only).

| Option | Rule | Consequence |
|---|---|---|
| **C1 (recommended)** | the organisation's rules or a decision they empower set the window; **silent → 14 days (platform default)**; **platform-wide maximum 30 days**, capped and recorded by Identity | most codes are used within days; a lost code is replaced; 30 days caps a leaked code's exposure |
| C2 | no default; every issuance states a window up to 30 days | the officer chooses each time; no silent default |

**Until P-C is decided:** the 14 / 30 values are proposals.

## 22. Residual register

| Class | Items |
|---|---|
| Corrected in revision 3 | findings 2–8 of the review of revision 2 |
| Remaining tactical | P-I2 mechanism (INV-REF-2 required); merge procedure; throttling values [TU]; confirmation that 24 h suffices for `RegistrationCompletion` [TU] |
| **Principal decisions** | **P-A (A1 / A2), P-A4, P-B (B2 / B1), P-C (C1 / C2)** |
| Governance | claim act kinds (after P-A); `end-membership` (D-194); X-3 |
| Platform | D-7 (initial contacts' Accounts) |
| Legal | retention; `PersonRef` classification; resignation effective time |
| Proofs not executed | PI-1 … PI-16 |

## 23. Traceability

| Requirement | Section | Accepted decision changed? |
|---|---|---|
| D-195 (1) Account → R | §7 | no |
| **D-195 (2), D-193 X-2, D-174, Platform R-B / M-6: R → bound Account** | **§8.1** | **no** |
| D-195 (2) bootstrap; D-148; D-178 | §9 | no |
| D-195 (3) refuse when unavailable | §7, §8.2, §12 | no |
| **D-164 (central contents)** | §9.1, §16, §21 P-B | **no under B2**; B1 would need a decision |
| **D-166, D-191, Platform H-2 (no documents or decision texts centrally)** | §9.1, §16 | **no**: documents fingerprint-only in all options |
| D-179 | §8.1 (Identity sends) | no |
| D-194 | §7.1 | no |
| D-167, D-12 | §6.2, §10, §21 P-A | no |
| R-XD3c-2, R-XD3c-3 | §4, §6.2, §21 P-A4 | no (P-A4 proposes a policy, per R-XD3c-3) |
| D-110, D-111, D-113 | §0 item 5, §4, §14, §18 | no |

**No accepted Platform decision is changed.** The Platform dependency D-7 is recorded, not solved.

**Author's statement:**
- **revision 3**, proposed;
- **ready for independent review**;
- open: P-A, P-A4, P-B, P-C and the dependencies in §19;
- **no implementation performed or authorised.**

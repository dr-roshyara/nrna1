# I-TDD-02: Identity claims and bindings (tactical design), revision 2

| | |
|---|---|
| **Status** | **PROPOSED, revision 2. Awaiting independent review.** It decides nothing, ratifies nothing and authorises no implementation. Written by Claude, **not independent** |
| **Commission** | **D-195**; bounded correction "I-TDD-02-R1", Principal 2026-11-11 (after the Principal's DDD review of revision 1) |
| **Revision 1** | preserved unchanged as `2026-11-11-i-tdd-02-identity-claims-and-bindings-tactical-design-rev1.md`, SHA-256 `fdc3b0c0f5d2b8e2777c832951759bc2c9be30a778f60b1c8db51fdbc718df24`. **Revision 2 replaces it** |
| **Starting point** | scope `2026-11-10-identity-i-tdd-02-scope.md` (**IS**), as trimmed by the pre-TDD adversarial review §7–§8 (**AR**); I-TDD-01 (**I1**, proposed) where not superseded |
| **Binding** | **D-75** (XD-3 RAT); D-100, D-101; **D-110, D-111, D-113**; D-148; D-164; D-166; D-174 … D-193 (Platform revision 11, incl. **X-2** and **R-B**); D-179; **D-194**; **D-195** |
| **Entry criteria** | E-1 met by D-113 (option (d), P1 brief line 39). E-2 not required under (d) (AR §8). E-4 met by D-195. **No conflict** |

**Labels:** [AD] accepted decision · [BF] fact · [AC] architectural conclusion · [TD] tactical design · [TU] proof · [EXT] another context · [P] proposal · [OQ] Principal decision · UNKNOWN.

## Change summary (revision 1 → revision 2)

| # | Finding (Principal's review) | Change | Section |
|---|---|---|---|
| 1 | **R-2 substituted a `ContactHandle` for the accepted Account** | **Corrected.** R-2 returns **the bound Account reference**, as D-174, D-193 X-2, D-195 (2) and Platform R-B require. **The handle is withdrawn from the design** and recorded only as a possible later privacy hardening, **not proposed for decision now** | §8, §21 |
| 2 | Bootstrap delivery path incomplete | Complete end-to-end path stated. **The one missing piece is identified: where the founding person data is held between application and provisioning.** It is a gap in accepted texts → **Principal decision P-B** | §9, §21 |
| 3 | Registration and redemption transaction incomplete | One central transaction for Person + Account + Binding + claim + outbox; pending-registration step; failure and retry table | §14 |
| 4 | Issuance idempotency vs code shown once | Retry, reissue, revoke and replacement distinguished; **no plaintext code stored** | §6.2 |
| 5 | Handle lifecycle | **Moot** (handle withdrawn, finding 1) | — |
| 6 | Seams must stay visibly deferred | P-I2, merge exception, inactive Account vs unbound, temporal meaning of R-1 / R-2 failures, time-derived expiry, **14 / 30 days relabelled as a proposal for decision (P-C)** | §5, §6, §7, §8, §18, §21 |
| — | Claim authority | **Principal decision brief P-A** (options, consequences, recommendation) | §21 |
| — | D-194 | Declarant, recorder, effective date and recording time distinguished | §7.1 |

## 0. Findings first

1. **[AC] Identity establishes *who*, never *what they may do*.** A binding says: this Account's Person is organisation O's `PersonRef` R. **It says nothing about belonging, membership, office, authority or access** (D-164; ISO-7; XD-3 §2.4).
2. **[AD] Two resolutions, two consumers:**

   | Resolution | Consumer |
   |---|---|
   | **R-1** Account → R in O (who is acting?) | every organisation context, every request |
   | **R-2** R → bound **Account reference** (which login is this contact?) | **Platform only** (X-2) |

3. **[EXT] Ordinary claims need Governance act kinds `issue-claim` / `revoke-claim`, which do not exist yet.** Until the Principal authorises them (P-A) and Governance defines them, **only the bootstrap claim can be issued**: no member can be given access.
4. **[EXT, observation, not solved here] Platform R-B** references initial contacts "by their Identity Account" before the organisation database exists. **It does not say how the applicant obtains those Account references without e-mail matching** (INV-ID-3). This is Platform's to answer. **Revision 2 provides the operation Platform could use** (§9.4) but decides nothing for Platform.

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
- P-I2 mechanism (seam only);
- P-I3 recovery;
- merges beyond the rule;
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
| **Account** | the login a Person controls; authenticates its holder, grants nothing else | D-110, I1 §2.2 |
| **Account reference** | the identifier of an Account, as Platform already stores it for contacts and the counterparty | Platform R-B, M-6 |
| **`PersonRef` (R)** | organisation O's opaque number for one person it recorded; issued at entry, **before any Person exists** | D-113 |
| **Claim** | a one-time, organisation-issued proof of possession for one R | D-113, XD-3b §2a |
| **Binding** | "R of O is Person P"; **not a merge** | D-113 |
| **Bootstrap claim** | the one claim per organisation issued without an officer, for the founding registrar | D-148, D-178, D-195 |

**Five separate concepts, never derived from one another [AD]:**
1. identity resolution (Identity);
2. belonging (Organisation);
3. membership (Membership);
4. authority (Governance);
5. access and entitlement (each consuming context).

## 3. Responsibility

| Identity owns (central, D-164) | Identity does not own |
|---|---|
| Person, Account, authentication, sessions | the organisation's record of the person, contacts, profile (D-101) |
| `PersonRef` issuance | belonging, membership, office, authority, access |
| claims, bindings | who may issue claims (Governance) |
| R-1, R-2 answers; sending D-179 security notices | routing, contacts lists, grants (Platform) |

## 4. Aggregates and invariants

| Aggregate | Invariants |
|---|---|
| **Person** | **INV-ID-1:** only `PersonId` and state (`active` / `mergedInto`). **INV-ID-2:** a merged Person takes no new binding |
| **Account** | **INV-ID-3:** the login identifier authenticates its holder only; never used to find, create or bind (sole exception: D-179 notices). **INV-ID-4:** bound to a Person only through redemption or claim-first registration. **INV-ID-5:** suspension and deletion only with authority evidence; never caused by membership or access changes (XD-3 §2.7). **INV-ACC-1:** login identifier unique |
| **IssuedReference** | **INV-REF-1:** R is issued to exactly one organisation. **INV-REF-2:** issuance idempotent on (O, entry E), **whether stored (L1) or derived (L2)**. A P-I2 mechanism that cannot guarantee this is not acceptable |
| **Claim** | **INV-ID-6:** binds only an unbound R. **INV-ID-7:** only the code's hash is stored. **INV-ID-8:** after it ends, only what audit needs is kept. **INV-ID-9:** failed attempts throttled. **INV-CLM-1:** at most one active claim per (O, R). **INV-CLM-2:** at most one bootstrap claim per O is ever redeemed |
| **Binding** | **INV-BND-1:** an R is bound to at most one Person. **INV-BND-2:** a Person is bound to at most one R of O; a second is refused and reported to O (D-113). **Exception: after a merge**, see §18. **INV-BND-3:** created only by redemption; never edited; ended only by Identity's explicit acts (deferred) |

**Rejected:**
- an Affiliation inside Identity (C-4);
- a working-organisation aggregate;
- e-mail as identity evidence;
- social sign-in;
- `anchored` (unneeded under (d));
- **`ContactHandle` (withdrawn, §8)**.

## 5. Models

| Model | Fields (minimal) |
|---|---|
| Person | `PersonId`, state |
| Account | `AccountId`, `PersonId`, login identifier (verified), credential, state (`active`, `suspended`, `deleted`) |
| PendingRegistration | login identifier, verification token hash, expiry, optional claim code hash. **Holds no Person or Account** |
| IssuedReference | R, O, issuedAt. **SEAM P-I2:** stored (L1) or derived (L2). **Open: which; it does not block R-1 / R-2, which read bindings** |
| Claim | `ClaimId`, O, R, kind (`ordinary` / `bootstrap`), code hash, issuedAt, expiresAt, state (`issued`, `redeemed`, `revoked`), issuance id, authority reference, attempt count |
| Binding | O, R, `PersonId`, boundAt, `ClaimId` |

**Expiry is time-derived.** A claim is *active* iff `state = issued ∧ now < expiresAt`. An `expired` mark written later by a sweeper is **for reporting only**; redemption checks time, not a mark.

## 6. Claims and bindings

### 6.1 Lifecycle

```
Claim:   issued ──redeem (active only)──▶ redeemed
           ├── revoke ──▶ revoked
           └── time passes expiresAt ──▶ (inactive; reported as expired)

Binding: (none) ──redemption──▶ bound ──[deferred: Identity's merge / recovery acts]──▶ ended
```

### 6.2 Issuing, retrying, reissuing, revoking (resolves finding 4)

| Operation | Meaning | Rule |
|---|---|---|
| **IssueClaim(O, R, issuanceId, authorityRef)** | first issue | Identity generates the code, stores its hash and returns the code **once**. Preconditions: R ∈ O; R unbound; no active claim for (O, R) |
| **retry with the same issuanceId** | the response was lost | **returns `AlreadyIssued(ClaimId)` without a code.** No second claim is created, and **the code is never stored in plaintext.** The officer then uses ReissueClaim |
| **ReissueClaim(O, oldClaimId, newIssuanceId, authorityRef)** | deliberate replacement while active (lost code, wrong delivery) | **one transaction:** revoke the old claim, issue a new one, return the new code once. Authority: `issue-claim` |
| **RevokeClaim(O, ClaimId, authorityRef)** | withdraw an unredeemed claim | `revoked`. Authority per P-A |
| **replacement after expiry** | the old claim is inactive | an ordinary IssueClaim (INV-CLM-1 is satisfied because the old claim is not active) |

**Delivery** is the organisation's, through its own channel and contact data (D-101). **Never via the login address** (INV-ID-3).

### 6.3 Claim window

**[P], for Principal decision P-C:**
- **default 14 days** where the organisation's rules are silent;
- **maximum 30 days**;
- the organisation's rule may set anything up to the maximum.

**Not accepted.**

## 7. R-1: Account → R in organisation O

| | |
|---|---|
| **Operation** | `ActingRef(session, O) → R \| NotBound \| Ambiguous \| Unavailable` |
| **Caller** | O's entry layer (ISO-1, ISO-3), for every request or job acting for a person |
| **Output** | O's own R only; never another organisation's R, a `PersonId`, other bindings or names |
| **Time** | read at the start of the operation, as its τ. Bindings never change retroactively, so the answer stays valid for the operation (D-73) |
| **`NotBound`** | the signed-in Person has no binding in O: only public endpoints of O (application form) |
| **`Ambiguous`** | **after a merge, two of O's references point to this Person (§18): refused** until O resolves it |
| **`Unavailable`** | Identity cannot answer: **the request is refused** (D-195 (3)); **no fallback, no cache across requests, no e-mail matching** |
| **Suspended or deleted Account** | there is no valid session, so R-1 is not reached. **The binding is untouched** (XD-3 §2.7) |

### 7.1 Uses and D-194 (Identity supplies only R)

| Act | R is | Decided by |
|---|---|---|
| read or post in a forum | reader or author | Organisation (D-152), D-161 / D-168; moderator via Governance |
| officer acts (admit, renew, suspend, restore, end, place, issue claim) | actor | Governance AuthorityCheck with actor R |
| **resignation (D-194)** | **declarant = the member concerned** | Membership records `End(resignation)` |
| newsletter send | actor | `send-newsletter(series)` |
| registrar recording | actor | `record-from-evidence` |
| membership application | applicant | Membership (account creation ≠ admission) |

**For a resignation, four things are kept apart, and Membership owns the fact [EXT for M-TDD-01]:**
1. **the declaration**, the member's own act;
2. **the declarant** = the member's R, from R-1 when the member submits it;
3. **the recorder**, an empowered office, when an office enters a declaration received otherwise; it records, it does not decide;
4. **the effective business date** (the declaration's) and **the recording time** (knowledge).

**Identity decides none of these.**

## 8. R-2: R → bound Account reference, for Platform (X-2) (resolves finding 1)

### 8.1 Conformance with the accepted contract

| Requirement | Accepted meaning | Revision 1 | Revision 2 | Conformance |
|---|---|---|---|---|
| D-193 X-2 | "an accepted Identity operation resolving an organisation `PersonRef` to its **bound Account**" | `ContactHandle` | **bound Account reference** | **yes** |
| D-195 (2) | "an organisation's `PersonRef` → **its bound Account**, for Platform" | handle | Account reference | **yes** |
| D-174 | "Platform keeps only their **account / PersonRef references** centrally" | handle in place of the Account | Account reference | **yes** |
| Platform R-B, M-6 | "Platform stores **only Account references** of contacts" | handle | Account reference | **yes** |
| Platform D-179 path | "Identity sends them on Platform's request" (`SecurityNoticeRequested`) | `SendSecurityNotice(H)` | `SendSecurityNotice(AccountRef, notice)` | **yes**: Platform never receives the address |

### 8.2 Answers to the commission's questions

1. **X-2 requires the actual bound Account reference.** A purpose-bound opaque substitute would change what Platform stores and compares. **Revision 1 was wrong.**
2. **Yes:** Platform revision 11 keys contacts by Account reference (R-B, M-6, D-174).
3. **Returning a handle would have changed accepted Platform behaviour:** what it stores, and how it compares an approver with a contact. **Withdrawn.**
4. **The distinction between identity and authority holds either way.** R-2 says which login a contact is; **the contact's power comes from the organisation's designation** (D-174, D-183, D-193).
5. **Cross-organisation correlation under XD-3:**
   - **XD-3 §2.3 reserves to Identity** the resolution of one organisation's R to the canonical Person or to another organisation's reference. R-2 does neither: it returns an Account reference, for O's own R.
   - **That Platform can see one Account reference among the contacts of several organisations follows from the accepted D-174 / R-B model** (it already stores Account references centrally). It is **purpose-limited to contacts and the counterparty**.
   - **[AC]:** permitted under the accepted decisions. A handle would be **an optional later privacy hardening**. It is **not proposed for decision now**, because no accepted decision requires it and adopting it would change D-174 / R-B.

### 8.3 The operation

| | |
|---|---|
| **Operation** | `BoundAccountFor(O, R, purpose = platform-contact) → AccountRef (+ account state) \| NotBound \| Unknown \| Unavailable` |
| **Caller** | **Platform (tenancy) only**, by its service identity; purpose fixed |
| **Input** | O and R, taken from O's own designation records (D-174, D-191) |
| **Output** | the Account reference bound to R in O, **with the account's state** (`active`, `suspended`, `deleted`). **Never** a `PersonId`, login address, other bindings or other organisations' references |
| **`NotBound`** | R exists in O, with no binding |
| **`AccountInactive`** (state ≠ active) | **a binding exists, but the Account is suspended or deleted. Not reported as `NotBound`.** Platform: the contact cannot approve (cannot sign in); notices per D-179 |
| **`Unknown`** | R is not O's, or the binding has ended (merge or recovery, deferred) |
| **`Unavailable`** | Identity down: **Platform fails closed** (D-193) |
| **Time** | read inside Platform's approval or promotion operation, as its τ. **A stored Account reference is a reference, not an authorisation:** Platform re-reads at each approval (its D-182 / D-186 checks) |
| **Companion** | `SendSecurityNotice(AccountRef, notice)`: **Identity** sends D-179 notices; nothing else ever uses the address |

## 9. Founding registrar's bootstrap claim (resolves finding 2)

### 9.1 End-to-end path (accepted steps, Platform rev 3 F-02 steps 1–8)

| Step | What | Context | Data held |
|---|---|---|---|
| 1 | Application: statutes; founding decision naming the registrar **and their contact address**; initial contacts | applicant → Platform (`pending`) | **see 9.2: the gap** |
| 3–4 | Counterparty verified; service-operation grant recorded | Platform | Platform's own records |
| 5 | Database created and migrated | Platform provisioning (technical operation, D-166) | — |
| 6a | **Founding records written into O's database:** the registrar's local entry E (name, contact, as stated in the founding decision), the initial contacts' entries | **provisioning, under the service-operation grant** (accepted step 6; writing founding records is a permitted technical operation, R6-6) | O's database (D-101) |
| 6b | `IssueReference(O, E)` → R | provisioning → Identity | central: R |
| 6c | **Bootstrap claim:** `IssueBootstrapClaim(O, R, foundingDecisionRef)`, unique per O (INV-CLM-2) | provisioning → Identity | central: claim (hash) |
| 6d | **Delivery:** the code is sent once to the registrar's contact address, **by provisioning, in the same step, from the data it is writing into O's database**. Nothing is stored centrally except the claim | provisioning (acting for O, under the service-operation grant) | **no central copy of the address** |
| 8 | The registrar registers or redeems (§14) | Identity | binding |
| — | **Report:** the redemption is reported to O (outbox) and, as a D-179 security notice, to the initial contacts' Accounts | Identity | audit |

### 9.2 The gap (needs a decision)

**Between step 1 (`pending`) and step 6, the founding person data must exist somewhere:**
- the registrar's and initial contacts' names and contact addresses;
- the statutes and founding decision.

**No accepted text says where:**
- D-164 lists what central may hold, and this is not in it;
- D-191 permits only contact *metadata* (references, digests);
- Platform H-2 says Platform holds no decision texts.

**Revision 2 does not invent a repository. → Principal decision P-B (§21).**

### 9.3 Retry, expiry, reissue and challenge

| Case | Rule |
|---|---|
| step 6 crash before delivery | provisioning resumes (idempotent on O). The bootstrap claim is **reissued** (old one revoked in the same transaction) and delivered again |
| code lost or expired before redemption | reissue while no bootstrap claim has been redeemed. **It needs the registrar's address again: either still held during provisioning (P-B option A) or resubmitted by the organisation** |
| bootstrap redeemed | **no bootstrap claim ever again** (INV-CLM-2); a replacement registrar needs an ordinary claim (`issue-claim`, P-A) |
| challenge | the redemption is reported to O and the initial contacts; **the founding decision is challengeable** (D-148); a successful challenge is O's to act on (an Identity binding is not undone by a claim, INV-BND-3) |
| what Platform retains | only its accepted records (status, grants, contacts metadata per D-191). **No address; no code** |

### 9.4 For Platform's R-B observation (§0 item 4)

**Initial contacts** could obtain their local entries and claims at step 6 like the registrar, and bind by ordinary redemption after an `issue-claim` designation exists. **How Platform references their Account before step 6 remains Platform's to define** [EXT].

## 10. Governance collaboration [EXT]

- **Needed:** `issue-claim` and `revoke-claim`. **Not accepted; Principal decision P-A, then a Governance addition note.**
- **Shape (if authorised):**
  - actor = the officer's R;
  - capacity = their tenure (pulled at the act);
  - subject = (E, R) + issuance id (claim id for revocation);
  - O;
  - business date = date of the act;
  - six fields + basis;
  - **`NotAuthorised` / `Undetermined` → no call; refused.**
- **The check runs in O's context.** Identity refuses calls without an authority reference or from another organisation. **It does not re-evaluate competence.**

## 11. Platform collaboration (X-2)

**R-2 (§8.3) is the Identity-owned operation D-193 X-2 requires.** Platform's fail-closed conditions are unchanged.

## 12. Cross-context contracts and failures

| # | Contract | Failure |
|---|---|---|
| C-I1 | `IssueReference(O, E) → R` | Identity down: the entry waits; no R invented |
| C-I2 | `IssueClaim`, `ReissueClaim`, `RevokeClaim` | refused unless an authority reference is given; idempotent (§6.2) |
| C-I3 | `ClaimRedeemed(O, R)` (outbox → O's inbox, idempotent on claim id) | delayed: O marks "claimed" later; **access does not depend on it** |
| C-I4 | R-1 | refuse the request |
| C-I5 | R-2, `SendSecurityNotice` | fail closed |
| C-I6 | `BindingRefused(O, R)` | O learns of the refusal only |
| C-I7 | `IssueBootstrapClaim(O, R, foundingDecisionRef)` | provisioning only, in `provisioning`; unique per O |

**Neutral errors:**
- one message for all claim failures;
- never reveals whether an Account or Person exists, or anything about another organisation.

## 13. Temporal consistency

| Fact | Business time | Knowledge time |
|---|---|---|
| Claim | issuedAt; active until expiresAt (time-derived) | recordedAt = issuedAt |
| Binding | boundAt | recordedAt = boundAt. **Never back-dated** |
| R-1, R-2 | the operation's τ | τ. **One τ, complete or refused (D-73)** |

## 14. Transactions and concurrency (resolves finding 3)

**All steps below are in the central Identity database. No step spans an organisation database.**

| Operation | Atomic unit | Asynchronous |
|---|---|---|
| **Start registration** (with or without code) | create PendingRegistration; send verification (to the address being registered: authentication of its holder, INV-ID-3) | — |
| **Complete claim-first registration** (verified address + code) | **one transaction:**<br>1. lock the claim `FOR UPDATE`;<br>2. check it is active and INV-ID-6;<br>3. create Person;<br>4. create Account (unique login, INV-ACC-1);<br>5. create Binding (unique (O, R), unique (O, `PersonId`));<br>6. claim → `redeemed`;<br>7. outbox `ClaimRedeemed`;<br>8. delete the PendingRegistration | delivery of `ClaimRedeemed` to O |
| **Redeem while signed in** | one transaction: lock the claim; check; Binding; `redeemed`; outbox | same |
| **Plain registration** (verified, no code) | one transaction: Person + Account; delete the PendingRegistration | — |

| Failure or race | Result |
|---|---|
| failure anywhere inside the transaction | **rollback: no Person, Account or Binding exists;** the PendingRegistration and the claim are unchanged; **the person retries** |
| **"after Account creation, before Binding"** | **cannot be observed** (same transaction) |
| uncertain response, then retry | the claim is `redeemed` and the binding is to this person's Person → **success returned (idempotent for the same Person)**; otherwise the neutral failure |
| retry of plain registration | unique login: the existing Account → "sign in instead" (neutral) |
| two redemptions of one code | row lock; one wins |
| two references of O, one Person | unique (O, `PersonId`): refused and reported (C-I6) |
| issue vs reissue vs revoke vs redeem | the same claim row lock |

## 15. Audit and provenance

**Audited (central, content-free):**
- reference issued;
- claim issued, reissued, revoked, redeemed;
- failed attempts (counts);
- binding refused;
- R-2 calls (purpose, O, outcome);
- security notices;
- bootstrap issuance and delivery attempts.

**Never in audit:** codes or addresses beside an organisation.

**O records its own issuance acts.** Retention: [LQ].

## 16. Privacy and minimisation

**Central data:**
- Person;
- Account;
- PendingRegistration (deleted on completion or expiry);
- references;
- claims (hashes);
- bindings.

**Not held centrally:** names, contacts or affiliations (C-4), **subject to P-B for founding data in transit**. `PersonRef`, bindings and Account references are pseudonymous personal data ([LQ]).

## 17. Security (threat walk-through)

| Threat | Answer |
|---|---|
| organisation takeover of identity | INV-ID-6; re-binding is recovery (P-I3) |
| leaked code | single use, time-derived expiry, revocable, throttled; redemption reported |
| cross-organisation correlation | R per organisation; R-1 returns O's R only; **R-2 limited to Platform, purpose-bound, and to O's own R** (§8.2) |
| e-mail confusion | INV-ID-3 |
| officer without authority | `issue-claim` in O; Identity refuses calls without an authority reference |
| bootstrap abuse | unique per O; founding evidence; reported to O and the initial contacts; challengeable |

## 18. Recovery and exceptional cases

| Case | Rule |
|---|---|
| lost login address | P-I3, deferred; never via a claim |
| **merge of two Persons (deferred; rule only)** | a merged Person takes no new binding; bindings resolve to the survivor. **If the survivor then holds two of O's references, INV-BND-2 no longer holds for O.** **R-1 answers `Ambiguous` (refused) until O resolves it** (e.g. archives one entry, D-129), and O is told. **This is the deferred exception, not a preserved invariant** |
| Account suspended or deleted | no session; R-2 reports `AccountInactive`; bindings and O's records untouched |
| membership ended (D-194) | no Identity effect |
| existing `users` | principle: each → one Account + one Person + references for its current organisations; migration ADR later |
| legacy flows | the first slice replaces the election-only voter invitation (AR §7) |

## 19. Open dependencies

| # | Item | Owner | Effect until resolved |
|---|---|---|---|
| D-1 | `issue-claim` / `revoke-claim` | **P-A**, then Governance | bootstrap only |
| D-2 | founding data in transit | **P-B** | bootstrap path incomplete |
| D-3 | claim window values | **P-C** | proposal only |
| D-4 | `end-membership` act kinds (D-194) | Governance | Membership cannot record End |
| D-5 | X-3 decision log | Governance | Identity unaffected |
| D-6 | P-I2 mechanism | Identity, later | must keep INV-REF-2; R-1 / R-2 unaffected |
| D-7 | Platform R-B: how initial contacts' Accounts are obtained | Platform | observation (§9.4) |
| D-8 | retention, legal classification | counsel | — |

## 20. Proof obligations (all NOT EXECUTED)

| # | Property |
|---|---|
| PI-1 | concurrent redemption of one code: exactly one binding |
| PI-2 | a bound R cannot be bound again |
| PI-3 | a second of O's references for one Person is refused and reported, with nothing about other organisations |
| PI-4 | R-1 never returns another organisation's R; refuses when Identity is unavailable; answers `Ambiguous` after a merge duplicate |
| PI-5 | R-2 answers only Platform, returns only the Account reference for O's own R, and distinguishes `AccountInactive` from `NotBound` |
| PI-6 | at most one bootstrap claim redeemed per O; reissue revokes the old one atomically |
| PI-7 | claim calls without an authority reference, or from another organisation, are refused |
| PI-8 | identical neutral errors; no code or address in logs |
| PI-9 | bindings never back-dated |
| PI-10 | registration without a code creates no binding or affiliation |
| PI-11 | **failure injected at each step of claim-first registration leaves no Person, Account or Binding; retry succeeds** |
| PI-12 | **an issuance retry with the same issuance id never creates a second claim and never returns a code; reissue returns a new code and revokes the old** |
| PI-13 | **redemption after `expiresAt` fails regardless of any `expired` mark** |

## 21. Principal decision brief (genuine choices only)

### P-A: who may issue and revoke claim codes (and so give people access)

**Relevant decisions:**
- D-167 (no default authority);
- D-190 (precedent for act kinds);
- D-148 (registrar records, does not decide).

| Option | Issue | Revoke an unredeemed claim | Business consequence |
|---|---|---|---|
| **A1 (recommended)** | only an office or body **the organisation's rules or a designation** empower to issue claims; **silent → `Undetermined`** (D-167) | **the same authority, or the officer who issued that claim** (undoing their own unredeemed act), **or Identity's security process with a reason** (compromise, R-XD3c-2) | no access is given until the organisation designates someone. **Founding documents should therefore name the office** (onboarding guidance) |
| A2 | as A1, but **revocation only by the same authority** | — | simpler; a mistaken code cannot be withdrawn by its issuer if their authority has since ended |
| A3 | the body that **admitted** a member may also issue that member's claim | — | **infers authority from admission; conflicts with D-167**. Not recommended |

**Recommendation:** A1. It infers nothing:
- the issuer withdrawing their own unredeemed code undoes their own act;
- the security route is Identity's existing account-security authority.

**Effects of A1:**
- **Governance:** two act kinds.
- **Identity:** §6.2 as written.
- **Inviting members:** possible once the organisation designates.

### P-B: where the founding person data is held between application and provisioning (bootstrap delivery)

**Relevant decisions:**
- D-164 (central holds only the listed things);
- D-191 (contact metadata only);
- D-148;
- D-166.

| Option | Business consequence |
|---|---|
| **B1 (recommended): transient onboarding intake.** Central, encrypted, holding only what step 6 needs (statutes and the founding decision as submitted; registrar's and initial contacts' names and contact addresses). **Purged once the founding records are written and the bootstrap claim is redeemed**, or on rejection or withdrawal of the application. **Only digests are kept.** **A narrow addition to D-164's list, for onboarding only** | the delivery path in §9.1 works end to end; personal data sits centrally for days or weeks, then is deleted |
| B2: no central holding; **the applicant resubmits the founding data at step 6** directly into provisioning | nothing central, but onboarding needs the applicant again at provisioning time |
| B3: the bootstrap code is **handed to the verified counterparty** to pass to the registrar | simplest; **the counterparty could redeem it themselves**. Reported and challengeable, but **turns the counterparty into a credential carrier**, against the spirit of D-175 |

**Recommendation:** B1. Step 6, which is already accepted, needs this data anyway. B1 states its place and its deletion instead of leaving it implicit.

### P-C: claim window

| Option | Consequence |
|---|---|
| **C1 (recommended):** default **14 days** where the rules are silent, maximum **30 days**, labelled platform default (as D-171) | most codes are used within days; a lost code can be reissued; 30 days caps exposure of a leaked code |
| C2: no default; every issuance states its window (≤ 30 days) | officers must choose each time |

**Not decided by this design.** P-A is needed before ordinary claims work. P-B is needed before the bootstrap path is complete. P-C is low-stakes.

## 22. Residual register

| Class | Items |
|---|---|
| **Design defects corrected** | finding 1 (R-2 Account); findings 2–4; finding 6 seams |
| **Unresolved tactical** | P-I2 mechanism (seam, INV-REF-2 required); merge procedure (deferred); throttling values [TU] |
| **Principal decisions** | P-A, P-B, P-C |
| **Governance dependencies** | `issue-claim`, `revoke-claim` (after P-A); `end-membership` (D-194); X-3 |
| **Platform observation** | R-B Account acquisition for initial contacts (§9.4) |
| **Legal** | retention; `PersonRef` classification; resignation effective time (D-194 [LQ]) |
| **Proofs not executed** | PI-1 … PI-13 |

## 23. Traceability

| Requirement | Section |
|---|---|
| D-195 (1) Account → R | §7 |
| **D-195 (2) R → bound Account; D-193 X-2; D-174; Platform R-B** | **§8 (conformance table §8.1)** |
| D-195 (2) bootstrap claim; D-148; D-178 | §9 |
| D-195 (3) refuse when unavailable | §7, §8.3, §12 |
| D-113 (claim, binding, one reference per Person per organisation) | §4, §6, §14 |
| XD-3 §2.3, §2.6, §2.7, §5 | §4, §8.2, §17, §18 |
| D-164, D-166 | §3, §9, §16 |
| D-179 | §8.3 (Identity sends notices) |
| D-194 | §7.1 |
| D-167 | §10, §21 P-A |

**Author's statement:**
- **revision 2**, proposed;
- **ready for independent review**;
- open: P-A, P-B, P-C and the dependencies in §19;
- **no implementation performed or authorised.**

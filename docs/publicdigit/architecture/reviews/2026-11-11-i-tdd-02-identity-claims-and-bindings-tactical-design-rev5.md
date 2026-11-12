# I-TDD-02: Identity claims and bindings (tactical design), revision 5

| | |
|---|---|
| **Status** | **PROPOSED, revision 5. Awaiting independent review.** It decides nothing, ratifies nothing and authorises no implementation. Written by Claude, **not independent** |
| **Commission** | **D-195**; bounded corrections I-TDD-02-R1 (→ rev 2), I-TDD-02-R2 (→ rev 3), I-TDD-02-R3 (→ rev 4) and **I-TDD-02-R4 (→ this revision)**, Principal 2026-11-11 / 12 |
| **Earlier revisions (preserved unchanged)** | rev 1: `…-tactical-design-rev1.md`, SHA-256 `fdc3b0c0f5d2b8e2777c832951759bc2c9be30a778f60b1c8db51fdbc718df24`<br>rev 2: `…-tactical-design-rev2.md`, SHA-256 `98177e1163da230a1d86b5e57c7f4bc0fde037d1fc441d0a9deeaf430ea23e89`<br>rev 3: `…-tactical-design-rev3.md`, SHA-256 `33a4de94e0f3cf1cc8deec7232a9a0faebcaaaf6fcd9c4730f01cff927778799`<br>rev 4: `…-tactical-design-rev4.md`, SHA-256 `6f79d7a43a340fbb26036679359afe49e72c0e9e7463940164f8f3af375a25e4`<br>**Revision 5 replaces all four** |
| **Starting point** | scope `2026-11-10-identity-i-tdd-02-scope.md` (**IS**) as trimmed by the pre-TDD adversarial review §7–§8 (**AR**); I-TDD-01 (**I1**, proposed) where not superseded |
| **Binding** | **D-75** (XD-3 RAT, incl. XD-3c R-XD3c-2 / -3); D-100, D-101; **D-110, D-111, D-113**; D-148; D-164; D-166; D-174 … D-193 (Platform revision 11, incl. X-2, R-B, H-2); D-179; D-191; **D-194**; **D-195** |
| **Entry criteria** | E-1 met by D-113 (option (d)); E-2 not required under (d) (AR §8); E-4 met by D-195. **No conflict** |

**Labels:** [AD] accepted decision · [BF] fact · [AC] architectural conclusion · [TD] tactical design · [TU] proof · [EXT] another context · [P] proposal · [OQ] Principal decision · UNKNOWN.

## Change summary

**Revision 4 → revision 5** (findings R4-01 … R4-07 of GPT-6's review of revision 4; nothing else changed):

| # | Finding | Change | Section |
|---|---|---|---|
| R4-01 | A2 replacement lacked a two-answer evidence contract | **Two Governance answers** (`issue-claim`, `revoke-claim`), each with its own request identifier, act, subject and outcome. **Both checked and both consumed in the replacement transaction**; either failing → full rollback, old claim active | §6.2, §10.1, §10.3, PI-17a |
| R4-02 | evidence called both value and reference | **One input: `AuthorityEvidence` (the complete Governance answer by value, including its `requestRef`).** `authorityRef` removed everywhere | §5, §6.2, §10.1, §12, §20 |
| R4-03 | initial-contact notice depends on Platform D-7 | **Reporting to O (established) and notice to initial contacts (conditional on D-7) separated**; the latter not counted as a mitigation until D-7 is resolved | §9.2, §9.3, §9.5, §21 |
| R4-04 | B1's consequences understated | **B1 withdrawn as an option**, with the reasons recorded (D-164, D-166; D-191 / H-2 not affected for documents) | §21 P-B |
| R4-05 | the registrar-address risk understated | **full consequence stated: impersonation through a wrong binding.** Identity's binding vs Governance's authority distinguished; **B-T2's limits stated** | §9.5, §21 |
| R4-06 | no-persistence on upload is an implementation claim | stated as a **requirement**; the upload path listed under **PI-16 (not executed)** | §9.4, §20 |
| R4-07 | PI-17 mixed tests and assessment | **PI-17a** (executable correspondence tests) and **PI-17b** (security assessment of integrity beyond the process boundary) | §10.1, §19, §20 |

**Revision 3 → revision 4** (findings of the Principal's review of revision 3; unaffected sections unchanged):

| # | Finding | Change | Section |
|---|---|---|---|
| 1 | Identity boundary sound | **kept unchanged** | — |
| 2 | **authority evidence:** "an `authorityRef` is present" is not enough | **The authority-evidence contract is specified.**<br>- The Governance answer is passed **by value**, with a single-use `requestRef`.<br>- Identity checks its **correspondence**: organisation, **actor = the signed-in person's R (Identity verifies this itself through R-1)**, act, subject, outcome, business date, freshness, single use.<br>- **The trust boundary is stated:** server-side only, never from the client.<br>- An integrity mechanism is left as proof obligation PI-17, **not invented** | §10.1 |
| 3 | **Governance extension route** | the D-190 route: Principal authorisation → Governance addition note (**drafted here, both variants**) → ratification in the I-TDD-02 acceptance act. Parts separated | §10.2, §10.3 |
| 4 | **P-A4 not bounded** | **Recommended disposition: defer.** The denial-of-service risk is stated; what a policy must specify is listed; **no lock and no thresholds in this design.** Throttling remains | §5, §6.1, §6.2, §17, §21 |
| 5 | **bootstrap address trust** | **The trust assumption is stated:** who supplies the address, what fingerprints prove and do not prove, consequences of a wrong address. **An optional two-channel delivery is offered as a proposal** within P-B | §9.5, §21 P-B |
| 6 | **B2 document processing** | four stages distinguished: transient receipt, persistence, writing into O's database, retention | §9.4 |
| 7–8 | other corrections preserved; 24 h retention | **kept**; PI-14 extended with cleanup | §20 |

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
| Claim | `ClaimId`, O, R, kind (`ordinary` / `bootstrap`), code hash, issuedAt, expiresAt, state (`issued`, `redeemed`, `revoked`), issuance id, authority evidence (§10.1), `replacesClaimId`, attempt count. **No `locked` state: P-A4 deferred** |
| ConsumedAuthorityRequest | `requestRef` request identifier, O, act, consumedAt. **Unique; makes each Governance answer single-use** (§10.1) |
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
           └── now ≥ expiresAt ──────────▶ inactive (reported as expired)

Binding: (none) ──redemption──▶ bound ──[deferred: Identity merge / recovery acts]──▶ ended
```

### 6.2 Operations and their authority (one model per P-A option, §21)

**All claim operations lock the claim row `FOR UPDATE` in one central transaction. The first committed act wins; the others find a state that refuses them.**

| Operation | Business act? | Authority checked in O before the call (option **A1**, recommended) | Under option A2 | Identity's checks | Effect |
|---|---|---|---|---|---|
| **IssueClaim(O, R, issuanceId, evidence)** | **yes**: O invites the person to bind | one `AuthorityEvidence`: `manage-claims` Authorised (actor = officer R, capacity = tenure, subject = (E, R)) | one `AuthorityEvidence`: `issue-claim` | R ∈ O; R unbound; no active claim; **evidence passes §10.1**; call from O (ISO-1) | new claim; **code returned once**; evidence consumed |
| **retry, same issuanceId** | no (same act) | — (the first answer stands) | — | claim exists for (O, issuanceId) | **`AlreadyIssued(ClaimId)`, no code** |
| **ReplaceClaim(O, oldClaimId, newIssuanceId, evidence[])** | **yes: one act** (withdraw one invitation and give another) | **one** `AuthorityEvidence`: `manage-claims`, subject = old claim id + new issuance id | **two** `AuthorityEvidence` values: **`revoke-claim`** (subject = old claim id) **and `issue-claim`** (subject = (E, R) + new issuance id), **each with its own request identifier**, both for the same actor and business date | old claim `issued` (not redeemed or revoked); **every evidence value passes §10.1** | **one transaction:** consume every evidence value; old → `revoked` (`replacesClaimId` recorded); new claim created. **Any evidence absent, stale, replayed, mismatched or not `Authorised`, or any other failure → full rollback: nothing consumed, the old claim stays active** |
| **RevokeClaim(O, ClaimId, evidence)** | **yes** | one `AuthorityEvidence`: `manage-claims` | one `AuthorityEvidence`: `revoke-claim` | state `issued`; evidence passes §10.1 | `revoked`; evidence consumed. A redeemed claim → refused (INV-CLM-3) |
| **suspected compromise** | **no organisational act** | — | — | **throttling only** (INV-ID-9): failed attempts are slowed per code and per client. **No automatic lock** (P-A4 deferred, §21) | the claim stays usable for its holder; O may revoke or replace it through its authority |

**Answers to the commission's questions:**
- **Does an officer keep authority to revoke after their issuing authority ends?**
  - **No, not under any option:** authority is evaluated at the act (D-12).
  - Having issued the claim confers nothing afterwards (D-167).
- **Is revoking one's own claim an organisational act?**
  - **Yes.** It withdraws O's invitation, so it needs the same check as any revocation.
  - Revision 2's "the issuer may revoke their own" is **withdrawn**.
- **Can Identity revoke an organisation-issued claim without O's act?** **No.**
  - Identity applies only throttling.
  - An automatic lock would need an approved security policy (R-XD3c-3 analogue); **that is deferred (P-A4).**
- **What authorises the security process?**
  - **No accepted decision.** R-XD3c-3 allows emergency suspension of *accounts* only "under an approved security policy".
  - **This design therefore introduces no claim lock.**
- **Is replacement a distinct act?**
  - Under A1, **one act kind covers it**.
  - Under A2 it is **a defined combination**: **two Governance answers** (`revoke-claim` for the old claim, `issue-claim` for the new one), **each with its own request identifier**, checked and consumed together in one transaction (§6.2, §10.1).

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
| — | **redemption reported to O** (outbox; established). **Notice to the initial contacts (D-179, via `SendSecurityNotice`) only once Platform D-7 establishes their Account references**; until then, not available | Identity | audit |

### 9.3 Retry, expiry, reissue, challenge

| Case | Rule |
|---|---|
| crash during step 6 | provisioning resumes (idempotent on O). **Under B2 the submission of step 6a must be repeated if it did not complete.** Then a new bootstrap claim is issued (the old one revoked in the same transaction) and delivered |
| code lost or expired, not redeemed | **reissue** while no bootstrap claim has been redeemed and O is still in `provisioning`.<br>- **Provisioning never reads the address back from O's database:** that would be reading business content, which D-166 forbids technical operations.<br>- **Under B2 the counterparty resubmits the registrar's address** (as messenger), and provisioning delivers the new code from that submission.<br>- After `operational`, a lost bootstrap code needs an ordinary claim, and so a `manage-claims` designation (P-A) |
| bootstrap redeemed | never again (INV-CLM-2) |
| residual risk | **the registrar's address is as supplied by the applicant** (full consequence in §9.5). **Established mitigations:** the redemption is reported to O; **the founding decision is challengeable** (D-148). **Notice to the initial contacts depends on Platform D-7** |
| Platform retains | only accepted records (status, grants, D-191 metadata). **No address, no code, no documents** |

### 9.4 Document processing under B2 (four stages)

| Stage | When | What happens | Persisted where |
|---|---|---|---|
| **1. Transient receipt and fingerprinting** | application (step 1) | documents are received over the upload channel; **fingerprints computed while the bytes stream through** | **nothing of the bytes.** No file, no temporary table, no queue payload; buffers discarded at the end of the request |
| **2. Persistence of document bytes** | — | **never centrally**, in any stage | — |
| **3. Writing into O's database** | provisioning (step 6a / 6b) | the counterparty uploads again; provisioning **streams the bytes into O's database as founding records** and computes the fingerprint during the write. **Mismatch with the application's fingerprint → O's transaction is rolled back** (nothing written), and provisioning stops and reports | **O's database only** (D-101, D-148) |
| **4. Retention of permitted metadata** | application and provisioning | fingerprints, document references, dates, sender, and **"matched / not matched" with time** | central (D-191 metadata; Platform H-2) |

**Stage 1's "nothing persisted" is a requirement, not an established fact.** The actual upload path must demonstrate it, **PI-16 (not executed)**, covering:
- PHP / Laravel upload temporary files;
- web-server and reverse-proxy request buffering;
- any temporary storage;
- queues and job payloads;
- logs, caches and error reports.

### 9.5 The registrar's address: the trust assumption (stated, not solved)

| Question | Answer |
|---|---|
| **Who supplies it** | **the organisation, through its verified counterparty (D-175) as messenger.** It is the organisation's own declaration. PublicDigit transmits it and does not vouch for it |
| **What the fingerprint match proves** | that the documents written into O's database are **the same documents** submitted at application |
| **What it does not prove** | **that the separately submitted address belongs to the registrar named in the founding decision**, or that the founding decision is genuine (D-148: "the platform only checks that the documents exist") |
| **Independent address verification** | **none in the accepted architecture.** Not by reading O's database back (D-166), not by e-mail matching (INV-ID-3), not by central storage (B2) |
| **If the address is wrong** | **Full consequence:**<br>1. a different person can redeem the bootstrap claim;<br>2. **Identity binds their Account to the registrar's `PersonRef`;**<br>3. **Governance recognises that `PersonRef` as the registrar designated by the founding decision;**<br>4. **so that person can act under the registrar's identity:** record committees and office holders from evidence (D-148, D-159), within the organisation's authority rules.<br><br>**This is impersonation through a wrong identity binding**, not just a wrong contact record. **Two things are distinct:** Identity created a binding (who is acting); Governance's recognition of the registrar comes from the founding decision (what that person may do). **PublicDigit created no authority; it bound the wrong human to an authority the organisation created** |
| **Detection** | **established:** the redemption is reported to O (outbox), seen by whoever acts in O, which may be only the impostor at first. **Conditional:** a D-179 notice to the initial contacts, **only once Platform D-7 is resolved.** **Until D-7, detection may rely on people outside PublicDigit** (the real registrar, the organisation's members) |
| **Remedy** | a challenge through O's processes. **The binding remains** (INV-BND-3); what that person recorded can be challenged. **A replacement registrar needs an ordinary claim (P-A)** |
| **Residual risk** | **accepted only if the Principal accepts it** (B-T1). **[P] alternative B-T2, two-channel delivery:** the code is split, and redemption needs **both** the part sent to the submitted address **and** a part handed to the counterparty for the registrar.<br>- **It reduces reliance on one channel:** a wrong address alone is not enough.<br>- **It does not prove that the redeemer is the named registrar.** It trusts that the counterparty hands the second part to the right person, **and a counterparty who also supplied a wrong address defeats it.**<br>- **Not an existing capability** |

## 10. Governance collaboration [EXT]

### 10.1 Authority-evidence contract between Governance and Identity

- **Who calls:** **only O's server-side application layer**, in O's organisation context (ISO-1). It calls Identity after asking Governance in O's database.
- **What it passes:** **`AuthorityEvidence` = the complete Governance answer by value** (six fields + basis, including `requestRef` with its subject and request identifiers; GTS correction notes 1, 2, 4).
  - **There is no separate "authority reference" input.**
  - **A bare identifier is never evidence.**
  - **One value per act kind:** A1 needs one; an A2 replacement needs two.
- **Never accepted:** evidence carried in client input (browser, form, header).

| Check by Identity (correspondence and trust, **not competence**) | Rule | Failure |
|---|---|---|
| organisation | the answer's organisation = the calling context's O | refused |
| **actor** | **the answer's `actor` = the R that Identity itself resolves for the signed-in session in O (R-1)** | refused. **This is the decisive check: Identity knows who is signed in, so an officer cannot present another officer's answer** |
| act | the answer's act kind = the operation (`manage-claims`, or `issue-claim` / `revoke-claim`, per P-A) | refused |
| subject | `requestRef` subject identifier = the call's (E, R) or claim id | refused |
| outcome | `Authorised` | refused (`NotAuthorised` / `Undetermined` never reach Identity; if they do, refused) |
| business date | `evaluatedFor` = the date of the act | refused |
| freshness | `checkedAt` within the same request as the call (the operation's τ, D-73) | refused as stale |
| single use | the `requestRef` request identifier is not yet in `ConsumedAuthorityRequest`; **consumed in the same transaction as the claim operation. For a replacement under A2, both values' identifiers are consumed together, or neither** | refused (replay) |

**Identity never re-evaluates competence:** whether the officer is competent is Governance's answer.

**Trust boundary, stated [AC]:**
- **The answer is trusted because it is produced server-side by Governance in O's context and passed in-process** to the call. It is not trusted because a reference is present.
- **A compromised server would defeat this. The checks above defeat a dishonest or mistaken caller.**
- **Integrity protection** beyond the process boundary (e.g. Governance signing its answers) **is not part of the accepted architecture.**
  - Whether it is needed is **PI-17b, a security assessment [TU]**, separate from the correspondence tests (PI-17a).
  - **Passing PI-17a does not answer PI-17b.**
  - No mechanism is invented here.

**Bootstrap exception:** `IssueBootstrapClaim` carries no Governance answer. Its basis is the founding decision reference, and it is accepted **only from provisioning, only in `provisioning`, once per O** (C-I7).

### 10.2 Introducing the act kinds (the D-190 route)

| Step | What | Who | Status |
|---|---|---|---|
| 1 | **Authorise the act kinds** (A1: `manage-claims`; or A2: `issue-claim` + `revoke-claim`) | **the Principal (P-A)**, as D-190 did for Platform | open |
| 2 | **Governance addition note** defining them (draft §10.3) | Governance design text | drafted here; **not appended** |
| 3 | **Ratification:** the note is appended to the Governance thin slice **in the act that accepts I-TDD-02** | the Principal's acceptance act (as D-193 did for D-190) | after independent review |
| 4 | **The Identity–Governance contract** | §10.1 | technical, this design |

**Choosing P-A creates nothing by itself. Until step 3, ordinary claims are refused.**

### 10.3 Draft Governance addition note (proposed; not appended)

> **Addition (D-[P-A]; ratified by the I-TDD-02 acceptance act):** AuthorityCheck act kinds consumed by **Identity**. Ratified six fields + basis; `requestRef` per correction note 4.
>
> | Act kind (A1 / A2) | `actor` / `capacity` | Subject | `evaluatedFor` | Competent | Rules silent |
> |---|---|---|---|---|---|
> | **A1: `manage-claims`** (issue, replace, revoke) | the officer's `PersonRef` / tenure (office-holder act, pulled at the act) | issue: (entry, `PersonRef`) + issuance id; replace / revoke: claim id (+ new issuance id) | date of the act | the office or body the organisation's rules or a designation empower to manage claims | **`Undetermined`** until designated (D-167, D-181) |
> | **A2: `issue-claim`** / **`revoke-claim`** | same | issue: (entry, `PersonRef`) + issuance id / revoke: claim id; **replace = two answers (`revoke-claim` + `issue-claim`), each with its own request identifier, both consumed in the replacement transaction** | date of the act | the office or body empowered for each | **`Undetermined`** |
>
> **A claim establishes an identity binding; it grants no membership and no access. The founding registrar's bootstrap claim is not checked with these act kinds** (D-148, D-178, D-195).

## 11. Platform collaboration (X-2)

**R-2 (§8.2) is the operation D-193 X-2 requires.** Platform's fail-closed conditions are unchanged.

## 12. Cross-context contracts and failures

| # | Contract | Failure |
|---|---|---|
| C-I1 | `IssueReference(O, E) → R` | the entry waits; no R invented |
| C-I2 | `IssueClaim`, `ReplaceClaim`, `RevokeClaim` (input: `AuthorityEvidence` value(s)) | refused unless every evidence value passes §10.1; idempotent (§6.2) |
| C-I3 | `ClaimRedeemed(O, R)` (outbox → O's inbox, idempotent on claim id) | delayed: O marks "claimed" later; access does not depend on it |
| C-I4 | R-1 | refuse |
| C-I5 | R-2, `SendSecurityNotice` | fail closed |
| C-I6 | `BindingRefused(O, R)` | O learns of the refusal only |
| C-I7 | `IssueBootstrapClaim` | provisioning only, in `provisioning`; unique per O |
| C-I8 | *(withdrawn: no claim lock; P-A4 deferred)* | — |

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
- claim issued, replaced, revoked, redeemed;
- authority evidence accepted or refused (reason only);
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
| leaked code | single use; time-derived expiry; revocable by O; throttled; redemption reported. **No automatic lock** (P-A4 deferred). **Throttling slows an attacker, and also the holder while an attack is under way; it never disables the claim** |
| forged or replayed authority | §10.1: actor verified by Identity itself; single-use `requestRef`; fresh `checkedAt`; server-side only |
| wrong registrar address | §9.5: stated trust assumption; detection and challenge; two-channel delivery proposed (P-B) |
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
| D-1 | claim act kinds (P-A) → addition note → ratification (§10.2) | **Principal**, Governance, acceptance act | bootstrap only; no ordinary claims |
| D-2 | founding contact data path and the address trust assumption (P-B) | **Principal** | the bootstrap path is not fixed |
| D-3 | claim window (P-C) | **Principal** | proposal |
| D-4 | security lock policy (P-A4) | **deferred**; a separate proposal later | no lock; throttling only |
| D-4a | integrity protection of Governance answers beyond the process boundary | **security assessment PI-17b [TU]** | in-process trust boundary (§10.1) |
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
| PI-7 | claim calls without valid `AuthorityEvidence`, or from another organisation, are refused |
| PI-8 | identical neutral errors; no code or address in logs |
| PI-9 | bindings never back-dated |
| PI-10 | registration without a code creates no binding or affiliation |
| PI-11 | failure injected at each step of claim-first registration leaves nothing; retry succeeds |
| PI-12 | an issuance retry never creates a second claim or returns a code |
| PI-13 | redemption after `expiresAt` fails regardless of any mark |
| **PI-14** | **lost response after commit: a retry with the same registration token within 24 h returns "completed", never a second Account; after 24 h, neutral. `RegistrationCompletion` rows are deleted at 24 h; whether 24 h suffices is [TU]** |
| **PI-15** | **replace vs redeem and revoke vs redeem in both orders; a failed replace leaves the old claim active; a redeemed claim cannot be revoked** |
| **PI-16** | **provisioning refuses founding documents whose fingerprints differ from the application; no document bytes, names or addresses remain centrally, including logs, caches, temporary storage, queues and error reports** |
| **PI-17a** | **executable correspondence tests:**<br>- evidence refused when its actor differs from the session's R in O;<br>- refused when it is for another act, subject or organisation, or is not `Authorised`;<br>- refused when stale or replayed;<br>- **never accepted from client input;**<br>- **under A2, a replacement with one of its two values missing, invalid or replayed consumes nothing and leaves the old claim active** |
| **PI-17b** | **security assessment, not a test:** is integrity protection of Governance answers beyond the trusted server-side process boundary needed? **Its outcome is a finding, not a pass** |

## 21. Principal decision brief (rebuilt for revision 4; nothing here is accepted)

### P-A: who may authorise claim codes (inviting a recorded person to bind their login to the organisation's record)

**A claim establishes an identity binding. It grants no membership and no access.**

**Relevant decisions:**
- D-167 (no default authority);
- D-12 (authority at the act);
- D-190 (route for new act kinds).

| Option | Issue / replace / revoke | Consequence |
|---|---|---|
| **A1: one competence, `manage-claims`** | one designation covers all three | **simpler:** an organisation designates one office or body for the whole invitation lifecycle. **The competence to invite includes the competence to withdraw** |
| A2: `issue-claim` and `revoke-claim` | separate designations; **replace needs two answers (revoke + issue), each with its own request identifier, consumed together** | **separation of duties:** inviting and withdrawing can lie with different offices. **Two designations are needed before codes are fully manageable** |

**Under both options:**
- **no lasting right from having issued:** authority is checked at each act (D-12);
- **rules silent → `Undetermined`** (D-167): **founding documents should name the office;**
- **the evidence contract (§10.1) is the same.**

| | |
|---|---|
| **Dependency** | §10.2: the Principal's authorisation (this decision), then the Governance addition note (draft §10.3), **ratified in the I-TDD-02 acceptance act** |
| **Recommendation** | **A1**, for a simpler baseline. **It is a governance-policy choice:** A2 is the right choice if the Principal wants inviting and withdrawing kept apart |
| **Can be decided now?** | **Yes, once this revision is independently reviewed** (the evidence contract §10.1 is what the review found missing) |
| **Until decided** | no ordinary claims. People recorded by an organisation can register, but cannot bind their login to their record, so they cannot act in its forum or as officers |

### P-A4: automatic security lock on a claim

| | |
|---|---|
| **Business question** | may Identity automatically lock a claim code that seems to be under attack? |
| **Risk** | **an attacker could cause the lock deliberately** (by guessing at a claim they know of), **denying a legitimate person their registration.** Locking protects the code but can be turned against its holder |
| **A safe policy would need to specify** | the trigger (what counts as attempts, against what); the counting scope (per claim, per client, per network); **which inputs an attacker controls**; temporary or terminal lock; notification (O; the holder through O's channel); recovery (O replaces the claim; the holder's path); interaction with throttling |
| **Current protection** | throttling per code and per client (INV-ID-9): it slows attempts and never disables the claim |
| **Recommendation** | **defer. Do not approve now.** Keep throttling. A bounded policy can be proposed separately later |
| **Can be decided now?** | **only as "defer"** |

### P-B: how the founding contact data reaches provisioning, and the address trust assumption

**Founding documents are never held centrally in any option:**
- fingerprints only (§9.4);
- the documents themselves are written into O's database at provisioning.

| Option | Central data | Consequence | Changes an accepted decision? |
|---|---|---|---|
| **B2 (recommended)** | **none** | the counterparty resubmits the documents and the founding contacts' names and addresses at provisioning, straight into O's database; **resubmits again if provisioning fails** | **no** |

**B1 (central transient holding of names and addresses) is withdrawn as an option.**
- Holding an organisation's founding people data centrally would need **D-164's list** to change.
- It would also put **D-166's boundary** in question: Platform handling organisational people data outside a technical operation on O's own database.
- (It would not touch the limits on decision texts in **D-191 and Platform H-2**, because no documents are held.)
- **B2 needs no change, so B1 is not offered.**

**Sub-choice within P-B: the registrar's address (§9.5):**

| Option | Meaning | Consequence |
|---|---|---|
| **B-T1: accept the stated trust assumption** | the organisation (through its counterparty) is responsible for the address; PublicDigit transmits it and does not vouch for it | **a wrong address lets someone else be bound as the registrar and act under the registrar's identity until challenged** (§9.5). **Detection:**<br>- established: report to O;<br>- **conditional on Platform D-7:** notice to the initial contacts.<br><br>**Remedy:** O's challenge (D-148) |
| B-T2: two-channel delivery [P] | redemption needs both the part sent to the address and a part handed to the counterparty for the registrar | **one wrong channel is not enough.** **It does not prove the redeemer is the named registrar; a counterparty who supplied a wrong address defeats it.** A slightly longer first registration; a new capability to design |

| | |
|---|---|
| **Recommendation** | **B2: yes.** **B-T1 / B-T2: not yet.**<br>- With D-7 open, B-T1's detection rests on reporting to O only, which the impostor may be the first to see.<br>- **Decide B-T1 / B-T2 after Platform D-7 is resolved**, or decide now with that weaker detection explicitly accepted |
| **Can be decided now?** | **B2: yes** (the only option). **B-T1 vs B-T2: possible, but better after D-7.** **Choosing B2 does not mean accepting B-T1** |

### P-C: how long a claim code stays valid

| Option | Rule | Consequence |
|---|---|---|
| **C1 (recommended)** | the organisation's rules or a decision they empower set the window; **silent → 14 days**; **platform-wide maximum 30 days**, capped and recorded by Identity | e.g. rules say 7 → 7; say 20 → 20; silent → 14; ask for 45 → 30 |
| C2 | no default; every issuance states a window up to 30 days | the officer chooses each time |

| | |
|---|---|
| **Basis** | **14 and 30 are product-policy proposals.** D-171 is a precedent only for the *method* (a labelled default that assigns no authority), **not for these values** |
| **Can be decided now?** | **yes** |

## 22. Residual register

| Class | Items |
|---|---|
| Corrected in revision 4 | authority-evidence contract (§10.1); Governance route and draft note (§10.2–§10.3); P-A4 withdrawn from the design (deferred); address trust assumption (§9.5); B2 processing (§9.4) |
| Remaining tactical | P-I2 mechanism (INV-REF-2 required); merge procedure; throttling values [TU] |
| **Principal decisions** | **P-A (A1 / A2); P-B (B2) and, separately, B-T1 / B-T2 (better after D-7); P-C (C1 / C2); P-A4 (defer)** |
| Governance | the claim act kinds (§10.2); `end-membership` (D-194); X-3 |
| Platform | D-7 (initial contacts' Accounts) |
| Legal | retention; `PersonRef` classification; resignation effective time |
| Proofs not executed | **PI-1 … PI-16, PI-17a**, plus the **assessment PI-17b** (PI-14 includes the 24 h retention and cleanup; PI-16 the upload path) |

## 23. Traceability

| Requirement | Section | Accepted decision changed? |
|---|---|---|
| D-195 (1) Account → R | §7 | no |
| **D-195 (2), D-193 X-2, D-174, Platform R-B / M-6: R → bound Account** | §8.1 | **no** |
| D-195 (2) bootstrap; D-148; D-178 | §9, §10.1 (bootstrap exception) | no |
| D-195 (3) refuse when unavailable | §7, §8.2, §12 | no |
| D-164 | §9.1, §9.4, §16, §21 P-B | **no** (B2; B1 withdrawn) |
| D-166, D-191, Platform H-2 | §9.3 (no read-back), §9.4 | no |
| D-179 | §8.1, §9.5 | no |
| D-190 (route for act kinds) | §10.2 | **no:** followed, not changed |
| D-194 | §7.1 | no |
| D-167, D-12 | §6.2, §10, §21 P-A | no |
| R-XD3c-2, R-XD3c-3 | §4, §6.2, §21 P-A4 | no (no lock introduced) |
| D-110, D-111, D-113 | §0 item 5, §4, §14, §18 | no |

**No accepted Platform, Governance or Identity decision is changed.**

**Author's statement:**

| | Items |
|---|---|
| **Proposals** | the whole design; the draft Governance note (§10.3); A1, B2 / B-T1, C1; the 14 / 30 values; B-T2 |
| **Accepted decisions used** | as listed in the header |
| **Unresolved dependencies** | §19 |
| **Unexecuted proofs** | PI-1 … PI-16, PI-17a; assessment PI-17b |

- **Revision 5 is ready for independent review.**
- **No implementation performed or authorised.**

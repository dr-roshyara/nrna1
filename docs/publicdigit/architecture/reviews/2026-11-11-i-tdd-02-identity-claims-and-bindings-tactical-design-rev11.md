# I-TDD-02: Identity claims and bindings (tactical design), revision 11

| | |
|---|---|
| **Status** | **PROPOSED, revision 11. Awaiting independent review.** It decides nothing, ratifies nothing and authorises no implementation. Written by Claude, **not independent** |
| **Commission** | **D-195**; bounded corrections I-TDD-02-R1 (→ rev 2), I-TDD-02-R2 (→ rev 3), I-TDD-02-R3 (→ rev 4), I-TDD-02-R4 (→ rev 5), I-TDD-02-R5 (→ rev 6), I-TDD-02-R6 (→ rev 7), I-TDD-02-R7 (→ rev 8), I-TDD-02-R8 (→ rev 9), I-TDD-02-R9 (→ rev 10) and **I-TDD-02-R10 (→ this revision)**, Principal 2026-11-11 / 12 |
| **Earlier revisions (preserved unchanged)** | rev 1: `…-tactical-design-rev1.md`, SHA-256 `fdc3b0c0f5d2b8e2777c832951759bc2c9be30a778f60b1c8db51fdbc718df24`<br>rev 2: `…-tactical-design-rev2.md`, SHA-256 `98177e1163da230a1d86b5e57c7f4bc0fde037d1fc441d0a9deeaf430ea23e89`<br>rev 3: `…-tactical-design-rev3.md`, SHA-256 `33a4de94e0f3cf1cc8deec7232a9a0faebcaaaf6fcd9c4730f01cff927778799`<br>rev 4: `…-tactical-design-rev4.md`, SHA-256 `6f79d7a43a340fbb26036679359afe49e72c0e9e7463940164f8f3af375a25e4`<br>rev 5: `…-tactical-design-rev5.md`, SHA-256 `030ad9e0a5dd6242b371010bff62013949382c146e4fc8355a62dab9f2b664a1`<br>rev 6: `…-tactical-design-rev6.md`, SHA-256 `63492d6e5108dc57675ae942cc1b253f5ac0bc1febb7e3938f62185d2a0b72c3`. **Re-hashed in the repository before this revision and matched.** *(The reviewer's hash of the attached copy, `8cdba4f3…`, differs; the repository file is unchanged, so the difference lies in the attachment's bytes, e.g. export or line-ending conversion.)*<br>rev 7: `…-tactical-design-rev7.md`, SHA-256 `dac4a48c5caa7ef14b4cdcb92c298f36ee1beddfcb240c2af8c97a6c58067940`<br>rev 8: `…-tactical-design-rev8.md`, SHA-256 `65960317b48f02828190032864696c18fb48901b7c247f43692418a59bbebca0`<br>rev 9: `…-tactical-design-rev9.md`, SHA-256 `614e8bec8850e01c195c7c4c7ca857481fdef1a8456bb1d7cf89a59c6901b77d`<br>rev 10: `…-tactical-design-rev10.md`, SHA-256 `43e6aa8b2444ace7b37dc651e241dee26b3b1536be735f4fb41f58b1e55a74b1`<br>**Revision 11 replaces all ten** |
| **Starting point** | scope `2026-11-10-identity-i-tdd-02-scope.md` (**IS**) as trimmed by the pre-TDD adversarial review §7–§8 (**AR**); I-TDD-01 (**I1**, proposed) where not superseded |
| **Binding** | **D-75** (XD-3 RAT, incl. XD-3c R-XD3c-2 / -3); D-100, D-101; **D-110, D-111, D-113**; D-148; D-164; D-166; D-174 … D-193 (Platform revision 11, incl. X-2, R-B, H-2); D-179; D-191; **D-194**; **D-195** |
| **Entry criteria** | E-1 met by D-113 (option (d)); E-2 not required under (d) (AR §8); E-4 met by D-195. **No conflict** |

**Labels:** [AD] accepted decision · [BF] fact · [AC] architectural conclusion · [TD] tactical design · [TU] proof · [EXT] another context · [P] proposal · [OQ] Principal decision · UNKNOWN.

## Change summary

**Revision 10 → revision 11** (the two clarifications of the Principal's review of revision 10; nothing else changed):

| # | Clarification | Change | Section |
|---|---|---|---|
| 1 | **every bootstrap redemption entry path** | **Locks are stated per claim kind for each entry path:**<br>- **ordinary:** `ClaimSlot` (O, R) → claim row;<br>- **bootstrap:** `BootstrapSlot` (O) → `ClaimSlot` (O, R) → claim row.<br><br>This applies to **claim-first registration**, **redemption while signed in** and the shared redemption service. **No path redeems a bootstrap claim without `BootstrapSlot`** | §6.2, §14 |
| 2 | **durable evidence for INV-CLM-2** | **A permanent marker on `BootstrapSlot`:** `redeemedAt` and `redeemedClaimId`, written in the redemption transaction. **Reissue checks the marker, not claim rows.**<br>- Bootstrap claim rows may be cleaned up only **after O has left `provisioning`**, when no bootstrap issuance or reissue is accepted any more. So cleanup cannot affect `bootstrapOpId` idempotency.<br>- **The marker lives as long as O; audit retention is separate** | §4, §5, §6.2, §9.3, §14, §15, §16 |
| — | proof cases | **PI-42 … PI-47** added (NOT EXECUTED) | §20 |

**Revision 9 → revision 10** (bootstrap clarifications 1–3 of the Principal's review of revision 9, plus one cross-slot case found while specifying them; nothing else changed):

| # | Correction | Change | Section |
|---|---|---|---|
| 1 | bootstrap rows selected before the time sample | **exact sequence:**<br>1. `BootstrapSlot` (O);<br>2. `ClaimSlot` (O, R) of the registrar's R;<br>3. **every bootstrap claim row of O with `state = issued`, in `ClaimId` order, `FOR UPDATE`, with no expiry filter**;<br>4. **then** one `t_check`;<br>5. decide | §6.2 |
| 2 | fate of an expired bootstrap claim on reissue | **one rule: reissue revokes every unredeemed bootstrap claim of O still `issued`, active or expired.** So at most one bootstrap row is ever `issued` (INV-CLM-4 strengthened). An expired code stays unusable in any case | §4, §6.1, §9.3, §14, PI-31 |
| 3 | which lock guards which bootstrap operation | **bootstrap claims support only issue, reissue and redemption, all under `BootstrapSlot` (O).** **The generic `ReplaceClaim` and `RevokeClaim` are not available for bootstrap claims** (they require Governance evidence, which a bootstrap claim never has) → refused | §4 INV-CLM-6, §6.2 |
| 4 | **found while specifying (flagged): a bootstrap redemption after `operational` vs an ordinary issuance for the registrar's same R** used different slots | **Bootstrap operations take `BootstrapSlot` (O) then `ClaimSlot` (O, R); ordinary operations take `ClaimSlot` (O, R) only.** **One global order: `BootstrapSlot` → `ClaimSlot` → claim rows (by `ClaimId`)**; no cycle | §4, §6.2, §14 |
| — | proof cases | PI-31 adjusted; **PI-37 … PI-41** added (NOT EXECUTED) | §20 |

**Revision 8 → revision 9** (R8-04, as R9-01 and R9-02 of the Principal's commission; nothing else changed):

| # | Correction | Change | Section |
|---|---|---|---|
| R9-01 | **issuance and reissue not serialised against an in-flight redemption** (expired-but-`issued` row; redemption locked only the claim row) | **One lock protocol for every claim operation, ordinary and bootstrap: the slot first, then the claim row.**<br>- **Redemption now also takes the slot** (`ClaimSlot` (O, R) or `BootstrapSlot` (O)) before its claim row.<br>- So **issuance, reissue, replacement, revocation and redemption for one R (or one O's bootstrap) are fully serialised on one row.** An issuance waiting behind a redemption sees its committed result (R bound, or bootstrap redeemed) and refuses.<br>- **No historical rows need to be locked.** The order is the same everywhere, so there is no deadlock. Idempotency unchanged.<br>- *(The reviewer's suggested direction, locking every `issued` row during issuance, was evaluated; the slot-for-redemption protocol is chosen because it needs no ordered locking of row sets and serialises the same operations. **For the reviewer to judge.**)* | §4 INV-CLM-6, §6.2, §14 |
| R9-02 | **time sampling made exact** | **One `t_check` = `clock_timestamp()`, captured once after all locks of the operation, used for every expiry decision in it.** At issuance, `issuedAt := t_check` and `expiresAt := t_check + window`, derived from that single sample | §5 |
| — | proof cases | **PI-28 … PI-36** added (NOT EXECUTED); none renumbered | §20 |

**Revision 7 → revision 8** (corrections R8-01 … R8-03 from the Principal's review of revision 7; nothing else changed):

| # | Correction | Change | Section |
|---|---|---|---|
| R8-01 | **PostgreSQL expiry time** | **The expiry decision reads `clock_timestamp()` after the relevant lock is acquired**, never `now()` or `CURRENT_TIMESTAMP` (transaction start). This applies to redemption, issuance, bootstrap issuance and reissue, replacement and revocation. **Isolation:** READ COMMITTED. Under a stricter level, a serialization failure restarts the whole transaction with a new sample | §5, §6.2, §6.3, §14 |
| R8-02 | **bootstrap reissue idempotency** | **Every bootstrap issuance or deliberate reissue has its own stable `bootstrapOpId`**, recorded by provisioning before the call. Retries reuse it; a later reissue has a new one. **Unique per (O, `bootstrapOpId`)** | §4 INV-CLM-5, §5, §6.2, §9.3, §14 |
| R8-03 | **replacing an expired claim** | **`ReplaceClaim` and `RevokeClaim` act only on an *active* claim.** An expired invitation needs no withdrawal: **O uses `IssueClaim`**, which INV-CLM-1 allows | §6.2, §14 |
| — | proof cases | **PI-22 … PI-27** added (NOT EXECUTED); none renumbered | §20 |

**Revision 6 → revision 7** (findings R6-01 … R6-04 of the Principal's review of revision 6; nothing else changed):

| # | Finding | Change | Section |
|---|---|---|---|
| R6-01 | **first issuance cannot lock a claim row that does not exist yet** | **Issuance slots.**<br>- One `ClaimSlot` row per (O, R) and one `BootstrapSlot` row per O, **created if absent** (`INSERT … ON CONFLICT DO NOTHING`, under a unique key), **then locked `FOR UPDATE`**.<br>- Under that lock the active check uses **time-derived expiry**, so an expired claim never blocks a new one.<br>- **Lock order: slot → claim. Redemption takes the claim row only. No cycle.**<br>- The database's unique keys guarantee the slot; the lock serialises the active check. **Independent of P-I2** | §4, §5, §6.2, §14 |
| R6-02 | full Governance answer possibly stored centrally | **Identity receives the full answer, checks it, and does not persist it.** It persists only the consumed request identifier with O, act kind, claim id and time. **The full answer stays in O's own record of the act** | §5, §10.1, §14, §15, §16 |
| R6-03 | "Person" overstated | **Person = a platform identity record created by one registration.** Not a verified natural person. D-110's one-registration rule unchanged | §2, §0 item 5 |
| R6-04 | P-C and P-C2 not independent | **Bootstrap-window options renamed BW-1 / BW-2 / BW-3, with a combination table; invalid and open combinations named** | §21 |
| — | proof cases | **PI-18 … PI-21** added (NOT EXECUTED); none renumbered | §20 |

**Revision 5 → revision 6** (findings R5-01 … R5-05 of the Principal's review of revision 5; nothing else changed):

| # | Finding | Change | Section |
|---|---|---|---|
| R5-01 | stale "refuses calls without a reference" | §17 now uses the `AuthorityEvidence` contract (A1: one value; A2 replacement: two) | §17 |
| R5-02 | **A2 promised separation of duties it does not deliver** | **A2 stated as separate competences that one person may hold**, giving both answers in one signed-in session. **Not separation of persons, not dual control.** Different people for withdraw and issue would need a multi-person evidence contract, **not designed and not invented here** | §6.2, §21 P-A |
| R5-03 | B2 described as "none" centrally | **B2 = no founding people's names or addresses and no document bytes or decision texts centrally; the permitted D-191 evidence metadata does remain central** | §21 P-B |
| R5-04 | bootstrap "unique / once" vs reissue | **Three invariants distinguished: one-time redemption (INV-CLM-2), one active bootstrap claim (INV-CLM-4), and idempotent issue and reissue (INV-CLM-5)** | §4, §9.2, §9.3, §10.1, §12 C-I7, §17 |
| R5-05 | bootstrap claim expiry undefined | **Not determined by accepted sources:** the organisation's rules cannot yet be read through Governance before `operational`. **Stated as open question P-C2** with options; no rule invented | §21 P-C2 |
| — | stale revision references | §21 heading; §22 corrections listed per revision | §21, §22 |

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
| **Person** | **a platform identity record, created by one registration** (D-113); only `PersonId` and state. **It is not a verified natural person:** PublicDigit does not know that one Person is one human, nor that two Persons are two humans (§0 item 5). D-110's rule, one registration per person, is a rule for people, **not a property the system verifies** | D-113, D-110, XD-3 §2.1 |
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
| **Claim** | **INV-ID-6:** binds only an unbound R. **INV-ID-7:** only the code's hash is stored. **INV-ID-8:** after it ends, only what audit needs is kept. **INV-ID-9:** failed attempts throttled. **INV-CLM-1:** at most one active claim per (O, R). **INV-CLM-2 (one-time redemption):** at most one bootstrap claim per O is ever redeemed, **for O's whole lifetime. Enforced by the permanent marker `BootstrapSlot.redeemedAt`** (set once, never cleared while O exists), **not by claim rows or audit records**, which may be cleaned up. **INV-CLM-4 (one issued bootstrap claim):** at most one bootstrap claim per O is in state `issued` at any time, because every reissue revokes all `issued` bootstrap claims of O, active or expired. A fortiori, at most one is active. **INV-CLM-6 (serialisation points and order):**
- **every operation that issues, replaces, revokes or redeems an ordinary claim for (O, R) holds `ClaimSlot` (O, R)** for its whole transaction, before any claim row;
- **every bootstrap operation (issue, reissue, redemption) holds `BootstrapSlot` (O) and then `ClaimSlot` (O, R) of the registrar's R**, before any claim row;
- **global lock order: `BootstrapSlot` (O) → `ClaimSlot` (O, R) → claim rows in `ClaimId` order.** No operation takes them in another order;
- **`ReplaceClaim` / `RevokeClaim` refuse bootstrap claims.** **INV-CLM-5 (idempotent issue and reissue):**
- **each bootstrap issuance and each deliberate reissue carries its own stable `bootstrapOpId`**;
- a retry with the same `bootstrapOpId` creates no further claim and returns no code;
- a deliberate reissue (new `bootstrapOpId`), while none has been redeemed and O is in `provisioning`, revokes the active one and creates its replacement **in one transaction**. **INV-CLM-3:** a redeemed claim is terminal; it cannot be revoked, replaced or redeemed again |
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
| Claim | `ClaimId`, O, R, kind (`ordinary` / `bootstrap`), code hash, issuedAt, expiresAt, state (`issued`, `redeemed`, `revoked`), issuance id (**unique per (O, issuance id)**; bootstrap: **unique per (O, `bootstrapOpId`)**: one per initial issuance or deliberate reissue, §9.3), **authority request identifier(s)** (pointing to `ConsumedAuthorityRequest`; **not the Governance answer**), `replacesClaimId`, attempt count. **No `locked` state: P-A4 deferred** |
| ConsumedAuthorityRequest | `requestRef` request identifier, O, act kind, `ClaimId`, consumedAt. **Unique on the request identifier; makes each Governance answer single-use** (§10.1). **The only central trace of the Governance answer** (R6-02) |
| **ClaimSlot** | (O, R), **unique**. Created if absent; carries no data. **Serialises issuance for one R** (§6.2) |
| **BootstrapSlot** | O, **unique**. Created if absent. **Serialises every bootstrap operation for O.** **Permanent marker (revision 11):** `redeemedAt` (null until the bootstrap redemption) and `redeemedClaimId`. **No code, address, person, document or other data.**<br>- **Retention:** as long as O exists. It ends only with O's closure process (later release; [LQ]).<br>- **Why the slot carries data:** INV-CLM-2 must hold even after claim rows and audit records are cleaned up; **a marker on the row that already serialises bootstrap operations is the least-data place for it.** A tactical consequence of INV-CLM-2, not a new rule |
| Binding | O, R, `PersonId`, boundAt, `ClaimId` |

**Expiry is time-derived:**
- a claim is **active** iff `state = issued ∧ t_check < expiresAt`;
- **`t_check` = PostgreSQL `clock_timestamp()`, read in the same transaction *after* the lock that guards the check has been acquired** (the claim row for redemption, revocation and replacement; the slot for issuance);
- **never `now()` or `CURRENT_TIMESTAMP`:** those return the transaction's *start* time, which may precede a lock wait across the expiry;
- **one sample per operation (R9-02):** `t_check` is captured **once**, immediately after the operation has acquired **all** its locks (slot, then claim row where applicable). **Every expiry decision in that operation uses this one value**;
- **at issuance:** `issuedAt := t_check` and `expiresAt := t_check + window`, both from that single sample. **One clock (the central Identity database's) governs**; no application-server clock is used;
- **an `expired` mark is for reporting only.**

**Isolation [TD]:**
- claim operations run at **READ COMMITTED**: a `FOR UPDATE` that waited returns the row's latest committed version, and the check then reads `t_check`;
- **under REPEATABLE READ or SERIALIZABLE**, a concurrent change raises a serialization failure; **the whole transaction is retried from the start, with a fresh `t_check`.** Idempotency keys make the retry safe.

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

**Concurrency rule (revision 7, R6-01). Every claim operation runs in one central transaction:**

| Operation | Locks, in this order | Check under the locks |
|---|---|---|
| IssueClaim (first or later) | **1.** `ClaimSlot` (O, R): insert if absent (unique key), then `FOR UPDATE` | idempotency on (O, issuance id) first; then **read `t_check`**; **no claim of (O, R) with `state = issued ∧ t_check < expiresAt`** (an expired `issued` row does not block); R unbound |
| ReplaceClaim, RevokeClaim (**ordinary claims only**; a bootstrap claim → refused) | **1.** `ClaimSlot` (O, R); **2.** the claim row `FOR UPDATE` | **read `t_check`**; **the claim is *active*** (`issued` ∧ `t_check < expiresAt`). An expired claim → refused ("not active"); **O uses IssueClaim instead** (R8-03) |
| IssueBootstrapClaim / bootstrap reissue | **1.** `BootstrapSlot` (O): insert if absent, then `FOR UPDATE`; **2.** `ClaimSlot` (O, R) of the registrar's R, insert if absent, then `FOR UPDATE`; **3.** **every bootstrap claim row of O with `state = issued`** (active or expired; no expiry filter), **in `ClaimId` order**, `FOR UPDATE` | **after all locks:**<br>1. idempotency on (O, `bootstrapOpId`) → `AlreadyIssued`, no code;<br>2. **one `t_check`**;<br>3. **`BootstrapSlot.redeemedAt` is null** (read on the locked slot row, latest committed; INV-CLM-2), else refused, **even if every claim row has been cleaned up**;<br>4. R unbound;<br>5. **revoke every locked `issued` bootstrap row**; create the new claim with `issuedAt := t_check`, `expiresAt := t_check + window`.<br><br>**All in one transaction** (INV-CLM-4, -5) |
| Redemption (ordinary or bootstrap) | **0.** find the claim by code hash (plain read, to learn O, R and kind; **nothing decided on this read**); **1.** ordinary: `ClaimSlot` (O, R). **Bootstrap: `BootstrapSlot` (O), then `ClaimSlot` (O, R)**, each `FOR UPDATE`; **2.** the claim row `FOR UPDATE` | **read `t_check`** (after all locks); on the **re-read, latest committed** row: active; INV-ID-6 (R unbound); **for bootstrap: `BootstrapSlot.redeemedAt` is null (INV-CLM-2), and on success set `redeemedAt := t_check`, `redeemedClaimId` in the same transaction** |

**Why it holds:**
- The slot row is guaranteed unique by the database (unique key, insert-if-absent), so **two concurrent first issuances serialise on it**, whatever their issuance ids.
- **A partial unique index on "active" is not used**, because activity depends on the clock; the check runs under the slot lock instead.
- **Lock order is always `BootstrapSlot` → `ClaimSlot` → claim rows (by `ClaimId`), for every operation including redemption (INV-CLM-6).** Ordinary operations use a suffix of that order. No operation takes a lock out of order, so **no cycle can arise.**
- **Bootstrap operations also hold `ClaimSlot` (O, R):** a bootstrap redemption **after `operational`** therefore serialises with any **ordinary** issuance for the registrar's same R. That issuance waits, sees R bound, and refuses (INV-ID-6). Without it, the two would use different slots.
- **Under READ COMMITTED, a bootstrap reissue waiting on `BootstrapSlot` behind a bootstrap redemption** reads, after the wait, the redemption's committed result (`redeemed`) and refuses (INV-CLM-2). The rows it locks in step 3 are read in their latest committed state.
- **Why the in-flight-redemption race is closed (R9-01):** the slot is held by a redemption from before its time sample until its commit.
  - An issuance or reissue for the same R (or O's bootstrap) **waits on the slot until the redemption commits or rolls back.**
  - **Under READ COMMITTED** it then reads the latest committed rows: **the binding (R now bound) or the bootstrap claim `redeemed`**.
  - **So it refuses.** It never acts on the pre-commit view.
- **The re-read after locking:** under READ COMMITTED each statement after the lock sees the latest committed data, and `FOR UPDATE` on the claim row returns its latest committed version. **The decision uses only data read after the locks, and `t_check` read after them.**
- **The first committed act wins**; the others find a state that refuses them (redeem vs replace or revoke: unchanged from revision 6).

| Operation | Business act? | Authority checked in O before the call (option **A1**, recommended) | Under option A2 | Identity's checks | Effect |
|---|---|---|---|---|---|
| **IssueClaim(O, R, issuanceId, evidence)** | **yes**: O invites the person to bind | one `AuthorityEvidence`: `manage-claims` Authorised (actor = officer R, capacity = tenure, subject = (E, R)) | one `AuthorityEvidence`: `issue-claim` | R ∈ O; R unbound; no active claim; **evidence passes §10.1**; call from O (ISO-1) | new claim; **code returned once**; evidence consumed |
| **retry, same issuanceId** | no (same act) | — (the first answer stands) | — | claim exists for (O, issuanceId) | **`AlreadyIssued(ClaimId)`, no code** |
| **ReplaceClaim(O, oldClaimId, newIssuanceId, evidence[])** | **yes: one act** (withdraw one invitation and give another) | **one** `AuthorityEvidence`: `manage-claims`, subject = old claim id + new issuance id | **two** `AuthorityEvidence` values: **`revoke-claim`** (subject = old claim id) **and `issue-claim`** (subject = (E, R) + new issuance id), **each with its own request identifier**, **both for the same actor (the one signed-in officer) and business date** | old claim **active** (`issued` ∧ `t_check < expiresAt`; not redeemed, revoked or expired); **every evidence value passes §10.1** | **one transaction:** consume every evidence value; old → `revoked` (`replacesClaimId` recorded); new claim created. **Any evidence absent, stale, replayed, mismatched or not `Authorised`, or any other failure → full rollback: nothing consumed, the old claim stays active** |
| **RevokeClaim(O, ClaimId, evidence)** | **yes** | one `AuthorityEvidence`: `manage-claims` | one `AuthorityEvidence`: `revoke-claim` | claim **active**; evidence passes §10.1 | `revoked`; evidence consumed. A redeemed claim → refused (INV-CLM-3); **an expired claim → refused as not active** (nothing to withdraw) |
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
- **Check at redemption:** `t_check < expiresAt`, with `t_check = clock_timestamp()` read after the claim-row lock (§5).
- **Replacing an expired invitation (R8-03):** not a replacement. O issues a new claim (`IssueClaim`), which the slot rule allows because the expired claim is not active.

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
| 6c | `IssueReference(O, E)` → R; **`IssueBootstrapClaim(O, R, foundingDecisionRef, bootstrapOpId)`** (INV-CLM-2, -4, -5) | provisioning → Identity | R; claim hash |
| 6d | **code sent once to the registrar's address, in the same step, from the data being written into O's database** | provisioning, acting for O | **no address retained** |
| 8 | the registrar registers or redeems (§14) | Identity | binding |
| — | **redemption reported to O** (outbox; established). **Notice to the initial contacts (D-179, via `SendSecurityNotice`) only once Platform D-7 establishes their Account references**; until then, not available | Identity | audit |

### 9.3 Retry, expiry, reissue, challenge

| Case | Rule |
|---|---|
| **operation identities (R8-02)** | Provisioning **records each bootstrap operation's `bootstrapOpId` in its own step log before calling Identity**:<br>1. **initial issuance:** `bootstrapOpId` = op-1;<br>2. **retry of the initial issuance:** op-1 again → `AlreadyIssued`, no code;<br>3. **deliberate reissue:** a new id, op-2 → **every `issued` bootstrap claim of O (here op-1's, active or expired) revoked** and the op-2 claim created, in one transaction;<br>4. **retry of that reissue** (e.g. after a lost response): op-2 again → `AlreadyIssued`, no code, no further claim;<br>5. **a later deliberate reissue:** op-3, and so on.<br><br>**Only while no bootstrap claim has been redeemed** (INV-CLM-2) **and O is in `provisioning`** |
| crash during step 6 | provisioning resumes (idempotent on O). **Under B2 the submission of step 6a must be repeated if it did not complete.**<br>- **If the bootstrap claim was already issued under the same `bootstrapOpId`:** the retry returns `AlreadyIssued`, with no code (INV-CLM-5).<br>- **Because the code's delivery is then uncertain, provisioning performs a deliberate reissue:** the active claim is revoked and a new one created and delivered, in one transaction (INV-CLM-4) |
| code lost or expired, not redeemed | **reissue** while no bootstrap claim has been redeemed and O is still in `provisioning`.<br>- **Provisioning never reads the address back from O's database:** that would be reading business content, which D-166 forbids technical operations.<br>- **Under B2 the counterparty resubmits the registrar's address** (as messenger), and provisioning delivers the new code from that submission.<br>- After `operational`, a lost bootstrap code needs an ordinary claim, and so a `manage-claims` designation (P-A) |
| bootstrap redeemed | **no further bootstrap claim can be issued or reissued** (INV-CLM-2) |
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
- **Persistence (revision 7, R6-02):**

  | Question | Answer |
  |---|---|
  | **What Identity receives** | the complete Governance answer |
  | **What it verifies** | the table below |
  | **What it persists** | **only** `ConsumedAuthorityRequest` (request identifier, O, act kind, `ClaimId`, time) and, on the claim, the request identifier(s) |
  | **What remains exclusively in O's database** | **the complete answer (six fields + basis) as the basis of O's own claim act** (multi-database design §2, last row) |

  **No accepted decision authorises storing Governance evidence centrally, and none is needed.**
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

**Bootstrap exception:** `IssueBootstrapClaim` carries no Governance answer. Its basis is the founding decision reference, and it is accepted **only from provisioning, only in `provisioning`, and only while no bootstrap claim of O has been redeemed** (INV-CLM-2, -4, -5; C-I7).

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
| C-I7 | `IssueBootstrapClaim` / reissue | provisioning only, in `provisioning`; **one active at a time, one redemption ever, idempotent per `bootstrapOpId`** (INV-CLM-2, -4, -5) |
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
| **complete claim-first registration** | **one transaction:**<br>1. find the claim by code hash (plain read: O, R, kind); **lock by kind** (INV-CLM-6):<br>&nbsp;&nbsp;- **ordinary:** `ClaimSlot` (O, R) → claim row;<br>&nbsp;&nbsp;- **bootstrap:** `BootstrapSlot` (O) → `ClaimSlot` (O, R) → claim row;<br>&nbsp;&nbsp;all `FOR UPDATE`; then read `t_check`. **For bootstrap, check `redeemedAt` is null;**<br>2. check it is active and INV-ID-6;<br>3. create Person;<br>4. create Account (unique login, INV-ACC-1);<br>5. create Binding (unique (O, R), unique (O, `PersonId`));<br>6. claim → `redeemed`; **for bootstrap, set `BootstrapSlot.redeemedAt` and `redeemedClaimId`**;<br>7. outbox `ClaimRedeemed`;<br>8. **write `RegistrationCompletion`**;<br>9. delete the PendingRegistration |
| redeem while signed in | one transaction: find the claim by code hash; **lock by kind**:<br>- **ordinary:** `ClaimSlot` (O, R) → claim row;<br>- **bootstrap:** `BootstrapSlot` (O) → `ClaimSlot` (O, R) → claim row.<br><br>Then read `t_check`; check (for bootstrap, `redeemedAt` null); Binding; `redeemed` (for bootstrap, set the marker); outbox. **Both entry paths use one shared redemption service with this lock rule; no other path redeems a claim** |
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

**Claim operations:** the concurrency rule of §6.2 (slot → claim). Races:

| Race | Outcome |
|---|---|
| replace vs redeem | whichever commits first. Redeem first → the replace is refused (INV-CLM-3). Replace first → the redeem is refused (old claim `revoked`) |
| revoke vs redeem | the same rule |
| replace fails after selecting the old claim | **full rollback; the old claim is still active** |
| the same request retried | idempotent on issuance id (§6.2) |
| **two first issuances for one (O, R), different issuance ids** | **serialise on `ClaimSlot`:** the first creates a claim; the second finds it active and is refused (INV-CLM-1) |
| **two first bootstrap issuances for one O** | **serialise on `BootstrapSlot`:** idempotent if the same `bootstrapOpId`; otherwise the second finds an active claim and must be an explicit reissue with its own `bootstrapOpId` |
| **lock wait across expiry** | every check reads `t_check` **after** its lock (§5). A transaction that started before expiry but got the lock after it **sees the claim as expired** |
| **issuance after the earlier claim expired** | allowed: the expired `issued` row is not active |
| **bootstrap reissue vs bootstrap redemption** | **both: `BootstrapSlot` → claim.** **First to the slot wins**; after a redemption, the reissue finds a redeemed bootstrap claim → refused (INV-CLM-2) |

**Race interleavings (R9-01), ordinary and bootstrap:**

| Case | Interleaving | Outcome | Invariant |
|---|---|---|---|
| **A, ordinary** | 1. redemption locks `ClaimSlot`, then the claim; `t_check` < expiry (valid);<br>2. **issuance starts after expiry and waits on `ClaimSlot`;**<br>3. redemption commits (binding created);<br>4. issuance gets the slot and reads the latest committed data | **R is bound → issuance refused.** No active claim is left for a bound R | INV-ID-6, INV-CLM-1, INV-CLM-6 |
| **B, ordinary** | 1. issuance takes `ClaimSlot` first; `t_check` ≥ old claim's expiry (inactive), R unbound → new claim committed;<br>2. **redemption of the old code waits on the slot**, then locks the old claim and reads `t_check` | **the old claim has expired → refused.** The new claim is the only active one | INV-CLM-1, expiry |
| **A, bootstrap** | 1. bootstrap redemption takes `BootstrapSlot`, then the claim; valid;<br>2. **reissue starts after expiry and waits on `BootstrapSlot`;**<br>3. redemption commits (`redeemed`);<br>4. reissue reads the latest committed rows | **a redeemed bootstrap claim exists → reissue refused.** No new bootstrap claim | INV-CLM-2, INV-CLM-6 |
| **B, bootstrap** | 1. reissue takes `BootstrapSlot`, then `ClaimSlot`, then the `issued` rows; **every `issued` bootstrap row (active or expired) → revoked**, and the new one created and committed;<br>2. **redemption of the old code waits** on `BootstrapSlot`, then reads it `revoked` | **refused.** At most one redemption ever, of the new claim only | INV-CLM-2, INV-CLM-4 |
| **bootstrap redemption after `operational` vs ordinary issuance for the registrar's R** | 1. bootstrap redemption holds `BootstrapSlot`, then `ClaimSlot` (O, R);<br>2. **ordinary `IssueClaim` for the same R waits on `ClaimSlot`;**<br>3. the redemption commits (R bound);<br>4. the issuance sees R bound | **refused** (INV-ID-6).<br>**Reverse order** (ordinary issuance takes `ClaimSlot` first):<br>- **while the bootstrap claim is active**, the issuance is refused, because INV-CLM-1 counts every claim of (O, R), bootstrap included;<br>- **if the bootstrap claim has expired**, the ordinary claim is issued, and the waiting bootstrap redemption then reads `t_check` ≥ `expiresAt` and **is refused**.<br><br>**Either way, at most one claim of R is active and R is bound at most once** | INV-CLM-1, INV-CLM-6, INV-ID-6 |
| **expired but `issued`, reissue after expiry** | reissue holds the slot, so **no redemption of that row can be in flight** (any redemption would hold the slot) | the expired row is treated as inactive; the new claim is issued | INV-CLM-6 |

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

**O records its own claim acts with their complete authority answers; Identity keeps only the consumed request identifiers (§10.1).**

**Retention:**
- audit and ended claim rows: [LQ];
- **bootstrap claim rows: not before O has left `provisioning`;**
- **the `BootstrapSlot` marker: for O's lifetime, independent of audit retention** (INV-CLM-2).

## 16. Privacy and minimisation

**Central data:**
- Person;
- Account;
- PendingRegistration (deleted on completion or expiry);
- RegistrationCompletion (24 h);
- references;
- claims (hashes; authority request identifiers only);
- consumed authority requests (identifiers and provenance, **no answer content**);
- issuance slots (`ClaimSlot`: no data; **`BootstrapSlot`: the redemption marker only**, a time and a claim id);
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
| officer without authority | authority checked by Governance in O. **Identity refuses any call whose required `AuthorityEvidence` value(s) do not all pass §10.1** (A1: one value; A2 replacement: two). **A bare identifier is never evidence** |
| bootstrap abuse | one redemption ever and one active claim per O; fingerprint match; reported to O; challengeable |
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
| **PI-18** | **two concurrent first `IssueClaim` calls for one (O, R) with different issuance ids:** exactly one active claim; the other refused |
| **PI-19** | **two concurrent first bootstrap issuances for one O:** one active bootstrap claim; a same-operation retry is idempotent and returns no code |
| **PI-20** | **issuance after the earlier claim expired** succeeds; a same-issuance retry stays idempotent; reissue vs redemption obeys first-committed-wins |
| **PI-22** | **redemption across expiry:** the transaction starts before `expiresAt`, waits for the claim lock until after it, and **is refused**; for ordinary and bootstrap claims alike |
| **PI-23** | **issuance across expiry:** the transaction starts before the old claim's `expiresAt`, waits for `ClaimSlot` / `BootstrapSlot` until after it, and **treats the old claim as inactive** (the new claim is issued) |
| **PI-24** | **no claim becomes usable because its operation began before expiry:** the checks never use `now()` or `CURRENT_TIMESTAMP`; under a stricter isolation level a serialization failure restarts with a fresh `t_check` |
| **PI-25** | **bootstrap operation identities:** initial retry (op-1) idempotent; deliberate reissue (op-2) revokes and creates atomically; **a retry of op-2 after a lost response creates nothing and returns no code**; a later reissue (op-3) works; **after a redemption every further op is refused** (INV-CLM-2) |
| **PI-26** | **bootstrap reissue vs redemption**, both orders: first committed wins; never two redemptions |
| **PI-42** | **bootstrap claim-first registration** acquires `BootstrapSlot` → `ClaimSlot` → claim row before `t_check` (statement order) |
| **PI-43** | **signed-in bootstrap redemption** acquires the same three locks in the same order |
| **PI-44** | **no alternate path:** only the shared redemption service redeems claims; a bootstrap claim presented through any path is processed with `BootstrapSlot` |
| **PI-45** | **the marker survives cleanup:** after deleting ended bootstrap claim rows and their audit records, `redeemedAt` remains and **a reissue is refused** |
| **PI-46** | **redemption vs reissue with the marker**, both orders: the marker is set in the redemption transaction; a waiting reissue reads it and refuses; a `bootstrapOpId` retry stays idempotent before cleanup, and after O leaves `provisioning` any bootstrap issuance is refused |
| **PI-47** | **no deadlock** under `BootstrapSlot` → `ClaimSlot` → claim rows with the marker update in the mix |
| **PI-37** | **bootstrap operations never bypass `BootstrapSlot` (O):** issue, reissue and redemption all hold it, then `ClaimSlot` (O, R); `ReplaceClaim` / `RevokeClaim` on a bootstrap claim are refused |
| **PI-38** | **an expired-but-`issued` bootstrap claim cannot be redeemed**, and **after any reissue no `issued` bootstrap row other than the new one exists** |
| **PI-39** | **all locks precede the single `t_check`** in every bootstrap operation (verified by statement order) and the expiry decision uses that sample only |
| **PI-40** | **bootstrap redemption after `operational` vs ordinary `IssueClaim` for the same R:** serialised on `ClaimSlot` (O, R); outcomes as §14 in both orders; no deadlock |
| **PI-41** | **no deadlock under the global order** `BootstrapSlot` → `ClaimSlot` → claim rows (by `ClaimId`), with mixed bootstrap and ordinary operations |
| **PI-28** | **ordinary, case A:** redemption holds `ClaimSlot` after a valid pre-expiry `t_check`; issuance started after expiry **waits, then observes the committed binding and is refused**; no active claim remains for the bound R |
| **PI-29** | **ordinary, case B:** issuance takes the slot first and issues; the waiting redemption of the expired old code **is refused**; one active claim |
| **PI-30** | **bootstrap, case A:** redemption holds `BootstrapSlot` after a valid pre-expiry `t_check`; reissue started after expiry **waits, observes `redeemed`, is refused**; no new bootstrap claim |
| **PI-31** | **bootstrap, case B:** reissue takes the slots first; **every `issued` bootstrap claim of O, active or expired, is revoked**; the waiting redemption is refused; **never two bootstrap redemptions** |
| **PI-32** | an **expired row still `issued`** is covered: no operation on it, or on its R, can proceed without the slot |
| **PI-33** | **no new bootstrap claim after a concurrent bootstrap redemption commits** (repeat of PI-30 under load and many interleavings) |
| **PI-34** | **no active ordinary claim for an R bound by a concurrent redemption** (repeat of PI-28 under load) |
| **PI-35** | **idempotency under the new protocol:** retries of the same issuance id or `bootstrapOpId` while another operation holds the slot create nothing and return no code |
| **PI-36** | **no deadlock:** concurrent mixes of issue, replace, revoke, redeem (ordinary and bootstrap) and registration complete or fail cleanly; **every transaction takes slot before claim** (verified by inspection of the lock order and by load) |
| **PI-27** | **`ReplaceClaim` / `RevokeClaim` on an expired claim are refused as not active; `IssueClaim` then succeeds** |
| **PI-21** | **central Identity storage contains no Governance answer content:** only the request identifiers and provenance of §10.1 |
| **PI-17b** | **security assessment, not a test:** is integrity protection of Governance answers beyond the trusted server-side process boundary needed? **Its outcome is a finding, not a pass** |

## 21. Principal decision brief (rebuilt for revision 6; nothing here is accepted)

### P-A: who may authorise claim codes (inviting a recorded person to bind their login to the organisation's record)

**A claim establishes an identity binding. It grants no membership and no access.**

**Relevant decisions:**
- D-167 (no default authority);
- D-12 (authority at the act);
- D-190 (route for new act kinds).

| Option | Issue / replace / revoke | Consequence |
|---|---|---|
| **A1: one competence, `manage-claims`** | one designation covers all three | **simpler:** an organisation designates one office or body for the whole invitation lifecycle. **The competence to invite includes the competence to withdraw** |
| A2: `issue-claim` and `revoke-claim` | **separate competences**; replace needs two answers (revoke + issue), each with its own request identifier, consumed together, **both from the one signed-in officer** | an organisation can give inviting and withdrawing to different offices, **but a replacement needs one person who holds both.** **It is not separation of persons and not dual control:** two different people cannot jointly perform one replacement under this evidence contract, which checks every answer against the single signed-in actor. **Requiring different people would need a multi-person evidence contract, not designed here.** Two designations are needed before codes are fully manageable |

**Under both options:**
- **no lasting right from having issued:** authority is checked at each act (D-12);
- **rules silent → `Undetermined`** (D-167): **founding documents should name the office;**
- **the evidence contract (§10.1) is the same.**

| | |
|---|---|
| **Dependency** | §10.2: the Principal's authorisation (this decision), then the Governance addition note (draft §10.3), **ratified in the I-TDD-02 acceptance act** |
| **Recommendation** | **A1**, for a simpler baseline. **It is a governance-policy choice.** A2 separates *competences* only (one person may hold both), not *persons* |
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
| **B2 (recommended)** | **no founding people's names or addresses; no document bytes or decision texts. The permitted D-191 evidence metadata remains central:** fingerprints, document references, dates, sender, match results | the counterparty resubmits the documents and the founding contacts' names and addresses at provisioning, straight into O's database; **resubmits again if provisioning fails** | **no** |

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
| **Can be decided now?** | **B2: yes** (the only remaining option; it is the minimum-data option, not "no central data"). **B-T1 vs B-T2: possible, but better after D-7.** **Choosing B2 does not mean accepting B-T1** |

### P-C: how long an ordinary claim code stays valid

| Option | Rule | Consequence |
|---|---|---|
| **C1 (recommended)** | the organisation's rules or a decision they empower set the window; **silent → 14 days**; **platform-wide maximum 30 days**, capped and recorded by Identity | e.g. rules say 7 → 7; say 20 → 20; silent → 14; ask for 45 → 30 |
| C2 | no default; every issuance states a window up to 30 days | the officer chooses each time |

| | |
|---|---|
| **Basis** | **14 and 30 are product-policy proposals.** D-171 is a precedent only for the *method* (a labelled default that assigns no authority), **not for these values** |
| **Can be decided now?** | **yes** |

### P-C2: how long the founding registrar's bootstrap claim stays valid (open question)

**Not determined by accepted sources:**
- P-C's organisation rule is read through Governance in O's context, which **cannot answer before O is `operational`**;
- the bootstrap claim is issued during `provisioning`.

**Options (renamed from revision 6 to avoid confusion with P-C's C1 / C2):**

| Option | Rule |
|---|---|
| **BW-1** | the bootstrap claim uses **P-C's platform default** |
| **BW-2** | a period stated **in the founding decision**, capped at 30 days; **if absent, BW-1** |
| **BW-3** | the founding decision **must** state a period (≤ 30 days); **if absent, no bootstrap claim is issued** and provisioning stops until it is supplied |

**The two decisions are not independent:**

| P-C | P-C2 | Valid? | Bootstrap window |
|---|---|---|---|
| **C1** (14-day default) | **BW-1** | **yes (recommended)** | 14 days |
| C1 | BW-2 | yes | the founding decision's period, else 14 days |
| C1 | BW-3 | yes | the founding decision's period, required |
| C2 (no default) | BW-1 | **no:** there is no platform default to use | — |
| C2 | BW-2 | **open:** valid only if the founding decision states a period; **its fallback is undefined** | needs a separate bootstrap default, **a further Principal choice** (no value proposed here) |
| C2 | BW-3 | yes | the founding decision's period, required |

| | |
|---|---|
| **Recommendation** | **C1 with BW-1**, subject to the Principal's decision |
| **Until decided** | the bootstrap window is a proposal; §9.3's reissue path works under every valid combination |

## 22. Residual register

| Class | Items |
|---|---|
| Corrected in revision 4 | authority-evidence contract (§10.1); Governance route and draft note (§10.2–§10.3); P-A4 withdrawn from the design (deferred); address trust assumption (§9.5); B2 processing (§9.4) |
| Corrected in revision 5 | A2 two-answer replacement; single `AuthorityEvidence` input; initial-contact notice conditional on D-7; B1 withdrawn; full impersonation consequence; upload path under PI-16; PI-17a / PI-17b split |
| Corrected in revision 11 | lock rules per claim kind on both redemption entry paths; permanent `BootstrapSlot` redemption marker for INV-CLM-2; retention split (marker vs audit) |
| Corrected in revision 10 | bootstrap sequence (locks before `t_check`, no expiry filter); reissue revokes every `issued` bootstrap claim; bootstrap operations limited to issue, reissue and redemption, under `BootstrapSlot` → `ClaimSlot`; cross-slot case closed |
| Corrected in revision 9 | one serialisation point per R (redemption takes the slot too, INV-CLM-6); one `t_check` after all locks; issuance times derived from it |
| Corrected in revision 8 | PostgreSQL expiry time sampled after the lock (`clock_timestamp()`); bootstrap operation identities; replace and revoke only on active claims |
| Corrected in revision 7 | first-issuance concurrency (slots); Governance answer not persisted centrally; Person = platform identity record; P-C / P-C2 combinations |
| Corrected in revision 6 | stale reference wording (§17); A2 = separate competences, not dual control; B2 central metadata stated; bootstrap invariants INV-CLM-2 / -4 / -5; bootstrap expiry raised as P-C2 |
| Remaining tactical | P-I2 mechanism (INV-REF-2 required); merge procedure; throttling values [TU] |
| **Principal decisions** | **P-A (A1 / A2); P-A4 (defer); P-B (B2) and, separately, B-T1 / B-T2 (better after D-7); P-C (C1 / C2); P-C2 (BW-1 / BW-2 / BW-3, constrained by P-C)** |
| Governance | the claim act kinds (§10.2); `end-membership` (D-194); X-3 |
| Platform | D-7 (initial contacts' Accounts) |
| Legal | retention; `PersonRef` classification; resignation effective time |
| Proofs not executed | **PI-1 … PI-16, PI-17a, PI-18 … PI-47**, plus the **assessment PI-17b** (PI-14 includes the 24 h retention and cleanup; PI-16 the upload path) |

## 23. Traceability

| Requirement | Section | Accepted decision changed? |
|---|---|---|
| D-195 (1) Account → R | §7 | no |
| D-110, D-113 (Person) | §2 (platform identity record) | **no:** meaning clarified, rule unchanged |
| multi-database design §2 (O owns its claim acts and their basis) | §10.1, §15 | no |
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
| **Unexecuted proofs** | PI-1 … PI-16, PI-17a, PI-18 … PI-47; assessment PI-17b |

- **Revision 11 changes:** both redemption entry paths lock by claim kind; the permanent `BootstrapSlot` marker enforces INV-CLM-2 independently of audit retention.
- **Revision 10 changes:** the exact bootstrap lock sequence and lifecycle; bootstrap operations limited to issue, reissue and redemption; one global lock order; the cross-slot case.
- **Business rules:** none changed. The cross-slot outcomes follow INV-CLM-1, which counts bootstrap claims of R, and INV-ID-6.
- **Remaining technical uncertainty:** lock contention and throughput under load (PI-36), and the exact SQL realisation of the find-then-lock step in redemption. Both are [TU], **not design gaps**.
- **Still proposed:** the whole design.
- **Principal decisions:** P-A, P-A4, P-B, B-T1 / B-T2, P-C, P-C2.
- **External dependencies:** Governance act kinds (§10.2), `end-membership`, X-3; Platform D-7; legal questions.
- **Revision 11 is ready for independent review.**
- **PI-1 … PI-16, PI-17a and PI-18 … PI-47 remain unexecuted; the assessment PI-17b has not been carried out.**
- **No acceptance or ratification has occurred.**
- **No accepted decision was changed. No implementation performed or authorised. No proof claimed passed.**

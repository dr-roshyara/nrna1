# I-TDD-02: Identity claims and bindings (tactical design)

| | |
|---|---|
| **Status** | **PROPOSED tactical design.** It decides nothing, ratifies nothing and authorises no implementation. Written by Claude, **not independent**. It needs **independent review before the Principal considers acceptance** (D-195; scope §4 item 7) |
| **Commission** | **D-195** (scope accepted with three additions), Principal 2026-11-11; Principal commission "I-TDD-02 — Identity Tactical Design" |
| **Starting point** | scope `2026-11-10-identity-i-tdd-02-scope.md` (**IS**), as trimmed by the pre-TDD adversarial review §7–§8 (**AR**); I-TDD-01 (**I1**, proposed) where not superseded |
| **Binding** | **D-75** (XD-3 RAT, conditions C-1 … C-5, §2 items 1–8); D-100, D-101; **D-110, D-111, D-113**; D-148; D-164; D-166; D-174 … D-193 (Platform, incl. **X-2**); **D-194**; **D-195** |
| **Entry criteria (IS §2, AR §8)** | E-1: met by **D-113**. D-113 is option (d) "reference at import, Person at claim" (P1 brief line 39). E-2: **not required under (d)** (AR §8). E-3: removed (AR §8). E-4: met by **D-195**. **No conflict found** |
| **Placement** | `docs/publicdigit/architecture/reviews/`, beside the scope (same placement rule as IS) |

**Labels:** [AD] accepted decision · [BF] fact · [AC] architectural conclusion · [TD] tactical design · [TU] proof · [EXT] another context · [P] proposal · [OQ] Principal question · UNKNOWN.

## 0. Findings first

1. **[AC] Identity establishes *who*, never *what they may do*.**
   - A binding says: this signed-in Account is this organisation's `PersonRef` R.
   - It says nothing about belonging, membership, office, authority or access (D-164; multi-database design ISO-7; XD-3 §2.4).
   - Every act is decided by its own context, with R as the actor.
2. **[AC] Two resolutions, two purposes, two consumers:**

   | Resolution | Answers | Consumer |
   |---|---|---|
   | **R-1** Account → `PersonRef` (per organisation) | who is acting here? | every organisation context, on every request |
   | **R-2** `PersonRef` → Account handle (X-2) | which login is this contact? | **Platform only**, for its contacts |

   The two must not be merged into one general lookup.
3. **[P] Possible tension with XD-3 §2.3 and §5 (cross-organisation correlation).**
   - If R-2 returned the raw `AccountId`, Platform could see that one Account is a contact for organisations A and B.
   - **This design returns an organisation-scoped `ContactHandle` instead (§8).** Identity alone maps it to the Account.
   - **Whether this needs a conformance note on the accepted Platform design** (which keys contacts "by Account", rev 4 M-5) is for the independent reviewer. **This design does not change Platform.**
4. **[EXT] Claim issuing needs Governance act kinds that do not exist yet** (`issue-claim`, `revoke-claim`; GTS correction note 4 names them only as examples).
   - **They need a Principal authorisation like D-190.** Until then, only the bootstrap claim (§9) can be issued.
   - **Business consequence:** where an organisation's rules and founding decision name no office competent to issue claims, **no member can be given access until a committee designates one** (D-167). **[AC]: founding documents should name that office.**
5. **[AC] Under D-113 (option (d)) I1's `anchored` flag and its question P-I7 disappear** (AR §1 item 6). Registration creates a Person; an organisation's record gets only an opaque reference.

## 1. Purpose and scope

**Purpose:** let a person who registered once with PublicDigit act, with their own identity, inside an organisation that recorded them. **No e-mail matching, no takeover and no cross-organisation disclosure.**

**In scope** (IS G-1 … G-16 as trimmed by AR §7, plus D-195):
- PersonRef issuance;
- claim;
- redemption;
- binding;
- R-1;
- R-2;
- bootstrap claim;
- refusal on unavailability;
- neutral errors;
- audit;
- concurrency;
- the merge rule;
- the users-mapping principle.

**Out** (IS §1, commission §9):
- multi-organisation discovery (P-I2: only the seam);
- account recovery (P-I3: boundary only);
- merges beyond "binding is not a merge";
- Election and Voting;
- Finance;
- authentication provider (Keycloak);
- user interface;
- code and migrations;
- Platform redesign.

## 2. Business language

| Term | Meaning | Source |
|---|---|---|
| **Person** | the one identity of a human **who has registered**; holds only `PersonId` and state | D-113, XD-3 §2.1 |
| **Account** | the login a Person controls; authenticates its holder, grants nothing else | D-110, I1 §2.2 |
| **`PersonRef` (R)** | an organisation's opaque number for one person it recorded; **issued at import or entry, before any Person exists** | D-113, XD-3 §2.2 |
| **Claim** | a one-time, organisation-issued proof of possession that lets the person bind their Account to R | D-113, XD-3b §2a |
| **Binding** | the recorded fact "R of organisation O is Person P". **Binding is not a merge** | D-113 |
| **Bootstrap claim** | the single claim per organisation issued without an officer, for the founding registrar | D-148, D-178, D-195 |
| **ContactHandle** | an organisation-scoped opaque handle for the Account bound to a Platform contact's R (§8) | [P] |

**Five separate concepts, never derived from one another [AD]:**
1. **identity resolution** (Identity);
2. **belonging** (Organisation);
3. **membership** (Membership);
4. **authority** (Governance);
5. **access and entitlement** (each consuming context).

## 3. Responsibility of the Identity context

| Owns (central, D-164) | Does not own |
|---|---|
| Person, Account, authentication, sessions | the organisation's record of the person, contacts, profile (D-101) |
| `PersonRef` issuance (`ReferenceIssuer`) | belonging, placement, membership, office, authority |
| Claims and their lifecycle | who may issue claims (Governance) |
| Bindings R ↔ Person | access to any content; routing (Platform) |
| R-1 and R-2 answers | the decision to act on them |

## 4. Aggregates and invariants

| Aggregate | Why it exists | Invariants it protects |
|---|---|---|
| **Person** | the one identity of a registered human | **INV-ID-1:** holds only `PersonId` and state (`active` / `mergedInto`); no organisation, name or contact. **INV-ID-2:** a merged Person takes no new binding |
| **Account** | authentication | **INV-ID-3:** the login identifier authenticates its holder only; never used to find, create or bind (XD-3 §2.6). **INV-ID-4:** an Account is bound to a Person only through redemption or claim-first registration. **INV-ID-5:** suspension and deletion only with authority evidence; never caused by membership or access changes (XD-3 §2.7) |
| **IssuedReference** (R) | the organisation's opaque number | **INV-REF-1:** R is issued to exactly one organisation and is meaningless elsewhere. **INV-REF-2:** issuance is idempotent on (organisation, entry E) (multi-database design §4 step 2) |
| **Claim** | proof of possession for one R | **INV-ID-6 (takeover guard):** binds only an R that is unbound. **INV-ID-7:** only a hash of the code is stored; shown once. **INV-ID-8:** after it ends, the claim keeps only what audit needs. **INV-ID-9:** failed attempts are throttled. **INV-CLM-1:** at most one active, unexpired claim per (organisation, R) (IS G-3) |
| **Binding** (R → Person) | the one place identity crosses into an organisation | **INV-BND-1:** an R is bound to at most one Person. **INV-BND-2:** a Person is bound to at most one R of the same organisation; a second is refused and reported to that organisation (D-113). **INV-BND-3:** bindings are created only by redemption; never edited; ended only by Identity's explicit acts (merge or recovery, deferred) |

**Rejected (ASP, from I1 §2.4, still valid):**
- an `Affiliation` inside Identity (C-4);
- a working-organisation aggregate (grants nothing);
- e-mail as identity evidence;
- social sign-in (removed, P-I5).

**Rejected now:** `anchored` (unneeded under (d)).

## 5. Models

| Model | Fields (minimal) | Storage |
|---|---|---|
| Person | `PersonId`, state | central |
| Account | `AccountId`, `PersonId`, login identifier (verified flag), credential, state | central |
| IssuedReference | `PersonRef` R, organisation O, issuedAt; **no data about the person** | central. **SEAM P-I2:** stored (L1) or derived (L2); **R-1 and R-2 need only bound references, which are stored as bindings in either case** (AR §2 row "Effect on P-I2") |
| Claim | `ClaimId`, O, R, kind (`ordinary` / `bootstrap`), code hash, window, state, issuance reference (O's issuance id), authority reference, attempts | central |
| Binding | O, R, `PersonId`, boundAt, claimId | central (the "association" of D-164) |
| ContactHandle | O, R, handle; resolves to `AccountId` inside Identity only | central [P] |

**PersonRef issuance:** the organisation context requests R for its entry E (`IssueReference(O, E)`). Identity returns the same R on retry. **R carries no data.** An unbound R is harmless (multi-database design §4 step 2).

## 6. Claim and binding lifecycles

```
Claim:   issued ──redeem──▶ redeemed (terminal)
           │ ├──revoke (issuing organisation)──▶ revoked (terminal)
           │ └──window passes──▶ expired (terminal)
           └──reissue for same (O, R)──▶ previous claim revoked in the same transaction, new one issued

Binding: (none) ──redemption──▶ bound ──[deferred: merge / recovery, Identity's explicit act]──▶ ended
```

| Operation | Preconditions | Authoritative checks | State change | Outcome / failure |
|---|---|---|---|---|
| **IssueClaim(O, R, issuanceId, authorityRef)** | called from O's context (ISO-1); R issued to O | **in O: Governance `issue-claim` Authorised** (§10) before the call. **Identity:** R ∈ O; R unbound; no active claim for (O, R) unless `reissue` | claim `issued`; code shown once to the issuer | idempotent on (O, issuanceId). R already bound → **refused** ("already has access", to O only) |
| **RevokeClaim(O, claimId)** | claim issued by O | Governance `revoke-claim` Authorised (in O) | `revoked` | idempotent |
| **RegisterWithClaim(code, login, credential)** | — | login address **verified first** (D-113); then as redemption | Account + Person created, then bound | neutral failure (§12) |
| **RedeemClaim(code)** while signed in | verified login | as redemption | bound | neutral failure |
| **Redemption** (domain service, one central transaction, §14) | claim `issued`, inside window | INV-ID-6, INV-BND-1, INV-BND-2, INV-ID-2 | claim `redeemed`; binding created; outbox `ClaimRedeemed` to O | each refusal neutral to the person; **INV-BND-2 refusal also reported to O** (D-113) |
| **RegisterAccount(login, credential)** (no code) | — | verified login | Account + Person; **no binding, no affiliation** | — (account creation ≠ admission) |

- **Claim window [TD]:** the organisation's rule sets it, within a platform maximum (IS G-11).
  - Where the rules are silent: **14 days, labelled platform default (D-167)**. It assigns no authority.
  - **Platform maximum: 30 days.**
- **Throttling:** platform settings (INV-ID-9). Values: [TU].
- **Delivery of a code:** by the organisation through its own channel (IS step 4), using its own contact data (D-101). **Never via the login address** (INV-ID-3).

## 7. R-1: Account → `PersonRef` in organisation O

| | |
|---|---|
| **Operation** | `ActingRef(session, O) → R \| NotBound \| Unavailable` |
| **Caller** | the entry layer of O's context (ISO-1, ISO-3), **for every authenticated request and job acting for a person** |
| **Input** | the authenticated session (Account) and the request's single organisation O |
| **Output** | O's own R only. **Never** another organisation's R, the `PersonId`, the Account's other bindings, or names |
| **Rule** | the Binding (O, R, P) where P = the session Account's Person, as known at the request's τ |
| **Consistency** | bindings are append-only and change only by redemption or Identity's explicit acts. **Every request reads afresh**; no cross-request cache beyond the session's own validation [TD] |
| **Failure** | `NotBound` → only public endpoints of O (application form, ISO-3); `Unavailable` → **the request is refused** (D-195 (3); multi-database design §6) |

**Uses within the minimum package (Identity supplies only R; the consuming context decides):**

| Act | R is | The consuming context checks |
|---|---|---|
| read or post in a forum | the reader or author | Organisation belonging (D-152), D-161 / D-168 standing; moderator via Governance |
| officer acts (admit, renew, suspend, restore, end, place, issue claim) | **actor** | Governance AuthorityCheck with actor R |
| **resignation (D-194)** | **declarant = subject** | Membership records `End(resignation)`. **The declarant is the member; an office that records it is only the recorder.** M-TDD-01 must keep the two roles separate [EXT] |
| editor sends a newsletter | actor | `send-newsletter(series)` |
| registrar records from evidence | actor | `record-from-evidence` (GTS correction note 1) |
| apply for membership | applicant | Membership application (account creation ≠ admission) |

## 8. R-2: `PersonRef` → Account, for Platform (X-2, D-193)

| | |
|---|---|
| **Operation** | `ContactHandleFor(O, R, purpose = platform-contact) → H \| NotBound \| Unknown \| Unavailable` |
| **Caller** | **Platform (tenancy) only**, enforced by the caller's service identity; purpose fixed |
| **Input** | O and one of O's references R, from O's own records (contact designations, D-174 / D-191) |
| **Output** | **H, an organisation-scoped opaque handle** for the Account bound to R. **H for the same Account differs between organisations.** No `AccountId`, `PersonId`, login address or other bindings |
| **Companion operations (Identity-owned)** | `ContactHandleOf(session, O) → H \| none`: is the signed-in approver this contact? (Platform's approval checks, D-182 / D-186). `SendSecurityNotice(H, notice)`: D-179 notices, which **Identity** sends to the login address. **Platform never learns the address** |
| **Organisation boundary** | R must be issued to O (INV-REF-1); otherwise `Unknown`. H is valid only together with O |
| **Missing, ended or ambiguous binding** | `NotBound` (no binding) or `Unknown` (R not O's, or the binding ended by a merge or recovery): **Platform fails closed** (D-193) |
| **Staleness** | Platform must call `ContactHandleOf` at each approval (same τ as its approval transaction's read). **A stored H is a reference, not an authorisation** [TD] |

**[P] Note for the reviewer:**
- Platform revision 11 keys contacts "by Account" (rev 4 M-5) and sends D-179 notices to "the verified login address".
- **H is offered as the Account reference Platform stores, and notices go through Identity.**
- This changes no Platform rule, but its wording may need a **conformance note**. **The Principal's acceptance act would decide that; this design does not.**

## 9. Founding registrar's bootstrap claim (D-148, D-178, D-195 (2))

| Question | Answer |
|---|---|
| **Why needed** | before the first claim there is no bound officer, so no `issue-claim` check can name an actor. Without an exception, no organisation could ever start [AC] |
| **Who initiates** | **Platform provisioning**, during `provisioning` (D-185 lifecycle), **before `operational`** (multi-database design §3 step 3; Platform rev 3 F-03, M-3) |
| **Entitlement evidence** | **the founding decision naming the registrar**, recorded from evidence and challengeable (D-148, D-159). **Authority kind = asserted.** The claim records it as its authority reference. **PublicDigit creates no authority** (D-1a, D-167) |
| **Subject** | the registrar's own entry E in O's database, created by provisioning from the founding decision; R issued for it as for anyone |
| **Delivery [TD]** | to the contact address **stated for the registrar in the founding documents**, stored in O's own person profile (D-101). **Not via the counterparty** (D-175 makes it a messenger for documents, not for credentials) |
| **One time** | **unique (O, kind = bootstrap, state ∈ {issued, redeemed})** in the central Claim table. **Once a bootstrap claim is redeemed, no bootstrap claim can ever be issued for O.** Until then, a re-issue (lost or expired code) revokes the previous one in the same transaction, on the same founding evidence |
| **No circularity** | it needs no binding and no AuthorityCheck. Its authority is the founding decision, the only exception to §10 (Platform rev 4 M-3; D-178 conformance note) |
| **Failure or retry** | redemption fails neutrally like any claim. A second redemption is refused (single use). After the bootstrap is redeemed, **all later claims need `issue-claim`**, including a replacement registrar's |
| **Audit** | issuance, re-issue, redemption and failures are audited (§15). **The redemption is reported to O's initial platform contacts** (D-179 security notice, via H). The organisation can challenge it (D-148) |

## 10. Governance collaboration (AuthorityCheck) [EXT]

**Needed act kinds; not yet defined. They need a Principal authorisation like D-190, then a Governance addition note:**

| | `issue-claim` | `revoke-claim` |
|---|---|---|
| Action | issue an ordinary claim for one of O's entries | revoke an unredeemed claim |
| Actor / capacity | the officer's R / their tenure (pulled at the act, contract review §3) | same |
| Subject (`requestRef`) | (entry E, R) + O's issuance id | the claim id |
| Organisation, business date | O; date of issuing | O; date of revoking |
| Evidence | six fields + basis (GTS correction notes 1, 4) | same |
| `Authorised` | the organisation context calls `IssueClaim` with the authority reference | calls `RevokeClaim` |
| `NotAuthorised` / `Undetermined` | **no call; refused**; recorded in O. Rules silent → `Undetermined` until designated (D-167) | same |

- **Where the check runs:** **in O's context** (Governance is in O's database, D-164).
- **What Identity records:** the authority reference, and that the call came from O (ISO-1).
- **Identity does not re-evaluate competence.** It only refuses calls without a reference or from another organisation [AC].

## 11. Platform collaboration (X-2)

- **R-2 (§8) is the Identity-owned operation that D-193 X-2 requires.**
- **Platform's fail-closed conditions are unchanged:** no binding, an unknown reference or Identity unavailable means no promotion and no approver.

## 12. Cross-context contracts and failures

| # | Contract | Direction | Failure |
|---|---|---|---|
| C-I1 | `IssueReference(O, E) → R` | O → Identity | Identity down: the entry waits for R; **no R invented** |
| C-I2 | `IssueClaim` / `RevokeClaim` | O → Identity | refused unless Authorised; idempotent |
| C-I3 | `ClaimRedeemed(O, R)` event | Identity → O (outbox → inbox, idempotent on claim id) | delayed delivery: O marks the entry "claimed" later; **access does not depend on it** (R-1 reads the binding) |
| C-I4 | R-1 `ActingRef` | O's entry layer → Identity | refuse the request |
| C-I5 | R-2 `ContactHandleFor` / `ContactHandleOf` / `SendSecurityNotice` | Platform → Identity | fail closed |
| C-I6 | `BindingRefused(O, R)` (INV-BND-2) | Identity → O | O learns a second reference was refused; **nothing about other organisations** |

**Neutral error contract (IS G-4):**
- one message for unknown, expired, revoked, used, throttled and "already has access";
- **it never reveals whether an Account or Person exists**, or anything about another organisation (XD-3 §6).

## 13. Temporal consistency

| Fact | Business time | Knowledge time | Rule |
|---|---|---|---|
| Claim | issuedAt, window | recordedAt (= issuedAt) | not back-dated |
| Binding | boundAt (redemption) | recordedAt (= boundAt) | **never back-dated**: access starts when claimed |
| R-1 / R-2 answers | the request's τ | τ | **one τ per operation (D-73)**: the context's other reads use the same τ; incomplete → refused |

**Append-only:**
- claims change state only forward;
- bindings are never edited;
- an end (merge or recovery, deferred) is a new fact.

## 14. Transactions and concurrency

**Redemption is one central transaction:**
1. lock the claim row `FOR UPDATE`;
2. check state `issued` and the window;
3. insert the Binding under **unique (O, R)** (INV-BND-1) and **unique (O, `PersonId`)** (INV-BND-2);
4. set the claim to `redeemed`;
5. write the outbox `ClaimRedeemed`;
6. commit.

| Race | Result |
|---|---|
| two redemptions of one code | the row lock: one wins, the other sees `redeemed` → neutral failure |
| two claims for the same R | INV-CLM-1 prevents two active; if both somehow redeem, unique (O, R): the second fails |
| two of O's references redeemed by one Person | unique (O, `PersonId`): the second is refused and reported (C-I6) |
| issue vs redeem vs revoke | the same row lock; first wins |
| bootstrap re-issue vs redemption | the same row and unique bootstrap key |

**No step spans two databases** (multi-database design §4).

## 15. Audit and provenance (IS G-5)

**Audited (central, content-free):**
- reference issued;
- claim issued, re-issued, revoked, expired, redeemed;
- failed attempts (per code and per client, counts only);
- binding refused;
- R-2 calls (purpose, O, outcome);
- security notices.

**Never recorded in audit:**
- codes;
- login addresses beside an organisation;
- other organisations' references.

**Organisation side:** O records its issuance (officer, entry, authority answer) in its own database (multi-database design §2 last row). **Retention: open (XD-PROV, [LQ]).**

## 16. Privacy and minimisation

- **Central data:**
  - Person (`PersonId`, state);
  - Account;
  - issued references (O, R; SEAM P-I2);
  - claims (hashes);
  - bindings;
  - handles.
- **No names, no contacts, no affiliations beyond bindings** (C-4).
- `PersonRef`, bindings and handles are **pseudonymous personal data** ([LQ] classification, multi-database design §2).
- **R-1 returns only O's own R. R-2 returns only an O-scoped handle.**

## 17. Security boundaries (threat walk-through, IS §4 item 4)

| Threat (XD-3 §5; I1 TH-1 … TH-6) | Answer |
|---|---|
| one organisation takes over a person's identity | INV-ID-6: a claim binds only an unbound R; **re-binding is recovery (P-I3), never a claim** |
| leaked or forwarded code | single use, window, revocable, throttled; O sees `ClaimRedeemed` and can request review |
| cross-organisation correlation | R per organisation; R-1 returns O's R only; **R-2 returns O-scoped handles (§8)**; neutral errors |
| e-mail confusion | INV-ID-3 |
| an officer without authority issues claims | `issue-claim` in O before the call; Identity refuses calls without an authority reference |
| bootstrap abuse | unique per organisation, founding evidence, reported to initial contacts, challengeable |
| Platform reads identity | R-2 is purpose-bound, Platform-only and O-scoped; no `AccountId` or addresses |

## 18. Recovery and exceptional cases

| Case | Rule |
|---|---|
| lost login address | **P-I3 (deferred)**; never solved by an organisation's claim (INV-ID-6) |
| merge of two Persons | **rule only (IS G-9):** a merged Person takes no new binding; its bindings resolve to the survivor; **two of O's references then point at one Person: reported to O** (D-124 for elections) |
| Account suspended or deleted | R-1 → `NotBound` behaviour for that session; **the binding and O's records are untouched** (XD-3 §2.7) |
| membership ended (D-194) | **no Identity effect** (XD-3 §2.7) |
| existing `users` (IS G-12) | principle: each user → one Account + one Person; their current organisations' records get references; migration is a separate ADR |
| legacy flows (IS G-15) | the first slice replaces the election-only voter invitation (AR §7); others → implementation planning |

## 19. Open dependencies and questions

| # | Item | Owner | Effect until resolved |
|---|---|---|---|
| D-1 | `issue-claim`, `revoke-claim` act kinds (§10) | **Principal authorisation (as D-190)**, then Governance | **only bootstrap claims**; no member access |
| D-2 | `end-membership` act kinds (D-194) | Governance completion | Identity unaffected; Membership cannot record End |
| D-3 | X-3 decision-log rules | Governance | Identity unaffected (it records references, not evaluations) |
| D-4 | the R-2 handle vs Platform's "by Account" wording (§8) | **reviewer, then Principal at acceptance** | — |
| D-5 | P-I2 (L1 / L2) | Identity, later | issuance mechanism behind `ReferenceIssuer`; R-1 and R-2 unaffected |
| D-6 | retention, legal classification | counsel | — |

**[OQ]: none needed to complete this design.** D-1 is a Principal authorisation, raised when Governance completion is commissioned.

## 20. Acceptance criteria and proof obligations

**Acceptance criteria (IS §4):**
- every element traces to an invariant;
- G-items answered or deferred with a seam (§1, §18, §19);
- no contradiction with D-75, D-100, D-101, D-113, D-164, D-193, D-194;
- threat walk-through done (§17);
- **independent review before Principal acceptance.**

**Proof obligations [TU] (all NOT EXECUTED; one sentence each, RED-ready):**

| # | Property |
|---|---|
| PI-1 | two concurrent redemptions of one code: exactly one binding; the loser gets the neutral message |
| PI-2 | an R already bound cannot be bound again by any claim (INV-ID-6) |
| PI-3 | a Person redeeming a second of O's references is refused, and O receives `BindingRefused` with nothing about other organisations |
| PI-4 | `ActingRef(session, O)` never returns another organisation's R, and refuses when Identity is unavailable |
| PI-5 | `ContactHandleFor` returns different handles for the same Account in two organisations, and refuses non-Platform callers |
| PI-6 | at most one bootstrap claim is ever redeemed per organisation; re-issue revokes the previous one atomically |
| PI-7 | `IssueClaim` without an authority reference, or from another organisation's context, is refused |
| PI-8 | neutral errors are byte-identical across all failure causes, and logs hold no code or address |
| PI-9 | the binding is never back-dated, and R-1 reflects it from redemption onward |
| PI-10 | registration without a code creates no binding and no affiliation |

**Verdict (author's): READY FOR INDEPENDENT REVIEW. Not accepted; no implementation authorised.**

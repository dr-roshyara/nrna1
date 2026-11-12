# Multi-database boundary (D-164): validation of Addendum M and tactical design — PROPOSED

| | |
|---|---|
| **Status** | **PROPOSED.** Records no ruling. Written by Claude, **not independent** (author of Addendum M) |
| **Commissions** | "Strategic Baseline Correction and Tactical Design Readiness" and "Multi-Database Boundary: Tactical Domain Design and Strategic Baseline Validation", both accepted by the Principal 2026-11-11 |
| **Binding** | **D-164**; D-75 (XD-3 RAT), D-101, D-104, D-110, D-111, D-113, D-31 / D-93, E-11 (tenancy is a platform capability, D-106) |
| **Consumes** | Identity: XD-3 act §2–§6; **I-TDD-01** (`2026-11-09-i-tdd-01-identity-participant-access-tactical-design.md`, P, other session): `Claim` with T8–T12, INV-ID-3/4/6/7/9, `ReferenceIssuer` SEAM (P-I2 open); coherence review (2026-11-11) §2 and Addendum M |

**Labels:** [RAT] · [AD] · [BF] · [AC] · [TD] · [TU] · [WD] · [P] · [OQ] · [LQ] · UNKNOWN · **SEAM** (I-TDD-01's word: an open decision that plugs in without changing the design around it).

## 0. Disagreements (first)

1. **The first review's Problem 1** ("one database, one deployable" contradicts D-164) **targets a superseded text.**
   - The coherence review and Addendum M already applied D-164. **"One deployable" stays as the simplicity goal** (D-164 governs storage, not deployment).
   - The stale sentence in the integrated design §7 is now marked superseded (§9).
2. **Its Problems 5–7 were answered in the coherence review:**
   - §2B: consumers derive `InForceUnrestricted`; Organisation forwards Membership's intervals;
   - §2E: a time-bounded suspension ends by its own interval, which is not a Restore;
   - §2C: an admission naming a unit is **two act kinds, `admit` + `place-member`**.

   **One refinement on suspension evidence** (§7 V-6).
3. **Addendum M said some background jobs may continue while the central database is down.** **I withdraw that** in favour of one fail-closed rule (§6). It was an unforced exception.
4. **Addendum M treated "association" as a central record of its own. It is not:** it is **Identity's binding** (D-113) seen from the routing side (§2). No new central concept.

## 1. Business explanation

- A person registers once. **PublicDigit's centre knows their account and, for each organisation, only an opaque number** that organisation uses for them: issued by Identity, meaningless elsewhere.
- **When the person claims their record in an organisation** with that organisation's one-time code, **the centre links their account to that number.** From then on the platform knows **where** to send their requests for that organisation.
- **Nothing else crosses:** whether they are a member, what they may read, what office they hold. **All of that is decided inside the organisation's own database**, by the organisation's own records and rules.
- **If an organisation's database is down, only that organisation stops. If the centre is down, everything waits:** nobody's data is lost, and nobody gets in by a side door.

## 2. Three kinds of ownership (corrects the matrix)

| Fact | Domain owner (context) | Information owner | Storage |
|---|---|---|---|
| Person, Account, login, sessions | Identity | the person / PublicDigit | central |
| **Issued `PersonRef` (opaque, per organisation, unbound or bound)** | Identity (`ReferenceIssuer`) | PublicDigit (infrastructure). **Pseudonymous, linkable to a person by Identity, so treated as personal data** ([LQ] legal classification for counsel; IR-R1-02 M-5) | central |
| **Binding of a `PersonRef` to a Person = the "association"** | **Identity** (D-113) | PublicDigit | central |
| **Claim** (code hash, subject `PersonRef`, window, state) | **Identity** (I-TDD-01) | PublicDigit | central |
| **Organisation registry entry** (identity, hostnames, database locator, platform status) | **Platform / tenancy capability** (E-11; context-owner naming HD-8 open, no business effect) | PublicDigit | central |
| Official geographical references | Reference Geography | PublicDigit (shared reference) | central |
| Organisation's record of the person (profile, contacts, capacities) | Organisation | **the organisation** | organisation |
| Units, belonging, placement, workspace grants | Organisation | the organisation | organisation |
| Membership facts | Membership | the organisation | organisation |
| Committees, offices, decisions, authority | Governance | the organisation | organisation |
| Forums, posts, moderation | Deliberation | the organisation | organisation |
| Series, issues, dispatch, opt-outs | Communication | the organisation | organisation |
| (later) elections, voting, finance | Election, Voting, Finance | the organisation | organisation |
| **Claim issuance record on the organisation side** ("code issued to entry E by officer X") | Organisation (its act, with Governance authority) | the organisation | organisation |

**Co-location ≠ merger:** each context owns its tables inside the organisation database; architecture tests forbid cross-context table access [AC].

**Completeness:** every fact named in the six flows, the registry, the binding, the claim and the reference data appears **exactly once** in the domain-owner column.

## 3. Organisation lifecycle

**Platform status** (registry, central). **Operational, not business:**

| Status | Meaning | Requests |
|---|---|---|
| `provisioning` | registry entry exists; database being created and migrated | refused (not available) |
| `operational` | database reachable and at the current schema | routed |
| ~~`unavailable`~~ | *(superseded by §13.6: reachability is health, not a status; a failed migration is `schemaState = migration-failed`)* | |
| `suspended` (later) | platform-side stop (e.g. service ended) | refused; data untouched |
| `closed` (later) | closure decided; export and retention apply ([LQ]; B-5) | refused; **never "drop database"** without the retention process |

**Organisation business readiness is not a status.** It is **derived from the organisation's own records:**
- founding documents and registrar recorded (D-148);
- designations made (D-149, D-150, D-154).

Example: "nobody can admit yet" is simply `Undetermined` from AuthorityCheck. **No state is added for it** [AC].

**Creation flow (two-step, idempotent):**
1. Registry entry `provisioning` (central, key = organisation id).
2. Database created and migrated (idempotent).
3. Founding registrar's account invited via a claim, as for anyone (§4).
4. Registry `operational`.

**A crash at any step resumes from the recorded status.** No compensation is needed: an unfinished entry is never routed.

## 4. Person reference and claim (consumes D-113 and I-TDD-01)

| # | Step | Initiator → owner | Storage | Durable result | Retry and duplicates |
|---|---|---|---|---|---|
| 1 | Organisation creates its record of the person (application, import, registrar entry) | Organisation | organisation | entry E (pending ref) | idempotent on E |
| 2 | **Request `PersonRef` for (organisation, E)** | Organisation → **Identity `ReferenceIssuer`** | central | issued R (unbound) | **idempotent on (organisation, E)**: the same R is returned; **if step 3 fails, R is an unbound opaque number with no data: harmless; the retry gets it back** |
| 3 | Store R on E | Organisation | organisation | E has R | idempotent |
| 4 | Officer issues a claim for E (AuthorityCheck in the organisation) | Organisation → **Identity `IssueClaim(R)`** | central (claim), organisation (issuance record) | Claim (single use, window, revocable: T8–T10) | idempotent on (organisation, E, issuance id); the organisation records the issuance after Identity confirms; **an orphan claim expires by its window** |
| 5 | Person authenticates (verified login address) | Identity | central | session | — |
| 6 | **Redeem**: code + verified login address | Identity | **central, one transaction** | **binding R → Person** (D-113); claim consumed | **takeover guard INV-ID-6**; single use; **a second redemption is refused neutrally** (T8); "at most one of an organisation's references per Person" (D-113) |
| 7 | Organisation learns of the redemption | Identity → Organisation (outbox event `ClaimRedeemed`) | organisation | E marked "claimed" | idempotent on claim id |

**No step spans two databases.**
- Steps 2, 4 and 6 are single central transactions.
- Steps 1, 3 and 7 are single organisation transactions.

**Claiming a record ≠ membership:** step 6 links an account to the organisation's record of the person. **Whether that person is a member is Membership's**, unchanged.

**Open and owned elsewhere (not decided here):**
- **SEAM P-I2:** how `ReferenceIssuer` issues and resolves (L1 / L2);
- **SEAM K4 / `ClaimSubject`:** settled for this purpose by D-113 (subject = the organisation's `PersonRef`);
- code length, window length and throttling: I-TDD-01 [TD].

## 5. Isolation invariants and proof

| # | Invariant | Enforced by |
|---|---|---|
| **ISO-1** | **Exactly one organisation context per operation**, set at entry (request or job), immutable for its duration | entry middleware / job envelope |
| **ISO-2** | The organisation connection is **derived only from the registry entry** of that context; **no code path names a database directly** | connection resolver |
| **ISO-3** | **For an authenticated request, the context's organisation must be one the account is bound to** (§2 binding), unless the endpoint is a public one (application form, login). **A conflicting organisation id in route, body or header → refused** | entry check |
| **ISO-4** | **Every `PersonRef` received inside an organisation is checked to belong to that organisation** (present in its own records, or confirmed by Identity for the current organisation) | Organisation application layer |
| **ISO-5** | **No operation opens a second organisation connection**; cross-organisation questions exist only inside Identity (none in release 1) | architecture test + resolver guard |
| **ISO-6** | **Jobs carry the organisation id in their envelope; a worker refuses a job without one; the connection is reset between jobs** (no reuse across organisations) | job middleware |
| **ISO-7** | **The binding grants routing only:** no organisational check reads it as membership, access or authority | architecture test |

**Tests:**
- **two organisation databases, one account bound to both:**
  - each request sees only its own organisation;
  - switching the id in a request is refused;
  - a forged `PersonRef` from B is refused in A;
- **a worker processing alternating A and B jobs** never reads across;
- **a job without context** is refused;
- **concurrent requests for A and B** in one worker process stay separate.

## 6. Central database availability (one rule)

**Recommendation [AC]: fail closed.** Every interactive request and every job needs the centre for session validity, binding (ISO-3) and routing (ISO-2). **If the centre is down, everything waits.**

| Operation | Needs the centre | When the centre is down |
|---|---|---|
| New login | yes | refused |
| Existing session | yes (session, binding) | refused ("service unavailable"); session resumes afterwards |
| Organisation selection, routing | yes | refused |
| Running request | completed only if its context was resolved before the outage; **no new central read is attempted** | — |
| Background jobs | yes (routing) | **paused and retried**; none runs without a fresh registry lookup |
| Recovery | — | nothing to reconcile: no operation proceeded on stale routing |

**Why not keep sessions alive on cached routing:**
- it would add token validity, revocation lag and a second source of truth for routing (security and consistency obligations) **for availability release 1 does not need**;
- **it can be designed later** if the centre's availability proves insufficient [TU].

**The central database is a single point of failure for release 1**, accepted and stated.

## 7. Validation of Addendum M

| # | Addendum M claim | Verdict |
|---|---|---|
| V-1 | Central holds identity, association, registry, reference data | **Confirmed**, refined: "association" = Identity's binding (§2) |
| V-2 | No identity projection in organisation databases | **Confirmed** (D-111, XD-3 §5: personal data organisation-local; central bare) |
| V-3 | Different `PersonRef` per organisation gives structural isolation | **Partly**: it removes the join key, **but isolation is proved by ISO-1 … ISO-7, not by identifiers alone** |
| V-4 | Two-step crossings | **Confirmed, made precise** (§3, §4) |
| V-5 | Some jobs continue while the centre is down | **Withdrawn** (§6) |
| V-6 | A time-bounded suspension ends by its interval | **Evidence:** ratified final §3 and §7 make a suspension a **restriction interval** and Restore an act that "ends a suspension only". **The Suspend payload's `until`** (one decision suspending for a stated period) **is [TD] within the ratified "interval"**, not ratified wording. **Business meaning:** the decision's own stated end. **No Restore is implied or recorded** |
| V-7 | No contradiction with accepted decisions | **Confirmed against** D-75 §2–§6, D-110, D-111 (physical tenancy left open; D-164 closes it), D-113, D-31 / D-93, E-11 |

## 8. Tactical design (central side; organisation contexts unchanged)

| Element | Kind | Responsibility | Invariants |
|---|---|---|---|
| `OrganisationRegistryEntry` | aggregate (Platform capability) | identity, hostnames, database locator, platform status | status transitions §3; never routed unless `operational` |
| `ReferenceIssuer` | Identity domain service (**SEAM P-I2**) | issue / resolve `PersonRef` per organisation | idempotent per (organisation, local entry); opaque; **pseudonymous personal data** (M-5) |
| `Claim` | Identity aggregate (I-TDD-01) | issue, revoke, expire, redeem | T8–T12; INV-ID-6 |
| `Binding` | inside Identity's Person (D-113) | `PersonRef` → Person; read for routing | at most one reference per organisation per Person; never authority (ISO-7) |
| `OrganisationContext` | value object | the one organisation of an operation | ISO-1 |
| Job envelope | value object | carries `OrganisationContext` | ISO-6 |

**Transaction boundaries:**
- one database per transaction, always;
- crossings per §3 and §4;
- **compensation is never needed**, because every intermediate state is harmless (unrouted entry, unbound reference, expiring claim).

**Published language:** `PersonRef` (opaque), `ClaimRedeemed`, `OrganisationContext`.

**Effect on the four product areas:**
- **Membership, Forum and Newsletter run entirely inside one organisation database**, under ISO-1 … ISO-6.
- **Geography** is read from the centre only at stated positions (later); **no organisation writes centrally.**

## 9. Correction to the integrated design

- §7's "**one database, one deployable**" is superseded: **one deployable; one central database and one database per organisation (D-164).**
- Its §3 matrix is superseded by §2 above.

## 10. Proof obligations [TU]

1. ISO-1 … ISO-7 tests (§5).
2. Idempotency of steps 2, 4 and 7 with a crash between each.
3. The registry status machine.
4. The per-database migration runner, marking failures `schemaState = migration-failed` (F-16).
5. A per-organisation backup and restore drill.
6. Worker connection reset.

**Carried forward:** the ledger clock, grouped renewal, tree invariants.

## 11. Principal decisions

**None.**
- Claim rules come from D-113 and I-TDD-01.
- Availability follows from fail-closed (D-73 spirit).
- Lifecycle statuses are operational.

**Deferred (B-5):** closure, export and retention, with [LQ].
**Owned by the Identity session:** P-I2 (reference issuance mechanism).

## 12. Verdict

**STRATEGIC BASELINE COHERENT, WITH SPECIFIED CORRECTIONS:**
- §0.3 (V-5 withdrawn);
- §0.4 (association = binding);
- §9 (stale integrated-design text).

**Tactical design ready for Principal review, after an independent check of:**
- §4 (claim flow against I-TDD-01);
- §5 (isolation invariants).

**This is a self-review, not independent validation.**

---

## 13. Revision 2 (2026-11-11): Identity and multi-database coherence

*Commission: "Identity and Multi-Database Boundary Coherence", accepted by the Principal. Self-review, not independent.*

### 13.0 Disagreement (first): P-I1 / K4 is decided

- **D-113** (decision record §11.42, recorded 2026-11-10) states verbatim: "**Answers K4 and P-I1.**"
- **Its content (R2b):**
  - "**When an organisation records someone who has not registered, Identity issues that organisation's opaque `PersonRef` without creating a Person**";
  - the Person is created when the person registers and validly claims;
  - "**the claim code is tied to the organisation's `PersonRef`, not to a `PersonId`**";
  - "no Person is created at import; **the reference is still issued at import**".
- **I-TDD-01** (2026-11-09) **predates D-113**. Its "open K4 seam" was closed the next day.
- **The review conflated two different things:**
  - **issuing the reference early** (decided: yes);
  - **creating the Person early** ("eager creation": decided: no).
- **This design's §4 steps 1–3 are D-113 exactly.**
- **No decision brief is prepared, because asking the Principal to re-decide D-113 would reopen an accepted decision without new evidence.**
- **Classification:** P-I1 / K4 [AD] D-113. **Still open in the Identity session:** P-I2 (issuance mechanism), C-5 (merge policy).

### 13.1 `ClaimRedeemed`: corrected meaning

| I-TDD-01 (line 93) | Corrected meaning (this design; to be carried into the Identity session's design) |
|---|---|
| "that person now has **access** to that organisation" | "**this organisation's `PersonRef` R is now bound to a registered Person; requests by that person's Account may be routed to this organisation.**" **It grants nothing else**: no membership, voting, office, authority, forum or newsletter right, no workspace access. **The organisation may only:** mark its entry "claimed", show the person their own record, and let its own rules decide everything else |

*(I-TDD-01 is another session's proposal and is not edited here; the correction is recorded for that session.)*

### 13.2 Authority contract for claim issuance and revocation

**Who decides (Governance) and who owns the claim (Identity) stay separate. Identity verifies provenance; it never re-decides authority.**

1. **Trusted path only.** `IssueClaim` / `RevokeClaim` exist **only as an internal port** called by Organisation's application service **inside an operation whose `OrganisationContext` was set at entry (ISO-1).** **There is no public endpoint.**
2. **Identity checks, from its own records:**
   - **R was issued for that same organisation** (its `ReferenceIssuer` record);
   - the context's organisation = R's organisation.
   - **A `PersonRef` from another organisation is refused.**
3. **The command carries the AuthorityCheck answer** (six fields + basis) for act kind `issue-claim` / `revoke-claim`, with `outcome = Authorised`, **same organisation**, and **`checkedAt` within a short freshness window** [TU].
   - **Identity stores it on the Claim as provenance.**
   - **Identity refuses a missing, foreign, stale or non-Authorised answer. It never infers authority from R, a binding or an organisation id.**
4. **A later change of authority** (officer's term ends) **does not void issued claims.** As for decided acts (D-12), the organisation **revokes** them if it wishes.
5. **Revocation versus redemption race:**
   - both act on the one central Claim row under its version;
   - **whichever commits first wins**;
   - revoking a redeemed claim is refused (`AlreadyRedeemed`);
   - **undoing a binding is not revocation** (a separate Identity act, policy C-5, open in the Identity session).

### 13.3 Crossings: durable state and recovery (corrects "no compensation needed")

| # | Operation | Owner | Durable state | Transaction | Idempotency key | Recovery |
|---|---|---|---|---|---|---|
| 1 | Local entry created | Organisation | entry E | organisation | E | — |
| 2 | **Request R** | Identity | issued R (unbound) | central | (organisation, E) | **retry returns the same R** |
| 3 | Store R on E | Organisation | E.R | organisation | E | **reconciliation:** entries without R are re-requested |
| 4 | **Intent to issue** (written first) | Organisation | `ClaimIssuance(id, E, pending)` | organisation | issuance id | — |
| 5 | `IssueClaim(id, R, authority)` | Identity | Claim (code hash, window) + **outbox `ClaimIssued`** | **central, atomic** | issuance id | retry returns "already issued" |
| 6 | Record result | Organisation | `ClaimIssuance(id, issued)` | organisation | issuance id | from `ClaimIssued` (inbox) **or** reconciliation |
| 7 | Redeem | Identity | **binding + claim consumed + outbox `ClaimRedeemed`** | **central, atomic** | claim | single use |
| 8 | Receive `ClaimRedeemed` | Organisation | inbox record + E "claimed" | organisation | **event id** (inbox unique) and claim id | at-least-once delivery |

**Lost response at step 5:**
- **the code's plain text is shown once and Identity keeps only its hash**, so a retry cannot recover it;
- **retry answers "already issued"**; the organisation then **revokes and issues anew** (new issuance id);
- **the first code is never usable without the person receiving it, and it expires.**

**This is forward recovery** (retry, reconcile, revoke-and-reissue), **not compensation**: no committed fact is undone [AC].

**Step 8 when the organisation's database is down:**
- **already true:** the binding (routing works from now on), the claim consumed, `ClaimRedeemed` committed in Identity's outbox;
- **not yet true:** the organisation's entry shows "claimed";
- **delivery:** the relay retries with backoff, under the event's organisation context (ISO-9); the organisation inbox deduplicates by event id; processing is idempotent by claim id;
- **meanwhile** the person can be routed, **but sees nothing beyond what the organisation's own rules allow.** "Not yet marked claimed" changes no right.

**The organisation's record has changed before delivery:**
- the event names R;
- **if R is held by a retired or merged entry**, the organisation records the event against R and **reports it to its registrar for review**;
- **the Identity binding stays (routing only).** **Retirement and merge of references are UNKNOWN** (Identity session C-5).

**Reconciliation, per organisation, periodic:**
- Identity answers "**which of this organisation's references are bound**" (**only its own references, never to whom**: XD-3 §3);
- the organisation compares this with its "claimed" marks and repairs them.

### 13.4 Reference (`PersonRef`) lifecycle at this boundary

| Question | Answer | Status |
|---|---|---|
| Who may request R | Organisation's application service, via the internal port, in its `OrganisationContext` | [TD] |
| Idempotency key | (organisation, local entry id) | [TD] |
| Re-issue after failure | the same R | [TD] |
| Invalidate, replace, retire; local merge of duplicates | **UNKNOWN**: D-113 fixes "at most one of an organisation's references bound to one Person; a second is refused and reported"; **the rest is C-5**, Identity session | open (not Principal-now) |

### 13.5 Isolation invariants, tested against execution paths

| # | Protected truth | Enforcement | Failure behaviour | Proof |
|---|---|---|---|---|
| ISO-1 | one organisation per operation | entry (request, job, inbox, relay) | no context → refused | missing-context tests per entry type |
| ISO-2 | connection only from the registry | resolver | unknown or non-operational → refused | resolver tests |
| ISO-3 | context ∈ the account's bindings (non-public endpoints) | entry check | conflicting route / body / header ids → refused | conflicting-id tests |
| ISO-4 | every R received belongs to the context's organisation | Organisation application layer; Identity port check (§13.2) | refused | forged-R tests in both directions |
| ISO-5 | no second organisation connection | resolver guard + architecture test | exception (fail closed) | test |
| ISO-6 | jobs carry context; connection reset between jobs | worker middleware | refused | alternating A/B worker test; **connection-reuse test in one long-running process** |
| ISO-7 | binding ≠ authority | architecture test | — | test |
| **ISO-8 (D-166)** | **platform staff access only by the organisation's grant:** technical operations (provisioning, migrations, backups, restores) need the **joining grant** (a registry fact) and explicit context, and never read business content; **content access needs a case grant recorded in the organisation's own database (owned by Organisation)**; every access recorded and reported to the organisation; **no grant → refused** | operations commands check the grant | refused | tests: no grant → refused; case grant → recorded and reported |
| **ISO-9 (new)** | **an inbox event is processed only in the organisation named in the event, and only if that organisation is its rightful recipient** (e.g. R belongs to it) | inbox handler | mismatch → rejected, alerted | misrouted-event test |
| — | public endpoints (application form, login) | no binding required, **but an organisation context still required** for organisation-scoped public forms | — | test |
| — | concurrent requests for A and B | per-request context (no global state) | — | concurrency test |
| — | delayed `ClaimRedeemed` | §13.3 | — | delay test |
| — | outages | §6, §13.6 | fail closed | outage tests |

### 13.6 Availability and registry, refined

**Durable registry statuses:**
- **`provisioning` · `operational` · `suspended` (later) · `closed` (later)**;
- **plus a durable `schemaState`** (current / migration-failed), which blocks routing until repaired.

**Reachability is a health observation, not a status** (the reviewer is right: §3's `unavailable` is withdrawn as a status).

**Release 1 working design (fail closed):** the centre is needed by every authenticated request (session, binding, routing) and every job (routing).

| Operation | Needs the centre | Why |
|---|---|---|
| New login, existing session, selection, routing | yes | session store, ISO-3, ISO-2 |
| Already-running request | no new read | context resolved at entry |
| Organisation-internal jobs (e.g. newsletter dispatch) | **yes, for routing at job start** | ISO-2 |
| Identity → organisation event delivery | yes (source) | outbox is central |
| Organisation → Identity calls (request R, issue claim) | yes | target |
| After recovery | resume; nothing proceeded on stale routing | — |

**Not assumed for ever:** a later design may let organisation-internal jobs run on a signed routing snapshot [TU; later], if central availability proves insufficient.

### 13.7 Effect on the four product areas

**Membership, Forum and Newsletter are unchanged.** They run inside one organisation database under ISO-1 … ISO-9. **A redeemed claim lets the person's requests be routed to the organisation; what the member area shows is decided by the organisation's rules: belonging and `InForceUnrestricted` (D-152, D-161).** **Geography is unaffected.**

### 13.8 Principal decision

**One genuine question: platform staff access to organisation data (ISO-8).**
- D-111 says PublicDigit does not become the owner of an organisation's data.
- **Whether PublicDigit's own operators may ever look at an organisation's business content** (e.g. to support a problem) is a trust and privacy promise to the parties, not a technical choice.
- **Asked separately.**

### 13.9 Verdict (revision 2)

**STRATEGIC BASELINE COHERENT, WITH SPECIFIED CORRECTIONS:**
- §13.0: P-I1 stands as decided (D-113);
- §13.1: `ClaimRedeemed` meaning;
- §13.2: authority contract;
- §13.3: recovery model;
- §13.6: registry status versus health.

**Tactical design ready for Principal review once ISO-8's policy is decided**, then an independent check of §13.2 and §13.3.

**Still open elsewhere, not Principal-now:**
- P-I2;
- C-5 (reference retirement and merge);
- closure and retention ([LQ]).

**Decision (2026-11-11): D-165.** Operators look at business content only on the organisation's written request or a legal obligation; every access is recorded and reported to the organisation. ISO-8 becomes: technical operations run per organisation with explicit context and never read content. **Any content access is a separate, recorded, organisation-reported act.** **§13.9's condition is met: the tactical design is ready for an independent check of §13.2 and §13.3, then Principal review.**

**Decision (2026-11-11): D-166 amends D-165.**
- **Technical operations** run under a **grant the organisation gives once at joining** (service terms) and never read content.
- **Any look at business content needs the organisation's grant for that case**, recorded and reported to it.
- **No legal-obligation ground for staff access** ([LQ] for counsel).

**ISO-8 now reads:**
- **technical operations need the organisation's joining grant** (a registry fact) and explicit context;
- **content access needs a case grant recorded in the organisation's own database**, owned by Organisation (it is the organisation's access decision, D-104 / D-111).

**Without a grant: refused.**

---

## 14. Revision 3 corrections (2026-11-11, after IR-R1-01; that review was by deepseek-chat, not independent)

| Finding | Correction |
|---|---|
| **M-4** (answer replayable) | **The AuthorityCheck request for `issue-claim` / `revoke-claim` names its subject:** `scope` includes **R, the local entry E and the issuance id**; the answer's basis carries them as **`requestRef`**. **Identity accepts the answer only if `requestRef` = (R, E, issuance id) of this command**, R was issued for (organisation, E) (closes L-11), and the answer is fresh. **One answer serves one issuance; it cannot be replayed for another** |
| **M-5** (full evidence central) | **No D-164 exception.** Central provenance on the Claim is **minimal**: organisation id, issuance id, actor `PersonRef`, outcome, `checkedAt`, and a **digest** of the full answer. **The full answer (six fields + basis) stays in the organisation's `ClaimIssuance` record** (organisation database). An auditor matches the digest |
| **M-6** | ISO-8 row rewritten to D-166 (§13.5) |
| **M-7** | **ISO-10 (new):** every log line, error, trace and metric **carries the operation's organisation context and never contains another organisation's data**; **central logs hold no organisation business content**; user-facing errors are neutral (D-75 §5; I-TDD-01 TH-3). **Test:** a cross-organisation error and log case |
| L-7 | §3's `unavailable` row marked superseded |
| L-8 | The suspension **interval** (with its end) is ratified (final §3 `restrictions`); **only the command field `until` and its encoding are [TD]** |
| L-9 | **"Reset" defined:** the worker uses **a fresh connection per operation (or per organisation)**; session state (`search_path`, temp tables, settings, prepared statements) is never carried across organisations |
| L-10 | ISO-5: the **organisation picker** is a cross-organisation question **inside Identity** (the signed-in person's own bindings only); no other exists in release 1 |
| L-12 (registry locator) | **The database locator is written only by provisioning, audited, and never influenced by any request** (ISO-2's trust anchor) |
| L-12 / L-14 (overstatements) | "the first code is never usable…" → **an intercepted code is usable within its window; the controls are single use, a short window, throttling, and revoke-and-reissue.** "Not yet marked claimed changes no right" → **true when the mark is administrative**; **if an organisation's rules use it as an access condition, access waits until delivery** |
| L-13 | §13.7 reworded to routing |
| L-14 (E-11) | E-11 is D-106's accepted conclusion "tenancy is a platform capability, not Organisation"; the record text is in the Organisation strategic baseline (not in the package): **UNAVAILABLE there**, cited here for completeness |
| L-15 | Addendum M §M-7's continuing jobs are superseded by §6 and §13.6 (noted in that document) |

**Assessment, revision 3:** ready for an **independent** review (outstanding), then Principal acceptance.

**Note (2026-11-11):**
- **ISO-8 implements the baseline invariant INV-ACCESS-1** (`2026-11-11-h1-silence-rules-and-strategic-baseline-completion.md` §H, from D-166). The invariant is cross-cutting; ISO-8 is its enforcement at this boundary.
- **D-167:** acts for an area without a committee return `Undetermined` (no default authority).

## 15. Revision 4 corrections (2026-11-11, after IR-R1-02; deepseek-chat, not independent)

| Finding | Correction |
|---|---|
| **F-02** (D-168 has no path) | **Governance exposes `ParticipationRuleAsAt(organisation, d, τ)`**: the organisation's recorded provision on who may post and receive newsletters (a **RuleProvision** of the rules on file), **or "none recorded"**. **Consumers apply the provision if recorded, else the platform default D-161.** The provision is the organisation's own rule (Governance), never a platform setting |
| **F-03** (D-167 label) | **Every access decision, audience snapshot or record that a kind-D default affected carries `ruleSource = organisation provision (ref) \| platform default (D-nn)`.** Applies to: forum read and post (D-152, D-153, D-161); newsletter audience (D-161); top-unit default placement (D-114); resumed allocations (D-140, later); display defaults (D-52, D-55) |
| **F-14** (D-113 report) | A refused second binding is **refused neutrally to the person and reported to the issuing organisation** via the outbox (`BindingRefused(R, reason)`), in the same central transaction |
| **F-11** (D-164 "only") | **Identity's Claim and issued-`PersonRef` state are part of "authentication" and "the association"** in D-164's list: they hold no personal data and serve only login and routing |
| **F-16** | §10 item 4 now uses `schemaState = migration-failed` |
| F-06, F-07 | **Principal questions** (asked separately) |
| F-12 | registry owner = platform capability (E-11); **disclosed, no business effect**, unchanged |
| F-13 | file 16 (I-TDD-01) belongs to the Identity session; **its K4 and `ClaimRedeemed` wording is superseded by D-113 and §13.0–§13.1**, to be noted there by that session |

**Decision (2026-11-11): D-170 (F-06).** **When an organisation's database cannot record a case grant**, the organisation's authorised person gives it **through the platform**.
- **Holding:** it is held **centrally as a pending grant record** (grant metadata only, no business content), **used for that one case**, recorded and reported.
- **Copying:** it is **copied into the organisation's database as soon as that works again**; the copy is idempotent on the grant id.
- **ISO-8 / INV-ACCESS-1:** without such a grant, still refused.
- **Central metadata:** the pending record is grant metadata, consistent with D-164 (no organisational business content).

## 16. Revision 5 corrections (2026-11-11, after IR-R1-02 by GPT-6, independent of the package authorship)

| Finding | Correction |
|---|---|
| **M-9** (act kind, actor) | **`requestRef` = (act kind, R, E, issuance id).** Identity checks: **the answer's act kind equals the command** (an `issue-claim` answer cannot authorise a `revoke-claim`, nor the reverse); **the answer's `actor` equals the officer of the operation's authenticated context**. **Trust assumption, stated:** in release 1 the answer arrives through the in-process internal port of one deployable, **not with cryptographic proof of origin**; architecture tests ensure only Governance's AuthorityCheck service constructs answers and only Organisation's application service calls the port. Signed answers: a later option [TU] |
| **M-10** (intercepted code) | §13.3's "every intermediate state is harmless" is qualified: **a claim issued centrally but not yet recorded locally can be redeemed by whoever holds the code during its window.** Controls: single use, short window, throttling, revoke-and-reissue, and `ClaimRedeemed` reported to the organisation. **The risk stays in the threat model** |
| **M-5** (`PersonRef`) | §2 now classes the issued reference as **pseudonymous personal data, linkable by Identity**; [LQ] for counsel. F-11's "no personal data" is withdrawn |
| **M-6** (D-166 controls) | **Release-1 acceptance criteria for INV-ACCESS-1** (to be proved before go-live): <br>- no human standing credentials on organisation business tables; <br>- operator roles non-interactive; <br>- per-organisation backup encryption with key-use log; <br>- restore only by the automated process; <br>- migrations only by the runner; <br>- content-free logs (ISO-10); <br>- temporary copies encrypted and expiring; <br>- case grants recorded (organisation database, or D-170 pending record) and reported; <br>- **the service-operation grant's lifecycle:** given at joining, recorded in the registry, revocable only with service termination |
| **M-11** (demo) | **Demo mode is deferred from release 1** [AC]. When built, **a demo organisation with its own database**, never central business data (D-164) |
| **M-3** (registry owner) | **Recommendation [AC]:** the organisation registry, database locator, service-operation grant and pending case grants (D-170) belong to a **Platform (tenancy) supporting context**, distinct from Identity and from every organisation context. **To be confirmed in the baseline acceptance** (closes HD-8's owner part) |

**Status (2026-11-11): ACCEPTED (D-178)** at the fingerprint recorded in the decision record. Build gated on its proof tests; no implementation authorised. Later notes follow the D-173 change-control rule.

**Conformance note (2026-11-11, implements D-166 / D-170 / D-175 via Platform design revision 4; no meaning change):**
- **ISO-11:** platform operators reach an organisation's data **only through a case-grant session** (operations channel, separate from member and workspace requests); **ISO-3 is unchanged for those requests**.
- **`schemaState` adds `migrating`** (unroutable).
- **Status `pending`** precedes `provisioning`; no technical operation before counterparty verification and the service-operation grant.
- **The registrar's first claim is the one bootstrap exception** to §4's officer and AuthorityCheck rule.

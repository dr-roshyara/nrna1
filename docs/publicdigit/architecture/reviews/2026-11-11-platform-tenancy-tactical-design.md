# Platform (tenancy) context: tactical DDD discovery and design — PROPOSED

| | |
|---|---|
| **Status** | **PROPOSED.** Records no ruling. Written by Claude, not independent |
| **Commission** | "Platform (Tenancy) — Tactical DDD Discovery and Design", accepted by the Principal 2026-11-11 |
| **Binding** | **D-173** (Platform owns registry, locator, status / `schemaState`, service-operation grant record, D-170 pending grants); D-164, D-166, D-170, D-148, D-159, D-160, D-167, D-73; INV-ACCESS-1; ISO-1 … ISO-10 (multi-database design §13, §14, §16) |
| **Consumes** | multi-database design §3 (lifecycle), §6 and §13.6 (availability, status versus health), §16 M-6 (INV-ACCESS-1 release criteria); `docs/publicdigit/brainstorming/multi-database-system/` (read only, not authoritative: host resolution, registry tables) |

**Labels:** [AD] · [BF] · [PK] · [AC] · [TD] · [TU] · [WD] · [P] · [H] · [OQ] · [LQ] · UNKNOWN.

## 0. Findings first

1. **[AC] D-170 has an authority gap.**
   - It lets "the organisation's authorised person" give a case grant through the platform **when the organisation's database is down**.
   - **But who is authorised is Governance's answer, and Governance lives in that database.**
   - Today the design cannot tell an authorised person from anyone else at exactly that moment.
   - **This is a genuine Principal question (Q-P1).**
2. **[AC] Joining has a counterparty gap.** D-148 says the platform "only checks that the documents exist" and creates no authority. Nothing says **who PublicDigit is contracting with**. An impostor could register a well-known party's name with fabricated statutes and run a fake organisation. Two things must stay distinct:
   - **constitutional authority** (never PublicDigit's, D-1a);
   - **PublicDigit's own decision about whom it provides the service to** (a commercial and trust matter).
   - **Genuine Principal question (Q-P2).**
3. **[BF] Code today:**
   - organisations live in one shared database with `organisation_id`;
   - the organisation is resolved from path, session or header (`TenantContext`);
   - there is no host-based routing and no registry of databases (brainstorming 01).
   - **Nothing of this context exists yet.**

## Part 1. Business explanation

**The Platform context is PublicDigit's register of the organisations it serves, and the switchboard that connects a request to the right organisation's own database.**
- **It knows:**
  - that "Party A" and "Union B" are served;
  - under which web addresses;
  - where each one's database is;
  - whether that database is ready;
  - that each organisation agreed, when joining, to let PublicDigit run the technical service.
- **It never knows** who is a member of Party A, what Union B decided, or what anyone posted.
- **It never decides anything for an organisation.**

| Example | What Platform does | What it never does |
|---|---|---|
| Party A joins | records A in the register (`provisioning`), creates A's database, marks it `operational`; **A's registrar** then records A's units and committees *inside A's database* (D-148, D-159) | create A's committees or authority |
| A member of both A and B logs in | Identity confirms the person; **Platform resolves "A" to A's database** for a request to A | let a request for A reach B's database (ISO-1, ISO-2, ISO-5) |
| B's database is down | B is "temporarily unavailable"; A works normally | mark B as closed, or touch B's data |
| PublicDigit updates its software | migrates each organisation's database under its **service-operation grant** | read any content |
| A asks support to look at a broken record | only with **A's case grant**, recorded and reported (D-166) | look on its own initiative, or because it holds A's backup |

## Part 2. Boundaries

| Owner | Owns | Never |
|---|---|---|
| **Platform** | registry entry (organisation identity for routing, hostnames, public display name), database locator, platform status, `schemaState`, **record** of the service-operation grant, **pending** case-grant records (D-170) | members, profiles, units, authority, content; account identity |
| **Identity** | Person, Account, login, sessions, `PersonRef`, claims, bindings | which database an organisation uses; registry |
| **Organisation** | its records, **its decisions to grant** (service-operation at joining, case grants), **the case-grant records once in its database** | the registry or locator |
| **Governance** | **who may grant on the organisation's behalf** (AuthorityCheck) | platform records |

**What crosses:**

| From → to | What | Never |
|---|---|---|
| Platform → every request or job | `OrganisationContext` (organisation id + resolved connection) | another organisation's connection |
| Identity → Platform | binding check (ISO-3) | membership or authority |
| Platform → Organisation | **pending case grants to copy** (D-170) | content |
| Organisation / Governance → Platform | **the grant decision with its authority evidence** (where available) | business data |

**No overlap; no missing owner** (except the two gaps in §0).

## Part 3. Domain discovery

### 3.1 Actors

- **Joining applicant:** a person acting for an organisation, with its documents (D-148).
- **The organisation's competent body and its authorised persons** (Governance).
- **Platform operations:** automated, non-interactive (INV-ACCESS-1).
- **Support staff:** content only by case grant.
- **Identity**, as the gatekeeper of persons.

### 3.2 Lifecycle (platform side; **not** the organisation's constitutional life)

| Status | Meaning | Evidence |
|---|---|---|
| `provisioning` | registered; database being created and migrated | [AD] D-164, design §3 |
| `operational` | routable | [AD] |
| `suspended` | **platform-side stop of service** (e.g. service ended); data untouched | **later**; business meaning and triggers: **UNKNOWN** (commercial) |
| `closed` | service relationship ended; export and retention apply | **later**; **[LQ]**, B-5 |

**Separate from status:**
- **`schemaState`** (`current` · `migration-failed`): durable, blocks routing.
- **Database health** (reachable or not): an **observation**, never a status.

**Joining sequence (D-148, D-159, D-160):**
1. Application with statutes and the decision naming a registrar.
2. *(Q-P2: counterparty check)*.
3. Registry entry `provisioning`.
4. Database created on the **membership ledger from day one** (D-160).
5. Service-operation grant recorded (the organisation's acceptance of the service terms).
6. `operational`.
7. Registrar claims their record (Identity) and records the existing structure and committees **inside the organisation's database**.

**Rules:**
- **No transition invents authority.**
- **Health never changes status.**
- **`closed` never means "delete the database"** without the retention process.

### 3.3 Service-operation grant (D-166)

- **Given by the organisation at joining, as part of the service terms; recorded by Platform; revocable only with service termination.**
- **Permits:** provisioning, migrations, backups, restores (automated), health checks.
- **Excludes:** any human reading of content.
- **Evidence kept:** terms version, date, the accepting person (`PersonRef` once claimed, or the applicant's account), the founding document fingerprints.
- **Without the grant: no technical operation** (provisioning cannot start).

### 3.4 Pending case grants (D-170)

**Lifecycle:** `pending` → `used` (for its one case) → `reported` → `copied` (into the organisation's database), or `expired` / `withdrawn` by the organisation.

| Field | Content |
|---|---|
| Identity | grant id; organisation id |
| Scope | **one case**: purpose, data area, time window, staff member |
| Grantor | **per Q-P1** |
| Evidence | timestamps; digest of the grant text |

**Rules:**
- **Never broadened or reused.** Each use is checked against scope and window.
- **Copy and report are idempotent on the grant id.**
- **If the organisation's database stays down:**
  - the pending record stays, and the report goes to the organisation's platform contacts;
  - **the copy happens on recovery (reconciliation).**

## Part 4. Tactical model

**Ubiquitous language:**
- **Served organisation** (registry entry);
- **database locator**;
- **platform status**;
- **schema state**;
- **health**;
- **service-operation grant**;
- **case grant** (pending, used, reported, copied);
- **organisation context**;
- **resolution**.

| Element | Kind | Invariants |
|---|---|---|
| **`ServedOrganisation`** | **aggregate** (one per organisation) | id immutable; **locator written only by provisioning, audited, never from a request** (ISO-2); status transitions per §3.2; **never routable unless `operational` and `schemaState = current`**; **a service-operation grant must exist before provisioning** (§3.3); hostnames unique across the registry |
| `ServiceOperationGrant` | value object inside `ServedOrganisation` | immutable once recorded; terms version; ends only with termination |
| **`PendingCaseGrant`** | **aggregate** (one per grant) | belongs to one organisation; scope fixed at creation; used only within scope and window; states per §3.4; idempotent copy and report |
| `OrganisationContext` | value object | one per operation (ISO-1) |
| `ResolutionService` | domain service | (hostname or selected organisation) → `ServedOrganisation` → connection; **fail closed**: unknown, not operational, wrong schema or not bound (ISO-3) → refused |

**Commands:**
- `RegisterOrganisation` (with evidence; Q-P2);
- `RecordServiceOperationGrant`;
- `CompleteProvisioning`;
- `MarkMigrationFailed` / `MarkSchemaCurrent`;
- `GiveCaseGrantViaPlatform` (Q-P1);
- `UseCaseGrant`;
- `ReportCaseGrant`;
- `CopyCaseGrantToOrganisation`;
- `WithdrawCaseGrant`.

**Events** (outbox, central):
- `OrganisationRegistered`, `ServiceOperationGranted`, `OrganisationOperational`;
- `SchemaMigrationFailed`, `SchemaRepaired`;
- `CaseGrantGiven`, `CaseGrantUsed`, `CaseGrantReported`, `CaseGrantCopied`.

**Transactions:**
- each aggregate change is **one central transaction**;
- **anything in an organisation's database is a separate, idempotent step** (no transaction across the two).

**Failure and recovery:**
- provisioning resumes from its recorded status;
- copying a case grant retries until it succeeds;
- reconciliation compares the pending and copied records.

**Audit:**
- every registry and locator change, every grant event and every resolution refusal is logged;
- **logs are content-free** (ISO-10).

## Part 5. Scenarios

| # | Scenario | Outcome |
|---|---|---|
| 1 | Party A joins | §3.2 sequence; A routable only after `operational` |
| 2 | A's database unreachable | A refused "temporarily unavailable" (health); status unchanged; B unaffected |
| 3 | Central database down | everything waits (fail closed); nothing routed on stale data |
| 4 | Request names A, session bound only to B | refused (ISO-3) |
| 5 | Migration fails for A | `schemaState = migration-failed`; A unroutable until repaired; others continue |
| 6 | Hostname typo or unknown | refused; no default organisation |
| 7 | A's service terminated (later) | `suspended` → `closed`; routing stops; **data kept for the retention process [LQ]** |
| 8 | Support needs to inspect A while A's database is down | **case grant via Platform by A's authorised person (Q-P1)**; used once within scope; reported; copied on recovery |
| 9 | The same case grant offered twice | idempotent; one record |
| 10 | Staff try a second inspection under the same grant outside its window | refused |

## Part 6. Principal decisions

- **Q-P1:** who may give a case grant through the platform when the organisation's database is down?
- **Q-P2:** does PublicDigit verify whom it contracts with when an organisation joins?

**Asked separately, one at a time.**

## Part 7. Readiness

| Area | Assessment |
|---|---|
| **Understood** | registry, locator, status / schema / health split, service-operation grant, routing and fail-closed behaviour, pending-grant lifecycle (except its grantor) |
| **Uncertain** | Q-P1 (D-170 grantor); Q-P2 (joining counterparty); `suspended` / `closed` triggers and retention (later, [LQ]) |
| **Proofs [TU]** | ISO-1 … ISO-10; locator write path; hostname uniqueness; provisioning resume; idempotent copy and report; INV-ACCESS-1 release criteria |

**Verdict: READY WITH OPEN QUESTIONS** (Q-P1, Q-P2).

## Decisions (2026-11-11)

**D-174 (Q-P1):** case grants given through the platform while the organisation's database is down come only from the organisation's **designated platform contacts**. These are designated by its own committee decision, at joining and changeable later. **Platform holds only their account references.**
- **Model:** `ServedOrganisation` gains **`PlatformContacts`** (a set of `PersonRef` / account references, each with the designating decision reference and date).
- **Invariants:**
  - `GiveCaseGrantViaPlatform` is accepted **only from a current platform contact of that organisation**;
  - changes arrive from the organisation's database (idempotent, newest decision wins) and are refused if older than the current list;
  - **when the database works, the normal AuthorityCheck route applies instead.**

**D-175 (Q-P2):** **counterparty verification before `operational`.**
- **Model:** `ServedOrganisation` records `CounterpartyVerification` (verified person, method, date, service-agreement reference).
- **Invariant:** **`CompleteProvisioning` → `operational` only after verification.**
- **The verification grants no authority inside the organisation** (D-1a).

**Verdict after the decisions: READY.** Remaining uncertainty is later-release only: `suspended` and `closed` triggers, retention ([LQ]).

---

## Revision 2 (2026-11-11): tactical review and completion

*Commission: "Independent Tactical Review of Platform (Tenancy)". **Reviewer is the author (Claude); a self-review, not independent.** The earlier "READY" verdict is withdrawn pending this revision.*

### R-A. Contact revocation and stale information (D-174) [finding: medium-high]

**Authority versus copy:**
- **The authoritative designation is the organisation's committee decision, recorded in its database (Governance).**
- **Platform holds a copy** used **only** when that database cannot answer.

**The gap:**
- A revocation decided just before the database fails may never reach the copy.
- **A revoked contact could then authorise a staff inspection.**
- **D-170 and D-174 do not say what happens then.**

**Freshness cannot be proved while the database is down**, so one of three business rules must apply. **Principal question Q-P3** (asked separately).

**What holds whatever the answer [TD]:**
- updates to the copy are **ordered by the designating decision's date and log position**; older updates are refused; duplicates are idempotent;
- **every D-174 grant is reported immediately** to all current contacts and, on recovery, to the organisation's database;
- **a grant never lets the contact see content.** It lets PublicDigit staff inspect for one case.

### R-B. Initial designation at joining [finding: medium]

**Founding evidence:** the founding decision submitted at joining (D-148) **names the registrar and the initial platform contacts**. It is recorded on the platform **from evidence, challengeable** (as D-159). PublicDigit creates no authority (D-1a, D-167).

**Representation before the organisation's database exists:**
- contacts are referenced by their **Identity Account** (they need one anyway to log in and give a grant);
- **not by `PersonRef`**: a `PersonRef` needs the organisation's own record (D-113 step 1), which does not exist yet.

**Transition:**
1. once the database is `operational`, the **registrar records the founding decision** (designations included) as the organisation's first Governance records;
2. the contacts' organisation records are created;
3. their **claims bind those `PersonRef`s to the same Persons** (no duplicate Person: one Account per Person, D-110);
4. **from then on, the organisation's own decisions maintain the list.**

### R-C. Counterparty verification (D-175) [finding: medium, boundary]

| What | Meaning | Grants |
|---|---|---|
| (1) Signer's identity | the person accepting the service terms is who they claim | nothing inside the organisation |
| (2) Signer's link | a confirmation through a channel independent of the applicant (e.g. the organisation's published office contact) | nothing inside the organisation |
| (3) Commercial acceptance | PublicDigit agrees to serve | the service relationship only |
| (4) Constitutional authority | **only** from the organisation's documents and decisions | — |

**Rules:**
- **The verified signer is the *service counterparty*: commercial matters only.**
- **The signer is a platform contact only if the founding decision names them.**
- **Sufficient evidence and identity-check methods: [LQ]** (counsel).
- **[TD]:** `CounterpartyVerification` records the method, evidence fingerprints, date and verifier.

### R-D. Identity boundary [finding: low, clarified]

- Platform stores **only Account references** of contacts, and of the counterparty (R-B).
- **A designation grants no membership, workspace access or authority** (ISO-7 extended to contacts).
- **A disabled or unlinked Account cannot give a grant** (Identity refuses login). The organisation should keep **at least two contacts** (recommendation, not a rule).

### R-E. Tactical model, revised

| Element | Revision |
|---|---|
| **`ServedOrganisation`** | **Holds:** identity and hostnames, locator, platform status, `schemaState`, **service-operation grants (append-only list; a new terms version is a new entry; ends only with termination)**, `CounterpartyVerification`, **`PlatformContacts` (Account references + designating decision reference, date, position)**. **One aggregate** is justified by the invariant: **"`operational` only with a grant, a verified counterparty and at least one contact"**. Contact updates and status changes are rare, so they do not contend |
| **`PendingCaseGrant`** | **States:** `given` (**reported immediately**) → `active` within its window → `closed` (window ended or withdrawn). **Orthogonal flags:** `copiedToOrganisation` (set on recovery, idempotent). **Each use is a logged and reported event**, checked against scope (purpose, data area, staff member) and window. **Grantor evidence:** contact's Account reference + the designation record version used (R-A) |
| Commands added | `RegisterPlatformContacts` (from founding evidence); `UpdatePlatformContacts` (from the organisation's decision, ordered); `WithdrawCaseGrant` (by any current contact, or by the organisation after recovery) |
| Events added | `PlatformContactsChanged`, `CaseGrantWithdrawn`, `CaseGrantUseRecorded` |

### R-F. Failure scenarios

| # | Scenario | Outcome | Audit |
|---|---|---|---|
| 1 | Counterparty not verified | stays `provisioning`; not routable | verification record |
| 2 | No service-operation grant | provisioning refused | — |
| 3 | Contact list updated twice out of order | older refused (decision order) | change log |
| 4 | Contact revoked; database fails before the copy updates | **per Q-P3** | — |
| 5 | Contact's Account disabled | cannot log in; no grant | Identity |
| 6 | Case grant given twice | idempotent | one record |
| 7 | Use outside scope or window | refused | refusal logged |
| 8 | Database recovers | copy and report into the organisation (inbox, deduplicated) | copy event |
| 9 | Locator altered outside provisioning | refused; alert (ISO-2) | audit log |
| 10 | Migration fails | `schemaState = migration-failed`; unroutable | — |
| 11 | Central or Identity down (one database) | everything waits | — |
| 12 | Request for A reaches B | impossible by ISO-1/2/5; attempt refused | log |

### R-G. Proofs and legal questions

- **[TU]:**
  - ordered contact updates;
  - immediate reporting;
  - scope and window checks;
  - idempotent copy;
  - the provisioning-gate invariant;
  - ISO-1 … ISO-10.
- **[LQ]:**
  - counterparty evidence sufficiency;
  - identity-check methods;
  - retention.

### Verdict (revision 2)

**READY WITH OPEN QUESTIONS (Q-P3).** After Q-P3: ready for the Principal's acceptance, on the stated self-review basis.

**Decision (2026-11-11): D-176 (Q-P3).** For the emergency route, **the platform's registered contact list governs**. The organisation may register additions and removals directly at any time, also while its database is down, through another current contact or its verified counterparty, citing its decision. **Every emergency grant is reported at once to all current contacts.**
- **Model:**
  - `UpdatePlatformContacts` accepts **two sources**: the organisation's database (ordered by decision) **or** a direct registration by a current contact or the counterparty, carrying the decision reference;
  - **the newest decision wins**;
  - every change is reported to all contacts;
  - **scenario 4 (R-F):** the removal counts once registered; until then, a grant by the removed contact is valid **but reported immediately to every other contact**, who can withdraw it (`WithdrawCaseGrant`).

**Verdict: READY FOR PRINCIPAL ACCEPTANCE** (self-review basis; [LQ] items separate).

**Status (2026-11-11):** **not yet accepted.** The Principal chose **acceptance after an independent review** (D-178 record). Reviewed text: revision 2, fingerprint `6908c15de5e6dfa5c514e9bf00373abf46ed12bb68e90f29f082ec5245e186f5`.

---

## Revision 3 (2026-11-11): corrections after IR-P-01 (GPT-6, independent; verdict NOT READY)

*Revision 3 governs where it differs from revisions 1–2. **No accepted decision is reopened.***

### F-01: contact changes carry evidence; the sender is a messenger only (implements D-175 + D-176)

- **The sender** (a current platform contact or the verified service counterparty) **transmits an organisation decision; the sender never decides.** The counterparty's verification (D-175) gives them **no power over contacts**: it only makes them a recognised channel.
- **Evidence required with every direct registration** (the asserted-competence model of the Governance thin slice §3):
  1. the **decision** (text or extract) and its **date**;
  2. the **body** that made it and **the provision of the organisation's rules on file** giving that body competence over platform contacts;
  3. the **sitting minutes or signed extract** (fingerprinted);
  4. the **sender's identity** (authenticated Account) and their role as contact or counterparty.
- **Platform checks only that all four elements are present and well-formed** and that the sender is a current contact or the verified counterparty.
  - **It never judges competence** (that is Governance's, D-1a, D-167).
  - **Missing elements → refused.**
- **Every registration is marked "asserted, not machine-checked"**, **reported at once to all current contacts and to the counterparty**, and **challengeable**.
- **On recovery,** the registration and its evidence are copied into the organisation's database. **Governance's records are then compared:**
  - if the cited decision is **not found or not competent**, the registration and **every emergency grant given under it are flagged to the organisation**;
  - **the organisation decides** what follows. **Platform reverses nothing on its own** (no invented rule).
- **Asymmetry, informational only:** a removal reduces risk and an addition increases it. **Both follow D-176**, and additions are additionally reported to *all* contacts (including the one removed or added).

### F-02: the service-operation grant precedes provisioning (D-166)

**Corrected joining sequence:**
1. Application with the founding documents (statutes; the decision naming the registrar **and the initial platform contacts**; D-148, D-174).
2. **`ServedOrganisation` created with status `pending`** (new status: application recorded; **no technical operation yet**).
3. **Counterparty verified** (D-175).
4. **Service terms accepted: service-operation grant recorded** (D-166).
5. **Only then `provisioning`:** database created on the membership ledger (D-160) and migrated.
6. **Founding records** written into the organisation's database by provisioning, from evidence (D-148, D-159):
   - the registrar's and initial contacts' **local person entries**;
   - Identity issues their `PersonRef`s (D-113);
   - **the registrar's claim is issued**, its authority basis being the founding decision recorded from evidence ("asserted", D-148).
7. **`operational`.**
8. The registrar claims (binding to their existing Person if they have an Account: no duplicate, D-110) and records the existing units and committees (D-159).

**Invariants:**
- `pending → provisioning` **only with a verified counterparty and a recorded grant**;
- `provisioning → operational` **only with schema current and at least one contact**.

### F-03: registrar bootstrap aligned with the accepted multi-database design (D-178)

- **Step 6 above issues the registrar's claim *before* `operational`**, as multi-database design §3 requires.
- Redemption may happen before or after `operational`; **routing to the organisation starts only at `operational`.**
- The initial contacts are the same Persons as in revision 2 R-B: Account references centrally; **their local entries and `PersonRef`s are created at step 6**; their claims bind to the same Persons.

### F-04: contact removal and emergency grants cannot race

- **Linearisation point: the `ServedOrganisation` row.**
  - `UpdatePlatformContacts` takes it **exclusively**;
  - `GiveCaseGrantViaPlatform` takes it **shared**, checks that **the grantor is in the current contact list at that version**, and inserts the `PendingCaseGrant` **with that version** in the **same central transaction**.
- **Result:**
  - a removal committed first means a later grant is refused;
  - a grant committed first is valid under D-176 (removal not yet registered), and it is reported and withdrawable.
- **Proof test [TU]:** concurrent removal and grant, both orders, many repetitions; never a grant by a contact removed in an earlier-committed version.

### Updated status list

`pending` · `provisioning` · `operational` · `suspended` (later) · `closed` (later), plus `schemaState` and health (unchanged).

**Verdict (author's): READY FOR FOCUSED INDEPENDENT RE-REVIEW** of F-01 … F-04.

---

## Revision 4 (2026-11-11): corrections after the deepseek-chat review of revision 3

*That review (saved at `2026-11-11-ir-p-01-independent-review.md`) is by the **excluded reviewer** and is **not** the independent IR-P-01. GPT-6's IR-P-01 report is not in the repository. **Revision 4 governs where it differs.***

| Finding | Correction |
|---|---|
| **H-2** (central governance content) | **Platform holds no decision text, rules or minutes.** A direct registration stores centrally **only** the decision reference, date, body name, provision reference, **digests (fingerprints) of the documents**, and sender. **The documents stay with the sender, who submits them into the organisation's database on recovery**; the organisation compares them with the digests. A missing or mismatched document → registration and grants flagged to the organisation. Consistent with D-164 and D-170 (metadata only) |
| **H-3** (staff path vs ISO-3) | **A separate operations channel**, never a member or workspace request. **Platform staff have their own operator identity** (`PlatformOperator`, central, no binding to any organisation). **They reach an organisation's data only through a case-grant session:** opened from a valid grant (normal or emergency), **scoped to the grant's case, data area and window, and to the named operator**, logged and reported. **ISO-3 is unchanged for member and workspace requests.** The operations channel is **ISO-11** (new): no operator access without an active case grant naming them. The accepted multi-database design gets a conformance note (implements D-166 / D-170; no meaning change) |
| **H-4** (reporting channel) | **Principal question Q-P4** (uses Identity's login address, which INV-ID-3 reserves for authentication) |
| **H-5** (list capture) | **Design part:** removals are now reported to **the removed contacts too**, and to the counterparty. **Ordering (M-12):** direct registrations are ordered by **arrival on the platform**, not by the decision date they cite; **future-dated decisions are refused**. **Business part:** **Principal question Q-P5** |
| **M-1** (state model) | **One model:** `PendingCaseGrant` = `given` → `active` → `closed(expired \| withdrawn \| used-up)`; **orthogonal flags `reportedToContacts` and `copiedToOrganisation`**, each with its own attempts log. §3.4 and Part 4 superseded |
| **M-2** (`pending`) | `pending` refines the accepted design's lifecycle (operational statuses); **D-175's "stays `provisioning` until verified" is satisfied in substance: no technical operation before verification**. Conformance note in the multi-database design |
| **M-3** (bootstrap claim) | **Stated bootstrap exception:** the registrar's first claim is issued by provisioning on the **founding decision recorded from evidence** (asserted, D-148). **No officer exists yet. It is the only claim issued without an officer**; every later claim follows multi-database design §4 |
| **M-4** (contact without an Account) | Named contacts get an **invitation to register** (Identity). **`operational` needs at least one contact with an active Account**; if a named person is blocked by INV-ID-6, the organisation names another through its founding documents |
| **M-5** (keys) | Platform keys contacts by **Account**. Updates from the organisation database carry the `PersonRef`; **Identity resolves `PersonRef` → bound Account for Platform** (Identity's own resolution, its own references only; XD-3 §3) |
| **M-6** | `position` removed; Platform stores Account reference + decision reference, date and ordering key (**required by D-176's "citing the organisation's decision"**; no other data) |
| **M-7** | The signed service agreement and identity evidence are **PublicDigit's commercial records, kept outside the platform databases**; Platform holds the verification metadata and the agreement reference only |
| **M-8** | `schemaState` gains **`migrating`**: unroutable while it lasts; `migration-failed` as before |
| **M-9** | **Case types:** `live-database` and **`backup-copy`** (inspection of a named backup, restored only into an isolated, expiring copy for that case, destroyed after the window) |
| **M-10** | Governance contract additions (thin-slice conformance note): act kinds **`designate-platform-contacts`** and **`give-case-grant`** (normal route) |
| **M-11** | **Withdrawal and use are linearised on the `PendingCaseGrant` row** (exclusive lock); a withdrawal committed first refuses later use |
| **M-13** | Give, use and withdraw are evaluated at the **platform's τ** (one central transaction each); the copy into the organisation is a later idempotent step |
| **L-1 … L-5** | Old verdicts and §3.4 marked superseded; scenario 4 → D-176; scenario 12 → "refused; a breach is a defect"; withdrawal by any contact is **intended** (safe side); aggregate justification restated (hostname uniqueness is a registry-wide unique index; locator is a command rule; F-04's transaction spans two aggregates by design) |

**Superseded in this file:** the verdicts at the end of revision 1, revision 2 and revision 3; §3.4's state list.

**Verdict (author's): READY FOR FOCUSED INDEPENDENT RE-REVIEW after Q-P4 and Q-P5.**

**Decisions (2026-11-11):**
- **D-179:** security notices go to the verified login address (narrow INV-ID-3 exception); Identity sends them on Platform's request (`SecurityNoticeRequested`).
- **D-180:** a contact added through the emergency route can grant only **24 hours after registration**; removals are immediate.
- **Model:** `PlatformContacts` entries carry `source` (organisation database | direct) and `grantableFrom` (= registration time + 24 hours for direct additions). `GiveCaseGrantViaPlatform` checks `now ≥ grantableFrom`.
- **New scenario 13:** list capture attempt → the removed contacts are notified at once; the accomplice cannot grant for 24 hours; any contact or the counterparty can register a correcting removal.

**Verdict (author's): READY FOR FOCUSED INDEPENDENT RE-REVIEW** (by GPT-6; revision 4 governs).

---

## Revision 5 (2026-11-11): corrections after IR-P-02 (GPT-6, independent; verdict NOT READY) and D-182

*Revision 5 governs where it differs from revisions 1–4. **Proposed, not accepted.** Implements D-182; reopens no decision.*

### D-182: two contacts for an emergency grant (resolves IR-P-02 F-01)

- **`GiveCaseGrantViaPlatform` now creates a grant in state `requested`**, naming its scope and the first approving contact.
- **It becomes `given` only when a second, different current contact approves the same grant** (same scope, unchanged).
- **Both approvers must be current and grant-capable** (D-180: direct-added contacts only after 24 hours) **at the moment of each approval.**
- **Organisations with fewer than two grant-capable contacts cannot create emergency grants** (refused; normal route only).
- A `requested` grant **expires unapproved** after its own window; it can be withdrawn by any current contact.
- **Effect on the capture scenario:**
  - after X removes Y and Z, X alone cannot grant;
  - an accomplice added by X is not grant-capable for 24 hours, and every current and removed contact is notified (D-179);
  - **during those 24 hours the counterparty can register a correcting change.**

### F-02: emergency changes are provisional; recovery decides (D-176, D-181)

| Stage | Meaning | Effect |
|---|---|---|
| **Received** | a direct registration arrives with its evidence metadata (references, digests, sender) | none yet |
| **Registered (in effect for the emergency route)** | passes D-176's checks (sender current; evidence elements present; ordering, F-03) | **applies only while the organisation's database cannot answer** (D-176 unchanged); additions grant-capable only after 24 hours (D-180) |
| **Validated** | on recovery: documents submitted into the organisation's database and matched to the digests; **Governance's AuthorityCheck on the cited decision returns `Authorised`** | recorded as the organisation's own fact |
| **Not validated** | documents missing or mismatched, or the AuthorityCheck returns `NotAuthorised` / `Undetermined` | **the registration has no effect from recovery onwards** (**D-181**: a decision whose competence cannot be determined takes no effect). The platform list is reconciled to the organisation's records. **Grants given under it are flagged to the organisation** (already-used access cannot be undone; it is reported) |
| **Challenged** | any time, through the organisation's normal challenge route | as decided by the organisation |

- **A digest proves only** that the bytes submitted later are the bytes registered earlier.
- **It never proves that the decision is genuine or competent. That is Governance's answer, available only after recovery.**
- **No deadline is needed:** once the database works, **the organisation's records govern** (D-176), so an unvalidated registration simply stops applying.

### F-03: one precedence rule

- **Ordering key:**
  - **(decision date, decision-log position)** for decisions from the organisation's database;
  - **(cited decision date, platform arrival sequence)** for direct registrations.
- **Rules:**
  1. **A direct registration citing a decision dated before the decision behind the current entry is refused** (stale).
  2. The same decision submitted twice is idempotent (key: organisation, decision reference, contact).
  3. Conflicting registrations citing the same date are refused as ambiguous; the senders are notified.
  4. **On recovery the organisation's records replace the platform list** (reconciliation, F-02), so **no direct registration can ever override a newer decision of the organisation once known.**
- **Linearisation point:** the `ServedOrganisation` row (exclusive) for every contact update, whatever its source.

### F-04: notification lifecycle (per recipient)

**States:**
- `requested`;
- `queued`;
- `dispatched`;
- `delivered` (provider acceptance or confirmation);
- `failed`;
- `retrying`;
- `permanently-failed` (after the retry limit).

**Rules:**
- **Idempotency key:** (event id, recipient). **The model records "dispatched" or "delivered" honestly, never "notified" for an attempt.**
- **Channels:**
  - contacts and removed contacts → **verified login address** (D-179, security notices only; never used to find, create or bind);
  - **the counterparty → PublicDigit's commercial contact channel** (its commercial records, outside the platform databases; D-179 does not cover the counterparty);
  - a recipient **without an active Account** → **no channel**, recorded as such and **escalated to the counterparty channel**.
- **Escalation:** a `permanently-failed` notice to a contact is escalated to the counterparty channel and logged.

### F-05: operator sessions

- **A case-grant session is opened only from a `given` grant naming the operator. It is bound to:**
  - organisation;
  - case;
  - data area;
  - window;
  - case type (`live-database` | `backup-copy`).
- **Every protected operation re-checks the grant** (state, window, operator, scope) **in the same transaction as the operation**, with no caching.
- **Withdrawal, expiry or closing the grant terminates the session immediately:** server-side session revocation, plus the per-operation check as backstop.
- **`used-up`** = the case is closed, explicitly by the operator (case complete) or by either approving contact. **Several operations within one open case are allowed.**
- **Operator Account disabled** → the session ends.
- **Interruption** → the session cannot resume; a new session needs the grant still active.
- **`backup-copy`:** the isolated copy expires with the grant and is destroyed. **Destruction is logged and verified.**

### F-06: concurrency and recovery matrix

| Case | Aggregates | Linearisation point | Idempotency key | Both orders |
|---|---|---|---|---|
| contact removal vs grant approval | `ServedOrganisation`, `PendingCaseGrant` | `ServedOrganisation` row (exclusive for the update, shared for the approval); **lock order always `ServedOrganisation` then grant** | — | removal first: approval refused; approval first: valid, reported, withdrawable |
| second approval vs withdrawal | `PendingCaseGrant` | grant row, exclusive | grant id | withdrawal first: approval refused; approval first: grant `given`, then withdrawn |
| withdrawal vs use (open session) | `PendingCaseGrant` | grant row, exclusive; operation re-check in the same transaction | grant id | withdrawal first: operation refused and session ended; use first: that operation completes, the next is refused |
| duplicate grant request | `PendingCaseGrant` | unique (organisation, case reference) | case reference | second returns the first |
| duplicate or reordered contact updates | `ServedOrganisation` | row, exclusive | (organisation, decision ref, contact) | F-03 rules |
| recovery copy and evidence | organisation database (separate) | organisation inbox, unique event id | event id | idempotent; reconciliation per F-02 |

- **No distributed transaction** anywhere.
- **Proof tests** (repeated concurrent runs, both orders, crash mid-way) for every row.

### F-07: lifecycle, exact semantics

| Status | Meaning | Allowed operations |
|---|---|---|
| `pending` | application and founding evidence recorded | **no technical operation** |
| `provisioning` | **counterparty verified and service-operation grant recorded** | database creation, migration, founding records, registrar's bootstrap claim |
| `operational` | schema `current`; **at least two grant-capable contacts recommended, at least one active-account contact required** | routing |
| `suspended`, `closed` | later release | — |

- **D-175 ("stays `provisioning` until verified")** reads, under this model, as **"no technical operation until verified"**.
  - `pending` makes this explicit.
  - **A recorder's clarification note on D-175 would align the wording; it needs the Principal's authorisation** (offered at acceptance).
- **The bootstrap claim** remains the single exception to the officer / AuthorityCheck path, **limited to the founding registrar named in the founding decision**, issued once.

### Remaining

- **[TU]** every proof listed in F-05 and F-06.
- **[LQ]:**
  - counterparty evidence;
  - identity-check methods;
  - retention;
  - `PersonRef` classification.

**Verdict (author's): READY FOR FOCUSED INDEPENDENT RE-REVIEW.**

### Revision 5, part 2: author's self-check against the IR-P-02 work instruction (written before packaging)

*Part 2 completes and corrects part 1 above; **where they differ, part 2 governs.** Written by Claude; **not independent.***

#### Correction to F-05 / F-06: no cross-database transaction (supersedes "in the same transaction as the operation")

- **Part 1 was wrong** in claiming the grant re-check runs "in the same transaction as the operation":
  - the `PendingCaseGrant` row is in the **central** database;
  - a `live-database` operation runs in the **organisation's** database (D-164);
  - **one transaction across both would be a distributed transaction**, which the design excludes.
- **Corrected mechanism: a platform-side access gateway.**
  - All operator access goes through it (ISO-11; no standing human database credentials, no direct connection).
  - **For each protected operation:**
    1. **Access record (central, committed first):** `attempted`, with grant id, operator, operation kind, time.
    2. **Check (central transaction T1 opened):** **shared lock** on the grant row; verify state `given`/`active`, window, named operator, scope, operator Account enabled.
    3. **Execute** the operation against the organisation database (or the isolated backup copy) **while T1 holds the shared lock**.
    4. **Outcome:** record it (`succeeded` / `failed`), then commit T1.
  - **Withdrawal, closing and expiry take the exclusive lock on the grant row.**
    - **They wait for an operation already in flight, and no operation can start after them.**
    - **Linearisation point: the grant row lock.** An operation counts as authorised if and only if its shared lock was granted before the exclusive one.
  - **Interrupted between execute and outcome:**
    - the access record stays `attempted` with no outcome;
    - **reconciliation flags it to the organisation as "access may have occurred"** (never as "no access").
  - **Session revocation** is a second layer, not the guarantee. The guarantee is that the per-operation check refuses everything after the exclusive lock.
- **Withdrawal from the organisation's own database** (after recovery, once the grant has been copied):
  - delivered to the platform as a command against the grant row (idempotency key: grant id);
  - **until it lands the grant remains active.** The delay is bounded by delivery and is a stated proof obligation (**[TU] P-W1**).
  - **The organisation's copy of a grant never widens it:** only the platform row admits access, and the organisation may only withdraw or close it.

#### F-02: completions

- **Who keeps the evidence:** the sender, until submission into the recovered organisation database (rev 4 H-2). **The central record holds metadata only:** references, digests, sender, time.
- **Cited decision not found** in the recovered organisation's decision log → **not validated** (same consequence as a missing document).
- **Digest mismatch** → not validated. Both versions are recorded for the organisation's challenge route.
- **Temporary effect before validation:** yes, as D-176 already provides. This needs **no change to D-176** and raises no Principal question. Its business consequence is stated:
  - **an unvalidated addition can be grant-capable after 24 hours** (D-180), but only with a second distinct contact's approval (D-182);
  - every step is notified (D-179);
  - every grant given under an unvalidated registration is flagged at recovery.

#### F-03: completions

| Case | Result |
|---|---|
| older decision arriving later | refused as stale (rule 1); recorded; sender notified |
| newer decision arriving earlier | applies (if it passes D-176 checks); a later-arriving older one is refused |
| duplicate / retry / delayed delivery | idempotent on (organisation, decision ref, contact); a delayed copy is a duplicate or stale |
| conflicting evidence (two registrations, same cited decision, different digests) | **both refused** as conflicting; contacts and counterparty notified |
| future-dated decision | refused |
| recovery | organisation records replace the platform list (rule 4) |

- **Business consequence, stated:**
  - **during an outage, precedence follows the decision order the senders cite, not arrival order;**
  - the citation is unverifiable until recovery, so a backdated or invented decision is caught only at validation (F-02);
  - **it cannot override any entry backed by a newer decision.**
- **[OQ] for the reviewer:** is ordering by cited decision date a *business* precedence rule needing the Principal, or is it implied by D-176 ("the organisation's decisions govern")? The author holds the latter; **the Principal has not ruled on it.**

#### F-04: completions

- **Retry:**
  - exponential backoff;
  - limit 24 hours or 8 attempts (platform default, overridable by the operator configuration; not organisation authority);
  - then `permanently-failed`.
- **Delivery evidence:** the provider's acceptance id, plus delivery confirmation where the channel supplies it. **Absent confirmation, the state stays `dispatched`.**
- **Removed contacts are notified** of their own removal and of every later change during the outage.
- **D-179 unchanged:** the login address is used only for these security notices.

#### F-06: the full matrix (supersedes part 1's table)

| # | Case | Aggregates | Transaction boundary | Linearisation point | Idempotency key | Lock order | Order A → result | Order B → result | Recovery | Proof test |
|---|---|---|---|---|---|---|---|---|---|---|
| 1 | contact removal vs grant approval | `ServedOrganisation`, `PendingCaseGrant` | one central transaction each | `ServedOrganisation` row (X for update, S for approval) | update: (org, decision ref, contact); approval: (grant id, approver) | `ServedOrganisation` → grant | removal first: approval refused | approval first: valid, reported, withdrawable | rollback leaves neither | concurrent runs both orders; assert no grant approved by a removed contact |
| 2 | second approval vs withdrawal | `PendingCaseGrant` | central | grant row X | (grant id, approver) / grant id | grant only | withdrawal first: approval refused | approval first: `given` then `withdrawn` | — | both orders; final state `withdrawn` |
| 3 | withdrawal vs use | `PendingCaseGrant` + gateway | central T1 (S) vs withdrawal (X); org DB separate | grant row lock | grant id | grant → org DB | withdrawal first: operation refused | operation first: completes, withdrawal waits, next refused | `attempted` without outcome flagged | inject withdrawal mid-operation; assert no operation starts after X lock |
| 4 | already-open session | as 3 | as 3 | as 3 | session id | as 3 | — | — | session revoked; per-operation check backstops | open session, withdraw, assert next operation refused |
| 5 | duplicate grant command | `PendingCaseGrant` | central | unique (org, case ref) | case ref | — | first creates | second returns first | — | parallel duplicates create one |
| 6 | duplicate / delayed / reordered contact updates | `ServedOrganisation` | central | row X | (org, decision ref, contact) | — | per F-03 | per F-03 | — | permutations of 3 updates; final list = newest cited decision |
| 7 | recovery copy into organisation database | organisation inbox (org DB) | org DB transaction; platform flag set after acknowledgement | inbox unique event id | event id | — | copy then flag | crash before flag: copy repeated, idempotent | re-delivery until acknowledged | crash between steps; assert one copy |
| 8 | evidence submission and reconciliation | org DB (Governance) + platform list | org DB transaction, then platform reconciliation command | `ServedOrganisation` row X | (org, registration id) | org DB → platform (separate) | validated: kept | not validated: removed, grants flagged | reconciliation retried until done | missing / mismatch / not found / NotAuthorised cases |

**No distributed transaction:** rows 3, 7 and 8 cross databases only through ordered, idempotent steps.

#### F-07: completions

- **The registrar's claim is issued during `provisioning`, before `operational`.** Redemption and routing follow D-113 and D-178 (multi-database design §3–§4). It is a one-time act, not a claim-issuance mechanism.
- **`migrating` blocks routing** (fail-closed; unchanged from rev 4 M-8).

#### Final coherence check (author's, not independent)

| Item | Result |
|---|---|
| D-164 physical separation | kept; corrected F-05 removes the only cross-database transaction claim |
| D-166 / INV-ACCESS-1 | kept; access only through the gateway under an active, named grant |
| D-170 | kept; `PendingCaseGrant` remains the central record |
| D-174, D-176 | kept; provisional effect during outage; organisation records govern after |
| D-175 | kept; counterparty is a messenger, never organisational power |
| D-179 | kept; narrow use only |
| D-180, D-182 | implemented |
| D-181 | applied at validation |
| ISO-1 … ISO-11 | no change; ISO-11 realised by the gateway |
| Identity XD-3 | no change (Identity resolves PersonRef → Account; no duplicate person) |
| Governance authority | **validity comes only from Governance's AuthorityCheck**; Platform creates no authority |

**Open:**
- **[OQ]** F-03 precedence classification;
- **[TU]** every matrix proof test and P-W1;
- **[LQ]** as listed in part 1.

**Verdict (author's): READY FOR FOCUSED INDEPENDENT RE-REVIEW. Not accepted.**

---

## Revision 6 (2026-11-11): corrections after IR-P-02-R (GPT-6, focused re-review; verdict NOT READY) and D-183 … D-185

**Status:**
- Revision 6 governs where it differs from revision 5 (parts 1 and 2) and earlier revisions.
- **Proposed, not accepted.**
- It implements D-183, D-184 and D-185, and reopens no decision.
- Written by Claude; not independent.

**Resolved by IR-P-02-R and kept as written:** F-04 (notifications) and the bootstrap exception.

### R6-1. Approver eligibility (D-182 + D-183; resolves F-01-R)

**Each `PlatformContacts` entry carries `source`:**
- **`organisation-record`**: the designation is recorded in the organisation's own database and was copied to the platform through the normal route while the database worked. Initial contacts count once the founding records in the organisation database record their designation (during `provisioning`).
- **`direct`**: registered through the emergency route (D-176).

**Approval rules:**
- **An approver (creator or second approver of an emergency grant) must be a current entry with `source = organisation-record`.**
- A `direct` entry receives notices (D-179) and may withdraw a grant, but **never approves**.
- D-180's 24 hours apply only to the `direct` entry's other capabilities; **they confer no approval right** (D-183).
- **On recovery,** a `direct` entry becomes `organisation-record` only if the organisation's records designate that person; otherwise it is removed in reconciliation.
- **Fewer than two current `organisation-record` entries:** emergency grants are refused (D-182, D-183).

**Capture scenario:**
1. X removes Y and Z (immediate, D-176).
2. X adds A (`direct`).
3. **After any wait, X and A still cannot grant**, and X alone cannot grant.
4. **Result: denial of service until recovery, never access.**

**[TD], conservative:** at the second approval, the first approver must **still** be a current `organisation-record` entry; otherwise the request lapses. This narrows access and adds no authority. **Flagged for the reviewer:** it is stricter than D-182's literal "at each approval".

### R6-2. Provisional operation versus constitutional validity (resolves F-02-R)

- **Senders (D-176, unchanged):** a direct registration may be sent by **a current platform contact or the verified service counterparty (D-175)**, citing the organisation's decision.
  - The counterparty is **a transmission channel only**: it holds no authority, and its sending validates nothing.
  - *(Revision 5's "sender current" is corrected to "sender is a current contact or the verified counterparty".)*
- **Two different things, never merged:**

| | Provisional platform operation | Constitutional validity |
|---|---|---|
| What | the entry's effect on the **emergency route only** (D-176), during the outage | whether the **organisation's decision** was genuinely and competently made |
| Who decides | the platform's mechanical checks (sender, evidence elements, D-184 order) | **Governance only** (AuthorityCheck), after recovery |
| Can establish authority? | **never** | only with outcome `Authorised` |

- **`Undetermined` (D-181) and `NotAuthorised` never establish authority.**
  - The registration is not validated.
  - The platform list is reconciled to the organisation's records.
  - Grants given while it stood are flagged to the organisation.
- **Revision 5's wording "in effect for the emergency route" means only the left-hand column.**

### R6-3. Precedence (D-184; resolves F-03-R)

The F-03 rules of revision 5 now rest on D-184. **Same-date conflicts (resolving the ambiguity GPT-6 named):**

| Situation | Platform list state |
|---|---|
| two different decisions, same cited date; first already applied | **first stays**; second refused; **both flagged** for validation at recovery |
| two different decisions, same cited date, arriving concurrently | linearised on the `ServedOrganisation` row: whichever commits first is "already applied"; the other is refused; both flagged |
| same decision, different digests | **both refused** (rev 5 part 2); if one had already been applied, **it is withdrawn from the list**, notified and flagged |
| same decision, same digest | idempotent |

### R6-4. Withdrawal by the organisation and expiry (resolves F-05-R; the author's technical answer to GPT-6's P-3)

**No Principal decision was needed: the bound is enforced by construction, not by delivery time.**

- **`live-database` case.** The operation runs in a transaction in the organisation database. **Its first step reads the organisation's copy of the grant, if any, with a shared lock.**
  - A withdrawal in the organisation's database takes that row exclusively.
  - **The organisation's own withdrawal is therefore linearised inside the organisation database. Propagation latency: zero.**
  - Before the grant is copied, the organisation's database holds no copy and cannot withdraw it, so the platform row governs.
- **`backup-copy` case.** Once copy delivery to the organisation has begun (outbox record exists), **each admission reads the organisation's copy status.**
  - Withdrawn → refused.
  - **Organisation database unreachable → refused (fail closed).**
  - A withdrawal committed after that read affects only the operation in flight; the next one is refused.
  - **Bound: one operation.**
- **Propagation** of the organisation's withdrawal to the platform row remains as before (idempotent, grant id), **for the record only. Access no longer depends on it.**
- **Lock order:**
  1. platform grant row (S);
  2. the organisation's grant copy (S).
  - Withdrawals lock only their own side (X), so **no cycle** is possible.
- **Expiry admission rule:**
  - **Admission point:** after the platform shared lock is acquired, the gateway reads the clock `t_admit`. **The operation is admitted only if `t_admit < expiry`.**
  - The organisation-side transaction runs with **deadline = expiry**: statement timeout, plus the gateway stops returning results at expiry and aborts.
  - **No result is delivered after expiry.**
  - An expiry or close command takes the exclusive lock like a withdrawal.

### R6-5. Corrected concurrency matrix (resolves F-06-R1 … R3; supersedes rev 5 part 2 rows 1–2 and adds rows)

**One approval transaction, used for both approvals:**
1. lock `ServedOrganisation` (S);
2. lock `PendingCaseGrant` (X);
3. check the approver (current, `organisation-record`, distinct) and the grant (`requested`, unchanged, not expired); at the second approval, also check the first approver (R6-1);
4. write;
5. commit.

**Contact update:** `ServedOrganisation` (X) only. **Withdrawal / close / expiry:** `PendingCaseGrant` (X) only. **Global order: `ServedOrganisation` → `PendingCaseGrant` → (organisation-side grant copy). No path takes them in reverse.**

| # | Case | Linearisation point | Order A | Order B | Proof test |
|---|---|---|---|---|---|
| 1 | removal of an approver vs **any** approval (first or second) | `ServedOrganisation` S/X | removal first: approval refused | approval first: recorded; removal then makes a pending request lapse (R6-1) or, if `given`, is reported and withdrawable | both orders, first and second approval |
| 2 | second approval vs withdrawal | `PendingCaseGrant` X (after `ServedOrganisation` S) | withdrawal first: approval refused | approval first: `given`, then `withdrawn` | both orders; **with a concurrent contact removal as third party** |
| 2a | second approval vs contact removal vs withdrawal (3-way) | as above | all 6 orders | — | no grant ends `given` with a removed approver at its approval time |
| 3 | platform withdrawal vs use | grant row S/X | refused | in-flight completes; next refused | as rev 5 |
| 3a | **organisation-side withdrawal vs use (live-database)** | organisation grant copy S/X | refused | in-flight completes; next refused | both orders, without platform propagation |
| 3b | **organisation-side withdrawal vs use (backup-copy)**; organisation DB unreachable | admission read | refused | in-flight completes | unreachable → refused |
| 3c | **expiry** | `t_admit` under S lock | admitted at `t_admit < expiry`, results stop at expiry | `t_admit ≥ expiry` refused | admission just before; at; just after; **delayed between check and execution**; expiry command waiting on an admitted operation |
| 5 | duplicate grant command | unique (org, case ref) | — | — | **same key, different scope → rejected**, not merged |
| 6 | duplicate / delayed / reordered contact updates | `ServedOrganisation` X | D-184 | D-184 | permutations incl. same-date cases (R6-3) |
| 8 | evidence reconciliation | `ServedOrganisation` X | — | — | missing · mismatch · not found · NotAuthorised · **`Undetermined`**: assert not validated, no authority, list reconciled, grants flagged |

**Rows 4 (open session) and 7 (recovery copy) of rev 5 part 2 are unchanged.** All rows: **proof tests are [TU], not executed.**

### R6-6. Lifecycle (D-185; resolves F-07-R1)

**`pending` is a platform registry status** (D-185): application recorded; no technical operation.
- `pending` → `provisioning`: counterparty verified (D-175) **and** service-operation grant recorded (D-166).
- `provisioning` → `operational`: as revision 5 F-07.
- D-175's wording carries the authorised clarification note.

### Status after revision 6

| Finding | Resolution |
|---|---|
| F-01-R | D-183, R6-1 |
| F-02-R | R6-2 |
| F-03-R | D-184, R6-3 |
| F-04 | resolved in IR-P-02-R |
| F-05-R | R6-4 (technical; no Principal decision; **reviewer to confirm**) |
| F-06-R1 … R3 | R6-5 |
| F-07-R1 | D-185, R6-6 |
| bootstrap | resolved in IR-P-02-R |

**Open:**
- **[TU]** all proof tests;
- **[LQ]:** counterparty evidence, identity-check methods, retention, `PersonRef` classification;
- **flagged for the reviewer:** the R6-1 first-approver re-check (stricter than D-182's literal wording).

**Verdict (author's): READY FOR FOCUSED INDEPENDENT RE-REVIEW. Not accepted.**

---

## Revision 7 (2026-11-11): corrections after IR-P-02-R2 (GPT-6, focused re-review; verdict NOT READY) and D-186, D-187

**Status:**
- Revision 7 governs where it differs from revision 6 and earlier revisions.
- **Proposed, not accepted.**
- It implements D-186 and D-187, and reopens no decision.
- Written by Claude; not independent.

**Resolved by IR-P-02-R2 and kept as written:**
- R6-2 (provisional operation versus validity);
- R6-3 (D-184 precedence);
- R6-6 (D-185 lifecycle);
- the D-183 eligibility rule;
- the R6-5 lock order.

### R7-1. Promotion requires Governance `Authorised` (resolves R2-2; corrects R6-1)

- **A `direct` entry becomes `organisation-record` only when both of these hold:**
  1. the organisation's records contain the designation;
  2. **Governance's AuthorityCheck on the designating decision returns `Authorised`** in the recovery validation (R6-2).
- **`NotAuthorised` or `Undetermined` (D-181):**
  - no promotion;
  - the entry is removed in reconciliation;
  - grants given while it stood are flagged.
- **The same condition applies to every contact entry copied from the organisation's records** through the normal route, initial contacts included: **the copy carries the AuthorityCheck outcome, and only `Authorised` designations produce `organisation-record` entries.**
- **R6-1's sentence "becomes `organisation-record` only if the organisation's records designate that person" is superseded.**

### R7-2. Both approvers current at completion (D-186; settles R2-1)

- **R6-1's first-approver re-check is now the rule (D-186).** At the second approval, both approvers must be current `organisation-record` entries; otherwise the request lapses.
- A grant already `given` is not undone by a later removal; it is reported and withdrawable.
- **The approval transaction (R6-5) is unchanged.** It already holds `ServedOrganisation` S while checking both approvers.

### R7-3. Recovery handover and the missing copy (D-187; resolves R2-3, R2-4; supersedes R6-4 where they differ)

**Handover marker:** the platform enqueues the grant copy (outbox record, per grant) **when the organisation's database is declared able to answer** (routing restored). **From that moment the grant is in `handover`.**

**Admission rule for every protected operation** (`live-database` and `backup-copy`), after the platform grant row's shared lock and the expiry check (R6-4):

| Grant state | Organisation's copy | Result |
|---|---|---|
| no outbox record (database cannot answer) | n/a | **platform row governs** (R6-4) |
| outbox record exists | **absent** (not yet delivered) | **refused** (D-187) |
| outbox record exists | **unreadable** (database unreachable) | **refused** |
| outbox record exists | present, withdrawn or closed | **refused** |
| outbox record exists | present, active | **admitted**; for `live-database` the copy is held with a shared lock inside the operation's transaction (R6-4); for `backup-copy` see R7-4 |

**Withdrawal paths:**
- **Throughout:** platform withdrawal by any current contact is immediate (R6-4 row 3).
- **After the copy exists:** organisation-side withdrawal is linearised on the copy row.
- **Corrected claim:** R6-4's "propagation latency: zero" holds **from the moment the copy exists**. **Before that, D-187 refuses access, so no un-withdrawable interval remains.**

### R7-4. `backup-copy`: serialising admission with organisation-side withdrawal (strengthens R6-4; addresses R2-5)

- **A bare status read is replaced by a short transaction in the organisation database:**
  1. `SELECT … FOR SHARE` on the grant-copy row;
  2. check the row;
  3. **register the admitted operation as an `admission` row** (grant id, operation id), committed in that same transaction.
- **Organisation-side withdrawal takes the copy row `FOR UPDATE`.** It therefore serialises with every admission:
  - admissions committed before it are the in-flight set;
  - admissions after it see `withdrawn`.
- **Bound:** operations whose admission committed before the withdrawal may complete **up to their own deadline** (min(expiry, operation limit), R6-4). **No new operation is admitted.**
- **Failure during admission** (organisation database lost mid-transaction) → no commit → **refused**.

### R7-5. Matrix additions (supersede R6-5 row 3b; add rows 3d, 9)

| # | Case | Linearisation point | Order A | Order B | Proof test |
|---|---|---|---|---|---|
| 3b | organisation-side withdrawal vs `backup-copy` admission | organisation copy row (SHARE / UPDATE) | withdrawal first: admission refused | admission first: operation completes within its deadline; next refused | admission before withdrawal; withdrawal before admission; withdrawal after admission commit but before execution; database failure during admission; the next operation after withdrawal refused |
| 3d | **handover:** operation vs copy delivery | outbox record + copy row presence | copy absent: refused | copy present and active: admitted | operation attempted between outbox record and copy arrival (both case types) refused; after arrival admitted; withdrawal on the platform during handover immediate |
| 9 | promotion on recovery | `ServedOrganisation` X (reconciliation) | `Authorised`: promoted | `NotAuthorised` / `Undetermined`: removed, grants flagged | all three outcomes; a promoted entry may then approve; a non-promoted entry never approves |

**The other rows are unchanged.** All proof tests remain **[TU], not executed, and are build gates (R2-6):**
- row 2a (six orders);
- 3a;
- 3b;
- 3c (boundary, delayed execution, no result after expiry);
- 3d;
- 8 (`Undetermined`);
- 9;
- **the `attempted`-without-outcome window** (reconciliation reports "access may have occurred").

### Status after revision 7

| Finding | Classification | Resolution |
|---|---|---|
| R2-1 | (c) | D-186, R7-2 |
| R2-2 | (a) | R7-1 |
| R2-3 | (c) | D-187, R7-3 |
| R2-4 | (a) | R7-3 (absent copy → refused) |
| R2-5 | (b) | R7-4 mechanism + row 3b proofs [TU] |
| R2-6 | (b) | [TU], build gates |

**Open:**
- **[TU]** all proof tests;
- **[LQ]** counterparty evidence, identity-check methods, retention, `PersonRef` classification.

**Verdict (author's): READY FOR FOCUSED INDEPENDENT RE-REVIEW. Not accepted.**

---

## Revision 8 (2026-11-11): corrections after IR-P-02-R3 (GPT-6, focused re-review; verdict NOT READY) and D-188, D-189

**Status:**
- Revision 8 governs where it differs from revision 7 and earlier revisions.
- **Proposed, not accepted.**
- It implements D-188 and D-189, and reopens no decision.
- Written by Claude; not independent.

**Confirmed by IR-P-02-R3 and kept as written:**
- the missing-copy rule (absence means refusal; the request terminates and a new attempt rechecks);
- D-183 … D-187 as implemented.

### R8-1. One AuthorityCheck per individual designation, non-reusable (resolves R3-1; refines R7-1)

- **One AuthorityCheck request per designated person.**
  - Reconciliation issues one request for each contact entry to be confirmed. It is answered by Governance in the organisation's database (D-164).
  - It uses **the accepted Governance contract, with no second schema.**
  - **The request:**
    - act kind `designate-platform-contacts` (rev 4 M-10);
    - `evaluatedFor` = the designated person's `PersonRef`;
    - `requestRef` = (act kind, decision reference **and part**, the designated `PersonRef`, **a reconciliation request id generated for this entry and used once**).
  - **The answer carries the ratified fields:**
    - actor, capacity, ruleRef, policyVersion, result, checkedAt;
    - outcome, competenceKind, decisionRef, evidence, requestRef.
- **Acceptance check before promotion,** in the `ServedOrganisation` X transaction (row 9). All must hold:
  - `outcome = Authorised`;
  - `requestRef` equals the request issued for **this** entry;
  - `evaluatedFor` resolves through Identity to **this** entry's Account (rev 4 M-5);
  - the decision reference and part match the designation the entry cites.
- **Anything else is refused and recorded as a mismatch.** Examples:
  - evidence for another person;
  - another decision or part;
  - a reused request id;
  - an answer to a request never issued.
- **A multi-person decision yields one request and one answer per person; one person's `Authorised` never promotes another.**
- The entry stores a reference to its evidence. **Each request id is unique and consumed on use.**

### R8-2. Recovery and handover are one central transition, serialised with admission (resolves R3-3; supersedes R7-3's "handover marker" paragraph)

**Recovery transition** (an explicit platform act restoring routing for the organisation, never a health observation, per rev 1 §3.4). **One central transaction:**
1. lock `ServedOrganisation` X; set routing restored;
2. for each `PendingCaseGrant` of the organisation in state `given`/`active`, lock the grant row X:
   - set **`handoverState = handover`**;
   - insert its **outbox record**;
3. commit.

**Admission** (R6-4 / R7-3) holds the grant row S and **checks `handoverState`, not the existence of an outbox record:**
- `none` → the platform row governs;
- `handover` → the R7-3 table rows "outbox record exists" apply (copy required).

**Linearisation point: the grant row lock.**
- An admission that obtained S **before** the transition proceeds under the pre-handover rule, and the transition waits for it, within the D-189 deadline.
- **Every admission after the transition sees `handover`. No interval remains.**

**Lock order:** `ServedOrganisation` → `PendingCaseGrant` (global order, R6-5); admission takes only the grant row.

### R8-3. Copies are versioned and terminal states never reopen (resolves R3-4)

- **`PendingCaseGrant.version`** increases with every state change.
- **Every state change of a grant in `handover`, or after the copy exists,** inserts a new outbox record **in the same transaction**. This covers withdrawal, close, expiry, suspension (D-188) and use-up.
- **The organisation's inbox applies a copy only if its version is higher** than the copy it holds (monotonic).
- **States `withdrawn`, `closed`, `expired`, `suspended` and `used-up` are terminal on both sides:** no delivery can move a copy from a terminal state back to `active`.
- **Admission requires both the central row and the copy to be active** (R7-3). A stale active copy therefore never admits once the central row is withdrawn.
  - *(This was already true, because the central check runs first (R6-4 step 2), so R3-4 did not reopen access.)*
  - **R8-3 also keeps the organisation's own records truthful.**
- **Organisation-side withdrawal** changes the copy (terminal) and propagates to the central row. Applying it centrally increments the version.

### R8-4. Operation identity (resolves R3-5)

- **The gateway assigns each requested operation an `operationId`**, bound to (grant, operator, operation kind, scope digest).
- **Unique key `(grant_id, operation_id)`** on the admission row (organisation side, R7-4) and on the central access record.
- **Retries:**
  - **same id, same binding:** returns the existing admission. **Never a second admission.**
  - **same id, different binding:** refused.

### R8-5. Admission lifecycle and failure after commit (resolves R3-6)

**Admission states:** `admitted` → `started` → `succeeded` | `failed` | `aborted-at-deadline`.
- `started` is committed **before** execution begins.
- **No exactly-once claim.**

| Failure point | Rule |
|---|---|
| before the admission commits | no admission; refused (R7-4) |
| after admission commit, before `started` | a retry **with the same `operationId`** may proceed, after a fresh grant check and within the original deadline; otherwise the admission is closed `abandoned` |
| after `started`, no outcome | **never re-executed**; reconciliation reports **"access may have occurred"** (central `attempted` record, B-1); any new work needs a new `operationId` and a fresh admission |
| during retry or reconciliation | idempotent on `operationId`; same rules |

### R8-6. Operation deadline (D-189; resolves R3-7)

- **Deadline** = min(grant expiry, `t_admit` + limit).
  - Limit = **15 minutes (platform default, D-189)**, or the shorter limit stated in the grant.
  - It is fixed at admission and stored with the admission row.
- **Enforcement:**
  - **`live-database`:** the organisation transaction runs with statement and transaction timeouts set to the deadline; at the deadline it is aborted (`aborted-at-deadline`).
  - **`backup-copy`:** the job is terminated at the deadline and its partial output discarded.
  - **Both:** the gateway delivers **no result after the deadline**.
- Longer work is split into operations, each admitted separately.

### R8-7. Suspension of grants under failed designations (D-188)

- **In reconciliation** (row 9): when a designation is `NotAuthorised` or `Undetermined`, each grant **approved by that contact** is suspended. Mechanics:
  - grant row X, under the global lock order;
  - state `suspended` (terminal);
  - version incremented;
  - outbox record (R8-3).
- **In-flight operations** end by their deadline (R8-6); no new admission.
- Access already taken is reported. Grants are reported and flagged.
- **D-186's removal rule is unaffected.**

### Status after revision 8

| Finding | Class | Resolution |
|---|---|---|
| R3-1 | (a) | R8-1 |
| R3-2 | (c) | D-188, R8-7 |
| R3-3 | (a) | R8-2 |
| R3-4 | (a) | R8-3 (access was not reopened; records now consistent) |
| R3-5 | (a) | R8-4 |
| R3-6 | (a) | R8-5 |
| R3-7 | (a) + value | D-189, R8-6 |
| R3-8 | (b) | proof register, section C (all NOT EXECUTED) |

**Open:**
- **[TU]** every proof obligation;
- **[LQ]** as before.

**Verdict (author's): READY FOR FOCUSED INDEPENDENT RE-REVIEW. Not accepted.**

---

## Revision 9 (2026-11-11): corrections after IR-P-02-R4 (GPT-6, focused re-review; verdict NOT READY; no Principal question)

**Status:**
- Revision 9 governs where it differs from revision 8 and earlier revisions.
- **Proposed, not accepted.**
- It reopens no decision.
- Written by Claude; not independent.

**Confirmed by IR-P-02-R4 and kept as written:**
- R8-2's per-grant serialisation and lock order;
- R8-5's lifecycle (no exactly-once claim);
- D-188, D-189.

### R9-1. AuthorityCheck mapping and rule checks (resolves A-1; refines R8-1)

**Mapping onto the accepted contract (Governance correction note 4: `requestRef` = "the subject and request identifiers"). No new field and no second schema:**

| Contract element | Value for a contact designation |
|---|---|
| act kind (request) | `designate-platform-contacts` |
| `evaluatedFor` | the decision date (D-12) |
| `requestRef`, **subject identifier** | **the designation**: `(decisionRef, part, designated PersonRef)`, as one identifier value. A Governance **Resolution has parts, each with its own competence evaluation** (thin slice §6 `Resolution`), so "part" is the Resolution part |
| `requestRef`, **request identifier** | the reconciliation request id (unique, used once) |

- **Precision (correcting R8-1):** R8-1 named `evaluatedFor` as the designated `PersonRef`. In the accepted contract, `evaluatedFor` is the **business date**. **The designated person travels in the subject identifier.**
- **Acceptance predicate before promotion** (all must hold, in the `ServedOrganisation` X transaction):
  1. `outcome = Authorised`;
  2. the request identifier equals the one issued for this entry and not yet consumed;
  3. the subject identifier equals this entry's designation, and its `PersonRef` resolves through Identity to this entry's Account;
  4. **`ruleRef` and `policyVersion` equal the citation and rule-document version recorded with that Resolution part's competence evaluation** in the Governance decision log. Resolutions are immutable (GD-1), so these are fixed values.

  **The reconciliation reads them in the same organisation-side step and sends them with the answer.**
- **Any mismatch** → refused and recorded; the entry is not promoted.
- **Retention:** the full answer (six fields + basis) is stored with the entry's evidence reference, for audit and reconciliation.
- **Conformance item (for acceptance, not a new rule):** `designate-platform-contacts` is a new AuthorityCheck act kind (rev 4 M-10). **Like D-163's act kinds, it needs an addition note in the Governance thin slice.** That note is offered for the acceptance act; it is not written here.

### R9-2. Handover version (resolves C-1; refines R8-2, R8-3)

- **The R8-2 handover transition is a state change: it increments `PendingCaseGrant.version`.** Its outbox record carries the new version `v_h`.
- **An absent organisation copy counts as version 0,** so the first delivery (`v_h ≥ 1`) always applies.
- **Every later outbox record carries the version of the state change it reports.**

### R9-3. Operation identity (resolves D-1; supersedes R8-4)

- **`operationId` is globally unique** (gateway-generated random 128-bit identifier).
- **Central `OperationRegistry`,** unique on `operation_id`, stores the **immutable binding:**
  - grant id;
  - operator;
  - operation kind;
  - scope digest;
  - **operator request id** (the identifier of the operator's request on the operations channel, ISO-11).
- **It is written in the gateway's first, committed step** (the central access record, rev 5 part 2).
- **Same `operationId`, identical binding** → the existing operation (no second admission). **Any difference** (grant, request, scope, kind, operator) → refused.
- **The organisation admission row** is unique on `operation_id` as well, and carries the grant id.
- **`started` claim:** a conditional update (`admitted` → `started` only where the state is `admitted`). **Exactly one executor can succeed;** a concurrent retry finds `started` and does not execute (R8-5).

### R9-4. Deadline clock and enforcement (resolves E-1; completes R8-6; D-189 unchanged)

- **Authoritative time:** the **central database clock**, read inside T1 after the grant row's shared lock (R6-4). **This is `t_admit`.**
  - Grant expiry is stored in the same clock.
  - **Deadline `D` = min(expiry, `t_admit` + limit)**, with limit = 15 min (D-189) or shorter per grant. It is fixed at admission and stored with both admission records.
- **No cross-host clock comparison:** execution environments receive **a remaining duration, not an absolute time**.
  1. **At admission:** remaining = `D − t_admit`.
  2. **The gateway** starts a monotonic timer.
  3. **At every dispatch** (to the organisation database or the backup worker) it recomputes remaining = (`D − t_admit`) − (monotonic time elapsed). **If ≤ 0 → not dispatched; `aborted-at-deadline`.**
- **`live-database`:**
  - the session is opened with statement, transaction and lock timeouts equal to the remaining duration;
  - **at expiry of the gateway timer, the gateway cancels and then terminates the backend.**
- **`backup-copy`:**
  - the worker runs isolated, and **its only output channel is the gateway**;
  - it receives the remaining duration as a lease;
  - **at expiry the gateway terminates it** and discards partial output.
  - **If termination cannot be confirmed:** the isolated copy is **destroyed** (B-2), and the incident is reported to the organisation.
- **Results:** the gateway is the only conduit and **relays nothing after its timer expires**.
- **Fail closed:** if the central clock cannot be read at admission, or the remaining duration cannot be established at dispatch, the operation is **refused**.

### R9-5. D-188 reporting (resolves F-1; completes R8-7)

**The report to the organisation names:**
- **the failed designation** (decision, part, person, outcome);
- **every grant suspended because of it**;
- **every operation under those grants**, including "access may have occurred" cases.

**Delivery:** via the organisation's inbox (idempotent on event id), with D-179 notices to the current contacts.

### R9-6. Proof register re-stamped (resolves G-1)

- **The register now lives at `2026-11-11-platform-proof-obligation-register.md`, stamped for revision 9.** Sections A and B were reviewed and carried forward; C stays; **D is added**.
- The revision-7-named file stays as a historical record, with a pointer.

### Status after revision 9

| Finding | Resolution |
|---|---|
| A-1 | R9-1 |
| C-1 | R9-2 |
| D-1 | R9-3 |
| E-1 | R9-4 |
| F-1 (design) | R9-5 |
| G-1 | R9-6 |

**Open:**
- **[TU]** all proof obligations;
- **[LQ]** as before;
- **conformance item:** Governance act-kind addition note (R9-1).

**Verdict (author's): READY FOR FOCUSED INDEPENDENT RE-REVIEW. Not accepted.**

---

## Revision 10 (2026-11-11): corrections after IR-P-02-R5 (GPT-6, focused re-review; verdict NOT READY) and D-190, D-191

**Status:**
- Revision 10 governs where it differs from revision 9 and earlier revisions.
- **Proposed, not accepted.**
- It implements D-190 and D-191, and reopens no decision.
- Written by Claude; not independent.

**Confirmed by IR-P-02-R5 and kept as written:**
- R9-3's global operation identity and `started` claim;
- R9-2's central handover version;
- `evaluatedFor` = decision date;
- the register re-stamp.

### R10-1. Draft Governance addition note for D-190 (resolves R5-01 as far as Platform can; the note itself is Governance's)

**Proposed text, to be appended to the Governance thin slice by the acceptance act (D-190). It is not appended now.** It follows the pattern of the D-163 addition:

> **Addition (D-190):** AuthorityCheck act kinds consumed by Platform (tenancy). Ratified six fields + basis; `requestRef` per correction note 4.
>
> | Act kind | Subject (`requestRef` subject identifier) | Request identifier | `evaluatedFor` | Competent | Rules silent |
> |---|---|---|---|---|---|
> | **`designate-platform-contacts`** | one designation: (Resolution, part, designated `PersonRef`) | consumer's single-use request id | the Resolution's decision date (D-12) | the body or office the organisation's rules (or a designation) make competent to designate platform contacts | **`Undetermined`** until a competent body designates who decides (D-167, D-181) |
> | **`give-case-grant`** (normal route only, D-174) | one grant: (organisation, case reference, data area, window, operator, case type) | consumer's single-use request id | the date of the act giving the grant | the body or office the rules (or a designation) make competent to give case grants | **`Undetermined`**, as above |
>
> **The answer's `ruleRef` and `policyVersion` are the citation and rule-document version under which the competence of that Resolution part (or act) was evaluated.** For a Resolution, these values are **recorded with the part's competence evaluation and never change** (GD-1). *(This sentence closes M-4 for these act kinds, if ratified.)* **Emergency grants (D-174, D-176) are not checked with `give-case-grant`.** Their approvers' designations are checked with `designate-platform-contacts` at recovery (D-183, D-188).

**Consequence (D-190):** an organisation whose rules are silent must designate its platform contacts at joining, in its founding documents. Otherwise it has none.

### R10-2. Central contact metadata (D-191; resolves R5-10)

**Each `PlatformContacts` entry holds exactly:**
- the Account reference (D-174);
- `source` (`organisation-record` | `direct`);
- **the cited decision reference and date;**
- **the document digests;**
- **the sender;**
- **the validation outcome and its evidence reference;**
- `grantableFrom` (D-180).

**Nothing else.** No decision texts, minutes or rules (D-164). The entry is replaced by the organisation's records on recovery (D-176).

### R10-3. Atomic copy application in the organisation inbox (resolves R5-04; completes R8-3, R9-2)

**One organisation-database transaction per delivered copy:**
1. insert the event id into the inbox (unique; a duplicate ends the transaction: **idempotent**);
2. **lock the grant copy row `FOR UPDATE`.** If it is absent, insert it as version 0 under the unique key on grant id (`ON CONFLICT DO NOTHING`), then lock;
3. **apply only if `incoming.version > copy.version` and the copy is not in a terminal state.** A terminal incoming state may replace a non-terminal copy; nothing replaces a terminal copy;
4. commit.

**Concurrent deliveries serialise on the row lock. Version 5 arriving after version 6 is ignored.** This is the same row that admission locks `FOR SHARE` (R7-4), so delivery and admission serialise as well.

### R10-4. Deadline: no unaccounted time (resolves R5-05; completes R9-4)

- **The gateway takes its monotonic reading `m0` before it sends the admission request.** It then computes:
  - **remaining = (`D − t_admit`) − (monotonic now − `m0`).**
- **This is conservative:** `m0` precedes `t_admit`, so round-trip, commit and scheduling delays are all subtracted.
- **Gateway failover or restart:**
  - the new instance has no `m0`;
  - **an operation in `started` is never resumed** (R8-5);
  - an admission that has not started recomputes remaining = `D − t_now`. It reads `t_now` from the central clock, again bracketed by a monotonic reading taken before the request. **≤ 0 → not dispatched.**
- **The server-side timeouts set at dispatch (R9-4) enforce the bound independently of the gateway.**

### R10-5. Live-database termination and containment (resolves R5-06; completes R9-4)

- **Each live operation runs in a session tagged with its `operationId`,** under the grant's non-interactive database role (multi-database design §16 M-6).
- **Termination confirmed** = the tagged session no longer exists in the organisation database's session list after cancel, then terminate.
- **Not confirmed within the confirmation window** (a platform setting, never organisation authority):
  1. **containment:**
     - revoke `LOGIN` / `CONNECT` from the grant's role;
     - terminate every session of that role;
     - **close the grant's network path from the gateway** (ISO-11).
  2. **The grant moves to `contained`:** terminal for admission, versioned, reported.
  3. **The operation is recorded as `termination-unconfirmed`, with "access may have occurred".**
  4. **An incident report goes to the organisation** (inbox), with D-179 notices to the current contacts.
- **The organisation may give a new grant** through its route. **The contained grant is never reopened.**

### R10-6. D-188 report: one record per operation (resolves R5-07; completes R9-5)

**Each operation under a suspended grant is reported with these fields:**

| Field | Values |
|---|---|
| `operationId`, kind, operator, case type | — |
| `admittedAt`, deadline | central clock |
| lifecycle state | `registered` · `admitted` · `started` · `succeeded` · `failed` · `aborted-at-deadline` · `abandoned` · `termination-unconfirmed` |
| access completed | yes · no · **unknown** |
| access may have occurred | true / false (true whenever `started` has no confirmed outcome) |
| termination status | n/a · confirmed · **unconfirmed, contained** (R10-5) |
| audit references | central `OperationRegistry` row; central access record; organisation admission row |

- **Completeness:** the report lists every `OperationRegistry` row bound to each suspended grant. **The report carries the count, and reconciliation checks it.**
- **Durability:** the report is a versioned record in the organisation's inbox, idempotent on event id. **If reconciliation is interrupted, it resumes and re-emits a higher version.**
- **D-179 notices carry only a security notice, never the report's content.**

### R10-7. From registry to admission (resolves R5-08; completes R9-3)

**`OperationRegistry` states:**

`registered` → `admitted` (organisation admission committed and acknowledged) → … → `abandoned`.

- **Retry with the same binding while `registered`:**
  - the gateway looks for the organisation admission row with that `operationId` (unique);
  - **found** (committed, acknowledgement lost) → use it, with its stored deadline;
  - **absent** → a fresh admission attempt (new `t_admit`, new deadline). **No execution has happened, so no time is extended.**
  - **The unique key prevents a second admission row.**
- **A `registered` row with no admission after the abandonment window** → reconciliation marks it `abandoned`. The same id is then refused.

### R10-8. Proof register

**Section E is added (E-1 … E-8)**, and the register is re-stamped for revision 10. All entries remain **NOT EXECUTED**.

### R10-9. Dependencies outside Platform: acceptance conditions, not Platform defects

| # | Dependency | Owner | Status |
|---|---|---|---|
| X-1 | D-190 addition note (R10-1) ratified into the Governance thin slice | Governance; acceptance act | drafted here |
| X-2 | **Identity operation resolving an organisation `PersonRef` to its bound Account** (XD-3, D-113; **no email matching**) | **Identity (I-TDD-02, other session)** | **open** (R5-02) |
| X-3 | GD-11 / GD-12 decision-log detail; per-part `ruleRef` / `policyVersion` | Governance tactical model (proposed) | **open** (R5-03); per-part values closed by X-1 for the two act kinds, if ratified |

**Until X-2 is accepted, R9-1 predicate 3 fails closed:** no promotion and no emergency approver. The emergency route is then **unavailable**; access is denied, never granted.

### Status after revision 10

| Finding | Resolution |
|---|---|
| R5-01 | D-190, R10-1 (X-1) |
| R5-02 | X-2 (Identity) |
| R5-03 | X-3 |
| R5-04 | R10-3 |
| R5-05 | R10-4 |
| R5-06 | R10-5 |
| R5-07 | R10-6 |
| R5-08 | R10-7 |
| R5-09, R5-11, R5-12 | register section E [TU] |
| R5-10 | D-191, R10-2 |

**Verdict (author's):** the Platform design defects found so far are corrected. **Acceptance depends on X-1 … X-3 and on independent review.** Not accepted.

---

## Revision 11 (2026-11-11): corrections after IR-P-02-R6 (GPT-6, focused re-review; verdict NOT READY) and D-192

**Status:**
- Revision 11 governs where it differs from revision 10 and earlier revisions.
- **Proposed, not accepted.**
- It implements D-192 and reopens no decision.
- Written by Claude; not independent.

**Confirmed by IR-P-02-R6 at design level and kept as written:**
- R10-2 (D-191 metadata);
- R10-3 (inbox compare-and-replace);
- R10-4's initial-admission path;
- the register's integrity.

### R11-1. Governance addition: actor and capacity per act kind (resolves R6 F-01; corrects R10-1)

**The draft note (R10-1) gains a column. It is still proposed, and is ratified with the Platform acceptance (D-190):**

| Act kind | `actor` | `capacity` | Subject |
|---|---|---|---|
| `designate-platform-contacts` | **the deciding organ** (committee) that made the Resolution | **its sitting** (Resolution context) | the designation (Resolution, part, designated `PersonRef`). **The designated person is the subject, never the actor** |
| `give-case-grant` | **the act's maker:** the giving `PersonRef` for an office-holder's act, or the organ for a committee decision | **the tenure** (office) or **the sitting**, matching the actual act | the grant (organisation, case reference, data area, window, operator, case type) |

**This follows the accepted AuthorityCheck mapping** (contract review §3, §5; thin-slice correction notes): committee decisions are pushed with their decision; office-holders' acts are pulled at the act.

**Correction of an overstatement:**
- **The R10-1 sentence that each Resolution part "records" its `ruleRef` / `policyVersion` "and never changes" is withdrawn from the draft note.**
- **R10-9's claim that X-1 closes it is withdrawn.**
- It stays open under **X-3**: Governance must establish it in its own accepted contract; D-190 does not.
- **Until then, R9-1 predicate 4 cannot be evaluated**, so promotion fails closed (no approver, no emergency route), as for X-2.

### R11-2. Failover deadline formula (resolves R6 F-04; corrects R10-4)

**For a new gateway instance** (and for any recomputation):
1. take a monotonic reading `m_before`;
2. request `t_now` from the central clock;
3. **the remaining duration at dispatch = (`D − t_now`) − (monotonic at dispatch − `m_before`)**;
4. **if ≤ 0, or if `t_now` cannot be obtained → not dispatched.**

**Because `m_before` precedes `t_now`, every delay is subtracted:** request, response, scheduling and dispatch. **The initial path is the same formula with `m0` and `t_admit`** (R10-4).

### R11-3. Termination: confirmation window and last resort (resolves R6 F-05; completes R10-5; D-192)

| Step | Action | Confirmation |
|---|---|---|
| 1 | at the deadline: cancel the tagged session | session gone from the organisation database's session list |
| 2 | **confirmation window:** 10 s on the gateway's monotonic clock from step 1 (platform setting; default 10 s; not organisation authority) | — |
| 3 | not confirmed: terminate; **containment:** revoke `LOGIN` / `CONNECT` from the grant's role, terminate the role's other sessions, close the gateway network path; grant → `contained` | session gone, within a further 10 s |
| 4 | **still not confirmed: forced restart of the database server hosting the organisation's database (D-192)** | the server's start time is later than the restart order, **and** the session is absent |
| 5 | **restart not confirmable** | **the organisation's database becomes unroutable** (platform status, fail closed) until an operator confirms the server state; incident report |

- **`contained` means "no new admission"; it never means "terminated".**
- The operation stays `termination-unconfirmed` with **"access may have occurred"** unless step 1, 3 or 4 confirms termination. In that case it records `aborted-at-deadline`, and "access may have occurred" stays true for the time it ran.
- **Incident reports:**
  - the organisation always receives one;
  - at step 4, **every organisation on the same server** receives one (D-192).
- **[TU]:** whether the session list is authoritative, and the restart semantics, on the chosen database product. These are proof obligation F-3.

### R11-4. D-188 report record, completed (resolves R6 F-06; extends R10-6)

**Added per-operation fields:**

| Field | Values |
|---|---|
| **`grantId`** | the suspended grant |
| **`failedDesignationRef`** | (Resolution, part, designated `PersonRef`, outcome `NotAuthorised` / `Undetermined`) |
| **`suspensionRef`** | the grant's suspension event (version, time) |
| **`outcome`** (distinct from lifecycle state) | `completed` · `failed` · `aborted-at-deadline` · `never-executed` · `unknown` |

- **Lifecycle state stays as in R10-6.**
- **For an operation still `registered`:**
  - `admittedAt` and deadline are `null`;
  - outcome is `never-executed`;
  - "access may have occurred" is false.

### R11-5. Admission versus abandonment: arbitration in the organisation database (resolves R6 F-07; completes R10-7)

- **Admission window:** a registry row may be admitted until `registeredAt + 2 min` on the central clock (platform setting; default 2 min).
  - **The gateway refuses to start an admission attempt once the window has passed**, checked with the R11-2 bracket.
- **Arbitration (no cross-database transaction):** both outcomes claim the same unique key, `operation_id`, in the organisation's admission table.
  - **admission** inserts an `admitted` row;
  - **abandonment** (reconciliation, only after the window) inserts an `abandoned` tombstone row.
  - **Whichever commits first wins; the other fails on the unique key.**
- **Afterwards, the central registry adopts the organisation's result:**
  - the admission row exists → `admitted`, with its stored deadline;
  - the tombstone exists → `abandoned`; the same id is refused from then on.
- **A late admission commit after the window but before the tombstone** is a valid admission, adopted centrally. **A late one after the tombstone fails.**
- **No state is contradictory:** the organisation row is the single arbiter, and the central row only mirrors it.

### R11-6. Proof register

- **Re-stamped for revision 11.**
- **E-8 is now blocked on X-1 and X-3.**
- **Section F added (F-1 … F-6).** No obligation removed; all **NOT EXECUTED**.

### R11-7. External dependencies (R10-9, restated)

**X-1 now has two parts:**
- **X-1a:** the corrected draft note (R11-1), ratified with the acceptance.
- **X-1b:** this ratification **does not** close per-part persistence; that stays under X-3.

**X-2 and X-3 are unchanged.** While any of them is open, contact promotion and normal-route grant checks fail closed.

**Verdict (author's):** the R6 Platform design defects are corrected. **Remaining:**
- **[b]** proofs (all NOT EXECUTED);
- **[EXT]** X-1 … X-3.

**Not accepted.**

---

**Status note (2026-11-11, D-193):**
- **Revision 11 is ACCEPTED WITH CONDITIONS** by the Principal (decision record §11.92).
- **Accepted text:** this file as it stood at SHA-256 `e3772d2e8e266d909a1328cb2205b1d9271e47c0890caf5be8984d5724d4e005`. **This status note is the only later change.**
- **Basis:** independent review IR-P-02-R7 (GPT-6, READY FOR PRINCIPAL CONSIDERATION), after IR-P-01 and IR-P-02 … R6.
- **Conditions:**
  - X-2 and X-3 (fail closed until accepted);
  - the 53 proof obligations in the register (`a3fe00f2…`), all NOT EXECUTED, are build gates.
- **X-1a is ratified** by the Governance thin-slice addition made in the same act.
- **No implementation authorised.** Whether the emergency route ships in release 1 is a separate later decision.

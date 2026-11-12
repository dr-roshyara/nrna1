# Integrated baseline: coherence review and completion (revises the integrated release-1 design)

| | |
|---|---|
| **Status** | **PROPOSED.** Records no ruling except by citing D-164 (decided). Written by Claude, **not independent** |
| **Commission** | "Integrated Baseline Coherence Review and Completion" and the physical-tenancy note, accepted by the Principal 2026-11-11 |
| **Revises** | `2026-11-11-integrated-release-1-domain-design.md` (§2, §3, §5 flows A and D–E, §6 rows 2–5); that document stays as history |
| **New decision applied** | **D-164:** one central platform database + one database per organisation (decision record §11.69) |
| **Sources** | D-75, D-101, D-104, D-107, D-110, D-111 (precisions), D-113, D-151 … D-154, D-161, D-163, D-164; ratified Membership §1, §4, final §3, §18; CR-01, CR-02; AuthorityCheck review + thin-slice notes 1–2; Membership revision 3; `docs/publicdigit/brainstorming/multi-database-system/01` (read only, not authoritative) |

**Labels:** [RAT] · [AD] · [BF] · [AC] · [TD] · [TU] · [WD] · [P] · [H] · [OQ] · [LQ] · UNKNOWN.

## 0. Disagreements (first)

1. **Issue A (D-161 v D-163) is settled by D-161's own text.**
   - D-161 reads: "**only members in good standing (in force and not suspended)** may write in forums and receive newsletters" [AD].
   - **The parenthesis is the Principal's definition.** `InForceUnrestricted` names exactly that.
   - **Payment was never part of it**, so D-163's exclusion of payment removes nothing.
   - **The ratified `Standing` (dues, E0b-S) is a different concept and is not used.**
   - **No open question; nothing redefined.**
2. **Issue B (identical eligibility for forum posting and newsletters) is also decided by D-161.** One rule names both capabilities; their difference is D-151 (opt-out applies to newsletters only).
3. **The brainstorming analysis puts "grants, roles" in the central database.** That conflicts with **D-111 / D-104** (workspace-access decisions are the organisation's data). **Under D-164 they belong in the organisation's database**; central keeps only the account–organisation association. That analysis is not authoritative.

## 1. The baseline in plain language (after D-164)

- **PublicDigit keeps one small central record:**
  - who has registered and how they log in;
  - which organisations exist;
  - which organisations each account is connected to, so the platform knows where to look;
  - shared reference information such as the official map.
- **Everything about a person's life in an organisation lives in that organisation's own database:**
  - their profile and contacts, their unit, their membership;
  - offices held, posts written, newsletters received;
  - (later) elections and finance.
- **Being connected to an organisation grants nothing by itself.** Membership, office and access are each decided inside the organisation.

## 2. Resolution of the reviewer's issues

### A. Good standing: settled (see §0)

`InForceUnrestricted(t) = t ∈ inForce ∧ t ∉ suspended`, from CR-02's ratified fields = D-161's definition.

### B. Who decides access (correction)

- **Correction to the integrated design §3:** Organisation does **not** derive `InForceUnrestricted`. **It forwards Membership's intervals unchanged and attributed to Membership** (Published Language passed through, as §36 requires), together with its own belonging facts.
- **The consuming context makes the final access decision:**
  - **Deliberation:** read = belonging (D-152); post = belonging ∧ `InForceUnrestricted` (D-161) ∧ forum open (D-150).
  - **Communication:** audience = belonging in scope ∧ `InForceUnrestricted` (D-161) ∧ not opted out of the series (D-151).

| Layer | Owner |
|---|---|
| Membership facts (`coverage`, `inForce`, `suspended`) | Membership |
| Belonging (unit and ancestors) | Organisation |
| Eligibility rule for an action | the Principal's decisions (D-152, D-153, D-161) |
| **Final access decision** | **the consuming context** (Deliberation, Communication) |

### C. AuthorityCheck: structure supplied by Organisation; admission and placement

**Ancestors:**
- **They are not caller input in the user sense.** Organisation's **application service** reads them from **its own Tree** (the authoritative owner, §49) **as at (d, τ)** and attaches its **tree version** and τ.
- **Governance records them in the basis** (`structureRef = tree version, τ`) and evaluates competence over them.
- **Organisation applies the act only if the tree version is unchanged**; **otherwise it re-asks** (optimistic check, §49.7 step 6).
- **A user request never supplies structure.**

**Admission and placement:**
- **D-154 (2)** gives the admitting body, when the rules are silent, **the competence to name the unit in the admission act**.
- **That competence is bounded by the admitting body's own scope.** So the **one request carries two act kinds**: `admit` and `place-member(unit)`. Governance returns **one answer with an outcome per act kind**.
- **Examples:**
  - **a district committee admitting into another district → `place-member: NotAuthorised`**;
  - **if the rules name a different body for placement → that body's competence is checked**, and the admission may stand while placement waits (top-unit default, D-114).
- **This corrects AuthorityCheck review G-6** ("one answer reused").

**Conflicts:**
- rules versus decision → D-70 (effective until challenged, flagged);
- office holder versus organ competence → D-65 / D-69 per §20.3.

**Missing evidence → `Undetermined`**: refused, never permission.

### D. Identity, account association and access

| Step | Owner | Where (D-164) | Grants |
|---|---|---|---|
| Register; one Account; verify login address | Identity | central | nothing in any organisation |
| Authenticate | Identity | central | a session |
| Organisation issues a **claim code** for its register entry | **Organisation** | organisation database | — |
| Person redeems it; Identity **binds** the Account to that organisation's `PersonRef` (D-113; binding ≠ merge) and records the **association** | **Identity** | central | **routing only** (D-164) |
| Member area: own status, forums, newsletter settings | **Organisation** belonging (participant access, K-2) | organisation database | per §2B |
| Workspace (registrar, editor, admin screens) | **Organisation** workspace grant (D-104) | organisation database | screens only; **never authority** |
| Any authority-sensitive act | **Governance** AuthorityCheck | organisation database | the act, if Authorised |

**No matching by name or e-mail** (D-113). The existing defect PBDIGIT-341 is a release blocker.

### E. Temporal consistency: worked examples

**Three views:**
- **(1)** what was effective in the business world;
- **(2)** what PublicDigit knew at a time τ₀;
- **(3)** what it knows now.

**Consumers always state (d, τ).**

| # | Case | (1) Effective | (2) Known at τ₀ before recording | (3) Known now | Consumer may conclude |
|---|---|---|---|---|---|
| 1 | Renewal recorded today, effective 1 Mar | in force from 1 Mar | lapsed from 1 Jan (no renewal known) | in force from 1 Mar | **a newsletter sent 5 Mar (τ = 5 Mar) correctly excluded the person; nothing is resent automatically** |
| 2 | Late renewal 15 Feb, gap 1 Jan – 14 Feb | lapsed 1 Jan – 14 Feb | same | same | could not post in the gap; can from 15 Feb |
| 3 | Back-dated renewal to 1 Jan, recorded 20 Feb | in force throughout | before 20 Feb: lapsed in Jan–Feb | in force | **audit shows both**; past refusals stand as correct for their τ |
| 4 | Suspension 1 Mar – 1 Jun while in force | suspended | from its record time | same | read yes, post no, newsletters no |
| 5 | **Term expires 31 Mar during that suspension** | lapsed **and** suspended from 1 Apr | — | same | read yes (covered); Restore refused (not in force); the restriction **still ends by itself on 1 Jun** |
| 6 | Renewal while suspended (from 1 Apr) | in force + suspended | — | — | still no posting until 1 Jun or a Restore |
| 7 | Restore after that renewal, 15 Apr | in force, unrestricted from 15 Apr | — | — | posting from 15 Apr |
| 8 | "Could X read the district forum on 1 Feb?" | belonging + coverage at d = 1 Feb | answer with τ = then (what the forum knew) **or** τ = now (corrected truth) | — | **the question must name τ**; access logs use the τ of the request |
| 9 | Newsletter audience | — | one τ = send time for authority, belonging and intervals | — | the stored snapshot explains every inclusion and exclusion |
| 10 | End annulled later | not ended | ended (until the annulment's record time) | not ended | decisions taken while it was known as ended stand; **recomputation only for later questions** |

**Expiry versus Restore:**
- **a time-bounded suspension ends by its own interval** (`until`). That is **not an act**: no fact, and no C1 (the intervals were already published);
- **Restore is an act** (fact, authority, evidence, C1). **They are never interchangeable** [RAT final §3, §7].

**Renewal refused while lapsed and suspended:** the person stays lapsed; the suspension runs to `until` (or open-ended until a renewal makes a Restore possible).

**τ is sufficient** for every operation above, given:
- **each consumer stores the τ it used** (snapshot, access decision, authority answer);
- **historical questions name τ explicitly.**

### F. Ownership and process inputs (checked fact by fact)

| Fact or decision | Owner | Database (D-164) | Inputs it needs |
|---|---|---|---|
| Account, login, association | Identity | central | claim (from Organisation's code) |
| Organisation registry | Platform / Organisation registry | central | the founding act (D-148) |
| Profile and contacts | Organisation | organisation | entries by the organisation |
| Units, belonging, placement | Organisation | organisation | decisions (Governance), coverage (Membership) |
| Workspace grants | Organisation | organisation | its rules |
| Membership facts | Membership | organisation | authority (Governance) |
| Committees, offices, decisions, authority | Governance | organisation | structure (Organisation), C3 (Membership) |
| Forum access decision; posts; moderation acts | Deliberation | organisation | belonging + intervals (via Organisation); moderator authority (Governance) |
| Newsletter audience decision; series; snapshots; opt-outs | Communication | organisation | belonging + intervals + contacts (Organisation); editor authority (Governance) |
| Formal notices (later) | Communication | organisation | the notice owner's audience |
| Official map | Reference Geography | **central (shared reference, D-31)** | — |
| Organisation areas (D-82) | Organisation | organisation | — |

**Result:** **every fact and decision has exactly one owner.** For release 1 this is demonstrated by the table, not asserted.

## 3. Consequences of D-164 for each context

| Context | Consequence |
|---|---|
| **Identity** | central; issues `PersonRef` per organisation. **Crossing into an organisation database is two-step and idempotent** (issue in central, store in the organisation); no cross-database transaction |
| **Organisation, Membership, Governance, Deliberation, Communication** | all in the organisation's database, so **their in-process contracts stay inside one database**: the grouped renewal (one transaction), the ledger clock (now naturally per database) and AuthorityCheck need no distributed transaction |
| **Membership** | `closedThrough` per organisation is now per database: **simpler, same guarantee** |
| **Outbox / inbox, jobs** | per organisation database; **every background job carries explicit organisation context** (fail closed if absent) [TU] |
| **Reference Geography** | **central shared reference**, read by organisations **at a position**. **One τ also bounds Geography's `closedThroughTime`.** Dormant in release 1 |
| **Election, Finance** | organisation database (later releases) |
| **Legacy code** | today one shared database with `organisation_id` scoping (brainstorming 01, [BF]); **migration path = the existing cutover machinery plus per-organisation databases** (D-160: new organisations start on the target model) |

## 4. Corrected flows (deltas only; the rest of the integrated design §5 stands)

- **A (admission):**
  - step 3 asks **`admit` + `place-member(unit)`** in one request, with outcomes per act;
  - step 5 records placement only if `place-member` is Authorised, else the top unit (D-114);
  - **step 7 = the claim redeemed at Identity (central), association = routing only.**
- **D (forum):** **Deliberation decides** read and post from belonging and the forwarded intervals; Organisation does not decide.
- **E (newsletter):** **Communication decides** the audience; Organisation supplies belonging, intervals and contacts at the send's τ.
- **C (suspension):** add rows 5–7 of §2E (term expiring during a suspension; renewal; restore).

## 5. Contract catalogue (corrections)

| Row (integrated design §6) | Correction |
|---|---|
| 2 Organisation → Deliberation | returns **belonging (unit, ancestors) + Membership's intervals as received (attributed)**; **Deliberation decides** |
| 3 Organisation → Communication | same, plus contacts; **Communication decides** |
| 4 Governance ⇄ Organisation | ancestors from Organisation's own Tree with tree version and τ; **re-ask on version change** |
| 5 Governance → Membership | **multi-act requests** (`admit` + `place-member`) with per-act outcomes |
| new 8 | **Identity → Organisation:** `PersonRef` issue and binding; **association = routing only** (D-164) |

## 6. Tactical design status

| Area | Status |
|---|---|
| Membership revision 3 | grouped renewal now sits inside one organisation database by D-164; **the argument still needs tests and an independent check against the ratified formal proofs** |
| AuthorityCheck | corrected as §2C; a new correction note goes in the thin slice |
| One database per organisation | **routing, connection resolution, migrations per database, and background-job context** need a **tactical design** of their own (next step) |

## 7. Principal decisions

**None open.**
- D-164 was decided in this round.
- Issue A is settled by D-161's text.
- **[LQ]** newsletter legal basis (unchanged).
- **UNKNOWN** per organisation: statute content.

## 8. Technical proof obligations

**Carried forward:**
- ledger clock;
- grouped transaction;
- tree invariants;
- exclusion constraints;
- outbox idempotency.

**New:**
- **fail-closed organisation resolution** for every request and job;
- **no query crosses organisation databases**;
- **the central association is never read as authority** (architecture test).

## 9. Verdict

**BASELINE STRATEGICALLY COHERENT FOR RELEASE 1, WITH SPECIFIC OPEN QUESTIONS** (the [LQ], per-organisation UNKNOWNs, deferred processes B-5 and B-6).

**Tactically:**
- **the Membership revision 3 independent check** is outstanding;
- **the tenancy tactical design (D-164)** is the next design step.

**Not independent.** An independent reviewer should check:
- §2C (two-act authority request);
- §2E (temporal table);
- §3 (D-164 consequences).

---

## Addendum M (2026-11-11): the multi-database architecture through the baseline (D-164)

*Commission: "Principal Architecture Decision — Organisation-Level Multi-Database Architecture". Treated as binding (D-164). Self-review, not independent.*

### M-0. Clarifications (first)

- **The commission's central list** (registration, login, association mapping) **is read as not exhaustive.**
- **D-164 as decided also places two items centrally, for evidence-based reasons:**
  - **the organisation registry:** without it no request can find an organisation's database;
  - **shared reference data, i.e. the official map:** Reference Geography is ratified as **one platform-level reference** (D-31, D-93); a copy per organisation would contradict it.
- **Neither is organisational business data. No contradiction.**
- **The brainstorming analysis's "read-only identity projection" in each organisation database is not adopted** [AC]. It would copy account data (e.g. login e-mail) into every organisation, against **D-111** (bare central identity, organisation-local profiles) and **D-101**. **An organisation database holds `PersonRef`s, never account data.** Legacy tables with foreign keys to `users` are a migration concern, not a target design.

### M-1. Central association: what it means and what it does not

| It means | It never means |
|---|---|
| "this Account has claimed a record in organisation O" (D-113 binding), so **requests may be routed to O's database** | membership, voter status, office, workspace access, any right or authority |
| the Account can select O on the organisation picker | that O's data may be read outside O's database |

**It is created only by redeeming the organisation's claim code** (D-113). **Admission, registration or e-mail matching never create it.**

### M-2. Person identity

| Identity (central) | Organisation (its database) |
|---|---|
| Person (bare `PersonId`), Account, login address, sessions | the organisation's record of the person: profile, contacts, capacities, belonging |
| issues **a different opaque `PersonRef` per organisation** (D-75) | stores only its own `PersonRef`; **cannot derive the person's refs in other organisations** |

**Consequence:** the same human has **no join key across organisation databases.** Isolation is structural, not only procedural.

### M-3. Cross-organisation isolation (why a shared account cannot reach another organisation's data)

1. **Resolution:** each request or job names **one** organisation (host, path or explicit job context) → the central registry → **that organisation's connection only**. **Unresolved → refused** (fail closed).
2. **Association check:** the account must be associated with that organisation. **This is necessary, not sufficient**: then belonging, workspace grant or AuthorityCheck decide inside the organisation.
3. **No connection to a second organisation database exists in that request or job**, so no query can reach it.
4. **No shared key** (M-2).
5. **Proof [TU]:**
   - an integration test with two organisation databases and one account associated with both (each sees only its own);
   - architecture tests (no code path opens two organisation connections; the central association is never read as authority).

### M-4. Access layers (one per question)

| Question | Owner | Where |
|---|---|---|
| Who are you? | Identity | central |
| Where may your requests go? | Identity (association) | central |
| May you see the member area? | Organisation belonging (participant access) | organisation |
| May you open staff screens? | Organisation workspace grant (D-104) | organisation |
| May you do this act? | Governance AuthorityCheck; consumer rules (D-161) | organisation |

### M-5. Cross-context communication

- **Inside one organisation:** Organisation, Membership, Governance, Deliberation and Communication **share the organisation's database but not their facts.**
  - Each owns its tables; they talk through Published Language queries and the organisation's outbox.
  - **Co-location is storage, not a merged domain** [AC]; architecture tests forbid cross-context table access.
- **Central ↔ organisation:** **never one transaction.** Every crossing is a **two-step, idempotent flow with an explicit intermediate state:**

| Flow | Steps |
|---|---|
| **Organisation creation** | registry entry `provisioning` → database created and migrated → founding records (D-148) → registry `active`. **Failure leaves `provisioning`; retry resumes** |
| **Claim** | the organisation issues a code (organisation database) → the person redeems it; Identity verifies with the organisation and **binds** (central) → the organisation records "claimed" (idempotent on the code) |
| **`PersonRef` issue** | Identity issues (central, idempotent per organisation and register entry) → the organisation stores it |
| **Geography read** (later) | read-only at a position; no write crosses |

### M-6. Data lifecycle

| Stage | Architecture | Release 1 |
|---|---|---|
| Creation and provisioning | M-5 flow; **new organisations start on the per-organisation database and the membership ledger** (D-160, D-164) | **essential** |
| Schema changes | **every organisation database migrated by one controlled run; an organisation whose migration fails is marked unavailable (fail closed), never half-served** | **essential** |
| Backup and restore | **per database**; restore of one organisation does not touch others. **The central database is backed up separately**; after a central restore, associations are reconciled against organisations' "claimed" records | **essential** (backups); restore drills later |
| Suspension of an organisation (platform side, e.g. service ended) | the registry status blocks routing; **data untouched** | later |
| Closure, retention, export | **the data belongs to the organisation** (D-111): export to it, then deletion after a retention period; **[LQ]** retention law; links to dissolution (B-5) | **later** (deferred with B-5) |
| Moving today's shared-database organisations | the existing cutover machinery (gate C-1 … C-10) extended to "copy into its own database" | later (D-160) |

### M-7. Failure behaviour

| Unavailable | Effect | Design |
|---|---|---|
| **One organisation database** | login works; the organisation picker shows it "temporarily unavailable"; **other organisations unaffected** | fail closed for that organisation only; its jobs pause |
| **Central database** | **no login, no routing, no claims**; organisation data intact | **accepted single point of failure for release 1** [AC]; organisation-only jobs not needing identity (e.g. outbox delivery to stored contacts) may continue **only if they carry their own organisation context and need no central read** [TU] |
| **Reference Geography** (later) | queries needing the map are refused (D-73) | dormant in release 1 |

### M-8. Changes to the baseline (consolidated)

1. **Context map:**
   - Identity, the organisation registry and Reference Geography are central;
   - all organisational contexts are per organisation;
   - the only central ↔ organisation crossings are those in M-5.
2. **Ownership matrix (§2F):** the database column is confirmed. Additions: **workspace grants in the organisation database** (corrects the brainstorming placement); **no account data in organisation databases.**
3. **Contracts:**
   - new: Identity → Organisation **`PersonRef` issue and claim binding** (two-step);
   - every in-organisation contract unchanged.
4. **Flows:** A (admission) ends with the claim (M-5); F (structure) starts with provisioning (M-6).
5. **Access model:** M-4.
6. **Tactical:**
   - the ledger clock, grouped renewals and AuthorityCheck stay inside one organisation database: **no distributed transaction anywhere**;
   - outbox and jobs per organisation with explicit context.

### M-9. Contradictions with accepted decisions

**None.**
- **D-110** (one registration, many organisations) and **D-111** (data ownership; physical tenancy left open) are **unchanged**; D-164 closes the open part.
- **D-31 / D-93** (Geography platform-level) are respected by keeping Geography central.
- **No earlier decision is claimed to have settled physical tenancy.**

### M-10. Principal decisions

**None now.** **Deferred, to be asked when closure is designed (not release 1):** what an organisation receives on closure, and the retention period (with **[LQ]**).

### M-11. Technical proof obligations (architect's responsibility)

1. **Fail-closed resolution** for every request, job, scheduled task and outbox relay.
2. **Two-database isolation tests** (M-3).
3. **Idempotent two-step flows** (M-5) with crash-in-the-middle tests.
4. **Per-database migration runner** with failure isolation.
5. **Per-database backup and restore** of a single organisation, verified.
6. **No central ↔ organisation transaction** (architecture test).
7. **Solo-developer operations:**
   - one PostgreSQL server, one database per organisation, one central database;
   - **one command each** for provisioning, migrating all organisations and backing up all organisations.

   Infrastructure beyond that is not needed for release 1 [AC].

### M-12. Verdict

**The multi-database requirement is consistently reflected in the baseline. It introduces no contradiction, and no distributed transaction is needed.**

**Release-1 essentials:**
- provisioning;
- fail-closed resolution;
- per-database migrations and backups;
- job context.

**Later:**
- closure and retention;
- platform suspension;
- moving existing organisations.

**Next design step:** the tenancy **tactical** design (resolution, provisioning states, migration runner, job context) to the depth needed for the first build increment.

**Supersession note (2026-11-11, IR-R1-01 L-15):** Addendum M §M-7's statement that some organisation-only jobs may continue while the central database is down is **superseded** by the multi-database tactical design §6 and §13.6 (one fail-closed rule).

**Note (2026-11-11, D-172):** an admission that names a unit is placed there **only if `place-member` is Authorised** (from the rules or a designation that includes placement). **D-154(2) no longer gives the admitting body placement competence by default.** Otherwise the member stays in the top unit (D-114, platform default) until a designated body places them.

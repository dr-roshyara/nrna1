# IR-OPR-01 — Independent Architecture Review: Organisation People Register (revision 3)

| | |
|---|---|
| **Object of review** | `docs/publicdigit/architecture/reviews/2026-11-10-organisation-people-register-strategic-and-tactical-design.md`, revision 3, SHA-256 `c52069ff8e7048a1d79ee242f01a33189adac41c0e5da29d11e8f7181840325e` |
| **Reviewer** | A DeepSeek-family model, designation **`deepseek-chat`**, operating inside the Claude Code command-line harness (an Anthropic product). **Not a Claude model.** Disclosures in §13 |
| **Integrity** | 13/13 bundle files verified by me with `sha256sum -c MANIFEST.sha256`; primary-subject fingerprint matches `c52069ff8e7048a1` |
| **Authority basis** | Commission `2026-11-10-organisation-people-register-independent-review-commission.md` §2 (each decision's status read from that table, not from the design's "WD" label) |
| **Status of this report** | Review only. It decides nothing for the Principal and authorises nothing |

**Where each question A–I is answered**

| Q | Subject | Section |
|---|---|---|
| A | Strategic coherence, ten ownership concerns | §3 |
| B | Aggregate boundaries and the non-overlap invariant | §4 |
| C | Archival | §5.1 |
| D | Import | §5.2 |
| E | Duplicate Accounts and duplicate records | §5.3 |
| F | BR-10 / P-I1, four options | §7 |
| G | Cross-context contracts | §6 |
| H | Scenarios that expose missing rules | §9.2 |
| I | O-7, voter relationship scope | §8 |

---

## 1. Executive verdict

**Conditionally sound.**

The **strategic** separation is coherent, well-sourced and, in my judgement, correct as far as the record allows me to test it. The ten ownership concerns (§3) are each held by one context, the two most dangerous conflations — "Member" asserted by an affiliation, and a voter relationship implying entitlement — are explicitly refused, and the design is unusually honest about its open seams (P-I1, O-7, XD-PROV).

The **tactical** design is not yet sound, for four reasons that are internal to the document and do not depend on any open question:

1. **A claimed invariant cannot be enforced where the design puts it.** Non-overlap of periods "per (person, capacity)" is listed as a rule enforced *inside* the `Affiliation` aggregate, while the aggregate is defined as *one occurrence*. No instance can see the other occurrences (finding F-1, §4).
2. **The import operation straddles two aggregates** while the same document states "one aggregate per transaction", and it defines no business outcome for a row whose person was recorded but whose voter relationship was not (F-5, §5.2).
3. **The archival guarantee is asserted, not demonstrated.** "A race is closed by the archive handler" is a race ordering, not a guarantee; under failure or retry an archived person can keep an active affiliation or workspace access. The cascade also records ends with no basis recognised by the design's own `Basis` value object, contradicting invariant I-14 (F-2, F-3, §5.1).
4. **One invariant overclaims uniqueness.** I-13, "one Account per registered human", is classed **RAT**; the ratified source says *zero or one Account per **Person***. Duplicate Accounts are expected until an explicit Identity merge — exactly as D-110 states in its own caveat (F-6, §5.3).

Two further states that will certainly arise have no rule: two of an organisation's records sharing one `PersonRef` after a merge notice (F-7), and the act that places a person in a *specific election's* electorate, which is neither named nor owned (F-11, §8).

**Conditions I would attach before revision 3 is accepted as a design basis** (the Principal decides whether to adopt them):

- Resolve F-1, F-2/F-3, F-5, F-6/F-7 by amending the design (§11).
- Obtain the Principal decisions in §10 — in particular P-I1/BR-10 (§7), the meaning of "archival" (§5.1), and O-7 (§8).
- Correct I-13 and cite D-73's knowledge-time contract for every read (§6, F-9).

None of these undermines the direction. They are seams at the tactical boundary that the design itself has left open, and that the next revision can close without reopening ratified architecture.

**What the design gets right** (stated so the conditions above are not read as a rejection): the exclusion of "User" and "Member" as recordable capacities; "voter never creates entitlement"; "no account lookup at import"; no cross-organisation finders; the three-way split of the duplicate problem (8.5); the refusal to let an Account create an affiliation; and the §13 test of D-111 against the other contexts, which surfaces the P-I1 tension rather than hiding it.

---

## 2. The design in simple business language

Every organisation on PublicDigit keeps a **private address book of the people it knows** — members, workers, guests, imported voters, applicants, and people who have never used PublicDigit at all. For each person the organisation keeps **its own version of the details** (name, phone, address as the organisation wrote them) and **its own notes of the relationships** it has with that person: this person works for us from January, this one is our guest, this one is on our voters' list. Separately, the organisation decides **who may enter its digital workspace**.

PublicDigit itself holds **one login per human** and knows, invisibly, that the person in Organisation A's address book and the person in Organisation B's address book may be the same human — but it tells neither organisation anything about the other, and it never decides sameness by matching an e-mail address or a name.

The address book **never decides** who is a formal member (Membership does), who may vote (Election does), who holds office (Governance does), or who may use the workspace (the organisation's own rules decide, and only the grant says so).

Tactically, the proposal keeps **three separate notebooks**: one for the person and their details, one per relationship, and one for the workspace key. The reason given is that different officers decide these things at different times and on different evidence, and ending one must not disturb the others. The reviewer's concern (§4, §5) is that "one notebook per relationship" removes the only place where the rule "no two overlapping periods for the same person and capacity" could be kept, and the only place where "an archived person has no live relationships" could be made true at once.

---

## 3. Strategic bounded-context assessment (question A)

### 3.1 The ten concerns

| # | Concern | Owner in the design | Owner in the sources | Verdict |
|---|---|---|---|---|
| 1 | Human identity (canonical Person) | Identity | Identity (D-75 §2.1) | **Correct** |
| 2 | Account | Identity | Identity (D-75 §2.6; D-102) | **Correct** |
| 3 | Organisation-local person record | Organisation | Organisation (D-101) | **Correct** |
| 4 | Profile (name, contact, address as supplied) | Organisation, inside `OrganisationPerson` | Organisation (D-101, settles address) | **Correct** |
| 5 | Affiliation | Organisation, `Affiliation` aggregate | Organisation (D-101, D-109) | **Correct**, but see §4 on the aggregate split |
| 6 | Membership | Membership | Membership (D-96 RAT) | **Correct**; the "reflection" rule (D-103, I-6/I-7) is right |
| 7 | Unit belonging | Organisation, a separate capability, excluded from this design | Organisation (D-106 conclusion 2) | **Correctly excluded**; the seam is unstated but harmless |
| 8 | Voter-list inclusion | *Split*: Organisation records the "voter" relationship; Election decides electorate and eligibility (D-109) | D-109 + ratified Election boundary | **Owner of the relationship is right; the act of inclusion in a specific election is unowned** (F-11) |
| 9 | Workspace access | Organisation owns; Authorization enforces | D-104; baseline E-12 | **Correct**; the contract to Authorization is unsourced (F-10) |
| 10 | Governance authority | Governance; Organisation queries `AuthorityCheckPort` | RA §8 (RAT); Decision A | **Correct** |

**No duplicated ownership was found among the ten.** That is a real strength and uncommon in documents of this kind.

### 3.2 Boundary checks the commission asked for

- **Identity.** Clean. `PersonRef` is an opaque per-organisation identifier, not proof of identity, not a cross-organisation key, not readable across organisations (§4.2; I-1, I-12). This matches XD-3 §2 items 2–4. The one soft point: the design has the *organisation* "find the record by `PersonRef`" at sign-in; that is a within-organisation lookup and is consistent with I-1.
- **Organisation.** Owns three of the ten facts (person record, profile, affiliation) plus workspace access — four. That is defensible because they are different facts with different evidence (D-101, D-104), but it makes aggregate shape the load-bearing technical question, which is where §4 finds the problems.
- **Membership.** The design consumes coverage via CR-01 and never interprets it (I-7). Correct. However, the design's "Member" view is **complete only for records that hold an assigned `PersonRef`**. Since the design allows the slot to be empty (P-I1), every imported member without a `PersonRef` is invisible to C2 (`personRefs[]`) and therefore to the Member view. This is not a contradiction — it is a consequence the design does not state, and it becomes material under options (2) and (4) of §7.
- **Election.** Entitlement is never inferred (I-11); Election consumes the organisation's relationship (D-109). Correct at the strategic level. The consumption *mechanism* is unsourced (§6).
- **Governance.** Only competence is consumed; no register fact becomes authority (I-10). Correct.
- **Authorization.** Owns no business truth (D-104; baseline E-12); the design grants and revokes. Correct. The contract does not exist in any record (§6).
- **Finance.** Appears only in §13 and is correctly left open (person reference open; tenancy open). No defect.

### 3.3 Missing concepts

Three concepts are absent from the register model, at least one of which is required by a separate record:

1. **Provenance.** XD-PROV §2 requires four distinct facts: provenance ("imported by us on date D for purpose P", organisation-local), Identity claim, affiliation lifecycle, deletion eligibility. The design has the last two, treats the claim only as an incoming notice, and reduces provenance to an audit column ("batch id per row", §7). XD-PROV's rule that provenance is *not changed* by a later registration or claim is not stated anywhere in the design (F-12).
2. **Claim issuance, as an organisation-side state.** The design has a consumer for the claim outcome (`HandleIdentityClaimNotification`) and a scenario in which a person "claims the organisation's record with a code" (8.10), but no concept of *the organisation having issued a code to this person and not yet been answered*. Operations cannot answer "whom have we invited, and who has not claimed yet?" — a question the voter-import work depends on (F-15).
3. **The election-inclusion act.** See item 8 above and §8.

### 3.4 One challenge to D-111 (permitted by the commission)

D-111 says each organisation's business data "belongs to" the organisation. The design uses this architecturally (logical isolation, I-15), which is coherent. But "belongs to" is also a legal term — controllership. D-111's own precision (2) leaves physical tenancy open, and OQ-3a-1 (legal basis, controllers, processors) is open. The design should not let architectural ownership be read as a legal conclusion; it does not say so, but it does not warn either. Impact: low for the design, material for the data-protection review. **Inference/observation; no Principal decision needed in this review.**

---

## 4. Aggregate-boundary assessment (question B)

### 4.1 What the split does and does not justify

The design separates `OrganisationPerson`, `Affiliation` (one aggregate per occurrence) and `WorkspaceAccess` on grounds of **independent authority, evidence and lifecycle**. I accept the reasoning for the split *between the three aggregates*, and the revision-2 justification for moving affiliations out of `OrganisationPerson` is sound: a registrar correcting an address and an import adding a voter relationship are genuinely concurrent, and no business invariant requires the profile and a relationship to change atomically (§9.1, "Why not one aggregate").

**But the design conflates two different decisions:**

- (a) *Affiliation should not live inside the person aggregate* — justified by the concurrency argument.
- (b) *Each occurrence of a relationship should be its own aggregate* — **not justified by that argument.** Two occurrences of the same capacity are decisions by the same kind of authority, on the same kind of evidence, about the same person, and the design itself says they must not overlap. By the design's own criteria (shared authority, shared evidence, shared invariant), occurrences of one capacity belong together.

This is not a matter of taste. The design's own table (§9.1) assigns this rule to the Affiliation aggregate: **"non-overlap of periods per (person, capacity)"** as a rule **"enforced inside"**. A per-occurrence aggregate holds one period. It cannot enforce a relation between itself and a sibling it cannot see. The commission's example (worker A January–June; worker B April–September) therefore produces, by construction, two active periods that no aggregate rejects.

### 4.2 The ratified precedent the design does not address

The ratified Membership architecture faced the same shape of problem — periods of one relationship must be pairwise disjoint (INV-9) — and chose **one aggregate per (Person, Organisation)** containing all periods, with the stated reason: *"invariants: INV-EP, INV-9 and CFC+ read several periods"* (RA §7). It also records that concurrent commands can each pass the guard and together break the invariant, and that "a database constraint cannot replace this" because the guard depends on current state (RA §6, "Concurrency").

The people register's non-overlap is the same kind of rule. The design adopts the opposite aggregate shape without engaging with the ratified reasoning it mirrors. It is entitled to a different answer — the facts differ — but it must give the reason, and it has not.

### 4.3 Cross-aggregate invariants: claimed versus demonstrated

| # | Invariant claimed | Where | Demonstrated? |
|---|---|---|---|
| 1 | No two periods overlap for one (person, capacity) | §9.1 | **No** — claimed *inside* a per-occurrence aggregate (F-1) |
| 2 | An archived person has no active affiliation or access | §9.1 | **No** — eventual cascade, race ordering only (F-2, F-3) |
| 3 | No affiliation may start on an archived person | §7, §9.1 | **No** — check-then-act against another aggregate; the archive can commit in between |
| 4 | At most one active workspace grant per record | §6.1, §9.1 | **Yes** — the aggregate is one per person (`WorkspaceAccessRepository.get(person)`), so this is internal |
| 5 | The `PersonRef` slot changes only on an Identity notice | §9.1 | **Yes** as to the record; **no** as to *which* record the notice targets when the organisation holds duplicates (F-7) |
| 6 | The organisation may merge its own records only if they do not hold two different assigned `PersonRef`s | 8.5(b) | **No** — reads two aggregates; no operation, authority or basis is defined (F-8) |
| 7 | Every affiliation and access act records basis and actor (I-14) | §6.2, §9.2 | **No** for cascaded ends — `Basis` has no value for "person archived" (F-2) |
| 8 | One `PersonRef` per organisation identifies one person record | implied by RA / CR-01 C2 | **No** — after a merge notice two records may share it (F-7) |

### 4.4 The consistency requirement and the architectural alternatives (no mechanisms, as required)

**Requirement (business).** For one organisation, one person and one capacity, at most one period is active at any instant; and — the design should say whether this is also required — successive periods of the same capacity must not leave a gap that the business would treat as continuous. The second half is a business question, not a technical one.

**Alternatives:**

1. **One aggregate per (person, capacity) holding all occurrences of that capacity** ("this person's worker history"). The rule becomes internal and can be refused at the point of recording; it mirrors the ratified Membership choice (RA §7) and is the smallest change that makes the claim true. Cost: an import that adds a voter relationship and a registrar recording a worker relationship do not collide (different aggregates), which preserves the concurrency benefit the design wanted. This is the alternative I would expect the next revision to choose, unless the Principal answers that overlaps are permitted.
2. **One aggregate per person holding all affiliations** (the revision-1 shape) — makes non-overlap and archival atomic, at the cost of the collision the design correctly avoided between profile edits and affiliation edits. Only attractive if the *person record* is also needed atomically with the relationships, which the design argues it is not.
3. **Keep per-occurrence aggregates and treat non-overlap as a *detected* condition** with a reconciliation process, exactly as the ratified Membership architecture permits for its asynchronous branch (RA §10, BD-SYNC "asynchronous: INV-9 becomes a detected condition with a reconciliation process"). This is legitimate **only if the Principal accepts that the organisation can, for a time, hold two active periods of one capacity**, and the design then states detection, reporting and correction as a business process. As written, the design claims the strong invariant and provides neither.
4. **Drop the rule** — permissible only if the business says two overlapping occurrences of one capacity can be legitimate (for example a backdated engagement recorded after a successor started). The design asserts the rule without a source; no record in the bundle requires it. This is a **Principal** question (P3, §10).

**What cannot stand** is the present text: a strong invariant, claimed to be enforced inside an aggregate that cannot see the instances it constrains.

---

## 5. Lifecycle and invariant assessment

### 5.1 Archival (question C)

**The design's rule.** "Archiving a person ends their active affiliations and revokes access. The archive event triggers those acts, and recording a new affiliation checks the person's status first. A race is closed by the archive handler, which ends anything still active." (§9.1)

**Can an active affiliation or active access survive archival?** Under the design as written, **yes**, in four ways:

1. **Handler failure.** The archive commits; the handler does not run or fails permanently. Nothing in the design states a guarantee, a detection or a repair. "Eventually processes" is not a guarantee and the design does not even claim it explicitly.
2. **Race, direction 1 — relationship first.** An affiliation command reads the person's status as `recorded`, the archive commits, the affiliation commits. The handler may have read the affiliations before the new one existed and ended none. The result is an active affiliation under an archived person.
3. **Race, direction 2 — handler first.** The handler runs and ends nothing; the new affiliation commits afterwards. Same result. The design's sentence "the race is closed by the archive handler" is true only for a particular interleaving, and is therefore not a closure.
4. **Retry.** If the handler is retried, it may record a second end for a relationship already ended; the design does not state idempotency for the cascade.

**Basis and actor (a contradiction with the design's own invariant).** I-14 requires every affiliation and access act to record its basis and actor, and `Basis` is defined as "decision / invitation / authorised import / declaration, with a reference" (§9.2). A cascaded end caused by archival is none of these. Either archival is itself a decision that ends relationships (then the business rule needs a source and the officer who archived is the actor, with "archival decision" as basis), or it is not, and the cascade is an unauthorised ending. The design has it both ways.

**The business guarantee the model must give — and the choice.** One of three models must be chosen; the design asserts the first without deciding it:

- **(i) Archive terminates.** Archiving is a recorded decision that ends every active affiliation and revokes access, with a basis and the archiving officer as actor. The guarantee can then be **hard** (no archived person has an active relationship at any observation) only if the ending is atomic with the status change — which means one aggregate, or a single transactional boundary across the three — or **soft** (violations are detected and repaired within a stated window). The design currently claims hard language with soft (in fact, no) provisions.
- **(ii) Archive is refused while relationships are active.** Archiving becomes a precondition check; the organisation must end relationships first. This removes the cascade and its basis problem, but leaves the check-then-act race against a *new* relationship unless the check is part of the same decision.
- **(iii) Archive is only a status flag.** Relationships continue; "archived" means "we no longer work with this person" informally, and nothing stops a live worker relationship. This is coherent only if "archived" is not meant to mean that; the design's own §9.1 says it does.

**Restoration.** The design has no `restored` state (`recorded` → `archived` → `erased`). If an archive is mistaken, the correction is forward-only, and the affiliations ended by the cascade carry a historical end whose basis is now false. The model has no compensation, and the design does not say restoration is forbidden. This must be stated (F-4).

**Erasure.** "No change after `erased`" is internal to the person aggregate; the design says nothing about what happens to the affiliations and access of an erased person, or to the `PersonRef` slot. XD-PROV is open, so UNKNOWN is acceptable — but the aggregate's own rule presupposes a state the design does not model (F-4).

### 5.2 Import (question D)

**What the design distinguishes.** `ImportPerson` is "the same operation as `RecordPerson`, batched", creating "persons recorded (+ voter affiliation, D-109)", with a "batch id per row" as audit (§7). Each import row is its own transaction, "idempotent per row key" (§9.5). Scenario 8.5(a) covers a repeated import row.

**What it does not distinguish:**

| Aspect | In the design? | Consequence |
|---|---|---|
| Creating the person record | Yes, but **not separated** from the voter relationship | see below |
| Recording the voter relationship | Merged into the same operation | one operation, **two aggregates** |
| Processing a row | Implied ("each row is its own transaction") | no row state, no progress model |
| Retrying a row | Not addressed | "idempotent per row key" is asserted; the **row key is never defined** |
| Partial failure | Not addressed | no business outcome |
| Duplicate rows | 8.5(a) within a batch; 8.5(b) across records | re-running yesterday's file under a new batch is neither |

**The internal contradiction (F-5).** §9.5 states "**one aggregate per transaction**". `ImportPerson` creates an `OrganisationPerson` **and** an `Affiliation` — two aggregates. Either the operation spans two aggregates in one transaction (contradicting §9.5), or it is two transactions (contradicting "same operation"), and the design then owes an answer for the state between them. The design gives neither.

**The business outcome when the record exists but the voter relationship was not recorded.** Establish the requirement first, as instructed: the organisation's list says this human is on its voters' list. After a partial failure the organisation believes the person was imported; Election, which consumes the relationship, sees nothing; the organisation's own screens may show the person without the voter relationship, or may not. The possible requirements are:

- **R-a (all-or-nothing per row):** a row either records both or neither, and is reported failed. Simplest for the organisation, but it forces two aggregates into one transaction (F-5) and prevents the very independence the design argues for.
- **R-b (ordered, with a visible intermediate state):** the person is recorded first; the voter relationship is a second, separately reported step; the row is "person recorded, voter pending" until completed or abandoned. This respects the design's independence argument and the fact that the two facts have different evidence, but requires the two states to be **visible and reconcilable** — which the design lacks.
- **R-c (person only):** a failed voter relationship is simply lost, and the organisation is told the row failed; the person stays. Only acceptable if the organisation can detect and fix it from its own list.

I recommend **R-b**, because the design's own aggregate reasoning implies it, and because a voter relationship is legally and operationally the more sensitive fact: it must never be *assumed* present. But the choice is the Principal's (P6, §10), because it decides what "we imported our voters" means to an organisation.

**Row identity.** Without a defined row key, "idempotent per row key" is unsupported. The business question is what makes two rows "the same": a stable external identifier supplied by the organisation, or the file and position. If the former, the organisation must supply it; if the latter, re-uploading an old file creates duplicates (F-5).

**Re-import versus update.** The design has `UpdatePersonProfile` for an officer's correction, but no operation that *updates an existing person's profile from an updated organisational list*. Organisations do re-issue member lists. As written, a re-import either duplicates or does nothing (F-5).

### 5.3 Duplicates (question E)

**The five cases the commission asks to distinguish:**

| Case | In the design? | Assessment |
|---|---|---|
| The intended canonical Account | Yes (§1, §4.2, I-13) | Correct as an **aim**; stated as an invariant it is wrong (see F-6) |
| Duplicate Accounts that may exist until an explicit merge | Partly: §1 "once"; D-110 quoted in §2; **but I-13 contradicts it** | **The design's invariant table overclaims** (F-6) |
| Identity's explicit merge | Yes, `HandleIdentityMergeNotice` and 8.5(c) | The **outcome** for the organisation is not defined (F-7) |
| The organisation's own local duplicates | Yes, 8.5(b) and (c) | The merge **act** is missing: no operation, authority or basis (F-8) |
| The organisation's decision about its records after a merge | "the organisation then decides" (8.5(c)) | A decision with no rule, actor or record; downstream consumers are not told (F-7) |

**The claim of automatic uniqueness — flagged.** I-13 reads: *"One Account per registered human; registration is never repeated per organisation — Identity — **RAT (0..1)**; WD D-110."* The ratified sentence in XD-3 §2 item 6 is *"**zero or one Account per Person**"*: it constrains how many Accounts a Person may hold, **not** how many Persons a human may have. D-110's own text says explicitly: *"Not a guarantee of uniqueness: because matching by e-mail or name is forbidden (D-75), a duplicate registration can occur and is resolved only by Identity's explicit act (C-5)."* The design's §1 and §2 quote D-110 accurately; its invariant table then states the opposite as RAT. This is the one place where the document claims automatic uniqueness, and it must be corrected (F-6).

**The consequence the design does not draw (F-7).** After an Identity merge notice, two of the organisation's records may be shown to be one human. The design says the organisation "decides about its records". Until it decides — and the design does not require it ever to — two records in one organisation hold the **same `PersonRef`**. The ratified Membership contract is keyed on `PersonRef` (CR-01 C2 accepts `personRefs[]`; "one Membership per (Person, Organisation)"). The organisation's "Member" view, and Election's input, will therefore see **two person records for one `PersonRef`** — a state the contract did not contemplate and for which the design sets no rule. In an election context this is the double-vote risk the Identity dossier names (P-I8: "duplicates within one organisation are Election's double-vote risk"). The design must state that either (a) at most one record per `PersonRef` per organisation may exist at a time, or (b) a "duplicate pending local merge" state exists and every consumer must treat the records as one — and must tell Membership and Election which.

### 5.4 The invariant table itself

- I-13 is wrong (§5.3).
- I-4 ("a person can be recorded without an Account") and I-5 ("a person can be recorded without formal Membership") are **capabilities**, not invariants: they state what is permitted, not what must always hold. Listing them as invariants is not harmful, but they cannot be "enforced inside an aggregate" as §9.1 claims.
- I-2 ("an Account never creates a person record or affiliation") is a **causal prohibition**, not a state property. It matters — and the legacy system violates it (PBDIGIT-341 F-5) — but it is tested by observing what an Account creation does, not by inspecting a register.
- I-15 has no owner ("all contexts, per organisation"). If it is the tenancy invariant of D-111, its owner is the platform capability, not the contexts; the design should say so.

These findings concern classification rather than substance, but an invariant table that mixes them loses its power as a checklist.

---

## 6. Cross-context contract assessment (question G)

Verdict legend: **SOURCED** — the fact, direction and owner are in a ratified or decided record; **PRINCIPLE SOURCED, MECHANISM UNKNOWN** — the obligation is recorded, the event/query is not; **INFERRED** — plausible, no source; **UNKNOWN** — no source, do not assume.

| Contract | Design's claim (§5, §7) | Source | Verdict |
|---|---|---|---|
| Identity → Organisation: `PersonRef` | Issued by Identity, scoped to the organisation, held in the record's slot | XD-3 §2 items 2–3 (RAT) | **SOURCED** |
| Identity → Organisation: `ClaimRedeemed` | An event for the issuing organisation only | D-75 §6 principle "a claim code from one organisation never reveals another's affiliation" (RAT); the **event name and mechanism** appear only in the PROPOSED addendum §4 | **PRINCIPLE SOURCED, MECHANISM UNKNOWN** |
| Identity → Organisation: merge notice | "an Identity merge notice" updates the slot | C-5 (merges only by Identity's explicit act, RAT); **no notice event is recorded anywhere** | **INFERRED / UNSOURCED** — mark UNKNOWN |
| Organisation → Identity: claim request | Implied by 8.10; no operation defined | XD-3b §2a claim (via D-75); the **issuance act** is not designed | **UNKNOWN** in this design (F-15) |
| Identity ↔ Organisation: sign-in discovery query | Identity finds the references; the register finds the record by `PersonRef` | D-102 (WD, challengeable); D-101 leaves the mechanism open; L1/L2 open | **SOURCED as an open responsibility; mechanism UNKNOWN** (correctly left open) |
| Membership → Organisation: C1 `MembershipCoverageResolved` | Event carrying complete coverage | CR-01 (D-73 RAT); payload in RA §8 amendment | **SOURCED**; the design does not mention `closedThrough`, `schemaVersion` or refusal beyond the boundary |
| Membership → Organisation: C2 `CoverageKnownAt` | Query by `personRefs[]` | CR-01 + D-77 (RAT) | **SOURCED**; **the design omits τ** at §5 ("C2 query by `personRefs[]`, D-77") — the query is `CoverageKnownAt(organisationId, personRefs[], τ)` and queries beyond `closedThrough` are **refused, never answered partially** (RA §8, CR-01 time paragraph) |
| Organisation ← Governance: `AuthorityCheckPort` | Query | RA §8 "Governance: the AuthorityCheck port" (RAT); Decision A | **SOURCED** |
| Organisation → Election: the "voter" relationship at τ | Query at τ | D-109 (WD) settles that Election **consumes** it; the **interface** (query vs event), payload and reference key are not recorded | **PRINCIPLE SOURCED, MECHANISM UNKNOWN**; the design's "query at τ" is an inference |
| Organisation → Authorization: grant/revoke | "event or query" | D-104 (WD): enforced technically by Authorization; **no contract exists**; Authorization scope open (XD-4) | **UNKNOWN** — the design's "event or query" is a free choice, not a sourced contract |
| Organisation ← Geography: official references for address resolution | Reference at τ | D-93 (RAT); D-101 (address ownership); baseline §6 (streets are components) | **SOURCED** |
| Profile never into Identity | Never crosses | XD-3 §2 item 4 (RAT) | **SOURCED** |
| One shared τ, complete answers or refusal (D-73) | Not stated for the register's own reads | D-73 (ARB act); RA §8 | **OMITTED** (F-9) |

**Findings.**

- **F-9.** The design never applies D-73's knowledge-time contract to its own read models. The "Member" view, the voter list for Election and any profile read need an `asOf` and a `τ`, with refusal beyond `closedThrough`. The design's signatures are silent. Against an ARB act, this is an omission, not an interpretation.
- **F-10.** Three contracts are named without a source (`ClaimRedeemed`, the merge notice, the Authorization event/query) and one is given a mechanism that no record fixes (Election query). The commission instructs: mark unsourced as UNKNOWN, invent no contract. The design should mark them, or the commission's reader cannot tell what is decided from what is proposed.

**One nuance in the design's favour.** The design's refusal to let `PersonRef` be a cross-organisation key, and its "never offered" cross-organisation finders (§9.4), are exactly what XD-3 §2 items 3–4 require. No contradiction with ratified architecture was found in the contract *directions* — only in their sourcing and completeness.

---

## 7. BR-10 and P-I1: comparison and recommendation (question F, mandatory)

### 7.1 What the sources actually say

- **BR-10 (PO direction, not a rule)**: "When an organisation imports a person who has not registered, Identity creates **or resolves** the Person and issues the reference for the organisation's record. No User affiliation with PublicDigit is created at import." It "conflicts with an XD-3b condition (K4, open)".
- **XD-3 (RAT)**: K4 — "mapping of imported people who have not registered" — is **explicitly not decided** (§3). No matching by e-mail or name (§2 item 6). Zero or one Account per Person. C-1…C-5 bind every later mechanism; C-4 permits a **minimised, data-protection-reviewed central index** (per the addendum's correction S-4; INV-AFF-1's "no persisted register" was **not adopted** by D-101).
- **"Resolves"**: the design and the BR amendment note both say it "cannot be done without the matching that D-75 forbids", so in practice BR-10 means **create**. I agree with that inference for the ordinary case; "resolve" could alternatively mean "resolve the reference for a person the organisation has already claimed", which needs no matching — so the wording need not be discarded, only read narrowly. **Inference.**
- **Membership's need**: CR-01 C1 carries a `personRef`; C2 accepts `personRefs[]`. Membership is keyed on a `PersonRef` per organisation (RA: "one Membership per (Person, Organisation)"; the ratified model's identity criterion). Any option that leaves an imported member without a `PersonRef` therefore cannot serve Membership via CR-01.
- **The design's own observation** (§13, Identity row): eager creation "makes Identity hold a central record about people who have **never registered**, which sits awkwardly with 'Identity owns the platform identity of registered people'." That is the crux, stated well.

### 7.2 The four options

The commission's four options are: (1) eager creation at import; (2) lazy, with an organisation-local placeholder until the person claims the record; (3) eager creation with restricted identity discovery; (4) creation only at claim. I note a definitional weakness first: the design's §10 option (d) ("reference at import, Person at claim") is not the same as the commission's option 4, and the Identity dossier's option (b) merges the commission's (2) and (4). **The option set is inconsistent across three documents, which makes the Principal's choice harder than it need be** — a small but real finding (part of F-14's family; see §11).

I use the commission's definitions.

| Criterion | (1) Eager at import | (2) Lazy + organisation placeholder | (3) Eager, restricted discovery | (4) Creation only at claim |
|---|---|---|---|---|
| **Business consequences** | Imported people are full subjects for Membership and Election from day one; matches parties where most members never log in | Imported people are invisible to Membership/Election until they claim; the organisation's own list is the only record | Same as (1) for the organisation; discovery deferred | Same as (2); BR-10 withdrawn |
| **Identity correctness** | No false matches (matching forbidden); every Person is an assertion that the organisation knows a human — identity covers non-users | Identity describes only people who acted | As (1) for identity; discovery withheld until claim | As (2) |
| **Duplicates** | Two organisations importing one human create two Persons; a merge process is needed **before imports grow** | No import duplicates; duplicates only from duplicate registrations | As (1) | As (2) |
| **Privacy / minimisation** | Identity holds a bare `PersonId` for non-consenting people; under L1 it may also hold an organisation mapping — the sharpest privacy objection (XD-3b's condition on central records without an account) | Least | Bare id only; **no discovery entry before claim** — satisfies the XD-3b condition | Least |
| **Organisation independence** | Good: every organisation has its reference immediately | Good, but every context carries placeholder semantics | Good | Good |
| **Voters who never register** | Fully supported | Supported in the organisation's record only; invisible to Membership/Election keyed on `PersonRef` | Fully supported | As (2) |
| **Claim and merge lifecycle** | Claim names an existing Person (the shape INV-ID-6 assumes); merge process needed anyway | Claim creates the Person; no import merge, but a duplicate Account may still exist → merge needed | As (1) | As (2) |
| **Effects on Membership and Election** | None: CR-01's `PersonRef` key is satisfied | **Placeholders and re-keying** — Membership facts are append-only and cannot be re-keyed (RA §2, §5 INV-6/INV-14); the design would touch ratified Membership | None | Same as (2) — and a **new key** for the organisation's person would be needed in a ratified contract; reported, not proposed |
| **Consistency with D-107…D-111, XD-3** | BR-10 satisfied; XD-3 not violated (K4 open); D-111 tension as the design states | BR-10 not satisfied as written; D-111 comfortable | BR-10 satisfied; best D-111 fit among the eager options; consistent with C-4 if discovery is withheld | **Contradicts BR-10**; requires a ratified-contract change for Membership |

### 7.3 Recommendation, with assumptions

**I recommend option (3): eager creation of the Person and the organisation's `PersonRef` at import, with nothing usable for discovery until the claim is redeemed.** This is also the Identity dossier's recommendation (P-I1 REC (c)); my reasoning is independent and agrees with it on the evidence available.

**Assumptions (the Principal should correct any that are wrong):**
1. Organisations import large lists whose members mostly never log in; the list is authoritative for the organisation's own internal purposes (BR-5/BR-6; PBDIGIT-341's import reality).
2. The organisation may lawfully record those people and their details (legal basis is OQ-3a-1, open; a data-protection question, not an architectural one).
3. Membership and Election must be able to refer to an imported person **before** that person claims (otherwise option (4) becomes viable and preferable).
4. A merge process for duplicates created by independent imports will exist before imports become large (D-110's caveat; P-I1's own dependency).

**Why not (1):** the only difference from (3) is that Identity may record an organisation mapping for a person who never registered — the precise thing XD-3b's condition and XD-PROV §1 were written to prevent. There is no business benefit to discovery for someone who has no Account; the cost is real.

**Why not (2) or (4):** they solve a privacy problem at a much higher price — re-keying append-only Membership facts, which the ratified architecture does not permit, and placeholders in every context. The organisation's own person record is already the natural placeholder; a second, central one is redundant. **Option (4) is the honest alternative only if the Principal's intention is that PublicDigit's identity should describe only people who have chosen PublicDigit** — in which case BR-10 must be withdrawn and Membership's key changes; that is a much larger decision than BR-10's current wording suggests.

**The genuine business question behind P-I1** (which no option table can answer): *does the platform identity mean "every human the platform knows about because an organisation knows them", or "every human who has registered"?* Option (3) chooses the first, with a curtain between the identity record and the organisation until the person appears. Option (4) chooses the second. **The Principal decides the business intention; the design must not.** I do not recommend claiming that option (3) "resolves K4": it chooses K4's answer, and that choice is the Principal's.

---

## 8. O-7: voter relationship scope, alternatives and evidence (question I)

### 8.1 The design's position, and a challenge to the framing

The design says the "voter" capacity (election-only organisations) means "is on the organisation's **list of people it puts forward for its elections**; scope per election is **open (O-7)**" (§4.3). O-7 is presented as a choice between an **election-specific** relationship and a **standing** organisation voter register.

**I challenge the either/or.** The ratified boundary already assigns the **electorate** to Election ("Election owns the electorate, eligibility and entitlement"; D-109: the organisation's relationship is Election's *input*). A standing organisation relationship and a per-election electorate are therefore **compatible**, and probably the correct shape:

- The organisation maintains a **standing** relationship ("this person is on our voters' list") in its own register — this is what D-109 describes.
- For each election, **Election fixes an electorate** at a cut-off (τ), from the organisation's records plus the election's rules. That is Election's own fact and is not O-7.

Under this reading, O-7 narrows to a single question: **does the organisation's voter relationship itself carry election scope** (one relationship per election) **or not** (one standing relationship)? If it does not — the design's implied model — then "which election was this person on the list for?" is answered by Election's electorate, not by the organisation's affiliation. If it does, the organisation's register carries the election scope and Election consumes a per-election relationship.

### 8.2 Consequences of the two alternatives

| Dimension | Election-specific relationship (org records per election) | Standing relationship (org records once; Election fixes the electorate) |
|---|---|---|
| **Operations** | Every election starts with an assembly step (copy/select from where? the last election's list? a spreadsheet?). PBDIGIT-341 shows per-election imports today. | One list maintained continuously; election preparation is a read plus Election's rules. |
| **Election preparation** | The list is an artefact of the election; its authority and basis are clear per election. | Election must interpret a moving relationship state at τ; eligibility depends on data that may change after the cut-off (mitigated by τ and `closedThrough`, if applied — F-9). |
| **List maintenance** | Removals after an election do not alter it. | A removal today changes no closed election **only if** periods are kept and read at τ; the design's forward-correction rule supports this. |
| **Historical reconstruction** | Strong: each election's list is preserved as its own record. | Possible via knowledge-time reads, but only if every read carries τ and no affiliation is deleted (XD-PROV applies). |
| **Election's eligibility determination** | Input is explicit at the cut-off. | Election must query the relationship at τ; the design's contract does not carry τ to Election today (§6). |
| **Fit with D-109** | Consistent (the relationship is the organisation's). | Consistent, and closer to D-109's wording ("the voter-related relationship is the Organisation's record … Election consumes it as an input"). |

### 8.3 The business evidence the Principal needs (assume no universal practice)

1. **The organisations' written rules.** Do the statutes or rules define a voters' list **per election**, with a publication, a cut-off and an objection/challenge window? If yes, the election-specific artefact is required by the organisation's own authority and O-7 is largely answered by the rules, per organisation.
2. **Applicable election law.** Some jurisdictions require a fixed, published electoral roll per election. That is legal input, not a design choice; it may differ per country (the design's own geography work is per country).
3. **Current practice.** How are lists produced today — one spreadsheet per election (as the legacy import implies), or a maintained register that is exported? PBDIGIT-341's per-election import is evidence of *practice*, not of a rule.
4. **Turnover between elections.** If most people recur, a standing register saves work; if lists are rebuilt each time, election scope is the natural model.
5. **Whether an organisation ever maintains a voters' list outside an election.** If it does (for canvassing, for example), the standing relationship is a business fact independent of elections.
6. **What must be reconstructible.** If an election result can be challenged, the list as at the cut-off must be reproducible. Ask which mechanism the organisation's rules require.

**Recommendation (not a decision):** model the standing relationship as the design does, and make the per-election electorate an explicit Election fact read at τ — provided F-9 is fixed. If the rules of the organisations require a published per-election list, record that list's fixation as an Election fact, not as a change to the organisation's affiliation. The Principal should confirm with the statutes on file; until then, UNKNOWN is the honest status, and the design's "open (O-7)" is correct.

---

## 9. Contradictions, omissions and material risks

### 9.1 Findings

Each finding states: business fact or requirement; strategic consequence; tactical consequence; evidence; kind; impact; whether a Principal decision is needed.

---

**F-1. Non-overlap of periods is claimed as a rule enforced inside an aggregate that cannot see the periods it constrains.**

- **Business fact/requirement (assumed, unsourced):** one person cannot hold two active relationships of the same capacity with one organisation at the same time.
- **Strategic consequence:** the affiliation is the organisation's own fact (D-101) and the rule is an intra-organisation business rule; it does not touch another context.
- **Tactical consequence:** with one aggregate per occurrence, no instance holds the sibling periods; the rule is neither refused at the point of recording nor detectable afterwards. Either rebuild the aggregate per (person, capacity) (alternative 1, §4.4) or declare the rule a detected condition with reconciliation (alternative 3), or drop it (alternative 4).
- **Evidence:** design §9.1 (line 175, "non-overlap of periods per (person, capacity)" listed under "Rules enforced inside"), §9.1 "one per capacity occurrence" (line 175), §9.5 (line 207); commission §3 B; RA §7 (the ratified precedent: one aggregate per (p,o) because invariants "read several periods").
- **Kind:** **fact** (an internal contradiction between the stated rule and the stated aggregate shape); the alternatives are **recommendation**.
- **Impact:** **High** — the design's central tactical claim is untrue as written, and the same mistake recurs in the archival rule (F-2).
- **Principal decision:** **partly.** P3 (§10): does the business require the rule at all? The enforcement shape is a technical choice.

---

**F-2. Archiving is asserted to end relationships and access, but the cascade has no basis, no actor and no proof.**

- **Business fact/requirement:** after a person is archived, the organisation expects that the person has no live relationship and no workspace access.
- **Strategic consequence:** archiving is an Organisation act; but ending a relationship and revoking access are acts that XD-PROV §4 treats as distinct, each following its own lifecycle. An automatic cascade contradicts that principle unless the Principal rules that archiving is a decision to end all relationships.
- **Tactical consequence:** three aggregates must agree; the `Basis` value object has no value for a cascaded end, so I-14 is violated; if the end facts are recorded by the handler, the actor and the authority are not those of an officer acting on that relationship.
- **Evidence:** design §9.1 (line 179), §9.2 `Basis` (line 188), I-14 (line 138); XD-PROV §4 (lines 87–93, "Ending a Membership does not automatically end an affiliation; each follows its own lifecycle"); D-104 (access "can be withdrawn without changing any of those").
- **Kind:** **fact** (the cascade has no basis in the design's own vocabulary) + **inference** (the contradiction with the separate-acts principle).
- **Impact:** **High** — audit integrity and the meaning of "archived".
- **Principal decision:** **yes** — P2 (§10): does archiving terminate relationships, is it refused while relationships are active, or is it only a flag?

---

**F-3. The archival race is not closed; failure and retry leave live relationships under an archived person.**

- **Business fact/requirement:** the guarantee in F-2 must hold even when a handler fails, is retried, or an affiliation is recorded while archival is pending.
- **Strategic consequence:** none beyond F-2.
- **Tactical consequence:** the design offers one interleaving as proof. It must either make the guarantee hard (one consistency boundary — which argues for the aggregate shape in F-1) or state a soft guarantee: violations are detected and repaired within a stated business window, with the repair's authority and audit. Retry idempotency for the cascade must be stated.
- **Evidence:** design §9.1 (line 179: "A race is closed by the archive handler, which ends anything still active"); commission §3 C ("'A handler eventually processes the event' is not a proof").
- **Kind:** **fact** (no proof is offered) + **recommendation**.
- **Impact:** **High** — a live workspace access under an archived person is a security and audit failure.
- **Principal decision:** no (technical), contingent on P2.

---

**F-4. Restoration and erasure are not modelled; a mistaken archive leaves relationships ended on a false basis.**

- **Business fact/requirement:** organisations make mistakes; an archive may need to be reversed, and erasure is a future state (XD-PROV open).
- **Strategic consequence:** the person record's lifecycle is Organisation's; erasure's authority is Identity/XD-PROV.
- **Tactical consequence:** `recorded → archived → erased` has no restore edge; forward correction cannot revive a cascaded end without recording a new start with a new basis, which changes history's meaning; what happens to affiliations, access and the `PersonRef` slot after `erased` is undefined, and cross-aggregate references may dangle.
- **Evidence:** design §6.1 (line 116), §9.1 "no change after erased" (line 174); XD-PROV §4–§5 (retention, deletion); design §10 XD-PROV row (line 215).
- **Kind:** **omission**; the requirement is **inference**.
- **Impact:** **Medium** (becomes High if erasure is implemented before it is defined).
- **Principal decision:** **yes** for restoration (P2); erasure is XD-PROV's.

---

**F-5. Import spans two aggregates and defines no partial outcome; row identity and re-import are undefined.**

- **Business fact/requirement:** an import records the organisation's knowledge of a person **and** its voter relationship; the organisation must be able to tell what was actually recorded.
- **Strategic consequence:** the two facts have different evidence and different consequences (Membership, Election); the design's own aggregate reasoning says they are independent.
- **Tactical consequence:** §9.5 says "one aggregate per transaction" while §7's `ImportPerson` creates two; the design must choose all-or-nothing (§5.2 R-a), ordered with a visible intermediate state (R-b), or person-only with a reported failure (R-c). The row key must be defined; re-import must either update an existing record or be refused, not silently duplicate.
- **Evidence:** design §7 (line 146), §9.5 (line 207), 8.5(a) (line 161); commission §3 D; PBDIGIT-341 §3 (the target rule: recording the voter relationship is Organisation's).
- **Kind:** **fact** (internal contradiction) + **omission**.
- **Impact:** **High** — imports are the main onboarding path and the main way voter lists are built.
- **Principal decision:** **yes** — P6: what does "we imported our voters" mean when only the person was recorded?

---

**F-6. I-13 claims automatic uniqueness that the ratified source does not give and D-110 denies.**

- **Business fact/requirement:** one login per human is the aim; a duplicate registration can occur and is resolved only by Identity's explicit merge.
- **Strategic consequence:** Identity owns equivalence (D-75); the register must not assume uniqueness anywhere.
- **Tactical consequence:** correct I-13 to "at most one Account per Person; duplicate Accounts are possible until an Identity merge"; remove the RAT classification of the uniqueness claim; check every place the design says "one Account" (8.4, §4.3) for the intended meaning.
- **Evidence:** design I-13 (line 137), §1 (line 17), 8.4 (line 160); XD-3 §2 item 6 (line 23, "zero or one Account per **Person**"); D-110 (DR line 860, "Not a guarantee of uniqueness"); design §2 quotes D-110 correctly (line 38).
- **Kind:** **fact**.
- **Impact:** **High** for correctness of the invariant table; Medium operationally.
- **Principal decision:** **no** — a correction.

---

**F-7. After a merge notice, two records may share one `PersonRef`, and no consumer is told.**

- **Business fact/requirement:** when Identity establishes that two records are one human, the organisation must be able to act — and until it does, the organisation still holds two records for one person.
- **Strategic consequence:** Identity owns equivalence; the organisation owns its records; Membership and Election consume by `PersonRef`.
- **Tactical consequence:** the design must state either that at most one record per `PersonRef` per organisation may exist at a time, or that a "duplicate pending local merge" state exists that Membership and Election must treat as one. Today neither is stated; CR-01's C2 and Election's input will see two records for one reference.
- **Evidence:** design 8.5(c) (line 161), `HandleIdentityMergeNotice` (line 152), §7 `findByPersonRef` (line 197); RA §8 CR-01, D-77; Identity dossier P-I8 (line 109: "duplicates within one organisation … Election's double-vote risk").
- **Kind:** **omission** (consequence not drawn); the rule is a **recommendation**.
- **Impact:** **High** in election contexts; **Medium** otherwise.
- **Principal decision:** **yes** — P4 (may the organisation merge its own records, and must downstream treat one reference as one person?).

---

**F-8. The organisation's merge of its own duplicate records has no operation, authority or basis.**

- **Business fact/requirement:** an organisation that discovers two of its records describe one person needs a way to act on that.
- **Strategic consequence:** the decision is the organisation's (D-101), under its own rules (D-97); Identity establishes sameness only (D-75).
- **Tactical consequence:** 8.5(b) permits a merge "only if they do not hold two different assigned `PersonRef`s", but §7 lists no merge operation, §6.2 no invariant, §9.2 no basis. The condition itself reads two aggregates.
- **Evidence:** design 8.5(b) (line 161), §7 operation list (lines 144–152).
- **Kind:** **omission**.
- **Impact:** **Medium**.
- **Principal decision:** **yes** — P4 (authority to merge).

---

**F-9. The knowledge-time contract (D-73) is not applied to the register's reads.**

- **Business fact/requirement:** a "Member" view, a voter list and a profile read must answer *as known at a time*, or refuse; they must not answer partially.
- **Strategic consequence:** Organisation consumes Membership facts (RAT); Election consumes the organisation's facts (D-109). Both depend on a shared τ.
- **Tactical consequence:** state `asOf` and `τ` for the Member view, the voter-list read and any profile read; refuse beyond `closedThrough`; state that one multi-context operation uses one τ.
- **Evidence:** D-73 (ARB act, DR line 658); RA §8 CR-01 "Time" paragraph (line 200); design §5 (line 102, C2 without τ), §9.4 read models (line 204).
- **Kind:** **omission** against an ARB act.
- **Impact:** **Medium–High** — historical reconstruction and election integrity.
- **Principal decision:** no.

---

**F-10. Several contracts are named without a source.**

- **Business fact/requirement:** a contract the design relies on must be traceable to a record, or marked unknown.
- **Strategic consequence:** mis-sourcing invites an implementation that no owner agreed to.
- **Tactical consequence:** mark `ClaimRedeemed`, the Identity merge notice and the Authorization event/query UNKNOWN; mark the Election query mechanism UNKNOWN; name only C1/C2 (sourced) and the `AuthorityCheckPort` (sourced) as contracts.
- **Evidence:** design §5 (lines 100–107); XD-3 §6 (principle only); the addendum §4 (a PROPOSED document naming `ClaimRedeemed`, with sync/async "not established"); D-104; commission §3 G ("Mark anything unsourced UNKNOWN; invent no contract").
- **Kind:** **fact** (sourcing) + **recommendation**.
- **Impact:** **Medium**.
- **Principal decision:** no.

---

**F-11. The act that places a person in a specific election's electorate has no owner.**

- **Business fact/requirement:** an election has an electorate; someone decides who is in it, when, and on what evidence.
- **Strategic consequence:** D-109 gives the organisation the relationship and Election the electorate; between the two lies an act the design does not name.
- **Tactical consequence:** either the organisation's relationship carries election scope (O-7 alternative A) or Election fixes an electorate from the standing relationship at a cut-off (alternative B). Either way the act, its authority and its record must be named; today "voter list for Election" (§9.4) is a read model with no producer.
- **Evidence:** design §4.3 (line 92), §9.4 (line 204), §13 Election row (line 246); D-109 (DR line 859); RA §1 (Election owns the election chain).
- **Kind:** **omission**.
- **Impact:** **High** for election preparation.
- **Principal decision:** **yes** — P5/O-7 (§8).

---

**F-12. Provenance, required as a distinct fact by XD-PROV, is reduced to an audit column.**

- **Business fact/requirement:** the organisation records which process first established a person's record, and that survives a later registration or claim.
- **Strategic consequence:** provenance is organisation-local (XD-PROV §1, Principal 2026-11-02) and distinct from affiliation and claim.
- **Tactical consequence:** add provenance as a fact of the person record (created by import or officer, with date and purpose), unchanged by later events; "batch id per row" is audit, not provenance.
- **Evidence:** XD-PROV §2 (lines 37–55), §6 Scenario E (lines 203–216); design §7 (line 146, "batch id per row").
- **Kind:** **omission**; XD-PROV is still PROPOSED, so the dependency is legitimate — but the design does not carry it.
- **Impact:** **Medium**.
- **Principal decision:** no for the design; XD-PROV itself is pending.

---

**F-13. The design asks for a baseline amendment that already exists (D-112 / A-1).**

- **Business fact/requirement:** the accepted baseline must be read consistently under D-107/D-108.
- **Strategic consequence:** none; this is document coherence.
- **Tactical consequence:** none; but a reader of revision 3 will believe an approved act is outstanding when it is done.
- **Evidence:** design §3 REC (line 62) and §10 (line 217) say the amendment note "has not been applied" and "needs the Principal's approval"; the bundle's baseline carries **Amendment note A-1 (D-112)** (baseline lines 335–345; DR line 872); the commission lists D-112 in the authority table (§2).
- **Kind:** **fact**.
- **Impact:** **Low**, but it shows revision 3 predates D-112 and was not refreshed.
- **Principal decision:** no.

---

**F-14. "The first capability needs no voter-import change" is contradicted by the documented state of voter import.**

- **Business fact/requirement:** the first capability is "an organisation can keep its own list of the people it knows … record who works for it or is its guest, and decide explicitly who may use its workspace" (§12).
- **Strategic consequence:** today's election-only import performs Identity's, Organisation's and Election's work in one Election service, creates affiliations and workspace access for existing accounts by e-mail matching, and so contradicts D-75 §2.6, D-101 and D-102 (PBDIGIT-341 F-1…F-6, §3).
- **Tactical consequence:** the new `ImportPerson` is the *replacement* for that path. Saying the first capability needs no voter-import change means the platform will run two contradictory import paths, or that the legacy one is remediated separately (PBDIGIT-341). That dependency should be stated, not denied.
- **Evidence:** design §12 (line 227–232, "It needs no Identity decision, no Membership change and no voter-import change"); PBDIGIT-341 F-5, F-6 (lines 22–23), §3 (lines 53–73), §9 (lines 157–160).
- **Kind:** **fact** (the legacy import's behaviour, as reported) + **inference** (the contradiction).
- **Impact:** **Medium–High** for sequencing and for what an organisation actually experiences.
- **Principal decision:** **yes** — P7: may the first capability coexist with the unremediated legacy import?

---

**F-15. Claim issuance is not represented on the organisation's side.**

- **Business fact/requirement:** the organisation issues a code to a person it knows, waits, and is answered or not; operations need to know who has been invited and who has not claimed.
- **Strategic consequence:** the claim is Identity's process (D-75); the organisation initiates and is told the outcome.
- **Tactical consequence:** the design has the outcome (`HandleIdentityClaimNotification`, the `PersonRef` slot) but not the state "code issued, not yet redeemed", nor the operation issuing it. PBDIGIT-341's acceptance step and the Identity dossier's claim slice both need it.
- **Evidence:** design 8.10 (line 166), §7 (line 151), §12 (line 231, "claim codes (Identity)"); XD-3b §2a via D-75; Identity dossier P-I3, P-I12 (claim-related).
- **Kind:** **omission**.
- **Impact:** **Medium**.
- **Principal decision:** no for the design; the claim slice is Identity's.

---

### 9.2 Scenarios that expose a missing rule (question H)

Only scenarios that expose a rule the design lacks. Unit belonging, areas, coverage and Governance are excluded as instructed.

| # | Scenario | Rule the design lacks | Finding |
|---|---|---|---|
| S-1 | Registrar starts a worker affiliation; the archivist archives the person before the command commits | No ordering guarantee; the status check is not part of the affiliation decision | F-3 |
| S-2 | Archive commits; the handler fails; the affiliation stays active | No detected-condition, repair or retry statement | F-3 |
| S-3 | Two officers record the same capacity with overlapping dates for one person on the same day | No aggregate sees both | F-1 |
| S-4 | A backdated correction shortens one period and lengthens another, creating an overlap | Corrections forward are defined for the profile, not for periods vs the non-overlap rule | F-1 |
| S-5 | Import row 1 records the person; row 1's voter relationship fails | No partial outcome | F-5 |
| S-6 | Next month the organisation re-uploads last month's list under a new batch | No row key, no update-from-import | F-5 |
| S-7 | Two of the organisation's records are shown to be one human by an Identity merge | Two records, one `PersonRef`; consumers unprepared | F-7 |
| S-8 | A duplicate Account claims the organisation's record; later Identity merges the two Accounts | The claimed slot changes to the surviving reference; the organisation's other record may now hold the same reference | F-7 |
| S-9 | A person with active affiliations and access asks for erasure | `erased` leaves references and relationships undefined | F-4 |
| S-10 | An archive is reversed a week later | No restore; the cascade's ends stand on a false basis | F-4 |
| S-11 | Election day approaches; the organisation's standing list has people who joined after the previous election | No named act fixes the electorate or its cut-off | F-11 |
| S-12 | The organisation removes a person from its voter list after a closed election; a result is challenged | Reconstruction depends on τ and retention; the design's reads do not carry τ | F-9 |

### 9.3 Material risks not elevated to findings

- **Data protection of recorded non-registrants.** The register records people who never registered, with contact details, on the organisation's authority. Lawful basis, notice and retention are open (OQ-3a-1, OQ-3b-2). The design is architecturally silent, which is acceptable at this level, but the Principal should not read "organisation-owned" as "no data-protection consequence".
- **The "ownership" language of D-111** (§3.4): architectural ownership is not legal controllership.
- **Legacy divergence.** PBDIGIT-341's facts show the running system violating the design's I-2 and I-1 today; the design's "not authorised for implementation" note is correct, but the gap between design and code is wide and the first capability will meet it.

---

## 10. Genuine Principal decisions, separated from technical design choices

These are business or constitutional questions the Principal alone can answer. Each is stated in business terms.

- **P1 — Identity of non-registered people (P-I1 / BR-10).** Does PublicDigit's central identity describe *every human an organisation knows*, created when the organisation records them, or *only humans who have registered or claimed*? Option (3) of §7 is my recommendation; the choice is the Principal's. **This is the single most consequential decision in this review.**
- **P2 — What "archiving" means.** Does archiving a person (a) end all their active relationships and access, (b) become impossible while relationships are active, or (c) merely flag the record? And may an archive be reversed? **Business/audit decision; the design currently assumes (a) without saying so.**
- **P3 — May two periods of the same capacity overlap?** Is the non-overlap rule a real business requirement (and therefore to be enforced), or may an organisation legitimately hold two overlapping occurrences of one capacity (for example a backdated engagement)? **If the answer is "may overlap", F-1 dissolves and the aggregate can stay per occurrence.**
- **P4 — The organisation's own duplicates.** May an organisation merge two of its own records that describe one human, and who in the organisation has that authority? After an Identity merge, must the organisation resolve its records — and must Membership and Election treat one `PersonRef` as one person meanwhile? **Business rule, per the organisation's written rules (D-97).**
- **P5 — O-7, voter-list scope.** Does the organisation's voter relationship carry election scope, or is it standing, with the electorate fixed per election by Election? The evidence the Principal needs is listed in §8.3; it depends on the organisations' statutes and applicable election law.
- **P6 — What an import guarantees.** When an import row records the person but not the voter relationship, what must the organisation be told and what must it be able to rely on? (§5.2, R-a/R-b/R-c.)
- **P7 — Sequencing with the legacy import.** May the first capability coexist with the current voter import, which still links accounts by e-mail and creates affiliations silently, or must containment (PBDIGIT-341 C-1…C-4) precede it? **Operational/risk decision.**

**Technical choices that are not the Principal's** (once P1–P7 are answered): the aggregate shape for affiliations (F-1), the modality of the archival guarantee (F-3), the contract forms and their sourcing (F-10), the read-model completeness and τ (F-9), the row key (F-5), the basis values (F-2), and the correction of I-13 (F-6).

---

## 11. Recommended amendments to revision 3

Each amendment is a recommendation; none changes a ratified document.

1. **Remove the non-overlap rule from the Affiliation aggregate and choose an enforcement shape** (F-1). Either one aggregate per (person, capacity) holding the occurrences, or a stated detected condition with reconciliation. Cite the business source of the rule, or obtain P3.
2. **State the archival guarantee and its modality** (F-2, F-3). Choose hard (one consistency boundary) or soft (detected and repaired within a stated window); define the basis and actor of a cascaded end; state idempotency for retries. Alternatively make archive a precondition (P2).
3. **Model restoration and erasure** (F-4). Either add a restore edge with its basis, or state that restoration is not permitted and why; state the effect of `erased` on affiliations, access and the `PersonRef` slot, or defer it explicitly to XD-PROV with the consequences named.
4. **Split the import's two facts and define the partial outcome, the row key and re-import** (F-5). Align §7 with §9.5.
5. **Correct I-13** (F-6): "at most one Account per Person; duplicate Accounts are possible until Identity's explicit merge." Remove the RAT classification from the uniqueness claim.
6. **Rule on shared `PersonRef`s after a merge notice** (F-7): either at most one record per reference per organisation, or a duplicate-pending state that Membership and Election must treat as one; say which.
7. **Add the organisation's merge operation** (F-8): authority, basis, and the check that neither record holds a different assigned reference.
8. **Apply D-73 to every read** (F-9): `asOf` and `τ`, refusal beyond `closedThrough`, one τ per multi-context operation — on the Member view, the voter-list read and the profile read.
9. **Mark unsourced contracts UNKNOWN and name only sourced ones** (F-10): C1, C2 and `AuthorityCheckPort` are sourced; `ClaimRedeemed`, the merge notice and the Authorization mechanism are not; the Election query's form is an inference.
10. **Name the election-inclusion act** (F-11) once P5 is answered, or state that it is Election's electorate fixed at a cut-off from the standing relationship.
11. **Add provenance** as a fact per XD-PROV, or state the dependency explicitly (F-12); **add claim issuance status** on the organisation's side, or state it as out of scope for the first capability (F-15).
12. **Refresh the document against D-112** (F-13) and **reconcile §12 with PBDIGIT-341** (F-14).
13. **Reclassify I-2, I-4 and I-5** as a prohibition and capabilities, not state invariants, and give I-15 its owner (§5.4).

---

## 12. What this review does not establish

- **No independent verification of code or runtime behaviour.** I did not open the repository. Every code fact I cite from PBDIGIT-341 (F-1…F-14 there) is taken as reported in the bundle, not re-verified. The design's claim that the legacy system diverges is therefore accepted on the bundle's evidence only.
- **No legal or data-protection assessment.** Lawful basis, controllers and processors, retention, and the lawfulness of recording non-registrants (OQ-3a-1, OQ-3b-2) are outside this review.
- **No statute or constitution.** Every statement about what an organisation's rules require (non-overlap, voter-list scope, authority to merge, authority to record) is UNKNOWN until the written rules are on file (D-97; O-8…O-10).
- **No evaluation of the Identity discovery mechanisms** (L1/L2) or of their feasibility and privacy; those are P-I2's and the 2026-11-01 evaluation's.
- **No tenancy decision.** Physical database separation is open (D-111 precision 2; HD-8); I assumed logical isolation only.
- **Out of scope by instruction:** unit belonging, organisation-defined areas, coverage, the full Governance model, Finance. Where they touch the boundary I report only the dependency.
- **UNAVAILABLE sources.** The bundle does not contain XD-3a/b/c, the CR-01 change request, the affiliation-discovery evaluation, the context map, ODA-01, ADR-T11, PBDIGIT-30, or the "2026-11-08 model document §50" cited by the commission — the bundled 2026-11-08 record has no §50. I did not guess their contents. Where I rely on XD-3b's conditions (K4, C-4) I rely on the XD-3 act's and addendum's quotations, which I mark as such.
- **No ratification, no authorisation.** Nothing in this report ratifies, approves or authorises implementation. Findings are for the Principal; the design's author incorporates what is accepted.
- **No assessment of the design's own provenance.** I did not attempt to determine which session or model wrote which part; the design states it is Claude-authored, and that is sufficient for my disclosure at §13.
- **Earlier AI sessions' conclusions are not treated as independent evidence.** Where the design or another document relies on its own earlier analysis, I read the underlying decision record where it exists in the bundle.

---

## 13. Reviewer identity, prior involvement and integrity-check method

**Who or what I am.** I am a model of the **DeepSeek family**, model designation **`deepseek-chat`**, operating inside the **Claude Code command-line harness** (an Anthropic product, with its system instructions and tooling). I am **not** a Claude model. I disclose the harness because it bears on independence: my instructions, tools and file access are Anthropic's, though the analysis and opinions in this report are mine. I have no access to, and was not given, any Claude session's summary or assessment of this design.

**Prior involvement.** None. I have not seen this design, its commission, the PublicDigit repository or any of its decision records before this session. I separated my own findings from assumptions inherited from the documents: where the design or another record asserts something I could not test (e.g. the code facts of PBDIGIT-341, the content of XD-3a/b/c), I have marked it as taken from the document, not verified.

**Integrity check performed by me.**
1. In the handover folder I ran `sha256sum -c SHA256SUMS`: all four files OK (`0-START-HERE.md`, `1-reviewer-prompt.md`, `2-opening-message.md`, `ir-opr-01-review-bundle.tar.gz`).
2. In the bundle folder I ran `sha256sum -c MANIFEST.sha256`: **13 of 13 files OK**. I computed the hashes myself; I did not rely on the manifest's assertions without running them.
3. The primary subject's computed SHA-256 is `c52069ff8e7048a1d79ee242f01a33189adac41c0e5da29d11e8f7181840325e`; its first 16 hex digits are `c52069ff8e7048a1`, as required by prompt §3 and the commission §1. **No stop was triggered.**
4. I modified no supplied document. I read the extracted bundle directly (no execution); no code from the archive was run.

**Labels used in this report.** *Fact* — a statement verifiable in the bundle. *Inference* — a conclusion I draw from the bundle, which another reader could contest. *Recommendation* — my advice, which the Principal may reject. I have not used "UNKNOWN" as a fact, and I have not interpreted a decision beyond its recorded text.

**End of report.**

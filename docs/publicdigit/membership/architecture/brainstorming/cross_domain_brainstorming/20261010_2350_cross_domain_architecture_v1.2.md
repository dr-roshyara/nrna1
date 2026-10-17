# Cross-Domain Organisational Architecture v1.2: Published Language, Governance Contract Amendments and Forum Contract

**Date:** 2026-10-10 23:50 · **Author:** Claude (developer and researcher role)
**Amends:** `20261010_2327_final_cross_domain_architecture_v1.1.md`, which amends `20261010_2314_final_cross_domain_architecture.md`. Unchanged parts are not restated.
**Status:** **CANDIDATE. Authority: generated. Not ratified; authorises nothing.** Scope: the cross-functional relationships among Membership, Member, Committee, roles (positions), Permission, geographical committee and Discussion Forum. **Election is out of scope.**

**Evidence labels (v1.1):**
- **Proposed:** written down, no approval.
- **Approved:** a recorded PO / ARB act exists.
- **Implemented:** the code exists in the repository.
- **Verified:** an executable check was re-run in this session and passed.
- **Accepted:** a formal acceptance act exists.

---

## 0. Summary

1. **Canonical-discovery correction.** v1.0 and v1.1 overlooked an existing contract: `docs/publicdigit/reviews/2026-10-09-participation-semantic-contract-draft.md` (gaps G-1 … G-5, invariants IP-1 … IP-7, BQ-P8 … P11). Three of its findings are missing from v1.1 and are now adopted:
   - **Duty ≠ Authority:** delegation transfers authority, never duty; a duty can outlive the tenure until a discharge act.
   - **Access projections must be rebuildable** (IP-4).
   - **Legal vs functional vacancy** are both derived (IP-6).

   **Consequence: the Governance contract is that draft plus the amendments in §3. It is not a new document.** (ES-005.4: extend, never copy.)
2. **Temporal compatibility for Governance: verified within bounds** (new model `g9`, 35,113 histories).
   - The ratified fact pattern transfers to tenures and committee lifecycle.
   - A recording-time "status field" makes future-effective appointments active early in **10,986** histories.
   - Cascading a Vacate on committee dissolution goes wrong in **270** histories once that dissolution is annulled, and breaks the annulment counterfactual in **190 / 5,916**.
   - The derived reference model gives **0 / 5,916** counterfactual violations.
3. **Published language (§4):**
   - one catalogue of every cross-context predicate: owner, signature `(subject, asOf v, knownAt k) → (T | F | U, seq)`, closed-world vs open-world, consumers;
   - plus the integration events, which act as triggers only.
4. **Forum contract (§5):** the first full contract of the Communication context. It covers aggregates, commands by action class, default policy-module templates and the open questions. No forum code exists.
5. **Worked example (§6):** "a district secretary moderates the district forum", evaluated end to end with the K2 guard rule and a witness path.
6. **Progress:** still **7 / 30 gates**. The research evidence is stronger, but nothing new was approved or implemented. The bottleneck remains the PO / ARB decision package (v1.1 §9).

---

## 1. Integration of the existing participation contract draft

| Draft item | What it establishes | Effect on v1.1 |
|---|---|---|
| **G-1 / IP-2 / IP-3: Duty vs Authority** | Authority ⊆ effective tenure (or delegation). Duty ⊇ held tenure and may continue until a discharge act (BQ-P8). Delegation moves authority, not duty | **Glossary addition:** **Duty**, owned by Governance; a responsibility attached to a tenure; never a Permission. **Delegation (R-16)** gains "transfers authority, never duty". Authorization evaluates **authority only**; duties are a Governance query (`OpenDuties(p, t)`), not a PDP input |
| **G-3 / IP-4: access is a rebuildable projection** | Stored ACL vs derived access diverged in 180 / 432 histories. "Deleting every access projection and recomputing yields the same access at every t" | **Stronger than v1.1 §3** and compatible with it. Adopted as forum invariant **FO-4** (§5). The v1.1 read-set cache is one admissible projection *because* it is rebuildable |
| **G-4 / IP-6: legal vs functional vacancy** | `Vacant_legal = ¬∃ Held`, `Vacant_functional = ¬∃ Effective`; they differ in 100 / 432 histories. LAPSE vs SUSPEND (BQ-P3) differ only with re-entry (110 / 540) | **Governance published language:** both predicates exported (§4). Forum moderation uses **functional** authority (EffectiveTenure) |
| **IP-7: no cross-context cascade writes** | 140 / 476 (Membership), G-2, G-3 | = v1.0 X-1 / X-2; now also evidenced inside Governance by g9 T-3 / T-6 (§2) |
| **G-2 / IP-5: Charge vs Payment** (Finance) | derived charge target + append-only ledger with adjusting postings | **Out of this scope.** Boundary only: Forum and Governance never read or write Finance state; a "fees paid" eligibility rule would be a Finance predicate behind its own port |
| **G-5: ownership table** | MembershipQueryPort, `EffectiveTenures`, `HasAuthority`, `FeeStanding` | merged into the §4 catalogue |
| **BQ-P8 … P11** | duty end; historical-content access; refunds; fee basis | BQ-P8 and **BQ-P9 (former members reading old posts)** are now forum-relevant (§5.5). P10 and P11 are Finance |
| **Admissibility note (Rev. 2):** G-3 classified UNCHK until Annul exists in the kernel | — | The kernel suite re-run in v1.1 includes `AnnulVoidTest` (C-1 slice; **implemented, not accepted**). **G-3 is a promotion candidate:** re-run `g3_g4_access_vacancy.py` filtered by the kernel once C-1 is accepted. Recorded as a TODO; not done here (Membership scope) |

---

## 2. Temporal compatibility of the ratified pattern for Governance (g9)

Model: `docs/publicdigit/membership/architecture/models/g9_governance_temporal_compat.py`.
- **Domain:** one person, one position, one committee; months 0–3; histories of up to 4 recording-ordered facts (Appoint, Vacate, Dissolve, Annul). That gives **35,113 histories**.
- **Reference:** the ratified liveness rule; `Effective = Held ∧ CommitteeActive`, derived, with no write on dissolution.

| Test (v1.1 §6.2) | Comparison | Result | Reading |
|---|---|---|---|
| **T-1** future-effective appointment | "status field" set at recording time vs reference | **10,986** histories show the holder effective before `effectiveAt` | Governance must resolve from `effectiveAt`, never from recording order. This is the same discipline as Membership |
| **T-3** committee dissolved, dissolution later annulled | CASCADE (store a Vacate when dissolving) vs reference | **270** divergent histories | dissolution must leave tenures untouched; non-effectiveness is **derived** (IP-7) |
| **T-6** counterfactual `Res(F·Annul(c)) = Res(F ∖ c)` | reference vs cascade, every final Annul (5,916 checked) | reference **0** violations; cascade **190** | the ratified annulment theorem carries over to Governance only under derived consequences |
| **T-5** policy / mandate versions | in-place retroactive edit vs forward-only versions, replay "as decided" | in-place 48 / 96 mismatches; forward-only 0 | **true by construction (illustrative, not evidence):** replay is exact only if pinned versions are immutable. It confirms the v1.1 §6.1 design rule; it proves nothing beyond it |
| T-2 retroactive mandate change | — | reduces to T-5 (mandate = forward-only rule version) + read-set pinning (g8) | no separate model needed |
| T-4 jurisdiction boundary change | — | reduces to read-set pinning of the geography snapshot id (g8 P2) + XD-5 | modelled once XD-5 is decided |

**Conclusion:** XD-1 (one temporal pattern) is **compatible with Governance lifecycle facts within these bounds**.
- **Not modelled:** several positions, capacity κ > 1, Correct / Void on tenure facts, delegation chains (the draft's open falsification question 3).
- Claim strength: verified within bounds.

---

## 3. Amendments to the Governance participation contract draft (proposed)

Numbering continues the draft. Each amendment is **Proposed** until RD-1 … RD-5 and the PO / ARB act.

| # | Amendment | Source |
|---|---|---|
| **AM-1** | **Aggregates:** Committee (narrowed: lifecycle, Term, Positions, κ, participations, tenures ⊆ participation) + **PositionHoldings** per (Person, Organisation) for incompatibility (INV-P6) and any per-person position limit (BQ-P1). An Appoint commits both, with expected versions, in one transaction | v1.1 §5, g7 (18 / 20 → 0) |
| **AM-2** | **JurisdictionAssignment:** a lifecycle log `(committee, GeoUnit, kind?, [effectiveAt …))` with Annul / Void / Correct. Committee-local unless BQ-J1 = "exclusive per committee kind", which then needs a JurisdictionRegistry aggregate per (organisation, kind) | v1.1 §5.2 |
| **AM-3** | **Committee ↔ Unit relation (R-06)** with kinds {governs, represents, advises, serves}; several kinds may coexist for one pair | v1.0 §4 |
| **AM-4** | **Dissolution rule:** `CommitteeActive(c, t)` is derived from lifecycle facts; dissolving **never writes** tenure or participation ends | g9 T-3 / T-6 |
| **AM-5** | **Time rule:** every Governance predicate resolves from `effectiveAt` with KnownAt(k); no recording-time status | g9 T-1 |
| **AM-6** | **Mandate and policy:** Mandate versions and Governance PolicyModule versions are **forward-only** (immutable once active; Void only before activation) | v1.1 §6.1; g9 T-5 |
| **AM-7** | **Authorization interface:** Governance owns the PolicyModule for its resources (appoint, vacate, constitute, dissolve, assign jurisdiction). Grants come only from that module; guards follow K2 | v1.1 §1, §2 |
| **AM-8** | **Ports (published, §4):** `Participates`, `Held`, `EffectiveTenure`, `VacantLegal`, `VacantFunctional`, `CommitteeActive`, `JurisdictionContains`, `CommitteeGoverns`, `HasAuthority`, `OpenDuties`; signature `(subject, asOf v, knownAt k) → (T | F | U, seq)` | v1.1 §4 |
| **AM-9** | **Evidence:** every Governance command records authority evidence `{actor, organ, decision / tenure / delegation id, mandateVersion, procedureResult, subject, asOf}` (G-AUTH-1) | committee discovery §1.5 |

---

## 4. Published language: cross-context predicate catalogue (proposed)

Every predicate is a query, never an event payload treated as truth. **Closed-world** means that absence in an available authoritative source is F; **open-world** means that absence is U (v1.0 §6.4). Consumers: **Gov** = Governance, **Org** = Organisation, **Authz** = Authorization, **Forum**.

| Owner | Predicate | Subject | World | Consumers |
|---|---|---|---|---|
| Identity | `SamePerson(account, person)` | account | closed | Authz |
| Membership **[Approved model]** | `IsMember(p, o)`, `Suspended(p, o)`, `CurrentType(p, o)`, `AdmissionDate(p, o)`, `ContinuousDuration(p, o)` | (p, o) | **closed** (after migration with attested provenance) | Gov (ε), Org (ε), Authz, Forum policies |
| Organisation | `UnitAffiliated(p, u)`, `UnitAncestor(u, u')`, `UnitActive(u)`, `PlatformRole(p, o, r)` | p, u | closed | Gov, Authz |
| Governance | AM-8 list | p, s, c | closed for tenure, participation and lifecycle; **open** for `OpenDuties` (statute-specific) | Authz, Forum, Org |
| Geography | `GeoAncestor(g, g')` at snapshot id | g | closed per snapshot | Gov, Authz |
| Communication | `ForumScope(f)`, `ForumStatus(f)`, `TopicLocked(t)`, `AuthorOf(post)` | f, t, post | closed | Authz |
| Authorization | `Decide(...) → DecisionRecord` (v1.1 §4) | request | — | every PEP |

**Integration events** (existing outbox / inbox; triggers and cache-invalidation signals only):
- Membership: `MembershipFactAppended{p, o, seq}`;
- Organisation: `UnitAffiliationChanged{p, u, seq}`, `UnitStructureChanged{o, seq}`;
- Governance: `TenureChanged{p, s, seq}`, `CommitteeLifecycleChanged{c, seq}`, `JurisdictionChanged{c, seq}`;
- Geography: `GeoSnapshotPublished{country, snapshotId}`;
- policy modules: `PolicyModuleActivated{moduleId, version}`.

The payload is **identifiers + seq only**, so no consumer can use an event as state.

---

## 5. Communication / Forum contract (proposed, first full version)

### 5.1 Aggregates and invariants

| Aggregate | State | Invariants |
|---|---|---|
| **Forum** | scope anchor ∈ {organisation, unit, committee, jurisdiction (committee + GeoUnit set), invitation-only}; status {open, read-only, archived}; active PolicyModule version; moderator source (PositionKind(s) in the anchoring committee, or an explicit ModeratorAssignment) | FO-1 one anchor, same tenant · FO-2 policy versions forward-only · FO-3 archived ⇒ no new topics or posts · **FO-4 rebuild:** deleting all access projections and recomputing yields identical access at every t (IP-4) |
| **Topic** | forum, author `PersonRef`, status {open, locked, hidden} | TO-1 locked ⇒ no posts · TO-2 a hidden topic remains in history |
| **Post** (separate aggregate) | topic, author `PersonRef`, revisions[], moderation state {visible, hidden, removed-for-retention} | PO-1 author immutable · PO-2 edits are revisions · PO-3 hide ≠ delete; deletion is a retention act (XD-8) |
| **ModerationAction** | actor, target, action, reason, DecisionRecord id | MO-1 every action cites a Permit DecisionRecord (class MODERATE: fresh facts only) |
| **Subscription / Invitation** | person, forum, valid time | SU-1 grants nothing; an invitation is a *fact* that a policy may reference (`Invited(p, f)`), never an ACL entry |

### 5.2 Commands by action class (v1.0 §6.5)

| Command | Class | Decided by |
|---|---|---|
| ReadForum, ReadTopic | READ | PDP; cache allowed (read-set + C-AUTHV) |
| StartTopic, Reply, EditOwnPost, Subscribe | CONTRIBUTE | PDP; cache allowed, shorter staleness |
| HidePost, LockTopic, ModerateMember (forum-local mute) | MODERATE | PDP, fresh facts only, DecisionRecord |
| CreateForum, ChangePolicyModule, Archive | ADMINISTER | PDP, fresh, DecisionRecord + policy-lint |

A forum-local mute is a **Forum fact** (`Muted(p, f, [s, e))`), referenced by a forum *prohibition*. It never touches Membership's `Suspended`.

### 5.3 Default policy-module templates (organisations may version them)

```text
ORG forum         read, post ⇐ IsMember(p, o) ;   prohibit post ⇐ Suspended(p, o) ∨ Muted(p, f)
UNIT forum        read, post ⇐ IsMember(p, o) ∧ ∃u' : UnitAffiliated(p, u') ∧ UnitAncestor(anchor, u')
COMMITTEE forum   read, post ⇐ Participates(p, c) ∨ EffectiveTenure(p, s) ∧ committee(s) = c ;  prohibit post ⇐ Muted(p, f)
JURISDICTION      read, post ⇐ IsMember(p, o) ∧ ∃u : UnitAffiliated(p, u) ∧ GeoAncestor(J(c), geo(u))
MODERATE (all)    moderate   ⇐ EffectiveTenure(p, s) ∧ kind(s) ∈ moderatorKinds(f) ∧ committee(s) = owner(f)
                               ∨ ModeratorAssigned(p, f)
```

Guards follow K2:
- a grant needs T;
- `Suspended = U` or `Muted = U` (source unavailable) ⇒ the prohibition is *possible* ⇒ Deny(PROHIBITION_POSSIBLE) for CONTRIBUTE;
- READ may use its cached projection within its staleness limit.

### 5.4 What Forum must never do

- copy membership, tenure or affiliation as state;
- treat a subscription or invitation as access;
- rewrite authorship;
- let a moderator act on a stale authority projection.

### 5.5 Open business questions (forum)

- **BQ-P9:** may former members or former office holders read historical content? Both answers are expressible as module versions.
- **BQ-F1:** are moderators always office holders, or can a forum appoint its own (ModeratorAssignment)?
- **BQ-F2:** may a forum be public (non-members read)? This needs an "anonymous actor" concept in Identity.
- **XD-8:** attribution and retention after membership ends (privacy).

---

## 6. Worked example: a district secretary moderates the district forum

**Facts at t:**
- Membership `IsMember(Asha, Party) = T`, `Suspended = F`.
- Governance: tenure of Asha in position *Secretary* of committee *District-7*; `EffectiveTenure = T`; `JurisdictionContains(District-7, GeoUnit 7) = T`.
- The forum *D7-Discussion* has anchor jurisdiction (District-7) and `moderatorKinds = {Secretary, Chair}`.

**Request:** `HidePost(post 991)` (class MODERATE).

1. The PEP calls `decideNow(Asha, forum.moderate, post 991, t)`.
2. The PDP gathers fresh facts through PIPs. No projection is used (MODERATE). The read set is `{Membership seq 14, Tenure seq 3, Committee seq 9, Jurisdiction seq 2, Geo snapshot 2026-10, ForumPolicy v4, PlatformModule v2, OrgModule v5}`.
3. **Prohibitions (all modules):** `Muted(Asha, D7) = F`; organisation module "suspended members may not moderate" → guard `Suspended = F` → does not bind.
4. **Conditions:** platform "tenant matches" = T.
5. **Grants (ForumPolicy v4 only):** `moderate ⇐ EffectiveTenure(Asha, Secretary@District-7) ∧ Secretary ∈ moderatorKinds ∧ District-7 = owner(D7)` = T.
6. Result: `Permit(GRANT)`. Witness path: `Asha —holds→ Tenure#3 —in→ Secretary —of→ District-7 —owns→ D7-Discussion ∋ post 991`. The DecisionRecord stores `t`, `v = t`, `k`, the read set and the module versions.
7. A month later, Asha's term ends (`Vacate` effective at t+30). The next `HidePost` evaluates `EffectiveTenure = F` → `Deny(NO_GRANT)`. Her earlier posts remain attributed (PO-1). `reconstructDecision(record, AS_DECIDED)` still yields Permit (R-1).
8. If the Membership service is unreachable at t+1: `Suspended = U` → the organisation prohibition is *possibly* binding → `Deny(PROHIBITION_POSSIBLE)` (indeterminate, escalated), never a silent Permit.

---

## 7. Mathematics, statistics and machine learning in this step

| Technique | Use now | Status |
|---|---|---|
| Bounded enumeration (Python oracles) | g9: Governance temporal compatibility; g7 / g8 (v1.1) | Verified within bounds |
| Kleene logic + completion semantics | §5.3 templates are K2-sound by the g8 P1 result; the templates use only literals and conjunctions of the modelled shape | the conjunction case is in g8; **disjunctive grants (COMMITTEE template) are not yet modelled** → TODO |
| **Statistical process control** (new) | monitor the daily proportion of `Deny(PROHIBITION_POSSIBLE)` and `fact_missing` per fact type with a p-chart (3σ limits). A shift signals a PIP outage or a fact type that should be declared closed-world | design; signal only, never changes a decision |
| Clustering / embeddings | map the seven legacy role vocabularies and the four committee models to PositionKind for G-2 migration (the draft's ML item) | advisory, human-verified |
| NLP / LLM rule extraction | candidate forum and committee rules from statutes, with citations, into the rule matrix as Hypothesis | advisory, human-verified |

---

## 8. How far are we from the implementation goal?

- **Gates:** **7 / 30 (23 %)**, unchanged from v1.1 under the strict rule.
  - Gate 14 (Governance contract approved) is still open. The contract now *exists* (draft + AM-1 … AM-9) but is not approved.
  - The forum contract exists as Proposed only; gates 26 and 27 need code.
- **Research and architecture:** converged and now **consistent with the repository's prior research** (the gap from §1 is closed). Temporal compatibility for Governance, guard soundness, cache safety and aggregate safety are each backed by bounded models (g7, g8, g9).
- **Decisions:** none taken. The PO / ARB package is v1.1 §9, plus the new questions BQ-F1, BQ-F2 and BQ-J1.
- **Implementation:** Membership kernel and Geography exist and were verified in v1.1. Governance consolidation, Authorization, Organisation structure and Forum are not built.
- **End to end:** not demonstrated. §6 is a specification of the first acceptance scenario, not a test.

**Interpretation:** the architecture work has reached diminishing returns for research. The next real progress unit is a PO / ARB decision, then the first approved Governance slice.

---

## 9. Remaining TODOs

### P0: Decisions
- Decide in the v1.1 §9 order: XD-1 → XD-4 + XD-7 → RD-1 … RD-5 (with AM-1) and BQ-J1 → XD-2 → XD-3 → XD-5 → XD-6 / XD-8 → XD-9.
- Answer BQ-P1 … BQ-P3 and BQ-P8 (duty end), BQ-P9 (former members reading history), BQ-F1 (who moderates) and BQ-F2 (public forums).
- Approve the Governance participation contract **as the 2026-10-09 draft + AM-1 … AM-9** (no second contract).
- Close the `PBDIGIT-266` security gap on the isolated test database (interim route protection).

### P1: Contracts and verification
- Specify the PolicyModule contract and `policy-lint`; extend g8 to multi-module combination (INV-AUTH-OWN) and to **disjunctive grants** (the COMMITTEE template).
- Extend g9 to capacity κ > 1, several positions, Correct / Void on tenure facts, and delegation chains (duty retention, the draft's question 3).
- Add a JurisdictionAssignment model once BQ-J1 is answered.
- After C-1 acceptance, re-run `g3_g4_access_vacancy.py` filtered by the kernel to promote G-3 (IP-4) from UNCHK.
- Specify each port in the §4 catalogue with its closed / open-world declaration and `seq` exposure.
- Populate the rule and evidence matrix for Groups 1 and 2 from `ELECTION_MANIFESTO.md` (EM-GOV / EM-VOC) and legacy committee limits, with evidence and decision status kept separate.

### P2: Implementation (each slice only after approval)
- Governance: Committee + PositionHoldings + ports + EffectiveTenure (G-1); legacy committee migration with clustering-assisted vocabulary mapping and parity tests (G-2).
- Authorization: minimal PDP with K2 guards, policy modules, action classes, DecisionRecord and replay R-1 (A-1).
- Organisation: structure + UnitAffiliation (O-1); JurisdictionAssignment + geography snapshot ids (J-1).
- Forum: aggregates and commands of §5 through the PDP, FO-4 rebuild test, revocation tests (F-1); moderation, jurisdiction forums, mutes, subscriptions and invitations (F-2).
- The §6 worked example as the first end-to-end acceptance test; audit consolidation and replay (AU-1).

### P3: Continuous assurance
- Re-run g3 … g9 and the DB-free unit suites as a regression gate; score gates only from re-run evidence.
- Introduce the p-chart monitoring of indeterminate denials once the PDP runs (signal only).

---

## 10. Traceability

| Section | Sources |
|---|---|
| §1 | `docs/publicdigit/reviews/2026-10-09-participation-semantic-contract-draft.md` (G-1 … G-5, IP-1 … IP-7, Rev. 1–2) |
| §2 | `models/g9_governance_temporal_compat.py` (run 2026-10-10: 35,113 histories; T-1 10,986; T-3 270; T-6 0 / 5,916 vs 190 / 5,916; T-5 illustrative) |
| §3 | v1.1 §2, §5, §6; g7; committee discovery §1.5 |
| §4 | v1.0 §6.4; draft G-5; ratified Membership act §8 |
| §5, §6 | v1.0 §5.6; v1.1 §1–§4; draft IP-4 |

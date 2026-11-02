# ARB decision pack: organisation and governance (session agenda)

**Date:** 2026-11-02 · **Author:** Claude (DDD architect / requirements engineer; authority: generated)
**Status:** DECISION PREPARATION. It decides nothing, ratifies nothing and authorises no implementation. Each recommendation is the architect's; the ARB decides.
**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0).
**Baseline:** branch `election-flow-updated`, HEAD `54cc0af63`. The DR-07 records carry **uncommitted** updates from the PO-DR07-01 step.

**Method:**
- Each topic points to its **canonical record**; this pack does not copy those records.
- Topics without a canonical decision paper (XD-4, O-14) have a short brief here.
- **Settled and not for re-decision:** PO-DR07-01 (DR-07 paper §15); K7 = K′; BR-13 / 13a / 13-S / 14; the ratified Membership architecture (2026-10-08).

**Readiness labels:**
- **READY**: the ARB can decide now.
- **READY WITH CONDITIONS**: the ARB can decide the stated part; the rest waits for a named input.
- **BLOCKED**: not decidable yet.

---

## 0. Agenda and readiness

| # | Topic | Canonical record | Decision asked | Readiness |
|---|---|---|---|---|
| 1 | **INV-ORG-SCOPE-1** organisation-scoped access | `2026-11-01-organisation-inv-org-scope-1-organisation-scoped-access-decision-record.md` | ratify the PO-approved principle (§1, §1a, §1c) as worded | **READY** |
| 2 | **O-14** vocabulary | governance map §2 (`2026-11-02-governance-domain-discovery-map-roadmap-and-readiness.md:48-75`); brief §2 below | adopt the term meanings and the splits of overloaded terms | **READY** (meanings only; no code renaming) |
| 3 | **DR-07a** governance-organ ownership; ADR-0001 location clause | DR-07 paper `2026-11-02-governance-dr07-committee-ownership-decision-paper.md` §17 (ARB-1…ARB-7) | Governance owns governance organs; treatment of ADR-0001; **relation to frozen BDR-03** | **READY WITH CONDITIONS**: Membership owner's position (paper §16) outstanding |
| 4 | **XD-4** authorisation ownership | frozen Round 49-06 BDR-07; v1.1 XD-4; brief §4 below | confirm BDR-07 for this scope; policy modules owned by resource contexts | **READY WITH CONDITIONS**: structure decidable; permission **content** waits for DR-07a and XD-7 |
| 5 | **INV-AFF-1** affiliation ownership | `2026-11-01-organisation-inv-aff-1-affiliation-ownership-decision-record.md`; evaluation `2026-11-01-identity-affiliation-discovery-evaluation.md` | adopt as **target principle** only | **READY WITH CONDITIONS**: full ratification **BLOCKED** on feasibility (U-1…U-5) and data-protection review |

**Recommendation on scheduling:**
- Hold the session **after the Membership owner has answered DR-07 paper §16.**
- Items 1 and 2 can go to a session earlier on their own.
- One session for all five is efficient only if item 3 has that input.

---

## 1. INV-ORG-SCOPE-1: ratify the approved principle

| | |
|---|---|
| **Question** | Does the ARB ratify INV-ORG-SCOPE-1 as approved by the PO on 2026-11-01 (§1 invariant, §1a platform-level clause, §1c identity access, request context, enforcement expectation)? |
| **Status** | principle **APPROVED by the PO**; ARB act pending (record §1b) |
| **Authority** | ARB ratifies; the PO has approved |
| **Constrains** | every context that holds organisation-specific records: `Owning Organisation = Working Organisation`; fail closed; no silent substitution of the default organisation; a client-supplied organisation id is a request, not authorisation |
| **Implementation evidence** | the record's §2: V-1 met by the ratified Membership kernel; V-4 silent substitution, V-5 379 bypasses, V-6 cross-organisation role cache, V-7 `X-Tenant-Id` all **contradict** it. Stage 1 investigation `2026-11-02-inv-org-scope-1-stage1-static-investigation.md`. These are gaps, **not** closed by ratification |
| **Options** | (a) ratify as worded; open questions stay open. (b) Ratify with §1a extended to the deletion-eligibility check (O-5). This changes the approved wording, so it needs the PO first. (c) Defer |
| **Recommendation** | **(a).** The act **formalises** the approved principle; it does not expand it. Take O-5 to the PO separately |
| **Risks / reversal** | a requirement for cross-organisation platform administration that cannot be expressed as an approved exception (OQ-OS-2) |
| **Exact act** | "INV-ORG-SCOPE-1 (record §1, §1a, §1c) is ratified. OQ-OS-1, OQ-OS-2, OQ-OS-3 and O-5 remain open. No enforcement is authorised by this act." Then update record §1b, register A-1, resumption record |
| **Stays open** | OQ-OS-1 (reference data, with XD-5); OQ-OS-2 (exception catalogue, PO + Security); OQ-OS-3 (bypass classification, separately authorised); O-5 |

## 2. O-14: vocabulary (brief)

**Question:** does the ARB adopt the following meanings and splits as the ubiquitous language of the organisation and governance domain?

**Status:** OPEN. Sources: governance map §2; ratified Membership vocabulary (EM-VOC-001 / 002); ratified act §11; PO-DR07-01; v1.5 package `:60-100`; v1.1 XD-2.

| Term | Meaning (proposed for adoption) | Context | Source | Note |
|---|---|---|---|---|
| **Member** | a Person holding a Membership in an organisation | Membership | ratified (EM-VOC-001) | the word is overloaded in code (registrant role `member`, enrolment enum, committee role, `MemberId`). The language keeps **only** this meaning |
| **Membership** | the enduring Person ↔ Organisation relation (periods, append-only facts) | Membership | ratified | — |
| **OrganisationAffiliation** | access and roles of a Person in an organisation (e.g. User) | Organisation | ratified EM-VOC-002 | not Membership |
| **Governance organ (Committee)** | a constituted body with positions, appointments and authority | Governance (ownership: DR-07a) | PO-DR07-01; §11 "organs" | "Committee" in the language means the organ |
| **Organisational unit** | a branch, chapter, wing or department (structure) | Organisation [candidate] (ownership: O-16) | PO-DR07-01; v1.5 | **not** a governance organ |
| **Unit affiliation** | a Person's belonging to an organisational unit | Organisation [candidate] | v1.1 **XD-2** "UnitAffiliation (Organisation-owned)" | candidate meaning of `CommitteeAssociation` (OQ-DR07-1, Membership owner) |
| **Position** | an office or seat of an organ; outlives its holders | Governance | v1.5 | — |
| **PositionKind** | chair, treasurer, …: the **organisational** meaning of "role" | Governance | v1.5 | split from AccessRole |
| **AccessRole** | an organisation access role (owner, admin, commission) | Organisation / Authorisation | governance map §2 | split from PositionKind; Spatie and dashboard roles are technical, not domain terms |
| **Appointment** | the act opening a tenure, with provenance and authority evidence | Governance | v1.5 | — |
| **Tenure** | a Person holds a Position over `[s, e)`, derived from Appoint / Vacate facts | Governance | v1.5 | — |
| **Mandate** | the scope of authority of a position or organ | Governance | v1.5; frozen BDR-03 ("Appointment owns Mandate truth") | see §3 on BDR-03 |
| **Authority / competence** | the right to perform a governance act, as evidenced to other contexts | Governance (§11 "competence, … authority") | ratified §11 | consumed by Membership acts (§10 "authority evidence") and Adjudication (`AuthorityAssertionPort`) |
| **ElectionMembership** | an Election-owned roster record per (person, organisation, election) | **Election** | **ratified §11** ("Election owns ElectionMembership") | **is not Membership**; no rename needed, the meaning is already ratified |
| **Observer** | split into: (a) election roster role (Election); (b) an associate-membership **type** without voting rights (Membership); (c) the default authorisation label for actors without a role, **not a domain term** | as listed | governance map §2 row "Observer" | — |

**Options:**
- (a) Adopt the table.
- (b) Adopt meanings only for the ratified and PO-decided rows; defer the candidate rows.

**Recommendation:** (a). Rows marked [candidate] keep their ownership open (O-16, DR-07a). Adoption fixes **meaning**, not ownership.

**Exact act:** "The vocabulary table (ARB pack §2) is adopted as the ubiquitous language; ownership marked candidate remains open. No code renaming is authorised." Then update governance map §2 and register O-14.

**Reversal:** statutes (T-08) that use a term in a different sense.

## 3. DR-07a: governance-organ ownership

**Canonical record:** DR-07 paper §3–§8, §14–§17. The ARB request is **§17 (ARB-1…ARB-7)**; it is not repeated here.

**Settled input:** PO-DR07-01 (governance committees are organs). **The ARB is not asked to re-decide it.**

**Outstanding (condition):** the Membership owner's answers M-1…M-5 (paper §16). Nothing is assumed about them.

**Added by this pack (canonical discovery; not cited in the DR-07 paper before):**
- **Frozen Round 49-06 Boundary Decision Register** (`docs/architecture/design/Round49-06_Boundary_Decision_Register.md`; header "IMMUTABLE … changes only by a versioned re-issue … via ADR").
  - **BDR-03** classifies **"Appointment (Authority)"** as a Confirmed BC (Operational) that "owns Mandate truth".
  - **BDR-07** classifies Authorization as a pure resolver that "owns no truth; consumes Mandate".
  - Round 50 (FROZEN per `.claude/MEMORY.md`) makes **Appointment** the producer of `MandateGranted` / `MandateRevoked` (`Round50-05_Event_Catalogue.md:14-15`).
- **Code:** the Authority / Delegation code now sits in `app/Contexts/Governance/Domain/Authority/` (`AuthorityAssignment`, `AuthorityResolver`, `DelegationLifecyclePolicy`).

**Question this adds (ARB-8):** how does option A relate to BDR-03?

| Option | Meaning | Consequence |
|---|---|---|
| (i) Governance **is** the context that BDR-03 names "Appointment (Authority)"; organs, positions, tenure and mandate join it | one context owns organs **and** authority, as §11 says | the BDR is re-issued (v1.x) via ADR to record the name and scope; no change in substance for Mandate ownership |
| (ii) Appointment stays a separate BC owning Mandate; Governance owns organs, positions and tenure only | mandate is split from the organ that holds it | conflicts with §11 grouping "organs, authority"; needs an explicit boundary contract for every appointment |

**Recommendation:** (i). [I] It reconciles the frozen register with ratified §11 without changing who owns Mandate.

**Exact acts** (in addition to paper §17):
- a new ADR that supersedes **only** ADR-0001's location clause;
- the BDR re-issue under (i).

**ADR-0001 is not marked superseded before that act.**

## 4. XD-4: authorisation ownership (brief)

| | |
|---|---|
| **Question** | For the organisation and governance domain, does the ARB confirm that Authorisation **owns no business truth** and decides access from facts owned by other contexts, with policy modules owned by the resource contexts? |
| **Status** | OPEN for this scope. **Existing decisions:** frozen **BDR-07** (Authorization = Supporting Subdomain / Domain Service; "pure resolver; owns no truth; consumes Mandate"); Round49-07 migration plan "No module, Domain Service"; Round50-06 policy category "Authorization" (pre-command, allow / deny). Earlier proposal v1.1 XD-4 (a): own capability context, policy modules owned by resource contexts |
| **Authority** | ARB |
| **Distinctions** | **request** (any actor) · **validate preconditions** (the resource context) · **decide allow / deny** (the authorisation policy for that resource, owned by the resource context, evaluated with a shared resolver) · **record the fact** (the resource context) · **consume authority evidence** (from Governance, e.g. Mandate, Tenure; from Organisation, e.g. AccessRole). An API endpoint or code location does not confer the decision |
| **Options** | (a) confirm BDR-07 for this scope; policy modules owned by resource contexts (INV-AUTH-OWN, governance map §4); whether a separate module exists is a tactical question. (b) A dedicated Authorization context that owns policy semantics and decisions (v1.1 XD-4 (a) read strictly). This conflicts with frozen BDR-07 and needs a BDR re-issue. (c) Each context evaluates its own authorisation with no shared semantics |
| **Recommendation** | **(a).** It consumes the frozen decision instead of creating a second one (ES-005.4) |
| **Consequences** | permissions derive from AccessRole ∪ EffectiveTenure × Mandate × Jurisdiction (governance map, permission row, [P]); **not from Membership alone** (ratified "no Membership → entitlement shortcut") |
| **Dependencies** | the permission **content** needs DR-07a (Tenure, Mandate), O-11 (Jurisdiction), XD-7 (unknown-fact semantics, v1.1 recommends (c)) |
| **Exact act** | "BDR-07 applies to the organisation and governance domain: Authorisation owns no business truth; policy modules are owned by resource contexts; permission content follows DR-07a and XD-7." |
| **Reversal** | evidence that Authorisation must hold state of its own (BDR register: "only on new evidence, e.g. Authorization acquires state") |

## 5. INV-AFF-1: affiliation ownership

| | |
|---|---|
| **Question** | Does the ARB adopt INV-AFF-1 (record §1) as the **target principle**, with feasibility explicitly conditional? |
| **Status** | **PROPOSED; referred to the ARB by the PO on 2026-11-02** (record status line). Not ratified |
| **Wording** | record §1: each organisation owns its affiliation records; sign-in discovery with signed organisation-specific assertions; **no persisted cross-organisation register**, also not via logs / caches / traces / sessions; organisation-context access only; automatic entry only when discovery is complete |
| **Problem addressed** | a central affiliation register would let the platform reconstruct every person's organisations; it would also contradict organisation-owned data under tenancy direction C |
| **Evidence** | evaluation: L2 (discovery) **feasibility not established** (`:5`, `:48`); `:220-225` "do not declare L2 feasible yet … keep INV-AFF-1 as the target principle … decide the fallback explicitly" |
| **Interactions** | account (global, XD-3b/c); affiliation (organisation-local, BR-9 direction); Membership (unaffected: organisation-local already); INV-ORG-SCOPE-1 §1a (discovery is a permitted platform operation; returns only "recognises the person", BR-13-S); K′ (the bootstrap User affiliation's placement, DC-R9, is open) |
| **Ambiguities exposed (not resolved here)** | (1) the assertion mechanism ("signed, organisation-specific identity assertions") is unspecified. (2) Who may authorise an exception ("a separate business and data-protection decision") is not named. (3) The data-protection review required by the record has not happened |
| **Options** | (a) adopt as target principle, with the fallback rule (if L2 is infeasible: L1 under C-4, or L3 with a B1 amendment, decided explicitly). (b) Defer until U-1…U-5. (c) Reject, permitting a central register; this would contradict the principle's purpose and tenancy direction C |
| **Recommendation** | **(a)** as a target only. **Full ratification BLOCKED** until U-1…U-5 (PO + Ops) and the data-protection review |
| **Exact act** | "INV-AFF-1 is adopted as the target principle. Ratification requires feasibility at U-1…U-5 and a data-protection review. No implementation is authorised." |

---

## 6. Not an ARB item: committee statutes (PO action, parallel)

**Why:**
- O-8 (incompatible positions), O-9 (Membership ↔ tenure, does a seat require membership) and O-10 (committee authority, which acts each organ may take) are **business rules**.
- They must come from statutes, not from the ARB or from inference.

**State:**
- not in the corpus;
- **not yet requested** (T-08).
- The recipient is not named in the repository; the PO names it.

**Request text (for the PO to send):**
> For the PublicDigit domain design, please provide the statutes (constitution, by-laws, rules of procedure) of [organisation; preferably the intended operator organisation]. We need them to establish: (1) which positions may not be held together; (2) whether a committee seat requires current membership, and what happens to a seat when membership ends; (3) which organ may decide which acts (admission, exclusion, appointments, budgets) and how far rules may vary per organisation.

## 7. Outside this pack (separate gates, unchanged)

- **DR-07b / O-16:** organisational units, `CommitteeAssociation`, record classification (DR-07 paper §18).
- G-7 production-use verification.
- S1 acceptance; S2–S4.
- V-7.
- PBDIGIT-317.
- T6 contracts: after DR-07a and O-8…O-10.

# Review — Membership Domain Architecture V4.0 + Architecture Blueprint E0a-1→E0a-4

**Type:** brainstorming review (research; **decides nothing**) · **Date:** 2026-10-08 13:05
**Reviewer role:** Senior DDD Architect + Senior Requirements Engineer (`claude-code-session:42e0287e…`, self-declared)

**Reviewed:**
- **[V4]** `20261008_1301_membership_domain_architecture_v4_e0a1_baseline.md`
- **[BP]** `20261008_1303_membership_election_architecture_blueprint_e0a1_to_e0a4.md`

**Checked against:**
- the **adopted** record: `ELECTION_MANIFESTO.md` EM-ENT / EM-EO / EM-FM / EM-SEQ; ADR_20261004_1549 (DECIDED); `PBDIGIT-68` F1 + D-ENT-2;
- the Discovery Part 1 (`docs/publicdigit/reviews/2026-10-08-membership-domain-discovery.md`);
- this folder's 0041 continuation;
- the code.

---

## 0. Verdict

**The conceptual direction is good, and in several places better than Part 1.** The strongest contributions are:
- the six semantic dimensions (§8);
- `[from, until)` with date vs instant decided per fact;
- separate state, decision and record histories;
- "Member is a role or projection, not the core entity";
- *Membership ⇏ ElectionMembership*;
- contracts, not tables;
- bound admission.

**Two problems prevent it from being a "ratified baseline" or a "master architecture" today:**
1. **Governance.** It claims a status nobody has granted.
2. **Canonical discovery.** It was written **without consulting the adopted Election record**. As a result it (a) drops the product that is actually in delivery (Election-Only), and (b) **re-orders and redefines four concepts that a DECIDED ADR already fixes.**

Both are fixable by corrections rather than another version. I agree with V4 §18 that there should be no V5 ontology: **apply a correction list, then start E0a-2.**

| Finding class | Count | Severity |
|---|---|---|
| Conflicts with adopted decisions (must fix) | 5 (D-1…D-5) | 🔴 |
| Internal / formal inconsistencies | 6 (F-1…F-6) | 🟠 |
| DDD modelling concerns | 6 (M-1…M-6) | 🟡 |
| Requirements-engineering defects | 7 (R-1…R-7) | 🟠 |

---

## 1. Conflicts with the adopted record (🔴 must be corrected before anything is called a baseline)

### D-1: Status overclaim
V4 says *"Status: Ratified as E0a-1 baseline"*, *"frozen"* (FC-1…12), *"binding vocabulary"*, *"binding table"*. BP says *"PublicDigit master architecture"*.
- **No ratifying act exists.** There is no PO or ARB decision, date or record.
- Both documents are AI-assisted brainstorm output. The project rule is that AI-generated knowledge is **never authoritative without human review**, and that *"the strongest statement must never exceed the evidence"*.
- "Ratified" here means "the conversation converged". That is a different thing.

**Correction:** set the status to **PROPOSED baseline (brainstorm), not ratified**. "Frozen" becomes "**proposed for freezing**". A PO/ARB act may then adopt the FC list explicitly, row by row, as was done for the Election Manifesto.

### D-2: The Election-Only product disappears
BP's architecture is a single chain: *Person → Membership → … → Vote* (§39, §25). V4 never mentions Election-Only either. That contradicts adopted rules that are **in force now**:
- **EM-EO-001:** in Election-Only mode Organisation Membership is *not required*;
- **EM-EO-002:** a person may become an ElectionMember *directly*;
- **EM-SEQ-001:** Election-Only *is the first implementation phase*. Full Membership is deferred.

As drawn, the master architecture makes Membership a **prerequisite of voting**, which is exactly what EM-EO-001 rules out.

**Correction:** the Selection Contract's **population source** is a dimension with (at least) two values:
- *Membership registry* (Full Membership);
- *Imported electorate* (Election-Only, today's `voter_source_strategy = election_only`).

That is EM-ENT-005: *one entitlement concept; only the route to admission differs*. Draw both routes.

### D-3: The election chain is inverted relative to the DECIDED ADR
| | ADR_20261004_1549 (**DECIDED**, implemented under `PBDIGIT-213`) | BP §19–§23 |
|---|---|---|
| Order | **Registration** (creates the ElectionMembership) → **Admission** → **VotingEntitlement** | Evaluation → **Election Admission** → **ElectionMembership** → VotingEntitlement → Exercisability |
| Admission | *"derived, never stored … No `is_admitted` flag exists"* (ADR:81) | `ElectionAdmissionDecision` as a **stored decision record** (V4 §13, BP §21 "Decision Record") |
| Entitlement | `registered ∧ role=voter ∧ status=active ∧ org match ∧ ¬deleted ∧ admitted ∧ ElectionLifecycle.canVote()` (ADR:37-38). **Lifecycle exercisability is part of entitlement.** | VotingEntitlement and Exercisability as **separate stages** |

These are not cosmetic differences. BP's order means *no ElectionMembership exists before admission*. The ADR's order means *an ElectionMembership exists (registered) and admission is evaluated over it*.

Both can be defended, but **only one can be the model**, and the ADR's is decided and implemented. The ADR itself records the naming debt as **G11** ("admission" in EM-ENT-003 = Registration).

**Correction:** BP adopts the ADR order. "Eligibility evaluation" becomes the step **before Registration**: the selection contract governs *who may be registered*. A stored **refusal** record (bound admission, §21) is compatible with "admission derived", **because what is stored is a refusal ground, not an admitted flag**. State this explicitly.

### D-4: D-ENT-2 / EM-FM-003 is absent
Neither document contains the adopted Full Membership rule: *removal of Organisation Membership automatically suspends the ElectionMembership, distinct from Chief suspension* (EM-FM-003/004/006). BP's exercisability reasons (§23) list "entitlement suspended" generically.
- The chain has **no edge from a Membership change to Election exercisability**.
- Without that edge the architecture cannot express an adopted rule.

**Correction:** add the edge *Membership lifecycle/restriction event → Election exercisability (automatic suspension cause)*. Reference FM-5 (*which act is "removal"*). V4's OC-8 "suspension: state or restriction?" is **the same question on the Membership side**, so link the two.

### D-5: The anonymity invariant is missing from the "frozen" list
BP §36 freezes identity, time, history, integration and election ownership, but **not vote anonymity**. Every diagram draws `… → Exercisability → Vote` as a continuous chain from `Person`. For this product that is the one edge that must be **explicitly broken**: ADR-T11, and root CLAUDE.md (*votes: NO user_id*).

**Correction:** add the invariant **"Vote carries no reference to Person, Membership, ElectionMembership or EligibilityEvaluation; the chain ends at *the act of casting*, not at the vote record."** Draw the Vote node as detached.

---

## 2. Internal and formal inconsistencies (🟠)

| # | Where | Problem | Fix |
|---|---|---|---|
| **F-1** | BP §39: `ElectionMembership ⇒ VotingEntitlement` | False under BP's own §22-23 (entitled ≠ exercisable is fine, but **registered ≠ entitled**). It is also false under the ADR (not yet admitted ⇒ no entitlement) and for `role=candidate` memberships | Reverse it: `VotingEntitlement ⇒ ElectionMembership(role=voter)` |
| **F-2** | BP §39: `VotingEntitlement ⇒ Eligibility` (timeless) | Contradicts BP §20 (`EligibleAtAdmission`), EM-ENT-004 and F1. As written, a dues change after admission would void entitlement | `VotingEntitlement(p,E) ⇒ EligibleAt(p,E,ref_E)`, where `ref_E` is the contract's reference date (0041 INV-SEL-1/2) |
| **F-3** | BP §17: `Eligible(p,E,t) = C_E(F(p,t))` | **`t` is undefined:** evaluation time, registration time or a reference date (*Stichtag*)? This is the single most consequential temporal parameter in the model and is left implicit | Make `ref_E` part of the contract snapshot ❓ |
| **F-4** | BP §17 uses `Member(p)` inside the formula | Contradicts FC-6/FC-7 (*Member is a role, not a core concept*) | `HasMembership(p,O,ref_E)` with explicit dimensions (existence ∧ recognition ∧ effectiveness) |
| **F-5** | V4 §4.4: *"Member = a Person who currently holds a **qualifying** Membership"* | "qualifying" is undefined. Qualifying **for what**? It smuggles eligibility (dimension 6) back into Member (dimensions 1–4), violating V4's own §8 | Member is defined over dimensions 1–4 only, e.g. "existing ∧ recognised ∧ effective now ∧ not terminated". Anything else is eligibility |
| **F-6** | BP top diagram vs §14/§25 | CommitteePosition and ParticipationRestriction sit in **"Membership Extensions"** in one diagram and in **Organisation/Governance** in two others. "Membership Extensions" is **not a bounded context** and has no owner | Delete "Membership Extensions". MembershipType, Obligation, Dues and Standing → Membership context (0041 §6: one publisher per fact). Committee and Restriction → Governance |

---

## 3. DDD modelling concerns (🟡)

**M-1: OrganisationAffiliation is missing.** Both documents go Identity → Membership directly. But the platform's access relationship (`organisation_users` + roles: owner, admin, election officers) **is neither identity nor membership**. Election-Only voters (D-2) and election officers (Chief and Deputy authority, ADR D2) live there. Without it, either Membership absorbs access (FC-2 violated in spirit) or Identity does. Part 1 §5 named it. **Add it to the Organisation/Governance context.**

**M-2: Placing MembershipType outside the aggregate is asserted, not derived.** V4 naming rule 4 says *"Do not put MembershipType, obligations, dues, or standing into the Membership aggregate"*, while §4.6 says boundaries follow from invariants. There is at least one candidate **cross-cutting invariant**: the type's duration determines the **effective interval** (`[legacy-impl]` renewal extends by `type.duration_months`). If interval length is derived from the type at admission or renewal, the *type assignment of a Membership* is probably **inside** (as a value: `typeRef + assignedAt`), while the *type definition* (fees, duration policy) is **outside** (configuration). **Re-derive this in E0a-2; don't freeze it as a naming rule.**

**M-3: Proportionality.** IdentityResolution (merge and split), biometric evidence, bitemporal histories for every fact, constitution versioning and an Authority aggregate form a *national-registry*-grade model. PublicDigit serves associations and parties, and today has a `users` table with e-mail identity. The brainstorm's own rule applies: *"model the complexity the business actually has"*.
- **Recommendation:** keep **decision history** and **valid-time vs recorded-time** (election integrity needs both).
- Mark **IdentityResolution**, **multiple effective intervals**, **record history** and **constitution versioning** as *option value*: architecturally allowed, but **not built until a requirement names them** (R-4).

**M-4: Identity is cross-tenant, and that is a privacy boundary.** `PersonId` is platform-wide (Q-O3: memberships in several organisations). The platform is **multi-tenant with hard isolation** (root CLAUDE.md). A cross-tenant Person means Organisation A could learn that a person is also in B. **Missing invariant:** *a tenant can see only the Memberships of its own organisation; cross-tenant correlation of a PersonId is never exposed to a tenant.* This belongs in the frozen list beside anonymity.

**M-5: VotingEntitlement as an "aggregate" (BP §32).** The ADR defines it as a **derived predicate**, never stored or cached for `canVote()`. BP lists "VotingEntitlement Aggregate/value model". **Correction:** it is a derived policy result (a query), not an aggregate.

**M-6: "Constitution" is overloaded.** In V4, Constitution = the organisation's statutes (O-2). In the codebase and the governance record, "constitution" and "constitutional" mean **PublicDigit's own governance rules** (the Election Constitution, `ConstitutionalArbitrationKernel`, `CommitteeConstitution`, … 277 occurrences in `app/`). **Use "Organisation Statutes" (Satzung) for the tenant's document.** Otherwise the ubiquitous language collides on its most important word.

**Also noted, without objection:** FC-5 (MembershipId independent of Person and Org) and the M-7 non-overlap analysis (synchronous vs reconciled) are good DDD reasoning. The five-mechanism table is the right way to argue it.

---

## 4. Requirements-engineering review (🟠)

**R-1: The question IDs collide; traceability is broken.** V4 §11 defines **MQ-1…MQ-4**. Discovery Part 1 already defines **MQ-0…MQ-10** with **different meanings**:

| ID | V4 | Part 1 |
|---|---|---|
| MQ-1 | existence | when is a person an active Member (≈ same) |
| MQ-2 | existence / recognition / effectiveness | approval always required? |
| MQ-3 | multiplicity / overlap | **who holds membership authority** |
| MQ-4 | lifecycle / suspension | paid vs exempt vs waived |

In the conversation "answer MQ-3" now means two different things. **Fix:**
- renumber V4's questions as **EQ-1…EQ-4** (E0a-2 questions);
- map them: EQ-1 ⊇ MQ-1; EQ-4 ⊇ MQ-6/MQ-7 + V4 OC-8; EQ-2 and EQ-3 are new;
- keep **SQ-1…SQ-6** (0041) and **FM-1…FM-15** as they are.

**R-2: "The organisation" is singular, but the platform is generic and multi-tenant.** V4 tags many items *"Domain truth — must come from the organisation"*. The PO asked for a **generic** model. For a platform there is **no single organisation to ask**, so each open question must first be classified:

| Class | Who answers | Example |
|---|---|---|
| **Platform invariant** | PO / ARB, once | Membership ≠ Election participation; anonymity; `[from, until)`; non-overlap (probably) |
| **Platform canonical model with tenant parameters** | PO defines the model; tenant sets the values | lifecycle states fixed by the platform; *whether* expiry is automatic, grace days, approval required per type |
| **Tenant statute** | the organisation, recorded as evidence | who approves; membership types; criteria vocabulary limits |

Without this classification, E0a-2 cannot finish: it would wait for "the organisation" to answer questions only the PO can answer, or the reverse. *(Part 1 §7.1 started this split; V4 dropped it.)*

**R-3: Ambiguous terms remain in "binding" text.**
- "qualifying" (F-5);
- "recognition" (V4 itself warns about `recognitionStatus` but uses "Recognition" as a dimension without a definition test);
- "constitution" (M-6);
- "admission", with 4 context meanings (V4 rule 9 is good), still collides with the ADR's G11.

A binding glossary needs a **definition + a test** ("X is recognised iff …") for each term.

**R-4: No requirement is stated.** Both documents are **ontologies**. Nothing in them is testable as a stakeholder need: no actor, goal or acceptance criterion. That is acceptable for E0a-1. It becomes a defect if E0a-2 continues the same way.
- **Recommendation:** every E0a-2 answer produces at least one requirement card (Part 1 §7.2 format, extended per 0032 §17: Context, Trigger, Preconditions, Postconditions);
- every model element must trace to a card (**no card, no element**, which also enforces M-3's proportionality).

**R-5: There is no link to the delivery backlog or to the legacy system.** There is no mapping of V4 concepts to:
- the existing tables, aggregates and stories (`members`, `Domain/Member/Member`, `MembershipLineage`, `OrganisationUser`, `PBDIGIT-05/06/08/49/68/202/209/211/257`);
- the Phase-A conformance evidence (three status vocabularies, two fee fields, an approved member who can never vote, `manageMembers` undefined).

The architecture is drawn on a blank page, but the system is not blank. **Add a "legacy → target" mapping table in E0a-2.** It is also the input to any strangler-fig migration later.

**R-6: Decision gates lack owners and exit criteria.** G1–G5 list questions, but name no **decision owner** (PO / ARB / tenant), no **exit criterion** (what evidence closes the gate?) and no **dependency order**. V4 §18 gives an order for MQ-1→4, but G5 (authority) is called "may remain provisional", while Part 1 ranks authority (its MQ-3) **priority 1**. MS-3 ("state change is authorised") cannot be designed without it. **Give each gate an owner and an exit criterion. Move G5 up, or state why authority can wait.**

**R-7: Earlier open questions are silently dropped.** SQ-2 (bound vs discretionary admission) appears in BP §21 as a *recommendation adopted into the architecture*, yet it is a **PO integrity decision** that has not been taken. Likewise SQ-1 (the effect of a fact changing after admission), SQ-5 (who adopts the contract) and SQ-6 (criteria guardrails) do not appear. **Carry them forward explicitly as open, not as resolved by the blueprint.**

---

## 5. What I would accept now (as PROPOSED, for PO/ARB adoption row by row)

| Candidate principle | Source | Note |
|---|---|---|
| Person ≠ Membership ≠ Member; Member is a contextual role | V4 FC-1/2/6/7 | with the F-5 definition fix |
| Membership is an entity with its own id, referencing PersonId and OrganisationId | V4 FC-3…5 | |
| Six semantic dimensions; `ACTIVE` never stands for recognised, in good standing or eligible | V4 §8 | **the most valuable single artefact here** |
| `[from, until)`; date vs instant per fact; valid vs recorded time | V4 TC-01, §5.3 | the tenant time zone is needed for "civil day" ❓ |
| State, decision and record histories are separate | V4 FC-10 | build decision + valid/recorded time first (M-3) |
| No persistence coupling between contexts | V4 FC-12, BP §27 | = 0041 / Part 1 O-1 (candidate ARB principle) |
| Membership ⇏ Eligibility ⇏ ElectionMembership; MembershipType ≠ voting right | BP §1, §12 | consistent with PI-1…4 |
| Selection contract, versioned and frozen per election | BP §30 | as the **generalisation of `voter_source_strategy`** + ADR D4 lock (0041 §4) |
| Explainable evaluation (per-criterion result + source version) | BP §29 | |
| Bound admission | BP §21 | **only after the PO decides SQ-2** |
| **+ added by this review:** vote anonymity; tenant isolation of PersonId; two population routes (D-2) | D-5, M-4, D-2 | |

## 6. Correction list (apply to V4/BP before E0a-2; no V5 rewrite)

1. **Status:** PROPOSED, not ratified (D-1).
2. **Two population routes:** Election-Only drawn (D-2).
3. **ADR order:** Registration → Admission (derived) → Entitlement (incl. `canVote`); refusal record ≠ admitted flag (D-3).
4. **EM-FM-003 edge:** link to V4 OC-8 and FM-5 (D-4).
5. **Anonymity** and **tenant-isolation** invariants frozen (D-5, M-4).
6. **Formal fixes:** F-1…F-5. **Delete "Membership Extensions"** (F-6).
7. **OrganisationAffiliation added** (M-1). **"Organisation Statutes"** replaces "Constitution" (M-6). VotingEntitlement is derived (M-5).
8. **Renumber questions:** V4 MQ → EQ-1…4; publish one **consolidated question register** (MQ, EQ, SQ, FM) with the owner class from R-2.
9. **Gates:** an owner and exit criteria for G1–G5 (R-6).

**Then E0a-2 starts.** Its first decisions are EQ-1 (existence), MQ-3 (authority) and SQ-2 (bound admission), since these three are prerequisites for almost everything else. It produces requirement cards and a legacy→target mapping as it goes (R-4, R-5).

**Traceability:** V4 §0, §1, §4.4, §4.6, §8, §11, §12, §13 · BP §1, §17, §19–§23, §25, §30, §32, §36, §39 · `ELECTION_MANIFESTO.md` EM-ENT-004/005, EM-EO-001/002, EM-FM-003/004/006, EM-SEQ-001 · ADR_20261004_1549 :37-38, :63, :81, :129, :190 (G11) · ADR-T11 · Discovery Part 1 §5, §7 · 0041 §2, §4, §6, §9.

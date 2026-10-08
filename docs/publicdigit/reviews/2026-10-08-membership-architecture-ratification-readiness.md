# Membership Architecture: Ratification Readiness

**Type:** Final adversarial closure review → ratification-ready package for the **Principal Reviewer** · **Date:** 2026-10-08 · **implements nothing; changes no ADR or adopted terminology**
**Commission:** *"PublicDigit Membership — Final Ratification Readiness / Principal Reviewer Gate"* (2026-10-08)
**Under review:** `2026-10-08-e0b-membership-architecture-finalization.md` (**E0b**: CONDITIONAL-FINAL; Architecture D + B′; INV-9)
**Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)

> **Labels:** ADOPTED · PROVEN · PROVEN UNDER ASSUMPTIONS · FINITE VERIFIED · COUNTEREXAMPLE · ARCHITECTURAL DERIVATION · BUSINESS DECISION REQUIRED · IMPLEMENTATION CHOICE · OPEN RISK.
>
> **Business rules:** *"Recommended — awaiting PO confirmation"*. **Architecture:** *"Proposed for Principal Reviewer ratification"*. **Nothing here is DECIDED** beyond the adopted corpus.
>
> **New computation:** `ratify4.py`: **444,604** canonical histories of ≤ 5 lifetime-affecting facts (ADMIT, END, ANNUL_END, CORRECT_END, CORRECT_START, ANNUL_ADMIT) over valid time 0–3, under the policies refuse / void / supersede / none. Counterexamples are conclusive; their absence is a bounded check.

---

## 1. Executive Verdict

**RATIFIABLE**, for **architecture** ratification. The **business confirmations** listed in §29 stay open; the commission's quality gate allows that, as long as everything remaining is explicitly a business or downstream decision.

**One E0b recommendation is falsified and replaced in this review:**
- **COUNTEREXAMPLE:** E0b's Q-ANNUL-RE "merge = **void** the later period" **erases membership the later period legitimately provided**.
  - Example: Admit → End → re-Admit → **Correct the End to a later date** → the overlap is only partial, yet voiding the later period removes coverage after the corrected end.
  - 782 of 444,604 histories.
- **It also misuses `Void`.** That later admission was **valid when made**; it did not "never exist".
- **Replacement (proposed): "supersede-by-trim".** The reinstated or enlarged period absorbs exactly the overlapping portion of the other period. The other period **keeps its identity**, is trimmed (or marked *superseded* if fully covered), and **no reference is ever rewritten**.
  - **0 membership losses, 0 disjointness violations, 0 splits needed** (verified).
  - **Refuse** (no reversal while an overlap would arise) is also clean, and is **architecturally the safest**.
- **The choice between refuse and supersede is a business decision** (§7).

**With that repair:**
- no unresolved architectural contradiction remains (§23);
- every invariant has a proof or explicit assumptions (§2, §26);
- every known counterexample is addressed;
- D and B′ remain justified;
- downstream effects of reversal are **bounded** to one explicitly downstream policy: **fee re-attribution for a superseded portion** (Finance / E0b-S).

## 2. What is Actually Proven

| # | Statement | Proof sketch | Assumptions |
|---|---|---|---|
| P1 | `Ended ⇒ ¬InForce` | definition of InForce | — |
| P2 | lapse never implies an ending | induction: only End facts create an end; Annul/Correct/Void act on End facts; the passage of time adds no fact | Ended is defined only from End facts |
| P3 | **KnownAt is stable:** for τ₁ < τ₂ ≤ now, `KnownAt(φ,t,τ₁)` never changes after τ₂ | `F_τ₁ = {f : recordedAt(f) ≤ τ₁}` is immutable under append-only recording. Resolution is a deterministic function of `F_τ₁` | append-only; deterministic resolution rules |
| P4 | **INV-9 (period lifetimes pairwise disjoint)** | induction over facts: Admit (g1: all existing lifetimes end ≤ v) · End / Correct-shrink (subset) · Annul_End / Correct-enlarge / Correct_Start-earlier (g2: refuse, or supersede-by-trim removes exactly the overlap) · Annul_Admit / Void (removal) · Renew / ChangeType / Suspend / Restore (no lifetime change) | **g1 and g2 enforced synchronously within one (p,o) aggregate** |
| P5 | I5 (≤ 1 non-terminal period at any t) | pairwise-disjoint intervals contain any t at most once; non-terminal at t ⇒ t ∈ lifetime | INV-9 |
| P6 | **supersede-by-trim preserves membership coverage** | `trim(q) = q \ L_new`; the removed part ⊆ `L_new`, so the union of live lifetimes is unchanged or enlarged | trim is the set difference; g1 held before |
| P7 | trim never needs a split | g1 makes later periods start ≥ the earlier ones' ends; an enlarged lifetime extends contiguously from its own start or end, so it cannot lie strictly inside another period | g1 |
| P8 | no lapse-gap erasure; term segments never overlap; segment derivation is deterministic | a new segment starts at `max(b_last, r) ≥ b_last` | the segment-opening rule (§10) |
| P9 | the holder / organisation of a period never changes | no fact kind changes them; a wrong holder → Void + a new Admit | the Void rule |
| P10 | number uniqueness cannot be enforced inside a (p,o) aggregate | uniqueness quantifies over all persons of an organisation | — |
| P11 | IsMember is partition-invariant (A, B, D groupings) | identical segments and targeting under every grouping | a shared targeting / acceptance rule |
| P12 | **D is the smallest synchronous boundary for INV-9** | g1 and g2 quantify over all periods of (p,o) | **synchronous enforcement is required** (business, §16) |

## 3. What is Computationally Verified (bounded)

| Run | Histories | Result |
|---|---|---|
| E0a-3 `theory.py` | 137,256 | IsMember partition invariance; lapse ≠ end |
| E0a-4 `stress.py` | 101,040 | **counterexamples:** backdated facts rewrite the past; admission date / tenure differ A vs B; I5 broken by Annul/Correct after re-entry |
| E0b `final2.py` | 153,920 | interval g1/g2 → I5 0 violations; lapse gaps visible (5,831); without backdating, only Annul/Correct rewrite the past |
| **this review `ratify4.py`** | **444,604** | **V1 disjointness:** 0 violations under refuse / void / supersede (3,573 without g2) · **supersede-by-trim:** 0 membership losses, 0 splits · **void (E0b merge):** 782 losses (**COUNTEREXAMPLE**) · period ids never lost under any policy |

## 4. What is Architecture (proposed for Principal Reviewer ratification)

- Architecture D (one root per (p,o)) + the B′ structure;
- INV-9 with interval g1 and g2;
- **supersede-by-trim and refuse as the only admissible reversal policies** (void rejected);
- the facts model with provenance;
- three clocks;
- append-only facts;
- term segments;
- authority evidence snapshot;
- RestrictionSet;
- number uniqueness outside the aggregate;
- sections outside the root;
- no Event Sourcing requirement;
- the published-fact boundary to Election, Finance and Audit.

## 5. What is Business Decision

All of §29. **None** of them changes the architecture's structure.

---

## 6. Final Canonical Model

```text
Organisation Membership (= EM-VOC-001 "Member" aggregate; one per (Person, Organisation))
  MembershipId · PersonRef · OrganisationRef · MembershipNumber (credential; org-unique outside)
  Periods  (lifetimes [start,end) pairwise disjoint — INV-9)
    Period: PeriodId · lifetime · status ∈ {live, superseded, voided, annulled} (derived from facts)
            term segments · type segments · restriction intervals
  Facts (append-only): kind · effectiveAt · decidedAt? · recordedAt · provenance · target?
    kinds: Admit · Renew · ChangeType · Suspend · Restore · End(kind) · Annul(target) · Correct(target,values) · Void(target)
  Guards: g1 (Admit: no existing lifetime extends beyond v) · g2 (any lifetime-enlarging fact: refuse | supersede-by-trim)
Derived only: Recognised · Effective · Lapsed · Ended · Suspended · InForce · IsMember · CurrentPeriod · CurrentType ·
              admission/tenure predicates (§18) · TrueAt / KnownAt
```

## 7. Identity Semantics (B′)

| Identity | Meaning | Persists across re-entry? |
|---|---|---|
| Person (PersonId) | the human (Identity context) | yes |
| Affiliation | access / role in the organisation (EM-VOC-002) | not necessarily; **INV-7:** the Membership survives its loss |
| **MembershipId** | the person's enduring membership record with this organisation | **yes** |
| **PeriodId** | one admission-to-ending episode (the legal membership relationship) | **no** (new per admission) |
| MembershipNumber | an organisation-issued credential on the root | **yes** (recommended D-ID.2) |

**Does B′ give the correct *meaning* in each scenario?**

| Scenario | Meaning under B′ | Correct? |
|---|---|---|
| first admission | a new Membership + Period 1 | ✓ |
| lapse / renewal | the same period; a term gap; a new segment | ✓ (a lapse is not an ending) |
| resignation / exclusion | Period ends (End kind) | ✓ |
| suspension / restoration | a restriction interval in the period | ✓ |
| re-entry (even after many years) | same Membership, a **new Period** ("returning member") | ✓ (a legal new admission; the record continues) |
| correction of the original admission | a Correct fact; g2 if it enlarges | ✓ |
| annulment after re-entry | §8: refuse, or supersede-by-trim; both periods keep their ids | ✓ (after repair) |
| historical migration | attested Admit/End facts in the Membership | ✓ |
| **person merge** (duplicate accounts) | two roots for one real person, so the merge must **combine roots** (re-parent periods; INV-9 re-checked across them) | **OPEN RISK:** representable, but it needs an Identity-resolution act (deferred; DR proportionality) |
| **organisation merge / split** | the (p,o) changes, so **new roots** in the target organisation, with attested provenance and a cross-reference | ✓ (INV-1 kept; history stays with the old root) |
| membership number | on the root; retained | ✓ |
| election references | Election keys on the person; PeriodId only if Q-FM-MAP option A | ✓ |
| financial references | a fee references its PeriodId | ✓ (§8 for superseded periods) |
| committee tenure | a named tenure predicate (§18), chosen by the committee rule | ✓ |

## 8. Reversal semantics and references to affected periods (critical issue)

**Q-ANNUL-RE options** (trigger: End(P1) → re-Admit P2 → a reversal that enlarges P1):

| Aspect | **R: refuse** | **M′: supersede-by-trim** (replaces E0b's void) | **O: allow overlap** |
|---|---|---|---|
| identity | both kept | both kept; P2 trimmed or superseded | both kept |
| legal history | the appeal outcome cannot be fully reflected while P2 exists (an organ would end P2 first, then annul) | the reinstatement reflected; P2's covered part absorbed | contradictory (double membership) |
| fees / dues | unchanged | P2's fees for the superseded portion: **re-attribution policy needed (downstream)** | double obligations |
| number | unchanged (root) | unchanged | unchanged |
| audit | clean | the derivation is explicit (the reversal fact + the deterministic trim) | — |
| committee tenure | unchanged | recomputed from the lifetimes | ambiguous |
| ElectionMembership | unaffected (person-keyed) | unaffected (person-keyed); only a future period-bound election reference (Q-FM-MAP A) would need a rule | unaffected |
| references to a PeriodId | never invalid | **never rewritten:** P2 stays resolvable with status *superseded* / trimmed | — |
| INV-9 | holds | holds (P4, verified) | **violated** |
| replay / correction | simple | deterministic | — |
| **architecturally safest** | **yes** | safe (verified) | no (rejected) |

**Business decision:** R or M′ (BUSINESS DECISION REQUIRED; *"Recommended — awaiting PO confirmation"*: **M′**, because it reflects a successful appeal without forcing a second organisational act). **O is rejected by the architecture**: it breaks INV-9.

**References to a period that is reversed or superseded (resolution):**
- **A PeriodId is never deleted, re-pointed or rewritten.** Every external reference stays a valid historical reference.
- **Fee(P2)** stays attached to P2. Whether a fee for a superseded portion is refunded, credited to P1 or kept is **downstream policy** (Finance / E0b-S). Membership publishes the supersession so that policy can act.
- **ElectionMembership** references the person, not a period. It is **unaffected**. If Q-FM-MAP option A later binds elections to a PeriodId, that track must define the rule for superseded periods. This is bounded and deferred.
- **"Superseded" ≠ "never existed".** P2 existed and was valid when admitted; later facts reduced its effect.

## 9. Fact / Provenance Model

| Operation | What happened historically | What changes | Identity | Past truth affected? |
|---|---|---|---|---|
| **End(kind)** | the membership period was ended (prospectively, or effective at a date) | the lifetime end | kept | only if backdated |
| **Annul(target)** | a **valid legal decision** (End, Admit, Suspend) was **set aside** by a later decision (appeal, challenge) | the target's effect is removed | kept (target marked annulled) | **yes** (KnownAt shows the change) |
| **Correct(target, values)** | the **record** of a real event had wrong values | the target's values | kept | yes, if the values are temporal |
| **Void(target)** | the record **never corresponded to any domain event** (wrong person, duplicate, test data) | the target is removed from resolution | kept as a voided record | yes |
| Restore | the end of a **suspension** only | the restriction interval ends | kept | no |
| Re-Admit | **not a kind:** an Admit when ended periods exist (new PeriodId) | a new period | new period id | no |
| Supersede | **not a fact:** the derived consequence of g2 under M′ | the other period trimmed / superseded | kept | yes (via the triggering fact) |

**Void Admit ≠ Annul Admit:**
- **Void** = "this record never reflected a membership" (a data nullity).
- **Annul** = "a membership decision existed and was legally set aside".

Both remove the lifetime from resolution, but the **reason class and the legal narrative differ**, and audit must preserve which one occurred.

**Provenance** (a domain concept, not mere metadata: it determines validity and consent evidence):
- `decision{actor, authoritySnapshot, personDeclaration?}`
- `rule{ruleRef, version}`, for system acts such as a tenant's lapse-to-end rule or ex-officio / statutory membership (**added in this review**)
- `attested{sourceKind ∈ {legacy import, migration, external registry, verified historical record}, sourceRef, importer}`

Three variants suffice. Finer source detail is an attribute, not a type.

## 10. Period / Term Segment Model

- **Term segments are a domain concept:** the granted membership term ("membership year"), not a persistence trick. Their *list* is the history of grants.
- **Rules:**
  - **Admit** opens `[v, v + d(type@v))`;
  - **Renew at r** opens `[max(b_last, r), +d(type@start))`;
  - type changes, suspension, End and restoration **do not** open or close term segments;
  - a backdated Correct of an Admit/Renew date **re-derives** segments (the facts are unchanged; KnownAt shows the change).
- **Proven (P8):** no lapse-gap erasure; no overlapping segments; deterministic derivation; corrections never mutate facts.

## 11. Lifecycle / Restriction Model

**`Suspended(m,t)`:** a decided, membership-scoped restriction of **all membership rights** by an authorised organ (a Suspend / Restore interval). **Membership owns only this fact.**

| Effect | Owner / nature |
|---|---|
| `IsMember`, `InForce` | **architecture** (S2: unchanged, *recommended*) |
| member count | **organisation policy / statute** |
| dues | **statute / organisation policy** (E0b-S) |
| standing | E0b-S (derived elsewhere) |
| committee participation | **Governance rule** |
| quorum / assembly attendance | **statute / Governance rule** |
| services | **organisation policy** |
| election eligibility | **Election rule** (criteria may require ¬Suspended) |
| voting exercisability | **Election** (EM-FM-003/FM-6, deferred; EM-GOV-003 keeps the causes distinct) |

**RestrictionSet:** Membership publishes `Suspended`. Governance publishes its bans. Standing-derived restrictions come from E0b-S. **No one generic "suspended".**

**D-TERM-1, the non-payment chain (where each decision occurs):**

```text
fee unpaid (Fee fact, Membership context)  →  standing = not compliant (derived, E0b-S)
   →  optional restriction (derived from standing by a statute rule; not a membership state)
   →  optional End(kind = non-payment)  ← ONLY by an explicit End fact:
        provenance decision (organ) or rule (tenant's declared lapse/non-payment rule, versioned)
```

**Finance never ends a membership. Lapse never ends a membership** (P2). Only an End fact does.

## 12. Recognition / Consent

| Requirement layer | Content | Status |
|---|---|---|
| **Domain requirement** | an Admit records its basis and the evidence of the person's declaration, **or** the provenance that substitutes for it | ARCHITECTURAL DERIVATION |
| **Legal requirement** | whether membership can be imposed (freedom of association; association law) | **legal validation outside the current corpus** |
| **Organisation statute** | ex-officio members, automatic membership (for example by office), honorary appointment | tenant rule |
| **PublicDigit product rule** | Q-CONSENT: native admission requires a declaration unless a statutory/rule basis applies | BUSINESS DECISION REQUIRED |

| Path | Basis / provenance | Supported without model change? |
|---|---|---|
| application | decision + declaration = application | ✓ |
| invitation + acceptance | decision + declaration = acceptance | ✓ |
| direct add | decision + declaration evidence (or **refused** if the product rule requires it) | ✓ |
| import / migration | attested | ✓ |
| **statutory / automatic membership; membership through an organisational role** | **rule** provenance (ruleRef, version) + the triggering fact | ✓ (via the `rule` provenance added in §9) |

**Conclusion:** PublicDigit can support **different legal bases without changing the Membership model**. The basis and provenance carry the difference. Which bases are valid for a tenant is **statute / legal** and stays outside the architecture.

## 13. Authority

**Snapshot content:** `{actor, claimedCapacity, ruleRef, policyVersion, checkResult, checkedAt}` + the fact's own `decidedAt`, `effectiveAt`, `recordedAt`.

| Stress | Outcome |
|---|---|
| changed statutes / roles | the historical fact is explained by its snapshot (ruleRef + version), with no lookup of today's Governance |
| dissolved organs | the snapshot names the capacity as it was |
| revoked permissions | do not retroactively invalidate a fact. Invalidation requires an Annul / Void with a reason |
| later discovery of an unauthorised act | **Annul** (if the decision existed but lacked competence) or **Void** (if no decision existed), with the reason |
| duplication of Governance rules? | **no:** only references + version + result are stored, never rule content |

## 14. Membership Type

- **MembershipType** (definition) is configuration.
- **Type segments** belong to the period.
- **ChangeType** is a fact.
- The **duration** is read at segment opening.
- The **fee** is snapshotted by the Fee aggregate.
- **Classifications** are readable by consumers.
- **A type never grants a VotingEntitlement** (ADOPTED EM-ENT-001/007).
- The `grants_voting_rights` disposition is BUSINESS DECISION REQUIRED.

## 15. Membership Number

| Concept | Model |
|---|---|
| **identifier** (technical) | MembershipId / PeriodId (never the number) |
| **credential / business number** | MembershipNumber on the root (recommended: retained across re-entry) |
| **allocation** | an organisation-level allocator or an imported value (IMPLEMENTATION CHOICE) |
| **uniqueness** | per organisation, **outside the aggregate** (P10); recommended N-U |
| **history** | changes are Correct facts on the root |

| Stress | Outcome |
|---|---|
| historical numbering | imported as attested values |
| type prefixes | not supported by default (a type change never changes the number). A tenant needing them is a deferred variant |
| re-entry | kept |
| migration | carried with attested provenance |
| organisation merger | collisions → renumbering as Correct facts (the history keeps old numbers) |
| organisation split | new roots in the new organisation carry the number (attested) |
| deleted affiliation | the root and number persist (INV-7) |
| duplicate imported numbers | refused by uniqueness → manual resolution |

## 16. Aggregate Boundary

| Variant | Description | Valid when |
|---|---|---|
| **D1** | one root per (p,o) | **INV-9 must hold synchronously** (never even transiently violated) |
| D2 | period aggregates + external coordination | eventual reconciliation of overlaps is acceptable |
| D3 | a root + separate period aggregates | same as D2 (the root becomes a coordinator) |

**Is synchronous enforcement required?**
- **No adopted rule demands it.**
- The harm of a transient double membership is real but limited: double fees, wrong reporting, ambiguous committee tenure. Election is person-keyed, so it is unaffected.
- Write frequency per (p,o) is very low, so synchronous enforcement is cheap.
- **Requirement:** *"a person never holds two overlapping membership periods, even transiently."* **BUSINESS DECISION REQUIRED** (*Recommended — awaiting PO confirmation: yes*). **D1 is minimal under it** (P12). Migration convenience is not used.

## 17. Invariants

INV-1 holder constant (P9) · INV-2 (P1) · INV-3 lapse ≠ end (P2) · INV-4 conditions derived · INV-5 three clocks + provenance · INV-6 append-only (P3) · INV-7 history survives an affiliation change · INV-8 partition invariance (P11) · **INV-9 lifetimes disjoint (P4)** · I5 (P5) · INV-10 number unique per organisation (business; outside) · INV-11 no entitlement from Membership (ADOPTED) · INV-12 lapse gaps preserved (P8) · INV-13 Admit validity: basis + declaration or substitute provenance (business + derivation) · **INV-14 (new):** a PeriodId is never deleted or rewritten; reversal effects are statuses derived from facts (P6, verified).

## 18. Formal Predicates

As E0b §16 (the inputs, the time parameter, and the KnownAt variant), plus the **Q-TEN set**. Each is derived; none is stored; consumers must name the one they use:

| Predicate | Definition | Needed by |
|---|---|---|
| `FirstAdmissionDate(p,o)` | min start over the non-voided periods | reporting, history |
| `CurrentAdmissionDate(p,o,t)` | start(CurrentPeriod) | Membership, display |
| `CurrentPeriodDuration(p,o,t)` | t − start(CurrentPeriod) | display |
| `ContinuousMembershipDuration(p,o,t)` | length of the maximal InForce interval ending at t (a lapse gap breaks it) | **committee rules / election criteria** (statute chooses) |
| `CumulativeMembershipDuration(p,o,t)` | measure of {s ≤ t : IsMember(p,o,s)} | **committee rules / election criteria** (statute chooses) |
| Joiner / Returning | first period / a period with an earlier ended one | statistics |

**No ambiguous `member_since` / `tenure` field.** A UI "member since" label must name its predicate (recommended default: `CurrentAdmissionDate`, with `FirstAdmissionDate` shown).

## 19. Commands and Guards

As E0b §17, with these changes:
- `Annul` / `Correct` apply g2 with policy **R or M′** (never void);
- `Void` is for nullity only;
- `rule` provenance is added for system acts (lapse-to-end rule, statutory membership);
- **person merge** is an Identity-resolution act (deferred).

## 20. Election Integration (unchanged)

- Election-Only → ImportedVoterRegistry.
- Full Membership → MembershipRegistry.
- Membership ≠ ElectionMembership ≠ VotingEntitlement. Exercisability is part of the entitlement (`canVote`, ADR_20261004_1549).
- **Published:** IsMember, Suspended, CurrentType, the named tenure predicates, standing (E0b-S), End/Suspend events (EM-FM-003, deferred), PeriodId only if Q-FM-MAP option A.
- **Membership never computes entitlement.** ADR-T11 preserved.

## 21. Finance Integration

- Membership owns the fee facts (the Fee aggregate references PeriodId) and **publishes** dues compliance (E0b-S).
- Finance owns the accounting projection (Conformist, as implemented).
- **Bounded downstream decision:** the re-attribution of fees for a **superseded portion** of a period (M′ only). The policy belongs to Finance / E0b-S. Membership supplies the supersession facts.

## 22. Audit Integration

- The **membership fact history is authoritative**: append-only, with provenance and three clocks.
- Audit is a projection. It must never become a second source of truth.
- Reversals and corrections are visible as facts. TrueAt / KnownAt are reconstructable (P3).

## 23. Contradiction Audit

| Pair | Finding | Status |
|---|---|---|
| Membership suspension vs ElectionMembership suspension | different facts and owners (Membership `Suspended` vs the Election Chief's suspension; EM-GOV-003 keeps them distinct). The organisation-driven election effect is EM-FM-003/FM-6 (deferred) | **resolved** (FM-6 stays a business question) |
| lapse vs unpaid fees vs termination | lapse = derived term gap. Unpaid = fee fact → standing. Termination = an End fact only (§11 chain) | **resolved** |
| Membership restriction vs Governance ban | different owners and scope; published separately | **resolved** |
| Person vs Affiliation vs Membership | EM-VOC-002; INV-7; Membership is keyed on PersonRef, not on the affiliation | **resolved** (legacy cascade = a migration defect) |
| Annul vs Void vs Correct | §9 table. **E0b's void-merge contradiction found and removed** | **resolved in this review** |
| Effective vs Decided vs Recorded | the three clocks. `recordedAt` = when the fact entered PublicDigit's authoritative record; **not** DB `created_at`, queue time or audit timestamps (implementations may realise it from the commit time) | **resolved** |
| MembershipId vs PeriodId vs MembershipNumber | identity / episode / credential (§7, §15) | **resolved** |
| **open** | person merge (Identity resolution); fee re-attribution for superseded portions; FM-6 / Q-FM-MAP | **explicitly downstream / deferred** |

## 24. Downstream Consequences

1. **Finance / E0b-S:** fee policy for superseded portions; dues compliance; standing.
2. **Election (deferred Full Membership phase):** Q-FM-MAP; FM-5 (map End kinds to "removal"); FM-6 (suspension); a rule for superseded periods **only if** elections bind to a PeriodId.
3. **Identity:** a person-merge act (combining roots, re-checking INV-9).
4. **Governance:** the authority model (EQ-5, EM-OPEN-014); the AuthorityCheck port.
5. **Implementation:** the tenant time-zone source; the number allocator; the uniqueness index.

## 25. Requirements Traceability

| REQ | Business decision | Domain rule | Invariant | Predicate | Command / guard | Acceptance test | Component |
|---|---|---|---|---|---|---|---|
| REQ-MEM-ID | D-ID.1 (B′) | one Membership per (p,o); a new period per admission | INV-1, INV-14 | CurrentPeriod | Admit | re-entry: same MembershipId, a new PeriodId | the Organisation Membership root |
| REQ-MEM-TIME | — (architecture) | three clocks | INV-5 | TrueAt / KnownAt | all | a backdated End changes TrueAt, not KnownAt(τ<record) | facts |
| REQ-MEM-HISTORY | — | append-only; segments | INV-6, INV-12 | Effective, Lapsed | Renew | a late renewal keeps the gap; no fact is overwritten | facts, periods |
| REQ-MEM-END | D-TERM-1/4, Q-ANNUL-RE | End kinds; Annul A1; R or M′ | INV-2/3/9 | Ended, InForce | End, Annul, Correct + g2 | End→re-Admit→Annul: refused (R) or P2 trimmed with its id intact (M′); never voided | root |
| REQ-MEM-SUSPENSION | D-SUSP (S2) | decided full restriction | — | Suspended | Suspend / Restore | Suspended published; IsMember unchanged | periods |
| REQ-MEM-RECOGNITION | REC-0, Q-CONSENT | basis + declaration or substitute provenance | INV-13 | Recognised | Admit | a direct add without a declaration follows the product rule; a rule-based admission carries ruleRef | Admit provenance |
| REQ-MEM-MULTIPLICITY | I5, sync (§16) | disjoint lifetimes | INV-9 | CurrentPeriod | g1, g2 | a backdated re-admission overlapping an earlier period is refused | root guards |
| REQ-MEM-NUMBER | D-ID.2, N-U | the credential on the root, org-unique | INV-10 | — | Admit (allocate / import) | a duplicate in one organisation is refused; two organisations may share a number | root + an org-level uniqueness mechanism |
| REQ-MEM-TYPE | `grants_voting_rights` disposition | type segments; never an entitlement | INV-11 | CurrentType | ChangeType | no voting fact derives from the type | periods |
| REQ-MEM-AUTHORITY | — (architecture) + EQ-5 (deferred) | an authority snapshot on every decision | INV-13 | — | the AuthorityCheck port | an act explains itself without consulting today's Governance | facts ← Governance |

## 26. Computational Verification (V1–V10)

| # | Property | Model | Assumptions | Counterexample | Proof | Finite verification | Status |
|---|---|---|---|---|---|---|---|
| V1 | lifetimes pairwise disjoint | `ratify4.py` | g1 + g2 (R / M′) | without g2: 3,573 | P4 | 0 / 444,604 | PROVEN UNDER ASSUMPTIONS |
| V2 | I5 follows from V1 | logic | V1 | — | P5 | — | PROVEN |
| V3 | a late renewal cannot erase a lapse | `final2.py` | the segment rule | single interval: E0a-4 | P8 | 5,831 gaps visible | PROVEN |
| V4 | annulment cannot create an overlap without g2 | `ratify4.py` | — | **yes, without g2** | — | 0 with g2 | PROVEN UNDER ASSUMPTIONS |
| V5 | correction cannot create an overlap without g2 | `ratify4.py` (CORRECT_END / START) | — | **yes, without g2** | — | 0 with g2 | PROVEN UNDER ASSUMPTIONS |
| V6 | KnownAt is stable | logic | append-only, deterministic | — | P3 | — | PROVEN |
| V7 | TrueAt ≠ KnownAt exactly when later-recorded facts alter the interpretation | `final2.py` / `stress.py` | — | — | follows from P3 + resolution | without backdating, only Annul / Correct (E0b) | PROVEN UNDER ASSUMPTIONS |
| V8 | derived predicates are coherent (no Ended ∧ InForce; Lapsed ⇒ ¬Ended; I5) | logic + runs | — | none | P1, P2, P5 | 0 | PROVEN |
| V9 | Void / Annul / Correct preserve traceability | logic | append-only; reason kinds | — | the facts are never removed | — | PROVEN |
| V10 | period references stay explainable after a reversal | `ratify4.py` | M′ / R | **void-merge loses coverage (782)** | P6, INV-14 | ids never lost; 0 losses under M′ | PROVEN UNDER ASSUMPTIONS |

## 27. Statistics / ML Assessment

- **Statistics: unavailable.** There is no representative production membership dataset. Generated-model frequencies ≠ real-organisation frequencies.
- **ML: not justified for semantic truth.** Possible future uses with real data: duplicate identity candidates (person merge), migration anomaly detection, unusual lifecycle transitions, identity-resolution suggestions.
- **ML never determines** identity, termination semantics, legal validity, voting rights, authority or business rules.

## 28. Decision Register

**A. Already adopted:** EM-VOC-001/002/003 · EM-ENT-001…007 · EM-EO-001…004 · EM-FM-001…006 (deferred) · EM-SEQ-001/002 · EM-GOV-001…003 · SM2 (2026-09-16) · ADR_20261004_1549 · ADR-T11 · Manifesto ≠ Constitution (2026-08-13) · delivery baseline freeze (PO 2026-10-01).

**B. Architecture: proposed for Principal Reviewer ratification:**
- Architecture D (D1) + the B′ structure;
- INV-9 with interval g1/g2;
- **R or M′ as the only reversal policies** (void-merge rejected; overlap rejected);
- INV-14 (no reference rewriting);
- three clocks;
- append-only facts;
- provenance {decision, rule, attested};
- term segments;
- authority evidence snapshot;
- RestrictionSet;
- number uniqueness outside the aggregate;
- sections outside the root;
- the published-fact boundary;
- no Event Sourcing requirement.

**C. Business decisions: Recommended, awaiting PO confirmation:**

| ID | Recommendation |
|---|---|
| D-ID.1 | B′ |
| D-ID.2 | number retained on the root |
| D-TERM-1 | resignation, exclusion, organisation termination (incl. non-payment, by an explicit End), death; lapse is not an end |
| D-TERM-4 | A1 annulment |
| Q-ANNUL-RE | **M′ supersede-by-trim** (R is acceptable; O rejected by the architecture) |
| D-SUSP | S2, with the effects allocated as in §11 |
| REC-0 | no pending membership |
| Q-CONSENT | declaration required for native admissions unless a rule/statutory basis applies (**legal validation outside the corpus**) |
| I5 | M1 |
| **INV-9 synchronous** | yes (§16) |
| Q-TEN | the named predicates; display default CurrentAdmissionDate |
| N-U | per organisation |
| `grants_voting_rights` | retire, or reinterpret as a non-granting classification |

## 29. Remaining Business Decisions

§28 C, plus the later ones:
- D-TERM-3 (W1);
- D-TERM-5 (Void vs Correct as defined);
- REC-IMP / REC-INV;
- FM-5 / FM-6 / Q-FM-MAP (election track);
- the fee policy for superseded portions (Finance / E0b-S).

**No adopted record changes under the recommendations.**

## 30. Remaining Risks

| Risk | Classification |
|---|---|
| person merge (duplicate accounts) needs an Identity-resolution act that combines roots | OPEN RISK, downstream |
| legal validity of consent / statutory bases per jurisdiction | OPEN RISK, legal validation outside the corpus |
| fee re-attribution under M′ | OPEN RISK, downstream (bounded) |
| bounded verification (≤ 5 facts, 4 time points) | mitigated by proofs P1–P12 for the key invariants |
| a tenant that genuinely needs concurrent memberships | OPEN RISK. The structure survives; INV-9 / g1 / g2 would be relaxed by a business decision |
| a tenant time zone not yet defined | IMPLEMENTATION CHOICE before the date predicates |
| a multi-section organisation | kept outside the root by construction. To be re-tested when a real tenant appears |

## 31. Principal Reviewer Ratification Checklist

- [ ] §28 B architecture items ratified (or amended)
- [ ] the void-merge replacement accepted (R / M′ only) and INV-14 adopted
- [ ] the provenance `rule` variant accepted
- [ ] D1 minimality accepted as conditional on synchronous INV-9 (business confirmation)
- [ ] the contradiction audit (§23) accepted: no open architectural contradiction
- [ ] the downstream list (§24) acknowledged as out of scope for Membership ratification
- [ ] §28 C routed to the PO, with the plain-language questions (Decision Gate §16 + Q-ANNUL-RE, Q-CONSENT, Q-TEN, sync INV-9)
- [ ] implementation stays gated: EP-01 plan + EM-SEQ-001 sequencing act by the PO

## 32. Final Verdict

**RATIFIABLE.**
- **For the architecture:** no architectural contradiction remains; every open matter is explicitly a business or downstream decision; the key invariants are proven, with their assumptions stated; every known counterexample is addressed (including the one found here); D and B′ are justified; reversal effects are bounded.
- **The business semantics remain *Recommended, awaiting PO confirmation*.** Ratifying the architecture does not decide them, and **does not authorise implementation.**

---

**STOP.** Nothing implemented, migrated, refactored or committed in this phase. Scripts (research aids) are in the session scratchpad: `ratify.py` → `ratify4.py` (the property definitions were refined twice; the earlier versions over-counted and were corrected before the results were recorded).

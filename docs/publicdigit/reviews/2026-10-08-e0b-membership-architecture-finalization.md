# E0b: Membership Architecture Finalisation

**Type:** Finalisation report for **Principal Reviewer ratification** · **Date:** 2026-10-08 · **implements nothing; changes no ADR**
**Commission:** *"PublicDigit Membership — Final Architecture Finalization: E0b / Principal Architecture Closure and Canonical Membership Model"* (2026-10-08)
**Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)
**Label note:** "E0b" here means **architecture finalisation**, as the commission names it. In earlier documents E0b meant *MembershipType → obligations → dues → standing*; that track is renamed **E0b-S (standing)** below to avoid a label clash.

> **Status vocabulary** (§3): PROVEN BY LOGIC · PROVEN UNDER EXPLICIT ASSUMPTIONS · FINITE COMPUTATIONAL VERIFICATION · COUNTEREXAMPLE / FALSIFICATION · EMPIRICAL EVIDENCE · ARCHITECTURAL DERIVATION · BUSINESS DECISION · IMPLEMENTATION CHOICE · UNRESOLVED.
>
> **"Chosen" in this document means:**
> - for **business** questions: **RECOMMENDED: awaiting PO confirmation**. The researcher is not the author of business truth.
> - for **architecture** questions: **PROPOSED FOR RATIFICATION** by the Principal Reviewer.
>
> **Nothing is DECIDED here unless an adopted record already decides it.**

---

## 1. Executive Decision

**Verdict: CONDITIONAL-FINAL.** The architecture is coherent, falsification-tested and structurally complete. No known counterexample survives. It is **conditional** on:
- **(a)** the PO confirming the recommended business semantics (§29), and
- **(b)** the Principal Reviewer ratifying the architectural decisions (§24).

**One canonical model is chosen: Architecture D with "B′" identity semantics.**
- The **Organisation Membership** (EM-VOC-001: *the `Member` aggregate*) is the **enduring membership record of one person with one organisation**. It is the aggregate root.
- Each admission opens a **new, identified Membership Period**: the legal admission-to-ending episode.
- Re-entry is a **new period under the same Organisation Membership.**

This choice **keeps EM-VOC-001 unchanged** (no terminology amendment). It keeps every period separately referenceable (the legal episode, fees, election evidence). It puts both consistency guards inside one boundary.

**New in this phase (computational and logical):**
- **COUNTEREXAMPLE:** the point-wise I5 guard ("no non-terminal period after the admission date") is **unsound under backdating**. A backdated re-admission placed *before* an earlier period, followed by annulment of that period's ending, yields an overlap (20 histories).
- **The repaired invariant is interval-based:** *period lifetimes are pairwise disjoint*, with guards g1 (admission) and g2 (any fact that enlarges a lifetime: annulment, a correction of an end or start). The point-wise I5 then **follows by logic**. **0 violations** in 153,920 histories with merge or refuse. The induction holds (§26).
- **Term segments verified:** a late renewal leaves the lapse gap visible (5,831 histories). Renewal no longer rewrites the past. Without backdating, only Annul and Correct rewrite past truth (117 / 236).
- **O3 simplified:** "attestation" is a **provenance value** on a fact, not a separate fact type. The ontology is *facts with provenance*.

## 2. Research corpus and evidence

The corpus:
- V3/V4 + the blueprint (HYP);
- DR, FR, DP, G, E3, E4 (`reviews/2026-10-08-e0a*`);
- R / Q / D;
- adopted: EM-VOC-001/002/003, EM-ENT-001…007, EM-EO-001…004, EM-FM-001…006 (deferred), EM-SEQ-001/002, SM2, ADR_20261004_1549, ADR-T11, the Manifesto ≠ Constitution ruling;
- code and tests (EMPIRICAL / IMPLEMENTATION);
- guides (EMPIRICAL, intent).

**Domain knowledge** used and labelled as such:
- association law treats a re-joining as a new admission;
- membership cannot be imposed on a person (freedom of association);
- multi-section clubs exist.

## 3. E0a-4 review

| E0a-4 conclusion | Classification |
|---|---|
| IsMember is the same under A, B and D | FINITE COMPUTATIONAL VERIFICATION (238k + 154k histories) + PROVEN UNDER EXPLICIT ASSUMPTIONS (a shared targeting rule) |
| identity-scoped meanings (admission date, tenure, number) differ between A and B | COUNTEREXAMPLE (conclusive) |
| record time needed beyond appeals | COUNTEREXAMPLE (backdated facts) + EMPIRICAL (the import supplies backdating) |
| term segments needed | COUNTEREXAMPLE (lapse-gap erasure) + EMPIRICAL (the legacy defect) |
| admission needs the person's declaration | EMPIRICAL (path analysis) + domain knowledge → **BUSINESS DECISION** (Q-CONSENT) |
| I5 needs guard g2 | COUNTEREXAMPLE → **this phase refines it:** g1 must also be interval-based (§1) |
| authority as an evidence snapshot | ARCHITECTURAL DERIVATION |
| number uniqueness outside the (p,o) aggregate | PROVEN BY LOGIC (uniqueness quantifies over persons; the aggregate is per person) |
| sections outside the root | ARCHITECTURAL DERIVATION (no corpus evidence; domain knowledge) |
| O3 facts with provenance | ARCHITECTURAL DERIVATION. **Simplified here** (§4) |
| three clocks | ARCHITECTURAL DERIVATION from COUNTEREXAMPLES |
| D strongest | ARCHITECTURAL DERIVATION (from g1 + g2) |
| E0b-S standing is research-ready | ARCHITECTURAL DERIVATION |
| statistics unavailable / ML not justified | EMPIRICAL (no data) |

**No E0a-4 claim is reversed. One is sharpened:** the I5 guards (§1, §15).

## 4. Final ontology

| | O1: act-centric | O2: state-centric | O3: facts with provenance |
|---|---|---|---|
| native decisions | ✓ | ✓ | ✓ |
| imports / migration | **only if** an "act" may describe a historical state it did not cause | ✓ via an overwrite (history lost) | ✓ |
| corrections / annulments | ✓ (as acts) | ✗ (mutation) | ✓ |
| backdated facts | ✓ | ✗ | ✓ |
| as-known-at-τ | ✓ (with recordedAt) | ✗ | ✓ |
| vocabulary size | small | smallest | small |

**Test of necessity:** does O3 need a separate **Attestation** type?
- **No.** An import is still an **act performed in PublicDigit** (by an importer, at record time). Its content asserts a historical admission with **unknown decision provenance**.
- The difference from a native admission is **one attribute**: `provenance ∈ {decision(actor, authoritySnapshot, personDeclaration), attested(source, importer)}`.

**Final ontology (ARCHITECTURAL DERIVATION):**
- **Membership facts**: one family, each with `kind`, `effectiveAt`, `decidedAt?`, `recordedAt`, `provenance`, and an optional `target` (Annul / Correct / Void).
- "Act" is the business word for a fact with decision provenance.
- **O2 is rejected.** It cannot answer as-known-at-τ or survive corrections.
- **No Event Sourcing:** §17.

## 5. Final identity model

| Term | Definition | Identity |
|---|---|---|
| **Organisation Membership** ("the Membership"; EM-VOC-001 `Member`) | the enduring membership record of one person with one organisation, across all periods | `MembershipId` (root); one per (p,o) |
| **Membership Period** | one admission-to-ending episode: the legal membership relationship from an admission until its ending | `PeriodId`; **new on every admission** |
| **Re-entry** | an admission when the Organisation Membership already has an ended period | a new Period, same Membership |
| **Identity of a historical membership** | `PeriodId` (+ `MembershipId`) | — |
| **External references** | `MembershipId` for "the member" (number, member record); `PeriodId` for period-bound facts (decisions, fees, election eligibility evidence) | — |
| **Joiner / returning member** | joiner = the first period opened; returning member = a period opened when an earlier ended period exists | derived |
| **"Member since" / duration** | **Q-TEN** (§16): named predicates, never one ambiguous field | derived |

**Why B′, not A or pure B:**
- **(i) EM-VOC-001 (adopted):** Organisation Membership = the aggregate. Under A it would be the *period*, which would require amending adopted vocabulary.
- **(ii) Legal episode distinctness:** a re-joining is a new admission (domain knowledge). This is preserved **as a period identity**.
- **(iii)** g1 and g2 are internal.
- **(iv)** Both reporting views (joiner / returning) are expressible.
- **(v) Secondary:** legacy fit.

**Status:** RECOMMENDED, awaiting PO confirmation (D-ID.1).

## 6. Final temporal model

**Clocks, per fact:**
- `effectiveAt`: valid time; when the fact takes effect in the domain;
- `decidedAt`: when the organisation decided (null for attested facts if unknown);
- `recordedAt`: when PublicDigit recorded it.

**Valid time is effective time; there is no fourth clock.** EM-VOC-008 D-3 applies: the device time zone is never a business rule. The tenant time zone stays an open implementation item.

**Formal definitions:**

```text
F_τ            = { f ∈ F : recordedAt(f) ≤ τ }                                  -- knowledge at τ
Res(F_τ)       = F_τ with targets of Annul/Void removed and Correct applied (all recorded ≤ τ)
Holds(φ, t | τ)= φ evaluated at valid time t over Res(F_τ)
TrueAt(φ, t)   = Holds(φ, t | now)                     -- "what was true at t" (best current knowledge)
KnownAt(φ,t,τ) = Holds(φ, t | τ)                       -- "what PublicDigit knew at τ about t"
DecidedBy(φ,t,d)= Holds(φ, t | now) restricted to facts with decidedAt ≤ d  -- "what the organisation had decided by d"
```

| Case | Effective truth | Decision truth | Record truth |
|---|---|---|---|
| future-effective admission / termination | from effectiveAt | from decidedAt (a pending effect) | from recordedAt |
| backdated admission / termination / renewal | from the earlier effectiveAt | decidedAt (later) | recordedAt (later). KnownAt before it differs |
| late renewal | a new segment from the renewal date; **the gap stays** | — | — |
| suspension / restoration | a restriction interval | decidedAt | recordedAt |
| correction / annulment | recomputed | — | KnownAt before ≠ after (visible) |
| import / migration | the historical effectiveAt | unknown (attested) | the import time |
| historical type change | a type segment from effectiveAt | — | — |

**Three separate layers:**
1. **The domain temporal model:** the three clocks and the predicates above.
2. **The database temporal implementation:** an IMPLEMENTATION CHOICE. Append-only fact rows with these columns are sufficient. No bitemporal tables are required, because KnownAt is a filter on `recordedAt`.
3. **Event Sourcing:** not required (§17).

## 7. Final fact / act / attestation model

| Fact kind | Nature | Targets | Changes |
|---|---|---|---|
| **Admit** | a new business fact; opens a period | — | period lifetime start; first term segment; first type segment; number (if new root) |
| **Renew** | a new business fact | period | adds a term segment |
| **ChangeType** | a new business fact | period | adds a type segment |
| **Suspend / Restore** | a new business fact | period | restriction interval |
| **End(kind)** | a new business fact | period | lifetime end (from effectiveAt) |
| **Annul** | a **reversal**: the target is treated as never having taken effect | a fact (typically End; also Suspend, Admit) | removes the target's effect. **Guarded by g2** if it enlarges a lifetime |
| **Correct** | a **correction**: the target was recorded wrongly | any fact | replaces the target's values. **Guarded by g2** if it enlarges a lifetime |
| **Void** | a correction: the target was **never valid** (wrong person, never a member) | Admit | removes the period |
| Re-Admit | **not a separate kind**: an Admit on a Membership with ended periods | — | a new period |
| Restore (after an ending) | **not a kind**: either Annul(End) (D-TERM-4 A1) or a re-Admit | — | — |
| Lapse, expiry | **derived**, never a fact | — | — |

Every fact carries **provenance**: `decision` (actor, authority snapshot, the person's declaration where applicable) or `attested` (source, importer).

## 8. Final Membership Period and term segment model

```text
Period
  lifetime   = [start, end)     start := effectiveAt(Admit); end := effectiveAt(End) or ∞   (Annul/Correct may change end)
  termSegs   = ordered list of [a_i, b_i)
  typeSegs   = ordered list of [c_j, d_j) × TypeRef
  restrictions = list of [s_k, e_k) × {full-suspension}
```

| Question | Rule |
|---|---|
| opens a term segment | **Admit**: `[v, v + duration(type at v))` (∞ for lifetime types). **Renew at r**: `[max(b_last, r), max(b_last, r) + duration(type at that start))` |
| closes a segment | its own end bound only (the segment is the paid/granted term) |
| renewal creates a segment | **yes** |
| type change creates a term segment | **no**: it creates a **type segment** (independent dimension) |
| suspension changes segments | **no**. Unless a tenant rule pauses the term, which would be an explicit Extend fact (deferred; D-SUSP) |
| termination closes segments | **no**: `Ended` is derived from the lifetime end. Segments stay the record of the granted term |
| restoration creates a segment | **no** |
| backdated correction | segments are **derived from facts**, so a Correct of Admit/Renew dates recomputes them. KnownAt shows the change |

**Lemma (lapse-gap preservation):** if `r > b_last` (a late renewal), then no segment covers `[b_last, r)`. Therefore `¬Effective` there, `¬InForce` there, and the gap is visible.
- **Proof:** segments are opened only by Admit/Renew. The new one starts at `max(b_last, r) = r`. Earlier ones end ≤ b_last. ∎
- **PROVEN BY LOGIC** under the segment-opening rule. Confirmed in FINITE COMPUTATIONAL VERIFICATION (5,831 histories).
- **A segment is not a status:** it records a granted term interval and carries no lifecycle meaning.

## 9. Final lifecycle / restriction model

**Stored per period:** lifetime start/end, segments and restriction intervals. **No status field.**

| Predicate | Definition |
|---|---|
| `Ended(m,t)` | `end(m) ≤ t` |
| `Effective(m,t)` | `∃ seg: a ≤ t < b` |
| `Suspended(m,t)` | `∃` a full-suspension interval containing t (decided, membership-scoped) |

**`Suspended` is precisely:** a **decided, membership-scoped restriction of all membership rights** by an authorised organ. It is **not**:
- Governance bans (scoped to one process; issued by another organ);
- standing-derived restrictions (for example dues arrears);
- the Election Chief's ElectionMembership suspension.

Each of these is a **separate fact from its owner**.

| Effect of `Suspended` (**RECOMMENDED S2**, PO confirmation) | |
|---|---|
| `InForce`, `IsMember` | unchanged (in force; counted as a member) |
| member count | counted |
| dues / standing | continue / unaffected (E0b-S may add rules) |
| committee participation | Governance consumes `Suspended` (excluded while suspended) |
| quorum / assembly | cannot exercise attendance or voting rights while suspended (PO confirmation) |
| services | withdrawn |
| election eligibility | Election criteria may require `¬Suspended` (Election decides) |
| election exercisability | EM-FM-003 / FM-6 (deferred election track) |

**Restriction set:** a period holds membership-scoped restrictions (today only full suspension). Consumers receive bans and standing restrictions **separately**. This is ARCHITECTURAL DERIVATION.

## 10. Recognition and consent model

| Path | Person declaration | Organisation decision | Admit provenance |
|---|---|---|---|
| application | the application (ref) | approval | decision{actor, authority, declaration=applicationRef} |
| invitation | **the acceptance** (ref) | the invitation by an authorised person | decision{…, declaration=acceptanceRef}; effective at acceptance |
| direct add | **must be evidenced** (for example a written declaration recorded by an officer) | the officer's decision | decision{…, declaration=evidenceRef}. **Without evidence: invalid** |
| import / migration | historical (unknown) | historical (unknown) | attested{source, importer} |

- **REC-0 (RECOMMENDED: no pending membership).** A membership exists from a valid Admit. Unconfirmed entries remain applications, invitations or import candidates.
- **Q-CONSENT (RECOMMENDED: the person's declaration is mandatory for native admissions).**
  - The basis is domain knowledge: membership in an association cannot be imposed on a person (freedom of association).
  - Imports are attested.
  - **The legacy direct add (`addMember`) and auto-membership on invitation acceptance would need declaration evidence.** That is a conformance gap.
  - **Awaiting PO confirmation.**

## 11. Authority model

```text
Governance (competence rules, organs, statutes)  --AuthorityCheck(actor, capacity, factKind, org)-->  result
Membership fact.provenance.decision.authority = {
  actor, claimedCapacity (role/organ), ruleRef, policyVersion, checkResult (allowed/denied + reason),
  checkedAt }   + fact.decidedAt, fact.effectiveAt, fact.recordedAt
```

**Why a snapshot and not a live reference (ARCHITECTURAL DERIVATION):**
- competence rules change;
- organs are reconstituted;
- references can be re-pointed.

A historical act must stay explainable **as it was justified then**. An act later found to be unauthorised is handled by a **Correct / Void / Annul** fact with that reason, **never** by rewriting.

Competence rules stay in **Governance** (deferred design: EQ-5, EM-OPEN-014).

## 12. Membership Type model

| Element | Placement |
|---|---|
| **MembershipType** (name, fee, duration, approval requirement, classifications) | configuration outside the aggregate (Membership context) |
| **Type assignment** | **type segments** of a period; changed by **ChangeType** |
| fee | the Fee aggregate **snapshots** the amount at creation (existing `fee_amount_at_time`) |
| duration | read **at segment opening**: the type in force at the segment start |
| rights classifications | readable by consumers (Election criteria read `TypeAt(m,t)`) |
| election relevance | a **criterion fact only** |
| number prefix | **not** tied to the type (a type change never changes the number; recommended) |
| validity | type segments are `[from, until)` within the period lifetime |

**Preserved (ADOPTED, EM-ENT-001/007):** *a Membership Type never grants a VotingEntitlement.*
- The `grants_voting_rights` disposition stays open: retire, or reinterpret as a non-granting classification (FR §13).

## 13. Membership Number model

| Aspect | Model |
|---|---|
| **value** | an organisation-issued credential (EMPIRICAL); importable |
| **ownership** | **the Organisation Membership root** (retained across re-entry: RECOMMENDED D-ID.2) |
| **uniqueness** | **per organisation** (RECOMMENDED N-U), across all roots of the organisation, **outside the aggregate**: an organisation-level uniqueness mechanism (IMPLEMENTATION CHOICE: a unique index on (organisation, number), or an allocator) |
| **allocation** | an organisation-level allocator for generated numbers; imported numbers are validated against the same uniqueness |
| **history** | changes are `Correct` facts on the root (the old value is kept in the fact history) |

**No Number Registry concept.** The domain gives no reason for one. Uniqueness is a cross-aggregate **constraint**, not an entity.

## 14. Aggregate boundary: derivation

**The invariants requiring atomic enforcement on one write:**
- **g1:** an admission's lifetime `[v, ∞)` must not intersect any existing period lifetime of the same (p,o);
- **g2:** a fact that enlarges a lifetime (Annul or Correct of an End; Correct of an Admit start) must not create an intersection;
- **holder immutability:** a per-period, per-root invariant;
- **segment derivation:** within one period;
- **restriction consistency:** within one period.

g1 and g2 quantify over **all periods of (p,o)**. The smallest boundary containing every period of (p,o) is **the (p,o) root**.
- **PROVEN BY LOGIC**, under the assumption that g1 and g2 are enforced **synchronously**.
- **If eventual reconciliation of overlaps were acceptable** (a business choice no one has asked for), period-level aggregates would suffice. **This is the only assumption under which D is not minimal.**

| Potential falsifier | Outcome |
|---|---|
| multi-section organisations | **section enrolments outside the root** (a separate concept or Governance units) |
| honorary; multiple types | a type / attribute of the period |
| primary / secondary; federation | different organisations → different roots |
| multiple statutory bases | basis attributes on Admit |
| independent fee obligations | the Fee aggregate outside |
| concurrent memberships | violates I5. **If** the business allowed them (M2), the root still represents overlapping periods, g1/g2 relax, and D stays valid but no longer minimal |
| multiple numbers | not supported by default (number on the root). The period-level number is a deferred variant |
| multiple applications | application aggregates outside |
| historical migration | attested Admit / End facts |

**Migration ease is NOT used as justification.**

## 15. Invariants

| ID | Invariant | Definition | Status |
|---|---|---|---|
| **INV-1** | the holder and organisation are constant | no fact changes (p,o); a wrong holder → Void + new Admit | PROVEN UNDER EXPLICIT ASSUMPTIONS (the Void rule) |
| **INV-2** | `Ended ⇒ ¬InForce` | by the definition of InForce | PROVEN BY LOGIC |
| **INV-3** | lapse is not an ending | Ended only from End facts | PROVEN BY LOGIC (induction, §26) |
| **INV-4** | conditions are derived from facts | no stored condition | ARCHITECTURAL DERIVATION |
| **INV-5** | every fact carries the three clocks and provenance | — | ARCHITECTURAL DERIVATION |
| **INV-6** | facts are append-only; reversal and correction are new facts | — | ARCHITECTURAL DERIVATION. As-known stability is PROVEN BY LOGIC from it |
| **INV-7** | the membership history survives any affiliation change | — | ARCHITECTURAL DERIVATION (the legacy cascade violates it) |
| **INV-8** | IsMember is partition-invariant | — | PROVEN UNDER EXPLICIT ASSUMPTIONS + FINITE VERIFICATION |
| **INV-9 (I5′)** | **the period lifetimes of a (p,o) are pairwise disjoint** | `∀ m1≠m2: [s1,e1) ∩ [s2,e2) = ∅` | PROVEN UNDER EXPLICIT ASSUMPTIONS (guards g1 + g2, §26) + FINITE VERIFICATION (0 violations). **The business rule M1 is RECOMMENDED** |
| INV-9′ (I5) | ≤ 1 non-terminal period at any t | follows from INV-9 | PROVEN BY LOGIC from INV-9 |
| **INV-10** | the number is unique within the organisation | — | BUSINESS DECISION (recommended); enforced outside the aggregate |
| **INV-11** | Membership grants no election entitlement | — | ADOPTED |
| **INV-12** | lapse gaps are preserved by renewal | §8 lemma | PROVEN BY LOGIC |
| **INV-13** | every native Admit has a person declaration and an authority snapshot; an attested Admit has a source | — | BUSINESS DECISION (Q-CONSENT) + ARCHITECTURAL DERIVATION |

## 16. Formal predicates

*All derived; none stored as truth. Evaluated over `Res(F_τ)`, with τ = now for TrueAt.*

| Predicate | Inputs | Definition | Authoritative? |
|---|---|---|---|
| `Recognised(m,t)` | Admit | `start(m) ≤ t` (REC-0 = no) | yes |
| `Effective(m,t)` | Admit, Renew, type durations | `∃` term segment ∋ t | yes |
| `Ended(m,t)` | End, Annul, Correct | `end(m) ≤ t` | yes |
| `Lapsed(m,t)` | — | `Recognised ∧ ¬Ended ∧ ¬Effective` | yes |
| `Suspended(m,t)` | Suspend, Restore | a full-suspension interval ∋ t | yes |
| `InForce(m,t)` | — | `Recognised ∧ Effective ∧ ¬Ended` (S2: suspension not included) | yes |
| `IsMember(p,o,t)` | — | `∃ m ∈ periods(p,o): InForce(m,t)` | yes |
| `CurrentPeriod(p,o,t)` | — | the period whose lifetime ∋ t (unique by INV-9) | yes |
| `AdmissionDate(m)` | Admit | `start(m)` | yes |
| `FirstAdmission(p,o)` | — | `min start` over the periods | yes |
| `MemberSince(p,o,t)` | **Q-TEN** | **RECOMMENDED:** `start(CurrentPeriod)`, with `FirstAdmission` published alongside | business-defined |
| `Tenure(p,o,t)` | **Q-TEN** | named variants: `continuousTenure = t − start(CurrentPeriod) − lapse gaps?` · `cumulativeTenure = Σ InForce time` | consumers name the variant they need (committee rules, election criteria) |
| `CurrentType(m,t)` | ChangeType | the type segment ∋ t | yes |
| `Standing(m,t)` | Fee facts | **E0b-S** (out of scope) | — |

Record-time semantics: any predicate can be asked as `KnownAt(·, t, τ)`.

## 17. Commands and guards (application layer)

| Command | Guard(s) | Fact |
|---|---|---|
| `Admit(person, org, basis, declarationRef \| source, type, effectiveAt)` | **g1**; authority check (decision provenance); declaration present (Q-CONSENT); type active | Admit (+ the root if new: number allocated or imported, with **organisation-level uniqueness**) |
| `Renew(period, effectiveAt)` | period not ended; authority (or self-renewal policy) | Renew |
| `ChangeType(period, type, effectiveAt)` | period not ended; authority | ChangeType |
| `Suspend` / `Restore(period, effectiveAt, reason)` | period in force / suspended; authority | Suspend / Restore |
| `End(period, kind, decidedAt, effectiveAt, reason)` | not ended; authority (resignation: the member's declaration) | End |
| `Annul(target, reason)` | **g2** if it enlarges a lifetime (Q-ANNUL-RE policy); authority | Annul (+ Void of the merged period under merge) |
| `Correct(target, values, reason)` | **g2** if it enlarges a lifetime; authority | Correct |
| `Void(admit, reason)` | authority | Void |
| `ImportHistorical(...)` | g1 / g2 on attested lifetimes; uniqueness of the number | Admit / End with attested provenance |

**Layering:**
- **Domain:** facts, invariants, derivation.
- **Application:** commands, guards, policies.
- **Persistence:** fact rows + materialised current state (IMPLEMENTATION CHOICE).
- **Integration:** published facts and events.

**Event Sourcing is not required** (a domain argument): the domain needs immutable, attributed, three-clock facts. Rebuilding state *only* from an event stream, and event versioning as the persistence protocol, add nothing that any requirement asks for.

## 18. Context map

| Upstream → Downstream | Pattern | Contract | Never shared |
|---|---|---|---|
| Identity → Membership | Conformist | PersonId (internal) | identity evidence; cross-tenant relations (INV-TEN-1) |
| Governance → Membership | Customer/Supplier | `AuthorityCheck` port; affiliation existence | competence rule internals (Membership stores snapshots) |
| Membership → Election | **OHS + Published Language**; Election **ACL** | §19 facts | Membership tables; fee details; decision provenance |
| Membership → Finance | published event; Finance **Conformist** (as implemented) | FeePaid (amount, currency, method, periodRef) | membership lifecycle internals |
| Membership → Audit | published fact stream | facts with provenance | — (Audit is a projection) |
| Governance → Election | (existing) | bans, officers | — |

**No shared persistence. No shared kernel.**

## 19. Election integration (Election unchanged)

- **Preserved:**
  - Election-Only → ImportedVoterRegistry (no Membership; EM-EO-001);
  - Full Membership → MembershipRegistry;
  - Registration → ElectionMembership → derived Admission → VotingEntitlement (incl. `canVote`: exercisability is **inside** the entitlement, per ADR_20261004_1549);
  - ADR-T11.
- **Membership publishes:**
  - `IsMember(p,o,t)`;
  - `Suspended(p,o,t)`;
  - `CurrentType(p,o,t)`;
  - `Standing` (E0b-S);
  - `MemberSince` / `Tenure` variants (Q-TEN);
  - End and Suspend events (for EM-FM-003, deferred);
  - `PeriodId` **only** where Q-FM-MAP option A is later adopted.
- **Membership never computes eligibility or entitlement.** No membership fact reaches the cast transaction.

## 20. Finance integration

- Fees and payments are recorded in the **Membership context** (the Fee aggregate, outside the root, referencing `PeriodId`).
- **Membership publishes dues compliance** (E0b-S).
- Finance consumes `FeePaid` as data (Conformist).
- Finance never decides membership.
- *If an external payment provider is added later,* its evidence enters Membership through an ACL.

## 21. Audit integration

**The membership fact history *is* the membership audit trail.** Audit consumes it as a projection. Requirements for Audit to rely on it:
- append-only facts;
- three clocks;
- provenance with the authority snapshot.

## 22. Canonical requirements (domain level; not implementation)

| ID | Requirement · rationale | Actor / trigger | Precondition | Invariant | Behaviour → result | Historical effect | Acceptance / negative cases | Aggregate / context |
|---|---|---|---|---|---|---|---|---|
| **REQ-MEM-ID** | one enduring Organisation Membership per (p,o); a new identified period per admission · references and reporting | organ / person; admission | — | INV-1, INV-9 | first admission creates the Membership + period; re-entry creates a period | periods keep their ids | re-entry yields the same MembershipId and a new PeriodId. **Neg:** a second Membership for (p,o) refused | root / Membership |
| **REQ-MEM-TIME** | three clocks on every fact · backdating, imports, appeals | any | — | INV-5 | TrueAt / KnownAt answerable | KnownAt is stable for past τ | a backdated End changes TrueAt but not KnownAt(τ<record). **Neg:** a fact without recordedAt rejected | root / Membership |
| **REQ-MEM-HISTORY** | append-only facts; term segments · legal history, lapse visibility | any | — | INV-6, INV-12 | reversal and correction are new facts; renewal adds a segment | lapse gaps visible | a late renewal leaves the gap ¬InForce. **Neg:** no fact deleted or overwritten | root / Membership, Audit |
| **REQ-MEM-END** | End(kind); Annul (A1); Correct; Void · statutes | member / organ | not ended | INV-2, INV-9 (g2) | End stops InForce from effectiveAt; Annul = never ended; Q-ANNUL-RE = merge | KnownAt shows the reversal | End → re-Admit → Annul ⇒ the later period voided (merge), no overlap. **Neg:** lapse never ends | root / Membership |
| **REQ-MEM-SUSPENSION** | full suspension = a decided membership-scoped restriction (S2) · rights | organ | in force | — | Suspended published; InForce unchanged | an interval | Suspended ≠ ban ≠ arrears restriction. **Neg:** suspension never changes IsMember (S2) | root / Membership, Governance |
| **REQ-MEM-RECOGNITION** | an Admit needs the organisation decision + the person declaration (or attested) · validity, consent | organ + person | — | INV-13 | a path-specific declaration ref | provenance kept | a direct add without a declaration is refused. **Neg:** no pending membership (REC-0) | root / Membership |
| **REQ-MEM-MULTIPLICITY** | period lifetimes disjoint · no double membership | system | — | INV-9 (g1, g2) | admission and lifetime-enlarging facts guarded | — | a backdated re-admit before an earlier period is refused if it overlaps. **Neg:** overlaps impossible | root / Membership |
| **REQ-MEM-NUMBER** | the number on the root, retained; unique per organisation · credential | allocator / import | — | INV-10 | allocate or validate on root creation | number corrections as facts | two organisations may share a number. **Neg:** a duplicate in one organisation refused | outside (uniqueness) + root |
| **REQ-MEM-TYPE** | type segments; type ≠ entitlement · fees, criteria | organ | not ended | INV-11 | ChangeType adds a segment; duration read at segment start | the type at t answerable | fee snapshot unchanged by a type edit. **Neg:** no voting fact from the type | root / Membership |
| **REQ-MEM-AUTHORITY** | each decision fact stores an authority snapshot · explainability | any organ | — | INV-13 | AuthorityCheck via the Governance port | the snapshot survives rule changes | an act shows actor, capacity, rule, version, result. **Neg:** an unauthorised act → Annul/Void with reason | root ← Governance |

## 23. Legacy mapping (requirements only; no redesign)

| Legacy | Defect | Canonical target | Data preservation requirement | Risk |
|---|---|---|---|---|
| two Member models; three status vocabularies | conflicting truths | one root + facts | map every status to facts with `provenance=attested(legacy)` | mis-mapping `archived` |
| `members.status` / `expired` stored | derived condition stored | derived predicates | `expired` → an open period with a past segment end | none |
| `organisation_user_id` UNIQUE + CASCADE | cascade deletes history | a root per (p,o); no cascade | keep the history if the affiliation is deleted | data loss if migrated naively |
| `joined_at` / `membership_expires_at` | a single interval | the Admit segment + Renew segments | the renewal ledger (`membership_renewals`) → segments | earlier gaps are unrecoverable where no ledger exists (mark `unknown`) |
| renewal keeps `expired` | stale state | derived | — | — |
| `suspended` (unreachable) | — | a Suspend fact | — | — |
| `endMembership` (dormant; removes ElectionMemberships) | contradicts EM-FM-003/EO-004/SM2 | an End fact; the election effect is deferred | — | do not wire it up |
| membership number: global unique; not generated on approval | wrong scope; regression | root number; per organisation | keep the existing numbers | collisions after re-scoping: none (global ⊂ per-org) |
| `fees_status` + `fee_state` | two truths | Fee aggregate → E0b-S | — | — |
| `grants_voting_rights` | grant semantics | a classification or retired (open) | keep the column until decided | — |
| six re-entry paths | inconsistent | one Admit command (+ g1) | — | — |
| `addMember` without a declaration | consent gap | Admit requires a declaration | legacy members: `attested(legacy)` | — |
| missing decision/authority on legacy rows | — | `provenance=attested(legacy)`; **never fabricated** | — | — |

## 24. Decision matrix

| Decision | Options | Chosen | Why | Evidence | Impact |
|---|---|---|---|---|---|
| D-ID.1 | A · B · **B′** (enduring Membership + new period id) | **B′: RECOMMENDED** (PO) | EM-VOC-001 kept; legal episodes as periods; g1/g2 internal | adopted vocab; domain knowledge; logic | identity, references, reporting |
| D-ID.2 | retain · reissue · tenant | **retain (on the root): RECOMMENDED** (PO) | the credential follows the member record; no tenant-variation evidence | empirical (org-issued) | number placement |
| D-TERM-1 | kinds | **resignation, exclusion, organisation termination (incl. non-payment), death**; lapse is **not** an ending; an optional lapse-to-end rule = a system End fact | guide; logic (INV-3) | lifecycle | |
| D-TERM-4 | A1 · A2 · A3 | **A1 annulment: RECOMMENDED** (PO) | keeps endings final; works with B′ | logic | g2 |
| Q-ANNUL-RE | refuse · merge · overlap | **merge: RECOMMENDED** (PO) | consistent with A1 "never ended"; INV-9 holds (0 violations) | finite verification + induction | g2 |
| D-SUSP | S1 · S2 | **S2: RECOMMENDED** (PO) | smallest; composes with bans | logic; empirical (no S1 evidence) | IsMember definition |
| REC-0 | yes · no | **no: RECOMMENDED** (PO) | no pending membership needed | empirical | — |
| Q-CONSENT | mandatory · optional | **mandatory for native admissions: RECOMMENDED** (PO) | membership cannot be imposed | domain knowledge; path analysis | Admit validity; `addMember` |
| I5 | M1 · M2 · M3 | **M1, as INV-9 (interval-disjoint): RECOMMENDED** (PO) | no counterexample; a guarded invariant | finite verification + induction | g1/g2 |
| Q-TEN | first · current · cumulative | **named predicates; MemberSince = current period start (FirstAdmission also published): RECOMMENDED** (PO) | avoids one ambiguous field | logic | consumers |
| N-U | per org · global · configurable | **per organisation: RECOMMENDED** (PO) | organisation-issued numbers | empirical | uniqueness mechanism |
| O3 | O1 · O2 · O3 | **O3 (facts with provenance; attestation = a provenance value): PROPOSED FOR RATIFICATION** | covers imports, corrections, backdating | counterexamples | ontology |
| three clocks | 2 · 3 · 4 | **3: PROPOSED** | valid = effective | counterexamples | every fact |
| term segments | interval · segments | **segments: PROPOSED** | lapse-gap lemma | logic + finite verification | periods |
| Architecture | A · B · C · **D** | **D: PROPOSED** | the smallest boundary containing g1 + g2 | logic (under the synchronous-enforcement assumption) | aggregate |
| authority | ref · **snapshot** | **snapshot: PROPOSED** | explainability under rule change | derivation | facts |
| restrictions | one "suspended" · **RestrictionSet** | **RestrictionSet: PROPOSED** | distinct owners and effects | empirical + derivation | facts / consumers |
| number uniqueness boundary | inside · **outside** | **outside: PROPOSED** | it quantifies over persons | logic | persistence |
| section enrolments | inside · **outside** | **outside: PROPOSED** | the root stays minimal | derivation | — |
| Event Sourcing | ES · **append-only facts + materialised state** · state + log | **append-only facts + state: PROPOSED** | domain needs met without ES | domain argument | persistence |

## 25. Architecture alternatives rejected

| Alternative | Why rejected |
|---|---|
| **A** (membership per admission as the aggregate) | g1/g2 cross-aggregate; would require amending EM-VOC-001 |
| **pure B** (one membership, periods without identity) | undated references are ambiguous; legal episodes are not referenceable |
| **C** (relationship + episode as two aggregates) | dominated by D (the same semantics, cross-aggregate consistency) |
| **O2** state-centric | fails corrections, backdating, KnownAt |
| a single term interval | lapse-gap erasure (counterexample) |
| point-wise I5 guard | **unsound under backdating** (counterexample, this phase) |
| a Number Registry | no domain reason; uniqueness is a constraint |
| Event Sourcing | no requirement needs it |
| one generic "suspended" | conflates three owners |

## 26. Computational verification and inductive proofs

**New in this phase, `final.py` / `final2.py`:**
- one (p,o); valid time 0–4;
- ≤ 4 facts with effective times (backdated and future);
- term segments; period lifetimes;
- guards g1/g2 under policies none / merge / refuse;
- **153,920** canonical histories.

| Property | Result |
|---|---|
| point-wise g1 + merge / refuse | **I5 violated in 20** (backdated re-admission before an earlier period + Annul): **COUNTEREXAMPLE** |
| interval g1 + interval g2 (merge or refuse) | **I5: 0 violations**; without g2: 70 |
| INV-2 | 0 violations |
| lapse gap visible after a late renewal | 5,831 histories (segments) |
| past truth rewritten without backdating | **only Annul (117) and Correct (236)**. Renew no longer rewrites (vs 1,957 / 206 with a single interval in E0a-4) |

**Inductive proofs** (state = the resolved fact set; a step appends one fact):

| Invariant | Base | Transition cases | Result | Assumptions |
|---|---|---|---|---|
| INV-2 | — | — | definitional | InForce includes ¬Ended |
| INV-3 lapse ≠ end | ∅ | only End adds an end; Annul removes; Correct moves; the others none; time passing adds none | proven | Ended defined only from End facts |
| INV-6 as-known stability | ∅ | append-only, so earlier `F_τ` never change | proven | no deletion |
| INV-9 lifetimes disjoint | ∅ | Admit: g1 requires every existing lifetime to end ≤ v, so the new `[v, ∞)` is disjoint. End / Correct-earlier: the lifetime shrinks (disjointness preserved). Annul / Correct-later / Correct-start-earlier: enlarge → g2 refuses or voids the intersecting periods (merge). Renew / ChangeType / Suspend / Restore: no lifetime change. Void: removes | **proven under assumptions** | g1/g2 enforced synchronously on the (p,o) aggregate |
| I5 point-wise | — | — | follows: pairwise-disjoint intervals contain any t at most once; non-terminal at t ⇒ t ∈ lifetime | from INV-9 |
| INV-12 lapse gap | — | §8 lemma | proven | the segment-opening rule |
| INV-8 partition invariance | — | identical segments and targeting across groupings | proven under assumptions | a shared targeting and acceptance rule |

## 27. Statistical / ML assessment

- **Statistics: unavailable.** There is no production membership data. Development data is demo data, outside the mandate. Enumeration counts are **frequencies in a generated model, not in real organisations**.
- **ML: not appropriate for semantic or domain truth discovery at this stage.** With real data later, it is justified only for:
  - duplicate identity detection (persons with several accounts);
  - anomalous lifecycle sequences;
  - migration anomaly detection (legacy rows that cannot be mapped);
  - identity-resolution candidates.

  ML never decides semantics.

## 28. Remaining risks

| Risk | Mitigation / falsification test |
|---|---|
| a tenant genuinely has concurrent memberships (M2) | D represents them; g1/g2 relax. Re-test minimality |
| suspension pauses the term in some statutes | an explicit Extend fact (E0b-S / D-SUSP extension) |
| merge (Q-ANNUL-RE) voids a period that has fees / election registrations | fees referencing a voided period need a Finance and Election rule. **Open follow-up** (affects E0b-S and the Full Membership election track) |
| legacy history without a renewal ledger | gaps unrecoverable; mark `unknown`, never fabricate |
| tenant time zone undefined | the implementation must fix it before the date-based predicates |
| bounded verification | the inductive proofs cover the key invariants. The others remain C-level |
| Q-CONSENT legal variance across jurisdictions | PO / legal confirmation |

## 29. Remaining business decisions (all RECOMMENDED above; awaiting PO confirmation)

**MUST:**
- **D-ID.1** (B′)
- **D-ID.2** (retain)
- **D-TERM-1** (kinds)
- **D-SUSP** (S2 + effects table §9)
- **REC-0** (no)
- **I5** (M1 / INV-9)

**Also needed for closure:**
- **D-TERM-4** (A1)
- **Q-ANNUL-RE** (merge)
- **Q-CONSENT** (mandatory)
- **Q-TEN** (named predicates; the MemberSince default)
- **N-U** (per organisation)

**Later:** D-TERM-3 (withdrawal: W1 recommended), D-TERM-5 (Void vs Correct as defined), REC-IMP/INV (attested / acceptance), `grants_voting_rights`, Q-FM-MAP (election track).

**No adopted record needs changing under the recommended answers** (EM-VOC-001 is kept).

## 30. Final architecture

```text
Person (Identity)                                   Organisation / Governance (authority, organs, bans, sections)
      │ PersonId                                              │ AuthorityCheck port
      ▼                                                       ▼
┌───────────────────────── Organisation Membership  (aggregate root; one per (p,o); EM-VOC-001 "Member") ─────────────┐
│ MembershipId · holder · organisation · MembershipNumber (retained; org-unique outside)                                │
│ Periods (pairwise-disjoint lifetimes [start,end) — INV-9; guards g1 Admit, g2 lifetime-enlarging facts)               │
│   └── Period (PeriodId; legal admission-to-ending episode)                                                            │
│         ├── term segments   (Admit, Renew → [a,b))        ── Effective, Lapsed (derived; gaps preserved)            │
│         ├── type segments   (Admit, ChangeType)           ── CurrentType (never an entitlement)                     │
│         ├── restriction intervals (Suspend/Restore: full, membership-scoped) ── Suspended                           │
│         └── lifetime end    (End(kind); Annul; Correct)   ── Ended, InForce                                         │
│ Facts (append-only): effectiveAt · decidedAt · recordedAt · provenance {decision(actor, authority snapshot,         │
│                       person declaration) | attested(source, importer)} · target (Annul/Correct/Void)              │
└──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘
      │ published facts (OHS): IsMember · Suspended · CurrentType · MemberSince/Tenure variants · (Standing: E0b-S) · events
      ├──► Election (ACL; unchanged; Election-Only needs none)        ├──► Audit (fact projection)
      └──► Finance (FeePaid with PeriodId; Conformist)                 Outside the root: Application, Invitation, Fee, sections
```

## 31. Implementation readiness

- **Commands and guards identified:** §17.
- **Persistence implications understood:**
  - fact rows with the three clocks and provenance;
  - materialised current state;
  - an organisation-level number uniqueness constraint;
  - no cascade deletes.
- **Migration implications understood:** §23.
- **No premature technology decision.** No Event Sourcing.

**Not ready to implement until:**
1. the PO confirms the §29 MUST and closure items;
2. the Principal Reviewer ratifies §24's PROPOSED rows;
3. an EP-01 plan is approved for the first slice;
4. EM-SEQ-001 is respected: Full Membership is a later phase, so implementation sequencing needs a PO act.

## 32. Principal Reviewer approval checklist

**Domain**
- [ ] Person ≠ Affiliation ≠ Organisation Membership ≠ ElectionMembership: no contradiction (§4–5, §18–19)
- [ ] identity semantics B′ (pending PO)
- [ ] re-entry = a new period (pending PO)
- [ ] ending kinds (pending PO)
- [ ] suspension S2 (pending PO)
- [ ] recognition / consent (pending PO)

**Logic**
- [ ] invariants defined (§15)
- [ ] counterexamples addressed: re-entry annulment (g2); **backdated re-admission (interval g1)**; lapse gap (segments)
- [ ] INV-9 with g1 + g2 (§26)
- [ ] temporal predicates coherent (§6)
- [ ] no contradiction among the derived predicates (§16)

**Architecture**
- [ ] aggregate boundary justified by g1/g2 (§14)
- [ ] contexts stable (§18)
- [ ] authority boundary (§11)
- [ ] Election boundary preserved (§19)
- [ ] Finance boundary (§20)
- [ ] Audit boundary (§21)

**History**
- [ ] backdating
- [ ] correction
- [ ] annulment
- [ ] migration (attested)
- [ ] TrueAt
- [ ] KnownAt

**Implementation readiness**
- [ ] commands and guards (§17)
- [ ] persistence implications (§31)
- [ ] migration implications (§23)
- [ ] no premature technology choice
- [ ] no Event Sourcing requirement

**Verdict: CONDITIONAL-FINAL.** It is ratifiable once the business confirmations in §29 and the architectural ratifications in §24 are recorded.

---

**STOP.** Nothing implemented, migrated or committed. Scripts are in the session scratchpad (`final.py`, `final2.py`; research aids).

---

**Correction (ratification-readiness review, 2026-10-08; additive):** the Q-ANNUL-RE recommendation "merge = **void** the later period" (§7, §17, §24, §29) is **falsified**. It loses membership the later period legitimately provided (782 / 444,604 histories; e.g. Admit → End → re-Admit → Correct the End later), and it misuses Void. It is replaced by **supersede-by-trim (M′)** or **refuse (R)**: both keep INV-9 with 0 losses, and no PeriodId is ever rewritten (INV-14). Provenance gains a `rule` variant. See `2026-10-08-membership-architecture-ratification-readiness.md`.

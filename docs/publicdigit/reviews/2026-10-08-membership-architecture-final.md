# Membership Architecture: Final (canonical theory and architecture)

**Type:** Final architecture closure · **CONDITIONALLY RATIFIABLE** · **Date:** 2026-10-08 · **implements nothing; changes no ADR or adopted term**
**Commission:** *"Final Membership Architecture Closure, Proof and Ratification"* (2026-10-08)
**Supersedes as the canonical statement (not as history):** E0b `…-e0b-membership-architecture-finalization.md` and `…-membership-architecture-ratification-readiness.md` (**RR**). The research trail stays in the E0a/E0b documents.
**Companions:** `2026-10-08-membership-decision-register.md` · `2026-10-08-membership-implementation-readiness.md`
**Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)

> **Layers (§2 of the commission):** **[DT]** domain truth · **[BR]** requirement / business rule · **[FC]** formal consequence · **[AD]** architecture decision · **[IC]** implementation choice · **[LQ]** legal / statutory question · **[DD]** downstream context decision · **[OR]** open risk.
>
> **Claim status:** ADOPTED · PROVEN · PROVEN UNDER ASSUMPTIONS · FINITE VERIFIED · ARCHITECTURAL DERIVATION · RECOMMENDATION · BUSINESS DECISION REQUIRED · LEGAL VALIDATION REQUIRED · DOWNSTREAM DECISION · IMPLEMENTATION CHOICE · OPEN RISK · REJECTED.

---

## 0. What changed in this final pass (findings, not restatement)

1. **Verdict reclassified:** RR's "RATIFIABLE" → **CONDITIONALLY RATIFIABLE**. The mandatory business decisions are neither adopted nor architecturally isolated: two of them (I5 + synchronous INV-9; D-ID.1) can change the *justification* of the aggregate boundary or the *wording* of adopted vocabulary (§10). **None forces a structural redesign** (§10, §28).
2. **New counterexample: zero-length lifetimes.** Supersede-by-trim can require **splitting** a period if a lifetime may be empty (admitted and ended at the same instant) and overlap is pure set intersection (**52** splits in 300,000 random histories). **The fix is a new rule, NE:** a period lifetime is **non-empty** (End.effectiveAt > start). An ending at or before the admission is not an End; it is the **invalidation of the admission**. With NE: **0 splits, 0 disjointness violations** across 2.4 M runs (§5).
3. **Resolution must be order-deterministic:** facts need a **total recording order** per Membership (ties in `recordedAt` broken by a sequence) [FC].
4. **The g2 outcome is recorded on the triggering fact** (which periods were trimmed or superseded). Otherwise a later policy change would re-derive history differently [FC → AD].
5. **"Status" is not a field.** RR's `status ∈ {live, superseded, voided, annulled}` is **withdrawn as stored state**. It is a *resolution classification* derived from facts. No second lifecycle machine exists (§7).
6. **Minimality:** **Annul and Void are merged into one fact kind, `Invalidate{ground ∈ annulment | nullity}`.** The legal distinction is kept in the ground. Type segments are **conditional** on mid-period type changes being allowed. `decidedAt` is optional (§25).
7. **Person merge** cannot be guarded synchronously (the overlap is *discovered*, not created). It needs an Identity equivalence capability and a Membership **root-merge + reconciliation** act. That is downstream, not a contradiction (§16).

---

## 1. Canonical ontology

| Concept | IS | IS NOT | Owner | Identity | Persistent | Derived | Historical | Aggregate | Crosses contexts |
|---|---|---|---|---|---|---|---|---|---|
| **Person** | a human known to the platform | a member, a user role | Identity | PersonId (global, internal) | yes | no | yes | (Identity) | as a ref only |
| **Organisation** | the body in which membership is recognised | an organ, a committee | Organisation/Governance | OrganisationId | yes | no | yes | (Org) | as a ref |
| **OrganisationAffiliation** | access / role relationship (EM-VOC-002) | membership | Organisation/Governance | own id | yes | no | yes | (Org) | no |
| **Membership** (Organisation Membership; EM-VOC-001 `Member`) | the enduring membership record of one person with one organisation | a period, an election entitlement | Membership | **MembershipId** | yes | no | yes | **root** | published facts only |
| **MembershipPeriod** | one admission-to-ending legal episode | a status, a term | Membership | **PeriodId** | yes | lifetime bounds are derived from facts | yes | entity in the root | PeriodId as a reference |
| **MembershipNumber** | an organisation-issued credential | an identifier, a person id | Membership (value); uniqueness org-level | value | yes | no | via facts | attribute of the root | as a value |
| **MembershipApplication** | a person's request (process) | a membership | Membership (process) | own id | yes | no | yes | own aggregate | no |
| **Admission** | the decision/attestation that opens a period | the application | Membership | the Admit fact | yes | no | yes | fact | no |
| **Renewal** | a grant of a further term | a re-admission | Membership | the Renew fact | yes | no | yes | fact | no |
| **Term** (segments) | the granted membership time intervals | lifetime, status | Membership | — | derived from Admit/Renew | **yes** | yes | in the period | no |
| **MembershipFact** | an append-only record of an act or attestation, with 3 clocks + provenance | an event-sourcing event | Membership | fact id + sequence | yes | no | yes | in the root | as events / audit stream |
| **MembershipRestriction** | a decided, membership-scoped restriction (full suspension) | a Governance ban; a standing restriction | Membership | interval in the period | derived from facts | yes | yes | in the period | `Suspended` is published |
| **MembershipType** | configuration (fee, duration, classifications) | an entitlement | Membership (config) | TypeId | yes | no | via snapshots | outside the root | classification readable |
| **Standing** | evaluation of obligations (dues) | membership state | Membership (E0b-S) | — | — | yes | yes | — | published |
| **Recognition** | the organisation's acceptance (= Admit under REC-0 = no) | an application | Membership | — | — | yes | yes | — | — |
| **Effectiveness** | lies within a term segment | in force | Membership | — | — | yes | yes | — | — |
| **Eligibility** | qualification for a process | a membership fact | **Election** (or the other consumer) | — | — | yes (there) | — | — | consumer-owned |

## 2. Five-layer classification of the core statements

| Statement | Layer | Status |
|---|---|---|
| "Organisation Membership" = the `Member` aggregate | DT (vocabulary) | ADOPTED (EM-VOC-001) |
| an affiliation / role is not membership | DT | ADOPTED (EM-VOC-002) |
| Membership never grants a voting entitlement | DT | ADOPTED (EM-ENT-001/007) |
| re-entry = a new period, same Membership (B′) | BR | BUSINESS DECISION REQUIRED (RECOMMENDATION) |
| lapse is not an ending | FC (from the definitions) | PROVEN |
| Ended ⇒ ¬InForce | FC | PROVEN |
| period lifetimes pairwise disjoint (INV-9) | BR (I5) + FC (preservation) | preservation PROVEN UNDER ASSUMPTIONS; the rule itself is BUSINESS DECISION REQUIRED |
| one root per (p,o) (D1) | AD | ARCHITECTURAL DERIVATION (minimal under the synchronous-INV-9 business rule) |
| three clocks; append-only; total recording order | AD (+ FC for determinism) | ARCHITECTURAL DERIVATION |
| term segments | DT (granted terms) + FC (lapse-gap lemma) | PROVEN (lemma) |
| table / index design, allocator, materialisation | IC | IMPLEMENTATION CHOICE |
| consent / statutory bases | LQ | LEGAL VALIDATION REQUIRED |
| fee handling for trimmed periods; election period-binding | DD | DOWNSTREAM DECISION |
| person merge | DD (Identity) | OPEN RISK |

## 3. Final formal model (the minimum sufficient)

```text
Facts f = (seq, kind, target?, effectiveAt, decidedAt?, recordedAt, provenance, payload, consequence?)
  kinds: Admit · Renew · ChangeType* · Suspend · Restore · End(kind) · Invalidate(target, ground ∈ {annulment, nullity}) · Correct(target, values)
         (* only if mid-period type change is allowed)
  provenance ∈ { decision(actor, authoritySnapshot, personDeclaration?) , rule(ruleRef, version) , attested(sourceKind, sourceRef, importer) }
  consequence: for lifetime-enlarging facts, the recorded g2 outcome {trimmed: [(PeriodId, removedInterval)], superseded: [PeriodId]}
  seq: total recording order within one Membership (ties in recordedAt resolved by seq)

Membership m(p,o): MembershipId, MembershipNumber, periods q₁…qₙ
Period q: PeriodId; lifetime L(q) = [start, end) from Admit / End / Correct / Invalidate / recorded consequences
          termSegs(q), typeSegs(q), restrictions(q)       — all DERIVED by a deterministic fold over facts in seq order

F_τ = {f : recordedAt(f) ≤ τ}  (seq-closed);   Res(F) = deterministic fold (Invalidate removes target effect; Correct replaces values;
                                                recorded consequences applied as recorded)
```

| Predicate | Definition (over `Res(F_τ)`; `TrueAt` uses τ = now) |
|---|---|
| `Ended(q,t)` | `end(q) ≤ t` |
| `Effective(q,t)` | ∃ a term segment `[a,b)` ∋ t |
| `Recognised(q,t)` | `start(q) ≤ t` (REC-0 = no) |
| `Lapsed(q,t)` | `Recognised ∧ ¬Ended ∧ ¬Effective` |
| `Suspended(q,t)` | ∃ a restriction interval ∋ t |
| `InForce(q,t)` | `Recognised ∧ Effective ∧ ¬Ended` (S2) |
| `IsMember(p,o,t)` | ∃ q ∈ m(p,o): `InForce(q,t)` |
| `CurrentPeriod(p,o,t)` | the unique q with t ∈ L(q) (by INV-9) |
| `CurrentType(q,t)` | the type segment ∋ t (or the period type if there are no type segments) |
| `TrueAt(φ,t)` | φ at t over `Res(F_now)` |
| `KnownAt(φ,t,τ)` | φ at t over `Res(F_τ)` |
| `DecidedBy(φ,t,d)` | φ at t over `Res({f ∈ F_now : decidedAt(f) ≤ d or provenance ≠ decision})` |
| `FirstAdmissionDate`, `CurrentAdmissionDate`, `CurrentPeriodDuration`, `ContinuousMembershipDuration`, `CumulativeMembershipDuration` | as in RR §18 (Q-TEN). **No `member_since` / `tenure` field** |

## 4. Scenario validation (B′ + D1; the meaning, not just representability)

Each row answers: what the Membership is, what the Period is, which facts exist, what is true now, what was known at τ, and what was decided by d. **"✓" = identity preserved and no invariant broken.**

| # | Scenario | Membership / Period | Facts | Truth now · KnownAt · DecidedBy | Result |
|---|---|---|---|---|---|
| 1 | first admission | new M; q1 | Admit | in force from effectiveAt; known from recordedAt; decided at decidedAt | ✓ |
| 2 | resignation | M; q1 ends | End(resignation) | not in force from effectiveAt | ✓ |
| 3 | re-entry | M; **q2** | Admit | q1 history kept; q2 in force | ✓ (B′) |
| 4 | repeated re-entry | M; q3… | Admit… | lifetimes ordered and disjoint (g1) | ✓ |
| 5 | lapse | M; q1 | none (time) | ¬Effective; not ended | ✓ |
| 6 | renewal | M; q1 | Renew | a new segment; the gap is kept if late | ✓ (lemma) |
| 7 | suspension | M; q1 | Suspend | Suspended; IsMember unchanged (S2) | ✓ |
| 8 | restoration | M; q1 | Restore | the restriction ends | ✓ |
| 9 | historical migration | M; q's | Admit/End (attested) | true from historical dates; known from import | ✓ |
| 10 | correction | M; q | Correct (+ g2 if enlarging) | TrueAt changes; KnownAt(τ<rec) unchanged | ✓ |
| 11 | annulment | M; q | Invalidate(annulment) | the effect removed; history shows it | ✓ |
| 12 | void | M; q | Invalidate(nullity) | treated as never reflecting a membership | ✓ |
| 13 | **person merge** | two roots M1(P1,O), M2(P2,O) | — | **an overlap may be discovered** | **OR:** needs an Identity equivalence + a root-merge/reconciliation act (§16) |
| 14 | organisation merge | new roots in the target org (attested), cross-reference | Admit (attested) | number collisions → Correct on the root | ✓ |
| 15 | organisation split | new roots in the new org | Admit (attested) | ✓ | ✓ |
| 16 | deleted affiliation | M persists (INV-7) | — | unchanged | ✓ |
| 17 | duplicate imported person | = 13 | — | — | OR (Identity) |
| 18 | multiple organisational roles | affiliation; not membership | — | unchanged | ✓ |
| 19 | honorary membership | a type (or a rule-based Admit) | Admit / ChangeType | ✓ | ✓ |
| 20 | statutory / ex-officio | Admit with `rule` provenance | Admit(rule) | ✓ | ✓ (validity: LQ) |
| 21 | backdated admission | q | Admit (effectiveAt < recordedAt) | TrueAt ≠ KnownAt for τ < rec | ✓ (g1 interval) |
| 22 | backdated renewal | q | Renew | a segment from the backdated start | ✓ |
| 23 | backdated ending | q | End | ✓ | ✓ |
| 24 | correction of an admission date | q | Correct (g2 if earlier) | ✓ | ✓ (needs NE) |
| 25 | correction of an ending date | q | Correct (g2 if later) | ✓ | ✓ (needs NE) |
| 26 | annulment of an ending | q | Invalidate(annulment) of End (g2) | ✓ | ✓ |
| 27 | annulment after re-entry | q1, q2 | Invalidate + **consequence** (q2 trimmed / superseded, or refused) | ✓ | ✓ (§6) |
| 28 | correction after re-entry | q1, q2 | Correct + consequence | ✓ | ✓ (§6; NE) |
| 29 | late recording | any | recordedAt ≫ effectiveAt | KnownAt reveals it | ✓ |
| 30 | retroactive statutory change | facts keep `rule` versions; retroactive effects need explicit Correct/Invalidate facts with `rule` provenance | — | ✓ | ✓ (whether retroactive is legal: LQ) |

## 5. INV-9: proof and verification

**INV-9:** `∀ q₁ ≠ q₂ ∈ m(p,o) (both resolved-live): L(q₁) ∩ L(q₂) = ∅`.

**Assumptions (explicit):**
- **A1:** the guards g1 and g2 are evaluated synchronously on m(p,o) against `Res(F)` at record time;
- **A2 (NE):** every lifetime is **non-empty** (End / Correct must keep end > start; otherwise the fact is refused, or the admission is invalidated instead);
- **A3:** a total recording order (seq);
- **A4:** each fact changes **at most one bound** of **one** target lifetime;
- **A5:** g2's consequence is recorded and applied as recorded.

**Induction over the facts** (P(H) = pairwise disjoint after resolution):

| Fact | Effect on lifetimes | Why P is preserved |
|---|---|---|
| ∅ | none | trivially |
| **Admit(v)** | adds `[v, ∞)` | **g1:** accepted only if every live lifetime ends ≤ v, so it is disjoint from all of them |
| **End(e)** on an open q | `[s,∞) → [s,e)`, e > s (A2) | a subset of the old lifetime |
| **Correct end earlier / start later** | shrinks | a subset |
| **Correct end later / start earlier; Invalidate(End)** | enlarges `L_old → L_new ⊋ L_old` | **g2:** refuse, or trim every intersecting q′ to `q′ \ L_new` (below) |
| **Invalidate(Admit)** (annulment or nullity) | removes q | removal |
| Renew, ChangeType, Suspend, Restore | no lifetime change | — |

**Supersede-by-trim: full case analysis.** Let `L_new = [s,e)` arise from enlarging `L_old = [s₀,e₀)` (non-empty, by A2), with `L_old ⊆ L_new`, and let `q′ = [c,d)` be disjoint from `L_old` by P(H). By A4 exactly one bound moved.

| Relation of q′ to L_new (Allen) | Possible? | `q′ \ L_new` | Result |
|---|---|---|---|
| disjoint / adjacent (meets) | yes | q′ | untouched (adjacency is not overlap: `[a,b)`, `[b,c)`) |
| overlaps from the left (c < s < d ≤ e) | yes (start moved earlier) | `[c, s)` | trimmed (right end cut) |
| overlaps from the right (s ≤ c < e < d) | yes (end moved later) | `[e, d)` | trimmed (left end cut) |
| covered (s ≤ c, d ≤ e), incl. equal | yes | ∅ | superseded |
| **q′ strictly contains L_new (c < s, e < d)** | **impossible:** `L_old ⊆ L_new ⊂ q′` contradicts `L_old ∩ q′ = ∅`, **because L_old ≠ ∅ (A2)** | would be **two pieces = a split** | **excluded by A2** |
| several later periods | each handled independently, since all are disjoint from each other and from L_old | — | trimmed or superseded each |
| repeated enlargements | each step is again one of the cases above (induction) | — | — |

⇒ **No split, no PeriodId change, no deletion, no fact rewrite, and no external reference change is ever required.** Fee and election references keep pointing at the same PeriodId. Their *meaning* changes only by the recorded trim. **INV-9 is PROVEN UNDER ASSUMPTIONS A1–A5.**

**Computational evidence (FINITE VERIFIED):**
- exhaustive: 444,604 histories (RR) with 0 violations;
- random property-based, **this pass**: **300,000 histories × 8 configurations** (time domain 3–10; up to 14 facts; random targets; effective times independent of record order; multiple corrections, invalidations and re-entries; same-instant boundaries; zero-length attempts).

| Configuration | Splits | Disjointness violations |
|---|---|---|
| set semantics, **without NE**, supersede | **52** (counterexample: zero-length lifetime) | 0 |
| set semantics, with NE (supersede / refuse) | 0 | 0 |
| point-inclusive test (RR checkers), with or without NE | 0 | 0 |

## 6. Supersede-by-trim: meaning

| Term | Kind | Meaning |
|---|---|---|
| **Valid** | resolution classification | the fact is not invalidated |
| **Effective** | derived predicate | t lies in a term segment |
| **Ended** | derived predicate | t ≥ the lifetime end |
| **Annulled** | resolution classification of a fact (`Invalidate{annulment}`) | a real decision was later set aside |
| **Voided** | resolution classification (`Invalidate{nullity}`) | the record never reflected reality |
| **Trimmed** | resolution classification of a period (from a recorded g2 consequence) | the period was valid; part of its lifetime is now covered by another period's reinstated lifetime |
| **Superseded** | resolution classification (trimmed to ∅) | the period was valid when created; all of its lifetime is now covered |

**None of these is a stored mutable state.** They are the output of `Res(F)`. A materialised projection may cache them (an IMPLEMENTATION CHOICE), but they are never authoritative. **There is no second lifecycle state machine.**

## 7. Facts: semantic table

| Operation | Domain meaning | Historical event existed? | Removes effect? | Changes values? | New fact? |
|---|---|---|---|---|---|
| **End** | the membership episode ends (from effectiveAt) | yes | no (it *is* an effect) | no | yes |
| **Invalidate{annulment}** | **"the decision was real but later invalidated"** (appeal, challenge, lack of competence) | **yes** | yes (of its target) | no | yes |
| **Invalidate{nullity}** | **"the stored record never represented reality"** (wrong person, duplicate, test data) | **no** | yes | no | yes |
| **Correct** | **"the decision was real but its recorded attributes were wrong"** | yes | no | **yes** | yes |
| **Supersede / Trim** | not a fact: the **recorded consequence** of a lifetime-enlarging fact | (the triggering fact) | partially (the overlap only) | no | no (part of the trigger) |
| Restore | ends a suspension only | yes | ends the restriction | no | yes |
| Re-Admit | not a kind: an Admit on a Membership with ended periods | yes | — | — | yes (Admit) |

## 8. Temporal semantics: worked examples

| Ex. | Facts | What was true | What was known at τ | What was decided by d | Current belief | Audit shows |
|---|---|---|---|---|---|---|
| **A** admission decided 1 Jan, effective 1 Feb, recorded 2 Jan | Admit(eff 1 Feb, dec 1 Jan, rec 2 Jan) | member from 1 Feb | from 2 Jan: "member from 1 Feb"; before 2 Jan: nothing | by 1 Jan: decided | member from 1 Feb | decision 1 Jan, effective 1 Feb, recorded 2 Jan, actor, authority |
| **B** termination decided 1 Mar, effective 15 Feb, recorded 5 Mar | End(eff 15 Feb, dec 1 Mar, rec 5 Mar) | not a member from 15 Feb | before 5 Mar: member on 20 Feb; from 5 Mar: not | by 1 Mar | ended 15 Feb | a backdated ending, all three clocks |
| **C** termination recorded today, later annulled | End(rec today) → Invalidate{annulment}(rec later) | never ended | between the two recordings: ended; afterwards: never ended | both decisions | in force | both facts, the annulment reason, the actors |
| **D** migration creates an attested fact from 2019 | Admit(eff 2019, attested, rec import day) | member since 2019 | before import: unknown | DecidedBy: no decision provenance (attested) | member since 2019 | source register, importer |
| **E** a correction in 2026 moves effective 2020 → 2019 | Correct(target Admit, eff 2019, rec 2026) (+ g2 if it overlaps) | member from 2019 | before 2026: from 2020 | — | from 2019 | the original + the correction + the reason |

**Separation:**
- **domain time:** effectiveAt, decidedAt;
- **record time:** recordedAt + seq, the moment the fact entered PublicDigit's authoritative record;
- **database / audit / technical event timestamps:** IMPLEMENTATION, never business semantics.

**Not Event Sourcing:**
- the authoritative store is the fact list plus an optional materialised state;
- no requirement needs state to be rebuilt *only* from events, event-stream versioning, or replay as the primary persistence;
- **Event Sourcing is REJECTED as a necessity** (it remains an allowed IMPLEMENTATION CHOICE).

## 9. Lifecycle, restrictions and suspension

`Suspended` (Membership-owned fact) = a decided, membership-scoped restriction of all membership rights.

| Effect | Owner |
|---|---|
| IsMember, InForce | **Membership:** unchanged (S2, RECOMMENDATION) |
| member count | organisation statute / policy |
| dues | statute / policy → Membership fee facts (E0b-S) |
| standing | Membership E0b-S (derived) |
| committee participation | Governance |
| assembly attendance, quorum | statute / Governance |
| services | organisation policy |
| election eligibility | Election (criteria) |
| voting exercisability | Election (EM-FM-003/FM-6, deferred; EM-GOV-003) |
| renewal while suspended | statute (default: allowed; the term runs) |
| tenure while suspended | Q-TEN named predicates (statute chooses whether it counts) |

**The non-payment chain** (§11 RR, confirmed): unpaid fee (fact) → standing (derived) → optional restriction (statute; not a membership state) → **optional End(non-payment)**, only by an explicit fact with `decision` or `rule` provenance. **Finance never ends a membership.**

## 10. Aggregate boundary: D1, D2, D3

| | D1: one root per (p,o) | D2: one aggregate per period | D3: a root + coordinated period entities |
|---|---|---|---|
| INV-9 consistency | synchronous, local | needs a cross-aggregate lock or saga / reconciliation | as D2, unless the root locks |
| concurrency | per person+organisation (very low contention) | per period | mixed |
| backdated corrections / re-entry | local | cross-aggregate | cross |
| period references | PeriodId (an entity id) | PeriodId (aggregate id) | PeriodId |
| fees / election / audit | unchanged | unchanged | unchanged |
| tenant isolation | per (p,o) inside the tenant | same | same |
| complexity | low | higher (coordination) | highest |

**Answer to the commission's key question:**
- **D1 is not itself a domain object beyond what EM-VOC-001 already names.** It is the **consistency mechanism for a business rule**: synchronous INV-9.
- **It coincides with the adopted domain concept.** The Organisation Membership *is* the per-(p,o) record. That coincidence is why D1 is attractive.
- **D1 is minimal *if and only if* the PO adopts "no overlapping periods, even transiently" (I5 + sync).**
- If the PO allows overlaps (M2) or eventual reconciliation, **D1 stays correct** (no redesign), but D2 becomes admissible and D1 is no longer *proven* minimal.
- **Therefore no pending answer forces a structural redesign. Only the minimality claim is conditional.**

## 11. Membership Type

- MembershipType = configuration.
- Type segments are the historical assignment. They are **needed only if a mid-period ChangeType is allowed** (BUSINESS DECISION REQUIRED, "Q-TYPE-CHANGE", folded into the D-ID/type questions). Otherwise the type is a period attribute and a type change = End + re-Admit.
- Fee and duration are snapshotted at use.
- A re-entry with a different type is natural (a new period).
- Type-based eligibility = an Election criterion.
- **A MembershipType never creates a VotingEntitlement** (ADOPTED).
- `grants_voting_rights`: BUSINESS DECISION REQUIRED (retire, or reinterpret as a non-granting classification).

## 12. Membership Number

| Aspect | Classification |
|---|---|
| number value as a credential, on the root, retained across re-entry | RECOMMENDATION (BUSINESS DECISION REQUIRED: D-ID.2) |
| uniqueness per organisation | **domain invariant if adopted** (N-U), **enforced as a database constraint** (IC), outside the aggregate (PROVEN: it spans persons) |
| allocation | allocator responsibility (IC) |
| history / correction | Correct facts on the root |
| type prefixes | not supported by default |
| merger collisions | renumbering by Correct facts |
| concurrent allocation | the allocator plus the uniqueness constraint (IC) |
| Number Registry | **REJECTED** (no domain reason) |

## 13. Person / Affiliation

- Person ≠ Affiliation ≠ Membership (ADOPTED EM-VOC-002 + INV-7).
- Affiliation deleted or role changed, user account removed, or historical import without an affiliation: **the Membership persists**. It is keyed on PersonRef, not on the affiliation.
- The legacy `ON DELETE CASCADE` violates this. It is a migration defect.

## 14. Authority

The snapshot is `{actor, claimedCapacity, ruleRef, policyVersion, checkResult, checkedAt}`.
- Changed statutes, roles or organs: the fact explains itself.
- Later discovery of an unauthorised act: `Invalidate{annulment}` (a decision existed, but without competence) or `{nullity}` (no decision existed), with the reason.
- **No Governance rule content is copied.**

## 15. Election, Finance, Audit

- **Election (ABSOLUTE; unchanged):**
  - Election-Only → ImportedVoterRegistry;
  - Full Membership → MembershipRegistry;
  - the ADR_20261004_1549 chain (exercisability is inside VotingEntitlement / `canVote`);
  - ADR-T11.
  - **Checked: EM-VOC-001 ✓ (no change), EM-ENT-001…007 ✓, EM-EO-001…004 ✓ (no membership needed), EM-FM-001…006 ✓ (deferred; FM-5 maps End kinds and FM-6 suspension), ADR_20261004_1549 ✓, ADR-T11 ✓. No contradiction.**
  - Membership publishes: IsMember, Suspended, CurrentType, the named tenure predicates, standing (E0b-S), End/Suspend events, and PeriodId only if Q-FM-MAP option A.
- **Finance:**
  - Fee(P2) **stays historically valid**: the payment happened and the reference is unchanged.
  - When P2 is trimmed or superseded, Membership publishes **`PeriodTrimmed{periodId, removedInterval, causeFactId}`** / **`PeriodSuperseded{…}`**.
  - **Finance (with E0b-S) decides** refund, credit or keep (DOWNSTREAM DECISION).
  - Migrated fees are attested; a refunded fee is a Finance fact.
- **Audit:**
  - reconstructs what happened, when it became effective, when it was decided and recorded, why it changed, who acted, and under which authority;
  - it is a projection of the facts, **never a second source of truth**.

## 16. Person merge (downstream; no contradiction)

- Two roots M1(P1,O) and M2(P2,O) for one human, discovered later. **The overlap cannot be prevented synchronously:** it is discovered, not created.
- **Required future capabilities (outside Membership):**
  - **Identity:** a person-equivalence act;
  - **Membership:** a `MergeMembership(survivor, merged)` act. The merged root stays resolvable as *merged-into* (no id deleted). Its periods are re-homed by facts, and **INV-9 overlaps are reconciled by an organ's decision** (Invalidate or Correct facts).
- INV-1 (holder constant) is preserved **per root**. The merge is an explicit act, not a rewrite.
- **OPEN RISK (DOWNSTREAM: Identity).** **Not added to Membership now.**

## 17. Contradiction audit (pairs; one owner per truth)

| Pair | Owner of the truth | Changes it | Derives / consumes | Sync? | Shared persistence? | Duplicated truth? | Wrong inference risk |
|---|---|---|---|---|---|---|---|
| Membership – Person / Identity | Identity owns the person; Membership owns membership | each its own | Membership consumes PersonRef | async refs | no | no | the merge case (§16) |
| Membership – Affiliation | Org owns the affiliation | Org | — | — | no (cascade = a defect) | no | inferring membership from role `'member'`, **forbidden** (EM-VOC-002) |
| Membership – Governance | Governance owns authority rules and bans | Governance | Membership stores snapshots | sync check at command time | no | no (snapshot ≠ rule) | — |
| Membership – Finance | Membership owns fee facts; Finance owns accounting | each | Finance consumes FeePaid / trim events | async | no | no | Finance must not infer membership state |
| Membership – Election | Membership owns facts; Election owns eligibility and entitlement | each | Election consumes published facts | async (as of a reference date) | no | no | Election must not read tables |
| Membership – Audit | Membership facts | Membership | Audit projects | async | no | **no** (Audit is not authoritative) | — |
| Membership – Type | Membership (config + segments) | Membership | Election reads the classification | — | — | no | type ≠ entitlement |
| Temporal | Membership facts (three clocks) | — | all | — | — | no | technical timestamps ≠ business time |
| Number | Membership root (value); uniqueness org-level | Membership | consumers | — | — | no | number ≠ identifier |
| Migration | — | — | — | — | — | — | legacy status read as truth: **forbidden** |

**No unresolved architectural contradiction.** Open: §16 (Identity), fee policy (Finance), FM-5/FM-6/Q-FM-MAP (Election, deferred).

## 18. Requirements traceability (summary; full cards RR §25 / E0b §22)

| Need | Requirement | Rule | Invariant | Predicate | Command | Guard | Aggregate | Contract | Acceptance test |
|---|---|---|---|---|---|---|---|---|---|
| identity | REQ-MEM-ID | B′ | INV-1, INV-14 | CurrentPeriod | Admit | g1 | M | MembershipId / PeriodId | re-entry: same M, new q |
| admission | REQ-MEM-RECOGNITION | basis + declaration / rule / attested | INV-13 | Recognised | Admit | consent rule, authority | M | — | an invalid basis is refused |
| re-entry | REQ-MEM-ID | new period | INV-9 | — | Admit | g1 | M | — | a backdated re-admit into an existing lifetime is refused |
| term / lapse / renewal | REQ-MEM-HISTORY | segments | INV-12 | Effective, Lapsed | Renew | period not ended | M | — | a late renewal keeps the gap |
| termination | REQ-MEM-END | End kinds; NE | INV-2/3, NE | Ended | End | NE | M | End event | end ≤ start is refused (→ Invalidate) |
| suspension | REQ-MEM-SUSPENSION | S2 | — | Suspended | Suspend / Restore | in force | M | Suspended | IsMember unchanged |
| correction | REQ-MEM-HISTORY | Correct | INV-6/9 | KnownAt | Correct | g2, NE | M | — | KnownAt(τ<rec) unchanged |
| annulment | REQ-MEM-END | Invalidate | INV-9/14 | — | Invalidate | g2 | M | PeriodTrimmed / Superseded | the trim is recorded; ids intact |
| number | REQ-MEM-NUMBER | root, org-unique | INV-10 | — | Admit | uniqueness (IC) | M + org constraint | number | duplicate refused |
| type | REQ-MEM-TYPE | segments / attribute | INV-11 | CurrentType | ChangeType* | in force | M | classification | no entitlement |
| authority | REQ-MEM-AUTHORITY | snapshot | INV-13 | — | all decisions | AuthorityCheck | M ← Gov | — | self-explaining fact |
| historical truth / audit | REQ-MEM-TIME | three clocks, seq | INV-5/6 | TrueAt / KnownAt / DecidedBy | — | — | M | fact stream | the five worked examples (§8) |
| Election integration | (Election-owned) | published facts | INV-11 | IsMember… | — | — | — | OHS | Election reads no table |
| Finance integration | (Finance-owned policy) | fee facts + trim events | — | — | — | — | Fee aggregate | FeePaid, PeriodTrimmed | Fee(P2) reference unchanged after a trim |

**Incomplete (marked):**
- standing (E0b-S: no rule yet);
- consent per legal basis (LQ);
- person merge (Identity).

## 19. Minimality test

| Element | Removable? | Reason |
|---|---|---|
| root (D1) | no, **if** sync INV-9; otherwise it becomes optional | §10 |
| PeriodId | no | references, legal episodes |
| term segments | no | the lapse-gap lemma |
| type segments | **yes, if** mid-period type change is disallowed | §11 (conditional) |
| restriction intervals | no | suspension |
| **Annul + Void as two kinds** | **merged into `Invalidate{ground}`** | the same structural effect; the ground keeps the legal meaning |
| decidedAt | **optional** (null when unknown or attested) | needed for future-effective decisions, withdrawals, DecidedBy |
| recordedAt + seq | no | KnownAt, determinism |
| effectiveAt | no | truth at t |
| provenance (3 variants) | no | validity: decision / rule / attested |
| authority snapshot | no | explainability |
| recorded g2 consequence | no | policy-version independence |
| stored status | **removed** (derived) | §6 |
| Number Registry | removed (never needed) | §12 |
| Event Sourcing | not required | §8 |

## 20. Final classification of the major claims

| Claim | Classification | Evidence | Confidence |
|---|---|---|---|
| Architecture D (D1) | ARCHITECTURAL DERIVATION; minimal **only under** BUSINESS DECISION REQUIRED (sync I5) | §10; P12 (RR) | high (structure) / conditional (minimality) |
| B′ | RECOMMENDATION → BUSINESS DECISION REQUIRED | EM-VOC-001 fit; legal episodes; §4 | high |
| INV-9 preservation | PROVEN UNDER ASSUMPTIONS (A1–A5) + FINITE VERIFIED | §5 | high |
| I5 (the rule itself) | BUSINESS DECISION REQUIRED | no counterexample in the corpus | medium-high |
| I5 from INV-9 | PROVEN | interval disjointness | certain |
| supersede-by-trim | PROVEN UNDER ASSUMPTIONS (NE, A4) + FINITE VERIFIED; the choice vs refuse = BUSINESS DECISION REQUIRED | §5 | high |
| NE (non-empty lifetimes) | BUSINESS DECISION REQUIRED (RECOMMENDATION; required by the proof) | the counterexample (52 splits) | high |
| three clocks | ARCHITECTURAL DERIVATION | counterexamples E0a-4; §8 | high |
| facts (append-only, provenance, seq) | ARCHITECTURAL DERIVATION | §3, §8 | high |
| term segments | PROVEN (lemma) + domain concept | RR §10 | high |
| suspension (S2) | RECOMMENDATION → BUSINESS DECISION REQUIRED | §9 | medium |
| membership number on the root | RECOMMENDATION → BUSINESS DECISION REQUIRED; uniqueness outside = PROVEN | §12 | high |
| authority snapshot | ARCHITECTURAL DERIVATION | §14 | high |
| Election boundary | ADOPTED (preserved) | §15 | certain |
| Finance boundary | ARCHITECTURAL DERIVATION + DOWNSTREAM DECISION (fee policy) | §15 | high |
| Audit boundary | ARCHITECTURAL DERIVATION | §15 | high |
| Event Sourcing necessity | REJECTED | §8 | high |
| void-merge (E0b) | REJECTED (counterexample, RR) | — | certain |
| person merge | OPEN RISK (DOWNSTREAM: Identity) | §16 | — |

## 21. Statistics / ML

- **Statistics:** unavailable. Generated frequencies are model counts, not real-world statistics.
- **ML intentionally deferred.** Legitimate future uses, with real data only: duplicate person detection (§16), migration anomaly detection, identity-resolution suggestions, unusual transitions, data quality.
- **ML never decides** semantics, legal validity, authority, termination, identity or voting rights.

## 22. Verdict

**CONDITIONALLY RATIFIABLE.**
- **The architecture is stable:**
  - the aggregate boundary is justified and stays correct under every pending answer;
  - the temporal, identity and reversal semantics are coherent and proven under explicit assumptions;
  - the Election, Finance and Audit boundaries are preserved.
- **Not RATIFIED,** because the mandatory business decisions (see the decision register) are not yet adopted. Two of them condition *justifications*, not structure:
  - I5 + synchronous INV-9 condition D1's minimality;
  - D-ID.1 conditions EM-VOC-001's wording, which only an A-answer would require amending.

---

**STOP.** Scripts (research aids, session scratchpad): `ratify4.py`, `closure.py` (random property-based; seed 20261008).

---

**Superseded as the canonical statement (2026-10-08, additive)** by `2026-10-08-membership-architecture-ratified-candidate.md`. Changes:
- NE is withdrawn as a domain rule and replaced by guard ER;
- the empty-lifetime contradiction from full supersession is removed;
- Trim is a recorded consequence fact;
- Annul and Void are separate kinds again;
- Effective → TermCovered; Recognised removed;
- three-valued predicates under imprecise dates.

Details in `2026-10-08-membership-proof-and-verification.md`.

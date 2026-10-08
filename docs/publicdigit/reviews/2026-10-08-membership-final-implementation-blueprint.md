# Membership Final Implementation Blueprint (follows the architecture; no code)

**Date:** 2026-10-08 · **Supersedes:** `…-membership-implementation-blueprint.md` · **implements nothing**
**Reference:** `…-membership-architecture-ratified.md` (**MA**) · `…-membership-final-decision-register.md` (**FDR**)

## 1. Gate

**CONDITIONAL GO.** No Membership-domain code may start until:
- **G1:** the Principal Reviewer ratifies RA-1…RA-15;
- **G2:** **BD-I5 and BD-SYNC** are answered (they fix the aggregate);
- **G3:** a PO sequencing act (EM-SEQ-001);
- **G4:** an EP-01 plan is approved;
- **G5:** there is a story ID (frozen baseline).

**Per-command prerequisites:**
- BD-CONSENT / LQ-1 / BD-REC0 before Admit;
- BD-REV before reversals;
- BD-2 / BD-NU before the number;
- BD-TYPE before ChangeType;
- BD-NORM before migration.

## 2. Domain (pure PHP; `app/Contexts/Membership/Domain`)

- **Aggregate root** `Membership` (EM-VOC-001 `Member`): MembershipId, PersonRef, OrganisationId, MembershipNumber, periods, the fact log, a seq counter.
- **Entity** `MembershipPeriod`: PeriodId; derived lifetime, segments, restrictions.
- **Value objects:** each fact kind; `Provenance`; `AuthorityEvidence`; `Interval` (`[from, until)`); `DatePrecision` (metadata only).
- **Domain services (pure):**
  - `Resolution`: the fold over live facts, with CFC liveness;
  - `MembershipPredicates`: the MA §3 DAG, two-valued;
  - `OverlapGuard`: g1, g2 (refuse | trim), ER, CFC revival check; scope key per BD-I5.
- **Policies:** `ConsentPolicy` (BD-CONSENT) · `ReversalPolicy` (BD-REV).

## 3. Application (DTO commands; no Eloquent / Facades)

| Command | Guards | Facts appended (atomically) |
|---|---|---|
| AdmitMember | g1, consent, authority | Admit |
| RenewMembership | lifetime not ended | Renew |
| ChangeMembershipType* | in lifetime | ChangeType |
| Suspend / RestoreMembership | in lifetime; authority | Suspend / Restore |
| EndMembership | open period; authority or the member's resignation | End |
| AnnulFact / VoidFact | authority (organ vs registrar); **ER on the CFC revival** | Annul / Void (consequences become not live per CFC) |
| CorrectFact | g2 / ER; authority; never the holder | Correct + Trim(s) |
| ImportHistoricalMembership | g1 / g2 / ER; BD-NORM authority; uniqueness | attested Admit / End / Renew with a normalisation note |
| MergeMemberships (future, DS-3) | an organ's decision | merge + reconciliation facts |

**Ports:** AuthorityCheck (Governance) · MembershipRepository · NumberAllocator · MembershipFactsQuery · Clock · IdempotencyStore.

## 4. Infrastructure (implementation choices)

- **`memberships`:** one row per (organisation, person), with an optimistic version.
- **`membership_periods`:** a materialised cache, **not** authoritative.
- **`membership_facts`:** append-only, with `UNIQUE(membership_id, seq)` and `cause_fact_id`, `target_fact_id`, `effective_at`, `decided_at`, `recorded_at`, `provenance` (json), `payload` (json), `idempotency_key UNIQUE`. **No UPDATE or DELETE.**
- `UNIQUE(organisation_id, membership_number)` if BD-NU = per organisation.
- **No cascade deletes** from affiliations.
- Tenant scoping on every row.

## 5. Integration

- **Membership → Election:** the query port + an Election-side ACL. Consumed only in the Full Membership phase.
- **Membership → Finance:** FeePaid, PeriodTrimmed, TrimRevived.
- **Membership → Audit:** the fact stream.
- **Governance → Membership:** the AuthorityCheck port.
- **Identity → Membership:** PersonRef; a future equivalence event.

## 6. Migration

| Legacy | Disposition |
|---|---|
| two Member models | MIGRATE → one aggregate |
| status vocabularies | REINTERPRET → attested facts |
| `expired` | DEPRECATE (derived) |
| `joined_at` / `expires_at` | MIGRATE → Admit + segment (normalised, precision noted) |
| renewal ledger | MIGRATE → Renew |
| `suspended` | MIGRATE → Suspend |
| `organisation_user_id` UNIQUE | REINTERPRET → the (p,o) key |
| affiliation cascade | DELETE (the cascade) |
| membership number | MIGRATE (root; uniqueness per BD-NU) |
| fee fields | MIGRATE → Fee (E0b-S) |
| `grants_voting_rights` | per BD-GVR |
| `endMembership` | DELETE |
| six re-entry paths | → AdmitMember |
| missing authority | attested; **never fabricated** |

**Dry-run reports first.**

## 7. Sequencing

1. fact store + seq + idempotency + provenance;
2. aggregate core: Admit / End / Resolution / predicates / KnownAt;
3. term: Renew and segments;
4. reversals: Annul / Void / Correct with g2 / ER / CFC + Trim;
5. restrictions;
6. number;
7. migration;
8. published query port.

## 8. Tests (RED-first seeds)

- re-entry: the same MembershipId and a new PeriodId;
- a backdated re-admission into an existing lifetime is refused;
- **scenario E → F:** after Annul(Correct), the coverage equals the counterfactual, and P2's trim is no longer live;
- an annulment whose revival would overlap a later admission is refused;
- a late renewal preserves the gap;
- KnownAt reproduces a pre-correction report;
- Void is excluded from statistics; Annul keeps the history;
- a suspended member: IsMember true;
- an empty episode is never trimmed into another period (ER);
- no Membership table is read by Election (architecture test);
- deleting an affiliation keeps the history;
- a retried command creates no duplicate fact.

## 9. TODOs

| ID | Pri | Owner | Reason | Dependency | Blocking | Type | Phase |
|---|---|---|---|---|---|---|---|
| T-01 | **P0** | Principal Reviewer | ratify RA-1…RA-15 | — | yes | decision | ratification |
| T-02 | **P0** | PO | **BD-I5** | — | yes | decision | ratification |
| T-03 | **P0** | PO | **BD-SYNC** | — | yes | decision | ratification |
| T-04 | P1 | PO | sequencing act (EM-SEQ-001) | T-01…03 | yes | decision | slice 1 |
| T-05 | P1 | PO | EP-01 plan for slice 1 + a story ID | T-04 | yes | decision | slice 1 |
| T-06 | P1 | PO + legal | BD-CONSENT / LQ-1, BD-REC0 | T-01 | for Admit | decision | slice 2 |
| T-07 | P1 | PO | BD-REV, BD-1 (naming), BD-2, BD-NU | T-01 | for the respective slices | decision | slices 2–6 |
| T-08 | P2 | Finance / E0b-S | DS-1, DS-5 | T-01 | for slice 4 / standing | decision | before Full Membership |
| T-09 | P2 | Governance | DS-4 authority port; BD-NORM role | — | for slices 5 / 7 | decision + implementation | before Full Membership |
| T-10 | P2 | PO | BD-TYPE, BD-SUSP (term pause), BD-GVR, BD-TEN, BD-3, BD-TERM4 | T-01 | for optional facts | decision | before Full Membership |
| T-11 | P3 | Election | DS-2 | Full Membership phase | no | decision | Full Membership |
| T-12 | P3 | Identity | DS-3 | — | no | decision + implementation | later |
| T-13 | P3 | legal | LQ-2…4 | — | no | validation | later |
| T-14 | P4 | future | advisory ML (duplicates, migration anomalies) | real data | no | enhancement | future |
| T-15 | P2 | PO | authorise the model-neutral fixes and `PBDIGIT-257/-202/-209` | — | no | decision | any time |

---

## Amendment (final challenge round)

- **Domain:** remove the `Trim` fact kind. Lifetime-enlarging facts carry `consequences: [(PeriodId, removedInterval)]`. Replace the g1 / ER guards by **one post-condition check G\*** (INV-SHAPE ∧ INV-9 over the scope), plus the g2 consequence generator under the trim policy.
- **Persistence:** consequences are stored with the fact (json or a child table keyed by the fact id). An optional DB exclusion constraint per (organisation, person) is a safety net, not the domain rule.
- **Tests:** add CX-A, CX-B, CX-C (refused / no fragmentation); recorded-counterfactual equality after Annul and Void.
- **Gate:**
  - **G2 becomes:** "BD-I5 / BD-SYNC / BD-REV answered **before the reversal slice** (step 4)". They no longer block slices 1–3.
  - **Readiness:** CONDITIONAL GO. It remains conditional on the Principal Reviewer's ratification act, a sequencing act (EM-SEQ-001), an EP-01 plan and a story ID.

---

## Amendment (final closure round)

- **Domain:** implement CFC+ in `Resolution` (a justification check against the cause-target's **base** interval; never against resolved lifetimes). G\* = the `InvariantPreservingAcceptance` domain service: ResolveCandidate → check → Commit. **A Correct requires a live target.**
- **Tests:** add the §13.1 orphan pattern (cause → new End on the target → the trimmed period revives; coverage preserved) and cases A–K from §13.6.

### Legacy → target (final)

| Legacy | Target | Treatment | Data-loss risk | Evidence required |
|---|---|---|---|---|
| two Member models | one Membership aggregate | merge into facts | divergent fields | a field-by-field diff |
| `organisation_users` | Affiliation (Organisation context) | keep; **not** membership | the cascade deletes history | remove the cascade first |
| `user_organisation_roles` | Affiliation roles | keep; role `member` ≠ membership | misreading the role as membership | EM-VOC-002 |
| status fields (3 vocabularies) | attested facts | reinterpret | `archived` → a record flag | a mapping table |
| `fees_status` / `fee_state` | Fee (E0b-S) | migrate one truth | two disagreeing truths | reconciliation report |
| `grants_voting_rights` | a type classification (BD-GVR) | keep the column until decided | — | — |
| membership number | the root credential | migrate; per-organisation uniqueness | collisions on merge | a uniqueness report |
| expiry / `membership_expires_at` | the first term segment | derive | lapse already stored as `expired` | the renewal ledger |
| renewal ledger | Renew facts | migrate | gaps before the ledger existed | — (mark unknown) |
| suspension | Suspend (attested) | migrate | unreachable in legacy (few or none) | — |
| termination (`ended`, `endMembership`) | End (attested; kind = legacy) | migrate; delete the dormant code | — | — |
| application | MembershipApplication (unchanged) | keep | — | — |
| admission without a decision record | attested Admit, authority = unknown | **never fabricate** | — | — |
| existing election memberships | Election context (untouched) | none | — | — |
| finance records | Finance (untouched) | none | — | — |
| low-precision dates | a BD-NORM decision, or kept outside the predicates | normalise with a recorded decision | precision loss | the normalisation decision |
| records without authority | attested, `authority = unknown` | **never invent** | — | — |

**Rule:** migration normalises representation; it never invents domain truth.

**Gate:** unchanged. CONDITIONAL GO (administrative: the ratification act, the sequencing act, EP-01, a story ID). BD-I5 / BD-SYNC / BD-REV before slice 4.

---

## Amendment (principal review round)

**Domain:**
- `Resolution` implements **proportional CFC+**: LiveCut = Cut ∩ BaseClaim(target of the cause), FP §14.2.
- `ConsequenceGenerator` (g2) cuts only over Δ = NewBase − PrevBase − the target's own live cuts; a Correct inherits its superseded fact's consequences, clipped.
- `InvariantPreservingAcceptance` checks I = INV-EP ∧ INV-9 ∧ INV-COV ∧ INV-1 (∧ ER per BD-REV-ER).

**Concurrency (required):**
- the append carries the expected Membership version / `seq`, with `UNIQUE(membership_id, seq)`;
- on conflict: reload, recompute f⁺, re-run G\*. Never blind-retry the write;
- a fact and its consequences go in one transaction;
- an idempotency key per command;
- an outbox for integration events.

**Set-based rules outside the aggregate:**
- `UNIQUE(organisation_id, person_id)` for INV-M at creation;
- `UNIQUE(organisation_id, membership_number)` if BD-NU = per organisation.

These are database constraints that enforce domain rules; they are not the rules.

**Tests (RED seeds):**
- the scripted histories A–R of FP §14.6, including the three minimal counterexamples CX-14a/b/c as regression tests;
- a property test with the same generator: INV-COV / INV-EP / INV-9 after every step;
- a concurrency test: two conflicting commands, of which exactly one commits.

**Gate:** unchanged. CONDITIONAL GO (administrative): the Principal Reviewer's act, the sequencing act, EP-01, a story ID. BD-I5 / BD-SYNC / BD-REV / BD-REV-ER before slice 4.

---

## Amendment (closure re-run)

**Domain:**
- `Resolution` computes `active(f) = live(f) ∧ (f is not a Correct ∨ active(target(f)))` and uses it for consequence liveness;
- `CorrectFact` refuses an inactive target.

**Tests:**
- CX-15 (4 facts) as a regression test;
- a property test: for every live fact c, Res(F · Annul(c)) = Res(F ∖ D(c)).

Gate unchanged.

---

## Amendment (final freeze audit)

**Concurrency is an architectural requirement (FP §16.10), not an option.**
- Every Membership command runs: load (version v) → ResolveCandidate → G\* → Commit(expectedVersion = v).
- On conflict: reload, **recompute f⁺ (g2)** and re-run G\*.
- `recordedAt` and `seq` are assigned at commit, and `recordedAt` is non-decreasing in `seq`.
- Resolution never reads the DB transaction time.

**Command guards (domain rules, FP §16.4):**
- `AnnulFact`: the target has decision provenance;
- `VoidFact`: any live target;
- `CorrectFact`: the target is value-bearing; never Annul / Void. Annulled / voided targets per BD-REV.

**Tests (RED seeds):**
- the lost-update pair (P1 [0,0); A = Admit P2 @0; B = End P1 → 1): exactly one commits as first computed, and the other is recomputed or refused;
- Annul(Annul) restores the original (or is refused by G\*);
- Annul(Void), Correct(Annul) and Correct(Void) are refused;
- a property test: whenever a replay without c is possible, it equals the recorded counterfactual.

**Implementation prerequisites (final):**
- Principal Reviewer ratification;
- BD-I5, BD-REV, BD-SYNC confirmed (architecture-shaping, before slice 4);
- the PO sequencing act (EM-SEQ-001);
- EP-01 plan approval;
- a story ID;
- the per-command prerequisites in §1.

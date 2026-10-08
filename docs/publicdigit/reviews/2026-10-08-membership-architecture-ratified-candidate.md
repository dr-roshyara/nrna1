# Membership Architecture: Conditional Ratification Candidate

**Status:** **CONDITIONALLY RATIFIABLE**. This is the canonical theory only; the research history is in the E0a/E0b documents. **Not ratified, not final, not implemented.**
**Date:** 2026-10-08 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)
**Companions:**
- `2026-10-08-membership-decision-register-final.md` (**DRF**)
- `2026-10-08-membership-proof-and-verification.md` (**PV**)
- `2026-10-08-membership-implementation-blueprint.md` (**IB**)

**Supersedes as the canonical statement:** `…-membership-architecture-final.md`. Its decision register and readiness gate are superseded by DRF and IB.

> **Changes from the previous canonical statement** (each found in this pass; details in PV):
> 1. **NE is withdrawn as a domain rule.** It was a proof convenience. It is replaced by the guard rule **ER**: a period whose resolved lifetime is empty may not trim others, and trim reversals are refused on overlap. Same-instant episodes become a **business** choice.
> 2. **A hidden contradiction is removed:** supersede-by-trim itself produces empty lifetimes (full supersession), which the old NE rule did not cover.
> 3. **Trims are recorded consequence facts**, not on-the-fly derivations. Reversal of a reversal is therefore explicit and deterministic.
> 4. **Annul and Void are separate domain fact kinds again.** Last pass's merge into `Invalidate` was premature: authority, reporting and downstream reactions differ. They may share a persistence shape (an implementation choice).
> 5. **"Effective" is renamed `TermCovered`; "Recognised" is removed** (redundant under REC-0 = no). This avoids mixing the legal lifetime with the granted term.
> 6. **Imprecise historical dates** make predicates **three-valued** (true / false / unknown).

---

## 1. Ontology (canonical)

| Concept | Is | Is not | Owner | Identity |
|---|---|---|---|---|
| Person | a human (Identity context) | a member | Identity | PersonId (internal) |
| Organisation | the recognising body | an organ | Organisation | OrganisationId |
| OrganisationAffiliation | access / role (EM-VOC-002) | membership | Organisation | own |
| **Organisation Membership** (EM-VOC-001 `Member`) | the enduring membership record of one person with one organisation | a period; an entitlement | Membership | **MembershipId** |
| **Membership Period** | one admission-to-ending legal episode | a status; a term | Membership | **PeriodId** |
| **Lifetime** of a period | the legal episode interval `[start, end)` | the paid term | Membership | — (derived) |
| **Term segment** | a granted (paid or fixed-duration) membership interval | the lifetime | Membership | — (derived) |
| **Restriction** (`Suspended`) | a decided, membership-scoped restriction of all membership rights | a Governance ban; a standing restriction | Membership | — (derived) |
| MembershipNumber | an organisation-issued **credential / business reference** | an identifier | Membership (value) | — |
| MembershipType | configuration (fee, duration, classifications) | an entitlement | Membership (config) | TypeId |
| MembershipApplication | a request process | membership | Membership | own aggregate |
| Membership fact | an append-only record (an act or an attestation) with clocks and provenance | an ES event | Membership | fact id + seq |
| Standing | dues evaluation | lifecycle | Membership (E0b-S) | — |
| Eligibility | process qualification | membership | Election / the consumer | — |

## 2. Formal model

```text
Fact f = (seq, kind, target?, effectiveAt, decidedAt?, recordedAt, precision, provenance, payload, cause?)
 kinds:  Admit · Renew · ChangeType[BD-TYPE] · Suspend · Restore · End(kind) · Annul(target) · Void(target) · Correct(target, values)
         · Trim(period, removedInterval, cause = triggering fact)            ← recorded consequence (system fact, provenance=rule:g2)
         · [Recognise, if BD-REC0 = yes] · [TermExtension, if BD-SUSP requires a term pause]
 provenance ∈ { decision(actor, authoritySnapshot, personDeclaration?), rule(ruleRef, version), attested(sourceKind, sourceRef, importer) }
 precision ∈ { exact, day, month, year, unknown }   (for attested historical dates)

Membership m(p,o): MembershipId · MembershipNumber · periods
Period q: PeriodId · lifetime L(q)=[s,e) · termSegs · typeSegs · restrictions      — all DERIVED by fold over facts in seq order
Res(F)  = fold over F in seq order: Annul/Void remove target's effect; Correct replaces values; Trim applies as recorded;
          Annul/Void of a Trim (trim reversal) restores it (guarded)
F_τ     = { f : recordedAt(f) ≤ τ }   (and seq-prefix for intra-τ states)
```

## 3. Predicates (complete set; all derived; Kleene three-valued under imprecise dates)

| Predicate | Definition |
|---|---|
| `InLifetime(q,t)` | `s ≤ t < e` (q not voided or annulled) |
| `Ended(q,t)` | `e ≤ t` (and q not voided or annulled) |
| `TermCovered(q,t)` | ∃ a term segment `[a,b)` ∋ t |
| `InForce(q,t)` | `InLifetime ∧ TermCovered` |
| `Lapsed(q,t)` | `InLifetime ∧ ¬TermCovered` |
| `Suspended(q,t)` | `InLifetime ∧` ∃ a restriction interval ∋ t |
| `IsMember(p,o,t)` | ∃ q ∈ m(p,o): `InForce(q,t)` (**suspension does not change it**; a counting rule that excludes suspended members is organisation policy, applied by the consumer) |
| `CurrentPeriod(p,o,t)` | the unique q with `InLifetime(q,t)` (INV-9) |
| `CurrentType(q,t)` | the type segment ∋ t (or the period type) |
| `TrueAt(φ,t)` · `KnownAt(φ,t,τ)` · `DecidedBy(φ,t,d)` | φ over `Res(F_now)` · `Res(F_τ)` · `Res({f : decidedAt ≤ d ∨ provenance ≠ decision})` |
| Q-TEN set | `FirstAdmissionDate` · `CurrentAdmissionDate` · `CurrentPeriodDuration` · `ContinuousMembershipDuration` · `CumulativeMembershipDuration`. **No `member_since` / `tenure` field** |

**Verified relations** (32 / 32 assignments; PV §3):
- `Ended ⇒ ¬InForce`;
- `Lapsed ⇒ ¬TermCovered`;
- `Lapsed ⇒ ¬Ended` (so `Lapsed ⇏ Ended`);
- `¬(Lapsed ∧ InForce)`;
- `InLifetime ⇒ (InForce ⊕ Lapsed)`;
- `Suspended ⇒ InLifetime`;
- `IsMember ⇒` exactly one period in force (via INV-9).

## 4. Invariants

| ID | Invariant | Status (PV) |
|---|---|---|
| INV-1 | `holder(q)` and `organisation(q)` constant (a wrong holder → Void + a new Admit) | PROVEN UNDER ASSUMPTIONS |
| INV-2 | `Ended ⇒ ¬InForce` | PROVEN |
| INV-3 | lapse never implies an ending | PROVEN |
| INV-6 | facts append-only; `KnownAt` deterministic and stable | PROVEN UNDER ASSUMPTIONS (A3, A5) |
| INV-7 | the membership history survives any affiliation change | ARCHITECTURAL DERIVATION |
| **INV-9** | the resolved lifetimes of one Membership are pairwise disjoint *(scope per BD-I5: (p,o) or (p,o,class))* | PROVEN UNDER ASSUMPTIONS A1, A3, A4, A5, **ER** + FINITE VERIFIED |
| I5 | ≤ 1 period in lifetime at any t | PROVEN from INV-9 |
| INV-12 | a late renewal preserves the lapse gap | PROVEN |
| INV-14 | no MembershipId, PeriodId or fact is ever deleted, re-pointed or rewritten | ARCHITECTURAL DERIVATION |
| INV-11 | Membership grants no voting entitlement | ADOPTED |

## 5. Guards

| Guard | Fires on | Rule |
|---|---|---|
| **g1** | Admit(v) | refused if any live period's resolved lifetime has `end > v` (within the BD-I5 scope) |
| **g2** | a lifetime-enlarging fact on a **non-empty** target (Annul(End), Correct of end later or start earlier) | if it would overlap: **refuse** or **trim** the overlapping periods, recording Trim facts (policy = BD-REV) |
| **ER** | a lifetime-enlarging fact on an **empty** target; any trim reversal | **refused** on any overlap (never trims) |
| consent | Admit with `decision` provenance | a declaration is required unless the basis is `rule` / `attested` (BD-CONSENT) |
| authority | every decision fact | a Governance AuthorityCheck; snapshot recorded |

## 6. Aggregate

- **D1:** one Organisation Membership aggregate per (Person, Organisation), containing its periods and facts.
- **Nature:** D1 is a **transactional enforcement boundary** for the business invariant INV-9 (synchronous). It coincides with the adopted domain concept (EM-VOC-001).
- **It is a conditional architecture proposal:**
  - valid under every pending answer;
  - minimal only if the PO adopts BD-I5 + BD-SYNC.
- **Outside the aggregate:**
  - Application, Fee, sections;
  - number uniqueness (organisation-level constraint);
  - authority rules (Governance);
  - identity equivalence (Identity).

## 7. Identity model

| Identifier | Identifies | Scope | Stable across re-entry? | User-visible? |
|---|---|---|---|---|
| PersonId | the person | platform (internal) | yes | no |
| OrganisationId | the organisation | platform | yes | no / slug |
| MembershipId | the enduring membership record | organisation | **yes** | no |
| PeriodId | one admission episode | membership | no (new per admission) | no |
| MembershipNumber | **credential / business reference** (not an identifier) | organisation (uniqueness per BD-NU) | per BD-2 (recommended: yes) | **yes** |

**B′** (the period = the episode, the Membership = the enduring record) **is compatible with EM-VOC-001's wording.** An A-answer to BD-1 would require a PO terminology act on EM-VOC-001.

## 8. Temporal model

- **Clocks:** `effectiveAt` (domain: valid time), `decidedAt` (domain; **mandatory** for `decision` provenance when known, **absent** for `attested` / `rule` without a decision), `recordedAt` (system-of-record time: when the fact entered PublicDigit's authoritative record), and `seq` (the immutable total recording order within a Membership).
- `seq` is **persisted with the fact**. It is not the database's auto-increment by convention.
- **Database, audit and queue timestamps are not business time.**
- **KnownAt is deterministic:** `F_τ` is a seq-ordered finite prefix; `Res` is a pure fold; guard outcomes are recorded as facts (Trim). So no current policy is consulted.
- **Lifetime ≠ term:**
  - **lifetime** = the legal episode;
  - **term** = granted or paid time (a fixed-duration type; ∞ for open-ended types);
  - **standing** = dues (E0b-S);
  - **eligibility** = the consumer's rule;
  - **service validity** = organisation policy.

## 9. Reversal model

| Operation | Domain meaning | Event existed? | Effect |
|---|---|---|---|
| **End(kind)** | the episode ends | yes | sets the lifetime end |
| **Annul(target)** | a real decision was **set aside** by a later legal decision | **yes** | removes the target's effect; an authorised organ is required |
| **Void(target)** | the record **never reflected reality** | **no** | removes the target's effect; a registrar / correction authority is required; excluded from statistics |
| **Correct(target, values)** | a real decision with **wrong recorded attributes** | yes | replaces the values |
| **Trim (consequence)** | **legal supersession:** the reinstated earlier episode takes precedence over the overlapping part of a later, validly admitted episode | the later admission was real | shortens the later lifetime, recorded with its cause |
| Restore | ends a suspension | yes | ends the restriction |
| Re-admit | an Admit on a Membership with ended periods | yes | a new period |

- **Trimmed** = a period with ≥ 1 live Trim.
- **Fully superseded** = its resolved lifetime is empty.
- **Both are derived classifications, not states.** No command targets them; there is no second lifecycle.

## 10. Boundaries

- **Election (ADOPTED, unchanged):**
  - Election-Only → ImportedVoterRegistry;
  - Full Membership → MembershipRegistry;
  - the ADR_20261004_1549 chain (exercisability is inside VotingEntitlement);
  - ADR-T11.
  - **Membership publishes** IsMember, Suspended, CurrentType, the Q-TEN predicates, standing (E0b-S), End / Suspend / Trim events, and (MembershipId, asOf).
  - Period-bound election references are **discouraged**: after a trim, a date may belong to a different period. A consumer needing one uses (MembershipId, asOf) and gets the PeriodId informationally.
- **Finance:**
  - Fee(P2) stays valid and referenced.
  - Membership publishes `PeriodTrimmed(periodId, removedInterval, cause)`.
  - The refund / credit policy is Finance's (DS-1).
- **Audit:** a projection of the facts (what, when effective, decided, recorded, why, who, authority, which fact was corrected / annulled / voided / trimmed). Never authoritative.
- **Governance:** competence rules and organs. Membership stores authority snapshots only.
- **Identity:** person equivalence. On a merge, an explicit Membership root-merge + reconciliation (DS-3).

## 11. Verdict

**CONDITIONALLY RATIFIABLE.**
- Two pending business answers would change architecture if they were left unparameterised: **BD-1** (identity naming / EM-VOC-001) and **BD-I5** (invariant scope).
- Both are **parameterised here** (§7, §4), so neither forces a redesign.
- The others are parameterised variation points or business-only (DRF §3).
- **Architecture confidence: high.** Business closure is pending.

---

**Superseded as the reference (2026-10-08, additive)** by `2026-10-08-membership-architecture-ratified.md`. Changes:
- a counterexample: an independent Trim loses membership when its cause is annulled (1,903 / 200,000; scenario F). Fixed by "consequence follows cause" (CFC); "Annul Trim" is removed;
- three-valued predicates are withdrawn (import-time normalisation instead);
- D1 is justified by the g1/g2/CFC atomicity, conditional on BD-I5 + BD-SYNC.

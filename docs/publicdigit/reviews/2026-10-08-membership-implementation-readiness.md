# Membership Implementation Readiness Gate

**Type:** Implementation readiness gate · **Date:** 2026-10-08 · **authorises nothing**
**Companions:** `2026-10-08-membership-architecture-final.md` (**FA**) · `2026-10-08-membership-decision-register.md` (**DR**)

## 1. Gate result

**CONDITIONAL GO.**
- **No Membership-domain code may start until conditions C1–C6 hold.** Until then the effective state is **NO-GO** for domain implementation.
- **Model-neutral conformance fixes** (§6) may be authorised separately.

## 2. Prerequisites (conditions)

| # | Condition | Owner | Source |
|---|---|---|---|
| C1 | the **architecture ratified** (DR §2, AR-1…AR-18), or amended | Principal Reviewer | FA §22 |
| C2 | the **MUST business decisions confirmed:** BD-1, BD-2, BD-3, BD-SUSP, BD-REC0, BD-I5, BD-SYNC, BD-NE | PO | DR §3 |
| C3 | the **command-level decisions confirmed** before the related commands are built: BD-REV, BD-TERM4, BD-CONSENT (+ LQ-1), BD-TEN, BD-NU, BD-TYPE, BD-GVR | PO (+ legal) | DR §3–4 |
| C4 | **sequencing act:** Full Membership is a later phase (EM-SEQ-001). The PO must authorise starting it, or limit the slice to model-neutral foundations | PO | EM-SEQ-001 |
| C5 | an **EP-01 plan approved** for the first slice (plan first; RED → GREEN; developer guide; story ID) | PO | `.claude/CLAUDE.md` EP-01 |
| C6 | a **story ID:** a new PBDIGIT only if no existing story (EPIC-02 `PBDIGIT-05/06/08`, `-49`, `-68`, `-221`) covers the slice (frozen baseline) | PO | delivery baseline freeze |

## 3. Unresolved decisions that block code

These are all of DR §3, split as follows:
- **C2 blocks the aggregate model.**
- **C3 blocks the individual commands:**
  - Invalidate / Correct with g2 (BD-REV, BD-TERM4);
  - Admit validity (BD-CONSENT);
  - the number allocator (BD-NU);
  - ChangeType (BD-TYPE).

Downstream items (DS-1…5) block only their consumers.

## 4. Migration dependencies

| Legacy | Disposition | Data preservation requirement | Risk |
|---|---|---|---|
| two Member models (Eloquent + DDD) | **MIGRATE** to one aggregate | — | divergent rules |
| status fields (3 vocabularies) | **REINTERPRET** as facts (`attested{legacy}`) | each status maps to facts; `archived` → a record flag | mis-mapping |
| `expired` | **DEPRECATE** (derived lapse) | keep as an attested historical observation | — |
| `joined_at` / `membership_expires_at` | **MIGRATE** → Admit + first segment | keep the original values in an attested fact | — |
| `membership_renewals` (ledger) | **MIGRATE** → Renew facts / segments | — | gaps before the ledger existed: `unknown` |
| `suspended` | **MIGRATE** → a Suspend fact (attested) | — | — |
| `organisation_user_id` (UNIQUE) | **REINTERPRET** → PersonRef + the root key | — | — |
| affiliation `ON DELETE CASCADE` | **DELETE** (the cascade) | history must survive affiliation deletion | data loss if migrated naively |
| membership number (global unique; regression: not generated on approval) | **MIGRATE** to the root; uniqueness per organisation (BD-NU) | keep existing numbers | none (global ⊂ per-org) |
| `fees_status` + `fee_state` | **MIGRATE** → the Fee aggregate (E0b-S) | — | two truths until then |
| `grants_voting_rights` | **UNKNOWN** until BD-GVR (reinterpret or deprecate) | keep the column | — |
| `Member::endMembership` (dormant) | **DELETE** (replaced by an End fact; the election effect is deferred) | — | do not wire it up |
| imports (six inconsistent re-entry behaviours) | **MIGRATE** to Admit (g1) with `attested` provenance | keep source references | — |
| historical data without authority | **REINTERPRET:** `attested{legacy}`; **never fabricate** an authority | — | — |
| historical data without exact dates | **KEEP** as attested with a precision marker; `unknown` where absent | — | queries must treat unknown dates explicitly |

## 5. Sequencing (after C1–C6)

1. **Foundations (model-neutral):** the fact store with three clocks and seq; provenance; no-cascade history.
2. **Aggregate core:** Membership root + periods; Admit with g1; End with NE; derived predicates; KnownAt.
3. **Term:** Renew → segments; lapse derived; replace `ExpireMemberships`.
4. **Reversals:** Invalidate / Correct with g2 + the recorded consequence; PeriodTrimmed / Superseded events.
5. **Restrictions:** Suspend / Restore (needs the Governance authority port for organs).
6. **Number:** root number, the organisation-level uniqueness constraint and the allocator.
7. **Migration:** the legacy → facts mapping (dry-run reports first).
8. **Published facts:** an Election query port. Consumed only in the Full Membership phase (EM-SEQ-001).

## 6. Separately authorisable (model-neutral; not authorised here)

From the E0a decision pack §15:
- **F-4:** an invitation must not return an ended membership (fail closed);
- **F-6:** renewal must restore the in-force state;
- **F-8:** remove the cascade delete;
- **F-11:** write the reviewer fields on approval;
- **F-12:** write the start date on approval;
- **F-1 / F-5:** first-admission number generation, and importing members' own numbers.

Plus the existing defect stories `PBDIGIT-257`, `-202` and `-209`.

## 7. Implementation risks

| Risk | Mitigation |
|---|---|
| record-order ties produce non-deterministic resolution | `seq` per Membership (AR-7) |
| a policy change re-derives history | the recorded g2 consequence (AR-6) |
| treating legacy status as truth | attested provenance; no fabricated authority |
| person duplicates discovered after migration | Identity merge + reconciliation (DS-3); possible future ML-assisted detection |
| performance of derivation | materialised state (IC); histories per (p,o) are small |
| consumers reading tables | the published port only (AR-16) |
| time-zone ambiguity in date predicates | fix the tenant time-zone source before step 3 |
| Finance has no policy for trimmed periods | DS-1 before step 4 ships |

## 8. EP-01 prerequisites for the first slice

- the plan names its slice (for example step 1 or step 2), its story ID (C6), its RED tests (FA §18 acceptance tests), its gates and the developer guide;
- PO approval of the plan **itself**;
- confirmation that C1–C4 are met, or that the slice is model-neutral.

## 9. Explicit gate

| | |
|---|---|
| **Domain implementation** | **CONDITIONAL GO** (NO-GO until C1–C6) |
| **Model-neutral fixes (§6)** | GO **if** the PO authorises each |
| **Migration** | NO-GO until step 7 and the dry-run design |
| **Election consumption** | NO-GO (the Full Membership phase; EM-SEQ-001) |

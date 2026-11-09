# Reference Geography tactical design v3.1: companion assessment

| | |
|---|---|
| **Status** | PROPOSED. Companion to `2026-11-09-reference-geography-tactical-design-baseline-v3.1.md`. Not committed |

## 1. What changed from v3

| Area | v3 | v3.1 |
|---|---|---|
| Ordering | One sequence; `recordedAt` maps "monotonically" (asserted) | **Sequence row locked until commit** (not a sequence object); rollback reuses the position; **`recordedAt` clamped non-decreasing**, so the time-to-position mapping is monotonic by construction; **position is the authoritative token** |
| Aggregates | One platform `PublicationLog` aggregate | **A sequencer (technical mechanism) plus partitions as read-only consistency scopes.** Publication writes only the sequence row and one entry. **Staleness derived from positions**, so no version counters |
| Identity | "Legal entity" | **Geographical entity as established or characterised by the competent instrument**; no legal personality assumed |
| Governance | Second approver distinct from the preparer **and** the first approver | **Distinct from the first approver** (the narrowest reading of D-84); the preparer question is an [OQ]. Post-publication conflict handled in **three separate steps** |
| Disputes | Raised only | **Cleared** too, with a reason; history reconstructable |
| CLDR | Root ≈ English | **Root not assumed English**; resolved locale and release returned; **inheritance data stored per release**; a platform default as the presentation fallback |
| Registry | One registry kind | **Issuer, mandate, non-territorial competence, precedence** as separate kinds |

## 2. Review findings

| Issue | Status |
|---|---|
| 1 Sequence proof | Resolved, subject to [TU] on the isolation level |
| 2 Aggregate breadth | Resolved: ordering separated from consistency |
| 3 "Legal entity" | Resolved |
| 4 Governance strictness | Resolved to the narrowest reading; **the preparer question is raised, not decided** |
| 5 Naming | Kept open: two [OQ]s |
| 6 Claim and dispute semantics | Resolved (clearance; authentic but disputed source; registry kinds) |

## 3. Scenarios

**Key:** G = guaranteed by mechanism · H = depends on human judgement.

### 3.1 The 30 scenarios from v3 (re-tested)

**All hold under v3.1, with the mechanisms of v3.1 §5–§8.** Changes in mechanism only:
- **#5, #11, N10, N11:** now by the sequencer and the derived staleness rule (no version counters).
- **#10:** dispute plus change: the read set includes the target slot; a later position makes the other revision stale.
- **N2:** inheritance from the stored release data.

**Human-dependent, as before:** #1, N6, N7 (real-world identity); #8, N13 (conflict findings); #13 (assessment).

### 3.2 The 12 new scenarios

| # | Initial facts | Action | Invariant at risk | Expected | Mechanism | G / H |
|---|---|---|---|---|---|---|
| 1 | Last committed position 41 | A publication takes 42, then rolls back | No gaps | The row returns to 41; the next publication takes 42; nothing at 42 was ever visible | Sequence row in the transaction | G |
| 2 | Last `recordedAt` 10:00:05 | The clock jumps back to 09:59 | Monotonic time mapping | The new entry records 10:00:05 (clamped) | Clamp under the lock | G (metadata lags the real time) |
| 3 | Nepal and Germany publications ready | Both publish | Order, no coupling | One waits briefly; each validates only its partition; positions p and p+1 | Lock; partitions | G |
| 4 | Publication at 42 not yet committed | A query asks for p = 42 | No partial view | `closedThrough` = 41 → **refused**; a query at p ≤ 41 is answered | Snapshot plus `closedThrough` | G |
| 5 | A country change and a registry change in flight | Both publish | Consistency | Serialised; the country revision's read set includes any cited mandate. If the registry change touches it first, the country revision is stale | Read set plus staleness | G |
| 6 | Revision published by A; A's conflict found later | Reassess | D-84a | (1) conflict decided by B; (2) supportability reassessed; (3) correction only if not supportable | Governance steps | **H** |
| 7 | Single approval; a second steward appointed | Publish | D-84 | Refused until a second approval | Threshold check at publication | G |
| 8 | Claim X disputed at position 50 | Clearance (a precedence rule recorded) at 60 | History | Belief at p 55: disputed; at p 65: cleared; X's value never changes | Dispute-state slot | G |
| 9 | Locale requested "xx-YY"; the chain ends at root without a value | Display | D-87 | Platform default locale, marked as fallback | Display-name resolution | G (presentation default) |
| 10 | B reconciled to A at 70; consumers hold B | Reconciliation found wrong; separation at 80 | References | From 80, B independent; consumers receive the reverse mapping; their earlier decisions keep their recorded p | Reconcile revisions; events | H (judgement) / G (mechanics) |
| 11 | A ward without legal personality | Record it | Identity basis | Allowed: identity is the geographical entity per the instrument | Identity rule | G |
| 12 | Authentic instrument; applicability disputed | Approve | Basis | **Approval refused** until applicability is assessed positive; if already published → objection → dispute marker | Revision approval | H (assessment) / G (refusal) |

## 4. New issues found

- **[OQ] May the preparer be the "second, independent" approver?** D-84 is silent.
- **[TU] Multi-context time τ:** coherent only if every involved context guarantees completeness through τ (each `closedThrough` ≥ τ); Membership's barrier must be checked against Geography's.
- **The CLDR platform default is configuration.** If the Principal wants it to be a policy, it becomes an [OQ].

## 5. Enforceable versus judgement

| Guaranteed by mechanism | Depends on human governance |
|---|---|
| Gap-free ordered positions; complete-or-refused queries; all-or-nothing visibility | Whether two identities denote one entity |
| I-1 to I-4 | Authenticity, competence and applicability of instruments |
| Slot supersession; disputes never change values | Whether a conflict existed; whether a decision is supportable |
| Validation over the derived read set; staleness | Precedence where no rule exists (disputed, never chosen) |
| Governance checks at approval and publication | — |

## 6. Technical uncertainties to verify during implementation

- Isolation level and the `FOR UPDATE` re-read behaviour.
- Lock duration for large revisions: pre-validate without the lock, then re-validate only the delta since *p₀* under it.
- Cross-context τ completeness.
- Performance of the namespace and interval queries.

## 7. Principal decisions

**Three, none blocking the model:**
1. names of the five level-1 regions;
2. names of territorial units;
3. **whether the preparer may be the second approver** (interpretation of D-84).

**Later:** L-4c, Japanese blocks, Q-2.

## 8. Readiness

**Ready for the Principal DDD Architect's independent assessment.**

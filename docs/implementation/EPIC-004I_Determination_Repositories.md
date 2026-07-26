# EPIC-004I Determination Repositories

**Kind:** Tactical DDD artifact №8 (one artifact → ARB review → refine → freeze → next). **Role:** implementation architect from the frozen baseline.
**Entry condition (derived from the chain, analogous to the prior artifacts):** *a repository exists to persist and reconstitute an accepted aggregate so its protected truths survive across time and process boundaries. Every repository — and every operation on it — must serve the persistence, reconstitution, or boundary-enforcement of accepted truths/invariants. If removing it weakens none of those, it does not belong.* House rule honored: **repositories for aggregates only** (Rule 9); reads go through Eloquent directly (CQRS-light) — a read model never wears a repository's name.
**The honest shape:** one ratified aggregate → at most one repository. The audit confirms the existing `DeterminationRepository` operation-by-operation against the frozen truths, and defends the repository-silences per ASP.

---

## 1. Confirmed: `DeterminationRepository` (domain interface) — operation audit

| Operation (existing) | Serves | Removal test |
|---|---|---|
| `save(Determination)` | Persistence of the guarded state — INV-1..INV-3's lifecycle position and the refs INV-5/INV-6 fixed | The aggregate's protected state would not survive the request; every truth becomes process-local |
| `findByChallengeRef(...)` | **INV-B1's boundary enforcement** — the issuance service's uniqueness check is built on exactly this query (the frozen R-3 seat) | The set-level rule loses its lookup; only the DB index (the concurrency backstop) would remain — detection would move from refusal-before-write to constraint-violation-after |
| reconstitution (via mapper, `Determination::reconstitute`) | The aggregate returns from storage **without emitting** — reconstitution is not an occurrence (consistent with №6's silences) and restores exactly the state the invariants guard | Replays and re-reads would re-run creation semantics — corrupting INV-3's one-act-of-ruling |

- **Traces (ADP):** aggregate (№2) → truths (№3) → invariants (№4) → this persistence seam. The interface lives in Domain, the Eloquent implementation + mapper in Infrastructure — the implemented shape already honors the dependency rule; cited as evidence, not designed.
- **Boundary note (recorded):** the repository is one of INV-B1's two cooperating mechanisms (query-then-refuse at the service + unique index at storage) — the honest two-seat pattern already established at №4.

## 2. Repository silences, defended (ASP)

- **No `delete`/`remove` — CONFIRMED as constitutional silence.** A determination is never deleted: forward-only (ADR-T8), absolute finality (INV-1/2), Policy 1 (supersede, never remove). The absence of a delete operation is a feature with the same grounds as the Revoke command's rejection at №7 — the two silences are one constitutional stance seen from two artifacts.
- **No `update`-style partial mutation — CONFIRMED as designed absence.** ADR-T19 Model B leaves almost nothing mutable to update: ruling content lives in the immutable event; the row carries lifecycle + refs. `save` persists aggregate state transitions; there is no business meaning to partial field updates.
- **No query zoo (`findByAuthority`, `findByElection`, `listByOutcome`, …) — REJECTED.** Every such query is a *read concern*: per the house CQRS-light rule, reads use Eloquent/read models directly and never enter the aggregate repository. The repository's query surface stays exactly as large as invariant enforcement requires — today, one lookup.
- **No repository for the Process Manager's state — DEFERRED to the Emergent Design Cluster.** If the PM proves to need durable state (parked work, timers), its persistence is that design's question — sixth entry in the cluster's gravitational field, recorded without designing it.
- **No event repository — REJECTED.** The outbox is transport with its own discipline (ADR-T1/MP series), not a domain repository; the ruling-bearing event's persistence is the messaging platform's concern.

## 3. Open items carried

Unchanged: Q-2 (triply loaded) · Jurisdiction semantics · the Emergent Design Cluster (with the PM-state persistence question noted as a potential sixth member at PM-design time).

## Self-review

One repository confirmed for one ratified aggregate — Rule 9 held ✅ · every existing operation carries its serving truth/invariant and a removal test; no operation exists for convenience ✅ · reconstitution's non-emitting nature is recorded as consistent with the frozen event silences ✅ · five silences defended with grounds (ASP), including the constitutional no-delete and the read/write separation ✅ · the entry condition discriminated (query-zoo rejection; event-repository rejection; PM-state deferral) — Methodological Fitness Rule satisfied ✅ · ADP lineage intact; nothing bypassed the chain ✅ · no code designed or modified ✅.

**Stop condition: STOP.** Repositories only. Domain Services (artifact №9 — the final artifact of the chain) begins on explicit ARB opening, after this artifact's review → refine → freeze.

---
*Frozen inputs: `EPIC-004H` (commands; DMT adopted at its freeze) · `EPIC-004E` (invariants; the two-seat INV-B1 pattern) · `EPIC-004D` (truths; the frozen R-3 boundary finding) · ADR-T1/T8/T19 · Rule 9 (repositories for aggregates only) · Implementation evidence: EPIC-003 inventory (DeterminationRepository / EloquentDeterminationRepository / DeterminationMapper).*

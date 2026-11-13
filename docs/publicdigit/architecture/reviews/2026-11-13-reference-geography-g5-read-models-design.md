# Reference Geography G-5: published read models: strategic discovery and tactical design

**Status:** Session A design. It needs no Principal business decision (§1.4). **G-5a implemented 2026-11-13** (evidence `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g5a/`); one refinement found during implementation: the read side depends on a new port `TimeBoundary` (`closeThrough` only), which `PublicationLedger` extends, so it can never reach the publication lock **Date:** 2026-11-13.
**Consumes, unchanged:**
- v3.11 (D-93): §4 belief and disputes, §6 boundaries and the query rules, §10 events and read models, §12 reconciliation;
- tactical completion §3 (queries in a `GeographyReadModel`; "refuse beyond the boundary; never partial") and §8 (the G-5 row);
- D-73, D-147, D-224, D-225, D-227 to D-230;
- the accepted G-1 to G-4 work;
- the Hexagonal DDD baseline (accepted 2026-11-13).

## 1. Strategic discovery

### 1.1 In business terms

Reference Geography is PublicDigit's official record of places. Until now, it could only *receive* changes. **G-5 lets the rest of the platform *ask* it questions:**
- which places existed on a date;
- what a place is (its parent, codes, names, classification);
- what lies below it;
- whether an identity was merged into another;
- whether a fact is contested.

**Every answer says "as officially known at publication p".** A decision made today can be explained years later by asking the same question at the same p. **Nothing unpublished is ever visible.**

### 1.2 Already settled (not reopened)

| Question | Settled by | Answer |
|---|---|---|
| What is visible | v3.11 §4, completion §4 | **published claims only:** a claim belongs to the (revision, content version) named by a publication. Drafts, frozen-unpublished versions, the roster, approvals and steward identities are internal |
| "As known when" | v3.11 §4, §6; D-73 | a **position p** (inside Geography; consumers record it), or a **time τ**, mapped by `positionAt(τ)` once τ ≤ W (closed through τ) |
| Beyond the boundary | v3.11 §6; completion §3 | **refused, never partial:** p above the committed position; τ in the future; τ that can't be closed (lock busy) |
| Belief | v3.11 §4 | the value of the highest-position claim ≤ p whose interval contains t; a withdrawal gives none |
| Dispute | v3.11 §4 | the latest dispute claim ≤ p on **the claim in force.** It never changes the value; it is shown as a flag |
| Merged identities | v3.11 §12 | `resolve(B, p)` → `{canonical A, knownAtPosition, recordedAt, revision}`, or B itself. One level only (R-2) |
| References | D-225 pattern, v3.11 §2 | places are referred to by their **opaque `ReferenceUnitId` string**, never by name |
| Organisation's use | **D-147: deferred** | **no consumer is wired in G-5;** nothing is copied into organisation databases |
| Display names (CLDR) | G-7 (D-87) | not in G-5. The profile returns *source* names |

### 1.3 Consumer questions (from v3.11 §10 and the context map)

| # | Question | G-5 query | Slice |
|---|---|---|---|
| Q1 | what is "as known at p", or at τ | `knowledgeAt(p)`, `knowledgeAtTime(τ)` (`PositionAt`, PO-5) | **G-5a** |
| Q2 | which places exist at t | `placesAt(t, k)` (`PlacesAt`) | **G-5a** |
| Q3 | what is this place at t: parent per scheme, classification, codes, names, contested facts, merged into | `place(id, t, k)` (profile and belief) | **G-5a** |
| Q4 | what lies directly below a place in a scheme | `childrenOf(id, scheme, t, k)` (hierarchy) | **G-5a** |
| Q5 | was this identity merged | `resolve(id, k)` | **G-5a** |
| Q6 | which place is meant by this name or code | `PlaceLookup` (stateless match with match quality) | G-5b |
| Q7 | what changed since my last position | the publication feed (`RevisionPublished` content) | G-5c |

### 1.4 Decisions for the Principal

**None.** Every rule above is ratified. The one open technical point (§3.4) is an engineering decision within the accepted architecture.

## 2. Tactical design

```
  consumer context (later, after D-147)              Reference Geography
  ─────────────────────────────────────              ─────────────────────────────────────────────────────
  its own port  ──adapter──▶ Application\Published\GeographyReadModel  (provider-owned published contract;
                                       ▲                 strings, ints, DTOs only: no Domain type in a signature)
                                       │ implements
                        Application\PublishedGeography ──uses──▶ Domain\PlaceBelief (pure: belief, disputes, merges)
                                       │ uses ports
                Application\Ports\GeographyReadSource   Application\Ports\PublicationLedger (closeThrough, G-4)
                                       ▲ implemented by
                Infrastructure\PostgresGeographyReadSource (refgeo_claims ⋈ refgeo_publications, position ≤ p)
```

| Element | Layer | Responsibility |
|---|---|---|
| `Published\GeographyReadModel` | Application, **published language** | the contract consumers may depend on |
| `Published\Knowledge` (position, recordedThrough) | published | the token a consumer records with its decision |
| `Published\Place`, `Published\Resolution` | published | read-only DTOs |
| `Published\KnowledgeNotAvailable` | published | the refusal: beyond the boundary, future τ, lock busy |
| `PublishedGeography` | Application | maps time to position, enforces the boundary on every call, assembles the DTOs |
| `Domain\PlaceBelief`, `Domain\PlaceFacts` | Domain (pure) | v3.11 §4 belief, the dispute flags on the claims in force, §12 merge; **the business rules of reading** |
| `SlotHistory::latest()`, `positionOf()` | Domain (additive) | latest claim ≤ p (for disputes and reconciliation, which hold for all time); the position of a claim |
| `Ports\GeographyReadSource` | Application port | committed position; `positionAtTime(τ)`; a publication's facts; existence slots; a subject's slot histories; dispute histories; the published atlas at p |
| `PostgresGeographyReadSource` | Infrastructure | SQL over published claims only |

**Why this preserves the architecture:**
- the domain decides belief;
- the application only orchestrates;
- the database is behind one port;
- consumers see strings and DTOs, so they never import Geography's Domain.

**`Published\*` is added to the published-language list** (Principal rule, 2026-11-13: "other cross-context dependencies require explicit published contracts").

**An architecture test proves:**
- no public signature in `Published\*` names a Domain or Infrastructure type;
- no other context imports Reference Geography except through `Published\*` (A-3).

### 2.1 Consistency (PO-5)

- **A position query** reads only claims of positions ≤ p. Those are committed, gap-free and immutable (G-4a guards), so **several statements at one p give one consistent answer.** No snapshot needs holding open.
- **A time query:**
  1. `closeThrough(τ)` (G-4) succeeds, so every publication with `recordedAt ≤ τ` is committed and no later one can join;
  2. then p = max position with `recordedAt ≤ τ` (0 if none).

  **The answer at τ is final** (v3.11 §6 (c)).
- **Refusals:**
  - p < 0, or p > the committed position;
  - τ in the future (`BoundaryNotClosed`);
  - lock busy (`BoundaryNotClosed`).

  **Each call re-checks** its knowledge's position, so a fabricated `Knowledge` cannot see beyond the boundary.
- **No data yet (p = 0):** empty answers, not refusals. Nothing is published, and that is a complete answer.

### 2.2 Tests

| Level | What |
|---|---|
| Domain (database-free) | `PlaceBeliefTest`: belief at t and p; a withdrawal; an ended existence; a dispute flag only on the claim in force (a superseded claim's dispute doesn't flag the new value); a cleared dispute; a merge and its reversal; one level of reconciliation; `SlotHistory::latest`/`positionOf` |
| Application (fakes) | `PublishedGeographyTest`: the boundary refusals; τ → position through the ledger's close; re-check of a fabricated position |
| Adapter (isolated PostgreSQL) | `PostgresGeographyReadSourceTest` / read-model end-to-end: unpublished drafts invisible; frozen-but-unpublished versions invisible; answers at p unchanged after a later publication; `positionAtTime` with equal timestamps; future τ refused |
| Architecture | the published contract names no Domain or Infrastructure type; the provider wiring binds `GeographyReadModel` |

## 3. Slices

| Slice | Content |
|---|---|
| **G-5a** | Q1 to Q5 (this submission) |
| G-5b | `PlaceLookup`: name, alias or code → places, with match quality; the legacy fuzzy matcher **copied, not imported** (completion §2); no address parsing (D-83) |
| G-5c | the publication feed, and `RevisionPublished` |

### 3.4 `RevisionPublished`: required, but not through today's outbox (engineering decision, G-5c)

**Required: yes.** The ratified contract (context map; v3.11 §10) promises consumers one event per position: position, revision, kinds, subjects, reconciliation mappings.

**But two facts change *how*:**
1. **The shared outbox is organisation-scoped:** `OutboxWriter::store($event, string $organisationId)`. Reference Geography is central and has no organisation. Using it would mean changing `Shared` for a central producer, or inventing an organisation id. Neither is honest.
2. **`refgeo_publications` already is that event stream:** append-only, gap-free, totally ordered, one entry per position, immutable (G-4a). A consumer that records its last position can never miss or double-read an entry.

**Decision (G-5c):** publish the event's content as a **pull feed** on the published contract: `publicationsSince(p, k)`, giving position, `recordedAt`, revision, kinds, subjects and merges. **A push through an outbox is added only when the first consumer exists** (Organisation, after D-147), with a central-outbox design at that time. **No messaging framework.**

**Partitions are not in the event yet:** the kernel has no partition model (v3.11 §7 partitions are validation scopes). Stated, not invented.

**Traceability:** v3.11 §4, §6, §10, §12 · completion §3, §8 · D-73, D-87, D-93, D-147, D-225 · baseline (accepted) §4 published language · PBDIGIT-346.

# Discovery: handleDatesUpdated Assessment

**Date:** 2026-06-13  
**Status:** Complete  
**ADR Reference:** [ADR-001: Reuse Before Create](../decisions/ADR-001-Reuse-Before-Create.md)  
**Context:** Evaluation for Increment 2 — extract or leave?

## 1. Full Implementation

**Location:** Management.vue lines 1433-1483  
**Lines:** ~50  
**Input:** `{ phase: string, dates: { start: string, end: string } }` — emitted by `StateMachinePanel.vue`
**Output:** HTTP PATCH to `elections.update-timeline` route

```
receive { phase, dates }
    ↓
look up phase in columnMap (3 entries)
    ↓
convert local datetime strings to ISO UTC
    ↓
build payload { columnStart: isoStart, columnEnd: isoEnd }
    ↓
PATCH /elections/{slug}/update-timeline
    ↓
on success: router.reload({ preserveScroll: true })
```

## 2. Layer Analysis

| Component | Layer | Assessment |
|-----------|-------|------------|
| `columnMap` (phase → database columns) | **Persistence** | Knowledge of database schema column names. Belongs in Infrastructure or a mapper. |
| `convertToISO` (local datetime → UTC ISO) | **Infrastructure** | Date serialization is a cross-cutting concern. Pure utility. |
| `payload` construction | **Application** | Wires domain concept (phase) to infrastructure (column names, route). |
| `router.patch` + error handling | **Infrastructure** | HTTP call + Inertia lifecycle. |

**Verdict:** The function is **mixed-layer** — it contains persistence knowledge (column names), infrastructure logic (date conversion, HTTP), and application orchestration (wiring them together). The domain value is LOW.

## 3. Duplication Check

Similar column-name mapping found in **two locations**:

| Location | Role | Mapping |
|----------|------|---------|
| `Management.vue:1440-1453` | **Writes** these columns on date update | `phase → { start: columnName, end: columnName }` |
| `StateMachinePanel.vue:470-487` | **Reads** these columns for display | Implicitly knows same column names but reads them via `props.election.column_name` |

The column names themselves are duplicated between the two files. Both must stay in sync if the schema changes — a maintenance risk.

## 4. Extraction Candidates

### Option A: Extract column mapping to shared constants

```
lib/election-timeline-columns.ts

  const ELECTION_TIMELINE_COLUMNS = {
    setup_administration: { start: 'administration_suggested_start', end: 'administration_suggested_end' },
    setup_nomination:     { start: 'nomination_suggested_start',     end: 'nomination_suggested_end' },
    voting_active:        { start: 'voting_starts_at',               end: 'voting_ends_at' },
  }
```

| Factor | Assessment |
|--------|-----------|
| Lines extracted | ~10 (constant object) |
| Duplication eliminated | StateMachinePanel.vue could also consume it |
| Risk | Low — pure data |
| Layer | ⚠️ Infrastructure — column names are schema details, not domain language |
| Domain value | NONE — column names are not business concepts |
| Justified now? | ❌ Only if a third consumer appears (wait for 2nd consumer rule) |

### Option B: Extract `convertToISO` as shared utility

```
lib/date-utils.ts

  function toISODateString(localDatetimeStr: string | null): string | null
```

| Factor | Assessment |
|--------|-----------|
| Lines extracted | ~6 |
| Reuse potential | Could be used by other pages with datetime-local inputs |
| Risk | VERY LOW — pure function, no side effects |
| Domain value | NONE — utility function, not domain logic |

### Option C: Keep as-is

| Factor | Assessment |
|--------|-----------|
| Lines changed | 0 |
| Risk | NONE |
| Cost | Column mapping knowledge remains split across 2 files |

## 5. Recommendation

**Option C — Keep as-is for now.**

| Reason | Detail |
|--------|--------|
| Column names are not domain logic | They are database schema details. Putting them in `Domain/` would violate layer purity. |
| No application-layer abstraction needed | The orchestration is 3 lines (lookup → convert → PATCH). Adding a UseCase would increase indirection. |
| Duplication is low risk | Column names change rarely. Both files would be updated together during schema migrations anyway. |
| `convertToISO` extraction is premature | One consumer does not justify a shared utility. Apply the "wait for 2nd consumer" rule. |

**If extraction were to happen**, Option B (`toISODateString` as shared utility) would be the safest first step — but only when a second consumer appears.

## 6. Architectural Conclusion

| Question | Answer |
|---|---|
| Is there domain logic here? | ❌ No. Column mapping is persistence knowledge. Date conversion is infrastructure. |
| Is there duplication? | ⚠️ Column names appear in 2 files, but changing one without the other would break immediately. |
| Should we extract? | ❌ Not justified. The function belongs in its current layer, doing its current job. |
| What about Increment 2? | **Deferred.** No strong extraction candidate remains in Management.vue for a dedicated increment. |

## 7. Updated Priority Order

| Priority | Candidate | Action | Status |
|----------|-----------|--------|--------|
| 1 | `handleDatesUpdated` | Discovery | ✅ Complete — no extraction justified |
| 2 | `useElectionActions` / Inertia gap | Investigation | ⏳ Architectural boundary issue |
| 3 | Hardcoded status text → i18n | Technical debt | 🔍 When a page is already being modified |
| 4 | More domain policies | Only if discovered | 🔍 Discovery-driven |

## Related

- [ADR-001: Reuse Before Create](../decisions/ADR-001-Reuse-Before-Create.md)
- [Discovery: PhaseService Assessment](20260613-phase-service-assessment.md)
- [Discovery: Election Management Analysis](20260613-election-management-analysis.md)

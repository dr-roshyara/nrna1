# 22: Administrator notes for G-5 (the read side), 2026-11-13

**For whoever operates PublicDigit.** G-5a (the published read model), G-5b (PlaceLookup) and G-5c (the publication feed) are read-only code.

| Question | Answer |
|---|---|
| New database migration? | **No.** G-5 adds no table, column, index or trigger |
| New configuration or environment variable? | **No** |
| New queue, scheduler or cron job? | **No.** The G-5c feed is pulled by its consumers; nothing is scheduled and no outbox is used |
| New screen or HTTP route? | **No.** The contract is in code only; no consumer is wired (D-147) |
| New permission or role? | **No** |
| Can it change official geography? | **No.** It only reads published facts and holds no publication lock (`TimeBoundary` only) |
| Anything to do at deployment? | **Nothing beyond the normal deployment.** The service provider is already registered |

**The G-4 production restrictions still apply, and G-5 changes neither:**
1. **No production steward appointment or Geography publication** until the Platform Owner authority is formally established.
2. **No production database migration** of the Reference Geography tables until an explicit, technically enforced migration-approval control exists. **Until then, there is no official geography in production, and every read answers "nothing published yet" (position 0): empty, not an error.**

**When G-8 data exists (later),** watch PlaceLookup's response time: it matches every published name in PHP. The remedy is an index or a `pg_trgm` prefilter, added by a developer when measured. There's no setting to change.

## G-6a: candidates (2026-11-13)

| Question | Answer |
|---|---|
| New database migration? | **Yes, two Reference Geography ones, in this order:** `2026_11_13_180000_create_refgeo_candidates.php` (an empty table `refgeo_candidates`), then `2026_11_13_181000_one_candidate_per_proposed_revision.php` (a unique index) |
| May it run in production? | **No, not yet. The G-4 condition applies:** no production Reference Geography migration until a technically enforced migration-approval control exists. Development and test databases only |
| Configuration, queues, routes, permissions? | **None.** Use cases only; there's no screen. Stewards act through code that has `ActingPerson` |
| Data | none is created; candidates are recorded only by appointed stewards |

## G-6b: objections (2026-11-13)

| Question | Answer |
|---|---|
| New database migration? | **Yes, a Reference Geography one:** `database/migrations/2026_11_13_190000_create_refgeo_objections.php` (an empty table `refgeo_objections`), after the two G-6a migrations |
| May it run in production? | **No, not yet:** the G-4 migration-approval condition applies |
| Configuration, queues, routes, permissions? | **None** |

## G-6c-1: governance notices schema (2026-11-14)

| Question | Answer |
|---|---|
| New database migration? | **Yes, two Reference Geography ones, in order:** `database/migrations/2026_11_14_100000_add_refgeo_governance_notices.php`, then `2026_11_14_110000_refgeo_findings_resolved_once.php` (one resolution per finding; an explicit constraint name). **If it stops with "finding(s) resolved more than once: …", do not edit or delete those rows:** report the positions to the Principal. The first is (additive columns, CHECKs and a trigger on `refgeo_publications`; a new table `refgeo_publication_persons`, backfilled) |
| May it run in production? | **No, not yet:** the G-4 migration-approval condition applies |
| Configuration, queues, routes, permissions? | **None** |

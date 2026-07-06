# Plan — PB-003 Inbox / Deduplication

**Created:** 2026-07-06
**Last Updated:** 2026-07-06
**Status:** Approved — ready for implementation

**Living document** · authoritative design = IDD (`docs/implementation/backlog/PB-003_Inbox_Implementation_Design.md`, Approved 2026-07-06) · WBS detail = `docs/implementation/backlog/PB-003_PROGRESS.md`. This plan does NOT duplicate them — it tracks execution.

## Objective
Consumer-side effectively-once messaging: `inbox_events` dedupe (key `(event_id, consumer_context)`, D-03), idempotent consumer wrapper (one txn: dedupe-insert → handle → classify → mark), park/re-drive with deadline, handler registry. Unblocks PB-004/005.

## Background
Outbox/relay delivered (PB-001/002). ADR-T4 mandates inbox; Blueprint §6/§7/§8 fixes semantics; grep `Inbox` in app/ = 0 files (nothing to supersede).

## Scope
IN: port package · migration+model · wrapper · registry · `inbox:redrive` · config · 5 test files. OUT: any real handler (PB-004/005), relay/outbox changes, listener wiring, broker.

## Design decisions (already frozen — do not re-decide)
Classification markers (`IdempotentReplay`/`PermanentInboxFailure`/`CausalPreconditionMissing`) · transient ⇒ ROLLBACK leaves no row · park deadline configurable (D-05 pattern) · port package pure PHP.

## Task checklist (mirrors IDD §17 commits)
- [ ] C1 `PB-003:` port package + unit tests (RED first)
- [ ] C2 `PB-003:` inbox_events migration + InboxEvent model
- [ ] C3 `PB-003:` Inbox wrapper + consume tests (7 scenarios)
- [ ] C4 `PB-003:` InboxHandlerRegistry + container wiring
- [ ] C5 `PB-003:` inbox:redrive + scheduling + tests
- [ ] C6 `PB-003:` architecture tests + docs/traceability/backlog updates

## Progress
0/6 commits · 0/18 WBS (derived; tick in PB-003_PROGRESS.md)

## Risks
Marker misuse by future handlers (mitigate: port docblocks + checklist) · park-deadline tuning (Operations, config) · unique-violation race branch needs explicit test.

## Open questions
None blocking. (Handler-registry duplicate-key semantics fixed by mirror of PB-001 registry.)

## Next actions
Start C1: write failing tests `InboxHandlerRegistryTest` + `InboxMessageTest`; confirm RED; then implement port classes only.

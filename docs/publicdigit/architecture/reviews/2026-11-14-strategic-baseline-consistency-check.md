# Strategic baseline consistency check: five business domains (2026-11-14)

| | |
|---|---|
| **Status** | **CHECK, not a design.** It reopens nothing and decides nothing. Written by Claude (Session A), not independent |
| **Commission** | Principal DDD Architect commission 2026-11-14 ("Strategic Baseline Consistency Review and PBDIGIT-345 Task 6"), §4 |
| **Read** | `architecture/strategic-design-status.md`; decision register `reviews/2026-11-08-…` (D-156 … D-158, D-173, D-193, D-200, D-208 … D-210, D-219, D-227 … D-232); `backlog/` (PBDIGIT-61, -74, -75, -266, -341, -345, -346, -347); the routing plan and tactical design; Identity I-TDD-02 rev 12; the code under `app/Contexts` |

**Classification legend:**
- **DEC:** already decided, preserve.
- **IMPL:** designed, not implemented (implementation obligation).
- **TEST:** implemented, not proven (test obligation).
- **PO:** a genuine business decision for the Human ARB Chief.
- **EXT:** production or external dependency.

## Result

| Domain | Accepted baseline | Implementation state | Genuine gaps | Decisions required |
|---|---|---|---|---|
| **Reference Geography** | v3.11 ratified (D-93); D-227 … D-232 (DEC); streets are address components only (DEC) | G-0 … G-8 built (PBDIGIT-346 in progress); importer built, refuses unaccepted data | **EXT:** UNSD/M49 and ISO reuse permission; official Nepal documents; dataset acceptance. **EXT:** production publication needs Platform Owner authority and the enforced migration-approval control. Still open per D-210 (5): PO-1 … PO-8 | none new (PO-1 … PO-8 stay recorded, not raised here) |
| **Membership** | ratified 2026-10-08 (D-96); S1 rev 6; D-209 ledger clock; **D-210** M-TDD-01 rev 4 with gates MI-1 … MI-14, A1 … A3 | ledger slice 1 built; organisation-schema ledger and clock in organisation databases (PBDIGIT-345 tasks 4–5); **lifecycle (admission … resignation) not built** | **IMPL:** lifecycle slice (needs an EP-01 plan). **TEST:** MI and A gates not executed; C3 feed-consumer atomicity (G-6c condition). **PO (legal):** resignation effective time (D-210 (4)) | **none architectural.** Legal: resignation effective time and retention (counsel) |
| **Governance and Organisation** | D-156 (Organisation §49–§51; §51 build gated on an independent review); D-157 thin slice; **D-208** completion with GC-1 … GC-17; X-3 closed at design level | not implemented | **IMPL:** organisation structure (slice 3), AuthorityCheck (slice 4). **TEST:** GC-1 … GC-17. **DEC to reconcile:** D-156 fingerprint drift at the next Organisation act (D-210 (5)) | none new |
| **Membership Forum / Deliberation** | D-158 (minimum package Forum); D-210 integration; D-52 … D-56, D-150 … D-153 | not implemented | **PO:** the 2026-11-13 hybrid-forum direction widens accepted decisions. **Q-D1 … Q-D5** (Q-D1 public reading, with a data-protection [LQ]) are already recorded in **PBDIGIT-347**. The minimum-package forum is buildable without them | **Q-D1 … Q-D5 (PBDIGIT-347)**: needed only before the hybrid parts, not before the minimum package |
| **Newsletter / Communication** | D-158; D-210 (audiences, consent, opt-out, delivery, retry protection, audit) | new design not implemented; the **legacy** newsletter suites are green (PBDIGIT-61 resolved 2026-08-07); legacy defects PBDIGIT-74 (election audience) and PBDIGIT-75 (election tenancy check) open, decision required | **PO (legal):** legal basis, retention, resignation handling (D-210 (4); counsel). **EXT:** mail-provider deduplication over the retry period; monitoring of stuck runs and clock anomalies. **PO:** PBDIGIT-74 (legacy path) | legal basis (counsel) before launch; PBDIGIT-74 decision (legacy) |

## Cross-cutting finding: Identity bindings are designed but not built

**ISO-3:** every organisation request needs the account's **current binding** (D-113: created only by claim redemption).
- **I-TDD-02 rev 12 is accepted with conditions (D-200), but nothing of it is implemented, and it has no backlog item.** This check created **PBDIGIT-349**.
- **Consequences:**
  - PBDIGIT-345 task 6 is implemented and proven against Platform's own `IdentityBindings` port. In production that port answers "not bound" for everyone (fail closed). **No registry organisation is reachable over HTTP until PBDIGIT-349 is done.**
  - The registrar's bootstrap claim (B-8) waits for the same work.
- **Classification:** IMPL (accepted design, not authorised). **No new decision.** Authorising PBDIGIT-349 is a sequencing choice for the Principal, to be made when registry organisations should become usable.

## Consistency verdict

**The five domains are consistent with each other and with the routing work.** No contradiction was found between an accepted design and the implementation:
- Platform imports Membership and Identity only through recorded adapters;
- the R5 boundary holds (`MembershipLedgerIsolationTest`);
- Geography is consumed only through its published language.

**The gaps are implementation and test obligations, external or legal dependencies, and the already-recorded forum questions.** **Proceeding with PBDIGIT-345 task 6 is safe.**

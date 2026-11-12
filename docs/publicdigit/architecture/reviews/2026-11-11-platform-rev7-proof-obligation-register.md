# Platform (tenancy) revision 7: proof-obligation register

| | |
|---|---|
| **What this is** | A list of every proof obligation stated in the Platform design (`2026-11-11-platform-tenancy-tactical-design.md`, SHA-256 `6de2d7393ce5fad6c154ae9d4699ccceacabd3789f36dabbe6ec5b81148636ee`), **compiled from the design without changing it**. Where this register and the design differ, **the design governs** |
| **Execution status** | **No proof obligation has been executed.** No code, no test, no database exists for the Platform context. **Every entry is NOT EXECUTED [TU].** None is resolved by being specified |
| **Use** | Build gates: each test must pass, against real transactions on the target database, before the Platform context can be released |
| **Author** | Claude; not independent |

## A. Concurrency matrix (governing row versions)

| Row | Case | Governing source | Status |
|---|---|---|---|
| 1 | approver removal vs any approval, both orders, first and second approval | R6-5 row 1; D-186 / R7-2 | NOT EXECUTED |
| 2 | second approval vs withdrawal, both orders, with a concurrent removal | R6-5 row 2 | NOT EXECUTED |
| 2a | 3-way race: second approval, approver removal, withdrawal; all 6 orders | R6-5 row 2a | NOT EXECUTED |
| 3 | platform withdrawal vs use; no operation starts after the exclusive lock | R6-5 row 3; rev 5 part 2 row 3 | NOT EXECUTED |
| 3a | organisation-side withdrawal vs `live-database` use, both orders, without platform propagation | R6-5 row 3a | NOT EXECUTED |
| 3b | organisation-side withdrawal vs `backup-copy` admission: before, after, after admission commit but before execution, database failure during admission, next operation refused | **R7-5 row 3b** (supersedes R6-5 3b) | NOT EXECUTED |
| 3c | expiry: admission just before, at, after; delayed between check and execution; expiry command waiting on an admitted operation; **no result after expiry** | R6-5 row 3c | NOT EXECUTED |
| 3d | handover: operation between outbox record and copy arrival refused (both case types); admitted after arrival; platform withdrawal during handover immediate | R7-5 row 3d | NOT EXECUTED |
| 4 | already-open session: withdrawal, then next operation refused | rev 5 part 2 row 4 | NOT EXECUTED |
| 5 | duplicate grant command creates one; same key with different scope rejected | R6-5 row 5 | NOT EXECUTED |
| 6 | contact updates, permutations incl. D-184 same-date cases | R6-5 row 6; R6-3 | NOT EXECUTED |
| 7 | recovery copy: crash between copy and flag; exactly one copy | rev 5 part 2 row 7 | NOT EXECUTED |
| 8 | evidence reconciliation: missing, mismatch, not found, `NotAuthorised`, **`Undetermined`** | R6-5 row 8 | NOT EXECUTED |
| 9 | promotion on recovery: `Authorised` promotes; `NotAuthorised` / `Undetermined` remove and flag; non-promoted entry never approves | R7-5 row 9 | NOT EXECUTED |

## B. Other proof obligations stated in the design

| # | Obligation | Source | Status |
|---|---|---|---|
| B-1 | `attempted`-without-outcome window: reconciliation reports "access may have occurred" | rev 5 part 2 F-05 correction; R7-5 | NOT EXECUTED |
| B-2 | `backup-copy`: the isolated copy expires with the grant and is destroyed; destruction logged and verified | rev 5 F-05 | NOT EXECUTED |
| B-3 | session: revocation on withdrawal, expiry, close, operator Account disablement; interrupted sessions cannot resume | rev 5 F-05 | NOT EXECUTED |
| B-4 | notification lifecycle per recipient; `delivered` only with delivery evidence; retry limit; escalation; D-179 narrow use | rev 5 F-04 + part 2 | NOT EXECUTED |
| B-5 | capture scenario: X removes Y and Z, adds A; no grant possible at any time before recovery | R6-1 (D-183) | NOT EXECUTED |
| B-6 | single-contact / fewer than two `organisation-record` contacts: emergency grant refused | D-182, D-183; R6-1 | NOT EXECUTED |
| B-7 | lifecycle: no technical operation in `pending`; `provisioning` only after verification and grant; `migrating` unroutable | R6-6; rev 4 M-8 | NOT EXECUTED |
| B-8 | bootstrap claim issued once, only to the founding registrar, before `operational` | rev 5 F-07, part 2 | NOT EXECUTED |
| B-9 | failure scenarios 1–13 (revision 1 R-F, revision 4 scenario 13) as amended by later revisions | R-F; rev 4 | NOT EXECUTED |
| B-10 | INV-ACCESS-1 release-1 criteria | multi-database design §16 M-6 | NOT EXECUTED |

**Superseded and not listed:** P-W1 (rev 5 part 2). R6-4 and R7-3 replace it with enforcement in the organisation's database, so no delivery-time bound is relied on.

## C. Additions for revision 8 (after IR-P-02-R3 R3-8)

*Added 2026-11-11 against design SHA-256 `28cfecdfbf11874ae9af9e935d25a91cda962ea1d0b8708ac8de987611dc5006` (revision 8). **Still no proof obligation executed.** Every entry: NOT EXECUTED [TU].*

| # | Obligation | Source | Status |
|---|---|---|---|
| C-1 | recovery transition vs admission: race admission against the R8-2 transaction; no admission under the central grant after it | R8-2 (replaces row 3d's starting point) | NOT EXECUTED |
| C-2 | platform withdrawal during handover, then delayed and retried delivery of the older copy: copy stays withdrawn; no access | R8-3 | NOT EXECUTED |
| C-3 | per-designation binding: several designations in one decision with different outcomes; mismatched person, request, decision part, rule version; replay of one person's `Authorised` against another; reused request id | R8-1 (strengthens row 9) | NOT EXECUTED |
| C-4 | operation idempotency: same id concurrently and sequentially (one admission); same id, different binding (refused) | R8-4 (strengthens row 3b) | NOT EXECUTED |
| C-5 | failure injection: after admission commit before acknowledgement; after `started` before outcome; during retry or reconciliation; no second execution; "access may have occurred" reported where applicable | R8-5 (connects B-1) | NOT EXECUTED |
| C-6 | deadline: operation admitted just before withdrawal and before expiry; no result after min(expiry, limit); shorter grant limit honoured; backup-copy job terminated and output discarded | R8-6, D-189 | NOT EXECUTED |
| C-7 | missing-copy insert race: request refused; only a new request admitted after a fresh check | R7-3, R7-4 | NOT EXECUTED |
| C-8 | D-188: grant approved by a contact whose designation is `NotAuthorised` / `Undetermined` becomes `suspended`; no new admission; in-flight ends by deadline; contact not promoted | R8-7 | NOT EXECUTED |
| C-9 | terminal states never reopen: every terminal state × delayed lower-version and higher-version deliveries | R8-3 | NOT EXECUTED |

*Pointer (2026-11-11): superseded by `2026-11-11-platform-proof-obligation-register.md` (stamped for revision 9). This file remains as a historical record; statuses unchanged (all NOT EXECUTED).*

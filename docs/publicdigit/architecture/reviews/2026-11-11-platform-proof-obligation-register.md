# Platform (tenancy): proof-obligation register (stamped for revision 11)

| | |
|---|---|
| **Design** | `2026-11-11-platform-tenancy-tactical-design.md`, **revision 11**, SHA-256 `e3772d2e8e266d909a1328cb2205b1d9271e47c0890caf5be8984d5724d4e005`. **Where this register and the design differ, the design governs.** *(Stamp history: revision 9 `4e131bfb…` (sections A–D); revision 10 `30ecb53e…` (section E added); revision 11 (section F added; E-8 blocking widened to X-3; nothing removed).)* |
| **Supersedes** | `2026-11-11-platform-rev7-proof-obligation-register.md` (revision 7 header; section C added for revision 8). That file stays as a historical record |
| **Execution status** | **No proof obligation has been executed.** No code, no test, no database exists for the Platform context. **Every entry is NOT EXECUTED [TU].** A specification, test description or review is not execution evidence |
| **Use** | Build gates: each test must pass, against real transactions on the target database, before the Platform context can be released |
| **Author** | Claude; not independent |
| **Sections** | A and B: **carried forward from the revision-7 register unchanged in wording, and reviewed for revision 9.** They all still apply. Row 3d's starting point is extended by C-1; row 9 by C-3 and D-1; row 3b by C-4 and D-2. C: added for revision 8, unchanged. **D: added for revision 9** |

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

## C. Additions for revision 8 (after IR-P-02-R3 R3-8), carried forward

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

## D. Additions for revision 9 (after IR-P-02-R4)

*Every entry: NOT EXECUTED [TU].*

| # | Obligation | Source | Status |
|---|---|---|---|
| D-1 | AuthorityCheck acceptance predicate: an answer whose `ruleRef` or `policyVersion` differs from the Resolution part's recorded competence evaluation is refused; the subject identifier carries decision, part and person; `evaluatedFor` is the decision date | R9-1 (with C-3) | NOT EXECUTED |
| D-2 | `started` claim: concurrent retries and executors on one admission; exactly one reaches `started`; no second execution | R9-3, R8-5 (with C-5) | NOT EXECUTED |
| D-3 | operation registry: same `operationId` reused with a different grant, request, scope, kind or operator is refused; identical binding returns the existing operation | R9-3 (with C-4) | NOT EXECUTED |
| D-4 | deadline clock: dispatch delayed until remaining ≤ 0 (not dispatched); remaining-duration timeouts on the organisation session; gateway cancel and terminate; worker lease and termination; unconfirmed termination leads to copy destruction and a report; central clock unavailable at admission (refused) | R9-4 (with C-6) | NOT EXECUTED |
| D-5 | handover version: the transition increments the version; first delivery applies over an absent copy (version 0); later lower versions are ignored | R9-2 (with C-2, C-9) | NOT EXECUTED |
| D-6 | D-188 report content: failed designation, suspended grants, operations incl. "access may have occurred"; delivered idempotently | R9-5 (with C-8) | NOT EXECUTED |

## E. Additions for revision 10 (after IR-P-02-R5)

*Every entry: NOT EXECUTED [TU].*

| # | Obligation | Source | Status |
|---|---|---|---|
| E-1 | concurrent inbox deliveries (e.g. versions 5 and 6, both orders, plus a duplicate event id): the copy never regresses; terminal copies never change; delivery and admission serialise on the copy row | R10-3 (with C-2, C-9, D-5) | NOT EXECUTED |
| E-2 | registry-only crash: registry committed, organisation admission not; same-binding retry admits once; acknowledgement lost after admission commit, so the stored admission and deadline are reused; abandonment after the window, then the same id is refused | R10-7 (with C-4, D-3) | NOT EXECUTED |
| E-3 | restart durability: the `started` state and registry survive gateway and database restarts; no second execution | R9-3, R8-5 (with D-2) | NOT EXECUTED |
| E-4 | timer-start gap: delay injected between `m0`, the central time read, commit and dispatch; the effective end is never later than `D`; gateway failover recomputes conservatively; `started` never resumed | R10-4 (with C-6, D-4) | NOT EXECUTED |
| E-5 | live termination unconfirmed: the backend ignores cancel and terminate; containment (role revoked, sessions terminated, network closed); grant `contained` and never reopened; operation `termination-unconfirmed`; incident reported | R10-5 | NOT EXECUTED |
| E-6 | D-188 report record: every field present; count equals registry rows; interrupted reconciliation resumes and re-emits a higher version; D-179 notice without content | R10-6 (with C-8, D-6) | NOT EXECUTED |
| E-7 | Identity integration: `PersonRef` → bound Account only through Identity's accepted operation; email matching impossible; **fails closed until X-2 is accepted** | R10-9 X-2, R9-1 | NOT EXECUTED; **blocked on X-2** |
| E-8 | D-190 act kinds: `Undetermined` when the rules are silent; per-part `ruleRef` / `policyVersion` equality; `give-case-grant` never used for emergency grants | R10-1, R11-1 (with C-3, D-1) | NOT EXECUTED; **blocked on X-1 and X-3** (per-part persistence is not closed by D-190; R11-1) |

## F. Additions for revision 11 (after IR-P-02-R6)

*Every entry: NOT EXECUTED [TU]. These refine E-2, E-4, E-5, E-6 and E-8 without replacing them.*

| # | Obligation | Source | Status |
|---|---|---|---|
| F-1 | Governance answers carry the right `actor` / `capacity`: the organ and sitting for designations, the act's maker and their tenure or sitting for case grants; **the designated person is never accepted as the actor** | R11-1 (with E-8) | NOT EXECUTED; blocked on X-1 |
| F-2 | failover formula: delay injected at `m_before`, during the clock request and before dispatch; **effective end ≤ `D`**; `t_now` unavailable → not dispatched | R11-2 (refines E-4) | NOT EXECUTED |
| F-3 | backend that ignores cancel and terminate: containment, then **forced server restart (D-192)**; restart confirmed by server start time; restart unconfirmable → database unroutable; **co-hosted organisations receive incident reports**; session-list authority on the chosen product; `contained` never reported as terminated | R11-3 (refines E-5) | NOT EXECUTED |
| F-4 | report record: `grantId`, `failedDesignationRef`, `suspensionRef`, `outcome` separate from state; null handling for `registered` rows | R11-4 (refines E-6) | NOT EXECUTED |
| F-5 | admission vs abandonment: concurrent admission and tombstone (both orders), late admission before and after the tombstone, central adoption of the organisation's result, no admission attempt started after the window | R11-5 (refines E-2) | NOT EXECUTED |
| F-6 | window parameters: confirmation window (10 s default) and admission window (2 min default) applied on the stated clocks, with boundary cases | R11-3, R11-5 | NOT EXECUTED |

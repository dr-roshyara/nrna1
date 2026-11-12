# Membership CR-01 C1/C2: implementation specification (tactical) — PROPOSED

| | |
|---|---|
| **Status** | **ACCEPTED 2026-11-11 (D-162)**, incl. §11 corrections and CR-02 (D-161). **Build gated:** implementation, when approved, starts with the ledger-clock proof tests. No implementation authorised. Written by Claude, not independently reviewed |
| **Implements** | **CR-01 (RAT, D-73) C0–C2, §2, §3**, with **D-77** (`personRefs[]` key). **Changes no ratified text**; every choice here sits inside the contract |
| **Scope decision** | **D-160:** release 1 serves ledger organisations only; C1/C2 answered only from the ratified ledger |
| **Sources read** | CR-01 (`docs/publicdigit/reviews/2026-11-09-membership-change-request-cr-01-organisation-consumer-and-as-known-at-queries.md`); ratified §8, INV-COV (`…membership-architecture-ratified.md:122`); kernel `app/Contexts/Membership/Domain/Organisational/` (`Member::knownAt`, `ResolvedPeriod::lifetime`); `PostgresMemberRepository` (append transaction, lines 184–238); shared outbox (`app/Contexts/Shared/Infrastructure/Outbox/OutboxWriter.php`); cutover gate C-1 … C-10 (`2026-10-09-membership-pbdigit264-plan-ratification-pack-and-cutover-gate.md` §4) |

**Labels:** [AD] decided · [RAT] ratified · [BF] fact · [AC] conclusion · [TD] tactical design · [TU] proof obligation · [OQ] Principal question.

## 0. Findings first

1. **[BF] The ledger exists but is not live.**
   - Kernel, port and PostgreSQL adapter are implemented (PBDIGIT-258/259/260).
   - **The migration has not been rolled out**, and the cutover machinery (gate C-1 … C-10) is held.
   - **Live membership runs on the legacy store**, which keeps a status and dates but no record time and no corrections. **It cannot answer "as known at τ".** Hence D-160.
2. **[BF] Neither C1 nor C2 exists in code** (no `CoverageKnownAt`, no coverage event).
3. **[BF] The P3 risk named in CR-01 §8 is still present.**
   - `recordedAt` is taken from the application clock inside the append transaction: strictly increasing **per membership** (line 220), **but not ordered with commits across memberships**.
   - A slow transaction can commit **after** a faster one with a **smaller** `recordedAt`. **`closedThrough` cannot be guaranteed until this is fixed** (§4).
4. **[AC, reading of the ratified text] "Coverage" means INV-COV**, the union of period **lifetimes** (ratified §122; CR-01 C1 cites INV-COV).
   - **A lapsed period (term expired, not ended) is inside coverage.**
   - Like suspension (D-153), **lapse is not visible in C1/C2**; it is a C3 predicate.
   - **Consequence:** in release 1, lapsed members still belong, read and write.
   - **Changing that needs a Membership change request**, not this specification.

## 1. Business purpose (one paragraph)

- **Organisation must know, for each person it places, over which dates their membership exists**, as Membership worked it out, and as known at a stated time.
- That decides whether a member's unit belonging counts: for newsletter audiences, forum access (D-152, D-153) and placement.
- **Membership answers; Organisation never works out membership itself** (C0).

## 2. What is built (scope)

| # | Element | Owner | Placement |
|---|---|---|---|
| 1 | **Coverage function**: `coverage(ResolvedMember) = ⋃ period.lifetime()` (INV-COV) | Membership domain | kernel (pure PHP) |
| 2 | **Ledger clock per organisation** (fixes P3, gives `closedThrough`) | Membership infrastructure | §4 |
| 3 | **C1 `MembershipCoverageResolved`**, written to the shared outbox **in the same transaction** as the fact | Membership infrastructure | §5 |
| 4 | **C2 `CoverageKnownAt`** query service (Published Language DTOs) | Membership application | §6 |
| 5 | **Source-of-truth check** (gate C-1 flag): answers only for ledger organisations | Membership application | §6 |
| 6 | **Organisation's consumer side**: `MembershipCoveragePort` adapter (anti-corruption), C1 projection, rebuild | Organisation infrastructure | §7 |

**Not in scope:**
- C3 and C4 (same machinery, later);
- cutover machinery and legacy migration (gate C-1 … C-10, own plan);
- HTTP or UI;
- any change to ratified invariants.

## 3. Coverage [TD]

- **Definition:** for a membership with facts `F`, `coverage = ⋃_{p ∈ Res(F).periods} p.lifetime()`, as an ordered list of disjoint `[from, to)` intervals, `to` open for current.
- **As known at τ:** `coverage_τ = coverage(member.knownAt(τ))`; **`seq_τ`** = the highest `seq` with `recordedAt ≤ τ` (0 if none).
- **Property tests:**
  - (a) coverage equals {t : some period's lifetime contains t};
  - (b) **INV-COV**: equals the union of live base claims;
  - (c) monotonic knowledge: `knownAt(τ)` uses a prefix of the log (INV-14);
  - (d) Annul and Void restore earlier coverage;
  - (e) Correct moves boundaries.
- **A person with no membership** in the organisation (as at τ) → **empty coverage, `seq` 0**. That is "not a member", not an error.

## 4. Ledger clock and `closedThrough` (fixes P3) [TD, TU]

**Requirement (CR-01 §3):**
- every fact with `recordedAt ≤ closedThrough` is committed and visible;
- **no fact with `recordedAt ≤ closedThrough` can commit later**;
- proved by **transaction ordering**, not by moving timestamp generation (CR-01 §9).

**Design:** one row per organisation, `ledger_clock(organisation_id, last_recorded_at, closed_through)`.

1. **Append** (inside the existing transaction, after the version compare-and-set):
   - lock the organisation's clock row (`FOR UPDATE`);
   - `recordedAt = max(appClock, last_recorded_at + 1µs, closed_through + 1µs)`;
   - update `last_recorded_at`;
   - insert the fact;
   - write the C1 outbox row;
   - commit. **Appends of one organisation are serialised at this point only**, so record-time order equals commit order within the organisation.
2. **`closedThrough` read** (start of a C2 query):
   - try to lock the clock row **without waiting** (`FOR UPDATE NOWAIT`);
   - **if free:** no append in flight, so set `closed_through = max(closed_through, appClock)`, commit, and return it;
   - **if busy:** return the stored `closed_through` (an append in flight will record after it, by step 1's `+ closed_through`).
3. **Why it holds:** any append either committed before the read (visible) or takes the lock afterwards and records **after** `closed_through` (step 1). **No fact can appear at or before a reported `closedThrough`.**

**Cost:** membership writes of one organisation serialise briefly. They are low in volume; bulk imports use batches inside one transaction.

**[TU] Proof obligations:**
- a concurrency test with two appends and a reader in interleaved transactions;
- a test that a reported `closedThrough` is never later contradicted;
- a clock-skew test (application clock going backwards).

## 5. C1 `MembershipCoverageResolved` [TD]

- **Written** to the shared outbox (`OutboxWriter::store`) **in the append transaction**, only if `coverage` after the append differs from before (CR-01: gaps in `seq` allowed).
- **Fields exactly as CR-01 C1:**
  - `eventId`, `organisationId`, `membershipId`, `personRef`;
  - `seq` (= the membership version just written);
  - `coverage` (full state);
  - `recordedAt`, `schemaVersion = 1`.
- **Delivery:** the existing relay, at least once. **No completeness meaning** (CR-01 §3).

## 6. C2 `CoverageKnownAt` [TD]

**Signature:** `CoverageKnownAt(organisationId, membershipIds[] | personRefs[], τ)` → `{closedThrough, items: [{membershipId | personRef, seq, coverage}]}`.

**Procedure:**
1. **Tenant check:** `organisationId` must be the working organisation (INV-ORG-SCOPE-1); otherwise refused.
2. **Source check (D-160):** the organisation's authoritative store must be the ledger (gate C-1 flag); otherwise refused **`SOURCE_NOT_LEDGER`**. The consumer treats this as "unavailable": refused, never assumed.
3. **Boundary:** read `closedThrough` (§4 step 2); if `τ > closedThrough`, refused **`BOUNDARY_NOT_CLOSED`** with `closedThrough` (CR-01 §3). **Never a partial answer.**
4. **For each key:** load facts with `recorded_at ≤ τ`, `Member::knownAt(τ)`, return `seq_τ` and `coverage_τ`.
   - An unknown `personRef` gives empty coverage and `seq` 0 (§3).
   - An unknown `membershipId` is an **error** (Organisation only knows ids Membership gave it).
5. **Batch limit** per call ([TU], e.g. 1 000 keys). **The consumer pages; all pages use the same τ.**

**No `asOf`** (CR-01 C2): the consumer evaluates its business date against the intervals.

## 7. Organisation's consumer side [TD]

- **`MembershipCoveragePort`** (Organisation application) with an adapter in Organisation infrastructure that calls Membership's C2 service **in-process**, translating the Published Language into Organisation's own value objects.
  - **Organisation never reads `org_membership_*` tables** (no shared persistence; architecture test).
- **Projection from C1:**
  - keep the highest `seq` per stream;
  - lower → ignore;
  - equal and identical → ignore;
  - **equal and different → integrity error, raised, not applied** (CR-01 C1).
  - Used **only for current views**.
- **Every "as at d, as known at τ" answer uses C2 at that τ** (§50.8), never the projection.
- **Rebuild:** C2 at `τ = closedThrough` for all known memberships.
- **Admission just before τ:** use the `personRefs[]` key, since the projection may not know the id yet (D-77).

## 8. Tests (RED first)

| Level | Tests |
|---|---|
| **Kernel (unit)** | §3 property tests (a)–(e) |
| **Membership infrastructure (PostgreSQL, testing database)** | append writes fact, clock and C1 atomically (rollback leaves none); §4 concurrency and skew tests; C1 only on coverage change; **two organisations' clocks independent** (tenant isolation) |
| **C2 service** | `BOUNDARY_NOT_CLOSED`; `SOURCE_NOT_LEDGER`; cross-tenant refused; τ before / after an End, an Annul, a Correct (CR-01 §7 scenario reproduced exactly); `personRefs[]` key; unknown person → empty |
| **Organisation consumer** | the four C1 consumer rules; rebuild equals projection; **architecture test: no Organisation code references Membership tables or domain classes** |
| **Architecture** | Membership domain stays pure; Published Language DTOs are the only cross-context types |

**Never `migrate:fresh` / `migrate:refresh` on the development database.** Tests use `RefreshDatabase` on the testing connection.

## 9. Dependencies and order

1. **The ledger migration rolled out** (gate C-10, dev then prod).
2. **The source-of-truth flag** (gate C-1): **new organisations set to "ledger" at joining (D-160)**; existing ones only through the full cutover.
3. **Membership's application path writes the ledger for ledger organisations** (Membership slice 3–4 / M-TDD-01): admission, end and correction through the kernel.
   - **Release 1 cannot admit members otherwise.**
4. **Then** C1/C2 (this specification), **then** Organisation belonging (build slice 3 in the revised order).

## 10. Open points

- **[OQ] none** (D-160 decided the source).
- **[AC] Lapse and suspension are not in C1/C2** (§0 finding 4; D-153). A later Membership change request if the Principal wants them to affect belonging.
- **[TU]:**
  - batch size;
  - the relay's retry policy;
  - whether `NOWAIT` contention needs a short retry before returning the stored `closedThrough`.

## 11. Corrections after the conformance review and CR-02 (2026-11-10)

*Source: `docs/publicdigit/architecture/reviews/2026-11-10-membership-c1-c2-contract-conformance-review.md` (self-review, not independent) K-1 … K-7; D-161 / CR-02. These govern where they differ from §0–§10.*

- **K-1:** §0 finding 4 is **[RAT]**, not a reading. Ratified §92 says Renew, Suspend, Restore and ChangeType never change coverage; §102–§104 define Lapsed, Suspended and `IsMember`.
- **CR-02 (D-161) supersedes §0 finding 4's consequence:**
  - C1/C2 now also carry **`inForce`** and **`suspended`** intervals (full state, as known at τ);
  - **C1 is published when coverage, `inForce` or `suspended` changes** (`schemaVersion` 2);
  - kernel: `inForce = ⋃ period.term() ∩ lifetime` (= `IsMember`); `suspended = ⋃` restriction intervals within lifetime;
  - **added property tests:** `inForce` equals {t : `isMember(t)`}; suspension never changes `coverage` or `inForce`.
- **K-2, ledger clock assumptions (§4):**
  - **A1/A2:** the append and the `closedThrough` step run under **READ COMMITTED**. A serialisation error under a stricter level is **retried or fails closed**, never ignored.
  - **A3:** **facts are read only after `closedThrough` is fixed**: a separate committed step, then new statements.
  - **One clock source** for writers and readers.
  - **Added proof tests:** a reader between a writer's lock and its commit; a REPEATABLE READ misuse fails closed.
- **K-3:** `SOURCE_NOT_LEDGER` is **an extension of the refusal set under D-160**, not CR-01 text. It is still a refusal, never a partial answer.
- **K-4:** `closedThrough` is **per organisation** [AC]. That is sufficient because every C2 call names one organisation.
- **K-5:** consumers **never convert a refusal into "not a member"**. A refusal makes the dependent act refused.
- **K-6:** C1/C2 **state the interval time unit and the `[from, to)` convention**, so Organisation compares its business dates without interpreting Membership time [TU: the kernel `TimePoint` unit].
- **K-7:** this specification has **two halves of one contract**:
  - **Membership provider** (§2 items 1–5, §3–§6);
  - **Organisation consumer** (§7).

  Each has its own tests and "done", and **architecture tests run both ways**.
- **Organisation consumer (§7) passes standing on:**
  - **`MembersOfScopeAsAt` and `BelongingWithAncestorsAsAt` return, per person, belonging (from coverage) and good standing (in force and not suspended), at the same τ;**
  - Communication and Deliberation apply D-161;
  - **Organisation decides no right itself.**

**Correction note (2026-11-11, IR-R1-01 H-2; changes no meaning of D-162):**
- **Every Membership append takes the organisation's ledger-clock lock first, then the membership's version compare-and-set** (previously the reverse, §4 step 1).
- The ordering guarantee (A1–A3) is unchanged.
- **One lock order across single and grouped appends removes the deadlock between them.**
- The AuthorityCheck answer is obtained **before** any lock.

---

**Amendment pointer (2026-11-12, D-209):** §4 (ledger clock) and §11 K-2 are **amended in mechanism** by `docs/publicdigit/architecture/reviews/2026-11-12-c1-c2-ledger-clock-amendment-proposal.md` (accepted revision 2, `a6d9b05f…`):
- database `clock_timestamp()` for every writer and the `closedThrough` step;
- refuse and retry on regression, never adjusted;
- due scheduled resignations recorded before `closedThrough` passes their date.

**The text above is not edited; where it conflicts with the amendment, the amendment governs.** The contract (shapes, refusals, `closedThrough` meaning) is unchanged.

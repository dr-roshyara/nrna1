# ADR-C: Network lock before the single constitutional assessment at the vote

**Status: ✅ APPROVED CONDITIONALLY (G-ADR-C) by the Principal on 2026-10-14, as r2.**
- **Conditions, all mandatory:**
  - the full §6 proof list, including the same-session re-acquire **before any safety claim**;
  - the §3.1a busy sequence (no vote and no assessment-generated security event on a timeout);
  - **§8.3:** a failed refusal-record write is **logged critically and alertable**, while the voter still gets the busy response.
- **Lock entry point:** the narrow public `IpVoteLimit::lockNetworkForCast()` (§8.1), subject to the proof requirements; no wider refactor.
- **Approves the design only.** Tests need **G-ISO**; P8 needs its own authorisation. **No implementation authorised.**
- **Earlier status:** PROPOSED r2, awaiting Principal approval. r2: lock-timeout failure sequence (§3.1a) after the Principal review of 2026-10-14.
- **Drafted under:** P0 of the PBDIGIT-286 plan (G-PLAN approved 2026-10-14).
- **Authorises nothing:** no code, tests or database access until G-ADR-C **and** G-ISO.

**Created:** 2026-10-14 · **Ticket:** PBDIGIT-286 · **Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0), `adr/`.

**Consumes:**
- decision record r3 (§3.2 D6, D7, D9; §4.9);
- plan r3 (P6 count change, P8; AC-11, AC-12);
- ADR `ADR_20261005_0950_Voting_Authorization_Boundary.md` and the PBDIGIT-240 rule "one security assessment per attempt" (D-240-1).

> **What this ADR protects, in one line:** *Two simultaneous votes from one network can't both pass System B's count, and System B is still assessed exactly once per vote attempt.*

---

## 1 · Context (facts, verified in source)
**Today's order** in `VoteController::store`:
1. transaction (`:1480`);
2. **single security assessment** (`:1491`; System B count + evaluation), **no lock held**;
3. constitutional gate (`:1515`);
4. lifecycle and membership checks (refusals roll back);
5. **per-voter lock** `Cache::lock(…)->get()`, **non-blocking** (`:1641`);
6. `forCast` (`:1661`), ballot validation;
7. **System A** `IpVoteLimit::castRefusal` (`:1812`): **per-network PostgreSQL advisory lock** `pg_advisory_xact_lock(hashtext('ip_vote_limit'), hashtext(lockKey))`, blocking with `lock_timeout` from `voting.ip_lock_timeout_ms` (5000 ms), held until commit/rollback (`IpVoteLimit.php:118-139`); then System A's count;
8. participation update, vote insert, vote-code email (**synchronous**, `:1925`; BL-EMAIL);
9. commit (`:1941`).

**Lock details:**
- **Key:** `IpVoteLimit::lockKey($election, $ip)` = `election_id | IpVoteGroup::of($ip)` (IPv4 address; IPv6 /64) (`:113`).
- **Scope:** real elections only; skipped when the IP is whitelisted (`limitFor` = null); skipped on non-PostgreSQL drivers (`:123`).

**System B's count** (`TrustLegitimacySecurityVerdict.php:68-71`) is exact-IP and tenant-scoped.

**The race:** two votes from one network can both pass step 2 before either commits. Only System A (step 7) is serialised, so System B can be exceeded up to System A's limit.

## 2 · Decision drivers
- **D9 = (3):** lock before the single assessment; no assessment-before-lock race.
- **D6:** System B counts with System A's grouping, election-scoped. Tenant-context handling must be specified here.
- **D7:** `>=`.
- **PBDIGIT-240 D-240-1:** one assessment per attempt.
- **Fail closed;** no deadlock; minimal change.

## 3 · Decision (proposed)

### 3.1 New order in `VoteController::store` (real elections)
1. Transaction (unchanged).
2. Resolve the user and election (unchanged).
3. **If real and not whitelisted: acquire the per-network advisory lock**, the same key and namespace as System A, through a new public `IpVoteLimit::lockNetworkForCast(Election, string $ip): bool`. It is the existing private `lockNetwork` made public, with the same `lock_timeout` handling. **On timeout, the exact failure sequence is §3.1a. No assessment runs.**
4. **The single** `assess()`. System B's count now runs **after** every earlier vote from the same network group has committed.
5. Constitutional gate, lifecycle, membership (unchanged; every refusal rolls back, releasing the lock).
6. Per-voter lock (non-blocking, unchanged).
7. `forCast`, ballot validation (unchanged).
8. `castRefusal`: the advisory lock is already held. PostgreSQL grants a lock the session already holds immediately, and it is still released at the end of the transaction. **This is proven by a test before any claim** (§6). Then System A's count.
9. Participation update, vote insert, email, commit (unchanged).

**Demo elections:** no lock (unchanged; demo is exempt from System A and isn't gated by System B).

### 3.1a Lock-timeout failure sequence (revision r2, Principal finding 1)
**Facts, read-only:**
- **The transaction is aborted on timeout.** A PostgreSQL lock timeout raises SQLSTATE `55P03` inside the cast transaction and aborts it: until it is rolled back, every further statement in it fails. `lockNetwork` catches `55P03` and returns `false` **without** issuing another statement (`IpVoteLimit.php:131-136`).
- **The refusal record touches no database.** `IpVoteLimit::recordRefusal()` writes the admin-facing `ip_blocked` entry through `ElectionAuditService::log`, which **only writes files** (JSONL; `File::` operations, no database access). Its metadata comes from `limitFor`/`limitSource`, which read config and the loaded model only (`IpVoteLimit.php:191-205`). So it can run after the rollback.
- **Today's System A busy path already uses this order:** rollback, then `recordRefusal` (`VoteController.php:1818-1820`).
- **Assessment-generated security events** (`election_security_events`) are written on the **separate** `pgsql_audit` connection (`ElectionSecurityEvent::$connection`; `SecurityEventRecorder.php:34-36`; ADR_20260806_1340). So they would survive a cast rollback, and an assessment must therefore **not** run on the busy path.

**Required sequence on timeout:**
1. `lockNetworkForCast()` returns `false` (no further statement in the aborted transaction).
2. **Stop before the assessment.** `assess()` is not called, so no security event and no evaluation exist for this attempt (D-240-1: zero assessments for a refused-before-assessment attempt; never two).
3. **Roll back** the cast transaction (`failCastClosed`; the per-voter lock isn't held yet at this point, so there is nothing to release).
4. **Write the refusal record after the rollback:** `recordRefusal($election, $user, 'vote', $ip, 'network_busy')` (file-based, database-free), plus a `voting_audit` warning (no IP).
5. **Return** `IpVoteLimit::BUSY_MESSAGE` to the voter.

**Two separate records:**

| Record | Store | Written on the busy path? |
|---|---|---|
| **Network-busy refusal** (admin-facing) | election audit file, `ip_blocked`, `reason: network_busy`, `step: vote`, `limit_source` | **yes** (step 4) |
| **Assessment security event** (forensic) | `election_security_events` on `pgsql_audit` | **no** (no assessment ran) |

**Failure of the refusal record itself:**
- `ElectionAuditService::log` has no internal catch. If the file write throws, the exception reaches `store()`'s outer `catch` → `failCastClosed` (a no-op after the rollback) → the generic failure message.
- **No vote can result.**
- **The admin-facing record is lost for that attempt:** a file-store failure, logged by the outer catch.

**Requirement (decided 2026-10-14, §8 item 3):** step 4 is wrapped so that a file-write failure is logged **critically** (`voting_audit`) and is **alertable**, **and** the voter still receives the busy response. Alert routing is an operational item under G-REL. No reconciliation source exists for it, since nothing about the attempt is persisted in the database.

### 3.2 Lock scope = count scope (D6)
- **What System B counts** after P6: completed votes through `IpVoteLimit::votesFrom($election, $ip)`, the same IP group as the lock key. Every vote the count includes is therefore serialised by the lock. The lock is never narrower than the count.
- **Tenant-context handling:** `votesFrom` filters by `election_id` and ignores the tenant global scope (`withoutGlobalScopes`). This is safe and **not cross-election**:
  - the election was resolved and authorised before the cast (route binding with tenant checks, `ensureVoterMembership`);
  - its UUID is globally unique;
  - the count never spans elections.
- **Why drop the tenant scope:** it removes System B's dependency on an ambient tenant context. Without it, a missing or different context could silently under-count, which today's tenant-scoped query is exposed to (record §2.2) [I].

### 3.3 Retry and timeout
- **No server-side retry:** a busy vote is refused, and the voter retries. This is unchanged from System A (PBDIGIT-288).
- **Timeout:** `voting.ip_lock_timeout_ms` (default 5000), unchanged.
- **Known factor:** the synchronous email (step 9) extends the hold time; addressed by BL-EMAIL (separate).

### 3.4 Deadlock analysis [I; confirmed by test]
- **Only one waiting lock:** the advisory lock is the only lock that waits, and the per-voter lock never waits. So no cycle forms between the two.
- **Different networks** use different keys.
- **One voter on two networks:** each request takes its own network lock; the second fails the per-voter lock immediately.
- **No other advisory namespace** is taken in this transaction.

### 3.5 What stays unchanged
- One assessment per attempt (D-240-1).
- System A's rule, refusals and audit.
- Code entry: no lock, because it writes no vote. The stricter limit still applies there, since both controls refuse independently.
- Demo behaviour.

## 4 · Options considered
| Option | Concurrent excess of System B | D-240-1 | Verdict |
|---|---|---|---|
| (1) accept and document | possible, up to System A's limit | kept | rejected (D9) |
| (2) second assessment under the lock | prevented | **broken** (two assessments, two security events) | rejected |
| **(3) lock before the single assessment** | **prevented** (for real, non-whitelisted elections on PostgreSQL) | **kept** | **proposed** (D9) |

## 5 · Invariants
- **I-C1:** for a real, non-whitelisted vote, System B's count and decision happen while holding the network-group lock, and the lock is held until commit or rollback.
- **I-C2:** exactly one security assessment per cast attempt.
- **I-C3:** the lock group is identical to the count group.
- **I-C4:** a lock timeout refuses with no vote and no assessment.
- **I-C5:** the count is election-scoped and never crosses elections.

## 6 · Consequences and required proof (plan P8; isolated, two connections, `lock_timeout` technique)
1. **Busy before the assessment (§3.1a).** Connection 2 holds the network lock; a vote from that network is refused as busy. Each of these is verified **independently**:
   - (a) **no vote** and no participation consumed;
   - (b) **no assessment record:** no `election_security_events` row for the attempt on `pgsql_audit`;
   - (c) **exactly one** `ip_blocked` audit entry with `reason: network_busy`, `step: vote`;
   - (d) the voter receives the busy message.

   **Second case (mandatory, §8 item 3):** with the audit-file write forced to fail, the voter still receives the busy response and a critical `voting_audit` entry exists.
2. **Same-session re-acquire:** in one transaction, taking the lock twice succeeds immediately, and the lock is released at commit. **No concurrency claim before this passes.**
3. **Serialisation:** with System B stricter (for example N = 2, Unverified → 1), a held lock shows that the waiting vote counts after the first commit and is refused at the boundary (D7 `>=`).
4. **Whitelisted** network: no lock is taken.
5. **Regression:** `IpVoteLimitAtCastTest` and `IpRefusalAuditTest` unchanged.

**Limits that stay:**
- non-PostgreSQL drivers skip the lock (production is PostgreSQL);
- a single process can't reproduce a true parallel race. The database primitive and the production order are what get proven, as in the Phase 4 CAS evidence (`2026-10-05-phase4-final-cast-hardening-po-decision-record.md` §4).

## 7 · Anonymity-boundary assessment
- **No new data:** the lock key contains the election ID and an IP group. It exists only in PostgreSQL's lock table for the transaction's duration, and is never persisted or logged.
- **No link** between voters and votes.

## 8 · Open questions / approval conditions
1. **Lock entry point:** DECIDED 2026-10-14. The narrow public `lockNetworkForCast` (no separate lock class, no wider refactor).
2. **The concurrency proof list (§6)** is the mandatory acceptance condition for P8. **No safety claim** about the same-session re-acquire before §6 item 2 passes.
3. **§3.1a step 4 wrapping:** DECIDED 2026-10-14. A refusal-record write failure is logged **critically and alertably**; the voter still gets the busy response.

## 9 · Recommendation
**Approve option (3) as specified**, with the §6 proof list as P8's acceptance.

**Traceability:** PBDIGIT-286 · R2, R3 · D6, D7, D9 · PBDIGIT-240 D-240-1 · decision record r3 §4.9 · plan r3 P6, P8; AC-11, AC-12.

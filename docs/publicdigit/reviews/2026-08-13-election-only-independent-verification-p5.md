# Election-Only independent verification — **P5: the voting-time journey**

**Commission:** Product Owner · Session 1 · **verification only; nothing repaired, nothing decided**
**Date:** 2026-08-13 · **Baseline:** frozen 1,376 · **L3:** 240/1,376 *(unchanged — verification is not classification)*

---

## 1 · Executive summary

> **The voting-time eligibility decision is ELECTION-SCOPED and does not consult ambient organisation, tenant or Membership state.** The cross-election boundary holds **at the predicate level, by trace**. The rejection of an ineligible voter is an **intentional 403/redirect, not an exception**. **The admission snapshot is immutable and authoritative at admission; at voting time the materialised `ElectionMembership` row — not the snapshot — is the authority, by design.**
>
> **Three qualified findings:** the eligibility predicate is **cached for 300 s**, so revocation propagation speed is an **unspecified business rule**; the voter-gate contains an **ambient session-keyed demo bypass** with a defense-in-depth backstop on the critical routes; and **the voting-time decision has no bounded-context owner in executing code** — it is an Eloquent helper plus middleware.
>
> **⛔ And one instrument correction of my own, caught before publication: I nearly reported the entire voting middleware stack as dead code because I trusted an *invalid* artisan command that silently returned zeros.**

## 2 · The Election-Only runtime journey — measured, not assumed

**`POST v/{vslug}/vote/submit` carries a 12-middleware stack** (from `route:list --json`, resolved classes):

```
web → SubstituteBindings → VerifyVoterSlug → ValidateVoterSlugWindow
→ VerifyVoterSlugConsistency → EnsureElectionVoter → EnsureVoterStepOrder
→ VoteEligibility → ValidateVotingIp → EnsureRealVoteOrganisation
→ ThrottleRequests:10,1 → EnsureVotingActive
```

**Coverage measured:** `VoteEligibility` on **20 routes** (the whole `v/{vslug}/…` flow + `POST votes`) · `EnsureVotingActive` on **5** (`vote/create` · `vote/submit` · `vote/verify` ×2 · `vote/complete`) · `EnsureElectionState` on **35**.

## 3 · P5-A — voting-time eligibility trace

**1 · The deciding component:** `EnsureElectionVoter` → **`User::isVoterInElection()`** (`app/Models/User.php:315`):

```php
Cache::remember("user.{$this->id}.voter.{$electionId}", $ttl /* 300s */, fn () =>
    $this->electionMemberships()
        ->where('election_id', $electionId)
        ->where('role', 'voter')
        ->where('status', 'active')
        ->exists());
```

**2 · Ownership in EXECUTING code:** 🔴 **no bounded context owns this decision.** It is an **Eloquent model helper invoked from HTTP middleware**. The Domain port `VoterEligibilityPolicy` (Contexts\Elections) governs **admission-time** only — CF-3's traced chain — and is **not consulted at voting time**. **OBSERVED; not converted into an architectural decision.** *(Recorded for governance: voting-time entitlement enforcement lives in Interface/Infrastructure, the same placement class as the C8 one-vote gate.)*

**3–5 · Inputs to the decision:** `user_id` (from the authenticated slug owner — `VerifyVoterSlug:69` blocks `slug.user_id !== auth()->id()`) · `election_id` (resolved from the **slug's** election via `VerifyVoterSlugConsistency`, not from ambient session) · membership `role`/`status`. **No `organisation_id`, no tenant session key, no `members` table, no Membership-context read.**

**6 · `PBDIGIT-65`/`69` reported ambient/tenant-blind behaviour:** **NOT ESTABLISHED whether the reported instance is fixed** — I did not reproduce the tickets' original scenario. **What the trace shows:** on the slug path the decision inputs are election-scoped and ambient-free. **The untracked `ElectionOnlyEntitlementPinTest` (6 rows, all ERROR, not mine) targets exactly this territory; its rows cannot serve as evidence in either direction.**

**7 · Cross-election boundary:** ✅ **VERIFIED BY TRACE at the predicate level.** Membership in Election A cannot satisfy the gate for Election B: the query is `where('election_id', $electionId)`, the slug is bound to one election, and the slug is bound to one user. **Runtime execution evidence for the cross-election scenario was NOT gathered** (the pin tests that would provide it all ERROR and are not mine to run as evidence).

**⚠️ Qualified finding Q-P5-1 — the 300-second cache.** Eligibility is cached per user/election for `config('election.voter_cache_ttl', 300)`. **Consequence: revoking or suspending a membership may not take effect at the gate for up to 5 minutes.** **BUSINESS RULE NOT SPECIFIED: how quickly must entitlement revocation propagate?** *(Cross-references Session 2's finding that no test asserts a suspended voter cannot vote.)* **Not a defect claim.**

**⚠️ Qualified finding Q-P5-2 — ambient session key in the gate.** `VoteEligibility:37` bypasses on `session('selected_election_type') === 'demo'` — **session state persisted at `ElectionController:122/220` and cleared only at `:288`.** A stale demo session skips `VoteEligibility`'s constitutional check on real slug routes. **Defense-in-depth holds where it matters:** the 5 constitutional voting routes carry `EnsureVotingActive` *after* it, and `EnsureElectionVoter` reads `$election->type` from the **record**, not the session. **No demonstrated hole; recorded because it is the one ambient-keyed branch in an otherwise election-scoped gate.**

## 4 · P5-B — admission → voting contract

| Question | Answer |
|---|---|
| Where is Election-Only selected? | election creation: `VoterSourceStrategy::fromOrganisation()` — approved callers only |
| Where persisted? | `elections.voter_source_strategy` — **immutable snapshot** (`O-3`; the 3 `VoterStrategySnapshotTest` rows verify it and pass in isolation) |
| Who consumes it later? | **admission side only:** `ElectionVoterController` · `VoterImportController` · `VoterImportService` (all via `fromElection()`) |
| Can an org setting mutate an existing election's meaning? | 🔴 **No path found.** The snapshot is immutable and `fromElection()` throws on null rather than falling back to the org |
| Can later Membership configuration change voting eligibility? | 🔴 **No** — the voting-time gate reads **only** `election_memberships`; it never consults `members`, fees, or `uses_full_membership` |
| Is the snapshot authoritative at voting time? | **By design it is not CONSULTED at voting time.** The snapshot governs *who may be admitted*; the **membership row is the materialised entitlement** the gate enforces. **A design fact, not a bypass** |
| Paths bypassing the snapshot? | none found on the traced flow; **NOT ESTABLISHED** for un-traced admin paths |

## 5 · P5-C — the rejection boundary

**An ineligible voter on the slug path receives an INTENTIONAL outcome, not an exception:** `EnsureElectionVoter` returns **403 JSON** (`expectsJson`) or a **redirect to `election.dashboard` with an error message**; the legacy `VoteEligibility` election-scoped check likewise returns **403/redirect**. **No Membership or ambient context participates in the rejection decision.**

**Separation from `EM-OPEN-021`, kept explicit:** the *ineligible-voter* rejection (403) is healthy and intentional. **The `EM-OPEN-021` throw is a different situation** — an election whose *state cannot be derived* — and on those same 5 constitutional routes `EnsureVotingActive` derives state, so **the anomalous election 500s the whole real voting flow**, which **strengthens the P2/P3 finding**: the stack is confirmed live on 20 routes, not theoretical.

## 6 · ⛔ Instrument correction — disclosed

I ran `php artisan route:list --columns=…`, an **option that does not exist in this Laravel version**. The command failed, my `grep -c` piped over its error output, and **every middleware alias measured 0** — I had drafted *"`VoteEligibility` is dead middleware"* from four independent-looking checks that shared the same broken instrument. **Caught because `voter.slug.verify = 0` was implausible**, then re-measured with `route:list --json`: **20 routes.** *(A second, smaller attribution error in the same sweep: aliases never appear in `route:list` output — it prints resolved class names — so grepping for alias strings can only ever return 0.)* **Eleventh incident. The pattern extends: a failed instrument that returns EMPTY is indistinguishable from a true negative unless the command's own success is verified first.**

## 7 · Findings classified

| Classification | Finding |
|---|---|
| **VERIFIED** | eligibility gate is election-scoped (`election_id` + `role` + `status`) · slug→user binding · 12-middleware stack live on the voting flow · rejection is intentional 403/redirect · snapshot immutable with no org-mutation path · voting-time gate independent of Membership configuration |
| **DEFECT OBSERVED** | none new in this mission |
| **PRE-EXISTING** | the `EM-OPEN-021` throw reaching the live voting stack *(P2/P3, strengthened)* |
| **ARCHITECTURE / OWNERSHIP UNKNOWN** | **voting-time entitlement has no bounded-context owner in executing code** — Eloquent helper + middleware *(admission-time has one: `VoterEligibilityPolicy`)* |
| **BUSINESS DECISION REQUIRED** | none newly created — `EM-OPEN-021` unchanged |
| **BUSINESS RULE NOT SPECIFIED** | **Q-P5-1:** revocation-propagation speed under the 300 s eligibility cache |
| **NOT ESTABLISHED** | whether `PBDIGIT-65`/`69`'s original reported instance reproduces · runtime cross-election execution evidence · un-traced admin paths' snapshot use · the entitlement-pin tests' intent (not mine) |

## 8 · Remaining blockers for Election-Only readiness

1. **`EM-OPEN-021`** — the anomalous election 500s the live voting stack until ruled *(PO/ARB)*.
2. **`PBDIGIT-65`/`69` disposition** — this trace shows an election-scoped path today, but the tickets' reported instance was neither reproduced nor refuted; **their scope decision is Session 2's**.
3. **The 6 entitlement-pin rows (untracked, all ERROR)** — their author must state whether they are intentional RED pins.
4. **Q-P5-1** — revocation propagation, if the PO considers it material for live elections.

## 9 · Recommended NEXT VERIFICATION (not implementation)

**Reproduce-or-refute `PBDIGIT-65`/`69`'s original reported scenario** against the current code — the one gap that keeps their disposition evidence-poor. *(Bounded: one scenario, read-only, no repair.)*

---

**P5 COMPLETE · STOPPING**
**`EM-OPEN-021` remains unresolved · no semantic recommendation was made · the new-candidacy recovery path remains NOT ESTABLISHED · nothing repaired, greened, renamed or deleted · `SD-1` = 1,376 · `L3` = 240**

**Traceability:** `bootstrap/app.php:85-103` (aliases) · `route:list --json` (20/5/35 attachments) · `VerifyVoterSlug:69` · `EnsureElectionVoter` + `resolveElection()` · `User::isVoterInElection()` (`User.php:315`, TTL 300) · `VoteEligibility:37` (demo session branch) · `:62-79` (constitutional delegation) · `VoterSourceStrategy::fromElection/fromOrganisation` · consumers `ElectionVoterController` · `VoterImportController` · `VoterImportService` · P4 report §13 · P2/P3 §§11–12

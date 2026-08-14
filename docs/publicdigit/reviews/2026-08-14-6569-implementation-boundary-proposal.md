# PBDIGIT-65/69 — Implementation Boundary Proposal (Session 3, §5a gate) — **REVISED v2**

**Type:** Boundary presentation under grant Option A v5 (`f6bb5504`, disposition package §5a) · **Date:** 2026-08-14 · **Author:** Session 3
**⛔ No production, test, fixture, schema, Constitution, or cache change has been made. RED tests follow only after this boundary is approved.**

> *Revision v2 (2026-08-14, per PO boundary-review correction): (1) credential correspondence and entitlement modelled as TWO decisions, never combined; (2) T4/T5/T6 test model corrected — post-repair behaviour is AMBIENT-INVARIANCE, not "wrong ambient FALSE then corrected"; the P6 sequence is retained only as reproduction of the OLD defect; (3) cache identity now PROVEN from the call graph, not asserted; (4) the `=== 1` static-unreachability claim is WITHDRAWN — restored to Session 1's "reachability/correctness NOT ESTABLISHED"; (5) Q-TEN-2 (no credential → deny) kept explicitly separate from ambient-differs (no effect on entitlement). v1 reasoning stands where not corrected; corrections noted, never silently swapped.*

---

## 1 · The ruled model as TWO separate business decisions (correction 1)

```
Election ──determines──► required organisation
Voting credential ──supplies──► organisation to compare
Ambient session/tenant ──► NO role in entitlement
```

**Decision CC — credential correspondence (credential/session validity):**
*Is there a live voting credential, and does its organisation match the Election's?*
- No credential/context → **DENY** (Q-TEN-2; no derivation from the Election, no platform fallback).
- Credential org ≠ Election org → **DENY** (Q-TEN-1 credential table).
- Enforced at the **credential layer**: missing-slug throw (`VerifyVoterSlugConsistency:26-33`) + the election-derived comparison (`:58`). **Already conformant — REUSED, untouched, not duplicated.**

**Decision ENT — voting entitlement (the repair subject):**
*Given the credential layer has passed (or, at the pre-credential projection surface, given only voter + election): is this voter an admitted, active voter of THIS election?*
- A fact of **(voter, election)**. The election fixes its own organisation; the membership row is election-keyed.
- **Ambient session/tenant must not affect this answer in either direction.** Election A + Credential A + Ambient B → **TRUE**, exactly as Election A + Credential A + Ambient A → TRUE. There is no legitimate first FALSE later "corrected" to TRUE — a result that varies with ambient context is itself the defect.

The two decisions are never combined: mismatch-deny is CC's duty; ambient-invariance is ENT's property.

## 2 · Six-question reconciliation (updated where corrected)

**Q1 — Rule implemented:** grant §5a invariant v5 verbatim + §2a four-row table + §2c credential table + Q-TEN-2 row 5 + cache row 6. Expected-behaviour matrix under the two-decision model:

| Election org | Credential org | Ambient session | CC | ENT (admitted voter) | Voting |
|---|---|---|---|---|---|
| A | A | A | pass | TRUE | ✅ (subject to other rules) |
| A | A | **B** | pass | **TRUE** | ✅ — ambient has no role |
| A | B | A | **DENY** | (not reached) | ❌ |
| A | B | B | **DENY** | (not reached) | ❌ |
| A | — (none) | any | **DENY** (Q-TEN-2) | (not reached) | ❌ |

**Q2 — Concerns kept distinct:** credential validity (CC) · organisation correspondence (CC) · voter entitlement (ENT) · ambient infrastructure context (no role in ENT) · caching (identity of ENT's answer). Not "tenant handling."

**Q3 — Ownership (unchanged from v1):** the Election context owns voting-time entitlement recognition (Decision A CLOSED; `EM-GOV-001`, `EM-ENT-007`). This proposal expresses ownership by making ENT's evaluation election-derived **at the existing sites**; the structural home question is Option B, deferred. `User.php` is runtime placement, not authority — it is retained because the three existing call sites reach the predicate there and Option A is violations-scoped, not a relocation grant.

**Q4 — Smallest boundary (restated after corrections, correction 6):** **unchanged in substance — the election-derived entitlement predicate + its cache identity** — because every correction lands inside it: the two-decision separation assigns CC to already-conformant reused components (zero change), ambient-invariance is exactly what the election-derived predicate produces, and the cache identity is a property of that same predicate. Nothing in the corrections requires touching any additional component. INSIDE: (1) predicate internals (`User::isVoterInElection`) — membership lookup bypasses the ambient global scope and requires `organisation_id = <election's own organisation_id>` explicitly, signature and callers unchanged; (2) the entry-surface projection lookup (`ElectionVotingController.php:37-44`) — same election-derived semantics; (3) the predicate's cache identity (§5). OUTSIDE (reused): the whole credential layer; (untouched): `BelongsToTenant` and its 39 other consumers, middleware chain, `ElectionMembership`, all policies.

**Q5 — Violating mechanism (unchanged from v1):** ambient scope (`BelongsToTenant`: TenantContext → session → platform fallback, the fallback ruled non-conformant on this path) silently filters ENT's membership read at both sites [65]; a tenant-free key caches what is TODAY a tenant-dependent value, replaying it across contexts [69]. Correspondence duty is enforced upstream and conformant.

**Q6 — TDD slice (corrected test model, correction 2):**

**Credential-layer pins (Decision CC — all expected GREEN today; regression protection):**
| # | Scenario | Encodes |
|---|---|---|
| TC1 | credential org ≠ election org → denied | Q-TEN-1 table rows A/B, B/A |
| TC2 | no credential on a slug voting route → denied; no derivation; no platform fallback | Q-TEN-2 |

**Entitlement ambient-invariance (Decision ENT — the repair's acceptance):**
| # | Scenario | Expected now | Post-repair |
|---|---|---|---|
| TE1 | admitted voter · credential matches · ambient = election org · cold cache → predicate TRUE, gate passes | GREEN | GREEN |
| TE2 | admitted voter · credential matches · **ambient = OTHER org** → predicate TRUE, gate passes | 🔴 RED (the 65 defect) | GREEN |
| TE3 | **invariance sequence** — same voter, same election, same valid credential: evaluate under ambient B (cold) → ambient A (warm) → ambient B → ambient A again. **All four answers identical (TRUE).** Proves ambient is not part of the decision identity and no evaluation poisons any other | 🔴 RED (steps 1–2 reproduce P6: FALSE, then replayed FALSE — the 65+69 defects) | GREEN — same answer throughout |
| TE4 | entry-surface projection: admitted voter · ambient = OTHER org → `isEligible` TRUE | 🔴 RED (site 2) | GREEN |
| TE5 | voter admitted to election X only, evaluated for election Y → FALSE under every ambient value | GREEN (pin; ambient-invariant negative) | GREEN |

**P6's three-step sequence is retained ONLY as the reproduction of the OLD defect** (RED evidence inside TE3 steps 1–2 before the repair). It is **not** the desired behaviour: post-repair there is no wrong-ambient FALSE to correct — the answer is identical under every ambient value. *(v1's T4/T5/T6 framing — "wrong ambient → FALSE, correct ambient → recompute TRUE" — is withdrawn as the acceptance model; it would have preserved the dependency being eliminated.)*

## 3 · Cache identity — PROVEN from the call graph (correction 3)

The question first: *what is the identity of the cached business decision?* Traced, not assumed:

1. **The predicate's only inputs are (user, electionId).** `User::isVoterInElection(string $electionId)` — `$this` (the voter) + the election id. No other parameter exists.
2. **No caller passes the credential or any tenant value.** All three call sites measured: `EnsureElectionVoter:37` (election resolved from the credential-validated request attributes; passes `$election->id` only) · `EnsuresVoterMembership:35` · `VoteEligibility:103`. The credential is consumed at the credential layer (CC) and **is not an input to ENT anywhere in the call graph** — so `f(voter, election)` is not "too coarse": correspondence is established before ENT is reached, and where no credential exists (projection surface, legacy caller) CC's deny-duty is not ENT's to answer.
3. **The computed fact is election-keyed.** The membership row is unique on (user, election) (partial unique index `uq_user_election_active`); the required organisation is `election.organisation_id` — a fixed fact of the election, i.e. a function of the election id.
4. **Therefore the post-repair computation is a pure function of (voter, election).** Ambient context enters today's computation only through the global scope — the defect itself, not part of the identity.
5. **Consequently:** the existing key shape `(user, election)` matches the proven identity **once the computation is repaired**; clause 6 ("a result produced under any other context must never be reused") is satisfied by construction because no context-variant result can exist; TE3 is the executable proof. A **version-namespace fence** for pre-repair poisoned entries during the ≤300s transition is proposed as a deployment safeguard — a design detail inside the boundary, explicitly open to review (R1).

The PKS cache observation remains an observation; no architectural rule is created.

## 4 · `VerifyVoterSlugConsistency` treatment (corrections 4 & 5)

- `:58` is the ruled CC comparison — election-derived, credential-compared. **Reused, untouched.**
- `:26-33` missing-slug throw = Q-TEN-2's deny at the credential layer (**a CC validity rule — distinct from ambient-differs, which must not affect ENT**). Pinned by TC2.
- `:59-63` platform bypass (`organisation_id === 1`): **reachability/correctness NOT ESTABLISHED** — restored verbatim to Session 1's finding. *(v1's "statically unreachable in current data" is WITHDRAWN as overclaimed: observed UUID ids are evidence about observed data, not proof over every execution path.)* If reachable, its semantics would contradict the ruled credential table. **Recorded as a targeted-verification follow-up item; outside this boundary; not investigated further under this grant.**

## 5 · Exact files affected *(proposed boundary — NOT yet permission to edit)*

| File | Change class |
|---|---|
| `app/Models/User.php` (`isVoterInElection()` internals + its cache namespace) | production — predicate + cache fence |
| `app/Http/Controllers/ElectionVotingController.php:37-44` | production — projection lookup |
| new `tests/Feature/Election/` (+ unit) test file(s): TC1, TC2, TE1–TE5 | tests — RED first |

## 6 · Explicit out-of-scope list

`BelongsToTenant` trait and its other consumers · `VerifyVoterSlugConsistency` (incl. the `=== 1` branches — follow-up item) · `voter_count` · `has_voters` · `EM-VOT-003` · `EM-OPEN-021` · class B–F consumers · the 375 bypass sites · middleware chain/order · `ElectionMembership` model · all policies · any cache beyond the violating mechanism's own · Option B relocation.

## 7 · Risks / items for review

- **R1 (cache fence):** the version-namespace bump is a transition safeguard proposal, not a prescribed detail — approve, replace with targeted flush, or drop with accepted ≤300s exposure.
- **R2 (legacy caller):** `VoteEligibility:103` inherits the repaired ambient-free answer on a non-slug legacy route — same 65 correction, flagged for awareness.
- **R3 (projection surface):** the entry page precedes credential issuance; ENT there reports entitlement, exercise still traverses CC. Consistent with §2d.4's scope guard.

---

**STOP. Awaiting review of this corrected boundary. On approval: RED (TE2, TE3, TE4 + pins TC1/TC2/TE1/TE5) → minimal implementation → GREEN → regression vs frozen baseline → Session 1 independent verification. Session 3 does not self-certify.**

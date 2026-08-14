# Option A-2 — Boundary Re-Presentation for the Grant Decision

**Type:** Governance re-presentation (Session 2) · **Date:** 2026-08-15 · **Purpose:** re-present the boundary at which the Election implementation lane stopped, so the PO can grant or amend it. **No rediscovery of Election architecture; no new analysis.**
**⛔ NOT GRANTED. Nothing implemented. No test written. Election implementation lane has no active grant.**

---

## 1 · The exact invariant *(ruled; quoted, not re-derived)*

> **For a given voter and election, voting-time entitlement is evaluated against the organisation belonging to that election. The Election determines the required organisation; the voting-session/credential supplies the organisation compared against it. A matching context may permit voting; a non-matching context must not. No credential context ⇒ deny. A result produced under another context is never reused.**

Ownership is settled: **voting-time entitlement belongs to the Election bounded context** (Decision A, closed); ambient organisation context is forbidden **as the source of scope derivation**.

## 2 · The observed violation at `start()` *(measured, not inferred)*

Same voter · same election · same membership row · **only the session organisation changed**:

```
ambient B (wrong)   → redirect to elections.show · "You are not registered as a voter for this election."
ambient A (correct) → redirect to /v/{slug}/code/create · voter slug created · voting flow entered
```

The control run passed **every** downstream gate (membership → `has_voted` → lifecycle `canVote()` incl. the approved-candidate rule → IP → slug creation), so **the denial is isolated to the membership lookup** at `start():113-115` — `$user->electionMemberships()->where('election_id',…)->first()`, running under the ambient filter **with no bypass**: the same mechanism repaired at the two Option-A sites.

**Two facts that shape the boundary:** this is the **pre-credential** entitlement gate — it runs *before* any voter slug exists, so **credential correspondence cannot protect it**; and it queries the relation **directly**, so **the cached predicate is not involved** (no cache clause is needed).

## 3 · The authoritative decision boundary

The election's own organisation is authoritative for this evaluation. `start()`'s entitlement gate must derive the required organisation **from the election** and must not be filtered by ambient session/organisation context. **Denial must remain possible on its own merits** — a voter genuinely not admitted must still be denied; the repair must not widen eligibility.

## 4 · Why this repairs the violation, not a file

**Scope is the violation, not the path.** `start():113-115` is cited as *where the violation manifests*, exactly as the Option-A sites were — the grant does not instruct "edit this file." Any implementation satisfying the invariant at the pre-credential gate discharges it; any that leaves the ambient filter deciding entitlement does not, wherever the code sits. *(This is the same framing that made Option A verifiable: the acceptance test measures behaviour under two ambient contexts, not the shape of the diff.)*

## 5 · Failing tests first *(RED before any production edit)*

At minimum: **(a)** an admitted voter reaches the voting flow under the *wrong* ambient context — the measured B-run inverted from denial to entry; **(b)** the control case still works under the correct context (regression against a fix that merely flips a filter); **(c)** an overshoot guard — a voter *not* admitted to the election is still denied under **every** ambient context. Tests cite the ruled invariant and `PBDIGIT-65`.

## 6 · Acceptance evidence

The measured A/B is the acceptance scenario, run in the order that already exists (**B → A**, so no cache can favour the control): **both runs reach the voting flow**, the outcome no longer varies with the session organisation, the overshoot guard stays denied, and the existing entitlement suites — including Option A's `VotingEntitlementAmbientInvarianceTest` and its soft-deleted-election pin — remain green.

## 7 · Explicitly out of scope

`BelongsToTenant` and its other consumers · the cached predicate and cache keys (not involved on this path) · `voter_count` · `has_voters` · **EM-VOT-003's implementation** · **EM-OPEN-021** · the voter-source/sovereignty (Phase-3) work — *a separate concern, not this defect's family* · the 375 bypass sites · `workflow-state.php` and all platform work · any Election refactoring beyond the violation.

## 8 · The quarantined test stays quarantined

`tests/Feature/Election/ElectionOnlyEntitlementPinTest.php` remains **untracked, unmodified, undeleted, and not implementation or completion evidence**, by standing ruling. **It must not be adopted, repaired, extended, or run as part of this work merely because it sits in the same directory** — and the red it produces in a directory-wide run is *by ruling*, not a defect of this increment. Any implementer surprised by it is to leave it alone and report.

## 9 · EM-OPEN-021 and EM-VOT-003 are not pulled in

**EM-OPEN-021** is an undecided business meaning (what an election *is* when voting opens with no approved candidate) — this repair neither needs nor touches it. **EM-VOT-003** is an adopted rule awaiting its own expression home, boundary and grant — the voter-half check is **not** added here. Both remain separate work items; folding either into A-2 would destroy the authorization boundary.

## 10 · Independent-verification handoff

Session 3 does not self-certify. On GREEN: handoff to independent verification, which re-runs the A/B measurement itself, confirms the overshoot guard, confirms the entitlement suites and the frozen baseline, and attempts falsification — *can any ambient context still change the answer for an admitted voter?* Governance then reviews and brings the PO a closure decision.

---

**Boundary re-presented. Decision required: GRANT / AMEND / DECLINE.** *(Unchanged from the proposal of 2026-08-14; nothing added, nothing widened.)*

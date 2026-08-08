# PBDIGIT-66 — Establish Election-Only mode in the Election Constitution and in the lifecycle projection

**Type:** Governance / architecture decision (**not an implementation ticket**) · **Epic:** `PBDIGIT-EPIC-03` Election Management
**Created:** 2026-08-09 · **Raised by:** IERVP constitutional investigation
**Evidence:** [`../reviews/2026-08-09-election-constitution-voter-eligibility-traceability.md`](../reviews/2026-08-09-election-constitution-voter-eligibility-traceability.md) (report + two appendices)
**Status:** `OPEN — awaiting Product Owner / Architecture Board`. **Engineering must not resolve this.**

| | |
|---|---|
| **The situation** | **Election-Only mode works. It has no constitutional definition.** It was executed end to end at runtime — organisation flag → snapshot → import → voters enrolled → ballot — while the artifact that calls itself *"THE SINGLE SOURCE OF TRUTH"* for election rules **does not mention it at all** |
| **Why it matters** | Every rule governing who may vote in an Election-Only election currently exists **only as implementation**. There is nothing a non-engineer can be held to, nothing to test conformity against, and nothing that survives a rewrite of the code that happens to express it |
| **What this ticket is NOT** | **Not a request to remove Election-Only.** It is working business behaviour whose ownership is undocumented — **not legacy to be deleted.** That distinction is the whole point |

---

## What the investigation established (carry forward; do not re-derive)

1. **There is no Election Manifesto in this repository.** The phrase appears in two files, neither of which is one. The de-facto authority is a PHP class, `app/Domain/Election/Constitution/ElectionConstitution.php`, with **no version, status, steward, ratification record or amendment history**.
2. **Election-Only is absent from it.** `election_only` · `ElectionOnly` · `full_membership` · `voter_source` · `ElectionMembership` · `enroll` — **0 hits each.** "Eligibility" appears twice and both are `capacity_eligibility`, a **billing-plan** concept.
3. **The two modes converge completely.** `ElectionVotingController::start()` — the server-side enforcement boundary — reads **only `ElectionMembership`**. It never consults `voter_source_strategy`, `uses_full_membership`, `Member` or `User::isEligibleVoter()`.
4. **Therefore `ElectionMembership` *is* the entitlement model today**, carrying registration, eligibility and one-vote enforcement in a single row — **by implementation, not by declaration.**
5. **The lifecycle is a computed projection**, and it outranks the guarded transition system (`PBDIGIT-64`).

---

## Part 1 — Constitutional definition

**Decisions required. Each changes the product; none is engineering's to make.**

| # | Decision | Why engineering cannot settle it |
|---|---|---|
| **D-1** | **Is `ElectionMembership` the constitutional voter-entitlement authority**, or only a *registration* relationship with entitlement decided separately? | The code says one thing; only the business can say whether that is intended or incidental |
| **D-2** | **Are registration · eligibility · entitlement one decision or three?** | Today one row answers all three. Splitting them is a domain model change; keeping them fused is a domain commitment. **Both are defensible** |
| **D-3** | **Should Election-Only appear in the constitution at all**, given the modes converge immediately after import? | It may be a *provisioning* concern rather than a constitutional one — in which case the constitution should say so explicitly, not by silence |
| **D-4** | **Does importing a voter grant the right to vote**, or is a distinct activation/approval step intended? | The implementation grants it at import. Nothing records whether that was chosen |
| **D-5** | **Is the voter-source strategy an immutable constitutional snapshot?** It is copied from the organisation at election creation and never revisited — **is that binding, or incidental?** | Determines whether changing an organisation's mode may affect an election already under way |
| **D-6** | **May tenant context participate in constitutional predicates?** The constitution's own `has_voters` calls `withoutGlobalScopes()` on one branch and **not** the other | `PBDIGIT-65` proves this decides real outcomes |

## Part 2 — The projection

**The lifecycle projection is where a constitutional rule becomes a runtime fact, and it is currently where rules get lost.**

* [ ] **Decide whether the computed projection may return a state whose constitutional preconditions are unmet.** Today it can — `PBDIGIT-64`. **Until this is settled, adding a rule to the constitution does not guarantee it is enforced.**
* [ ] **Decide whether Election-Only must be visible in the projection at all** — e.g. does a consumer ever need to know *how* voters were sourced, or only *who* they are? The convergence finding suggests it may not.
* [ ] **Decide what the projection owes its consumers**: routing, `canVote`, the ballot and the officer UI all read it. **A projection that outranks the constitution is an authority, whether or not it was designed to be one.**

> **Constitutional text without a conforming projection is documentation, not governance.** This ticket deliberately covers both halves; resolving Part 1 alone would leave the rules unenforced.

---

## Acceptance criteria — stated as governance outcomes

* [ ] **A named, versioned, stewarded artifact states what Election-Only means**, who may create such an election, and how a person becomes entitled to vote in one — and it is discoverable by someone who cannot read PHP.
* [ ] **`D-1`…`D-6` are answered by their owners** and recorded where the next reader will find them.
* [ ] **The relationship between the constitution and the computed projection is stated**, including which wins when they disagree.
* [ ] **Conformity becomes measurable** — for each rule it is possible to say whether the implementation conforms, which today is *not answerable* because no standard exists.
* [ ] **No behaviour changes as a side effect of writing this down.** If the record and the running product disagree, that becomes a separate, evidenced finding — **not a silent correction.**

## Explicit non-goals

* **Not** removing or deprecating Election-Only.
* **Not** restoring `can_vote` / `is_voter` — they are absent from the schema and could not be authorities.
* **Not** implementing `PBDIGIT-64` or `PBDIGIT-65`; this ticket records why those fixes need the decisions above first.
* **Not** deciding whether `ElectionConstitution.php` should remain code or become a document — that is `D`-level input, listed below.

## Open question this ticket cannot itself answer

**Is `ElectionConstitution.php` intended to BE the constitution, or to implement one?** Everything above depends on the answer, and it is a governance question. If a Manifesto exists outside this repository, **that fact alone changes this ticket's scope**.

## Related

* **`PBDIGIT-64`** — the projection can reach `voting_active` with no candidate. **Part 2 exists because of this.**
* **`PBDIGIT-65`** — tenant context decides voter eligibility, and does so at the enforcement boundary. **`D-6` exists because of this.**
* **`PBDIGIT-49`** — voter eligibility has two homes; corroborated here from the constitutional side (`has_voters` ORs both authorities).
* **`PBDIGIT-35`** — retired global voter flags; confirmed absent from the schema while legacy console code still references them.
* **Next discovery (unstarted):** `codes.has_voted` vs `election_memberships.has_voted` — the remaining unmeasured duplication, on the anonymity-critical path.

---

**Traceability:** `app/Domain/Election/Constitution/ElectionConstitution.php:7-21` · `app/Application/Election/Services/ConstitutionalTransitionGuard.php:182-186` · `app/Application/Election/Services/ElectionLifecycleEngineImpl.php:99-107` · `app/Http/Controllers/ElectionVotingController.php:98-133` · `app/Services/VoterImportService.php::importElectionOnly` · runtime evidence: `iervp-experiment-b` / `election-2026-65b26848`, 2026-08-08/09.

# `ADR-002` amendment — **PROPOSAL** (not applied)

**Type:** ADR amendment proposal · **Date:** 2026-08-12 · **Programme:** IERVP (Session 2)
**Report:** **`D-ENT-1` DOMAIN DECISION RESOLVED · `Q-A0` = `F1` · `ADR-002` AMENDMENT PROPOSED · IMPLEMENTATION NOT AUTHORISED · SESSION-1 MATRIX UNTOUCHED**

**⛔ `ADR-002` IS NOT AMENDED BY THIS DOCUMENT.** It remains **Accepted** and unedited. Applying this proposal requires an explicit Product Owner act.
**No production code, test, fixture, schema, migration or Constitution change. Session 1's Master Matrix, `SD-4` and the 213 rows were not read as authority, not classified, not modified.**

**Basis:** [`2026-08-12-d-ent-1-two-mode-domain-decision-report.md`](2026-08-12-d-ent-1-two-mode-domain-decision-report.md) (`Q-A0` verification) · [`…-governance-decision-package.md`](2026-08-12-election-membership-governance-decision-package.md) · [`…-entitlement-lifecycle.md`](2026-08-12-election-membership-entitlement-lifecycle.md)

---

## 1 · The reconciliation, stated before the amendment text

**The ten points the Product Owner required, each with its status.**

| # | Statement | Status |
|---|---|---|
| 1 | **`F1` is the adopted business decision** — verified against three consistent repository recordings | **ADOPTED BUSINESS RULE** |
| 2 | **Model B is the chosen model** | **ADOPTED BUSINESS RULE** |
| 3 | **`Member` is an admission prerequisite only, and only in Full Membership mode** | **ADOPTED BUSINESS RULE** (`Q-A0`=`F1`) |
| 4 | **`ElectionMembership` is election-specific** | **ADOPTED BUSINESS RULE** |
| 5 | **The Election Chief may suspend the `ElectionMembership`** | **ADOPTED BUSINESS RULE** (authority currently enforced only in an application policy) |
| 6 | **Suspension is independent of organisation membership** | **ADOPTED BUSINESS RULE** · `SUPPORTED BY EVIDENCE` — no suspension/removal path branches on organisation membership or mode |
| 7 | **Suspension must affect voting exercisability** | **ADOPTED BUSINESS RULE**; **partially enforced** — see §3 |
| 8 | **Credential possession is NOT entitlement** | **ADOPTED BUSINESS RULE** · reinforced by evidence (§4) |
| 9 | **Credential issuance/revocation is a separate security concern** | **ADOPTED BUSINESS RULE** — a distinct concept (`F` in §2) |
| 10 | **Current implementation failures are FINDINGS, not approved repairs** | **Observed throughout.** §3 lists gaps; **none is authorised** |

---

## 2 · The seven concepts, never collapsed

**The commission's `A`–`G`, each with its owner and its lifetime. `ADR-002`'s single *"Eligible"* axis currently absorbs several of these; that is the substance of the amendment.**

| | Concept | Question it answers | Owner | Lifetime |
|---|---|---|---|---|
| **A** | **Admission entitlement** | *may this person be admitted?* | `VoterSourceStrategy` (mode-dependent) | evaluated **once**, at admission |
| **B** | **Durable `ElectionMembership`** | *has this person been admitted?* | Election | **persists** until an election-level termination rule ends it |
| **C** | **Election Chief suspension** | *has governance withdrawn exercise?* | Election governance | reversible, election-scoped |
| **D** | **Voting exercisability** | *may the entitlement be exercised now?* | derived | momentary |
| **E** | **Credential possession** | *does the voter hold a valid credential?* | credential | session-scoped |
| **F** | **Credential issuance / revocation** | *may a credential be granted or withdrawn?* | **security** | control over `E` |
| **G** | **Already-voted state** | *has the entitlement been consumed?* | Voting | irreversible (`ADR-T11`) |

**Two boundaries that the amendment must make explicit, because both are currently blurred:**

* **`A` is evaluated once; `B` is not re-derived from it.** That is `Q-A0`=`F1` in one sentence.
* **`E`/`F` are not `B`, and not `D`'s only input.** A credential is proof of possession, not a grant of entitlement.

---

## 3 · What is adopted vs what the system does — four-way separation

| Rule | **ADOPTED BUSINESS RULE** | **CURRENT IMPLEMENTATION BEHAVIOUR** | **IMPLEMENTATION GAP** | **PROPOSED ARCHITECTURAL CONSEQUENCE** |
|---|---|---|---|---|
| Organisation membership is an admission prerequisite only | ✅ `Q-A0`=`F1` | The live path **never consults** organisation membership | — **already consistent** | Retire or re-scope `scopeEligible()`, which implements the **opposite** (0 callers; would return 0/20) |
| `ElectionMembership` is the durable entitlement | ✅ | The row persists through suspension, consumption and removal | — consistent | Give the entitlement constitutional existence (**0** mentions today) |
| Chief may suspend | ✅ | Enforced via `manageVoters` (active chief/deputy) | Authority lives in an **application policy**, not the Constitution | Name a voter-level constitutional action, **distinct** from election-level `suspend` |
| Suspension affects **exercisability** | ✅ | Suspension writes `status='inactive'` — the **entitlement's own field** | 🔴 Mutates a business fact, contrary to *"freezes capabilities only"* | Represent exercisability separately (**`Q3`, still open**) |
| Suspension prevents voting | ✅ | **Ballot refused** — HTTP 403 at `EnsureElectionVoter` | Enforcement is **incidental** — nothing reads `suspension_status` | Express the rule; **do not vacate `status` before its replacement enforces** |
| Suspended ≠ already-voted | ✅ (implied by `B` vs `G`) | Both are `status='inactive'` | 🔴 **Indistinguishable at the enforcing predicate** | Separate the causes of non-exercisability |
| Credential ≠ entitlement | ✅ | Credential is a separate object (`VoterSlug`/`Code`) | — **consistent**, and it is why §4 matters | Keep them separate in the amendment's wording |
| Credential issuance is controlled | ✅ (point 9) | 🔴 **A suspended voter is issued a fresh credential; no path revokes an existing one** | 🔴 **`F` is unimplemented** | **`Q-E1`** — Product Owner decision, not a repair I may make |

**Every 🔴 above is a FINDING. None is authorised for repair.**

---

## 4 · The credential finding reinforces the model — it does not blur it

**The Product Owner's point, and the evidence agrees.**

Measured: a **confirmed-suspended** voter was **issued a fresh `VoterSlug`**, and **no suspension or removal path touches `VoterSlug` or `Code`.**

> **This does not mean "credential = entitlement". It means the opposite.** If credential possession *were* entitlement, issuing one to a suspended voter would have restored their right — and it did not: **the ballot was still refused.** So the two are demonstrably different things, and the system enforces the entitlement rule while failing the credential-control rule.

**Correctly classified, the finding is a gap in `F` (credential issuance/revocation — a security concern), not evidence about `B` (entitlement) or `D` (exercisability).**

---

## 5 · Proposed amendment text

**Preserve unchanged:** the Verified ≠ Eligible ≠ Authorized orthogonality · each axis's responsible context · the *"changes are localized to their domain"* principle · **Example 1** (verification expiry — the Verified axis) · **Example 2** (fees unpaid → *"cannot vote in Election **B**"*, which concerns admission to a **future** election and is therefore **compatible**) · the rejection of a single conflated `can_vote` boolean · the relationship to `ADR-001`/`ADR-003`.

### 5.1 Add axis 0 — **Entitled**

> **0. Entitled**
> **Definition:** the participant has been **admitted** to a specific election, and that admission has not been terminated by a defined election-level rule.
> **Responsibility:** Election context. **Scope:** election-specific.
> **Enabled by:** an admission decision, evaluated **once**.
> **Lifespan:** persists from admission until an election-level termination rule ends it.
> **Explicitly: it is NOT re-derived from organisation membership.**
> **Does NOT imply:** exercisability, credential possession, verification, or permission.

### 5.2 Amend the three incompatible statements

| Location | Current | Proposed |
|---|---|---|
| `:161` | `eligibility_status (computed from membership at check time)` | `entitlement` — established at admission, election-specific, durable until terminated · `eligibility_status` — computed at check time **from the entitlement and the election's own rules**, **not** from organisation membership |
| `:78` | *"Check: Active membership? Paid fees? Full member type? Enrolled? Window open?"* | **Split into two checks** — see 5.3 |
| `:71` | *"Enabled By: Process-specific rules (membership status, fees, timing, scope)"* | *"Enabled by: the entitlement, plus the election's own rules (governance state, window, scope)"* |

### 5.3 Separate admission-time from check-time

> **Admission-time** — *may this person be admitted?* Organisation membership status, fees and membership type apply **here, and only here** (Full Membership mode). Election-Only mode requires no organisation `Member`.
> **Check-time** — *may this entitled person act now?* Entitlement exists · not suspended · election window open · scope correct.

### 5.4 Amended authorization formula

**Decisions only. No storage, class, field, entity or mechanism named — `Q3` remains open.**

```
CanCastVote(actor, election) =
      Entitled(actor, election)         // admitted; not terminated            [NEW AXIS]
   && Exercisable(actor, election)      // not suspended; election rules permit now
   && Verified(actor)                   // unchanged
   && Permission(actor, action, scope)  // unchanged
```

**Credential possession is deliberately absent from this formula.** It is an **authentication** step preceding the act, not a term in the entitlement decision — and it is **voter-refreshable**, so a condition the subject can satisfy at will cannot be an entitlement condition.

### 5.5 Vocabulary disambiguation

> *Revocation*, in `ADR-001`, `ADR-003` and the trust-domain vocabulary, means **withdrawal of identity-trust attestation** — and it explicitly **does not block future voting**. **Termination of an `ElectionMembership` entitlement is a different act on a different object and must not be called revocation.**

### 5.6 Record staleness (not an amendment of the decision)

> `ADR-002`'s *"Current Implementation Evidence"* section is **stale**: it cites `Member voting_rights` and `EloquentVoterEligibilityQueryService` as the eligibility implementation, whereas the live gate is `User::isVoterInElection()`.

### 5.7 Explicitly out of scope

How exercisability is represented or stored (**`Q3`**) · any tactical class, entity, repository, field or event · credential-control semantics (**`Q-E1`**) · termination semantics (**`BR-1.1`/`1.2`**).

---

## 6 · Product Owner decisions still required

| # | Decision |
|---|---|
| **Apply?** | Whether to apply §5 to `ADR-002` — **the amendment is not applied** |
| **`Q3`** | The exercisability model — **the amendment deliberately leaves it open** |
| **`Q-E1`** | Should suspension prevent credential issuance and invalidate an existing credential? *(Today: neither.)* |
| **`Q-E2`** | Must suspended be distinguishable from already-voted **at the enforcing gate**? |
| **`BR-1.1`/`1.2`** | Termination semantics — Option B recommended, unratified |
| **`BR-1.5`/`1.6`** | Mandatory reason; unconditional audit |
| **`BR-1.13`** | Which suspension path is intended |
| **`Q-D1`** | Disposition of `scopeEligible()` — it implements the opposite of `F1` |

## 7 · Boundaries

* **`ADR-002` not edited.** Constitution not edited. No production code, test, fixture, schema or migration touched. Nothing implemented. No dormant code activated. **`Q3` not decided.**
* **Session 1 untouched** — Master Matrix, `SD-1`/`SD-2`/`SD-4`, the 1,376 baseline and the 213 rows **not read as authority, not classified, not modified.** No row classified anywhere in this document.
* **`Q-A0` resolution rests on repository convention**, not independent attestation — stated plainly in the two-mode report §"`Q-A0` RESOLVED".
* **`PBDIGIT-69`** cited nowhere.
* **Not mine:** `app/Domain/Election/Models/ElectionUser.php` remains modified in the working tree by another author; **left untouched and uncommitted.**

**Traceability:** `docs/adr/ADR-002-verified-eligible-authorized.md:71,78,161,198-204,208-227` · `docs/adr/ADR-001-trust-attestation-domain.md:40,73-85` · `docs/adr/ADR-003-governance-driven-revocation.md:62-71` · `docs/adr/ADR-T-LOG-Tactical-Implementation.md:18` (`ADR-T11`) · `docs/architecture/trust-domain/UBIQUITOUS_LANGUAGE.md:74-110,222-270` · `app/Domain/Election/Enum/VoterSourceStrategy.php:10-60` · `app/Models/ElectionMembership.php:126-146,157-171,202-224` · `app/Models/User.php:315-328` · `app/Http/Middleware/EnsureElectionVoter.php:37-54` · `app/Http/Controllers/ElectionVotingController.php:41-44,111,156-191` · `app/Policies/ElectionPolicy.php:91-98` · `app/Domain/Election/Constitution/ElectionConstitution.php:126-146` · verification commits `fc53f78a` · `3530a234` · `0fafdd50`.

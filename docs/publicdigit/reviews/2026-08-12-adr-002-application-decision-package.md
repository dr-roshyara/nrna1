# `ADR-002` application decision package

**Type:** Decision package for the Product Owner · **Date:** 2026-08-12 · **Programme:** IERVP (Session 2)
**Purpose:** verify the proposed amendment against the six criteria, then present the decision **APPLY / DO NOT APPLY** for the Product Owner to take.
**⛔ I do not take the decision. `ADR-002` remains Accepted and unedited.**

**Mode:** governance only. **No production code, test, fixture, schema, migration, Constitution or ADR change. `Q3`, `Q-E1`, `Q-E2` not decided. No tactical class, entity or repository designed. Session 1's Master Matrix, `SD-4` and the 213 rows not read as authority, not classified, not modified.**
**Subject:** [`2026-08-12-adr-002-amendment-proposal.md`](2026-08-12-adr-002-amendment-proposal.md)

---

## 1 · Verification against the six criteria

| # | Criterion | Verdict |
|---|---|---|
| **1** | Faithfully represents the already-adopted business decisions | ⚠️ **YES, with `V-1` and `V-2` to settle first** |
| **2** | Does **not** prematurely decide `Q3` | ✅ **PASS** — the formula names *decisions*; `Exercisable` is a decision name with no storage, field or mechanism attached |
| **3** | Does **not** prescribe tactical implementation | ✅ **PASS** — no class, entity, repository, field, column, event or service named anywhere in §5 |
| **4** | Preserves the eight-way distinction | ⚠️ **PARTIAL — `V-3`** |
| **5** | Does **not** use *"revocation"* ambiguously across Trust and Election | ⚠️ **The amendment passes; the ADOPTED WORDING does not — `V-2`** |
| **6** | Distinguishes adopted rule / current behaviour / gap / proposed consequence | ✅ **PASS** — §3 is a four-column table; every 🔴 is labelled a finding |

### `V-1` — the `Entitled` axis forward-references a rule that does not exist

§5.1 defines the axis as persisting *"until an election-level termination rule ends it."* **No such rule has been ratified** — `BR-1.1`/`BR-1.2` carry a recommendation (Option B) that is still open.

**This is faithful to the adopted wording**, because the Product Owner's own corrected formulation says *"remains associated with the election **unless a defined election-level revocation/removal rule terminates it**"* — the same forward reference. **So the amendment inherits the gap rather than introducing it.**

> **Consequence to decide, not a defect:** applying the amendment would commit `ADR-002` to a termination rule **that does not yet exist**. Two legitimate courses: **apply now** and let `BR-1.1` fill the reference later, or **ratify `BR-1.1` first** and apply once. **`D-APPLY-1`.**

### `V-2` — the adopted wording itself contains the ambiguity §5.5 forbids

§5.5 proposes that *"termination of an `ElectionMembership` must not be called revocation"*, because `ADR-001`/`ADR-003` and the trust vocabulary reserve *Revocation* for **identity-trust withdrawal** — which *"does not block future voting."*

🔴 **But the adopted wording says *"revocation/removal rule."*** **So the ratified sentence uses the reserved term for the election-side object.**

> **This must be settled before or during application, because otherwise `ADR-002` would forbid a term that the decision it encodes uses.** Options: **(a)** restate the adopted wording as *"termination/removal"* (a wording change to an adopted rule — a Product Owner act); **(b)** apply the amendment and record the adopted phrase as legacy wording superseded on this point; **(c)** drop §5.5 and accept two meanings of *revocation*, **not recommended**. **`D-APPLY-2`.**

### `V-3` — `ElectionMembership` (record) vs `Entitled` (decision) are not explicitly separated

The Product Owner's eight-way list treats **`ElectionMembership`** and **`Entitled`** as distinct. The amendment's §2 defines `B` as *"durable `ElectionMembership`"* and the new axis as *"Entitled"* **without stating the relationship**, which risks reading them as synonyms.

**Proposed clarification** — a wording addition only, no new concept:

> **`ElectionMembership` is the record of admission. `Entitled` is the decision that reads it.** The record is a fact; the axis is an evaluation of that fact against the termination rule. **One is stored, the other is decided.**

**Mapping of the eight:**

| Product Owner's term | In the amendment | Status |
|---|---|---|
| `Member` | organisation relationship, §2 | ✅ distinct |
| `ElectionMembership` | `B` — the record | ⚠️ needs `V-3` wording |
| `Entitled` | axis 0, §5.1 | ⚠️ needs `V-3` wording |
| `Exercisable` | `D` + formula term | ✅ distinct |
| `Verified` | preserved axis | ✅ distinct |
| `Credential` | `E` possession / `F` control | ✅ distinct, and **excluded from the formula** |
| `Permission` | preserved axis | ✅ distinct |
| `AlreadyVoted` | `G` | ✅ distinct |

---

## 2 · What applying the amendment would and would not do

| Would | Would **not** |
|---|---|
| Add **Entitled** as a fourth orthogonal axis, owned by the Election context | Decide **how** exercisability is represented (`Q3`) |
| Record that entitlement is **not re-derived from organisation membership** | Authorise any code, schema, migration or test change |
| Separate **admission-time** from **check-time** evaluation | Resolve `BR-1.1`/`BR-1.2` termination semantics |
| Exclude credential possession from the entitlement decision | Decide `Q-E1` credential control under suspension |
| Disambiguate *revocation* (subject to `V-2`) | Give voter-level governance **constitutional** existence — that is a Constitution matter, untouched |
| Note that `ADR-002`'s implementation-evidence section is stale | Retire `scopeEligible()` (`Q-D1`) |

---

## 3 · Implementation gaps — **FINDINGS ONLY, no repair authorised**

| # | Finding | Class |
|---|---|---|
| **G-a** | Suspension writes `status='inactive'` — mutating the entitlement's own field, contrary to *"freezes capabilities only"* | IMPLEMENTATION GAP |
| **G-b** | **Suspended and already-voted are indistinguishable at the enforcing predicate** | IMPLEMENTATION GAP |
| **G-c** | **A suspended voter is issued a fresh credential**; no path revokes an existing one | IMPLEMENTATION GAP (credential control, `F`) |
| **G-d** | `scopeEligible()` implements the **opposite** of `F1` — 0 callers, would return 0/20 | CONTRADICTION |
| **G-e** | Enforcement of suspension is **incidental**; nothing reads `suspension_status` | IMPLEMENTATION GAP |
| **G-f** | *"Permanent"* removal is not enforced — `approve()` has no guard against `removed` | IMPLEMENTATION GAP (`MECHANISM NOT ESTABLISHED` — read, not executed) |
| **G-g** | 4 of 6 voter-governance acts write **no audit**; none reaches the election's own audit trail | IMPLEMENTATION GAP |
| **G-h** | **No test asserts that a suspended voter cannot vote** | COVERAGE GAP |

**None of these is authorised for repair. `G-a` in particular must not be "fixed" before its replacement enforces — `status='inactive'` is currently the only thing preventing a suspended voter from voting.**

---

## 4 · `invited` — investigated independently (first-half item 4)

**Question: is `invited` an intended domain state, obsolete documentation, an application workflow state, or an implementation gap?**

| Layer | Supports `invited`? | Evidence |
|---|---|---|
| **Schema** | ✅ | `enum('invited','active','inactive','removed')`, default `active` |
| **UI** | ✅ | status label *"Invited"*, dedicated blue status pill + dot styling, and an action commented **`<!-- Approve (invited → active) -->`** |
| **Tests** | ✅ | `makeMembership('invited')` used repeatedly; one test asserts `'invited'` persists |
| **Documentation** | ✅ | *"The voter appears in the table with `invited` status"*; approval then required |
| **Production writer** | ❌ **none** | **no `=> 'invited'` anywhere in `app/`** |

> **ANSWER: `invited` is an INTENDED workflow state and an IMPLEMENTATION GAP — not obsolete documentation.** Four layers expect it; **only production never writes it.** **So the approval gate that the guide, the UI and the tests all assume does not exist on any production path**: assignment lands directly in `active`.
>
> ⚠️ **This corrects my earlier characterisation** (*"documented but unimplemented"*, and before that *"a vestigial enum value"*). **The UI and the test estate expect it too**, which makes it a genuine gap rather than stale prose. **`BR-1.12` remains a Product Owner decision** — whether the approval gate should exist. **I recommend nothing beyond that.**

---

## 5 · 🟡 THE DECISION — for the Product Owner

> ### `D-APPLY` — Apply the proposed `ADR-002` amendment?
>
> **☐ APPLY ADR-002 AMENDMENT**
> **☐ DO NOT APPLY**
>
> **Engineering does not take this decision.** The verification above establishes only that the amendment is faithful, non-tactical, and does not pre-empt `Q3`.

**Two sub-decisions ride on it and should be answered in the same act:**

| # | Sub-decision |
|---|---|
| **`D-APPLY-1`** | Apply **now**, accepting that the `Entitled` axis references a termination rule not yet ratified — or **ratify `BR-1.1` first** and apply once? |
| **`D-APPLY-2`** | 🔴 How to resolve *"revocation/removal"* in the adopted wording, given the amendment forbids that term for the election object: **(a)** restate the adopted wording as *"termination/removal"*, **(b)** apply and record the adopted phrase as superseded on this point, or **(c)** accept two meanings *(not recommended)*? |

**If `APPLY` is chosen, the act is:** amend `ADR-002` per §5 of the proposal, with `V-3`'s clarifying sentence added and `D-APPLY-2`'s resolution reflected. **Nothing else follows automatically — implementation remains unauthorised.**

---

## 6 · Remaining open decisions — preserved as open

| # | Question | Kind |
|---|---|---|
| **`Q3`** | How is exercisability represented? | Architecture (ARB) |
| **`Q-E1`** | Should suspension prevent credential issuance and invalidate an existing credential? | Business + security |
| **`Q-E2`** | Must suspended be distinguishable from already-voted at the enforcing gate? | Business |
| **`BR-1.1`/`1.2`** | Termination semantics — Option B recommended, unratified | Business |
| **`BR-1.5`/`1.6`** | Mandatory reason; unconditional audit | Business |
| **`BR-1.13`** | Which suspension path is intended (two exist) | Business |
| **`Q-D1`** | Disposition of `scopeEligible()` | Architecture → engineering |
| **`BR-1.12`** | Is the `invited` approval gate real? *(§4 — now evidenced as a gap)* | Business |
| **Officer Guide** | Section-level recognition, per the `D` assessment — **not promoted** | Governance |

**None of these is an implementation task.** The order is fixed: **business decision → domain meaning → authority → application capability → authorization → interface → persistence → tests.**

---

## 7 · Boundaries

* **`ADR-002` not edited.** Constitution not edited. Officer Guide **not modified, not promoted, no knowledge card added.** No production code, test, fixture, schema or migration touched. Nothing implemented. No dormant code activated.
* **`Q3`, `Q-E1`, `Q-E2` not decided.** No tactical class, entity, repository or event designed.
* **Session 1 untouched** — Master Matrix, `SD-1`/`SD-2`/`SD-4`, the 1,376 baseline and the 213 rows **not read as authority, not classified, not modified.** Handover is a separate artifact.
* **`PBDIGIT-69`** cited nowhere.
* **Not mine:** `app/Domain/Election/Models/ElectionUser.php` remains modified in the working tree by another author — **left untouched and uncommitted.**

**Traceability:** `docs/adr/ADR-002-verified-eligible-authorized.md:71,78,161,198-204,208-227` · `docs/adr/ADR-001-trust-attestation-domain.md:40,73-85` · `docs/adr/ADR-003-governance-driven-revocation.md:62-71` · `docs/architecture/trust-domain/UBIQUITOUS_LANGUAGE.md:222-270` · `database/migrations/2026_03_17_213212_create_election_memberships_table.php:25` · `resources/js/Pages/Elections/Voters/Index.vue:301,465,985-986` · `tests/Feature/Election/ElectionVoterManagementTest.php:49,65,81,92,191` · `app/Http/Controllers/ElectionVoterController.php:98,140,171-190,196-216,222-241` · `app/Models/ElectionMembership.php:126-146,164-171,202-224` · `app/Models/User.php:315-328` · verification commits `fc53f78a` · `3530a234` · `0fafdd50` · measured 2026-08-12: **no production writer of `invited` in `app/`**.

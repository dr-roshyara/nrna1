# ARB decision package — `ADR-002` `V-1` · `V-2` · `V-3`

**Type:** ARB decision package · **Date:** 2026-08-12 · **Programme:** IERVP (Session 2, governance support)
**Purpose:** make the three outstanding amendment issues **decision-ready**. **Engineering does not decide any of them.**
**⛔ `ADR-002` remains Accepted and UNAMENDED.** No production code, test, fixture, migration, schema or Constitution change. **Session 1's Master Matrix and its potentially-affected population were not read, used, classified or modified.**

**Subject:** [`amendment proposal`](2026-08-12-adr-002-amendment-proposal.md) · [`application decision package`](2026-08-12-adr-002-application-decision-package.md)

---

## `V-1` — Termination / removal semantics

**Exact question**
> **Does the `Entitled` axis's lifespan clause — *"persists until an election-level termination rule ends it"* — get applied before that termination rule exists, or after it is ratified?**

| | |
|---|---|
| **Business authority** | The adopted wording itself contains the forward reference: *"remains associated with the election **unless a defined election-level revocation/removal rule terminates it**."* **So the reference is faithful, not invented by engineering.** |
| **Observed implementation** | `remove()` sets `status='removed'` **and retains the row** with `removed_at` / `removed_reason` / `removed_by`. **No delete**, though `SoftDeletes` is available and deliberately unused. `destroy()` takes a row lock commented as guarding a **live vote in flight**. |
| **Existing authoritative sources** | `ADR-003` — consequences of a withdrawal are **governance decisions**, and revocation does **not** auto-invalidate past votes. `ADR-T11` — **no voter↔vote linkage**, so post-vote termination **cannot reach the ballot**. Membership context (`D`) — `TERMINATED` is terminal and irreversible, with **mandatory actor and reason**. |
| **Contradiction** | The Officer Guide calls removal *"permanent"*, yet **`approve()` has no guard against a `removed` row** — so permanence rests on a hidden button. *(`MECHANISM NOT ESTABLISHED` — read, not executed.)* |
| **Decision required** | **`D-APPLY-1`:** apply the amendment **now**, accepting the forward reference · **or** ratify `BR-1.1`/`BR-1.2` **first** and apply once. |
| **Recommendation, evidence permitting** | **Ratify `BR-1.1` first.** Applying an axis whose lifespan cites a non-existent rule leaves `ADR-002` incomplete on its own terms, and the termination recommendation (Option B) is already drafted and evidenced. **This is a sequencing preference only — both courses are legitimate.** |

## `V-2` — *Revocation* vs *removal / suspension* terminology

**Exact question**
> **`ADR-001`, `ADR-003` and the trust vocabulary reserve *Revocation* for withdrawal of identity-trust attestation — and state explicitly that it does NOT block future voting. The adopted `D-ENT-1` wording nonetheless says *"revocation/removal rule"* for the ELECTION object. Which term governs the election-side act?**

| | |
|---|---|
| **Business authority** | Adopted wording uses **"revocation/removal"**. |
| **Existing authoritative sources** | **`UBIQUITOUS_LANGUAGE`:** *Revocation* = *"an officer's decision to withdraw previously attested trust in an **identity**"*, and *"does **NOT** … block future voting (eligibility might persist)."* **`ADR-001`** §Revocation Semantics · **`ADR-003`** governs revocation of **verification**. |
| **Observed implementation** | The election side uses **`remove()`** and `status='removed'`. **No election-side code uses the word *revoke*.** |
| **Contradiction** | 🔴 **The amendment's §5.5 would forbid calling entitlement termination *revocation* — while the adopted sentence it encodes does exactly that.** Applying as drafted makes `ADR-002` forbid a word the decision uses. |
| **Decision required** | **`D-APPLY-2`:** **(a)** restate the adopted wording as *"termination/removal"* *(a wording change to an adopted rule — a Product Owner act)* · **(b)** apply, and record the adopted phrase as **superseded on this point only** · **(c)** accept two meanings of *revocation*. |
| **Recommendation, evidence permitting** | **(a)**, with **(b)** as an acceptable fallback. **Not (c):** two meanings for one term across Trust and Election would be the fourth vocabulary collision, and the two meanings are **opposites** on the only question that matters — whether it blocks voting. |
| **Note for the wider vocabulary decision** | The election-side verbs should be settled inside the **Phase 4 governance-language review** that `VoterSourceStrategy` already designates (its case names are `@deprecated`, *"transitional bridge vocabulary"*). **One naming decision, not two** (ES-005.4). |

## `V-3` — `ElectionMembership` (record) vs `Entitled` (decision)

**Exact question**
> **Are `ElectionMembership` and `Entitled` the same thing under different names, or a stored fact and a decision that reads it?**

| | |
|---|---|
| **Business authority** | The Product Owner's own list treats them as **two of eight distinct concepts.** |
| **Observed implementation** | Only the **record** exists. There is no evaluation step named anywhere — the live gate reads `role` and `status` directly off the row. **So today the record IS the decision, which is precisely the conflation.** |
| **Existing authoritative sources** | `ADR-002`'s structure — each axis is a **decision**, with a responsible context; a stored row is not an axis. The Membership context's stated principle: *"the aggregate reports operational facts; the policy interprets those facts into governance decisions — **never use aggregate methods for authorization**."* **That is the same distinction, already accepted one context over.** |
| **Contradiction** | None. This is an **under-specification**, not a conflict. |
| **Decision required** | Accept or reject the clarifying sentence: **"`ElectionMembership` is the record of admission. `Entitled` is the decision that reads it — the record is a fact; the axis is an evaluation of that fact against the termination rule."** |
| **Recommendation, evidence permitting** | **Accept.** It adds no concept, it mirrors an already-accepted principle in the Membership context, and **without it the amendment can be read as renaming a table column into an ADR axis** — which would defeat the amendment's purpose. |

---

## Decision summary for ARB

| ID | Question | Owner | Engineering recommendation |
|---|---|---|---|
| **`D-APPLY`** | Apply the `ADR-002` amendment? | **ARB / PO** | *(not recommended either way — see the three below)* |
| **`D-APPLY-1`** | Apply now, or ratify `BR-1.1` first? | **ARB / PO** | **Ratify `BR-1.1` first** *(sequencing preference)* |
| **`D-APPLY-2`** | Resolve *revocation* vs *termination/removal* | **ARB / PO** | **(a) restate as "termination/removal"**; **not (c)** |
| **`V-3`** | Accept the record-vs-decision clarification? | **ARB** | **Accept** |

**Nothing follows automatically from any of these. Implementation remains unauthorised, and `Q3` remains open.**

**Traceability:** `docs/adr/ADR-002-verified-eligible-authorized.md:71,78,161,198-204` · `docs/adr/ADR-001-trust-attestation-domain.md:40,73-85` · `docs/adr/ADR-003-governance-driven-revocation.md:62-71` · `docs/adr/ADR-T-LOG-Tactical-Implementation.md:18` · `docs/architecture/trust-domain/UBIQUITOUS_LANGUAGE.md:222-270` · `app/Contexts/Membership/Domain/Voting/VotingEligibilityPolicy.php:20-40` · `app/Contexts/Membership/Domain/Membership/MembershipLineage.php:364-395` · `app/Models/ElectionMembership.php:32,173-197` · `app/Http/Controllers/ElectionVoterController.php:171-190,196-216` · `app/Models/User.php:315-328` · `app/Domain/Election/Enum/VoterSourceStrategy.php:10-60`.

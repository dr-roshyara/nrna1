# Officer Guide — governance-source analysis (folder-wide)

**Type:** Governance-source analysis · **Date:** 2026-08-12 · **Programme:** IERVP (Session 2, governance support)
**Scope:** all seven files in `docs/election_management/` — **completing the folder-level gap I recorded in the earlier single-file assessment.**
**⛔ The guide was NOT modified, NOT promoted, and no knowledge card was added. No section-level authority applied — only proposed for ARB decision.**
**Predecessor:** [`governance weight assessment`](2026-08-12-officer-guide-governance-weight-assessment.md) *(single file, `04-voter-list.md`)*

**Classification scheme used:** `AUTHORITATIVE` · `SUPPORTED` · `OPERATIONAL GUIDANCE` · `UNSUPPORTED` · `CONTRADICTED` · `UNKNOWN`.

---

## 1 · The decisive structural fact

> 🔑 **The guide set cites NO authority anywhere.** Across all seven files there is **not one reference** to an ADR, the Constitution, a constitutional article, or an engineering standard. Every cross-reference points to a **sibling guide page**.

**Consequence for the governance question:** no statement in the guide set can be `AUTHORITATIVE` **on its own terms** — none claims to derive from an authority, so none can be traced to one. **A statement can at most be `SUPPORTED`** (agrees with an authority that exists elsewhere). **OBSERVED FACT.**

Combined with the earlier findings — no knowledge card · not a registered documentation root · cited as authoritative by nothing · one commit, no steward, no change control — **the guide set has no formal governance status.**

## 2 · Classification of business-rule statements, folder-wide

**Statements that make a business claim. Pure screen mechanics are omitted as `OPERATIONAL GUIDANCE` in bulk (§3).**

### 2.1 Voter governance — `04-voter-list.md`

| Statement | Class | Basis |
|---|---|---|
| *"Only voters with Active status can submit a ballot"* | **SUPPORTED** | agrees with the live enforcing predicate; **no constitutional article states it** |
| *"Available to Chief and Deputy Election Officers only"* | **SUPPORTED** | agrees with `manageVoters` (`role IN chief,deputy`, active) and with test expectations |
| Suspension → `inactive`; *"a suspended voter cannot vote, even if voting is still open"* | **SUPPORTED** | agrees with measured runtime behaviour; **the rule itself is unratified** |
| *"Suspended voters show an Approve button… to restore their eligibility"* | **SUPPORTED** | the guide is **more specific than any authority** — `restore` exists nowhere else |
| Removal is *"permanent"*; *"cannot be restored **through the voter list UI**"* | **UNSUPPORTED** | permanence is asserted on a **UI fact**; `approve()` has no `removed` guard |
| Assignment yields `invited`, approval then required | 🔴 **CONTRADICTED** | **no production path writes `invited`** |
| *"Only organisation members can be assigned as voters"* | **SUPPORTED** *(ambiguous)* | the FK enforces a **role linkage**; the `Member` aggregate has **0 rows** |
| *"Suspend any ineligible members"* / *"click Suspend to undo an approval"* | 🔴 **CONTRADICTED** | treats suspension as an **eligibility/undo device**, conflicting with adopted `A-2` (existence ≠ exercisability) |

### 2.2 Officer roles and appointment — `01-your-role.md`, `02-accepting-invitation.md`

| Statement | Class | Basis |
|---|---|---|
| *"your status is **Pending**. You **cannot access any election pages** until you accept"* | **SUPPORTED** | an **officer** appointment lifecycle — distinct from the voter `invited` state; consistent with `ElectionOfficer.status` being checked by `manageVoters` |
| *"You must accept again to become active"* (re-appointment) | **SUPPORTED** | — |
| *"Only one chief is typically appointed per election"* | **UNKNOWN** | hedged with *"typically"*; **no authority states a cardinality rule** |
| Commissioner is *"an observer role… cannot change anything"* / *"no write access"* | **SUPPORTED** | matches `manageVoters` excluding commissioner, and a test asserting **403** |
| *"Only the correct account holder can accept — the system checks that the logged-in user matches the invited user"* | **SUPPORTED** | security behaviour, described accurately |

### 2.3 Election conduct — `03-management-dashboard.md`, `06-faq-troubleshooting.md`

| Statement | Class | Basis |
|---|---|---|
| *"**Publishing results is a Chief-only** action. The Deputy can do everything else including closing voting"* | **SUPPORTED** | matches the Constitution: `publish_results` → `['chief']`; `close_voting` → `['chief','deputy']` |
| *"Voters with **'Invited' or 'Suspended'** status cannot vote"* | **SUPPORTED** on suspended · 🔴 **CONTRADICTED** on invited | a **second** statement treating `invited` as real |
| *"Removed voters cannot be restored through the voter list UI. Contact your **system administrator** to manually **restore the membership record**"* | **UNSUPPORTED** | 🔑 **A second, more explicit statement of administrative reversal — and *"restore the membership record"* implies the record PERSISTS**, which is consistent with `BR-1.1` Option B |
| *"Members must be explicitly assigned… by the Chief or Deputy… **or by the organisation administrator in bulk**"* | **UNKNOWN** | introduces a **third assignment actor** that no authority names |
| 🔴 *"**You can publish and unpublish as many times as needed**"* (with an *"Unpublish Results"* button documented) | 🔴 **CONTRADICTED** | **The Constitution has NO `unpublish` action.** The only action from `results_published` is `archive`. The sole implementation found is an **artisan console command** (`election:unpublish-results`) — **not a constitutional transition** |
| *"Publishing results does not delete any votes"* | **SUPPORTED** | consistent with `ADR-T11` and `ADR-003` |

> 🔴 **The publish/unpublish claim is the most consequential finding outside the voter domain.** The guide tells officers that a **published result may be withdrawn and re-published at will**, while the Constitution models publication as a one-way transition toward `archive`. **This is a results-integrity question, and it is outside `D-ENT-1`'s scope — raised here, deliberately not investigated further.**

## 3 · Everything else — `OPERATIONAL GUIDANCE`

Badge colours · status pills · button labels · click sequences · success-message strings · ASCII screen mock-ups · the CSV filename and column list · statistics-card definitions · "Common Mistakes" keyed to buttons · navigation links. **This is the dominant register of all seven files, and none of it should ever carry business authority.**

## 4 · Should the guide have governance weight?

**Recommendation unchanged from the single-file assessment, now confirmed folder-wide:**

> **`D` — MIXED AUTHORITY; SECTION-LEVEL REVIEW REQUIRED.** **Do not promote. Do not grant folder-level authority.**

**Reasons strengthened by this analysis:**
1. **It cites no authority at all** — so it cannot be a derivation of ratified rules today; it can only become one.
2. **It contains three contradictions**, one of them (`unpublish`) outside the voter domain entirely.
3. **Its dominant register is UI mechanics**, which must never become business rules.
4. **But it is the only written statement** of several genuinely useful rules — restoration, administrative reversal, officer-pending state — and it is **more specific than the Constitution on all of them**.

### Proposed for ARB decision — not applied

| | |
|---|---|
| **Recognise** | Only the statements classed **SUPPORTED** above, and only as the **operational expression** of rules ratified elsewhere |
| **Do not recognise** | Everything classed `OPERATIONAL GUIDANCE`, `UNSUPPORTED`, `UNKNOWN`, or `CONTRADICTED` |
| **Resolve first** | 🔴 `invited` *(contradicted, twice)* · 🔴 suspension-as-undo *(conflicts with `A-2`)* · 🔴 publish/unpublish *(no constitutional action)* · removal permanence *(unenforced)* |
| **Prerequisites for governability** | a **knowledge card**, a **registered documentation root**, a named **steward**, and **change control** — none exists |
| **Standing rule** | **The guide must derive authority, never hold it:** *Constitution → ADRs → ratified rules → Officer Guide → screen instructions* |

## 5 · Boundaries

* **Guide not modified, not promoted; no knowledge card added; no section-level authority applied.** Constitution and ADRs untouched. No production code, test, fixture, schema or migration change.
* **The publish/unpublish contradiction is recorded, not investigated.** It is a results-integrity matter outside this commission and outside `D-ENT-1`.
* **Not executed:** `approve()`-on-a-`removed`-row; the artisan unpublish command. Both read only.
* **Session 1 untouched** — Master Matrix and its potentially-affected population not read, used, classified or modified.

**Traceability:** `docs/election_management/01-your-role.md:20,39,43,70,95` · `02-accepting-invitation.md:3` · `03-management-dashboard.md:97,132,148` · `04-voter-list.md:3,5,63,69,90,101,105,111,119,130,133,177,190` · `05-viewboard.md:5,94` · `06-faq-troubleshooting.md:52,58-62,82,96,138` · `app/Domain/Election/Constitution/ElectionConstitution.php:94-123` · `app/Console/Commands/UnpublishResults.php:8-50` · `app/Policies/ElectionPolicy.php:91-98` · `app/Models/User.php:315-328` · measured 2026-08-12: **zero authority citations across all seven files** · **no production writer of `invited`** · no constitutional `unpublish` action.

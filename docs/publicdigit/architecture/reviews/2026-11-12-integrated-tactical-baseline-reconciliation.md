# Integrated tactical baseline (Geography, Membership, Organisation, Governance, Forum, Newsletter): reconciliation — PROPOSED

| | |
|---|---|
| **Status** | **REVIEWED by the Principal, 2026-11-13:** D-223 (option 1), the M-TDD-01 status note and D-224 (Geography prioritised) decided. Was: PROPOSED for the Principal's review. Design review only: no code, no decision changed. **Nothing here is accepted until the Principal says so** |
| **Commission** | Principal DDD Architect instruction "independent review and reconciliation of the integrated tactical baseline" (2026-11-12) |
| **Read (current files, not summaries)** | M-TDD-01 **revision 4** (accepted D-210; file `2026-11-12-m-tdd-01-rev2-…-integration.md`, its name kept from revision 2); Governance completion **revision 5** (accepted D-208; file `2026-11-12-governance-completion-tactical-design.md`); ledger-clock amendment rev 2 (D-209); release-1 blueprint rev 4 (D-215, D-216); Reference Geography v3.11 (D-93); decision record D-73 … D-222; slice 1 code (PBDIGIT-343, accepted) |
| **Labels** | **RATIFIED / ACCEPTED** · **CONSISTENT** · **CORRECTION REQUIRED** · **IMPLEMENTATION GATE** · **PRINCIPAL DECISION** · **LATER RELEASE** |

## 0. Disagreement first: the commission reviewed superseded revisions

The commission names "M-TDD-01 rev 2" and "Governance completion revision 3". **Both were superseded and accepted later the same day:**
- M-TDD-01 revision 4 (D-210);
- Governance completion revision 5 (D-208);
- with D-206 (correction authority) and D-209 (clock) in between.

**So two of its five findings are already settled** (§2 F3, and the substance of F1). **Two point at real defects of the current text** (F1's stale sentence, F4), and **one is a real ambiguity** (F2).

## 1. Business explanation

Each context answers one question, and **none copies another's answer:**

| Context | Its question | Never |
|---|---|---|
| **Identity** | who is this person, and who is acting? | membership, authority |
| **Platform** | which organisation's database may this operation use? | authority, content (D-222 mechanism) |
| **Membership** | is this person a member, in force, suspended or ended, and since when, as known at τ? | belonging, authority |
| **Organisation** | where does the person belong, in which capacity, as known at τ? | membership status, from address or map |
| **Governance** | is this actor authorised for this act, under which rule, as known at τ? | authority from account bindings (ISO-7) |
| **Deliberation (Forum)** | may this person read here; may they post here? | its own member list or hierarchy |
| **Communication (Newsletter)** | who receives this issue? | its own member list |
| **Reference Geography** | what are the official units and their history? | belonging, membership, entitlement |

**One time for a combined answer:** the consumer fixes **one knowledge time τ** for its operation (one per send, one per forum check). **Every source answers completely at τ or refuses**, from its own boundary:
- Membership `closedThrough` (C2);
- Organisation `organisationClosedThrough` (K-3, K-4, T-2);
- Governance `governanceClosedThrough` (T-1, AuthorityCheck, K-7).

A refusal is never "no" (blueprint A-03, GQ-3, T2-5).

## 2. The five findings of the commission

| # | Finding | Current text | Label | Action |
|---|---|---|---|---|
| **F1** | Clock: M-TDD-01 §5.5 shows `max(appClock, last+1µs, closedThrough+1µs)` | **D-209 replaced that mechanism** with one database clock, refusal on regression and no adjustment. **Slice 1 implemented and proved it** (PBDIGIT-343, accepted). But M-TDD-01 §5.5 line 137 and its open-items table **still describe the old clock as accepted and D-209 as "proposed"** | **RATIFIED** (D-209) + **CORRECTION REQUIRED** (stale text) | append a status note to M-TDD-01 (§4 below). **No redesign** |
| **F2** | Late execution of a scheduled resignation vs D-205 | see §3 | **PRINCIPAL DECISION** (a narrow clarification of D-205's wording) | D-223, proposed |
| **F3** | Authority for Correct / Void / Annul "not yet a contract" | **Decided:** D-206 allocates it (Correct and Void: an office with recording authority, with evidence, a Void reported and challengeable; Annul: the competent body's new decision or a tribunal remedy; silent rules → `Undetermined`). **D-208 ratified the Governance act kinds:** `correct-membership-record`, `void-membership-record`, the two Annul routes. Not built | **RATIFIED / ACCEPTED** + **IMPLEMENTATION GATE** (GC-1 … GC-17 unexecuted) | until Governance's AuthorityCheck is built (slice 4), these operations are refused, by construction |
| **F4** | "No new Principal decision is required" is too optimistic | the sentence (M-TDD-01 open items) predates F2 and lists D-208 / D-209 as open | **CORRECTION REQUIRED** | the status note supersedes it |
| **F5** | Newsletter legal basis | D-210 (4): before launch [LQ] | **CONSISTENT** (a launch gate, not a design gate) | none |

## 3. F2 in detail: D-205 and a late scheduled resignation

**Exact wording.** D-205:
- **(1)** "No Membership fact may take effect after the time it is recorded (`effectiveAt` ≤ the recording date): nothing is future-dated in the ledger."
- **(2)** "Exactly one case may take effect after its decision date: `End(resignation)` **recorded on its effective date** from a scheduled resignation declaration (D-204). Its `decidedAt` stays the declaration date."

D-204: "on the effective date Membership records `End(resignation)` dated that day."

**M-TDD-01 §5.2:** "Late execution (the job runs after the date; now possible only before `closedThrough` passes the date): effective date = the scheduled date; recording = later; backdated within the existing invariants."

**Three dates:** declaration `decidedAt` (e.g. 1 March) < `effectiveAt` (31 March) ≤ recording (31 March, or 1 April if the job ran late).

**The question.** Is "recorded on its effective date" a **condition** of the single exception, or a **description** of the normal case?
- **As a condition (strict):** a job that runs on 1 April **cannot record** the End (31 March > `decidedAt`, and not recorded on 31 March). Then:
  - `closedThrough` stays held (D-209: due resignations are recorded before it passes their date);
  - **the organisation's membership answers stay refused until someone acts**;
  - **and no lawful action exists**, because only this exception could record it. The member's resignation is stuck.
- **As a description (narrow clarification):** the exception covers an End recorded **on or after** its effective date from a scheduled declaration. A late recording is ordinary backdating, already allowed by D-5(b) and D-205 (1):
  - **effective 31 March** (the member's chosen date);
  - **decided 1 March** (truthful);
  - **recorded 1 April** (truthful: when PublicDigit learned it);
  - and because `closedThrough` could not pass 31 March before the recording, **no answer "as known at τ" was ever given that ignored it.**

**What the narrow reading does not broaden:**
- still **only** `End(resignation)`;
- still **only** from a scheduled declaration;
- still never recorded **before** its effective date (D-205 (1) unchanged);
- other backdating rules unchanged.

**Proposed D-223 (for the Principal):** "D-205 (2) reads: `End(resignation)` from a scheduled resignation declaration, recorded **on or after** its effective date (never before). A recording after that date is backdating under D-5(b), with `decidedAt` = the declaration date and `recordedAt` the actual recording time."
- **Option 1 (recommended):** adopt D-223.
- **Option 2:** keep the strict reading, and decide what happens to a resignation whose job missed its date. Some other lawful route is then needed, or the organisation's answers stay blocked indefinitely.

## 4. Correction required: status note for M-TDD-01 (append-only; to append on the Principal's word)

> **Status note (2026-11-12, after D-209, D-210, D-222):**
> - §5.5 and the open-items rows on the ledger clock are **superseded by D-209**: one database clock (`clock_timestamp()`), refusal and retry on regression, no adjustment. **Implemented and accepted in slice 1** (PBDIGIT-343). The formula at §5.5 line 137 is historical.
> - The open-items rows for D-208 and D-209 are closed (accepted the same day).
> - "No new Principal decision is required" is superseded: **D-223 (D-205 wording) is open.**
> - Platform's mechanism is D-222.

## 5. Contracts K-1 … K-9: verification

| # | Contract | Direction | Label | Note |
|---|---|---|---|---|
| K-1 | C2 `CoverageKnownAt` | Membership → Organisation | **CONSISTENT**, **built** (slice 1) | refusals exactly `BOUNDARY_NOT_CLOSED` · `SOURCE_NOT_LEDGER` · `CROSS_ORGANISATION` |
| K-2 | C1 events | Membership → Organisation | **CONSISTENT**, **built** | delivered by the per-organisation relay in slice 2 (§3.4) |
| K-3 | `EffectiveMembersAsAt` | Organisation internal | **CONSISTENT**; **IMPLEMENTATION GATE** (slice 3/5) | refused, never partial |
| K-4 | `MembersOfScopeAsAt`, `BelongingWithAncestorsAsAt` | Organisation → Communication, Deliberation | **CONSISTENT** | **Forum and Newsletter never call Membership** (blueprint architecture test) |
| K-5 | AuthorityCheck | Governance → all | **ACCEPTED** (D-208), not built (slice 4) | `Undetermined ≠ No`; boundary per GQ-1 |
| K-6 | Exclusion push | Governance → Membership | **ACCEPTED** | idempotent per Resolution part |
| K-7 | `ParticipationRuleAsAt` | Governance → Communication, Deliberation | **ACCEPTED**; D-161 labelled platform default (D-168) | refused beyond `governanceClosedThrough` (GQ-2) |
| K-8 | `ActingRef` | Identity → all | **ACCEPTED** (I-TDD-02, D-200), not built | — |
| K-9 | Geography → Organisation | — | **LATER RELEASE** (D-147) | — |

**Ownership rules of the commission, checked:**

| Rule | Where | Label |
|---|---|---|
| Membership must not read Organisation | K-1 and K-2 flow outward only; slice 1 architecture tests | **CONSISTENT** (built) |
| Forum and Newsletter keep no membership status or member list | K-4 only; "never reference Membership" test | **CONSISTENT** |
| Organisation never infers belonging from address or geography | D-79, D-80; §4a | **RATIFIED** |
| Governance never infers authority from account bindings | ISO-7 | **RATIFIED** |
| No context reads another's tables | ISO-5; per-context ports; D-222 one connection per organisation | **CONSISTENT** |

**Underspecified or impossible as written:** **none found** beyond F1, F2 and F4.

## 6. Newsletter and Forum: verification

| Requirement | Newsletter (Communication) | Forum (Deliberation) |
|---|---|---|
| one τ per operation | **CONSISTENT** (one τ per send) | **CONSISTENT** (one τ per check) |
| authoritative audience / standing via Organisation (K-4) | **CONSISTENT** | **CONSISTENT** |
| participation rule; platform default labelled (K-7, D-161, D-168) | **CONSISTENT** | **CONSISTENT** |
| per-series opt-out (D-151) | **CONSISTENT** | — |
| organisation-owned contact data; never the login address (T4-1) | **CONSISTENT** | — |
| no partial send: complete or refused | **CONSISTENT** | — |
| read ≠ post (D-153); posting needs a moderator in office (D-150) | — | **CONSISTENT** |
| unit containment via Organisation | — | **CONSISTENT** |
| fail closed on unavailable or incomplete answers | **CONSISTENT** | **CONSISTENT** |
| no local copy of status or hierarchy | **CONSISTENT** | **CONSISTENT** |
| legal basis | **IMPLEMENTATION GATE / launch** [LQ] | — |
| committee forums (confidential) | — | **LATER RELEASE** (D-56, D-152) |
| formal notices (convocations) | **LATER RELEASE** | — |

No capability or rule is added here.

## 7. Reference Geography: standalone vs integration

| Item | Needed for standalone implementation (central context) | Needed only for Organisation integration |
|---|---|---|
| PO-1 … PO-5, PO-7 (publication lock, single write path, recorded predicates, gap-free positions, one snapshot, one clock) | **yes**: its own proofs | — |
| PO-6 (refuse on regression, never adjust) | **yes** | — |
| PO-8 (every D-73 participant meets the same contract) | — | **yes.** **Membership's part is met** (D-209, slice 1); Organisation's and Governance's boundaries are designed (blueprint), not built |
| **Identity: one platform-level person reference, equality, merge notices, reversals** (v3.11 §8) | **yes**: the approval roster (`excluded(r)`, four-eyes stewardship) needs it. **I-TDD-02 does not provide it** (M-TDD-01 §9; baseline U-2) | — |
| L-4c, Q-2, Japanese classification, naming, address ownership | deferred by D-93 | deferred |

**Finding G-1 (IMPLEMENTATION GATE, new): standalone Geography cannot be built until Identity specifies the platform-level person reference** for stewards (U-2). That needs an Identity design step. It is **not** a business decision, and not a reason to reopen D-93 or D-147.

**D-147 is not reopened:** implementing the central context needs no Organisation integration; using it in Organisation stays later. **Where it sits in the build order remains Decision G.**

## 8. Readiness verdict (separately)

| Area | Verdict | Blocking items |
|---|---|---|
| **Reference Geography (standalone)** | **NOT READY** | G-1 (Identity platform-level reference); then its own PO-1 … PO-7 as test obligations; Decision G (order) |
| **Membership (lifecycle)** | **DESIGN READY after D-223; build after slices 2–4** | D-223; AuthorityCheck (slice 4); Organisation belonging (slice 3); MI-1 … MI-14 |
| **Forum** | **DESIGN READY; build after slices 3–5** | K-4, K-5, K-7 built; D-150 moderator designation via Governance |
| **Newsletter** | **DESIGN READY; build after slices 3–5; launch after [LQ] and [EXT]** | K-4, K-7, T-2 built; legal basis; a mail provider with idempotency; monitoring |

## 9. Decisions for the Principal

1. **D-223:** the D-205 wording for late execution. Option 1 recommended (§3).
2. **May the M-TDD-01 status note (§4) be appended?** It is consistency only.
3. **Decision G (still open):** Geography's place in the order. With G-1, standalone Geography first needs an Identity step. **My recommendation is unchanged:** after the Newsletter. If you want it earlier, the Identity platform-level reference becomes the next design task.

**Traceability:** D-73, D-93, D-147, D-150 … D-153, D-156, D-158, D-161, D-162, D-164, D-168, D-193, D-200, D-203 … D-210, D-215, D-216, D-222 · M-TDD-01 rev 4 · Governance completion rev 5 · blueprint rev 4 · Reference Geography v3.11 · PBDIGIT-343.

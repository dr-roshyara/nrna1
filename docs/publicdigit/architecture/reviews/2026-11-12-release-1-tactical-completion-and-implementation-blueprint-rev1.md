# Release 1: tactical completion and implementation blueprint (Membership, Organisation, Newsletter, Forum; Geography boundary)

| | |
|---|---|
| **Status** | **PROPOSED.** The contract additions in §3 need acceptance; the rest is blueprint and evidence. **It changes no accepted decision or design, and authorises no implementation** (each slice needs an EP-01-approved plan). Written by Claude, not independent |
| **Commission** | Principal, 2026-11-12: "Release 1 Integrated Tactical Design and Implementation Blueprint" |
| **Builds on (accepted)** | **M-TDD-01 rev 4 (D-210)**, the integrated tactical design, with its contracts K-1 … K-9, scenarios 1–22 and MI-1 … MI-14; Governance completion rev 5 (D-208); ledger-clock amendment rev 2 (D-209); D-213; strategic baseline v1.1 rev 3 (proposed) |
| **Code inspected** | `app/Contexts/Membership/{Domain,Application,Infrastructure}/Organisational/*`; `PostgresMemberRepository.php`; `InterimRoleAuthority.php`; `app/Services/NewsletterService.php`; `app/Http/Controllers/Membership/OrganisationNewsletterController.php`; `config/database.php`; the `app/Contexts` folder list |

**Labels:**
- **[F]** fact, with file and line;
- **[AD]** accepted decision;
- **[P]** proposal (needs acceptance);
- **[TD]** technical design.

## 0. Disagreements with the commission (stated first)

1. **The review examined a superseded text.** [F]
   - The reviewed file, titled "revision 2" but with a status saying "revision 1", is `…-docrev1.md` (`37f66b4b…`), kept for history.
   - **The accepted design is M-TDD-01 rev 4 (D-210).** It already contains what the review asks for:
     - Annul / Void / Correct authority (D-206, D-208 `correct-membership-record`, `void-membership-record`, Annul routes 1–2);
     - the scheduled resignation design (D-204, D-205);
     - scenarios 1–22.
   - **So a new integrated tactical design would duplicate D-210.** This document is a **delta**:
     - the three contract gaps;
     - the code conformance gaps;
     - the implementation blueprint;
     - the proof matrix.
2. **PO-8 is resolved at design level; the code is not conformant.** [F]
   - **D-209** (ARB, accepted) replaced `max(appClock, …)` with the organisation database's `clock_timestamp()`, refusal on regression and no adjustment.
   - Only one passage in M-TDD-01 rev 4 (§5.5, line 137) still calls it an open gate. Its appended D-210 status note supersedes it ("dependencies are now accepted: D-209").
   - **But the committed kernel repository still uses the old mechanism** (§2, G-1). Here the review's concern is right.
3. **"Identify the missing act kinds for Annul, Void and Correct":** they are not missing (D-206, D-208 §2.5). **The interim authority adapter is the problem** (§2, G-3).
4. **The review's dependency order lacks a prerequisite:**
   - **routing to the organisation's own database (D-164) does not exist in the code** (§2, G-5);
   - every release-1 context stores its data there.

   It comes first (§5).

## 1. Strategic consistency

- **Consistent with the strategic baseline v1.1 rev 3** and D-173 … D-213.
- **No boundary change is needed.**
- **The four areas:**

| Area | Strategic state | Tactical state | Code state |
|---|---|---|---|
| **Membership** | ratified | accepted (D-177, D-208, D-209, D-210) | **kernel partly built** (Admit, End, Annul, Correct, Void); **not conformant to D-209**; no Renew / Suspend / Restore, scheduled resignation, C1 or C2 |
| **Organisation** | accepted §49–§51 (D-156) | accepted; **two contract gaps** (§3 T-2) | **no context code** |
| **Newsletter** (Communication) | accepted; audience clarified (D-213) | accepted; **one contract gap** (§3 T-2) | **a legacy feature that breaks the accepted rules** (G-6) |
| **Forum** (Deliberation) | accepted | accepted; **one contract gap** (§3 T-1) | **no context code** |
| **Reference Geography** | ratified (D-93) | ratified v3.11 | `app/Contexts/Geography` and a `pgsql_geo` connection exist; **later release** (D-147) |

## 2. Code against the accepted design (conformance gaps)

| # | Gap | Evidence | Required (accepted) |
|---|---|---|---|
| **G-1** | **The ledger clock is the application clock, adjusted per membership:** `recordedAt = max(($this->clock)(), last + 1)` | [F] `PostgresMemberRepository.php` constructor line 51 (`\Closure $clock`); append, line ≈220 (`max(...)` with `last + 1`, per `membership_id`) | **D-209:**<br>- lock the **organisation's** clock row, then the version compare-and-set;<br>- `t = clock_timestamp()`;<br>- refuse when `t ≤ closed_through` or `t < last_recorded_at`;<br>- `recordedAt = t`;<br>- **no adjustment;** a per-organisation `last_recorded_at` and `closed_through` |
| G-2 | no `closedThrough`, C1 outbox or C2 | [F] no `CoverageKnownAt`, `MembershipCoverageResolved` or `closedThrough` anywhere in `app/` or `tests/` | D-162 §4–§6 as amended by D-209 |
| **G-3** | **interim authority:** Admit mirrors the legacy "owner / admin" role; End is denied for everyone | [F] `InterimRoleAuthority.php` header; **not bound in the container** | **Governance AuthorityCheck** (D-157, D-208). **An admin role is not authority** (PO-ORG-MEM-01 A; D-1a, D-167). **It must not be bound for any release-1 organisation** |
| G-4 | Renew / Suspend / Restore, scheduled resignation and the `ownAct` resignation path are not built | [F] no such facts in `Domain/Organisational/` | D-163, D-177, D-203 … D-205 |
| **G-5** | **no per-organisation database routing** | [F] `config/database.php`: `pgsql`, `pgsql_audit`, `pgsql_geo` only | **D-164, D-178, D-193:** central plus one database per organisation; a routing locator; ISO-1 … ISO-11 |
| **G-6** | **the legacy newsletter:**<br>- reads the legacy `members` table (`status = 'active'`);<br>- sends to **`users.email`** (the login address);<br>- uses one global unsubscribe (`members.newsletter_unsubscribed_at`);<br>- caches the audience (`Cache::remember`);<br>- offers a fee-overdue audience;<br>- relies on admin-role authorisation | [F] `NewsletterService.php` lines 108, 119–146; `OrganisationNewsletterController.php` lines 35, 51–55 | **D-160:** a ledger organisation is never served from the legacy store.<br>**D-101 / INV-ID-3:** contact from the organisation's profile, never the login address.<br>**D-151:** per-series opt-out.<br>**D-158:** caching must never outlive a belonging change.<br>**D-58:** an editor tenure, not an admin role.<br>**→ The new Communication context is built beside it; the legacy feature stays only for legacy-store organisations until their cutover** |
| G-7 | M-TDD-01 rev 4 §5.5 describes the D-209 gate as open | [F] line 137; superseded by the status note | documentation only; corrected when the document is next revised |

## 3. Proposed contract additions (closing U-5, U-9 and D-213) [P]

**Each is the smallest contract that answers the question. The owner keeps the fact; no rule is added.**

### T-1 (U-5): is the forum open? (Governance → Deliberation)

```
OfficeCoverageAsAt(organisationId, officeKind: moderator, scopeRef: forumRef, τ)
  → { covered: bool, tenureRefs: [...], decisionLogClosedThrough }
  | refused (τ beyond Governance's DecisionLog boundary; source unavailable)
```

- **Owner:** Governance. It answers from functional-office tenures (D-157 §2 item 3; D-150).
- **Deliberation:**
  - `covered = false` → read-only;
  - **refused → deny posting** (D-210 §8 "any answer unavailable → deny").
- **Reading is unaffected** (D-150: read-only, not closed).
- **The same contract with `officeKind: editor` lets Communication state why a series cannot be sent.** The send still needs the actor's own AuthorityCheck `send-newsletter(series)`.
- **Does not replace** AuthorityCheck `moderate(forum)` for the moderator's own act.

### T-2 (U-9 and D-213): non-member belonging, independent of Membership (Organisation → Communication, Deliberation)

```
CapacityBelongingAsAt(organisationId, scopeUnit, capacities[], τ)        // audience (D-213)
CapacityBelongingOfPersonAsAt(organisationId, personRef, capacities[], τ) // forum read (U-9)
  → { items: [{personRef, capacity, unitRef, basis}], organisationClosedThrough }
  | refused (τ beyond Organisation's own §49.8 boundary)
```

- **`capacities` excludes `Member`.** These queries **never consult Membership.**
  - Non-member belonging "counts by its own validity" (§50 B-2 applies only to membership-dependent belonging; MI-14).
  - **This is not a partial K-4 answer.** It is a complete answer to a different question.
- **K-4 is unchanged:** membership-dependent, complete or refused.
- **Newsletter (D-213):**
  - audience = K-4 members, filtered by the participation rule, **∪** `CapacityBelongingAsAt` for the capacities the series decision names;
  - **both at the same τ; if either is refused → no send** (D-73, "never partial").
- **Forum read during a Membership refusal:**
  - Deliberation may read the person's non-member capacities through T-2;
  - **it never infers "not a member";**
  - **posting still needs K-4 standing** (or the organisation's rule), so it is denied.

### T-3 (U-8, not release 1): pushing an applied determination

- Extends Governance completion C-1b to the route-2 pattern already used for Annul.
- Belongs to the D-212 change request.

## 4. Context-by-context tactical model (release 1; delta to D-210 only)

| Context | Aggregates (accepted) | New in this blueprint |
|---|---|---|
| Membership | `Member` (one membership, its periods and fact log; the kernel); **`ScheduledResignation`** (declaration outside the ledger, D-204); **`OrganisationLedgerClock`** (one row per organisation: `last_recorded_at`, `closed_through`, D-209) | none |
| Organisation | `Unit` (tree, successors); `PersonBelonging` (§50.3); person profile and contacts (D-101) | T-2 queries |
| Governance | `Organ`, `Position` with tenures (ordinary and functional), `Resolution`, `DecisionLog`; AuthorityCheck | T-1 query |
| Communication | `Series`, `Issue`, `Dispatch` (audience snapshot with `ruleSource` and basis, delivery records), `OptOut` | consumes T-1, T-2 |
| Deliberation | `Forum` (open / read-only / archived), `Post`, `ModerationAct` | consumes T-1, T-2 |

## 5. Laravel implementation blueprint [TD]

**Layering (CLAUDE.md rules 1–5):**
- **Domain:** pure PHP, final classes.
- **Application:** commands, queries, DTOs, ports.
- **Infrastructure:** Eloquent or query builder, outbox, adapters.
- **HTTP:** controllers and Inertia (`router.post`).

**One Laravel application, several bounded contexts; no microservices.**

| Concern | Design |
|---|---|
| **Folders** | `app/Contexts/{Membership, Organisation, Governance, Communication, Deliberation, Platform}/{Domain, Application, Infrastructure}`. **New:** `Organisation`, `Communication`, `Deliberation`, `Platform` |
| **Contracts between contexts** | the **consumer** declares a port (`Application/Ports/…`); an Infrastructure adapter calls the **provider's** published query or application service, in-process. **No consumer imports another context's Domain or Infrastructure.** Architecture tests enforce the direction rules (D-210 §4; `tests/Architecture`) |
| **Organisation database routing (G-5)** | Platform's locator resolves `organisationId → connection`. **Every repository takes the organisation connection explicitly** (as the kernel already takes `Connection`). No default connection for organisation data; refuse if unresolved (ISO tests) |
| **Transactions** | **one per context write;** the outbox row in the same transaction. **Lock order in Membership: organisation clock row, then the membership version** (D-162 correction note, D-209) |
| **Outbox / inbox** | per organisation database; idempotent consumers keyed on (source, id, `seq`); C1 to Organisation; Governance pushes (K-6); admission → placement (D-8 in D-210 §6) |
| **Clock** | `clock_timestamp()` read **inside** the locked transaction. **Remove the injected application clock from record time.** An application clock may remain only for business dates supplied by the user |
| **Scheduled resignations** | executed **inside** the `closedThrough` step (D-209). **A scheduler (`schedule:run`, every minute) calls that same step per organisation,** so executions happen even when nobody reads; it is the same step, not a second path |
| **Authority** | Governance AuthorityCheck behind each consumer's `AuthorityCheckPort` (the Membership port exists). **`InterimRoleAuthority` stays unbound for release-1 organisations** |
| **Mail** | `Communication/Infrastructure/Mail` adapter (anti-corruption layer); the provider idempotency key = dispatch id + recipient (D-210 §7) |
| **Legacy newsletter** | **untouched;** routed only for legacy-store organisations (D-160). Ledger organisations reach only the new Communication routes |
| **Frontend** | Inertia pages per context; forms use `router.post`; **the UI never computes eligibility**, it shows the context's answer |
| **Audit** | AuthorityCheck answers stored as fact provenance; `ruleSource` on access decisions and snapshots (D-167) |

## 6. Vertical slices for one developer (order by dependency)

**Each slice: EP-01 plan → RED → GREEN → its proofs → acceptance.**

| # | Slice | Contains | Proofs (all NOT EXECUTED) |
|---|---|---|---|
| **S0** | Platform minimum | organisation registry and locator; per-organisation connection; isolation | ISO-1 … ISO-11, INV-ACCESS-1, Platform register (53, as needed) |
| **S1** | **Ledger clock conformance (G-1, G-2)** | the clock row; D-209 append and `closedThrough` step; C2; C1 outbox | **LC-1 … LC-6, A1 … A3** |
| S2 | Membership lifecycle | Renew / Suspend / Restore; resignation (`ownAct`) and scheduled resignation; Correct / Void / Annul through Governance | D-177 tests; MI-1 … MI-4, MI-10 |
| S3 | Governance for release 1 | AuthorityCheck act kinds; tenures; `ParticipationRuleAsAt`; **T-1** | GC-1 … GC-17; T-1 tests |
| S4 | Organisation | units; belonging; placement (admission → placement exactly once); K-3, K-4; **T-2** | MI-12, MI-14; T-2 tests |
| **S5** | **Forum** | read, post, open, moderation | MI-7, MI-14; scenarios below |
| **S6** | **Newsletter** | series, issue, audience (K-4 ∪ T-2), opt-out, snapshot, outbox, mail adapter | **MI-11** |
| later | Reference Geography | standalone map; then Organisation coverage references | PO-1 … PO-8; Identity person-reference contract (U-2) |

**Forum before Newsletter:** the forum needs no provider ([EXT] C-03) and no legal gate (D-151 [LQ]); it exercises the same Organisation and Governance contracts first.

## 7. Scenarios (owner of each fact · contracts)

**Scenarios 1–22 of D-210 §13 stand.** This table maps the commission's scenarios onto them and adds the new ones.

| Scenario | Facts and owners | Contracts | Ref |
|---|---|---|---|
| admit into a ward | Admit (Membership); placement (Organisation) | K-5 `admit-member` + `place-member`; outbox → placement; root default until applied | D-210 §6 |
| renew; suspend and restore | facts (Membership) | K-5 `renew-` / `suspend-` / `restore-membership`; C1 | D-163, D-177 |
| resign now; schedule and withdraw | End(resignation) (Membership); `ownAct` (Governance) | K-5 `record-resignation`; D-209 step (a) | D-203 … D-205 |
| member reads a district forum | belonging (Organisation) | K-4 `BelongingWithAncestorsAsAt` | D-152 |
| suspended member reads, cannot post | standing (Membership via K-4); rule (Governance) | K-4; K-7 | D-161, D-168 |
| worker reads a forum | non-member belonging (Organisation) | **T-2** | D-153, MI-14 |
| newsletter to members and named capacities | audience (Organisation); rule (Governance); snapshot (Communication) | K-4 ∪ **T-2**, K-7, one τ | **D-213** |
| opt out of one series | opt-out (Communication) | — | D-151 |
| **C2 refuses (boundary not closed)** | — | K-4 refused → **no send**; **posting denied**; member read denied; a worker's read is answered by **T-2** | D-210 §11; §3 T-2 |
| **moderator tenure ends** | tenure (Governance) | **T-1** `covered = false` from the end date → read-only; posts refused | D-150 |

## 8. Proof matrix additions (beyond the existing suites)

| # | Property |
|---|---|
| T1-1 | `OfficeCoverageAsAt` is false after the tenure ends and true while it runs; refused beyond the DecisionLog boundary |
| T1-2 | a refused T-1 answer denies posting and never denies reading |
| T2-1 | T-2 never reads Membership (architecture test) and never returns `Member` |
| T2-2 | a newsletter send is refused if K-4 or T-2 is refused at its τ |
| T2-3 | during a C2 refusal, a worker can read; a member cannot post; **nothing reports "not a member"** |
| G1-1 | after S1, no repository passes an application clock into `recorded_at` (architecture test + LC-5) |
| G6-1 | **a ledger organisation can never reach the legacy newsletter routes** (D-160) |

## 9. Unresolved items

| Item | Kind |
|---|---|
| T-1, T-2 acceptance | **tactical contract additions:** an ARB acceptance (D-94), ideally after an independent review |
| G-1 … G-6 | **conformance gates** (S0 … S2, S6) |
| G-7 | documentation correction |
| newsletter legal basis, retention, resignation effective time | legal (D-151, D-201) |
| mail provider idempotency; monitoring | external (D-210 [EXT]) |
| U-2 (Geography stewards' person reference) | later-release Identity contract |
| U-1, U-10, T-3 | the D-212 change request |
| **Genuine Principal business decisions** | **none.** The legacy newsletter's continued use for legacy organisations follows from D-160 |

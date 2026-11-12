# Release 1: tactical completion and implementation blueprint (Membership, Organisation, Newsletter, Forum; Geography boundary)

| | |
|---|---|
| **Status** | **PROPOSED, revision 2** (applies the Principal's closure review of 2026-11-12; revision 1 `…-rev1.md`, `6076153c…`).<br>- **T-1, T-2, T-4 and K-C1 need ARB acceptance.**<br>- **It changes no accepted decision or design and authorises no implementation;** each slice needs an EP-01-approved plan.<br>- Written by Claude, not independent |
| **A delta on (accepted)** | **M-TDD-01 rev 4 (D-210):** contracts K-1 … K-9, scenarios 1–22, MI-1 … MI-14. Also Governance completion rev 5 (D-208), ledger clock rev 2 (D-209), D-213, Organisation §49–§51 (D-156), Newsletter and Forum design (D-158). Strategic baseline v1.1 rev 4 (proposed) |
| **Code inspected** | `app/Contexts/Membership/{Domain,Application,Infrastructure}/Organisational/*` (incl. `Member.php`, `PostgresMemberRepository.php`, `InterimRoleAuthority.php`, `Ports/AuthorityCheckPort.php`); `app/Services/NewsletterService.php`; `app/Http/Controllers/Membership/OrganisationNewsletterController.php`; `config/database.php`; `app/Contexts` folder list; COR-4 plan `docs/plans/20261103-0440-membership-cor4-correction-persistence-tdd-plan.md` |

**Classes used for every finding:**
- **[AD]** accepted decision;
- **[F]** repository fact verified by inspection;
- **[P]** proposed tactical refinement;
- **[BD]** unresolved business decision;
- **[G]** implementation conformance gap;
- **[DEF]** deferred release item.

## 0. Disagreements with the commission (stated first)

1. **Newsletter flow, step 3 ("Membership supplies the member-standing answer"):**
   - **That would create the direct Communication → Membership dependency the accepted design forbids** [AD] (D-210 §4 line 77; D-158 §4; CR-02 §3).
   - **Membership's standing reaches Communication only inside Organisation's K-4 answer.** The flow below keeps that.
2. **"Complete the Geography strategic and tactical design now": it is complete and ratified** [AD] (D-93; Organisation §49.5–§49.6, §51).
   - What was missing was its **statement in the baseline**. Baseline rev 4 adds it (§4a).
   - **Nothing new is designed here** (§8).
3. **"Contracts before routing": agreed for the design.** Revision 1's order already put the contracts (§3) before any slice. Routing stays **slice S0 of implementation** because every release-1 context stores data in the organisation's database.

## 1. Executive verdict

| | Verdict |
|---|---|
| **Strategic baseline v1.1** | **rev 4 is clean and ready for ratification** (§2), with the reservations it lists and the E-1 confirmation |
| **Context boundaries** | sound; unchanged |
| **T-1, T-2** | **refined below; ready for ARB acceptance**, ideally after an independent review |
| **T-4 (new, contact details)** | **a gap the review exposed**; proposed below |
| **Membership code** | **not conformant to D-209** (G-1 … G-4); the exact changes are in §6 |
| **Business decisions needed** | **none.** One derived consequence of D-213 is shown so you can see it (§4, T-2 item 3) |

## 2. Strategic baseline assessment (rev 4)

**Checked in the file itself (not from the transcript)** [F]:

| Check | Result |
|---|---|
| 1–8 one responsibility per context; no foreign facts; Identity ≠ Membership; Organisation ≠ Membership; Governance owns authority, offices, tenures; Communication and Deliberation own no eligibility; Geography owns no units | **pass** (§3, §4) |
| 9 organisation data not centralised | **pass** (§1 "where data lives") |
| 10 temporal correctness | **pass**, after rev 4 repaired the §7 time row. **In rev 3 the row's bullet lines fell outside the table** |
| 11 D-211 classification | **pass** |
| 12 release ≠ ownership | **pass** (§6 lists release only; ownership in §3 is unaffected) |
| D-213 included | **rev 3 said "through D-212"; fixed in rev 4** |
| Geography–unit–address–belonging relationship | **missing in rev 3; added as §4a** (D-31, D-79, D-80, D-93, D-101, §49.5–§49.6, §51) |

**Contradictions: none. Missing statements: §4a (now added).**

**E-1 needs no business decision.** It needs only your confirmation in the ratifying act: the note's four items are rules of D-193 and D-185, so it is attributed to them (baseline Appendix E, with the note's exact text and line references).

## 3. In plain language: who owns what

- **Identity** knows who has registered and who is acting.
- **Membership** knows whether someone is a member, since when, in force or suspended.
- **Organisation** knows:
  - the organisation's units;
  - who belongs where, in which capacity (member, worker, guest);
  - **and how to reach them** (the organisation's own contact details).
- **Governance** knows the rules, the committees, who holds which office, and whether someone may act.
- **Forum (Deliberation)** keeps forums, posts and moderation.
- **Newsletter (Communication)** keeps series, issues, opt-outs, the recipient snapshot and delivery.
- **Reference Geography** keeps the official map. **It never decides who belongs where.**

## 4. Tactical contracts [P]

### T-1: office coverage (Governance → Deliberation)

**Business question:** "does any valid moderator tenure cover this forum on this date, as Governance knew it at τ?"

**Not the same as AuthorityCheck**, which asks whether a **particular actor** may act.

```
OfficeCoverageAsAt(organisationId, office: moderator, forumId, at d, asKnownAt τ)
  → Covered   { tenures: [{ tenureRef, positionRef, basisDecisionRef, from, to, via: direct | containingForum }],
                decisionLogClosedThrough }
  | NotCovered { decisionLogClosedThrough }
  | Refused   { reason: BOUNDARY_NOT_CLOSED | SCOPE_UNRESOLVED | UNAVAILABLE }
```

| Point | Semantics | Source |
|---|---|---|
| **office kind** | the functional office **moderator** (a Position held for a term, attached to its organ) | [AD] thin slice §0 item 3, §6.1 `Position` |
| **scope** | `forumId`. **Also covered by a moderator of a containing forum, where the organisation's rules allow (`via: containingForum`)** | [AD] D-150 |
| **valid tenure** | Governance alone decides validity. The tenure:<br>- is in its term on d;<br>- has a Basis (a decision part; a tenure without Basis cannot exist);<br>- belongs to an organ that has not ended (D-145);<br>- **its basis is not annulled or voided as known at τ.**<br>**The consumer never re-checks the appointment** | [AD] thin slice G-I1, "tenure only with a Basis"; D-145; GD-1 … GD-12 |
| **overlapping tenures** | all listed; **covered if at least one is valid** (moderator is non-exclusive by platform default, D-171) | [AD] D-171 |
| **incomplete knowledge** | τ beyond the DecisionLog boundary, or a scope that cannot be resolved → **Refused, never NotCovered** | [AD] D-73 |
| **no personal data** | holder identities are not returned | data minimisation (D-75 spirit); [P] |
| **effect on Forum** | `NotCovered` → read-only. **`Refused` → posting denied; reading unaffected** | [AD] D-150, D-210 §8 |
| **editor** | **not needed for release 1:** sending is gated by the actor's own AuthorityCheck `send-newsletter(series)`, which already requires the actor's editor tenure (D-58, D-60). **The contract shape can later take `office: editor, scope: (seriesId, unitRef)` if a UI needs "no editor designated"** | [AD] D-58, D-60; [P] |

### T-2: non-member capacities, without Membership (Organisation → Communication, Deliberation)

**Business question:** "can Organisation establish that this person holds a named non-member capacity in this scope, without asking Membership?"

```
CapacityBelongingAsAt(organisationId, scopeUnit, capacities[], at d, asKnownAt τ)            // audiences
CapacityBelongingOfPersonAsAt(organisationId, personRef, capacities[], at d, asKnownAt τ)    // forum checks
  → { items: [{ personRef, capacity, unitRef, belongingRef, basis }], organisationClosedThrough }
  | Refused { reason: BOUNDARY_NOT_CLOSED | UNAVAILABLE | MEMBER_CAPACITY_REQUESTED }
```

| # | Rule | Source |
|---|---|---|
| 1 | **`Member` cannot be requested** (refused). **Never consults Membership.** **K-4 unchanged** | [AD] B-2 applies only to membership-dependent belonging (§50.3, §50.6); MI-14 |
| 2 | **An empty answer means "no such non-member belonging", never "not a member"** | [AD] D-210 §11 |
| 3 | **A person in both routes appears once in an audience.** The snapshot records every basis (e.g. `member`, `worker`).<br>**Derived from D-213:** each route qualifies independently. **A suspended member who is also staff, where the series names staff, receives it as staff.** If that is not intended, the series decision or the organisation's rule decides | [AD] D-213 (2) "by their own valid belonging … the standing filter does not apply" |
| 4 | **complete or refused**, at Organisation's own §49.8 boundary; **one τ with K-4 for a send** | [AD] D-73; §49.8 |
| 5 | **Forum reading as a worker or guest** uses T-2 when K-4 is refused (U-9) | [AD] D-153, D-207; [P] |
| 6 | **Posting is not implied by reading:** **default (D-161, D-153): only a member in good standing (K-4) posts.** A non-member posts **only** where the organisation's written rule names that capacity (D-168); T-2 then establishes the capacity | [AD] D-153, D-161, D-168; MI-14 |

### T-4 (new): contact for delivery (Organisation → Communication)

**The gap:** D-210 §7 step 5 says "contact = the organisation's person profile (D-101), never the login address", but **no contract names how Communication obtains it.** [F]

```
DeliveryContactsAsAt(organisationId, personRefs[], channel: email, asKnownAt τ)
  → { items: [{ personRef, contact | NoContact }], organisationClosedThrough } | Refused
```

- **Owner:** Organisation (D-101 contacts).
- **Never** Identity's login address (INV-ID-3).
- `NoContact` → excluded, with the reason recorded (D-210 §7).
- **The contact is used for this dispatch only; Communication keeps no copy beyond the delivery record** (no second contact register).

## 5. Business flows

### 5.1 Newsletter send (one issue)

| # | Step | Owner | Contract | Refusal |
|---|---|---|---|---|
| 1 | a series exists, created by a committee decision at a sitting, naming scope unit and any non-member capacities | Communication (series); Governance (decision) | series creation: AuthorityCheck (D-58, D-60) | no decision → no series |
| 2 | the editor sends an issue for official purposes (D-59) | Governance | **AuthorityCheck `send-newsletter(series)` for the actor** at send time | not Authorised → no send |
| 3 | **fix one τ for the send** | Communication | — | — |
| 4 | members of the scope, **with their Membership standing passed through** | Organisation | **K-4 `MembersOfScopeAsAt(unit, τ)`** | refused → **no send** |
| 5 | the named non-member capacities in the scope | Organisation | **T-2 `CapacityBelongingAsAt`** | refused → **no send** |
| 6 | the participation rule for members | Governance | **K-7 `ParticipationRuleAsAt(τ)`**: the written rule, else "platform default (D-161)" | refused → no send |
| 7 | union, de-duplicated by `personRef`; members filtered by step 6; named capacities unfiltered (D-213) | Communication | — | — |
| 8 | per-series opt-outs removed, for every capacity | Communication | — | — |
| 9 | contacts | Organisation | **T-4** | refused → no send; `NoContact` → excluded with reason |
| 10 | **one transaction:** the audience snapshot (each inclusion's basis and `ruleSource`) + the Dispatch + outbox rows | Communication | — | rollback → nothing sent |
| 11 | delivery: outbox → mail adapter; idempotency key = dispatch + recipient; records "attempted / accepted", never "read" | Communication (ACL to the provider) | provider [EXT] | retry only non-accepted |

- **Formal notices** (e.g. convocations) are **not** newsletters and are unaffected by opt-outs (D-151).
- **Legacy newsletter:** never for ledger organisations (D-160; G-6).
- **[LQ]** the legal basis for sending (D-151).

### 5.2 Forum

| Act | Checks, in order | Contracts | If a source refuses |
|---|---|---|---|
| **read F** | effective belonging in F's unit or a unit below it, in any capacity | K-4 `BelongingWithAncestorsAsAt`; **if K-4 is refused:** T-2 for the person's non-member capacities | member read denied; a worker or guest still reads through T-2; **never "not a member"** |
| **read a parent-unit forum** | same; the ancestors are included | K-4 | as above |
| **suspended or lapsed member reads** | belonging counts while covered (lapse and suspension are inside coverage) | K-4 | — |
| **post in F** | read ∧ **T-1 Covered** ∧ participation rule (K-7: default member in good standing via K-4; or a written rule naming a capacity, checked through T-2) | K-4, K-7, T-1, T-2 | **any refusal → post denied** |
| **moderate** | the actor's AuthorityCheck `moderate(forum)` at the act's time | K-5 | denied |
| **moderator's tenure ends** | from that date T-1 returns NotCovered (unless another tenure covers) → read-only | T-1 | — |
| **committee forums** | [DEF] (D-56, D-152) | — | — |

- **Deliberation stores no standing, no hierarchy and no authority.**
- It records each access decision's `ruleSource` (D-167).

## 6. Membership: exact conformance changes [G]

| # | Today | Change required | Source |
|---|---|---|---|
| G-1a | `recordedAt = max(($this->clock)(), last + 1)` per membership | `t = clock_timestamp()`, read in the database inside the transaction; **no adjustment;** the application clock is removed from record time | [F] `PostgresMemberRepository.php:220`, `:51`; [AD] D-209 |
| G-1b | append order: key check → valid time → **version compare-and-set** → insert | **lock the organisation's clock row `FOR UPDATE` first**, then the version compare-and-set | [F] same file lines 41–43; [AD] D-162 correction note, D-209 |
| G-1c | no per-organisation clock state | a per-organisation row holding `last_recorded_at` and `closed_through` (organisation database) | [AD] D-209 |
| G-1d | the repository "never retries" | **refuse when `t ≤ closed_through` or `t < last_recorded_at`**; the application retries (as it already re-decides on ConcurrencyConflict) | [F] lines 41–43; [AD] D-209 |
| **K-C1 [P]** | **V-5 (COR-4 Q-2): strictly increasing `recordedAt` per membership, achieved by +1 µs** | **keep V-5 without adjustment: also refuse and retry when `t` equals that membership's last `recordedAt`.** D-209 allows equal times across the organisation; this is stricter and adds no adjustment | [F] COR-4 plan line 37, `developer_guide/membership/14_cor4_correction_persistence.md:38`; domain only needs non-decreasing (INV-14, `Member.php:30`); **needs ARB acceptance with the S1 plan** |
| G-2 | no `closedThrough` step, C1 outbox or C2 | D-162 §4–§6 as amended by D-209; **a scheduler calls the same `closedThrough` step every minute per organisation**, so due resignations execute without readers | [AD] D-162, D-209 |
| G-3 | `InterimRoleAuthority` mirrors owner / admin | **not bound for any release-1 organisation;** Governance AuthorityCheck behind `AuthorityCheckPort` | [F]; [AD] D-1a, D-167, PO-ORG-MEM-01 A |
| G-4 | no Renew / Suspend / Restore, scheduled resignation, `ownAct` path; the D-205 guard exception absent | add per D-163, D-177, D-203 … D-205. **The guard exception is limited to `End(resignation)` executed from a scheduled declaration on its effective date** | [AD] |

## 7. Membership, Organisation and Governance tactical checks (verified against accepted texts)

| Item | Covered by | Status |
|---|---|---|
| admission, ending, renewal, suspension / restoration | RA; D-163, D-177; D-210 §5 | [AD] |
| resignation as the member's own act; scheduled and withdrawn | D-194, D-203 … D-205; D-210 §5.2 | [AD]; code [G-4] |
| Annul / Void / Correct with authority | D-206, D-208 §2.5 | [AD]; code: Annul / Correct / Void facts exist [F]; authority via G-3 |
| C1, C2, `closedThrough`, complete or refused | D-162, D-209 | [AD]; code [G-2] |
| a person may belong without being a member; a member may be unregistered | D-101, D-113, D-207 | [AD] |
| placement only by an authorised act; root default; lower unit only where competence exists | D-79, D-114, D-172 | [AD] |
| belonging never from an address or the map | D-79, D-80 | [AD] |
| organisation units ≠ official units | D-31, §49.5 | [AD] |

## 8. Reference Geography [DEF]

**Designed and ratified now:**

| Item | Where |
|---|---|
| stable `ReferenceUnitId` | D-93 (v3.11) |
| hierarchy; valid time and knowledge time; `positionAt(τ)` within `closedThroughTime` | v3.11 §2, §6; §49.6 |
| revisions (`RevisionPublished`) | v3.11 §6; §49.6 |
| the organisation's immutable coverage reference; review flags; no automatic change | §49.6 |
| allocation rules (only where the rules adopt a residence rule) | §51; D-79 |
| level correspondence, which warns and never imposes | §49.5 |
| addresses: organisation-local; at most a suggestion | D-80, D-101 |
| country conventions | CLDR (D-87) |

**Legitimately deferred:**
- implementation and integration (D-147);
- **address ownership** (the address model), L-4c, Q-2, Japanese classification, naming policies (D-93);
- **U-2:** the stewards' platform-level person reference. **Not solved by borrowing an organisation `PersonRef`**; it needs an Identity decision in the Geography release;
- proofs PO-1 … PO-8.

**Nothing requires changing D-147.**

## 9. Implementation architecture [P]

**Revision 1 §5 stands:**
- a modular monolith with explicit folders per context;
- consumer-declared ports;
- an explicit organisation connection;
- the outbox in the same transaction;
- the legacy newsletter kept for legacy-store organisations only.

**Additions:**

| Context | Commands | Queries it provides | Ports it consumes |
|---|---|---|---|
| Membership | Admit, Renew, Suspend, Restore, End, Annul, Void, Correct, DeclareResignation, WithdrawResignation | C2 | AuthorityCheck |
| Organisation | Place, Transfer, EndBelonging, record profile and contacts | K-3, K-4, **T-2, T-4** | C1 inbox, C2, AuthorityCheck |
| Governance | establish organ, appoint (incl. functional offices), end term | AuthorityCheck, K-7, **T-1** | Organisation (unit served) |
| Deliberation | CreateForum (on `UnitEstablished`), Post, Moderate | — | K-4, K-7, T-1, T-2, AuthorityCheck |
| Communication | CreateSeries, SendIssue, OptOut, Resubscribe | — | K-4, K-7, T-2, T-4, AuthorityCheck, mail adapter |

**Architecture tests:**
- no import of another context's Domain or Infrastructure;
- Communication and Deliberation never reference Membership;
- T-2 never references Membership;
- no repository writes an application time into `recorded_at`.

## 10. Slices (unchanged from revision 1)

| # | Slice |
|---|---|
| S0 | Platform routing |
| S1 | ledger clock (G-1, K-C1, G-2) |
| S2 | Membership lifecycle (G-4) |
| S3 | Governance + T-1 |
| S4 | Organisation + T-2, T-4 |
| S5 | Forum |
| S6 | Newsletter |
| later | Geography |

**Proofs: revision 1 §8, plus:**

| # | Property |
|---|---|
| T1-3 | overlapping tenures: covered while any one runs |
| T1-4 | an annulled appointment (as known at τ) no longer covers |
| T2-4 | a person in both routes appears once, with both bases |
| T4-1 | delivery never uses the login address; `NoContact` is excluded with a reason |
| KC1-1 | equal clock reading for the same membership → refused and retried, never adjusted |

**None has been executed.**

## 11. Principal decisions

| # | Decision | Kind | Recommendation |
|---|---|---|---|
| 1 | ratify strategic baseline v1.1 rev 4, confirming E-1 | ratification | ratify |
| 2 | accept T-1, T-2, T-4 | ARB, tactical | after a short independent review (GPT-6) |
| 3 | K-C1 (keep V-5 by refusal) | ARB, with the S1 plan | accept |

**Business decisions: none.** T-2 item 3 (suspended staff receive staff newsletters) is how D-213 reads; it is shown so you see it, not reopened.

## 12. Next prompt

> **Independent review IR-R1C-01 (GPT-6):**
> - strategic baseline v1.1 rev 4;
> - blueprint rev 2 §4 (T-1, T-2, T-4) and §6 (K-C1).
>
> Questions:
> 1. Does each contract answer exactly its business question without moving ownership?
> 2. Are refusal semantics consistent with D-73?
> 3. Does T-2 keep K-4 unchanged?
> 4. Does K-C1 conform to D-209?
>
> Verdict per item. Then the Principal ratifies and accepts, and the **EP-01 plan for slice S0 + S1** follows.

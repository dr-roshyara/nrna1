# PublicDigit strategic DDD baseline: assessment after D-210

| | |
|---|---|
| **Status** | **Assessment.** Decides nothing; changes no accepted text. Written by Claude, **not independent** |
| **Commission** | Principal, 2026-11-12: "PublicDigit Strategic DDD Baseline Assessment" |
| **Evidence (read in this session)** | decision record through D-210; strategic baseline acceptance act (D-173) `docs/publicdigit/reviews/2026-11-11-strategic-baseline-acceptance-act.md`; post-correction baseline review Part 2 (the accepted context table); `docs/publicdigit/architecture/context-map.md`; `docs/publicdigit/architecture/strategic-design-status.md`; capability discovery report 2026-11-09 §6, §9; Geography v3.11 §8; Governance completion rev 5 §2.2 |

**Labels:**
- **[F]** verified fact (file and line or section given);
- **[I]** architectural interpretation;
- **[R]** recommendation;
- **[LQ]** legal question for counsel.

## 0. Disagreements with the commission (stated first)

1. **The premise that the strategic baseline is unestablished is contradicted by the record.** [F]
   - **D-173 (§11.75, 2026-11-11) accepted the strategic baseline through D-172:**
     - the bounded contexts and three kinds of ownership;
     - the Platform context;
     - the cross-cutting rules;
     - the release-1 scope.
   - The tactical designs came **after** it. The order was strategic first, then tactical, which is the order the commission asks for.
   - **A fresh discovery would repeat accepted work** ("discover once … reopen only when new evidence justifies it", status record header).
   - **This assessment is therefore a delta check:** does the accepted baseline still hold after D-174 … D-210, and what material gaps remain?
2. **"A person registers once" needs its exact meaning.** [F]
   - D-111 and D-164 hold, but:
     - **D-113:** an organisation may record a person who has **not** registered; Identity then issues a `PersonRef` without creating a Person.
     - **D-75, D-101:** names and contact details are organisation-local.
   - So registration is **not** a precondition for membership. The constraint as phrased in the commission could be read that way.
3. **Agreed:** a finished tactical design is not proof of a complete strategic model. The answer to the commission's central question is in §1.

## 1. Executive summary

**Short answer:**
- **PublicDigit has a coherent, accepted strategic model** for:
  - the organisation and governance domain;
  - release 1 (Membership, Newsletter, Forum).
- **Every release-1 business fact has one owner.**
- **It is not yet a single usable baseline.** There are three material gaps:

| # | Gap | Kind |
|---|---|---|
| **SG-1** | the accepted model exists only as an acceptance act, a review table and 37 later decisions. **The context map document is a stale 2026-11-02 proposal that contradicts the accepted state** [F] | strategic artifact |
| **SG-2** | **no core domain has been declared** (HD-3 never decided) [F] | Principal decision |
| **SG-3** | **a member's exclusion by a party tribunal has no route.** D-208 allows exclusion only by an organ's decision; a tribunal ruling is never one (IR-GM-01 A-01) [F]. For German parties the tribunal decides exclusions (§10 (5) PartG) [LQ] | strategic ownership + tactical |

**Also:** the "D-156 fingerprint drift" I reported in IR-GM-01 and D-210 (5) **does not exist.** D-173 re-recorded the file at `1e9eaf31…`, which is its current hash [F]. Recorder correction §11.110.

## 2. Business domain model

**Business problem:** a self-governing organisation (D-100: party, association, union, federation) must run its members, structure, decisions and member communication according to **its own written rules**, provably, without the platform deciding anything for it.

| Capability | Purpose | Outcome | Authoritative owner |
|---|---|---|---|
| Registration and sign-in | one login across organisations | a person can act in each organisation | Identity (central) |
| Organisation tenancy | each organisation's own database and status | isolation, routing | Platform (central) |
| People, units, belonging | who is in the organisation, where, in which capacity | placement, scopes | Organisation |
| Membership lifecycle | admit, renew, suspend, restore, end, correct | provable member status over time | Membership |
| Rules, organs, offices, decisions | who may do what, under which rules | authority answers, decision records | Governance |
| Disputes and remedies | independent review of decisions | determinations | Adjudication (later in practice; frozen) |
| Member forums | discussion within a scope | posts, moderation | Deliberation |
| Newsletters | messages to a defined audience | delivered issues, opt-outs | Communication |
| Official geography | shared administrative map | stable references | Reference Geography (central; later release) |
| Elections, voting, finance | later releases | — | Election, Voting, Finance |

## 3. Bounded contexts

**The accepted table (post-correction review Part 2) stands.** [F] Changes since D-173 deepened contexts, not boundaries:

| Context | Since D-173 | Boundary change? |
|---|---|---|
| Platform | tactical design accepted (D-193) | no |
| Identity | claims and bindings accepted (D-200); `ownAct` self-service (D-203) | no |
| Governance | completion accepted (D-208): ending, correction, placement act kinds, decision records | no |
| Membership | scheduled resignation outside the ledger (D-204, D-205); ledger clock (D-209) | no |
| Organisation, Deliberation, Communication | integration accepted (D-210); applicants as Guests (D-207) | no |

[I] **No double owner; no missing owner in release-1 scope, except SG-3.**

## 4. Context map (release 1)

[I] The pattern is chosen from who adapts to whom:

| Upstream → downstream | Pattern | Reason |
|---|---|---|
| Identity → all | **Conformist** on `PersonRef` | one opaque reference; consumers never interpret it |
| Platform → all | **Open host** (routing, status) | infrastructure every context needs equally |
| Membership → Organisation | **Published Language** (C1 events, C2 `CoverageKnownAt`) | the ratified ledger must not be read directly (RA "no shared persistence") |
| Organisation → Communication, Deliberation | **Customer–Supplier** (`MembersOfScopeAsAt`, `BelongingWithAncestorsAsAt`) | these consumers need belonging, never Membership itself (D-161, D-210) |
| Governance → Membership, Organisation, Communication, Deliberation | **Customer–Supplier**, Governance supplying `AuthorityCheck` | authority comes only from the organisation's rules (D-1a, D-167) |
| Adjudication → Governance | **Published Language** (Determination); **Governance applies** | the tribunal never writes Governance or Membership facts (D-67, D-202 item 8) |
| Communication → mail provider | **Anti-Corruption Layer** | the provider's model must not leak in; idempotency is an external guarantee (D-210 [EXT]) |
| Reference Geography → Organisation (later) | **Published Language** (`ReferenceUnitId`, revisions) | references only; no inferred belonging (D-93, D-147) |

**No Shared Kernel is justified.**

## 5. Invariants and authority

| Fact | Owner | Source |
|---|---|---|
| member status at τ | Membership | RA; C2 |
| belonging and placement | Organisation | D-142, §50 |
| who may act | Governance | D-1a, D-167 |
| decision record | Governance | D-202 |
| determination of a dispute | Adjudication; **applied** by Governance | D-67, D-202 item 8 |
| who the actor is | Identity | D-75, D-200 |
| where an organisation's data lives | Platform | D-173 |
| official area reference | Reference Geography | D-93 |
| **exclusion decided by a tribunal** | **unresolved: neither an organ's Resolution (D-208) nor a remedy to a challenge (§3.3)** | **SG-3** |

## 6. Strategic gaps

| # | Finding | Evidence | Why it matters | Resolution |
|---|---|---|---|---|
| **SG-1** | **No consolidated, current strategic map.** `context-map.md` is PROPOSED, dated 2026-11-02, and states "Platform / tenancy … assignment to a named context not established" (line 88) and "Communication: forum" (line 95). The status record says the domain is "IN PROGRESS. Not complete" (§2). Both contradict D-173 | [F] context-map.md:3, :88, :95; strategic-design-status.md §2; acceptance act §3 last line; post-correction review P-7 | **a builder trusts the map**, not 210 decision entries. A solo developer needs one picture | [R] consolidation (§9); no business decision |
| **SG-2** | **Core domain undeclared.** HD-3 (is Election one subdomain or two; is Adjudication core) was raised and never decided | [F] discovery report §6 lines 232–246, §9 HD-3; no decision-record entry | it decides **where quality effort goes**: rigour, tests, build vs buy | **Principal decision P-A** |
| **SG-3** | **Tribunal-decided exclusion has no route.** D-194 (2) says "only the body the rules name", which is generic. D-208 narrowed it to an **organ's** Resolution part (GC-3, GC-13). After A-01, a tribunal ruling is never a Resolution. **A party whose statute names its tribunal therefore cannot exclude anyone:** the act waits (D-167) | [F] D-194; Governance rev 5 §2.2 line 90; §2.5 route 2; [LQ] §10 (4)–(5) PartG: exclusion only for serious culpable breach, **decided by the competent party tribunal**, with appeal; in urgent cases the board may suspend the member's rights pending the ruling | for German political parties this is the **normal** case, not an edge case | **Principal decision P-B** |

## 7. Tactical gaps, technical concerns, external items

| Class | Item |
|---|---|
| **Tactical** | **TG-1:** the exclusion route that follows P-B (a Governance addition plus one Adjudication case kind, if option 1). **TG-2:** committee forums, deferred (D-56, D-152). **TG-3:** Geography integration in the later release, designed (v3.11, §51), unchanged |
| **Technical** | proof suites not executed: Platform (53), GC-1 … 17, LC-1 … 6, MI-1 … 14, A1 … A3. The live election module still runs on the single-database model; it is outside release 1 and follows the D-160 cutover gate |
| **External** | [LQ] newsletter basis, retention, `PersonRef` classification; **resignation effective time: as I understand §10 (2) PartG, a party member may resign immediately at any time, so a statute's notice rule (D-201 "rules on notice take precedence") may not bind parties.** The design already supports immediate resignation; counsel to confirm. Mail-provider idempotency; monitoring |
| **Resolved** | old blockers DR-07 (D-3, D-142), Appointment (D-4, D-72), vocabulary O-14 (D-143), HD-8 (D-173); the D-156 "drift" (no drift) |

## 8. Decisions for the Principal

**P-A (SG-2): what makes PublicDigit worth choosing?**

| | |
|---|---|
| **Question** | which part of the business is the **core** (where to invest most care), and which parts are supporting or bought |
| **Why it matters** | effort, test depth and build-vs-buy choices follow it |
| **Option 1 [R]** | **Core: Governance (authority under the organisation's own rules, decision records) and Voting (anonymous ballots). Contestation and Adjudication core, labelled "not yet built".** Supporting: Membership, Organisation, Election lifecycle (BDR-08, frozen), Deliberation, Communication. Generic: Identity, Platform, Reference Geography (D-31), mail delivery (bought) |
| **Option 2** | Membership as core as well |
| **Consequence** | option 1 keeps the ratified rigour of Membership (it stays high-integrity) without calling a generic capability a differentiator. Every party tool has a member list; few prove that each act was authorised under the party's own statute |

**P-B (SG-3): who decides an exclusion when the statute names a tribunal?**

| | |
|---|---|
| **Question** | when the rules give exclusion to the party tribunal, which context decides, and how does the exclusion reach Membership? |
| **Why it matters** | without an answer, parties under German law cannot exclude members on PublicDigit |
| **Option 1 [R]** | **the tribunal decides in Adjudication** (an "exclusion application" filed by the competent body, for example the board). **Governance applies the Determination** as an applied fact, like Annul route 2. Membership records `End(exclusion)` with the Determination as provenance. Interim suspension by the board uses the existing Suspend act kind. **Keeps the tribunal independent of the organs** and needs no new context |
| **Option 2** | treat the tribunal as a Governance organ that passes Resolutions. **Conflicts with A-01 and D-67** (a tribunal ruling would become a Resolution) |
| **Option 3** | exclusion by organ only; tribunal-based parties wait |
| **Consequence** | option 1 extends D-208 by one route (TG-1) and Adjudication by one case kind. **Adjudication is frozen, so this needs a change request.** Not needed for launch if release-1 parties accept that exclusions wait until it exists |

## 9. Recommended next activity (one)

**Strategic baseline consolidation and re-acceptance (baseline v1.1), after P-A and P-B.** One document:
- the current context map;
- the subdomain classification;
- the ownership and authority matrix;
- the relationship list, each row citing its decision.

It replaces the stale `context-map.md` as the current map and ends the status record's contradiction. It also re-records fingerprints of the accepted texts that changed after D-173 (D-157, D-162, D-208 … D-210), as D-173's change control requires. **No new modelling, only consolidation.** **Then:** the EP-01 implementation plan for release-1 slice 1.

## 10. Verdict

**SUBSTANTIALLY ESTABLISHED, WITH MATERIAL GAPS.**
- Accepted by D-173 and coherent with D-174 … D-210.
- Gaps: SG-1 (no consolidated map), SG-2 (no core domain), SG-3 (no tribunal exclusion route).
- **After P-A, P-B and the consolidation, it is ready for re-acceptance as baseline v1.1.**

---

**Outcome (2026-11-12):**
- **P-A decided as D-211** (option 1).
- **P-B decided as D-212** (option 1).
- **SG-2 resolved.**
- **SG-3 resolved at strategic level;** TG-1 (Adjudication change request + Governance addition) remains, before tribunal exclusion is offered.
- **Remaining:** SG-1, through the recommended consolidation (§9).

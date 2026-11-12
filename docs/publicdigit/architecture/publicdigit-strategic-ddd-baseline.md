# PublicDigit Strategic DDD Baseline v1.1

| | |
|---|---|
| **Status** | **PROPOSED for Principal review, revision 4** (a cleanliness pass after the Principal's closure review of 2026-11-12: D-213 included, the §7 table repaired, §4a Geography relationship added; revision 3 was `9a691140…`, revision 2 `759ffd81…`, revision 1 `a9f56a1d…`). **Not accepted until the Principal ratifies it.** Until then the decision record governs, and `context-map.md` keeps its present status |
| **What it is** | a **consolidation** of the strategic model accepted by **D-173** and deepened by D-174 … D-213. **It decides nothing, adds no rule, and changes no decision's meaning** |
| **Date / author** | 2026-11-12 · Claude (consolidation; not independent) |
| **Decision record** | `docs/publicdigit/reviews/2026-11-08-organisation-strategic-discovery-record-structure-mandates-conventions.md`, through §11.112 (D-213) |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0) |

**Precedence:**
1. the decision record;
2. the D-173 acceptance act and its accepted context table;
3. accepted tactical designs, where they refine;
4. supporting documents;
5. proposals and assessments.

**If this document and the decision record ever differ, the decision record wins.**

**Labels:**
- **[RAT]** ratified;
- **[AD]** accepted decision;
- **[ACC-T]** accepted tactical design;
- **[DEF]** deferred to a later release;
- **[UNR]** unresolved, with no authority invented.

---

## 1. Strategic purpose

**Business problem:**
- A self-governing, membership-based organisation (political party, association, union or federation; **D-100**) must run its people, structure, membership, decisions and member communication **under its own written rules**.
- It must be able to prove later who was authorised, under which rule, on what evidence, and when it became known.

**Differentiators (D-211):**
1. **Legitimacy under the organisation's own rules.** Authority comes only from the organisation's competent acts (**D-1a**). PublicDigit never assigns authority by default; silence means the act waits (**D-167**).
2. **Trustworthy anonymous voting** [DEF].
3. **Independent correction of decisions** (Contestation and Adjudication) [DEF; not yet built].

**What PublicDigit centrally knows (D-111, D-164):**
- who has registered and how they sign in;
- which organisations exist and where their data lives;
- which accounts are associated with which organisations (routing only);
- the shared official map.

**Where data lives (D-164):**

| Kind | Where | Examples |
|---|---|---|
| **central platform data** | central database | Identity, Platform (tenancy) records |
| **shared reference data** | central database | Reference Geography (D-31) |
| **organisation-owned business data** | the organisation's own database | people records, affiliations, units and belonging, membership, governance, forums, newsletters, elections, finance |
| **unresolved** | **not decided** | **Contestation and Adjudication** records: D-164's list does not name them (U-1) |

**Registration is not a precondition for membership:**
- An organisation may record a person who has never registered. **Identity then issues that organisation's `PersonRef` without creating a Person (D-113).**
- **Names and contact details stay organisation-local (D-75, D-101).**
- If the person registers later, a valid claim (an organisation-issued one-time code plus a verified login address) associates the account with the existing record (**D-113, D-200**).
- One account may be associated with several organisations. **That centralises none of their membership, voter, finance or organisational records (D-110, D-164; ISO-7).**

## 2. Subdomain classification (D-211)

| Class | Subdomains |
|---|---|
| **Core** | **Governance** · **Voting** [DEF] · **Contestation** and **Adjudication** (two contexts; both [DEF], **not yet built** as a product path) |
| **Supporting** | Membership · Organisation · Election lifecycle (BDR-08) [DEF] · Deliberation · Communication |
| **Generic** | Identity · Platform · Reference Geography (D-31) · mail delivery (bought) |

**"Supporting" means "not the differentiator". It does not mean low integrity (D-211: "classification only").**
- Membership keeps its ratified rigour: ledger, history, provenance.
- **A wrong membership status lets the wrong person write in a forum, receive a newsletter or, later, vote.**

**Not bounded contexts:**
- **Authorization** is a supporting service that owns no truth (BDR-07).
- **Audit** is an infrastructure / platform capability (BDR-09).

## 3. Bounded contexts

**Storage (D-164):** **C** = central database · **O** = the organisation's own database.

| Context | Responsibility | Owns (facts) | Owns (authority) | Never owns | Store | Release | Establishing |
|---|---|---|---|---|---|---|---|
| **Identity** | platform identity | Person (registered people only), Account, login address, authentication, sessions, account lifecycle, `PersonRef` issuance and resolution, claims, bindings | none | membership, access, authority, organisation-local names and contacts | C | 1 | D-75, D-110, D-111, D-113; [ACC-T] D-200 |
| **Platform (tenancy)** | organisations as tenants | organisation registry, database locator, platform status and `schemaState`, service-operation grant **record**, D-170 pending case-grant records | none (**the grants inside are the organisation's decisions**) | any organisation's business facts; any authority | C | 1 | D-173 §1.3; D-166, D-170; [ACC-T] D-193, D-178 |
| **Reference Geography** | the shared official map | official reference units (`ReferenceUnitId`, minted once, never reused), revisions, approvals | **its own stewardship roles** (v3.11 §8) | **belonging, membership, entitlement, authority, the organisation's own units** | C | **[DEF]** (D-147) | D-31, D-93 [RAT] |
| **Organisation** | the organisation's people and structure | organisation-local person records and profiles, affiliations and capacities (User, Member, Worker, Guest), units, **belonging and placement**, workspace grants, case grants once recorded locally | none (**decides no right**, K-4) | **membership truth** (the Member capacity only reflects it, D-103); authority | O | 1 | D-101, D-103, D-106, D-114, D-142; [ACC-T] D-156 §49–§51, D-210 |
| **Membership** | formal membership over time | periods, terms, Admit / Renew / Suspend / Restore / End(kind) / Annul / Void / Correct, coverage, `inForce`, `suspended`, **scheduled resignation declarations (outside the ledger)** | none (**authority comes from Governance**) | belonging, rights, eligibility decisions | O | 1 | [RAT] 2026-10-08; D-73, D-161, D-162, D-163, D-204, D-205, D-209; [ACC-T] D-177, D-210 |
| **Governance** | authority under the organisation's rules | rules on file (**incl. the participation rule**), organs (committees), positions, appointments, terms of office, motions after submission, Resolutions and parts, competence evaluations, DecisionLog, **applied determinations** | **AuthorityCheck** (who may act); organ decisions | structure, belonging, membership facts | O | 1 (thin slice + completion) | D-4, D-51, D-72, D-144, D-157, D-167, D-202; [ACC-T] D-208 |
| **Deliberation** | member discussion | forums, posts, moderation acts, forum access decisions, **motions before submission** | none | belonging, authority, membership | O | 1 (unit forums; **committee forums [DEF]**, D-56, D-152) | D-50, D-51, D-150 … D-153, D-158 |
| **Communication** | member newsletters | series, issues, audience decisions and snapshots, delivery records, per-series opt-outs | none | audience facts (belonging, standing), authority | O | 1 (members of the scope, plus the capacities a series decision names, D-213; **outside subscribers [DEF]**, D-57) | D-57 … D-61, D-151, D-158, D-213; [ACC-T] D-210 §7 |
| **Contestation** | raising and carrying a challenge | the **Challenge** and its lifecycle (`Raised → Admitted → Investigating → Routed → Adjudicated → Resolved`, or `Dismissed` / `Lapsed`); `ChallengeRaised`; `ChallengeRouted` (published language) | none (it routes; it does not decide) | the determination; Governance, Membership and Election facts | **[UNR]** (U-1) | **[DEF]**; domain modelled, product path not built; **a Challenge is scoped to one Election today** (ADR-UL-01; see U-6) | ADR-PL-01, ADR-T20, ADR-T21, ADR-UL-01; PB-005; D-53, D-71 |
| **Adjudication** | independent determination | **Determinations** and remedies (`DeterminationIssued`); evidence inputs COL-1 … COL-3 | **the tribunal's determination** | **Governance and Membership facts** (Governance applies them; Adjudication never writes them) | **[UNR]** (U-1) | **[DEF]; not yet built**; EPIC-001/002 design **frozen** | D-67, D-71, D-202 item 8, D-212; EPIC-002 context map; ADR-PL-01 |
| **Election** | election lifecycle | electorate, issuance, election committee and officers | the election committee's acts within its competence (D-23) | membership, belonging | O | [DEF] (D-146) | boundary [RAT] (act §11–§12); D-23, D-32, D-41 |
| **Voting** | anonymous ballots | ballots (**no voter link**) | none | membership, belonging, identity linkage | O | [DEF] | BDR-12; D-146 |
| **Finance** | payments | payments, settlement | none | standing, eligibility | O | [DEF] | outline [RAT]; D-132 |

## 4. Ownership and authority matrix

| Business fact or decision | Authoritative owner | How others learn it | Establishing |
|---|---|---|---|
| who is registered; who is acting | Identity | `PersonRef` (R-1 `ActingRef`, K-8) | D-75, D-111, D-200 |
| where an organisation's data lives; its status | Platform | routing | D-173, D-193 |
| **whether, and since when, a person is a member; in force; suspended** | **Membership** | C1, C2 (K-1, K-2) | RA; D-73, D-103, D-161 |
| where a person belongs, and in which capacity | **Organisation** | K-3, K-4 | D-142, D-114, §50 |
| who may act; under which rule | **Governance** | AuthorityCheck (K-5) | D-1a, D-167 |
| what an organ decided (Resolution and its parts) | **Governance** | push with the part (K-6) | D-202, D-208 |
| a challenge and its lifecycle | **Contestation** | `ChallengeRaised`, `ChallengeRouted` | ADR-PL-01, ADR-T20, ADR-T21 |
| a tribunal's determination | **Adjudication**; **applied** by Governance as an applied fact | Determination → Governance | D-67, D-202 item 8, D-212 |
| the participation rule (who may post, who receives) | **Governance** (the organisation's written rule, else "platform default (D-161)") | K-7 | D-161, D-168 |
| forum posts and moderation | Deliberation | — | D-158 |
| newsletter content, audience snapshot, opt-outs | Communication | — | D-151, D-158 |
| official area references | Reference Geography | `ReferenceUnitId` (K-9) [DEF] | D-93 |

**Never conflated (each pair has two owners):**

| Not the same | Why | Establishing |
|---|---|---|
| **Person identity ≠ membership** | Identity knows nothing of membership; an unregistered person can be a member | D-75, D-113 |
| **Membership ≠ belonging** | Membership states coverage; Organisation states unit and capacity, anchored to coverage | D-103, D-142 |
| **Organisation units ≠ official geographic units** | units are the organisation's; the official map is shared reference data | D-31, D-93 |
| **An organ's decision ≠ a tribunal's determination** | a Resolution is Governance's; a Determination is Adjudication's and is applied, never re-decided | D-202 item 8, D-208 §2.5, D-212 |
| **Authority for an act ≠ recording its fact** | Governance answers who may act; the owning context records the fact | D-167, D-194 |
| **A resignation ≠ a decision** | it is the member's own act (`ownAct`), with no Governance decision record | D-194, D-203, strategic result §8 |

## 4a. Geography, organisation units, addresses and belonging

**Five things kept apart:**

| Thing | Owner | What it is | What it never does | Establishing |
|---|---|---|---|---|
| **official reference unit** (`ReferenceUnitId`) | Reference Geography (central) | an entry of the shared official map; identity minted once, never reused; valid time and knowledge time (`knownAtPosition`) | decide belonging, membership, placement or authority | D-31, D-93 (v3.11) |
| **organisation unit** (ward, district, chapter, wing) | Organisation | the organisation's own structure; **its parents are set by the organisation, independently of official parents** | mirror the map by itself | D-106; §49.5 |
| **coverage reference** | Organisation | an immutable record `(ReferenceUnitId as relied on, knownAtPosition, valid period, basis)` stating which official area a unit serves | change with the map by itself | §49.6 |
| **address** | Organisation (the person's profile, as supplied) | where a person lives | **associate the person with a unit or committee** (an address may at most produce a **suggestion**) | **D-80**, D-79, D-101 |
| **belonging** | Organisation | person · unit · capacity · valid period · basis | arise from an address or the map; it arises only from an individual act, unless the organisation's rules expressly adopt a residence rule (§51, later release) | **D-79**, D-114, §50, §51 |

**When the map changes** (`RevisionPublished`):
- Organisation flags **CoverageReviewNeeded** (and AllocationReviewNeeded where an allocation rule applies).
- **Coverage, allocations and belonging stay as recorded until the organisation decides** (§49.6).
- **Units, members and committees are never moved automatically.**

**Release boundary:** all of this is designed and ratified; **using it is a later release (D-147).** In release 1, units and placement work without Geography. **A map change never changes who reads or receives** (D-106 §6; D-158 line 137).

**Deferred by D-93:** address ownership (the address model and its standardisation), L-4c, Q-2, Japanese classification, naming policies. **D-101's organisation-local address record stands.** U-2 (stewards' person reference) stays open.

## 5. Context relationships and contracts (accepted)

| Upstream → downstream | Business information | Contract | Pattern | Owner of the information | Establishing |
|---|---|---|---|---|---|
| Identity → every context | the actor in this organisation | `PersonRef` (K-8) | conformist | Identity | D-75, D-200 |
| Platform → every context | organisation location and status | routing | open host | Platform | D-173, D-193 |
| Membership → Organisation | coverage, `inForce`, `suspended` | **C1 events** (current views); **C2 `CoverageKnownAt`** (historical; complete or refused) | published language | Membership | D-73, D-161, D-162, D-209 |
| Organisation → Communication, Deliberation | belonging **with Membership's standing passed through at the same τ** | `MembersOfScopeAsAt`, `BelongingWithAncestorsAsAt` (K-4) | customer–supplier | **belonging: Organisation; standing: Membership** | D-161, D-210 |
| Governance → Membership, Organisation, Communication, Deliberation | whether the actor may act | AuthorityCheck (K-5), pulled at the act | customer–supplier | Governance | D-157, D-208 |
| Governance → Membership | an organ's exclusion or annulment; **an applied tribunal determination** | push (K-6), outbox → inbox, idempotent:<br>- **organ route:** the Resolution part (Governance completion C-1b);<br>- **applied-determination route:** the fact ref + the `RemedyApplied` reference with the Determination and remedy part, **no Resolution** (Governance completion §2.5, Annul route 2, accepted).<br>**Exclusion by this route is outstanding (D-212).** C-1b's own definition names only parts (a documentation gap, U-8) | published language | Governance | D-208, D-212 |
| Governance → Communication, Deliberation | the participation rule | `ParticipationRuleAsAt` (K-7) | customer–supplier | Governance | D-168 |
| Governance → Deliberation, Communication | **moderator and editor tenures** (functional offices held for a term). The forum is **open** only while a moderator tenure covers it, else read-only (D-150). The editor's act is checked by AuthorityCheck `send-newsletter(series)` | **the actor's own act:** AuthorityCheck `moderate(forum)` (K-5). **"Is any moderator tenure covering forum F at τ?": no named contract [UNR] (U-5)** | customer–supplier | Governance | D-150, D-157 (functional offices), D-158 §3.4 and §4 row 1, D-210 §8 |
| Contestation → Adjudication | a routed challenge | `ChallengeRouted` (published language) | published language | Contestation | ADR-T21 **[DEF]** |
| Adjudication → Contestation, Election (today); → Governance (D-208, D-212) | a determination | `DeterminationIssued` | published language | Adjudication | ADR-PL-01; D-202 item 8 **[DEF]** |
| Adjudication → Governance → Membership | a determination, then its application | Determination; applied fact; Membership's Annul or End with that provenance | published language | Adjudication, then Governance | D-202 item 8, D-208 §2.5 (Annul), **D-212 (exclusion: outstanding)** |
| Communication → mail provider | messages | provider interface | anti-corruption layer | Communication | D-210 §7 [EXT] |
| Reference Geography → Organisation | official references | `resolve(ref, p)` (K-9) | published language | Reference Geography | D-93 **[DEF]** |

**Direction rules (D-210 §4):**
- **Membership never reads Organisation.**
- **Communication and Deliberation never read Membership directly.** They consume Membership's authoritative standing **through Organisation's K-4**, **and** Governance's participation rule (K-7) and designations.
- **Governance never reads the map.**
- **No shared kernel; no shared persistence** (RA).

**Forum and newsletter eligibility: who owns each part** (summary; the detail is in D-210 §7–§8):

| Question | Answered by | Rule (when the organisation's rules are silent) | Establishing |
|---|---|---|---|
| may this person **read** forum F? | Organisation (belonging, K-4) | effective belonging in F's unit or a unit below it. **Lapsed and suspended members still read** where they belong. **Workers and guests** read where placed. **Applicants only if placed as Guest** | D-152, D-153, D-161, D-207 |
| may this person **post** in F? | Governance (participation rule, K-7); standing via K-4 | reading eligibility ∧ forum open ∧ the participation rule. **Default (D-161, D-153): only a member in force and not suspended; workers, guests and applicants never post by default.** An organisation's written rule may provide otherwise, including naming other capacities (D-168; MI-14) | D-153, D-161, D-168 |
| is F **open**? | Governance (moderator tenure) | a moderator tenure covers F at the time; otherwise read-only | D-150 (contract: U-5) |
| who **receives** an issue? | Organisation (audience, K-4) + Governance (K-7) + Communication (opt-outs) | members of the scope at one τ ∧ the participation rule (default: in force and not suspended) ∧ not opted out of the series. **plus the other capacities the series decision names, by their own valid belonging, without the standing filter (D-213)** | D-57, D-151, D-158, D-161, D-168, **D-213**, D-210 §7 |
| may this person **moderate** or **send**? | Governance (AuthorityCheck) | a valid tenure at the act's time | D-150, D-157, D-58 |

## 6. Release scope

| Release 1 (D-146, D-147, D-160, D-163) | Later |
|---|---|
| Identity, Platform (tenancy) | demo mode |
| Organisation: units; **placement by individual act**; root-unit default (D-114, D-172); non-member capacities by `place-person` (D-207) | **area allocation rules (§51); use of Reference Geography** (D-147) |
| Membership on the **ledger only** (D-160): **admission, with a term where the organisation's rules set one, or open-ended (no platform default term, D-163)**; renewal; suspension and restoration; ending; corrections; scheduled resignation (D-204) | legacy-store organisations, after cutover (D-160) |
| Governance: thin slice + completion (D-157, D-208) | **tribunal exclusion (D-212: needs an Adjudication change request and a Governance addition)** |
| Newsletter to members of the scope (the participation rule applies), **and the other capacities the series decision names** (D-57, D-161, **D-213**) | outside subscribers (D-57) |
| Unit forums (D-152) | committee forums (D-56, D-152) |
| — | Election, Voting, Finance, Contestation and Adjudication |

## 7. Cross-cutting rules

| Rule | Establishing |
|---|---|
| **Tenancy and isolation:**<br>- one central database plus one database per organisation;<br>- staff see business content only by the organisation's grant per case, recorded and reported (INV-ACCESS-1) | D-164, D-166, D-170 |
| **Authority:**<br>- only from the organisation's rules or designations;<br>- **silence means the act waits**;<br>- **`Undetermined` ≠ No**;<br>- product defaults are labelled and overridable | D-1a, D-167, D-168, D-171, D-172, D-181 |
| **Time:**<br>- one τ per cross-context operation, complete or refused;<br>- each source has its own knowledge boundary;<br>- **one clock per recorded stream: the clock of the database that holds the stream:**<br>  - Membership's ledger: **the organisation's own database clock** (`clock_timestamp()`), for every writer and the `closedThrough` step (D-209);<br>  - Governance's DecisionLog: the organisation's database clock (GD-12);<br>  - each Geography stream: its own database clock (PO-7);<br>- **no global clock across the central and organisation databases:** contexts keep separate knowledge boundaries; consistency comes from passing one τ (D-177 M-7);<br>- refuse on regression; never adjust | D-73; D-177 (M-7); **D-209**; GD-12 (D-208); v3.11 PO-7 |
| **Nothing takes effect after its recording,** except End(resignation) executed from a scheduled declaration | D-5(b) as clarified by **D-205** |
| **A delayed execution never leaves the person eligible.** Example: a resignation scheduled for 1 December, executed late.<br>- Membership's `closedThrough` **cannot pass the start of 1 December until that End is committed** (D-209). The boundary is **per organisation**, so it holds back **every** Membership answer of that organisation at a later τ, not only this person's (D-209 §4, availability).<br>- C2 refuses (`BOUNDARY_NOT_CLOSED`): **never "still a member", never "not a member"** (D-210 §11).<br>- **What is refused is whatever depends on Membership at that τ:**<br>  - membership-dependent belonging (B-2);<br>  - good standing.<br>  So member-based reads, posts and every newsletter send that needs those answers are denied or refused until the End is recorded.<br>- **What does not depend on Membership is not thereby forbidden:** worker and guest belongings count by their own validity (§50; MI-14). **Whether Organisation answers those parts while C2 refuses, or refuses the whole query, is not stated (U-9).**<br><br>**An accepted mechanism, not a new rule; proofs unexecuted:** LC-4, MI-10 (B-05) | D-204, D-205, **D-209** (`closedThrough` step (a)); D-73; D-103; §50 B-2; D-210 §5.2, §11 |
| **History is append-only:**<br>- originals are never changed;<br>- Correct / Void / Annul are new facts;<br>- **a pending challenge changes nothing** | RA §9; D-71, D-202, D-206 |
| **Provenance:**<br>- every authority answer records six fields plus a basis;<br>- every decision record carries D-202's eight guarantees | D-202, D-208 |
| **Privacy:**<br>- opaque per-organisation `PersonRef`s;<br>- no cross-organisation disclosure;<br>- no linking by matching email or name | D-75, D-101, D-113 |
| **Refusal:**<br>- a missing authoritative answer is a refusal, **never a "no"** (for example "not a member");<br>- never a partial answer | D-73; C2 spec K-5 |
| **One owner per fact; no duplicated ownership** | D-173 §1.2 |

## 8. The D-212 tribunal-exclusion route

**Status: strategic responsibility accepted (D-212). Not available.** It needs:
- **(a)** an accepted change request to the frozen Adjudication context (one case kind);
- **(b)** an accepted Governance addition (one exclusion route).

Until both exist, **such exclusions wait (D-167).**

**The route:**
1. The body the rules name **applies to the tribunal.**
2. **Adjudication determines.**
3. **Governance applies** the Determination as an applied fact. No Resolution is created.
4. **Membership records `End(exclusion)`** with the Determination and its application as decision provenance. **Membership does not judge the Determination's validity.**
5. Interim suspension uses the existing Suspend act kind (D-163).

**[LQ]:** §10 (4)–(5) PartG, to be confirmed by counsel.

## 9. Unresolved items (no authority invented)

| # | Item | Evidence | Needed by |
|---|---|---|---|
| U-1 | **where Contestation and Adjudication data is stored** (central or per organisation): D-164's list does not name them | D-164; D-173 §1.2 | the D-212 Adjudication change request |
| U-5 | *(proposed closure: blueprint T-1, not accepted)* **the contract by which Deliberation learns that a moderator tenure covers a forum at τ** (forum open or read-only). Owner (Governance) and rule (D-150) are accepted; **the contract is not named** (AuthorityCheck answers only for a given actor) | D-150; D-158 §3.4, §4; D-210 §8 | the Forum tactical slice (an existing-decision documentation gap, not a business question) |
| U-6 | **Contestation's Challenge is scoped to one Election** (ADR-UL-01, a tactical language decision). **The accepted decisions already require more:** D-71 applies to challenged decisions under D-65 and D-70, to moderation decisions (D-53) and to election results; D-53 routes moderation reviews through Contestation (its default reviewer replaced by D-167). **So challenges beyond elections are already in Contestation's scope; only its language (the Challenge target) must be extended** | ADR-UL-01; D-53, D-71, D-167 | **tactical:** a Contestation change, before any non-election challenge is offered |
| U-7 | **Newsletter audience for non-member capacities.** D-57: "members and other known participants". D-158 `Series`: other capacities "only if the decision says so". **D-161 then amended the D-158 audience rule:** "only members in good standing … receive newsletters", overridable by written rules (D-168). **The record does not say whether D-161 removed the D-57 path for capacities named by the series decision** | D-57, D-158 §2 `Series` and §2 D-57 row, D-161, D-168 | **Decided: D-213** (option A). Remaining: the Newsletter tactical slice (an audience query by capacity; not in K-4) |
| U-8 | **C-1b names only Resolution parts,** while Governance completion §2.5 already pushes an applied determination (Annul route 2). The D-212 Governance addition must define the push of an applied exclusion determination **on that existing route-2 pattern** | Governance completion §2.5, §4 C-1b; D-212 | the D-212 Governance addition (contract; no new rule) |
| U-9 | *(proposed closure: blueprint T-2, not accepted)* **K-4 refusal granularity:** when C2 refuses, does Organisation refuse a whole person or scope query, or answer the non-member (not membership-dependent) parts? "Refused, never partial" (K-3) and "Organisation refuses" (D-210 §11) are stated for whole answers only | D-210 §4, §11; §50 B-2 | Organisation tactical slice (contract detail) |
| U-10 | **Intake of a tribunal exclusion application (D-212):** an application is **not a challenge** (no existing decision is contested). Accepted Adjudication intake is `ChallengeRouted` from Contestation plus evidence inputs (ADR-T21; EPIC-002 COL-1 … COL-3). Two routes fit the record (Appendix F) | D-212; ADR-T21; EPIC-002 map | **Principal decision within the D-212 change request**; not needed for ratification |
| U-2 | **Geography's platform-level person reference** for its stewards (I-TDD-02 does not supply it) | v3.11 §8; IR-GM-01 C-05 | the Geography release |
| U-3 | legal: newsletter basis; retention; resignation effective time (including any statutory right to leave immediately); `PersonRef` classification; §10 PartG | D-151, D-194, D-201, acceptance act §3 | before launch |
| U-4 | all proof suites **NOT EXECUTED**:<br>- Platform (53);<br>- ISO-1 … ISO-11, INV-ACCESS-1;<br>- GC-1 … GC-17;<br>- LC-1 … LC-6;<br>- MI-1 … MI-14;<br>- A1 … A3;<br>- Geography PO-1 … PO-8 | each acceptance | before building each slice |

## 10. Source fingerprints (calculated 2026-11-12)

**Change control (D-173 §2):** this table **proposes** the re-recording. Under D-173, fingerprints are re-recorded only by the next acceptance act, here the ratification of this baseline.

| Accepted source | Decision | Accepted at | Current | Difference since acceptance |
|---|---|---|---|---|
| Ratified Membership architecture | RAT | — | `2ada0ca5fee9b6b32c6e3338eaf4cee855321ab2f4d80c21d84a77023e0ab865` | — |
| CR-01 | D-73 | — | `04c3aeb1c768cf26f9d1ca38d27d0cd548b86f8a56924dfe9c547606dcd4b6ba` | — |
| CR-02 | D-161 | — | `a3c2326a6f3db25b2a07c6e74e4d558ee84415ea5e66bc059057ae9d7e31b8e6` | — |
| Organisation §49–§51 (governance model file) | D-156 | `1e9eaf31…` (D-173) | `1e9eaf31ee575b51f6ad5d4a9f27baa93946a7faed52f41293d3f81f9a7be79c` | **none** |
| Governance thin slice | D-157 | `eea71356…` (D-173) | `e36896c43b6cb852316faec6855c2b9d001aa1fd686ea7b3a5cdb1cb154ee10f` | addition notes D-190, D-196, D-208 (listed in those entries) |
| Newsletter and Forum design | D-158 | `22929c16…` (D-173) | `22929c16ee32b44a86d887bffcc5317e550facaef1629b7a723e52a36ee24d6c` | **none** |
| C1/C2 specification | D-162 | `5ea99458…` (D-173) | `193b4102b1e6a1e5b30775d6248ec1ee13a57b36e41395bf92170b8514218a48` | amendment pointer (D-209) |
| Renew / Suspend / Restore design | D-177 | `2ec5028f…` | `c631cc4a4b228fdda5402227d62248ca54f03b6c2dfeb60bfd945411ebc9991e` | a status line only (accepted text verified to hash `2ec5028f…`) |
| Multi-database boundary design | D-178 | `507a1290…` | `430ed58f780ae9ef27523966a45002f910c3c00b8a56cbb9753529fa7e823704` | a status line + **a conformance note (D-166 / D-170 / D-175) not listed in any decision entry** (accepted text verified to hash `507a1290…`); see E-1 |
| Platform tenancy design | D-193 | `e3772d2e…` | `245db3e69fdd1e10f1d6251ff8071733cf2b82bfd0560d8bee6718a54e258696` | status note (listed in D-193) |
| Identity I-TDD-02 | D-200 | `b6d54798…` | `2b5f6e92374c08357db47d1a3a9dcde51b53316602c657fc3ddf0f5f62a1d018` | status note (listed in D-200) |
| Governance completion | D-208 | `a29821da…` | `697f174e893051283025cbfb6c88b1ea074b2487725c6d3158976f26a0b79398` | status note (listed in D-208) |
| Ledger-clock amendment | D-209 | `a6d9b05f…` | `6ac7e192b260e8abc5712bd7bb8b6c3a1baa75f0a4ba9af5ddf6952f39edd4b5` | status note (listed in D-209) |
| M-TDD-01 | D-210 | `b4aba168…` | `56c7840923531ca5d176e7caf3850ceed3ae74ceb273ba71af5db6563ef26f0b` | status note (listed in D-210) |
| Reference Geography ratification act | D-93 | — | `54cf9bbabd1d99964015bdc59e349ee6fc369d03040df5aa14e4210530f164c3` | — |
| Reference Geography strategic baseline | D-93 | — | `2a629a8f934e6c1d680624f1d7dd076dfa986e14246c9a4036efb442687ea6c6` | — |
| Reference Geography tactical design v3.11 | D-93 | — | `8b7300d94616432b0efff4f3f11cfec14e68a2884da53bdd678c286d5dde6983` | — |

---

## Appendix A. Reconciliation: which decisions support each section

| Section | Supporting decisions and accepted designs |
|---|---|
| §1 Purpose | D-100, D-1a, D-167, D-110, D-111, D-113, D-164, D-200, D-211 |
| §2 Classification | D-211; BDR-07, BDR-08, BDR-09; D-31 |
| §3 Contexts | D-173 §1.2–§1.3 and its accepted table; D-31, D-50, D-51, D-67, D-72, D-75, D-93, D-101, D-103, D-106, D-142, D-144, D-146, D-147, D-150 … D-158, D-161 … D-163, D-177, D-178, D-193, D-200, D-208, D-210 |
| §4 Ownership | D-73, D-103, D-142, D-167, D-168, D-194, D-202, D-203, D-208, D-212 |
| §4a Geography, units, addresses | D-31, D-79, D-80, D-93, D-101, D-106, D-114, D-147; Organisation §49.5, §49.6, §50, §51 |
| §5 Relationships | D-210 §4 (K-1 … K-9, direction rules); D-73, D-161, D-162, D-168, D-209 |
| §6 Scope | D-146, D-147, D-160, D-163, D-57, D-56, D-152, D-207, D-212, D-213 |
| §7 Rules | D-164, D-166, D-170, D-1a, D-167, D-168, D-171, D-172, D-181, D-73, D-177, D-205, D-209, D-202, D-206, D-71, D-75 |
| §8 Tribunal route | D-212, D-202 item 8, D-163, D-167 |

## Appendix B. Stale or contradictory documents: corrections to apply on ratification

**Nothing is changed before ratification**, so that this proposal does not act as if it were accepted.

| Document | Contradiction | Exact correction |
|---|---|---|
| `docs/publicdigit/architecture/context-map.md` (PROPOSED, 2026-11-02, `614e1bf0…`) | line 88: Platform owner "assignment to a named context not established" (D-173 established it); line 95: "Communication: forum" (forums are Deliberation's, D-158); no Deliberation, Platform or Identity rows as accepted contexts | **prepend a supersession banner:** "Superseded as the current context map by `publicdigit-strategic-ddd-baseline.md` (ratified D-nnn). Kept as historical evidence." **No body edits.** |
| `docs/publicdigit/architecture/strategic-design-status.md` (`6ed98c78…`) | §2: organisation and governance "IN PROGRESS. Not complete"; §2: "only Membership's boundaries are ratified; the others are candidates"; §4 lists O-6 and O-14 as open (closed by D-142 and D-144, and by D-143); O-7 … O-10 are not re-assessed here | **prepend a status banner:** "Strategic baseline accepted (D-173), consolidated as baseline v1.1 (D-nnn). Sections §2–§6 are historical." |
| decision record D-210 (5), second bullet | the D-156 "drift" | **already corrected** by §11.110; no further action |
| post-correction review P-7; acceptance act §3 last line | "context map and status record lag this act" | **closed by ratification;** recorded in the ratifying entry, not in those texts |

## Appendix C. Evidence gaps found during consolidation

| # | Gap | Effect | Proposed handling |
|---|---|---|---|
| E-1 | the multi-database design's conformance note (after D-178) is **not listed in any decision entry**, against D-173's change control | its text is in the repository but not traceable to a decision | **Principal confirmation required** (Appendix E) |
| E-2 | U-1: Contestation and Adjudication storage | none for release 1 | decide in the D-212 change request |

## Appendix D. Principal decisions

- **The consolidation adds no business rule.**
- **One narrow confirmation is required: E-1** (Appendix E).
- **U-7 clarified by D-213** (2026-11-12).
- **Reserved for the D-212 change request:** U-10 (intake).
- **Then ratification**, which would also re-record the fingerprints in §10 and apply the two banners in Appendix B.

## Appendix E. E-1 comparison: the multi-database conformance note

**The exact text** (multi-database boundary design, after the D-178 status line):

> **Conformance note (2026-11-11, implements D-166 / D-170 / D-175 via Platform design revision 4; no meaning change):**
> - **ISO-11:** platform operators reach an organisation's data **only through a case-grant session** (operations channel, separate from member and workspace requests); **ISO-3 is unchanged for those requests**.
> - **`schemaState` adds `migrating`** (unroutable).
> - **Status `pending`** precedes `provisioning`; no technical operation before counterparty verification and the service-operation grant.
> - **The registrar's first claim is the one bootstrap exception** to §4's officer and AuthorityCheck rule.

**Point by point:**

| Item | What D-166 / D-170 / D-175 say | Beyond them? | Where it is accepted | Verdict |
|---|---|---|---|---|
| **ISO-11** case-grant session; separate operations channel | D-166 (2): content access needs a per-case grant, recorded and reported; D-170: the pending grant for one case. **Neither names a session or a channel** | **yes: a mechanism** (session bound to a grant; separate channel; no standing human credentials) | **Platform rev 11 H-3 (line 417) and gateway (lines 511, 574, 661): accepted D-193** | consistent; **adds no rule beyond D-193** |
| `schemaState` adds **`migrating`** (unroutable) | none | **yes: a state and a routing restriction** (fail closed) | **Platform rev 11 M-8 (lines 427, 647): accepted D-193** | consistent; **adds no rule beyond D-193** |
| **`pending`** before `provisioning`; no technical operation before verification and service grant | D-175: stays `provisioning` until verified; D-166 (1): technical operations granted at joining | **yes:** the `pending` status | **D-185** (`pending` is a platform status; "changes no behaviour"); Platform rev 11 (lines 371, 404, 543): D-193 | consistent; **= D-185** |
| **registrar's bootstrap claim:** the one exception to the officer / AuthorityCheck rule | none | **yes: an exception to a rule** | **Platform rev 11 M-3 (lines 422, 550): accepted D-193**; I-TDD-02 bootstrap claim kinds (D-200) | consistent; **adds no rule beyond D-193 / D-200** |

**Finding:**
- **Every item is an accepted rule, but not of the decisions the note cites.** Three items go beyond D-166, D-170 and D-175. They were accepted later by **D-193** (Platform rev 11) and **D-185**.
- **When written, the note cited an unaccepted design** ("Platform design revision 4"). D-193 has since accepted those rules.
- **It adds nothing that is not already accepted.**

**Recommendation:**
- **Confirm the note in the ratifying act** as a conformance note implementing **D-185 and D-193** (H-3, M-3, M-8), and through them D-166, D-170 and D-175.
- **Correct its attribution** there, not by editing the note.
- **No separate business decision.**

## Appendix F. Open choices left by the accepted decisions

### U-7: do non-members named by a series decision receive its newsletter?

| | |
|---|---|
| **Question** | a committee creates a series and names, besides members, other capacities (for example staff or guests) as recipients. **Do they receive it?** |
| **Why** | D-57 says "members and other known participants". D-161 later says "only members in good standing … receive newsletters" and amends the D-158 audience rule. **It does not say whether that removed the D-57 path** |
| **Option A [R]** | **D-161's standing filter applies to the Member capacity.** Capacities the series decision names receive by their own valid belonging (they have no membership standing to filter). Members still need good standing unless the written rules say otherwise (D-168). **Keeps D-57**, and matches party practice: staff and supporter newsletters are common |
| **Option B** | **D-161 replaced the audience:** only members in good standing, unless the organisation's written rules provide otherwise (D-168). A series decision alone cannot add non-members |
| **Consequence** | A needs an Organisation audience query by capacity (K-4 returns members) in the Newsletter slice. B needs nothing new, but staff and guest newsletters require a written rule |

### U-10: how does a tribunal exclusion application reach Adjudication? (for the D-212 change request)

| | |
|---|---|
| **Option 1 [R]** | **Referral from Governance.** The body the rules name decides to apply, as a Resolution part (category A, D-202). Governance sends the referral to Adjudication. **Contestation stays the context for contesting existing decisions.** Adjudication gains one intake besides `ChallengeRouted` |
| **Option 2** | **Through Contestation:** widen "Challenge" to "a matter brought before the tribunal". **This changes Contestation's ubiquitous language and responsibility**, a strategic change to a frozen-adjacent context |
| **Consequence** | option 1 keeps both languages clean and matches how party boards apply to their tribunal; it changes only Adjudication's intake (the change request D-212 already requires) |

---

**Status note (2026-11-12, D-214):**
- **Revision 4 is RATIFIED** as PublicDigit's Strategic DDD Baseline v1.1 (decision record §11.113).
- **Ratified text:** this file at SHA-256 `8e74bb6dc45964b6090834cb09c03ac4f71d6b0988ff2fa189310251142e8080`. **This note is the only later change.**
- **The decision record still governs** where the two differ.
- **It is the current context map;** `context-map.md` is superseded.
- **Still open:**
  - U-5 and U-9, until T-1 and T-2 are accepted;
  - U-1, U-2, U-3, U-4, U-6, U-8, U-10, as listed in §9.
- **No implementation authorised.**

**Change-control note (2026-11-12, D-217):**
- **§2's classification is refined by D-217.** Core is now legitimate, participatory self-governance:
  - Governance;
  - Voting;
  - Contestation and Adjudication;
  - participatory motion-making;
  - financial transparency;
  - dues and standing.
- Payment processing is **generic**; the Finance ledger of obligations and payments is **supporting**.
- **No boundary or ownership changes.** §2 above is left as ratified.
- **New open question** for the Finance slice: striking off for non-payment (D-217).
- **Fingerprint re-recorded at the next acceptance act** (D-173 change control).

**Change-control note (2026-11-12, D-218):**
- **The PublicDigit Product Principles are adopted** (`publicdigit-product-principles.md`) as product-design criteria beside this baseline, including the guarantees note: PublicDigit never breaches vote secrecy, isolation, append-only history or refusal-when-unestablished, even at an organisation's request; it declines that function instead.
- **They add no rule to this baseline,** change no ownership and add no release-1 scope.
- **Fingerprint re-recorded at the next acceptance act** (D-173 change control).

**Change-control note (2026-11-12, D-220):**
- **§2 / D-217 classify business capabilities, not bounded contexts.** A core capability may involve several contexts, each keeping its ownership.
- **Dues and standing follows D-132's two arrangements** (A or B, as the written rules choose).
- **Product principles revision 3** (principle 0 levels; principle 8 resignation).
- **No boundary or ownership changes.** Fingerprint re-recorded at the next acceptance act (D-173).

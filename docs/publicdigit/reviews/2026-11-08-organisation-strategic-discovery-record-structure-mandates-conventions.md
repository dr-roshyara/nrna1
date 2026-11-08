# Organisation strategic discovery: structure, mandates and conventions, discovery record

**Status:** **PROPOSED discovery evidence. Not accepted, not ratified.** This record preserves an interactive discovery conversation between the Principal and Claude (Architecture Session A). It defines no bounded context, no tactical model, no ADR and no implementation. It changes no existing decision. The ratified Membership Architecture and the frozen Adjudication workstream are untouched.

**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/` convention.
**Baseline:** branch `election-flow-updated`, HEAD `f34fcf979`.
**Recorded by:** Claude (Architecture Session A), on the Principal's instruction "Yes, write the discovery record and session log" (8 November 2026). The recorder records; it does not decide.
**Roadmap phase:** Phase 1–2 discovery (Business-Domain Baseline toward Strategic DDD), `docs/plans/20261108-0303-organisational-baseline-to-tactical-design-roadmap-plan.md` Appendix B method (one question per turn).

---

## 0. How to read this record

| Label | Meaning |
|---|---|
| [D] | Formal decision by the Principal in the named capacity |
| [PK] | The Principal's practical knowledge of organisational practice. Valuable, but becomes [F] only with documentary support (an actual constitution or Governance Rules) |
| [F] | Documentary or code fact (code facts show what the software permits, not what practice is) |
| [I] | Inference by the recorder |
| [P] | Proposal. Not accepted |
| [H] | Hypothesis. Unconfirmed |
| [OQ] | Open question |
| [R] | Risk |

**Source quality.** Some answers reached the recorder only through a reviewer's summary pasted by the Principal, not in the Principal's own words. These are marked *(as reported)*. Answers the Principal typed directly are marked *(direct)*. The Principal may correct any *(as reported)* item.

**Scope of [PK].** The findings describe the Principal's experience of how organisations of this kind (political party, NRNA) usually operate. None is [F] for any particular organisation until its actual constitution and Governance Rules are read (planned NRNA documentation request, roadmap Appendix A).

---

## 1. Decisions made during this discovery

| # | Decision | Capacity / date | Notes |
|---|---|---|---|
| D-1 | PublicDigit supports multiple organisations, each with its own constitution, its own constitutional versions and its own governance rules. | [D][PO], 8 Nov 2026 | Accepted in conversation on the recommendation of a pasted analysis |
| D-1a | Qualifier: the rules come from each organisation's own competent acts. Platform configuration creates no authority (authority meaning C). | [D][PO], 8 Nov 2026 | |
| D-1b | Qualifier: there are no platform-wide default constitutional rules. Shared content can only be a template an organisation adopts by its own recorded act. | [D][PO], 8 Nov 2026 | |
| D-1c | Qualifier: D-1 sets a direction, not a mechanism. YAML rules, a "Constitutional Rules Engine" and the proposed five-part split are **not** adopted. | [D][PO], 8 Nov 2026 | |
| W-1 | Working term **"territorial assignment"** replaces "geographical affiliation" during discovery, because "affiliation" already has an adopted meaning (EM-VOC-002, `docs/publicdigit/architecture/context-map.md:72`). The canonical term is decided later. | Working agreement, 8 Nov 2026 | Not a ubiquitous-language decision |
| W-2 | Working term **"Highest Committee"**: the committee at the top of an organisation's committee structure, which governs between conventions and issues the Governance Rules. It sits **below** the General Convention (the highest *committee*, not the supreme organ). Each organisation keeps its own name as a label: political party — "Central Committee"; NRNA — "International Coordination Council". "Central Committee" is not used as the general term (it is one organisation's name, and the `committees` table already has a type value `central` [F code]). | Working agreement, 8 Nov 2026 (Principal: "Yes, Highest Committee; NRNA calls it International Coordination Council") | Not a ubiquitous-language decision. In this record "Highest Committee" replaces "Central Committee" wherever the role is meant; the verbatim answers in §6 keep the original words |

**Accepted as direction, not formal decisions [P]:**
- P-1: examine the existing Governance capabilities (ACT §11; D-4 adapter `app/…/InterimRoleAuthority.php:26-51`; `AuthorityEvidence`) before adding any new capability. No separate rules engine is assumed.
- P-2: a software evaluation result means "consistent with the interpretation the organisation approved (rule set version *n*)", never "legally valid". `UNDETERMINED` applies where no approved interpretation exists; a residual default rule (§2.2) is distinct from a missing rule.
- P-3: the organisation, not the platform operator, approves its own constitutional interpretations (requires discovery and formalisation).
- P-4: record decisions first (provision relied on, deciding organ, decision date, effective date); add evaluation in advance only when evidence justifies it.

**Not a decision:** the PHP / Laravel stack is an existing fact [F].

---

## 2. Findings

### 2.1 Two layers of rules

- [PK] The Congress / General Convention adopts the constitution. *(as reported)*
- [PK] The constitution sets the rules for the organisation, including rules for the Highest Committee and subordinate committees, and says how subordinate committees are set up. *(as reported)*
- [PK] The General Convention defines the recognised **committee types**. *(as reported)*
- [PK] The Highest Committee governs between Conventions within the constitutional framework and issues its own **Governance Rules**. *(as reported)*
- [PK] The Highest Committee's Governance Rules decide which body may approve an individual member's application for a territorial assignment. *(as reported; reported wording "should specify" — whether NRNA's rules actually do so is [OQ])*
- [I] Rules come in two layers made by different organs: constitution (General Convention) and Governance Rules (Highest Committee). Through its rules the Highest Committee can delegate decisions to lower bodies.
- [I] Every structural or assignment decision should cite its basis, which may be a constitutional provision or a Governance Rule. Both layers must be versioned so the rules that applied at the time of a decision can be reconstructed.

### 2.2 Residual competence

- [PK] A matter is reserved for the General Convention when the constitution says so explicitly. Not every structural change needs the Convention. *(as reported)*
- [PK] Otherwise the Highest Committee can decide structural matters by its own decision (residual competence). *(as reported)*
- [I] Four bases of competence must stay separate:

| Basis | Meaning | Competent organ |
|---|---|---|
| Explicit grant | The constitution gives the power to the Highest Committee | Highest Committee |
| Explicit reservation | The constitution reserves the matter | General Convention |
| Residual default | The constitution is silent on which organ decides | Highest Committee, under the residual rule ([PK]; source clause not yet found) |
| Unclear | Organ clear, but procedure, majority, timing or ratification not | Open; no default |

- [I] Residual competence answers *which organ*; it does not answer procedure, quorum, majority, effective date or ratification.

### 2.3 Structure: levels, units, committees

- [PK] The Highest Committee is the uppermost geographic-level committee; it defines subordinate levels and their names. These mostly follow official country levels but differ between organisations. NRNA has a worldwide baseline and its Highest Committee represents the worldwide organisation. *(as reported)*
- [PK] By written decision, the Highest Committee creates, modifies and dissolves **units**, **committees**, and **levels** (e.g. adding a Zone between District and Province), provided this does not contradict the constitution. *(as reported)*
- [PK] When a unit is dissolved, its committee ceases to exist automatically; no separate decision is needed (Option A). *(as reported)*
- [PK] When a district is divided, the original committee ends and the Highest Committee establishes two new committees. *(direct)*
- [I] Official administrative geography (state reference data) and organisation-defined territorial structure have different owners and purposes.
- [PK] V-1 ("Geography Context define the committee level") meant the organisation's own territorial structure (C4), not the official administrative map (C5). Keep C4 and C5 separate: different owners (organisation vs state), change triggers and lifecycles; they may be related (an organisation may adopt official boundaries) without being the same thing. *(direct, V-22)*
- [P] Whether C5 needs its own bounded context or is a reference-data capability is undecided. The existing `app/Contexts/Geography` is **not** renamed and its boundary is not decided (Principal qualification, V-22).
- [I] A committee belongs to exactly one unit and does not carry over into a new unit. Creating a unit and establishing its committee are separate acts; dissolution ends the committee as a *consequence* of the dissolution decision.
- [I] Lifecycles:

| Concept | Created by | Ends when |
|---|---|---|
| Committee type | General Convention | The constitution changes |
| Level | Highest Committee | Highest Committee decision |
| Unit | Highest Committee | Dissolved or divided |
| Committee | Highest Committee establishing act | Its unit ends or is divided |
| Mandate | Appointment or election | The next mandate begins (§2.4) |

- [PK] NRNA's Highest Committee is called the **International Coordination Council**. *(direct, with W-2)*
- [PK] NRNA: the top committee is level zero; below it continents, then countries, then states, then cities; all of these must be defined by the highest committee. *(direct, V-16; read as "international committee" and "continents" — reading to be confirmed)*
- [I] The worldwide scope is the **top level of a single hierarchy** (level 0), not a separate kind of scope. This answers open question 10 for NRNA, subject to confirmation of the reading.
- [I] The continent level is organisation-defined and has no counterpart in any national administrative geography; country, state and city may correspond to official geography in each country. This supports the earlier separation of official reference geography from organisation-defined territorial structure.
- [OQ] ~~Whether the levels below country may differ from country to country.~~ Answered by V-17:
- [PK] The Highest Committee defines the rule; the structure should be similar everywhere. Exceptions are possible but must be mentioned in the Highest Committee's **rule log**. *(direct, V-17)*
- [I] A standard level pattern applies to all branches by default; a deviation is valid only as an explicitly recorded exception. Uneven depth is therefore possible but must be traceable to a recorded rule.
- [I] "Rule log" is the first mention of a register in which the Highest Committee records its rules and exceptions. Its form (minutes, numbered resolutions, consolidated rulebook) is [OQ]; it is the likely documentary source for turning [PK] into [F].
- [PK] The Highest Committee records its rules both as resolutions in meeting minutes and consolidated into a rulebook. *(direct, V-18)*
- [I] Two kinds of record: the **resolution** (the dated act by which a rule is made or changed) and the **rulebook** (a consolidated statement of the rules currently in force). A decision's basis could cite either the resolution or a rulebook edition.
- [I] Observation only: resolutions resemble a dated sequence of acts and the rulebook a consolidated view derived from them; this may map onto the existing `ruleRef` / `policyVersion` placeholders (`InterimRoleAuthority.php:26-51`). No design implied.
- [PK] If a resolution and the rulebook disagree, the resolution prevails; the rulebook is only an editorial summary. *(direct, V-19)*
- [I] The resolution is the authoritative source of a rule; the rulebook has no authority of its own. A decision's basis should cite the resolution; rulebook text must be traceable back to the resolution(s) behind it.
- [I] General rules (Governance Rules) and individual acts (creating a unit, establishing a committee, assigning members, appointing interim office holders) are all made the same way: as resolutions of the Highest Committee. The resolution may be one carrier for both normative rules and particular decisions. To be confirmed.
- [OQ] ~~Which record prevails if a resolution and the rulebook diverge?~~ Answered by V-19. Can the rule in force on a past date be determined? Is the rulebook shared with members? (V-18 did not address the last two.)
- [PK] Besides geographic committees, wings exist; the Highest Committee creates them, with their own levels. *(direct, V-20)*
- [I] Wings are a second kind of committee structure, created by the Highest Committee (consistent with its residual competence). Findings tied to territorial units (a committee belongs to exactly one unit; a committee ends with its unit) are so far established only for **geographic** committees; whether they hold for wing committees is open.
- [OQ] "Own levels" may mean (a) wing committees sit on the same territorial units (e.g. a youth committee for District X), or (b) a wing has its own level structure independent of the geographic one. Also open: whether members join wings in addition to their territorial assignment (V-20 did not address this).
- [PK] Whether wing committees sit on the geographic units or on their own divisions, and what happens to them when a unit is dissolved, should be defined in the **Highest Committee rule book**. *(direct, V-21; term confirmed by the Principal: "highest committee rule book". Read with V-19: where a resolution and the rule book differ, the resolution prevails)*
- [I] There is no universal answer: each organisation decides. Committee placement therefore cannot be hard-wired to the geographic hierarchy; an organisation's rules may place a wing committee on a territorial unit or in a separate wing hierarchy.
- [PK] A wing (e.g. the youth wing) is a branch of the organisation with its own committees at each level. *(direct, V-26)*
- [I] A wing is organisational **structure** (a branch), not a kind of committee; its committees are organs (PO-DR07-01) related to the structure through `JurisdictionAssignment`. "At each level" suggests a wing committee is placed by both its wing and a level/unit (e.g. the youth committee for District X); the exact placement remains a Highest Committee rule-book matter (V-21).
- [PK] The Highest Committee first creates the wing as a whole organisational branch, including its levels; committees are then established within that branch. The wing exists independently of its committees. *(as reported, V-27 — the Principal's answer given within a pasted analysis)*
- [OQ] The exact sequence of formal acts (whether the wing-creation resolution defines all levels, or later resolutions add them) is **not** confirmed; the Principal asked that it not be recorded as [PK].
- [I] A wing has an identity and lifecycle of its own in the organisational structure, independent of its committees — the same pattern as a territorial unit (created first; committee established by a separate act, §2.3).
- [PK] A wing level or a territorial unit can exist temporarily without a committee; the Highest Committee acts for it until a committee exists. *(direct, V-28)*
- [I] Structure and organs are independent: a unit (territorial or wing) may exist with no committee. Competence for a unit without a committee falls to the **Highest Committee** (not to the next higher committee). Answers open question 6 (a divided unit's successors before their committees are established). V-28 covers a committee not yet existing; a committee that exists but is not functioning is not covered.
- [PK] For a unit without a committee, the Highest Committee can assign who takes the responsibility. *(direct, V-29 — refines V-28)*
- [I] The Highest Committee holds the responsibility by default and may delegate it (to a body or a person) by its own decision. Delegation is a Governance matter (competence); if the assignee is a person holding a position, the resulting mandate falls under Appointment (Authority). Whether the assignee may be another committee or only a person is [OQ].
- [F code] Wings are modelled today as committee types (`youth_wing`, `women_wing`; governance map `:57`). Implementation evidence only; diverges from V-26.
- [I] Observation: the `committees` table has its own `parent_committee_id` alongside a geographic reference [F code]. If committees follow units, two hierarchies could drift apart. Observation only.

### 2.4 Office holders and mandates

- [PK] Each organisation defines how committee office holders are selected. The two common approaches are direct appointment by the Highest Committee, or interim appointment followed by election. Within an organisation the practice does not differ by level. *(as reported)*
- [PK] An interim committee has the same authority and responsibilities as an elected one. *(as reported)*
- [PK] When elected office holders take over from interim ones, it is the **same committee**; only the office holders change. *(as reported)*
- [PK] The elected office holders take over automatically; no Highest Committee decision is needed. A handover of documents, records, assets and pending matters normally follows, but it is not a precondition for their authority. *(as reported)*
- [PK] The Highest Committee's rules set the term of office. *(direct)*
- [PK] When a term ends without an election, office holders continue until successors are elected; the Highest Committee can intervene. *(direct)*
- [I] Establishing a committee, selecting its office holders, and the office holder's mandate are three separate concepts. "Interim" describes the mandate, not the committee.
- [I] An interim mandate ends when the elected mandate begins; a regular mandate ends when successors take over, not on its end date. A unit is never left without office holders.
- [F] **Appointment (Authority)** is an existing bounded context, classified FROZEN ("Confirmed BC — Operational") and owning "Mandate truth" (BDR-03; `docs/publicdigit/architecture/context-map.md:79`), publishing `MandateGranted` / `MandateRevoked` (R-09, `:115`). Its relation to Governance is open (ARB-8, `:248`). Mandate findings in this section are business input to that context, not a proposal to move mandate ownership.
- [P] Authority passing and administrative handover are recorded separately; authority never depends on handover completion.
- [PK] Elected office holders take office when the new working period starts, not necessarily when the result is declared. Elections may be held early, with the new period starting later. Highest Committee rules can define the timing. *(direct, V-13)*
- [I] Election and taking office are separate events: the declared result fixes *who* holds the next mandate; the start of the working period fixes *when*. Between them, the outgoing office holders remain in office (consistent with V-12) and the elected persons hold a mandate that has not yet begun.
- [I] V-13 speaks of "the period of the new committee". Read with §2.4 (same committee across office-holder changes), this is taken to mean the new *term* of the same committee, not a new committee. To be confirmed.
- [OQ] Does the same timing apply to delegates?
- [PK] When an office holder leaves during the term, Highest Committee rules decide how the position is filled; usually the committee appoints a replacement. *(direct, V-14)*
- [I] A third way of filling an office: appointment by the committee itself (alongside interim appointment by the Highest Committee and election by the convention). The committee's competence here derives from the Highest Committee's rules.
- [I] The replacement's mandate is assumed to run until the end of the current term (the question was framed that way; not contradicted, not explicitly confirmed).
- [PK] One member may hold several mandates at the same time; Highest Committee rules decide any restrictions. *(direct, V-15)*
- [I] Mandates are independent of each other; compatibility rules (if any) are organisation-specific, versioned Governance Rules. No platform-wide incompatibility rule may be assumed (consistent with D-1b).

### 2.5 Territorial assignment of members

- [PK] On division, the Highest Committee decides how existing members are assigned to successor units. *(as reported)*
- [PK] The Highest Committee may assign a member to a unit other than the one they live in; residence is not mandatory. *(as reported)*
- [PK] A member may apply to work in another district, and the Highest Committee can approve it. *(as reported)*
- [PK] Outside restructuring, the Highest Committee can request a member's move by decision, but the member can reject it. *(direct)*
- [PK] A restructuring assignment is binding; the member cannot reject it. *(direct)*
- [PK] When a district is dissolved without successors, the Highest Committee assigns its members to another district (binding); by default they are attached to the higher unit (e.g. province). *(direct)*
- [PK] Attachment to the higher unit is temporary until the Highest Committee assigns a district. *(direct)*
- [PK] At a ward convention only ward members take part; at district level all members under the district; at province level all members under the province. *(direct)*
- [I] Members are normally assigned at the lowest level of the hierarchy; belonging to higher units follows from the structure, not from separate assignments. Restructuring a unit therefore changes members' higher-level belonging without individual decisions.
- [I] Residence (a fact about the person) and territorial assignment (the result of an organisational act) are separate facts.
- [I] Paths to an assignment:

| Path | Started by | Member can reject? |
|---|---|---|
| Restructuring | Highest Committee | No (binding) |
| Organisation's request | Highest Committee, by decision | Yes |
| Member's application | Member | n/a — the body named in the Governance Rules approves |
| Fallback after dissolution | Rule, as consequence of the dissolution decision | n/a — temporary |

- [PK] A person is admitted into a specific unit; a member is never without a unit. *(direct, V-23)*
- [I] The unit is part of the admission itself: territorial assignment is part of the membership fact, not a later, separate placement. "Never without a unit" confirms the invariant that every member always has a unit (with the temporary higher-unit fallback after a dissolution, V-5/V-6). Whether a member can hold **more than one** unit at a time is not yet confirmed.
- [PK] A member belongs to one geographic unit only; wing memberships come in addition. *(direct, V-24)*
- [I] Invariant confirmed: exactly one geographic territorial assignment per member at any time. Wing membership is a second, additional kind of assignment. Whether a member may belong to several wings is [OQ].
- [PK] When a member moves to another unit (restructuring, own application or accepted request), they remain the same member; only the territorial assignment changes. *(direct, V-32)*
- [I] The territorial relationship has a lifecycle of its own, separate from membership identity: created together with membership at admission (Membership act, V-23); changed by Governance decisions and structural change (Organisation), with no change to the membership; ended at the latest when the membership ends (assumed, not asked) or replaced by the fallback when its unit ends (V-5, V-6). Business input for DR-07b; ownership not decided.
- [H] Invariant candidate: every member always has exactly one territorial assignment, normally at the lowest level, and temporarily at a higher unit only after a dissolution.
- [H] "Wants to work in another district" is a *reason* value for an assignment, not a separate concept (model-integrity check).
- [F code] The code models the link as a member's application with `RESIDENCE` (eligibility required) or `EXCEPTION` (justification required) reasons, then approval (`app/Contexts/Membership/Domain/Membership/MembershipApplication.php:42-61`). The restructuring and organisation-request paths have no counterpart in the code.
- [F] `CommitteeAssociation` ownership is **unresolved** under PO-DR07-01 (`docs/publicdigit/architecture/strategic-design-status.md:61`; O-16 / DR-07b, context map `:249`). These findings supply business answers to that open item; they do not override any ratified decision.
- [F code] The approval act's competence is marked unverified in the interim adapter (`InterimRoleAuthority.php:26-51`: `ruleRef` `interim:legacy-MembershipPolicy:approveApplication`, `competenceVerified = false`). §2.1 indicates where the real answer lives: a provision of a version of the Highest Committee's Governance Rules.

### 2.6 Conventions and delegates

- [PK] Ward and district conventions are direct. At province and General Convention level, the Highest Committee's rules decide between all members and delegates. *(direct)*
- [PK] Delegates are elected by lower conventions; the number is fixed by Highest Committee rules. *(direct)*
- [PK] A unit's convention elects both its committee office holders and its delegates, at the same or at different sittings, as the Highest Committee's rules decide. *(direct)*
- [PK] The unit's own committee convenes its convention. *(direct)*
- [I] The convention is the electing body of its unit. A convention (lasting body) differs from a sitting (one meeting, possibly containing several elections).
- [I] Elections form a chain: lower conventions produce the delegates who form higher conventions.
- [I] A delegate mandate is a third kind of mandate (alongside appointed and elected office holders), elected in a lower unit to represent it at a higher convention.
- [I] A committee convenes the election of its own successors; the competence to start a unit's election rests with that unit's committee. A platform permission would only reflect that competence (authority meaning C ≠ A).
- [PK] A convention approves the previous annual report and makes decisions about future direction, within the Highest Committee's Governance Rules. Different committees may have different decision-making powers; the Highest Committee defines which committee may decide what. *(as reported, V-30 — the Principal's answer reached the recorder only through a pasted analysis)*
- [PK] The Highest Committee normally establishes committees' decision-making powers through general rules in its Governance Rules, rather than by separate grants for individual matters. *(as reported, V-31)*
- [I] A convention is a governing organ, not only an electoral event: it elects (via Election's process) and decides. C9 therefore sits with Governance; Election conducts the elections held at a convention.
- [I] Competence is normally general (per committee type or level, in the rule book); specific delegation may also exist (V-29), but its conditions are [OQ].
- [OQ] Can a committee change a decision its convention adopted, or must it return to the convention? (Queued.)
- [I] Observation: `VoterSourceStrategy` (`app/Domain/Election/Enum/VoterSourceStrategy.php:18-28`) chooses between enrolment-based and membership-based voter lists. A delegate-based electorate would be a different kind. Observation only (relates to context map R-16).

### 2.7 Strategic synthesis — working allocation (PROPOSAL, not a bounded-context decision)

Accepted by the Principal as the **working proposal** on 8 November 2026 ("Yes, record it and proceed to step 3"). It decides no bounded context.

**Capabilities (step 1, confirmed by the Principal as matching):** C1 making and recording rules · C2 deciding competence · C3 making organisational decisions · C4 organisation-defined territorial structure · C5 official reference geography · C6 committees · C7 mandates and office holding · C8a geographic territorial assignment · C8b wing membership · C9 conventions and delegates · C10 holding elections · C11 membership itself.

| Candidate home | Capabilities | Status of that home |
|---|---|---|
| Governance | C1, C2, C3, C6, C9 | Exists (ACT §11); committees are organs (PO-DR07-01) |
| Organisation | C4 | Candidate context; scope unresolved in the Context Map |
| Geography | C5 | `app/Contexts/Geography` exists; not renamed, boundary not decided; bounded context vs reference data open |
| Appointment (Authority) | C7 | FROZEN (BDR-03, "Mandate truth"); findings are business input, nothing moves |
| Membership | C8a, C8b, C11 | Ratified; C8 fits PO-ORG-MEM-01 Decision A (admission into a unit, V-23) and existing code |
| Election | C10 | Exists |

**Correction recorded.** An earlier chat proposal placed C7 in Governance; that conflicted with the frozen Appointment (Authority) context and was withdrawn (canonical-discovery miss by the recorder).

**Step 3 — relationships (PROPOSAL).** S-1 Geography→Organisation (reference units) · S-2 Governance→Organisation (structural decisions with effective date) · S-3 Organisation→Governance (unit identities) · S-4 Governance→Membership (assignment-changing decisions; fallback rule) · S-5 Organisation→Membership (units; structure for derived belonging) · S-6 Governance→Election (convention rules; convening) · S-7 Membership+Organisation→Election (direct electorate; cf. R-16) · S-8 Election→Appointment (results; mandate begins at start of term) · S-9 Governance→Appointment (interim/vacancy appointments, term and compatibility rules, intervention; cf. ARB-8) · S-10 Appointment→Election (delegate electorate) · S-11 Appointment→Governance (current office holders; cf. R-09). Pattern [I]: Governance decides with an effective date; each owner applies consequences in its own area.
- [PK] A structural resolution can be reversed only by new decisions; nothing reverses automatically. *(direct, V-25)*
- [I] All flows are forward-only: a reversal is a new decision with its own consequences, never an undo. History is append-only. Whether a restored unit keeps its old identity or is a new unit is [OQ].

**Open:** Governance–Appointment relationship (ARB-8); whether Governance is too large (T-B, re-judge after relationships are mapped); whether C5 is a bounded context or reference data.

### 2.8 Step 4 — consistency check (read-only) and accepted corrections

Four corrections **accepted by the Principal [ARCH], 8 November 2026, as stated** ("I accept all four proposals as stated, without modification"). Qualifications: territorial-assignment ownership undecided (DR-07b); Governance–Appointment stays ARB-8; Geography's boundary open; code observations are implementation evidence, not business rules; no commit or push authorised.

**Accepted corrections**
1. **C8 territorial assignment** is a **separate relationship outside the Membership aggregate**. Ownership (Membership context or Organisation) open under DR-07b. Admission into a unit creates both the membership and the territorial relationship; their consistency mechanism is a later architectural/tactical question.
2. **Eligibility (S-6, S-7):** Governance supplies convention rules; Membership and Organisation supply facts; Appointment supplies delegate mandates (S-10); **Election decides eligibility** (ratified ownership).
3. **`JurisdictionAssignment`** (proposed Governance vocabulary v1.5, A73 / register P-7; "the committee tree and the geography tree are related only through JurisdictionAssignment", ODA-01 `:117`) replaces the recorder's invented "committee placement".
4. This section records step 4.

**K — conflicts with ratified / frozen decisions**
- K-1 [F] Ratified act §10 (`docs/publicdigit/reviews/2026-10-08-membership-architecture-ratification-act.md:300-306`): the Membership aggregate must not absorb "committee participation" or "unrelated organisation structures". Canonical artifact §7 I5 (`…-membership-architecture-ratified.md:150`): "units, committee roles … each map to a type, a provenance, another relationship or another organisation". The earlier proposal "Membership owns C8" was too strong → correction 1.
- K-2 [F] C7 in Appointment (Authority) is consistent with BDR-03 (FROZEN). Proposed Governance vocabulary v1.5 also lists Appointment, Tenure, Mandate, Delegation; whether these are one context is ARB-8. Not settled here.

**D — divergence from an unratified proposal**
- D-1 [F] Context Map proposes Geography owns "GeoUnits, hierarchy, revisions" (governance map §4; XD-5 / O-11 open; `context-map.md:92`). The working allocation gives Geography only C5 and C4 to Organisation (basis: V-22). Proposal vs proposal; not reconciled silently.

**R — existing concepts and capabilities to reuse**

| Working concept | Existing | Assessment |
|---|---|---|
| C3 decision record | `GovernanceDecision` (`app/Contexts/Governance/Domain/GovernanceDecision.php:17-25`: committee, capability type, legitimacy, constitutional basis, trace, `effectiveFrom`, `decidedAt`) | Reuse; matches P-4 |
| C2 competence, approval | `GovernanceApprovalProcessManager`, `AuthorityGraphValidator`, `AuthorityEvidence`, D-4 adapter | Reuse; check against the two rule layers |
| Committee ↔ unit relation; wings | `JurisdictionAssignment` (proposed) | Reuse (correction 3) |
| C1, C2, C6, C9 | Governance ratified scope (act §11: competence, organs, authority, governance rules) | Consistent |
| Eligibility | Election ratified ownership (act §11) | Consistent after correction 2 |
| S-11 | Authorization (BDR-07, FROZEN) consumes mandates | Consistent |
| W-1 | EM-VOC-002 | Consistent |
| D-1 multi-organisation | Working Organisation; INV-ORG-SCOPE-1 | Consistent |

**C — code observations (implementation evidence, not business rules; no action)**
- C-a [F] `GeoLevelType` (`app/Contexts/Geography/Domain/Enums/GeoLevelType.php`) is a fixed enum of seven levels (province, district, municipality, ward, state, county, city); no international, continent or country level and no way to add levels. Diverges from V-16 / V-17 (Highest Committee defines and adds levels).
- C-b [F] `geo_administrative_units` holds official and organisation-specific units (nullable `organisation_id`): C4 and C5 in one table.

**Other observation:** project `CLAUDE.md` states Laravel 11 / PHP 8.2; `composer.lock` has `laravel/framework` v12.70.0 and `composer.json` requires PHP ^8.3. Observation only.

### 2.9 Strategic boundary assessment (working recommendations, not bounded-context decisions)

**Assessment 1 — Geography vs Organisation.** Accepted by the Principal as a **working architectural recommendation**, 8 November 2026; not a bounded-context decision; no code change authorised.

| Responsibility | Working home | Status |
|---|---|---|
| C4 organisation-defined territorial structure | Organisation domain | Accepted recommendation |
| C5 official reference geography | Reference-data capability | Accepted recommendation |
| C5 as a separate bounded context | — | Open |
| Existing `app/Contexts/Geography` | No renaming or restructuring | Unchanged |

Basis [I]: different authority (Highest Committee resolutions vs the state), lifecycle (decision-driven, forward-only vs external sync), language and consumers; "shared shape isn't shared meaning". Safeguards (Principal): Organisation must not become a catch-all (assess against registry, OrganisationAffiliation, BR-9); the current Geography implementation is not presumed to be the final architecture.

**Assessment 2 — Organisation.** Accepted by the Principal as a **working recommendation**, 8 November 2026 ("Yes, I accept the Organisation recommendation"); not a bounded-context decision.

| Item | Working home | Basis |
|---|---|---|
| Registry (the organisation itself) | Organisation | The organisation as an entity |
| Organisation-defined structure (C4, broadened): levels, territorial units, wing branches and their levels, hierarchy, forward-only history | Organisation | Own identity and lifecycle independent of committees (V-26–V-28); changed by Highest Committee resolutions |
| Organs within the structure (C6), related through `JurisdictionAssignment` | Governance | PO-DR07-01 |
| OrganisationAffiliation (platform access and roles) | Not justified by this assessment; BR-9 / INV-AFF-1 decide on their own merits | Platform permission ≠ organisational authority; avoids a catch-all |
| Provenance | Undecided (supporting concern) | — |

Organisation answers one question: "What is this organisation, and how is it divided?" Risks: name collision with the tenancy term Working Organisation; scope would grow if C8 (DR-07b) were later placed here.

**Assessment 3 — Governance.** Accepted by the Principal as a **working recommendation**, 8 November 2026 ("Yes, I accept the Governance recommendation"); not a bounded-context decision.

| Capability | Working home |
|---|---|
| C1 rules (constitution; Highest Committee resolutions; rule book as derived summary) | Governance |
| C2 competence (general, by committee type/level) and delegation (V-29, V-31) | Governance |
| C3 organisational decisions (forward-only; reuse `GovernanceDecision`) | Governance |
| C6 committees and other organs (`JurisdictionAssignment`) | Governance |
| C9 conventions as governing organs (composition, convening, sittings, decisions) | Governance |
| Elections held at a convention | Election |
| Resulting mandates | Appointment (Authority), FROZEN |

Governance answers: "who may decide what, through which organs, and what was decided?" No split proposed. Open: ARB-8; possible overlap of `RemoveCommitteeMemberCommand` (Governance code) with Appointment's mandate truth — for the ARB, meaning not verified.

---

## 3. Tensions

| # | Tension | Status |
|---|---|---|
| T-1 | The Highest Committee's rules set the composition of the General Convention, the organ from which the Highest Committee derives authority. Legitimate only if the constitution delegates it. | [OQ] — document review |
| T-2 | The General Convention defines committee types, but the Highest Committee may add levels. What committee does a new level (e.g. Zone) have if its type is not constitutionally recognised? | [OQ] |
| T-3 | Residual competence implies a default organ on constitutional silence, while P-2 treats missing interpretation as `UNDETERMINED`. Resolved in principle: a residual default (organ) is distinct from a missing rule (procedure); its source clause must still be found. | [I], source [OQ] |

## 4. Open questions

1. ~~When does an elected mandate take effect?~~ Answered by V-13 (§2.4): at the start of the new working period, which Highest Committee rules can define; open only for delegates.
2. What may the Highest Committee do when it intervenes on an overdue election (convene, appoint interim office holders, other)?
3. Is there a deadline for assigning members temporarily attached to a higher unit, and what rights do they have meanwhile? [I: they take part in the higher unit's convention only — unconfirmed]
4. Does a Highest Committee request to move take effect unless rejected, or only on acceptance?
5. Can a member bound to X1 by restructuring later apply to move to X2? [H: yes]
6. Can a divided unit's successors exist for a time without a committee; who acts meanwhile?
7. What happens to the records and pending matters of a committee that ends?
8. Duration of a delegate mandate; effect of restructuring on it; basis of delegate allocation.
9. Merge and rename: [H] merge ends both committees and establishes one; rename keeps the committee. Unconfirmed.
10. Is international scope (NRNA worldwide) a distinct organisational level rather than a territory; how do country-level bodies relate to the worldwide organisation?
11. Source of the fallback-to-higher-unit rule and of residual competence (constitution, Governance Rules or practice).
12. Division of responsibility between candidate responsibilities Governance (competence, decisions, validity), Organisation (structure and lifecycle) and Geography (reference identities and hierarchies). These are **not** accepted bounded contexts.

## 5. What this record does not do

- It decides no bounded-context boundary, aggregate, value object or port.
- It does not modify the ratified Membership Architecture, ACT §11, the Context Map, the Platform Rules Index or any ADR.
- It turns no [PK] into [F]. Documentary verification is pending the NRNA documentation request.
- It is not committed until the Principal approves (gate E).

## 6. Verbatim record of the Principal's direct answers

Exact wording as typed, in order (typos preserved). These are the primary evidence for every *(direct)* item above; where the summary in §2 and this wording differ, this wording governs.

| # | Question (short) | Principal's words |
|---|---|---|
| V-1 | How is a district committee defined? (earlier session) | "We should not define what is District committee. We should combine it with Geographical context. Geography Context define the committee level." |
| V-2 | Division: what happens to the original committee? | "The original committee ends; the Central Committee establishes two new committees." |
| V-3 | Can the Central Committee move a member without an application? | "Yes, the Central Committee can request the   member by decision but the member can reject . ." |
| V-4 | Can a member reject a restructuring assignment? | "Restructuring assignment is binding; the member cannot reject it." |
| V-5 | Dissolution without successors: what happens to members? | "Central Committee assigns them to another district; binding. default they are attached to higher unit such as province." |
| V-6 | Is province attachment temporary or permanent? | "Only temporary until the Central Committee assigns a district." |
| V-7 | Lowest level or designated level? | "normally if a ward convention happens, only the ward member take part . if district level convention then all  members under this district.  if province ,then all members under the province" |
| V-8 | Direct participation or delegates? | "Direct at ward and district;  Central committe rule fix whether the delegates or all memers at province and General Convention." |
| V-9 | How are delegates chosen? | "Elected by lower conventions; number fixed by Central Committee rules." |
| V-10 | Who elects committee office holders? | "The unit's convention elects both, at the same sitting or different sitting. this should be a rule defined by central committee" |
| V-11 | Who convenes a convention? | "The unit's own committee convenes; Central Committee rules define how long a committee office holder's time period exists" |
| V-12 | Term ends without an election? | "Office holders continue until successors are elected; Central Committee can intervene." |
| V-13 | When do elected office holders take office? | "When the result is declared; Central Committee rules can define otherwise. not when the results are declared but when the period of the new committee starts. sometimes people make election early and the working period of newly elected body starts later point of time." |
| V-14 | How is a mid-term vacancy filled? | "Central Committee rules decide; usually the committee appoints a replacement." |
| V-15 | Can one member hold several mandates? | "Yes, multiple mandates allowed; Central Committee rules decide restrictions." |
| V-16 | How do countries fit into NRNA's structure? | "nrna has internal committee which is zero level , then contents , then countries , then states and then city but all these must be defined by the highest committee." |
| V-17 | Same level pattern in every country? | "the central committee defines rule  and it should be everywhere similar structure . there might be exception but that should be mentioned in the rule log of central committee." |
| V-18 | How does the Highest Committee record its rules? | "Both; resolutions in minutes, consolidated into a rulebook" |
| V-19 | Resolution or rulebook — which counts? | "Resolution prevails; the rulebook is only an editorial summary" |
| V-20 | Do wings or other bodies exist? | "Yes, wings exist; Highest Committee creates them with own levels" |
| V-22 | What did "Geography Context define the committee level" (V-1) mean? | "Organisation's own territorial structure; keep C4 and C5 separate." (Accompanying pasted elaboration: "both, but separated by ownership"; do not yet rename the existing Geography context or decide its boundary.) |
| V-23 | Admitted into a unit, or to the organisation first? | "Admitted into a specific unit; never without a unit" |
| V-24 | More than one unit at a time? | "One geographic unit only; wings in addition" |
| V-25 | Does reversing a structural resolution reverse its consequences? | "New decisions are required; nothing reverses automatically" |
| V-26 | Is a wing a branch or a kind of committee? | "A branch with its own committees at each level" |
| V-27 | Wing created first, or through its first committee? | (within a pasted analysis) "the Highest Committee first creates the wing as a whole organisational branch, including its organisational levels; committees are then established within that branch." — with the qualification: "I would not yet record the precise resolution sequence as confirmed practical knowledge." |
| V-28 | Can a unit or wing level exist without a committee? | "Yes, temporarily; the Highest Committee acts until a committee exists" |
| V-29 | (refinement of V-28) | "highest committee can assign who takes the responsibility" |
| V-30 | Do conventions only elect? | (reported only through a pasted analysis; the Principal's own wording not available to the recorder) |
| V-31 | General rules or specific grants? | (reported only through a pasted analysis; the Principal's own wording not available to the recorder) |
| V-32 | Does moving unit change the membership itself? | "Same member; only the territorial assignment changes" |
| V-21 | Do wing committees sit on the geographic units or on their own divisions? | "this should be defined in the central committee rule book" — then, correcting the term: "highest committee rule book" |

**Wording caveats.**
- V-7 answers the lowest-level question indirectly, through convention participation. "Members are assigned at the lowest level" (§2.5) is an [I] from V-7, qualified by "normally". It is not a confirmed rule.
- V-10 says the sitting arrangement "should be" a Highest Committee rule. Like the reported "should specify" in §2.1, this may be normative. Whether NRNA's rules actually contain it is [OQ].
- V-5's "default" was clarified by V-6 as temporary.

The eleven findings presented in conversation map to §2 as follows: 1 → §2.1; 2 → §2.2; 3–4 → §2.3; 5–6 → §2.4; 7–8 → §2.5; 9–11 → §2.6.

## 7. Traceability

Conversation: Architecture Session A, 8 November 2026 (one question per turn). Linked: roadmap plan `docs/plans/20261108-0303-organisational-baseline-to-tactical-design-roadmap-plan.md`; status record `docs/publicdigit/architecture/strategic-design-status.md`; PO-DR07-01; PO-ORG-MEM-01 and J-rec; context map R-16, O-16 / DR-07b, EM-VOC-002.

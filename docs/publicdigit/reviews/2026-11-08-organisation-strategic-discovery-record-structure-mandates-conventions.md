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
| V-33 | Can a non-member belong to a unit? (holding an office or mandate excluded) | "yes as guest , worker/staff , observer etc but not as member." |
| V-34 | Who decides that a non-member belongs to a unit? | "depends on the capacity; staff formally appointed, guests just recorded" |
| V-35 | Does a staff appointment carry authority? | "depends on the position; some staff can sign, most cannot" |
| V-36 | What happens to unit belonging when membership ends? | "ends completely; a new act is needed, e.g. recorded as guest" |
| V-37 | Does a suspended member still belong to their unit? | "yes, still belongs to unit; suspension restricts rights only" |
| V-38 | Is joining a wing like unit belonging, or a separate admission? | "depends on the wing; organisation rules decide" |
| V-39 | Membership types; organisation-wide membership; higher-level committee service | **Reported summary, not a quotation.** A pasted analysis summarised the Principal's answer as: General Member and Active Member are membership types; membership is organisation-wide; the member belongs to the lowest unit; serving on a higher committee creates no second membership. The Principal's own wording is not available to the recorder |
| V-40 | How does an ordinary committee member get their place? (first answer) | **Reported, not directly verified.** A pasted analysis quotes the Principal as: "The General Assembly of Active Members elects the committee members or office holders". Practice of the organisations described; not a rule for all PublicDigit organisations |
| V-41 | Eligibility for higher-level committees | **Reported, not directly verified.** A pasted analysis gives example criteria: "at least 5 years as an Active Member" and "at least 2 years of service as a committee member or office holder at the lowest organisational level", stated there as illustrative. Examples of organisation-specific criteria; not a rule for all PublicDigit organisations |
| V-42 | Can a committee fill a place without an office (an "ordinary seat") itself? | "(b) committee appoints a replacement, like office holders" — after asking "what you mean by an ordinary seat ?" |
| V-43 | Does authority always come from a position? | "(3) positions created when needed, like acting coordinator." |
| V-44 | What can be ratified after the fact? | "both; individual actions as B-light" |
| V-45 | When a ratified appointment takes effect | **Reported, not typed directly.** A pasted analysis records the Principal's choice as option (c), "rule-governed case-by-case effect of appointment ratification" |
| V-46 | Is the number of places on a committee set by the rules? | "(1) set by rules; each place can be vacant" |
| V-47 | Is the chair its own place, or an office added to a member's place? | "(a) own place, elected directly into it" |
| V-48 | Can a committee be dissolved while its unit continues? | "(3) both, by organisation rules" |
| V-49 | When a district moves to another province, is it the same district? | "(a) same district; committee continues" |
| V-50 | When two districts merge: a new district, absorption, or the decision says which? | "(c) the decision says which; organisation rules decide" |
| V-51 | When a dissolved district is brought back: same district, a new one, or the decision says which? | "(c) the decision says which; organisation rules decide" |
| V-52 | When a province is dissolved, what happens to its districts? | "(c) move up to next higher level until reassigned" |
| V-53 | Can a member belong to more than one wing at a time? | "(c) organisation rules decide" |
| V-54 | Can a person be staff, or a guest, in more than one unit at a time? | "(c) organisation rules decide" |
| V-55 | At a delegate convention, does anyone take part by virtue of an office, without being elected? | "(c) organisation rules decide" |
| V-56 | When an elected delegate leaves before the higher convention, how is the place filled? | "(d) organisation rules decide" |
| V-57 | How long does a delegate hold the place? | (via paste, accepted) the pasted analysis recommended "Option C — Organisation rules decide"; the Principal answered "I accept the recommendation" |
| V-58 | When a represented unit is divided, merged or dissolved, what happens to its delegates' mandates? | "organisation rule decides" |
| V-59 | Who may enter an organ's decision into PublicDigit? | "(c) organisation rules decide" |

**Wording caveats.**
- V-7 answers the lowest-level question indirectly, through convention participation. "Members are assigned at the lowest level" (§2.5) is an [I] from V-7, qualified by "normally". It is not a confirmed rule.
- V-10 says the sitting arrangement "should be" a Highest Committee rule. Like the reported "should specify" in §2.1, this may be normative. Whether NRNA's rules actually contain it is [OQ].
- V-5's "default" was clarified by V-6 as temporary.

The eleven findings presented in conversation map to §2 as follows: 1 → §2.1; 2 → §2.2; 3–4 → §2.3; 5–6 → §2.4; 7–8 → §2.5; 9–11 → §2.6.

## 7. Traceability

Conversation: Architecture Session A, 8 November 2026 (one question per turn). Linked: roadmap plan `docs/plans/20261108-0303-organisational-baseline-to-tactical-design-roadmap-plan.md`; status record `docs/publicdigit/architecture/strategic-design-status.md`; PO-DR07-01; PO-ORG-MEM-01 and J-rec; context map R-16, O-16 / DR-07b, EM-VOC-002.

## 8. Continuation: Session B, 8 November 2026 (C8, ARB-8, consolidated map, Governance tactical model)

**Status.** PROPOSED discovery evidence, like the rest of this record. The working decisions below are the Principal's **working** decisions. None is ratified. None changes a ratified or frozen record. Each ARB act named here is still to be done.

**Source quality.** As in §0: *(direct)* means the Principal typed the answer in the conversation; *(as reported)* means it reached the recorder only through a pasted analysis. Several decisions were given as "I accept the recommendation" in reply to a pasted analysis; they are marked *(via paste, accepted)*.

### 8.1 Working decisions

| # | Decision | Source | Notes |
|---|---|---|---|
| D-2 | The roadmap's phase order stands. §2.7–2.9 of this record are provisional Phase 5 material until Phases 3 and 4 are accepted. | via paste, accepted | The roadmap's own status remains PROPOSED |
| D-3 | **Organisation owns C8** (belonging to units and wing branches, in every capacity), with the exclusions in §8.3. | via paste, accepted | Working decision; formal DR-07b act later |
| D-4 | **ARB-8 option (i):** Governance is the context BDR-03 calls "Appointment (Authority)". It owns positions and tenures, and therefore mandate truth. | direct: "Yes, I accept option (i) as a working decision." | BDR-03 explicitly confirmed a bounded context ("Confirmed BC — Operational", `docs/architecture/design/Round49-06_Boundary_Decision_Register.md:16`). This is therefore a **consolidation of bounded contexts**, to be recorded later by ADR, with a BDR v1.x addition and a Boundary Evolution History row. The v1.0 table keeps its wording |
| D-5 | Governance stays **one bounded context**, with internal modules for Rules, Organs and positions, and Decisions and competence. Rules is reconsidered only if rule interpretation develops its own lifecycle. | via paste, accepted | — |
| D-6 | **Retrospective effect is limited to ratification** (option C). Ordinary decisions take effect no earlier than the date they are made. | via paste, accepted | Act date and ratification date both kept; no separate "ratification effective date" |
| D-7 | **Mandate merge test (Round 50-02, open) settled:** no Mandate aggregate. Authority is held through the Position aggregate and its tenure history. `MandateGranted` / `MandateRevoked` stay unchanged, as integration events derived from tenures. Organ-to-organ delegation stays `AuthorityAssignment`. | direct: "Yes, I accept the Mandate merge test resolution." | Round 50-01 to be superseded later by ADR, with a note and its original text kept |
| D-8 | Both organ decisions and individual actions can be ratified; individual actions are recorded only when brought for ratification (B-light). | direct (V-44) | — |
| D-9 | When an appointment is ratified, the ratifying organ decides its effect each time, within the organisation's rules (RA-6a–RA-6d). | as reported (V-45) | — |
| D-10 | The **Governance tactical model** is accepted as the working baseline: aggregates RuleSet, Decision, Organ, Position, AuthorityAssignment. | direct: "Yes, I accept the Governance tactical model." | Qualifications in §8.6 stay in force. No implementation authorised |
| D-11 | Record the decisions in the repository before Organisation's tactical model. | Recommended by the recorder; endorsed by the Principal through a pasted analysis | A decision about **sequence** only. Permission to write these records and any commit or push are separate approvals |
| D-12 | A decision's **competence** is checked on its **decision date**, under the rules and structure in force that day; its **content** must be applicable on its **effective date**, otherwise it is recorded as inapplicable (which does not invalidate it) and a new decision follows. | direct: "(a) decision date." | Consistent with RA-3 (competence to ratify on the ratification date) |

### 8.2 Findings from V-33 to V-48 (summary; §6 wording governs)

- [PK] A person belongs to a unit **in a capacity**: member, staff, guest, observer, and wing member where the wing works that way (V-33, V-38). Non-members never belong as members.
- [PK] How a belonging starts depends on the capacity: members by admission, staff by formal engagement, guests by being recorded (V-34). Observers: [OQ].
- [PK] Whether a staff member has authority depends on the post (V-35). [P] Belonging and authority are separate facts; a post with powers is a Governance position, a post without powers is only a staff belonging with a job title.
- [PK] Member belonging ends completely when membership ends; anything further needs a new act (V-36). It is unaffected by suspension (V-37).
- [PK] How people join a wing is a property of the wing, set by the organisation's rules (V-38). [I] Under I5 (`…-membership-architecture-ratified.md:150`) a wing with its own admission is "another relationship", not a second membership.
- [PK, as reported] General Member and Active Member are membership types; membership is organisation-wide; higher-level committee service creates no second membership (V-39, reported summary). In the organisations described, places on committees are elected by the General Assembly of Active Members (V-40). Organisations set their own eligibility criteria for higher committees; the 5-year and 2-year figures are examples (V-41). None of V-39 to V-41 is a rule for all PublicDigit organisations.
- [PK] Committees fill vacancies in places without an office the same way as offices (V-42). No co-option of extra members.
- [PK] Authority always comes from a position; positions are created when needed, such as acting coordinator (V-43).
- [PK] Committee size is set by rules; each place can be vacant (V-46). The chair is its own place, elected directly (V-47). A committee can be dissolved while its unit continues, where the organisation's rules allow (V-48).

### 8.3 C8 (unit belonging), working model

- [P] One relationship: person, unit or wing branch, capacity, basis act, period. It never carries authority and never copies membership status.
- [P] Membership supplies only *membership ended*, which closes belongings whose capacity requires membership. Staff, guest and observer belongings are unaffected.
- [I] Restructuring: individual reassignment needs a decision; the temporary fallback to the higher unit (V-5, V-6) and higher-level belonging follow from the structure.
- Excluded from C8: membership truth, authority and mandates, competence and approval rules, voting rights, platform access.

### 8.4 Superseded statements in this record (not edited; superseded here)

- §2.5, the [I] at the V-23 bullet ("territorial assignment is part of the membership fact"): superseded by §2.8 correction 1 and D-3.
- §2.7, the table row "Membership: C8a, C8b, C11": C8 is superseded by D-3.
- W-1 "territorial assignment": **provisional** proposed replacement **"unit belonging"** (it covers wings and non-members; "affiliation" clashes with EM-VOC-002). W-1 stays in use until vocabulary decision O-14 is made; this is not a vocabulary decision.

### 8.5 Evidence found during Session B

- [F] BDR-03's evidence (EV-050/051) is Governance code: `Authority/…DelegationStatus`, `DelegationLifecyclePolicy`, `RemoveCommitteeMemberCommand`/`Handler` (`docs/architecture/design/Round49-04_Behavioral_Evidence_Dossier.md:35`). Governance was not a candidate in that register.
- [F] Round 50-01 `AGG — Mandate` takes its lifecycle from `DelegationStatus` (organ-to-organ delegation); Round 50-02 left its merge test "OPEN … NOT finalized" (`docs/architecture/design/Round50-02_Aggregate_Review.md:26`).
- [F] Election declares `OrganisationalAppointmentAuthority` as an external port, deliberately unimplemented (EM-OPEN-049). `ElectionCommittee` is Election's own aggregate; its seats are filled only through that authority (I-4, I-5).
- [F] `RemoveCommitteeMemberCommand` deletes an entry from the Governance `Committee` member→role map, with no basis, effective date or history.
- [F] `CommitteeAssociation` carries its own `MembershipStatus` with `suspend`/`restore`/`terminate`, and enforces no "one geographic unit" rule.

### 8.6 Open items carried forward

R-14 Membership → Governance (inferred, contract not validated) · observers on committees · change of committee size mid-term · RuleSet–Decision integration design · meaning of `GovernanceDecision` `Legitimacy` and `DecisionTrace` · event-flow and failure testing of the Organ → Position → tenure ending chain (effective date, safe to repeat, correct "as at" read models) · whether Finance consumes `ActRatified` · B-4 (Change Request authority for new consumers of Membership events) · C5 as its own bounded context · Organisation vs Working Organisation naming · vocabulary O-14.

**Linked:** `docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md` (PROPOSED).

## 9. Continuation: Session B, 8 November 2026 (Organisation tactical model, cross-context contracts)

**Status.** PROPOSED discovery evidence, like the rest of this record. Working decisions only; none is ratified, none changes a ratified or frozen record, and none authorises implementation. Source labels as in §8: *(direct)*, *(via paste, accepted)* — a recommendation in a pasted analysis that the Principal accepted —, and *(as reported)* — a decision that reached the recorder only through a pasted analysis, then endorsed by "I accept the recommendation".

### 9.1 Working decisions

| # | Decision | Source |
|---|---|---|
| D-13 | Fallbacks (V-5, V-6, V-52) and merger successors (V-50) are worked out "as at" a date from the structure and the decisions; they are never written as changes to members' or units' records. | direct: "Yes, I accept the derived fallback principle" |
| D-14 | When a dissolved unit is brought back as the same unit, the decision bringing it back states whether former members and child units that were never reassigned return. If it says nothing, nobody returns. | via paste, accepted |
| D-15 | Successor continuity: child units and members of a unit that ends with a declared successor follow the successor, including through its later moves and its own later successor or fallback. A newer explicit reassignment takes precedence. | as reported |
| D-16 | Division: the division decision sets the general allocation of child units and members and is the authoritative basis; later decisions handle exceptions as newer records. | as reported |
| D-17 | A member belonging directly to a divided unit and not allocated falls back temporarily to the nearest active higher unit until a decision places them (consistent with V-5, V-6, V-23). | as reported |
| D-18 | A division may take effect with child units unallocated; they keep their identity and fall back temporarily to the nearest active higher unit. | as reported |
| D-19 | A fallback may skip a level. Confirms V-52; an organisation's rules forbidding this remain possible [OQ]. | via paste, accepted |
| D-20 | D-13 to D-19 are the working Organisation domain baseline; the aggregates stay under validation; R-15's direction is accepted, its contract open. | via paste, accepted |
| D-21 | Only the Highest Committee makes formal structural changes (create, change, move, merge, divide, dissolve units); lower committees may propose. Describes the organisations under discovery, not a platform rule (D-1). | as reported |
| D-22 | Organisation's working tactical model: aggregates Organisation (registry), Unit, PersonBelonging (per person and organisation); tree consistency by change sets and a tree version (option C); R-15 outline. | direct: "Yes, I accept the Organisation tactical model" |
| D-23 | R-6c: Governance owns the competence to appoint election officials and the appointment decision; Election owns its committee, officer roles and seats; four exchanges; EM-OPEN-066 and EM-OPEN-049 answered in form (the organ the organisation's rules make competent). | direct: "Yes, I accept the R-6c contract as a working decision" |
| D-24 | A position becomes vacant only by a formal decision of the competent organ; a later annulment of the holder's membership End does not give the seat back. | as reported |
| D-25 | B-4: the Principal approves Change Requests to the ratified Membership architecture; the Principal Reviewer is advisory. | as reported |
| D-26 | An appointment stays valid as made when Membership later corrects or voids the facts it relied on; neither it nor the decisions the appointee took part in are invalidated automatically; further action needs a formal Governance decision. | as reported |
| D-27 | After an exclusion is annulled, Organisation recalculates whether existing member belongings are effective from the corrected coverage; an earlier belonging may become effective again for the regained coverage; a later explicit belonging stays effective from its own date and takes precedence. No record is rewritten, no later decision cancelled. | via paste, accepted |

### 9.2 Findings and inferences (summary; §6 wording governs)

- [I] Division ends the original unit; its child units are moved (V-49) and keep their identity, so members belonging to them need no individual allocation. Only members belonging directly to the divided unit do.
- [I] A boundary adjustment never moves members automatically: belonging is fixed by an organisational act, not by residence (§2.5).
- [I] V-25 answered "Does reversing a structural resolution reverse its consequences?" (§6). It governs organisational decisions; a Membership annulment and Organisation's recalculation under D-13 and D-27 are not such a reversal.
- [I] Under D-12 and D-26, each context judges another context's facts as known when it acts, and never rewrites its own history afterwards.
- [I] A lapse is not an ending (ratified INV-3); a lapsed member's unit belonging continues.

### 9.3 Evidence found (Session B, continued)

- [F] Ratified Membership: one aggregate per (Person, Organisation); `seq` is "a total recording order per Membership"; a fact and its consequences commit atomically; every fact carries `effectiveAt`, `decidedAt`, `recordedAt`, `seq`; "deterministic and stable KnownAt" (`docs/publicdigit/reviews/2026-10-08-membership-architecture-ratified.md` §2, §5–§7).
- [F] Ratified Membership facts can change the past: Annul (ex tunc), Void, Correct (same document, §2).
- [F] Ratified §8 lists Election as the only consumer of Membership facts; "Changes only via an Architecture Decision / Change Request" (`:3`).
- [F] The only code event, `MembershipTerminated`, carries `committeeId` and no `PeriodId` (`app/Contexts/Membership/Domain/Membership/Events/MembershipTerminated.php`); it is not a suitable contract.
- [F] Election rules: EM-GOV-028 (appointing authority), EM-GOV-026, EM-GOV-029, EM-GOV-033, EM-GOV-056, EM-GOV-064 (closed list of vacancy grounds); EM-OPEN-049, EM-OPEN-066 (`docs/publicdigit/reviews/2026-08-16-EM-OPEN-049-authority-over-the-chief.md`; `docs/publicdigit/reviews/2026-10-09-committee-responsibility-authority-and-membership-boundary-discovery.md`).
- [F] B-4 is registered as open: "Which authority may approve a Change Request to the ratified Membership architecture?" (`docs/publicdigit/architecture/reviews/2026-11-07-oda-01-v1.3-correction-plan-approval-package.md:248`).

### 9.4 Superseded statements in this record (not edited; superseded here)

- §8.3, "Membership supplies only *membership ended*, which closes belongings whose capacity requires membership": the mechanism is superseded by the R-3 coverage contract (no closing record; effectiveness worked out from coverage, D-27). The business rule (V-36, V-37) is unchanged.
- §8.6, "R-14 Membership → Governance (inferred, contract not validated)": now a designed query contract (model document §12), still subject to the Change Request.

### 9.5 Open items carried forward

Observers' starting act · deadline for reassigning after a fallback · body that engages staff · lifecycle when an organisation ends · whether the single geographic member limit becomes an organisation rule · a seat filled on an appointment later found invalid and not ratified · vocabulary CQ-1 ("Election Committee") and O-14 · eligibility for election committee seats (EM-OPEN-069) · contracts R-1, R-2, R-5, R-6a, R-6b, R-7, R-8, R-10, R-11 · Organisation vs Working Organisation naming.

**Linked:** model document `docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md` §9–§13 (PROPOSED).

## 10. Continuation: Session B, 8 November 2026 (remaining cross-context contracts)

**Status.** PROPOSED discovery evidence. Working decisions only; none is ratified or authorises implementation. Source labels as in §9.

### 10.1 Working decisions

| # | Decision | Source |
|---|---|---|
| D-28 | An elected person's tenure begins at the start of the working period after the result is published. A later successful challenge is a new change from its own effective date; it does not erase the tenure or invalidate what was done in it. Publication finality and adjudicative finality are distinct. | as reported |
| D-29 | R-1 (Governance → Organisation: `StructuralChangeSetsDecided`, `BelongingAllocationsDecided`) and R-2 (Organisation → Governance: `StructureAsAt`, `BelongingAsAt`, tree version recorded) are working designs. | direct: "Yes, I accept R-1 and R-2 as working designs." |
| D-30 | Within one resolution, each structural change set stands or falls on its own, unless the resolution declares a group of change sets indivisible; a division remains one change set. | direct: "(b) independent change sets unless declared indivisible" |
| D-31 | Official geography is its own platform-level bounded context (Reference Geography): official units, revisions, approval of submitted missing units. The organisation's own units are Organisation's. | direct: "(a) its own platform-level bounded context" |
| D-32 | The Election contract set R-5, R-6a, R-6b, R-7, R-8 is accepted as working designs, with four qualifications: taking part is not voting; one ballot per person is current software, not a platform rule (weighting [OQ]); a replacement delegate's mandate follows the applicable rule; restructuring consequences for delegates follow V-58. | direct: "yes i accept" (to the pasted recommendation with these qualifications) |

### 10.2 Findings and inferences

- [I] D-28's open question "is the published result itself the basis for holding office?": §2.4 records "The elected office holders take over automatically; no Highest Committee decision is needed" (as reported), and the convention elects while Election conducts (§2.6). So the published result is the convention's decision; Governance records it, and no separate act is needed unless an organisation's rules require one.
- [I] Who approves Election-side changes: Election's rules were adopted by "PO/ARB adoption acts" (`docs/publicdigit/reviews/2026-08-16-adoption-act-v3.md`), so the Principal, as PO and human ARB Chief.
- [E] Election predicates: "`Eligibility` — Election's (not a Membership predicate)" (ratified Membership architecture §4); supports Election applying candidacy criteria.
- [I] V-58 is applied by Governance once Organisation confirms a structural change set (R-1 reply).
- [F] BDR-08 freezes "Election Lifecycle" as a supporting subdomain; Election's boundaries come from the Membership act (§11, §12) and the adopted EM-GOV rules.

### 10.3 Evidence found

- [F] `VoterSourceStrategy` has two sources only, platform users (`election_only`) and the `Member` table (`full_membership`); "Election snapshot is sovereign … IMMUTABLE at election level" (`app/Domain/Election/Enum/VoterSourceStrategy.php`).
- [F] `ResultsPublishedEvent` carries `electionId, publishedBy, publishedAt, state` only (`app/Contexts/Elections/Domain/Events/ResultsPublishedEvent.php`).
- [F] No convention, delegate or candidacy-eligibility concept exists in Election code; regional voting matches `user.region` text (project `CLAUDE.md`).
- [F] `GovernanceDecision::record` rejects an effective date after the decision date and accepts earlier ones (`app/Contexts/Governance/Domain/GovernanceDecision.php`) — the inverse of D-6 and D-28.
- [F] `GeoAdministrativeUnit` has a fixed `parentId` and one `validFrom`/`validTo`; `GovernanceGeoUnitQueryService` reads unit tables directly.
- [F] Reference geography: landlord master, tenant copy with remapped IDs, `landlord_geo_id`, `is_official`, `code`; levels 6–8 are organisations' custom units in the same table; daily sync of "approved geography"; `GeographyCandidateService::submitMissingGeography` (`app/Contexts/Geography/`).
- [F] Authorization today: Laravel policies in `app/Policies` over platform roles; the D-4 adapter marks competence unverified.

### 10.4 Superseded statements (not edited; superseded here)

- §9.5's open item "contracts R-1, R-2, R-5, R-6a, R-6b, R-7, R-8, R-10, R-11" now has this status:

| Contract | Status |
|---|---|
| R-1, R-2 | Working designs [D] D-29, D-30 |
| R-5, R-6a, R-6b, R-7, R-8 | Working designs [D] D-32 |
| R-10 | Proposed [P]. D-31 settles the context boundary only; identity and temporal semantics open |
| R-11 | Proposed [P]. BDR-07 and V-59 set its constraints |
| R-3, R-14 | Proposed [P]; depend on the Membership §8 Change Request (not approved) |
| R-15 | Proposed [P] |

Accepting a bounded context (D-31) does not accept the contracts connecting to it.

### 10.5 Open items carried forward

A cancelled convention and delegate mandates · weighted votes through two participation routes · who keeps a person's residence · whether an ex officio seat carries a vote (organisation rule) · how an observer's belonging starts · deadline for reassigning after a fallback · body that engages staff · lifecycle when an organisation ends · vocabulary CQ-1 and O-14 · the Election-side changes and their PO/ARB act (model document §17).

**Linked:** model document §15–§19.

## 11. Continuation: Session B, 8–9 November 2026 (Governance tactical design, cross-context consistency, Election and Voting, Deliberation, Communication, Evidence integration)

**Status.** PROPOSED discovery evidence. Working decisions only. None is ratified or authorises implementation, except where marked **ARB** (rulings by the Principal as human ARB Chief, which still need recording in their own governed artifacts; see model document §26). **Provenance:** *direct* = the Principal typed the answer · *via paste, accepted* = the Principal accepted a pasted review that recommended the option · *as reported* = a pasted review reported the Principal's choice and the Principal accepted that review.

### 11.1 Working decisions

| # | Decision | Provenance |
|---|---|---|
| D-33 | PublicDigit records evidence of how a decision was reached (meeting date, attendance, votes for, against, abstaining, minutes reference). It does not validate quorum or majority. Which evidence applies depends on the method of decision. | direct: "I accept option B." |
| D-34 | Every part of a resolution stands or falls on its own unless the resolution declares parts indivisible; a part depending on a failed part fails. Extends D-30 beyond structural change sets. | direct: "I accept option A" |
| D-35 | The Resolution is the unit of recording (one decider, one decision date, procedure evidence); the Part is the unit of validity and application. A part without competence is a recorded act, not an effective decision, until ratified. The decision log position records when PublicDigit learnt of a decision. | via paste, accepted |
| D-36 | When a resignation takes effect is decided by the organisation's rules; the rule version applied is identified. | as reported |
| D-37 | When a holder's death ends a tenure is decided by the organisation's rules. | direct: "organisation rule decide" |
| D-38 | A position's powers are held in the rules; Position references the provision defining them. A change of powers is a new rule version. | direct: "(b) keep powers in RuleSet, reference from Position" |
| D-39 | Position (with tenure history) is an aggregate referring to its powers provision; RuleProvision is the rule aggregate; RuleSet is a catalogue read model. | direct: "Yes, I accept the Position and RuleSet design" |
| D-40 | An indivisible group completed after its declared effective date still takes effect from the date the organ decided. A technical delay changes only knowledge time. | as reported (option A) |
| D-41 | Election does not fix an electorate while a pending indivisible restructuring affects its units and takes effect on or before the cut-off. It proceeds once the restructuring is completed or authoritatively settled. No timeout releases it. | as reported (option B) |
| D-42 | A snapshot built after its cut-off uses only facts recorded by the cut-off, plus the outcomes of restructuring decisions already recorded by then. | via paste, accepted (option A) |
| D-43 | A restructuring decided before the cut-off and still pending keeps blocking. If it completes, it enters the snapshot from its decided date; if a later competent decision pre-empts it, it is left out and the wait ends. A timeout, failed message or operator action is never pre-emption. | as reported (option A) |
| D-44 | Secret elections are in the current baseline; motion voting is a later, separate capability. | as reported (option C) |
| D-45 | Staged secret-ballot assurance: Level A (stored-data unlinkability) now; Level B (secrecy not depending on the operator) later. Level A is never presented as Level B. | as reported (option C) |
| D-46 | The per-network vote limit applies when voting rights are issued, inside Election. Voting holds no IP address. | via paste, accepted (option A) |
| D-47 | Replacement of a lost voting right follows the organisation's rules; default: no replacement. Where allowed: by election committee decision, with the number of replacements published with the result. | via paste, accepted (option D) |
| D-48 | The official result of every race is always published. Optional breakdowns only for groups at least as large as the organisation's rules set; none until a minimum is set. | via paste, accepted (option B) |
| D-49 | Escrow of the combined-ballot key for the challenge period follows the organisation's rules; default: destroyed at close. Escrow needs a stated purpose, dual control, fixed expiry and disclosure. | via paste, accepted (option C) |
| D-50 | Member deliberation is part of PublicDigit now (HD-P1). | direct: "option a" |
| D-51 | Governance owns a motion from the moment it is submitted (HD-P2). Before submission, Deliberation owns drafting, discussion and co-signatures. | direct: "(a) Governance owns it from submission" |
| D-52 | Whether supports are visible by name is decided by the organisation's rules per forum; default: counted only. Co-signatures are always named. | direct |
| D-53 | Whether and by whom a moderation decision is reviewed is decided by the organisation's rules; default: the independent tribunal through Contestation and Adjudication, with recusal of moderators in their own matters. | direct |
| D-54 | An organ must respond to a highly supported idea only where the organisation's rules set a duty; default: no duty. | direct |
| D-55 | Posting under real names or pseudonyms is decided by the organisation's rules per forum. Default real names (taken from the option text; not stated separately). | direct: "organisation's rule decide" |
| D-56 | Read access to geographic forums is decided by the organisation's rules per forum; default: own unit and the units above. Writing stays within the forum's scope; committee forums stay confidential. | direct |
| D-57 | Newsletters reach members and other known participants now; outside subscribers come later as a separate capability. | direct: "(c) members now, outside subscribers later" |
| D-58 | When the rules are silent, nobody may send newsletters in an organisation's name until a competent Governance organ designates an editor for the series and scope. | direct: "I accept option A" |
| D-59 | A newsletter is for official purposes only, never for advertising a candidacy. | direct |
| D-60 | Who may send newsletters is decided by the committee in a meeting (a decision at a sitting). | direct |
| D-61 | An editor who is a candidate is not restricted; protection comes from D-59. | direct: "I accept option A" |
| D-62 | PublicDigit has a personal-messaging capability; each organisation decides whether to use it. | direct |
| D-63 | Private messages not reported by a participant can be read only by order of the independent tribunal in a case, limited to what the order names, with every access recorded. | via paste, accepted (option C) |
| D-64 | The organisation's rules decide which organ switches messaging on and how; default: the committee at a formal sitting. | as reported (option C) |
| D-65 | When competence genuinely cannot be determined from the organisation's rules (a declared gap, not missing platform data), the organisation's rules decide; default: effective until challenged. | via paste, accepted (option C) |
| D-66 | When an occupied position is abolished, the organisation's rules decide; if silent, the tenure ends on the abolition's effective date. History is kept. | via paste, accepted (option C) |
| D-67 | **ARB.** EPIC-001's and EPIC-002's Adjudication are one bounded context; EPIC-001's Determination is extended with EPIC-002's inputs (COL-1 to COL-3), outputs (COL-5a/b) and D1. Closes the reconciliation deferred on 2026-07-25. | direct: "I accept your recommendation" (option b) |
| D-68 | **ARB.** In R-11, `termRef` identifies the basis of a mandate: a tenure or an AuthorityAssignment (delegation). R-11 is not amended. Governance revokes when the delegation, the underlying authority or the delegator's supporting powers end. | as reported (option A) |
| D-69 | When two rules grant an individual the same power under different conditions, the organisation's rules decide precedence; if none is declared, every applicable condition must be met. Uncertainty never creates authority for an individual. | as reported (option D) |
| D-70 | When the rules on an organ's competence contradict each other, the organisation's rules decide precedence; if none is declared, the decision is effective until challenged, and the contradiction goes to the rule-making organ. | via paste, accepted (option C) |

### 11.2 Findings that are evidence, not decisions

- [E] The current vote-casting code links voters to ballots in fourteen ways (F1 to F14): vote ID in the voter's step record; vote ID logged with the slug; a receipt hash of vote ID, user ID and election ID; a vote hash recomputable from the code row; identical timestamps; time-ordered vote UUIDs; cast IP on the code row; code update and vote insert in one transaction (same `xmin`); per-voter audit file; queued emails; adjacent access-log entries; a receipt that reveals ballot content; unencrypted receipt codes. F1 and F2 were already recorded as known gaps (`tests/Feature/Characterization/Phase1/BallotAnonymityCharacterizationTest.php:145-176`). Backlog item `PBDIGIT-337`.
- [E] The membership newsletter audiences include `election_voted` and `election_not_voted` and read other contexts' tables directly (`app/Services/NewsletterService.php:34-36, 113-132`).
- [E] The ratified Membership documents contain no contact details; the ratified Identity scope is person identity and identity equivalence only.
- [E] Membership's ratified KnownAt is defined over `recordedAt ≤ τ`, but the code takes `recordedAt` from the application clock before commit (`PostgresMemberRepository.php:219`), which can break ratified property P3 for queries just after τ.
- [E] No rule anywhere states whether filing a challenge suspends the challenged decision.
- [E] Round 50-05 defines `MandateGranted(mandateId · holderId · scope · termRef · grantedAt)`, producer Appointment, consumers Authorization and Audit; `termRef` has no further definition.

### 11.3 Open question awaiting the Principal

**Adjudication question 1:** between filing and determination, does a challenged decision stay effective, with only the tribunal able to suspend it by interim measure (recommended, as the default under the organisation's rules), or is it suspended automatically?

### 11.4 Open items carried forward

Organisation rules: ex officio rights of a suspended holder; joint organs when one unit ends; transfer of submissions after restructuring; withdrawal after submission; candidates messaging during campaigns. Legal: subscription default, electronic convocation, retention, GDPR Art. 9. Technical: Record-Fixing key management; the Level A security review; RFC 9474 feasibility in the stack.

**Linked:** model document §20–§26.

### 11.5 Decision D-71 (answers §11.3)

| # | Decision | Provenance |
|---|---|---|
| D-71 | Between filing and determination, a challenged decision stays effective unless the organisation's rules provide otherwise. Only the tribunal can suspend it, by an interim measure. Applies to decisions effective under D-65 and D-70, to moderation decisions (D-53) and to election results. | direct: "(c) organisation rules decide, stays effective by default" |

### 11.6 Decision D-72 (ARB)

| # | Decision | Provenance |
|---|---|---|
| D-72 | **ARB.** BDR-03 v1.2: Appointment (Authority) is a capability within Governance, not a separate bounded context. Governance owns the authoritative facts (positions, tenures, delegations, effective powers) and, through its Appointment capability, derives and publishes `MandateGranted` / `MandateRevoked`. Round 50-05, R-11, D-68 and BDR-07 unchanged. The Mandate aggregate question (Round 50-01, Round 50-02, D-7) is not decided by this act. Act: `docs/adr/ADR_20261109_0833_Appointment_Authority_Consolidated_into_Governance.md`; register `Round49-06` amended append-only. | direct: "Yes, I accept the BDR-03 v1.2 act" |

### 11.7 Decision D-73 (ARB)

| # | Decision | Provenance |
|---|---|---|
| D-73 | **ARB.** Membership Change Request CR-01 revision 2 accepted under D-25: C0 (Organisation boundary), C1 (`MembershipCoverageResolved`, complete coverage), C2 (`CoverageKnownAt` with τ), C3 (membership facts for Governance; Governance decides qualification), C4 (τ for Election's predicate queries). Appended to the ratified documents by addition only. The `closedThrough` proof and one shared τ per multi-context operation are mandatory technical requirements. | as reported (accepted review, 2026-11-09) |

### 11.8 Decision D-74 (ARB)

| # | Decision | Provenance |
|---|---|---|
| D-74 | **ARB.** Round 50-01 `AGG — Mandate` is superseded: there is no Mandate aggregate. Authority is held through Position (tenures, restrictions) and AuthorityAssignment (delegation); `MandateGranted` / `MandateRevoked` are derived by Governance's Appointment capability. Round 50-02's merge test is closed (neither; composition is a read model). Round 50-05 unchanged. Formalises D-7. Act: `docs/adr/ADR_20261109_1024_Supersede_Round50-01_Mandate_Aggregate.md`. | direct: "Yes, I accept superseding the Mandate aggregate" |

### 11.9 Decision D-75 (ARB)

| # | Decision | Provenance |
|---|---|---|
| D-75 | **ARB.** XD-3 Identity act: one canonical Person owned by Identity (bare `PersonId`); a different opaque `PersonRef` per organisation, resolvable only by Identity; no cross-organisation disclosure; names and contact details organisation-local (owner per INV-AFF-1); Person ≠ Account, no linking or takeover by matching email or name; membership end, access removal, suspension and deletion are separate acts; C-1 to C-5 bind every mechanism. Open: L1 / L2, K4, legal questions, INV-AFF-1. Act: `2026-11-09-identity-xd3-arb-act.md`. | direct: "Yes I accept XD-3" |

### 11.10 Decision D-76

| # | Decision | Provenance |
|---|---|---|
| D-76 | Whether a power may be delegated is decided by the organisation's rules; **when they are silent, it may not**. A delegated power may be delegated further only if both the rules and the delegation itself permit it. Absences are covered by acting posts or organ decisions. | direct: "i accept your recommendation" (option c) |

### 11.11 Decision D-77 (ARB)

| # | Decision | Provenance |
|---|---|---|
| D-77 | **ARB.** C2 (`CoverageKnownAt`, CR-01 / D-73) also accepts `personRefs[]` as an alternative key. Additive, meaning unchanged. Noted on CR-01 §10 and in the ratified artifact's CR-01 amendment. | direct: "i accept our recommendtation" |

### 11.12 Decision D-78

| # | Decision | Provenance |
|---|---|---|
| D-78 | When a rule withdraws permission to delegate an authority, **the rule-changing organ may specify the transition** for existing delegations (a defined period, until original expiry, or another stated transition). **If it is silent, affected delegations end on the new rule's effective date.** A delegation stays valid until then (subject to earlier expiry or revocation); acts validly done before termination stay valid. From termination the former delegate cannot exercise the withdrawn authority on that delegation; any continuing authority needs an independent basis. Only the affected powers are affected; unaffected scope of the same delegation continues. D-76 unchanged. Answers the open question in model document §37. | as reported (option C; accepted review, 2026-11-09) |

### 11.13 Decision D-79

| # | Decision | Provenance |
|---|---|---|
| D-79 | How members are placed in, or transferred between, units is decided by the organisation's rules. **By default, only individual acts** (an allocation, a transfer decision, or an admission naming the unit) create or change a belonging; an address may produce a **suggestion**, which creates nothing until a responsible person or organ confirms it. Placement by a residence rule is possible only where the organisation's rules expressly adopt it, and that rule is then recorded as the basis. (Organisation question 1.) | direct: "(c) organisation rules decide, individual acts by default" |

### 11.14 Decision D-80

| # | Decision | Provenance |
|---|---|---|
| D-80 | A person's address and the committee structure are two different things. An address in District X does not associate the person with the unit or committee of District X. A unit's coverage says nothing about who lives there. Consistent with D-79: an address may at most produce a suggestion. | direct: "make sure that address of a person and committee structure is two different things. having address in District X does not mean , it is associated with the same committee." |

### 11.15 Decision D-81

| # | Decision | Provenance |
|---|---|---|
| D-81 | **Geographical levels (L-1, option B).** Levels 0 (World), 1 (continent / region) and 2 (country) are global. Levels 3–10 are **classification positions in each country's dated profile**: the profile assigns each class of that country one position, positions strictly increase along containment and may be skipped, and a level is never computed by counting parents. **Addresses and committees read the same profile;** within one country and date no position has two meanings. Profile contents (for example what "5 = Bezirk, 6 = Street" means for Germany) are set per country and are not decided by this ruling. | direct: "I accept L-1 option B" |

### 11.16 Decision D-82

| # | Decision | Provenance |
|---|---|---|
| D-82 | **Official Geography and organisation-defined local areas (Geography Question 1, option A + C).** Reference Geography publishes only geographical facts supported by competent official sources. An organisation may define its own local areas (toles, settlements, neighbourhoods) as organisation-owned facts where official subdivisions are insufficient. Such areas are not official geographical units, receive no Reference Geography identity, are not platform-wide provisional references, belong to the organisation that defines them, are governed by its rules, may be used for organisational structure and committee coverage, and never by themselves determine member belonging or electoral entitlement (D-79, D-80). | via paste, accepted: the Principal's ruling "Geography Question 1 — Option A + C" as recorded in the Principal DDD Architect's note of 2026-11-09, accepted by the Principal in this session ("I accept the recommendations written in the document") |

**D-82 provenance corrected (append-only note, 2026-11-09):** **direct**. The Principal states: "The Principal explicitly ruled Option A+C in the conversation on 9 November 2026. Record this as a direct Principal ruling." The table text above is unchanged.

### 11.17 Decision D-83

| # | Decision | Provenance |
|---|---|---|
| D-83 | **Streets as address components (L-2, option ii).** Streets are address components where relevant, not official Reference Geography units. PublicDigit does not maintain a global street register. Street precision does not establish official street identity. Organisations may describe street-based areas under D-82. | **direct**, ruling of 9 November 2026 (the Principal: "Record D-83 as the Principal's direct ruling of 9 November 2026") |

### 11.18 Decision D-84

| # | Decision | Provenance |
|---|---|---|
| D-84 | **Publication governance of Reference Geography (S-1, option C, rule as worded):** "The platform owner appoints Geography stewards. A steward assesses and publishes official geographical references on recorded evidence: instrument, authenticity, covering mandate, applicability, and any precedence rule applied. Publication never creates official status. While only one steward exists, that steward may publish all changes, with a recorded conflict-of-interest declaration. From the appointment of a second steward, every structural change requires approval by a second, independent natural person; if none is available, the change waits. Organisations may object to a published fact with counter-evidence; the fact changes only by a correction or a recorded dispute." | direct: "I accept S-1 option C with the rule as worded" |

**Note (2026-11-09, not part of the decision):** the Principal DDD Architect's review submitted with the ruling proposes a conflict-of-interest amendment (a declaration does not resolve a material conflict). Put to the Principal as a separate question; D-84 stands as worded unless amended.

### 11.19 Amendment D-84a (conflict of interest)

| # | Amendment | Provenance |
|---|---|---|
| D-84a | **Amends D-84** (D-84's text above is unchanged and remains the record of the original ruling). **Reason:** a conflict-of-interest declaration makes a conflict visible but does not make the declarer impartial; D-84 as worded let a conflicted sole steward decide alone. **Rule:** a Geography steward must not decide a matter in which they have a **material conflict**, meaning a direct organisational interest (holding office in, acting for, or having submitted for an organisation that brought the matter or whose position the outcome favours) **combined with a judgment between competing positions** (an objection, a missing-unit submission, a dispute, a precedence question, an assessment between competing instruments). Such a matter waits for a non-conflicted steward or for an **independent reviewer appointed by the platform owner for that matter alone**: a different natural person with no material connection to the organisations or parties concerned, who declares independence; the appointment, declaration and decision are recorded. The reviewer has authority for that matter only, holds no standing publication authority, and never creates official status. **Mere association with an organisation does not prevent routine publication:** an uncontested official instrument whose mandate, authenticity and applicability are not in question, or a routine correction with no competing claim, may be published by the steward with the recorded evidence and declaration. | via paste, accepted: the Principal DDD Architect's commission states "The Principal has accepted the recommendations in the latest review, including the proposed conflict-of-interest refinement to D-84", accepted by the Principal ("I accept the recommendations written in the document"), answering the explicit question "amend D-84 or keep it as worded" |

### 11.20 Decision D-85

| # | Decision | Provenance |
|---|---|---|
| D-85 | **Level 1 regions (L-3, option A).** Level 1 holds the five UN M49 regions: Africa, Americas, Asia, Europe, Oceania. They are **statistical groupings**; their authority is the UN Statistics Division's competence in statistical classification, not territorial or political. They imply no political affiliation, sovereignty or legal boundary. PublicDigit defines no competing continent classification. Organisation-defined international regions remain the organisations' own (D-82). M49 sub-regions are **not** part of level 1; they may be considered later as a separately identified grouping. D-81 is unchanged. | **direct**, 2026-11-09: the ruling "L-3, Option A" given in the Principal DDD Architect's session, recorded as direct at the Principal's instruction ("The provenance should be recorded as a direct Principal ruling"), accepted in this session |

### 11.21 Decision D-86

| # | Decision | Provenance |
|---|---|---|
| D-86 | **Countries and areas at level 2 (L-4, policy A′).** (1) Level 2 holds neutral **"country or area"** entries; an entry asserts no sovereignty, recognition or political status. (2) PublicDigit owns each entry's identity; ISO 3166-1 and M49 codes are external identifiers only. (3) **ISO 3166-1 entries are admitted by default** under platform policy; admission permits an entry to exist and is not recognition; changes to the ISO list are handled through governed publication (D-84, D-84a) and never silently rewrite history. (4) **Any other entry is admitted only by an explicit, recorded Principal decision.** (5) Level-1 region membership (D-85) only from allowed evidence (a published M49 row, a verified special-purpose code recorded as such, or an explicit platform-policy assignment); never by inference. (6) No relationship between level-2 entries is recorded in the baseline; future relations must be typed and sourced, never classification containment. Display names (L-4b) and region assignment where no M49 row exists (L-4c) remain separate questions. | direct: "yes i accept option A." clarified as "yes A'" |

### 11.22 Decision D-87

| # | Decision | Provenance |
|---|---|---|
| D-87 | **Display names of countries and areas (L-4b, option C).** The name shown to users for a country or area is the **Unicode CLDR** territory display name **in the user's language**: CLDR's standard form, with CLDR's short form only where space is tight. Official source names (ISO 3166-1, UN M49 and others) are kept as **attributed alternative names** visible in the place's details. PublicDigit authors no names and settles no naming dispute itself; a new CLDR release is evidence for a name revision, a non-structural change under D-84. Organisations continue to name their own units freely (D-82). Any exception to the CLDR rule requires a further explicit Principal decision. | direct: "I accept L-4b option C" |

### 11.23 Decision D-88 (interpretation of D-84)

| # | Decision | Provenance |
|---|---|---|
| D-88 | **Second person under D-84 (interpretation T).** D-84's "second, independent natural person" is second to **the steward who prepared and assessed the change**. From the appointment of a second steward, a structural change requires approval by a natural person (a steward or a matter reviewer under D-84a, a distinct canonical person) **other than the steward who prepared and assessed it**. The preparer can never approve their own structural change; two persons suffice. D-84 and D-84a are otherwise unchanged. | direct: "I accept option T" |

### 11.24 Decision D-89 (interpretation of D-84: the second-person threshold)

| # | Decision | Provenance |
|---|---|---|
| D-89 | **Option L (latching).** From the **first appointment of a second steward**, the requirement that every structural change be approved by a second, independent natural person (D-84, as interpreted by D-88) **remains permanently active**. It does not cease when the second steward's appointment ends. Consequences: a sole remaining steward cannot publish a structural change alone; a structural change waits until an eligible independent second person is available; a matter reviewer appointed under D-84a may provide that review for the particular matter; non-structural changes remain governed by D-84; the governance record must show when the threshold first activated. Resolves the divergence between D-84's clauses "while only one steward exists…" and "from the appointment of a second steward…". | via paste, accepted: "Principal ruling accepted — D-84 Option L", recorded in the Principal DDD Architect's note of 2026-11-09 and accepted by the Principal in this session ("I accept the recommendations written in the document") |

### 11.25 Decision D-90 (published facts after a defective approval is discovered)

| # | Decision | Provenance |
|---|---|---|
| D-90 | **Option K (keep, flagged).** If, after publication, Identity establishes that the second person who approved a structural change was the same natural person as someone who prepared or assessed it (so D-88 was not met in substance), the approval is recorded as **valid when given but substantively defective**; a **governance finding** is recorded; **history is not rewritten**. The published facts **stay in force**, visibly marked **approval-defective** in their explanation and in a published notice, until a valid second person **confirms** them (appended) or a **correction** replaces them. Consuming contexts see the flag and apply their own rules. Retroactive voiding and suspension are not used. | direct: "K" (answer to the question "K or S?", 2026-11-09) |

### 11.26 Decision D-91 (ADMISSION is structural)

| # | Decision | Provenance |
|---|---|---|
| D-91 | **ADMISSION of a country-or-area entry is a structural change** under D-84. Admission changes what exists in the register. ISO 3166-1 entries remain admitted by default and non-ISO entries still need the Principal's explicit decision (D-86); these are **admission authority**. Separately, **publication governance** applies: once D-89 is active, every admission publication requires an independent second person (D-88). Changing an existing entry's name or external identifier does not become structural because it concerns that entry. | via paste, accepted: "ADMISSION is STRUCTURAL. Accepted on 10 November 2026", recorded in the Principal DDD Architect's note and accepted by the Principal ("I accept the recommendations written in the document"), answering the question "structural or not structural?" |

### 11.27 Decision D-92 (GROUPING is non-structural)

| # | Decision | Provenance |
|---|---|---|
| D-92 | **GROUPING is a non-structural change** under D-84: moving a country-or-area entry to another UN M49 level-1 region **on the evidence of a published M49 row or a verified special-purpose code** (D-86 (5)). One eligible steward may publish it, with evidence, valid dates and history kept; D-84a applies if the matter is contested. Organisations decide what a regrouping means for their own coverage (D-79, D-80, D-82). **Region assignment by an explicit platform-policy assignment is not covered; it stays with L-4c (deferred).** | direct, 2026-11-10: "Non-structural (Recommended)", the answer to the question "GROUPING … structural or non-structural?" |

### 11.28 Decision D-93 (Reference Geography baseline ratified)

| # | Decision | Provenance |
|---|---|---|
| D-93 | **The Reference Geography strategic baseline (§C), tactical DDD design v3.11 and the baseline coherence assessment are ratified as PublicDigit's architectural baseline for Reference Geography.** Ratification accepts the domain design only; PO-1 to PO-8 and the Identity contract remain implementation proof obligations; L-4c, Q-2, Japanese classification, naming policies and address ownership remain deferred; no implementation is authorised. Act: `2026-11-10-reference-geography-baseline-ratification-act.md`. | direct, 2026-11-10: "Ratify (Recommended)" |

### 11.29 Decisions D-94 and D-95 (ARB authority; Geography roadmap exception)

| # | Decision | Provenance |
|---|---|---|
| D-94 | **ARB authority.** The Principal, acting as Human ARB Chief, exercises the final Human ARB authority for PublicDigit. **No separate ARB body is currently constituted.** Any future change to the ARB's composition or authority requires an explicit Principal decision. D-93 and the earlier acts recorded as ARB acts (including D-67, D-72 to D-75, D-77) **stand as recorded**; their dates, provenance and meaning are not rewritten. Closes the open authority question "whether the ARB and the Human ARB are the same authority" (status record §9a; roadmap phase 2b). | via paste, accepted: "Ruling 1 — Human ARB authority", in the Principal DDD Architect's note of 2026-11-10, accepted by the Principal ("I accept the recommendations written in the document") |
| D-95 | **Reference Geography roadmap exception.** Reference Geography was **intentionally** ratified (D-93) ahead of the phase order of `docs/plans/20261108-0303-organisational-baseline-to-tactical-design-roadmap-plan.md`, justified by its platform-level responsibility (D-31) and the need to fix its boundaries before Organisation's strategic baseline. **The roadmap's remaining sequence and the ratified Geography design are unchanged;** D-2 continues to apply to everything else. | via paste, accepted: "Ruling 2 — Reference Geography roadmap exception", same note, same acceptance |

### 11.30 Decision D-96 (Membership ratification under D-94)

| # | Decision | Provenance |
|---|---|---|
| D-96 | **D-94 also confirms the Membership ratification of 2026-10-08** made by "the Principal Reviewer" (a human act, `docs/publicdigit/reviews/2026-10-08-membership-architecture-ratification-act.md`): the Principal Reviewer acted with the Human ARB authority described in D-94. The ratified Membership architecture stands as recorded; its date, provenance and content are not rewritten. D-25 (the Principal approves Change Requests; the Principal Reviewer is advisory) is unchanged. | direct, 2026-11-10: "Yes, confirm it (Recommended)", the answer to the question "Does D-94 also confirm the Membership ratification?" |

### 11.31 Decision D-97 (Organisation scope: written rules of any form; ODA-M-20, first part)

| # | Decision | Provenance |
|---|---|---|
| D-97 | **PublicDigit serves organisations that have written rules of any form:** a formal constitution (statutes), **or** a rules document the organisation has adopted by a recorded decision. **Organisations governed only by unwritten custom are not in scope**, because competence (D-12) and powers (D-38) must be traceable to a written provision. Where a formal statute exists, it is recorded. In the candidate definition (ODA-01 §3.1), "under its own constitution (statutes)" reads as **"under its own written rules (a constitution or an adopted rules document)"**. Answers the first part of ODA-M-20; whether companies, memberless foundations and unions are in scope remains open. The meaning of "Organisation" as a whole (ODA-M-2) is not yet ruled. | direct, 2026-11-10: "B", the answer to the question "A, B or C?" (A formal statutes only; B written rules of any form; C unwritten rules too) |

### 11.32 Decision D-98 (kinds of body in scope; ODA-M-20, second part)

| # | Decision | Provenance |
|---|---|---|
| D-98 | **In scope now: membership-based bodies** with written rules (D-97): political parties, associations (including diaspora associations), unions, and federations of such bodies. **Companies and memberless foundations are not in scope now;** they may be added **later, as a separate capability, by their own Principal decision and discovery**. D-32's "one ballot per person" and the ratified Membership model are **not reopened**. Completes ODA-M-20. ODA-M-2 (the meaning of "Organisation" as a whole) is not yet ruled; federations are named as a kind of body only, and BR-13a (no cross-organisation governance now) is unchanged. | direct, 2026-11-10: "C", the answer to the question "A, B or C?" (A membership-based only, permanently; B every body with written rules, now; C membership-based now, the others later as a separate capability) |

### 11.33 Decision D-99 (sections: unit or separate organisation; ODA-E-1)

| # | Decision | Provenance |
|---|---|---|
| D-99 | **Whether a section (a wing, a branch, a chapter, a "sister organisation") is part of an organisation or a separate organisation is decided by where its authority comes from.** If it exists **under the parent's written rules**, it is a **unit or wing of the parent** (belonging under D-3; its committees act under the parent's authority). If it has **its own written rules adopted by its own members** (D-97), it is a **separate Organisation**, with its own registry, members and governance; **PublicDigit gives the other organisation no governing power over it** (BR-13a), and their data stay separate. Each case is determined from the organisations' own documents; nothing is hard-coded. Where the documents are not on file (e.g. NRNA), the classification is **UNKNOWN**, never assumed. Answers ODA-E-1. | direct, 2026-11-10: "C", the answer to the question "A, B or C?" (A always a unit; B always a separate organisation; C decided by where its authority comes from) |

### 11.34 Decision D-100 (the meaning of "Organisation"; ODA-M-2)

| # | Decision | Provenance |
|---|---|---|
| D-100 | **Definition.** "An Organisation is a self-governing, membership-based collective body (a political party, association, union or federation of such bodies) with its own identity and purpose, governed by written rules: either a constitution or another rules document formally adopted by a recorded decision. It conducts its internal affairs through the organs and office holders authorised under those rules, subject to applicable law and other binding external rules." **Federation note:** "A federation is in scope where its members take part as natural persons, directly or as delegates. Membership of organisations in a federation is not modelled now (BR-13a)." **Explanatory principles (unchanged from ODA-01 §3.1 and D-99):** identity persists through changes of name, structure, rules, leadership and status; it does not depend on PublicDigit, and its authority is independent of the software; Membership, Governance, Election and other capabilities serve it, none constitutes it; a platform record is not an Organisation and tenancy is not part of the definition; sections are classified under D-99. "Subject to applicable law" qualifies the organisation's conduct; it is not a PublicDigit promise to interpret law. Consolidates D-97, D-98, D-99 and Decision A; answers ODA-M-2. The vocabulary question O-14 is otherwise unchanged. | direct, 2026-11-10: "Refined + federation note (Recommended)", the answer to the question "Which version do you accept?" |

### 11.35 Decision D-101 (ownership of affiliation records and the person profile; INV-AFF-1 / BR-9, ownership part)

| # | Decision | Provenance |
|---|---|---|
| D-101 | **Each organisation owns its affiliation records** (a person's association with that organisation, in a capacity: User, Member, Worker, Guest, voter) **and its own record of each person** (the organisation-local person profile: name, contact details, address as supplied, with its components and resolution). They are kept within that organisation and never disclosed to another organisation (XD-3 §2 items 3 to 5). **The sign-in discovery mechanism is not decided here:** it is left to Identity's tactical design, within XD-3's conditions C-1 to C-5, once its feasibility has been evaluated; **no mechanism is foreclosed** (the stored minimal mapping L1 and the keyed derivation L2 both remain open). Answers the ownership part of BR-9 and of INV-AFF-1; INV-AFF-1's stricter "no persisted central register" wording is **not adopted** and remains a proposal tied to the mechanism. **Settles address ownership** (previously a recommendation in the Geography baseline §2a and §C.2): the organisation-local person profile, within Organisation. Membership does not own it (staff and guests have addresses, V-54). D-79 and D-80 (an address never establishes belonging) are unchanged. | direct, 2026-11-10: "B", the answer to the question "A, B or C?" (A adopt INV-AFF-1 as worded; B decide ownership now, mechanism later; C leave it open) |

### 11.36 Decision D-102 (Identity's responsibility boundary: account lifecycle and authentication; authorisation stays with each owning context)

| # | Decision | Provenance |
|---|---|---|
| D-102 | **Identity establishes and manages the trusted relationship between a human Person, their PublicDigit Account and the authenticated session.** It owns: registration and Account creation; **verification of the login address** (the credential; contact addresses stay in the organisation's own person profile, D-101); authentication and login; password reset and recovery (recovery without the login address remains open, P-I3); Account suspension and lifecycle (R-XD3c-1…5); session management; connecting an existing Person to an Account through an organisation-issued claim (D-75); and the sign-in **discovery mechanism** (still undecided, D-101). **The application/presentation layer orchestrates the registration and sign-in screens but must not implement Identity's business rules.** **Identity is not the permission authority:** access to an organisation's workspace follows that organisation's rules; membership is Membership's; belonging is Organisation's; election eligibility is Election's; ballots are Voting's (VO-1); official authority is Governance's. Selecting an organisation sets a working context and grants nothing. Account creation never creates an affiliation or a Membership (D-100, D-101). Registration without a claim may exist; its journey is still to be designed. No separate bounded context for registration. Authentication technology (Laravel today; Keycloak or another provider possible later behind a port) does not change this boundary. | via paste, accepted: the Principal DDD Architect's notes of 2026-11-10 ("Identity … should not own all authorisation decisions"; "record this as an accepted working architectural direction … not yet a ratified architecture decision"), accepted by the Principal ("I accept the recommendations written in the document"). Recorded with two precisions by the recorder: "e-mail verification" read as login-address verification; "organisation discovery and selection" split into Identity's discovery mechanism and a session working context. |

### 11.37 Decisions D-103 … D-105 (Organisation baseline coherence: B-2, B-3, B-1 with B-4)

| # | Decision | Provenance |
|---|---|---|
| D-103 | **The affiliation capacity "Member" reflects Membership's facts; it never establishes membership.** An organisation cannot make a person a formal member by recording an affiliation. Whether, and since when, a person is a member is stated only by Membership (RAT), which Organisation consumes through the ratified CR-01 contract (C1 `MembershipCoverageResolved`, C2 `CoverageKnownAt`; D-73, D-77). One source of membership truth. D-101's list of capacities is unchanged; this ruling fixes what "Member" means in it. | via paste, accepted: coherence review 2026-11-10 decision B-2, option (a) "reflection", recommended in the Principal DDD Architect's note of 2026-11-10 ("accept B-1, B-2 option A, B-3 and B-4"), accepted by the Principal ("I accept the recommendations written in the document") |
| D-104 | **Workspace access is a distinct business fact, owned by the Organisation:** granted under the organisation's own rules, technically enforced by Authorization (which owns no business-domain truth). It is not implied by an Account, by sign-in, by selecting a working organisation, by an affiliation or by Membership, and it can be withdrawn without changing any of those (D-100, D-102). | as D-103 (decision B-3) |
| D-105 | **(B-1)** The Organisation strategic baseline is corrected before its acceptance decision: it cites the ratified Membership → Organisation contract (CR-01 C1/C2) instead of describing that contract as absent or provisional; it distinguishes **Membership time coverage** (Membership's term) from **unit coverage** (the territory a unit serves, D-80); it states D-103 and D-104. **(B-4)** The Principal rulings D-79, D-80, D-97 … D-105 remain **separate decision acts**, referenced by the baseline; accepting the baseline does not re-ratify or absorb them. | as D-103 (decisions B-1 and B-4) |

### 11.38 Decision D-106 (Organisation strategic baseline accepted)

| # | Decision | Provenance |
|---|---|---|
| D-106 | **The Organisation strategic architecture (baseline v1.0, revision 2c, §13: eight architectural conclusions) is accepted.** D-79, D-80 and D-97 … D-105 remain separate acts (Decision B; D-105 B-4). Working decisions D-1 … D-30, the §8.3 working model, BR-10, BR-11 and D-102 remain provisional. No tactical design fixed; no implementation authorised. Act: `2026-11-10-organisation-strategic-baseline-acceptance-act.md`. | direct, 2026-11-10: Decision A "Accept (Recommended)", Decision B "Keep as separate acts (Recommended)", confirmed for revision 2c: "Yes, 2c; record it here" |

### 11.39 Decisions D-107 … D-110 (people register: O-1, O-2, O-3; one registration)

| # | Decision | Provenance |
|---|---|---|
| D-107 | **(O-1) Registration creates an Account only, and no affiliation of any kind.** The bootstrap PublicDigit record is platform infrastructure (D-100) and holds **no affiliations**; platform welcome, demo and onboarding are platform features, not organisational relationships. **Supersedes the "User affiliation" parts of BR-2, BR-8 and BR-12** (approved 2026-11-01/02): BR-2 and BR-8 are re-read as "a person who registers becomes a registered user of PublicDigit, i.e. holds an Account"; BR-12's statement that registrants are **not** affiliated with the operator organisation stands; its clause that the bootstrap holds "the User affiliation created at registration" is superseded. The texts of BR-2, BR-8, BR-12 are not rewritten (an amendment note is appended to their record). The accepted Organisation baseline (D-106) lists BR-2, BR-8, BR-12 among approved rules; this ruling governs where they conflict. | via paste, accepted: Principal DDD Architect's note of 2026-11-10, §8 "O-1: A — Account creation only; no affiliation", accepted by the Principal ("I accept the recommendations written in the document") |
| D-108 | **(O-2) "User" is not a standard affiliation capacity of an Organisation.** A person can be in an organisation's register with no capacity at all. Workspace access is its own fact (D-104). A login account is never a reason for an affiliation. An organisation's own rules may later name additional relationships, recorded explicitly (organisation-specific capacities remain UNKNOWN until statutes are on file). Narrows D-101's example list of capacities by removing "User". | as D-107 ("O-2: Drop as a standard affiliation capacity") |
| D-109 | **(O-3) In election-only organisations, the voter-related relationship is the Organisation's record** (an affiliation in the capacity "voter", in the organisation's own people register), **and Election consumes it as an input.** Being recorded as a voter **never** creates eligibility or entitlement: Election alone decides the electorate, eligibility and entitlement at τ, and Voting enforces ballot rules (RAT boundary; VO-1). | as D-107 ("O-3: A — Organisation records voter-related relationship; Election decides entitlement") |
| D-110 | **One registration, one Account, many organisations.** A person registers once with PublicDigit, verifies one login address and holds one Account (XD-3: zero or one Account per Person, RAT), and maintains relationships with several organisations through it. **Registration and authentication are never repeated per organisation.** Each organisation independently owns its person record, profile, affiliations and workspace-access decisions; connecting the Account to an organisation's record follows Identity's claim process (mechanism open, P-I1). Not a guarantee of uniqueness: because matching by e-mail or name is forbidden (D-75), a duplicate registration can occur and is resolved only by Identity's explicit act (C-5). | via paste, accepted: the Principal DDD Architect's note of 2026-11-10 ("one registration and one account, with multiple independently managed organisational relationships"), accepted by the Principal; uniqueness caveat added by the recorder from D-75/C-5 |

### 11.40 Decision D-111 (single platform identity, independent organisational data ownership)

| # | Decision | Provenance |
|---|---|---|
| D-111 | **Single platform identity, independent organisational data ownership.** A human registers once on PublicDigit and holds one platform Account (D-110; XD-3 0..1). **Identity** owns the platform identity: the canonical Person, organisation-scoped `PersonRef` issuance and resolution, the Account, login-address verification, authentication, sessions, the account lifecycle, claims and the discovery mechanism (XD-3, RAT; D-102). **Each Organisation's business data belongs to that organisation**: its people records and profiles, affiliations and voter-related relationships, workspace-access decisions, its membership records, its election records and its finance records and transactions. No other organisation sees them, and PublicDigit does not become their owner by providing the login. **Precisions (recorder):** (1) "belongs to the organisation" is **data ownership and isolation**; the bounded contexts that hold the rules stay as they are, each one context applied per organisation: Membership owns membership truth (RAT), Election owns the electorate, eligibility and entitlement (RAT boundary; the organisation's voter relationship is its input, D-109), Finance owns payment and settlement (RAT), Organisation owns people records, affiliation and access (D-101, D-104). (2) **Logical** separation is the invariant; whether an organisation's data (including Finance) sits in a **separate physical database** is the open tenancy decision (D-1 option C candidate; HD-8), not decided here. (3) Identity's minimised discovery information (C-4, mechanism open) is platform access infrastructure, not ownership of organisational records. | via paste, accepted: Principal DDD Architect's note of 2026-11-10 ("Agreed. This is now an accepted working architecture direction (WD)"; invariant "Single Platform Identity, Independent Organisational Data Ownership"), presented by the Principal; precisions (1)–(3) added by the recorder against XD-3 (RAT), the Membership act (RAT) and the open tenancy decision |

### 11.41 Decision D-112 (amendment note A-1 to the accepted Organisation baseline)

| # | Decision | Provenance |
|---|---|---|
| D-112 | **An append-only amendment note A-1 is added to the accepted Organisation strategic baseline** (`docs/publicdigit/architecture/reviews/2026-11-10-organisation-strategic-baseline-v1.0.md`): it names the statements whose reading changed under **D-107** (registration creates an Account only; the "User affiliation" parts of BR-2, BR-8 and BR-12 superseded) and **D-108** ("User" is not a standard affiliation capacity), states the current reading, and leaves the accepted text unchanged. **D-106 stays accepted**; the note is a separate act. | direct, 2026-11-10: "Approve the note (Recommended)", the answer to the question "Approve an append-only amendment note to the accepted Organisation baseline (D-106)?" |

### 11.42 Decision D-113 (P1 / P-I1 / K4: identity scope; reference at import, Person at claim)

| # | Decision | Provenance |
|---|---|---|
| D-113 | **(R2b)** **PublicDigit's canonical Person represents people who have registered.** When an organisation records someone who has not registered, **Identity issues that organisation's opaque `PersonRef` without creating a Person.** When the person registers and **validly claims** the record (an **organisation-issued one-time claim code** plus a **verified login address**; **possession of a `PersonRef` proves nothing**), Identity **binds** the reference to their Person. **Binding is not a merge.** **At most one of an organisation's references may be bound to one Person**; a second is refused and reported to that organisation. **Clarifies two accepted texts, without editing them:** (1) the Principal's import clarification of 2026-11-01 (XD-3b §1) and **BR-10**: no Person is created at import; the reference is still issued at import; (2) **XD-3b §2a**: the claim code is tied to the organisation's `PersonRef`, not to a `PersonId`. **Answers K4 and P-I1.** Unchanged and still open: the issuance and discovery mechanism (L1 / L2, P-I2), merge policy beyond "binding is not a merge" (C-5), legal questions (OQ-3a-1 and others). Ratified Membership, Election and Finance are unchanged. Brief: `docs/publicdigit/architecture/reviews/2026-11-10-p1-identity-scope-decision-brief.md` (§11, §12). | direct, 2026-11-10: "R2b, accept (Recommended)", the answer to the P1 question stating the proposition and both clarifications |

### 11.43 Decision D-114 (no effective member without a unit: the root unit by default; §50.12 of the model document)

| # | Decision | Provenance |
|---|---|---|
| D-114 | **Every effective member belongs to a geographic unit on every date. Where no lower unit applies, that unit is the organisation's root unit** (the top unit, whose organ is the Highest Committee or its equivalent; members belong to the unit, not to the committee, PO-DR07-01). **An organisation may start with only its root unit; all members then belong to it.** When a lower unit is created (for example province X), **members move down to it only on a basis under D-79**: an allocation in the creating decision or a later placement act, **or** a residence rule the organisation's written rules expressly adopt, recorded as the basis; **an address alone never moves anyone (D-80)**. **Members not moved stay where they are** (for example, in province Y, members not allocated to its new districts D1 and D2 stay in Y; members of no province stay in the root unit). The same applies at every level. This answers §50.12 of the 2026-11-08 model document **neither as A nor as B: there is always a unit, because the root unit always exists**; an organisation that admits members "centrally" admits them into its root unit. Not decided here: how many geographic belongings a member may hold (the organisation's rules; V-24 [PK]); wings and other capacities. | direct, 2026-11-10: "yes but the effective member is associated to upper unit in that case. Suppose an organication can only have first the highest committee. All mebmers are assocaited with that highest committee. Two months later, a province X make an unit and province committee. All members related to that province X are under that province X and the rest member still stay under the highest committeee. … in the third month The provoince Y creates its District D1 and D2. all members related to D1 are under D1, all members related to D2 are under D2. but the rest member of province Y are still under Y." **Recorder's reading** (stated to the Principal): "related to" is read under D-79 and D-80 (allocation act or adopted residence rule; never an address alone); "under the highest committee" is read as belonging to the root unit |

### 11.44 Decision D-115 (working direction: default authority for geographical allocation within a province)

| # | Decision | Provenance |
|---|---|---|
| D-115 | **WD (working direction, not a constitutional rule).** **A province's committee is the default authority for deciding geographical allocation within that province, unless the highest committee decides differently.** It applies **where the organisation's written rules do not settle the competence**; where they do, the rules govern (D-97, D-106). A decision of the highest committee on the same scope prevails over the province committee's. Modelled as a default, never assumed regardless of the written rules. Also recorded with it (same source): **closing an organisational unit is a governed structural transition** whose coverage, dependent allocations and member belongings are reassessed and resolved, never a deletion; historical records stay available. | via paste, accepted: the Principal DDD Architect's notes of 2026-11-10 ("Province Y's Committee is the default authority for deciding geographical allocation within Province Y, unless the highest committee decides differently"; "Model this as a default authority arrangement, not as a universal constitutional rule"; the reverse-lifecycle section), accepted by the Principal ("I accept the recommendations written in the document") |

### 11.45 Decisions D-116 and D-117 (allocation rules versus individual decisions; effect on existing members)

| # | Decision | Provenance |
|---|---|---|
| D-116 | **(Q-1, option a)** Default where the organisation's written rules are silent: **an individual exception** (an act that expressly places a member **notwithstanding the allocation rules**) **prevails over a general allocation rule while it stands.** It ends by an act ending it, a later decision for that member, a later decision of an equal or higher authority that **expressly addresses it**, or the closure of its destination (the member then re-derives by the rules and is flagged). **An ordinary placement** (an allocation, a transfer, an admission naming a unit; D-79) **is a floor:** the rules may still place the member further down. | direct, 2026-11-10: "(a) Exception wins (Recommended)" |
| D-117 | **(Q-2, option a)** **A new allocation rule applies to all qualifying members, existing and future, from its effective date**; nothing changes before that date (no retroactive effect); valid exceptions under D-116 remain. | direct, 2026-11-10: "(a) All members from its date (Recommended)" |

### 11.46 Decisions D-118 … D-121 (answers to IR-OTD-01 H-1, H-2 with M-1, H-4, M-5)

| # | Decision | Provenance |
|---|---|---|
| D-118 | **(H-1)** Platform default: **one geographic member belonging per member per date**; wing belongings and staff, guest or other non-member capacities are additional. An organisation's written rules may allow more. | direct, 2026-11-10: "One by default (Recommended)" |
| D-119 | **(H-2, M-1)** **A higher body's decision prevails over a lower body's on the same scope, whatever their order**; a lower body's later act cannot undo it. **An exception may be granted only by the authority of the rule it overrides, or a higher one.** **An area allocation targets only a direct child of its scope;** a higher body overrides a lower scope's rule **by deciding within that scope** (D-115), never by a rule that jumps past it. The organisation's written rules may provide otherwise. | direct, 2026-11-10: "Higher prevails; override in scope (Recommended)" |
| D-120 | **(H-4)** **An organisation has its root unit for as long as it has effective members:** the root is established before the first admission and **cannot be closed while any membership coverage exists**. Dissolving the whole organisation is a separate act, not a unit closure. | direct, 2026-11-10: "Yes, root required (Recommended)" |
| D-121 | **(M-5)** **"Awaiting placement" means only that no rule or act places the member** (they sit in the fallback unit). Members whose new unit the rules determine after a closure receive a separate **"moved by closure: review"** notice, not the flag. | direct, 2026-11-10: "Only unanswered cases (Recommended)" |

### 11.47 Decision D-122 (D-118 confirmed; no silent displacement by a new root)

| # | Decision | Provenance |
|---|---|---|
| D-122 | **(1) D-118 stands** (one geographic member unit per member per date by default; an organisation's written rules may allow more), confirmed after the Principal DDD Architect recommended replacing it with "no platform default". **(2) When an organisation inserts a new top unit above its existing root, members whose effective unit is the old root without a recorded placement stay with the old root** (now a child unit) **unless the restructuring decision moves them**; no silent displacement. The notice for members whose unit the rules re-determine after a closure (D-121) is named **`PlacementReviewNeeded`**. | direct, 2026-11-10: "Keep D-118 (one by default)"; "Stay unless decided (Recommended)" |

### 11.48 Decisions D-123 … D-135 (strategic baseline closure: Election entitlement, people register, fees)

Source: `docs/publicdigit/architecture/reviews/2026-11-10-principal-strategic-baseline-decision-paper.md` (Decisions 1–9; wording there governs where this summary is shorter). All are **working directions (WD)**, not ratified. Provenance quotes the Principal's selected answer, given directly in session on 2026-11-10.

| # | Decision | Provenance |
|---|---|---|
| D-123 | **(R-ID′, voting channels)** **Entitlement does not depend on an Account.** Election decides entitlement on the organisation's electorate entry. A right is issued through a **channel the organisation's rules authorise for that election**: by default online to a Person **bound by claim** to the entry's reference (D-113); where authorised, another channel (for example a one-time credential to contact details the organisation has verified, or in-person issuance by the election committee). **Each electorate entry receives at most one right across all channels**, and each claimed Person at most one. Before opening, the election committee is shown the number of entitled entries without a claimed Person; the result reports "entitled, no right issued". Postal or paper voting is out of scope (UNKNOWN). | direct, 2026-11-10: "Channels by rules (Recommended)" |
| D-124 | **(P4, duplicates after an Identity merge)** Two of an organisation's entries resolving to one Person after an Identity merge are **one voter**. Same races: one right. Different races: **that person's whole right is held** until the organisation names the governing entry, then released; unresolved at close means no vote, recorded. Archived or pending entries are not in the electorate. A disputed merge follows Identity's answer at issuance; reversal after voting goes to Contestation. **No whole-election block.** The number of held rights is published. **Guarantee wording:** "At most one right per electorate entry, and per canonical Person where entries are claimed; duplicate Persons are handled once Identity has merged them. PublicDigit does not guarantee one right per human while duplicates remain undetected." The organisation may merge its own records under its rules (IR-OPR-01 F-8). | direct, 2026-11-10: "Accept as written (Recommended)" |
| D-125 | **(E-3′a, electoral divisions)** An organisation may define **electoral division schemes** and assign the people it records (its register entry, by `PersonRef`) to divisions, by individual act, authorised import or adopted residence rule, **never by an address or an organisation area alone** (D-79, D-80, D-82). Assignments have effective periods and a recorded basis, and **grant no organisational belonging, rights, forum, audience or authority**. Election reads them as at its cut-off, as known then (Organisation → Election contract, to be added). A post district is a set of units or a set of divisions. Without an assignment, no regional race from that scheme unless the rules provide a fallback assignment. Applies to membership-mode and election-only organisations; independent of Membership and of the voter relationship. Accepted in principle; used where an organisation's rules need it. | direct, 2026-11-10: "Accept in principle (Recommended)" |
| D-126 | **(E-3′b)** A second division assignment **in the same scheme** for the same date is **refused when assigned, unless the organisation's written rules allow it**; where allowed, D-127 governs at election time. | direct, 2026-11-10: "Refused unless rules allow (Recommended)" |
| D-127 | **(E-4′, overlaps)** A genuine overlap exists only where a voter qualifies for races filling seats of **the same organ through alternative constituencies** and the written rules do not say whether that is allowed. Nested districts and races for different organs are not overlaps. **Only the affected races** are held until the authority the rules name decides (D-65 if none); other races proceed if the election committee so decides. No default entitlement is invented. | direct, 2026-11-10: "Hold affected races (Recommended)" |
| D-128 | **(E-5′, secrecy)** PublicDigit **describes each race's secrecy layer by layer** (stored-data unlinkability; secrecy while voting; inference from published results; its own claims; the organisation's constitutional requirement) and **never as more than holds**. A race below the organisation's minimum opens only on a recorded decision of the authority the written rules name; **no operational decision overrides a written secret-ballot requirement**. No platform-wide number. | direct, 2026-11-10: "Accept (Recommended)" |
| D-129 | **(P2, archiving; M1)** A register entry may be archived **only when no current relationship remains**; the "Member" capacity reflecting an active Membership (D-103) counts. Archiving **never** ends Membership, retires a `PersonRef`, ends an affiliation or withdraws access by itself; each is its owner's separate act. New relationships on an archived entry are refused. Unarchiving is allowed, forward-only. Supersedes the cascade in people-register design rev. 3 (to be corrected in rev. 4). | direct, 2026-11-10: "Only if nothing active (Recommended)" |
| D-130 | **(P3, capacities)** Worker and Guest relationships may overlap, each with its own description. **At most one active standing voter relationship per register entry.** No capacity implies another; unit capacities, offices (Governance) and workspace access (D-104) are separate facts; **no employment model**. The rules may add limits. | direct, 2026-11-10: "Accept (Recommended)" |
| D-131 | **(P5, P6)** **P5:** election-only organisations keep a **standing voter relationship** (D-109): not Membership, not entitlement, not the carrier of a division. **P6:** the stages recorded, reference issued, claimed, voter relationship confirmed, entitled at T, right issued are never merged; **pending is never a voter** (shown to the committee as a count); the row key is the organisation's own identifier, otherwise file plus row as **an import occurrence only**; re-import applies corrections with the import as basis; **omission never archives**; **an import never creates an Account** (D-107). | direct, 2026-11-10: "Accept (Recommended)" |
| D-132 | **(G-1, fees and voting)** PublicDigit supports **both** arrangements, as the written rules determine. **A:** arrears change Membership status only by Membership's own act on Finance evidence; Election uses C4 unchanged (needs the separate ARB act on the FeePaid direction between Finance and Membership). **B:** Membership continues; Election obtains a **Finance settlement fact for the rule's fee period, as known at its cut-off**, and applies the rule (Finance → Election contract, to be added). **No payment predicate in C4.** A disputed or indeterminate fact with silent rules **holds** the affected right; it is never assumed. | direct, 2026-11-10: "Support both (Recommended)" |
| D-133 | **(E-1, E-2)** **E-1:** a central account field (`users.region` or any other) never determines a voter's regional ballot; Election decides from the organisation's facts at its cut-off. **E-2:** each regional post has a post district defined by Election per election (units or divisions, D-125); the voter's regional ballot is fixed at cut-off T (τ = T) and kept in the snapshot; **display, preview, right issuance and final-cast validation use that one decision**. | direct, 2026-11-10: "Accept all three (Recommended)" |
| D-134 | **(E-6′, regional candidacy)** Whether a candidate for a regional post must belong to its district (unit or division) is the organisation's rule (Governance criteria, R-6a; Election applies). Where it applies, Election checks at the time the rule names, **by default the nomination cut-off**. A later change is reported to the election committee, which decides under the rules; after the ballot is fixed, Contestation (D-71). **No automatic removal**; no platform limit on standing in several districts. | as D-133 |
| D-135 | **(P7(a), confirmation, not a new rule)** D-107 and D-113 **apply to every current and legacy flow**. The legacy voter import's silent e-mail linking and its account creation (`app/Services/VoterImportService.php:268-278`; PBDIGIT-341) contradict them. **Release condition:** the people register's first capability goes live only after containment. Authorising the containment work is a separate act. | direct, 2026-11-10: "Confirm (Recommended)" |

### 11.49 Decision D-136 (nested areas: authority before specificity)

| # | Decision | Provenance |
|---|---|---|
| D-136 | **A higher body's allocation governs every area it covers, including contained areas, whatever the order.** A lower body cannot narrow or carve out part of it by a more specific allocation, **unless the organisation's written rules expressly give the lower body that competence** (an identifiable provision). **Where that competence is unknown or not established, the lower body's conflicting allocation does not take effect: it is refused and reported.** **Among allocations of equal authority, the most specific area wins.** Applies D-119 to nested areas (IR-OTD-01-C2 item 2). | direct, 2026-11-10: "Accept authority-first (Recommended)", the answer to the question stating this rule |

### 11.50 Decisions D-137 … D-139 (amendments to D-123, D-124, D-131 after the final validation)

*Numbering note: these were authorised as "D-136 … D-138"; a parallel session had already recorded D-136 (§11.49, nested areas), so they are recorded as D-137 … D-139 with unchanged content.*

Source: `docs/publicdigit/architecture/reviews/2026-11-10-strategic-decision-paper-final-validation.md` (B-1…B-4). Each **amends** the named entry; the earlier entry and its provenance stand unchanged and are read together with the amendment. **Working directions (WD)**, not ratified. Recording authorised by the Principal: "Authorise recording (Recommended)".

| # | Decision | Provenance |
|---|---|---|
| D-137 | **(amends D-123; B-1)** Every authorised voting channel states its **assurance model**: the evidence it relies on, who verifies it, and how issuance is recorded. A code delivered to an e-mail address or telephone number **proves control of that contact only**, and PublicDigit says so; the organisation records how it verified the contact. In-person issuance requires the issuer's identity-document check and, **by default, a second authorised member as witness**; the organisation's written rules may waive the witness, in which case the issuance record and the election's published assurance state **"single issuer"**. One issuance authorisation per electorate entry whatever the channel; a disputed authorisation is held. Issuance authorisation, blind credential and ballot remain separate; the ballot is submitted online and anonymously. | direct, 2026-11-10: "Witness unless rules waive (Recommended)" |
| D-138 | **(amends D-124; B-2 and B-4)** The electorate snapshot fixed at cut-off T is **never changed by later identity knowledge**. At issuance, "at most one right per claimed Person" applies using **Identity's binding as known at issuance**. Entries known to be one Person **with identical races receive one right, recorded against the duplicate case, without the platform choosing an entry**; with different races the right is held until the organisation names the governing entry. **An issued blind credential cannot be revoked individually**; duplicates found afterwards are recorded and may be contested (D-71) without identifying any ballot. The organisation checks its own register for likely duplicates before issuance using only its own data; PublicDigit may flag, never declares, sameness; Identity alone merges Persons; the organisation alone resolves its entries. **Guarantee:** at most one issuance authorisation per electorate entry across channels and at most one right per claimed Person; **no guarantee of one right per human while duplicate entries or Persons remain undetected.** | direct, 2026-11-10: "One right, no entry chosen (Recommended)" |
| D-139 | **(amends D-131; B-3)** The stages depend on the organisation's mode. **Membership mode:** register entry → `PersonRef` → claim (only for the claimed channel) → **Membership's own facts** (an import never creates Membership) → entitled at T (C4 at τ = T, plus unit or division) → issuance through an authorised channel. **Election-only mode:** register entry → `PersonRef` → claim (as above) → **standing voter relationship confirmed** ("pending" is never a voter) → entitled at T (voter relationship at τ, plus unit or division) → issuance. **Claiming is a channel requirement, never a condition of entitlement.** No import creates an Account, a Membership or a right. | direct, 2026-11-10: "Accept (Recommended)" |

### 11.51 Decision D-140 (an overridden lower allocation resumes when the higher one ends)

| # | Decision | Provenance |
|---|---|---|
| D-140 | **A lower body's allocation that a higher body's allocation overrides (D-119, D-136) is not ended, only without effect while the higher allocation stands.** **When the higher allocation ends** (removed, superseded, or lapsed because its destination closed), **the lower allocation applies again from that date**, and every member whose unit changes as a result receives a **`PlacementReviewNeeded`** notice (D-122). An allocation **refused at adoption** was never effective and never applies. | direct, 2026-11-10: "Resumes, with review (Recommended)", the answer to the question on suspended allocations |

### 11.52 Decisions D-141 … D-143 (strategic closure: F-1 reading, DR-07b closure, O-14 vocabulary)

Source: `docs/publicdigit/architecture/reviews/2026-11-10-strategic-baseline-consolidation-review.md` §7 (F-1, F-4). **Working directions (WD)**, not ratified. Recording authorised by the Principal: "Record D-140…D-142 (Recommended)". *Numbering note: authorised as D-140 … D-142; a parallel session had already recorded D-140 (§11.51), so they are recorded as D-141 … D-143 with unchanged content.*

| # | Decision | Provenance |
|---|---|---|
| D-141 | **(F-1, interpretation)** The ratified Membership text "Election-Only → ImportedVoterRegistry … does not require Membership" (ratification act §12) decides that **election-only voting needs no Membership** and that **Election's own architecture governs**; **it does not assign ownership of the registry**. Under D-109 and D-139, Organisation owns the standing voter relationship and Election consumes it to build each election's electorate; Election owns its per-election electorate entry ("ElectionMembership", act §11). **The ratified text is unchanged; no ownership moves.** | direct, 2026-11-10: "Confirm the reading (Recommended)" |
| D-142 | **(DR-07b, closure)** Ownership of unit belonging is **formally settled by CR-01 C0 (D-73)**: "Organisation owns organisational structure and person-to-unit belonging". D-3 is thereby formalised; **DR-07b's ownership question is closed.** The disposition of the legacy `CommitteeAssociation` is a tactical matter for Organisation and Membership. | direct, 2026-11-10: "Confirm closed by C0 (Recommended)" |
| D-143 | **(O-14, closure with glossary note)** **Member:** a Membership fact (Organisation's "Member" capacity reflects it, D-103). **Role:** an affiliation capacity (D-101, D-130), a unit capacity where the rules recognise it (V-33), or a Governance office held for a term; never interchangeable; no "User" capacity (D-108). **Observer:** a unit capacity. **"ElectionMembership"** (ratified, act §11) means **an entry in one election's electorate**, not Membership; new texts say **"electorate entry"**, with ElectionMembership as its synonym. | direct, 2026-11-10: "Close with glossary note (Recommended)" |

### 11.53 Decision D-144 (DR-07a: Governance owns committees)

Source: `docs/publicdigit/architecture/reviews/2026-11-10-dr07a-committee-ownership-decision-brief.md`; full paper `docs/publicdigit/reviews/2026-11-02-governance-dr07-committee-ownership-decision-paper.md` (option A, §8, §13).

| # | Decision | Provenance |
|---|---|---|
| D-144 | **ARB act (D-94; the Principal as PO, Membership owner and ARB chief).** **DR-07a decided: option A, with B as the cutover mechanism.** **Governance owns committees (organs), their positions, appointments and terms of office**; Membership supplies membership facts only; Organisation supplies the unit a committee serves (D-106, D-142). **ADR-0001's location clause is superseded; its CQRS principle stays.** The existing Membership committee code is **frozen as a behavioural reference** until a Governance model reaches parity, then retired; the Governance stub write path (`api/v1/governance/committees/{id}/members`) is recorded as a gap. **Not decided:** migration, code or schema changes (each separately authorised; the M-1 … M-5 independent review gates migration); committee powers' content (written rules, O-8 … O-10); classification of existing `committees` rows (O-16); `CommitteeAssociation` (D-142: Organisation and Membership). | direct, 2026-11-10: "A, Governance owns (Recommended)" |

### 11.54 Decision D-145 (a committee ends with its unit when nothing else is said)

Source: `docs/publicdigit/architecture/reviews/2026-11-10-governance-committee-discovery-gap-analysis.md` §3 G-2.

| # | Decision | Provenance |
|---|---|---|
| D-145 | **When a unit ends** (dissolved, divided, merged or absorbed) **and neither the deciding act nor the organisation's written rules say what happens to its committee, the committee ends on the unit's end date:** its terms of office and delegations end, mandates are revoked, and pending matters pass to a **named successor committee, otherwise lapse**. **The Highest Committee is competent for the area until a new committee is established.** The deciding act or the written rules may provide otherwise (V-48, V-50, V-58). Affected committees, terms and delegations appear in the closure impact report. | direct, 2026-11-10: "Ends with its unit (Recommended)" |

### 11.55 Decision D-146 (minimum product package)

| # | Decision | Provenance |
|---|---|---|
| D-146 | **The minimum package PublicDigit offers is: Membership, Newsletter and Membership Forum.** Product scope only: it changes no ownership, ratification or earlier ruling. Contexts needed for it: Membership (RAT), Communication (newsletter; D-57 … D-61), Deliberation (forum; D-50 … D-56), and the parts of Organisation, Governance and Identity these depend on. Election and Voting are outside the minimum package. | direct, 2026-11-10: "my minimum package to offer is now : membership , newsletter and membership forum" |

### 11.56 Decision D-147 (structure in the first release)

| # | Decision | Provenance |
|---|---|---|
| D-147 | **The first release of the minimum package (D-146) supports organisational units and placement of members by individual act** (D-79), every member belonging to the top unit by default (D-114). Unit forums and unit-targeted newsletters work on that basis. **Area allocation rules (§51) and the use of Reference Geography come in a later release**; their designs stand unchanged. Release scope only; no ruling changes. | direct, 2026-11-10: "Units, placement by act (Recommended)" |

### 11.57 Decision D-148 (bootstrap: founding registrar by act)

Source: `docs/publicdigit/reviews/2026-11-10-governance-thin-slice-minimum-package.md` §8 OQ-1.

| # | Decision | Provenance |
|---|---|---|
| D-148 | **When an organisation joins PublicDigit, it submits its written rules (statutes) and a decision of its competent body naming a registrar.** The registrar records the organisation's current committees and office holders **from evidence** (e.g. the last congress or election minutes). **Every such entry is marked "recorded from evidence" and can be challenged.** The platform only checks that the documents exist; **it creates no authority** (D-1a). The registrar's recording authority comes from the organisation's decision (V-59), and recording is not deciding (G-6). | direct, 2026-11-10: "Founding registrar by act (Recommended)" |

### 11.58 Decisions D-149, D-150 (admission and moderation when the rules are silent)

Source: `docs/publicdigit/reviews/2026-11-10-governance-thin-slice-minimum-package.md` §8 OQ-2, OQ-3.

| # | Decision | Provenance |
|---|---|---|
| D-149 | **When an organisation's filed rules do not say who admits members, nobody may approve membership applications until a competent committee decides, at a sitting, which body or office admits members** (as D-58 for newsletters). Applications wait as pending; **admission authority is never assumed or inferred.** | direct, 2026-11-10: "Nobody until designated (Recommended)" |
| D-150 | **A forum for which no moderator is designated (for it, or for a containing forum where the rules allow) is read-only:** members can read it; **posting opens once a competent committee designates a moderator covering it at a sitting.** No unmoderated posting; no assumed moderator. | direct, 2026-11-10: "Read-only until moderated (Recommended)" |

### 11.59 Decisions D-151, D-152 (newsletter opt-out; forum access by unit level)

Source: `docs/publicdigit/reviews/2026-11-10-newsletter-and-forum-design-minimum-package.md` §6 and the Principal's statement on forum access.

| # | Decision | Provenance |
|---|---|---|
| D-151 | **Members may opt out of any newsletter series, per series, and re-subscribe later.** Formal notices (e.g. convocations) are not affected. Editors see how many opted out, never who. *[LQ] Counsel to confirm the legal basis for sending newsletters to members.* | direct, 2026-11-10: "Yes, per series (Recommended)" |
| D-152 | **Forum access follows the organisation's unit levels:** a unit's forum is accessible to the members belonging to that unit **or any unit below it**. A ward forum is for that ward's members; a district forum for all members within the district; the top unit's forum for all members. **Confirms the D-56 default** (a member reads their own unit's forum and those above it), stated from the forum's side. Levels are the organisation's units (D-147), not the official map. Committees' own forums stay confidential to their office holders (D-56). | direct, 2026-11-10: "Forum access is based on the geographic level. e.g. only ward members can access the ward forum. district level forum can be accessed by all members within the district . etc highest committee forum is accessed by all members." |

### 11.60 Decision D-153 (who writes in a forum when the rules are silent)

| # | Decision | Provenance |
|---|---|---|
| D-153 | **When the organisation's rules are silent, the members with membership coverage in a forum's scope (D-152) may write in it; pending applicants, workers and guests may read where they belong but not write.** Suspension is not applied in release 1, because the ratified Membership contract does not supply it to Organisation or Deliberation. Applying it later needs a Membership change request (ARB act). The organisation's rules may provide otherwise. | direct, 2026-11-10: "Effective members only (Recommended)" |

### 11.61 Decision D-154 (units and placement when the rules are silent)

Source: `docs/publicdigit/architecture/reviews/2026-11-10-minimum-package-coherence-check.md` K-1.

| # | Decision | Provenance |
|---|---|---|
| D-154 | **When the organisation's filed rules are silent:** (1) **nobody may decide structural changes** (create, change, move or end units below the top unit) **until a competent committee designates, at a sitting, the body or office that may** (as D-149); (2) **the body that admits a member may name the member's unit in the admission act** (D-79); **later transfers only by a designated body**. Otherwise members belong to the top unit (D-114). Nothing is assumed or inferred. | direct, 2026-11-10: "Designated, plus at admission (Recommended)" |

### 11.62 Decision D-155 (equal-highest bodies must agree to move a scheme's map version)

Source: `docs/publicdigit/architecture/reviews/2026-11-10-ir-otd-04-independent-validation.md` L-5; model document §51.4 A-8.

| # | Decision | Provenance |
|---|---|---|
| D-155 | **When the written rules are silent and several bodies share the highest authority in an allocation scheme, moving the scheme's confirmed Geography position in a way that affects their allocations needs the act of all of them**, or of the body the written rules designate as the scheme's competent authority. **No body's allocations change how they apply without its act.** A binding default, not a working direction. | direct, 2026-11-10: "Default: all must agree (Recommended)" |

### 11.63 Decisions D-156 … D-158 (acceptance of the minimum-package tactical designs)

Act: `docs/publicdigit/reviews/2026-11-10-minimum-package-tactical-designs-acceptance-act.md`.

| # | Decision | Provenance |
|---|---|---|
| D-156 | **Accepted: Organisation tactical design §49–§51** (model document, SHA-256 `2bc6b65c206488bd1a94ea6d1c8e00045d496b9db89eecdfac16e2f4c1dcf488`) as the tactical baseline. **Rests on self-review by one model (IR-OTD-01 … IR-OTD-04, deepseek-chat), not on independent validation.** Release 1 builds §49–§50 and the reduced descent (D-147); **building the area rules (§51 allocation) requires an independent review first.** | direct, 2026-11-10: "Accept, §51 build gated (Recommended)" |
| D-157 | **Accepted: Governance thin slice for the minimum package** (`docs/publicdigit/reviews/2026-11-10-governance-thin-slice-minimum-package.md`, SHA-256 `0f89507f12dba25d6644cb9ee10740f2e88553d0855b73066f73de287cffcf47`), incl. the D-154 addition. Reviewed only by its author. **Round 7 (committee precedence) remains a proposal**, to be accepted with the area-rules release. | direct, 2026-11-10: "Accept (Recommended)" |
| D-158 | **Accepted: Newsletter and Forum design for the minimum package** (`docs/publicdigit/reviews/2026-11-10-newsletter-and-forum-design-minimum-package.md`, SHA-256 `8b2be45d7c898b4a67d6e92f246ef91de4c5f9b684e8c8556a5b486cbb8f8835`). Reviewed only by its author. **Counsel's confirmation of the legal basis for newsletters (D-151 [LQ]) is still required before launch.** | direct, 2026-11-10: "Accept (Recommended)" |

### 11.64 Decision D-159 (existing units are recorded from evidence at joining)

Source: `docs/publicdigit/architecture/reviews/2026-11-10-integrated-baseline-architecture-review.md` §12.

| # | Decision | Provenance |
|---|---|---|
| D-159 | **When an organisation joins with an existing structure, the founding registrar (D-148) records its existing units from evidence** (statutes, last congress minutes, unit lists), **each marked "recorded from evidence" and challengeable.** This is recording, not deciding (G-6); the records carry the evidence as their basis (S-7). **Only later changes** to the structure need a designated body (D-154). | direct, 2026-11-10: "Registrar records from evidence (Recommended)" |

### 11.65 Decision D-160 (release 1 serves ledger organisations only)

Source: `docs/publicdigit/reviews/2026-11-10-membership-cr01-c1-c2-implementation-specification.md`.

| # | Decision | Provenance |
|---|---|---|
| D-160 | **Release 1 (D-146, D-147) serves only organisations whose authoritative membership store is the ratified membership ledger** (D-6 / R10, gate C-1). **New organisations start on the ledger from joining** (no data migration); **existing organisations join release 1 after their cutover** (gate C-1 … C-10). **CR-01 C1/C2 are answered only from the ledger; for an organisation on the legacy store they are refused.** No interim adapter over the legacy store. | direct, 2026-11-10: "Ledger organisations only (Recommended)" |

### 11.66 Decision D-161 (standing in C1/C2; good standing for forum write and newsletters)

Source: `docs/publicdigit/architecture/reviews/2026-11-10-membership-c1-c2-contract-conformance-review.md` §10; CR-02 `docs/publicdigit/reviews/2026-11-10-membership-change-request-cr-02-standing-in-c1-c2.md`.

| # | Decision | Provenance |
|---|---|---|
| D-161 | **ARB act (D-94): CR-02 accepted.** C1/C2 also carry the **`inForce`** and **`suspended`** intervals (ratified predicates), as known at τ; additive; Membership returns facts only; belonging stays on coverage. **Product rule:** **only members in good standing (in force and not suspended) may write in forums and receive newsletters; lapsed and suspended members can still read the forums they belong to.** **Amends D-153** (its "suspension is not applied in release 1" no longer holds; the rest of D-153 stands) and the audience rule of the D-158 design. | direct, 2026-11-10: "Add standing to C1/C2 (Recommended)" |

### 11.67 Decision D-162 (C1/C2 implementation specification accepted)

| # | Decision | Provenance |
|---|---|---|
| D-162 | **Accepted: Membership CR-01 C1/C2 implementation specification** (`docs/publicdigit/reviews/2026-11-10-membership-cr01-c1-c2-implementation-specification.md`, SHA-256 `b7c9bb43b94760285f0670c5257ca2b7d858dea24f68b5384e14af0ef6c5d5bc`, incl. §11 corrections and CR-02 standing) as the tactical design. **Reviewed only by its author** (conformance review 2026-11-10, not independent). **Condition: when implementation is separately approved, it starts with the ledger-clock concurrency proof tests (A1–A3); if they fail, the design returns to the Principal.** No implementation authorised by this act. | direct, 2026-11-11: "Accept, proofs gate build (Recommended)" |

### 11.68 Decision D-163 (release 1 includes renewal and suspension)

Source: `docs/publicdigit/architecture/reviews/2026-11-11-whole-baseline-strategic-coherence-assessment.md` B-1.

| # | Decision | Provenance |
|---|---|---|
| D-163 | **Release 1 includes the ratified Membership facts Renew and Suspend / Restore** on the ledger. **Each admission records its term as the organisation's rules set it (open-ended allowed); there is no platform default term.** **Renewals, suspensions and restorations are recorded by an authorised office, checked through Governance's AuthorityCheck** (new act kinds `renew-membership`, `suspend-membership`, `restore-membership`; when the rules are silent, nobody until a committee designates, as D-149). **Payment stays outside PublicDigit in release 1**: a renewal records the organisation's decision or confirmation, not a payment (Finance and FeePaid later). Makes D-161 effective. Product scope only; no ratified text changes (the facts are ratified, §56–§61). | direct, 2026-11-11: "Build renew + suspend (Recommended)" |

**Terminology note on D-161 (2026-11-11, recorder; meaning unchanged):** the ratified Membership glossary defines **`Standing` = dues compliance (E0b-S)**. **D-161's "good standing" means `InForce ∧ ¬Suspended`, named `Active`** in the designs from now on, so that the ratified word keeps its single meaning. The ratified `Standing` is not used in release 1. Source: `docs/publicdigit/reviews/2026-11-11-membership-kernel-renew-suspend-restore-design.md` §0.

**Terminology note 2 on D-161 (2026-11-11, recorder; meaning unchanged; supersedes the name in note 1):** **`Active` is not used**, because ratified Membership §3 already uses `active(f)` for a fact's liveness. **D-161's condition (in force and not suspended) is named `InForceUnrestricted`**: a name derived by consumers, not a Membership fact and not a contract field. CR-02's fields (`inForce`, `suspended`) are unchanged.

### 11.69 Decision D-164 (physical separation: one central platform database, one database per organisation)

Source: Principal DDD Architect note on physical tenancy (2026-11-11); D-111 precision (2) left this open (HD-8; D-1 option C candidate). Analysis, not authoritative: `docs/publicdigit/brainstorming/multi-database-system/` (read only).

| # | Decision | Provenance |
|---|---|---|
| D-164 | **Binding architectural principle (ARB act, D-94): one central platform database plus one separate database per organisation.** **Central holds only:** person registration and the login Account, authentication and sessions, the organisation registry, the **association between an Account and an organisation** (routing to that organisation's database), and **shared platform reference data** (e.g. Reference Geography, D-31). **Each organisation's own database holds all of its business data:** people records and profiles, affiliations and capacities, workspace-access decisions, units and belonging, membership, governance, forums, newsletters, elections, finance, its outbox/inbox and audit. **The central association never establishes membership, voter status, office, access or authority.** Closes the physical-tenancy part of HD-8 (the tenancy *owner* question remains as recorded). Database routing and connection design are tactical. | direct, 2026-11-11: "Adopt per-organisation DB (Recommended)" |

### 11.70 Decision D-165 (platform staff access to organisation business content)

Source: `docs/publicdigit/architecture/reviews/2026-11-11-multi-database-boundary-tactical-design.md` §13.8 (ISO-8).

| # | Decision | Provenance |
|---|---|---|
| D-165 | **PublicDigit's own operators look at an organisation's business content** (people records, members, posts, newsletters, decisions) **only on that organisation's written request or under a legal obligation; every such access is recorded and reported to the organisation.** **Technical operations** (provisioning, migrations, backups, restores) **continue without reading business content as part of their function.** A trust and privacy commitment under D-111 (PublicDigit does not own organisations' data). | direct, 2026-11-11: "Only on request, recorded (Recommended)" |

### 11.71 Decision D-166 (access to an organisation's database only by the organisation's grant; amends D-165)

| # | Decision | Provenance |
|---|---|---|
| D-166 | **Amends D-165.** **Platform staff and administrators may access an organisation's database only if the organisation provides the access.** (1) **Technical operations** (provisioning, migrations, backups, restores) are **granted once by the organisation when it joins**, as part of the service terms; they **never read business content**. (2) **Looking at business content** (people records, members, posts, newsletters, decisions) **needs a separate grant from the organisation for each case**; **every access is recorded and reported to the organisation.** **D-165's "or under a legal obligation" is no longer a ground for platform staff access.** *[LQ] (recorder): what PublicDigit must do if compelled by law to disclose data is for counsel; this decision does not answer it.* | direct, 2026-11-11: "the pltform staff or administrator can have access to organisation database only if the organisation provides the access" + "Content: case by case (Recommended)" |

### 11.72 Decisions D-167, D-168 (H-1: silence rules and D-1a / D-1b)

Source: `docs/publicdigit/architecture/reviews/2026-11-11-h1-silence-rules-and-strategic-baseline-completion.md` §B–§C; consolidation review §11.5 (H-1).

| # | Decision | Provenance |
|---|---|---|
| D-167 | **PublicDigit never assigns authority by default.** **Where an organisation's rules are silent on who may act, the act waits until a competent body designates someone** (as D-58, D-149, D-150, D-154). **Amends, without rewriting them:** **D-145** — its sentence "the Highest Committee is competent for the area until a new committee is established" no longer applies; until a committee is established for the area or a body is designated, **acts needing that area's committee wait** (the rest of D-145 stands); **D-53** — the default "the independent tribunal" no longer applies; a moderation challenge is **recorded and waits** until the rules or a designation name the reviewing body; **D-64** — messaging stays **off** until a designated body switches it on; **D-115** (WD) — **withdrawn as a default**. **Product-level defaults that assign no authority** (visibility, display, participation conditions: D-52, D-55, D-56, D-114, D-140, D-152, D-153, D-161) **stay**, apply only while the rules are silent, **are displaced by any written rule**, and are **labelled "platform default (D-nn)"** wherever they affect a record or decision. D-1a and D-1b hold without exception. | direct, 2026-11-11: "Never (Recommended)" |
| D-168 | **D-161 is an overridable platform default:** "only members in force and not suspended may write in forums and receive newsletters; lapsed and suspended members can still read" applies while the organisation's rules are silent; **an organisation's written rules may provide otherwise**; labelled "platform default (D-161)". | direct, 2026-11-11: "Overridable default (Recommended)" |

### 11.73 Decisions D-169 … D-171 (after IR-R1-02)

Source: `docs/publicdigit/architecture/reviews/2026-11-11-ir-r1-02-independent-review.md` F-05, F-06, F-07 (review by deepseek-chat, **not independent**; the Principal stated that no independent reviewer is available).

| # | Decision | Provenance |
|---|---|---|
| D-169 | **Re-acceptance of the current texts of four accepted designs** (their correction notes since acceptance are thereby accepted; each note is traced to a finding or decision inside the file): **D-156** Organisation §49–§51, model document SHA-256 `1e9eaf31ee575b51f6ad5d4a9f27baa93946a7faed52f41293d3f81f9a7be79c`; **D-157** Governance thin slice `69f475cfb23c1603f86fa1bc1dfa9500362f3755cf8009b1c3717a7ae05d3547`; **D-158** Newsletter and Forum `8453d81c9848af4acdacbe41217665862e100042296a18414fc40d3fa9fb0ce0`; **D-162** C1/C2 specification `5ea99458cb4e34a089f435b5487e0e99fdce2f90387c09872f9a26a69c336b2e`. **The reviews behind these texts were self-reviews or by deepseek-chat, not independent; acceptance rests on them, as the Principal decided, because no independent reviewer is available.** Conditions of the original acts stand (§51 build gated; proof tests first; counsel for newsletters). | direct, 2026-11-11: "Re-accept current texts (Recommended)" |
| D-170 | **Amends the mechanism of D-166 (meaning unchanged):** when an organisation's database cannot record a case grant (failed migration, corruption, backup inspection), **the organisation's authorised person gives the grant through the platform; it is held centrally as a pending grant record (metadata only, no business content), used for that one case, recorded and reported, and copied into the organisation's database when it works again.** Without such a grant, content access is refused. | direct, 2026-11-11: "Grant recorded centrally, then copied (Recommended)" |
| D-171 | **Office-holder count defaults are platform defaults under D-167:** "editor exclusive, moderator non-exclusive" applies only while the rules and the creating decision are silent, is displaced by either, and is labelled "platform default (D-171)". | direct, 2026-11-11: "Labelled platform default (Recommended)" |

### 11.74 Decision D-172 (D-154(2) under D-167)

Source: `docs/publicdigit/architecture/reviews/2026-11-11-ir-r1-02-independent-review.md` (GPT-6, independent of the package authorship) H-1.

| # | Decision | Provenance |
|---|---|---|
| D-172 | **D-167 governs D-154(2). Amends D-154(2), without rewriting it:** the body that admits a member **may place the member in a unit only where the organisation's rules or a competent committee's designation give it that competence** (the designation of an admitting body may include placement). **Otherwise the member belongs to the top unit (D-114, labelled platform default) until a designated body places them.** The two-act request (`admit` + `place-member`) returns an outcome per act; `place-member` without such competence is `Undetermined`. | direct, 2026-11-11: "D-167 governs (Recommended)" |

### 11.75 Decision D-173 (strategic baseline accepted)

| # | Decision | Provenance |
|---|---|---|
| D-173 | **Accepted: the PublicDigit strategic baseline through D-172** (act `docs/publicdigit/reviews/2026-11-11-strategic-baseline-acceptance-act.md`): the bounded contexts and three-kind ownership; **a Platform (tenancy) context owns the organisation registry, database locator and status, the service-operation grant record and D-170 pending grants (closes HD-8's owner part)**; the cross-cutting rules (D-1a, D-73, D-166 / D-170 INV-ACCESS-1, D-167, D-168, D-171, D-172); the release-1 scope. **Re-records the fingerprints of D-156, D-157, D-158 and D-162** (superseding D-169's). **Basis: one independent review (IR-R1-02, GPT-6) plus self-reviews.** Tactical designs S1 and S2 are not accepted by this act; no implementation is authorised. | direct, 2026-11-11: "Accept (Recommended)" |

### 11.76 Decisions D-174, D-175 (Platform tenancy: grantor when the database is down; joining counterparty)

Source: `docs/publicdigit/architecture/reviews/2026-11-11-platform-tenancy-tactical-design.md` §0, Q-P1, Q-P2.

| # | Decision | Provenance |
|---|---|---|
| D-174 | **Specifies the grantor in D-170:** each organisation designates, **by its own committee decision**, one or more **platform contacts** who may give a case grant through the platform **while its database cannot record it**. **Platform keeps only their account / `PersonRef` references centrally** (no other data). The designation is set up at joining (part of the founding documents, D-148) and changeable by the organisation's competent decision; changes are recorded in the organisation's database and mirrored to the Platform list. Grants given this way are used for one case only, reported to the organisation, and copied into its database on recovery. **When the database works, grants follow the normal AuthorityCheck route.** | direct, 2026-11-11: "Designated platform contacts (Recommended)" |
| D-175 | **Before an organisation becomes operational, PublicDigit verifies its own commercial counterparty:** the identity of the person accepting the service terms and their stated link to the organisation (e.g. a signed service agreement and a contact check). **This is PublicDigit's decision about whom it serves; it creates no constitutional authority (D-1a)**: the organisation's internal authority still comes only from its own documents (D-148, D-159). Registration stays `provisioning` until verified. | direct, 2026-11-11: "Verify counterparty (Recommended)" |

### 11.77 Decision D-176 (platform contacts: which list governs the emergency route)

Source: `docs/publicdigit/architecture/reviews/2026-11-11-platform-tenancy-tactical-design.md` revision 2, R-A, Q-P3.

| # | Decision | Provenance |
|---|---|---|
| D-176 | **For the D-170 / D-174 emergency route only, the platform's registered contact list governs:** a designation counts **once registered on the platform**, and a **removal takes effect once registered there**. **The organisation may register additions and removals directly with the platform at any time, including while its database is down**, through another current platform contact or its verified service counterparty (D-175), **citing the organisation's decision**. **Every grant given through this route is reported at once to all current contacts** (and to the organisation's database on recovery). A removal not yet registered is the organisation's to register. When the database works, the normal AuthorityCheck route applies and the organisation's own records govern. | direct, 2026-11-11: "Platform list governs, organisation can update it directly (Recommended)" |

### 11.78 Decisions D-177, D-178 (tactical designs accepted; Platform sent for review)

| # | Decision | Provenance |
|---|---|---|
| D-177 | **Accepted: Membership Renew / Suspend / Restore tactical design, revision 6** (`docs/publicdigit/reviews/2026-11-11-membership-kernel-renew-suspend-restore-design.md`, SHA-256 `2ec5028fc6a2c90ddb75231050466c4fc3c2d2379cce9efe15e5cc7fbfcbf9a0`). Basis: one independent review (IR-R1-02, GPT-6) plus self-reviews. **Build gated:** implementation starts with the ledger-clock and group-transaction proof tests (as D-162). No implementation authorised by this act. | direct, 2026-11-11: "Accept (Recommended)" |
| D-178 | **Accepted: multi-database boundary tactical design, revision 5** (`docs/publicdigit/architecture/reviews/2026-11-11-multi-database-boundary-tactical-design.md`, SHA-256 `507a12904ee17dc352003701f01cb98aac3e9e1708c5ad50594ac67c71c0e203`). Basis: one independent review (IR-R1-02) plus self-reviews. **Build gated** on the isolation (ISO-1 … ISO-10) and INV-ACCESS-1 release-criteria proofs; legal questions open. No implementation authorised. **The Platform (tenancy) design is not accepted: the Principal chose "accept after a review"** (independent review first). | direct, 2026-11-11: "Accept (Recommended)"; Platform: "Accept after a review" |

### 11.79 Decisions D-179, D-180 (security notices; protection against list capture)

Source: `docs/publicdigit/architecture/reviews/2026-11-11-platform-tenancy-tactical-design.md` revision 4, Q-P4, Q-P5 (findings H-4, H-5 of a deepseek-chat review, not independent).

| # | Decision | Provenance |
|---|---|---|
| D-179 | **Narrow exception to INV-ID-3:** Identity sends **account-security notices** to the account's **verified login address**: a grant the holder gave; a change to the holder's platform-contact role; an emergency grant or contact change in an organisation where the holder is a platform contact (incl. a removed contact's own removal). **Nothing else is ever sent there.** INV-ID-3 otherwise unchanged (the login identifier is never used to find, create or bind any record). | direct, 2026-11-11: "Security notice to login address (Recommended)" |
| D-180 | **Amends D-176 (adds a safeguard):** a platform contact **added through the emergency (direct) route can give a case grant only 24 hours after registration**; **removals take effect at once**. Contacts designated through the organisation's own database are unaffected. All emergency changes are notified to every current and removed contact and the counterparty (D-179). | direct, 2026-11-11: "24h wait for new contacts (Recommended)" |

### 11.80 Decision D-181 (D-65 under D-167: a declared competence gap establishes no authority)

Source: Principal decision brief in conversation, 2026-11-11 (H-1 verification: D-65 found to give effect, by platform default, to an act whose competence cannot be found in the organisation's rules; not covered by D-167 or D-172). *Numbering note: authorised as "D-169"; parallel sessions had already recorded D-169 … D-180, so it is recorded as D-181 with unchanged content.* A new Principal decision after the baseline acceptance (D-173, change control §2 of the act); consistent with the accepted cross-cutting rule "authority only from the organisation's rules or designations".

| # | Decision | Provenance |
|---|---|---|
| D-181 | **D-167 governs D-65. Amends D-65, without rewriting it:** where competence cannot be determined from the organisation's rules (a declared gap), **no body is established as competent; the act is recorded but takes no effect (`Undetermined`) until the rules are amended or a competent body designates who decides.** An organisation's written rules may adopt a presumption of validity. **D-65's default "effective until challenged" no longer applies**; D-71's and D-127's references to D-65 read accordingly. **D-70 (contradictory rules) is unchanged.** | direct, 2026-11-11: "D-167 governs (Recommended)"; recording: "Record as D-169 (Recommended)" |

### 11.81 Decision D-182 (emergency grants need two contacts)

Source: IR-P-02 (GPT-6, independent review of Platform design revision 4) F-01; brief in the session of 2026-11-11.

| # | Decision | Provenance |
|---|---|---|
| D-182 | **Adds to D-174 and D-176 (without rewriting them):** **an emergency case grant given through the platform (D-170) needs the approval of two different current platform contacts.** An organisation with **only one** current contact **cannot use the emergency route** and waits until its database can record a grant through the normal route. A contact added through the emergency route counts only after D-180's 24 hours. All approvals and changes are notified per D-179. | direct, 2026-11-11: "Two contacts must agree (Recommended)" |

### 11.82 Decision D-183 (only organisation-recorded contacts approve emergency grants)

Source: IR-P-02-R (GPT-6, focused independent re-review of Platform design revision 5) F-01-R / P-1; session of 2026-11-11.

| # | Decision | Provenance |
|---|---|---|
| D-183 | **Adds to D-180 and D-182 (without rewriting them):**<br>- **Only a contact whose designation is recorded in the organisation's own database can be one of the two approvers of an emergency case grant** (D-182). Once the grant has been copied, that record is the organisation's copy of the platform contact list.<br>- **A contact added directly to the platform** (D-176):<br>&nbsp;&nbsp;- receives notices (D-179) and may withdraw a grant;<br>&nbsp;&nbsp;- **becomes an approver only once the organisation's own records confirm the designation.** D-180's 24 hours no longer suffice on their own for approval.<br>- **If removals leave fewer than two such contacts, the emergency route is unavailable** and the organisation waits for the normal route (as D-182 provides for a single-contact organisation).<br>- **Accepted consequence:** removals can block emergency grants until recovery. This denies a service; it grants no access. | direct, 2026-11-11: "Only organisation-recorded contacts approve (Recommended)" |

### 11.83 Decision D-184 (precedence of direct contact registrations during an outage)

Source: IR-P-02-R (GPT-6) F-03-R / P-2; session of 2026-11-11.

| # | Decision | Provenance |
|---|---|---|
| D-184 | **Adds to D-176 (without rewriting it):** while the organisation's database cannot answer, **direct registrations are ordered by the date of the organisation decision they cite**, and the later cited decision prevails.<br>- **Refused:**<br>&nbsp;&nbsp;- a registration citing a decision **older** than the one behind the current entry (stale);<br>&nbsp;&nbsp;- a registration citing a **future** date.<br>- **Same cited date, different decisions:** the registration **already applied stays**, the later one is refused, and **both are flagged** for validation at recovery.<br>- **Same decision submitted again:** idempotent.<br>- **On recovery the organisation's own records govern and replace the platform list** (D-176). D-184 governs only the outage period and **establishes no constitutional validity** (validity comes only from Governance, D-181). | direct, 2026-11-11: "Cited decision date wins (Recommended)" |

*Recorder note on D-183 (2026-11-11):*
- The Principal pasted an advisor's proposed "working decision". It proposed keeping D-180's 24 hours as sufficient for approval, accepting the accomplice risk, and requiring no minimum membership duration.
- **Asked to choose, the Principal confirmed D-183** ("Keep D-183 (Recommended)"); the proposal was not adopted.
- **D-183 requires no minimum membership duration.**

### 11.84 Decision D-185 (`pending` is a platform status; clarification of D-175)

Source: IR-P-02-R (GPT-6) F-07-R1 / P-4; session of 2026-11-11.

| # | Decision | Provenance |
|---|---|---|
| D-185 | **`pending` is a platform registry status preceding `provisioning`:** the application is recorded and **no technical operation** takes place until the counterparty is verified (D-175) and the service-operation grant is recorded (D-166). **The Principal authorises a clarification note on D-175**, which changes no behaviour. | direct, 2026-11-11: "Pending is a status; add note (Recommended)" |

*Clarification note on D-175 (authorised by D-185; D-175's text is not rewritten):*
- **D-175's "registration stays `provisioning` until verified" reads as "no technical operation until verified".**
- **This is realised by the status `pending`, which precedes `provisioning`.**

### 11.85 Decision D-186 (both approvers current when an emergency grant is completed)

Source: IR-P-02-R2 (GPT-6) R2-1 / P-1; session of 2026-11-11.

| # | Decision | Provenance |
|---|---|---|
| D-186 | **Clarifies D-182 and D-183 (without rewriting them):** an emergency case grant becomes `given` only if, **at the moment of the second approval, both approvers are current contacts recorded in the organisation's own database** (D-183).<br>- If the first approver has been removed meanwhile, **the request lapses** and must be started again by an eligible contact.<br>- **A grant already `given` is not undone** by a later removal; it is reported and can be withdrawn. | direct, 2026-11-11: "Both current at 2nd approval (Recommended)" |

### 11.86 Decision D-187 (pause emergency access during the recovery handover)

Source: IR-P-02-R2 (GPT-6) R2-3 / P-3; session of 2026-11-11.

| # | Decision | Provenance |
|---|---|---|
| D-187 | **Adds to D-170 and D-176 (without rewriting them):** **once the organisation's database can answer again and copying of an emergency case grant into it has begun, every protected operation under that grant is refused** until the organisation's own copy is present and checked (for both `live-database` and `backup-copy` work). From then on, the organisation's copy governs (D-176: when the database works, the organisation's records govern).<br>- **Withdrawal on the platform by any current contact remains available and immediate throughout.**<br>- **Accepted consequence:** a short pause in emergency work during the handover. | direct, 2026-11-11: "Pause until copy is present (Recommended)" |

### 11.87 Decision D-188 (grants approved under a designation that fails validation are suspended)

Source: IR-P-02-R3 (GPT-6) R3-2 / P-R3-1; session of 2026-11-11.

| # | Decision | Provenance |
|---|---|---|
| D-188 | **Adds to D-181, D-182 and D-186 (without rewriting them):** when recovery validation finds that **the designation of a contact who approved an emergency case grant** is `NotAuthorised` or `Undetermined`, **that grant is suspended on reconciliation.**<br>- No further access under it unless the organisation authorises or reissues it **through its normal route**.<br>- The grant and the designation are reported to the organisation.<br>- **Access that already took place cannot be undone;** it is reported.<br>- **D-186's rule that a later *removal* does not undo a `given` grant is unchanged.** D-188 concerns a designation that was never validly established, not a later removal. | direct, 2026-11-11: "Suspend the grant (Recommended)" |

### 11.88 Decision D-189 (maximum duration of one operation under a case grant)

Source: IR-P-02-R3 (GPT-6) R3-7; session of 2026-11-11.

| # | Decision | Provenance |
|---|---|---|
| D-189 | **A single protected operation under a case grant may run at most 15 minutes** (**platform default (D-189)**, in the D-167 / D-171 sense of a labelled default). **The organisation's grant may set a shorter limit, never a longer one.**<br>- **Deadline** = the earlier of grant expiry and this limit; **no result is delivered after the deadline.**<br>- Longer work is split into several operations, each admitted and checked separately.<br>- **It creates no authority**; it only bounds access already granted. | direct, 2026-11-11: "15 minutes, grant may shorten (Recommended)" |

### 11.89 Decision D-190 (Governance AuthorityCheck act kinds for the Platform)

Source: IR-P-02-R5 (GPT-6) R5-01; session of 2026-11-11.

| # | Decision | Provenance |
|---|---|---|
| D-190 | **Authorises extending Governance's AuthorityCheck contract with two act kinds, consumed by the Platform (tenancy) context.** They are to be defined in a **Governance thin-slice addition note** (as for D-163), ratified together with the Platform acceptance:<br>- **`designate-platform-contacts`:** the subject is one designation (decision, part, designated person);<br>- **`give-case-grant`:** a case grant given **through the organisation's normal route**, while its database works (D-174).<br><br>**Both are answered from the organisation's own rules, on the decision date (D-12).**<br>- **Where the rules are silent: `Undetermined` until a competent body designates who decides (D-167, D-181).**<br>- **Accepted consequence:** an organisation whose rules are silent must make the contact designation at joining, in its founding documents (D-174). Otherwise it has no platform contacts and no emergency route.<br>- **The emergency route itself is unchanged** (D-174, D-176, D-182 … D-188).<br>- **Platform remains a consumer; it creates no authority.** | direct, 2026-11-11: "Authorise both (Recommended)" |

### 11.90 Decision D-191 (D-176 and D-184 govern D-174's "no other data")

Source: IR-P-02-R5 (GPT-6) R5-10; session of 2026-11-11.

| # | Decision | Provenance |
|---|---|---|
| D-191 | **D-176 and D-184 govern D-174's "no other data", without rewriting it.** For each platform-contact entry the platform may also hold centrally:<br>- **the cited decision reference and date;**<br>- **the document digests** (fingerprints, never the documents);<br>- **the sender;**<br>- **the validation outcome and its evidence reference.**<br><br>**Metadata only:** no decision texts, minutes or rules (D-164). These entries are replaced by the organisation's records on recovery (D-176). **No other rule changes.** | direct, 2026-11-11: "Later decisions govern (Recommended)" |

### 11.91 Decision D-192 (last resort when a staff operation cannot be stopped)

Source: IR-P-02-R6 (GPT-6) F-05; session of 2026-11-11.

| # | Decision | Provenance |
|---|---|---|
| D-192 | **Adds to D-166 and D-189 (without rewriting them):** if a protected staff operation continues after its deadline and the database process ignores cancellation and termination, **the last resort is a forced restart of the database server hosting that organisation's database.**<br>- **Where other organisations' databases share that server, they accept a brief outage.** Each affected organisation receives an incident report.<br>- **Access never continues past the deadline undetected:** a denial of service is preferred to continued access (as D-183, D-187).<br>- The operation is recorded as "access may have occurred" unless termination is confirmed.<br>- **D-164's hosting layout (shared or dedicated servers) is unchanged.** | direct, 2026-11-11: "Restart the server (Recommended)" |

### 11.92 Decision D-193 (Platform (tenancy) tactical design accepted with conditions)

Source: IR-P-02-R7 (GPT-6, final bounded independent review: READY FOR PRINCIPAL CONSIDERATION; no design defect); session of 2026-11-11.

| # | Decision | Provenance |
|---|---|---|
| D-193 | **The Platform (tenancy) tactical design, revision 11, is ACCEPTED WITH CONDITIONS.**<br><br>**(1) Accepted text:** `docs/publicdigit/architecture/reviews/2026-11-11-platform-tenancy-tactical-design.md` at SHA-256 `e3772d2e8e266d909a1328cb2205b1d9271e47c0890caf5be8984d5724d4e005`. An appended status note (file now `245db3e69fdd1e10f1d6251ff8071733cf2b82bfd0560d8bee6718a54e258696`) is the only later change.<br><br>**(2) Governance ratification, in this act (D-190):** the AuthorityCheck addition for `designate-platform-contacts` and `give-case-grant` (revision 11, R11-1) is appended to the accepted Governance thin slice. Fingerprints of the thin slice: before `eea71356da5e8b9585f2f84d9acc4c8a5ec8e17424d843b62e1bb0b4b8fca284`, after `bdf9bb3dafe08fbc9a3ca0d54d082a40af2e9a3e9f386860a592cb9ceeb413c4`. **This closes X-1a.** **It does not decide the per-part persistence of `ruleRef` / `policyVersion`** (that stays under X-3).<br><br>**(3) Conditions; until each is met, contact promotion and normal-route grant checks fail closed:**<br>- **X-2:** an accepted Identity operation resolving an organisation `PersonRef` to its bound Account (Identity, I-TDD-02). No email matching and no Platform-side inference;<br>- **X-3:** an accepted Governance contract for the decision-log rules (GD-11, GD-12) and for per-Resolution-part evidence persistence.<br><br>**(4) Build gates:** the 53 proof obligations of `2026-11-11-platform-proof-obligation-register.md` (SHA-256 `a3fe00f28f8fe70c388891cbf36042f428bc69a70d1efdf9d24f3cc8e129fb18`), all NOT EXECUTED, must pass against the target database before release.<br><br>**(5) What this decision is not:**<br>- **No implementation is authorised.**<br>- **Whether the emergency route (D-170, D-174 … D-188) ships in release 1 is a separate Product Owner decision**, still to be taken.<br>- No accepted decision is reopened. | direct, 2026-11-11: "Accept with conditions (Recommended)" |

### 11.93 Decision D-194 (who may record the end of a membership)

Source: whole-baseline strategic DDD coherence review (2026-11-11) G-2 / P-1; session of 2026-11-11.

| # | Decision | Provenance |
|---|---|---|
| D-194 | **Where the organisation's rules are silent, the end of a membership (ratified fact `End(kind)`) is recorded per kind.**<br><br>**(1) Resignation is the member's own act.** It takes effect on the member's declaration, **without any body's decision**. It is recorded either:<br>- by the member themselves (self-service, through their own binding, Identity I-TDD-02); or<br>- by an office the rules or a designation empower to record membership facts.<br><br>**(2) Expulsion only by the body the rules name.** If they name none, it waits until a competent body designates one (D-167, D-181).<br><br>**(3) Death and other kinds** are recorded **from evidence** by an office the rules or a designation empower; otherwise they wait (D-167).<br><br>**Effects:**<br>- Governance gains the corresponding AuthorityCheck act kinds in an addition note, as for D-163 and D-190 (Governance completion).<br>- For a resignation, the check is that **the actor is the member concerned**, or an empowered recording office.<br>- **This assigns no authority by default:** it states the member's own right to leave.<br><br>**[LQ]:** counsel to confirm the statutory right to leave at any time (e.g. the German Parties Act) and its effective time. | direct, 2026-11-11: "Per kind, as recommended" |

### 11.94 Decision D-195 (I-TDD-02 scope accepted, with additions)

Source: whole-baseline strategic DDD coherence review (2026-11-11) G-1 / P-2; session of 2026-11-11.

| # | Decision | Provenance |
|---|---|---|
| D-195 | **The I-TDD-02 scope (`docs/publicdigit/architecture/reviews/2026-11-10-identity-i-tdd-02-scope.md`) is accepted** (its entry criterion E-4), **with three additions:**<br>**(1) Resolution in both directions:**<br>- a signed-in Account → the person's `PersonRef` in this organisation, for every act;<br>- an organisation's `PersonRef` → its bound Account, for Platform (X-2, D-193).<br><br>**(2) The founding registrar's one-time bootstrap claim** (D-148, D-178).<br><br>**(3) Refusal when Identity cannot answer.**<br><br>**I-TDD-02 is to be written in this session as a tactical design only.** It needs an **independent review** before the Principal accepts it (IS §4 item 7). **No implementation is authorised.** | direct, 2026-11-11: "Accept scope + additions (Recommended)" |

### 11.95 Decision D-196 (claim-code competence: one act kind `manage-claims`)

Source: I-TDD-02 revision 12 (`b6d54798…`) §21 P-A; GPT-6 limited review PASS (2026-11-12); session of 2026-11-12.

| # | Decision | Provenance |
|---|---|---|
| D-196 | **Option A1. Authorises extending Governance's AuthorityCheck contract with one act kind, `manage-claims`, consumed by Identity**, covering **issuing, replacing and revoking** an ordinary claim code.<br>- A claim code establishes an identity binding only; **it grants no membership and no access.**<br>- **Who holds the competence:** the office or body the organisation's rules or a designation empower. **Rules silent → `Undetermined` until a competent body designates** (D-167, D-181).<br>- **Authority is evaluated at each act** (D-12); having issued a code confers nothing later.<br>- **Ratification route (as D-190):** the Governance addition note (I-TDD-02 §10.3, A1 row) is appended to the Governance thin slice **in the act that accepts I-TDD-02**.<br>- **The bootstrap claim is not checked with this act kind** (D-148, D-178, D-195). | direct, 2026-11-12: "A1: one competence (Recommended)" |

### 11.96 Decision D-197 (no automatic claim lock; throttling only)

Source: I-TDD-02 revision 12 §21 P-A4; session of 2026-11-12.

| # | Decision | Provenance |
|---|---|---|
| D-197 | **No automatic security lock on claim codes.** Protection is **throttling only** (per code and per client, INV-ID-9), plus the organisation's own replacement or revocation (D-196).<br>- **Reason:** an automatic lock could be triggered deliberately to deny the legitimate holder their registration.<br>- **A bounded lock policy may be proposed separately later** (as R-XD3c-3 requires an approved security policy for emergency account actions). | direct, 2026-11-12: "Defer; throttling only (Recommended)" |

### 11.97 Decision D-198 (founding information reaches provisioning by resubmission; nothing personal held centrally)

Source: I-TDD-02 revision 12 §9.1–§9.4, §21 P-B; session of 2026-11-12.

| # | Decision | Provenance |
|---|---|---|
| D-198 | **Option B2.**<br>- **At application:** Platform keeps only the evidence metadata D-191 permits (fingerprints, document references, dates, sender, match results). **No founding people's names or addresses, no document bytes or decision texts are held centrally.**<br>- **At provisioning:** the verified counterparty, as messenger (D-175, D-176), **resubmits the founding documents and the founding contacts' details straight into the organisation's own database**; the fingerprints must match; provisioning stops on a mismatch; it resubmits if provisioning fails.<br>- **D-164, D-166, D-191 and Platform H-2 are unchanged.**<br>- **Not decided here:** whether the registrar-address trust risk is accepted (**B-T1 / B-T2**), which stays **open until Platform D-7** is resolved. | direct, 2026-11-12: "B2: resubmit at setup (Recommended)" |

### 11.98 Decision D-199 (claim-code validity periods)

Source: I-TDD-02 revision 12 §6.3, §21 P-C and P-C2; session of 2026-11-12.

| # | Decision | Provenance |
|---|---|---|
| D-199 | **Options C1 + BW-1.**<br>- **Ordinary claim codes:** the period is set by the organisation's rules or a decision of a body they empower. **Where they are silent: 14 days, labelled "platform default (D-199)".** **Platform-wide maximum: 30 days;** a longer requested period is capped at 30 and the cap is recorded.<br>- **The founding registrar's bootstrap code: the same 14-day platform default**, because the organisation's own rules cannot be read before it is `operational`.<br>- **Both defaults assign no authority** (D-167; method as D-171).<br>- **The values 14 and 30 are product policy set by this decision.** | direct, 2026-11-12: "C1 + BW-1: 14 days, max 30 (Recommended)" |

### 11.99 Decision D-200 (Identity tactical design I-TDD-02 accepted with conditions)

Source: GPT-6 limited independent review of revision 12, PASS (`docs/publicdigit/architecture/reviews/2026-11-12-i-tdd-02-rev12-independent-review.md`); session of 2026-11-12.

| # | Decision | Provenance |
|---|---|---|
| D-200 | **The Identity tactical design I-TDD-02 (claims and bindings), revision 12, is ACCEPTED WITH CONDITIONS.**<br><br>**(1) Accepted text:** `docs/publicdigit/architecture/reviews/2026-11-11-i-tdd-02-identity-claims-and-bindings-tactical-design.md` at SHA-256 `b6d54798a3d7b2da09f5da4f872d92fa45e7e051ac507f50968d41cc8dc0fb44`. An appended status note (file now `2b5f6e92374c08357db47d1a3a9dcde51b53316602c657fc3ddf0f5f62a1d018`) is the only later change. Revisions 1–11 are kept as history.<br><br>**(2) Applies:** D-196, D-197, D-198, D-199.<br><br>**(3) Governance ratification (D-196 route):** the `manage-claims` AuthorityCheck addition is appended to the accepted Governance thin slice (before `bdf9bb3dafe08fbc9a3ca0d54d082a40af2e9a3e9f386860a592cb9ceeb413c4`, after `a6dfd66b8b0d12682f25c16d1454240d55531230a94cf2e24ab969c01e397f40`).<br><br>**(4) Conditions:**<br>- **B-T1 / B-T2 (registrar-address trust risk) to be decided after Platform D-7.** Until then, a bootstrap claim is issued **only if that risk is explicitly accepted for the organisation being set up**, or setup waits;<br>- dependencies: the `end-membership` act kinds (D-194); Governance X-3; P-I2 (`PersonRef` issuance mechanism, keeping INV-REF-2); legal questions (retention, `PersonRef` classification, resignation effective time).<br><br>**(5) Build and release gates:** PI-1 … PI-16, PI-17a, PI-18 … PI-48 (all NOT EXECUTED) must pass on the target database; **security assessment PI-17b** must be carried out before release.<br><br>**(6) Effects on Platform:** **D-193's condition X-2 is met at design level by R-2** (§8). It remains a build gate until implemented and proved.<br><br>**(7) No implementation is authorised. No accepted decision is reopened.** | direct, 2026-11-12: "Accept with conditions (Recommended)" |

### 11.100 Decision D-201 (resignation timing where the rules are silent)

Source: Governance completion strategic discovery (2026-11-12), open question 1; session of 2026-11-12.

| # | Decision | Provenance |
|---|---|---|
| D-201 | **Adds to D-194 (without rewriting it).** Where the organisation's rules are silent on timing:<br>- **a member may state an effective date for their resignation that is no earlier than their declaration;**<br>- **without a stated date, the resignation takes effect at once;**<br>- **the member may withdraw the resignation before its effective date**; after that date, re-entry needs a new admission (BD-1).<br><br>**Organisation rules on notice periods or timing take precedence.**<br>**[LQ]:** counsel to confirm the legal effective time, including any statutory right to leave with immediate effect.<br>**[TD], for M-TDD-01, not decided here:** which ratified Membership fact represents a withdrawal before the effective date, **without changing any ratified invariant**. | direct, 2026-11-12: "Later date allowed, withdrawable (Recommended)" |

### 11.101 Decision D-202 (business requirement for Governance decision records: X-3)

Source: Governance completion strategic discovery (2026-11-12), open question 2; session of 2026-11-12.

| # | Decision | Provenance |
|---|---|---|
| D-202 | **A recorded Governance decision must let the organisation explain later:**<br>**(1)** which organ decided, and at which sitting or by which method;<br>**(2)** whether that organ was competent under the rules in force on the decision date, **evaluated per part**. Recorded as *worked out*, *asserted* (cited, not machine-checked), *gap declared* (with D-181's effect: no effect while undetermined) or *contradiction*. **Never re-run automatically;**<br>**(3)** which rule provisions and rule versions were relied on;<br>**(4)** what evidence supported it;<br>**(5)** the decision date and each part's effective date;<br>**(6)** what each part decided;<br>**(7)** what PublicDigit knew and when: gap-free log positions, record times that never decrease (GD-11, GD-12);<br>**(8)** **corrections and challenges never rewrite history.**<br>&nbsp;&nbsp;- A correction is a new fact that fixes only the record to match the evidence, made under recording authority (V-59, G-6).<br>&nbsp;&nbsp;- Changing what was decided needs a new decision.<br>&nbsp;&nbsp;- A pending challenge changes nothing (D-71).<br>&nbsp;&nbsp;- Tribunal remedies are separate applied facts (governance model §40.1, §41.5).<br><br>**Settles the business meaning of Platform condition X-3 (D-193) and Identity's dependency (D-200).** The tactical Governance completion lifts the existing proposed model (§20.1, §33, §40–§41) into an accepted addition. **Adjudication stays frozen.** | direct, 2026-11-12: "Complete as listed (Recommended)" |

### 11.102 Decision D-203 (`competenceKind = ownAct` for a member's own resignation)

Source: Governance completion tactical design revision 2 §2.3 (option O-A); session of 2026-11-12.

| # | Decision | Provenance |
|---|---|---|
| D-203 | **Authorises adding the value `ownAct` to the AuthorityCheck answer's `competenceKind`**, valid **only** for `record-resignation` in its self-service form (**actor = subject**, both the organisation's `PersonRef`):<br>- **`ruleRef`:** the organisation's provision on resignation if it has one, else "D-194";<br>- **`competenceVerified` = true;**<br>- **it assigns no authority to anyone else** (D-167).<br><br>**Ratified together with the acceptance of the Governance completion design** (route as D-190 / D-193, D-196 / D-200). **Not yet appended to the thin slice.** | direct, 2026-11-12: "Approve ownAct (Option A) (Recommended)" |

### 11.103 Decision D-204 (D-201 realised without future-dated ledger facts; D-5(b) kept)

Source: conflict found while preparing M-TDD-01 (2026-11-12). **D-201** allows a future-dated resignation, withdrawable before its date. The **Membership PO decision D-5(b)** (2026-10-11, ratified per the Membership readiness audit) says "backdating allowed; **future-dated admissions and endings refused**", because "scheduling is a different capability". The kernel enforces it (`Member.php`, `EffectiveDateAfterDecisionDate`). Session of 2026-11-12.

| # | Decision | Provenance |
|---|---|---|
| D-204 | **D-5(b) is kept unchanged. D-201 is realised as a scheduled declaration outside the Membership ledger:**<br>- **a resignation with a later effective date** is held by Membership as a **scheduled resignation declaration**, not as a ledger fact (as an application waits outside the Member aggregate, D-2), carrying its Governance answer (`record-resignation`, D-203) and the member's declaration;<br>- **on the effective date** Membership records `End(resignation)` **dated that day**. **No ledger fact is ever future-dated;**<br>- **withdrawal before the date cancels the scheduled declaration.** **No Annul is needed**, and the open Annul question (Governance completion §2.4) is withdrawn;<br>- **until the date the membership is unchanged**: rights, forums and newsletters as before; the feeds (C1 / C2) show the end only once recorded.<br><br>**D-201's business rule is unchanged.** | direct, 2026-11-12: "A: scheduled declaration (Recommended)" |

*Recorder's correction note on D-204 (2026-11-12, by D-205):* D-204's proviso "no ratified or kernel rule changes" (in the question's option A) was **wrong**. The kernel enforces D-5(b) as **"effective date not after decision date"** (`Member.php`, `EffectiveDateAfterDecisionDate`; Slice 1a contract line 79). A scheduled resignation recorded on its effective date still has its effective date after its decision (declaration) date. **D-205 settles this.**

### 11.104 Decision D-205 (D-5(b) clarified narrowly for scheduled resignations)

Source: correction of D-204 (2026-11-12); session of 2026-11-12.

| # | Decision | Provenance |
|---|---|---|
| D-205 | **Clarifies D-5(b), without rewriting it:**<br>**(1)** **No Membership fact may take effect after the time it is recorded** (`effectiveAt` ≤ the recording date): **nothing is future-dated in the ledger**.<br>**(2)** **Exactly one case may take effect after its decision date: `End(resignation)` recorded on its effective date from a scheduled resignation declaration** (D-204). Its `decidedAt` stays the declaration date, so **the record states truthfully when the member decided**.<br>**(3)** Every other fact keeps the existing guard (effective date not after decision date).<br><br>**Consequence:** the kernel guard `EffectiveDateAfterDecisionDate` gains that **single exception**, to be designed in M-TDD-01 and proved by a test. **Backdating rules are unchanged.** | direct, 2026-11-12: "1: narrow exception (Recommended)" |

### 11.105 Decision D-206 (authority to correct, void and annul membership records)

Source: author's review of M-TDD-01 rev 2 (2026-11-12) F-4 / G-1; session of 2026-11-12.

| # | Decision | Provenance |
|---|---|---|
| D-206 | **Authority for the ratified correction operations on Membership facts** (the ratified algebra itself is unchanged):<br>- **Correct** (a real fact recorded wrongly): **an office with recording authority, with evidence**;<br>- **Void** (the record never reflected reality): **an office with recording authority, with evidence.** **Reported to the organisation and challengeable**, as for records made from evidence (D-148, D-159);<br>- **Annul** (a valid decision set aside): **a new decision of the body competent for the original act**, or **a tribunal determination remedy** (D-67, applied as a fact; Adjudication stays frozen).<br><br>**Rules silent on recording authority → `Undetermined`; nothing is corrected or voided** (D-167). **Governance gains the corresponding act kinds in an addition note** (as D-163, D-190), ratified with the Governance completion acceptance. | direct, 2026-11-12: "Recorder with evidence (Recommended)" |

### 11.106 Decision D-207 (forum reading by pending applicants)

Source: author's review of M-TDD-01 rev 2 (2026-11-12) F-7 / G-2; session of 2026-11-12.

| # | Decision | Provenance |
|---|---|---|
| D-207 | **Clarifies D-153 (without rewriting it):** a pending applicant has **no belonging by virtue of the application** (an application is Membership's and stays outside the Member, D-2).<br>- **An applicant reads forums only where an authorised body has placed them in the capacity Guest** (D-101), by a placement act (`place-person`, authority as D-154 / D-172; rules silent → `Undetermined`).<br>- **Otherwise they read nothing until admitted.**<br>- **No new capacity and no new Membership → Organisation contract.** | direct, 2026-11-12: "B: only if placed as Guest (Recommended)" |

### 11.107 Decision D-208 (Governance completion design accepted with conditions)

Source: IR-GM-01 (GPT-6) and its limited re-check IR-GM-01-R1 (READY FOR PRINCIPAL CONSIDERATION); session of 2026-11-12.

| # | Decision | Provenance |
|---|---|---|
| D-208 | **The Governance completion tactical design, revision 5, is ACCEPTED WITH CONDITIONS.**<br><br>**(1) Accepted text:** `docs/publicdigit/architecture/reviews/2026-11-12-governance-completion-tactical-design.md` at `a29821dabf7ca48b9dbb82a8c14bde2a32dd78906884e8b4bf3ba0c51d513f2c` (reviewed `04e41258…` plus the GC-17 wording requested by IR-GM-01-R1). Status note appended; file now `697f174e893051283025cbfb6c88b1ea074b2487725c6d3158976f26a0b79398`.<br><br>**(2) Ratification:** the Governance addition note is appended to the thin slice (before `a6dfd66b8b0d12682f25c16d1454240d55531230a94cf2e24ab969c01e397f40`, after `e36896c43b6cb852316faec6855c2b9d001aa1fd686ea7b3a5cdb1cb154ee10f`). It covers:<br>- **`ownAct` (D-203);**<br>- `record-resignation`, `exclude-member`, `record-death` (D-194);<br>- `correct-membership-record`, `void-membership-record` and the two Annul routes (D-206);<br>- `place-person` (D-207);<br>- GD-1, GD-11, GD-12 and the per-part evidence (D-202).<br><br>**(3) X-3** (D-193, D-200) **is closed at design level;** it remains a build gate.<br><br>**(4) Build gates:** GC-1 … GC-17 (not executed).<br><br>**(5) No implementation is authorised. No accepted decision is reopened.** | direct, 2026-11-12: "Accept with conditions (Recommended)" |

### 11.108 Decision D-209 (ledger-clock amendment accepted with conditions, ARB)

Source: IR-GM-01 and IR-GM-01-R1 (GPT-6, READY FOR PRINCIPAL CONSIDERATION); session of 2026-11-12.

| # | Decision | Provenance |
|---|---|---|
| D-209 | **The C1 / C2 ledger-clock amendment, revision 2, is ACCEPTED WITH CONDITIONS by the ARB, as an addition to D-162 (mechanism only).**<br><br>**(1) Accepted text:** `docs/publicdigit/architecture/reviews/2026-11-12-c1-c2-ledger-clock-amendment-proposal.md` at `a6d9b05f45906f701170efbdf1c3d1af5763d3ad4394b85765884908a059362e` (reviewed `35739576…` plus the LC-6 wording requested by IR-GM-01-R1). Status note appended; file now `6ac7e192b260e8abc5712bd7bb8b6c3a1baa75f0a4ba9af5ddf6952f39edd4b5`.<br><br>**(2) Mechanism:**<br>- one database clock (`clock_timestamp()`);<br>- appends and the `closedThrough` step refuse and retry on regression; equality allowed; **no timestamp adjusted**;<br>- due scheduled resignations are recorded before `closedThrough` passes their date (M-TDD-01 D-1; D-204, D-205).<br><br>**(3) Unchanged:** the C1 / C2 shapes, refusals and the meaning of `closedThrough`. **The accepted specification text is not edited;** a pointer note is appended (before `5ea99458cb4e34a089f435b5487e0e99fdce2f90387c09872f9a26a69c336b2e`, after `193b4102b1e6a1e5b30775d6248ec1ee13a57b36e41395bf92170b8514218a48`).<br><br>**(4) Membership's part of PO-8 (D-93) is resolved at design level.**<br><br>**(5) Build gates:** LC-1 … LC-6 and A1 … A3 (not executed).<br><br>**(6) No implementation is authorised. No accepted decision is reopened.** | direct, 2026-11-12: "Accept with conditions (Recommended)" |

### 11.109 Decision D-210 (M-TDD-01 accepted with conditions)

Source: IR-GM-01 and IR-GM-01-R1 (GPT-6, READY FOR PRINCIPAL CONSIDERATION); session of 2026-11-12.

| # | Decision | Provenance |
|---|---|---|
| D-210 | **M-TDD-01 revision 4 (Membership lifecycle and release-1 integration: Membership, Organisation, Newsletter, Forum) is ACCEPTED WITH CONDITIONS.**<br><br>**(1) Accepted text:** `docs/publicdigit/architecture/reviews/2026-11-12-m-tdd-01-rev2-membership-lifecycle-and-release-1-integration.md` at `b4aba1687e3cfcbc3171bd731ef1248b01ce2b9d25400431fdb80659b790c137`. Status note appended; file now `56c7840923531ca5d176e7caf3850ceed3ae74ceb273ba71af5db6563ef26f0b`.<br><br>**(2) Dependencies accepted:** D-208, D-209.<br><br>**(3) Build gates:** MI-1 … MI-14 and A1 … A3 (not executed).<br><br>**(4) Before launch:**<br>- newsletter legal basis, resignation effective time, retention [LQ];<br>- the mail provider's idempotency and deduplication over the retry period [EXT];<br>- monitoring for stuck scheduled executions and clock anomalies [EXT].<br><br>**(5) Still open, unchanged:**<br>- Geography PO-1 … PO-8 and the Identity platform-level person reference (later release);<br>- the D-156 fingerprint drift, to be reconciled at the next Organisation acceptance act.<br><br>**(6) No implementation is authorised;** implementation needs an EP-01-approved plan. **No accepted decision is reopened.** | direct, 2026-11-12: "Accept with conditions (Recommended)" |

### 11.110 Recorder correction (no decision): the "D-156 fingerprint drift" does not exist

- D-173 (§11.75; acceptance act §2) **re-recorded** the Organisation §49–§51 text (D-156) at `1e9eaf31ee575b51f6ad5d4a9f27baa93946a7faed52f41293d3f81f9a7be79c`, superseding the earlier value. The file hashes to that value today.
- The statements that D-156 "cites `2bc6b65c…`" and that drift remains open are **wrong**: the IR-GM-01 commission (file 01 §1 row 17), IR-GM-01 C-06, and **D-210 (5) second bullet**. They were the recorder's error (Claude), carried into the review.
- **Nothing else in D-210 changes.** Source: strategic DDD baseline assessment 2026-11-12, §1.

### 11.111 Decisions D-211, D-212 (subdomain classification; exclusion by a party tribunal)

Source: strategic DDD baseline assessment `docs/publicdigit/architecture/reviews/2026-11-12-strategic-ddd-baseline-assessment.md` (`e077ed3e89bbe589aadd401decd989c66f82734725f42d4fac5509bfeea9fc7f`), §8 P-A and P-B; session of 2026-11-12.

| # | Decision | Provenance |
|---|---|---|
| D-211 | **(HD-3 closed) Subdomain classification.**<br>- **Core:** Governance (authority under the organisation's own rules; decision records) and Voting (anonymous ballots). Contestation and Adjudication are core, **labelled "not yet built"**.<br>- **Supporting:** Membership (keeps its ratified rigour), Organisation, Election lifecycle (BDR-08, unchanged), Deliberation, Communication.<br>- **Generic:** Identity, Platform, Reference Geography (D-31), mail delivery (bought).<br><br>**Classification only:** it changes no boundary, ownership or ratified invariant. | direct, 2026-11-12: "Governance + Voting core (Recommended)" |
| D-212 | **Exclusion when the organisation's rules give it to a tribunal.**<br>- **The tribunal decides in Adjudication**, on an exclusion application filed by the body the rules name (for example the board).<br>- **Governance applies the Determination** as its own applied fact; no Resolution is created (consistent with D-202 item 8 and Governance rev 5 §2.5 route 2).<br>- **Membership records `End(exclusion)`** with the Determination and its application as decision provenance (RA §9 pattern).<br>- **Interim suspension** by the body the rules empower uses the existing Suspend act kind (D-163).<br><br>**Consequences:**<br>- D-194 (2) is **not changed**; D-208's organ route stays as the route where the rules name an organ.<br>- A **change request to the frozen Adjudication context** (one case kind) and a **Governance addition** (one exclusion route) are needed **before** tribunal exclusion is offered.<br>- **Until then, such exclusions wait (D-167).** Not a release-1 gate unless a launching party requires it.<br><br>**[LQ]:** counsel to confirm §10 (4)–(5) PartG. | direct, 2026-11-12: "Tribunal in Adjudication (Recommended)" |

### 11.112 Decision D-213 (newsletter audience: D-161 and D-57 together)

Source: Strategic DDD baseline v1.1 revision 3 (`docs/publicdigit/architecture/publicdigit-strategic-ddd-baseline.md`), U-7 and Appendix F; session of 2026-11-12.

| # | Decision | Provenance |
|---|---|---|
| D-213 | **Clarifies D-161 with D-57 (no rule reopened).** The newsletter audience of a series is:<br>- **(1) members of the scope,** filtered by the participation rule: the organisation's written rule, **else "platform default (D-161)": in force and not suspended** (D-168);<br>- **(2) persons in the other capacities the series' creating decision names** (D-57, D-58; D-158 `Series`), **by their own valid belonging in the scope.** The membership-standing filter does not apply to them; they have no membership standing.<br><br>Opt-outs (D-151) and contact rules apply to both.<br><br>**Consequence (tactical):** Organisation needs an audience query by capacity in scope, at the send's τ, alongside K-4. To be specified in the Newsletter slice.<br><br>No change to D-161's forum rules. | direct, 2026-11-12: "Members filtered; named capacities too (Recommended)" |

### 11.113 Decision D-214 (Strategic DDD Baseline v1.1 ratified)

Source: IR-R1C-01 (GPT-6), S1 verdict "READY FOR RATIFICATION" (`docs/publicdigit/architecture/reviews/2026-11-12-ir-r1c-01-independent-review.md`, `663facf2…`); session of 2026-11-12.

| # | Decision | Provenance |
|---|---|---|
| D-214 | **The PublicDigit Strategic DDD Baseline v1.1, revision 4, is RATIFIED** as the consolidated strategic model of D-173 … D-213. **It adds no rule; the decision record governs where they differ.**<br><br>**(1) Ratified text:** `docs/publicdigit/architecture/publicdigit-strategic-ddd-baseline.md` at `8e74bb6dc45964b6090834cb09c03ac4f71d6b0988ff2fa189310251142e8080`. Status note appended; file now `a292bb4aac9755cc72923c03ac496ef3326818dc7f369dedd9a6d5d0d31c3e94`.<br><br>**(2) E-1 confirmed:** the conformance note at the end of the multi-database boundary design (after D-178) is a conformance note **implementing D-185 and D-193** (Platform rev 11 H-3, M-3, M-8), and through them D-166, D-170 and D-175. **The note itself is not edited**; its original attribution ("D-166 / D-170 / D-175 via Platform design revision 4") is corrected here.<br><br>**(3) Fingerprints re-recorded (D-173 §2 change control; supersede D-173's table):**<br>- D-156 `1e9eaf31ee575b51f6ad5d4a9f27baa93946a7faed52f41293d3f81f9a7be79c`;<br>- D-157 `e36896c43b6cb852316faec6855c2b9d001aa1fd686ea7b3a5cdb1cb154ee10f`;<br>- D-158 `22929c16ee32b44a86d887bffcc5317e550facaef1629b7a723e52a36ee24d6c`;<br>- D-162 `193b4102b1e6a1e5b30775d6248ec1ee13a57b36e41395bf92170b8514218a48`;<br>- D-177 `c631cc4a4b228fdda5402227d62248ca54f03b6c2dfeb60bfd945411ebc9991e`;<br>- D-178 `430ed58f780ae9ef27523966a45002f910c3c00b8a56cbb9753529fa7e823704`;<br>- D-193 `245db3e69fdd1e10f1d6251ff8071733cf2b82bfd0560d8bee6718a54e258696`;<br>- D-200 `2b5f6e92374c08357db47d1a3a9dcde51b53316602c657fc3ddf0f5f62a1d018`;<br>- D-208 `697f174e893051283025cbfb6c88b1ea074b2487725c6d3158976f26a0b79398`;<br>- D-209 `6ac7e192b260e8abc5712bd7bb8b6c3a1baa75f0a4ba9af5ddf6952f39edd4b5`;<br>- D-210 `56c7840923531ca5d176e7caf3850ceed3ae74ceb273ba71af5db6563ef26f0b`.<br><br>Each was verified on 2026-11-12 to differ from its accepted text only by notes listed in decision entries (baseline §10).<br><br>**(4) Banners applied:**<br>- `context-map.md` superseded (before `614e1bf0cb639dd04b78bea3669a19551927bbc4a5b383f95db69cca29c25a6d`, after `83fef6a48e321037e34da1a342ada4b2e32ecd3f4569dc7aa20a774f000051e4`);<br>- `strategic-design-status.md` historical (before `6ed98c781bbb523ecdaee602d86cc869a2ff95c16588352adfac9bad8b5dbf34`, after `9d675a2d3ebd3df20fa2013cb2d15d69df9a35697125e8f2a1e38c55d27cbd2e`).<br><br>This closes post-correction review P-7 and the acceptance act's "context map and status record lag" note.<br><br>**(5) Still open:** U-5, U-9 (pending T-1, T-2); U-1, U-2, U-3, U-4, U-6, U-8, U-10.<br><br>**(6) No implementation authorised. No decision reopened.** | direct, 2026-11-12: "Ratify now (Recommended)" |

### 11.114 Decision D-215 (T-2 and K-C1 accepted, ARB)

Source: IR-R1C-01 and IR-R1C-01-R1 (GPT-6): T-2 and K-C1 "READY FOR ARB ACCEPTANCE" (`docs/publicdigit/architecture/reviews/2026-11-12-ir-r1c-01-r1-limited-recheck.md`, `b8121427…`); session of 2026-11-12.

| # | Decision | Provenance |
|---|---|---|
| D-215 | **ARB act (D-94). Accepted, as written in the release-1 blueprint revision 4** (`docs/publicdigit/architecture/reviews/2026-11-12-release-1-tactical-completion-and-implementation-blueprint.md`, `c08ebc3563db4d7da76d3182c42ffa405471cd869fdff2a42d3fa92fd8b87855`):<br><br>**(1) T-2:** Organisation's queries `CapacityBelongingAsAt` and `CapacityBelongingOfPersonAsAt` (§4 T-2, rules 1–6):<br>- non-member capacities only; `Member` refused;<br>- **never consults Membership; K-4 unchanged and gains no parameter;**<br>- an empty answer is never "not a member";<br>- one audience entry per person, with every qualifying basis (D-213);<br>- **combined with K-4: the same τ, with T-2's date the organisation-local date of τ;**<br>- reading never implies posting (D-153, D-161, D-168).<br>**Closes baseline U-9.**<br><br>**(2) K-C1:** V-5 (COR-4 Q-2, strictly increasing `recordedAt` per membership) is kept **by refusal and retry, never by adjustment**. Equal readings across different memberships stay allowed (D-209).<br><br>**(3) Build gates:** T2-1 … T2-5, KC1-1, KC1-2; for K-C1 also G1d-1 (the retryable clock refusal outcome, §6 G-1d). **Not executed.**<br><br>**(4) Not accepted by this act:** T-1 (with G-Q1) and T-4, pending a limited re-check.<br><br>**(5) No implementation authorised; no decision reopened.** | direct, 2026-11-12: "Accept both (Recommended)" |

### 11.115 Decision D-216 (T-1 and T-4 accepted, ARB; release-1 tactical design complete)

Source: IR-R1C-01-R2 (GPT-6): T-1 and T-4 "READY FOR ARB ACCEPTANCE", no new contradictions (`docs/publicdigit/architecture/reviews/2026-11-12-ir-r1c-01-r2-limited-recheck.md`, `aa4cee14…`); session of 2026-11-12.

| # | Decision | Provenance |
|---|---|---|
| D-216 | **ARB act (D-94). Accepted, as written in the release-1 blueprint revision 4** (`docs/publicdigit/architecture/reviews/2026-11-12-release-1-tactical-completion-and-implementation-blueprint.md`, `c08ebc3563db4d7da76d3182c42ffa405471cd869fdff2a42d3fa92fd8b87855`; status note appended, file now `1c1fb171ed2f2ecb58be5bef73b319454e5f3242fd5df98c4263244a2e2988b7`):<br><br>**(1) T-1** `OfficeCoverageAsAt` (moderator, forum) returns only valid tenures. **With G-Q1:** one per-organisation Governance completeness point, `governanceClosedThrough`, on the DecisionLog head:<br>- **extends GD-12 by addition** (refuse a recording at or before the boundary; a NOWAIT closing step with a regression guard);<br>- **used by `OfficeCoverageAsAt`, AuthorityCheck and K-7**, close then read;<br>- beyond it: T-1 `Refused`, AuthorityCheck `Unknown` (no `NotAuthorised` recorded), K-7 refused;<br>- **no change to any business meaning** (D-157, D-208, D-210).<br>**Closes baseline U-5.**<br><br>**(2) T-4:**<br>- the newsletter snapshot stores the contact slot `(personRef, email)`, never an address;<br>- `ResolveDeliveryContact` reads the current contact at each delivery (D-210 §7 items 3, 9);<br>- `NoContact{NO_CURRENT_CONTACT}` excludes with the reason recorded; `Unavailable` stays queued;<br>- never the login address (INV-ID-3).<br><br>**(3) With D-215, the release-1 tactical design is complete.**<br><br>**(4) Build gates:** T1-1 … T1-7, GQ-1 … GQ-3, T4-1 … T4-5, G1d-1 and the rest of §10 (not executed).<br><br>**(5) Open, for the Organisation slice:** the selection rule when several contacts are current and the profile designates none.<br><br>**(6) No implementation authorised; no decision reopened.** | direct, 2026-11-12: "Accept both (Recommended)" |

### 11.116 Recorder note (no decision): blueprint build order reconciled

- **What:** the accepted release-1 blueprint (D-215, D-216) has a traceability note appended (before `1c1fb171ed2f2ecb58be5bef73b319454e5f3242fd5df98c4263244a2e2988b7`, after `3ad25014c0955140219aa9dc477afa4085a0c02f6a37d97e6b21bc11a481f5a7`).
- **What it says:** its §10 slice order is superseded for implementation by the build order the Principal approved with the slice-1 plan (2026-11-12, plan §13).
- **Unchanged:** contracts, slice contents and D-147.

### 11.117 Decision D-217 (core domain refined: legitimate, participatory self-governance)

Source: Principal, 2026-11-12, in discussion of D-211 ("the core domain is not only Voting … digitalisation of the organisation"; "organisations always have problems to manage the membership fees and the duration of membership"). Analysis given in the session: the whole purpose (digitalising the organisation) is the product domain, not a core subdomain. Within it, participatory motion-making, financial transparency and the fee-to-standing rules are differentiating.

| # | Decision | Provenance |
|---|---|---|
| D-217 | **Refines D-211 (D-211 stays in the record). Subdomain classification:**<br><br>**Core — legitimate, participatory self-governance:**<br>- **Governance:** authority under the organisation's own rules; decision records;<br>- **Voting:** anonymous ballots;<br>- **Contestation and Adjudication:** labelled "not yet built";<br>- **participatory motion-making:** members' ideas developed in discussion and carried into motions and committee decisions (Deliberation before submission, Governance after, D-51);<br>- **financial transparency:** showing members and authorities how money was decided and used under the organisation's rules;<br>- **dues and standing:** how membership fees decide standing and rights under the organisation's own rules, with provable history.<br><br>**Supporting:**<br>- Membership (keeps its ratified rigour);<br>- Organisation;<br>- Election lifecycle (BDR-08);<br>- the ordinary member forum;<br>- newsletters;<br>- the Finance ledger of fee obligations and payments.<br><br>**Generic:**<br>- Identity, Platform, Reference Geography (D-31), mail delivery;<br>- **payment processing** (collection, direct debit, receipts, reconciliation).<br><br>**Classification only.** No boundary, ownership or ratified invariant changes:<br>- **dues and standing is a collaboration, not a merged module.** Governance holds the fee rules; Finance holds obligations and payments; **Membership records the consequence** (renew, lapse, suspend) **by its own act on Finance evidence (D-132)**.<br><br>**Release scope unchanged:** Finance stays outside release 1 (D-146); release 1 keeps term, renewal, suspension and lapse in the ledger (D-163).<br><br>**Open question (for the Finance slice, not decided):** many statutes **strike a member off for long non-payment**. In the ratified model an unpaid term is **lapsed (still a member), not ended**, and the closed list of ending kinds (resignation, exclusion, death, legacy) has **no "struck off"**. To be decided, with real statutes, before Finance is designed:<br>- a lapse;<br>- a deemed resignation;<br>- or a new ending kind (a ratified-model change). | direct, 2026-11-12: "yes, record it as a new decision" |

### 11.118 Decision D-218 (product principles)

Source: the Principal's product philosophy (2026-11-12), inspired by the Principal's reading of Antonio Gramsci; the Principal DDD review of the same day (five refinements). **Ratified by the Principal on 2026-11-12, with the guarantees note.**

| # | Decision | Provenance |
|---|---|---|
| D-218 | **Adopts the PublicDigit Product Principles** (`docs/publicdigit/architecture/publicdigit-product-principles.md`, revision 2, SHA-256 `19f387681c415891e9625111bdd9663d6e8a40f16a080009aa369a044cf9a2f4`), including the five refinements to principles 0, 3, 6, 7 and 8.<br><br>**(1) What they do:** they guide product design, feature choices, defaults, visibility and tactical design reviews. **Every future tactical design assesses the relevant design tests and records any conflict or justified deviation.**<br><br>**(2) What they never do:**<br>- create organisational authority or act on an organisation's behalf;<br>- override an organisation's statutes or rules;<br>- change context ownership or boundaries;<br>- authorise implementation;<br>- add features to release 1, or change the accepted implementation sequence.<br><br>**The design tests are product-design criteria, not organisational rules, constitutional requirements or universal acceptance conditions.**<br><br>**(3) Precedence:**<br>1. applicable law;<br>2. the organisation's own rules, within the law;<br>3. accepted architecture and domain decisions;<br>4. the principles.<br><br>**Guarantees note (ratified):** the platform's protective invariants (vote secrecy, isolation, append-only history, refusal when unestablished) govern how PublicDigit itself behaves. **Where an organisation's rule would require breaching one, PublicDigit does not provide that function. It never breaches the invariant.**<br><br>**(4) Inspiration, recorded accurately:**<br>- **"Catalyst" is the Principal's image;**<br>- Gramsci's own terms are the "Modern Prince", the *collective intellectual*, hegemony through consent, and democratic centralism "in movement" as against bureaucratic centralism (not Bordiga's "organic centralism").<br><br>**(5) Unchanged:** D-211 and D-217 (the core-domain classification), D-147, and every accepted design. | direct, 2026-11-12: "ratify D-218 with the guarantees note and commit" |

### 11.119 Decision D-219 (slice 2: four Principal answers; slice 1 accepted)

Source: Principal DDD review "Slice 1 closure and Slice 2 decision" (2026-11-12), whose recommendations the Principal accepted ("I accept the recommendations written in the document"). Answers Q-S2-1 … Q-S2-4 of `docs/publicdigit/architecture/reviews/2026-11-12-platform-organisation-routing-isolation-tactical-design.md`.

| # | Decision | Provenance |
|---|---|---|
| D-219 | **(0) Membership slice 1 (PBDIGIT-343) is accepted**, with the qualifications recorded in its plan's task 10. It is not production-ready.<br><br>**(1) Q-S2-1, derived ledger rule (option A).** In release 1, **every organisation served through the Platform registry uses the Membership ledger.** "Its authoritative store is the ledger" (D-160, gate C-1) is **derived from registry service; no separate flag is stored.** **Condition: enforced, not assumed:**<br>- provisioning cannot mark an organisation `operational` before its organisation database, the Membership ledger schema, its identity row and its ledger clock row are in place;<br>- no legacy organisation can receive a registry entry in release 1;<br>- if the rule cannot be established, Membership fails closed (`SOURCE_NOT_LEDGER`).<br><br>The cutover of existing organisations (C-1 … C-10) stays outside release 1 and may need stored state then.<br><br>**(2) Q-S2-2, one shared hostname (option B).** The person selects an organisation after login. **Every selection is checked against the account's current bindings** (ISO-3). A URL, header, body parameter or stale session never establishes it alone. Per-organisation hostnames may be added later without changing the model.<br><br>**(3) Q-S2-3: the application-level emergency route (D-170, D-174 … D-188) is deferred from release 1.** This settles D-193 (5).<br>**Not deferred:** backups, monitoring, database recovery and authorised technical operations under the joining grant (D-166 (1), ISO-8). These are operational duties, distinct from emergency access to organisation content.<br><br>**(4) Q-S2-4, one PostgreSQL cluster with a separate database per organisation (option A).** **The cluster is a shared failure domain:** an outage affects every organisation on it. Backup and restore are per organisation database and must be rehearsed before launch. | direct, 2026-11-12: Principal accepted the review's recommendations |

### 11.120 Decision D-220 (clarifications of D-217 and D-218)

Source: Principal DDD review "what to do now" (2026-11-12). **D-217 and D-218 stand as decided; nothing in them is rewritten.** On ratification, the D-218 document gets revision 3 with the exact wording below, and the fingerprint in D-218 is re-pinned by an appended note.

| # | Decision | Provenance |
|---|---|---|
| D-220 | **RATIFIED by the Principal, 2026-11-12.**<br><br>**(1) D-217 classifies business capabilities, not bounded contexts.**<br>- A core capability may be delivered by several contexts, **each keeping its ownership**.<br>- Where D-217 names a context (Governance, Voting, Contestation, Adjudication), it means the capability that context owns.<br>- **Participatory motion-making:** Deliberation, a supporting context, owns drafting, discussion and co-signatures before submission; Governance owns the motion from submission (D-51).<br>- **Dues and standing:** Governance owns the fee rules (rules on file); Finance owns obligations and payments. **The written rules choose one of D-132's two arrangements:**<br>&nbsp;&nbsp;- **(A)** arrears change membership status **only by Membership's own act on Finance evidence**;<br>&nbsp;&nbsp;- **(B)** membership continues, and Election obtains a Finance settlement fact instead.<br>&nbsp;&nbsp;**This corrects the precision of D-217's "Membership records the consequence (D-132)", which describes arrangement A only.** D-217's classification is unchanged.<br>- **Financial transparency:** Finance owns the financial facts. **Who may see them follows the organisation's rules; no decision yet designs that capability** (Finance is outside release 1, D-146). **No ownership moves to Governance or any other context.**<br>- **D-217 never justifies merging contexts.**<br><br>**(2) D-218 principle 0, replacing "How levels 2 and 3 meet":**<br>- **Organisational rules govern organisational affairs:** who has authority, what may be decided, and how the organisation operates (D-1a, D-97, D-167, D-168).<br>- **Platform guarantees limit which functions PublicDigit provides:** vote secrecy, isolation between organisations, append-only history, refusal when an answer cannot be established.<br>- **Architecture implements those guarantees. It gives PublicDigit no authority over the organisation's affairs.**<br>- **Product principles guide design within those boundaries.**<br><br>This is **not** a rule that PublicDigit's architecture outranks an organisation's constitution. It states the limits of the service PublicDigit is willing and able to provide. Where a rule asks for a function outside them (for example a voter-to-ballot list), PublicDigit does not offer it, and the organisation remains free to act outside the platform.<br><br>**(3) D-218 principle 8: the resignation bullet is replaced by these bullets** (the current text states unconditionally what D-194 states only where the rules are silent):<br>- **Where the organisation's rules are silent, resignation is the member's own act:** it takes effect on the member's declaration, without any body's decision (D-194 (1)).<br>- It is recorded by the member (self-service, the `ownAct` competence of D-203, ratified but not yet appended to the Governance thin slice) or by an empowered recording office (D-194 (1)).<br>- **Where the rules provide for resignation, they govern** (D-203 `ruleRef`), subject to applicable law.<br>- [LQ] counsel to confirm the statutory right to leave and its effective time (D-194).<br>- The ratified Membership architecture is unchanged. | direct, 2026-11-12: "ratify D-220 and D-221, approve the plan and commit" |

### 11.121 Decision D-221 (Q-S2-5: central organisation job queue)

Source: slice 2 delta revision 3, §3.10 … §3.10.2 (`docs/publicdigit/architecture/reviews/2026-11-12-platform-organisation-routing-isolation-tactical-design.md`); Principal DDD review of 2026-11-12, which recommends yes with five conditions. **Compatible with D-164 and D-219; changes neither.**

| # | Decision | Provenance |
|---|---|---|
| D-221 | **RATIFIED by the Principal, 2026-11-12.** **The queue for organisation jobs is held in the central database:**<br>- the table `organisation_jobs`, used only by the separate queue connection `organisation`;<br>- failures in the standard `failed_jobs`, identified by `connection = 'organisation'`.<br><br>**This is transport infrastructure, not an organisation's business record (D-164).** **Conditions, all required:**<br>**(1)** only identifiers and transport metadata are stored centrally: organisation id, operation key, aggregate ids, attempt data;<br>**(2)** no Eloquent model, personal data or business content in any payload, including failed-job payloads;<br>**(3)** every attempt resolves the organisation again through the Platform registry (`ResolutionService`); nothing about routing is taken from the payload;<br>**(4)** failed-job storage follows the installed Laravel mechanism (one global failer, verified 12.71.0) and is proved by test T-23;<br>**(5)** work that depends on a committed organisation transaction is produced through that organisation's outbox; direct dispatch inside an open organisation transaction is refused (T-24, T-25).<br><br>**The legacy queue connection (`sync`) and its behaviour are unchanged** (T-19). **Revisit** if an organisation's data obligations ever forbid even identifiers centrally; the alternative is a queue in each organisation database. | direct, 2026-11-12: "ratify D-220 and D-221, approve the plan and commit" |

**Appended note to D-218 (2026-11-12, after D-220):**
- The principles document is now **revision 3**, applying D-220 (2) and (3).
- **Fingerprint:** revision 2 `19f387681c415891e9625111bdd9663d6e8a40f16a080009aa369a044cf9a2f4` → revision 3 `0465df57ec3680adca886295e73c3f586782d88fb7cfa01498c9ce0542ed507d`.
- D-218's text is unchanged; this note records the only later change.

### 11.122 Decision D-222 (slice 2: the Laravel multi-database mechanism is custom)

Source: evaluation `docs/publicdigit/architecture/reviews/2026-11-12-platform-laravel-multi-database-mechanism-evaluation.md` and its probe evidence; the Principal DDD review of 2026-11-12, recommendations accepted by the Principal.

| # | Decision | Provenance |
|---|---|---|
| D-222 | **Option B is chosen: a small custom Laravel integration.**<br>- `ServedOrganisationRepository`, `ResolutionService`, an immutable `OrganisationContext`, and an `OrganisationConnectionFactory` that creates **one connection name per organisation** and **never re-points a shared name**;<br>- request and job middleware establish and clear the context.<br><br>**Spatie Laravel Multitenancy is not installed for this purpose.**<br><br>**Preserved:**<br>- the explicit connection per organisation;<br>- Laravel's default connection stays central;<br>- every accepted isolation rule (ISO-1 … ISO-11, D-219, D-221).<br><br>**T-26 is added as an executable proof obligation:** a connection object held from organisation A's context, used after organisation B's work starts, still reaches A only (the probe of the evaluation, §2).<br><br>**Technical choice only:** no ownership, topology or accepted rule changes. | direct, 2026-11-12: Principal accepted the review's recommendations ("I approve option B …") |

### 11.123 Decision D-223 (late recording of a scheduled resignation; clarifies D-205 (2))

Source: reconciliation `docs/publicdigit/architecture/reviews/2026-11-12-integrated-tactical-baseline-reconciliation.md` §3; the Principal DDD review of 2026-11-13 (option 1 recommended), accepted by the Principal.

| # | Decision | Provenance |
|---|---|---|
| D-223 | **D-205 (2) reads: `End(resignation)` from a scheduled resignation declaration (D-204), recorded on or after its effective date, never before.**<br><br>**A recording after the effective date is backdating under D-5(b):**<br>- `decidedAt` = the declaration date;<br>- `effectiveAt` = the scheduled date;<br>- `recordedAt` = the actual recording time.<br><br>**Not broadened:**<br>- scheduled resignation only;<br>- D-205 (1) unchanged (nothing future-dated);<br>- every other fact keeps the existing guard.<br><br>**The knowledge boundary keeps the answers truthful:** `closedThrough` cannot pass the effective date until the End is recorded (D-209, MI-10), so no answer "as known at τ" ignores it.<br><br>**Proof:** MI-1 (read with "on or after") and MI-15a … f, appended to M-TDD-01 by a status note (fingerprint `56c7840923531ca5…` → `8863c22a87d9cf7f…`). | direct, 2026-11-13: Principal accepted the review's recommendations (option 1) |

### 11.124 Decision D-224 (Decision G: Reference Geography is prioritised; D-147 unchanged)

Source: the Principal DDD review of 2026-11-13, accepted by the Principal. Settles Decision G and finding G-1 of the reconciliation.

| # | Decision | Provenance |
|---|---|---|
| D-224 | **Reference Geography is prioritised as a standalone central context.** Order of design work:<br>**(1)** the **minimum Identity person-reference contract** that Geography's approval roster needs (G-1, U-2). **Not an Identity redesign;**<br>**(2)** Reference Geography's standalone tactical completion against D-93 / v3.11, with its own proofs (PO-1 … PO-7);<br>**(3)** its implementation, test-first.<br><br>**Then** Membership (after Platform, Organisation and Governance dependencies), the Forum and the Newsletter, as before.<br><br>**Platform slice 2 (PBDIGIT-345) continues in parallel.**<br><br>**D-147 unchanged:** Geography–Organisation integration (K-9, coverage, allocation rules) stays a later release. **Geography never decides belonging** (D-79, D-80). | direct, 2026-11-13: Principal accepted the review's recommendations ("prioritise Geography") |

### 11.125 Decision D-225 (Identity → Reference Geography: the platform-level person reference)

Source: `docs/publicdigit/architecture/reviews/2026-11-13-identity-platform-person-reference-for-reference-geography.md` (D-224 (1)).

| # | Decision | Provenance |
|---|---|---|
| D-225 | **The minimal Identity contract IG-1 … IG-6 is ACCEPTED.** Accepted text: SHA-256 `15d31dc79bc29c942f51b1d281bbef6680c9c4def37d93c6dd1c62d57db26f72`; with its status line `60d32826d5fcf10792e16b70e3f938df7708e066eb810612d23770bb30522d4f`.<br><br>**Q-IG-1 = (a):** a platform-level context receives **one opaque `ContextPersonRef` per (Person, context)**:<br>- never reused, resolvable only by Identity, no reverse lookup;<br>- **never the bare `PersonId`** (consistent with D-75's per-consumer pattern).<br><br>**Q-IG-2 = yes:** Geography stewards, second persons and matter reviewers act **through their own Identity Account** (`ActingContextRef`), never a `PlatformOperator` identity (D-193 H-3). D-88 compares Identity's canonical Persons.<br><br>**Adds** to Identity:<br>- one reference kind;<br>- the query `ActingContextRef`;<br>- the notices `PersonsMerged` and `PersonMergeReversed`, emitted only by Identity's explicit merge act (C-5). The merge procedure P-I3 stays deferred, and until it exists no notice is emitted.<br><br>**Changes** no Identity aggregate, invariant or accepted flow, and no Geography rule.<br><br>**Closes:** baseline U-2, reconciliation G-1, and the v3.11 §8 [TU].<br><br>**Proof:** IG-P1 … IG-P8 (not executed). **Residual risk, accepted:** two Accounts of one person that Identity never merges are not detected (I-TDD-02 §0 item 5). | direct, 2026-11-13: "accept the contract with Q-IG-1 (a) and Q-IG-2 yes" |

### 11.126 Decision D-226 (Reference Geography standalone completion accepted; Q-G-2, Q-G-1)

Source: `docs/publicdigit/architecture/reviews/2026-11-13-reference-geography-standalone-tactical-completion.md` (D-224 (2)); Principal DDD review of 2026-11-13, accepted by the Principal.

| # | Decision | Provenance |
|---|---|---|
| D-226 | **The standalone tactical completion of Reference Geography is ACCEPTED** (text with its clarifications: SHA-256 `8feb5a7c835ba8762bf986d71fa9ef67397d2e3ef7e489215e10966727fd2fd7`). It consumes v3.11 (D-93), which governs where they differ.<br><br>**§8 (G-0 … G-8) is the approved implementation sequence,** strict TDD. **G-0 starts now** (story PBDIGIT-346).<br><br>**Q-G-2 = YES:** G-0 creates a minimal Identity Person, one per legacy `users` row, lazily. **Safeguard:** a transitional mapping, never proof that one human holds one account. Two references mean "not known to be the same person". The independence check and its record say "as known to Identity at roster version v" (§5a). IG-4 / IG-5 unchanged.<br><br>**Q-G-1 = as recommended:**<br>- World; the five M49 regions (UNSD names, source-attributed); ISO 3166-1 countries and areas; Nepal's territorial scheme, re-entered as evidenced revisions;<br>- **every claim needs evidence competent for that fact and level**; the existing seeder is input, not evidence;<br>- further countries on a customer need.<br><br>**Clarification A:** claims are fully immutable; publication adds a `refgeo_publications` row and changes no claim.<br><br>**Unchanged:** D-93, D-147, D-224, D-225; the legacy `App\Contexts\Geography` is not patched. | direct, 2026-11-13: "accept the design with Q-G-2 yes and Q-G-1 as recommended" |

### 11.127 Decision D-227 (authority and evidence time; targeted amendment of v3.11 §5)

Source: G-2c open questions Q3 and Q4 (`developer_guide/reference_geography/04_g2c_validator.md`); Principal DDD review of 2026-11-13; ratified by the Principal.

| # | Decision | Provenance |
|---|---|---|
| D-227 | **Amends v3.11 §5's read-set row "Evidence citing mandates and rules → those registry claims at the claim's valid time", and the §5.2 queries `competence(issuer, kind, t)`, `mandate(m, t)` and `rule(r, t)`, for authority-dependent instruments:**<br>**(1)** an issuer's **territorial competence** (D-82) is assessed at **the date the instrument was issued or adopted**;<br>**(2)** the **mandates and precedence rules relied on** for that instrument are assessed at that same date;<br>**(3)** an evidence version representing an authority-dependent instrument records its **issuer and issue/adoption date**. Evidence types where these do not apply (e.g. a duplicate-search assessment) carry none;<br>**(4)** a **dispute** on relied-upon evidence is recorded and visible. **It does not by itself change a value, prove a claim false or invalidate it.** The validator never adjudicates disputes or creates authority.<br><br>**Unchanged:**<br>- the claim's own effective interval and every other v3.11 rule;<br>- the reads are still recorded as dependencies (§5.2);<br>- D-93 is not reopened. | direct, 2026-11-13: Principal ratified ("D-227 — Authority and evidence") |

### 11.128 Decision D-228 (endings versus corrections: the basis carries the difference)

Source: G-2c open question Q1; Principal DDD review of 2026-11-13; approved by the Principal.

| # | Decision | Provenance |
|---|---|---|
| D-228 | **Within v3.11's insert-only model (§3, §5.1):**<br>- a **real-world ending** is a `none` claim in the EXISTENCE slot from the ending date, **based on the authoritative instrument**;<br>- a **correction** of an erroneous earlier assertion is a `none` claim **with a correction basis** (v3.11 §3: a withdrawal rests on "instrument or correction"); a `Correction` basis kind makes this explicit;<br>- **published claims are never edited**; if the end is known when the place is entered, a bounded existence interval remains valid;<br>- a **succession** is recorded separately: its event kind and successor references. **Division, merger and absorption need successors and end the predecessor;** a partial split (v3.11 §3) names successors and the predecessor continues (closure document G-5, `SuccessorSet`). No other event kinds. | direct, 2026-11-13: Principal approved ("Q1 — Endings and corrections") |

### 11.129 Decision D-229 (Q-DS: the duplicate search is supplementary, not a basis)

Source: the G-2 acceptance review, revision 2, §4 (Q-DS, a genuine conflict between v3.11 I-4 and D-82 / v3.11 §3 under the one-basis claim model); Principal DDD review of 2026-11-13; approved by the Principal.

**This is a new Principal decision.** It amends I-4. The original I-4 wording permitted a duplicate-search assessment *instead of* an instrument.

| # | Decision | Provenance |
|---|---|---|
| D-229 | **Q-DS Option A (cumulative evidence). Amends v3.11 §1 I-4; consistent with D-82, D-227 and the §3 basis column:**<br>**(1)** every new geographical **Existence** claim cites a **competent establishing instrument** (D-82, D-227, §3 "Territorial");<br>**(2)** where applicable, a recorded **duplicate-search assessment** is **additional** evidence that the place is not already represented in the atlas. It is recorded as a Revision assessment, and **never substitutes for the instrument**;<br>**(3)** a duplicate-search assessment **alone never establishes** a geographical unit;<br>**(4)** "establishing instrument" **does not mean only the original founding document**: a later competent official instrument or source qualifies if it genuinely establishes the fact and meets the authority and evidence rules;<br>**(5)** no weakening of D-82 and no generic `Other` exception. The F-2 rule stands: only an explicitly identified, attributed cartographic source may support a **Boundary** as a non-instrument basis, and only that claim kind. A withdrawal rests on an instrument or a correction (§3, D-228).<br><br>**Also accepted with it:** G-2 findings F-1 (option (a): a country change re-checks the subtree) and F-2 (the narrowed boundary basis).<br>**Unchanged:** D-82, D-83, D-91, D-227, D-228, Q2. | direct, 2026-11-13: Principal approved ("I approve Q-DS Option A (cumulative evidence)") |

### 11.130 Decision D-230 (Q-G3-1: judgment matters are classified conservatively)

Source: the G-3 tactical assessment (`docs/publicdigit/architecture/reviews/2026-11-13-reference-geography-g3-tactical-assessment.md`, Q-G3-1); Principal DDD review of 2026-11-13; adopted by the Principal.

| # | Decision | Provenance |
|---|---|---|
| D-230 | **Applies D-84a. Who classifies a judgment matter (fail closed):**<br>**(1)** a matter is a **judgment matter** when a recognised trigger exists (a dispute, an objection, a missing-unit submission, a precedence assessment), when **anyone involved flags** it as involving competing positions, or when competing evidence leaves **reasonable doubt** whether it is routine;<br>**(2)** the classification **cannot be removed** by the submitter, preparer, assessor or a potentially interested steward;<br>**(3)** an uncertain matter **stays a judgment matter until an independent, authorised person resolves it.** A steward with a relevant interest never decides that classification;<br>**(4)** with **no eligible independent decider,** the decision is **refused or held.** It never silently proceeds.<br><br>**Authority check (Session A, from D-84a):**<br>- the fallback route already exists: a non-conflicted steward, **or a matter reviewer appointed by the platform owner for that matter alone**, who declares independence;<br>- the platform owner **appoints**; the reviewer **decides** that matter only;<br>- **no decision defines an authority to *remove* a classification.**<br><br>**So the implementation offers no removal at all.** This is stricter than (3) and needs no new authority. An independent steward or reviewer decides the matter itself, as a judgment matter. Reclassifying would only ever help a conflicted person decide it.<br><br>**Unchanged:** D-84, D-84a, D-88, D-89, D-90. | direct, 2026-11-13: Principal adopted ("I accept the recommendations written in the document": the Q-G3-1 recommended policy) |

### 11.131 Decision D-231 (G-7: display names of the five level-1 regions — D-87 extended)

Source: the G-6c acceptance audit and G-7 decision brief (Reference Geography tactical baseline v3.11 §9 "Not decided (kept open): names of the five regions"; §17); Principal DDD review of 2026-11-14; approved by the Human Arb Chief ("G-7 policy approved: Option A — Extend D-87").

| # | Decision | Provenance |
|---|---|---|
| D-231 | **Option A: D-87 is extended to the five level-1 regions (D-85: Africa, Americas, Asia, Europe, Oceania).** The name shown to users for a level-1 region is the **Unicode CLDR** display name **in the user's language**, resolved exactly as for countries and areas (v3.11 §9: the locale chain, the short form falling back to the standard form, the marked platform fallback). The UN M49 names are kept as **attributed alternative names** (SOURCE_NAME). PublicDigit authors no region name. D-87's own text is unchanged; any further exception still requires an explicit Principal decision. Not covered: names of territorial units below country level (v3.11 §11), and the starting value of the platform default locale (both G-8). | direct: "G-7 policy approved — Option A — Extend D-87", 2026-11-14 |

### 11.132 Decision D-232 (G-8: default locale, languages, sub-country names, entries outside ISO 3166-1)

Source: the G-8 decision brief (G-7 completion report, 2026-11-14; v3.11 §9, §17; D-86 (4)); answered by the Human Arb Chief.

| # | Decision | Provenance |
|---|---|---|
| D-232 | **(D1) The platform default locale starts as `en`.** Published as the PLATFORM_DEFAULT_LOCALE fact (a non-structural revision); used only when a user's locale chain has no CLDR name, and always marked PLATFORM_FALLBACK. A later change is an ordinary governed revision.<br>**(D2) The initial CLDR display names are loaded for the platform's interface languages only: German (`de`), English (`en`) and Nepali (`ne`)**, from one CLDR release (the newest at load time), standard and short forms where CLDR has them. Further languages are later non-structural revisions.<br>**(D3) Units below country level (v3.11 §17, Nepal's provinces, districts, local levels) carry official source names only** (the competent Nepali authority's names, in Nepali, plus an official romanisation where one is published), as attributed SOURCE_NAME claims; **no display-name rule** for them is defined now (CLDR subdivision names may be considered later).<br>**(D4) Entries outside ISO 3166-1 (e.g. Kosovo, XK) are admitted only on a customer need,** each by its own explicit Principal decision (D-86 (4)). | direct: "D1 en, D2 option a, D3 option a, D4 on customer need", 2026-11-14 |

### 11.133 Decision D-233 (PBDIGIT-345 task 10: the Platform–Membership routing contract)

Source: `docs/publicdigit/architecture/reviews/2026-11-14-platform-membership-routing-contract-proposal.md` (Stage A, commit `f7ac00787`) and the Principal DDD review of it; answered by the Human Arb Chief.

| # | Decision | Provenance |
|---|---|---|
| D-233 | **(D-1) Platform is an OPEN HOST.**<br>- Platform publishes the organisation-routing contract: `RoutedOrganisation` (application level, Laravel-free) and `RoutedOrganisationConnection` (infrastructure level, a narrowly scoped published integration contract, explicitly allowlisted).<br>- Organisation-owned contexts consume it through their **own Infrastructure adapters** only. Platform depends on no business context.<br>- Membership's Domain and Application never see Laravel's `Connection` or Platform implementation details.<br>**(D-2) M-2 re-checks the registry on every call** (ResolutionService through the published contract). Correctness takes precedence over one central read.<br>**(D-3) C2's accepted check order is preserved.** No organisation context answers `CROSS_ORGANISATION`. `SOURCE_NOT_LEDGER` answers a call where the routed context exists but its organisation is not registry-routable now. The non-registry case must be representable in the existing Platform design; no context is invented for a test.<br>**(D-4) T-12 is two suites:** the 135 slice-1 tests unchanged, plus an integration suite on real organisation databases through the connection factory.<br>**Condition:** the consistency between the registry check and the connection is defined and tested before Stage B (proposal §8). | direct: "I accept the recommendations" (Principal DDD review of task 10 Stage A, recommending D-1 A, D-2 A, D-3 and D-4 as proposed, plus the consistency condition), 2026-11-14 |

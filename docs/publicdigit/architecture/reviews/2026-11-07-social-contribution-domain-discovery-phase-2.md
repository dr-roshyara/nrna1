# Social contribution domain discovery: Phase 1 preparation package (originally issued as "Phase 2")

> **This is a workshop preparation package. It does not establish a bounded context, an aggregate, a state machine, an owner, a scoring method or an implementation plan.** Every model in it is a hypothesis for discussion with the business domain expert.

| | |
|---|---|
| **Status** | DISCOVERY PREPARATION, DRAFT. Not independently reviewed. Waiting for the Principal's review. |
| **Authority** | generated (Claude, Session A; Principal DDD Discovery Architect role). Not authoritative without human review. |
| **Authorisation** | Principal Decisions A and B (2026-11-07), and the Principal instruction "Social Contribution Discovery — Phase 2" (2026-11-07). Creation of this document at this path was authorised. |
| **Backlog** | `PBDIGIT-331` (this discovery) · `PBDIGIT-332` (PO decision on the existing module) · `PBDIGIT-333` (generalisation, blocked until the individual-case interviews W1–W3 of 331 are complete) |
| **Evidence base** | `docs/publicdigit/architecture/reviews/2026-11-07-social-contribution-cross-project-discovery.md` (hereafter **XPD**) |
| **Date** | 2026-11-07 |
| **Relation to the initiative plan** | **The authoritative phase numbering is `2026-11-07-social-contribution-initiative-story-and-development-plan.md` §11.** In that numbering this document is the **Phase 1 preparation package**. Its glossary (§5) and capability map (§10) are *pre-interview drafts* for Phase 2 (strategic DDD, separately authorised), labelled [H]. The filename keeps "phase-2" because committed documents link to it. *(Corrected 2026-11-07, C-8.)* |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0). Path as instructed. |

**Labels.**
- [F] verified fact from code or documents (with source).
- **[R] reported by the Principal from the conversation with Komal Oli.** It is a fact that it was reported; its content is not independently verified.
- [D] existing decision (with authority).
- [P] proposal by this document.
- [H] hypothesis requiring domain validation.
- [I] inference.
- [OQ] open question.

---

## 0. Session A's disagreements and clarifications (recorded before the package)

The Principal's review is accepted. The following points depart from, or sharpen, the instruction. They are recorded first so they are not silently absorbed.

1. **"Write stories" versus "no implementation tickets."**
   - [P] Only discovery and decision items were created: `PBDIGIT-331` (discovery), `PBDIGIT-332` (PO decision) and `PBDIGIT-333` (blocked discovery).
   - No implementation story was written. A story needs a capability and an owner, and neither is established. Writing one now would let a ticket become architecture.
2. **"Start strategic design."**
   - [I] Strategic design proper (who owns which capability, context-map changes) cannot conclude before the interviews: the Operating Loop's phase 2 requires a named owner.
   - This package prepares it: §10 is a hypothesis map, not a context map.
3. **Product-scope fit. This is the most consequential observation.**
   - [F] The product backlog defines PublicDigit as "an Organisation Governance Platform" and states "Nothing in the backlog exists outside" the organisation journey (`docs/publicdigit/backlog/README.md`, Level 0).
   - [F] Platform data is separated per organisation (`organisation_id`; XPD §2).
   - [H] An individual's social-contribution history is anchored to a person. It may span several organisations or none ("contributions made independently of party activities"), and it may be public.
   - [OQ-S1] Whether this is in product scope, and whether the profile is anchored to a person or to an organisation member, is a **PO decision**. It is not a modelling detail.
   - **Partly answered 2026-11-07 (C-13):** nrna1 is the product home (Principal direction). Open: the identity anchor (initiative plan §7.2, O-1/O-2/O-3) and whether a person-scoped capability is within product scope.
4. **"Recognition granted."**
   - [I] In the initial case, the certificates were issued by third parties. The platform would *hold a record of* recognition received; it would not *grant* it.
   - The glossary therefore separates **Recognition** (the issuer's act) from the **recognition record** (what the platform holds).
5. **A real, named person.**
   - [P] The name is used because the Principal uses it, and only reported statements are attributed to her.
   - [OQ-S2] Her consent to be the discovery case and to be named in project documents should be confirmed.
   - Her certificates and other evidence must not enter the repository.
6. **Priority.**
   - [F] The current delivery focus is frozen as WP-7C → WP-8 → EPIC-005 (`.claude/CLAUDE.md`). This discovery competes for attention. Recorded for the PO; not blocking.

---

## 1. Principal decisions and scope

- **[D] Decision A, initial business scope** (Principal, 2026-11-07):
  - Start with Komal Oli's individual social contribution profile as the initial discovery case.
  - Afterwards, investigate reuse for all members of political parties.
  - This does **not** authorise a general member contribution platform.
- **[D] Decision B, existing Contribution Points module** (Principal, 2026-11-07):
  - It is a historical experiment, pending formal PO confirmation (`PBDIGIT-332`).
  - It is not an approved capability, and its `GaneshStandardFormula` is not a foundation.
  - It is not deleted, modified, retired or migrated.
  - Its implementation does not represent approved business rules.
- **In scope:** business meaning, language, conceptual relationships, measurement stance, interview and workshop preparation.
- **Out of scope:** bounded contexts, aggregates, schemas, state machines, scoring, tickets for implementation, changes to PBDIGIT-330, ODA-01, the ratified Membership architecture, or Adjudication.

---

## 2. Business problem statement

**What was reported [R]:**
- Komal Oli, a Nepali political leader, has received many appreciation certificates.
- She has made numerous social contributions.
- These are not systematically measured or consolidated.
- She wants to make them visible to members of her political party and potentially the wider public.
- She wants to understand and demonstrate the extent of her social contribution.

**Problem statement [P]:**
> A person who has contributed to society over many years has scattered artefacts (certificates and other records) but no consolidated, substantiated account of *what* they did, *what supports it*, *who recognised it* and *what difference it made*. They therefore cannot present it credibly to their party or the public, nor understand its extent themselves.

**Not yet known [OQ]** (must not be filled in on her behalf):
- what "measured" and "calculate" mean to her;
- whether the audience is primarily the party, the public, or herself;
- what would make the account credible to a sceptical reader.

---

## 3. Capability hypothesis

**[H] Capability statement:**
> "Enable an individual to document, substantiate and present their social contributions through an evidence-backed contribution history, including activities, recognition and demonstrable outcomes."

**Its three verbs are separate sub-capabilities [H]:**

| Verb | Sub-capability | Core question |
|---|---|---|
| Document | Record what was done and in what role | *What happened, and what was this person's part?* |
| Substantiate | Attach evidence; possibly obtain verification | *Why should a reader believe it?* |
| Present | Curate a profile for an audience under visibility rules | *Who sees what, and what can they conclude?* |

**Need versus candidate solution [D, Principal ruling 2026-11-07; C-6].**
- **Business need [R/H]:** consolidate, substantiate, understand ("calculate") and present social contributions.
- **Initial discovery case:** Komal Oli.
- **Candidate solution [H]:** an individual contribution profile.
- **Unresolved:** whether the right form is a profile, portfolio, documented history, report or dossier, or a combination.

"Profile" is the discovery framing. It must not predetermine the domain model.

**[I] The hypothesis is silent on:**
- the identity anchor (person versus member, §0.3);
- who verifies;
- impact, which the statement mentions only as "demonstrable outcomes".

Those silences are the agenda for the workshops.

---

## 4. Problem A versus Problem B

| Aspect | **A: Individual Social Contribution Profile** (`PBDIGIT-331`) | **B: Office-holder accountability** (`PBDIGIT-330`) |
|---|---|---|
| Source | [R] Komal Oli conversation; [D] Decision A | [F] PBDIGIT-330 (PO story text, 2026-11-06) |
| Subject | A person | A post and its holder for a term |
| Purpose | Make social contribution visible and understood | Let the organisation review fulfilment of responsibilities |
| Initiator (hypothesis) | [H] The individual | [H] Organisation or committee |
| Scope of activity | Any social activity, possibly outside any party | Activities performed *in the official role* |
| Reference frame | None given; perhaps self-defined | Responsibilities, commitments, term objectives |
| Recognition | Central (certificates) | Not mentioned |
| Review | Not mentioned; credibility via evidence | Central: progress review, formal review decision |
| Audience | Party members, possibly the public | Committee and members |
| Scoring | Not requested as a score; "calculate" is ambiguous [OQ] | Excluded: "no arbitrary universal numerical scores" ([F] PBDIGIT-330 L109) |
| Governance dependency | Product scope (OQ-S1), consent, visibility | ARB-8 (Appointment / Tenure / Mandate), open |

**Possibly shared concepts [H]:** Activity, Evidence, Outcome, a verification or review decision, and the distinction between self-reported, verified and reviewed records (PBDIGIT-330 L110 states this as a constraint).

**[P] Position:**
- Shared *concepts* are not shared *ownership*.
- Problem A must not be routed into PBDIGIT-330 because both use the word "activity".
- Equally, PBDIGIT-330's rules (for example, formal review) must not be imposed on a personal profile without evidence that the business wants them.

---

## 5. Ubiquitous Language glossary (v1, for validation)

Each term is a candidate meaning, not a definition approved by the business.

### 5.1 Activity performed
- **Definition [H].** Something that actually happened in the world: an event, action or initiative carried out by one or more people, with a time, a place and a nature.
- **Example.** A health camp held in a village on a given date.
- **Relationships.**
  - Has one or more participants, each with a role (initiated, organised, funded, participated, led).
  - May produce outcomes.
  - May be the subject of recognition and of evidence.
- **Ambiguities.**
  - Is a long-term initiative (for example a scholarship programme over ten years) one activity, or a container of many?
  - Does an activity exist independently of anyone claiming it?
- **Validate.** How does the business speak about its work: "programme", "campaign", "event", "कार्यक्रम"?

### 5.2 Contribution claimed
- **Definition [H].** A person's assertion of *their* part in an activity: role, effort, resources.
- **Example.** "I organised the health camp and arranged the doctors."
- **Relationships.**
  - Refers to an activity.
  - Is made by or for a person.
  - May be supported by evidence.
  - May be subject to a verification decision.
- **Ambiguities.**
  - Can someone else (party, organiser) record a contribution for a person?
  - Can two people claim the same role?
  - Is the claim distinct from the activity record when the person is also the organiser?
- **Validate.** Who should be allowed to enter such a record?

### 5.3 Contribution verified
- **Definition [H].** A decision, by a party with standing to make it, that a claim is supported. The decision is about the claim, based on evidence.
- **Example.** The organising NGO confirms in writing that she organised the camp.
- **Relationships.**
  - About one claim.
  - Made by a verifier with an authority basis.
  - Cites evidence.
  - Can be negative (rejected) or contested.
- **Ambiguities.**
  - Which claims, if any, may a particular certificate substantiate, and to what extent? (See the §5.4 ruling: not automatically all claims.)
  - Is peer attestation verification?
  - Is "verified" binary, or does it come in kinds (by organiser, by beneficiary, by institution)?
- **Validate.** Whom would a sceptical party member trust?

### 5.4 Recognition granted (and the recognition record)
- **Definition [H].** *Recognition* is an issuer's formal acknowledgement of a person, for a stated reason. The *recognition record* is what a profile holds about it.
- **Example.** A municipality issues an appreciation certificate (प्रशंसापत्र) "for service during the flood relief".
- **Relationships.**
  - Issued by an issuer (an external or internal body) to a person.
  - May reference specific activities, or none (for example a lifetime honour).
  - The certificate artefact is also evidence (see §6, the double role).
- **Principal ruling (2026-11-07) [D]:**
  - Recognition is an act by an issuing institution or organisation.
  - A certificate can serve as evidence **of that recognition**.
  - A certificate does **not** automatically verify every claim about the underlying activity.
  - Recognition does **not** automatically demonstrate an outcome or social impact.
- **Ambiguities.**
  - Does recognition prove that the activity happened, or only that the issuer acknowledged it? (Open for the business; the ruling above sets the default: not automatically.)
  - Are all certificates equal in standing?
  - Can the platform's own organisation grant recognition?
- **Validate.** What exactly does each of her certificates say it recognises?

### 5.5 Outcome achieved
- **Definition [H].** A documented, observable result attributable (in whole or part) to an activity.
- **Example.** 340 people screened; 12 referred for treatment.
- **Relationships.**
  - Of an activity or initiative.
  - Supported by evidence.
  - Input to an impact claim.
- **Ambiguities.**
  - Output ("340 screened") versus outcome ("12 treated")?
  - How is attribution handled when many actors contributed?
- **Validate.** What results does she know of, and how?

### 5.6 Impact demonstrated
- **Definition [H].** A claim, supported by evidence and a stated method, that activities made a meaningful difference to people or a community beyond the immediate outputs.
- **Example.** Reduced maternal deaths in a district after a sustained programme, with comparison data.
- **Relationships.**
  - Built on outcomes, evidence and a method.
  - Usually concerns an initiative or a programme, not a single person.
- **Ambiguities.**
  - Can an individual's impact be separated from a collective one?
  - Who is competent to assess it?
- **Validate.** Does she want impact, or is a credible record of outcomes and recognition enough?

### 5.7 Financial contribution
- **Definition [H].** Money or material resources given (donation, sponsorship, funding of an activity).
- **Example.** She funded the camp's medicines.
- **Relationships.**
  - It is a *form* of contribution claim (the resource dimension), not social outcome.
  - [F] It is distinct from the membership fees and income in nrna1 (XPD §4.8).
- **Ambiguities.**
  - Should amounts be shown at all, given that money is not outcome?
  - Is there a risk of donation display being read as patronage or vote-buying?
- **Validate.** Visibility and sensitivity of amounts.

### 5.8 Organisational contribution
- **Definition [H].** Work performed *for an organisation in a role*: committee duties, office-holding.
- **Example.** Serving as district secretary for a term.
- **Relationships.**
  - Bound to a post, a term and responsibilities: PBDIGIT-330, ARB-8 territory.
  - [F] Role-history data exists, but is defective (XPD §4.2).
- **Ambiguities.**
  - Is serving the party "social contribution"?
- **Validate.** Should party roles appear on a *social* contribution profile at all?

### 5.9 Political participation
- **Definition [H].** Taking part in political processes: meetings, campaigns, rallies, elections.
- **Example.** Campaigning in a by-election.
- **Relationships.**
  - Activity-like, but its purpose is political, not social.
  - Participation is not leadership, and not contribution to society.
- **Ambiguities.**
  - Is the line between a social and a political activity clear in the Nepali context (for example, party-run relief work)?
- **Validate.** Where does she draw the line?

### 5.10 Deliberative or forum contribution
- **Definition [H].** Ideas, comments, evidence or proposals submitted in a deliberation.
- **Example.** A policy proposal in a members' forum.
- **Relationships.**
  - [F] Designed as `MemberContributionRecorded` in the members-forum design (XPD §4.6).
  - Unrelated to social service.
- **Ambiguities.** None for Problem A; it is listed to prevent the word collision.
- **Validate.** Confirm that it is out of scope for Problem A.

---

## 6. Conceptual relationship model (hypothesis, not an aggregate design)

```
  Person ──< Participation(role: initiated|organised|funded|participated|led) >── Activity / Initiative
     │                       │                                                        │
     │                makes  ▼                                                        │ produces
     │             Contribution claim ──── supported by ───> Evidence <── supports ── Outcome
     │                       │                                  ▲                     │
     │        is about       ▼                                  │ is also            │ input to
     │             Verification decision (by Verifier,          │ evidence           ▼
     │              authority basis, cites Evidence)            │               Impact claim (method + evidence)
     │                                                          │
     └──< receives ── Recognition (Issuer, reason) ─── artefact: certificate ─┘
                         │ may reference 0..n Activities

  Presentation (candidate forms: profile, portfolio, history, report, dossier)
            = a curated presentation of a person's records, for an audience, under visibility rules.
            It is a view: it owns no facts.
```

**Cardinalities to validate [H]:**
- An activity has many participants, and a person has many activities.
- A claim concerns one person and one activity.
- Evidence can support several things at once.
- A recognition may reference zero, one or many activities.
- An impact claim usually concerns an initiative, not a person.

**Three observations that matter for the model [I]:**
1. **A certificate plays two roles.** It is a *recognition* (the issuer acknowledged her) and an *artefact of evidence*, possibly of an activity. For the initial case, certificates are likely to be the main raw material. The model must not collapse "has a certificate" into "activity verified" or "impact achieved". This is a Principal ruling (§5.4), carried into all later phases.
2. **The lifecycle is not linear.** Recognition can exist without a recorded activity, and an activity without any claim. Outcomes may be documented years later. A single status field (as in the nrna1 experiment, XPD §8) cannot represent this.
3. **Provenance is part of the meaning.** For every record, a reader needs to know *who asserted it* (self, organiser, issuer, verifier) and *when*. This matches PBDIGIT-330 L110: "self-reported, verified evidence and formal review decisions must remain distinguishable".

---

## 7. Interview guide for Komal Oli

**Format [P].** A semi-structured conversation, ideally with three to five of her real certificates as concrete examples. Ask; do not suggest answers. Record her words, not our categories.

**0. Consent and framing**
- May we use your case to design this capability, and may we refer to you by name in internal documents?
- What would you like this conversation to lead to?

**A. Understanding her contribution**
- What kinds of social contribution have you made?
- Were they individual, collective or institutional activities?
- What was your part: did you initiate, organise, fund, participate in or lead them?
- Were they one-time activities or long-term initiatives?
- Which of these were connected to your party, and which were independent of it?

**B. Existing evidence**
- What appreciation certificates do you have? Who issued them, and roughly how many?
- Are there photographs, reports, news articles or institutional confirmations?
- Are there contributions for which you have no certificate?
- Are there people or institutions who could confirm the activities?

**C. Measurement**
- When you say you want to calculate or measure your contribution, what do you have in mind?
- Would you count activities, time, resources, beneficiaries, outcomes or impact?
- Which of these dimensions matter most to you, and why?
- What would make such a measurement credible to someone who doubts it?

**D. Recognition**
- For a few certificates: who issued it, and what exactly did it recognise?
- Should a certificate be shown on its own, or together with the activity it refers to?
- Are some recognitions more significant to you than others? Why?
- Does this certificate describe what you did, or does it express thanks or honour without describing the activity?

**E. Visibility**
- Who should be able to see your contribution profile?
- Should it be public, visible to party members only, or private?
- Which evidence should be visible, and which should remain confidential (for example, photographs of beneficiaries)?

**F. Expected value**
- If you wanted to demonstrate your social contribution to another person today, what information and evidence would you show them, and what is missing from that picture? *(Principal's central question, 2026-11-07; C-6. It names no presentation form.)*
- What question should a person be able to answer by looking at your profile?
- What would make you consider the profile complete and useful?
- Who would maintain it: you, an assistant, or the party?

---

## 8. Generalisation questions for political-party representatives (`PBDIGIT-333`; only after §7)

| Category | Likely common | Likely different rules [H] | Questions |
|---|---|---|---|
| Ordinary members | Activity, claim, evidence | Visibility defaults; who curates | Should every member have such a profile, or only on request? |
| Volunteers (possibly non-members) | Activity, participation | Identity anchor: not a member | Can a non-member have a record? Who owns it? |
| Elected representatives | Recognition, outcomes | Public interest; higher scrutiny | Is the profile part of public accountability? |
| Committee office holders | Activity, evidence | Overlap with PBDIGIT-330 (official role) | Which activities belong to the role, and which to the person? |
| Party-sponsored initiatives | Activity, outcome, impact | The initiative is the subject; there are many contributors | Is attribution to individuals appropriate? |
| Independent contributions | Activity, recognition | No party attribution; party is not the authority | May the party display, or verify, work it did not organise? |

**Further questions for representatives:**
- Who in the party would have authority to verify?
- What would the party *not* want shown?
- Risk of competition between members?
- Election-period sensitivities (a profile used as campaign material)?

---

## 9. Measurement and impact analysis

**Stance [P].** Each dimension is presented on its own terms. No dimension is converted into another, and no composite score is proposed. **Effort** (time, activities, resources) describes input. **Outcome** and **impact** describe results. They are reported separately.

| Dimension | What can be measured | Evidence needed | Self-reportable? | Needs independent verification | Cannot responsibly be quantified |
|---|---|---|---|---|---|
| Time and effort | Hours or days, frequency, span | Rosters, organiser confirmation | Yes, labelled as self-reported | For public claims of extent | The intensity, skill or sacrifice behind the hours |
| Activities performed (number, type) | Count by type and period | Activity records, photos, reports | Yes | That each activity occurred and her role in it | The "importance" of an activity by count alone |
| Financial and material resources | Amounts, in-kind items | Receipts, acknowledgements | Yes, but sensitive | For any displayed amount | Social value of money; money ≠ outcome |
| Beneficiary reach | Number of people served, by group | Registers, organiser data | Weakly | Yes: counts are easily inflated | Depth of benefit per person |
| Geographic reach | Places, districts, countries | Activity locations | Yes | Rarely needed | "Reach" as significance (one remote village vs. a city) |
| Duration | Start and end of initiatives, continuity | Dated records | Yes | For long-term claims | Sustainability of effect after she left |
| Outcomes | Documented results per activity | Reports, data, third-party statements | Claimable, but weak | Yes | Attribution share among many actors |
| Independent recognition | Count and kind of recognitions, by issuer | Certificates and the issuer's identity | Yes (artefact exists) | Authenticity of the artefact, if challenged | Comparative worth of different issuers' recognitions; **a count of certificates is not impact** |
| Demonstrated social impact | Only with a method (baseline, comparison) | Studies, official statistics, evaluations | No | Yes, by competent assessors | Individual share of collective impact; anything without a method |

**[I] Consequence for "calculating her contribution".**
- What can be produced credibly is a **multi-dimensional, provenance-labelled account**, for example: "42 activities (31 with organiser confirmation), 18 recognitions from 11 issuers, documented outcomes for 9 initiatives".
- A single number cannot be produced credibly. Whether she wants more than such an account is OQ-C1.

---

## 10. Candidate capability map (hypotheses; not bounded contexts)

| Candidate | Business responsibility [H] | Existing evidence (XPD) | Possible relationships | Potential ownership (options only) | Dependencies | Open questions |
|---|---|---|---|---|---|---|
| Contribution Management | Record activities, participation and claims | nrna1 experiment (P, Decision B); public-digit `member_activities` (D) | Feeds Profile; uses Evidence; subject of Verification | Person-scoped capability · Membership · Governance (for official-role activities) · new: **undecided** | OQ-S1 identity anchor | Is "activity" owned once, or per purpose (A vs B)? |
| Evidence Management | Hold artefacts and their provenance | public-digit `documents` with `file_hash` (D); nrna1 `newsletter_attachments` (other domain) | Supports claims, recognition, outcomes | Generic supporting capability · per-domain: **undecided** | Privacy, retention | Is evidence shared across A and B? |
| Verification | Decide on claims with stated authority | None implemented; election registration state machine (pattern for study only) | About claims; uses Evidence | Organiser · party · issuer · peers: **undecided** | ARB-8 for official roles | Who has standing? Disputes: kept separate from Adjudication unless decided otherwise |
| Recognition | Record recognitions received; possibly grant own | None (docs only) | References activities; is also evidence | Record-keeping only, or granting: **undecided** | Decision A scope | Can the platform's organisation grant recognition? |
| Impact Assessment | Methodical claims of difference made | None | Uses outcomes and evidence | Possibly outside the platform (external evaluators) | Competence, method | Is it in scope at all for an individual? |
| Individual Contribution Presentation (candidate form: profile) | Curated, audience-specific presentation | None (no biography or public profile anywhere) | Reads all of the above | Read model / presentation: owner of the *curation* undecided | OQ-S1, consent, visibility | Who curates: the person or the party? |
| Organisational Participation History | Posts held and terms | nrna1 `committee_assignments` (P, history defect); GMAP Tenure (D); PBDIGIT-330 (B) | Possible input to Profile | **ARB-8 (open)** | ARB-8, GMAP G-9 | Does party office belong on a *social* profile? |

---

## 11. Dependencies on existing architecture

| Item | State | Relevance |
|---|---|---|
| `PBDIGIT-330` | [F] PROPOSED, not authorised | Shares Activity, Evidence and Outcome concepts; constraints L109–110 inform the measurement stance. Not changed. |
| ARB-8 (Appointment / Tenure / Mandate ownership) | [F] Open | Blocks any conclusion on participation history |
| GMAP 2026-11-02, G-9 | [F] "Appointment history is append-only — violated today" | Corroborated by XPD §4.2. No ticket without separate authorisation. |
| ODA-01 | [F] Under its own approval process | Not changed. Identity-anchor questions (person vs member) touch its organisation-membership boundaries. |
| Ratified Membership architecture | [F] Frozen | A profile anchored on `members` would depend on it. A person-anchored profile might not. Hence OQ-S1. |
| Contribution Points module | [D] Historical experiment (Decision B); `PBDIGIT-332` | Not a foundation; untouched |
| Multi-tenancy (`organisation_id`) | [F] Platform invariant | Conflicts with a cross-organisation personal history unless the PO decides otherwise |
| Vote anonymity (ADR-T11) | [F] Constitutional | Unaffected. Contribution records must never be linked to votes. |
| Adjudication | [F] Separate workstream | "Disputed contribution" must not be routed there by assumption |
| public-digit designs | [F] Design only; backend absent | Reference material only |
| election registration workflow | [F] Implemented in another repository | Pattern for study only |

---

## 12. Open business questions

| ID | Question | Owner |
|---|---|---|
| OQ-S1 | Is an individual (person-scoped, possibly cross-organisation, possibly public) contribution profile within PublicDigit's product scope? Is it anchored to a person or to an organisation member? **Partly answered 2026-11-07 (C-13):** nrna1 is the product home (Principal direction); the identity anchor (initiative plan §7.2) and person-scoped scope remain open. | PO |
| OQ-S2 | Has the initial case consented to be the discovery subject and to be named in project documents? | Principal |
| OQ-C1 | What does "calculate / measure her contribution" mean to her: an account of extent, or a single figure? | Komal Oli (interview §7C) |
| OQ-C2 | Which dimensions (§9) matter, and to whom? | Komal Oli; party representatives |
| OQ-V1 | Who has standing to verify a claim? Is a certificate a verification? | Business domain expert |
| OQ-R1 | Does the platform only record recognitions, or may an organisation also grant them on the platform? | PO, party representatives |
| OQ-P1 | Default visibility; consent for showing beneficiaries; confidentiality of evidence | Komal Oli; PO (privacy) |
| OQ-P2 | Who curates and maintains a profile: the person, the party, or both? | Komal Oli; party representatives |
| OQ-B1 | Should party office and political participation appear on a *social* contribution profile? | Komal Oli; PO |
| OQ-I1 | Is impact assessment in scope for an individual profile, or only outcomes and recognition? | Komal Oli; PO |
| OQ-G1 | Which categories (§8) should eventually be supported? | PO, after `PBDIGIT-331` |

---

## 13. Recommended workshop sequence [P]

| # | Workshop | Participants | Input | Output |
|---|---|---|---|---|
| W0 | Scope and consent | Principal, PO | §0, OQ-S1, OQ-S2 | Decision on product-scope fit (at least provisional); consent confirmed |
| W1 | The individual story | Komal Oli, Principal | §7 parts 0, A, F | Her contributions, in her words; her expected value |
| W2 | Artefact walkthrough | Komal Oli (with 3–5 real certificates or records) | §7 parts B, D; §6 | Classification of real artefacts into activity, recognition and evidence; glossary corrections |
| W3 | Measurement and visibility | Komal Oli | §7 parts C, E; §9 | Her measurement intent (OQ-C1); visibility rules (OQ-P1) |
| W4a | Individual-case synthesis (Phase 1) | Session A, Principal | W1–W3 notes | Glossary v2 draft, relationship model v2 draft |
| W5 | Party representatives (Phase 1; after W1–W3) | Representatives | §8 | Generalisation evidence (`PBDIGIT-333`) |
| W4b | Final business-problem synthesis (Phase 1; after W5) | Session A, Principal | W4a, W5 | Glossary v2, relationship model v2, anchor decision package (O-1/O-2/O-3) for the PO and ARB |
| W6 | **Phase 2: strategic DDD, separately authorised; not part of Phase 1** | Principal, ARB | W4b | Capability ownership options, context-map impact, ADR if warranted |

W2 matters most. Working with real artefacts shows the double role of certificates (§6) and the actual evidence landscape faster than abstract questions can.

---

## 14. Decisions still required

| # | Decision | Authority |
|---|---|---|
| 1 | Product-scope fit of an individual social contribution profile (OQ-S1). **Partly answered 2026-11-07 (C-13):** nrna1 is the product home; the identity anchor and person-scoped scope remain open. | PO |
| 2 | Identity anchor (person or member) | PO / ARB, after W4b |
| 3 | Confirmation of the Contribution Points module status (`PBDIGIT-332`) | PO |
| 4 | Consent and naming of the initial case (OQ-S2) | Principal |
| 5 | Authorisation of interviews W1–W3 | Principal |
| 6 | Measurement stance: a multi-dimensional account versus anything aggregated (after OQ-C1) | PO |
| 7 | Priority relative to WP-7C → WP-8 → EPIC-005 | PO |
| 8 | Whether to raise a ticket for the committee-history defect (XPD §4.2) | Principal (separate authorisation) |
| 9 | Authorisation of strategic design (W6) | Principal / ARB |

**Stop. Waiting for the Principal's review.**

---

**Traceability.** Principal review and Decisions A and B (2026-11-07) · XPD 2026-11-07 · `PBDIGIT-330` · `PBDIGIT-331` · `PBDIGIT-332` · `PBDIGIT-333` · GMAP 2026-11-02 · ODA-01 · `docs/publicdigit/backlog/README.md` (Level 0).

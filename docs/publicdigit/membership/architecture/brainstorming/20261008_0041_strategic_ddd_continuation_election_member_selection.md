# Strategic DDD continuation — Election Member Selection (response to brainstorm 0032–0038)

**Type:** brainstorming (research, **not** a decision) · **Date:** 2026-10-08 00:41 · **Author role:** DDD Architect + Senior Requirements Engineer (`claude-code-session:42e0287e…`, self-declared)
**Responds to:**
- `20261008_0032_review_of_membership_domain_discovery_part1.md` (German review of Discovery Part 1)
- `20261008_0032_committee_level_type_and_election_committee_selection.md`
- `20261008_0035_election_member_selection_contract_composable_criteria.md`
- `20261008_0038_selection_contract_multi_context_facts_and_exclusions.md`

**Base:** `docs/publicdigit/reviews/2026-10-08-membership-domain-discovery.md` (Part 1).

> Tags as in Part 1:
> - `[adopted]`: decided
> - `[PO-input]`: the PO's business statement in this brainstorm. It is **business input, not a ratified rule**.
> - `[legacy-impl]`: what the code does
> - `[candidate]`: proposed in this note
> - `❓`: needs a decision
>
> Nothing here is adopted.

---

## 1. What the PO actually said (extracted from the brainstorm prompts)

Read through the assistant's replies, the PO's business input is five statements:

| # | PO input (paraphrased; quoted where the brainstorm quotes it) | Discovery question it addresses |
|---|---|---|
| PI-1 | *"only certain committee level and certain committee type can take part in a particular election"* | MQ-5, MQ-8 |
| PI-2 | *"who exactly can take part is decided by election committee"* | MQ-10, plus a new authority question |
| PI-3 | an election should *"define electionmember from members"* through combinable criteria (type, level, …) | MQ-5 |
| PI-4 | *"an organisation can have many rules"*, for example dues outstanding | MQ-4, MQ-5 |
| PI-5 | *"a person who is suspended from election via a kind disciplinary committee may also not be able to take part"* | new: a third suspension source |

**Consequence for the discovery:** MQ-5 is answered **in direction**. Voting participation is **not a property of Membership or of the MembershipType**. It is the outcome of **election-owned selection criteria** evaluated over facts from several contexts, followed by an **election authority's decision** (option (d) of MQ-5, generalised). I accept this direction, and it is consistent with the adopted record:
- EM-ENT-007: *the Election Chief governs ElectionMembership*;
- EM-ENT-005: *one entitlement concept; only the route to admission differs*;
- ADR_20261004: *Registration ≠ Admission ≠ Entitlement*.

Below I challenge **six points** where the brainstorm, taken literally, would collide with adopted rules, with the platform's integrity mission, or with the existing code model.

---

## 2. Challenge 1: the invariant needs a *time* (the most important correction)

The brainstorm's central invariant is:

> `ElectionMember(p,E) ⇒ SelectionContract_E(p) = TRUE`

As written, it is **timeless**. Read as "at all times", it contradicts adopted rules:
- **EM-ENT-004:** *changes to a person's Organisation Membership do not automatically destroy the election entitlement*;
- **EM-ENT-003:** *the entitlement remains unless a defined election-level removal rule terminates it*;
- **EM-FM-006:** membership is an **admission** prerequisite, and only **exercisability** is continuously affected.

**Candidate restatement (two invariants, each with its time made explicit):**

```text
INV-SEL-1 (admission-time):   Admitted(p,E,t₀)  ⇒  Contract_E(p, facts@ref_E) = TRUE
INV-SEL-2 (after admission):  a later change of a fact never deletes ElectionMembership;
                              it may only affect EXERCISABILITY, and only for fact kinds
                              that a rule explicitly names (today: EM-FM-003, membership removal).
```

**Why this matters:** without INV-SEL-2, every criterion becomes a hidden continuous check. Dues falling overdue mid-vote, a member changing committee, or a birthday crossing an age band would each silently disenfranchise someone. That is exactly the "membership checked continuously" model the PO rejected with F1. D-ENT-2 then made one *named* exception.

❓ **SQ-1:** for each criterion family (dues, committee position, disciplinary ban, type), is the effect after admission (a) none, (b) suspension of exercisability as in EM-FM-003, or (c) termination? *(This generalises FM-6/FM-7/FM-9 and `W-3` and does not replace them.)*

## 3. Challenge 2: "Election Committee decides who exactly" — discretion or verification?

The example in brainstorm 0032 §9 (*"150 eligible → 120 admitted"*) models admission as a **free choice** among eligible people. For a voting platform whose mission is integrity, **that is the most dangerous sentence in the brainstorm**. A body that can leave 30 eligible members off the electorate without grounds can decide the election's outcome.

There are two governance models, and the PO must choose (❓ **SQ-2**):

| | **Bound admission** (`[candidate]`, recommended) | **Discretionary selection** |
|---|---|---|
| Rule | every person who satisfies the contract **is admitted** unless a **named ground** applies (identity unverified, duplicate, objection upheld) | the committee picks from the eligible pool |
| Refusal | requires a **ground + reason + evidence** and is recordable and contestable | none required |
| Fits | ADR_20261004 (admission is **evidence-based**: identity verification by the Chief or Deputy, D1–D3); German *Vereinsrecht* practice (Wählerverzeichnis plus Einspruch) | delegate selection, where the electorate is a *chosen body* |
| Risk | low | **manipulation of the electorate** |

If discretionary selection is a genuine business need (for example a delegate assembly), it is a **different election type**: the chosen people form a *body*, and the selection is itself an act that must be recorded. It should not be the default meaning of "admission".

## 4. Challenge 3: the contract already exists in part. Generalise it; don't create a second one.

Canonical discovery (ES-005.4) finds **two existing homes** that the "Selection Contract" would otherwise duplicate:

1. **`voter_source_strategy`** `[adopted: immutable per-election snapshot]`. This is today's one-criterion contract: `election_only` means "the imported list", and `full_membership` means "the member registry". **Candidate:** the Selection Contract **subsumes and supersedes** this snapshot rather than standing beside it. P1 (Election-Only) becomes a contract whose population is "the imported list". P2 becomes a contract over the member registry plus criteria. That is precisely EM-ENT-005 (*only the route differs*).
2. **`VoterAdmissionPolicy`** `[adopted: ADR_20261004]`, with its lifecycle lock **D4**: changeable only in Draft, SetupAdministration and SetupNomination, and immutable from ReadyForVoting. **Candidate:** the Selection Contract follows the **same lock and snapshot discipline**. It is adopted in setup, frozen at ReadyForVoting, and records who adopted it and when. *(This answers the brainstorm's §11 "snapshot" point by reusing an adopted rule instead of inventing one.)*

So the chain becomes a refinement of the adopted one, not a second vocabulary:

```text
Selection Contract (frozen, versioned; supersedes voter_source_strategy)
   → Rule-eligibility (derived, never stored – like Admission, ADR)
   → Registration (ElectionMembership created)        [adopted term]
   → Admission (policy + evidence, bound decision)     [adopted term; SQ-2]
   → Voting Entitlement (… ∧ exercisable)              [adopted term]
```

⚠️ **Term collision to avoid:** brainstorm 0032 calls the eligible set an **"Eligible Candidate Pool"**. **"Candidate" already means a person standing for a post** (Candidacy). Use **Eligible Electorate** or **Rule-eligible Population** (L-7, new).

## 5. Challenge 4: Committee "level" and "type" are already in the code, and overloaded

`[legacy-impl]` canonical findings:
- `app/Contexts/Membership/Domain/Committee/CommitteeLevel.php` is a value object carrying an index of 1–10, a geography policy and scope, role limits, **minimum membership years, an age range and a gender requirement**. That is a *selection-criteria* model for **committee** membership that **already exists**.
- `app/Contexts/Membership/Domain/ValueObjects/CommitteeType.php` puts **level** values (CENTRAL, PROVINCE, DISTRICT, WARD) and **type** values (YOUTH_WING, WOMEN_WING, STUDENT_WING, DIASPORA) into **one dimension**.

**Model-integrity check** (CLAUDE.md rule: new dimension, overloaded dimension, or merely another value):
- **Level** is a position in a hierarchy that is probably **geographic**. It is tied to `GeoScope`, the geo units and the regional posts.
- **Type/Wing** is a functional or demographic grouping.
- They are **orthogonal**: a district youth wing exists. That makes `CommitteeType` an **overloaded dimension** today. The brainstorm's two dimensions are right, but the code splits them differently.

❓ **SQ-3:** Is "committee level" a property of the **committee** or of an **organisational unit / jurisdiction** that committees sit in? The geography coupling suggests the latter. It also connects to the existing **regional posts** filtered by `user.region` (root CLAUDE.md), which is a *post-level* rule distinct from election-level selection. **Do not** model level twice (committee level, geography and region).

## 6. Challenge 5: which context owns which fact? The two brainstorms disagree

- Brainstorm 0032 (review), §27: **Payment → Membership** ("Membership *interprets* payment evidence").
- Brainstorm 0038, §2–3: **Finance publishes "Financial Standing" directly to Elections.**

These are incompatible upstream paths for the **same** fact (dues compliance). `[candidate]` resolution:
- Dues are an **obligation of membership**, because the membership type defines the fee (`[legacy-impl]`). So **Membership owns "dues compliance"** as part of Membership Standing.
- Finance/Payment is upstream of Membership and supplies only **payment evidence**.
- Elections never talks to Finance.
- This keeps the number of published facts small. **One fact gets one publisher.**

The same discipline applies to PI-5, the disciplinary ban:
- **Who publishes "excluded from election participation"?** If it is a decision of a disciplinary body, the publisher is **Governance** (the decision record), not Membership and not Elections.
- ❓ **SQ-4:** does a disciplinary ban **exist as a business fact today**? Is the disciplinary committee an organ the organisation has, and does it act per election or for a period? The current code has **two** suspension notions: the Chief's suspension (election-scoped, ElectionMembership) and organisation-membership suspension (Member). PI-5 would add a **third** (organisation-wide, participation-specific). Together with EM-FM-003 that gives **four suspension causes**, so `W-3` (several causes; does clearing one restore?) becomes urgent, not academic.

**Candidate context map (strategic, not final):**

```text
Identity ──► Organisation (Affiliation, Org Roles, Units/Jurisdictions?)
                 │
       ┌─────────┼───────────────┬───────────────────┐
       ▼         ▼               ▼                   ▼
  Membership   Governance       Finance            Elections
  (Member,     (Committees,     (payments)         (Selection Contract,
   Type,        positions,         │                ElectionMembership,
   Standing     disciplinary       │ payment         Admission, Entitlement)
   incl. dues)  decisions)         │ evidence
       │         │               ──┘──► Membership
       │ OHS: Membership Standing          
       │         │ OHS: Committee Position, Participation Ban
       └─────────┴─────────────────────────────────► Elections (ACL: criterion evaluators)
```

- Upstream contexts expose an **Open Host Service** with a small **Published Language** (fact types plus reason codes).
- Elections holds an **anti-corruption layer**: one *criterion evaluator* per fact family. It never reads the tables (this resolves O-1 and I-1).
- **Not every arrow should exist on day one.** EM-SEQ-001 still holds: Full Membership is a later phase.

## 7. Challenge 6: who may define criteria? There are three authorities, not one

Brainstorm 0038 §18 rightly separates *defining* from *applying* from *deciding*. Made explicit:

| Act | Candidate authority | Bound by |
|---|---|---|
| **A1:** define the **criterion vocabulary** (which fact kinds an election may use at all) | the organisation's constitutional authority ❓ (EM-OPEN-014: the "Organisation Chief" has no referent) | platform invariants (anonymity, non-discrimination ❓) |
| **A2:** adopt **this election's** contract (pick criteria and values) | the Election Committee (Chief) ❓, in setup states only (cf. D4) | A1 |
| **A3:** decide **admission** of a person | Chief / Deputy (cf. ADR D2) | the contract plus SQ-2 |
| **A4:** evaluate the criteria | the system (deterministic, explainable with reason codes) | — |

❓ **SQ-5:** brainstorm 0032 §13, Model A vs Model B. Does the Election Committee choose the criteria (A2 is the committee), or does the organisation's constitution fix them per election *type* (A2 is constitutional and the committee only applies them)? **Is A2 itself something that must be approved,** for example in the existing Draft → SubmittedForApproval → Approved flow? That flow already exists in the lifecycle and would be the natural home for it.

⚠️ **The `CommitteeLevel` criteria (age, gender, minimum years) raise a platform-invariant question:** which criteria is an organisation **allowed** to use? Gender- or age-restricted electorates may be legitimate (a women's wing, a youth wing) or unlawful, depending on the organisation and jurisdiction. ❓ **SQ-6:** does PublicDigit impose any guardrail on the criterion vocabulary, or record the organisation's constitutional basis per criterion as evidence?

## 8. Agreements with the brainstorm (accepted as candidate direction)

- Membership provides **facts**, never "voting rights". Remove `grants_voting_rights` from the target model (it stays `legacy-impl` until it is migrated).
- Membership Standing is a **derived** fact with **reason codes** and `as_of`. It is minimal and **not** a god concept (review 0032 §25–26).
- Criteria are **composable value objects** (predicates). Use **no rule engine** until the business shows a need for OR or NOT (0035 §10, 0038 §16). **Include + exclude** lists are enough for every PO example so far.
- **Every** fact consumed crosses a published contract. The architecture rule from 0032 §35 is: *"A context may consume another context's published domain contract, but must not infer the other context's business meaning from its persistence model."* → candidate ARB principle, which resolves O-1.
- Requirement cards gain Context, Trigger, Preconditions and Postconditions (0032 §17). Accepted for Part 2.
- Committee association is **not** part of the Member aggregate (0032 §6). It answers MQ-8 in direction: **Governance owns it**. ⚠️ This conflicts with `[accepted-legacy]` ADR-F3.2 D8 (*Membership owns lifecycle truth* for the committee lineage), which therefore needs **retiring or amending**. This is a governance item, not an implementation item.
- MQ-0 is a product decision and MQ-10 an Election policy. Neither blocks the domain model (0032 §30–31).

## 9. Revised question set (replaces MQ-5, MQ-8, MQ-10; others stand)

| # | Question | Priority |
|---|---|---|
| MQ-1 | when does someone become an active Member (approval vs payment) | **1** |
| MQ-3 | who holds membership authority (Organisation Chief, EM-OPEN-014) | **1** |
| **SQ-2** | is election admission **bound** (admit unless a named ground) or **discretionary** | **1**: integrity |
| **SQ-1** | the effect *after admission* of each criterion family (none / suspend / terminate) | **1**: generalises FM-6/7/9 |
| **SQ-5** | who adopts an election's selection contract, and is that adoption approved | 2 |
| **SQ-3** | committee level: a committee property or an organisational unit/jurisdiction | 2 |
| **SQ-4** | does a disciplinary participation ban exist; who decides it; its scope (one election or a period) | 2 |
| **SQ-6** | guardrails on allowed criteria (age, gender, residence) | 2 |
| MQ-4, MQ-6, MQ-7, MQ-2, MQ-9 | as in Part 1 | 3 |
| ~~MQ-5~~ | **answered in direction** by PI-1…PI-4: election-owned criteria | — |
| ~~MQ-8~~ | **answered in direction**: committee association lives in Governance (pending ADR-F3.2) | — |
| ~~MQ-10~~ | **reframed** as an Election policy; the "auto" question folds into SQ-2 | — |

## 10. Proposed next step

1. The PO answers **MQ-1, MQ-3, SQ-1, SQ-2** (four answers unlock the lifecycle, the authority model and the contract semantics).
2. Then **Discovery Part 2**: Phases E–J, with the Selection Contract modelled as the generalisation of `voter_source_strategy` and VoterAdmissionPolicy, the context map of §6, and the requirement cards in the extended format.
3. **Governance items to raise (not decide):** the candidate ARB principle "no persistence-level inference across contexts"; the status of ADR-F3.2 D8 under the committee-in-Governance direction; whether a *Membership* business-rule catalogue should exist alongside the Election Manifesto.

**Traceability:** brainstorm files 0032–0038 (this folder) · Discovery Part 1 · `ELECTION_MANIFESTO.md` EM-ENT-003/004/005/007, EM-FM-003/006, EM-SEQ-001 · ADR_20261004_1549 D1–D4 · `PBDIGIT-68` (F1, D-ENT-2) · `CommitteeLevel.php`, `CommitteeType.php` · ADR-F3.2 (`architecture_legacy/membership/decisions/`).

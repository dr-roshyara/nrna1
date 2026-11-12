# Governance committees after D-144: discovery and gap analysis

| | |
|---|---|
| **Status** | **Proposal.** Records no ruling; changes no code. Written by Claude, not independent |
| **Commission** | Principal DDD Architect, "Governance Strategic Discovery and Committee DDD Design", accepted by the Principal 2026-11-10 |
| **Method** | **Canonical discovery first:** the existing Governance model is consumed, not rewritten. Sources: model document §5, §20, §33, §34, §40, §41, §42.2, §49–§51; decision record D-7, D-10 … D-12, D-15, D-21, D-23, D-24, D-28, D-29, D-37, D-38, D-65 … D-78, D-97, D-114 … D-144; V-series [PK] |

**Labels:** [AD] decided · [BF] fact · [AC] conclusion · [TD] tactical design · [WD] working decision · [P] proposal · [H] hypothesis · [OQ] Principal question.

## 0. Disagreement with the commission (stated first)

**The commission treats Governance discovery as not yet started. It is not.**
- **Governance has a working tactical model** (D-10, [WD]), refined over six rounds (§20, §33, §34, §40, §41). **§41.8 states "no Principal decision remains"** for it.
- **It already covers most questions the commission lists:**
  - organ, position, tenure;
  - appointment, election, delegation, removal, resignation, expiry;
  - powers, delegation and mandates;
  - decisions and remedies.
- **Starting again would create a second Governance model**, which ES-005.4 forbids.

**What the existing model has not done is meet the later Organisation design** (§49–§51, D-114 … D-140) and D-144. **That is where the gaps are** (§3). This document is therefore a **gap analysis plus a tactical delta**, not a new discovery.

## 1. Governance in business terms [AC]

Governance is **the organisation's constitution at work**:
- **the written rules**, and who may change them;
- **the bodies** (committees, the tribunal);
- **the offices in those bodies** and who holds them, for how long;
- **what each body and office may decide**;
- **the decisions they record.**

It **does not** own:
- who is a member (Membership);
- where members belong, or which units exist (Organisation; Governance **decides** structural changes, Organisation **records** them, D-29);
- who a person is (Identity);
- elections (Election, D-23);
- ballots (Voting);
- rulings on disputes (Adjudication, D-67);
- money (Finance);
- the map (Reference Geography);
- technical permissions (Authorization: **mandates flow out of Governance; Governance never reads them back**, §41.6).

## 2. The committee concepts and where each is already answered

| Concept | Meaning | Status and source |
|---|---|---|
| **Organ** (committee) | A constituted body under the written rules, attached to one or more units (own or joint) | [WD] §20.2, §33.1; at most one active own organ per unit and kind; **a unit may have no committee**, and then competence falls to the Highest Committee ([PK] decision record §6) |
| Organ ≠ unit | The district is a unit (Organisation); the district committee is an organ (Governance) | [AD] PO-DR07-01, D-142, D-144; §42.2 |
| Organ without a unit? | Organisation-wide organs attach to the **root**, which always exists while there are members (S-9) | [AC] from S-9; no free-floating organs |
| Organ serving several units | Joint organ | [WD] §20.2 |
| **Position** | An office in an organ; created and abolished by decision; number and kinds per the rules | [WD] §33.1; size by rules (V-46); chair its own place (V-47); abolition D-66 |
| **Tenure** | A person holding a position for a period, inside Position | [WD] §41.2 (option A, storage exclusion constraint) |
| Appointment / election / succession / acting | Different **Basis** kinds starting a tenure | [WD] §41.4; election results via D-23, start of term D-28; acting posts §20.2 |
| Removal / resignation / death / expiry / abolition | Different **Basis** kinds ending a tenure | [AD] D-24 (vacancy only by formal decision), D-37 (death per rules), D-66 (abolition); resignation submitted is a recorded fact (§33.4) |
| Membership changes during a term | **Does not end office by itself**; the competent organ decides (D-24); Governance reads CR-01 C3 at τ | [AD] D-24; [RAT] CR-01 C3 |
| **Powers** | Grants in the written rules (RuleProvision), never in Position | [AD] D-38, D-97 |
| **Delegation** | AuthorityAssignment; delegable only if the rules allow (D-76); transitions (D-78) | [WD] §41.1 |
| **Mandate** | Derived integration event for Authorization, never a Governance fact | [AD] D-7, D-72, D-74 |
| **Decisions** | Submission → Sitting → Resolution (immutable), competence checked on the decision date (D-12) | [WD] §20.1; D-65, D-70 |
| Challenges | Adjudication decides; Governance applies remedies (RemedyApplication) | [AD] D-67, D-71; [WD] §40.1, §41.5 |
| Office holder reference | The organisation's `PersonRef` (Identity issues, Organisation's register holds) | [AD] D-75, D-113 |

## 3. Gaps found where Governance meets the later Organisation design

### G-1. "Higher body": the authority ranking has no source [AC → TD]

**Fact:**
- §51 (A-7, the Adopt / Change / Remove / Higher-body rows) and D-119 / D-136 / D-140 all depend on **"a higher body"**.
- D-136 and A-7 forbid inferring authority **from names, levels or containment**.
- **Nothing in Governance or Organisation says where "higher" comes from.**

**Conclusion:**
- Authority is Governance's (D-144, D-38). **"Body X prevails over body Y for allocations in scope S" is a Governance fact**, from the written rules.
- **If it is not recorded, D-136 already decides the outcome: competence unknown → refused and reported.** So no new Principal decision is needed.

**Proposed [TD]:**
1. **RuleProvision records precedence** as a grant like any other: *"organ kind K₁ prevails over organ kind K₂ for allocation decisions in scopes of kind S"*, citing the provision.
   - **When an organisation is set up, its organ precedence is recorded once from its statutes.**
   - D-115 (the province committee is the default allocation authority unless the highest committee decides otherwise) is a **working direction**, to be confirmed from the statutes, never assumed.
2. **Every allocation decision Governance sends to Organisation** (`BelongingAllocationsDecided`, D-29) carries:
   - the deciding organ;
   - **Governance's precedence evaluation on the decision date** (D-12): which existing allocations in that scope it prevails over, is subordinate to, or is authorised against by an identifiable provision (D-136).
   - **Organisation records that evaluation; it never ranks bodies itself.**
3. **Later rule changes do not silently re-rank stored allocations** (D-12). A new rule version that changes precedence takes effect through a new decision or its transition provision (D-78 pattern).

**Why this matters for Geography:** this is the bridge that lets an area rule ("everyone in D4 belongs to district D3") rest on **constitutional authority** rather than on map containment. **Geography supplies only "W lies inside D4"; who may decide about W comes from Governance.**

### G-2. What happens to a committee when its unit ends — **DECIDED: D-145, option (a)**

**Fact:**
- Organisation's lifecycle says **"its organs' fate is Governance's"** (§49.9; V-3, V-4, V-48).
- The organisation's rules decide (V-48, V-50, V-58), and the deciding act may say what happens.
- **No rule says what happens when the closing decision and the rules are both silent.** The rule for abolished positions (D-66) has a default; the rule for organs does not.

**Why it matters:** a unit ends by dissolution, division, merger or absorption. If its committee keeps existing with no unit, it holds powers over nobody. If it silently follows a successor, an **absorbing** unit could end up with **two committees** (contrary to "one own organ per unit and kind").

**Options:**

| | Rule when the decision and rules are silent | Consequence |
|---|---|---|
| **(a) Ends with its unit** (recommended) | The organ ends on the unit's end date: tenures end, delegations end, mandates revoked; pending matters pass to a **named successor organ, otherwise lapse** (§20.1). Competence over the successor area falls to the **Highest Committee** until a new committee is established ([PK], decision record §6) | Mirrors D-66; never two committees for one unit; reported in the closure impact report |
| (b) Follows a declared successor unit | Like members (D-15) | Two committees after an absorption or merger; needs another rule |
| (c) Continues, suspended | Exists without powers until a decision | Office holders appear to hold office with no authority; confusing |

**Question:** *When a unit ends and neither the deciding act nor the written rules say what happens to its committee, does the committee end with the unit (a), follow a successor unit (b), or continue suspended (c)?*

### G-3. Organ attachment over time [TD]

- Organ attachments name **Organisation's units** (`UnitRef`) and must be valid **on the effective date, as known at τ**, read through R-2 `StructureAsAt` (D-29).
- **An attachment to a unit not active on that date is refused.**
- **A unit change of tree** (§50.1 R-1) keeps the attachment if the unit's identity continues; otherwise G-2 applies.

### G-4. Geography is never read by Governance [AC]

- A committee's **area** is its unit's coverage (Organisation, D-80).
- **Grants of power are scoped by units or organ kinds, never by official references.**
- That keeps D-106 §6 intact: a map change can never change who holds authority where. This confirms context map §16.6.

### G-5. Legacy code [BF]

- `app/Contexts/Membership/Domain/Committee/` mixes three concepts:
  - units → Organisation;
  - organs → Governance;
  - assignments → tenures (§42.2).
- **It is frozen as a behavioural reference (D-144). It is evidence of current behaviour only.**
- **Classification of existing rows** stays O-16.
- **Defect:** ended assignments are rewritten (`left_date = now()`). It must **not** be carried into the new model; tenures are append-only (§41.6).

## 4. Tactical delta for the Governance model (round 7, [P])

| Change | Where |
|---|---|
| RuleProvision gains **precedence grants** (G-1 §1) | §33.1 RuleProvision |
| **Precedence evaluation** domain service (with organ competence, D-65/D-70) | §33.2 |
| `BelongingAllocationsDecided` carries decider, precedence evaluation, provision basis | §16 contracts / D-29 R-1 |
| Organ: `AttachUnit` validated against `StructureAsAt(d, τ)`; **organ end on unit end per G-2** | §33.1 Organ, §33.4 |
| Closure impact report lists affected organs, tenures, delegations | §51.5 closure row (Organisation) ⇄ Governance |
| Invariant **G-I7:** an organ's attachments are active units on every date of the organ's life | §40.2 |
| Invariant **G-I8:** no allocation decision leaves Governance without a precedence evaluation | §40.2 |

**Entry condition for completing tactical design:** met (G-2 decided by D-145). **The Governance ⇄ Organisation contract is finalised only after IR-OTD-04 and acceptance of §49–§51.**

## 5. Remaining uncertainty

- **Statute content** (O-8 … O-10): organ kinds, precedence, quorum, incompatibilities. **Per organisation, as UNKNOWN until on file. The generic model does not depend on it.**
- **Authorization's synchronous check** for sensitive operations (§41.5): Authorization's tactical design.
- **[TU]** Storage proofs (exclusion constraint, guard): §41.8.

**Follow-up (2026-11-10):** the tactical delta in §4 is written out as **round 7**: `docs/publicdigit/reviews/2026-11-10-governance-tactical-model-round-7-committees.md` (PROPOSED).

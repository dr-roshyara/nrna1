# Minimum package (Membership, Newsletter, Forum): coherence check

| | |
|---|---|
| **Status** | **Coherence check.** Records no ruling; changes no design text. Written by Claude, not independent |
| **Scope** | D-146, D-147, release 1 |
| **Designs checked** | Membership (RAT, D-96; CR-01); Organisation §49–§50 (with §51.3 descent) under IR-OTD-04; Governance thin slice (`docs/publicdigit/reviews/2026-11-10-governance-thin-slice-minimum-package.md`); Newsletter and Forum (`docs/publicdigit/reviews/2026-11-10-newsletter-and-forum-design-minimum-package.md`); Identity (D-75, D-111, D-113; I-TDD-01/02 in their own session); D-104 workspace access; Authorization (BDR-07) |
| **Decisions in force** | D-144 … D-153 and earlier |

**Labels:** [AD] decided · [BF] fact · [AC] conclusion · [P] proposal · [OQ] Principal question · [TU] technical uncertainty.

## 1. Verdict

- **The pieces fit together.** The one blocking gap (K-1) is decided by D-154; three clarifications remain (K-2 … K-4).
- **Nothing has two owners.** No design contradicts a ratified or accepted text.
- **Reference Geography plays no part in release 1, and nothing depends on it.**

## 2. Ownership across the package (one owner per fact)

| Fact | Owner | Used by |
|---|---|---|
| Person, account, login, claim, `PersonRef` | Identity | everyone, by `PersonRef` |
| Person profile incl. contact e-mail | Organisation (D-101) | Communication |
| Units, placement, belonging, capacities | Organisation | Governance, Communication, Deliberation |
| Membership truth (coverage; standing in C3) | Membership | Organisation (C1, C2), Governance (C3) |
| Rules on file, committees, offices, terms, decisions, authority | Governance | Membership, Communication, Deliberation (AuthorityCheck) |
| Series, issues, dispatch, opt-outs | Communication | — |
| Forums, posts, supports, moderation acts | Deliberation | — |
| Workspace access | Organisation (D-104) | Authorization |
| Technical enforcement | Authorization (owns no business truth) | — |

## 3. Findings

### K-1. Who may create units and place members? — **DECIDED: D-154**

**Fact:**
- Release 1 needs units (D-147) and placement by individual act (D-79).
- **Units are created by a committee's structural decision**, which Organisation records (D-21 describes the organisations studied; D-29).
- **Placement is an act by an authorised body.**
- **The thin slice's AuthorityCheck covers admission, newsletter and moderation, but not structural decisions or placement acts.**

**Consequence:** without these, no unit below the top unit can be created and no member can be placed. **The forums of D-152 would then exist only at the top unit.**

**Proposed addition:** two act kinds:
- `decide-structural-change(scope)`;
- `place-member(unit)`.

They follow the same evidence rules as the thin slice §3. **Decided by D-154:** when the rules are silent, structural changes wait for a committee designation (as D-149); the admitting body may name the unit at admission; later transfers only by a designated body.

### K-2. Member access is not workspace access [AC]

- D-104: workspace access (the organisation's working area for staff and officers) is **never implied by membership**.
- D-152 / §35: **a member's forum access follows from belonging**.
- **These do not conflict once the two areas are distinct:**
  - the **member area** (own membership status, forums, newsletter preferences) is **participant access, derived from belonging and capacities**;
  - the **workspace** (administration, registrar and editor tools) needs a **workspace access grant** (D-104) **and**, for authority-sensitive acts, an AuthorityCheck answer.
- **Holding an office does not grant workspace access, and workspace access does not grant authority.**
- No decision needed; this is how the existing decisions read together.

### K-3. Pending applicants have no belonging [AC]

- Belonging is defined for effective members (D-114) and for unit capacities such as workers and guests (D-130). **A pending applicant has neither.**
- D-153's "pending applicants read where they belong" therefore means: **they read nothing until admitted**, unless the organisation gives them a capacity in a unit.
- Workers and guests read the forums of the unit their capacity names and those above it. **No decision needed.**

### K-4. The launch sequence has to be part of release 1 [AC]

D-148 … D-150 mean that before an organisation's members can be admitted, posts written or newsletters sent:
1. the organisation submits its statutes and the decision naming a registrar (D-148);
2. the registrar records the current committees and holders from evidence;
3. a committee, at a recorded sitting, designates:
   - who admits (D-149);
   - the editor (D-58);
   - the moderators (D-150);
   - who may create units and place members (K-1).

**Release 1 therefore includes the registrar's recording screens and the recording of sittings and designations.** That is a real build cost, not a contradiction. The guidance for organisations should present it as **"set up your committees once"**.

### Other checks (no finding)

| Check | Result |
|---|---|
| Organisation §50 depends on §51.3 (descent) | With no area rules (D-147), descent reduces to: top unit → placement (floor) → exception. **Consistent.** §49–§51 are accepted together; release 1 builds §49–§50 and this reduced descent |
| Suspension | Not supplied to Organisation or Deliberation; D-153 accounts for it. Governance reads it through C3 for office eligibility. **Consistent** |
| Identity | Members need a **claimed account** (D-113, claim code) to use the member area; **newsletters need none** (they use the profile e-mail). **Release dependency** on Identity participant access (I-TDD-02) |
| Mandates | Editor and moderator offices produce mandates for Authorization (screens only); **every act re-checked through AuthorityCheck**. Consistent with §41.6 |
| Tenancy | Every operation is in one working organisation (INV-ORG-SCOPE-1). **Owner HD-8 still open; does not block design** |
| Geography | None. Audiences, forums and authority are scoped by units; D-106 §6 holds; the Geography → Organisation contract is dormant until area rules (§16.6) |
| Election (outside the package) | The legacy committee code frozen by D-144 is also used by existing election flows (e.g. `EnsureCommitteeMember`). **An implementation-planning risk, not a design conflict**: the freeze must keep those flows working |

## 4. Before implementation can be authorised

1. The two act kinds (D-154) added to the thin slice.
2. **IR-OTD-04**, then the Principal's acceptance of §49–§51.
3. **Acceptance** of the Governance thin slice and the Newsletter and Forum design.
4. **Identity participant access** (claim code) designed, in its own session.
5. **[LQ]** counsel on newsletter consent (D-151).

**Note (2026-11-11, P-4, D-172):** K-1's placement at admission applies **only where the rules or a designation give the admitting body placement competence**; otherwise the member stays in the top unit until a designated body places them.

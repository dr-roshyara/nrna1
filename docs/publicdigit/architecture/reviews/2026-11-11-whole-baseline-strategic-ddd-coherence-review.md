# PublicDigit Whole-Baseline Strategic DDD Coherence Review

| | |
|---|---|
| **Status** | **Review.** It records no decision and changes no source document. Written by Claude (author of several reviewed designs), **not independent** |
| **Commission** | "PBDIGIT — Next Step: Whole-Baseline Strategic DDD Coherence Review", Principal, 2026-11-11 |
| **Baseline examined** | Decision record through **D-193** (`docs/publicdigit/reviews/2026-11-08-organisation-strategic-discovery-record-structure-mandates-conventions.md`, abbreviated **DR**). Its previous whole-baseline check stopped at D-172 (`2026-11-11-post-correction-strategic-baseline-coherence-review.md`, **PCR**) |
| **Sources read** | DR; strategic baseline acceptance act (D-173); the integrated release-1 domain design (**IRD**, proposed, assembling accepted parts); Governance thin slice (**GTS**, accepted D-157, with notes through D-193); Newsletter and Forum design (**NFD**, accepted D-158); C1/C2 specification (D-162); Renew / Suspend / Restore design (D-177); multi-database design (D-178); Platform revision 11 (D-193); Reference Geography ratification act (D-93); I-TDD-02 scope (**IS**); M-TDD-01 (proposed); XD-3 ARB act (D-75) |

**Labels:** [BF] fact · [AD] accepted decision · [AC] architectural conclusion · [TD] tactical · [TU] technical / proof · [P] proposal · [OQ] Principal question · [EXT] owned elsewhere · [LQ] legal · UNKNOWN.

## 1. Executive summary (business language)

**Yes, we have a coherent blueprint for the whole system.**
- Every business fact the minimum package needs has exactly one owner.
- Every hand-over between contexts has a defined meaning and a defined failure.
- Nothing in the decisions made since D-172 (the Platform work, D-174 … D-193) contradicts the earlier baseline. Those decisions add the tenancy layer underneath it.

**Two things are not yet settled:**
1. **How a person's login is linked to their record in an organisation (Identity, I-TDD-02).** Every human act in release 1 needs this link:
   - a member reading or posting in a forum;
   - an officer admitting, renewing or suspending;
   - the founding registrar's first entry;
   - a platform contact approving an emergency grant.

   The design exists only as a proposed scope. **It is the true prerequisite for the minimum package.**
2. **Who may end a membership** (resignation, expulsion, death, other). Release 1 includes ending a membership, but **Governance has no rule for who may record it.** Under D-167, silence means nobody, so **no membership could ever be ended.** This is the one genuine business decision in this review.

**What to design next, in order:**
1. Identity I-TDD-02;
2. a small Governance completion: decision-log rules, end-of-membership and claim-issuing request kinds;
3. a revision of the Membership admission and end design (M-TDD-01).

Newsletter and Forum need no new design, only a conformance check against the per-organisation database.

## 2. Current accepted baseline: what is settled

| Area | Settled by | Status |
|---|---|---|
| Strategic baseline (contexts, ownership, authority never by default) | D-173 (through D-172), D-167, D-168, D-181 | [AD] |
| Minimum package and release-1 scope | D-146, D-147, D-160, D-163 | [AD] |
| Organisation: units, placement by act, registrar onboarding | D-148, D-154 / D-172, D-156 (§49–§50; §51 later), D-159 | [AD] |
| Membership kernel, C1/C2, standing | RAT; D-161, D-162, D-177 (Renew / Suspend / Restore) | [AD] |
| Governance thin slice and AuthorityCheck | D-157 + correction notes 1–4; additions K-1, D-163, D-190 | [AD] |
| Newsletter and Forum | D-150 … D-153, D-158 (amended by D-161, D-168) | [AD]; [LQ] D-151 |
| Reference Geography (dormant in release 1) | D-93 (RAT), D-147 | [AD] |
| Identity principles | D-75 (XD-3 RAT), D-110, D-111, D-113 | [AD]; **tactical design not accepted** |
| Physical tenancy: central database plus one per organisation | D-164, D-178 | [AD] |
| Staff access only by the organisation's grant | D-165 → D-166, D-170 | [AD] |
| Platform (tenancy) tactical design | **D-193** (revision 11, with conditions X-2, X-3; 53 proof gates) | [AD] |

## 3. Bounded-context responsibility and ownership

| Context | Owns | Decides | Never owns | Depends on | Assessment |
|---|---|---|---|---|---|
| **Identity** (central) | Person, Account, login, sessions, `PersonRef` issuance, claims, Account ↔ `PersonRef` bindings (routing only) | whether a claim binds an Account | membership, authority, access (D-164) | Governance (who may issue claims) | **coherent; tactical design missing** (§6 G-1) |
| **Platform (tenancy)** (central) | registry, locator, status / `schemaState`, service-operation grant, pending case grants, contacts metadata (D-191) | routing; admission of staff operations under grants | business facts; authority | Identity (X-2), Governance (D-190, X-3) | coherent (D-193) |
| **Reference Geography** (central) | official references | — | belonging, authority | — | coherent; dormant |
| **Organisation** (per organisation) | profiles and contacts (D-101), units and hierarchy, belonging, placement, workspace access | placement records (on authority) | membership truth, authority | Governance (authority), Membership (C1/C2), Identity (`PersonRef`) | coherent |
| **Membership** | periods, terms, Admit / Renew / Suspend / Restore / **End** / corrections, `inForce`, `suspended` | applies facts on authority | belonging, rights | Governance (AuthorityCheck) | **gap: End has no authority rule** (§6 G-2) |
| **Governance** | rules on file, committees, offices, Resolutions, decision log, AuthorityCheck | who is competent | structure, membership facts | Organisation (ancestors) | coherent; **decision-log detail proposed only** (X-3) |
| **Deliberation** (forums) | forums, posts, moderation acts | read / post on Organisation's answer + D-161 / D-168 | belonging, authority | Organisation, Governance, **Identity (who is the reader)** | coherent |
| **Communication** (newsletters) | series, issues, opt-outs, dispatch records | audience at one τ | contacts (Organisation's), authority | Organisation, Governance | coherent; [LQ] D-151 |
| Election, Finance | — | — | — | — | outside release 1 |

**Findings:**
- **No double owner; no missing owner** for any fact the minimum package uses [AC] (IRD §3, re-checked through D-193).
- **Platform and Identity acquire no organisational authority** [AD] (D-164: "the central association never establishes membership, … access or authority"; D-193).
- **Membership is not a shortcut to rights:** Communication and Deliberation never read Membership; they use Organisation's answer and derive `InForceUnrestricted` (IRD §4; D-161, D-168) [AD].
- **Newsletter contacts come from the organisation's person profile, never the login address** (NFD line 23; D-101; INV-ID-3) [AD].

## 4. Context relationships and collaboration

**What changed since IRD §4:** D-164 places Organisation, Membership, Governance, Deliberation and Communication **in the same per-organisation database**. Their contracts are unchanged in meaning; they no longer cross a database. **Only Identity, Platform and Reference Geography are across the boundary** [AC] (D-178).

| Relationship | Kind | Authoritative answer at | Unavailable / undetermined |
|---|---|---|---|
| Membership → Organisation (C1/C2) | published facts, read at τ | `closedThrough` | refused, never partial (D-162) |
| Organisation → Deliberation / Communication | query at τ (+ derived standing) | request time / send time | no read or post / **no send** (D-73) |
| Governance → all (AuthorityCheck) | query (pull) or pushed decision | `evaluatedFor` / `checkedAt` | refused; **Undetermined ≠ No** (D-181) |
| **Identity → every context (who is acting)** | **binding Account → `PersonRef` in this organisation** | **at each request** | **refused (no binding, no act)** [AC] |
| Platform → all (routing) | locator, status | per request | fail closed (ISO rules) |
| Identity → Platform (X-2) | `PersonRef` → bound Account | at promotion | fail closed (D-193) |

**Missing contract** [AC]: the **Identity → context binding** used to name the actor of every act. IRD flow A step 7 points to I-TDD-02, and GTS's AuthorityCheck takes an `actor PersonRef`, but **no accepted design says how a signed-in Account becomes that `PersonRef` inside one organisation.** This is the same mechanism as X-2, in the other direction.

## 5. Cross-context invariants and consistency

| # | Invariant | Authority | Assessment |
|---|---|---|---|
| I-1 | One τ per cross-context operation; complete or refused | D-73 | holds; per-organisation database makes it local except the Identity, Platform and Geography reads |
| I-2 | Business date ≠ knowledge time in every fact and answer | D-12; IRD §8 | holds; **knowledge time in Governance rests on decision-log rules GD-11, GD-12, which are only proposed** (X-3) |
| I-3 | Authority never inferred | D-1a, D-167, D-181 | holds; **exposes G-2 (End)** |
| I-4 | Organisation data stays in its database; central holds only permitted data | D-164, D-191 | holds |
| I-5 | Staff see content only by case grant | D-166, D-170, D-193 | holds |
| I-6 | Belonging counts only while covered; rights need `InForceUnrestricted` (overridable default) | D-161, D-168 | holds |
| I-7 | The map never decides belonging or forum access | D-79, D-152, D-147 | holds: **forum "levels" are the organisation's own units** (D-152), not map entries |
| I-8 | Login address is a credential, never a contact | INV-ID-3, D-179 (narrow exception) | holds |
| I-9 | Append-only; corrections are new records | IRD §8 | holds |

**Temporal consistency:**
- **Lapse is visible:** since CR-02 (D-161), C1/C2 carry `inForce`, so lapsed members lose posting and newsletters.
- **The earlier C1/C2 text** saying lapse is invisible ("lapsed members still … write", D-162 spec lines 23–25) **predates CR-02 and is superseded by it** [AC].
- **D-153's** "suspension is not applied in release 1" is superseded by D-161 in the same way [AD].

## 6. Strategic gaps and unresolved business questions

| # | Gap | Business impact | Contexts | Level | Resolvable from accepted decisions? |
|---|---|---|---|---|---|
| **G-1** | **No accepted Identity design for claims and bindings (I-TDD-02)**: Account ↔ `PersonRef` in both directions, the registrar's bootstrap claim, the officer who issues claims | **No human act in release 1 can name its actor**; members cannot reach forums; Platform's X-2 stays closed | Identity → all | tactical (strategy settled by D-75, D-113) | **Yes, strategically** [AC]. The scope document (IS) exists, but **its acceptance (IS §2 E-4) is not recorded** (UNKNOWN). **IS does not yet list X-2** (`PersonRef` → Account for Platform) |
| **G-2** | **No authority rule for ending a membership.** GTS lists act kinds for admit, renew, suspend and restore, **but none for End.** Kernel interim: "End is denied for every actor" (M-TDD-01 line 78) | Resignations, expulsions and deaths cannot be recorded; members who leave stay members | Membership, Governance | **strategic (who may act) + tactical (act kind)** | **No.** D-167 makes silence mean "nobody", so a business rule is needed. **[OQ] P-1 below.** Resignation is the member's own act, not a body's competence; [LQ]: in Germany the right to leave a party at any time is statutory (PartG §10) — counsel to confirm |
| **G-3** | Governance decision-log rules (GD-11, GD-12) and per-part evidence persistence only proposed | Authority "as known at τ" cannot be built reliably | Governance; all consumers | tactical | **Yes** [AC]: complete within Governance (X-3); no business choice found |
| **G-4** | `issue-claim` / `revoke-claim` are named only as examples (GTS correction note 4), not defined act kinds | I-TDD-02 G-7 cannot ask Governance | Governance, Identity | tactical | **Yes, by precedent**: same form as D-163 / D-190; needs a Principal authorisation like D-190 when I-TDD-02 asks for it |
| **G-5** | Membership admission and end design (M-TDD-01) is **proposed**, written before D-161 … D-193 | Admission flow (IRD flow A) has no accepted tactical design | Membership | tactical | **Yes**: revise against accepted decisions |
| **G-6** | Context map document lags the decision record (PCR P-7) | Readers may trust an outdated map | all | documentation | **Yes**; the decision record governs |
| G-7 | Emergency route in release 1 | product scope only | Platform | PO decision, **not needed now** (D-193) | — |
| G-8 | [LQ] newsletter legal basis (D-151); compelled disclosure (D-166); retention; `PersonRef` classification | launch, not design | — | legal | counsel |

**Checked and found coherent (no further work):**
- Forum access by unit level (D-152);
- newsletter audience at one τ (D-73);
- Renew / Suspend / Restore authority (D-163);
- Platform boundaries (D-193);
- Geography dormant (D-147).

## 7. External context dependencies

| Dependency | Owner | Needed by | Status |
|---|---|---|---|
| I-TDD-02 (claims, bindings; incl. X-2) | Identity | every context | scope proposed; design not written |
| X-3 decision log (GD-11, GD-12), per-part evidence | Governance | all authority answers | proposed |
| End-of-membership act kind(s) | Governance (after P-1) | Membership | missing |
| `issue-claim`, `revoke-claim` act kinds | Governance | Identity | named only |
| Legal answers | counsel | launch | open |

## 8. Minimum package readiness

| Capability | Strategic | Tactical | Blocked by |
|---|---|---|---|
| Membership: admit | ready | M-TDD-01 proposed | G-1 (actor), G-5 |
| Membership: renew / suspend / restore | ready | **accepted** (D-177) | G-1 (actor) |
| Membership: end | **not ready** | missing | **G-2 (P-1)**, G-1 |
| Organisation: units, placement, registrar onboarding | ready | **accepted** (D-156) | G-1 (registrar's bootstrap claim) |
| Forum: read / post / moderate | ready | **accepted** (D-158) | **G-1** (who is reading) |
| Newsletter: send to unit audience | ready | **accepted** (D-158) | G-1 (editor as actor only); [LQ] |
| Governance thin slice | ready | **accepted** (D-157) | G-3 (decision-log detail) |
| Platform: onboarding, routing | ready | **accepted** (D-193) | X-2 (= G-1), X-3 (= G-3) |
| Geography | ready (dormant) | ratified (D-93) | — (later release) |

## 9. Recommended next tactical subject: Identity I-TDD-02

**Why first:** it is the single dependency on every row of §8. Without it, no act has an actor, no member can open a forum, and the founding registrar cannot begin.

- **Business capability enabled:**
  - a person registered once signs in and acts inside an organisation that recorded them;
  - officers act with a known identity;
  - Platform can confirm its contacts.
- **Already settled:**
  - one Person, one Account (D-110, XD-3);
  - an opaque reference at import, and a Person only at claim (D-113);
  - no email matching;
  - account creation is not admission;
  - bindings are routing only (D-164).
- **Contracts needed first:**
  - Governance `issue-claim` / `revoke-claim` (G-4; a D-190-style authorisation);
  - the AuthorityCheck shape (accepted).
- **Include (IS G-1 … G-16 plus three additions):**
  1. **resolution in both directions:**
     - Account → `PersonRef` in this organisation, for every request;
     - `PersonRef` → bound Account, for Platform (X-2);
  2. **the registrar's bootstrap claim** (D-178), as the one exception;
  3. **behaviour when Identity is unreachable:** refuse.
- **Exclude:**
  - multi-organisation discovery (P-I2);
  - recovery (P-I3);
  - merges (rule only);
  - Election;
  - Keycloak;
  - user-interface implementation.

**Then:**
- **Governance completion** (small): GD-11, GD-12, per-part evidence (X-3); act kinds for End (after P-1) and claims.
- **M-TDD-01 revision:** application, admit with explicit term, end and corrections, aligned with D-161 … D-193.
- **Newsletter and Forum:** a short conformance note against D-164 / D-178 (same database); **no redesign**.

## 10. Principal decisions required

| # | Decision | Why it is yours |
|---|---|---|
| **P-1** | **Who may record each kind of membership end, where the organisation's rules are silent:** resignation, expulsion, death, other | D-167 makes silence mean "nobody"; without a rule no membership can end. It is a business and constitutional choice, not a technical one |
| **P-2** | **Accept the I-TDD-02 scope (IS), amended to include X-2 (`PersonRef` → Account for Platform), the registrar's bootstrap claim and refusal when Identity is unavailable**, so I-TDD-02 can be written | IS §2 E-4 requires your acceptance; the amendment follows from D-178 and D-193 |

**Not needed now:**
- the emergency route in release 1 (G-7);
- the legal questions (counsel).

## 11. Out of scope for the next step

- Platform revision 11 (accepted; no revision 12);
- implementation, tests, migrations;
- Election, Voting, Finance;
- area rules (§51) and the Reference Geography link (later release);
- multi-organisation discovery and account recovery;
- project administration.

**Verdict (author's, not independent): BASELINE COHERENT, WITH ONE STRATEGIC GAP (G-2) AND ONE BLOCKING TACTICAL DEPENDENCY (G-1).**

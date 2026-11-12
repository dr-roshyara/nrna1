# PublicDigit integrated baseline architecture and DDD coherence review

| | |
|---|---|
| **Status** | **Review.** Records no ruling; changes no decision, design or code. Written by Claude, **not independent** |
| **Commission** | "Principal Instruction — Integrated PublicDigit Baseline Architecture and DDD Coherence Review", accepted by the Principal 2026-11-10 |
| **Consumes, does not repeat** | Whole-platform consolidation `2026-11-10-strategic-baseline-consolidation-review.md` (parallel session; its F-1/F-4 since decided as D-141 … D-143); context map §16 and §16.6; `2026-11-10-minimum-package-coherence-check.md`; accepted designs D-156 … D-158; decision record D-1 … D-158 |

**Labels:** [AD] · [BF] · [PK] · [AC] · [TD] · [TU] · [WD] · [P] · [H] · [OQ] · [LQ] · UNKNOWN.

## 0. Disagreements with the commission (stated first)

1. **"Integrated review not yet done" is only half true.**
   - The **whole-platform** ownership and relationship review exists (consolidation review §3–§10, with its open items since decided: D-141 … D-143).
   - The **minimum-package** coherence check exists.
   - **What was missing is joining them with the release-1 build reality.** This review does that and **repeats neither** (ES-005.4).
2. **"Do not independently invent a time model per context": none was invented.** One model exists and is binding:
   - **D-73** (ARB): one shared τ per cross-context operation, complete or refused;
   - **CR-01 §2** (RAT): `asOf`, τ, `closedThrough`;
   - **Geography** `positionAt(τ)` within `closedThroughTime` (RAT);
   - Organisation §49.8 and Governance's DecisionLog apply it.

   §8 shows the one place it is under-specified.
3. **"Membership provides membership standing to Forum" contradicts accepted decisions.**
   - Deliberation is **not** a ratified Membership partner (§36; CR-01 partners: Organisation C1/C2, Governance C3, Election C4).
   - Forum reads belonging through Organisation. **Standing (suspension) is not supplied in release 1 (D-153).**
   - Supplying it needs a Membership change request (ARB act).
4. **Root closure is not an "interim technical limitation" only.**
   - **D-120 settles the business rule:** the root cannot close while any coverage exists; dissolving an organisation is a separate act.
   - The missing C2 adapter only prevents ending the root of an **empty** organisation, a case release 1 does not need.
5. **Administration.** The commission says not to update session logs; **CLAUDE.md makes the session log mandatory.** I keep it to one line.

## 1. Executive summary

PublicDigit is becoming **a platform where each organisation runs its own constitutional life**:
- its structure;
- its members;
- its committees and their authority;
- its communication and deliberation;
- (later) its elections.

**Each organisation's data stays its own; a person registers once.**

**The baseline is close to coherent:**
- **every business fact has one owner** (§6);
- **authority is never inferred** (§5);
- **one time model applies everywhere** (§8).

**Four things stand between the accepted designs and safe implementation** (§11):
1. **Membership's ratified C1/C2 contract is not implemented in code** [BF]. Release 1 depends on it for belonging, audiences and forum access.
2. **Organisation structure cannot operate without Governance's AuthorityCheck.** Slices 1 and 3 must be one increment.
3. ~~How a joining organisation's existing units are recorded~~ **decided: D-159** (registrar records them from evidence).
4. **Identity's claim code**, together with the existing e-mail-matching defect (PBDIGIT-341), blocks the member area.

## 2. Business capability map

| Capability | Release 1 | Owner |
|---|---|---|
| Register once, sign in, claim one's record in an organisation | **yes** | Identity |
| Organisation joins; founding documents; registrar (D-148) | **yes** | Governance (authority), Organisation (registry) |
| Organisation structure (units over time) | **yes** | Organisation |
| Membership: apply, admit, end; standing | **yes**; **standing (in force, suspended) reaches forum and newsletter through C1/C2 (CR-02, D-161)**: good standing to write and receive | Membership (authority via Governance) |
| Member placement in units | **yes** (by act, top-unit default) | Organisation |
| Committees, offices, terms, decisions, authority checks | **yes** (thin slice) | Governance |
| Newsletter | **yes** (legal check pending) | Communication |
| Member forum | **yes** | Deliberation |
| Workspace access for staff and officers | **yes** | Organisation (D-104), enforced by Authorization |
| Area rules from the official map | later (gated, D-156) | Organisation + Reference Geography |
| Elections and voting | outside the package (existing product) | Election, Voting |
| Fees and payments | later | Finance |
| Challenges and rulings | manual in release 1 | Adjudication |

## 3. Bounded-context map

| Context | Owns | Does not own | Status |
|---|---|---|---|
| **Identity** | Person, Account (0..1), login, claims, `PersonRef` issue and resolution | profiles, affiliations, membership, authority | ARB D-75; D-110, D-111, D-113 |
| **Organisation** | registry, person profile and contacts, affiliations and capacities, **units and belonging**, workspace access | membership truth, authority, persons, official geography | ACC D-106; tactical **D-156** |
| **Membership** | periods, facts, lifecycle, types, standing | belonging, entitlement, authority | RAT D-96; CR-01 |
| **Governance** | rules on file, committees, offices, terms, decisions, competence, AuthorityCheck, mandates | membership, structure facts, technical permissions | ownership D-144; thin slice **D-157**; full model WD (D-10, rounds 1–6); round 7 P |
| **Communication** | series, issues, dispatch, opt-outs, notices | audiences' facts, editor authority | **D-158** |
| **Deliberation** | forums, posts, supports, moderation acts | belonging, moderator authority, motions after submission | **D-158**; D-50 … D-56 |
| **Election / Voting** | electorate, entitlement, issuance / ballots, secrecy | membership, belonging | RAT boundary; FRZ; outside the package |
| **Adjudication** | challenges, determinations | remedies' application (owners apply) | ARB D-67 |
| **Reference Geography** | official references, publication, history | belonging, organisation areas | RAT D-93; **dormant in release 1** |
| **Finance** | payments, settlement | standing, eligibility | RAT outline; FeePaid direction OPEN (ARB act) |
| *Authorization* (capability) | technical enforcement | any business truth | FRZ BDR-07; scope OPEN (XD-4) |
| *Platform / tenancy* (capability) | working organisation, scoping | organisational authority | ACC E-11; owner OPEN (HD-8) |

**[AC] No new context is needed.**

## 4. Context relationships (release 1; the full platform list is in the consolidation review §4)

| Upstream → downstream | Fact | Contract | Status |
|---|---|---|---|
| Identity → all | `PersonRef`; claim and binding | claim code (D-113) | ARB / WD; **I-TDD-02 pending** |
| Membership → Organisation | coverage C1 (events), **C2 (`CoverageKnownAt` at τ)** | CR-01 | **RAT, not implemented** [BF] |
| Membership → Governance | C3 (`MembershipFactsAsAt`) | CR-01 | RAT, not implemented |
| Governance → Membership, Organisation, Communication, Deliberation | **AuthorityCheck** answer | thin slice §6.2 | **ACC D-157**; extends the ratified AuthorityCheck port |
| Governance → Organisation | structural change sets, placement acts (decided) | D-29 R-1 | WD; act kinds D-154 |
| Organisation → Governance, Communication, Deliberation | `StructureAsAt`, `MembersOfScopeAsAt`, `BelongingWithAncestorsAsAt`, `UnitEnded` | §49, §50; D-158 | ACC / P (queries named in D-158 design) |
| Organisation → Authorization | workspace grants | D-104 | RUL; contract UNKNOWN |
| Governance → Authorization | mandates | D-72 | ARB |
| Deliberation → Communication | forum notifications | D-158 | ACC |

**Failure rule everywhere:** unavailable, unknown or incomplete → **refused, never assumed** (D-73). Contested facts stay effective until determination (D-71).

## 5. Authority model

1. **Source:** the organisation's **written rules on file** (D-97). No authority from platform configuration, admin roles, names, levels or the map (D-1a, D-136, PO-ORG-MEM-01 A).
2. **Start:** a founding registrar named by the organisation's own decision records the current committees and holders from evidence (D-148).
3. **Exercise:** committees decide at recorded sittings. Offices, including editor and moderator, are held for terms (D-144, D-157).
4. **Silence in the rules:**
   - nobody admits (D-149);
   - nobody moderates, and forums are read-only (D-150);
   - nobody changes structure (D-154);
   - **until a committee designates someone.**
5. **Check:** every authority-sensitive act asks **AuthorityCheck** at the moment of the act. The consumer stores the answer as its basis. "Asserted" answers must carry their four elements (thin slice §3).
6. **Legacy:** `users.is_committee_member` (a **global** flag gating election administration, `EnsureCommitteeMember`) [BF] is **an Election-era technical permission, not a Governance fact.**
   - **It is never read by the new contexts, and Governance never writes it.**
   - Election keeps using it until Election is redesigned (D-144 freeze).
   - **One authority for the new contexts (Governance); the legacy flag governs only legacy election screens.**

## 6. Data ownership and isolation

- **One owner per fact** (§3).
- **Per-organisation isolation:**
  - profiles, affiliations, belonging and Governance records never cross organisations (D-101, D-111);
  - Identity holds only the bare Person and Account.
- **No automatic matching by name or e-mail (D-113).** The **existing code does match by e-mail (PBDIGIT-341, open)** [BF]. That is a release-1 blocker for the member area, not a design question.
- **Contacts:** the organisation's profile (D-101). **The login e-mail is a credential only** (§31).
- **Legacy geography in code:**
  - `geo_administrative_units` (organisation-scoped) and `users.residence_geo_unit_id` [BF] **contradict S-8 / D-79** if used for belonging.
  - **New contexts never read them.**
  - Their classification (organisation areas, D-82, versus copies of official references) belongs to the later Geography release.

## 7. Lifecycle coherence (one example: a unit ends)

| Consequence | Owner | Trigger | Rule |
|---|---|---|---|
| Unit ended, successor recorded | Organisation | Governance decision → change set (D-29) | §49.7, S-7 |
| Members' belonging re-derived, `PlacementReviewNeeded` | Organisation | same change set | C-2, D-121 |
| Committee ends (unless the act says otherwise); terms, delegations, mandates end | Governance | `UnitEnded` | **D-145** |
| Forum archived read-only | Deliberation | `UnitEnded` | D-158 §3.4 |
| Series scoped to the unit ends | Communication | `UnitEnded` (via the editor office ending) | D-158 §2.2 |
| Closure impact report to the deciding body | Organisation (+ Governance items) | change set | M-6, round 7 §52.6 |

- **[AC] No context writes another's facts.** Consequences are applied by each owner on the event: eventual, idempotent.
- **The lag is harmless** because every authority-sensitive act re-checks at use (G-I4). **Root closure:** D-120.
- **Other lifecycles:**
  - **membership end:** coverage ends → belonging stops counting (B-2); **office continues until the committee decides** (D-24); forum write ends with coverage (D-153);
  - **term end:** AuthorityCheck answers "not authorised" at once.

## 8. Temporal coherence

| Time | Owner | Used as |
|---|---|---|
| **Effective date d** (business world) | the context owning the fact | `…AsAt(d)` |
| **Record time / knowledge time τ** | each context for its own records (database clock, never decreasing; GD-12) | "as known at τ" |
| **`closedThrough`** | each source context (Membership CR-01; Organisation §49.8; Geography `closedThroughTime`) | queries beyond it refused or closed first |
| **One τ per cross-context operation** | the operation's coordinator | D-73: complete or refused |

**Consumers:**
- **a newsletter send** fixes (d = τ = send time) for both audience and authority;
- **forum access** uses (d = τ = request time);
- **a structural change** checks with its own τ.

**Under-specified:**
- **[TU]** Release-1 consumers asking "now" must still pass one τ to every source and refuse if any source's `closedThrough` is behind.
  - For Membership this requires **C2's `closedThrough` to exist**, which ties back to the unimplemented C1/C2 (finding 1).
  - **No new business rule; a proof obligation.**
- **Corrections:** new records with their own τ; retroactive business changes follow §49.7 (decision-route), never silent edits.

## 9. Minimum-package coherence

**Coherent as designed** (coherence check K-1 … K-4, D-154). **It cannot function until:**
- Membership C1/C2 exist in code (audiences, forum, belonging);
- AuthorityCheck exists (every act);
- the onboarding path exists (D-148 + §12);
- the claim code exists (member area).

**None of these is a design contradiction; all are build dependencies.**

## 10. Accepted versus unresolved

| Status | Items |
|---|---|
| **RAT / ARB** | Membership + CR-01; Geography (D-93); Identity XD-3 (D-75); D-72; D-73 |
| **ACC** | Organisation strategic (D-106); **D-156** (§51 build gated); **D-157**; **D-158** |
| **Principal rulings** | D-114 … D-155 |
| **WD** | D-115; Governance full model (D-10); D-102, D-111 responsibility lists; R-5 / T-8 contracts |
| **P** | Governance round 7; queries named in D-158; Organisation slice candidate |
| **OPEN (ARB)** | HD-8 tenancy owner; XD-4 Authorization scope; FeePaid direction; Membership CR for suspension (if wanted) |
| **[LQ]** | newsletter legal basis (D-151) |
| **UNKNOWN** | each organisation's statute content (O-8 … O-10) |
| **[OQ]** | Geography's defective-publication question (not needed for release 1) |

## 11. Organisation slice assessment (PBDIGIT-342 candidate)

| Issue | Classification | Assessment |
|---|---|---|
| Tree as aggregate | [AC], settled by §49.7 (ACC D-156) | **Justified:** S-0 … S-3 span units |
| Invariants checked from the effective date onwards | [AD] D-156 | Sound |
| Change sets all-or-nothing, idempotent | [AD] D-30 (WD), §49.7 | Sound |
| **Authority** | [AC] | **The slice must not operate without a real AuthorityCheck.** Test doubles are tests only; a production build without Governance refuses every change (fail-closed). **Recommendation: build structure and the Governance core as one increment** |
| Root ending and C2 | [AD] D-120; [TU] adapter | Business rule settled; **C2 implementation is a separate prerequisite** (not for slice 1, required for belonging) |
| Append-only, `StructureAsAt(d, τ)` | [AD] §49.8; [TU] clock | Sound; record-time source is a technical obligation |
| Isolation | [AD] D-111 | Two-organisation tests required |
| No address or Geography | [AD] S-8, D-147 | Architecture test |
| Coexistence with election flows | [AC] | No shared tables touched; legacy flag untouched |
| **Existing units at onboarding** | **[AD] D-159** | The slice needs a second basis kind, **"recorded from evidence" by the registrar**, beside decided change sets (S-7) |

**Verdict: architecturally ready as a candidate**, with a revised build order:
1. **Structure + Governance core together** (authority and structure foundation);
2. **Membership CR-01 C1/C2 implementation** (new, previously missing);
3. belonging;
4. onboarding;
5. newsletter;
6. forum.

## 12. Genuine Principal question (one) — **DECIDED: D-159** (the registrar records existing units from evidence; only later changes need a designated body)

**When an organisation joins with an existing structure (provinces, districts already in place), how is that structure recorded?**
- D-148 lets the registrar record existing **committees** from evidence.
- D-154 makes **changes** to structure wait for a designation.
- **Neither says how units that already exist are recorded.** Committees cannot be attached to units that are not recorded.

## 13. Recommended next DDD step

**The smallest useful next step is tactical: specify the Membership CR-01 C1/C2 implementation boundary** (what Organisation receives, `closedThrough`, refusal semantics). It is:
- the one ratified contract release 1 depends on that exists only on paper;
- the place where the shared time model (§8) becomes concrete.

**After that**, the first build increment (structure + Governance core) can be planned.

---

## Addendum A (2026-11-10): eight matters under independent scrutiny

*Commission: "Principal Instruction — Integrated PublicDigit Baseline Architecture and Strategic DDD Review" (second version).*

**Disagreements first:**
- **(1) Its premises predate the acceptance acts.**
  - The Governance thin slice and the Newsletter and Forum design are **accepted (D-157, D-158)**, not proposals.
  - Organisation §49–§51 is **accepted (D-156)**, with only the §51 area-rules **build** gated on an independent review.
  - D-155 … D-160 also exist.
- **(2) The integrated review it requests is this document's §1–§13.** This addendum answers only its eight special matters, so nothing is duplicated (ES-005.4).
- **None of the eight needs a new Principal decision.** One (A-2) is a real alignment gap with a ratified text, now made explicit.

| # | Matter | Evidence | Finding | Resolution |
|---|---|---|---|---|
| **A-1** | **Bootstrap trust model** (D-148, D-159) | D-148, D-159; D-1a; G-6; thin slice §3 | **No document uses "verified" for the bootstrap** [BF]: D-148 says "the platform only checks that the documents exist". Three things are distinct and must stay so: **(a)** the organisation **claims** a decision named the registrar; **(b)** it **supplies evidence** (document, fingerprint); **(c)** PublicDigit **records** what follows, marked "recorded from evidence". **PublicDigit never asserts the claim is true or legally valid** | [AC] **Trust model, stated:** PublicDigit is a **record keeper, not a validator** of founding authority. **Assurance comes from visibility and challenge** (members see the basis; D-53, D-67), not from platform verification. **Required wording in every surface:** "recorded from evidence supplied by the organisation", never "verified". [TD] The founding records carry Basis `RecordedFact(kind = founding, evidence fingerprints, registrar, submitting account)` |
| **A-2** | **AuthorityCheck: ratified versus extended** | Ratified §8 line 156: "**Governance: the AuthorityCheck port; Membership stores evidence only**"; ratified §72: authority evidence = **{actor, capacity, ruleRef, policyVersion, result, checkedAt}**; D-157 (accepted) extends it | **Ratified:** **one** consumer (Membership) and **that evidence shape**. **Accepted, not ratified:** the other consumers (Communication, Deliberation, Organisation) and the act kinds beyond Membership's (D-154, D-157). **Gap [BF]:** the thin slice's answer `Authorised(tenureRef, competence kind, citation, decisionRef)` **is not the ratified shape**; the ratified `result` field has no stated place for "asserted / worked out" | [TD] **One answer shape for all consumers = the ratified six fields**, mapped: actor = `PersonRef`; capacity = office (tenure ref); ruleRef = provision cited; policyVersion = rule-document version; result; checkedAt = τ. **The thin slice's extra items (competence kind, decision ref) are carried as an additional `basis` reference**, which leaves the ratified fields untouched. **[TU] Check whether the ratified `result` domain admits only allow / deny:** if so, "asserted" lives in `basis`, never in `result`. **Changing the ratified fields would need a Membership change request; this design does not** |
| **A-3** | **Unit forum versus committee forum** | D-56 ("committee forums stay confidential"); D-152 (Principal's words "highest committee forum is accessed by all members"); §35 (two forum dimensions) | **Settled by the sources:** D-56 makes committee forums confidential, so "highest committee forum" in D-152 can only mean the **top unit's forum** (recorded so in D-152) | [AC] **Glossary, binding for the baseline:** **unit forum** (one per unit; the top unit's is the **organisation-wide forum**; access by containment, D-152) · **committee forum** (one per committee; office holders only; confidential; optional in release 1). **"Highest committee forum" is not used again.** **Access:** members by coverage and belonging (D-152, D-153); **workers and guests read only, where a unit capacity attaches them** (D-130); **pending applicants: no forum** (no belonging, K-3) |
| **A-4** | **Newsletter series when its scope unit ends** | D-158 design §2.2 ("ends when its scope unit ends"); D-145 covers committees only | **The rule is an inference, not a constitutional decision** [AC]. Reason: **a series' audience is defined by its scope unit; an ended unit has no members** (they re-derive, C-2), so the series can no longer reach anyone as defined. Its editor office ends with the committee (D-145) | [AC] **The series is ended as a consequence of its scope, not by a rule about newsletters.** **The deciding act may re-scope it** to a successor unit (as V-48 / V-50 let the act decide for committees). **No Principal decision:** no constitutional choice is involved, only whether a series without an audience continues |
| **A-5** | **Suspension in release 1** | D-153; CR-01 partners; this spec §0 finding 4 (**lapse** is equally invisible) | **Release 1 cannot enforce "suspended (or lapsed) members may not post or receive"** [AD D-153; AC] | **Visible limitation, stated in the baseline:** release 1 bases forum write and newsletter audience on **coverage only**. **Suspended and lapsed members still belong, read, write and receive.** To enforce standing, a Membership change request (ARB act) adds standing to C1/C2, or names Deliberation as a C3 consumer. **Never enforced through a back channel** (e.g. reading Membership tables) |
| **A-6** | **Committee lifecycle versus unit lifecycle** | D-145; round 7 §52.6; §7 above | **Settled** [AD D-145]: a committee ends with its unit when nothing else is said. **Ownership:** Organisation ends the unit; **Governance** ends the committee on `UnitEnded`; neither writes the other's facts | [TD] Governance consumes `UnitEnded`; **the closing act's own instructions are applied first; D-145 fills silence**; idempotent per (unit, change set) |
| **A-7** | **Evidence for an asserted competence** | thin slice §3 (four elements); D-60; V-59; D-148 | **Defined** [AD D-157]: provision cited in a rules document on file · sitting with minutes evidence · recorder and their recording authority · the organ's attachment covering the scope. **Missing any → not authorised** | [AC] Unchanged. **Members see "asserted from the rules, not machine-checked"** with the citation. **Combined with A-1: authority in release 1 is evidential and challengeable, not machine-proved.** That is a property of the product to be stated to customers |
| **A-8** | **Exclusive positions and functional offices** | G-I1 (§40.2: one holder per **exclusive** position); D-58 ("designates **an** editor"); D-150 ("**a** moderator covering it"); V-46 | **Not every office is exclusive.** A chair is; moderators usually are several | [TD] **Exclusivity is an attribute of the Position, set by the decision that creates it** (the rules or the committee say how many holders). **G-I1 applies only to exclusive positions.** **Defaults when the creating decision is silent:** editor of a series **exclusive** (D-58's singular); forum moderator **non-exclusive** (several may cover one forum). These are tactical defaults, overridable by the creating decision; **no constitutional choice** |

**Effect on the baseline:**
- **A-2 changes the Governance thin slice's answer shape.** It now uses the ratified six fields, plus a `basis` reference. That is a correction that changes no meaning of D-157 (header note to be added).
- **A-1, A-3, A-4, A-5 and A-8 are now explicit in the baseline.**
- **No new decisions; nothing reopened.**

**Geography:** none of the eight touches Reference Geography. **It stays dormant in release 1** (D-147). The area rules remain designed (D-156), with their build gated.

**Note (2026-11-10, after the C1/C2 conformance review):** §2's Membership row corrected (K-6). **D-161 / CR-02 supersedes Addendum A-5:** release 1 now enforces good standing for forum write and newsletters; lapsed and suspended members read only.

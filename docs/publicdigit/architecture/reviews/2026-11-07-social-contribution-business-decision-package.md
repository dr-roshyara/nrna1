# Social Contribution: business decision package (decision-ready)

> **This package prepares decisions. It takes none.** It does not authorise Phase 1, strategic DDD design or implementation. It selects no identity or ownership option, approves no bounded context, and invents no verification authority. `PBDIGIT-335` remains `BLOCKED`.

| | |
|---|---|
| **Status** | DECISION PACKAGE, for Principal review. Not independently reviewed. |
| **Authority** | generated (Claude, Session A; Principal DDD Architect role). Not authoritative without human review. |
| **Commissioned by** | Principal Instruction "Refine the Social Contribution Business Decision Package" (2026-11-07, analysis only). The user then asked for the package to be written "as written document in proper folder" (2026-11-07). This file was the only change **at the time of writing**; no existing file, backlog item, plan, Git state or log was changed. It was committed as `5b1a9f81b` on the Principal's explicit instruction (2026-11-07). |
| **Backlog** | `PBDIGIT-335` (Phase 2, BLOCKED) · related `PBDIGIT-331` … `PBDIGIT-334` |
| **Date** | 2026-11-07 |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |

**Labels.**
- [F] verified fact (with source).
- [RD] ratified decision.
- [PD] Principal decision (with date and source).
- [D] other approved decision (with authority).
- [P] proposal.
- [H] hypothesis.
- [I] inference.
- [OQ] open question.

**Abbreviations.**
- PLAN = `2026-11-07-social-contribution-initiative-story-and-development-plan.md`
- PH2 = `2026-11-07-social-contribution-domain-discovery-phase-2.md` (the Phase 1 preparation package)
- Both are in this folder.

---

## 0. Points where this package departs from its instructions

1. **Writing this file.** The Principal instruction says "analysis only; do not edit files". The user then explicitly asked for the package to be written as a document. This **new** file is the only change. No existing file was edited, and the file was committed only on the Principal's later explicit instruction (`5b1a9f81b`).
2. **Backlog stories and strategic design.** The user's standing request to "write stories and start strategic design" was **not** followed. The Principal instruction forbids both, and Phase 1 evidence does not yet exist.
3. **Where the authority principle comes from.** `PBDIGIT-335` §5.E labels the principle "[D] … (PO-ORG-MEM-01)". That label was written by Session A. The Principal's adoption of the principle for Social Contribution comes from the Principal's instructions of 2026-11-07, not from PO-ORG-MEM-01. The sources are "Prepare Strategic DDD Discovery for Social Contribution" §6.E ("Preserve the principle: Technical platform access does not create organisational authority"), reaffirmed in "Complete Social Contribution Governance Readiness" §6 ("Remember: platform access does not create organisational authority"). §2 records the corrected traceability. Correcting the story's label is proposed as R-01b; it is not applied here.

---

## 1. Corrected source traceability

| Item | Finding |
|---|---|
| **Git** | [F] **At the time of writing:** branch `election-flow-updated`, HEAD `c4b05d54c`. Social Contribution commits in history: `43624fe1c`, `6beef7a6a`, `0fa62cd8f`, `edefe49e2`, `c4b05d54c`. None pushed. 38 unrelated uncommitted entries, untouched. |
| **Backlog statuses** | [F] `PBDIGIT-331` PROPOSED · `332` PROPOSED (awaiting PO) · `333` BLOCKED until 331's W1–W3 · `334` DONE · `335` BLOCKED. `331`–`333` unchanged since `edefe49e2`; `334` changed only in its status line (`c4b05d54c`). |
| **PO-ORG-MEM-01** (`docs/publicdigit/reviews/2026-11-03-organisation-po-org-mem-01-membership-ownership-decision-record.md`) | [D] "APPROVED by the Principal / PO, 3 November 2026"; ARB validation of consequences pending. **Scope: Membership.** "PublicDigit's technical ownership and platform access permissions do not constitute organisational authority." |
| **Ratified Membership** (`docs/publicdigit/reviews/2026-10-08-membership-architecture-ratified.md` L41-52; ratification act §11 L309-322) | [RD] Person → Identity; Organisation Membership "one per (Person, Organisation)"; a Membership Period is "one legal membership episode"; Governance "owns competence, organs, authority and organisational governance rules". |
| **XD-3a** | [F] PROPOSED. Conditions C-1 to C-5 at L42-50. **The political-affiliation statement is at L95, not L84** (§3). |
| **Context map** (`docs/publicdigit/architecture/context-map.md`) | [F] PROPOSED; the PO approved only its location and role. Identity ownership RATIFIED (L69). |
| **PLAN §5.5, §7.2, §11; PH2 §5, §7F, §10, §13; `PBDIGIT-335`** | [F] Present as committed. PLAN §7.2's option definitions still say "profile" (pre-C-6 wording, annotated by C-6). |

---

## 2. Refined authority principle

**Rule:** "Technical platform access does not create organisational authority."

| Layer | Status | Source |
|---|---|---|
| Within **Membership** | [D] Approved (Principal / PO, 2026-11-03); ARB validation pending | PO-ORG-MEM-01 §1 |
| Extended to **Social Contribution** | [PD] Adopted by the Principal for Social Contribution, 2026-11-07 | Principal instruction "Prepare Strategic DDD Discovery for Social Contribution" §6.E ("Preserve the principle: Technical platform access does not create organisational authority"), reaffirmed in "Complete Social Contribution Governance Readiness" §6 ("Remember: platform access does not create organisational authority") |
| Authority for **each individual action** (earlier session package, given in session and not persisted, §2; matrix below) | [OQ] **Unresolved.** The rule excludes one source of authority (the platform administrator). It does **not** say who holds authority. | — |

**Three things kept apart:**
- **Technical permission:** what a system account can do. It confers no business authority.
- **Organisational mandate:** what an organ or office holder is empowered to do. Under [RD], Governance owns competence and authority.
- **Decision-making authority for a particular action:** to be discovered per action and per organisation.

**Authority matrix (status only; details as in the earlier session package, given in session and not persisted, §2):**

| # | Action | Authority status |
|---|---|---|
| 1 | Record a contribution or activity | [OQ] unresolved |
| 2 | Make a claim | [OQ] unresolved (claim on behalf of another?) |
| 3 | Submit evidence | [OQ] unresolved (third-party data) |
| 4 | Verify a claim | [OQ] unresolved; the platform administrator is excluded [PD] |
| 5 | Grant recognition | [PD] the issuing institution or organisation (PH2 §5.4 ruling); platform-hosted granting [OQ] (R-12) |
| 6 | Request correction | [OQ] unresolved |
| 7 | Decide on a correction | [OQ] unresolved; the Membership correction pattern does **not** apply automatically [I] |
| 8 | Set visibility | [OQ] unresolved; depends on the anchor and on consent |
| 9 | Withdraw consent or request restriction | [OQ] specialist assessment needed |
| 10 | Associate a contribution with a person, membership or organisation | Model level: the **Product Owner** decides the business requirement; the **ARB** assesses its architectural consequences within its mandate; the **Principal** authorises the phase (§5a; `PBDIGIT-335` E-7 aligned by R-01c in `436d5f4ea`). Per record: [OQ] |

---

## 3. XD-3a citation finding

| | |
|---|---|
| **Cited as** | "XD-3a L84" (PLAN L268, L279, L295, L357, L426; `PBDIGIT-335` L140; validation report L188) |
| **Actual location** | [F] `docs/publicdigit/reviews/2026-11-01-identity-xd3a-person-identity-scope-decision-record.md` **L95**: "**Privacy risk to keep visible:** for political organisations, a central record of which organisations a person may access can disclose political affiliation." |
| **What the source supports** | [F] Political-affiliation risk from a **central access index** (which organisations a person may access). The record is PROPOSED. |
| **Related sources** | [F] XD-PROV (`…/2026-11-02-identity-xd-prov-person-provenance-and-deletion-authority-decision-record.md`, PROPOSED) L27: for a political organisation, "imported by organisation X" *is* the political affiliation. [F] XD-15 (`…/2026-11-02-organisation-xd15-tenancy-and-data-ownership-decision-proposal.md`, a proposal) L27: membership, governance and affiliation records of a political party "reveal political affiliation". |
| **Finding** | The line number was **wrong** (L84 → L95). The **content** is supported, but only for central access and provenance records. Applying it to **contribution histories** is an **[I] inference** that no source states. |
| **Consequence** | Political-affiliation exposure **remains a discovery concern** for Social Contribution [I], supported by analogy with three proposed records. It is **not** an established privacy requirement. |
| **Correction proposed (R-01a, not applied)** | Change "XD-3a L84" to "XD-3a L95 (central access index; applied to contribution histories by inference)" in PLAN (L268, L279, L295, L357, L426) and `PBDIGIT-335` (L140). The validation report is a historical record; the correction is recorded here, and that report is not rewritten. |

---

## 4. Consent and permission breakdown (R-02)

**K-1 to K-8 are a business permission checklist for discovery, not a legally validated consent form.** Agreeing to K-1 does not by itself establish every legal basis needed to process her information. A data-protection specialist (R-08) must advise on:
- the information notices she receives;
- the processing basis for each use;
- how agreement is recorded and withdrawn;
- retention;
- third-party data (people shown in photographs or certificates, beneficiaries).

**These permissions are separate.** [P] Agreement to one does not imply agreement to another. Each should be asked for, and recorded, on its own.

| # | Permission | What it allows | Can she decline it and still take part? | Specialist advice needed? |
|---|---|---|---|---|
| K-1 | Participation in an interview | The discovery conversation itself | — (the prerequisite) | Advisable (information to give her beforehand) |
| K-2 | Use of her name in internal project documents | Naming her in discovery documents | Yes: she can be pseudonymised ("the initial case") | Yes |
| K-3 | Use of her story | Her account of her contributions as discovery material | Partially: aspects may be excluded | Yes |
| K-4 | Examination of certificates and other evidence | Looking at real artefacts (W2) | Yes: discovery can use descriptions instead | Yes |
| K-5 | Photographs or information involving third parties | Material showing other people (e.g. beneficiaries) | Yes: such items are excluded or redacted | **Yes** (third parties' own agreement, or another basis the specialist confirms) |
| K-6 | Internal analysis | Using the material to build the model | Whether internal analysis needs separate agreement, or another basis, is for the specialist to advise. It is **not** assumed to be a separate consent. | Yes |
| K-7 | Presentation to party members | Any members-only showing | **Yes** | **Yes** |
| K-8 | Public presentation | Any public showing | **Yes** | **Yes** |

- [P] **Declining K-7 and K-8 does not need to prevent participation in business discovery** (K-1 and the use of her information in discovery), if she agrees to take part on those terms.
- [P] Her evidence and certificates are not stored in the repository (already constrained in `PBDIGIT-331` §5).
- [OQ] The form of the consent record, how it may be withdrawn, and how long it is kept: specialist advice (R-08).
- **No legal conclusion is drawn here.**

**A sensitive interview question.** The earlier package proposed "Which contributions were connected to your party, and which were independent of it?" [I] Its answer can reveal political affiliation and organisational relationships.
- [P] Ask it only where it is genuinely necessary (it affects R-10 and R-15), and only after K-1 and K-3 agreement has been given.
- [P] Make declining easy. Suggested neutral wording: "Were any of these activities organised by or through an organisation? You don't have to say which."

---

## 5. Revised decision register

**Group A: decisions the Principal can make now**

| Ref | What is being decided (plain language) | Why it matters | Who decides | Evidence / consultation needed | If postponed | Blocks |
|---|---|---|---|---|---|---|
| **R-01** | Improve the project plan with four corrections: (1) Phase 1 can start only with consent, a named Product Owner and the Principal's go-ahead; (2) data protection is part of the business discovery; (3) the plan points to `PBDIGIT-335` for Phase 2; (4) add the interview question about who decides what is shown | Makes the plan complete and honest about what must happen first | Principal | none | The plan understates the prerequisites | Neither (documentation) |
| **R-01a** | Correct the privacy citation (XD-3a L84 → L95, marked as an inference for contribution histories) (§3) | The project must not rest on a wrong reference | Principal | none | A wrong citation remains in PLAN and `PBDIGIT-335` | Neither |
| **R-01b** | Correct the label in `PBDIGIT-335` §5.E so it shows the Principal's 2026-11-07 adoption as the source for Social Contribution (§2) | Traceability of the authority rule | Principal | none | The rule appears to come from a Membership decision only | Neither |
| **R-05 / R-06** | Commission a **separate** security assessment of S-1, and keep the branch unpushed until the Principal decides on the basis of that assessment (§6) | Avoids sharing potentially sensitive information | Principal | security assessment | Branch cannot be shared | Neither Social Contribution phase (repository sharing only) |
| **R-07** | **Record** the Principal's decision, already taken on 2026-11-07, that technical platform access does not create organisational authority in Social Contribution [PD]. **This is not a request to decide it again.** | Prevents system administrators from becoming de-facto verifiers or record owners | Principal | none | Ambiguity in later design | Neither |
| **R-08** | Obtain early advice from a data-protection specialist, before the interviews | Interviews touch political affiliation, third-party data and publication | Principal (to commission); the specialist advises | — | Consent and visibility questions may be asked wrongly | Weakens Phase 1; does not formally block it |

**Group B: decisions that need people**

| Ref | What is being decided | Why it matters | Who decides | Needed | If postponed | Blocks |
|---|---|---|---|---|---|---|
| **R-02** | Komal Oli's participation and each permission K-1 to K-8 | Discovery cannot use her case without it | **Komal Oli** (the Principal requests it) | §4 breakdown; advice from R-08 | No individual case | **Phase 1** |
| **R-03** | Name the Product Owner | Someone must own the business questions and accept outcomes | Principal (appoints) | A suitable person | Nobody can accept discovery results | **Phase 1** |
| **R-04** | Authorise Phase 1 | Starts the business discovery | Principal | R-02 (K-1, agreement to the interview, and agreement to the use of her information in discovery on the basis the specialist confirms) and R-03 | Discovery waits | **Phase 1** |

**Group C: decisions that need business evidence or further governance (do not decide now)**

| Ref | What is being decided | Who decides | Evidence needed | Blocks |
|---|---|---|---|---|
| R-09 | What to do with the old Contribution Points module | **Product Owner** | Product view; not blocked by Phase 1 | Neither (before Phase 4) |
| R-10 | Whether contributions belong with a member, a person or a person-owned organisation (O-1/O-2/O-3) | **Product Owner** decides the business requirement; **ARB** assesses architectural consequences within its mandate (§5a) | W1–W5, data-protection advice | Finalising Phase 2 |
| R-11 | Who may confirm which contributions | **Issuing and organising bodies; organisational organs; PO** for the product rule | W2, W5 | Phase 2 |
| R-12 | Whether the platform only records recognition or also hosts granting | **PO** | W5 | Phase 2 |
| R-13 | Who can see contribution histories by default | **PO**, with **data-protection specialist** | W3, R-08 | Phase 2 |
| R-14 | What "calculating" a contribution means | **PO** (from Komal Oli's answer) | W3 | Phase 2 |
| R-15 | Whether party office or political participation appears | **PO** | W1, W5 | Phase 2 |
| R-16 | Whether strategic DDD may begin (unblock `PBDIGIT-335`) | **Principal** | W4b approved | Phase 2 |

The Principal is not asked to decide R-02 (Komal Oli's), R-09 or R-12 to R-15 (the PO's), R-10 (the PO's business decision, with ARB assessment), or R-11 (issuing bodies and organs).

### 5a. Who is responsible for what

| Role | Responsible for | Not responsible for |
|---|---|---|
| Product Owner | Business direction and requirements; accepting discovery outcomes | Granting organisational authority |
| ARB | Architectural consequences; ownership-boundary review; context-map governance within its mandate. *[F] No ARB charter was found in `docs/publicdigit` or `engineering/governance`; no further powers are assumed.* | Business policy |
| Principal | Phase authorisation; the Principal decisions assigned by the initiative's governance | Komal Oli's choices; Product Owner decisions |
| Issuing organisation | The recognition it grants, within its mandate | Verifying claims it did not issue, unless mandated |
| Organisational organs and authorised office holders | Authority established by their organisation's governance ([RD] Governance owns competence and authority, act §11) | — |
| Data-protection specialist | Independent advice | Product ownership; business decisions |

*Note:* `PBDIGIT-335` E-7 and §5.G were aligned with this table by R-01c (`436d5f4ea`, 2026-11-07).

---

## 6. S-1 security decision path

1. **[P] First, a separate security assessment** (R-06). It must establish:
   - what information the legacy profile route could expose;
   - whether historical commit `43624fe1c` creates an actual disclosure risk if shared;
   - whether the branch has been shared ([F] it is local and unpushed as of this package);
   - which remediation options exist;
   - the consequences of each option.
2. **Then a Principal decision** informed by that assessment.
3. **Until then:**
   - nothing is pushed;
   - no history is rewritten;
   - no remediation takes place under Social Contribution.

**History rewriting is not presented as an ordinary option.** It would change every later commit on this branch. It could be considered only if the assessment recommends it and the Principal gives a separate, explicit instruction.

---

## 7. Recommended decisions and consequences

| Decision | Recommendation [P] | Business consequence |
|---|---|---|
| R-01, R-01a, R-01b | Approve | The plan and story become accurate; no new work is authorised |
| R-05 / R-06 | Approve the separate assessment; keep the branch unpushed | The branch can be shared later on an informed basis |
| R-07 | **Record** (already decided) | Administrators stay technical; business authority is discovered |
| R-08 | Approve | Better consent and visibility questions; lower risk in Phase 1 |
| R-02 | Request Komal Oli's agreement, permission by permission | She can take part without agreeing to publication |
| R-03 | Name a Product Owner from the business side | Clear acceptance of discovery outcomes |
| R-04 | Authorise only after R-02 (K-1, and agreement to the use of her information on the basis the specialist confirms) and R-03 | Discovery starts on a sound basis |
| Group C | Do not decide now | Avoids turning assumptions into architecture |

**O-1, O-2 and O-3 remain open.** The choice decides whether a person's contribution history follows them when they change organisation. It is a business decision for the Product Owner, assessed architecturally by the ARB (§5a), after the evidence is in.

**Approving R-01, R-01a, R-01b or R-07 corrects documentation only. It does not authorise Phase 1 or strategic DDD.**

---

## 8. Phase 1 readiness and remaining blockers

| Prerequisite | Status | Decision-maker |
|---|---|---|
| Phase 0 approval | **Completed** [PD] (commit `c4b05d54c`) | Principal |
| Komal Oli's participation and permissions (K-1, and agreement to the use of her information in discovery on the basis the specialist confirms) | Outstanding | Komal Oli |
| Named Product Owner | Outstanding | Principal |
| Phase 1 authorisation | Outstanding | Principal |
| Data-protection advice (recommended before the interviews) | Outstanding | Principal (commission) |
| W1–W3 | Not started | — |
| W5 (`PBDIGIT-333`) | Blocked until W1–W3 | — |
| W4b (final synthesis, Principal approval) | Not started | Principal |

**Order:** R-02 + R-03 (+ R-08) → R-04 → W0 → W1–W3 → W4a → W5 → W4b approved → R-10 path → R-16 (unblock `PBDIGIT-335`).

**Constraints preserved:**
- O-1, O-2 and O-3 are open.
- No bounded context is approved.
- No verification authority is invented.
- Recognition is the issuer's act.
- Activity, claim, evidence, verification, recognition, outcome and impact stay distinct.
- There is no scoring, ranking or leaderboard.
- The ratified Membership architecture is unchanged.
- The context map remains PROPOSED.
- `PBDIGIT-335` stays BLOCKED.
- No strategic design and no implementation.

**Stop. Awaiting Principal decisions.**

---

**Traceability.**
- Principal instructions "Prepare Strategic DDD Discovery for Social Contribution" and "Refine the Social Contribution Business Decision Package" (2026-11-07).
- Earlier session decision package (2026-11-07, given in session).
- PO-ORG-MEM-01 · XD-3a L95 · XD-PROV L27 · XD-15 L27 · ratified Membership architecture and act §11 · `context-map.md`.
- PLAN · PH2 · `PBDIGIT-331` … `PBDIGIT-335`.

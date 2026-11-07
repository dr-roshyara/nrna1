# PBDIGIT-335: Social Contribution, strategic DDD discovery (Phase 2)

**Type:** Strategic DDD discovery (not implementation) · **Created:** 2026-11-07 · **Raised by:** the Principal (instruction "Prepare Strategic DDD Discovery for Social Contribution", 2026-11-07). Recorded by Claude, Session A.

| | |
|---|---|
| **Status** | `BLOCKED`: entry conditions E-2 to E-7 are not met (§2). Writing this story does not make it ready. **Strategic design not authorised · implementation not authorised.** |
| **Target project [D]** | `nrna1`. `public-digit` and `election` are reference sources only (plan §5.5). |
| **Phase** | Phase 2 of the initiative plan (`docs/publicdigit/architecture/reviews/2026-11-07-social-contribution-initiative-story-and-development-plan.md` §11) |
| **Depends on** | `PBDIGIT-331` (W1–W3, W4a, W4b) · `PBDIGIT-333` (W5) · `PBDIGIT-332` (informs, does not block) · `PBDIGIT-334` (Phase 0, DONE) |
| **Workstream / Epic** | none assigned |

**Labels used in all deliverables:** [F] verified fact · [D] approved decision · [P] proposal · [H] hypothesis · [OQ] open question · [I] inference. A hypothesis never becomes a decision by being written down.

---

## 1. Business objective

After business discovery has established what social contribution means, how it is evidenced, who needs the information and which ownership alternatives exist, establish a coherent strategic domain model for Social Contribution within nrna1. Determine:

- which business capabilities are required;
- which capabilities belong together;
- which responsibilities must remain separate;
- which existing contexts provide authoritative information;
- where new bounded-context candidates may be justified;
- which organisational and governance authorities own the relevant decisions.

---

## 2. Entry conditions

**Preparation may begin now (met):**

| | Condition | Evidence |
|---|---|---|
| P-1 | Phase 0 corrections committed | commits `0fa62cd8f`, `edefe49e2` (confirmed by the Principal, 2026-11-07) |
| P-2 | Existing discovery documentation available | XPD, PH2 (Phase 1 preparation package), initiative plan, validation report (`docs/publicdigit/architecture/reviews/2026-11-07-social-contribution-*`) |
| P-3 | Source register S1–S15 validated | plan §5.5; validation report §4 |

**Actual strategic discovery requires all of:**

| | Condition | Status (2026-11-07) | Decision-maker |
|---|---|---|---|
| E-1 | Phase 0 milestone approval | **Met**: approved by the Principal, 2026-11-07 (recorded in plan status, §11, §13 and `PBDIGIT-334`) | Principal |
| E-2 | Komal Oli's consent to participate and to be named in project documents | **Not met**: no record | Principal (with Komal Oli) |
| E-3 | A named Product Owner | **Not met**: no record | Principal |
| E-4 | Phase 1 individual discovery complete (W0–W3, W4a) | **Not met**: not started; Phase 1 not yet authorised | Principal (authorisation) |
| E-5 | Generalisation investigation complete (`PBDIGIT-333`, W5) | **Not met**: blocked on W1–W3 | Principal |
| E-6 | Final Phase 1 synthesis (W4b) complete **and approved by the Principal** | **Not met** | Principal |
| E-7 | A decision, or an authorised governance path, for the identity anchor (O-1 / O-2 / O-3) **before** context ownership and the strategic model are finalised | **Not met**; alternatives may be documented earlier | **Product Owner** decides the business requirement; **ARB** assesses architectural consequences and ownership boundaries within its mandate; **Principal** authorises Phase 2 (L52) and takes the Principal decisions in the strategic architecture review (PLAN §11, Phase 3) *(R-01c, R-01c′, 2026-11-07)* |

The story moves from `BLOCKED` to ready only when E-2 to E-6 are met and the Principal explicitly authorises Phase 2. E-7 gates finalisation, not the start.

---

## 3. Scope and exclusions

**In scope:** the strategic questions in §5, based on Phase 1 evidence.

**Out of scope:**
- tactical design (aggregates, entities, schemas, state machines);
- implementation;
- scoring, ranking or leaderboards;
- changes to the ratified Membership architecture, ODA-01, Adjudication or `PBDIGIT-330`;
- the Contribution Points module (`PBDIGIT-332`);
- security observation S-1 (separate workstream);
- approval of the nrna1 context map as a whole.

---

## 4. Deliverables and acceptance criteria

Each deliverable labels every statement as [F] / [D] / [P] / [H] / [OQ] / [I] and cites Phase 1 evidence (interview notes, W2 artefact classification, W5 findings) for every [F].

| # | Deliverable | Accepted when |
|---|---|---|
| D-1 | Validated Ubiquitous Language | Every term from PH2 §5 is confirmed, amended or rejected by the business domain expert, with the evidence source; collisions ("contribution", "verification", "trust") resolved or recorded |
| D-2 | Business capability map | Each capability traced to a Phase 1 scenario; no capability without business evidence |
| D-3 | Subdomain assessment | Core / supporting / generic classification, each with a stated reason (differentiation, complexity, availability elsewhere); none classified by name alone |
| D-4 | Candidate bounded contexts | Each candidate with evidence and rationale, and the alternatives considered (existing context, new context, supporting service, out of scope) |
| D-5 | Responsibility and ownership analysis | Each responsibility assigned to an *owner candidate*, with the identity-anchor option(s) under which it holds; no final ownership before E-7 |
| D-6 | Context relationships and dependencies | Upstream/downstream, authoritative sources, published language, data ownership, consistency needs |
| D-7 | Dependencies on Membership, Governance, Identity and `PBDIGIT-330` | Stated without reopening ratified decisions; the Identity context recorded as not implemented |
| D-8 | Authority and verification boundaries | For submit, verify, grant recognition, correct, set visibility and decide ownership: who has legitimate authority, with source |
| D-9 | Proposed impact on the nrna1 context map | Marked as a **proposed change to a PROPOSED map**; not an approval of the map |
| D-10 | Open questions, decisions required, risks | Including the data-protection items in §5.F |
| D-11 | Independent strategic architecture review package | Ready for a reviewer who did not author D-1 to D-10 |

---

## 5. Strategic discovery framework (how Phase 2 will investigate; no answers given here)

**Input, not result:** PH2 §10 (candidate capability map) and plan §8–§9 are hypotheses. They are tested against Phase 1 evidence, not adopted.

### A. Business capabilities
Investigation topics, **not approved capabilities**:
- recording activities;
- submitting contribution claims;
- managing evidence;
- verifying claims;
- recording recognition;
- recording outcomes;
- assessing impact;
- presenting contribution histories;
- managing visibility and consent.

For each topic, ask: which Phase 1 scenario needs it, who performs it, what it decides, and what it must not decide.

### B. Subdomain assessment
Classify only with evidence:
- **core:** differentiating, and complex in this business;
- **supporting:** necessary, specific to the business, but not differentiating;
- **generic:** solvable by standard means (for example file storage).

Record the reasoning; importance alone is not a criterion.

### C. Bounded-context discovery
For each responsibility, consider in order:
1. within an existing context;
2. in a new context;
3. in a supporting service;
4. outside product scope.

Do not create a context per capability. Check against the existing contexts (Membership, Governance, Trust, Geography, Shared; plan §7.1) and the deptrac/architecture-test rules (Shared hosts no business concepts).

### D. Context relationships
- upstream and downstream relationships;
- authoritative sources;
- published language and contracts;
- data ownership;
- integration (outbox/inbox; plan §7.6);
- consistency requirements.

### E. Governance and authority
**Principle: technical platform access does not create organisational authority.** Within Membership: [D] PO-ORG-MEM-01 (Principal / PO, 2026-11-03). For Social Contribution: [PD] adopted by the Principal, 2026-11-07 ("Prepare Strategic DDD Discovery for Social Contribution" §6.E; reaffirmed in "Complete Social Contribution Governance Readiness" §6). The rule excludes the platform administrator as an authority; it does not determine who holds authority for each action.

Identify the legitimate authority for submitting, verifying, granting recognition, correcting, setting visibility and deciding ownership. A platform administrator is never assumed to hold organisational authority.

### F. Data protection (strategic risk)
**Political-affiliation exposure is a named strategic risk** [I]: by inference from XD-3a L95 (central access index), XD-PROV L27 and XD-15 L27; not an established legal or business requirement. Investigate:
- who can see contribution histories;
- whether visibility is organisation-specific or cross-organisational;
- consent and its withdrawal;
- third-party data in photographs, certificates and beneficiary records;
- public versus member-only presentation;
- retention and portability;
- whether a design exposes political affiliation.

**Legal compliance is not decided by assumption.** Mark every point that needs a specialist data-protection assessment.

### G. Identity and ownership boundary
- O-1 (member-scoped), O-2 (person-scoped) and O-3 (person as own organisation) stay open. They are compared using the plan §7.2 implications table and Phase 1 evidence. **None is selected.**
- The business ownership decision belongs to the Product Owner, after Phase 1 evidence. The ARB assesses its architectural consequences within its mandate (E-7; R-01c, 2026-11-07).
- The ratified Membership architecture is not reopened.
- The Identity context is not assumed to be implemented.

### H. Context-map boundary
The nrna1 context map (`docs/publicdigit/architecture/context-map.md`) is PROPOSED. Phase 2 may prepare a proposed change for Social Contribution. Approving that change does not approve the whole map. Ratified Membership boundaries stay unchanged.

---

## 6. Constraints (carried from the initiative)

- Social Contribution ≠ office-holder performance (`PBDIGIT-330`) ≠ financial donation ≠ political participation ≠ forum contribution.
- Activity ≠ claim ≠ verification ≠ recognition ≠ outcome ≠ impact.
- Certificates are evidence of recognition, not automatically of the activity, outcome or impact (Principal ruling, PH2 §5.4).
- No universal score, ranking or leaderboard.
- Verification authority is unresolved until decided.
- The Contribution Points module is a historical experiment pending PO confirmation (`PBDIGIT-332`).
- No tactical design or implementation.
- Adjudication and ODA-01 are outside this story.
- public-digit ideas enter only via the plan §5.5 method, with their `Source:` line.

---

## Traceability

Principal instruction "Prepare Strategic DDD Discovery for Social Contribution" (2026-11-07) · initiative plan §5.5, §7, §11 · PH2 §5, §10, §13 · validation report 2026-11-07 · `PBDIGIT-331` · `PBDIGIT-332` · `PBDIGIT-333` · `PBDIGIT-334`.

# Social Contribution: remaining governance corrections (R-01, R-01a, R-01b, R-01c review)

> **Preparation only.** This document proposes exact wording. It applies nothing, approves nothing, and authorises neither Phase 1 nor strategic DDD. `PBDIGIT-335` remains `BLOCKED`. O-1/O-2/O-3 remain open.

| | |
|---|---|
| **Status** | **APPLIED** on the Principal's instruction, 2026-11-07: R-01, R-01a, R-01b, R-01c′ and R-01d (R-01c was already in `436d5f4ea`). The text below is kept as the proposal record. Not independently reviewed. *(Was: PROPOSED CORRECTIONS, for Principal review.)* |
| **Authority** | generated (Claude, Session A). Not authoritative without human review. |
| **Commissioned by** | Principal Instruction "Prepare the Remaining Social Contribution Governance Corrections" (2026-11-07, analysis only). The user asked for the result as a written document in the proper folder (2026-11-07). This new file is the only change; it is not staged or committed. |
| **Backlog** | `PBDIGIT-335` (BLOCKED) |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |

**Labels.** [F] fact · [RD] ratified decision · [PD] Principal decision · [D] other approved decision · [P] proposal · [OQ] open question · [I] inference.

**Abbreviations.**
- PLAN = `docs/publicdigit/architecture/reviews/2026-11-07-social-contribution-initiative-story-and-development-plan.md`
- PH2 = `docs/publicdigit/architecture/reviews/2026-11-07-social-contribution-domain-discovery-phase-2.md`
- PKG = `docs/publicdigit/architecture/reviews/2026-11-07-social-contribution-business-decision-package.md`
- S335 = `docs/publicdigit/backlog/PBDIGIT-335-social-contribution-strategic-ddd-discovery.md`

---

## 0. Points where this document departs from its instruction

1. **R-01c is already applied.** The instruction treats R-01c as pending. [F] On the user's direct instruction it was applied and committed in `436d5f4ea` (S335 E-7 and §5.G). It is therefore reviewed here **against the governance sources**, and an amendment is proposed (§5).
2. **A new document was created.** The instruction says "do not edit files". At the user's request, the review is written as this **new** document. No existing file was edited.
3. **R-01 correction 4 is in PH2, not the plan.** The instruction calls all four "project-plan corrections". [F] Correction 4 (the ownership interview question) belongs in PH2 §7F.
4. **A new consequence: R-01d.** The two PKG notes saying S335 E-7 "differs as currently worded" became stale with `436d5f4ea` (§6).
5. **No backlog stories, no strategic design.** The user's standing request to "write stories and start strategic design" was not followed; it is forbidden here.

---

## 1. Repository state and commit verification

| Check | Result |
|---|---|
| Branch / HEAD | [F] `election-flow-updated` at `436d5f4ea` |
| `5b1a9f81b` | [F] Adds only PKG (255 lines) |
| `c01c1d61a` | [F] Only PKG; 12 insertions, 12 deletions (fixes I-1 to I-7) |
| `436d5f4ea` | [F] Only S335; 2 insertions, 2 deletions (R-01c) |
| Staged | [F] nothing |
| Unrelated working-tree entries | [F] 38, unchanged; this new file is the 39th entry (untracked) |
| Remote | [F] No remote branch contains HEAD; nothing pushed; no fetch run |

---

## 2. R-01: four corrections (documentation only; no phase authorised)

| # | File / location | Existing text (exact) | Proposed replacement | Why |
|---|---|---|---|---|
| 1 | PLAN §11, Phase 1 row, entry gate (L400) | `\| Phase 0 approved; consent confirmed \|` | `\| Phase 0 approved; Komal Oli's agreement to take part and to the use of her information in discovery confirmed (per the decision-package §4 checklist and specialist advice); Product Owner named; Phase 1 explicitly authorised by the Principal \|` | The gate omits the Product Owner and the explicit Principal authorisation. "Consent" is replaced by the checklist wording (PKG §4). |
| 2 | PLAN §11, Phase 1 row, content (L400), after `**Alternative ownership models (O-1, O-2, O-3) are explored, not selected.**` | (no data-protection item) | append: ` Data-protection discovery: possible exposure of political affiliation and organisational relationships [I]; who can see contribution information; who decides what is shown; consent and visibility; visibility after joining another organisation; triggers for specialist assessment. **No legal conclusion is drawn.**` | Data protection must shape the discovery (Principal review; PKG §4, R-08). |
| 3 | PLAN §11, Phase 2 row (L401) | `\| **2 Strategic DDD** (separately authorised) \|` | `\| **2 Strategic DDD** (separately authorised; backlog \`PBDIGIT-335\`) \|` | Traceability to the story |
| 4 | **PH2** §7F (after L345, the Principal's central question) | (absent) | `- Who should decide what is shown about your contributions, and should that change if you joined a different organisation? *(Endorsed by the Principal, 2026-11-07.)*` | Ownership expectations, asked neutrally |

[P] None of the four authorises Phase 1 or Phase 2. Correction 1 adds conditions; it removes none.

---

## 3. R-01a: privacy-source corrections

[F] **Source:** `docs/publicdigit/reviews/2026-11-01-identity-xd3a-person-identity-scope-decision-record.md` **L95**: "for political organisations, a central record of which organisations a person may access can disclose political affiliation." XD-3a is PROPOSED. The statement concerns a **central access index**.

**Every remaining incorrect reference** (verified by search):

| File:line | Existing text (exact) | Replacement |
|---|---|---|
| PLAN L268 | `L84 flags that a central record "can disclose political affiliation"` | `L95 flags that a central record of which organisations a person may access "can disclose political affiliation" (applying this to contribution histories is an inference)` |
| PLAN L279 | `Political-affiliation disclosure risk (XD-3a L84)` | `Political-affiliation disclosure risk (by inference from XD-3a L95, which concerns a central access index)` |
| PLAN L295 | `political-affiliation risk (XD-3a L84)` | `political-affiliation risk (inference from XD-3a L95)` |
| PLAN L357 | `Political-affiliation risk is documented (XD-3a L84)` | `Political-affiliation risk is documented for central access and provenance records (XD-3a L95, XD-PROV L27); for contribution histories it is an inference` |
| PLAN L426 | `Political-affiliation disclosure through a visible profile (XD-3a L84)` | `Political-affiliation disclosure through a visible profile (inference from XD-3a L95)` |
| S335 L140 | `**Political-affiliation exposure is a named strategic risk** (XD-3a L84).` | `**Political-affiliation exposure is a named strategic risk** [I]: by inference from XD-3a L95 (central access index), XD-PROV L27 and XD-15 L27; not an established legal or business requirement.` |

The validation report (L188) is a historical record and is not rewritten (PKG §3).

---

## 4. R-01b: authority-rule traceability

| File:line | Existing text (exact) | Replacement |
|---|---|---|
| S335 L135 | `**Principle [D]: technical platform access does not create organisational authority** (PO-ORG-MEM-01).` | `**Principle: technical platform access does not create organisational authority.** Within Membership: [D] PO-ORG-MEM-01 (Principal / PO, 2026-11-03). For Social Contribution: [PD] adopted by the Principal, 2026-11-07 ("Prepare Strategic DDD Discovery for Social Contribution" §6.E; reaffirmed in "Complete Social Contribution Governance Readiness" §6). The rule excludes the platform administrator as an authority; it does not determine who holds authority for each action.` |

[F] PKG §2 already carries this traceability.

---

## 5. R-01c: governance check of the committed wording, and a proposed amendment

**Committed in `436d5f4ea`:**
- E-7 decision-maker column: "**Product Owner** decides the business requirement; **ARB** assesses architectural consequences and ownership boundaries within its mandate; **Principal** authorises finalisation of Phase 2 *(R-01c, 2026-11-07)*"
- §5.G: "The business ownership decision belongs to the Product Owner, after Phase 1 evidence. The ARB assesses its architectural consequences within its mandate (E-7; R-01c, 2026-11-07)."

**Governance evidence:**

| Source | What it establishes |
|---|---|
| S335 L52 | [F] "…the Principal explicitly authorises Phase 2. E-7 gates finalisation, not the start." The Principal **authorises the start** of Phase 2. |
| PLAN §11, Phase 3 row (L402) | [F] Strategic architecture review → "Findings, corrections, **Principal and ARB decisions**; ADRs if warranted". Finalising the strategic model passes through this review, where both the Principal and the ARB take decisions. |
| PLAN §14 | [F] The Principal approves the Phase 0 milestone and explicitly authorises Phase 1. |
| `docs/implementation/Implementation_Process_v1.1_Draft.md` L56-60 (EP-01) | [F] Approval applies to the plan; only an approved plan authorises implementing it. Engineering-level; it does not define Principal/PO/ARB business roles. |
| ARB charter | [F] **None found** in `docs/publicdigit` or `engineering/governance` (PKG §5a). |
| Product Owner mandate for Social Contribution | [F] **No PO named**; no initiative-specific PO mandate document exists. |

**Finding [I].** "Principal authorises **finalisation** of Phase 2" goes slightly beyond the sources:
- The sources show that the Principal authorises the **start** of Phase 2 (S335 L52).
- Finalisation goes through the **Phase 3 review**, with Principal **and** ARB decisions (PLAN L402).
- No source gives the Principal sole authority to finalise.

**Proposed amendment (R-01c′, not applied):**

| File:line | Existing (committed) | Replacement |
|---|---|---|
| S335 L50 (E-7, last cell) | `**Principal** authorises finalisation of Phase 2 *(R-01c, 2026-11-07)*` | `**Principal** authorises Phase 2 (L52) and takes the Principal decisions in the strategic architecture review (PLAN §11, Phase 3) *(R-01c, R-01c′, 2026-11-07)*` |
| S335 L153 (§5.G) | (committed text is sound) | no change |

---

## 6. R-01d: consequence of `436d5f4ea` for the decision package

| File:line | Existing text | Replacement |
|---|---|---|
| PKG §2 authority matrix, row 10 | `(§5a; differs from \`PBDIGIT-335\` E-7 as currently worded, see the §5a note)` | `(§5a; \`PBDIGIT-335\` E-7 aligned by R-01c in \`436d5f4ea\`)` |
| PKG §5a note | `*Note:* \`PBDIGIT-335\` E-7 names "PO + architecture governance (ARB)" as decision-maker. Aligning it with this table is a separate correction. It is not applied here.` | `*Note:* \`PBDIGIT-335\` E-7 and §5.G were aligned with this table by R-01c (\`436d5f4ea\`, 2026-11-07).` |

---

## 7. Consistency check of the proposals

| Against | Result |
|---|---|
| Ratified Membership architecture and act §11 | [F] Unchanged and not reopened. Governance owns organisational authority [RD]; the proposals do not move it. |
| Identity ownership | [F] Unchanged; no Identity capability is assumed. |
| PO-ORG-MEM-01 | [F] Cited only for its Membership scope. |
| Decision package | [F] Consistent once R-01d is applied. |
| Phase gates | [F] R-01 correction 1 tightens the Phase 1 gate. R-01c′ aligns with PBDIGIT-335 L52 and PLAN §11 Phase 3. Nothing unblocks a phase. |
| O-1/O-2/O-3, bounded contexts, tactical design | [F] Untouched |

---

## 8. Decision register (who decides)

| Ref | Item | Classification | Who decides | Status |
|---|---|---|---|---|
| R-01 | Four corrections (PLAN ×3, PH2 ×1) | Documentation correction | Principal | Open |
| R-01a | Privacy source (6 sites) | Documentation correction | Principal | Open |
| R-01b | Authority-rule traceability (S335 L135) | Documentation correction | Principal | Open |
| R-01c | E-7 and §5.G roles | Documentation correction | Principal | **Applied** (`436d5f4ea`) |
| R-01c′ | Ground the Principal's role in E-7 in the governance sources | Documentation correction | Principal | Open |
| R-01d | Update the stale PKG notes | Documentation correction | Principal | Open |
| R-07 | Platform access is not organisational authority | **Existing decision to record** [PD] | — (not to be re-decided) | Recorded in PKG §2 |
| R-02 | Komal Oli's participation and permissions | **Komal Oli's personal choice** | Komal Oli | Open |
| R-03 | Appoint the Product Owner | **Principal decision** | Principal | Open |
| R-04 | Authorise Phase 1 | **Principal decision** | Principal | Open (after R-02, R-03) |
| R-05 / R-06 | Separate S-1 assessment; no push until the Principal decides | **Principal decision**, separate workstream | Principal | Open |
| R-08 | Early data-protection advice | **Principal decision**, then **specialist advice** | Principal / specialist | Open |
| R-09 | Contribution Points module | **Product Owner decision** | PO | Not now |
| R-10 | Identity anchor | **PO decision**, with **ARB architectural assessment** | PO; ARB | Not now (after W4b) |
| R-11 | Verification authority | Issuing and organising bodies; organs; **PO** for the product rule | — | Not now |
| R-12 to R-15 | Recognition hosting, visibility, measurement, political content | **PO decision** (R-13 with **specialist advice**) | PO | Not now |
| R-16 | Unblock `PBDIGIT-335` | **Principal decision** | Principal | Not now (after W4b) |

---

## 9. What cannot be established from the available sources

1. **The ARB's mandate.** No charter was found. The ARB's role is stated only as "assesses architectural consequences within its mandate".
2. **The Product Owner's formal mandate** for Social Contribution. No PO has been named.
3. **Sole Principal authority to finalise the strategic model.** Not found; finalisation runs through the Phase 3 review (R-01c′).
4. **Whether contribution histories expose political affiliation.** Not stated in any source; an inference (R-01a).
5. **Legal requirements** for processing Komal Oli's information. These need specialist advice (R-08); no conclusion is drawn here.

**Stop. Awaiting Principal review. No correction is applied by this document.**

---

**Traceability.**
- Principal instruction "Prepare the Remaining Social Contribution Governance Corrections" (2026-11-07).
- Commits `5b1a9f81b`, `c01c1d61a`, `436d5f4ea`.
- PKG · PLAN · PH2 · S335 · XD-3a L95 · XD-PROV L27 · XD-15 L27 · PO-ORG-MEM-01 · ratified Membership act §11 · EP-01.

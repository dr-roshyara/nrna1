# IR-ODA-01: Review of the Organisation Domain Architecture Baseline (ODA-01)

> **This review is read-only. It approves, ratifies, decides and authorises nothing.** It challenges ODA-01, verifies its sources and routes questions to the Principal / PO and the ARB. It is not a second conceptual baseline.

| | |
|---|---|
| **Commission** | IR-ODA-01, "Independent Review of the Organisation Domain Architecture Baseline" (Principal / PO, 2026-11-03). Read-only review |
| **Subject** | `docs/publicdigit/architecture/reviews/2026-11-03-organisation-domain-architecture-baseline-oda-01.md` (877 lines; **untracked**, not committed). Reviewed version: sha256 `4617c57116064c2a10dd15bee41558a90f892bb2a63349a18f609895c6d9c740` |
| **Status** | **REVIEW FOR PRINCIPAL / ARB.** Not an approval |
| **Authority** | generated (Claude, acting as strategic DDD / domain-modelling / architecture-governance reviewer) |
| **Independence statement** | **This review was performed by a separate Claude agent with no access to the ODA-01 author's working context. It is process-separated from the author, but it is of the same model family. It is therefore NOT a fully independent review.** Shared training, shared blind spots and shared stylistic habits are possible. **Recommendation: a human reviewer or a different-model review should check this review and ODA-01 before the ARB relies on either.** |
| **Snapshot** | branch `election-flow-updated`, HEAD **`f826d275b`** (ODA-01 recorded `821a3bb29`). Commits since `821a3bb29`: `26a766124`, `3ef42f243` (KnowledgeOS, session log) and `f826d275b` (`.claude/scripts/session-id-log.sh`, `.claude/settings.json`, `readme-session-id.md`). **None touches `app/`, `database/`, `routes/` or `docs/publicdigit/`** [F] `git log --stat 821a3bb29..HEAD` |
| **Date** | 2026-11-03 |
| **Labels** | **[F]** fact with `path:line` · **[D]** decision with authority · **[P]** proposal · **[I]** inference or judgement · **[OQ]** open question · **[R]** risk |

**Paths used.** R/ = `docs/publicdigit/reviews/` · AR/ = `docs/publicdigit/architecture/reviews/` · RAT = R/`2026-10-08-membership-architecture-ratified.md` · ACT = R/`2026-10-08-membership-architecture-ratification-act.md` · DR07 = R/`2026-11-02-governance-dr07-committee-ownership-decision-paper.md` · GMAP = R/`2026-11-02-governance-domain-discovery-map-roadmap-and-readiness.md` · CMAP = `docs/publicdigit/architecture/context-map.md` · PRI = `docs/publicdigit/architecture/platform-rules-index.md` · BR = `docs/publicdigit/business_rules/20261101_1739_person_account_affiliation_business_rules.md` · PREP = AR/`2026-11-03-organisation-membership-constitutional-authority-discovery-preparation.md` · BDR = `docs/architecture/design/Round49-06_Boundary_Decision_Register.md`.

**Method.** Every material ODA-01 claim was re-read at its cited source. Code was read statically (`sed`, `grep`). No command changed anything. No test, migration, artisan command or database access. `docs/knowledgeos/**` was not read.

**Working tree (other sessions; not touched).** Modified: `.claude/CONTEXT-publicdigit.md`, `.claude/sessions/2026-10-0{1,7,8}.md`, `2026-10-1{0,1,5}.md`, `2026-11-03.md`, `.claude/settings.local.json`, `bootstrap/cache/packages.php`, `developer_guide/election/ip_voting/{00,06,07,08}_*.md`, a KnowledgeOS `START-TIMES.tsv`. Untracked: `.claude/plans/wobbly-foraging-pillow.md`, a stray `C:\Users\…\debug_response.txt`, `architecture_legacy/how_far_we_are/VoterSlugStep.php`, KnowledgeOS scratch folders, `docs/publicdigit/architecture/brainstorming/`, `platform-rules-index.md`, eight AR/ 2026-11-03 documents (including ODA-01 and, **new during this review**, `2026-11-03-ir-sec-01-independent-review.md`), `docs/publicdigit/brainstorming/governence/`, `…/multi-database-system/SESSION_START.md`, R/`2026-11-03-membership-cor3-decision-brief-cq3-cq9-ga1.md`, `tests/Isolated/Pbdigit274/…`. The only file this review created is this one.

---

## A. Executive conclusion

**Classification: SOUND WITH CORRECTIONS. Two parts are not yet decision-ready: the rationale for O-2 (§5.3) and the strategic classification (§11.1).**

[I] What holds:
- The core separations hold and are well sourced: Organisation ≠ Membership ≠ Governance ≠ Election ≠ Tenant; unit ≠ organ; GeoUnit ≠ jurisdiction; access role ≠ constitutional authority.
- C-1 is a real and important finding. The "recognising body" definition is labelled ratified in two documents, but it exists only in a non-ratified candidate. I confirmed this, and found **two further documents** that repeat the mislabel (§B).
- BR-13a is respected. §9 stays a taxonomy.
- The document is honest that it decides nothing. Its label discipline is mostly good.

[I] What must be corrected before the Principal or ARB relies on it:
1. **The definition exists in two different versions** (§0 item 3 vs §3.1). The §3.1 version builds tenancy into the definition ("each Organisation is also the unit of data isolation"). That contradicts its own claim that tenancy is not part of the definition, and it pre-empts XD-15.
2. **"That PublicDigit recognises"** makes the platform's recognition part of what an Organisation *is*. That contradicts the separation of the organisational and platform lifecycles in §3.3.
3. **The O-2 argument has two flaws.** It treats O-1 as a straw man: "Governance would own rules it does not apply". But O-1 can also let each capability derive its own rules. The real difference is only *where the source record lives*. O-2 also leans on PO-ORG-MEM-01 A ("its Constitution"). That is a statement of **business** ownership, and PREP itself says it is "not context ownership" (PREP `:67`). The recommendation may still be right, but the argument for it is not yet good enough to be ARB-ready.
4. **The strategic classification is not supported by the business case.** It also ignores three classifications that already exist and conflict with each other (AIP-14, BDR-08, the 2026-10-09 review). It adds a fourth.
5. **Several [F] labels are wrong or overstated:**
   - `geographic_levels` immutability;
   - the `admin_level` range;
   - `governance_status` called a "platform service status";
   - "committees model both units and organs" stated as fact;
   - the merger claim cited as "ratified";
   - "constitution absent";
   - ACT §10 cited as a context rule.
6. **Missed evidence:**
   - existing Governance code (`ConstitutionalBasis`, `AuthorityAssignment`, delegation);
   - BDR-03 "Appointment (Authority)", which covers appointments as well as Mandate;
   - the bootstrap organisation record, which does not meet P-ODA-1;
   - election regions (`posts.state_name`, `users.region`), a fourth geographic concept;
   - an unflagged dependency cycle Organisation → Platform → Governance → Organisation in §12.

None of these defeats the baseline's direction. All of them change what may be decided now.

---

## B. Evidence verification

### B.1 Claims confirmed

| ODA-01 claim | Verified at |
|---|---|
| RAT names Organisation only as the owner of OrganisationAffiliation and as the second party of a Membership | [F] RAT `:42`, `:43` |
| RAT I5 allows units to map to "another organisation" | [F] RAT `:150` |
| RAT §8: Governance supplies the AuthorityCheck port; Membership stores evidence only | [F] RAT `:156` |
| Provenance fields `ruleRef`, `policyVersion` | [F] RAT `:72` |
| ACT §10 aggregate exclusions; ACT §11 Governance "competence, organs, authority and organisational governance rules"; "No shared persistence is required" | [F] ACT `:283-305`, `:321`, `:349` |
| ACT does not mention "positions" | [F] `grep -n -i position` on ACT and RAT: no hit |
| C-1: "the recognising body" exists only in the non-ratified candidate; it is labelled ratified elsewhere | [F] candidate `:3` ("Not ratified"), `:27`; GMAP `:56` "[R-D] (ratified ontology)"; audit `:112` "RATIFIED"; no hit in RAT, ACT or the four `2026-10-08-membership-final-*` companions |
| PO-DR07-01 wording, including "positions" | [F] DR07 `:265-268` |
| BR-4, BR-12, BR-13, BR-13a, BR-14 wording and status | [F] BR `:26`, `:35`, `:36`, `:39`, `:40` |
| ADR-004: Organisation is a Candidate; "Election Governance" is the confirmed context | [F] ADR-004 `:32`, `:15` |
| `Organisation` model: SoftDeletes, UUID, slug route key; `isElectionOnly()` | [F] `app/Models/Organisation.php:12`, `:54-57`, `:203-206` |
| `type enum('platform','tenant')`, `is_default` | [F] `database/migrations/2026_03_05_000001_create_uuid_organisations_table.php:17-18` |
| `uses_full_membership` default true; it selects the voter source | [F] `2026_04_15_120000_…:19`; `app/Domain/Election/Enum/VoterSourceStrategy.php:18-28` |
| `OrganisationGovernanceStatus` has four values; only `ACTIVE` allows committee creation | [F] `app/Domain/ValueObjects/OrganisationGovernanceStatus.php:9-16` |
| Nepal default geography | [F] `GeographicStructure.php:15-23`; `Organisation.php:236-239` |
| `committees` in the Membership Tenant migrations, with type, level and `parent_committee_id` | [F] `…/Membership/…/Tenant/2026_01_16_000002_create_committees_table.php:41-73` |
| `geo_administrative_units.organisation_id` is nullable | [F] `database/migrations/2026_05_13_134529_…:15-16` |
| Geography has 71 files | [F] `find app/Contexts/Geography -type f \| wc -l` = 71 |
| NRNA, "80+ countries" | [F] `docs/publicdigit/business_case/EXECUTIVE_SUMMARY.md:7`; `BUSINESS_CASE.md:129-134` |
| PRI A87: "no universal governance model" has no authoritative record; creating the operator organisation is not authorised | [F] PRI `:415`, `:103` |
| BDR-03: "Appointment (Authority)" owns Mandate truth | [F] BDR `:16` |
| No product or `docs/publicdigit` commit between `508283527` and `821a3bb29` | [F] `git log 508283527..821a3bb29 -- app database routes docs/publicdigit` is empty |

### B.2 Claims corrected

| # | ODA-01 says | Source shows | Effect |
|---|---|---|---|
| K-1 | `geographic_levels` is among the fields "immutable after creation" (§2.3) | [F] the immutable list is `committee_structure`, `geographic_scope`, `allowed_countries`, `base_country_code`, `base_region_id` only (`Organisation.php:61-67`). `geographic_levels` was added by a separate migration, `2026_05_05_000002_…`, which ODA-01 does not cite | R-10 / C-5 are slightly overstated: the level configuration *can* change. [I] The guard is an Eloquent `updating` event (`Organisation.php:59-75`). It is a model convention, not a schema constraint |
| K-2 | Worldwide alternative = "Worldwide, Region, Country" | [F] `forWorldwideOrg()` has four levels, including Province (`GeographicStructure.php:25-33`). **It is not called anywhere in `app/`** (grep). A second, unrelated profile model exists: `TenantGeographyProfile::forDiaspora / forNepalParty / forGermanyNGO` (`app/Contexts/Geography/Domain/Models/TenantGeographyProfile.php:60-95`), which uses a different level scale (0–9) | The "worldwide alternative" exists only on paper. The code already contains three organisation archetypes, and they match ODA-01's three reference candidates (§K) |
| K-3 | `admin_level` 1–4 | [F] widened to `BETWEEN 0 AND 10` (`…/Geography/…/Landlord/2026_01_14_000003_extend_level_range_add_continents.php:33-34`) | minor |
| K-4 | `governance_status` is a "platform service status" (§3.3, C-7) | [F] the only write found is `'governance_configured'`, by `GovernanceSetupController.php:101-105`. It is gated by the organisation **owner** access role (`app/Policies/OrganisationPolicy.php:65-84`). **No write of `active` or `suspended` was found** in `app/` or `routes/` (grep). The status also drives a committee "structure epoch" (`CommitteeStructureEpochContext.php:29-31`) | It is an owner-driven setup state with three meanings (setup progress, committee-creation gate, structure epoch). Calling it "platform service status" is an **[I]**, not a fact. "Suspended by platform" has no code evidence |
| K-5 | "[F] `committees` models both units and organs" (§2.3, R-2) | [F] the migration shows one table with types. That the table represents **two concepts** is the **Principal's position** (DR07 `:224`). PO-DR07-01 says records are **not classified** (DR07 `:268`) | Relabel as [D] (Principal position) plus [I]. Not [F] |
| K-6 | C-6: committees in the Membership context are "contrary to ACT §10" | [F] ACT §10 is the **aggregate** boundary (ACT `:283-305`). Whether it also constrains context placement is ARB-1, which is **pending** (DR07 `:312`). The context-level basis is ACT §11 "organs" (`:321`) plus recorder's note N-6 (ACT `:19`), which "does not amend the act" (ACT `:9`) | C-6 presumes an ARB reading. It should cite ACT §11 and N-6, and leave ARB-1 open |
| K-7 | "ratified Membership handles merger collisions by renumbering" (§9) | [F] the source is the **ratification-readiness review** (R/`…-ratification-readiness.md:277`), not a ratified artifact. The ratified set addresses **person** merges only (`…-final-decision-register.md:27`; `…-final-proof.md:68`) | Downgrade to [P] (pre-ratification analysis) |
| K-8 | "Geography-based" appears only in brainstorming (§8.2) | [F] the cited report says "geography-based **jurisdiction**" (`…/20261011_0935_analysis_report.md:606`). The phrase "**geography-based committee governance platform** for NRNA" is in a legacy session instruction, which pairs NRNA with Nepal's *domestic* Province→Ward hierarchy (`architecture_legacy/committee/geo_committe_formation/20260513_1257_geo_unit_matrix.md:5-12`) | It strengthens ODA-01's point: the framing came from an AI working brief, not a decision. It also shows how NRNA (diaspora) and Nepal's domestic geography were conflated |
| K-9 | "No existing record classifies Organisation, Membership or Governance" (§11.1) | True for those three. But "Elections = core" ignores records that conflict with each other: AIP-14 (Election system core), BDR-08 (Election Lifecycle a **supporting** subdomain, `BDR:21`), a 2026-10-09 review ranking Election / Voting / Appointment "Supporting" (AR/`2026-11-03-publicdigit-architecture-assessment.md:104`), and BDR v1.1, where Adjudication + Contestation are "the genuine greenfield Core" (`BDR:57`) | P-ODA-8 adds a fourth classification without reconciling the other three (§H) |
| K-10 | "Absent: … statutes, constitution versions" (§2.3) | [F] No document or version store exists. But article-reference value objects do exist: `app/Contexts/Governance/Domain/ValueObjects/ConstitutionalBasis.php:9-25` (article + text, no version) and `app/Contexts/Membership/Domain/Committee/Constitutional/Doctrine/ConstitutionalArticleReference.php`. Governance code also has `AuthorityAssignment`, `DelegationScope`, `AuthorityDelegated` (`app/Contexts/Governance/Domain/Authority/…`) | Canonical discovery (Operating Loop phase 3) missed existing value objects. They are a precedent that places constitutional citations in Governance, which matters for O-1 vs O-2 |
| K-11 | PO-ORG-MEM-01 A is labelled [D] and quoted with specific wording | [F] No decision record exists (DR-07a report `:9`, `:330`). PREP offers **two** wordings (PREP `:47-60`). Which one was approved is not recorded | The content can be cited as an unrecorded PO direction. Wording-level arguments ("its Constitution") cannot rest on it |
| K-12 | Mandate is the only item flagged to ARB-8 (§6.2, §11.2) | [F] BDR-03 names a *context*, "Appointment (Authority)" (BDR `:16`). CMAP lists "whether this is the same context as Governance" as UNRESOLVED (CMAP Appointment rows, `:79-80`) | Appointment and Tenure under "GOV [P]" also depend on ARB-8, not only Mandate |
| K-13 | P-ODA-1 is one definition | [F] §0 item 3 and §3.1 differ. §3.1 adds "under its constitution", "None of them constitutes it" and "**each Organisation is also the unit of data isolation (the tenant)**" | Internal inconsistency. The commission reviews the §0 version (§C) |
| K-14 | `policyVersion` identifies a constitutional version (§5.2, §5.3 "one retention point for `policyVersion`") | [F] RAT `:72` fixes the field names, not what they refer to. It could equally be a derived-rule or platform-policy version | [I] An interpretation. O-2's main "for" argument and the rejection of O-3 rest on it, and on PREP §8.3, which is itself [P] |

### B.3 Not verified

- PO-ORG-MEM-01 A / B approvals: there is no record. I can only see the recitals in generated documents.
- The ODA-01 commission text: I did not have it. ODA-01's references to "commission §n" are unverified.
- Exact line anchors CMAP `:89`, `:121`, K7 brief `:119-130`, GMAP G-6 / G-9: spot-checked only. The rows exist; the line numbers were not checked one by one.
- Any statement about real organisations' statutes. No statutes are on file (PRI A86).

---

## C. Definition review (P-ODA-1)

**Recommendation: ACCEPT WITH AMENDMENTS** (business meaning to the PO, vocabulary to the ARB under O-14).

### C.1 Conceptual completeness (commission §4.1)

| Concept | Distinguished? | Comment |
|---|---|---|
| Person, Membership, Election, Tenant | yes | [F] backed by ACT §11 and RAT §1 |
| Governance | yes, with nuance | §3.2 separates the self-governing *fact* from the Governance *context*. Good. C-8 (ADR-004 "Election Governance") still collides |
| OrganisationAffiliation / platform access | yes | [D] BR-2 / BR-3, EM-VOC-002 |
| Organisational Unit vs Governance Organ | yes | [D] PO-DR07-01 |
| Geographical Jurisdiction | yes | §8. But "jurisdiction" is also used for committee arbitration (`ConstitutionalArbitrationKernel`; BDR v1.1 `:54`). The reservation of the term must name that use |
| **Missing:** bootstrap / default organisation | **no** | [F] `type = 'platform'`, `is_default` (`create_uuid_organisations_table.php:17-18`). [D] B1 / BR-12: "infrastructure, never a Working Organisation" (BR `:35`). It is an `organisations` row that is **not** a self-governing body. The definition must say that a platform record is not an Organisation in the domain sense, or the glossary will contradict the data |
| **Missing:** demo mode (`organisation_id = NULL`) | no | project overview, MODE 1. [I] It is neither an Organisation nor a tenant in the domain sense |
| **Missing:** sources of authority outside the statutes | no | see C.2 |

### C.2 "Self-governing" (commission §4.2)

| Case | Fits P-ODA-1? | Comment |
|---|---|---|
| independent association | yes | — |
| worldwide diaspora organisation | yes, if it is one body | [OQ] whether country-level bodies are units or separate organisations (C-4) |
| political party | **partly** | [I] party law and electoral law bind internal affairs. "*Its* constitution is *the* source of authority" is too strong |
| subordinate organisational body | **excluded or ambiguous** | a body whose statutes are subject to a parent's is not fully "self-governing". RAT I5 allows it to be "another organisation" (`:150`) |
| organisation subject to external rules | **partly** | [I] e.g. statutory association law overrides statutes. The questionnaire asks this (X2) but the definition denies it |
| federation member | **ambiguous** | BR-13a forbids *designing* federation (BR `:39`). It does not forbid such an Organisation from *existing*. The wording must not make it conceptually impossible |

[I] Fix: "decides its internal affairs through its own organs under its own rules, **within the law and any external rules that bind it**". This admits all six cases and designs nothing.

### C.3 "Distinct party" (commission §4.3)

[I] "Distinct party" is undefined. ODA-01 §3.1 grounds it in BR-13 / BR-13a, but those rules are about *rules across organisations*, not identity. The real criterion in ODA-01 is **own identity, own rules, own organs**. Not legal personality (correctly, §3.3) and not PublicDigit recognition. Replace "distinct party that PublicDigit recognises" with "**has its own identity**". Platform recognition (onboarding) belongs to the platform lifecycle.

### C.4 Identity continuity (commission §4.4)

| Change | Status in the record |
|---|---|
| renaming | [P] (ODA-01 §3.3). No decision; the code keeps `name` without history |
| restructuring | [P]. The code partly *forbids* it (K-1) |
| constitutional amendment | [P]. Consistent with RAT's append-only provenance (`:72`), but not decided |
| change of leadership | **not listed in P-ODA-1**. [I] Obvious, but it should be stated, because tenure history (GMAP G-9) depends on it |
| dormancy | [P] |
| dissolution | [P]. The invariant "a dissolved organisation accepts no new decisions" (§11.2) is **unsupported**. [I] Liquidation-phase decisions after dissolution are common. Needs evidence (questionnaire O4) |
| merger / succession | [P] / [OQ]. ODA-01 §3.3 says "BR-13a applies". [I] That stretches BR-13a: it covers cross-organisation *governance functionality* and shared rules (BR `:39`), not an organisation's own lifecycle event. Succession should stay an open question, not be ruled out by BR-13a |

**Nothing on identity continuity is an established requirement.** All of it is [P].

### C.5 Recommended wording [P]

> **Organisation.** A collective body with its own identity, purpose and rules, which decides its internal affairs through its own organs under its own constitution (statutes), within the law and any external rules that bind it.
> - Its identity persists through changes of name, structure, constitution, leadership and status. It does not depend on PublicDigit.
> - Membership, Governance, Elections and other capabilities serve the Organisation. None of them constitutes it.
> - A platform record (the bootstrap organisation, demo data) is not an Organisation in this sense.
> - How PublicDigit isolates an Organisation's data (tenancy) is not part of the definition.

| Amendment | Consequence |
|---|---|
| drop "that PublicDigit recognises" | the Organisation exists before and after onboarding. The two-lifecycle split (§D) becomes definitional, not optional |
| "within the law and external rules" | parties, subordinate bodies and federation members fit without any federation design. **Fail-closed reasoning (§D) must allow authority sources other than the statutes.** X2 becomes a mandatory question |
| drop the tenant sentence | XD-15 stays open. "Unit as separate Organisation" (C-4) is no longer forced into a separate database by definition (ODA-01 R-9) |
| add "leadership" | aligns with tenure history |
| platform-record exclusion | removes the contradiction with B1 / BR-12. The glossary must then say what an `organisations` row of `type = 'platform'` is |
| "collective body" kept | [OQ] whether companies (business case: "corporate governance", `EXECUTIVE_SUMMARY.md:90`) and foundations without members are in scope. A PO question, not settled here |

---

## D. Identity and lifecycle review

| Commission question | Assessment |
|---|---|
| 1 Are the organisational and platform-service lifecycles genuinely different? | **yes** [I]. A platform suspension cannot dissolve a body that exists in law or in fact. **But** the platform lifecycle in ODA-01 (onboarding → offboarded) is wholly [P]. The only code evidence is SoftDeletes, and `governance_status` is not a platform lifecycle (K-4) |
| 2 States in the Organisation domain | active, dormant, dissolved, succeeded: [P], no evidence yet. The questionnaire (O4) is the right source |
| 3 States in Platform / tenancy | onboarding, in service, suspended by platform, offboarded: [P]. Supports XD-15 / OQ-ODA-3 being an ARB question |
| 4 Can an Organisation exist independently of PublicDigit? | **yes** [I]. P-ODA-1 as worded ("PublicDigit recognises") contradicts this (C.3) |
| 5 Can it use PublicDigit before statutes are recorded? | [F] today **every** organisation does: no statute is on file anywhere (PRI A86). [I] This is a fact of the current product, not a hypothetical |
| 6 Should competence-based decisions fail closed without an authoritative source? | **ODA-01 understates a conflict.** [D] D-4 interim lets owner / admin admit (PREP `:299`). If "no decision may claim constitutional competence" until statutes are recorded, then either D-4 stays as an **explicitly non-constitutional interim provenance** (recorded as such) or current admissions stop. ODA-01 §5.2 calls this "consistent with D-4"; it is in tension with it. The same applies to who calls elections in election-only organisations. **PO decision needed** |
| 7 Does `governance_status` mix meanings? | **yes, three, not two** [F] (K-4): setup progress, committee-creation gate, structure epoch. "Suspended" has no writer |

**Decisions required before tactical design:**
- PO: OQ-ODA-5 together with the D-4 interplay (above).
- ARB: OQ-ODA-3 (where platform service status lives).
- PO: whether name history and succession are requirements.
- Evidence: dormancy and dissolution provisions (O4).

---

## E. Constitution and authority review (most important)

### E.1 The ten questions

| # | Question | O-1 (Governance owns the document) | O-2 (Organisation owns the record) | Assessment |
|---|---|---|---|---|
| 1 | Owner of the original document | Governance context | Organisation context | [D] **business** owner: the Organisation (PO-ORG-MEM-01 A, unrecorded). **Context** owner: open (ARB). Neither option changes the business owner |
| 2 | Amendments and provenance | recorded in Governance with the organ's decision | recorded in Organisation, with authority evidence from Governance | O-2 adds a GOV → ORG dependency (§I) |
| 3 | Who decides whether an amendment is valid? | Governance | Governance (evidence), Organisation records | **same in both** [I]: competence plus procedure is a Governance question (ACT §11) |
| 4 | Who interprets competences? | Governance | Governance | same in both. ACT §11 |
| 5 | Does an organ or office have authority? | Governance | Governance | same |
| 6 | May an actor exercise it now? | Governance (AuthorityCheck, RAT `:156`) | same | same. Content is P11 (not touched) |
| 7 | How Membership and Elections cite the source | `(document, article, version)` | same | **ODA-01 says "(article, version)". That is insufficient**: bylaws and regulations are separate documents (§5.1 row "Rules of procedure"). The document identity must be part of the citation |
| 8 | Is ACT §11 preserved? | yes, if the ARB reads "organisational governance rules" as including the source text | yes, if the ARB reads it as the *derived* rules only | **both depend on one ARB reading.** ODA-01 states this correctly (§5.3) |
| 9 | Second owner of Governance rules? | no | **risk**: the provisions on organs and competence exist twice (text in ORG, interpreted rule in GOV). Acceptable only if ORG holds the text **as evidence, with no interpretation and no derived rule** | needs an explicit constraint (E.3) |
| 10 | Is the source / interpretation / authority evidence / operational rule distinction clear? | — | — | **partly.** §5.1 defines "Governance rule" and "Organisational policy" but not "authority evidence" or "interpretation ruling" (questionnaire D5) as separate terms |

### E.2 Where ODA-01's comparison is weak

- **Straw man.** ODA-01's case against O-1 is that Governance would become "the owner of rules it does not apply" and a "rules engine for everything" (§5.3). But O-1 only places the **source record**. Nothing in O-1 stops Membership and Election from deriving their own rules from it. [I] Once that is seen, the two options differ in one thing: **which context holds the versioned source record**.
- **Conflated ownership.** "Matches PO-ORG-MEM-01 A ('its Constitution')" uses a business-ownership statement to argue for context placement. PREP `:67` says explicitly that the decision "concerns business ownership, not context ownership". This breaks quality criterion 4.
- **Unequal cost.** O-2 needs (a) the Organisation context to move from Candidate (ADR-004 `:32`) and (b) a GOV → ORG evidence flow, which creates the ORG ⇄ GOV cycle (§I). O-1 needs neither, only an ARB reading. ODA-01 lists (a) but treats (b) lightly.
- **Unmentioned precedent.** Code places article citations in Governance (`ConstitutionalBasis`, K-10). That is weak evidence (legacy, no version), but canonical discovery should have weighed it.
- **For O-2, genuine:** a constitution also governs purpose, membership, finance and elections. A record held in Governance would serve non-governance consumers. [I] This is the strongest argument for O-2, and ODA-01 does make it.

### E.3 ARB-ready recommendation [I]

1. **Decide the separation first; it holds under either placement.** Adopt four distinct concepts:
   - **constitutional source record:** document, versions, effective periods, adoption evidence;
   - **interpretation:** rulings of the body the statutes name;
   - **authority evidence:** AuthorityCheck results;
   - **operational rule:** each capability's derived rule, citing `(document, article, version)`.
2. **Ask the ARB one reading:** does "organisational governance rules" (ACT `:321`) include the source text? This is the hinge for O-1 vs O-2. It does not amend ACT §11.
3. **Then place the record conditionally:**
   - if the ARB establishes an Organisation context, use **O-2**, under the constraint that it holds the source **as evidence only**, with no interpretation and no derived rules, and that amendment validity is decided by Governance;
   - otherwise use **O-1**, with the record published to all capabilities as a published language.
   - Either way, record the resulting dependency explicitly.
4. **Defer the final placement** until at least one real statute has been read (§K). [I] Without one, nobody knows whether "the constitution" is one document or several, or how amendments take effect.

The proposed five-way separation (Organisation: source and version; Governance: organs, competence, authority, governance rules; Membership: facts; Election: processes and eligibility; Platform: access and tenancy) is **coherent as a working hypothesis** [I]. Two qualifications: "governance rules" must mean *derived* rules (step 1), and appointments may sit in the BDR-03 context (K-12).

---

## F. Structure, units and organs review

| # | Question | ODA-01 position | Assessment | Status |
|---|---|---|---|---|
| 1 | unit vs organ | unit = where members belong; organ = who decides | sound | [D] PO-DR07-01 for the distinction; definitions [P] |
| 2 | organ within a unit | organ scope = whole Organisation **or exactly one** unit (§4.2) | **"exactly one" is unsupported.** [I] Joint or regional organs span several units; functional organs (an election commission) have no unit scope | [P]; needs evidence (O9). Rephrase O9 so it does not presuppose the answer |
| 3 | unit with geographic or functional scope | both allowed | sound | [P]; [F] wings exist as committee types (DR07 `:328`) |
| 4 | office independent of holder | yes | sound | [P] (GMAP v1.5) |
| 5 | position always part of an organ? | §4.2 "Organ has Office / Position 0..n" implies yes; §6.1 "Office … existing independently" and §6.2 "decision of an office holder (individual)" imply offices of the Organisation itself | **internally inconsistent.** A General Secretary may be an office of the Organisation, not a seat in an organ | [OQ] for evidence (S4) |
| 6 | position vs office | position = seat, possibly an office | workable | [P]; C-2 |
| 7 | appointment vs administrative responsibility | separated (§6.2) | sound | [P]; PREP §3.2 (c)–(d) |
| 8 | delegation vs original competence | separated | sound | [P]; PREP §3.2 |
| 9 | jurisdiction = authority area, not unit | yes (§8.1) | sound, subject to the arbitration use of "jurisdiction" (C.1) | [P] |

**Established:**
- [D] ratified (ACT §11): Governance owns organs, competence and authority.
- [D] PO business ruling (PO-DR07-01): governance committees are organs; units are distinct.

**Needs decisions:**
- unit ownership (O-16 / DR-07b; not resolved here);
- committee placement (DR-07a; not resolved here);
- positions under ACT §11 (ARB-2 / OQ-ODA-12; **not assigned to ACT §11 here**);
- appointment / tenure placement (ARB-8 incl. BDR-03);
- organ scope cardinality (evidence).

---

## G. Geography and jurisdiction review

| # | Question | Assessment |
|---|---|---|
| 1 | GeoUnit independent of an Organisation? | **yes** [F]: official units carry NULL `organisation_id` (`2026_05_13_134529_…:15`) |
| 2 | different structures per country? | [OQ]. [F] the code allows one `geographic_levels` setting per organisation. Evidence needed (O8) |
| 3 | operating across countries? | [F] `worldwide` / `multi_country` scopes exist. [F] the worldwide factory is unused (K-2) |
| 4 | geographic structure = organisational structure? | **no** as a modelling rule [I]. GMAP's "two hierarchies" is [F + H] (GMAP `:104-108`) |
| 5 | organ jurisdiction = authority assignment over an area? | yes, as proposed [P] (GMAP JurisdictionAssignment). BQ-J1 open |
| 6 | Nepal hierarchy: requirement or implementation choice? | **implementation choice** on current evidence [F]: it is a code default (`Organisation.php:236-239`). Its framing comes from a legacy AI brief (K-8). The named customer is a diaspora organisation (`EXECUTIVE_SUMMARY.md:7`) |
| 7 | "geography-based governance platform" approved? | **no** [F]. No decision record contains it (K-8) |

**Gap ODA-01 missed: electoral areas.** [F] `posts.is_national_wide` and `posts.state_name` (`database/migrations/2026_03_05_000005_create_uuid_posts_table.php:16-17`) and `users.region` (`app/Models/User.php:74`) model a fourth geographic concept: the **area a post is elected for**, and the voter's region. It is free text, not a GeoUnit, and it is not an operating area, a unit's responsibility area or an organ's jurisdiction. [OQ] Election owns it today. Should it reference GeoUnits?

**Scope choices (the PO decides; implications only):**

| Option | Implication |
|---|---|
| A. National hierarchies only (e.g. Nepal) | simplest. Conflicts with the named diaspora customer |
| B. Diaspora (worldwide → region → country) as well | needs per-organisation hierarchies; the unused worldwide factory becomes real |
| C. Any official hierarchy plus organisation-custom units | the most general. Raises OQ-OS-1 (shared reference data, PRI `:340`) and revision handling (XD-5). Risk of becoming a geography platform |

[I] ODA-01's "geographic hierarchy as a per-Organisation choice over shared reference data" (§8.2) is compatible with all three. It should not be read as choosing C.

---

## H. Strategic DDD review

| # | Question | Assessment |
|---|---|---|
| 1 | Actual product differentiation | [F] the business case names: diaspora-specific design, multi-language, mobile-first, transparent security, for **elections** (`EXECUTIVE_SUMMARY.md:195-200`; `BUSINESS_CASE.md:335-341`; `COMPETITIVE_ANALYSIS.md:349-353`). **It never mentions constitution-faithful governance or membership** (grep: no "constitution" or "statute") |
| 2 | Most value | [I] on present evidence: trustworthy, anonymous, verifiable **elections** for dispersed organisations. ADR-T11 anonymity and BDR-12 "supreme" invariant support this |
| 3 | Could constitutional identity and lifecycle be core? | [I] Not on current evidence. Any multi-organisation platform needs them. **ODA-01's "foundational supporting" is defensible** |
| 4 | Is constitution-faithful governance the core? | **unsupported as a present fact.** [I] It is a plausible **strategic hypothesis**: a move from "election tool" to "organisation operating system". Only the PO can make that a strategy. ODA-01 states it as an [I] about "PublicDigit's distinctive promise", but no business document makes that promise |
| 5 | Membership: core or supporting? | [F] election-only organisations exist and vote without Membership (`VoterSourceStrategy.php:18-22`). [I] So Membership is **supporting** for the current product. It is core only under the hypothesis in row 4 |
| 6 | Geography: generic or specialised? | [I] **split.** Official reference data is generic (import or buy). Per-organisation hierarchies, custom units and revision pinning in decisions are **specialised supporting**. ODA-01's "thin extension" understates this |
| 7 | Platform / tenancy outside? | **agree** [I]. Caveat: INV-ORG-SCOPE-1 is a security invariant with business weight (fail closed). That does not make tenancy a domain context, but the invariant needs a named owner |
| 8 | Consistent with ADR-004? | ADR-004 classifies **contexts**, not subdomains. Its "Governance" is Election Governance (C-8). **ADR-004 still lists Membership as Candidate** (`:30`), while ACT §11 ratified "context boundaries" and ODA-01 calls Membership "RATIFIED". No ADR supersedes ADR-004 on this. Not flagged by ODA-01 (new conflict C-11, §J) |

**Recommendation [I]:**
- Keep "Organisation = foundational supporting" as a proposal.
- Do **not** present Governance or Membership as core candidates until the PO states the product strategy.
- Before any classification is adopted, reconcile the four classifications (AIP-14, BDR-08, the 2026-10-09 review, P-ODA-8). The classification must say which scope it covers.
- As the commission says, no bounded context should be established because of a classification.

---

## I. Bounded-context review

**Scope coherence.** ODA-01's candidate Organisation context holds:
- identity and lifecycle;
- the constitution record;
- units;
- operating area;
- capability enablement;
- OrganisationAffiliation (§11.2 "Purpose").

[I] **That is a grab-bag.** It mixes constitutional facts with access (affiliation) and commercial enablement, which is the conflation R-4 warns against.
- **Capability enablement** is what the Organisation buys and switches on. That is a platform-service fact: put it with the platform service status.
- **OrganisationAffiliation**: RAT `:42` gives it to "Organisation", but whether that means the *business owner* or *this context* is BR-9 / OQ-C-5 (open).
- **Smallest coherent candidate:** identity, organisational status, the constitutional source record (if O-2) and, on evidence, units (O-16). Operating area waits for evidence. It may simply be derived from units' responsibility areas.

| Relationship | Authoritative fact owner | Supplier → consumer | Domain or technical dependency | Circular? | Published language enough? | Risk of duplicated truth |
|---|---|---|---|---|---|---|
| Organisation ↔ Governance | ORG: identity, record (O-2), units. GOV: organs, competence, authority | ORG → GOV (versions, units); GOV → ORG (authority evidence for amendments and structural acts) | domain | **yes, under O-2.** Under O-1 only the unit link remains | only if both directions are separate, minimal languages and recorded explicitly (GMAP `:153-157` precedent) | **high**: constitutional provisions on organs exist as text (ORG) and as interpreted rules (GOV). E.3 step 1 constraint required |
| Organisation → Membership | ORG: id, status. MEM: facts | ORG → MEM | domain (id); constitution ref only if O-2 | no | yes | low, if MEM keeps only `OrganisationId` |
| Organisation → Election | ORG: id, status, enablement (if kept) | ORG → ELE | domain / technical | no | yes | low. [F] today enablement is overloaded: `uses_full_membership` selects the voter source |
| Geography → Organisation / Governance | GEO: GeoUnits | GEO → ORG, GOV | reference data | no | yes, with pinned revisions | medium: three link types (operating area, responsibility area, jurisdiction), plus Election's free-text regions (§G) |
| Organisation ↔ Platform / tenancy | ORG: id, organisational status. PLT: tenant mapping, Working Organisation, service status | ORG → PLT (§12); PLT → GOV / MEM / ELE (R-15) | technical | **yes, unflagged:** ORG → PLT → GOV → ORG (ODA-01 §12 edges) | — | medium: if platform service status sits in ORG (OQ-ODA-3 open), PLT reads it back |

**Conceptual-only notes [I]:**
- Under ACT `:349`, shared persistence is not *required*. That is not the same as forbidding it. ODA-01 reads it correctly ("not assumed").
- **ODA-01 §12 is a second context map.** CMAP is the PO-approved consolidation point (CMAP header). §12 should be treated as **input to CMAP's change control, not a parallel map**. Otherwise relationship statuses will drift, as C-1 shows labels already do.

---

## J. Conflict register (C-1…C-10, plus new items)

| Conflict | Original claim | Source evidence | Independent assessment | Decision authority | Recommended next action |
|---|---|---|---|---|---|
| **C-1** definition presented as ratified | "recognising body" is labelled ratified but is not | candidate `:3`, `:27`; GMAP `:56`; audit `:112` | **confirmed.** It also propagates to PREP `:67` and AR/`2026-11-03-baseline-stabilisation-and-strategic-decision-preparation-review.md:89`, `:105` | ARB (O-14) | ARB records the correction. The owners of the four documents annotate them (not done here) |
| **C-2** positions / ACT §11 | the commission's summary goes beyond ACT §11 | ACT `:321`; DR07 `:265` | **confirmed.** "Positions" rests on PO-DR07-01 (a PO business ruling) and GMAP (proposal). Also affected by BDR-03 (K-12) | ARB (ARB-2, ARB-8) | include in ARB-2 / ARB-8. Do not assign positions to ACT §11 meanwhile |
| **C-3** ODA-01.7 vs BR-13a | taxonomy only | BR `:39` | **confirmed.** §9 stays taxonomic. But §3.3 stretches BR-13a to succession (C.4) | PO | PO confirms §9 is discovery only. Keep succession an [OQ], not a BR-13a item |
| **C-4** units vs separate Organisations | compatible as alternatives | DR07 `:266`; RAT `:150` | **confirmed and important.** [I] For a diaspora body with country-level bodies, this is the central evidence question. P-ODA-1's tenant sentence would prejudge it | PO on evidence; ARB for consequences | evidence per reference organisation (O7) |
| **C-5** immutable structure / geography | code freezes structure | `Organisation.php:59-75` | **confirmed with correction** (K-1: `geographic_levels` is mutable) | PO / ARB at design time | design input only |
| **C-6** committees = units and organs inside Membership | "contrary to ACT §10" | ACT `:283-305`; DR07 `:312` | **partly.** The context-level basis is ACT §11 plus N-6. The ACT §10 reading is ARB-1, pending (K-6). "Both concepts" is the Principal's position (K-5) | ARB (ARB-1, ARB-2); DR-07a | re-cite. Leave in DR-07a / O-16 |
| **C-7** `governance_status` | platform status overloaded with a Governance gate | `OrganisationGovernanceStatus.php:9-16` | **confirmed and understated**: three meanings, an owner-driven writer, no writer for `active` / `suspended` (K-4) | ARB at design time | record K-4 facts |
| **C-8** two meanings of Governance | ADR-004 vs ACT §11 | ADR-004 `:15`; ACT `:321` | **confirmed** | ARB (O-14) | glossary qualification ("Election Governance" vs "Organisational Governance") |
| **C-9** meanings of Constitution | five or more | PRI §2 `:39-55` | **confirmed and larger.** PRI also lists `CommitteeConstitution`, `ConstitutionalBasis`, the arbitration kernel; ADR-004 "Constitution as SSOT" (`:51`) means the *election* constitution | ARB (O-14) | qualified term "Organisation Constitution (statutes)" [P] is reasonable |
| **C-10** no authoritative "no universal governance model" rule | assumed, not recorded | PRI `:415` (A87) | **confirmed** | PO | PO decides whether it is a platform rule |
| **C-11 (new)** Membership context status | ODA-01 calls Membership "RATIFIED" | ADR-004 `:30` "Candidate"; ACT §11 "Ratified Context Boundaries" | [I] ACT §11 is the later and stronger act, but ADR-004 has not been superseded | ARB | note in CMAP change control or a superseding ADR |
| **C-12 (new)** subdomain classifications | P-ODA-8 | K-9 sources | four inconsistent classifications | PO (strategy) + ARB | reconcile before adopting any |
| **C-13 (new)** Appointment ownership | GOV owns appointments, tenure [P] | BDR `:16`; CMAP `:79-80` | ARB-8 covers more than Mandate | ARB (ARB-8) | widen ARB-8's question |
| **C-14 (new)** bootstrap organisation vs P-ODA-1 | every Organisation is a self-governing body and a tenant | `create_uuid_organisations_table.php:17-18`; BR `:35` | a `type = 'platform'` row is not an Organisation in the proposed sense | PO (glossary meaning), ARB (O-14) | state the exclusion in the definition (C.5) |

---

## K. Reference organisation review

| # | Question | Assessment |
|---|---|---|
| 1 | Do the candidates test the model? | **partly.** Only **NRNA** is a concrete organisation. "A political party", "a small e.V." and "the operator organisation" are archetypes. The operator organisation does not exist and creating it is not authorised (PRI `:103`) |
| 2 | Are three enough? | [I] Three *concrete* organisations that differ on the §14.1 axes, yes. Three archetypes, no. Missing axes: **an organisation without written statutes**, **a member of a federation or umbrella** (to test that the definition does not exclude it; no federation design), and, if the PO puts them in scope, a company or union |
| 3 | Overfitting to NRNA? | **real risk.** Two of four candidates are Nepal-related. [F] The three archetypes match code that already exists (`TenantGeographyProfile::forDiaspora / forNepalParty / forGermanyNGO`, `:60-95`). The selection may confirm prior assumptions rather than test them. [I] (external, unverified in the repository) NRNA is commonly described as an international council with national coordination councils. If so, it tests C-4 directly. That must come from its statutes, not from this note |
| 4 | Are the questions neutral? | **mostly.** O9 presupposes ODA-01's model ("the whole organisation or one unit"). O6 / O7 presuppose "units". There is no question "do you have written statutes at all?" before D1. Reword O9 as an open question |
| 5 | Statutory evidence vs interview interpretation? | **no.** Answers must be "article and version, or SILENT / AMBIGUOUS" (§14.2). But O1–O4, X1 and X3 are mostly **not** in statutes: registry entries, founding acts, current status and representation mandates. An **evidence-type column** is needed (statute · registry extract · minutes · representative's statement). Also add "UNKNOWN / NOT PROVIDED", distinct from "SILENT" (the statute says nothing) |
| 6 | Are representatives authorised? | X3 asks about this. [D] the PO names them (PREP OQ-2). Adequate, provided the mandate is recorded as evidence |
| 7 | Can questions stay unanswered without defaults? | yes for statute questions ("silence never produces a default"). It needs extending to non-statute questions (row 5) |

**Minimum evidence before tactical design [I]:**
1. At least one complete, authoritative, versioned statute from a concrete organisation, plus the D-block metadata.
2. For that organisation:
   - organs and offices with their scopes (S1–S5, O9);
   - unit kinds and whether any is a separate organisation (O6, O7);
   - the amendment procedure and its effective dates (C1, C2);
   - external binding rules (X2).
3. The same from at least two further concrete organisations that differ on legal form and geography, before patterns are read (ODA-01 §14.3 step 4, which is correct).

---

## L. Risk review (corrected and prioritised)

| Priority | ID | Risk | Status of ODA-01's treatment | Needs |
|---|---|---|---|---|
| 1 | R-12 / **R-19 (new)** | **Single decision and review chain.** The same person decides and owns the technical work (PREP §4.4). The baseline and this review come from the same model family | R-12 present. R-19 added | human or different-model review (header) |
| 2 | **R-14 (new)** | **Wrong ARB choice on O-1 / O-2** because the comparison is a straw man and conflates ownership kinds | missing | ARB uses E.3, not §5.3 alone |
| 3 | **R-16 (new)** | **Fail-closed-before-statutes blocks current customers** (D-4 admissions; election calling) | missing; called "consistent" | PO decision (§D q6) |
| 4 | R-9 / **R-13 (new)** | **Tenancy defines the domain**, now *through the definition itself* (§3.1 tenant sentence) | R-9 present; the cause in P-ODA-1 missed | amend P-ODA-1 (C.5) |
| 5 | **R-17 (new)** | **Overfitting** to NRNA, Nepal and the code's existing archetypes | partial (R-6) | broaden the candidates (§K) |
| 6 | **R-15 (new)** | **Classification inflation.** "Core" spreads to Governance and Membership without business evidence, so investment is misdirected | missing | PO strategy statement (§H) |
| 7 | R-8 / **R-18 (new)** | **Duplicated truth and documentation drift.** Parallel maps (ODA-01 §12 vs CMAP vs GMAP) and labels that propagate (C-1 in four documents) | R-8 covers code stores only | route §12 through CMAP change control |
| 8 | R-2, R-3, R-4, R-5, R-6, R-10 | as in ODA-01 | sound. R-10 needs the K-1 correction. R-4 is partly covered by D-4 / PBDIGIT-266 | as ODA-01 |
| 9 | R-1, R-7 | as in ODA-01 | sound, low | — |
| 10 | R-11 | **Over-modelling** | sound, but ODA-01 is itself exposed. Name history, external legal refs 0..n, two four-state lifecycles, three geographic link types and an operating-area concept are all proposed before any statute has been read | treat them as **candidates for evidence**, not model elements |

**Already covered by ratified decisions:** none of the above is covered outright. RAT append-only provenance (`:72`) and "Membership enforces its own invariants" (RAT §6) mitigate R-5 and R-4 for Membership only.

**Needs external evidence:** R-6, R-17, C-4, organ scope, dissolution behaviour.

**Needs PO / ARB decision:** R-14, R-15, R-16, R-13, R-18.

---

## M. Principal / ARB decision register

| Decision | Question | Recommended options | Authority | Evidence needed | Consequence |
|---|---|---|---|---|---|
| M-1 | Record PO-ORG-MEM-01 A / B with their exact wording | choose PREP §3.1 wording, the Principal's restatement, or a combined wording (PREP `:61`) | **PO** | none | lets later documents cite a [D] with a source |
| M-2 | Business meaning of "Organisation" | accept P-ODA-1 with the C.5 amendments / accept as proposed / reject pending evidence | **PO**, then ARB for vocabulary (O-14) | none for the meaning; statutes for continuity details | fixes C-1 and C-14; removes tenancy from the definition |
| M-3 | Is a platform record (bootstrap, demo) an Organisation? | no (recommended, consistent with B1 / BR-12) / yes | **PO** | none | glossary and data agree |
| M-4 | Operate before statutes; interplay with D-4 interim | fail closed for claims of constitutional competence; keep D-4 as an explicitly *interim, non-constitutional* provenance / end D-4 when statutes are recorded / other | **PO** | none | protects current customers; keeps provenance honest |
| M-5 | Geography scope (OQ-ODA-8) | §G options A / B / C | **PO** | the customer list | scope of Geography work |
| M-6 | "No universal governance model" as a platform rule (C-10) | adopt / reject | **PO** | none | constrains CQ-10 |
| M-7 | Is Governance capability optional (OQ-ODA-9)? | optional / mandatory | **PO** | none | enablement design |
| M-8 | Product strategy for subdomain classification | election-integrity core (current evidence) / organisation-governance core (hypothesis) | **PO**, then ARB to reconcile C-12 | business strategy | prevents R-15 |
| M-9 | Reference organisations and representatives | ≥ 3 concrete organisations, broadened per §K | **PO** | — | starts evidence |
| M-10 | Confirm §9 / ODA-01.7 is discovery only; succession stays [OQ] | confirm | **PO** | — | C-3 |
| M-11 | Reading of ACT §11 "organisational governance rules": does it include the source text? | yes → O-1 / no → O-2 eligible | **ARB** | none | hinge for E.3 |
| M-12 | Adopt the four-way separation (source / interpretation / authority evidence / operational rule) and `(document, article, version)` citation | adopt / amend | **ARB** | none | placement-neutral; unblocks P11 framing |
| M-13 | Organisation context: does it move from Candidate (ADR-004)? With what minimal scope? | defer until M-11 and evidence / establish minimal scope (§I) | **ARB** | ≥ 1 statute | precondition for O-2 |
| M-14 | Platform service status and enablement placement (OQ-ODA-3) | Platform / Organisation | **ARB** | XD-15 | removes K-4 overload |
| M-15 | ARB-1 / ARB-2 / ARB-8 readings, widened to include positions, appointments and tenure (C-2, C-6, C-13) | per DR07 §17 | **ARB** | DR-07a report | clears C-2, C-6, C-13 |
| M-16 | O-14 vocabulary: C-1, C-8, C-9, C-11, "Affiliation" between organisations, "jurisdiction" | — | **ARB** | — | one glossary |
| M-17 | Treat ODA-01 §12 as input to CMAP change control | yes (recommended) | **ARB** (CMAP owner: PO) | — | prevents R-18 |
| E-1 | Is a given section a unit or a separate Organisation? | — | **external evidence**, then PO | statutes O6, O7; registry extracts | C-4 |
| E-2 | Organ scope cardinality; offices outside organs | — | **external evidence** | S1–S5, O9 (reworded) | §F rows 2 and 5 |
| E-3 | Amendment procedure and effective dates; one document or several | — | **external evidence** | C1, C2, D4 | E.3 step 4 |
| E-4 | External binding rules (law, parent, federation) | — | **external evidence** | X2 | definition C.2 |
| E-5 | Dormancy, dissolution, liquidation, succession provisions | — | **external evidence** | O3, O4 | §C.4 |
| E-6 | Same structure in every country? | — | **external evidence** | O8 | §G |

---

## N. Final recommendation

**Recommended sequence:**
1. **Now, in parallel (no dependency between them):**
   - PO: M-1, then M-2, M-3, M-4, M-5, M-6, M-9, M-10.
   - Revise the questionnaire per §K: evidence-type column; UNKNOWN distinct from SILENT; neutral O9; a "written statutes?" gate.
   - Have this review and ODA-01 checked by a human or a different-model reviewer.
   - Have ODA-01's author record the K-1…K-14 corrections, as errata or a revision. This review does not edit ODA-01.
2. **ARB, early (readings only):** M-11, M-12, M-15, M-16, M-17. None needs external evidence.
3. **Evidence:** collect statutes and answers from at least one, then three concrete organisations (E-1…E-6).
4. **ARB, after evidence:** M-13 (context status and minimal scope), O-1 / O-2 placement per E.3, M-14, M-8 reconciliation.
5. **Only then:** tactical design of the Organisation context (aggregates, invariants, storage), under EP-01 with separate authorisation.

**Can proceed immediately:** steps 1 and 2. **Must wait:**
- O-1 / O-2 placement;
- establishing the Organisation context;
- adopting any subdomain classification;
- unit ownership (O-16; not resolved here);
- organ ownership (DR-07a; not resolved here);
- any aggregate, schema or code.

**Not done by this review:** no change to ODA-01, CMAP, PRI, ADRs, BDR, ratified records, ACT §10 / §11 or business rules. O-16 and DR-07a are not resolved. No federation design. No contact with any organisation. No test, migration or database access. No commit or push.

---

## Traceability

- **Commission:** IR-ODA-01 (Principal / PO, 2026-11-03).
- **Reviewed:** ODA-01 (sha256 above).
- **Ratified:** ACT `:9`, `:19`, `:283-305`, `:309-349`; RAT `:42-43`, `:72`, `:140-150`, `:156`.
- **PO decisions:** DR07 §14 `:222-254`, §15 `:261-291`, §17 `:308-320`, §19 `:332-343`; BR `:23-40`; `20260806_0818_how_many_organisation.md:61-75`, `:145-153`.
- **Same-day documents:** PREP `:45-115`, `:145-246`, `:299`, `:338-364`; DR-07a report `:1-14`, `:330`.
- **Proposals:** CMAP §4–§5; GMAP `:50-70`, `:100-122`, `:150-160`; PRI `:39-56`, `:99-105`, `:336-342`, `:410-418`; ADR-004 `:1-70`; BDR `:1-60`; XD-15 proposal §8; AR/`2026-11-03-publicdigit-architecture-assessment.md:100-108`; business case (`EXECUTIVE_SUMMARY.md`, `BUSINESS_CASE.md`, `COMPETITIVE_ANALYSIS.md`).
- **Code:** `app/Models/Organisation.php`; `app/Domain/ValueObjects/OrganisationGovernanceStatus.php`; `app/Http/Controllers/GovernanceSetupController.php`; `app/Policies/OrganisationPolicy.php`; `app/Contexts/Membership/Application/Committee/Policies/GovernanceCapabilityContextFactory.php`; `app/Contexts/Geography/Domain/ValueObjects/GeographicStructure.php`; `app/Contexts/Geography/Domain/Models/TenantGeographyProfile.php`; `app/Contexts/Governance/Domain/**`; `app/Domain/Election/Enum/VoterSourceStrategy.php`; migrations `2026_03_05_000001`, `2026_03_05_000005`, `2026_04_15_120000`, `2026_05_04_000001`, `2026_05_05_000002`, `2026_05_07_000001`, `2026_05_13_134529`; Geography Landlord `2025_01_01_000002`, `2026_01_14_000003`, `2026_05_11_000001`; Membership Tenant `2026_01_16_000002`.
- **Legacy:** `architecture_legacy/committee/geo_committe_formation/20260513_1257_geo_unit_matrix.md:5-12`.

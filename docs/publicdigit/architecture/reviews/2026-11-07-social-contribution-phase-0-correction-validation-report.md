# Social Contribution, Phase 0: validation of corrections C-1 to C-13

> **This is a validation report. It does not apply any correction and approves nothing.** C-1 to C-13 remain **proposals** until the Principal explicitly approves them. No existing document, backlog story, README, session history or commit was changed while preparing it.

| | |
|---|---|
| **Status** | VALIDATION REPORT, for Principal review. Not independently reviewed. |
| **Authority** | generated (Claude, Session A; Principal DDD Architecture Assistant role). Not authoritative without human review. |
| **Commissioned by** | Principal Instruction "Social Contribution: Validate C-1 to C-13 and Prepare Controlled Documentary Corrections" (2026-11-07, read-only). The user then asked for this report to be recorded as review documentation (2026-11-07). |
| **Story** | `PBDIGIT-334` (Phase 0) |
| **Validates** | The correction proposal C-1 to C-13 (Session A, 2026-11-07, given in session, not persisted) against: **XPD** `2026-11-07-social-contribution-cross-project-discovery.md`, **PH2** `2026-11-07-social-contribution-domain-discovery-phase-2.md`, **PLAN** `2026-11-07-social-contribution-initiative-story-and-development-plan.md`, and `PBDIGIT-331` … `PBDIGIT-334` |
| **Date** | 2026-11-07 |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |

**Labels.**
- [F] verified fact, with source.
- [D] existing decision, with authority.
- [P] proposal.
- [OQ] open question.

**Validation results.**
- VALID: the correction is supported as proposed.
- VALID WITH AMENDMENT: supported, but the wording must change before it is applied.
- NOT VALID: the correction is not supported.

---

## 1. Summary

1. **[F] Thirteen corrections checked.**
   - 7 are **VALID**: C-1, C-2, C-5, C-7, C-9, C-10, C-13.
   - 6 are **VALID WITH AMENDMENT**: C-3, C-4, C-6, C-8, C-11, C-12.
   - None is NOT VALID.
2. **[F] All 22 quoted passages** in the correction proposal match the current documents exactly, once each.
3. **[F] Most important amendments:**
   - **C-11** missed three mentions of the security observation in PLAN (L267, L285, L402).
   - **C-11** would also have relabelled S-3 as "O-1", which collides with identity option O-1.
   - **C-12**'s register had imprecise or incomplete line references for S4, S5 and S10, and a misleading description for S12.
4. **[F] No factual error was found in the cross-project evidence findings.** Every correction concerns wording, maturity labels, phase structure, superseded recommendations, or the source register.
5. **[F] The architectural boundaries** (§6) hold in the documents before and after the corrections.

---

## 2. Git state recorded before validation

| Item | Value |
|---|---|
| Branch | `election-flow-updated` |
| HEAD | `43624fe1c` (2026-11-07 15:00:38 +0100). Intact; not pushed. |
| Files in `43624fe1c` | XPD, PH2, PLAN, `PBDIGIT-331` … `PBDIGIT-334`, `docs/publicdigit/backlog/README.md` |
| Changes to those 8 files since `43624fe1c` | none |
| Other uncommitted work | 17 modified and 21 untracked entries (38 lines of `git status --porcelain`), none from this initiative. |
| Untracked file noticed during the session | `tests/Isolated/Pbdigit274/Database/VoteAnonymityLinkageTest.php`, from other work; untouched |
| Session log | `.claude/sessions/2026-11-07.md` is untracked and contains entries from other work; append-only |

---

## 3. Correction validation (Part A)

| ID | Result | Evidence (verified 2026-11-07) | Required adjustment | Affected files |
|---|---|---|---|---|
| **C-1** Identity anchor framing | VALID | PLAN L221 "the identity anchor is the gating decision for the whole initiative"; options at L225-227. BR-13a (`docs/publicdigit/business_rules/20261101_1739_person_account_affiliation_business_rules.md` L39), INV-ORG-SCOPE-1, XD-3a and XD-3b (both PROPOSED) and the ratified Membership architecture exist and remain unchanged. | None. Insert the business-implications table (§8 of this report) with the sentence "All three options remain open; none is selected". | PLAN §7.2 |
| **C-2** Gate wording | VALID | PLAN L317 "must be decided before strategic DDD can name an owner". | None | PLAN §10 |
| **C-3** Overstated identity maturity | VALID WITH AMENDMENT | PLAN L95-96. **Isolation:** `tests/Architecture/MembershipLedgerIsolationTest.php` exists. **Not wired to HTTP:** no reference in `routes/` or `app/Http/`. **Release gate:** `database/migrations/2026_11_03_120000_extend_org_membership_facts_for_corrections.php` L22-26 ("must not run on any persistent database before OQ-01, the §10.2 operator preflight, the lc_messages check and ARB-MEM-01 … Until then it runs only on the PBDIGIT-324 throwaway test cluster"); backlog README L143 (PBDIGIT-264: "release gate for the migrations stays open"). | Item 2 to read: "(Membership kernel, isolated by `MembershipLedgerIsolationTest`; not wired to HTTP; its migrations sit under the open ledger-migration release gate: PBDIGIT-264; migration header `2026_11_03_120000…` L22-26)". Align PLAN §7.5 L246 with the same citation. | PLAN §4, §7.5 |
| **C-4** Maturity ladder | VALID WITH AMENDMENT | `app/Contexts/Membership/Domain/Organisational/PersonRef.php` exists. XD-3b "PROPOSED, pending governance approval" (L4). Ratification act L313: Identity "Owns Person identity and identity equivalence". | Level 3 to read: "**Identity bounded context:** ownership of person identity assigned by the ratified Membership architecture (ratification act L313); **the context is not implemented**." This separates ownership assigned from capability built. | PLAN §7.2 |
| **C-5** PD-1 classification | VALID | PLAN L117, L303, L151. | None | PLAN §5.1, §5.4, §9 |
| **C-6** Profile as a candidate solution | VALID WITH AMENDMENT | PLAN L45; PH2 L273, L385; titles of `PBDIGIT-331` and `PBDIGIT-333` (L1). | **Required:** PLAN §3 need-vs-solution block; PH2 §3, §6 L273, §7F, §10 L385; titles of 331 and 333. **Filenames keep "profile"** so that IDs and links are preserved; record this explicitly. **Optional editorial:** PLAN §8 row 5 label and §10 item 2. PLAN L45 (Decision A) is **not rewritten**; an annotation is appended. XPD unchanged. | PLAN, PH2, 331, 333; README (Principal decision) |
| **C-7** Certificate, recognition, evidence | VALID | PH2 L175, L180-191, L188; PLAN L284. | None. The replacement for PH2 L175 is a question. The ruling states defaults ("not automatically") and introduces **no automatic verification rule**. | PH2 §5.3, §5.4, §6, §7D; PLAN §8 |
| **C-8** Phase structure | VALID WITH AMENDMENT | PH2 L1 (title), L13 (relation row); PLAN L332-333, L339; PH2 W5/W6 L435-436. | (a) **PH2 L9 is not changed.** It quotes the historical instruction title "Social Contribution Discovery — Phase 2" (traceability). (b) Split W4 into **W4a** (individual synthesis, after W1–W3) and **W4b** (final synthesis, after W5). (c) No other phase references in the seven documents require change; all were checked. | PH2 L1, L13, §13; PLAN §11 |
| **C-9** 331/333 dependency | VALID | `PBDIGIT-333` L7; `PBDIGIT-331` L66 (§6 item 4); README L212. | None. Order: 331 interviews (W1–W3) → 333 generalisation (W5) → 331 final synthesis and anchor-decision package (W4b). This is a partial dependency, not a cycle. IDs and links preserved. | 331 §6; 333 header; README L212 |
| **C-10** XPD recommendation superseded | VALID | XPD L535. | None. Annotate; do not rewrite. | XPD §15 |
| **C-11** S-1 outside the initiative | VALID WITH AMENDMENT | PLAN L371-373 and L389, **plus three mentions missed by the proposal:** L267 (§7.7 "precedent to avoid (§12, S-1)"), L285 (§8 row 5 "legacy `/user/{profile}` unsafe"), L402 (traceability spot-check list "S-1; S-2"). | (a) L267 → "There is no sanctioned profile surface; legacy profile routes are not a precedent." (b) L285 → "None sanctioned". (c) L402 → remove "S-1; S-2" from the spot-check list. (d) Do **not** relabel S-3 as "O-1" (collides with identity option O-1); use **"X-1 (out-of-scope architecture observation)"**. (e) Add: "Removal from this document does not resolve or downgrade the observation; it was escalated separately." (f) Commit `43624fe1c`, git history and the session log are untouched. | PLAN §7.7, §8, §12, §13, Traceability |
| **C-12** public-digit source register | VALID WITH AMENDMENT | All 15 paths exist at `a766ef5` (§4). | (a) Method: **Reference → Extract → Classify → Evaluate → Rewrite in nrna1 at the authorised phase**. public-digit documents are **not authoritative** for nrna1. Rewriting happens **only when the consuming phase is authorised**. **No snapshot** without Principal approval. (b) Line-reference fixes for S4, S5, S10 and S12 (§4). (c) Each register row names the nrna1 artefact type; the concrete file is created only in the authorised phase. | PLAN new §5.5; `PBDIGIT-334` new §5; `PBDIGIT-331` §4 (one line) |
| **C-13** Product scope after the nrna1 direction | VALID | PH2 L37 (§0.3), L412 (OQ-S1), L446 (§14 row 1). | None. "Partly answered" reflects only the decided target-project direction. The identity anchor and the person-scoped scope remain open. | PH2 §0, §12, §14 |

---

## 4. Source register validation (Part B)

**Repository:** `public-digit` at `a766ef5f2`. Each line range was checked to contain the claimed content (grep of the distinctive terms within the range).

**Transfer method:** Reference → Extract → Classify → Evaluate → Rewrite in nrna1 at the authorised phase. Every transferred idea carries `Source: public-digit@a766ef5f2:<path>#L<a>-<b> · class <X>`. The `chatgpt/` duplicate documents are deliberately excluded.

| # | Path (repository-relative) | Range(s) | What it describes | Maturity | Class | Target phase / nrna1 artefact type | Result |
|---|---|---|---|---|---|---|---|
| S1 | `architecture/backend/tenant_database/architectural_discussion/20251211_2056_tenant_templates_poltical_parties_nepal.md` | L575-596 · L799-850 · L1013-1030 · L1065-1085 · L187-205 · L465 | member activities with verification; campaign activities and volunteers; meeting attendance; event volunteers; media files; honorary type | documented | ADAPT (activity, attendance) · REJECT (points, rating, media files, honorary) | Phase 1 glossary; Phase 4 tactical design | ✓ |
| S2 | `architecture/backend/tenant_database/political_parties_nepal/20250612_0847_all_political_parties_scheme.md` | L1087-1141 · L519-529 · L601-633 | evidence documents with hash and verification; verification method and level; membership history | documented (filename date unreliable; first committed 2025-12-11) | ADAPT · history superseded by the nrna1 ledger pattern | Phase 2 capability notes; Phase 4 | ✓ |
| S3 | `architecture/backend/membership-context/brainstorming/20260101_2309_architecture_review.md` | L97-128 · L180-209 · L211-227 · L273-298 | Identity ≠ Membership; immutable versioned documents; snapshot at activity time; derived/explainable score principle | documented | REUSE (principles) | Phase 1 constraints; Phase 2 | ✓ |
| S4 | `architecture/backend/membership-context/brainstorming/20260101_2313_membership_related_stuff.md` | L29-40 · **L121-126** | "IS NOT" list; Invariant 4 financial neutrality | documented | REUSE (principle) | Phase 2 | **Amended: L121-126 added** |
| S5 | `architecture/backend/membership-context/brainstorming/20260101_1536_memership_extension.md` | **L37 · L43 · L518-519 · L619** · L539 | pre-signed upload (L37); 60-second viewing link (L43); pre-signed pattern and AES-256 server-side encryption (L518-519); secure viewer (L619); proposer/seconder (L539) | documented | ADAPT | Phase 1 attestation question; Phase 4 evidence access | **Amended: "L514-520" was imprecise** |
| S6 | `architecture/backend/digitcalcard_contexts/phase_1/20251225_2316_tables_necessary_for_digitalcard_context.md` | L47-77 · L121-122 · L224-260 | hashed QR and revocation lifecycle; retention category; signed validation bundles | documented (implementation claimed, unverifiable) | ADAPT (recognition records) | Phase 2; Phase 4 | ✓ |
| S7 | `architecture/backend/tenant_database/political_parties_nepal/20251217_1712_claude_cli_instructions_for_membership_digitilazation.md` | L360-440 · L683 | initiatives with target outcome; owner views own ledger | proposed | ADAPT · REUSE | Phase 1 glossary; Phase 2 | ✓ |
| S8 | `architecture/backend/tenant_database/political_parties_nepal/20251217_1728_sql_schema_prompt_instructions.md` | L495 · L501-511 · L515-560 | profile visibility; skill verification; points tables | documented | ADAPT · REJECT (points) | Phase 1 visibility questions | ✓ |
| S9 | `architecture/backend/tenant_database/political_parties_nepal/20251217_1634_political_parties_schema.md` | L524-548 · L604-680 | member profiles and visibility; activity logs, balances, rank tiers | documented | ADAPT (visibility idea) · REJECT | reference only | ✓ |
| S10 | `architecture/scoring/` (four files, all first committed 2026-01-02) | **`scoring_algorithm.md` L13-37** · **`20260101_1206_score_idea.md` L11-42** · **`dashboard.md` L11-53** · **`scoring_algorithm_01.md` L17, L49-53** | weighted scores, time decay, penalties; four pillars; leaderboard and promotion bonus; points for verifying others; escrow/cooldown | documented | **REJECT** (cited only as anti-examples) | Phase 2 note only | **Amended: per-file ranges added** |
| S11 | `developer_guide/laravel-backend/multi-tenancy/02-template-system.md` | L60-115 | NGO projects; named beneficiaries without consent; donors | example | REWRITE | Phase 1 beneficiary questions; Phase 4 | ✓ |
| S12 | `developer_guide/laravel-backend/membership-context/20260104_0028_member_approval_rejection_system.md` | L59-87 (events at L71, L85) · L298-324 · L423 | approve/reject with actor and reason; **authorisation gate `can:manage-members` and per-decision audit fields**; auto-approval idea | unverifiable | ADAPT · REJECT (auto-approval) | Phase 4 | **Amended: L298-324 description** |
| S13 | `architecture/backend/membership-context/brainstorming/scopebased_concept/20260102_1111_scopebased_membership_context.md` | L1-60 · L283 | scope-based membership; diaspora scope | proposed | ADAPT | Phase 2 | ✓ |
| S14 | `architecture/backend/membership-context/brainstorming/geography_ready_concept/20260102_0146_geography_ready_membership_architecture_from_simple_to_complex_geography.md` | L316-345 | verification method (field visit, testimony), `verified_by` | documented | ADAPT | Phase 1 question; Phase 2 | ✓ |
| S15 | `apps/mobile/src/app/features/membership/models/member.models.ts` | L16-55 · L87-94 · L186 | profile with bio; verification request with document URLs; privacy level | TypeScript types, no consumer | INVESTIGATE | reference only | ✓ |

**Missing references:** none of substance. **Inaccurate or incomplete:** S4, S5, S10, S12 (amended above).

---

## 5. Cross-document consistency (Part C, state after the corrections)

| Document | Placement | Maturity claims | Phase numbering | Identity and ownership | Recognition / evidence / verification / impact | Backlog dependencies | Accidental authorisation | Residual note |
|---|---|---|---|---|---|---|---|---|
| XPD | ✓ | ✓ (no factual error) | n/a | ✓ | ✓ | n/a | ✓ after C-10 | — |
| PH2 | ✓ after C-13 | ✓ | ✓ after C-8 (L9 kept as history) | ✓ after C-13 | ✓ after C-7 | ✓ after C-8 (W4a/W4b) | ✓ | filename keeps "phase-2" (links) |
| PLAN | ✓ | ✓ after C-3, C-4, C-5 | ✓ after C-8 | ✓ after C-1, C-2 | ✓ after C-7 | ✓ after C-8 | ✓ | security observation removed in all five places after amended C-11 |
| PBDIGIT-331 | ✓ | ✓ | ✓ | ✓ (title per C-6) | ✓ | ✓ after C-9 | ✓ | filename keeps "profile" |
| PBDIGIT-332 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ (no module change) | — |
| PBDIGIT-333 | ✓ | ✓ | ✓ | ✓ (title per C-6) | ✓ | ✓ after C-9 | ✓ | filename keeps "profile" |
| PBDIGIT-334 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | gains §5 per C-12 |

---

## 6. Architectural boundary checks

| Boundary | Holds? | Where |
|---|---|---|
| Social contribution ≠ PBDIGIT-330 office-holder performance | ✓ (strengthened by C-10) | PH2 §4; PLAN §2 |
| ≠ financial donation | ✓ | PH2 §5.7; XPD §4.8 |
| ≠ political participation, ≠ forum contribution | ✓ | PH2 §5.9, §5.10 |
| Effort, activity, recognition, outcome, impact distinct | ✓ | PH2 §5, §9 |
| No universal score, leaderboard or ranking authorised | ✓ | PLAN §8 row 8, §9 |
| Verification authority unresolved | ✓ | PLAN §7.4; PH2 OQ-V1 |
| No bounded context approved | ✓ | PH2 §10 (hypotheses) |
| No Identity capability assumed to exist | ✓ after C-3, C-4 | PLAN §4, §7.2 |
| Contribution Points module = historical experiment pending PO | ✓ | `PBDIGIT-332`; PLAN §1 |
| Ratified Membership architecture not reopened | ✓ | PLAN §7.2 |
| ODA-01 and Adjudication outside the assignment | ✓ | PLAN §7.1, §10 |

---

## 7. Execution plan, only on Principal approval (Part D)

**Before editing:** record the branch, HEAD, `git show --name-only 43624fe1c` and `git status --porcelain`. Confirm that none of the target files has uncommitted changes.

**Required corrections:**

| File | Sections and lines |
|---|---|
| PLAN | §1 L45 annotation, §3 need-vs-solution block (C-6) · §4 L95-96 (C-3) · §5.1 L117, §5.4 L151, §9 L303 (C-5) · new §5.5 register and method (C-12) · §7.2 L221 + implications table + maturity ladder (C-1, C-4) · §7.5 L246 citation (C-3) · §7.7 L267 (C-11) · §8 L284 (C-7), L285 (C-11) · §10 item 3 L317 (C-2) · §11 L332-333, delete L339 (C-8) · §12 L371-373 → pointer + X-1 (C-11) · §13 delete row 9, L389 (C-11) · Traceability L402 (C-11) |
| PH2 | L1 title, L13 relation row (C-8) · §0.3 L37, OQ-S1 L412, §14 row 1 L446 (C-13) · §3, §6 L273, §7F, §10 L385 (C-6) · §5.3 L175, §5.4 ruling and L188 note, §6 observation 1, §7D (C-7) · §13 W4a/W4b, W5, W6 (C-8) |
| XPD | §15 L535 annotation only (C-10) |
| `PBDIGIT-331` | L1 title, §3 line (C-6) · §4 register pointer (C-12) · §6 item 4, L66 (C-9) |
| `PBDIGIT-333` | L1 title (C-6) · L7 status (C-9) |
| `PBDIGIT-334` | new §5 "Source documents (public-digit)" (C-12) |
| backlog `README.md` | L212 status (C-9) · titles for 331 and 333 **only if approved** |

**Optional editorial:** PLAN §8 row 5 label and §10 item 2, adding "(candidate form)".

**Commit control:**
- One documentation commit, staging only the explicit paths above (at most seven files; `PBDIGIT-332` unchanged).
- No session log, no other working-tree file, no amend, no history rewrite, no push.

**After editing:**
- Repeat the git-status comparison.
- Grep that "S-1", "/user/{profile}" and "gating decision for the whole initiative" no longer occur in the seven documents.
- Re-check every register line reference.

**Reserved; not decided by this pass:**
- the identity anchor (PO + ARB);
- `PBDIGIT-332` (PO);
- consent and naming (Principal);
- Product Owner confirmation;
- Phase 1 authorisation;
- the S-1 security assignment.

---

## 8. Business implications of the identity-anchor options (for C-1)

This is analysis only. **No option is selected.**

| Implication | O-1 Member-scoped | O-2 Person-scoped | O-3 Person as own organisation |
|---|---|---|---|
| Who owns the history | The organisation (its record about its member) | The individual | The individual, as an organisation |
| Who controls visibility | The organisation under its rules; the individual's say depends on its policy [OQ] | The individual | The individual, within their own organisation |
| Individual consent | Organisation processes the data; consent rules [OQ] | The individual is the source; third-party data still needs consent | The individual; third-party data still needs consent |
| Portability to another party | None by design; export only by organisation decision [OQ] | Inherent | Inherent (independent of any party) |
| Access by several parties | Each party keeps its own separate record; no sharing (BR-13a) | Parties as audiences; needs an exception to BR-13a and INV-AFF-1, and XD-3a C-1 | Party members must be given access to her organisation, or a published view; mechanism undefined |
| Who may verify | Organisational organs or office holders (PO-ORG-MEM-01); their competence over activities outside the party is questionable [OQ] | Verifiers from several organisations and institutions; no cross-organisation authority model exists | External verifiers would act inside her organisation; the authority meaning is stretched [OQ] |
| When membership ends | The record stays with the organisation; visibility afterwards undefined [OQ] | Unaffected | Unaffected |
| Cross-organisation information access | None; INV-ORG-SCOPE-1 §1a respected | Required; needs Identity (XD-3a/3b), a §1a amendment and data-protection review; political-affiliation risk (XD-3a L84) | Avoided if audiences join or a public view is used; stretches the meaning of "organisation" (ODA-01 §11.2); PO acceptance needed |

**Constraints kept under all three options:** BR-13a, INV-ORG-SCOPE-1 §1a, XD-3a, XD-3b and the ratified Membership architecture remain unchanged. The options differ only in which of them would need new decisions.

---

## 9. Outstanding decisions (Part E)

| # | Decision | Recommendation (not an approval) |
|---|---|---|
| 1 | Approve C-1 to C-13 **with the amendments in §3 and §4** | Approve as amended |
| 2 | README title changes for `PBDIGIT-331` and `PBDIGIT-333` | Yes, title wording only; filenames and IDs unchanged |
| 3 | Optional editorial changes | Principal's preference; not needed for consistency |
| 4 | public-digit frozen snapshot | **No snapshot.** Reference `a766ef5f2`; ask the repository owner to keep public-digit read-only. **This is a recommendation, not an approval.** |
| 5 | Handling of the S-1 detail already contained in `43624fe1c`, before any push | Principal's decision. Editing the document does not remove it from history. If it must not reach the remote, a separate explicit instruction is needed **before** any push. |
| 6 | Separate security assignment for S-1 | Yes, independent of this initiative and not waiting for it. Removal from the plan is **not** resolution of the risk. |

---

**Traceability.**
- Correction proposal C-1 to C-13 (Session A, 2026-11-07).
- Principal Instruction "Validate C-1 to C-13 …" (2026-11-07).
- XPD · PH2 · PLAN · `PBDIGIT-331` … `PBDIGIT-334` · commit `43624fe1c`.
- `public-digit@a766ef5f2`.

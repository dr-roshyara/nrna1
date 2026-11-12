# IR-P-01: independent review of the Platform (tenancy) tactical design

| | |
|---|---|
| **Commissioning authority** | The Principal (D-94), 2026-11-11; acceptance deferred until this review (D-178 record) |
| **Status** | **COMMISSION, READY TO HAND OVER** |
| **Reviewer** | **Independent:** not Claude, **not DeepSeek**, **not run inside Claude Code**: e.g. GPT-6 in ChatGPT (as for IR-R1-02), Gemini or Mistral in its own interface, or a human architect |
| **Subject** | `docs/publicdigit/architecture/reviews/2026-11-11-platform-tenancy-tactical-design.md`. **Revision 2 governs** (sections after "Revision 2", with the D-174 … D-176 decision notes). Its first sections are revision 1 and are superseded where revision 2 differs. **The file's last paragraph (status note "not yet accepted") is the only change after the reviewed fingerprint `6908c15d…`.** |
| **Not in scope** | Redesign; other contexts; implementation. **A design review proves neither implementation correctness nor test execution** |

## 1. Files (`MANIFEST.sha256` authoritative)

| # | File | Role |
|---|---|---|
| 01 | this commission | start here |
| 02 | **subject:** Platform (tenancy) tactical design | revision 2 governs |
| 03 | decision record `…/2026-11-08-organisation-strategic-discovery-record-structure-mandates-conventions.md` | **D-1a, D-73, D-113, D-148, D-159, D-160, D-164, D-166, D-167, D-170, D-173 … D-178** |
| 04 | strategic baseline acceptance act (D-173) | Platform ownership, change control |
| 05 | multi-database boundary design (accepted, D-178) | ISO-1 … ISO-10, INV-ACCESS-1 criteria, §13–§16 |
| 06 | H-1 analysis (D-167, INV-ACCESS-1 §H, D-166 controls §D) | cross-cutting rules |
| 07 | post-correction coherence review (Platform positioning, Part 2) | context map |
| 08 | I-TDD-01 Identity design (P; superseded where D-113 applies; see its closing note) | Account, claim, binding |
| 09 | XD-3 ARB act (RAT) | identity rules |
| 10 | Governance thin slice (accepted, D-157) | AuthorityCheck, designations |

## 2. Questions

1. **Boundaries:** does Platform own **only** what D-173 gives it (registry, locator, status / `schemaState`, service-operation grant record, pending case grants, platform contacts per D-174 / D-176, counterparty record per D-175)? **Does it take over anything of Identity, Organisation or Governance?** Does anything grant authority (D-1a, D-167)?
2. **Lifecycle:** are platform status, `schemaState` and health kept apart, with no health observation changing status? Is `closed` never "delete"? Are the joining steps (D-148, D-159, D-160, D-175) correctly ordered and gated?
3. **Aggregates:** is **one `ServedOrganisation` aggregate** justified by its invariants ("`operational` only with grant, verified counterparty and at least one contact"; locator written only by provisioning; hostnames unique)? Is **`PendingCaseGrant`** (given → active → closed, copied flag, per-use checks) sufficient?
4. **D-174 / D-176 (emergency route):**
   - can a revoked or stale contact misuse the route beyond what D-176 accepts?
   - are ordered updates, direct registration (contact or counterparty citing the decision), immediate reporting and withdrawal sufficient?
   - **is anything still forgeable**, e.g. a direct registration by a counterparty who is not entitled to change contacts?
5. **Initial contacts (R-B):**
   - is referencing contacts by **Identity Account** before the organisation database exists consistent with D-113 / D-110 (no duplicate Person)?
   - is the transition to the organisation's own records sound?
6. **Counterparty (D-175, R-C):** are identity, link, commercial acceptance and constitutional authority kept apart? Does the counterparty gain any organisational power?
7. **INV-ACCESS-1 (D-166, D-170):** does any path let staff read content without a case grant, including emergency grants, backups and restores?
8. **Routing and failure:** is fail-closed resolution complete (unknown, not operational, schema failed, not bound, locator tampered)? Are the 12 scenarios (R-F) correct? Is any cross-database transaction assumed?
9. **Contradictions** with accepted decisions or designs (05, 10), invented rules, or claims stronger than evidence.

## 3. Report

Title: **"IR-P-01 — Independent Review: Platform (Tenancy) Tactical Design"**.
1. **Per question:** CONFIRMED · PARTIAL · MISSING · CONTRADICTORY · NOT ASSESSABLE, with **file, section and line**.
2. **Findings** (high, medium, low): evidence, fact or inference, impact, **whether a Principal decision is needed**.
3. **Verdict:** READY FOR PRINCIPAL ACCEPTANCE · READY AFTER CORRECTIONS · NOT READY.
4. **Reviewer identity** (human, or model, version and interface), prior involvement, integrity method.

**Rules:**
- Decide nothing for the Principal.
- Modify nothing.
- Claim no acceptance.
- Mark anything not in the package UNAVAILABLE.
- **Stop after the report.**

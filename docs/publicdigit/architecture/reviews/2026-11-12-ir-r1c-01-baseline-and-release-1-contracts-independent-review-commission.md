# IR-R1C-01: independent review of the strategic baseline v1.1 (rev 4) and the release-1 contract additions

| | |
|---|---|
| **Commissioning authority** | The Principal (D-94), 2026-11-12 |
| **Reviewer** | **GPT-6 in ChatGPT.** Not Claude. **Prior involvement:** IR-GM-01 and IR-GM-01-R1 (Governance completion, M-TDD-01, ledger clock); not these two subjects |
| **Subjects (frozen)** | **S1** strategic DDD baseline v1.1, **revision 4**: `8e74bb6dc45964b6090834cb09c03ac4f71d6b0988ff2fa189310251142e8080` (file 02)<br>**S2** release-1 tactical completion and implementation blueprint, **revision 2**: `ca5fad2ba1a24b44ab3a38ffeeed738baf2d4a69f9aad963a74d1bc4c5302217` (file 03). **In scope: §4 (T-1, T-2, T-4), §5 (flows), §6 (code conformance, incl. K-C1)**; §7–§10 for consistency only |
| **Status** | both **proposed**; S1 awaits ratification, S2's contracts await ARB acceptance. **No implementation authorised; no proof executed.** Both written by Claude, not independent |

## 1. Files (`MANIFEST.sha256` authoritative)

| # | File | Status |
|---|---|---|
| 01 | this commission | — |
| 02 / 03 | **S1**, **S2** | proposed |
| 04 / 05 | S1 rev 3 (`9a691140…`); S2 rev 1 (`6076153c…`) | historical, for diff |
| 06 / 07 | unified diffs S1 rev 3 → 4; S2 rev 1 → 2 | — |
| 08 | **decision record** (through D-213, §11.112) | **binding** |
| 09 | strategic baseline acceptance act (D-173) | accepted |
| 10 | post-correction baseline review (the accepted context table, Part 2) | accepted basis of D-173 |
| 11 | strategic assessment 2026-11-12 (source of D-211, D-212) | assessment |
| 12 | ratified Membership architecture | **RAT** |
| 13 / 14 / 15 | C1/C2 specification (D-162, with D-209 pointer) · CR-01 · CR-02 | accepted |
| 16 | ledger-clock amendment rev 2 (D-209) | accepted |
| 17 | **M-TDD-01 rev 4 (D-210)**: K-1 … K-9, §7 newsletter, §8 forum, §11 refusal | accepted |
| 18 | Governance completion rev 5 (D-208) | accepted |
| 19 | Governance thin slice (D-157 + additions): §6.1 Position / functional offices, §6.2 AuthorityCheck | accepted |
| 20 | Newsletter and Forum design (D-158) | accepted |
| 21 | governance model file containing **Organisation §49–§51** (D-156) | §49–§51 accepted |
| 22 | Renew / Suspend / Restore design (D-177) | accepted |
| 23 | multi-database boundary design (D-178) — **E-1 note at its end** | accepted |
| 24 | Platform tenancy design rev 11 (D-193) — H-3, M-3, M-8 | accepted |
| 25 | Identity I-TDD-02 rev 12 (D-200) | accepted |
| 26 / 27 / 28 | Reference Geography act · strategic baseline · tactical design v3.11 (D-93) | **RAT** |
| 29 / 30 / 31 | ADR-PL-01 · ADR-UL-01 · ADR-T log (Contestation / Adjudication) | accepted ADRs |
| 32 | EPIC-002 canonical context map (Adjudication) | accepted |
| 33 / 34 | COR-4 plan (Q-2 / V-5) · developer guide 14 | accepted plan; guide |
| 35 … 40 | **code evidence**: `Member.php`, `PostgresMemberRepository.php`, `InterimRoleAuthority.php`, `AuthorityCheckPort.php`, `NewsletterService.php`, `OrganisationNewsletterController.php` | code |
| 41 | database connection names (extract of `config/database.php`; **values withheld**) | code extract |

## 2. Binding (do not reopen)

- **Binding:**
  - D-1 … D-213 as recorded;
  - RA;
  - every accepted design above;
  - Reference Geography (D-93);
  - Adjudication (frozen).
- **S1 must only consolidate;** S2's contracts **must not move ownership or add business rules.**
- **A contradiction that cannot be resolved within these is a Principal question.**

## 3. Questions

**S1 (baseline):**
1. Does S1 represent D-173 … D-213 **faithfully, without adding a rule**? Name any statement without an establishing decision.
2. Is it **internally consistent** (sections, tables, unresolved list)? Were the rev 3 defects (D-213 missing, the broken §7 table, no Geography relationship) corrected, without new ones?
3. **§4a (Geography / units / addresses / belonging):** is each row supported by D-31, D-79, D-80, D-93, D-101, §49.5–§49.6, §51?
4. **E-1 (Appendix E):** is the comparison correct (the note's four items = rules of D-185 and D-193)? Is attribution in the ratifying act sufficient?
5. Is S1 ready for ratification?

**S2 (contracts and conformance):**

6. **T-1 `OfficeCoverageAsAt`:**
   - Does it answer "does any valid moderator tenure cover forum F at d, as known at τ" without duplicating AuthorityCheck?
   - Validity, overlap (D-171), containing forums (D-150), refusal vs NotCovered (D-73).
   - Is limiting release 1 to moderators correct, given D-58 / D-60?
7. **T-2 `CapacityBelonging…`:**
   - Does it keep K-4 unchanged, never consult Membership, and give a complete (not partial) answer (§50 B-2; MI-14)?
   - Is item 3 (independent qualification per D-213) a correct reading of D-213?
   - Is item 6 (posting not implied by reading; D-153, D-161, D-168) right?
8. **T-4 `DeliveryContactsAsAt`:** is it the smallest contract consistent with D-101, INV-ID-3 and D-210 §7?
9. **Flows §5.1–§5.2:**
   - Owner, contract and refusal at every step?
   - **No direct Communication / Deliberation → Membership dependency** (D-210 §4)?
10. **§6 conformance:**
    - Are G-1a … G-4 correct readings of the code (files 35–41) against D-209, D-162 and D-163 … D-205?
    - **K-C1:** does keeping V-5 by refusal (not adjustment) conform to D-209?
11. **New high-severity contradictions.**

## 4. Classification, stopping rule, report

**Classes:**

| Class | Meaning |
|---|---|
| **[A]** | defect (exact section and line, the binding source, a failure scenario, the minimum correction) |
| **[B]** | proof obligation |
| **[C]** | external dependency |
| **[D]** | Principal decision (only if not derivable) |
| **[E]** | resolved |

**Stopping rule:**
- **anything only a test can show is [B];**
- no implementation-level proof on paper;
- no manufactured findings.

**Report title:** "IR-R1C-01 — Independent Review: Strategic Baseline v1.1 rev 4 and Release-1 Contract Additions".
1. Integrity: the manifest and both fingerprints.
2. Questions 1–11.
3. Findings register.
4. Verdicts:
   - **S1:** READY FOR RATIFICATION · READY AFTER SPECIFIC CORRECTIONS · NOT READY;
   - **T-1, T-2, T-4, K-C1 each:** READY FOR ARB ACCEPTANCE · AFTER SPECIFIC CORRECTIONS · NOT READY.
5. Reviewer identity.

**Rules:**
- decide nothing;
- modify nothing;
- reopen nothing;
- **stop after the report.**

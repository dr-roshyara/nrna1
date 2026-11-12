# IR-GM-01: independent review of Governance completion rev 4, M-TDD-01 rev 3 and the ledger-clock amendment

| | |
|---|---|
| **Commissioning authority** | The Principal (D-94), 2026-11-12 |
| **Reviewer** | **GPT-6 in ChatGPT.** Not Claude, not DeepSeek, not run inside Claude Code. **Prior involvement:** reviewed Platform rev 1–11 and Identity I-TDD-02 rev 1–12; not previously these three subjects |
| **Subjects (frozen; not changed while packaging)** | **S1** Governance completion tactical design **rev 4**, `7903c0286971cd4f02e0d901c47eb468b4a7b0b12a3d2366b8fefba74a762f31`<br>**S2** M-TDD-01 **rev 3** (integrated Membership lifecycle and release-1 integration), `b033127607673cfc6332d69ce8bfbb68f144790b551439fa7a33c0d7f1287324`<br>**S3** C1 / C2 ledger-clock **amendment proposal**, `2e8cd2bcd68e93758b64a702bc8727bbb63b078d7bc5b2092d01a73a568b5219` |
| **Status of the subjects** | **Proposed. Not accepted. No implementation authorised.** The author's self-review (file 05) is **not independent evidence** |
| **Not in scope** | Reopening accepted or ratified decisions; Platform, Identity, Adjudication designs; implementation |

## 1. Files (`MANIFEST.sha256` authoritative)

| # | File | Status |
|---|---|---|
| 01 | this commission | — |
| 02 | **S1** Governance completion rev 4 | proposed |
| 03 | **S2** M-TDD-01 rev 3 | proposed |
| 04 | **S3** ledger-clock amendment proposal | proposed (needs ARB acceptance) |
| 05 | author's self-review of S1 rev 3 and S2 rev 2 (findings F-1 … F-15, corrections D-1 … D-8) | **not independent; navigation only** |
| 06 | Governance completion strategic result, with §8 clarification (decisions vs recorded facts) | approved in principle by the Principal |
| 07 | S1 rev 3 (previous) | historical, for diff |
| 08 | S2 previous text (doc rev 1) | historical, for diff |
| 09 | **decision record** | **binding:** D-12, D-67, D-71, D-73, D-101, D-103, D-114, D-147 … D-169, D-160, D-163, D-164, D-166, D-167, D-181, D-193, **D-194, D-200, D-201 … D-207** (§11.93 … §11.106) |
| 10 | ratified Membership architecture (**RA**) | **RAT**: fact table, provenance (line 72), predicates, correction algebra §9 |
| 11 | C1 / C2 implementation specification | **accepted (D-162)**: §4 ledger clock, §6 C2, §11 K-2 (A1–A3) |
| 12 | CR-01 | **RAT** contract (CoverageKnownAt, closedThrough) |
| 13 | CR-02 | accepted (D-161): `inForce`, `suspended` |
| 14 | Renew / Suspend / Restore design | accepted (D-177) |
| 15 | Newsletter and Forum design | accepted (D-158); file carries later conformance notes |
| 16 | Governance thin slice | accepted (D-157) with additions through D-196 / D-200 |
| 17 | strategic context map and governance tactical model (GM; Organisation §49–§50 inside) | GM §20, §33, §40–§41 **proposed**; **Organisation §49–§50 accepted (D-156)** — **note:** D-156 cites the file at `2bc6b65c…`; it now hashes to `1e9eaf31…` (known drift, IR-R1-02) |
| 18 | Membership Slice 1b closure (**D-5(b)** source) | PO decided |
| 19 | Membership Slice 1a contract (D-5(b) as **EffectiveDateAfterDecisionDate**) | accepted |
| 20 | kernel `Member.php` (the three guards) | code, evidence only |
| 21 | kernel `EndKind.php` (closed list of ending kinds) | code, evidence only |
| 22 | Identity I-TDD-02 rev 12 | accepted (D-200) |
| 23 | Reference Geography ratification act | **RAT (D-93)** |
| 24 | Reference Geography tactical design v3.11 (PO-1 … PO-8 §6; Identity contract §8) | **RAT (D-93)** |
| 25 | Reference Geography strategic baseline (§C governs) | **RAT (D-93)** |

## 2. Binding (do not reopen)

**Binding, not to be reopened:**
- RA and its invariants;
- D-5(b) **as clarified by D-205**;
- **D-194, D-201 … D-207**;
- D-160 … D-163, D-167, D-168, D-73;
- the accepted C1 / C2 **contract** (S3 may propose only a mechanism change);
- Organisation §49–§50;
- the Newsletter and Forum design;
- Adjudication (frozen);
- Reference Geography (D-93).

**If a contradiction cannot be resolved within these, name it as a Principal question.**

## 3. Questions

1. **Scheduled resignation (D-204, D-205; S2 §5.2):**
   - Does coupling execution to the `closedThrough` step (S2 §5.2, S3 §2) **guarantee that no consumer acts on the person at τ ≥ the effective date before the End is recorded**?
   - Are execution and withdrawal mutually exclusive in every interleaving?
   - Is the D-205 guard exception narrow enough?
   - Is the availability cost (a stuck execution blocks the organisation's C2 answers) correctly fail-closed?
2. **Category A / B (S1 §2, §3.4; file 06 §8):**
   - Is a Governance decision record created **only** for exclusion (and Annul)?
   - Is `ownAct` (D-203) consistent with RA line 72 and the accepted answer shape?
   - Is exclusion organ-only?
   - Are the pull and push directions correct?
3. **Corrections (D-206; S1 §2.5):** do the act kinds give the ratified Annul / Void / Correct exactly the authority D-206 states, **without changing RA §9** and **without Adjudication writing Governance or Membership facts**?
4. **Decision records (D-202; S1 §3):**
   - Is each of the eight guarantees carried by a specific record?
   - Are GD-1, GD-11 and GD-12 precise?
   - Does GD-12 meet the D-73 participant contract (refuse, never adjust)?
   - **Is X-3 for Platform (D-193) and Identity (D-200) closed at design level?**
5. **S3 (ledger clock):**
   - Is it **mechanism-only**, keeping the C1 / C2 contract and `closedThrough` meaning?
   - Does it satisfy PO-6, PO-7 and the Membership part of PO-8?
   - Are A1–A3 still provable?
   - Is folding D-1 into the `closedThrough` step sound (NOWAIT path, rollback on a failed execution)?
6. **Organisation integration (S2 §6):**
   - admission → placement exactly once, with the root default until then;
   - non-member capacities (D-207, `place-person`);
   - belonging kept after an ending;
   - C2, not C1, for historical answers.
7. **Newsletter (S2 §7):** is the delivery protocol (identity, idempotency, snapshot, records, retry, partial failure, late opt-out, contacts, access) complete and consistent with D-151, D-158, D-161, D-168?
8. **Forum (S2 §8):** read and post rules; applicants (D-207); a moderator tenure valid at the act time, not "online"; committee forums deferred, not dropped.
9. **Reference Geography (S2 §9):** is release 1 genuinely independent of it? Is the later boundary consistent with §C and v3.11? Do PO-1 … PO-8 and the Identity contract stay visible?
10. **New high-severity contradictions** with any binding source.

## 4. Classification, stopping rule, report

**Classifications:**

| Class | Meaning |
|---|---|
| **[A]** | design defect |
| **[B]** | proof obligation (needs real code or a database) |
| **[C]** | external dependency |
| **[D]** | Principal decision (only if not derivable) |
| **[E]** | resolved |

**Stopping rule:**
- **a property that only a test can show is [B], not [A];**
- do not demand implementation-level proof on paper;
- do not manufacture findings.

**For each [A]:** exact section and line, the binding source, a failure scenario, and the minimum correction.

**Report title:** "IR-GM-01 — Independent Review: Governance Completion rev 4, M-TDD-01 rev 3, Ledger-Clock Amendment".
1. Integrity: recalculate the manifest and the three subject fingerprints.
2. Questions 1–10.
3. Findings register.
4. Verdict **per subject**: READY FOR PRINCIPAL CONSIDERATION · READY AFTER SPECIFIC CORRECTIONS · NOT READY.
5. Reviewer identity and prior involvement.

**Rules:**
- decide nothing for the Principal;
- modify nothing;
- reopen nothing;
- claim no acceptance;
- **stop after the report.**

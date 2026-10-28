# Consolidation S0151–S0181: settlement

*2026-10-28. One stage, since there is no planted-gap test. Freeze `e540bc64f` (2026-10-27 22:07:43Z). Reports saved verbatim with their sha256: A (Opus) `REPORT-A.md`; B (Sonnet) `REPORT-B.md`; integration (Opus) `INTEGRATION-REPORT.md`. Rulebook v1.4 §12. The working book is unfrozen by this commit.*

## 0. Entry 58 record

| Measure | Value |
|---|---|
| Reviewer A (Opus), harness | 7,745.5 s |
| Reviewer B (Sonnet), harness | 7,823.2 s |
| Integration (Opus), harness | 6,508.9 s |
| Settlement (the Professor) | from the integration report (2026-10-28 02:08Z) to this commit (see the session log); the Professor's harness time is not available |
| Elapsed, freeze to settlement commit | 2026-10-27 22:07:43Z to this commit |
| **Defects in the rotated chapters** | **Ch. 4: 9** (A only 3, B only 3, both 3) **+ 1** found by the integration review (N-2). **Ch. 5: 16** (A only 9, B only 6, both 1) **+ 2** (N-1, N-3) |
| Overlap estimate | indicative only, since the reviewers are not independent: Chapman ≈ 11 (Ch. 4), ≈ 43 (Ch. 5; one match, unstable) |
| Findings | A: 13 (1 critical); B: 24 (0 critical, 5 high); integration: 3 new defects and one critical block (A-01, A-02, A-03) |

## 1. The critical block: the designation completion (A-01, A-02, A-03; B-02; integration §3)

- **Verified by the integration review's own probes (I1–I6)**, and by the Professor's rerun of the reviewers' cases in the scratch model:
  - v18's guard told accepted entries from bypass entries only by a flag that no record carries;
  - guarded carrier reuse (C1→C2→C1→C3) gave a record outside D;
  - an accepted entry citing a bypass entry promoted it;
  - classing an entry as foreign changed no CE* value (B-01).
- **Disposition: model v19 and the text** (§9.17 R3 (c), §9.13.2, CON-0264). These are the Professor's technical choices, **marked for review**:
  1. **Kernel attestation.** Every entry the kernel accepts carries a record-visible attestation, covered by the integrity check (an extension of K5). An entry without one takes the record out of D, reported as "not accepted by the kernel" (fail-closed; T9-10 (b) resolved). The attestation is **assumed unforgeable; not shown**. R3 (a)'s scope now names this as a second decidable route.
  2. **A store is a carrier at most once**, in both the guard and D (A-02; a rule, not an epoch, per the integration review).
  3. **Every entry carries a store** (A-10, B-03).
  4. **Membership is a classification for the carrier change only;** CE* evaluates every entry of a record in D (B-01). The draft's "safe direction (withheld)" is withdrawn.
- **Model v19**, run `tmp/tools/ce_model-run-20261028-v19.txt`:
  - checks 1–29 give output identical to v18;
  - check 30's bypass case now expects the record outside D;
  - check 31 (reuse, a lone bypass designation, a cited bypass entry, a missing store, no oracle in the guard) passes.
- **Not established:**
  - that an attestation cannot be forged (an assumption);
  - the estate's record;
  - the case of two applications appending to one store, which is T9-10 (a).
- **For the Principal (T9-10 (a)):** may two applications move the same record? The Professor recommends no: one owning application per store, as for single-use grants.

## 2. Other findings and dispositions

| Finding(s) | Disposition | Where |
|---|---|---|
| A-04 | "D has no carriers" corrected: D names carriers and admits no retirement. The K2-w row is annotated | §9.17 R4, R2 table; CON-0265 |
| A-05, B-06 | the T3 statement removed (it rested on files not on the allowed list) | §8.26 C6 |
| A-06, B-11 | the premise is stated; relabelled Int; linked to §8.06 C3; routed to DB-2 | §4.1.2 |
| A-07 | decision pointer added | §8.23 C12 |
| A-08, B-04 (a), (b), (c) | the K2 attribution is split; the §9.15 status line updated; §9.17.1 item 3 restated | §9.15; §9.17 |
| A-09 | S2055 and S0917 removed from CON-0261's sources | register |
| A-11, B-24 | the map's position, counts, chapters and stale count updated; the outline rotation updated | THEORY-MAP; BOOK-OUTLINE |
| A-12 | the C33 settlement's "R-CONFLICT is not homed" was wrong (§8.17 C9, §8.19 C26), and so was its W-02 consequence. Recorded here; the C33 record is not rewritten | this row |
| A-13 | the lineage of S0180 corrected; S0130 named | §8.26 C5 |
| B-05 | the consequence (no answers until repair) stated | §9.17 R3 (c); T9-10 |
| B-07 | the deciding condition made definitional; CON-0256 (i) no longer cited as its home; "exact" disambiguated | §8.22 C7; map |
| B-08 | "one deduction is new" corrected | §8.24.1 |
| B-09 | title and quantifier: "can" | §8.26 |
| B-10 | T0-03 noted: the window sections are a coverage ledger; a theory turn needs the Principal | Ch. 0 TODO |
| B-12, B-13, B-15, B-16; A Ch. 4 items | qualified, relabelled, two readings, mapping removed, scope stated, TODO header updated | §4.1, §4.2, §4.3, Ch. 4 TODO |
| B-14 | updated | §4.2 C4 |
| B-17 | attribution corrected; F28 rests on S0080 L8 | §5.7 C7 |
| B-18 | owner row qualified | §5.00 |
| B-19 | the cited lines are not found in the working book: no change, recorded | — |
| B-20 | reworded | §5.7 C3 |
| B-21 | relabelled | §5.3 C3 |
| B-22 | premise stated; the incomparability claim withdrawn | §5.4 |
| B-23 | **not applied**: its fix conflicts with the source (N-1) | — |
| N-1 | §5.7: "no eligible commissioned deliverable", not "never ran" | §5.7 |
| N-2 | the basis cells of CON-0107, CON-0113 and CON-0131 updated | register |
| N-3 | recorded only (a reconciliation target, not a home) | — |
| A Ch. 5 items | three open questions linked to §9.13.2; the CON-0165 scope, CON-0175 tension and CON-0187 trivial gate noted; the shared term of CON-0145 / CON-0175 separated; the homonym table extended | §5.5, §5.6, §5.7, §5.00; register |
| Coverage | the ranked MISSING items go to T8-05 (none blocks reading) | Ch. 8 TODO |

**Verdicts entered in the register** (reviewed column):
- **CON-0265 VALIDATED** within its scope; **CON-0079 VALIDATED** (scoped).
- CON-0264: core PROVISIONAL; two v18 clauses INVALID as stated, restated (v19).
- CON-0261: PROVISIONAL.
- Ch. 4 and Ch. 5 verdicts as the integration review gives them.
- Counts: 265 concepts, 47 VALIDATED, 122 PROVISIONAL, 96 INVALID.

**Conflicts between corrections, as the integration review resolved them:**
- fail-closed over evaluate-and-mark for entries the kernel did not accept;
- drop, not relabel, for the T3 content;
- a rule over an epoch for carrier reuse;
- A-04 over B's "D has no carriers";
- B-23 not applied.

## 3. Process notes

- **The frozen copy** gained a bytecode cache, `BOOK-UNDER-REVIEW/tmp/tools/__pycache__/` (dated 23:11; the writer is not attributable). It is git-ignored, and no book text changed.
- **Both reviewers** wrote scratch files into this session's earlier scratchpad. They opened none of the excluded files and none of REVIEWER-NOTE item 4's files.
- **B's disclosed deviations:** the hash table and directory listings (names only). B ran R3 for only 3 targets.
- **The integration review** found that B under-excluded inventory items resting on S0155 and S0161, and that A over-excluded some. These are judged per citation (integration §7).

## 4. Checks after settlement

| Check | Result |
|---|---|
| Quote check (Ch. 4, Ch. 5, §8.22–§8.26, §9.13, §9.15, §9.17) | 179 found, 0 not found |
| R1 | 241 homes, 1 flag (CON-0133 H4, information, Ch. 6; it predates this consolidation). The H3 flag for CON-0079 is resolved by updating its tag |
| R2 | no new tier-A term from the settlement beyond ordinary words |
| Model v19 | all assertions pass |

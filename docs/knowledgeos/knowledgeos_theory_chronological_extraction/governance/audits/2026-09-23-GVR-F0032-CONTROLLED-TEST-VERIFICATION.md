# Governance Verification Record — F0032 controlled correction test

| | |
|---|---|
| **Kind** | verification only: integrity, fidelity and authority of the research record. ⛔ Not theory validation. No integration proposal is accepted or rejected here |
| **Verifier** | governance / control-plane session (not the research session). ⚠️ Same model family |
| **Evaluated state** | HEAD **`97ef0f7bd`**, taken as a `git archive` snapshot. F0032 work: `d60efe05f` (test) · `b434ff155` (retrofit) · `e2e330dee` (protocol completion); related: `73b6bb5b5` (SRE-Q1) |
| **Method** | **source first:** canonical F0032 was read in full (L1–L95) before any research artifact was opened. ⛔ No other held or incident-range file was read |
| **Modified** | nothing but this file |
| **Classes** | CONFIRMED · PARTIALLY_CONFIRMED · NOT_SUPPORTED · UNRESOLVED |

## 1. Scope and artifacts

- **Canonical F0032:** `docs/knowledgeos/KnowledgeOS_Operational_Validation_Report.md` (list line 32) · **95 lines · 9,593 bytes · sha256 `05d8b4442d2500e5…`** · unchanged since `d61bf5e84` (2026-08-04).
- **Research artifacts:**
  - `FINDINGS-F0032-CONTROLLED-TEST.md` · `DOSSIERS/F0032.md`;
  - `reconstruction-records/F0032{.json,.gates.json,.pipeline.json}`;
  - rows written in `SOURCE-LOCAL-IDENTIFIERS`, `DERIVATION-INSTANCES`, `INTRA-FILE-REVISIONS`, `CONTRADICTIONS`, `ORDERING-CONSTRAINTS` and `RESEARCH-OBLIGATIONS`;
  - `evidence/READ-RECEIPTS.jsonl` rows 7–9.
- **Context:** `FILE-REGISTRY.jsonl` · `THEORY-OBJECTS.jsonl` (T-0027, T-0032, T-0035, T-0042) · `governance/L0-DECISION-RECORD-01.md`.

## 2. Authority (checked first, because F0032 was GOVERNANCE_HELD)

| # | Point | Finding | Class |
|---|---|---|---|
| A-1 | The research claim: *"L0 authorized this single read as a controlled correction/reconstruction test"* | **No governance artifact records this authorization.** The governance decision record (`L0-DECISION-RECORD-01.md`, L0-DEC-05/06/07, entries appended but *uncommitted*) records the opposite state: **C-5 held** until 1a and 1b are accepted and **SQ-1** is answered, where SQ-1 is *whether canonical F0032/35/36/40 are within RC-H-04 at all*. SAFE-RESEARCH-EXCEPTION-01 **forbids** *"re-admission or reading of canonical F0032 … under the unresolved correction"*. If L0 authorized the read in the research session, **that act supersedes those entries but is not recorded where governance can see it** | **UNRESOLVED** (authority plausible, not traceable) |
| A-2 | The research claim (`73b6bb5b5`): *"L0 chose (a)"* for SRE-Q1 | likewise **recorded only in the research tree**. The governance record still shows SRE-Q1 **open** | **UNRESOLVED** in the governance record |
| A-3 | Containment: *"one file read; F0035, F0036, F0040 remain held"* | receipts show only F0032 among the held four; no F0035/F0036/F0040 receipt exists | **CONFIRMED** |
| A-4 | Admission path | via `evidence/admit.py`, which is **research-produced and unreviewed** (GIA-9). The order resolve → read → receipt is followed, and the earlier F0047 receipt is **voided openly** (receipts row 8) | **CONFIRMED** (as recorded) · ⚠️ the evidence path is unreviewed |

## 3. Findings verified (CONFIRMED)

| # | Research finding | Evidence |
|---|---|---|
| V-1 | **Identity:** the record, dossier and receipt bind canonical F0032, and the receipt sha and bytes match the source (9,593) | receipts row 9; recomputed |
| V-2 | **Every quotation in the Phase-1 part is verbatim:** the thin evidence base (L9); the strategic answer (L17); the verdict table (L21–25); "PREVENTIVE … GENERATIVE … can say no; not yet proven it can learn" (L69); the R-75 commit sequence (L65); U-1…U-11 (L48–58); "absence of exercise is not evidence of a problem" (L77) | source lines cited |
| V-3 | **U-1 "the distinctive bet (n=0 inside and outside)"** agrees with T-0027 / F0026 | L48 |
| V-4 | **R-6 "guides" = 2, enumerated** (agrees with T-0035's count) | L31 |
| V-5 | **U-4: formal back-edge n=0, informal n≈3** | L51 |
| V-6 | **Integration withheld:** no write to `THEORY-OBJECTS`, `THEORY-OBJECT-RELATIONS`, `VERIFIED-EDGES`, `GAPS` or the Candidate Theory in `d60efe05f` / `b434ff155` / `e2e330dee` | commit file lists |
| V-7 | **§9A 11/12**, `THEORY_AND_ARCHITECTURE_OBJECTS_UPDATED` deliberately unsatisfied, INCOMPLETE_BY_DESIGN; **§9: 23 DONE · 2 PARTIAL · 4 BLOCKED · 1 NOT_APPLICABLE** | `F0032.gates.json`; `F0032.pipeline.json` |
| V-8 | **The collision warning:** research-era "F0032" in `FILE-REGISTRY.jsonl` = `Conceptual_Foundation` (canonical F2837). New rows avoid the collision | dossier L15; registry |
| V-9 | **REV 2** (L85–89) recorded as the only in-file revision; **C4 "rediscovery decreasing" has no derivation** (the source marks it "suggestive only", L24) | IFR row; source |

## 4. Partially verified (PARTIALLY_CONFIRMED)

| # | Research finding | Holds | Does not hold |
|---|---|---|---|
| P-1 | **T-0042 "41 enforced / 0 capabilities" CONFIRMED** | the source has deny×19 · ask×22 "operating" (L36) = 41, and "governance did not advise … it blocked" (L65) | the source does **not** state that no capability enforces. CAP-001 was "exercised once · 1 decision changed" (L35). "Enforcement sits outside capabilities" is an **interpretation** |
| P-2 | **The date test "0 → 1 lands exactly … one day apart"** | F0032 (2026-08-03) records formal n=0 (L51) | the other end, *"F0034 register, 2026-08-04"*, is **canonical F0034** (`Operational_Evidence_Register`), which the research registry holds as **research "F0035"** with **`FIRST_ENTRY 2026-08-03` · `LAST_ENTRY 2026-08-04`**. The traversal entry's own date is not established by the registry. "One day apart" is therefore not verified; the two could fall on the same day. ⛔ It cannot be settled without reading canonical F0034 (incident range), which was not done here |

## 5. Not supported (NOT_SUPPORTED)

| # | Research statement | Why |
|---|---|---|
| N-1 | **"Four INDEPENDENT corroborations"** (T-0027, T-0032, T-0035, T-0042) | F0032 is **from the same programme on the same day** (2026-08-03) as F0026. It **reuses F0026's labels** ("R-6", "the distinctive bet") and **the same underlying evidence** (OE-KOS-1/2 underlie both F0026's REV 5 and F0032's count of 2). It is **consistent** with those objects, not **independent** of them. Counting it as independent confirmation would be repetition raising maturity, which P2P Q22 forbids |
| N-2 | **"A distinction none of the 46 prior files stated this cleanly"** | not verifiable from the record: no search over the 46 files is recorded |

## 6. Unresolved and provenance points

| # | Point | Class |
|---|---|---|
| U-1 | **A new identity form `F0032(canonical)`** is used as the file key in five registries. It avoids the collision, but it is a **third identity convention**, alongside the research ID and the canonical ID. It departs from P1P §4 ("use verbatim"), and it was introduced **without an L0 decision** | UNRESOLVED |
| U-2 | **Mixed ID conventions inside one test:** the findings cite "F0034" meaning **canonical** F0034, while T-0032's `historical_sources` cite **research** "F0034" (= canonical F0031, Phase_B) and "F0035" (= canonical F0034). A reader cannot resolve "F0034" without knowing which convention is meant | UNRESOLVED (inside RC-H-04 scope) |
| U-3 | The record's `file_id` is plain **"F0032"** (canonical) while `FILE-REGISTRY`'s "F0032" is another file. Within `reconstruction-records/` there is only one F0032 record, so there is no conflict there, but it is ambiguous against the registry | UNRESOLVED (recorded by research) |
| U-4 | **Whether the controlled test is itself partial execution of RC-H-04** (re-admission and reading of a held canonical file is inside C-5's scope) ahead of P-1 (1a), P-2 (1b) and P-5 (SQ-1/SQ-2) | UNRESOLVED; **an L0 question** |

## 7. Discrepancies requiring correction (recorded; not corrected)

| # | Discrepancy | Owner |
|---|---|---|
| X-1 | the L0 acts (F0032 read authorization; SRE-Q1 = (a)) exist **only in the research tree**. The governance decision record is stale and contradicts them | **L0** confirms, then governance records |
| X-2 | "independent corroboration" (N-1) must become "consistent with, same programme" before any integration | research (integration proposal) |
| X-3 | the date test (P-2) is overstated | research |
| X-4 | T-0042's "0 capabilities" (P-1) is an interpretation labelled as confirmed | research |
| X-5 | the ad-hoc key `F0032(canonical)` (U-1) and the mixed conventions (U-2) | L0 / governance naming (RC-H-04 scope) |

## 8. Final statement

> **The research record faithfully represents the evidence currently available from F0032: PARTIALLY_CONFIRMED.**
>
> - **The Phase-1 reconstruction is faithful:** identity exact, quotations verbatim, gates and pipeline recorded as stated, integration correctly withheld, and containment held (one held file read).
> - **The Phase-2 proposal overstates** in three places: **independence** (N-1), the **date test** (P-2) and **T-0042** (P-1).
> - **The authority for reading a governance-held file is not traceable in any governance artifact** (A-1). The governance decision record, as it stands, says that read was still held.
> - **Governance cannot accept or reject the integration proposal** until L0 reconciles A-1/A-2 and answers SQ-1.

---

*Traceability:*
- Snapshot `97ef0f7bd`.
- Source read in full (L1–L95); `wc`/`sha256sum` on the canonical file.
- Registry dates for research F0035 (= canonical F0034).
- Commit file lists for `d60efe05f`, `b434ff155` and `e2e330dee`.
- Nothing modified outside this file.

---

## RE-VERIFICATION A (2026-09-23, HEAD `1e6dc3824`; appended; the record above stands as issued)

| Item | Now | Evidence |
|---|---|---|
| **A-1** authority for the F0032 read | **RESOLVED**: authorized by L0 (L0-DEC-09) | D-2; governance transcription |
| **A-2** SRE-Q1 = (a) | **RESOLVED** (L0-DEC-09a) | `73b6bb5b5`; D-2 |
| **U-4** whether the controlled test is part of RC-H-04 | ⛔ **OPEN** (not answered by D-2; SQ-1 still open) | L0-DEC-09 |
| **X-2 / N-1** "independent corroboration" | **ADDRESSED BY RULE, NOT IN THE RECORD**: ARCH v1.2 **RA-15** (four epistemic statuses, "INDEPENDENTLY CORROBORATED" kept separate) cites this N-1 as evidence. `FINDINGS-F0032-CONTROLLED-TEST.md` is unchanged. The integration proposal still claims independence | `9444cfbb5`; no commit to the findings file after `97ef0f7bd` |
| **P-2 / X-3** date test · **P-1 / X-4** T-0042 · **N-2** "46 files" | **OPEN** | — |
| Ledger row F0032 | **CORRECTED** from `COMPLETE` to `11/12 · INCOMPLETE_BY_DESIGN`, with the prior value preserved in-row | `PHASE1-CONFORMANCE.json` |
| **U-1** key form `F0032(canonical)` | **OPEN** | — |

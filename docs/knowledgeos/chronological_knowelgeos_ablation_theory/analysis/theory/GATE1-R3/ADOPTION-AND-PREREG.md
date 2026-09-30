# Coding manual r3: adoption record and pre-registration (frozen before any r3 coder runs)

| | |
|---|---|
| **Authority** | **Human act** (2026-09-30): the answer "Authorize r3, then code", plus the message "also authorize subagent — Manual r3: adopt the reviewer's seven clarifications". The authority is the human's. The verification subagent below checks the adoption; it **creates no authority** |
| Change | **section G** appended to each bundle's own manual. It holds the seven clarifications of review GATE1-B2/REVIEW (F-LOG-0159), **verbatim** apart from dropping the "RECOMMENDATION ONLY." prefix. The READMEs point to section G. **Nothing else changed**: ROWS.md and FORM.json are byte-identical (hashes in BUNDLE-FILES.sha256) |
| Disclosed asymmetry | the Gate 1 bundle's manual (v1.1) has no section F; the reserve manual (r2) does. r3 keeps each bundle's own base. Adding F to Gate 1 would be an unauthorized second change |
| Bundle | `~/F-GATE1-R3-BUNDLE-01.tar` sha256 `5059e785a3ae59979c864f1eb87618113bea9ab82b3da65efe973c4dec92f273`. It is the bundle to give a **non-Claude coder** for Gate 1 proper |
| Old codings | MAIN, B1 and B2 stand unchanged (coded under v1.1 / r2) |

## Procedure
1. **Verification subagent (V):** fidelity of G to the seven recommendations; conflicts with sections A–F; codability. **If V finds a defect, stop before coding** and report.
2. **Two fresh coders, C1 and C2** (Claude, SECONDARY), per folder: four sessions, each confined to its folder, with Read/Write only.
3. **Scoring** with the unchanged frozen scorers: **C1 vs C2** is the r3 inter-coder reliability. Reference comparisons: B1 vs B2 (v1.1 / r2 reliability, F-LOG-0159), MAIN vs C1, and MAIN vs C2.
4. **Review subagent**, then reconciliation.

## Pre-registered comparison (the test of r3)

| Outcome | Consequence |
|---|---|
| C1–C2 V1 agreement on Gate 1 **> B1–B2's 0.393**, and V4 **> 0.75**, with no judgement dropping by > 0.1 | r3 **improves reliability** of the instrument (same-family; not independent) |
| no V1 / V4 improvement | the instability is not the vacuous case alone; manual ambiguity persists |
| V1 improves but another judgement drops by > 0.1 | a trade-off: G rules interact; a review is needed |

**Sealed main-analyst expectation (bias check):** Gate 1 V1 C1–C2 agreement rises clearly (≥ 0.75), because G1 targets the dominant cause (15/17 of the B1–B2 V1 disagreements). V4 rises modestly. The other judgements are stable. Moderate confidence.

## Verification result (recorded before coding)

**PASS_WITH_LIMITATIONS** (`VERIFY/VERIFY.json`).
- **Fidelity and verbatim: OK.**
- **Limitations:**
  - undefined terms (§C-guard mapping, "guarded act satisfied", "competing readings", "looser");
  - G2 does not specify unguarded acts;
  - G6 vs F3 (reserve);
  - **G depends implicitly on F1, F2 and F4, which are absent from the Gate 1 manual, so the two r3 manuals are not equivalent.**

Per the frozen procedure, only FAIL stops coding. **Proceeding.**

**Derived prediction** (from the verifier's finding; recorded before coding): r3's reliability gain is **larger on RESERVE than on Gate 1**, because Gate 1 lacks F1, F2 and F4. Fixing the limitations would be a further protocol change needing human authorization.

# S-Series Theory Manuscript: Source Register

| | |
|---|---|
| **Kind** | A reference index for the manuscript. It **decides nothing**, records no decision state and does not replace any source. Each row says where a source lives, which exact bytes were read, what governance status the authoritative record gives it, and what this manuscript may use it for. |
| **Maintained by** | Session B (Professor), 2026-10-17 |
| **Verification** | Every SHA-256 below was computed by Session B on 2026-10-17 from the file at the stated path. Where the governance log records a hash for the same file, the two were compared, and they match unless the row says otherwise. |
| **Authority** | The governance log (`S-GL`) is the only record of human acts. Where this register gives a status, the cited log entry is the authority and this register only repeats it. |

## Locations

| Key | Path | Branch | Commit at reading |
|---|---|---|---|
| **W** | `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1-s5-g1` (S-Series worktree) | `s-series/s5-r7-v29-repair` | `725bfa1e3` |
| **PROD** | `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1` (the same directory as `/home/nab-raj.roshyara@dg-nexolution.de/roshyara/personal/nrna1`, verified by inode) | `election-flow-updated` (a development branch, not `main`) | `f54037f96` |

`CR` stands for `docs/knowledgeos/chronological-read`.

## Register

| ID | Artifact | Location / path | SHA-256 | Last commit | Status (authority) | Manuscript may use it for |
|---|---|---|---|---|---|---|
| **S-GL** | P3b governance log | PROD `CR/P3B-GOVERNANCE-LOG.md` (2,218 lines; G-LOG-0001…0140) | `04cb0c1971a4cf7acc71c8357b9bcae5a9be563e1a343932a3c2e75c0a7b6ce9` | `11dc5cf8c` | **Authoritative record of human acts**, append-only (GL l.3–4) | every statement about what was decided, frozen, authorized or prohibited. **Conflict X-1:** the W copy stops at G-LOG-0110 (1,718 lines, sha `f5905a5567228c2f…`). |
| **S-TODO** | S-Series TODO and decision sheet | PROD `.claude/S-SERIES-TODO.md` (510 lines) | `e7d35147e62a8b30bb9785e8dd25a9bd05eb30cc13a1333be05851bbd8f962fc` | `83a059f98` + **uncommitted working-tree changes** | A **derived working state** that "decides nothing" (TODO l.24) | current state, the open-decision codes (pointers only), the critical path. A view, never an authority. |
| **S-MP** | Master Protocol v3.5 | W `CR/prompts/20260911_0221_prompt3-optimized.md` (515 l.) | `508b9f99edaea870e393e4c7e6737f4012266c0e30227f2f64b14da5c83730c5` | `e3e47b139` | **Governing procedure** of the S-lane (GL-0069 l.1186). "Never edited" (P3b §26 item 4) | phases P0–P7, GATE, rules R0–R20, the B4 status vector, TYPES |
| **S-P3B** | P3b operating annex v1.7 | W `CR/prompts/20260924_2311_p3b-phase1-continuation-protocol-v1.7.md` (3,257 l.) | `38021aa4328c1fae23edf2eeab068d1a8d446d6197aac6567681a72687502d12` (= GL-0034) | `a72528b29` | **FROZEN** (GL-0034, H-01). **X-7:** the file's own header still reads "PROPOSED … NOT frozen" (l.3). This is a known stale header (TODO l.405, F-10). | stage model, units, evidence model, register, §13 verification layer, gates, audit, change control |
| **S-ADD** | H-19 hold-out addendum v1.5 | W `CR/prompts/20260924_1625_p3b-H19-holdout-addendum-v1.5.md` (326 l.) | `75a5e45d0f8a1e5726beb92ef1ac18c540355ce51cdec7b8dd6873399e806ae3` (= TODO, GL-0137 "v1.5 §7, sha 75a5e45d…") | `a72528b29` | **APPROVED, variant S** (GL-0016); **sealed** HS-3d32dd44d162 (GL-0018); **seal breach, S5c PROHIBITED** (GL-0045, P3B-ESC-0001). The file still self-labels "PROPOSED" (ADD l.3). | hold-out design, prediction format, scoring, unseal rule |
| **S-ARCH** | KnowledgeOS Research Architecture v1.2 | W `CR/prompts/KNOWLEDGEOS-RESEARCH-ARCHITECTURE.md` (492 l.); byte-identical to `docs/knowledgeos/knowledgeos_theory_chronological_extraction/prompts/…` | `e3bbf29269346cde78e84ad9f91e7f5065b8fb3410f3ebed2f2c01951c816439` (= GL-0068 baseline) | `a72528b29` | **FROZEN as an F-lane artifact.** **Not declared governing for the S-lane** (GL-0068 F-01; GL-0069 l.1186). Decision A is open (TODO l.353). **X-2.** | a **comparative and terminological lens only** (RA-numbers, the four epistemic statuses, Layer 5), always cited with this caveat |
| **S-OP** | S-Series operating protocol | W `CR/prompts/S-SERIES-OPERATING-PROTOCOL.md` (292 l.); identical in PROD | `d91b3f372b9b16e1efd289cb4bed172fb8a53b7425d21e571d9811f9ec9e45de` | `67f32604a` | **Adopted** by the human 2026-10-01 (OP l.5); AM-1/AM-2 per GL-0109. "Grants no authorization" (OP l.6) | the research mission, the two named outcomes, review and statistical discipline, the definition of "measured" |
| **S-B1** | B1 v2 experiment design (Freeze 1 text) | W `CR/audit-p3b/20260927_B1-EXPERIMENT-DESIGN-FREEZE-PROPOSAL.md` (122 l.) | `d6b238866b637249f20c0a0f8fb7f9d1b9afd619af8124d02868ac463ee82163` (= GL-0094) | `a72528b29` | **FROZEN** as Freeze 1 (GL-0094). Self-label "freeze candidate" is historical | estimands θ_D/θ_A/θ_E, R1/R2, Model 0, the three-way comparison. **X-8** (re-registration) |
| **S-PRV1** | prereg_v1 instrument | W `CR/research/prereg_v1.py` (198 l.) | `eb6291abfe0653ea0a5dcaee8c0dcaf04ab6d47ecf40b3cb960dda103ac1a1f5` (= GL-0094, GL-0137) | `a72528b29` | FROZEN (GL-0094); **historical, not used for S5 analysis** (GL-0137) | **not opened by Session B**; cited only through prereg_v2's import guard and S-B1 |
| **S-PRV2** | prereg_v2 analysis instrument | W `CR/research/prereg_v2.py` (365 l.) | `827ce461634201fa4da3d69bb87890a5bae0f3bd4ac993042a1eb9cf43b1d87d` (= GL-0137) | `b8a3ecf2a` | **FROZEN** (GL-0137). **X-6:** the code still self-labels "DRAFT, NOT FROZEN" (l.4, l.348) | confirmatory and exploratory analysis plan, constants, decision vocabulary |
| **S-PRT** | prereg_v2 tests | W `CR/research/test_prereg_v2.py` (424 l.) | `8e126dd8fc335700bdb7d0fb7ad3ce44aec67194c9c1b4f8757177662b5a18e4` (= GL-0137) | `b8a3ecf2a` | FROZEN with S-PRV2 (41 pass, as recorded; **not re-run by Session B**) | properties the instrument is tested for |
| **S-PRR** | prereg_v2 review record | W `CR/research/PREREG-v2-DRAFT-REVIEW.md` (126 l.) | `da57876515d48536b112fc0a7405bb68a51b21c7d44c4a9dd431f61796c62b78` (= GL-0137) | `b8a3ecf2a` | FROZEN with S-PRV2 | review history of the analysis instrument |
| **S-VC18** | Verdict criteria v1.8 (B-core) | W `CR/audit-p3b/verdict-criteria/VERDICT-CRITERIA-PROPOSAL-v1.8.md` (623 l.) | `469fddcabf196e5f89ffd51b39ecd01422620857e2ff09d3cb8f05068118a1d7` | `9a220f613` | **PROPOSAL, not adopted** (banner l.3; GL-0140 authorizes drafting and review only). V-1…V-13 open | Chapter 7, always labelled as proposal |
| **S-VCR** | Verdict-criteria review record | W `CR/audit-p3b/verdict-criteria/VERDICT-CRITERIA-REVIEW.md` (811 l.) | `e2f183eb8d33b985e620f896415ca2118420051e0bf4944df90986a6492696b0` | `add79665d` | Review record (rounds 1–13); same-model reviewers | review outcomes and their declared limits |
| **S-NAC4** | N-a/C-4 decision package v2 | W `CR/audit-p3b/verdict-criteria/VERDICT-CRITERIA-DECISION-PACKAGE-NA-C4-2026-10-16.md` (182 l.) | `c4c6129954c7babe0bf066145c58e87e05e65e8029b40e3cdfda5afec0f59728` | `e6cdfdae4` | **NEEDS-REVISION** (round 12b, N1) | the content of the pending N-a/C-4 question |
| **S-NAC4P** | N-a/C-4 audit-placement package r2 | W `CR/audit-p3b/verdict-criteria/VERDICT-CRITERIA-DECISION-PACKAGE-NA-C4-AUDIT-PLACEMENT-2026-10-17.md` (261 l.) | `7bc4e3a1bc0d47845be8b37c32ac71c55e67df0d0c4c9e7d52d2ae9df8605781` | `add79665d` | **NEEDS-REVISION** (round 13b, R2-1, R2-2) | the G0/G1 premise and Q-a |
| **S-TT1** | Theory-track protocol v1 | W `CR/audit-p3b/theory-track/THEORY-TRACK-PROTOCOL-v1.md` (373 l.) | `07401d1ccb6b7750bed7b302f2a8ccdcc0eb8732b0c37e42984e06acb558ac57` (= GL-0139) | `40b0b1f5a` | **DRAFT**; T-0/T-1 pending (GL-0139) | the planned early-read option O3c and its ceilings |
| **S-TTR** | Theory-track review record | W `CR/audit-p3b/theory-track/THEORY-TRACK-PROTOCOL-REVIEW.md` (37 l.) | `4dd18d223e2720effffce9fef4b5455615d19bd5753a124caa1bc1e2a6e6c4d9` (= GL-0139) | `40b0b1f5a` | v1 RESOLVED WITH NOTES A–E | the track's limits |
| **S-C4** | S-Series system architecture (C4) and status | W `CR/audit-p3b/20261016_S-SERIES-SYSTEM-ARCHITECTURE-C4-AND-STATUS.md` (459 l.) | `3970c10720e1d4ef2d02576ff31259fd07740b9d33bdb99a7be92d1f4a8771b5` | `9a220f613` | **Analysis and proposal; authorizes nothing** (l.3). Treats ARCH as governing (l.9) (**X-2**) | the lane structure, a state snapshot, the proposed formalization of the verdict |
| **S-ASM** | Researcher's assessment of the working model | W `CR/audit-p3b/20261017_RESEARCHER-PROFESSOR-PRINCIPAL-WORKING-MODEL-ASSESSMENT.md` (103 l.) | `50374c7f5ccecafba8a4119d1594e2be262638d8c52a8a9803696524aed99850` | `725bfa1e3` | Assessment; decides nothing | the Stage-1 framing, the review limits and the concurrency rule |

### Added in manuscript v0.2 (2026-10-17)

| ID | Artifact | Location / path | SHA-256 | Last commit | Status | Manuscript may use it for |
|---|---|---|---|---|---|---|
| **S-CTR29** | R7 agent-contract addendum, v2.9 DRAFT | W `CR/prompts/20260926_2400_p3b-agent-contract-r7-addendum.md` | `7eba03857e07bdfee67ddb73848202e3bc97e652f21e22987a324c21250b6ffa` (= GL "v2.9 DRAFT `7eba0385…`") | `f7396ac3f` | **DRAFT** for the isolated environment (GL-0112/0115); **not the production contract** (v2.8.1 `7b68cbc9…`, not inspected) | the FD-1′a predecessor rule (l.712–715) and "no historical precedence" (l.714): Ch. 8.14 |
| **S-REC** | R7 reconstruction module | W `CR/scripts/p3b_s5_r7_reconstruction.py` | `4dcf8998f8299ab245bc535365330f5916304e0790acab2d238f1d2dd76c9470` | `2f7338b26` | engineering code on the v2.9 repair branch | `dated_position`/`file_position` (l.43–65), `summary_violations` (l.76–126): Ch. 8.14 |
| **S-KP** | Session A note: Kind-P candidate theory structure, r2 | W `CR/audit-p3b/20261017_KIND-P-CANDIDATE-THEORY-STRUCTURE.md` | `7d3f2de13c117237b5309b4959b3299b22c6f6895eeb001850397016ce8a229b` (r1 was `eed17218…`) | **uncommitted** | research note; pre-data; hypothetical | T_P, its Q1–Q6 derivations: Ch. 8.16 |
| **S-HO** | Session A research handoff to the Professor | W `CR/audit-p3b/20261017_S-SERIES-RESEARCH-HANDOFF-TO-PROFESSOR.md` | `4eb6cc91c94c4ce6ab3d0e0bee5ad8da5c56428989f8f0ec36e5f8b9b196944a` | **uncommitted** | research handoff; decides nothing | derivations A/B, the H2 power table (Annex 2, REPORTED), answers to RQ-1…3 |
| **S-PKA** | Session A consolidated decision packet D1–D6 | W `CR/audit-p3b/20261017_S-SERIES-PRINCIPAL-DECISION-PACKET.md` | `55ae2afa1b74b1a326d8088b227dad81424e57b50a7d0ad74b603d1884d71421` | `c470da83c` | decision packet; decides nothing; one of **two** packets (X-13) | the Researcher's recommendations |

Additional conflict: **X-13**. Two decision packets exist, Session A's (S-PKA) and Session B's (`PRINCIPAL-DECISION-PACKET-2026-10-17.md`). Which counts is for the Principal.

### Corpus sources (Part III; under G-LOG-0141 and its extension; read through the hold-out filter; reads in `corpus-access/READ-LOG.tsv`)

| ID | Artifact | sha256 (prefix) | Class | Used for |
|---|---|---|---|---|
| C-285 | `brainstorming/phase_measure_theory/20260831-173224_step_285_canonical-state-reconciliation.md` | `24609ec8…` | SECONDARY-SYNTHESIS (research programme) | Ch. 15.1–15.2 |
| C-0901 | `brainstorming/phase_measure_theory/20260901_0927_mathematical_theory_gita_inspired_knowelge-os-model.md` | `c3b9dabc…` | SECONDARY-SYNTHESIS (research prompt) | S-10 |
| C-30 | `…/knowledgeos_kernel/research/30-THE-EIGHT-PRIMITIVES-HOLD-CONVERGENCE-ON-THE-ANSWER.md` | `b358915c…` | SECONDARY-SYNTHESIS | S-1, S-5 |
| C-38 | `…/knowledgeos_kernel/research/38-THE-LAYERED-MODEL-AND-A-SECOND-IRREDUCIBLE-TRANSFORMATION.md` | `57fa8751…` | SECONDARY-SYNTHESIS | S-6…S-9 |
| F-* | Session A family files in `chronological-read/20-FAMILIES/` (6 read; hashes in READ-LOG) | see log | P1/P2 reconstruction (derived) | Ch. 15.7 T-1…T-8 |

## Sources cited but not opened by Session B

These are cited only through the sources above. Their content is reported second-hand and marked as such wherever it is used.
- EP-01 statistical specification (`CR/audit-p3b/20260926_EP-01-STATISTICAL-SPECIFICATION.md`, bound by GL-0106(e), sha `f43504f4…`): the θ_D/θ_A estimators and the v2.5-S exact bound.
- The R7 contract chain (v2.3…v2.9) and its runbooks. Hashes are listed in GL-0088…0115.
- `CR/research/RESEARCH-LEDGER.md`, the theory-candidate registry named by B1 §0.
- The Freeze 2 record (`CR/audit-p3b/20260928_FREEZE-2-RECORD.json`).
- `prereg_v1.py`, beyond its hash.

## Restricted material: not read (O1, G-LOG-0125; OP §22)

Session B opened **no** corpus file and **no** S5 data, batch input (`_batch_input*`), ledger (`ledger*`), pilot decomposition, family file (`20-FAMILIES/`), `*.jsonl` data file, witness archive, hold-out or seal file. Two disclosures:
- A directory listing of `CR/` during the first inspection displayed the names of such files. It showed names only; no contents were opened.
- Extraction subagents working for Session B were bound by the same rule. One displayed G-LOG heading lines beyond its assigned range; that is governance text, not restricted material.

## Source conflicts (recorded, not resolved)

| ID | Conflict | Sources | Handling |
|---|---|---|---|
| **X-1** | The two branches hold divergent copies of the governance log: PROD reaches G-LOG-0140; W stops at G-LOG-0110 | S-GL vs `W:CR/P3B-GOVERNANCE-LOG.md` | The manuscript cites the PROD copy. **Request RQ-2** asks which copy is authoritative. |
| **X-2** | ARCH v1.2 authority over the S-lane. Recorded as not declared governing (GL-0068 F-01; GL-0069); Decision A is open. Yet S-C4 (l.9), S-TT1 and S-VC18 cite RA-rules as binding | S-ARCH, S-GL, S-C4, S-TT1, S-VC18 | The manuscript uses ARCH as a lens and labels every RA citation. This is a **human decision** (Decision A), not a researcher question. |
| **X-3** | RI-1 status: GL-0104 l.1616 says "RI-1 reopened on its condition 1"; the TODO says it stays DEFERRED (l.20, l.231, l.290) | S-GL, S-TODO | **Request RQ-3**. |
| **X-4** | The binary frame has 41 labels (GL-0095) vs "42 labels carry a decided file" (GL-0101 l.1583) | S-GL | Recorded. Not material to Stage 1. |
| **X-5** | Two different stratifications of the 1,975 labels: Freeze 2 (HUB 66 · EMPTY 7 · MULTIROW 4 · DECOMPOSED 173 · SINGLE 1,725) vs TODO l.302 (SINGLE 1,789 · DECOMPOSED 178 · EMPTY 8) | S-GL GL-0103, S-TODO | The manuscript uses **Freeze 2**, the frozen record. |
| **X-6** | The frozen prereg_v2 code labels itself "DRAFT, not frozen" | S-PRV2 l.4, l.348; GL-0137 | GL-0137 governs. Cosmetic. |
| **X-7** | The frozen P3b v1.7 header reads "NOT frozen" | S-P3B l.3; GL-0034 | GL-0034 governs (known issue F-10). |
| **X-8** | Freeze 1 states "Nothing learned later … may change" H1–H5, the families, α/q and B (S-B1 §8, l.108; B = 9,999, l.68). prereg_v2 changed the family to {H1, H2, H4} confirmatory, set B = 999 and reworded H2 (GL-0118…0137), before any S5 data | S-B1, S-GL, S-PRV2 | OP §17 permits "a governed, **disclosed** re-registration". **Request RQ-1** asks for the record that discloses this re-registration against Freeze 1's immutability clause. |
| **X-9** | GL-0139 records PROD's branch as `knowelegeos-modelling`; PROD is now on `election-flow-updated` | S-GL | Recorded. |
| **X-10** | S-VC18 l.9: "The Principal authorized option 1 of Q-1"; S-VCR l.668: the orchestrator *took* sending the task as that choice, and "the task created no governance act" | S-VC18, S-VCR | The manuscript reports the review record's reading. |
| **X-11** | OP names two legitimate outcomes (l.12); S-VC18 proposes a third (OPEN), which GL-0140 records as "defined in no governing document" | S-OP, S-VC18, S-GL | Reported as an open matter (V-1). |
| **X-12** | G-LOG-0028 calls itself "informational / non-normative" (l.567), but D-ETR-1 = O1 incorporates it ("follows … G-LOG-0028 after S5b", GL l.2010) | S-GL | Reported. Its normative force today comes from O1. |

# F-SERIES v1.3-R — AUTHORITY, OWNERSHIP & CONFORMANCE REVIEW

**Status:** assessment only. No redesign, no implementation, no human decision taken.

**Commission (human, 2026-09-25):** *"write the authority and ownership review"*, with ten additions (items 1–10), all answered below.

**Subject:** `prompts/F-SERIES-v1.3-R-DESIGN-FOR-REVIEW.md` (commit `d41c68439`, "the Design").

> ## Central question
>
> **Can F-Series enforce execution integrity without acquiring authority to define what the KnowledgeOS reconstruction means?**
>
> **Answer [D]:** **yes, in principle, for 15 of the 31 rules and mechanisms in the Design. Not demonstrably yes for the other 16:**
> - 3 are semantic;
> - 6 are operational (they change methodology) and need a decision first;
> - 7 belong to machinery already allocated to, or planned by, the **governance session**. Two of these (the audit's enforced blindness and its cadence) are also operational.
>
> By the commission's own rule, **implementation stays BLOCKED.**

**Tags:** **[F]** source fact (file + lines) · **[D]** derived · **[O]** open.

Paths are under `docs/knowledgeos/knowledgeos_theory_chronological_extraction/` unless stated.

---

## 0. New evidence read for this review, and corrections it forces

**Read this session:**

| Document | Status | Lines read |
|---|---|---|
| `architecture/research-control-architecture.md` (the **RCA**, 399 lines) | **PROPOSED**, authority generated (RCA L6) | L1–20, L76–399 |
| `governance/L0-DECISION-RECORD-01.md` (467 lines) | — | L1–257 and L258–467 in full |
| `governance/GIA-DECISION-PACKAGE-01.md` | — | GIA-5…GIA-10 rows, L59–90, L158 |
| `SESSION-RECORD-RCI.md` | — | L75–97 |
| `evidence/README.md` | — | L1–105 |

**Corrections to my own earlier documents** (the Map `fce30354e`, the Design `d41c68439`, the DD review):

| # | Earlier claim | Correction | Evidence |
|---|---|---|---|
| K-1 | cited ARCH RA-15 and RA-16 as binding authority | **No L0 act approves ARCH v1.2 Addendum B (RA-13…RA-16)**, nor v1.0 or v1.1 ("as was also the case for v1.0/v1.1"). This is open item GI-1. The lane's own research records therefore *refuse* to invoke RA-13…RA-16 as authority. The conclusions I drew from RA-15/RA-16 stand, but on other grounds: MP §35 L3881–3899 (never use one status for another); MP §0E.4 L938; GOV README and L0-DEC-15 for control-plane authority. **Citations of RA-13…16 must carry "GI-1 open".** | L0 record L218; `RESEARCH-STATE-SYNC-01.md` L6, L54, L114 |
| K-2 | "STATE HOLD" was the operative block | STATE (dated 2026-09-23 after F0040) is **older than** L0-DEC-29/30. The **operative** constraint is **L0-DEC-30**: research is released YELLOW **only** for Critical Attack Pass 01, with **"No corpus reading … no file is opened, admitted or receipted"**, and **1b not authorized** | L0 record L451–467 |
| K-3 | the evidence-binding owner question was only B-15 | A **governance plan exists**: the RCA (PROPOSED). It allocates the canonical manifest to *"a governance step (**not** the research session)"*. It plans increments **1b** (evidence binding), **2** (traceability, *dispositions per contribution*, no-destructive-mutation check) and **3** (independent governance, periodic independent audits). **Most of the Design's machinery re-derives these increments inside a research lane** | RCA §5 L200, §8 L291–323, §11 L354–365 |
| K-4 | several rules were "implementation constraints, stricter never looser" | three are **operational** (they change methodology); see §3 | the review's trichotomy; RCA "New?" column L128–131 |
| K-5 | the Design's §18 translation table | **withdrawn from the Design's authority.** It is recorded as a *proposed protocol-boundary artifact* (§6) | commission item 5 |
| K-6 | version lineage via MP §5A is executable | MP §5A leaves **m1** (the hash computation) and **m2** (how an unversioned row becomes v1) undefined. Governance records them as **"for L0 to confirm (or for L0 to delegate to governance)"**. The Design's mechanism J would decide m1/m2 | L0 record L230–257 (GI-3) |
| K-7 | F3082 was the next file | Under canonical order the next file is **F0041**. F3082 is non-canonical (**H-3**), and "F3082 first" is the **H-11** issue (§9) | Map §F; SRE-Q1 L0 record L191 |
| K-8 | `exec_*` = execution metadata | the disposition vocabulary is a **semantic** construct (§4) | RCA §9 L325–338 |

---

## 1. Authority sources — who can bind whom

| Source | Normative status (evidence) | Binds |
|---|---|---|
| **L0 human acts** | binding. Recorded in `L0-DECISION-RECORD-01.md`; every entry carries *"Attestation: UNVERIFIED (AI transcription)"* | everything |
| **ARCH** | treated as governing by MP (L11–15), STATE (L13) and README-P. **No recorded L0 approver** for any version (GI-1). Header: "FROZEN v1.2" | Phase boundaries; [O] its L0 standing |
| **MP** | governing Phase-1 protocol (Map §1). The 2026-09-23 role amendment was **recorded by L0-DEC-22** | Phase-1 semantics |
| **GOV** (`gates.yaml`, `gate-runner.py`, door, `governance-state.yaml`) | **temporary** control mechanism with human activation (GOV README L1–10). **Only the governance session may modify runner, `gates.yaml` and door; the research session is excluded (L0-DEC-15)** | enforcement |
| **RCA** | **PROPOSED**, not adopted (RCA L6). Adoption is RC-H-05, open; its "Addendum B" slot collides with ARCH v1.2 (GI-2) | nothing yet; it is the only written allocation plan |
| **EVB** (`evidence/`) | *"RESEARCH-PRODUCED PROPOSAL … NOT AUTHORITATIVE"* (EVB L3). ADOPT/ADAPT/REJECT is **GIA-9, open**. L0-DEC-17 deliberately does *not* use its manifest | nothing |
| **L0-DEC-27 (MVG)** | binding: *"L0 decides only: release, scope changes, exceptions, phase boundaries, and R1 defects. Technical choices inside an approved scope are not L0 decisions."* | the delegation boundary |
| **F-Series lane** (F-LOG, protocols, contracts) | human instructions recorded **in the research lane only**. **[O]** They have not been transcribed into the L0 record the way research-side acts were (L0-DEC-08…10 precedent) | [O] (H-15) |

---

## 2. The ten authority questions

| # | Question | Answer, with evidence |
|---|---|---|
| **Q1** | Who owns each semantic rule? | **MP** (Phase 1), under L0 for changes (L0-DEC-22 precedent; ARCH §9 L484–486 with the GI-1 caveat). F-Series owns **none** (Design §5) |
| **Q2** | Who owns each execution mechanism? | Split three ways (§5). **Governance session:** runner, gates, door (L0-DEC-15); `admit.py` (L0-DEC-18); manifest generation (RCA §5, proposed). **Research agent:** receipts, research records, research state, stop records (RCA §8, proposed). **Independent auditor:** audit reports, source-first inventory (RCA §8, proposed; L0-DEC-25/28 practice) |
| **Q3** | Who may change each mechanism? | governance-owned mechanisms: governance session + independent review + L0 acceptance (L0-DEC-13 sequence 1a.1–1a.7). Research-owned: the research session, inside a released scope (L0-DEC-27) |
| **Q4** | Which mechanisms are implementation details? | the 20 **mechanical** items (§3), of which 15 are F-implementable (§5) |
| **Q5** | Which constitute protocol changes? | the 3 **semantic** items (disposition vocabulary, correction schema, translation) and, if made binding, the 6 **operational** items |
| **Q6** | Which require governance authorization? | anything touching GOV files (L0-DEC-15); EVB treatment (GIA-9); 1b (not authorized, L0-DEC-30); m1/m2 (GI-3); any corpus reading (a new release, L0-DEC-27/30); the write zone (H-10) |
| **Q7** | Which may Claude implement autonomously after GO? | only the 15 items marked **F: YES** in §5, **and only as "technical choices inside an approved scope"** (L0-DEC-27). GO itself is a scope/release act (L0) |
| **Q8** | Which require human approval? | release/scope (L0-DEC-27); H-1…H-15 (§10); GI-1, GI-3, GIA-9, GIA-10, RC-H-01/02/05/06; the approval of the v1.3-R spec |
| **Q9** | Which belong in MP/ARCH rather than F-Series? | disposition vocabulary and contribution granularity (MP / RCA increment 2; RCI-008 "granularity NOT-YET-DEFINED", RCA L154); correction-request schema (MP, P-1); the Phase-1→Phase-2 translation contract (protocol boundary, §6); the look-ahead policy (MP §0E / RCA L163: "reading order … deliberately not an invariant") |
| **Q10** | What must be true before F3082 can start? | §9 |

---

## 3. Three-way constraint classification (every rule and mechanism in the Design)

**Definitions** (from the commission; aligned with the RCA's existing "New?" column, RCA L128–131):

| Class | Definition | RCA equivalent | Who may introduce it |
|---|---|---|---|
| **MECHANICAL** | MP says X; the implementation guarantees X mechanically, or measures without prohibiting anything MP permits | *NO* or *MECHANISM* | implementer, inside scope |
| **OPERATIONAL** | forbids or requires something MP leaves open, or fixes a methodology parameter MP leaves undecided | *RULE* (methodology) | L0 / protocol change |
| **SEMANTIC** | defines what a record or judgment means | *RULE* | MP amendment only |

**The 31 items:**

| # | Item (Design §) | Class | Why |
|---|---|---|---|
| 1 | controlled paged reader (§7-A) | MECHANICAL | guarantees MP §2 complete reading |
| 2 | source admission ADMIT/STOP (§7-A, R11) | MECHANICAL | guarantees MP §4 identity |
| 3 | canonical manifest (§6) | MECHANICAL | derived from the canonical log |
| 4 | read receipts (§7-A) | MECHANICAL | binds bytes to `FILE_READ` |
| 5 | corpus/protocol/schema hashes (§7-D) | MECHANICAL | MP §36A L4006–4026 |
| 6 | §5A record hashes (§7-J) | MECHANICAL, **reserved** | m1/m2 are L0's (GI-3) |
| 7 | `exec_seal` (§7-E) | MECHANICAL | integrity only; prohibits nothing |
| 8 | Git-CAS (§7-F) | MECHANICAL | crash safety, MP §36A |
| 9 | the deterministic unit frame as **the coverage denominator** (§7-B) | **OPERATIONAL** | it fixes the coverage granularity that RCI-008 marks NOT-YET-DEFINED |
| 10 | inventory floor: every unit covered or dispositioned (§7-C) | **OPERATIONAL** | an obligation beyond MP's "nothing silently omitted" |
| 11 | disposition vocabulary `RESTATES` / `NO-SUBSTANTIVE-CONTENT` (§7-C) | **SEMANTIC** | §4 |
| 12 | MATH/CODE never dispositionable (§7-C) | **OPERATIONAL** | forbids a judgment MP permits |
| 13 | quote-verbatim byte check (§14, §7-C) | MECHANICAL | a byte comparison |
| 14 | independent audit with enforced blindness (§16) | **OPERATIONAL** | restricts what an auditor may know; commissioning is governance's (RCA §8) |
| 15 | audit cadence, first + every 5th (§16) | **OPERATIONAL** | a methodology parameter |
| 16 | Layer-1 isolation (§7-I) | **OPERATIONAL** | forbids consultation MP permits |
| 17 | quarantine non-reference (§7-H, R15) | MECHANICAL | MP §45: pilot artifacts disposable |
| 18 | TDI self-check (quote, `why`, not-document-kind) (§14) | MECHANICAL | mirrors KOS-G-010…012. **The "not seeded from P3A" part is a reviewer judgment** |
| 19 | TDI measurement (class balance, `false` counts) (§14) | MECHANICAL | measurement only (§7) |
| 20 | STATE integration (§15.3) | MECHANICAL | but the owner conflict is RC-H-06 (E-7) |
| 21 | `RECONSTRUCTION-STATE.json` writes (§15.3) | MECHANICAL | MP §36A |
| 22 | local non-authoritative cache (§15.3) | MECHANICAL | never authoritative |
| 23 | governance preflight before a unit (§15.1) | MECHANICAL | consumes the door |
| 24 | correction workflow (§17) | **SEMANTIC** | what counts as a correction; P-1 schema absent; RC-H-04 precedent: correction units are scheduled by L0 |
| 25 | P3A pairing check (§13) | MECHANICAL | the agreement judgment itself is the extractor's |
| 26 | redesigned S-identifier guard (§13) | MECHANICAL | enforces MP §1 "never adopt" |
| 27 | consultation log (§12, R13) | **OPERATIONAL** | a new obligation; overlaps RCA §5's proposed `research_traversal` field |
| 28 | the look-ahead policy (§12) | **OPERATIONAL** | methodology (H-4; RCA L163) |
| 29 | STOP reason codes and record (the RCA §7 pattern) | MECHANICAL | consumed, not defined by F |
| 30 | the Phase-1→Phase-2 translation table (§18) | **SEMANTIC** | a Published-Language artifact (§6) |
| 31 | execution-phase names `EXTRACTION` … `AUDIT` (§8.1) | MECHANICAL | labels only |

**Tally:**

| Class | Count | Items |
|---|---|---|
| MECHANICAL | **20** | 1–8, 13, 17–23, 25, 26, 29, 31 |
| OPERATIONAL | **8** | 9, 10, 12, 14, 15, 16, 27, 28 |
| SEMANTIC | **3** | 11, 24, 30 |
| **Total** | **31** | |

This is the **only** constraint-class tally in the document. §5 has a separate **ownership** tally (15 / 7 / 6 / 3); the two classify different things and must not be conflated. Items 14 and 15 are operational in class. In ownership terms they fall under governance-proposed machinery, because the RCA's *proposed* allocation (§8, not adopted) gives audits to an independent auditor.

**The Design's R1 ("implementation constraint, stricter never looser") is withdrawn as a category [D].** "Stricter" is not semantically neutral: forbidding what MP permits is operational (K-4).

---

## 4. Protocol-inside-protocol test (commission item 4)

**Test [D]:** an artifact is protocol-inside-protocol if it (a) records a **judgment about the source**, or (b) **changes which judgments are permissible**, whatever its label.

| `exec_*` construct | (a) judgment about the source? | (b) changes permissible judgments? | Verdict |
|---|---|---|---|
| `NO-SUBSTANTIVE-CONTENT:<reason>` | **yes**: it asserts that a span of the source carries no substantive content. RCA §9 lists *"whether a source unit is 'substantive'"* among what automation must not decide (L327), and the *proposed* RCA increment 2 plans "dispositions per contribution" (L361) without naming an implementer | yes: it is the only way to discharge coverage | **PROTOCOL-INSIDE-PROTOCOL** |
| `RESTATES:<unit>` | **yes**: an identity-of-content judgment between two passages | yes | **PROTOCOL-INSIDE-PROTOCOL** |
| `exec_coverage` | no: it is computed | **yes**, through the denominator it fixes (item 9) | operational, not semantic |
| `exec_audit.verdict` | no (computed), **if** the compared fields are MP fields | no | passes, provided "NO-DISCREPANCY" is never read as correctness (§8) |
| `exec_consultation` | no | yes: it makes consultation a recorded obligation | operational |
| `exec_seal`, `exec_run_id`, `exec_unit_state`, `exec_units` | no | no | **passes** |
| `exec_signal` (ML/statistics label) | no (a descriptor) | no | passes |
| execution-phase names | no | no | passes |

**[D] Result:** 2 constructs fail outright (the disposition vocabulary) and 2 are operational. An `exec_*` prefix does **not** neutralise meaning. **F-Series cannot hold definition authority over the disposition vocabulary**, even though it implements the coverage machinery around it. Where that authority sits is **UNRESOLVED**: candidates are an MP amendment or the proposed RCA increment 2, and either needs an L0 act.

---

## 5. Complete ownership matrix — four authorities (evidence package)

*(Revised on the human's instruction of 2026-09-25: "Complete the ownership matrix before any human governance decision … evidence-preparation task only." It assigns nothing and recommends no allocation. It records what the evidence establishes and marks **UNRESOLVED** where it establishes nothing.)*

### 5.1 Parties

| Code | Party |
|---|---|
| **L0** | the human authority. Every L0 record carries *"Attestation: UNVERIFIED (AI transcription)"* |
| **GOV-S** | the governance session |
| **RES** | the research session / research agent. **The F-Series lane is a RES lane** |
| **AUD** | an independent auditor |
| **RUN** | the gate runner / mechanical instrument. **It holds no authority** |
| **MP** | the rule is written in the Master Protocol; changing that text is an L0 protocol act (L0-DEC-22 precedent) |

### 5.2 Evidence keys

The standing of each source is kept explicit:
- **B** = binding L0 decision;
- **P** = proposed RCA allocation (**not adopted**; RC-H-05 open);
- **M** = MP text;
- **R** = research-side proposal (not authoritative);
- **O** = unresolved.

| Key | Source | Standing | Content relied on |
|---|---|---|---|
| **B15** | L0-DEC-15 (L0 record L279) | **B** | *"only the governance session modifies `gate-runner.py`, `gates.yaml` and the door … The research session is excluded"* |
| **B16** | L0-DEC-16 (L280, L285) | **B** | activation pins the gate-definition hash; pins are **written by the human** |
| **B17** | L0-DEC-17 (L281, L284) | **B** | KOS-G-003's known ID set = the canonical list; the research-produced `CORPUS-MANIFEST.jsonl` stays non-authoritative (GIA-9) |
| **B18** | L0-DEC-18 (L297) | **B** | GOV-S may repair **the specific** `admit.py --audit` defect, tests-first, followed by independent review; *"No general `admit.py` redesign"* |
| **B05** | L0-DEC-05 (L129–152) | **B** | the RC-H-04 correction: *"The research session executes (C-5). A separate session reviews (C-6). L0 closes (C-7)"*; ordering *"AFTER extended 1a AND 1b are accepted by L0"* |
| **B22** | L0-DEC-22 (L326–340) | **B** | a protocol amendment is recorded as an L0 act |
| **B25** | L0-DEC-25, L0-DEC-28 (L362, L412–426; verifier standing L419) | **B** | a fresh verification is required; the verifier was *"fresh in context; not organisationally independent"*; the record is committed *"unchanged"* by governance |
| **B27** | L0-DEC-27 (L369–410) | **B** | *"L0 decides only: release, scope changes, exceptions, phase boundaries, and R1 defects. Technical choices inside an approved scope are not L0 decisions"* |
| **B30** | L0-DEC-30 (L451–467) | **B** | current release: Critical Attack Pass 01 only; no corpus reading; 1b not authorized; entry requires a live door `CLEAR` |
| **BG** | `governance-state.yaml` L1–10; KOS-G-060 (`gates.yaml` L464–481) | **B** (rule text in a human-owned file) | activation is a human act; the research session must not add entries; acceptance is recorded by a human |
| **P5** | RCA §5 (L196–224) | **P** | manifest produced by *"a governance step (not the research session)"*, and *"a human approves the baseline"*; receipt *"appended by the research unit"*, verified by the runner; proposed `research_traversal` field |
| **P6** | RCA §6 (L234–257) | **P** | per-source *source admission control* (RC-H-01, open) |
| **P7** | RCA §7 (L259–289) | **P** | STOP reason codes, stop record, resume authority per code |
| **P8** | RCA §8 (L291–323) | **P** | roles and write zones; auditor contract; zones "advisory" |
| **P11** | RCA §11 (L354–365) | **P** | increments 1b (evidence binding), 2 (dispositions per contribution; no-destructive-mutation), 3 (periodic independent audits). **No implementer named** |
| **P3** | RCA §3 (L128–163; RCI-008 L154) | **P** | RCI-008 *"granularity of 'contribution' is NOT-YET-DEFINED"*; *"Deliberately not an invariant: reading order"* |
| **F-B15** | `SESSION-RECORD-RCI.md` L83 | **R** (research record of a governance-plan fact) | *"The governance plan assigns Increment 1a/1b evidence-binding implementation to the governance session"* |
| **R-EVB** | `evidence/README.md` L3 | **R** | *"RESEARCH-PRODUCED PROPOSAL … NOT AUTHORITATIVE"*; ADOPT/ADAPT/REJECT = GIA-9 |
| **R-D** | the Design `d41c68439` and F-Series contracts | **R** | the F-Series proposals |
| **R-FL** | F-LOG-0002 RL-01 (F lane) | **R** (human approval recorded in the research lane only; its L0 standing is **H-15**) | approval of F protocol v1.0, including no-look-ahead |
| **M-x** | MP sections as cited | **M** | — |

### 5.3 The matrix

**Row types:**
- rows **1–31** are the items classified in §3 and counted in the tallies;
- rows marked **G** are grouping rows added for completeness (not counted): the evidence-binding package and the governance-controlled machinery.

**Classification columns:**
- **Sem / Op / Mech** — the §3 constraint class.
- **F** — F-Series implementable: **YES** · **PROPOSE-ONLY / CONSUME-ONLY** · **DECISION FIRST** · **NO**.

**Every authority cell carries a tag:**
- **[F]** source fact;
- **[D]** derived mapping;
- **[O]** unresolved.

| # | Item | Sem | Op | Mech | Current owner | Normative status | MP change? | Human decision | F | DEFINE | CHANGE | APPROVE | EXECUTE / IMPLEMENT |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| 1 | controlled paged reader | | | ✓ | none | R-D only | no | H-14a | YES (behind admission) | **UNRESOLVED** [O: H-14a]; research-side proposal [F: R-D §7-A] | UNRESOLVED [O] | UNRESOLVED [O]. If it is an in-scope technical choice, not L0 [D: B27] | reading is the worker's act [F: M §9 step 2 L1765]; RES [D] |
| G-a | **evidence-binding package (1b)** | | | ✓ | GOV-S **proposed** | P11 + F-B15 (not adopted); R-EVB exists | no | GIA-9, 1b acceptance | — | GOV-S — **proposed** [F: P11; F-B15] | UNRESOLVED [O] | **L0 acceptance is required** before the correction runs [F: B05 C4-b] | implementation GOV-S — **proposed** [F: F-B15]; the research session produced EVB steps 3–6, **non-authoritative** [F: R-EVB] |
| 2 | source admission (`admit.py`) | | | ✓ | GOV-S (repair only) | **B18** for the named repair; **P** otherwise | no | RC-H-01, GIA-9 | PROPOSE-ONLY | design: GOV-S — **proposed** [F: P5, P6] | **the named defect repair: GOV-S** [F: B18]; any general redesign **UNRESOLVED** [O; B18 forbids it under that grant] | independent review, then L0 acceptance [F: B18; 1a.7 pattern L428–449] | RUN (`admit.py`), invoked by the reading unit — **proposed** [F: P5 L218–224] |
| 3 | canonical manifest | | | ✓ | none authoritative | P5; EVB artifact non-authoritative | no | RC-H-02, GIA-9 | PROPOSE-ONLY | *"a governance step (not the research session)"* — **proposed** [F: P5 L200] | UNRESOLVED [O] | *"a human approves the baseline"* — **proposed** [F: P5 L200]; RC-H-02 open [O] | EVB's manifest was research-produced and is non-authoritative [F: R-EVB; B17]. KOS-G-003 uses the canonical list directly [F: B17] |
| 4 | read receipts | | | ✓ | none authoritative | P5; EVB receipts non-authoritative | no | 1b acceptance | YES (after 1b) | part of 1b — **proposed** [F: P5 L202] | UNRESOLVED [O] | 1b acceptance by L0 [F: B05 C4-b] | *"appended by the research unit"*, the runner verifies — **proposed** [F: P5 L202]; receipts are never backfilled [F: R-EVB L87] |
| 5 | corpus/protocol/schema hashes | | | ✓ | MP rule | **M** §36A L4006–4026 | no | — | YES | **MP** [F: M §36A] | L0 protocol act [D: B22] | L0 [D: B22] | the state writer; which session owns writing is **open in MP itself** [F: M §49 item 4 L4705] → **UNRESOLVED** [O] |
| 6 | §5A lineage/hash mechanics (m1/m2) | | | ✓ (reserved) | L0 | **GI-3 open** | no | **GI-3** | NO until L0 | **UNRESOLVED**: *"for L0 to confirm (or for L0 to delegate to governance)"* [F: GI-3, L0 record L244–254] | UNRESOLVED [O: GI-3] | L0 [F: GI-3] | UNRESOLVED [O]. The revising unit applies it once defined [D: M §5A] |
| 7 | execution seal `exec_seal` | | | ✓ | none | R-D only | no | H-14a | YES | **UNRESOLVED** [O: H-14a]; research-side proposal [F: R-D §7-E] | UNRESOLVED [O] | the scope: L0 [F: B27]; a technical choice inside it is not L0 [D: B27] | RES [D: R-D] |
| 8 | Git-CAS | | | ✓ | none | R-D only; zones P8 (advisory) | no | H-10, H-14a | YES (in an approved zone) | **UNRESOLVED** [O: H-14a]; research-side proposal [F: R-D §7-F] | UNRESOLVED [O] | the write zone: **UNRESOLVED** [O: H-10]; RCA's zones are proposed and "advisory" [F: P8 L310–321] | RES [D] |
| 9 | coverage denominator (unit frame used as the measure of coverage) | | ✓ | | none | **P3: granularity NOT-YET-DEFINED** | possibly | H-16 | DECISION FIRST | **UNRESOLVED** [O: P3 RCI-008; H-16] | UNRESOLVED [O] | UNRESOLVED [O] | RES computes; RUN may check form [D] |
| 9a | deterministic unit frame (as a measurement only) | | | ✓ | none | R-D only | no | H-14a | (part of 9) | **UNRESOLVED** [O: H-14a]; research-side proposal [F: C02 v1.1 §2] | UNRESOLVED [O] | UNRESOLVED [O] | RES [D] |
| 10 | inventory floor | | ✓ | | none | P11 increment 2 (no implementer named); R-D | possibly | H-16 | DECISION FIRST | **UNRESOLVED** [O: H-16]; the proposed increment 2 covers "dispositions per contribution" [F: P11 L361] | UNRESOLVED [O] | UNRESOLVED [O] | RES [D] |
| 11 | disposition vocabulary `NO-SUBSTANTIVE-CONTENT` / `RESTATES` | ✓ | | | **none; not F-Series** | absent from MP; P11 increment 2 (proposed) | **yes** | H-16 | NO | **UNRESOLVED; not F-Series** [D: §4]. Candidates: an MP amendment [D: B22] or the proposed increment 2 [F: P11] | L0 [D: B22] | L0 [D: B22] | RES records dispositions [D]; RUN checks *form only* [F: P, RCA §4 L187]; *meaning* is judged by an auditor or reviewer [F: P8 L302–306, proposed] |
| 12 | MATH/CODE units may not be dispositioned | | ✓ | | none | R-D (C02 v1.1 §5) | possibly | H-16 | DECISION FIRST | **UNRESOLVED** [O: H-16]; research-side proposal [F: R-D] | UNRESOLVED [O] | UNRESOLVED [O] | RES [D] |
| 13 | quote-verbatim byte check | | | ✓ | MP rule | **M** (verbatim `quoted_signal` L53; `excerpt_or_reference` L1486) | no | — | YES | rule: **MP** [F: M]; check specification **UNRESOLVED** [O: H-14a] | spec: UNRESOLVED [O] | in-scope technical choice [D: B27] | RES self-check [D] |
| 14 | independent audit | | ✓ | | none adopted | P8 (auditor contract, proposed); B25 practice | no (advisory) | H-14a | PROPOSE-ONLY | *see §5.4* | *see §5.4* | *see §5.4* | *see §5.4* |
| 15 | audit cadence | | ✓ | | none | P11 increment 3 "periodic independent audits" (proposed); R-D (first + every 5th) | no | H-14a | PROPOSE-ONLY | **UNRESOLVED** [O]; proposed in increment 3 [F: P11 L362]; research-side value [F: R-D C13] | UNRESOLVED [O] | UNRESOLVED [O] | an auditor [D] |
| 16 | Layer-1 isolation | | ✓ | | none | R-D (C15) | possibly | H-16, HDR-1 | DECISION FIRST | **UNRESOLVED** [O: H-16]; research-side proposal [F: R-D C15] | UNRESOLVED [O] | accepting the residual = **HDR-1** [O] | RES and the auditor attest [D: R-D C15 §4] |
| 17 | quarantine | | | ✓ | none | R-D only | no | H-14a | YES | **UNRESOLVED** [O: H-14a]; research-side [F: `quarantine/README.md`] | UNRESOLVED [O] | release from quarantine: **UNRESOLVED** [O] (the Design called it a human act [F: R-D §7-H]) | RES [D] |
| 18 | TDI self-check | | | ✓ | rule MP; gates GOV-S | **M** P1-Q1; gates **B15/BG** | no | — | YES (self-check, never a gate) | rule: **MP** [F: M L38–60]; gates KOS-G-010…012: **GOV-S** [F: B15]; self-check spec **UNRESOLVED** [O: H-14a] | gates: **GOV-S only** [F: B15]; self-check: UNRESOLVED [O] | gate activation: **L0** [F: BG; B16] | gates: RUN [F: `gates.yaml`]; self-check: RES [D]; the *"not seeded from P3A"* judgment: **UNRESOLVED** reviewer [O] |
| 19 | TDI measurement | | | ✓ | none | R-D | no | H-17 | YES (class balance and `false` counts only) | descriptive metrics: **UNRESOLVED** [O: H-14a]; **reference set: UNRESOLVED** [O: H-17] | UNRESOLVED [O] | reference set: UNRESOLVED [O: H-17] | RES [D] |
| 20 | STATE integration | | | ✓ | RES writes today | ARCH RA-11/12 (**GI-1 open**); P8 L295 (proposed); **Q18 vs Q61 conflict** | no | RC-H-06 | YES (after RC-H-06) | ARCH RA-11/12 [F; standing GI-1 open] | UNRESOLVED [O: RC-H-06] | UNRESOLVED [O: RC-H-06] | RES — **proposed** [F: P8 L295]; conflict pending [F: P8 L317] |
| 21 | `RECONSTRUCTION-STATE.json` writes | | | ✓ | MP schema | **M** §36A | no | MP §49 item 4 | YES | **MP** [F: M L3949–4026] | L0 protocol act [D: B22] | L0 [D: B22] | **UNRESOLVED**: MP itself leaves *"which agent/session boundary owns writing it"* open [F: M §49 item 4 L4705] |
| 22 | local non-authoritative cache | | | ✓ | none | R-D only | no | H-14a | YES | **UNRESOLVED** [O: H-14a]; research-side [F: R-D §15.3] | UNRESOLVED [O] | in-scope technical choice [D: B27] | RES [D] |
| G-b | **governance-controlled machinery**: `gate-runner.py`, `gates.yaml`, door | | | ✓ | **GOV-S** | **B15/B16** | no | — | CONSUME-ONLY | **GOV-S** [F: B15]; *"Governance proposes status"* [F: `gates.yaml` header] | **GOV-S only; RES excluded** [F: B15] | activation and pins: **L0** [F: BG; B16]; a separate session reviews and L0 accepts [F: B15 text] | RUN measures [F: GOV README §2] |
| 23 | governance preflight / door | | | ✓ | GOV-S | **B15** | no | — | CONSUME-ONLY | **GOV-S** [F: B15] | **GOV-S only** [F: B15]; wiring into project-root configuration is a human act [F: GOV README L171] | L0 (activation) [F: BG] | RUN; RES invokes it and must see `CLEAR` before starting [F: B30 entry obligation] |
| 24 | correction workflow (semantics and schedule) | ✓ | | | MP semantics; L0 schedule | **M** §5A; **B05** for RC-H-04 | P-1 (schema absent) | H-5 | NO | semantics: **MP** §5A three cases [F: M L1352–1358]; request schema **UNRESOLVED** [O: P-1]. **F-Series acquires no semantic authority by implementing lineage** [D] | L0 [D: B22] | **L0 schedules and closes** — binding **for RC-H-04** [F: B05]; the generalisation to every correction is [D] | RES executes C-5 [F: B05]; *"a separate session reviews"* [F: B05] |
| 25 | P3A pairing check | | | ✓ | MP rule | **M** §42 | no | — | YES | rule: **MP** [F: M L4409–4437]; check spec **UNRESOLVED** [O: H-14a] | spec: UNRESOLVED [O] | in-scope technical choice [D: B27] | check: RES [D]; **the agreement/disagreement judgment is the reconstruction's own research work** [F: M §42] |
| 26 | S-identifier guard (redesigned) | | | ✓ | MP rule | **M** §1 | no | — | YES | rule: **MP** "consult, never adopt" [F: M L1030–1041]; guard spec **UNRESOLVED** [O: H-14a] | spec: UNRESOLVED [O] | in-scope technical choice [D: B27] | RES [D] |
| 27 | consultation logging | | ✓ | | none | P5 `research_traversal` (proposed); R-D §12 | no | RC-H-05, H-4 | DECISION FIRST | **UNRESOLVED** [O]; proposed state field [F: P5 L207–213]; research-side proposal [F: R-D §12] | UNRESOLVED [O] | UNRESOLVED [O: RC-H-05] | RES [D] |
| 28 | look-ahead policy | | ✓ | | none binding | **M** §0E permits targeted forward investigation; P3 "reading order … not an invariant"; R-FL | possibly | **H-4**, H-15 | DECISION FIRST | **UNRESOLVED** [O: H-4]. MP permits [F: M L813–886]; RCA proposes free traversal [F: P3 L163]; the F-Series strict rule was approved by the human **in the research lane only** [F: R-FL] (L0 standing = H-15) | UNRESOLVED [O] | UNRESOLVED [O: H-4] | RES [D] |
| 29 | STOP protocol | | | ✓ | none adopted | **P7** | no | RC-H-05 | CONSUME-ONLY | GOV-S — **proposed** [F: P7] | UNRESOLVED [O] | UNRESOLVED [O: RC-H-05] | *"the stop is always recorded by the party that stopped"*; resume authority per reason code (L0 / GOV-S) — **proposed** [F: P7 L283–284] |
| 30 | Phase-1 → Phase-2 translation | ✓ | | | **none; not F-Series** | ARCH §4 RA-3 (**GI-1 open**) requires recorded translation; no contract exists | yes / [O] | **H-18** | NO | **UNRESOLVED** [O: H-18]. ARCH requires translation to be recorded [F: ARCH L294; GI-1 open]. **Not F-Series** [D: §6] | UNRESOLVED [O] | UNRESOLVED [O] | UNRESOLVED [O]: the Phase-1 hand-over and Phase-2 intake [D] |
| 31 | execution-phase names | | | ✓ | none | R-D only | no | — | YES | **UNRESOLVED** [O: H-14a]; research-side [F: R-D §8.1] | UNRESOLVED [O] | in-scope technical choice [D: B27] | RES [D] |

### 5.4 The audit, decomposed (row 14)

| Audit authority | Party | Evidence | Standing |
|---|---|---|---|
| **defines audit requirements** | an auditor contract: *"assume the research artifacts may be wrong … read the canonical source first … build an independent inventory before opening any research artifact … state independence limits"* — authored as governance architecture | [F: P8 L302–306] | **proposed** |
| | the F-Series C13 v1.2 §3 audit specification | [F: R-D] | research-side proposal |
| **commissions / approves** | L0 has commissioned verifications on governance work (L0-DEC-25 required a fresh one; L0-DEC-28 recorded the route) | [F: B25] | **binding**, for those verifications. For research-output audits: **UNRESOLVED** [O] |
| | for the RC-H-04 correction: *"A separate session reviews (C-6)"* | [F: B05] | **binding**, for RC-H-04 |
| **performs** | an *independent auditor* who writes audit reports only | [F: P8 L298] | **proposed** |
| | precedent: a subagent launched by the implementing session was *"fresh in context; **not organisationally independent**"* | [F: B25] | **binding** classification of that instance |
| | **"fresh agent" ≠ organisational independence** | [F: B25] | — |
| **records the result** | *"audit reports only (`docs/knowledgeos/governance/audits/`)"* | [F: P8 L298] | proposed |
| | governance committed a verifier's record *"unchanged … read in full by governance before commit"* | [F: B25 L0-DEC-28] | **binding** precedent |
| **accepts** | a human authority records acceptance; *"an instrument the author wrote is not an independent acceptor"* | [F: BG, KOS-G-060] | **binding** rule text |

### 5.5 Tallies (ownership — distinct from the §3 class tally)

| Ownership outcome | Count | Items |
|---|---|---|
| **F: YES** (possibly after a named dependency) | **15** | 1, 4, 5, 7, 8, 13, 17, 18, 19, 20, 21, 22, 25, 26, 31 |
| **PROPOSE-ONLY / CONSUME-ONLY** (governance-owned or governance-proposed) | **7** | 2, 3, 6, 14, 15, 23, 29 |
| **DECISION FIRST** (operational) | **6** | 9, 10, 12, 16, 27, 28 |
| **NO** (semantic; not F-Series) | **3** | 11, 24, 30 |
| **Total** | **31** | grouping rows G-a, G-b and sub-row 9a are not counted |

**Four-authority profile of the 31 counted rows (facts of this matrix, not a recommendation):**

| Profile | Count | Items |
|---|---|---|
| DEFINE is **UNRESOLVED**, with F-Series holding only a research-side proposal | **13** | 1, 7, 8, 9, 10, 12, 15, 16, 17, 19, 22, 27, 31 |
| DEFINE is MP text for the rule (the check or implementation specification may be UNRESOLVED) | **7** | 5, 13, 18, 21, 24, 25, 26 |
| DEFINE is L0-reserved or L0-open | **3** | 6 (GI-3), 28 (H-4), 30 (H-18) |
| DEFINE is a **binding** GOV-S allocation (B15) | **1** | 23 |
| DEFINE is a **proposed** GOV-S allocation (RCA) | **4** | 2, 3, 4, 29 |
| DEFINE for the audit requirements is proposed (RCA) or research-side | **1** | 14 |
| DEFINE is ARCH (GI-1 open) | **1** | 20 |
| DEFINE is UNRESOLVED and **not F-Series** by the §4 test | **1** | 11 |
| **Total** | **31** | 1, 7, 8, 9, 10, 12, 15, 16, 17, 19, 22, 27, 31 · 5, 13, 18, 21, 24, 25, 26 · 6, 28, 30 · 23 · 2, 3, 4, 29 · 14 · 20 · 11 |
| **APPROVE held by RES/F-Series** | **0** | — |
| **CHANGE of a binding governance-owned mechanism held by RES/F-Series** | **0** | B15 excludes RES |

**[D] What the evidence establishes, and what it does not:**
- No counted row gives F-Series APPROVE authority.
- No row gives F-Series CHANGE authority over binding governance machinery.
- **No source establishes that F-Series DEFINES any mechanism.** Every F-Series definition is a research-side proposal, and for 13 rows the defining authority is UNRESOLVED.
- Whether those 13 become F-Series definitions, governance definitions, or split definitions is **H-14a**, which this matrix does not decide.

---

## 6. Translation contract ownership (commission item 5)

**Removed from the Design's authority.** The Design's §18 table becomes:

> **PROPOSED PROTOCOL-BOUNDARY ARTIFACT — "Phase-1 → Phase-2 Translation Contract (draft input)".** Not authoritative; not owned by F-Series.

| Question | Evidence | Status |
|---|---|---|
| Where does translation live architecturally? | ARCH §4: the handoff is an ACL, the Published Language is the RRP, *"every crossing is translated and recorded"* (RA-3, L294). RA-3 is an original invariant, not Addendum B, but still carries GI-1 (no recorded approver) | [F] |
| Why not in ARCH? | ARCH is frozen; changes need a §9 proposal (L484–486) | [F] |
| Candidate homes | (a) MP "What this context HANDS OVER" (L62–66); (b) S2's intake side (ORIGIN axis §5C.2, gates Q51/Q52); (c) a separate boundary contract referenced by both | [O] |
| Required properties (from the review) | deterministic where possible · explicit · versioned · provenance-preserving · auditable · non-destructive | proposed |
| Owner | **not F-Series.** L0 decides placement | **H-18** |

---

## 7. TDI measurement precondition (commission item 6)

**Rule [D], proposed for the TDI self-check:**
- Until a **governance-approved, human-labelled reference set** exists, TDI reporting is limited to class balance (`true`/`false` counts), the number of `false` answers with their `why`, confidence distribution, and coverage (entries / files read).
- **No** sensitivity, specificity, recall, precision, false-positive or false-negative rate may be reported or implied.

**Basis:**
- **[F]** MP's own analogous rule: the P3A comparison is a *"provisional empirical recovery estimate"*, deliberately *"not called 'P3A recall'"*, because recall needs a known ground truth (MP L4429–4435).
- **[F]** The discriminator has never returned `false` (TDI policy §3; STATE "C2 measurable ≠ validated"; KOS-G-013 is a REVIEW gate).
- **[D]** Without a reference set, the error rate on the `false` side is undefined, not zero.
- **Do not tune the discriminator before measurement.**

**Owner of the reference set:** governance design; L0 approves the sample (**H-17**).

---

## 8. Coverage and audit-independence invariants (commission items 7–8)

**INV-COV — coverage is syntactic only.**
- Unit coverage is a **syntactic** measure over a byte-derived frame. It may be computed mechanically.
- **Semantic** coverage (every meaning captured) and **epistemic** coverage (every claim with its correct status, scope and qualification) are **not** computable by the frame.
- Unit coverage must never be reported, labelled or used as either of them, nor as reconstruction completeness, `EXTRACTION_COMPLETE` sufficiency or `dossier_status` evidence of correctness.
- Every report carrying a coverage figure states *"syntactic coverage"*.
- Basis: MP §40 L4235 (process indicators *"never theory quality or correctness"*); RCA §4 (C2 "form only", L187).

**INV-IND — agreement under a shared method is not corroboration.**
- Every audit record discloses its **common-method components**: reader, page model, unit frame, schema, prompt family, model family, examples, parser, and any shared exposure.
- Agreement between extractor and auditor under shared components is **methodological agreement**. It is **not independent corroboration** (ARCH RA-15 D, GI-1 caveat; MP §25 L3292 *absence of an edge ≠ independence* is the analogous discipline) and **not scientific validation** (Phase 2).
- The **shared frame is itself a common-method component**. The Design's "shared deterministic frame" and "independence" are therefore stated together, never separately.
- Basis: C13 v1.2 §3 ("procedural, not statistical"); L0-DEC-28 ("fresh in context; not organisationally independent"); RCA §8 item 5 ("state independence limits: shared model family, prior exposure", L308).

---

## 9. Corrected execution start conditions (commission item 9)

### 9.1 Before ANY corpus file can execute under v1.3-R

| # | Condition | Evidence | State today |
|---|---|---|---|
| S-1 | H-1 = R-A, recorded as an L0 act | — | open |
| S-2 | **H-14a: the allocation of execution-assurance machinery** (research lane vs governance session) | RCA §5/§8 (proposed); L0-DEC-15, 18; GIA-8 (proposed: research must not commit governance artifacts); B-15 | open |
| S-3 | **a new Research Release** under L0-DEC-27 whose scope includes corpus reading | L0-DEC-30: "No corpus reading" | ⛔ not released |
| S-4 | an L0 ruling on whether **1b** (evidence binding) must be accepted before new reads | SRE-3 (L0 record L193): no admission control exists; RCA increment 4 precondition "increments 1–2 passing … L0's call" (L363) | open |
| S-5 | the scope respects SAFE-RESEARCH-EXCEPTION-01 | no F0031–F0040 dependency; no F0032/35/36/40; "F0041+ progression" (= chronological advancement, L0-DEC-09a) forbidden until C-7; out-of-sequence safe reads not prohibited by it | standing |
| S-6 | a live door `CLEAR` at unit start | L0-DEC-30 entry obligation pattern | per unit |
| S-7 | H-10 (write zone / lane) and H-11 (population and order) | Map §F; MP L4284–4290 | open |
| S-8 | the 6 operational items decided (H-16) or removed from the spec | §3 | open |
| S-9 | the 3 semantic items removed from F-Series (§4, §6; P-1 for corrections) | — | design change needed |
| S-10 | the v1.3-R execution spec approved by a human; the §20 tests RED → GREEN | Design §24 | not started |
| S-11 | GI-3 (m1/m2) decided **before any §5A revision** (not needed for first reads) | L0 record L244–254 | open |

### 9.2 F3082 specifically

| # | Condition | Evidence |
|---|---|---|
| F-1 | all of §9.1 | — |
| F-2 | **H-3: F3082 is not a canonical ID.** The canonical list holds F0001–F3081; KOS-G-003's known set is that list (L0-DEC-17), so any F3082 citation in the governing workspace is a dangling reference | Map §F I1–I4 |
| F-3 | **H-11: "F3082 first" is an F-Series population/order artifact.** Under canonical registry order the next unread canonical ID is **F0041** (SRE-Q1, L0 record L191). F0041 is itself not executable today: L0-DEC-30 forbids corpus reading, and chronological advancement to F0041+ is forbidden until C-7 | L0-DEC-07, 09a, 30 |
| F-4 | **H-12:** the existing F3082 read was performed by the orchestrating session (R14; non-isolated). Whether it may count as evidence | Map §H |

**[D] Consequence:** F3082 cannot be the first file under a conformant v1.3-R unless L0 decides H-3 (canonical membership) **and** H-11 (an order that places it first). Both are open.

---

## 10. Human decisions (refined; none taken here)

| Id | Decision | Minimum information |
|---|---|---|
| **H-1** | F-Series role (R-A / R-B / R-C) | ⚠ **refined by K-3:** the "execution-assurance layer" R-A describes overlaps the RCA's increments 1b/2/3, which the RCA *proposes* (not adopted) for governance; the binding parts are L0-DEC-15/18 (runner, gates, door, `admit.py` repair). R-A for a research lane collides with that proposed allocation unless H-14a decides otherwise |
| **H-14a** | who defines and implements execution-assurance machinery: the governance session (RCA, proposed; L0-DEC-15/18 for runner and admission), the research lane (F-Series), or a split | §5 (15 F-implementable vs 7 governance-owned) |
| **H-14b** | EVB treatment (= **GIA-9**: ADOPT/ADAPT/REJECT per artifact) | EVB L3; L0-DEC-17 note |
| **H-15** | whether the F-Series human rulings (F-LOG RL-01…10, FD-11…14, …) are transcribed into the L0 record, as research-side acts were (L0-DEC-08…10) | §1 |
| **H-16** *(new)* | coverage granularity and the operational items 9, 10, 12, 16, 27 (RCI-008's NOT-YET-DEFINED granularity) | §3, §5 |
| **H-17** *(new)* | a TDI human-labelled reference set: whether, what size, who labels | §7 |
| **H-18** *(new)* | placement and ownership of the Phase-1→Phase-2 translation contract | §6 |
| carried | H-2 · H-3 · H-4 · H-5…H-8 · H-10 · H-11 · H-12 · H-13 · HDR-1 · HDR-6 | the Map and the Design, unchanged |
| existing L0 items | GI-1 (architecture approval) · GI-3 (m1/m2) · GIA-9 · GIA-10 · RC-H-01/02/05/06 · SQ-1/SQ-2 · C-5…C-7 | L0 record |

---

## 11. Conformance assessment

| Dimension | Verdict | Basis |
|---|---|---|
| Semantics stay with MP | **NOT YET** | 3 semantic items remain in the Design (§4) |
| Methodology unchanged by F | **NOT YET** | 8 operational items, 6 of which need decisions (§3, §5) |
| Governance authority respected | **NOT YET** | 7 items belong to governance-owned machinery (binding: L0-DEC-15/18) or governance-proposed machinery (RCA, not adopted) (§5); the Design proposed building them in a research lane |
| Epistemic separation | **CONDITIONALLY YES** | §8.2 of the Design holds; invariants INV-COV and INV-IND added (§8) |
| Authority citations | **CORRECTED** | RA-13…16 now carry GI-1 (K-1) |

```text
v1.3-R AUTHORITY & CONFORMANCE STATUS
-------------------------------------
Central question (integrity without semantic authority):   YES for 15/31 · NOT DEMONSTRATED for 16/31
Proceed to implementation design:                          NO
Implementation authorization:                              BLOCKED
F3082 authorization:                                       BLOCKED (H-3, H-11, H-12, plus §9.1)
Canonical next candidate:                                  F0041 — not executable today (L0-DEC-30; SRE-01 until C-7)

Next safe step:
Human review of this evidence package. H-1 and H-14a are the decisions it informs; the package
recommends neither an owner nor an allocation (§5.5, §12).
```

---

## 12. What remains for human decision

**Unresolved decisions and evidence gaps only.** Nothing here is resolved or recommended.

| Id | Open question | Where it sits | Evidence in this package |
|---|---|---|---|
| **H-1** | F-Series role (R-A / R-B / R-C) | human | §0 K-3; §10 |
| **H-14a** | who holds DEFINE / CHANGE / APPROVE / EXECUTE for execution-assurance machinery; 13 rows have an UNRESOLVED definer | human | §5.3, §5.5 |
| **GI-1** | no recorded L0 approval of the research architecture (v1.0, v1.1, v1.2 Addendum B) | L0 record L218 | §0 K-1; §1 |
| **GIA-9** | ADOPT / ADAPT / REJECT of the research-produced evidence binding (`evidence/`) (= H-14b) | GIA package L71 | §5.3 rows G-a, 2–4 |
| **GI-3** | §5A mechanics m1 (hash computation) and m2 (unversioned row → v1): *"for L0 to confirm (or for L0 to delegate to governance)"* | L0 record L244–254 | §5.3 row 6 |
| **RC-H-05** | adopt or reject the RCA (and its "Addendum B" slot collision, GI-2) | RCA §13 | §1; every **P**-key in §5.2 |
| **1b acceptance** | whether increment 1b is accepted, and whether it must be accepted before new corpus reads | L0-DEC-05 C4-b; L0-DEC-30; SRE-3 | §5.3 rows G-a, 4; §9.1 S-4 |
| **H-15** | whether F-Series human rulings recorded in the research lane (F-LOG) are transcribed into the L0 record | human | §1; §5.2 R-FL; row 28 |
| H-16 | coverage granularity and operational items 9, 10, 12, 16, 27 | human (RCI-008 NOT-YET-DEFINED) | §3; §5.3 |
| H-17 | a TDI human-labelled reference set | human | §7; row 19 |
| H-18 | placement and ownership of the Phase-1→Phase-2 translation contract | human | §6; row 30 |
| H-4 | the look-ahead policy | human | row 28 |
| H-10 | the write zone / lane | human | row 8 |
| RC-H-01 / RC-H-02 / RC-H-06 | source admission design · manifest baseline · Q18 vs Q61 (STATE writer) | RCA §13 | rows 2, 3, 20 |
| MP §49 item 4 | which session owns writing `RECONSTRUCTION-STATE.json` | MP L4705 | rows 5, 21 |
| **Evidence gap** | no source defines who owns a *research-output* audit (as opposed to governance-work verifications) | — | §5.4 |
| **Evidence gap** | no source names an implementer for RCA increments 2 and 3 | RCA §11 | rows 10, 15 |

*Stop. No redesign, implementation or decision follows from this document.*

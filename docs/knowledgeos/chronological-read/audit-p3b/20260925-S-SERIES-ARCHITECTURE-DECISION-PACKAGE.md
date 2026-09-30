# S-Series architecture decision package (Decision A): decision support only, unranked

**Scope:** S-Series only. **This document decides nothing and recommends nothing.**

**Sources:** committed artifacts only:
- G-LOG-0068 mapping (`20260925-S-SERIES-CONFORMANCE-MAPPING.md`) and G-LOG-0069 analysis (`20260925-S-SERIES-GOVERNANCE-DECISION-ANALYSIS.md`);
- Master Protocol v3.5 (`prompts/20260911_0221_prompt3-optimized.md`);
- P3b v1.7 (`prompts/20260924_2311_p3b-…-v1.7.md`, frozen by G-LOG-0034);
- Research Architecture v1.2 (`../knowledgeos_theory_chronological_extraction/prompts/KNOWLEDGEOS-RESEARCH-ARCHITECTURE.md`);
- V1.2.1 (`51e04f8cb`);
- the decision matrix (`S-SERIES-NEXT-ARCHITECTURE-DECISION-MATRIX.md`).

F-lane material is cited only where G-LOG-0069 already cites it: the location of the Step-2 gates and of P1-Q1.

**Cell status vocabulary:**

| Label | Meaning |
|---|---|
| **SAT** | already satisfied |
| **MISS** | missing |
| **CLAR** | requires clarification (documentation or governance record) |
| **PREV** | requires a protocol revision (P3b §26) |
| **IMPL** | requires implementation |
| **UNRES** | unresolved (a human decision) |
| **N/A** | not applicable |

**The four states:**
- **DEFER:** no governance act. Current governance stays in force by default.
- **A:** an explicit governance record keeps v3.5 + P3b as the S-lane framework, with no architecture above them.
- **B:** v1.2 adopted as the S-lane architecture.
- **C:** v1.2 governs boundaries and invariants; v3.5 remains the controlling procedure; P3b remains the operating annex.

**DEFER vs A:**
- **Operationally identical:** the same rules apply.
- **They differ only in the record:**
  - **A** closes F-01 by a recorded human act, and states that the choice can be revisited;
  - **DEFER** leaves F-01 open. Future readers still face the ambiguity (F-01, F-02, and the untracked copy).

## Two facts that constrain B and C

1. **The v1.2 ACL enforcement gates live in non-S artifacts.**
   - The v1.2 §4.2b gates `Q38`, `Q53`, `Q54` and `Q55` are defined **only** in the Step-2 protocol, whose header reads "PROPOSAL … not adopted · not frozen".
   - `P1-Q1` (RA-10) is defined only in the F-lane Phase-1 protocol.
   - **Consequence:** "apply the v1.2 gates directly" means depending on F-lane protocol text, one of which is not adopted even in its own lane.
2. **The S-lane already has a Research-State-like artifact.**
   - P3b §24 defines `P3B-STATE.json` as "mutable execution position (holds no rules)". That is the same property v1.2 §8A requires of Layer 3 ("Layer 3 holds no rules").
   - v1.2 RA-11 names a specific file, `KNOWLEDGEOS-RESEARCH-STATE.md` (F-lane). Under B or C, an equivalence would need to be declared rather than a new file created.

---

## 1. Decision matrix

| # | Dimension | DEFER | A: keep v3.5 + P3b | B: adopt v1.2 | C: controlled adoption |
|---|---|---|---|---|---|
| 1 | Governing architecture | none declared (**UNRES**, F-01 open) | none, by record (**CLAR**: decision entry) | v1.2 (**PREV**: P3b §7/D-18 replaced) | v1.2 for boundaries and invariants only (**PREV**) |
| 2 | Controlling procedure | v3.5 Part A (**SAT**) | v3.5 (**SAT**) | v3.5 remains the procedure; a precedence rule is needed if v1.2 §9 ("protocol changes") meets P3b §26.4 ("v3.5 never edited") (**UNRES**) | v3.5 for procedure, v1.2 for boundary; the precedence rule is stated in the revision (**PREV**); a v3.5-located contradiction stays **UNRES** |
| 3 | P3b role | operating annex (D-01) (**SAT**) | the same (**SAT**) | annex under v3.5, now also bound by v1.2 invariants (**PREV**) | the same as B (**PREV**) |
| 4 | Phase-1 boundary | v3.5 "Phase 1" = P1 (R0); no context boundary (**SAT** as written) | the same (**SAT**) | the v1.2 Phase-1 context mapped to v3.5 P1–P3 (**PREV** + **UNRES** on exact edges) | the same as B |
| 5 | Phase-2 boundary | none declared (**SAT** as written) | the same | the v1.2 Phase-2 context mapped to v3.5 P5–P7 (**PREV**) | the same |
| 6 | P4 placement | P4 follows P3 (A12) (**SAT**) | the same | **UNRES:** P4 (v1.2 membership) has no v1.2 context assignment | **UNRES** |
| 7 | Theory Discovery Index | not required (**N/A**) | not required (**N/A**) | required by RA-10 / P1-Q1; absent (**MISS** → **IMPL**, or a declared backlog per v1.2 "enforcement frozen ≠ compliance achieved") | the same as B |
| 8 | Published Language | not required; P4 hand-over exists (P3b §24) (**SAT**) | the same | the hand-over exists; the PL declaration and per-crossing translation record (RA-3) are missing (**CLAR** + **IMPL**) | the same as B |
| 9 | ACL-1…ACL-4 | v3.5 equivalents act as principles (R2/R3/R16; B2 closed list; A11 search-before; R17) (**SAT** as principles) | the same | binding; the enforcement gates are defined only in F-lane protocols (fact 1) (**UNRES**: direct vs S-equivalent gates) | the same as B |
| 10 | Epistemic status model | v3.5 R6/R9/R15; P3b layers A/B/C (**SAT**) | the same | RA-15's four statuses must be mapped onto R15/§1B (**CLAR**) | the same as B |
| 11 | Layer-5 canonicalization | GATE "Never FINAL"; R20; P6 acts (**SAT**) | the same | RA-4 is satisfied by the same rules (**SAT**) | **SAT** |
| 12 | Research State authority | v3.5 A3 runbook; `P3B-STATE.json` (**SAT**) | the same | RA-11 names an F-lane file; an equivalence to `P3B-STATE.json` and A3 must be declared (**CLAR**) | the same as B |
| 13 | P3b theory-verification work (§1 priority 1; H-19 §9F) | inside P3 (**SAT** under v3.5) | the same | conflicts with v1.2 §7 (experiments: Phase 1 "evidence only"; falsification: Phase 1 "record historical") and §2B (2C) (**UNRES** → **PREV**) | the same as B |
| 14 | H-19 relationship | a P3b addendum; SEALED; S5c PROHIBITED (**SAT**) | the same | a prediction test = Phase-2 attack activity; placement follows row 13 (**UNRES**) | the same as B |
| 15 | Discovery-Augmented Reconstruction | P3b §1/§9B/§13/§9E within R19 (**SAT**) | the same | also maps to 1B, §6, RA-5, RA-7 (**SAT** conceptually; **IMPL** for the 1B artifact) | the same as B |
| 16 | R19 | in force (**SAT**) | the same | in force (v3.5 unchanged); RA-13 is permissive, with no collision (§2 F-07) (**SAT**) | the same |
| 17 | V1.2.1 | executable on human authorization; independent (**SAT**) | the same | the same; it is implementation-layer instrumentation under v1.2 §8A (**SAT**) | the same |
| 18 | F-02 ("Master Protocol" ×2) | ambiguity persists (**UNRES**) | **CLAR** (path/version citation, glossary) | **CLAR** (more pressing: v1.2 §8B's "Master Protocol" then sits in the S-lane's governing chain) | the same as B |
| 19 | F-03 ("Phase 1", "v1.2", "research-first", "validation") | persists (**UNRES**) | **CLAR** | **CLAR** + **PREV** (the glossary becomes normative once v1.2's vocabulary governs) | the same as B |
| 20 | F-04 | N/A | N/A | (a) record kind: **CLAR**; (b) the verification activity: **PREV** (row 13) | the same |
| 21 | F-05 | N/A | N/A | **IMPL** or a declared backlog (row 7) | the same |
| 22 | F-06 | N/A | N/A | **CLAR** + **IMPL** + **UNRES** (gates) (rows 8–9) | the same |
| 23 | F-07 | N/A | N/A | no conflict; **CLAR** to record the permissive reading | the same |
| 24 | P3b §26 change required? | no | no | **yes** (new annex version; §26.6 freeze check) | **yes** |
| 25 | v3.5 change required? | no | no | no (P3b §26.4) | no |
| 26 | Architecture v1.2 change required? | no | no | no, on current evidence (G-LOG-0069 §4.2) | no, on current evidence |
| 27 | S/F isolation risk | unchanged | unchanged | increased: v1.2's gates, Research State and examples are F-lane-keyed (fact 1); a bright-line rule "architecture only, no F-lane protocols, gates or evidence" is needed (**CLAR**) | the same as B (the boundary-only scope limits what is imported) |
| 28 | Governance complexity (structure, not a value judgment) | one authority chain; one open question | one authority chain | two authorities over one lane, plus a precedence rule | two authorities with explicitly partitioned scopes |
| 29 | New implementation obligations | none | none | a 1B index (or backlog), a PL translation record, S-equivalent ACL gates (if chosen), a Research-State equivalence | the same as B |
| 30 | Reversibility | trivially revisable (nothing recorded) | revisable by a later act | revisable only by another §26 revision plus a governance act | the same as B |

## 2. Findings F-04…F-07, traced

### F-04: abstraction difference vs genuine conflict

**Abstraction difference:**
- P3b's `THEORY-CANDIDATE` (§1B layer C) is a *register record kind*: "a possible explanation or improved structure; provisional and testable".
- v1.2's "Candidate Theory" (§7 table; §4.1 Phase-2 return "candidate theory") is a *Phase-2 deliverable*.
- These are different objects that share a word. A register record resembles v1.2 1B index content ("candidate mechanisms · theory branches · unresolved theoretical questions", §2A), provided it is kept as "an INDEX, never a theory". **A clarification suffices.**

**Genuine context-responsibility conflict:**
- P3b's head principle sets "Verification is the objective; reconstruction is the control layer … 1. theory verification; 2. falsification and disconfirmation; 3. … prediction on held-out evidence (H-19)".
- v1.2 §7 assigns experiments to Phase 2 ("Phase 1: evidence only"), falsification to Phase 2 ("Phase 1: record historical"), and §2B places attack and test in 2C.
- Under v1.2, P3b (a Phase-1-context annex) would own Phase-2 work. **Resolving it requires a P3b revision** that assigns the verification layer to the Phase-2 context. RA-13 permits it to run before Phase 1 completes. No architecture change is needed.

### F-05: the Theory Discovery Index

**Text:**
- RA-10: "Phase 1 carries a Theory Discovery Index (1B) … enforced by P1-Q1, an artifact + gate".
- The freeze table reads "P1-Q1 … enforcement FROZEN · ⚠️ no index exists yet — backlog open".
- §0: "Enforcement frozen ≠ compliance achieved. A rule can be binding while the work it demands is unfinished."

**Classification from the text:**
- it is binding once adopted;
- the architecture itself tolerates its absence as an **open compliance backlog**;
- the text sets no deadline and no execution gate that would halt other work.

It is therefore **a compliance backlog under a binding rule**. Whether it must exist before a particular step is not stated by the text (**UNRES** if B or C).

### F-06: what exists vs what v1.2 requires

**Exists (P3b §24):**
- the "P4 hand-over package: per-object statuses + timelines + births + edges + the research register summary and carried-forward questions + escalations", inside `30-RECONCILIATION.md`;
- the underlying registries: `02-FILES.jsonl` / `12-SOURCE-REGISTER.md`, `03-CONTRIBUTIONS.jsonl`, `20-FAMILIES/**`, `31-RECONCILIATION-PAIRS.jsonl` and `32-RECONCILIATION-OBJECTS.jsonl`.

**v1.2 §4.1 Published Language, mapped item by item:**

| v1.2 §4.1 item | S-lane counterpart |
|---|---|
| corpus registry | 02-FILES / 12-SOURCE-REGISTER |
| evidence objects | 03-CONTRIBUTIONS |
| file reconstruction records | 02-FILES records |
| theory objects | families / object records |
| definitions, assumptions | family completeness roll-up and assumption register (v3.5 A10) |
| claims | contributions |
| derivations, relationships | typed edges; the 31 pairs |
| contradictions | CONTESTED / CONTRADICTION rows |
| gaps | absences (R17) |
| scope/regime | B2 SCOPE |
| provenance | S-ids (R11) |
| historical ordering | timelines and births |
| reconstruction confidence & status | the B4 status vector |
| branches / merges | partly: lifecycle candidates (A10) |
| **Theory Discovery Index** | **MISS** |
| **theory threads** | **MISS** |

**To be declared under B or C:**
1. the package is the Published Language;
2. the item-by-item correspondence above;
3. a per-crossing translation record (RA-3 "every crossing is translated and recorded");
4. the ACL gate question (fact 1).

### F-07: RA-13 and R19, precisely

- **R19** forbids executing Phase N+1 *as a substitute for* an unfinished Phase N. It prohibits **substitution**.
- **RA-13** states Phase 2 "does not require Phase 1 completion … The Reconstruction Package is a data contract, never a completion gate", and "a blocked obligation blocks its thread, never the phase". It **permits** starting Phase-2 work on coherent threads and **defines** blocking at thread granularity.
- **Collision test:** a Phase-2 activity on a thread while Phase-1 obligations elsewhere remain open. It substitutes for nothing as long as those obligations still run to completion, so R19 is untouched.
- **Where they could collide:** only if early Phase-2 output were used to **declare** unfinished Phase-1/P3 obligations met. R19 forbids that, and RA-13 does not require it.
- **Residual difference:** v3.5 A13 builds P7 synthesis "from Phases 2–6 only", i.e. the final synthesis waits for completed phases, while RA-13 speaks of Phase-2 work generally. These are compatible (early thread work is not the final synthesis). **A clarification records this; no revision is needed.**

## 3. Option analyses

### DEFER

| | |
|---|---|
| **Unchanged** | everything: v3.5 R0–R20 and A2–A14; P3b v1.7 (including §7/D-18); V1.2.1; H-19 SEALED; S5c PROHIBITED |
| **Newly authoritative** | nothing |
| **Becomes non-authoritative** | nothing |
| **Must be revised** | nothing |
| **Can remain unchanged** | all artifacts |
| **Unresolved** | F-01 stays open; the F-02/F-03 ambiguity persists; the untracked copy's status stays undecided |

### A: keep v3.5 + P3b by record

| | |
|---|---|
| **Unchanged** | the same as DEFER |
| **Newly authoritative** | the governance record stating that the S-lane has no declared architecture above v3.5 + P3b, and that v1.2 remains a separate lane's architecture |
| **Becomes non-authoritative** | nothing changes authority. What becomes explicit is that v1.2's invariants bind the S-lane **only as principles** (mapping §2: 17 correspondences) |
| **Must be revised** | none required. Optional documentation: glossary (F-02/F-03); notes on F-10 (header) and F-11 (stale "v1.1"), since frozen files are not edited |
| **Can remain unchanged** | P3b §7/D-18 (consistent with A); v3.5; V1.2.1 |
| **Unresolved** | none arising from A itself. The P4/verification/ACL questions do not arise |

### B: adopt v1.2

| | |
|---|---|
| **Unchanged** | v3.5 text (P3b §26.4); V1.2.1; frozen P3b v1.7 as a historical file (superseded by the next version); H-19/S5c states; v1.2 text |
| **Newly authoritative** | v1.2's contexts, layers, ACL rules, invariants RA-1…RA-16 and change control (§9) over the S-lane |
| **Becomes non-authoritative** | P3b §7/D-18's exclusion; P3b's single-context reading of its verification priority (F-04b) |
| **Must be revised** | **P3b §26 revision:** replace §7/D-18; context mapping (rows 4–6); F-04b placement; declare the PL (§24 correspondence); ACL gates (direct or S-equivalent); Research-State equivalence (`P3B-STATE.json`); RA-15 ↔ R15 mapping; normative glossary; a bright-line S/F isolation clause |
| **Can remain unchanged** | v3.5; v1.2; P3b mechanics that already correspond (mapping §3: 27 A rows); V1.2.1; the Discovery-Augmented Reconstruction mechanisms (P3b §9B/§13/§9E) |
| **Unresolved** | P4 placement; verification ownership; ACL gate applicability; §4.3 precedence (v1.2 §9 vs P3b §26.4) if a v3.5-located contradiction ever appears; 1B timing |

### C: controlled adoption

| | |
|---|---|
| **Unchanged** | the same as B |
| **Newly authoritative** | v1.2 **for boundary questions only** (what a context may do; invariants; epistemic statuses; Layer 5). v3.5 stays authoritative for procedure; P3b for operation |
| **Becomes non-authoritative** | the same as B |
| **Must be revised** | the same P3b §26 revision as B, plus an explicit authority partition and conflict rule (boundary → v1.2; procedure → v3.5, then P3b; a P3b-located contradiction → revise P3b; a v3.5-located contradiction → observation plus human precedence decision) |
| **Can remain unchanged** | the same as B |
| **Unresolved** | the same as B. The partition narrows but does not eliminate the precedence question |

## 4. Discovery-Augmented Reconstruction (descriptive label; not an official term)

| Element | Existing place | Allowed augmentation or prohibited substitution |
|---|---|---|
| discovery channels | P3b §9B, §13 ("primary discovery channel") | augmentation |
| candidate mechanisms | register kinds (§13.2); layer C | augmentation (hypotheses only, §1D) |
| research registers | `P3B-RESEARCH-REGISTER.jsonl` (§24) | augmentation |
| trigger generation | a research design mechanism (design v2 C1–C3; not adopted) | augmentation **only if** it decides what is *additionally* read, never what the floor skips |
| targeted re-examination | v3.5 A11 ("found → back to P1-style capture"); P3b §11.4 | augmentation |
| correction requests | v3.5 R10 (append); P3b §26.5; `P3B-CORRECTIONS.jsonl` | augmentation |
| independent discovery | blind audit (P3b §21); v3.5 A8 | augmentation |
| hypothesis generation | §13.10 disconfirmation; §13.10a pre-registration | augmentation |
| per-label reconstruction floor | P3b §1 ("the controlled floor … not its ceiling"), §9.8, §23 | **the invariant that makes the rest augmentation** |

**Prohibited substitution** (R19; A11 TERMINAL; P3b §23):
- skipping required reads or the per-label roll-up;
- treating research recall as P3 completeness;
- declaring absences resolved without the A11 search (hub labels stay ESCALATED, §23 3b);
- treating a register record as layer-A evidence (G-12);
- turning candidates into theory (§1D).

**Requirement: no change.** The mechanisms already exist in P3b within R19. A **documentation clarification** would be needed only to adopt the descriptive name. Under B or C, the 1B mapping would additionally need the P3b revision already listed. **No architecture revision.**

## 5. V1.2.1 independence

**Decision A does not determine V1.2.1's executability:**
- V1.2.1's executability depends on:
  - the P3b paged reader (contract rev 3);
  - the H-19 seal;
  - the S5 common library and resolver (bound by N2);
  - the six PX0004 files (V1.2.1 §2);
  - its own guard (`V1.2.1-AUTHORIZATION.json` naming `51e04f8cb…`).
- None of these references an architecture.
- Its decision rules (V1.2.1 §17) cite no architectural invariant.
- Under every option in row 17 it remains implementation-layer instrumentation (P3b §26.7; v1.2 §8A).

**V1.2.1 execution does not determine Decision A:**
- its outputs go only to `pilot-s5-decomp/v1/` (non-production) and claim nothing about governance;
- its §22 excludes strategy, substitution and methodology conclusions.

**What a future authorization act must record about F-13:**
1. V1.2.1's outcome is an **instrument-validation result only**;
2. its proposition taxonomy (V1.2.1 §7) is **experimental vocabulary, not P3b/v3.5 types**. It does not conform to v3.5 B2 ("CLOSED LIST — never invent a type"; "a scope value is never a type") and is not adopted by executing V1.2.1;
3. **no v3.5 or P3b obligation changes;**
4. any adoption of the instrument into P3b requires a **P3b §26 revision** that maps its types to v3.5 B2, or restricts it to non-contribution use.

## 6. Untracked architecture copy (housekeeping)

| | |
|---|---|
| **Known** | the file is `chronological-read/prompts/KNOWLEDGEOS-RESEARCH-ARCHITECTURE.md`; it is byte-identical to the frozen v1.2 (sha256 `e3bbf292…`); it is untracked (`git status` "??"); its mtime is 2026-09-25 22:42; the human stated they pasted it |
| **Not known** | the purpose of the paste (not recorded in any artifact) |
| **Why it has no authority** | it is uncommitted and ungoverned. v1.2 itself requires protocols to "reference … must not duplicate or redefine it" (its header; §9). The canonical committed file exists at the F-lane path |
| **Why it does not establish adoption** | adoption requires a recorded human act and, for B/C, a P3b §26 revision (G-LOG-0069 §4.2). A file copy is neither |
| **Possible human actions** (none chosen) | keep temporarily (untracked, pending Decision A); remove; move outside `prompts/`; commit **only after** a governance decision, noting that under B/C a committed reference to the canonical path avoids duplicating the architecture |

## 7. Governance logging for this document

`P3B-GOVERNANCE-LOG.md` is specified as "append-only, human entries" (P3b §24), and "Human acts are recorded in `P3B-GOVERNANCE-LOG.md`" (P3b §17). This package records **no human act**. No written rule requires a G-LOG entry for an analysis-only document.

Prior sessions logged analyses as a **practice**, but that practice is not a stated rule. **Per the instruction, no G-LOG entry was added. The uncertainty is reported here** rather than resolved by inventing a rule.

## 8. HUMAN DECISION FORM

**Decision A1:** Should the S-Series have a declared architecture above v3.5 + P3b?
- Choices: **DEFER · A · B · C**

**Decision A2** (if B/C): Where does v3.5 **P4** (v1.2 membership) map in the v1.2 contexts?
- Choices: the Phase-1 context, the Phase-2 context, or boundary / handoff.

**Decision A3** (if B/C): Where does P3b's current **theory-verification activity** (§1 priority 1–4; H-19 §9F) belong?
- Choices: it remains in the P3b annex, or it is assigned to the Phase-2 context (running early under RA-13).

**Decision A4** (if B/C): Do the v1.2 **ACL gates** apply directly (defined only in F-lane protocols, one not adopted), or are **equivalent S-Series gates** defined?

**Decision A5:** Approve the **terminology clarification** for F-02/F-03 (path/version citation plus a glossary; no rename)?

**Decision A6:** Disposition of the **untracked v1.2 copy**:
- keep temporarily / remove / move / commit after the decision.

**Decision A7:** Approve the formal **restatement of options B/D** as augmentation-only (C, D-all), with B and D-selected recorded as prohibited substitutions under v3.5 + P3b?

**Decision B (separate; not merged with A):** Authorize **V1.2.1** execution under pre-registration `51e04f8cb621cee1b8083245d8068418ac80b6c5`, with the four F-13 statements of §5 recorded in the act?

---

**Stop.** This package changes no artifact, decides nothing, and does not authorize V1.2.1.

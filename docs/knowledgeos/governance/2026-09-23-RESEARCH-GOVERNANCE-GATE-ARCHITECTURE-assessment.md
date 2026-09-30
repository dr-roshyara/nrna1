# Research Governance Gate Architecture — Assessment & Proposal

| | |
|---|---|
| **Kind** | Architecture assessment + proposal (sections A–L as commissioned) |
| **Status** | ⚠️ **PROPOSED — authority: generated. Not authoritative without human review.** Nothing in this document is implemented, adopted, or authorized |
| **Date** | 2026-09-23 |
| **Produced by** | Claude Code session (engineering assistant acting in a Governance-Engineer *analysis* capacity — **no governance authority held**) |
| **Scope** | Governance of the KnowledgeOS research program (`docs/knowledgeos/knowledgeos_theory_chronological_extraction/`, Phase 1 / Phase 2) |
| **Modified** | No research, production, gate, hook or settings artifact was modified to produce this |
| **Evidence method** | Three read-only investigations (research architecture · authority model · enforcement mechanics) + direct spot-checks of the cited lines. V = verified in source · I = inference |

> **Headline:** the current governance architecture is **insufficient** for the target control model. The *vocabulary* and most *gate definitions* exist; **authority for the research program is undefined, no gate is executed by anything other than the agent being judged, and no enforcement point lies outside the executing agent's write reach.** Automating gates on top of this would produce a technically impressive layer resting on self-declared authority.

Abbreviations: `D/` = `docs/knowledgeos/knowledgeos_theory_chronological_extraction/` · `ARCH` = `D/prompts/KNOWLEDGEOS-RESEARCH-ARCHITECTURE.md` · `P1P` = `D/prompts/knowledge_os_protocoll.md` · `P2P` = `D/prompts/knowledge_os_step2_theory_construction_protocol.md`.

---

## A. CURRENT GOVERNANCE ARCHITECTURE

### A.1 Two separate governance systems that do not meet

| | **Engineering / platform governance** | **Research-program governance** |
|---|---|---|
| Governing text | ES-001..006 (**all PROPOSED**, `STANDARDS_INDEX.md:3`) · EEP (Adopted) · KOS-OPERATING-MODEL-001 (Adopted 2026-08-23) · Session-Completion protocol (Adopted) | `ARCH` v1.1 (**FROZEN 2026-09-22**, no approver recorded) · `P1P` · `P2P` (header still "PROPOSAL … not adopted", l.5–6) |
| Roles | Six adopted engineering roles; lane roles `governance·architecture·implementation·verification`; PO/ARB = Decision Authority | Verification modes SELF / INDEPENDENT / EXTERNAL_REQUIRED (`P2P` §10.2); "human research owner" (`EPISTEMIC-STATUS-VOCABULARY.md:3-4`) — **not mapped to PO/ARB** |
| State | `.claude/runtime/workflow/<WI>.json` (append-only by code path; **gitignored**) | **Three** state files, mutually inconsistent: `KNOWLEDGEOS-RESEARCH-STATE.md` (RA-11 authoritative, prose), `RECONSTRUCTION-STATE.json` (stale: `last_processed_file_id F0010` vs 25 read), `phase2_extraction/STEP2-STATE.json` |
| Machinery | `workflow-state.php`, `session-bootstrap.php`, `next-actor-orchestration.php`, … (contract-tested) | **None.** No script, test, hook or CI references `D/` (V, repo-wide grep) |

The research program has **no workflow record, work item, grant, or recorded human act** (newest workflow record 2026-09-04; the v1.1 freeze has none). V.

### A.2 What exists and is valuable (reuse, do not reinvent — ES-005.4)

- **Four-value verdict vocabulary** already defined: `PASS · PASS_WITH_OPEN_QUESTIONS · FAIL · INCONCLUSIVE`, with "INCONCLUSIVE never silently treated as a pass" (`P2P` l.710–712). V
- **Fault taxonomy**: THEORY / PROTOCOL / INSTRUMENT, "an instrument failure is NEVER evidence about the theory" (`P2P` §10.3c l.1619–1629); REPRESENTATIONAL_GAP classes incl. HUMAN_JUDGEMENT (§3B.4). V
- **Gate definitions**: Q1–Q61 (`P2P` §16), P1-Q1, §9A (12 per-file gates), §37 (14 batch checks), §45 Gates 0–7, Class-A prerequisites P-1..P-3 ("a gate may never require the output of the stage it guards"), freeze condition §3A.6, LAB-READINESS (9 conditions). V
- **Architectural separation** Architecture (rules) / Protocols / State, with "Layer 3 holds no rules" (`ARCH` l.333). V
- **Separation-of-duties text**: ES-001 authority chain — Governance owns fact→interpretation→recommendation, never the decision (`ES-001:41-50`); EEP "final authority is never delegated to an automated system" (`:18`). V
- **Reusable machinery**: `workflow-state.php` append-only ledger pattern (exit 0/64/65); `scripts/lib/EngineeringKnowledge` validator library with **injected-violation tests** (`Tests/BackTest/Phase0BackTest.php`); `greenfield-merge-gate.yml` — the one genuinely blocking CI job. V

### A.3 The actual control flow today

```
human prompt ─▶ executing agent ─▶ writes artifacts ─▶ same agent runs its own checks
             ─▶ same agent writes "PASSING" into the state file RA-11 makes authoritative
             ─▶ commit (no hooks active, no CI on docs/knowledgeos/**) ─▶ next step
```

Evidence of self-grading (V): Q59 "my first pass produced 2 violations of my own gate" (`B2-Q59-COMPLIANCE-REPORT.md` l.21); iteration-1 "17/17 quality gates passed" while 3 of 11 seed items had been dropped (`END-TO-END-ENFORCEMENT-AUDIT.md` l.62); `RECONSTRUCTION-STATE.json` gate booleans written by the executor; §45 Gates 1–3 "NOT executed" (l.93) yet scaling proceeded; Q54 "fired because a human said 're-examine F0022 and F0023'" (audit l.39–41).

**The target model does not exist today.** There is no PRE-GATE, no independent POST-GATE, and nothing that "permits" the next step.

---

## B. AUTHORITY MODEL

### B.1 Four things that must not be conflated

| Concept | Today | Finding |
|---|---|---|
| **Governance ROLE** | "Governance Engineer" — adopted role (`reviews/2026-08-19-six-role-operating-model-adoption.md`); role ≠ agent ≠ authority (`:30-36`, `:68`) | Exists. "Governance **Agent**" exists only in corpus/brainstorming text — **no contract** |
| **Governance AUTHORITY** | Governance recommends and records; **never decides, never executes** (`ES-001:41-66`). Decisions: PO/ARB (Decision Authority) | Exists for engineering. **For research: undefined** — no one is named for freezes, `ARCH` §9 changes, changeset authorization (E-*/R-* both still say "awaiting authorization" while applied), or gate grading |
| **Governance IMPLEMENTATION** | `--writer-role=governance` CLI string; lane role in records | Self-declared: any process can pass the string (`workflow-state.php:303-308`) |
| **Engineering assistant doing governance tasks** | What every Claude session actually is | Correct self-description in this session |

### B.2 What each actor can do — current vs. proposed

| Power | Current (V) | Proposed |
|---|---|---|
| **Block** progression | Nothing blocks (bootstrap exits 0 even on UNRESOLVED; no hooks wired) | Governance Agent may **withhold clearance** (a FAIL/INCONCLUSIVE verdict record); **a CI required check** is what physically blocks merge |
| **Modify** research artifacts | Anyone | Governance Agent: **never** (read-only; findings only). Executor: only within the step's declared write-set |
| **Modify gates** | Anyone, incl. the executor, same session | Human-authorized change only (see I) |
| **Record human acts** | The AI transcribes them as strings; engine checks non-empty only (`next-actor-orchestration.php:434-435`) | Human acts must be **produced by the human** in a channel the agent cannot write (signed commit/tag, PR approval by a CODEOWNER) |
| **Accept / freeze / waive** | Undefined for research | Human (research owner ≡ Decision Authority — *to be decided*, G-1) |

### B.3 Authority-model verdict

**Insufficient.** Three structural defects, each independent of automation:

1. **No named authority for the research program** (NOT-YET-DEFINED).
2. **Human acts are forgeable by the actor they authorize** — every "authorization" on record could have been self-issued; nothing distinguishes them.
3. **The authoritative records are writable by the executor** (research state in-tree, workflow state gitignored and editable).

---

## C. GAP ANALYSIS

| # | Gap | Class | Evidence (V) |
|---|---|---|---|
| G-1 | No research-program authority; research owner ↔ PO/ARB unmapped | Governance | `ARCH` §9 names no approver |
| G-2 | No executed gate — all self-graded or human-triggered | Enforcement | A.3 |
| G-3 | No enforcement point outside the executor's reach | Enforcement | settings/scripts writable; no git hooks; no CI on `docs/knowledgeos/**` |
| G-4 | Human acts unattested | Authority | B.2 |
| G-5 | Step-1 immutability unenforced; §5A hash-chain fields not implemented (0 rows); hashes `NOT_COMPUTED` | Enforcement | `RECONSTRUCTION-STATE.json:4,91`; RO-0014 rewrote 25 FILE-REGISTRY rows in place |
| G-6 | Three inconsistent state files; the authoritative one is prose | State | A.1 |
| G-7 | Schemas never frozen (§45 Gate 2) — THEORY-OBJECTS has 28 keysets across 30 rows | Methodology | P-2 cannot be checked |
| G-8 | No correction-request lifecycle (CR-02..04 exist only as a Markdown table; CR-01 absent) | Methodology | `B2-Q59-COMPLIANCE-REPORT.md` l.71–81 |
| G-9 | Rule documents carry stale state (`ARCH` l.39–40 "no index exists yet"; Q-table "Observed?" column) | Architecture | inverse of "Layer 3 holds no rules" |
| G-10 | Fault taxonomy lacks a **GOVERNANCE/PROCESS** defect class (self-graded gate, skipped §45 gate) and an explicit **EVIDENCE/RECORDING** class | Methodology | only prose admissions |
| G-11 | Verdict records have no format, no commit binding, no location | Enforcement | runtime state has no verdicts |
| G-12 | Gates themselves are untested (a prose check, CHECK 14, was itself wrong — `RECONSTRUCTION-STATE.json:135`) | Meta | — |
| G-13 | Gate applicability is not machine-derivable (which step triggers which Q-gates) | Architecture | Q-gates listed flat |
| G-14 | Status contradictions: `P2P` "not adopted/not executed" vs. Candidate Theory v0.6 exists; `step2_authorized: PENDING` | Governance | `RECONSTRUCTION-STATE.json:143` |
| G-15 | Recorded project posture is **checkpoints, not walls** (knowledge-lint D-3, verify.sh Gate 7, placement guard exit 1) | Posture | blocking research gates reverse a recorded decision |
| G-16 | Citation drift: "never accepts own work" cited as R-34, but R-34 is ruling-classification; the rule's only text is in a PROPOSED document | Governance | `ADR-AIP-LOG-Platform-Rulings.md:20` |

---

## D. PROPOSED GOVERNANCE GATE ARCHITECTURE

### D.1 Principle

> **Automate deterministic verification; preserve agent reasoning where necessary; preserve human authority where necessary — and put the trust anchor where the executing agent cannot write.**

### D.2 Components

```
┌─────────────────────────── HUMAN AUTHORITY (Decision Authority / research owner) ─────────────────────────┐
│  approves: ARCH changes · Gate Registry changes · baselines · waivers · freezes · L5 · acceptance          │
│  channel: signed commit / tag, or PR approval as CODEOWNER — NOT an AI-transcribed string                 │
└───────────────┬──────────────────────────────────────────────────────────────────────────▲─────────────────┘
                │ governs                                                                    │ escalates
┌───────────────▼─────────────┐   ┌──────────────────────────┐   ┌──────────────────────────┴──────────────┐
│ GOVERNING ARCHITECTURE      │   │ GATE REGISTRY (new)      │   │ GOVERNANCE AGENT (contract, new)         │
│ ARCH v1.1 + P1P + P2P       │──▶│ machine-readable: gate id│──▶│ • derives gate set DETERMINISTICALLY     │
│ (rules; unchanged)          │   │ → source rule, class,    │   │   from registry (may ADD, never REMOVE)  │
└─────────────────────────────┘   │ step-types, check impl,  │   │ • runs automated checks via runner       │
                                  │ fixtures                 │   │ • commissions agent-review in a SEPARATE │
                                  └──────────────────────────┘   │   session (no shared context)            │
                                                                 │ • routes HUMAN items; never decides them │
                                                                 │ • writes verdicts; NEVER edits artifacts │
                                                                 └───────┬───────────────────▲──────────────┘
                                      PRE-GATE verdict (clearance)       │                   │ POST-GATE verdict
                                                                 ┌───────▼───────────────────┴──────────────┐
                                                                 │ EXECUTING AGENT (step: READ/CONSTRUCT/   │
                                                                 │ IMPLEMENT/TEST/REFINE/CONSOLIDATE or P1  │
                                                                 │ file/batch) — declared write-set only    │
                                                                 └──────────────────────────────────────────┘
┌──────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│ GATE RUNNER (deterministic, read-only, versioned, fixture-tested) → VERDICT LEDGER (committed, append-only, │
│ each verdict bound to a commit SHA + runner version + registry version)                                     │
│ ENFORCEMENT: CI required check on protected branch (trust anchor) · Claude hooks = early warning only       │
└──────────────────────────────────────────────────────────────────────────────────────────────────────────┘
```

### D.3 Verdict semantics (reuse `P2P` §3A.6 vocabulary; add scope + fault class)

| Verdict | Who may emit | Meaning |
|---|---|---|
| `PASS` | runner (automated) or reviewer (agent-review) | every applicable check satisfied **within its declared scope** |
| `PASS_WITH_OPEN_QUESTIONS` | composite gate only | no FAIL; every open question is an **enumerated record** with an owner |
| `FAIL` | any | at least one violation; carries `fault_class` |
| `INCONCLUSIVE` | any | check could not run, zero items checked, tool error, missing input, or reviewer could not decide. **Blocks like FAIL** for progression; never counted as PASS |

Every verdict record states `scope` (e.g. *"form of Q59 satisfied; completeness of relations NOT assessed"*) so an automated PASS can never be read as an epistemic claim.

**Fault classes (extends §10.3c without redefining it — RA-9):** `THEORY` · `PROTOCOL` · `INSTRUMENT` (incl. runner bugs) · add `EVIDENCE/RECORDING` · add `GOVERNANCE/PROCESS` · `HUMAN_JUDGEMENT` (not a defect). **Rule: an automated gate failure is never classified THEORY.** A THEORY fault can only be asserted in Phase 2 TEST with a pre-registered falsifier (Q35), by review.

### D.4 Governance Agent contract (draft)

- **Inputs:** step declaration (step type, work unit, declared write-set, base commit), Gate Registry version, research state.
- **Outputs:** PRE-GATE and POST-GATE verdict records; findings; escalations.
- **May:** read everything; run the runner; commission a reviewer; refuse clearance; record.
- **May not:** edit any artifact under review; edit the Gate Registry, runner or fixtures; emit or transcribe a human act; downgrade a FAIL; decide any HUMAN item; be the same session that executed the step.
- **Relationship to ES-001:** the Governance Agent stays inside "fact → interpretation → recommendation"; a FAIL verdict is a *fact about conformance*, not a decision. The decision to proceed despite a FAIL is a **waiver — human only**.

### D.5 Interaction with READ → CONSTRUCT → IMPLEMENT (and Phase 1)

| Step | PRE-GATE (clearance to start) | POST-GATE (before next step) |
|---|---|---|
| **P1 file/batch** | base commit clean · Phase-1 baseline manifest valid · target in state's next-work | P1-Q1 · §9A records present · §37 deterministic checks · append-only vs baseline · Q61 |
| **Phase-2 entry** | Class-A P-1/P-2/P-3 (automated; P-2 needs G-7 closed) · Step-1 immutability | — |
| **READ** (0,A,B,C) | entry PASS | Q17/Q18 write-set · Q19 all nodes admitted (count) · Q5 · Q51 labels present |
| **CONSTRUCT** (D,B2,E,F,G) | READ PASS | Q14 stage ≤ F1 with open terms · Q35 falsifier present & timestamped · Q56 RA-9 (agent-review) |
| **IMPLEMENT** (L) | CONSTRUCT PASS | every divergence has fault_class (Q36 form) · I-1 `git diff` on Step-1 empty |
| **TEST** (H0,H) | IMPLEMENT PASS; **reviewer ≠ executor session** | §11.0 independence recorded; demotions only |
| **CONSOLIDATE** (M,K) | TEST verdict exists | Q41 theory current · Q60 seed ⊆ theory · L5 = 0 · Q61 state updated |
| **Freeze (§3A.6)** | — | composite gate → **HUMAN decision**, never automatic |

### D.6 Step-1 immutability mechanism

1. Human-approved **baseline manifest**: per Phase-1 file, SHA-256 + line count, bound to a signed tag.
2. **Append-only check** per JSONL: first *n* lines of the current file byte-identical to baseline; new lines only appended; non-JSONL Phase-1 files byte-identical.
3. Any Phase-1 change outside an authorized Phase-1 unit → FAIL(`GOVERNANCE/PROCESS`).
4. Corrections from Phase 2 → **CR records** (new, G-8), never writes.
5. **Legacy**: RO-0014's in-place migration pre-dates the baseline; it is recorded as a legacy finding, **not** retro-"fixed" and not silently grandfathered (human decision H-4).

---

## E. AUTOMATABLE CHECK CATALOG

Deterministic, script-able, read-only. Each needs a violation fixture (see I). Result is **form conformance only**.

| Check | Source rule | Deterministic test | Precondition |
|---|---|---|---|
| AUT-01 Corpus immutability | RA-1 | corpus hash manifest unchanged | manifest (Gate 3) |
| AUT-02 Phase-1 append-only | RA-2, Q9, Q17, Q25, I-1, I-12 | prefix-identity vs baseline; Step-1 git diff empty in Phase-2 units | baseline (H-4) |
| AUT-03 Phase-2 write-set | Q18, §15.0 | every changed path under `phase2_extraction/` | step declaration |
| AUT-04 P1-Q1 index | P1-Q1, RA-10 | registry ids ⇔ index ids; no `document_kind`; `false` ⇒ `why` | — |
| AUT-05 L5 census | Q1, RA-4, I-4 | count(level = L5) = 0 | — |
| AUT-06 Referential integrity | Q5, P-1, §37 | every referenced id resolves; ids unique | id grammar |
| AUT-07 Schema conformance | P-2, §37 | every row validates against frozen schema | **G-7 (schemas frozen)** |
| AUT-08 Q59 form | Q59 | every theory object has relations or NONE_FOUND + reason (per NONE_FOUND value — current per-object reason is ambiguous, NDF-06) | — |
| AUT-09 Q60 inclusion | Q60 | seed ids ⊆ Candidate Theory ids | — |
| AUT-10 Q61 state update | Q61, RA-11 | a unit changing registries also changes the authoritative state | **G-6 resolved** |
| AUT-11 Presence fields | Q32, Q43, Q51, Q57, Q58 | `iteration_id`, level≠"F", origin label, disposition ∈ vocab, coverage stated | vocabularies closed |
| AUT-12 Falsifier-before-result | Q35, Q47 | falsifier timestamp < result timestamp | timestamps recorded |
| AUT-13 Stage cap | Q14 | stage ≤ F1 while linked open terms exist | — |
| AUT-14 Hash chain | §5A | `previous_record_hash` chain valid | **fields implemented** |
| AUT-15 State consistency | RA-11 | JSON/MD state agree (or only one exists) | G-6 |
| AUT-16 No silent repair by gate | meta | runner run leaves `git status` unchanged | — |

Spot evidence: a read-only re-check of AUT-04, AUT-08, AUT-09 on today's tree passes; AUT-11 finds two records with origin "—" (T-0006, T-0009) whose admissibility is undefined → would be INCONCLUSIVE, not PASS.

## F. AGENT-REVIEW CATALOG

Semantic; requires a reviewer **session distinct from the executor**, working from artifacts + evidence, not the executor's reasoning. Same-session review is `SECONDARY_REVIEW` only (`P2P` §11.0).

| Check | Source | What the reviewer judges |
|---|---|---|
| REV-01 Kind-based exclusion | ACL-1 / Q54 | was anything excluded by kind/type/folder? |
| REV-02 Category existence | ACL-2 / Q38 | is the Phase-2 category shown to exist in the corpus? |
| REV-03 Canonical discovery adequacy | ACL-3 / Q53 / Q10 | was the search sufficient before constructing? |
| REV-04 Window/scope carried | ACL-4 / Q55 | does each fact keep its scope? |
| REV-05 Term collision | RA-9 / Q56 | same term, different meaning? |
| REV-06 Claim ≤ evidence | Q3 | strength calibration |
| REV-07 Identity (six criteria) | §19A / Q7 | same object or not? |
| REV-08 Fault-class correctness | Q36 | is a divergence THEORY vs PROTOCOL vs INSTRUMENT vs EVIDENCE? |
| REV-09 P1-Q1 truth | P1-Q1 | is `true`/`false` correct (AUT-04 checks only form)? |
| REV-10 Relations accounting vs discovery | Q59 / C3 | accounted ≠ complete; denominator unknown |
| REV-11 Readability / history without L2 claims | Q42, Q16, Q27 | — |
| REV-12 Seed fidelity | Q60 semantics | nothing dropped *in meaning* (the iteration-1 failure mode) |

**Limit, stated plainly:** a reviewer that is another session of the same model family shares systematic biases. It is *more* independent than self-grading, not *independent* in the §11.0 sense. Whether that suffices for M2 is a human question (H-8).

## G. HUMAN-ADJUDICATION CATALOG

| ID | Decision | Why not automatable |
|---|---|---|
| H-1 | Who is the research program's authority (research owner ≡ PO/ARB?) | authority cannot be self-assigned |
| H-2 | Adopt the Governance Agent contract (D.4) and verdict/fault vocabulary extensions | changes governance |
| H-3 | Approve Gate Registry v1 and every later change | gates define what counts as passing |
| H-4 | Approve the Phase-1 baseline; disposition of legacy violations (RO-0014, stale state, §45 Gates 1–3 skipped) | historical judgement |
| H-5 | Which state file is authoritative (RA-11 names the Markdown) | architecture change under §9 |
| H-6 | Waivers: proceed despite FAIL/INCONCLUSIVE | risk acceptance |
| H-7 | Freeze decisions (§3A.6), L5/M5, AuthorityAct (I-5b), EXTERNAL_REQUIRED findings | epistemic authority |
| H-8 | Is a same-model separate session an acceptable "independent reader"? | resource & epistemics |
| H-9 | Retro-authorize or reject the applied E-*/R-* changesets and the v1.1 freeze | currently unrecorded |
| H-10 | Reverse the "checkpoints, not walls" posture for research gates (G-15) | recorded design posture |
| H-11 | Is this work authorized under the CLAUDE.md methodology freeze ("no protocol refinement … unless implementation exposes a genuine deficiency") and PublicDigit-first priority? | scope/priority |

## NOT-YET-DEFINED (methodology insufficient — cannot be gated until defined)

NDF-01 research authority & approval record format · NDF-02 CR lifecycle · NDF-03 frozen schemas (Gate 2) · NDF-04 hashing/registry import (Gate 3) · NDF-05 Reference Architecture (Gate 1, "§49 item 1 BLOCKING") · NDF-06 per-value NONE_FOUND reason · NDF-07 admissibility of origin "—" · NDF-08 how to exercise P1-Q1's `false` branch · NDF-09 C2/C3 validation method · NDF-10 §3C.5 next-target ranking (qualitative by design; ES-003.2 forbids numeric scores — **must stay agent-review, not be forced into a script**) · NDF-11 step-type → gate applicability map · NDF-12 GOVERNANCE/PROCESS and EVIDENCE fault classes.

---

## H. PROPOSED PRE/POST HOOK MODEL

**Hooks are layered by trust, not by convenience.**

| Layer | Where | Role | Can be bypassed by executor? |
|---|---|---|---|
| L0 Early warning | Claude `PreToolUse` on Write/Edit/**Bash** touching `D/` (path + write-set check); `Stop` hook runs runner in warn mode | fast feedback | **Yes** (settings editable; Bash writes; outside Claude) — **never a trust anchor** |
| L1 Local commit | `.husky/pre-commit` → runner | catch before commit | Yes (`--no-verify`; husky not installed in this checkout) |
| **L2 Trust anchor** | **CI job on `docs/knowledgeos/**`**, required status check, protected branch; CODEOWNERS on Gate Registry, runner, fixtures, `ARCH`, `.claude/settings*.json`, `.claude/scripts/**` | **physically blocks merge** | Only by a human with admin rights — which is a visible human act |
| L3 Verdict ledger | committed append-only JSONL, e.g. `docs/knowledgeos/governance/gate-verdicts/` (placement to be resolved via `doc-placement.php`) | audit | Edits visible in git; CI verifies ledger is append-only |

**How a failed gate prevents progression:** the PRE-GATE of step *n+1* requires a POST-GATE `PASS`/`PASS_WITH_OPEN_QUESTIONS` verdict for step *n* **bound to the parent commit SHA**; CI fails any merge whose ledger lacks it. Inside a session nothing can *physically* stop an agent — the claim is only that **its output cannot be merged**. That is the honest strength of the mechanism.

**Implication for the current workflow:** branch `knowelegeos-modelling` is committed to directly with no PR. The trust anchor only exists if research work flows through PRs into a protected branch (H-10).

**How violations are recorded without silent correction:** the runner is read-only (AUT-16); findings go to the ledger with `fault_class`; repairs are a *separate* executor step whose own POST-GATE re-runs the check. A verdict is never edited — a later verdict supersedes it by reference.

**Reproducibility:** each verdict carries `{commit_sha, runner_version, registry_version, fixture_suite_result, inputs_hash}`; anyone can re-run at that SHA. Gates run on a **commit, never on the working tree** — today a concurrent session is appending to `D/` registries while this assessment was being made (V), which would make working-tree verdicts meaningless.

---

## I. GATE-OF-GATES / META-GOVERNANCE MODEL

1. **Every automated check has a violation fixture that must FAIL and a clean fixture that must PASS** (pattern: `Phase0BackTest.php`). The fixture suite runs *before* the gate in CI; if a check stops failing on its fixture → the gate run is `INCONCLUSIVE(INSTRUMENT)`, not PASS.
2. **Historical back-test:** the runner must reproduce known historical defects (RO-0014 in-place rewrite; iteration-1 seed drop at the form level; the 2 NONE_FOUND-without-reason violations in B-2's first pass). A runner that passes those states is broken.
3. **Zero-coverage rule:** a check that examined 0 items returns INCONCLUSIVE.
4. **Anti-weakening ratchet:** CI flags any change set that modifies the Gate Registry / runner / fixtures **and** research artifacts together → requires a human act. Gate changes must cite evidence (`ARCH` §9 pattern) and must be merged *before* the artifacts they would affect.
5. **Additive-only derivation:** the Governance Agent may add gates to a step, never remove registry-mandated ones.
6. **Gate change control is itself human-only (H-3)**, recorded through the attested channel (G-4).
7. **Periodic independent audit of the gate system** (another session/human reviews registry ↔ protocol drift, G-9).

---

## J. RISKS OF AUTOMATING GOVERNANCE (falsification of this proposal)

| # | Attack on the proposal | Does it break it? | Mitigation / residual |
|---|---|---|---|
| J-1 **Circular authority** — Governance Agent and executor are the same model; a Claude session could also write the "human act" | Yes, if human acts remain transcribed strings | Attested human channel (H-2/G-4). **Residual:** until that exists, every verdict of "authorized" is unverifiable — say so in every record |
| J-2 **Governance bypass** — executor edits settings/hooks/runner, writes via Bash, works outside Claude | Yes for L0/L1 | L2 CI + CODEOWNERS. Residual: repo admin can disable protection (visible human act) |
| J-3 **False PASS** — buggy/empty runner; zero items; wrong file globs | Yes without meta-gates | I.1–I.3. Residual: fixtures only cover imagined violations |
| J-4 **Form mistaken for truth** — AUT-08 PASS read as "relations complete" | Yes — the most likely real-world failure | mandatory `scope` field; REV-10; never report automated PASS as validation |
| J-5 **Automation making epistemic decisions** — scripting §3C.5 ranking, identity, fault class | Yes | kept in F / NDF-10; runner may only check *presence* of these judgements |
| J-6 **Gate failure read as theory failure** | Yes | fault-class rule D.3: automated failures are never THEORY |
| J-7 **Silent artifact modification** — "auto-fix" by gate | Yes | AUT-16; runner has no write path |
| J-8 **Gates untestable** — agent-review gates can't be fixture-tested | Partly | seeded-defect review audits (plant a known collision; check reviewer finds it) — expensive, periodic, not per-step |
| J-9 **Weakening to pass** | Yes | I.4 ratchet |
| J-10 **Retroactive FAIL of all existing work** (legacy violations) | Operationally yes — would halt the program on day one | H-4 baseline with explicit legacy findings list |
| J-11 **Concurrency** — multiple sessions writing `D/` | Yes on working tree | commit-bound verdicts; one executor per unit (workflow-state lane) |
| J-12 **Bureaucratic paralysis** — 61 Q-gates × every step | Real | applicability map (NDF-11); start with a minimal registry (L plan P2) |
| J-13 **Correlated reviewer error** — same model family | Yes | H-8; human spot audit |
| J-14 **Building on unadopted foundations** — ES-001..006 PROPOSED, `P2P` "not adopted" | Yes | H-9/H-11 first; otherwise the gates encode rules nobody adopted |
| J-15 **Hook assumed to protect** — reliance on Claude hooks | Yes | L0 labelled early-warning only |

**Surviving claim after the attack:** the architecture is sound *only* with (a) a named human authority, (b) an attested human-act channel, (c) a CI trust anchor on a protected branch, and (d) fixture-tested read-only runners. Without (a)–(c), the automation should be built — if at all — as a **warn-only instrument**, and labelled as such.

### What should NOT be automated

Freeze decisions · waivers · L5/M5 · acceptance · identity judgements (§19A) · fault-class assignment · next-target ranking (§3C.5) · adequacy of canonical discovery · "is the theory right" · recording of human acts · changes to gates · anything whose automated PASS would be read as validation.

---

## K. REQUIRED ARCHITECTURE CHANGES

Each is a proposal under `ARCH` §9 or a governance act — **none applied**.

| # | Change | Owner of decision |
|---|---|---|
| K-1 | Name the research-program authority; map research owner ↔ Decision Authority | H-1 |
| K-2 | Adopt Governance Agent contract (D.4) as a *responsibility*, consistent with ES-001 (recommends, never decides) | H-2 |
| K-3 | Define attested human-act channel | H-2 |
| K-4 | Introduce Gate Registry (machine-readable extraction of existing Q/P1-Q/§37/§45 gates + class + applicability) — **no new rules, only indexing** | H-3 |
| K-5 | Introduce committed verdict ledger + record schema (verdict, scope, fault_class, SHA, versions) | H-3 |
| K-6 | One authoritative, machine-readable research state; Markdown rendered from it or removed; strip state from rule docs (G-9) | H-5 (`ARCH` RA-11 change) |
| K-7 | Freeze schemas (§45 Gate 2) and hashing/baseline (Gate 3) | H-4 |
| K-8 | CR lifecycle (id, target, status, owner, acceptance) | H-3 |
| K-9 | Add fault classes `EVIDENCE/RECORDING`, `GOVERNANCE/PROCESS`; rule "automated failure ≠ THEORY" | H-2 (§9 proposal) |
| K-10 | Research work via PR → protected branch with required CI check; CODEOWNERS on governing/gate files | H-10 |
| K-11 | Resolve status contradictions (`P2P` "not adopted", `step2_authorized PENDING`, E-*/R-* "awaiting") | H-9 |

## L. IMPLEMENTATION PLAN (only after authorization; each phase = one backlog item, one commit, own POST-GATE)

| Phase | Deliverable | Blocking decisions | Notes |
|---|---|---|---|
| **P0** | Human decisions H-1, H-2, H-9, H-10, H-11 recorded via attested channel | — | **Nothing below starts without P0.** If H-11 = "not now", stop here; this document stands as the finding |
| **P1** | Gate Registry v1 (extraction only; small initial set: AUT-02/03/04/05/06/08/09/16) | H-3 | proves value on the cheapest, highest-risk gates (Step-1 immutability, write-set) |
| **P2** | Read-only runner under `scripts/lib/EngineeringKnowledge` (existing convention), CLI `scripts/research-gate.php`, **fixture suite + historical back-test first (RED)** | P1 | exit codes aligned with existing convention; developer guide per DoD |
| **P3** | Baseline manifest + legacy-findings list | H-4, K-7 | enables AUT-01/02 |
| **P4** | Verdict ledger + schema; PRE/POST verdict emission | K-5 | append-only check in CI |
| **P5** | CI job (`docs/knowledgeos/**`), required check, CODEOWNERS; L0 Claude hook as warning | K-10 | trust anchor |
| **P6** | State consolidation | H-5 | enables AUT-10/15 |
| **P7** | Agent-review protocol: commissioning a separate reviewer session, REV-xx verdict records, seeded-defect audits | H-8 | |
| **P8** | Extend registry (remaining AUT, CR lifecycle) incrementally, each via I.4 | H-3 | |

**Recommended immediate next action (for the human):** decide P0 — especially **H-1 (who is the authority)** and **H-11 (is this authorized now, given the methodology freeze and PublicDigit-first priority)**. Until then, the current research workflow should be understood as *self-graded*, and its PASS statements read accordingly.

---

*Traceability:* produced in response to the user's governance-architecture commission of 2026-09-23 · sources: `ARCH` v1.1, `P1P`, `P2P` (§3.0, §3A.6, §10.3c, §11.0, §16, §16B), `RECONSTRUCTION-STATE.json`, `KNOWLEDGEOS-RESEARCH-STATE.md`, `END-TO-END-ENFORCEMENT-AUDIT.md`, `B2-Q59-COMPLIANCE-REPORT.md`, ES-001, EEP, KOS-OPERATING-MODEL-001, `workflow-state.php`, `next-actor-orchestration.php`, `session-bootstrap.php`, `.claude/settings.json`, `.husky/*`, `.github/workflows/*`. No artifact modified.

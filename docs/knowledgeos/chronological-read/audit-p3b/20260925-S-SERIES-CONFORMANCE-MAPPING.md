# S-Series conformance mapping: Research Architecture v1.2 → Master Protocol v3.5 → P3b v1.7 → V1.2.1

**Kind:** conformance audit (inspection only). Recorded as G-LOG-0068.

**What was not done:**
- none of the four artifacts was modified;
- nothing was executed, no corpus was read, no agent was dispatched;
- no authorization was created, and `pilot-s5-decomp/v1/` was not created.

**Evidence rule:** every statement cites a committed artifact. Conversation history and earlier interpretations are not used as evidence. Where an earlier statement of mine is corrected, that is said explicitly (§7.1).

## Baselines inspected (repository HEAD `d41c68439`)

| Layer | Artifact | Identity |
|---|---|---|
| Research Architecture v1.2 | `docs/knowledgeos/knowledgeos_theory_chronological_extraction/prompts/KNOWLEDGEOS-RESEARCH-ARCHITECTURE.md` | sha256 `e3bbf292…c816439`. Added `708674287` (2026-09-22); last change `1e6dc3824` (2026-09-23); header "FROZEN — v1.2, 2026-09-23"; clean working tree |
| Master Protocol v3.5 | `docs/knowledgeos/chronological-read/prompts/20260911_0221_prompt3-optimized.md` | sha256 `508b9f99…83730c5`; last change `e3e47b139` (2026-09-12); clean |
| P3b v1.7 | `docs/knowledgeos/chronological-read/prompts/20260924_2311_p3b-phase1-continuation-protocol-v1.7.md` | sha256 `38021aa4…7502d12`. It equals the hash frozen by **G-LOG-0034 (H-01, "Freeze v1.7")**; last change `de52149fb`; clean |
| V1.2.1 | `audit-p3b/20260925_2000_v1.2.1-instrument-validation-preregistration.md`, `scripts/p3b_v1_2_1_instrument.py`, tests | commit `51e04f8cb621cee1b8083245d8068418ac80b6c5` (G-LOG-0066/0067) |
| (observed) | `chronological-read/prompts/KNOWLEDGEOS-RESEARCH-ARCHITECTURE.md` | **untracked** working-tree file, byte-identical to Architecture v1.2. Not committed, no governance entry. Not created by this session, and not touched |

**Classification:**
- **A** conformant;
- **B** documentation or reference gap;
- **C** implementation gap;
- **D** protocol violation;
- **E** architectural conflict;
- **F** genuine unresolved governance question;
- **N/A**.

---

## Executive conclusion

1. **The hierarchy as drawn is not the recorded authority chain.**
   - The recorded chain is **Master Protocol v3.5 → P3b v1.7 (operating annex, D-01) → V1.2.1** (an experiment beneath P3b).
   - **Research Architecture v1.2 is not declared as governing the S-lane** in any committed or governed artifact.
   - The frozen P3b v1.7 §7 (approved by H-01, G-LOG-0034) states the opposite: the lane methodology "under Research Architecture v1.1" **does not govern P3b** (D-18).
   - v1.2 binds a **different** "Master Protocol" (`knowledge_os_protocoll.md`, "Chronological File-Level Derivation", which cites the architecture at its line 13). v3.5 predates the architecture (2026-09-12 vs 2026-09-22/23) and cannot reference it.

   → **F (governance question), not a conformance failure.**
2. **v3.5 → P3b is conformant.**
   - P3b inherits R0–R20 in full and changes none of v3.5 (§6).
   - The two A11 deviations (66 hub labels ESCALATED for `LOAD`; Tier X `PROVISIONAL-HELD` pending H-02) are **human-approved, claim-scoped implementation gaps (C)**, not substitutions.
3. **P3b → V1.2.1 is conformant as instrumentation.**
   - V1.2.1 measures and validates extraction and matching machinery.
   - It changes no P3b semantic obligation, and it cannot substitute for any P3b step (V1.2.1 §1, §22).
   - **One gap:** V1.2.1's purpose sentence names "extraction instrumentation for Phase-1 (P3) execution under v3.5". But no P3b obligation requires an exhaustive proposition inventory, and V1.2.1's proposition taxonomy does not follow v3.5's closed TYPES list (B2).

   → C. It does not bar V1.2.1 as an experiment; it bars **adoption** of the instrument without a §26 change.
4. **Measured against v1.2 substantively** (as if it governed), v3.5 and P3b correspond on most invariants (B). There are **four substantive divergences:**
   - no Theory Discovery Index (1B / RA-10);
   - no declared Published-Language ACL between the contexts;
   - P3b producing `THEORY-CANDIDATE` records inside a reconstruction phase;
   - RA-13 concurrency vs v3.5's sequential pipeline.

   They become **E** only if v1.2 is adopted as governing (F).
5. **Discovery-Augmented Reconstruction already has a place:**
   - in the recorded chain: P3b §9B discovery loop, §13 register, §9E passes, §11.4 targeted search, and the A11 "found → back to P1-style capture" feedback;
   - in v1.2: 1B index, §6 feedback, 2A targeted search, RA-5 correction requests.

   **No missing architectural capability was found.**
6. **R19:** no adopted mechanism substitutes Phase N+1 for Phase N. The not-adopted S-Series strategy options B/D would do so if adopted (F; restate them as augmentation).

---

## 1. Governing hierarchy: recorded vs requested

```
REQUESTED (audited object)             RECORDED (committed authority)
Architecture v1.2                      Master Protocol v3.5  (2026-09-12; "Part A is the controlling procedure")
      ↓  (no declaration)                    ↓  D-01: operating annex, R0–R20 inherited unchanged (P3b §6)
Master Protocol v3.5                   P3b v1.7  (frozen, G-LOG-0034 / H-01)
      ↓                                      ↓  an experiment beneath P3b (P3b §26.7: operational, implementation layer)
P3b v1.7                               V1.2.1  (pre-registration only; READY-FOR-HUMAN-AUTHORIZATION, G-LOG-0067)
      ↓
V1.2.1                                 Architecture v1.2 → binds the F-lane Master Protocol (knowledge_os_protocoll.md l.13)
                                       and the Step-2 protocol (DRAFT, not adopted); P3b §7 D-18: does not govern P3b
```

**Authority of each layer:**
- **v3.5:** controlling procedure for P0–P7 (Part A, A2).
- **P3b v1.7:** operates P3's roll-up; "Nothing in this annex governs P4+" (§6).
- **V1.2.1:** an experiment; it claims no protocol authority (§1, §22).
- **Architecture v1.2:** governs "the research process" (its header) through "the Phase-1 and Phase-2 protocols [that] reference this document". The S-lane protocols do not reference it.

## 2. Architecture v1.2 → Master Protocol v3.5 (substantive correspondence; binding is F throughout, finding F-01)

| # | Architecture element (citation) | v3.5 counterpart (citation) | Class |
|---|---|---|---|
| 1 | two bounded contexts (§1–§2) | a single pipeline P0–P7 (A2); reconstruction P1–P3(P4) vs validation and synthesis P5–P7; no context boundary declared | B |
| 2 | own ubiquitous language per context (§2) | a single vocabulary; "Phase 1" = P1 only (R0 "Reduction begins in Phase 2") | B (F-03) |
| 3 | 1A Evidence Reconstruction list (§2A) | source/provenance R11; chronology R1/R1a; claims R6; definitions, assumptions, derivations A10; relationships and contradictions A11; gaps R17/A11 absences; scope B2 SCOPE; theory objects P2 families. **Theory threads: NO EXPLICIT MAPPING FOUND** | B |
| 4 | 1B Theory Discovery Index, content-keyed (§2A, RA-10, P1-Q1) | **NO EXPLICIT MAPPING FOUND.** P2 families are label-keyed candidate objects, not a content-keyed theory index | F (F-05) |
| 5 | Phase-2 jobs 2A/2B/2C (§2B) | 2A ≈ P7 synthesis "from Phases 2–6 only" (A13); 2B ≈ DERIVED-PROPOSAL, quarantined (A11, R8); **2C attack: NO EXPLICIT MAPPING** (P5 records the corpus's own validation means, not new attacks) | F |
| 6 | five layers; Layer 5 unreachable (§3, RA-4) | the GATE "Never FINAL", pending governance review (A13); P6 promotion only by a recorded act (A12); R20 | B |
| 7 | handoff = Published Language / ACL (§4, §4.1, RA-3) | phase artifacts (`30-RECONCILIATION.md` → P4 input); **no declared translation layer** | F (F-06) |
| 8 | ACL-1: classification ≠ relevance filter (§4.2) | R2 whole-file, R3 "no 'nothing here' shortcut", R16 | B |
| 9 | ACL-2: category must exist in the corpus (§4.2) | B2 "CLOSED LIST — never invent a type"; R5 labels are handles | B |
| 10 | ACL-3: query before constructing (§4.2) | A11 absences: targeted search before GENUINELY-UNDEFINED; DERIVED-PROPOSAL needs evidence | B |
| 11 | ACL-4: a Phase-1 fact carries its scope (§4.2) | R17; A11 NEGATIVE-BOUNDED vs NEGATIVE-CENSUS | B |
| 12 | RA-9 term collision (§4.2c) | **NO EXPLICIT MAPPING FOUND** | F (F-03) |
| 13 | shared kernel = identifiers + provenance (§4.3, RA-8) | R11 identity; S-ids | B |
| 14 | upstream immutable; corrections as requests (§5, RA-2, RA-5) | R10 "never rewrites the earlier record" | B |
| 15 | feedback is normal (§6, RA-7) | A11 "found → back to P1-style capture"; R18 | B |
| 16 | responsibilities table: Candidate Theory not in Phase 1 (§7) | v3.5 P3 emits only quarantined DERIVED-PROPOSALs (R8) | B |
| 17 | RA-1 corpus immutable | A0 firewall; A4 SELF-CITATION-EXCLUDED | B |
| 18 | RA-6 (= ACL-1) | as row 8 | B |
| 19 | RA-11 Research State authoritative | A3 runbook: state from `ls OUTPUT_DIR`; "Never resume from conversation memory"; CONTEXT line. A different artifact from `KNOWLEDGEOS-RESEARCH-STATE.md` | B |
| 20 | RA-12 next work recomputed from state | A3 decision ladder | B |
| 21 | RA-13 Phase 2 need not wait for Phase 1 | v3.5 is sequential (A2, A3); P7 "from Phases 2–6 only"; R19 | F (F-07) |
| 22 | RA-14 provenance chain traversed explicitly | A13: every statement [S-id] + origin + status vector | B |
| 23 | RA-15 four epistemic statuses never collapsed | R15 status vector; R6 claim ≠ assessment ≠ observation; R9 duplicate ≠ independent | B |
| 24 | RA-16 governance state read before execution | A3 session start; R20 | B |
| 25 | §8A three control layers | Part A = procedure; A3 = state; no separate architecture layer | B |
| 26 | §8B O-1 (own-file objects) | N/A (F-lane Phase-1 unit is the file) | N/A |
| 27 | §9 change control ("protocols reference, never restate") | v3.5 references no architecture, and **predates it** | F (F-01, F-02) |

**27 rows:** B 17 · F 9 · N/A 1. No row is classified D or E, because the binding itself is unresolved (F-01). Rows 4, 5, 7 and 21 would become **E** if v1.2 were declared governing, and row 12 would become **C**.

## 3. Master Protocol v3.5 → P3b v1.7

P3b §6: "Inherited unchanged from v3.5: R0–R20 in full; A11 per-object roll-up semantics … Changed: nothing in v3.5's text."

| # | v3.5 obligation | P3b mechanism (citation) | Class |
|---|---|---|---|
| 1 | R0 preservation | P1 consumed, never regenerated (§2, §5.1); absence search never thinned (§11.4) | A |
| 2 | R1/R1a ordering | birth and date rules, per-label timeline (§14.2–§14.3, D-11) | A |
| 3 | R2 whole-file read once (P1) | P1 complete and consumed. P3b stage-2 whole-file reading with page proof (§11.4; contract rev 3) | A |
| 4 | R3 roadmap coverage | corpus boundary, firewalled/secondary (§3.1–§3.5) | A |
| 5 | R4 multi-object | per-label records drawing on shared files (§9.8) | A |
| 6 | R5 no identity resolution in Phase 1 | P3b is P3; no merge or rename (§1D) | A |
| 7 | R6 claim / assessment / observation | layers A/B/C never collapsed (§1B, G-12); epistemic classes (§11.1) | A |
| 8 | R7 lineage | relationship model (§12) | A |
| 9 | R8 derived ≠ primary | the register never changes a status (§2); external lane only as DERIVED (§7, D-18) | A |
| 10 | R9 duplicate ≠ independent | independence carried upward (§9E.3) | A |
| 11 | R10 no rewriting | corrections and supersession append-only (§12.3) | A |
| 12 | R11 identity | identity model and manifest (§4; A.3b) | A |
| 13 | R12 no cross-batch identity in Phase 1 | cross-object pass writes to the register only (§9E) | A |
| 14 | R13 scripts derive, agents interpret | automation boundary (§15) | A |
| 15 | R14 orchestrator never reads the corpus | §19.3 (l.1879): "The orchestrator never reads a corpus file or a full ledger (v3.5 R14)" | A |
| 16 | R15 status vector | B4 inherited (§6) | A |
| 17 | R16 type ≠ identity | Q1/Q2 separation (§12) | A |
| 18 | R17 absence/census | two-stage absence search; `NOT-FOUND-LOAD-ESCALATED` "asserts nothing about the hits" (§11.4, §13) | A |
| 19 | R18 progress ≠ stop | §23 "Not stop conditions (v3.5 R18)" | A |
| 20 | **R19 no substitution** | sequence S0→S7 → A11 TERMINAL → P4 (§8); terminal predicate extends A11 (§23); "Nothing in this annex governs P4+" (§6) | A (see §5) |
| 21 | R20 governance review | proposal vs acceptance (§16.5); human governance (§17) | A |
| 22 | P0, P0.5, P1, P1c | consumed frozen (§5.1) | A |
| 23 | P2a/P2b | consumed frozen; 2,497 labels (§2) | A |
| 24 | P3 A11 pair questions Q1/Q2 | P3a (frozen `125cfe8371`, `c9e76918b`); reliability limits inherited (§5.3) | A |
| 25 | P3 A11 per-object roll-up | per-label procedure (§9.8); terminal (§23) | A |
| 26 | A11 absences ("run one targeted search … not found → GENUINELY-UNDEFINED") | **66 hub labels:** stage 2 not performed; hit-bearing dimensions `ESCALATED (LOAD)`; claim scope restricted (§11.4, §23 item 3b; G-LOG-0031…0034) | C (F-08) |
| 27 | A11 TERMINAL ("every … load-bearing object has … statuses") | Tier X `PROVISIONAL-HELD` pending H-02 (§8, §9.3, §23 item 2) | C (F-09) |
| 28 | A11 DERIVED-PROPOSAL → `35-DERIVED-PROPOSALS.md` ("MAY") | research register (§13). **Routing to `35-DERIVED-PROPOSALS.md`: NO EXPLICIT MAPPING FOUND** | B (F-12) |
| 29 | P4–P7, GATE | "Valid from P4–P7: all of A12–A13 as written"; hand-over shaped to A12 (§6, §24) | A |
| 30 | v3.5 change control (Part A controlling) | "v3.5 is never edited"; defects go to governance (§26.4) | A |

**30 rows:** A 27 · B 1 · C 2 · D 0.

**P3b additions with no v3.5 counterpart** (they are allowed as a stricter annex, §6 "Newly introduced"; **NO EXPLICIT MAPPING** by design):
- input-exposure tiering (§9.3);
- the persisted agent contract (§19.2);
- the research register (§13);
- hindsight controls (§14);
- blind audit (§21);
- the H-19 hold-out (§9F);
- cross-object and corpus passes (§9E).

## 4. P3b v1.7 → V1.2.1 (instrumentation)

| Component | Purpose | Input → output | P3b obligation served | Nature | Changes a P3b semantic obligation? | Can it substitute for a P3b step? |
|---|---|---|---|---|---|---|
| Segment inventory (SEG) | test whether a proposition inventory attains mechanical segment coverage | 6 read files → per-segment propositions | **NO EXPLICIT MAPPING FOUND.** P3b requires whole-file reading for births and absences (§9.8, §11.4) and a discovery register (§13), not an exhaustive inventory | validation (of an instrument) | no | no (V1.2.1 §22) |
| Proposition taxonomy (V1.2.1 §7) | the inventory schema | — | contrasts with v3.5 B2 TYPES ("CLOSED LIST — never invent a type"; "A scope value is never a type"). V1.2.1 lists **SCOPE** as a type and adds CLAIM, RULE, NOTATION, THEOREM-OR-RESULT, ALGORITHM, RELATION, LINEAGE, METHOD | experiment schema | no (it writes no ledger or object record) | no |
| E1/E2 open-ended extractors | reference for capture | files → propositions | none (experiment reference) | validation | no | no |
| M1/M2 matching, κ | matcher reliability | lists → clusters and decisions | none | validation | no | no |
| Capture (R1, X6, N1) | relative recall against E1∩E2 | clusters and decisions → value or NOT-COMPUTABLE | none | validation | no | no |
| Quote verification (X1, F1) | string provenance | outputs → counts | mirrors R11 anchoring (verbatim quotes) | validation | no | no |
| Bounded repair (R4) | integrity of the correction round | failure list → diff | mirrors R10 (the original is preserved) | execution control | no | no |
| Tool-call audit, transcript linkage, persisted-output controls (R2, X2, X4, X5) | agent isolation | transcripts → breaches | mirrors R14 and P3b §19 (the read discipline) | execution control | no | no |
| Guard and dependency binding (X3, N2) | frozen identity | commit → accept or refuse | mirrors P3b §26.6 (freeze integrity) and §19.5 | execution control | no | no |
| Provenance checks (X7) | reproducibility | stage hashes → breaches | mirrors P3b §18 and §19.5 | execution control | no | no |
| Paged reader (reused) | page-proven reading | S-id/page → text plus log | P3b §11.4 (contract rev 3) | instrumentation | no | no |
| Decision states (R3, X1, X6, N1, F1) | outcome attribution | metrics → outcome | none | validation | no | no |

**Assessment:**
- **V1.2.1 measures and validates execution machinery.** It does **not** define a P3b research procedure: its outputs go to `pilot-s5-decomp/v1/` (non-production), never to `32-RECONCILIATION-OBJECTS.jsonl` or the register, and its §22 excludes any substitution.
- **Potential for silent redefinition (flagged, not resolved):**
  - its purpose sentence ("extraction instrumentation for Phase-1 (P3) execution under v3.5") anticipates an adoption path;
  - adopting SEG into P3b would be a **§26 change**, and would need the taxonomy reconciled with v3.5 B2 (F-13).

**12 components:** A 11 · C 1.

## 5. R19 substitution analysis

| Mechanism | Status | Allowed? | Basis |
|---|---|---|---|
| P3b research register and discovery loop | adopted (P3b §13, §9B) | **allowed:** augments the roll-up "floor, not ceiling" (§1) | R19; P3b §1, §23 |
| Cross-object and corpus passes | adopted (§9E) | allowed: register only | §2 |
| Targeted absence search | adopted (§11.4) | allowed: completes A11 | A11 |
| A finding → P1-style capture | v3.5 A11 | allowed: a correction as an append | R10 |
| Hub `LOAD` escalation | adopted (v1.7) | allowed **as a disclosed gap**: absences are not declared resolved (claims A/B only; claim C unsupported, §23 3b). It would **violate R19/R17 if** P4 treated those dimensions as resolved | §23 3b |
| H-19 hold-out prediction | adopted as a gated addendum (§9F) | allowed: a research test, not a P5 substitute | §1D |
| Theory candidates (layer C) | adopted (§1B) | allowed in v3.5 terms (quarantined, R8). Under v1.2 it would conflict (§7: Candidate Theory is Phase 2) | F-04 |
| V1.2.1 | pre-registered | allowed: an experiment; no P3b step replaced | V1.2.1 §22 |
| Gate C research-first strategy (B arm) | a completed experiment (G-LOG-0057) | allowed **as an experiment**. Its report claims no substitution | Gate C report §limits |
| Decision-matrix option B (research-first only) / D without flooring every label | **not adopted** (design and decision support only) | **not allowed without a formal change.** It would replace the P3 roll-up for unfloored labels, violating R19 and P3b §23. The matrix records that it needs a §26 revision plus a v3.5-level act | F-14 |
| Design v2 B2 arm | not adopted (a pilot design) | allowed only as a comparison arm; its C2/C3 metadata triggers leave untriggered files unread, a pattern that ACL-1 would forbid as a relevance filter **if** v1.2 governed | F-14 |

**The general rule the artifacts support:**
- **Allowed:** discovery that identifies, proposes, guides, hypothesizes, registers or requests correction while the A11 obligations still run.
- **Not allowed without a change:** anything that declares obligations met without meeting them, replaces required reads with sampling, treats recall as completeness, treats a discovery as evidence, turns a candidate into theory, or skips a P3 obligation.

**Ambiguity (F):** v3.5 R19 forbids *substitution*, not *concurrency*. v1.2 RA-13 permits concurrency. Neither artifact settles whether theory work (P7-like) may run on complete threads before P3 closes.

## 6. Research and discovery layer: Discovery-Augmented Reconstruction (descriptive label only)

| Capability | Recorded chain (v3.5 + P3b) | Architecture v1.2 (if it governed) |
|---|---|---|
| discovery augmentation | P3b §9B loop, §13 register ("primary discovery channel") | 1B index (§2A) |
| candidate generation | register kinds (§13.2); layer C | 1B (index, not theory); 2A |
| research-oriented search | §11.4; §9B | 2A "targeted search outside the window" (§2B) |
| independent extraction | blind audit (§21); v3.5 A8 self-audit | **NO EXPLICIT MAPPING** (a protocol-level concern, §8A) |
| targeted re-examination | A11 "found → back to P1-style capture" | §6 feedback |
| discovery registers | §13 | 1B |
| findings trigger Phase-1 correction | R10 append; §26.5 research-driven change | RA-5 correction request |
| experimental machinery (instruments) | P3b §26.7: the implementation layer | §8A: protocols = HOW, state = WHERE |

**Answer: yes.**
- Both the recorded chain and v1.2 already provide a place for discovery-augmented reconstruction, and for experimental machinery beneath the protocol.
- **No missing architectural capability was found.**
- The one element v1.2 requires and the S-lane lacks, the **1B Theory Discovery Index artifact** (RA-10), is an implementation gap *if* v1.2 is adopted. It is not a new architecture.

## 7. Governance and reference gap

### 7.1 Correction of an earlier statement

G-LOG-0063 said the S-lane "references v1.2 nowhere". That is accurate for v1.2, but incomplete:
- every P3b version (v1.0–v1.7) §7 names **"Research Architecture v1.1 (frozen)"**, as the governance of the F-lane, and states that it **does not govern P3b**;
- an **untracked**, byte-identical copy of v1.2 now sits in `chronological-read/prompts/`. It has no governance entry.

### 7.2 Answers

| Question | Answer (with evidence) |
|---|---|
| 1. Is v1.2 formally declared governing for the S-Series? | **No.** No committed S-lane artifact or governance entry declares it. P3b §7 (frozen, H-01) declares the opposite, for v1.1 |
| 2. Is a declaration present in an authoritative artifact? | No. The untracked copy is not authoritative (uncommitted, ungoverned) |
| 3. Is this a genuine governance gap? | **Yes (F).** The architecture presents itself as governing "the research process"; the S-lane records that it is not governed by it. The authority to decide belongs to the human governance act |
| 4. Does v1.2 state that phase protocols reference it? | Yes: header "Binding"; §9 "referenced, never restated" |
| 5. Does the S-lane satisfy that? | No. v3.5 predates the architecture; P3b references only v1.1, and as non-governing |
| 6. Is a simple conformance declaration sufficient? | **No.** Four reasons below |
| 7. What more would be required? | a governance decision (F-01), then a P3b §26 revision (with the §26.6 freeze check), a vocabulary resolution (F-03), and one of: an architecture-change proposal under v1.2 §9, or recorded dispositions of rows 4, 5, 7 and 21 |

**Why a simple declaration is not enough:**
- it would contradict frozen P3b §7 (D-18), which only a §26 revision can change;
- "Master Protocol" and "Phase 1" name different things across the documents (F-02, F-03);
- the four substantive divergences (F-04…F-07) would turn into **E** conflicts on declaration unless they are dispositioned;
- v1.2's binding clause presumes protocols that reference it.

## 8. Findings

| ID | Class | Severity | Higher layer (citation) | Lower layer (citation) | Finding | Next governance action |
|---|---|---|---|---|---|---|
| F-01 | F | **material (governance)** | Arch v1.2 header "Binding", §9 | P3b v1.7 §7, D-18 (frozen, G-LOG-0034) | v1.2 is not declared governing for the S-lane; the frozen P3b declares the lane architecture non-governing | human decision: adopt v1.2 for the S-lane (then F-02…F-07 apply), or record the S-lane as governed by v3.5 plus the annex only |
| F-02 | F | material (clarity) | Arch §8B cites "Master Protocol §397" | `knowledge_os_protocoll.md` l.13, l.468 vs v3.5 | "Master Protocol" names two different documents. v1.2 binds the F-lane one; v3.5 predates v1.2 | a governance decision on which protocol sits under which architecture |
| F-03 | B | moderate | Arch RA-9 | v3.5 R0 ("Phase 1" = P1); P3b title "PHASE-1 CONTINUATION" (= P3); v3.5 A13 "THEORY v1.2" vs "Architecture v1.2"; P3b "RESEARCH-FIRST PRINCIPLE" (D-27: discovery **around** the floor) vs the S-Series "research-first strategy" (**substituting** reads) | term collisions across layers | a documentation clarification or glossary; the renaming decision should note that frozen P3b D-27 keeps its term |
| F-04 | F | material if adopted | Arch §7 (Candidate Theory not in Phase 1), §2A ("1B is an INDEX, never a theory") | P3b §1B layer C `THEORY-CANDIDATE`; §1 verification priority | theory candidates are produced inside P3 | disposition required only if v1.2 is adopted |
| F-05 | F | material if adopted | Arch §2A 1B, RA-10, P1-Q1 | v3.5 / P3b: NO EXPLICIT MAPPING | no Theory Discovery Index | the same |
| F-06 | F | material if adopted | Arch §4, RA-3 | v3.5 A11 → A12 hand-over; P3b §24 | no declared Published Language or ACL | the same |
| F-07 | F | moderate | Arch RA-13 | v3.5 A2/A3 sequential; R19 | concurrency is unresolved | the same |
| F-08 | C | disclosed, human-approved | v3.5 A11 absences | P3b §11.4, §23 3b (v1.7) | 66 hub labels: hit-bearing absences ESCALATED (LOAD) | none now; P4 must carry ESCALATED unresolved (R17/R19) |
| F-09 | C | disclosed, human-approved | v3.5 A11 TERMINAL | P3b §8, §23 item 2 | Tier X PROVISIONAL-HELD pending H-02 | H-02 as already scheduled |
| F-10 | B | minor | G-LOG-0034 (freeze) | P3b v1.7 header "PROPOSED … NOT frozen" | the frozen file's header still reads "not frozen"; the governance log is the authority | a documentation note (the frozen file is not edited) |
| F-11 | B | minor | Arch now v1.2 | P3b §7 cites "v1.1" | stale version reference | carry into any §26 revision |
| F-12 | B | minor | v3.5 A11 "MAY emit … 35-DERIVED-PROPOSALS.md" | P3b §13 | routing of register proposals to `35-` is not mapped | a documentation clarification at P4 hand-over |
| F-13 | C | **material for adoption, not for V1.2.1 execution** | v3.5 B2 closed TYPES; P3b §26 | V1.2.1 §1 purpose; §7 taxonomy (SCOPE as a type; non-B2 types) | the instrument serves no explicit P3b obligation; its taxonomy is not B2-conformant | record, in the V1.2.1 authorization act, that results can feed only a future §26 proposal (with B2 reconciliation); V1.2.1 itself unchanged |
| F-14 | F | material before any v2 pilot | v3.5 R19; P3b §23, §26; Arch ACL-1 (if adopted) | decision matrix options B/D; design v2 B2 arm (not adopted) | substitution options exist in the design documents | restate B/D as augmentation variants (deferred item 2), before the v2 pilot |
| F-15 | A | — | P3b §22 (V1.2.1), P3b §26.7 | V1.2.1 | V1.2.1 is conformant as instrumentation; no substitution | none |

**Counts:** A 1 · B 4 · C 3 · D 0 · E 0 · F 7 (15 findings).

**Mappings evaluated:** 27 (§2) + 30 (§3) + 12 (§4) + 11 (§5) = **80**.

## 9. Required actions

| Kind | Items |
|---|---|
| **No action** | F-15; the v3.5 → P3b rows classified A |
| **Documentation clarification** | F-03 (glossary for "Phase 1", "Master Protocol", "v1.2", "research-first"); F-10; F-11; F-12; F-13 (note in the authorization act) |
| **Implementation repair** | none required for V1.2.1. F-08/F-09 are governed gaps with scheduled decisions |
| **Protocol change proposal** | only if F-01 is decided "adopt": a P3b §26 revision (the §7 reference; the 1B index; layer-C placement). Any adoption of the SEG instrument (F-13) |
| **Architecture change proposal** | none found necessary. If adoption exposes irreducible conflicts at rows 5, 7 or 21, then a v1.2 §9 proposal with evidence |
| **Governance decision** | **F-01** (does v1.2 govern the S-lane?); F-02 (which protocol under which architecture); F-04…F-07 dispositions if adopted; F-14 (the B/D restatement); the status of the untracked architecture copy in `chronological-read/prompts/` |

## 10. Recommendation for the next gate

**V1.2.1 execution does not depend on resolving F-01…F-07:**
- V1.2.1 is conformant instrumentation beneath the recorded chain (v3.5 → P3b) (F-15);
- it changes no obligation, and it validates machinery only.

**Before authorizing V1.2.1, the minimum is:**
1. **Record, in the authorization act (F-13),** that V1.2.1's outcome (a) is an instrument-validation result only, (b) changes no v3.5 or P3b obligation, and (c) can enter P3b only through a future §26 proposal that reconciles its taxonomy with v3.5 B2.
2. **Decide whether F-01 must precede execution.** This audit finds **no dependency**: V1.2.1's validity does not depend on which architecture governs. Deciding F-01 first is a governance preference, not a conformance requirement.

**Before any v2 pilot or strategy adoption:** F-14 (restate B/D as augmentation) and, if adopted, F-01's consequences.

---

**Stop.** Nothing was modified or executed:
- Research Architecture v1.2, Master Protocol v3.5, P3b v1.7 and V1.2.1 are unchanged;
- the V1.2, V1.1 and V1 records are unchanged;
- H-19, S5c, OB0018 and production are untouched;
- no corpus was read, and the untracked architecture copy was not touched.

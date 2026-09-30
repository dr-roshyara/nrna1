# KOS Research Gate Specification — v0.1

| | |
|---|---|
| **Kind** | Gate specification for the 11 Tier-A gates of the gate catalog |
| **Status** | ⚠️ **PROPOSED — authority: generated. Not authoritative without human review.** No gate here is accepted, active, or allowed to produce an authoritative verdict |
| **Date** | 2026-09-23 |
| **Builds on** | `2026-09-23-KOS-RESEARCH-GATE-CATALOG-proposal.md` (45 proposed gates) · `2026-09-23-RESEARCH-GOVERNANCE-GATE-ARCHITECTURE-assessment.md` |
| **Not produced** | no runner · no fixture files · no hook · no CI · no change to any research artifact or protocol |
| **Evidence base** | every "current data" statement was computed read-only against a `git archive` snapshot of **HEAD `fa8b0e240`**, not the working tree. Probe scripts were run in the scratchpad only |

> **Headline:**
> - Of the 11 Tier-A gates, **2 can be specified completely and run today** (G-010, G-060).
> - **6 can be specified but not run today:** 3 have gaps inside the gate rule itself (G-004, G-005, G-007), and 3 are held back by missing preconditions (G-002, G-030, G-070).
> - **1 is blocked by a contradiction between two protocol rules** (G-003).
> - **1 is fully specified, but today's data would fail it universally**, because the field it checks was never implemented (G-052).
> - **1 depends on a meaning the protocol leaves open** (G-071).
>
> Separately, the research session's committed `check-gates.py` was tested with planted violations. It **returns PASS when its inputs are missing, and accepts invalid vocabulary**. It is a useful self-check, but it is **not usable as a gate runner** (§9).

---

## 1. Scope

These gates exist **solely** for the KnowledgeOS Theory Reconstruction and Theory Construction program in `docs/knowledgeos/knowledgeos_theory_chronological_extraction/`. No gate is assumed to apply anywhere else. Generalizing requires separate evidence and a future decision.

The scope statement is an AI transcription of the user's instruction of 2026-09-23. It is **not yet an attested human act** (assessment G-4).

**This specification changes no research rule.** Where a gate cannot be specified without a rule the protocols do not contain, the gap is recorded as a dependency (§8) and returned to the human. It is never filled in.

Abbreviations:
- `D/` = the research directory
- `ARCH` = `D/prompts/KNOWLEDGEOS-RESEARCH-ARCHITECTURE.md`
- `P1P` = `D/prompts/knowledge_os_protocoll.md`
- `P2P` = `D/prompts/knowledge_os_step2_theory_construction_protocol.md`

**Gate numbering:** the catalog lists **45 gates**. Their IDs run from KOS-G-001 to KOS-G-081 and are **sparse by design**, because they are grouped by stage (00x cross-cutting · 01x Phase 1 · 02x Phase-2 entry · 03x READ · 04x CONSTRUCT · 05x IMPLEMENT · 06x EXPERIMENT/TEST · 07x CONSOLIDATE · 08x freeze/promotion). So there are 45 gates, not 81. This specification covers **11** of them.

---

## 2. Gate lifecycle

```
PROPOSED ──▶ SPECIFIED ──▶ FIXTURED ──▶ IMPLEMENTED ──▶ REVIEWED ──▶ ACCEPTED ──▶ ACTIVE
   │             │             │              │              │            │
   └─────────────┴─────────────┴──────────────┴──────────────┴────────────┴──▶ REVISE · DEFER · RETIRED
```

| State | Entry condition | Who |
|---|---|---|
| `PROPOSED` | the gate is in the catalog with a source rule | Governance Engineer |
| `SPECIFIED` | the card in §6 is complete **and** passes the definability test in §6.0 | Governance Engineer |
| `FIXTURED` | clean and violation fixtures exist, and each gives its expected verdict when run through a *reference* evaluation | Governance Engineer |
| `IMPLEMENTED` | the runner passes the whole fixture suite, is read-only, and is bound to a commit | an implementer (not the research executor) |
| `REVIEWED` | a session other than the implementer's has checked the runner against the card | an independent reviewer |
| `ACCEPTED` | a human decides ACCEPT, through the attested channel | **human** |
| `ACTIVE` | the gate runs at its applicability points and its verdicts count | — |

⛔ **Only `ACTIVE` gates produce authoritative verdicts.** Output from any earlier state is a self-check or development evidence, and must be labelled that way.

**Where every Tier-A gate stands today:** `PROPOSED → SPECIFIED` is attempted in this document. None is further along.

---

## 3. Verdict vocabulary

This reuses the vocabulary of P2P §3A.6 and redefines none of it (RA-9).

| Verdict | Condition |
|---|---|
| `PASS` | every check ran, covered at least one item, and found no violation — **within the declared scope** |
| `PASS_WITH_OPEN_QUESTIONS` | no violation; one or more **enumerated** open questions, each with an owner |
| `FAIL` | one or more violations; each carries `fault_class` and a location |
| `INCONCLUSIVE` | any of the following: an input is missing · 0 items examined · a precondition or dependency is unmet · the input is not a clean commit · the fixture suite did not pass at this runner version · an undefined term was encountered |

**Generic rules:**
- `INCONCLUSIVE` blocks progression exactly as `FAIL` does. It is never counted as a pass.
- **A missing input is never an empty input.** A file that does not exist yields `INCONCLUSIVE`, never "0 violations". This rule is aimed at the defect found in §9.
- Every verdict carries a **scope** string. An automated PASS certifies form only, never truth.
- `fault_class` ∈ `PROTOCOL · INSTRUMENT · EVIDENCE/RECORDING · GOVERNANCE/PROCESS`. **No Tier-A gate can emit `THEORY`**: a gate verdict is about how the research was conducted, not about the theory (catalog §2).

**Verdict record** (every gate):

```yaml
gate_id:            KOS-G-0xx
gate_version:       # card version
runner_version:
unit_id:            # see §5
commit_sha:         # the evaluated commit; never a working tree
base_sha:           # for diff-based gates
verdict:            PASS | PASS_WITH_OPEN_QUESTIONS | FAIL | INCONCLUSIVE
scope:              # what this verdict does and does not certify
items_examined:     # integer; 0 ⇒ INCONCLUSIVE
violations:         [{location, rule, fault_class, detail}]
open_questions:     [{id, owner, text}]
inconclusive_reasons: []
fixture_suite:      {version, passed: true|false}
authoritative:      false   # true only when the gate is ACTIVE
```

---

## 4. Gate classes

`AUT` · `REV` · `HUM` · `AUT+REV`, as defined in catalog §3.

All 11 Tier-A gates are `AUT`, or the `AUT` (form) half of `AUT+REV`. The `REV` half of each is **out of scope for v0.1** and appears only as a pointer.

---

## 5. Applicability model

A gate needs to know **what kind of unit** it is judging. G-002 alone behaves in opposite ways for a Phase-1 unit (appending is allowed) and a Phase-2 unit (no Step-1 write is allowed at all).

**Current fact (V):**
- Execution units are not declared anywhere.
- Commits mix unit types. `3d1f31195` ("process F0026-F0030 and raise Candidate Theory to v0.7") appends to six Step-1 registries **and** rewrites the Phase-2 theory document in one commit.
- Commit `708674287` landed all Phase-1 and Phase-2 output together (74 files).

**Proposed unit declaration** (a governance mechanism, not a research rule; decision **D-3**):

```yaml
kos_unit:
  unit_id:        U-nnnn
  unit_type:      P1-FILE | P1-BATCH | P1-CORRECTION | P2-ENTRY | P2-READ | P2-CONSTRUCT
                  | P2-IMPLEMENT | P2-TEST | P2-CONSOLIDATE | GOVERNANCE
  base_sha:       # commit before the unit
  head_sha:       # commit that closes the unit
  write_set:      # declared path globs
```

**Carrier:** either a commit trailer (`KOS-Unit: U-0012 P2-CONSOLIDATE`) or a unit file. A unit must not mix Phase-1 and Phase-2 types. When units are mixed or undeclared, **every unit-dependent gate returns `INCONCLUSIVE`**.

**Tier-A applicability** (confirmation pending: NDF-R8):

| Gate | P1-FILE | P1-BATCH | P2-READ | P2-CONSTRUCT | P2-IMPLEMENT | P2-TEST | P2-CONSOLIDATE | Needs unit type? |
|---|---|---|---|---|---|---|---|---|
| G-002 | POST (a) | POST (a) | POST (b) | POST (b) | POST (b) | POST (b) | POST (b) | **yes** for (b) |
| G-003 | — | — | POST | POST | POST | POST | POST | yes |
| G-004 | POST | POST | POST | POST | POST | POST | POST | no |
| G-005 | — | — | POST | POST | POST | POST | POST | no (whole tree) |
| G-007 | POST | POST | POST | POST | POST | POST | POST | no |
| G-010 | POST | POST | — | — | — | — | — | no (whole tree) |
| G-030 | — | — | POST | — | — | — | — | yes |
| G-052 | — | — | POST | POST | POST | POST | POST | no (whole tree) |
| G-060 | — | — | — | — | — | PRE + POST | — | yes (pairs commits) |
| G-070 | — | — | — | — | — | — | POST | yes (checkpoint) |
| G-071 | — | — | — | — | — | — | POST | no (whole tree) |

---

## 6. Tier-A gate specifications

### 6.0 Definability test

A card is `SPECIFIED` only if every answer below is YES:

1. Could an **independent engineer** implement it from this card alone, **without asking the research session what anything means**?
2. Does every input it names **exist** in the committed data, under the name the card uses?
3. Is every term it uses defined in `ARCH`, `P1P` or `P2P`, or in this card as a purely mechanical definition?
4. Can a clean fixture and a violation fixture be written that **must** give different verdicts?
5. Is the gate **consistent with every other rule**, i.e. can it pass at all?

Each card records one of four outcomes:
- `SPECIFIED`;
- `SPECIFIED — NOT RUNNABLE` (the card is complete but depends on an unmet decision);
- `NOT-YET-DEFINED` (one of the five answers is NO because the methodology is silent);
- `BLOCKED` (answer 5 is NO: two rules contradict each other).

---

### KOS-G-002 · Step-1 record immutability

| Field | Specification |
|---|---|
| **Source rule** | RA-1, RA-2, RA-5, Q9, Q17, I-1 ("`git diff ../` empty"), P1P §5A |
| **Purpose** | Step 1 stays independently re-runnable. Phase 2 never writes it, and Phase 1 only appends |
| **Applicability** | POST every unit. Mode **(a) append-only** for P1-* units; mode **(b) zero-write** for P2-* units |
| **Inputs** | `base_sha`, `head_sha`, **Step-1 artifact manifest** (the list of files that make up the Step-1 record), unit type |
| **Deterministic check** | (a) for every JSONL in the manifest: `lines(base) == lines(head)[0:len(base)]`, byte-identical. Every non-JSONL manifest file is byte-identical, unless it is declared appendable. No manifest file is deleted. (b) `git diff --name-only base..head` ∩ manifest = ∅ |
| **PASS** | (a) holds for every manifest file, or (b) the intersection is empty. At least one manifest file was examined |
| **FAIL** | an in-place edit, insertion, reordering or deletion (a), or any write to a manifest file (b). Location = file:line. `fault_class: GOVERNANCE/PROCESS` |
| **INCONCLUSIVE** | the manifest is not approved · the unit type is undeclared or mixed (mode b) · `base_sha` is missing |
| **Evidence output** | per file: base and head line counts, first differing line, SHA-256 at base and head |
| **Violation fixtures** | V1: change one byte inside line 3 of `THEORY-OBJECTS.jsonl` → FAIL(a) · V2: insert a line in the middle → FAIL(a) · V3: a P2-CONSOLIDATE unit appends one row to `GAPS.jsonl` → FAIL(b) · V4: delete `CORPUS-INDEX.jsonl` → FAIL(a) |
| **Clean fixtures** | C1: a P1-FILE unit appends 5 rows → PASS(a) · C2: a P2-READ unit touches only `phase2_extraction/` → PASS(b) |
| **Human review / decision** | the manifest (**D-1**) · the baseline commit (**D-2**, H-4) · the unit declaration (**D-3**) · a legacy ruling on RO-0014 (H-4) |
| **Current data** | since the first commit `708674287`, every Step-1 JSONL change is a **pure append**, verified with `git show --numstat` (0 deletions in `6c3aa64da` and `3d1f31195`). Mode (a) would PASS on `708674287..HEAD`. ⚠️ **The RO-0014 in-place rewrite of 25 FILE-REGISTRY rows happened *before* the first commit, so git cannot see it, and this gate can never detect it.** That limit must be stated in the verdict scope |
| **Definability** | Q1 ✅ · Q2 ⚠️ no manifest exists (P2P §15.0 says the root holds "the 13 registries, RECONSTRUCTION-STATE.json, batch reports", but the root holds **16** JSONL files plus Phase-2 documents such as `PHASE2-*.md` and `check-gates.py`) · Q3 ✅ · Q4 ✅ · Q5 ✅ |
| **Status** | **SPECIFIED — NOT RUNNABLE** (needs D-1, D-2; mode (b) also needs D-3). ⭐ The runnable subset is **mode (a) applied to every commit**, which needs only D-1 and D-2 |

### KOS-G-003 · Write-set confinement

| Field | Specification |
|---|---|
| **Source rule** | Q18, P2P §15.0 rules 1–2 ("Step 2 writes nothing outside `phase2_extraction/`. Not to the root…"), Q26 (form part) |
| **Purpose** | keep Step-2 residue out of the Step-1 record, so that deleting `phase2_extraction/` restores a clean Step 1 |
| **Applicability** | POST every P2-* unit |
| **Inputs** | `base_sha`, `head_sha`, unit type |
| **Deterministic check** | every path in `git diff --name-only base..head` matches `D/phase2_extraction/**`. Every new or changed `[E]` record carries `"origin":"STEP2"` |
| **PASS / FAIL / INCONCLUSIVE** | PASS when all paths and records conform · FAIL for any path outside the folder or any `[E]` record without origin · INCONCLUSIVE when the unit is undeclared or mixed |
| **Violation fixture** | a P2 unit that writes `D/GAPS.jsonl` → FAIL |
| **Clean fixture** | a P2 unit that writes only `phase2_extraction/THEORY-SEED.md` → PASS |
| ⛔ **Conflict** | **Q61 / RA-11 / §16B require *every execution unit*, Phase-2 units included, to update `D/KNOWLEDGEOS-RESEARCH-STATE.md`, which lives at the root, outside `phase2_extraction/`.** So Q18 and Q61 cannot both pass for any Phase-2 unit. In current data, every Phase-2 commit (`708674287`, `6eaff361c`, `3d1f31195`) writes root files, and the research session also placed `check-gates.py` and the `PHASE2-*.md` documents at the root |
| **Definability** | Q5 ❌: the gate as written cannot pass while Q61 is obeyed |
| **Status** | **BLOCKED — PROTOCOL defect.** Resolving it is a research-rule change under `ARCH` §9, and **a human decision (D-4)**. Examples: move the state file, or declare it the single permitted exception to Q18. **This specification does not choose.** It also does not quietly add an exception, because that would change the methodology to make a gate implementable |

### KOS-G-004 · Append-only research history

| Field | Specification |
|---|---|
| **Source rule** | Q24 ("every theory change written to THEORY-EVOLUTION with the old text verbatim"), Q32 ("every artifact carries `iteration_id`; no iteration overwrites an earlier one"), I-11 ("FAILED-TESTS append-only"), I-12 |
| **Purpose** | theory evolution stays recoverable; the protocol cites IFR-0010's lost original |
| **Applicability** | POST every unit |
| **Inputs** | `base_sha`, `head_sha`, the list of append-only Phase-2 artifacts, the iteration field name |
| **Deterministic check** | (i) for each append-only artifact, the prefix is identical, as in G-002(a) · (ii) every JSONL record in `phase2_extraction/` carries the iteration field · (iii) `old_formulation` of every THEORY-EVOLUTION entry is byte-identical to its first committed value |
| **PASS / FAIL / INCONCLUSIVE** | FAIL on an edited or removed entry, or a missing iteration field. INCONCLUSIVE when an append-only artifact named by the protocol does not exist |
| **Violation fixtures** | edit the `old_formulation` of `THEORY-EVOLUTION.jsonl` row 1 → FAIL · delete row 2 → FAIL · a record without an iteration field → FAIL |
| **Clean fixture** | append one evolution entry → PASS |
| **Current data (V)** | `THEORY-EVOLUTION.jsonl` exists (5 rows). ⚠️ **`FAILED-TESTS` does not exist at all**, although I-11 names it. ⚠️ The field in the data is **`iteration`**, not `iteration_id`, and it is present in 11 of the 12 Phase-2 JSONL files but **absent from `THEORY-OBJECT-RELATIONS.jsonl`**, the B-2 overlay with 30 rows |
| **Definability** | Q2 ❌: `FAILED-TESTS` is missing, and whether `iteration` means `iteration_id` is undefined · Q3 ❌: which Phase-2 artifacts are append-only is listed for only two of them |
| **Status** | **NOT-YET-DEFINED in part.** Check (iii) on THEORY-EVOLUTION is SPECIFIED and runnable. Checks (i) and (ii) need **D-5**: the append-only artifact list, whether `iteration` ≡ `iteration_id`, and whether I-11 means an artifact that should exist |

### KOS-G-005 · L5 unreachability

| Field | Specification |
|---|---|
| **Source rule** | Q1 (`level_census[L5] == 0`), RA-4, I-4, provenance class `GOVERNANCE_ACT` ("not available to Phase 2") |
| **Purpose** | Phase 2 must not adopt anything |
| **Applicability** | POST every P2-* unit, over the whole `phase2_extraction/` tree at `head_sha` |
| **Inputs** | every Phase-2 record carrying an epistemic-ladder level; every record carrying `asserted_by` |
| **Deterministic check** | count(level == `L5`) == 0; count(maturity == `M5`) == 0; count(`asserted_by` == `GOVERNANCE_ACT`) == 0 |
| **PASS** | all counts are 0 **and** items examined > 0 for each count |
| **FAIL** | any count > 0. `fault_class: GOVERNANCE/PROCESS` (an authority violation) |
| **INCONCLUSIVE** | a count examined 0 items (the field is absent everywhere) |
| **Violation fixture** | append `{"level":"L5"}` to `THEORY-CONSTRUCTION.jsonl` → FAIL · `{"asserted_by":"GOVERNANCE_ACT"}` → FAIL |
| **Clean fixture** | current `THEORY-CONSTRUCTION.jsonl` → PASS on the level count |
| **Current data (V)** | the `level` field exists **only** in `THEORY-CONSTRUCTION.jsonl` (7 records, values `L2`, `L3`), so level count = 0 L5 over 7 records. **No maturity field exists in any JSONL.** **`asserted_by` occurs 0 times in the whole tree.** The theory document uses the separate `A–E` scale (Q43), which is a different ladder, and contains 0 occurrences of "L5" or "M5" |
| **Expected verdict today** | `INCONCLUSIVE`. Level: PASS over 7 records. Maturity: 0 items → INCONCLUSIVE. `GOVERNANCE_ACT`: 0 items → INCONCLUSIVE. Reported scope: *"L5 absent from the 7 records that carry a ladder level; maturity and provenance class are not machine-recorded."* |
| **Definability** | Q2 ⚠️: maturity and `asserted_by` are not in the data. Q3 ⚠️: how the `L0–L5` ladder relates to the `A–E` scale (Q43) is not stated for gating purposes |
| **Status** | **SPECIFIED — runnable with reduced coverage**, and that coverage is stated in the verdict. **D-6:** is a census over 7 records meaningful enough to count as a gate, or should it be DEFERRED until level and maturity are recorded per statement? |

### KOS-G-007 · Referential integrity

| Field | Specification |
|---|---|
| **Source rule** | Q5 (per item), P-1, P1P §37 checks 1 and 5 |
| **Purpose** | "a broken graph makes every traversal wrong without error" |
| **Applicability** | POST every unit, over the whole `D/` tree at `head_sha` |
| **Inputs** | all JSONL files; the **prefix → defining registry map**; the collision map `SOURCE-LOCAL-IDENTIFIERS.jsonl` |
| **Deterministic check** | (i) ids are unique within their defining registry · (ii) every reference to a mapped prefix, found in a **reference-bearing field**, resolves to a defined id · (iii) every verified edge's `source` and `target` resolve to a `file_id` |
| **PASS / FAIL / INCONCLUSIVE** | FAIL on a duplicate or unresolved reference, `fault_class: EVIDENCE/RECORDING` · INCONCLUSIVE on a prefix with no mapping |
| **Violation fixtures** | a duplicate `file_id` → FAIL · a relation that references `T-9999` → FAIL · a verified edge targeting `F9999` → FAIL |
| **Clean fixture** | HEAD → PASS for mapped prefixes |
| **Current data (V)** | Step-1 JSONL files use **13 id prefixes**: `C, CMP, DI, E, G, HA, IFR, OC, R, RO, SI, T, TH`. The collision map records multiple meanings for `D-` and `R-`, among others. `check-gates.py` checks only `T-`, `G-` and `F`, and matches them anywhere in raw text rather than in reference fields |
| **Definability** | Q1 ❌ **without a prefix map**. Which prefix is defined in which registry, and which fields carry references (as opposed to prose that mentions an id), is written down nowhere. Q3 ⚠️: the rule depends on the collision map for the ambiguous prefixes |
| **Status** | **SPECIFIED — NOT RUNNABLE** until the prefix map exists (**D-7**). Drafting the map is mechanical; **approving it is a human act**, because a map that leaves out a prefix silently narrows the gate |

### KOS-G-010 · Theory Discovery Index (form)

| Field | Specification |
|---|---|
| **Source rule** | P1-Q1 (P1P l.38–64), RA-10, ACL-1 |
| **Purpose** | stop a file being excluded from theory because of its **kind**, at the point where it is read |
| **Applicability** | POST every P1-FILE and P1-BATCH unit; whole index at `head_sha` |
| **Inputs** | `FILE-REGISTRY.jsonl`, `THEORY-DISCOVERY-INDEX.jsonl` (both **must exist**) |
| **Deterministic check** | (i) the set of registry `file_id`s whose read status is complete ⊆ the set of index `file_id`s · (ii) no index entry has a field named `document_kind`, `folder`, `filename`, `artifact_type` or `kind` · (iii) every `candidate_theory_bearing: false` has a non-empty `why` · (iv) `candidate_theory_bearing` ∈ {true, false}, `confidence` ∈ {HIGH, MEDIUM, LOW} |
| **Warn → REV (not a FAIL)** | a `why` whose content is only a kind term. The protocol's own example is "governance/process/administrative document". Only that example list is used; **extending it would be a rule change** |
| **PASS** | (i)–(iv) hold and items examined > 0 |
| **FAIL** | a missing entry, an inadmissible field, a missing `why`, or an out-of-vocabulary value. `fault_class: EVIDENCE/RECORDING` |
| **INCONCLUSIVE** | either input is missing or empty |
| **Evidence output** | coverage n/m; the count of `false` entries (reported, never scored: C2) |
| **Violation fixtures** | a registered file with no entry → FAIL · an entry with `document_kind` → FAIL · `false` with an empty `why` → FAIL · `confidence: "VERY_HIGH"` → FAIL · registry file deleted → **INCONCLUSIVE** (not PASS) |
| **Clean fixture** | HEAD → PASS |
| **Current data (V)** | 30/30 covered; 0 `document_kind`; **0 `false` entries**, so the `false` branch has never been exercised (NDF-08). Expected: **PASS**, with open question "C2: discriminator has never returned false" → `PASS_WITH_OPEN_QUESTIONS` |
| **Definability** | ✅ all five. One precision needed: which `read_status` value means "READ_COMPLETE". The registry carries `read_status`; the card uses the protocol's term, and the value mapping is part of **D-8** (small) |
| **Status** | **SPECIFIED**. The first candidate for fixturing |

### KOS-G-030 · Admission completeness (form)

| Field | Specification |
|---|---|
| **Source rule** | Q19 ("every Step-1 node is admitted to synthesis — T- **and** HA- and any other typed node; ungrouped ≠ excluded"), Q45 |
| **Purpose** | nothing is parked or dropped before synthesis. The v2 dry run lost 21 objects |
| **Applicability** | POST every P2-READ unit (the phase-B admission of that synthesis run) |
| **Inputs** | the Step-1 typed-node set at the unit's `base_sha`; the admission record of that run (`phase2_extraction/_phaseB_nodes.json`) |
| **Deterministic check** | Step-1 node ids == admitted ids. `grouped: false` counts as admitted |
| **PASS / FAIL / INCONCLUSIVE** | FAIL on any node missing from admission. INCONCLUSIVE when no admission record exists for the run, or the node-type list is unset |
| **Violation fixture** | remove `HA-0003` from `_phaseB_nodes.json` → FAIL |
| **Clean fixture** | admission equals the node set → PASS |
| **Current data (V)** | `_phaseB_nodes.json` is dated iteration 1 and holds 33 nodes (23 THEORY_OBJECT, 10 ARCHITECTURE_OBJECT). THEORY-OBJECTS now has 30: **T-0024…T-0030 were added by later units and are not in any admission record.** Whether the v0.7 update (`3d1f31195`) was a synthesis run that needed a new phase B is **undetermined**, because units are undeclared |
| **Expected verdict today** | `INCONCLUSIVE`, not FAIL. The gate cannot tell a missed admission apart from a unit that did not require one |
| **Definability** | Q3 ❌ "any other typed node" is open-ended, so the node-type list is undefined. Q2 ⚠️ there is one admission file, not one per run |
| **Status** | **SPECIFIED — NOT RUNNABLE** until the node-type list exists (**D-9**) and units are declared (**D-3**) |

### KOS-G-052 · A machine proposal gains no authority (form)

| Field | Specification |
|---|---|
| **Source rule** | I-14, P2P §3B.6 six provenance classes ("`asserted_by` is mandatory and non-defaultable"; "a `MACHINE_PROPOSAL` cannot reach L3+ without a `HUMAN_ASSERTION` or `VALIDATED_FINDING` referencing it"), I-5b |
| **Purpose** | AI discovers ≠ AI decides |
| **Applicability** | POST every P2-* unit; whole `phase2_extraction/` tree |
| **Inputs** | every Phase-2 statement record |
| **Deterministic check** | (i) every record has `asserted_by` ∈ {SOURCE_ASSERTION, DETERMINISTIC_DERIVATION, MACHINE_PROPOSAL, HUMAN_ASSERTION, VALIDATED_FINDING} (no `GOVERNANCE_ACT` in Phase 2) · (ii) every `MACHINE_PROPOSAL` at level ≥ L3 has a reference that resolves to a HUMAN_ASSERTION or VALIDATED_FINDING record · (iii) no promotion without an `AuthorityAct` reference |
| **PASS / FAIL / INCONCLUSIVE** | FAIL on a missing or invalid `asserted_by`, or an unreferenced L3+ proposal. `fault_class: EVIDENCE/RECORDING` for (i); `GOVERNANCE/PROCESS` for (ii) and (iii) |
| **Mandatory scope clause** | *"Form only. HUMAN_ASSERTION records are not attested; this PASS does not certify that a human acted."* It stays until an attested channel exists (NDF-R7) |
| **Violation fixtures** | a record without `asserted_by` → FAIL · a `MACHINE_PROPOSAL` at L3 with no reference → FAIL · a reference to a missing HUMAN_ASSERTION → FAIL |
| **Clean fixture** | a MACHINE_PROPOSAL at L2, plus one at L3 referencing a HUMAN_ASSERTION → PASS |
| **Current data (V)** | **`asserted_by` occurs 0 times in all committed JSONL.** The provenance-class mechanism was specified and never implemented. Check (i) would **FAIL every Phase-2 record** |
| **Definability** | ✅ all five. The rule is precise. The data does not implement it |
| **Status** | **SPECIFIED.** Running it today yields a universal FAIL, which is **a finding about the recorded data, not a defect in the gate.** **D-10 (human):** activate it and record the universal FAIL as a legacy finding with a remediation obligation, **or** DEFER until `asserted_by` is recorded. ⛔ It must not be weakened to "optional" just so that it can pass |

### KOS-G-060 · Falsifier pre-registered

| Field | Specification |
|---|---|
| **Source rule** | Q35 ("`falsification_condition` stated **before execution**"), Q47 (form part), Q13 |
| **Purpose** | a test that cannot fail is not a test (the `bar` failure) |
| **Applicability** | PRE and POST each P2-TEST unit that executes an experiment |
| **Inputs** | `EXPERIMENTS.jsonl` history across commits (`git log -p`) |
| **Deterministic check** | for every experiment id with `executed: true`: there is an **earlier commit** in which the record exists with a non-empty `falsification_condition` and `executed: false` (or no `result`), **and** `falsification_condition` is byte-identical between that commit and the result commit |
| **Why commit order** | a timestamp is written by the agent that ran the experiment. Commit ancestry, once pushed and seen by CI, cannot be back-dated. This is an evidence mechanism for Q35's existing rule, not a new rule |
| **PASS** | pre-registration found for every executed experiment, falsifier unchanged |
| **FAIL** | the falsifier first appears in the same commit as the result, is missing, or changed after execution. `fault_class: GOVERNANCE/PROCESS` |
| **INCONCLUSIVE** | the experiment was introduced before the baseline (history unavailable) |
| **Violation fixtures** | falsifier and result in one commit → FAIL · falsifier edited in the result commit → FAIL · empty `falsification_condition` → FAIL |
| **Clean fixture** | commit A has the falsifier with `executed:false`; commit B sets `executed:true` and the result, falsifier unchanged → PASS |
| **Current data (V)** | `EXPERIMENTS.jsonl` has **1** record, with `falsification_condition` present. It was introduced in `708674287` together with its result |
| **Expected verdict today** | `INCONCLUSIVE` (legacy; the order cannot be established). ⛔ Not FAIL: the absence of evidence of pre-registration is not evidence of its absence |
| **Epistemic meaning** | NONE. Even a FAIL means only that *the experiment's outcome cannot be used as evidence*, never that the hypothesis failed |
| **Definability** | ✅ all five |
| **Status** | **SPECIFIED.** Runnable today; it produces legacy INCONCLUSIVE until the first experiment registered under this card. Operationally it requires experiments to be committed in two steps (**D-11**, to be confirmed as practice) |

### KOS-G-070 · Candidate Theory current and complete

| Field | Specification |
|---|---|
| **Source rule** | Q41 ("exists and is current at every checkpoint"), Q60 ("every seed item appears … or is recorded `DELIBERATELY_OMITTED` with a reason — with a mechanically checkable identifier link (§5A.7)"), P2P §5A.7 (body/trace separation; the set difference must be empty) |
| **Purpose** | theory items cannot silently drop out of the primary document (3 of 11 were dropped, "and every gate passed") |
| **Applicability** | POST every P2-CONSOLIDATE unit (a checkpoint) |
| **Inputs** | `phase2_extraction/THEORY-SEED.md`, `phase2_extraction/CANDIDATE-KNOWLEDGEOS-THEORY.md`, and the location of its **trace appendix** |
| **Deterministic check** | (i) the theory document exists and is changed in the checkpoint unit's diff (Q41 "current") · (ii) `{SI-nnnn in seed} − {SI-nnnn in trace appendix} − {SI-nnnn recorded DELIBERATELY_OMITTED with a non-empty reason}` = ∅ · (iii) `{SI in trace} − {SI in seed}` = ∅ (a phantom trace) |
| **PASS / FAIL / INCONCLUSIVE** | FAIL on an untraced or phantom item, or a theory document unchanged at a checkpoint. INCONCLUSIVE when the seed or theory document is missing, the trace appendix cannot be located, or the unit is not a declared checkpoint |
| **Violation fixtures** | remove `SI-0003` from the trace (the historical state) → FAIL · add `SI-0999` to the trace → FAIL · a checkpoint unit that does not touch the theory document → FAIL · seed file deleted → **INCONCLUSIVE** |
| **Clean fixture** | HEAD → PASS on (ii) and (iii) |
| **Current data (V)** | 20/20 seed items traced; 0 phantom; 0 `DELIBERATELY_OMITTED`. (ii) and (iii) would PASS. (i) cannot be evaluated because checkpoints are undeclared |
| **Definability** | Q1 ⚠️ §5A.7 requires a trace appendix but does not fix **how it is delimited**. `check-gates.py` uses the heading "`# Appendix · Trace`", a convention of the research session that is not in the protocol. Q3 ⚠️ "checkpoint" is defined as a phase (K), not as an identifiable commit |
| **Status** | **SPECIFIED — NOT RUNNABLE for (i)**; (ii) and (iii) are runnable once the trace delimiter is fixed as a mechanical convention (**D-12**). D-12 is a format decision, not a research rule |

### KOS-G-071 · Relations accounted (form)

| Field | Specification |
|---|---|
| **Source rule** | Q59 ("every recovered theory object states its RELATIONSHIPS or records `NONE_FOUND` with a reason") |
| **Purpose** | stop a theory made of disconnected fragments |
| **Applicability** | POST every P2-CONSOLIDATE unit; whole overlay at `head_sha` |
| **Inputs** | `THEORY-OBJECTS.jsonl` (Step 1, read only), `phase2_extraction/THEORY-OBJECT-RELATIONS.jsonl` |
| **Deterministic check** | (i) `{theory_object_id}` ⊆ `{object}` in relations · (ii) every value `NONE_FOUND` inside `relations` has a stated reason · (iii) at most one relation record per object |
| **PASS / FAIL / INCONCLUSIVE** | FAIL on an object with no relation record, a `NONE_FOUND` without a reason, or a duplicate. INCONCLUSIVE when either input is missing |
| **Mandatory scope clause** | *"Relationships ACCOUNTED FOR, not relationships DISCOVERED; the denominator is unknown (C3)."* |
| **Violation fixtures** | delete the relation row for `T-0005` → FAIL · a `NONE_FOUND` with no reason → FAIL · a duplicate row → FAIL |
| **Clean fixture** | HEAD → PASS under the per-record reading |
| **Current data (V)** | 30/30 objects have a record. Every `NONE_FOUND` sits in a record with **one top-level `reason`**. **T-0006 and T-0009 each have two `NONE_FOUND` values (`premises`, `conclusion`) sharing that single reason** |
| **Definability** | Q3 ❌: Q59 does not say whether "with a reason" means **per `NONE_FOUND` value** or **per object** (NDF-R5). Under the per-object reading, HEAD passes. Under the per-value reading, it depends on whether the shared reason covers both values, which is a judgement |
| **Status** | **NOT-YET-DEFINED on (ii)** until **D-13** decides per value or per object. (i) and (iii) are SPECIFIED and runnable |

---

## 7. Fixture requirements (all gates)

1. **Fixtures before the runner.** A gate enters `FIXTURED` only when its fixture set exists and has been evaluated by hand, or by a reference implementation, with the expected verdicts.
2. **Every AUT check has at least one violation fixture that must FAIL, and one clean fixture that must PASS.**
3. **Every input-reading check has a "missing input" fixture that must give INCONCLUSIVE.** This is the defect found in §9.
4. **Zero-items fixture:** an empty but present input → INCONCLUSIVE.
5. **Historical back-test:** where a recorded historical defect exists in committed history, a fixture reproduces it and must FAIL:
   - G-070: the 3-of-11 seed drop;
   - G-071: the two NONE_FOUND-without-reason entries from B-2's first pass;
   - G-002 **cannot** have this fixture for RO-0014, because it predates git history. That limit is stated in the gate's scope.
6. **Fixtures are synthetic copies**, built from a `git archive` snapshot. They never edit `D/`.
7. **Location:** fixtures are owned by governance and are not writable by the research executor. Placement is catalog §14, decision **D-14**.
8. **A fixture suite failure at a runner version makes every verdict of that version `INCONCLUSIVE(INSTRUMENT)`.**

---

## 8. NOT-YET-DEFINED dependencies and decisions

| ID | Decision / missing definition | Blocks | Research rule or mechanism? | Who |
|---|---|---|---|---|
| **D-1** | Step-1 artifact manifest (which root files are the Step-1 record) | G-002 | mechanism; must agree with P2P §15.0 | human approves |
| **D-2** | Baseline commit, plus the legacy ruling for pre-git RO-0014 | G-002, G-004 (i) | mechanism (H-4) | human |
| **D-3** | Unit declaration and carrier; no mixed P1/P2 units | G-002(b), G-003, G-030, G-060, G-070(i) | mechanism | human |
| **D-4** | ⛔ **Q18 vs Q61 contradiction** (state file at root) | G-003 | **research rule (`ARCH` §9)** | human |
| **D-5** | Append-only artifact list; `iteration` ≡ `iteration_id`?; the missing `FAILED-TESTS` artifact | G-004 (i)(ii) | research rule (clarification) | human |
| **D-6** | Is a 7-record L5 census meaningful, or DEFER until level and maturity are recorded? | G-005 | judgement | human |
| **D-7** | Prefix → registry map and reference-bearing fields | G-007 | mechanism (drafted mechanically) | human approves |
| **D-8** | `read_status` value that means READ_COMPLETE | G-010 (i) | mechanical mapping | human confirms |
| **D-9** | Step-1 typed-node list ("any other typed node") | G-030 | research rule (clarification) | human |
| **D-10** | Activate G-052 with a universal legacy FAIL, or DEFER | G-052 | judgement | human |
| **D-11** | Two-commit experiment practice | G-060 | practice (evidence for existing Q35) | human confirms |
| **D-12** | Trace-appendix delimiter | G-070 (ii)(iii) | format convention | human confirms |
| **D-13** | NONE_FOUND reason per value or per object | G-071 (ii) | **research rule (clarification)** | human |
| **D-14** | Placement of registry, fixtures and ledger (catalog §14) | all | mechanism | human |

Carried from the catalog: **NDF-R7**, the attested human-act channel. It limits G-052 to "form only", and it limits every `ACCEPTED` transition in §2.

⛔ **Rows marked "research rule" change or clarify the methodology.** They go through `ARCH` §9, and **no gate may be activated by quietly assuming an answer.**

---

## 9. Research-session self-check vs. governance gate

### 9.1 The distinction

| | Research-session self-check | Governance gate |
|---|---|---|
| Authored by | the executing research session | Governance specification; an implementer other than the executor |
| Judges | its own output | another session's output |
| Status of its PASS | **self-check / development evidence** | authoritative only when `ACTIVE` |
| Requirements | none enforced | the card in §6, fixtures (§7), read-only, commit-bound, independent review, human ACCEPT |

A self-check is **useful and should continue**. It gives the executor early feedback. **It never substitutes for a gate verdict**, and a self-check PASS must not be reported as "gate passed".

### 9.2 Review of the committed self-check `D/check-gates.py` (commit `fa8b0e240`)

**Good practices, which the specification keeps:**
- It is read-only: the source has no file writes.
- It lists 13 judgement gates as UNCHECKABLE and does not treat them as passing.
- It states "a green run does NOT mean the protocol was followed".
- It reports C2 without scoring it.
- On first run it found a real Q57 failure (3/13).

**Planted-violation probes.** These were run against a `git archive` copy of HEAD in the scratchpad. `D/` was not touched.

| Probe | Planted violation | Result | Finding |
|---|---|---|---|
| a | `FILE-REGISTRY.jsonl`, `THEORY-DISCOVERY-INDEX.jsonl` and `THEORY-SEED.md` **deleted** | **P1-Q1: all three checks PASS**; Q60 "every seed item traced" PASS | ⛔ **Fails open.** A missing file is read as an empty list (`load_jsonl` returns `[]`), so "0 missing of 0" passes |
| b | every gap set to `resolution_status: "NOT_FILLED"` | **Q57 PASS 13/13; exit 0; the whole run green** | ⛔ **False PASS.** A substring match (`"FILLED" in "NOT_FILLED"`) accepts it, and the vocabulary also adds `RESOLVED` and `PARTIALLY_RESOLVED`, which Q57 does not contain. **The instrument extends a research rule** |
| e | one relation record gets three `NONE_FOUND` values plus an unrelated key `unrelated_reason_note` | Q59 "no NONE_FOUND without a reason" PASS | ⚠️ Any key whose name contains "reason", anywhere in the record, satisfies every `NONE_FOUND` |
| c | in-place edit of an existing `THEORY-OBJECTS.jsonl` row | undetected | outside the script's declared scope (no immutability check). Recorded as a coverage gap, not a bug |
| d | `L5` / `GOVERNANCE_ACT` record injected into Phase 2 | undetected | outside declared scope (no L5 census) |
| f | a Phase-2-style append to Step-1 `GAPS.jsonl` | undetected | outside declared scope (no write-set check) |

**Further observations (V):**
- **Label drift:** the check labelled `Q43` tests "no JSON filenames in the body", which is a facet of Q42 / §5A.7. The real Q43 (epistemic level A–E, never `F`) is not checked. A check ID that does not match its rule ID is the RA-9 collision pattern, inside the instrument itself.
- **Mislabelled as uncheckable:** Q45 (contribution disposition present), Q52 (field presence) and Q61 (state updated in the unit) each have a deterministic *form* part.
- Runs on the **working tree**, not a commit. Has **no INCONCLUSIVE** (exit 0/1 only), **no fixtures**, and **no tests**.
- Lives inside `D/`, the tree it judges, and can be written by the executor.
- Tier-A coverage: G-010 (form, fail-open) · G-070 (ii)(iii) (fail-open on a missing seed) · G-071 (i)(iii), plus a weak (ii) · G-007 (partial: T/G/F only, raw text). **None of G-002, G-003, G-004, G-005, G-030, G-052 or G-060.**

**Classification:** `D/check-gates.py` is a **research-session self-check (development instrument)**. It may inform the implementation of Tier-A runners, but **only after the §9.2 defects are fixed and the §7 fixture suite passes**, and the fix is not to be made by the session whose output it judges. Its Q57 result (3/13 FAIL) is reproducible and correct in direction. Its Q57 PASS values would not be trustworthy, because of probe b.

This review **records** these defects and does not repair them. The file belongs to the research session's commit history, and repairing it is an authorized later step (ER-08: reviews record deviations; implementation repairs them).

---

## 10. Open human decisions — per gate

The human decides **the mechanism by which the research process is judged against its own methodology**, not the theory.

| Gate | Specification status | Recommended decision | Blocking decisions |
|---|---|---|---|
| **G-010** P1-Q1 | SPECIFIED | **ACCEPT** for fixturing | D-8 (small) |
| **G-060** Falsifier pre-registered | SPECIFIED | **ACCEPT** for fixturing | D-11 |
| **G-052** Machine ≠ authority | SPECIFIED; would FAIL universally | **ACCEPT** the specification; decide D-10 | D-10, NDF-R7 |
| **G-002** Step-1 immutability | SPECIFIED — not runnable | **ACCEPT**; mode (a) first | D-1, D-2 (D-3 for mode b) |
| **G-070** Theory complete | SPECIFIED — (i) not runnable | **ACCEPT** (ii)(iii) | D-12 (D-3 for i) |
| **G-005** L5 census | SPECIFIED — reduced coverage | **REVISE or DEFER** | D-6 |
| **G-007** Referential integrity | SPECIFIED — not runnable | **ACCEPT** once the map exists | D-7 |
| **G-004** Append-only history | partly NOT-YET-DEFINED | **REVISE** | D-5 |
| **G-071** Relations accounted | partly NOT-YET-DEFINED | **REVISE** | D-13 |
| **G-030** Admission | SPECIFIED — not runnable | **DEFER** | D-9, D-3 |
| **G-003** Write-set | ⛔ BLOCKED (Q18 vs Q61) | **DEFER** until D-4 | D-4 (`ARCH` §9) |

**Suggested first slice (after ACCEPT):** fixtures for **G-010, G-060, G-002(a), G-070(ii)(iii)**. These four need only small or mechanical decisions (D-1, D-2, D-8, D-11, D-12) and together protect three ★ properties: HIST, FALS and COMP.

**Not requested and not done:** no runner, no fixture files, no hook, no CI, no change to any gate or research artifact, no authoritative verdict.

---

*Traceability:*
- Source rules: `ARCH` v1.1 (RA-1..RA-12, ACL-1), `P1P` (P1-Q1, §5A, §37), `P2P` (§3A.6, §3B.6, §5A.7, §15.0, §16 Q1/Q5/Q13/Q17/Q18/Q19/Q24/Q26/Q32/Q35/Q41/Q45/Q47/Q59/Q60/Q61, §16B).
- Data facts computed read-only at HEAD `fa8b0e240` via `git archive`.
- Commit facts from `git show --numstat` on `708674287`, `6c3aa64da`, `6eaff361c`, `3d1f31195` and `fa8b0e240`.
- Probe scripts ran in the session scratchpad only.

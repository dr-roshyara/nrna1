# Increment 0 — Root-Cause Closure, F0031–F0040

| | |
|---|---|
| **Kind** | analysis only. ⛔ **No repair · no ID rewritten · no research artifact modified** |
| **Status** | ⚠️ PROPOSED — authority: generated |
| **Evaluated state** | HEAD `6a7ac1bd9`, taken as a `git archive` snapshot. The canonical list is `docs/knowledgeos/list_of_files_to_read.log` (commit `7698c99b4`, unchanged since) |
| **Consumes, does not repeat** | the research session's forensic report `ID-ROOTCAUSE-01.md` (commit `6a7ac1bd9`): verdict, mechanism (alphabetical directory listing plus incremented IDs), and forward mapping. This document adds what that report left open: the **reverse mapping**, **window status**, an **exact per-ID census**, the **collision hazard**, **remedy options assessed against RCI-015**, and the **preservation baseline** |

---

## 1. Exact mapping, in both directions

| Research ID | File actually read | **Its canonical ID** | Canonical file for the research ID |
|---|---|---|---|
| F0031 | Mission_Discovery | **F0038** | Phase_B_Evidence_Reconciliation |
| F0032 | Conceptual_Foundation | **F2837** | Operational_Validation_Report |
| F0033 | Ontology_Architecture_Classification | **F0037** | Operational_Knowledge_Principles |
| F0034 | Phase_B_Evidence_Reconciliation | **F0031** | Operational_Evidence_Register |
| F0035 | Operational_Evidence_Register | **F0034** | Ontology_Discovery |
| F0036 | Vision_Mission_Clarification | **F0042** | Ontology_Cross_Product_Validation |
| F0037 | Epistemic_Control_Systems_Comparison | **F2838** | Ontology_Architecture_Classification |
| F0038 | Operational_Knowledge_Principles | **F0033** | Mission_Discovery |
| F0039 | Meta_Model_Discovery | **F0039** ✅ | Meta_Model_Discovery |
| F0040 | Semantic_Architecture_Reconciliation | **F0045** | README |

## 2. Status of the canonical window F0031–F0040

| Canonical ID | File | Status |
|---|---|---|
| F0031 | Phase_B_Evidence_Reconciliation | **read, under research ID F0034** |
| F0032 | Operational_Validation_Report | ⛔ **never read** |
| F0033 | Operational_Knowledge_Principles | **read, under research ID F0038** |
| F0034 | Operational_Evidence_Register | **read, under research ID F0035** |
| F0035 | Ontology_Discovery | ⛔ **never read** (named successor of Meta_Model_Discovery, per `ID-ROOTCAUSE-01` §6) |
| F0036 | Ontology_Cross_Product_Validation | ⛔ **never read** (contains an unrecorded internal withdrawal; audit 2026-09-23 §3) |
| F0037 | Ontology_Architecture_Classification | **read, under research ID F0033** |
| F0038 | Mission_Discovery | **read, under research ID F0031** |
| F0039 | Meta_Model_Discovery | read, correct ID |
| F0040 | README | ⛔ **never read** |

**Summary:**
- **Correct:** 1 of 10.
- **Read, but under the wrong ID:** 5 (a permutation inside the window).
- **Unread:** 4.
- **Read from outside the window, under in-window IDs:** 4 files, canonical **F0042 · F0045 · F2837 · F2838**.

## 3. Affected artifacts: exact census

References to the IDs F0031–F0040 in the research tree at HEAD. `prompts/` and `architecture/` were excluded.

| Artifact | Refs | Per ID |
|---|---:|---|
| `THEORY-OBJECTS.jsonl` | 43 | F0031:4 · F0033:4 · F0034:9 · F0035:4 · F0036:6 · F0038:2 · F0039:5 · F0040:9 |
| `FILE-REGISTRY.jsonl` | 13 | every ID, 1–2 each |
| `THEORY-DISCOVERY-INDEX.jsonl` | 11 | every ID |
| `phase2_extraction/THEORY-OBJECT-RELATIONS.jsonl` | 11 | F0031:3 · F0034:1 · F0035:1 · F0038:1 · F0039:2 · F0040:3 |
| `phase2_extraction/CANDIDATE-KNOWLEDGEOS-THEORY.md` | 6 | F0031:3 · F0034:1 · F0036:1 · F0040:1 |
| `KNOWLEDGEOS-RESEARCH-STATE.md` | 5 | F0031:1 · F0035:1 · F0036:1 · F0040:2 |
| `phase2_extraction/THEORY-CHANGELOG.md` | 5 | F0031:2 · F0035:1 · F0036:1 · F0040:1 |
| `PREFLIGHT-LOG.jsonl` | 4 | F0031 · F0035 · F0036 · F0040 |
| `phase2_extraction/THEORY-SEED.md` | 4 | F0031 · F0035 · F0036 · F0040 |
| `phase2_extraction/RQ-KOS-01-REFERENT-DISAMBIGUATION.md` | 3 | F0031 · F0036 · F0040 |
| **Total: 10 research artifacts** | **105** | |
| `ID-ROOTCAUSE-01.md` (forensic) | 27 | *describes the defect; not contaminated* |

**Reconciling with `ID-ROOTCAUSE-01` §5 ("11 artifacts").** That list includes `architecture/research-control-architecture.md`. That document **cites the IDs in order to describe the divergence**, as does `ID-ROOTCAUSE-01.md` itself. **The contaminated set is the 10 research artifacts above.** Documents that describe the defect must be read as *pointing at the research-ID meaning*. Any correction must leave them unchanged, because they are historical records of the finding.

**F0039 is in the census but is not contaminated,** since its research and canonical meanings coincide. Its references do need to be told apart from the others in any mechanical correction.

## 4. Collision hazard (why a remedy cannot be deferred indefinitely)

- Five canonical files have already been read under **someone else's** canonical ID.
- When canonical **F0031** (Phase_B) is cited correctly in future, the ID F0031 will already mean Mission_Discovery in 29 existing references.
- The same holds for F0033, F0034, F0035 and F0038, and for F0032, F0036 and F0040 once those files are read.
- **Every new correct citation in the range makes the corpus-wide meaning of the ID ambiguous.** That is the RA-9 collision condition applied to identifiers.
- Until a remedy is chosen, **no new unit should cite F0031–F0040 at all**. That is a recommendation for L0, not a rule.

## 5. Remedy options, assessed (the choice belongs to L0)

`ID-ROOTCAUSE-01` §6 deliberately proposed no remedy. This section assesses the three options it named against RCI-015 (non-destructive evolution) and `ARCH` RA-2/RA-5. **It does not choose.**

| Option | What it does | RCI-015 / RA-2 | Risk |
|---|---|---|---|
| **A · Renumber in place** | rewrite the 105 references to canonical IDs | ⛔ **violates RCI-015 and RA-2** (Phase-1 records rewritten; the v0.9 text changed) | destroys the as-issued state that the audits evaluated; a mechanical rewrite over look-alike IDs (F0039 is correct) is exactly the kind of sweep the repository's editing policy forbids |
| **B · Annotate (correction overlay)** | append an ID-correction record per research ID, `{research_id → actual path → canonical_id}`, plus correction requests (RA-5); nothing existing is edited | ✅ consistent | every reader has to resolve through the overlay; this needs a resolver or gate, otherwise it becomes a second silent convention |
| **C · Freeze and forward** | preserve v0.9 as issued; open **v0.9-AUDIT**, classing each affected finding as retained · source-identity-affected · requires re-read · unsupported · unresolved; later work (v0.10) cites canonical IDs only; B's overlay is the bridge | ✅ consistent | the most work; but it is the only option that also schedules reading the **4 unread** canonical files |

**Observation:**
- A is the only option incompatible with the architecture proposal.
- B and C are not alternatives, because C contains B.
- Whichever is chosen, the **4 unread canonical files still have to be read.** No ID remedy covers that.

## 6. Preservation baseline

| Item | Value |
|---|---|
| Theory v0.9 as issued | commit `ca8b96d59` |
| Forensic report | commit `6a7ac1bd9` |
| Independent audit | commit `da9a5beec` |
| Snapshot artifact | ⚠️ `CANDIDATE-THEORY-SNAPSHOTS/` is **empty**. Until snapshots exist, the as-issued state is preserved **only** by git history |
| **Recommended L0 act** | tag the as-issued state, e.g. `git tag kos-theory-v0.9-as-issued ca8b96d59`. A tag is a human act and is **not** made here |

## 7. What Increment 0 does not do

It does not correct any ID, re-read any file, change v0.9, choose a remedy, or resume research. It closes the **analysis**. The remedy is decision RC-H-04 in `research-control-architecture.md` §13.

---

*Traceability:*
- The census was computed read-only from a `git archive` of `6a7ac1bd9`, as a regular-expression count of `F00(3[1-9]|40)` per file, excluding `prompts/` and `architecture/`.
- Both mappings are derived from `FILE-REGISTRY.jsonl` `path` against column 2 of the canonical list.
- Consumes `ID-ROOTCAUSE-01.md` §§1, 5, 6 and 7.

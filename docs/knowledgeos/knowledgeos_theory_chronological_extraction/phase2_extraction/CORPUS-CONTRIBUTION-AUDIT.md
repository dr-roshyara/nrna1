# Corpus Contribution Audit — F0001–F0025

| | |
|---|---|
| **Baseline** | Candidate Theory **v0.2** |
| **Range** | F0001–F0025 · ⛔ **F0026 not read** |
| **Rule** | ⛔ **Coverage means every potentially theory-bearing contribution has a DISPOSITION — not that every statement becomes theory** (§5B.3) |
| **Result** | **5 theory-bearing gaps found**, 4 of them in **F0012** · 1 provenance correction that strengthens an existing finding |

---

## 1 ⭐⭐ F0012 — the file that was under-read

F0012 was classified in Step 1 as an architecture consolidation and treated as migration material. **That classification was right about most of it and wrong about five quotations**, each of which is a claim about *what KnowledgeOS is*, not about where files live.

| # | Contribution | Classification | In v0.2? | Disposition |
|---|---|---|---|---|
| **12-A** | ⭐ *"**Governance precedes automation. Automation may implement governance. Automation never defines governance.**"* — Reference Architecture governing principle, quoted verbatim | **THEORY-BEARING** | ⛔ **NO** | ⭐ **NEW — admit to v0.3.** This is the *general form* of the separation of powers that `EXP-0002` tested as a special case |
| **12-B** | ⭐ *"It governs how engineering work is planned, approved, executed, verified, and evolved, **independent of project, programming language, or execution provider**"* | **THEORY-BEARING** | ⛔ **NO** | ⭐ **NEW — admit.** A claim about the platform's *invariance*, not its packaging |
| **12-C** | **Four-layer runtime model** — `GOVERNANCE POLICY → CAPABILITY MAPPING → RUNTIME ADAPTER → CONCRETE CONFIGURATION`, with *"Capability Mapping is NOT an adapter… it exists so that Claude-specific vocabulary **cannot LEAK UPWARD** into the governance model"* | **THEORY-BEARING** | ⛔ **NO** | ⭐ **NEW — admit.** An anti-corruption layer stated as a *design invariant*, and the mechanism that makes 12-B achievable |
| **12-D** | ⭐ *"KnowledgeOS may be extracted by **REFERENCE, never by COPY**"* — BRM-1 RETAIN MODEL B; ES-005.4 *"one rule → one home"* | **CANDIDATE-THEORY-BEARING** | ⛔ **NO** | ⭐ **NEW — admit as candidate.** A claim about knowledge *identity*: the same rule may not exist twice |
| **12-E** | *"components carry stable ids **CMP-nnn**… **Paths change; ids never do.** ADRs reference ids, not paths"* | **CANDIDATE-THEORY-BEARING** | ⛔ NO | ⚠️ **admit as candidate** — identity independent of location; relates to provenance |
| **12-F** | ⭐⭐ `G-C1`: *"Operational Evidence stands at **ZERO-INDEPENDENT**… The arrow has never been traversed"* — **attributed to EAD-1** | **EVIDENCE-BEARING** + **provenance correction** | partially | ⭐ **STRENGTHENS an existing finding — see §2** |
| **12-G** | `G-C2`: **CAP-001 — 0 executions, 0 decisions changed** | **IMPLEMENTATION-EVIDENCE** | ⛔ no | ⭐ **admit as evidence** — direct observation at **realization layer D** (§13A) |
| **12-H** | Migration map M-1…M-16; stages 0–5; reserved namespaces | **ARCHITECTURE-BEARING** | no | ⛔ **CORRECTLY EXCLUDED.** §5B.3: migration maps and stage roadmaps stay architecture material. **Preserved in** `HISTORICAL-STORY.md` and the Step-1 registries |
| **12-I** | `OQ-K1`…`K6`, `OQ-C1`…`C2` | **OPEN-QUESTION** | no | **admit to the open-question register** |
| **12-J** | *"the current strategic model is sufficient **FOR THE PRESENT MATURITY LEVEL**"* | **HISTORICAL/CONTEXTUAL** | no | ⛔ excluded from theory; preserved in the story |
| **12-K** | Measured landscape (1,532 code files · 448 md · 106 reports) | **NON-THEORETICAL** | no | ⛔ excluded; preserved |

### ⭐ 2 · The provenance correction — `12-F`

The *"0 traversals"* claim appears in **≥ 7 files**. F0012 attributes it to a **single named source**: `EAD-1 §0, claim 3`.

> ⭐ **This confirms `SOURCE_REPETITION`, not corroboration.** Seven appearances of one claim traced to one origin are **one observation**, not seven. It is exactly the case `Q22` forbids counting as maturity — and F0012 supplies the proof that the seven share an origin.

⛔ **It does not settle whether the claim is true.** The independent review's falsification and its three records remain outside reach.

---

## 3 · The other 24 files — dispositions

| Files | Principal contributions | Classification | Disposition |
|---|---|---|---|
| **F0001** | five-tier architecture · six subsystems · generation loop with per-arrow evidence | **ARCHITECTURE** + **EVIDENCE** | represented (§4, §6) |
| **F0002 · F0003 · F0004** | validation matrices · REV-2 revisions · projection subordinating itself to its source | **EVIDENCE** + **HISTORICAL** | represented in the story; ⚠️ *C-1 model amnesia at n=11* **partially represented** |
| **F0005** | genesis as *"the ONE architectural gap"* | **CANDIDATE-THEORY** | represented (§2) |
| **F0006** | decision packages D-1…D-10 · requirement-candidate register | **GOVERNANCE-CONSTRAINT** | correctly excluded — governance process |
| **F0007** | AI workflow observation log | **HISTORICAL** | correctly excluded |
| **F0008** | bootstrap finding · *"no strategic-DDD method"* | **CANDIDATE-THEORY** + **CONTRADICTION** | represented; the claim's refutation represented |
| **F0009** | `PD-3` — a governed domain owned by **a named individual** | **THEORY-BEARING** | represented (§12, `SI-0006`) |
| **F0010** | nine admissible justifications applied · loop arrows | **ARCHITECTURE** | represented |
| **F0011** | product-boundary discovery | **ARCHITECTURE** | represented |
| ⭐ **F0012** | **see §1 — 5 gaps** | mixed | ⭐ **4 NEW, 1 candidate** |
| **F0013** | meta-model dimensions D-I…D-VI | **CANDIDATE-THEORY** | ⚠️ **UNRESOLVED** — relation to `RQ-002` undetermined (`G-0009`) |
| **F0014** | `LG-1` posed · four level confusions · nesting closes `H-3` | **CANDIDATE-THEORY** | represented (`SI-0005`) |
| **F0015** | falsifies *"0 traversals"* (n≈3) · occurrence #11 | **FALSIFIER** | represented (`CMP-0001`) |
| **F0016 · F0017** | positioning verdict + refusal to adopt · PROV survey | **CANDIDATE-THEORY** + **EVIDENCE** | represented (§1) |
| **F0018** | authority = provenance × standing · *evidence earns, governance grants* | **THEORY-BEARING** | ⭐ represented (§3, `SI-0002`, `SI-0009`) |
| **F0019** | 9 rediscoveries · self-refuted correlation · declines a 4th taxonomy | **EVIDENCE** + **CONTRADICTION** | represented (§2) |
| **F0020** | four bounded contexts · `D-6` splits · `Baseline` × 5 | **ARCHITECTURE** + **THEORY** | represented (§5, §11) |
| **F0021** | improvement cycle | **GOVERNANCE-CONSTRAINT** | correctly excluded |
| **F0022 · F0023** | decision-model validation and discovery | **GOVERNANCE-CONSTRAINT** | correctly excluded — ⚠️ *but see follow-up* |
| **F0024** | falsification verdicts per domain | **FALSIFIER** | represented |
| **F0025** | implementation that actually exists (`init`, `doctor`, `watch.php`) | **IMPLEMENTATION-EVIDENCE** | ⚠️ **PARTIALLY** — noted as a discontinuity; ⭐ **not yet used at realization layers C/D** |

---

## 4 · Coverage summary

| | Count |
|---|---|
| Files audited | **25 of 25** |
| Theory-bearing contributions | **14** |
| ⭐ **Newly discovered** *(not in v0.2)* | **5** — `12-A` `12-B` `12-C` `12-D` `12-E` |
| Represented | 8 |
| Partially represented | **3** — `C-1` at n=11 · F0025 implementation · `12-F` |
| Unresolved | **1** — F0013 ↔ `RQ-002` |
| Competing / contradictory | 0 new |
| ⛔ **Correctly excluded, with reason** | **6 classes** — migration maps · stage roadmaps · measured counts · governance process · observation logs · improvement cycles |

⛔ **No coverage was manufactured.** Six contribution classes are excluded *with a stated reason and a stated location*, per §5B.3.

## 5 · Follow-up obligations

| # | Obligation | From |
|---|---|---|
| `VO-01` | Determine whether **`12-A`** is the general principle of which `SI-0009` is a special case, or a distinct claim | `12-A` |
| `VO-02` | ⭐ **`12-G`** — CAP-001 at **0 executions** is a realization-layer-**D** observation. Combine with `OT-0002a` into one operational-realization question | `12-G` |
| `VO-03` | F0025's implementation vs realization layers **C/D** — what exists and what is invoked | F0025 |
| `VO-04` | F0013 ↔ `RQ-002` | `G-0009` |
| `VO-05` | ⚠️ **F0022/F0023 excluded as governance process — re-check.** A *decision model* may be theory-bearing; the exclusion was made on document kind, not content | audit self-check |

> ⚠️ **`VO-05` is an honest flag against my own audit.** Two files were excluded by *kind* rather than by reading their contributions — the same error F0020 names as *"filename thinking wearing a taxonomy."*

# S-Series Next Architecture Brief (design only; nothing executed)

**Scope:** S-Series only.

**Status:** an architecture hypothesis and a pilot design for human approval. **The pilot is not executed.** OB0018 is not executed; no production batch; H-19 SEALED; S5c PROHIBITED. v3.5, v1.7, the S5 population (1,975 labels, 66 hubs), K = 64 and A'1 are unchanged. The OB0004 R2/R2.2/R2.3 and Gate C records are unchanged.

**Tags:** OBSERVED / INFERRED / HYPOTHESIS / UNRESOLVED.

**Governing principle:** the current evidence supports neither "exhaustive research is sufficient" nor "research-first is sufficient".

## A. Gate C evidence (from `S-SERIES-GATE-C-STRATEGY-COMPARISON-REPORT.md`, G-LOG-0057; not re-run)

**Established (OBSERVED, on 2 labels only):**
- **Research-first ran cleanly and cheaply:** 51 candidates, 28 triggers, 16 files, 830,188 bytes (about 40% of the exhaustive arm), and 0 hindsight/selection failures by the mechanical checks.
- **It surfaced findings the reference missed:** 29 findings that the exhaustive reference had omitted from files it had itself read, 11 of them R3-VERIFIED. Its evidence was stronger in 6 of 11 matched pairs.
- **It did not reach the recall bar:** blind M1 = **0.462**, so the pre-registered H0 rule applies. The 0.500 value is sensitivity analysis only.
- **Where it missed:** reconstruction content, and early-file or birth information. 7 of 12 diagnosed misses were candidate-generation failures on information already in R1.
- **Reading completely does not guarantee complete research extraction:** the exhaustive reference had read the files that held the 29 omitted findings.

**Not established:** anything corpus-wide; model independence (same vendor); whether an improved research-first layer closes the gaps; whether the exhaustive reference's omissions are typical.

## B. Failure taxonomy (kept separate)

| Loss | Definition | Gate C measurement | Layer |
|---|---|---|---|
| **Candidate-generation loss** | the information is present in R1 input, but no candidate was formed | 7 of 12 A-only diagnoses (template-term false negatives, null type signatures, missing birth candidates, CORRECTION typing, a revival, a redundancy inference) | discovery (research-first) |
| **Trigger loss** | the finding lies in files the research-first arm never triggered | 34 of 38 unrecovered P1-gap records; births and origin in untriggered early files (S1413, S1438, S1439, S1441) | discovery (research-first) |
| **Extraction/register loss** | the finding lies in a file the arm read completely, but was never registered | exhaustive reference: 29 findings. Research-first: 3 P1-gap records in triggered files not surfaced | **both arms** |
| **Reconstruction loss** | a layer-A obligation (births, timeline, absences, statuses, edges) is not produced | research-first produced none by design; the exhaustive reference produced all of them | floor |
| **Verification limitations** | weaknesses of grading and auditing | lenient R3 self-grading (0 NOT-VERIFIED despite disconfirming clauses); one off-scale verdict (19/51); stage-2 blinding inferable from field structure; same-vendor auditor | both |

**INFERRED:** fixing candidate generation does not fix trigger loss (a different mechanism). Neither fixes extraction/register loss, which affects the exhaustive arm too.

### B.1 Four completeness states (not collapsed)

| State | Meaning | Measurable evidence | Status |
|---|---|---|---|
| **READ-COMPLETE** | every page of every required file consumed | READ-COVERAGE: pages 1..N logged with verified hashes (existing gate) | measurable now (OBSERVED in the pilot and R2.3) |
| **RECONSTRUCTION-COMPLETE** | every layer-A obligation of the label is filled under the production rules | verifier PASS on the object (G-01…G-13, READ-COVERAGE) | measurable now |
| **RESEARCH-EXTRACTION-COMPLETE** | every substantive finding a completely read file supports is registered, or explicitly declined with a reason | **proposed:** a file-local extraction record per read file with a fixed-class checklist (§D channel 4), plus an **independent re-extraction on a sample** measuring the extraction-loss rate | proposed (HYPOTHESIS: this is measurable by sampled re-extraction) |
| **THEORY-COMPLETE** | the theory is reconstructed and synthesized | not a P3b state (v3.5 P7) | out of scope |

## C. Proposed two-layer architecture (HYPOTHESIS; not adopted)

```text
                    S-SERIES CORPUS (frozen population)
                               │
              ┌────────────────┴────────────────┐
              │                                 │
   LAYER 1 — RECONSTRUCTION FLOOR      LAYER 2 — RESEARCH DISCOVERY
   (frozen v1.7 obligations)           (research-first v2, §D)
   READ-COMPLETE per label             R1 channels 1–4 from pre-S5 layers
   births, timeline, absences,         registered, frozen triggers
   statuses, edges                     page-proven targeted reading
   (decomposition for heavy labels:    file-local exhaustive extraction
    engineering question = OB0018)     strict R3 verification
              │                                 │
              └────────────────┬────────────────┘
                               │
                 RESEARCH REGISTER (layer B/C)
                 every record → layer-A records + S-ids
                               │
                 VERIFICATION + INDEPENDENT AUDIT
                               │
                 SYNTHESIS LATER (v3.5 P4–P7)
```

**INFERRED consequences:**
- **Layer 1 keeps the frozen reconstruction guarantees:** chronology, never-thinned absences, anti-hindsight through layer A.
- **Layer 2 gets strong analytical discovery** at lower cost. Its register records point to layer-A records where they exist (§1B rule 3).
- **UNRESOLVED:** whether layer 2 can run **before** layer 1 is complete for a label. If it does, it must run without layer-A anchoring and carry the hindsight caveats of the gate document §9.

## D. Improved research-first protocol (v2), for the pilot only

**Inherited unchanged from Gate C:**
- R1 from pre-S5 layers only;
- a trigger registry frozen and committed before any read;
- paged whole-file reading with page-hash proof;
- no untriggered reads; no reads before the freeze;
- a three-stage independent audit, with post-unblinding corrections as sensitivity only.

**Changes, each tied to a measured loss:**

| Channel | Change | Loss addressed |
|---|---|---|
| **1. Existing candidate generation** | kept as in Gate C (analyst-driven R1 discovery) | — |
| **2. Mandatory gap/null candidates (mechanical, before R1 analysis)** | a script emits one candidate per structured null or anomaly in the package: type_signature null in ≥ 90% of rows; P3a `completeness_absences` entries; null birth candidate in the reconciliation object; P3a verdict or pair conflicts; rows typed CONTRADICTION, CORRECTION or RETRACTION; stage-1 terms with zero raw hits, or template-form terms (`nnn`, `..`); files touching the label with no rows; P2 lifecycle labels (DORMANT/CONTESTED) produced by heuristics | candidate-generation loss |
| **3. Mandatory reconstruction triggers (mechanical)** | trigger: (a) the 3 earliest-dated files in stage-2 ∪ row sources (02-FILES best date; ties by S-id); (b) every stage-2 file dated earlier than all row sources; (c) every birth-candidate file named in the reconciliation object; (d) the earliest file with a row typed DEFINITION or CONCEPT with scope OBJECT (self-description) | trigger loss (births, origin, early self-description) |
| **4. File-local exhaustive extraction** | for **every** file read, the verifier writes a file-local extraction record against a fixed class checklist: definitions; rules/principles; formal structures; algorithms; contradictions; corrections; dependencies/relations; dates and births; self-descriptions; methodological issues; **other substantive**. Each class is PRESENT (with verbatim quotes) or ABSENT. Every PRESENT item becomes a register candidate, or is explicitly **declined with a reason**. **No silent omission** | extraction/register loss |
| **R3 grading** | only the pre-registered scale (VERIFIED / PARTIALLY-VERIFIED / NOT-VERIFIED / CONTRADICTED / UNRESOLVED). **Any off-scale verdict is refused by the checker.** Any recorded disconfirming clause forces PARTIALLY-VERIFIED or worse | verification limitations |

**Channels 2–3 are mechanical**, so they add no analyst degrees of freedom. The trigger rules are fixed before selecting labels.

## E. Pilot design (proposed; requires human approval before any execution)

**Question:** can a mandatory reconstruction floor plus an improved research-first layer be combined without weakening the frozen epistemic guarantees? Specifically: does v2 close candidate-generation and trigger loss, what is the extraction loss in each arm, and does the discovery layer replace the floor? (Hypothesis: it does not.)

### E.1 Population: new labels, not a reuse of Gate C

**Selection rule (pre-registrable; computed from structured metadata only, no content read):**
- non-hub, tier U, not in OB0004;
- mandatory text set of 150–600 KB, so one context suffices for the floor arm;
- at least 6 required files and at least 3 row sources.

That gives **106 eligible labels**. Failure-mode pools within them:
- null type signatures: 93;
- OMQ-14 content: 72;
- early stage-2 file before all row sources: 32;
- template terms: 10.

**Seeded picks** (seed 20261102), stratified by failure mode:

| # | Label | Batch | Files / bytes | Modes |
|---|---|---|---|---|
| 1 | `sanjaya-observation-layer-closes-omega-referent-gap` | OB0040 | 19 / 275,452 | early-file, OMQ-14, null type signatures |
| 2 | `book-architecture-amendment-ed2` | OB0042 | 21 / 470,442 | early-file, OMQ-14, null type signatures, template |
| 3 | `godel-numbering-mechanism` | OB0060 | 18 / 529,023 | early-file, OMQ-14, null type signatures, template |
| 4 | `fagin-halpern-moses-vardi-epistemic-logic-lens` | OB0013 | 9 / 186,154 | template, OMQ-14, null type signatures |

**Alternative population (for the human to choose instead):** the three remaining OB0004 labels, which already have page-proven single-agent R2.3 records. Those records are **unaudited** and belong to a FAILED batch; they would be used only as reference data, not reinterpreted. They are cheaper, but weaker as a reference.

### E.2 Arms

- **Arm A0 (reconstruction floor + exhaustive reference):** per label, one fresh single-context agent under production contract revision 3 semantics, in the non-production namespace. It does paged whole-file reading of the full mandatory set, writes a layer-A object plus register, and passes the production verifier via the validation copy (as in the pilot).
- **Arm B2 (improved research-first, §D):**
  - a mechanical channel 2/3 generator script;
  - one R1 agent on the blind packages;
  - a frozen trigger registry;
  - R2/R3 agents with file-local extraction (channel 4) and strict grading.
- **Reference E (independent extraction, for extraction loss):** an independent agent re-extracts findings, using the channel-4 checklist, from a **stratified random sample of files read by A0 or B2**. The sample is 30% of the files, at least 2 per label, seeded. This measures how much each arm left unregistered in files it read.

### E.3 Controls and blinding (improving on Gate C)

- Pre-registration, trigger freeze and read-log checks as in Gate C.
- **Structure-equalized matching lists:** both arms' findings are normalized to one schema (label, statement, evidence quotes with S-id and anchor). Verdicts and arm-specific fields are withheld until matching is committed. This removes the Gate C stage-2 inference channel.
- Materiality is judged blind on the merged, shuffled list (as in Gate C).
- **The auditor is on a different model from the arms; a different vendor if one is available.** Availability is not assumed, and the limitation is recorded.
- Post-unblinding corrections are sensitivity only. Off-scale verdicts are refused mechanically.

### E.4 Cost (estimate)

A0: 4 agents. B2: 1 R1 plus about 2 R2/R3 agents (budget ≤ 800 KB each). E: 1. Auditor: 1. **About 9 contexts**, in the non-production namespace `PX0105`.

## F. Pre-registered metrics (formulas and proposed thresholds; frozen at approval)

**Definitions:**
- **U** = the union of MATERIAL reference findings from A0 and E (deduplicated by the auditor).
- FB = FOUND-BOTH; PM = PARTIAL-MATCH.

| Metric | Formula | Proposed threshold | Question |
|---|---|---|---|
| **DR discovery recall** (B2) | (FB + 0.5·PM) / \|U\| | ≥ 0.80 | does v2 recover the material findings? |
| **CGL candidate-generation loss** (B2) | \|{u ∈ U : information present in B2's R1 input, no B2 candidate}\| / \|U\| | ≤ 0.10 | does channel 2 work? |
| **TL trigger loss** (B2) | \|{u ∈ U : located only in files B2 never triggered}\| / \|U\|; reported also for reconstruction-type u | ≤ 0.15 | does channel 3 work? |
| **XL extraction loss** (per arm) | \|{e ∈ E findings in sampled files the arm read : not registered by the arm}\| / \|E findings in those files\| | XL(B2) ≤ XL(A0); both reported | does channel 4 work, and how complete is exhaustive extraction? |
| **EQ evidence quality** | matched pairs rated A0-stronger / equal / B2-stronger | B2-weaker in ≤ 1/3 of pairs | comparable strength? |
| **RC reading cost** | bytes page-read / VERIFIED material findings, per arm | reported (no threshold) | efficiency |
| **HS hindsight** | untriggered reads + reads before the freeze + post-freeze trigger changes + B2 access to A0/E outputs | **= 0 (hard)** | integrity |
| **RCov reconstruction coverage** (B2) | fraction of A0's layer-A obligations (5 births, absence resolutions, statuses, change-type timeline points) that B2 establishes with page-proven evidence | reported; RCov < 0.80 ⇒ layer 1 remains necessary | can discovery replace the floor? |

**Pre-registered interpretation (proposed):**
- **Two-layer hypothesis supported (on these labels):** DR ≥ 0.80, CGL ≤ 0.10, TL ≤ 0.15, XL(B2) ≤ XL(A0), EQ met and HS = 0. RCov then shows whether layer 1 stays mandatory; the expectation is that it does.
- **v2 insufficient:** DR < 0.50, or CGL > 0.25, or TL > 0.30.
- **Otherwise mixed:** reported per loss type.
- No single metric decides.

## G. OB0018 relationship (kept separate)

| | This pilot | OB0018 |
|---|---|---|
| **Question** | which research strategy discovers findings, and can a discovery layer be combined with the floor? | can the exhaustive reconstruction floor be executed at scale (heavy labels, decomposition fidelity across units)? |
| **Population** | 4 labels of 150–600 KB (single-context floor feasible) | a heavy-label control (OB0018 label, 772 KB, 3 units) |
| **Evidence it provides** | DR, CGL, TL, XL, EQ, RCov | fidelity of decomposition arms A/B/C versus a single-context baseline |
| **Evidence it does not provide** | nothing about executing the floor on the 82 heavy labels | nothing about research-discovery strategy |

**INFERRED:** if the human adopts a two-layer architecture, **both** questions matter. Layer 1 on heavy labels still needs an answer to OB0018. Neither experiment is evidence for the other.

## H. Human decisions required

1. Whether the **two-layer architecture** is to be tested (this pilot) as the working hypothesis.
2. **Pilot population:** the four seeded new labels (§E.1), or the three remaining OB0004 labels as a cheaper, weaker reference.
3. **Thresholds** in §F, as proposed or amended, to be frozen at approval.
4. **Auditor:** a different model (available: claude-fable-5-1) or a different vendor (availability to be confirmed by the human).
5. Whether **Reference E** (independent sampled re-extraction) is included. Without it, extraction loss cannot be measured.
6. Separately: whether and when to run **OB0018**.
7. Still pending from earlier briefs: the binary-file policy, byte-bounded paging, NOT-CONSUMED-ESCALATED.

**State:** design only. No execution; production frozen; no batch authorized; H-19 SEALED; S5c PROHIBITED.

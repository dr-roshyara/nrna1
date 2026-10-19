# M-00 — The reconstruction method in business language (one-page overview)

| Field | Value |
|---|---|
| Date | 2026-10-18 |
| Source | the Principal reviewer's business-language explanation, with Professor's corrections |
| Status | **Overview only.** The method (MRP-1.1) is a **candidate**, not adopted and not validated. Detail and the binding text are in M-01 (protocol), M-02 (R/T layers, controls) and M-03 (tests, completion, decisions) |

## Purpose

Reconstruct the KnowledgeOS theory from its documents without:
- losing ideas;
- confusing old ideas with new ones;
- presenting assumptions as facts.

**About the corpus:**
- It was written in about **two months (Aug–Sep 2026)**, mostly by AI sessions working with the Principal, on top of a month of engineering work.
- It holds **2,779 registered documents**; **2,734** are on the permitted list.
- Sealed and hold-out documents stay excluded.

## The six steps

| # | Step | Business equivalent |
|---|---|---|
| 1 | Establish the authorized reading scope: dates, locations, duplicates | define the project archive |
| 2 | Read each document in full; record what it actually says, with exact passages | requirements linked to evidence |
| 3 | Trace each idea over time: first appearance, extension, narrowing, correction, rejection | decision history, not "the latest document is the truth" |
| 4 | Link concepts, assumptions, claims and mathematics; record what depends on what | a coherent system model |
| 5 | Quality checks: **before** reading the corpus (tests V-1, V-2), then **in every batch** (quotations verified, skipped passages accounted for, disagreements recorded) | QA throughout, not at the end |
| 6 | Evaluate the theory: consistency, proofs, counterexamples. Done **in every batch** as a provisional synthesis, revised when evidence contradicts it | specification vs engineering analysis |

## The core idea: two separate layers

- **R — Reconstruction:** what each document **asserts**, with its own standing (draft, proposal, adopted, superseded). It does not record what someone "believed".
- **T — Evaluation:** what analysis establishes, i.e. whether a claim is well defined, consistent and true.
- A claim can be faithfully recorded and still be wrong. Repeated in many documents, it shows **persistence, not independent confirmation**, because documents written from shared context are rarely independent.

**Labels:** [EV] evidence · [I] interpretation · [H] hypothesis · [?] open question · [NEW] new deduction.

## Controls against mistakes

| Risk | Control |
|---|---|
| An idea is overlooked | every passage accounted for; a reviewer samples the skipped passages |
| Finding only what one expects | read before consulting hypotheses; expectations written down beforehand and scored; search for contrary evidence |
| Repetition mistaken for proof | track which documents derive from which |
| Inaccurate quotation | quotations checked by machine against the source file |
| Disputed meaning | independent second reading; a third party adjudicates |
| Unread files hold new ideas | random probes, reported with their limits |
| My reasoning attributed to the sources | evidence labels; R and T kept apart |

These controls make the work **auditable**. They cannot guarantee that every idea is found or every reading is right.

## Three tests

| Test | What it is | Question it answers |
|---|---|---|
| **V-1** | fictional dated documents with planted ideas, contradictions and a misleading summary, written by someone independent; sealed answer key | do the checks catch predictable mistakes? |
| **V-2** | Professor and an independent reviewer extract the same 7 background files separately; disagreements adjudicated | is interpretation consistent and traceable? |
| **V-3** | small pilot on 5–10 original documents, after access approval | does the method work on the real material? |

**A test fails** if an important item is missed or misread and **no check flagged it**, even when most other checks pass. Passing the pilot justifies a scale-up decision. It does not prove the whole corpus is understood.

## What "complete" means

- **Read files:** "complete" applies only to the documents actually read, with their checks passed.
- **The corpus as a whole:** never called complete. Example: if 200 documents are read and 30 random unread ones show nothing new, that bounds how *common* new ideas are among the rest. It does not exclude a rare idea in a single document, and the probe can also miss things.
- **Reports always state:** what was read, what was not, what the probes found, and what remains open.

## The deliverable

A coherent theory, not 3,000 summaries:
- concepts and exact meanings;
- principles and assumptions;
- mathematical and logical relationships;
- historical development and corrections;
- supporting passages;
- open contradictions and hypotheses;
- a separate statement of which claims have been validated.

## Where things stand (2026-10-18)

- **Tests:** materials for V-1 and V-2 are ready (`05-validation/`). Neither has run.
- **Corpus reading is blocked until the governance log records all of these:**
  1. the D1 filter review and the signed human test;
  2. a written answer to the D2 clarification;
  3. the safety gate recorded as passed;
  4. a written authorization covering this workspace;
  5. the assessment of the earlier filter runs;
  6. a decision on who reviews corpus extractions.
- **Main risk:** the method consuming more time than the research. No further methodology documents are planned; the next work is the two tests and the original documents.

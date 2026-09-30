# Independent review of ANSWER.md / ANSWER.json (ES-004.3 decision-time evidence)

Files used: `REVIEW-TASK.md`, `TASK.md`, `SOURCES.md`, `ANSWER.json`, `ANSWER.md`. Nothing else.
Source labels follow A's: **G** = commit 7632b5685, **S1** = register row R-41, **S2** = session log.

## Summary

- **A's final outputs hold up under my reading.** Ordering is `SECOND-AFTER-DECISION`, decision-time evidence is **1**, and the decision act is the PA instruction. A correctly marks the ordering as INFERENCE, not SOURCE FACT.
- **Evidence scope and quotes.** A used only permitted evidence. Every quoted string I checked is verbatim, allowing for stripped `**` markup and marked ellipses.
- **Where A goes wrong is the reasoning behind the outputs:**
  1. Two of A's six "decisive" quotes rely on narrative order in S2. The sources themselves show that order is **not chronological**. R-41 is listed as "appended" *before* the checklist run, yet R-41's text reports the run's result. A's ellipsis in the S2 Summary quote removes exactly the words ("+ register ruling **R-41**") that show this.
  2. A's arguments for "Not SAME-ACT" and "Not UNDETERMINED" mix up categories. They treat the R-41 append, or the mere existence of rule text, as if it were the decision act. A's own §1 says the decision act is the PA instruction.
  3. The one textual link that actually puts the PA instruction before the checklist, "PA instruction institutionalized", is filed by A under *contrary* quotes.
  4. All three sources come from one author, in one commit, written after the run. A's seven decisive quotes are one narrative, not independent corroboration.
- **Net effect.** The verdict survives, but it rests on a single defeasible reading, not the broad convergence A presents. That reading is that ES-004.3, checklist included, was written *in response to* an earlier PA instruction. A ratification scenario (the PA instruction comes after a drafted rule has already been run) is not ruled out by the text.

## Findings (per REVIEW-TASK items)

### 1. Permitted evidence: PASS
A uses nothing outside `TASK.md` / `SOURCES.md`. Its "+16 lines vs. S2 lines 1-41" reasoning uses only G's diffstat and S2's header. The model name "Claude Fable 5" is quoted from G, not brought in from outside.

### 2. Verbatim quotes and support: PASS on verbatim, PARTIAL on support
- **Verbatim.** Everything I checked is verbatim: S1 heading, S2 Decisions, S2 Summary, S2 Completed bullets, G subject and body, the ADR-T22 annotation, "Co-Authored-By: Claude Fable 5". G's line-wrapped body is re-joined correctly.
- **Constructed "quote".** `S2 Completed order: "ES-004.3 hosted" → "R-41 appended" → "Checklist executed…"` is presented as a decisive quote. The three strings are verbatim, but the arrows are A's own construction, and A itself lists "bullet order is chronological" as an open ambiguity (#4). The quote does not support the ordering claim. Worse, the sources contradict a chronological reading: R-41 is listed before the run, yet its text says "first checklist execution the same day caught a second instance". Either the bullets are not chronological, or R-41 was edited after it was appended. Nothing in the sources supports the second option, and S2 later says "R-41 text … untouched".
- **Elision that hides counter-evidence.** The S2 Summary quote "adopted as **ES-004.3** … First checklist execution … caught a second real instance" drops "+ register ruling **R-41**". In the full sentence, R-41 also comes before the run, so this sentence's order is the same non-chronological narrative order as above. The quote is verbatim, but it does not support "adoption stated first, *then* the checklist run" as evidence of time order.
- **"ES-004.3 annotation" is overstated.** It supports only "the designation ES-004.3 existed when the ADR-T22 annotation was written". It does not show that the *PA instruction* came earlier (see §4).
- **Unlabelled inference.** JSON `performed_by` says: "The hosting, the R-41 append and the commit were carried out by the session author". This is not labelled as inference. A co-author trailer does not establish who performed the hosting. It also contradicts A's ambiguity #6: "Who ran the checklist and wrote the records … is not stated".

### 3. Stated / logical / assumed: PARTIAL
A labels well overall. Corrections:

| Claim | A's label | Correct label |
|---|---|---|
| R-41's text (as written) postdates the checklist run | conditional INFERENCE ("*if* the decision event is the R-41 append …") | **FORMAL CONSEQUENCE, unconditional.** A text that reports a finding cannot be written before the finding. This holds whether or not R-41 is the decision act. The same applies to G's message. |
| The checklist run came after the checklist's text existed | INFERENCE | **LOGICAL.** A checklist defined by the rule cannot be run before its content exists, but that content may have been a draft. A correctly marks adopted-vs-draft as UNK. |
| The PA instruction came before the ES-004.3 text | INFERENCE (§1, "instruction comes first") | **ASSUMED / textual implicature.** It rests on "PA instruction institutionalized" and "the rule generalizes the WP-1 status-line correction". This is the **load-bearing link** for the whole ordering, and A does not identify it as such. |
| Instance 2 was found "in the same commit as the adoption record" | SOURCE FACT | The *annotation* is in the commit. Discovery is not a git event. Stated: the same day ("the same day", S1). |
| "committed by 2026-07-30 19:33:58 +0200" | SOURCE FACT | G's `Date:` field is what the record shows. Whether it is author time or commit time is not stated (minor). |

### 4. Category confusion: PARTIAL
- **The five categories are kept apart in A's §1 / JSON `decision_act`.** The PA instruction is the act; hosting, R-41 and the commit are records of it. I agree, and the text supports it: "ES-004.3 adopted by explicit PA instruction (recorded R-41)"; "the register records the decision event" (S2); "register records the decision" (G body).
- **A breaks this separation later:**
  - **"Not SAME-ACT."** A writes "The sources describe the adoption and the checklist run as separate steps ('R-41 appended' and 'Checklist executed …')". This codes **the register row as the adoption**.
  - **"Not UNDETERMINED".** A writes "Every source … treats it as applying a rule that already exists". This codes **the rule text's existence (hosting) as the decision**. Text existing and the PA instruction are different events, and only the first is established before the run.
  - **Decisive quotes "first application" and "ES-004.3 annotation".** These order the run against the **rule text**, not against the **PA instruction**.
- **The decision act is the PA instruction.** The register row, hosting and commit record or carry out the decision. The first checklist run applies the rule; it does not decide anything.

### 5. Missed quotes bearing on the order
| Quote | Where | Bearing |
|---|---|---|
| "Rule hosted once in ES-004; register records the decision" | G body | Second statement that the register is a *record*, not the act. Supports A's coding of the decision act. |
| "ES-004.3 adopted (PA instruction, R-41); ADR-T22 status annotated per first checklist run" | G subject | Another narrative ordering, from the same author and the same moment. Weak evidence. |
| "…with the ADR-T22 first-execution case recorded as the demonstration…" | S2 refinement | ES-004.3 itself was later revised to include instance 2. Rule text gets post-hoc demonstration material. |
| "this is refinement of the same rule, noted in the provenance line" | S2 refinement | Provenance lines are updated after the event. This supports reading R-41's "Provenance" (which includes instance 2) as a write-up afterwards, not the decision basis. Supports A's count of 1. |
| "R-41 text and session history untouched" | S2 refinement | The R-41 text in S1 is the original text. So the original R-41 already cited instance 2, which confirms that R-41 was written after the run. |
| "ES-004.3's checklist now binds that closure and every closure after it" | S2 Next Steps | "now" places the binding force at the end of the session. The WP-1 run was a first application to an already-closed slice. It does not order the run against the instruction. |
| "ES-004.3 in force; checklist binds every slice closure" | S1 status column | Same as above. Neutral. |

A also **miscodes "PA instruction institutionalized"** as a contrary quote. It is the only direct wording that places the instruction before the rule text, the checklist included, so it is the *key supporting* quote. Read with the Summary sentence, it frames the checklist run as part of the work of institutionalizing the instruction.

### 6. Recomputed ordering and decision-time evidence
See the next section. My outputs match A's: `SECOND-AFTER-DECISION`, evidence **1**. The grounds are narrower and the strength is lower than A presents.

### 7. Strongest alternative: see "Alternatives" below.

## Recomputed ordering (my reading)

| # | Relation | Basis | Status |
|---|---|---|---|
| a | Instance 1 corrected (`307b90e8e`) **<** checklist run | The run's line "Work Plan ✔ (status line fixed `307b90e8e`)" cites the fix | SOURCE FACT + LOGICAL |
| b | Instance 1 known **<** rule formulated | "Provenance: the WP-1 closure inconsistency"; "the rule generalizes the WP-1 status-line correction" | TEXTUAL (strong) |
| c | PA instruction **<** ES-004.3 text, checklist included | "PA instruction institutionalized: … adopted as **ES-004.3**"; "adopted by explicit PA instruction" | **ASSUMED / implicature.** Load-bearing. |
| d | Checklist text exists **<** first checklist run | The checklist is part of the rule's content; the run is called its "first application" | LOGICAL (text may be a draft) |
| e | ES-004.3 designation exists **<** ADR-T22 annotation written | The annotation reads "ES-004.3 annotation" | LOGICAL |
| f | Checklist run **<** R-41 text as written, and **<** G message | Both report the run's finding | FORMAL CONSEQUENCE |
| g | All of the above **≤** 2026-07-30 19:33:58 +0200 | G `Date:` | SOURCE FACT (record time) |
| h | First S2 block **<** refinement and validation sections | Section order; G shows only "+16" for the log | INFERENCE |

- **Instruction vs. run.** Links c and d give PA instruction < run, so the ordering is **`SECOND-AFTER-DECISION`**. This is an INFERENCE that depends on c. Without c, the result is `UNDETERMINED`, because no stated or formal link orders the PA instruction against the run.
- **Weak evidence.** The narrative order in S2 Summary, S2 Completed and G subject is not evidence of time order: link f shows the narrative puts R-41 before the run, which is impossible chronologically.
- **Decision-time evidence: 1.** This follows formally from c + d plus "caught" (first discovery). If c is rejected, the count is UNK. If the register row is miscoded as the decision act, the count is 2, via link f.
- **What git can and cannot order.** I agree with A's lists, with two additions:
  - Git cannot even order the R-41 append against the ADR-T22 edit. The order is fixed only by the *logical* link f.
  - The `Date:` line is the only timestamp in the sources. Every other temporal claim is day-level ("2026-07-30", "the same day") or narrative.

## Alternatives

| Alternative | Reading | Ruled out by text? |
|---|---|---|
| **Ratification.** The PA's instruction already cited both instances: the rule and checklist were drafted and run first, and the PA then instructed adoption. | Run < instruction, so `SECOND-BEFORE-DECISION` and evidence 2. | **Not ruled out.** Disfavoured by: "PA instruction institutionalized" (the instruction's *content*, "Artifact Lifecycle Consistency", is what got institutionalized as ES-004.3); S2 Decisions naming only instance 1 as what the rule generalizes; "first application"; the run listed among the work that institutionalized the instruction. All of this comes from one author writing after the run, and no timestamp separates the events. This is the strongest alternative. |
| **Register row / commit as decision act.** | Row and commit postdate the run (link f), so `SECOND-BEFORE-DECISION` and evidence 2. | **Ruled out as coding.** The text states that the register *records* the decision: "the register records the decision event"; "register records the decision". |
| **SAME-ACT.** | Decision and run are one indivisible act. | **Ruled out if the decision act is the PA instruction.** The instruction and a checklist run are different kinds of act, described in separate statements. Only the commit bundles them, and the commit is a record, not the act. |
| **Run from a draft before "adoption".** | The run precedes a formal adoption. | **Irrelevant if adoption = the PA instruction and c holds.** It matters only if "adoption" is a later act separate from the instruction, and the text names no such act. |
| **UNDETERMINED.** | Refuse implicature c. | **Defensible under a strict "stated or formal only" standard.** The text does not formally exclude it. I reject it only because TASK allows answers "from the text", and c is a direct reading of the text, not a guess. The dependence on c must be stated. |

## Disagreement register

| ID | Topic | A | Reviewer | Class | Effect on outputs |
|---|---|---|---|---|---|
| D1 | S2 Completed bullet order as decisive | Decisive | Not chronological: R-41 is listed before the run but reports it. It also contradicts A's ambiguity #4. | formal reasoning | None on verdict; lowers support |
| D2 | S2 Summary quote with ellipsis | "adoption stated first, then the checklist run" | The ellipsis removes "+ register ruling R-41", which shows the same non-chronological order | source interpretation | Lowers support |
| D3 | "Not SAME-ACT" / "Not UNDETERMINED" / "first application" / "ES-004.3 annotation" | Adoption = R-41 append / rule exists | These order against the register row or rule text, not the PA instruction | coding | Lowers support; shifts weight to link c |
| D4 | "PA instruction institutionalized" | Contrary quote | Key supporting quote; the only instruction-before-text link | coding | Relocates the real basis |
| D5 | R-41 text postdates the run | Conditional on the decision-act reading | Unconditional formal consequence | formal reasoning | None |
| D6 | Corroboration across G/S1/S2 | Treated as convergent | One author, one commit, written after the run; not independent | independence | Lowers confidence |
| D7 | `performed_by` (JSON) | Session author did the hosting and R-41 append (unlabelled) | UNK; contradicts A's ambiguity #6 | coding | None on verdict |
| D8 | Missed quotes (G "register records the decision", "noted in the provenance line", "R-41 text … untouched", "recorded as the demonstration", "now binds", S1 status column) | Not used | Relevant; mostly support A's count of 1 and the record-vs-act coding | evidence scope | Strengthens the count; no change |
| D9 | "Not UNDETERMINED because no source puts the run before the instruction" | Sufficient | Argument from silence; the ratification alternative is not excluded | formal reasoning | Verdict holds only as an inference dependent on c |
| D10 | "found … in the same commit"; "committed by" the `Date:` | SOURCE FACT | Discovery is not a git event; the `Date:` semantics are not stated | wording | None |
| — | Final ordering / count / decision act | SECOND-AFTER / 1 / PA instruction | Same | — | **No substantive disagreement** |

## What can and cannot be established

**Can be established:**
- *Stated.* The decision act is the PA instruction. R-41, the ES-004.3 hosting and the commit record or carry it out.
- *Stated.* Everything happened on 2026-07-30 and was committed together, with the only timestamp at 19:33:58 +0200.
- *Stated + textual.* Instance 1 was corrected before the checklist run and is the named basis of the rule.
- *Formal.* R-41's text and G's message were written after instance 2 was found.
- *Logical.* The checklist run happened after the checklist's text existed, possibly as a draft.

**Established only by inference (defeasible):**
- The PA instruction came before the checklist text, so the run came after the decision (`SECOND-AFTER-DECISION`) and 1 instance was known at the decision act.

**Cannot be established:**
- A clock time for the PA instruction, for the discovery of instance 1, or for the checklist run.
- Whether the PA had seen a draft or the run's result before instructing (the ratification alternative).
- Who performed the hosting, the run and the recording.
- The order of events inside commit 7632b5685, except where link f fixes it.

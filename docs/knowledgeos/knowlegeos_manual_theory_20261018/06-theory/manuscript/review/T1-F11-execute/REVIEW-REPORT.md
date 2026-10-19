# T1 Step 2: review of §3.9 "What executes" (fork F11)

Independence here is process independence, not personal independence. I am the same kind of model as the authors of the corpus.

Time spent on step 2: about 25–35 minutes. No clock was kept, so this is an estimate.

What I read: REVIEWER-INSTRUCTIONS.md (from Step 2 onward), 03.09-what-executes.md and THEORY-MAP.md. I re-opened none of the five sources; the quote checks below use what I read in step 1. My step-1 per-source reading serves as the inventory, as you instructed. I wrote no files.

## 1. Assessment
- The section's core distinctions hold within their scope: "executable" is not "executed", and running a check is not enforcing it.
- The fork resolution does not hold as stated. Claim C1 says statements A and C are compatible; claim C2 makes A false while C holds. C5 also turns the source's two meanings of "execute" into "the platform does not execute".
- **Largest risk:** the section never answers "what counts as evidence that something was executed". The sources give four rival criteria, and the section takes up only S1524's.

## 2. Findings (ranked)

| Sev | Type | Claim / topic | Source line | What is wrong or missing | Consequence | Correction |
|---|---|---|---|---|---|---|
| **critical** | defect | C1 against C2: "A and C are compatible" | §3.9 C1 bullet 4; C2 "So, for X1" | C2 says C holds (adapters run and are artifacts) and A is a category slip. Then "whatever executes is a capability" is false of the very adapters that C says execute. So A also conflicts with C under X1, not only with B. Separately, C5 makes A false under X2. A is therefore rejected in both senses. It is not dissolved by word collision. | The F11 row in the map ("A and C are compatible …") and "largely a collision of words" rest on an inconsistency. "Largely" is not quantified. | Restate: once the senses are separated, B and C hold under X1. A is rejected under X1 and X2, given the book's relation runs(r, a). Drop "compatible". Report which part is collision and which part is rejection. |
| high | defect | C5: "the platform does not execute … platform capabilities do not execute at all" | S1052 L490, L1023, L1035–1058, L1218 | S1052 puts an **Execution Service** and an **Action** module inside the platform. It has the platform decide "whether A42 has already executed" (L1046), with retry semantics for ExecuteAction (L1023). The source uses two senses: the platform **dispatches** an action, and the external system **performs** it. The section keeps only the second. S1524 L839, "Centralize authority, **not necessarily** execution", allows central execution; it does not deny it. | X2 merges dispatch and performance. Who owns execution (an open question) is pre-judged. | Split X2 into dispatch (platform → port → adapter) and performance (the external system). Grade "the platform does not execute" as Int, not Ded. |
| high | defect | C5: "Authorization is the act … matches A1" | S1052 L405–430 | In S1052, authorization is a **service** that evaluates Principal, Policy, Scope, Context and Conditions and returns ALLOW or DENY. Under A1 and ARCH-INV-1 ("mechanisms record, never grant"), a machine ALLOW is a mechanism, not a recorded human act. | It contradicts the book's own authority axiom. | Read runtime authorization as a mechanism applying a prior human grant (the adopted policy). The act is the adoption of the grant. Raise this as a new fork (§4). |
| high | defect | X2 definition, "produces a change in an external system" | S1052 L977, L1655 | S1052's first execution is deliberately **read-only** ("Read Nexus configuration", LOW risk). That is a counterexample inside the definition's scope. For a read, the execution and the observation (L532–546) are the same act at the adapter. | X2 is too narrow. The difference between execution and evidence disappears exactly where the source starts. | Define X2 as an authorized interaction with an external system through a port, whether it mutates or not. Mark the read/observe overlap as Open. |
| high | omission | What counts as evidence of execution | S0043 L63–64; S0889 L719, L751; S1052 L510–526, L1046; S1524 L863 | Four criteria are in the sources: (a) a decision changed (S0043, n=1); (b) replay equality and determinism, required but not observed (S0889); (c) an ExecutionFact plus an Execution record keyed by ActionID, with evidence captured **separately** through observation (S1052); (d) machine-produced output (S1524). The section uses only (d). The relation between an ExecutionFact and evidence is left open in S1052 L526 and in the section. | The topic's fourth question goes unanswered. | Add a claim that compares (a)–(d) and grades each one. |
| high | defect | C6: "Only X4 gives observed evidence" | S0043 L48, L63; S1052 L510 | "Only" is too strong. An observed block (deny), an observed changed decision, and an observed external effect are also observations. | The evidence grades are distorted. | Replace with "only an observed run or an observed effect, never a specification". |
| high | defect | C6 uses S1524's counts as observation ("0 / 22 executed") | S1524 L28–31, L891, L893 | S1524 shows no grep or md5 transcript. By its own L863 criterion, its counts are reports, not machine evidence that appears in the document. | X4 is attributed to a report that does not meet X4 itself. | Grade S1524's counts as Src (reported). |
| high | omission | C6's strongest instance is missing | S1524 L895–900 | The clearest case of X3 being reported as X4 is the fabricated, mutually inconsistent result blocks in S153/154: "47 PASS", "42 PASS", "7/128/19/42". | C6 rests on weaker cases. | Cite them, as S1524's report. |
| high | defect | C6 cites S1052 L1628 as reporting results as if run | S1052 L1628–1657 | S1052 announces a specification ("define the actual first implementation slice") and reports no run of its own. | A misattributed instance. | Keep S0889 L1080 as the instance; S1052 only uses "executable" in the X3 sense. |
| high | defect | C4 (Ded): "running a governed rule produces evidence (a report), not enforcement" | S0043 L48 | A `deny` entry is a rule that the runtime runs and that blocks. The general sentence is false. Only non-sufficiency holds. | CON-0092 overstated. | "Running a rule is not sufficient for enforcement; enforcement = running + a stop over the action path." |
| high | defect | C4: S1052 places enforcement "not in the agent environment"; and "S1052 states the same" | S1052 L1419, L466 | S1052 keeps local enforcement: "Hooks should enforce local properties" (L1419). RT-001 denies *authority*, not enforcement. Also, an AGENTS.md instruction is not run by any runtime (it is read and interpreted by an agent), so Instruction ≠ Enforcement is a different case from an advisory check that runs. | The placement claim and the "same" claim are both distorted. | Separate the **enforcement point** from the **authority boundary**. Treat instruction (not run) and advisory check (run, no stop) as two cases. |
| high | omission | Authorization as a swappable capability vs as a platform boundary | S0166 L31, L541, L1192; S1052 L452, L1145 | S0166 lists "authorization systems" and "policy engines" as replaceable by configuration. Its third voice puts enforcement in the composition root. S1052 makes authorization an enforced platform boundary. This affects the four capability referents directly. | The capability referents overlap; the four-referent picture is incomplete. | Add this as an Open item under the referents. Note S0166 Q4 (L1015–1038): technical changes and governance changes take separate approval paths. |
| normal | defect | Map line 142 and C4 count `ask` (22) as "enforced" | S0043 L49 | A trigger interrupts and hands the decision to a person; it does not block. The "41 enforced" figure merges blocking with interrupting. The counts appear in S0043 with no listing. | The comparison with Ch. 0 §0.2 and Ch. 1 §1.9 inherits the merge. | Report deny 19 (blocking) and ask 22 (interrupting) separately, both as S0043's assertion. |
| normal | defect | C3 relies on S1524's criterion "cannot fail → not invariants" | S1524 L194 | Once types are implemented, a type distinction becomes checkable: no code path may use a Recommendation record as an Authorization. The criterion holds for model statements only. | C3's "principled reason" is weaker than presented. | Scope it: "not a runtime invariant until the type is realized". The proposed testable replacement for I-11 stands. |
| normal | defect | CON-0090: the four senses are not on one level | §3.9 table | X1 and X2 are activities. X3 (could run) and X4 (did run, with machine output) are modal or evidential statuses of an activity. | The senses are not orthogonal. | Present them as {run, act} × {specified, performed-and-evidenced}. |
| normal | omission | Execution identity | S1052 L1056–1058 | "Same ActionID ⇏ second physical execution" defines what counts as **one** execution. | Missing from X2. | Add it to X2. |
| normal | source defect | S0889 indexing | S0889 L102–112 against L254–266, L381, L454, L713; `D` used for two things (L69, L856); Uncertain vs Unknown (L548, L625) | Snapshot and event indices do not match, and K4 drops the evidence. This matters only because C6 says the model is "consistent on the worked cases". | C6's wording overstates. | Say "constructible in outline; the worked cases contain indexing slips". |

## 3. Concept verdicts

| Concept | Verdict | Reason · scope |
|---|---|---|
| **CON-0090** four senses of "execute" | **PROVISIONAL** | The senses are real and quoted correctly. But the set is not exhaustive (dispatch vs performance; S0889's in-model "We never execute K1.modify", L115), X2 is defeated as worded by read-only execution, and X3/X4 are modalities, not senses. Scope: the five sources. |
| **CON-0091** runtime runs artifact; neither knowledge nor capability executes | **PROVISIONAL** | Coherent as a stipulation of a transitive relation. It is not derived from the sources, which use "execute" intransitively with artifacts and actions as subject (S1052 L1046; the checker's `execute(input)`, S1524 L496). Its premise that a capability is realized by running artifacts and does not itself run is asserted. It depends on knowledge ≠ artifact (§3.4), and the map marks CON-0010 ✗ (only "distinct" survives). Scope: X1. |
| **CON-0092** running a rule is not enforcing it | **VALIDATED** (as non-sufficiency only) | Running ⇏ enforcing is supported by S0043's advisory column and S1052 L452/L466. The general Ded sentence ("produces evidence, not enforcement") is **invalid** against deny rules and must be corrected. Scope: S0043 on 2026-08-02, n = 1 repository, counts as asserted. |
| **CON-0093** executable ≠ executed | **VALIDATED** (the distinction and the grading rule) | S0889 L1080 against L1128, and S1524 L891, L394. Scope: the distinction itself. "Only X4 gives observed evidence" and the S1052 instance are defects (§2). |
| **Fork resolution C1–C2** (F11 largely a collision; A a category slip under X1) | **INVALID as stated** | C1's "A and C compatible" is contradicted by C2 and C5. The defensible version, "the senses separate; B and C hold under X1; A is rejected in X1 and X2 under runs(r, a)", is **PROVISIONAL**, because it inherits CON-0091's stipulated premise. |

## 4. Coverage against my inventory (ranked by theoretical importance)

| # | Inventory point | Status |
|---|---|---|
| 1 | Four rival criteria for evidence of execution (S0043, S0889, S1052, S1524) | **MISSING** |
| 2 | Platform's Execution Service (dispatch) vs external system (performance) | **DISTORTED** (C5) |
| 3 | Read-only execution; overlap of execution and observation; ExecutionFact vs evidence (S1052 L510–546) | **MISSING** |
| 4 | Automated gates (S1052 Authorization Service; S0889 `Commit(A,K,EC)`) vs the human-act axiom | **MISSING**, and a new fork |
| 5 | Authorization as a swappable capability (S0166) vs an enforced boundary (S1052); S0166 merging composition with enforcement (L1192) | **MISSING** |
| 6 | Enforcement point vs authority boundary (S0043 deny in Zone A vs RT-001; hooks L1419) | **DISTORTED** (C4) |
| 7 | Executable ≠ executed | **COVERED** (instances partly mis-chosen) |
| 8 | Composition is not execution and not authority (S0166 L655, L797; S1052 L249) | **COVERED** |
| 9 | Four capability referents | **COVERED** (overlap in #5 not marked) |
| 10 | S0166 runtime/root self-reversal | **COVERED** (map) |
| 11 | S1524 does not meet its own execution criterion | **MISSING** |
| 12 | Fabricated result blocks in S153/154 | **MISSING** |
| 13 | "ask = enforced" stretch; S0043 counts unshown | **MISSING** (map adopts "41 enforced") |
| 14 | ActionID execution identity and idempotency | **MISSING** |
| 15 | S0889 commitment boundary, AI as transformation, Known → Unknown, Retraction(E) ⇏ Retraction(A) | **COVERED** in the map's new-topics block (Known → Unknown); commitment boundary **MISSING** |
| 16 | S1524 type-distinctness criterion as itself contestable | **MISSING** (C3 relies on it) |
| 17 | Deontic "Required", provenance ≠ lineage, four authorities, export-order timestamps | **COVERED** (map) |
| 18 | S0043 L112 "nothing in this document executes" as operative effect rather than a run | **DISTORTED** (minor; filed under X1) |
| 19 | RT-004 "high-risk" undefined; fail-closed policy | **JUSTIFIABLY EXCLUDED** (off-topic) |
| 20 | S1052 "We already have" inventory unverified | **COVERED** in effect (C4: "a design, not an observation, unbuilt") |

**New topics and forks the map lacks:**
- **Can a mechanism change standing?** The corpus has automated gates that change state or permission: S1052's ALLOW, and S0889's commit under an epistemic contract. Do these conflict with A1, or apply grants? This bears on F9 and P-E.
- **Where does an ExecutionFact sit in the evidence grades?** S1052 L840–850 makes the external system authoritative for its own state. That bears on F2 (external authority), and the section does not connect them.

**Source defects, listed separately:**
- S0889 indexing and symbol reuse (as in §2).
- S0166's three unattributed voices that contradict each other.
- S1524's broken table row (L875–877).

## 5. Next steps for the theory (in dependency order)
1. Fix C1/C2. Done when §3.9 and map row F11 state "rejected", not "compatible", and quantify "largely".
2. Split X2 into dispatch and performance, and allow read-only execution. Done when C5's claim is re-graded and the read/observe overlap is recorded as Open.
3. Derive an evidence-of-execution claim that compares criteria (a)–(d) and grades each. Done when every criterion has a grade and a scope.
4. Restate CON-0092's Ded line as non-sufficiency, and separate enforcement point from authority boundary. Done when deny and ask are reported separately.
5. Correct C6: replace "only X4"; replace the S1052 instance with S1524 L895–900; grade S1524's counts as Src.
6. Open the "mechanism vs act" fork (S1052 authorization, S0889 commit) against A1 and ARCH-INV-1.
7. Re-check CON-0091 against §3.4 and CON-0010. Done when it is shown that only "distinct" is needed.
8. Question for the Principal: none is needed to proceed.

## 6. Limits
- I did not open the concept register, §3.8 C5, §3.4, Ch. 1 §1.5/§1.9/§1.10/§1.14.3 or §6.1 C5, as the grant excludes them. I therefore could not check how A, B and C are worded in §3.8, nor whether A meant the "governed" capability (C1 assigns it that referent without argument). I also could not check whether CON-0010 ✗ undermines C2's premise.
- I could not verify S1524's claims about other steps, or S0043's counts.
- I quote-checked every source line cited in §3.9 against my step-1 reading, and all are accurate.
- I ran no computation. The claims are definitional, so no count, simulation or similarity check would decide anything here.

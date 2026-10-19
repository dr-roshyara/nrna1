## Trial T1, Steps 2–3: review of §3.9 "What executes" (F11)

Independence is process independence, not personal independence: I am the same kind of model that likely wrote parts of this corpus. **Time spent:** about 30 minutes for Steps 2–3. That is an estimate; I did not time it.

**Reads.** My frozen inventory `STEP1-RANKED-INVENTORY.md`, then `03.09-what-executes.md` and `THEORY-MAP.md`. I re-opened none of the five sources; every claim was checked against what I had already read of them in full in this session.

### 1. Assessment
1. §3.9 is a useful map of the senses of "execute". CON-0092 and CON-0093 hold if read as "running ⇏ enforcing" and "executable ⇏ executed".
2. The fork resolution does not hold as stated. C2's "neither knowledge nor a capability executes" is contradicted by two of the section's own four capability referents. C5's "the platform does not execute" is contradicted by S1052.
3. **Largest risk:** "A is a category slip" is a definitional choice presented as a deduction. If accepted, it would close F11 by assuming its own answer.

### 2. Findings (ranked)
| # | Sev. | Type | Claim / topic | Source line | What is wrong or missing | Consequence | Correction |
|---|---|---|---|---|---|---|---|
| 1 | **critical** | defect | C2 / CON-0091: "a capability … is not itself what runs" | §3.9 table, referents 2–3; S0166 L23–32; S1052 L154–167, L542 | The section's own referents 2 and 3 are services and providers: AI providers, vector databases, policy engines, the "Evidence Service". These are runtimes, i.e. the *r* in runs(*r*, *a*). So in the run sense, capabilities do execute. | The fork resolution and the map's F11 row (L127) rest on a claim refuted inside §3.9. | Restrict to referent 1: a *governed* capability is not an artifact. State this as a stipulation. |
| 2 | **critical** | defect | C2: "A is a category slip" | §3.9 C2; S0043 L50–52 | The verdict assumes capability ≠ artifact, which is exactly what A denies. That is question-begging. Corpus usage also supports A's reading: S0043 places "every engineering capability" in the column made up of hooks and scripts. | It turns a definitional choice into a refutation. | Restate as "this book adopts the convention …; under it A is false". Record the corpus usage as counterevidence. |
| 3 | high | defect | C5: "the platform does not execute … they authorize and record" | S1052 L490, L925, L1043–1046, L1167, L1218, L1240–1249, L860/L870 | The Execution Service, the Action module and the adapters are all inside KnowledgeOS ("The first deployment does not require each adapter to be its own service", L1249). The platform runs `Execute(A42)` and checks "whether A42 has already executed". The control/data split is "Primarily", and §3.9 drops that hedge. The port and adapter are not "in the external system". | "A is false for X2" is unsupported. The link to Ch. 1 authority ("authorization is the act; execution is its effect") is overstated. | Split X2 (see #4). Withdraw "the platform does not execute". |
| 4 | high | defect | CON-0090 X2 = "an authorized action produces a change in an external system" | S1052 L80 vs L490; S1052 L977, L1655; S1524 L623 (2nd-hand) | X2 merges two meanings: dispatch (the platform's service and adapter) and performance (the external system). S1524 reports a third layer, the technical command. The "change" clause also excludes S1052's own first execution, which is read-only. | The typology is not a partition of the source usage. C5 inherits the merge. | Use X2a dispatch, X2b perform, and possibly X2c technical command. Drop "change", or add read-only actions. |
| 5 | high | defect | C3: "a general criterion shows why" I-11 is unfalsifiable | S1524 L194 vs L446; S1052 L892–904 | S1524's "they cannot fail" is too strong. A type distinction can be violated as a *conflation*, e.g. a system that accepts a recommendation as an authorization. S1524 itself rates "C-018 Agent cannot self-authorize" behaviourally testable while rating "C-017 Recommendation is not authority" not realized. Checkability depends on phrasing, not on content. | The reason C3 gives for "I-11 holds by stipulation" is weak. The testable replacement C3 proposes is the right move. | Present C3 as "I-11 as phrased is a classification; its violable form is …", without borrowing S1524's criterion. |
| 6 | high | omission | What counts as evidence that something ran | S1052 L506–546, L1046; S0889 L719, L751, L303; S0043 L63 | §3.9.1 promises this as a residual question, but §3.9.4 does not list it. Missing: S1052's ExecutionFact and ActionID-keyed record (and whether that fact is evidence), and S0889's replay/determinism/idempotency criteria. | The fork's stated remainder is lost. | Add a table of each sense against its evidence criterion, and add the question to §3.9.4. |
| 7 | normal | defect | CON-0092 Ded: "running a governed rule produces evidence, not enforcement" | S0043 L48; S1052 L1419 | This is too general. `deny` entries are rules, and the runtime that runs them blocks. Hooks can enforce local properties. The next sentence in §3.9 ("needs a mechanism that can stop the action") is the correct condition. | Overclaim, within the concept. | "Running is not sufficient for enforcing." |
| 8 | normal | defect | C4: "S1052 proposes the opposite placement" | S1052 L1419–1440 | S1052 adds to rather than replaces: "LocalFastCheck + GovernedAssurance". | Overstated contrast. | Say "adds server-side enforcement for governed actions". |
| 9 | normal | omission | Enforcement point ≠ authority boundary | S0043 L48 vs S1052 L1133 | S0043's only enforced boundaries sit in the zone S1052 calls "not an authority boundary". This is a distinction, not merely a placement dispute. | A missing concept. | Record it as a new distinction. |
| 10 | normal | omission | Authorization as a swappable capability vs an enforced boundary | S0166 L31, L541 vs S1052 L452, L1145 | Not addressed. | Bears on referents 2 and 3, and on A1. | Add to §3.9.4. |
| 11 | normal | omission | The commitment gate as an epistemic counterpart of authorization | S0889 L210, L257–275, L795 | Omitted. The topic asks how executing and authorizing relate. | A missing relation. | Cite it in C5 or in Ch. 4. |
| 12 | normal | defect | S0043 L112 cited as X1 evidence | S0043 L112 | "Nothing in this document executes" means the document has no operative (normative) effect. It is not about a runtime. | A minor sense misassignment. | Move it, or note it as a fifth sense. |
| 13 | normal | defect | S0889's sense recast as X3 | S0889 L9, L43 | The source's sense is that the knowledge state evolves by transitions. That bears on I-11, and C2's second attempt only partly addresses it. | A distortion. | Name the transition sense. Test I-11 against it. |
| 14 | normal | omission | C7 scope "one rule, one check" | S0043 L63 | S0043 names three further checks (knowledge-lint, link-check, doc-placement). n = 1 applies to *decisions changed*. | Scope understated. | Correct the scope line. |
| 15 | normal | defect | Map L142: "41 runtime controls (deny 19, ask 22) are enforced" | S0043 L49 | `ask` hands the decision to a person; it does not block. The counts are unlisted in the source. | The map adopts the source's merge. | "19 block, 22 interrupt; counts as asserted by the source." |

**Source defects (counted only where the theory relies on them):**
- **S0889 indexing.** K3–K5 at L102–112 do not match L266, L381 and L454. `Uncertain` (L548) and `Unknown` (L625) are used for the same case. C6 says an X3 result is "consistent on the worked cases", and these slips falsify that for S0889, so this one counts.
- **S1524's own counts.** The md5 hashes and grep counts (L28–31, L893) come with no transcript. §3.9 uses "0/22 executed" as its X4 exemplar, so it should be graded as reported by the source.
- **S0166 L655 reversed in its own file** (L1184). It is already noted in the map, but §3.9 cites L655 without the caveat.

### 3. Concept verdicts
| Concept | Verdict | Reason · scope |
|---|---|---|
| CON-0090, four senses of "execute" | **PROVISIONAL** | A useful typology, but not exhaustive: it lacks the operative-effect sense (#12) and the transition sense (#13). X2 merges dispatch and performance, and its "change" clause excludes the sources' own read-only execution (#4). Scope: the five sources. |
| CON-0091, runs(*r*, *a*); neither knowledge nor capability executes | **INVALID** as stated | Refuted by §3.9's own referents 2 and 3 (#1). The relation runs/processes(*r*, *a*) alone is PROVISIONAL: the argument type is open, as §3.9 itself says. The restricted form "a governed capability is not an artifact" is a PROVISIONAL stipulation. |
| CON-0092, running ≠ enforcing | **VALIDATED** in this form | Valid as the non-implication "running ⇏ enforcing": S0043's advisory checks run and do not block (L50). Scope: one repository on 2026-08-02, counts as asserted. INVALID if read as "running a rule never enforces" (#7). |
| CON-0093, executable ≠ executed | **VALIDATED** in this form | Valid as "executable ⇏ executed" plus the rule to grade X3 as derivation. Demonstrated by S0889 L1080 vs L1128, and by S1052 L1628 vs S1524 L394. Not supported: "X3 shows constructibility / consistency on the worked cases" for S0889. |
| C1, F11 is "largely a word collision" | **PROVISIONAL** | Plausible: the senses do diverge. But the residue is larger than stated: it includes A vs B, X2's owner, and evidence criteria. I cannot check the wording of A, B and C (§3.8 C5, S0029). |
| C2, "in the run sense, A is a category slip" | **INVALID** as a deduction | It begs the question, and corpus usage supports A (#2). It survives only as a declared convention. |

### 4. Coverage (by my inventory, ranked by importance)
| Rank | Inventory item | Mark |
|---|---|---|
| 1 | S1052 #17: two senses of "execute", dispatch and perform | **CONTRADICTED** (C5) |
| 2 | Capability as a running service (S0166 #3, S1052 #9) | **CONTRADICTED** by C2 |
| 3 | S0043 #4: capabilities located among the hooks and scripts | **MISSING** as counterevidence to C2 |
| 4 | Evidence-of-execution criteria (cross-source table) | X3/X4 **COVERED**; S1052 and S0889 criteria **MISSING** |
| 5 | S1524 #12: Action / Execution / technical command layers | **MISSING** |
| 6 | S1052 #18: read-only execution coincides with observation | **MISSING** (counterexample to X2) |
| 7 | S0889 #1: the transition sense | **DISTORTED** |
| 8 | S1052 #1–#2: Instruction ≠ Enforcement; the gate in the platform | **COVERED** |
| 9 | S0166 #1–#2: runtime ≠ composition root; DI ≠ authority | **COVERED** |
| 10 | S0043 #3, #6: three-term split; self-correction | **COVERED**; #14 (`ask` ≠ block) **DISTORTED** in map L142 |
| 11 | S0889 #16 / S1524 #1: nothing run; 0/22 | **COVERED**; S1524 #19 (its own counts unshown) **MISSING** |
| 12 | S1052 #20: local enforcement plus server-side | **DISTORTED** ("opposite") |
| 13 | S1524 #7–#8, #11: intent/fact; DM-003/004; Capability ≠ Authority | **COVERED** |
| 14 | S0166 #11: authorization as a swappable capability | **MISSING** |
| 15 | S0889 #3–#5: commitment gate; LLM as transformation | **MISSING** |
| 16 | S0889 #6–#7: retraction; Known → Unknown | **COVERED** (map new topics) |
| 17 | S1524 #9–#10: CanExecute, AuthorizedCapability, MayPerform (ill-typed) | **MISSING** |
| 18 | S1052 #5, #11, #19: ExecutionFact; ActionID identity | **MISSING** |
| 19 | S1524 #14: a shape check cannot decide path-universal bypass | **MISSING** |
| 20 | S0166 #6–#7: technical vs governance change path; Execution → Evidence Qualification | **MISSING** |
| 21 | S1052 #8: control/data plane | **COVERED**, "primarily" dropped |
| 22 | S1524 #17: Required; Knowledge ⇏ Action | **COVERED** (map) |
| 23 | S1052 #12, #22: risk-dependent fail-closed; undefined "high-risk" | **OPEN** (not in §3.9) |
| 24 | S0166 #4: capability binding · S0889 #12: I1–I10 collision | **COVERED** (map) |
| 25 | S1524 #18 verdict drift · S0043 #9 ladder · S0889 #14 kernel | **JUSTIFIABLY EXCLUDED** (off-topic) |

**New topics and forks the map lacks:**
- F11-b: in the act sense, who executes: the platform (dispatch) or the external system (perform)?
- Enforcement point ≠ authority boundary.
- Reconciling the four evidence-of-execution criteria.
- Is a read-only action an "execution"?

### 5. Next steps for the theory (dependency order)
1. **Split X2** into dispatch, perform and (possibly) technical command. **Done when** every S1052 use of "execute" (L80, L399, L490, L874, L925, L1043–1046, L1167, L1655) maps to exactly one sense.
2. **Rewrite C5 on the split** and withdraw "the platform does not execute". **Done when** it is consistent with S1052 L1218 and L1240–1249.
3. **Restrict C2 to the governed capability** and restate the A-verdict as a convention. **Done when** the claim survives the referent-2 counterexample.
4. **Re-argue C3 without S1524's "cannot fail"**, and state I-11's violable form. **Done when** it answers the "recommendation accepted as authorization" counterexample.
5. **Add the evidence-of-execution table** and the open question it raises. **Done when** each sense has a criterion with its source line.
6. **Correct #7, #8, #12, #14 and #15** and the map rows L127 and L142.

No question for the Principal is needed.

### 6. Limits
- **Files not opened, per the grant:** §3.4, §3.8 C5, Ch. 1 (§1.5, §1.9, §1.10, §1.14.3), §6.1, the concept register and S0029. I therefore could not check:
  - the wording of A, B and C;
  - the "realization order";
  - the text of I-11, CON-0039 and CON-0066;
  - axiom A1, i.e. whether C5's link to it is apt.
- **Second-hand items:** everything marked (2nd-hand) from S1524 about Steps 141–158 is unverified.
- **Computation:** the only computation was a trivial consistency check of S0043's counts (19 + 22 = 41; 10 + 6 = 16), which agree. No similarity analysis or dependency graph was run, because the corpus was too small to need them.
- **Re-reading:** I did not re-open the sources in Step 2. Line checks rest on my full reading in this session.

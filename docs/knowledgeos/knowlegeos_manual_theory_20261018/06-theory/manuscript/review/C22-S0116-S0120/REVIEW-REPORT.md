*Window reviewer W (Opus), steps 2-3 review report, returned as hand-back text, verbatim (saved by the Professor at 2026-10-21T07:08:45Z). sha256 of the hand-back text at receipt: `e2e24c579b7e7569fbca9039d2e4ece11abe31b881fbee58ca09fed0de3da1cd` (UTF-8, the text below this line exactly).*

C22 STEP 2-3: REVIEW REPORT

**Independence:** this is process independence, not personal independence. I worked from my saved inventory; the body below its 2-line header hashes to f582db…, so it matches. I edited nothing.

**Checks I ran:**
- I re-ran `ce_model.py` v15 myself (`/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/c22w_run.txt`). All assertions pass, and apart from its random-record lines the output is identical to the saved v15 run.
- A probe, `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/c22w_probe.py`, supports W-4 and W-11.

## 1. Assessment
- §8.15 represents the sources faithfully, line by line. But three of its interpretations go beyond the sources or contradict the book: C5's "Mathematically" step, C8's attribution of the two-limb test, and C9's link to CE*.
- The largest risk is CON-0218. It carries a non-sequitur ("permanently unsatisfiable") that the source's own level L3 refutes, and a "discriminator" that the source's own worked case refutes.
- In the changed theory, R4c's coordination result holds only if two of the Professor's technical choices hold, and its "only two differences" is false. v14 silently changes what a consumption can do.

## 2. Findings (ranked)

| ID | Sev. | Type | Claim · line | What is wrong | Correction | Resolved when |
|---|---|---|---|---|---|---|
| W-1 | **critical** (R5, F1 and F4) | defect | C5 Int "Mathematically"; CON-0218 (basis "Ded"); S0117 L213, L225 | "A history conjunct is false at every instant" holds only if the test is applied to evidence from a single moment. That premise is missing. Evaluated on the record up to time t, E10 becomes true once a history exists. The source's own L3 (L236) contains E1–E10, and the book says each level "can hold at some time". That contradicts "permanently". | State the premise. Restate: such a conjunction is unsatisfiable *at the first realization*, not permanently. Option A ≈ L3. Reassess C5's Int and open question 1. | C5, CON-0218 and the step-8 file carry the premise, and the "permanently" claim is attributed to the source as its claim and marked refuted in §8.15.4 |
| W-2 | high | defect | CON-0218; C5 "the discriminator" (S0117 L163) | Counterexample in scope: CAP-01 bootstrap delivery is live (S0120 L59) and recurs on every SessionStart (S0117 L179), yet it is incidental. Repetition does not tell owned from incidental; E7's counterfactual does. | Give E6 as necessary at the operational level, not as the discriminator. | CON-0218 restated, with CAP-01 cited |
| W-3 | high | defect | C8 "both limbs were applied … rests on absence of performance and of an adopted commitment"; the order of acts | The PO/ARB's grounds (S0120 L15) name only non-realization and incidental delivery, and they acknowledge "a governed receipt semantic". The two-limb test is Governance's reconstruction in its registration (L29–40, from A1.1). "The next act" is wrong: D3, D4.1, D4, D4.2 and D4.3 come between, and the change is the D5 work statement (AMD1). AMD1 removed the *presumption* of the test, "a refinement of the open question, not its answer" (S0116 L102). | Keep the authority's grounds and the registrar's reading apart. Restate the order and the force of AMD1. | C8 and CON-0213 cite L15 as the authority's grounds and L29–40 as Governance's reading |
| W-4 | high | omission/defect | §9.13 v14 + v12/v13; check 21 | Every act under a single-use grant is a use, and v14 makes every consumable grant single-use. So a consumable grant now allows **exactly one act**. "Adopt, then consume" is refused (probe: "g is single-use and already used by x1"). Check 21 still asserts x1 = T and k1 = T on a record that the guard forbids and that the marks flag. As a result: §9.13 L35 (currency of earlier work, §1.14.1-4) protects only records made without the guard; L36 and L41's "what single use bounds" are now empty; the consumption kind is reduced to "end without work". | State the consequence. Decide whether the work act *is* the use. Reopen whether the consumption kind is minimal. | §9.13 states it, check 21 is re-scoped, and minimality is listed open |
| W-5 | high | defect | R4c "differs … only in two things"; "the consequence the Principal asked" | The coordination result rests on the Professor's choices: "recorded" read as recorded at the kernel (R4a v6), K4-k's record of uses, and "every use counts" (C21 §4 lists these as "decided by the Professor"). "Only" leaves out availability: under (C + O), a use needs the owner *and* the kernel up, while (C + K) needs the kernel only. It also contradicts itself: the uses are said to sit in the owner's record, yet the kernel records them as well. | Make the conclusion conditional on those choices. Add the availability difference and resolve which record holds the uses. | R4c names the premises and lists three differences |
| W-6 | high | defect | §9.15 K4-o versus R4c | K4-o still says the owner's guard is an assumption "which the kernel does not provide". Under R4c the kernel itself refuses a second use. After v14, K4-o applies to nothing. G4 still counts uses "that are not F", which is the rule v13 replaced. | Reconcile §9.15 K4 and G4. | §9.15 v10 |
| W-7 | high | defect | C9 "the two roundings CE* forbids" | §9.13 row 1 (L256) lets a consumer map U to F *at the output* (fail-closed use); only inside Φ is that forbidden. The corpus forbids *reclassification* (M4 is never MISSING) and allows *action under an exception* while U is kept (compensating control). | State it as: no rewriting of the status, while action under U is allowed by an authorized rule. | C9 and CON-0221 cite §9.13 row 1 |
| W-8 | high | defect | CON-0217 "orthogonal" | The source classifies inconsistently: a "proposed schema" is Class C, but I-K1 "does not even reach Class C, being PROPOSED" (S0116 L25, S0117 L92). So subject is not shown independent of epistemic status. | Mark orthogonality as claimed by the source, not shown. | CON-0217 text |
| W-9 | normal | defect | C4 "does not depend on L10-type identity assumptions" | Re-deriving re-runs the computation, not the truth of the record's fields. Check 24k shows the `app` field depends on L10. The step-8 counterexample (a) found this, but it is not in the text. | Limit the claim to the computation. | C4 |
| W-10 | normal | defect | §8.14.4 L86; §8.14 C1's last sentence; C21 settlement W-1 "Holds? yes" | §8.14.4 still says "silent on performance (W-1)". C1 still has the stale "to be read when index order reaches it". | Amend additively. | §8.14.4 bullet corrected |
| W-11 | normal | defect | the model's `in_D` | It admits a consumption of the root or of an adoption (probe: `in_D` True). The book (L40) says "only of a grant marked single-use". `random_record` marks consumed grants afterwards, so random runs never reach the new outside-D case. | Refuse consumptions whose target is not a grant. | model v16 check |
| W-12 | normal | omission | C6 | Recording a decision by its content does not fix the act's *force*: S0120 L638 is a "RECOMMENDED" heading read as a selection. | Add force as a separate field. | C6 |
| W-13 | normal | omission | C8; S0120 L15 versus L103 | D2's grounds count "C-10-owned applicability" activity, which D3.2 later places outside C-10. | Note the drift. | C8 |
| W-14 | normal | defect | C10 Int "the claim the platform can carry is about its record" | The selected text keeps "delivery occurred", a claim about the world, so the move is partial. "Acknowledgement was recorded" can also be satisfied by the receipt itself unless the acknowledgement is a separate record. | Qualify both points. | C10 and CON-0222 |
| W-15 | normal | defect | CON-0223 | It merges "a name ≠ a criterion" with "permitted ≠ enabled", which is a deontic distinction. | Split them. | register |

## 3. Concept verdicts

| Concept | Verdict | Reason · scope |
|---|---|---|
| CON-0213 (restated) | **VALIDATED** | The PO/ARB noted the semantic and did not count it (S0120 L15; S0112 L14). Scope: this one decision. |
| CON-0215 | PROVISIONAL | Faithful, but "not general" is stronger than "not adopted as general", and it bundles three items. |
| CON-0216 | PROVISIONAL | A source requirement with no falsifier. "Four fields" need not mean four parties: a kernel guard is both observer and authority. |
| CON-0217 | PROVISIONAL | W-8, W-9. |
| CON-0218 | **INVALID as stated** | W-1, W-2. The level structure and the counterfactual test survive. |
| CON-0219 | **VALIDATED** | Follows from the definitions (S0118 L43–53; cured S0119 L6; selected by text S0120 L640). One lineage. W-12 is an omission. |
| CON-0220 | **VALIDATED** | The structural argument checks: required field with no referent → cannot be filled; optional → can. Adopted at S0120 L679–683. |
| CON-0221 | PROVISIONAL | W-7. |
| CON-0222 | PROVISIONAL | W-14. |
| CON-0223 | PROVISIONAL | W-15. |
| §9.13 v14 rule | PROVISIONAL | Consistent in D. W-4, W-11. |
| R4c | coordination: PROVISIONAL (conditional); "only two": **INVALID as stated** | W-5, W-6. |

**Proof steps (R4)**

C5, the instant-indexing step:

| Step | Justification | Defined? |
|---|---|---|
| E10 is a property of a history | S0117 L207–213 | yes |
| "Applied at a single instant" | none stated | **undefined**: snapshot or record up to t? |
| Hence false at every instant | needs "snapshot" | **premise missing** |

Judgement: inadequate, premise missing.

R4c:

| Step | Justification | Status |
|---|---|---|
| The kernel refuses uses not recorded by the owner | R4a decision, plus the "at the kernel" reading | the second part is the Professor's choice |
| The kernel records accepted uses | K4-k | Professor's choice (C21) |
| It refuses after any use | R4a v8 | Professor's choice |
| So it is the coordination point | follows from the three above, if every use passes the kernel's append step | valid, but conditional |
| "Only two differences" | none given | unsupported |

## 4. Coverage

**From my inventory:**
- COVERED: C1–C3, C6, C7, C9–C11 and their main lines.
- DISTORTED: W-3, W-7, W-14.
- MISSING: W-12, W-13; residue K (S0119 L50); "realization ≠ bounded context ≠ ownership" (S0116 L29, S0117 L30–36; not found by lexical search, which does not prove absence); "independent" used as a homonym (E6, the trigger); L2 compared with Option B; M4 as a classification rule rather than an enforcement rule (S0120 L410); the E3/E4 name drift; the commission's S5 obstacle (a transfer implies the predecessor concluded, which conflates transfer with completion; C2).
- JUSTIFIABLY EXCLUDED: lane and process mechanics.

**New forks:**
- (i) whether the work act is itself the single use (W-4);
- (ii) the consumption kind's minimality;
- (iii) force versus content of an act in D (W-12).

**Source defects:**
- S0118 S-2, "two" versus three;
- S0120 L72, "either limb alone … only because both", which contradicts itself;
- S0117 L225, "permanently".

**Refutation adequacy:**
- adequate: CON-0215, CON-0220;
- found but not carried into the text: CON-0217 (a), CON-0221 (c), CON-0222 (a);
- inadequate: CON-0218 (rival (c), indexing by time, refutes the claim but was not applied; no proof table), CON-0219 (force), CON-0216 and CON-0223 (no falsifier).

**Depth:** the Professor and I both judged all five sources full. We agree.

## 5. Next steps (in dependency order)
1. W-1 and W-2: restate CON-0218. Done when C5 and §8.15.4 carry the corrections.
2. W-3: C8 separates the authority's grounds from Governance's reading, and states the order of acts.
3. W-4, then W-11: state the one-act consequence, re-scope check 21, release model v16. Then W-5 and W-6: R4c conditional, with availability added; reconcile §9.15 K4-o and G4.
4. W-7 and W-8, then the normal findings.

No question for the Principal, except fork (i) if the Professor cannot decide it from R3a's reason.

## 6. Limits
- I did not run the two-record test. The C21 settlement deferred it to the "next model revision", and v15 does not include it.
- I did not check §1.14.1-4 directly, nor the THEORY-MAP beyond the rows for §8.14 and §8.15.
- The CAP-01 recurrence is an inference from S0117 L179 and S0120 L59.

## Summary
- §8.15 cites its sources accurately.
- Its interpretations of C5, C8 and C9 overreach.
- CON-0218 is INVALID as stated; one finding is critical (W-1, a missing premise).
- CON-0213, CON-0219 and CON-0220 are VALIDATED.
- v14 means a consumable grant allows exactly one act, and the book has not drawn this consequence.
- R4c's coordination result depends on the Professor's choices, and its "only two differences" is false (availability is missing).
- §9.15 K4-o is inconsistent with R4c.
- C21 settlement: W-2, W-3 and W-4 hold, confirmed in the run. W-1's "silent on performance" is now withdrawn but survives in §8.14.4.
- Running measure: 15 findings, 1 critical, 8 high, 6 material omissions.

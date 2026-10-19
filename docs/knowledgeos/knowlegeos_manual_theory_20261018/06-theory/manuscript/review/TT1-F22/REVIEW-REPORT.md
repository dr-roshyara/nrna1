*Saved verbatim 2026-10-19 from the reviewer's step-2 hand-back. Harness: step 1 140 s; step 2 sent 16:09:52Z, received at this save.*

# REVIEW REPORT — THEORY TURN F22

**Scope.** This reviews §9.6 (the three analyses), the C2 correction in §6.5, register rows CON-0135–0137, and the F22 row of the theory map. I read these against my saved step-1 inventory and the dependent sections: §1.6, §1.12–§1.14, §1.16, §9.1–§9.4, §0.5 and RESEARCH-PURPOSE. Independence is process independence only. I wrote no files.

## 1 · Assessment
The three questions are kept apart and every result is conditional, which is right. But §9.6 names the wrong deciding assumption, and its "one recorder per act-space" law does not follow. Counterexamples to it exist in the sources and in the book itself. Largest risk: the Principal is offered a choice framed by kernel closure (KC), while the purpose's own delegability test, applied to §9.6's own finding, already settles the question conditionally.

## 2 · Findings (ranked)

| # | Sev | Type | Claim | Anchor | What is wrong or missing | Correction |
|---|---|---|---|---|---|---|
| 1 | **critical** | defect | One-recorder rule "(Ded, from U and K1)" | §9.6.5 L145 | It is **neither sufficient nor necessary**. *Not sufficient:* BC-7 is one recorder, yet 23 of 30 concurrent appends silently lost a transition, because there is no lock, lease, compare-and-swap or version check [S0234 L19, L282]. *Not necessary:* §1.16.7 allows "several write paths that all consult one shared registry or lock". Also, recording that an act occurred only ever adds facts: if every act carries a global identity, the union of several recorders agrees whatever the order (64 of 64 small cases checked, inline Python). That is the book's own F19 / §9.4 C2 point. U governs *uniqueness* constraints, such as I-1 and duplicate registration, not occurrence | Restate it as: one serialization point per *uniqueness* constraint over an act-space; occurrence by set union needs a global act identity. Withdraw "kernel law" |
| 2 | **critical** | defect | "Must the kernel provide CR-a? Decided by KC, not by the evidence" | §9.6.4 step 4 | RESEARCH-PURPOSE L52–54 tests delegability by relocating the capability into **several independent applications**: "kernel candidate only if necessary and not delegable". §9.6.5 itself finds delegation to several recorders "not safe". §9.6.4's own definition of "must" includes an answer that "depend[s] on which application is asked". So, given R_s ∈ Req and more than one application, "must" holds **without KC**. Step 2 hides a premise: one shared context, reachable by all | Name the deciding assumptions as (a) R_s ∈ Req and (b) whether the kernel serves several independent applications (the purpose's test). Keep KC only as an alternative |
| 3 | high | defect | Analysis 3 row "append-only history: yes" | §9.6.5 L131 | It rests on seq contiguity [S0072 L53]. S0234's erratum shows a dense seq survives lost writes. Per the references (L200), §9.6 read only S0234 §2–3, §7.4 and §16–17, so the erratum (L13–23) and §8 were missed. E-2, a second write-capable path through `KOS_MECHANISM_PATH` [S0234 L20, L284], also defeats contract item 2 as a guarantee: "one writer class" is not "one write path" | Change the row to "no, as built". Add a no-lost-update clause and a write-path clause to the contract |
| 4 | high | omission | R_s needs "scope x at time t" | §9.6.3 N3; CR-a definition L45 | BC-7 holds no clocks: only 2 of 218 transitions carry a date [S0234 L283; S0071 L197]. GrantScope must stay opaque [S0071 L307], so scope containment cannot be computed. R_s also needs an **evaluator**: standing is *derived* (§1.14.1; two time indices, §1.16.2). ARCH-INV-1 bars the recorder from evaluating. Analysis 1 proves recording necessary but is silent on where evaluation lives, and that is the stronger kernel candidate | Add to the contract: act time, as-of validity, endings and pinning (§1.16.2, §1.16.6). Run Analysis 1 for the standing evaluator |
| 5 | high | defect | CR-a/CR-w split "along I-4" | §9.6.2 L44; CON-0135 | CR-a's defining act, registering a reference to an external human act, also happens in **transitions**: every START carries `humanAct` (35/35) [S0078 L63]. Cross-part reads exist: `authorized` requires a registered session [S0072 L32], and `identity` reads both parts [S0078 L266]. The sources used I-4 to argue for **one** root [S0071 L139–145; S0078 L100]. "Ded from the sources' I-4" reverses their conclusion | Redefine CR-a as all registration of human acts, START included. Mark the split as the book's own choice |
| 6 | high | defect | §6.5 C2 correction: "Not a breach after all … I-5 enforced in full on the authority part" | §6.5 L57 | (a) S0078's evidence E-1…E-8 covers the architecture HANDOFFs and the grants. It does **not** cover the self-recorded STARTs [S0071 L11; S0067 L9; S0078 L294]. Those are written by an architecture lane with `recordedBy: human` (OQ-10, S0078 L258), against §1.14.3's rule that "Governance records … STARTs". (b) "In full" overclaims: `grant` overwrites `registeredBy='governance'` unconditionally [S0078 L143], and `--writer-role` is self-declared. 47/47 is therefore structural, not evidence. §9.6's own row "recorder attestation: no" contradicts its row "I-5: yes, totally" | Keep the HANDOFF and "2 of 5" corrections. Restore the self-recorded START as an open breach of the §1.14.3 role law. Restate I-5 as "the declared writer role is checked; the actual writer is unattested" |
| 7 | high | defect | KC (CON-0136) | §9.6.4 K2 | Applied to A1, KC can never be met, because a kernel can check only records (RA, §1.12, residual trust). Applied to ARCH-INV-1, it is circular: an invariant *of the recorder* is a kernel invariant only if the recorder is in the kernel. "Checkable" is undefined (on records, or on acts?). Trivial case: a kernel with no invariants meets KC | Define "checkable on records". Exclude invariants that presuppose the placement |
| 8 | high | defect | Options table (C) vs (S) | §9.6.6 | (C) already puts the contract and the one-recorder rule in the kernel. (S) adds only "all of CR-w in an application". The options are not distinct | Merge them, or state the real difference |
| 9 | high | defect | "the move was decided, not executed" | §9.6.5 L132 | S0234 §6.4 records the decision state as **contradictory**: the runtime grant says B′, the tracked review says NOT SELECTED [L228–235] | Say "decided per the runtime record; contested" |
| 10 | normal | omission | Plane-ward evidence | §9.6.6 | S0234's invariant asymmetry [L272] is missing, together with its own defects: it lists I-4 and I-10 as declared-only [L264–266], and it calls the engine "advisory" [L339] | Add it, with its defects |
| 11 | normal | defect | "A plane can be one shared supporting context; need not differ in substance" | §9.6.4 L109 | This interpretation dissolves a recorded conflict between acts of unequal standing (V3, a Track-B AI proposal of 08-16, vs ADR-AIP-03, accepted 08-17), and supersession is not weighed. Citing "the sources accepted it as a context" as evidence against (P) treats an acceptance as evidence | Mark it Int. Record the two acts' standing and order |
| 12 | normal | omission | Circularity | — | The boundary is read off the mechanism [S0067 L144; S0071 L282], and the same line is then cited as preservation evidence (ARCH-INV-1 row, S0071 L293) | Mark it as one line, not as a test |
| 13 | normal | omission | Notation | S0068 L12 | The Capability Identity Invariant (one capability id, one owner) cannot express a capability shared across contexts, so it biases the outcome toward (C) | Note it |

## 3 · The Principal's five questions

| Analysis | 1 Defined | 2 Derivation | 3 Assumptions | 4 Refutable / cases | 5 Decision recorded |
|---|---|---|---|---|---|
| A1 necessity | mostly. "Mechanism set" is ambiguous: the kernel's M (§9.1) or the whole system's? | valid for recording | hidden: R_s needs an evaluator, time and scope containment (#4) | trivial cases correct; no counterexample to recording | R_s ∈ Req is reserved for the Principal: correct |
| A2 must-provide | "must" is well defined; KC is not (#7) | step 2 assumes a single shared context; step 4 contradicts the definition (#2) | hidden: one application | its own challenge (c) is met by E-1 (#1, #3) | the deciding variable is framed wrongly |
| A3 delegability | "safe" is defined only against one context | rows 3 and 4 rest on refuted evidence (#3, #6) | D1 conflates the subdomain class with the layer | counterexample to the limit (#1) | nothing reserved is decided, except that "kernel law" is asserted |

## 4 · Verdicts

| Concept | Verdict | Reason · scope |
|---|---|---|
| CON-0135 | **INVALID as stated** | Human-act registration is in both parts, and cross-part reads exist (#5). A redefined capability split may survive |
| CON-0136 | **INVALID as the decider**; PROVISIONAL as a stipulation | Not decisive (#2); can never be met for A1 and circular for ARCH-INV-1 (#7) |
| CON-0137 | **INVALID** (the one-recorder limb); contract PROVISIONAL | Counterexamples (#1). The contract lacks time, endings, no-lost-update and write path (#3, #4) |
| §6.5 C2 correction | **PROVISIONAL** | Right for the HANDOFFs and "2 of 5"; overreaches on the STARTs and on "in full" (#6) |

## 5 · Next steps (in dependency order)
1. Restate U's scope (uniqueness vs occurrence), using F19. Done when the one-recorder text is replaced.
2. Re-run Analysis 2 with the purpose's relocation test. Done when the options are conditioned on R_s ∈ Req and on several applications.
3. Run Analysis 1 for the standing evaluator. Done when its placement is stated.
4. Repair the Analysis 3 rows from S0234 §8 and the erratum, and the contract (#3, #4).
5. Redefine CR-a (#5). Fix §6.5 C2 (#6).
6. Question for the Principal: does the kernel serve several independent applications? On the purpose's test this decides F22, given R_s ∈ Req.

## 6 · Limits
- I did not check S0065 L28, S0164 L2420–2424 or V3.
- I did not run `kernel_model.py`. The two inline checks (set union agrees regardless of order; a lost update with one unlocked recorder) test form only.
- All the evidence comes from one estate and one operator. Apart from S0234, it is from one day.

---

## Appendix

**A. Quotes and anchors spot-checked** (all match except where noted):
- S0071 L130, L293;
- S0078 L201–204, L206, L208, L214, L222, L230;
- S0067 L51, L68;
- S0069 L21, L33–35, L52, L63;
- S0072 L53;
- S0062 L112, L113;
- S0234 L126;
- S0078 N-2;
- one distortion found: "decided" for S0234 finding 4 (#9).

**B. A dated inconsistency inside the book.** §6.5 C2 and the theory map (L193) say authority endings were never exercised, citing S0071 (08-17: 41 grants, all AUTHORIZED). The §1.13.2 recount shows REVOKED 1 and CONSUMED 1. Date both statements.

**C. Ranked omissions against my step-1 inventory:**
1. E-1 lost writes — MISSING (#1, #3).
2. No time dimension — MISSING (#4).
3. The OQ-10 `recordedBy` referent — MISSING from the C2 correction (#6).
4. The structural `registeredBy` overwrite — MISSING (#6).
5. S0234's internal contradiction and "advisory" — MISSING (#10).
6. Circularity — MISSING (#12).
7. Capability Identity Invariant — MISSING (#13).
8. "Grant" homonym [S0072 L80] — MISSING. Normal severity.
9. The D.5 "access to the mechanism" line — COVERED (recorder row).
10. The knowledge-seam argument (adjudicated vs folded) — MISSING from §9.6. It is in §6.5 C7, but is not used as an argument against kernel placement.

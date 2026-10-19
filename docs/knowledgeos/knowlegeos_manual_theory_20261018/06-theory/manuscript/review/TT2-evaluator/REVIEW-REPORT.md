*Saved verbatim 2026-10-19 from the reviewer's step-2 hand-back. Harness: step 1 130 s; step 2 sent 17:24:26Z, received at this save.*

# REVIEW REPORT — THEORY TURN 2

*Topic review of §9.7 (the evaluator CE) against §1.14.1, §9.6, the register and RESEARCH-PURPOSE. I re-read my saved step-1 inventory first. Independence: process independence, not personal independence.*

## 1. Assessment

- **What is sound:** the necessity of *some* evaluator of standing is sound, given R_s, N1, N2 and the fact that standing depends on time.
- **What is not shown:**
  - that the kernel must *own* the parameters;
  - that *execution is delegable*;
  - that K5 is well-posed.
- **Largest risk:** §1.14.1's CE presupposes "one global, gap-free sequence… No two entries share a position" (§1.14.1 L159). That is a cross-application uniqueness constraint, so CE as defined already answers K4 with "yes". This contradicts §9.6.6a, which says recording is delegable by set union and that "F22 now turns on K4 alone".

## 2. Findings (ranked)

| # | Sev | Type | Claim | Line | What is wrong or missing | Consequence | Correction |
|---|---|---|---|---|---|---|---|
| 1 | **critical** | defect | the record is "shared or mergeable" (§9.7.4 step 5; §9.6.6a items 3–4) | §1.14.1 L159–161; S0128 L48 | **CE's ordering:** CE orders acts by (t_x, s_x), with s_x a global, unique position. **The estate's seq:** it is per record ("in every one of the 16 records"). **Set union:** keeps the acts but loses their order. **Check:** one grant record (issue, revoke) plus one act x in a second record has 3 admissible interleavings, giving authorized(x) = F, T, F. | CE is not a function of a union-merged record. A global position needs a serialization point (U, §1.16.7). | Either give F22 a uniqueness constraint (K4 = yes for positions), or redefine CE over a **partial order** that returns U when acts are incomparable (S2775 L1680–1686). Re-open §9.6.6a items 3–4. |
| 2 | **critical** | defect | "Execution is delegable… deterministic… recompute to check" (step 4; result 3) | §1.14.1 L215, L219 | **υ:** under υ = "does not count until evidenced", mutual annulment oscillates (U,U) → (T,T) → (F,F) → (T,T)…; under "counts as U" it stays at (U,U). **Other gaps:** covers(B, x) and the link between actor and holder are undefined, and σ = conditional recurses outside the fixed point. So CE is undefined for **every** θ, not only one υ value. | Step 1 ("for fixed R and θ, one value") has no function to apply to yet, and result 5 understates the gap. | Make the definition of covers, the actor–holder link and a monotone υ preconditions of results 2–3. |
| 3 | high | omission / defect | case 10 shows that different ρ means disagreement | S2775 L1121–1157; §1.14.1 L132 | §9.7.2 quotes S2775's three queries but never uses them. Read through S2775, ρ = at-the-time and ρ = now are close to two **queries** (as-then vs as-now), not two policies. Under standing qualified by axis, case 10 is not a disagreement. Only P (case 4), and κ, σ and υ (cases 9, 5, 15), give genuine disagreements. §1.14.1 safeguard 3 and S2775 conflict on this, and the conflict is not recorded. | K5, "one answer per (item, scope, time)", is ill-posed until the time axis is fixed. Result 2 pre-empts reserved decision 2, which could also be "ρ is part of the question". | Restate K5 over (item, scope, time, axis). Record the conflict between ρ as policy and ρ as query. |
| 4 | high | distortion | C3 says §1.14.1 lacks an **acceptance** time, so CE may need to be tritemporal | §1.14.1 L159, L218 (defect 4) | Acceptance is the adoption act's t_x, which §1.14.1 already has. The missing axis is **recording time**: there are positions, but no times. "What did we believe on 1 June" (KnownStateAsOf) needs a map from time to position. | §9.7.8's open question asks the wrong thing. | Replace it with "recording time as an input of CE". |
| 5 | high | defect | "kernel must own CE's definition and policy parameters" | §9.7.4 step 3 | **Non sequitur:** "one value for all applications" implies one authoritative *home*, not a kernel home. Step 3 calls the parameters acts under A1, so their home is the record, which TT1 found delegable. **Hidden premises:** kinds of item are kernel-level, and the parameters never change. | **Not addressed:** if θ changes by later acts, which θ applies: at t_x, at t, or now? (contract time, S2775 L771–803; pinning of deciding logic, §1.16.6). Nor whether CE's definition is versioned (S0916 L1390–1416; §1.16.3 analyser version). | Restate: one authoritative, **versioned** home each for the definition, the coverage relation, the root designation and the parameter values indexed by time; placement left open. |
| 6 | high | omission / distortion | input table, §9.7.3 | S0128 L45, L48; §1.14.1 L127, L173, L221 | **"seq: yes" is distorted:** seq is per record, not the global position CE needs. **Missing rows:** adoption acts with cited bases ("no rule cites the grant" [AMS L200–206]); recording time; the **root** designation; the actor–holder link; the coverage relation; a declared record cut. The measured records are workflow and grant records, not adoptions of knowledge items. | The record contract (§9.6.5 item 5) is incomplete. | Add the rows; correct the "seq" row. |
| 7 | high | omission | Analysis 3: three conditions make delegation safe | §1.14.1 property 2; §9.6.5 (S0234 L282) | CE is not monotone in R: a later revocation turns T into F. An application reading a stale or partial copy returns T where F or U is due, which breaks INV-A3 and K5. Density of seq is not proof of completeness. | Condition 3, "inputs from the shared record", is not sufficient. | Condition 3 becomes: computed against a **declared cut**, CE(c, t; θ, R≤s), with completeness up to the cut checkable (S0916 L1217–1248, snapshot as a set of parameters). |
| 8 | high | omission | trivial solutions | — | A constant evaluator that always returns U meets all five rows of Analysis 3, and K5. | Nothing requires CE to return T for an adoption that the record evidences as valid. | Add a must-do: soundness and completeness of CE relative to the evidenced acts. |
| 9 | normal | defect | C5: "historical standing… cannot be reconstructed" | S2775 L1600–1614 | S2775's theorem gives **sufficient** conditions. Failing one of them does not imply that reconstruction is impossible (denying the antecedent). | The conclusion may still hold, but only via Analysis 1. | Re-derive it from the input table. |
| 10 | normal | defect | "CE is not computable from the record" | §1.14.1 L127, L259 | CE is three-valued and total by design. On this record it is **uninformative** (U or F), not uncomputable. | Computability and informativeness are merged. | Restate. |
| 11 | normal | omission | conflict outcomes | S2775 L1466–1471; F24 | "Your decision needed" is not among CE's headlines. Two current, conflicting claims are each judged alone (C6). | CE cannot report a conflict. | Link to F24. |
| 12 | normal | omission | the root of the authority regress | §1.14.1 L173; S1414 L1277–1281 | The root is an input of CE. With one root per application or organisation, K5 fails trivially. | Unstated condition of K5. | State it. |

## 3. Concept verdicts

| ID | Verdict | Reason · scope |
|---|---|---|
| CON-0138 | **PROVISIONAL** | That *an* evaluator is necessary holds, given R_s, N1, N2 and time-dependent standing. **The term merges two meanings:** "an evaluator" (a role) and "§1.14.1's function" (which is undefined: findings 2, 6). "Delegable only as a kernel-defined function" is not shown (finding 5). |
| CON-0142 | **INVALID as stated** | "Deterministic, may run anywhere, checked by recomputation" is defeated by findings 1–2 (order dependence, oscillation, undefined covers). "Kernel owns" is a non sequitur (finding 5). The parameter list leaves out ι, covers, the root and the order. Restated form, PROVISIONAL: under K3 and K5 (axis-qualified), each item has one versioned authoritative home; execution is delegable only against a declared cut with a fixed order, once CE is defined. |
| CON-0143 | **PROVISIONAL** | **Ill-posed:** time axis missing (finding 3). **Hidden premise:** the scope excludes the asking application; if scope may equal the application, per-application θ satisfies K5. **Not implied by the central question:** RESEARCH-PURPOSE L123 glosses "valid" as evidence and correctness, and "reads against that" (§9.7.4) overclaims. **Duplicate:** it restates §9.6.4 step 5; the register's same_subject_as CON-0136 looks wrong. |

**The Principal's five questions, per analysis:**

| | Precise definitions | Step-by-step derivation | Explicit assumptions | Refutable | Nothing reserved decided |
|---|---|---|---|---|---|
| Analysis 1 | CE merges role and function | sound for the role | R_s, N1, N2 stated; reliance on time-dependence implicit | trivial cases given | yes |
| Analysis 2 | "agreement" lacks the time axis | steps 1 and 4 fail (findings 1, 2); step 3 is a non sequitur | hidden: shared order and cut; kinds at kernel level; θ constant over time | cases 4 and 15 hold; case 10 depends on the axis | partly: result 2 fixes the *scope* of reserved decision 2 |
| Analysis 3 | yes | row by row | missing: completeness and the cut | the trivial evaluator passes (finding 8) | yes |

**Agreement argument:** "same R and θ give the same answer" is a tautology wherever CE is defined; the substantive premise, that two applications hold the *same R*, is not given (finding 1). "Different θ gives disagreement" holds as an existential claim over possible records. It holds for P, κ, σ and υ, and for ρ only if ρ is policy. On the actual estate (2 endings in 144 records) differences in P would rarely show. The claim is modal, not empirical (generalisation rule), and no rate is claimed.

**"Execution delegable":** unsound for now, because of the non-monotone υ, the undefined covers, and the partial order across per-record logs (my step-1 S2775 #9).

**K5:** a separate stipulation, correctly labelled as one, but not "the reading" of the central question.

## 4. Coverage

**Gaps, ranked:**
1. order and uniqueness of positions (F22/K4);
2. time axis and recording time;
3. contract time and versioning of the definition;
4. completeness and the record cut;
5. root;
6. conflict;
7. ι not classified.

**New fork:** is ρ policy or query? It is not in the theory map.

**Lineage:**
- the book's count of S0916, S1414 and S2775 as one line agrees with my inventory;
- S0128 measures the same BC-7 estate as §9.6's S0071 and S0234, so it is not a line independent of TT1's evidence.

**Quotes spot-checked:** 15 anchors, all matching:
- S0128 L22, L48, L49, L123, L171;
- S0916 L77–87, L216, L1699;
- S1414 L375, L411, L463, L506–508, L614–616, L1162–1165;
- S2775 L1121–1149, L1602–1603.

**Source defects:** none relied upon.

## 5. Next steps (in dependency order)

1. **Decide the order semantics** (global total or partial). *Done when:* §9.6.6a items 3–4 and K4 are restated.
2. **Define covers, the actor–holder link and a monotone υ** (defects 1, 5, 7). *Done when:* CE is total over a stated domain, and a small model check passes.
3. **Add recording time and the axis to CE's signature**, and restate K5 over (item, scope, time, axis). *Done when:* case 10 is re-run.
4. **Version the definition and index θ by time.** *Done when:* the question "which θ applies" is answered or reserved.
5. **Add the declared-cut and must-do requirements to Analysis 3.**
6. **Re-run the analyses, then re-grade CON-0142.**

**Question for the Principal:** is the record view ρ an organisational policy, or a parameter of each query?

## 6. Limits

- **Not read:** S0071, S0234 and AMS, so the citations to them are unchecked; Ch. 6 §6.5 and §8.8 (seen only via grep); §1.13 beyond its recount table.
- **Not run:** kernel_model.py (not decisive here).
- **Check scripts:** `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/tt2_check.py` (the oscillation and interleaving checks). These are small cases; they show form, not proof.
- **Book searches:** "recording time", "contract time" and "global sequence" found nothing in the book. A miss is a signal, not proof of absence.

## Appendix (lower priority)

- **ι** (the reason condition) is among θ but not classified as policy or not (§9.7.2; result 2).
- **§9.7.1's plain-language line** "different policies… disagree" overgeneralises: they disagree on some possible records.
- **"Producer ≠ verifier by recomputation"** rests on §8.6 C5, which is itself PROVISIONAL. Recomputation checks execution, not the definition.
- **S0916's acceptance** may be an *evidential* act, not adoption. If so, mapping it to t_x merges the two engines that §1.6 keeps apart (RC-37a). This needs the source checked before C3 is restated.

# Critical Review of the Theory Readiness Assessment (Session B self-audit)

| | |
|---|---|
| **Date** | 2026-10-17 |
| **Reviews** | `THEORY-READINESS-ASSESSMENT.md` (Session B, this date) |
| **Kind** | A review report for the Principal. **It decides nothing and modifies no existing file.** Corrections are given as diffs (§F) and are **not applied**. |
| **Method** | Read-only inspection of authorized governance records, frozen code, R7 verifier code and design records, plus deduction. **No computation, simulation, enumeration or model check was run.** No corpus, S5, hold-out, sealed, ledger or batch-input material was read (O1, GL-0125). |
| **Labels** | **VERIFIED** (checked today against the cited source) · **REPORTED** (stated by another session; not checked) · **DERIVED** (Session B's own deduction; same model family, unreviewed) · **CONDITIONAL** (holds only under the stated rules or assumptions) · **OPEN** |
| **Independence** | Session B audits its own work here. **This is not independent review.** Every DERIVED item below still needs a human or a different-model-family reviewer before it can be treated as established. |

---

## A. Executive summary

1. **Most of the factual foundation holds**: governance head, worktree divergence, Run-3 deferral, 0 measured labels, the RI-1 reopening, file states. **One factual claim was wrong, and it matters.** The assessment listed "B = 999" among frozen design constants, and recommended recording a single-run condition "B = 999, seed 20260927".
   - The governance record does **not** settle these execution parameters for the confirmatory tests.
   - Freeze 1 froze **B = 9,999** and a seed to be drawn from the OS CSPRNG at the freeze (GL-0094; B1 l.68).
   - Later acts set B = 999 only for H5 (GL-0122, 0124) and for the H2 power threshold (GL-0135 #1).
   - No analysis seed and no single-run rule is recorded anywhere. **VERIFIED.**

   This is a **pre-registration gap**, not just documentation. It belongs with the open Freeze 1 / prereg_v2 reconciliation (RQ-1), and it needs a Principal decision (new brief **DS-4c**).

2. **Proposition Q2's core identity survives re-examination. Its interpretation and scope were stated too strongly.**
   - **What survives.** For every contradiction relation produced under the DR-01 predecessor rule with both endpoints A.10-dated and untied, H2's "forward" indicator equals agreement between the agent's **list order** of timeline points and the A.10 file-date order. **DERIVED, CONDITIONAL.** The facts it rests on are VERIFIED in the inspected code.
   - **Correction 1: other relations.** Agents may enter *any* contradiction pair between timeline points, not only predecessor pairs. The affected share of H2's data is unknown.
   - **Correction 2: what Q2 does not show.** It does not show that H2 "has no meaningful domain interpretation". It shows that, for the predecessor-rule subset, the statistic contains no information about contradiction direction beyond list-order agreement.
   - **Correction 3: "π ≈ 1 by construction".** This is withdrawn. It was an empirical conjecture.
   - **Correction 4: the C1 model.** That C1 is a precondition for a Kind-P ROBUST follows from v1.8's CA-8 rule, not from Q2. Q2 suggests C1 may be *insufficient* for H2.

3. **A new, separate H2 point.** H2's null assumes all orientations are independent, but relations within one object share one agent ordering. A rejection can therefore reflect dependence rather than a forward share above ½.
   - This does not break validity for the stated null (Proposition 1 holds as stated).
   - It does weaken what a rejection licenses. **DERIVED.** Session A noted the same point (REPORTED).

4. **The confirmatory guarantee (Proposition 1, Holm) is scoped correctly:** at most 5% chance of any false rejection among the true Model-0 nulls of {H1, H2, H4}.
   - It holds under A1–A4.
   - **A3 is currently unmet as a recorded rule** (see 1).
   - It says nothing about reconstruction error, exploratory tests, theory generation or the verdict. **DERIVED, reviewed only by the same model family.**

5. **H3 and H5 false-positive rates are unknown.** The earlier "conservative" claim stays withdrawn.

6. **The reachability claim stands as stated** ("under V-2 (ii) with S5c prohibited, ROBUST is impossible"), but only as a deduction **conditional on the unadopted v1.8 proposal and the current S5c prohibition**. It is not a governance fact. Two qualifications were missing:
   - (a) "core = exactly {H2, H4}" under V-2 (i) depends on **k_min = 2**, which V-3 has not decided;
   - (b) any pre-registered Kind P candidate blocks NCTF, unless V-11 or a vacuous disposal applies.

7. **The numerical correction (999·2⁻ᵐ) checks out**, with one wording refinement (§C.6).

**What the Principal needs to understand:**
- The assessment can support decisions on **DS-3, DS-5, DS-8 and DS-6/7** as written.
- **DS-4a must be split.** Recording an execution condition requires first deciding the execution parameters (DS-4c).
- **Q-M1 and Q-M2 are deductions about a proposal and about code**, not findings about KnowledgeOS. They need independent checking before any decision relies on them.

---

## B. Verification register

| # | Claim in the assessment | Source checked | Status | Reasoning and remaining uncertainty | Affects |
|---|---|---|---|---|---|
| B-1 | Governing hierarchy: v3.5 + P3b v1.7; ARCH v1.2 not declared governing | GL-0068 l.1175, GL-0069 l.1186; TODO "Decision A" open | **VERIFIED** | as recorded. Decision A has no recorded act | governance |
| B-2 | O1 in force; no early corpus reads | GL-0125 l.2010; GL-0137, 0138, 0140 "not authorized" rows | **VERIFIED** | — | scope of all work |
| B-3 | Log head G-LOG-0140; PROD 2,218 lines, sha `04cb0c19…`; worktree ends at G-LOG-0110 | both files, today | **VERIFIED** | — | governance |
| B-4 | The PROD copy is authoritative | no recorded act. GL-0139 says to read W documents "from PROD with `git show`", which implies the log's home is PROD | **REPORTED (Session A) + inferred** | consistent with the record, but **not a recorded determination** | governance (DS-3) |
| B-5 | prereg_v2 FROZEN at GL-0137, sha `827ce461…`; the file self-labels "DRAFT, NOT FROZEN" | GL-0137; file l.4, l.348 | **VERIFIED** | documentation discrepancy only | documentation |
| B-6 | No record reconciles Freeze 1 with prereg_v2 | grep of the PROD log | **VERIFIED (absence)** | Freeze 1's immutability clause (GL-0094) vs GL-0118…0137 changes | governance + science (B-7) |
| B-7 | **"B = 999" is a frozen design constant** | GL-0094 (Freeze 1: "B = 9,999"); B1 l.68 ("B = 9,999 … seed drawn at the freeze from the OS CSPRNG"); GL-0122/0124 (B = 999 **for H5**); GL-0135 #1 (NR-P "at B = 999") | **CONTRADICTED** | For H1, H2 and H4 the record contains **B = 9,999 (Freeze 1)** and only implicitly 999 (the H2 power threshold; the code default). The instrument takes B from the command line (S-PRV2 l.361) | **science** (A3; the NR-P threshold depends on B) |
| B-8 | Seed 20260927; a single run | log: 0 matches for the seed in an analysis role; no single-run rule; code default only (prereg_v2 l.309, l.362) | **VERIFIED: not recorded** | A3 is unmet as a rule. Freeze 1's CSPRNG analysis seed is not recorded either | **science** |
| B-9 | Run-3: approved at G2, execution DEFERRED; no PREFLIGHT-OK recorded | GL-0117 l.1906; TODO l.8 | **VERIFIED (records)** | live preflight state not inspected (by design) | operations |
| B-10 | 0 of 1,975 measured; no accepted batch | TODO l.5/8/47; no H-06 acceptance entry in the log | **VERIFIED** | — | science |
| B-11 | RI-1 reopened (GL-0104), treated by EG-2 and RI-1b report-only (GL-0106 f) | GL l.1616, l.1649 | **VERIFIED** | The TODO (l.20, 231, 290) is stale. Whether RI-1 is "closed" is not recorded: it is reopened and treated | documentation |
| B-12 | Manuscript, PO-01 note and architecture proposal are untracked and unreviewed | shas today: `3d43f59b`, `4ea81900`, `f6d84cee`; `git status` untracked | **VERIFIED** | "unreviewed" means no independent review. Session A's same-model review exists (REPORTED) | documentation |
| B-13 | RQ-1/RQ-2 answers exist in Session A's "last report" | no authorized file contains them | **REPORTED; source not found** | — | traceability |
| B-14 | DR-01: each CONTRADICTS point must appear in `contradicted_by` with target = predecessor | `p3b_s5_r7_reconstruction.py` (W, sha `4dcf8998…`) l.83–92: `pred = tl[i-1]`; R7 design record l.45; clause audit l.149 | **VERIFIED** (verifier constraint) | the agent-facing contract text itself was not opened | science (Q2) |
| B-15 | No check ties the timeline list order to A.10 dates | reconstruction l.76–126 (no order check); `p3b_s5_verify.py` timeline checks l.700–720 (key presence, `order` ∈ {ORDERED, UNORDERED-BLOCK}); clause audit l.123–134 ("no predicate") | **VERIFIED for the inspected modules** | an exhaustive search of all R7 code was not done. Another module could check it | science (Q2) |
| B-16 | Other routes create `contradicted_by` entries "e.g. semantic_evidence.d4" | reconstruction l.111–125 | **CORRECTED** | The verifier permits **any** pair (s, t) of distinct timeline points of the object. A d4 entry is required only for CONTESTED/ROW-1 objects. So non-predecessor relations are an agent-entry route, not a d4 route | science (Q2 scope) |
| B-17 | H2 uses `file_position` (file-level A.10), not point-level `dated_position` | prereg_v2 l.300–305; reconstruction l.63–65 | **VERIFIED** | the verifier's own `precedence` uses point-level confirmation; H2 does not | science (Q2) |

---

## C. Mathematical and scientific review

### C.1 Proposition Q2: step-by-step re-examination

**Setting.** For an object, let tl = (p₁, …, p_n) be the agent's timeline **list**. Each p_i has a `source_id` and a `change_vs_previous` class. Let d(s) be the A.10 file-level dated position (`file_position`), or UNDATED.

| Step | Assessment's statement | Check | Verdict |
|---|---|---|---|
| (F1) | each CONTRADICTS point s yields (s, pred(s)), with pred = "immediately preceding point in the agent's timeline order τ" | the code takes pred = tl[i−1], the **previous list element**, whatever its order flag or dating (l.89–92). The rule is a verifier constraint: a missing entry is a failure; the agent writes the entry | **Correct**, once "timeline order" is read as list order. Refinement: τ is the list index, not a dated chronology |
| (F2) | the verifier does not check τ against A.10 dates | B-15 | **Correct for the inspected modules**; not exhaustive |
| Ties / undated | dated, untied relations only | prereg_v2 l.302–303: a relation enters `orient` only if both `file_position`s are dated and unequal. An UNORDERED-BLOCK neighbour lies in a BULK block, which A.10 excludes, so it is undated and excluded (P3b §14.4c) | **Correct.** Excluded relations do not count, but they do count against H2's untestability guard (m < 5) |
| Identity | X_r = 1 ⇔ list order and A.10 order agree on {pred(s), s} | pred(s) precedes s in the list by definition; X_r = 1[d(pred(s)) < d(s)] | **Valid (DERIVED)**. A tautology given F1, and correct |
| Scope | "for r ∈ R_C" | other entries (B-16) have orientation equal to the agent's choice of which point "contradicts" which, compared by A.10 | **Correct as restricted.** The share of R_C among H2's relations is unknown |
| Interpretation 1 | on R_C, π = the rate of list-order / A.10 agreement on adjacent contradiction pairs | follows from the identity, if π is read as the population forward share over R_C | **Valid, CONDITIONAL** |
| Interpretation 2 | "says nothing about the direction of contradictions in the domain" | on R_C, the encoding fixes direction: a later list point contradicts its predecessor. The only variable left is order agreement. A real backward contradiction (an earlier-dated text contradicting a later one) cannot appear on R_C except as a list/A.10 disagreement | **Valid for R_C**, as "no information on domain direction **beyond** order agreement". It does **not** follow that H2 as a whole is meaningless (other relations) |
| Interpretation 3 | "if agents order from the same A.10 metadata, π ≈ 1 by construction" | whether agents see A.10 metadata, and how they order, is empirical and was not inspected | **Overstated. Withdrawn** → "if list-order agreement with A.10 is high, the R_C forward share is high" |
| Interpretation 4 | the frozen caveat is "sharpened" | B1 §3's artifact-model column already notes the method constraint, with the mitigation "tested only on relations whose A.10 dates differ" | **New point (DERIVED):** that mitigation does not remove the Q2 effect, because Q2 concerns exactly the date-differing relations |
| Consequence 4 | the C1 model is a precondition of any Kind-P ROBUST | v1.8 CA-8: H2/H4 "met only once the C1 model shows the reading is not an artifact of agent classification" | **The precondition follows from CA-8** (CONDITIONAL on v1.8 being adopted as written, and on V-2 (i)), **not from Q2.** Q2 adds that C1 may not be *sufficient* for H2: a correct observation model could confirm that the R_C reading is order agreement, so H2 would fail CA-8 |

**Revised statement (proposed, DERIVED, CONDITIONAL).**

> **Proposition Q2′.** Under the R7 verifier rule DR-01 (each CONTRADICTS point s must have the entry (s, tl[i−1]) in `contradicted_by`), and given that no inspected verifier check relates list order to A.10 dates, for every such entry whose endpoints are both A.10-dated and unequal:
>
> X = 1 ⇔ the agent's list order and the A.10 file order agree on that adjacent pair.
>
> Hence, restricted to these entries, H2's forward share is the list-order / A.10 agreement rate, and carries no further information about the temporal direction of contradictions.
>
> Proposition Q2′ does **not** determine the share of such entries, the agreement rate, or H2's outcome.

**What this establishes.** A **potential measurement-validity problem** for the H2 licensed reading on an identifiable subset of the data. Not a proof that H2 is meaningless, and not a prediction of its outcome.

### C.2 Proposition 1 and the Holm guarantee

- **Family.** `CONFIRMATORY = ("H1","H2","H4")` (prereg_v2 l.69; GL-0128/0131). Holm runs over the fixed family of three, with an untestable hypothesis entering at p = 1 (R3). **VERIFIED.**
- **Sufficiency of A1–A4 (DERIVED).** Holm needs only valid marginal p-values. Proposition 1 gives validity under A1 (null invariance under the generator's group), A2 (ideal uniform independent randomness), and A3 (statistic, group, B and seed fixed before the data, one run reported). A4 sets the scope. These assumptions suffice for the stated null hypotheses. **Same-model review only.**
- **H4 null scope (VERIFIED in code).** `h4_dated` keeps every `births` key with a dated S-id (l.284–290), and `m0_h4` shuffles values across all keys of that dict. The statistic counts only the five kinds. A1 for H4 is therefore exchangeability across **all** dated birth keys, which is broader than "the five kinds".
- **A3 (VERIFIED: not a recorded rule; B-7, B-8).** The guarantee holds for any B and seed fixed before the data. It is voided by:
  - re-running with several seeds and reporting a chosen one;
  - choosing B after seeing results;
  - changing the statistic.

  Today nothing in the record prevents these, and the record **conflicts** on B (Freeze 1: 9,999). **This is a scientific gap, not a wording issue.**
- **Reconstruction error (A4).** The guarantee concerns the data as extracted. Reconstruction quality is measured separately (θ_D, θ_A, R1/R2; B1 §1–2). θ_D has no adequacy threshold (V-5 open).
- **H2 independence.**
  - *For validity:* H2's null is "all pooled orientations independent and fair". The test is valid **for that null**.
  - *For interpretation (DERIVED):* relations of one object share one agent ordering (Q2), so independence is doubtful. Under positive within-object dependence with marginal π = ½, the i.i.d. sign-flip null is false, so rejection rates exceed α **as a test of π = ½**. A rejection therefore licenses "the i.i.d.-fair null is false", not necessarily "π > ½".
  - *For power:* NR-P's power formula assumes independence (S-PRV2 l.87–88). Its 0.8 power figure is unjustified under dependence.

**What the guarantee establishes:** P(at least one false REJ among the true Model-0 nulls of {H1, H2, H4}) ≤ 0.05, under A1–A4. **Not established:** anything about exploratory tests, NR-P, reconstruction validity, theory generation, the verdict, or the substantive readings beyond Model 0.

### C.3 H3 and H5

| Item | Standing |
|---|---|
| The general star-scheme counterexample | **VERIFIED by recomputation** (Session A, REPORTED; Session B's own earlier computation, DERIVED). It concerns the scheme, not the frozen tests |
| The frozen tests at their chain lengths (10·\|E\| and 5·\|R\|) | **OPEN.** Size unknown |
| The earlier simulations | **invalid as size evidence.** Wald intervals near 0; Session A's exact bounds include above-nominal values (REPORTED, not recomputed) |
| The H5 simulation statistic | **wrong** (exact implications only; the frozen statistic is the v2 min-p combination). VERIFIED against the frozen code (prereg_v2 l.203–209) |
| H3, n ≤ 4 | **finite result**, reproduced by Session A (REPORTED). Not evidence beyond n = 4 |
| H3 for general n | **OPEN.** The triangle-reversal proof route is refuted (checked by hand) |

**Conclusion:** the false-positive rates of H3 and H5 are **unknown**. These tests are exploratory and never confirmatory (B1 §5).

### C.4 Q-M1 reachability, rechecked

| Statement | Classification |
|---|---|
| Under V-2 (ii) with S5c prohibited, ROBUST is impossible | **CONDITIONAL deduction from the written v1.8 rules** (R4 → CA-9 → PREDICTIVE → a scored prediction → S5c). It holds while (1) v1.8 R4/CA-9 stand as written, (2) S5c stays prohibited or not scoreable, and (3) V-2 (iii) is not chosen. **Not a governance fact.** v1.8 is a proposal |
| Under V-2 (i), the only core is {H2, H4} | **CONDITIONAL, with a missing premise: k_min = 2** (V-3 open). With k_min = 1, a single-claim Kind P core (H2 or H4) could qualify. It also needs H2 and H4 to share one formal object and a "linked" J1 ruling, and H1 to fail CA-8 permanently (D0, frozen) |
| NCTF is narrow while S5c is prohibited | **CONDITIONAL.** Correct, and **narrower than stated**: per v1.8 §11.3 (d), any registered Kind P candidate cannot be disposed with today's instruments except through ground (ii) or a vacuous disposal. So registering one blocks NCTF unless V-11 adds a disconfirmation criterion |
| V-2 should be decided together with an S5c intent | **Recommendation** (sound as a dependency; not a rule) |

### C.5 Q-M2 conclusion 1 ("all five readings rest on agent classifications")

**VERIFIED** against the extraction code and the P3b procedure (§9.8, §11.4, §14.3). The mechanical parts (A.10 dates, the stage-1 search, the hub flag) are correctly listed. Nuance: H4's birth *kinds* originate from a mechanical P2b detector before agent confirmation, so H4 is partly mechanical.

### C.6 The numerical correction

- E[K] = 999·2⁻ᵐ. At m = 10: 999/1024 = **0.976**. K is approximately Poisson(0.976), so P(K = 0) ≈ 0.38 and E[p] = (1 + 0.976)/1000 ≈ **0.0020**. At m = 14: E[K] = 0.061, P(K = 0) ≈ 0.94. **VERIFIED by hand.**
- Refinement: "p ≈ 0.002" is the *expected* p; p ranges over 0.001, 0.002, … with these probabilities.
- The case T = m (all relations forward) is the extreme. With any fixed forward share above ½, rejection probability tends to 1 as m grows. That is consistency, not certainty at a given m.
- The α/3 = 0.0167 comparison is correct for the NR-P level. The rejection decision itself uses the Holm-adjusted p (at most 3p).

---

## D. Principal decision briefs

Each brief: question · meaning · options · recommendation · trade-off · evidence · dependencies · consequences · authority boundary · **proposed governance-log text (not recorded)**.

### D-1. DS-3: the authoritative branch and governance-log copy
1. **Question.** Which branch holds S-Series records and the manuscript, and which governance-log copy is authoritative?
2. **Meaning.** It fixes where authoritative work lives. Today the log and TODO are on a product-development branch, and the frozen instruments on the S-Series branch. The two log copies diverge.
3. **Options:**
   - (a) the S-Series branch, plus a sync of the log to it;
   - (b) PROD `election-flow-updated` as the home of the log, with the manuscript there too;
   - (c) a dedicated new S-Series records branch.
4. **Recommendation:** decide the **log copy first**: PROD, as the copy holding all entries. Then choose the branch: (a) or (c) for the research files; for the manuscript, follow the Principal's earlier chat instruction ((b)) unless replaced.
5. **Trade-off:**
   - (a)/(c): cleaner separation, but one sync step and a risk of a third copy;
   - (b): the log stays visible, but research history mixes with product work.
6. **Evidence.** B-3, B-4 (VERIFIED divergence; authority inferred, not recorded).
7. **Dependencies.** Precedes every commit (D6, documentation fixes).
8. **Consequences.** It permits authorized commits on the chosen branch. It prohibits nothing new.
9. **Boundary.** No move, merge, switch or commit is authorized by the decision itself.
10. **Proposed log text:** "Human act: the authoritative S-Series governance log is `<path>` on `<branch>`; the copy on `<other>` is non-authoritative as of `<sha>`. S-Series records and the theory manuscript are committed on `<branch>`. No commit, merge or move is authorized by this entry."

### D-2. DS-4a: documentation corrections only
1. **Question.** May the Professor correct documentation of known limitations? That covers: the PO-01 note; manuscript Ch. 6/8; the README; proposed TODO lines for the Researcher.
2. **Meaning.** Accurate descriptions of what is already known. No method change.
3. **Options:** authorize all / some / none.
4. **Recommendation:** authorize the Professor-workspace files (§F); leave TODO edits to the Researcher.
5. **Trade-off:** minimal effort, versus withdrawn claims remaining visible if declined.
6. **Evidence.** C.2–C.3; B-11.
7. **Dependencies.** None for the edits; DS-3 for any commit.
8. **Consequences.** The edits are permitted; nothing else.
9. **Boundary.** No frozen file, TODO or governance-log edit; no commit.
10. **Log text:** optional ("record of documentation corrections; no decision").

### D-3. DS-4c (new): confirmatory execution parameters
1. **Question.** What are B and the analysis seed for H1/H2/H4, and is "exactly one recorded run" a rule?
2. **Meaning.** The 5% guarantee requires these to be fixed before the data. Today the record conflicts on B (9,999 in Freeze 1 vs 999 in later acts and the code) and has no seed or single-run rule.
3. **Options:**
   - (a) keep Freeze 1 (B = 9,999; a CSPRNG seed drawn and recorded at a stated act);
   - (b) declare B = 999 for all confirmatory tests, consistent with the H2 NR-P threshold (GL-0135 #1) and the frozen code default, plus a recorded seed (drawn, or the code default) and a single-run rule;
   - (c) defer (A3 stays unmet; the confirmatory guarantee cannot be claimed).
4. **Recommendation:** (b), recorded as part of the RQ-1 reconciliation of Freeze 1 with prereg_v2.
   - Reason: the H2 NR-P threshold (m ≥ 58) was computed at B = 999, so (a) would require recomputing it.
   - Counterargument: Freeze 1's immutability clause, which is exactly why the reconciliation must be explicit.
5. **Trade-off:** (a) honours Freeze 1 literally but reopens a reviewed threshold; (b) is consistent with later acts but needs a disclosed re-registration statement.
6. **Evidence.** B-7, B-8 (VERIFIED).
7. **Dependencies.** RQ-1 (Freeze 1 reconciliation). Precedes any confirmatory run (G5 / S5 analysis).
8. **Consequences.** A3 becomes a recorded rule. Seed shopping becomes a recorded deviation.
9. **Boundary.** It changes no code, authorizes no run, and does not unfreeze prereg_v2.
10. **Proposed log text:** "Human act: confirmatory execution parameters for prereg_v2 (sha `827ce461…`): B = `<n>` for H1, H2, H4; analysis seed `<value>` (`<source>`); exactly one recorded run before which no confirmatory output is inspected. This reconciles Freeze 1 (GL-0094, B = 9,999) with GL-0122/0135 as a disclosed re-registration before any S5 data. No run is authorized."

### D-4. DS-5: independent review of Proposition 1 and the H2 points
1. **Question.** Should a human statistician or a different model family review Proposition 1, A1–A4, the Holm scope, and the H2 dependence point (C.2)?
2. **Meaning.** The core error guarantee has only same-model checks.
3. **Options:** a human expert / a different model family / defer.
4. **Recommendation:** a human expert, if available. Otherwise a different model family, with prompts not written by Session A or Session B.
5. **Trade-off:** cost and lead time, versus a guarantee resting on same-family agreement.
6. **Evidence.** C.2 (DERIVED); Session A's review (REPORTED).
7. **Dependencies.** Best after DS-4c, so the reviewer sees fixed parameters. Independent of DS-1.
8. **Consequences.** Preparing and sending a review packet is permitted.
9. **Boundary.** No study, no code change.
10. **Proposed log text:** "Human act: independent review of PO-01 Proposition 1 (sha `4ea81900…`) and the H2 independence question commissioned from `<reviewer>`; read-only; no study authorized."

### D-5. DS-1: N-a/C-4 (Q-a)
1. **Question.** Is a second linkage judgement binding (option (a)), or is the audit gap accepted as a final, disclosed limitation (option (b))? Deferral is "not an option for adoption" (package v2 §3).
2. **Meaning.** A single wrong "linked / not linked" ruling can produce a false definite verdict. Under (b) that path stays open and is disclosed; your own review at J4 is the only check. Under (a), a false definite verdict needs both rulings to agree on the same wrong value.
3. **Options.** (a), with sub-choices a2 (who assigns auditors) and a5 (how completion is handled); (b).
4. **Recommendations conflict.** Package v2: (a); the TODO and Session A: (b).
   - *Professor:* (a) is safer **only to the extent the two judgements are independent**.
   - Correlated errors weaken it. If the same model rules and audits, a shared misreading produces the same wrong value, and the (1 − δ)ᵐ illustration (package v2) assumes independence.
   - Blinding (the auditor not seeing the first value) is necessary but does not create independence.
   - Disagreement moves the outcome only towards OPEN, under conditions C1–C4 (Lemma A, same-model reviewed).
   - **Neither option protects candidate-theory assembly** (package v2 counterexample).
   - (a) is also **not yet specifiable**: the placement depends on the G0/G1 premise, which collides with V-13/O1.
5. **Trade-off:** (a) costs double judging effort, makes NCTF harder and gives an auditor a lever towards OPEN; (b) accepts a residual false-definite path.
6. **Evidence.** S-NAC4 §1–5, S-NAC4P §8–9 (VERIFIED as text); Lemma A (REPORTED, same-model).
7. **Dependencies.** Q-c (who judges) for both options; if (a), V-13/O1 first.
8. **Consequences:** (b) finalizes a limitation and a disclosure slot; (a) authorizes v1.9 drafting plus a fresh review.
9. **Boundary.** Neither adopts v1.8 or authorizes any data access.
10. **Proposed log text:** "Human act: Q-a = `<binding / accepted limitation>`; Q-c = `<who rules and audits>`; recorded before any S5 data; v1.8 not adopted by this entry."

### D-6. DS-2: V-1
1. **Question.** Adopt **the outcome vocabulary** (ROBUST / NCTF / OPEN with codes), or adopt V-1 **as part of a complete verdict rule**?
2. **Meaning.**
   - Adopting the word OPEN lets reports say "insufficient evidence" without forcing a verdict.
   - Adopting the full rule fixes the criteria for every outcome, which needs V-2…V-13 and Q-a.
3. **Options:** (i) vocabulary alone; (ii) part of the full adoption act; (iii) two outcomes only; (iv) defer.
4. **Recommendation:** (i) now, explicitly labelled "vocabulary only; the criteria are not adopted"; (ii) later.
5. **Trade-off:** (i) gives clarity without commitment, at some risk that the word is mistaken for an adopted procedure; (iii) forces unearned verdicts.
6. **Evidence.** S-OP l.12 (two outcomes); v1.8 §12; S-VCR l.750 vs v1.8 §22.4 (VERIFIED conflict on readiness).
7. **Dependencies.** (i): none. (ii): DS-1 and V-2…V-13.
8. **Consequences.** Under (i), OPEN becomes an adopted *term*. The present programme status is then "OPEN — INSUFFICIENT EVIDENCE" as a label, not as the result of a computed procedure.
9. **Boundary.** No criteria and no studies are authorized.
10. **Proposed log text:** "Human act: V-1 (vocabulary only): the programme outcome vocabulary is ROBUST THEORY FOUND / NO COHERENT THEORY FOUND / OPEN — INSUFFICIENT EVIDENCE with reason codes. The criteria of VERDICT-CRITERIA-PROPOSAL v1.8 are not adopted."

### D-7. V-2 together with an S5c intent
1. **Question.** Is the Principal content that, under V-2 (ii) with S5c prohibited, ROBUST is impossible by design? Or will S5c become authorizable (ADD §6 ruling / E7), or is V-2 (i) acceptable?
2. **Meaning.** Under V-2 (ii) the programme can claim a robust theory only after a successful out-of-sample prediction. If prediction scoring stays prohibited, it can only report "no coherent theory" (narrowly) or "open".
3. **Options:**
   - V-2 (ii), with an intent to rule on S5c later;
   - V-2 (ii), knowingly ROBUST-impossible;
   - V-2 (i), which departs from the order of the protocol; Kind-P-only cores (H2/H4 at most), weakened by Q2 and needing the C1 model;
   - defer (V-2 must precede any S5 data).
4. **Recommendation.** No recommendation on the S5c intent; it is a scientific-policy choice. I recommend deciding V-2 **only together with** a stated S5c intent.
5. **Trade-off:** scientific strictness (out-of-sample corroboration) versus the possibility of any positive result.
6. **Evidence.** C.4 (CONDITIONAL deduction from v1.8); GL-0045 (VERIFIED prohibition).
7. **Dependencies.** V-3 (k_min) affects the V-2 (i) branch; V-11 affects NCTF.
8. **Consequences.** It determines which outcomes are attainable at all.
9. **Boundary.** No unseal, no scoring.
10. **Proposed log text:** "Human act: V-2 = `<option>`; statement of intent on S5c: `<…>`; recorded before any S5 data."

### D-8. DS-8: manuscript architecture
1. **Question.** Keep the single manuscript file until chapter-level review is commissioned (the charter's position), or migrate now?
2. **Recommendation:** keep the single file. Revisit when DS-5 or a chapter review is commissioned.
3. **Trade-off:** less tooling now, but no automated citation checks.
4. **Boundary.** No restructuring.
5. **Log text:** not needed (editorial).

### D-9. DS-6 and DS-7: specifications and study designs
1. **Question.** May the Professor and Researcher **prepare**, as documents only:
   - (6) a formal specification of the v1.8 outcome rule, starting with the reachability table of C.4;
   - (7) designs for V-5 (θ_D adequacy), V-9 (generic generation sensitivity), the C1 rater model and an FCA pre-registration?
2. **Meaning.** Preparation commits no resources. Execution needs separate acts. A formal model can expose logical counterexamples, but it cannot by itself show that it encodes v1.8 faithfully; the encoding needs review.
3. **Recommendation:** authorize preparation only, after DS-1 (which shapes the rule). C1 design first, since Q-M2 makes it a precondition.
4. **Boundary.** No model checker run, no simulation, no tooling in the repository.
5. **Proposed log text:** "Human act: preparation only, as documents, of `<items>`; no execution, tool run or study authorized."

**Dependency order.**
1. DS-3 (log copy, then branch).
2. DS-4c + RQ-1 (parameters).
3. DS-5 (review).
4. DS-1 (Q-a, with Q-c).
5. DS-2 (i).
6. V-2 with the S5c intent (plus V-3).
7. DS-6/DS-7 (preparation).

DS-4a and DS-8 are independent.

---

## E. Independent-review requests (proposed; none sent)

| # | To | Request |
|---|---|---|
| REQ-4 | Researcher | Verify Q2′: (1) DR-01 in the agent-facing contract text (not only the verifier); (2) whether any R7 module checks the timeline list order against A.10 dates; (3) whether agents receive A.10 metadata when ordering; (4) all routes that create `contradicted_by` entries |
| REQ-6 | Researcher | Reconcile B and the analysis seed: Freeze 1 (B = 9,999, a CSPRNG seed at freeze) vs GL-0122/0135 and the code defaults. Is the H2 NR-P threshold (m ≥ 58) valid at the B that will actually be used? |
| REQ-5 | Researcher | Provide the RQ-1/RQ-2 answers in an authorized file |
| IR-1 | independent statistician | (a) Proposition 1 under A1–A4; (b) whether H2's i.i.d. null makes a rejection interpretable as π > ½ under within-object dependence; (c) whether Q2′ is correct and what H2 can license; (d) the Holm scope statement |

---

## F. Proposed corrections (diffs only; NOT applied)

**F-1. `THEORY-READINESS-ASSESSMENT.md` §4, design-constants row.** The B and seed values are not settled for H1/H2/H4.
```diff
-| Design constants (α = 0.05, families, B = 999, π = 0.7, h₀, e₀, s₀, D = 12) | design | **Supportable now** | GL-0118…0137 | none (frozen) | none |
+| Design constants (α = 0.05, families, π = 0.7, h₀, e₀, s₀, D = 12; B = 999 for H5 and the H2 NR-P threshold) | design | **Supportable now** | GL-0118…0137 | none (frozen) | none |
+| B and the seed for H1/H2/H4; single run | execution parameters | **Unresolved: conflicting record** (Freeze 1: B = 9,999 and a CSPRNG seed; code default 999/20260927; no seed or single-run rule recorded) | GL-0094; B1 l.68; GL-0122/0135 | — | DS-4c, RQ-1 |
```

**F-2. Same file, §6.2 (F1).** The predecessor is the previous list element.
```diff
-- **(F1)** By DR-01, every CONTRADICTS timeline point s yields a relation (s, pred(s)), where pred(s) is the immediately preceding point **in the agent's timeline order** τ.
+- **(F1)** By the R7 verifier rule DR-01, every CONTRADICTS timeline point s must have the entry (s, pred(s)), where pred(s) is the previous element of the agent's timeline **list** (`p3b_s5_r7_reconstruction.py` l.89–92).
```

**F-3. Same file, §6.2 consequences 1 and 3.** Withdraw "by construction"; correct the second route.
```diff
-   - If agents order mostly from the same A.10 metadata, π ≈ 1 on R_C by construction.
+   - If the agent's list order agrees with A.10 on most adjacent dated pairs, the R_C forward share is correspondingly high (empirical; unknown).
...
-3. Relations entered by other routes (e.g. a `semantic_evidence.d4` CONTRADICTION row; R6 root-cause doc l.222) are not covered by Prop. Q2.
+3. The verifier also admits any other agent-entered pair of distinct timeline points (l.111–118); a d4 entry is required only for CONTESTED/ROW-1 objects. Such relations are not covered by Prop. Q2.
```

**F-4. Same file, §6.3 and §10.** Qualify the C1 statement.
```diff
-The C1 observation-process model is therefore a precondition of **any** Kind-P-based ROBUST, not an optional refinement.
+Under v1.8 CA-8 as written (a proposal), the C1 observation-process model is a precondition of any Kind-P-based ROBUST (V-2 (i)). Prop. Q2 adds that C1 may not be sufficient for H2.
```

**F-5. Same file, §5.2, V-2 (i) row.** Add the k_min premise and the NCTF block.
```diff
-| V-2 (i), S5c prohibited | **Only** through a Kind-P-only core. With k_min = 2 that core is exactly **{H2, H4}**
+| V-2 (i), S5c prohibited | **Only** through a Kind-P-only core. **If k_min = 2 (V-3, open)**, that core is exactly **{H2, H4}** (with k_min = 1, a single H2 or H4 core); a shared formal object and a "linked" J1 ruling are also required
```
Add to the V-2 (ii) row: "and any pre-registered Kind P candidate blocks NCTF unless V-11 adds a disconfirmation criterion (v1.8 §11.3 d)".

**F-6. Same file, §11 item 2.**
```diff
-2. **The A3 execution condition (one run, B = 999, seed 20260927).** Should it be recorded in the governance log (DS-4a)?
+2. **The confirmatory execution parameters** (B, analysis seed, single-run rule): the record conflicts with Freeze 1. Decide them first (DS-4c, with RQ-1); then record them.
```

**F-7. Same file, new §6.4.** Add: "H2's null assumes independent orientations; relations within one object share an ordering, so a rejection may reflect dependence rather than π > ½ (DERIVED; IR-1 b)."

Corrections F-1…F-7 add to, and do not replace, the PO-01 and manuscript diffs already proposed in the assessment's §8. These remain valid, except that the manuscript Ch. 6.8 item L-S9 should read "B and the seed unresolved (Freeze 1 conflict)" rather than "B = 999, seed 20260927".

---

## G. Authorization and dependency matrix

| Work | Status now |
|---|---|
| Read-only verification of authorized records and code; deductive analysis; decision materials | **permitted** (done here) |
| Verdict-criteria drafting under G-LOG-0140 (documents; review) | permitted for the Researcher's lane. **Not** tooling, studies or new mechanisms |
| Applying any diff in §F, or the assessment's §8 diffs | **needs DS-4a** |
| Recording any governance entry | **Principal act** (recorder designated by the Principal) |
| Fixing B, the seed and the single-run rule | **needs DS-4c** (+ RQ-1) |
| Sending REQ-4/5/6 or IR-1 | **needs the Principal's go-ahead** (IR-1 = DS-5) |
| Formal specification or study designs (documents) | **needs DS-6/DS-7**, preparation only |
| Model checking, simulation, enumeration (e.g. n = 5), any study | **separate execution act** |
| Commits, pushes, merges, branch changes | **needs DS-3** + a per-commit authorization |
| Corpus, S5, hold-out or sealed access; early reads | **prohibited** (O1; T-0/T-1 pending) |
| Run-3 | the Principal schedules it; this session stops for the quiet period |

---

## H. Final status

**Inspected:**
- the PROD governance log (head, entries 0089, 0094, 0104, 0106, 0117, 0122, 0124, 0135–0140; searches for the seed, B and single-run terms);
- the worktree log length and head;
- the TODO (head; RI-1 lines);
- prereg_v2.py, lines 260–365;
- `p3b_s5_r7_reconstruction.py`, lines 60–165;
- `p3b_s5_verify.py` (timeline-check lines found by search);
- the R7 design-repair record l.45 and the annex clause audit l.123–149 (by search);
- v1.8 §3–§6, §10–§12, §22.4;
- N-a/C-4 package v2 and placement sheet r2;
- the review charter and Session A's report;
- workspace file hashes.

**Done:** this new file only. **No existing file modified.**

**Unverified:**
- Session A's exact Clopper–Pearson bounds and its n ≤ 4 reproduction (REPORTED);
- the agent-facing DR-01 contract text;
- any R7 module beyond those inspected;
- the live Run-3 preflight state;
- the content of Session A's "last report".

**Confirmations.** No restricted corpus, S5, hold-out, sealed, ledger or batch-input material was accessed. No study, simulation, enumeration, model check or run was performed. No commit, push, merge or branch change. No governance record changed. No Principal decision taken or recorded.

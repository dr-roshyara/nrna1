# Principal Decision Packet: S-Series KnowledgeOS, 17 October 2026

| | |
|---|---|
| **From / to** | Session B (Professor) → the Principal |
| **Kind** | Decision packet. **It decides nothing and records nothing.** The wording in §D is draft text only. |
| **Baseline** | `TRA-CRITICAL-REVIEW-2026-10-17.md` (cited as **CR**), with `THEORY-READINESS-ASSESSMENT.md` (**TRA**) behind it |
| **State re-checked today (read-only)** | governance-log head G-LOG-0140 (sha `04cb0c19…`); TODO unchanged (sha `e7d35147…`); PROD on `election-flow-updated`; S-Series worktree at `725bfa1e3` |
| **Labels** | **VERIFIED** (checked against a source) · **REPORTED** (another session; unchecked) · **DERIVED** (Professor's deduction; same model family; unreviewed) · **CONDITIONAL** (holds only under the stated rules) · **OPEN** |
| **Session A input** | Session A has verified **only** the confirmatory-parameter finding (Decision B). It has not verified the other findings of CR; those are assessed here against Session B's own reading of the authorized sources and remain DERIVED where so marked |
| **Naming** | "Decision A … F" here are packet labels. **They are not** the long-standing "Decision A" on ARCH v1.2's authority (TODO l.353), which stays open and separate |

## 0. Consistency check of the critical review (CR)

| # | Finding | Effect | Handling |
|---|---|---|---|
| K-1 | CR D-3 recommended **B = 999**, and TRA §11 item 2 proposed recording "one run, B = 999, seed 20260927" as an execution condition. Both select values that no governance act has decided | governance | **both withdrawn.** No file needs correcting: the proposed TODO change was never applied. Decision B presents the options without a value recommendation |
| K-2 | CR §C.3 labels the star-scheme counterexample **VERIFIED**. It is DERIVED (Professor's computation) plus REPORTED (Session A's recomputation); no independent review | terminology | read it as DERIVED + REPORTED. CR is not edited |
| K-3 | CR §C.2: under within-object dependence, rejection rates "exceed α". That holds for **positive** dependence (approximately, not exactly); negative dependence lowers them | precision | read it as "may differ from α; exceed it under positive dependence" |
| K-4 | Q2′ scope, H3/H5 = unknown, reachability = CONDITIONAL, the B/seed conflict: all consistent across CR | — | none |

No other material inconsistency was found.

---

## A. Executive decision summary

Three decisions unblock responsible progress. Three more define what the research can conclude at all.

**Unblocking decisions:**
1. **Decision A (DS-3).** Which record of your decisions counts, and where the work is filed.
2. **Decision B (DS-4c).** The settings of the main statistical test. They were never consistently fixed, and they must be fixed **before** any confirmatory run.
3. **Decision C (DS-5).** Commission an outside expert to check the core statistical guarantee. Two sessions of the same AI agreeing is not independent confirmation.

**Defining decisions:**
4. **Decision D (DS-1, Q-c).** How to guard against a wrong judgement that two claims are "linked".
5. **Decision E (DS-2).** Adopt the three outcome labels (vocabulary only).
6. **Decision F (V-2 + S5c).** Whether a positive "robust theory" result is meant to be possible.

**Nothing here authorizes:** running anything, reading restricted data, committing, or sending review requests.

## B. Principal decision table (dependency order)

### Decision A: DS-3, the authoritative record and branch

1. **What must be decided?** (1) Which governance-log copy is authoritative. (2) Which branch holds S-Series records and the manuscript.
2. **In plain terms.** The record of your decisions exists twice, and the copies differ: one ends at decision 140, the other at 110. Nothing written says which counts. Filing work on the wrong branch can mix research records into product code, lose them if that branch is retired, or create a third, divergent "official" copy.
3. **Options:**
   - (a) PROD copy authoritative; research files on `s-series/s5-r7-v29-repair`;
   - (b) PROD copy authoritative; records and manuscript on `election-flow-updated`;
   - (c) a new dedicated S-Series records branch.
4. **Recommendation.** Make the **PROD copy authoritative**: it holds all 140 entries, while the other stops at 110. The branch choice is yours. (b) matches your earlier chat instruction for the manuscript. (a) or (c) separate research from product work better.
5. **Risks:** (a)/(c) need a sync step and risk a third copy; (b) mixes histories.
6. **Evidence.** The divergence is VERIFIED (CR B-3). That PROD is authoritative is REPORTED and inferred (CR B-4).
7. **Uncertain.** None about the facts; the choice is a policy one.
8. **Dependencies.** None. It blocks every commit.
9. **Authorizes / does not.** It settles where records live. **It does not authorize** any commit, push, merge, move or branch switch; each needs its own authorization.

### Decision B: DS-4c, the confirmatory test settings (together with RQ-1)

1. **What must be decided?** For H1, H2 and H4: the number of random reshuffles B, the analysis seed, and whether exactly one recorded run counts. Also how this is reconciled with Freeze 1 (RQ-1).
2. **In plain terms.** The main statistical guarantee is that, across the three confirmatory hypotheses, there is at most a 5% chance of falsely reporting a pattern. It holds only if these settings are fixed in advance and the test is run once. Otherwise someone could re-run until a favourable result appears.
   - The record is inconsistent. The original design freeze says B = 9,999, with a seed drawn at the freeze. Later decisions say B = 999 for one hypothesis (H5) and for H2's power threshold.
   - No seed and no one-run rule are recorded. The values 999 and 20260927 are **code defaults, not approved settings**.
   - This is a pre-registration issue, not a documentation fix.
3. **Options:**
   - (a) Freeze 1 as written: B = 9,999 and a seed drawn and recorded at a stated act; the H2 power threshold (≥ 58 relations, computed at B = 999) is then re-derived for B = 9,999.
   - (b) A disclosed reconciliation: one stated B for all three tests, a recorded seed, and a one-run rule, recorded as a departure from Freeze 1 before any S5 data.
   - (c) Defer. No confirmatory run may then take place.
4. **Recommendation.** No value recommendation. The Professor recommends that **whichever B is chosen is stated once, for all three tests, with a recorded seed and a one-run rule, and with the relation to Freeze 1 written out explicitly** (RQ-1).
   - (a) honours the original freeze literally but reopens a reviewed power figure.
   - (b) keeps the reviewed figure but must openly disclose the departure.
5. **Risks:** (a) means re-derivation work and possibly a different power threshold; (b) a departure from the freeze; (c) the guarantee cannot be claimed.
6. **Evidence** (VERIFIED by Session A and by Session B):

   | Record | What it says |
   |---|---|
   | B1 v2 l.68 (Freeze 1 text) | "B = 9,999 permutations per test; a seed drawn at the freeze from the OS CSPRNG, recorded with the instrument's sha256" |
   | GL-0094 (Freeze 1 act) | freezes B = 9,999; lists "seed" under **Not authorized**. The analysis seed was never drawn: no later entry records one |
   | GL-0103 (Freeze 2) | its seed is for the **audit sample**, not the permutation analysis |
   | GL-0122, GL-0124 | B = 999 **for H5 only** |
   | GL-0135 #1 | uses B = 999 for the H2 NR-P threshold (≥ 58 dated relations) but **does not decide B for confirmatory H2** |
   | `prereg_v2.py` l.309, l.361–362 | B = 999 and seed 20260927 are **command-line defaults**, not governance decisions |

   **Conclusion:** B, the analysis seed and the single-run rule are **unresolved** for H1, H2 and H4.

   **What depends on B:**
   - **Validity.** The claimed validity proof (Proposition 1, under A1–A4) holds **for any B fixed in advance**. B does not affect validity, provided it and the seed are fixed before the data and one run is reported (A3).
   - **H2 power.** The power calculation and its threshold (≥ 58 relations for NR-P) were computed **at B = 999**. Choosing another B leaves validity intact but requires the threshold to be recomputed, through an authorized computation.
7. **Uncertain.** The H2 NR-P threshold at any B other than 999 (not computed).
   - **If deferred:** no confirmatory run can take place, and the 5% guarantee cannot be claimed for any run.
8. **Dependencies.** RQ-1. Must precede any confirmatory execution, and preferably the external review.
9. **Authorizes / does not.** It fixes the settings. **It does not authorize** a run, a code change, or reopening prereg_v2.

### Decision C: DS-5, independent scientific review

1. **What must be decided?** Whether to commission a qualified human statistician, or a genuinely independent reviewer, and who.
2. **In plain terms.** The core proof has been checked only by two sessions of the same AI model family, with prompts written within the project. Shared blind spots survive such checks. Those checks have found errors, but they cannot certify correctness.
3. **Options:** a human statistician; a reviewer of a different model family, with prompts written by neither session; defer.
4. **Recommendation:** a human statistician, if available.
5. **Risks:** cost and delay, versus a guarantee resting on same-family checks.
6. **Evidence.** CR §C.2; Session A's review (REPORTED).
7. **Uncertain.** Whether Proposition 1 survives.
8. **Dependencies.** Best after Decision B, so the reviewer sees fixed settings.
9. **Authorizes / does not.** It authorizes preparing and sending one review request. **It does not authorize** studies or code changes.

**Scope of the review request** (drafted; **not sent**):
- (1) Proposition 1 and the Holm guarantee under A1–A4. A1 for H4 covers all dated birth keys; A3 is the settings from Decision B.
- (2) The parameter conflict, and whether the pre-registration is adequate once Decision B is made.
- (3) H2's independence assumption, kept as three separate questions:
  - validity for the stated null (independent fair orientations);
  - what a rejection can mean when orientations within an object are dependent;
  - the power calculation, which assumes independence.
- (4) Proposition Q2′ and the limits of what it supports.

### Decision D: DS-1, the audit gap in linkage judgements (N-a/C-4), with Q-c

1. **What must be decided?**
   - **Q-a:** option (a), a binding second judgement with a specified disagreement rule; or option (b), accepting and disclosing the single-judgement risk.
   - **Q-c:** who makes and who audits these judgements (human, model or mixed).
2. **In plain terms.** The verdict depends on judgements that pairs of claims are "linked". One wrong judgement can produce a false "robust theory" or a false "no coherent theory".
3. **Options.** (a); (b). Deferral is "not an option for adoption" (package v2 §3): deciding after data would be changing the rules after seeing results.
4. **Recommendation.** The existing materials conflict: package v2 recommends (a); the TODO l.161 and Session A recommend (b). The Professor does not resolve this, and states the trade-off:
   - **(a)** costs about double the judging effort and makes "no coherent theory" harder to reach. It may reduce false definite verdicts, but only to the extent the two judges' errors are unrelated.
     - Judging separately is not the same as judging independently. If the same model does both, a shared misreading produces the same wrong answer twice.
     - Hiding the first answer from the second judge (blinding) is necessary, but not sufficient.
     - A disagreement moves the result towards "open" (Lemma A, same-model reviewed).
     - Who assigns the judges, and when, must be fixed in advance.
     - **(a) is not yet fully specified:** where the second judgement sits depends on an unsettled premise (G0/G1), which collides with the V-13/O1 timing.
   - **(b)** is simple and final, but leaves the documented paths to a false definite verdict. They are disclosed; your post-result review is the only check.
   - **Under both:** the step that assembles candidate theories is not protected.
5. **Risks:** (a) cost, a lever for an auditor towards "open", and remaining unspecified; (b) a known residual false-verdict risk.
6. **Evidence.** VERIFIED as document text: package v2 §1–§5; placement sheet r2 §8–§9; v1.8 §22.4.
7. **Uncertain.** The real error rates and how correlated the judges' errors are; neither is known.
8. **Dependencies.** Q-c (both options). Under (a), V-13/O1 first.
9. **Authorizes / does not.** (a) authorizes drafting v1.9 plus a fresh review; (b) authorizes recording a final limitation and a disclosure slot. **Neither adopts v1.8 or authorizes data access.**

### Decision E: DS-2, the V-1 outcome vocabulary

1. **What must be decided?** Whether to adopt the labels ROBUST THEORY FOUND / NO COHERENT THEORY FOUND / OPEN — INSUFFICIENT EVIDENCE.
2. **In plain terms.** The labels let reports say "insufficient evidence" without forcing a yes or no. **Adopting the words is not adopting the rules** that decide which label applies.
3. **Options:** (i) labels only; (ii) as part of the full v1.8 rule (blocked by Decisions D and F and V-2…V-13); (iii) two labels only; (iv) defer.
4. **Recommendation:** (i), with the record stating that the v1.8 criteria remain unadopted.
5. **Risks:** (i) a label mistaken for an adopted procedure, which the wording must prevent; (iii) forces unearned verdicts.
6. **Evidence.** The operating protocol names two outcomes (S-OP l.12). v1.8 §12 and §22.4, and the review record l.750 (readiness conflict), are VERIFIED.
7. **Uncertain.** None for (i).
8. **Dependencies.** None for (i).
9. **Authorizes / does not.** It adopts the terms. **It does not authorize** calculating a verdict, running studies, or adopting criteria.

### Decision F: V-2, together with an S5c intent (and V-3)

1. **What must be decided?** Which evidence must support a "robust theory" verdict (V-2), stated together with your intent on hold-out scoring (S5c), plus the minimum core size (V-3).
2. **In plain terms.** Under the recommended V-2 (ii), a positive conclusion requires a successful prediction tested on held-back material. That testing (S5c) is currently prohibited. **If the required route to positive evidence is closed, a positive conclusion cannot be reached.** Only "no coherent theory" (narrowly) or "open" remain.
3. **Options:**
   - V-2 (ii), with an intent to decide S5c later;
   - V-2 (ii), knowingly ruling out ROBUST;
   - V-2 (i) (a departure from the protocol's order), where robust cores could come only from the pre-registered hypotheses;
   - defer (V-2 must still precede any S5 data).
4. **Recommendation:** decide V-2 only together with a stated S5c intent. **No recommendation on whether S5c should become authorizable**; that is your scientific-policy decision.
5. **Risks:** an unstated intent silently rules out ROBUST. V-2 (i) narrows any positive result to the pre-registered hypotheses, and H2 carries the Q2′ caveat.
6. **Evidence.** **CONDITIONAL** on the written v1.8 proposal and the current prohibition (GL-0045, VERIFIED); CR §C.4.
7. **Uncertain.** Under V-2 (i), the core depends on V-3: k_min = 2 gives at most {H2, H4}; k_min = 1 allows a single claim. NCTF is blocked by any registered pre-registered-hypothesis candidate unless V-11 is added.
8. **Dependencies.** V-3; V-11.
9. **Authorizes / does not.** It fixes which outcomes are attainable. **It does not authorize** an unseal or scoring.

### B.1 Deferral consequences and decision owner

All decisions are **the Principal's**. The Professor and the Researcher advise; the Principal's designated recorder enters the human acts in the authoritative governance log.

| Decision | If deferred |
|---|---|
| A (DS-3) | nothing can be committed safely; untracked work stays unprotected; the two log copies can drift further |
| B (DS-4c + RQ-1) | no confirmatory run; the confirmatory guarantee cannot be claimed; the external review lacks a fixed target |
| C (DS-5) | the core guarantee rests on same-family checks only; this is acceptable pre-data, but not before confirmatory results are reported |
| D (DS-1 + Q-c) | the verdict rule cannot be adopted. Deferral past the first S5 data is excluded (it would be a rule change after results) |
| E (DS-2) | reports must avoid the word OPEN as a formal outcome; no other effect |
| F (V-2 + S5c, V-3) | the attainable outcomes stay undefined. They must be decided before any S5 data |

## Secondary decisions (not blocking now)

| Item | What | Depends on | Boundary |
|---|---|---|---|
| **DS-4a** | Corrections to the Professor's workspace documents only. **Independent:** in the PO-01 note, withdraw "conservative" and the Wald intervals, and correct the proof route, the H5 statistic and the H3/H5 table row (TRA §8); in the TRA and CR, the scope and wording fixes (CR §F-2…F-5, F-7; K-2, K-3). **Wait for Decision B:** CR §F-1 and F-6, and manuscript Ch. 6.8 item L-S9 (the parameter text). Researcher's file, proposed only: the stale RI-1 TODO lines (GL-0104/0106) | Decision B, for the parameter items only | no TODO, governance-log or frozen-file edits by the Professor; no commit |
| **DS-8** | Keep the single manuscript file until chapter-level review is commissioned | independent | no restructuring, no tooling |
| **DS-6** | Prepare a formal specification of the proposed verdict rules (documents only) | after Decision D | no model checker run; a specification can expose logical counterexamples but cannot prove that it matches v1.8 |
| **DS-7** | Prepare study designs: V-5, V-9 (generic only until the charter), the C1 rater model, the FCA pre-registration | V-9 after the charter or V-13; others independent | **design documents only**: no simulation, enumeration, training, corpus access or study |

## C. Scientific risk register

| Risk | Known | Unknown | Cannot be claimed now | Source |
|---|---|---|---|---|
| Parameter conflict (B, seed, one run) | the record conflicts; no seed or one-run rule (VERIFIED) | the H2 power threshold at another B | that the confirmatory guarantee is in force as executed | CR B-7, B-8 |
| H2 measurement validity | on the predecessor-rule subset, "forward" = list-order vs file-date agreement (DERIVED from VERIFIED code; CONDITIONAL) | the subset's share; the agreement rate | that H2 is invalid as a whole, or what H2 will show | CR §C.1 |
| H2 dependence | the null assumes independent orientations; relations within an object share an ordering | the sign and size of the dependence | that a rejection means a forward share > ½; that the power figure applies | CR §C.2 (K-3) |
| H3, H5 | not exact in general | their false-positive rates | any size claim ("conservative" withdrawn) | CR §C.3 |
| Verdict reachability | — | V-2, S5c, V-3, V-11 | any reachable verdict other than OPEN; ROBUST impossible under V-2 (ii) + S5c prohibited is **CONDITIONAL** | CR §C.4 |
| Review independence | all checks are same-model-family | — | independent validation of any proposition | CR §C.2 |

## D. Proposed decision-record wording (DRAFT; not recorded, not adopted)

- **Decision A (DS-3):** "Human act: the authoritative S-Series governance log is `docs/knowledgeos/chronological-read/P3B-GOVERNANCE-LOG.md` on `<branch>` (head G-LOG-0140, sha `04cb0c19…`); the copy on `s-series/s5-r7-v29-repair` (head G-LOG-0110) is non-authoritative. S-Series records and the manuscript are kept on `<branch>`. No commit, push, merge, move or branch change is authorized by this entry."
- **Decision B (DS-4c):** "Human act: confirmatory execution parameters for prereg_v2 (sha `827ce461…`): B = `<value>` for H1, H2 and H4; analysis seed `<value>`, `<how fixed>`; exactly one recorded confirmatory run. Relation to Freeze 1 (G-LOG-0094, B = 9,999): `<kept / disclosed departure, reason>`. Recorded before any S5 data. No run is authorized."
- **Decision C (DS-5):** "Human act: independent review commissioned from `<reviewer>`; scope as in the Decision Packet of 2026-10-17, Decision C; read-only; no study authorized."
- **Decision D (DS-1):** "Human act: Q-a = `<binding second judgement under rule … / accepted and disclosed limitation>`; Q-c = `<who rules / who audits>`. Recorded before any S5 data. VERDICT-CRITERIA-PROPOSAL v1.8 is not adopted by this entry."
- **Decision E (DS-2):** "Human act: V-1, vocabulary only: ROBUST THEORY FOUND / NO COHERENT THEORY FOUND / OPEN — INSUFFICIENT EVIDENCE with reason codes. The criteria of VERDICT-CRITERIA-PROPOSAL v1.8 remain unadopted. No verdict calculation or study is authorized."
- **Decision F (V-2 / S5c / V-3):** "Human act: V-2 = `<option>`; intent on S5c: `<…>`; V-3 k_min = `<n>`. Recorded before any S5 data. No unseal or scoring is authorized."

## E. Next-step dependency list

| After | What becomes possible (each step still needs its own authorization) | Still blocked |
|---|---|---|
| Decision A | authorized commits on the chosen branch | everything requiring Decisions B–F |
| Decision B (+ RQ-1) | a stable target for the external review; parameter-related DS-4a corrections | any confirmatory run (also needs S5 data, gates G3–G5 and the adopted criteria) |
| Decision C | prepare and send the review request | studies |
| Decision D | (a): v1.9 drafting + review; (b): record the limitation | adoption of the verdict rule (needs V-2…V-13) |
| Decision E | use of the outcome labels | verdict calculation |
| Decision F | which outcomes are attainable becomes defined | unseal, scoring |
| All of the above + V-2…V-13 + adoption | an adoptable verdict rule | S5 data, which still needs Run-3 → G3 → G5 |

Run-3 is independent of all of these; its scheduling is yours.

## E.1 Prohibited pending authorization

- Run-3; any simulation, enumeration, model check, training or study.
- Access to restricted corpus, S5, hold-out, sealed, ledger or batch-input material (O1, GL-0125).
- Changes to governed files: the manuscript, PO-01 note, TODO, pre-registration, governance log, frozen code.
- Any Git operation: commit, push, merge, branch switch.
- Sending requests to the Researcher or any independent reviewer.
- Recording any decision, or treating code defaults (B = 999, seed 20260927) or silence as approval.
- Acting on project-state sync warnings (session-log or CONTEXT reminders) as if they authorized file changes.

## F. Status statement

- **Verified against sources:**
  - the log head and its divergence;
  - Run-3 deferred, with no PREFLIGHT-OK recorded;
  - 0 of 1,975 measured;
  - the B conflict, and no recorded seed or one-run rule (verified by **Session A and Session B**; the only CR finding Session A has verified);
  - RI-1 reopened (GL-0104/0106);
  - the code facts behind Q2′;
  - the text of the decision packages and v1.8.
- **Derived by the Professor** (same model family; unreviewed): Proposition 1's sufficiency; Q2′ and its interpretation; the H2 dependence point; the reachability statements, which are CONDITIONAL on the draft v1.8.
- **Reported, not verified:** Session A's answers to RQ-1/RQ-2; its exact H3/H5 bounds; its n ≤ 4 reproduction.
- **Require independent review before being relied on:** Proposition 1, Q2′, the H2 dependence and power points.
- **Done:** read-only checks; this packet (one file in the workspace, replacing an earlier draft written in the same task).
- **Not done:** no run, simulation, enumeration or model check; no restricted access; no governed file changed; nothing committed; no request sent; no decision recorded.

*Session B stops here and waits for the Principal's decisions.*

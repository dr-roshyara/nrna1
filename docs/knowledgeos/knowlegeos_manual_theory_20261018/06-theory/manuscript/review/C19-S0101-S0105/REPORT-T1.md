*Commission T reviewer T1 (Opus), returned as hand-back text, verbatim (saved by the Professor at 2026-10-20T22:02:53Z). sha256 of the hand-back text at receipt: `66f6753d9fe3ae9d0df45dd5f5e1ad0f2f6bbe945366481947bea49072dec516` (UTF-8, the text below this line exactly).*

# C19, commission T: report of reviewer T1 (Opus)

Mode: topic review. Object: §9.13 v8, §9.14 v4, §9.15 v5, §9.16 v3, models ce_model v9 and helly_check v4, plus the listed pointers and rows. Every finding comes from the text, from the model source, or from my own probes in `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/` (c19t1_probe.py, c19t1_helly.py, c19t1_coupling.py; all run with `python3 -I -B`, which imports ce_model). Labels: **Fact** = observed in text or run; **Ded** = my derivation; **Op** = my opinion.

## 1. Verdict table

| Concept / claim | Verdict | Reason (scope) |
|---|---|---|
| §9.13 v8: ties unordered. Φ stays monotone, KK = WF on D, the program split of ¬(e∧o) | **VALIDATED** | Order is a constant of the record. De Morgan and distributivity hold in strong Kleene. An atom o ← not o behaves like u ← not u in R3 step 2. Probe P8: on 3,000 crowded-tie records in D, KK, WF and both routes agree everywhere. The text is not updated (T1-12) |
| §9.13 v8: consumption clause (Φ) | **PROVISIONAL** | Within Φ it is sound (check 21). In current-authority it takes an unmarked position on P (T1-04). What "single use" bounds is not stated (T1-18) |
| §9.13 v8: append guard ("single use prevented") | **INVALID as stated** | It refuses on any recorded consumption, effective or not. It has no static counterpart in D. It prevents the entry, not the use (T1-02, T1-03) |
| §9.13 v8: removal acts, removed_by, "values unchanged at every cut" | **INVALID as stated** | Every prefix cut that holds the tombstone but not its removal act is outside D (T1-01). An unauthorized removal still destroys content (T1-05) |
| §9.13 marks, item answers (conservative closure, v7) | VALIDATED | Check 19. Removal acts are in the closure |
| §9.13 marks_act (the Professor's choice) | PROVISIONAL | Sound by the same argument, but not checked (T1-16) |
| §9.13 R1, R2, R3 with ties | VALIDATED (Ded), wording stale | T1-12 |
| §9.14 formula h = max(2, sup \|J\|+t(J)) | VALIDATED for the upper bound | The attainment sentence and two corollaries are wrong as worded (T1-13) |
| §9.14 check 8 / R5 (d): "up- and down-sets of product orders and trees: h = 2" | **INVALID as worded** | With all up- and down-sets, h ≥ 4 on 3×3 and on the 6-node tree. Only principal ones were computed (T1-08) |
| §9.14 coupling: "h rises by at most 1" | **INVALID as worded** | Counterexample: 2 → k (T1-14) |
| §9.15 R4: signed-as-actor, one direction with L2, L3 per entry, L8, K5 | VALIDATED | Proof table below |
| §9.15 criterion: each K and L row meets exactly one clause | PROVISIONAL | K4 meets the kernel clause only under an unstated reading (T1-10) |
| §9.15 G4, K4, K2 (v5) | PROVISIONAL | T1-02, T1-10, T1-17 |
| §9.16 R2 v3: "no merge order can change any value or mark" | **INVALID as stated** | Two removals of one entry: the marks depend on the merge order (T1-06). This is the section's own falsifier |
| §9.16 R3a: per-law classification | PROVISIONAL | The table matches the Principal's reading. The reasons are inconsistent and omit the corpus source cited in R3 (T1-09) |
| §9.16 R4 v3: "(C) with kernel-provided coordination points" | PROVISIONAL | "Coordination needed" is derived. "Kernel-provided" is not (T1-07) |
| §9.16 R5 | INVALID as stated | Past cuts (T1-01) |
| §9.16 R6 (adopted), R7 (adopted provisionally as Int) | VALIDATED as applications of the answers | The R6 table and §9.9 are not updated (T1-11) |

## 2. Findings (ranked)

| ID | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| **T1-01** | A tombstone is an in-place change of an earlier entry, and D requires that its `removed_by` names a removal act in the record. So every prefix cut that holds the tombstone but not the later removal act is **outside D, and Q1 refuses it**. This falsifies: "a prefix of a record in D is in D" (§9.13.6 Lemma D); R5's "every value at every cut is unchanged"; R2 (d)'s "the cut condition is automatic"; §9.16 R1 row "R1 step 5". An unauthorized removal can trigger it too, which is an availability attack | high | **Fact**, probe P1: before removal CE*(c,3) = T; after removal the full record is T and in D, but the prefix without `rm` gives `undefined: tombstone g cites no removal act`. ce_model `in_D` L142–145, `remove` L552–562 | Check a tombstone's `removed_by` against the full record, or exempt cuts that end before the removal act. Alternatively, define past cuts over record versions. Then restate Lemma D, R5 and R2 (d) | a model check takes every prefix of check 22's record (and of 18b's histories with removals) and finds each in D with unchanged values |
| **T1-02** | The append guard refuses a consumption whenever any consumption of the grant is recorded. Three effects: (1) a non-holder's consumption (value F, "ends nothing") blocks the holder's legitimate consumption; (2) an annulled consumption also blocks; (3) D admits records with two consumptions (merges), so §9.13.2's "the append guard and the static test now agree" is false since v8. The guard and CE* disagree on what "consumed" means | high | **Fact**, probes P3 (kB = F; guard refuses kA), P4 (k1 annulled, value F; guard refuses k2; if appended, k2 = T), P5 (two consumptions in a record in D). Model L759 | Make the guard read the evaluated value (refuse iff an existing consumption is T or U), or state the syntactic guard as a choice with its denial-of-service cost. Add the static counterpart, or withdraw "agree" | the guard accepts kA in P3 and k2 in P4, or the text states and costs the syntactic rule; §9.13.2 no longer claims agreement |
| **T1-03** | The guard prevents the second **entry**, not the second **use**. The work is delivered in the world (R3a's own reason). So "prevented" holds only under an unstated premise: no work is delivered until the kernel accepts the consumption entry. Without it, a refused second consumption leaves a double use in the world **unrecorded**, which is weaker than detection, where CE* would record it as F | high | **Ded** from R3a's reason (§9.16 L183) and the `append_guard` semantics. No such premise is in §9.15's K or L lists | State the premise (record before delivery) as a kernel or legitimacy assumption in §9.15. Say what happens to a refused consumption whose work happened anyway | the premise is in §9.15's lists; G4 cites it |
| **T1-04** | current-authority counts a consumption of a cited grant ordered after the adoption as ending the adoption's authority. So delivering the work strips earlier work done under the grant of standing. This is exactly what §1.14.1-4 was built to avoid ("a grant recorded as CONSUMED … would strip that work of authority"; case 4: "if the adoption itself consumed g, the claim stays current"). Because consumption is its own kind, "the adoption itself consumed g" cannot be represented in D. P is reserved to the Principal, and the choice is not marked | high | **Fact**, probe P2: adoption at 1, consumption at 2 gives authorized(x) = T, CE*(c,1) = T, CE*(c,3) = F. §9.13.3 current-authority clause; model `current` L373. Ch. 1: 01.14 L103–105, L160, L198, L234; TODO T1-12 | Mark the clause as a choice under P, or exclude consumptions from the lapse (the §1.14.1 premise). Return it with P | §9.13.3 says which, with the reason; a check shows the chosen value for P2 |
| **T1-05** | "A removal is an **authorized**, recorded act" is applied as "a removal whose authorization is evaluated". An unauthorized removal still drops the content, with only a mark. Dropped content cannot be restored by any later recorded act. So by decision 1's own criterion ("prevent … when its effects cannot be undone by a later recorded act"), unauthorized removal should be prevented. The application does less than the wording | high | **Fact**: §9.13.2 L46 ("the content stays dropped"); check 22 (unauthorized removal: T plus the mark). **Ded** from decision 1's criterion | Return to the Principal: either refuse to tombstone unless the removal act is T, or confirm marking. If refusal is chosen, add a guard and a check | the Principal's answer is recorded and the guard or the mark follows it |
| **T1-06** | The merge rule keeps one `removed_by` in first-come order (`y.get('removed_by') or x.get(...)`). With two removals of one entry in two applications, one authorized and one not, the mark "removal not authorized" depends on the merge order. This refutes R2 v3 "no merge order can change any value or mark", which is §9.16.1's own falsifier | high | **Fact**, probe P6: order (rm1, rm2) gives `['rests on removed content g']`; order (rm2, rm1) adds `'removal not authorized: rm2 (F)'`. Model `merge` L741–743 | Make removed_by a set, joined by union, and mark when any or all removals are not T (state which). Re-run 18d with two removals | 18d includes two removals and gives the same marks in both orders |
| **T1-07** | Three problems with R4 v3's consequence for F22. (1) §9.6.4 step 3 says the shared point is "a **kernel candidate**", but (i) says the kernel "must provide" it. (2) The ownership rival (EXT-089), with the contract assigning each grant's consumptions to one owner application, prevents double consumption with no kernel-provided runtime point; only a contract rule is needed. So "kernel-provided" is not derived. (3) (iii)'s hypothesis ("no kernel law requires prevention") is now false, yet "Recording … remain[s] delegable" reuses it, although consumptions must pass the point. The result is a third option outside §9.6.6's (C)/(P) table (whose "holds if" column would select (P)). "So for F22: the kernel provides …" reads as a resolution of a matter reserved for the Principal | high | **Fact**: §9.16 L201–219; §9.6 L127, L188–189; THEORY-MAP F22 row. **Ded**: ownership-by-contract construction | Restate it as: coordination is needed per grant (derived); whether the kernel provides it or the contract assigns an owner is open (rival named). Present "(C) + coordination" as an option for the Principal, and restate (iii) for laws under prevention | R4, §9.6 L217 and THEORY-MAP F22 name the rival and call the result an option |
| **T1-08** | §9.14 check 8 and R5 (d) say "up- and down-sets" of the 3×3 product order and of a rooted tree have h = 2, and "the product order's up- and down-sets are boxes". The tool computes only **principal** up- and down-sets. With all of them, h ≥ 4 in both cases (cap 4). This is a false claim in a list that licenses pairs-only checking (also CON-0192) | high | **Fact**, c19t1_helly.py: tree gives 4 (witness {a,a1,a2}, {a1,a2,b1}, {r,a,b,a1,b1}, {r,a,b,a2,b1}); 3×3 gives 4. helly_check L254–264. A smaller tree example (Ded): {a,b}, {r,a}, {r,b} is minimal empty | Say "principal up- and down-sets (order intervals)" everywhere, and add general up-sets to the h > 2 list | the text, CON-0192 and the tool docstring say "principal"; a check of general up-sets shows h > 2 |
| T1-09 | R3a's reasons. (a) The line "effects the law protects lie outside the record" is drawn so the result matches the Principal's reading: acts under ended authority and exclusive roles also have world effects, which are set aside as "legitimacy matters", while single use's delivered work is not. (b) The once-only reason opens with a standing effect, which by the row's own line is correctable. (c) The single-use reason credits the correction to an annulment, though the second consumption is already F by evaluation. (d) Role exclusivity is "detect" against the source R3 cites: S0991's I_AuthorityUniqueness, "the distributed system must prevent simultaneous conflicting authoritative states" (verified, L2054–2064, sha256 match). (e) The correcting acts for law 26 and roles (supersession, annulment of an assessment) are not kinds of D | normal | §9.16 L183–189, L175 | Say that the classification of the last three rests on the Principal's reading, record S0991 as the counter-source, and fix (b), (c), (e) | the reasons are consistent with one stated line, and S0991 is cited against |
| T1-10 | (1) K4: when it fails, each application's own guard accepted its entry and computed it per definition, so K4 meets the kernel clause only if the shared guard counts as a kernel step. That is R4 v3's contested conclusion, so the reasoning is circular. (2) The header "CE*'s answers depend on these and on nothing else" contradicts K4's "No CE* value depends on K4". (3) §9.15 K4 needs held acts unconditionally; §9.16 R3a and R4 make it conditional on held acts becoming a kind of D | normal | §9.15.2 L44; §9.15 L70, L77; §9.16 L192, L212 | Say under which reading K4 is a kernel assumption; reword the header; align the condition | each K and L row names its clause; header and K4 agree |
| T1-11 | Stale text after v8, v5 and v3 (each is a sentence the decisions now falsify). §9.13: inputs table "I5 … by key", "I9 (F26 (a)/(c))"; §9.13.5 row 4 "(b) open … outside D", "(Ded, not checked)"; "Result 2 stands for … F26 (b)"; §9.13.9 "F26 among (a), (b), (c)"; §9.13.10 first bullet; §9.13.7 "the Professor's reading of the retained fields"; R2's broken sentence after "v8)." §9.15: R5 "D orders by (t_x, s_x)"; G3 and R6 "how a removal is recorded … open"; R6 "D admits every combination", "fields citations rely on"; G4 "no **act** consumed twice" (it is a grant) and its stale conditional. §9.16: R1 rows F26, delegable, Lemma D, Q3, law 21 ("ties by content"), §9.15 K2 ("by position, the rule now"), G4 ("consumption is not an admitted kind"), H1′/F30 ("returned"); §9.16.1 falsifier; §9.16.2 decisions table without the post-turn-7 decisions; R3 "(consumption is not admitted in D)"; R4 (ii) "Θ_f breaks ties by position … the order already needs a shared point"; R5 "Φ … read … position"; R6 table rows 9b, 24, 26; §9.16.5 "outside D now"; §9.16.6 K2. §9.9 L126: R7 "proposed, not adopted". CON-0191 meaning: "endings ordered by the key (act time, position)" | normal | the line numbers given in the cited sections | Update each to the decisions in force | a grep for "by position", "content tie", "not admitted in D", "not adopted" finds only marked history |
| T1-12 | Under ties, the derivations are right but not restated. R3 lemma step 2 names only u atoms, not o atoms. R2 (c)/(d) lists two U-causes; a tie is a third (o ← not o). §9.13.3's reason list (i)–(iii) has no tie item. Several check figures are stale against the v9 run: 14 (text 3,228/1,718/178 of 2,772; run 3,067/1,632/217 of 2,933) and 2 (0.6; run 0.75) | normal | §9.13 L175–176, L201, L130–144, L165, L195; v9 run | Add the o atoms to step 2, a third cause and a reason item; refresh the figures | the text matches the v9 run |
| T1-13 | §9.14 formula. (a) Attainment: "the closed interval J with its point complements" has \|J\|+1 members, not \|J\|+2. The t = 2 case needs the two rays x ≥ min J, x ≤ max J; for J = X no interval belongs. (b) "finite but above 3 … when finite intervals have at most K ≥ 2 points" is false: n = 2, 3 give h = 2, 3, and {0,1}⊕ℚ gives h = 3 with K = 2. (c) The "Pairs suffice?" cell says "no on … any order with a finite interval of 2 or more points", which contradicts "only if n ≤ 2" at n = 2. Exact (Ded): pairs suffice iff \|X\| ≤ 2 | normal | §9.14 L92, L117. **Ded**; the finite cases were brute-forced (n = 2..5 gives h = n) | Fix the attainment family, the corollary and the cell | the row's sentences agree with the formula on these examples |
| T1-14 | Coupling: "F ∪ {C} minimal iff F satisfiable without the coupling" is right, but "so h rises by at most 1" is false. Families without C are minimal over the uncoupled space and can be larger | normal | **Fact**, c19t1_coupling.py: point complements and singletons on {1..k}, coupling {1,2}: h in X = 2; coupling as a rule gives k (k = 3, 4, 5) | Bound only the families that contain C, or drop "at most 1" | the sentence holds on this example |
| T1-15 | The Professor's choice "signed-as-other ⇒ recorder ≠ actor". It is technical (definitional consistency with §9.15.2), but it assumes recorder = signing party, which R6 lists as open. Settlement §6 item 8 had returned this question to the Principal; reclassifying it as technical should be said. Also, "neither field changes any value" (check 20) now holds only within D: the combination now refuses a record | normal | §9.13 L37; §9.15 L50, L141; check 20's generator avoids the combination (model L1300–1304) | State the premise; note the reclassification; scope check 20 to D | the text says so |
| T1-16 | marks_act soundness is asserted ("by the same closure") but check 19 tests item marks only. "Removal not authorized" is also printed for a U removal | normal | model `marks_act` L591; check 19 | Extend check 19 to act marks; word U separately | the run shows it |
| T1-17 | §9.15 K2 v5: "agreement on the cut's entries suffices" also needs each reader's order to respect citations (D reads positions) and the readers to agree on tombstone state (marks) | normal | §9.15 L75; `in_D` reads `pos` | Add both | K2 says so |
| T1-18 | Single use as modelled bounds **consumptions**, not uses. A single-use grant authorizes any number of non-consumption acts before its consumption (check 21: an act at 1 is T). This may be the right reading of "used up by delivering its work", but it is Int and unstated | normal | check 21; §1.13.2 L78 | State the reading and its rival (the first act under g consumes it) | §9.13.2 states it |
| T1-19 | EXT-087's gloss, "iff merging **any two** states that each satisfy it", is stronger than invariant confluence as I know it from Bailis et al. There, it is defined over states reachable from a common valid ancestor under the given transactions. **From my own knowledge, not verified in this session** | normal | EXT-087 row; §9.16 R3 L153 | Check the definition and restate | the gloss matches the paper's definition |

## 3. Proof-step tables (derivations judged adequate)

**A. KK = WF on D with order atoms (§9.13 v8; R3 lemma extended).**

| Step | Claim | Justification |
|---|---|---|
| 1 | In Φ, before(e,x) is a record constant in {T,F,U}, so Φ is a strong-Kleene composition of atoms and constants | §9.13.3; model `before` |
| 2 | Φ is monotone; R1 steps 4–8 are unchanged | the connectives are monotone |
| 3 | In the program, ¬(a(e) ∧ o) = ¬a(e) ∨ ¬o, and B ∧ (¬e ∨ ¬o) = (B∧¬e) ∨ (B∧¬o), giving two rules per tied ending | De Morgan and distributivity hold in strong Kleene |
| 4 | o ← not o is U in KK and WF; o has no positive body, so it is in no unfounded set (as u in step 2) | R3 step 2's argument |
| 5 | Positive dependencies are unchanged (cited bases only) and acyclic on D, so steps 3–8 apply and KK = WF | R3 |
| 6 | Model: 3,000 records in D with act times in {1,2}: Φ-KK, WF, program-KK and program-WF agree (probe P8); check 6 | evidence, form only |

**B. §9.15 R4, signed-as-actor under P-perf (one direction).**

| Step | Claim | Justification |
|---|---|---|
| 1 | the entry is intact and verifies under the key the record binds to the named actor | K5 (the recorded status is the true verification result) |
| 2 | that key is the actor's | L2 |
| 3 | a valid signature on this content was produced by a holder of the key | L8 |
| 4 | no holder other than the actor used it for this entry | L3 per entry |
| 5 | so the actor signed this content; under P-perf the signing is the act | 1–4; P-perf. Copies of the same content are the same act (content identity), so replay adds no counterexample |

**C. §9.14 upper bound h ≤ max(2, sup \|J\|+t(J)).**

| Step | Claim | Justification |
|---|---|---|
| 1 | in a minimal empty F, if the intervals meet in ∅, then F is two intervals | Helly in dimension 1 (the interval proof) |
| 2 | otherwise the intervals meet in J ≠ ∅; the complements forbid exactly J, so J is finite | emptiness and minimality |
| 3 | J = [max left end, min right end]: at most 2 intervals are needed, 1 if J is an initial or final segment, 0 if J = X | closed thresholds |
| 4 | so \|F\| ≤ \|J\| + t(J). Attainment needs two rays when t = 2 (T1-13) | — |

## 4. Answers to the brief's questions (short)

1. Φ is monotone. KK = WF on D. The lemma survives (o atoms behave like u). The split is correct. I found no record in D where the two routes differ (P8).
2. Consumption: the Φ clause is a defensible reading, but it bounds consumptions, not uses (T1-18), and it ends the currency of earlier adoptions (T1-04). The guard does not prevent what decision 1 targets (T1-02, T1-03). Two consumptions arriving by merge: the record stays in D; the one with the later act time is F, both are U at equal act times, and a backdated consumption wins (P5).
3. Removal: its authorization is evaluated by Φ, but there is no distinct removal activity, so any covering grant authorizes it. An unauthorized removal changes no value, but it destroys content and makes prefix cuts undefined (T1-01, T1-05). "Not authorized" as a mark only does less than the Principal's wording.
4. R3a: the table equals the Principal's reading. The line between standing and world effects is not applied consistently (T1-09).
5. R4 v3: "coordination needed" is derived; "kernel-provided" is asserted. The text offers it as a consequence, but its F22 wording overreaches (T1-07).
6. Every K and L row meets one clause, except K4, which depends on a reading (T1-10). R4's proof is valid (table B).
7. The bound is complete. The attainment sentence and two corollaries are wrong; J = X is handled in the bound but not in the attainment sentence (T1-13).
8. Both of the Professor's choices are technical. The recorder ≠ actor choice rests on an open premise, and the question had been returned to the Principal (T1-15).

**Settlement rows:** I checked the rows of A, B, C and D against the current text, not each row in depth. Not met, or regressed by v8/v5/v3:
- B1-01 (T1-08) and B1-04 (T1-14): the corrections introduced false claims;
- B1-02 (T1-13): partly met;
- D1-01 and D1-02: regressed by removal acts (T1-01, T1-06);
- C1-06, C1-07/C2-09, C1-10, A1-07, A1-12: overtaken by later text and now stale (T1-11).

The others I sampled hold, for example A1-01 (check 19), A1-18, B1-10 (grep finds no "unbounded attribute" outside review files) and D1-03.

## 5. Coverage

- **Read in full:** the brief; COMMON; the settlement; §9.13 v8; §9.14 v4; §9.15 v5; §9.16 v3; both runs.
- **Model source read:** ce_model.py core (L40–400), tombstone, support, marks, merge, guard, checks 21–22; helly_check check 8.
- **Pointer and row checks:** §9.6 L117–217; §9.9 L126; §9.10 and §9.12 S0916 rows; §9.11 F36 note; THEORY-MAP F22, F26, F30, F36; CON-0191–0194; EXT-084–089.
- **Chapter 1, at its consumption lines:** §1.13 and §1.14.
- **Corpus:** S0991 L1140–1146 and L2054–2064 only (allowed, sha256 matched).
- **Not done:** other corpus lines and quote checks; a full re-run of either model; an external check of EXT-084–089 (T1-19 is from my own knowledge); REVIEWER-INSTRUCTIONS read only at its mode, verdict and severity parts.

## Summary

1. Ties unordered: sound. Φ is monotone, KK = WF survives the o atoms, and the program split is correct; the text needs restating (T1-12).
2. Removal acts break prefix-closure: every cut between a tombstone and its removal act is refused (T1-01, high).
3. The consumption guard is syntactic: ineffective or annulled consumptions block, it has no static counterpart in D, and it prevents entries, not uses (T1-02, T1-03, high).
4. The consumption clause makes earlier adoptions lapse, against §1.14.1-4, under P, which is reserved and here unmarked (T1-04, high).
5. Unauthorized removals still destroy content; the Principal's criterion suggests preventing them (T1-05, high).
6. The merge rule makes marks depend on merge order with two removals, refuting R2 v3 (T1-06, high).
7. R4 v3's "kernel-provided" coordination is not derived; ownership by contract is a rival (T1-07, high).
8. §9.14's "up- and down-sets have h = 2" is false (h ≥ 4); the coupling bound and the formula's attainment wording are also wrong (T1-08 high; T1-13, T1-14).
9. There is widespread stale text across all four sections and §9.9 (T1-11).
10. §9.15 R4's proof is valid; the R6 and R7 adoptions are applied as worded.

**Process independence:** a separate session that read no other reviewer's report. I am the same model family as the author's tooling, so independence is of process, not of person. My first probe ran without `-B` and rewrote the git-ignored `tmp/tools/__pycache__/ce_model.cpython-313.pyc`; I edited nothing else.

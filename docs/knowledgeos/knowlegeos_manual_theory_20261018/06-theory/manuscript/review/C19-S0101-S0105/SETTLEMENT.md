# Settlement of window C19 (S0101–S0105) and of its commission T (the turn-7 settlement's changes)

*2026-10-21. By the Professor. Two commissions, as the Principal ordered:*
- *the window commission W, one reviewer (Opus), transition review with a blind step;*
- *commission T, two reviewers (Opus T1, Sonnet T2), topic review of all changes of the turn-7 settlement in full (§9.13 v7, §9.14 v4, §9.15 v4, §9.16 v2) and of the application of the Principal's turn-7 answers (§9.13 v8, §9.15 v5, §9.16 v3).*

*No reviewer saw another's report. The Professor read all four reports in full.*

## Reports, saved verbatim, with their sha256 at hand-back (the Principal's instruction)

| File | Reviewer | sha256 of the hand-back text |
|---|---|---|
| `STEP1-INVENTORY.md` | W (Opus), blind inventory | `dfc75e5ada5f9d9437e16ec2915a7e62829306fcd6c4f97b530cea8c2e33edfa` |
| `REVIEW-REPORT.md` | W (Opus), steps 2–3 | `4850f659eeaf321d4c230555cc17a3552d86efc9cfa19b872f69d299375e6f6e` |
| `REPORT-T1.md` | T1 (Opus) | `66f6753d9fe3ae9d0df45dd5f5e1ad0f2f6bbe945366481947bea49072dec516` |
| `REPORT-T2.md` | T2 (Sonnet) | `f0282dacb9de5f2293e6792852c1d7c3817cb914b86a3a74a23577b8cb3c0468` |

Each hash is over the UTF-8 text of the hand-back message as received. Each file starts with one header line and then holds that text exactly. Reviewer W re-hashed its saved inventory and confirmed the match.

## 0. Deliverable status

**Met.**
- S0101–S0105 were read in order, all at depth full.
- §8.12 is settled as v2, with CON-0195, CON-0196 and CON-0199 provisional, CON-0197 and CON-0198 INVALID as stated, restated as CON-0200 and CON-0201.
- The turn-7 changes and the application of the answers were reviewed in full and settled: §9.13 v9, §9.14 v5, §9.15 v6, §9.16 v4; models `ce_model` v10 and `helly_check` v5.

**No critical finding.** Both commission-T reviewers found the tie semantics sound, and their proof tables agree: Φ is monotone with the three-valued order, KK = WF survives the tie atoms, and the program split is correct.

The defects lay in the machinery the Professor built to apply the answers:
- consumption;
- the single-use guard;
- removal acts;
- the merge of tombstones;
- R4's F22 consequence.

All are repaired as technical choices and marked for review. **One question goes to the Principal** (§5).

## 1. Measures (per window, from C19 on; the Principal, 2026-10-21)

| Source | Depth | Time | Findings (window commission) | New concepts |
|---|---|---|---|---|
| S0101 | full | not separable (see below) | 3 (W-1, W-8, W-10) | — |
| S0102 | full | not separable | 6 (W-3, W-4, W-5, W-9, W-12, W-13) | CON-0197, CON-0198, CON-0199 |
| S0103 | full (topic-full at turn 6) | not separable | 4 (W-2, W-3, W-5, W-6) | CON-0195 |
| S0104 | full | not separable | 2 (W-8, W-11) | — |
| S0105 | full | not separable | 4 (W-1, W-7, W-8, W-11) | CON-0196 |

- **Time per source.** In C19 the five start times were recorded together (21:40:00Z) and the closes run together (21:46:20–22Z), so per-source time was **not measured**. *From C20:* each source is started, read and closed in turn, and the time is its stop minus start.
- **Reviewer time** (harness): W step 1, 144 s; W steps 2–3, 498 s; T1, 665 s; T2, 571 s.
- **Counts:**
  - W: 13 findings (6 high, 7 normal);
  - T1: 19 findings (8 high, 11 normal);
  - T2: 15 findings (4 high, 11 normal);
  - 47 findings in all, none critical.
- **Skim.** None of the five was skimmed. The Professor's judgement: each adds theory (CON-0195–0199; the provenance settlement). S0103 had been read topic-full at turn 6 and was re-read whole; it could have been checked against the book only, but it added CON-0195. *The Principal's instruction of 2026-10-21 applies from C20* (§6).

## 2. Window commission W (§8.12 → v2)

| ID | Finding | Sev. | Decision and correction | Resolved when | Holds? |
|---|---|---|---|---|---|
| W-1 | C3 and C9 confirmed a compaction mechanism that S0105 denies (compaction NO; context through a pasted user turn) | high | Accepted. C3 confirms only the attribution clause. The mechanism is disputed between S0099 and S0105, with the operator as the likelier channel. CON-0180, §8.11 C1 and the THEORY-MAP line restated. A new fork on the inheritance channel | §8.12 C3, §8.11 C1, CON-0180 and the map name both sources | **yes** |
| W-2 | C1 "answers §8.11 C2 for this work item": the criteria bind one assignment, going forward | high | Accepted. Rescoped in C1, CON-0195 and the §8.11.3 pointer; S0103 is Governance's registration of the act | the three places say "one assignment, going forward" | **yes** |
| W-3 | C6's three-way Int contradicts the sources' definitions | high | Accepted. CON-0198 INVALID as stated; CON-0201 restates it on two levels (holds: T/F/U; can be established), with two senses of "not established" and a "refuted" state | §8.12 C6 and CON-0201 | **yes** |
| W-4 | C5's mapping onto §9.15's K-list is partial; two homonyms merged | high | Accepted. CON-0197 INVALID as stated; CON-0200 maps the conditions one by one (bypass and timing: no K), and qualifies "authority (effect)" and "ratification (of an outcome / of a rule)" | §8.12 C5 and CON-0200 | **yes** |
| W-5 | the "contradiction" rests on the constrained party's own report of acceptance | high | Accepted. S0102 L780 classed as ASSERTED; two readings; the F25 row reworded | C3 and the map row | **yes** |
| W-6 | "only by implication" (S0103 L36) | normal | Accepted. Both readings stated | C1 | **yes** |
| W-7 | the reconciler's own producer is unattested | normal | Accepted. Added to C3 | C3 | **yes** |
| W-8 | the "fifth class" mixes two scales | normal | Accepted. An identity scale apart from the authorship scale; modification-time fragility | C2, C4, CON-0196 | **yes** |
| W-9 | C7's ground ① fails the trivial-solution test; C-19 and L476 missing | normal | Accepted. "Treated as sufficient by the source"; looseness; necessity ≠ existence; an instance-based falsifier | C7, CON-0199 | **yes** |
| W-10 | "every subagent … with the parent's context" | normal | Accepted. Only forked subagents | C9 | **yes** |
| W-11 | the AMD2 homonym; gaps in the criteria | normal | Accepted. Added to C1 | C1 | **yes** |
| W-12 | the receipt is self-asserted | normal | Accepted. Added to C9 | C9 | **yes** |
| W-13 | §8.10 C7 "external attestation absent by construction" | normal | Accepted. Corrected: absent in fact | §8.10 C7 | **yes** |

**Coverage gaps named by W, all now in §8.12:**
- compaction NO;
- S0101 L23's faulty inference;
- the reconciler's process;
- the AMD2 homonym;
- the criteria gaps;
- the two senses of "not established";
- PARTIAL and PROCEDURAL ONLY;
- the self-asserted receipt;
- C-19 and L476;
- the fragility of modification-time binding.

**New fork:** the inheritance channel (§8.12.3).

**Source slips** (listed separately):
- S0101 L199 says "2 of 6", while 7 terms are mandated;
- the erratum's "compacted" (S0101 L222; S0102 L920) contradicts S0105 L91.

**Verdicts:**

| Concept | Verdict |
|---|---|
| CON-0195, CON-0196, CON-0199 | PROVISIONAL |
| CON-0197, CON-0198 | INVALID as stated |
| CON-0200, CON-0201 | new, PROVISIONAL |

## 3. Commission T (turn-7 changes in full; the application of the answers)

| IDs | Finding | Sev. | Decision and correction | Resolved when | Holds? |
|---|---|---|---|---|---|
| T1-02 / T2-03 | the single-use guard counts unauthorized and annulled consumptions; it has no static counterpart; it prevents the entry, not the use | high | Accepted. The guard counts consumptions that are **not F** (v10). The cost is stated: it evaluates CE*. L9 "record before use" added to §9.15. The "agree" claim restricted to citations | check 21: an F or annulled consumption does not block; L9 in §9.15 | **yes** |
| T1-03 | prevention needs an unstated premise | high | Accepted. §9.15 L9; G4 cites it | §9.15 | **yes** |
| T2-02 | a consumption was not tied to its grant | high | Accepted. A consumption must cite its grant and is based on it only (`bases_of`, v10) | check 21: another grant's holder gives F; a record not citing the grant is refused | **yes** |
| T1-04 / T2-01 | consumption in the currency clause makes earlier adoptions lapse, against §1.14.1-4 | high | Accepted. **The Professor's choice:** consumptions do not end the currency of earlier adoptions, which follows §1.14.1-4 and the Principal's wording "ends its grant for acts ordered after it". P stays reserved | check 21: adoption at 1, consumed at 2, current at 4 | **yes** |
| T1-01 | prefix cuts between a tombstone and its removal act were outside D | high | Accepted. D is checked on the whole record; a cut is evaluated on its entries (`ce_star_cut`) | check 23: 291 records, every cut evaluable, values unchanged | **yes** |
| T1-05 / T2-06 | an unauthorized removal still destroyed content | high | Accepted. **The Professor's choice under decision 1's criterion:** lost content cannot be restored by a later act, so an unauthorized removal is **refused**. A removal that later stops being T is marked | check 22 | **yes**; *limit:* one activity, so any covering grant authorizes a removal (open) |
| T1-06 / T2-04 | marks depended on merge order with two removals | high | Accepted. removed_by is a set; the mark needs every removal act to be non-T | check 18e: both orders equal | **yes** |
| T1-07 / T2-08 | R4 v3's "kernel-provided" is not derived; ownership by contract is a rival; a third value of F22 | high | Accepted. R4 v4: coordination per grant derived; **two options returned to the Principal**, (C + K) and (C + O) | R4, THEORY-MAP F22, CON-0194 | **yes**; decision with the Principal |
| T1-08 | §9.14 "up- and down-sets have h = 2" false; only principal ones | high | Accepted; verified by the Professor (general ones: h ≥ 4 on both). "Principal" everywhere; check 9 | the text and tool say "principal"; check 9 | **yes** |
| T1-09 / T2-07 | R3a's reasons not derived from the criterion | normal | Accepted. Labelled as the Professor's reading reproducing the Principal's list. S0991 recorded against role exclusivity. Rows (b), (c), (e) fixed; "Ded" relabelled Int | R3a | **yes** |
| T1-10 / T2-12 | K4 under a reading; header; K3 in both clauses | normal | Accepted. K4's reading stated; header qualified; K3's "correct" defined | §9.15 | **yes** |
| T1-11 / T2-11 | stale text throughout | normal | Accepted; a grep pass over §9.09, §9.13–§9.16, CON-0191–0194 and THEORY-MAP F22 | the listed phrases appear only as marked history | **yes** |
| T1-12 / T2-09 / T2-10 | tie atoms not written into R2, R3 and the reasons; a soundness property; stale figures | normal | Accepted. R2 (d) has three causes; R3 steps 1–2 name o; reason (iv) added; the soundness property and the U gloss qualified in row 4; figures refreshed against the v10 run | §9.13 | **yes** |
| T1-13 / T2-15 | §9.14 attainment wording, corollary, cell; \|X\| = 1 | normal | Accepted | the row | **yes** |
| T1-14 | the coupling bound "at most 1" false | normal | Accepted. Restricted to families containing C; the reviewer's construction noted, not re-run | §9.14.2 | **yes** |
| T1-15 / T2-14 | recorder ≠ actor under signed-as-other: normative in effect | normal | Accepted. Changed to a mark, in line with decision 2; the premise stated | check 22b | **yes** |
| T1-16 | marks for acts not checked | normal | Partly. Check 22b exercises marks_act. A soundness check like check 19 for acts is not added | — | **open (normal)** |
| T1-17 | K2's further conditions | normal | Accepted | §9.15 K2 | **yes** |
| T1-18 | single use bounds consumptions | normal | Accepted. Stated, with the rival | §9.13.2 | **yes** |
| T1-19 | EXT-087's gloss stronger than the paper | normal | Accepted. Abstract checked (2026-10-21); the definition labelled as recalled, not checked | EXT-087, R3 | **yes** |
| T2-05 | removed_by points forward | normal | Accepted. Stated as outside the citation rule, like targets | §9.13.2 | **yes** |
| T2-13 | an identical second consumption collapses under content identity | normal | Accepted. Limit stated | §9.13.2 | **yes** |

**Counts, T:** 34 findings (T1 19, T2 15) → 22 rows. Found by both: 10 rows. One reviewer only: 12.

**Settlement rows of turn 7 that the reviewers found not met or regressed, now repaired:**
- B1-01 (T1-08), B1-04 (T1-14), B1-02 (T1-13);
- D1-01, D1-02 (T1-01, T1-06);
- the stale ones (T1-11).

## 4. Checks run on the corrections

- **Quote check:** 0 not found in every changed section:
  - §8.10 31, §8.11 16, §8.12 50;
  - §9.09 13, §9.13 10, §9.14 2, §9.15 13, §9.16 14.

  The whole book stays within the baseline (re-run before the commit).
- **R1:** 177 rows with a home; 1 inherited flag (CON-0133). Tags agree with the register.
- **Models:**
  - `ce_model.py` v10: all assertions passed (`ce_model-run-20261021-v10.txt`), with new checks 18e, 21 (extended), 22 (extended), 22b (changed) and 23;
  - `helly_check.py` v5: check 9 added; run `helly_check-run-20261021-v5.txt`.

## 5. Returned to the Principal (normative or reserved)

1. **F22, the remaining option** (§9.16 R4 v4). Coordination per grant for single use is derived. Who provides it:
   - **(C + K)** the kernel provides the coordination points and holds the contract;
   - **(C + O)** the contract assigns an owner application per grant.

   Both keep recording and evaluation at a cut delegable, and both differ from §9.6's (P).

*Not returned, decided by the Professor and marked for review (the standing instruction):*
- the consumption fixes (T2-02, T1-04, T1-18);
- the guard's counting rule and L9;
- refusing unauthorized removals;
- cuts on the whole record;
- the merge rule;
- the recorder ≠ actor mark.

## 6. The Principal's instruction of 2026-10-21, applied from C20

> "Window size stays 5. Use 10 where §12a allows it (a coherent series of short documents, at most 2 added, review split into two commissions), and record per window: time per source, findings per source. Apply the skim depth of v1.3 §3 honestly. Each source is opened and scanned whole, and one that repeats earlier material or is purely operational gets "skim" with one line on what it adds. In 104 sources there has been no skim so far. The reviewer reads one skimmed source blind per window, as §3 requires."

**What C20 will do:**
- each source is started and closed in turn;
- the measures table of §1 is filled per source;
- a source that repeats earlier material or is operational is closed as `skim`, with one line on what it adds;
- the reviewer reads one skimmed source blind. If a window has none, it takes one from an earlier window.

## 7. Open items

| Item | Role | Next action |
|---|---|---|
| F22: (C + K) or (C + O) | **Principal** | decide |
| removal tied to a removal activity over the target's scope | Professor | next model revision |
| a soundness check for act marks (T1-16) | Professor | next model revision |
| the inheritance-channel fork | Professor / Principal | when a later source bears on it |
| §9.15: a gate's bypass and the timing of evaluation as kernel assumptions (C5) | Professor | next §9.15 review |
| P (conjunctive or disjunctive) | Principal (reserved) | unchanged |
| reading | Professor | C20 (S0106–S0110), under §6 |

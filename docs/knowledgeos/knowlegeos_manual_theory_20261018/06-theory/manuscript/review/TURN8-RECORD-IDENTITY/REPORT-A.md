TURN 8 REPORT A

*Reviewer A (Opus). Mode: topic review, steps 2 and 3. I re-read my saved inventory first. My independence is process independence only: I have not seen reviewer B's inventory or report.*

## 1. Assessment

- **R1 and the M2 countermodel hold.** "The record" is set by something outside the entries, and K1 with K2 as worded does not fix which store it is.
- **R4 (CON-0263) does not hold as stated.**
  - Its definition of loss counts entries that R3 says are not members of the record.
  - Its operand condition A3 drops CON-0259's "not only".
  - Its "three independent necessary conditions" depends on which of A1's two definitions you read.
  - The model check confirms a tautology.
- **Largest risk:** R4 has been written into §9.15 K1's kernel cell. Its setting, removing a store, has no referent in D.

## 2. Findings (ranked)

| ID | Sev. | Type | Claim / topic | Location | What is wrong | Consequence | Correction |
|---|---|---|---|---|---|---|---|
| A-01 | **critical** (F1: §9.15 K1 cell, CON-0263, the §8.18.3 and §8.21.3 answers rest on it) | defect | R4 (i) and (ii) | §9.17.2 "loss"; R4 proof (i) | "Loss" counts any entry *written* before the act. By R3, a write to the old store after the switch is not of ρ, yet it counts as a "loss" whenever the survivor lacks it. So R4 would require promoting non-members, which contradicts R3 and S0300 L957. The proof step "by A1 and A2 the reference equals the content that ought to survive" needs two premises the section does not state: (a) the reference holds no non-member (⊆), and (b) the source lost nothing before t_a (lost update, S0300 L428–443). | (i) and (ii) are unproved. The estate's design breaks A1-timing by design: the enumeration closes at 3(ii) and source writes follow. | Define loss over members of ρ that the survivor must hold. State (a) and (b) as premises. |
| A-02 | high | defect | A3 operand | R4 A3 | "Not the one the act removes" distorts CON-0259 ("**not only** the casualty"). It also contradicts S0300 L941–947: two gates, two operands, "both required". My probe: a gate comparing survivor **and** source detects every loss in the model even when A1 fails (0 of 1,862 undetected; the single-operand gate misses 1,862). | A1's necessity holds only for a single-operand gate. With a casualty comparison, A1's failure becomes a block (the safe POST-DEMOTION route). | Restore "not only". Add the casualty comparison as the detector of a coverage failure. |
| A-03 | high | defect | Independence and minimality of A1–A3 | R4 A1; model | A1 has two non-equivalent definitions: coverage ("covers every entry written") and timing ("taken after the last write"). Under coverage, A2 has **no** necessity witness (0 of 32,000 scenarios), so it is derivable. Under timing, the model's sufficiency is a tautology: under A1∧A2 the reference equals the source in all 32,000 scenarios, and the survivor is always a subset of the source. A2's second clause ("the act cannot rewrite it") is never modelled and is empty under simultaneity. | "Three independent necessary conditions" is not established. The model adds no evidence beyond the definitions. | Choose one definition of A1. Under coverage, treat A2 as the means to A1. Extend the model: membership switch, lossy source, foreign entries, two operands. |
| A-04 | high | defect | R3 (d) restates CON-0254 | R3 (d) | "The closing record lies outside the record it closes" reintroduces CON-0252, which is INVALID. CON-0254 itself says a closing entry "can describe a state that includes itself" (§8.20 C6, W-02). Only a hash, or a list fixed before the entry, cannot cover it. R3 (d) also drops CON-0254 (ii), evidence placement, which is the estate's real reason (S0300 L801–829, PREMISE 2; S0253 RV-1, L164–191). | A settled correction is silently reversed. | Restate per CON-0254 (i) and (ii). Scope the claim to hash or pre-fixed list commitments. |
| A-05 | high | defect | R3 (a) and (c) | R3 (a) proof; (c); register CON-0262 | (a) builds two byte-identical entries in one store. D forbids that: unique identifiers, one position each, τ by H2 (§9.13.2; §9.8 L37). The sound proof is two histories with identical final content. The challenge row's boundary "entries carry no epoch" is false for D: τ with a trusted shared clock acts as an epoch. That is CON-0251's premise, unstated here. The register's "decidable … only if recorded with an enumeration" is contradicted by sealing the old store and by trusted τ. The section's "if" overclaims too: writes in [t_E, switch) are misclassified, so the enumeration gives a conservative classification, not a decision. | Membership's decidability claim is wrong in both directions. | Use the two-history proof. Add the τ option and CON-0251's premise. Say "conservatively classifiable". Fix the register. |
| A-06 | high | defect | K2-d | R2; §9.15 K2 row | "K2-d" names two propositions: (1) a designation exists and components mean it; (2) it was made by a competent authority. §9.15 places K2-d inside the kernel assumption K2. R2 classes it as legitimacy (L11). The independence table promises K2-d but has no K2-d row. K2-d cannot fail alone: K2-w, K2-r and K1's "the record" (CON-0239) all presuppose it. R1 calls the record argument an input "like K3", but K3's correctness test ("as accepted by the kernel's steps") is undefined, because D has no designation kind. | Homonym; unsupported independence claim; two homes for inputs. | Split K2-d into a presupposition (K2-d) and L11. Withdraw the independence claim for K2-d. Say why designation is not in K3. |
| A-07 | high | defect | Definition of designation | §9.17.2 | "Outside every record it concerns" is contradicted by the evidence R3 (d) cites: the switch-over record sits **inside** the new designated store (S0300 L1315). It also clashes with L11's own self-designation boundary row. R1 does not derive it. | The definition is false of the case used to support it. | "Outside the record it closes." |
| A-08 | high | defect | Kernel or application? | R5; §9.15 K1 cell | R4's setting is removing a store, an application or migration act. D has no stores to remove or close (§8.20 C1, C6), so "the act" and A3 have no kernel referent. Must-do missing: nothing obliges a check before an irreversible act (contrast S0300 L829 step 4, P5·3). | Kernel and application are mixed. | Give K1's cell coverage and independence only. Home R4's operand part as application or architecture. State the must-do. |
| A-09 | normal | omission | R2 rival not answered | §9.17.4 | The rival "identity folded into K1" (a misdirected accepted write is an entry lost to ρ) is listed and left unanswered. R5 says K1 is "unchanged", yet "the record" now means ρ. | The challenge is inadequate. | Answer the rival. State K1's referent. |
| A-10 | normal | defect | Hash in place of a list | R4 | "A1 holds by binding" is wrong: binding secures the comparison, not coverage. A hash also needs the same algorithm and representation (S0917 L220) and a byte-preserving medium (CON-0240). | Wrong attribution. | Restate. |
| A-11 | normal | omission | The resolver provides K2-w | R2 boundary table | P-4's substituted interpreter is a write path (S0300 L480–488). "One writer" holds on code paths, not as a structural guarantee (L424). The enforcing form is deferred (OPEN-M5). | Overstates what the resolver gives. | Qualify. |
| A-12 | normal | omission | Resolution includes the predicate | "Store", "Resolution" | Store and resolution are defined without the reader's predicate (`*.json` glob: S0300 L609, L1051; S0253 L218; §8.20 C4). | A file inside the glob is read as a record entry. | Define resolution as location plus predicate. |
| A-13 | normal | defect | Evidence in meaning; lineage | R1 Src; R4 | S0917 L1322 and L1327 and S0904 L328 concern evidential independence of replicas, not record identity. "Reached one at a time: S0137, S0146, S0142" is one chain, and S0142 and S0146 have one author. The S0300 boundary is a specification that has never been executed. | Over-counted support. | Re-label. |
| A-14 | normal | defect | M2's illustration | R2 M2 step 5 | L578 makes an **empty** new store, where e's citation dangles, so the store is outside D and Q1 refuses. M2 still stands. "Any cut of ρ" overreaches; "the current cut" is what holds. | Illustration only. | Fix wording. |

**Proof-step tables** (proof_step_check.py found 0 lemmas, so these were checked by hand).

R2, "K2-r ⇒ old K2":

| Step | Justification | Verdict |
|---|---|---|
| Same cut, named by its entries | definition (§9.15 K2) | ok |
| Each value is a prefix of "the same ρ" | K2-r | **Premise missing:** ρ is one sequence across a designation change (continuity, or K1) |
| Prefixes with the same named entries are identical | D: unique identifiers | ok |
| Tombstones agree | G3 | ok |

Valid within one designation. Close to trivial, because the cut is named by its entries.

R3 (c):
- Step "every member written before t_E is in E": premise missing (the old store is lossless before t_E; A-05).
- Steps "outside E ⇒ after t_E" and "after the switch ⇒ not of ρ": valid.
- "Decidable": not established.

R4 (i):
- Step 1 "reference = content that ought to survive": **inadequate, premises missing** (A-01).
- Steps 2–3: valid.
- (iii): design-relative (A-02, A-03).

**Recorded challenges.**

| Item | Verdict | Reason |
|---|---|---|
| R1 | adequate | The strongest rival is entries plus location; the claim's scope is entries only, so it stands. |
| R2 | inadequate | Rival unanswered; no K2-d row. |
| R3 | inadequate | The boundary rests on a false premise about D (τ). |
| R4 | inadequate | The counterexample is A2's failure by definition. Under coverage-A1 the falsifier is immune. |
| L11 | adequate as a definition | Conflicts with the definition of designation (A-07). |

## 3. Concept verdicts

| Concept | Verdict | Reason and scope |
|---|---|---|
| R1 (identity is not a function of entries) | **VALIDATED** | Ded, scope: entries only. |
| "Identity is not derivable from K1 and K2 as worded" (M2) | **VALIDATED** | A valid countermodel. |
| CON-0261 | PROVISIONAL | A-06, A-07. |
| L11 | PROVISIONAL | A model separates it from L5 (competent root designation, incompetent record designation). It hinges on A-06. |
| CON-0262 | PROVISIONAL | The definition is coherent; (a) and (c) are defective (A-05). |
| CON-0263 | **INVALID as stated** | "Three independent necessary conditions" fails under A1-as-defined (probe). Loss contradicts R3 (A-01–A-03). |
| CON-0162 restatement | **VALIDATED** | ¬(i) ⇒ ¬(ii) checked on one case set; matches A-13 of the consolidation. |
| CON-0258 | PROVISIONAL | If G may name x's values ("x never changes"), the circularity moves into G. State that it may not. |
| Ch. 3 re-verdicts (CON-0008, 0041, 0043, 0065) | consistent with the rule | Checked against their reasons. |

## 4. Coverage against my inventory (ranked)

| Item | Status |
|---|---|
| S0300 two gates and operands (L941–947); S0253 RV-1 / Premise 2 | **DISTORTED** (A-02, A-04) |
| S0300 lost update and "dense ⇏ complete", as A1's source premise | MISSING (A-01) |
| S0300 designation inside the new store (L1315) | CONTRADICTED by the definition (A-07) |
| Predicate as part of resolution (S0300 L609; S0253 L218) | MISSING (A-12) |
| P-4 write path | MISSING (A-11) |
| Quarantine laundering (S0300 L1010–1024): a move of the operand defeats the comparison | MISSING; it belongs with A-02 |
| RV-7, a fresh enumeration per attempt | MISSING (consistent with A1, minor) |
| S0917 hash representation premises | MISSING (A-10) |
| S1375 "authoritative for a particular fact" | COVERED |
| Single resolver; advisory for ad-hoc readers; mkdir manufactures a location; content-closed enumeration; Merkle commitment; causal order | COVERED |
| grantId collision; crash durability; OPEN-M7 hold; declared-delta (CON-0249) | JUSTIFIABLY EXCLUDED (the research question's out-of-scope list) |
| S2055 state vs history; S0904 merge and convergence; S0917 four-way separation | JUSTIFIABLY EXCLUDED (not the topic) |

**New forks:**
- (F-a) Is H2's τ a trusted, shared order source? If yes, membership is by epoch; if no, by enumeration. This decides R3.
- (F-b) Where does R4's operand law live: kernel or architecture?
- (F-c) Where does a designation live: inside the new record, or outside every record?

**Source defects (separate):** S0300's header omits the DV and RV amendments (L3). S0300 L686 ("handled SAFELY") stands against L1193. "18 hash comparisons" at L1043 is hard-coded. S0917 L843–844 swaps Au and Auth. S0904 L630 announces three states and lists four. None of these is relied on by §9.17.

## 5. Next steps (dependency order)

1. **A-01 → A-03.** Redefine loss over members. Pick one A1. Restore "not only" in A3. Extend anchor_model with a designation switch, a lossy source, foreign entries and two operands. Done when the probe results above are reproduced and the claims are restated to match.
2. **A-08.** Re-home R4's operand part. Rewrite K1's cell. Add the must-do. Done when K1's cell cites no store-removal act.
3. **A-05.** Write the two-history proof for (a). Add τ with CON-0251's premise. Fix the register's "only if". Done when the falsifier in RESEARCH-QUESTION Q-M is answered for D, not only for the estate.
4. **A-04.** Restate R3 (d) per CON-0254, then re-check §8.20.3's pointer.
5. **A-06, A-07.** Split K2-d. Fix the definition of designation. Re-run R3 on CON-0261.
6. Normal items A-09 to A-14.
7. **Question for the Principal:** is H2's τ to be trusted as a cross-record order source (F-a)? The theory of membership cannot settle it alone.

## 6. Limits

- Ch. 2: not reviewed. Ch. 3: only CON-0162, CON-0258 and four re-verdict rows were checked against the consolidation's A-13, A-06 and B-31.
- I did not open S0136–S0146; I used them through Ch. 8.
- The EXT works were not opened.
- The model probes are small-scope. A passing probe is not a proof.
- R2 (undefined_terms.py) was not re-run.

## Appendix

- **Stale lines:** §9.15 L204 and L265 still say "L1–L10".
- **Quantifiers:** CON-0263's register "iff" does not match the section's "imply" plus a single necessity witness. In R4, "necessary" means "fails in some scenario".
- **Trivial cases:** the restated K2, R3 (c) and R4 are all satisfied by a kernel that never changes a designation and never removes anything. Only K2-d's existence is a must-do, and nothing owns it.

**Files opened:**
- W/06-theory/manuscript/review/TURN8-RECORD-IDENTITY/{STEP1-INVENTORY-A.md, BRIEF-STEP2.md, RESEARCH-QUESTION.md}
- W/06-theory/manuscript/chapter_09/09.17-the-designated-record-and-its-anchors.md
- W/06-theory/manuscript/chapter_09/09.15-the-trust-boundary.md
- W/06-theory/manuscript/chapter_08/08.18-…md, 08.19-…md, 08.20-…md, 08.21-…md
- W/06-theory/manuscript/chapter_03/03.02-derived-attributes.md (L1–20)
- W/06-theory/manuscript/review/CONSOLIDATION-S0131-S0150/SETTLEMENT.md
- W/06-theory/CONCEPT-REGISTER.tsv (selected rows)
- W/tmp/tools/{anchor_model.py, anchor_model-run-20261024.txt}; the headers of proof_step_check.py, inbound_citations.py and corpus_quote_check.py
- Six sources, from the step-1 reads. No new corpus files.

**Searches and commands run:**
- `git show --stat ca75864ef`; `git show ca75864ef` (register, 09.15, change log, ch. 8, map, EXT; output partly viewed)
- grep: "CON-0258|CON-0038" in 03.06; "H2" in 09.08; "recording time|position|D" in 09.13; "L1–L10" in 09.15; "A-13|A-06" in the consolidation's REPORT-A.md; "B-31" in its REPORT-B.md
- Python register extraction (CON-0162, 0191, 0193, 0239–0263; CON-0008, 0009, 0038, 0041, 0043, 0065)
- `proof_step_check.py 'chapter_09/09.17*.md'` (0 lemmas)
- `inbound_citations.py CON-0261 CON-0262 CON-0263 "§9.17 R2/R3/R4"`
- `corpus_quote_check.py` on 09.17 (12 found, 0 not found)
- Scratch probe `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/anchor_probe.py`, run with `python3 -I` over anchor_model.py. Output: tautology 0 exceptions; coverage-A1 gives A2 0 witnesses and A3 2,638; A1 failing with A2 and A3 holding: single operand misses 1,862, two operands miss 0.
- ls of the review, chapter_03, chapter_08, chapter_09 and tools folders. No file edited.

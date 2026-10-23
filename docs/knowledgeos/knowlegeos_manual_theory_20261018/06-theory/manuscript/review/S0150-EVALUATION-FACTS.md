# S0150 evaluation: facts from the consolidation S0101–S0130 to window C28

*Prepared by the Professor, 2026-10-23, at the Principal's instruction ("facts only, no proposals for rule text"), for entries 57–58 and the rule decision at S0150. The rules are frozen, and nothing here proposes a change. Times are wall-clock UTC from commits, report save stamps and the source tool's start and close records. Reviewer times are the harness's subagent durations.*

*Disclosure: a grep for "six practices" in `03-methodology/` surfaced four lines of `S0150-CONSOLIDATED-REVISION-PROPOSAL.md` (L19, L28, L33, L38), the Rule Reviewer's file committed by another session (`4711ef027`). Nothing else in it was read, and nothing here relies on it. The definitions of the six practices are taken from `PRINCIPAL-DECISION-RECORD-RULES-20261018.md` (L340–360).*

## 1. Time by activity

| Activity | Consolidation S0101–S0130 | C25 | C26 | C27 | C28 | C25–C28 total |
|---|---|---|---|---|---|---|
| **Reading and writing** (open first source → draft commit) | — | 535 s | 500 s | 420 s | 230 s | **1,685 s** (20 sources; 84 s/source) |
| **Reviews** (harness) | A 957 + B 1,342 + integration 616 = **2,915 s** | 662 s | 630 s | 795 s | 717 s | **2,804 s** (140 s/source) |
| **Settlements** (last report saved → settlement commit) | **654 s** (17:32:57 → 17:43:51) | 156 s | 147 s | 107 s | 99 s | **509 s** (25 s/source) |
| **Kernel work** (edits to Ch. 9 kernel sections) | inside the 654 s (batch 4: §9.13, §9.15, §9.16 repairs) | 0 | 0 | 0 | 0 | **0 s** |
| **Records** (CONTEXT, session log, map, index, register) | inside the settlement | inside | inside | inside | inside | not measured separately |

**Not measured:**
- The consolidation's preparation (scope, frozen table, brief, R3) was done in an earlier context, before 16:58:45 when the scope was frozen. Its duration is not in this record.
- The Professor's step-8 files and briefs were written while step 1 ran, so they overlap the reviewer's time and are not separated.

**Per-source time cannot tell a full read from a skim.** The C27 and C28 reviewers observed this (per source, 19–174 s with writing included).

## 2. Cadence

- **The consolidation S0101–S0130:**
  - harness time 2,915 s (reviews only);
  - settlement 654 s;
  - **30 defects found in the rotated chapters** (Ch. 0: 12, Ch. 1: 18; union of A 18 and B 19, overlap 7);
  - the integration reviewer's Lincoln–Petersen estimate ≈ 49, which is indicative only, since the reviewers are not independent.
- **Per source, on this record's basis:**
  - consolidation reviews 97 s/source, plus settlement 22 s/source, giving **119 s/source**;
  - windows C25–C28: reading and writing 84 + reviews 140 + settlement 25, giving **249 s/source**.
- *The basis differs from the Principal's 10–15% estimate.* That estimate's basis is not in this record. On this basis the consolidation adds about 48% to window cost per source, and its review time alone is about 69% of the windows' review time per source. The figures are stated, not reconciled.

**Defects found later in work that had already passed review:**

| Window (work reviewed) | Defect found later | Found by, when |
|---|---|---|
| Consolidation settlement | I-11 not applied in the register (CON-0206 "converse") | C26 review, W-15 |
| C25 (§8.18) | C21 "reproducible at the plan's commit" is an inference, not attestable | source S0136, read in C26 (§8.19 C9) |
| C25 (§8.18) | S0131 L131, cited in §8.18, is a malformed table row. The book's claim is unaffected | sources S0143 and S0144, read in C27 |
| C26 (§8.19) | none found later | C27 step 9 (8 rows held) |
| C27 (§8.20) | CON-0250 kept "a hold on what contains it" after W-04's restatement, so the settlement was partly applied | C28 review, W-16 |
| C28 (§8.21) | — (no later window yet) | — |

**Findings per window, by severity (critical · high · normal):**
- C25: 1 · 5 · 11
- C26: 2 · 7 · 10. Both critical findings are by F4 only, with no dependants.
- C27: 1 · 8 · 9. The critical finding is by F1.
- C28: 3 · 5 · 8. One critical finding is by F1; two are by F4 only, with no dependants.

**Concepts INVALID as stated, then restated:**
- C25: none INVALID (five restated or scoped).
- C26: CON-0246, CON-0247.
- C27: CON-0252.
- C28: CON-0255.

**R5 calibration notes, "critical by F4 only, no dependants":** C26 W-08, W-09; C28 W-04, W-05.

## 3. The six practices (as defined in the decision record), C25–C28

| Practice | Where it helped (example) | Where it only added work, or was not applied |
|---|---|---|
| **1. Research question per theory turn** | no theory turn in this stretch | not exercised |
| **2. Prior work, corpus first** | The book search for existing homes ran every window and kept restatements out. In C26, S0136's lost update and writer switch were pointed to existing homes (§9.15 K4-o, §9.16 R4a). Re-reading S0137, already read topic-full, against the source in order found new theory (RC-4, RC-6, RC-10) | **Not applied as written for new concepts:** no logged corpus search (`branch_search.py --log`) and no outside literature for CON-0239–0256. CON-0251 restates a known result (happened-before; *Ext:* Lamport 1978) without citation. CON-0253 is close to the mutation-testing literature, and CON-0245 to the lost-update anomaly |
| **3. Read before claiming** | — | no helper text in C25–C28, so it held trivially. No added work |
| **4. Effort by risk** | One reviewer per window. The consolidation had two reviewers plus integration: A-only 11 and B-only 12 rotated-chapter defects, more than either alone. No kernel edits in windows (the Principal's rule) | — |
| **5. Challenge by type** | The reviewers' countermodels found all four derivations that were INVALID as stated: a shared counter (CON-0251's premise), a self-including closing entry (CON-0252), a small model (CON-0255), and the replica case (CON-0239) | A rival and a falsifier for each new concept were adopted from C28 on a reviewer's reading of entry 55 (c), (d). The first falsifier written was immune to refutation (C28 W-03). Practice 5 says definitions need no falsifier; CON-0255 was a derivation |
| **6. Verification marks** | — | **not written** into the C25–C28 briefs. All theory was authored and read by the Professor, so the marks would have been uniform. The omission is a fact |

**The C25 self-checks (the Principal: "keep it"):**
- **Second lineage pass.** It helped in C25 (S0134's decision text reaches the record only through the registrar, in its recommended wording) and in C26–C28 ("accepted" in seven producers' texts with no act). It missed remedy text written by a reviewer and adopted by the next amendment (C27 W-13).
- **Source-first attacks.** They helped: S0131's compliance test passes take-one-side (C25); S0145's trend counts its own "undetectable" findings (C27); S0148's "FAIL → PASS" against its own table (C28). One attack's arithmetic was itself wrong (C27 W-12, the recount).
- **Quote the rule relied on.** It helped where the rule's text decided (C26 W-16, §1.16.7 quoted directly). Quoted rules were still misapplied: G3 read as a block (C27 W-03), H3b read as agreement (C27 W-15), §9.13's information order where the truth order was relied on (C28 W-09).
- **The adversarial pass on the book's own claims, before review.** In each of C25–C28, an amendment or argument made in the pass failed under review: C25 W-01, C26 W-02, C27 W-02/W-03, C28 W-02. It also caught overstatements: C25 C17, C26 C3, C27 C1 and C2.

## 4. Skims

| Source | Window | Safeguard check | Lost theory? |
|---|---|---|---|
| S0123 | C23 | checked by the reviewer | no |
| S0125 | C23 | checked by the reviewer | **yes** (the authority's wording against the producer's gloss) |
| S0147 | C28 | checked by the reviewer | a little (acceptance in two places; role and actor merged) |
| S0149 | C28 | checked by the reviewer | **yes** (corpus evidence for the R_K fork; the source's DDD defects) |

**Totals.** 4 sources skimmed in reading order, all 4 checked, 2 lost theory and 1 lost a little.

**Related observation:** in C26 the Professor read S0139 in full where the reviewer would have skimmed it. The reviewer found that the full read added no theory beyond a skim.

## 5. Open kernel questions, with origin

| Question | Origin |
|---|---|
| The identity of the record: does §9.15 need an assumption that every reader and writer resolves the same record, or is it part of K2? | §8.18.3 (S0131, CON-0239) |
| K1's anchor holds for the entries it commits to; must it be re-taken at the operation it protects? | §8.18.3 (S0135, CON-0243) |
| The interpreter recorded (K7): governed versus recorded. Partly answered by S0136's reporting form | §8.18.3 (S0134); §8.19 C8 |
| D's integrity check over fields CE* does not read | §8.18.3 (S0131, CON-0240) |
| L9 needs K6's serialisation and K1's durability | §8.19.3 (S0136, CON-0245) |
| Membership in the record at write time | §8.19.3 (S0138, C18) |
| The declared-write claim and its authenticated actor (L10) | §8.19.3 (S0138, S0140; CON-0249) |
| A positive hold state, ended only by a disposition act | §8.20.3 (S0141, S0142; CON-0250) |
| Cross-record order through citation, with its premise (no shared order source) | §8.20.3 (S0141; CON-0251) |
| The closing-record rule for records about records | §8.20.3 (S0142; CON-0254) |
| Reference conditions for a gate (CON-0256) alongside CON-0243: one concept or two? | §8.21.3 (S0146) |
| T9-07 (a)–(g): F22 protocol questions (use in flight; carrying uses; several records; consumption target; check 21's scope; Φ and work-act uses; source of truth between K4-k and the owner's chain) | Ch. 9 TODO (C20–C22, consolidation) |
| T9-01–T9-06: required capabilities, state space, minimality, uniqueness, §9.6–§9.9 against the type, the relocation test | Ch. 9 TODO (earlier) |

*Not kernel questions, recorded in the same lists:* the bounded-context admission rule (CON-0085 INVALID against S0150's test, §8.21 C15); coverage versus the number of gates (§8.21.3).

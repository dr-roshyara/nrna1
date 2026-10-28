CONSOLIDATION S0151-S0181 REPORT A

Reviewer A (Opus). This is process independence, not personal independence: I did not read reviewer B's report or any working file. I edited nothing in the repository. My verdicts below are second, independent verdicts.

## 1. Assessment

- **Ch. 4, Ch. 5 and §8.22–§8.26 are reliable within their stated scopes.** Every quote I checked was found (see section 6). Almost all the defects are stale cross-references to Ch. 9.
- **The largest risk is the v18 completion of the designation rule.** My model probes show three places where it does not do what the book says:
  - guarded appends *inside one serialised kernel* can produce a record outside D;
  - a bypass write can be promoted into the members of the record;
  - D's chain of designations can name a carrier the kernel never designated, and a resolver that follows designation entries (§8.22 C2) would follow it.
- **§8.26 C6 uses content from a file outside the corpus,** reached through two summaries.

## 2. Findings (ranked)

| ID | Finding | Sev. | Type | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|---|
| A-01 | Guard clause 2 ("maximal among the entries the kernel accepted") and "bypass write never promoted" both presuppose the kernel can tell its accepted entries from bypass entries in the same store. The book never states how. Model v18 uses an oracle flag `bypass` (ce_model.py L218, L228) that no recorded field carries. Without the flag the bypass entry counts as a maximal accepted entry (my probe P3b: maximal of C1 = [gD, z]). Content cannot separate the two, by the same argument as R1 / R3 (a). A lone bypass designation (C1→C7) leaves the record **in D** (P3: `in_D` True), so the "one chain" names C7. This contradicts §9.17 R3 (c) "a bypass designation, which the check then reports as 'a fork'". | **critical (F3)** | defect (missing premise) | `cons_a_designation_probe.py` P3, P3b; §9.17 L164–175; §9.13.2 L48. Dependants confirmed: T9-10 (b) (its framing assumes detection); §8.22 C2 ("enforcing operation now exists"); CON-0264 meaning | State the premise: each accepted entry, its store field and each designation are **kernel-attested** (covered by the integrity check, as K5). An unattested entry fails integrity and takes the record out of D, reported as a K1/K2-w failure, not as "a fork". Scope §8.22 C2 to attested designations. | premise stated; a model check without the oracle flag |
| A-02 | **Carrier reuse.** C1→C2→C1→C3, every step through the guarded `append`: the guard accepts every step, and `in_D` then refuses the record as "two designations close the same store ['C1'] (a fork)". So D is not closed under guarded appends inside one kernel. This contradicts §9.13.2 ("the append guard and the static test agree") and the C33 verdict "sound within K6". The unstated premise is that a store is carrier at most once. | high | defect (missing premise) | probe P1 | Either the guard refuses a designation naming a store already closed (state this as a premise), or D's clause keys on (store, epoch). | rule stated + model check |
| A-03 | Membership is the citation down-closure from the maximal accepted entries. A kernel-accepted entry that cites a bypass entry therefore promotes the bypass entry. Probe P2: z (bypass) is in `members_closed`, and the record stays in D. "Never promoted" (§9.17 R3 (c)) is false as stated. Check 30 tests only an uncited bypass write. | high | defect | probe P2 | The guard refuses an append that cites a non-accepted entry, or the down-closure stops at non-accepted entries. With A-01 this becomes an integrity failure. | stated + check |
| A-04 | §9.17 R4 says "D has no carriers to retire". Since v18, D reads a store per entry and checks the carrier chain. Two things are stale as a result: R4's architecture/kernel classification, and the K2-w row of R2's boundary table ("only if the resolution of the kernel's own appends is a kernel step"), which v18's kernel-set store now meets. | high | defect (stale boundary claim) | §9.17 L121, L215; L164, L173 | Restate R4's classification: D names carriers, the retirement act is architecture. Update the K2-w row. | text restated; R3 run |
| A-05 | §8.26 C6 rests on S0178 L161 (T5) and S0179 L43 (D11). Both take the T3 PKS context set (CBC-1…4) and "different systems" from `docs/knowledge_tranfer/…` files. Those files are not in `02-FILES.jsonl` and not on the allowed list. This contradicts §8.26's own line "nothing they say about those files is used". | high | defect (access pattern) | S0178 L22, L161; S0179 L43; the allowed-list and index greps returned 0 hits | Drop the T3 content from C6. Keep only S0179's own "not a kernel" claim. Widen ACCESS-NOTE from the six files to every non-corpus file. | C6 restated |
| A-06 | §4.1 makes recurrence start at "the first independent instance", and by §4.1's definition no in-estate pair is independent. §8.06 C3 adds that independence can be refuted on the record but never established. So the rule attached to CON-0069 (VALIDATED) can never be met on the record: a learning loop that never promotes satisfies it. The two are not connected, and the homonym table in Ch. 5 omits §8.06's sense of "independent". | high | omission | §4.1 L16–18, L38; §8.24 C10; 05.00 L32 | Link §4.1 C2 to §8.06 C3. State what the rule requires, or route it to DB-2. Add the §8.06 sense to the homonym table. | linked; DB-2 noted |
| A-07 | §8.23 C12's title and body still say "the fork stays open (T8-04)", although T8-04 was decided on 2026-10-27. | normal | stale | 08.23 L121, L126 | Add the decision pointer. | edited |
| A-08 | The §9.15 K2 cell and CON-0261's register meaning attach "every later carrier by a designation entry in D" to "decided by the Principal 2026-10-25". §9.17 itself labels that clause K2-d, the Professor's presupposition. The Principal's decision is being read through the producer's gloss. | normal | defect | 09.15 L75; register CON-0261 | Label it as in §9.17. | edited |
| A-09 | CON-0261's sources list S2055 L745–757 and S0917 L1322/1327. §9.17 says S2055 is "not cited as support" and calls those S0917 lines analogies "not on point". | normal | defect | register; 09.17 L66–70, L314 | Remove both, or mark them as analogies. | edited |
| A-10 | The `store` field is required by D's chain clauses but appears in no required-field list (§9.13.2). The model treats a missing store as the root carrier (`x.get('store', ROOT_CARRIER)`, L321), which fails open. | normal | defect | ce_model L107, L173, L321 | Make `store` a required field of every kind; a missing store puts the record outside D. | stated + check |
| A-11 | THEORY-MAP §9 is stale: last read S0150, 149 sources read, Ch. 8 §8.1–8.21, Ch. 9 §9.1–9.16, "71 of 134 INVALID". Map L190 garbles the relation between CON-0256 (i) and CON-0257. BOOK-OUTLINE's rotation table still shows S0150 and S0175 as "planned". | normal | stale | map L190, L360–369; outline L198–202 | Update all three. | edited |
| A-12 | Two statements in the C33 settlement do not hold. (i) "R-CONFLICT is not homed": its no-side rule is homed at §8.17 C9 and §8.19 C26; what is unhomed is its ownership by Evidence. (ii) The W-02 consequence (a bypass designation takes the record out of D) fails for a lone bypass designation (A-01). | normal | defect | book search; P3 | Correct both in the next settlement. | — |
| A-13 | §8.26 C5 calls S0180 "one line with S0158 and S0159", but S0180 L32–41 cites S0128, S0152 and S0172. §8.26 C3 says the act's text is "not quoted", although S0130 (the decision record) is on the allowed list and was read earlier. | normal | defect | S0180 L32–41 | Correct the lineage; quote S0130. | edited |

Lower-ranked defects are in Appendix A.

## 3. Concept verdicts

| Concept | Verdict | Reason · scope |
|---|---|---|
| CON-0261 | PROVISIONAL | R1's 4-step proof and countermodel M2 are valid. K2-d is undercut by A-01, and the K2-w row is stale (A-04). |
| CON-0264 | PROVISIONAL (core) | The R3 (a) two-history proof and (b) under its premises are valid. The (c) completion is **INVALID as stated** in two clauses (A-02, A-03) and rests on A-01's premise. |
| CON-0265 | **VALIDATED** | Within its stated scope (exact list, not taken from the survivor, no write between check and act). I checked C1–C5 step by step against the definitions of G0, G1 and G2. |
| CON-0262, CON-0263 | INVALID as stated (confirmed) | R3 shows them cited only as invalid, beside their restated forms. |
| CON-0068, 0070, 0071, 0107 | VALIDATED | 0068 as a record design (quote at L113 checked); 0070 for confirmatory counts; 0071 in its definitional part (a tautology); 0107 as a description of its source. |
| CON-0069 | VALIDATED (the separation of streams only) | The recurrence rule attached to it is unsatisfiable on the record (A-06). |
| CON-0113 | INVALID as stated (confirmed) | The restated form in §4.3 C1 holds. |
| CON-0048, 0049, 0077 | VALIDATED | As descriptions of the source, or as a design. |
| CON-0131 | VALIDATED | The proof is valid and its hidden premise is stated. That this engine is subject-blind rests on one probe and stays reported. |
| CON-0079 | VALIDATED | Scope: Q2 read as demand, the source's own gloss. Rival reading: under "ought implies can", Q2 depends on Q1. |
| CON-0080, 0101 | PROVISIONAL | The book's own preconditions are unmet, and S0896 has not been read in full. |
| CON-0123, 0168, 0174 | PROVISIONAL | One line, one estate. CON-0168 is also limited by the composite object in §5.6 C7. |
| CON-0165 | PROVISIONAL | "≠" can mean distinct attributes or distinct holders. As distinct holders it has a counterexample: a person who both decides and runs the command. |
| CON-0175 | PROVISIONAL | Tension: D does not admit supersession, so a recorded supersession takes the record out of D (§9.13.2 L52). |
| CON-0187 | PROVISIONAL | A gate that refuses everything satisfies it, so it needs a cost or must-do half. |
| INVALID cores 0050, 0052, 0053, 0078, 0081, 0109, 0124, 0144, 0145 | Confirmed as restated | None is cited as the home of a live claim. CON-0081 at §9.05 L35 is cited for its defect only. |
| v18 completion | PROVISIONAL | Not sound within K6 as stated (A-02). |

**Rotated-chapter defect count:**
- **Ch. 4: 6** (A-06; §4.2 C4 stale "RD-1…RD-7 not yet read"; TODO header "Ch. 4 … is planned"; §4.2 C3 over-identification with CON-0070; §4.1 C6 "coverage is a set of path patterns" generalised from one monitor; CON-0107's basis "not yet reviewed" is stale).
- **Ch. 5: 10** (§5.2 "Ch. 8, planned"; three open questions already answered by §9.13.2 and not linked: §5.5.4 Q1 recorder field, §5.6.3 Q2 pre-emptive targets, §5.7.3 Q1 retirement not admitted; CON-0175 vs D; CON-0165 ambiguity; CON-0187 trivial gate; CON-0131's basis stale; CON-0145 and CON-0175 share one register term; the homonym table lacks §8.06's sense of "independent").

## 4. Changed theory: the designation rule

- **Applied as worded?**
  - T9-09 (b) (F or U refused, in the same step) is applied as worded; I checked `append_guard` L1013.
  - T9-09 (a) narrows "every entry no other entry of that store cites" to accepted entries. The ruling's own limit supports this, and it is labelled as the Professor's completion. It becomes operational only with A-01's premise.
- **Is the completion needed?**
  - Carrier in force: needed; without it, one kernel accepts a fork.
  - Kernel-set store: needed.
  - Accepted entries only: needed, but not computable without attestation.
  - The three D clauses: needed to detect forks across merges, but "one designation per store" breaks carrier reuse.
  - All of these are correctly labelled.
- **Is the scope right?** "One serialised kernel" is too generous (A-02). "Across applications, detected, not prevented" holds only if two kernels write one store. That premise is unstated and is itself a K6 break.
- **T9-10 (a), one coordination point per carrier: recommend yes.** The simplest form is "each carrier is appended to by one serialised kernel, its owner", in the style of F22's owner. This is technical. It goes to the Principal only if carriers shared across applications are wanted, because that is an F22-type ownership choice.
- **T9-10 (b), may a bypass entry take the record out of D? Recommend yes,** fail-closed, as §9.13.2 already does for integrity failures. Two conditions: the entry is attested (A-01), and the report says "not accepted by the kernel" rather than "fork". This is technical. The availability cost (one bypass write disables every answer on the record) is a normative trade-off. Bring it to the Principal only if the existing fail-closed integrity rule is to be revisited.

## 5. Architecture view

- **Turn 8 and the decisions of 2026-10-25 and 2026-10-27 added:**
  - one record kind (designate);
  - one per-entry field (store), kernel-set but unlisted;
  - one legitimacy assumption (L11);
  - K2 restated as d/w/r;
  - append-guard steps.
- **C29–C33 added no concept.** Their content is observation of one estate, with lineage correctly reduced to one line.
- **Unsupported:** "never promoted" (A-03) and "D has no carriers" (A-04).
- **Not circular:** I found no isolated concept and no circular definition.
- **Kernel or application:**
  - CON-0261 and CON-0264: kernel, but not stable (changed three times in four days); CON-0264 is not minimal (three routes).
  - CON-0265: architecture, stable.
  - Ch. 4 concepts: methodology or application.
  - Ch. 5: CON-0131 and 0165/0123/0168/0174/0175 are kernel candidates as act-record attributes or checks (F13a); CON-0080 is a kernel candidate as a contract gap (§9.10 Q7); the rest are application practice.

## 6. Limits

- Ch. 4 and Ch. 5 rest partly on Ch. 0–2, which I did not open; Ch. 0–2 points were checked only through book search hits.
- I read §9.13 only at L1–113 and §9.15 only at its K/L cells.
- Corpus "in meaning" paraphrases were spot-checked only where a pattern check needed it.
- The probes are small models, not proofs.
- Quote checks: the tool found every bracketed quote; my short-form check found 53 of 54, and the one miss ("Standing rule") is a cited-range artefact, the text is at S0029 L12.
- Model run: my v18 run is identical to the saved run.

## Summary (≤15 lines)

1. Ch. 4 and Ch. 5 hold within scope; there are 6 and 10 defects respectively, mostly stale links to Ch. 9.
2. CON-0265 is VALIDATED; the other turn-8 concepts stay PROVISIONAL; all INVALID cores are confirmed.
3. One critical finding: the designation completion presupposes that the kernel can tell its own entries from bypass entries. The model uses an oracle flag for this, and without it a bypass designation stays in D (A-01).
4. In one kernel, reusing a carrier gives a record outside D through guarded appends only (A-02).
5. A bypass entry cited by an accepted entry is promoted (A-03).
6. R4's "D has no carriers" is stale (A-04).
7. §8.26 C6 uses content from a file outside the corpus, through summaries (A-05).
8. CON-0069's recurrence rule is unsatisfiable on the record (A-06).
9. T9-10: recommend one owning kernel per carrier, plus kernel attestation with fail-closed reporting. Both technical.
10. The map, the outline and parts of the C33 settlement are stale or partly inaccurate (A-11, A-12).
11. Coverage: most items are COVERED. The main MISSING items are the evidence lifecycle, the governed command loop, R-CONFLICT's ownership by Evidence, and the trivial-model point on ADR-KOS-002.

---

## Appendix A: lower-priority findings (normal)

- **Stale or unlinked text:**
  - §4.2 C4 (RD-1…RD-7 "not yet read"; §4.3 reads S0044);
  - the Ch. 4 TODO header ("planned");
  - §5.2 ("Ch. 8, planned");
  - §5.5.4 Q1, §5.6.3 Q2 and §5.7.3 Q1, each answered by §9.13.2 (recorder field; targets not restricted; retirement not admitted);
  - T8-04's Problem column reads only "as before".
- **Register:**
  - CON-0107 and CON-0131 carry the basis "not yet reviewed" against a filled reviewed column;
  - CON-0145 (INVALID) and CON-0175 share one term.
- **Scope:**
  - §4.2 C3 calls a minimum time window a "fixed-class rule" (it is a minimum-exposure rule);
  - §4.1 C6 generalises "coverage is path patterns" from one monitor.
- **Pattern checks:**
  - rule applied beyond its text: A-03 and A-08;
  - premise missing: A-01, A-02, A-03;
  - INVALID concept cited as a home: none;
  - a source's own status adopted as shown: none (every instance is labelled "reported");
  - summary used as evidence for what it summarizes: A-13;
  - authority's decision read through a gloss: A-08;
  - non-allowed content reached through a summary: A-05.
- **C33 settlement spot-check:** W-01 to W-11 are each applied where stated in the copy. The exceptions are A-12 (i) and (ii).
- **Turn 8 / D:** a designation that closes nothing is refused, so an empty root carrier cannot be changed before its first append. This is an edge case worth stating.

## Appendix B: coverage against the window inventories

Key: C = COVERED, M = MISSING, D = DISTORTED, JE = JUSTIFIABLY EXCLUDED.

**C29**

| Src | Items | Mark | Location |
|---|---|---|---|
| S0151 | 2, 5, 6, 9, 14 | C | §8.22 C1, §8.22.3 |
| S0151 | 3, 4, 7, 8, 10–13 | C or JE (duplicate of S0149) | §8.21 C13; §1.11, §6.1 |
| S0151 | 1 (identity) | M | T0-03; search found nothing |
| S0152 | 2–8, 12, 13 | C | §8.22 C2–C4, C10; §8.06 |
| S0152 | 9 ("single" scope gap) | M | — |
| S0152 | 1, 10, 11 | JE (plan) | — |
| S0153 | 2, 3, 6–8, 10, 13 (n = 1) | C | §8.22 C5, C6, §8.22.3 |
| S0153 | 1, 4, 5, 12, 13 (tension) | M | — |
| S0153 | 9, 11 | JE | — |
| S0154 | 1, 2, 4–8, 10, 12 | C | §8.22 C7, C8; §8.26 C2 |
| S0154 | 3, 9, 11 | M | — |
| S0158 | 1, 3, 4, 6, 8, 9, 11, 13 (drift, article) | C | §8.22 C9–C11, §8.22.3; §8.23 C3 |
| S0158 | 2, 5, 7, 10, 12, 13 (undefined kernel) | M | — |

**C30**

| Src | Items | Mark | Location |
|---|---|---|---|
| S0159 | 1–4, 6, 13 | C | §8.23 C1–C3 |
| S0159 | 7 | C in part | §8.17 C9 |
| S0159 | 5, 8–12 | M | — |
| S0160 | 1–3, 5, 8, 11, 12 | C | §8.23 C4–C7, §8.23.3 |
| S0160 | 4, 6, 7, 9, 10, 13 | M | — |
| S0162 | 3 (rule), 4–8, 14 | C | §8.23 C7–C9, §8.23.3 |
| S0162 | 1, 2, 3 (split), 9–13 | M | — |
| S0163 | 1, 2, 5, 6 | C | §8.23 C10 |
| S0163 | 3, 4, 7, 10–12 | JE (business) | — |
| S0163 | 8, 9 | M | — |
| S0164 | 1–7, 9, 11–15 | C | §8.23 C11, C12 and their homes |
| S0164 | 8; 4 (classes A–E); 10 (partly) | M | — |

**C31**

| Src | Items | Mark | Location |
|---|---|---|---|
| S0165 | 2–5, 8–12, 14 | C | §8.24 C1–C5, §8.24.3 |
| S0165 | 1, 6, 7, 13 | M | — |
| S0166 | 4–6, 8, 9, 11–14 | C | §8.24 C6, C7, §8.24.3 |
| S0166 | 1–3, 7, 10 | M | — |
| S0167 | 1–4, 6–9, 11–13 | C | §8.24 C8; RESEARCH-PURPOSE for 7 |
| S0167 | 5 | M | — |
| S0167 | 10 | JE | — |
| S0168 | 1–8, 10, 12–15 | C or partly | §8.24 C3, C9–C11, §8.24.3 |
| S0168 | 9, 11 | M | — |
| S0169 | all | C (duplicate) or JE | §8.24 C12 |

**C32**

| Src | Items | Mark | Location |
|---|---|---|---|
| S0171 | 1–4, 6, 8, 11, 12 | C | §8.25 C1, C2, §8.25.3 |
| S0171 | 5 (regrouping), 7 (binary results), 9 | M | — |
| S0171 | 10 | JE | — |
| S0172 | 1, 3–8, 10–14 | C | §8.25 C3–C5, §8.25.3; §2.8 |
| S0172 | 2, 9 (owner of SC-2) | M | — |
| S0173 | 3, 4, 6–10, 13 | C | §8.25 C6, C7 |
| S0173 | 1, 2, 5, 11, 12; 6 ("Implemented" without acceptance) | M | — |
| S0175 | 1, 3, 5, 7, 8, 10, 12 | C | §8.25 C5, C7, C8 |
| S0175 | 2, 4, 6 | M | — |
| S0175 | 9, 11 | JE (access, business) | — |
| S0176 | 1–3, 5, 7, 9, 12 (T1, T4) | C | §8.25 |
| S0176 | 6 (hexagonal), 10, 11 | JE (access: S0155) | — |
| S0176 | 4, 8 (the S0158 attribution), 12 (T3) | M | — |

**C33**

| Src | Items | Mark | Location |
|---|---|---|---|
| S0177 | 1, 4, 5, 9, 11, 12, 13 (T1), 15 | C | §8.26 C1, C3, C4; §8.25 C3; settled defects |
| S0177 | 3 | C in part | §8.17 C9; Evidence ownership M |
| S0177 | 2, 6, 7, 8 (partly), 10, 13 (T2–T4), 14 | M | — |
| S0178 | 4, 6, 8–10, 12, 13 | C | §8.26 C1; §8.23; §8.25 |
| S0178 | 15 | D (rests on a non-corpus file) | §8.26 C6 |
| S0178 | 1, 2, 5, 7, 11, 14 | JE (access: S0161) | — |
| S0178 | 3, 16; R-CONFLICT ownership | M | — |
| S0179 | 1, 2, 5, 8, 12, 13 | C | §8.26 C1, C3, C6, §8.26.3 |
| S0179 | 2 (P1), 6 | JE (access; settled defect) | — |
| S0179 | 3, 4, 7, 9–11 | M or partly | — |
| S0180 | 1–3, 8, 11 (log as stream) | C | §8.26 C5, §8.26.3 |
| S0180 | 7, 12 | settled source defects | — |
| S0180 | 4–6, 9, 10, 11 (idempotency), 13 | M | — |
| S0181 | 5, 6 | C | §8.24 C6; §8.26 C2 |
| S0181 | 1, 2, 7, 11 | JE (access: S0155) | — |
| S0181 | 9 (T5) | D (non-corpus) | — |
| S0181 | 3, 4, 8, 10 | M | — |

**Ranked gaps:**
1. The evidence lifecycle, and "AI creates candidates, not knowledge" (S0160 4, 6; S0177 6).
2. The governed command loop compared with the kernel's append steps (S0158 10; S0180 4).
3. R-CONFLICT's ownership by Evidence.
4. ADR-KOS-002 is all constraints, so the trivial model satisfies it (S0160 9).
5. Domain events vs integration events (S0162 9; S0180 5).
6. No owner named for SC-2.

**New fork:** may a store be a carrier twice? (A-02)

**Source errors** (listed separately; nothing in the theory relies on them): S0154 "CURRENT" includes Python; S0173 review-state slip; S0175's undeclared [GOVERNANCE] tag.

## Files opened

Prefixes used below:
- W = `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018`
- C = `W/06-theory/manuscript/review/CONSOLIDATION-S0151-S0181`
- B = `C/BOOK-UNDER-REVIEW`

**Instructions and scope:**
- `W/03-methodology/REVIEWER-INSTRUCTIONS.md`
- `C/REVIEWER-NOTE.md`, `C/SCOPE.md`, `C/BRIEF.md`, `C/frozen-table.txt` (head), `C/R3-OUTPUT.txt`

**Inventories and settlement:**
- `W/06-theory/manuscript/review/C29-S0151-S0158/STEP1-INVENTORY.md`
- `W/06-theory/manuscript/review/C30-S0159-S0164/STEP1-INVENTORY.md`
- `W/06-theory/manuscript/review/C31-S0165-S0169/STEP1-INVENTORY.md`
- `W/06-theory/manuscript/review/C32-S0171-S0176/STEP1-INVENTORY.md`
- `W/06-theory/manuscript/review/C33-S0177-S0181/STEP1-INVENTORY.md`
- `W/06-theory/manuscript/review/C33-S0177-S0181/SETTLEMENT.md`

**The copy:**
- `B/06-theory/manuscript/chapter_04/`: all six files
- `B/06-theory/manuscript/chapter_05/`: all ten files
- `B/06-theory/manuscript/chapter_08/`: 08.22, 08.23, 08.24, 08.25, 08.26, TODO.md
- `B/06-theory/manuscript/chapter_09/`: 09.17 (whole), 09.13 (L1–113 and grep), 09.15 (headings and K/L cells by grep), TODO.md
- `B/06-theory/CONCEPT-REGISTER.tsv` (rows by grep; counts by python)
- `B/06-theory/THEORY-MAP.md` (L1–30, L360–372, greps)
- `B/06-theory/manuscript/BOOK-OUTLINE.md` (L186–206, grep)
- `B/tmp/tools/ce_model.py` (L160–329, L985–1140, L1344–1350, L1955–2113, greps; imported by my probe; run read-only)
- `B/tmp/tools/ce_model-run-20261027-v18.txt` (wc, diff)
- `B/tmp/tools/book_search.py` (L1–40; run)

**Tools and indexes:**
- `W/tmp/tools/` (listing)
- `W/tmp/tools/corpus_quote_check.py` (L1–30; run)
- `W/tmp/tools/inbound_citations.py` (L1–25; run)
- `W/tmp/tools/register_home_check.py`, `W/tmp/tools/undefined_terms.py` (run)
- `W/02-FILES.jsonl` (grep; read by the tools and my scripts)
- `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv` (head, grep, python)

**Corpus, all checked on the allowed list first:**
- Opened by me: S0181 (L8–24, L53–62), S0179 (L12–45), S0178 (grep), S0176 (L110–126), S0177 (L28–40, L104–110), S0175 (L30–40), S0180 (L30–42), S0029 (grep), S0027 (L113–121), S0160 (grep "freez").
- Read programmatically by the quote checks: S0019, S0023, S0024, S0027, S0029, S0030, S0040, S0044, S0051–S0053, S0055, S0058, S0061, S0064, S0065, S0076–S0080, S0129, S0152–S0154, S0158–S0160, S0162–S0166, S0168, S0171–S0173, S0208, S0253, S0300, S0721, S0896, S0917, S1296, S1375, S2377, S2834.

**Access record:**
- No file was refused.
- I opened none of the files on REVIEWER-NOTE's item-4 list, none of S0155/S0156/S0157/S0161/S0170/S0174, and no Ch. 0–2 basis, sealed, hold-out or chronological-read file.
- The copy's chapter_00–02 files and BASELINE-REVISIONS.md appeared only as book-search hits; I did not open them.

**Scratch files** (all in `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/`, scripts run with `python3 -I`):
- `cons_a_designation_probe.py`: probes P0–P3b
- `cons_a_short_quote_check.py`
- `cons_a_ce_model_run.txt`
- `cons_a_r3.txt`

## Searches run

- **Listings:** `find`/`ls` of `C` and `B`; `wc -w` on the in-scope files; `ls W/tmp/tools`.
- **Greps:**
  - `09.13` for `designat` and headings; `09.15` for the K/L cells;
  - `ce_model.py` for designat/bypass/store/guard/main/helpers;
  - the register for 38 concept IDs;
  - the map for C29–C33, turn 8, position and counts; the outline for "rotat";
  - the allowed list for the six excluded IDs, `knowledge_tranfer`, `204018`/`123902`/`0841`/`204431`, and `STATE-DURABILITY`;
  - `02-FILES.jsonl` for `knowledge_tranfer`, `204018`, `123902` and the durability records;
  - S0178 for `knowledge_tranfer`/`0841`/`204018`; S0029 for "standing rule"; S0160 for "freez";
  - a regex extraction of the quoted S-IDs in 16 section files, checked against the allowed list (python).
- **Book search (copy):** "dimension admission test"; R-CONFLICT; "Governance Domain Platform"; "federated projection|graph is a projection"; Method.{0,5}Binding.{0,5}Evidence; SC-1; "Transactional Outbox|outbox"; EvidenceQualified; "scenario test"; ProductVersion; "integration event"; Hexagonal; "evidence lifecycle|Governance-Accepted"; "governed command|Command → Application"; "non-registration|absence of registration"; "AST is necessary"; "Knowledge Governance"; "four pillars|pillars"; "god service|Knowledge Runtime"; "Decision aggregate|ChosenOption"; "Communication Engineer|Communication bounded"; "trustworthy history"; "independence = true|INDEPENDENCE = TRUE"; warn-only; knowledge-lint; "first-class domain capability|lineage"; Delivery; "Assurance Context"; "same storage".
- **R1** `register_home_check.py --root B`; **R2** `undefined_terms.py --root B` (full output, then tiers A–C filtered to the in-scope sections).
- **R3** `inbound_citations.py --root B` on CON-0264, "9.17 R3", "§8.23 C12", "§8.22 C2", CON-0261, "§4.1 C2", CON-0069, "§8.26 C6", CON-0175, "§5.5 C1", CON-0165. The citing lines are in `cons_a_r3.txt`; the dependants are summarised in the findings.
- **Quote check** `corpus_quote_check.py` on Ch. 4 §4.1–4.3, Ch. 5 §5.1–5.7, §8.22–8.26 and §9.17 of the copy, plus my short-form check on §4.1, §5.1, §5.2 and §5.3.
- **Model:** `ce_model.py` v18, run once (output identical to the saved run); probes P0–P3b.

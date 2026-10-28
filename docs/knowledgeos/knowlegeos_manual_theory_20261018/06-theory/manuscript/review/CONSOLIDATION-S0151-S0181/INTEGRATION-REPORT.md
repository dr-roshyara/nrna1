CONSOLIDATION S0151-S0181 INTEGRATION REPORT

Integration reviewer (Opus). This is process independence only, not personal independence. I read both reports in full (hashes match the commission: A fafe6c55…cf34, B 273b843e…def0). I did not open either reviewer's scratch files. Every probe below is my own. I edited nothing in the repository, and every Python run used `python3 -I -B`, so no bytecode was written.

Evidence labels:
- **V** = I checked it, and the command or record is named.
- **R** = reported by A or B and not re-run by me.

My probe script and its output:
- script: `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/cons_i_probe.py`
- output: `…/scratchpad/cons_i_probe_out.txt`

The script imports the copy's `ce_model.py` v18. Probes:

| Probe | Case | Result (V) |
|---|---|---|
| I1 | carrier reuse C1→C2→C1→C3, all through guarded `append` | all three accepted. The guard then requires the designation to re-cite x1, which d1 had already closed. Result: `in_D` = False, "two designations close the same store ['C1'] (a fork)" |
| I1b | C1→C2→C1 only | stays in D |
| I2 | a lone bypass designation C1→C7 | in D. With the oracle flag, `carrier_in_force` = C1; without it, C7. The next kernel append lands in C1 and the record stays in D |
| I2b | a bypass designation plus a kernel designation of C1 | out of D as "a fork" |
| I3 | an accepted y cites a bypass z | `members_closed(d1)` contains z; the record stays in D |
| I4 | an unflagged z | the designation is refused: "omits the maximal entries ['z']" |
| I5 | a foreign annulment of g, written into closed C1, not cited by d1 | not a member, yet x1 goes T→F; record in D |
| I6 | an entry labelled C1 after C1 was closed, or with no store key | record in D; the entry is evaluated as T |

Model re-runs (V):
- `ce_model.py` v18: output identical to `ce_model-run-20261027-v18.txt`.
- `anchor_model.py` v2: output identical to the saved v2 run.

## 1. Classification of every finding

| A | B | Class | Disagreement |
|---|---|---|---|
| A-01 oracle flag | B-02 (oracle; P1, P3) | both | severity (A critical, B high); premise (A: attestation; B: a kernel-held accepted set, or K5 binding) |
| A-02 carrier reuse | — | A only | — |
| A-03 bypass promoted | B-02 (P2) | both | — |
| A-04 "D has no carriers" stale | — | A only | B's CON-0265 verdict repeats "D has no carriers", so the two implicitly disagree |
| A-05 §8.26 C6 non-corpus | B-06 | both | fix: A drops the content, B relabels or removes it |
| A-06 §4.1 recurrence unsatisfiable | B-11 §4.1 premise "actor" | both, at one locus; different defects, complementary | — |
| A-07 §8.23 C12 stale | — | A only | — |
| A-08 K2 cell attribution | B-04(a) | both | — |
| A-09 CON-0261 sources | — | A only | — |
| A-10 `store` not required | B-03 | both | — |
| A-11 map/outline stale | B-24, B-04(b) in part | both | A adds the outline and map L190; B adds the §9.15 status line |
| A-12 C33 settlement errors | — | A only | — |
| A-13 S0180 lineage; S0130 | — | A only | — |
| — | B-01 foreign classification inert | B only | — |
| — | B-04(b) §9.15 status line; B-04(c) §9.17.1 items 3–4 | B only | — |
| — | B-05 cross-application fork, total refusal | B only (A mentions the availability cost in its T9-10 text) | — |
| — | B-07 INVALID home in §8.22 C7 | B only | **disagree:** A's pattern check says "INVALID cited as a home: none" |
| — | B-08 §8.24 C7 circular | B only | **disagree:** A found no circular part |
| — | B-09, B-10 | B only | — |
| Ch. 4: §4.2 C4 stale | B-14 | both | — |
| Ch. 4: §4.2 C3 mapping | B-16 | both | — |
| Ch. 4: TODO header "planned"; §4.1 C6 "path patterns"; CON-0107 basis stale | — | A only | — |
| — | B-12, B-13, B-15 | B only | — |
| Ch. 5: §5.2 "Ch. 8, planned" | B-19 (first part) | both | — |
| Ch. 5: §5.5.4 Q1, §5.6.3 Q2, §5.7.3 Q1 unlinked; CON-0175 vs D; CON-0165 "≠"; CON-0187 trivial gate; CON-0131 basis; CON-0145/0175 one term; homonym table lacks §8.06 | — | A only | — |
| — | B-17, B-18, B-19 (§5.3 C2 part), B-20, B-21, B-22, B-23 | B only | — |
| Verdicts | — | — | **disagree** on CON-0265, CON-0079, CON-0264 (c), CON-0113's restated rule (§4) |

## 2. One-only checks (and the priority pairs)

| Finding | Result | Evidence |
|---|---|---|
| **A-02** | **holds** | I1 (V). The C1 store's "maximal entries" mix two epochs, so the guard demands x1 again. D then calls the result a fork. §9.13.2 ("guard and static test agree") and CON-0264's scope ("refused within one serialised kernel") are false as stated. I1b: C1→C2→C1 stays in D, so D also needs the clause |
| **A-03 = B-02 P2** | **holds** | I3 (V). "Never promoted" (§9.17 L167, L175; §9.17.1 item 3, L23; CON-0264 "i.e. every accepted entry") is false |
| **B-01** | **holds** | I5 (V). §9.13.2 says "Φ reads no carrier", so membership is not wired into CE*. A non-member negative act changes answers inside D. This is R2's countermodel M2 realised inside D: v18's D does not detect a K2-r failure caused by a merge. "Safe direction (withheld)" has no meaning in CE* terms. If it were implemented as withholding, §9.13.2 L58 (v5) already shows that withholding negative acts is the unsafe direction |
| **A-04** | **holds in part** | §9.17 L215 says "D has no carriers to retire". Since v18, D names carriers (designations, the store chain, ce_model L304–323, V). Retirement is still not admitted (§9.13.2 "Not admitted … retirement", V). So "no carriers" is stale and "no retirement act" stands. The K2-w row (L121) is not refuted, but v18 now presupposes that the kernel resolves (append L1097, V). It needs a note |
| **A-06 / B-11** | **both hold, as different defects** | §4.1 L18 already states that the condition cannot be met in the estate and routes it to DB-2 (V), so A's "not connected" is partly answered. A's real addition holds: §8.06 C3 (V) says independence can be refuted on the record but not established. So §4.1's proposed escape (a graded notion) still cannot establish independence on an incomplete record. B-11 holds: the Ded at L18 needs the unstated premise that "actor" covers operator and model family, and C5 (L79) labels the same fact Int |
| **B-17** | **holds** (severity **normal**, not high) | S0080 L4 (V) says the registration records "ARB DIRECTION and RECOMMENDATIONS". The ARB's quoted words are at L8. "Adopted. … authoritative portfolio state." at L10 is unquoted registrar voice, but §5.7 C7 presents both as "The ARB". F28 survives on L8 alone, so the consequence is the attribution only |
| **B-07** | **holds in part** | CON-0256 is INVALID (register, V). Its basis refutes the derived-from-source falsifier and never re-validates clause (i). §8.22 C7 (L109) makes clause (i) the deciding condition: an unvalidated clause of an INVALID concept is used as a home. The "exact" homonym holds: R4 L218 means "lists members only", while C7 means "is the intended result" (V). The deduction itself is definitional and needs no home |
| **B-08** | **holds in part** | It is not circular. R3 on "§8.24 C7" finds 0 claim and 0 concept citations (V), so nothing cites C7 back as support for K2-d. But §8.24.1's "one deduction is new" overclaims, because C7 is K2-w's contrapositive under K2-d |
| A-07 | holds | §8.23 L121 and L126 say "the fork stays open"; L130 says decided (V) |
| A-09 | holds | The register lists S2055 L745–757 and S0917 L1322/1327 as sources. §9.17 L314 says S2055 is "not cited as support", and L68–70 call S0917 "not on point" (V) |
| A-12 | holds, both parts | (i) Book search "R-CONFLICT" hits §8.17 C9 and §8.19 C26 (V), while the settlement L35 says "not homed". (ii) Settlement W-02 claims the record leaves D; I2 shows a lone bypass designation stays in D (V) |
| A-13 | holds | S0180 L32–41 cites the durability ADR (S0128), `…120633` (S0152) and `…204714` (S0172) (V, allowed list). §8.26 C5 instead says "one line with S0158 and S0159". S0130 is allowed, and §8.17 C9 already quotes it |
| B-04(b), B-04(c) | hold | §9.15 L10 still reads "version 10 (C22 settlement)" (V). §9.17.1 item 3 ("never by promoting") is contradicted by I3 |
| B-05 | holds | §9.13.2 L69 (V): one ill-formed entry makes Q1 refuse every answer on the record |
| B-09 | holds in part | §8.26 C1 L38 already labels the S0178 application "by S0179's criterion (W-08)". The quantifier holds: the title and C2 L45 ("not even reliable evidence") generalise from one mis-gloss |
| B-10 | holds | §8.22 C5, §8.24 C8, §8.25 C1 and §8.26 C6 each route to T0-03 unresolved (V via R3 hits) |
| B-12 | holds | S0027 L175 (V): "Checked against the canonical wording … the check was real". So the class existed before the count, and C3's "not fixed in advance" needs that qualification |
| B-13 | holds in part | S0044 L26–30 (V): the HYPOTHESIZED row is in the table, under the heading "only terms in actual use". Both readings fit |
| B-15 | holds in part | S0027 L113 names no three kinds (V). "Four, not three" is a recount, so the label should be Int, not Ded |
| B-18 | holds | §5.00 L48 states an invariant whose owner is the registrar; §5.6 C3 and C7 make it Hyp, with a composite object (V) |
| B-19 (§5.3 part), B-20, B-21, B-22 | hold in part | B-20: C3 labels it Int; only the plain-language line L72 is unlabelled. B-21: S0029 L62 (V) is addressed by the book, but the conclusion sits under a "Src" heading. B-22: C7 states fixed R; the authorization caveat is missing |
| B-23 | holds in part, **but its correction is wrong** | See N-1 in §6: the source's no-output lane did start |
| A Ch. 4 items (TODO header; §4.1 C6; CON-0107 basis) | hold | TODO L124 says "planned" (V); one monitor is generalised to "coverage is a set of path patterns"; the register basis says "not yet reviewed" while the reviewed column is filled (V) |
| A Ch. 5 items | CON-0175 vs D, CON-0165, CON-0187, CON-0131 basis, one term, homonym table: **hold**. The three "answered" open questions: **hold in part** | §9.13.2 records model choices (recorder field; targets outside the citation rule; retirement not admitted). These are links due, not answers |

## 3. The designation rule

| Question | Answer |
|---|---|
| **Rating of A-01 / B-02** | **Critical.** By F1, the guard's clause 2, the Ded "membership across the change = every accepted entry" (L175) and CON-0264's scope rest on telling accepted entries from bypass entries. By F3, it adds a kernel assumption and frames T9-10 (b). Dependants confirmed (V): §9.17 L167, L174, L175; §9.17.1 item 3; CON-0264; §8.22 C2 L57 ("enforcing operation now exists": I2 shows a resolver without the oracle follows C7); check 30's "bypass" PASS. B judged by F4 only and did not apply F1/F3. *By the letter of F1, A-02 is also critical-eligible*, because CON-0264's "refused within one serialised kernel" rests on it. Treat A-01, A-02 and A-03 as one critical block on the v18 completion |
| **Smallest premise** | It depends on which claims are kept. **(i) Append-time claims only** (accepted-only clause, no stale or forked designation in one kernel): a **kernel-held accepted set** (B) is enough, together with a guard clause refusing an append that cites an entry outside the set (A-03's fix). **(ii) Claims about D and readers** (L174 "reported as a fork"; §8.22 C2; merged multi-application records): a private set is invisible to D, so the minimum is **record-visible kernel attestation** of acceptance and of the store field (A = B's second option, "K5 binds entries to kernel acceptance"). That extends K5, which today checks only intactness for unsigned entries (§9.15 L78, V). R3 (a)'s scope must then name authenticated acceptance as a second decidable route (B-02's point). **Recommendation:** adopt attestation, because the book states (ii)-type claims. Otherwise rescope L174 and §8.22 C2 to append time and keep B's set |
| **Carrier reuse (A-02)** | **A rule, not an epoch:** "a designation may not name as its new carrier the root carrier or any store already named in the chain." It must be **both** a guard clause and a D clause (I1b shows D alone admits C1→C2→C1). It mirrors D's existing "one designation per store" clause. An epoch, meaning the store keyed by the opening designation, is needed only if returning to a former store is required. The book cites no such case |
| **T9-10 (a)** | Technical, by the decided precedent: the Principal chose prevention for single-use grants (owner check, K4/G4). Give each carrier one owning kernel; it alone may designate. **The only part needing the Principal, in plain words:** "Should each record store belong to one application, so that only that application can move the record to a new store? If yes, conflicting moves are prevented. If two applications must be able to move the same record, a conflicting move can only be found afterwards, and until someone repairs it the whole record gives no answers." |
| **T9-10 (b)** | Technical. With attestation: an unattested designation or store label takes the record out of D, reported as "not accepted by the kernel", not "fork". This is the existing fail-closed integrity rule (§9.13.2 L53). Unattested ordinary entries must not be silently dropped, by the v5 lesson. Either refuse them (fail-closed) or evaluate and mark them. Choose one and state it. A Principal question arises only if the fail-closed integrity rule itself is to be revisited |

## 4. Concept verdicts (where A and B differ)

| Concept | Verdict | Reason |
|---|---|---|
| CON-0265 | **VALIDATED** within its stated scope: exact list, not survivor-derived; no write between check and act; a list | I checked C1–C5 against the gate definitions. The anchor model re-run is identical (V). Being near-definitional is not a defect (B). The register clause "(D has no carriers)" must be corrected (A-04) |
| CON-0079 | **VALIDATED** for "the answers are logically independent; activation needs the first two", with Q2 read as demand per S0029 L16's own gloss (V) | B's point is recorded as a limit: logical independence does not show the source's decision-relevant "ORTHOGONAL". The register basis "not yet reviewed" is to be updated |
| CON-0113, restated rule (§4.3 C1) | **PROVISIONAL** (Int) | S0044 L26–30 fits both the admission and the promotion reading (V) |
| CON-0113 as stated | **INVALID** stands, on the used ≠ performed ground (L28, V), independently of B-13 | — |
| CON-0264 | core **PROVISIONAL**: (a) proof valid; (b) valid under its premises. Two v18 completion clauses **INVALID as stated**: "membership across the change = every accepted entry" (I3) and "fork refused within one serialised kernel" (I1). "Classed foreign (withheld)" is undefined in CE* terms (I5) | — |
| CON-0261 | PROVISIONAL (A and B agree) | — |
| CON-0131, CON-0069 | VALIDATED, narrowed: CON-0131 as the lemma; CON-0069 as the stream separation | — |

## 5. Corrections: cross-chapter consequences (R3 on the copy, V; `…/scratchpad/cons_i_r3.txt`)

| Correction | R3 citing lines (claim / concept) | Sections and rows touched |
|---|---|---|
| A-01/A-02/A-03/B-01/B-03 (designation completion) | CON-0264: 20 concept lines | §9.17 L23, L64, L166–177; §9.13.2 L48; §8.19 L236; §8.23 L21, L63; §8.24 L26, L102–104; T9-09, T9-10; register CON-0264; THEORY-MAP L367; check 30 |
| A-01 consequence for §8.22 C2 | 3 claim lines | §8.23 L69; register CON-0261 |
| A-04 | CON-0265: 21 concept lines | §9.17 L215; register CON-0265 meaning; §8.18 L212; §8.21 L182; §8.22 L110; §8.26 L21, L54; §9.15 K1 |
| A-08/B-04 (K2 attribution) | CON-0261: 22 concept lines | §9.15 L75; register CON-0261; §8.24 L84 |
| B-07 | §8.22 C7: 13 claim + 10 concept lines; CON-0256: 17 concept lines | §8.22 L23, L33, L109, L147; §8.23 C12 and L125/L130; T8-04; register CON-0257; THEORY-MAP L190–193; §8.21 L182 |
| A-05/B-06 | §8.26 C6: 2 section-only lines | THEORY-MAP L195; §8.26 index row L23; T0-03 (Ch. 0 basis, not opened) |
| A-13 | §8.26 C5: THEORY-MAP L195 | — |
| A-06/B-11 | §4.1 C2: 2 claim lines; §4.1 C3: 6 claim lines | T4-03, T4-04, §5.00 homonym row, §6.3 L29, DB-2 |
| B-17 | §5.7 C7: 1 claim line | THEORY-MAP L145 (F28) |
| B-13 / CON-0113 | 4 concept lines | §4.3 L13, L51; register; THEORY-MAP L201 |
| CON-0175 / B-20 | 4 concept lines | register CON-0145 and CON-0175 (shared term); THEORY-MAP L245 |
| CON-0165 | 5 concept lines | §5.5 L13, L20; register CON-0109 and 0165; map L198 |
| CON-0187 | 4 concept lines | §5.3 L23; register CON-0078 and 0187; map L96 |

Limit: "§9.17 R3", "§9.17 R4" and "§9.15 K2" return 0 because the tool matches only C-numbered claims. I traced those by grep (V).

**Conflicts between corrections:**
1. A-01 (unattested entries fail closed) against B's T9-10 (b) / B-01 (evaluate and mark foreign entries). Choose one. Dropping is excluded by v5.
2. A-03's fix ("refuse citing a non-accepted entry") needs "accepted by *some* kernel" in merged multi-application records (F22, G4). So it works only with attestation, not with B's private set.
3. B-23's "add 'ran'" conflicts with the source (N-1). Key the condition on the commissioned deliverable instead.
4. A-04 against B's verdict text "D has no carriers". A's correction holds.
5. A-05 "drop" against B-06 "relabel". REVIEWER-NOTE item 7 forbids relying on summaries' content about non-allowed files, so drop.
6. A-02 rule against epoch: choose the rule.
7. B-11's alternative ("restrict actor to a session") would reopen T4-03. After A-06's §8.06 link, independence is still only refutable, so state the premise rather than narrow it.

## 6. Rotated chapters

| Chapter | A only | B only | Both | Union |
|---|---|---|---|---|
| Ch. 4 | 3 | 3 | 3 | 9 |
| Ch. 5 | 9 | 6 | 1 | 16 |

Overlap estimate, **indicative only**: the reviewers are not independent, A counts three unlinked questions separately, and their focuses differ.

| Scope | Union | Lincoln–Petersen | Chapman |
|---|---|---|---|
| Ch. 4 | 9 | 12 | ≈11 |
| Ch. 5 | 9 + 6 + 1 = 16 | 70 | ≈43 |
| Combined | 25 | 52 | ≈47 |

The Ch. 5 estimate rests on one match and is unstable.

**Defects both missed, found while checking (counted separately):**

| ID | Chapter | Sev. | Defect |
|---|---|---|---|
| N-1 | Ch. 5 | normal, DISTORTED | §5.7 C1 (05.07 L26, L29) calls the S0079 L57 lane one that "never ran". S0079 L55 says "HANDED_OFF, started", ruled ineligible, no output (V). The source's distinction is "no eligible commissioned deliverable", not "never ran". This also invalidates B-23's fix |
| N-2 | Ch. 4 | normal | CON-0113's register basis says "not yet reviewed" while the reviewed column says C8 and CONS (V) |
| N-3 | Ch. 5 | low | §5.4 L188 lists the INVALID CON-0053 as a reconciliation target, not as a home (V, R3-OUTPUT) |

## 7. Coverage disagreements

| Item | A | B | Right, and why |
|---|---|---|---|
| S0178 2 (three principles) | JE (access) | M | **A.** L32 and L35 cite only `…204018`, not on the allowed list and = S0161 by §8.26 L7 (V) |
| S0178 1 (identity) | JE (access) | M | **B.** L38–39 cite S0149 and S0162 (V) |
| S0178 11 | JE | C | **B** for L112, which cites S0149. JE only for L159's attribution to `…204018` |
| S0178 5, 7, 14 | JE | C / M | **Split.** The S0149/S0162 parts are covered or missing on their merits; the `…204018` parts are JE (V, citation map) |
| S0176 10 (Hexagonal ESTABLISHED) | JE (S0155) | C | **A.** The inventory names `…123902` |
| S0176 6 | JE (S0155) | C | **B.** L62–70 cite only `…224159` = S0167 (V) |
| S0181 1, 11 | JE | M | **A.** L29 cites `…123902` (V) |
| S0181 2, 7 | JE | M | Split. L66 is S0149; L69 is `…123902` |
| S0177 6 (evidence lifecycle) | M | C | **Mostly A.** §8.23 C6 covers only "qualified before governance". Book search finds 0 hits for "Governance-Accepted" and for "candidates, not knowledge" |
| S0158 10 (command loop) | M | C (§8.24 C8) | **A.** §8.24 C8 is about access through the kernel; "governed command" has 0 hits |

B's statement that no inventory item rests on the six excluded files does not hold (S0176 10, S0178 2, S0181 1).

**Ranked MISSING items to bring into the book:**
1. R-CONFLICT's ownership by Evidence (S0160 7, S0177 3, S0180 6).
2. The evidence lifecycle and "AI creates candidates, never knowledge" (S0177 6, S0160 4/6).
3. ADR-KOS-002 is all constraints, so the trivial model satisfies it (S0160 9).
4. The governed command loop compared with the kernel's append steps (S0158 10, S0180 4).
5. S0172 2: rulings authorize nothing.
6. The identity framings for T0-03 (S0178 1, S0158 12).
7. Domain events vs integration events (S0162 9, S0180 5).

## 8. Process notes

- **Files on REVIEWER-NOTE item 4:** neither file list reaches one, and neither reaches the six excluded files or Ch. 0–2.
- **The C33 settlement:** both reviewers opened `C33-…/SETTLEMENT.md`. SCOPE does not name it by path (grep, V). The commission lists it for me; whether the reviewers' brief allowed it I cannot verify, because BRIEF.md is not on my list.
- **B's disclosed deviations:** `frozen-table.txt` printed in full; `ls` of `CP/`, `BK/`, `REV/`, `BK/tmp/`, `BK/tmp/tools/` (names only); B did not open R3-OUTPUT. B ran R3 for only 3 targets against 24 findings, whereas the instructions require R3 for every correction. A opened the head of `frozen-table.txt`, BRIEF.md, and grepped `02-FILES.jsonl`.
- **Scratch files:** both reviewers wrote into scratchpad `…/16a0ac97-…/scratchpad/` (`cons_a_*`, `cons_b_*`). That is the scratchpad assigned to this session. It also holds unprefixed files (`a`, `b`, `c31`, `c33`, `probe`, `v16`, `v17`, `v17.diff`, `window-times.txt`). I opened none of them.
- **A write into the frozen copy:** `BOOK-UNDER-REVIEW/tmp/tools/__pycache__/ce_model.cpython-313.pyc` is dated 2026-10-27 23:11 (+0100). That is after the copy (23:06) and before the reports. A says ce_model was "imported by my probe", and B ran it too; the writer is not attributable. No book text changed, and `git status` on the folder is clean. My runs used `-B`, and the mtime is unchanged.

## 9. Summary

1. The v18 designation completion has one **critical** block: A-01, A-02 and A-03 together, all confirmed by my own probes.
2. The guard cannot tell accepted entries from bypass entries without an oracle (critical by F1 and F3).
3. Guarded carrier reuse gives a record outside D.
4. A bypass entry is promoted when an accepted entry cites it.
5. B-01 holds: foreign classification changes no CE* value, so a merge can realise M2 inside D.
6. Smallest premise: record-visible kernel attestation (an extension of K5) for the claims the book makes. A kernel-held set is enough only if those claims are rescoped to append time.
7. Carrier reuse needs a rule, "a store is carrier at most once", in both the guard and D. It does not need an epoch.
8. T9-10: both parts are technical. The Principal is asked one business question: may two applications move the same record?
9. Verdicts: CON-0265 and CON-0079 VALIDATED (scoped). CON-0264's core PROVISIONAL, with two completion clauses INVALID as stated. CON-0113's restated rule PROVISIONAL.
10. Rotated chapters: union Ch. 4 = 9, Ch. 5 = 16, plus 3 found by me. N-1 (a distorted reading of S0079) also defeats B-23's fix.
11. Coverage: the access judgements must be made per citation. A over-excludes (S0178 1/11, S0176 6); B under-excludes (S0178 2, S0176 10, S0181 1).
12. Process: a bytecode write sits inside the frozen copy; B's R3 coverage is incomplete.

**Limits:**
- I read the C32 inventory (S0176 part only) and the C33 inventory in full. For C29–C31 I took only word counts, so those coverage rulings rest on A's and B's renderings of the items.
- I read §9.13 only at L40–60, and Ch. 5 §5.1, §5.2 and §5.4 only at the cited claims.
- The probes are small models, not proofs.

## Files opened (absolute prefixes)

Prefixes:
- W = `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018`
- C = `W/06-theory/manuscript/review/CONSOLIDATION-S0151-S0181`
- BK = `C/BOOK-UNDER-REVIEW`

Instructions and inputs:
- `W/03-methodology/REVIEWER-INSTRUCTIONS.md`
- `C/REVIEWER-NOTE.md`, `C/SCOPE.md`, `C/INTEGRATION-COMMISSION.md`
- `C/REPORT-A.md`
- `C/REPORT-B.md` (also through my own persisted tool-output file)
- `C/SHA256-REPORT-A.txt`, `C/SHA256-REPORT-B.txt`
- `C/R3-OUTPUT.txt` (grep)

Inventories and settlement:
- `W/06-theory/manuscript/review/C33-S0177-S0181/STEP1-INVENTORY.md` (whole)
- `…/C32-S0171-S0176/STEP1-INVENTORY.md` (S0176 part)
- `…/C29`, `C30`, `C31` inventories (`wc` only)
- `…/C33-S0177-S0181/SETTLEMENT.md` (`wc` and grep)

The copy:
- `BK/06-theory/manuscript/chapter_09/`: 09.17 (whole), 09.13 (L40–60 and grep), 09.15 (grep), TODO (grep)
- `chapter_04/`: all six files
- `chapter_05/`: 05.00 and 05.07 (whole); 05.03, 05.04, 05.05, 05.06 (sections and grep)
- `chapter_08/`: 08.22 and 08.26 (whole); 08.24 (L1–40, L75–95); 08.23 (L121–131, C6); 08.06 (C3); 08.17 (C9); TODO (grep)
- `chapter_08/` 08.15, 08.19, 08.20 and `chapter_09/` 09.16: lines matched by a python regex
- `BK/06-theory/CONCEPT-REGISTER.tsv` (python parse)
- `BK/06-theory/THEORY-MAP.md` (grep, L188–192)
- `BK/06-theory/manuscript/BOOK-OUTLINE.md` (grep)
- `BK/tmp/tools/ce_model.py` (L100–330, L985–1105, L1128–1140, L1340–1350, L1960–1980, L2071–2113, grep; imported and run with `-B`)
- `BK/tmp/tools/anchor_model.py` (run); `BK/tmp/tools/book_search.py` (run)
- `BK/tmp/tools/ce_model-run-20261027-v18.txt`, `BK/tmp/tools/anchor_model-run-20261024-v2.txt` (diff)
- `stat` on the copy's `__pycache__` file

Tools and indexes:
- `W/tmp/tools/inbound_citations.py` (L1–30 read; run)
- `W/tmp/tools/register_home_check.py`, `W/tmp/tools/undefined_terms.py`, `W/tmp/tools/corpus_quote_check.py` (run)
- `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv` (head, grep)

Corpus (each checked on the allowed list first):

| Source | Lines read |
|---|---|
| S0027 | L111–121, L170–176 |
| S0044 | L20–31, L52–55 |
| S0080 | L1–12, L44–48 |
| S0079 | L55–58, L60–63, L72–75 |
| S0029 | L14–17, L59–62 |
| S0040 | L8, L42–52 |
| S0077 | grep; L22, L24–27, L40–42 |
| S0178 | L10–27, L159–162; citation extraction L30–160 |
| S0179 | L41–44 |
| S0180 | L30–42 |
| S0181 | L10–22; citation extraction L24–70 |
| S0176 | citation extraction L59–70 |

Scratch (mine, all in `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/`):
- `cons_i_probe.py`, `cons_i_probe_out.txt`
- `cons_i_ce_model_run.txt`, `cons_i_anchor_run.txt`
- `cons_i_r3.txt`

Not opened: frozen-table.txt; BRIEF.md; A's and B's scratch files; S0155, S0156, S0157, S0161, S0170, S0174; every item-4 file; Ch. 0–2; any `.env*`, sealed, hold-out or chronological-read file. No file was refused.

**Disclosed deviations:**
- a name listing of the scratchpad;
- `find` over the copy (names only);
- `ls docs/knowledgeos/brainstorming | grep -c 123902` (a count only; the file was not opened).

## Searches run

- **Book search (copy):** "--help" (a mistaken invocation), "independence graded", "Governance-Accepted", "Qualified", "candidates, not knowledge", "governed command", "integration event", "SC-2", "UNKNOWN ≠ DOES NOT APPLY", "trivial model", "R-CONFLICT".
- **Mechanical checks on the copy:**
  - R1 `register_home_check.py --root BK`: 241 homes, 1 flag, CON-0133 in Ch. 6;
  - R2 `undefined_terms.py --root BK`: tiers A 63 / B 59 / C 17 / D 529;
  - quote check: Ch. 4: 17 found, 0 not found; Ch. 5 §5.2–5.7: 92/0; §8.22–8.26: 36/0; §9.17: 7/0;
  - R3 `inbound_citations.py --root BK` on 25 targets: CON-0264, "§9.17 R3", "§9.17 R4", CON-0265, CON-0261, "§8.22 C2", "§8.22 C7", "§8.26 C6", "§8.26 C5", "§4.1 C2", CON-0069, CON-0113, "§4.3 C1", "§5.7 C7", "§5.7 C3", CON-0175, CON-0165, CON-0256, "§8.24 C7", "§9.15 K2", CON-0187, CON-0168, "§4.1 C3", "§5.7 C1", CON-0174.
- **Greps on the copy:** ce_model functions and store/bypass; §9.13 designation, foreign, safe direction; §9.15 K2 and status; THEORY-MAP stale lines; BOOK-OUTLINE S0150/S0175; T9-08–T9-10, T8-04; "D has no carriers|never promot|not promoted|safe direction" across Ch. 8–9; CON-0079 in §5.3; §8.17 C9; §8.06 C3; §8.23 C6.
- **Greps on the allowed list:** S-IDs S0027–S0181 as needed; 204018, 204431, 205757, 220806, 123902, 224159, 225329, 142748, 231930, 204714, 120633, STATE-DURABILITY, 121929, 145153; `knowledge_tranfer` (count 0). Greps on the C33 settlement (R-CONFLICT, fork, bypass) and on SCOPE (settlement).
- **Python:** the register parse and counts (265; VALIDATED 45 / PROVISIONAL 124 / INVALID 96); the probes I1–I6; the capture–recapture arithmetic; full v18 and anchor-model runs with diffs (identical).
- `git status --short` on the consolidation folder (clean).

*Window reviewer W (Opus), steps 2–3 report, returned as hand-back text, verbatim (saved by the Professor at 2026-10-20T21:59:43Z). sha256 of the hand-back text at receipt: `4850f659eeaf321d4c230555cc17a3552d86efc9cfa19b872f69d299375e6f6e` (UTF-8, the text below this line exactly).*

C19 WINDOW REVIEW, STEPS 2–3 (reviewer W, transition review of §8.12, CON-0195 to CON-0199)

I re-read my saved inventory first. Its sha256, computed over the text below the Professor's header line with no trailing newline, is dfc75e5a…edfa and matches. Independence here is process independence, not personal independence.

## 1. Assessment
- **Reliability:** §8.12 quotes its sources faithfully: 49 of 49 bracketed quotes are present at the cited lines (c19w_quotes.py). Its scoping words ("one work item", "one line", Hyp) are mostly right. The Src layer is reliable.
- **Largest risk:** the book's interpretation (Int) layer. Three Int claims go beyond the sources or contradict them:
  - C3 and C9 "confirm" a compaction mechanism that S0105 denies.
  - C6's three-way split contradicts the sources' own definitions.
  - C5's mapping onto §9.15's K-list is partial, and it merges two senses of "ratification" and two of "authority".
- **Purpose measure:** no new concept is classed kernel or application. CON-0195 and CON-0198 are estate assurance practice (application); only CON-0196 and CON-0197 touch the kernel, through I15 and the K-list.

## 2. Findings (ranked)

| # | Sev | Type | Claim | Source line | What is wrong or missing | Consequence | Correction |
|---|---|---|---|---|---|---|---|
| 1 | high | defect | C3 "confirms §8.11 C1 as restated (CON-0180)"; C9 calls the erratum's "compacted continuation" "the supported side" | S0105 L91–93; S0101 L23 | S0105 finds compaction **NO** and continuation/fork NOT ATTESTED. Context was inherited through the opening *user turn*. S0101 L23 also reports no compaction boundary. S0105 §6 supports the erratum's two conclusions, not its mechanism. | CON-0180's "context-compaction continuation" and §8.11 C2's case premise are contradicted, and THEORY-MAP L215 is stale. The inheritance channel is plausibly the operator (§8.6 factor "human operator"). | Restate CON-0180: the channel is undetermined (S0099 asserts compaction, S0105 denies it). C3 confirms only the attribution clause. Fix C9 and map L215. |
| 2 | high | defect | C1 "answers §8.11 C2 for this work item" | S0103 L5, L20–38; S0101 L224 | The criteria bind one assignment (S5), **going forward only**. §8.11's case (the S1 verification ca6039a8) remains a Governance/PO-ARB act on standing, still undecided. S0103 is Governance's registration of the act, not the act itself. | The scope is stated too broadly, and §8.11.3's pointer says "answered". | Scope C1 to "for S5, going forward"; §8.11 C2's own case stays open. |
| 3 | high | defect | C6 Int: not established = "a definite negative finding"; NOT ATTESTED = undetermined, a separate state | S0102 L522, L528, L840; S0103 L35 | NOT_ESTABLISHED is defined as "cannot currently establish it", which is about what can be established. NOT ATTESTED is "lineage cannot be established". Same definition, so they are not two states. NOT_ESTABLISHED also has two senses: unachievable (organisational independence, L528) and achievable but not done (external attestation, L840). There is no "refuted" state for a property shown false, as in NOT INDEPENDENT. PARTIAL and PROCEDURAL ONLY (L514–516) fall outside the four states. | CON-0198's Int is defeated. The step-8 rejection of the "merge" rival rests on the false premise. | Use two levels: whether the property holds (T/F/U), and whether it can be established (applies?, establishable?, done?). Add "refuted". Split NOT_ESTABLISHED's two senses. |
| 4 | high | defect | C5 Int: "That is what §9.15's kernel assumptions list"; "trusted for its execution conditions, never as an authority"; §1.14.5 read as an upstream ratification | S0102 L814, L824; §9.15 K1–K7; §1.14.5-1 | Of S0102's conditions, inputs map to K3 and version to K7. "When it runs" and "bypass" map to no K. K1 concerns writes to the record, not alteration of the check. "Never as an authority" contradicts S0102's "authority is about effect". Ratification in S0102 is of an **outcome**; in §1.14.5 it is of the **rule**. S0102 L824 says authoritative results are not thereby ratified. | Homonyms in "authority" and "ratification"; CON-0197's Int overreaches. | Map each condition separately and name the two gaps. Qualify the terms as "authority (effect)", "ratification (of an outcome)" and "ratification (of a rule)". |
| 5 | high | defect | C3 "a contradiction in the corpus"; F25 "answered yes, with the risk accepted"; map F25 "the two sources disagree" | S0102 L779–780; S0103 L40; S0101/S0102 erratum L221–223 | The acceptance is reported by the constrained party itself, which is ASSERTED in C2's own vocabulary and is the failure mode the erratum names. The ruling's ordered correction of seq 21 never reached the record (S0103 L40 still names 5e1dd9ee). Rival reading: an acceptance never recorded, the F-7 pattern again. | The contradiction is not established, and F25's "yes" rests on one ASSERTED line. | Classify L780 as ASSERTED. Give two readings instead of a contradiction. Reword the F25 row. |
| 6 | normal | defect | C1 Int: "as S0103 L36 does for NOT INDEPENDENT only by implication" | S0103 L36, L187 | L36 is explicit for NOT INDEPENDENT. "If not INDEPENDENT: STOP" can be read as covering NOT ATTESTED. | Open question 1 is partly answered by the source. | Fix the sentence, and note that the source text can be read both ways. |
| 7 | normal | omission | the settling source's provenance | S0105 L4, L137 | The reconciler is "Governance". It names no process, and its lineage relative to the disputants is not tested. | The evidence that settles C3 is itself of an unattested producer. | State this in C3's assumptions. |
| 8 | normal | defect | C2/C4: a "fifth class" between ASSERTED and AUTHORED | S0104 L14–22; S0101 L18–28; S0105 L143 | The two scales grade different objects: a process's own identifier versus an artifact's authorship. S0101 read its identifier correctly and still attributed authorship falsely. Binding identity by latest modification time is fragile when windows run concurrently. | The proposed class misplaces the evidence. | Keep the identity-evidence scale separate from the authorship scale. |
| 9 | normal | defect | C7 / CON-0199 | S0102 L475–478, L535 | Trivial-solution test: INV-ATTR-2 is a prohibition that the empty mechanism satisfies, so it presupposes the concept, not the capability. Missing: the C-19 application (composition "occurs" plus A-7, showing how loose the condition is), and the L476 instance of necessity ≠ existence (support for CON-0178). The falsifier rests on authority, but the condition is empirical. | Sufficiency is claimed by the source, not shown. | Say "treated as sufficient by the source". Add the instance-based falsifier: an accidental, one-off performance. |
| 10 | normal | defect | C9: "every subagent … with the parent's context" | S0101 L48 | Counterexample: a fresh subagent inherits only its prompt (information lineage), as this reviewer does. S0101 says "unattestable", not "not independent". | The quantifier is wrong. | Restrict the claim to forked subagents. |
| 11 | normal | omission | AMD2 homonym; gaps in S0104's criteria | S0105 L51–58; S0104 L36–41 | Criterion 3 passes only under the grant sense of AMD2. Criterion 1's reference set leaves out the process itself. "IS the barred producer" is filed under lineage (criterion 6). The criteria do not partition the cases. | A gap in the vocabulary of C1's own criteria. | Add to C1 or C9. |
| 12 | normal | omission | I-K1 receipt | S0102 L554, L570–571 | ContextAcknowledged is recorded by the session itself, so "possession authority" is self-asserted, the §8.3/OQ-L failure. | It qualifies C9's "receipt is that field". | Add the qualification. |
| 13 | normal | defect | §8.10 C7 Int "external attestation absent by construction" | S0102 L840 | S0102 says it applies and could in principle be supplied. | An unreconciled conflict with C6. | Reconcile. |

## 3. Concept verdicts

| ID | Verdict | Reason · scope |
|---|---|---|
| CON-0195 | PROVISIONAL | The Src is exact. The scope must be one assignment, going forward (finding 2). The fail-closed Int is sound. |
| CON-0196 | PROVISIONAL | The classes are faithful. "Recorded attribution is ASSERTED" agrees with §9.15 R3. The assumptions omit that the transcript store is writable while artifact isolation is NOT_ESTABLISHED (S0102 L513), and that sessions ran concurrently. |
| CON-0197 | INVALID as stated | The definitions and the three axes are valid as vocabulary (one source, PROPOSED in the estate). The register's Int ("§9.15's K-list is their execution conditions") is defeated by finding 4. |
| CON-0198 | INVALID as stated | The four-state Src is provisional and its vocabulary incomplete. The register's three-way Int is defeated by S0102 L522 and S0103 L35 (finding 3). |
| CON-0199 | PROVISIONAL | One source. Ground ① fails the trivial test. Sufficiency is claimed, not shown. |

## 4. Coverage against my inventory
- **Covered:** S0105 items 1–4, 6, 7, 9–11; S0104 items 5–8; S0103 items 2–3, 5–6; S0102 items 1, 3–6, 11–14; S0101 items 2 and 4 (via §8.11).
- **Missing:** compaction NO (finding 1); the faulty inference at S0101 L23 (absence of compaction taken as absence of inheritance); the reconciler's process; the AMD2 homonym; S0104's criteria gaps; NOT_ESTABLISHED's two senses; PARTIAL and PROCEDURAL ONLY; the receipt as self-assertion; C-19 and L476; the fragility of identity bound by modification time.
- **Distorted:** C3, C5, C6, C9 (erratum), as in the findings table.
- **Justifiably excluded:** S0103's Inv C, handoff ≠ start, the G-3 conjunction and the two orders (sequence vs real time). It was read topic-full at turn 6; I did not check where that went in the book. Also the control loop and four clocks, C-14's sub-concerns and OQ-J, which duplicate S0096 in §8.10.
- **New fork:** the inheritance channel. Does inherited working context through the operator (a pasted turn) bar a process, as criterion 7 implies, even without structural lineage? This bears on F32 and §8.6's operator factor.
- **Source defects (listed separately):**
  - S0101 L199 says "2 of 6", but 7 terms are mandated.
  - S0101 §0 L18 is false; the book covers this.
  - The erratum's "compacted" (S0101 L222, S0102 L920) contradicts S0105 L91.

## 5. Next steps (in dependency order)
1. Reconcile S0099 and the erratum against S0105 L91 and S0101 L23. Restate CON-0180, §8.11 C2's premise, §8.12 C3/C9 and map L215. Done when both sources are cited and the channel is marked undetermined.
2. Rescope C1, CON-0195 and the §8.11.3 pointer (finding 2).
3. Rewrite C6's Int on two levels, split NOT_ESTABLISHED's senses, and consider a "refuted" state. Then re-tag CON-0198.
4. Restate C5's Int per condition and qualify the homonyms. Then re-tag CON-0197.
5. Reclassify S0102 L780 as ASSERTED and reword C3 and F25.
6. Apply the trivial-solution test to C7 and add C-19 and L476.
7. Smaller fixes: findings 6–8 and 10–13.

No question for the Principal is needed.

## 6. Limits
- §9.13 v8 and §9.16 R3a were not read in full; I read §9.15's G and K tables.
- I read §8.10 C3, C4 and C7 in full, and §8.6 and §8.9 only through the register.
- S0164 and the transcripts (outside the corpus) were not checked.
- The quote check covered only bracketed quotes; "in meaning" paraphrases were judged by reading.
- No REPORT-* file was read.

## Appendix

**Falsification attempts (step-8 file):**

| Concept | Judgement |
|---|---|
| CON-0195 | (a), (c), (d) adequate. Missed the scope issue (finding 2) and the doubt about the case's premise (finding 1). |
| CON-0196 | (a), (c), (d) adequate; (c) is strong. (b) inadequate: misses the store's writability and concurrency. |
| CON-0197 | (a), (c) adequate. None tests the Int (finding 4). |
| CON-0198 | (a) adequate. (c) inadequate: its rejection premise is false (finding 3). |
| CON-0199 | (a), (c) adequate, but not folded into the text. (d) weak, since it rests on authority. |
| C3 | (a) adequate. (c), the operator as the channel, is strong and belongs in the text. |
| C4, C8 | Adequate. |

The strongest rival, found by me: the inheritance came through the operator's pasted user turn (S0105 L96). That joins C3's rival (c) with §8.6's operator factor, and it explains S0101's false self-attribution without any continuation.

**R4 proof-step table, C3 ("the refusal true, the correction false"). Judged adequate under A1–A3:**

| Step | Statement | Justification |
|---|---|---|
| 1 | The refusal file was created by `cat >` in 2da45a86 at 08:48:51Z | S0105 L30. A1: transcripts intact and keyed correctly. |
| 2 | 4858c37c's first record is at 09:02:18Z | S0105 L100. A2: the transcript is complete from its start. |
| 3 | A process does not write before it exists | logic |
| 4 | So 4858c37c did not create it, and CORRECTION 1 is false | 1–3 |
| 5 | Creator = declarer (2da45a86), so the declaration is true | 1. A3: the create at step 1 is the file's origin. Sole-writer status is shown only for the analysis file (L37). |

"One process on both sides" follows from A1 and the uniqueness of the writes at L33–34. Calling it a **defect** needs a further normative premise: that finder ≠ fixer applies across assignments, which is F25, still open.

**R5 (severity of the missing premise):** A1 rests on store integrity in an estate where the same window records artifact isolation as NOT_ESTABLISHED (S0102 L513). Severity normal; it bounds "settles".

**Other point:** C8 applies the kernel record model (D) to a prose artifact, but S0102 L868 separates the artifact from the workflow record. The open question "annotate without negating" mixes the two objects.

**Computation:**
- c19w_quotes.py: 49 quotes, 49 found at their cited lines.
- c19w_deltas.py (step 1): 8 of 8 time gaps match, and the gap is 13.45 minutes.

Scripts are in /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/.

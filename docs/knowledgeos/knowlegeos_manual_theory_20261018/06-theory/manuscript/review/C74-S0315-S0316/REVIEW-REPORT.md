C74 REVIEW REPORT

Window C74 (S0315–S0316), step 2, by window reviewer W in a transition review. I re-read my saved step-1 inventory first. Independence here means a separate process from the Professor's, not a different person.

## 1. Summary
- §8.67 quotes its sources accurately and C2 is sound. The largest risk is in C1, the lead claim.
- **The plain language (L30) and the index row (08.00 L83) say independence is refuted "on S0315's own reading". It is not.** S0315's own map has two different ids. A refutation needs S0315's producer row and S0316's verifier row together. Those two records contradict each other, so the truthfulness premise of CON-0127 (R ⊆ F) fails. This case is an instance of §8.06 C3's failing second premise, not a refutation.
- C1 misses three homes the book already has:
  - CON-0195 and CON-0196 (§8.12): lineage through a resume, and a scale for how a process knows its own id;
  - §8.10 C5 (CON-0179): authorship shown by provenance, and the warning that a transcript search for authorship contaminates itself;
  - S0314 L20, which bears against S0316's map.
- C3–C5 hold in substance but use several homes by analogy without saying so: E6, T7, K5, and "F within the kernel".
- One source result the verdict rests on, V-5, is missing.
- No new concept or fork. Nothing reserved for the Principal is presented as decided, except that K5 is applied as if it were in force (W-08).

## 2. Findings (ranked)

| W-nn | Sev | Claim | Evidence | Correction | R3 lines |
|---|---|---|---|---|---|
| W-01 | high | C1, §8.67.1 PL1 (L30) "On S0315's own reading it is refuted"; index row "under S0315's own map independence is refuted" | S0315 L16–18: its own map is d1612e03 ≠ 8a525719 (not refuted). S0316 L18–20: its map is 8a525719 ≠ a8ce5a39 (not refuted). Only the cross-reading (S0315's producer row with S0316's verifier row) gives one label. CON-0127 needs R ⊆ F, and §8.06 C3 says refutation from the record "is only as reliable as the record's truthfulness". Two contradictory records are a visible failure of that premise. | Restate: "Neither record alone refutes independence, and neither establishes it. Only the cross-reading gives one label, and it combines two contradictory records, which violates CON-0127's truthfulness premise. The instance goes under §8.06 C3's failing second premise." Apply the same change to L30 and the index row. | 08.00 L83; 08.67 L30, L56. R3 for §8.67 C1: 0 inbound citations. |
| W-02 | high (omission) | C1, readings (a)–(c), weighed without the available evidence | (i) **S0314 L20**, written by the producing session (S0314 L5), says a8ce5a39 sits in free text "while the running processes were 8a525719 / c11a5a12". That places a8ce5a39 outside the running processes and bears against S0316's producer = a8ce5a39. §8.67 L52 cites the line only as "8a525719 … with no role". (ii) S0315 L130: a8ce5a39 is referenced in two architecture lanes of a different work item. (iii) S0315 L67, L113 and L270 (V-7) suggest that labels are hex runs of the session id. On that reading d1612e03 is a fourth process and reading (c) loses support, so "the estate shows no mapping" (L49) is too strong. (iv) S0316 L78 then names 8a525719 as the process that should commit the artifact, a practical consequence of the dispute. | Weigh the readings. (a) has the most support and remains undecided. Name what would decide it: the provenance of S0315's writing act (§8.10 C5, CON-0179). | 08.67 L49–55. No inbound citations. |
| W-03 | high (omission) | C1 "Labels drift on resume … S0316 settles its own identity by reading its transcript … (§8.50 C3's pattern)" | The scratchpad that held a8ce5a39 after a resume (S0316 L22) is evidence of **lineage through a resume**: CON-0195 says "a different session id alone is insufficient". Under S0316's own map the recorder may then share lineage with the producer, which is the concentration S0316 says it avoided. S0316's method (transcript size and recency, keyword counts in its own transcript) is the self-identity scale of CON-0196, and §8.10 C5 warns that such a search "is self-contaminating" (S0092 L74). §8.50 C3 (a producer registering its own adoption) does not fit: S0316 is the recorder. | Home the point in §8.12 (CON-0195, CON-0196) and §8.10 C5. State that S0316's conclusion "NOT the AST-017 producer" is not established, given the possible resume lineage. | 08.67 L57. |
| W-04 | normal | C1 home "INV-ATTR-1/2 (§1.14.2)" (L16, L57) | INV-ATTR does not occur in §1.14 (grep). Its homes are §9.4 L21, §8.06 C3 and §8.10–§8.11. | Re-home it. | 08.67 L16, L57 |
| W-05 | normal | C3 "§9.10.2 requires an E6 answer to carry its reason"; "a U-type answer … fails the reason clause" | E6 (§9.10 L19) covers a U given where ref_q gives T or F. A label that matches two lanes makes AMBIGUOUS the reference answer itself, not an E6 case. The resolver's question is also not among Q1–Q5. | Say "by analogy". The exact point: if the candidate set is part of the answer, the answer is not equal to ref_q's answer. | 08.67 L18, L76 |
| W-06 | normal | C3 "a degenerate case of the kind §9.19 T7 warns about (a test whose input cannot tell the cases apart)" | T7 (§9.19 L264) concerns trivial systems (∅, refuse-all, noop), not tests whose fixtures cannot tell cases apart. The fitting home is §8.06 C2's trivial case: where both behaviours give the same output on the input, nothing is reported. | Re-home it. | 08.67 L76 |
| W-07 | normal | C4: the R3 for §8.66 C5 (premises (2) and (3), Src) | Correct, but the scope is missing. S0315 L178 says "inside one harness". The asset reads `CLAUDE_CODE_SESSION_ID` (L67, L113), a harness variable that identifies the asker without being declared. That bears on §8.66 W-12 ("the label is a declared input"). | Add "within one harness" to the R3. Note in it the identity read from the environment. | §8.66 C5 citing lines: 08.00 L83; 08.67 L19, L33, L57, L92–94, L114 |
| W-08 | normal | C3 V-2 reading (b) "K5 … breached" | K5 (CON-0143) is about **standing** across applications and is a PROVISIONAL stipulation "for the Principal to confirm". `recommended_next_actor` is advisory, not standing. Both (a) via §9.10 Q1 and (b) are analogies. | Label both readings as analogies. Note that K5 is not in force. | 08.67 L77–81 |
| W-09 | normal | C4 critique of S0316 L33 "supported" | Fair for the byte-identity part. But S0316's "therefore" rests on three results (L27–31). The live AMBIGUOUS (one label referenced in two lanes) is an observation of the record. It supports the "coordination" half, though not the contrast with the models. "Now made by a different process" (L93) asserts an identity that C1 leaves in doubt. | Narrow the critique to the contrast clause. Drop "by a different process" or qualify it. | 08.67 L33, L93 |
| W-10 | normal | C5 "within that record its standing cannot be shown: under decision C1, within the kernel, F ('never adopted'; §1.17 E4)" | This applies the Principal's kernel decision to an estate workflow store. It presupposes that store is the designated record (K2-d, a provisional presupposition). The activation is itself recorded, in prose (S0314 L64–70). "Never adopted" (§1.14 L254) concerns adoption, not activation. | Make it conditional: "if the workflow store is the designated record (K2-d)". Say "activation", not "adoption". | 08.67 L104 |
| W-11 | normal (omission) | V-5 is absent | It is one of the three verdict grounds (S0315 L219, L290). The pointers tell sessions to consume a field the mechanism does not emit, so a recorded instruction fails to reach the actor (§9.5). | Add it as an instance in §8.67.3, or as part of C3. | — |
| W-12 | normal (omission) | Missing source content | (i) Derived caches vs authoritative storage: "grants … never merged" (S0315 L91) is an instance of states derived from entries (§9.8), which C2 relies on. (ii) The plan's prediction failed and the record was not tuned to match it (L127, L207, L251): a counter-instance to §8.66 C3's accommodation. (iii) Continuation is computed as ACTIVE∧MATCH (L168) with no producer bar, against S0313 L67–69 (§8.66 C5's "blur"). | Record each in §8.67.3. | — |
| W-13 | normal | C5 durability home "§8.62 C4" | The primary homes are §8.17 C4 (CON-0233) and §1.13 L80. "Fifth occurrence" is S0316's own count and was not checked. | Add the homes. Label the count as the source's. | 08.67 L107 |

## 3. Verdicts per claim
- **C1: PROVISIONAL.** The disagreement is quoted exactly. Its bearing on CON-0127 is misstated in the plain language and the index (W-01). The readings are weighed without the evidence of W-02 and W-03.
- **C2: VALIDATED** within its scope. The converse error is derived correctly ("two entry sequences reach one state"), and the source supports it.
- **C3: PROVISIONAL.** The split between a right verdict and a wrong reason is correct. The homes are analogies (W-05, W-06, W-08).
- **C4: PROVISIONAL.** The R3 is correct but needs its harness scope (W-07). The critique is fair but too broad (W-09).
- **C5: PROVISIONAL.** The instances are right. The kernel-F step lacks its premise (W-10).

## 4. Coverage against my inventory
- **COVERED:** S0315 items 1–4, 5 (the subset argument), 7, 8, 10, 12, 13, 15 and the slips (a)–(e). S0316 items 1–12, the lineage note, and the cross-source table.
- **PARTIAL:** S0315 item 6 (only V-6 is used), item 14 (the verdict logic), item 3 (only the re-execution).
- **MISSING:** V-5 (W-11); caches vs storage, reality over the plan, the continuation computation (W-12); S0314 L20's bearing on the map (W-02).
- **JUSTIFIABLY EXCLUDED:** the PASS details as Src (§8.67.3).

**Checks:**
- R1: not re-run.
- R2 (scope 8.67, scratch copy): tier A 0. I judged all 30 tier-D candidates in §8.67 one by one. All are ordinary English, or glossed where they occur ("fail-safe" L118, "resume" in the §8.06 factor list). No defect.
- Quote check by hand: every line-cited quote matches its source. One cosmetic change: L88 closes the bold early ("**Live (F10)**" against the source's "**Live (F10) — beyond what the suite covers:**"). Meaning unchanged.
- R3: as in the table.

## Limits
I did not open the commits, transcripts or code that the sources cite. I did not re-run R1.

## Files opened
- …/06-theory/manuscript/review/C74-S0315-S0316/BRIEF-W-STEP2.md, STEP1-INVENTORY.md, STEP8-AND-CHECKS.md (listing of this folder only)
- …/06-theory/manuscript/chapter_08/08.67-two-records-disagree-on-who-verified-and-a-state-read-as-an-act.md; 08.06-independence-of-a-check.md; 08.66-…-correct-refusals.md (all in full)
- Partial reads: chapter_08/08.00-index.md (grep), 08.62 (L112–135), 08.10 (L48–56), 08.11 and 08.50 (claim headings); chapter_01/01.14 (L297–331 and headings), 01.17 (L76–90), 01.13 (L130–138), 01.06 (L15–19); chapter_09/09.07 (L70–82), 09.08 (L1–12), 09.10 (L41–50 and grep), 09.17 (grep), 09.19 (grep); chapter_03/03.09 (grep)
- 06-theory/CONCEPT-REGISTER.tsv (rows CON-0127, 0143, 0155, 0179, 0195, 0196, 0242); THEORY-MAP.md (grep only)
- S0315, S0316 (docs/knowledgeos/reviews/2026-08-22-KOS-SESSION-BOOTSTRAP-001-INDEPENDENT-VERIFICATION.md, …-independent-verification-registration.md), already read in step 1, re-cited from it
- S0314 docs/knowledgeos/reviews/2026-08-22-KOS-SESSION-BOOTSTRAP-001-diagnostic-and-activation-registration.md; S0313 docs/knowledgeos/governance/2026-08-22-KOS-SESSION-BOOTSTRAP-001-session-completion.md (both checked on the allowed list first, rows 76–77)
- tmp/tools/inbound_citations.py and undefined_terms.py (headers), run on the scratch copy

(…/ = /home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018)

## Commands and searches
- `ls` of the C74 folder, of `chapter_08` (filtered to 08.0x/08.6x) and of `tmp/tools`; `wc -l` on STEP8 and §8.67 and on the dependency sections.
- `grep` of the 08.00 index for 8.66/8.67.
- `grep -nP '\tS031[34]\t'` on CORPUS-ALLOWED.tsv.
- `grep`, restricted to the named files or to `chapter_*/*.md` (never `review/`), for: T7, K5, E6/Q1/Q2, K2/R1, E4, "never adopted", durab/untracked, INV-ATTR, C4/C3 headings, claim headings in §8.10 and §8.11.
- `sed -n` on the ranges listed above.
- `grep` and `awk` on CONCEPT-REGISTER.tsv for CON-0127, 0143, 0179, 0195, 0196; `grep` of THEORY-MAP.md for 8.67/C74.
- Scratch copy built at /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/rev-c74-wren/c74-w-root, containing chapter_*/*.md, the register, the map, RESEARCH-PURPOSE, BOOK-OUTLINE and the tools.
- `python3 -I undefined_terms.py --root <copy> --no-corpus --scope 8.67` → /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/rev-c74-wren/c74-w-r2.txt; then grep of that output for 08.67.
- `python3 -I inbound_citations.py --root <copy> "§8.67 C1" "§8.67 C3" "§8.67 C4" "§8.67 C5" "§8.66 C5"` → /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/rev-c74-wren/c74-w-r3.txt
- I edited nothing in the repository.

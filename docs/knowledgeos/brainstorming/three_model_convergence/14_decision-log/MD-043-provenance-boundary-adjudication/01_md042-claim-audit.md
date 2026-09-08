# MD-043 §1 — MD-042 Claim Audit

Every MD-042 claim of the listed forms, with the evidence that did or did not support it at the time.
MD-042's own text is not modified — this is a cold audit against it, per Step 1's own instruction.

| MD-042 claim | Located in | Evidence MD-042 actually had | Verdict this audit reaches |
|---|---|---|---|
| "`reviews/synthesis/` ... potential lineage overlap ... K-1/K-2 research track" | `00_index.md`, `01_...md` | The user's own prior explicit resolution (a human decision, not derived by MD-042 itself) plus ~30-40 lines read before stopping | **Correctly sourced** — this was a human ruling MD-042 recorded, not a self-derived claim; MD-042 did not overstate it |
| "`reviews/kernel/` ... reads as theory-extraction-adjacent ... treated under the same absolute firewall by extension" | `01_...md` §2 | Filenames alone ("restatement is not independent arrival," "cross-track observation register") | **NOT adequately supported.** No git history, no commit-message content, and no session1/ filename check were done before this conclusion. §3 of this MD-043 finds contrary evidence. **NARROWED** |
| "confirmed, by literal filename match ... identical 'Step 272A/272B' material already read ... in the frozen Phase 5J" | `01_...md` §3 | Direct filename match (`05-ADDENDUM-STEP-272A.md`/`06-STEP-272B-REVIEW.md`) against the decision log's own prior citation of "Step 272A"/"Step 272B" | **Filename-match evidence was real, but MD-042 did not verify the decision log's own citation used the same file path**, and did not distinguish "same document" from "same filename." §5 of this MD-043 closes that gap |
| "the entire tree is treated as K-1/K-2-lineage source material ... not searched for content" (for the whole 482-file `verification/` tree) | `01_...md` §3 | `step-280/281/282` numbering continuation, a `handoff/` directory name match, and a list of vocabulary-resonant subdirectory names (`witnesses/`, `lane-conflict/`, etc.) | **Overbroad — extended a strong, narrow finding (the `step-272` cluster) to the entire tree on naming-vocabulary grounds alone.** §3/§6 of this MD-043 narrows this to a per-subdirectory finding |
| "`reviews/exec/` ... K_9/Closure(K_9) naming matching this repository's own recent commit-message style, characteristic of the parallel Lane-T session" | `01_...md` §5 | Commit-message *titles* visible in `git log` at session start, style/cadence impression only | **WRONG, contrary evidence found.** The commit's own full body (`590043f42`) is explicit first-party KnowledgeOS kernel-governance research (`GN-77`, `K_9`, `K_4`, `O_core`), with zero theory-extraction/Lane-T/P-series markers. **WITHDRAWN** |
| "3 of `brainstorming/synthesis/`'s 4 real files carry 'EXTRACTION' in their names, matching the theory-extraction track's own core term ... treated as extraction-adjacent" | `00_index.md`, `01_...md` §4 | Filename vocabulary match on the single word "EXTRACTION" | **Weak — "extraction" is a generic research-methodology term, and MD-042's own F7 finding already traced this same ledger's content pointers into admissible `brainstorming/kernel/` files, which is inconsistent with treating the ledger itself as off-limits theory-extraction output.** §6 of this MD-043 narrows this |
| "`nrna1/research/knowledgeos-sim/` ... previously excluded from MD-030's own scope ... not explicitly re-included" | `00_index.md`, `01_...md` §6 | A citation to MD-030's own prior scope note | **Accurate as far as it went, but incomplete** — MD-042 did not check whether the directory has any git history at all. §7 of this MD-043 finds it has none |
| "five of the eight nominally-authorized directories are not independently searchable" | `00_index.md`, `04_...md`, decision log, CONTEXT.md | An informal count | **The "eight directories" figure itself conflates directories, directory families, and nested subdirectories — not a clean count of 8 comparable units.** §2 of this MD-043 replaces it with a 10-distinct-path table |
| "no reason to call any of this 'independently searchable' vs. 'not'" (implicit binary framing throughout) | Whole MD-042 record | — | The binary framing itself obscured that provenance strength varies continuously across the excluded set — some exclusions are now ESTABLISHED-level (stronger than MD-042 realized), others are WITHDRAWN (weaker) |

## Summary

Of MD-042's directory-level firewall decisions: **one is unaffected** (`reviews/synthesis/`, a human
ruling, correctly recorded), **one is narrowed with a real correction found** (`reviews/kernel/` —
not theory-extraction-adjacent; a different, better-evidenced hypothesis now available),
**one claim is withdrawn outright** (`reviews/exec/` — not Lane-T style at all; direct evidence of a
different, K-1/K2-adjacent identity), **one is narrowed from whole-tree to a specific subtree**
(`brainstorming/verification/` — the `step-272`/`280`/`281`/`282`/`handoff`/etc. cluster is
well-evidenced; the rest of the 482-file tree is not), **one is weakened** (`brainstorming/
synthesis/`'s "EXTRACTION" naming — a naming-coincidence hypothesis is now at least as well
supported as the theory-extraction hypothesis), and **one gains a stronger negative finding than
before** (`knowledgeos-sim/` — zero git history, not merely "previously excluded"). No firewall is
lifted for any of these — see `05_final-classification-and-answers.md` for the resulting boundary
matrix.

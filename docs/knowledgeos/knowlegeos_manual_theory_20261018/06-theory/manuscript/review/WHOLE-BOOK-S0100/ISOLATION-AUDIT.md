# Whole-book review S0100: isolation audit of the ten part reviewers

*2026-10-19. By the Professor, after the ten hand-backs. Method: every tool call recorded in each reviewer's transcript (steps 1 and 2) was checked mechanically by `tmp/tools/isolation_audit.py` against the forbidden paths and searches of the commission. Every flag was then checked by hand against the command and its output. A second scan listed every `cd` or file path into `06-theory/` outside the copy, with its output. The reviewers' own file lists were compared with their transcripts.*

**Breach classes (plan):** B0 none · B1 an unlisted allowed file, or an unscoped search with no prohibited hit (valid; noted) · B2 the working chapters, register, map or outline, OLD texts or manuscript git history (non-blind for the sections touched; those findings stay as evidence but do not count for scoring) · B3 the key, the manifest or the scoring (void for scoring; the Rule Reviewer decides).

**Copy integrity:** all 119 files of `BOOK-UNDER-REVIEW/` hash identically to `PROFESSOR/COPY-SNAPSHOT-PRE.sha256` after the ten reviews. No reviewer wrote to the copy or to the working book (git status: no tracked change under `06-theory/`).

## Result per reviewer

| Reviewer | Step 1 (blind inventory) | Step 2 | Class | Effect on findings |
|---|---|---|---|---|
| P1-A (Opus) | B0 | B1: greps on single named allowed sources (S0012, S0028) | **B1** | none |
| P1-B (Sonnet) | B0 | **B2** (below); B1: a grep and `ls` over `docs/implementation/*.md`, beyond the twelve granted files (self-reported; only one line of a granted file was shown; file names of the directory were listed) | **B2** | see below |
| P2-A (Opus) | B0 | B0 | **B0** | none |
| P2-B (Sonnet) | B0 | B1: greps on single named allowed sources (S1524) and on granted files | **B1** | none |
| P3-A (Opus) | B0 | B1: grep on a single named allowed source (S0896) | **B1** | none |
| P3-B (Sonnet) | B0 (grep on S0896 only) | B1: greps on single named allowed sources (S0896, S0029) | **B1** | none |
| P4-A (Opus) | B0 (S0078 heading grep) | B1: grep on a single named allowed source (S1500) | **B1** | none |
| P4-B (Sonnet) | B0 | B1: greps on single named allowed sources (S0045, S0034, S0078, S0973) | **B1** | none |
| P5-A (Opus) | B0 | B0 | **B0** | none |
| P5-B (Sonnet) | B0 | **B2** (below) | **B2** | see below |

**No B3.** No reviewer reached the key, the manifest, ACCESS-RECORD, the Professor's files (`PROFESSOR/`, `PROFESSOR-STEP8-FINDINGS.md`, `README-PROFESSOR.md`) or another part's folder. Every fifth (skimmed) source is on its part's allowed list (P1 S0019, S0016 · P2 S0009, S0021 · P3 S0896 ×2 · P4 S0078, S0034 · P5 S0097, S0054).

## The two B2 breaches

**P1-B (Sonnet), step 2.** Four commands ran with `cd …/06-theory/manuscript` (the **working** manuscript) instead of the copy:
- `sed -n 1,60p chapter_03/03.09-what-executes.md` (working §3.9, L1–60);
- `sed -n 10,45p chapter_03/03.04-knowledge-and-artifact.md` (working §3.4, L10–45);
- `grep "not yet a function…" chapter_09/09.07*.md` and a grep in `chapter_03/03.04` (working §9.7, one line; §3.4);
- `grep -rln "work-execution…" chapter_0*` and `grep -rn "taxonomy of change…"` (working chapter files; hit `chapter_04/04.00-index.md`).

The reviewer reported these as reads of the copy, so it was not aware of the breach. Step 1 was saved before, so **the inventory is blind**. P1's own chapters (Ch. 0, Ch. 1) were read from the copy. The working book was frozen and unchanged since the copy was made, but by rule the touched sections count as non-blind: **P1-B findings 6 (§3.9, F11), 20 (§3.4) and 21 (Ch. 4 index) are evidence only and do not count for scoring**, nor does any part of its report resting on the §9.7 line. *(The Professor did not compare the working text with the copy; the rule forbids it.)*

**P5-B (Sonnet), step 2.** One command: `cd …/06-theory/manuscript/chapter_09 2>/dev/null || cd …/BOOK-UNDER-REVIEW/…/chapter_09; grep -n "H4\|M5\|H2\b" 09.08-is-history-a-primitive.md`. The first `cd` succeeded, so the grep read the **working** §9.8 (about six lines: H2, H4, the H4/H5 sentence, the Lemma D head). Step 1 was blind. **No P5-B finding rests on §9.8**; the touched lines are non-blind by rule, with no effect on any scored finding.

## Further observations (not isolation breaches)

- **Writes despite "read-only":** P2-A wrote a check script (`chk.py`: laminar-family count and the containment counterexample; pure computation, no file reads). P1-A and P4-B wrote scratch concatenations of copy sections (`c1b–c1d.txt`, `ch6b.txt`, `ch9a–c.txt`). All went to the Professor's session scratchpad, not to the repository, the copy or the working book. No effect on blindness; noted against the commission's "no writes".
- **Harness tool-result files:** P1-B, P2-B and P4-B read the harness's saved outputs of their own commands; these are copies of what they had already been shown.
- **Unprompted mention:** P5-A wrote "I did not search for a planted gap as such". No reviewer was told of a test (briefs and commissions checked: no "plant", "test is", "key", "score"). The likely origin is the book itself: the copy's §8.5 (which P5-A read in full), §5.6 and the register mention the earlier "C10 planted-gap test" by name. The reviewer agent's definition (`.claude/agents/kos-theory-reviewer.md`) contains no such term. The mention is not evidence of disclosure of this test. Reported for the Rule Reviewer.
- **Brief error found by reviewers:** the briefs give `06-theory/BOOK-OUTLINE.md` and `06-theory/CHAPTER-PLAN.md`; in the copy both lie under `06-theory/manuscript/`. No effect.

## Limit

The mechanical audit sees tool calls; it cannot see what a reviewer knew from training or from the commission text. Process independence only (§1.14.3-3).

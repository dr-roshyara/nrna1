# C97: reviewer access deviation (recorded before settlement)

*2026-11-02. Recorded by the Professor from W's own disclosure in REVIEW-REPORT.md ("Limits", deviation 2). Settlement of C97 is paused under stop condition (d) until the Principal rules.*

## What happened (W's account, not independently verified)
- For step 2, W made a scratch copy of `06-theory/` (excluding `manuscript/review`) by `rsync`. The copy included six files that BRIEF-W-STEP2.md does not allow: `EXTERNAL-SOURCES.md`, `PRINCIPAL-TESTIMONY.md`, and, under `manuscript/`, BASELINE-REVISIONS, CHAPTER-PLAN, TARGET-TOC and TODO-TRIAGE-S0100.
- Over that copy W ran one `grep -rn "8\.90|S0366"` and its first R2 (`undefined_terms.py --no-corpus --scope 08.90`) and R3 (`inbound_citations.py`) runs. W states that it did not view the six files' content and that the grep's only hit was in 08.00.
- W then deleted the six copies, re-ran R2 and R3 on the clean copy, and reports identical output (`c97-w-r2b.txt`, sha256 `32a36220…68b4`).
- W also ran `--help` on four tools before passing `--root` (deviation 1); W states that they resolved to scratch paths and read no repository file.

## The rule it departs from
BRIEF-W-STEP2.md L22: "A file you may not open, you may not search either. Restrict every search to the files above." (the Principal's standing rule that a reviewer must not search any file its brief does not allow).

## What the Professor has not done
- No finding of the report has been applied; §8.90 is unchanged since its draft (`5290ef928`).
- The Professor has not opened any of the six files, or W's scratch copies of them.

## Assessment (the Professor's, for the Principal; not a decision)
- Copying the files read them, and the search ran over them: a breach of the brief, though content was not displayed, by W's account.
- None of the report's findings cites any of the six files; its evidence is to allowed book sections and sources.
- The cause is in the commission: the step-2 prompt says "run every tool with an explicit root on a scratch copy" but does not say "copy only the files listed". From the next commission the Professor would state the copy list explicitly.

# C103: reviewer access deviation (recorded before settlement)

*2026-11-03. Recorded by the Professor from W's own disclosure in REVIEW-REPORT.md ("Limits and checks", "Access breach, mine"). Settlement of C103 is paused under stop condition (d) until the Principal rules.*

## What happened (W's account, not independently verified)
- In step 2, W ran one grep with the glob `20260823-1*.md` in `docs/knowledgeos/brainstorming/kernel/`.
- The glob matched two files that BRIEF-W-STEP2.md does not allow:
  - S0365 (`20260823-112155-perplexity-kernel-domain-responsibility-brainstorming.md`);
  - S0368 (`20260823-113645-deepseek-consistency-boundary-domain-discovery.md`).
- Four matching lines from them were displayed to W. W states that it did not rely on them.

## The rule it departs from
- BRIEF-W-STEP2.md: "A file you may not open, you may not search either. Restrict every search to the files above." This is the Principal's standing rule that a reviewer must not search any file its brief does not allow.
- The brief allows, for checking only, S0366, S0367, S0369, S0370 and S0371. Neither S0365 nor S0368 is on the brief's list or on COPY-LIST-STEP2.txt (checked: 0 matches).

## What makes it different from C97 (facts, not a ruling)
- Both files are corpus sources on CORPUS-ALLOWED.tsv, read in index order, settled in §8.89 (S0365) and §8.92 (S0368), and quoted in the book. They are not sealed, hold-out, quarantine or exposed sources.
- No file outside the corpus allowed list was touched. No copy was made, and no tool ran over them.

## What the Professor has not done
- No finding of the report has been applied. §8.96 is unchanged since its draft (`9f1be2a86`).
- The Professor has not checked which four lines were displayed. W's report does not quote them.

## Assessment (the Professor's, for the Principal; not a decision)
- The search reached two files outside the brief, so it breached the brief, though both are allowed corpus sources the book already uses.
- No finding in the report cites S0365 or S0368 as evidence. W-05 relies on S0366, S0367, S0369, S0370 and S0371, and states "S0368 was not checked (not allowed)".
- The cause is in W's command (a glob), not in the commission: the brief and the copy list name every file individually.

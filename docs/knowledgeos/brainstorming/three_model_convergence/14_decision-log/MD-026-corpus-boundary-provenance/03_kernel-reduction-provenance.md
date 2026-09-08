# `kernel-reduction/` Provenance — Git Evidence

## Machine-observable facts (CB-4, CB-5)

Direct `git log` inspection: **every file under `docs/knowledgeos/research/` was first tracked in one
commit, `70fee73c`, dated 2026-09-06.** The commit's own subject: *"docs(knowledgeos): check in the
brainstorming corpus and research lanes as untracked-until-now research material."* Its own body,
quoted verbatim:

> *"Brings the research corpus under version control for the first time. It is brainstorming material
> and is checked in as such — multiple, competing and superseded formulations coexist deliberately, and
> nothing here is canonical: `phase_measure_theory/`... `verification/`... `three_model_convergence/`...
> `kernel/`, `classification/`, `falsification/`, `synthesis/`, `research/theory-v1.1-simulation/`,
> `research/kernel-reduction/`"*

**This is the same single commit that checked in the entire `docs/knowledgeos/brainstorming/` corpus
this reconstruction has read since Phase 0** — `git log` on a known primary-corpus file
(`20260901-222111_prompt-kernel-reduction-and-minimality-experiment.md`, M0030 itself) confirms its own
most recent tracked history includes this same 2026-09-06 check-in event as its origin in this
repository.

## What this establishes

1. **MD-010/MD-011's own corpus-boundary decisions (2026-09-01) predate this repository's git history
   for this material by 5 days.** The boundary decision cannot have been informed by, or have
   deliberately excluded, a directory that was not yet under version control — this rules out
   "deliberate, considered exclusion of `research/` specifically" as the mechanism (contra one reading
   of Outcome A).
2. **The check-in commit's own author (the human collaborator, not this session) already distinguished
   "the brainstorming corpus" from "research lanes" in the same sentence**, and separately itemized
   `research/theory-v1.1-simulation/` and `research/kernel-reduction/` apart from the enumerated
   `brainstorming/` subdirectories (`phase_measure_theory/`, `verification/`, `three_model_convergence/`,
   `kernel/`, `classification/`, `falsification/`, `synthesis/`). **This is a human-authored,
   contemporaneous linguistic distinction, not this study's own inference** — `[DIRECT EVIDENCE]`.

## What this does NOT establish

This does not establish that `research/` content was *authored* after 2026-09-01 — only that it was
*tracked in git* after that date. Git check-in date is not authorship date, exactly as Phase 5J's own
prior finding established for other parts of this corpus (a single bulk-import reflects when material
was checked in, not written). Content-internal dating (`04`) is required to establish authorship
chronology, and is treated separately.

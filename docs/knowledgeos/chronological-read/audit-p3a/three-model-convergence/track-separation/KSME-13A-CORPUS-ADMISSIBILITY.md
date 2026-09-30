---
source_track: TRACK-A-PHASE-MEASURE
input_artifacts: [KSME-13-grounding-forks]
derived_from: [mathematical_ideas_that_can_be_implemented/20260902-170000, .claude/CONTEXT.md BC-02.14-20 entries]
cross_track_dependency: unresolved — see verdict
---

# KSME-13A — Corpus Admissibility Classification

## The two paths, corrected

`mathematical_ideas_that_can_be_implemented/20260902-170000_the-research-decision-and-optimized-model.md`
§D.2 cites `research/knowledgeos-sim/kos12/...` — a **repo-root path, not under `docs/`**. A distinct directory,
`docs/knowledgeos/research/theory-v1.2-simulation/` (33 markdown files, no code, self-described in its own
`00-INDEX.md`: "simulation of the theory, not of the product... not canonical architecture... not a
proven theory"), was initially suspected to be the same location — **it is not**. The actual `kos12`
codebase (`contr.py`, `contr2.py`, `comp.py`, an `fde/` submodule, run scripts, ~140 real JSON result
files) lives at `research/knowledgeos-sim/`.

## The decisive finding: this is not unclassified territory

`.claude/CONTEXT.md` already documents extensive prior engagement with `research/knowledgeos-sim/`, under
the label **`BC-02.14` through `BC-02.20`+ — predating the entire KSME series and the Track-A/Track-B
split itself.** A dedicated `BC-02.14-K-OBJECT-REGISTRY.md` exists; a "PROFOUND UNEXPECTED FIND" was
logged; real code was inspected (`kos/state.py`, `transitions.py`, `factivity.py`); an evidence-tier
self-correction was made ("not read in depth" → "inspected/executed"). **From that point forward, every
KSME-era `CONTEXT.md` entry explicitly logs `knowledgeos-sim/` as deliberately "untouched"** — an
established session practice, never formalized as an exclusion rule, never formally admitted either.

**One discrepancy, disclosed not adjudicated**: `CONTEXT.md:3057` states "research/knowledgeos-sim/ has
zero git history at all" — this conflicts with this pass's finding of a real single commit (`e3e47b139`,
2026-09-12). Possible explanations: the note predates that commit, or refers to a different observation.
Not resolved here.

## Git provenance (both directories)

`docs/knowledgeos/research/theory-v1.2-simulation/`: single commit `6f38df520` (2026-09-06), all 33 files
dated 2026-09-02, internal mtimes spread 09:25–17:33 (8 hours) — genuine incremental authoring, the
opposite of the `182xxx` cluster's 26-second signature.

`research/knowledgeos-sim/`: single commit `e3e47b139` (2026-09-12, "knowledge-theory-research"). No
fabrication indicators found in either directory.

## Classification verdict

Neither cleanly "admissible historically (Track A)" nor cleanly "EXTERNAL/SCOPE-UNRESOLVED." The accurate
category, per direct evidence: **"previously investigated under a separate label (`BC-02.14`+), never
formally admitted or firewalled, and consistently left untouched by this session's own subsequent KSME
practice."**

## Recommendation (not a decision — awaiting explicit authorization)

Continuity with the session's own established posture is the safer default: leave `research/
knowledgeos-sim/` untouched pending explicit re-authorization, rather than treating this fork's discovery
as license to open it. If authorized, the correct first step is re-reading this session's OWN prior notes
(`BC-02.14-K-OBJECT-REGISTRY.md` and the `CONTEXT.md` `BC-02.14`–`20` entries) — not new corpus material —
since those may already answer most of what `Contr`/`C6`/`C7`/`majority` grounding needs without opening
any new file.

## What remains explicitly undecided by this pass

Whether `BC-02.14`-`20`'s findings may be folded into KSME-13's dependency closure. This is logged as an
open item in `KSME-13A-UNDERIVED-TERM-QUEUE.md`, not silently resolved either way.

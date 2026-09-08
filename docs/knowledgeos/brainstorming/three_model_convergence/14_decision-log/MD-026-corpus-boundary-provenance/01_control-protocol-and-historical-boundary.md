# Control Protocol and Historical Boundary — Read Directly, Not Assumed

## MD-010 (2026-09-01), quoted directly

*"`docs/knowledgeos/brainstorming/` is the corpus root. Five top-level subdirectories are **derived
research-processing artifacts**, not primary source material, and are **excluded from the primary
sequential reading pass**"* (`verification/`, `synthesis/`, `falsification/`, `corpus/`,
`classification/` — 454 entries). At this point, `docs/knowledgeos/` root-level files (711 of them)
were **still treated as primary corpus**.

## MD-011 (2026-09-01, same day), quoted directly

*"MD-010 excluded five derived-artifact subdirectories from primary evidence but still treated all of
`docs/knowledgeos/`... as primary corpus. The human instruction narrows this: **the primary corpus is
strictly `docs/knowledgeos/brainstorming/` and its subdirectories**... Files directly under
`docs/knowledgeos/` (not inside `brainstorming/`) are **not** part of 'the brainstorming corpus'... and
are out of scope for the sequential pass."* Recomputed split: PRIMARY 1,155 · 5 excluded subdirectories
454 · `OUT_OF_SCOPE_ROOT` 711 · Total 2,320.

## What this establishes about the boundary mechanism (CB-2)

**The boundary was defined by filesystem location** (path prefix immediately after
`docs/knowledgeos/`), not by provenance, acquisition manifest, or research programme — MD-010's own
matching rule states this explicitly: *"only the path component immediately after
`docs/knowledgeos/brainstorming/` is checked."*

## What this does NOT establish

Neither MD-010 nor MD-011 mentions `docs/knowledgeos/research/` by name, anywhere. The 711-count
`OUT_OF_SCOPE_ROOT` figure is described only by example ("architecture baselines, validation matrices,
discovery reports, etc.") — not as an exhaustive named list. **Whether `research/` was part of that
711-file crawl, or simply did not exist for the crawl to see, is a chronology question, not answerable
from MD-010/MD-011's own text alone** — addressed directly in `03`.

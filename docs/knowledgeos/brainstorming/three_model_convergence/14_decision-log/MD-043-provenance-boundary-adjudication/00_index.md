# MD-043 — Provenance Boundary and Evidence-Landscape Adjudication

**Status: EXECUTED, 2026-09-09.**

## Purpose

MD-042 converted several filename/structure signals into directory-level firewalls without
establishing provenance at content level — a fair methodological critique. This phase does not
perform kernel or Warrant research. It asks a narrower, prior question for each firewalled landscape:
what is the actual evidential basis for its provenance/boundary status, classified on a four-level
scale (ESTABLISHED / STRONGLY INDICATED / PLAUSIBLE / UNRESOLVED), using git history, commit
messages, decision-log cross-references, and — only where necessary — minimal targeted filename/
metadata inspection (never full scientific content-reading, never theory-extraction/).

## Method

Git archaeology (`git log --diff-filter=A --follow`, `git show --stat`, commit-message reading) for
every disputed landscape, cross-referenced against the frozen `model-boundary-decisions.md` record
and MD-042's own artifacts. No new scientific content was read from any firewalled directory beyond
what was already read in MD-042 (a README, a small set of filenames, one already-public corpus-cutoff
marker file's own listing).

## Central result, stated up front

**Three of MD-042's own provenance hypotheses do not survive this check and are narrowed or
withdrawn; two are sharpened with stronger evidence than MD-042 had; one is unaffected.** No firewall
is lifted — every landscape MD-042 excluded remains excluded — but the *reasons* change materially for
several of them, and the "five of eight" bookkeeping is replaced with a precise 10-path scope table
(the "eight directories" figure conflated directories, directory families, and nested subdirectories).

## Artifact map

- `00_index.md` — this file.
- `01_md042-claim-audit.md` — every MD-042 claim of "same lineage"/"identical"/"firewalled"/etc.,
  with the evidence that did or did not support it (Step 1).
- `02_scope-reconciliation-table.md` — the precise 10-distinct-path scope table replacing "eight
  directories" (Step 2).
- `03_provenance-verification.md` — the git-archaeology findings and per-landscape provenance
  classification, Steps 3–9 (the phase's own central content).
- `04_git-integrity-audit.md` — reconstruction of commit `196aa607e` and the shared-file absorption
  into the parallel session's `c821abece` (Step 12).
- `05_final-classification-and-answers.md` — Steps 10–11, the required provenance boundary matrix,
  the K-1/K2/GA-001/GA-038 and Warrant impact answers, main-goal impact, next-step classification.
- `06_verification-and-completion.md` — verification suite and completion statement.

## Non-scope (unchanged, restated)

Does not reopen Phase 5A–5N. Does not reopen K-1/K2. Does not resolve GA-001 or GA-038. Does not
admit any previously firewalled material. Does not define Warrant or "surviving a defeater." Does not
select V0/V6. Does not canonicalize Kernel/K_t. Does not merge F1–F8. Does not open Stage 07. Does
not select a model. Does not perform kernel or Warrant research. Does not treat directory names or
repeated observations as proof of a bounded context or of independent replication.

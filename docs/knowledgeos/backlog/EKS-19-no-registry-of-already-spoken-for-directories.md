# EKS-19 — No registry of already-spoken-for directories; overlap is rediscovered by hand each time

**Status: BACKLOG · OPERATING-MODEL EXPOSURE**
**Class:** operating-model problem (research-boundary discoverability)
**Added:** 2026-09-09, from the `three_model_convergence` research programme's own MD-042
(cross-landscape semantic kernel and warrant audit).

## 1. The problem, in business language

When a research session is told "go and search these directories for evidence," it currently has no
single place to check whether a named directory already belongs to a *different, off-limits* piece of
work — either a separate research initiative that this session is not allowed to touch, or a part of
this same programme that was already finished and locked down. The only way to find out is to open the
directory and look at the file names, one directory at a time, and use judgment about whether the
names "look like" they belong to the other work.

That worked this time, but only because the naming happened to be distinctive enough to notice. In one
day of work on this one research programme, this exact situation came up **four separate times**:

1. A directory named `reviews/synthesis/` turned out to overlap with an already-finished and
   locked-down part of this same programme — discovered only by reading the first few lines of two of
   its files and recognizing familiar vocabulary.
2. A directory named `reviews/kernel/` had file names ("restatement is not independent arrival," a
   "cross-track observation register") that read like the style of a completely separate, off-limits
   research effort — discovered only by eyeballing the file list, never opening a file.
3. A directory named `brainstorming/verification/` (482 files) turned out to contain, several folders
   deep, two files whose names were an *exact, literal match* to material already read and closed out
   weeks earlier under this same programme's own already-finished work — discovered only because
   someone happened to open that one subfolder's file listing.
4. Three files inside a small directory named `brainstorming/synthesis/` had "EXTRACTION" in their
   file names, matching the core word of the separate, off-limits research effort named in point 2 —
   again spotted only by reading the file list, not because anything flagged it.

Each of these was caught. But each was caught by a different person (or the same person, working
alone, four separate times) doing the same manual check from scratch, with no record of "we already
figured this out once" to consult. **Nothing currently stops a fifth instance from being missed** — if
the next off-limits directory happens to have an unremarkable name, there is no safety net beyond
someone's attention that day.

## 2. Why this matters

This research programme's own foundational rule is that evidence from a different, uninvestigated
lineage must never be quietly treated as if it belonged to the material actually being studied. That
rule is only as strong as the session's ability to notice the overlap in the first place. Right now,
noticing depends entirely on the names of files happening to be distinctive enough for a human or an
AI assistant to recognize on sight, in the moment, with no list of "known overlaps" to check against.
A missed overlap would not announce itself — the search would simply return results, the results would
look like ordinary evidence, and the mixing-in would go unnoticed until, if ever, someone happened to
compare the two documents directly.

## 3. What this is not

This is not a claim that any specific mixing-in has already happened undetected — all four instances
found this session were, in fact, caught before any content was read or used as evidence. This is not
a request to build automated content-fingerprinting or any specific technical solution — that is a
design question for whoever picks this up. This is not a duplicate of existing items: `EKS-15` is
about *how to admit* evidence found outside the corpus root once a human decides to allow it — a
different problem from *noticing in advance* that a directory belongs to someone else's work at all.
`EKS-16` is about not checking whether the existing research estate already answered a question before
re-deriving it — a "did we look" problem, not a "did we recognize this territory as off-limits"
problem. `EKS-17` is about one research topic being split across two identically-named folders — a
"where do I look" problem, not an "is this place already spoken for" problem. Checked against all
three (`ES-005.4`, never a copy) — distinct on each count.

## 4. Candidate direction (not proposed as the solution — for whoever picks this up to design)

Some kind of short, plainly-worded, kept-up-to-date list — even a single page — naming the
directories/paths that are already known to belong to a different piece of work or to an
already-finished, locked part of this one, so the next search can check a name against that list in
seconds instead of re-deriving the boundary from scratch. Whether that takes the shape of a document,
a small checklist step in the existing session-start routine, or something else is a design decision
for the person or team who takes this on.

## 5. Urgency

Low today — every instance so far was caught. Rises with every additional directory added to this
corpus and with every new session that works on this material without having lived through today's
four discoveries first-hand.

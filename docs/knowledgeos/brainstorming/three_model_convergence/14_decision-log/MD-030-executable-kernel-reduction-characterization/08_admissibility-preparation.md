# Admissibility Preparation

**No admission decision is made here.** This artifact classifies what a *future* admission decision
would need to weigh, and keeps three distinct questions separate throughout, per the authorization:

- **Scientific relevance** — does this artifact bear on the B `Validate` input-specification
  question MD-029 left open?
- **Admissibility** — could this artifact legitimately be used as evidence under this programme's
  own governance discipline (provenance, authority, corpus-boundary rules)?
- **Model-B membership** — would using it as evidence make it part of Model B itself?

A "yes" to the first does not imply a "yes" to the second or third.

## Per-artifact classification

| Artifact | Relevance | Admissibility | Model-B membership |
|---|---|---|---|
| `kr/carriers.py` lines 65–66 (the two `Verdict` derivation rules) | **Relevant and potentially necessary** — the single most on-point piece of new information this study found for the Validate input-specification gap | **Inadmissible without additional provenance** (see below) | Would NOT automatically confer membership even if admitted — see reasoning below |
| `kr/variants.py` `V6` (the alternative 3-input rule) | **Relevant but unnecessary in isolation; necessary as a qualifier if `carriers.py` is ever proposed for admission** — without it, admitting `carriers.py` alone would misrepresent an internally-contested design choice as a fixed fact | Same provenance status as `carriers.py` (same untracked file, same session) | N/A |
| `kr/atoms.py` (`A_WARRANT`), `kr/operators.py` (`Validate`'s atom-set), `kr/capabilities.py` (`C10`) | **Relevant but unnecessary** — corroborate, do not extend, what the two already-admitted narrative files already state | N/A — adds nothing the corpus doesn't already have admitted | N/A |
| `results/baseline.json`, `results/minimal_kernels.json` | **Relevant but unnecessary for the input-specification question specifically** — they show a downstream *consequence* (C10's reachability) of a derivation rule, not the rule's own source authority | Same untracked-file provenance status | N/A |
| `README.md` | **Relevant as context** (states the lane's own self-declared non-canonical status — itself useful admissibility evidence) | N/A (already read as context throughout this study; not proposed as free-standing evidence) | N/A |
| All other files (`scenarios.py`, `properties.py`, `ablate.py`, `audit_variants.py`, `infotheory.py`, `run_all.py`'s non-Validate sections, the 9 remaining `results/*.json`) | **Not relevant** to the Validate input-specification question | N/A | N/A |

## Why `carriers.py`'s derivation rule is "inadmissible without additional provenance" — the reasoning, stated explicitly

Three independent qualifications, each individually sufficient to block treating it as settled
source evidence, found together:

1. **Zero git history** (`02`) — weaker provenance than the narrative lane's own already-cautious
   `RECONSTRUCTED PROVENANCE` status; this material has no version-control record at all, on any
   branch, ever. MD-026's own established discipline ("Git-tracking date ≠ existence" and its
   converse) applies with even more force here: there isn't even a Git-tracking date to reason
   about.
2. **Self-declared non-canonical status** (`03`, README) — "[EXP]... a research instrument, not
   KnowledgeOS architecture. Nothing here is canonical." This is the artifact's own stated intended
   role, and this study treats it (per the authorization's own instruction) as *evidence about
   intended role*, not as proof of governance status either way — but it is evidence, and it points
   against, not toward, canonical/authoritative status.
3. **Internally contested even within its own lane** (`03`, `variants.py` `V6`) — the same codebase
   that encodes the two-input rule also encodes and tests a three-input alternative as an explicit
   "robustness variant," meaning the rule is not even presented, by its own authors, as the single
   settled answer within this experimental instrument — let alone as Model B's own authoritative
   specification.

## Would admission confer Model-B membership? No — and this needs to be stated explicitly

Per this programme's own established discipline (MD-028-DQ-1's 12 conditions, condition explicitly
distinguishing admission-for-a-narrow-purpose from corpus membership): even if a future, separate
human decision admitted `carriers.py` (or a specific extract of it) for the narrow purpose of
testing whether it resolves the Validate input gap, that would not by itself make the file "Model
B evidence" in the sense Phase 2's own evidence-base discipline uses the term — exactly the same
distinction MD-028-DQ-1 already drew for the two currently-admitted narrative files. This is stated
here so a future decision-maker does not have to re-derive it.

## Direct answer to the census's own central question (per `04`'s "does not establish" line)

**Does the executable lane supply what the two currently-admitted files leave unspecified?**
Yes, in the narrow, technical sense that it contains a concrete, computationally exercised
candidate rule the narrative files do not state. **No**, in the sense that matters for closing the
gap MD-029 identified: that rule's own status (untracked, self-declared experimental, internally
contested) does not rise to "source-stated specification" — using it now would replace one disclosed
gap (`NOT SPECIFIED BY SOURCE`) with a different disclosed gap (`MACHINE-ENCODED, SOURCE-DOCUMENTATION
ABSENT, PROVENANCE WEAKER THAN THE NARRATIVE LANE`), not close it.

## What this means for `06-composition-rules.md`

This study did not read `06-composition-rules.md` (out of scope, `00`). It cannot determine whether
`06`'s content matches, extends, or is silent on `carriers.py`'s rule. **This is itself a finding**:
the question "is 06 the minimal sufficient admission" cannot be answered by this study alone —
answering it would require either (a) a future study that reads `06` (a narrative-lane document,
inside `docs/knowledgeos/research/kernel-reduction/`, a different admissibility question than this
study's own scope), or (b) treating this study's own finding (a concrete, if weakly-provenanced,
computed candidate already exists) as independently relevant regardless of what `06` says.

# PKS Observation — Triple Qualification Moved From the Engineering Flow to the Acceptance Pass

**Date:** 2026-08-02 · **Kind:** operational evidence · **Domain:** PKS
**Placement:** derived — `php scripts/doc-placement.php --scope=product-specific --domain=pks` → `docs/pks`
**Status:** recorded observation. **No methodology changed. No ruling issued. R-67 stands.**

---

## Observation

> **WP-3A completed Triple Qualification retrospectively before acceptance, unlike WP-1 and WP-2 where qualification formed part of the engineering flow. Governance remained valid because acceptance followed qualification. Future work should observe whether this represents an isolated occurrence or a recurring process pattern before any methodology change is considered.**

*(Wording adopted verbatim from the ARB, 2026-08-02.)*

## Evidence

**Established from the repository, not from recollection:**

| Time | Event | Verification |
|---|---|---|
| 2026-07-30 | WP-3A reaches GREEN + gates + dev guide | plan §"WP-3A GREEN DONE + GATES PASS" |
| 2026-07-30 | **Triple Qualification absent** | the plan's own progress line reads **`⏳ triple qualification + ARB slice acceptance`** — the omission was declared, not concealed |
| 2026-08-02 01:06 | **Triple Qualification performed and appended** | `731ebaa51` · `git log -S "Triple Qualification — WP-3A"` returns **exactly one commit** |
| 2026-08-02 01:13 | Catalog finding withdrawn | `c2d4b46b7` |
| 2026-08-02 01:16 | **WP-3A accepted — R-67** | `828cf88d8` |

**Ten minutes separate the gate from the ruling. The order is what matters: qualification preceded acceptance.**

**Precedent it departs from:** WP-1 and WP-2 performed Triple Qualification **inside the slice, by engineering, recorded in-plan** (WP-2 plan §15, *"all three categories PASS"*). **WP-3A's was performed three days after GREEN, by the Recording Architect, in the same working pass that produced the acceptance review.**

**WP-4 has the identical shape** — its plan also carried `⏳`, and its qualification is in the same commit `731ebaa51`.

## Classification

| Finding | Classification |
|---|---|
| Triple Qualification omitted at GREEN | **Operational Observation** |
| Triple Qualification completed before acceptance | **Governance Evidence** |
| The acceptance is therefore valid | **Governance Conclusion** |
| Execution pattern differs from WP-1/WP-2 | **PKS Observation** — this document |
| *Standardising the timing* | **KnowledgeOS candidate — only after repeated evidence** |

> **The last row is deliberately not an Engineering Standard. It happened once, and one occurrence is not methodology (ES-006.1).**

## What the observation suggests about the process

**Two distinctions the episode makes visible, which is why it is worth keeping:**

- **Engineering progression and governance authorization are separable.** The engineering process was delayed; the governance sequence stayed valid. **A late gate is not an ungated acceptance** — the two failures look alike in a status table and are not alike at all.
- **The party performing a Definition-of-Done gate drifted.** In WP-1/WP-2 engineering qualified its own work as part of finishing it. Here the gate was cleared by the Recording Architect while assembling the acceptance package. **The gate was cleared either way; who clears it, and at which stage, is the variable to watch.**

**The declared `⏳` is the reason this is an observation rather than an incident.** The plan said the gate was outstanding. **The omission was visible in the artifact the whole time; what was missing was anyone reading it before the acceptance question was asked.**

## What is explicitly NOT concluded

**R-67 is not amended, suspended or reopened.** The acceptance is supported by the evidence: the gate was cleared before the ruling.

> **The acceptance record stands; the process variation becomes operational evidence.**

**And the history is preserved rather than tidied.** The plan's `⏳`, the separate commit, and the three-day gap all remain visible. **Rewriting them to look like WP-1/WP-2 would destroy the only evidence this observation rests on.**

## Promotion condition

**This observation is a candidate for nothing yet.** **It is promoted only if a subsequent work package reproduces the pattern** — a Definition-of-Done gate deferred out of the engineering flow and cleared during the acceptance pass. **Two occurrences make a pattern; one makes a record.**

**WP-4 is not a second occurrence.** Its qualification was performed in the same pass, by the same party, for the same reason — **it is the same occurrence with two subjects.**

---

**Traceability:** `.claude/plans/WP-3-challengerouted-published-language.md` §Triple Qualification · `.claude/plans/WP-4-apm-wiring.md` §Triple Qualification · `.claude/plans/WP-2-apm-core.md` §15 (the precedent) · `engineering/verification/reports/2026-08-02-wp3a-acceptance-review.md` · **R-67** · **ES-006.1** (promotion ladder) · **R-34** (no ruling by inference) · commits `731ebaa51` · `828cf88d8`. **Observation only — no methodology changed, no artifact rewritten, no ruling issued.**

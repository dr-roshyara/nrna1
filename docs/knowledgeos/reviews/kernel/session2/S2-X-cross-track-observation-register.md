# Session-2 · CROSS-TRACK OBSERVATION register

> **Purpose.** Observations about the *relationships between tracks* — Session-1 extraction methodology, Session-2 review methodology, research findings, formal architecture, adjudication process. Established on HPA direction, 2026-08-25.
>
> **⛔ Rules of this register.** Cross-track observations are **never mixed with corpus findings** and are **never routed automatically into adjudication**. A cross-track observation does **not** become a Kernel finding by being recorded here. Entries are numbered `X-nnn` and kept separate from the `S2-Fnnn` finding series.

---

## X-001 · "Preserve distinctions" and the constitutional sufficiency test are principles of different tracks

| | |
|---|---|
| **SOURCE** | `session1/S1-F002` (the corpus's own method rule) · and the constitutional sufficiency test established by HPA ruling on F-CM-1a |
| **TRACKS INVOLVED** | research/extraction ↔ architecture/adjudication |
| **STATUS** | OPEN · recorded only · **not evidence for either principle** |

**OBSERVATION.** Two principles now operate in this programme that pull in opposite directions when read carelessly:

| Principle | Track | What it says |
|---|---|---|
| *"preserve these distinctions rather than prematurely normalizing them"* | **research / extraction** | when analysing, do not merge concepts that behave differently |
| *"record a new constitutional distinction only when existing law is insufficient"* | **architecture / adjudication** | when legislating, do not add a distinction without demonstrated need |

**They are not contradictory.** One governs **analysis vocabulary** — where over-merging destroys information that cannot be recovered. The other governs **law** — where over-adding creates permanent obligations that cannot easily be removed. The asymmetry is deliberate: analysis is cheap to revise, law is not.

**WHY IT MATTERS.** The risk is not confusion between them; it is **citation across them**. A future actor may reasonably write *"the corpus records that distinctions must be preserved, therefore this distinction should be recorded in §15"* — converting a research-hygiene rule into an argument for constitutional vocabulary. That inference is invalid and would defeat the sufficiency test precisely where it is most needed, since the corpus is a generator of interesting distinctions.

The converse misuse is equally available: *"the sufficiency test says do not add distinctions, therefore the extraction should merge these"* — which would license exactly the premature normalisation the corpus warned against.

**Neither principle is evidence for the other, and neither may be cited across the boundary without an explicit later analysis that establishes the crossing.** Recorded so the crossing, if it is ever made, must be made deliberately and on the record.

---

## X-002 · A scope exclusion can be circumvented by secondary citation

| | |
|---|---|
| **SOURCE** | `session1/S1-F004`, Finding 4 scope note |
| **TRACKS INVOLVED** | Session-1 extraction methodology ↔ research findings |
| **STATUS** | OPEN · recorded as a methodology observation · **no criticism implied — Session 1 disclosed it** |

**OBSERVATION.** S1-F004 records, transparently:

> ⟦I⟧ *"**Scope note:** the document that develops the OS analogy at length — `_misc/20260819-224159-linux-analogy-kernel-os-model.md` — is **out of scope** by the `_misc` exclusion. This section partially recovers that evidence, but only as a rating, not as the argument."*

So evidence excluded from the corpus by scope has **entered the register indirectly**, through a second in-scope document that summarises it. Session 1 flagged this itself, which is the right handling of a single instance.

**WHY IT MATTERS.** The mechanism generalises: **any scope exclusion is porous to secondary citation.** A document ruled out of scope can still shape the register wherever an in-scope document cites, rates or summarises it — and the secondary route carries *less* of the original's argument while carrying *all* of its conclusion. That is the weaker half of the evidence arriving without the stronger half.

Three consequences worth keeping visible:

1. **Directional bias.** Summaries preserve verdicts more reliably than reasoning, so secondary citation tends to import *ratings* — exactly what happened here (*"only as a rating, not as the argument"*).
2. **Provenance depth.** The register's provenance fields record the citing document, not the cited one. Depth-2 provenance is currently invisible in the artifact format.
3. **Audit difficulty.** Because the disclosure is prose rather than a field, a later reader scanning provenance tables will not see that an excluded document is in play.

**Not a defect claim against Session 1**, which disclosed the instance in the artifact where it occurred. Recorded so that the *pattern* is visible if it recurs, and so that a decision about whether depth-2 citations need a provenance field is taken deliberately rather than by default.

**No adjudication implication. Not to be routed anywhere.**

---

## X-003 · An extraction finding proposed a merge of three open adjudication items

**SOURCE** — `S1-F013` Finding 2: *"Direct bearing on unruled workbook items — `W:C-15` (retraction: state/event?), `W:C-14` (`NOT_ASSESSED` representation), `W:C-7` (`CONFLICTED` ↔ `ConflictRecord` cardinality). **All three are instances of this one classification problem.** ⚠ Noted, not adjudicated."*

**OBSERVATION** — the "noted, not adjudicated" marker is honoured in letter: no ruling is proposed. But *"all three are instances of one problem"* is a **structural claim about the adjudication set**, and structural claims have consequences a ruling would have: if three items reduce to one, then ruling one rules all three, and their independence — the reason they are three rows — is retired.

**WHY IT MATTERS** — this is the reverse of `X-002`. There the risk was adjudication conclusions leaking into research. Here it is a research artifact reshaping the adjudication set's **topology** while correctly declining to rule on its contents. Both are the same porosity, in opposite directions.

⚠ The observation itself may well be right, and it is the batch's most valuable single insight (see `S2-F019`, which endorses it on its own reasoning). The issue is not its quality but its **route**: a merge of three open items should arrive as a proposal to whoever holds them, not as a sentence inside a finding about something else.

**TRACKS INVOLVED** — research/extraction → adjudication (topology, not content).

**STATUS** — recorded. Not routed by me. `W:C-7`, `W:C-14` and `W:C-15` remain three separate open items in every register I can see, and I have not merged them.

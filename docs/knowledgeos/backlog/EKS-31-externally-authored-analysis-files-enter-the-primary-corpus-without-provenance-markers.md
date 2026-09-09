# EKS-31 — A file that consumes and restates this reconstruction's own findings was saved directly
into the primary research corpus, with no filename or provenance marker distinguishing it from
original brainstorming material

**Status:** **BACKLOG · DATA-HYGIENE / PROVENANCE-INTEGRITY EXPOSURE** — registered from the three-
model-convergence reconstruction's own MD-059
(`docs/knowledgeos/brainstorming/three_model_convergence/14_decision-log/MD-059-f4-semantic-
instantiation/`). ⛔ **Not commissioned; activation requires a Human/PO-ARB authorization act.**
**Class:** operating-model problem — corpus provenance integrity.
**Registered by:** Three-model-convergence reconstruction, 2026-09-09.

> ### ⛔ **This item records a problem and a candidate requirement. It does not judge the content of
> the file in question, does not propose deleting or moving it, and commissions no remedy.**

---

## 1 · The problem in plain terms

During MD-059, the user supplied a file to read, located at
`docs/knowledgeos/brainstorming/mathematical_ideas_that_can_be_implemented/I conducted the targeted
evidence-extrac` — a file whose **name is a truncated sentence fragment** (not a dated, titled
research filename in the convention every other file in this directory follows), whose **content
explicitly cites and analyzes this reconstruction's own MD-058 output** (quoting its instantiation
matrix, its R1a finding, its GA-001/GA-038 framing), and whose **filesystem timestamp is today**
(created during this very session, per direct `ls -la` confirmation).

**This means the file cannot be prior, independent brainstorming corpus material** — it is a same-day
analysis that consumes this reconstruction's own already-published finding as its own input. That is
a legitimate and useful thing to produce and share (this reconstruction used it as a cross-check in
MD-059 with correct provenance labeling) — **but it now sits, permanently, inside the exact directory
tree** (`mathematical_ideas_that_can_be_implemented/`) that this reconstruction's own sequential
corpus pass (`00_control/resume_mathematical.py`) treats as primary evidence, with **nothing in its
filename, header, or location marking it as external, derivative, or dated after the reconstruction's
own governed output** that it discusses.

## 2 · Why this is a business problem, not a one-time curiosity

**a. The next reader — human or AI — has no cheap way to tell this file apart from genuine 2026-09-02
primary research.** Every other file in this directory carries a `YYYYMMDD-HHMMSS_topic.md`-style
name that the corpus's own reading protocol relies on for chronological-thread discovery (the
"follow the timestamp sequence" methodology this reconstruction has used in every phase). This file
has no such name and no date — a future sweep applying the *exact same* keyword/timestamp discovery
method this reconstruction has used repeatedly (MD-047/052/054/056/057) would either skip it (no
timestamp to sort by) or, worse, **mistake its content for a genuine, independent primary-source
finding**, since nothing in the file itself, once opened, announces "this was written after and using
MD-058."

**b. The specific risk is exactly the one this reconstruction's own provenance discipline exists to
prevent.** This session has spent multiple phases (MD-054/056/057) correcting exactly this failure
mode — treating a derivative or common-provenance document as if it were independent corroborating
evidence. A file that *looks* like corpus material but is actually a response to this reconstruction's
own work is the single easiest way for that same mistake to recur automatically, without anyone
intending it, the next time a session searches this directory cold.

**c. This is not about who wrote the file, or whether its content is good.** MD-059 checked the file's
substantive claims directly (e.g. spot-verified its seq-0481 citation) and found it careful and
useful. **The problem is purely structural**: a corpus directory whose entire discipline (dated
filenames, sequential-pass registers, timestamp-thread reading) depends on every file being what its
name and location claim it to be now contains at least one file that is not.

## 3 · What is *not* the problem

⛔ **Not a claim that the file's content is wrong, low-quality, or unwelcome.** MD-059 used it
constructively.

⛔ **Not a call to delete, move, or rename the file unilaterally.** Whether and how to handle it is a
governance/user decision, not something this reconstruction should do on its own initiative mid-phase.

⛔ **Not a claim that the user did anything improper by sharing it this way.** The file's origin (a
tool run outside this session, saved into the working tree) is a normal way to bring outside analysis
in — the gap is that the corpus has no *convention* for marking material that arrives this way.

## 4 · Candidate requirement (a direction, not a design)

> **When a file enters a directory this programme's own sequential-pass tooling treats as primary
> evidence, and that file was authored using this reconstruction's own governed output as an input
> (rather than being prior, independent research), it should carry an explicit marker — in its
> filename, a header, or a dedicated `external/`-style location — before or as soon as it is
> discovered, so no future pass has to re-derive its provenance from content alone.**

The cheapest version costs one check per newly-noticed file: does its content reference this
reconstruction's own governed decision-log output? If so, flag it, rather than let it sit
indistinguishable from primary material.

## 5 · Relationship to existing items (`ES-005.4` — never a copy)

| item | overlap | why this is separate |
|---|---|---|
| `EKS-24` — a discussion's conclusion buried in a short, differently-named later file | both concern a file whose importance a searcher can't tell from its name | ⛔ `EKS-24` is about **a genuine corpus file's own conclusion being hard to find**; this is about **a file that should not be read as a corpus file (a primary source) at all**, regardless of how easy it is to find |
| `EKS-22` — a large theory rewrite with no status/owner recorded | both concern missing provenance/status metadata | ⛔ `EKS-22` is about **an internally-produced body of work missing a status tag**; this is about **an externally/tool-produced file with no marker that it is external at all** |
| `EKS-19` — no registry of already-spoken-for directories | both concern directory-level discovery risk | ⛔ `EKS-19` is about **overlapping authorization scope between sessions**; this is about **a single file's own misleading provenance signal**, unrelated to authorization scope |

⭐ **Checked and distinct on all three counts.**

## 6 · Urgency

**Low for this specific file** (its provenance is now recorded here and in MD-059's own text) — **but
the underlying gap is structural and will recur** the next time any outside analysis is saved into a
primary corpus directory without a marking convention.

## 7 · Evidence

* `docs/knowledgeos/brainstorming/mathematical_ideas_that_can_be_implemented/I conducted the targeted
  evidence-extrac` — the file itself (name, content, and `ls -la` timestamp confirmed 2026-09-09).
* `14_decision-log/MD-059-f4-semantic-instantiation/00_index.md` and `07_final-judgment.md` — this
  finding's own full characterization and provenance labeling of the file as a cross-check input.

## 8 · Second occurrence, 2026-09-09 (same day, later)

A second file of the identical shape was found:
`mathematical_ideas_that_can_be_implemented/# F4 Handover Audit — post-MD-059.md` (created 15:32,
39 120 bytes, its own content duplicated verbatim twice within itself — a further, distinct data-
hygiene defect on top of the provenance one this ticket tracks). Same profile as the first instance:
no dated-filename convention, content explicitly built on this reconstruction's own MD-058/059
output, saved directly into the primary corpus directory. **Recorded here as occurrence 2**, per this
reconstruction's own "record recurrence, don't refile" discipline (`EKS-16`'s own precedent) — full
characterization in `14_decision-log/MD-060-f4-kt-variant-reconstruction-and-adjudication/
00_index.md` §6. The candidate requirement (§4) is unchanged by this recurrence; it is simply
stronger evidence that the gap is structural and will keep recurring absent a marking convention.

---

⛔ **Registered under the operating model's own standing rule: when a deeper requirement is discovered,
record it as a follow-up and STOP. No remedy is designed and no work is commissioned.**

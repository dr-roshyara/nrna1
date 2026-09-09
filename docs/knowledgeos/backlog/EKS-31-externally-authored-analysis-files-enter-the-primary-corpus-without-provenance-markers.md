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

## 9 · Full inventory, 2026-09-09 — the scale is much larger than occurrences 1–2 showed

A direct filesystem check (`ls -la` + `md5sum`, this reconstruction's own MD-062 follow-up) found
**eleven** same-day, non-standard-named files in this one directory, spanning the entire session
(13:54–16:47), none present in the git-tracked 401-file `mathematical-manifest.tsv`:

| Time | Size | File |
|---|---:|---|
| 13:54 | 29 222 B | `I conducted the targeted evidence-extrac` (occurrence 1, §1) |
| 14:19 | 27 030 B | `# Historical Evidence-Recovery Report.md` |
| 14:20 | 31 751 B | `I treated the uploaded file as the gover` |
| 15:32 | 39 120 B | `# F4 Handover Audit — post-MD-059.md` (occurrence 2, §8) |
| 15:37 | 19 832 B | `handover_verdict` |
| 15:44 | 26 485 B | `historical_source.md` |
| 15:46 | 20 916 B | `document1.md` |
| 15:46 | 19 955 B | `document2.md` |
| 15:47 | 22 466 B | `document3.md` |
| 16:47 | 19 337 B | `Untitled-17.md` |
| 16:47 | 19 337 B | `document4.md` — **byte-identical to `Untitled-17.md`** (same md5) |

**This is no longer a two-occurrence pattern — it is a whole cluster**, roughly one new file every
10–20 minutes across the session, two of them exact duplicates of each other under different names.
One (`historical_source.md`) explicitly self-describes as *"a historical-source audit"* while itself
being same-day, non-corpus material — the same masking risk §2(a) already named, now demonstrated
directly: a document *about* historical material can itself be mistaken for historical material.

**One substantive claim from this cluster was independently verified** (this reconstruction's own
MD-062 follow-up, not this ticket's own job to re-litigate): a cited requirement structure,
`r=(id,type,scope,content,standard,priority,validity)`, genuinely exists in a real, git-tracked
primary source (`M0047`, `20260902-082333_...formal-theory-of-epistemic-gaps.md`) — confirming that
at least some of this cluster's own claims are checkable and correct, which is exactly why the
provenance-marking gap matters: **good analysis and bad analysis are equally invisible as "not
corpus" from the filename alone.**

**Urgency raised from "low for this specific file" (§6) to moderate-and-growing** — the candidate
requirement (§4) is unchanged, but eleven same-day instances in one directory is no longer a rare
edge case for this reconstruction's own working method.

## 10 · A sharper defect than §9 itself found: filenames in this cluster do not identify stable
content — they are re-used save targets for successive outputs of an external research session

**Mechanism, confirmed directly by the user**: the user runs a separate research session (an external
tool/conversation) and saves its output into this directory as it produces successive answers,
reusing the same filename each time rather than saving each answer under a new one. This explains,
precisely, what was otherwise only observed as an unexplained content change.

`document4.md` and `Untitled-17.md` were read three separate times over roughly two hours of one
session. Each read returned **genuinely different content** at the same path — each one, per the
mechanism above, a different successive output of the same external research session:

| Read | Time (approx.) | Size | md5 | Content |
|---|---|---:|---|---|
| 1st (`Untitled-17.md`) | ~16:47 | 19 337 B | `e8aa7153...` | a numbered 12-question F4/`Sat*` audit |
| 2nd (`document4.md`, same content) | ~16:47 | 19 337 B | `e8aa7153...` | byte-identical to the 1st — recorded as duplication in `§9` |
| 3rd (`document4.md`, re-read on request) | 16:49 | 16 260 B | `56cf6e7c...` | a **different** 13-question audit, reorganized into an explicit L0/L1/L2 corpus-native/derived/constructed layering |

**This is a materially sharper version of the risk `§1`–`§9` already describe.** The earlier framing
assumed the defect was *"a file's name and location don't announce that its content is external."*
This shows the defect goes one level deeper: **in this specific cluster, even a fixed filename,
re-opened minutes apart, does not guarantee the same content twice.** A future reader (or this
reconstruction itself, in a later phase) citing `document4.md` "as read on 2026-09-09" would have no
way to know *which* of at least three distinct versions is meant, since the file carries no version
marker, no timestamp-of-content, and no diff trail — only the filesystem's own single, overwritten
`mtime`.

**Consequence for the candidate requirement (`§4`)**: a filename/header marker alone (the original
proposal) is not sufficient for files in active, repeated-overwrite use — the marker would need to
either (a) be paired with a stable, non-overwriting save convention (new filename per version, as the
rest of the corpus already does by timestamp), or (b) itself carry a content version/hash, not just an
"external" flag. **Not designed here** — recorded as a sharper instance of the same underlying gap,
per this ticket's own standing rule not to propose remedies.

---

⛔ **Registered under the operating model's own standing rule: when a deeper requirement is discovered,
record it as a follow-up and STOP. No remedy is designed and no work is commissioned.**

---

## Appended 2026-09-09 by Lane T — a second, sharper face of the same cause

**Registered from `104`** (`docs/knowledgeos/theory-extraction/104-READ-RECORD-NINE-F4-FILES-…`).
⛔ **This appendix adds evidence to an existing item. It opens no new item and proposes no marker
format.**

### What was observed

**Nine analysis files were saved into the primary corpus directory in a single afternoon.** ⭐⭐⭐ **Four
of them — saved at 15:44, 15:46, 15:46 and 15:47 — are four separate runs of the same commission.**
Three share a near-identical title. **All four reach the same verdict.** ⛔ **None cites any of the
others, and none is marked as a repeat run.**

### Why this is the same problem, seen from a new side

This item was registered because externally produced analysis entered the corpus with nothing recorded
about **where it came from**. ⭐⭐ **Today shows the same gap producing a different and arguably worse
effect: nothing records that four documents are *the same work done four times*.**

$$\boxed{\textbf{A reader six months from now will find } \mathbf{four\ agreeing\ documents} \textbf{ and reasonably count } \mathbf{four\ confirmations.}}$$

⭐ **There is one.**

### Why it matters in business terms

**a. It inflates confidence in a conclusion nobody re-tested.** Four documents agreeing is normally
strong evidence. ⛔ **Here it is expected by construction** — same question, same corpus, same
afternoon — **and therefore carries almost no additional weight.**

**b. The estate already knows this and applies the test elsewhere.** ⭐⭐ One of these very files rules
another same-day document *"external, same-day, consuming the earlier work, **non-independent**"* and
excludes it from corroboration. ⭐⭐⭐ **That is exactly the right discipline — and the four repeat runs
are not held to it.**

**c. The disagreements are the part that gets lost.** ⚠️ The four runs **agree on the verdict but order
their three blockers differently** — one of them demotes the blocker the others call decisive.
⭐⭐ **That ordering is what selects the next piece of work**, so the divergence matters more than the
agreement — and with no marker saying these are parallel runs, ⛔ **a reader has no reason to compare
them at all.**

**d. It compounds with the storage problem already recorded here.** ⭐ Names like `document1.md`,
`document2.md`, `document3.md` carry no timestamp, no author, no commission reference and no run
number. **On a directory listing they look like three unrelated papers.**

### Candidate requirement — an extension of §5, not a replacement

> ⭐ **A marker should record not only where an analysis came from, but whether it is a repeat run of a
> question already answered — and if so, which run, of which commission.**

⭐⭐ **The decisive property: repeated runs must be countable as one.** A provenance marker that
identifies the author but not the commission still lets four replicates read as four confirmations.

### Evidence

| | |
|---|---|
| the four runs | `historical_source.md` (15:44) · `document1.md` (15:46) · `document2.md` (15:46) · `document3.md` (15:47) |
| all four reach the same verdict; blocker ordering differs | `104-…` §1 |
| the estate's own non-independence test, applied to other material | the 15:37 handover file, §4.4 |
| the earlier three files of the same afternoon | 13:54 · 14:19 · 14:20 |

⛔ **No claim is made about the correctness of any of these documents.** ⭐ **Their content is not in
question; only the absence of a marker distinguishing repetition from replication.**

### Further instance, same day — a copy filed under a *different* title

**Registered from `105`** (`docs/knowledgeos/theory-extraction/105-READ-RECORD-2026-09-02-…`).

A byte-level check of one earlier day's 159 files in the same directory found **13 exact-duplicate
pairs — 15 446 lines, about a tenth of that day's material.** ⭐ **Eleven are honest repeats**, one of
them even named *"second copy"*. ⛔ **Two are not.**

$$\boxed{\begin{array}{c}\textbf{A file named for a } \mathbf{boundary\ separation\ EXPERIMENT} \textbf{ contains, byte for byte,}\\ \textbf{a different document about a lens/predicate distinction.}\end{array}}$$

⭐⭐⭐ **This is worse than an unmarked repeat.** An unmarked repeat inflates a count. **A copy filed
under a different title creates a work that does not exist:** someone auditing what experiments were
run will list that experiment, find a document where it should be, and have no way to notice the
document is about something else. ⚠️ A second pair does the same to a review.

⭐ **It strengthens the requirement already stated above rather than adding a new one:** *repeated runs
must be countable as one* — ⭐⭐ **and countability fails hardest when the copy is renamed**, because
neither the filename, the length, nor a keyword search reveals it. **Only a content hash does.**

⛔ **No remedy is proposed, and no claim is made about either document's correctness.**

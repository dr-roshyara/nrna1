# EKS-42 — Two different documents number their sections identically, so every citation to them is ambiguous

## Problem, in business language

Two separate research documents in the KnowledgeOS corpus both label their internal sections
`272A.1`, `272A.2`, … through `272A.29`. The numbering ranges do not merely overlap at the edges —
they are **the same range, end to end**. The two documents were written eight minutes apart and
cover entirely different subject matter: one derives the set of operations the system must support,
the other derives the minimum set of epistemic states.

The consequence is simple and serious: **a citation of the form "see 272A.17" does not identify a
place in the corpus.** It identifies two places, with different content, and the reader has no way
to tell which was meant.

## Concretely, what a reader hits

| Section label | In the 22:42 document | In the 22:50 document |
|---|---|---|
| `272A.16` | Operation Reduction | Relation to probability |
| `272A.17` | **Mandatory Distinction Register** (the 19-row requirements table) | Epistemic state versus lifecycle state |
| `272A.20` | Dependency Structure | Algebraic interpretation |
| `272A.25` | **First Formal Result** (the 19-operation candidate set) | **Minimality conclusion** (the four-state result) |

The two sections most often cited downstream — `272A.17` and `272A.25` — are precisely the two where
both documents hold a load-bearing, quotable result. A reader who resolves the citation to the wrong
document gets a confident, well-formed, entirely unrelated answer.

## Why this is a business problem, not a formatting nitpick

- **It has already produced an under-specified citation in this programme's own output.** The prior
  audit artifact `P-95` cites `272A.17` and `272A.25` with no file named. The content it quoted is
  from the 22:42 document and is correct — but the citation as written cannot be checked by anyone
  else without re-deriving which file was meant. Work that cannot be checked cannot be relied on.
- **It defeats the corpus's own reading discipline.** The programme's standing method is
  "search hit → identify timestamp → follow the thread." That method assumes a hit identifies a
  document. Here it does not.
- **It is invisible to the reader who finds only one of the two.** Nothing in either document says
  another document shares its numbering. There is no cross-reference in either direction.
- **The risk is asymmetric.** A missing citation announces itself. A citation that resolves to the
  wrong place does not.

## What is *not* the problem

- The section content is not wrong. Both documents are internally coherent and both were accepted by
  their supervisory reviews.
- This is not a duplicate-file problem. The two documents have different filenames, different
  timestamps, and genuinely different content — they are two real, distinct pieces of work.
- It is not caused by the timestamp/argument-order mismatch tracked in `EKS-35`, though it sits
  beside it: `EKS-35` is about *ordering*, this is about *addressing*.

## How it arose (recorded, not blamed)

The second document opens by declaring itself *"the missing part of Step 272A"* and *"the formal
completion of Step 272A, not a replacement."* Numbering its sections `272A.n` was a deliberate act
expressing that intent. The intent was reasonable; the addressing consequence was not considered.

## Candidate requirement

Whatever numbering convention the corpus adopts must guarantee that **a section reference identifies
exactly one location.** Options for governance to weigh, in rough order of cost:

1. Cite by **file plus section** everywhere, and state that bare `272A.n` is not a valid citation.
   Cheapest; changes no existing document.
2. Give the second document a distinct prefix (for example `272A′.n` or `272B.n`) and record the
   renumbering, so old citations to the 22:42 document stay valid.
3. Require every document that continues or completes another to open with an explicit
   "shares/continues the numbering of …" line, so the hazard is at least visible where it occurs.

**Recommendation: option 1 plus option 3.** Option 2 rewrites accepted documents, which the corpus's
own discipline discourages; options 1 and 3 add information without altering any decision text.

## Relationship to existing items (ES-005.4 — consume or extend, never create a second)

Checked against the existing register before writing:

- **`EKS-35`** (file timestamps do not encode argument order) — related but distinct. That ticket is
  about *when* a document sits in a sequence; this is about *whether a reference reaches it at all*.
  A fix for one does not fix the other.
- **`EKS-38`** (two decision registers share one ID space) and **`EKS-41`** (one symbol, unrelated
  objects) — the same *family* of defect (one identifier, several referents) at the register and
  symbol levels. This is the same failure one level down, at the **section** level. It is recorded
  separately because the remedy is different: symbol collisions need a glyph register, register
  collisions need namespacing, and this one needs a citation convention.
- No existing item covers section-level addressing. This does not duplicate or extend one.

## Urgency

**Medium, and rising with reuse.** Nothing is currently wrong in the corpus because of it. But the
19-row Mandatory Distinction Register at `272A.17` (22:42 document) has been identified as the
requirements basis that the later `ℛ_req` work descends from, so it is about to be cited repeatedly.
Every such citation made before the convention is fixed is a citation that will have to be
re-resolved by hand later.

## Evidence

- `docs/knowledgeos/brainstorming/phase_measure_theory/20260830-224242_step_272a_core-operation-universe-derivation.md`
  — sections `272A.1 … 272A.29`.
- `docs/knowledgeos/brainstorming/phase_measure_theory/20260830-225058_step_272b_minimum-epistemic-status-structure-derivation.md`
  — sections `272A.1 … 272A.30`, same range.
- Heading extraction confirming complete overlap, and the four-row comparison table above:
  `docs/knowledgeos/theory-extraction/117-P96-QUEUE-DRIVEN-CHRONOLOGICAL-READ.md` §8.2.
- The under-specified citation this defect has already produced:
  `docs/knowledgeos/theory-extraction/116-P95-COMPLETED-CHRONOLOGICAL-READ-270-TO-272A.md`.

## Status

`OPEN` — raised by the P-96 queue-driven chronological read, 2026-09-09. Advisory only: this ticket
records a problem and recommends a convention; it decides nothing and changes no document.

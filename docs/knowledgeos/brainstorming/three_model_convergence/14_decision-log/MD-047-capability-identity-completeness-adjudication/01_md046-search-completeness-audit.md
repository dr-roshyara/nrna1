# MD-047 §1 — MD-046 Search-Completeness Audit (Phase A)

## Exact reconstruction of MD-046's search frame

- **Directories searched**: `docs/knowledgeos/brainstorming/kernel/` (172 files + 17 nested),
  `docs/knowledgeos/reviews/kernel/` (112 files, session1/session2, admitted narrow scope via
  `MD-043-DQ-1`), `docs/knowledgeos/brainstorming/mathematical_ideas_that_can_be_implemented/` (402
  files, excluding a deep re-read of the already-known 10-file MinKer chain), `nrna1/verification/
  zero-algebra/`, `nrna1/research/kernel-reduction/`.
- **Directories excluded**: `theory-extraction/` (absolute), `reviews/synthesis/`,
  `brainstorming/verification/` (whole tree), `brainstorming/synthesis/`'s 3 extraction-named files,
  `reviews/exec/`, `research/knowledgeos-sim/` — all per prior, already-recorded governance
  decisions (MD-042/043/043-DQ-2).
- **Search concepts**: 17 named concept terms (capability identity, semantic capability, capability
  equivalence, etc.) plus the 13 literal MinKer capability names, searched "only within kernel/
  capability-relevant contexts."
- **Census vs. sample**: census in intent (grep across full file sets), not a statistical sample.
- **Manual inspection**: the fork read and quoted specific files for genuine hits; incidental/generic
  hits were filtered by the fork's own judgment, not manually re-verified by this session before
  MD-046 was written.
- **Positive control**: **none was explicitly run in MD-046 itself** — this is the one real gap this
  audit identifies in MD-046's own method (not its conclusion, see below).
- **Negative control**: implicit only — the fork's own report of substantive hits (`S1-F037`,
  `S1-F016`, `S1-F008`, `S2-F007`) in the same directories demonstrates the search paths were live and
  the tooling worked, which is evidence against a total EKS-21-style path failure, but is not the same
  as a term-by-term positive control on the specific 13-capability-name claim.

## The positive-control check this phase performed

Direct, independent re-run (not delegated) of the "zero hits for the 13 MinKer capability names in
kernel-capability context" claim, using `grep -rl "\b<name>\b"` across `brainstorming/kernel/` and
`reviews/kernel/` for all 13 names, unfiltered:

`Observe`: 7 files · `Interpret`: 6 · `Represent`: 3 · `Relate`: 0 · `Discriminate`: 0 · `Qualify`: 0
· `Hypothesize`: 0 · `DetectGap`: 0 · `Challenge`: 26 · `Validate`: 8 · `Revise`: 2 · `Determine`: 22
· `Select`: 7.

**This is not, by itself, a contradiction of MD-046** — MD-046's own claim was scoped to
"kernel-capability context," not literal string presence. The unfiltered counts include ordinary
English usage (e.g. "determine whether," "to validate"). Manual inspection of the highest-count
hits (`Determine`, `Challenge`, `Validate`, `Select`) confirmed:

- **`Determine`** (22 files): every hit inspected is either an ordinary instructional verb ("Determine
  whether X is an Entity or a Value Object") or one step inside a named, different process ("Receive
  → Apply → Check → **Determine** admissibility → Produce → Record," a 6-step "Adjudication" workflow
  description) — not a standalone capability-list item comparable to MinKer's own bare "Determine."
- **`Validate`** (8 files): identical pattern — generic instructional/process-step usage ("Validate
  observation," "Validate syntax," "Validate the architectural shifts with the Constitution").
- **`Select`** (7 files): generic usage ("Select variant based on," "Select ravens only from cages
  containing black birds" — a probability-textbook example, unrelated to Kernel capabilities).
- **`Challenge`** (26 files, the single largest count): here the check found a genuine, material item
  — see below.

## The material correction: "Challenge" as a shared surface word, confirmed a homonym

**`brainstorming/kernel/20260823-113410-perplexity-smallest-consistency-boundary-investigation.md`**
lists "Challenge" as a candidate domain concept **directly alongside** `Candidate, Knowledge, Evidence,
Justification, EpistemicState, Confidence, Identity, History, Supersession, Reconciliation,
Withdrawal, Rejection` — the identical item set (`Identity, Evidence, Justification, EpistemicState,
Confidence, History`) already found in `reviews/kernel/session1/S1-F016`'s own aggregate-member list
(MD-046 `01_...md`). **This is a genuine, previously-unfound point of lexical contact between the two
vocabularies MD-046 characterized as having "zero name-level overlap."**

A follow-up check (`grep "Challenge("`, and a search for "capability...challenge" pairings) confirmed
**"Challenge" is never discussed as a verb/capability anywhere in either directory** — every instance
treats it as a **noun**: a domain object/event ("Is Challenge a Domain Event, or is it an Entity...",
"Knowledge → Challenge → Superseded/Reconciled/Withdrawn/Rejected"). MinKer's own "Challenge" is
implicitly a capability (a verb-like action the Kernel performs). **These are grammatically distinct
roles for the same surface word — a homonym, not an established identity, and the source material
itself never claims otherwise.**

## What this means for MD-046

**MD-046's core conclusion is unaffected and, if anything, reinforced.** Its literal "zero name-level
overlap" phrasing is corrected here to "one shared surface word, confirmed to occupy different
grammatical/ontological roles in each vocabulary, not established as the same semantic capability."
This is exactly the surface-similarity-vs-semantic-identity trap this whole MD-045–047 arc exists to
guard against — finding one instance of it, and confirming it as a homonym rather than genuine
overlap, is evidence *for* the difficulty of the underlying problem, not evidence of a criterion.
MD-046's own text is not modified; this correction stands here.

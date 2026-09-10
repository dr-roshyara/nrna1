# MD-093 §03 — Negative-History Register, Self-Correction Ledger, Cross-Lane Transfer, Corpus Hygiene

## Negative-History Register (explicit rejections/falsifications, this 39-file burst)

| Claim | Disposition | Where |
|---|---|---|
| Six-part `KnowledgeAggregate` invariant (Identity+Evidence+Justification+EpistemicState+Confidence+History) | **FALSIFIED** via pairwise atomicity testing | File 11 |
| Three-part replacement (Identity+EvidenceReferences+JustificationHistory) | Demoted to HYPOTHESIS — reviewer flags it as "recreating the exact error just eliminated" | File 11 |
| "God Aggregate" (any single object owning every semantic relation/evidence item/interpretation/reasoning path/decision) | Explicitly and repeatedly REJECTED | Files 2, 6, 13, 38/39 |
| "Kernel = pure function" | HYPOTHESIS only, never established | File 2 |
| DeepSeek's original candidate bounded-context decomposition | REJECTED as premature, relabeled "candidate contexts" | File 2 |
| Confidence classified as CORE Kernel responsibility | REOPENED, reclassified SUPPORTING, derivation UNKNOWN | File 15/16 |
| "Contradiction detection = CORE" (blanket) | REJECTED — decomposed into 4 distinct contradiction types requiring separate ownership | File 15/16 |
| Cross-lens agreement as a kernel-promotion criterion | REJECTED explicitly twice, independently | File 12, closure dossier |
| Principle of Charity, radical interpretation, truth-condition semantics as Kernel mechanisms | Explicitly REJECTED (Davidson-derived) | Files 27, 28 |
| Knowledge/action inseparability implies boundary coupling (Kimi, via Yangming *zhi-xing*) | REJECTED — "philosophical coupling, but not necessarily a boundary coupling" | File 19 |
| Huayan boundary dissolution ("no smallest unit exists") | Reframed, not accepted or flatly rejected — recast as strengthening minimality | File 19 |
| DecisionPower as a persisted primitive | Self-corrected to a derived concept | File 30 |
| "Knowledge is always propositional" (DeepSeek's Tractatus reading) | Explicitly REJECTED by the reviewing document | File 36 |
| "Logical form cannot in principle be represented" (Tractatus, over-literal reading) | Explicitly REJECTED | File 36 |
| `Problem` and `Pattern` as kernel primitives | Both explicitly demoted, relocated one level up | File 39 |
| Abductive reasoning as a `ReasoningMethod` | Introduced and immediately flagged unsupported by source in the same breath | File 39 |
| MECE as "a universal truth rule" | Demoted to `DecompositionQuality`/`CoverageAssessment` | File 39 |

## Self-Correction Ledger (representative; ~34 total instances documented across the burst)

The single cleanest, most fully narrated chain in the burst: **File 36** (review of a Tractatus
extraction, File 33) corrects its own predecessor document three separate times, each with an exact
prior-claim → correction → reason → surviving-claim structure:
1. "Kernel's fundamental unit should be the proposition, not the entity" → rejected as "too strong" →
   replaced with "an entity is not, by itself, an asserted fact" (Entity/Claim/Relation as co-equal
   primitives).
2. Literal implementation of the saying/showing distinction as KnowledgeOS entities → rejected →
   replaced with "some system properties should be enforced structurally rather than represented as
   ordinary knowledge claims."
3. "Logical form cannot in principle be represented" as an architectural conclusion → rejected →
   replaced with "do not confuse the representation's schema with the reality it represents."

A second fully narrated chain: **File 39**'s own `EpistemicStatus` proposal (an 8-state enum) is
immediately self-qualified in the same file — "status has multiple dimensions" — and kept as "a domain
concept under investigation," enum not frozen.

A third: **File 39**'s own Decision treatment is a clean, four-stage, explicitly narrated refinement
(never a contradiction) — §23 draws a strict `Knowledge≠Decision` boundary → §24 immediately qualifies
it ("however, Decision needs a knowledge basis") → the file's own later sections operationalize this
into a separate "Decision Context" bounded context with its own `DecisionBasis` object and an explicit
`Claim→Decision` transition rule requiring "Decision policy/authority."

**Corpus-level pattern**: every file across this burst that proposes a Kernel enumeration or diagram
revises it at least once within the same file. In the majority of cases (Audi's grounding-triad
collapsing to a pair; Shieber's two non-identical Kernel diagrams forty sections apart) the revision is
**silent/unflagged** — drift the reader must notice, not a correction the text announces. Only a
minority of revisions (the three examples above) are explicitly self-narrated.

## Cross-Lane Transfer Register

**Inbound**: none found — this is the earliest material surveyed to date; nothing in this burst cites
anything later in this reconstruction's own chronology (impossible, by construction).

**Outbound / forward echoes, not yet confirmed as transfers**:
- The `Question`/`Inquiry` primitive family (File 39) structurally anticipates the post-T22
  fact-finding architecture MD-089's own Thread 2 independently rebuilt eleven days later — no
  citation found in either direction across two separate extraction passes; recorded `IDENTITY
  UNRESOLVED — INSUFFICIENT EVIDENCE`.
- The "Ming (正名)" vocabulary-collision test and the closure dossier's three-tier evidence discipline
  (`SOURCE FACT`/`LENS OBSERVATION`/`ARCHITECTURAL HYPOTHESIS`) are methodological — not theory-object
  — ancestors of this reconstruction's own governing discipline (homonym classification;
  evidence-status vocabulary). Recorded as observed continuity of method across the whole reconstruction
  programme, not a citation-based transfer.

**Dangling external dependencies, confirmed not resolvable within this batch**: `⟨C-1⟩`, `C-3`, `C-4`,
`C-7`, `C-8`, `C-11`, `C-15`, `C-17`, `C-18`, `F-CM-1`, `F-CM-2`, `DEF-1` — cited throughout as
already-established canonical items, never defined in any of these 39 files. Point to material
elsewhere in `kernel/`'s own remaining 133 files or possibly `phase_measure_theory/` — named, not
chased down this phase.

**Firewall discipline maintained**: `theory-extraction/` never accessed by any of the five extraction
agents or by this adjudication.

## Corpus-hygiene findings

- File 3 (`f1-f5-closes-domain-discovery-gap-duplicate.md`) confirmed an exact duplicate of File 2's
  own second half.
- Files 30/31 (`managing-memory-for-ai-agents...` and its `-duplicate` filename) confirmed byte-
  identical (MD5 match) — a case where the "duplicate" filename tag is accurate, unlike several
  previously-documented `EKS-31` instances where "duplicate"-tagged files actually diverged.
- Files 38/39 (the closure dossier's own two near-simultaneous saves) confirmed byte-identical (MD5
  match), 13 minutes apart — a further accurate "duplicate" instance.
- File 19 (`research-commission-chinese-philosophical-lenses...`) is **not** what its filename implies
  — it silently contains a full third AI report (see §01) never flagged in the filename or the
  document's own stated scope. This is a **new class** of corpus-hygiene finding for this
  reconstruction: a filename/scope mismatch by omission, distinct from both the `EKS-31` duplicate-
  content pattern and Part 20's within-file self-duplication (MD-090).

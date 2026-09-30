# C14 — Human governance and change control · v1.1

**Treatment:** F-SPECIFIC log, same discipline as the S lane (P3B §17, §26; v3.5 R20).

## 1. Authority
Every decision that changes population, rules, gates, schemas, contracts, cadence, parallelism or scope is a **human**
decision, recorded in `F-GOVERNANCE-LOG.md` (append-only) with the instruction quoted. The AI proposes, implements an
approved decision, supplies evidence — it never accepts its own work.

## 2. Versioning
A change is a new versioned file in `prompts/` (protocol, runbook, or `contracts-vX.Y/`), listing what changed and the
human source. Frozen files are never edited; a correction is a new version or a log entry. Code implementing a version
is committed with it, and its tests pass before the freeze.

## 3. S boundary
S artifacts are read-only. An F finding that bears on S methodology is an `F-OBSERVATION-ON-INHERITED-METHOD` entry,
routed to a future S review; nothing in the S tree is changed.

## 4. What F-Series may and may not conclude
May: "these are the research discoveries supported by the F-corpus, these are the hypotheses they generate, these are
the tests performed, and this is the remaining uncertainty." May not: "this is the KnowledgeOS theory." Theory
synthesis is a separate, later stage, commissioned by the human. Theory adoption is never automatic.

## 5. Cadence gates
The human decides: continuation after F3082 · any exception to list order · parallel reading · checkpoint interval ·
admission of new statistical/ML methods · any re-pinning of reused S infrastructure.

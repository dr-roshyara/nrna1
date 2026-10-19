# Rule RK-2: the core loop

| Field | Value |
|---|---|
| Status | **APPROVED**, recorded on the Principal's instruction, 2026-10-19: "read the corpus file, use your ai intelligence, find the theory concept/idea , register /update into the book . mark it is read. Actually that is the main core work." |
| Applies | from S0026 |
| Builds on | RK-1 (one home per fact; at most two sources per turn) · RV-1 (reviewer mandate) |

## The core loop, per source

1. **Read** the whole source (allowed-list check first; sealed files never).
2. **Find the theory:** the concepts, claims, arguments, mathematical content and contradictions that bear on the theory of KnowledgeOS.
3. **Write it into the book:** add or revise definitions, claims, derivations and open questions. Change the book **only when the theory changes**. Repeats and plain supporting evidence stay in the source record.
4. **Mark it read:** one script run (`tmp/tools/close_source.py`) does the read-log row, the fingerprint, the quote check and the tag check.

## What is reduced

- **The source record is working notes:** the anchored quotes the book relies on, the theory found, contradictions, and one line per idea saying what happened to it.
- **The concept register holds only the theory's building blocks:** a concept is registered only when the book uses it as a definition, premise or result. Source vocabulary stays in the record.
- **Bookkeeping is scripted.** Before and after hashes are taken by the script at batch checkpoints.
- **After each five-source review**, the affected sections are consolidated into clean theory text (definitions → claims → derivations → open questions), with provenance in the reference tables.

## Unchanged

Reading in full · quotes checked · claims no stronger than their sources · a real counterexample attempt before VALIDATED · the five-source independent review (RV-1) · the Principal's reserved decisions.

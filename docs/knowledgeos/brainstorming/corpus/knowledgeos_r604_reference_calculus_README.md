# KnowledgeOS R602.4 — Reference Calculus

This is a deterministic reference implementation of the currently frozen subset of the KnowledgeOS operation algebra.

It implements:
- State K=(X,H)
- OperationClass × MutationPolicy admissibility
- Pure-state immutability (I-X02 scoped to X)
- typed CompatibilityWitness with partial conversion
- finite TPP checking with counterexample generation
- target-specific preservation checking
- distinction of projection, aggregation and deduplication
- specification/execution/result/status separation
- witness-based operation composition

The test suite is a finite executable reference check, not a proof of universal theorems.

Run:

    python knowledgeos_r604_reference_calculus.py

# S5 verifier, contract revision 7: developer guide

| | |
|---|---|
| **Area** | S-Series · P3b S5 batch verification · contract revision 7 |
| **Contract** | `docs/knowledgeos/chronological-read/prompts/20260926_2400_p3b-agent-contract-r7-addendum.md` (v2.3, FROZEN, G-LOG-0088) |
| **Trust root** | ADR-R7-01 `docs/knowledgeos/chronological-read/audit-p3b/20260926_ADR-R7-EXECUTION-TRUST-ROOT.md` |
| **State** | implemented, engineering-verified, **NOT activated, NOT independently audited** |

**Reading order:**

| # | Guide | Covers |
|---|---|---|
| 01 | `01_contexts_and_verdicts.md` | the five bounded contexts, the composer, and the layered verdicts (BATCH-*, STATISTICS-*, PROGRAM-ACCEPTED) |
| 02 | `02_witness_and_reader.md` | the neutral transcript decoder, the harness witness (W1–W8), I(run), the orchestrator grammar, and the reader's R7 behaviour |
| 03 | `03_universe_evidence_reconstruction_statistics.md` | registry sovereignty, discovery vs validation, claims, byte-exact anchoring, summary relations and precedence, `estimate_v7` |
| 04 | `04_testing_and_known_conflicts.md` | fixtures, the acceptance matrix, properties, and the known design conflicts (NDB-1, DC-1, DC-2) |
| 05 | `05_orchestrator_and_slice_view.md` | the v2.7 SLICE-VIEW (lossless slice rendering) and the EG-1 orchestrator tool (prepare / archive / freezes / verify) |

**Invariants honoured throughout:**
- Anonymity (ADR-T11) is untouched: nothing here links voters to votes.
- The verifier reads source bytes only to check integrity; it creates no Evidence object.
- ML is outside every evidence and verdict path.

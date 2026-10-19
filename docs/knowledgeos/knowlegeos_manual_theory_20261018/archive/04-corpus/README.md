# 04-corpus — MRP-1 workspace (templates only)

| Field | Value |
|---|---|
| Protocol | MRP-1, defined in `03-methodology/M-01-methodology-assessment-20261018.md` §5 |
| Created | 2026-10-18, empty |
| State | **No corpus file has been queued, read or extracted.** Use is blocked until the prerequisites in M-01 §7 are recorded in the governance log |
| Principle | Primary reading is the unit of evidence. Tools only locate, hash, count and check; they never summarise |

## Layout

| Path | Component (M-01 §5.1) | One row or file per |
|---|---|---|
| `queue/QUEUE.tsv` | 2 · reading queue | file to read |
| `extract/E-TEMPLATE.md` → `extract/E-<S-id>.md` | 3 · file-by-file extraction | file read |
| `ledgers/CONCEPTS.tsv` | 4 · concept and definition ledger | definition occurrence |
| `ledgers/CHRONOLOGY.tsv` | 5 · chronology | dated change event |
| `ledgers/DEPENDENCIES.tsv` | 6 · theoretical dependency map | dependency edge |
| `ledgers/CONTRADICTIONS.tsv` · `ledgers/UNCERTAINTY.tsv` | 7 · registers | contradiction / uncertainty |
| `ledgers/MATH.tsv` | 8 · mathematics | mathematical statement |
| `ledgers/CLAIMS.tsv` · `ledgers/LEADS.tsv` | 9 · traceability; quarantine of secondary material | claim / lead |
| `batches/BATCH-TEMPLATE.md` → `batches/BATCH-nn.md` | 10 · batch checks and report | batch |
| `tools/quote_anchor_check.py` | quote-anchor check (M-01 W-6) | — |

Provenance (component 1) stays in `00-access/READ-LOG.tsv` and `01-ledger/READ-AND-EXTRACTED.tsv`, which are unchanged.

## Controlled vocabularies

| Field | Values |
|---|---|
| `QUEUE.status` | QUEUED · READING · DONE · DEFERRED (reason required) · DUPLICATE-OF:`<S-id>` (diff recorded) · VARIANT-OF:`<S-id>` |
| `QUEUE.tier` | T0 founding canon (S0001–S0046, S0011) · T1 core-term definers · T2 chronological sweep · H hub |
| `date_basis` | EXPLICIT-IN-FILE (confirmed to apply to the file) · GIT-FIRST-COMMIT · REGISTRY · UNDATED |
| `CHRONOLOGY.change_type` | BIRTH · RESTATES · EXTENDS · NARROWS · CHANGES · RETRACTS · CONTRADICTS |
| `DEPENDENCIES.kind` | DEFINED-BY · ASSUMES · PROVED-FROM · SPECIALISES · USES |
| `CONTRADICTIONS.classification` | GENUINE · SCOPE · TERMINOLOGY · REVISION · UNDETERMINED |
| `MATH.status` | TRANSCRIBED · WELL-FORMED · VERIFIED · COUNTEREXAMPLE · UNDETERMINED |
| `CLAIMS.status` | STATED · STABLE · CONTESTED · RECONSTRUCTED · UNSUPPORTED |
| `UNCERTAINTY.kind` | DATING · SCOPE · TERM · AUTHORSHIP · TEXT (e.g. a truncated or garbled passage) |

## Rules

1. **Anchors:** `S-id` plus a line range `L<a>-<b>` at the sha recorded in the queue. A verbatim quote is required for every definition, claim and mathematical statement.
2. **Precedence** between two events only when both have date basis EXPLICIT-IN-FILE and the dates are strictly ordered. Otherwise the order is UNDETERMINED.
3. **Secondary material** (manuscript v1, Session A families, F-Series, `docs/knowledge_tranfer/`, my basis notes) enters **only** `LEADS.tsv`. No CLAIMS row may cite it.
4. **My own mathematics or deductions** are marked `[NEW]` and never merged into source rows.
5. **IDs:** `C-nnnn`, `EV-nnnn`, `DEP-nnnn`, `X-nnnn`, `U-nnnn`, `M-nnnn`, `CL-nnnn`, `L-nnnn`. Never reused; rows are corrected by appending a superseding row, never by editing.
6. **Completion criteria** for a file, a batch and a claim: M-01 §5.2.
7. **Access:** sealed and hold-out material stays excluded; no corpus text is passed to sub-agents (G-LOG-01xx §5).
8. **Extraction rules R1–R4** (adopted by the Principal 2026-10-18 after V-2; `05-validation/V2-REPORT.md` §5), stated in `extract/E-TEMPLATE.md`: meaning lines describe only their own source · itemise every finding in dense lines · REPETITION-OF only for verbatim or strict-subset repeats · each batch keeps an independent blind re-extraction of a sample.
10. **Working loop (Principal, 2026-10-18): research first, records second.** Read one file → understand its ideas → check them against the text → write them into the theory chapter (R: what the source says, with anchors; T: my analysis). Repeat file by file. After every few sections, look back twice: (1) **did I leave out any idea** from the files behind these sections? (2) **does the theory written so far make sense:** consistent, well-defined, logically sound? Record what each look-back found and fixed. Extraction records and ledgers serve the chapter; they are not the product.
9. **Batch size** 100 files, closed and quote-checked in groups of 10–20; one Principal checkpoint report per 100-file batch.

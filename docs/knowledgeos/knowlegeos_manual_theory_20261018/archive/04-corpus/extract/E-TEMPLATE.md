# E-<S-id> — <short title as the file states it>

<!-- MRP-1 extraction record. Copy to E-<S-id>.md. Fill every section, or write "none found".
     Quote lines must keep the exact form  > Q<n> L<a>-<b>: "<verbatim text>"
     so that tools/quote_anchor_check.py can verify them.

     Extraction rules adopted after V-2 (2026-10-18, V2-REPORT §5):
     R1  A meaning line describes ONLY this source. Comparisons with other documents
         go to §10/§11 and are marked [I]. (V-2 failure cause.)
     R2  Every numbered or bulleted finding inside a line is its own item.
         Never "summarised, not itemised".
     R3  REPETITION-OF in line accounting only when the passage repeats an earlier
         item word for word or as a strict subset. Otherwise extract it.
     R4  Batches keep an independent re-extraction of a sample (see BATCH-TEMPLATE). -->

```yaml
source_path:
s_id:
sha256:
lines_total:
lines_read:          # must equal lines_total for DONE
bytes_total:
bytes_read:          # must equal bytes_total for DONE
registry_date:
git_first_commit:
explicit_dates_in_file:   # each with a line anchor and whether it applies to the file
read_utc:
batch:
status:              # READING | DONE | DEFERRED
```

## 1. What the document says it is
<!-- purpose, genre, self-declared status (draft, adopted, superseded …), as stated -->
> Q1 L-: ""

## 2. Definitions
| # | Term | Quote | Concept id |
|---|---|---|---|

> Q L-: ""

## 3. Assumptions
<!-- stated or explicitly marked as assumed; nothing inferred here -->

## 4. Propositions and claims
<!-- what the document asserts, with its own strength words (proved, conjecture, proposal …) -->

## 5. Arguments
<!-- how the claims are supported in the document: derivation, example, appeal to another document -->

## 6. Examples

## 7. Mathematical statements
| # | Quote anchor | Math id | Type (definition · axiom · theorem · lemma · conjecture · formula) |
|---|---|---|---|

## 8. Terms introduced, renamed or changed
| Term | Change | Quote anchor | Chronology event |
|---|---|---|---|

## 9. References to other documents
| Reference as written | Line | Resolves to (path / S-id / UNRESOLVED) |
|---|---|---|

## 10. Contradictions and uncertainties noticed
| Item | Register id |
|---|---|

## 11. [I] My notes — interpretation only, kept apart from §§1–10

## 12. Open questions

## 13. Completion check
- [ ] sha matches the queue
- [ ] lines_read = lines_total and bytes_read = bytes_total
- [ ] every section filled or "none found"
- [ ] quote_anchor_check: all quotes PASS
- [ ] ledgers updated (concepts, chronology, dependencies, contradictions, uncertainty, math)
- [ ] READ-LOG and READ-AND-EXTRACTED rows written

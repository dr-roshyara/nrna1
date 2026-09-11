# ledger/

**Append-only raw records.** One record per unit of work, written as it happens.

| | |
|---|---|
| **Format** | Markdown per [`../templates/TEMPLATE-record.md`](../templates/TEMPLATE-record.md); JSONL once a stage needs machine-parsing (§C7) |
| **Rule** | a record is **never edited**. A correction is a **new record** naming the record it corrects (§C8) |
| **Why** | the ledger is history. A rewritten record destroys the evidence that a change happened |

A ledger record is **`DERIVED`** — it records what *we* did, never what a source said. A source is
**cited, never replaced**.

**Empty by decision.** No record exists yet because no unit of work has happened yet. The first
record is written when the first brainstorming session is captured.

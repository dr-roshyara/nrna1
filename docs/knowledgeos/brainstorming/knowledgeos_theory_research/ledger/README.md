# ledger/

**Append-only raw records.** One record per unit of work, written as it happens.

| | |
|---|---|
| **Format** | Markdown per [`../templates/TEMPLATE-record.md`](../templates/TEMPLATE-record.md); JSONL once a stage needs machine-parsing (§C7) |
| **Rule** | a record is **never edited**. A correction is a **new record** naming the record it corrects (§C8) |
| **Why** | the ledger is history. A rewritten record destroys the evidence that a change happened |

**On provenance.** A ledger record is usually **`DERIVED`** — it records what *we* did. A source is
**cited, never replaced**: where a record rests on something said, it names that `UTTERANCE` in its
`cites:` field rather than restating it as its own (§C4.3).

A ledger record that *is* source material — a captured statement promoted here as its own unit — is
`PRIMARY · UTTERANCE`, and it stays so (§C6).

**Empty by decision.** No record exists yet because no unit of work has happened yet. The first
record is written when the first brainstorming session is captured.

# templates/

One template per document kind. A template exists so that the conventions in
[`../00-CONVENTIONS.md`](../00-CONVENTIONS.md) are satisfied **by construction** rather than by
memory — especially §C4 (status is a vector), §C6 (provenance) and §C9 (anchors).

**How to use.** Copy the template → rename it `YYYYMMDD-HHMM_<slug>.md` (§C1) → fill it → **register
it in [`../00-INDEX.md`](../00-INDEX.md) in the same act**. An unregistered document is outside the
system (§C3).

**How to add a kind.** A kind is added when a document genuinely does not fit an existing kind, and
the commit that adds it says so. Prefer extending an existing template over adding a near-duplicate.

**The record shape.** `TEMPLATE-record.md` is the machine-record shape. When a stage promotes to
JSONL (§C7), these field names become the JSONL keys — **no new fields are invented at that point**.

| template | for |
|---|---|
| `TEMPLATE-session.md` | one brainstorming session, as it happened |
| `TEMPLATE-finding.md` | one finding, with its falsifier |
| `TEMPLATE-decision.md` | one decision (`D-nn`) |
| `TEMPLATE-open-question.md` | one open question (`OQ-nn`) |
| `TEMPLATE-topic.md` | one theory topic and the forms found in it |
| `TEMPLATE-synthesis.md` | a stage roll-up |
| `TEMPLATE-record.md` | one append-only ledger record |

# `<record id>` — ledger record

***Append-only.** One record per unit. **Never edit a written record** — a correction is a new record
that names this one (§C8).*

```yaml
---
id:            <ID>
date:          <YYYY-MM-DD>
stage:         <NN-stage>
kind:          <session | finding | decision | open-question | topic | synthesis>
provenance:    <PRIMARY | DERIVED | SECONDARY-SYNTHESIS>
claim_layer:   <UTTERANCE | OBSERVATION | INTERPRETATION | HYPOTHESIS | FINDING | N/A>
governance:    <NONE-RECORDED | reviewed | recommended | selected | adopted | ratified | rejected | withdrawn | N/A>
evidence_status: <value>
identity_status: <value>
lifecycle:     <ACTIVE | DORMANT | RETRACTED | SUPERSEDED | CONTESTED>
anchor:        <heading or verbatim quote — never a line number (§C9)>
cites:         <record id(s) this record rests on, or null>
corrects:      <record id, or null>
---
```

> **`provenance` and `claim_layer` are two questions, not one** (§C4.1).
> `provenance` — who produced this record. `claim_layer` — what role it plays in the corpus.
> Neither is derived from the other, and **`claim_layer` is not a ladder**: `FINDING` is not higher
> than `OBSERVATION`, only different.
>
> **`governance` is orthogonal to both** (§C4.4) — it lists recorded acts, not strength, and it never
> changes a `claim_layer`. `claim_layer: N/A` marks a record that is an **act**, not a claim.
>
> **`cites` is how the evidence layer stays intact.** An assessment of an utterance cites the
> utterance; it never re-types it (§C4.3).

**Statement**

`<the record>`

**Says what**

`<what this record is based on — cited by record id or quoted with an anchor, never paraphrased>`

**Does not say**

`<the boundary. Most records are read as broader than they are.>`

---

> **JSONL promotion (§C7).** When a stage needs machine-parsing, this record becomes one JSON line
> and **these field names become the JSONL keys** — no new fields are invented at that point.

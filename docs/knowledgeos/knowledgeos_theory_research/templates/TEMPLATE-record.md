# `<record id>` — ledger record

***Append-only.** One record per unit. **Never edit a written record** — a correction is a new record
that names this one (§C8).*

```yaml
---
id:            <ID>
date:          <YYYY-MM-DD>
stage:         <NN-stage>
kind:          <session | finding | decision | open-question | topic | synthesis>
provenance:    DERIVED
evidence_status: <value>
identity_status: <value>
lifecycle:     <ACTIVE | DORMANT | RETRACTED | SUPERSEDED | CONTESTED>
origin:        <EVIDENCE | DERIVED-PROPOSAL>
anchor:        <heading or verbatim quote — never a line number (§C9)>
corrects:      <record id, or null>
---
```

**Statement**

`<the record>`

**Says what**

`<what this record is based on — cited, not paraphrased>`

**Does not say**

`<the boundary. Most records are read as broader than they are.>`

---

> **JSONL promotion (§C7).** When a stage needs machine-parsing, this record becomes one JSON line
> and **these field names become the JSONL keys** — no new fields are invented at that point.

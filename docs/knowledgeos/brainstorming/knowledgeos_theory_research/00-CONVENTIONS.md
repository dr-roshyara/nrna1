# Conventions — `knowledgeos_theory_research/`

**`[DEF]` · created 2026-09-11 · amended 2026-09-11 (A1–A3).** Every document in this root satisfies
these rules.

The rules are **executable, not aspirational**: a document that breaks one is a **finding**, not a
style nit.

---

## C1 · Naming

| artefact | pattern | example |
|---|---|---|
| a document | `YYYYMMDD-HHMM_<slug>.md` | `20260911-1508_bootstrap-session-corpus-purpose.md` |
| a stage folder | `NN-<stage>/` | `10-capture/` |
| a meta file inside a stage | `_<NAME>.md` | `_STAGE.md` |
| a template | `TEMPLATE-<kind>.md` | `TEMPLATE-finding.md` |
| a registered identifier | `<KIND>-<nn>` | `D-01` · `OQ-01` · `F-01` |

The timestamp is the **authoring** time, not the event time. Identifiers are stable once assigned and
are **never reused**.

## C2 · Numbering encodes stage — nothing else

`10 · 20 · 30 · 40 · 50 · 60 · 70` are **pipeline stages**. They are never a priority, a rank, an
importance, or a claim of authority.

A document in `70-theory/` is not *more true* than one in `10-capture/`. It is **later in the
pipeline**.

## C3 · The register defines membership

A document is **in the system** when [`00-INDEX.md`](00-INDEX.md) lists it.

A file on disk that the register does not name is **outside the system** — either it is registered,
or it is removed.

## C4 · Status is a vector, never one enum

Every document carries **independent** status fields. They coexist. They are never collapsed into a
single grade.

| group | field | values |
|---|---|---|
| evidence | `evidence_status` | `WITNESSED` · `WITNESSED-PARTIAL` · `WITNESSED-INFORMAL` · `WITNESSED-NAME-ONLY` · `SECONDARY-ONLY` · `NOT-EVIDENCED-IN-CAPTURE` · `GENUINELY-UNDEFINED-AFTER-CENSUS` |
| identity | `identity_status` | `RECONCILED` · `IDENTITY-UNWITNESSED` · `HOMONYM-SPLIT` · `CONTESTED` · `N/A` |
| lifecycle | `lifecycle` | `ACTIVE` · `DORMANT` · `RETRACTED` · `SUPERSEDED` · `CONTESTED` |
| provenance | `provenance` | `PRIMARY` · `DERIVED` · `SECONDARY-SYNTHESIS` (§C6) |
| epistemic role | `claim_layer` | `UTTERANCE` · `OBSERVATION` · `INTERPRETATION` · `HYPOTHESIS` · `FINDING` (§C4.1) |
| governance | `governance` | `NONE-RECORDED` · `reviewed` · `recommended` · `selected` · `adopted` · `ratified` · `rejected` · `withdrawn` (§C4.4) |

A document that is `CONTESTED` in lifecycle and `WITNESSED` in evidence is **exactly that — both, at
once.** Neither field is derived from the other.

### C4.1 · Provenance and epistemic role are two different questions

| field | answers | and is **not** |
|---|---|---|
| `provenance` | **who or what produced this record** | not what the record claims |
| `claim_layer` | **what epistemic role the record plays** | not who produced it |

**Neither field is ever derived from the other.** They are set independently, and both must be set.

**`claim_layer` is unordered.** It is not a ladder, not a maturity scale, and not a promotion path.
`FINDING` is not *higher* than `OBSERVATION`; it is a **different role**. Nothing is ever *promoted*
between layers — a record at a new layer is a **new record** that cites the old one (§C4.3).

**`claim_layer` is a corpus-record classification — machinery, not theory.** It says what a record is
doing *in this corpus*. It is not a claim about the subject matter, not an ontology of knowledge, and
not a theoretical vocabulary. It exists so that later stages can tell a participant's words from the
agent's.

### C4.2 · Coherent and incoherent pairings

The two fields are independent, but not every combination means anything. Three are **defects**:

| pairing | verdict |
|---|---|
| `PRIMARY` + `UTTERANCE` | ✅ coherent — source material, in a participant's own voice |
| `DERIVED` + `OBSERVATION` / `INTERPRETATION` / `HYPOTHESIS` / `FINDING` | ✅ coherent — agent material, at a stated role |
| `DERIVED` + `UTTERANCE` | ⛔ **defect** — agent material labelled as source evidence |
| `PRIMARY` + anything other than `UTTERANCE` | ⛔ **defect** — a source is never *our* observation, interpretation, hypothesis or finding |
| `SECONDARY-SYNTHESIS` + `UTTERANCE` | ⛔ **defect** — navigation is never source material |

A defect is **recorded as a finding** (§C5). It is never silently repaired.

### C4.3 · A source utterance is never reclassified

**Source material stays source material — permanently, whatever it says.**

An utterance that *expresses a hypothesis* is still an `UTTERANCE`. An utterance that turns out to be
wrong is still an `UTTERANCE`. An utterance that later becomes the basis of a finding is still an
`UTTERANCE`.

Our assessment of an utterance is a **new record that cites the utterance's ID**. The utterance's own
`provenance` and `claim_layer` never change. This is what keeps the corpus a corpus: the evidence
layer is not rewritten by what we later conclude about it.

### C4.4 · Governance is orthogonal — it is not the top of the ladder

`governance` records **which acts have been recorded**. It does not record what is true, and it does
not record how strong a claim is.

- A governance act may attach to a record at **any** `claim_layer` — including `UTTERANCE`.
- A governance act **never changes** a record's `claim_layer`, `provenance` or `evidence_status`.
- `FINDING` is **not** "one step below governance". **Governance ≠ truth. Not ratified ≠ false.**
- Absent acts are recorded as `NONE-RECORDED`. The field is never left blank.

### C4.5 · Scope — where the vector applies

`provenance`, `claim_layer` and `governance` are required on every **corpus record** — anything in
`10-capture/` … `70-theory/`, and anything in `ledger/`.

The system's own **machinery** — `00-*`, any `_STAGE.md`, `templates/`, `scripts/`, `ledger/README.md`,
`90-meta/` — carries `N/A` in the `claim_layer` and `governance` positions. *Machinery is not a claim.*

### Compact form

```
[F-01 20260911-1508] · DERIVED · FINDING · WITNESSED · N/A · ACTIVE · NONE-RECORDED
```

Order: `provenance · claim_layer · evidence_status · identity_status · lifecycle · governance`.

**Never** write "status: confirmed", "status: final", or any other single word. There is no such
field.

> **Amended 2026-09-11 (A3).** The field `origin` (`EVIDENCE` · `DERIVED-PROPOSAL`) is **withdrawn**.
> It duplicated what `provenance` and `claim_layer` now carry separately, and a redundant third axis
> is exactly the collapse §C4.1 exists to prevent. Recorded in [`90-meta/CHANGELOG.md`](90-meta/CHANGELOG.md).

## C5 · Status advances only by a recorded act

**A document never acquires a later status because of the directory it sits in.**

Moving a file between stage folders changes nothing. Status changes when a **record** says it
changed — and that record is appended, never substituted (§C8).

An incoherent pairing under §C4.2 is likewise a **recorded finding**, never a silent edit.

## C6 · Provenance classes

| class | meaning |
|---|---|
| `PRIMARY` | **produced by a participant, not by the agent** — source material: the captured utterance, or an ingested source document |
| `DERIVED` | **produced by the agent** — observation, interpretation, hypothesis, finding, synthesis |
| `SECONDARY-SYNTHESIS` | a synthesis of syntheses; navigational, **never** evidence |

**`PRIMARY` means "not produced by the agent" — not "not produced in this root".**

A statement made by a participant *in this root* is `PRIMARY`. It is the evidence this corpus exists
to preserve. Its **file** is written by the agent, which is precisely why the file's provenance and
the statement's provenance are recorded separately and at different scopes (§C4.1, §C4.5).

> **Amended 2026-09-11 (A2).** As first committed, `PRIMARY` read *"an external source document — not
> produced here"*. Every utterance in this root therefore fell to `DERIVED` — *our own synthesis* —
> and the corpus's evidence layer would have been typed as agent material on contact. Recorded as
> [`F-01`](10-capture/20260911-1508_f-01-provenance-defect.md).

## C7 · Record format

Machine-parseable records are **JSONL**. Narrative is **Markdown**.

**Never TSV for quoted text.** Quoted text contains tabs, newlines and delimiters; a TSV silently
corrupts it.

No file in this root is JSONL yet — the format is declared so that the first stage which needs it
**promotes into an existing shape rather than inventing one**. `TEMPLATE-record.md` carries that shape.

## C8 · Append-only

`ledger/` records and session records are **append-only**.

A correction is a **new record** that names the record it corrects. An earlier record is **never
silently rewritten** — the fact that a change happened is itself evidence.

## C9 · Anchors

A verbatim quote carries an **anchor**: a heading, or the quoted text itself.

**Never a line number.** Line numbers do not survive an edit; a quote does.

## C10 · Vocabulary boundary

**This root invents no *theoretical* vocabulary.** It does not define, rename, translate or restate a
concept belonging to KnowledgeOS theory. Where a statement needs a term from the theory, the term is
used exactly as its source used it, and the source is cited (§C9).

**It does define its own *record classification*** — the fields of §C4 and the values of §C4.2 —
because a corpus cannot be assembled without a way to tell source material from agent material. That
classification is **machinery**: it describes records *in this root*, makes no claim about the subject
matter, and carries no theoretical content.

> **The test.** A `claim_layer` value answers *"what is this record doing in the corpus?"* If a
> proposed value instead answers *"what is true about the thing being discussed?"*, it is theory, and
> it does not belong here. An unresolved question is recorded as an open question — never resolved by
> adding a value.

> **Amended 2026-09-11 (A1).** The earlier `C10` borrowed three vocabularies **from outside this
> root** by pointer, and its own rule made resolving them a prerequisite for the first document that
> needed them. All three sat `PENDING`, so the rule would have blocked the corpus from ever starting.
> The pointers are **withdrawn**. Nothing outside this root is required to write a record here.
> Recorded in [`90-meta/CHANGELOG.md`](90-meta/CHANGELOG.md).

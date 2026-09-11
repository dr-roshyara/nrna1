# Conventions — `knowledgeos_theory_research/`

**`[DEF]` · created 2026-09-11.** Every document in this root satisfies these rules.

The rules are **executable, not aspirational**: a document that breaks one is a **finding**, not a
style nit.

---

## C1 · Naming

| artefact | pattern | example |
|---|---|---|
| a document | `YYYYMMDD-HHMM_<slug>.md` | `20260911-1430_theory-scope-brainstorm.md` |
| a stage folder | `NN-<stage>/` | `30-reconciliation/` |
| a meta file inside a stage | `_<NAME>.md` | `_STAGE.md` |
| a template | `TEMPLATE-<kind>.md` | `TEMPLATE-finding.md` |
| a registered identifier | `<KIND>-<nn>` | `D-01` · `OQ-07` · `F-12` |

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
| origin | `origin` | `EVIDENCE` · `DERIVED-PROPOSAL` |

A document that is `CONTESTED` in lifecycle and `WITNESSED` in evidence is **exactly that — both, at
once.** Neither field is derived from the other.

A compact one-line form is used in the register and in document headers:

```
[D-04 20260911-1430] · DERIVED · WITNESSED-PARTIAL · RECONCILED · ACTIVE
```

**Never** write "status: confirmed", "status: final", or any other single word. There is no such
field.

## C5 · Status advances only by a recorded act

**A document never acquires a later status because of the directory it sits in.**

Moving a file between stage folders changes nothing. Status changes when a **record** says it
changed — and that record is appended, never substituted (§C8).

## C6 · Provenance classes

| class | meaning |
|---|---|
| `PRIMARY` | an external source document — **not produced here** |
| `DERIVED` | **our own synthesis — the default for everything in this root** |
| `SECONDARY-SYNTHESIS` | a synthesis of syntheses; navigational, **never** evidence |

## C7 · Record format

Machine-parseable records are **JSONL**. Narrative is **Markdown**.

**Never TSV for quoted text.** Quoted text contains tabs, newlines and delimiters; a TSV silently
corrupts it.

Nothing in this root is JSONL yet — the format is declared so that the first stage which needs it
promotes into an existing shape rather than inventing one. `TEMPLATE-record.md` carries that shape.

## C8 · Append-only

`ledger/` records and session records are **append-only**.

A correction is a **new record** that names the record it corrects. An earlier record is **never
silently rewritten** — the fact that a change happened is itself evidence.

## C9 · Anchors

A verbatim quote carries an **anchor**: a heading, or the quoted text itself.

**Never a line number.** Line numbers do not survive an edit; a quote does.

## C10 · Borrowed vocabularies — pointer register

**This root invents no vocabulary.**

Status values, relationship taxonomy and the status chain are **borrowed** from vocabularies already
governed in this estate. They are cited by pointer and **never restated** here — per `ES-005.4`,
never a copy.

| borrowed for | vocabulary | canonical home | resolved? |
|---|---|---|---|
| epistemic status of a statement | the estate's epistemic-status vocabulary | *to be resolved at first use* | ⛔ `PENDING` |
| relationship between two forms | the relationship taxonomy (`MD-017`) | *to be resolved at first use* | ⛔ `PENDING` |
| maturity of a claim | the status chain (`MD-018`) | *to be resolved at first use* | ⛔ `PENDING` |

**Rule.** Resolving a row is a **prerequisite** for the first document that needs it.

Until then the row stays `PENDING`. *An unresolved pointer is recorded, never guessed — emptiness is
recorded, never filled.*

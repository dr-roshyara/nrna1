# 20-objects — what forms and candidate objects are present

**`[DEF]` stage contract · P2.** Written 2026-09-11. Registered in [`../00-INDEX.md`](../00-INDEX.md) §D.

> ## The one question this stage answers
> ### *What forms and candidate objects are present?*

| | |
|---|---|
| **Entry criteria** | `10-capture/` holds at least one finding or topic |
| **Exit criteria** (terminal predicate) | every captured statement is referenced by at least one topic; every topic lists its forms with anchors; **no form has been merged with another and no canonical name has been chosen** |
| **May be written here** | topic files · form lists · candidate object names · groupings of labels shown side by side |
| **Must NOT be written here** | a declaration that two forms are **the same** · a chosen canonical name · an identity resolution of any kind |
| **Source phase** | protocol P2 — *labels are handles; `UNKNOWN-OBJECT-CANDIDATE` is always a valid answer* |
| **Template** | [`TEMPLATE-topic`](../templates/TEMPLATE-topic.md) |

## The rules that govern this stage

**Grouping is not merging.** Labels may be placed side by side in one group — `K-state`,
`knowledge-state`, `K*` — so that the next stage can inspect them together. **The grouping never
declares them the same object.**

**A form with no anchor cannot proceed.** If a form cannot be tied to a heading or a verbatim quote,
that is recorded as `WITNESSED-NAME-ONLY` — not quietly dropped, and not given an anchor it does not
have.

**Two forms that differ only in type are not thereby different objects** (§C4 — type and identity are
decided separately). This stage keeps them apart; it does not decide.

## What is deliberately absent here

No JSONL records and no script. Added only if this stage needs machine-parsing.

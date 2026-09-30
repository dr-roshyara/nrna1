# 30-reconciliation — how the forms relate, and whether they are type-coherent

**`[DEF]` stage contract · P3.** Written 2026-09-11. Registered in [`../00-INDEX.md`](../00-INDEX.md) §D.

> ## The one question this stage answers
> ### *How are those forms related, and are they type-/math-coherent?*

| | |
|---|---|
| **Entry criteria** | a topic in `20-objects/` lists ≥2 forms with anchors |
| **Exit criteria** (terminal predicate) | every pair of forms in a topic has a recorded **relationship *and its basis***, plus a **separately recorded type compatibility**; every negative verdict is labelled `NEGATIVE-BOUNDED` or `NEGATIVE-CENSUS` — never an unlabelled `INDEPENDENT` |
| **May be written here** | pair records · per-object roll-ups (identity · type · lifecycle) · dependency edges · quarantine files for derived proposals |
| **Must NOT be written here** | an `INDEPENDENT` verdict from a search bounded to one topic · a verdict in which type decided identity · a relationship with no stated basis |
| **Source phase** | protocol P3 — *relationship and type are decided separately* |
| **Template** | [`TEMPLATE-synthesis`](../templates/TEMPLATE-synthesis.md) for the roll-up; pair records use [`TEMPLATE-record`](../templates/TEMPLATE-record.md) |

## The rules that govern this stage

**Two questions per pair, answered independently:**

1. **Relationship** — what is the historical/semantic relation? Every answer carries a **basis**:
   `CORROBORATED` · `SOURCE-CLAIMED-ONLY` · `INFERRED` · `NONE`. *A claim never becomes a
   relationship without the basis being stated.*
2. **Type compatibility** — are the formal signatures compatible? These are recorded **separately**.
   A representation change may change the codomain without changing the object.

**Insufficient alone** for any relationship: same symbol · same name · same author · same document ·
similar wording · temporal proximity · similar purpose.

**The negative-verdict bar.** `INDEPENDENT` may be concluded **only** from a **corpus-wide** search
for a connecting claim or shared derivation. A search bounded to the current topic that finds
nothing yields **`UNWITNESSED`, labelled `NEGATIVE-BOUNDED`** — never `INDEPENDENT`. Only a search
stated as corpus-wide and empty yields `INDEPENDENT`, labelled `NEGATIVE-CENSUS`.

**Derived proposals are quarantined.** If a completeness gap suggests a completion, it goes to a
quarantine file marked `PROPOSED` and **is never promoted here** — promotion happens at
`60-governance/`, by a recorded act.

## What is deliberately absent here

No JSONL records and no script. Added only if this stage needs machine-parsing.

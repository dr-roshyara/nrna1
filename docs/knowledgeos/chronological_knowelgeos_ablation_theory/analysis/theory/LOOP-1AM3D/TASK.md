# Task (frozen): does role conformance separate accepted from not-accepted items?

Use ONLY `SOURCES.md` (four register rows, all of header type "Delivery Governance · Acceptance") and this file. No other path, no web, no commands.

## 1. Events
Find every **acceptance act** in the rows: an item accepted, not accepted, withdrawn or held.

## 2. Code each act
From the text only. `UNK` if not established; never guess. Quote every non-UNK value.

| Field | What to record |
|---|---|
| `row` | the register row |
| `item` | what is accepted or not accepted |
| `item_kind` | e.g. work package, slice, completion evidence, acceptance package, report |
| `actor` | who accepts or declines |
| `state_before` | e.g. completed / submitted / PREPARED |
| `conformance` | whether role separation around the item is **stated**: who produced the evidence, who certified it, who accepted it. `CONFORMANT` (the separation is stated as kept) · `COLLAPSED` (stated as collapsed, e.g. self-certified by the producer) · `UNK` (not stated). **Do not code CONFORMANT merely because no collapse is mentioned.** |
| `outcome` | ACCEPTED · NOT-ACCEPTED/HELD/WITHDRAWN |
| `ground` | RULE · CHOICE · UNK; quote the reason |
| `cluster` | acts decided together share a cluster; name the decision |

## 3. Frozen witness rule (identical to the prior task)
- **Fields compared:** `item_kind`, `actor`, `state_before`, `conformance`. The header type is equal by construction.
- **STRICT witness for X:** outcomes opposite; the not-accepted act's ground is not CHOICE; X is known and different; every other compared field is known and equal.
- **POSSIBLE witness for X:** the same, but some other compared field is UNK. None is known and different.
- **Otherwise:** none.

Examine **every** pair of an ACCEPTED act and a NOT-ACCEPTED act, and report the witness class for **conformance** and for **item_kind**. Also report the reason the text itself gives for each not-accepted act.

## 4. Output
- **ANSWER.json:** {acts, pairs: [{accepted, not_accepted, witness_conformance, witness_item_kind, decisive_fields}], stated_reasons, notes}.
- **ANSWER.md:** labels SOURCE FACT / INFERENCE / FORMAL CONSEQUENCE / UNKNOWN; list the ambiguities.

There is no expected answer.

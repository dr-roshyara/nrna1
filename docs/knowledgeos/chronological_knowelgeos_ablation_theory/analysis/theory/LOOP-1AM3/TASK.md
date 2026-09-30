# Task (frozen): what separates an adoption that was performed from one that was held?

Use ONLY `SOURCES.md` (four register rows) and this file. No other path, no web, no commands.

Two ADOPT events are named:
- **P:** the Decision Authority's adoption of R-81 … R-85, recorded in R-86;
- **Q:** the Decision Authority's adoption of R-91, which was held or not performed.

## 1. Code each event
From the text only. `UNK` if not established; never guess. Quote every non-UNK value.

| Field | What to record |
|---|---|
| `object` | what is adopted |
| `kind` | the kind of the adopted object (e.g. ruling / constitutional decision / evidence record / implementation evidence). Quote the text that states it |
| `actor` | who adopts or declines |
| `state_before` | the object's status before (e.g. PREPARED) |
| `conformance` | whether the role separation around the object is **stated** (who prepared, submitted evidence, reviewed, decided): `CONFORMANT` (the separation is stated as kept) · `COLLAPSED` (stated as collapsed / self-certified) · `UNK` (not stated) |
| `outcome` | PERFORMED · REFUSED/HELD |
| `ground` | RULE · CHOICE · UNK; quote the reason |

## 2. Compare (frozen rule)
- **Fields compared:** `kind`, `actor`, `state_before`, `conformance`.
- **STRICT witness for field X:** outcomes opposite; Q's ground is not CHOICE; X is known and different; every other compared field is known and equal.
- **POSSIBLE witness for X:** the same, but some other compared field is UNK. None is known and different.
- **Otherwise:** not a witness for X.

Report for **conformance** and for **kind** separately. Also report which reading, if any, the text itself gives as the reason for Q's outcome.

R-71 is supplied as possible background on role separation. Use it only to the extent that its text bears on P or Q, and say how.

## 3. Output
- **ANSWER.json:** {P, Q (coded records), witness_conformance: STRICT / POSSIBLE / NONE, witness_kind: STRICT / POSSIBLE / NONE, decisive_fields, stated_reason_for_Q, alternatives}.
- **ANSWER.md:** labels SOURCE FACT / INFERENCE / FORMAL CONSEQUENCE / UNKNOWN; list the ambiguities.

There is no expected answer.

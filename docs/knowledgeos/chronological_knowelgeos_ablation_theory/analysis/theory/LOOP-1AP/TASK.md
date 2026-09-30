# Task (frozen): what makes a ruling in force: its issuer, or its status?

Use ONLY `SOURCES.md` (five register rows) and this file. No other path, no web, no commands.

## 1. Rulings
List every **ruling** that the rows make, or say something about, whose **force** (whether it is in force / effective / operative) the text states or determines. This includes rulings discussed by other rows (e.g. a row that adopts earlier rulings).

## 2. Code each ruling-state
If a ruling's status changes over time, code **one record per state** (e.g. before and after an adoption). From the text only; `UNK` if not established; never guess. Quote every non-UNK value.

| Field | What to record |
|---|---|
| `ruling` | the ruling id |
| `state_label` | e.g. "at issue", "after R-86" |
| `issuer` | who issued or prepared it, as named |
| `status` | the ruling's status marker as stated (e.g. PREPARED, ADOPTED, HELD, WITHDRAWN, none stated). "none stated" is **different** from UNK: record exactly what the text shows |
| `adopter` | who adopted it, if anyone, as named; else "none" / UNK |
| `in_force` | IN-FORCE · NOT-IN-FORCE · UNK, with a quote. Say whether this is **stated** by the text or **derived** by you, and if derived, from what |

## 3. Frozen test

| Model | Claim |
|---|---|
| **M_issuer** | force is determined by the issuer alone |
| **M_status** | force is determined by the status marker alone |
| **M_adopter** | force is determined by who adopted it |
| **M_chain** | issuer / adopter → status → force (the status mediates) |

- A single-variable model (M_issuer, M_status, M_adopter) is **FALSIFIED** by two ruling-states with equal known values of its variable and opposite known in_force.
- It is **UNDETERMINED** if no such pair exists and some pair has UNK on the variable or on in_force.
- It **SURVIVES** otherwise.
- **M_chain** survives iff M_status survives **and** every status change in the data is attributed to an act of an actor; report which actor.

Report the deciding pairs for each model.

## 4. Output
- **ANSWER.json:** {ruling_states: [...], models: {M_issuer, M_status, M_adopter: {verdict, deciding_pairs, undetermined_pairs}, M_chain: {verdict, status_changes: [{ruling, from, to, by_actor, quote}]}}, notes}.
- **ANSWER.md:** labels SOURCE FACT / INFERENCE / FORMAL CONSEQUENCE / UNKNOWN; list the ambiguities.

There is no expected answer.

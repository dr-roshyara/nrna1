# Task (frozen): does evidence alone separate these two dispositions?

Use ONLY `SOURCES.md` and this file. No other path, no web, no commands.

## 1. Identify the two dispositions
SOURCES.md contains two dispositions concerning one candidate:
- in P1, the candidate is **parked** / not actioned;
- in P2, it is **adopted / put in force** in some form.

Identify each one. If P1 and P2 are not about the same candidate, say so, and quote the evidence either way.

## 2. Code each disposition
Fill each field from the text only. Use `UNK` if the text does not establish it. Never guess.

| Field | What to record |
|---|---|
| `object` | what the candidate is |
| `kind` | the object's kind, e.g. reusable document, protocol, contract |
| `actor` | who disposes, as named |
| `target` | the home / standing it is to receive, in the text's words |
| `state_before` | the candidate's status before this disposition |
| `evidence` | the count and unit stated or clearly enumerated, with a quote |
| `exception` | YES / NO / UNK |
| `outcome` | RAISED / NOT-RAISED |
| `ground` | RULE (a cited rule or principle requires the refusal) · CHOICE (selection among permitted options) · UNK |

For every field, record whether the basis is SOURCE or UNK, with a quote.

## 3. Classify the pair (frozen rule)
- **Fields compared:** `kind`, `actor`, `target`, `state_before`, `exception`. The differing field under test is `evidence`.
- **STRICT witness for evidence:** outcomes differ; P1's outcome is RULE-grounded (not CHOICE); the evidence values are known and differ; and every compared field is known and equal.
- **POSSIBLE witness:** as STRICT, but some compared fields are UNK. None is known and different.
- **NOT A WITNESS:** some compared field is known and different, **or** P1's ground is CHOICE, **or** the objects differ, **or** the evidence is not known to differ.

Name the decisive field(s) in every case.

## 4. Output
- **ANSWER.json:** {same_object, dispositions: [two coded records], classification, decisive_fields, alternatives}.
- **ANSWER.md:** a short account. Label each statement SOURCE FACT / INFERENCE / FORMAL CONSEQUENCE / UNKNOWN, and list the ambiguities.

There is no expected answer.

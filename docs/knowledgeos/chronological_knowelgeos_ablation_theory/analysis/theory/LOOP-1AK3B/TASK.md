# Task (frozen): promotion decisions and how their evidence is counted

Use ONLY `SOURCES.md` and this file. No other path, no web, no commands.

## 1. Events
Find every **standing-raising decision or assessment** in SOURCES.md: an item promoted, held, not promoted, or assessed for promotion into a higher-standing home (canon, a standard, methodology, the engineering platform, and so on). Include precedents that the text cites as having happened, marked `cited: true`.

## 2. Code each event
From the text only. `UNK` if not established; never guess. Quote every non-UNK value.

| Field | What to record |
|---|---|
| `item` | what is being raised |
| `target` | its intended home |
| `actor` | who decides or assesses, as named |
| `act_type` | DECISION · ASSESSMENT/RECOMMENDATION · REPORT-OF-PRIOR-DECISION · UNK |
| `evidence_instances` | the count of instances / occurrences, as stated |
| `evidence_contexts` | the count of contexts / bounded contexts / domains, as stated |
| `evidence_other` | any other unit (e.g. slices), with its count |
| `threshold_stated` | the rule's stated requirement, verbatim, and **which unit it counts** (instances / contexts / slices / UNK) |
| `exception` | YES (a recorded exception to the normal rule) · NO (the text states none, or states the normal rule is applied) · UNK |
| `outcome` | RAISED · NOT-RAISED/HELD · PENDING · UNK |
| `ground` | RULE · CHOICE · UNK |

## 3. Frozen classification
- **U (evidence unit):** does any event have a RULE-grounded NOT-RAISED/HELD outcome where `evidence_instances` ≥ 2 **and** `evidence_contexts` = 1, and where the stated threshold counts **contexts**? If so, that event is a **unit witness**: instances do not substitute for contexts. List every such event, or say none.
- **X (exception mechanism):** list every event that is RAISED while its evidence is below the stated threshold. For each one, report:
  - `exception`: YES, NO or UNK;
  - whether the actor is identified as the Decision Authority (DA).

  The categories are:
  - **X-consistent:** below threshold, exception YES;
  - **X-counter:** below threshold, exception NO, actor identified as the DA;
  - **X-undetermined:** anything else.

## 4. Output
- **ANSWER.json:** {events, U_witnesses, X_events: [{event, class}], notes}.
- **ANSWER.md:** a short account. Label each statement SOURCE FACT / INFERENCE / FORMAL CONSEQUENCE / UNKNOWN, and list the ambiguities.

There is no expected answer.

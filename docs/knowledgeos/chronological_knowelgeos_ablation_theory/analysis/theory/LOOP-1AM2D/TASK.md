# Task (frozen): which variable separates raised from not-raised items?

Use ONLY `SOURCES.md` (four register rows) and this file. No other path, no web, no commands.

## 1. Events
In each row, find every **standing-raising event**: an item raised into, or explicitly not raised into, a higher-standing home (a standard, a methodology, canon, an ES clause, and so on). A clarification or refinement that is hosted in an existing standard counts, if the text presents it as gaining standing.

## 2. Code each event
From the text only. `UNK` if not established; never guess. Quote every non-UNK value.

| Field | What to record |
|---|---|
| `row` | the register row |
| `item` | what is raised |
| `actor` | who decides, as named (e.g. PA / Principal Architect, ARB, DA / Decision Authority, PO) |
| `raise_kind` | `EXTENSION` (added to or hosted in an **existing** standard or home) · `NEW` (a new standing item or a new standard) · `UNK` |
| `target` | the home, in the text's words |
| `evidence` | the count and unit (instances / occurrences / slices / work packages / contexts), or UNK |
| `recorded_exception` | YES if the text records an exception to a normal rule; NO if the text applies the normal rule; UNK otherwise |
| `outcome` | RAISED · NOT-RAISED |
| `ground` | RULE · CHOICE · UNK |

## 3. Given prior events (reconciled in earlier reviewed tasks; check the quotes but do not re-code)

| id | actor | raise_kind | target | evidence | exception | outcome | ground | quote |
|---|---|---|---|---|---|---|---|---|
| E01 | PA | EXTENSION | ES-004 (documentation standard) | 1 instance | none recorded | RAISED | UNK | "ES-004.3 … permanent documentation standard (Principal Architect instruction)" (R-41) |
| E02 | UNK | NEW | methodology | 1 occurrence | NO | NOT-RAISED | RULE | "NOT promoted to methodology — single occurrence, and ES-006.1 forbids promoting from one" |
| E20 | ARB | NEW | operating instructions | 1 slice | NO | NOT-RAISED | RULE | "temporary carrier, not a pattern (PB-005 Q1)" |

**Note:** E01 is R-41. If your coding of R-41 differs from the E01 row, report the difference; do not silently merge.

## 4. Frozen test
Each model says the outcome is a monotone function of evidence, **with a threshold that may depend on**:

| Model | Threshold may depend on |
|---|---|
| M_A | `actor` |
| M_T | `target` class |
| M_K | `raise_kind` |
| M_AT | (`actor`, `target`) |

Recorded-exception events (YES) are **excluded** from every model.

**Falsification rule:** a model is FALSIFIED by a pair where:
- P is RAISED;
- Q is NOT-RAISED on a RULE ground;
- P and Q have equal **known** values on the model's parameter(s);
- evidence(Q) ≥ evidence(P) is certain.

Units: repetition counts (instances / occurrences / slices / work packages) are comparable with one another as counts. Contexts are comparable only with contexts. Any other combination is UNDETERMINED.

A pair with an UNK on a needed field is UNDETERMINED.

## 5. Output
- **ANSWER.json:** {events (new, coded), R41_vs_E01_differences, models: {M_A, M_T, M_K, M_AT: {verdict: FALSIFIED / NOT FALSIFIED, deciding_pairs, undetermined_pairs}}, confound_broken: which pair (if any) separates which models}.
- **ANSWER.md:** labels SOURCE FACT / INFERENCE / FORMAL CONSEQUENCE / UNKNOWN; list the ambiguities.

There is no expected answer.

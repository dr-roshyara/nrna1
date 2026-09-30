# Task (frozen): what determines a standing-raising (RAISE / "promotion") outcome?

You are an independent researcher. Use ONLY `SOURCES.md` and this file. Do not open any other path, do not use the web, and do not run commands.

## 1. Identify the events
Find every standing-raising event in SOURCES.md: an item being promoted, or explicitly not promoted, into a higher-standing home such as:
- a standard or a standards document;
- operating instructions;
- a methodology or principles document.

## 2. Code each event (one record per decision item)
Fill these fields from the text only. Use `UNK` if the text does not establish a value. **Never guess.**

| Field | Content |
|---|---|
| `id` | a short name + source (S1…S6) + its anchor words |
| `actor` | who decides or acts, as named (e.g. "PA", "ARB", "PO/ARB", "Decision Authority", "the session author / engineering") |
| `target_type` | the kind of home, in the text's own words, then normalized to one of: STANDARD-DOC · OPERATING-INSTRUCTIONS · METHODOLOGY/PRINCIPLES · PRINCIPLES-DOC · CANDIDATE-PATTERN · OTHER · UNK |
| `evidence` | the number of independent instances / slices / contexts stated (an integer), or UNK; and the unit the text uses (instances / slices / contexts / occurrences) |
| `exception` | YES if an exception is recorded for this item; NO only if the text says there is none; otherwise UNK |
| `outcome` | PROMOTED · NOT-PROMOTED · UNK |
| `ground` | RULE (the text cites a rule that forbids or requires) · CHOICE (selection among options on a stated principle) · UNK |
| `cluster` | the decision it belongs to (items decided together share a cluster) |
| `basis` | for each field: SOURCE (stated) or UNK. Put a quote for every non-UNK field |

## 3. Evaluate these competing models (frozen)
Every model assumes the outcome is **monotone in evidence**: if an item is PROMOTED at evidence n, an otherwise-equal item with evidence ≥ n is not NOT-PROMOTED on a RULE ground.

| Model | The outcome is a monotone function of evidence … |
|---|---|
| M_E | … alone |
| M_A | … with a threshold that may differ by `actor` |
| M_T | … with a threshold that may differ by `target_type` |
| M_AT | … with a threshold that may differ by the pair (`actor`, `target_type`) |
| M_C | none of the above is consistent (some other mechanism) |

**Falsification rule:** a model is FALSIFIED iff there are two RULE-grounded-or-PROMOTED events P (PROMOTED) and Q (NOT-PROMOTED, RULE) such that:
- they have equal known values on the model's parameters;
- evidence(Q) ≥ evidence(P).

A pair with UNK on a needed field is UNDETERMINED. CHOICE-grounded items are excluded from falsification.

## 4. Output (write both files in this directory)
- **EXECUTION.json:**
  - `events`: the list of coded events;
  - `models`: M_E, M_A, M_T, M_AT, each with {verdict: FALSIFIED / SURVIVES / UNDETERMINED, deciding_pairs, undetermined_pairs};
  - `M_C`: whether it is needed.
- **EXECUTION.md:** a short account. Label every statement SOURCE FACT / OBSERVATION / INFERENCE / HYPOTHESIS / FORMAL CONSEQUENCE / UNKNOWN. List every place where the text was ambiguous and how you coded it.

There are no expected answers.

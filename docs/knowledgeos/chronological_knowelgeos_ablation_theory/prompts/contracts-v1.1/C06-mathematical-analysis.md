# C06 — Mathematical analysis · v1.1

**Treatment:** F-SPECIFIC first-class lens. Inherits unchanged: XC STEP 9 (flag, never repair: MATH-QUESTION,
STAT-QUESTION, TYPE-QUESTION), v3.5 B4 ("type-closed ≠ valid"), P3B §1D (a structure is a candidate, never canonical),
P3B §13.12 (external-theory comparison is never historical evidence).

## 1. Questions (apply to every formula, notation, theorem and formal claim in the inventory)
Well-definedness (are the objects, domains and codomains defined?) · typing (does each operation type-check; do two
uses of one symbol have the same type?) · consistency of stated properties (can they hold together?) · proof status
(what is proved, sketched, asserted) · hidden assumptions (a theorem invoked without its hypotheses) · known
mathematical results invoked correctly? · what the formalization would need to become precise.

## 2. Analysis record — `ANALYSIS.jsonl` (shared by C04, C06, C07, C08)
```json
{"an_id":"FAN-F####-001","level":2,"lens":"STRUCTURAL|MATHEMATICAL|LOGICAL|DDD|STATISTICAL-ML",
 "kind":"OBSERVATION|STRUCTURE|GAP|INTERNAL-TENSION|CORRECTNESS-FINDING|SCHEMA-LIMITATION|METHODOLOGICAL-DEFICIENCY",
 "epistemic_class":"RESEARCH-OBSERVATION|DOMAIN-INTERPRETATION|EXTERNAL-THEORY-COMPARISON",
 "statement":"…","inventory_refs":["FCI-F####-0010"],"reasoning":"the step written out",
 "severity":"BLOCKING|MATERIAL|MINOR|null","research_time":"UTC","run_id":"…","model_id":"…"}
```
`CORRECTNESS-FINDING` states a mathematical defect in the source's claim (e.g. two stated properties cannot hold
together) with the reasoning written out. It is our assessment (R6), never a correction of the source, and never
written into Level 1.

## 3. Forbidden
Repairing the source's mathematics in place · promoting a sketched theorem to proved · treating elegance or
repetition as validity.

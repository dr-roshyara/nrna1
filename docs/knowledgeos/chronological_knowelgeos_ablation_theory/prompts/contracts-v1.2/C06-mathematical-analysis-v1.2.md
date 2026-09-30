# C06 — Mathematical analysis · v1.2 (delta)

**Base:** `contracts-v1.1/C06-mathematical-analysis.md`, sha256 `36dfecd51981050ba8f97da32d7d8f16beb17ec97e0f72c953a5143a4b1daf59`. Unchanged: the questions in §1, and never repairing the source.

## Changes (RN-04, RN-05, RN-06; F-09)
1. **Two separate status fields.**
   - **Source status** (L1, THEOREM `source_status`): ASSERTED · PROOF-SKETCH · PROVED · CITED · UNDEFINED-UNCLEAR. This is what the author did.
   - **Analytical status** (L2, CORRECTNESS-FINDING `analytical_status`): INTERNALLY-CONSISTENT · INTERNALLY-INCONSISTENT · EXTERNALLY-VERIFIED · EXTERNALLY-CONTRADICTED · UNRESOLVED. This is what we established.

   "The author did not prove it" is a source status. It is never an analytical defect.
2. **Basis, matching the status:**
   - INTERNAL: from the file's own definitions and assumptions, with the derivation in `reasoning`.
   - EXTERNAL: against established mathematics, naming `external_source` (P3B §13.12; never historical evidence).
   - UNRESOLVED: the analyst cannot verify.

   "I do not recognize this result" is UNRESOLVED, never a defect.
3. **Every Level-2 record states its `reasoning`.** A CORRECTNESS-FINDING without reasoning, basis or status is refused.
4. **Shared Level-2 schema (C04, C06, C07, C08):**
```json
{"an_id":"FAN-F####-001","level":2,"lens":"STRUCTURAL|MATHEMATICAL|LOGICAL|DDD|STATISTICAL-ML",
 "kind":"OBSERVATION|STRUCTURE|GAP|INTERNAL-TENSION|CORRECTNESS-FINDING|SCHEMA-LIMITATION|METHODOLOGICAL-DEFICIENCY",
 "epistemic_class":"RESEARCH-OBSERVATION|DOMAIN-INTERPRETATION|EXTERNAL-THEORY-COMPARISON",
 "statement":"descriptive, no prescription","reasoning":"the step written out","inventory_refs":["FCI-F####-0010"],
 "interpretation_frame":"required for DOMAIN-INTERPRETATION","external_source":"required for EXTERNAL-*",
 "analytical_status":"CORRECTNESS-FINDING only","basis":"INTERNAL|EXTERNAL|UNRESOLVED",
 "ddd_claim_type":"SOURCE-DESCRIBED (DDD STRUCTURE)","research_time":"ISO-8601 UTC","run_id":"…","model_id":"…"}
```

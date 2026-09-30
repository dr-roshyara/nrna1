# 1am-2b: disclosed revision (evidence-scope expansion; the task is unchanged)

- **Trigger:**
  - 1am-2 produced no deciding pair between authority and target (per review B).
  - E30 (R-39) was coded with actor DA, exception YES, and target and evidence UNK, because only a reference to R-39 was in scope.
  - A locate-level read of the R-39 register row (inside the already-read register range, L16–86) showed that it states a target and an evidence count.
- **Change:** SOURCES.md gains **S7 = the verbatim R-39 row**. TASK.md is byte-identical. No model and no rule was changed.
- **The 1am-2 outputs (A and B) stand unamended.**
- **Sealed main-analyst expectation (bias check, not shown to the subagents):**
  - R-39 is coded as PA/DA · METHODOLOGY/PRINCIPLES · 1 context · PROMOTED · exception YES.
  - Paired with E04 (METHODOLOGY · 1 · NOT-PROMOTED · RULE): M_E and M_T are FALSIFIED if the units "context" and "occurrence" are compared as bare integers; M_A and M_AT stay UNDETERMINED (E04's actor is UNK).
  - Risk: the reviewer may rule that the unit mismatch makes the pair non-comparable. It may also classify the mechanism as *exception*, i.e. outside all frozen models (M_C), with authority and exception confounded in R-39.

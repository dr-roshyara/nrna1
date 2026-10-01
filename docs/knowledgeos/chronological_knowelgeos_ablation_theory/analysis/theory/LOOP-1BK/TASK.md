# Task (frozen): draft the minimal formal theory (R4)

Use ONLY:
- `R3-CODING-INDEPENDENT.json`: the admissible empirical base (51 items), with the "R4 may / may not assume" constraints in section H;
- `HYPOTHESES.md`: H-D and H-B, frozen;
- `H5.md`: the adopted non-identifiability result and its gaps;
- `INSTRUMENT-FINDING.md`: the observation layer is not reliably codable, and the candidate concept *performative ambiguity*.

No commands, no web.

Write **FORMAL-THEORY-R4.md**. It must be **minimal**: introduce nothing that the base, or the two hypotheses as stated, do not need.

1. **Primitives and definitions:** record, act, norm, norm state σ(t), work event, actor, and the three times (recording, decision, entry into force).
   - Give an **observation function** O from (unobserved) histories to the observable corpus.
   - Say exactly what O can and cannot see, consistent with the base (e.g. recording time yes; decision time not in general).
2. **The state space and its transitions.**
3. **H-D and H-B as model classes:** constraints on histories, derived **only** from HYPOTHESES.md. Show they are nearly nested; state the exact relation.
4. **D1 as a proposition:**
   - (a) state it precisely relative to O;
   - (b) list every assumption;
   - (c) prove it;
   - (d) give a countermodel for each assumption, showing it is needed;
   - (e) give sufficient conditions on an **additional observable** under which H-D and H-B become distinguishable;
   - (f) relate (e) to the H5 gaps, without claiming any such observable exists.
5. **Performative ambiguity:**
   - a formal definition, e.g. in terms of O⁻¹ of a record containing histories where the record *is* the act and histories where it *reports* a separate act;
   - its relation to D1;
   - the **conjecture** linking it to the coding failure. State it as a conjecture, with what would falsify it.
6. **Statement ledger:** a table of every statement, with its label (definition / assumption / lemma / proposition / conjecture / empirical observation) and its base item ids where it relies on data.
7. **R5 spec:**
   - a precise specification of a small finite-state model (bounded actors, acts, norms, time steps) and of the claims to check by exhaustive enumeration: equivalence under O, the countermodels, the sufficient conditions;
   - give the expected outcome of each check;
   - **do not write code**. A separate independent verifier will implement it.

**Prohibitions:**
- no coding-dependent claim (e.g. no act or norm counts);
- H-D and H-B are never axioms;
- every "may not" in section H applies;
- no DDD or architecture terms.

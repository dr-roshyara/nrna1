# T-min v1: out-of-sample test pre-registration (1ab)

| | |
|---|---|
| Status | **Frozen at commit before any sampled row is read.** Not canonical. Authority: none |
| Theory under test | `analysis/theory/T-MIN-V1-CANDIDATE-THEORY.md` (`a7e04491…`), unchanged |
| Population | the 41 register rows (`7795c14b…`) never read or coded in F-LOG-0102…0134. Excluded: R-37, R-39, R-41, R-43, R-44, R-47, R-51, R-53, R-66, R-72, R-77…R-91, R-94, R-96, R-98, R-99, R-100 |
| Sample | **deterministic pseudo-random:** the first 15 of the population ordered by sha256("F-1ab-2026-09-28" + ID). Result: **R-30, R-32, R-34, R-35, R-40, R-45, R-52, R-56, R-62, R-63, R-64, R-65, R-68, R-75, R-97**. Chosen by the hash, not by content |
| Scope note | reduced from "~40 rows" to 15 (maximize information per exposure); extensible with the same seed |
| Coder | the same agent that built v1: **not blind to the theory**. Mitigations: the manual and predictions below are frozen first; source facts are coded before any prediction is applied; ambiguous codings are flagged, never resolved in favour of v1 |

## Coding manual (per row; operations first, then changes)
1. **Acts.**
   - Code every performative act in the headline or Effect (an ADOPT, AUTHORIZE, ACCEPT, APPROVE, … as the row names it).
   - Map an act to a v1 vocabulary operation **only if the row's text defines it as that operation**. Otherwise it is **NOT-MODELLED (name)**.
   - v1 vocabulary: AUTHORIZE, ADOPT, HOLD, WITHDRAW, ANNOTATE, ACCEPT {acceptance, lifecycle}, SUBDIVIDE, DETERMINE, FREEZE/LIFT, CONTRA, CREATE-NORM, RAISE, REJECT, ALLOCATE, PERMIT, SUPERSEDE, CORRECT-TEXT.
2. **Per act, record:**
   - authority (the triple);
   - kind;
   - the explicitly stated changes Δ⁺;
   - the explicit non-effects Δ⁻;
   - the evidence cited (yes, with a count if stated / NR);
   - any refusal, with its ground (RULE if the row cites a forbidding rule; CHOICE if it selects among options).
3. **Silence is NOT-RECORDED.** Nothing is inferred.

## Predictions (v1). Result per row: SUPPORTED · VIOLATED · UNTESTABLE · NOT-MODELLED

| # | Prediction | VIOLATED iff |
|---|---|---|
| V1 | **Legality:** every performed act is Legal under the v1 guards (authority · kind · prior state · four-act separation · evidence bar on RAISE/SUPERSEDE); every RULE-grounded refusal is ¬Legal | a performed act that a v1 guard forbids, with the forbidding fields positively recorded |
| V2 | **Four-act separation:** no row treats permission as authorization, authorization as commissioning, or commissioning as execution | the row derives one of these from another without a separate act |
| V3 | **Frames:** every stated change of a modelled operation lies in its Frame⁺ (ACCEPT {acceptance, lifecycle}; ADOPT {status, annotation-role}; others per M1/M2) | a stated direct change outside Frame⁺ |
| V4 | **Evidence persistence:** no standing/status changes by evidence alone | the row states such a change |
| V5 | **Reopening:** any reopening or reversal of a prior ruling cites new evidence | a reopening stated without new evidence |
| V6 | **Authority:** every ruling names its authority (triple or text); no self-adoption | an unnamed authority for a decisive act, or a self-adoption |
| V7 | **Supersession:** any supersession is explicit and keeps the old record | an implicit supersession, or a deleted old record |

## Out-of-sample metrics
- **Per prediction:** k supported / n applicable. Reported with the caveat of a single register, not IID across regimes (dates are recorded per row).
- **Coverage:** the share of coded acts that v1 can model (1 − NOT-MODELLED rate). **This is the key out-of-sample measure of the vocabulary's completeness.**
- **Any VIOLATED is reported with its row ID and quote. No revision of v1 in this step.**

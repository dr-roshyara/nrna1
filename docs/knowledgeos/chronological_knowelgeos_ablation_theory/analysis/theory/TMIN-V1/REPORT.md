# T-min v1: consolidation check and the event-level dataset

| | |
|---|---|
| Status | research record. Not canonical. Authority: none |
| Theory | `analysis/theory/T-MIN-V1-CANDIDATE-THEORY.md` (`a7e04491…`), frozen at `b2f1d5d76` before the check |
| Checker | `tminv1_check.py` (`7419653f…`), selftest 3/3 |
| Output | `TMIN-V1/RESULT.json` (`df948b41…`) |
| Log | F-LOG-0134 |

## 1. Does v1's legality layer reproduce the coded outcomes? (24 events; open world)
v1 was *built from* these events, so EXPLAINED means **consistency, not validation**.

| Evidence-bar variant | Explained | Open | Unexplained |
|---|---|---|---|
| G-R (route) | 23 | 0 | 1 |
| G-K (kind) | 23 | 0 | 1 |
| G-O (operation) | 22 | **1 (P1)** | 1 |
| G-E (effect) | UNDETERMINED (no effect field coded) | | |

**The one UNEXPLAINED event (all variants): AUTHORIZE(4C)@R-72.**
- The conflict is **internal to my coding**, not in the corpus.
  - v1's prior-state guard makes a successor's authorization illegal before its predecessor is accepted.
  - The legality/choice recoding (F-LOG-0132) had classed this refusal as CHOICE-grounded ("GROUNDS FOR THE SLICE-AT-A-TIME FORM"). That implies it was legal.
- Source facts: R-72 both *states* the rule ("Each is to be authorized from IMPLEMENTATION EVIDENCE … after its predecessor is accepted") and *applies* it ("WP-4C AND WP-4D ARE EXPRESSLY NOT AUTHORIZED").
- **Resolution options:**
  - recode the refusal as RULE-grounded (the ruling creates the rule and applies it in the same act);
  - or keep CHOICE and weaken the v1 guard.
- **Not resolved silently.** This is an instance of a general coding question: *when an act creates a rule and applies it at once, is the refusal rule- or choice-grounded?*

**P1 under G-O is OPEN:**
- if P1 is a RAISE without an exception, G-O forbids it;
- G-R and G-K explain P1 fully.

This agrees with the earlier M0 conjecture (G-R favoured), but it depends on P1's unrecorded operation reading and exception, so it **is not decisive**.

## 2. Markov
All three history pairs are handled by v1's finite summary: status + registry, authorization + proviso state, and no delegation from adoption.

## 3. Event-level dataset (1z; exported in `RESULT.json` → `dataset`)
- **24 events.** Each row carries: operation, route, authority, kind, prior state, evidence, exception, conformance, outcome, ground (RULE/CHOICE), regime, source family and source ref.
- **Copies collapsed:** R-81…R-85's pasted adoption annotation is **one** event, not five.
- **Source families:**

  | Family | Events |
  |---|---|
  | register | 15 |
  | session-log | 4 |
  | git history | 2 |
  | register rule | 1 |
  | standards document | 1 |
  | ADR | 1 |

- **Regimes:**

  | Regime | Events |
  |---|---|
  | pre-template human authority | 7 |
  | template, Chief-issued | 5 |
  | post-template Chief acts | 3 |
  | other dates | 9 |

- **Statistical readiness: NOT READY.**
  - The events were selected for discrimination.
  - They are clustered by day and source.
  - The families are unbalanced.
  - Any inferential use needs a sampling frame (e.g. all register rows as the population; the register census in F-LOG-0126 is the only census so far), plus clustering by regime and family.

## 4. Where the theory stands after consolidation
- **Consistent with every coded development event,** except one internal coding conflict.
- **Established:**
  - factored state;
  - the four-act separation;
  - authority, kind and prior state as legality variables;
  - the two-layer decision (legality, then choice);
  - finite history summaries;
  - the drafting-window record rule;
  - supersession as a pointer.
- **Fitted, must be tested on unseen data:** the conformance variable; the frame tables.
- **Undetermined by corpus design:** route vs operation.
- **Undetermined for lack of pairs:** evidence and exception as legality variables.

## 5. What would test v1 next (no new reading is done in this step)
1. **Prospective validation on unseen events.** Take the register rows not yet coded (about 40 of 71), code their acts blind, and check v1's predictions. This is the first genuine out-of-sample test.
2. **Independent verification (Gate 1).** A non-Claude verifier re-codes a sample of events from the source and re-runs `tminv1_check.py`.
3. **A designed observation for route vs operation:** a future promotion decision recorded with all event fields.

### Person planning the decision

The person is the **decision-process owner**. Their task is to:

1. **Define the problem** — What decision must be made?
2. **Define the scope** — What is and is not part of the decision?
3. **Provide the strategic and organizational constraints** — e.g. Cloud First.
4. **Identify stakeholders and decision authority**.
5. **Provide access to facts, documents, experts and organizational knowledge**.
6. **Set the governance rules** — who approves what, what is mandatory.
7. **Challenge AI's assumptions and conclusions**.
8. **Make or obtain the final decision**.
9. **Take responsibility for the decision**.

### Knowlegeos 's task

Knoweledgeos is the **analytical and epistemic support**, not the decision owner.

Its task is to:

1. **Understand and structure the problem.**
2. **Ask the right questions** and identify missing information.
3. **Challenge the proposed assumptions and requirements.**
4. **Detect contradictions, overlaps and double counting.**
5. **Propose alternative formulations and decision models.**
6. **Analyse evidence and distinguish fact, assumption and inference.**
7. **Test whether criteria are measurable and independent.**
8. **Evaluate options according to the agreed model.**
9. **Perform sensitivity/robustness analysis.**
10. **Identify risks, uncertainties and unintended consequences.**
11. **Challenge its own recommendation** — including looking for reasons why the opposite option might be better.
12. **Make the reasoning transparent and reproducible.**

### The key separation

```text
PERSON
  │
  │ defines problem, constraints, authority
  ▼
AI
  │
  │ questions → analyses → challenges → compares
  ▼
EVIDENCE-BASED DECISION MODEL
  │
  ▼
Knoweledgeos + PERSON
  │
  │ review / challenge
  ▼
ARCHITECTURE BOARD
  │
  ▼
FINAL DECISION
```

And one principle should govern the whole exercise:

> **The person must not tell Knoweledgeoswhich answer to produce; Knoweledgeos must not decide what the organization should do.**

The person defines **what decision needs to be made and under which legitimate constraints**.
Knoweledgeos helps determine **whether the reasoning used to reach that decision is sound**.

For your Nexus case, this is particularly important: **we should not start with “How can we justify On-Prem?”** We should start with **“Under what conditions should Cloud or On-Prem be selected?”** Then let the evidence determine the result.

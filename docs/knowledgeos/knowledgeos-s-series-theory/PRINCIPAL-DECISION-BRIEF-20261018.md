# Principal decision brief (2026-10-18)

Each decision is set out under the same seven headings: the decision · why it matters · options · recommendation · risks · evidence missing · the closing action.

## 1. D2: complete the authorization details

- **Decision:** approve the G-LOG-0147 wording (`safety-gate/D2-AMENDMENT-PROPOSAL-r2-20261017_2113.md`).
- **Why it matters:** it closes D2 and gives the authorization a citable number. It does not open the gate.
- **Options:** approve r2 as written · approve with row 5 ("studies") struck · ask for changes.
- **Recommendation:** approve, after confirming **row 1** (the narrower rules govern) and **row 5** (the count-only check is an access-control check, not a study).
- **Risks:** if row 1 is left unconfirmed, G-LOG-0141's broader scope could be read as conflicting.
- **Evidence missing:** none.
- **Closing action:** you paste G-LOG-0147 into the log. Committing the log is your separate choice.

## 2. D1: appoint the filter reviewer and the human tester

- **Decision:** name both people.
- **Why it matters:** no safety gate is possible without them.
- **Options:**
  - reviewer: a human engineer, or another AI model family as a supplement;
  - tester: any non-author with a non-root account.
- **Recommendation:** a human engineer as reviewer, and a different person as tester.
- **Risks:**
  - every internal review round found new filter leaks, so the external review matters;
  - under G-LOG-0144, a person who accesses this workspace becomes ineligible for Kind-P or prediction roles.
- **Evidence missing:** the reviewer's numbered verdict, then the signed test.
- **Closing action:** record the appointments; hand over `safety-gate/D1-REVIEW-PACKET-AND-HUMAN-TEST-SHEET.md`.

## 3. D6: appoint the external scientific reviewer

- **Decision:** name the reviewer, the permitted files and a deadline.
- **Why it matters:** **no claim is externally reviewed.** All review so far is same model family.
- **Options:** a human mathematician or theoretical computer scientist (preferred) · another AI model family as a supplement.
- **Recommendation:** a human reviewer, with access to `external-review/` and the listed `validation_reports/` only.
- **Risks:** the G-LOG-0144 side effect (as above); reviewer time.
- **Evidence missing:** written per-claim verdicts; a ruling on U-1; literature checks.
- **Closing action:** send the packet (README + stage-1 claims + two addenda).

## 4. The gate decision (later)

- **Decision:** record "gate passed".
- **When:** only after D1 PASS, a signed test and the G-LOG-0147 entry.
- **Then:** a separate act for the count-only check, and your ruling on its result.

## 5. D9 / D10 with stakeholders (later)

- **Decision:** which decisions are critical, and which retention option (A–E in `DECISION-PAPER-WHAT-TO-RETAIN-20261017_2052.md`) applies to each.
- **What is new since the decision paper:**
  - "**local vs global correctness**" (F3) is a policy choice;
  - **retroactivity and deletion constrain each other** (F4-6);
  - **parties and auditors may need different evidence** (F1).

  These belong in that discussion.
- **Recommendation:** none yet. These are stakeholder choices. The research has only set out the trade-offs.

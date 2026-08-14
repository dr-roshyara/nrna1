# Approval Request — Does the finished Session Assignment Lookup meet the requirement?

**Prepared by Governance (Session 2) · 2026-08-15 · Business-language format · One decision, with one small optional condition.**

---

## Purpose

Decide whether the finished tool meets what you approved — the last decision before it becomes a live part of the platform.

## What was built

The read-only lookup you approved: it tells a terminal which piece of governed work it is assigned to, and says **found · none · more than one · unreadable** — never a guess.

## What the independent verifier did — and it attacked, not just re-ran

The verifier tried to break the two safeguards you made binding:

- **It removed the existing qualified tool.** The lookup **refused to answer** rather than working things out itself.
- **It replaced that tool with one that deliberately lied.** The lookup **relayed the lie** rather than secretly consulting the records behind its back — proving it genuinely delegates and has no interpretation of its own.
- **It checked your success rule behaviourally:** all four answers report success; only a genuine misuse reports failure. So *"no assignment"* and *"more than one"* can no longer be silently discarded.
- **It ran the tool eight times across every path** and confirmed the records were left **byte-for-byte identical**.
- **It confirmed the tool grants nobody anything** — no permission, no ownership, no state change.

**Result: verified, with findings — no defect, no scope violation.** The verifier changed nothing: its only file is its own report, and the implementation is untouched since it was built.

## Two findings, and what Governance makes of them

**① A rough edge, not a fault.** Pointing the tool at a folder that doesn't exist looks the same as an empty one, so it answers *"no assignment."* That answer is safe — it means *stop and ask Governance* — so this is a diagnostics nicety, not a problem with what you approved.

**② One genuine question worth your attention.** The tool can be pointed at a *different* interpreter through a technical switch. Three things bound it: the switch exists **because you required a test proving the tool cannot answer without the real interpreter** — you cannot prove that without being able to take it away; the code declares it a test-only seam that nothing else depends on; and it cannot grant anyone anything — at worst it produces a *misleading report*, and a report was never permission.

**But Governance found one gap while checking it: the report doesn't say which interpreter produced its answers**, so a substitution would be silent. **A one-line improvement — the report names the interpreter it used — would make it visible without removing the test.** Your choice whether that is a condition now or a follow-up.

## What I am being asked to decide

**Does the finished capability meet the requirement?**

## What this decision does NOT authorize

Automatic use at session start · the six related record improvements · changes to the existing qualified tool · anything on the Election side.

## Technical reference

Verifier's report: `…-session1-verification.md` · Governance's review of it, including the full reasoning on finding ②: `…-verification-governance-review.md`

## Decision

**QUALIFIED / QUALIFIED WITH CONDITION (add the interpreter-name line before adoption) / NOT QUALIFIED**

*After this, Governance marks the tool adopted in the platform register and closes the work item — the last step.*

---

## DECISION RECORDED — QUALIFIED WITH CONDITION (PO/ARB, 2026-08-15, verbatim)

> *"I choose QUALIFIED WITH CONDITION.*
> *The condition is narrowly limited to making the workflow interpretation source visible in the ResolutionReport before adoption.*
> *Please: 1. Register my qualification decision verbatim as a conditional qualification. 2. Do not mark AST-016 adopted yet. 3. Do not close KOS-SESSION-DISCOVERY-001 yet. 4. Define the smallest possible implementation change: ResolutionReport must identify the qualified workflow mechanism/interpreter that supplied the workflow-state answer. Preserve all existing resolution semantics. Preserve the existing KOS_MECHANISM_PATH verification seam; do not remove it merely to hide the finding. Do not introduce authorization, activation, ownership, startup wiring, or any D-1…D-6 work. 5. Correct the documentation wording so that KOS_MECHANISM_PATH is described truthfully as a runtime-selectable mechanism path used as a verification seam, rather than implying that it is technically impossible to use at runtime. 6. Present the exact change boundary before implementation. 7. The change must then be implemented TDD-first and independently re-verified by Session 1. 8. Session 1 must specifically verify: interpreter identity is present in the report; normal AST-015 resolution still works; substituted-mechanism testing still works; no second fold exists; read purity remains intact; no authorization or state mutation is introduced. 9. Only after independent verification succeeds may Governance present the final qualification/adoption/closure decision.*
> *Do not broaden this into a redesign.*
> *The objective is simply: "make the source of workflow interpretation visible without changing who is authoritative." — PO/ARB"*

**Registered as a CONDITIONAL qualification.** `AST-016` stays **`planned`** · `KOS-SESSION-DISCOVERY-001` stays **OPEN** · no adoption, no closure, no final qualification until the condition is met and independently verified.

### Governance correction owed on item 5 — stated plainly

The PO's item 5 is a **correction of a claim Governance itself relied on.** My V-2 reasoning cited the code's comment (*"deliberately NOT a runtime configuration contract … undocumented for operators, no registry entry, nothing depends on it"*) as bound #2. That text is accurate about **intent and status**, but read as a whole it **implies the seam is not usable at runtime — and Session 1 demonstrated that it is.** The implication is false and must not stand in the codebase. **My reasoning's other bounds (the seam is the cost of amendment ②; it creates no authority) are unaffected — but the wording bound is weakened, and the PO is right to require truthful text.**

---

## THE CHANGE BOUNDARY (defined by Governance per item 4; PRESENTED per item 6 — implementation NOT authorized)

**Objective, one sentence:** *make the source of workflow interpretation visible without changing who is authoritative.*

**MUST change — exactly two things:**

| # | Change | Constraint |
|---|---|---|
| C-1 | **`ResolutionReport` identifies the interpreter that supplied the workflow-state answer** — the resolved mechanism path, present in **both** renderings (JSON and human), on **every** verdict path including `UNRESOLVABLE` | a **field**, not a judgement: the report **states** which interpreter answered; it must **not** assess whether that interpreter is legitimate, compare it to a registry entry, warn, or refuse. *Visibility, not validation* — validation would be a new authorization-shaped behaviour |
| C-2 | **Documentation wording corrected** at `session-resolve.php:76-85` (and anywhere else that repeats it): describe the seam **truthfully** as a **runtime-selectable mechanism path whose intended use is verification/dependency substitution**. **Do not delete the seam. Do not delete the finding. Do not soften it into "test-only" language that repeats the false implication** | the register entry's own wording is checked for the same implication |

**MUST NOT change:** every existing resolution semantic (the four verdicts, operability, `missingForActivation`, `UNKNOWN`s, verbatim grants, the unconditional caveat line, exit-code contract per amendment ①) · the delegation architecture (still no second fold; still cannot answer without the interpreter — amendment ②/T-13 must continue to pass **unchanged**) · read purity · `workflow-state.php` (byte-identical) · the `KOS_MECHANISM_PATH` seam itself.

**Explicitly excluded:** authorization, activation, ownership, startup wiring, hooks/locks/leases, D-1…D-6, Increment-2, Election work, registry **schema** change, any redesign. **`AST-016` may be updated only at final adoption, by Governance, after verification succeeds.**

**Tests:** the existing T-1…T-13 must all still pass **unchanged** (regression proof that semantics were preserved); **new tests, RED first**, must pin: interpreter identity present in **both** renderings on **every** verdict path · the identity reflects the **actually used** interpreter (i.e. it changes under substitution — which is what makes substitution visible) · nothing else in the report shifted.

**Verification obligations (item 8, carried verbatim into the eventual verification grant):** interpreter identity present · normal AST-015 resolution still works · substituted-mechanism testing still works · no second fold exists · read purity intact · no authorization or state mutation introduced.

**Sequence from here:** your approval of *this* boundary → Governance registers new implementation and verification assignments (R8: the previous ones are terminal — new work is a new assignment) with their grants → **TDD-first** implementation → independent re-verification → **then** Governance presents the final qualification/adoption/closure decision.

**Decision on the boundary: APPROVE / AMEND / DECLINE.**

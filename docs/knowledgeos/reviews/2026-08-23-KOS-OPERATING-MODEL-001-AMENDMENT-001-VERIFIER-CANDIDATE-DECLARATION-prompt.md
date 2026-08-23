# `KOS-OPERATING-MODEL-001-AMENDMENT-001` — verifier candidate-declaration prompt (AST-019)

**Work item:** `KOS-OPERATING-MODEL-001-AMENDMENT-001` · **Role:** verification (fresh, independent) · **Subject:** `AST-019` / `ActivateCommissionedFreshSession`
**Document type:** the PO/ARB's candidate-declaration prompt, **preserved verbatim** for the fresh verification session to consume on first start
**Recorded by:** `claude-code-session:77b85fa3-074e-4e5c-a494-f11d2c128595` — disclosed GOVERNANCE-RECORDING capacity. **This process is barred from the verifier role** (it recorded the producer identity) and is **NOT** the appointee.
**Placement derived:** `scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0)

> ⛔ **This document preserves the prompt only.** It creates no authority, no lane, no grant, no state change. The fresh verifier is a **separate process the PO/ARB starts** — **not a subagent**. Empirical probe (2026-08-22): a subagent reports its **parent's** `CLAUDE_CODE_SESSION_ID`, so a subagent **is** the parent session and fails the independence bar.

---

## The prompt (paste into a new session)

```text
============================================================
KOS-OPERATING-MODEL-001-AMENDMENT-001
CANDIDATE DECLARATION — INDEPENDENT VERIFICATION OF AST-019
============================================================

ROLE

You are a candidate for the fresh, independent verification role for:

    KOS-OPERATING-MODEL-001-AMENDMENT-001
    subject: AST-019 / ActivateCommissionedFreshSession
             (.claude/scripts/activate-commissioned-fresh-session.php)

This is a CANDIDATE-DECLARATION prompt.

You are NOT yet the appointed/activated verifier.

DO NOT begin verifying anything yet.

============================================================
0. DECLARE BEFORE YOU ORIENT  (read this first)
============================================================

A previous candidate on the parent work item read the material first
and declared afterwards. Its own reading then became prior
participation, and because the governing review-independence policy is
a deliberate placeholder, nobody could rule whether that participation
disqualified it. The session was spent and the question is still open.

Do not repeat that.

    Establish your identity FIRST.
    Check the bars FIRST.
    Read ONLY the authoritative state named in step 4.
    Declare. Then STOP.

Do not read AST-019's source, its tests, the plan, the amendment
document, or the completion report during this step. Reading the
subject is verification work, and you are not authorized yet.

============================================================
1. YOUR FIRST DUTY — RUNTIME IDENTITY
============================================================

Determine your process identity from the runtime:

    CLAUDE_CODE_SESSION_ID

It MUST come from the environment. Never from this prompt, never from
a command-line argument, never copied from a document.

Report:

    PROCESS IDENTITY:
        claude-code-session:<actual runtime identity>

============================================================
2. INDEPENDENCE CHECK — THE PRODUCER IS ON RECORD
============================================================

Unlike earlier steps in this estate, the thing you must exclude now
EXISTS. The producer of AST-019 was established from provenance and
recorded on 2026-08-23:

    PRODUCER:  1899d8bf-2688-4bf3-9787-b4114ddaeec8

Verify your runtime identity is not that, and not any of:

    1899d8bf   AST-019 producer                (R-34/EP-02 bar)
    cf621832   parent adoption reviewer        (re-ran AST-019's tests)
    77b85fa3   recorded the producer identity  (wrote this prompt)
    fc59bb0a   parent independent verifier
    259c1966   parent implementation producer
    b51dba91   CORRECTION-001 author / recorder
    5928b9f9   recorder (supersession notice)
    d31ea60f   prior governance process
    d89af2f5   prior governance recorder
    5c0e13c1   AST-018 implementation actor
    8a525719   AST-017 producer
    8deac5de   independent re-verifier
    d1612e03   prior verifier
    b64828fe   prior Governance process
    7c2690ae   prior would-be verifier
    PO/ARB     human authority

Then state, factually and in full, any prior participation you have on
this work item or on AST-019. Disclose everything; mitigate nothing.

    INDEPENDENCE:
        PASS / FAIL / UNDETERMINED

If you are a subagent of another session, you are that session. Report
FAIL.

============================================================
3. ELIGIBILITY
============================================================

    ELIGIBILITY:
        ELIGIBLE / NOT ELIGIBLE / UNDETERMINED

Eligible means only: "this process may be considered for the role."
It does not mean "I may begin."

============================================================
4. AUTHORITATIVE STATE — READ ONLY THIS
============================================================

Read the work item's state, and nothing else:

    php .claude/scripts/workflow-state.php fold \
        KOS-OPERATING-MODEL-001-AMENDMENT-001 \
        --dir=.claude/runtime/workflow

Expect: OPEN, no sessions, no mutation owner, no grants. Report what
you actually see, not what you expect.

============================================================
5. CURRENT AUTHORIZATION
============================================================

Your authorization is:

    NOT AUTHORIZED

No verification lane exists for your identity. It is created by
Governance and started by the human — never by you.

============================================================
6. DO NOT SELF-APPOINT
============================================================

    DO NOT REGISTER yourself.
    DO NOT HANDOFF.
    DO NOT START.
    DO NOT create a lane.
    DO NOT verify anything.
    DO NOT report a verification verdict.
    DO NOT claim AST-019 is verified, adopted, or authorized.

That the human ordered a verification does not let you fabricate the
activation that authorizes you to perform it.

============================================================
7. WHAT THE VERIFICATION WILL COVER  (context only — not now)
============================================================

So you know what you are being considered for:

  - Independently re-execute the GO-01..GO-25 contract tests
    (tests/Unit/Platform/WorkflowEngine/
     ActivateCommissionedFreshSessionContractTest.php) and the full
    WorkflowEngine regression. Do not trust the reported numbers.

  - Inspect the source against what AST-019 claims about itself:
    sole-writer through AST-015 (no direct record writes, no store-path
    knowledge), never writes CONTINUATION, identity from the
    environment only, never appoints, fail-closed validation V1-V10.

  - THE CENTRAL POINT: AST-019 has NEVER written a transition in
    production. Every lane in this estate was created by hand-composed
    AST-015 calls in governance-recording capacity. Its main write path
    is exercised only by its own test suite. Establishing whether that
    path is trustworthy is the substance of this verification.

  - Its two observed refusals in practice: CONFLICTING_ASSIGNMENT over
    an active lane (GO-13) and NOT POSSIBLE on a stopped item (GO-21).
    Both refusals were correct. Confirm they are correct for the right
    reasons.

  - Confirm the ADOPTED operating-model layers (L1 document, L2
    .claude/scripts/operating-model.php, L3 OperatingModelContractTest)
    are byte-unchanged. They are adopted and authorized; you verify
    them untouched, you do not re-verify or modify them.

  - Report IMPLEMENTED vs VERIFIED vs ADOPTED vs AUTHORIZED as four
    distinct states. You may conclude VERIFIED. You may never conclude
    ADOPTED or AUTHORIZED — those are the human's.

============================================================
8. HARD BOUNDARIES FOR THE WHOLE COMMISSION
============================================================

    Do NOT modify AST-019's source or its tests.
    Do NOT modify the adopted L1/L2/L3 layers.
    Do NOT modify AST-015/016/017/018.
    Do NOT reopen KOS-OPERATING-MODEL-001 (adopted, authorized, closed).
    Do NOT invoke AST-019's `activate` against a real work item.
         Use `check` (read-only) and the hermetic test fixtures.
    Do NOT adopt, authorize, or accept your own work (R-34/EP-02).

============================================================
9. REQUIRED OUTPUT
============================================================

    PROCESS IDENTITY:      <claude-code-session:actual-runtime-id>

    INDEPENDENCE:          PASS / FAIL / UNDETERMINED
                           Evidence: <brief factual basis>

    ELIGIBILITY:           ELIGIBLE / NOT ELIGIBLE / UNDETERMINED
                           Evidence: <brief factual basis>

    CURRENT AUTHORIZATION: NOT AUTHORIZED

    WORK ITEM STATE:       <as actually read in step 4>

    VERIFICATION LANE:     <as actually read in step 4>

    NON-ACTIONS:           No REGISTER / HANDOFF / START
                           No verification performed
                           No verdict, no adoption, no authorization

============================================================
10. STOP
============================================================

Return the declaration and STOP.

Governance then registers your declared identity to the verification
lane, and the human starts it. Only then do you verify.

    identity != role
    role != eligibility
    eligibility != authorization
    verified != adopted != authorized
============================================================
```

---

**Traceability:** verifier appointment registration (`…-AMENDMENT-001-VERIFIER-APPOINTMENT-registration.md`) · producer-identity registration (`…-AMENDMENT-001-PRODUCER-IDENTITY-registration.md`, F-3 settled) · Governance adoption review F-2/F-3/F-4/F-6 · authorization decision (AST-019 held) · candidate-declaration precedent `…-CANDIDATE-DECLARATION-77b85fa3.md` (the declare-before-you-orient lesson) · `R-34`/`EP-02` · `INV-ATTR-1/2`, `G-2` · `G-3` · `ES-004.2`

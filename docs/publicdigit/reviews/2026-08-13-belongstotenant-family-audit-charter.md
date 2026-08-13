# BelongsToTenant Family Audit — Commission Charter (Decision B execution)

**Provenance:** **PO-AUTHORED, VERBATIM** (2026-08-13) — registered durably by Session 2 (governance secretary); Session 2 neither wrote nor edited the commission text below.
**Status:** CHARTER REGISTERED · recommended executor: **Session 1** (independent verification stream) · **delivery to Session 1 is the PO's act in that stream** — this registration does not start the audit.
**Authority chain:** Decision A (CLOSED, 2026-08-13 — Election BC owns voting-time entitlement; ambient org/tenant context forbidden) → Decision B (DECIDED, 2026-08-13 — *"audit the `BelongsToTenant` family first, then repair"*) → **this audit** → PO/ARB disposition of findings → explicit bounded implementation grant → Session 3 → TDD.
**⛔ This charter authorizes investigation scope only. It authorizes no repair. Its findings will not self-authorize fixes.**

---

## Commission text (PO, verbatim)

```text
SESSION 1 — COMMISSION: BelongsToTenant FAMILY AUDIT
====================================================

PURPOSE
-------
Perform the audit authorized by Decision B:

"Audit the BelongsToTenant family first, then repair."

Decision A is CLOSED:
- Voting-time voter entitlement belongs to the Election bounded context.
- Its organisational scope must derive from the Election itself.
- Ambient organisation/tenant context is a forbidden dependency for voting-time entitlement.

Decision B is DECIDED:
- Scope = family-wide audit first.
- Repair follows the audit.
- No repair is authorized by this commission.

YOUR ROLE
---------
Independent verification only.

You are NOT Session 3.
You are NOT authorized to repair anything.
You must not change production code, tests, fixtures, schema, configuration,
Constitution, policies, cache keys, traits, repositories, or application services.

Do not "fix" findings.
Do not prepare a patch.
Do not refactor.
Do not make architecture decisions.

MISSION
-------
Enumerate every relevant consumer of the BelongsToTenant family in the
repository.

At minimum establish:

1. Where the trait / mechanism is defined.
2. Every model/class that uses it.
3. Every relevant query path through those consumers.
4. Whether the consumer depends on ambient tenant/organisation context.
5. Whether the consumer is:
   A. voting-time entitlement,
   B. admission-time eligibility,
   C. another Election concern,
   D. another bounded context / product concern,
   E. infrastructure/shared behaviour,
   F. unknown.
6. For Election voting-time consumers:
   determine whether organisational scope derives from the Election
   or from ambient tenant/session context.
7. Identify tenant-blind cache keys or equivalent context leakage.
8. Determine whether the behaviour can produce:
   - false denial,
   - false admission,
   - cross-election contamination,
   - cross-tenant contamination,
   - stale/poisoned cache results.
9. Identify the concrete production consumers affected.
10. Measure the blast radius.

DDD DISCIPLINE
--------------
Do NOT infer ownership from:

- file location,
- namespace,
- class name,
- current call stack,
- trait location,
- middleware location,
- dependency injection binding.

For each finding distinguish:

FACT
----
What the code demonstrably does.

BOUNDARY
--------
Which bounded context / concern the behaviour serves, based only on
accepted architectural authority.

VIOLATION
---------
Whether the observed behaviour violates Decision A.

UNKNOWN
-------
Anything that cannot be established from evidence.

Do not turn a runtime location into an ownership conclusion.

IMPORTANT:
The existence of BelongsToTenant does NOT mean all its consumers belong
to the Election context.

The audit is specifically intended to discover whether the family contains
other consumers with different semantics.

ELECTION-ONLY TEST
------------------
For every Election consumer ask:

"Does this operation determine whether this person is entitled to vote
in THIS Election at voting time?"

If YES:
    apply Decision A.

If NO:
    do not force Decision A onto it.
    classify its actual concern and authority separately.

This distinction is critical.

EVIDENCE METHOD
---------------
Prefer direct evidence:

- source code,
- actual query construction,
- global scopes,
- cache keys,
- call sites,
- runtime reproduction where useful,
- accepted ADRs / governance decisions.

Do not classify by filename or test name alone.

If runtime testing is needed:
- use throwaway test data only,
- do not modify production data,
- record the exact reproduction,
- do not change the code to make the test pass.

DELIVERABLE
-----------
Produce one decision-ready audit report containing:

1. Executive summary
2. Complete BelongsToTenant consumer inventory
3. Classification of every consumer
4. Election voting-time consumers
5. Decision-A compliance matrix
6. Confirmed violations
7. Potential violations requiring further evidence
8. Non-Election consumers explicitly excluded from Decision A
9. Cache/context contamination findings
10. Blast-radius assessment
11. Evidence and reproduction details
12. Unknowns / limitations
13. Recommended next governance questions

The report must NOT contain implementation recommendations such as:
"change User.php", "remove the trait", "change the cache key", etc.

You may describe candidate repair surfaces only as observed facts,
not as recommendations or authorization.

STOP CONDITIONS
---------------
STOP immediately if:

- you discover an ambiguity requiring a business decision;
- you discover conflicting accepted architecture;
- ownership cannot be established from accepted authority;
- you find a potentially broader architectural rule that requires ARB/PO
  decision;
- the audit would require changing code;
- the audit would require choosing a repair.

Return the evidence and stop.

COMMIT DISCIPLINE
-----------------
You may commit ONLY the audit report and explicitly authorized audit
artifacts.

Before committing:

git diff --cached --stat
git diff --cached --name-only

Verify that ONLY your audit artifacts are staged.

Never use:
    git add -A
    git commit -a

Other sessions are working concurrently. Never stage or commit their files.

FINAL VERDICT
-------------
The final report must answer:

"Which BelongsToTenant consumers demonstrably violate the accepted
Decision-A rule for voting-time entitlement, and what is the measured
family-wide blast radius?"

It must NOT answer:

"How should we fix them?"

Repair comes only after:

AUDIT
  → PO/ARB review
  → repair disposition
  → explicit bounded implementation grant
  → Session 3
  → TDD
```

---

**Session 2 registration notes:** the PO confirmed Session 2's Decision B interpretation ("DECIDED: Scope 2" — scope decision preserved as distinct from implementation authorization). Stream roles restated by the PO: Session 1 measures/audits · Session 2 governs the audit result and determines disposition · Session 3 remains STOPPED, later implements only an explicitly authorized slice · Session 4 reopens only if the audit exposes an architectural ambiguity. **Nobody asks Session 1 to "fix 65/69."**

**Traceability:** decision package §5e (Decision B ruling verbatim) · §5d (rulings A+B) · P6 report (`fc86049f`) · `PBDIGIT-65`/`69` tickets · Decision A acceptance (sentences (i)+(ii), 2026-08-13) · `EM-VOT-003` (Manifesto §4a).

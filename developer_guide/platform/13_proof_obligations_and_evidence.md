# 13: Proof obligations and evidence (D-193's 53, slice 2's T-1 … T-26), PBDIGIT-345 task 13

**Purpose:** what slice 2 has proven, what it has not, and where the evidence is. It also shows how a later slice continues the account, so the same obligation is never re-derived or silently dropped.
**Evidence:** `docs/publicdigit/platform/evidence/2026-11-15-pbdigit345-task13/` (README first).
**How the evidence is made:** `developer_guide/testing/08_isolated_runs_evidence_mutations_and_baseline.md`.

## Two lists, not one

| List | What it is | Slice 2 |
|---|---|---|
| **T-1 … T-26** (delta revision 3) | slice 2's own acceptance tests: routing, isolation, queue, relay, operator channel, outage, restore | **all passed** at `8b6fcd569`; per-test results in the JUnit logs (`05-t-matrix.txt`) |
| **D-193's 53** (`2026-11-11-platform-proof-obligation-register.md`) | the build gates of the **whole** Platform design: contacts, case grants, emergency route, D-170 copy, D-188 report, lifecycle, failure scenarios, INV-ACCESS-1 | **0 wholly proven; 3 partly (B-7, B-9, B-10); 3 blocked (E-7, E-8, F-1); 47 out of scope** (`01-d193-proof-obligation-account.md`) |

**Why so few of the 53:** the register was written for features slice 2 does not build (delta §T). **An out-of-scope obligation is not discharged.** It stays a gate for the slice that builds its subject.

## What the open parts mean for a developer

| Open | Backlog | Do not |
|---|---|---|
| B-7: no `SchemaState::Migrating` | **PBDIGIT-352** | add any command that migrates **operational** organisations before it exists |
| B-9 scenario 9: the database does not refuse or alert on a locator change | **PBDIGIT-353** | write `locator_*` anywhere but founding step 3; edit the registry by hand |
| B-10 / D-166: unencrypted, non-expiring drill copies; manual in-place restore | **PBDIGIT-351** | use `restore-drill` or the in-place recovery on production data |
| E-7 (X-2) | **PBDIGIT-349** | bypass `NoIdentityBindingsYet` (E-1 fails closed until Identity's bindings exist) |

## Continuing the account in a later slice

1. **Start from `01-d193-proof-obligation-account.md`; do not re-derive.** For each obligation the new slice builds the subject of, change its status in a **new** dated account, citing the previous one. Never edit the old account.
2. **Count only passing tests** that exercise the property on the real target. A class or a feature is not proof.
3. **Name every test**, with its evidence file and `SHA256SUMS`.
4. **Keep the four pre-existing architecture failures listed by name**, and never call the suite passing while they fail:
   - `Election\ElectionLifecycleStateConsistencyTest`;
   - `ElectionStateMachineConsistencyTest`;
   - `GovernanceRuntime\ConstitutionalBoundaryTest`;
   - `Phase_C25_SovereigntyLeakagePreventionTest`.

## Architecture checks done in task 13

- **Domain and Application of Platform:** no Laravel reference (scan in `06-architecture/`, plus `PlatformDomainPurityTest`).
- **Ratchet:** PBDIGIT-345 raised the ceilings by **4 documented entries only**: 197→201 baseline entries and 264→268 occurrences. All four are Infrastructure adapters behind Platform-owned ports onto Identity, guarded by `PlatformIdentityBoundaryTest`. **No rule or detector was removed.**
- **Full architecture suite:** 364 tests, the same 4 failures as at the baseline `5bd25c463`.

## Re-verifying the package (task 14)

```
python3 -I docs/publicdigit/platform/evidence/2026-11-15-pbdigit345-task14/verify-t14.py
```

**What it checks:**
- every guide indexed;
- every evidence seal over committed bytes;
- the JUnit results;
- the T-matrix re-derived;
- the 53-obligation account against the register;
- the backlog links;
- the 4 architecture failures;
- the recorded commits.

**How it reads:** committed content only (`git show HEAD:…`), so an uncommitted edit can neither pass nor fail it. Exit 0 = all PASS.

**When to run it:** run it again after any change to the evidence or the records. **If a check fails, find out whether the package or the script is wrong before changing either.** Task 14's first run failed four checks, all of them script defects; both runs are kept.

**Traceability:** D-193 (4) · delta revision 3 §T · D-166 · D-234 · PBDIGIT-345 tasks 13 and 14.

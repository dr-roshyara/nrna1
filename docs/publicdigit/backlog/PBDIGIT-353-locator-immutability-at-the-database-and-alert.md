# PBDIGIT-353: The database refuses, and alerts on, a locator change outside provisioning (D-193 B-9 scenario 9)

**Status:** OPEN (created 2026-11-15) · security hardening · not authorised · **Context:** Platform · **Kind:** missing control (accepted requirement)
**Found by:** PBDIGIT-345 task 13 (`…/2026-11-15-pbdigit345-task13/01-d193-proof-obligation-account.md`, B-9 scenario 9).

## Finding

Scenario 9 requires: "locator altered outside provisioning → **refused; alert (ISO-2); audit log**". In the application this holds:
- `LocatorAlreadyAssigned`;
- the only caller is founding step 3;
- the locator never comes from a request;
- two organisations can never share a database.

**But:**
1. `platform_served_organisations` has **no trigger** refusing an `UPDATE` of `locator_*` once set. An out-of-band SQL edit succeeds.
2. There is **no alert or audit entry** for such an edit.
3. Per-request routing checks `current_database()` against the registry's locator, but **not** the `organisation_identity` row. A locator re-pointed to an **unregistered** database (for example a leftover `scratch_restore_*` copy of *another* organisation) would be served until the next L-1 check.

## Business impact

**Someone with write access to the central database could silently redirect an organisation's members to another database.** Today this requires direct database access, which is itself outside the application.

## Scope (when authorised; test first)

- (a) a trigger: `locator_*` written once (NULL → value, during `provisioning`), never changed. The pattern is `organisation_identity_immutable`.
- (b) the refusal logged at **critical**, content-free (ISO-10).
- (c) optionally, `OrganisationConnectionFactory::establish` also verifies the `organisation_identity` row (one indexed read per operation). **This one needs a Principal decision on its cost.**

**Traceability:** D-193 register B-9 (scenario 9) · ISO-2 · D-193 F-13 · PBDIGIT-345 task 13.

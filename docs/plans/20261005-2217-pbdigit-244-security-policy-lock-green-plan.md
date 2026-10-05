# Plan — PBDIGIT-244 GREEN: security policy locked from ReadyForVoting (I9)

**Status:** ✅ complete — approved and implemented 2026-10-05 (PO decisions: whole PATCH refused · `null` = `[]` · fixture adaptations approved) · **Story:** `docs/publicdigit/backlog/PBDIGIT-244-security-policy-not-locked-from-ready-for-voting.md` · **RED:** `58b0ec769`

## Objective

Make the RED suite `SecurityPolicyLifecycleLockTest` pass. From ReadyForVoting on, a change to the interim security policy is refused and nothing is written. Enforce this at two layers, as PBDIGIT-213 C2 does for the admission policy (PO 2026-10-05).

## Background (facts [S] at `fc4e166db`)

- **The admission policy lock, which this plan reuses:**
  - Application layer: `ChangeVoterAdmissionPolicyHandler` throws `AdmissionPolicyLocked` (a `DomainException`) when the policy really changes and `isLocked()` is true. `VoterAdmissionPolicyController` turns that into `back()->withErrors([field => 'The admission policy cannot be changed once the election is ready for voting.'])`.
  - Model guard: `Election::booted()` → `static::saving`: `isDirty('voter_admission_policy') && ElectionLifecycle::of($election)->isLocked()` → `\LogicException`.
- **The lock predicate:** `ElectionLifecycle::of($election)->isLocked()` is true from ReadyForVoting on, including Suspended (`ElectionLifecycleEngineImpl::isLocked()`).
- **The security settings path:** `ElectionSettingsController::update()` runs the optimistic lock, then the active-election confirmation, then `detectChanges()`, then `$election->update($updateData)`, with no lifecycle check. The form sends **all** settings together: the security fields plus `no_vote_option_*`, `selection_constraint_*` and `agreed_to_settings`.

## Scope

**Interim policy boundary**, the three components from the RED (`ip_restriction_enabled` and `ip_restriction_max_per_ip`, `ip_whitelist`, `voter_verification_mode`). Q3 stays open; this plan adds no other field.

## Design

1. **One list of the policy fields.** Add `Election::SECURITY_POLICY_FIELDS` with the four fields; both layers read it, so when Q3 decides, one list changes. It is a plain constant, not a new framework.

2. **Domain exception.** Add `app/Contexts/Elections/Domain/Exceptions/SecurityPolicyLocked.php` (`DomainException`, `forElection()`). It has the same shape as `AdmissionPolicyLocked`.

3. **Application layer (owns the refusal).** In `ElectionSettingsController::update()`, after the optimistic-lock check:
   1. Compute the *effective* changes to `SECURITY_POLICY_FIELDS`, reusing `detectChanges()` but normalising `ip_whitelist` so that `null` and `[]` count as the same.
   2. If there is an effective change and `ElectionLifecycle::of($election)->isLocked()`, return `back()->withErrors([<first changed field> => 'The security policy cannot be changed once the election is ready for voting.'])`. **Nothing is written; the whole PATCH is refused.**
   3. If nothing changed and the election is locked, **leave the security fields out of the update data**. The model guard then never sees an unchanged field as dirty (for example a stored `null` resubmitted as `[]`). The rest of the form (no-vote option, selection constraints) saves as today, because Q3 decides those.

   *Placement note:* this is the HTTP/Laravel layer. Unlike the admission policy, the settings form has no command handler. Adding one would be a refactor of the settings form, which is out of scope. The check stays small and reads the same lifecycle facade the admission lock uses.

4. **Model guard (defence in depth, no user messaging).** In `Election::booted()` `static::saving`, next to the admission guard: if any `SECURITY_POLICY_FIELDS` field is dirty and `isLocked()`, throw `\LogicException('The security policy cannot change once the election is ready for voting.')`. It has the same form as the admission guard.

## Tests

**RED suite unchanged:** `SecurityPolicyLifecycleLockTest` 18/18 must pass. One fixture adaptation is needed: its baseline write uses `forceFill()->save()` on already-locked elections, which the new guard would refuse. It changes to a query-builder write (fixture only; the assertions stay the same). **This needs your approval.**

**New tests in the same suite:**
- **Model guard:** a direct `update()` of each component on a locked election throws `\LogicException`, and the value is unchanged. An unlocked election accepts it.
- **No false refusal:** on a locked election, a PATCH that resubmits the unchanged security values (including `ip_whitelist` stored `null`, sent `[]`) and changes only `no_vote_option_enabled` succeeds.
- **Refusal shape:** a locked election gets an error on the changed field, with nothing written (neither the security field nor any other field in the same PATCH).

## Impact: tests that may break, verified at GREEN, never adapted silently

These suites change the security fields on elections and may hit the new lock:
- `ElectionSettingsControllerTest`
- `ElectionSettingsTest`
- `VoterVerificationTest`
- `ElectionSettingsServiceTest` (Feature and Unit)
- `ElectionIpRestrictionTest`
- `ElectionSettingsControllerAuditTest`
- `VotingSecurityVerdictAdapterTest`. Its `forceFill(['ip_whitelist' => …])->save()` on a voting election is a known-defect characterization, so the guard will refuse it.

Any test that fails because it changes a locked election's security policy will be reported with its cause. A fixture-only adaptation is proposed for your approval, as was done for `SimpleStoreTest` in PBDIGIT-213 C3. Assertions are never weakened.

## Verification

1. `SecurityPolicyLifecycleLockTest` all green.
2. `VoterAdmissionPolicyEndpointTest`, so the admission lock is unchanged.
3. The suites listed under Impact.
4. The 97-suite regression compared per test with the `fc4e166db` run. The baseline is the last regression (`5cbba6f15`) plus the new suite; production is unchanged since then.
5. Report, then STOP before committing.

## Risks

- **A form that submits everything:** an unchanged-but-retyped value could trigger a false refusal or a guard exception. This is mitigated by normalising and leaving security fields out of the update on locked elections, and covered by the no-false-refusal test.
- **Other writers:** no production code outside the settings controller writes these fields (search at `fc4e166db`). Seeders and console commands were not searched; I will check them at GREEN.
- **Q3:** once Q3 decides, fields may be added to `SECURITY_POLICY_FIELDS`. This GREEN's completeness claim is limited to the interim boundary.

## Open questions for the PO

1. Is refusing the **whole** settings PATCH acceptable when a security field changes on a locked election? The alternative is saving the non-security fields and refusing only the security ones.
2. Do you approve the RED fixture adaptation (baseline written via the query builder)?
3. Should `null` and `[]` count as the same `ip_whitelist` value for change detection?

## Out of scope

- Q3 and any further fields (`max_votes_per_ip`, `network_binding_strategy`, …).
- PBDIGIT-240.
- `ValidateVotingIp`, `resolveIpBlock`, `verifyCode()`.
- The whitelist defects.
- A command handler for the settings form.
- UI changes beyond showing the returned error.

**Traceability:** ADR_20261005_0950 §5 I9 · PBDIGIT-213 C2 (admission lock: handler, exception, model guard) · completion matrix §I9 · RED `58b0ec769`.

## Progress

- GREEN implemented as designed: `Election::SECURITY_POLICY_FIELDS` (exactly the four fields) + model guard; `SecurityPolicyLocked`; refusal and field stripping in `ElectionSettingsController::update()`.
- `SecurityPolicyLifecycleLockTest` 26/26 (18 RED cases + 8 new).
- Impact suites before (`fc4e166db`, worktree) vs after: one status change — `VotingSecurityVerdictAdapterTest` whitelist known-defect fixture (model write on a voting election). Repaired fixture-only after the regression (PO-approved), suite back to baseline (8 pass, 22 assertions).
- 97-suite regression vs `5cbba6f15`: 578 / 335 / 19 — the one change above; no other status change.
- Other writers of these fields: none found in `app/` outside the settings controller, and none in `database/seeders` or `app/Console` (search 2026-10-05).

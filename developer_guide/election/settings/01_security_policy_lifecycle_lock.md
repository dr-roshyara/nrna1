# 01 — The security policy is locked from ReadyForVoting (I9, PBDIGIT-244)

**Story:** PBDIGIT-244 · **RED:** `58b0ec769` · **GREEN:** this step's commit · **Plan:** `docs/plans/20261005-2217-pbdigit-244-security-policy-lock-green-plan.md` · **Decision:** ADR_20261005_0950 §5 I9

## Purpose

I9: *"The security policy is immutable from ReadyForVoting (like the admission policy) — the policy boundary itself is Q3."* Before this step, an election's Chief could change the IP restriction, whitelist and verification mode while voting was already running; the change was stored. Now a change is refused once the election is locked.

**Scope:** I9 is enforced for the **currently defined four-field boundary** (PO 2026-10-05). Whether more settings belong to the security policy (e.g. `max_votes_per_ip`, `network_binding_strategy`) is **Q3** — open.

## The boundary — one list

```php
// app/Models/Election.php
const SECURITY_POLICY_FIELDS = [
    'ip_restriction_enabled',
    'ip_restriction_max_per_ip',
    'ip_whitelist',
    'voter_verification_mode',
];
```

Both layers read this list. When Q3 decides, change it here only.

**Locked** means `ElectionLifecycle::of($election)->isLocked()` — ReadyForVoting, VotingActive, Suspended and later. Draft, SetupAdministration and SetupNomination are editable.

## Two layers (the PBDIGIT-213 C2 pattern)

| Layer | Where | Behaviour |
|---|---|---|
| Application (owns the refusal) | `ElectionSettingsController::update()`, right after the optimistic-lock check | A real change to a policy field on a locked election throws `SecurityPolicyLocked` (domain exception) → `back()->withErrors([<first changed field> => 'The security policy cannot be changed once the election is ready for voting.'])`. **The whole PATCH is refused** — no field of that request is written, `settings_version` unchanged (PO decision) |
| Model guard (defence in depth) | `Election::booted()` → `static::saving`, next to the admission guard | `isDirty(SECURITY_POLICY_FIELDS)` on a locked election → `\LogicException`. No user messaging — it catches any other writer |

## How "a real change" is decided

The settings form submits every setting together, so an untouched value can arrive re-typed. `changedSecurityPolicyFields()` compares normalised values:

- `ip_restriction_enabled` — by boolean value
- `ip_restriction_max_per_ip` — by integer value
- `ip_whitelist` — `null` and `[]` are the same value (PO decision)
- `voter_verification_mode` — by string

On a locked election with **no** real change, the policy fields are removed from the update data, so the model guard never sees a re-typed value as dirty and the rest of the form (no-vote option, selection constraints) saves as before.

## Testing

`tests/Feature/Election/SecurityPolicyLifecycleLockTest.php` — 26 cases, synthetic data:

| Group | Cases |
|---|---|
| Editable before ReadyForVoting | 3 components × Draft / SetupAdministration / SetupNomination — change stored |
| Locked from ReadyForVoting | 3 components × ReadyForVoting / VotingActive / Suspended — refused, value unchanged, no success |
| Model guard | direct `update()` refused on a locked election (×3), accepted on an unlocked one (×3) |
| No false refusal | locked election, whitelist stored `null` re-sent as `[]`, only the no-vote option changed → saved |
| Whole-PATCH refusal | locked election, verification mode + no-vote option changed → error on `voter_verification_mode`, nothing written |

The baseline is written with the query builder: the model guard (correctly) refuses security-policy writes on an already locked election, so fixtures must not go through the model.

**Validation evidence (2026-10-05):** suite 26/26 · 97-suite regression vs `5cbba6f15`: one status change, `VotingSecurityVerdictAdapterTest` whitelist known-defect test — its fixture wrote `ip_whitelist` through the model on a voting election; repaired fixture-only (query builder, PO-approved), suite back to its baseline (8 pass, 22 assertions). No observed regression relative to that baseline after the repair; no broader claim.

## Pitfalls

- **Test fixtures:** never set a policy field with `update()` / `forceFill()->save()` on a locked election — use `DB::table('elections')`. A `\LogicException` "The security policy cannot change…" in a test means exactly this.
- **New writers** of these fields must check the lock themselves and own the user-facing refusal; the model guard is a backstop, not a message.
- **Adding a field** to the policy is a Q3 decision, not an implementation choice.
- Do not move the security policy under voter admission — same pattern, different concern.

## Still open

- **Q3** — the complete security-policy surface.
- I3 / PBDIGIT-240 — one security authority (separate).

**Traceability:** ADR_20261005_0950 §5 I9, §9 Q3 · PBDIGIT-244 · completion matrix §I9 · admission-lock pattern PBDIGIT-213 C2 (`ChangeVoterAdmissionPolicyHandler`, `AdmissionPolicyLocked`, Election model guard).

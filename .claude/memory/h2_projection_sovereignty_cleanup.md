---
name: h2-projection-sovereignty-cleanup
description: "H.2 completed — removed procedural sovereignty residue (registered_ip, current_ip) from frontend Inertia props; frontend now receives constitutional outcome semantics without procedural legitimacy mechanics"
metadata: 
  node_type: memory
  type: project
  phase: D.0 Constitutional Retirement Sequencing
  originSessionId: 9059105f-c80d-49a0-8438-aee9dca2bed8
---

# H.2 — Projection Sovereignty Cleanup Complete

**Date:** 2026-05-28

## Architectural Significance

The runtime removed projection-layer procedural sovereignty residue from the frontend contract. Before H.2, the frontend could infer procedural legitimacy topology from IP fields in Inertia props. After H.2, the frontend receives constitutional outcome semantics without procedural legitimacy mechanics.

## Classification Model

| Type | Handling |
|---|---|
| Constitutional evidence (user_name, denial_type) | Preserved |
| Operational metadata (valid, skip_reason) | Preserved where appropriate |
| Procedural sovereignty residue (registered_ip) | Retired |
| Hidden authority vocabulary (current_ip, original_ip) | Removed |

## Critical Success: No Rename-Only Migration

The cleanup correctly avoided replacing `registered_ip` with disguised procedural euphemisms (trusted_ip, validated_ip, approved_ip). That would have been sovereignty semantics surviving under renamed vocabulary.

## Election Settings Isolation Preserved

Per-election IP governance configuration (Election Settings page) correctly isolated from per-user procedural legitimacy residue (validateVotingIpWithResponse Inertia props).

## Files Changed

- `app/Helpers/helpers.php` — removed current_ip and registered_ip from all 3 return paths
- `resources/js/Pages/Vote/VoteDenied.vue` — removed 3 props, IP template section, copy-text IP lines
- `tests/Unit/Helpers/ValidateVotingIpWithResponseReturnTest.php` — 7 tests (22 assertions)
- `tests/Feature/Vote/VoteControllerPropTest.php` — 2 tests (5 assertions)

## Regression Verified

- IpEvidenceLegacyEquivalenceTest: 10/10 passing
- VoteControllerConstitutionalTest: 2 pre-existing failures (302/405) confirmed unrelated to H.2

## Convergence Trajectory

D.0.1 (shadow sovereignty activation) → D.0.2 (convergence certifiability) → H.2 (projection sovereignty cleanup) — all completed without destabilizing replay or equivalence invariants.

The system is evolving from procedural legitimacy explanation toward constitutional legitimacy explanation.

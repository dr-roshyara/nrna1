# Engineering Platform Repair Plan — Pre-Push Governance Chain

**Created:** 2026-07-26 20:56
**Status:** APPROVED (ARB 2026-07-26) — with three amendments folded: EG-001 classified as workstation configuration; EG-001 gated on reproducibility verification before any removal; Repair Acceptance Gate added (positive + negative validation before workstream close)
**Role:** Chief Engineer
**Input (immutable):** `docs/implementation/20260726_PrePush_Governance_Gate_Audit.md` (findings F-GATE-1..7)
**Commissioned by:** ARB instruction of 2026-07-26: *Discover → Classify → Plan → Approve → Implement → Verify* — never Discover → Fix.

---

## Objective

Restore the engineering platform's push path and make the design-governance measurement instruments trustworthy (fail-closed, actually measuring), as tracked engineering work — explicitly NOT hidden implementation work, NOT architecture work, and NOT part of WP-1.

## Background

The audit found: the design-governance shell gates run on an impostor `jq` (npm jQuery wrapper) and fail open on unreadable config; the token rules have never matched (BRE/ERE defect); thresholds are uncalibrated; and the only correctly-functioning gate (Role & Permission) blocks pushes based on local DB state unrelated to pushed content. `composer merge-gate` (WP-1's acceptance instrument) is verified independent and healthy. **Causality (ARB-refined wording): WP-1 execution is blocked by engineering-platform defects.**

## Scope

**IN:** the four repair work items EG-001..EG-004 below (pre-push chain, its scripts, its configs, its policy placement).
**OUT:** architecture, ADRs, roadmap sequencing, WP-1 content, merge-gate/quality-gate (healthy — untouched), the pre-commit hook's advisory-only design (F-GATE-7, record-only), any new governance concepts.

**Naming note (collision avoidance, flagged not silent):** the backlog already contains **ENG-004 Mutation Ratchet 1**. This workstream uses the distinct prefix **EG-** (Engineering-Gate repair) precisely so EG-004 ≠ ENG-004; if the ARB prefers a different prefix, it is a rename, not a re-plan.

---

## The Workstream — Engineering Platform Repair (EG)

### EG-001 — Environment: authentic jq (repairs F-GATE-1)

| | |
|---|---|
| **Class** | **Workstation configuration** — Engineering Platform / Environment / **machine-local / no repository changes** (sole exception: the docs-only hazard note in step 3, which exists precisely because this is per-machine work another developer will need to repeat). NOT project work; tracked here so it cannot become hidden work. |
| **Decision authority** | Pure engineering — no ARB decision needed |
| **Actions** | **0) Reproducibility verification (ARB amendment — gate for the rest):** before removing anything, re-verify on the live machine: PATH resolution (`type -a jq`), executable location and identity (shim target + its package.json = npm "jq" jQuery wrapper), and the failure behavior (valid query → empty output + exit 0). Removal proceeds ONLY if the diagnosis reproduces. 1) `npm -g uninstall jq` (removes the jQuery-wrapper impostor). 2) Install real jq (e.g. `winget install jqlang.jq`, or place `jq.exe` on PATH). 3) Document the impostor hazard + a self-check command in `scripts/README.md` so other dev machines can verify — the only repo-touching part, docs-only. |
| **Acceptance criteria** | Post-install identity re-verified (`type -a jq` resolves to the real binary; `jq --version` prints `jq-1.x`); behavior verified (`jq -r '.baseline' scripts/design-rules.json` prints `613`); the `padLevels` node warnings no longer appear in gate output. |
| **Dependencies** | None. First — everything downstream is only verifiable with a real jq. |

### EG-002 — Shell-gate reliability: fail-closed (repairs F-GATE-2, F-GATE-6)

| | |
|---|---|
| **Class** | Repository repair (script changes) — split into two decision grades |
| **EG-002a — config validation (pure engineering)** | After every jq parse in `design-check.sh`, `check-design-tokens.sh`, `component-audit.sh`, `structure-check.sh`: assert the value is a non-empty integer (where numeric); on failure, print a diagnostic naming the config key and **exit non-zero**. Restores the scripts' evident intent; no policy change. |
| **EG-002b — guard behavior (ARB decision required)** | `.husky/pre-push` currently *skips all governance with exit 0* when jq is missing, and accepts any binary named jq. Proposed change: verify authenticity (`jq --version` matches `^jq-`) and **block with instructions** when absent/fake. This changes documented behavior (today's comment says "Skipping design governance checks") — a governance-behavior change, so it needs an explicit ARB yes/no, even though the diff is small. |
| **Acceptance criteria** | Falsifiability both ways: (1) with config intact, gates produce numeric baselines/thresholds and zero `integer expression expected` errors; (2) with a deliberately corrupted copy of the config (test-only), the gate **fails loudly** instead of passing; (3) with jq renamed away (test-only), pre-push blocks rather than skips (if EG-002b approved). |
| **Dependencies** | EG-001 (verification needs real jq). Can be authored in parallel; verified after. |

### EG-003 — Role-gate placement (disposes F-GATE-5) — **the actual push blocker**

| | |
|---|---|
| **Class** | Policy placement decision — **ARB decision required first**; implementation afterwards is trivial engineering |
| **Options for the ARB (audit §Gate 4)** | **(i) RECOMMENDED: move the gate out of pre-push** into deployment/CI verification against a seeded database — a content-independent runtime-state check cannot give consistent pre-push verdicts across machines/branches. **(ii)** Keep in pre-push with an explicit "environment not seeded → SKIP with warning" mode (keeps local signal, loses enforcement). **(iii)** Keep as-is and seed the dev DB (`ElectionPermissionSeeder`) — fixes this machine today, breaks again on every fresh clone. Options compose: (i) + optionally seeding dev for local convenience. |
| **Acceptance criteria** | After the ruling is implemented: a docs-only commit pushes without the role gate misfiring; the role/permission policy is still enforced at the ruled location (CI/deploy) with the same required-roles list unchanged. |
| **Dependencies** | None technically — this is the minimal unblock for the push path. The pending snapshot commit `5e708a586` also waits on it (or on an ARB-sanctioned one-time `--no-verify`, which this plan surfaces as an explicit interim decision, not a habit). |

### EG-004 — Token-rule measurement + calibration (repairs F-GATE-3 + F-GATE-4, **coupled — never separately**)

| | |
|---|---|
| **Class** | Repository repair (scripts) + policy recalibration (config) — **mixed: mechanics are engineering; the new numbers are an ARB/design-governance decision** |
| **Actions** | 1) Switch rule matching to `grep -E` (ERE, as the patterns are written). 2) Apply `allowlist_patterns` in counting (currently read, never used). 3) **In the same slice**, measure the true counts (audit evidence: bg-color 903, text-color 1913 alone) and bring a re-baselining proposal (new baseline, new threshold, ratchet schedule) to the ARB — the current 613/150 are fictions of the broken instrument. 4) Only after the ARB ratifies the numbers does strict enforcement resume. |
| **Sequencing rule (binding within this plan)** | The regex fix and the recalibration land **together**; fixing matching against the stale threshold would hard-block every push (the audit's "unpassable wall" risk, F-GATE-4). |
| **Acceptance criteria** | Gate counts match an independent manual `grep -E` spot-check; a synthetic violation added in a test copy is detected (falsifiability); strict mode passes at the ratified baseline; removing the synthetic violation returns the count to baseline. |
| **Dependencies** | EG-001 + EG-002a (a trustworthy parse layer is a precondition for trusting any measured number). |

### Not repaired (recorded)

- **F-GATE-7** (pre-commit advisory-only): matches its own documented design; record-only, no action.

---

## Repair order (proposed)

```
EG-001 (workstation config, no ARB)   ── first; OPENS with reproducibility verification —
                                          removal only after the diagnosis reproduces
EG-003 (ARB ruling → tiny impl)       ── unblocks the push path; pending 5e708a586 rides on it
EG-002a (fail-closed parsing)          ── engineering; verified against real jq
EG-002b (guard fail-closed)            ── awaits its ARB yes/no; lands with EG-002a if approved
EG-004 (ERE + recalibration, coupled)  ── last; needs ARB-ratified numbers
```

**Minimal set to unblock WP-1 execution: EG-001 + EG-003.** EG-002/EG-004 restore *trustworthiness* of the design-measurement instruments and can proceed as tracked engineering work without holding WP-1 hostage — WP-1 is a backend slice whose acceptance instrument (`composer merge-gate`) is verified healthy; the design gates do not govern its content, only its transport. **Recommendation: WP-1 opens after EG-001 + EG-003 are verified; EG-002/EG-004 continue in parallel under this workstream.** (ARB may rule more conservatively; the dependency facts above are what the evidence supports.)

## Decision summary for the ARB (what needs a ruling vs. what doesn't)

| Item | ARB decision needed? |
|------|---------------------|
| EG-001 environment repair | No — pure engineering |
| EG-002a fail-closed config validation | No — restores evident intent |
| EG-002b pre-push guard blocks on missing/fake jq | **Yes** — documented behavior change |
| EG-003 role-gate placement (options i/ii/iii) | **Yes** — policy placement |
| EG-004 mechanics (ERE, allowlists) | No — defect repair |
| EG-004 new baseline/threshold numbers | **Yes** — design-governance policy |
| One-time `--no-verify` for pending `5e708a586` | **Yes** — explicit interim sanction or refusal |
| WP-1 opens after EG-001+EG-003 (vs. after all EG) | **Yes** — sequencing ruling |

## Repair Acceptance Gate (ARB amendment — workstream close-out condition)

Before the EG workstream may be declared complete, **demonstrate — not assert — that the instruments are trustworthy**, with both polarities of evidence:

1. **Reproduce the original failing scenario:** the same docs-only push path that failed on 2026-07-26 is exercised end-to-end and now completes (or is blocked ONLY by a rule the ARB has ratified).
2. **Negative validation per repaired gate:** intentionally break the input (corrupted config copy; jq renamed away; synthetic token violation; missing role in a test context) → the gate **blocks, loudly, naming the cause**.
3. **Positive validation per repaired gate:** restore the input → the gate **passes** and its printed numbers match an independent manual check.
4. The break→block→restore→pass cycle is recorded as evidence in the workstream close-out (same discipline as the fitness suites' falsifiability proofs, e.g. 7B).

An instrument that has never been seen to fail on bad input has not been shown to measure anything — this gate encodes that.

## Task checklist

- [x] ARB approval of this plan (granted 2026-07-26, three amendments folded)
- [x] EG-001 ✔ DONE 2026-07-26: step-0 verification reproduced on all 4 axes (PATH · identity npm-jq-1.7.2 jQuery wrapper · empty-output/exit-0 probe · no other jq) → impostor removed (`npm uninstall -g jq`, 146 pkgs) → real jq-1.8.1 installed (winget, WinGet/Links) → acceptance PASS (identity re-verified; `.baseline`→613, threshold→150, 9 rules iterate; zero `integer expression` errors; zero padLevels noise; component audit compares true baselines 92/75/15/22 — all ✅ legitimately) → hazard + self-check documented in `scripts/README.md`. Predicted residue confirmed visible: token rules count 0 via BRE (F-GATE-3 → EG-004, untouched by design).
- [ ] Repair Acceptance Gate: break→block→restore→pass evidence recorded per repaired gate + original failing scenario re-run
- [ ] EG-003: ARB ruling recorded → implemented → docs-only push verified end-to-end
- [ ] EG-002a: fail-closed parsing in 4 scripts · falsifiability demonstrated
- [ ] EG-002b: (if approved) guard blocks on missing/fake jq
- [ ] EG-004: ERE+allowlists · true counts measured · re-baseline proposal → ARB → ratified numbers land with the fix
- [ ] Close-out: defect register F-GATE-1..6 statuses updated in the audit doc · session log · this plan marked complete

## Progress

0/7 — plan awaiting approval.

## Risks

- **Unpassable-wall risk** if EG-004's two halves are split (mitigated: binding coupling rule above).
- **Other dev machines** carry the same impostor-jq hazard (mitigated: EG-001 documentation + EG-002b authenticity check if approved).
- **Scope creep into a design-migration project:** re-baselining will reveal ~thousands of token violations; this plan only restores the *instrument* — migrating the violations stays with the existing design-migration phases, not EG.

## Open questions

The four ARB rulings in the decision summary. Nothing else blocks.

## Next actions

STOP. Await ARB approval of this plan. On approval: EG-001 first.

---

**Traceability:** derives entirely from `20260726_PrePush_Governance_Gate_Audit.md` (F-GATE-1..7); commissioned by the ARB's plan-before-repair instruction (2026-07-26); ES-004.2 naming; workstream prefix EG- chosen to avoid the existing ENG-004 backlog id.

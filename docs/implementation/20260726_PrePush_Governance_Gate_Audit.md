# Engineering Platform Governance Audit — Pre-Push Gate Chain

**Commissioned by:** Chief ARB (governance audit commission, 2026-07-26)
**Role:** Chief Engineer — evidence-gathering and root-cause analysis ONLY
**Trigger:** failed `git push` of docs-only commit `5e708a586` (executive status snapshot)
**Scope:** every gate executed by `.husky/pre-push` → `scripts/verify.sh`
**Explicitly out of scope:** repairs, policy changes, architecture changes, WP-1

---

## Executive Summary

**The pre-push design-governance chain is NOT currently trustworthy.** Five of six gates passed **for the wrong reasons** (fail-open on unreadable configuration), and the one gate that blocked the push (Role & Permission) was the **only gate that executed its policy correctly** — but it evaluates local database state, not the content being pushed.

Root cause of the systemic failure: **the `jq` on PATH is an impostor.** `C:\Users\nabra\AppData\Roaming\npm\jq` is the npm package `jq` v1.7.2 — *"Server-side jQuery wrapper for node"* (jsdom-based, 2011-era) — not the JSON processor. It accepts any arguments, prints **nothing**, and exits **0**. Every shell gate that parses its JSON config through `jq` therefore receives empty strings for baseline/threshold/rule-count, all numeric comparisons error out (`integer expression expected`) and evaluate as *false*, and every enforcement branch happens to sit on the false side — so the gates **pass silently on garbage input**. The real `jq` binary is not installed anywhere on this machine.

Two further latent defects were found that predate today and are masked by the first: the token-rule regexes are ERE but the scripts call BRE `grep` (the rules have **never matched anything** through these scripts), and the configured thresholds are therefore uncalibrated against real counts.

**Critically: the WP-1 acceptance instrument is unaffected.** `composer merge-gate` (Architecture fitness → Deptrac → greenfield PHPStan → GreenfieldCore regression) is pure PHP tooling with no dependency on `jq` or any of the audited scripts. The defect is confined to the frontend design-governance chain and the push transport it guards.

**Verdict: REQUIRES REPAIR BEFORE WP-1** — narrowly scoped: the push path must work and the design gates must fail closed. See Engineering Recommendation.

---

## Gate-by-Gate Findings

### Gate 1 — Design Token Compliance (`design-check.sh --strict`)

| | |
|---|---|
| **Expected** | Parse `design-rules.json` (baseline 613, threshold 150, 9 rules); count violations per rule; in `--strict`, exit 1 when total > threshold. |
| **Observed** | Baseline/Threshold/Phase printed empty; zero per-rule lines (rule loop never ran — `RULE_COUNT` empty ⇒ arithmetic 0); total = 92 (raw `<button>` count only — plain-string grep, no jq); `[: : integer expression expected` at lines 153/200/203/216/225; contradictory "STRICT ENFORCEMENT READY (92 ≤ )" then "✅ All checks passed! (92 violations, threshold: )". |
| **Root cause** | Impostor `jq` (F-GATE-1): every `jq -r` returns empty/exit 0. `set -euo pipefail` does not catch it (exit 0). All `[ N -gt "" ]` tests error → false → the pass branches. |
| **Was config present?** | Yes — `scripts/design-rules.json` is valid and complete (verified). Parsing failed, not configuration. |
| **Should it have blocked?** | Indeterminate by design intent: with working jq **and** current script, total = 92 ≤ 150 → pass. With working jq **and** the intended ERE matching (F-GATE-3), total ≫ 150 → block. The gate currently measures almost nothing. |
| **Trustworthy today?** | **No** — fail-open, contradictory output, measures only one of ten rules. |

### Gate 2 — Quick Token Count (`check-design-tokens.sh`)

Same root cause, same mechanics: threshold empty, rule loop skipped, total = 92 (buttons only), `[ 92 -gt "" ]` errors → false → "PASS". **Not trustworthy** — duplicate of Gate 1's failure mode.

### Gate 3 — Component Audit (`component-audit.sh --strict`)

| | |
|---|---|
| **Expected** | Compare raw tag counts against `ui-components.json` baselines (button 92 / input 75 / select 15 / textarea 22, recorded 2026-06-13); exit 1 on regression in strict mode. |
| **Observed** | Per-tag lines showed "❌ REGRESSION … (baseline: 0)" while the final verdict said "✅ passed — no regressions". |
| **Root cause** | Same impostor jq: baselines empty. The **display** check (line 84, `[ count -le "" ]`) errors → false → prints ❌ REGRESSION (with `printf %d` rendering empty as 0); the **enforcement** check (line 230, `[ count -gt "" ]`) errors → false → no regression flag. The two broken tests place their false-branches on opposite labels — the contradiction is fully mechanical, not logical. |
| **Material finding** | Current live counts (92/75/15/22) **exactly equal** the recorded baselines. With a working jq, this gate would have passed **legitimately**. No actual regression exists. |
| **Trustworthy today?** | **No** (fail-open), but its policy data is intact and its verdict would coincidentally have been correct. |

### Gate 4 — Role & Permission Governance (`check_roles.php --strict`) — THE BLOCKING GATE

| | |
|---|---|
| **Expected** | Boot Laravel, verify Spatie roles (member, election-committee, constitutional-council, auditor, admin) and election permissions exist and are assigned; exit 1 if missing. |
| **Observed** | No roles, no permissions found; exit 1 → push blocked. |
| **Root cause** | The dev database `publicdigit` (pgsql) genuinely contains **0 roles and 0 permissions** (verified read-only). Spatie `teams` = false, so no tenancy-scoping artifact. A seeder defining these roles exists (`database/seeders/ElectionPermissionSeeder.php`) but has not been run against this database. |
| **Classification (per commission options)** | **A + B combined:** (A) expected for current project state — the greenfield/EPIC-004 track never required seeding this DB; **(B) the gate assumes runtime data in a pre-push context** — it evaluates *local database state*, not *the content being pushed*. A documentation-only commit was blocked by DB state unrelated to the diff. Not C (config error) and not D (genuine governance failure — the platform's RBAC code is unchanged; only seed data is absent). |
| **Executed correctly?** | **Yes — the only gate in the chain that did.** Script logic, policy evaluation, and exit code are all faithful. |
| **Belongs in pre-push?** | Recommendation (classification only, no change made): **not in its current form for the current phase.** A gate keyed to mutable local runtime state cannot give consistent verdicts across machines or branches; its natural home is deployment verification / CI against a seeded database, or pre-push only with an explicit "environment not seeded" skip-with-warning mode. That is a policy decision for the ARB, not made here. |

### Gate 5 — Domain Purity (warning-only)

Passed legitimately — pure grep-based scan, no jq dependency, found the Domain layer clean. **Trustworthy.**

### Gate 6 — DDD Structure Visibility (warning-only)

Same impostor-jq symptom (`integer expression expected` at lines 59/116, empty coverage table), but explicitly informational ("no CI failures"). Broken but harmless by design. Not trustworthy as information; irrelevant as enforcement.

### The `padLevels` node warnings

Emitted by the impostor jq package's ancient dependency chain on every invocation. Noise only; disappears when F-GATE-1 is resolved.

### Pre-push guard itself (`.husky/pre-push`)

`command -v jq` was satisfied **by the impostor** — the guard cannot distinguish a real jq from a name-collision. Worse, if no `jq` exists at all, the hook prints a warning and **exits 0, skipping all governance** — the guard is fail-open by design (F-GATE-6).

---

## Defect Register

| ID | Severity | Affected | Description | Impact | Recommended priority |
|----|----------|----------|-------------|--------|---------------------|
| **F-GATE-1** | CRITICAL | machine toolchain (`AppData\Roaming\npm\jq`) | npm package `jq` 1.7.2 (server-side jQuery wrapper) shadows the JSON processor; returns empty + exit 0 for every query; real jq not installed | All JSON-configured shell gates run on empty config; systemic silent fail-open | **P1** — environment repair (remove npm `jq`, install real jq) |
| **F-GATE-2** | HIGH | `design-check.sh`, `check-design-tokens.sh`, `component-audit.sh`, `structure-check.sh` | Numeric comparisons against possibly-empty variables; test errors evaluate false; every enforcement branch sits on the false side ⇒ **fail-open on unreadable config** | Gates pass when they cannot read policy; independent hazard even after F-GATE-1 (any future parse failure repeats it) | **P1** — fail-closed validation (assert integers after parse; abort otherwise) |
| **F-GATE-3** | HIGH | `design-check.sh`, `check-design-tokens.sh` | Rule regexes are ERE (`(a\|b)` alternation) but scripts invoke BRE `grep` (no `-E`); token rules match 0 — always have. Verified: BRE 0 vs ERE 903 (bg-color) / 1913 (text-color). Additionally `allowlist_patterns` are read from config but never applied to counting | The token-compliance gate has never measured token compliance through these scripts | **P2** — script repair, after F-GATE-1 |
| **F-GATE-4** | MEDIUM | `design-rules.json` calibration | Baseline 613 / threshold 150 are uncalibrated against real ERE counts (two rules alone ≈ 2,800). Repairing F-GATE-1/3 without recalibration hard-blocks every push | Repair sequencing risk: a "fixed" gate becomes an unpassable wall | **P2** — policy/ARB decision at repair time (re-baseline, then ratchet) |
| **F-GATE-5** | MEDIUM | `check_roles.php` placement | Gate evaluates local DB runtime state in pre-push; dev DB unseeded (0 roles / 0 permissions; seeder exists, never run) — **this is the defect that actually blocked the push** | Any push from an unseeded environment is blocked regardless of content | **P2** — ARB placement decision (move to deploy/CI verification, or seed dev DB, or skip-with-warning) |
| **F-GATE-6** | LOW | `.husky/pre-push` | `command -v jq` guard satisfied by impostor; absent jq ⇒ skip all checks with exit 0 (fail-open guard) | Governance silently absent on machines without jq | **P3** — fold into F-GATE-2's fail-closed repair |
| **F-GATE-7** | INFO | pre-commit hook | `npx lint-staged … \|\| true` — pre-commit is advisory-only and can never block (matches its header comment; noted for completeness) | None (by design), recorded so the fail-open pattern is visible in one place | record only |

---

## Engineering Recommendation

**WP-1 execution is blocked by engineering-platform defects — the architecture, the DDD model, and the merge-gate are NOT what stops WP-1.** *(Wording refined per ARB review of this audit: the original "REQUIRES REPAIR BEFORE WP-1" overstated the evidence — WP-1's acceptance instrument is healthy; what is broken is the engineering platform's push path and the trustworthiness of the design-governance measurements.)*

Evidence for the split verdict:

1. **`composer merge-gate` — the WP-1 acceptance instrument — has zero dependency on any audited component** (verified: PHPUnit Architecture suite → Deptrac shim → greenfield PHPStan → GreenfieldCore suite; pure PHP/composer). The trusted computing base for slice acceptance is unaffected.
2. **But WP-1 cannot complete its lifecycle:** slice delivery requires pushing commits, and every push is currently blocked by F-GATE-5 (and would be silently *unprotected* on the design side even when it passes, per F-GATE-1/2/3). Starting WP-1's RED locally is technically possible; finishing the slice is not.
3. **Gates that pass for wrong reasons are worse than gates that fail:** the design chain currently *reports* enforcement ("STRICT ENFORCEMENT READY") while enforcing nothing. Under the program's own standard — measurement instruments must be trustworthy before their measurements govern (F-7D-2 precedent: a measurement that fails evidence validation is rejected) — this chain fails validation.

**Recommended repair sequence (for a separate, explicitly authorized repair commission — NOT executed in this audit):**
1. F-GATE-1: remove the npm `jq` impostor; install real jq (environment, no repo change).
2. F-GATE-2/6: make gates fail-closed on unparseable config (small script change, repo).
3. F-GATE-5: ARB placement ruling for the role gate (policy), and/or seed the dev DB.
4. F-GATE-3/4: repair ERE matching **together with** re-baselining (policy + script, sequenced to avoid the unpassable-wall state).

Interim note: `git push --no-verify` exists as a documented bypass in the hook itself; whether the ARB sanctions its one-time use for the already-committed docs snapshot (`5e708a586`) before repairs land is the ARB's call, not this audit's.

**Consistency observation for the record:** this finding is the same *class* as F-PB006-1 (a missing/defective platform capability discovered by attempted use, classified before repair) — the process is behaving as designed: the push failure was evidence, the audit classified it, repair awaits authorization.

---

## Traceability

Evidence gathered read-only on 2026-07-26: `.husky/pre-push`, `.husky/pre-commit`, `scripts/verify.sh`, `scripts/design-check.sh`, `scripts/check-design-tokens.sh`, `scripts/component-audit.sh`, `scripts/check_roles.php`, `scripts/design-rules.json`, `scripts/ui-components.json`, `composer.json` (merge-gate), npm global `jq` shim + package.json, live grep BRE/ERE counts, read-only DB counts (`roles`=0, `permissions`=0, db=pgsql/publicdigit, spatie teams=false). Commission: ARB governance-audit instruction of 2026-07-26 (diagnosis separated from repair). No file outside this report was modified.

# scripts/ — Design & Security Governance Gates

Shell/PHP gates run by `.husky/pre-commit` and `.husky/pre-push` (orchestrator: `verify.sh`).
Configs: `design-rules.json` (token rules) · `ui-components.json` (component registry + baselines).

## ⚠️ Toolchain requirement: REAL jq (JSON processor)

These gates parse their JSON configs with `jq`. **A known hazard on Windows dev machines:**
the npm registry package `jq` is a *server-side jQuery wrapper* (2011), **not** the JSON
processor. If it is installed globally (`npm i -g jq`), it shadows the real tool, answers
every query with **empty output and exit 0**, and the gates then run on empty configuration
and **fail open** (they pass without measuring anything). This happened on 2026-07-26 —
full analysis: `docs/implementation/20260726_PrePush_Governance_Gate_Audit.md` (F-GATE-1).

**Self-check (run before trusting any gate output):**

```bash
jq --version                                    # must print jq-1.x  (impostor prints nothing useful)
jq -r '.baseline' scripts/design-rules.json     # must print 613 (or the current configured baseline)
```

If either check fails:

```bash
npm uninstall -g jq          # remove the impostor if present
winget install jqlang.jq     # install the real jq (or: https://jqlang.github.io/jq/)
```

Repair workstream for the gates themselves (fail-closed behavior, ERE matching,
role-gate placement, threshold recalibration): `docs/plans/20260726-2056-engineering-platform-repair-plan.md` (EG-001..EG-004).

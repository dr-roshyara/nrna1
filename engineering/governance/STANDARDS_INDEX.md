# Engineering Standards — Index (ES-001..ES-006)

**Status:** PROPOSED — awaiting ARB review (constitutional consolidation ordered 2026-07-11; evidence of necessity: OQ-ENG-002 F-OQ2-1/2 — binding rules had no canonical home; runtime MEMORY was carrying permanent governance).
**The consolidation convention (rules live once — the consolidation obeys the rule it enforces):**
- **HOSTED rule** — its full canonical text lives in the ES document (previously homeless: MEMORY-only or evidence-record addenda).
- **REGISTERED rule** — it already has a governed home (sealed corpus, Stable protocol, process draft, rulings register); the ES document carries the authoritative pointer + one-line statement. Copying it would create the duplication this consolidation cures.
**End state:** MEMORY = runtime hints only · Standards = constitutional truth · Qualification verifies standards · the EEP executes them. Every rule findable from `engineering/README.md` → this index.

| Standard | Title | Governs | Hosts (new canonical homes) |
|---|---|---|---|
| [ES-001](ES-001-Engineering-Constitution.md) | Engineering Constitution | Foundational principles & governance creation | rule parsimony · documents-record-governance |
| [ES-002](ES-002-Engineering-Execution.md) | Engineering Execution | How work is performed | implementation-first default · AIP observation stop |
| [ES-003](ES-003-Qualification.md) | Qualification | How the platform verifies itself | qualification lifecycle · score-persistence stop · measurement conventions |
| [ES-004](ES-004-Documentation.md) | Documentation | How records are structured & governed | retrospectives-recommend · record conventions |
| [ES-005](ES-005-Repository.md) | Repository | How the repository is organized | folder rule · placement litmus |
| [ES-006](ES-006-Engineering-Knowledge-Governance.md) | Engineering Knowledge Governance | How ENGINEERING knowledge is harvested, promoted, retired (NOT project knowledge — a separate bounded context with its own future standards after the pilot) | promotion ladder · knowledge research freeze · harvest discipline |

## Constitutional hierarchy

```text
                    ES-001 Constitution
                          │
              ┌───────────┴───────────┐
              ▼                       ▼
      ES-002 Execution        ES-003 Qualification
              │                       │
              └───────────┬───────────┘
                          ▼
                 ES-004 Documentation
                          │
                          ▼
                  ES-005 Repository
                          │
                          ▼
        ES-006 Engineering Knowledge Governance
```

## Enforcement Matrix (ARB-commissioned, 2026-07-11 — automation is an implementation of governance, never governance itself)

**Classification vocabulary:** **Machine** (a script can verify) · **AI** (an engineer-agent can reason about compliance) · **Human** (governance acts — cannot and must not be automated) · **Hybrid**. **Automation eligibility:** YES / PARTIALLY / NO — only YES rules may ever become scripts, and then only with evidence of need.

| Rule | Enforcement | Mechanically verifiable? | Existing machinery |
|---|---|---|---|
| ES-001.1 Rule parsimony | AI | NO — "does an existing rule cover this?" is reasoning | — |
| ES-001.2 Documents-record-governance | AI + Human | NO — stopping to ask is judgment; deciding is human | — |
| AIP-10/11 (registered) | AI (+ Machine PARTIALLY) | scans for asserted dates/scores exist as OQ greps | OQ instruments |
| ES-002.1 Implementation-first · ES-002.2 Observation stop | AI | NO | — |
| EEP lifecycle + EP-01-Light gate | AI + Human (approval) | PARTIALLY — plan-before-mutation reminders | **already exists:** discipline-gate-reminder (AST-005), dev-guide-reminder (AST-006) |
| ES-003.1 Qualification lifecycle | AI + Human | PARTIALLY (verdict-vocabulary lint conceivable — no evidence of need) | OQ practice |
| ES-003.2 Score-stop | AI (+ Machine PARTIALLY) | YES-trivially (pattern scan) — but low value; no incident | OQ greps |
| ES-003.3 Measurement conventions | AI + Machine | PARTIALLY — config-recorded-with-result is checkable | registry validator pattern |
| ES-004.1 Retrospectives-recommend | AI + Human | NO | — |
| ES-004.2 Record conventions (append-only logs) | AI + **Machine-candidate** | **YES — and the ONLY rule with real incident evidence** (2026-07-11 session-log overwrite, repaired from git in minutes) | none — **the single evidence-backed automation candidate** (ARB decides) |
| ES-005.1 Three-concern · ES-005.2 Folder rule | Machine | YES — placement + empty-dir checks | **already executed** as OQ E-2 instruments |
| ES-005.3 Placement litmus · ES-005.4 Never-a-copy (candidate) | AI | NO — judgment | — |
| ES-006.1 Promotion ladder | Human + AI | NO — promotion is an ARB act | — |
| ES-006.2 Research freeze · ES-006.3 Harvest discipline | AI (+ Machine PARTIALLY: register integrity) | register checks exist as OQ instruments | OQ instruments |
| Registry-first (registered) | Machine PARTIALLY + AI | path/id/orphan validation | **already exists:** symfony/yaml validator runs |

**The smallest hook set justified by evidence: ZERO new hooks now.** Machine-verifiable rules are already verified — at qualification time, by OQ instruments (the correct home per R-26: run → capture → verdict; periodic, not resident). Existing runtime reminders (AST-005/006/007) already cover Level-2 guidance for execution rules. **One candidate is recorded for ARB decision:** an append-only guard for session logs — the only rule with an actual incident behind it; one incident, git-recoverable, may not clear the burden of proof. Everything else: enforcement stays where the matrix puts it — in the reasoning engineer and the human authority.

## The stopping rule

> **The ES document set is complete. No new ES standards will be created unless operational evidence demonstrates insufficiency in the existing set.** If a new rule appears, the first question is: **"Which existing ES document owns this?"** — never "Should we create ES-007?" (This is ES-001.1 Rule Parsimony applied to the standards themselves; recreating fragmentation would undo this consolidation's entire purpose.)

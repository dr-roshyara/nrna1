# Engineering Standards — Index (ES-001..ES-006)

**Status:** PROPOSED — awaiting ARB review (constitutional consolidation ordered 2026-07-11; evidence of necessity: OQ-ENG-002 F-OQ2-1/2 — binding rules had no canonical home; runtime MEMORY was carrying permanent governance).
**The consolidation convention (rules live once — the consolidation obeys the rule it enforces):**
- **HOSTED rule** — its full canonical text lives in the ES document (previously homeless: MEMORY-only or evidence-record addenda).
- **REGISTERED rule** — it already has a governed home (sealed corpus, Stable protocol, process draft, rulings register); the ES document carries the authoritative pointer + one-line statement. Copying it would create the duplication this consolidation cures.
**End state:** MEMORY = runtime hints only · Standards = constitutional truth · Qualification verifies standards · the EEP executes them. Every rule findable from `engineering/README.md` → this index.

| Standard | Title | Governs | Hosts (new canonical homes) |
|---|---|---|---|
| [ES-001](ES-001-Engineering-Constitution.md) | Engineering Constitution | Foundational principles & governance creation | rule parsimony · documents-record-governance · governance orientation duty |
| [ES-002](ES-002-Engineering-Execution.md) | Engineering Execution | How work is performed | implementation-first default · AIP observation stop |
| [ES-003](ES-003-Qualification.md) | Qualification | How the platform verifies itself | qualification lifecycle · score-persistence stop · measurement conventions |
| [ES-004](ES-004-Documentation.md) | Documentation | How records are structured & governed | retrospectives-recommend · record conventions |
| [ES-005](ES-005-Repository.md) | Repository | How the repository is organized | folder rule · placement litmus |
| [ES-006](ES-006-Engineering-Knowledge-Governance.md) | Engineering Knowledge Governance | How ENGINEERING knowledge is harvested, promoted, retired (NOT project knowledge — a separate bounded context with its own future standards after the pilot) | promotion ladder · knowledge research freeze · harvest discipline · harvest question |

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

## Decision Authority & Verification Matrix (ARB-commissioned 2026-07-11; refined per review — three dimensions separated)

**The three dimensions (never mixed):** **Primary Decision Authority** = *who determines compliance* (Machine · AI evaluates · Human decides — the AI always *evaluates and recommends*; authority stays with governance, ES-001.2) · **Verification** = *how compliance is checked* (OQ instrument · EP-02 review · human review · reminder) · **Automation** = *what software exists or is justified* (Existing · Candidate · None). Companion concept map: the [Engineering Decision Model](../architecture/reference/Engineering_Decision_Model.md).

| Rule | Primary Decision Authority | Verification | Automation |
|---|---|---|---|
| ES-001.1 Rule parsimony | AI evaluates ("does an existing rule cover this?" is reasoning) | human review at ratification | None |
| ES-001.2 Documents-record-governance | AI evaluates → **Human decides** | human review | None |
| ES-001.3 Governance orientation duty (incl. business-language-first communication · command protocol: Governance translates, routes, never executes the work it routes) | AI evaluates and **must recommend** → **Human decides** (recommendation is never authorization) | human review + workflow-record fold (state claims must be reproducible from the records; **business meaning stated before technical identifiers**) | **Candidate** — the detections are record-derivable (stale/unstarted lanes, grants without assignments, owner-vs-state mismatches); the communication duty is **judgment, not automatable**; no automation exists |
| AIP-10/11 (registered) | AI evaluates | OQ instruments (date/score scans) | Existing (OQ greps) |
| ES-002.1 Implementation-first · ES-002.2 Observation stop | AI evaluates | EP-02 review | None |
| EEP lifecycle + EP-01-Light gate | AI evaluates → **Human approves** | EP-02 review + reminders | Existing (AST-005/006 reminders) |
| ES-003.1 Qualification lifecycle | AI evaluates → **Human accepts** | re-runs + verdict-history audit | None (lint conceivable; no evidence of need) |
| ES-003.2 Score-stop | AI evaluates | OQ pattern scan | Existing (OQ greps; trivially scriptable, low value) |
| ES-003.3 Measurement conventions | AI evaluates + Machine (config-with-result checks) | OQ instruments | Existing (validator pattern) |
| ES-004.1 Retrospectives-recommend | AI evaluates → **Human decides** | human review | None |
| ES-004.2 Record conventions (append-only logs) | AI evaluates | OQ diff checks | **Candidate** — append-only guard; the ONLY rule with real incident evidence (2026-07-11 overwrite, git-recovered in minutes); may not clear burden of proof — **ARB decides** |
| ES-005.1 Three-concern · ES-005.2 Folder rule | Machine | OQ E-2 structural instruments | Existing (executed each OQ) |
| ES-005.3 Placement litmus · ES-005.4 Never-a-copy (candidate) | AI evaluates (judgment) | EP-02 review | None |
| ES-006.1 Promotion ladder | **Human decides** (AI recommends) | promotion-ladder audits | None |
| ES-006.2 Research freeze · ES-006.3 Harvest discipline | AI evaluates | OQ register-integrity checks | Existing (OQ instruments) |
| ES-006.4 Harvest question | AI evaluates ("No" is the common, valid answer) → **Human decides** any Yes via ES-006.1 | EP-02 review | None |
| Registry-first (registered) | Machine (paths/ids) + AI (trace quality) | registry validator | Existing (symfony/yaml runs) |

**The smallest automation set justified by evidence: ZERO new hooks.** Machine verification lives in qualification instruments (periodic, per R-26) — not resident daemons; existing reminders cover runtime guidance; one candidate awaits the ARB. *Automation is an implementation of governance, never governance itself* (an architectural statement following the normal promotion path — see the Decision Model).

## The stopping rule

> **The ES document set is complete. No new ES standards will be created unless operational evidence demonstrates insufficiency in the existing set.** If a new rule appears, the first question is: **"Which existing ES document owns this?"** — never "Should we create ES-007?" (This is ES-001.1 Rule Parsimony applied to the standards themselves; recreating fragmentation would undo this consolidation's entire purpose.)

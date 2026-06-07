# Round 17 — Assumption Register

**Date:** 2026-06-07

**Status:** Initialized

**Purpose:** Track foundational assumptions that Step 2 discovery is relying on. Identify assumptions that, if wrong, would invalidate findings.

---

## What This Register Tracks

An **assumption** is something you are acting as if true without current evidence.

Examples:
```
"Repository behavior reflects actual organizational behavior"
"Code still represents current business rules"
"Election workflow is representative of all governance workflows"
```

Assumptions are different from hypotheses:
- **Hypothesis:** Something you believe may be true AND are investigating
- **Assumption:** Something you believe is true AND are currently using without investigating

This register prevents silent assumption failures.

---

## Register Structure

| ID | Assumption | Risk Level | If Wrong, Impact | Validation Method | Status |
|----|-----------|-----------|------------------|------------------|--------|
| (ID) | (What we assume is true) | High / Medium / Low | (What breaks?) | (How to test?) | Not Yet Validated / Validated / Flagged |

---

## Foundational Assumptions for Step 2

---

### A1: Repository Behavior Reflects Organizational Reality

**Assumption:** The code in the repository reflects actual organizational governance and election practices.

**Risk Level:** High

**If Wrong, Impact:** 
- All findings about actual governance practices are invalid
- Code analysis may describe theoretical system, not actual system
- Recommendations based on code analysis may not match organizational needs

**Validation Method:**
- Stakeholder interviews comparing code behavior to practice
- Operational observation during elections
- Officer feedback on whether code matches their experience

**Status:** Not Yet Validated

**Rationale:** Investigation relies on repository as source of truth for governance behavior. If repository is outdated or aspirational, findings are unreliable.

---

### A2: Existing Code Still Represents Current Business Rules

**Assumption:** Business rules implemented in code have not changed since implementation. Current code reflects current requirements.

**Risk Level:** High

**If Wrong, Impact:**
- Code analysis findings may describe obsolete rules
- Governance rules may have evolved but code has not
- Recommendations may address former concerns, not current ones

**Validation Method:**
- Compare code rules with current constitutional documents
- Interview officers about whether rules have changed
- Review commit history for recent changes (last 6 months)
- Verify election configuration against code assumptions

**Status:** Not Yet Validated

**Rationale:** Some code may be legacy. If business rules have evolved, code analysis describes past behavior, not current behavior.

---

### A3: State Machine is Authoritative

**Assumption:** The state machine implementation (ElectionConstitution.php) is the authoritative source of governance rules, not documentation.

**Risk Level:** Medium

**If Wrong, Impact:**
- Governance findings based on code may differ from documented governance
- Gap between code and documentation may indicate either
  - Outdated documentation
  - Outdated code
  - Intentional flexibility

**Validation Method:**
- Compare state machine rules with Election Policy documentation
- Compare with organizational bylaws
- Ask officers which is authoritative (code or docs)

**Status:** Not Yet Validated

**Rationale:** Investigation assumes code is current. If documentation is authoritative and code is outdated, findings are wrong.

---

### A4: Election Workflow Provides Insight Into Broader Governance

**Assumption:** The election workflow examined (candidate → voting → results) provides useful insight into broader organizational governance patterns, though it may not be fully representative of all governance workflows.

**Risk Level:** Medium

**If Wrong, Impact:**
- Governance findings may be election-specific rather than universal
- Non-election governance workflows may differ significantly
- Authority and governance patterns discovered may not apply elsewhere
- Governance model may vary by decision type

**Validation Method:**
- Document other organizational decision workflows (if accessible)
- Compare governance patterns across different workflow types (if possible)
- Determine which patterns are election-specific vs. universal
- Note scope limitations in findings

**Status:** Not Yet Validated

**Rationale:** Step 2 focuses on election workflows as primary investigation source. Investigation should identify which findings are election-specific and which generalize more broadly.

---

### A5: Officers Actually Follow Constraints

**Assumption:** Officers actually follow the constraints implemented in the code. The code-enforced constraints match actual behavior.

**Risk Level:** Medium

**If Wrong, Impact:**
- Authority findings describe constraints, not actual authority
- Officers may have informal override mechanisms
- Code constraints may be bypassed in practice

**Validation Method:**
- Officer interviews about whether they follow code constraints
- Observation during elections
- Review of documented exceptions or overrides
- Check for alternate authorization paths

**Status:** Not Yet Validated

**Rationale:** Code analysis reveals technical constraints. If officers have informal workarounds, actual authority differs from code authority.

---

### A6: Database Schema Reflects Current State

**Assumption:** The database schema currently in use matches the migration files. There are no ad-hoc schema changes outside the migration history.

**Risk Level:** Medium

**If Wrong, Impact:**
- Schema analysis based on migrations may not match actual database
- Evidence of features may exist in schema but not be used
- Features in schema may not actually be implemented

**Validation Method:**
- Compare current database schema with latest migrations
- Check for undocumented schema changes
- Verify schema is in use (not abandoned columns)

**Status:** Not Yet Validated

**Rationale:** Investigation assumes schema = implementation. If undocumented changes exist, analysis is incomplete.

---

### A7: No Critical Workarounds Exist

**Assumption:** There are no critical operational workarounds that bypass the documented system. System behavior matches code behavior.

**Risk Level:** Medium

**If Wrong, Impact:**
- Operational findings describe only formal system
- Actual governance may include informal processes
- Critical functionality may exist outside the system

**Validation Method:**
- Officer interviews about workarounds or manual processes
- Observation during actual elections
- Request for undocumented procedures
- Check for manual intervention points

**Status:** Not Yet Validated

**Rationale:** Step 2 assumes formal system is complete. If officers have critical manual workarounds, findings miss important governance processes.

---

### A8: Evidence Gathering Represents Complete Picture

**Assumption:** The six investigation streams cover all significant domain areas. No major domain concerns exist outside these streams.

**Risk Level:** Low

**If Wrong, Impact:**
- Investigation findings are incomplete
- Critical domain concepts may exist outside investigation scope
- Subsequent discovery may reveal major oversight

**Validation Method:**
- Stakeholder review of investigation streams
- ARB checkpoint review confirmation
- Hypothesis register review for gaps

**Status:** Partially Validated (ARB approved scope)

**Rationale:** ARB approved six investigation streams as appropriate scope. This assumption is partially validated by ARB acceptance.

---

### A9: Round 16 Findings Are Accurate

**Assumption:** Round 16 candidate reassessment findings are accurate. The evidence gathered in Round 16 is sufficient foundation for Step 2.

**Risk Level:** Low

**If Wrong, Impact:**
- Step 2 may be investigating wrong candidates
- Investigation may be based on incorrect Round 16 conclusions

**Validation Method:**
- ARB approval of Round 16 findings
- Step 2 checkpoint with ARB confirming candidates still relevant

**Status:** Validated by ARB

**Rationale:** Round 16 findings were reviewed and approved by ARB. This assumption is backed by ARB authority.

---

### A10: Investigation Independence

**Assumption:** Each of the six investigation streams can be investigated somewhat independently. Findings in one stream do not invalidate investigation in another.

**Risk Level:** Low

**If Wrong, Impact:**
- Parallel investigation becomes serial investigation
- Investigation timeline expands significantly
- Some findings may be contradictory

**Validation Method:**
- Monitor cross-stream dependencies during Phase 2
- Phase 3 ARB checkpoint can identify problematic dependencies

**Status:** Not Yet Validated

**Rationale:** Investigation plan assumes streams are largely independent. If tight coupling exists, investigation approach may need adjustment.

---

### A11: Terminology Is Used Consistently

**Assumption:** Terms such as Governance, Authority, Evidence, Trust, Legitimacy, and Audit mean the same thing across documents, code, and organizational discussions.

**Risk Level:** High

**If Wrong, Impact:**
- Same term used with different meanings in different contexts
- Different terms used for same concept in different places
- Investigation may conflate unrelated concerns or split unified concerns
- Findings may describe terminology confusion rather than domain separation

**Validation Method:**
- Track terminology usage across documents and code
- Identify term definitions in different sources
- Document when same term has multiple meanings
- Document when multiple terms describe same concept
- Phase 2 synthesis will identify terminology inconsistencies

**Status:** Not Yet Validated

**Rationale:** DDD discovery frequently uncovers terminology confusion. Tracking this assumption prevents false confidence in investigated boundaries.

---

## Assumption Validation Timeline

**Phase 1 (Evidence Gathering):** 
- Collect evidence relevant to assumptions
- Flag assumptions if contradictory evidence emerges

**Phase 2 (Synthesis):**
- Validate assumptions against accumulated evidence
- Update assumption status

**Phase 3 (ARB Checkpoint):**
- Review assumptions with ARB
- Request additional validation if needed

**Phase 4-5 (Deep Investigation):**
- Continue validating assumptions
- Investigate if assumption validity questioned

**Phase 6 (ARB Review):**
- Final assumption status
- Document validated vs flagged assumptions
- Note impact on findings

---

## Assumption Status Summary

| Status | Count | Examples |
|--------|-------|----------|
| Not Yet Validated | 9 | A1, A2, A3, A4, A5, A6, A7, A10, A11 |
| Partially Validated | 1 | A8 (ARB-approved scope) |
| Validated | 1 | A9 (ARB-approved Round 16) |
| Flagged | 0 | (none yet) |

---

## Key Rules

**Critical Governance Rule:**

Assumptions are not findings.

Validation of an assumption does not constitute validation of any hypothesis or conclusion.

Assumptions merely define the reliability of the investigation environment, not the validity of investigation outcomes.

---

1. **If an assumption is flagged during investigation**, pause that investigation stream and review with ARB before proceeding.

2. **If an assumption affects multiple streams**, flag it to ARB immediately rather than proceeding with potentially invalid findings.

3. **Document when assumptions change**, so findings can be re-evaluated if assumptions change during investigation.

4. **Use assumptions to shape Phase 3 ARB checkpoint**, specifically asking ARB to validate critical assumptions.

---

## Impact on Findings

Any finding that depends on a flagged assumption is also flagged.

Example:
```
If A1 (Repository reflects reality) is flagged,
then all findings about "actual governance behavior" are flagged
and must be qualified with uncertainty.
```

---

**Status: Initialized**

**Next Update: During Phase 1 Evidence Gathering as validation begins**


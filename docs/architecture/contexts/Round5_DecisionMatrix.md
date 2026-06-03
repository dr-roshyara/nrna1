# Round 5 — Decision Matrix

**Purpose:** Explicit mapping of blocking decisions with current confidence and missing evidence  
**Format:** One-page decision matrix to anchor all research  
**Discipline:** Only research that can change D1, D2, or D3

---

## Decision D1: SecurityEventRecorder Classification

### Question
Is SecurityEventRecorder an **infrastructure capability** or an **emerging domain capability**?

### Current State (from Rounds 1-4)

| Property | Evidence | Implication |
|----------|----------|------------|
| **Status** | Operational, tested, called during every vote evaluation | Exists and is used |
| **Purpose** | Records evaluation outcomes; fires fire-and-forget; never blocks voting | Supporting mechanism |
| **Location** | `App\Application\Election\Security\` | Application layer, not Domain layer |
| **Hashing** | Raw IP hashed before recorder sees it; voter_id explicitly null | Privacy-conscious |
| **Retention** | 730 days default; append-only; cannot delete | Long-term audit storage |

### Current Confidence: **MEDIUM**

**Reasoning:** Code shows it is operational and carefully implemented. But its architectural role (temporary infrastructure vs. foundational domain capability) cannot be determined from code alone.

### Missing Evidence

**To move confidence to HIGH, need:**
- Explicit statement of architectural intent (infrastructure or domain)
- Clarification whether it will be replaced, supplemented, or remains permanent
- Whether it represents a deliberate design decision or an interim mechanism

### Research Source Categories (in priority order)

1. **Commit message** for initial SecurityEventRecorder introduction
   - Look for problem statement ("Needed because...") or design rationale
   - File: git log on `app/Application/Election/Security/SecurityEventRecorder.php`

2. **Architecture Decision Record** mentioning "security event recording" or "trust audit"
   - Location: `docs/adr/` or similar
   - Keywords: "recording", "audit trail", "security events"

3. **Author statement** on purpose (if commit message or ADR is unclear)
   - Direct question: "Is SecurityEventRecorder meant to be permanent?"

### Expected Outcome

**If HIGH confidence reached:**
- "SecurityEventRecorder is **infrastructure** — understand it as temporary supporting mechanism" OR
- "SecurityEventRecorder is **domain capability** — align Evidence Context with its design"

**If confidence remains MEDIUM/LOW:**
- Design Evidence Context assuming SecurityEventRecorder is infrastructure (safer assumption)
- Flag as assumption to be validated later

---

## Decision D2: Inactive Domain Events Classification

### Question
What is the status of the five domain event classes (ObservationRecorded, LegitimacyGranted, LegitimacyEvaluated, DivergenceObserved, SovereigntyBoundaryCrossed)?

- **Future Architecture**: Will be dispatched after D.0.3c migration
- **Migration Artifact**: Temporary instrumentation, will be deleted
- **Experimental Code**: Abandoned prototype
- **Unknown**: Cannot determine

### Current State (from Rounds 1-4)

| Property | Evidence | Implication |
|----------|----------|------------|
| **Dispatch Sites** | Zero found (grep exhaustive) | Not currently operational |
| **Listeners** | None registered in EventServiceProvider | No consumption path exists |
| **Payload** | Carry `voterIdentifier` field | Different design philosophy from SecurityEventRecorder |
| **Docstrings** | Reference "D.0.3c migration" and "D-phase" | Appear migration-related |
| **Commit Date** | 2026-05-29, message "security domain created" | Recent, intentional addition |
| **Voter Linkage** | All carry voterIdentifier; SecurityEventRecorder does not | Opposite design choice |

### Current Confidence: **LOW**

**Reasoning:** Docstrings suggest migration instrumentation, but "D.0.3c" is undefined. No explicit statement confirms whether these events are planned for integration or should be removed.

### Missing Evidence

**To move confidence to HIGH, need:**
- Definition of "D.0.3c" phase (is it an architecture phase? A migration milestone?)
- Explicit status: planned/deferred/abandoned
- Timeline for integration (if planned)

### Research Source Categories (in priority order)

1. **Commit message** for 89914ce3 (the commit adding all five events)
   - git log --oneline | grep -A5 "security domain created"
   - Or: git show 89914ce3

2. **Roadmap or phase documentation** mentioning D.0.3c
   - Location: `docs/` or `ROADMAP.md` or project management system
   - Keywords: "D.0.3c", "D-phase", "middleware retirement"

3. **Architecture Decision Record** on security event strategy
   - Look for decision between two approaches (SecurityEventRecorder vs. domain events)

4. **Author statement** if documentation is unclear
   - Direct question: "Are these events planned for permanent integration?"

### Expected Outcome

**If HIGH confidence reached:**
- "Events are **future architecture** — Evidence Context must accommodate them" OR
- "Events are **migration artifact** — Can be ignored; focus on SecurityEventRecorder" OR
- "Events are **abandoned** — Should be deleted or explicitly marked deprecated"

**If confidence remains MEDIUM/LOW:**
- Design Evidence Context assuming events are migration-only (safer assumption)
- Plan to revisit after D.0.3c phase reaches known status

---

## Decision D3: Relationship Between Systems

### Question
What is the intended relationship between SecurityEventRecorder and the domain events?

- **Replace**: Domain events will replace the recorder
- **Supplement**: Domain events will coexist alongside recorder
- **Separate**: They serve fundamentally different purposes
- **Unrelated**: No relationship intended; independent design paths
- **Unknown**: Relationship not yet determined

### Current State (from Rounds 1-4)

| Aspect | SecurityEventRecorder | Domain Events | Implication |
|--------|----------------------|---------------|------------|
| **Voter Identity** | Explicitly null | Carried in payload | Opposite design choices |
| **Dispatch** | Direct DB write (operational) | Not dispatched (inactive) | Different maturity |
| **Purpose** | Audit/analysis (inferred) | Verification/evaluation (guessed) | Possible different responsibilities |
| **Permanence** | Appears permanent (inferred) | Appears temporary (docstring) | Different architectural roles |
| **Co-existence** | Both exist in code now | Unclear why both needed | Suggests either incomplete migration or intentional dual approach |

### Current Confidence: **LOW**

**Reasoning:** Both mechanisms exist and have different designs (voter identity handling, dispatch status, purpose). But the architectural intent for having both is unclear.

### Missing Evidence

**To move confidence to HIGH, need:**
- Explicit architectural decision comparing the two approaches
- Statement of whether one will replace the other, or both are needed
- Clarification of what purpose each serves that requires different design

### Research Source Categories (in priority order)

1. **Architecture Decision Record** explicitly comparing approaches
   - Keywords: "security recording", "event dispatch", "voter linkage"
   - Likely title: "ADR: Security Event Recording Strategy" or similar

2. **Commit message for domain events** (89914ce3)
   - Why was this alternative approach introduced if SecurityEventRecorder already exists?

3. **Design document** describing the constitutional authority chain
   - Should explain why voter linkage is needed in one place (events) but avoided in another (recorder)

4. **Author statement** on architectural strategy
   - Direct question: "Why do both approaches exist? Are they competing designs or intentionally separate?"

### Expected Outcome

**If HIGH confidence reached:**
- Clear understanding of roles: "SecurityEventRecorder is [for audit], domain events are [for verification/evaluation]" OR
- Clear migration path: "Domain events will replace SecurityEventRecorder in D.0.3c" OR
- Clear separation: "They serve different boundaries and should coexist"

**If confidence remains MEDIUM/LOW:**
- Design Evidence Context assuming SecurityEventRecorder is primary (safer)
- Flag domain events as TBD pending D.0.3c clarification

---

## Summary Table

| Decision | Current Confidence | Key Unknown | Most Likely Source | Impact if Resolved |
|----------|-------------------|------------|-------------------|-------------------|
| **D1: SecurityEventRecorder Role** | MEDIUM | Is it temporary or foundational? | Commit msg or ADR | Can decide if Evidence Context aligns with it or replaces it |
| **D2: Domain Events Status** | LOW | Are they planned or abandoned? | Roadmap or ADR on D.0.3c | Can decide if Evidence Context must support them |
| **D3: System Relationship** | LOW | Why do both exist? | Architecture decision document | Can decide Evidence Context design strategy |

---

## Round 5 Research Sequence

**Step 1 (5 min):** Git commit message for 89914ce3
- Answers: Why were domain events introduced at the same time?
- May help with: D2, D3

**Step 2 (10 min):** Search for "D.0.3c" in codebase + docs
- Answers: Is D.0.3c a real phase? What is its status?
- May help with: D2, D1

**Step 3 (15 min):** Search for ADR on security recording or authentication
- Answers: Was the choice between SecurityEventRecorder and domain events documented?
- May help with: D1, D3

**Step 4 (As needed):** Author statement via chat if documentation is unclear
- Answers: Clarify any unresolved D1, D2, D3

---

## Success Criterion

**Round 5 succeeds when:** At least 2 of 3 decisions (D1, D2, D3) move from LOW/MEDIUM to HIGH confidence.

**Round 5 fails when:** After exhausting documented sources, D1-D3 remain LOW confidence and require author decision.

---

**Status: Decision matrix established. Ready to proceed with targeted research on D1, D2, D3.**

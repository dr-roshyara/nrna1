# step180-unknown-vs-contradictory-state-space

**Scope(s):** THEORY-LEVEL · **Row count:** 2 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `E = {Unasserted,Candidate,Supported,Established,Contradicted,Disputed,Unknown}`, `KnownUnknown = Unknown + KnownMissingEvidence`, `Unknown != Contradictory` · **Aliases:** `seven-state epistemic space; known-unknown formalized`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0033`, scope `THEORY-LEVEL`: Step 180 distinguishes Unknown (Case A: no reliable evidence either way, E=empty) from Contradictory (Case B: E1->C and E2->not-C, evidence exists on both sides) -- Unknown != Contradictory, a genuinely new distinction beyond the prior True/False/Unknown three-valued logic. Proposes a provisional seven-state epistemic space E = {Unasserted,Candidate,Supported,Established,Contradicted,Disputed,Unknown}, explicitly experimental, not frozen. Formalizes KnownUnknown = Unknown + KnownMissingEvidence (e.g. 'we don't know whether the firewall permits the connection' AND we know the missing evidence is a FirewallTest), argued to be far more useful than a bare 'unknown' -- it becomes actionable via Unknown(C) + EvidenceRequired(C)=E => NextAction=Collect(E), with the information-theoretic framing X* = argmax_X E[H(Theta)-H(Theta|X)] as a design principle (not literally implemented everywhere): 'a good knowledge system should know what evidence would be most useful next.'

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1373 §"E=∅. Therefore: Status=Unknown. ... E1→C and: E2→¬C. Now the system has evidence on both sides. That is not simply: Unknown. It is: Contradictory. ... Unknown ≠ Contradictory. ... E = {Unasserted,Candidate,Supported,Established,Contradicted,Disputed,Unknown}."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1373 §"E=∅. Therefore: Status=Unknown. ... E1→C and: E2→¬C. Now the system has evidence on both sides. That is not simply: Unknown. It is: Contradictory. ... Unknown ≠ Contradictory. ... E = {Unasserted,Candidate,Supported,Established,Contradicted,Disputed,Unknown}."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1373. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1373, S1373 |
| type_signature | PRESENT | S1373 |
| invariants | PRESENT | S1373 |
| dependencies | PRESENT | S1373, S1373 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1373, S1373 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1373] types=['DISTINCTION', 'FORMALIZATION'] scope=THEORY-LEVEL — "Distinguishes Unknown (E=empty, no reliable evidence either way) from Contradictory (E1->C and E2->not-C, evidence exists on both sides) -- Unknown != Contradictory. Proposes a provisional seven-state epistemic space {Unasserted,Candidate,Supported,Established,Contradicted,Disputed,Unknown}, explicitly experimental, not the final taxonomy, revealing that KnowledgeOS needs to represent epistemic states, not merely textual answers." (anchor: "E=∅. Therefore: Status=Unknown. ... E1→C and: E2→¬C. Now the system has evidence on both sides. That is not simply: Unknown. It is: Contradictory. ... Unknown ≠ Contradictory. ... E = {Unasserted,Candidate,Supported,Established,Contradicted,Disputed,Unknown}.")
- [S1373] types=['FORMALIZATION', 'PRINCIPLE'] scope=THEORY-LEVEL — "Formalizes KnownUnknown = Unknown + KnownMissingEvidence (e.g. knowing the firewall access is unknown AND that a FirewallTest is the missing evidence), making unknowns actionable: Unknown(C) with EvidenceRequired(C)=E yields NextAction=Collect(E). Draws an information-theoretic framing (X* = argmax_X E[H(Theta)-H(Theta|X)]) as a design principle, not requiring literal implementation everywhere: 'a good knowledge system should know what evidence would be most useful next.'" (anchor: "KnownUnknown = Unknown + KnownMissingEvidence. ... Unknown(C) and: EvidenceRequired(C)=E. Then KnowledgeOS can produce: NextAction=Collect(E). ... X* = argmax_X E[H(Θ)-H(Θ|X)]. ... A good knowledge system should know what evidence would be most useful next.")

## Notes for P3
- Thin evidence base (n=2 row(s)) — treat conclusions here as provisional.

# workflow-mechanism

**Scope(s):** `OBJECT` · **Row count:** 7 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- **G1121**: [`bc7-domain-model` · `workflow-mechanism`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0002, scope OBJECT): The AST-015/AST-016 workflow-record mechanism (workflow-state.php / session-resolve.php) that realizes governed session orchestration.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0049] §"Verified against workflow-state.php: assertTransitionAllowed and the grant writer read only transitions[] and grants[]; no assurance-shaped input exists anywhere in the engine"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: `S0079`. Candidate lifecycle: **DORMANT**.
Evidence: none recorded (no retraction, supersession, or internal contradiction found). The **DORMANT** classification is a heuristic based on how recently (by source_id ordering) this label was last used in the captured contributions, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0058 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0058, S0062, S0076, S0079 |
| experiments | PRESENT | S0049 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
A probe of the workflow mechanism's `authorized` command finds it never actually consults which session is asking -- it only checks that a grant with the exact requested scope string is AUTHORIZED anywhere on the record -- so a completed, unrelated lane can report itself authorized under a grant meant for a different lane; carried forward as direct evidence for KOS-GOV-GAPS-VERIFY-001 [S0058].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S0049]` types=[EXPERIMENTAL-RESULT] scope=CROSS-OBJECT — "T-4 (indirect assurance-to-gate paths): source inspection confirms no live indirect path exists today from an assurance outcome to a workflow gate; only latent, policy-held channels remain, addressed by F-5's constraint." (anchor: "Verified against workflow-state.php: assertTransitionAllowed and the grant writer read only transitions[] and grants[]; no assurance-shaped input exists anywhere in the engine")
- `[S0055]` types=[LIMITATION] scope=OBJECT — "V-E: the workflow-record count in the baseline is retroactively unverifiable because the records are gitignored with no history, meaning the baseline's own snapshot figure inherits the very no-provenance defect its thesis reports; recorded as an acceptance-context fact, not an error." (anchor: "V-E · '8 workflow records' is retroactively unverifiable — because of Phase A's own finding. ... the baseline's own snapshot number inherits the no-provenance defect it reports.")
- `[S0055]` types=[VALIDATION] scope=OBJECT — "Four Declared (not-yet-Observed) mechanism-behavior probes (dual-ACTIVE possible, unguarded second-START ownership transfer, no CLOSE vocabulary, unvalidated recordedBy free string) are independently re-derived and confirmed directly from the workflow-mechanism source code." (anchor: "the four Declared mechanism probes ... all four re-derived from the assertTransitionAllowed/foldSessions source by this session: CONFIRMED.")
- `[S0058]` types=[WARNING, ANALYSIS] scope=OBJECT — "A probe of the workflow mechanism's `authorized` command finds it never actually consults which session is asking -- it only checks that a grant with the exact requested scope string is AUTHORIZED anywhere on the record -- so a completed, unrelated lane can report itself authorized under a grant meant for a different lane; carried forward as direct evidence for KOS-GOV-GAPS-VERIFY-001." (anchor: "authorized never consults the session ... The --session argument is checked for existence and then ignored.")
- `[S0062]` types=[WARNING] scope=THEORY-LEVEL — "Six coupling problems are recorded, most materially that the candidate orchestration context's own authority store has no provenance (gitignored, zero history, C-3) despite the context's purpose being auditable authority, and that the registry-first contract is declared binding but measured bypassable by three unregistered hooks (C-6)." (anchor: "C-1..C-6 Coupling problems ... Authority state has no provenance — all workflow records gitignored, zero history")
- `[S0076]` types=[WARNING] scope=OBJECT — "A record defect is disclosed rather than repaired: the Verification #3 lane ran and was accepted despite never having been formally STARTed in the workflow record, named as the same class as an earlier off-record verification and live evidence for confirmed gaps G-3 and EKS-01." (anchor: "The Verification #3 lane was never STARTed on the record. ... nothing was back-dated, and the acceptance stands on the report's substance")
- `[S0079]` types=[WARNING] scope=OBJECT — "The BASELINE-003 Verification #3 off-record-START defect is restated here as standing, unrepaired evidence for the confirmed governance gaps G-3 and EKS-01, closed in the record with the defect explicitly stated rather than hidden." (anchor: "Verification #3 of KOS-ARCH-BASELINE-003 ran off-record ... Live evidence for confirmed gap G-3 and for EKS-01; no START was back-dated.")

## Notes for P3
- No additional observations beyond what is captured above; nothing about this label's own rows struck this reviewer as unusual relative to its evidentiary base.

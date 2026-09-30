# gov-state-durability-adr

**Scope(s):** OBJECT · **Row count:** 15 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** KOS-AIP-GOV-STATE-DURABILITY-ADR · **Aliases:** the durability ADR

**Candidate group membership (NOT an identity claim):**
- **G0055** [`gov-state-durability-adr` · `session-completion-handoff-protocol`] — explicit agent-stated uncertainty: 'session-completion-handoff-protocol' POSSIBLY relates to 'gov-state-durability-adr' (batch B0007). Note: A newly proposed, reviewed (producer self-assessment + independent review), and PO/ARB-ADOPTED operational protocol requiring every completed governed AI session to produce a deterministic Session Completion Report recommending (never assigning) the next actor; explicitly advisory-only, creates no authority, and is distinct from but complementary to the End-of-Commission checklist and the workflow-state.php engine.
- **G0732** [`gov-state-durability-adr` · `gov-state-durability-decision` · `kos-aip-gov-state-durability-program`] — labels share the notation 'KOS-AIP-GOV-STATE-DURABILITY-ADR'
- **G0936** [`gov-state-durability-adr` · `gov-state-durability-decision`] — working_label token overlap Jaccard=0.60 (shared tokens: ['durability', 'gov', 'state'])

## Sources (how this label entered the ledger)

- **OBJECT-INDEX**, batch B0004, scope OBJECT: The proposed ADR (S0128) diagnosing governance evidence misfiled in .claude/runtime and analysing Options A/B/C, recommending B' (relocation).

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S0128 §"16 work-item records hold 210 transitions and 99 grants... The records store NO derived state."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0129 §"Decision authority is reserved to the human PO/ARB... This process authored the ADR... approving this particular ADR is the single act this process is most specifically barred from."]

## Lifecycle

last_seen: S0305. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/contradiction rows found). This DORMANT classification is a heuristic based on how recently (by source_id, last_seen=S0305) this label was last used in the captured contribution set, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0128, S0132, S0291 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0128 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0128, S0129, S0132 |
| examples | PRESENT | S0130 |
| warnings | PRESENT | S0128 |
| experiments | PRESENT | S0130 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

Ten measured observations (O-1..O-10) about the estate's runtime governance store: .gitignore excludes the whole of .claude/runtime; 16 records hold 210 transitions/99 grants; the records persist only an append-only evidence log and authority record, never derived state (mutationOwner, sessions, workItemState are fold-computed and never stored); seq is dense/monotonic in every record; only 2 of 210 transitions carry any date field; reconstructability from tracked documents measures 100% for sessions and 83% for grants, with the 16 uncited grants overwhelmingly amendments [S0128]. The reframing that changes the question: the problem is not whether runtime state should be tracked (there is no runtime execution state to track), but that governance evidence has been misfiled into a runtime location whose gitignore contract is correct for the location and wrong for the content [S0128]. Three options are analysed for durable governance state: A (track runtime directly — risks a JSON-array merge-conflict class, mitigated but not removed by dense/monotonic seq gap-detection); B (governance snapshot export — risks a second representation and divergence, and the snapshot must be mechanical, never an act, to avoid Governance preserving its own authority record); C (status quo, runtime-only — measured 100%/83% reconstructable, but the lossy layer is precisely the amendment layer, the load-bearing constraints) [S0128]. Recommends relocating the authority record out of runtime/ into a tracked, append-only governance-evidence location (Option B'), reasoned as dominating A (avoids the merge-conflict class inherent to a directory contracted as ephemeral) and B (has exactly one representation, avoiding the anti-corruption/divergence problem) and C (does not leave the amendment layer outside durable history); flags this recommendation as a variant beyond the three commissioned options [S0128]. A narrative business-value case restating the ADR/decision's technical content across six dimensions (auditability/compliance, reduced operational risk, faster engineering decisions, prevention of expensive architecture drift, responsible AI-assisted engineering, IP protection, reduced cost of disagreement) [S0132]. A governance determination that the routed correction chain (Architecture repair -> bounded review -> PO/ARB) had not been discharged at the Architecture stage [S0291].

## Assumption register

NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S0128]` types=[ANALYSIS] scope=OBJECT — "Ten measured observations (O-1..O-10) about the estate's runtime governance store: .gitignore excludes the whole of .claude/runtime; 16 records hold 210 transitions/99 grants; the records persist only an append-only evidence log and authority record, never derived state (mutationOwner, sessions, workItemState are fold-computed and never stored); seq is dense/monotonic in every record; only 2 of 210 transitions carry any date field; reconstructability from tracked documents measures 100% for sessions and 83% for grants, with the 16 uncited grants overwhelmingly amendments." (anchor: "16 work-item records hold 210 transitions and 99 grants... The records store NO derived state.")
- `[S0128]` types=[ARGUMENT, DISTINCTION] scope=OBJECT — "The reframing that changes the question: the problem is not whether runtime state should be tracked (there is no runtime execution state to track), but that governance evidence has been misfiled into a runtime location whose gitignore contract is correct for the location and wrong for the content." (anchor: "The load-bearing observation: there is no runtime state in the runtime directory... the artifact under runtime/ is not runtime state. It is governance evidence that happens to be stored in a runtime location.")
- `[S0128]` types=[ANALYSIS, ALTERNATIVE] scope=OBJECT — "Three options are analysed for durable governance state: A (track runtime directly — risks a JSON-array merge-conflict class, mitigated but not removed by dense/monotonic seq gap-detection); B (governance snapshot export — risks a second representation and divergence, and the snapshot must be mechanical, never an act, to avoid Governance preserving its own authority record); C (status quo, runtime-only — measured 100%/83% reconstructable, but the lossy layer is precisely the amendment layer, the load-bearing constraints)." (anchor: "Option A — track .claude/runtime/ directly ... Option B — governance snapshot model ... Option C — runtime-only")
- `[S0128]` types=[ARGUMENT] scope=OBJECT — "Recommends relocating the authority record out of runtime/ into a tracked, append-only governance-evidence location (Option B'), reasoned as dominating A (avoids the merge-conflict class inherent to a directory contracted as ephemeral) and B (has exactly one representation, avoiding the anti-corruption/divergence problem) and C (does not leave the amendment layer outside durable history); flags this recommendation as a variant beyond the three commissioned options." (anchor: "Recommended: Option B′ — RELOCATION, which is Option B with the boundary set at the SOURCE rather than at an export.")
- `[S0128]` types=[WARNING] scope=OBJECT — "M-3: because only 2 of 210 transitions carry a date field, importing the record into git would make commit times the only available time evidence, and those would be import times, not act times; this is a pre-existing gap, not one relocation creates, but relocation must not let an import commit be mistaken for a chronology." (anchor: "O-7 has a migration consequence worth stating precisely: the log has almost no time dimension... Do not let an import be mistaken for a chronology.")
- `[S0129]` types=[GOVERNANCE, CONSTRAINT] scope=METHODOLOGICAL — "A process declines to perform the PO/ARB decision role on two independent grounds: decision authority is reserved to the human PO/ARB (a process writing 'DECIDED' would manufacture authority, not record it), and this specific process authored the ADR under decision, so approving it is the one act it is most specifically barred from." (anchor: "Decision authority is reserved to the human PO/ARB... This process authored the ADR... approving this particular ADR is the single act this process is most specifically barred from.")
- `[S0129]` types=[PRINCIPLE, GOVERNANCE] scope=METHODOLOGICAL — "A four-stage authority chain is stated compactly by the PO/ARB themselves as the reason a recommendation must not be read as a decision: Architecture proposal → Principal Architect recommendation → PO/ARB decision → Governance registration." (anchor: "Architecture proposal → Principal Architect recommendation → PO/ARB decision → Governance registration")
- `[S0130]` types=[EXAMPLE, EXPERIMENTAL-RESULT] scope=OBJECT — "First-hand, dated, reproducible evidence of Option C's failure mode: a specific commit durably preserved sixteen narrative review documents while all 28 grants and 33 transitions of the underlying authority record remained outside git — documents survived, the record they register against did not." (anchor: "commit de998173 (2026-08-19) committed the narrative lineage — sixteen review documents — while all 28 grants and 33 transitions remained outside git")
- `[S0132]` types=[RESTATEMENT, EXPLANATION] scope=OBJECT — "A narrative business-value case restating the ADR/decision's technical content across six dimensions (auditability/compliance, reduced operational risk, faster engineering decisions, prevention of expensive architecture drift, responsible AI-assisted engineering, IP protection, reduced cost of disagreement)." (anchor: "The business value is actually not 'moving files from .claude/runtime to another directory'... The real business value is that the organization gains a trustworthy engineering governance memory.")
- `[S0291]` types=[CONSTRAINT] scope=OBJECT — "A bounded-review-scope discipline preventing a completeness-only review from being cited as technical sign-off." (anchor: "C-11: the Governance review is completeness, provenance, amendment lineage and current/superseded document integrity, and IS NOT technical Architecture verification, design-soundness verification or migration safety verification")
- `[S0291]` types=[ANALYSIS] scope=OBJECT — "A governance determination that the routed correction chain (Architecture repair -> bounded review -> PO/ARB) had not been discharged at the Architecture stage." (anchor: "verdict: CHAIN NOT READY FOR PO/ARB ACCEPTANCE -- DV-1..DV-7 and RV-1..RV-7 remain open; migration not authorized; Phase 3 must not begin")
- `[S0296]` types=[GOVERNANCE] scope=OBJECT — "A bounded-commission pattern in which the PO/ARB explicitly authorizes a narrow repair scope and forbids using the same authorization to widen scope toward the ultimate migration." (anchor: "Repair RV-1...RV-7 only, produce evidence, stop. NOT 'continue migration.'")
- `[S0298]` types=[VALIDATION] scope=OBJECT — "Documents a verification-integrity practice: when a naive string-match test would fail on the correction's own labelled-history quotations, the test is re-specified to test the underlying property, and this loosening is explicitly disclosed rather than silently applied." (anchor: "the 18 property tests (T1-T18) all passing, with three tests explicitly re-specified mid-run to measure the property rather than the raw string (disclosed, not hidden)")
- `[S0302]` types=[CORRECTION] scope=OBJECT — "Governance re-verification catching and correcting a small measurement error made by the prior independent Architecture reviewer, recorded so a future reader does not re-open the finding from the wrong sub-breakdown." (anchor: "the independent review's own sub-breakdown said '11 shell scripts'; the re-measured true count is 10 shell scripts (13 entries total, which was correct)")
- `[S0305]` types=[GOVERNANCE] scope=OBJECT — "The PO/ARB's final acceptance wording, carefully scoping what closes (this correction chain's findings) versus what remains open (the durability observation itself, owned by migration preparation) so no reader can claim the durability problem is solved by this acceptance." (anchor: "Accepted for correction-chain completion. Not accepted as migration authorization. Untracked governance artifacts remain an explicit migration prerequisite.")

## Notes for P3

No internal tension or unusual evidentiary pattern noticed while assembling this file; the rows are mutually consistent at the level this pass can check.

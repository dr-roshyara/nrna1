# corpus-artifact-status-pipeline

**Scope(s):** METHODOLOGICAL · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `SOURCE -> CLASSIFIED -> SYNTHESIZED -> ADVERSARIAL -> ARCHITECTURAL AUTHORITY` · **Aliases:** `directory-status-pipeline governance invariant`
**Candidate group membership (NOT an identity claim):**
- G0690: [`corpus-artifact-status-pipeline` · `kernel-corpus-census`] — an UNKNOWN-OBJECT-CANDIDATE row (batch B0014, source S0553) named these as alternative candidates for one piece of evidence. why_uncertain: New governance/status-pipeline concept recurring across kernel corpus directory READMEs; not yet in object index.
- G0691: [`corpus-artifact-status-pipeline` · `kernel-corpus-census`] — an UNKNOWN-OBJECT-CANDIDATE row (batch B0014, source S0556) named these as alternative candidates for one piece of evidence. why_uncertain: New governance concept about falsification directory structure.
- G0692: [`corpus-artifact-status-pipeline` · `kernel-corpus-census`] — an UNKNOWN-OBJECT-CANDIDATE row (batch B0014, source S0557) named these as alternative candidates for one piece of evidence. why_uncertain: New governance concept.
- G0693: [`corpus-artifact-status-pipeline` · `kernel-corpus-census`] — an UNKNOWN-OBJECT-CANDIDATE row (batch B0014, source S0558) named these as alternative candidates for one piece of evidence. why_uncertain: New governance concept; HPA role not previously indexed in this snapshot check.
- G0694: [`corpus-artifact-status-pipeline` · `kernel-corpus-census`] — an UNKNOWN-OBJECT-CANDIDATE row (batch B0014, source S0559) named these as alternative candidates for one piece of evidence. why_uncertain: New governance concept.
- G1407: [`corpus-artifact-status-pipeline` · `kernel-corpus-census`] — labels co-occur in the same contribution's labels[] 3 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0014, scope METHODOLOGICAL): A recurring governance invariant stamped on every kernel-corpus directory README: a document never acquires a later status because of the directory it sits in; status advances only by a recorded act; directory names express artifact function, never subject or authority; source classification is metadata recorded in a census, never a path or filename code.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0554] §"The **99 root-level documents have never been censused.** Only the kernel corpus has (`kernel/00_CENSUS.md`, 141 documents)."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0554] §"The **99 root-level documents have never been censused.** Only the kernel corpus has (`kernel/00_CENSUS.md`, 141 documents)."

## Lifecycle
last_seen: S0583. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows). This DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0583 |
| dependencies | PRESENT | S0554, S0560 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S0560 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0554] types=[LIMITATION, GOVERNANCE] scope=METHODOLOGICAL — "The 99 root-level brainstorming documents have never been censused; only the 141-document kernel corpus has. A corpus-wide census would need to span root, kernel/, and _misc/, and reconcile parent/kernel duplicate pairs already found (e.g. ../20260824-145631 vs kernel/…151311). Expected member when done: corpus-index.md. Kernel-specific classification does not belong at this coordination level — it lives in kernel/classification/." (anchor: "The **99 root-level documents have never been censused.** Only the kernel corpus has (`kernel/00_CENSUS.md`, 141 documents).")
- [S0560] types=[GOVERNANCE, EXAMPLE] scope=METHODOLOGICAL — "kernel/corpus/ is INERT BY DECISION: the 141 kernel documents stay in kernel/ root where they were captured; retained as a reserved name only. Four role anomalies flagged in census §3 C12 are deliberately left in place: 20260824-032219 (an EKS architecture baseline, not brainstorming), 163922 (a raw article, not analysis), 20260825-130339 (lossy scrollback), 20260824-010504 (.docx duplicate)." (anchor: "Four **role anomalies** were flagged in census §3 C12 and deliberately **left in place**")
- [S0583] types=[GOVERNANCE] scope=METHODOLOGICAL — "HPA ruling (2026-08-25): classification is metadata, not filesystem — a filename carries identity/chronology/provenance only, never a category code; cluster membership is recorded in the census, not the path (a document may belong to several clusters, a path cannot express that without arbitrary choice); a reclassification edits the census, never a document's name/location; directory names express artifact function, never subject or authority; not one source document was moved. Separately ruled: renaming kernel/ to corpus/ was considered and rejected (the semantic hazard is mitigated by rule, not layout); every directory is kept but the two levels (brainstorming/ coordination vs brainstorming/kernel/ working) must differ rather than mirror, since mirroring identical trees created ambiguity about which to use." (anchor: "**Ruled by the HPA, 2026-08-25.** ... A reclassification edits **this census** — never a document's name or location.")

## Notes for P3
Carries 6 candidate group membership(s); P3 should prioritize resolving whether these reflect the same underlying object. Lifecycle is DORMANT on recency heuristics only — no explicit retraction/supersession was found in this label's own rows.

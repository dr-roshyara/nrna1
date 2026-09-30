# recommendation-lifecycle

**Scope(s):** OBJECT · **Row count:** 12 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- **G0043** [`recommendation-decision-outcome-assessment-loop` · `recommendation-lifecycle`] — explicit agent-stated uncertainty: 'recommendation-decision-outcome-assessment-loop' POSSIBLY relates to 'recommendation-lifecycle' (batch B0005). Note: The EKS knowledge-learning workflow chain distinguishing measurement from knowledge: an observation becomes a recommendation, a human decision follows, an outcome results, and an assessment evaluates effectiveness, feeding back into knowledge. Possibly the same lifecycle already tracked by the existing recommendation-lifecycle object from a later batch.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0002, scope OBJECT): The Recommendation domain (issuance, decision, rationale) under live observational discovery.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0044 §"RD-1 | A recommendation is immutable once issued; responses live in SEPARATE records"]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0044 §"RD-3 | Rules produce structurally honest silence (absence of grounds → absence of advice)"]
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0044 §"RD-6 | ACTOR PROVENANCE GAP: the decision record's actor stamps the git user, but the decider was the AI at the user's direction"]

## Lifecycle
last_seen: S0206. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction lineage found. This lifecycle value is a heuristic based on how recently (by source_id, last_seen=S0206) this label was last used in the ledger, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0044, S0206 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0044 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0044 |
| experiments | PRESENT | S0044, S0206 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Recommendation age (time-to-decision) is computable purely from existing issued/decided timestamps with no new schema fields; validated when Run 2 computed a real 6.5-hour time-to-decision from existing data. [S0044] Even after the first real decision, the KnowledgeOS-impact learning event remains only 3 of 5 complete (missing a real code change and a post-commit metric delta), so impact measurement stays insufficient evidence. [S0044] The full recommendation->decision->outcome->assessment chain is implemented and has been exercised exactly once end-to-end: 10 issued -> 2 decision rows (1 unique recommendation) -> 1 outcome -> 1 assessment (verdict INCONCLUSIVE, 'only 1 commits since decision, min 3'); 8 recommendations remain open since 2026-08-04; the same recommendation was decided twice with near-identical rationales, which the mechanism permits (classifying by latest) but the duplicate is still visible in the record. DASHBOARD.md itself frames the drop-off as 'a finding, not a failure -- an organizational mirror.' [S0206]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S0044]` types=[DEFINITION] scope=OBJECT — "A Recommendation is immutable once issued, with dedicated response records (decisions.jsonl) kept structurally separate from the issuance record." (anchor: "RD-1 | A recommendation is immutable once issued; responses live in SEPARATE records")
- `[S0044]` types=[DEFINITION] scope=OBJECT — "A Recommendation's identity is the pair (rule, subject); while a recommendation on that pair is open, no duplicate can be issued, confirmed by a re-run producing zero new records." (anchor: "RD-2 | Identity = (rule, subject) — duplicates cannot exist while open")
- `[S0044]` types=[CONCEPT] scope=OBJECT — "Recommendation rules are designed to stay silent when they lack grounds to fire, observed four times in Run 1 (e.g. insufficient history, flat CBO), rather than issuing speculative advice." (anchor: "RD-3 | Rules produce structurally honest silence (absence of grounds → absence of advice)")
- `[S0044]` types=[HYPOTHESIS] scope=OBJECT — "A staged hypothesis proposes that Recommendation identity may eventually need three layers (Definition/Instance/Revision) because (rule,subject) dedup cannot answer whether a rule changed, whether re-issuance after changed conditions is warranted, or whether v1-ignored/v2-accepted should be distinguished; held at 'think, never implement, until a real question requires it'." (anchor: "RD-4 | identity may need THREE layers: RecommendationDefinition / Instance / Revision")
- `[S0044]` types=[ANALYSIS] scope=OBJECT — "Recommendation age (time-to-decision) is computable purely from existing issued/decided timestamps with no new schema fields; validated when Run 2 computed a real 6.5-hour time-to-decision from existing data." (anchor: "RD-5 | recommendations accumulate AGE — and age is already computable from existing timestamps")
- `[S0044]` types=[WARNING, GOVERNANCE] scope=CROSS-OBJECT — "When an AI participates in a decision at a user's direction, the decision record's actor field stamps the git user identity, obscuring who-executed versus who-authorized; only the free-text rationale comment carries the honest attribution today, flagged as a real modeling question once a second AI-mediated decision occurs." (anchor: "RD-6 | ACTOR PROVENANCE GAP: the decision record's actor stamps the git user, but the decider was the AI at the user's direction")
- `[S0044]` types=[EXPERIMENTAL-RESULT, WARNING] scope=OBJECT — "A genuine anomaly: a session interruption produced two identical ACCEPTED decision records for the same recommendation, empirically answering (by accident) that recommendations can currently be re-decided unguarded, and skewing a naive dashboard record count; the fix for this is deliberately deferred until the domain semantics (exactly-once vs last-wins) are decided, so as not to prematurely encode a guard." (anchor: "RD-7 | A recommendation CAN currently be decided twice: two identical ACCEPTED records exist for REC-eaf245474a")
- `[S0044]` types=[LIMITATION] scope=OBJECT — "The DDD classification of Recommendation (Application Service / Domain Service / Aggregate / Domain Capability / Process Manager) is explicitly refused for insufficient evidence after only one day of issuance with zero responses; the shape currently resembles 'an event with a process around it' more than an aggregate, pending accumulation of decisions, rationales, and outcomes." (anchor: "NONE YET — insufficient evidence, said explicitly.")
- `[S0044]` types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Run 2 recorded the first-ever Decide action on a recommendation (ACCEPTED, reason code IMMEDIATE_VALUE), upgrading several ubiquitous-language terms from vocabulary-only to observed-exercised." (anchor: "DECIDING observed for the first time — ACCEPTED with reason code + comment")
- `[S0044]` types=[ANALYSIS] scope=OBJECT — "Even after the first real decision, the KnowledgeOS-impact learning event remains only 3 of 5 complete (missing a real code change and a post-commit metric delta), so impact measurement stays insufficient evidence." (anchor: "Impact: STILL insufficient — the first learning event is 3/5 complete (recommendation ✓ decision ✓ rationale ✓ · commit ✗ outcome ✗)")
- `[S0044]` types=[PRINCIPLE] scope=METHODOLOGICAL — "The observation protocol commits to re-running this discovery instrument only on evidence triggers (first decision, every ~10 decisions, or an anomaly), never on a schedule, and forbids ML/ranking/confidence-scoring/adaptive behavior unless deterministic v1 demonstrably fails." (anchor: "Re-run this discovery when: the first decision lands · every ~10 decisions thereafter · any anomaly appears in practice.")
- `[S0206]` types=[EXPERIMENTAL-RESULT, ANALYSIS] scope=OBJECT — "The full recommendation->decision->outcome->assessment chain is implemented and has been exercised exactly once end-to-end: 10 issued -> 2 decision rows (1 unique recommendation) -> 1 outcome -> 1 assessment (verdict INCONCLUSIVE, 'only 1 commits since decision, min 3'); 8 recommendations remain open since 2026-08-04; the same recommendation was decided twice with near-identical rationales, which the mechanism permits (classifying by latest) but the duplicate is still visible in the record. DASHBOARD.md itself frames the drop-off as 'a finding, not a failure -- an organizational mirror.'" (anchor: "Recommendation lifecycle — class A, exercised once ... 8 recommendations remain open since 2026-08-04. The single closed loop returned INCONCLUSIVE ... the same recommendation was decided twice ... with near-identical rationales")

## Notes for P3
NOT-EVIDENCED-IN-CAPTURE — no reviewer-added observation for this label beyond what appears above.

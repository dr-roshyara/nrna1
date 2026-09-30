# epistemic-status-vocabulary-thm-cor-prp-conj-2026-09

**Scope(s):** METHODOLOGICAL · **Row count:** 11 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** [CONJ], [COR], [DEFECT], [DEF], [EXP], [NEG], [OPEN], [PRP], [REC], [THM] · **Aliases:** EPISTEMIC-STATUS-VOCABULARY.md
**Candidate group membership (NOT an identity claim):**
- G0831: links `epistemic-status-vocabulary-thm-cor-prp-conj-2026-09` with `theory-doc-series-00-14-information-transformation-2026-09` — labels share the notation '[THM]'
- G0832: links `epistemic-status-vocabulary-thm-cor-prp-conj-2026-09` with `theory-doc-series-00-14-information-transformation-2026-09` — labels share the notation '[EXP]'
- G0833: links `epistemic-status-vocabulary-thm-cor-prp-conj-2026-09` with `theory-doc-series-00-14-information-transformation-2026-09` — labels share the notation '[NEG]'
- G1882: links `epistemic-status-vocabulary-thm-cor-prp-conj-2026-09` with `theory-doc-series-00-14-information-transformation-2026-09` — labels co-occur in the same contribution's labels[] 3 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0066, scope METHODOLOGICAL): Governance document ADOPTED 2026-09-04 (with a 2026-09-05 addendum) extending the zero-algebra lane's existing [DEF]/[EXP]/[NEG]/[OPEN] tags with new [THM]/[COR]/[PRP]/[CONJ] tags, retiring the ambiguous [PROP] tag into [PRP] (proposition) and [REC] (recommendation), stating a one-directional gated promotion ladder that forbids [EXP]->[THM] by accumulation, and adding the 'a design property is not an empirical result' rule with worked corpus examples. Distinct from the pre-existing five-value 'candidate-theory-status-vocabulary' object (RECONSTRUCTED/THEORETICALLY SOUND/etc.), which is a different vocabulary for a different purpose.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2745] §"Do not promote any [EXP] to [THM]"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2745] §"Do not promote any [EXP] to [THM]"

## Lifecycle
last_seen: S2761. Candidate lifecycle: ACTIVE.
Evidence: No retraction/supersession/contradiction evidence recorded. The ACTIVE label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S2761 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2750, S2756 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S2745, S2750, S2756 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- [S2761] (ANALYSIS) A tag-occurrence ledger for the whole set: [EXP] 129, [NEG] 118, [REC] 40, [OPEN] 34, [DEF] 24, [CONJ] 13, [DEFECT] 11, [THM] 8 (referencing one distinct theorem, the DPI, cited eight times), [COR] 6, [PRP] 3, [CORPUS]/[DESIGN] 1/1; the near-1:1 [NEG]-to-[EXP] ratio is read as the point, not the totals — a set where [EXP] outran [NEG] by an order of magnitude would indicate a programme that had stopped testing its own hypotheses.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S2745]` types=[GOVERNANCE/WARNING] scope=METHODOLOGICAL — "Explicit recommendation list of what NOT to do next: don't further tune the generator, don't widen the Pi x Q grid, don't declare a 'no-bridge law', don't promote [EXP] to [THM] (a theorem needs a proof not more evidence), don't move to a new algebra/Vedic-derived operator, don't test the Gita strand further for kernel candidacy, and don't open Theory v1.3 before adjudication." (anchor: "Do not promote any [EXP] to [THM]")
- `[S2750]` types=[GOVERNANCE] scope=METHODOLOGICAL — "Adopted 2026-09-04 for the KnowledgeOS research lane, issued by the human research owner as a permanent methodological rule; an untagged mathematical statement is declared a defect — the exact mechanism by which an experiment becomes a theorem without anyone deciding that it should." (anchor: "Every future mathematical statement in KnowledgeOS must carry its epistemic status: Definition, Theorem, Corollary, Empirical Result, Proposition, Conjecture, Refuted, or Open.")
- `[S2750]` types=[GOVERNANCE/EXTENSION] scope=METHODOLOGICAL — "The zero-algebra lane already used [EXP]/[OPEN]/[NEG]/[DEF] (plus [PROP]/[THEORY]/[DESIGN]/[DEFECT]); five of eight required categories already existed, so [THM] (theorem, proof cited/contained), [COR] (corollary, cited antecedent + stated derivation), and [CONJ] (conjecture, must state its refutation condition) are added as genuinely new tags rather than duplicating the vocabulary." (anchor: "This EXTENDS the existing vocabulary — it does not replace it (ES-005.4, never a copy)")
- `[S2750]` types=[CORRECTION/DISTINCTION] scope=METHODOLOGICAL — "[PROP] collided between 'proposal/recommendation' (KR-BRIDGE-02's usage) and the mathematical sense 'proposition' (a proved statement of lesser weight than a theorem); resolved per the repository's existing legacy-alias pattern (EXPERIMENT-ID-REGISTRY) into [PRP] (Proposition, proved, live) and [REC] (Recommendation, no truth claim, live), with [PROP] retired as a legacy alias only; every existing [PROP] use in the zero-algebra results is confirmed to be the recommendation sense, none a proved proposition." (anchor: "[PROP] is ambiguous and is retired as a live tag")
- `[S2750]` types=[PRINCIPLE] scope=METHODOLOGICAL — "The promotion ladder is stated as one-directional and gated: [CONJ] -experiment-> [EXP] -proof-> [PRP]/[THM] -derivation-> [COR], with refutation moving anything to [NEG]; [EXP]->[THM] requires a proof not more evidence, stated as a prohibition rather than a caution, and [REC] never becomes anything because it carries no truth claim." (anchor: "[EXP] NEVER becomes [THM] by accumulating experiments. A theorem needs a PROOF.")
- `[S2750]` types=[WARNING/EXTENSION] scope=METHODOLOGICAL — "Added 2026-09-05 on the research owner's instruction: before tagging any measured quantity [EXP], ask whether the number could have come out differently given the design — if no, it is [DEF], a restatement of the construction; worked corpus instances include context_preserved=1.000 (the operator was designed never to delete), restriction's 0.000 cross-dimension-cause rate (a restricted operator cannot cross its own defining boundary), the 0.87525 'Zero before evidence' rate (identical to the generator's cross-dimension rate, relabelled), and zoom_admissible==zoom_nontrivial at 844/844 (the threshold could not fail)." (anchor: "A design property is not an empirical result")
- `[S2750]` types=[GOVERNANCE] scope=METHODOLOGICAL — "New reporting requirement: a results document without a Degenerate Metrics section 'has not looked'." (anchor: "Every experiment reports a DEGENERATE METRICS section naming each such quantity and the mechanism that forces it.")
- `[S2750]` types=[RESTATEMENT] scope=THEORY-LEVEL — "Immediate application §4: the standing 2026-09-04 register is fully re-tagged under the new vocabulary, including the DPI as the sole [THM], its [COR] with the corrected 'structurally inapplicable != empirically falsified' note, FR-001 and FR-003 tagged [EXP], and the flattening-redundancy-increases-informative-strata claim explicitly re-tagged [NEG] per the KR-BRIDGE-03 calibration." (anchor: "Eliminability ≠ Preservation ≠ Realization ... [EXP] — separated experimentally by KR-ZERO, KR-REP-REDUCTION and KR-BRIDGE-01/02. Not [THM]")
- `[S2756]` types=[WARNING/PRINCIPLE] scope=METHODOLOGICAL — "A definitional-vs-evidential distinction: several measured 1.000/0.000 quantities (context preservation, restriction's inability to find cross-dimension causes, the 0.87525 'Zero before evidence' rate that is identical to the generator's cross-dimension rate relabelled) are forced by design and must be reported as such, not as empirical results — now a standing methodological rule referenced to the epistemic-status vocabulary." (anchor: "context_preserved = 1.000 must never be written as 'the experiment demonstrated perfect context preservation.'")
- `[S2758]` types=[CORRECTION] scope=METHODOLOGICAL — "Withdraws a prior claim that 'governance grew by zero entries', citing the newly adopted epistemic status vocabulary document." (anchor: "governance HAS gained an entry since I last measured: EPISTEMIC-STATUS-VOCABULARY.md — ADOPTED 2026-09-04")
- `[S2761]` types=[ANALYSIS] scope=METHODOLOGICAL — "A tag-occurrence ledger for the whole set: [EXP] 129, [NEG] 118, [REC] 40, [OPEN] 34, [DEF] 24, [CONJ] 13, [DEFECT] 11, [THM] 8 (referencing one distinct theorem, the DPI, cited eight times), [COR] 6, [PRP] 3, [CORPUS]/[DESIGN] 1/1; the near-1:1 [NEG]-to-[EXP] ratio is read as the point, not the totals — a set where [EXP] outran [NEG] by an order of magnitude would indicate a programme that had stopped testing its own hypotheses." (anchor: "Recounted 2026-09-06 across all 16 documents. ⚠️ These are TAG OCCURRENCES, counted mechanically — not distinct statements.")

## Notes for P3
- Participates in 4 candidate groups — a relatively dense cross-linkage; may deserve priority attention in reconciliation.

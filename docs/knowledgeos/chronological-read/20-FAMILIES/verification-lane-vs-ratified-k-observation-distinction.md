# verification-lane-vs-ratified-k-observation-distinction

**Scope(s):** OBJECT · **Row count:** 5 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Observation ∈ ratified K, Observation ∉ verification-lane imported vocabulary · **Aliases:** D285-1 K-1/K-2 distinction
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0051, scope OBJECT): D285-1's foundational distinction between K-1 (ratified K_t over 8 primitives, where Observation IS a member) and K-2 (the verification lane's imported (A,R) vocabulary, where Observation is simply absent from what was imported) — corrects the false conclusion that Observation is absent from KnowledgeOS itself.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2088] §"Observation is **not absent from KnowledgeOS**; it is absent from the verification lane's imported vocabulary."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2099. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S2099 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2088 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S2088 |
| experiments | PRESENT | S2099 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- [S2099] (CORRECTION/ARGUMENT) Adopts Reviewer A's narrowed wording verbatim (the lanes agree operationally, not semantically, on Action/Event/Policy externality) and identifies the genuinely load-bearing asymmetry as Observation: the ratified model has it as a primitive, the verification lane never introduced it — and this is the same hole that makes Qualify unimplementable.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S2088]` types=[DISTINCTION/CORRECTION] scope=OBJECT — "Corrects a potential false conclusion: Observation is a member of the ratified K (Observation ∈ K); it is only absent from the verification lane's imported vocabulary — not absent from KnowledgeOS itself. This distinction must be kept explicit in every downstream artifact." (anchor: "Observation is **not absent from KnowledgeOS**; it is absent from the verification lane's imported vocabulary.")
- `[S2088]` types=[CORRECTION/WARNING] scope=OBJECT — "Tightens an overly strong D285-1 claim ('the lanes do not disagree about Action/Event/Policy — they agree, and place them differently') to a weaker, more defensible one: the lanes agree only operationally that these are outside the verification lane's epistemic K; this does not establish they share the same semantic interpretation of that externality." (anchor: "**The lanes agree operationally that Action/Event/Policy are outside the verification lane's epistemic K; this does not establish semantic equivalence of their treatment across the two models.**")
- `[S2095]` types=[VALIDATION/CORRECTION] scope=OBJECT — "Accepts Reviewer A's first scope tightening (the Action/Event/Policy agreement claim was too strong) and adopts A's exact restated wording, describing this as 'the equality discipline applied to a prose claim — the same rule, one level up.'" (anchor: "**The lanes agree operationally that `Action/Event/Policy` are outside the verification lane's epistemic `K`; this does not establish semantic equivalence of their treatment across the two models.**")
- `[S2099]` types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Executes a primitive-level set comparison: ratified K_t has 8 primitives (Action, Entity, Event, Observation, Policy, Proposition, Relation, State), the verification lane has 2 (Assertion, Relation, where Assertion expands to Proposition/Entity/Evidence/Context/Time/Provenance); 3 primitives are shared by name (Entity, Proposition, Relation), 5 are ratified-only (Action, Event, Observation, Policy, State), and 4 are verification-only (Context, Evidence, Provenance, Time)." (anchor: "SHARED (3): Entity · Proposition · Relation / RATIFIED-ONLY (5): Action · Event · Observation · Policy · State / VERIFICATION-ONLY (4): Context · Evidence · Provenance · Time")
- `[S2099]` types=[CORRECTION/ARGUMENT] scope=OBJECT — "Adopts Reviewer A's narrowed wording verbatim (the lanes agree operationally, not semantically, on Action/Event/Policy externality) and identifies the genuinely load-bearing asymmetry as Observation: the ratified model has it as a primitive, the verification lane never introduced it — and this is the same hole that makes Qualify unimplementable." (anchor: "*the lanes agree **operationally** that `Action/Event/Policy` are outside the verification lane's epistemic `K`; this does not establish semantic equivalence of their treatment across the two models.* — the equality discipline, applied to a prose claim. They **do** differ on `Observation`, which the ratified model has as a primitive and the verification lane never introduced. **That asymmetry is the load-bearing one, and it is the same hole that makes `Qualify` unimplementable.**")

## Notes for P3
- No unusual internal tension observed across this label's 5 captured row(s); evidentiary base is proportionate to row count.

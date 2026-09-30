# session-bootstrap-ast017

**Scope(s):** OBJECT · **Row count:** 15 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `AST-017`, `session-bootstrap.php` · **Aliases:** `Session Bootstrap & Responsibility Resolution`
**Candidate group membership (NOT an identity claim):**
- G0056: [`session-assignment-resolver` · `session-bootstrap-ast017`] — explicit agent-stated uncertainty: 'session-bootstrap-ast017' POSSIBLY relates to 'session-assignment-resolver' (batch B0009). Note: Read-only, ON_DEMAND resolver capability composing AST-015 (fold/identity/authorized) to answer a governed session's lane/role/authorization deterministically at start; six-way output separation (identity/assignment/activation_prerequisites/grant/mutation_owner/gates/continuation); one bounded raw-read exception (v3HandoffRead).
- G0749: [`kos-session-bootstrap-mechanism` · `session-bootstrap-ast017`] — labels share the notation 'AST-017'
- G0750: [`engineering-with-knowledgeos-chapter` · `kos-session-bootstrap-mechanism` · `session-bootstrap-ast017`] — labels share the notation 'session-bootstrap.php'

## Sources (how this label entered the ledger)
- **PROPOSAL** (batch B0009, scope OBJECT): Read-only, ON_DEMAND resolver capability composing AST-015 (fold/identity/authorized) to answer a governed session's lane/role/authorization deterministically at start; six-way output separation (identity/assignment/activation_prerequisites/grant/mutation_owner/gates/continuation); one bounded raw-read exception (v3HandoffRead).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0324] §"A governed AI session — Claude or DeepSeek — must be able to resolve its own registered lane / role / workflow state / authority deterministically at session start, from the SAME authoritative state, WITHOUT weakening a single governance rule."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0324] §"AST-017 holds no fold loop, no transition state machine, no state derivation. Every workflow fact comes from AST-015 invoked as a subprocess"
- CANDIDATE-OPERATIONAL-BIRTH: [S0329] §"The regressions S-18/S-19/S-20 were read, then independently reconstructed with fresh hermetic fixtures built through AST-015 in temp dirs ... never touching the live store"
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0341. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows). This DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0341 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0324 |
| type_signature | PRESENT | S0324 |
| invariants | PRESENT | S0324, S0325 |
| dependencies | PRESENT | S0324, S0325, S0341 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0324, S0325, S0337 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0329, S0341 |
| experiments | PRESENT | S0325, S0329 |
| open_questions | PRESENT | S0329 |

## Rationale
AST-017's processLabelsReferenced() merges two semantically different matches (a lane's registered actor vs any hex-shaped token mentioned in free prose, e.g. an independence-disclosure sentence naming a prior actor) into one set, producing a false AMBIGUOUS verdict whose surface grows monotonically as governed lanes accumulate prose referencing prior actors by design; the verdict stays safe (fail-closed) but is reasoned about for the wrong cause [S0341].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0324] types=[DEFINITION, PRINCIPLE] scope=OBJECT — "AST-017's one-line thesis: any governed AI session, regardless of model provider, must be able to deterministically resolve its own lane/role/state/authority from the single authoritative workflow record without weakening any governance rule; 'Resolution is not activation' — the report creates no authority, ownership, or state change." (anchor: "A governed AI session — Claude or DeepSeek — must be able to resolve its own registered lane / role / workflow state / authority deterministically at session start, from the SAME authoritative state, WITHOUT weakening a single governance rule.")
- [S0324] types=[CONSTRAINT, FORMALIZATION] scope=OBJECT — "AMENDMENT 2 delegation contract: AST-017 delegates every workflow fact (assignment, grant, mutation_owner) to AST-015 via askMechanism(fold/identity/authorized); if the mechanism is unavailable the bootstrap fails closed (UNRESOLVABLE) rather than reading records itself." (anchor: "AST-017 holds no fold loop, no transition state machine, no state derivation. Every workflow fact comes from AST-015 invoked as a subprocess")
- [S0324] types=[CONSTRAINT] scope=OBJECT — "The one bounded exception to AST-017's read-only delegation is a single named function reading exactly the HANDOFF-to/from-lane fact directly from the raw record, because no AST-015 read command yet exposes it (open finding V-3); regression S16 poisons the raw record to prove the bootstrap still reports fold-derived truth." (anchor: "AST-017 performs exactly ONE raw read, in the named function v3HandoffRead() ... the single HANDOFF-to/from-lane fact")
- [S0324] types=[FORMALIZATION, DISTINCTION] scope=OBJECT — "AST-017's output contract is a six-way separation, each a first-class report block: identity (evidence-only, never a grant input per INV-ATTR-1/2), assignment, activation_prerequisites (the G-3 prerequisites, never a status), grant, mutation_owner, gates, continuation, and meta." (anchor: "identity ≠ role ≠ eligibility ≠ authorization ≠ ownership ≠ continuation")
- [S0324] types=[CONSTRAINT, PRINCIPLE] scope=OBJECT — "Every non-RESOLVED verdict collapses to a uniform fail-closed report shape naming the missing fact, its source, and the responsible next actor, and never invents identity/authorization or performs a transition." (anchor: "Fail-closed mapping: any AMBIGUOUS/UNRESOLVED/UNRESOLVABLE ⇒ operable=false, authorized_to_act=false, current_session_can_continue=false, unresolved_message = missing fact · source · responsible next actor. Never invent; never transition.")
- [S0325] types=[CORRECTION] scope=OBJECT — "V-1 defect: recorded_human_start_act was derived from 'state !== CREATED', falsely claiming a human START for a lane that reached CANCELLED without ever being STARTED; corrected to derive from 'state === ACTIVE', the fold state AST-015 sets only on START/CONTINUATION." (anchor: "$recordedHumanStartAct = $selected['state'] !== 'CREATED'; // AFTER — $recordedHumanStartAct = $selected['state'] === 'ACTIVE';")
- [S0325] types=[CORRECTION] scope=OBJECT — "V-3 defect: the disambiguation message listed every discovered candidate rather than only the ones actually matching the process selector (e.g. listing a non-matching lane V3LANE-C); corrected by tracking an explicit $ambiguousMatches set and rendering only it, verified live to still leave the real a8ce5a39 case AMBIGUOUS without silently selecting a lane." (anchor: "meta.disambiguation_required rendered describeLanes($candidates) — every discovered candidate, not the ones that actually matched the process selector")
- [S0325] types=[CORRECTION, RESTATEMENT] scope=OBJECT — "V-5 defect: harness documentation referenced field name bootstrapping_status while the implementation already emitted activation_prerequisites; resolved by canonicalizing activation_prerequisites everywhere (no working code change needed) and pinning a regression asserting bootstrapping_status is never emitted." (anchor: "harness consumers referenced bootstrapping_status; the report emits activation_prerequisites. Mechanism and consumers disagreed.")
- [S0325] types=[VALIDATION, EXPERIMENTAL-RESULT] scope=OBJECT — "Eight named preservation properties (single-authority AST-015; AST-017 read-only; V-3 exception bounded to one fact; no second fold/engine; provider independence; ambiguity fail-closed; identity evidence-only; human START boundary intact) were each independently checked and all survive the correction; full suite 50/50 · 566 assertions green." (anchor: "Preservation conditions — all eight survive")
- [S0329] types=[EXPERIMENT, VALIDATION] scope=METHODOLOGICAL — "Independent verification methodology: rebuild the falsification fixtures from scratch through the canonical mechanism rather than re-running the author's own regression tests, so that a false-positive shared bug in the original tests could not silently pass twice." (anchor: "The regressions S-18/S-19/S-20 were read, then independently reconstructed with fresh hermetic fixtures built through AST-015 in temp dirs ... never touching the live store")
- [S0329] types=[WARNING, OPEN-QUESTION] scope=CROSS-OBJECT — "A prior verification artifact and its Governance registration attribute the same act to two different process identities (d1612e03 vs 8a525719); both may be genuine (different producing vs registering processes) but the inconsistency should be reconciled before any adoption decision." (anchor: "the prior verification artifact self-declares verifier d1612e03; the independent-verification registration attributes it to 8a525719 ... the naming inconsistency should be reconciled before the adoption decision")
- [S0337] types=[PRINCIPLE] scope=THEORY-LEVEL — "When a work item is named but no authoritative workflow record yet exists for it, the mechanism reports UNRESOLVABLE rather than treating the absence of a record as freedom to act; nothing can be registered or started until the record exists and a lane is attributed." (anchor: "'no record = ungoverned = free' is forbidden reasoning")
- [S0341] types=[LIMITATION, WARNING] scope=THEORY-LEVEL — "The entire governance chain's trust anchor — the transition log every REGISTER/HANDOFF/START in this batch is written into — lives only in a git-ignored working-tree file with no durability mechanism outside it; a lost or reset working tree would lose every governed lane and every recorded human act, sharpening a previously weaker concern about evidence durability into a concern about the durability of the registration mechanism itself." (anchor: "FU-1 · The authoritative workflow store is not durable ... .claude/runtime/workflow/*.json is the authoritative record ... A lost or reset working tree loses every governed lane, every recorded human START act, and therefore every attribution the system relies on.")
- [S0341] types=[LIMITATION, ANALYSIS] scope=OBJECT — "AST-017's processLabelsReferenced() merges two semantically different matches (a lane's registered actor vs any hex-shaped token mentioned in free prose, e.g. an independence-disclosure sentence naming a prior actor) into one set, producing a false AMBIGUOUS verdict whose surface grows monotonically as governed lanes accumulate prose referencing prior actors by design; the verdict stays safe (fail-closed) but is reasoned about for the wrong cause." (anchor: "FU-2 · Identity-label extraction cannot distinguish an assignment from a mention ... a8ce5a39 is the registered actor of S5 and, in S6, merely named in prose ... Both lanes therefore 'reference' a8ce5a39, and the resolver reports AMBIGUOUS")
- [S0341] types=[LIMITATION] scope=OBJECT — "A narrower artifact of the same extraction regex: a label immediately followed by a sentence-ending period is captured with the trailing period attached, which could in the worst case leave a legitimately ACTIVE lane's own actor resolving UNRESOLVED because its only recorded label form does not match." (anchor: "the label character class includes '.', so a label written immediately before a sentence-ending period is captured with the period ... A lane whose only label carried the period would not be attributable to its own actor at all: the actor would resolve UNRESOLVED despite holding an ACTIVE lane.")

## Notes for P3
Carries 3 candidate group membership(s); P3 should prioritize resolving whether these reflect the same underlying object. Lifecycle is DORMANT on recency heuristics only — no explicit retraction/supersession was found in this label's own rows.

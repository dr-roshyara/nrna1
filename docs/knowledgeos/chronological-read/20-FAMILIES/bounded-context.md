# bounded-context

**Scope(s):** OBJECT · **Row count:** 8 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** BC-1..BC-6 · **Aliases:** strategic bounded context
**Candidate group membership (NOT an identity claim):**
- G0031: co-listed with `platform-domain` — explicit agent-stated uncertainty (batch B0001): "The frozen Phase-02 six-context map; a candidate bounded context under Strategic DDD's admissible-justification criteria. Kept separate from platform-domain since the corpus itself treats BC-* and PD-*/D-* as two competing, not-yet-reconciled maps (see D-3/R-4)." Relationship not yet decided (P3).
- G0909: co-listed with `avacchedaka-context-bounded-identity` — working_label token overlap Jaccard=0.50 (shared tokens: bounded, context). Relationship not yet decided (P3).
- G0911: co-listed with `bounded-context-derivation-program` — working_label token overlap Jaccard=0.50 (shared tokens: bounded, context). Relationship not yet decided (P3).
- G0912: co-listed with `bounded-context-map` — working_label token overlap Jaccard=0.67 (shared tokens: bounded, context). Relationship not yet decided (P3).
- G0913: co-listed with `kos-bounded-context-candidates` — working_label token overlap Jaccard=0.50 (shared tokens: bounded, context). Relationship not yet decided (P3).
- G1089: co-listed with `viewpoint` — labels co-occur in the same contribution's labels[] 3 separate times across the corpus. Relationship not yet decided (P3).
- G1090: co-listed with `platform-domain` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus. Relationship not yet decided (P3).

## Sources (how this label entered the ledger)
- PROPOSAL, batch B0001, scope OBJECT, relation_to_existing="POSSIBLY:platform-domain" — "The frozen Phase-02 six-context map; a candidate bounded context under Strategic DDD's admissible-justification criteria. Kept separate from platform-domain since the corpus itself treats BC-* and PD-*/D-* as two competing, not-yet-reconciled maps (see D-3/R-4)."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0001 §"The ownership table — headers read, not inferred"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0001 §"The ownership table — headers read, not inferred"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0005. Candidate lifecycle: DORMANT.
Evidence: no retracted_by, no superseded_by, and contested_by_own_contradiction_type is false. DORMANT is a heuristic based on how long ago (by source_id) S0005 was last used — all rows are from early batch B0001 (2026-08-03) — not a confirmed retirement.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0001 (x2), S0002 (x2) |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0001 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0002 (x2), S0005 |
| assumptions | PRESENT | S0001 |
| semantics | PRESENT | S0001 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S0002 (x2) |

## Rationale
This object addresses whether ownership signals can be used to identify bounded contexts, and whether the platform's existing BC-1..BC-6 map is sound and reconciled with a competing PD-1..PD-6 platform-domain map. The initial test ("ownership signals bounded contexts") applied to a five-viewpoint ownership table found every governed platform-side viewpoint asset resolving to the same owner, the Decision Authority — an apparent argument against treating viewpoints as separate bounded contexts [S0001]. This inference was then explicitly walked back as "TOO STRONG": shared ownership is evidence against separate bounded contexts but not proof of a single one (a single Chief Architect could legitimately own three genuine bounded contexts), so the conclusion (viewpoints are not bounded contexts) is retained only jointly with an independent language test (no viewpoint has its own ubiquitous language) and an ARB "complementary siblings" ruling [S0001]. A broader discovery-coverage exercise found the bounded-context question already partially answered in the frozen corpus, but the context map itself is deliberately incomplete: only one edge is evidenced (the product binds the platform), and Evans/Vernon relationship-pattern assignment is withheld pending a standing ARB ruling ("the relationship is the conclusion"), tracked as blocking decision D-3 [S0002]. Two open questions are registered: R-2 (upstream/downstream pattern assignments are constrained, not missing, pending evidence) and R-4 (whether the frozen BC-1..BC-6 map and the PD-1..PD-6 platform-domain map reconcile, already queued as D-3) [S0002]. Independently, relationship R-8 (Product binds Platform, "Product Primacy as a direction of service") is validated at HIGH confidence, citing Team Topologies and matching AIP-14 [S0005].

## Assumption register
| Statement | Stated | Source ID | Anchor |
|---|---|---|---|
| ownership is one of the strongest signals for bounded contexts in DDD | EXPLICIT | S0001 | "Commission line" |

## All rows (source_id order)
- `[S0001]` types=[ANALYSIS, FORMALIZATION] scope=CROSS-OBJECT (co-labeled `viewpoint`), explicit_date=2026-08-03 — "A table maps each of five viewpoints (Semantic, Structural, Behavioral, Governance-hypothesis, Operational) to its carrying asset(s), its owner read verbatim from the asset's header (e.g. 'Owner: Decision Authority', 'owner: nab.raj.sharma'), and its ladder status (executable/CANDIDATE, DRAFT, operating)." (anchor: "The ownership table — headers read, not inferred")
- `[S0001]` types=[ANALYSIS] scope=THEORY-LEVEL (co-labeled `viewpoint`), explicit_date=2026-08-03 — "Running the reviewer's own test ('ownership signals bounded contexts') against the ownership table returns a verdict against viewpoints-as-contexts: every governed platform-side viewpoint asset resolves to the same owner, the Decision Authority..." Assumption (EXPLICIT): "ownership is one of the strongest signals for bounded contexts in DDD." (anchor: "Finding 1 — ownership does NOT cut along viewpoint lines")
- `[S0001]` types=[CORRECTION, DISTINCTION] scope=THEORY-LEVEL (co-labeled `viewpoint`), explicit_date=2026-08-03 — "The original one-owner-implies-one-context inference is downgraded: a single Chief Architect may legitimately own three genuine bounded contexts, so shared ownership is evidence against separate bounded contexts but insufficient by itself to establish a single one... viewpoints are not bounded contexts and must never be teamed, owned, or governed separately." Lineage claim: SOURCE-CLAIMED-REFINEMENT targeting "Finding 1 (single-owner convergence -> one context)" — quote: "the inference was TOO STRONG — ownership is evidence, not proof." (anchor: "REV 2 (review 2026-08-03): the inference was TOO STRONG — ownership is evidence, not proof")
- `[S0002]` types=[ANALYSIS, VALIDATION] scope=THEORY-LEVEL (co-labeled `mission`, `platform-domain`), explicit_date=2026-08-03 — "A table maps eleven commissioned questions (mission, vision, domains, bounded contexts, core/supporting/generic, context map, lifecycle, knowledge flow, capability model, runtime boundary, PKS boundary, product boundary) to where each is already answered in the frozen corpus, with a grade..., whether it was blind-validated, and the gate door it routes to." (anchor: "The answer map — every commissioned question → its existing answer")
- `[S0002]` types=[ANALYSIS, LIMITATION] scope=CROSS-OBJECT, explicit_date=2026-08-03 — "The context map records one evidenced edge (the product binds the platform) plus boundaries and a knowledge-flow/authority-flow inversion, but is deliberately incomplete: Evans/Vernon relationship patterns are withheld under a standing ARB ruling that 'the relationship is the conclusion'. D-3 blocks the map section of Phase C." Missing: "Evans/Vernon pattern assignment." Dependency: `D-3`. (anchor: "Context Map — one evidenced edge (the product BINDS the platform) + boundaries + the knowledge-flow/authority-flow inversion")
- `[S0002]` types=[OPEN-QUESTION] scope=CROSS-OBJECT, explicit_date=2026-08-03 — "R-2: upstream/downstream assignments per context are constrained rather than missing — a standing ARB ruling defers pattern assignment until evidence; one edge is already evidenced." (anchor: "R-2 | Upstream/downstream assignments per context ... constrained, not missing")
- `[S0002]` types=[OPEN-QUESTION] scope=CROSS-OBJECT (co-labeled `platform-domain`), explicit_date=2026-08-03 — "R-4: whether the platform's own frozen BC-1..BC-6 bounded-context map and PD-1..PD-6 platform-domain map reconcile is already queued as D-3; this commission adds urgency, not novelty." Dependency: `D-3`. (anchor: "R-4 | Whether the platform's own frozen BC-1..BC-6 map and PD-1..PD-6 reconcile ... already queued as D-3")
- `[S0005]` types=[VALIDATION] scope=CROSS-OBJECT, explicit_date=2026-08-03 — "Relationship R-8: Product binds Platform (Product Primacy as direction of service). External support: Team Topologies — platform teams exist to serve stream-aligned teams; the product team is the platform's customer; the direction matches AIP-14 exactly. No challenge. Confidence: HIGH." Dependency: `AIP-14`. (anchor: "R-8 | Product —binds→ Platform (Product Primacy as a direction of service) | HIGH")

## Notes for P3
This label carries an unusually large number of group_ids (7) — a strong candidate cluster around "bounded context" terminology (`bounded-context-map`, `bounded-context-derivation-program`, `kos-bounded-context-candidates`, `avacchedaka-context-bounded-identity`, `platform-domain`, `viewpoint`). None of these relationships are resolved here, per instructions, but P3 should prioritize this cluster given its size and the explicit unresolved reconciliation question (R-4/D-3: does the BC-1..BC-6 map reconcile with the PD-1..PD-6 platform-domain map?) that appears directly in this label's own rows. `family.files_touching` includes S0003, which does not appear in any of the 8 rows shown — a minor bookkeeping discrepancy. The evidentiary base is solid (5 distinct source documents from a single day, 2026-08-03) but entirely from one discovery session — no later corroboration or contradiction appears in this capture.

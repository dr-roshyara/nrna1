# Knowledge Harvest #3 — "Working with an AI Engineer" Best-Practices Article (ARB-reviewed, 2026-07-08)

**Aggregate:** `KnowledgeHarvest { Source, Patterns[], Evidence[], Decision }` — third consecutive harvest in the same shape (register row added for EPC-010).
**Source & provenance:** Anthropic best-practices article (explore→plan→implement · verification · interviewing · context hygiene), **as categorized by ARB review 2026-07-08; article text not stored** — patterns, not prose, are the knowledge.
**ARB verdict:** Tier 1 (9 practices) already implemented, mostly stronger — confirmations, no action. Tier 5 rejected (permissions/CLI/subagent/skills/plugins/MCP syntax — provider-binding internals). Tier 3 items map onto existing rules (small plans=EP-01 scope discipline · context clearing=economies · fresh reviewer=EPC-016/018 · investigation≠implementation=EP-03 · always-verify=philosophy).
**Closing insight on record:** the article optimizes **one coding session**; this platform optimizes **the engineering organization** — knowledge, evidence, and decisions across many features and sessions.

---

## EPC-015 · Verification Ladder
- *(Scaling note, reviewer 2026-07-08, for the retrospective: may later generalize to Verification **Strategy** → Ladder → Layer, where the ladder is one strategy among Automated/Human/Hybrid/Risk-based. Not restructured now.)*
- **Problem:** verification as a single step misses that assurance is layered; each layer should be cheaper and more automatic than the one above it.
- **Independence:** Yes. · **Existing:** the layers all exist — TDD (RED/GREEN) → automated gates (PHPStan/architecture suite) → gate runner (AST-010, C3) → adversarial review (AdversarialReview aggregate) → EP-02 Completion Review (human) → retrospective — but they are **not named as a ladder**, and nothing states which layers are mandatory per change class.
- **Assessment:** Equivalent-in-parts, weaker-in-articulation. · **Disposition:** Improvement Candidate (naming + a per-change-class ladder table; no new machinery).
- **Owner:** Verification & Evidence + Engineering Process. · **Earliest review:** PB-004 retrospective.
- **Required evidence:** PB-004 confusion about which verification layers a given change required.

## EPC-016 · Independent Verification ("fresh reviewer") *(renamed from "Routine Independent Verification" — ARB: "routine" is implementation policy; "independent" is the engineering principle)*
- **Problem:** implementer-reviewed work inherits the implementer's blind spots; an independent reviewer with **no shared reasoning context** catches what the author cannot.
- **Independence:** Yes ("let another Claude review it" is just independent review). · **Existing:** **structurally stronger than the article** — producer ≠ reviewer is an aggregate invariant (PD-10, FF-7), and AdversarialReview requires evidence-cited findings. Gap: independence is enforced at *certification/ticket* boundaries; it is not a routine step for ordinary implementation slices.
- **Assessment:** Stronger-in-structure, narrower-in-application. · **Disposition:** Improvement Candidate (extend routine application downward, e.g. an independent review pass per micro-slice class; the human EP-02 stays terminal).
- **Owner:** Adversarial Review Support / CAP-11. · **Earliest review:** PB-004 retrospective.
- **Required evidence:** a PB-004 defect that survived self-verification and would plausibly have been caught by an independent pass.

## EPC-017 · Engineering Interview Framework *(ARB: "the one thing I would absolutely add")*
- **Problem:** EP-03's "ask only what cannot be derived" leaves the *asking* unstructured; a guided interview (business → architecture → DDD → risk → implementation) turns gap-filling into a disciplined conversation — near-guided Event Storming.
- **Independence:** Yes. · **Existing:** EP-03 already mandates derive → identify unknowns → ask → stopping condition; the interview gives the ask-phase explicit structure — **and is conditional (ARB refinement):** `Repository Derivation → Gap Analysis → [unknowns? YES → Engineering Interview / NO → continue] → Approved Plan`. Zero gaps ⇒ repository → plan directly; the interview never becomes ceremony.
- **Assessment:** Natural evolution of EP-03, not a replacement. · **Disposition:** **Improvement Candidate — ARB priority 1 from this harvest.** Deliberately NOT amended into EP-03 today: EP-03 was frozen at ARB instruction ("resist adding"), and its validator is PB-004 — whose ERRs will show whether unstructured asking suffices or the interview structure earns its place.
- **Owner:** Implementation Guidance (EP-03 evolution — process, not capability). · **Earliest review:** PB-004 retrospective.
- **Required evidence:** PB-004 ERRs where the ask-phase was meandering, incomplete, or missed a domain the interview structure would have covered (note: the deferred Risk domain candidate folds naturally into an interview).

## EPC-018 · Fresh-Context Implementation (spec first, then a clean session)
- **Problem:** carrying a long design conversation into implementation pollutes the implementing context; the approved specification should be the ONLY bridge.
- **Independence:** Yes. · **Existing:** **already practiced deliberately** — the ARB's C3 ruling was exactly this ("approved — implement next session; the fresh session is itself the experiment"), and ERR→IDD→approved-plan→fresh-implementation is the structural equivalent of interview→SPEC.md→fresh session.
- **Assessment:** Equivalent in practice; not yet stated as a default rule. · **Disposition:** Improvement Candidate (thin: one sentence in the process — "major slices implement from the approved plan in a fresh session by default").
- **Owner:** Engineering Process. · **Earliest review:** PB-004 retrospective — C3 itself is the first evidence point.
- **Required evidence:** C3 + PB-004 slices demonstrating that fresh-context implementation from the plan alone was sufficient (or not).
- **ES-candidate wording on record (ARB):** *"Major implementation slices SHOULD begin in a fresh engineering session using only the approved plan."* — provider-independent; applies equally to any AI and to humans. Joins the Engineering Standards candidate queue.

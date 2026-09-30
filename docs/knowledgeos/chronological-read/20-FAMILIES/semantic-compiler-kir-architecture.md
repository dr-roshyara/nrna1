# semantic-compiler-kir-architecture

**Scope(s):** OBJECT · **Row count:** 20 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `KIR`, `Semantic Compiler` · **Aliases:** `Knowledge Intermediate Representation`, `Semantic AST pipeline`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.



## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0008, scope OBJECT): The compiler-analogy mechanism (Semantic Lexer/Parser/Type-Checker/Context-Binder/KIR-Generator/Expression-Generator) proposed as the operational engine translating expression into canonical meaning, kept explicitly outside the constitutional kernel; extensively prototyped and CPU-benchmarked in this batch (KOS-SCB v0.1/v0.2).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0292] §"the Semantic Compiler: Meaning -> Context -> Valid Expression, never Text -> Vector -> Similarity"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0307] §"Knowledge Intermediate Representation (KIR): an expression-agnostic canonical YAML/graph representation analogous to LLVM IR, sitting between Semantic AST and Reasoning/Validation."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0297] §"Artha Language is a mechanism, not the kernel: 'do not claim perfect meaning representation or elevate Artha to kernel status. It is a mechanism for reducing semantic distortion, not eliminating it.'"

## Lifecycle
last_seen: S0383. Candidate lifecycle: DORMANT. Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | PRESENT | S0295, S0310 |
| Informal meaning | PRESENT | S0292, S0308 |
| Formal definition | PRESENT | S0307, S0308, S0383 |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| Dependencies | PRESENT | S0383 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S0309, S0310, S0311 |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | PRESENT | S0309, S0310, S0311 |
| Open questions | PRESENT | S0307, S0309 |

## Rationale
Proposes a semantic-invariant-language mechanism with KARTA/KARMA/KARANA-style case-role encoding (an ASL: Artificial Semantic Language) where field order is irrelevant because identity comes from the semantic structure, not sequence [S0295]. A reviewer's explicit rejection of an intermediate turn's own additive-accuracy-projection table, reclassifying the claimed 93-95% figure from 'projected accuracy' to 'target hypothesis requiring measurement' [S0310].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0292] types=[DEFINITION] scope=OBJECT — "Names the internal-generation mechanism behind the already-admitted 'not text->vector->similarity' anti-embedding stance; the generative face of the kernel-vs-engine boundary." (anchor: "the Semantic Compiler: Meaning -> Context -> Valid Expression, never Text -> Vector -> Similarity")
- [S0292] types=[EXTENSION] scope=OBJECT — "Derived from Vedic Sanskrit's witnessed/unwitnessed/possible/conditional/intended tense-mood system; the mechanism-level representation for the temporal-validity kernel law and the structured-uncertainty hypothesis." (anchor: "temporal epistemic metadata: event time != knowledge state time (basis / confidence / time-horizon / observation status)")
- [S0295] types=[ALTERNATIVE] scope=OBJECT — "Proposes a semantic-invariant-language mechanism with KARTA/KARMA/KARANA-style case-role encoding (an ASL: Artificial Semantic Language) where field order is irrelevant because identity comes from the semantic structure, not sequence." (anchor: "Artha Language / VakyaOS / Jnana Language: a computer-oriented semantic language inspired by, not copying, Sanskrit grammar principles, operating on Artha (meaning) not words")
- [S0297] types=[GOVERNANCE] scope=OBJECT — "Explicit placement discipline classifying Karaka labeling, PERIN-style parsing, dhatu root-lineage, semantic sandhi, and case frames as mechanism-layer candidates, with 'Artha Language as kernel' and 'perfect meaning representation' both explicitly REJECTED." (anchor: "Artha Language is a mechanism, not the kernel: 'do not claim perfect meaning representation or elevate Artha to kernel status. It is a mechanism for reducing semantic distortion, not eliminating it.'")
- [S0307] types=[FORMALIZATION] scope=OBJECT — "The first worked KIR example (a Decision object with identity/semantic/epistemic/temporal/lifecycle fields) proposed as the missing intermediate-representation layer for a semantic-compiler architecture." (anchor: "Knowledge Intermediate Representation (KIR): an expression-agnostic canonical YAML/graph representation analogous to LLVM IR, sitting between Semantic AST and Reasoning/Validation.")
- [S0307] types=[HYPOTHESIS] scope=THEORY-LEVEL — "An unmeasured, purely architectural first-order estimate later explicitly retracted by the same author in a following turn of this same file ('not yet... my previous numbers were an architectural estimate, not a real simulation on a normal PC')." (anchor: "simulation projection: Semantic-first architecture is 5-20x faster and roughly 95%+ accurate on enterprise-reasoning tasks compared to a pure large-LLM approach.")
- [S0307] types=[FUTURE-RESEARCH] scope=METHODOLOGICAL — "The concrete experimental design later executed as the KOS-SCB v0.1 benchmark (S0309/S0310)." (anchor: "proposed benchmark experiment: build a small executable prototype and measure actual parsing/semantic-normalization/KIR-construction/reasoning latency, memory, throughput, semantic accuracy, contradiction detection, provenance preservation and transformation/re-expression accuracy on a normal CPU-on")
- [S0308] types=[FORMALIZATION] scope=OBJECT — "The most complete formal layer specification of the Semantic Compiler in this batch, including entity/relation type constraints and an 'epistemic validation error' analogy to compiler type errors (e.g. Decision missing DecisionMaker/InputEvidence/Reason)." (anchor: "five architecture layers: Semantic Lexer, Semantic Parser (Karaka), Knowledge Type Checking, Knowledge Intermediate Representation (KIR), Expression Generation")
- [S0308] types=[DEFINITION] scope=THEORY-LEVEL — "The one-sentence essence statement for the Semantic Compiler proposal, positioned as the operational engine beneath the constitutional kernel." (anchor: "KnowledgeOS is a semantic compiler that transforms expressions into accountable meaning structures (KIR), preserving identity, evidence, context, and uncertainty -- so that knowledge can evolve and be expressed in any language without losing its integrity.")
- [S0308] types=[EXTENSION] scope=OBJECT — "A DDD bounded-context proposal for the Semantic Compiler, explicit about what it does and does not own (truth/authority/evidence-creation excluded)." (anchor: "Semantic Interpretation Context (proposed Supporting Bounded Context): convert human and machine expressions into a stable semantic representation without losing identity, context, evidence, or uncertainty; in scope = lexing/parsing/context-binding/type-checking/KIR-generation; out of scope = truth ")
- [S0309] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "The first genuinely measured (not estimated) data point in the semantic-compiler research thread, explicitly caveated as measuring rule-engine overhead for a tiny grammar, not a production semantic compiler." (anchor: "first measured CPU-only prototype benchmark: English-approval, German-approval, English-rejection, German-rejection sentences parsed at roughly 0.001-0.0015 ms mean latency each.")
- [S0309] types=[FUTURE-RESEARCH] scope=METHODOLOGICAL — "A concrete next-benchmark design (100-500 sentences) executed in the following file as the actual 120-case KOS-SCB v0.1 run." (anchor: "KOS-SCB v0.1 proposed test categories: word-order variation, English<->German, active<->passive, meaning-changing sentences, context (temporal/causal/concessive), and contradiction.")
- [S0309] types=[PRINCIPLE] scope=THEORY-LEVEL — "States the intended architectural relationship between an LLM (semantic-hypothesis generator) and the deterministic KnowledgeOS machinery (epistemic authority) -- explicitly contrasted with ordinary RAG systems." (anchor: "the LLM does not get to directly write KnowledgeOS knowledge. It proposes a semantic interpretation. The compiler validates it.")
- [S0310] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "The first full measured KOS-SCB benchmark run, broken down by capability (expression equivalence 70%, meaning changes 80%, context preservation 75%, safe abstention 70%); interpreted as demonstrating that the computational model is cheap while the semantic model is hard." (anchor: "KOS-SCB v0.1 measured result: 120 test cases, 74.2% overall semantic accuracy, mean parsing latency 0.0081 ms (median 0.0057 ms, P95 0.0093 ms), ~123,000 parses/sec, peak traced Python memory ~0.05 MB; 6 false accepts among 20 deliberately ambiguous cases.")
- [S0310] types=[ANALYSIS] scope=OBJECT — "A reviewer's explicit rejection of an intermediate turn's own additive-accuracy-projection table, reclassifying the claimed 93-95% figure from 'projected accuracy' to 'target hypothesis requiring measurement'." (anchor: "accuracy improvements from adding layers are not additive: 74.2% + 20% + 10% + 25% + 15% + 19% + 25% -> ~95% is not a valid statistical inference, because later stages can fix the same failures earlier stages already fixed (overlapping gains).")
- [S0310] types=[DISTINCTION] scope=THEORY-LEVEL — "A fundamental distinction the benchmark surfaced: understanding an expression correctly is not the same question as whether there are sufficient grounds to believe the claim it expresses." (anchor: "semantic accuracy != epistemic correctness: the compiler can correctly interpret 'the architect approved the design' (semantic interpretation = correct) while KnowledgeOS must still return epistemic status = UNKNOWN because there is no evidence.")
- [S0311] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "A controlled toy simulation (not real NLP) explicitly showing non-collapse succeeding (approve vs reject never collapse) while contradiction handling is 0% because the toy compiler treats semantically different claims as merely different propositions rather than as a claim-pair requiring same-subject/incompatible-proposition comparison." (anchor: "KOS-SCB v0.2 toy-simulation result: 1,000 synthetic cases, overall invariance score 80.0%, expression equivalence 66.7%, semantic distinction 100%, context preservation 100%, transformation preservation 100%, temporal distinction 100%, unknown/ambiguity handling 100%, contradiction handling 0%, abst")
- [S0311] types=[PRINCIPLE] scope=THEORY-LEVEL — "Replaces the earlier 'target 95% accuracy' success criterion with five distinct, separately-measured properties, explicitly rejecting a single composite score." (anchor: "five redefined success properties: Semantic Invariance (equivalent expressions converge), Semantic Non-Collapse (different meanings stay different), Epistemic Non-Invention (insufficient evidence -> UNKNOWN), Identity Preservation (revision preserves history, K1 != K2), Contradiction Integrity (inco")
- [S0312] types=[CONSTRAINT] scope=OBJECT — "A firm boundary rule for the Semantic Compiler's placement relative to the constitutional core, with an explicit feedback loop (architecture hypothesis -> prototype -> observed failure -> KEEP/MOVE/REJECT classification) for using experimental evidence without letting it redesign the kernel prematurely." (anchor: "keep the Semantic Compiler outside the constitutional kernel: the compiler can be replaced; the kernel cannot -- this protects KnowledgeOS from becoming 'a sophisticated NLP system with philosophical decoration.'")
- [S0383] types=[DEFINITION] scope=METHODOLOGICAL — "Defines the Paninian/Sanskrit-grammar lens (distinct from Vani: can expression be transformed deterministically into structured representation via explicit rules -- Expression -> morphological/syntactic analysis -> semantic roles -> structured candidate), concluding the architectural lesson is not to build Sanskrit into the Kernel but that a deterministic semantic compiler may exist outside it." (anchor: "4. Paninian / Sanskrit Grammar Lens -- Generate Structure from Rules ... Can expression be transformed deterministically into structured representation through explicit rules? ... A deterministic semantic compiler can exist outside the Kernel.")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.

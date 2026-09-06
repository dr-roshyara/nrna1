Here is a complete C4 architecture diagram for KnowledgeOS in PlantUML, showing all four levels of abstraction from System Context down to Code.

---

## Level 1: System Context Diagram

This shows KnowledgeOS as a system interacting with external actors and systems.

```plantuml
@startuml KnowledgeOS_SystemContext
!include https://raw.githubusercontent.com/plantuml-stdlib/C4-PlantUML/master/C4_Context.puml

LAYOUT_WITH_LEGEND()

title KnowledgeOS - System Context (Level 1)

Person(knower, "Knower", "Human decision-maker who owns the decision and action")
Person(admin, "System Administrator", "Governs policies, roles, and authorities")

System(knowledgeos, "KnowledgeOS", "Epistemic-Normative-Decision-Action System\nProvides governed knowledge, guidance, and investigation")

System_Ext(documents, "Document Sources", "PDFs, Word, Markdown, Confluence, SharePoint")
System_Ext(apis, "API Sources", "REST APIs, Databases, Git, Jira")
System_Ext(llm, "LLM Services", "GPT-4, Llama 3, Claude for interpretation")
System_Ext(enterprise, "Enterprise Systems", "Monitoring, Deployment, Ticketing")

Rel(knower, knowledgeos, "Interacts with", "Query, Clarify, Decide, Act")
Rel(admin, knowledgeos, "Governs", "Policy, Role, Authority")
Rel(knowledgeos, documents, "Ingests", "HTTP/File")
Rel(knowledgeos, apis, "Queries", "HTTP/SQL")
Rel(knowledgeos, llm, "Requests interpretation", "REST API")
Rel(knowledgeos, enterprise, "Executes actions", "API")

@enduml
```

---

## Level 2: Container Diagram

This shows the major containers (services/applications) within KnowledgeOS.

```plantuml
@startuml KnowledgeOS_Containers
!include https://raw.githubusercontent.com/plantuml-stdlib/C4-PlantUML/master/C4_Container.puml

LAYOUT_WITH_LEGEND()

title KnowledgeOS - Container Diagram (Level 2)

Person(knower, "Knower", "Human decision-maker")

System_Boundary(kos, "KnowledgeOS") {
    
    Container(api_gateway, "API Gateway", "GraphQL/REST", "Entry point for all interactions\nHandles authentication and routing")
    
    Container(source_layer, "Source Layer", "Python/Go", "Ingests documents, APIs, and events\nCreates Artifacts and SourceObservations")
    
    Container(semantic_layer, "Semantic Reconstruction", "Python/PyTorch", "Parses text structurally and semantically\nProduces CandidateAssertions")
    
    Container(knowledge_state, "Knowledge State", "Neo4j/PostgreSQL", "Stores assertions, evidence, relationships\nMaintains epistemic history")
    
    Container(zero_lens, "Zero Lens", "Python", "Detects gaps, conflicts, and boundaries\nDiagnoses epistemic deficiencies")
    
    Container(lord_lens, "Lord Lens", "Python", "Generates candidate dimensions\nHypothesizes and synthesizes")
    
    Container(sarathi_lens, "Sārathi Lens", "Python", "Provides contextual guidance\nRecommends investigations")
    
    Container(decision_engine, "Decision Engine", "Python", "Assesses decision readiness\nPresents recommendations")
    
    Container(action_executor, "Action Executor", "Python", "Executes authorized actions\nTracks outcomes")
    
    Container(knowledge_ui, "Knowledge UI", "React/TypeScript", "Web interface for Knower\nPresents knowledge, gaps, guidance")
}

System_Ext(documents, "Document Sources")
System_Ext(apis, "API Sources")
System_Ext(llm, "LLM Services")
System_Ext(enterprise, "Enterprise Systems")

Rel(knower, api_gateway, "Interacts via", "HTTPS")
Rel(api_gateway, knowledge_ui, "Serves", "GraphQL")
Rel(knower, knowledge_ui, "Views", "Browser")

Rel(source_layer, documents, "Ingests", "HTTP/File")
Rel(source_layer, apis, "Queries", "HTTP/SQL")
Rel(source_layer, knowledge_state, "Creates observations", "Events")

Rel(semantic_layer, source_layer, "Processes observations", "Events")
Rel(semantic_layer, llm, "Uses for interpretation", "REST API")
Rel(semantic_layer, knowledge_state, "Creates candidate assertions", "Events")

Rel(knowledge_state, zero_lens, "Analyzes", "Query")
Rel(knowledge_state, lord_lens, "Analyzes", "Query")
Rel(knowledge_state, sarathi_lens, "Analyzes", "Query")

Rel(zero_lens, lord_lens, "Feeds gaps", "Events")
Rel(lord_lens, sarathi_lens, "Feeds candidates", "Events")
Rel(sarathi_lens, decision_engine, "Feeds guidance", "Events")

Rel(decision_engine, knower, "Presents decisions", "UI")
Rel(knower, action_executor, "Authorizes actions", "API")
Rel(action_executor, enterprise, "Executes", "API")
Rel(action_executor, knowledge_state, "Records outcomes", "Events")

@enduml
```

---

## Level 3: Component Diagram

This shows the internal components of each container.

```plantuml
@startuml KnowledgeOS_Components
!include https://raw.githubusercontent.com/plantuml-stdlib/C4-PlantUML/master/C4_Component.puml

LAYOUT_WITH_LEGEND()

title KnowledgeOS - Component Diagram (Level 3)

Container(kos, "KnowledgeOS", "Epistemic-Normative-Decision-Action System")

System_Boundary(source, "Source Layer") {
    Component(connector, "Source Connector", "Ingests documents/APIs")
    Component(artifact_store, "Artifact Store", "Raw content storage")
    Component(observation_factory, "Observation Factory", "Creates SourceObservations")
    Component(tokenizer, "Tokenizer", "BPE tokenization")
}

System_Boundary(semantic, "Semantic Reconstruction") {
    Component(structural_parser, "Structural Parser", "C-like grammar parser")
    Component(semantic_parser, "Semantic Parser", "Sanskrit-style semantic parser")
    Component(llm_interface, "LLM Interface", "Calls LLM services")
    Component(proposition_constructor, "Proposition Constructor", "Creates CandidateAssertions")
}

System_Boundary(state, "Knowledge State") {
    Component(assertion_store, "Assertion Store", "Stores assertions with epistemic states")
    Component(evidence_store, "Evidence Store", "Stores evidence and relationships")
    Component(history_store, "History Store", "Append-only event log")
    Component(provenance_tracker, "Provenance Tracker", "Tracks lineage")
}

System_Boundary(epistemic, "Epistemic Engine") {
    Component(zero, "Zero Lens", "Gap/Conflict detection")
    Component(lord, "Lord Lens", "Candidate generation")
    Component(sarathi, "Sārathi Lens", "Guidance generation")
}

System_Boundary(decision, "Decision Layer") {
    Component(readiness, "Decision Readiness", "Assesses sufficiency")
    Component(presentation, "Presentation Projection", "Formats for user")
}

System_Boundary(action, "Action Layer") {
    Component(authorization, "Authorization", "Checks authority")
    Component(executor, "Action Executor", "Executes commands")
    Component(outcome_tracker, "Outcome Tracker", "Records outcomes")
}

' Relationships
Rel(connector, artifact_store, "Creates artifacts")
Rel(artifact_store, observation_factory, "Processes artifacts")
Rel(observation_factory, tokenizer, "Tokenizes content")
Rel(observation_factory, semantic, "Sends SourceObservations")

Rel(structural_parser, tokenizer, "Uses for tokenization")
Rel(structural_parser, semantic_parser, "Feeds syntax trees")

Rel(semantic_parser, llm_interface, "Uses for interpretation")
Rel(semantic_parser, proposition_constructor, "Creates candidates")

Rel(proposition_constructor, assertion_store, "Stores candidates")
Rel(assertion_store, evidence_store, "Links evidence")
Rel(assertion_store, history_store, "Logs events")
Rel(evidence_store, provenance_tracker, "Tracks lineage")

Rel(assertion_store, zero, "Queries state")
Rel(zero, lord, "Feeds gaps")
Rel(lord, sarathi, "Feeds candidates")
Rel(sarathi, decision, "Feeds guidance")

Rel(decision, readiness, "Assesses")
Rel(readiness, presentation, "Formats")

Rel(presentation, action, "Presents recommendations")
Rel(action, authorization, "Checks authority")
Rel(authorization, executor, "Executes if authorized")
Rel(executor, outcome_tracker, "Records outcomes")

@enduml
```

---

## Level 4: Code Diagram

This shows key classes/interfaces for critical components.

```plantuml
@startuml KnowledgeOS_Code
!include https://raw.githubusercontent.com/plantuml-stdlib/C4-PlantUML/master/C4_Component.puml

title KnowledgeOS - Code Diagram (Level 4)

' ====================================================
' Core Types
' ====================================================
package "Core Types" {
    class Artifact {
        +id: ArtifactID
        +source: SourceRef
        +content: RawContent
        +acquired_at: Timestamp
        +method: AcquisitionMethod
        +provenance: ProvenanceRef
    }
    
    class SourceObservation {
        +id: SOID
        +artifact_id: ArtifactID
        +content: RawContent
        +method: ObservationMethod
        +observed_at: Timestamp
    }
    
    class Proposition {
        +id: PropID
        +type: PropType
        +subject: EntityRef
        +predicate: String
        +object: Value
    }
    
    class Assertion {
        +id: AssertionID
        +proposition: Proposition
        +epistemic_state: Sigma
        +evidence: List<Evidence>
        +lineage: LineageRef
    }
    
    class Evidence {
        +id: EvID
        +proposition: Proposition
        +source_observation: SourceObservation
        +relation_type: EvidentialRelation
        +quality: EvidenceQuality
    }
}

' ====================================================
' Structural Parser (C-like)
' ====================================================
package "Structural Parser (C-like)" {
    class CLikeParser {
        +parse(text: str) -> SyntaxTree
        -tokenize(text: str) -> List[Token]
        -apply_grammar(tokens: List[Token]) -> SyntaxTree
        -detect_dependencies(tree: SyntaxTree) -> Dependencies
    }
    
    class GrammarRules {
        +sentence_rules: List[Rule]
        +clause_rules: List[Rule]
        +phrase_rules: List[Rule]
        +dependency_rules: List[Rule]
    }
    
    class SyntaxTree {
        +nodes: List[SyntaxNode]
        +edges: List[Dependency]
        +to_json() -> JSON
    }
}

' ====================================================
' Semantic Parser (Sanskrit-style)
' ====================================================
package "Semantic Parser (Sanskrit-style)" {
    class SanskritParser {
        +interpret(syntax: SyntaxTree, ctx: Context) -> List[SemanticStructure]
        -map_to_roles(syntax: SyntaxTree) -> List[Role]
        -apply_relation_rules(roles: List[Role]) -> List[Relation]
        -apply_compound_rules(syntax: SyntaxTree) -> List[Compound]
        -resolve_ambiguities(interpretations: List) -> List
    }
    
    class RelationRules {
        +KARTA: Rule
        +KARMA: Rule
        +KARANA: Rule
        +SAMPADA: Rule
        +APADANA: Rule
        +ADHIKARANA: Rule
        +SAMBANDHA: Rule
    }
    
    class CompoundRules {
        +TATPURUSHA: Rule
        +KARMADHARAYA: Rule
        +DWANDWA: Rule
        +BAHUWREEHI: Rule
        +AVYAYEEBHAVA: Rule
    }
}

' ====================================================
' Epistemic Lenses
' ====================================================
package "Epistemic Lenses" {
    class ZeroLens {
        +detect_gaps(K: KnowledgeState, I: IdealState) -> List[Gap]
        +detect_conflicts(K: KnowledgeState) -> List[Conflict]
        +assess_coherence(K: KnowledgeState) -> CoherenceReport
    }
    
    class LordLens {
        +generate_candidates(K: KnowledgeState, gaps: List[Gap]) -> List[CandidateDimension]
        +synthesize(assertions: List[Assertion]) -> Assertion
        +hypothesize(K: KnowledgeState, I: IdealState) -> List[Hypothesis]
    }
    
    class SārathiLens {
        +recommend(K: KnowledgeState, gaps: List[Gap]) -> Recommendation
        +plan_investigation(gaps: List[Gap]) -> InvestigationPlan
        +contextualize(guidance: Guidance, ctx: Context) -> Guidance
    }
}

' ====================================================
' Evidence Assessment
' ====================================================
package "Evidence Assessment" {
    class EvidenceAssessor {
        +assess(evidence: Evidence, policy: Policy) -> EvidenceAssessment
        +aggregate(evidences: List[Evidence]) -> EvidenceAssessment
        -compute_reliability(source: Source) -> Float
        -compute_relevance(evidence: Evidence, prop: Proposition) -> Float
        -compute_currency(evidence: Evidence) -> Float
        -compute_independence(evidence: Evidence, graph: EvidenceGraph) -> Float
    }
}

' ====================================================
' Relationships
' ====================================================
CLikeParser --> GrammarRules : uses
CLikeParser --> SyntaxTree : creates

SanskritParser --> RelationRules : uses
SanskritParser --> CompoundRules : uses

ZeroLens --> Assertion : reads
ZeroLens --> Evidence : reads

LordLens --> ZeroLens : receives gaps from
LordLens --> Assertion : reads

SārathiLens --> ZeroLens : receives gaps from
SārathiLens --> LordLens : receives candidates from

EvidenceAssessor --> SourceObservation : reads
EvidenceAssessor --> Proposition : references

@enduml
```

---

## Full C4 Model (All Levels Combined)

This gives you a complete view of all levels together:

```plantuml
@startuml KnowledgeOS_Full_C4
!include https://raw.githubusercontent.com/plantuml-stdlib/C4-PlantUML/master/C4_Context.puml
!include https://raw.githubusercontent.com/plantuml-stdlib/C4-PlantUML/master/C4_Container.puml
!include https://raw.githubusercontent.com/plantuml-stdlib/C4-PlantUML/master/C4_Component.puml

title KnowledgeOS - Complete C4 Architecture

' ====================================================
' Level 1: System Context
' ====================================================
LAYOUT_WITH_LEGEND()

Person(knower, "Knower", "Human decision-maker")

System(knowledgeos, "KnowledgeOS", "Epistemic-Normative-Decision-Action System")

System_Ext(documents, "Document Sources", "PDFs, Word, Markdown")
System_Ext(apis, "API Sources", "REST, SQL, Git")
System_Ext(llm, "LLM Services", "GPT-4, Llama 3")
System_Ext(enterprise, "Enterprise Systems", "Monitoring, Deployment")

Rel(knower, knowledgeos, "Interacts")
Rel(knowledgeos, documents, "Ingests")
Rel(knowledgeos, apis, "Queries")
Rel(knowledgeos, llm, "Requests")
Rel(knowledgeos, enterprise, "Executes")

' ====================================================
' Level 2: Containers (Boundary)
' ====================================================
System_Boundary(kos, "KnowledgeOS") {
    
    Container(api, "API Gateway", "GraphQL/REST", "Entry point")
    Container(ui, "Knowledge UI", "React", "Web interface")
    
    Container(source, "Source Layer", "Python", "Ingests sources")
    Container(semantic, "Semantic Reconstruction", "Python/PyTorch", "Parses text")
    Container(state, "Knowledge State", "Neo4j/PostgreSQL", "Stores knowledge")
    
    Container(zero, "Zero Lens", "Python", "Detects gaps")
    Container(lord, "Lord Lens", "Python", "Generates candidates")
    Container(sarathi, "Sārathi Lens", "Python", "Provides guidance")
    
    Container(decision, "Decision Engine", "Python", "Assesses readiness")
    Container(action, "Action Executor", "Python", "Executes actions")
}

' Level 2 relationships
Rel(knower, api, "Uses")
Rel(api, ui, "Serves")
Rel(knower, ui, "Views")

Rel(source, documents, "Ingests")
Rel(source, apis, "Queries")
Rel(source, state, "Creates observations")

Rel(semantic, source, "Processes")
Rel(semantic, llm, "Uses")
Rel(semantic, state, "Creates candidates")

Rel(state, zero, "Analyzes")
Rel(state, lord, "Analyzes")
Rel(state, sarathi, "Analyzes")

Rel(zero, lord, "Feeds gaps")
Rel(lord, sarathi, "Feeds candidates")
Rel(sarathi, decision, "Feeds guidance")

Rel(decision, knower, "Presents")
Rel(knower, action, "Authorizes")
Rel(action, enterprise, "Executes")

' ====================================================
' Level 3: Components (Inside Source Layer)
' ====================================================
System_Boundary(source_components, "Source Layer Components") {
    Component(connector, "Source Connector")
    Component(artifact_store, "Artifact Store")
    Component(observation_factory, "Observation Factory")
    Component(tokenizer, "Tokenizer")
}

Rel(connector, artifact_store, "Creates")
Rel(artifact_store, observation_factory, "Processes")
Rel(observation_factory, tokenizer, "Tokenizes")

' ====================================================
' Level 3: Components (Inside Semantic Layer)
' ====================================================
System_Boundary(semantic_components, "Semantic Reconstruction Components") {
    Component(structural_parser, "Structural Parser", "C-like")
    Component(semantic_parser, "Semantic Parser", "Sanskrit-style")
    Component(llm_interface, "LLM Interface")
    Component(proposition_constructor, "Proposition Constructor")
}

Rel(structural_parser, semantic_parser, "Feeds")
Rel(semantic_parser, llm_interface, "Uses")
Rel(semantic_parser, proposition_constructor, "Creates")

@enduml
```

---

## How to Use These Diagrams

1. **Copy and paste** any of the PlantUML code blocks into a `.puml` file.

2. **Render** using:
   - PlantUML plugin in VS Code/IntelliJ
   - PlantUML online server (https://www.plantuml.com/plantuml/uml)
   - Command line: `plantuml -tpng filename.puml`

3. **Levels**:
   - **Level 1**: For stakeholders, executives
   - **Level 2**: For architects, platform team
   - **Level 3**: For developers, engineers
   - **Level 4**: For implementers

4. **Customize**:
   - Add your specific source types (PDF, Word, Git, Jira)
   - Add your specific enterprise systems
   - Expand code level with your exact classes

The diagrams follow C4 standards and integrate the KnowledgeOS theory (Zero/Lord/Sārathi, Source/Knowledge planes, Evidence Assessment, etc.).
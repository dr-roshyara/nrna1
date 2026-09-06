# KNOWLEDGEOS — COMPLETE FLOWCHART

**Date:** 2026-08-31  
**Version:** 1.0  
**Purpose:** Visual representation of the complete KnowledgeOS pseudo-algorithm

---

## Part 1: High-Level Flowchart (Main Pipeline)

```mermaid
flowchart TD
    START([START]) --> INPUT[Receive Input]
    INPUT --> PHASE1
    
    subgraph PHASE1["PHASE 1: OBSERVE (Sañjaya)"]
        A1[Create Artifact] --> A2[Create SourceObservation]
        A2 --> A3[Structural Parse<br/>C-like Parser]
        A3 --> A4[Semantic Parse<br/>Sanskrit-style Parser]
        A4 --> A5[SemanticInterpretation]
    end
    
    PHASE1 --> PHASE2
    
    subgraph PHASE2["PHASE 2: ASSESS (Arjuna)"]
        B1[Create CandidateAssertion] --> B2[Gather Evidence]
        B2 --> B3[Assess Evidence Quality]
        B3 --> B4[Determine Epistemic State Σ]
    end
    
    PHASE2 --> DECISION1{Should Admit?}
    DECISION1 -->|NO| REJECT[Reject Candidate]
    DECISION1 -->|YES| PHASE3
    
    subgraph PHASE3["PHASE 3: TRANSFORM (Krishna)"]
        C1[Create Assertion] --> C2[Update Knowledge State K]
        C2 --> C3[Update Knowledge Ātma]
        C3 --> C4[Record in History H]
    end
    
    PHASE3 --> PHASE4
    
    subgraph PHASE4["PHASE 4: GUIDE (Krishna/Sārathi)"]
        D1[Zero Lens: Detect Gaps & Conflicts] --> D2[Lord Lens: Generate Candidates]
        D2 --> D3[Sārathi Lens: Provide Guidance]
    end
    
    PHASE4 --> PHASE5
    
    subgraph PHASE5["PHASE 5: DECIDE (Arjuna)"]
        E1{Decision Available?} -->|NO| E2[Return: Human Required / Insufficient]
        E1 -->|YES| E3[Evaluate Options]
        E3 --> E4{Governance Check}
        E4 -->|FAIL| E5[Return: Governance Blocked]
        E4 -->|PASS| E6[Select Best Option]
        E6 --> E7[Return: Decision]
    end
    
    PHASE5 --> PHASE6
    
    subgraph PHASE6["PHASE 6: LEARN (Arjuna's Transformation)"]
        F1[Record Decision in History] --> F2[Refine Knowledge]
        F2 --> F3[Update Epistemic State]
    end
    
    PHASE6 --> CHECK{Continue?}
    CHECK -->|YES| INPUT
    CHECK -->|NO| END([END])
    
    REJECT --> CHECK
    E2 --> CHECK
    E5 --> CHECK
```

---

## Part 2: Detailed Observation Phase (Sañjaya)

```mermaid
flowchart TD
    START_OBSERVE([OBSERVE PHASE]) --> INPUT[Raw Input]
    
    INPUT --> STEP1[Step 1: Create Artifact]
    STEP1 --> DETAIL1["DETAILS:<br/>• id: generate_id()<br/>• source: from source_info<br/>• content: raw input<br/>• content_type: text/json/etc<br/>• acquired_at: now()<br/>• method: file/sql/llm/etc<br/>• context: from source_info<br/>• provenance: actor, source, time"]
    
    DETAIL1 --> STEP2[Step 2: Create SourceObservation]
    STEP2 --> DETAIL2["DETAILS:<br/>• id: generate_id()<br/>• artifact_id: from artifact<br/>• content: artifact.content<br/>• method: artifact.method<br/>• observed_at: now()<br/>• context: artifact.context<br/>• provenance: actor, source, time"]
    
    DETAIL2 --> STEP3[Step 3: Structural Parse (C-like)]
    STEP3 --> DETAIL3["DETAILS:<br/>• Tokenization<br/>• Grammar rules<br/>• Syntax tree<br/>• Dependencies"]
    
    DETAIL3 --> STEP4[Step 4: Semantic Parse (Sanskrit-style)]
    STEP4 --> DETAIL4["DETAILS:<br/>• Entity recognition<br/>• Dimension discovery<br/>• Value extraction<br/>• Relationship detection<br/>• Ontology mapping<br/>• Context interpretation"]
    
    DETAIL4 --> STEP5[Step 5: SemanticInterpretation]
    STEP5 --> DETAIL5["OUTPUT:<br/>• id: generate_id()<br/>• source_observation_id<br/>• entities: List[Entity]<br/>• dimensions: List[Dimension]<br/>• values: Dict[Dimension, Value]<br/>• relationships: List[Relationship]<br/>• confidence: 0.0-1.0<br/>• interpretation_method<br/>• interpreted_at: now()<br/>• context<br/>• alternatives: List[Interpretation]<br/>• provenance"]
    
    DETAIL5 --> END_OBSERVE([RETURN TO MAIN])
```

---

## Part 3: Detailed Assessment Phase (Arjuna)

```mermaid
flowchart TD
    START_ASSESS([ASSESS PHASE]) --> INPUT[SemanticInterpretation]
    
    INPUT --> STEP6[Step 6: Create Proposition]
    STEP6 --> DETAIL6["DETAILS:<br/>• id: generate_id()<br/>• entity: from interpretation<br/>• dimension: from interpretation<br/>• value: from interpretation<br/>• context: from interpretation<br/>• truth_condition: description"]
    
    DETAIL6 --> STEP7[Step 7: Create CandidateAssertion]
    STEP7 --> DETAIL7["DETAILS:<br/>• id: generate_id()<br/>• proposition: from Step 6<br/>• semantic_interpretation_id<br/>• status: 'pending'<br/>• created_at: now()<br/>• context<br/>• provenance"]
    
    DETAIL7 --> STEP8[Step 8: Gather Evidence]
    STEP8 --> DETAIL8["DETAILS:<br/>• id: generate_id()<br/>• proposition_id<br/>• source_observation_id<br/>• relation_type: supports/contradicts/qualifies<br/>• quality: EvidenceQuality<br/>• temporal_validity<br/>• provenance"]
    
    DETAIL8 --> STEP9[Step 9: Assess Evidence Quality]
    STEP9 --> DETAIL9["DETAILS:<br/>• reliability: from source type<br/>• relevance: keyword matching<br/>• currency: age check<br/>• independence: dependency graph<br/>• completeness: based on evidence"]
    
    DETAIL9 --> STEP10[Step 10: Determine Epistemic State Σ]
    STEP10 --> DETAIL10["DETAILS:<br/>• acquisition: observed/reported/inferred/etc<br/>• support: none/weak/moderate/strong/very_strong<br/>• resolution: open/in_progress/resolved<br/>• validity: current/stale/expired/unknown<br/>• conflict: none/potential/active/resolved"]
    
    DETAIL10 --> DECISION{Should Admit?}
    DECISION -->|NO| REJECT[Reject Candidate]
    DECISION -->|YES| END_ASSESS([RETURN TO MAIN])
    
    REJECT --> END_ASSESS
```

---

## Part 4: Detailed Transformation Phase (Krishna)

```mermaid
flowchart TD
    START_TRANSFORM([TRANSFORM PHASE]) --> INPUT[Admitted Candidate + Evidence + Σ]
    
    INPUT --> STEP11[Step 11: Create Assertion]
    STEP11 --> DETAIL11["DETAILS:<br/>• id: generate_id()<br/>• proposition: from candidate<br/>• epistemic_state: Σ<br/>• evidence: List[Evidence]<br/>• lineage: Lineage<br/>• asserted_at: now()<br/>• context<br/>• provenance"]
    
    DETAIL11 --> STEP12[Step 12: Update Knowledge State K]
    STEP12 --> DETAIL12["DETAILS:<br/>• assertions: add new assertion<br/>• relationships: add relationships<br/>• epistemic_state: aggregate<br/>• evidence: add evidence<br/>• updated_at: now()<br/>• version: increment"]
    
    DETAIL12 --> STEP13[Step 13: Update Knowledge Ātma]
    STEP13 --> DETAIL13["DETAILS:<br/>• Check if proposition exists<br/>• If new: create Atma<br/>• Add to lineage<br/>• Map: proposition → Atma"]
    
    DETAIL13 --> STEP14[Step 14: Record in History H]
    STEP14 --> DETAIL14["DETAILS:<br/>• id: generate_id()<br/>• type: 'assertion'<br/>• payload: assertion + evidence<br/>• timestamp: now()<br/>• actor: from actor state<br/>• policy_version<br/>• provenance"]
    
    DETAIL14 --> END_TRANSFORM([RETURN TO MAIN])
```

---

## Part 5: Detailed Guidance Phase (Zero → Lord → Sārathi)

```mermaid
flowchart TD
    START_GUIDE([GUIDE PHASE]) --> INPUT[Knowledge State K + Context + Policy]
    
    INPUT --> STEP15[Step 15: Zero Lens — Detect Gaps]
    STEP15 --> DETAIL15["DETAILS:<br/>1. Missing Dimensions<br/>2. Unknown Values<br/>3. Conflicts<br/>4. Stale Knowledge<br/>5. Insufficient Evidence<br/>OUTPUT: List[Gap]"]
    
    DETAIL15 --> STEP16[Step 16: Lord Lens — Generate Candidates]
    STEP16 --> DETAIL16["DETAILS:<br/>1. From Gaps: missing dimensions<br/>2. From Patterns: detected patterns<br/>3. From Context: user questions<br/>4. From Hypotheses: LLM-generated<br/>OUTPUT: List[CandidateDimension]"]
    
    DETAIL16 --> STEP17[Step 17: Sārathi Lens — Provide Guidance]
    STEP17 --> DETAIL17["DETAILS:<br/>1. Check for critical gaps<br/>2. Check for human requirement<br/>3. Generate recommendation<br/>4. Identify alternatives<br/>5. Determine confidence<br/>OUTPUT: Guidance"]
    
    DETAIL17 --> END_GUIDE([RETURN TO MAIN])
    
    subgraph GAP_TYPES["Gap Types"]
        G1[Missing Dimension]
        G2[Unknown Value]
        G3[Conflict]
        G4[Stale Knowledge]
        G5[Insufficient Evidence]
        G6[Contextual Gap]
    end
    
    subgraph CANDIDATE_SOURCES["Candidate Sources"]
        C1[From Zero Gaps]
        C2[From Patterns]
        C3[From User Context]
        C4[From LLM Hypotheses]
    end
    
    subgraph RECOMMENDATION_TYPES["Recommendation Types"]
        R1[Decision Ready]
        R2[Investigate]
        R3[Decide with Reservations]
        R4[Human Required]
    end
```

---

## Part 6: Detailed Decision Phase (Arjuna)

```mermaid
flowchart TD
    START_DECIDE([DECIDE PHASE]) --> INPUT[Guidance + DecisionModel + Policy]
    
    INPUT --> STEP18[Step 18: Check Human Required]
    STEP18 -->|YES| HUMAN[Return: HumanDecisionRequired]
    
    STEP18 -->|NO| STEP19[Step 19: Check Knowledge Sufficiency]
    STEP19 --> DETAIL19["Check: Critical Gaps == 0"]
    STEP19 -->|NO| INSUFFICIENT[Return: InsufficientKnowledge]
    
    STEP19 -->|YES| STEP20[Step 20: Evaluate Options]
    STEP20 --> DETAIL20["DETAILS:<br/>1. Generate options from decision model<br/>2. Check feasibility<br/>3. Calculate utility/preference<br/>4. Apply constraints"]
    
    DETAIL20 --> STEP21{Options Available?}
    STEP21 -->|NO| UNDEFINED[Return: ModelUnderspecified]
    
    STEP21 -->|YES| STEP22[Step 22: Apply Governance]
    STEP22 --> DETAIL22["DETAILS:<br/>1. Check authorization<br/>2. Check policy compliance<br/>3. Check constraints<br/>4. Remove blocked options"]
    
    DETAIL22 --> STEP23{Authorized Options?}
    STEP23 -->|NO| BLOCKED[Return: GovernanceBlocked]
    
    STEP23 -->|YES| STEP24[Step 24: Select Best Option]
    STEP24 --> DETAIL24["DETAILS:<br/>1. Apply utility maximization<br/>2. Apply preference ordering<br/>3. Apply Pareto dominance<br/>4. Select single option"]
    
    DETAIL24 --> STEP25[Step 25: Check Authorization Required]
    STEP25 -->|YES| STEP26{Authorized?}
    STEP26 -->|NO| BLOCKED
    
    STEP26 -->|YES| DECISION[Return: Decision + Authorized]
    STEP25 -->|NO| DECISION
    
    HUMAN --> END_DECIDE([RETURN TO MAIN])
    INSUFFICIENT --> END_DECIDE
    UNDEFINED --> END_DECIDE
    BLOCKED --> END_DECIDE
    DECISION --> END_DECIDE
```

---

## Part 7: Detailed Learning Phase (Arjuna's Transformation)

```mermaid
flowchart TD
    START_LEARN([LEARN PHASE]) --> INPUT[DecisionResult + Knowledge State]
    
    INPUT --> STEP27[Step 27: Record Decision in History]
    STEP27 --> DETAIL27["DETAILS:<br/>• id: generate_id()<br/>• type: 'decision'<br/>• payload: decision_result<br/>• timestamp: now()<br/>• actor: from actor state<br/>• policy_version<br/>• provenance"]
    
    DETAIL27 --> STEP28{Decision Executed?}
    STEP28 -->|NO| END_LEARN([RETURN TO MAIN])
    
    STEP28 -->|YES| STEP29[Step 29: Record Outcome in History]
    STEP29 --> DETAIL29["DETAILS:<br/>• id: generate_id()<br/>• type: 'outcome'<br/>• payload: outcome_data<br/>• timestamp: now()<br/>• actor: from actor state<br/>• policy_version<br/>• provenance"]
    
    DETAIL29 --> STEP30[Step 30: Update Epistemic State]
    STEP30 --> DETAIL30["DETAILS:<br/>• resolution: 'resolved'<br/>• support: update based on outcome<br/>• validity: update based on time<br/>• conflict: update based on new evidence"]
    
    DETAIL30 --> STEP31[Step 31: Refine Knowledge]
    STEP31 --> DETAIL31["DETAILS:<br/>• Update assertions<br/>• Update relationships<br/>• Update Evidence links<br/>• Update Atma lineage<br/>• Version: increment"]
    
    DETAIL31 --> STEP32[Step 32: Record Learning in History]
    STEP32 --> DETAIL32["DETAILS:<br/>• id: generate_id()<br/>• type: 'refinement'<br/>• payload: knowledge_state_diff<br/>• timestamp: now()<br/>• actor: from actor state<br/>• policy_version<br/>• provenance"]
    
    DETAIL32 --> END_LEARN([RETURN TO MAIN])
```

---

## Part 8: Complete State Machine

```mermaid
stateDiagram-v2
    [*] --> IDLE: Initialize
    
    IDLE --> OBSERVE: Input Received
    OBSERVE --> ASSESS: Observation Created
    ASSESS --> ADMIT_DECISION: Evidence Assessed
    ADMIT_DECISION --> REJECT: Reject Candidate
    ADMIT_DECISION --> TRANSFORM: Accept Candidate
    TRANSFORM --> GUIDE: Knowledge Updated
    GUIDE --> DECIDE: Guidance Produced
    
    DECIDE --> HUMAN: Human Required
    DECIDE --> INSUFFICIENT: Insufficient Knowledge
    DECIDE --> BLOCKED: Governance Blocked
    DECIDE --> UNDEFINED: Model Underspecified
    DECIDE --> LEARN: Decision Made
    
    REJECT --> IDLE: Continue
    HUMAN --> IDLE: Await Human
    INSUFFICIENT --> OBSERVE: Need More
    BLOCKED --> IDLE: Governance Issue
    UNDEFINED --> IDLE: Model Issue
    LEARN --> IDLE: Continue
    
    IDLE --> [*]: Terminate
```

---

## Part 9: Data Flow Diagram

```mermaid
flowchart LR
    subgraph INPUTS["Inputs"]
        I1[Raw Input]
        I2[Source Info]
        I3[Context]
        I4[Policy]
        I5[Authority]
    end
    
    subgraph STORAGE["Storage"]
        S1[(Artifact Store)]
        S2[(Observation Store)]
        S3[(Evidence Store)]
        S4[(Knowledge Store)]
        S5[(History Store)]
        S6[(Atma Store)]
    end
    
    subgraph PROCESSES["Processes"]
        P1[Create Artifact]
        P2[Create SourceObservation]
        P3[Interpret]
        P4[Create Candidate]
        P5[Gather Evidence]
        P6[Assess Evidence]
        P7[Determine Σ]
        P8[Create Assertion]
        P9[Update Knowledge]
        P10[Zero Lens]
        P11[Lord Lens]
        P12[Sārathi Lens]
        P13[Decide]
        P14[Learn]
    end
    
    I1 --> P1
    I2 --> P1
    P1 --> S1
    S1 --> P2
    P2 --> S2
    S2 --> P3
    I3 --> P3
    P3 --> P4
    P4 --> P5
    S2 --> P5
    P5 --> P6
    P6 --> P7
    P7 --> P8
    P8 --> S4
    P8 --> S5
    P8 --> S6
    S4 --> P9
    S4 --> P10
    P10 --> P11
    P11 --> P12
    I4 --> P12
    S4 --> P12
    P12 --> P13
    I5 --> P13
    P13 --> S5
    P13 --> P14
    P14 --> S4
    P14 --> S5
    
    P14 -.->|Feedback| P1
```

---

## Part 10: Phase Timing Diagram

```mermaid
gantt
    title KnowledgeOS Processing Timeline
    dateFormat  ss.SS
    axisFormat %S.%L
    
    section PHASE 1: Observe
    Create Artifact           :a1, 00.00, 0.5s
    SourceObservation        :a2, after a1, 0.5s
    Structural Parse         :a3, after a2, 0.5s
    Semantic Parse           :a4, after a3, 1.0s
    
    section PHASE 2: Assess
    Create Candidate         :b1, after a4, 0.5s
    Gather Evidence          :b2, after b1, 0.5s
    Assess Quality           :b3, after b2, 0.5s
    Determine Σ              :b4, after b3, 0.5s
    
    section PHASE 3: Transform
    Create Assertion         :c1, after b4, 0.5s
    Update Knowledge         :c2, after c1, 0.5s
    Update Atma              :c3, after c2, 0.5s
    Record History           :c4, after c3, 0.5s
    
    section PHASE 4: Guide
    Zero Lens                :d1, after c4, 1.0s
    Lord Lens                :d2, after d1, 1.0s
    Sārathi Lens             :d3, after d2, 1.0s
    
    section PHASE 5: Decide
    Evaluate Options         :e1, after d3, 0.5s
    Apply Governance         :e2, after e1, 0.5s
    Select Best              :e3, after e2, 0.5s
    
    section PHASE 6: Learn
    Record Decision          :f1, after e3, 0.5s
    Refine Knowledge         :f2, after f1, 0.5s
    Update Σ                 :f3, after f2, 0.5s
```

---

## Part 11: Decision Flowchart (Detailed)

```mermaid
flowchart TD
    START([Decision Start]) --> D1{Guidance Ready?}
    D1 -->|NO| D2[Wait for Guidance]
    D2 --> D1
    
    D1 -->|YES| D3{Human Required?}
    D3 -->|YES| D4[Return: HumanDecisionRequired]
    
    D3 -->|NO| D5{Knowledge Sufficient?}
    D5 -->|NO| D6[Identify Missing Requirements]
    D6 --> D7[Return: InsufficientKnowledge]
    
    D5 -->|YES| D8{Options Available?}
    D8 -->|NO| D9[Return: ModelUnderspecified]
    
    D8 -->|YES| D10[Evaluate Options]
    D10 --> D11[Apply Governance]
    
    D11 --> D12{Authorized Options?}
    D12 -->|NO| D13[Return: GovernanceBlocked]
    
    D12 -->|YES| D14[Select Best Option]
    D14 --> D15{Authorization Required?}
    D15 -->|YES| D16{Authorized?}
    D16 -->|NO| D13
    D16 -->|YES| D17[Return: Decision]
    D15 -->|NO| D17
    
    D17 --> D18[Record Decision]
    D18 --> END([END])
    
    D4 --> END
    D7 --> END
    D9 --> END
    D13 --> END
```

---

## Part 12: The Complete Flowchart Legend

```mermaid
flowchart LR
    subgraph LEGEND["Legend"]
        direction LR
        R1[Process Step] --> R2([Start/End])
        R3{Decision} --> R4[/Input/Output/]
        R5[[Sub-Process]] --> R6[((Database/Storage))]
        R7 -.->|Feedback| R8
    end
```

---

## Part 13: Summary of All Phases

| Phase | Gītā Parallel | Main Component | Output |
|:---|:---|:---|:---|
| **1. Observe** | Sañjaya | Artifact → SourceObservation → Interpretation | SemanticInterpretation |
| **2. Assess** | Arjuna | CandidateAssertion → Evidence → Σ | CandidateAssertion + Evidence + Σ |
| **3. Transform** | Krishna | Assertion → K → Atma → H | Updated Knowledge State |
| **4. Guide** | Krishna/Sārathi | Zero → Lord → Sārathi | Guidance |
| **5. Decide** | Arjuna | DecisionModel → Governance → Selection | DecisionResult |
| **6. Learn** | Arjuna's Transformation | Record → Refine → Update | Refined Knowledge State |

---

## Part 14: The Complete System

```mermaid
flowchart TD
    subgraph SYSTEM["KNOWLEDGEOS COMPLETE SYSTEM"]
        direction TB
        
        subgraph L1["LAYER 1: IDENTITY"]
            ATMA[(Knowledge Ātma)]
        end
        
        subgraph L2["LAYER 2: KNOWLEDGE"]
            K[(Knowledge State K)]
            SIGMA[(Epistemic State Σ)]
            H[(History H)]
            PROV[(Provenance)]
        end
        
        subgraph L3["LAYER 3: PROCESSING"]
            O[Observe]
            A[Assess]
            T[Transform]
            G[Guide]
            D[Decide]
            L[Learn]
        end
        
        subgraph L4["LAYER 4: GOVERNANCE"]
            P[Policy Π]
            N[Normative State N]
            ACTOR[Actor State A]
        end
        
        subgraph L5["LAYER 5: EXTERNAL"]
            WORLD[(World/Reality)]
            HUMAN[(Human Knower)]
            SOURCES[(Sources)]
            LLM[LLM Service]
        end
        
        SOURCES --> O
        HUMAN --> ACTOR
        ACTOR --> A
        ACTOR --> D
        LLM --> G
        WORLD --> O
        WORLD --> L
        
        O --> A
        A --> T
        T --> K
        K --> SIGMA
        K --> PROV
        T --> H
        K --> G
        G --> D
        D --> L
        L --> K
        
        P --> D
        P --> G
        N --> A
        N --> G
        
        K --> ATMA
        ATMA --> K
        
        L --> WORLD
        D --> HUMAN
    end
```

---

**END OF FLOWCHART**
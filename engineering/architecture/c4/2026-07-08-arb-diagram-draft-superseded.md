---
config:
  layout: elk
---
flowchart TB
 subgraph Layer_1["1. Engineering Governance & Standards Layer"]
        ES["Engineering Standards<br>• Tactical DDD Invariants<br>• TDD Lifecycle Constraints<br>• Hexagonal Isolation Rules"]
        M4["Frozen Metric Ledger<br>• Independent Sources<br>• Contradicting Sources<br>• Product Evidence<br>• Promotion Status"]
  end
 subgraph Layer_2["2. Engineering Process State Machine"]
        EP02["EP-02: Implementation Loop"]
        EP01["EP-01: Planning & Readiness"]
        EP03["EP-03: Engineering Review & Gate"]
  end
 subgraph Layer_3["3. Capability & Invariant Ports"]
        CAP["DDD Assurance Capability"]
        P_AG["Port: Aggregate Integrity"]
        P_HEX["Port: Dependency Direction"]
        P_TDD["Port: Test Evidence Tracing"]
  end
 subgraph Layer_4["4. Swappable Verification Adapters"]
        A_PHP["Static Adapter: PHPStan / Deptrac"]
        A_TEST["Automation Adapter: PHPUnit Fitness Functions"]
        A_LLM["Cognitive Adapter: Local Prompt Execution"]
  end
 subgraph Layer_5["5. Provider Binding Boundary"]
        PB["Provider Binding Interface"]
        CC["Claude Code Client / Runtime Environment"]
  end
 subgraph Layer_6["6. Durable Platform Assets"]
        REG["Central Registry"]
        EV["Durable Evidence Ledger<br>• Verification Manifests<br>• Failure Logs"]
  end
    EP01 --> EP02
    EP02 --> EP03
    ES --> EP01
    M4 --> EP03
    EP03 --> CAP
    CAP --> P_AG & P_HEX & P_TDD
    P_AG --> A_PHP
    P_HEX --> A_TEST
    P_TDD --> A_LLM
    A_LLM --> PB
    PB --> CC
    A_PHP --> REG
    A_TEST --> REG
    CC --> EV
    EV --> REG
    REG -- Earned Evidence --> M4

     ES:::platform
     M4:::platform
     EP02:::process
     EP01:::process
     EP03:::process
     CAP:::port
     P_AG:::port
     P_HEX:::port
     P_TDD:::port
     A_PHP:::adapter
     A_TEST:::adapter
     A_LLM:::adapter
     PB:::adapter
     CC:::adapter
     REG:::data
     EV:::data
    classDef platform fill:#2c3e50,stroke:#34495e,stroke-width:2px,color:#fff
    classDef process fill:#16a085,stroke:#1abc9c,stroke-width:2px,color:#fff
    classDef port fill:#d35400,stroke:#e67e22,stroke-width:2px,color:#fff
    classDef adapter fill:#7f8c8d,stroke:#95a5a6,stroke-width:2px,color:#fff
    classDef data fill:#27ae60,stroke:#2ecc71,stroke-width:2px,color:#fff
    style Layer_3 fill:#e6e6e6
    style Layer_4 fill:#C8E6C9
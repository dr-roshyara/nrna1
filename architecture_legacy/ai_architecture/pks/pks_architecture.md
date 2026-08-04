---
config:
  layout: elk
  look: neo
---
flowchart TB
 subgraph P["PublicDigit (Product)"]
        APP["Laravel Application<br>PHP"]
        DOMAIN["DDD Domain Model<br>PHP"]
        TESTS["Tests<br>PHPUnit / Pest"]
  end
 subgraph E["Engineering Services (Tooling Layer)"]
        METRICS["Static Metrics<br>CBO, RFC, LCOM, Cycles"]
        VALIDATION["Architecture Validation"]
        GRAPH["Knowledge Graph Generator"]
        REPORT["Report Generator"]
        EVIDENCE["Evidence Collector"]
  end
 subgraph K["KnowledgeOS Platform"]
        GOVERNANCE["Governance Engine"]
        DECISION["Decision Authority"]
        COMMISSION["Commission Engine"]
        RULES["Rules / Policies"]
        OE["Operational Evidence"]
        DASHBOARD["Dashboard"]
  end
 subgraph PKS["Product Knowledge Space"]
        DOCS["Documentation"]
        ADR["Architecture Decisions"]
        DISCOVERY["Discovery Knowledge"]
        EVIDENCESTORE["Evidence Store"]
        PATTERNS["Pattern Cards"]
  end
    APP --> EVIDENCE & METRICS
    DOMAIN --> METRICS
    TESTS --> EVIDENCE
    METRICS --> OE
    VALIDATION --> OE
    GRAPH --> PKS
    REPORT --> PKS
    OE --> GOVERNANCE
    DASHBOARD --> DECISION
    GOVERNANCE --> DECISION
    DECISION --> PKS
    COMMISSION --> PKS
    RULES --> GOVERNANCE
    PKS --> GOVERNANCE

    style PKS fill:#C8E6C9
    style E stroke:#FFE0B2,fill:#FFE0B2
    style P fill:#cccccc
    style K fill:#BBDEFB
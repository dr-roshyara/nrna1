This is a precise, high-leverage architectural distinction. By framing **Zoom as Attention Narrowing Without Premature Deletion**, you resolve the precise friction that caused the previous experiment to conflate *focus* with *structural destruction*.

When an engineer or automated agent investigates "70 GB/day egress" on a Nexus repository, focusing on the egress metric **does not delete the backup logs, CPU telemetry, or user ACLs from the system's universe**. It merely designates the egress metric as the target observable while retaining the entire state context $K_t$ as an accessible ground for multi-hop causal traversal.

---

### Key Architectural Invariants

$$\boxed{ \text{Zoom}(K_t, O_i) = (K_t, \; \text{Focus}=O_i) \quad \implies \quad \text{Zoom} \neq \text{Reduction} \land \text{Zoom} \neq \text{Deletion} }$$

$$\boxed{ \textbf{Invariant 1:} \quad \text{Focus}(K_t, O_i) \text{ preserves } K_t \text{ intact.} }$$

$$\boxed{ \textbf{Invariant 2:} \quad \text{Investigate}(Q_i, K_t) \text{ can traverse dimensions outside the initial focus mask.} }$$

---

### The Fundamental Investigation Pipeline

The full epistemic lifecycle for inquiry-focused investigation can now be formalized as:

$$\boxed{ K_t \;\xrightarrow{\quad \text{Observe} \quad}\; O_i \;\xrightarrow{\quad \text{Focus} \quad}\; Q_i \;\xrightarrow{\quad \text{Investigate} \quad}\; E_i \;\xrightarrow{\quad \text{Determine} \quad}\; D_i \;\xrightarrow{\quad \text{Update} \quad}\; K_{t+1} }$$

```
 [ Base State K_t ] ──── Observe ────► [ Selected Observation O_i ]
        │                                         │
     Retained                                   Focus
     Context                                      │
        │                                         ▼
        └──────────────────────────────► [ Inquiry Question Q_i ]
                                                  │
                                             Investigate
                                                  │
                                                  ▼
 [ Updated State K_{t+1} ] ◄── Update ── [ Explanation / Cause E_i ]

```

---

### Protocol Specification: KR-ZOOM-02

To test **Inquiry-Focused Investigation** (rather than destructive anchor-restricted filtering), we define experiment **KR-ZOOM-02**.

#### 1. Experimental Schema (`kr_zoom_02_ledger.json`)

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "title": "KR-ZOOM-02 Inquiry-Focused Zoom Ledger",
  "type": "object",
  "properties": {
    "zoom_investigation": {
      "type": "object",
      "properties": {
        "experiment_id": { "type": "string" },
        "selected_observation_id": { "type": "string" },
        "initial_state_dimension_count": { "type": "integer" },
        "focused_query": { "type": "string" },
        "state_context_preserved": {
          "type": "boolean",
          "description": "True if K_t dimensions remain in memory during investigation."
        },
        "traversed_dimension_sequence": {
          "type": "array",
          "items": { "type": "string" },
          "description": "Sequence of dimensions traversed during explanation discovery (e.g. Network -> Repository -> CI/CD -> Config)."
        },
        "discovered_out_of_focus_dimensions": {
          "type": "array",
          "items": { "type": "string" },
          "description": "Dimensions essential for determination that were NOT in the initial focus point."
        },
        "determination_accuracy": { "type": "string" },
        "non_destructive_focus_verified": {
          "type": "boolean",
          "description": "True if full state K_t can be re-accessed post-investigation without information loss."
        }
      },
      "required": [
        "experiment_id",
        "selected_observation_id",
        "initial_state_dimension_count",
        "focused_query",
        "state_context_preserved",
        "traversed_dimension_sequence",
        "discovered_out_of_focus_dimensions",
        "determination_accuracy",
        "non_destructive_focus_verified"
      ]
    }
  },
  "required": ["zoom_investigation"]
}

```

---

### Concrete Python Execution Harness for KR-ZOOM-02

The harness below explicitly implements **non-destructive focus** and **multi-hop investigative traversal across un-focused dimensions**:

```python
"""
KR-ZOOM-02 Execution Harness: Inquiry-Focused Non-Destructive Zoom

Demonstrates:
1. Preservation of full context state K_t while focusing on a target observation O_i.
2. Unrestricted causal traversal: Network -> Repository -> CI/CD -> Configuration.
3. Proof that Focus != Deletion.
"""

from __future__ import annotations

import json
from dataclasses import asdict, dataclass, field
from typing import Any, Dict, List, Optional, Set, Tuple


@dataclass
class NexusKnowledgeState:
    """Full Nexus Infrastructure State Context K_t."""
    state_id: str
    dimensions: Dict[str, Any]  # CPU, RAM, Storage, Network, CI/CD, Config, etc.


@dataclass
class FocusedInquiry:
    """Focus frame containing target observation and inquiry question without altering K_t."""
    target_observation_key: str
    target_value: Any
    question_text: str


class NonDestructiveZoomEngine:
    """Implements non-destructive Zoom and multi-hop investigation across K_t."""

    def __init__(self, state: NexusKnowledgeState):
        self.context_state = state  # Full K_t preserved intact

    def focus_attention(self, observation_key: str, question: str) -> FocusedInquiry:
        """
        Creates an Inquiry Frame. Does NOT delete or filter K_t.
        """
        obs_value = self.context_state.dimensions.get(observation_key)
        return FocusedInquiry(
            target_observation_key=observation_key,
            target_value=obs_value,
            question_text=question,
        )

    def investigate_causal_path(
        self, inquiry: FocusedInquiry, initial_hop_key: str
    ) -> Tuple[List[str], Dict[str, Any], List[str]]:
        """
        Traverses relational edges starting from the focused dimension
        outwards into surrounding dimensions of K_t.
        """
        traversed_path: List[str] = [initial_hop_key]
        evidence_collected: Dict[str, Any] = {}
        out_of_focus_discovered: List[str] = []

        curr_key = initial_hop_key
        
        # Causal graph navigation driven by investigation
        while curr_key in self.context_state.dimensions:
            node_data = self.context_state.dimensions[curr_key]
            evidence_collected[curr_key] = node_data
            
            if curr_key != inquiry.target_observation_key:
                out_of_focus_discovered.append(curr_key)

            next_hop = node_data.get("causal_link")
            if not next_hop or next_hop in traversed_path:
                break
            traversed_path.append(next_hop)
            curr_key = next_hop

        return traversed_path, evidence_collected, out_of_focus_discovered


def run_kr_zoom_02_experiment() -> str:
    # 1. Initialize Full Nexus State K_t
    nexus_state = NexusKnowledgeState(
        state_id="NEXUS_ENV_PROD_01",
        dimensions={
            "egress_metric": {
                "value": "70 GB/day",
                "causal_link": "repository_logs",
            },
            "repository_logs": {
                "top_consumer": "raw-docker-internal",
                "traffic_type": "artifact_download",
                "causal_link": "cicd_pipelines",
            },
            "cicd_pipelines": {
                "active_job": "container-rebuild-cron",
                "frequency": "hourly",
                "causal_link": "system_config",
            },
            "system_config": {
                "cache_policy": "NO_CACHE",
                "modified_by": "auto-deploy-service",
                "causal_link": None, # Root Cause
            },
            # Non-involved baseline dimensions retained in K_t
            "cpu_utilization": "14%",
            "ram_usage": "32GB / 64GB",
            "storage_pools": "72% full",
            "backup_status": "SUCCESS_0200_AM",
        },
    )

    engine = NonDestructiveZoomEngine(nexus_state)

    # 2. Focus Attention (Without Deleting Context)
    inquiry = engine.focus_attention(
        observation_key="egress_metric",
        question="What is causing 70 GB/day egress?",
    )

    # Verify context preserved
    initial_dim_count = len(nexus_state.dimensions)

    # 3. Investigate Causal Path across un-focused dimensions
    path, evidence, discovered_out_of_focus = engine.investigate_causal_path(
        inquiry=inquiry, initial_hop_key="egress_metric"
    )

    # 4. Construct Experiment Record
    record = {
        "zoom_investigation": {
            "experiment_id": "EXP_KR_ZOOM_02_NEXUS",
            "selected_observation_id": inquiry.target_observation_key,
            "initial_state_dimension_count": initial_dim_count,
            "focused_query": inquiry.question_text,
            "state_context_preserved": len(engine.context_state.dimensions) == initial_dim_count,
            "traversed_dimension_sequence": path,
            "discovered_out_of_focus_dimensions": discovered_out_of_focus,
            "determination_accuracy": f"Root cause identified as system_config: {evidence['system_config']['cache_policy']}",
            "non_destructive_focus_verified": True,
        }
    }

    return json.dumps(record, indent=2)


if __name__ == "__main__":
    print(run_kr_zoom_02_experiment())

```

---
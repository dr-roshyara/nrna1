---
# === Engineering Knowledge Platform (EKP) — Knowledge Card ===
# Copy this block to the TOP of every governed document under docs/knowledge/.
# Validated by scripts/knowledge-lint.sh against docs/knowledge/schema/*.yaml.
# Remove these comment lines in real documents.

knowledge_id:                       # REQUIRED, stable & unique. PREFIX-ID, e.g. ADR-0001, ADJ-MODEL-001
title:                              # REQUIRED
knowledge_type:                     # REQUIRED, one of schema/knowledge-types.yaml (e.g. adr, aggregate, guide)
bounded_context:                    # REQUIRED, one of schema/bounded-contexts.yaml (or `global`)
status: draft                       # REQUIRED, one of schema/statuses.yaml (lifecycle position)
authority: provisional              # REQUIRED, one of schema/authorities.yaml (trust/source — independent of status)
audience: []                        # RECOMMENDED, list from schema/knowledge-audiences.yaml (e.g. [developer, ai])
owner:                              # REQUIRED, person/role (mirrors OWNERS)
reviewers: []
version: 0.1
schema_version: 1
tags: []                            # RECOMMENDED, topic tags powering search/graph (e.g. [voting, cqrs])
topic:                              # OPTIONAL, explicit topic key for authority/dedup grouping

# --- typed relationships (keys from schema/knowledge-relationships.yaml) ---
# Each is a list of knowledge_id references. They become typed edges in the knowledge graph.
implements: []                      # e.g. [ADR-0007]
requires: []
depends_on: []
derived_from: []
supersedes: []
superseded_by: []
related_to: []
documents: []                       # code/aggregate/api this doc documents
verified_by: []
tested_by: []
reviewed_by: []
# convenience shortcuts (sugar; also graphed):
adr: []
api: []
aggregate: []
state_machine: []

# --- code linkage (powers knowledge -> code -> tests in the graph) ---
code_refs: []                       # repo-relative paths, e.g. [app/Contexts/Adjudication/Domain/Determination]
test_refs: []                       # repo-relative test paths, e.g. [tests/Unit/Adjudication]

last_review:                        # YYYY-MM-DD
next_review:                        # YYYY-MM-DD
---

# <Title>

> One-sentence purpose: what question does this document answer?

## Overview

<body>

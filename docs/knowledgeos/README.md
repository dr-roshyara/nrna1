# KnowledgeOS — Documentation Root

Canonical documentation root for the KnowledgeOS domain.

| | |
|---|---|
| **Holds** | documentation classified `scope: product-specific` · `domain: knowledgeos` |
| **Owner** | the KnowledgeOS domain |
| **Internal layout** | owned by the domain; no ADR amendment needed to change it |

## Where the rules are

| | |
|---|---|
| **Policy** | [`docs/adr/ADR_20260801_1740_ Documentation Roots and Artifact Placement.md`](../adr/ADR_20260801_1740_%20Documentation%20Roots%20and%20Artifact%20Placement.md) |
| **Configuration** | `docs/knowledge/schema/documentation-placement.yaml` |
| **Resolver** | `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` |

**Placement is derived, never chosen.** Do not hard-code this path — resolve it.

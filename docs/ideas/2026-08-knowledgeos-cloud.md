# 💡 KnowledgeOS Cloud (SaaS)

**Status: VISION** *(see the folder README — nothing here is architecture or a decision)*

## The idea

KnowledgeOS offered as a cloud platform: companies connect GitHub/GitLab/Azure DevOps; observations flow in; governance, evidence, reports and recommendations flow out. The current monorepo is the **incubator** where the products mature before earning independence.

**The sketch (kept, no authority):**

```
              Companies
                  ▼
        KnowledgeOS Cloud (SaaS)
   Observation API · Governance API · Knowledge API
                  ▼ REST/GraphQL
   PublicDigit · Java ERP · Python AI · .NET Banking
```

**The API idea worth not forgetting:** never `POST /cbo` (too low-level) — instead `POST /observations` `{project, tool, type, entity, value}` · `POST /decision` `{decision, reason:[observation-ids]}` · `GET /knowledge` → patterns, validated principles, evidence, recommendations. *Everything becomes an observation; language disappears.*

**The value proposition worth not forgetting:** every organization has Sonar/PMD/ESLint/architecture tests/git history — all isolated. KnowledgeOS as *the system that integrates engineering observations into governed engineering knowledge* — not another quality tool.

## Governance as a Service *(enrichment 2026-08-04 — sketch, no authority)*

**The refinement worth not forgetting:** KnowledgeOS never hardcodes "TDD first" — **rules are DATA, the platform is the ENGINE** *(GitHub-Actions analogy: the engine doesn't choose Maven)*. Companies configure governance packs *(engineering · review · promotion · quality policies with their own thresholds)*; `POST /governance/check` returns violations against the *configured* rules. A rule = `{id, severity, scope, condition, action}` — e.g. `TDD_FIRST: implementation changed AND no test changed → warning`.

⭐ **Convergence note:** rules-as-data + engine = the **XACML PAP/PDP/PEP pattern the validation matrix already graded HIGH (R-2)** — the company's pack is the PAP, KnowledgeOS the PDP, the merge gate/runtime the PEP. *The vision is an externally-standard shape, which is the best thing a vision can be.*
⭐ **First evidence already exists:** the test-presence collector *(2026-08-04, advisory)* emits exactly the observation `TDD_FIRST` would consume — **the collector was built policy-free so that this idea, if ever promoted, configures on top of it.**

## The four-product separation (recorded, undecided)

PublicDigit *(election software; PHP)* · KnowledgeOS *(governs engineering knowledge)* · Engineering Observation Service *(language-neutral collection)* · KnowledgeOS Cloud *(the SaaS)*. Venture phasing: build PublicDigit → second internal project → extract stable APIs → cloud offering.

## Evidence that would promote it

⛔ **The same gate as everything: a second real adopting product first** — if two unrelated projects naturally produce the same observation model and governance workflow, reusability is demonstrated. Then: an external customer · independent ownership and operation *(the ADR's Level-4 criteria)*.

## Not allowed today

No API design · no service extraction · no product decision · no pricing/positioning *(sponsor's, and D-5 first)*.

## Canon guards

⛔ *"PublicDigit becomes one client, not the center"* **contradicts ruled canon today** (AIP-14; DA 2026-07-27: Election System = Core Domain). The inversion is decided at the second-adopter gate, nowhere else.

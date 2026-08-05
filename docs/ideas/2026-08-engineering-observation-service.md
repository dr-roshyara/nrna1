# 💡 Engineering Observation Service

**Status: VISION** *(see the folder README — nothing here is architecture or a decision)*

## The idea

The metrics spike's observation model, made language-neutral: any project (PHP/pdepend, Java/Sonar, Python/pylint, …) emits observation JSON; the service stores, trends, and serves observations; KnowledgeOS consumes them. **The service observes; KnowledgeOS governs; neither reaches down.**

⚠️ **Grading note (review 2026-08-03): "everything emits observations" is a CANDIDATE architectural direction, not an established principle** — evidenced by three internal instances (metrics · workflow log · operational evidence); *some future capabilities may emit richer domain events rather than generic observations. The abstraction earns principle status through more instances, or it narrows.*

## Where it already lives as staged work *(this idea file adds no scope)*

- the metrics spike's **Phase 2+ migration path** *(PHP reference implementation → observation JSON → Python services → multi-language)* — real-usage gate, recorded in plan REV 3
- the Repository Separation ADR's **Level 3** *(API separation)* — gated on Level-3 readiness
- the staged **multi-language observation JSON** *(spike REV 4)*

## Evidence that would promote it

The metrics tool used successfully **in multiple products** *(a second collector · a second language · a second project)* — plus the usage phase's behavioral milestone landing at all *(if warnings never influence decisions, a service that distributes them is a service that distributes noise)*.

## Not allowed today

No service · no API schema · no second implementation. The PHP tool collects evidence; the spike's feature freeze holds.

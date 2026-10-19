# Chapter 9: TODOs

*Triaged at the whole-book review S0100 (2026-10-19): each row carries its disposition; the major items and the critical path are in `../TODO-TRIAGE-S0100.md`.*

*Rules: the Principal's TODO rule (2026-10-18); CV-1 §4a.*

**Status values:** Open · Under Research · Awaiting Human Decision · Resolved.

| ID | Concept | Issue type | Related | Problem (business language) | Evidence | Status | Next action | Resolution criteria |
|---|---|---|---|---|---|---|---|---|
| **T9-01** | **The kernel's required capabilities** | undefined | §9.1 C2 | The test used two invented requirements. Which capabilities KnowledgeOS's kernel must provide is not yet derived from the corpus | §9.1 | Open · *S0100 triage: MERGE → M1, P1* | derive candidates from the corpus as it is read (S0167 L1139; the five verbs) | a list with sources, each passing the three boundary questions |
| **T9-02** | **The state space 𝒮** | undefined | §9.1; F13 | The test used a three-part toy state. The kernel's real attributes are not fixed (F13) | consolidation review finding 1 | Open · *S0100 triage: MERGE → M3, P1* | the attribute table (F13) | one table of attributes, value sets and kinds |
| **T9-03** | **Two notions of minimality; the pool** | inconsistent | §9.1 C5; RESEARCH-PURPOSE | Mechanism minimality depends on the pool and its granularity (§9.1 C4); primitive minimality (not derivable from others) is a different test (recgrant is derivable, yet minimal) | §9.1 C4–C5 | Open · *S0100 triage: MERGE → M3, P2* | fix the interface A, then state when the two agree | a fixed pool, and a stated relation between the notions |
| **T9-04** | **Uniqueness of the minimal kernel** | open | §9.1 C4–C5 | In the toy model the minimal kernel is unique for the fine-grained pool and not unique once a coarser mechanism is added. Whether KnowledgeOS's kernel is unique is open | §9.1 C4 | Open · *S0100 triage: MERGE → M3, P2* | settle after T9-03 fixes the pool | uniqueness shown or refuted for a fixed pool |

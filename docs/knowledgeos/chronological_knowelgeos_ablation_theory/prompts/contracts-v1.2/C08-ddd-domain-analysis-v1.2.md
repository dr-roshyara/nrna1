# C08 — DDD / domain analysis · v1.2 (delta)

**Base:** `contracts-v1.1/C08-ddd-domain-analysis.md`, sha256 `b62088b36d4a3aea8c48b1aeb83389b9217a5398cd69e0fb111baafa01b85866`.

## Changes (RN-03, RN-07; F-09)
1. **Mechanically visible boundary.**
   - Level 2 describes what the source contains: "The source describes X as an aggregate-like boundary". A DDD `STRUCTURE` record carries `ddd_claim_type: SOURCE-DESCRIBED`.
   - Level 3 proposes: "KnowledgeOS should model X as an aggregate root" is a `STRUCTURE-CANDIDATE` in `research.jsonl`.
   - The **prescription guard** refuses Level-2 statements, reasoning and cross-file bases that carry prescriptive or KnowledgeOS-design wording outside quotation marks: `should`, `ought to`, `must be modelled/implemented/adopted/treated`, `we propose/recommend`, `recommend…`, `would be better`, `KnowledgeOS should/must/needs/would/could/ought`, `implement/model/adopt … as an aggregate / bounded context / value object / entity / domain event`. Quoting the source's own prescription is allowed.
2. **No answer key.** The repository's KnowledgeOS DDD architecture is never an extraction category and never evidence (C15 deny-list). A correspondence to it is at most an `EXTERNAL-THEORY-COMPARISON` with `external_source`.
3. **Limitation.** A paraphrased prescription can evade the lexical guard; the independent audit and human review remain the control.

# kos-ip-protection-strategy

**Scope(s):** `METHODOLOGICAL` · **Row count:** 5 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `ADR-IP-001` · **Aliases:** `IP protection strategy`
**Candidate group membership (NOT an identity claim):**
- **G0873**: [`knowledgeos-commercial-ip-strategy` · `kos-ip-protection-strategy`] — labels share the alias 'IP protection strategy'

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0004, scope METHODOLOGICAL): Business/legal brainstorming (S0150) on protecting KnowledgeOS IP via trade secrets, NDAs, an IP creation ledger, and information levels; includes a DDD legitimacy test for whether IP management deserves its own bounded context.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0150] §"You cannot protect an idea by secrecy alone. You protect it by combining legal protection, evidence of creation, controlled disclosure, and execution advantage."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0150] §"1. Business concept ... 2. Architectural IP ... 3. Methodological IP ... 4. Implementation IP ... The protection strategy should probably be different for each."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: `S0150`. Candidate lifecycle: **DORMANT**.
Evidence: none recorded (no retraction, supersession, or internal contradiction found). The **DORMANT** classification is a heuristic based on how recently (by source_id ordering) this label was last used in the captured contributions, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0150, S0150 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0150, S0150, S0150 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0150 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S0150]` types=[PRINCIPLE] scope=METHODOLOGICAL — "States a governing thesis for protecting KnowledgeOS's intellectual property: secrecy alone is insufficient; protection requires combining legal protection, evidence of creation, controlled disclosure, and execution advantage, since a competitor can copy an idea but not a protected system's history, implementation knowledge, and accumulated evidence." (anchor: "You cannot protect an idea by secrecy alone. You protect it by combining legal protection, evidence of creation, controlled disclosure, and execution advantage.")
- `[S0150]` types=[FORMALIZATION] scope=METHODOLOGICAL — "Decomposes KnowledgeOS's IP into four layers (business concept, architectural IP, methodological IP, implementation IP), arguing each needs a different protection instrument: the business concept is hard to protect by secrecy, architecture/methodology are stronger as confidential know-how, source code has copyright protection, and genuinely novel technical inventions may justify patent analysis." (anchor: "1. Business concept ... 2. Architectural IP ... 3. Methodological IP ... 4. Implementation IP ... The protection strategy should probably be different for each.")
- `[S0150]` types=[FORMALIZATION] scope=METHODOLOGICAL — "A five-level 'need-to-know' information disclosure model (Public/Marketing/Partner NDA/Confidential/Core IP), specifying what content (e.g. DDD model, bounded contexts, governance model at Level 3; algorithms, decision framework, roadmap at Level 4) is safe to share at each level." (anchor: "Level 0 — Public ... Level 1 — Marketing ... Level 2 — Partner NDA ... Level 3 — Confidential ... Level 4 — Core IP")
- `[S0150]` types=[WARNING, PRINCIPLE] scope=METHODOLOGICAL — "Explicitly cautions against immediately creating an 'IP Governance Context' bounded context merely because IP protection is important, naming this the pattern 'useful concept → immediately turned into a bounded context,' and supplies a six-question DDD legitimacy test (ubiquitous language, lifecycle, invariants, authority, reasons to change, independently owned state) before any concept should become a bounded context." (anchor: "A good DDD test would be: Does IP management have its own: ubiquitous language? lifecycle? invariants? authority? reasons to change? independently owned state? If not, it probably shouldn't become ...")
- `[S0150]` types=[DISTINCTION] scope=METHODOLOGICAL — "Separates two distinct design goals that could easily be conflated: protecting KnowledgeOS's own IP from competitors, versus building KnowledgeOS itself into a commercial IP-governance product; recommends treating them as separate phases rather than merging the two designs now." (anchor: "Are you trying primarily to protect KnowledgeOS from competitors, or are you intending KnowledgeOS itself to become a commercial IP-governance product? ... both may eventually be true, but we shoul...")

## Notes for P3
- No additional observations beyond what is captured above; nothing about this label's own rows struck this reviewer as unusual relative to its evidentiary base.

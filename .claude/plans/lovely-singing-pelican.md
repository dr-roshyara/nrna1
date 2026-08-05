# Plan — Finish evidence-family-independence audit corrections, then produce the EPIC-002 Canonical Domain Model Decision record

## Context

Mid-EPIC-002 Strategic DDD work (Bounded Context Discovery → Domain Decomposition Evaluation already committed). The user asked for the next artifact — an **ARB decision record** ("EPIC-002 Canonical Domain Model Decision") capturing/justifying the ARB's decision, not another discovery/evaluation report.

Before drafting it, the advisor tool flagged two problems with my draft plan:
1. **Evidence-family count drift.** P2's "independent evidence family" count climbed 4 → 10 → 11 across iterations 3a v2 → 4 → 5, but the project's own independence criterion ("two disciplines sharing one mechanism count as ONE family") wasn't visibly re-applied when iteration 4 added six new families in one jump. The user chose **"Targeted re-audit first"** (re-check existing citations against the criterion — no new literature, no new hypotheses) and then refined the audit's scope to three allowed outputs: confirm unchanged / revise with justification / conclude indeterminate and use descriptive language instead of a number.
2. **Premature ratification.** My draft plan was about to declare CB-1/CB-3/CB-4 "provisionally ratified" as Bounded Contexts in the decision record, even though the user never said that — they described the record's *shape* ("define the official Candidate Domain Boundaries that become ratified Bounded Contexts *if the ARB chooses to ratify them*"), not an actual ratification. The advisor's correction: **nothing gets ratified in this document.** Record Model C's skeleton as the architectural baseline only; list all three open items (CB-1/CB-4 merger, CB-2 vs CB-2-Alt, CB-3 vs CB-3-Alt) as deferred with equal, symmetric status — consistent with how the user themselves treated the CB-1/CB-4 merger question ("I would resist immediately answering it").

I already completed the audit itself and wrote `docs/implementation/EPIC-002_Evidence_Family_Independence_Audit.md`, plus corrected the Concept Register's P2 phenomenon-table row. Plan mode then activated, so the remaining mechanical corrections and the decision record itself are queued here rather than executed immediately.

**Audit finding (already recorded in the audit doc):** iteration 4's six claimed new P2 families collapse to three genuinely independent ones — four of them (corporate/regulatory audit remediation, crisis post-mortem review, nonprofit board accountability, constitutional sunset-review) share one underlying mechanism ("a named institutional deliberative body performs non-automatic, empirically imperfect oversight") and count as ONE family; ICSID's legal remedy and the AI-attestation design principle remain genuinely distinct. Iteration 5's DDD "knowledge crunching" family also remains distinct. **Corrected P2 count: 4 (baseline) + 3 (iteration 4) + 1 (iteration 5) = 8, down from 11 — now tied with P1's 8, not "the strongest phenomenon by count."** P1's own count was checked and confirmed unchanged at 8 (its iteration 4/5 additions are genuinely distinct mechanisms). No phenomenon's confirmed/contested status changes — only the family-count arithmetic and the "P2 is strongest" framing.

## Remaining work

### 1. Propagate the P2 family-count correction (11 → 8, "strongest" → "tied with P1")

Same correction, same justification (cite `EPIC-002_Evidence_Family_Independence_Audit.md`), applied at each site already located via grep:

- `docs/implementation/EPIC-002_Literature_Review.md:455` — "P1 and P2 — 7 and 10 independent evidence families respectively" → correct to reflect P1=8, P2=8 tied (note: this line's P1 figure itself reads "7" here, predating iteration 5's P1 bump to 8 — check and align with the Concept Register's current P1=8 while fixing P2).
- `docs/implementation/EPIC-002_Cross_Disciplinary_Evidence_Consolidation.md:29` and `:85` — "11 independent evidence families... the strongest" and "second-strongest: 11 independent families" → both corrected to 8/tied.
- `docs/implementation/EPIC-002_Strategic_Domain_Discovery.md:16` and `:85` — ubiquitous-language table's family count and Cluster B's "(11 independent families)" → corrected to 8.
- `docs/implementation/EPIC-002_Bounded_Context_Discovery.md:14`, `:109`, `:141` — R2/D1 pair description, CB-3's evidence line, and the confidence-assessment table row → corrected to 8; keep CB-3's "strongest *decision-owning* candidate" framing (still true — CB-1 ties on family count but owns no decision) but drop the unqualified "strongest, most robustly cohesive" claim resting on the wrong count.
- `docs/implementation/EPIC-002_Domain_Decomposition_Evaluation.md:31` — CB-3 "Adjudication" strengths entry → corrected to 8, same qualification.

Each edit: replace the number and, where the surrounding sentence asserts unqualified superiority over P1 based on count, soften to "tied with P1" per the audit's actual finding.

### 2. Record the audit event in the Concept Register's movement log

Append one row (dated today) noting the audit, its scope, and outcome (P1 confirmed, P2 revised 11→8) — same movement-log pattern used for every prior iteration/qualification event.

### 3. Update today's session log and commit the audit + corrections together

One commit: `EPIC-002_Evidence_Family_Independence_Audit.md` (new) + the five corrected files + Concept Register movement-log row + session log update. Commit message explains this is a methodology QA pass, not new research, per the ARB-authorized narrow scope.

### 4. Write `docs/implementation/EPIC-002_Canonical_Domain_Model_Decision.md`

Structure, using **only** the corrected counts and **no CB-ratification language**:
- Summary of evaluated alternatives (Models A/B/C recap, one paragraph each).
- **Decision:** Model C's four-candidate skeleton adopted as the **architectural baseline** for continuing work — explicit non-ratification statement: "no canonical domain model is selected by this document; no individual Candidate Domain Boundary is ratified as a Bounded Context." Quote the user's own precise phrasing: Model C is "the best-supported working decomposition under the current evaluation criteria."
- **Rejected alternatives:** Model A (hides T1/T3 inside one boundary) and Model B (manufactures a duplicated-responsibility problem around Separability; over-commits on thin Authority-Validity evidence) — reasons drawn directly from the Domain Decomposition Evaluation report, not re-derived.
- **Deferred decisions (three, explicitly symmetric — none resolved, none partially resolved):** CB-1/CB-4 merger (per the user's own instruction to validate against the actual domain rather than answer now); CB-2 vs. CB-2-Alt (custody vs. self-verification); CB-3 vs. CB-3-Alt (adjudication vs. authority-validity, corrected evidence: CB-3 now tied with CB-1 at 8 families, not 11).
- **Open questions remaining** for the ARB (mirrors the three deferred items plus the family-count audit's own residual note that P2's earlier iterations, 3a-v1-era counts, were not re-checked since they predate the flagged jump).
- **Architectural-baseline statement for Context Mapping:** Context Mapping remains **not yet authorized** — two of four skeleton positions are still genuinely undetermined between named alternatives, so a complete Context Map cannot yet be produced; only a future explicit ARB ruling on the deferred items (or an explicit decision to proceed with them as documented placeholders) unblocks it.
- Explicitly out of scope, verified by grep before committing (same discipline as every prior EPIC-002 artifact): Context Map, relationship patterns (Partnership/ACL/Shared Kernel/Published Language), aggregates, entities, repositories, APIs, events, services, databases, IDDs.

### 5. Update session log again and commit the decision record

## Verification

- `grep -rn "11 independent\|11 families\|(11)" docs/implementation/EPIC-002_*.md` returns nothing after step 1's edits.
- `grep -n -i -E "context map|shared kernel|published language|partnership pattern|aggregate|entit(y|ies)|repository|\bAPI\b|\bIDD\b" docs/implementation/EPIC-002_Canonical_Domain_Model_Decision.md` returns only scope-declaration/stop-condition lines, same pattern used successfully for the two prior reports.
- `git log --oneline -5` shows two new commits (audit-correction, then decision-record) with the working tree otherwise clean of unrelated changes (`git status --short` limited to the intended files, per this session's established discipline of never broad-`git add`-ing).

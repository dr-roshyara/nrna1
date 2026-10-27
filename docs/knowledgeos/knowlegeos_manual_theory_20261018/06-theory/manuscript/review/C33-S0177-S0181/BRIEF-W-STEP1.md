# C33, the window commission: step 1 (blind inventory)

*2026-10-27. Window reviewer W (Opus). Rulebook v1.4. Instruction: `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). W = `docs/knowledgeos/knowlegeos_manual_theory_20261018/`.*

Write the blind inventory (REVIEWER-INSTRUCTIONS "Step 1") of the five sources below, in index order, each read whole to its last line. For each source, also give **your depth judgement**: full, or skim. Under v1.4 §3, skim only a purely operational source: a diagram, a log, a listing. Hand back and stop.

| S-ID | Path (repository-relative) | Lines |
|---|---|---|
| S0177 | `docs/knowledgeos/architecture/03-KnowledgeOS-Evidence-Assurance-and-Governance.md` | 174 |
| S0178 | `docs/knowledgeos/architecture/01-KnowledgeOS-Domain-and-Context-Architecture.md` | 192 |
| S0179 | `docs/knowledgeos/architecture/07-KnowledgeOS-Target-Architecture-Review.md` | 289 |
| S0180 | `docs/knowledgeos/architecture/04-KnowledgeOS-Event-and-Integration-Architecture.md` | 126 |
| S0181 | `docs/knowledgeos/architecture/05-KnowledgeOS-Architecture-Patterns-and-Technology.md` | 131 |

All five are on `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. They belong to one "Architecture Review Set", which summarises other corpus files. The files they cite are **not** part of this window: do not open them.

## Rules for the inventory (the Principal)
- Keep what a source says, what it reports, and your own judgements ([I]) apart.
- A finding is not resolved because code or tests pass.

## Access (binding)
- **Allowed:** `03-methodology/REVIEWER-INSTRUCTIONS.md`, this brief, and the five sources. **Nothing else**: not the book, the register, the map, the review folders, or any other corpus file. The inventory is blind.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- Never `.env*`, `_batch_input*`, sealed, hold-out or quarantine files, or `docs/knowledgeos/chronological-read/*`. No searches or listings of `docs/knowledgeos/`.
- No corpus text to any outside service. Edit nothing. Do not reproduce any private person's name.

## Hand-back
Begin "C33 STEP 1: BLIND INVENTORY". Per source: numbered items with line numbers; distinctions, rules, claims, contradictions, changes over time, open questions; [I] for your own judgements. Then a cross-source table and a lineage note. End with every file you opened, and the commands you ran.

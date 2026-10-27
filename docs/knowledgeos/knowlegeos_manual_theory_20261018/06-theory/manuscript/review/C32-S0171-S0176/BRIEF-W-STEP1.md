# C32, the window commission: step 1 (blind inventory)

*2026-10-27. Window reviewer W (Opus). Rulebook v1.4. Instruction: `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). W = `docs/knowledgeos/knowlegeos_manual_theory_20261018/`.*

Write the blind inventory (REVIEWER-INSTRUCTIONS "Step 1") of the five sources below, in index order, each read whole. For each source, also give **your depth judgement**: full, or skim. Under v1.4 §3, skim only a purely operational source: a diagram, a log, a listing. Hand back and stop.

| S-ID | Path (repository-relative) | Lines (including a last line without a newline) |
|---|---|---|
| S0171 | `docs/knowledgeos/brainstorming/_misc/20260819-221159-digitalization-robot-vision.md` | 638 |
| S0172 | `docs/knowledgeos/brainstorming/20260816-204714-track2-eks-semantic-discovery.md` | 498 |
| S0173 | `docs/knowledgeos/brainstorming/20260821-121929-business-translator-capability.md` | 355 |
| S0175 | `docs/knowledgeos/architecture/06-KnowledgeOS-Operating-Model-and-Product-Architecture.md` | 144 |
| S0176 | `docs/knowledgeos/architecture/02-KnowledgeOS-Kernel-and-Platform-Architecture.md` | 179 |

All five are on `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. S0174 lies between S0173 and S0175 in the index; it is not on the allowed list and is not part of this window. Read each file to its last line: some files have no final newline.

## Rules for the inventory (the Principal)
- Keep what a source says, what it reports, and your own judgements ([I]) apart.
- A finding is not resolved because code or tests pass.

## Access (binding)
- **Allowed:** `03-methodology/REVIEWER-INSTRUCTIONS.md`, this brief, and the five sources. **Nothing else**: not the book, the register, the map, the review folders, or any other corpus file. The inventory is blind.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- Never `.env*`, `_batch_input*`, sealed, hold-out or quarantine files, or `docs/knowledgeos/chronological-read/*`. No searches or listings of `docs/knowledgeos/`.
- No corpus text to any outside service. Edit nothing. Do not reproduce any private person's name.

## Hand-back
Begin "C32 STEP 1: BLIND INVENTORY". Per source: numbered items with line numbers; distinctions, rules, claims, contradictions, changes over time, open questions; [I] for your own judgements. Then a cross-source table and a lineage note. End with every file you opened, and the commands you ran.

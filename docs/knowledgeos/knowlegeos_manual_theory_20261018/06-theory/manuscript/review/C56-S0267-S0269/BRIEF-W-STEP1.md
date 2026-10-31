# C56, the window commission: step 1 (blind inventory)

*2026-10-30. Window reviewer W (Opus). Rulebook v1.4. Instruction: `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). W = `docs/knowledgeos/knowlegeos_manual_theory_20261018/`.*

Write the blind inventory (REVIEWER-INSTRUCTIONS "Step 1") of the sources below, in index order, each read whole to its last line (read long files in consecutive parts; some files have no final newline). For each source, also give **your depth judgement**: full, or skim. Under v1.4 §3, skim only a purely operational source: a diagram, a log, a listing. Hand back and stop.

| S-ID | Path (repository-relative) | Lines |
|---|---|---|
| S0267 | `docs/knowledgeos/brainstorming/20260822-112442-bala-ganesha-wisdom-engine-lens.md` | 1385 |
| S0268 | `docs/knowledgeos/brainstorming/20260822-112804-knowledgeos-consolidation-review-character-synthesis.md` | 782 |
| S0269 | `docs/knowledgeos/reviews/2026-08-22-KOS-AIP-GOV-STATE-DURABILITY-ADR-session-completion-handoff-protocol-independent-governance-review.md` | 210 |

All are on `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`.

## Rules for the inventory (the Principal)
- Keep what a source says, what it reports, and your own judgements ([I]) apart.
- A finding is not resolved because code or tests pass.

## Access (binding)
- **Allowed:** `03-methodology/REVIEWER-INSTRUCTIONS.md`, this brief, and the sources above. **Nothing else**: not the book, the register, the map, the review folders, or any other corpus file, including files the sources cite. The inventory is blind.
- **Never open** S0155, S0156, S0157, S0161, S0170 or S0174, and do not rely on anything a source says about them; mark such items "(access)". Never `.env*`, `_batch_input*`, sealed, hold-out or quarantine files, or `docs/knowledgeos/chronological-read/*`. No searches or listings of `docs/knowledgeos/`, except a targeted check of these three paths against the allowed list.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- No corpus text to any outside service. Edit nothing. Do not reproduce any private person's name.

## Hand-back
Begin "C56 STEP 1: BLIND INVENTORY". Per source: numbered items with line numbers; distinctions, rules, claims, contradictions, changes over time, open questions; [I] for your own judgements. Then a cross-source table and a lineage note. End with every file you opened, and the commands you ran.

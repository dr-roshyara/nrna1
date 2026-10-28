# C35, the window commission: step 1 (blind inventory)

*2026-10-28. Window reviewer W (Opus). Rulebook v1.4. Instruction: `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). W = `docs/knowledgeos/knowlegeos_manual_theory_20261018/`.*

Write the blind inventory (REVIEWER-INSTRUCTIONS "Step 1") of the sources below, in index order, each read whole to its last line (read long files in consecutive parts; some files have no final newline). For each source, also give **your depth judgement**: full, or skim. Under v1.4 §3, skim only a purely operational source: a diagram, a log, a listing. Hand back and stop.

| S-ID | Path (repository-relative) | Lines |
|---|---|---|
| S0187 | `docs/knowledgeos/brainstorming/20260821-2032-how-to-change-eks-into-knowledgeos-kernel.md` | 844 |
| S0188 | `docs/knowledgeos/brainstorming/20260821-2033-what-eks-is-today.md` | 1,329 |
| S0189 | `docs/knowledgeos/brainstorming/20260801-1231-eks-current-architecture-baseline.md` | 2,098 |
| S0190 | `docs/knowledgeos/reviews/KOS-AIP-GOV-STATE-DURABILITY-DV-CORRECTION-REVIEWER-ELIGIBILITY-DECLARATION-8a525719.md` | 202 |
| S0191 | `docs/knowledgeos/brainstorming/20260821-2108-how-to-integrate-current-eks-into-knowledgeos-kernel.md` | 671 |

All are on `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`.

## Rules for the inventory (the Principal)
- Keep what a source says, what it reports, and your own judgements ([I]) apart.
- A finding is not resolved because code or tests pass.

## Access (binding)
- **Allowed:** `03-methodology/REVIEWER-INSTRUCTIONS.md`, this brief, and the sources above. **Nothing else**: not the book, the register, the map, the review folders, or any other corpus file, including files the sources cite. The inventory is blind.
- **Never open** S0155, S0156, S0157, S0161, S0170 or S0174, and do not rely on anything a source says about them; mark such items "(access)". Never `.env*`, `_batch_input*`, sealed, hold-out or quarantine files, or `docs/knowledgeos/chronological-read/*`. No searches or listings of `docs/knowledgeos/`.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- No corpus text to any outside service. Edit nothing. Do not reproduce any private person's name.

## Hand-back
Begin "C35 STEP 1: BLIND INVENTORY". Per source: numbered items with line numbers; distinctions, rules, claims, contradictions, changes over time, open questions; [I] for your own judgements. Then a cross-source table and a lineage note. End with every file you opened, and the commands you ran.

# C73, the window commission: step 1 (blind inventory)

*2026-11-02. Window reviewer W (Opus). Rulebook v1.4. Instruction: `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). W = `docs/knowledgeos/knowlegeos_manual_theory_20261018/`.*

Write the blind inventory (REVIEWER-INSTRUCTIONS "Step 1") of the sources below, in index order, each read whole to its last line (read long files in consecutive parts; some files have no final newline). For each source, also give **your depth judgement**: full, or skim. Under v1.4 §3, skim only a purely operational source: a diagram, a log, a listing. Hand back and stop.

| S-ID | Path (repository-relative) | Lines |
|---|---|---|
| S0311 | `docs/knowledgeos/brainstorming/20260822-143421-semantic-compiler-kernel-boundary-refinement-kos-ev.md` | 930 |
| S0312 | `docs/knowledgeos/brainstorming/20260822-143844-next-steps-architecture-first-then-semantic-compiler-experiment.md` | 400 |
| S0313 | `docs/knowledgeos/governance/2026-08-22-KOS-SESSION-BOOTSTRAP-001-session-completion.md` | 99 |
| S0314 | `docs/knowledgeos/reviews/2026-08-22-KOS-SESSION-BOOTSTRAP-001-diagnostic-and-activation-registration.md` | 93 |

All are on `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`.

## Rules for the inventory (the Principal)
- Keep what a source says, what it reports, and your own judgements ([I]) apart.
- A finding is not resolved because code or tests pass.

## Access (binding)
- **Allowed:** `03-methodology/REVIEWER-INSTRUCTIONS.md`, this brief, and the sources above. **Nothing else**: not the book, the register, the map, the review folders, or any other corpus file, including files the sources cite. The inventory is blind.
- **A file you may not open, you may not search either** (no grep, listing or pattern over it). This applies to every path not listed as allowed.
- **Never open** S0155, S0156, S0157, S0161, S0170 or S0174, and do not rely on anything a source says about them; mark such items "(access)". Never `.env*`, `_batch_input*`, sealed, hold-out or quarantine files, or `docs/knowledgeos/chronological-read/*`. No searches or listings of `docs/knowledgeos/`, except a targeted check of these four paths against the allowed list.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- If you write a scratch file, name it `c73-w-*.txt`.
- No corpus text to any outside service. Edit nothing. Do not reproduce any private person's name. **The outside-authors rule:** persons named as people in prose are referred to by role; eponymous technical terms and bibliographic citations stay unchanged; paths and filenames are quoted unchanged.

## Hand-back
Begin "C73 STEP 1: BLIND INVENTORY". Per source: numbered items with line numbers; distinctions, rules, claims, contradictions, changes over time, open questions; [I] for your own judgements. Then a cross-source table and a lineage note. End with every file you opened, and the commands you ran.

# Adversarial Falsification (the 8 required falsifiers)

| # | Falsifier | Testable? | Result |
|---|---|---|---|
| 1 | `research/` existed before 2026-09-01 but was untracked | Partially — filesystem `mtime` (`02`) is consistent with 2026-09-01 authorship, not with a much-earlier untracked existence, but `mtime` is unreliable evidence for this specific question (it reflects last modification, not creation, and can be altered by checkout operations) | **NOT TESTABLE WITH CURRENT EVIDENCE, beyond the weak mtime signal already reported** |
| 2 | `kernel-reduction/` was produced by Model B but intentionally kept outside the reconstruction corpus | No document stating this intent was found | **NOT TESTABLE — no evidence either way; this remains the genuinely open question §01 claim 12 turns on** |
| 3 | M0030 only specifies a desired output path and no actual output was produced | This is actually the **default, unfalsified reading** — MD-026 never claimed stronger than this; nothing found upgrades it | **Confirmed as the current best-supported reading, not falsified** |
| 4 | The commit message is merely archival language, not governance language | Direct re-read of the commit message: it uses descriptive, cataloguing language ("Brings the research corpus under version control for the first time... nothing here is canonical") — no governance vocabulary (no "ratify," "authorize," "admit," "exclude") | **Falsifier CONFIRMED** — the commit message is archival/descriptive, not a governance act; MD-026's own DDD analysis should not have leaned on it as strongly as "self-governing" implies |
| 5 | `kernel-reduction/` is a research lane but not a DDD bounded context | Tested directly in `03` | **Falsifier CONFIRMED** — this is now this audit's own corrected position |
| 6 | `theory-v1.1-simulation/` belongs to a separate simulation programme | Consistent with MD-026's own already-weaker rating; its own directory name ("simulation") and lack of any citation from admissible B sources support this | **Falsifier CONFIRMED, consistent with MD-026's own existing hedge** |
| 7 | A genuine `ConflictRecord` definition exists elsewhere (not yet searched) | Not exhaustively ruled out — `docs/knowledgeos/` has sibling directories beyond the 6+2 searched only if any exist beyond what MD-025/026 already enumerated (none found); the wider filesystem outside `docs/knowledgeos/` was never in scope for any phase | **NOT TESTABLE beyond the frame already searched — disclosed limitation stands** |
| 8 | A genuine C2 `Θ` definition exists elsewhere | Same reasoning as #7 | **NOT TESTABLE beyond the frame already searched** |

## Net effect

**Falsifier 4 is the single most consequential result of this audit** — it directly confirms that
MD-026's "self-governing" DDD language over-read an archival commit message as if it carried governance
weight, which it explicitly does not (no governance vocabulary present). This grounds the `03`/`06`
correction in a positive, tested finding, not merely a cautious downgrade.

# Evidence: G-6c-1 audit against the Principal's integrity pattern (PBDIGIT-346), 2026-11-14

G-6c-1 was already committed (`bd98f3e5a`); this audit checked it against the review's detailed requirements and closed the gaps test-first.

| File | Shows |
|---|---|
| `a-01-red/` | RED, 3 failures: (1) a test-data defect: "revision row with a notice kind" tripped the finding-shape check; rebuilt so only the discriminator and the revision-shape rules can refuse it; (2) **a real gap:** a finding could be confirmed AND closed; (3) the old separate indexes answered instead of one resolution rule. **The new FK and duplicate-association tests passed at once** (verification of existing protection) |
| `a-02-green/` | GREEN with migration `2026_11_14_110000` (`one_resolution`; an explicit name for the notice-kind CHECK): 9 tests, 33 assertions; every refusal pinned to its named constraint (one case to exactly one of two jointly guarding rules) |
| `a-03-isolated-cluster-all/` | **the full cluster on the final code: 288 tests, exit 0** |
| `a-04-architecture-full.txt`, `.failures` | 343; the same 4 pre-existing failures |

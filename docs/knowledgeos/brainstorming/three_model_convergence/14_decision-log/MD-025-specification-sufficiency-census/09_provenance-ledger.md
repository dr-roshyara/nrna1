# Provenance Ledger

| Claim | Source → Observation → Interpretation → Status |
|---|---|
| B's operators lack I/O types in the admissible frame | 161-file exhaustive grep, `02` → 0 `Domain:` hits, thin coverage elsewhere → confirms MD-024 → `[MACHINE-OBSERVABLE FACT]` |
| `docs/knowledgeos/research/kernel-reduction/` contains full operator contracts | Direct read, `03` → `[DIRECT EVIDENCE]` |
| M0030 cites this directory as its own output | grep + direct read of M0030's raw text, lines 1443/1488 → `[DIRECT EVIDENCE]` |
| This directory is the actual execution output M0030 requested | subject-matter and trial-count match (~150,000) → `[RECONSTRUCTED PROVENANCE]`, explicitly not asserted stronger |
| `classification-register.tsv` never references `docs/knowledgeos/research/` | `grep -c`, 0 matches → `[MACHINE-OBSERVABLE FACT]` |
| `S^epi`'s `Context` argument is formally typed in `theory-v1.1-simulation/C-type-system.md` | Direct read → `[DIRECT EVIDENCE]`, external scope |
| A's own Context material is internally unresolved (4 treatments) | Direct re-read of `02_model-a_gita/03` CT-1, already-established Phase-1 finding → `[DIRECT EVIDENCE]`, reused not re-derived |
| No external elaboration found for `ConflictRecord` or `Θ` | Exhaustive search of `research/` and `architecture/` → `[MACHINE-OBSERVABLE FACT]` (absence within the searched frame) |
| The `research/kernel-reduction/` lane's own headline is later superseded by its own §19 | Direct read of `00-INDEX.md` → `[DIRECT EVIDENCE]` |

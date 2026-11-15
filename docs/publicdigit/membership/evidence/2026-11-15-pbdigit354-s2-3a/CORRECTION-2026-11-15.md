# Correction to this folder's README and to commit 42afaab73's message (added 2026-11-15, during S2-3b)

**The README and the S2-3a commit message say "infrastructure 71". That is wrong.**
- 71 was counted **before** the S2-3a provenance tests (11) and the interim adapter's new assertions were added.
- The suite's size at the S2-3a commit was **83**. S2-3b adds no infrastructure test, and the suite has 83 tests at S2-3b (see `docs/publicdigit/membership/evidence/2026-11-15-pbdigit354-s2-3b/`).

**Nothing else in this folder changes;** the original files are untouched. The other figures (domain 263, application 125, cluster 445, database-free 1015, 20 mutants) were taken on the final S2-3a code.

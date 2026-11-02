# Membership V-2: clean-copy evidence at `7f3c9896b` (2026-11-02)

**Authority:** the Human PO, 2026-11-02, decision on gap **G-8**: archive the D1 evidence in the repository (option B). This closes G-8.
**Purpose:** durable evidence for the PO's reconsideration of the C-1 (`PBDIGIT-268`) G3 verdict after the V-2 fix (`115e031de`). It is condition **D1** of the independent V-2 review.
**This folder records evidence only. It accepts nothing; C-1 stays DEFER FOR EVIDENCE until the PO decides G3.**

## Two kinds of evidence, kept apart

| Location | Kind | Tied to a commit? |
|---|---|---|
| this folder (top level) | **clean-copy run (E-RUN-V2):** the D1 evidence | yes, `7f3c9896b` |
| `historical-shared-tree/` | the RED / GREEN outputs captured while V-2 was developed, in the shared working tree | **no**; kept for history only |

## Clean-copy run (E-RUN-V2)

**Provenance:**
- produced by a separate Claude session on 2026-11-02 at 08:41:58 +01:00 (`env.txt`);
- copied here unchanged from that session's scratchpad (`…/78dc543b-…/scratchpad/d1-evidence/`);
- `SHA256SUMS` is the run's own file, and it verified OK after copying.

**Method:** as recorded by the producing session in the dispositions record §1 (E-RUN-V2), and not re-verified here beyond the files themselves:
- a fresh detached worktree at `7f3c9896b`, with no working-tree changes;
- dependencies from the committed `composer.lock` (`composer install --no-scripts`);
- `phpunit.xml` used; every test in these paths extends `PHPUnit\Framework\TestCase`, so no database is used.

**Target:** `commit.txt` = `7f3c9896b837d8d20def8be724dc5c3ad4d00cfa`.
**Environment:** PHP 8.5.11, PHPUnit 11.5.6 (`env.txt`).
**Working tree after the run:** `worktree-status-after.txt` is empty, i.e. clean.

| # | Command (`N-*.cmd`) | Result (`N-*.txt`) | Exit (`N-*.exit`) |
|---|---|---|---|
| 1 | `AnnulVoidTest.php` | OK (24 tests, 159 assertions) | 0 |
| 2 | `tests/Unit/Contexts/Membership/Domain/Organisational` | OK (117 tests, 112565 assertions) | 0 |
| 3 | `…/Application/Organisational` | OK (61 tests, 226 assertions) | 0 |
| 4 | `…/Infrastructure/Organisational` | OK (13 tests, 41 assertions) | 0 |
| 5 | the 7 `tests/Architecture/Membership*Test.php` files | OK (51 tests, 62 assertions) | 0 |

**Naming:** the files keep the names they had when produced (`N-*.txt`, not the `NN-*.out` used by older folders), so the run's own `SHA256SUMS` stays valid.

## Historical outputs (`historical-shared-tree/`)

Captured by the implementing session in the shared working tree, before the commit. **Not reproduced, not tied to a commit.** Hashes are in `historical-shared-tree/SHA256SUMS`, and they are the hashes recorded in the dispositions record.

| File | What it shows |
|---|---|
| `red.txt` | RED 1: two tests error, because `AnnulRequiresDecisionProvenance` did not exist yet |
| `red2.txt` | RED 2, reason present but guard absent. Test 1 fails on its **`attested` iteration** (refusal `null`, so the Annul was accepted); the `rule` iteration was **not observed**, because PHPUnit stops at the first failing assertion. Test 3 fails at "before `AnnulOfVoidRefused`" with `AnnulOfVoidRefused`, i.e. **wrong precedence, not acceptance** |
| `review-annulvoid.txt` | GREEN: `AnnulVoidTest` 24 OK |
| `review-domain.txt` | the Domain suite, 117 OK |

The clean-copy run supersedes the last two as evidence.

## Verify

```bash
cd docs/publicdigit/membership/evidence/2026-11-02-v2-clean-copy
sha256sum -c SHA256SUMS                                   # the five clean-copy outputs
(cd historical-shared-tree && sha256sum -c SHA256SUMS)    # the four historical outputs
sha256sum -c MANIFEST.sha256                              # every archived file
```

## Not here (not produced)

- a sensitivity mutant that removes the V-2 check (the independent review rated it optional);
- any acceptance.

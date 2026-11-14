# Evidence: G-6c acceptance audit and acceptance conditions C1 and C2 (PBDIGIT-346), 2026-11-14

| File | Shows |
|---|---|
| `audit/a1-db-free.txt`, `audit/a2-cluster/`, `audit/a3-arch.txt` | the acceptance audit's reproduction at `1e3d9a001`: database-free 343; cluster 320, exit 0; architecture 343 with the same 4 pre-existing failures |
| `audit/guard-probe.php`, `audit/a4-guard-probe.txt` | the REAL rule-7 pattern (read by reflection) against nine bypass shapes: 3 caught, 6 not |
| `c2-01-red.txt` | C2 RED: the planted `RogueChainedWriter` (a builder chain split over lines) is not caught |
| `c2-02-green.txt` | C2 GREEN: caught; the real source tree is still clean (no false positive on legitimate writes) |
| `c2-03-guard-probe-after.txt` | the probe after C2: 4 caught; the other 5 are documented in guide 31 §3 "What the guard does not catch" |
| `c2-04-db-free.txt` | database-free: 343 GREEN |
| `c2-05-arch.txt` | architecture: 343; the same 4 pre-existing failures |

**C1:** guide 31 no longer claims the guard makes bypass impossible. It states the measured reach, what the database enforces for any writer, what only the application path enforces, and why this is not an authorisation boundary.

# 06 — Empirical Evidence Register

## Real-system executions (Level 5)
| Command | Exit | Output |
|---|---:|---|
| `php scripts/knowledge-lint.php` | **0** | `Scanned 37 governed documents. ✅ All documents pass.` |
| `php scripts/knowledge-graph.php` (run 1) | **0** | `39 nodes, 70 edges` |
| `php scripts/knowledge-graph.php` (run 2) | **0** | **byte-identical to run 1** |

## Artifacts preserved — `step-280/exec/`
`kos279.py` · `kosmodel.py` · `corpus.py` · `run_e_tests.py` · `run_f_tests.py` · `run_real_and_stats.py`
`OUT-CORPUS.txt` · `OUT-E-EXECUTION.txt` · `OUT-E-RESULTS.json` · `OUT-F-EXECUTION.txt` ·
`OUT-F-RESULTS.json` · `OUT-REAL-AND-STATS.txt` · `OUT-TIMESTAMP.txt`

## Evidence hierarchy distribution
| Level | Meaning | E-tests | F-tests |
|---:|---|---:|---:|
| 5 | real KnowledgeOS validation | **8** | 0 |
| 4 | controlled empirical test | 13 | 13 |
| 3 | integration execution | 1 | 1 |
| 2 | unit execution | 1 | 0 |
| 0 | assertion only | 1 | 0 |

> **No Level-6 evidence exists — nothing here has been independently reproduced.**
> The specification counts only Levels 4–6 as substantive: **21/24 E-tests and 13/14 F-tests qualify**,
> but **only 8 carry Level-5 real-system evidence.**

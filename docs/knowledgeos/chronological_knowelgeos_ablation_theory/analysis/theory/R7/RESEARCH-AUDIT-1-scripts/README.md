# RESEARCH-AUDIT-1 reproduction scripts

These are snapshots of the analysis scripts used for `../RESEARCH-AUDIT-1.md` (r1.1). They were copied from the session scratchpad.

| Script | What it covers |
|---|---|
| `audit*.py`, `q2.py` | The auditor's r0 computations: encoding A0 and the Q1–Q4 measures |
| `enc.py` | The reviewer's independent encoding (RV) of all 83 T2 rows |
| `a1.py`–`a10.py` | The reviewer's checks of r0 |
| `q2r1.py`, `q2r1v.py`, `q2r1x.py` | The r1 Q2 re-run: size-matched nulls (`q2r1.py`), verification (`q2r1v.py`), exhaustive 9│14 rank (`q2r1x.py`). Encodings A0, A1 and RV |
| `q2r1.out`, `q2r1_rerun.out` | Outputs of the re-run |
| `rr_*.py` | The r1 re-review sensitivity checks: weighting (`rr_coh.py`), equal volume (`rr_vol.py`), K3 fixes (`rr_k.py`) |

Some scripts may reference absolute scratchpad paths, which you may need to adjust. This is analysis only. It decides nothing.

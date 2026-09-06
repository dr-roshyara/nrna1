# `second-order/exec/` — Executable evidence

Every `EXECUTION EVIDENCE` claim in documents `01`–`07` traces to a program here.
Nothing in this directory is architecture. It is a set of witnesses.

```bash
for f in second-order/exec/so_exp*.py; do echo "== $f"; python3 "$f"; done
```

Standard library only. No file outside this directory is written.

| File | Purpose |
|---|---|
| `so_model.py` | Shared model: `Obj`/`State`, six candidate abstractions `F1…F6` (per §259.6), eight class-1 operations each in a `blind` and a `sensitive` dependency variant (per §259.11–13). |
| `so_exp01_congruence_matrix.py` | **Computes Step 259 §259.9's matrix**, which the corpus leaves entirely `UNRESOLVED`. 6 abstractions × 8 operations × 2 variants = 96 cells over a 208-state domain. |
| `so_exp02_invariant_expressibility.py` | Shows `F4` passes `Merge`/sensitive **vacuously** — it cannot see what the operation preserves. Derives the second criterion: invariant-expressibility. |
| `so_exp03_adversarial_operations.py` | Classifies all 17 corpus-named operations into §259.7's five classes and applies §259.8's per-class test. Corrects the first-order EXP-3 inference. |
| `so_exp04_mandated_invariants.py` | Audits the six invariants the corpus states with mandatory force against each abstraction. 5/6 expressible in `K=(𝒜,ℛ)`. |
| `so_exp05_sigma_from_272.py` | Partially discharges the **commissioned but unwritten Step 272** using its own six named inputs and the minimal-sufficient-statistic method. |
| `so_exp06_closure.py` | Marks all 29 canonical nodes with the mandate's seven states; recomputes cycles; computes the five closures separately. |

## Reproduced from outside this directory

```bash
php .claude/scripts/session-bootstrap.php --process-label=verification   # UNRESOLVED, 73 lanes, fail-closed
python3 - <<'PY'   # the grant census reported in 02
import json,glob
gs=[]
for f in glob.glob('.claude/runtime/workflow/*.json'):
    d=json.load(open(f))
    def w(o):
        if isinstance(o,dict):
            if "grantId" in o: gs.append(o)
            for v in o.values(): w(v)
        elif isinstance(o,list):
            for x in o: w(x)
    w(d)
print(len(gs), sum(1 for g in gs if g.get("humanActRef")))   # -> 132 132
PY
```

## Limitations that apply to every experiment here

1. **Bounded domain.** Step 259 §259.16 is explicit: a finite test establishes `Congruent_tested`,
   never `Congruent_global`. Every PASS reads *"no counterexample found in this domain"*.
2. **Modelled value sets.** `so_exp05`'s counts depend on the value sets chosen for Step 272's six
   sources. The *structural* result (Σ is a product of axes) does not.
3. **Argued classifications.** The class assignment of each operation in `so_exp03` and each node
   mark in `so_exp06` is argued from corpus text and is falsifiable. Sources are cited inline.
4. **Independence.** The test *criteria* are the corpus's own (§259.15, §259.8, §271.36). The
   *executions* are new. See `07` §6 for the fingerprint check.

## How to refute the main result

`so_exp01` accepts new operations. **Exhibit a class-1 operation, sourced to the corpus, for which
`K=(𝒜,ℛ)` fails congruence** — add it to `OPERATIONS` in `so_model.py` and re-run. That would reopen
D-1.

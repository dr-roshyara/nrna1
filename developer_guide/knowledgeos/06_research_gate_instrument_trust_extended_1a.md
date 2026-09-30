# 06 — Research-gate instrument trust (extended Increment 1a)

> **A gate that examined nothing has not passed, and a run whose rule book
> may have moved under it has no verdict at all.** Extended 1a turns both
> sentences into code: `INCONCLUSIVE` for the first, `GOVERNANCE_INOPERATIVE`
> for the second.

## Purpose

The developer how-to for the **temporary** research-gate instrument in
`docs/knowledgeos/knowledgeos_theory_chronological_extraction/governance/`
after extended Increment 1a. It covers what changed, why, how to run it, and how
to add a check without reopening the defects that `GATE-INTEGRITY-AUDIT-02`
found. Canonical decision text lives elsewhere, once:
`governance/GIA-DECISION-PACKAGE-01.md` §7 (IC-1…IC-8) and
`governance/L0-DECISION-RECORD-01.md` (L0-DEC-12…17).

⚠️ **Scope:** this instrument serves one research programme only. It is not
platform governance and not a registry asset (`governance/README.md` §1).

## Where it fits

```
<workspace>/                               the research workspace (research artifacts)
  governance/
    gates.yaml          rule book           — governance session only (L0-DEC-15)
    gate-schema.yaml    shape of a gate     — now ENFORCED on every run
    governance-state.yaml activation        — the HUMAN writes it (pins)
    gate-runner.py      the instrument
    fixtures/           pass · fail · cases/<check>/<case>/EXPECT.json · CANONICAL-LIST.log
    regression/run_regression.py  the 60-case battery
  .claude/hooks/governance-preflight.sh     the door (exit 0 · 2 · 3)
docs/knowledgeos/list_of_files_to_read.log  canonical corpus list (KOS-G-003's F-ids)
```

## The two new outcomes

| Outcome | Level | When | Door |
|---|---|---|---|
| `INCONCLUSIVE` | one gate | a required input is **absent**, or present with **0 records** (IC-1) | blocks like FAIL → exit 2 |
| `GOVERNANCE_INOPERATIVE` | the whole run | unreadable YAML · duplicate gate id · schema violation · self-test < 100% · activated gate not `active` · activated REVIEW/HUMAN gate · unpinned activation · pin mismatch · runner crash | exit **3**. Never 2, never 0 |

## How it works

**Missing input ⇒ `Inconclusive`.** Checks read their required inputs via
`_required()`. It raises instead of returning `[]`, which was the fail-open
path (FP-1):

```python
def _required(root, rel, allow_empty=False):
    path = os.path.join(root, rel)
    if not os.path.exists(path):
        raise Inconclusive(f"{rel}: absent")
    recs, _ = _jsonl(path)
    if not recs and not allow_empty:
        raise Inconclusive(f"{rel}: 0 records")
    return recs
```

Pass `allow_empty=True` only when an empty file is a meaningful input. An
example is the index in `index_coverage`: an empty index against a non-empty
registry is a real FAIL, not a non-measurement.

**Operability before evaluation.** `main()` calls `operability(gates,
activated)` before `evaluate()`. Any reason returned becomes
`GOVERNANCE_INOPERATIVE` with exit 3. The self-test runs **on every evaluation**
(IC-4), so deleting fixtures or corrupting an `EXPECT.json` stops the
instrument instead of passing silently.

**Pins (L0-DEC-16).** An activation entry is

```yaml
- {id: KOS-G-003, pin: "<16 hex>", by: <human>, date: 2026-09-23}
```

with `pin_of(gate)` = sha256 of `json.dumps(gate, sort_keys=True,
ensure_ascii=False, separators=(",", ":"), default=str)`, first 16 hex. Editing
any field of a pinned gate (status, tier, class, check, even `purpose`)
changes the pin. This closes D08, D09 and D10: re-statusing or re-tiering an
activated gate no longer produces a silent CLEAR. `--pin-lines` prints the
lines, and **the human pastes them**. Quote the pin, because YAML reads an
all-digit hex string as an integer.

**Applicable means evaluated.** The run counts a gate as applicable only when it is
activated **and** its verdict is one of PASS/FAIL/ERROR/INCONCLUSIVE. This removes
the D09 mechanism, where NOT_APPLICABLE rows were counted as exercised.

**Declared scope = implemented scope (IC-5).** `_jsonl_paths()` walks the
workspace recursively and skips the top-level `governance/` tree (its fixtures
are deliberately broken) and hidden directories. KOS-G-001 and KOS-G-003
declare exactly that in `gates.yaml`. KOS-G-003 **does not scan markdown**, and
says so.

**Canonical F-ids (L0-DEC-17).** `c_no_dangling_refs` takes its known F-ids from
`CANONICAL_LIST`, which is derived from the runner's own location (never from
`--root`). It no longer uses the research registry. A correct canonical id of a
not-yet-registered file (G02, and the live F2841…F2847 false FAIL) now passes.

## How to add or change a check

1. **Fixtures first.** Add `fixtures/cases/<check>/<case>/` with the inputs
   and `EXPECT.json` = `{"expect": "PASS|FAIL|INCONCLUSIVE", "canonical":
   "shared|local|none"}`. At minimum add a *missing* case, an *empty* case and
   one *near-miss*. Run `--self-test` and see it MISMEASURE (RED).
2. Implement the check and read every required input through `_required()`.
3. Add a regression case to `CASES` in `run_regression.py`, with its expected
   verdict, **before** the change. Run with `--control rev` to see RED, then
   without it to see GREEN.
4. Changing `gates.yaml` changes the pins. The human must re-activate the
   affected gates. That is the point, not a nuisance.

## Testing

```bash
cd docs/knowledgeos/knowledgeos_theory_chronological_extraction
python3 governance/gate-runner.py --self-test                     # 55/55
python3 governance/regression/run_regression.py                   # 66/66 (worktree control plane; 60/60 before L0-DEC-21)
python3 governance/regression/run_regression.py --control rev --rev 9abd4ad95   # the pre-1a runner: RED
```

The battery reads research data from `git archive` and never touches the live
workspace. It pins the five first-group gates with **its own** `pin_of`, so a
disagreement with the runner's algorithm shows up as INOPERATIVE on A0.

## Correction slice after the 1a.6 review (L0-DEC-21)

`operability()` also refuses: a **tier-B activation** (IR-G1; `--pin-lines` no longer prints pins for tier-B gates) and an activated AUT gate whose `check` is **not in `CHECKS`** (IR-G4). `main()` refuses a `--stage`/`--timing` value outside the `gate-schema.yaml` enums (IR-G2). The door's no-control message now reads *"NO ACTIVATED CONTROL WAS EXERCISED (none is activated, or none applies to the requested stage/timing)"*. Regression cases R1–R6. The battery is now **66/66** and still RED on R1–R5 at `6e52ba73a`.

## Pitfalls

- **CLEAR is still only process conformance** over the activated rules
  (L0-DEC-12). C1 (swapped registry paths) and the RCI-015 and epistemic cases
  (E1–F2) still CLEAR, because no activated gate covers them. Binding the
  registry to the canonical corpus is 1b.
- **A pin is not a signature.** It detects that a definition *drifted*. It does
  not prove *who* pasted it (residual R-1).
- **Runner code is not pinned** (R-2). The self-test is the only guard against a
  weakened check.
- Do not "fix" `INOPERATIVE` by editing `governance-state.yaml` from a research
  session. Activation is a human act.

*Traceability:* `GIA-DECISION-PACKAGE-01.md` §7 IC-1…IC-8 ·
`L0-DEC-13…17` · `audits/2026-09-23-GATE-INTEGRITY-AUDIT-02.md` (D09, G02,
FP-1) · plan `docs/plans/20260923-0212-kos-evidence-binding-increment-1-plan.md`
(progress section) · commits `4746093a8` (1a.2 RED) and the 1a.3 commit.

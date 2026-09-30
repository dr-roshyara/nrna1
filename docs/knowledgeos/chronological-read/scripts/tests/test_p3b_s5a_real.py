"""Real pre-S5 data (allowlisted discovery loaders only): the three pre-S5 generators and their control draw, run twice.
Reproduces the plan previews under the preview conventions, checks the control invariants, and checks determinism.
No S5 execution, no hold-out access, no corpus reads. Outputs stay in memory."""
import collections
import sys

import s5a_test_fixtures as F
import p3b_s5_common as C
import p3b_s5a_generators as G
from test_p3b_s5a_mechanics import _check_controls

PRE = ("G-SHARED-GROUP", "G-NOTATION", "G-TYPE-SIM")
_CACHE = {}


def _ctx():
    if "ctx" not in _CACHE:
        C.assert_sealed()
        _CACHE["ctx"] = G.Ctx.load()
    return _CACHE["ctx"]


def test_preview_reproduction():
    ctx = _ctx()
    sets = G._ksubsets_from_buckets({g["group_id"]: g["members"] for g in ctx.groups}, ctx.pop)
    assert collections.Counter(len(s) for s in sets) == {2: 709, 3: 10}                    # plan §G.3: 719
    b = collections.defaultdict(set)
    for lab, n in ctx.nodes.items():
        for x in (n.get("notations") or []) + (n.get("aliases") or []):
            b[str(x).strip()].add(lab)
    s = G._ksubsets_from_buckets({k: v for k, v in b.items() if len(v) > 1}, ctx.pop)
    assert collections.Counter(len(x) for x in s) == {2: 72, 3: 9}                         # plan §G.3: 81 (preview norm.)


def test_real_determinism_and_control_invariants():
    ctx = _ctx()
    a = G.build(ctx, PRE)
    b = G.build(ctx, PRE)
    h = lambda recs: C.sha256_bytes(G.body_text(recs).encode())
    assert h(a[1]) == h(b[1]) and h(a[2]) == h(b[2])
    assert _check_controls(ctx, a[0], a[2]) > 0
    for r in a[1]:
        assert set(r["members"]) <= ctx.pop
    C.assert_sealed()
    print("   real: " + ", ".join(f"{g} full={len(r.candidates)} family={sum(c['in_test_family'] for c in r.candidates)}"
                                  for g, r in a[0].items()) + f"; candidates sha={h(a[1])[:12]} controls sha={h(a[2])[:12]}")


if __name__ == "__main__":
    sys.exit(F.run_all(dict(globals())))

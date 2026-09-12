"""Independent verification of 2026-09-06-KOS-K9-CLOSURE-CONDITIONAL-DEPENDENCY-COMPUTATION.md.

Uses the SOURCE program's graph and closure procedure by direct import -- not the
review lane's transcription -- so the check is independent of that transcription.
Read-only. Asserts nothing about kernel minimality.
"""
import importlib.util, itertools, os, sys

ROOT = os.path.dirname(os.path.abspath(__file__))
SRC = os.path.normpath(os.path.join(ROOT, "..", "..", "readiness", "exec", "minimum_implementable.py"))

spec = importlib.util.spec_from_file_location("mi", SRC)
mi = importlib.util.module_from_spec(spec)
sys.stdout = open(os.devnull, "w")          # the source prints its report on import
spec.loader.exec_module(mi)
sys.stdout = sys.__stdout__

G, closure = mi.G, mi.closure
blocked = lambda n: G[n][2] is not None      # the source's own blocker semantics

def C(seeds): return closure(set(seeds))
def rep(label, s):
    b = sorted(x for x in s if blocked(x))
    print(f"  {label:22s} size {len(s):2d} of {len(G)}   blocked {len(b):2d}   unblocked {len(s)-len(b)}")
    return s

print("=" * 78); print("0. PROVENANCE"); print("=" * 78)
print(f"  graph + closure() imported from: {os.path.relpath(SRC, ROOT)}")
print(f"  nodes: {len(G)}")

print(); print("=" * 78); print("1. CONTROL — does K_4 reproduce the source's published result?"); print("=" * 78)
K4 = ["K", "Rejection", "Replay", "Transformation"]
c4 = rep("Closure(K_4)", C(K4))
ok = (len(c4) == 18 and sum(blocked(x) for x in c4) == 15)
print(f"  published: 18 constructs / 15 blocked   ->  {'REPRODUCED' if ok else '*** MISMATCH ***'}")

print(); print("=" * 78); print("2. Closure(K_9) under the review lane's DECLARED mapping"); print("=" * 78)
SEEDS9 = {
    "C-1": ["Transformation", "Sigma"], "C-2": ["Authorization", "Sigma"],
    "C-3": ["Determination", "Policy"], "C-4": ["Policy", "Authorization"],
    "C-5": ["Evidence"], "C-6": ["K"], "C-7": ["Qualification"],
    "C-8": ["InvariantReg", "Authority", "Evidence"], "C-9": ["Determination"],
}
K9 = sorted({s for v in SEEDS9.values() for s in v})
print(f"  seeds ({len(K9)}): {', '.join(K9)}")
c9 = rep("Closure(K_9)", C(K9))
print(f"  published: 20 constructs           ->  {'REPRODUCED' if len(c9)==20 else '*** MISMATCH ***'}")

print(); print("=" * 78); print("3. INCOMPARABILITY — the load-bearing claim"); print("=" * 78)
print(f"  Closure(K_4) subset of Closure(K_9)?  {c4 <= c9}")
print(f"  Closure(K_9) subset of Closure(K_4)?  {c9 <= c4}")
print(f"  K_4 \\ K_9 ({len(c4-c9)}): {sorted(c4-c9)}")
print(f"  K_9 \\ K_4 ({len(c9-c4)}): {sorted(c9-c4)}")
print(f"  intersection ({len(c4&c9)})")
print(f"  reached by NEITHER ({len(set(G)-c4-c9)}): {sorted(set(G)-c4-c9)}")
inc = not (c4 <= c9) and not (c9 <= c4)
print(f"  published: INCOMPARABLE            ->  {'REPRODUCED' if inc else '*** MISMATCH ***'}")

print(); print("=" * 78); print("4. DEGENERACY — is Qualification definitional?"); print("=" * 78)
c9_noC7 = C([s for k, v in SEEDS9.items() if k != "C-7" for s in v])
print(f"  Qualification in Closure(K_9)                : {'Qualification' in c9}")
print(f"  Qualification in Closure(K_9) with C-7 removed: {'Qualification' in c9_noC7}")
print("  -> reached ONLY via its own seed. The degeneracy warning is CORRECT."
      if "Qualification" not in c9_noC7 else "  -> reached independently; warning would be too strong.")

print(); print("=" * 78); print("5. ONE-AT-A-TIME MAPPING SENSITIVITY"); print("=" * 78)
ALT = {"C-1": ["Sigma"], "C-2": ["Authority"], "C-4": ["Gamma"],
       "C-5": ["Assessment"], "C-6": ["Missingness"]}
PUB = {"C-1": 16, "C-2": 20, "C-4": 21, "C-5": 20, "C-6": 22}
for row, alt in ALT.items():
    seeds = sorted({s for k, v in SEEDS9.items() for s in (alt if k == row else v)})
    cc = C(seeds)
    tag = "" if row not in PUB else ("  REPRODUCED" if len(cc) == PUB[row] else f"  *** MISMATCH (published {PUB[row]}) ***")
    print(f"  {row} -> {str(alt):26s} size {len(cc):2d}   d={sorted(cc-c9)} / -{sorted(c9-cc)}{tag}")

print(); print("=" * 78); print("6. FULL PRODUCT OVER ALL PERTURBED ROWS — the range claim"); print("=" * 78)
rows = list(ALT)
sizes = []
for mask in itertools.product([0, 1], repeat=len(rows)):
    seeds = set()
    for k, v in SEEDS9.items():
        i = rows.index(k) if k in rows else None
        seeds |= set(ALT[k] if (i is not None and mask[i]) else v)
    sizes.append(len(C(seeds)))
print(f"  worlds enumerated: {len(sizes)}   min {min(sizes)}   max {max(sizes)}")
print(f"  published range: 15 … 22           ->  "
      f"{'REPRODUCED' if (min(sizes),max(sizes))==(15,22) else f'*** got {min(sizes)}…{max(sizes)} ***'}")

print(); print("=" * 78); print("7. O_core FRAGILITY — does it enter through one row only?"); print("=" * 78)
for row in rows:
    seeds = sorted({s for k, v in SEEDS9.items() for s in (ALT[row] if k == row else v)})
    cc = C(seeds)
    if "O_core" not in cc:
        print(f"  perturbing {row} alone REMOVES O_core from the closure")
print(f"  O_core in Closure(K_9): {'O_core' in c9}")

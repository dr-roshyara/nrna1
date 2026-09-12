"""Extension of the review lane's robustness design, using the SOURCE program's graph.

Their design: 2 InvariantReg-edge variants x (K_4 + 6 one-at-a-time mappings) = 14 worlds.
This: 2 edge variants x the FULL product over the 5 perturbable C-rows      = 64 worlds,
plus K_4 in both = 66. Everything else identical. Read-only; no minimality claim.
"""
import importlib.util, itertools, os, sys
ROOT = os.path.dirname(os.path.abspath(__file__))
SRC = os.path.normpath(os.path.join(ROOT, "..", "..", "readiness", "exec", "minimum_implementable.py"))
spec = importlib.util.spec_from_file_location("mi", SRC)
mi = importlib.util.module_from_spec(spec)
sys.stdout = open(os.devnull, "w"); spec.loader.exec_module(mi); sys.stdout = sys.__stdout__

G_epi = mi.G
G_str = dict(mi.G); G_str["InvariantReg"] = (["K"], "NOT ENUMERATED", "D")

def clo(roots, g):
    seen, stack = set(), list(roots)
    while stack:
        n = stack.pop()
        if n in seen: continue
        seen.add(n); stack += g[n][0]
    return seen

K4 = ["K", "Rejection", "Replay", "Transformation"]
BASE = {"C-1": ["Transformation","Sigma"], "C-2": ["Authorization","Sigma"],
        "C-3": ["Determination","Policy"], "C-4": ["Policy","Authorization"],
        "C-5": ["Evidence"], "C-6": ["K"], "C-7": ["Qualification"],
        "C-8": ["InvariantReg","Authority","Evidence"], "C-9": ["Determination"]}
ALT  = {"C-1": ["Sigma"], "C-2": ["Authority"], "C-4": ["Gamma"],
        "C-5": ["Assessment"], "C-6": ["Missingness"]}
rows = list(ALT)

worlds = []
for gname, g in (("epistemic", G_epi), ("structural", G_str)):
    worlds.append((f"K_4/{gname}", clo(K4, g)))
    for mask in itertools.product([0,1], repeat=len(rows)):
        seeds = set()
        for c, v in BASE.items():
            i = rows.index(c) if c in rows else None
            seeds |= set(ALT[c] if (i is not None and mask[i]) else v)
        worlds.append((f"K_9/{gname}/{''.join(map(str,mask))}", clo(seeds, g)))

sizes = [len(w[1]) for w in worlds]
inv = set.intersection(*[w[1] for w in worlds])
uni = set.union(*[w[1] for w in worlds])
blocked = lambda n: mi.G[n][2] is not None

print("="*78); print("EXTENDED WORLD SET"); print("="*78)
print(f"  worlds: {len(worlds)}   (review lane's design: 14)")
print(f"  closure size ranges over {min(sizes)} .. {max(sizes)} of {len(mi.G)}")
print(f"  review lane published:   15 .. 22")
print(f"  -> the published range is {'CONFIRMED and WIDENS' if (min(sizes)<=15 and max(sizes)>=22) else 'NOT reproduced'}"
      f" to {min(sizes)}..{max(sizes)}")
print()
print(f"  present in EVERY world ({len(inv)}): {', '.join(sorted(inv))}")
bl = sorted(n for n in inv if blocked(n))
print(f"  of those BLOCKED ({len(bl)}):")
for n in bl: print(f"      {mi.G[n][2]:3s} {n:15s} {mi.G[n][1]}")
print(f"\n  review lane's triply-robust blocked set (5): Identity, InvariantReg, K, Proposition, Relation")
print(f"  -> {'IDENTICAL under the wider set' if bl==sorted(['Identity','InvariantReg','K','Proposition','Relation']) else 'DIFFERS: '+str(bl)}")
print(f"\n  present in NO world ({len(set(mi.G)-uni)}): {', '.join(sorted(set(mi.G)-uni)) or '(none)'}")

# does any single world make O_core survive AND InvariantReg structural?
print()
print("="*78); print("O_core ACROSS THE WIDER SET"); print("="*78)
n_with = sum(1 for _, c in worlds if "O_core" in c)
print(f"  O_core present in {n_with} of {len(worlds)} worlds ({100*n_with//len(worlds)} %)")

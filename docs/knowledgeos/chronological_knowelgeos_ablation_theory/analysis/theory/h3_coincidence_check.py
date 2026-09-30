"""Exploratory [TEST under ASSUME H3]: what if every PromEvent coincides with its AuthEvent (same step: au 0->1 AND ad 0->1)?
The frozen Gate 2.5 reference (gate25/model_g25.py) is imported read-only; H3 is added as an extra step filter in a subclass.
Nothing frozen is modified. Reports the discriminating properties for the four primary models on chain3/V/diamond."""
import sys, os
sys.path.insert(0, os.path.join(os.path.dirname(os.path.abspath(__file__)), "..", "gate25"))
import model_g25 as G

class SysH3(G.Sys):
    def ok(self, x, k, y):
        if not super().ok(x, k, y): return False
        auth = x[5] == 0 and y[5] == 1; prom = x[6] == 0 and y[6] == 1
        return auth == prom            # H3: authorization and promotion are one act

KEYS = ["EVENT-FLOOR-SAFETY", "TOCTOU", "PERSIST", "AUTO-INVAL", "REVOC-EXPLICIT", "REVAL-a", "REVAL-b", "P-GUARD", "AUTH-SAFETY"]
for mdl in ("MT1-P0", "MT1-P1", "MT2-P0", "MT2-P1"):
    full = G.BASE + G.EXTRA[mdl]; row = {}
    for inst in ("chain3", "V", "diamond"):
        base, _ = G.properties(G.Sys(inst, full)); h3, _ = G.properties(SysH3(inst, full))
        row[inst] = {k: (base[k]["class"], h3[k]["class"]) for k in KEYS}
    same = all(row[i] == row["chain3"] for i in row)
    print(f"{mdl} (instance-invariant: {same})")
    for k in KEYS:
        b, h = row["chain3"][k]; print(f"   {k:20} frozen={b:14} H3={h:14} {'CHANGED' if b != h else ''}")

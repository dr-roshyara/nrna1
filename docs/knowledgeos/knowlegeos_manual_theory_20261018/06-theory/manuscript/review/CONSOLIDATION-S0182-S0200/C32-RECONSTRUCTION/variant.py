import itertools, importlib.util, sys
spec = importlib.util.spec_from_file_location('r', 'recon.py'); 
src = open('recon.py').read().split('# 1. The reviewer')[0]; ns = {}; exec(src, ns)
# NEW guard WITHOUT "new carrier never used" (the state after C32, before consolidation A-02)
def guard_mid(r, old, new, cites, used): return old == r.carrier and ns['guard_old'](r, old, new, cites)
ops = [('data',)] + [('des', o, n) for o in ns['STORES'] for n in ns['STORES'] if o != n]
bad = None; n_out = 0
for k in range(1, 6):
    for seq in itertools.product(ops, repeat=k):
        r = ns['Rec'](); used = {ns['ROOT']}
        for op in seq: r.data() if op[0]=='data' else ns['designate'](r, op[1], op[2], guard_mid, used)
        if not ns['D_new'](r): n_out += 1; bad = bad or seq
print("carrier-in-force guard only: records outside D:", n_out, "first:", bad)

"""Independent reconstruction of C32 W-01 (fork). Written from the text of §9.17 R3 (c)
only (at 9260e6d3a = OLD rule; at HEAD = NEW rule); does not import ce_model.py."""
import itertools
ROOT = 'C1'; STORES = ['C1', 'C2', 'C3']

class Rec:
    def __init__(s): s.E = []; s.carrier = ROOT          # kernel state (K6: serial)
    def maximal(s, store):
        ids = [e for e in s.E if e['store'] == store]
        cited = {c for e in ids for c in e['cites']}
        return {e['id'] for e in ids if e['id'] not in cited}
    def data(s, cites=()):
        s.E.append(dict(id=f"x{len(s.E)}", kind='data', store=s.carrier, cites=set(cites)))

def guard_old(r, old, new, cites):
    # OLD text: cites every maximal entry the old store has at that step; authority T (assumed)
    return r.maximal(old) <= cites and len(r.maximal(old)) > 0
def guard_new(r, old, new, cites, used):
    # NEW text item 1: old is the carrier in force; new never a carrier; plus the old checks
    return old == r.carrier and new not in used and guard_old(r, old, new, cites)

def D_old(r):
    for d in (e for e in r.E if e['kind'] == 'designate'):
        if not d['closed'] or not d['closed'] <= d['cites']: return False
        if any(next(e for e in r.E if e['id'] == c)['store'] != d['old'] for c in d['closed']): return False
        if d['store'] != d['new'] or d['new'] == d['old']: return False
    return True
def D_new(r):
    ds = [e for e in r.E if e['kind'] == 'designate']
    olds = [d['old'] for d in ds]; news = [d['new'] for d in ds]
    if len(set(olds)) != len(olds) or len(set(news)) != len(news) or ROOT in news: return False
    chain = [ROOT] + news                                  # one chain from the root
    if [d['old'] for d in ds] != chain[:-1]: return False
    return D_old(r) and all(e['store'] in chain for e in r.E)

def designate(r, old, new, guard, used):
    cites = r.maximal(old)
    ok = guard(r, old, new, cites) if guard is guard_old else guard(r, old, new, cites, used)
    if ok:
        r.E.append(dict(id=f"d{len(r.E)}", kind='designate', store=new, old=old, new=new,
                        cites=set(cites), closed=set(cites)))
        r.carrier = new; used.add(new)
    return ok

def forked(r):
    olds = [e['old'] for e in r.E if e['kind'] == 'designate']
    return len(olds) != len(set(olds))

# 1. The reviewer's case, rebuilt: a in C1; d1 C1->C2; d2 C1->C3
for name, g, D in [('OLD', guard_old, D_old), ('NEW', guard_new, D_new)]:
    r = Rec(); used = {ROOT}; r.data()
    a1 = designate(r, 'C1', 'C2', g, used); a2 = designate(r, 'C1', 'C3', g, used)
    print(f"{name}: d1 accepted={a1} d2 accepted={a2} forked={forked(r)} in_D={D(r)}")

# 2. Exhaustive: every sequence of up to 5 kernel steps (data append, or designation old->new)
ops = [('data',)] + [('des', o, n) for o in STORES for n in STORES if o != n]
for name, g, D in [('OLD', guard_old, D_old), ('NEW', guard_new, D_new)]:
    forks = outside = total = 0
    for k in range(1, 6):
        for seq in itertools.product(ops, repeat=k):
            r = Rec(); used = {ROOT}
            for op in seq:
                r.data() if op[0] == 'data' else designate(r, op[1], op[2], g, used)
            total += 1; forks += forked(r); outside += not D(r)
    print(f"{name}: {total} sequences; guarded records that fork: {forks}; guarded records outside D: {outside}")

# 3. Bypass (an entry not appended by a kernel step): a forked record built without the guard
r = Rec(); r.data()
for new in ('C2', 'C3'):
    r.E.append(dict(id=f"d{len(r.E)}", kind='designate', store=new, old='C1', new=new,
                    cites={'x0'}, closed={'x0'}))
print(f"bypass fork: in D_old={D_old(r)} in D_new={D_new(r)}")

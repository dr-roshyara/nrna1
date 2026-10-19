"""Worker B independent check of C2/C4 per PROTOCOL.md v1."""
import sys, platform, random, itertools
from collections import Counter
import numpy as np

N = 3
H = [''] + [''.join(p) for k in range(1, N + 1) for p in itertools.product('01', repeat=k)]
IDX = {h: i for i, h in enumerate(H)}
LAYERS = [[h for h in H if len(h) == k] for k in range(N + 1)]
PAIRS = [(a, b) for k in range(N + 1) for a, b in itertools.combinations(LAYERS[k], 2)]
assert len(PAIRS) == 35
PBIT = {}
for j, (a, b) in enumerate(PAIRS):
    PBIT[(a, b)] = j
    PBIT[(b, a)] = j
FULL = (1 << 35) - 1


def rel(a, b, mask):
    return a == b or (mask >> PBIT[(a, b)]) & 1 == 1


# ---------- enumeration: all set partitions per layer, product, explicit prefix filter ----------
def set_partitions(items):
    # restricted-growth strings
    n = len(items)
    out = []
    def rec(i, rgs, m):
        if i == n:
            out.append(list(rgs)); return
        for c in range(m + 1):
            rgs.append(c); rec(i + 1, rgs, max(m, c + 1) if c == m else m); rgs.pop()
    rec(0, [], 0)
    masks = []
    for rgs in out:
        m = 0
        for x, y in itertools.combinations(range(n), 2):
            if rgs[x] == rgs[y]:
                m |= 1 << PBIT[(items[x], items[y])]
        masks.append(m)
    return masks


LAYER_PARTS = [set_partitions(LAYERS[k]) for k in range(N + 1)]
BELL = [len(x) for x in LAYER_PARTS]


def prefix_closed(mask):
    for (a, b) in PAIRS:
        if rel(a, b, mask) and a != b:
            for t in range(0, len(a) + 1):
                if not rel(a[:t], b[:t], mask):
                    return False
    return True


def is_equivalence(mask):
    for a in H:
        for b in H:
            if len(a) != len(b):
                continue
            if rel(a, b, mask) != rel(b, a, mask):
                return False
            for c in H:
                if len(c) == len(a) and rel(a, b, mask) and rel(b, c, mask) and not rel(a, c, mask):
                    return False
    return True


PC = []
ncand = 0
for combo in itertools.product(*LAYER_PARTS):
    ncand += 1
    m = combo[0] | combo[1] | combo[2] | combo[3]
    if prefix_closed(m):
        PC.append(m)
for m in PC:
    assert is_equivalence(m)
PCSET = set(PC)
PCARR = np.array(PC, dtype=np.uint64)

# ---------- bad(s) for all 2^15 s ----------
S = np.arange(1 << 15, dtype=np.uint64)
BADS = np.zeros(1 << 15, dtype=np.uint64)
for j, (a, b) in enumerate(PAIRS):
    bit = ((S >> np.uint64(IDX[a])) ^ (S >> np.uint64(IDX[b]))) & np.uint64(1)
    BADS |= bit << np.uint64(j)


def masks_for(vals):
    D = dict(zip(H, vals))
    bad = estar = n1 = n2 = n4 = 0
    for j, (a, b) in enumerate(PAIRS):
        k = len(a)
        agree = [D[a[:t]] == D[b[:t]] for t in range(k + 1)]
        if not agree[k]:
            bad |= 1 << j
        if all(agree):
            estar |= 1 << j
        if agree[k]:
            n1 |= 1 << j
        if all(agree[t] for t in range(max(0, k - 1), k + 1)):
            n2 |= 1 << j
        if all(agree[t] for t in range(k + 1) if t != 1):
            n4 |= 1 << j
    return bad, estar, {'N1': n1, 'N2': n2, 'N3': 0, 'N4': n4}


def greatest_ok(R, bad, F):
    if R not in PCSET or (R & bad) != 0:
        return False
    return bool(np.all((F & np.uint64(FULL ^ R)) == 0))


def analyze(vals):
    bad, estar, cands = masks_for(vals)
    F = PCARR[(PCARR & np.uint64(bad)) == 0]
    c2 = greatest_ok(estar, bad, F)
    U = int(np.bitwise_or.reduce(F)) if len(F) else 0
    truth = (BADS & np.uint64(U)) != 0
    est_v = (BADS & np.uint64(estar)) != 0
    c4 = bool(np.array_equal(truth, est_v))
    res = {'F': len(F), 'C2': c2, 'C4': c4, 'cand': {}}
    for name, R in cands.items():
        differs = R != estar
        detected = not greatest_ok(R, bad, F)
        res['cand'][name] = (differs, detected)
    n5 = (BADS & np.uint64(cands['N1'])) != 0
    res['cand']['N5'] = (not np.array_equal(n5, est_v), not np.array_equal(n5, truth))
    return res


class Stratum:
    def __init__(self, name):
        self.name = name; self.n = 0; self.c2f = 0; self.c4f = 0
        self.fdist = Counter(); self.cd = Counter(); self.fails = []; self.defects = []

    def add(self, vals, r):
        self.n += 1; self.fdist[r['F']] += 1
        if not r['C2']: self.c2f += 1; self.fails.append(('C2', vals))
        if not r['C4']: self.c4f += 1; self.fails.append(('C4', vals))
        for k, (d, t) in r['cand'].items():
            self.cd[(k, 'differs')] += d; self.cd[(k, 'detected')] += t
            self.cd[(k, 'detected_and_differs')] += (d and t)
            if d and not t: self.defects.append((k, vals))
            if t and not d: self.cd[(k, 'detected_not_differs')] += 1

    def report(self):
        out = [f"== {self.name}: instances={self.n}  C2 failures={self.c2f}  C4 failures={self.c4f}",
               f"   |F| distribution: {dict(sorted(self.fdist.items()))}"]
        for k in ['N1', 'N2', 'N3', 'N4', 'N5']:
            out.append(f"   {k}: differs={self.cd[(k,'differs')]} detected={self.cd[(k,'detected')]} "
                       f"detected&differs={self.cd[(k,'detected_and_differs')]} "
                       f"undetected-while-differing(defect)={self.cd[(k,'differs')]-self.cd[(k,'detected_and_differs')]} "
                       f"detected-without-differing={self.cd[(k,'detected_not_differs')]}")
        for f in self.fails[:20]: out.append(f"   FAIL {f}")
        for d in self.defects[:20]: out.append(f"   DEFECT {d}")
        return '\n'.join(out)


def main():
    print(f"python {sys.version}"); print(f"platform {platform.platform()}"); print(f"numpy {np.__version__}")
    print(f"Bell per layer {BELL}; product candidates {ncand}; |PC| = {len(PC)} (claimed 7176: "
          f"{'MATCH' if len(PC) == 7176 else 'MISMATCH'})")
    s0 = Stratum('S0 all 2^15 binary single Dv'); s1 = Stratum('S1 binary single Dv with |F|<=9 (exhaustive)')
    for code in range(1 << 15):
        vals = [((code >> i) & 1,) for i in range(15)]
        r = analyze(vals)
        s0.add(vals, r)
        if r['F'] <= 9: s1.add(vals, r)
    print(s1.report()); print(s0.report())
    rng = random.Random(20261021)
    s2all = Stratum('S2 all q')
    for q in (0.1, 0.3, 0.5):
        st = Stratum(f'S2 q={q}')
        for i in range(100):
            if i % 2 == 0:
                vals = [(int(rng.random() < q),) for _ in range(15)]
            else:
                vals = []
                for _ in range(15):
                    b1 = int(rng.random() < q); b2 = int(rng.random() < q); vals.append((b1, b2))
            r = analyze(vals); st.add(vals, r); s2all.add(vals, r)
        print(st.report())
    print(s2all.report())
    named = {
        'constant': [(0,) for h in H],
        'first symbol (eps->-)': [(h[0] if h else '-',) for h in H],
        'first symbol (eps->0)': [(h[0] if h else '0',) for h in H],
        'last symbol (eps->-)': [(h[-1] if h else '-',) for h in H],
        'last symbol (eps->0)': [(h[-1] if h else '0',) for h in H],
    }
    s3 = Stratum('S3 named')
    for nm, vals in named.items():
        r = analyze(vals); s3.add(vals, r)
        print(f"   S3 {nm}: |F|={r['F']} C2={r['C2']} C4={r['C4']} cand(differs,detected)={r['cand']}")
    print(s3.report())
    tot = [s0, s1, s2all, s3]
    print("== OVERALL: C2 failures", sum(s.c2f for s in tot), " C4 failures", sum(s.c4f for s in tot),
          " harness defects", sum(len(s.defects) for s in tot))
    for k in ['N1', 'N2', 'N3', 'N4', 'N5']:
        print(f"   {k} differs on at least one instance overall: {any(s.cd[(k,'differs')] > 0 for s in tot)}")


if __name__ == '__main__':
    main()

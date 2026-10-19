#!/usr/bin/env python3
"""O-10a synthetic checks; implements PROTOCOL.md (sha256 77da84f5…c6ec). Synthetic only. Exact integer arithmetic."""
import itertools, math, random

SEED, N, S = 20261017, 3, (0, 1)

def all_hist(n):
    return [h for k in range(n + 1) for h in itertools.product(S, repeat=k)]

def random_prefix_closed(rnd, n):
    L = {()}
    frontier = [()]
    while frontier:
        h = frontier.pop()
        if len(h) == n:
            continue
        for a in S:
            if rnd.random() < 0.8:
                L.add(h + (a,)); frontier.append(h + (a,))
    return sorted(L, key=lambda h: (len(h), h))

def set_partitions(items):
    if not items:
        yield []; return
    f, rest = items[0], items[1:]
    for p in set_partitions(rest):
        for i in range(len(p)):
            yield p[:i] + [[f] + p[i]] + p[i + 1:]
        yield [[f]] + p

def pc_feasible(L, Dsig):
    """Enumerate prefix-closed length-preserving equivalences pi on L with pi ⊆ D (D-homogeneous blocks).
    Represented as dict history -> class label (labels unique across levels)."""
    levels = {}
    for h in L:
        levels.setdefault(len(h), []).append(h)
    out = []
    def rec(k, lab, nxt):
        if k not in levels:
            out.append(dict(lab)); return
        groups = {}
        for h in levels[k]:
            groups.setdefault(lab[h[:-1]], []).append(h)
        glist = list(groups.values())
        def rec_g(i, lab2, nxt2):
            if i == len(glist):
                rec(k + 1, lab2, nxt2); return
            for p in set_partitions(glist[i]):
                if any(len({Dsig[h] for h in B}) > 1 for B in p):
                    continue
                l3 = dict(lab2); n3 = nxt2
                for B in p:
                    for h in B:
                        l3[h] = n3
                    n3 += 1
                rec_g(i + 1, l3, n3)
        rec_g(0, lab, nxt)
    rec(1, {(): 0}, 1)
    return out

def star(L, Dsig):
    lab = {}
    for h in L:
        lab[h] = tuple(Dsig[h[:t]] for t in range(len(h) + 1))
    return lab

def subset_rel(p, q, L):
    """kernel(p) ⊆ kernel(q): p-equal => q-equal (p finer or equal)."""
    m = {}
    for h in L:
        if m.setdefault(p[h], q[h]) != q[h]:
            return False
    return True

def determines(p, s, L):
    m = {}
    for h in L:
        if m.setdefault(p[h], s[h]) != s[h]:
            return False
    return True

def check_instance(rnd, L):
    nd = rnd.choice((1, 2))
    ds = [{h: rnd.randrange(3) for h in L} for _ in range(nd)]
    Dsig = {h: tuple(d[h] for d in ds) for h in L}
    feas = pc_feasible(L, Dsig)
    st = star(L, Dsig)
    c2 = (any(subset_rel(st, f, L) and subset_rel(f, st, L) for f in feas)
          and all(subset_rel(f, st, L) for f in feas))
    s = {h: rnd.randrange(2) for h in L}
    c4 = (any(not determines(f, s, L) for f in feas)) == (not determines(st, s, L))
    # C1: class-log record of each feasible pi realizes pi (kernel equality)
    c1 = all(subset_rel(f, {h: tuple(f[h[:t]] for t in range(len(h) + 1)) for h in L}, L) and
             subset_rel({h: tuple(f[h[:t]] for t in range(len(h) + 1)) for h in L}, f, L) for f in feas[:50])
    return c1, c2, c4, len(feas)

def letterwise_vs_star(rnd):
    L = all_hist(N)
    ds = [{h: rnd.randrange(3) for h in L}]
    Dsig = {h: tuple(d[h] for d in ds) for h in L}
    def eq(a, b):
        return all(Dsig[h + (a,) + w] == Dsig[h + (b,) + w]
                   for h in L for w in L if len(h) + 1 + len(w) <= N)
    phi = {a: min(b for b in S if eq(a, b)) for a in S}
    letter = {h: tuple(phi[x] for x in h) for h in L}
    st = star(L, Dsig)
    return subset_rel(letter, st, L), not subset_rel(st, letter, L)

def first_letter_instance():
    L = all_hist(N)
    Dsig = {h: (h[0] if h else -1,) for h in L}
    st = star(L, Dsig)
    letter = {h: h for h in L}               # ≈ is the identity for this d
    return subset_rel(letter, st, L) and not subset_rel(st, letter, L)

def c5_projection_instance():
    letters = {"a": (0, 0), "b": (0, 1), "c": (1, 2), "e": (1, 3), "f": (2, 2)}
    d = {k: int(v[0] >= 1) for k, v in letters.items()}
    def kern(fields):
        return {k: tuple(v[i] for i in fields) for k, v in letters.items()}
    feasible = [fs for r in range(3) for fs in itertools.combinations((0, 1), r) if determines(kern(fs), d, list(letters))]
    minimal = [fs for fs in feasible if not any(set(g) < set(fs) for g in feasible)]
    kx, ky = kern((0,)), kern((1,))
    incomparable = not subset_rel(kx, ky, list(letters)) and not subset_rel(ky, kx, list(letters))
    return minimal, incomparable

def c6_storage_instance():
    L = all_hist(N)
    ds = [{h: (len(h) % 3) for h in L}, {h: (sum(h) % 3) for h in L}]
    Dsig = {h: tuple(d[h] for d in ds) for h in L}
    st = star(L, Dsig)
    ident = {h: h for h in L}
    classes = lambda p, k: len({p[h] for h in L if len(h) == k})
    mono = all(classes(st, k) <= classes(ident, k) for k in range(N + 1))
    naive_star_bits_per_step = 2 * math.log2(3)       # log of the decision vector, naive fixed-width
    naive_input_bits_per_step = 1.0
    return subset_rel(ident, st, L), mono, naive_star_bits_per_step > naive_input_bits_per_step

if __name__ == "__main__":
    rnd = random.Random(SEED)
    for kind in ("all", "random prefix-closed"):
        res = []
        for _ in range(300):
            L = all_hist(N) if kind == "all" else random_prefix_closed(rnd, N)
            res.append(check_instance(rnd, L))
        print(f"L={kind}: C1 {sum(r[0] for r in res)}/300  C2 {sum(r[1] for r in res)}/300  "
              f"C4 {sum(r[2] for r in res)}/300  feasible PC per instance: min {min(r[3] for r in res)} max {max(r[3] for r in res)}")
    lw = [letterwise_vs_star(rnd) for _ in range(300)]
    print(f"C3 letter-wise kernel ⊆ ≡*: {sum(a for a, _ in lw)}/300; strict: {sum(b for _, b in lw)}/300; "
          f"first-letter instance strict: {first_letter_instance()}")
    m, inc = c5_projection_instance()
    print(f"C5 minimal feasible field subsets: {m}; their kernels incomparable: {inc}")
    print("C6 (ident ⊆ ≡*, class counts monotone, naive decision log larger than input log):", c6_storage_instance())

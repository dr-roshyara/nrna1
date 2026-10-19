#!/usr/bin/env python3
"""O-6 synthetic checks (D7). Bounded: languages compared on all words of length <= LMAX.
C1: languages whose minimal NFA (1 initial state) has 2 states and that have >=2 non-isomorphic minimal 2-state NFAs.
C2: subset-construction growth for L_n = 'the n-th symbol from the end is a' (NFA n+1 states; minimal DFA states)."""
import itertools

LMAX = 7
S = (0, 1)
WORDS = [w for L in range(LMAX + 1) for w in itertools.product(S, repeat=L)]

def lang(n, delta, init, fin):
    acc = []
    for w in WORDS:
        cur = {init}
        for a in w:
            cur = {t for s in cur for t in delta[s][a]}
        acc.append(bool(cur & fin))
    return tuple(acc)

def nfas(n):
    subsets = [frozenset(c) for r in range(n + 1) for c in itertools.combinations(range(n), r)]
    for trans in itertools.product(subsets, repeat=n * 2):
        delta = [trans[s * 2:(s + 1) * 2] for s in range(n)]
        for fr in range(n + 1):
            for fin in itertools.combinations(range(n), fr):
                yield delta, frozenset(fin)

def canon(n, delta, fin):
    best = None
    for perm in itertools.permutations(range(n)):
        if perm[0] != 0:
            continue
        inv = {perm[i]: i for i in range(n)}
        d2 = tuple(tuple(frozenset(perm[t] for t in delta[inv[s]][a]) for a in S) for s in range(n))
        key = (tuple(tuple(tuple(sorted(x)) for x in row) for row in d2), tuple(sorted(perm[f] for f in fin)))
        best = key if best is None or key < best else best
    return best

one = {lang(1, d, 0, f) for d, f in nfas(1)}
by_lang = {}
for d, f in nfas(2):
    L = lang(2, d, 0, f)
    if L in one:
        continue
    # trim: both states reachable and co-reachable is implied by minimality; keep all, canonicalize
    by_lang.setdefault(L, set()).add(canon(2, d, f))
multi = {L: v for L, v in by_lang.items() if len(v) >= 2}
print(f"C1: languages with minimal NFA size 2 (bounded, LMAX={LMAX}): {len(by_lang)}; with >=2 non-isomorphic minimal NFAs: {len(multi)}")
L, v = next(iter(sorted(multi.items(), key=lambda kv: len(kv[1]))))
print("    example accepted words up to len 3:", [w for w, b in zip(WORDS, L) if b and len(w) <= 3])
for c in sorted(v)[:3]:
    print("    NFA:", c)

def min_dfa_states(n):
    # L_n: n-th from end is 0 ('a'); DFA state = last n symbols window; minimal = 2^n (computed by Moore refinement)
    states = list(itertools.product(S, repeat=n))
    tab = {s: {a: s[1:] + (a,) for a in S} for s in states}
    out = {s: int(s[0] == 0) for s in states}
    blk = {s: out[s] for s in states}
    while True:
        sig = {s: (blk[s], blk[tab[s][0]], blk[tab[s][1]]) for s in states}
        ids = {}; nb = {s: ids.setdefault(sig[s], len(ids)) for s in states}
        if len(set(nb.values())) == len(set(blk.values())):
            return len(set(nb.values()))
        blk = nb
print("C2: n, NFA states, minimal DFA states (window model):", [(n, n + 1, min_dfa_states(n)) for n in range(1, 7)])

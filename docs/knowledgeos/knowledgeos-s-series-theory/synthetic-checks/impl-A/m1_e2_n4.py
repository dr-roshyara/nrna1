#!/usr/bin/env python3
"""E2 extension: are there maximal cap-admissible right congruences with 4 states? Synthetic only."""
import sys
sys.path.insert(0, ".")
from m1_checks import channels, partitions

K = 3

def canon(n):
    """Generate BFS-canonical trim complete DFAs with n states over K letters."""
    table = [[None] * K for _ in range(n)]
    def rec(pos, used):
        if pos == n * K:
            if used == n:
                yield [tuple(r) for r in table]
            return
        s, a = divmod(pos, K)
        if s >= used:          # state s not yet discovered -> not BFS-reachable in order
            return
        for t in range(min(used + 1, n)):
            table[s][a] = t
            yield from rec(pos + 1, max(used, t + 1))
        table[s][a] = None
    yield from rec(0, 1)

def admissible(t):
    return all(len(c) <= 2 for c in channels(t, K).values())

def quotient(t, p):
    b = {s: i for i, B in enumerate(p) for s in B}
    for B in p:
        for a in range(K):
            if len({b[t[s][a]] for s in B}) != 1:
                return None
    q = [None] * len(p)
    for B in p:
        q[b[B[0]]] = tuple(b[t[B[0]][a]] for a in range(K))
    # renumber BFS from block of start state 0
    start = b[0]; order = [start]; i = 0
    while i < len(order):
        for x in q[order[i]]:
            if x not in order:
                order.append(x)
        i += 1
    m = {o: j for j, o in enumerate(order)}
    return [tuple(m[x] for x in q[o]) for o in order]

total = adm = maximal = 0
examples = []
for t in canon(4):
    total += 1
    if not admissible(t):
        continue
    adm += 1
    proper = [p for p in partitions(list(range(4))) if 1 < len(p) < 4 or len(p) == 1]
    if not any((q := quotient(t, p)) is not None and admissible(q) for p in proper):
        maximal += 1
        if len(examples) < 5:
            examples.append((t, {s: sorted(c) for s, c in channels(t, K).items()}))
print(f"4-state canonical trim automata: {total} · cap-admissible: {adm} · maximal (no admissible proper quotient): {maximal}")
for e in examples:
    print("   ", e)

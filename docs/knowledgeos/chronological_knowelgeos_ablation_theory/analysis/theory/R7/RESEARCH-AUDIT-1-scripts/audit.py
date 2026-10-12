#!/usr/bin/env python3
"""Research audit 1: crossing / cohesion computations over T2 r1.4 rows.

Encoding: each T2 row -> set of T1 concepts its statement reads, transcribed from
T3 r1.9 section 1a crossing table ("reads on CB1" / "reads on CB23" columns),
mapped to concepts by T3 rule 2 (coordinate -> concept). C1 (actor) is tracked
separately with an S'' location per row. CLASS = union of R0 clause reads (T3 rule 3).
"""
import itertools, random, sys
import numpy as np

# --- clause reads (Def 3.1, R0), concepts only; actor handled via ACT ---
DCHG = {7, 6, 8, 3, 12, 5, 2}          # + actor (alpha(a)) located on norm side
DST = {8, 12, 10}                       # Adopted named (not read through)
DREC = {17, 13, 8}
BCHG = {7, 6, 8, 3, 12, 5, 19, 20}
BCONF = {19, 21, 6, 20, 4, 3}
BRECW = {17, 13, 19}
CLASS = DCHG | DST | DREC | BCHG | BCONF | BRECW

# row: (tier, concept set excluding C1, C1 location or None)
# C1 location for S'': 'N' = norm/work side (actor of act / work / Cap holder),
# 'R' = records side (keeper), 'U' = unlocated (no side), 'B' = located on both.
ROWS = {
 'R1': ('A', {6, 12, 3, 5, 8}, None),
 'R2': ('A', {18, 17, 8}, None),
 'R3': ('A', {18, 14, 11, 8}, None),
 'R4': ('A', {9, 8}, None),
 'R5': ('A', {8, 9, 11, 3, 5, 4, 10, 12}, 'N'),
 'R6': ('A', {19, 3, 20}, 'N'),
 'R7': ('A', {13, 14, 15}, None),
 'R8': ('A', {13, 16}, 'R'),
 'R9': ('A', {17, 18, 13, 8, 9, 19}, None),
 'R10': ('A', {2}, 'N'),
 'R11': ('A', {6, 8}, None),
 'R12': ('A', {8} | CLASS, 'N'),          # class rule
 'R13': ('A', CLASS, 'N'),
 'R14': ('A', {14, 12, 11}, None),
 'R15': ('A', {14, 11, 12, 20}, None),
 'R16': ('A', {17, 13, 8, 19, 23}, None),
 'R17': ('A', {6, 8, 19}, None),
 'R18': ('A', {6, 20, 3, 4, 19, 21}, None),
 'R19': ('A', {10, 2, 11, 9, 8}, 'N'),
 'R20': ('A', {6, 7}, None),
 'R21': ('A', {15, 6, 12}, None),
 'R22': ('A', {13, 8, 19}, 'U'),
 'R23': ('A', {13, 17, 18, 16, 6, 12, 11, 2, 8, 19}, 'U'),
 'R24': ('A', {17, 13, 18, 8, 27}, None),
 # T-B
 'R25': ('B', DCHG, 'N'),
 'R26': ('B', DCHG - {2}, None),
 'R27': ('B', DST, None),
 'R28': ('B', DST, None),
 'R29': ('B', DREC, None),
 'R30': ('B', BCHG, None),
 'R31': ('B', BCHG, None),
 'R32': ('B', BCONF, None),
 'R33': ('B', {19, 21}, None),
 'R34': ('B', {19, 21}, None),
 'R35': ('B', BRECW, None),
 'R36': ('B', DREC | BRECW, None),
 'R37': ('B', {7, 6, 19, 20, 8, 12}, None),
 'R38': ('B', DCHG, 'N'),
 'R39': ('B', {13, 18, 8}, None),
 'R79': ('B', {8}, None),
 'R80': ('B', set(), None),
 'R83': ('B', {17, 13, 8, 19}, None),
 # T-C
 'R40': ('C', {14, 12}, None),
 'R41': ('C', {17, 13, 8, 19}, None),
 'R42': ('C', {14, 11}, None),
 'R43': ('C', {14, 12, 11}, None),
 'R44': ('C', {13, 18, 8}, None),
 'R45': ('C', {18, 13, 8}, None),
 'R46': ('C', {15, 6}, None),
 'R47': ('C', {6, 8}, None),
 'R48': ('C', {6, 19}, None),
 'R49': ('C', {23, 13, 8, 19}, None),
 'R50': ('C', {19, 7}, None),
 'R51': ('C', CLASS, 'N'),
 'R52': ('C', {15, 18, 8}, None),
 'R53': ('C', set(), None),
 'R54': ('C', {5}, None),
 'R55': ('C', {8, 9}, None),
 'R56': ('C', {2}, None),
 'R57': ('C', set(), None),
 'R58': ('C', {5, 8}, None),
 'R59': ('C', {13, 28}, None),
 'R60': ('C', {2}, None),
 'R61': ('C', {8, 10, 2, 7}, None),
 'R62': ('C', {13, 25, 26}, None),
 'R63': ('C', {9}, 'N'),
 'R81': ('C', {19, 7}, None),
 # T-D
 'R64': ('D', {5}, None),
 'R65': ('D', {8, 9}, None),
 'R66': ('D', {13, 16, 17, 18, 6, 12, 2, 8, 19, 26} | CLASS, 'N'),
 'R67': ('D', CLASS | {19, 20, 3, 4, 26}, 'N'),
 'R68': ('D', CLASS | {6, 10, 2, 26}, 'N'),
 'R69': ('D', CLASS | {6, 19, 20, 26}, 'N'),
 'R70': ('D', {18, 16, 13, 8, 25}, 'B'),
 'R71': ('D', DCHG | BCHG, 'N'),
 'R72': ('D', {16, 18, 17, 6, 12, 11, 2, 20}, 'N'),
 'R73': ('D', {7, 3}, None),
 'R74': ('D', {28}, None),
 'R75': ('D', set(), None),
 'R76': ('D', {2}, 'U'),
 'R77': ('D', {13, 8}, None),
 'R78': ('D', {13, 18, 8}, None),
 'R82': ('D', {13, 17, 18, 6, 12, 19} | CLASS, None),
}
assert len(ROWS) == 83, len(ROWS)

UNPLACED = {22, 25, 26, 28}
RECORDS = {13, 14, 15, 16, 17, 18, 23, 24, 27}
NORMWORK = {2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 19, 20, 21}

CUTS = {
    'P-c (records | norm+work)': [RECORDS, NORMWORK],
    'P-a (records | acts+sigma | work)': [RECORDS, {2,3,4,5,6,7,8,9,10,11,12}, {19,20,21}],
    'P-b (records | acts | sigma+work)': [RECORDS, {2,8,9,10,11,12}, {3,4,5,6,7,19,20,21}],
    'P-d (observed | unobserved)': [{13,14,15,24}, (RECORDS | NORMWORK) - {13,14,15,24}],
}

def side_of(c, cut):
    for i, s in enumerate(cut):
        if c in s:
            return i
    return None

def sides_read(row, cut, rule, c1_in_cut=None):
    """Return set of side indices the row reads.
    rule: 'S' (C1 on all sides), "S'" (C1 on none), "S''" (located),
    'P' (C1 placed as an ordinary concept on side c1_in_cut)."""
    tier, cs, loc = ROWS[row]
    sides = {side_of(c, cut) for c in cs} - {None}
    if loc is not None:
        if rule == 'S':
            sides |= set(range(len(cut)))
        elif rule == "S''":
            if loc == 'N':
                # norm-side: the side holding Cap (C2)
                sides.add(side_of(2, cut))
            elif loc == 'R':
                sides.add(side_of(16, cut))
            elif loc == 'B':
                sides |= {side_of(2, cut), side_of(16, cut)}
        elif rule == 'P':
            sides.add(c1_in_cut)
    return sides - {None}

def count(cut, rule, tiers='ABCD', c1_in_cut=None):
    cross = internal = none = 0
    xs = []
    for r, (t, cs, loc) in ROWS.items():
        if t not in tiers:
            continue
        s = sides_read(r, cut, rule, c1_in_cut)
        if len(s) >= 2:
            cross += 1; xs.append(r)
        elif len(s) == 1:
            internal += 1
        else:
            none += 1
    return cross, internal, none, xs

def main():
    out = []
    p = lambda *a: out.append(' '.join(str(x) for x in a))
    p('== Validation against T3 r1.7/r1.9 counts (P-c, all 83 rows) ==')
    for rule in ['S', "S'", "S''"]:
        c, i, n, xs = count(CUTS['P-c (records | norm+work)'], rule)
        p(f'  {rule:4s}: crossing {c}, internal {i}, none {n}')
    p('  (T3: S 46 crossing; S\'/S\'\' 36 crossing; internal+CB4-touch+none = 37/47)')

    p('\n== Crossing counts by cut and tier set ==')
    tiersets = {'T-A (24)': 'A', 'T-A+T-B (42)': 'AB', 'all (83)': 'ABCD'}
    for name, cut in CUTS.items():
        for rule in ['S', "S'", "S''"]:
            if name.startswith('P-d'):
                if rule != 'S':
                    continue
                cut_ = [cut[0], cut[1] | {1}]
                row = [count(cut_, 'P', ts, c1_in_cut=1)[0] for ts in tiersets.values()]
                p(f'  {name:36s} C1 placed (unobserved)  ' + '  '.join(f'{k}: {v}' for k, v in zip(tiersets, row)))
            else:
                row = [count(cut, rule, ts)[0] for ts in tiersets.values()]
                p(f'  {name:36s} {rule:4s}  ' + '  '.join(f'{k}: {v}' for k, v in zip(tiersets, row)))

    # Invariance across S / S' / S''
    p('\n== Rows whose P-c crossing status depends on the spanning rule ==')
    cut = CUTS['P-c (records | norm+work)']
    dep = []
    for r in ROWS:
        st = tuple(len(sides_read(r, cut, rl)) >= 2 for rl in ['S', "S'", "S''"])
        if len(set(st)) > 1:
            dep.append((r, ROWS[r][0], st))
    for d in dep:
        p('  ', d)
    p('  count:', len(dep), 'of 83;  S\' vs S\'\' differ on',
      sum(1 for r in ROWS if (len(sides_read(r, cut, "S'")) >= 2) != (len(sides_read(r, cut, "S''")) >= 2)), 'rows')

    # Null model: random 2-way partitions of the 23 placed concepts with the same sizes (9 | 14)
    p('\n== Null model: random 9|14 partitions of the 23 P-c-placed concepts (C1 excluded = S\') ==')
    placed = sorted(RECORDS | NORMWORK)
    rng = random.Random(20261012)
    for ts_name, ts in tiersets.items():
        obs = count(cut, "S'", ts)[0]
        samples = []
        for _ in range(20000):
            sh = placed[:]; rng.shuffle(sh)
            a, b = set(sh[:9]), set(sh[9:])
            samples.append(count([a, b], "S'", ts)[0])
        arr = np.array(samples)
        pct = (arr <= obs).mean()
        p(f'  {ts_name:14s} P-c={obs:3d}  null mean={arr.mean():5.1f} sd={arr.std():4.1f} min={arr.min()}  P(null<=P-c)={pct:.4f}')

    # Exhaustive search of all 2-way cuts of the 23 placed concepts (C1 excluded), per tier set
    p('\n== Exhaustive 2-way cuts of the 23 placed concepts (C1 excluded): minimum crossings by smaller-side size ==')
    idx = {c: k for k, c in enumerate(placed)}
    n = len(placed)
    for ts_name, ts in tiersets.items():
        masks = []
        for r, (t, cs, loc) in ROWS.items():
            if t in ts:
                m = 0
                for c in cs:
                    if c in idx:
                        m |= 1 << idx[c]
                masks.append(m)
        masks = np.array(masks, dtype=np.int64)
        full = (1 << n) - 1
        # enumerate A containing concept 0 is not necessary; enumerate all A with bit n-1 fixed 0 to halve
        A = np.arange(0, 1 << (n - 1), dtype=np.int64)
        sizes = np.zeros(A.shape, dtype=np.int64)
        tmp = A.copy()
        while tmp.any():
            sizes += tmp & 1; tmp >>= 1
        cross = np.zeros(A.shape, dtype=np.int64)
        for m in masks:
            if m == 0: continue
            cross += (((A & m) != 0) & (((~A) & full & m) != 0)).astype(np.int64)
        small = np.minimum(sizes, n - sizes)
        res = []
        for k in range(1, n // 2 + 1):
            sel = small == k
            if sel.any():
                j = np.argmin(np.where(sel, cross, 10**9))
                res.append((k, int(cross[j]), int(A[j])))
        pc_mask = sum(1 << idx[c] for c in RECORDS)
        pc_cross = int(sum(1 for m in masks if (m & pc_mask) and (m & ~pc_mask & full)))
        p(f'  {ts_name}: P-c (9|14) crossings = {pc_cross}')
        for k, cmin, a in res:
            members = [placed[i] for i in range(n) if (a >> i) & 1]
            other = [placed[i] for i in range(n) if not (a >> i) & 1]
            sm = members if len(members) <= len(other) else other
            p(f'     smaller side {k:2d}: min crossings {cmin:3d}   e.g. smaller side = C{sorted(sm)}')
        # rank of P-c among 9|14 cuts
        sel = small == 9
        rank = int((cross[sel] < pc_cross).sum())
        p(f'     P-c rank among {int(sel.sum())} cuts of size 9|14: {rank} cuts have strictly fewer crossings')

    # Modularity (clique expansion, weight 1/(k-1) per pair), C1 excluded
    p('\n== Modularity Q of the concept co-occurrence graph (clique expansion), C1 excluded ==')
    for ts_name, ts in tiersets.items():
        W = np.zeros((29, 29))
        for r, (t, cs, loc) in ROWS.items():
            if t not in ts: continue
            cc = sorted(c for c in cs if c in idx)
            if len(cc) < 2: continue
            w = 1.0 / (len(cc) - 1)
            for a, b in itertools.combinations(cc, 2):
                W[a, b] += w; W[b, a] += w
        deg = W.sum(1); m2 = W.sum()
        def Q(parts):
            q = 0.0
            for s in parts:
                s = list(s)
                q += W[np.ix_(s, s)].sum() / m2 - (deg[s].sum() / m2) ** 2
            return q
        line = []
        for name, cutp in CUTS.items():
            parts = [set(s) - {1} for s in cutp]
            line.append(f'{name.split(" ")[0]}={Q(parts):.3f}')
        line.append(f'no-cut=0.000')
        # random 9|14
        qs = []
        for _ in range(5000):
            sh = placed[:]; rng.shuffle(sh)
            qs.append(Q([set(sh[:9]), set(sh[9:])]))
        qs = np.array(qs)
        line.append(f'random9|14 mean={qs.mean():.3f} max={qs.max():.3f}')
        p(f'  {ts_name}: ' + '  '.join(line))

    # Internal density vs cross density (P-c), T-A only and T-A+T-B, C1 excluded
    p('\n== P-c cohesion/coupling: pair co-reads within vs across (C1 excluded) ==')
    for ts_name, ts in tiersets.items():
        within = {0: 0, 1: 0}; across = 0
        for r, (t, cs, loc) in ROWS.items():
            if t not in ts: continue
            cc = [c for c in cs if c in idx]
            for a, b in itertools.combinations(sorted(cc), 2):
                sa, sb = side_of(a, cut), side_of(b, cut)
                if sa == sb: within[sa] += 1
                else: across += 1
        pr = {0: 9 * 8 / 2, 1: 14 * 13 / 2}; pa = 9 * 14
        p(f'  {ts_name}: within CB1 {within[0]} co-reads over {pr[0]:.0f} pairs (density {within[0]/pr[0]:.2f}); '
          f'within CB23 {within[1]} over {pr[1]:.0f} ({within[1]/pr[1]:.2f}); across {across} over {pa} ({across/pa:.2f})')

    # Q1: coordinate-level record-coordinate reads of R0 dyn clauses
    p('\n== Q1: record coordinates (r,c,k,Ref,con = C14,C15,C16,C17,C18; records C13) read by each R0 clause ==')
    REC = {13, 14, 15, 16, 17, 18}
    for nm, cs in [('D-chg', DCHG), ('D-st', DST), ('D-rec', DREC), ('B-chg', BCHG), ('B-conf', BCONF), ('B-recW', BRECW),
                   ('R-i "E!=0"', {8}), ('R-e D-chg-Cap', DCHG - {2})]:
        p(f'  {nm:14s} record coords read: {sorted(cs & REC) or "none"}')
    print('\n'.join(out))

if __name__ == '__main__':
    main()

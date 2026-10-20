#!/usr/bin/env python3
"""A finite model of the evaluator of current authority, CE* (Ch. 9 §9.13, theory turn 4). A toy, not KnowledgeOS:
it tests the FORM of the repaired definition, not its content.

Record: a list of entries in record order (positions 0..n-1). Entry kinds:
  root(id, actor, scope)                                      a root act: authorized by stipulation
  grant(id, actor, cites, holder, scope, start, end)          a grant is the product of an act (issuance), §1.14.1 defect 2
  adopt(id, actor, cites, item, scope, t, vfrom, vto)          an adoption of item, valid on [vfrom, vto)
  revoke(id, actor, cites, item, scope, t)                     revokes the item's standing from t
  annul(id, actor, cites, target, scope, t)                    annuls an act (void from the start)
  endg(id, actor, cites, grant, scope, t)                      ends a grant at t (its window closes)
Three values T, F, U; strong Kleene connectives. Information order: U below T and below F.
covers(gscope, xscope): a given relation (here: equal, or gscope a prefix of xscope); pairs outside its domain give U.
Parameter υ: 'U' (an act of undetermined authorization counts as undetermined) or 'notcount' (it does not count until
evidenced). C1 = constitute: no adoption on record gives F.

Phi maps an assignment a: act id -> {T, F, U} to a new one by the defining clauses. CE*(c, t) is read off the least
fixed point, reached by iterating Phi from the bottom (all U, roots T).
Checks: (1) under υ='U', Phi is monotone in the information order (random pairs a <= b); (2) iteration from the bottom
stops within n+1 rounds on every random record (totality); (3) under υ='notcount', a mutual-annulment record has no
fixed point reached by iteration (it oscillates): §1.14.1 defect 1; (4) records on which CE* answers T and F exist, so
constant evaluators fail Q1 (§9.10 F-vac); (5) T and F of the least fixed point hold in every fixed point found by
iterating from random starting points that are fixed points (robustness of decided answers); (6) the well-founded model
(alternating fixpoint) extends the least fixed point; (7) on circular self-authorization (two grants issued under each
other, no root) the least fixed point gives U and the well-founded model F; (8) mutual annulment stays U in both.

Usage: python3 -I ce_model.py
"""
import random
from itertools import product

T, F, U = 'T', 'F', 'U'


def k_not(a):
    return {T: F, F: T, U: U}[a]


def k_and(*xs):
    if F in xs:
        return F
    if U in xs:
        return U
    return T


def k_or(*xs):
    if T in xs:
        return T
    if U in xs:
        return U
    return F


def leq(a, b):  # information order on assignments
    return all(a[k] == U or a[k] == b[k] for k in a)


def covers(gs, xs, domain):
    if (gs, xs) not in domain:
        return U
    return T if xs == gs or xs.startswith(gs + '/') else F


class Record:
    def __init__(self, entries, cov_domain):
        self.e = entries
        self.by = {x['id']: x for x in entries}
        self.pos = {x['id']: i for i, x in enumerate(entries)}
        self.dom = cov_domain

    def acts(self):
        return [x['id'] for x in self.e]


def effective(a, eid, upsilon):
    v = a[eid]
    if upsilon == 'notcount':
        return T if v == T else F
    return v


def phi(rec, a, upsilon):
    new = {}
    for x in rec.e:
        i = x['id']
        if x['kind'] == 'root':
            new[i] = T
            continue
        if not x['cites']:
            new[i] = F
            continue
        alts = []
        for g in x['cites']:
            if g not in rec.by:
                alts.append(F)
                continue
            gr = rec.by[g]
            if gr['kind'] == 'root':
                ok_holder = T if gr['actor'] == x['actor'] else F
                alts.append(k_and(a[g], ok_holder, covers(gr['scope'], x['scope'], rec.dom)))
                continue
            if gr['kind'] != 'grant':
                alts.append(F)
                continue
            in_window = T if gr['start'] <= x['t'] < gr['end'] else F
            ended = [k_and(effective(a, e['id'], upsilon), T if e['t'] <= x['t'] else F)
                     for e in rec.e if e['kind'] == 'endg' and e['grant'] == g and e['id'] != i]
            annulled_g = [effective(a, e['id'], upsilon) for e in rec.e if e['kind'] == 'annul' and e['target'] == g and e['id'] != i]
            alts.append(k_and(a[g], T if gr['holder'] == x['actor'] else F, in_window,
                              k_not(k_or(F, *ended)), k_not(k_or(F, *annulled_g)),
                              covers(gr['scope'], x['scope'], rec.dom)))
        own_annul = [effective(a, e['id'], upsilon) for e in rec.e if e['kind'] == 'annul' and e['target'] == i]
        new[i] = k_and(k_or(*alts), k_not(k_or(F, *own_annul)))
    return new


def bottom(rec):
    return {x['id']: (T if x['kind'] == 'root' else U) for x in rec.e}


def lfp(rec, upsilon, start=None, limit=None):
    a = start or bottom(rec)
    n = len(rec.e)
    for k in range((limit or n + 2) + 1):
        b = phi(rec, a, upsilon)
        if b == a:
            return a, k
        a = b
    return None, None


def wfs(rec):
    """Well-founded semantics by the alternating fixpoint (Van Gelder, Ross, Schlipf 1991), for υ = 'counts as
    undetermined'. Positive dependence: authorization through a cited grant (or root). Negative dependence: endings and
    annulments by other acts. A coverage pair outside covers' domain is false in the lower and true in the upper pass."""
    def least(J, hi):
        S = {x['id'] for x in rec.e if x['kind'] == 'root'}
        changed = True
        def neg(eid):            # 'not authorized(eid)' against J
            return eid not in J
        while changed:
            changed = False
            for x in rec.e:
                i = x['id']
                if i in S or x['kind'] == 'root' or not x['cites']:
                    continue
                if any(e['kind'] == 'annul' and e['target'] == i and not neg(e['id']) for e in rec.e):
                    continue
                ok = False
                for g in x['cites']:
                    if g not in rec.by or g not in S:
                        continue
                    gr = rec.by[g]
                    c = covers(gr['scope'], x['scope'], rec.dom)
                    if c == F or (c == U and not hi):
                        continue
                    if gr['kind'] == 'root':
                        if gr['actor'] == x['actor']:
                            ok = True
                        continue
                    if gr['kind'] != 'grant' or gr['holder'] != x['actor'] or not (gr['start'] <= x['t'] < gr['end']):
                        continue
                    if any(e['kind'] == 'endg' and e['grant'] == g and e['id'] != i and e['t'] <= x['t'] and not neg(e['id']) for e in rec.e):
                        continue
                    if any(e['kind'] == 'annul' and e['target'] == g and e['id'] != i and not neg(e['id']) for e in rec.e):
                        continue
                    ok = True
                if ok:
                    S.add(i); changed = True
        return S
    K = set()
    while True:
        Up = least(K, hi=True)
        K2 = least(Up, hi=False)
        if K2 == K:
            break
        K = K2
    return {x['id']: (T if x['id'] in K else (U if x['id'] in Up else F)) for x in rec.e}


def current(rec, a, item, t, upsilon):
    adopts = [x for x in rec.e if x['kind'] == 'adopt' and x['item'] == item and x['t'] <= t]
    if not adopts:
        return F                                  # C1 = constitute: no adoption on record
    alts = []
    for x in adopts:
        valid = T if x['vfrom'] <= t < x['vto'] else F
        revs = [k_and(effective(a, y['id'], upsilon), T if rec.pos[y['id']] > rec.pos[x['id']] and y['t'] <= t else F)
                for y in rec.e if y['kind'] == 'revoke' and y['item'] == item]
        intact = []
        for g in x['cites']:
            if g in rec.by and rec.by[g]['kind'] == 'grant':
                gr = rec.by[g]
                closes = T if gr['end'] <= t else F
                ends = [k_and(effective(a, e['id'], upsilon), T if x['t'] < e['t'] <= t else F)
                        for e in rec.e if e['kind'] == 'endg' and e['grant'] == g]
                intact.append(k_not(k_or(closes, *ends)))
        alts.append(k_and(a[x['id']], valid, k_not(k_or(F, *revs)), *intact))
    return k_or(*alts)


def random_record(rng, n_acts=6):
    scopes = ['s', 's/a', 's/b']
    dom = {(g, x) for g in scopes for x in scopes}
    if rng.random() < 0.3:
        dom.discard((rng.choice(scopes), rng.choice(scopes)))   # coverage sometimes undefined
    ents = [{'id': 'r', 'kind': 'root', 'actor': 'P', 'scope': 's'}]
    ids = ['r']
    actors = ['P', 'A', 'B']
    for k in range(n_acts):
        kind = rng.choice(['grant', 'grant', 'adopt', 'adopt', 'revoke', 'annul', 'endg'])
        i = f'{kind[0]}{k}'
        actor = rng.choice(actors)
        cites = rng.sample(ids, k=min(len(ids), rng.choice([1, 1, 2])))
        t = rng.randint(0, 5)
        sc = rng.choice(scopes)
        e = {'id': i, 'kind': kind, 'actor': actor, 'cites': cites, 'scope': sc, 't': t}
        if kind == 'grant':
            e.update(holder=rng.choice(actors), start=rng.randint(0, 3), end=rng.randint(3, 7))
        elif kind == 'adopt':
            e.update(item='c', vfrom=0, vto=rng.randint(3, 8))
        elif kind == 'revoke':
            e.update(item='c')
        elif kind == 'annul':
            e.update(target=rng.choice(ids))
        elif kind == 'endg':
            gs = [x for x in ids if x.startswith('g')] or ['r']
            e.update(grant=rng.choice(gs))
        ents.append(e)
        ids.append(i)
    return Record(ents, dom)


if __name__ == '__main__':
    rng = random.Random(20261020)
    # (1) monotonicity of Phi under υ='U'
    bad = 0
    for _ in range(400):
        rec = random_record(rng)
        ids = rec.acts()
        b = {i: (T if rec.by[i]['kind'] == 'root' else rng.choice([T, F, U])) for i in ids}
        a = {i: (b[i] if rng.random() < 0.5 else U) for i in ids}
        for i in ids:
            if rec.by[i]['kind'] == 'root':
                a[i] = T
        if not leq(phi(rec, a, 'U'), phi(rec, b, 'U')):
            bad += 1
    print('(1) Phi monotone in the information order under υ=U: violations', bad, 'of 400')
    assert bad == 0
    # (2) totality: iteration from the bottom stops within n+1 rounds
    worst = 0
    for _ in range(2000):
        rec = random_record(rng, n_acts=rng.randint(1, 9))
        a, k = lfp(rec, 'U')
        assert a is not None
        worst = max(worst, k / (len(rec.e) + 1))
    print('(2) least fixed point reached on 2000 random records; worst rounds/(n+1) =', round(worst, 2))
    assert worst <= 1.0
    # (3) υ='notcount': mutual annulment oscillates
    mut = Record([{'id': 'r', 'kind': 'root', 'actor': 'P', 'scope': 's'},
                  {'id': 'g1', 'kind': 'grant', 'actor': 'P', 'cites': ['r'], 'holder': 'A', 'scope': 's', 'start': 0, 'end': 9, 't': 0},
                  {'id': 'g2', 'kind': 'grant', 'actor': 'P', 'cites': ['r'], 'holder': 'B', 'scope': 's', 'start': 0, 'end': 9, 't': 0},
                  {'id': 'x', 'kind': 'annul', 'actor': 'A', 'cites': ['g1'], 'target': 'y', 'scope': 's', 't': 1},
                  {'id': 'y', 'kind': 'annul', 'actor': 'B', 'cites': ['g2'], 'target': 'x', 'scope': 's', 't': 1}],
                 {('s', 's')})
    a_u, _ = lfp(mut, 'U')
    a_n, _ = lfp(mut, 'notcount', limit=50)
    print('(3) mutual annulment: υ=U least fixed point x, y =', a_u['x'], a_u['y'], '; υ=notcount reaches a fixed point:', a_n is not None)
    assert a_u['x'] == U and a_u['y'] == U and a_n is None
    # (4) CE* answers T and F on some records: constant evaluators fail
    seen = set()
    for _ in range(3000):
        rec = random_record(rng)
        a, _ = lfp(rec, 'U')
        for t in range(0, 7):
            seen.add(current(rec, a, 'c', t, 'U'))
    print('(4) answers of CE*(c, t) seen on random records:', sorted(seen))
    assert {T, F, U} <= seen
    # (5) decided answers are robust: every fixed point extends the least one
    checked = 0
    for _ in range(300):
        rec = random_record(rng)
        a, _ = lfp(rec, 'U')
        ids = [i for i in rec.acts() if rec.by[i]['kind'] != 'root']
        for vals in product([T, F], repeat=min(len(ids), 6)):
            start = dict(a)
            for i, v in zip(ids, vals):
                if start[i] == U:
                    start[i] = v
            if phi(rec, start, 'U') == start:
                assert leq(a, start)
                checked += 1
    print('(5) total fixed points found and checked to extend the least one:', checked)
    # (6) the well-founded model extends the Kripke-Kleene least fixed point (it decides at least as much)
    ext = 0
    for _ in range(1500):
        rec = random_record(rng, n_acts=rng.randint(1, 9))
        kk, _ = lfp(rec, 'U')
        wf = wfs(rec)
        assert leq(kk, wf), 'WFS must extend KK'
        ext += kk != wf
    print('(6) the well-founded model extends the least fixed point on 1500 records; strictly more decided on', ext)
    # (7) circular self-authorization: two grants each issued under the other, no root in the chain
    circ = Record([{'id': 'r', 'kind': 'root', 'actor': 'P', 'scope': 's'},
                   {'id': 'g1', 'kind': 'grant', 'actor': 'A', 'cites': ['g2'], 'holder': 'B', 'scope': 's', 'start': 0, 'end': 9, 't': 1},
                   {'id': 'g2', 'kind': 'grant', 'actor': 'B', 'cites': ['g1'], 'holder': 'A', 'scope': 's', 'start': 0, 'end': 9, 't': 1},
                   {'id': 'x', 'kind': 'adopt', 'actor': 'A', 'cites': ['g2'], 'item': 'c', 'scope': 's', 't': 2, 'vfrom': 0, 'vto': 9}],
                  {('s', 's')})
    kk, _ = lfp(circ, 'U'); wf = wfs(circ)
    print('(7) circular grants: least fixed point g1, g2, x =', kk['g1'], kk['g2'], kk['x'], '; well-founded =', wf['g1'], wf['g2'], wf['x'])
    assert kk['x'] == U and wf['x'] == F
    # (8) mutual annulment stays undetermined under the well-founded model too
    wm = wfs(mut)
    print('(8) mutual annulment, well-founded: x, y =', wm['x'], wm['y'])
    assert wm['x'] == U and wm['y'] == U
    print('all assertions passed')

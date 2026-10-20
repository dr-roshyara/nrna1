#!/usr/bin/env python3
"""A finite model of the evaluator of current authority, CE* (Ch. 9 §9.13, theory turn 4). A toy, not KnowledgeOS:
it tests the FORM of the repaired definition, not its content. Version 3 (turn-4 decisions, applied in turn 5).

Record: a list of entries in record order (positions 0..n-1). Entry kinds admitted in D (§9.13.2):
  root(id, actor, scope, t)                                    a root act: authorized by stipulation
  grant(id, actor, cites, holder, scope, t, start, end)        a grant is the product of an act (issuance)
  adopt(id, actor, cites, item, scope, t, vfrom, vto)          an adoption of item, valid on [vfrom, vto)
  revoke(id, actor, cites, item, scope, t)                     revokes the item's standing from t
  annul(id, actor, cites, target, scope, t)                    annuls an act (void from the start, at any position)
  endg(id, actor, cites, grant, scope, t)                      ends a grant (its window closes)
Every entry also carries 'ok' (the integrity check passed; default True). A record with an entry that fails it, a
missing field, or another kind (e.g. supersession) is outside D: CE* refuses ('undefined', with the reason).
Order of acts: the key (t, position); an ending counts for act x only if its key is below x's key (ties by position).
Three values T, F, U; strong Kleene connectives. Information order: U below T and below F.
covers(gscope, xscope): a given relation (equal, or gscope a prefix of xscope); pairs outside its domain give U.
The activity is not modelled (one activity). υ: 'U' (an act of undetermined authorization counts as undetermined) or
'notcount'. C1 = constitute: no adoption on record gives F.

Two independent routes: Phi on the record (lfp = KK; wfs = the alternating fixpoint), and the record translated into
a ground normal program (program(); KK by Fitting's operator, WF by unfounded sets, Van Gelder-Ross-Schlipf).
Checks (1)-(13), (2b), (9b), (11b): see the printed lines. Version 3: D admits citations of earlier entries only (the
Principal, 2026-10-20); U carries its reasons (ce_star_explained). Checks 5, 7 and 8 use records outside D, to show what
the restriction excludes. Usage: python3 -I ce_model.py
"""
import random
from itertools import product

T, F, U = 'T', 'F', 'U'
FIELDS = {'root': {'actor', 'scope', 't'},
          'grant': {'actor', 'cites', 'holder', 'scope', 't', 'start', 'end'},
          'adopt': {'actor', 'cites', 'item', 'scope', 't', 'vfrom', 'vto'},
          'revoke': {'actor', 'cites', 'item', 'scope', 't'},
          'annul': {'actor', 'cites', 'target', 'scope', 't'},
          'endg': {'actor', 'cites', 'grant', 'scope', 't'}}


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

    def key(self, i):
        return (self.by[i]['t'], self.pos[i])


def in_D(rec):
    """Membership of D, decidable: unique ids, admitted kinds, required fields, integrity."""
    if len(rec.by) != len(rec.e):
        return False, 'identifiers not unique'
    for x in rec.e:
        if x.get('kind') not in FIELDS:
            return False, f"kind {x.get('kind')} not admitted ({x['id']})"
        if not FIELDS[x['kind']] <= set(x):
            return False, f"missing field in {x['id']}: {sorted(FIELDS[x['kind']] - set(x))}"
        if not x.get('ok', True):
            return False, f"integrity check failed for {x['id']}"
        for g in x.get('cites', []):
            if g in rec.pos and rec.pos[g] >= rec.pos[x['id']]:
                return False, f"{x['id']} cites {g}, which is not an earlier entry (D: citations of earlier entries only)"
    return True, None


def effective(a, eid, upsilon):
    v = a[eid]
    if upsilon == 'notcount':
        return T if v == T else F
    return v


def basis(rec, a, x, g, upsilon):
    """The value of 'x is authorized through basis g' (one cited grant or root)."""
    i = x['id']
    if g not in rec.by:
        return F                                   # a citation of an entry not in the record is not a basis
    gr = rec.by[g]
    cov = covers(gr['scope'], x['scope'], rec.dom)
    if gr['kind'] == 'root':                       # a root as a basis: its actor holds it; no window, no endings
        return k_and(a[g], T if gr['actor'] == x['actor'] else F, cov)
    if gr['kind'] != 'grant':
        return F
    in_window = T if gr['start'] <= x['t'] < gr['end'] else F
    ended = [effective(a, e['id'], upsilon) for e in rec.e
             if e['kind'] == 'endg' and e['grant'] == g and e['id'] != i and rec.key(e['id']) < rec.key(i)]
    annulled_g = [effective(a, e['id'], upsilon) for e in rec.e
                  if e['kind'] == 'annul' and e['target'] == g and e['id'] != i]
    return k_and(a[g], T if gr['holder'] == x['actor'] else F, in_window,
                 k_not(k_or(F, *ended)), k_not(k_or(F, *annulled_g)), cov)


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
        alts = [basis(rec, a, x, g, upsilon) for g in x['cites']]
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


def wfs(rec, count=None):
    """Well-founded model by the alternating fixpoint (Van Gelder 1989/1993), υ = 'counts as undetermined', read
    directly off the record. Positive dependence: authorization through a cited grant or root. Negative: endings and
    annulments. A coverage pair outside covers' domain is false in the lower pass and true in the upper one (the
    constant U as an atom u <- not u)."""
    passes = [0, 0]

    def least(J, hi):
        S = {x['id'] for x in rec.e if x['kind'] == 'root'}
        changed = True
        while changed:
            changed = False
            passes[1] += 1
            for x in rec.e:
                i = x['id']
                if i in S or x['kind'] == 'root' or not x['cites']:
                    continue
                if any(e['kind'] == 'annul' and e['target'] == i and e['id'] in J for e in rec.e):
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
                        ok = ok or gr['actor'] == x['actor']
                        continue
                    if gr['kind'] != 'grant' or gr['holder'] != x['actor'] or not (gr['start'] <= x['t'] < gr['end']):
                        continue
                    if any(e['kind'] == 'endg' and e['grant'] == g and e['id'] != i
                           and rec.key(e['id']) < rec.key(i) and e['id'] in J for e in rec.e):
                        continue
                    if any(e['kind'] == 'annul' and e['target'] == g and e['id'] != i and e['id'] in J for e in rec.e):
                        continue
                    ok = True
                if ok:
                    S.add(i)
                    changed = True
        return S
    K = set()
    while True:
        passes[0] += 1
        Up = least(K, hi=True)
        K2 = least(Up, hi=False)
        if K2 == K:
            break
        K = K2
    if count is not None:
        count.extend(passes)
    return {x['id']: (T if x['id'] in K else (U if x['id'] in Up else F)) for x in rec.e}


# ---- the second route: the record as a ground normal program --------------------------------------------------
def program(rec):
    """Rules (head, positive body, negative body). Roots are facts. For each non-root act x and cited basis g that
    passes the record constants (holder, window, covers not F): x <- g, [u_pair], not e (each prior ending of g),
    not e (each annulment of g), not e (each annulment of x). covers = U adds an atom u_pair with u_pair <- not u_pair."""
    rules = []
    upairs = set()
    for x in rec.e:
        i = x['id']
        if x['kind'] == 'root':
            rules.append((i, (), ()))
            continue
        own = tuple(e['id'] for e in rec.e if e['kind'] == 'annul' and e['target'] == i)
        for g in x['cites']:
            if g not in rec.by:
                continue
            gr = rec.by[g]
            c = covers(gr['scope'], x['scope'], rec.dom)
            if c == F:
                continue
            pos = [g]
            if c == U:
                u = ('u', gr['scope'], x['scope'])
                upairs.add(u)
                pos.append(u)
            if gr['kind'] == 'root':
                if gr['actor'] != x['actor']:
                    continue
                rules.append((i, tuple(pos), own))
                continue
            if gr['kind'] != 'grant' or gr['holder'] != x['actor'] or not (gr['start'] <= x['t'] < gr['end']):
                continue
            neg = [e['id'] for e in rec.e if e['kind'] == 'endg' and e['grant'] == g and e['id'] != i
                   and rec.key(e['id']) < rec.key(i)]
            neg += [e['id'] for e in rec.e if e['kind'] == 'annul' and e['target'] == g and e['id'] != i]
            rules.append((i, tuple(pos), tuple(neg) + own))
    for u in upairs:
        rules.append((u, (), (u,)))
    atoms = set(rec.acts()) | upairs
    return atoms, rules


def fitting_kk(atoms, rules):
    """Fitting's three-valued immediate-consequence operator, iterated from all-U."""
    val = {p: U for p in atoms}
    while True:
        new = {}
        for p in atoms:
            bodies = [k_and(*[val[q] for q in pos], *[k_not(val[q]) for q in neg]) for h, pos, neg in rules if h == p]
            new[p] = k_or(F, *bodies)
        if new == val:
            return val
        val = new


def wf_unfounded(atoms, rules):
    """WF by Van Gelder-Ross-Schlipf 1991: iterate W(I) = (immediate consequences, greatest unfounded set)."""
    Tset, Fset = set(), set()

    def lit_false(pos, neg):
        return any(q in Fset for q in pos) or any(q in Tset for q in neg)
    while True:
        newT = {h for h, pos, neg in rules if all(q in Tset for q in pos) and all(q in Fset for q in neg)}
        A = set(atoms)
        changed = True
        while changed:                               # greatest unfounded set: remove atoms with a usable rule
            changed = False
            for p in list(A):
                if any(h == p and not lit_false(pos, neg) and not any(q in A for q in pos) for h, pos, neg in rules):
                    A.discard(p)
                    changed = True
        if newT == Tset and A == Fset:
            break
        Tset, Fset = newT, A
    return {p: (T if p in Tset else F if p in Fset else U) for p in atoms}


def current(rec, a, item, t, upsilon, qscope=None):
    """current-authority(c, x, t): adoptions of c with t_x <= t (and, for a queried scope, covers(scope, x));
    whole-or-nothing scope; an earlier adoption stays current unless revoked (C6 fixed)."""
    adopts = [x for x in rec.e if x['kind'] == 'adopt' and x['item'] == item and x['t'] <= t]
    if not adopts:
        return F                                  # C1 = constitute: no adoption on record
    alts = []
    for x in adopts:
        valid = T if x['vfrom'] <= t < x['vto'] else F
        scope_ok = T if qscope is None else covers(x['scope'], qscope, rec.dom)
        revs = [k_and(effective(a, y['id'], upsilon), T if rec.key(y['id']) > rec.key(x['id']) and y['t'] <= t else F)
                for y in rec.e if y['kind'] == 'revoke' and y['item'] == item]
        intact = []
        for g in x['cites']:
            if g in rec.by and rec.by[g]['kind'] == 'grant':
                gr = rec.by[g]
                closes = T if gr['end'] <= t else F
                ends = [k_and(effective(a, e['id'], upsilon), T if rec.key(x['id']) < rec.key(e['id']) and e['t'] <= t else F)
                        for e in rec.e if e['kind'] == 'endg' and e['grant'] == g]
                intact.append(k_not(k_or(closes, *ends)))
        alts.append(k_and(a[x['id']], valid, scope_ok, k_not(k_or(F, *revs)), *intact))
    return k_or(*alts)


def ce_star(rec, item, t, qscope=None):
    ok, why = in_D(rec)
    if not ok:
        return ('undefined', why)
    return current(rec, wfs(rec), item, t, 'U', qscope)


def deps(rec, x):
    """x's dependencies in the program reading: (positive cited bases, negative endings and annulments)."""
    i = x['id']
    if x['kind'] == 'root':
        return [], []
    pos, neg = [], [e['id'] for e in rec.e if e['kind'] == 'annul' and e['target'] == i]
    for g in x['cites']:
        if g in rec.by and rec.by[g]['kind'] in ('root', 'grant'):
            pos.append(g)
            neg += [e['id'] for e in rec.e if e['kind'] == 'endg' and e['grant'] == g and e['id'] != i
                    and rec.key(e['id']) < rec.key(i)]
            neg += [e['id'] for e in rec.e if e['kind'] == 'annul' and e['target'] == g and e['id'] != i]
    return pos, neg


def ce_star_explained(rec, item, t, qscope=None):
    """CE* with its reason for U (the Principal, 2026-10-20: a self-cancelling act gives U with a refusal reason).
    Reasons: the cycles among U atoms that the item's adoptions depend on, and covers pairs outside covers' domain."""
    v = ce_star(rec, item, t, qscope)
    if v != U:
        return v, None
    a = wfs(rec)
    start = [x['id'] for x in rec.e if x['kind'] == 'adopt' and x['item'] == item and x['t'] <= t and a[x['id']] == U]
    return U, u_reasons(rec, a, start)


def act_explained(rec, i):
    """authorized(i) with its reason for U (the Q3-style question about one recorded act)."""
    ok, why = in_D(rec)
    if not ok:
        return ('undefined', why)
    a = wfs(rec)
    return (a[i], None) if a[i] != U else (U, u_reasons(rec, a, [i]))


def u_reasons(rec, a, start):
    seen, stack, reasons = set(), list(start), []
    while stack:
        i = stack.pop()
        if i in seen:
            continue
        seen.add(i)
        x = rec.by[i]
        pos, neg = deps(rec, x)
        for g in pos:
            gr = rec.by[g]
            if covers(gr['scope'], x['scope'], rec.dom) == U:
                reasons.append(f"covers({gr['scope']}, {x['scope']}) undefined for {i} under {g}")
        stack += [d for d in pos + neg if a[d] == U]
    for i in sorted(seen):                        # self-cancelling: i annuls a grant it acts under
        x = rec.by[i]
        if x['kind'] == 'annul' and x['target'] in x['cites']:
            reasons.append(f"self-cancelling act {i}: it annuls {x['target']}, the grant it acts under")
    for i in sorted(seen):                        # other negative cycles: i annuls j and j annuls i
        x = rec.by[i]
        if x['kind'] == 'annul' and x['target'] in seen and rec.by[x['target']]['kind'] == 'annul' \
                and rec.by[x['target']]['target'] == i and i < x['target']:
            reasons.append(f"mutual annulment of {i} and {x['target']}")
    return reasons or ['undetermined (no cycle or coverage reason found)']


def random_record(rng, n_acts=6, forward=False, targets_any=False):
    """forward=False: every citation and target is an earlier entry. forward=True: any other entry (forward citation).
    targets_any=True (with forward=False): citations earlier only, as D requires; annulment targets any other entry."""
    scopes = ['s', 's/a', 's/b']
    dom = {(g, x) for g in scopes for x in scopes}
    if rng.random() < 0.3:
        dom.discard((rng.choice(scopes), rng.choice(scopes)))   # coverage sometimes undefined
    kinds = [rng.choice(['grant', 'grant', 'adopt', 'adopt', 'revoke', 'annul', 'endg']) for _ in range(n_acts)]
    ids = ['r'] + [f'{k[0]}{j}' for j, k in enumerate(kinds)]
    ents = [{'id': 'r', 'kind': 'root', 'actor': 'P', 'scope': 's', 't': 0}]
    actors = ['P', 'A', 'B']
    for j, kind in enumerate(kinds):
        i = ids[j + 1]
        pool = [y for y in ids if y != i] if forward else ids[:j + 1]
        cites = rng.sample(pool, k=min(len(pool), rng.choice([1, 1, 2])))
        e = {'id': i, 'kind': kind, 'actor': rng.choice(actors), 'cites': cites, 'scope': rng.choice(scopes),
             't': rng.randint(0, 5)}
        if kind == 'grant':
            e.update(holder=rng.choice(actors), start=rng.randint(0, 3), end=rng.randint(3, 7))
        elif kind == 'adopt':
            e.update(item='c', vfrom=0, vto=rng.randint(3, 8))
        elif kind == 'revoke':
            e.update(item='c')
        elif kind == 'annul':
            e.update(target=rng.choice([y for y in ids if y != i] if targets_any else pool))
        elif kind == 'endg':
            gs = [y for y in pool if y.startswith('g')] or ['r']
            e.update(grant=rng.choice(gs))
        ents.append(e)
    return Record(ents, dom)


def loop_record(rng, n_acts=5):
    """Loop-rich records: one scope, wide windows, two actors; grants cite other grants or the root, so positive and
    negative cycles are frequent."""
    kinds = [rng.choice(['grant', 'grant', 'grant', 'adopt', 'annul', 'endg']) for _ in range(n_acts)]
    ids = ['r'] + [f'{k[0]}{j}' for j, k in enumerate(kinds)]
    ents = [{'id': 'r', 'kind': 'root', 'actor': 'P', 'scope': 's', 't': 0}]
    for j, kind in enumerate(kinds):
        i = ids[j + 1]
        pool = [y for y in ids if y != i]
        bases = [y for y in pool if y == 'r' or kinds[ids.index(y) - 1] == 'grant'] or ['r']
        e = {'id': i, 'kind': kind, 'actor': rng.choice(['A', 'B']), 'cites': [rng.choice(bases)], 'scope': 's',
             't': rng.randint(1, 3)}
        if kind == 'grant':
            e.update(holder=rng.choice(['A', 'B']), start=0, end=9)
        elif kind == 'adopt':
            e.update(item='c', vfrom=0, vto=9)
        elif kind == 'annul':
            e.update(target=rng.choice(pool))
        elif kind == 'endg':
            e.update(grant=rng.choice([y for y in bases if y != 'r'] or ['r']))
        ents.append(e)
    return Record(ents, {('s', 's')})


def all_fixed_points(rec, upsilon):
    ids = [i for i in rec.acts() if rec.by[i]['kind'] != 'root']
    out = []
    for vals in product([T, F, U], repeat=len(ids)):
        a = {i: T for i in rec.acts() if rec.by[i]['kind'] == 'root'}
        a.update(zip(ids, vals))
        if phi(rec, a, upsilon) == a:
            out.append(a)
    return out


def G(i, actor, cites, holder, t=1, start=0, end=9, scope='s'):
    return {'id': i, 'kind': 'grant', 'actor': actor, 'cites': cites, 'holder': holder, 'scope': scope, 't': t,
            'start': start, 'end': end}


ROOT = {'id': 'r', 'kind': 'root', 'actor': 'P', 'scope': 's', 't': 0}

if __name__ == '__main__':
    rng = random.Random(20261020)
    gens = [('backward', False), ('forward', True)]
    # (1) monotonicity of Phi under υ='U'
    for name, fw in gens:
        bad = 0
        for _ in range(400):
            rec = random_record(rng, forward=fw)
            ids = rec.acts()
            b = {i: (T if rec.by[i]['kind'] == 'root' else rng.choice([T, F, U])) for i in ids}
            a = {i: (b[i] if rng.random() < 0.5 or rec.by[i]['kind'] == 'root' else U) for i in ids}
            bad += not leq(phi(rec, a, 'U'), phi(rec, b, 'U'))
        print(f'(1) Phi monotone under υ=U, {name} citations: violations {bad} of 400')
        assert bad == 0
    # (2) KK: iteration from the bottom stops within n+1 rounds; a chain attains the bound
    worst = 0
    for name, fw in gens:
        for _ in range(2000):
            rec = random_record(rng, n_acts=rng.randint(1, 9), forward=fw)
            a, k = lfp(rec, 'U')
            assert a is not None
            worst = max(worst, k / (len(rec.e) + 1))
    n = 8
    chain = Record([ROOT] + [G(f'g{j}', 'P', [f'g{j + 1}'] if j < n - 1 else ['r'], 'P') for j in range(n)], {('s', 's')})
    _, kc = lfp(chain, 'U')
    print('(2) KK reached on 4000 random records; worst rounds/(n+1) =', round(worst, 2),
          f'; a chain of {n} grants recorded leaf first: {kc} rounds for n+1 = {len(chain.e)}')
    assert worst <= 1.0 and kc <= len(chain.e)
    # (2b) WF: alternating fixpoint, outer rounds <= n+1, inner passes <= 2(n+1)^2
    wo = wi = 0
    for _ in range(2000):
        rec = random_record(rng, n_acts=rng.randint(1, 9), forward=True)
        c = []
        wfs(rec, c)
        m = len(rec.e) + 1
        wo, wi = max(wo, c[0] / m), max(wi, c[1] / (2 * m * m))
    print('(2b) WF on 2000 forward records: worst outer rounds/(n+1) =', round(wo, 2),
          '; worst inner passes/(2(n+1)^2) =', round(wi, 3))
    assert wo <= 1.0 and wi <= 1.0
    # (3) υ='notcount': mutual annulment oscillates; it has two total fixed points and none is least
    mut = Record([ROOT, G('g1', 'P', ['r'], 'A', t=0), G('g2', 'P', ['r'], 'B', t=0),
                  {'id': 'x', 'kind': 'annul', 'actor': 'A', 'cites': ['g1'], 'target': 'y', 'scope': 's', 't': 1},
                  {'id': 'y', 'kind': 'annul', 'actor': 'B', 'cites': ['g2'], 'target': 'x', 'scope': 's', 't': 1}],
                 {('s', 's')})
    a_u, _ = lfp(mut, 'U')
    a_n, _ = lfp(mut, 'notcount', limit=50)
    fpn = all_fixed_points(mut, 'notcount')
    least = [p for p in fpn if all(leq(p, q) for q in fpn)]
    print('(3) mutual annulment: υ=U KK x, y =', a_u['x'], a_u['y'], '; υ=notcount: iteration reaches a fixed point:',
          a_n is not None, '; fixed points (x, y):', sorted((p['x'], p['y']) for p in fpn), '; least:', len(least))
    assert a_u['x'] == U and a_u['y'] == U and a_n is None and len(fpn) == 2 and not least
    # (4) CE* (read off WF) answers T, F and U on some records: constant evaluators fail
    seen = set()
    for _ in range(3000):
        rec = random_record(rng, targets_any=rng.random() < 0.5)          # records in D
        for t in range(0, 7):
            seen.add(ce_star(rec, 'c', t))
    print('(4) answers of CE*(c, t) on WF, 3000 random records in D:', sorted(seen))
    assert {T, F, U} <= seen
    # (5) exhaustive: every fixed point lies above KK; WF's extra values are not forced
    for name, gen in [('random forward', lambda: random_record(rng, n_acts=rng.randint(2, 6), forward=True)),
                      ('loop-rich', lambda: loop_record(rng, n_acts=rng.randint(2, 6)))]:
        recs = fps = multi = not_kk = not_above_wf = wf_not_fp = 0
        distinct = set()
        for _ in range(300):
            rec = gen()
            kk, _ = lfp(rec, 'U')
            wf = wfs(rec)
            fp = all_fixed_points(rec, 'U')
            recs += 1
            fps += len(fp)
            multi += len(fp) > 1
            wf_not_fp += wf not in fp
            for p in fp:
                assert leq(kk, p)
                not_kk += p != kk
                not_above_wf += not leq(wf, p)
        print(f'(5) exhaustive over 3^k assignments, {recs} {name} records: {fps} fixed points; records with more '
              f'than one: {multi}; fixed points other than KK: {not_kk} (all above KK); fixed points not above WF: '
              f'{not_above_wf}; records where WF is not a fixed point of Phi: {wf_not_fp}')
    assert fps > recs and not_kk > 0 and not_above_wf > 0
    # (6) the two routes agree: KK(Phi) = Fitting KK of the program; alternating WF = unfounded-set WF;
    #     with backward citations only WF = KK
    for name, fw in gens + [('loop-rich', None), ('backward citations, any targets (D)', 'D')]:
        diff = 0
        for _ in range(3000):
            rec = (loop_record(rng, n_acts=rng.randint(1, 9)) if fw is None else
                   random_record(rng, n_acts=rng.randint(1, 9), targets_any=True) if fw == 'D' else
                   random_record(rng, n_acts=rng.randint(1, 9), forward=fw))
            if fw == 'D':
                assert in_D(rec)[0]
            kk, _ = lfp(rec, 'U')
            wf = wfs(rec)
            atoms, rules = program(rec)
            fk, fw2 = fitting_kk(atoms, rules), wf_unfounded(atoms, rules)
            assert all(fk[i] == kk[i] for i in rec.acts()), 'KK routes differ'
            assert all(fw2[i] == wf[i] for i in rec.acts()), 'WF routes differ'
            assert leq(kk, wf)
            diff += kk != wf
        print(f'(6) {name}, 3000 records: KK routes agree, WF routes agree, KK <= WF; WF decides more on', diff)
        assert (diff == 0) == (fw in (False, 'D'))
    # (7) circular self-authorization: KK U, WF F; the all-T assignment is a total fixed point (F is grounded, not forced)
    circ = Record([ROOT, G('g1', 'A', ['g2'], 'B'), G('g2', 'B', ['g1'], 'A'),
                   {'id': 'x', 'kind': 'adopt', 'actor': 'A', 'cites': ['g2'], 'item': 'c', 'scope': 's', 't': 2,
                    'vfrom': 0, 'vto': 9}], {('s', 's')})
    kk, _ = lfp(circ, 'U')
    wf = wfs(circ)
    allT = {i: T for i in circ.acts()}
    print('(7) circular grants: KK g1, g2, x =', kk['g1'], kk['g2'], kk['x'], '; WF =', wf['g1'], wf['g2'], wf['x'],
          '; all-T is a fixed point:', phi(circ, allT, 'U') == allT)
    assert kk['x'] == U and wf['x'] == F and phi(circ, allT, 'U') == allT
    # (8) a grounded T that is not forced: x adopted under the root, annulled by an act under a self-supporting loop
    cm2 = Record([ROOT, {'id': 'x', 'kind': 'adopt', 'actor': 'P', 'cites': ['r'], 'item': 'c', 'scope': 's', 't': 1,
                         'vfrom': 0, 'vto': 9},
                  G('g1', 'A', ['g2'], 'B'), G('g2', 'B', ['g1'], 'A'),
                  {'id': 'n', 'kind': 'annul', 'actor': 'A', 'cites': ['g2'], 'target': 'x', 'scope': 's', 't': 2}],
                 {('s', 's')})
    kk, _ = lfp(cm2, 'U')
    wf = wfs(cm2)
    alt = {'r': T, 'x': F, 'g1': T, 'g2': T, 'n': T}
    print('(8) loop annulling a rooted adoption: KK x =', kk['x'], '; WF x =', wf['x'], '; current-authority(c, 3) off WF =',
          current(cm2, wf, 'c', 3, 'U'), '; {loop T, x F} is a fixed point:', phi(cm2, alt, 'U') == alt,
          '; CE* on this record (outside D):', ce_star(cm2, 'c', 3)[0])
    assert kk['x'] == U and wf['x'] == T and phi(cm2, alt, 'U') == alt
    # (9) mutual annulment stays undetermined under WF
    wm = wfs(mut)
    print('(9) mutual annulment, WF: x, y =', wm['x'], wm['y'])
    assert wm['x'] == U and wm['y'] == U
    mx = act_explained(mut, 'x')
    print('(9b) authorized(x) =', mx[0], 'with reason', mx[1])
    assert mx[0] == U and any('mutual annulment of x and y' in r for r in mx[1])
    # (10) a same-time ending: counts only if recorded before the act (key (t, position))
    def tie(first):
        x = {'id': 'x', 'kind': 'adopt', 'actor': 'A', 'cites': ['g'], 'item': 'c', 'scope': 's', 't': 3, 'vfrom': 0, 'vto': 9}
        e = {'id': 'e', 'kind': 'endg', 'actor': 'P', 'cites': ['r'], 'grant': 'g', 'scope': 's', 't': 3}
        return Record([ROOT, G('g', 'P', ['r'], 'A', t=0)] + ([e, x] if first else [x, e]), {('s', 's')})
    after, before = wfs(tie(False))['x'], wfs(tie(True))['x']
    print('(10) ending with the act time of x: recorded after x -> x', after, '; recorded before x -> x', before)
    assert after == T and before == F
    # (11) a holder annulling the grant it acts under: a negative cycle, U in KK and WF (defect 6 stays open)
    selfa = Record([ROOT, G('g1', 'P', ['r'], 'A', t=0),
                    {'id': 'e', 'kind': 'annul', 'actor': 'A', 'cites': ['g1'], 'target': 'g1', 'scope': 's', 't': 1}],
                   {('s', 's')})
    kk, _ = lfp(selfa, 'U')
    wf = wfs(selfa)
    print('(11) self-annulment cycle: KK g1, e =', kk['g1'], kk['e'], '; WF =', wf['g1'], wf['e'])
    assert kk['e'] == U and wf['e'] == U
    selfx = Record(selfa.e + [{'id': 'x', 'kind': 'adopt', 'actor': 'A', 'cites': ['g1'], 'item': 'c', 'scope': 's',
                               't': 2, 'vfrom': 0, 'vto': 9}], {('s', 's')})
    ans = ce_star_explained(selfx, 'c', 3)
    print('(11b) an adoption under that grant: CE*(c, 3) =', ans[0], 'with reason', ans[1])
    assert ans[0] == U and any('self-cancelling' in r for r in ans[1])
    # (12) a parent grant's ending does not reach the child grant's acts (defect 2, open half)
    par = Record([ROOT, G('g1', 'P', ['r'], 'A', t=0), G('g2', 'A', ['g1'], 'B', t=1),
                  {'id': 'e', 'kind': 'endg', 'actor': 'P', 'cites': ['r'], 'grant': 'g1', 'scope': 's', 't': 2},
                  {'id': 'x', 'kind': 'adopt', 'actor': 'B', 'cites': ['g2'], 'item': 'c', 'scope': 's', 't': 4,
                   'vfrom': 0, 'vto': 9}], {('s', 's')})
    print('(12) parent g1 ended at 2, act under child g2 at 4: x =', wfs(par)['x'], '; CE*(c, 5) =', ce_star(par, 'c', 5))
    assert wfs(par)['x'] == T
    # (13) outside D: CE* refuses with the reason (fail-closed against a dropped or failing negative entry)
    bad_int = Record([ROOT, {'id': 'x', 'kind': 'adopt', 'actor': 'P', 'cites': ['r'], 'item': 'c', 'scope': 's', 't': 1,
                             'vfrom': 0, 'vto': 9},
                      {'id': 'n', 'kind': 'annul', 'actor': 'P', 'cites': ['r'], 'target': 'x', 'scope': 's', 't': 2,
                       'ok': False}], {('s', 's')})
    nohold = Record([ROOT, {'id': 'g', 'kind': 'grant', 'actor': 'P', 'cites': ['r'], 'scope': 's', 't': 0,
                            'start': 0, 'end': 9}], {('s', 's')})
    sup = Record([ROOT, {'id': 'y', 'kind': 'supersede', 'actor': 'P', 'cites': ['r'], 'item': 'c', 'scope': 's', 't': 1}],
                 {('s', 's')})
    dropped = Record(bad_int.e[:2], {('s', 's')})
    fwd = Record([ROOT, G('g1', 'P', ['g2'], 'A'), G('g2', 'P', ['r'], 'P')], {('s', 's')})
    outs = [ce_star(r, 'c', 3) for r in (bad_int, nohold, sup, fwd)]
    print('(13) outside D:', [o[1] for o in outs], '; the same record with the failing annulment dropped would give',
          ce_star(dropped, 'c', 3))
    assert all(o[0] == 'undefined' for o in outs) and ce_star(dropped, 'c', 3) == T
    print('all assertions passed')

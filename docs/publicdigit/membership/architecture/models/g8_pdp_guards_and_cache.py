"""G-8 authorization decision point: unknown-fact guards and cache invalidation. Research only.

Commission: principal review of the consolidated cross-domain architecture (2026-10-10), Findings 2 and XD-7.
No production code is involved. Counts are enumerated cases, NOT probabilities.

P1 guards over {T, F, U}: is "Permit" sound for EVERY completion of the unknown facts?
   SPEC   : Permit only if the two-valued decision permits for all completions of U.
   E-ABS  : absence-as-false (U -> F) evaluator.
   E-K1   : Kleene, every rule binds only when its guard = T (candidate v1.0 §6.2).
   E-K2   : Kleene, grants / conditions need T; prohibitions / denies bind when guard != F.
P2 cached decisions under event lag.
   Facts: membership m, tenure u, jurisdiction j, policy version p.  Decision = m and u and (j == 0) under p=0;
   m and (j == 0) under p=1 (policy 1 drops the tenure requirement).
   C-DIRECT  : version vector over the "obvious" facts {m, u}, checked against the consumer's (lagged) view.
   C-CLOSURE : vector over the full dependency closure {m, u, j, p}, checked against the lagged view.
   C-WATER   : C-CLOSURE + watermark rule: trust the cache only if the consumer has processed the whole log.
   C-AUTHV   : C-CLOSURE checked against authoritative current versions (synchronous version read).
Run: python3 -I docs/publicdigit/membership/architecture/models/g8_pdp_guards_and_cache.py
"""
from itertools import product
import json

out = {}

# ---- P1 --------------------------------------------------------------------------------
T, F, U = 'T', 'F', 'U'
FACTS = ('f1', 'f2')
LITS = [None] + [(f, pos) for f in FACTS for pos in (True, False)]   # None = rule absent


def kleene(lit, val):
    if lit is None:
        return None
    v = val[lit[0]]
    if v == U:
        return U
    b = (v == T) if lit[1] else (v == F)
    return T if b else F


def decide2(rules, val):                       # two-valued reference (val has no U)
    g, d, pr, c = (kleene(r, val) for r in rules)
    if pr == T:
        return False
    if c is not None and c != T:
        return False
    if g != T:
        return False
    return d != T                              # equal priority: deny wins


def decide(rules, val, mode):
    if mode == 'ABS':
        return decide2(rules, {k: (F if v == U else v) for k, v in val.items()})
    g, d, pr, c = (kleene(r, val) for r in rules)
    blocks = (lambda x: x == T) if mode == 'K1' else (lambda x: x in (T, U))
    if pr is not None and blocks(pr):
        return False
    if c is not None and c != T:
        return False
    if g != T:
        return False
    return not (d is not None and blocks(d))


def completions(val):
    unk = [k for k, v in val.items() if v == U]
    for bits in product((T, F), repeat=len(unk)):
        c = dict(val)
        c.update(zip(unk, bits))
        yield c


p1 = {m: dict(unsound_permits=0, lost_certain_permits=0) for m in ('ABS', 'K1', 'K2')}
p1['cases'] = 0
example = {}
for rules in product(LITS, LITS, LITS, LITS):               # grant, deny, prohibition, condition
    for vs in product((T, F, U), repeat=len(FACTS)):
        val = dict(zip(FACTS, vs))
        p1['cases'] += 1
        certain = all(decide2(rules, c) for c in completions(val))
        for m in ('ABS', 'K1', 'K2'):
            got = decide(rules, val, m)
            if got and not certain:
                p1[m]['unsound_permits'] += 1
                example.setdefault(m, dict(rules=[str(r) for r in rules], facts=val))
            if certain and not got:
                p1[m]['lost_certain_permits'] += 1
p1['first_unsound_example'] = example
out['P1'] = p1

# ---- P2 --------------------------------------------------------------------------------
VARS = ('m', 'u', 'j', 'p')


def truth(s):
    if s['p'] == 0:
        return s['m'] == 1 and s['u'] == 1 and s['j'] == 0
    return s['m'] == 1 and s['j'] == 0


def state_after(init, log, n):
    s = dict(init)
    for var in log[:n]:
        s[var] ^= 1
    return s


def versions(log, n):
    return {v: sum(1 for x in log[:n] if x == v) for v in VARS}


p2 = {k: dict(trusted_stale=0, trusted_stale_unsafe_permit=0, refused=0)
      for k in ('C-DIRECT', 'C-CLOSURE', 'C-WATER', 'C-AUTHV')}
p2['cases'] = 0
MAXLOG = 3
for init_bits in product((0, 1), repeat=4):
    init = dict(zip(VARS, init_bits))
    for L in range(MAXLOG + 1):
        for log in product(VARS, repeat=L):
            for c in range(L + 1):                      # cache entry built from authoritative state at log position c
                cached = truth(state_after(init, log, c))
                vec = versions(log, c)
                actual = truth(state_after(init, log, L))
                for w in range(L + 1):                  # consumer has processed the first w events at query time
                    p2['cases'] += 1
                    view = versions(log, w)
                    auth = versions(log, L)
                    trust = {
                        'C-DIRECT': all(view[v] == vec[v] for v in ('m', 'u')),
                        'C-CLOSURE': all(view[v] == vec[v] for v in VARS),
                        'C-WATER': w == L and all(view[v] == vec[v] for v in VARS),
                        'C-AUTHV': all(auth[v] == vec[v] for v in VARS),
                    }
                    for k, ok in trust.items():
                        if not ok:
                            p2[k]['refused'] += 1
                        elif cached != actual:
                            p2[k]['trusted_stale'] += 1
                            if cached and not actual:
                                p2[k]['trusted_stale_unsafe_permit'] += 1
out['P2'] = p2

# ---- P3 (added 2026-10-11, discovery round): three policy modules, alternative and compound grants ----------
# Purpose: does K2 stay sound when a decision combines a platform module (prohibition), an organisation module
# (mandatory condition) and a resource module (alternative grants with AND / OR guards + an ordinary deny), and
# when a required module is missing? Also measure the completeness gap (certain permits that are denied).
# Guards are propositional formulas over two facts; evaluation is strong Kleene.
def k_not(a):
    return {T: F, F: T, U: U}[a]


def k_and(a, b):
    if F in (a, b): return F
    if U in (a, b): return U
    return T


def k_or(a, b):
    if T in (a, b): return T
    if U in (a, b): return U
    return F


def lit_val(lit, val):
    v = val[lit[0]]
    return v if lit[1] else k_not(v)


LITS2 = [(f, pos) for f in FACTS for pos in (True, False)]
FORMULAS = [('lit', l) for l in LITS2] \
    + [('and', a, b) for a in LITS2 for b in LITS2 if a[0] < b[0]] \
    + [('or', a, b) for a in LITS2 for b in LITS2 if a[0] < b[0]] \
    + [('or', ('f1', True), ('f1', False))]            # a tautology: Kleene gives U when f1 = U


def ev(form, val):
    if form is None: return None
    if form[0] == 'lit': return lit_val(form[1], val)
    a, b = lit_val(form[1], val), lit_val(form[2], val)
    return k_and(a, b) if form[0] == 'and' else k_or(a, b)


def decide3(grants, deny, prohib, cond, missing, val, kleene=True):
    if missing:                                         # any required module unavailable
        return False
    binds = (lambda x: x in (T, U)) if kleene else (lambda x: x == T)
    if prohib is not None and binds(ev(prohib, val)): return False
    if cond is not None and ev(cond, val) != T: return False
    if not any(ev(g, val) == T for g in grants): return False
    return not (deny is not None and binds(ev(deny, val)))


OPT = [None] + [('lit', l) for l in LITS2]
grant_sets = [()] + [(g,) for g in FORMULAS] + [(g, h) for i, g in enumerate(FORMULAS) for h in FORMULAS[i + 1:]]
p3 = dict(cases=0, unsound_permits=0, permits_with_missing_module=0, lost_certain_permits=0,
          lost_certain_permits_tautology_grant=0)
for grants in grant_sets:
    for deny in OPT:
        for prohib in OPT:
            for cond in OPT:
                for missing in (False, True):
                    for vs in product((T, F, U), repeat=len(FACTS)):
                        val = dict(zip(FACTS, vs))
                        p3['cases'] += 1
                        got = decide3(grants, deny, prohib, cond, missing, val)
                        if missing:
                            p3['permits_with_missing_module'] += got
                            continue
                        certain = all(decide3(grants, deny, prohib, cond, False, c, kleene=False)
                                      for c in completions(val))
                        if got and not certain:
                            p3['unsound_permits'] += 1
                        if certain and not got:
                            p3['lost_certain_permits'] += 1
                            if any(g == ('or', ('f1', True), ('f1', False)) for g in grants):
                                p3['lost_certain_permits_tautology_grant'] += 1
out['P3_three_modules'] = p3

print(json.dumps(out, indent=1))

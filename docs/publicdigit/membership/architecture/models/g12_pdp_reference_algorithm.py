"""G-12 normative PDP reference algorithm + test vectors. Research only (not application code).

Commission: principal review "Does v1.5 have implementation quality?" (2026-10-11), Finding 3.
Purpose:
  (1) state the decision algorithm as ONE Kleene-evaluated formula, so precedence is not left to implementations;
  (2) compare the two candidate combining algorithms for ordinary rules (an ARB decision, XD-13);
  (3) check soundness of PERMIT and DENY against all completions of unknown facts;
  (4) emit test vectors (JSON) that a future implementation must reproduce exactly.

Algorithm (normative candidate):
  1. any required module unavailable                      -> DEPENDENCY_FAILURE (MODULE_MISSING)
  2. module configuration invalid (lint)                  -> CONFIGURATION_ERROR
  3. D := AND(conditions) AND NOT OR(prohibitions) AND ORDINARY
       ORDINARY (DO, deny-overrides)  := OR(grants) AND NOT OR(denies)
       ORDINARY (PO, priority-ordered): highest priority level with any T-or-U rule decides; deny wins ties
                                        (expressed as a Kleene formula over levels)
  4. kleene(D) = T -> PERMIT ; F -> DENY ;
     U -> DEPENDENCY_FAILURE if any fact read by a rule is U with an operational reason, else INDETERMINATE
Run: python3 -I docs/publicdigit/membership/architecture/models/g12_pdp_reference_algorithm.py [--vectors out.json]
"""
from itertools import product
import json
import sys

T, F, U = 'T', 'F', 'U'
OPERATIONAL = {'SOURCE_UNAVAILABLE', 'PROJECTION_STALE', 'INCOMPLETE_QUERY', 'OUT_OF_FOOTPRINT'}


def k_not(a): return {T: F, F: T, U: U}[a]
def k_and(*xs): return F if F in xs else (U if U in xs else T)
def k_or(*xs): return T if T in xs else (U if U in xs else F)


def ev(form, val):
    """form: ('lit', fact, pos) | ('and', f, g) | ('or', f, g)"""
    if form[0] == 'lit':
        v = val[form[1]]
        return v if form[2] else k_not(v)
    a, b = ev(form[1], val), ev(form[2], val)
    return k_and(a, b) if form[0] == 'and' else k_or(a, b)


def facts_of(form):
    return {form[1]} if form[0] == 'lit' else facts_of(form[1]) | facts_of(form[2])


def ordinary_do(grants, denies, val):
    g = k_or(*[ev(x, val) for _, x in grants]) if grants else F
    d = k_or(*[ev(x, val) for _, x in denies]) if denies else F
    return k_and(g, k_not(d))


def ordinary_po(grants, denies, val):
    """Priority-ordered: levels descending; the first level whose rules can apply decides; deny wins ties.
    permit  <=> exists level L: grantL AND NOT denyL AND for all higher L': NOT grantL' AND NOT denyL'."""
    levels = sorted({p for p, _ in grants} | {p for p, _ in denies}, reverse=True)
    terms = []
    for i, lv in enumerate(levels):
        gL = k_or(*[ev(x, val) for p, x in grants if p == lv]) if any(p == lv for p, _ in grants) else F
        dL = k_or(*[ev(x, val) for p, x in denies if p == lv]) if any(p == lv for p, _ in denies) else F
        higher = [k_and(k_not(k_or(*[ev(x, val) for p, x in grants if p == h] or [F])),
                        k_not(k_or(*[ev(x, val) for p, x in denies if p == h] or [F])))
                  for h in levels[:i]]
        terms.append(k_and(gL, k_not(dL), *higher))
    return k_or(*terms) if terms else F


def decide(case, val, reasons, algo='DO'):
    if case['missing']:
        return dict(outcome='DEPENDENCY_FAILURE', reason='MODULE_MISSING')
    if case.get('config_invalid'):
        return dict(outcome='CONFIGURATION_ERROR', reason='LINT')
    cond = k_and(*[ev(c, val) for c in case['conditions']]) if case['conditions'] else T
    proh = k_or(*[ev(p, val) for p in case['prohibitions']]) if case['prohibitions'] else F
    ordi = (ordinary_do if algo == 'DO' else ordinary_po)(case['grants'], case['denies'], val)
    d = k_and(cond, k_not(proh), ordi)
    if d == T:
        return dict(outcome='PERMIT')
    if d == F:
        return dict(outcome='DENY')
    used = set()
    for f in case['conditions'] + case['prohibitions'] + [x for _, x in case['grants'] + case['denies']]:
        used |= facts_of(f)
    if any(val[f] == U and reasons.get(f) in OPERATIONAL for f in used):
        return dict(outcome='DEPENDENCY_FAILURE', reason='FACT_UNAVAILABLE')
    return dict(outcome='INDETERMINATE')


def completions(val):
    unk = [k for k, v in val.items() if v == U]
    for bits in product((T, F), repeat=len(unk)):
        c = dict(val)
        c.update(zip(unk, bits))
        yield c


L = lambda f, pos=True: ('lit', f, pos)
GRANTS = [[], [(1, L('f1'))], [(1, ('or', L('f1'), L('f2')))], [(1, ('and', L('f1'), L('f2', False)))],
          [(2, L('f1')), (1, L('f2'))]]                          # last: alternative grants at two priorities
DENIES = [[], [(1, L('f2'))], [(2, L('f2'))]]                   # deny at equal / higher priority
PROHIBS = [[], [L('f2')]]
CONDS = [[], [L('f1')]]
VALUES = [(T, None), (F, None), (U, 'OPEN_WORLD_ABSENT'), (U, 'SOURCE_UNAVAILABLE')]

stats = {a: dict(cases=0, unsound_permit=0, unsound_deny=0, indeterminate_but_certain=0) for a in ('DO', 'PO')}
differ = 0
vectors = []
for g, d, pr, co, missing in product(GRANTS, DENIES, PROHIBS, CONDS, (False, True)):
    case = dict(grants=g, denies=d, prohibitions=pr, conditions=co, missing=missing)
    for (v1, r1), (v2, r2) in product(VALUES, VALUES):
        val, reasons = {'f1': v1, 'f2': v2}, {'f1': r1, 'f2': r2}
        res = {}
        for algo in ('DO', 'PO'):
            out = decide(case, val, reasons, algo)
            res[algo] = out['outcome']
            s = stats[algo]
            s['cases'] += 1
            if missing:
                continue
            comp = [decide(case, c, {}, algo)['outcome'] for c in completions(val)]
            if out['outcome'] == 'PERMIT' and any(x != 'PERMIT' for x in comp):
                s['unsound_permit'] += 1
            if out['outcome'] == 'DENY' and any(x != 'DENY' for x in comp):
                s['unsound_deny'] += 1
            if out['outcome'] in ('INDETERMINATE', 'DEPENDENCY_FAILURE') and len(set(comp)) == 1:
                s['indeterminate_but_certain'] += 1
        differ += res['DO'] != res['PO']
        vectors.append(dict(case={k: (v if k == 'missing' else repr(v)) for k, v in case.items()},
                            facts={'f1': [v1, r1], 'f2': [v2, r2]},
                            expected_DO=res['DO'], expected_PO=res['PO']))

out = dict(stats=stats, cases_where_DO_and_PO_differ=differ, vectors=len(vectors))
if '--vectors' in sys.argv:
    path = sys.argv[sys.argv.index('--vectors') + 1]
    with open(path, 'w') as fh:
        json.dump(vectors, fh, indent=0)
    out['vectors_file'] = path
print(json.dumps(out, indent=1))

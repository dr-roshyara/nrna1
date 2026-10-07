#!/usr/bin/env python3
"""Cross-family cross-check comparator (PREREG.md + PREREG-ADDENDUM-01 r9).

Committed before any return exists. It encodes the unit set, the canonicalization,
the result classes, the exposure tiers and the licence rule.

FAIL-CLOSED: every key and value must match a closed alias table or a strict
grammar. Anything else raises ParseError, which gives INCOMPLETE (loud, never
silent). A checker-format adapter added after the return may map SYNTAX only.
It must be committed separately and reviewed, and it must not loosen any grammar
below.

usage: compare_xcheck.py CHECKER_DIR OUT_DIR [--untestable F.json] [--ambiguous F.json]
"""
import json, re, os, argparse
from collections import Counter

HERE = os.path.dirname(os.path.abspath(__file__))
THEORY = os.path.dirname(HERE)
CLAUDE_R5 = os.path.join(THEORY, 'LOOP-1BL/RETURN/RESULTS.json')
CLAUDE_V1B = os.path.join(THEORY, 'LOOP-1BN/V1B-RETURN/V1B-RESULTS.json')
CLAUDE_SUPP = os.path.join(THEORY, 'LOOP-1BN/V1B-RETURN/SUPP-RESULTS.json')

R5_KEYS = ['K1', 'K2', 'K3', 'K4', 'K4b', 'K5', 'K6', 'K7'] + [f'K8.{i}' for i in range(1, 8)] + \
          [f'K9.{i}' for i in range(1, 9)] + [f'K10.{i}' for i in range(1, 8)] + ['K11.1', 'K11.2', 'K12.1', 'K12.2']
READINGS = ['R0', 'R-b', 'R-c', 'R-d', 'R-e', 'R-f', 'R-g', 'R-g+', 'R-h', 'R-i', "R-i'", 'R-j']
FRAMES = ['U', 'U5']
FORMS = ['(i)-S', '(i)-O', '(ii)-S', '(ii)-O', '(iii)-L']
FIELDS = ['member(R_B)', 'member(H-D)', 'member(H-B)', 'iS', 'K4b', 'iiO_c', 'iiiL']
# Required (reading, history) tuples: V1B-SPEC §C; each in both frames. Common controls
# C-U5 and C-O1 define a frame and an observation, not a named history: they yield no named units.
REQUIRED = {('R-c', 'H_c'), ('R-d', 'H_d'), ('R-d', 'H_d2'), ('R-g', 'H_g'), ('R-g', "H_g'"),
            ('R-j', 'H_g'), ('R-g+', 'H_g'), ('R-h', 'I7'), ('R-h', 'W7'), ('R0', 'I7'), ('R0', 'W7'),
            ('R-i', 'H_i'), ('R-i', 'H_empty'), ("R-i'", 'H_i'), ("R-i'", 'H_empty'),
            ('R-j', 'H_j'), ('R-g+', 'H_g+')}
# V1B-SPEC §D.3 extras: required wherever their history's tuple is required.
EXTRAS = [('H_d2', 'exists(H-D+,~sf H_d2)', 'exists(H-D+, ~sf H_d2)'), ('W7', 'O', 'O'), ('I7', 'O', 'O')]
SUPP_UNITS = [('R-h', 'U', 'H_U5h', 'iiiL', 'iiiL(H_U5h)'), ('R-h', 'U5', 'H_U5h', 'iiiL', 'iiiL(H_U5h)'),
              ('R-i', 'U5', 'H_U5i', 'iiO_c', 'iiO_c(H_U5i)'), ('R-i', 'U5', 'H_U5i[c:=c1]', 'iiO_c', 'iiO_c(H_U5i with c := c1)'),
              ("R-i'", 'U5', 'H_U5i', 'iiO_c', 'iiO_c(H_U5i)'), ("R-i'", 'U5', 'H_U5i[c:=c1]', 'iiO_c', 'iiO_c(H_U5i with c := c1)')]
K12 = {
    'K12.1': (['O', 'O1', 'O2', 'O3', "O3'", 'O4', "O4'", 'O8'],
              ['sigma-only', 'f-only', 'Ref-only', 'con-only', 'd-only', '+act', '+work'],
              {'con-only', 'd-only'}),
    'K12.2': (['U', 'U5', 'U7', 'U9'],
              ['A5', 'A7+act', 'A7+work', 'A7+ref', 'A9con-only', 'A9d-only'],
              {'A7+act', 'A7+work', 'A7+ref', 'A9con-only', 'A9d-only'}),
}


class ParseError(Exception):
    pass


SUB = str.maketrans('₀₁₂₃₄₅₆₇₈₉', '0123456789')


KEY_CHARS = set("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 -_()[]:=,.'+~≈")


def norm(s):
    """Closed: any character outside KEY_CHARS (after the listed mappings) raises; nothing is silently dropped."""
    s = str(s).translate(SUB)
    for a in ('′', '’', '‘'):
        s = s.replace(a, "'")
    s = s.replace('⁺', '+').replace('∅', 'empty').replace('σ', 'sigma').replace('ρ', 'rho')
    bad = set(s) - KEY_CHARS
    if bad:
        raise ParseError(f'unknown characters {sorted(bad)!r} in key {s!r}')
    return re.sub(r"[^a-z0-9+']", '', s.lower())


def lookup(table, k, what):
    v = table.get(norm(k))
    if v is None:
        raise ParseError(f'unknown {what} {k!r}')
    return v


# ---------- closed alias tables (keys) ----------
T_READING = {'r0': 'R0', 'rb': 'R-b', 'rc': 'R-c', 'rd': 'R-d', 're': 'R-e', 'rf': 'R-f', 'rg': 'R-g',
             'rg+': 'R-g+', 'rgplus': 'R-g+', 'rh': 'R-h', 'ri': 'R-i', "ri'": "R-i'", 'riprime': "R-i'", 'rj': 'R-j'}
T_FRAME = {'u': 'U', 'u5': 'U5'}
T_FORM = {'is': '(i)-S', 'io': '(i)-O', 'iis': '(ii)-S', 'iio': '(ii)-O', 'iiil': '(iii)-L'}
T_HIST = {'hc': 'H_c', 'hd': 'H_d', 'hd2': 'H_d2', 'hg': 'H_g', "hg'": "H_g'", 'hgprime': "H_g'",
          'hg+': 'H_g+', 'hgplus': 'H_g+', 'hi': 'H_i', 'hempty': 'H_empty', 'hj': 'H_j', 'i7': 'I7', 'w7': 'W7',
          'hu5h': 'H_U5h', 'hu5i': 'H_U5i', 'hu5iwithcc1': 'H_U5i[c:=c1]', 'hu5icc1': 'H_U5i[c:=c1]',
          'hu5iwithc1': 'H_U5i[c:=c1]', 'hu5ic1': 'H_U5i[c:=c1]'}
T_FIELD = {'memberrb': 'member(R_B)', 'memberhd': 'member(H-D)', 'memberhb': 'member(H-B)', 'is': 'iS',
           'k4b': 'K4b', 'iioc': 'iiO_c', 'iiil': 'iiiL', 'o': 'O',
           'existshd+sfhd2': 'exists(H-D+,~sf H_d2)', 'existshd+sigmafhd2': 'exists(H-D+,~sf H_d2)',
           'existshdplussfhd2': 'exists(H-D+,~sf H_d2)'}
T_K12_1_ROW = {'o': 'O', 'o1': 'O1', 'o2': 'O2', 'o3': 'O3', "o3'": "O3'", 'o4': 'O4', "o4'": "O4'", 'o8': 'O8'}
T_K12_2_ROW = {'u': 'U', 'u5': 'U5', 'u7': 'U7', 'u9': 'U9'}
T_K12_1_GROUP = {'sigmaonly': 'sigma-only', 'sigma': 'sigma-only', 'fonly': 'f-only', 'f': 'f-only',
                 'refonly': 'Ref-only', 'ref': 'Ref-only', 'cononly': 'con-only', 'con': 'con-only',
                 'donly': 'd-only', 'd': 'd-only', '+act': '+act', '+actadditions': '+act', '+actaddition': '+act',
                 'actadditions': '+act', '+work': '+work', '+workadditions': '+work', '+workaddition': '+work',
                 'workadditions': '+work'}
T_K12_2_GROUP = {'a5': 'A5', 'a7+act': 'A7+act', 'a7+work': 'A7+work', 'a7+ref': 'A7+ref',
                 'a9cononly': 'A9con-only', 'a9con': 'A9con-only', 'a9donly': 'A9d-only', 'a9d': 'A9d-only',
                 # coarse labels, spec-literal ("A7 restricted to …", "A9 (con-only or d-only …)")
                 'a7': 'A7', 'a7restrictedto+act+workand+ref': 'A7', 'a9': 'A9',
                 'a9cononlyordonlychangessatisfyingf2': 'A9', 'a9cononlyordonly': 'A9'}
COARSE = {'A7': ('A7+act', 'A7+work', 'A7+ref'), 'A9': ('A9con-only', 'A9d-only')}

# ---------- strict value grammars ----------
INF = {'inf', '∞', 'infinity'}
KIND = {'iss': 'iss', 'issue': 'iss', 'adp': 'adp', 'adopt': 'adp', 'adoption': 'adp'}
REC = {'rho1', 'ρ1'}


def to_bool(x):
    if isinstance(x, bool):
        return x
    if isinstance(x, str) and x.strip().upper() in ('TRUE', 'FALSE'):
        return x.strip().upper() == 'TRUE'
    raise ParseError(f'bool {x!r}')


def to_int(x):
    if isinstance(x, bool) or not isinstance(x, int):
        raise ParseError(f'int {x!r}')
    return x


def _bit(x, what):
    if isinstance(x, bool) or x not in (0, 1):
        raise ParseError(f'{what} {x!r}')
    return x


def _rec_scalar(x, what):
    """r or c: a scalar, or a dict keyed exactly by the single record (ρ1/rho1)."""
    if isinstance(x, dict):
        if len(x) != 1 or next(iter(x)) not in REC:
            raise ParseError(f'{what} {x!r}')
        x = next(iter(x.values()))
    return x


def to_r(x):
    return _bit(_rec_scalar(x, 'r'), 'r')


def to_c(x):
    x = _rec_scalar(x, 'c')
    if x not in ('c1', 'c2'):
        raise ParseError(f'c {x!r}')
    return x


RE_OBS = [re.compile(r'^\(\s*r\s*=\s*([01])\s*,\s*(?:c\s*=\s*)?(c[12])\s*\)$'),
          re.compile(r'^\(\s*([01])\s*,\s*(c[12])\s*\)$')]


def to_obs(x):
    if isinstance(x, dict):
        if set(x) != {'r', 'c'}:
            raise ParseError(f'O {x!r}')
        return (to_r(x['r']), to_c(x['c']))
    if isinstance(x, (list, tuple)) and len(x) == 2:
        return (_bit(x[0], 'O.r'), to_c(x[1]))
    if isinstance(x, str):
        for rx in RE_OBS:
            m = rx.match(x.strip())
            if m:
                return (int(m.group(1)), m.group(2))
    raise ParseError(f'O {x!r}')


def to_sigma(x):
    if isinstance(x, (list, tuple)) and len(x) == 2 and all(v in ('p', 'q') for v in x):
        return tuple(x)
    if isinstance(x, str):
        m = re.match(r'^\(?\s*([pq])\s*,?\s*([pq])\s*\)?$', x.strip())
        if m:
            return (m.group(1), m.group(2))
    raise ParseError(f'sigma {x!r}')


def _f(x):
    if x is None or x == float('inf') or (isinstance(x, str) and x.strip().lower() in INF):
        return 'inf'
    return _bit(x, 'f')


RE_SLOT_ISS = re.compile(r'^(iss|issue)\(\s*d\s*=\s*([01])\s*,\s*v\s*=\s*([pq])\s*,\s*prep\s*=\s*([01])\s*,\s*f\s*=\s*(0|1|inf|∞)\s*\)$')
RE_SLOT_ADP = re.compile(r'^(adp|adopt)\(\s*d\s*=\s*([01])\s*(?:,\s*obj\s*=\s*(a1|a2))?\s*\)$')


def slot(x, me):
    if x is None or x == 'absent' or x == {'present': False}:
        return None
    if isinstance(x, str):
        m = RE_SLOT_ISS.match(x.strip())
        if m:
            fv = m.group(5)
            return ('iss', int(m.group(2)), m.group(3), int(m.group(4)), _f(int(fv) if fv.isdigit() else fv))
        m = RE_SLOT_ADP.match(x.strip())
        if m:
            if m.group(3) is not None and m.group(3) == me:
                raise ParseError(f'adp obj is its own slot {x!r}')
            return ('adp', int(m.group(2)))
        raise ParseError(f'slot {x!r}')
    if not isinstance(x, dict):
        raise ParseError(f'slot {x!r}')
    d = {k: v for k, v in x.items() if k != 'present' or v is not True}
    kind = KIND.get(str(d.get('kind', '')).lower())
    if kind == 'iss':
        if set(d) != {'kind', 'd', 'v', 'prep', 'f'} or d['v'] not in ('p', 'q'):
            raise ParseError(f'iss slot {x!r}')
        return ('iss', _bit(d['d'], 'd'), d['v'], _bit(d['prep'], 'prep'), _f(d['f']))
    if kind == 'adp':
        extra = set(d) - {'kind', 'd', 'obj'}
        if extra - {'v', 'prep', 'f'} or any(d.get(k) is not None for k in extra):
            raise ParseError(f'adp slot {x!r}')
        if extra:
            raise ParseError(f'adp slot with iss attributes {x!r}')
        if 'obj' in d and d['obj'] not in ({'a1', 'a2'} - {me}):
            raise ParseError(f'adp obj {x!r}')
        return ('adp', _bit(d['d'], 'd'))
    raise ParseError(f'slot kind {x!r}')


RE_CAP_STR = re.compile(r'^\{\s*(\(\s*x\s*,\s*(iss|adp)\s*\)\s*(,\s*\(\s*x\s*,\s*(iss|adp)\s*\)\s*)*)?\}$')


def to_cap(x):
    if isinstance(x, str):
        if not RE_CAP_STR.match(x.strip()):
            raise ParseError(f'Cap {x!r}')
        return frozenset(re.findall(r'(iss|adp)', x))
    if not isinstance(x, (list, tuple, set)):
        raise ParseError(f'Cap {x!r}')
    out = set()
    for it in x:
        if isinstance(it, (list, tuple)) and len(it) == 2 and it[0] == 'x' and str(it[1]).lower() in KIND:
            out.add(KIND[str(it[1]).lower()])
        elif isinstance(it, str):
            m = re.match(r'^\(?\s*(?:x\s*,\s*)?([a-z]+)\s*\)?$', it.strip())
            if not m or m.group(1) not in KIND:
                raise ParseError(f'Cap item {it!r}')
            out.add(KIND[m.group(1)])
        else:
            raise ParseError(f'Cap item {it!r}')
    return frozenset(out)


def to_w1(x):
    if x is None or x == 'absent' or x == {'present': False}:
        return None
    if isinstance(x, dict):
        d = {k: v for k, v in x.items() if k != 'present' or v is not True}
        if set(d) != {'t'}:
            raise ParseError(f'w1 {x!r}')
        return _bit(d['t'], 'w1.t')
    if isinstance(x, int):
        return _bit(x, 'w1')
    if isinstance(x, str):
        m = re.match(r'^t\s*=\s*([01])$', x.strip())
        if m:
            return int(m.group(1))
    raise ParseError(f'w1 {x!r}')


RE_REF_STR = re.compile(r'^\{\s*((a1|a2|w1)\s*(,\s*(a1|a2|w1)\s*)*)?\}$')


def to_ref(x):
    if isinstance(x, str):
        if not RE_REF_STR.match(x.strip()):
            raise ParseError(f'Ref {x!r}')
        return frozenset(re.findall(r'(a1|a2|w1)', x))
    if not isinstance(x, (list, tuple, set)):
        raise ParseError(f'Ref {x!r}')
    out = set()
    for it in x:
        if isinstance(it, (list, tuple)) and len(it) == 2 and it[0] in REC and it[1] in ('a1', 'a2', 'w1'):
            out.add(it[1])
        elif isinstance(it, str):
            m = re.match(r'^\(?\s*(?:(?:rho1|ρ1)\s*,\s*)?(a1|a2|w1)\s*\)?$', it.strip())
            if not m:
                raise ParseError(f'Ref item {it!r}')
            out.add(m.group(1))
        else:
            raise ParseError(f'Ref item {it!r}')
    return frozenset(out)


def _con_val(v):
    if v == 'none':
        return 'none'
    if v in REC:
        return 'rho1'
    raise ParseError(f'con value {v!r}')


def to_con(x, a1, a2):
    if isinstance(x, str):
        m = re.match(r'^\(\s*a1\s*:\s*(none|rho1|ρ1)\s*,\s*a2\s*:\s*(none|rho1|ρ1)\s*\)$', x.strip())
        if not m:
            raise ParseError(f'con {x!r}')
        d = {'a1': m.group(1), 'a2': m.group(2)}
    elif isinstance(x, dict):
        if not set(x) <= {'a1', 'a2'}:
            raise ParseError(f'con {x!r}')
        d = x
    else:
        raise ParseError(f'con {x!r}')
    out = []
    for k, sl in (('a1', a1), ('a2', a2)):
        if sl is None:
            out.append(None)  # an absent slot's con is irrelevant
        else:
            if k not in d:
                raise ParseError(f'con missing {k}')
            out.append(_con_val(d[k]))
    return tuple(out)


HKEYS = {'sigma', 'a1', 'a2', 'Cap', 'w1', 'Ref', 'con', 'r', 'c'}


CLAUDE_ANNOTATIONS = {'tuple_R1', 'h_v_exists', 'h_c_exists'}


def claude_hist(x):
    """Claude-side history: strip Claude's own annotation keys; any other failure is a comparator defect."""
    if x is None:
        return None
    return hist({k: v for k, v in x.items() if k not in CLAUDE_ANNOTATIONS})


def hist(x):
    if x is None:
        return None
    if not isinstance(x, dict) or not HKEYS <= set(x) or set(x) - HKEYS - {'tuple_R1'}:
        raise ParseError(f'history keys {x!r}')
    a1, a2 = slot(x['a1'], 'a1'), slot(x['a2'], 'a2')
    return (to_sigma(x['sigma']), a1, a2, to_cap(x['Cap']), to_w1(x['w1']), to_ref(x['Ref']),
            to_con(x['con'], a1, a2), to_r(x['r']), to_c(x['c']))


def k12_rows(x, key):
    """Returns {row: set(groups)}. Rows: a list of labels, or a dict label -> bool."""
    rows_t = T_K12_1_ROW if key == 'K12.1' else T_K12_2_ROW
    grp_t = T_K12_1_GROUP if key == 'K12.1' else T_K12_2_GROUP
    if not isinstance(x, dict):
        raise ParseError(f'{key} result {x!r}')
    out = {}
    for rk, labels in x.items():
        row = lookup(rows_t, rk, f'{key} row')
        if row in out:
            raise ParseError(f'{key}: colliding row keys for {row}')
        if isinstance(labels, dict):
            present, mentioned = set(), set()
            for g, b in labels.items():
                cg = lookup(grp_t, g, f'{key} group')
                if cg in mentioned:
                    raise ParseError(f'{key} row {row}: colliding labels for {cg}')
                mentioned.add(cg)
                if to_bool(b):
                    present.add(cg)
        elif isinstance(labels, list):
            present = {lookup(grp_t, g, f'{key} group') for g in labels}
            mentioned = set(present)
        else:
            raise ParseError(f'{key} row {rk!r}')
        out[row] = (present, mentioned)
    return out


# ---------- units ----------
ABSENT = object()
COARSE_MARK = object()
IGNORED_BODY_KEYS = {'images', 'diffs', 'class_sizes', 'n_counterexamples', 'scope_size', 'notes'}


def _get(d, k):
    """Field access that propagates a collision ParseError instead of hiding it."""
    if isinstance(d, ParseError):
        return d
    if not isinstance(d, dict):
        return ABSENT
    return d.get(k, ABSENT)


class Unit:
    def __init__(s, uid, tier, required, claude, checker, kind, **kw):
        s.uid, s.tier, s.required, s.claude, s.checker, s.kind = uid, tier, required, claude, checker, kind
        s.kw = kw


# REVIEW-r6 §4: under these readings H-D^R∩H-B^R ⊇ H-D∩H-B and H-D^R∩H-B^R ⊆ H-B^R ⊆ R_B^R, so exposed
# R0/U facts (K5, K4, K6, and the named-history exposures) transfer by monotonicity in frame U.
MONO_READINGS = ('R-c', 'R-e', 'R-f', 'R-i', "R-i'")
NAMED_C1 = {'H_c', 'H_d', 'H_d2', 'H_g', "H_g'", 'H_g+', 'H_i', 'H_empty', 'H_j', 'I7', 'W7'}


def exposed_v1b(R, F, form=None, h=None, field=None):
    # REVIEW-r8: named-history fields at planted histories are pervasively derivable (coordinates given;
    # K4b <=> H in R_B; member(R_B) syntactic; iS/iiO_c via exposed forms). Conservative final rule:
    # EVERY named-history, extra and SUPP field is EXPOSED. BLIND keeps only computed quantities.
    if field is not None:
        return True
    # ---- closure meta-rules (REVIEW-r7) ----
    if R == 'R-b':
        R = 'R0'  # in U and U5, B-recW forces w1 present, so B-conf^b <=> B-conf: R-b's classes equal R0's
    if form is None and F == 'U5' and h in NAMED_C1:
        F = 'U'   # every named history has c = c1: U5's constraint is vacuous for it and its witnesses
    if form is None and h == 'H_U5i[c:=c1]' and field == 'iiO_c' and R in ('R-i', "R-i'"):
        return True  # iiO_c depends on H only through O = (0, c1), exposed under R-i/R-i' in U
    if F == 'U' and R == 'R-h':
        if form is not None or (field == 'iS' and h in ('H_g+', 'W7', 'I7')) or field == 'iiO_c':
            return True  # K4/K6 witnesses keep Ref and the present slots, so they lie in H-B^h
    if form is not None and F == 'U' and R in ('R-c', 'R-f') and form in ('(ii)-S', '(ii)-O'):
        return True
    if form is not None:
        if (R == 'R0' and F == 'U') or (R == 'R0' and F == 'U5' and form in ('(i)-O', '(i)-S')) or \
               (R == 'R-d' and F == 'U' and form in ('(i)-O', '(i)-S')):
            return True
        if F == 'U' and R in MONO_READINGS and form == '(i)-O':
            return True
        if F == 'U' and R == 'R-e' and form in ('(i)-S', '(ii)-S', '(ii)-O'):
            return True
        return False
    if F == 'U' and R in MONO_READINGS + ('R-d',) and field == 'iS' and h in ('H_g+', 'W7', 'I7'):
        return True
    if F == 'U' and R in MONO_READINGS and field == 'iiO_c':
        return True
    if field and field.startswith('member') and h in ('H_c', 'H_g+', 'W7', 'I7'):
        return True
    if field == 'member(R_B)' and R == "R-i'":
        return True  # R_B = U by the reading's definition: spec-given, not an outcome
    if field == 'O' and h in ('W7', 'I7'):
        return True
    if field == 'iiiL' and h == 'I7' and R == 'R0' and F == 'U':
        return True
    # REVIEW-r3 D: derivable from exposed R0/U forms + exposed membership; K4b is reading-independent.
    if field == 'iS' and R == 'R0' and F == 'U' and h in ('H_g+', 'W7', 'I7'):
        return True
    # REVIEW-r4: iiO_c depends on H only through O(H); every planted history has O = (0, c1), so the
    # exposed iiO_c(W7) under R0/U exposes iiO_c at every named history under R0/U.
    if field == 'iiO_c' and R == 'R0' and F == 'U':
        return True
    if field == 'K4b' and F == 'U' and h in ('H_g+', 'W7', 'I7'):
        return True
    return False


def _idx(d, table, what, unrecognized, path):
    """Map checker keys through a closed table; unknown keys are recorded, never guessed."""
    out = {}
    if not isinstance(d, dict):
        return out
    for k, v in d.items():
        try:
            cid = lookup(table, k, what)
        except ParseError:
            unrecognized.append(f'{path}/{k}')
            continue
        if cid in out:
            # two raw keys map to one canonical id: never let one overwrite the other
            unrecognized.append(f'{path}/{k} (collides with an earlier key for {cid})')
            out[cid] = ParseError(f'colliding keys for {cid}')
        else:
            out[cid] = v
    return out


def build_units(c5, cv, cs, k5, kv, unrecognized):
    units = []
    units.append(Unit('R5/n_frame_histories', 'BLIND', True, c5['n_frame_histories'],
                      k5.get('n_frame_histories', ABSENT) if isinstance(k5, dict) else ABSENT, 'int'))
    cc = c5['checks']
    kchecks = k5.get('checks', {}) if isinstance(k5, dict) else {}
    if isinstance(k5, dict):
        for k in set(k5) - {'n_frame_histories', 'checks', '_meta'}:
            unrecognized.append(f'R5/{k}')
    kc = {}
    if isinstance(kchecks, dict):
        for k, v in kchecks.items():
            if k in R5_KEYS and k not in kc:
                kc[k] = v
                if isinstance(v, dict):
                    for x in set(v) - {'result', 'least_witness_or_counterexample', 'difference_sigma_values',
                                       'detail', 'notes'}:
                        unrecognized.append(f'R5/checks/{k}/{x}')
            else:
                unrecognized.append(f'R5/checks/{k}')
    for key in R5_KEYS:
        kk = kc.get(key, ABSENT)
        if key in K12:
            rows, groups, blind = K12[key]
            cl = {r: pm[0] for r, pm in k12_rows(cc[key]['result'], key).items()}
            try:
                kr = ABSENT if kk is ABSENT else k12_rows(kk.get('result') if isinstance(kk, dict) else None, key)
            except (ParseError, TypeError, ValueError, AttributeError) as e:
                kr = e if isinstance(e, ParseError) else ParseError(f'{type(e).__name__}: {e}')
            for r in rows:
                kpm = kr.get(r, ABSENT) if isinstance(kr, dict) else kr
                kset, kment = (kpm if isinstance(kpm, tuple) else (kpm, kpm))
                for g in groups:
                    coarse = next((c for c, fine in COARSE.items() if g in fine), None)
                    if kset is ABSENT or isinstance(kset, ParseError):
                        kv_ = kset
                    elif coarse and coarse in kset and not (kment & set(COARSE[coarse])):
                        # coarse only if NO fine label of this property is mentioned at all (an explicit false is scored)
                        kv_ = COARSE_MARK  # the checker reported only the coarse property for this row
                    else:
                        kv_ = g in kset
                    units.append(Unit(f'R5/{key}/{r}/{g}', 'BLIND' if g in blind else 'EXPOSED', True,
                                      g in cl.get(r, set()), kv_, 'bool'))
                for c, fine in (COARSE.items() if key == 'K12.2' else []):
                    if kset is ABSENT or isinstance(kset, ParseError):
                        kv_ = kset
                    else:
                        kv_ = c in kset or bool(kset & set(fine))
                    units.append(Unit(f'R5/{key}/{r}/{c}-any', 'EXPOSED', True,
                                      bool(cl.get(r, set()) & set(fine)), kv_, 'bool'))
            continue
        units.append(Unit(f'R5/{key}/result', 'EXPOSED', True, cc[key]['result'],
                          kk.get('result', ABSENT) if isinstance(kk, dict) else ABSENT, 'bool'))
        w = cc[key]['witness_or_counterexample']
        kw = kk.get('least_witness_or_counterexample', ABSENT) if isinstance(kk, dict) else ABSENT
        if w is None:
            units.append(Unit(f'R5/{key}/witness', 'EXPOSED', False, None, kw, 'hist'))
        else:
            roles = [h for h in w.values() if isinstance(h, dict) and 'sigma' in h]
            units.append(Unit(f'R5/{key}/witness', 'NONLICENSING', False, roles, kw, 'anyof'))
        if key == 'K8.1':
            units.append(Unit('R5/K8.1/difference_sigma_values', 'BLIND', True,
                              cc[key]['detail']['difference sigma values per o'],
                              kk.get('difference_sigma_values', ABSENT) if isinstance(kk, dict) else ABSENT, 'k81'))
    # V1b
    kvn = {}
    kv_top = {k: x for k, x in kv.items() if k != '_meta'} if isinstance(kv, dict) else kv
    for R, byF in _idx(kv_top, T_READING, 'reading', unrecognized, 'V1b').items():
        for F, body in _idx(byF, T_FRAME, 'frame', unrecognized, f'V1b/{R}').items():
            kvn[(R, F)] = body
    for R in READINGS:
        for F in FRAMES:
            cb = cv[R][F]
            kb = kvn.get((R, F), {})
            kforms, knamed = {}, {}
            if isinstance(kb, dict):
                for k, v in kb.items():
                    if k in IGNORED_BODY_KEYS:
                        continue
                    if k == 'named':
                        for h, fl in _idx(v, T_HIST, 'history', unrecognized, f'V1b/{R}/{F}/named').items():
                            knamed[h] = fl if isinstance(fl, ParseError) else \
                                _idx(fl, T_FIELD, 'field', unrecognized, f'V1b/{R}/{F}/named/{h}')
                        continue
                    try:
                        fid = lookup(T_FORM, k, 'form')
                    except ParseError:
                        unrecognized.append(f'V1b/{R}/{F}/{k}')
                        continue
                    if isinstance(v, dict):
                        for x in set(v) - {'holds', 'least_counterexample'} - IGNORED_BODY_KEYS:
                            unrecognized.append(f'V1b/{R}/{F}/{k}/{x}')
                    if fid in kforms:
                        unrecognized.append(f'V1b/{R}/{F}/{k} (collides with an earlier key for {fid})')
                        kforms[fid] = ParseError(f'colliding keys for {fid}')
                    else:
                        kforms[fid] = v
            for form in FORMS:
                ch = cb['checks'][form]
                kf = kforms.get(form, ABSENT)
                tier = 'EXPOSED' if exposed_v1b(R, F, form=form) else 'BLIND'
                kh = _get(kf, 'holds')
                units.append(Unit(f'V1b/{R}/{F}/{form}/holds', tier, True, ch['holds'], kh, 'bool'))
                if form != '(i)-O':
                    units.append(Unit(f'V1b/{R}/{F}/{form}/least_counterexample', tier, None,
                                      ch['least_counterexample'],
                                      _get(kf, 'least_counterexample'),
                                      'cex', claude_holds=ch['holds'], checker_holds=kh))
            for h, cfl in cb['named'].items():
                req = (R, h) in REQUIRED
                for fld in FIELDS:
                    tier = 'EXPOSED' if exposed_v1b(R, F, h=h, field=fld) else 'BLIND'
                    units.append(Unit(f'V1b/{R}/{F}/named/{h}/{fld}', tier, req, cfl.get(fld),
                                      _get(knamed.get(h, {}), fld), 'iiil' if fld == 'iiiL' else 'bool',
                                      claude_in_scope=cfl.get('iiiL_in_scope'),
                                      claude_scope_by_membership=not (cfl.get('member(H-D)') and cfl.get('member(R_B)')),
                                      member_units=(f'V1b/{R}/{F}/named/{h}/member(H-D)',
                                                    f'V1b/{R}/{F}/named/{h}/member(R_B)')))
            for h, fld, ckey in EXTRAS:
                req = (R, h) in REQUIRED
                units.append(Unit(f'V1b/{R}/{F}/named/{h}/{fld}',
                                  'EXPOSED' if exposed_v1b(R, F, h=h, field=fld) else 'BLIND', req,
                                  cb['named'][h].get(ckey), _get(knamed.get(h, {}), fld),
                                  'obs' if fld == 'O' else 'bool'))
            for sr, sf, sh, sfld, ckey in SUPP_UNITS:
                if (sr, sf) == (R, F):
                    units.append(Unit(f'V1b/{R}/{F}/supp/{sh}/{sfld}',
                                      'EXPOSED' if exposed_v1b(R, F, h=sh, field=sfld) else 'BLIND', True, cs[sr][sf][ckey],
                                      _get(knamed.get(sh, {}), sfld), 'iiil' if sfld == 'iiiL' else 'bool'))
    return units


def canon(kind, x, side='checker'):
    if side == 'claude' and kind in ('hist', 'cex'):
        return claude_hist(x)
    if kind == 'bool':
        return None if x is None else to_bool(x)
    if kind == 'int':
        return to_int(x)
    if kind == 'iiil':
        return None if x is None else to_bool(x)  # 3-valued: True / False / None(out of scope)
    if kind in ('hist', 'cex'):
        return hist(x)
    if kind == 'obs':
        return None if x is None else to_obs(x)
    if kind == 'k81':
        if not isinstance(x, dict):
            raise ParseError(f'K8.1 must be per-o {x!r}')
        seen, out = set(), set()
        for o, ss in x.items():
            co = to_obs(o)
            if co in seen:
                raise ParseError(f'K8.1: colliding o-keys for {co}')
            seen.add(co)
            if isinstance(ss, dict):
                # allowed only as a set written as a dict whose every value is literally true
                if not all(b is True for b in ss.values()):
                    raise ParseError(f'K8.1 per-o dict with non-true values at {o!r}')
                ss = list(ss)
            if not isinstance(ss, list):
                raise ParseError(f'K8.1 per-o value must be a list at {o!r}')
            for s in ss:
                out.add((co, to_sigma(s)))
        return frozenset(out)
    raise ValueError(kind)


def classify(u, untestable, ambiguous):
    flags = []
    if any(_pmatch(u.uid, p) for p in ambiguous):
        flags.append('AMBIGUOUS')
    unt = any(_pmatch(u.uid, p) for p in untestable)
    if u.checker is COARSE_MARK:
        return 'NOT_A_UNIT', flags + ['coarse-label'], None, None
    if isinstance(u.checker, ParseError):
        return 'INCOMPLETE', flags + ['MALFORMED'], None, str(u.checker)
    required = u.required
    if u.kind == 'cex':
        # a unit only where both sides say holds = false; then required
        try:
            ch = None if u.kw['checker_holds'] is ABSENT else to_bool(u.kw['checker_holds'])
        except ParseError:
            ch = None
        if not (u.kw['claude_holds'] is False and ch is False):
            return 'NOT_A_UNIT', flags, None, None
        required = True
        if u.checker is None:
            u.checker = ABSENT
    if u.checker is ABSENT:
        if unt:
            return 'UNTESTABLE', flags, None, None
        return ('INCOMPLETE' if required else 'NOT_A_UNIT'), flags, None, None
    try:
        # Claude side first, OUTSIDE the scoring: a failure here is a comparator defect and must crash.
        cv = None
        roles = {claude_hist(h) for h in u.claude} if u.kind == 'anyof' else None
        if u.kind != 'anyof':
            cv = canon(u.kind, u.claude, side='claude')
    except ParseError as e:
        raise RuntimeError(f'Claude-side parse failure at {u.uid}: {e}')
    try:
        if u.kind == 'anyof':
            kv = hist(u.checker)
            cls = 'AGREEMENT' if (kv in roles or (kv is None and not roles)) else 'DISAGREEMENT'
            cv = sorted(map(str, roles))
        else:
            kv = canon(u.kind, u.checker)
            cls = 'NOT_APPLICABLE' if (cv is None and kv is None) else ('AGREEMENT' if cv == kv else 'DISAGREEMENT')
            if u.kind == 'iiil' and cv is None and kv is False and u.kw.get('claude_in_scope') is False:
                # Pre-declared (REVIEW-r4 E3, narrowed by REVIEW-r5): the spec types iiiL as {true,false}.
                if u.kw.get('claude_scope_by_membership'):
                    # Claude's out-of-scope follows from membership alone: accepted iff both membership units agree.
                    cls = 'SCOPE_PENDING'
                    flags.append('iiiL-false-out-of-scope')
                else:
                    # out-of-scope follows from coordinates (a2, w1 conformance): not verifiable from units
                    cls = 'AGREEMENT'
                    flags += ['AMBIGUOUS', 'iiiL-scope-unverifiable']
    except (ParseError, TypeError, ValueError, AttributeError) as e:
        # checker-side data only (Claude side is parsed above, outside this try)
        return 'INCOMPLETE', flags + ['MALFORMED'], None, f'{type(e).__name__}: {e}'
    if unt:
        flags.append(f'value-compared:{cls}')
        cls = 'UNTESTABLE'
    return cls, flags, str(cv), str(kv)


def _pmatch(uid, p):
    return uid == p or uid.startswith(p.rstrip('/') + '/')


KNOWN_C_CANDIDATES = {'V1b/R-d/U/(i)-S/holds': 'F-1: R4 Lem 3.7 read literally asserts (i) incl. ≈σf under D-st+; '
                                                 'its proof gives only ≈σf,Cap (REVIEW-r2).'}


def _no_dup(pairs):
    d = {}
    for k, v in pairs:
        if k in d:
            raise ParseError(f'duplicate JSON key {k!r}')
        d[k] = v
    return d


def main(argv=None):
    ap = argparse.ArgumentParser()
    ap.add_argument('checker_dir'); ap.add_argument('out_dir')
    ap.add_argument('--untestable'); ap.add_argument('--ambiguous')
    a = ap.parse_args(argv)
    c5 = json.load(open(CLAUDE_R5)); cv = json.load(open(CLAUDE_V1B)); cs = json.load(open(CLAUDE_SUPP))
    files = {}
    for name in ('R5-RESULTS.json', 'V1B-RESULTS.json'):
        try:
            files[name] = json.load(open(os.path.join(a.checker_dir, name)), object_pairs_hook=_no_dup)
        except FileNotFoundError:
            files[name] = 'MISSING'
        except (json.JSONDecodeError, UnicodeDecodeError, ParseError):
            files[name] = 'MALFORMED'
    unt = json.load(open(a.untestable)) if a.untestable else []
    amb = json.load(open(a.ambiguous)) if a.ambiguous else []
    k5, kv = files['R5-RESULTS.json'], files['V1B-RESULTS.json']
    unrecognized = []
    units = build_units(c5, cv, cs, k5 if isinstance(k5, dict) else None, kv if isinstance(kv, dict) else None,
                        unrecognized)
    rows = []
    for u in units:
        fstate = k5 if u.uid.startswith('R5/') else kv
        if not isinstance(fstate, dict):
            cls, flags, cval, kval = 'INCOMPLETE', ['FILE_' + str(fstate)], None, None
        else:
            cls, flags, cval, kval = classify(u, unt, amb)
        row = {'unit': u.uid, 'tier': u.tier, 'required': bool(u.required), 'class': cls, 'flags': flags,
               'claude': cval, 'checker': kval}
        if u.uid in KNOWN_C_CANDIDATES:
            row['known_c_candidate'] = KNOWN_C_CANDIDATES[u.uid]
        rows.append(row)
    byid = {r['unit']: r for r in rows}
    for r in rows:
        if r['class'] == 'SCOPE_PENDING':
            hd, rb = (r['unit'].rsplit('/', 1)[0] + '/member(H-D)', r['unit'].rsplit('/', 1)[0] + '/member(R_B)')
            ok = all(byid.get(x, {}).get('class') == 'AGREEMENT' for x in (hd, rb))
            r['class'] = 'AGREEMENT' if ok else 'DISAGREEMENT'
    scored = [r for r in rows if r['class'] != 'NOT_A_UNIT']
    count = Counter((r['tier'], r['class']) for r in rows)
    flagged = [r['unit'] for r in scored if 'AMBIGUOUS' in r['flags']]
    licence = bool(scored) and all(r['class'] in ('AGREEMENT', 'NOT_APPLICABLE') for r in scored) \
        and not flagged and not unrecognized
    summary = {'n_units_scored': len(scored), 'n_not_a_unit_listed': len(rows) - len(scored),
               'counts': {f'{t}/{c}': n for (t, c), n in sorted(count.items())},
               'unrecognized_checker_keys': unrecognized,
               'ambiguous_flagged': flagged,
               'disagreements_for_review': [r['unit'] for r in rows if r['class'] == 'DISAGREEMENT'
                                            or 'value-compared:DISAGREEMENT' in r['flags']],
               'coarse_label_rows_not_scored': [r['unit'] for r in rows if 'coarse-label' in r['flags']],
               'iiiL_scope_unverifiable': [r['unit'] for r in rows if 'iiiL-scope-unverifiable' in r['flags']],
               'licence_cross_family_reproduction_BLIND_tier': licence,
               'note': 'Every DISAGREEMENT is classified (a)-(d) by the reviewer; any non-(a) goes to the human gate. '
                       'Unrecognized keys block the licence until a reviewed syntax-only adapter maps them. '
                       'Agreement is not proof; disagreement is not falsification.'}
    os.makedirs(a.out_dir, exist_ok=True)
    json.dump({'summary': summary, 'units': rows}, open(os.path.join(a.out_dir, 'COMPARISON.json'), 'w'),
              indent=1, ensure_ascii=False)
    print(json.dumps({k: v for k, v in summary.items() if k != 'note'}, indent=1, ensure_ascii=False)[:3000])
    return summary


if __name__ == '__main__':
    main()

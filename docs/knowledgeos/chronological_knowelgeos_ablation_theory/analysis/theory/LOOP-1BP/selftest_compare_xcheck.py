#!/usr/bin/env python3
"""Planted controls for compare_xcheck.py, run before any return exists.

P0: Claude's own results, re-encoded in TASK.md's format with different syntax
    (booleans, dict histories, alias names). Expected: no DISAGREEMENT and no
    INCOMPLETE; licence true.
N1–N6: single planted defects. Each must be caught exactly as stated.
"""
import copy, json, os, re, sys, tempfile
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import compare_xcheck as C

ALIAS_R = {"R-i'": 'R-i′', 'R-g+': 'R-g⁺'}
ALIAS_H = {'H_empty': 'H_∅', "H_g'": 'H_g′', 'H_g+': 'H_g⁺'}


def str_hist_to_dict(h):
    """Claude V1b string histories -> TASK-style dict (syntax only)."""
    if h is None or 'tuple_R1' not in h and isinstance(h.get('sigma'), list):
        return h

    def sl(s):
        if s == 'absent':
            return 'absent'
        m = re.match(r'(\w+)\((.*)\)', s)
        d = dict(re.findall(r'(\w+)=([^,)]+)', m.group(2)))
        return {'kind': m.group(1), 'd': int(d['d']), 'v': d['v'], 'prep': int(d['prep']),
                'f': None if d['f'] in ('inf', '∞') else int(d['f'])}
    con = dict(re.findall(r'(a\d):(\w+)', h['con']))
    return {'sigma': re.findall(r'[pq]', h['sigma']), 'a1': sl(h['a1']), 'a2': sl(h['a2']),
            'Cap': [f'(x,{k})' for k in re.findall(r'(iss|adp)', h['Cap'])],
            'w1': 'absent' if h['w1'] == 'absent' else {'t': int(re.search(r'\d', h['w1']).group())},
            'Ref': [f'(ρ1,{x})' for x in re.findall(r'(a1|a2|w1)', h['Ref'])],
            'con': {k: ('ρ1' if v != 'none' else 'none') for k, v in con.items()}, 'r': h['r'], 'c': h['c']}


def make_p0():
    c5 = json.load(open(C.CLAUDE_R5)); cv = json.load(open(C.CLAUDE_V1B)); cs = json.load(open(C.CLAUDE_SUPP))
    r5 = {'n_frame_histories': c5['n_frame_histories'], 'checks': {}}
    for k, v in c5['checks'].items():
        w = v['witness_or_counterexample']
        if isinstance(w, dict) and 'sigma' not in w:
            w = next((h for h in w.values() if isinstance(h, dict) and 'sigma' in h), None)
        e = {'result': v['result'] if isinstance(v['result'], dict) else v['result'] == 'TRUE',
             'least_witness_or_counterexample': w}
        if k == 'K8.1':
            e['difference_sigma_values'] = v['detail']['difference sigma values per o']
        r5['checks'][k] = e
    v1b = {}
    for R in C.READINGS:
        v1b[ALIAS_R.get(R, R)] = {}
        for F in C.FRAMES:
            b = cv[R][F]; out = {}
            for form in C.FORMS:
                ch = b['checks'][form]
                out[form] = {'holds': ch['holds']}
                if form != '(i)-O':
                    out[form]['least_counterexample'] = str_hist_to_dict(ch['least_counterexample'])
            named = {}
            for h, fl in b['named'].items():
                d = {f: fl[f] for f in C.FIELDS if f in fl}
                d['O'] = fl['O']
                if 'exists(H-D+, ~sf H_d2)' in fl:
                    d['exists(H-D+, ≈σf H_d2)'] = fl['exists(H-D+, ~sf H_d2)']
                named[ALIAS_H.get(h, h)] = d
            out['named'] = named
            v1b[ALIAS_R.get(R, R)][F] = out
    for R, byF in cs.items():
        for F, fl in byF.items():
            for k, val in fl.items():
                h = 'H_U5h' if 'U5h' in k else ('H_U5i with c := c1' if 'c1' in k else 'H_U5i')
                fld = 'iiiL' if k.startswith('iiiL') else 'iiO_c'
                v1b[ALIAS_R.get(R, R)][F]['named'].setdefault(h, {})[fld] = val
    return r5, v1b


def run(r5, v1b, **kw):
    d = tempfile.mkdtemp()
    if r5 is not None:
        json.dump(r5, open(os.path.join(d, 'R5-RESULTS.json'), 'w'), ensure_ascii=False)
    if v1b is not None:
        json.dump(v1b, open(os.path.join(d, 'V1B-RESULTS.json'), 'w'), ensure_ascii=False)
    args = [d, os.path.join(d, 'out')]
    for k, v in kw.items():
        p = os.path.join(d, k + '.json'); json.dump(v, open(p, 'w')); args += ['--' + k, p]
    import io, contextlib
    with contextlib.redirect_stdout(io.StringIO()):
        C.main(args)
    return json.load(open(os.path.join(d, 'out', 'COMPARISON.json')))


def cls(res, uid):
    return [u['class'] for u in res['units'] if u['unit'] == uid]


def main():
    ok = True
    r5, v1b = make_p0()
    res = run(r5, v1b)
    s = res['summary']
    bad = [u for u in res['units'] if u['class'] in ('DISAGREEMENT', 'INCOMPLETE', 'UNTESTABLE')]
    print('P0 units', s['n_units_scored'], s['counts'], 'unrecognized', s['unrecognized_checker_keys'])
    if bad or not s['licence_cross_family_reproduction_BLIND_tier']:
        ok = False; print('P0 FAIL', json.dumps(bad[:5], ensure_ascii=False))
    # N1: flip one BLIND V1b holds
    r, v = make_p0(); v['R-c']['U']['(i)-S']['holds'] = not v['R-c']['U']['(i)-S']['holds']
    x = run(r, v); ok &= cls(x, 'V1b/R-c/U/(i)-S/holds') == ['DISAGREEMENT'] and not x['summary']['licence_cross_family_reproduction_BLIND_tier']
    print('N1', cls(x, 'V1b/R-c/U/(i)-S/holds'))
    # N2: drop a required named field
    r, v = make_p0(); del v['R-d']['U']['named']['H_d2']['iS']
    x = run(r, v); ok &= cls(x, 'V1b/R-d/U/named/H_d2/iS') == ['INCOMPLETE']; print('N2', cls(x, 'V1b/R-d/U/named/H_d2/iS'))
    # N3: same drop, declared untestable in NOTES
    x = run(r, v, untestable=['V1b/R-d/U/named/H_d2']); ok &= cls(x, 'V1b/R-d/U/named/H_d2/iS') == ['UNTESTABLE']; print('N3', cls(x, 'V1b/R-d/U/named/H_d2/iS'))
    # N4: wrong K12.2 sub-kind (BLIND row)
    r, v = make_p0(); r['checks']['K12.2']['result']['U7'] = ['A7 +act']
    x = run(r, v); ok &= cls(x, 'R5/K12.2/U7/A7+work') == ['DISAGREEMENT']; print('N4', cls(x, 'R5/K12.2/U7/A7+work'))
    # N5: malformed history in a counterexample
    r, v = make_p0()
    tgt = next((R, F, f) for R in v for F in v[R] for f in C.FORMS if f != '(i)-O' and v[R][F][f].get('least_counterexample'))
    v[tgt[0]][tgt[1]][tgt[2]]['least_counterexample'] = {'sigma': 'garbage'}
    x = run(r, v); u = [w for w in x['units'] if w['unit'].endswith(f'{tgt[1]}/{tgt[2]}/least_counterexample') and 'MALFORMED' in w['flags']]
    ok &= len(u) == 1 and u[0]['class'] == 'INCOMPLETE'; print('N5', [w['class'] for w in u])
    # N6: missing V1B file entirely; and ambiguous flag blocks licence
    x = run(r5, None); ok &= all(u['class'] == 'INCOMPLETE' for u in x['units'] if u['unit'].startswith('V1b/')); print('N6a', x['summary']['counts'].get('BLIND/INCOMPLETE'))
    r, v = make_p0(); x = run(r, v, ambiguous=['V1b/R-j/U5']); ok &= not x['summary']['licence_cross_family_reproduction_BLIND_tier']; print('N6b licence', x['summary']['licence_cross_family_reproduction_BLIND_tier'])
    # ---- adversarial encodings (REVIEW-r3 C): each must be loud or correct, never a silent agreement ----
    def first_cex():
        r, v = make_p0()
        for R in v:
            for F in v[R]:
                for f in C.FORMS:
                    if f != '(i)-O' and v[R][F][f].get('least_counterexample'):
                        return r, v, R, F, f
    def ucls(x, suffix, flag=None):
        return [w['class'] for w in x['units'] if w['unit'].endswith(suffix) and (flag is None or flag in w['flags'])]
    r, v, R, F, f = first_cex(); h = v[R][F][f]['least_counterexample']
    rid = {'R-i′': "R-i'", 'R-g⁺': 'R-g+'}.get(R, R)
    suf = f'{rid}/{F}/{f}/least_counterexample'
    # T1: r/c keyed by record, changed values -> DISAGREEMENT
    h2 = dict(h); h2['r'] = {'ρ1': 1 - h['r']}; h2['c'] = {'ρ1': 'c2' if h['c'] == 'c1' else 'c1'}
    v[R][F][f]['least_counterexample'] = h2; x = run(r, v); t1 = ucls(x, suf); ok &= t1 == ['DISAGREEMENT']; print('T1', t1)
    # T1b: same, unchanged values -> AGREEMENT (correct parse)
    r, v, *_ = first_cex(); h2 = dict(h); h2['r'] = {'ρ1': h['r']}; h2['c'] = {'rho1': h['c']}
    v[R][F][f]['least_counterexample'] = h2; x = run(r, v); t = ucls(x, suf); ok &= t == ['AGREEMENT']; print('T1b', t)
    # T2: Cap with 'issue'/'adopt' words, one extra capability -> DISAGREEMENT
    r, v, *_ = first_cex(); h2 = dict(h); h2['Cap'] = [['x', 'issue'], ['x', 'adopt']]
    same = set(re.findall(r'(iss|adp)', json.dumps(h['Cap']))) == {'iss', 'adp'}
    v[R][F][f]['least_counterexample'] = h2; x = run(r, v); t = ucls(x, suf); ok &= t == (['AGREEMENT'] if same else ['DISAGREEMENT']); print('T2', t, 'same' if same else '')
    # T3: con with booleans -> MALFORMED
    r, v, *_ = first_cex(); h2 = dict(h); h2['con'] = {'a1': False, 'a2': True}
    v[R][F][f]['least_counterexample'] = h2; x = run(r, v)
    t = ucls(x, suf); present = h['a1'] != 'absent' or h['a2'] != 'absent'
    ok &= t == (['INCOMPLETE'] if present else ['AGREEMENT']); print('T3', t)
    # T4: present slot without f -> MALFORMED
    r, v, *_ = first_cex(); h2 = json.loads(json.dumps(h)); s = 'a1' if isinstance(h2['a1'], dict) else 'a2'
    if isinstance(h2[s], dict):
        h2[s].pop('f'); h2[s]['force'] = 0
        v[R][F][f]['least_counterexample'] = h2; x = run(r, v); t = ucls(x, suf); ok &= t == ['INCOMPLETE']; print('T4', t)
    # T5a: required supp iiiL omitted -> INCOMPLETE
    r, v = make_p0(); del v['R-h']['U5']['named']['H_U5h']['iiiL']
    x = run(r, v); t = ucls(x, 'R-h/U5/supp/H_U5h/iiiL'); ok &= t == ['INCOMPLETE']; print('T5a', t)
    # T5b: I7 iiiL null vs Claude's non-null -> DISAGREEMENT (scope disagreement is visible)
    r, v = make_p0(); cv0 = v['R-h']['U']['named']['I7']['iiiL']; v['R-h']['U']['named']['I7']['iiiL'] = None
    x = run(r, v); t = ucls(x, 'R-h/U/named/I7/iiiL'); ok &= t == (['DISAGREEMENT'] if cv0 is not None else ['NOT_APPLICABLE']); print('T5b', t, cv0)
    # T6: K12 row as dict of booleans; a false entry must not count as present
    r, v = make_p0(); rows = r['checks']['K12.1']['result']
    r['checks']['K12.1']['result'] = {k: {g: (g in gs) for g in ['sigma-only', 'f-only', 'Ref-only', 'con-only', 'd-only', '+act additions', '+work additions']} for k, gs in rows.items()}
    x = run(r, v); bad = [w for w in x['units'] if w['unit'].startswith('R5/K12.1/') and w['class'] != 'AGREEMENT']; ok &= not bad; print('T6', len(bad))
    # T7: generic A9 label echoing the spec -> MALFORMED, not an agreement on the con-only/d-only split
    r, v = make_p0(); r['checks']['K12.2']['result']['U9'] = ['A9 (con-only or d-only changes satisfying F2)']
    x = run(r, v); t = sorted(set(ucls(x, 'K12.2/U9/A9con-only') + ucls(x, 'K12.2/U9/A9d-only'))); t9 = ucls(x, 'K12.2/U9/A9-any')
    ok &= t == ['NOT_A_UNIT'] and t9 == ['AGREEMENT'] and len(x['summary']['coarse_label_rows_not_scored']) == 2; print('T7 coarse', t, t9)
    # T8: unknown reading key -> recorded, licence blocked
    r, v = make_p0(); v['R-a'] = {}; x = run(r, v); ok &= x['summary']['unrecognized_checker_keys'] == ['V1b/R-a'] and not x['summary']['licence_cross_family_reproduction_BLIND_tier']; print('T8', x['summary']['unrecognized_checker_keys'])
    # T9: n_frame_histories as a formatted string -> INCOMPLETE, no crash
    r, v = make_p0(); r['n_frame_histories'] = '665,920'; x = run(r, v); t = ucls(x, 'R5/n_frame_histories'); ok &= t == ['INCOMPLETE']; print('T9', t)
    # T10: counterexample omitted where both sides say holds=false -> INCOMPLETE
    r, v, *_ = first_cex(); del v[R][F][f]['least_counterexample']; x = run(r, v); t = ucls(x, suf); ok &= t == ['INCOMPLETE']; print('T10', t)
    # T11: subscripted / typographic aliases resolve (O₁ row, R-i’ reading)
    r, v = make_p0(); rows = r['checks']['K12.1']['result']; rows['O₁'] = rows.pop('O1')
    v['R-i’'] = v.pop('R-i′'); x = run(r, v); ok &= not x['summary']['unrecognized_checker_keys'] and x['summary']['licence_cross_family_reproduction_BLIND_tier']; print('T11', x['summary']['licence_cross_family_reproduction_BLIND_tier'])
    # ---- REVIEW-r4 controls: collisions, closed norm, prefixes, iiiL scope rule, gate routing ----
    def wrong_first(d, k_wrong, k_right, val_wrong):
        """Insert a colliding alias with a WRONG value before the right key (dict order = JSON order)."""
        items = list(d.items()); d.clear(); d[k_wrong] = val_wrong
        for k, v in items: d[k] = v
    r, v = make_p0(); wv = copy.deepcopy(v["R-i′"]); wv['U']['(ii)-S']['holds'] = not wv['U']['(ii)-S']['holds']
    wrong_first(v, "R-i'", "R-i′", wv); x = run(r, v)
    a1 = x['summary']['licence_cross_family_reproduction_BLIND_tier']; ok &= not a1 and any('collides' in k for k in x['summary']['unrecognized_checker_keys']); print('A1 licence', a1)
    r, v = make_p0(); nm = v['R-g⁺']['U']['named']; wrong_first(nm, 'H_g+', 'H_g⁺', dict(nm['H_g⁺'], iS=not nm['H_g⁺'].get('iS')))
    x = run(r, v); a2 = x['summary']['licence_cross_family_reproduction_BLIND_tier']; ok &= not a2; print('A2 licence', a2, ucls(x, 'R-g+/U/named/H_g+/iS'))
    r, v = make_p0(); b = v['R-c']['U']; wrong_first(b, '(i)S', '(i)-S', {'holds': not b['(i)-S']['holds']})
    x = run(r, v); a3 = ucls(x, 'R-c/U/(i)-S/holds'); ok &= a3 == ['INCOMPLETE']; print('A3', a3)
    r, v = make_p0(); ch = r['checks']; wrong_first(ch, 'K 5', 'K5', {'result': False})
    x = run(r, v); a4 = x['summary']['licence_cross_family_reproduction_BLIND_tier']; ok &= not a4; print('A4 licence', a4)
    d = tempfile.mkdtemp(); r, v = make_p0(); json.dump(r, open(os.path.join(d, 'R5-RESULTS.json'), 'w'))
    s = json.dumps(v, ensure_ascii=False); s = s.replace('{"R0":', '{"R0": {}, "R0":', 1)
    open(os.path.join(d, 'V1B-RESULTS.json'), 'w').write(s)
    import io, contextlib
    with contextlib.redirect_stdout(io.StringIO()): C.main([d, os.path.join(d, 'o')])
    y = json.load(open(os.path.join(d, 'o', 'COMPARISON.json'))); a5 = all(u['class'] == 'INCOMPLETE' for u in y['units'] if u['unit'].startswith('V1b/')); ok &= a5; print('A5 duplicate JSON key -> file INCOMPLETE', a5)
    r, v = make_p0(); v['R-iʼ'] = v.pop('R-i′'); x = run(r, v); a6 = x['summary']['unrecognized_checker_keys']; ok &= a6 == ['V1b/R-iʼ'] and not x['summary']['licence_cross_family_reproduction_BLIND_tier']; print('A6 closed norm', a6)
    r, v = make_p0(); x = run(r, v, untestable=['R5/K1']); a7 = sum(1 for u in x['units'] if u['class'] == 'UNTESTABLE'); k1n = sum(1 for u in x['units'] if u['unit'].startswith('R5/K1/'))
    ok &= a7 == k1n; print('A7 prefix boundary', a7, k1n)
    r, v = make_p0(); v['R-c']['U']['(ii)-S']['holds'] = not v['R-c']['U']['(ii)-S']['holds']; x = run(r, v, untestable=['V1b/R-c/U/(ii)-S'])
    ok &= 'V1b/R-c/U/(ii)-S/holds' in x['summary']['disagreements_for_review']; print('A8 untestable disagreement routed to gate', 'V1b/R-c/U/(ii)-S/holds' in x['summary']['disagreements_for_review'])
    # iiiL false out of scope: accepted only when member(H-D) and member(R_B) agree
    c_v = json.load(open(C.CLAUDE_V1B))
    tgt = next((R, F, h) for R in C.READINGS for F in C.FRAMES for h, fl in c_v[R][F]['named'].items()
               if fl.get('iiiL') is None and fl.get('iiiL_in_scope') is False and fl.get('member(H-D)') is not None)
    rk = ALIAS_R.get(tgt[0], tgt[0]); hk = ALIAS_H.get(tgt[2], tgt[2]); suf = f'{tgt[0]}/{tgt[1]}/named/{tgt[2]}/iiiL'
    r, v = make_p0(); v[rk][tgt[1]]['named'][hk]['iiiL'] = False; x = run(r, v); s1 = ucls(x, suf)
    r, v = make_p0(); v[rk][tgt[1]]['named'][hk]['iiiL'] = False; v[rk][tgt[1]]['named'][hk]['member(H-D)'] = not v[rk][tgt[1]]['named'][hk]['member(H-D)']; x = run(r, v); s2 = ucls(x, suf)
    ok &= s1 == ['AGREEMENT'] and s2 == ['DISAGREEMENT']; print('A9 iiiL false out of scope', s1, s2)
    # ignored body keys do not block; an unknown field does
    r, v = make_p0(); v['R0']['U']['images'] = {}; v['_meta'] = {}; x = run(r, v)
    a10 = x['summary']['unrecognized_checker_keys']; ok &= a10 == [] and x['summary']['licence_cross_family_reproduction_BLIND_tier']; print('A10 ignored keys', a10)
    r, v = make_p0(); v['R0']['U']['named']['I7']['designated_for'] = ['R0']; x = run(r, v); a11 = x['summary']['unrecognized_checker_keys']
    ok &= a11 == ['V1b/R0/U/named/I7/designated_for']; print('A11 unknown field recorded', a11)
    print('SELFTEST', 'PASS' if ok else 'FAIL')
    sys.exit(0 if ok else 1)


if __name__ == '__main__':
    main()

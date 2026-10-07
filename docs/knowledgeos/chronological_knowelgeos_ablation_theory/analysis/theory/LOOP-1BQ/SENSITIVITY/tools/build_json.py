import json, os, glob
base = json.load(open('base/SLICE-RESULTS.json'))
L = '/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/chronological_knowelgeos_ablation_theory/analysis/theory/LOOP-1BQ'
blindq = json.load(open(L + '/COMPARISON-WF-SONNET/SLICE-COMPARISON.json'))['distinct_blind_quantities']
def flat(d, p=''):
    out = {}
    for F in d:
        out[f'{F}/n_frame_histories'] = d[F]['n_frame_histories']
        for R, x in d[F]['readings'].items():
            for s, v in x['sizes'].items(): out[f'{F}/{R}/sizes/{s}'] = v
            for form, y in x.items():
                if form == 'sizes': continue
                for k, v in y.items(): out[f'{F}/{R}/{form}/{k}'] = v
    return out
fb = flat(base)
res = {}
for vd in sorted(glob.glob('variants/*/')):
    n = os.path.basename(vd.rstrip('/'))
    fv = flat(json.load(open(vd + 'SLICE-RESULTS.json')))
    cmpu = {u['unit']: u for u in json.load(open(f'cmp/{n}/SLICE-COMPARISON.json'))['units']}
    cal = json.load(open(f'cal/{n}.json'))
    ch, uch = {}, []
    for k in fb:
        if fb[k] != fv[k]:
            u = cmpu.get(k, {})
            entry = {'baseline': fb[k], 'variant': fv[k], 'tier_in_baseline': u.get('tier'), 'qid': u.get('qid')}
            if k.startswith('U_P2/'): ch[k] = entry
            else: uch.append({'unit': k, 'baseline': fb[k], 'variant': fv[k]})
    blind_ch = {k: [v['baseline'], v['variant']] for k, v in ch.items() if v['tier_in_baseline'] == 'BLIND'}
    other_ch = {k: [v['baseline'], v['variant']] for k, v in ch.items() if v['tier_in_baseline'] != 'BLIND'}
    bq = sorted({ch[k]['qid'] for k in blind_ch})
    res[n] = {'sha256_results': None, 'changed_units': blind_ch,
              'changed_blind_quantity_ids': bq,
              'changed_U_P2_nonblind_units': other_ch,
              'u_frame_changes': uch,
              'u_frame_vs_sealed_V1b_mismatches': [m['unit'] for m in cal['mismatches']]}
import hashlib
for n in res:
    res[n]['sha256_results'] = hashlib.sha256(open(f'variants/{n}/SLICE-RESULTS.json','rb').read()).hexdigest()
sens = sorted({q for n, r in res.items() for q in r['changed_blind_quantity_ids']})
out = {'baseline': {'program': 'LOOP-1BQ/CLAUDE-SIDE/slice_enum.py (copied unmodified)', 'sha256_results': hashlib.sha256(open('base/SLICE-RESULTS.json','rb').read()).hexdigest(), 'reproduces_sealed_cc6e635f': True},
       'variants': res,
       'n_distinct_blind_quantities': len(blindq),
       'interpretation_sensitive_blind_quantities_any_variant': sens,
       'interpretation_sensitive_blind_quantities_U_consistent_variants_only': sorted({q for n, r in res.items() if not r['u_frame_vs_sealed_V1b_mismatches'] for q in r['changed_blind_quantity_ids']}),
       'interpretation_robust_blind_quantities': [q for q in blindq if q not in sens]}
json.dump(out, open('SENSITIVITY.json', 'w'), indent=1, default=str)
for n, r in res.items():
    print(n, 'BLIND:', json.dumps(r['changed_units'], default=str)[:600]); print('   nonblind U_P2:', json.dumps(r['changed_U_P2_nonblind_units'], default=str)[:1500]); print('   U changes:', len(r['u_frame_changes']), 'V1b mismatches', len(r['u_frame_vs_sealed_V1b_mismatches']))
print(sens)

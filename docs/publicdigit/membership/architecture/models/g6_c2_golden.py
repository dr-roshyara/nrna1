"""G-6 golden fixture for the C-2 fragment (PBDIGIT-327, COR-2 plan steps 1-2). Research / test support only.

Runs the UNCHANGED g6 oracle (g6_correction_oracle.py) on the full correction fragment, with the decisions in force:
  - candidates: Admit, End, Annul, Void, Correct (the oracle's own candidate order);
  - D-COR-2 (PO 2026-10-10): Annul / Void of a target that is not live is refused (TargetNotLive), after the
    oracle's structural guards (so N-3 Annul(Void) is reported first);
  - P-1 (PO 2026-11-03, m5): a Correct whose chain ROOT is voided is refused (CorrectOfVoidedFact), like a
    directly voided target (the oracle's own guard);
  - P-3: a Correct whose value equals the target's current value is refused (ValueUnchanged);
  - P-4: a Correct of an End (chain root End) to a value at or before its period's start, or of an Admit (chain
    root Admit) to a value at or after its period's live End, is refused (EndNotAfterPeriodStart);
  - BD-REV-ER = refuse (PO 2026-11-02): the oracle's refuse_absorption=True (PeriodAbsorbed);
  - P-5 (dating rules) is NOT modelled: the oracle has no decision date; values are finite and in 0..H-1.
  - R-2 holds trivially: every enumerated fact has decision provenance.
Line format "<facts>|<verdict>|<resolution>|<consequences of the decided fact>":
  facts:        A<pid>@<t>, E<pid>@<t>, N<i> (Annul of i), V<i> (Void of i), C<i>@<t> (Correct of i to t)
  verdict:      ok, or the kernel RefusalReason value (mapping below)
  resolution:   accepted only: "<pid>:<times in the lifetime>" per Admit in log order, joined by ";"; else "-"
  consequences: accepted only: "<pid>:<removed times>" per consequence of the new fact, joined by ";"
                (absolute times, the oracle's representation); "" when none; "-" for a refusal
Breadth first (depth, then frontier order, then candidate order). The fixture stores the per-depth counts, the
SHA-256 of the whole stream and the full listing up to LISTING_DEPTH.
Run: G6_C2_L=<depth> python3 -I docs/publicdigit/membership/architecture/models/g6_c2_golden.py \
       > tests/Unit/Contexts/Membership/Domain/Organisational/Fixtures/g6_c2_golden.json
"""
import hashlib, json, os

HERE = os.path.dirname(os.path.abspath(__file__))
ORACLE = os.path.join(HERE, 'g6_correction_oracle.py')
ns = {'__file__': ORACLE}
exec(open(ORACLE).read().split("if __name__ == '__main__':")[0], ns)
decide, live, root, value, admits, structural_refusal, candidates, resolve, period_admit, H, INF = (
    ns['decide'], ns['live'], ns['root'], ns['value'], ns['admits'], ns['structural_refusal'], ns['candidates'],
    ns['resolve'], ns['period_admit'], ns['H'], ns['INF'])
L = int(os.environ.get('G6_C2_L', '4'))
LISTING_DEPTH = 2
REFUSAL_MAP = {
    'PeriodNotFound': 'period_not_found',
    'PeriodAlreadyEnded': 'period_already_ended',
    'EndNotAfterPeriodStart': 'end_not_after_period_start',
    'N3_AnnulOfVoid': 'annul_of_void_refused',
    'N3_CorrectOfAnnulOrVoid': 'correction_of_annul_or_void_refused',
    'CorrectOfVoidedRefused': 'correct_of_voided_fact',
    'ValueUnchanged': 'value_unchanged',
    'TargetNotLive': 'target_not_live',
    'INV9_overlap': 'overlapping_membership_periods',
    'INV_EP_fragmented': 'period_lifetime_not_contiguous',
    'INV_COV': 'membership_coverage_lost',
    'BDREVER_absorption': 'period_absorbed',
}

def voided(S, i):
    return any(S[j].kind == 'Void' and S[j].target == i and live(S, j) for j in range(i + 1, len(S)))

def live_end_value(S, a):
    """the value of the live End of the period whose Admit is a, or None."""
    ends = [i for i in range(len(S)) if S[i].kind == 'End' and S[i].pid == S[a].pid and live(S, i)]
    return value(S, max(ends)) if ends else None

def c2_decide(S, f):
    r = structural_refusal(S, f)
    if r: return r, None
    if f.kind in ('Annul', 'Void') and not live(S, f.target): return 'TargetNotLive', None
    if f.kind == 'Correct':
        r0 = root(S, f.target)
        if voided(S, r0): return 'CorrectOfVoidedRefused', None                       # P-1
        if f.v == value(S, f.target): return 'ValueUnchanged', None                    # P-3
        if S[r0].kind == 'End':                                                        # P-4 (End side)
            if not value(S, period_admit(S, r0)) < f.v: return 'EndNotAfterPeriodStart', None
        else:                                                                          # P-4 (Admit side)
            e = live_end_value(S, r0)
            if e is not None and not f.v < e: return 'EndNotAfterPeriodStart', None
    return decide(S, f, refuse_absorption=True)

def token(f):
    if f.kind == 'Admit': return f'A{f.pid}@{f.v}'
    if f.kind == 'End': return f'E{f.pid}@{f.v}'
    if f.kind == 'Correct': return f'C{f.target}@{f.v}'
    return ('N' if f.kind == 'Annul' else 'V') + str(f.target)

def line(S, f, r, F):
    facts = ' '.join(token(x) for x in S + [f])
    if r: return f'{facts}|{REFUSAL_MAP[r]}|-|-'
    res = ';'.join(f'{pid}:' + ','.join(str(t) for t in sorted(l)) for pid, l in resolve(F).items())
    cons = ';'.join(f'{F[q].pid}:' + ','.join(str(t) for t in sorted(x)) for (q, x) in F[-1].cons)
    return f'{facts}|ok|{res}|{cons}'

digest = hashlib.sha256(); per_depth = []; listing = []; frontier = [[]]
for depth in range(1, L + 1):
    nxt = []; decisions = 0; refusals = {}
    for S in frontier:
        for f in candidates(S):
            r, F = c2_decide(S, f)
            text = line(S, f, r, F)
            digest.update((text + '\n').encode())
            if depth <= LISTING_DEPTH: listing.append(text)
            decisions += 1
            if r:
                key = REFUSAL_MAP[r]; refusals[key] = refusals.get(key, 0) + 1
            else:
                nxt.append(F)
    per_depth.append({'depth': depth, 'decisions': decisions, 'accepted': len(nxt),
                      'refusals': dict(sorted(refusals.items()))})
    frontier = nxt

print(json.dumps({
    'generator': f'G6_C2_L={L} python3 -I docs/publicdigit/membership/architecture/models/g6_c2_golden.py',
    'oracle': 'docs/publicdigit/membership/architecture/models/g6_correction_oracle.py',
    'oracle_sha256': hashlib.sha256(open(ORACLE, 'rb').read()).hexdigest(),
    'purpose': 'transcription fidelity of the PHP port (COR-2 plan steps 1-2); evidence, not proof',
    'H': H, 'L': L, 'infinity': INF,
    'restriction': 'C-2 fragment: D-COR-2 TargetNotLive; P-1 voided chain root; P-3 ValueUnchanged; P-4 End/Admit '
                   'ordering; BD-REV-ER refuse_absorption; P-5 not modelled; R-2 trivial',
    'line_format': '<facts>|<verdict>|<resolution>|<consequences of the decided fact>',
    'refusal_map': REFUSAL_MAP,
    'per_depth': per_depth,
    'stream_sha256': digest.hexdigest(),
    'listing_depth': LISTING_DEPTH,
    'listing': listing,
}, indent=1))

"""G-6 golden fixture for the C-1 fragment (PBDIGIT-268, plan step 10). Research / test support only.

Runs the UNCHANGED g6 oracle (g6_correction_oracle.py) restricted to the C-1 fragment:
  - candidates: Admit, End, Annul, Void (no Correct: that is C-2);
  - D-COR-2 (PO approved): Annul / Void of a target that is not live is refused (TargetNotLive),
    checked after the oracle's structural guards (so N-3 Annul(Void) is reported first);
  - R-2 holds trivially: every enumerated fact has decision provenance.
Every (S, f) decision reachable within H, L is serialised as one canonical line
  "<facts>|<verdict>|<resolution>"
  facts:      A<pid>@<t> (Admit), E<pid>@<t> (End), N<i> (Annul of fact index i), V<i> (Void of i)
  verdict:    ok, or the kernel RefusalReason value (mapping below)
  resolution: for accepted decisions, "<pid>:<times in the lifetime, comma separated>" per Admit in
              log order, joined by ";"; "-" for a refusal
in breadth-first order (depth, then frontier order, then candidate order). The fixture stores the
per-depth counts, the SHA-256 of the whole stream and the full listing up to depth 3.
Run: python3 -I docs/publicdigit/membership/architecture/models/g6_c1_golden.py \
       > tests/Unit/Contexts/Membership/Domain/Organisational/Fixtures/g6_c1_golden.json
"""
import hashlib, json, os

HERE = os.path.dirname(os.path.abspath(__file__))
ORACLE = os.path.join(HERE, 'g6_correction_oracle.py')
ns = {'__file__': ORACLE}
exec(open(ORACLE).read().split("if __name__ == '__main__':")[0], ns)
decide, live, structural_refusal, candidates, resolve, H = (
    ns['decide'], ns['live'], ns['structural_refusal'], ns['candidates'], ns['resolve'], ns['H'])
L = 5
LISTING_DEPTH = 3
REFUSAL_MAP = {
    'PeriodNotFound': 'period_not_found',
    'PeriodAlreadyEnded': 'period_already_ended',
    'EndNotAfterPeriodStart': 'end_not_after_period_start',
    'N3_AnnulOfVoid': 'annul_of_void_refused',
    'TargetNotLive': 'target_not_live',
    'INV9_overlap': 'overlapping_membership_periods',
    'INV_EP_fragmented': 'period_lifetime_not_contiguous',
    'INV_COV': 'membership_coverage_lost',
}

def c1_candidates(S):
    return [f for f in candidates(S) if f.kind != 'Correct']

def c1_decide(S, f):
    r = structural_refusal(S, f)
    if r: return r, None
    if f.kind in ('Annul', 'Void') and not live(S, f.target): return 'TargetNotLive', None
    return decide(S, f)

def token(f):
    if f.kind == 'Admit': return f'A{f.pid}@{f.v}'
    if f.kind == 'End': return f'E{f.pid}@{f.v}'
    return ('N' if f.kind == 'Annul' else 'V') + str(f.target)

def line(S, f, r, F):
    facts = ' '.join(token(x) for x in S + [f])
    if r: return f'{facts}|{REFUSAL_MAP[r]}|-'
    res = ';'.join(f'{pid}:' + ','.join(str(t) for t in sorted(l)) for pid, l in resolve(F).items())
    return f'{facts}|ok|{res}'

digest = hashlib.sha256(); per_depth = []; listing = []; frontier = [[]]
for depth in range(1, L + 1):
    nxt = []; decisions = 0; refusals = {}
    for S in frontier:
        for f in c1_candidates(S):
            r, F = c1_decide(S, f)
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
    'generator': 'python3 -I docs/publicdigit/membership/architecture/models/g6_c1_golden.py',
    'oracle': 'docs/publicdigit/membership/architecture/models/g6_correction_oracle.py',
    'oracle_sha256': hashlib.sha256(open(ORACLE, 'rb').read()).hexdigest(),
    'purpose': 'transcription fidelity of the PHP port (plan step 10); evidence, not proof',
    'H': H, 'L': L, 'infinity': H,
    'restriction': 'C-1 fragment: no Correct; D-COR-2 TargetNotLive after the structural guards; R-2 trivial',
    'line_format': '<facts>|<verdict>|<resolution>',
    'refusal_map': REFUSAL_MAP,
    'per_depth': per_depth,
    'stream_sha256': digest.hexdigest(),
    'listing_depth': LISTING_DEPTH,
    'listing': listing,
}, indent=1))

"""G-15 affiliation discovery at sign-in (INV-AFF-1 evaluation). Research only; print-only; standard library.

Commission: Principal decision 2026-11-01, "bounded discovery evaluation: YES (research model and documentation only)".
Report: docs/publicdigit/reviews/2026-11-01-identity-affiliation-discovery-evaluation.md

Designs compared
  L1      Identity STORES a mapping person_id -> (organisation, random PersonRef). Discovery = one lookup.
  L1-SH   one SHARED stored reference per person, the same in every organisation (rejected "option A"; baseline).
  L2      Identity stores NO person->organisation mapping. Each organisation's PersonRef is DERIVED:
          ref = HMAC(K_derive, person_id || organisation_id). Discovery = ask every organisation (fan-out).
          Merges keep an alias list (person_id -> earlier person_ids); it names no organisation.
  L3      no discovery: the person enters through an organisation-specific address or invitation.

Parts
  D1  fan-out cost and sign-in latency                       (seeded Monte Carlo; ASSUMED latency/availability)
  D2  completeness under outages: wrong automatic entry      (closed form; ASSUMED availability)
  D3  narrowing with a remembered Working Organisation (B1.5) (closed form; ASSUMED share r)
  D5  identity-assertion verifier rules vs attack cases      (exhaustive truth table)
  D6  import / register / claim / merge sequences            (exhaustive enumeration; all designs)
  D7  derivation-key rotation cost                           (closed form; ASSUMED population)
  (D4 persistence sinks and D8 failure modes are analysed in the report, not modelled.)

All numeric inputs marked ASSUMED are illustrative. NOTHING here is a measured production rate.
Run: python3 -I docs/publicdigit/membership/architecture/models/g15_affiliation_discovery.py
"""
import heapq
import itertools
import json
import math
import random

out = {}

# ------------------------------------------------------------------------------------------- D1
# ASSUMED: per-organisation lookup latency lognormal, median 10 ms, sigma 0.8 (p99 ~ 64 ms);
# per-organisation unavailability 0.1 %; timeout 1 s; at most 32 concurrent lookups per sign-in;
# platform-wide budget of 200 concurrently open organisation-database connections.
def d1(n_orgs, seed, trials=400, median=0.010, sigma=0.8, p_down=0.001, timeout=1.0, conc=32, budget=200):
    rng = random.Random(seed)
    spans, conn_secs, unknown_any = [], [], 0
    for _ in range(trials):
        durs, unknown = [], 0
        for _ in range(n_orgs):
            if rng.random() < p_down:
                durs.append(timeout); unknown += 1
            else:
                d = rng.lognormvariate(math.log(median), sigma)
                if d > timeout:
                    durs.append(timeout); unknown += 1
                else:
                    durs.append(d)
        workers = [0.0] * min(conc, n_orgs)
        heapq.heapify(workers)
        for d in durs:
            heapq.heappush(workers, heapq.heappop(workers) + d)
        spans.append(max(workers))
        conn_secs.append(sum(durs))
        unknown_any += unknown > 0
    spans.sort(); conn_secs.sort()
    mean_cs = sum(conn_secs) / trials
    return dict(signin_latency_p50_s=round(spans[trials // 2], 3),
                signin_latency_p95_s=round(spans[int(trials * 0.95)], 3),
                connection_seconds_per_signin_mean=round(mean_cs, 3),
                max_signins_per_second_at_budget=round(budget / mean_cs, 1),
                share_of_signins_with_at_least_one_unknown=round(unknown_any / trials, 3))


out['D1_fanout_cost_L2'] = {
    'assumptions': 'ASSUMED median 10 ms, sigma 0.8, p_down 0.1 %, timeout 1 s, 32 parallel, budget 200 connections',
    **{f'N={n}': d1(n, seed=20261101 + n) for n in (10, 100, 1000, 5000)},
    'L1_for_comparison': 'one central lookup + k organisation checks (k = the person\'s affiliations)',
}

# ------------------------------------------------------------------------------------------- D2
# A person with k real affiliations among N organisations; each organisation answers with probability a
# (independent) or all fail together (one shared cluster). Policies:
#   P-AUTO      enter automatically when exactly one "yes" arrives, whatever is missing (violates B1.4 when wrong)
#   P-COMPLETE  enter automatically only when every organisation answered; otherwise show what was found + notice
def d2(n, k, a):
    p_complete = a ** n
    # P-AUTO is wrong when k >= 2 but exactly one of the k affiliated organisations answered
    p_wrong_auto = (k * a * (1 - a) ** (k - 1)) if k >= 2 else 0.0
    return dict(P_COMPLETE_degraded_share=round(1 - p_complete, 4),
                P_AUTO_wrong_entry_share=round(p_wrong_auto, 6))


out['D2_completeness'] = {
    'independent_failures': {f'N={n},a={a}': d2(n, 2, a)
                             for n in (10, 100, 1000, 5000) for a in (0.9999, 0.999, 0.99)},
    'single_shared_cluster': 'all organisations answer or none: P-COMPLETE degraded share = cluster outage rate, '
                             'independent of N; P-AUTO wrong entries = 0',
    'person_has': 'k = 2 real affiliations',
}

# ------------------------------------------------------------------------------------------- D3
# r = share of sign-ins with a remembered Working Organisation (B1.5) that is still valid: 1 lookup; else N.
out['D3_narrowing_remembered_WO'] = {
    f'N={n}': {f'r={r}': round(r * 1 + (1 - r) * n, 1) for r in (0.0, 0.5, 0.8, 0.95)}
    for n in (100, 1000, 5000)
}
out['D3_note'] = ('expected lookups per sign-in. Valid only if B1.5 (remembered choice) takes precedence over '
                  'B1.3 (count real organisations) - an OPEN business reading. A device hint cannot prove '
                  '"exactly one organisation", so it narrows but never authorises automatic entry under B1.4.')

# ------------------------------------------------------------------------------------------- D5
CASES = {
    'valid':               dict(sig=True,  aud='A', exp=False, nonce_fresh=True,  key='current'),
    'replayed_to_other':   dict(sig=True,  aud='B', exp=False, nonce_fresh=True,  key='current'),
    'expired':             dict(sig=True,  aud='A', exp=True,  nonce_fresh=True,  key='current'),
    'bare_ref_no_sig':     dict(sig=False, aud=None, exp=False, nonce_fresh=True, key=None),
    'replayed_same_org':   dict(sig=True,  aud='A', exp=False, nonce_fresh=False, key='current'),
    'retired_signing_key': dict(sig=True,  aud='A', exp=False, nonce_fresh=True,  key='retired'),
}
VERIFIERS = {
    'ref_lookup_only':  lambda c: True,                                    # "do we hold this ref?"
    'sig_only':         lambda c: c['sig'] and c['key'] != 'retired',
    'sig_aud_exp':      lambda c: c['sig'] and c['key'] != 'retired' and c['aud'] == 'A' and not c['exp'],
    'full':             lambda c: c['sig'] and c['key'] != 'retired' and c['aud'] == 'A' and not c['exp']
                                  and c['nonce_fresh'],
}
out['D5_assertion_verifier'] = {
    v: {'accepted_bad_cases': [n for n, c in CASES.items() if n != 'valid' and f(c)],
        'rejects_valid': not f(CASES['valid'])}
    for v, f in VERIFIERS.items()
}

# ------------------------------------------------------------------------------------------- D6
# Two organisations A, B; one human. Events (each at most once, in any order, any subset):
#   IMP_A / IMP_B : organisation imports the human -> Identity creates a Person (eager, BR-10)
#   REG           : the human registers -> a new Person for the account if no claim has bound one yet
#   CLM_A / CLM_B : the signed-in account redeems the organisation's claim code (needs REG and IMP_x first);
#                   if the code's Person differs from the account's Person -> explicit merge
# Measured at the end of every sequence:
#   org_writes_for_merge   organisation-database writes caused by merges (want 0)
#   central_org_facts      persisted central rows that name an organisation for this human (INV-AFF-1 wants 0)
#   discovery_correct      sign-in discovers exactly the claimed organisations
#   fanout_multiplier      L2: derivations per organisation at sign-in (1 + aliases)
#   persons_live           Person records still live (after merges) - 1 is ideal once everything is claimed
EVENTS = ['IMP_A', 'IMP_B', 'REG', 'CLM_A', 'CLM_B']


def run_d6(seq, design):
    nxt = itertools.count(1)
    imported = {}           # org -> person_id the org's record points at (L1/L2: via ref; L1-SH: shared ref value)
    account_person = None
    alias = {}              # merged-away person -> survivor (L2 alias list; L1 mapping re-point)
    claimed = set()
    org_writes = 0
    central_rows = set()    # (person, org) facts persisted centrally

    def root(p):
        while p in alias:
            p = alias[p]
        return p

    for e in seq:
        if e.startswith('IMP_'):
            org = e[-1]
            if org in imported:
                continue
            p = next(nxt)
            imported[org] = p
            if design in ('L1', 'L1-SH'):
                central_rows.add((p, org))          # stored mapping / shared ref registry
        elif e == 'REG':
            if account_person is None:
                account_person = next(nxt)
        elif e.startswith('CLM_'):
            org = e[-1]
            if account_person is None or org not in imported or org in claimed:
                continue
            p_code = root(imported[org])
            p_acc = root(account_person)
            if p_code != p_acc:                       # explicit Identity merge (C-5): p_code -> p_acc
                alias[p_code] = p_acc
                if design == 'L1':
                    central_rows = {(root(p), o) for (p, o) in central_rows}   # re-point: central only
                elif design == 'L1-SH':
                    for o, p in imported.items():     # every org storing the losing shared ref must rewrite
                        if root(p) == p_acc and p != p_acc:
                            org_writes += 1
                            imported[o] = p_acc
                    central_rows = {(root(p), o) for (p, o) in central_rows}
                # L2: nothing outside Identity changes; the org's ref stays derived from its original person_id
            claimed.add(org)
            if design in ('L1', 'L1-SH'):
                central_rows.add((root(imported[org]), org))

    # discovery at sign-in for the account
    if account_person is None:
        discovered = set()
    elif design in ('L1', 'L1-SH'):
        r = root(account_person)
        discovered = {o for (p, o) in central_rows if root(p) == r} & claimed
    elif design == 'L2':
        r = root(account_person)
        ids = {p for p in list(alias) + [account_person] if root(p) == r} | {r}
        # ask every org: does it hold HMAC(K, pid, org) for any pid in ids? It does iff its record's person is in ids
        discovered = {o for o, p in imported.items() if p in ids} & claimed
    else:  # L3
        discovered = set()
    persons_live = len({root(p) for p in list(imported.values()) + ([account_person] if account_person else [])})
    aliases_of_root = sum(1 for p in alias if root(p) == root(account_person)) if account_person else 0
    return dict(org_writes=org_writes,
                central_org_facts=len(central_rows),
                discovery_correct=(discovered == claimed),
                fanout_multiplier=(1 + aliases_of_root) if design == 'L2' else (0 if design == 'L3' else 1),
                persons_live=persons_live,
                merges=len(alias))


d6 = {}
for design in ('L1', 'L1-SH', 'L2', 'L3'):
    agg = dict(sequences=0, sequences_with_org_writes=0, max_org_writes=0, sequences_with_central_org_facts=0,
               discovery_incorrect=0, max_fanout_multiplier=0, sequences_with_merge=0,
               fully_claimed_sequences=0, fully_claimed_but_persons_live_gt_1=0)
    for L in range(1, len(EVENTS) + 1):
        for seq in itertools.permutations(EVENTS, L):
            r = run_d6(seq, design)
            agg['sequences'] += 1
            agg['sequences_with_org_writes'] += r['org_writes'] > 0
            agg['max_org_writes'] = max(agg['max_org_writes'], r['org_writes'])
            agg['sequences_with_central_org_facts'] += r['central_org_facts'] > 0
            agg['discovery_incorrect'] += not r['discovery_correct']
            agg['max_fanout_multiplier'] = max(agg['max_fanout_multiplier'], r['fanout_multiplier'])
            agg['sequences_with_merge'] += r['merges'] > 0
            full = {'CLM_A', 'CLM_B'} <= set(seq) and {'IMP_A', 'IMP_B', 'REG'} <= set(seq) \
                and all(seq.index(f'CLM_{o}') > max(seq.index('REG'), seq.index(f'IMP_{o}')) for o in 'AB')
            if full:
                agg['fully_claimed_sequences'] += 1
                agg['fully_claimed_but_persons_live_gt_1'] += r['persons_live'] > 1
    d6[design] = agg
out['D6_import_register_claim_merge'] = d6

# ------------------------------------------------------------------------------------------- D7
# ASSUMED population: 100,000 persons, mean 1.3 organisation records each.
persons, mean_aff = 100_000, 1.3
out['D7_derivation_key_rotation'] = {
    'assumptions': 'ASSUMED 100,000 persons x 1.3 organisation records',
    'L1': 'no derivation key; random references; rotation cost 0. Risk moves to the stored mapping table.',
    'L1-SH': 'no derivation key; rotation cost 0. Shared reference is itself the correlation risk.',
    'L2_rewrite_all': f'{int(persons * mean_aff):,} organisation-database rewrites across all organisation databases',
    'L2_keep_old_versions': 'store key version with each reference; new references use the new key; old key '
                            'stays able to correlate old references for as long as they exist',
    'signing_key_note': 'the assertion SIGNING key (D5) rotates freely via a published key set; only the '
                        'DERIVATION key is costly to rotate',
}

print(json.dumps(out, indent=1))

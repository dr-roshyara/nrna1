"""G-6: executable oracle of the RATIFIED Membership correction algebra (research only).

Implements final-proof §14.2 definitions verbatim in spirit:
  live, root, active (whole chain live, §15.1), superseded, value, BaseClaim,
  frozen consequences cons(f) via g2 (trim policy, BD-REV), LiveCut, ResolvedLifetime,
and G* = accept iff INV-9 (pairwise-disjoint lifetimes) ∧ INV-EP (each lifetime one interval)
∧ INV-COV (∪ lifetime = ∪ BaseClaim) on the resolved post-state, plus the structural guards:
  - kernel guards for Admit/End (End needs a live, open period; End strictly after start);
  - act N-3 refusals: Annul(Void), Correct(Annul), Correct(Void); Correct of a voided target refused;
  - Correct targets Admit / End / Correct only; Correct(annulled target) accepted but inert (act §4).
Discrete valid time 0..H-1, ∞ represented as H. Purpose: an independent oracle for the
correction slice (ARB-MEM-01 gate) and evidence for BD-REV-ER. NOT production code.
Run: python3 -I docs/publicdigit/membership/architecture/models/g6_correction_oracle.py
"""
import itertools, json, os, subprocess, sys
H = 5                      # valid-time horizon; times 0..4, ∞ = 5
INF = H

class Fact:
    __slots__ = ('kind', 'target', 'pid', 'v', 'cons')
    def __init__(s, kind, target=None, pid=None, v=None):
        s.kind, s.target, s.pid, s.v, s.cons = kind, target, pid, v, ()
    def __repr__(s):
        return f"{s.kind}({'' if s.target is None else 't'+str(s.target)}{'' if s.pid is None else ' p'+str(s.pid)}{'' if s.v is None else ' v'+str(s.v)})"

def live(F, i):
    return not any(F[j].kind in ('Annul', 'Void') and F[j].target == i and live(F, j) for j in range(i + 1, len(F)))
def root(F, i):
    return i if F[i].kind != 'Correct' else root(F, F[i].target)
def active(F, i):
    return live(F, i) and (F[i].kind != 'Correct' or active(F, F[i].target))
def superseded(F, i):
    return any(F[j].kind == 'Correct' and F[j].target == i and live(F, j) for j in range(i + 1, len(F)))
def value(F, i):
    cs = [j for j in range(i + 1, len(F)) if F[j].kind == 'Correct' and F[j].target == i and live(F, j)]
    return value(F, max(cs)) if cs else F[i].v
def admits(F):
    return {i: F[i].pid for i in range(len(F)) if F[i].kind == 'Admit'}
def base_claim(F, a):
    """a = index of the period's Admit."""
    if not live(F, a): return frozenset()
    s = value(F, a)
    ends = [i for i in range(len(F)) if F[i].kind == 'End' and F[i].pid == F[a].pid and live(F, i)]
    e = max(s, value(F, max(ends))) if ends else INF
    return frozenset(range(s, e))
def live_cuts_on(F, q_admit):
    out = set()
    for i, f in enumerate(F):
        for (q, x) in f.cons:
            if q == q_admit and active(F, i) and not superseded(F, i):
                out |= set(x) & base_claim(F, root(F, i) if F[root(F, i)].kind == 'Admit' else period_admit(F, root(F, i)))
    return out
def period_admit(F, i):
    """the Admit index of the period a fact belongs to (via pid)."""
    pid = F[i].pid
    return next(k for k in range(len(F)) if F[k].kind == 'Admit' and F[k].pid == pid)
def target_period_admit(F, i):
    r = root(F, i)
    return r if F[r].kind == 'Admit' else period_admit(F, r)
def lifetime(F, a):
    return base_claim(F, a) - live_cuts_on(F, a)
def resolve(F):
    return {F[a].pid: lifetime(F, a) for a in admits(F)}
def claims(F):
    return {F[a].pid: base_claim(F, a) for a in admits(F)}

def contiguous(s):
    return not s or max(s) - min(s) + 1 == len(s)

def g2(S, f):
    """frozen consequences of a lifetime-defining fact f appended to S (trim policy)."""
    if f.kind not in ('End', 'Correct'): return ()
    SF = S + [f]
    p = target_period_admit(SF, len(S))
    delta = base_claim(SF, p) - base_claim(S, p) - live_cuts_on(S, p)
    cons = []
    for q in admits(S):
        if q == p: continue
        x = delta & lifetime(S, q)
        if x: cons.append((q, frozenset(x)))
    if f.kind == 'Correct':
        for (q, x) in S[f.target].cons:
            y = set(x) & base_claim(SF, p)
            if y: cons.append((q, frozenset(y)))
    return tuple(cons)

def structural_refusal(S, f):
    n = len(S)
    if f.kind == 'Admit':
        if any(S[i].kind == 'Admit' and S[i].pid == f.pid for i in range(n)): return 'DuplicatePeriod'
        return None
    if f.kind == 'End':
        a = next((i for i in range(n) if S[i].kind == 'Admit' and S[i].pid == f.pid), None)
        if a is None or not live(S, a): return 'PeriodNotFound'
        if any(S[i].kind == 'End' and S[i].pid == f.pid and live(S, i) for i in range(n)): return 'PeriodAlreadyEnded'
        if not value(S, a) < f.v: return 'EndNotAfterPeriodStart'
        return None
    t = S[f.target]
    if f.kind == 'Annul' and t.kind == 'Void': return 'N3_AnnulOfVoid'
    if f.kind == 'Correct':
        if t.kind in ('Annul', 'Void'): return 'N3_CorrectOfAnnulOrVoid'
        if any(S[j].kind == 'Void' and S[j].target == f.target and live(S, j) for j in range(n)): return 'CorrectOfVoidedRefused'
    return None

def invariant_violation(F, with_cov=True):
    R, C = resolve(F), claims(F)
    ls = list(R.values())
    for a, b in itertools.combinations(ls, 2):
        if a & b: return 'INV9_overlap'
    if not all(contiguous(l) for l in ls): return 'INV_EP_fragmented'
    if with_cov and set().union(*ls) != set().union(*C.values()) if ls else False: return 'INV_COV'
    return None

def decide(S, f, with_cov=True, refuse_absorption=False):
    r = structural_refusal(S, f)
    if r: return r, None
    if f.kind in ('Annul', 'Void', 'Correct'):
        f.pid = S[root(S, f.target)].pid if S[root(S, f.target)].pid is not None else None
    f.cons = g2(S, f)
    F = S + [f]
    v = invariant_violation(F, with_cov)
    if v: return v, None
    if refuse_absorption:   # BD-REV-ER option (b): a correction may not absorb a whole live period
        for a in admits(F):
            if live(F, a) and base_claim(F, a) and not lifetime(F, a): return 'BDREVER_absorption', None
    return None, F

# ---------- enumeration ----------------------------------------------------------------------
def candidates(S):
    n = len(S); pids = [S[i].pid for i in admits(S)]
    nxt = max(pids, default=-1) + 1
    out = [Fact('Admit', pid=nxt, v=t) for t in range(H)]
    out += [Fact('End', pid=p, v=t) for p in pids for t in range(1, H + 1) if t < INF]
    for i in range(n):
        out += [Fact('Annul', target=i), Fact('Void', target=i)]
        out += [Fact('Correct', target=i, v=t) for t in range(H)]   # incl. Annul/Void targets: exercises the N-3 guard
    return out

def enumerate_logs(L, **kw):
    frontier = [[]]; accepted = 0; refusals = {}; logs = []
    for _ in range(L):
        nxt = []
        for S in frontier:
            for f in candidates(S):
                r, F = decide(S, f, **kw)
                if r: refusals[r] = refusals.get(r, 0) + 1
                else: nxt.append(F); accepted += 1; logs.append(F)
        frontier = nxt
    return logs, refusals

if __name__ == '__main__':
    L = int(os.environ.get('G6_L', '4'))
    logs, ref = enumerate_logs(L)
    rep = {'H': H, 'L': L, 'accepted_logs': len(logs), 'refusals': ref}
    # Q3: does INV-COV ever fire? (published claim: the safety net never fires)
    logs_nc, ref_nc = enumerate_logs(L, with_cov=False)
    rep['INV_COV_safety_net_fires'] = sum(1 for F in logs_nc if invariant_violation(F, True) == 'INV_COV')
    rep['refusals_identical_with_without_COV'] = ({k: v for k, v in ref.items() if k != 'INV_COV'} == ref_nc)
    # Q2: Annul(Annul) restores: Res(F·annul(x)·annul(annul)) == Res(F·x)?  (subject to G*)
    mism = tot = 0
    for F in logs:
        last = len(F) - 1
        if F[last].kind == 'Annul' and F[F[last].target].kind == 'Annul' and F[last].target == last - 1:
            tot += 1
            if resolve(F) != resolve(F[:-2]): mism += 1
    rep['annul_annul_restores'] = f'{tot - mism}/{tot}'
    # Q4: BD-REV-ER absorption
    logs_b, ref_b = enumerate_logs(L, refuse_absorption=True)
    rep['BDREVER'] = {'accepted_allow': len(logs), 'accepted_refuse': len(logs_b),
                      'absorption_refusals': ref_b.get('BDREVER_absorption', 0)}
    ex = next((F for F in logs if any(live(F, a) and base_claim(F, a) and not lifetime(F, a) for a in admits(F))), None)
    rep['BDREVER_minimal_example'] = repr(ex)
    print(json.dumps(rep, indent=1))

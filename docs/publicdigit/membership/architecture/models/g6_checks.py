"""G-6 checks: (A) oracle vs the REAL Slice 0 kernel on the Admit/End fragment;
(B) remedy coverage: can every single-fact recording error in an Admit/End history be repaired
by ONE ratified operation (Correct / Void / Annul), reproducing the intended resolution?
Research only. Run: python3 -I docs/publicdigit/membership/architecture/models/g6_checks.py"""
import json, os, subprocess, copy, collections
HERE = os.path.dirname(os.path.abspath(__file__)); ROOT = os.path.abspath(os.path.join(HERE, *['..'] * 5))
ns = {'__file__': os.path.join(HERE, 'g6_correction_oracle.py')}
exec(open(ns['__file__']).read().split("if __name__ == '__main__':")[0], ns)
Fact, decide, resolve, candidates, H = ns['Fact'], ns['decide'], ns['resolve'], ns['candidates'], ns['H']

def membership(F):
    """semantic comparison: periods with non-empty lifetime (a voided period has none)."""
    return {p: l for p, l in resolve(F).items() if l}
def ae_only(F): return all(f.kind in ('Admit', 'End') for f in F)
def replay(seq):
    S = []
    for f in seq:
        r, F = decide(S, copy.copy(f))
        if r: return r, S
        S = F
    return None, S

# (A) all Admit/End-only (S, f) decision pairs with |S| <= 3
pairs, frontier = [], [[]]
for _ in range(4):
    nxt = []
    for S in frontier:
        for f in candidates(S):
            if f.kind not in ('Admit', 'End'): continue
            r, F = decide(S, f)
            pairs.append((S, f, r, F))
            if not r: nxt.append(F)
    frontier = nxt
def to_ops(seq):
    ops = []
    for k, f in enumerate(seq):
        if f.kind == 'Admit': ops.append(dict(k='admit', id=f'A{f.pid}', at=f.v, rec=k))
        else: ops.append(dict(k='end', id=f'E{f.pid}_{k}', period=f'A{f.pid}', at=f.v, rec=k))
    return ops
hist = [to_ops(S + [f]) for (S, f, r, F) in pairs]
out = subprocess.run(['php', os.path.join(HERE, 'kernel_admissibility.php'), str(H)], cwd=ROOT,
                     input=json.dumps(hist), capture_output=True, text=True, check=True).stdout.splitlines()
agree = dis = 0; examples = []
for (S, f, r, F), line in zip(pairs, out):
    k = json.loads(line)
    if (r is None) != k['admissible']:
        dis += 1; examples.append((repr(S + [f]), r, k)); continue
    if r is None:
        R = resolve(F); mem = [any(t in l for l in R.values()) for t in range(H)]
        if mem != k['member']: dis += 1; examples.append((repr(F), 'member-mismatch', k)); continue
    agree += 1
A = dict(pairs=len(pairs), agree=agree, disagree=dis, examples=examples[:3])

# (B) remedy coverage over accepted Admit/End histories (|F| <= 3)
logs = [F for (S, f, r, F) in pairs if r is None]
res = collections.Counter(); unrem = []
for F in logs:
    for i, fi in enumerate(F):
        # wrong value: intended G has F[i].v = w
        for w in range(H):
            if w == fi.v: continue
            G = [copy.copy(x) for x in F]; G[i] = Fact(fi.kind, pid=fi.pid, v=w)
            rg, Gs = replay(G)
            if rg: continue                                   # intended history itself invalid
            r, Fx = decide(F, Fact('Correct', target=i, v=w))
            key = f'wrong {fi.kind} value'
            if not r and membership(Fx) == membership(Gs): res[key + ': Correct OK'] += 1
            else:
                res[key + f': NOT remedied ({r or "different resolution"})'] += 1
                unrem.append((repr(F), i, w, r))
        # spurious fact: intended G = F without fact i (and without its dependants: Ends of a removed Admit)
        G = [copy.copy(x) for k, x in enumerate(F) if k != i and not (fi.kind == 'Admit' and x.kind == 'End' and x.pid == fi.pid)]
        rg, Gs = replay(G)
        if rg: continue
        for op in ('Void', 'Annul'):
            r, Fx = decide(F, Fact(op, target=i))
            key = f'spurious {fi.kind}: {op}'
            res[key + (' OK' if not r and membership(Fx) == membership(Gs) else f' NOT ({r or "different resolution"})')] += 1
B = dict(histories=len(logs), outcomes=dict(sorted(res.items())), unremedied_examples=unrem[:3])
print(json.dumps(dict(A_kernel_crosscheck=A, B_remedy_coverage=B), indent=1, default=str))

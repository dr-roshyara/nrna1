"""Exhaustive bounded checks of ALM-r1 against the independent oracle, plus seeded random tests.
These are EMPIRICAL results within the stated bounds; proofs are in ALM-R1-SPEC.md S-10.

Bounded alphabet (13 templates, fixed ids, ord = template index):
  gRa  GRANT R->a scope {(*,*)}            gRaF GRANT R->a scope {(*,*)} period [tau,tau+1]
  gRb  GRANT R->b scope PB                 gRbP GRANT R->b scope PB, proviso
  gab  GRANT a->b scope PB, bars (iv,p1,*) rRa  REVOKE R -> gRa
  rab  REVOKE a -> gab                     cRbP CONFIRM R -> gRbP
  Ab   ADOPT b subject s                   xa   END a -> Ab
  ib   PERFORM b implement w (p1)          vb   PERFORM b verify w (p1)
  acc  ACCEPT b w (performer p2)
Histories: every subset of size 1..3 x tau in {1,2,3}^n  (8,463 histories).
Run: python3 -B tests_r1_exhaustive.py   (writes run-exhaustive.json)
"""
import json, random, sys, time
from itertools import combinations, permutations, product
from alm_r1 import Act, Theta, History, Entry, rec, INF
from oracle_r1 import Oracle, to_dict, theta_dict

ROOTSET = frozenset({("R", ("*", "*"))})
ALLS = frozenset({("*", "*")})
PB = frozenset({("PERFORM", ("implement", "w")), ("PERFORM", ("verify", "w")), ("ADOPT", "s"),
                ("ACCEPT", "w")})


def TEMPLATES(t):
    return [
        Act("gRa", "GRANT", "R", tau=t, ord=0, grantee="a", scope=ALLS),
        Act("gRaF", "GRANT", "R", tau=t, ord=1, grantee="a", scope=ALLS, period=(t, t + 1)),
        Act("gRb", "GRANT", "R", tau=t, ord=2, grantee="b", scope=PB),
        Act("gRbP", "GRANT", "R", tau=t, ord=3, grantee="b", scope=PB, proviso=True),
        Act("gab", "GRANT", "a", tau=t, ord=4, grantee="b", scope=PB, bars=frozenset({("iv", "p1", "*")})),
        Act("rRa", "REVOKE", "R", tau=t, ord=5, target="gRa"),
        Act("rab", "REVOKE", "a", tau=t, ord=6, target="gab"),
        Act("cRbP", "CONFIRM", "R", tau=t, ord=7, target="gRbP"),
        Act("Ab", "ADOPT", "b", tau=t, ord=8, subject="s"),
        Act("xa", "END", "a", tau=t, ord=9, target="Ab"),
        Act("ib", "PERFORM", "b", performer="p1", tau=t, ord=10, activity="implement", work="w"),
        Act("vb", "PERFORM", "b", performer="p1", tau=t, ord=11, activity="verify", work="w"),
        Act("acc", "ACCEPT", "b", performer="p2", tau=t, ord=12, work="w"),
    ]


N_T = len(TEMPLATES(1))


def histories(max_n=3, taus=(1, 2, 3)):
    for n in range(1, max_n + 1):
        for idx in combinations(range(N_T), n):
            for ts in product(taus, repeat=n):
                yield [TEMPLATES(t)[i] for i, t in zip(idx, ts)]


def theta(**kw):
    return Theta(root=ROOTSET, **kw)


VALIDITY_GRID = [dict(C1="b", RD1={"decision": "persist"}, delta=d, BASIS="any", C10=c, iota_g=ig,
                      iota_c="closed", C6="off", BAR="any")
                 for d in ("independent", "dependent") for c in ("given", "no_same_instant")
                 for ig in ("closed", "half_open")]
CURRENCY_GRID = [dict(C1="b", RD1={"decision": rd}, delta="independent", BASIS=bs, C10="given",
                      iota_g="closed", iota_c=ic, C6=c6, BAR="any")
                 for rd in ("persist", "cascade") for bs in ("any", "all")
                 for ic in ("closed", "half_open") for c6 in ("off", "later_prevails")]

R = {}


def th1_and_currency():
    n = dis = nonuniq = cur_n = cur_dis = 0
    first_dis = None
    for H in histories():
        for cfg in VALIDITY_GRID:
            th = theta(**cfg)
            imp = History(H, th)
            orc = Oracle([to_dict(a) for a in H], theta_dict(th))
            fps = orc.fixed_points()
            n += 1
            if len(fps) != 1:
                nonuniq += 1
                continue
            if any(fps[0][a.id] != imp.valid(a.id) for a in H):
                dis += 1
                first_dis = first_dis or ([a.id for a in H], [a.tau for a in H], cfg)
        if any(a.kind == "ADOPT" for a in H):
            for cfg in CURRENCY_GRID:
                th = theta(**cfg)
                imp = History(H, th)
                orc = Oracle([to_dict(a) for a in H], theta_dict(th))
                V = orc.fixed_points()[0]
                for t in (1, 2, 3, 4):
                    cur_n += 1
                    if imp.current("Ab", t) != orc.current(V, "Ab", t):
                        cur_dis += 1
                        first_dis = first_dis or ("current", [a.id for a in H], [a.tau for a in H], cfg, t)
    R["Th1_uniqueness_and_oracle_agreement"] = dict(checks=n, nonunique=nonuniq, disagreements=dis)
    R["currency_oracle_agreement"] = dict(checks=cur_n, disagreements=cur_dis)
    R["first_disagreement"] = repr(first_dis)


def th2_locality():
    bad = n = 0
    later = [Act("zz1", "REVOKE", "R", tau=4, ord=0, target="gRa"),
             Act("zz2", "REVOKE", "R", tau=4, ord=1, target="gRb"),
             Act("zz3", "END", "R", tau=4, ord=2, target="Ab"),
             Act("zz4", "GRANT", "R", tau=4, ord=3, grantee="b", scope=PB)]
    for H in histories():
        for cfg in VALIDITY_GRID:
            th = theta(**cfg)
            base = History(H, th)
            for z in later:
                ext = History(H + [z], th)
                n += 1
                if any(ext.valid(a.id) != base.valid(a.id) for a in H) or \
                        (any(a.id == "Ab" for a in H) and any(ext.current("Ab", t) != base.current("Ab", t)
                                                                for t in (1, 2, 3))):
                    bad += 1
    R["Th2_locality"] = dict(checks=n, violations=bad)


def th3_th4():
    """All recording orders (permutations) of every history; prefixes k = 0..n."""
    th = theta(**VALIDITY_GRID[0])
    n = sound_viol = 0
    flips = {"T->F": 0, "T->U": 0, "F->T": 0, "U->T": 0, "U->F": 0}
    minimal = {}
    for H in histories():
        V = History(H, th)
        for perm in permutations(H):
            E = [Entry(i + 1, a) for i, a in enumerate(perm)]
            for e in H:
                prev = {0: None, INF: None}
                for k in range(len(H) + 1):
                    comp = all(any(en.act.id == f.id and en.pos <= k for en in E) for f in H if f.tau <= e.tau)
                    for ln in (0, INF):
                        r = rec(E, k, ("valid", e.id), th, now=100, lam_pos=0, lam_neg=ln)
                        if comp and any(en.act.id == e.id and en.pos <= k for en in E):
                            n += 1
                            want = "T" if V.valid(e.id) else ("F" if ln == 0 else "U")
                            if r != want:
                                sound_viol += 1
                        p = prev[ln]
                        if p and p != r and not (p == "U" and r == "U"):
                            key = f"{p}->{r}"
                            if key in flips:
                                flips[key] += 1
                                if key not in minimal or len(H) < len(minimal[key][0]):
                                    minimal[key] = ([(a.id, a.tau) for a in H],
                                                    [a.id for a in perm], e.id, k, ln)
                        prev[ln] = r
    R["Th3_soundness_under_completeness"] = dict(checks=n, violations=sound_viol)
    R["Th4_flips_lam_pos0"] = flips
    R["Th4_minimal_examples"] = {k: repr(v) for k, v in minimal.items()}


def th5_defeasibility():
    """For every invalid act: does a late completion (root grant to its holder, earliest) make it
    valid? Compare with the spec's characterization of non-defeasible negatives."""
    th = theta(**VALIDITY_GRID[0])
    rows = []
    for H in histories():
        V = History(H, th)
        for e in H:
            if V.valid(e.id) or e.holder == "R":
                continue
            # completion family: (1) root grant of everything; (2) root grant of exactly Need(e).
            # (2) was added after (1) alone produced 190 false "non-defeasible" cases (ACCEPT after an
            # unauthorized implementation: granting everything also validates the implementation and
            # triggers IND-1). Recorded in ALM-R1-RESULTS.md.
            stars = [Act("g_star", "GRANT", "R", tau=0, ord=-1, grantee=e.holder, scope=ALLS)]
            if V.form(e):
                stars.append(Act("g_star", "GRANT", "R", tau=0, ord=-1, grantee=e.holder,
                                 scope=frozenset(V.need(e))))
            defeasible = False
            for star in stars:
                fp = Oracle([to_dict(a) for a in H + [star]], theta_dict(th)).fixed_points()[0]
                defeasible = defeasible or fp[e.id]
            form_fail = not V.form(e)
            restr = V.restr(e)
            rows.append(dict(kind=e.kind, form_fail=form_fail, restr=restr,
                             recorded_revocation=any(x.kind == "REVOKE" for x in H),
                             defeasible=defeasible))
    pred_ok = sum((not r["defeasible"]) == (r["form_fail"] or r["restr"]) for r in rows)
    R["Th5_defeasibility"] = dict(invalid_acts=len(rows),
                                  defeasible=sum(r["defeasible"] for r in rows),
                                  characterization_matches=pred_ok,
                                  mismatches=len(rows) - pred_ok,
                                  with_recorded_revocation_defeasible=sum(
                                      r["defeasible"] for r in rows if r["recorded_revocation"]))
    return rows


def pr10_c1_incomparable():
    """C1=a vs C1=b on the same history, record times rho in {None, tau, tau+2} per act."""
    a_not_b = b_not_a = n = 0
    ex = {}
    for H in histories(max_n=3):
        for rh in product((None, 0, 2), repeat=len(H)):
            rho = {a.id: (None if d is None else a.tau + d) for a, d in zip(H, rh)}
            cb = dict(VALIDITY_GRID[0])
            ca = dict(cb, C1="a")
            Hb, Ha = History(H, theta(**cb), rho), History(H, theta(**ca), rho)
            for e in H:
                n += 1
                vb, va = Hb.valid(e.id), Ha.valid(e.id)
                if va and not vb:
                    a_not_b += 1
                    ex.setdefault("a_not_b", ([(x.id, x.tau, rho[x.id]) for x in H], e.id))
                if vb and not va:
                    b_not_a += 1
                    ex.setdefault("b_not_a", ([(x.id, x.tau, rho[x.id]) for x in H], e.id))
    R["Pr10_C1_incomparability"] = dict(checks=n, valid_a_not_b=a_not_b, valid_b_not_a=b_not_a,
                                        first_examples={k: repr(v) for k, v in ex.items()})


def random_property(seed=20261018, trials=3000, n_min=4, n_max=6):
    """Larger histories, random configs; oracle vs implementation; delta-debug minimization."""
    rnd = random.Random(seed)
    dis = []
    for _ in range(trials):
        n = rnd.randint(n_min, n_max)
        idx = rnd.sample(range(N_T), n)
        H = [TEMPLATES(rnd.randint(1, 4))[i] for i in idx]
        cfg = rnd.choice(VALIDITY_GRID)
        th = theta(**cfg)
        imp = History(H, th)
        fps = Oracle([to_dict(a) for a in H], theta_dict(th)).fixed_points()
        if len(fps) != 1 or any(fps[0][a.id] != imp.valid(a.id) for a in H):
            # delta debugging: drop acts while disagreement persists
            cur = list(H)
            changed = True
            while changed:
                changed = False
                for i in range(len(cur)):
                    cand = cur[:i] + cur[i + 1:]
                    if not cand:
                        continue
                    f2 = Oracle([to_dict(a) for a in cand], theta_dict(th)).fixed_points()
                    i2 = History(cand, th)
                    if len(f2) != 1 or any(f2[0][a.id] != i2.valid(a.id) for a in cand):
                        cur, changed = cand, True
                        break
            dis.append(([(a.id, a.tau) for a in cur], cfg))
    R["random_property"] = dict(seed=seed, trials=trials, sizes=[n_min, n_max], disagreements=len(dis),
                                minimized=[repr(d) for d in dis[:5]])


if __name__ == "__main__":
    t0 = time.time()
    th1_and_currency()
    th2_locality()
    th3_th4()
    rows = th5_defeasibility()
    pr10_c1_incomparable()
    random_property()
    R["seconds"] = round(time.time() - t0, 1)
    R["bounds"] = "13 templates; subsets 1..3; tau in {1,2,3}; 8 validity configs; 16 currency configs"
    json.dump(R, open("run-exhaustive.json", "w"), indent=1)
    json.dump(rows, open("th5-rows.json", "w"))
    print(json.dumps(R, indent=1))

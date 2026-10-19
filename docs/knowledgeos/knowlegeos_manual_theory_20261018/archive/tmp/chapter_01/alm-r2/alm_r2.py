"""ALM-r2 implementation of ALM-R2-SPEC.md (Appendix I interface, plain dicts).
Research candidate; not validated. No default values for any policy parameter.
Implementation choices where the spec is silent are marked  # SPEC-GAP  and listed in IMPL-NOTES.md.
"""
from __future__ import annotations
import itertools

INF = float("inf")
ROOT, UNREC = "ROOT", "UNREC"
KINDS = {"GRANT", "REVOKE", "CONFIRM", "ADOPT", "END", "PERFORM", "ACCEPT"}
CKINDS = ("decision", "rule", "exception")
ACTIVITIES = ("implement", "verify", "review")
THETA_KEYS = {"C1", "RD1", "delta", "BASIS", "C10", "iota_g", "iota_c", "C6", "BAR", "C15", "root"}
RP_KEYS = {"lam_pos", "lam_neg", "upsilon", "pi", "D_tau", "D_rho", "B"}


class BoundExceeded(Exception):
    pass


# ----------------------------------------------------------------- normalisation
def _num(x):
    return INF if x == "inf" else x


def _atom(a):
    """[kind, object] -> hashable tuple; object may be str, '*', or a list (pair or atom)."""
    k, o = a[0], a[1]
    return (k, _obj(o))


def _obj(o):
    if isinstance(o, (list, tuple)):
        if len(o) == 2 and isinstance(o[0], str) and o[0] in KINDS | {"*"}:
            return _atom(o)
        return tuple(_obj(x) for x in o)
    return o


def norm_act(a):
    d = dict(a)
    if d.get("scope") is not None:
        d["scope"] = frozenset(_atom(x) for x in d["scope"])
    if d.get("period") is not None:
        d["period"] = (_num(d["period"][0]), _num(d["period"][1]))
    if d.get("cperiod") is not None:
        d["cperiod"] = (_num(d["cperiod"][0]), _num(d["cperiod"][1]))
    if d.get("bars") is not None:
        d["bars"] = frozenset(tuple(b) for b in d["bars"])
    return d


def norm_theta(th):
    missing = THETA_KEYS - set(th)
    if missing:
        raise ValueError(f"theta must state {sorted(missing)}")
    t = dict(th)
    t["root"] = frozenset((h, _atom(x)) for h, x in th["root"])
    return t


def covers(S, pi):
    return pi in S or (pi[0], "*") in S or ("*", "*") in S


def in_iv(conv, t, iv):
    lo, hi = iv
    return lo <= t <= hi if conv == "closed" else lo <= t < hi


# ----------------------------------------------------------------- V layer
class V:
    def __init__(self, history, theta, forced=frozenset()):
        self.th = norm_theta(theta)
        self.A = {a["id"]: norm_act(a) for a in history["acts"]}
        self.rho = {k: (INF if v == "never" else v) for k, v in history.get("rho", {}).items()}
        self.forced = set(forced)            # acts with Auth forced to T (upsilon variants)
        C1 = self.th["C1"]
        if C1 not in ("b", "a_tau", "a_rho"):
            raise ValueError("C1")
        for a in self.A.values():
            if self.inst(a) is None:
                raise ValueError(f"no instant for {a['id']}")
        if self.th["C10"] == "given":
            keys = [(self.inst(a), a.get("ord")) for a in self.A.values() if self.inst(a) != INF]
            if any(o is None for _, o in keys) or len(set(keys)) != len(keys):
                raise ValueError("C10=given needs distinct defined (inst, ord)")
        self.valid_, self.auth_, self.basis_, self.form_ = {}, {}, {}, {}
        order = sorted(self.A.values(), key=lambda a: (self.inst(a), a.get("ord") or 0, a["id"]))
        for e in order:
            self._eval(e)

    # S-4.1
    def inst(self, a):
        if self.th["C1"] == "a_rho":
            r = self.rho.get(a["id"], INF)
            return r
        return a.get("tau")

    def rho_of(self, a):
        return self.rho.get(a["id"], INF)

    def prec(self, f, e):
        if self.inst(f) == INF or self.inst(e) == INF:
            return False if self.inst(f) == INF else True
        if self.th["C10"] == "given":
            return (self.inst(f), f["ord"]) < (self.inst(e), e["ord"])
        return self.inst(f) < self.inst(e)

    def avail(self, f, p):
        kind, x = p
        if kind == "act":
            ok, s = self.prec(f, x), x.get("tau")
        else:
            ok, s = self.inst(f) <= x, x
        if ok and self.th["C1"] == "a_tau":
            ok = self.rho_of(f) <= s
        return ok

    def time(self, p):
        return self.inst(p[1]) if p[0] == "act" else p[1]

    # S-4.2
    def form(self, e):
        k = e["kind"]
        if k not in KINDS:
            return False
        if self.th["C1"] in ("a_tau", "a_rho") and self.rho_of(e) == INF:
            return False
        if k == "GRANT":
            return e["period"] is not None and e["period"][0] >= self.inst(e)
        if k in ("REVOKE", "CONFIRM"):
            g = self.A.get(e.get("target"))
            return g is not None and g["kind"] == "GRANT" and self.avail(g, ("act", e))
        if k == "END":
            a = self.A.get(e.get("target"))
            return a is not None and a["kind"] == "ADOPT" and self.avail(a, ("act", e))
        return True

    # S-4.3
    def need(self, e):
        k = e["kind"]
        if k == "GRANT":
            return {("GRANT", pi) for pi in (e["scope"] or ())}
        if k in ("REVOKE", "CONFIRM"):
            return {(k, e["target"])}
        if k == "ADOPT":
            return {("ADOPT", (e["subject"], e["ckind"]))}
        if k == "END":
            a = self.A.get(e.get("target"))
            if a is None or a["kind"] != "ADOPT":
                return None
            return {("END", (a["subject"], a["ckind"]))}
        if k == "PERFORM":
            return {("PERFORM", (e["activity"], e["work"]))}
        if k == "ACCEPT":
            return {("ACCEPT", e["work"])}
        return None

    # S-4.4
    def endings(self, g, p):
        t = self.time(p)
        out = set()
        until = g["period"][1]
        past = t > until if self.th["iota_g"] == "closed" else t >= until
        if past:
            out.add(("expiry", until))
        for r in self.A.values():
            if r["kind"] == "REVOKE" and r.get("target") == g["id"] and self.valid_.get(r["id"]) \
                    and self.avail(r, p):
                out.add(("revocation", self.inst(r)))
        return out

    def first_end(self, g, p):
        E = self.endings(g, p)
        if not E:
            return set()
        m = min(x for _, x in E)
        return {k for k, x in E if x == m}

    def started(self, g, p):
        return self.time(p) >= g["period"][0]

    # S-4.5
    def alive(self, g, p, depth=0):
        if depth > 64:
            raise RecursionError("delegation depth")
        if not (self.valid_.get(g["id"]) and self.avail(g, p) and self.started(g, p)):
            return False
        if self.endings(g, p):
            return False
        if g.get("proviso") and not any(
                c["kind"] == "CONFIRM" and c.get("target") == g["id"] and self.valid_.get(c["id"])
                and self.avail(c, p) for c in self.A.values()):
            return False
        return self.parent(g, p, depth)

    def parent(self, g, p, depth=0):
        b = self.basis_.get(g["id"]) or set()
        if ROOT in b or UNREC in b:
            return True
        return any(self.survives_for(self.A[x], p, depth + 1) for x in b)

    def survives_for(self, g2, p, depth):
        if self.alive(g2, p, depth):
            return True
        fe = self.first_end(g2, p)
        return bool(fe) and all(self.th["delta"][k] == "independent" for k in fe)

    # S-4.6
    def _root_covers(self, e, need):
        rs = {x for (h, x) in self.th["root"] if h == e["holder"]}
        return all(covers(rs, pi) for pi in need)

    def restr(self, e):
        return e["kind"] == "ACCEPT" and any(
            f["kind"] == "PERFORM" and f.get("activity") == "implement" and f.get("work") == e.get("work")
            and f["holder"] == e["holder"] and self.valid_.get(f["id"]) and self.avail(f, ("act", e))
            for f in self.A.values())

    def _eval(self, e):
        self.form_[e["id"]] = self.form(e)
        if e["id"] in self.forced:
            self.basis_[e["id"]] = {UNREC}
            self.auth_[e["id"]] = "T"
        else:
            need = self.need(e)
            if need is None:
                self.basis_[e["id"]] = None
                self.auth_[e["id"]] = "NA"
            else:
                b = set()
                if self._root_covers(e, need):
                    b.add(ROOT)
                for g in self.A.values():
                    if g["kind"] == "GRANT" and g["id"] != e["id"] and g.get("grantee") == e["holder"] \
                            and g["scope"] is not None and all(covers(g["scope"], pi) for pi in need) \
                            and self.alive(g, ("act", e)):
                        if e["kind"] == "GRANT" and self.th["C15"] == "yes":
                            pe, pg = e["period"], g["period"]
                            if pe is None or not (pg[0] <= pe[0] and pe[1] <= pg[1]):
                                continue
                        b.add(g["id"])
                self.basis_[e["id"]] = b
                self.auth_[e["id"]] = "T" if (b and not self.restr(e)) else "F"
        self.valid_[e["id"]] = self.form_[e["id"]] and self.auth_[e["id"]] == "T"

    # S-4.8
    def survives(self, g, t, ckind):
        if self.alive(g, ("time", t)):
            return True
        fe = self.first_end(g, ("time", t))
        return bool(fe) and all(self.th["RD1"][f"{ckind}|{k}"] == "persist" for k in fe)

    def current(self, cid, t):
        a = self.A.get(cid)
        if a is None or a["kind"] != "ADOPT" or not self.valid_[cid]:
            return False
        if not self.avail(a, ("time", t)):
            return False
        if not in_iv(self.th["iota_c"], t, a["cperiod"]):
            return False
        if any(x["kind"] == "END" and x.get("target") == cid and self.valid_[x["id"]]
               and self.avail(x, ("time", t)) for x in self.A.values()):
            return False
        b = self.basis_[cid]
        if not (ROOT in b or UNREC in b):
            q = any if self.th["BASIS"] == "any" else all
            surv = [self.survives(self.A[g], t, a["ckind"]) for g in b]
            if not (surv and q(surv)):
                return False
        if self.th["C6"] == "same_subject_proxy":
            for x in self.A.values():
                if x["kind"] == "ADOPT" and x["id"] != cid and x.get("subject") == a["subject"] \
                        and x.get("ckind") == a["ckind"] and self.prec(a, x) and self.avail(x, ("time", t)) \
                        and self.current(x["id"], t):
                    return False
        return True

    # S-4.9
    def elig(self, eid, purpose, work):
        e = self.A[eid]
        if not self.valid_[eid]:
            return "NOT_VALID", []
        impl = [f for f in self.A.values() if f["kind"] == "PERFORM" and f.get("activity") == "implement"
                and f.get("work") == work and self.avail(f, ("act", e))]
        if purpose == "independent_verification" and any(f.get("performer") == e.get("performer") for f in impl):
            return "INELIGIBLE", ["IND-2"]
        b = self.basis_[eid] or set()
        G = [self.A[g] for g in b if g not in (ROOT, UNREC)]
        barred = [any(x[0] == purpose and x[1] == e.get("performer") and x[2] in (work, "*")
                      for x in (g.get("bars") or ())) for g in G]
        mode = self.th["BAR"]
        if (mode == "any" and any(barred)) or \
                (mode == "all_root_exempt" and G and all(barred) and ROOT not in b) or \
                (mode == "all_root_not_exempt" and G and all(barred)):
            return "INELIGIBLE", ["IND-5"]
        flags = []
        if purpose == "governance_review" and any(f.get("performer") == e.get("performer") for f in impl) \
                and not e.get("disclosure"):
            flags.append("DISCLOSURE_REQUIRED")
        return ("ELIGIBLE_ATTESTED" if e.get("attested") else "ELIGIBLE_ASSERTED"), flags


# ----------------------------------------------------------------- Appendix I (V layer)
def valid(history, theta):
    v = V(history, theta)
    return dict(v.valid_)


def auth(history, theta):
    return dict(V(history, theta).auth_)


def current(history, theta, cid, t):
    return V(history, theta).current(cid, t)


def elig(history, theta, eid, purpose, work):
    return V(history, theta).elig(eid, purpose, work)[0]


# ----------------------------------------------------------------- R layer
ROLE_FIELDS = ("holder", "grantee", "performer")
VALUE_FIELDS = ("subject", "work")


def _queried_atoms(acts, extra_levels=2):
    """N_w: atoms whose coverage can be queried in this world (S-6.1 scope domain).
    Delegation closure to depth extra_levels.  # SPEC-GAP: spec states N_w without a construction"""
    base = set()
    for a in acts:
        k = a["kind"]
        if k in ("REVOKE", "CONFIRM") and a.get("target") is not None:
            base.add((k, a["target"]))
        elif k == "ADOPT":
            base.add(("ADOPT", (a.get("subject"), a.get("ckind"))))
        elif k == "END":
            t = next((x for x in acts if x["id"] == a.get("target")), None)
            if t is not None and t["kind"] == "ADOPT":
                base.add(("END", (t.get("subject"), t.get("ckind"))))
        elif k == "PERFORM":
            base.add(("PERFORM", (a.get("activity"), a.get("work"))))
        elif k == "ACCEPT":
            base.add(("ACCEPT", a.get("work")))
        elif k == "GRANT" and a.get("scope") is not None:
            base |= {("GRANT", pi) for pi in a["scope"]}
    out = set(base)
    layer = set(base) | {("*", "*")}
    for _ in range(extra_levels):
        layer = {("GRANT", pi) for pi in layer}
        out |= layer
    return out


def _subsets(s):
    s = list(s)
    for r in range(len(s) + 1):
        for c in itertools.combinations(s, r):
            yield frozenset(c)


def worlds(entries, k, theta, rp):
    """Yield (history, forced_set) per S-6.1 steps 1-4. Raises BoundExceeded beyond rp['B']."""
    missing = RP_KEYS - set(rp)
    if missing:
        raise ValueError(f"rparams must state {sorted(missing)}")
    th = norm_theta(theta)
    Ek = [e for e in entries if e["pos"] <= k]
    variants = {}
    for e in sorted(Ek, key=lambda x: x["pos"]):
        key = (repr(sorted(e["act"].items(), key=lambda kv: kv[0])), repr(e.get("rho")))
        variants.setdefault(e["act"]["id"], {})[key] = (e["act"], e.get("rho"), e["pos"])
    ids = list(variants)
    count = 0
    B = rp["B"]
    for choice in itertools.product(*[list(variants[i].values()) for i in ids]):
        acts = [norm_act(a) for a, _, _ in choice]
        rho_rec = {a["id"]: r for a, r, _ in choice}
        pos = {a["id"]: p for a, _, p in choice}
        for w_acts, w_rho in _fill(acts, rho_rec, pos, th, rp):
            for ordered in _orders(w_acts, th):
                hist = {"acts": ordered, "rho": {i: (r if r is not None else "never") for i, r in w_rho.items()}}
                try:
                    base = V(_denorm(hist), theta)
                except ValueError:
                    continue
                X = []
                if rp["upsilon"] == "counts_as_undetermined":
                    X = [a["id"] for a in base.A.values() if base.form_[a["id"]] and base.auth_[a["id"]] == "F"
                         and (a["kind"] in ("REVOKE", "END", "CONFIRM")
                              or (a["kind"] == "ADOPT" and th["C6"] == "same_subject_proxy"))]
                for Y in _subsets(X):
                    count += 1
                    if count > B:
                        raise BoundExceeded()
                    yield (base if not Y else V(_denorm(hist), theta, forced=Y))


def _denorm(hist):
    """Back to Appendix-I-ish dicts (V re-normalises)."""
    out = []
    for a in hist["acts"]:
        d = dict(a)
        if isinstance(d.get("scope"), frozenset):
            d["scope"] = [list(x) if not isinstance(x, list) else x for x in d["scope"]]
            d["scope"] = [[x[0], x[1]] for x in d["scope"]]
        if isinstance(d.get("bars"), frozenset):
            d["bars"] = [list(x) for x in d["bars"]]
        if isinstance(d.get("period"), tuple):
            d["period"] = [d["period"][0], "inf" if d["period"][1] == INF else d["period"][1]]
        if isinstance(d.get("cperiod"), tuple):
            d["cperiod"] = [d["cperiod"][0], "inf" if d["cperiod"][1] == INF else d["cperiod"][1]]
        out.append(d)
    return {"acts": out, "rho": hist["rho"]}


def _fill(acts, rho_rec, pos, th, rp):
    """S-6.1 step 2: enumerate every bottom field."""
    roles = {f: sorted({a[f] for a in acts if a.get(f) is not None}) + ["?new"] for f in ROLE_FIELDS}
    vals = {f: sorted({a[f] for a in acts if a.get(f) is not None}) + ["?new"] for f in VALUE_FIELDS}
    grants = [a["id"] for a in acts if a["kind"] == "GRANT"] + ["?unrecorded"]
    adopts = [a["id"] for a in acts if a["kind"] == "ADOPT"] + ["?unrecorded"]
    D_tau = list(rp["D_tau"])
    slots = []
    for i, a in enumerate(acts):
        if a.get("tau") is None:
            slots.append((i, "tau", D_tau))
        for f in ROLE_FIELDS:
            if f in a and a[f] is None and (f != "grantee" or a["kind"] == "GRANT") \
                    and (f != "performer" or a["kind"] in ("PERFORM", "ACCEPT")):
                slots.append((i, f, roles[f]))
        if a["kind"] in ("REVOKE", "CONFIRM", "END") and a.get("target") is None:
            slots.append((i, "target", grants if a["kind"] != "END" else adopts))
        for f in VALUE_FIELDS:
            if f in a and a[f] is None:
                slots.append((i, f, vals[f]))
        if a["kind"] == "PERFORM" and a.get("activity") is None:
            slots.append((i, "activity", list(ACTIVITIES)))
        if a["kind"] == "ADOPT" and a.get("ckind") is None:
            slots.append((i, "ckind", list(CKINDS)))
        if a["kind"] == "GRANT" and "proviso" in a and a["proviso"] is None:
            slots.append((i, "proviso", [False, True]))           # SPEC-GAP
    for vals_ in itertools.product(*[d for _, _, d in slots]):
        aa = [dict(a) for a in acts]
        for (i, f, _), v in zip(slots, vals_):
            aa[i][f] = v
        yield from _fill_periods_scopes_rho(aa, rho_rec, pos, th, rp)


def _fill_periods_scopes_rho(aa, rho_rec, pos, th, rp):
    D_tau = list(rp["D_tau"])
    pslots = []
    for i, a in enumerate(aa):
        if a["kind"] == "GRANT" and a.get("period") is None:
            if rp["pi"] == "open_ended":
                aa[i]["period"] = (a["tau"], INF)
            else:
                opts = [(f, u) for f in D_tau if f >= a["tau"] for u in [d for d in D_tau if d >= f] + [INF]]
                pslots.append((i, "period", opts))
        if a["kind"] == "ADOPT" and a.get("cperiod") is None:
            opts = [(a["tau"], INF)] if rp["pi"] == "open_ended" else \
                [(f, u) for f in D_tau if f >= a["tau"] for u in [d for d in D_tau if d >= f] + [INF]]
            pslots.append((i, "cperiod", opts))                    # SPEC-GAP: pi applied to cperiod
    for pv in itertools.product(*[d for _, _, d in pslots]):
        bb = [dict(a) for a in aa]
        for (i, f, _), v in zip(pslots, pv):
            bb[i][f] = v
        N = _queried_atoms(bb)
        sslots = [(i, "scope", list(_subsets(N | {("*", "*")}))) for i, a in enumerate(bb)
                  if a["kind"] == "GRANT" and a.get("scope") is None]
        for sv in itertools.product(*[d for _, _, d in sslots]):
            cc = [dict(a) for a in bb]
            for (i, f, _), v in zip(sslots, sv):
                cc[i][f] = v
            yield from _fill_rho(cc, rho_rec, pos, th, rp)


def _fill_rho(cc, rho_rec, pos, th, rp):
    if th["C1"] == "b":
        yield cc, {a["id"]: rho_rec.get(a["id"]) for a in cc}
        return
    ids = [a["id"] for a in cc]
    slots = [(a["id"], [d for d in rp["D_rho"] if d >= a["tau"]]) for a in cc if rho_rec.get(a["id"]) is None]
    for vals_ in itertools.product(*[d for _, d in slots]):
        r = dict(rho_rec)
        for (i, _), v in zip(slots, vals_):
            r[i] = v
        if any(r[i] is not None and r[i] < next(a["tau"] for a in cc if a["id"] == i) for i in ids):
            continue                                                 # AX-NoBackdate
        srt = sorted(ids, key=lambda i: pos[i])
        if any(r[srt[j]] > r[srt[j + 1]] for j in range(len(srt) - 1)):
            continue                                                 # AX-PosRho
        yield cc, r


def _orders(acts, th):
    """S-6.1 step 3: under C10=given, all linear orders per equal-instant group consistent with
    the relative order of recorded ords. Under no_same_instant: one arbitrary ord assignment."""
    if th["C10"] != "given":
        out = [dict(a, ord=a.get("ord") if a.get("ord") is not None else 0) for a in acts]
        yield out
        return
    key = "tau"   # SPEC-GAP for a_rho: groups by rho handled by V via inst(); here group by tau
    groups = {}
    for a in acts:
        groups.setdefault(a[key], []).append(a)
    glists = []
    for t, g in groups.items():
        perms = []
        for p in itertools.permutations(g):
            rec = [a for a in p if a.get("ord") is not None]
            if all(rec[j]["ord"] < rec[j + 1]["ord"] for j in range(len(rec) - 1)):
                perms.append([dict(a, ord=n) for n, a in enumerate(p)])
        glists.append(perms)
    for combo in itertools.product(*glists):
        yield [a for grp in combo for a in grp]


def _phi(Vw, q):
    k = q[0]
    if k == "valid":
        return Vw.valid_[q[1]], Vw.A[q[1]]["tau"]
    if k == "auth":
        return Vw.auth_[q[1]] == "T", Vw.A[q[1]]["tau"]
    if k == "current":
        return Vw.current(q[1], q[2]), q[2]
    if k == "elig":
        return Vw.elig(q[1], q[2], q[3])[0].startswith("ELIGIBLE"), Vw.A[q[1]]["tau"]
    raise ValueError(q)


def rec(entries, k, q, theta, rparams, now):
    if not any(e["act"]["id"] == q[1] and e["pos"] <= k for e in entries):
        return "U"
    lp, ln = _num(rparams["lam_pos"]), _num(rparams["lam_neg"])
    allT = allF = True
    n = 0
    try:
        for Vw in worlds(entries, k, theta, rparams):
            n += 1
            v, t = _phi(Vw, q)
            if not (t <= now - lp and v):
                allT = False
            if not (t <= now - ln and not v):
                allF = False
            if not allT and not allF:
                return "U"
    except BoundExceeded:
        return "U"
    if n == 0:
        return "U"
    return "T" if allT else ("F" if allF else "U")


def rec_at_date(entries, d, wit_pos, wit_neg, q, theta, rparams):
    kmax = max((e["pos"] for e in entries), default=0)
    lo = max([p for (dd, p) in wit_pos if dd <= d] + [0])
    hi = min([p - 1 for (dd, p) in wit_neg if dd >= d] + [kmax])
    if lo > hi:
        return "CONFLICT"
    vs = {rec(entries, kk, q, theta, rparams, now=d) for kk in range(lo, hi + 1)}
    return vs.pop() if len(vs) == 1 else "U"


def eff(entries, k, eid, theta, rparams, now, proc):
    known = any(e["act"]["id"] == eid and e["pos"] <= k for e in entries)
    return known and proc(k, eid) and rec(entries, k, ["valid", eid], theta, rparams, now) == "T"

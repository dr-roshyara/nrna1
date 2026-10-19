"""Independent reference oracle for ALM-r2 (ALM-R2-SPEC.md).

Written ONLY from ALM-R2-SPEC.md.  Literal transcription is preferred over
efficiency.  Every interpretive choice is tagged ``[F-nn]`` and explained in
SPEC-FINDINGS.md.

Strategy (deliberately different from a straightforward recursion):
  * V layer: Valid is obtained by brute-force search over all 2^n truth
    assignments of the definitional equations Valid(x) = Form(x) & Auth(x)=T;
    exactly one solution must exist, otherwise SpecError is raised.  Current
    likewise is solved by brute force over the ADOPT acts at instant t.
    (Above BRUTE_LIMIT acts a stratified evaluation along prec is used and
    then re-checked as a fixpoint.)
  * R layer: every world is built literally from S-6.1 steps 1-4 and then
    S-6.2 is applied.  The world bound B is honoured.

Public interface (Appendix I):
    valid, auth, current, elig, rec, rec_at_date
Extras: elig_full, rec_explain, eff, eff_obs, usable_from, count_worlds.
"""
import itertools
import json
import math

INF = math.inf
NEVER = "never"
ROOT = "ROOT"
UNREC = "UNREC"
BARS_ALL = "__BARS_ALL__"      # [F-17] sentinel: bars matching every triple
NEW = "?new"                   # [F-11] one fresh value
UNRECORDED = "?unrecorded"
LEGAL_KINDS = ("GRANT", "REVOKE", "CONFIRM", "ADOPT", "END", "PERFORM", "ACCEPT")
CKINDS = ("decision", "rule", "exception")
EKINDS = ("expiry", "revocation")
ACTIVITIES = ("implement", "verify", "review")
BRUTE_LIMIT = 14


class SpecError(Exception):
    """Missing parameter, malformed input, or a definitional failure."""


# --------------------------------------------------------------------------
# parameters
# --------------------------------------------------------------------------
def _req(d, key, allowed=None, where=""):
    if not isinstance(d, dict) or key not in d or d[key] is None:
        raise SpecError("missing required parameter %s%s" % (where, key))
    v = d[key]
    if allowed is not None and v not in allowed:
        raise SpecError("parameter %s%s=%r not in %r" % (where, key, v, allowed))
    return v


def check_theta(theta):
    th = {}
    th["C1"] = _req(theta, "C1", ("b", "a_tau", "a_rho"))
    rd1 = _req(theta, "RD1")
    th["RD1"] = {}
    for ck in CKINDS:
        for ek in EKINDS:
            th["RD1"][(ck, ek)] = _req(rd1, "%s|%s" % (ck, ek), ("persist", "cascade"), "RD1.")
    dl = _req(theta, "delta")
    th["delta"] = {ek: _req(dl, ek, ("independent", "dependent"), "delta.") for ek in EKINDS}
    th["BASIS"] = _req(theta, "BASIS", ("any", "all"))
    th["C10"] = _req(theta, "C10", ("given", "no_same_instant"))
    th["iota_g"] = _req(theta, "iota_g", ("closed", "half_open"))
    th["iota_c"] = _req(theta, "iota_c", ("closed", "half_open"))
    th["C6"] = _req(theta, "C6", ("off", "same_subject_proxy"))
    th["BAR"] = _req(theta, "BAR", ("any", "all_root_exempt", "all_root_not_exempt"))
    th["C15"] = _req(theta, "C15", ("no", "yes"))
    root = _req(theta, "root")
    th["root"] = frozenset((h, tup(atom)) for h, atom in root)
    return th


def _lam(v, name):
    if v == "inf":
        return INF
    if isinstance(v, (int, float)) and v >= 0:
        return v
    raise SpecError("%s must be a number >= 0 or 'inf'" % name)


def check_rparams(rp):
    out = {}
    out["lam_pos"] = _lam(_req(rp, "lam_pos"), "lam_pos")
    out["lam_neg"] = _lam(_req(rp, "lam_neg"), "lam_neg")
    out["upsilon"] = _req(rp, "upsilon", ("does_not_count", "counts_as_undetermined"))
    out["pi"] = _req(rp, "pi", ("open_ended", "undetermined"))
    out["D_tau"] = list(_req(rp, "D_tau"))
    out["D_rho"] = list(_req(rp, "D_rho"))
    out["B"] = _req(rp, "B")
    if not isinstance(out["B"], int):
        raise SpecError("B must be int")
    return out


# --------------------------------------------------------------------------
# atoms / normalisation
# --------------------------------------------------------------------------
def tup(x):
    if isinstance(x, (list, tuple)):
        return tuple(tup(y) for y in x)
    return x


def covers(S, pi):
    """S-1: Covers(S, pi) <=> pi in S or (kind(pi), *) in S or (*, *) in S."""
    return pi in S or (pi[0], "*") in S or ("*", "*") in S


def _num(x):
    if x == "inf":
        return INF
    return x


def _pair(p):
    if p is None:
        return None
    return (_num(p[0]), _num(p[1]))


def norm_act(a):
    """Normalise a JSON act into the internal representation."""
    if "id" not in a or a["id"] is None:
        raise SpecError("act without id")
    n = dict(a)
    n["scope"] = frozenset(tup(x) for x in a["scope"]) if a.get("scope") is not None else None
    n["period"] = _pair(a.get("period"))
    n["cperiod"] = _pair(a.get("cperiod"))
    b = a.get("bars")
    if b == BARS_ALL:
        n["bars"] = BARS_ALL
    elif b is not None:
        n["bars"] = [tup(x) for x in b]
    else:
        n["bars"] = None
    return n


def need_of(acts, e):
    """S-4.3.  Returns a frozenset of atoms, or None (undefined)."""
    a = acts[e]
    k = a["kind"]
    if k == "GRANT":
        return frozenset(("GRANT", pi) for pi in a["scope"])
    if k in ("REVOKE", "CONFIRM"):
        return frozenset({(k, a["target"])})
    if k == "ADOPT":
        return frozenset({("ADOPT", (a["subject"], a["ckind"]))})
    if k == "END":
        t = a["target"]
        if t in acts and acts[t]["kind"] == "ADOPT":
            return frozenset({("END", (acts[t]["subject"], acts[t]["ckind"]))})
        return None
    if k == "PERFORM":
        return frozenset({("PERFORM", (a["activity"], a["work"]))})
    if k == "ACCEPT":
        return frozenset({("ACCEPT", a["work"])})
    return None  # [F-23] illegal kind: Need undefined


def period_subset(pe, pg):
    return pg[0] <= pe[0] and pe[1] <= pg[1]


# --------------------------------------------------------------------------
# History: order, availability, Form (S-4.1, S-4.2)
# --------------------------------------------------------------------------
class History:
    def __init__(self, acts, rho, th):
        """acts: dict id -> normalised act; rho: dict id -> int|NEVER|None."""
        self.th = th
        self.acts = acts
        self.ids = list(acts)
        self.rho = rho
        for x in self.ids:
            if self.acts[x].get("tau") is None:
                raise SpecError("act %r has no tau" % (x,))
        if th["C1"] in ("a_tau", "a_rho"):
            for x in self.ids:
                if rho.get(x) is None:
                    raise SpecError("C1=%s needs rho for act %r" % (th["C1"], x))
        if th["C10"] == "given":
            seen = {}
            for x in self.ids:
                if self.acts[x].get("ord") is None:
                    raise SpecError("C10=given: act %r has no ord" % (x,))
                k = self.key(x)
                if k is None:
                    continue
                p = (k, self.acts[x]["ord"])
                if p in seen:
                    raise SpecError("C10=given: (instant, ord) %r not distinct (%r, %r) [F-04]" % (p, seen[p], x))
                seen[p] = x
        self._root_cache = {}

    @classmethod
    def from_json(cls, history, th):
        if not isinstance(history, dict) or "acts" not in history:
            raise SpecError("history needs 'acts'")
        acts = {}
        for a in history["acts"]:
            n = norm_act(a)
            if n["id"] in acts:
                raise SpecError("duplicate act id %r" % (n["id"],))
            acts[n["id"]] = n
        rho = dict(history.get("rho") or {})
        return cls(acts, rho, th)

    def root_scope(self, holder):
        if holder not in self._root_cache:
            self._root_cache[holder] = frozenset(at for (h, at) in self.th["root"] if h == holder)
        return self._root_cache[holder]

    def key(self, x):
        """The instant used by prec/inst: tau, or rho under a_rho (None if never)."""
        if self.th["C1"] == "a_rho":
            r = self.rho[x]
            return None if r == NEVER else r
        return self.acts[x]["tau"]

    inst = key

    def prec(self, f, e):
        if f == e:
            return False
        kf, ke = self.key(f), self.key(e)
        if kf is None or ke is None:
            return False  # [F-05] rho = never under a_rho: never precedes / preceded
        if self.th["C10"] == "given":
            return (kf, self.acts[f]["ord"]) < (ke, self.acts[e]["ord"])
        return kf < ke

    def avail(self, f, p):
        a_tau = self.th["C1"] == "a_tau"
        if p[0] == "act":
            e = p[1]
            if not self.prec(f, e):
                return False
            if a_tau:
                rf = self.rho[f]
                return rf != NEVER and rf <= self.acts[e]["tau"]
            return True
        t = p[1]
        i = self.inst(f)
        if i is None or not (i <= t):
            return False
        if a_tau:
            rf = self.rho[f]
            return rf != NEVER and rf <= t
        return True

    def time(self, p):
        # [F-03] time(p) of an act point := inst(e)
        return self.inst(p[1]) if p[0] == "act" else p[1]

    def form(self, e):
        a = self.acts[e]
        k = a["kind"]
        if k not in LEGAL_KINDS:
            return False
        if self.th["C1"] in ("a_tau", "a_rho") and self.rho[e] == NEVER:
            return False  # RecOK
        if k == "GRANT":
            i = self.inst(e)
            return i is not None and a["period"][0] >= i
        if k in ("REVOKE", "CONFIRM"):
            t = a["target"]
            return t in self.acts and self.acts[t]["kind"] == "GRANT" and self.avail(t, ("act", e))
        if k == "END":
            t = a["target"]
            return t in self.acts and self.acts[t]["kind"] == "ADOPT" and self.avail(t, ("act", e))
        return True

    def need(self, e):
        return need_of(self.acts, e)


# --------------------------------------------------------------------------
# Eval: everything relative to a candidate Valid assignment V (S-4.4..S-4.6)
# --------------------------------------------------------------------------
class Eval:
    def __init__(self, H, V, forced=frozenset()):
        self.H = H
        self.th = H.th
        self.V = V
        self.forced = frozenset(forced)
        self._basis = {}
        self._alive = {}

    # S-4.4
    def endings(self, g, p):
        H = self.H
        out = []
        tp = H.time(p)
        until = H.acts[g]["period"][1]
        if tp is not None and until != INF:
            if (self.th["iota_g"] == "closed" and tp > until) or \
               (self.th["iota_g"] == "half_open" and tp >= until):
                out.append(("expiry", until))
        for r in H.ids:
            ra = H.acts[r]
            if ra["kind"] == "REVOKE" and ra["target"] == g and self.V[r] and H.avail(r, p):
                out.append(("revocation", H.inst(r)))
        return out

    def first_end(self, g, p):
        en = self.endings(g, p)
        if not en:
            return set()
        m = min(x for _, x in en)
        return {k for k, x in en if x == m}

    def started(self, g, p):
        tp = self.H.time(p)
        return tp is not None and tp >= self.H.acts[g]["period"][0]

    # S-4.5
    def alive(self, g, p):
        key = (g, p)
        if key in self._alive:
            return self._alive[key]
        H = self.H
        ga = H.acts[g]
        r = (ga["kind"] == "GRANT" and self.V[g] and H.avail(g, p)   # avail first => well-founded
             and self.started(g, p) and not self.endings(g, p)
             and (not ga["proviso"] or any(
                 H.acts[c]["kind"] == "CONFIRM" and H.acts[c]["target"] == g
                 and self.V[c] and H.avail(c, p) for c in H.ids))
             and self.parent(g, p))
        self._alive[key] = bool(r)
        return bool(r)

    def parent(self, g, p):
        b = self.basis(g)
        if b is None:
            return False
        if ROOT in b:
            return True
        return any(x == UNREC or self.survives_for(x, p) for x in b)

    def survives_for(self, g2, p):
        if self.alive(g2, p):
            return True
        en = self.endings(g2, p)
        return bool(en) and all(self.th["delta"][k] == "independent" for k in self.first_end(g2, p))

    # S-4.6
    def basis(self, e):
        if e in self._basis:
            return self._basis[e]
        H = self.H
        if e in self.forced:
            b = frozenset({UNREC})
        else:
            need = H.need(e)
            if need is None:
                b = None
            else:
                a = H.acts[e]
                holder = a["holder"]
                s = set()
                rs = H.root_scope(holder)
                if all(covers(rs, pi) for pi in need):
                    s.add(ROOT)
                for g in H.ids:
                    ga = H.acts[g]
                    if g == e or ga["kind"] != "GRANT":
                        continue
                    if ga["grantee"] != holder:
                        continue
                    if not all(covers(ga["scope"], pi) for pi in need):
                        continue
                    if a["kind"] == "GRANT" and self.th["C15"] == "yes" and \
                            not period_subset(a["period"], ga["period"]):
                        continue
                    if self.alive(g, ("act", e)):
                        s.add(g)
                b = frozenset(s)
        self._basis[e] = b
        return b

    def restr(self, e):
        H = self.H
        a = H.acts[e]
        if a["kind"] != "ACCEPT":
            return False
        for f in H.ids:
            fa = H.acts[f]
            if fa["kind"] == "PERFORM" and fa["activity"] == "implement" and fa["work"] == a["work"] \
                    and fa["holder"] == a["holder"] and self.V[f] and H.avail(f, ("act", e)):
                return True
        return False

    def auth(self, e):
        if e in self.forced:
            return "T"
        if self.H.need(e) is None:
            return "NA"
        if self.basis(e) and not self.restr(e):
            return "T"
        return "F"

    def rhs_valid(self, e):
        return self.H.form(e) and self.auth(e) == "T"

    # S-4.8
    def survives(self, g, t, ck):
        if g == UNREC:
            return True
        p = ("t", t)
        if self.alive(g, p):
            return True
        en = self.endings(g, p)
        if not en:
            return False
        try:
            return all(self.th["RD1"][(ck, k)] == "persist" for k in self.first_end(g, p))
        except KeyError:
            raise SpecError("ckind %r not in K" % (ck,))

    def carry(self, a, t):
        b = self.basis(a)
        if b is None:
            return False
        if ROOT in b:
            return True
        ck = self.H.acts[a]["ckind"]
        vals = [self.survives(g, t, ck) for g in b]
        return any(vals) if self.th["BASIS"] == "any" else all(vals)


def solve_valid(H, forced=frozenset(), method=None):
    """Find THE solution of Valid(x) = Form(x) & Auth(x)=T for all x."""
    ids = H.ids
    if method is None:
        method = "brute" if len(ids) <= BRUTE_LIMIT else "strat"
    if method == "brute":
        sols = []
        for bits in itertools.product((False, True), repeat=len(ids)):
            V = dict(zip(ids, bits))
            ev = Eval(H, V, forced)
            if all(ev.rhs_valid(x) == V[x] for x in ids):
                sols.append(V)
        if len(sols) != 1:
            raise SpecError("Valid equations have %d solutions (expected exactly 1)" % len(sols))
        return Eval(H, sols[0], forced)
    # stratified along a linear extension of prec
    def sk(x):
        k = H.key(x)
        return (k is None, k if k is not None else 0, H.acts[x].get("ord") or 0)
    V = {x: False for x in ids}
    ev = Eval(H, V, forced)
    for x in sorted(ids, key=sk):
        V[x] = ev.rhs_valid(x)
    chk = Eval(H, dict(V), forced)
    if not all(chk.rhs_valid(x) == V[x] for x in ids):
        raise SpecError("stratified Valid is not a fixpoint")
    return chk


def _current_rhs(ev, a, t, C):
    H, th = ev.H, ev.th
    A = H.acts[a]
    if A["kind"] != "ADOPT":
        return False
    if not ev.V[a]:                                            # 1
        return False
    if not H.avail(a, ("t", t)):                               # 2
        return False
    if th["C1"] in ("b", "a_tau") and not (A["tau"] <= t):
        return False
    cf, cu = A["cperiod"]                                      # 3
    if th["iota_c"] == "closed":
        if not (cf <= t <= cu):
            return False
    elif not (cf <= t < cu):
        return False
    for x in H.ids:                                            # 4
        xa = H.acts[x]
        if xa["kind"] == "END" and xa["target"] == a and ev.V[x] and H.avail(x, ("t", t)):
            return False
    if not ev.carry(a, t):                                     # 5
        return False
    if th["C6"] == "same_subject_proxy":                       # 6
        for a2 in H.ids:
            b = H.acts[a2]
            if b["kind"] == "ADOPT" and b["subject"] == A["subject"] and b["ckind"] == A["ckind"] \
                    and H.prec(a, a2) and H.avail(a2, ("t", t)) and C(a2):
                return False
    return True


def solve_current(ev, t):
    H = ev.H
    adopts = [x for x in H.ids if H.acts[x]["kind"] == "ADOPT"]
    if len(adopts) <= BRUTE_LIMIT:
        sols = []
        for bits in itertools.product((False, True), repeat=len(adopts)):
            Cm = dict(zip(adopts, bits))
            if all(_current_rhs(ev, a, t, Cm.__getitem__) == Cm[a] for a in adopts):
                sols.append(Cm)
        if len(sols) != 1:
            raise SpecError("Current equations have %d solutions" % len(sols))
        return sols[0]
    memo = {}

    def cur(a):
        if a not in memo:
            memo[a] = _current_rhs(ev, a, t, cur)
        return memo[a]
    return {a: cur(a) for a in adopts}


def _elig(ev, e, purpose, w):
    """S-4.9.  Returns (grade, flags)."""
    H, th = ev.H, ev.th
    a = H.acts[e]
    flags = []
    if not ev.V[e]:
        return "NOT_VALID", flags
    perf = a.get("performer") if a["kind"] in ("PERFORM", "ACCEPT") else None   # [F-27]

    def impl_by_same():
        if perf is None:
            return False
        for f in H.ids:
            fa = H.acts[f]
            if fa["kind"] == "PERFORM" and fa["activity"] == "implement" and fa["work"] == w \
                    and H.avail(f, ("act", e)) and fa["performer"] == perf:
                return True   # [F-26] no Valid(f) required
        return False

    if purpose == "independent_verification" and impl_by_same():
        return "INELIGIBLE", flags
    b = ev.basis(e)
    G = [g for g in b if g != ROOT]

    def barred(g):
        if g == UNREC:
            return False   # [F-28]
        bars = H.acts[g]["bars"]
        if bars == BARS_ALL:
            return True
        return any(bp == purpose and bf == perf and bw in (w, "*") for (bp, bf, bw) in (bars or []))
    mode = th["BAR"]
    if mode == "any":
        inel = any(barred(g) for g in G)
    elif mode == "all_root_exempt":
        inel = bool(G) and all(barred(g) for g in G) and ROOT not in b
    else:
        inel = bool(G) and all(barred(g) for g in G)
    if inel:
        return "INELIGIBLE", flags
    if purpose == "governance_review" and impl_by_same():
        if "disclosure" not in a:
            raise SpecError("act %r lacks 'disclosure'" % (e,))
        if not a["disclosure"]:
            flags.append("DISCLOSURE_REQUIRED")
    if "attested" not in a or a["attested"] is None:
        raise SpecError("act %r lacks 'attested'" % (e,))
    return ("ELIGIBLE_ATTESTED" if a["attested"] else "ELIGIBLE_ASSERTED"), flags


# --------------------------------------------------------------------------
# Appendix I: V-layer functions
# --------------------------------------------------------------------------
def _v_setup(history, theta):
    th = check_theta(theta)
    H = History.from_json(history, th)
    return H, solve_valid(H)


def valid(history, theta):
    H, ev = _v_setup(history, theta)
    return {x: bool(ev.V[x]) for x in H.ids}


def auth(history, theta):
    H, ev = _v_setup(history, theta)
    return {x: ev.auth(x) for x in H.ids}


def current(history, theta, cid, t):
    if t is None:
        raise SpecError("missing t")
    H, ev = _v_setup(history, theta)
    if cid not in H.acts or H.acts[cid]["kind"] != "ADOPT":
        raise SpecError("claim id %r is not an ADOPT" % (cid,))
    return bool(solve_current(ev, _num(t))[cid])


def elig_full(history, theta, eid, purpose, work):
    H, ev = _v_setup(history, theta)
    if eid not in H.acts:
        raise SpecError("unknown act %r" % (eid,))
    return _elig(ev, eid, purpose, work)


def elig(history, theta, eid, purpose, work):
    return elig_full(history, theta, eid, purpose, work)[0]


# --------------------------------------------------------------------------
# R layer: worlds (S-6.1)
# --------------------------------------------------------------------------
FIELDS_BY_KIND = {
    "GRANT": ["holder", "grantee", "period", "proviso", "bars"],   # scope handled last
    "REVOKE": ["holder", "target"],
    "CONFIRM": ["holder", "target"],
    "ADOPT": ["holder", "subject", "ckind", "cperiod"],
    "END": ["holder", "target"],
    "PERFORM": ["holder", "performer", "activity", "work", "attested", "disclosure"],
    "ACCEPT": ["holder", "performer", "work", "attested", "disclosure"],
}
POOL_FIELDS = ("holder", "grantee", "performer", "subject", "work")


def _entries_upto(entries, k):
    pos = [en["pos"] for en in entries]
    if len(pos) != len(set(pos)):
        raise SpecError("entry positions are not unique")
    return sorted([en for en in entries if en["pos"] <= k], key=lambda en: en["pos"])


def _uniq(xs):
    out = []
    for x in xs:
        if x not in out:
            out.append(x)
    return out


def _period_dom(raw, tau, pi, D_tau):
    """[F-14] period (and, by choice [F-15], cperiod) for a (partially) ⊥ value."""
    fr = None if raw is None else _num(raw[0])
    un = None if raw is None else _num(raw[1])
    froms = [fr] if fr is not None else ([tau] if pi == "open_ended" else [d for d in D_tau if d >= tau])
    out = []
    for f in froms:
        if un is not None:
            out.append((f, un))
        elif pi == "open_ended":
            out.append((f, INF))
        else:
            for u in [d for d in D_tau if d >= f] + [INF]:
                out.append((f, u))
    return out


def _subsets(xs):
    for bits in itertools.product((False, True), repeat=len(xs)):
        yield [x for x, b in zip(xs, bits) if b]


def _orders(group, constraints):
    """All permutations of group respecting (x before y) constraints."""
    for perm in itertools.permutations(group):
        idx = {x: i for i, x in enumerate(perm)}
        if all(idx[x] < idx[y] for x, y in constraints):
            yield perm


def gen_worlds(Ek, th, rp):
    """Yield (History, forced_set, base_eval_or_None) for every world of S-6.1."""
    C1 = th["C1"]
    a_star = C1 in ("a_tau", "a_rho")
    # ---- step 1: group variants by id ------------------------------------
    groups = {}
    sigs = {}
    for en in Ek:
        act = dict(en.get("act") or {})
        if act.get("id") is None:
            act["id"] = "?id@%d" % en["pos"]       # [F-09]
        aid = act["id"]
        sig = json.dumps([act, en.get("rho")], sort_keys=True, default=str)
        groups.setdefault(aid, [])
        sigs.setdefault(aid, set())
        if sig not in sigs[aid]:                    # [F-08] distinct (act, rho) variants
            sigs[aid].add(sig)
            groups[aid].append((en["pos"], act, en.get("rho")))
    pools = {f: _uniq([en["act"].get(f) for en in Ek if en.get("act") and en["act"].get(f) is not None])
             for f in POOL_FIELDS}                    # [F-10] per role, all entries of E_k
    ids = list(groups)

    for choice in itertools.product(*[groups[i] for i in ids]):
        chosen = dict(zip(ids, choice))
        raw = {i: chosen[i][1] for i in ids}
        # ---- step 2a: kind (domain not stated) [F-12] -------------------
        unk_kind = [i for i in ids if raw[i].get("kind") is None]
        for kinds in itertools.product(LEGAL_KINDS, repeat=len(unk_kind)):
            kind_of = {i: raw[i].get("kind") for i in ids}
            kind_of.update(zip(unk_kind, kinds))
            # ---- step 2b: tau --------------------------------------------
            unk_tau = [i for i in ids if raw[i].get("tau") is None]
            for taus in itertools.product(rp["D_tau"], repeat=len(unk_tau)):
                tau_of = {i: raw[i].get("tau") for i in ids}
                tau_of.update(zip(unk_tau, taus))
                # ---- step 2c: other fields -------------------------------
                slots = []
                for i in ids:
                    kd = kind_of[i]
                    for f in FIELDS_BY_KIND.get(kd, []):
                        v = raw[i].get(f)
                        if f in ("period", "cperiod"):
                            if v is not None and v[0] is not None and v[1] is not None:
                                continue
                            slots.append((i, f, _period_dom(v, tau_of[i], rp["pi"], rp["D_tau"])))
                            continue
                        if v is not None:
                            continue
                        if f in ("holder", "grantee", "performer", "subject", "work"):
                            dom = pools[f] + [NEW]
                        elif f == "target":
                            want = "ADOPT" if kd == "END" else "GRANT"
                            dom = [j for j in ids if kind_of[j] == want] + [UNRECORDED]
                        elif f == "activity":
                            dom = list(ACTIVITIES)
                        elif f == "ckind":
                            dom = list(CKINDS)
                        elif f in ("proviso", "attested", "disclosure"):
                            dom = [False, True]          # [F-16]
                        elif f == "bars":
                            dom = [[], BARS_ALL]         # [F-17]
                        else:
                            raise SpecError("no domain for field %s" % f)
                        slots.append((i, f, dom))
                    if a_star and chosen[i][2] is None:
                        slots.append((i, "rho", [d for d in rp["D_rho"] if d >= tau_of[i]]))
                for vals in itertools.product(*[s[2] for s in slots]):
                    acts_raw = {}
                    for i in ids:
                        r = dict(raw[i])
                        r["kind"] = kind_of[i]
                        r["tau"] = tau_of[i]
                        if r["kind"] not in ("PERFORM", "ACCEPT"):     # [F-27]
                            r["attested"] = bool(r.get("attested"))
                            r["disclosure"] = bool(r.get("disclosure"))
                        acts_raw[i] = r
                    rho_of = {i: chosen[i][2] for i in ids}
                    for (i, f, _), v in zip(slots, vals):
                        if f == "rho":
                            rho_of[i] = v
                        else:
                            acts_raw[i][f] = v
                    if a_star:
                        # [F-13] world filter: AX-NoBackdate and AX-PosRho
                        if any(rho_of[i] < tau_of[i] for i in ids):
                            continue
                        byp = sorted(ids, key=lambda i: chosen[i][0])
                        if any(rho_of[byp[j]] > rho_of[byp[j + 1]] for j in range(len(byp) - 1)):
                            continue
                    # ---- step 2d: scope over N_w ∪ {(*,*)} [F-18] ---------
                    unk_scope = [i for i in ids if kind_of[i] == "GRANT" and acts_raw[i].get("scope") is None]
                    pre = {}
                    for i in ids:
                        n = norm_act(acts_raw[i])
                        if n["scope"] is None:
                            n["scope"] = frozenset()
                        pre[i] = n
                    Nw = set()
                    for i in ids:
                        if i in unk_scope:
                            continue
                        nd = need_of(pre, i)
                        if nd is not None:
                            Nw |= nd
                    atoms = sorted(Nw | {("*", "*")}, key=repr)
                    for scopes in itertools.product(*[list(_subsets(atoms)) for _ in unk_scope]):
                        acts_n = {}
                        for i in ids:
                            acts_n[i] = dict(pre[i])
                        for i, sc in zip(unk_scope, scopes):
                            acts_n[i]["scope"] = frozenset(sc)
                        # ---- step 3: orders [F-19][F-20] -------------------
                        if th["C10"] == "given":
                            keyf = (lambda i: rho_of[i]) if C1 == "a_rho" else (lambda i: tau_of[i])
                            recf = (lambda i: chosen[i][2] is not None) if C1 == "a_rho" \
                                else (lambda i: raw[i].get("tau") is not None)
                            grp = {}
                            for i in ids:
                                grp.setdefault(keyf(i), []).append(i)
                            glist = list(grp.values())
                            per_group = []
                            for g in glist:
                                cons = [(x, y) for x in g for y in g
                                        if x != y and recf(x) and recf(y)
                                        and raw[x].get("ord") is not None and raw[y].get("ord") is not None
                                        and raw[x]["ord"] < raw[y]["ord"]]
                                per_group.append(list(_orders(g, cons)))
                            order_choices = itertools.product(*per_group)
                        else:
                            order_choices = [None]
                        for oc in order_choices:
                            acts_o = {i: dict(acts_n[i]) for i in ids}
                            if oc is not None:
                                for perm in oc:
                                    for pos_i, i in enumerate(perm):
                                        acts_o[i]["ord"] = pos_i
                            H = History(acts_o, dict(rho_of), th)
                            base = solve_valid(H)
                            # ---- step 4: upsilon closure ------------------
                            if rp["upsilon"] == "does_not_count":
                                yield H, frozenset(), base
                                continue
                            X = []
                            for i in ids:
                                kd = H.acts[i]["kind"]
                                cand = kd in ("REVOKE", "END", "CONFIRM")
                                if kd == "ADOPT" and th["C6"] == "same_subject_proxy":   # [F-21]
                                    cand = any(H.acts[j]["kind"] == "ADOPT"
                                               and H.acts[j]["subject"] == H.acts[i]["subject"]
                                               and H.acts[j]["ckind"] == H.acts[i]["ckind"]
                                               and H.prec(j, i) for j in ids)
                                if cand and H.form(i) and base.auth(i) == "F":
                                    X.append(i)
                            for Y in _subsets(X):
                                yield H, frozenset(Y), (base if not Y else None)


# --------------------------------------------------------------------------
# R layer: verdict (S-6.2)
# --------------------------------------------------------------------------
def _phi(ev, q):
    H = ev.H
    kind = q[0]
    e = q[1]
    if kind == "valid":
        return bool(ev.V[e]), H.acts[e]["tau"]
    if kind == "auth":
        return ev.auth(e) == "T", H.acts[e]["tau"]          # [F-29]
    if kind == "current":
        t = _num(q[2])
        if H.acts[e]["kind"] != "ADOPT":
            return False, t                                  # [F-30]
        return bool(solve_current(ev, t)[e]), t
    if kind == "elig":
        g, _ = _elig(ev, e, q[2], q[3])
        return g.startswith("ELIGIBLE"), H.acts[e]["tau"]
    raise SpecError("unknown query %r" % (q,))


def _check_q(q):
    if not isinstance(q, (list, tuple)) or not q:
        raise SpecError("bad query")
    n = {"valid": 2, "auth": 2, "current": 3, "elig": 4}.get(q[0])
    if n is None or len(q) != n:
        raise SpecError("bad query %r" % (q,))


def rec_explain(entries, k, q, theta, rparams, now):
    """Returns (verdict, reason, worlds_seen)."""
    th = check_theta(theta)
    rp = check_rparams(rparams)
    _check_q(q)
    if now is None:
        raise SpecError("missing now")
    nowk = now(k) if callable(now) else now
    Ek = _entries_upto(entries, k)
    if q[1] not in {en["act"].get("id") for en in Ek if en.get("act")}:
        return "U", "subject not in E_k", 0
    thrT = nowk - rp["lam_pos"]
    thrF = nowk - rp["lam_neg"]
    count = 0
    allT = allF = True
    for H, forced, base in gen_worlds(Ek, th, rp):
        count += 1
        if count > rp["B"]:
            return "U", "bound", count
        if not allT and not allF:
            continue    # verdict is already U; keep counting so 'bound' is reported literally
        ev = base if base is not None else solve_valid(H, forced)
        phi, tphi = _phi(ev, q)
        allT = allT and (tphi <= thrT and phi)
        allF = allF and (tphi <= thrF and not phi)
    if count == 0:
        return "U", "W_k empty", 0
    if allT:
        return "T", "all worlds", count
    if allF:
        return "F", "all worlds", count
    return "U", "otherwise", count


def rec(entries, k, q, theta, rparams, now):
    return rec_explain(entries, k, q, theta, rparams, now)[0]


def count_worlds(entries, k, theta, rparams, cap=10 ** 6):
    th = check_theta(theta)
    rp = check_rparams(rparams)
    n = 0
    for _ in gen_worlds(_entries_upto(entries, k), th, rp):
        n += 1
        if n >= cap:
            break
    return n


# --------------------------------------------------------------------------
# S-7 operational layer
# --------------------------------------------------------------------------
def _in_Ek(entries, k, eid):
    return eid in {en["act"].get("id") for en in _entries_upto(entries, k) if en.get("act")}


def eff(entries, k, eid, theta, rparams, now, proc):
    if proc is None:
        raise SpecError("Proc is a required argument")
    return _in_Ek(entries, k, eid) and rec(entries, k, ["valid", eid], theta, rparams, now) == "T" \
        and bool(proc(k, eid))


def eff_obs(entries, k, eid, proc):
    if proc is None:
        raise SpecError("Proc is a required argument")
    return _in_Ek(entries, k, eid) and bool(proc(k, eid))


def usable_from(entries, eid, theta, rparams, now, proc):
    kmax = max([en["pos"] for en in entries] + [0])
    for k in range(1, kmax + 1):
        if eff(entries, k, eid, theta, rparams, now, proc):
            return k
    return None   # [F-34] min of the empty set


# --------------------------------------------------------------------------
# S-8 dated questions
# --------------------------------------------------------------------------
def rec_at_date(entries, d, wit_pos, wit_neg, q, theta, rparams):
    if wit_pos is None or wit_neg is None:
        raise SpecError("wit_pos and wit_neg are required (may be empty lists)")
    kmax = max([en["pos"] for en in entries] + [0])
    lo = max([p for (d2, p) in wit_pos if d2 <= d] + [0])
    hi = min([p - 1 for (d2, p) in wit_neg if d2 >= d] + [kmax])
    if lo > hi:
        return "CONFLICT"
    vals = {rec(entries, k, q, theta, rparams, d) for k in range(lo, hi + 1)}
    return vals.pop() if len(vals) == 1 else "U"

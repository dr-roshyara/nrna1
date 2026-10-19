"""Independent reference oracle for ALM-R1-SPEC.md, written from the SPEC TEXT.

Independence measures (and limits):
- does NOT import alm_r1; acts are plain dicts;
- validity is found by BRUTE-FORCE search for all fixed points of the S-4 equations over
  every assignment V in {0,1}^n (not by recursion along an order), so Th-1's uniqueness is
  itself tested rather than assumed;
- same author as the implementation (limit: shared misreadings of the spec are possible;
  the independent reviewer is the stronger check).
"""
from itertools import product

INF = float("inf")


def _cov(S, atom):
    return atom in S or (atom[0], "*") in S or ("*", "*") in S


def _inp(conv, t, p):
    return p[0] <= t <= p[1] if conv == "closed" else p[0] <= t < p[1]


class Oracle:
    def __init__(self, acts, th, rho=None):
        """acts: list of dicts (fields as in the spec). th: dict of Theta fields."""
        self.A = {a["id"]: a for a in acts}
        self.L = list(self.A)
        self.th = th
        self.rho = rho or {}

    # S-3 / S-4.7
    def before(self, f, e_or_t, is_time=False):
        a = self.A[f]
        if is_time:
            ok, s = a["tau"] <= e_or_t, e_or_t
        else:
            e = self.A[e_or_t]
            if self.th["C10"] == "given":
                ok = (a["tau"], a["ord"]) < (e["tau"], e["ord"])
            else:
                ok = a["tau"] < e["tau"]
            s = e["tau"]
        if ok and self.th["C1"] == "a":
            r = self.rho.get(f)
            ok = r is not None and r <= s
        return ok

    @staticmethod
    def _per(a):
        # spec S-2: an unstated grant period is [tau, inf)
        return a["period"] if a["period"] is not None else (a["tau"], INF)

    def wellformed(self, e):
        a = self.A[e]
        k = a["kind"]
        if k == "GRANT":
            return self._per(a)[0] >= a["tau"]
        if k in ("REVOKE", "CONFIRM"):
            t = a["target"]
            return t in self.A and self.A[t]["kind"] == "GRANT" and self.before(t, e)
        if k == "END":
            t = a["target"]
            return t in self.A and self.A[t]["kind"] == "ADOPT" and self.before(t, e)
        return k in ("ADOPT", "PERFORM", "ACCEPT")

    def needs(self, e):
        a = self.A[e]
        k = a["kind"]
        if k == "GRANT":
            return [("GRANT", x) for x in a["scope"]]
        if k in ("REVOKE", "CONFIRM"):
            return [(k, a["target"])]
        if k == "ADOPT":
            return [("ADOPT", a["subject"])]
        if k == "END":
            return [("END", self.A[a["target"]]["subject"])]
        if k == "PERFORM":
            return [("PERFORM", (a["activity"], a["work"]))]
        return [("ACCEPT", a["work"])]

    # equations, parameterized by an assignment V (dict id -> bool)
    def _basis(self, V, e):
        a = self.A[e]
        if not self.wellformed(e):
            return set()
        ns = self.needs(e)
        out = set()
        rs = {x for (h, x) in self.th["root"] if h == a["holder"]}
        if ns and all(_cov(rs, n) for n in ns):
            out.add("ROOT")
        for g in self.L:
            G = self.A[g]
            if g != e and G["kind"] == "GRANT" and G["grantee"] == a["holder"] \
                    and all(_cov(G["scope"], n) for n in ns) and self._alive(V, g, e, False):
                out.add(g)
        return out

    def _alive(self, V, g, x, is_time, depth=0):
        if depth > 20:
            return False
        G = self.A[g]
        if not V[g] or not self.before(g, x, is_time):
            return False
        t = x if is_time else self.A[x]["tau"]
        if not _inp(self.th["iota_g"], t, self._per(G)):
            return False
        if any(self.A[r]["kind"] == "REVOKE" and self.A[r]["target"] == g and V[r]
               and self.before(r, x, is_time) for r in self.L):
            return False
        if G["proviso"] and not any(self.A[c]["kind"] == "CONFIRM" and self.A[c]["target"] == g
                                    and V[c] and self.before(c, x, is_time) for c in self.L):
            return False
        if self.th["delta"] == "dependent":
            pb = self._basis(V, g)
            if "ROOT" not in pb and not any(self._alive(V, p, x, is_time, depth + 1) for p in pb):
                return False
        return True

    def _restricted(self, V, e):
        a = self.A[e]
        return a["kind"] == "ACCEPT" and any(
            self.A[f]["kind"] == "PERFORM" and self.A[f]["activity"] == "implement"
            and self.A[f]["work"] == a["work"] and self.A[f]["holder"] == a["holder"]
            and V[f] and self.before(f, e) for f in self.L)

    def _rhs(self, V, e):
        if self.th["C1"] == "a" and self.rho.get(e) is None:
            return False
        return bool(self._basis(V, e)) and not self._restricted(V, e)

    def fixed_points(self):
        sols = []
        for bits in product([False, True], repeat=len(self.L)):
            V = dict(zip(self.L, bits))
            if all(V[e] == self._rhs(V, e) for e in self.L):
                sols.append(V)
        return sols

    def current(self, V, c, t):
        a = self.A[c]
        if a["kind"] != "ADOPT" or not V[c] or a["tau"] > t or not _inp(self.th["iota_c"], t, a["cperiod"]):
            return False
        if any(self.A[x]["kind"] == "END" and self.A[x]["target"] == c and V[x]
               and self.before(x, t, True) for x in self.L):
            return False
        if self.th["RD1"][a["ckind"]] == "cascade":
            b = self._basis(V, c)
            if "ROOT" not in b:
                al = [self._alive(V, g, t, True) for g in b]
                if not al or not (any(al) if self.th["BASIS"] == "any" else all(al)):
                    return False
        if self.th["C6"] == "later_prevails":
            for x in self.L:
                X = self.A[x]
                if X["kind"] == "ADOPT" and X["subject"] == a["subject"] and x != c and V[x] \
                        and self.before(c, x) and self.before(x, t, True):
                    return False
        return True


def to_dict(act):
    """Neutral conversion from any object with the spec's field names."""
    return {k: getattr(act, k) for k in ("id", "kind", "holder", "performer", "tau", "ord", "grantee",
                                         "scope", "period", "proviso", "bars", "target", "subject",
                                         "ckind", "cperiod", "activity", "work")}


def theta_dict(th):
    return {k: getattr(th, k) for k in ("C1", "RD1", "delta", "BASIS", "C10", "iota_g", "iota_c",
                                        "C6", "BAR", "root")}
